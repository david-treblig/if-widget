jQuery(function($) {


	// Add Visibility Rules to elements
	function addVisibilityRules() {
		$('.widget-liquid-right .if-widget-visibility-rules:not(.has-if-widget)').each(function() {
			var $el = $(this).addClass('has-if-widget');

			new Vue({
				el: '#' + $el.attr('id'),
				components: {VRuntimeTemplate},
				data: {
					rules: ifWidget.rules,
					texts: ifWidget.texts,
					enabled: $el.find('.if-widget-is-enabled').prop('checked'),
					visibility: JSON.parse($el.find('.if-widget-the-rules').val())
				},
				created: function() {
					var vm = this;
					this.visibility.map(function(v) {
						if (v.type == 'rule') {
							v.rule = vm.rules[v.rule];
						}
						return v;
					});
					for (ruleId in this.rules) {
						this.rules[ruleId].id = ruleId;
					}
				},
				computed: {
					vis: function() {
						var q = JSON.parse(JSON.stringify(this.visibility));
						return JSON.stringify(q.map(function(v) {
							if (v.type == 'rule') {
								v.rule = v.rule.id;
								delete v.isOpen;
							}
							return v;
						}));
					}
				},
				methods: {
					translate: function(str) {
						if (!this.texts[str]) {
							console.log(str, 'needs translation');
						}
						return this.texts[str] || str;
					},
					formatName: function(rule) {
						var formattedText = rule.name;

						if (rule.type === 'bool') {
							formattedText = sprintf(rule.name, '<span class="type-op">' + this.translate('is') + '/' + this.translate('is not') + '</span>');
						} else if (rule.type === 'select' || rule.type === 'multiple') {
							var options = Object.values(rule.options);
							if (options.length > 2) {
								options = options.slice(0, 2);
								options.push('<i>' + this.translate('etc') + '</i>');
							}
							formattedText = sprintf(rule.name, '<span class="type-op">' + this.translate('is') + '/' + this.translate('is not') + '</span>', '<span class="type-value">' + options.join(options.length == 2 ? ' or ' : ', ') + '</span>');
						} else if (rule.type === 'text') {
							formattedText = sprintf(rule.name, '<span class="type-op">' + this.translate('equals') + '/' + this.translate('starts with') + '/' + this.translate('contains') + '</span>', '<span class="type-value">' + rule.placeholder + '</span>');
						}

						return formattedText;
					},
					formatRule: function(rule, index) {
						var formattedText = rule.name;

						if (rule.type === 'bool') {
							formattedText = sprintf(rule.name, '<select v-model="visibility[' + index + '].values[0]" class="type-op"><option value="1">' + this.translate('is') + '</option><option value="0">' + this.translate('is not') + '</option></select>');
						} else if (rule.type === 'select') {
							formattedText = sprintf(rule.name, '<select v-model="visibility[' + index + '].values[0]" class="type-op"><option value="1">' + this.translate('is') + '</option><option value="0">' + this.translate('is not') + '</option></select>', '<select v-model="visibility[' + index + '].values[1]" class="type-value"><option v-for="(label, val) in visibility[' + index + '].rule.options" :value="val">{{ label }}</option></select>');
						} else if (rule.type === 'text') {
							formattedText = sprintf(rule.name, '<select v-model="visibility[' + index + '].values[0]" class="type-op"><option value="1">' + this.translate('equals') + '</option><option value="0">' + this.translate('not equal') + '</option><option value="starts">' + this.translate('starts with') + '</option><option value="ends">' + this.translate('ends with') + '</option><option value="contains">' + this.translate('contains') + '</option></select>', '<input type="text" v-model="visibility[' + index + '].values[1]" :placeholder="visibility[' + index + '].rule.placeholder" required class="type-value" />');
						} else if (rule.type === 'multiple') {
							formattedText = sprintf(rule.name, '<select v-model="visibility[' + index + '].values[0]" class="type-op"><option value="1">' + this.translate('is') + '</option><option value="0">' + this.translate('is not') + '</option></select>', '<div class="multi-select"><span v-for="(opt, pos) in visibility[' + index + '].values[1]" @click="visibility[' + index + '].values[1].splice(pos, 1)" class="type-value">{{ visibility[' + index + '].rule.options[opt] }}</span><select class="type-value" :class="{\'is-empty\': visibility[' + index + '].values[1].length == Object.keys(visibility[' + index + '].rule.options).length}" @change="visibility[' + index + '].values[1].push(event.srcElement.value)"><option value="0">' + this.translate('select') + '</option><option v-for="(label, val) in visibility[' + index + '].rule.options" v-if="!visibility[' + index + '].values[1].includes(val)" :value="val">{{ label }}</option></select></div>');
						}

						return '<span class="selection">' + formattedText + '</span>';
					},
					addOp: function() {
						this.visibility.push({
							type:	'op',
							op:		'and'
						}, {
							type:	'rule',
							rule:	this.rules[Object.keys(ifWidget.rules)[0]],
							values:	[1],
							isOpen:	false
						});
					},
					setRule: function(v, rule) {
						v.rule = rule;
						v.isOpen = false;
						v.values = [1];

						if (rule.type === 'select') {
							v.values.push(Object.keys(rule.options)[0]);
						} else if (rule.type === 'multiple') {
							v.values.push([]);
						} else if (rule.type === 'text') {
							v.values.push('');
						}
					}
				}
			});
		});
	}


	addVisibilityRules();
	$(document).ajaxStop(addVisibilityRules);


});
