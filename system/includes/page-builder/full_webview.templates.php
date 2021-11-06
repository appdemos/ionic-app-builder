<?php

//$run_emulator = false;


if(isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}

function rrmdir($dir)
{
    if(is_dir($dir))
    {
        $objects = @scandir($dir);
        foreach($objects as $object)
        {
            if($object != "." && $object != "..")
            {
                if(filetype($dir."/".$object) == "dir")
                    rrmdir($dir."/".$object);
                else
                    @unlink($dir."/".$object);
            }
        }
        reset($objects);
        @rmdir($dir);
    }
}


if(isset($_POST['page-builder']))
{
    $app_menus = array();
    $app_menus['menu']['type'] = 'side_menus';
    $app_menus['menu']['menu_style'] = 'none';
    $app_menus['menu']['menu_position'] = 'left';
    $app_menus['menu']['title'] = $_SESSION['PROJECT']['app']['name'];
    $app_menus['menu']['items'] = array();

    $app_menus['menu']['header_background'] = "positive-900";
    $app_menus['menu']['menu_background'] = "stable";
    $app_menus['menu']['menu_color'] = "royal-900";
    $app_menus['menu']['header_image_background'] = "";
    $app_menus['menu']['expanded_header'] = "";
    $app_menus['menu']['logo'] = "data/images/header/logo.png";

    $app_menus['menu']['items'][0]["label"] = "About Us";
    $app_menus['menu']['items'][0]["var"] = "about_us";
    $app_menus['menu']['items'][0]["icon"] = "ion-android-contact";
    $app_menus['menu']['items'][0]["icon-alt"] = "ion-android-contact";
    $app_menus['menu']['items'][0]["type"] = "link";
    $app_menus['menu']['items'][0]["option"] = "";
    $app_menus['menu']['items'][0]["desc"] = "";

    $app_menus['menu']['items'][1]["label"] = "Rate This App";
    $app_menus['menu']['items'][1]["var"] = "rate_this_app";
    $app_menus['menu']['items'][1]["icon"] = "ion-android-playstore";
    $app_menus['menu']['items'][1]["icon-alt"] = "ion-android-playstore";
    $app_menus['menu']['items'][1]["type"] = "ext-playstore";
    $app_menus['menu']['items'][1]["option"] = "";
    $app_menus['menu']['items'][1]["desc"] = "";


    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/menu.json',json_encode($app_menus));

    $json_save['page_builder']['full_webview']['site_url'] = htmlentities($_POST['full_webview']['site_url']);
    $json_save['page_builder']['full_webview']['type'] = htmlentities($_POST['full_webview']['type']);

    $site_url = $json_save['page_builder']['full_webview']['site_url'];
    $type_webview = $json_save['page_builder']['full_webview']['type'];

    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/page_builder.full_webview.json',json_encode($json_save));

    $app_config['app'] = $_SESSION['PROJECT']['app'];
    $app_config['app']['start'] = 'index.html';
    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/app.json',json_encode($app_config));

    $iframe_html = null;
    @unlink('output/'.$_SESSION['FILE_NAME'].'/www/index.php');
    switch($type_webview)
    {
        case 'none':
            @unlink('projects/'.$file_name.'/mod.cordova-plugin-remote-injection.json');
            @unlink('projects/'.$file_name.'/configxml.json');
            @unlink('projects/'.$_SESSION['FILE_NAME'].'/js.json');

            buildIonic($file_name);
            break;
        case 'config.xml':
            @unlink('projects/'.$file_name.'/mod.cordova-plugin-remote-injection.json');
            @unlink('projects/'.$file_name.'/configxml.json');
            $js_json['js']['directives'] = '';
            $app_config['app'] = $_SESSION['PROJECT']['app'];
            $app_config['app']['start'] = $site_url;
            buildIonic($file_name);
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/css');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/data');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/fonts');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/lib');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/js');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/templates');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/translations');
            @unlink('output/'.$_SESSION['FILE_NAME'].'/www/index.html');
            @unlink('output/'.$_SESSION['FILE_NAME'].'/www/start.html');

            if(!file_exists('output/'.$_SESSION['FILE_NAME'].'/www/'))
            {
                @mkdir('output/'.$_SESSION['FILE_NAME'].'/www/',0777,true);
            }
            file_put_contents('output/'.$_SESSION['FILE_NAME'].'/www/index.php','<html><style type="text/css">*{text-align: center;}</style><h1>Cordova Webview</h1><p>Only work in real device/android</p><html>');
            file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/app.json',json_encode($app_config));

            $js_json['js']['directives'] = '';
            $js_json['js']['router'] = '';
            file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/js.json',json_encode($js_json));

            $retry_code = '<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no"/>
		<title>Retry</title>
		<style>html,body{height:100%}body{font-size:14px;line-height:1.25em;position:relative;background-color:#fff;font-family:Helvetica;-webkit-text-size-adjust:none;color:#000}a{color:#000;text-decoration:none}#content{height:100%}.error{position:absolute;top:50%;left:0;width:100%;margin-top:-22px;text-align:center}.error .btn_retry a{display:inline-block;width:136px;height:44px;background:#848484;line-height:44px;font-size:16px;color:#fff}.error .desc{position:absolute;width:100%;bottom:62px;font-size:14px;line-height:17px;color:#9b9b9b}@media all and (min-width:768px){.error .desc{font-size:20px;bottom:88px;line-height:24px}.error{margin-top:-32px}.error .btn_retry a{width:192px;height:63px;line-height:63px;font-size:22px}}</style>
	</head>
	<body>
		<div id="content">
			<div class="error">
				<p class="desc">Unable to connect to the network.<br/>Please check the network connection.</p>
				<div class="btn_retry"><a href="'.$site_url.'">Retry</a></div>
			</div>
		</div>
	</body>
</html>';

            file_put_contents('output/'.$file_name.'/www/retry.html',$retry_code);
            file_put_contents('output/'.$file_name.'/www/index.html',null);
            break;
        case 'config-with-plugin-inject':
            $mod = null;
            $mod['mod']['remote-injection']['name'] = 'cordova-plugin-remote-injection';
            $mod['mod']['remote-injection']['engines'] = 'cordova';
            $mod['mod']['remote-injection']['info'] = 'required by Page Builder Full Webview';

            file_put_contents('projects/'.$file_name.'/mod.cordova-plugin-remote-injection.json',json_encode($mod));

            $wishlist = $site_url.'/*';
            $configXml['configxml']['code'] = "\t".'<preference name="CRIInjectFirstFiles" value="www/js/init.js" />'."\r\n";
            $configXml['configxml']['code'] .= "\t".'<allow-navigation href="'.str_replace('//*','/*',$wishlist).'"/>'."\r\n";

            file_put_contents('projects/'.$file_name.'/configxml.json',json_encode($configXml));


            $js_json['js']['directives'] = '';
            $app_config['app'] = $_SESSION['PROJECT']['app'];
            $app_config['app']['start'] = $site_url;
            buildIonic($file_name);

            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/css');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/data');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/fonts');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/lib');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/js');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/templates');
            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/translations');
            @unlink('output/'.$_SESSION['FILE_NAME'].'/www/index.html');
            @unlink('output/'.$_SESSION['FILE_NAME'].'/www/start.html');

            if(!file_exists('output/'.$_SESSION['FILE_NAME'].'/www/js'))
            {
                @mkdir('output/'.$_SESSION['FILE_NAME'].'/www/js/',0777,true);
            }
            file_put_contents('output/'.$_SESSION['FILE_NAME'].'/www/index.php','<html><style type="text/css">*{text-align: center;}</style><h1>Cordova Webview</h1><p>Only work in real device/android</p><html>');
            file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/app.json',json_encode($app_config));


            $retry_code = '<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no"/>
		<title>Retry</title>
		<style>html,body{height:100%}body{font-size:14px;line-height:1.25em;position:relative;background-color:#fff;font-family:Helvetica;-webkit-text-size-adjust:none;color:#000}a{color:#000;text-decoration:none}#content{height:100%}.error{position:absolute;top:50%;left:0;width:100%;margin-top:-22px;text-align:center}.error .btn_retry a{display:inline-block;width:136px;height:44px;background:#848484;line-height:44px;font-size:16px;color:#fff}.error .desc{position:absolute;width:100%;bottom:62px;font-size:14px;line-height:17px;color:#9b9b9b}@media all and (min-width:768px){.error .desc{font-size:20px;bottom:88px;line-height:24px}.error{margin-top:-32px}.error .btn_retry a{width:192px;height:63px;line-height:63px;font-size:22px}}</style>
	</head>
	<body>
		<div id="content">
			<div class="error">
				<p class="desc">Unable to connect to the network.<br/>Please check the network connection.</p>
				<div class="btn_retry"><a href="'.$site_url.'">Retry</a></div>
			</div>
		</div>
	</body>
</html>';

            file_put_contents('output/'.$file_name.'/www/retry.html',$retry_code);


            // TODO: onesignal-cordova-plugin
            $admob = $pushnotification = null;
            if(isset($_SESSION['PROJECT']['push']['plugin']))
            {
                if($_SESSION['PROJECT']['push']['plugin'] == 'onesignal-cordova-plugin')
                {
                    $onesignal_id = $_SESSION['PROJECT']['push']['app_id'];
                    $pushnotification = '
        // cordova plugin add onesignal-cordova-plugin --save      
		if(window.plugins && window.plugins.OneSignal){
            window.plugins.OneSignal.enableNotificationsWhenActive(true);
            var notificationOpenedCallback = function(jsonData){};
            window.plugins.OneSignal.startInit("'.$onesignal_id.'").handleNotificationOpened(notificationOpenedCallback).endInit();
		}
        ';
                }
            }

            if(isset($_SESSION['PROJECT']['mod']['admob-free']['data']))
            {
                $admob_data = $_SESSION['PROJECT']['mod']['admob-free']['data'];
                $admob = '
        // cordova plugin add cordova-plugin-admob-free --save      
		if (typeof admob !== "undefined"){
		
				// banner
				admob.banner.config({id: "'.$admob_data['banner']['code'].'"});
				admob.banner.prepare();
				admob.banner.show();
			 
				// interstitial
				admob.interstitial.config({id: "'.$admob_data['interstitial']['code'].'"});
				admob.interstitial.prepare();
				admob.interstitial.show();
		}
        ';
            }

            if(isset($_SESSION['PROJECT']['mod']['admob']['data']))
            {
                $admob_data = $_SESSION['PROJECT']['mod']['admob']['data'];
                $admob = '
          // cordova plugin add cordova-plugin-admobpro --save    
		  if (typeof AdMob !== "undefined"){
		      
                // banner
                AdMob.createBanner({
                    adId: "'.$admob_data['banner']['code'].'",
                    overlap: false,
                    offsetTopBar: false,
                    bgColor: "black"
                });
                
                // interstitial
                AdMob.prepareInterstitial({
                    adId: "'.$admob_data['interstitial']['code'].'",
                    autoShow: true,
                });
                                
                // rewardvideo
                AdMob.prepareRewardVideoAd({
                    adId:"'.$admob_data['rewardvideo']['code'].'",
                    autoShow: true,
                });   
                    
           }
            
                ';

            }
            $js_code = '
var app = {
     
    initialize: function() {
        document.addEventListener("deviceready", this.onDeviceReady.bind(this), false);
    },

	onDeviceReady: function() {
     
		// cordova plugin add cordova-plugin-inappbrowser --save 
		document.onclick = function (e){
			e = e ||  window.event;
			var element = e.target || e.srcElement;
			
			// appbrowser
			if (element.target == "_blank") {
				window.open(element.href, "_blank", "location=yes");
				return false;
			}
			
			// link download
			if (element.target == "_system") {
				window.open(element.href, "_system", "location=yes");
				return false;
			}
			
			// webview
			if (element.target == "_self") {
				window.open(element.href, "_self");
				return false;
			}
			
		};
		
        '.$admob.' 
        
		'.$pushnotification.'
    },
};

app.initialize();            
            
            
            ';
            file_put_contents('output/'.$file_name.'/www/js/init.js',$js_code);
            file_put_contents('output/'.$file_name.'/www/index.html',null);

            $js_json['js']['directives'] = '';
            $js_json['js']['router'] = '';
            file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/js.json',json_encode($js_json));


            break;

        case 'iframe':
            @unlink('projects/'.$file_name.'/mod.cordova-plugin-remote-injection.json');
            @unlink('projects/'.$file_name.'/configxml.json');

            $iframe_html = '
                            <ion-pane>
                              <ion-content scroll="true" overflow-scroll="true">
                        		<iframe ng-src="{{ \''.$site_url.'\' | trustUrl }}"  class="fullscreen"></iframe>
                           	  </ion-content>
                            </ion-pane>
                        ';
            $js_json['js']['directives'] = '';
            $js_json['js']['router'] = '';
            file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/js.json',json_encode($js_json));

            $html_code = '<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <title></title>
    <link href="lib/ionic/css/ionic.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style type="text/css">*{text-align: center;}</style>
  </head>
  
  <body ng-app="'.$file_name.'" class="platform-android platform-cordova platform-webview" ng-controller="indexCtrl" id="{{ page_id }}">
    
        '.$iframe_html.'
        
		<script src="lib/ionic/js/angular-chart/Chart.min.js"></script>
		<script src="lib/localforage/localforage.min.js"></script>
		<script src="lib/ionic/js/ionic.bundle.min.js"></script>
 	    <script src="lib/ionic/js/angular-dynamic-locale/tmhDynamicLocale.min.js"></script>
		<script src="lib/ionic/js/angular-translate/angular-translate.min.js"></script>
		<script src="lib/ionic/js/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js"></script>
		<script src="lib/ionic/js/angular-utf8-base64/angular-utf8-base64.js"></script>
		<script src="lib/ionic/js/angular-chart/angular-chart.min.js"></script>
		<script src="lib/ionic/js/angular-md5/angular-md5.min.js"></script>
		<script src="lib/ionic-material/ionic.material.min.js"></script>
		<script src="lib/ion-md-input/js/ion-md-input.min.js"></script>
		<script src="lib/ionic-rating/ionic-rating.min.js"></script>
		<script src="lib/ion-slide-tabs/js/slidingTabsDirective.js"></script>
		<script src="lib/ion-datetime-picker/ion-datetime-picker.min.js"></script>
		<script src="lib/ionic-image-lazy-load/ionic-image-lazy-load.js"></script>
		<script src="lib/ionic/js/angular-cordova/ng-cordova.min.js"></script>
		<script src="cordova.js"></script>
		<script src="js/app.js"></script>
		<script src="js/controllers.js"></script>
		<script src="js/services.js"></script>
        
  </body>
  
</html>';

            buildIonic($file_name);
            file_put_contents('output/'.$file_name.'/www/index.html',$html_code);


            break;
        case 'inappbrowser':
            @unlink('projects/'.$file_name.'/mod.cordova-plugin-remote-injection.json');
            @unlink('projects/'.$file_name.'/configxml.json');
            $iframe_html = '';
            $js_json['js']['directives'] = '
            
.run(function($ionicPlatform, $ionicLoading){
	$ionicPlatform.ready(function(){
	   
		var ref = window.open("'.$site_url.'", "_blank","location=no");

        ref.addEventListener("loadstart", function() {
			ref.insertCSS({
				code: ""
			});
		});

		ref.addEventListener("loadstop", function() {
			ref.insertCSS({
				code: ""
			});
		});

		ref.addEventListener("loaderror", function(){
            ref.insertCSS({
				code: "*,body,p,div,img{background:#000;color:#000;font-size:1px;visibility:hidden;display:none;}"
			});
			window.location = "retry.html";
		});


		ref.addEventListener("exit", function() {
			ionic.Platform.exitApp();
		});

	});
})
            ';
            $js_json['js']['router'] = '';
            file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/js.json',json_encode($js_json));


            $html_code = '<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <title></title>
    <link href="lib/ionic/css/ionic.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style type="text/css">*{text-align: center;}</style>
  </head>
  
  <body ng-app="'.$file_name.'" class="platform-android platform-cordova platform-webview" ng-controller="indexCtrl" id="{{ page_id }}">
    
        '.$iframe_html.'
        
		<script src="lib/ionic/js/angular-chart/Chart.min.js"></script>
		<script src="lib/localforage/localforage.min.js"></script>
		<script src="lib/ionic/js/ionic.bundle.min.js"></script>
 	    <script src="lib/ionic/js/angular-dynamic-locale/tmhDynamicLocale.min.js"></script>
		<script src="lib/ionic/js/angular-translate/angular-translate.min.js"></script>
		<script src="lib/ionic/js/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js"></script>
		<script src="lib/ionic/js/angular-utf8-base64/angular-utf8-base64.js"></script>
		<script src="lib/ionic/js/angular-chart/angular-chart.min.js"></script>
		<script src="lib/ionic/js/angular-md5/angular-md5.min.js"></script>
		<script src="lib/ionic-material/ionic.material.min.js"></script>
		<script src="lib/ion-md-input/js/ion-md-input.min.js"></script>
		<script src="lib/ionic-rating/ionic-rating.min.js"></script>
		<script src="lib/ion-slide-tabs/js/slidingTabsDirective.js"></script>
		<script src="lib/ion-datetime-picker/ion-datetime-picker.min.js"></script>
		<script src="lib/ionic-image-lazy-load/ionic-image-lazy-load.js"></script>
		<script src="lib/ionic/js/angular-cordova/ng-cordova.min.js"></script>
		<script src="cordova.js"></script>
		<script src="js/app.js"></script>
		<script src="js/controllers.js"></script>
		<script src="js/services.js"></script>
        
  </body>
  
</html>';

            buildIonic($file_name);

            @rrmdir('output/'.$_SESSION['FILE_NAME'].'/www/data');
            file_put_contents('output/'.$file_name.'/www/index.html',$html_code);


            break;
    }


}

