<?php
/**
 * Admin integration.
 *
 * @package openlab-announcements
 */

namespace OpenLab\Announcements;

/**
 * Admin integration.
 */
class Admin {
	/**
	 * Private constructor.
	 *
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Gets the singleton instance.
	 *
	 * @return \OpenLab\Announcements\Admin
	 */
	public static function get_instance() {
		static $instance;

		if ( empty( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Initializes WP hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'in_admin_footer', [ $this, 'news_panel' ], 20 );
		add_action( 'wp_ajax_openlab_announcements_hide_panel', [ $this, 'hide_panel_ajax_cb' ] );
	}

	/**
	 * Displays a welcome panel to introduce users to WordPress.
	 *
	 * @return void
	 */
	public function news_panel() {
		wp_enqueue_script(
			'openlab-announcements-admin',
			OPENLAB_ANNOUNCEMENTS_PLUGIN_URL . 'build/admin.js',
			[],
			OPENLAB_ANNOUNCEMENTS_VERSION,
			true
		);

		wp_enqueue_style(
			'openlab-announcements-admin',
			OPENLAB_ANNOUNCEMENTS_PLUGIN_URL . 'build/admin.css',
			[],
			OPENLAB_ANNOUNCEMENTS_VERSION
		);

		$panel_is_visible = $this->is_panel_visible_for_user();

		wp_add_inline_script(
			'openlab-announcements-admin',
			'const OpenLabAnnouncementsAdmin = ' . wp_json_encode(
				[
					'panelIsVisible' => (bool) $panel_is_visible,
				]
			) . ';',
			'before'
		);

		$can_customize  = current_user_can( 'customize' );
		$is_block_theme = wp_is_block_theme();

		$hidden_class = $panel_is_visible ? '' : ' hidden';

		$announcements = $this->get_announcements();

		?>
		<div id="openlab-news-panel" class="openlab-news-panel-content <?php echo esc_attr( $hidden_class ); ?>">
			<div class="panel-header">
				<div class="panel-header-content">
					<h2><span class="very-bold">OPEN</span>LAB News!</h2>
					<p>
						<a href="https://openlab.citytech.cuny.edu/about/">Learn more about the OpenLab</a>
					</p>
				</div>
			</div>

			<?php // phpcs:disable ?>
			<div class="panel-column-container">
				<?php foreach ( $announcements as $announcement ) : ?>
					<div class="panel-column">
						<div class="panel-icon">
							<i class="fa <?php echo esc_attr( $announcement->get( 'icon' ) ); ?>"></i>
						</div>

						<div class="welcome-panel-column-content">
							<h3><?php echo esc_html( $announcement->get( 'heading' ) ); ?></h3>
							<?php echo wp_kses_post( $announcement->get( 'content' ) ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php // phpcs:enable ?>

			<?php wp_nonce_field( 'openlab-news-panel-nonce', 'openlab-news-panel-nonce', false ); ?>
		</div>
		<?php
	}

	/**
	 * Gets a list of site users for whom the news panel is hidden.
	 *
	 * @param int $site_id Optional. Site ID. Defaults to current site.
	 * @return int[]
	 */
	public function get_hidden_users( $site_id = 0 ) {
		$site_hidden_users = get_blog_option( $site_id, 'openlab_announcements_hidden_users', [] );
		if ( ! is_array( $site_hidden_users ) ) {
			$site_hidden_users = [];
		}

		$site_hidden_users = array_map( 'intval', $site_hidden_users );

		return $site_hidden_users;
	}

	/**
	 * Determines whether the news panel should be visible for the current user.
	 *
	 * @param int $user_id Optional. User ID. Defaults to current user.
	 * @param int $site_id Optional. Site ID. Defaults to current site.
	 * @return bool
	 */
	public function is_panel_visible_for_user( $user_id = 0, $site_id = 0 ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( ! $site_id ) {
			$site_id = get_current_blog_id();
		}

		$panel_is_visible = ! in_array( $user_id, $this->get_hidden_users(), true );

		return (bool) $panel_is_visible;
	}

	/**
	 * Sets whether the news panel should be visible for the current user.
	 *
	 * @param int  $user_id Optional. User ID. Defaults to current user.
	 * @param bool $visible Optional. Whether the panel should be visible. Defaults to true.
	 * @return void
	 */
	public function set_is_panel_visible_for_user( $user_id = 0, $visible = true ) {
		$site_hidden_users = $this->get_hidden_users();

		if ( $visible ) {
			$site_hidden_users = array_diff( $site_hidden_users, [ $user_id ] );
		} else {
			$site_hidden_users[] = $user_id;
		}

		update_blog_option( get_current_blog_id(), 'openlab_announcements_hidden_users', $site_hidden_users );
	}

	/**
	 * AJAX callback to hide the news panel.
	 *
	 * @return void
	 */
	public function hide_panel_ajax_cb() {
		check_ajax_referer( 'openlab-news-panel-nonce', 'nonce' );

		$visible = isset( $_POST['visible'] ) && 'false' === $_POST['visible'] ? 0 : 1;

		$this->set_is_panel_visible_for_user( get_current_user_id(), (bool) $visible );
	}

	/**
	 * Gets a list of announcements to display in the Dashboard panel.
	 *
	 * @return DashboardAnnouncement[]
	 */
	public function get_announcements() {
		$announcements_data = [
			[
				'heading' => 'Accessibility',
				'content' => '<p>Make your work on the OpenLab accessible so it\'s easy for everyone to use and understand! Visit the <a href="https://openlab.citytech.cuny.edu/blog/help/help-category/accessibility-on-the-openlab/">Accessibility section</a> in OpenLab Help for step-by-step instructions, and <a href="https://openlab.citytech.cuny.edu/blog/help/editoria11y-accessibility-checker/">use the Editoria11y plugin</a> to check your site for accessibility.</p><p>Learn more in <a href="https://openlab.citytech.cuny.edu/blog/help/help-category/accessibility-on-the-openlab/">OpenLab Help</a>.',
				'icon'    => 'fa-universal-access',
			],
			[
				'heading' => 'New Feature: Add to Portfolio',
				'content' => '<p>It\'s easy to add the work you do in Courses, Projects, or Clubs to your Portfolio! Simply enable the <a href="https://openlab.citytech.cuny.edu/blog/help/add-to-my-portfolio/">Add to Portfolio</a> button and, in one click, add the posts, comments, or pages you\'ve created on other sites to your Portfolio!</p><p>Learn more in <a href="https://openlab.citytech.cuny.edu/blog/help/add-to-my-portfolio/">OpenLab Help</a>.</p>',
				'icon'    => 'fa-check-circle-o',
			],
			[
				'heading' => 'Help & Support',
				'content' => '<p>We\'re here to help! Check out the <a href="https://openlab.citytech.cuny.edu/blog/help/">OpenLab Help section</a> for step-by-step instructions. Reach out for email support at OpenLab@CityTech.cuny.edu. Sign up for <a href="https://openlab.citytech.cuny.edu/openroad/office-hours/">Open Hours</a>, a 30-minute Zoom session with a member of the OpenLab team.</p><p>Learn more in <a href="https://openlab.citytech.cuny.edu/blog/help/">OpenLab Help</a> and the <a href="https://openlab.citytech.cuny.edu/openroad/support/">Open Road</a>.</p>',
				'icon'    => 'fa-question-circle-o',
			],
		];

		$announcements = array_map(
			function ( $data ) {
				$announcement = new DashboardAnnouncement();
				$announcement->fill_announcement_data( $data );

				return $announcement;
			},
			$announcements_data
		);

		return $announcements;
	}
}
