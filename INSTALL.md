# Doormatic Install
...

# Doormatic Setup
- Copy "door" folder into /var/local/
- Create database and set up user
- Add an entry to /etc/rc.local with this line: /usr/bin/python /var/local/door/bin/doorOpener.py &
**INFO: Must be added BEFORE line with exit 0**

