# sms-api.py

from flask import Flask, request, jsonify
import RPi.GPIO as GPIO
import serial
import time

app = Flask(__name__)

ser = serial.Serial("/dev/ttyS0", 115200)
ser.flushInput()
power_key = 6

def send_at(command, expected, timeout):
    rec_buff = b''
    if command:
        ser.write((command + '\r\n').encode())
    time.sleep(timeout)
    if ser.inWaiting():
        time.sleep(0.01)
        rec_buff = ser.read(ser.inWaiting())
    decoded = rec_buff.decode(errors='ignore')
    success = expected in decoded
    return success, decoded.strip()

def send_sms(phone_number, text_message):
    GPIO.setmode(GPIO.BCM)
    GPIO.setwarnings(False)
    GPIO.setup(power_key, GPIO.OUT)

    # Power on SIM module
    GPIO.output(power_key, GPIO.HIGH)
    time.sleep(2)
    GPIO.output(power_key, GPIO.LOW)
    time.sleep(20)
    ser.flushInput()

    log = []

    ok, response = send_at("AT+CMGF=1", "OK", 1)
    log.append({"command": "AT+CMGF=1", "response": response, "success": ok})
    if not ok:
        return False, log

    ok, response = send_at(f'AT+CMGS="{phone_number}"', ">", 2)
    log.append({"command": f'AT+CMGS="{phone_number}"', "response": response, "success": ok})
    if not ok:
        return False, log

    ser.write(text_message.encode())
    ser.write(b'\x1A')  # Ctrl+Z
    ok, response = send_at('', 'OK', 20)
    log.append({"command": "[message body + Ctrl+Z]", "response": response, "success": ok})

    return ok, log

@app.route("/send-sms", methods=["POST"])
def handle_sms():
    data = request.get_json()
    phone = data.get("phone")
    message = data.get("message")
    if not phone or not message:
        return jsonify({"status": "error", "message": "Missing phone or message"}), 400

    success, log = send_sms(phone, message)
    return jsonify({
        "status": "ok" if success else "fail",
        "log": log
    })

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5005)
