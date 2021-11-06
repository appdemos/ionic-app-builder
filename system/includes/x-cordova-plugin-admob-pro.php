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
$admob_path = 'projects/'.$file_name.'/mod.admob.json';
if(isset($_GET['delete']))
{
    @unlink($admob_path);
    buildIonic($file_name);
    header('Location: ./?page=x-cordova-plugin-admob-pro&err=null&notice=delete');
    die();
}
if(isset($_POST['admob-save']))
{
    @unlink('projects/'.$file_name.'/mod.admob-free.json');
    @unlink('projects/'.$file_name.'/mod.promise-polyfill.json');
    @unlink('projects/'.$file_name.'/mod.admob-sdk.json');

    $new_admob['mod']['admob']['name'] = 'cordova-plugin-admobpro';
    $new_admob['mod']['admob']['engines'] = 'cordova';
    $new_admob['mod']['admob']['info'] = 'required by Admob Pro Menu';
    $new_admob['mod']['admob']['test'] = $_POST['test'];
    $new_admob['mod']['admob']['position'] = $_POST['position'];
    if(isset($_POST['admob']['banner']['on-ready']))
    {
        $_POST['admob']['banner']['on-ready'] = true;
    } else
    {
        $_POST['admob']['banner']['on-ready'] = false;
    }
    if(isset($_POST['admob']['interstitial']['on-ready']))
    {
        $_POST['admob']['interstitial']['on-ready'] = true;
    } else
    {
        $_POST['admob']['interstitial']['on-ready'] = false;
    }
    if(isset($_POST['admob']['rewardvideo']['on-ready']))
    {
        $_POST['admob']['rewardvideo']['on-ready'] = true;
    } else
    {
        $_POST['admob']['rewardvideo']['on-ready'] = false;
    }
    $new_admob['mod']['admob']['data'] = $_POST['admob'];
    if(!is_dir('projects/'.$file_name))
    {
        mkdir('projects/'.$file_name,0777,true);
    }
    file_put_contents($admob_path,json_encode($new_admob));
    buildIonic($file_name);
    header('Location: ./?page=x-cordova-plugin-admob-pro&err=null&notice=save');
    die();
}
$out_path = 'output/'.$file_name;
$content = null;
$raw_admob['mod']['admob']['test'] = 'true';
$raw_admob['mod']['admob']['position'] = 'TOP_CENTER';
$raw_admob['mod']['admob']['data']['banner']['code'] = '';
$raw_admob['mod']['admob']['data']['banner']['on-ready'] = true;
$raw_admob['mod']['admob']['data']['interstitial']['code'] = '';
$raw_admob['mod']['admob']['data']['interstitial']['on-ready'] = false;
$raw_admob['mod']['admob']['data']['rewardvideo']['code'] = '';
$raw_admob['mod']['admob']['data']['rewardvideo']['on-ready'] = false;
if(file_exists($admob_path))
{
    $raw_admob = json_decode(file_get_contents($admob_path),true);
}
if(!isset($raw_admob['mod']['admob']['data']['banner']['code']))
{
    $raw_admob['mod']['admob']['data']['banner']['code'] = '';
}
if(!isset($raw_admob['mod']['admob']['data']['interstitial']['code']))
{
    $raw_admob['mod']['admob']['data']['interstitial']['code'] = '';
}
if(!isset($raw_admob['mod']['admob']['data']['rewardvideo']['code']))
{
    $raw_admob['mod']['admob']['data']['rewardvideo']['code'] = '';
}
$barner_position[] = array('label' => 'Bottom Center','value' => 'BOTTOM_CENTER');
$barner_position[] = array('label' => 'Top Center','value' => 'TOP_CENTER');
$checked = '';
if($raw_admob['mod']['admob']['test'] == 'true')
{
    $checked = 'checked';
}
$z = 0;
foreach($barner_position as $position)
{
    $barner_position[$z] = $position;
    if($position['value'] == $raw_admob['mod']['admob']['position'])
    {
        $barner_position[$z]['active'] = true;
    }
    $z++;
}


