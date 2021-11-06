<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2018
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

if(!defined('JSM_EXEC'))
{
    die(':)');
}


$file_name = 'test';
$bs = new jsmBootstrap();
$form_input = $html = null;

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
$bs = new jsmBootstrap();
$content = $out_path = $footer = $html = null;

if(isset($_POST['plugin-save']))
{
    // TODO: GET POST BARCODESCANNER
    if(isset($_POST['cordova_plugin']['barcodescanner']['enable']))
    {
        $_POST['cordova_plugin']['barcodescanner']['enable'] = true;
    } else
    {
        $_POST['cordova_plugin']['barcodescanner']['enable'] = false;
    }
    // TODO: GET POST GEOLOCATION
    if(isset($_POST['cordova_plugin']['geolocation']['enable']))
    {
        $_POST['cordova_plugin']['geolocation']['enable'] = true;
    } else
    {
        $_POST['cordova_plugin']['geolocation']['enable'] = false;
    }
    // TODO: GET POST CLIPBOARD
    if(isset($_POST['cordova_plugin']['clipboard']['enable']))
    {
        $_POST['cordova_plugin']['clipboard']['enable'] = true;
    } else
    {
        $_POST['cordova_plugin']['clipboard']['enable'] = false;
    }
    // TODO: GET POST XSOCIALSHARING
    if(isset($_POST['cordova_plugin']['xsocialsharing']['enable']))
    {
        $_POST['cordova_plugin']['xsocialsharing']['enable'] = true;
    } else
    {
        $_POST['cordova_plugin']['xsocialsharing']['enable'] = false;
    }


    $raw_data['cordova_plugin'] = $_POST['cordova_plugin'];
    file_put_contents('projects/'.$file_name.'/cordova_plugin.json',json_encode($raw_data));


    // TODO: SAVE MOD BARCODESCANNER
    if($raw_data['cordova_plugin']['barcodescanner']['enable'] == true)
    {
        $mod = null;
        $mod['mod']['phonegap-plugin-barcodescanner']['name'] = 'phonegap-plugin-barcodescanner';
        $mod['mod']['phonegap-plugin-barcodescanner']['engines'] = 'cordova';
        file_put_contents('projects/'.$file_name.'/mod.phonegap-plugin-barcodescanner.json',json_encode($mod));
    } else
    {
        if(file_exists('projects/'.$file_name.'/mod.phonegap-plugin-barcodescanner.json'))
        {
            @unlink('projects/'.$file_name.'/mod.phonegap-plugin-barcodescanner.json');
        }
    }

    // TODO: SAVE MOD GEOLOCATION
    if($raw_data['cordova_plugin']['geolocation']['enable'] == true)
    {
        $mod = null;
        $mod['mod']['cordova-plugin-geolocation']['name'] = 'cordova-plugin-geolocation';
        $mod['mod']['cordova-plugin-geolocation']['engines'] = 'cordova';
        file_put_contents('projects/'.$file_name.'/mod.cordova-plugin-geolocation.json',json_encode($mod));
    } else
    {
        if(file_exists('projects/'.$file_name.'/mod.cordova-plugin-geolocation.json'))
        {
            @unlink('projects/'.$file_name.'/mod.cordova-plugin-geolocation.json');
        }
    }

    // TODO: SAVE MOD CLIPBOARD
    if($raw_data['cordova_plugin']['clipboard']['enable'] == true)
    {
        $mod = null;
        $mod['mod']['clipboard']['name'] = 'cordova-clipboard';
        $mod['mod']['clipboard']['engines'] = 'cordova';
        file_put_contents('projects/'.$file_name.'/mod.cordova-clipboard.json',json_encode($mod));
    } else
    {
        if(file_exists('projects/'.$file_name.'/mod.cordova-clipboard.json'))
        {
            @unlink('projects/'.$file_name.'/mod.cordova-clipboard.json');
        }
    }

    // TODO: SAVE MOD SOCIALXSHARING
    if($raw_data['cordova_plugin']['xsocialsharing']['enable'] == true)
    {
        $mod = null;
        $mod['mod']['xsocialsharing']['name'] = 'cordova-plugin-x-socialsharing';
        $mod['mod']['xsocialsharing']['engines'] = 'cordova';
        file_put_contents('projects/'.$file_name.'/mod.cordova-plugin-x-socialsharing.json',json_encode($mod));
    } else
    {
        if(file_exists('projects/'.$file_name.'/mod.cordova-plugin-x-socialsharing.json'))
        {
            @unlink('projects/'.$file_name.'/mod.cordova-plugin-x-socialsharing.json');
        }
    }

    buildIonic($file_name);
    //header('Location: ./?page=x-cordova-plugin-others&notice=save&err=null');

}

if(file_exists('projects/'.$file_name.'/cordova_plugin.json'))
{
    $raw_data = json_decode(file_get_contents('projects/'.$file_name.'/cordova_plugin.json'),true);
}

$_content = null;


