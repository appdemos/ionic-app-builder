<?php

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
$admob_path = 'projects/'.$file_name.'/mod.admob-free.json';
if(isset($_GET['delete']))
{
    @unlink($admob_path);
    buildIonic($file_name);
    header('Location: ./?page=x-cordova-plugin-admob-free&err=null&notice=delete');
    die();
}
$out_path = 'output/'.$file_name;
$content = null;
if(isset($_POST['admob-free']))
{
    @unlink('projects/'.$file_name.'/mod.admob.json');

    $new_admob['mod']['admob-free']['name'] = 'cordova-plugin-admob-free';
    $new_admob['mod']['admob-free']['engines'] = 'cordova';
    $new_admob['mod']['admob-free']['info'] = 'required by Admob Free Menu';
    $new_admob['mod']['admob-free']['var'] = 'ADMOB_APP_ID="'. htmlentities($_POST['admob-free']['app_id']).'"';

    if(isset($_POST['admob-free']['banner']['on-ready']))
    {
        $_POST['admob-free']['banner']['on-ready'] = true;
    } else
    {
        $_POST['admob-free']['banner']['on-ready'] = false;
    }

    if(isset($_POST['admob-free']['interstitial']['on-ready']))
    {
        $_POST['admob-free']['interstitial']['on-ready'] = true;
    } else
    {
        $_POST['admob-free']['interstitial']['on-ready'] = false;
    }

    $new_admob['mod']['admob-free']['data'] = $_POST['admob-free'];
    if(!is_dir('projects/'.$file_name))
    {
        mkdir('projects/'.$file_name,0777,true);
    }

    file_put_contents($admob_path,json_encode($new_admob));

    $new_mod = null;
    $new_mod['mod']['promise-polyfill']['name'] = 'cordova-promise-polyfill';
    $new_mod['mod']['promise-polyfill']['engines'] = 'cordova';
    $new_mod['mod']['promise-polyfill']['info'] = 'required by Admob Free Menu';
    file_put_contents('projects/'.$file_name.'/mod.promise-polyfill.json',json_encode($new_mod));

    $new_mod = null;
    $new_mod['mod']['admob-sdk']['name'] = 'cordova-admob-sdk';
    $new_mod['mod']['admob-sdk']['engines'] = 'cordova';
    $new_mod['mod']['admob-sdk']['info'] = 'required by Admob Free Menu';
    file_put_contents('projects/'.$file_name.'/mod.admob-sdk.json',json_encode($new_mod));


    buildIonic($file_name);
    header('Location: ./?page=x-cordova-plugin-admob-free&err=null&notice=save');
    die();

}

if(file_exists($admob_path))
{
    $raw_admob_free = json_decode(file_get_contents($admob_path),true);
}


$barner_onready = null;
if(!isset($raw_admob_free['mod']['admob-free']['data']['banner']['on-ready']))
{
    $raw_admob_free['mod']['admob-free']['data']['banner']['on-ready'] = false;
}
if(!isset($raw_admob_free['mod']['admob-free']['data']['interstitial']['on-ready']))
{
    $raw_admob_free['mod']['admob-free']['data']['interstitial']['on-ready'] = false;
}


if($raw_admob_free['mod']['admob-free']['data']['banner']['on-ready'] == true)
{
    $barner_onready = 'checked="checked"';
}

if($raw_admob_free['mod']['admob-free']['data']['interstitial']['on-ready'] == true)
{
    $barner_onready = 'checked="checked"';
}


if(!isset($raw_admob_free['mod']['admob-free']['data']['banner']['code']))
{
    $raw_admob_free['mod']['admob-free']['data']['banner']['code'] = '';
}

if(!isset($raw_admob_free['mod']['admob-free']['data']['interstitial']['code']))
{
    $raw_admob_free['mod']['admob-free']['data']['interstitial']['code'] = '';
}

if(!isset($raw_admob_free['mod']['admob-free']['data']['rewardvideo']['code']))
{
    $raw_admob_free['mod']['admob-free']['data']['rewardvideo']['code'] = '';
}
if(!isset($raw_admob_free['mod']['admob-free']['data']['app_id']))
{
    $raw_admob_free['mod']['admob-free']['data']['app_id'] = '';
}

$form_input .= $bs->FormGroup('admob-free[app_id]','default','text',__('Admob App ID'),'ca-app-pub-4855740622510094~1743954969',null,null,'8',$raw_admob_free['mod']['admob-free']['data']['app_id']);
$form_input .= '<br/>';
$form_input .= '<br/>';
$form_input .= $bs->FormGroup('admob-free[banner][code]','default','text',__('Ad unit ID for Banner'),'ca-app-pub-8094096715994524/6097141095',null,null,'8',$raw_admob_free['mod']['admob-free']['data']['banner']['code']);
$form_input .= $bs->FormGroup('admob-free[banner][on-ready]','default','checkbox','',__('auto show'),'',$barner_onready,'8');

$form_input .= $bs->FormGroup('admob-free[interstitial][code]','default','text',__('Ad unit ID for Interstitial'),'ca-app-pub-8094096715994524/6097141095',null,null,'8',$raw_admob_free['mod']['admob-free']['data']['interstitial']['code']);
$form_input .= $bs->FormGroup('admob-free[interstitial][on-ready]','default','checkbox','',__('auto show'),'',$barner_onready,'8');

//$form_input .= $bs->FormGroup('admob-free[rewardvideo][code]', 'default', 'text', __('Ad unit ID for Video Reward'), 'ca-app-pub-8094096715994524/6097141095', null, null, '8', $raw_admob_free['mod']['admob-free']['data']['rewardvideo']['code']);
//$form_input .= $bs->FormGroup('admob-free[rewardvideo][on-ready]', 'default', 'checkbox', '', __('auto show'), '', $barner_onready, '8');


