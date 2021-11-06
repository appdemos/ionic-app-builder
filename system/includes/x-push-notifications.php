<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
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
$push_content = $html = $content = null;
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

$out_path = 'output/'.$file_name;
if(!isset($_GET['prefix']))
{
    $_GET['prefix'] = '';
}

$push_path = 'projects/'.$file_name.'/push.json';

if(isset($_POST['push-save']))
{
    $push_code = $_POST['push'];
    file_put_contents($push_path,json_encode(array('push' => $push_code)));
    $mod_push = 'projects/'.$file_name.'/mod.notification.json';
    if($push_code['plugin'] == 'none')
    {
        @unlink($mod_push);
    } else
    {
        $new_mod['mod']['notification']['name'] = $push_code['plugin'];
        $new_mod['mod']['notification']['engines'] = 'cordova';
        file_put_contents($mod_push,json_encode($new_mod));
    }
    buildIonic($file_name);
    header('Location: ./?page=x-push-notifications&err=null&notice=save');
    die();
}

$raw_push['push']['plugin'] = 'none';
$raw_push['push']['app_id'] = '';
$raw_push['push']['app_key'] = '';

if(file_exists($push_path))
{
    $raw_push = json_decode(file_get_contents($push_path),true);
}


$push_content = null;
$cordova_plugin[] = array('label' => __('none'),'value' => 'none');
$cordova_plugin[] = array('label' => 'onesignal-cordova-plugin (recommended)','value' => 'onesignal-cordova-plugin');


$z = 0;
foreach($cordova_plugin as $_cordova_plugin)
{
    $cordova_plugins[$z] = $_cordova_plugin;
    if($raw_push['push']['plugin'] == $_cordova_plugin['value'])
    {
        $cordova_plugins[$z]['active'] = true;
    }
    $z++;
}
if(!isset($raw_push['push']['app_key']))
{
    $raw_push['push']['app_key'] = '';
}
$push_content .= $bs->FormGroup('push[plugin]','default','select',__('Using Cordova Plugin'),$cordova_plugins,'','','8');
$push_content .= $bs->FormGroup('push[app_id]','default','text',__('AppID from OneSignal Site'),'c6c7cc44-75d3-4c0b-8a4b-d3a2e0432ece',__('Your OneSignal AppId, available in <a href="https://documentation.onesignal.com/docs/accounts-and-keys#section-keys-ids">OneSignal</a>'),'','8',htmlentities($raw_push['push']['app_id']));
$push_content .= $bs->FormGroup('push[app_key]','default','text',__('AppKey from OneSignal Site'),'ZThaNjNvOTctY2RjYi00ZjUxLTgxMTItNDg2NTRkNmY3MGVk',__('Your OneSignal AppKey, required for (IMAB) Web Admin Generator'),'','8',htmlentities($raw_push['push']['app_key']));

$list_page = array();
foreach($_SESSION['PROJECT']['page'] as $_page)
{
    if(isset($_page['query'][0]))
    {
        $slist_page = '<code>'.$_page['prefix'].'/[ID]'.'</code>';
    } else
    {
        $slist_page = '<code>'.$_page['prefix'].'</code>';
    }
    $list_page[] = $slist_page;
}

if($raw_push['push']['plugin'] != 'none')
{

    switch($raw_push['push']['plugin'])
    {
        case 'onesignal-cordova-plugin':
            $push_content .= '<blockquote class="blockquote blockquote-danger"><h4>'.__('The rules that apply are:').'</h4><ul>';
            $push_content .= '<li>'.__('You need install <code>onesignal-cordova-plugin</code> or follow IMA BuildeRz Guides (in Dashboard -> How to build?):').'</p>';
            $push_content .= '<pre class="shell">cordova plugin add onesignal-cordova-plugin --save</pre></li>';
            $push_content .= '<li>'.__('You can use <strong>Additional Data</strong> with variable: <code>page</code> for open specific pages, value available:').' '.implode(', ',$list_page).'</li>';
            $push_content .= '<li>'.__('Official docs oneSignal:').' <a target="_blank" href="https://documentation.onesignal.com/docs/ionic-sdk-setup">https://documentation.onesignal.com/docs/ionic-sdk-setup</a></li>';
            $push_content .= '</ul></blockquote>';
            break;
        case 'cordova-plugin-fcm':

            break;
        case 'phonegap-plugin-push':
            break;
    }

}

