<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

if(isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}

$require_target_page = false;

$file_table_admob = JSM_PATH.'/system/includes/page-builder/eazy_setup_backend_for_admob/json/tables.admob.json';
$file_data_admob = JSM_PATH.'/system/includes/page-builder/eazy_setup_backend_for_admob/json/admob.json';
$file_page_admob = JSM_PATH.'/system/includes/page-builder/eazy_setup_backend_for_admob/json/page.admob.json';

$table_admob = json_decode(file_get_contents($file_table_admob),true);
$data_admob = json_decode(file_get_contents($file_data_admob),true);
$page_admob = json_decode(file_get_contents($file_page_admob),true);

$pagebuilder_file = 'projects/'.$_SESSION['FILE_NAME'].'/page_builder.backend_for_admob.json';
if(isset($_POST['page-builder']))
{

    foreach(glob("projects/".$file_name."/page.*.json") as $raw_json)
    {
        $data_json = file_get_contents($raw_json);
        $data_json = str_replace('<div ng-controller=\"admobCtrl\"><\/div>','',$data_json);
        file_put_contents($raw_json,$data_json);
    }
    // TODO: SETTINGS
    //$json_save['page_builder']['backend_for_admob']['backend'] = htmlentities($_POST['backend_for_admob']['backend']);
    $json_save['page_builder']['backend_for_admob']['plugin'] = $admob_plugin = htmlentities($_POST['backend_for_admob']['plugin']);
    $json_save['page_builder']['backend_for_admob']['json_url'] = htmlentities($_POST['backend_for_admob']['json_url']);
    file_put_contents($pagebuilder_file,json_encode($json_save));

    // TODO: TABLE ADMOB
    $file_table_target = 'projects/'.$_SESSION['FILE_NAME'].'/tables.admob.json';
    $table_admob['tables']['admob']['db_url'] = $json_save['page_builder']['backend_for_admob']['json_url'];
    $table_admob['tables']['admob']['builder_link'] = @$_SERVER["HTTP_REFERER"];
    file_put_contents($file_table_target,json_encode($table_admob));

    // TODO: PAGE ADMOB
    $file_page_target = 'projects/'.$_SESSION['FILE_NAME'].'/page.admob.json';


    switch($admob_plugin)
    {

            // TODO: ---- NONE
        case 'none':
            // TODO: ----|---- REMOVE PLUGIN
            if(file_exists('projects/'.$file_name.'/mod.admob-free.json'))
            {
                @unlink('projects/'.$file_name.'/mod.admob-free.json');
            }
            if(file_exists('projects/'.$file_name.'/mod.promise-polyfill.json'))
            {
                @unlink('projects/'.$file_name.'/mod.promise-polyfill.json');
            }
            if(file_exists('projects/'.$file_name.'/mod.admob-sdk.json'))
            {
                @unlink('projects/'.$file_name.'/mod.admob-sdk.json');
            }
            if(file_exists('projects/'.$file_name.'/mod.admob.json'))
            {
                @unlink('projects/'.$file_name.'/mod.admob.json');
            }
            break;
            // TODO: ---- ADMOB FREE
        case 'admobfree':
            // TODO: ----|---- INSERT CONTROLLER
            $path_file_index = 'projects/'.$file_name.'/page.'.$_SESSION['PROJECT']['app']['index'].'.json';
            if(file_exists($path_file_index))
            {
                $data_json = json_decode(file_get_contents($path_file_index),true);
                $data_json['page'][0]['content'] = $data_json['page'][0]['content']."\r\n".'<div ng-controller="admobCtrl"></div>';
                file_put_contents($path_file_index,json_encode($data_json));
            }
            // TODO: ----|---- INSERT PLUGIN
            if(file_exists('projects/'.$file_name.'/mod.admob.json'))
            {
                @unlink('projects/'.$file_name.'/mod.admob.json');
            }

            $new_admob['mod']['admob-free']['name'] = 'cordova-plugin-admob-free';
            $new_admob['mod']['admob-free']['engines'] = 'cordova';
            $new_admob['mod']['admob-free']['info'] = 'required by Admob Free Menu';
            file_put_contents('projects/'.$file_name.'/mod.admob-free.json',json_encode($new_admob));

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

            // TODO: ----|---- APPEND CUSTOM CODE
            $page_admob['page'][0]['js'] = '
angular.forEach(data_admobs, function(ads, key) {
	switch (ads.ad_format) {
	case "banner":
		if (typeof admob !== "undefined") {
			admob.banner.config({id:ads.ad_unit_id});
			admob.banner.prepare();
			admob.banner.show();
		}
		break;
	case "interstitial":
		if (typeof admob !== "undefined") {
			admob.interstitial.config({id:ads.ad_unit_id});
			admob.interstitial.prepare();
			admob.interstitial.show();
		}
		break;
	case "rewardvideo":
        // not support reward video
		break;
	default:
	}
});    
    ';
            break;

            // TODO: ---- ADMOB PRO
        case 'admobpro':
            // TODO: ----|---- INSERT CONTROLLER
            $path_file_index = 'projects/'.$file_name.'/page.'.$_SESSION['PROJECT']['app']['index'].'.json';
            if(file_exists($path_file_index))
            {
                $data_json = json_decode(file_get_contents($path_file_index),true);
                $data_json['page'][0]['content'] = $data_json['page'][0]['content']."\r\n".'<div ng-controller="admobCtrl"></div>';
                file_put_contents($path_file_index,json_encode($data_json));
            }

            // TODO: ----|---- INSERT PLUGIN
            if(file_exists('projects/'.$file_name.'/mod.admob-free.json'))
            {
                @unlink('projects/'.$file_name.'/mod.admob-free.json');
            }
            if(file_exists('projects/'.$file_name.'/mod.promise-polyfill.json'))
            {
                @unlink('projects/'.$file_name.'/mod.promise-polyfill.json');
            }
            if(file_exists('projects/'.$file_name.'/mod.admob-sdk.json'))
            {
                @unlink('projects/'.$file_name.'/mod.admob-sdk.json');
            }
            $new_admob = null;
            $new_admob['mod']['admob']['name'] = 'cordova-plugin-admobpro';
            $new_admob['mod']['admob']['engines'] = 'cordova';
            $new_admob['mod']['admob']['info'] = '';
            file_put_contents('projects/'.$file_name.'/mod.admob.json',json_encode($new_admob));

            // TODO: ----|---- APPEND CUSTOM CODE
            $page_admob['page'][0]['js'] = '
angular.forEach(data_admobs, function(ads, key) {
	switch (ads.ad_format) {
	case "banner":
		if (typeof AdMob !== "undefined") {
			$timeout(function(){
				AdMob.createBanner({
				    adId: ads.ad_unit_id,
                    overlap: false,
                    autoShow: true,
                    offsetTopBar: false,
                    position: AdMob.AD_POSITION.TOP_CENTER,
                    bgColor: "black"
                });				
			}, 1000);
		}
		break;
	case "interstitial":
		if (typeof AdMob !== "undefined") {
				AdMob.prepareInterstitial({
					adId: ads.ad_unit_id,
					autoShow: true,
				});
		}
		break;
	case "rewardvideo":
		if (typeof AdMob !== "undefined") {
			$timeout(function(){
				AdMob.prepareRewardVideoAd({
					adId: ads.ad_unit_id,
					autoShow: true,
				});
			}, 30000);
		}         
		break;
	default:
	}
});    
    ';
            break;
    }


    $page_admob['page'][0]['content'] = '
    <div class="padding">
        <div ng-repeat="item in data_admobs">
            <div ng-if="item.ad_format==\'banner\'">
                    <span>Banner</span><br/>
                    <strong>(#ID{{ item.ad_id }}) {{ item.ad_unit_name }}</strong><br/>
                    <span>{{ item.ad_unit_id }}</span><br/><br/>
            </div>
           
            <div ng-if="item.ad_format==\'interstitial\'">
                    <span>Interstitial</span><br/>
                    <strong>(#ID{{ item.ad_id }}) {{ item.ad_unit_name }}</strong><br/>
                    <span>{{ item.ad_unit_id }}</span><br/><br/>
            </div>
           
            <div ng-if="item.ad_format==\'rewardvideo\'">
                    <span>Reward Video</span><br/>
                    <strong>(#ID{{ item.ad_id }}) {{ item.ad_unit_name }}</strong><br/>
                    <span>{{ item.ad_unit_id }}</span><br/><br/>
            </div>
                        
        </div>
    </div>
 ';

    $page_admob['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'].'-custom';
    $page_admob['page'][0]['title'] = 'Admob Code';
    $page_admob['page'][0]['lock'] = 'true';

    file_put_contents($file_page_target,json_encode($page_admob));

    // TODO: EXAMPLE DATA JSON
    $file_data_target = 'projects/'.$_SESSION['FILE_NAME'].'/tables/admob.json';
    if(!file_exists('projects/'.$_SESSION['FILE_NAME'].'/tables/'))
    {
        mkdir('projects/'.$_SESSION['FILE_NAME'].'/tables/',0777,true);
    }
    file_put_contents($file_data_target,json_encode($data_admob));

    $file_data_target = 'output/'.$_SESSION['FILE_NAME'].'/www/data/tables/admob.json';
    if(!file_exists('output/'.$_SESSION['FILE_NAME'].'/www/data/tables/'))
    {
        mkdir('output/'.$_SESSION['FILE_NAME'].'/www/data/tables/',0777,true);
    }
    file_put_contents($file_data_target,json_encode($data_admob));

    // TODO: BUILD
    buildIonic($file_name);
    //header('Location: ./?page=x-page-builder&prefix=eazy_setup_backend_for_admob');
    //die();
}
$raw_data = array();
if(file_exists($pagebuilder_file))
{
    $_raw_data = json_decode(file_get_contents($pagebuilder_file),true);
    $raw_data = $_raw_data['page_builder']['backend_for_admob'];
}


$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443)?"https://" : "http://";
$url = explode('?page=x-page-builder',$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$default_url = $url[0].'output/'.$_SESSION['FILE_NAME'].'/www/data/tables/admob.json';
;

if(!isset($raw_data['json_url']))
{
    $raw_data['json_url'] = $default_url;
}
if($raw_data['json_url'] == '')
{
    $raw_data['json_url'] = $default_url;
}

if(!isset($raw_data['backend']))
{
    $raw_data['backend'] = 'json';
}

$select_option_backend = array();
$_option_backend[] = array('label' => 'JSON Files','value' => 'json');
$_option_backend[] = array('label' => 'WordPress Plugin Generator','value' => 'wp-plugin');
$_option_backend[] = array('label' => 'REST API Generator','value' => 'php-restapi');
$x = 0;
foreach($_option_backend as $option_backend)
{
    $select_option_backend[$x] = $option_backend;
    if($option_backend['value'] == $raw_data['backend'])
    {
        $select_option_backend[$x]['active'] = true;
    }
    $x++;
}

$form_input .= '<blockquote class="blockquote blockquote-info">';
$form_input .= 'This is a feature that affects the page builder:
<table class="table table-striped">
<tr>
	<th>Type</th>
	<th>Name/Prefix</th>
</tr>
<tr>
	<td>(IMAB) Tables</td>
	<td>admob</td>
</tr>
<tr>
	<td>(IMAB) Pages</td>
	<td>admob</td>
</tr>
<tr>
	<td>(IMAB) Custom Cordova Plugin</td>
	<td>yes</td>
</tr>
</table>';
$form_input .= '</blockquote>';
$form_input .= '<hr/>';

$form_input .= '<blockquote class="blockquote blockquote-danger">';
$form_input .= '<h4>How to Use it?</h4>';
$form_input .= '<ol>';
$form_input .= '<li>Save this form, then update your backend code</li>';
$form_input .= '<li>You can use the following backend:';
$form_input .= '<ul>';
$form_input .= '<li><a target="_blank" href="./?page=z-php-sql-restapi-generator">Backend Tools -&raquo; (IMAB) REST-API Generator (PHP + SQL)</a></li>';
$form_input .= '<li><a target="_blank" href="./?page=z-wordpress-plugin-generator">Backend Tools -&raquo; (IMAB) WordPress Plugin Generator</a></li>';
$form_input .= '<li>or <a target="_blank" href="./?page=z-json-raw&edit=edit&raw_file=admob">Directly Upload JSON files</a></li>';
$form_input .= '</ul>';
$form_input .= '</li>';

$form_input .= '<li>Table format like this:
<table class="table table-striped">
<tr>
	<th>Name</th>
	<th>Ad Format</th>
	<th>Ad Unit</th>
</tr>
<tr>
	<td>APK Banner</td>
	<td>banner</td>
	<td>ca-app-pub-3940256099942544/6300978111</td>
</tr>
<tr>
	<td>APK Interstitial</td>
	<td>interstitial</td>
	<td>ca-app-pub-3940256099942544/1033173712</td>
</tr>
<tr>
	<td>APK Video Reward</td>
	<td>rewardvideo</td>
	<td>ca-app-pub-3940256099942544/5224354917</td>
</tr>
</table>

</li>';


$form_input .= '</ol>';
$form_input .= '</blockquote>';
$form_input .= '<hr/>';

$form_input .= $default_url;
//http : //ionic.co.id/ima-builder/?page=z-json&act=edit&prefix=admob
//$form_input .= $bs->FormGroup('backend_for_admob[backend]', 'horizontal', 'select', 'Backend?', $select_option_backend, 'please select the backend you want to use', null, '5');

if(!isset($raw_data['plugin']))
{
    $raw_data['plugin'] = 'admobfree';
}
$option_cordova_plugin = array();
$_cordova_plugin[] = array('label' => '< none >','value' => 'none');
$_cordova_plugin[] = array('label' => 'Cordova Plugin AdmobPro','value' => 'admobpro');
$_cordova_plugin[] = array('label' => 'Cordova Plugin Admob Free','value' => 'admobfree');


foreach($_cordova_plugin as $cordova_plugin)
{
    $option_cordova_plugin[$z] = array('label' => $cordova_plugin['label'],'value' => $cordova_plugin['value']);
    if($raw_data['plugin'] == $cordova_plugin['value'])
    {
        $option_cordova_plugin[$z]['active'] = true;
    }
    $z++;
}

$form_input .= $bs->FormGroup('backend_for_admob[plugin]','horizontal','select','Cordova Plugin',$option_cordova_plugin,'select cordova plugin',null,'4');
$form_input .= $bs->FormGroup('backend_for_admob[json_url]','horizontal','text','URL List Item','http://yourdomain.com/wp-json/your_app/v2/app_admob?numberposts=3','Where will the json file be uploaded?',null,'7',$raw_data['json_url']);

$preview_url .= 'admob';

?>