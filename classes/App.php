<?php
/**
 * App class.
 *
 * @package openlab-announcements
 */

namespace OpenLab\Announcements;

/**
 * App clas.
 */
class App {
	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public static function init() {
		if ( is_admin() ) {
			$admin = Admin::get_instance();
			$admin->init();
		}
	}
}
