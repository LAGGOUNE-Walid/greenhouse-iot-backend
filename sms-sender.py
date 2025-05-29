#!/usr/bin/python

import RPi.GPIO as GPIO
import serial
import time
import sys

# Check for arguments
if len(sys.argv) != 3:
    print("Usage: python3 sms.py <phone_number> <message>")
    sys.exit(1)

phone_number = sys.argv[1]
text_message = sys.argv[2]

ser = serial.Serial("/dev/ttyS0", 115200)
ser.flushInput()

power_key = 6
rec_buff = ''

def send_at(command, back, timeout):
    rec_buff = ''
    ser.write((command + '\r\n').encode())
    time.sleep(timeout)
    if ser.inWaiting():
        time.sleep(0.01)
        rec_buff = ser.read(ser.inWaiting())
    if back not in rec_buff.decode(errors='ignore'):
        print(command + ' ERROR')
        print(command + ' back:\t' + rec_buff.decode(errors='ignore'))
        return 0
    else:
        print(rec_buff.decode(errors='ignore'))
        return 1

def SendShortMessage(phone_number, text_message):
    print("Setting SMS mode...")
    send_at("AT+CMGF=1", "OK", 1)
    print("Sending Short Message")
    answer = send_at('AT+CMGS="' + phone_number + '"', ">", 2)
    if answer == 1:
        ser.write(text_message.encode())
        ser.write(b'\x1A')  # Ctrl+Z to send
        answer = send_at('', 'OK', 20)
        if answer == 1:
            print('Sent successfully')
        else:
            print('Send failed')
    else:
        print('Command rejected')

def ReceiveShortMessage():
    rec_buff = ''
    print('Setting SMS mode...')
    send_at('AT+CMGF=1', 'OK', 1)
    send_at('AT+CPMS="SM","SM","SM"', 'OK', 1)
    answer = send_at('AT+CMGR=1', '+CMGR:', 2)
    if answer == 1:
        answer = 0
        if 'OK' in rec_buff:
            answer = 1
            print(rec_buff)
    else:
        print('Read failed')
        return False
    return True

def power_on(power_key):
    print('SIM7600X is starting:')
    GPIO.setmode(GPIO.BCM)
    GPIO.setwarnings(False)
    GPIO.setup(power_key, GPIO.OUT)
    time.sleep(0.1)
    GPIO.output(power_key, GPIO.HIGH)
    time.sleep(2)
    GPIO.output(power_key, GPIO.LOW)
    time.sleep(20)
    ser.flushInput()
    print('SIM7600X is ready')

def power_down(power_key):
    print('SIM7600X is logging off:')
    GPIO.output(power_key, GPIO.HIGH)
    time.sleep(3)
    GPIO.output(power_key, GPIO.LOW)
    time.sleep(18)
    print('Good bye')

try:
    power_on(power_key)
    print('Sending SMS to:', phone_number)
    SendShortMessage(phone_number, text_message)
    print('Receive SMS test:\n')
    print('Please send message to phone ' + phone_number)
    ReceiveShortMessage()
    power_down(power_key)
except Exception as e:
    print("Error:", e)
    if ser:
        ser.close()
    GPIO.cleanup()