$button[] = array(
    'name' => 'push-save',
    'label' => __('Save Setting'),
    'tag' => 'submit',
    'color' => 'primary');


$push_content .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,$button));


$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-server fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Push Notifications</h4>';
$content .= '
<blockquote class="blockquote blockquote-danger">
    <h4>'.__('The rules that apply are:').'</h4>
    
    <ul>
        <li>'.__('Push Notifications only work in real device, it\'s will <ins>not be displayed on the (IMAB) Emulator</ins>.').'</li>
        <li>'.__('Use <strong>two or more phones</strong> for push notification testing and wait a few hours if it still does not work.').'</li>
 
    </ul>
</blockquote>
';

$content .= '<div class="row">';
$content .= '<div class="col-md-8">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h4 class="panel-title">'.__('General').'</h4></div>';
$content .= '<div class="panel-body">';
$content .= notice();
$content .= $bs->Forms('app-setup','','post','default',$push_content);
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

$content .= '<div class="col-md-4">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h4 class="panel-title">'.__('Information').'</h4></div>';
$content .= '<div class="panel-body">';
$content .= '<dl>';
$content .= '<dt>'.__('Project/App Name').'</dt><dd>'.$_SESSION['PROJECT']['app']['name'].'</dd>';
$content .= '<dt>'.__('Package Name').'</dt><dd><code>'.JSM_PACKAGE_NAME.'.'.str_replace('_','',str2var($_SESSION["PROJECT"]["app"]["company"])).'.'.str_replace('_','',$_SESSION["PROJECT"]["app"]["prefix"]).'</code></dd>';
$content .= '</dl>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
if($raw_push['push']['plugin'] == 'onesignal-cordova-plugin')
{
    $content .= '<div class="col-md-4">';
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading"><h4 class="panel-title">'.__('OneSignal Tester').'</h4></div>';
    $content .= '<div class="panel-body">';

    if(isset($_POST['test-onesignal-push']))
    {
        $_content = array("en" => date('H:i:s').' : '.$_POST["test_push"]['text']);
        $fields = array(
            "app_id" => $raw_push['push']['app_id'],
            "included_segments" => array("All"),
            "data" => array("page" => $_POST["test_push"]['page']),
            "contents" => $_content);
        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,"https://onesignal.com/api/v1/notifications");
        curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json; charset=utf-8","Authorization: Basic ".$raw_push['push']['app_key']));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER,false);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        $response = json_decode(curl_exec($ch),true);
        curl_close($ch);
        if(isset($response["errors"][0]))
        {
            $content .= "<div class=\"alert alert-dismissible alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>".$response["errors"][0]."</div>";
        } else
        {
            $content .= "<div class=\"alert alert-dismissible alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>ID #".$response["id"]." with ".$response["recipients"]." recipients</div>";
        }
    }
    $test_content = null;
    $test_content .= $bs->FormGroup('test_push[text]','default','textarea',__('Your Message'),'',__('Write your message here'));
    $test_content .= $bs->FormGroup('test_push[page]','default','text',__('Page'),'about_us',__('your page prefix'));

    $test_button[] = array(
        'name' => 'test-onesignal-push',
        'label' => __('Test OneSignal Push'),
        'tag' => 'submit',
        'color' => 'success');
    $test_content .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,$test_button));


    $content .= $bs->Forms('test-push-onesignal','','post','default',$test_content);
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';

}
$content .= '</div>';


$template->demo_url = $out_path.'/www/#/';
$template->title = $template->base_title.' | '.'Extra Menus -&raquo; Push Notifications';
$template->base_desc = '';
$template->content = $content;
$template->emulator = false;

?>