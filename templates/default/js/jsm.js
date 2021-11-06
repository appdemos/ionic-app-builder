/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 
 * @package Ionic App Builder
 */

function strToLink(str) {
	str = str.replace("0", "_zero");
	str = str.replace("1", "_one");
	str = str.replace("2", "_two");
	str = str.replace("3", "_three");
	str = str.replace("4", "_four");
	str = str.replace("5", "_five");
	str = str.replace("6", "_six");
	str = str.replace("7", "_seven");
	str = str.replace("8", "_eight");
	str = str.replace("9", "_nine");
	var rands = Math.floor((Math.random() * 10) + 1);
	var link, charCode;
	str = str.split(' ').join('_').toLocaleLowerCase();
	link = '';
	for (i = 0; i < str.length; i++) {
		charCode = str[i].charCodeAt();
		if ((charCode < 123) && (charCode > 96) || (charCode == 95)) {
			link += str[i];
		}
		//logger(charCode);
	}
	link = link.split('__').join('_').toLocaleLowerCase();
	link = link.split('__').join('_').toLocaleLowerCase();
	link = link.split('__').join('_').toLocaleLowerCase();
	link = link.split('__').join('_').toLocaleLowerCase();
	link = link.split('__').join('_').toLocaleLowerCase();
	link = link.split('__').join('_').toLocaleLowerCase();
	if (link == '_') {
		link = link + str.length;
		link = link.replace("0", "_zero");
		link = link.replace("1", "_one");
		link = link.replace("2", "_two");
		link = link.replace("3", "_three");
		link = link.replace("4", "_four");
		link = link.replace("5", "_five");
		link = link.replace("6", "_six");
		link = link.replace("7", "_seven");
		link = link.replace("8", "_eight");
		link = link.replace("9", "_nine");
	}
	return link;
}
$(document).ready(function() {
	$(".this-elms").hover(function() {
		var target = $(this).attr("data-target");
		$(target).css("color", "red");
		//$(target).css("background","#eee");
		//$(target).css("display","block");
	}, function() {
		var target = $(this).attr("data-target");
		$(target).css("color", "inherit");
		//$(target).css("background","inherit");
	});
	$("#page_title_").on("keydown", function() {
		var set_link = strToLink($(this).val());
		$("#page_var_").val(set_link);
	});
	$("#page_title_").on("blur", function() {
		var set_link = strToLink($(this).val());
		$("#page_var_").val(set_link);
	});
	$("input[data-type='icon-picker']").on('click', function() {
		window.ICON_PICKER = '#' + $(this).prop('id');
		$('#icon-dialog').modal();
	});
	$(".ionicon-list").on('click', function() {
		var class_icon = $(this).attr('data-icon');
		var form_input = window.ICON_PICKER;
		$(form_input).val(class_icon);
		$('#icon-dialog').modal('hide');
	});
	$("#filter-icon").keyup(function() {
		var keyword = $(this).val();
		$(".ionicon-item").each(function() {
			var fa_icon = $(this).attr('data-id');
			$(this).addClass('hidden');
			if (fa_icon.toLowerCase().indexOf(keyword) >= 0) {
				$(this).removeClass('hidden');
			}
		});
	});
	$("#forms_max_").on("change", function() {
		var prefix = $("#forms_select_").val();
		var forms = $("#forms_max_").val();
		window.location = "./?page=x-forms&prefix=" + prefix + "&forms=" + forms;
	});
	$("#forms_select_").on("change", function() {
		var prefix = $("#forms_select_").val();
		window.location = "./?page=x-forms&prefix=" + prefix;
	});
	$("#table-current").on("change", function() {
		var parent = $("#tables_parent_").val();
		var prefix = $("#table-current").val();
		var tables = $("#tables-cols").val();
		// console.log("./?page=tables&prefix=" + prefix);
		window.location = "./?page=tables&prefix=" + prefix;
	});
	$("#tables-cols,#tables_parent_").on("change", function() {
		var tables = $("#tables-cols").val();
		var parent = $("#tables_parent_").val();
		var prefix = $("#table-current").val();
		window.location = './?page=tables&cols=' + tables + "&parent=" + parent + "&prefix=" + prefix;
	});
	$("#page_prefix,#page_editor").on("change", function() {
		var prefix = $("#page_prefix").val();
		var editor = $("#page_editor").val();
		window.location = './?page=page&prefix=' + prefix + '&editor=' + editor;
	});
	$("#popover_max-menu_").on("change", function() {
		window.location = './?page=popover&max-menu=' + $(this).val();
	});
	$(".remove-item").on("click", function() {
		var target = $(this).attr("data-target");
		$(target).replaceWith(' ');
	});
	var substringMatcher = function(strs) {
		return function findMatches(q, cb) {
			var matches, substringRegex;
			matches = [];
			substrRegex = new RegExp(q, 'i');
			$.each(strs, function(i, str) {
				if (substrRegex.test(str)) {
					matches.push(str);
				}
			});
			cb(matches);
		};
	};
	//sortable
	$(".sortable").sortable({
		containerSelector: 'table',
		itemPath: '> tbody',
		itemSelector: 'tr',
		placeholder: '<tr class="placeholder"/>'
	});
	if (typeof typehead_vars != 'undefined') {
		//typehead
		$(".typeahead").typeahead({
			hint: true,
			highlight: true,
			minLength: 1,
			limit: 20,
		}, {
			name: 'typehead_vars',
			source: substringMatcher(typehead_vars)
		});
	}
	if ($('#error-modal').length) {
		$('#error-modal').modal();
	}
	$("#tables_template_").click(function() {
		var val = $(this).val();
		//console.log(val);
	});
	$(".phone-frame").contents().find("html").find('head').append('<style>body,*,a{cursor:url("../../../templates/default/img/finger.png"), auto !important;};</style>'); /** validate **/

	function validateID() {
		var hitID = 0;
		$('[data-type="cols"]').each(function() {
			var colsType = $(this).val();
			if (colsType == 'id') {
				hitID++;
			}
		});
		return hitID;
	}
	$('[data-type="cols"]').change(function() {
		var hitID = validateID();
		if (hitID === 0) {
			alert("The table required have one column which dataType ID");
		}
		if (hitID > 1) {
			alert("1st column use as base ID (Database), and 2nd ID only use for link (Unique) ");
		}
	});
	$('input[data-type="tagsinput"]').tagsinput();

	function select_ionic_templates() {
		var _value = $('#menu_type_').val();
		//console.log(_value);
		if (_value == 'side_menus') {
			$("#menu_menu_style_ option[value='expanded-header']").fadeIn();
			$("#menu_menu_style_ option[value='tabs-striped']").fadeOut();
			$("#menu_menu_position_ option[value='left']").fadeIn();
			$("#menu_menu_position_ option[value='right']").fadeIn();
			$("#menu_menu_position_ option[value='top']").fadeOut();
			$("#menu_menu_position_ option[value='bottom']").fadeOut();
			$("#menu_expanded_header_").attr("readonly", false);
		} else {
			$("#menu_menu_style_ option[value='expanded-header']").fadeOut();
			$("#menu_menu_style_ option[value='tabs-striped']").fadeIn();
			$("#menu_menu_position_ option[value='left']").fadeOut();
			$("#menu_menu_position_ option[value='right']").fadeOut();
			$("#menu_menu_position_ option[value='top']").fadeIn();
			$("#menu_menu_position_ option[value='bottom']").fadeIn();
			$("#menu_expanded_header_").attr("readonly", true);
		}
	};
	$(document).ready(function() {
		select_ionic_templates();
	});
	$('#menu_type_').click(function() {
		$("#menu_menu_position_").val("");
		$("#menu_menu_style_").val("");
		select_ionic_templates();
	});
	$("#menu_header_background_").change(function() {
		var _value = $('#menu_header_background_').val();
		if (_value == 'images') {
			$("#menu_header_image_background_").attr("readonly", false);
		} else {
			$("#menu_header_image_background_").attr("readonly", true);
		}
	});
	$(".json-checked").click(function() {
		var _id = $(this).prop('id');
		var page_list_checked = _id.replace('page_list', 'json');
		var page_detail_checked = _id.replace('page_detail', 'json');
		$("#" + page_list_checked).attr("checked", true);
		$("#" + page_detail_checked).attr("checked", true);
	});
	$("#select-current-table").on("click", function(e) {
		e.preventDefault();
		var prefix = $("#table-current").val();
		window.location = './?page=tables&prefix=' + prefix;
	});
});
$(document).ready(function() {
	$("#column_preview").fadeOut();
	$('select[data-type="cols"]').change(function() {
		var data_type = $(this).val();
		$("#column_preview").html("<img class=\"" + data_type + "\"  src=\"./templates/default/img/item/" + data_type + ".png\" />");
		$("#column_preview").fadeIn();
		setTimeout(function() {
			$("#column_preview").fadeOut();
		}, 6000);
	});
	$('select[data-type="color"]').attr('class', function() {
		$(this).attr("class", "form-control");
		var _value = $(this).val();
		$(this).addClass("bg-" + _value);
	});
	$('select[data-type="color"]').change(function() {
		$(this).attr("class", "form-control");
		var _value = $(this).val();
		$(this).addClass("bg-" + _value);
	});
	$('select[data-type="color-hexa"]').attr('class', function() {
		$(this).attr("style", "");
		var _value = $(this).val();
		$(this).attr("style", "-webkit-appearance: none;color:#fff !important;background:" + _value);
	});
	$('select[data-type="color-hexa"]').change('class', function() {
		$(this).attr("style", "");
		var _value = $(this).val();
		$(this).attr("style", "-webkit-appearance: none;color:#fff !important;background:" + _value);
	});
	var KCFinderTarget = "";
	window.KCFinder = {
		callBack: function(e) {
			var path = e.split("/www/");
			$(KCFinderTarget).val(path[1]);
		},
		open: function(prop_id, file_type) {
			KCFinderTarget = prop_id;
			var newwindow = window.open("./system/plugin/" + app_filebrowser + "/?type=" + file_type, "File Explorer", "height=480,width=1024");
			if (window.focus) {
				newwindow.focus()
			}
		}
	};
	$("input[data-type='image-picker']").on('click', function() {
		var _id = $(this).prop('id');
		KCFinder.open('#' + _id, "images");
	});
	$(".shell").on('mousedown', function(e) {
		$this = $(this);
		if ((e.which == 1)) {
			$(this).css('color', '#0f0');
		}
		if ((e.which == 3)) {
			$(this).css('color', '#0f0');
		} else if ((e.which == 2)) {
			//alert("middle button"); 
		}
	});
});
$('[data-toggle="tooltip"]').tooltip();
/**
 (function(o) {
 // keep old log method
 var _log = o.log;
 o.log = function(e) {
 alert(e);
 _log.call(o, e);
 }
 }(console));
 ***/