$form_input .= '<hr/>';

$button[] = array(
    'name' => 'admob-save',
    'label' => __('Save Plugin').' &raquo;',
    'tag' => 'submit',
    'color' => 'primary');
$button[] = array(
    'label' => __('Reset'),
    'tag' => 'reset',
    'color' => 'warning');
if(file_exists($admob_path))
{
    $button[] = array(
        'label' => __('Delete'),
        'icon' => 'glyphicon glyphicon glyphicon-trash',
        'tag' => 'anchor',
        'color' => 'danger',
        'link' => "./?page=x-cordova-plugin-admob-free&delete=true");
}
$form_input .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,$button));

$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-database fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Cordova Plugin - Admob Free</h4>';
$code_js = null;


if($raw_admob_free['mod']['admob-free']['data']['banner']['code'] != '')
{
    $code_js .= '// code banner'."\r\n";
    $code_js .= 'if (typeof admob !== "undefined"){'."\r\n";
    //$code_js .= "\t"."\t".'admob.banner.config({'."\r\n";
    //$code_js .= "\t"."\t"."\t".'id: "'.$raw_admob_free['mod']['admob-free']['data']['banner']['code'].'"'."\r\n";
    //$code_js .= "\t"."\t".'});'."\r\n";
    //$code_js .= "\t"."\t".'admob.banner.prepare();'."\r\n";
    $code_js .= "\t"."\t".'$timeout(function(){'."\r\n";
    $code_js .= "\t"."\t"."\t".'admob.banner.show();'."\r\n";
    $code_js .= "\t"."\t".'},500);'."\r\n";
    $code_js .= '}'."\r\n";
    $code_js .= ''."\r\n";

}
if($raw_admob_free['mod']['admob-free']['data']['interstitial']['code'] != '')
{
    $code_js .= '// code interstitial'."\r\n";
    $code_js .= 'if (typeof admob !== "undefined"){'."\r\n";
    //$code_js .= "\t"."\t".'admob.interstitial.config({'."\r\n";
    //$code_js .= "\t"."\t"."\t".'id: "'.$raw_admob_free['mod']['admob-free']['data']['interstitial']['code'].'"'."\r\n";
    // $code_js .= "\t"."\t".'});'."\r\n";
    //$code_js .= "\t"."\t".'admob.interstitial.prepare();'."\r\n";
    $code_js .= "\t"."\t".'$timeout(function(){'."\r\n";
    $code_js .= "\t"."\t"."\t".'admob.interstitial.show();'."\r\n";
    $code_js .= "\t"."\t".'},1000);'."\r\n";
    $code_js .= '}'."\r\n";
    $code_js .= ''."\r\n";
}
$content .= notice();
$content .= '
<blockquote class="blockquote blockquote-danger">
<h4>'.__('The rules that apply are:').'</h4>
<ol>
<li>'.__('Admob Free only work in real device, it\'s will <ins>not be displayed on the (IMAB) Emulator</ins>.').'</li>
<li>'.__('You must install <code>cordova-plugin-admob-free</code> or have run the command on your cordova <pre class="shell">cordova plugin add cordova-plugin-admob-free --save</pre> Or following instructions <code>How to Build</code> in <a target="_blank" href="./?page=dashboard">(IMAB) Dashboard</a>.').'</li>
<li>'.__('For delete this plugin in cordova project, typing this command: <pre class="shell">cordova plugin rm cordova-plugin-admob-free --save</pre>').'</li>
<li>'.__('For new <strong>account</strong> or <strong>Ad Unit ID</strong> may not directly active, sometimes take 1 or 2 days for ads to run on your app.').'</li>
<li>'.__('Your reference here : <a target="_blank" href="https://github.com/ratson/cordova-plugin-admob-free">cordova-plugin-admob-free</a>').'</li>
<li>'.__('App using Webview, AppBrowser or iframe not suitable for Admob Free').'</li>
</ol>
<h5>AdUnit ID for Testing Mode</h5>
<table class="table table-stipped">
<tr>
	<td>Banner</td>
	<td><code>ca-app-pub-3940256099942544/6300978111</code></td>
</tr>
<tr>
	<td>Interstitial</td>
	<td><code>ca-app-pub-3940256099942544/1033173712</code></td>
</tr>
<tr>
	<td>RewardVideo</td>
	<td><code>ca-app-pub-3940256099942544/5224354917</code></td>
</tr>
</table>
</blockquote>';
$content .= '<div class="row">';

$content .= '<div class="col-md-6">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h4 class="panel-title">'.__('General').'</h4></div>';
$content .= '<div class="panel-body">';
$content .= $bs->Forms('app-setup','','post','default',$form_input);
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

$content .= '<div class="col-md-6">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h4 class="panel-title">'.__('Example Custom Code').'</h4></div>';
$content .= '<div class="panel-body">';

$content .= '<blockquote class="blockquote blockquote-danger">'.__('We are not responsible for your AdSense, so You should follow <a target="_blank" href="https://support.google.com/admob/answer/6066980?hl=en">best practices from google</a> the Do and Don\'ts for implementing banner and interstitial ads, maybe you need a custom code:').'</blockquote>';
if(strlen($code_js) >= 10)
{
    $content .= '<pre>';
    $content .= $code_js;
    $content .= '</pre>';
}
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';


$template->demo_url = $out_path.'/www/#/';
$template->title = $template->base_title.' | '.'Extra Menus -&raquo; Cordova Plugin - Admob Free';
$template->base_desc = 'tools';
$template->content = $content;
$template->emulator = false;

?>