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
$form_input = $content = $html = null;

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
if (isset($_POST['metatags-save']))
{
    if (!is_dir('projects/' . $file_name))
    {
        mkdir('projects/' . $file_name, 0777, true);
    }
    $data['metatags'] = $_POST['metatags'];
    file_put_contents('projects/' . $file_name . '/metatags.json', json_encode($data));
    buildIonic($file_name);
    header('Location: ./?page=x-custom-meta-tags&notice=save&err=null');
}
$raw_data['metatags']['tags'] = '';
if (file_exists('projects/' . $file_name . '/metatags.json'))
{
    $raw_data = json_decode(file_get_contents('projects/' . $file_name . '/metatags.json'), true);
}


$subpage_path = str2var($_SESSION["PROJECT"]['menu']['title']);
$out_path = 'output/' . $file_name;

$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-puzzle-piece fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Custom Meta Tags</h4>';


$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">'.__('General').'</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';

$content .= '<blockquote class="blockquote blockquote-info">'.__('This menu used for custom Meta Tags HTML').'</blockquote>';

$input_content = null;
$input_content .= $bs->FormGroup('metatags[tags]', 'default', 'textarea', '', 'Help', '', '', '8', $raw_data['metatags']['tags']);
$input_content .= ''.__('Example:').'<br/><code style="padding:0;font-size: 12px;font-family: courier;">' . htmlentities("<meta http-equiv=\"Content-Security-Policy\" \r\ncontent=\"default-src *; style-src * 'self' 'unsafe-inline' 'unsafe-eval'; script-src * 'self' 'unsafe-inline' 'unsafe-eval';\">") . '</code><br/><br/>';

$input_content .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'metatags-save',
        'label' => __('Save Meta Tags Code').' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'), array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));

$content .= $bs->Forms('app-setup', '', 'post', 'default', $input_content);
$content .= '</div>';
$content .= '</div>';

            $footer = '
<link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="./templates/default/vendor/codemirror/addon/hint/show-hint.css">
<script src="./templates/default/vendor/codemirror/lib/codemirror.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/show-hint.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/xml-hint.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/html-hint.js"></script>
<script src="./templates/default/vendor/codemirror/mode/xml/xml.js"></script>
<script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
<script src="./templates/default/vendor/codemirror/mode/css/css.js"></script>
<script src="./templates/default/vendor/codemirror/mode/htmlmixed/htmlmixed.js"></script>
  
<script type="text/javascript">
  var editor = CodeMirror.fromTextArea(document.getElementById("metatags_tags_"), {
    lineNumbers: true,
    mode: "text/html",
    extraKeys: {"Ctrl-Space": "autocomplete"}
  });
</script>
';

$template->demo_url = $out_path . '/www/';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Custom Meta Tags';
$template->base_desc = 'Custom Meta Tags';
$template->content = $content;
$template->footer = '';
$template->emulator = true;
$template->footer = $footer;
?>