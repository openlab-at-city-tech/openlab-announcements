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
	 * @var array{
	 *   id:string,
	 *   content:string,
	 *   active:bool,
	 *   date:string
	 * }
	 */
	protected $data = [
		'id'      => '',
		'content' => '',
		'active'  => false,
		'date'    => '',
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
	 * @param array{
	 *   id:string,
	 *   content:string,
	 *   active:bool,
	 *   date:string
	 * } $data Data to fill.
	 * @return void
	 */
	public function fill_announcement_data( array $data ) {
		foreach ( $data as $key => $value ) {
			switch ( $key ) {
				case 'active':
					$this->data[ $key ] = (bool) $value;
					break;
				default:
					$this->data[ $key ] = (string) $value;
			}
		}
	}

	/**
	 * Gets announcement data.
	 *
	 * @param string $key Key to retrieve.
	 * @return mixed
	 */
	public function get( $key ) {
		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : '';
	}
}
