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
	 * @param array{ id:string, content:string, active:bool, date:string } $data Data to fill.
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
	 * Gets the announcement ID.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->data['id'];
	}

	/**
	 * Gets the announcement content.
	 *
	 * @return string
	 */
	public function get_content() {
		return $this->data['content'];
	}

	/**
	 * Gets the announcement active status.
	 *
	 * @return bool
	 */
	public function get_active() {
		return $this->data['active'];
	}

	/**
	 * Gets the announcement date.
	 *
	 * @return string
	 */
	public function get_date() {
		return $this->data['date'];
	}
}
