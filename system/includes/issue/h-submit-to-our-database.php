<?php

/**
 * @author Jasman
 * @copyright 2016
 */


if (!defined('JSM_EXEC'))
{
    die(':)');
}

if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}

$content = $footer = null;
$bs = new jsmBootstrap();
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-database fa-stack-1x"></i></span>Helper - Submit to Our Database</h4>';



$form_input = null;
 
$form_input .= '<p>What are the benefits? No guarantees, but we\'ll help you</p>';
$form_input .= '<ul>';
$form_input .= '<li>We will display your app on our website.</li>';
$form_input .= '<li>We will help to improve the SEO of your application.</li>';
$form_input .= '</ul>';

$form_input .= $bs->FormGroup('database[app_name]', 'horizontal', 'text', 'App Name', '&nbsp;', '', '', '8', $_SESSION['PROJECT']['app']['name']);
$form_input .= $bs->FormGroup('database[app_desc]', 'horizontal', 'textarea', 'App Description', '&nbsp;', '', '', '8', $_SESSION['PROJECT']['app']['description']);
$form_input .= $bs->FormGroup('database[app_author]', 'horizontal', 'text', 'Author', '&nbsp;', '', '', '8', $_SESSION['PROJECT']['app']['author_name']);
$form_input .= $bs->FormGroup('database[app_categories]', 'horizontal', 'text', 'Categories', '&nbsp;', '', '', '8', '');
$form_input .= '<hr/>';
$form_input .= $bs->FormGroup(null, 'horizontal', 'html', 'Download Link');
$form_input .= $bs->FormGroup('database[app_download_android]', 'horizontal', 'text', 'Android', '&nbsp;', '', '', '8', '');
$form_input .= $bs->FormGroup('database[app_download_iphone]', 'horizontal', 'text', 'iPhone', '&nbsp;', '', '', '8', '');
$form_input .= $bs->FormGroup('database[app_download_window]', 'horizontal', 'text', 'Window', '&nbsp;', '', '', '8', '');

$form_input .= $bs->FormGroup(null, 'horizontal', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'submit',
        'label' => 'Submit APP',
        'tag' => 'submit',
        'color' => 'primary'), array(
        'label' => 'Reset',
        'tag' => 'reset',
        'color' => 'default'))));

$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">Apps Galeries</h5>';
$content .= '</div>';

$content .= '<div class="panel-body">';
$content .= '<div class="row">';
$content .= '<div class="col-md-12">';
$content .= $bs->Forms('submit-form', 'http://ihsana.net/pub/submit.php', 'post', 'horizontal', $form_input);
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';


$out_path = 'output/' . $file_name;
$template->title = $template->base_title . ' | ' . 'Submit to Our Database';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->demo_url = $out_path .'/www/';

?>