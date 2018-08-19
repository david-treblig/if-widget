<?php
/*
Plugin Name: If Widget - Visibility control for widgets
Plugin URI: https://wordpress.layered.studio/if-widget
Description: Display customised widgets to each visitor with visibility rules
Version: 0.1
Text Domain: if-widget
Author: Layered
Author URI: https://layered.studio
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

require plugin_dir_path(__FILE__) . 'vendor/autoload.php';


// Prepare visibility rules (common in all IF plugins)

if (!function_exists('ifVisibilityRulesPrepare')) {
	function ifVisibilityRulesPrepare(array $rules) {
		return array_map(function(array $rule) {

			if (!isset($rule['type'])) {
				$rule['type'] = 'bool';
			}

			if (!isset($rule['group'])) {
				$rule['group'] = __('Other', 'if-widget');
			}

			return $rule;
		}, $rules);
	}
}


// start the plugin

add_filter('if_visibility_rules', 'ifVisibilityRulesPrepare', 500);
add_filter('if_visibility_rules', '\Layered\IfWidget\VisibilityRules::user');
add_filter('if_visibility_rules', '\Layered\IfWidget\VisibilityRules::page');
add_filter('if_visibility_rules', '\Layered\IfWidget\VisibilityRules::url');
add_filter('if_visibility_rules', '\Layered\IfWidget\VisibilityRules::device');

