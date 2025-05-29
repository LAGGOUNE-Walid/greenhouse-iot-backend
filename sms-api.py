# sms-api.py

from flask import Flask, request, jsonify
import RPi.GPIO as GPIO
import serial
import time

app = Flask(__name__)

ser = serial.Serial("/dev/ttyS0", 115200)
ser.flushInput()
power_key = 6

def send_at(command, back, timeout):
    rec_buff = ''
    ser.write((command + '\r\n').encode())
    time.sleep(timeout)
    if ser.inWaiting():
        time.sleep(0.01)
        rec_buff = ser.read(ser.inWaiting())
    return back in rec_buff.decode(errors='ignore')

def send_sms(phone_number, text_message):
    GPIO.setmode(GPIO.BCM)
    GPIO.setwarnings(False)
    GPIO.setup(power_key, GPIO.OUT)
    # Power on
    GPIO.output(power_key, GPIO.HIGH)
    time.sleep(2)
    GPIO.output(power_key, GPIO.LOW)
    time.sleep(20)
    ser.flushInput()

    send_at("AT+CMGF=1", "OK", 1)
    if send_at(f'AT+CMGS="{phone_number}"', ">", 2):
        ser.write(text_message.encode())
        ser.write(b'\x1A')
        if send_at('', 'OK', 20):
            return True
    return False

@app.route("/send-sms", methods=["POST"])
def handle_sms():
    data = request.get_json()
    phone = data.get("phone")
    message = data.get("message")
    if not phone or not message:
        return jsonify({"status": "error", "message": "Missing phone or message"}), 400

    success = send_sms(phone, message)
    return jsonify({"status": "ok" if success else "fail"})

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5005)