$pagebuilder_file = 'projects/'.$_SESSION['FILE_NAME'].'/page_builder.full_webview.json';
$raw_data = array();
if(file_exists($pagebuilder_file))
{
    $_raw_data = json_decode(file_get_contents($pagebuilder_file),true);
    $raw_data = $_raw_data['page_builder']['full_webview'];
}

if(!isset($raw_data['site_url']))
{
    $raw_data['site_url'] = 'https://cordova.apache.org/docs/';
    $raw_data['type'] = 'iframe';
}

$out_path = 'output/'.$file_name;
$preview_url = $out_path.'/www/index.html';

switch($raw_data['type'])
{
    case 'config.xml':
        $preview_url = 'system/includes/page-builder/full_webview/assets/page/cordova-webview';
        break;
    case 'inappbrowser':
        $preview_url = 'system/includes/page-builder/full_webview/assets/page/cordova-in-app-browser';
        break;
    case 'config-with-plugin-inject':
        $preview_url = 'system/includes/page-builder/full_webview/assets/page/cordova-webview';
        break;
}

$_option_webviews[] = array('label' => 'None / Reset / Back to Normal App','value' => 'none');
$_option_webviews[] = array('label' => 'iframe','value' => 'iframe');
$_option_webviews[] = array('label' => 'Cordova - Plugin inAppBrowser (recommended)','value' => 'inappbrowser');
$_option_webviews[] = array('label' => 'Cordova - Webview (config.xml)','value' => 'config.xml');
$_option_webviews[] = array('label' => 'Cordova - Webview + Plugin Inject','value' => 'config-with-plugin-inject');


