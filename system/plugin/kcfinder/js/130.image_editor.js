_.photoEditor = function(data) {
	var ts = new Date().getTime();
    var url = $.$.escapeDirs(_.uploadURL + "/" + _.dir + "/" + data.name) + "?ts=" + ts;
	var newwindow = window.open('./../../raw.php?page=admin&sub=crop_images&src=' + url, 'Image Editor', 'height=600,width=1000');
	if (window.focus) {
		newwindow.focus()
	}
};
