<?php

if(!defined('JSM_EXEC'))
{
    die(':)');
}


$file_name = 'test';
$bs = new jsmBootstrap();
$form_input = $content = $html = null;

if(isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}
if(!isset($_SESSION["PROJECT"]['menu']))
{
    header('Location: ./?page=menu&err=new');
    die();
}
if(isset($_POST['configxml-save']))
{
    if(!is_dir('projects/'.$file_name))
    {
        mkdir('projects/'.$file_name,0777,true);
    }
    $data['configxml'] = $_POST['configxml'];

    file_put_contents('projects/'.$file_name.'/configxml.json',json_encode($data));
    buildIonic($file_name);
    header('Location: ./?page=x-custom-config-xml&notice=save&err=null');
}
$raw_data['configxml']['tags'] = '';
if(file_exists('projects/'.$file_name.'/configxml.json'))
{
    $raw_data = json_decode(file_get_contents('projects/'.$file_name.'/configxml.json'),true);
}


if(!isset($raw_data['configxml']['code']))
{
    $raw_data['configxml']['code'] = '
    <preference name="Orientation" value="default"/>
    <preference name="StatusBarOverlaysWebView" value="true" />
    <preference name="StatusBarStyle" value="lightcontent" />
    <preference name="StatusBarBackgroundColor" value="#000000" />
    <allow-navigation href="http://localhost:8080/*"/>
    <feature name="CDVWKWebViewEngine">
        <param name="ios-package" value="CDVWKWebViewEngine" />
    </feature>
    <preference name="CordovaWebViewEngine" value="CDVWKWebViewEngine" />
    <preference name="ScrollEnabled" value="true" />
';
    $raw_data['configxml']['code'] = '';
    $raw_data['configxml']['statusbar-style'] = 'lightcontent';
    $raw_data['configxml']['statusbar-bgcolor'] = '#dddddd';

}

$subpage_path = str2var($_SESSION["PROJECT"]['menu']['title']);
$out_path = 'output/'.$file_name;

$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-puzzle-piece fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Custom Config.XML</h4>';


$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">'.__('General').'</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';


$input_content = null;

if(!isset($raw_data['configxml']['statusbar-style']))
{
    $raw_data['configxml']['statusbar-style'] = 'blackopaque';
}

if(!isset($raw_data['configxml']['statusbar-bgcolor']))
{
    $raw_data['configxml']['statusbar-bgcolor'] = '#dddddd';
}

if(!isset($raw_data['configxml']['orientation']))
{
    $raw_data['configxml']['orientation'] = 'default';
}
if(!isset($raw_data['configxml']['phonegap_cli']))
{
    $raw_data['configxml']['phonegap_cli'] = 'cli-8.0.0';
}


$statusbar_options[] = array('label' => 'Default','value' => 'default');
//$statusbar_options[] = array('label' => 'LightContent (default)','value' => 'lightcontent');
//$statusbar_options[] = array('label' => 'BlackTranslucent','value' => 'blacktranslucent');
//$statusbar_options[] = array('label' => 'BlackOpaque','value' => 'blackopaque');

$html_statusbar = null;
$html_statusbar .= '<select name="configxml[statusbar-style]" class="form-control">';
foreach($statusbar_options as $statusbar_option)
{
    $selected_option = '';
    if($statusbar_option['value'] == $raw_data['configxml']['statusbar-style'])
    {
        $selected_option = 'selected';
    }
    $html_statusbar .= '<option value="'.$statusbar_option['value'].'" '.$selected_option.'>'.$statusbar_option['label'].'</option>';
}
$html_statusbar .= '</select>';


$input_content .= '<blockquote class="blockquote blockquote-info">'.__('This is required to install the <code>cordova-plugin-statusbar</code> plugin').'<footer>ref: <a target="blank" href="https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-statusbar/index.html">cordova plugin statusbar</a></footer></blockquote>';


