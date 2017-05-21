# doormatic
## Description
This project was created a few years ago with the purpose of managing the main door of a tiny private club with some friends.
For this, I created a LAMP service initially running inside an Arduino Yún with connected to a simple relay to a 12V doorlock and a client (HTTP(s) client with a POST) for Android.
Currently this code has suffered many transformations to adapt to be run inside a Raspberry Pi 2 plus a physical push button has been aded inside the club for door opening without any other security requirement but physical reach.

## Doormatic Server info
The service has the ability to manage devices and users plus a security user deactivation switch through a token in case of device loss.

### Connectivity
This service was meant to be a WiFi WPA2-Enterprise accesed service plus a «security account-lock» reachable through the Internet.

### User management
Users require to be created through service's main panel with the following fields:
- Login Name
- Password
- Email address (where account-lock link can be sent)
**INFO: Users can currently be created yet can't be deleted through the panel but disabled.

### Device management
Devices are managed with a name/description and their MAC address associated to an existing user.

### Security
#### Connectivity through LAN or WLAN
Use of WiFi with WPA2-Enterprise so you can lock-out specific users through this layer
#### Device's MAC Address
When a device requests openning, the service will send a 0.5-sec-timeout ICMP Echo request to the device and read ARP table to get that device's MAC address to check if in the list. Login credentials won't be check if this step failed.
#### Login credentials / User + Password
The service requires a username and password for authentication. It was planned to use an automated 2FA, yet never developed.
#### User-autolock
The service has the ability to lock a user through a "panic token" which can be sent to user through email so they can just tap/click on it and lock their account from anywhere if connectivity permits.

## Doormatic Client info
### Web client
Just target to your server's url (ex.: https://raspberrypi.local/door/)
### Android client
Currently there's an Android client at Google Play Store
https://play.google.com/store/apps/details?id=es.doormatic
### iOS client
A friend created an iOS version for this but it's not uploaded to the App Store yet.
