<?php

 

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */
if (!defined('JSM_EXEC'))
{
    die(':)');
}
$bs = new jsmBootstrap();
$file_name = 'test';
if (!isset($_GET['prefix']))
{
    $_GET['prefix'] = null;
}
if (!isset($_SESSION['JSON_TEST']))
{
    $_SESSION['JSON_TEST'] = '';
}
$content = $out_path = null;
if (isset( $_POST['submit']))
{
    $_SESSION['JSON_TEST'] = json_decode( $_POST['raw_json'],true);
}

if(defined("JSON_PRETTY_PRINT")){
	$bjson = json_encode( $_SESSION['JSON_TEST'],JSON_PRETTY_PRINT);
}else{
	$bjson = json_encode( $_SESSION['JSON_TEST']);
}
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-code fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) JSON Viewer</h4>';
$content .= '<form action="" method="post">';
$content .= '<textarea id="raw_json" name="raw_json">'.$bjson.'</textarea>';
$content .= '<br/><input type="submit" class="btn btn-danger" name="submit" value="'.__('JSON PRETTY PRINT').'" />';
$content .= '</form>';

//$content .= '<pre>';
//$content .= print_r($_SESSION['JSON_TEST'],true);
//$content .= '</pre>';

$footer = '
<link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="./templates/default/vendor/codemirror/theme/'.JSM_THEME_CODEMIRROR.'.css">
<link rel="stylesheet" href="./templates/default/vendor/codemirror/addon/hint/show-hint.css">
<link rel="stylesheet" href="./templates/default/vendor/codemirror/addon/fold/foldgutter.css">
<script src="./templates/default/vendor/codemirror/lib/codemirror.js"></script>
<script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
<script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
<script src="./templates/default/vendor/codemirror/addon/edit/matchbrackets.js"></script>
<script src="./templates/default/vendor/codemirror/addon/fold/foldcode.js"></script>
<script src="./templates/default/vendor/codemirror/addon/fold/foldgutter.js"></script>
<script src="./templates/default/vendor/codemirror/addon/fold/brace-fold.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/show-hint.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/javascript-hint.js"></script>
<script type="text/javascript">
  var editor = CodeMirror.fromTextArea(document.getElementById("raw_json"), {
    theme: "'.JSM_THEME_CODEMIRROR.'",
    lineNumbers: true,
    foldGutter: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
    mode: "application/ld+json",
    extraKeys: {"Ctrl-Space": "autocomplete"},
  }); 
  editor.setSize("100%", 1200);
  
  $("#raw_file").change(function(){
        window.location = "./?page=z-json-raw&edit=edit&raw_file=" + $(this).val();
  });
</script>
';
 
$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Helper Tools -&raquo; JSON Tester';
$template->base_desc = 'tools';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>