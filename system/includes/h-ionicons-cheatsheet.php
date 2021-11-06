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

$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="ion-ionic fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) Ionicons Cheatsheet</h4>';
$content .= '<p>List of ionicons classname</p>';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-body">';
$content .= '<div class="row">';
$ionicons = new jsmIonicon();
foreach($ionicons->iconList() as $icon){

    $content .= '<div class="col-xs-1 text-center"><div class="ionicons_list"><i class="fa-2x icon ion-'.$icon['var'].'"></i><br/><input class="form-control" value="ion-'.$icon['var'].'"/></div></div>';
}
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Helper Tools -&raquo; Ionicons Cheatsheet';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>