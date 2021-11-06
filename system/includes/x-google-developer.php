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

if (isset($_POST['update']))
{
    $google_api_key = trim($_POST['google']['api-key']);
    foreach (glob("projects/" . $file_name . "/tables.*.json") as $raw_json)
    {
        $var_table = str_replace(array('tables.', '.json'), '', basename($raw_json));
        $data_json = json_decode(file_get_contents($raw_json), true);


        $data_json['tables'][$var_table]['option']['gmap']['api_key'] = $google_api_key;
        $data_json['tables'][$var_table]['option']['youtube']['api_key'] = $google_api_key;
        file_put_contents($raw_json, json_encode($data_json));
    }

    file_put_contents($raw_json, json_encode($data_json));

    $google_data['google']['api-key'] = $google_api_key;
    file_put_contents("projects/" . $file_name . "/google.json", json_encode($google_data));

    buildIonic($file_name);
}

 

$out_path = 'output/' . $file_name;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-question fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) Google Developer</h4>';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h4 class="panel-title">' . __('General') . '</h4>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= '<table class="table table-striped">';
$content .= '<tr>';
$content .= '<th>' . __('Tables') . '</th>';
$content .= '<th>' . __('API Key') . '</th>';
$content .= '</tr>';
foreach ($_SESSION['PROJECT']['tables'] as $arr_table)
{
    $content .= '<tr>';
    $content .= '<td>' . $arr_table['prefix'] . '</td>';
    $content .= '<td><pre>' . $arr_table['option']['gmap']['api_key'] . '&nbsp;</pre></td>';
    $content .= '</tr>';
}
$content .= '</table>';
$gapi_key = '';
if(isset($_SESSION['PROJECT']['google']['api-key'])){
    $gapi_key = $_SESSION['PROJECT']['google']['api-key'] ;
}
$content .= '
<form method="post" enctype="multipart/form-data">
    <div class="input-group">
        <input type="text" name="google[api-key]" class="form-control" placeholder="AIzaSyAVLRZEgsoGb4OnId_aHxgm396LfeOA44k" value="'.$gapi_key.'">
        <span class="input-group-btn">
            <input type="submit" name="update" class="btn btn-primary" value="' . __('Update API Key') . '" />
        </span>
    </div>
</form>
';

$content .= '</div>';
$content .= '</div>';

$template->demo_url = $out_path . '/www/';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Google Developer';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;

?>