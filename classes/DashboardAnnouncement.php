<?php
/**
 * Dashboard Announcement.
 *
 * @package openlab-announcements
 */

namespace OpenLab\Announcements;

/**
 * Dashboard Announcement.
 */
class DashboardAnnouncement {
	/**
	 * Announcement data.
	 *
	 * @var string[]
	 */
	protected $data = [
		'heading'   => '',
		'content'   => '',
		'link_text' => '',
		'link_url'  => '',
		'icon'      => '',
	];

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Fills announcement data.
	 *
	 * @param string[] $data {
	 *   Announcement data.
	 *   @type string $heading   Heading.
	 *   @type string $content   Content.
	 *   @type string $link_text Link text.
	 *   @type string $link_url  Link URL.
	 *   @type string $icon      Font Awesome icon class.
	 * }
	 * @return void
	 */
	public function fill_announcement_data( array $data ) {
		foreach ( $data as $key => $value ) {
			if ( isset( $this->data[ $key ] ) ) {
				$this->data[ $key ] = $value;
			}
		}
	}

	/**
	 * Gets announcement data.
	 *
	 * @param string $key Key to retrieve.
	 * @return string
	 */
	public function get( $key ) {
		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : '';
	}
}
