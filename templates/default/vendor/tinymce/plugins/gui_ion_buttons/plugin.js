/* global tinymce, Element */

(function() {
	tinymce.PluginManager.add('gui_ion_buttons', function(editor, url) {
		var galau_ui_title = 'Galau UI - Ionic Buttons';
		var galau_ui_desc = 'Visual Editor for ionic buttons style.';
		var css_list = [url + '/assets/css/plugin.min.css'];
		var config = '';
		var each = tinymce.util.Tools.each;
		var Env = tinymce.Env;
		var dom = editor.dom;
		var $_ = tinymce.dom.DomQuery;
		var trim = tinymce.util.Tools.trim;
		if (typeof editor.settings['gui_ion_buttons'] === 'object') {
			var config = editor.settings['gui_ion_buttons'];
		}
		var display_toolbar_text = true;
		if (typeof config === 'object') {
			if (typeof config.css !== 'undefined') {
				if (!config.css.exist) {
					if (!config.css.external) {
						css_list.push(url + '/assets/css/ionic.min.css');
						if (window.galau_ui_debug === true) {
							console.log('buttons => css: internal');
						}
					} else {
						css_list.push(config.css.external);
						if (window.galau_ui_debug === true) {
							console.log('buttons => css: external');
						}
					}
				} else {
					if (window.galau_ui_debug === true) {
						console.log('buttons => css: exist');
					}
				}
			} else {
				css_list.push(url + '/assets/css/ionic.min.css');
				if (window.galau_ui_debug === true) {
					console.log('buttons => css: internal');
				}
			}
			if (config.toolbar_text) {
				display_toolbar_text = true;
			} else {
				display_toolbar_text = false;
			}
		} else {
			css_list.push(url + '/assets/css/ionic.min.css');
			if (window.galau_ui_debug === true) {
				console.log('buttons => css: internal');
			}
		}
		/**
		 * Display Dialog For Button
		 */

		function show_buttonDialog() {
			var win;
			var data = {
				tag: 'link',
				text: 'Example Button',
				'style': 'primary'
			};
			var input_option = [];
			var dom = editor.dom;
			var data = current_buttonData(editor.selection.getNode());
			if (window.galau_ui_debug === true) {
				console.log('buttons => current data : ', data);
			}
			var iconPicker = [{
				value: 'none',
				text: 'No Icon'
			}];

			//register to iconPicker
			if (typeof editor.settings.gui_icon_picker === 'object') {
				iconPicker = editor.settings.gui_icon_picker;
			}
			if (window.galau_ui_debug === true) {
				console.log('buttons => icon Picker : ', iconPicker);
			}

			win = editor.windowManager.open({
				title: galau_ui_title,
				classes: 'gui_ion_buttons-panel',
				bodyType: "tabpanel",
				//resizable: true,
				data: data,
				body: [{
					title: "General",
					type: "form",
					columns: 1,
					items: [{
						type: 'form',
						layout: "grid",
						columns: 2,
						margin: 0,
						padding: 0,
						items: [
							{
							type: 'form',
							margin: 0,
							padding: 0,
							classes: 'ion-button-options',
							minWidth: 400,
							items: [{
								type: 'form',
								style: 'border: 1px solid #ddd;',
								items: [{
									type: 'label',
									text: 'Basic',
									style: 'font-weight: bold;'
								},
								{
									label: "Text",
									name: "ion_button_text",
									type: "textbox",
									tooltip: "Text for display",
									value: data.text,
									onchange: function(s) {
										if (window.galau_ui_debug === true) {
											console.log('buttons => text : ', s.target.value);
										}
										data.text = s.target.value;
										update_buttonPreview();
									},
									onclick: function(s) {
										if (window.galau_ui_debug === true) {
											console.log('buttons => text : ', s.target.value);
										}
										data.text = s.target.value;
										update_buttonPreview();
									},
									onkeyup: function(s) {
										if (window.galau_ui_debug === true) {
											console.log('buttons => text : ', s.target.value);
										}
										data.text = s.target.value;
										update_buttonPreview();
									},
									margin: 0,
									padding: 0
								},
								{
									type: "listbox",
									name: "ion_button_tag",
									label: "HTML Tag",
									tooltip: "HTML Tag",
									value: data.tag,
									onselect: function(s) {
										if (window.galau_ui_debug === true) {
											console.log('buttons => tag : ', s.target.state.data.value);
										}
										data.tag = s.target.state.data.value;
										update_buttonPreview();
									},
									values: [{
										value: "link",
										text: "<a href=\"#\">"
									},
									{
										value: "button",
										text: "<button>"
									},
									{
										value: "input-button",
										text: "<input type=\"button\">"
									},
									{
										value: "input-submit",
										text: "<input type=\"submit\">"
									},
									{
										value: "input-reset",
										text: "<input type=\"reset\">"
									}]
								},
								{
									type: "listbox",
									name: "ion_button_style",
									label: "Style",
									value: data.style,
									onselect: function(s) {
										if (window.galau_ui_debug === true) {
											console.log('buttons => style : ', s.target.state.data.value);
										}
										data.style = s.target.state.data.value;
										update_buttonPreview();
									},
									values: [
										{
										value: "button-light",
										text: "Light"
									},
									{
										value: "button-stable",
										text: "Stable"
									},
									{
										value: "button-positive",
										text: "Positive"
									},
									{
										value: "button-calm",
										text: "Calm"
									},
									{
										value: "button-balanced",
										text: "Balanced"
									},
									{
										value: "button-energized",
										text: "Energized"
									},
									{
										value: "button-assertive",
										text: "assertive"
									},
									{
										value: "button-royal",
										text: "Royal"
									},
									{
										value: "button-dark",
										text: "Dark"
									}
									]
								},
								{
									type: "listbox",
									name: "ion_button_mode",
									label: "Mode",
									value: data.style,
									onselect: function(s) {
										if (window.galau_ui_debug === true) {
											console.log('buttons => mode : ', s.target.state.data.value);
										}
										data.style = s.target.state.data.value;
										update_buttonPreview();
									},
									values: [
										{
										value: "",
										text: "None"
									},
									{
										value: "run-webview",
										text: "Webview"
									},
									{
										value: "run-app-browser",
										text: "App Browser"
									},
									{
										value: "run-open-url",
										text: "Open URL"
									}
									]
								},


								{
									type: "listbox",
									name: "ion_button_size",
									label: "Size",
									value: data.size,
									onselect: function(s) {
										if (window.galau_ui_debug === true) {
											console.log('buttons => size : ', s.target.state.data.value);
										}
										data.size = s.target.state.data.value;
										update_buttonPreview();
									},
									values: [{
										value: "",
										text: "Default"
									},
									{
										value: "button-small",
										text: "Small"
									},
									{
										value: "button-large",
										text: "Large"
									}
										                                                        ]
								},
								{
									label: ' ',
									type: "checkbox",
									name: "ion_button_hollow",
									text: "Outlined Style",
									checked: data.hollow,
									onclick: function(s) {
										if (window.galau_ui_debug === true) {
											console.log('buttons => hollow : ', s.control.state.data.checked);
										}
										if (s.control.state.data.checked) {
											data.hollow = true;
										} else {
											data.hollow = false;
										}
										update_buttonPreview();
									}
								},
								{
									label: ' ',
									type: "checkbox",
									name: "ion_button_block",
									text: "Full width 100%",
									checked: data.block,
									onclick: function(s) {
										if (window.galau_ui_debug === true) {
											console.log('buttons => block : ', s.control.state.data.checked);
										}
										if (s.control.state.data.checked) {
											data.block = true;
										} else {
											data.block = false;
										}
										update_buttonPreview();
									}
								}
									                                                ]
							}
								                                            //====================
								                                        ]
						},
						{
							type: 'form',
							margin: 0,
							padding: 0,
							items: [{
								type: 'form',
								margin: 0,
								padding: 15,
								style: 'border: 1px solid #ddd;',
								items: [
									{
									type: "label",
									text: "Preview",
									style: 'font-weight:bold;'
								},
								{
									type: 'container',
									html: '<div id="ion_button_preview" srcdoc="' + generate_buttonCode(data) + '">' + generate_buttonCode(data) + '</div>',
									style: '',
									border: 0,
									minWidth: 300,
									minHeight: 80,
									margin: 0

								}]
							},
							{
								type: 'form',
								margin: 0,
								padding: 15,
								style: 'border: 1px solid #ddd;',
								items: [{
									type: "label",
									text: "Other",
									style: 'font-weight: bold;'
								},
								{
									type: 'form',
									minWidth: 300,
									margin: 0,
									padding: 15,
									items: [{
										type: 'textbox',
										name: 'ion_button_title',
										label: 'Title',
										onkeyup: function(s) {
											if (window.galau_ui_debug === true) {
												console.log('buttons => title : ', s.target.value);
											}
											data.title = s.target.value;
											update_buttonPreview();
										},
										onchange: function(s) {
											if (window.galau_ui_debug === true) {
												console.log('buttons => title : ', s.target.value);
											}
											data.title = s.target.value;
											update_buttonPreview();
										},
										value: data.title
									},
									{
										type: 'filepicker',
										filetype: 'file',
										name: 'ion_button_url',
										label: 'URL',
										onkeyup: function(s) {
											if (window.galau_ui_debug === true) {
												console.log('buttons => set url : ', s.target.value);
											}
											data.url = s.target.value;
											update_buttonPreview();
										},
										onchange: function(s) {
											if (window.galau_ui_debug === true) {
												console.log('buttons => set url : ', s.target.value);
											}
											data.url = s.target.value;
											update_buttonPreview();
										},
										value: data.url
									},
									{
										type: 'listbox',
										name: 'ion_button_target',
										label: 'Target',
										values: [{
											value: '_self',
											text: '_self'
										},
										{
											value: '_top',
											text: '_top'
										},
										{
											value: '_blank',
											text: '_blank'
										},
										{
											value: '_parent',
											text: '_parent'
										}],
										onselect: function(s) {
											if (window.galau_ui_debug === true) {
												console.log('buttons => target : ', s.target.state.data.value);
											}
											data.target = s.target.state.data.value;
											update_buttonPreview();
										},
										onchange: function(s) {
											if (window.galau_ui_debug === true) {
												console.log('buttons => target : ', s.target.state.data.value);
											}
											data.target = s.target.state.data.value;
											update_buttonPreview();
										},
										value: data.target
									},
									{
										type: 'buttongroup',
										label: 'Before',
										height: 28,
										items: [
											{
											type: 'textbox',
											name: 'ion_button_icon_prepend',
											id: 'ion_button_icon_prepend',
											style: "border-left: 1px solid #ddd !important; border-right: 0 !important",
											value: data.icon_prepend,
											onkeyup: function(s) {
												if (window.galau_ui_debug === true) {
													console.log('buttons => icon_prepend : ', s.target.value);
												}
												data.icon_prepend = s.target.value;
												update_buttonPreview();
											},
											onchange: function(s) {
												if (window.galau_ui_debug === true) {
													console.log('buttons => icon_prepend : ', s.target.value);
												}
												data.icon_prepend = s.target.value;
												update_buttonPreview();
											}
										},
										{
											type: 'menubutton',
											menu: iconPicker,
											style: "border: 1px solid #ddd !important",
											height: 28,
											onselect: function(s) {
												var selectPicker = s.target.state.data.value;
												if (window.galau_ui_debug === true) {
													console.log('buttons => set append icon : ', selectPicker);
												}
												if (selectPicker !== 'none') {
													if (typeof editor.settings[selectPicker] === 'function') {
														editor.settings.prependButtonIcon = function(e) {
															if (window.galau_ui_debug === true) {
																console.log('buttons => prepend icon : ', e);
															}
															win.find('#ion_button_icon_prepend')[0].value(e);
															data.icon_prepend = e;
															update_buttonPreview();
														};
														editor.settings[selectPicker]('prependButtonIcon');
													} else {
														tinymce.activeEditor.windowManager.alert('Icon Picker plugin not installed or not supported!');
													}
												} else {
													win.find('#ion_button_icon_prepend')[0].value('');
													data.icon_prepend = '';
													update_buttonPreview();
												}
											}
										}
										]
									},
									{
										type: 'buttongroup',
										height: 28,
										label: 'After',
										items: [{
											type: 'textbox',
											name: 'ion_button_icon_append',
											id: 'ion_button_icon_append',
											style: "border-left: 1px solid #ddd !important; border-right: 0 !important",
											value: data.icon_append,
											onkeyup: function(s) {
												if (window.galau_ui_debug === true) {
													console.log('buttons => icon_append : ', s.target.value);
												}
												data.icon_append = s.target.value;
												update_buttonPreview();
											},
											onchange: function(s) {
												if (window.galau_ui_debug === true) {
													console.log('buttons => icon_append : ', s.target.value);
												}
												data.icon_append = s.target.value;
												update_buttonPreview();
											}
										},
										{
											type: 'menubutton',
											height: 28,
											menu: iconPicker,
											style: "border: 1px solid #ddd !important",
											onselect: function(s) {
												var selectPicker = s.target.state.data.value;
												if (window.galau_ui_debug === true) {
													console.log('buttons => append icon : ', selectPicker);
												}
												if (selectPicker !== 'none') {
													if (typeof editor.settings[selectPicker] === 'function') {
														editor.settings.appendButtonIcon = function(e) {
															if (window.galau_ui_debug === true) {
																console.log('buttons => append icon : ', e);
															}
															win.find('#ion_button_icon_append')[0].value(e);
															data.icon_append = e;
															update_buttonPreview();
														};
														editor.settings[selectPicker]('appendButtonIcon');
													} else {
														tinymce.activeEditor.windowManager.alert('Icon Picker plugin not installed or not supported!');
													}
												} else {
													win.find('#ion_button_icon_append')[0].value('');
													data.icon_append = '';
													update_buttonPreview();
												}
											}
										}
											                                                                ]
									}
										                                                        ]
								}
									                                                ],
								minHeight: 220
							}
								                                        ]
						}
						]
					}
						                        ]
				},
				{
					title: "Code",
					type: "form",
					items: [{
						type: 'label',
						text: 'HTML Code here'
					},
					{
						flex: 1,
						name: "ion_button_code",
						type: "textbox",
						multiline: true,
						value: generate_buttonCode(data)
					}]
				},
				{
					title: "About",
					type: "form",
					layout: "grid",
					items: [{
						type: "panel",
						classes: 'about-us',
						html: "<h2>" + galau_ui_title + "</h2><h4>Created by <a href='http://ihsana.com/jasman/'>Jasman</a></h4><p>" + galau_ui_desc + "</p>",
						style: "background:#fff"
					}
						                        ]
				}],
				onsubmit: function(e) {
					editor.undoManager.transact(function() {
						var button = win.find('#ion_button_code')[0].value();
						editor.insertContent(button);
						win.hide();
					});
				}
			});

			function switch_option() {
				win.find("#ion_button_title")[0].disabled(true);
				win.find("#ion_button_url")[0].disabled(true);
				win.find("#ion_button_target")[0].disabled(true);
				win.find("#ion_button_icon_prepend")[0].disabled(true);
				win.find("#ion_button_icon_append")[0].disabled(true);
				switch (data.tag) {
				case 'link':
					win.find("#ion_button_title")[0].disabled(false);
					win.find("#ion_button_url")[0].disabled(false);
					win.find("#ion_button_target")[0].disabled(false);
					win.find("#ion_button_icon_prepend")[0].disabled(false);
					win.find("#ion_button_icon_append")[0].disabled(false);
					break;
				case 'button':
					win.find("#ion_button_title")[0].disabled(false);
					win.find("#ion_button_url")[0].disabled(false);
					win.find("#ion_button_target")[0].disabled(false);
					win.find("#ion_button_icon_prepend")[0].disabled(false);
					win.find("#ion_button_icon_append")[0].disabled(false);
					break;
				case 'input-submit':
					win.find("#ion_button_title")[0].disabled(false);
					win.find("#ion_button_url")[0].disabled(true);
					win.find("#ion_button_target")[0].disabled(true);
					win.find("#ion_button_icon_prepend")[0].disabled(true);
					win.find("#ion_button_icon_append")[0].disabled(true);
					break;
				case 'input-button':
					win.find("#ion_button_title")[0].disabled(false);
					win.find("#ion_button_url")[0].disabled(true);
					win.find("#ion_button_target")[0].disabled(true);
					win.find("#ion_button_icon_prepend")[0].disabled(true);
					win.find("#ion_button_icon_append")[0].disabled(true);
					break;
				case 'input-reset':
					win.find("#ion_button_title")[0].disabled(false);
					win.find("#ion_button_url")[0].disabled(true);
					win.find("#ion_button_target")[0].disabled(true);
					win.find("#ion_button_icon_prepend")[0].disabled(true);
					win.find("#ion_button_icon_append")[0].disabled(true);
					break;
				}
			}
			update_buttonPreview();


			function update_buttonPreview() {
				switch_option();
				var newPreview = document.createElement('div');
				newPreview.setAttribute('id', 'ion_button_preview');
				markup_button = generate_buttonCode(data);
				newPreview.innerHTML = markup_button;
				var preview = document.querySelector('#ion_button_preview');
				preview.parentNode.replaceChild(newPreview, preview);
				win.find('#ion_button_code')[0].value(markup_button);
			}

			function current_buttonData(element) {
				if (Object.defineProperty && Object.getOwnPropertyDescriptor && Object.getOwnPropertyDescriptor(Element.prototype, "textContent") && !Object.getOwnPropertyDescriptor(Element.prototype, "textContent").get)(function() {
					var innerText = Object.getOwnPropertyDescriptor(Element.prototype, "innerText");
					Object.defineProperty(Element.prototype, "textContent", {
						get: function() {
							return innerText.get.call(this);
						},
						set: function(x) {
							return innerText.set.call(this, x);
						}
					});
				})();
				var data = {
					tag: 'button',
					text: 'Example Button',
					'style': 'button-calm'
				};
				data.active = false;
				data.disabled = false;
				data.expanded = false;
				data.hollow = false;

				var is_button = false;
				if (window.galau_ui_debug === true) {
					console.log('buttons => content : ', element);
				}
				if (window.galau_ui_debug === true) {
					console.log('buttons => TagName: ', element.tagName.toLowerCase());
				}
				if (dom.getAttrib(element, 'class')) {
					var current_classes = dom.getAttrib(element, 'class').toLowerCase();
					if (window.galau_ui_debug === true) {
						console.log('buttons => class : ', current_classes);
					}
					var _classes = current_classes.split(' ');
					for (var z = 0; z < _classes.length; z++) {
						if ("button" === _classes[z].toLowerCase()) {
							is_button = true;
						}
						var class_color = ["button-light", "button-stable", "button-positive", "button-calm", "button-balanced", "button-energized", "button-assertive", "button-royal", "button-dark"];
						for (var y = 0; y < class_color.length; y++) {
							if (class_color[y] === _classes[z].toLowerCase()) {
								data.style = class_color[y];
							}
						}
						var class_size = ["button-small", "button-large"];
						for (var y = 0; y < class_size.length; y++) {
							if (class_size[y] === _classes[z].toLowerCase()) {
								data.size = class_size[y];
							}
						}
						if ("button-full" === _classes[z].toLowerCase()) {
							data.block = true;
						}

						if ("button-outline" === _classes[z].toLowerCase()) {
							data.hollow = true;
						}

					}
				}
				if (is_button === true) {
					switch (element.nodeName.toLowerCase()) {
					case 'a':
						data.tag = 'link';
						var find_fa_icon = element.querySelectorAll('.fa,.dashicons,.glyphicon');
						if (typeof find_fa_icon[0] !== 'undefined') {
							data.icon_prepend = dom.getAttrib(find_fa_icon[0], 'class');
						}
						if (typeof find_fa_icon[1] !== 'undefined') {
							data.icon_append = dom.getAttrib(find_fa_icon[1], 'class');
						}
						if (window.galau_ui_debug === true) {
							console.log('buttons => icon : ', find_fa_icon);
						}
						data.text = trim($_(element).text());
						break;
					case 'button':
						data.tag = 'button';
						var find_fa_icon = element.querySelectorAll('.fa,.dashicons,.glyphicon');
						if (typeof find_fa_icon[0] !== 'undefined') {
							data.icon_prepend = dom.getAttrib(find_fa_icon[0], 'class');
						}
						if (typeof find_fa_icon[1] !== 'undefined') {
							data.icon_append = dom.getAttrib(find_fa_icon[1], 'class');
						}
						if (window.galau_ui_debug === true) {
							console.log('buttons => icon : ', find_fa_icon);
						}
						data.text = trim($_(element).text());
						break;
					case 'input':
						if (dom.getAttrib(element, 'value')) {
							data.text = dom.getAttrib(element, 'value');
							data.tag = 'input';
						}
						if (dom.getAttrib(element, 'type')) {
							data.tag += '-' + dom.getAttrib(element, 'type');
						}
						break;
					}
					if (element.getAttribute('disabled')) {
						data.disabled = true;
					}
					if (element.getAttribute('title')) {
						data.title = element.getAttribute('title');
					}
					if (element.getAttribute('target')) {
						data.target = element.getAttribute('target');
					}
					if (element.getAttribute('href')) {
						data.url = element.getAttribute('href');
					}
				}
				return data;
			}


			function generate_buttonCode(data) {
				var markup_button = '';


				var classes = [];
				var attrib = [];

				if (data.style) {
					classes.push(data.style);
				}

				if (data.hollow === true) {
					classes.push('button-outline');
				}

				if (data.size) {
					classes.push(data.size);
				}

				if (data.block === true) {
					classes.push('button-full');
				}


				if (data.title) {
					attrib.push({
						title: data.title
					});
				}
				if (data.tag === 'link') {
					if (data.target) {
						attrib.push({
							target: data.target
						});
					}
					if (data.url) {
						attrib.push({
							href: data.url
						});
					} else {
						attrib.push({
							href: '#'
						});
					}
				}
				classes.push('button');

				var icon_prepend = '';
				if (data.icon_prepend) {
					icon_prepend = '<span class="' + data.icon_prepend + '"></span> ';
				}
				var icon_append = '';
				if (data.icon_append) {
					icon_append = ' <span class="' + data.icon_append + '"></span>';
				}
				var _classes = classes.join(' ');
				var _attrib = '';
				for (var z = 0; z < attrib.length; z++) {
					var array_keys = new Array();
					var array_values = new Array();
					for (var key in attrib[z]) {
						_attrib += key + '="' + attrib[z][key] + '" ';
					}
				}
				switch (data.tag) {
				case 'link':
					markup_button = '<a class="' + _classes + '" ' + _attrib + '>' + icon_prepend + data.text + icon_append + '</a> ';
					break;
				case 'button':
					markup_button = '<button class="' + _classes + '" ' + _attrib + '>' + icon_prepend + data.text + icon_append + '</button> ';
					break;
				case 'input-submit':
					markup_button = '<input type="submit" class="' + _classes + '" value="' + data.text + '" ' + _attrib + '/> ';
					break;
				case 'input-button':
					markup_button = '<input type="button" class="' + _classes + '" value="' + data.text + '" ' + _attrib + '/> ';
					break;
				case 'input-reset':
					markup_button = '<input type="reset" class="' + _classes + '" value="' + data.text + '" ' + _attrib + '/> ';
					break;
				}
				if (window.galau_ui_debug === true) {
					console.log('output => HTML : ', markup_button);
				}
				return markup_button;
			}
		}
		// Include CSS
		if (typeof editor.settings.content_css !== 'undefined') {
			if (typeof editor.settings.content_css.push === "function") {
				for (var i = 0; i < css_list.length; i++) {
					editor.settings.content_css.push(css_list[i]);
				};
			} else if (typeof editor.settings.content_css === "string") {
				editor.settings.content_css = [editor.settings.content_css];
				for (var i = 0; i < css_list.length; i++) {
					editor.settings.content_css.push(css_list[i]);
				};
			} else {
				editor.settings.content_css = css_list;
			}
		} else {
			editor.settings.content_css = css_list;
		}
		// Allow elements
		if (typeof editor.settings.extended_valid_elements === 'undefined') {
			editor.settings.extended_valid_elements = '*[*]';
		}
		if (typeof editor.settings.valid_elements === 'undefined') {
			editor.settings.valid_elements = '*[*]';
		}
		if (window.galau_ui_debug === true) {
			console.log('buttons => valid: ', editor.settings.valid_elements);
			console.log('buttons => extended_valid: ', editor.settings.extended_valid_elements);
		}
		var toolbar_text = '';
		if (display_toolbar_text) {
			toolbar_text = 'Button';
		}
		// Include CSS
		editor.on('init', function() {
			if (document.createStyleSheet) {
				for (var i = 0; i < css_list.length; i++) {
					document.createStyleSheet(css_list[i]);
				}
			} else {
				for (var i = 0; i < css_list.length; i++) {
					cssLink = editor.dom.create('link', {
						rel: 'stylesheet',
						href: css_list[i]
					});
					document.getElementsByTagName('head')[0].appendChild(cssLink);
				}
			}
		});


		function changeButton(format) {
			var dom = editor.dom;
			var btnElm = editor.dom.getParent(editor.selection.getStart(), '.button');
			if (window.galau_ui_debug === true) {
				console.log('alert => ', btnElm);
			}
			if (btnElm === null) {
				editor.undoManager.transact(function() {
					show_buttonDialog();
				});
			} else {
				editor.undoManager.transact(function() {
					each('buttonLight buttonStable buttonPositive buttonCalm buttonBalanced buttonEnergized buttonAssertive buttonRoyal buttonDark'.split(' '), function(name) {
						editor.formatter.remove(name);
					});
					editor.formatter.apply(format);
				});
			}
		}
		/**
		 * Create button remove
		 */
		editor.addButton('gui_ion_button_remove', {
			icon: 'remove',
			tooltip: 'remove this button',
			stateSelector: '.button',
			onclick: function() {
				var labelElm = editor.dom.getParent(editor.selection.getStart(), '.button');
				if (labelElm) {
					editor.undoManager.transact(function() {
						$_(labelElm).replaceWith('');
					});
				}
			}
		});
		editor.on('init', function() {
			editor.addContextToolbar('.button', 'gui_ion_buttons | undo redo | gui_ion_button_remove');

			/**
			 * Register Button Format
			 */

			editor.formatter.register({
				buttonLight: [{
					selector: '.button',
					classes: 'button-light'
				}],
				buttonStable: [{
					selector: '.button',
					classes: 'button-stable'
				}],
				buttonPositive: [{
					selector: '.button',
					classes: 'button-positive'
				}],
				buttonCalm: [{
					selector: '.button',
					classes: 'button-calm'
				}],
				buttonBalanced: [{
					selector: '.button',
					classes: 'button-balanced'
				}],
				buttonEnergized: [{
					selector: '.button',
					classes: 'button-energized'
				}],
				buttonAssertive: [{
					selector: '.button',
					classes: 'button-assertive'
				}],
				buttonRoyal: [{
					selector: '.button',
					classes: 'button-royal'
				}],
				buttonDark: [{
					selector: '.button',
					classes: 'button-dark'
				}]
			});
		});
		// Add to button
		editor.addButton('gui_ion_buttons', {
			icon: 'guicon-button guicon guicon-button',
			text: toolbar_text,
			tooltip: 'Insert/Edit Button',
			onclick: show_buttonDialog,
			type: 'splitbutton',
			stateSelector: '.button',
			classes: 'gui_ion_buttons',
			menu: [
				{
				text: 'Light',
				onclick: function() {
					changeButton('buttonLight');
				}
			},
			{
				text: 'Stable',
				onclick: function() {
					changeButton('buttonStable');
				}
			},
			{
				text: 'Positive',
				onclick: function() {
					changeButton('buttonPositive');
				}
			},
			{
				text: 'Calm',
				onclick: function() {
					changeButton('buttonCalm');
				}
			},
			{
				text: 'Balanced',
				onclick: function() {
					changeButton('buttonBalanced');
				}
			},
			{
				text: 'Energized',
				onclick: function() {
					changeButton('buttonEnergized');
				}
			},
			{
				text: 'Assertive',
				onclick: function() {
					changeButton('buttonAssertive');
				}
			},
			{
				text: 'Royal',
				onclick: function() {
					changeButton('buttonRoyal');
				}
			}
			]
		});
		if (!editor.settings.showGuiIonicButtons) {
			editor.settings.showGuiIonicButtons = show_buttonDialog;
		}
	});
})();