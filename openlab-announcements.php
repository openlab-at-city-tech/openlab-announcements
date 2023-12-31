<?php
/**
 * Plugin Name: OpenLab Announcements
 * Plugin URI: https://openlab.citytech.cuny.edu
 * Description: A plugin to display announcements on the OpenLab.
 * Version: 1.0
 * Author: OpenLab at City Tech
 * Author URI: https://openlab.citytech.cuny.edu
 * License: GPL3
 * Network: true
 *
 * @package openlab-announcements
 */

namespace OpenLab\Announcements;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/constants.php';

/**
 * Bootstrap the plugin.
 */
App::init();
