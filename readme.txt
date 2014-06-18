=== Uptime Robot ===
Contributors: brianwelch
Tags: Uptime Robot, dashboard, widget, monitoring
Donate link: http://utopianthemes.com/downloads/uptime-robot/
Requires at least: 3.7
Tested up to: 4.0
Stable tag: trunk
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A simple Wordpress dashboard widget that shows you the current uptime stats of your Uptime Robot monitored websites.

== Description ==
This plugin will allow you to enter your Uptime Robot API key and will provide a dashboard widget that will show you each of your current monitors, the monitor ID, their type, ratio and will provide a link to each of the sites in question.

*Each listing is also color coded visually depending on its status, providing much needed information at a quick glance.*


== Installation ==
Either upload the zip in the plugins manager, or install using the WordPress dashboard and activate the plugin.
Once you have activated the plugin, visit the Uptime Robot settings submenu, and enter your Uptime Robot API key, and save your changes.

The widget and monitor listing will be displayed on your WordPress dashboard.

== Frequently Asked Questions ==
* Q: Do I need to sign up for Uptime Robot to get an API key?
* A: Yes.  You will not be able to obtain an API key otherwise.


* Q: What is Uptime Robot?
* A: Uptime Robot provides real time site monitoring for multiple different types of connections and data types.


* Q: Does Uptime Robot cost anything?
* A: No.  They have free accounts and options.


* Q: Are you affiliated with Uptime Robot?
* A: No.  I just thought others may enjoy this plugin.


* Q: Does it cost anything to use this plugin?
* A: No.  However, you may donate to help fund further development if you find the plugin useful.

== Screenshots ==
1. Dashboard Widget
2. Widget Settings

== Changelog ==
**v1.0.4**
* Fix API key sanitization.


**v1.0.3**
* Code and CSS cleanup.  Revised tooltip function so that tooltips are displayed only on monitored ports.  Updated screenshot.


**v1.0.2**
* Add tooltips to show the port being monitored if the monitor subtype is set to "Port."


**v1.0.1**
* Fix error with loading CSS file if plugin directory name was changed.


**v1.0.0**
* Initial Version

== Upgrade Notice ==
This is a critical update. Upgrading to 1.0.4 fixes a security issue by properly sanitizing the API Key setting.