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
$color["positive"] = "#387ef5";
$color["calm"] = "#11c1f3";
$color["balanced"] = "#33cd5f";
$color["energized"] = "#ffc900";
$color["assertive"] = "#ef473a";
$color["royal"] = "#886aea";
$color["dark"] = "#444444";
$color["positive-900"] = "#1A237E";
$color["calm-900"] = "#0D47A1";
$color["balanced-900"] = "#1B5E20";
$color["energized-900"] = "#E65100";
$color["assertive-900"] = "#B71C1C";
$color["royal-900"] = "#311B92";
$file_name = 'test';
$bs = new jsmBootstrap();
$css_content = $html = $content = null;
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
$out_path = 'output/' . $file_name;
if (!isset($_GET['prefix']))
{
    $_GET['prefix'] = '';
}

$css_path = 'projects/' . $file_name . '/css.json';

if (isset($_GET['delete']))
{
    @unlink($css_path);
    buildIonic($file_name);
    header('Location: ./?page=x-custom-css&err=null&notice=delete');
    die();
}

if (isset($_POST['css-save']))
{
    $css_code = $_POST['css'];
    file_put_contents($css_path, json_encode(array('css' => $css_code)));
    buildIonic($file_name);
    header('Location: ./?page=x-custom-css&err=null&notice=save');
    die();
}
$_color = array_values($color);
$raw_css['css'] = "\r\n";
$raw_css['css'] .= '/** menu **/' . "\r\n";
$raw_css['css'] .= "\r\n";
$raw_css['css'] .= '.menu .bar.bar-header.expanded {' . "\r\n";
$raw_css['css'] .= "\t".'/** background-size: 100% !important; **/' . "\r\n";
$raw_css['css'] .= '}' . "\r\n";
$raw_css['css'] .= "\r\n";
$z=0;
foreach ($_SESSION["PROJECT"]['menu']['items'] as $menu)
{
        if ($z == 12)
    {
        $z = 0;
    }

    $raw_css['css'] .= '.menu-' . $menu['var'] . ' .icon{' . "\r\n\t/** color:" . $_color[$z] . "; **/\r\n" . '}' . "\r\n\r\n";
$z++;
}
$raw_css['css'] .= "\r\n";
$raw_css['css'] .= '/** page **/' . "\r\n";
foreach ($_SESSION["PROJECT"]['page'] as $page)
{
    $raw_css['css'] .= '#page-' . $page['prefix'] . ',.page-' . $page['prefix'] . '{' . "\r\n\t/** font-size:14px !important **/\r\n" . '}' . "\r\n\r\n";
}


if (file_exists($css_path))
{
    $raw_css = json_decode(file_get_contents($css_path), true);
}

$css_content .= '<div class="panel panel-default">';
$css_content .= '<div class="panel-heading">';
$css_content .= '<h5 class="panel-title">'.__('General').'</h5>';
$css_content .= '</div>';
$css_content .= '<div class="panel-body">';
$css_content .= '<p>'.__('Write the css code like on the web/blog generally').'</p>';
$css_content .= '<textarea name="css" id="css">' . $raw_css['css'] . '</textarea>';
$css_content .= '<p>'.__('Press <strong>ctrl-space</strong> to activate autocompletion.').'</p>';
$css_content .= '</div>';
$css_content .= '</div>';

$button[] = array(
    'name' => 'css-save',
    'label' => __('Save CSS Code').' &raquo;',
    'tag' => 'submit',
    'color' => 'primary');
$button[] = array(
    'label' => __('Reset'),
    'tag' => 'reset',
    'color' => 'warning');
if (file_exists($css_path))
{
    $button[] = array(
        'label' => __('Delete'),
        'icon' => 'glyphicon glyphicon glyphicon-trash',
        'tag' => 'anchor',
        'color' => 'danger',
        'link' => "./?page=x-custom-css&delete");
}
$css_content .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, $button));

$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-css3 fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Custom CSS</h4>';
$content .= notice();
$content .= $bs->Forms('app-setup', '', 'post', 'default', $css_content);
 


$footer = '
<link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">

<link rel="stylesheet" href="./templates/default/vendor/codemirror/addon/hint/show-hint.css">
<script src="./templates/default/vendor/codemirror/lib/codemirror.js"></script>
<link rel="stylesheet" href="./templates/default/vendor/codemirror/theme/'.JSM_THEME_CODEMIRROR.'.css">
<script src="./templates/default/vendor/codemirror/mode/css/css.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/show-hint.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/css-hint.js"></script>
 
<script type="text/javascript">
  var editor = CodeMirror.fromTextArea(document.getElementById("css"), {
    lineNumbers: true,
    theme: "'.JSM_THEME_CODEMIRROR.'",
    mode: "text/css",
    extraKeys: {"Ctrl-Space": "autocomplete"},
  });
</script>
';

$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Custom CSS';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;

?>