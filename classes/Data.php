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
		$data = [
			[
				'id'      => '20240801-user-profiles',
				'content' => '<p>Click \'Edit Profile\' to check out the new privacy settings on your profile.</p>

<p>Learn more about <a href="https://openlab.citytech.cuny.edu/blog/help/privacy-on-the-openlab/">privacy on the OpenLab</a> in OpenLab Help.</p>',
				'active'  => true,
				'date'    => '2024-08-01',
			],
		];

		foreach ( $data as $_data ) {
			$announcement = new FrontendAnnouncement();
			$announcement->fill_announcement_data( $_data );

			$this->frontend_announcements[] = $announcement;
		}
	}

	/**
	 * Gets message for display on front end.
	 *
	 * @return \OpenLab\Announcements\FrontendAnnouncement|null
	 */
	public static function get_active_frontend_announcement() {
		$data = new self();

		foreach ( $data->frontend_announcements as $announcement ) {
			if ( $announcement->get_active() ) {
				return $announcement;
			}
		}

		return null;
	}
}
