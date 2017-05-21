import RPi.GPIO as GPIO
import time
import os.path
import stat

sleep_time = 4

open_door_flag_file = "/var/local/door/temp/opendoor.txt"
GPIO.setmode(GPIO.BCM)
GPIO.setup(18, GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(2, GPIO.OUT)
GPIO.output(2, GPIO.HIGH)

def file_age_in_seconds(pathname):
    return time.time() - os.stat(pathname)[stat.ST_MTIME]

def openDoor():
    try:
        #print("Opening door...")
        GPIO.output(2, GPIO.LOW)
        time.sleep(sleep_time);
        GPIO.output(2, GPIO.HIGH)
        #print("My work here is done.")
        return True
    except:
        #print("Couldn't make it to the opener...")
        return False


try:
    while True:
        inputValue = GPIO.input(18)
        if (inputValue == False):
            try:
                a = openDoor()
            #except:
            #    print("Error while trying to open the door. Button pressed")
            finally:
                time.sleep(0.2)
        elif os.path.isfile(open_door_flag_file):
            try:
                if file_age_in_seconds(open_door_flag_file) < 5:
                    openDoor()
            finally:
                os.remove(open_door_flag_file)
                time.sleep(0.2)
        else:
            time.sleep(0.3)
except KeyboardInterrupt:
    print "Quit"
    GPIO.cleanup()

