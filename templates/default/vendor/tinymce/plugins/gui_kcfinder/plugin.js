/* global tinymce */

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 
 * @package Galau UI
 */

tinymce.PluginManager.add('gui_kcfinder', function(editor, url) {
	var galau_ui_title = 'Galau UI - KCFinder';
	var galau_ui_name = 'KCFinder';
	var galau_ui_desc = 'Plugin for integration KCFinder in TinyMCE';
	var show_toolbar_text = false;
	if (typeof editor.settings['gui_kcfinder'] === 'object') {
		var config = editor.settings['gui_kcfinder'];
		if (!config.url_reguest) {
			config.url_reguest = './system/plugin/kcfinder/';
		}
		if (!config.toolbar_text) {
			show_toolbar_text = false;
		} else {
			show_toolbar_text = true;
		}

	} else {
		var config = {
			toolbar_text: true,
			url_reguest: './system/plugin/kcfinder/'
		};
	}

	var text_toolbar = '';
	if (show_toolbar_text == true) {
		text_toolbar = galau_ui_name;
	}
	if (window.galau_ui_debug === true) {
		console.log('kcfinder => url_reguest: ', config.url_reguest);
	}
	editor.settings.file_browser_callback = function(field, url, type, win) {
		tinyMCE.activeEditor.windowManager.open({
			file: config.url_reguest + '/browse.php?opener=tinymce4&field=' + field + '&type=' + type,
			title: galau_ui_title,
			width: 640,
			height: 500,
			inline: true,
			close_previous: false
		}, {
			window: win,
			input: field
		});
	}

	editor.addButton('gui_kcfinder', {
		icon: 'browse',
		text: text_toolbar,
		tooltip: galau_ui_desc,
		onclick: editor.settings.file_browser_callback
	});


});