<?php
/**
 * Plugin Name:       Uptime Robot
 * Plugin URI:        http://www.utopianthemes.com/downloads/uptime-robot/
 * Description:       A simple Wordpress dashboard widget that shows you the current uptime stats of your Uptime Robot monitored websites.
 * Version:           1.1.1
 * Author:            Brian Welch
 * Author URI:        http://briancwelch.com/
 * Requires at least: 3.7
 * Tested up to:      4.1
 * License:           GPLv3
 *
 * Text Domain:       uptime-robot
 * Domain Path:       /lang/
 *
 * @package           Uptime Robot
 * @category          Plugin
 * @author            Brian Welch
 */

include_once( dirname( __FILE__ ).'/class-uptime-robot.php' );
include_once( dirname( __FILE__ ).'/class-uptime-robot-widget.php' );

$uptime_robot = new UptimeRobot();
$uptime_robot_widget = new UptimeRobotWidget();
