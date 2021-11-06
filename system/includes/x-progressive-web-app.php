<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

$file_name = 'test';
$bs = new jsmBootstrap();
$form_input = $html = $footer = null;

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




if (isset($_POST['pwa-save']))
{
    if (!is_dir('projects/' . $file_name))
    {
        mkdir('projects/' . $file_name, 0777, true);
    }
    if (isset($_POST['pwa']['enable']))
    {
        $data['pwa']['enable'] = true;
    } else
    {
        $data['pwa']['enable'] = false;
    }

    if (isset($_POST['pwa']['service-workers']['enable']))
    {
        $data['pwa']['service-workers']['enable'] = true;
    } else
    {
        $data['pwa']['service-workers']['enable'] = false;
    }

    file_put_contents('projects/' . $file_name . '/pwa.json', json_encode($data));
    buildIonic($file_name);
    header('Location: ./?page=x-progressive-web-app&notice=save&err=null');
}
if (file_exists('projects/' . $file_name . '/pwa.json'))
{
    $raw_pwa = json_decode(file_get_contents('projects/' . $file_name . '/pwa.json'), true);
}
  if (!isset($raw_pwa['pwa']['service-workers']['enable']))
    {
        $raw_pwa['pwa']['service-workers']['enable'] = false;
    }

$content = null;
$footer = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-cogs fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) PWA - Progressive Web App</h4>';


//$form_input .= '<div class="panel panel-default">';
//$form_input .= '<div class="panel-heading">';
//$form_input .= '<h4 class="panel-title">Add To Home Screen</h4>';
//$form_input .= '</div>';
////$form_input .= '<div class="panel-body">';
//$form_input .= '<p>Supports add to home, can not be turned off.</p>';
//$form_input .= '</div>';
//$form_input .= '</div>';

$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h4 class="panel-title">Service Workers</h4>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';
$checked = '';
if($raw_pwa['pwa']['service-workers']['enable']==true){
    $checked = 'checked';
}

$form_input .= '<blockquote class="blockquote blockquote-info">';
$form_input .= '<p>What is a service worker. A service worker is a script that your browser runs in the background, separate from a web page, opening the door to features that don\'t need a web page or user interaction.</p>';
$form_input .= '<footer>Source: <cite title="Source Title"><a href="https://developers.google.com/web/fundamentals/primers/service-workers/" target="_blank">Web Fundamentals</a></cite></footer>';
$form_input .= '</blockquote>';
$form_input .= '<blockquote class="blockquote blockquote-danger">';
$form_input .= '<p>The Service Worker will work only on domains that use <code>ssl</code>, download <a target="_blank" href="./download.php?download=output&prefix='.$file_name.'">(IMA) Output</a> then unzip and upload all files in <code>www folder</code> to your server</p>';
$form_input .= '</blockquote>';
$form_input .= $bs->FormGroup('pwa[service-workers][enable]', 'default', 'checkbox', '', 'Enable service workers', '', $checked, '12');

$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'pwa-save',
        'label' => __('Save PWA') . ' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'), array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));
$content .= $bs->Forms('app-setup', '', 'post', 'default', $form_input);

$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h4 class="panel-title">How to install PWA</h4>';
$content .= '</div>';
 
$content .= '<div class="panel-body">';

$content .= '<div class="row">';
$content .= '<div class="col-md-6 text-center">';
$content .= '<img src="./templates/default/img/pwa-android.gif" width="330" height="610" />';
$content .= '</div>';
$content .= '<div class="col-md-6 text-center">';
$content .= '<img src="./templates/default/img/pwa-ios.gif" width="330" height="610" />';
$content .= '</div>';
$content .= '</div>';

$content .= '</div>';
$content .= '</div>';

$out_path = 'output/' . $file_name;
$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'PWA - Progressive Web App';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = true;

?>