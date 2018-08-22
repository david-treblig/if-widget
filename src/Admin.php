<?php
namespace Layered\IfWidget;

class Admin {

	public static function start() {
		return new static;
	}

	protected function __construct() {
		add_action('admin_enqueue_scripts', [$this, 'assets']);
		add_action('admin_init', [$this, 'actions']);
		add_action('admin_menu', [$this, 'menu']);
		add_action('admin_notices', [$this, 'notices']);
		add_filter('plugin_action_links_if-widget/if-widget.php', [$this, 'actionLinks']);
	}

	public function assets() {
		if (get_current_screen()->id === 'appearance_page_if-widget') {
			wp_enqueue_style('if-widget', plugins_url('assets/if-widget.css', dirname(__FILE__)), [], '0.1');
		}
	}

	public function actions() {

		if (isset($_POST['if-widget-action'])) {
			// do this
		}

	}

	public function menu() {
		add_submenu_page('themes.php', __('If Widget Options', 'if-widget'), __('If Widget', 'if-widget'), 'manage_options', 'if-widget', [$this, 'page']);
	}

	public function notices() {
		$notices = [];

		if (isset($_REQUEST['if-widget-alert'])) {
			$notices[] = [
				'type'			=>	isset($_REQUEST['alert-type']) ? $_REQUEST['alert-type'] : 'success',
				'message'		=>	$_REQUEST['if-widget-alert'],
				'dismissable'	=>	true
			];
		}

		foreach ($notices as $notice) {
			?>
			<div class="notice notice-<?php echo esc_attr($notice['type']) ?> <?php if (isset($notice['dismissable']) && $notice['dismissable'] === true) echo 'is-dismissible' ?>">
				<p><?php echo wp_kses($notice['message'], ['a' => ['href' => [], 'title' => []], 'strong' => []]) ?></p>
			</div>
			<?php
		}
	}

	public function actionLinks(array $links) {
		return array_merge([
			'settings'	=>	'<a href="' . menu_page_url('if-widget', false) . '">' . __('Settings', 'if-widget') . '</a>'
		], $links);
	}

	public function page() {
		$options = get_option('if-widget');
		?>

		<div class="wrap about-wrap if-widget-wrap">
			<h1>If Widget</h1>
			<p class="about-text"><?php printf(__('Thanks for using the %s plugin! Now you can display tailored widgets to each visitor, based on visibility rules. Here are a few examples:', 'if-widget'), '<strong>If Widget</strong>') ?></p>
			<hr class="wp-header-end">

			<div class="feature-section three-col">
				<div class="col">
					<div class="feature-box">
						<h3><span class="dashicons dashicons-admin-users"></span> <?php _e('By User', 'if-widget') ?></h3>
						<p><?php _e('Display or hide widgets based on user info:', 'if-widget') ?></p>
						<ul>
							<li><code><?php _e('Show widget if user is/isn\'t logged in', 'if-widget') ?></code></li>
							<li><code><?php _e('Show widget only for Admins/Editors', 'if-widget') ?></code></li>
							<li><code><?php _e('Show widget only for Super Admin (WP Multisite)', 'if-widget') ?></code></li>
						</ul>
					</div>
				</div>

				<div class="col">
					<div class="feature-box">
						<h3><span class="dashicons dashicons-admin-page"></span> <?php _e('By Page', 'if-widget') ?></h3>
						<p><?php _e('Display or hide widgets based on current page info:', 'if-widget') ?></p>
						<ul>
							<li><code><?php _e('Show widget only on Front Page', 'if-widget') ?></code></li>
							<li><code><?php _e('Show widget only on Blog Page', 'if-widget') ?></code></li>
							<li><code><?php _e('Show widget only on Posts or Events pages', 'if-widget') ?></code></li>
						</ul>
					</div>
				</div>

				<div class="col">
					<div class="feature-box">
						<h3><span class="dashicons dashicons-laptop"></span> <?php _e('By Browser', 'if-widget') ?></h3>
						<p><?php _e('Show widgets only for visitors like:', 'if-widget') ?></p>
						<ul>
							<li><code><?php _e('Show widget only when browsing from Mobile', 'if-widget') ?></code></li>
							<li><code><?php _e('Hide widget if current URL includes \'keyword\'', 'if-widget') ?></code></li>
						</ul>
					</div>
				</div>
			</div>

			<p><?php printf(__('Visibility rules can be added to widgets by activating the "%s" option when editing any widget.', 'if-widget'), '<strong>' . __('Show widget only if', 'if-widget') . '</strong>') ?></p>
			<br>
			<p class="if-widget-text-center">
				<a href="<?php echo admin_url('widgets.php') ?>" class="button button-primary"><?php _e('Manage Widgets', 'if-widget') ?></a>
			</p>

			<?php do_action('if_widget_page_content') ?>

			<br><br><br><br><hr>

			<p class="if-widget-text-right">
				<strong>If Widget</strong>:
				<a href="https://wordpress.org/plugins/if-widget/#faq" target="wpplugins"><?php _e('FAQs', 'if-widget') ?></a> &middot;
				<a href="https://wordpress.org/plugins/if-widget/#reviews" target="wpplugins"><?php _e('Reviews', 'if-widget') ?></a> &middot;
				<a href="https://wordpress.org/support/plugin/if-widget" target="wpplugins"><?php _e('Support', 'if-widget') ?></a>
			</p>
		</div>
		<?php
	}

}
