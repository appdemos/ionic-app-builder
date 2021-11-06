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

$subpage_path = str2var($_SESSION["PROJECT"]['menu']['title']);

if (!isset($_GET['prefix']))
{
    $_GET['prefix'] = '';
}


$code = $output = null;
if (isset($_POST['convert']))
{
    $code = $_POST['unicode-string'];
    $output .= '<div class="panel panel-default">';
    $output .= '<div class="panel-heading">';
    $output .= '<h5 class="panel-title">Result</h5>';
    $output .= '</div>';
    $output .= '<div class="panel-body">';
    $output .= '<pre><code>' . htmlentities(json_encode($_POST['unicode-string'])) . '</code></pre>';
    $output .= '</div>';
    $output .= '</div>';
}

$footer = $content = $form_content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-database fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) Unicode Converter</h4>';
$form_content .= '<div class="panel panel-default">';
$form_content .= '<div class="panel-heading">';
$form_content .= '<h5 class="panel-title">String</h5>';
$form_content .= '</div>';
$form_content .= '<div class="panel-body">';
$form_content .= '<textarea id="unicode-string" name="unicode-string" class="form-control">' . $code . '</textarea>';
$form_content .= '</div>';
$form_content .= '</div>';

$button[] = array(
    'name' => 'convert',
    'label' => 'Convert &raquo;',
    'tag' => 'submit',
    'color' => 'primary');

$button[] = array(
    'label' => 'Reset',
    'tag' => 'reset',
    'color' => 'warning');

$form_content .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, $button));
$content .= $bs->Forms('app-setup', '', 'post', 'default', $form_content);
$content .= ($output);

$template->title = $template->base_title . ' | ' . 'Helper Tools -&raquo; Unicode Converter';
$footer = '';

$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>