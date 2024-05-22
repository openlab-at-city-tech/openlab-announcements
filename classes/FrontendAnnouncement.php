<?php
/**
 * Frontend Announcement.
 *
 * @package openlab-announcements
 */

namespace OpenLab\Announcements;

/**
 * Frontend Announcement.
 */
class FrontendAnnouncement {
	/**
	 * Announcement data.
	 *
	 * @var string[]
	 */
	protected $data = [
		'id'      => '',
		'content' => '',
		'active'  => '',
		'date'    => '',
	];

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct( $data = [] ) {
		$this->fill_announcement_data( $data );
	}

	/**
	 * Fills announcement data.
	 *
	 * @param string[] $data {
	 *   Announcement data.
	 *   @type string $id      ID.
	 *   @type string $content Content.
	 *   @type bool   $active  Whether the announcement is active.
	 *   @type string $date    Date.
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
