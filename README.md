# connect
A non intrusive touch screen messaging based intercom system

# Raspi settings:
# Standard LAMP install on raspbian buster
# sudo apt install unclutter

Then edit:
sudo nano /etc/xdg/lxsession/LXDE-pi/autostart

Add this to the bottom of that file:

# full screen launch of app
/usr/bin/chromium-browser --kiosk --disable-restore-session-state --check-for-update-interval=1 --simulate-critical-update 127.0.0.1/messages

# hide mour pointer
unclutter -idle 0

# no sleep
@xset s off
@xset -dpms
