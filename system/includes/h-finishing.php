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
$form_input = $html = $js_helper = $content = $footer = null;
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
$postdata['old_url'] = 'http://' . $_SERVER["HTTP_HOST"];
$postdata['new_url'] = '';
$file_replace = $message_debugger = null;
if (isset($_POST['replace-url']))
{
    $data_json = null;

    $postdata['old_url'] = $_POST['finishing']['old_url'];
    $postdata['new_url'] = $_POST['finishing']['new_url'];
    $old_url = json_encode($postdata['old_url']);
    $new_url = json_encode($postdata['new_url']);

    $_postdata['old_url'] = substr($old_url, 1, (strlen($old_url) - 2));
    $_postdata['new_url'] = substr($new_url, 1, (strlen($new_url) - 2));


    foreach (glob("projects/" . $file_name . "/*.json") as $raw_json)
    {
        $file_replace .= '<strong>' . basename($raw_json) . '</strong> : <code>' . $postdata['old_url'] . '</code> replace with <code>' . $_postdata['new_url'] . '</code><br/>';

        $data_json = file_get_contents($raw_json);
        $data_json = str_replace($_postdata['old_url'], $_postdata['new_url'], $data_json);
        file_put_contents($raw_json, $data_json);
    }
    $file_replace .= '<br/><br/>';
    buildIonic($file_name);
}

if (isset($_POST['submit-debug']))
{
    foreach (glob("projects/" . $file_name . "/tables.*.json") as $raw_json)
    {
        $message_debugger .= '* Update Table <strong>' . str_replace( array( 'tables.','.json'),'', basename($raw_json)) . '</strong><br/>';

        $data_json = file_get_contents($raw_json);
        if ($_POST['debugger']['status'] == 'disable')
        {
            $data_json = str_replace('"error_messages":"true"', '"error_messages":"false"', $data_json);
        } else
        {
            $data_json = str_replace('"error_messages":"false"', '"error_messages":"true"', $data_json);
        }
        file_put_contents($raw_json, $data_json);
    }
    $message_debugger .= '<br/><br/>';
    buildIonic($file_name);
}
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-question fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) Finishing</h4>';
$content .= '<div class="row">';
$content .= '<div class="col-md-6">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h4 class="panel-title">(IMAB) Text Replacer</h4></div>';
$content .= '<div class="panel-body">';
$content .= '<blockquote class="blockquote blockquote-danger"><h4>The rules that apply are:</h4>This menu for replace Text in this project. Example when done create a project, you need changing URL on localhost with URL on live server, you can using this menu for replacing all that URL by one click, so <ins>backup your project</ins> before use this menu.</blockquote>';
$form_content = null;
$form_content = $file_replace;
$form_content .= $bs->FormGroup('finishing[old_url]', 'default', 'text', 'Old Text/URL', 'http://localhost/myapp/', 'Enter old URL API', '', '8', $postdata['old_url']);
$form_content .= $bs->FormGroup('finishing[new_url]', 'default', 'text', 'New Text/URL', 'http://domain.com/', 'Enter old URL API', '', '8', $postdata['new_url']);

$button[] = array(
    'name' => 'replace-url',
    'label' => 'Replace All URL &raquo;',
    'tag' => 'submit',
    'color' => 'primary');
$form_content .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, $button));
$content .= $bs->Forms('app-setup', '', 'post', 'default', $form_content);

$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '<div class="col-md-6">';

$button = null;

$form_debugger = null;

$notice_options[] = array('label' => 'All Disable', 'value' => 'disable');
$notice_options[] = array('label' => 'All Enable', 'value' => 'enable');

$form_debugger .= $bs->FormGroup('debugger[status]', 'default', 'select', 'Show Error Table', $notice_options);
$button[] = array(
    'name' => 'submit-debug',
    'label' => 'Update &raquo;',
    'tag' => 'submit',
    'color' => 'primary');
$form_debugger .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, $button));

$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h4 class="panel-title">(IMAB) Debugger</h4></div>';
$content .= '<div class="panel-body">';
$content .= $message_debugger;
$content .= $bs->Forms('app-setup', '', 'post', 'default', $form_debugger);
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

$template->demo_url = $out_path . '/www/';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Form Request';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;

?>