$z = 0;
foreach($_option_webviews as $_option_webview)
{
    $option_webview[$z] = $_option_webview;
    if($_option_webview['value'] == $raw_data['type'])
    {
        $option_webview[$z]['active'] = true;
    }
    $z++;
}
$form_input .= '<blockquote class="blockquote blockquote-warning">';
$form_input .= '<ol>';

$form_input .= '<li>';
$form_input .= 'iframe type for online web, add this code to .htaccess:';
$form_input .= '<code>Header set X-Frame-Options "ALLOWALL"</code>';
$form_input .= '</li>';

$form_input .= '<li>';
$form_input .= 'Webview type <code>InAppBrowser</code> and <code>Cordova Webview</code> only work in real device/android, ';
$form_input .= 'IMAB Emulator will be open new window so please ignore it';
$form_input .= '</li>';

$form_input .= '<li>';
$form_input .= '<p>Offline, create <code>new folder</code> on folder: <br/><code>'.realpath(JSM_PATH.'/output/'.$file_name.'/www/').'</code></p>';
$form_input .= '</li>';


$form_input .= '<li>';
$form_input .= '<p>in case you are displaying your own webpages, more something only can be control by your web not imabuilder, like scalable, link or other.</p>';
$form_input .= '</li>';

$form_input .= '<li>';
$form_input .= 'For remove zoom button in webview, you can try to add/edit the following into your HTML header tag:';
$form_input .= '<pre>';
$form_input .= htmlentities('<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0">');
$form_input .= '</pre>';

