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
		add_action( 'welcome_panel', [ $this, 'news_panel' ], 20 );
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
		?>
		<div id="openlab-news-panel" class="openlab-news-panel-content <?php echo esc_attr( $hidden_class ); ?>">
			<div class="panel-header">
				<div class="panel-header-content">
					<h2><span class="very-bold">OPEN</span>LAB News!</h2>
					<p>
						<a href="<?php echo esc_url( admin_url( 'about.php' ) ); ?>">Learn more about the OpenLab</a>
					</p>
				</div>
			</div>

			<?php // phpcs:disable ?>
			<div class="panel-column-container">
				<div class="panel-column">
					<div class="welcome-panel-column-content">
						<h3><?php _e( 'Author rich content with blocks and patterns' ); ?></h3>
						<p><?php _e( 'Block patterns are pre-configured block layouts. Use them to get inspired or create new pages in a flash.' ); ?></p>
						<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=page' ) ); ?>"><?php _e( 'Add a new page' ); ?></a>
					</div>
				</div>
				<div class="panel-column">
					<div class="welcome-panel-column-content">
					<?php if ( $is_block_theme ) : ?>
						<h3><?php _e( 'Customize your entire site with block themes' ); ?></h3>
						<p><?php _e( 'Design everything on your site &#8212; from the header down to the footer, all using blocks and patterns.' ); ?></p>
						<a href="<?php echo esc_url( admin_url( 'site-editor.php' ) ); ?>"><?php _e( 'Open site editor' ); ?></a>
					<?php else : ?>
						<h3><?php _e( 'Start Customizing' ); ?></h3>
						<p><?php _e( 'Configure your site&#8217;s logo, header, menus, and more in the Customizer.' ); ?></p>
						<?php if ( $can_customize ) : ?>
							<a class="load-customize hide-if-no-customize" href="<?php echo wp_customize_url(); ?>"><?php _e( 'Open the Customizer' ); ?></a>
						<?php endif; ?>
					<?php endif; ?>
					</div>
				</div>
				<div class="panel-column">
					<div class="welcome-panel-column-content">
					<?php if ( $is_block_theme ) : ?>
						<h3><?php _e( 'Switch up your site&#8217;s look & feel with Styles' ); ?></h3>
						<p><?php _e( 'Tweak your site, or give it a whole new look! Get creative &#8212; how about a new color palette or font?' ); ?></p>
					<?php else : ?>
						<h3><?php _e( 'Discover a new way to build your site.' ); ?></h3>
						<p><?php _e( 'There is a new kind of WordPress theme, called a block theme, that lets you build the site you&#8217;ve always wanted &#8212; with blocks and styles.' ); ?></p>
						<a href="<?php echo esc_url( __( 'https://wordpress.org/documentation/article/block-themes/' ) ); ?>"><?php _e( 'Learn about block themes' ); ?></a>
					<?php endif; ?>
					</div>
				</div>
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
}