$code_js = __('Complete the form on the left side first');
if($raw_admob['mod']['admob']['data']['banner']['code'] != '')
{

    $banner = $raw_admob['mod']['admob']['data'];

    if(isset($raw_admob['mod']['admob']['position']))
    {
        $position = $raw_admob['mod']['admob']['position'];
    } else
    {
        $position = 'BOTTOM_CENTER';
    }


    //$code_js .= '// code banner' . "\r\n";
    //$code_js .= 'if (typeof AdMob !== "undefined"){' . "\r\n";
    //$code_js .= "\t" . "\t" . 'AdMob.createBanner({' . "\r\n";
    //$code_js .= "\t" . "\t" . "\t" . 'adId: "' . $banner['banner']['code'] . '",' . "\r\n";
    //if ($raw_admob['mod']['admob']['test'] == 'true')
    //{
    //$code_js .= "\t" . "\t" . "\t" . 'isTesting: true,// TO' . 'DO: remove this line when release' . "\r\n";
    //}
    //$code_js .= "\t" . "\t" . "\t" . 'overlap: false,' . "\r\n";
    //$code_js .= "\t" . "\t" . "\t" . 'offsetTopBar: false,' . "\r\n";
    //$code_js .= "\t" . "\t" . "\t" . 'position: AdMob.AD_POSITION.' . $position . ',' . "\r\n";
    //$code_js .= "\t" . "\t" . "\t" . 'bgColor: "black"' . "\r\n";
    //$code_js .= "\t" . "\t" . '});' . "\r\n";
    //$code_js .= '}' . "\r\n";
    $code_js .= ''."\r\n";
       $code_js .= ''."\r\n";
    $code_js .= '// code for show banner'."\r\n";
    $code_js .= 'if (typeof AdMob !== "undefined"){'."\r\n";
    $code_js .= "\t".'AdMob.showBanner(8);'."\r\n";
    $code_js .= '}'."\r\n";
    $code_js .= ''."\r\n";
    $code_js .= '// code for hide banner'."\r\n";
    $code_js .= 'if (typeof AdMob !== "undefined"){'."\r\n";
    $code_js .= "\t".'AdMob.hideBanner();'."\r\n";
    $code_js .= '}'."\r\n";
    $code_js .= ''."\r\n";
    //$code_js .= '// code interstitial'."\r\n";

    //$code_js .= '$timeout(function(){'."\r\n";
    //$code_js .= "\t".'if (typeof AdMob !== "undefined"){'."\r\n";
    //$code_js .= "\t"."\t"."\t".'AdMob.prepareInterstitial({'."\r\n";
    //$code_js .= "\t"."\t"."\t"."\t".'adId: "'.$banner['interstitial']['code'].'",'."\r\n";
    //$code_js .= "\t"."\t"."\t"."\t".'autoShow: true,'."\r\n";
    //if($raw_admob['mod']['admob']['test'] == 'true')
    //{
    //    $code_js .= "\t"."\t"."\t"."\t".'isTesting: true,// TO'.'DO: remove this line when release'."\r\n";
    //}
    //$code_js .= "\t"."\t"."\t".'});'."\r\n";
    //$code_js .= "\t".'}'."\r\n";
    //$code_js .= '},1000); // delay 1000ms'."\r\n";

    $code_js .= '// code for show interstitial'."\r\n";
    $code_js .= 'if (typeof AdMob !== "undefined"){'."\r\n";
    $code_js .= "\t".'AdMob.showInterstitial();'."\r\n";
    $code_js .= '}'."\r\n";
    $code_js .= ''."\r\n";

    //$code_js .= '// code rewardvideo'."\r\n";
    //$code_js .= '$timeout(function(){'."\r\n";
    //$code_js .= "\t".'if (typeof AdMob !== "undefined"){'."\r\n";
    // $code_js .= "\t"."\t"."\t".'AdMob.prepareRewardVideoAd({'."\r\n";
    //$code_js .= "\t"."\t"."\t"."\t".'adId:"'.$banner['rewardvideo']['code'].'",'."\r\n";
    //$code_js .= "\t"."\t"."\t"."\t".'autoShow: true,'."\r\n";
    //if($raw_admob['mod']['admob']['test'] == 'true')
    //{
    //$code_js .= "\t"."\t"."\t"."\t".'isTesting: true,// TO'.'DO: remove this line when release'."\r\n";
    //}
    //$code_js .= "\t"."\t"."\t".'});'."\r\n";
    //$code_js .= "\t".'}'."\r\n";
    //$code_js .= '},60000); // delay 60000ms'."\r\n";
    $code_js .= '// code for show rewardvideo'."\r\n";
    $code_js .= 'if (typeof AdMob !== "undefined"){'."\r\n";
    $code_js .= "\t".'AdMob.showRewardVideoAd();'."\r\n";
    $code_js .= '}'."\r\n";
}

$form_input .= $bs->FormGroup('test','default','checkbox','Mode',__('Testing Mode (To test your cordova)'),null,$checked,'8','true');

