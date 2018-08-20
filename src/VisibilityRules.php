<?php
namespace Layered\IfWidget;

class VisibilityRules {

	public static function user(array $rules) {
		global $wp_roles;


		// Rule - user auth state
		$rules['user-logged-in'] = [
			'name'		=>	__('User %s logged in', 'if-widget'),
			'callback'	=>	'is_user_logged_in',
			'group'		=>	__('User', 'if-widget')
		];


		// Rule - user roles
		$roleOptions = array_map(function($role) {
			return $role['name'];
		}, $wp_roles->roles);

		$rules['user-role'] = [
			'name'		=>	__('User role %s one of %s', 'if-widget'),
			'type'		=>	'multiple',
			'options'	=>	$roleOptions,
			'callback'	=>	function(array $roles) {
				global $current_user;
				return is_user_logged_in() && count(array_intersect($roles, $current_user->roles));
			},
			'group'		=>	__('User', 'if-widget')
		];

		if (defined('WP_ALLOW_MULTISITE') && WP_ALLOW_MULTISITE === true) {
			$rules['user-is-super-admin'] = [
				'name'		=>	__('User %s Super Admin', 'if-widget'),
				'callback'	=>	'is_super_admin',
				'group'		=>	__('User', 'if-widget')
			];
		}

		return $rules;
	}

	public static function page(array $rules) {

		// Visibility Rule - Post types
		$postTypes = array_map(function($postType) {
			return $postType->labels->singular_name;
			return $postType->labels->name;
		}, get_post_types([], 'objects'));

		$rules['post-type'] = [
			'name'		=>	__('Post type %s one of %s', 'if-widget'),
			'type'		=>	'multiple',
			'options'	=>	$postTypes,
			'callback'	=>	function(array $postTypes) {
				global $post;
				return isset($post, $post->post_type) && in_array($post->post_type, $postTypes);
			},
			'group'		=>	__('Page type', 'if-widget')
		];


		// Visibility Rule - Page types
		$rules['page-type'] = [
			'name'		=>	__('Page %s %s', 'if-widget'),
			'type'		=>	'select',
			'options'	=>	[
				'front_page'	=>	__('Front Page', 'if-widget'),
				'home'			=>	__('Blog Page', 'if-widget')
			],
			'callback'	=>	function($selected) {
				return $selected === 'front_page' ? is_front_page() : is_home();
			},
			'group'		=>	__('Page type', 'if-widget')
		];

		return $rules;
	}

	public static function url(array $rules) {

		// Visibility Rule - URL match
		$rules['url'] = [
			'name'			=>	__('URL %s %s', 'if-widget'),
			'type'			=>	'text',
			'placeholder'	=>	__('g.co/about', 'if-widget'),
			'callback'		=>	function() {
				return 'http' . (is_ssl() ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			},
			'group'			=>	__('URL', 'if-widget')
		];

		return $rules;
	}

	public static function device(array $rules) {

		// Visibility Rule - Is mobile
		$rules['is-mobile'] = [
			'name'			=>	__('Device %s mobile', 'if-widget'),
			'callback'		=>	'wp_is_mobile',
			'group'			=>	__('Device', 'if-widget')
		];

		return $rules;
	}

}
