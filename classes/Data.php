<?php
/**
 * Data class.
 *
 * @package openlab-announcements
 */

namespace OpenLab\Announcements;

/**
 * Data class.
 */
class Data {
	/**
	 * Frontend announcements.
	 *
	 * @var \OpenLab\Announcements\FrontendAnnouncement[]
	 */
	protected $frontend_announcements = [];

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->frontend_announcements = [
			new FrontendAnnouncement(
				[
					'id'      => '20240521-privacy',
					'content' => '<p>Here is a message about privacy.</p><p>Learn more about privacy.</p>',
					'active'  => true,
					'date'    => '2024-05-21',
				]
			),
		];
	}

	/**
	 * Gets message for display on front end.
	 *
	 * @return \OpenLab\Announcements\FrontendAnnouncement|null
	 */
	public static function get_active_frontend_announcement() {
		$data = new self();

		foreach ( $data->frontend_announcements as $announcement ) {
			if ( $announcement->get( 'active' ) ) {
				return $announcement;
			}
		}

		return null;
	}
}