$form_input .= '<p>Statusbar issue on Apple\'s iPhone X or Latest iOS, read this: <a target="_blank" href="https://css-tricks.com/the-notch-and-css/">css-tricks</a></p>';
$form_input .= '<pre>';
$form_input .= htmlentities('<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0, viewport-fit=cover">');
$form_input .= '</pre>';
//https://css-tricks.com/the-notch-and-css/

$form_input .= '</pre>';
$form_input .= '</li>';
$form_input .= '</ol>';
$form_input .= '
<table class="table table-stripped">
<tr>
	<th>Embed Type</th>
	<th>Support</th>
	<th>Info</th>
    <th>Emulator</th>
</tr>
<tr>
	<td>iframe</td>
	<td>
        <ul>
            <li>Admob Banner</li>
            <li>Admob Interstitial</li>
            <li>Admob Reward Video</li>
            <li>OneSignal Push notification and Alert</li>
        </ul>
    </td>
	<td>Required:<br/>X-Frame allowed by WebServer</td>
    <td>IMA Emulator and Real Device</td>
</tr>
<tr>
	<td>inAppBrowser</td>
	<td>     
        <ul>
            <li>Admob Interstitial</li>
            <li>Admob Reward Video</li>
            <li>OneSignal only Push notification </li>
            <li>Download Link, AppBrowser</li>
        </ul>
    </td>
	<td>-</td>
    <td>Only Real Device</td>
</tr>
<tr>
	<td>Cordova Webview</td>
	<td>-</td>
	<td>-</td>
    <td>Only Real Device</td>
</tr>
<tr>
	<td>Cordova Webview + Inject JS<br/><small>Using Admob maynot pass the playstore</small></td>
	<td>
        <ul>
            <li>Admob Banner</li>
            <li>Admob Interstitial</li>
            <li>Admob Reward Video</li>
            <li>OneSignal Push notification and Alert</li>
            <li>Download Link, AppBrowser</li>
        </ul>    
    
    </td>
	<td>-</td>
    <td>Only Real Device</td>
</tr>

</table>

';


$form_input .= '</blockquote>';
$form_input .= '<hr/>';
$form_input .= '<h4>Settings</h4>';
$form_input .= $bs->FormGroup('full_webview[site_url]','horizontal','text','Site URL','http://demo.ihsana.net/','',null,'7',$raw_data['site_url']);
$form_input .= $bs->FormGroup('full_webview[type]','horizontal','select','Type',$option_webview,'','','4');

?>