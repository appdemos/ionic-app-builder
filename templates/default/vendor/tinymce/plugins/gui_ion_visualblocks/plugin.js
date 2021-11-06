/* global tinymce */

tinymce.PluginManager.add('gui_ion_visualblocks', function(editor, url) {
	var each = tinymce.util.Tools.each;
	var Env = tinymce.Env;
	var dom = editor.dom;
	var css_list = [url + '/assets/css/plugin.min.css'];
	var config;
	/**
	 * Load config
	 */
	if (typeof editor.settings['gui_ion_visualblocks'] === 'object') {
		config = editor.settings['gui_ion_visualblocks'];
	}
	var display_toolbar_text = true;
	if (typeof config === 'object') {
		if (typeof config.css !== 'undefined') {
			if (!config.css.exist) {
				if (!config.css.external) {
					css_list.push(url + '/assets/css/ionic.min.css');
					if (window.galau_ui_debug === true) {
						console.log('visualblocks => css: internal');
					}
				} else {
					css_list.push(config.css.external);
					if (window.galau_ui_debug === true) {
						console.log('visualblocks => css: external');
					}
				}
			} else {
				if (window.galau_ui_debug === true) {
					console.log('visualblocks => css: exist');
				}
			}
		} else {
			css_list.push(url + '/assets/css/ionic.min.css');
			if (window.galau_ui_debug === true) {
				console.log('visualblocks => css: internal');
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
			console.log('visualblocks => css: internal');
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
		console.log('visualblocks => valid: ', editor.settings.valid_elements);
		console.log('visualblocks => extended_valid: ', editor.settings.extended_valid_elements);
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
	var toolbar_text = '';
	if (display_toolbar_text) {
		toolbar_text = 'Visualblocks';
	}
	var cssId, gui_ion_visualblocksMenuItem, enabled;
	if (!window.NodeList) {
		return;
	}

	function toggleActiveState() {
		var self = this;
		self.active(enabled);
		editor.on('gui_ion_visualblocks', function() {
			self.active(editor.dom.hasClass(editor.getBody(), 'gui_ion_visualblocks'));
		});
	}
	editor.addCommand('gui_ion_visualblocks', function() {
		var dom = editor.dom,
			linkElm;
		if (!cssId) {
			cssId = dom.uniqueId();
			linkElm = dom.create('link', {
				id: cssId,
				rel: 'stylesheet',
				href: url + '/assets/css/visualblocks.css'
			});
			editor.getDoc().getElementsByTagName('head')[0].appendChild(linkElm);
		}
		editor.on("PreviewFormats AfterPreviewFormats", function(e) {
			if (enabled) {
				dom.toggleClass(editor.getBody(), 'gui_ion_visualblocks', e.type === "afterpreviewformats");
			}
		});
		dom.toggleClass(editor.getBody(), 'gui_ion_visualblocks');
		enabled = editor.dom.hasClass(editor.getBody(), 'gui_ion_visualblocks');
		if (gui_ion_visualblocksMenuItem) {
			gui_ion_visualblocksMenuItem.active(dom.hasClass(editor.getBody(), 'gui_ion_visualblocks'));
		}
		editor.fire('gui_ion_visualblocks');
	});
	editor.addButton('gui_ion_visualblocks', {
		title: 'Show Ionic Blocks',
		icon: 'guicon-view-grid guicon guicon-view-grid',
		cmd: 'gui_ion_visualblocks',
		text: toolbar_text,
		onPostRender: toggleActiveState
	});
	editor.addMenuItem('gui_ion_visualblocks', {
		text: 'ionic blocks',
		icon: 'guicon-view-grid guicon guicon-view-grid',
		cmd: 'gui_ion_visualblocks',
		onPostRender: toggleActiveState,
		selectable: true,
		context: 'view',
		prependToContext: true
	});
	editor.on('init', function() {
		if (typeof config === 'object') {
			if (config.default_state) {
				editor.execCommand('gui_ion_visualblocks', false, null, {
					skip_focus: true
				});
				if (window.galau_ui_debug === true) {
					console.log('visualblocks => ON');
				}
			}
		}
	});
	editor.on('remove', function() {
		editor.dom.removeClass(editor.getBody(), 'gui_ion_visualblocks');
	});
});