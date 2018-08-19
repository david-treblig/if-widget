<?php
namespace Layered\IfWidget;

class WidgetVisibility {

	public static function start() {
		return new static;
	}

	protected function __construct() {
		add_action('admin_enqueue_scripts', [$this, 'assets']);
		add_action('in_widget_form', [$this, 'form'], 1, 3);
	}

	public function assets() {
		global $pagenow;

		if ($pagenow === 'widgets.php') {
			wp_enqueue_script('vuejs', 'https://cdn.jsdelivr.net/npm/vue@2.5', [], '2.5');
			wp_enqueue_script('v-runtime-template', plugins_url('assets/v-runtime-template.min.js', dirname(__FILE__)), ['vuejs'], '1.5.1');
			wp_enqueue_script('sprintf', plugins_url('assets/sprintf.min.js', dirname(__FILE__)), [], '1.1.1');
			wp_enqueue_script('if-widget', plugins_url('assets/if-widget.js', dirname(__FILE__)), ['vuejs', 'sprintf'], '0.1');
			wp_localize_script('if-widget', 'ifWidget', [
				'rules'		=>	apply_filters('if_visibility_rules', []),
				'texts'		=>	[
					'is'			=>	__('is', 'if-widget'),
					'is not'		=>	__('is not', 'if-widget'),
					'etc'			=>	__('etc', 'if-widget'),
					'equals'		=>	__('equals', 'if-widget'),
					'starts with'	=>	__('starts with', 'if-widget'),
					'contains'		=>	__('contains', 'if-widget'),
					'equals'		=>	__('equals', 'if-widget'),
					'not equal'		=>	__('not equal', 'if-widget'),
					'select'		=>	__('select', 'if-widget')
				]
			]);

			wp_enqueue_style('if-widget', plugins_url('assets/if-widget.css', dirname(__FILE__)), ['wp-jquery-ui-dialog'], '0.1');
		}
	}

	public function form(\WP_Widget $widget, $return, array $instance) {
		$rules = apply_filters('if_visibility_rules', []);

		$visibility = isset($instance['if-widget']) && $instance['if-widget'];
		$visibilityRules = $visibility ? $instance['if-widget'] : [[
			'type'		=>	'rule',
			'rule'		=>	'user-logged-in',
			'values'	=>	[1],
			'isOpen'	=>	false
		]];
		?>

		<hr class="if-widget-line">

		<div class="if-widget-visibility-rules" id="if-widget-visibility-rules-<?php echo $widget->id ?>">
			<p>
				<label><input type="checkbox" name="<?php echo $widget->get_field_name('if-widget') ?>" class="if-widget-is-enabled" <?php checked($visibility) ?> v-model="enabled"> <?php _e('Show widget only if »', 'if-widget') ?></label>
			</p>

			<div v-if="enabled">
				<div v-for="(v, index) in visibility">
					<div v-if="v.type === 'rule'" class="if-widget-visibility-rule" :class="{'is-open': v.isOpen}">
						<span class="change" @click="v.isOpen = !v.isOpen"><span class="dashicons dashicons-arrow-down-alt2"></span></span>
						<span class="remove" v-show="visibility.length > 1" @click="visibility.splice(Math.max(index - 1, 0), 2)">-</span>
						<v-runtime-template :template="formatRule(v.rule, index)"></v-runtime-template>
						<ul class="options">
							<li v-for="(rule, ruleId) in rules" :class="{selected: v.rule == rule}" v-html="formatName(rule)" @click="setRule(v, rule)"></li>
						</ul>
					</div>

					<div v-if="v.type === 'op'" class="if-widget-visibility-rule-op">
						<span :class="{selected: v.op == 'and'}" @click="v.op = 'and'"><?php _e('and', 'if-widget') ?></span>
						<span :class="{selected: v.op == 'or'}" @click="v.op = 'or'"><?php _e('or', 'if-widget') ?></span>
					</div>
				</div>

				<div class="if-widget-visibility-rule-op">
					<span @click="addOp()">+</span>
				</div>
			</div>

			<input type="text" name="<?php echo $widget->get_field_name('if-widget-visibility') ?>" class="if-widget-the-rules" value='<?php echo json_encode($visibilityRules) ?>' v-model="vis">
		</div>

		<?php
	}

}
