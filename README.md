# connect
A non intrusive touch screen messaging based intercom system

# Raspi settings:
Standard LAMP install on raspbian buster

Install Clutter:
sudo apt install unclutter

Edit Autostart file:
sudo nano /etc/xdg/lxsession/LXDE-pi/autostart

Add this to the bottom of that file:

/usr/bin/chromium-browser --kiosk --disable-restore-session-state --check-for-update-interval=1 --simulate-critical-update 127.0.0.1/messages

unclutter -idle 0

@xset s off
@xset -dpms