// TODO: FORM BARCODE
$barcode_enable = '';
if(!isset($raw_data['cordova_plugin']['barcodescanner']['enable']))
{
    $raw_data['cordova_plugin']['barcodescanner']['enable'] = false;
}
if($raw_data['cordova_plugin']['barcodescanner']['enable'] == true)
{
    $barcode_enable = 'checked';
}
$_content .= '<h4>Barcode Scanner</h4>';
$_content .= $bs->FormGroup('cordova_plugin[barcodescanner][enable]','default','checkbox','','phonegap-plugin-barcodescanner','',$barcode_enable,'8');
if($raw_data['cordova_plugin']['barcodescanner']['enable'] == true)
{
    $_content .= '<h5>Example Code</h5>';
    $_content .= '<pre>'.htmlentities('
<div class="item item-input-inset">
  <label class="item-input-wrapper">
    <input ng-model="barcode_input" type="text" placeholder="shake your phone">
  </label>
  <a class="button button-small" barcode-scanner barcode-text="barcode_input">Bar Scanner</a>
</div>').'</pre>';
    $_content .= 'repo: <a target="_blank" href="https://github.com/phonegap/phonegap-plugin-barcodescanner">github</a>';
}
$_content .= '<hr/>';


// TODO: FORM GEOLOCATION
$geolocation_enable = '';
if(!isset($raw_data['cordova_plugin']['geolocation']['enable']))
{
    $raw_data['cordova_plugin']['geolocation']['enable'] = false;
}
if($raw_data['cordova_plugin']['geolocation']['enable'] == true)
{
    $geolocation_enable = 'checked';
}
$_content .= '<h4>GEO Location</h4>';
$_content .= $bs->FormGroup('cordova_plugin[geolocation][enable]','default','checkbox','','cordova-plugin-geolocation','',$geolocation_enable,'8');
if($raw_data['cordova_plugin']['geolocation']['enable'] == true)
{
    $_content .= '<h5>Example Code</h5>';
    $_content .= '<pre>'.htmlentities('
<div class="item item-input-inset">
  <label class="item-input-wrapper">
    <input ng-model="geo_input" type="text" >
  </label>
  <a class="button button-small" geo-location geo-text="geo_input">Location</a>
</div>').'</pre>';
    $_content .= 'repo: <a target="_blank" href="https://github.com/apache/cordova-plugin-geolocation">github</a>';
}
$_content .= '<hr/>';


// TODO: FORM CLIPBOARD
$clipboard_enable = '';
if(!isset($raw_data['cordova_plugin']['clipboard']['enable']))
{
    $raw_data['cordova_plugin']['clipboard']['enable'] = false;
}
if($raw_data['cordova_plugin']['clipboard']['enable'] == true)
{
    $clipboard_enable = 'checked';
}
$_content .= '<h4>Clipboard</h4>';
$_content .= $bs->FormGroup('cordova_plugin[clipboard][enable]','default','checkbox','','cordova-clipboard','',$clipboard_enable,'8');
if($raw_data['cordova_plugin']['clipboard']['enable'] == true)
{
    $_content .= '<h5>Example Code</h5>';
    $_content .= '<pre>'.htmlentities('<a clipboard-copy text="COPY THIS TEXT TO CLIPBOARD">Copy</a>').'</pre>';
    $_content .= 'repo: <a target="_blank" href="https://github.com/ihadeed/cordova-clipboard">github</a>';
}
$_content .= '<hr/>';


// TODO: FORM SOCIALXSHARING
$xsocialsharing_enable = '';
if(!isset($raw_data['cordova_plugin']['xsocialsharing']['enable']))
{
    $raw_data['cordova_plugin']['xsocialsharing']['enable'] = false;
}
if($raw_data['cordova_plugin']['xsocialsharing']['enable'] == true)
{
    $xsocialsharing_enable = 'checked';
}
$_content .= '<h4>Social Sharing</h4>';
$_content .= $bs->FormGroup('cordova_plugin[xsocialsharing][enable]','default','checkbox','','cordova-plugin-x-socialsharing','',$xsocialsharing_enable,'8');
if($raw_data['cordova_plugin']['xsocialsharing']['enable'] == true)
{
    //$_content .= '<h5>Example Code</h5>';
    //$_content .= '<pre>'.htmlentities('').'</pre>';
    $_content .= 'repo: <a target="_blank" href="https://github.com/EddyVerbruggen/SocialSharing-PhoneGap-Plugin/">github</a>';
}
$_content .= '<hr/>';


$_content .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,array(array(
        'name' => 'plugin-save',
        'label' => __('Save Native Plugin').' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'),array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));


$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-th fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Cordova Plugin Others (Native)</h4>';
$content .= '<blockquote class="blockquote blockquote-danger">';
$content .= '<h4>'.__('The rules that apply are:').'</h4>';
$content .= '<ul>';
$content .= '<li>'.__('Using many plugins will probably cause errors, so before using cordova plugins, make sure that there is no conflict between the plugins you use, by installing those plugins on blank projects.').'</li>';
$content .= '<li>'.__('By enabling the plugin, options on some features will increase as in the <code>(IMAB) Menu</code>, <code>(IMAB) Table</code>, <code>(IMAB) Page</code> and <code>(IMAB) Form</code>, if you disable it, please save it again like <code>(IMAB) Menu</code>, <code>(IMAB) Table</code>, <code>(IMAB) Page</code> and <code>(IMAB) Form</code>').'</li>';
$content .= '</ul>';

$content .= '<p>'.__('Test your cordova before using this features:').'</p>';
if(isset($_SESSION['PROJECT']['mod']))
{
    foreach($_SESSION['PROJECT']['mod'] as $mod)
    {
        $content .= '<pre class="shell">cordova plugin add '.$mod['name'].' --save</pre>'."\r\n";
    }
}

$content .= '</blockquote>';

$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h4 class="panel-title">Native Plugins</h4>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= $bs->Forms('native-save','','post','default',$_content);
$content .= '</div>';
$content .= '</div>';

$template->demo_url = $out_path.'/www/#/';
$template->title = $template->base_title.' | '.'Extra Menus -&raquo; (IMAB) Cordova Plugin Others';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>