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


$file_name = 'test';
$bs = new jsmBootstrap();
$form_input = $html = null;

if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}
if (!isset($_SESSION["PROJECT"]['menu']))
{
    header('Location: ./?page=menu&err=new');
    die();
}
$bs = new jsmBootstrap();
$content = $out_path = $footer = $html = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-database fa-stack-1x"></i></span>Backend Tools -&raquo; (IMAB) Others</h4>';

$content .= '<div class="col-md-4">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h4 class="panel-title">'.__('Other PHP Backend').'</h4></div>';
$content .= '<div class="panel-body">';
$content .= '<div class="table-responsive">';
$content .= '<table class="table table-striped">';
$content .= '<thead>';
$content .= '<tr>';
$content .= '<th>'.__('File Name').'</th>';
$content .= '<th>'.__('Action').'</th>';
$content .= '</tr>';
$content .= '</thead>';
foreach (glob("system/includes/backend-others/*.phps") as $filename)
{
    $content .= '<tr>';
    $content .= '<td>' . str_replace('.phps', '.php', basename($filename)) . '</td>';
    $content .= '<td><a class="btn btn-danger btn-sm" href="./?page=z-others&prefix=' . basename($filename) . '">'.__('View Source').'</a></td>';
    $content .= '</tr>';
}
$content .= '</table>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '<div class="col-md-8">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h4 class="panel-title">'.__('Source Code').'</h4></div>';
$content .= '<div class="panel-body">';
if (isset($_GET['prefix']))
{
    $filename_backend = "system/includes/backend-others/" . basename($_GET['prefix']);
    if (file_exists($filename_backend))
    {
        $content .= '<textarea id="code-php">';
        $content .= htmlentities(file_get_contents($filename_backend));
        $content .= '</textarea>';

    }
}
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

$footer = '
<link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">
<script src="./templates/default/vendor/codemirror/lib/codemirror.js"></script>
<script src="./templates/default/vendor/codemirror/mode/clike/clike.js"></script>
<script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
<script src="./templates/default/vendor/codemirror/mode/php/php.js"></script>
<script src="./templates/default/vendor/codemirror/mode/sql/sql.js"></script>
<script type="text/javascript">
  var editor = CodeMirror.fromTextArea(document.getElementById("code-php"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true
  });
</script>
';
$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Helper Tools -&raquo; FAQs';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>