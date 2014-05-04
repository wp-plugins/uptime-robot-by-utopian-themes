<?php
/**
 * Plugin Name: Uptime Robot
 * Plugin URI: http://www.utopianthemes.com/downloads/uptime-robot/
 * Description: A simple Wordpress dashboard widget that shows you the current uptime stats of your Uptime Robot monitored websites.
 * Version: 1.0.1
 * Author: Brian Welch
 * Author URI: http://www.utopianthemes.com/
 * Requires at least: 3.7
 * Tested up to: 4.0
 * License: GPLv3
 *
 * Text Domain: uptime-robot
 * Domain Path: /lang/
 *
 * @package Uptime Robot
 * @category Plugin
 * @author Brian Welch
 */

include_once( dirname( __FILE__ ).'/class-UptimeRobot.php' );

if( is_admin() )
    $uptime_robot = new UptimeRobot();
