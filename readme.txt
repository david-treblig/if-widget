=== If Widget - Visibility control for Widgets ===
Contributors: andreiigna
Tags: widget, visibility, rules, roles, hide, if, show, display
Requires at least: 4
Tested up to: 5.1
Requires PHP: 5.4
Stable tag: trunk
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Display tailored widgets to each visitor with visibility rules

== Description ==

Control what widgets your site's visitors see, based on visibility rules. Here are a few examples:

* Display a widget only if current `User is logged in`
* Hide widgets if `Device is mobile`
* Display widgets for `Admins and Editors`
* Hide Login or Register widgets for `Logged in Users`

The plugin is easy to use, each widget will have a new option “Show widget only if” which will enable the selection of rules (example in Screenshots)

## Features

* Basic set of visibility rules
  * User state `User is logged in`
  * User roles `Admin` `Editor` `Author` etc
  * Page type `Front page` `Blog page`
  * Post type `Post` `Page` `Product` etc
  * Visitor device `Is Mobile`
  * Current URL contains or ends with word `your-product`
* Multiple rules - mix multiple rules for a widget visibility
  * show if `User is logged in` AND `Device is mobile`
  * show if `User is Admin` AND `Is Front page`
* Support for adding custom visibility rules

Example of adding a new visibility rule is described in the FAQ section

== Frequently Asked Questions ==

= How can I enable custom visiblity for a widget? =

On Widgets editing page, each widget will have a section for controlling visibility. Enable the option "Show widget only if" to reveal and configure visibility rules (Example in screenshots).

= How can I add a custom visibility rule for menu items? =

New rules can be added by any other plugin or theme.

Example of adding a new custom rule for displaying/hiding a widget when current page is a custom-post-type.

`
// theme's functions.php or plugin file
add_filter('if_visibility_rules', 'my_new_visibility_rule');

function my_new_visibility_rule($rules) {

  $rules['single-my-custom-post-type'] = array(
    'name'      =>  __('Single my-CPT', 'i18n-domain'),     // name of the condition
    'callback'  =>  function() {                            // callback - must return Boolean
      return is_singular('my-custom-post-type');
    }
  );

  return $rules;
}
`

= Where can I find conditional functions? =

WordPress provides [a lot of functions](http://codex.wordpress.org/Conditional_Tags) which can be used to create custom rules for almost any combination that a theme/plugin developer can think of.

== Screenshots ==

1. Enable of visibility rules for Widgets
2. Visibility rules

== Changelog ==

= 0.2 - 2 March 2019 =
* Updated - Plugin texts
* Updated - Compatibility with WordPress 5.1

= 0.1 =
* Plugin release. Includes basic visibility rules