$barner_onready = null;
if($raw_admob['mod']['admob']['data']['banner']['on-ready'] == true)
{
    $barner_onready = 'checked="checked"';
}
$barner_onready = null;
if($raw_admob['mod']['admob']['data']['banner']['on-ready'] == true)
{
    $barner_onready = 'checked="checked"';
}
$interstitial_onready = null;
if($raw_admob['mod']['admob']['data']['interstitial']['on-ready'] == true)
{
    $interstitial_onready = 'checked="checked"';
}
$rewardvideo_onready = null;
if($raw_admob['mod']['admob']['data']['rewardvideo']['on-ready'] == true)
{
    $rewardvideo_onready = 'checked="checked"';
}
$form_input .= '<hr/>';
$form_input .= $bs->FormGroup('position','default','select',__('Banner Position'),$barner_position,null,null,'8');
$form_input .= $bs->FormGroup('admob[banner][code]','default','text',__('Ad unit ID for Banner'),'ca-app-pub-8094096715994524/6097141095',null,null,'8',$raw_admob['mod']['admob']['data']['banner']['code']);
$form_input .= $bs->FormGroup('admob[banner][on-ready]','default','checkbox','',__('auto show'),'',$barner_onready,'8');
$form_input .= '<hr/>';
$form_input .= $bs->FormGroup('admob[interstitial][code]','default','text',__('Ad unit ID for Interstitial'),'ca-app-pub-8094096715994524/4760008695','',null,'8',$raw_admob['mod']['admob']['data']['interstitial']['code']);
$form_input .= $bs->FormGroup('admob[interstitial][on-ready]','default','checkbox','',__('auto show'),null,$interstitial_onready,'8');
$form_input .= '<hr/>';
$form_input .= $bs->FormGroup('admob[rewardvideo][code]','default','text',__('Ad unit ID for Reward Video'),'ca-app-pub-8094096715994524/1042454297','',null,'8',$raw_admob['mod']['admob']['data']['rewardvideo']['code']);
$form_input .= $bs->FormGroup('admob[rewardvideo][on-ready]','default','checkbox','',__('auto show (delay: 30s)'),null,$rewardvideo_onready,'8');


$button[] = array(
    'name' => 'admob-save',
    'label' => __('Save Admob').' &raquo;',
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
        'link' => "./?page=x-cordova-plugin-admob-pro&delete=true");
}
$form_input .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,$button));


$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-database fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Cordova Plugin - Admob Pro</h4>';

$content .= notice();
$content .= '
<blockquote class="blockquote blockquote-danger">
<h4>'.__('The rules that apply are:').'</h4>
<ol>
<li>'.__('Admob Pro only work in real device, it\'s will <ins>not be displayed on the (IMAB) Emulator</ins>.').'</li>
<li>'.__('You must install <code>cordova-plugin-admobpro</code> or have run the command on your cordova <pre class="shell">cordova plugin add cordova-plugin-admobpro --save</pre> Or following instructions <code>How to Build</code> in <a target="_blank" href="./?page=dashboard">(IMAB) Dashboard</a>.').'</li>
<li>'.__('For delete this plugin in cordova project, typing this command: <pre class="shell">cordova plugin rm cordova-plugin-admobpro --save</pre>').'</li>
<li>'.__('For new <strong>account</strong> or <strong>Ad Unit ID</strong> may not directly active, sometimes take 1 or 2 days for ads to run on your app.').'</li>
<li>'.__('Your reference here : <a target="_blank" href="https://github.com/floatinghotpot/cordova-admob-pro">cordova-admob-pro</a>').'</li>
<li>'.__('App using Webview, AppBrowser or iframe not suitable for Admob Pro').'</li>
<li>'.__('AdmobPro is not completely <a target="_blank" href="https://github.com/floatinghotpot/cordova-admob-pro#license">free plugin</a>, you pay it with your own traffic.').'</li>
</ol>
<table class="table">
<thead>
<tr>
	<th>Features</th>
	<th>Ads Type</th>
	<th>Support</th>
</tr>
</thead>
<tr>
	<td>RESTAPI/JSON</td>
	<td>Banner</td>
	<td>Yes</td>
</tr>
<tr>
	<td>RESTAPI/JSON</td>
	<td>Interstitial</td>
	<td>Yes</td>
</tr>
<tr>
	<td>RESTAPI/JSON</td>
	<td>Reward Video</td>
	<td>Yes</td>
</tr>

<tr>
	<td>Webview/AppBrowser</td>
	<td>Banner</td>
	<td>No</td>
</tr>
<tr>
	<td>Webview/AppBrowser</td>
	<td>Interstitial</td>
	<td>Yes</td>
</tr>
<tr>
	<td>Webview/AppBrowser</td>
	<td>Reward Video</td>
	<td>Yes</td>
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
$template->title = $template->base_title.' | '.'Extra Menus -&raquo; Cordova Plugin - Admob Pro';
$template->base_desc = 'tools';
$template->content = $content;
$template->emulator = false;

?>