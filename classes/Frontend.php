<?php
/**
 * Frontend integration.
 *
 * @package openlab-announcements
 */

namespace OpenLab\Announcements;

/**
 * Frontend class.
 */
class Frontend {
	/**
	 * Private contstructor.
	 */
	private function __construct() {}

	/**
	 * Gets the singleton instance.
	 *
	 * @return \OpenLab\Announcements\Frontend
	 */
	public static function get_instance() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Initialize the frontend.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'openlab_member_under_avatar', array( $this, 'show_profile_announcements' ) );
		add_action( 'wp_ajax_openlab_announcements_hide_tip', array( $this, 'hide_tip_ajax_cb' ) );
	}

	/**
	 * Register assets.
	 *
	 * @return void
	 */
	public function register_assets() {
		wp_register_script(
			'openlab-announcements-frontend',
			OPENLAB_ANNOUNCEMENTS_PLUGIN_URL . 'build/frontend.js',
			[],
			OPENLAB_ANNOUNCEMENTS_VERSION,
			true
		);

		wp_register_style(
			'openlab-announcements-frontend',
			OPENLAB_ANNOUNCEMENTS_PLUGIN_URL . 'build/frontend.css',
			[],
			OPENLAB_ANNOUNCEMENTS_VERSION
		);
	}

	/**
	 * Show profile announcements.
	 *
	 * @return void
	 */
	public function show_profile_announcements() {
		if ( ! bp_is_my_profile() ) {
			return;
		}

		$user_id = bp_displayed_user_id();

		$currently_active_announcement = Data::get_active_frontend_announcement();
		if ( ! $currently_active_announcement ) {
			return;
		}

		if ( $this->has_user_dismissed_announcement( $user_id, $currently_active_announcement->get_id() ) ) {
			return;
		}

		wp_enqueue_script( 'openlab-announcements-frontend' );
		wp_enqueue_style( 'openlab-announcements-frontend' );

		?>

		<div class="openlab-tip" id="openlab-tip-<?php echo esc_attr( $currently_active_announcement->get_id() ); ?>">
			<div class="openlab-tip-header">
				<span class="fa fa-info-circle"></span>
				<?php esc_html_e( 'OpenLab Tip', 'openlab-announcements' ); ?>

				<button class="openlab-tip-close" data-announcement-id="<?php echo esc_attr( $currently_active_announcement->get_id() ); ?>">
					<span class="screen-reader-text"><?php esc_html_e( 'Close', 'openlab-announcements' ); ?></span>
					<span class="fa fa-times" aria-hidden="true"></span>
				</button>
			</div>

			<div class="openlab-tip-content">
				<?php echo wp_kses_post( $currently_active_announcement->get_content() ); ?>
			</div>

			<?php wp_nonce_field( 'openlab_announcements_hide_tip', 'openlab-tip-nonce' ); ?>
		</div>

		<?php
	}

	/**
	 * Determines whether a user has dismissed an announcement.
	 *
	 * @param int    $user_id         User ID.
	 * @param string $announcement_id Announcement ID.
	 * @return bool
	 */
	public function has_user_dismissed_announcement( $user_id, $announcement_id ) {
		// Super admins can ignore dismissed announcements.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( is_super_admin() && ! empty( $_GET['ignore_dismissed_announcements'] ) ) {
			return false;
		}

		$dismissed_announcements = get_user_meta( $user_id, 'openlab_announcements_dismissed', true );

		if ( ! is_array( $dismissed_announcements ) ) {
			$dismissed_announcements = [];
		}

		return in_array( $announcement_id, $dismissed_announcements, true );
	}

	/**
	 * AJAX callback for hiding a tip.
	 *
	 * @return void
	 */
	public function hide_tip_ajax_cb() {
		check_ajax_referer( 'openlab_announcements_hide_tip', 'nonce' );

		if ( ! is_user_logged_in() || ! isset( $_POST['announcementId'] ) ) {
			wp_send_json_error();
		}

		$user_id         = get_current_user_id();
		$announcement_id = sanitize_text_field( wp_unslash( $_POST['announcementId'] ) );

		$dismissed_announcements = get_user_meta( $user_id, 'openlab_announcements_dismissed', true );

		if ( ! is_array( $dismissed_announcements ) ) {
			$dismissed_announcements = [];
		}

		$dismissed_announcements[] = $announcement_id;

		update_user_meta( $user_id, 'openlab_announcements_dismissed', $dismissed_announcements );

		wp_send_json_success();
	}
}