$input_content .= '<div class="row">';
$input_content .= '<div class="col-md-6">';
$input_content .= '<label>StatusBar Style</label>';
$input_content .= $html_statusbar;
$input_content .= '</div>';
$input_content .= '<div class="col-md-6">';
$input_content .= '<label>StatusBar Background Color</label>';
$input_content .= '<div data-type="color-picker" class="input-group colorpicker-component"><input name="configxml[statusbar-bgcolor]" type="text" value="'.$raw_data['configxml']['statusbar-bgcolor'].'" class="form-control" /><span class="input-group-addon"><i></i></span></div>';
$input_content .= '<small>value: a hex string (#RRGGBB)</small>';
$input_content .= '</div>';
$input_content .= '</div>';
$input_content .= '<hr/>';


$orientation_options[] = array('label' => 'Default','value' => 'default');
$orientation_options[] = array('label' => 'Landscape','value' => 'landscape');
$orientation_options[] = array('label' => 'Portrait','value' => 'portrait');

$html_orientation = null;
$html_orientation .= '<select name="configxml[orientation]" class="form-control">';
foreach($orientation_options as $orientation_option)
{
    $selected_option = '';
    if($orientation_option['value'] == $raw_data['configxml']['orientation'])
    {
        $selected_option = 'selected';
    }
    $html_orientation .= '<option value="'.$orientation_option['value'].'" '.$selected_option.'>'.$orientation_option['label'].'</option>';
}
$html_orientation .= '</select>';

$input_content .= '<div class="row">';
$input_content .= '<div class="col-md-6">';
$input_content .= '<label>Screen Orientation</label>';
$input_content .= $html_orientation;
$input_content .= '<small>Allowed values: default, landscape, portrait</small>';
$input_content .= '</div>';

$input_content .= '<div class="col-md-6">';
$input_content .= '<label>Phonegap CLI</label>';
$input_content .= '<input name="configxml[phonegap_cli]" type="text" value="'.$raw_data['configxml']['phonegap_cli'].'" class="form-control" />';
$input_content .= '<small>Default values: cli-8.0.0, read: <a target="_blank" href="https://build.phonegap.com/current-support">Currently Supported PhoneGap Versions</a></small>';
$input_content .= '</div>';
$input_content .= '</div>';
$input_content .= '<hr/>';


$input_content .= '<blockquote class="blockquote blockquote-info">'.__('This menu used for custom config.xml').'<footer>ref: <a target="blank" href="https://cordova.apache.org/docs/en/latest/config_ref/">cordova config</a></footer></blockquote>';

$input_content .= $bs->FormGroup('configxml[code]','default','textarea','Custom config.xml','','','','8',$raw_data['configxml']['code']);

$input_content .= ''.__('Example:').'<br/><code style="padding:0;font-size: 12px;font-family: courier;">'.htmlentities("<preference name=\"ScrollEnabled\" value=\"true\" />").'</code><br/><br/>';

$input_content .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,array(array(
        'name' => 'configxml-save',
        'label' => __('Save Config XML').' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'),array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));

$content .= $bs->Forms('app-setup','','post','default',$input_content);
$content .= '</div>';
$content .= '</div>';

$footer = '
<link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="./templates/default/vendor/codemirror/theme/'.JSM_THEME_CODEMIRROR.'.css">
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
  var editor = CodeMirror.fromTextArea(document.getElementById("configxml_code_"), {
    lineNumbers: true,
    theme: "'.JSM_THEME_CODEMIRROR.'",
    mode: "text/html",
    extraKeys: {"Ctrl-Space": "autocomplete"}
  });
  
  
 $("div[data-type=\'color-picker\']").colorpicker();
 

</script>
';

$template->demo_url = $out_path.'/www/';
$template->title = $template->base_title.' | '.'Extra Menus -&raquo; Custom Meta Tags';
$template->base_desc = 'Custom Config.XML';
$template->content = $content;
$template->footer = '';
$template->emulator = true;
$template->footer = $footer;

?>