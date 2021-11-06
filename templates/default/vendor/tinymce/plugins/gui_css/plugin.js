/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2015
 * @license Commercial License
 
 * @package Galau UI
 */
(function() {
	tinymce.PluginManager.add('gui_css', function(editor, url) {
		var css_list = [];

		/**
		 * Load config
		 */
		if (typeof editor.settings['gui_css'] === 'object') {
			css_list = editor.settings['gui_css'];
		}

		if (window.galau_ui_debug == true) {
			console.log('css => ', css_list);
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
	});
})();