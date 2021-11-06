<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2017
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */
if(!defined('JSM_EXEC')) {
    die(':)');
}
$file_name = 'test';
$bs = new jsmBootstrap();
$form_input = $html = null;
$error_notice = array();
if(isset($_SESSION['FILE_NAME'])) {
    $file_name = $_SESSION['FILE_NAME'];
} else {
    header('Location: ./?page=dashboard&err=project');
    die();
}
$direction = null;
if($_SESSION["PROJECT"]['app']['direction'] == 'rtl') {
    $direction = 'dir="rtl"';
}

if(file_exists('projects/'.$file_name.'/page..json')) {
    unlink('projects/'.$file_name.'/page..json');
}

if(file_exists('output/config.xml')) {
    unlink('output/config.xml');
}

$imabuilder['ima_builder']['name'] = 'IMA BuildeRz';
$imabuilder['ima_builder']['version'] = JSM_VERSION;
$imabuilder['ima_builder']['licensed_to'] = JSM_ENVATO_USERNAME;
$imabuilder['ima_builder']['email'] = JSM_EMAIL;

file_put_contents('projects/'.$file_name.'/ima_builder.json',json_encode($imabuilder));

$notice = null;
$out_path = 'output/'.$file_name;
$max_menu = 6;
$raw_menu['menu']['title'] = $_SESSION['PROJECT']['app']['name'];
$raw_menu['menu']['type'] = 'side_menus';
$raw_menu['menu']['header_background'] = 'positive-900';
$raw_menu['menu']['menu_color'] = 'dark';
$raw_menu['menu']['menu_background'] = 'stable';
$raw_menu['menu']['menu_style'] = 'expanded-header';
$raw_menu['menu']['expanded_header'] = 'data/images/header/header.jpg';
$raw_menu['menu']['logo'] = 'data/images/header/logo.png';
$raw_menu['menu']['header_image_background'] = '';
$raw_menu['menu']['menu_position'] = 'left';
$raw_menu['menu']['items'][0]['label'] = 'Dashboard';
$raw_menu['menu']['items'][0]['var'] = 'dashboard';
$raw_menu['menu']['items'][0]['icon'] = 'ion-ios-home';
$raw_menu['menu']['items'][0]['icon-alt'] = 'ion-ios-home';
$raw_menu['menu']['items'][0]['type'] = 'divider';
$raw_menu['menu']['items'][1]['label'] = 'Menu One';
$raw_menu['menu']['items'][1]['var'] = '';
$raw_menu['menu']['items'][1]['icon'] = 'ion-android-favorite';
$raw_menu['menu']['items'][1]['icon-alt'] = 'ion-android-favorite';
$raw_menu['menu']['items'][1]['type'] = 'link';
$raw_menu['menu']['items'][1]['desc'] = 'Example Menu 1';
$raw_menu['menu']['items'][2]['label'] = 'Menu Two';
$raw_menu['menu']['items'][2]['var'] = '';
$raw_menu['menu']['items'][2]['icon'] = 'ion-android-favorite';
$raw_menu['menu']['items'][2]['icon-alt'] = 'ion-android-favorite';
$raw_menu['menu']['items'][2]['type'] = 'link';
$raw_menu['menu']['items'][2]['desc'] = 'Example Menu 2';
$raw_menu['menu']['items'][3]['label'] = 'Help';
$raw_menu['menu']['items'][3]['var'] = 'help';
$raw_menu['menu']['items'][3]['icon'] = 'ion-help-circled';
$raw_menu['menu']['items'][3]['icon-alt'] = 'ion-help-circled';
$raw_menu['menu']['items'][3]['type'] = 'divider';
$raw_menu['menu']['items'][4]['label'] = 'Faqs';
$raw_menu['menu']['items'][4]['var'] = 'faqs';
$raw_menu['menu']['items'][4]['icon'] = 'ion-android-chat';
$raw_menu['menu']['items'][4]['icon-alt'] = 'ion-android-chat';
$raw_menu['menu']['items'][4]['type'] = 'link';
$raw_menu['menu']['items'][4]['desc'] = 'FAQs and Tutorial';
$raw_menu['menu']['items'][5]['label'] = 'Rate This App';
$raw_menu['menu']['items'][5]['var'] = 'rate_this_app';
$raw_menu['menu']['items'][5]['icon'] = 'ion-android-playstore';
$raw_menu['menu']['items'][5]['icon-alt'] = 'ion-android-playstore';
$raw_menu['menu']['items'][5]['type'] = 'ext-playstore';
$raw_menu['menu']['items'][5]['desc'] = 'Taking a moment to rate this app?';
if(file_exists('projects/'.$file_name.'/menu.json')) {
    $raw_menu = json_decode(file_get_contents('projects/'.$file_name.'/menu.json'),true);
    $max_menu = count($raw_menu['menu']['items']);
}
if(!isset($_GET['max-menu'])) {
    $_GET['max-menu'] = $max_menu;
}
$max_menu = (int)$_GET['max-menu'];
// TODO: SAVE MENU
if(!isset($_SESSION['menu-save'])) {
    $_SESSION['menu-save'] = 1;
}
if(isset($_POST['menu-save'])) {

    if($_SESSION['menu-save'] == 2) {
        $_SESSION['menu-save'] = 1;
    } else {
        $_SESSION['menu-save']++;
    }


    $index_prefix = $_SESSION['PROJECT']['app']['index'];
    if(!file_exists('projects/'.$file_name.'/page.'.$index_prefix.'.json')) {
        $app_json = file_get_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/app.json');
        $app_config = json_decode($app_json,true);
        $app_config['app']['index'] = 'dashboard';
        file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/app.json',json_encode($app_config));
    }
    $_POST['menu']['title'] = $_SESSION['PROJECT']['app']['name'];

    if(!empty($_POST['menu']['title'])) {
        if(!isset($_POST['menu']['menu_color'])) {
            $_POST['menu']['menu_color'] = 'positive';
        }
        if(!isset($_POST['menu']['menu_background'])) {
            $_POST['menu']['menu_background'] = 'light';
        }
        if(!is_dir('projects/'.$file_name)) {
            mkdir('projects/'.$file_name,0777,true);
        }
        $data['menu'] = $_POST['menu'];
        $data['menu']['items'] = array();
        $data['menu']['title'] = $_SESSION['PROJECT']['app']['name'];
        if(!isset($_POST['menu']['items'])) {
            $_POST['menu']['items'][0]['label'] = 'Dashboard';
            $_POST['menu']['items'][0]['var'] = 'dashboard';
            $_POST['menu']['items'][0]['icon'] = 'ion-ios-home';
            $_POST['menu']['items'][0]['icon-alt'] = 'ion-ios-home';
            $_POST['menu']['items'][0]['type'] = 'divider';
        }
        $z = 0;
        foreach($_POST['menu']['items'] as $item) {
            
            $item['label'] = trim($item['label']);
            $item['var'] = trim($item['var']);
            
            $new_page = array();
            if(strlen($item['label']) > 1) {

                if(str2var($item['var']) == '') {
                    $item['var'] = str2var($item['label']);

                    $item['var'] = str_replace('0','zero_',$item['var']);
                    $item['var'] = str_replace('1','one_',$item['var']);
                    $item['var'] = str_replace('2','two_',$item['var']);
                    $item['var'] = str_replace('3','three_',$item['var']);
                    $item['var'] = str_replace('4','four_',$item['var']);
                    $item['var'] = str_replace('5','five_',$item['var']);
                    $item['var'] = str_replace('6','six_',$item['var']);
                    $item['var'] = str_replace('7','seven_',$item['var']);
                    $item['var'] = str_replace('8','eight_',$item['var']);
                    $item['var'] = str_replace('9','nine_',$item['var']);
                }

                $item['var'] = str_replace("'","",$item['var']);

                if(is_numeric($item['var'][0])) {
                    $item['var'] = '_'.$item['var'];
                }

                if(str2var($item['icon']) == '') {
                    $item['icon'] = 'ion-ionic';
                }

                if(str2var($item['icon-alt']) == '') {
                    $item['icon-alt'] = str2var($item['icon']);
                }


                $data['menu']['items'][] = array(
                    'label' => trim($item['label']),
                    'var' => trim(str2var($item['var'])),
                    'icon' => $item['icon'],
                    'icon-alt' => $item['icon-alt'],
                    'type' => $item['type'],
                    'option' => $item['option'],
                    'desc' => $item['desc']);
                $page_title = $item['label'];

                $page_prefix = str2var($item['var']);

                // TODO: -- | -- sample page
                $new_page['page'][0] = array(
                    'title' => $page_title,
                    'prefix' => $page_prefix,
                    'parent' => $data['menu']['type'],
                    'menutype' => $data['menu']['type'],
                    'lock' => false,
                    'js' => '$ionicConfig.backButton.text("");',
                    'version' => 'Upd.'.date('ymdhi'),
                    'menu' => str2var($_POST['menu']['title']),
                    'for' => '-',
                    'priority' => 'low',
                    'last_edit_by' => 'menu');
                if(($item['type'] == 'link') || ($item['type'] == 'iframe')) {
                    if($item['type'] == 'link') {
                        $new_page['page'][0]['content'] = '
<div class="padding">
  <div '.$direction.'>
    <h4>'.$page_title.'</h4>
    <p>This page is under construction. Please come back soon!</p>
  </div>
</div>
';
                    } else {
                        $new_page['page'][0]['overflow-scroll'] = 'true';
                        $new_page['page'][0]['scroll'] = 'true';
                        $new_page['page'][0]['content'] = "\t".'<iframe data-tap-disabled="true" width="100%" height="100%" class="fullscreen" ng-src="{{ \''.$item['option'].'\' | trustUrl  }}"></iframe>';
                    }
                    $is_lock = false;
                    $lock_path = 'projects/'.$file_name.'/page.'.$page_prefix.'.json';
                    if($item['type'] == 'iframe') {
                        @unlink($lock_path);
                    }
                    if(file_exists($lock_path)) {
                        $lock_data = json_decode(file_get_contents($lock_path),true);
                        $is_lock = $lock_data['page'][0]['lock'];
                        $new_page['page'][0] = $lock_data['page'][0];
                        $new_page['page'][0]['menutype'] = $data['menu']['type'];
                        $new_page['page'][0]['parent'] = $data['menu']['type'];
                    }
                    if($is_lock == true) {
                        $error_notice[] = 'Page <code>'.$page_prefix.'</code> is <span class="fa fa-lock"></span> locked.';
                    } else {
                        if(file_exists($lock_path)) {
                            @copy($lock_path,$lock_path.'.'.time().'.save');
                        }
                        file_put_contents($lock_path,json_encode($new_page));
                    }
                }
                $z++;
            }
        }
        // TODO: refix all page
        foreach(glob("projects/".$file_name."/page.*.json") as $filename) {
            $_list_pages = json_decode(file_get_contents($filename),true);
            if(isset($_list_pages['page'][0])) {
                $fix_page = $_list_pages['page'][0]['menutype'];
                if(($fix_page == 'tabs-custom') || ($fix_page == 'side_menus-custom')) {
                    $_list_pages['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'].'-custom';
                    @copy($filename,$filename.'.'.time().'.save');
                    file_put_contents($filename,json_encode($_list_pages));
                }
                if(($fix_page == 'sub-tabs') || ($fix_page == 'sub-side_menus')) {
                    $_list_pages['page'][0]['menutype'] = 'sub-'.$_SESSION['PROJECT']['menu']['type'];
                    @copy($filename,$filename.'.'.time().'.save');
                    file_put_contents($filename,json_encode($_list_pages));
                }
            }
        }
        // TODO: -- | -- sample page - about us
        $about_us_content = null;
        $about_us_content .= '
<div class="text-center">
    <img class="avatar relative" ng-src="'.$raw_menu['menu']['logo'].'" />
    <p>'.ucwords($_SESSION["PROJECT"]["app"]["name"]).' v'.ucwords($_SESSION["PROJECT"]["app"]["version"]).'</p>
</div>
<br/>
<div class="list card">';
        if(strlen($_SESSION["PROJECT"]["app"]["fb"]) > 2) {
            $about_us_content .= '
  <a class="item item-icon-left" ng-click="openURL(\''.strtolower($_SESSION["PROJECT"]["app"]["fb"]).'\')" >
    <i class="positive icon ion-social-facebook"></i>
    Like Us on Facebook
  </a>    
    ';
        }
        if(strlen($_SESSION["PROJECT"]["app"]["gplus"]) > 2) {
            $about_us_content .= '
  <a class="item item-icon-left" ng-click="openURL(\''.strtolower($_SESSION["PROJECT"]["app"]["gplus"]).'\')" >
    <i class="assertive icon ion-social-googleplus"></i>
    Join us on Google+
  </a> ';
        }
        if(strlen($_SESSION["PROJECT"]["app"]["twitter"]) > 2) {
            $about_us_content .= '
  <a class="item item-icon-left" ng-click="openURL(\''.strtolower($_SESSION["PROJECT"]["app"]["twitter"]).'\')" >
    <i class="calm icon ion-social-twitter"></i>
   Follow me on Twitter
  </a> ';
        }
        $about_us_content .= '  
   <a class="item item-icon-left" ng-click="openURL(\'mailto:'.strtolower($_SESSION["PROJECT"]["app"]["author_email"]).'\')" >
    <i class="icon ion-android-mail royal"></i>
    For Business Cooperation<br/>
    <span>
        Email: '.strtolower($_SESSION["PROJECT"]["app"]["author_email"]).'
    </span>
  </a>
</div>
<br/>
<div class="text-center">
    <p>&copy; '.date("Y").' <a ng-click="openURL(\''.strtolower($_SESSION["PROJECT"]["app"]["author_url"]).'\')" >'.$_SESSION["PROJECT"]["app"]["company"].'</a><br/>All Rights Reserved</p>
</div>
';
        $new_page = null;
        $new_page['page'][] = array(
            'title' => 'About Us',
            'prefix' => 'about_us',
            'for' => '-',
            'last_edit_by' => 'menu',
            'builder_link' => './?page=x-page-builder&prefix=page_about_us&target=about_us',
            'priority' => 'low',
            'parent' => '',
            'menutype' => $_SESSION['PROJECT']['menu']['type'].'-custom',
            'menu' => '',
            'lock' => false,
            'version' => 'Upd.'.date('ymdhi'),
            'js' => '$ionicConfig.backButton.text("");',
            'class' => 'padding',
            'bg_image' => true,
            'content' => $about_us_content);
        // TODO: -- | -- save - about_us
        $is_lock = false;
        $lock_path = 'projects/'.$file_name.'/page.about_us.json';
        if(file_exists($lock_path)) {
            $lock_data = json_decode(file_get_contents($lock_path),true);
            $is_lock = $lock_data['page'][0]['lock'];
        }
        if($is_lock == true) {
            $error_notice[] = 'Page <code>about</code> is <span class="fa fa-lock"></span> locked.';
        } else {
            if(file_exists('projects/'.$file_name.'/page.about_us.json')) {
                @copy('projects/'.$file_name.'/page.about_us.json','projects/'.$file_name.'/page.about_us.json'.'.'.time().'.save');
            }
            file_put_contents('projects/'.$file_name.'/page.about_us.json',json_encode($new_page));
        }
        // TODO: -- | -- sample page - dashboard
        $menu_list = "\r\n";
        $bg_colors = array(
            'positive',
            'positive-900',
            'calm',
            'calm-900',
            'balanced',
            'balanced-900',
            'energized',
            'energized-900',
            'assertive',
            'assertive-900',
            'royal',
            'royal-900',
            );
        $menu_list .= "\t\t".'<div class="dashboard-panel">'."\r\n";
        $z = 0;
        $co_index = 0;
        foreach($data['menu']['items'] as $menu_item) {
            if($menu_item['type'] != "divider") {
                $sref = null;
                $z++;
                if($z == 1) {
                    $menu_list .= "\t\t\t".'<!-- row -->'."\r\n";
                    $menu_list .= "\t\t\t".'<div class="row">'."\r\n";
                }
                if(($menu_item['type'] == 'link') || ($menu_item['type'] == 'iframe')) {
                    $param_query = '';
                    if(isset($_SESSION['PROJECT']['page'])) {
                        foreach($_SESSION['PROJECT']['page'] as $check_dinamic_page) {
                            if(str2var($menu_item['var']) == $check_dinamic_page['prefix']) {
                                if($check_dinamic_page['db_url_dinamic'] == 'on') {
                                    $check_dinamic_page['db_url_dinamic'] = true;
                                }
                                if($check_dinamic_page['db_url_dinamic'] == true) {
                                    if(strlen($check_dinamic_page['query_value']) >= 1) {
                                        $param_query = '/'.$check_dinamic_page['query_value'];
                                    }
                                }
                            }
                        }
                    }
                    $sref = ' ng-href="#/'.$_SESSION['PROJECT']['app']['prefix']."/".str2var($menu_item['var']).$param_query.'"';
                }
                $type_webview = array(
                    "webview",
                    "app-browser",
                    "ext-browser");
                if(in_array($menu_item['type'],$type_webview)) {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = $_SESSION['PROJECT']['app']['author_url'];
                    }
                }
                if($menu_item['type'] == 'app-home') {
                    $sref = 'ng-href="#/'.$_SESSION['PROJECT']['app']['prefix'].'/'.$_SESSION['PROJECT']['app']['index'].'"';
                }

                if($menu_item['type'] == 'app-browser') {
                    $sref = 'ng-click="openAppBrowser(\''.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'ext-browser') {
                    $sref = 'ng-click="openURL(\''.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'webview') {
                    $sref = 'ng-click="openWebView(\''.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'ext-email') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = $_SESSION['PROJECT']['app']['author_email'];
                    }
                    $sref = 'ng-click="openURL(\'mailto:'.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'ext-sms') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = '08123456789';
                    }
                    $sref = 'ng-click="openURL(\'sms:'.htmlentities($menu_item['option']).'\')"';
                }

                if($menu_item['type'] == 'html-link') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = '';
                    }
                    $sref = 'href="'.htmlentities($menu_item['option']).'"';
                }

                if($menu_item['type'] == 'ext-call') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = '08123456789';
                    }
                    $sref = 'ng-click="openURL(\'tel:'.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'ext-playstore') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = str_replace("_","",JSM_PACKAGE_NAME.'.'.str2var($_SESSION['PROJECT']['app']['company']).".".str2var($_SESSION['PROJECT']['app']['prefix']));
                    }
                    $sref = 'ng-click="openURL(\'market://details?id='.htmlentities($menu_item['option']).'\')"';
                }

                if($menu_item['type'] == 'ext-geo') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = "";
                    }
                    $sref = 'ng-click="openURL(\'geo:'.htmlentities($menu_item['option']).'\')"';
                }

                if($menu_item['type'] == 'app-exit') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = "";
                    }
                    $sref = 'ng-click="exitApp()"';
                }


                // TODO: -- | -- sample page - dashboard - item dinamic

                if(!isset($_SESSION['PROJECT']['cordova_plugin']['xsocialsharing']['enable'])) {
                    $_SESSION['PROJECT']['cordova_plugin']['xsocialsharing']['enable'] = false;
                }
                if($_SESSION['PROJECT']['cordova_plugin']['xsocialsharing']['enable'] == true) {
                    if($menu_item['type'] == 'xsocialsharing-share-myapp') {


                        if($menu_item['desc'] == "") {
                            $menu_item['desc'] = "I have been having fun with ".$_SESSION['PROJECT']['app']['name']." App. Try it NOW! :D";
                        }

                        if($menu_item['option'] == "") {
                            $menu_item['option'] = "https://play.google.com/store/apps/details?id=".str_replace("_","",JSM_PACKAGE_NAME.'.'.str2var($_SESSION['PROJECT']['app']['company']).".".str2var($_SESSION['PROJECT']['app']['prefix']));
                            ;
                        }

                        $text_invite = htmlentities($menu_item['desc']);
                        $link_invite = htmlentities($menu_item['option']);

                        $sref = 'ng-click="socialShare(null,\''.$text_invite.'\',null,\''.$link_invite.'\')"';
                    }
                }


                if(!isset($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'])) {
                    $_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] = false;
                }
                if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true) {

                    if($menu_item['type'] == 'barcodescanner-alert') {
                        if($menu_item['option'] == "") {
                            $menu_item['option'] = "";
                        }
                        $sref = 'ng-click="barcodeScanner(\'alert\')"';
                    }
                    if($menu_item['type'] == 'barcodescanner-link-internal') {
                        if($menu_item['option'] == "") {
                            $menu_item['option'] = "";
                        }
                        $sref = 'ng-click="barcodeScanner(\'inlink\')"';
                    }
                    if($menu_item['type'] == 'barcodescanner-link-external') {
                        if($menu_item['option'] == "") {
                            $menu_item['option'] = "";
                        }
                        $sref = 'ng-click="barcodeScanner(\'outlink\')"';
                    }
                    if($menu_item['type'] == 'barcodescanner-appbrowser') {
                        if($menu_item['option'] == "") {
                            $menu_item['option'] = "";
                        }
                        $sref = 'ng-click="barcodeScanner(\'appbrowser\')"';
                    }
                }
                $menu_list .= "\t\t\t\t".'<a class="col-33 no-border light '.$bg_colors[$co_index].'-bg ink" '.$sref.' >'."\r\n";
                $menu_list .= "\t\t\t\t\t".'<i class="icon '.htmlentities($menu_item['icon']).'" ></i>'."\r\n";
                $menu_list .= "\t\t\t\t\t".'<p>{{ "'.htmlentities($menu_item['label']).'" | translate }}</p>'."\r\n";
                $menu_list .= "\t\t\t\t".'</a>'."\r\n";
                $co_index++;
                if($co_index == 11) {
                    $co_index = 0;
                }
                if($z == 3) {
                    $menu_list .= "\t\t\t".'</div>'."\r\n";
                    $menu_list .= "\t\t\t".'<!-- ./row -->'."\r\n";
                    $menu_list .= "\t\t\t"."\r\n\r\n";
                    $z = 0;
                }
            } else {
                if($z != 0) {
                    $menu_list .= "\t\t\t".'</div>'."\r\n";
                    $menu_list .= "\t\t\t".'<!-- ./row -->'."\r\n";
                }
                $menu_list .= "\t\t\t".'<div class="item item-title no-border item-'.$data['menu']['header_background'].'">'.htmlentities($menu_item['label']).'</div>'."\r\n";
                $z = 0;
            }
        }
        if(($z < 3) && ($z != 0)) {
            $menu_list .= "\t\t\t".'</div>'."\r\n";
            $menu_list .= "\t\t\t".'<!-- ./row -->'."\r\n";
            $menu_list .= "\t\t\t"."\r\n\r\n";
        }
        $menu_list .= "\t\t".'</div>'."\r\n";
        $menu_list .= "\t\t".'<br/>'."\r\n";
        $menu_list .= "\t\t".'<br/>'."\r\n";
        $menu_list .= "\t\t".'<br/>'."\r\n";
        $menu_list .= "\t\t".'<br/>'."\r\n";
        $new_page = null;
        $new_page['page'][] = array(
            'title' => 'Dashboard',
            'prefix' => 'dashboard',
            'for' => '-',
            'last_edit_by' => 'menu',
            'priority' => 'low',
            'parent' => '',
            'menutype' => $_SESSION['PROJECT']['menu']['type'].'-custom',
            'menu' => '',
            'version' => 'Upd.'.date('ymdhi'),
            'table-code' => array('menu' => $menu_list),
            'lock' => false,
            'class' => '',
            'bg_image' => true,
            'img_bg' => 'data/images/background/bg0.jpg',
            'content' => $menu_list,
            'js' => '$ionicConfig.backButton.text("");',
            'title-tranparant' => false,
            'header-shrink' => false,
            //'hide-navbar' => true,
            'button_up' => 'none',
            'css' => '
.dashboard-panel .row .col-33 {text-decoration-line: unset;text-align: center;padding: 22px 20px 10px 20px;border:0;}
.dashboard-panel .row .col-33 i {font-size: 28px;margin-bottom: 2px;}
.dashboard-panel a:link, .dashboard-panel a:visited{text-decoration: none;}
.dashboard-panel .row .col-33 p {font-size: 12px;font-weight:500;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}             
            ');
        $is_lock = false;
        $lock_path = 'projects/'.$file_name.'/page.dashboard.json';
        if(file_exists($lock_path)) {
            $lock_data = json_decode(file_get_contents($lock_path),true);
            $is_lock = $lock_data['page'][0]['lock'];
        }
        if($is_lock == true) {
            $error_notice[] = 'Page <code>App Menu</code> is <span class="fa fa-lock"></span> locked.';
        } else {
            if(file_exists('projects/'.$file_name.'/page.dashboard.json')) {
                @copy('projects/'.$file_name.'/page.dashboard.json','projects/'.$file_name.'/page.dashboard.json'.'.'.time().'.save');
            }
            file_put_contents('projects/'.$file_name.'/page.dashboard.json',json_encode($new_page));
        }
        // TODO: -- | -- sample page - slide_tab_menu
        $is_use_divider = false;
        $z = -1;
        foreach($data['menu']['items'] as $menu_item) {
            if($menu_item['type'] == "divider") {
                $z++;
                $_menu_list[$z] = null;
            }
            if($menu_item['type'] != "divider") {
                $sref = null;
                if(($menu_item['type'] == 'link') || ($menu_item['type'] == 'iframe')) {
                    $param_query = '';
                    if(isset($_SESSION['PROJECT']['page'])) {
                        foreach($_SESSION['PROJECT']['page'] as $check_dinamic_page) {
                            if(str2var($menu_item['var']) == $check_dinamic_page['prefix']) {
                                if($check_dinamic_page['db_url_dinamic'] !== false) {
                                    if(strlen($check_dinamic_page['query_value']) >= 1) {
                                        $param_query = '/'.$check_dinamic_page['query_value'];
                                    }
                                }
                            }
                        }
                    }
                    $sref = 'ng-href="#/'.$_SESSION['PROJECT']['app']['prefix']."/".str2var($menu_item['var']).$param_query.'"';
                }
                $type_webview = array(
                    "webview",
                    "app-browser",
                    "ext-browser");
                if(in_array($menu_item['type'],$type_webview)) {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = $_SESSION['PROJECT']['app']['author_url'];
                    }
                }
                if($menu_item['type'] == 'app-browser') {
                    $sref = 'ng-click="openAppBrowser(\''.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'html-link') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = '';
                    }
                    $sref = 'href="'.htmlentities($menu_item['option']).'"';
                }
                if($menu_item['type'] == 'app-home') {
                    $sref = 'ng-href="#/'.$_SESSION['PROJECT']['app']['prefix'].'/'.$_SESSION['PROJECT']['app']['index'].'"';
                }

                if($menu_item['type'] == 'ext-browser') {
                    $sref = 'ng-click="openURL(\''.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'webview') {
                    $sref = 'ng-click="openWebView(\''.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'ext-email') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = $_SESSION['PROJECT']['app']['author_email'];
                    }
                    $sref = 'ng-click="openURL(\'mailto:'.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'ext-sms') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = '08123456789';
                    }
                    $sref = 'ng-click="openURL(\'sms:'.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'ext-call') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = '08123456789';
                    }
                    $sref = 'ng-click="openURL(\'tel:'.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'ext-playstore') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = str_replace("_","",JSM_PACKAGE_NAME.".".str2var($_SESSION['PROJECT']['app']['company']).".".str2var($_SESSION['PROJECT']['app']['prefix']));
                    }
                    $sref = 'ng-click="openURL(\'market://details?id='.htmlentities($menu_item['option']).'\')"';
                }
                if($menu_item['type'] == 'ext-geo') {
                    if($menu_item['option'] == "") {
                        $menu_item['option'] = "";
                    }
                    $sref = 'ng-click="openURL(\'geo:'.htmlentities($menu_item['option']).'\')"';
                }


                // TODO: -- | -- sample page - dashboard - item dinamic

                if(!isset($_SESSION['PROJECT']['cordova_plugin']['xsocialsharing']['enable'])) {
                    $_SESSION['PROJECT']['cordova_plugin']['xsocialsharing']['enable'] = false;
                }
                if($_SESSION['PROJECT']['cordova_plugin']['xsocialsharing']['enable'] == true) {
                    if($menu_item['type'] == 'xsocialsharing-share-myapp') {


                        if($menu_item['desc'] == "") {
                            $menu_item['desc'] = "I have been having fun with ".$_SESSION['PROJECT']['app']['name']." App. Try it NOW! :D";
                        }

                        if($menu_item['option'] == "") {
                            $menu_item['option'] = "https://play.google.com/store/apps/details?id=".str_replace("_","",JSM_PACKAGE_NAME.'.'.str2var($_SESSION['PROJECT']['app']['company']).".".str2var($_SESSION['PROJECT']['app']['prefix']));
                            ;
                        }

                        $text_invite = htmlentities($menu_item['desc']);
                        $link_invite = htmlentities($menu_item['option']);

                        $sref = 'ng-click="socialShare(null,\''.$text_invite.'\',null,\''.$link_invite.'\')"';
                        $menu_item['desc'] = 'Invite Friends';
                    }
                }


                if(!isset($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'])) {
                    $_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] = false;
                }
                if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true) {

                    if($menu_item['type'] == 'barcodescanner-alert') {
                        if($menu_item['option'] == "") {
                            $menu_item['option'] = "";
                        }
                        $sref = 'ng-click="barcodeScanner(\'alert\')"';
                    }
                    if($menu_item['type'] == 'barcodescanner-link-internal') {
                        if($menu_item['option'] == "") {
                            $menu_item['option'] = "";
                        }
                        $sref = 'ng-click="barcodeScanner(\'inlink\')"';
                    }
                    if($menu_item['type'] == 'barcodescanner-link-external') {
                        if($menu_item['option'] == "") {
                            $menu_item['option'] = "";
                        }
                        $sref = 'ng-click="barcodeScanner(\'outlink\')"';
                    }
                    if($menu_item['type'] == 'barcodescanner-appbrowser') {
                        if($menu_item['option'] == "") {
                            $menu_item['option'] = "";
                        }
                        $sref = 'ng-click="barcodeScanner(\'appbrowser\')"';
                    }
                }


                if(!isset($menu_item['desc'])) {
                    $menu_item['desc'] = 'simple desc menu';
                }
                $_menu_list[$z] .= "\t\t\t\t\t\t\t".'<a class="item item-icon-left" '.$sref.' >'."\r\n";
                $_menu_list[$z] .= "\t\t\t\t\t\t\t\t".'<i class="icon '.htmlentities($menu_item['icon']).'" ></i>'."\r\n";
                $_menu_list[$z] .= "\t\t\t\t\t\t\t\t".'<h2>{{ "'.htmlentities($menu_item['label']).'" | translate }}</h2>'."\r\n";
                $_menu_list[$z] .= "\t\t\t\t\t\t\t\t".'<span class="item-note pull-left">'.htmlentities($menu_item['desc']).'</span>'."\r\n";
                $_menu_list[$z] .= "\t\t\t\t\t\t\t".'</a>'."\r\n";
            }
        }
        $z = 0;
        $menu_list = "\r\n";
        $menu_list .= "\t"."<div style=\"width: 100%;height:100%;\">"."\r\n";
        $menu_list .= "\t\t".'<ion-slide-box class="ion-slide-tabs" slide-tabs-scrollable="false" show-pager="false" ion-slide-tabs>'."\r\n";
        foreach($data['menu']['items'] as $menu_item) {
            if($menu_item['type'] == "divider") {
                $is_use_divider = true;
                $menu_list .= "\t\t\t".'<ion-slide ion-slide-tab-label="'.$menu_item['label'].'" >'."\r\n";
                $menu_list .= "\t\t\t\t\t".'<ion-content scroll="false" class="slidingTabContent">'."\r\n";
                $menu_list .= "\t\t\t\t\t\t".'<div class="list">'."\r\n";
                if(isset($_menu_list[$z])) {
                    $menu_list .= $_menu_list[$z];
                }
                $menu_list .= "\t\t\t\t\t\t".'</div>'."\r\n";
                $menu_list .= "\t\t\t\t\t".'</ion-content>'."\r\n";
                $menu_list .= "\t\t\t".'</ion-slide>'."\r\n";
                $z++;
            }
        }
        $menu_list .= "\t\t".'</ion-slide-box>'."\r\n";
        $menu_list .= "\t"."</div>"."\r\n";
        $css_tabs = '';
        $new_page = null;
        $new_page['page'][] = array(
            'title' => 'Menu',
            'prefix' => 'slide_tab_menu',
            'for' => '-',
            'last_edit_by' => 'menu',
            'priority' => 'low',
            'parent' => '',
            'menutype' => $_SESSION['PROJECT']['menu']['type'].'-custom',
            'menu' => '',
            'lock' => false,
            'class' => '',
            'bg_image' => false,
            'version' => 'Upd.'.date('ymdhi'),
            'img_bg' => '',
            'content' => $menu_list,
            'table-code' => array('menu' => $menu_list),
            'title-tranparant' => false,
            'header-shrink' => false,
            'hide-navbar' => false,
            'button_up' => 'none',
            'js' => '$ionicConfig.backButton.text("");',
            'css' => $css_tabs);
        if($is_use_divider == true) {
            $is_lock = false;
            $lock_path = 'projects/'.$file_name.'/page.slide_tab_menu.json';
            if(file_exists($lock_path)) {
                $lock_data = json_decode(file_get_contents($lock_path),true);
                $is_lock = $lock_data['page'][0]['lock'];
            }
            if($is_lock == true) {
                $error_notice[] = 'Page <code>App Menu</code> is <span class="fa fa-lock"></span> locked.';
            } else {
                if(file_exists('projects/'.$file_name.'/page.slide_tab_menu.json')) {
                    @copy('projects/'.$file_name.'/page.slide_tab_menu.json','projects/'.$file_name.'/page.slide_tab_menu.json'.'.'.time().'.save');
                }
                file_put_contents('projects/'.$file_name.'/page.slide_tab_menu.json',json_encode($new_page));
            }
        }
        file_put_contents('projects/'.$file_name.'/menu.json',json_encode($data));
        $form_input .= $bs->Alerts(null,'Application has been updated.','success',true);
        if(!isset($_SESSION['PROJECT']['popover'])) {
            $popover['popover']['icon'] = 'ion-android-more-vertical';
            $popover['popover']['title'] = '';
            $popover['popover']['menu'][0]['title'] = 'About Us';
            $popover['popover']['menu'][0]['type'] = 'link';
            $popover['popover']['menu'][0]['link'] = '#/'.$file_name.'/about_us';

            $popover['popover']['menu'][1]['title'] = 'Language';
            $popover['popover']['menu'][1]['type'] = 'show-language-dialog';
            $popover['popover']['menu'][1]['link'] = '';


            file_put_contents('projects/'.$file_name.'/popover.json',json_encode($popover));
        }
        buildIonic($file_name);
        $_SESSION['PAGE_ERROR'] = $error_notice;
        header('Location: ./?page=menu&notice=save&err=null');
        die();
    } else {
        $form_input .= $bs->Alerts(null,'Menu title is required.','danger',true);
    }
}
$menu_type['tabs'] = array(
    'label' => __('Tabs'),
    'value' => 'tabs',
    );
$menu_type['side_menus'] = array(
    'label' => __('Side Menus'),
    'value' => 'side_menus',
    );
$z = 0;
foreach($menu_type as $_menu_type) {
    $data_menu_type[$z] = $_menu_type;
    if(isset($raw_menu['menu']['type'])) {
        if($raw_menu['menu']['type'] == $_menu_type['value']) {
            $data_menu_type[$z]['active'] = true;
        }
    }
    $z++;
}
$_max_menu = array();
for($i = 0; $i <= 100; $i++) {
    $x = $i;
    $_max_menu[$i] = array('label' => $x,'value' => $x);
    if($max_menu == $x) {
        $_max_menu[$i]['active'] = true;
    }
}
$colors = array(
    'light',
    'stable',
    'positive',
    'positive-900',
    'calm',
    'calm-900',
    'balanced',
    'balanced-900',
    'energized',
    'energized-900',
    'assertive',
    'assertive-900',
    'royal',
    'royal-900',
    'dark',
    );
$menu_styles[] = array("label" => __("none"),"value" => "none");
$menu_styles[] = array("label" => __("Tabs - Striped"),"value" => "tabs-striped");
$menu_styles[] = array("label" => __("Side Menus - Expanded Header"),"value" => "expanded-header");

$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">'.__('General').'</h5>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';
$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-6">';
$form_input .= $bs->FormGroup('max-menu','default','select',__('Max Menu'),$_max_menu,__('Maximum menu what you need'),' onChange="window.location=\'?page=menu&max-menu=\'+this.value;"');
$form_input .= '</div>';
$form_input .= '<div class="col-md-6">';
$form_input .= $bs->FormGroup('menu[type]','default','select',__('Menu Type'),$data_menu_type,__('The type of menu you want to use it'),null);
$form_input .= '</div>';
$form_input .= '</div>';
$form_input .= '</div>';
$form_input .= '</div>';
//create color
$z = 0;
$_colors = $colors;
$_colors[] = 'transparent';
foreach($_colors as $menu_background) {
    $_menu_background[$z] = array('label' => ucwords($menu_background),'value' => $menu_background);
    if($raw_menu['menu']['menu_background'] == $menu_background) {
        $_menu_background[$z]['active'] = true;
    }
    $z++;
}
$z = 0;
foreach($colors as $menu_color) {
    $_menu_color[$z] = array('label' => ucwords($menu_color),'value' => $menu_color);
    if($raw_menu['menu']['menu_color'] == $menu_color) {
        $_menu_color[$z]['active'] = true;
    }
    $z++;
}
$z = 0;
$new_colors = $colors;
$new_colors[] = 'images';
//$new_colors[] ='transparent';
foreach($new_colors as $header_background) {
    $_header_background[$z] = array('label' => ucwords($header_background),'value' => $header_background);
    if($raw_menu['menu']['header_background'] == $header_background) {
        $_header_background[$z]['active'] = true;
    }
    $z++;
}
if(!isset($raw_menu['menu']['menu_style'])) {
    $raw_menu['menu']['menu_style'] = 'none';
}
$z = 0;
foreach($menu_styles as $menu_style) {
    $_menu_style[$z] = array('label' => $menu_style['label'],'value' => $menu_style['value']);
    if($raw_menu['menu']['menu_style'] == $_menu_style[$z]['value']) {
        $_menu_style[$z]['active'] = true;
    }
    $z++;
}
if(!isset($raw_menu['menu']['header_image_background'])) {
    $raw_menu['menu']['header_image_background'] = '';
}
$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">'.__('Styles').'</h5>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';
$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('menu[menu_style]','default','select',__('Menu Styles'),$_menu_style,__('This option menu type'));
$form_input .= '</div>';
if($raw_menu['menu']['type'] == 'tabs') {
    if(!isset($raw_menu['menu']['menu_position'])) {
        $raw_menu['menu']['menu_position'] = 'bottom';
    }
    if($raw_menu['menu']['menu_position'] != 'bottom') {
        $raw_menu['menu']['menu_position'] = 'top';
    }
} else {
    if(!isset($raw_menu['menu']['menu_position'])) {
        $raw_menu['menu']['menu_position'] = 'left';
    }
    if($raw_menu['menu']['menu_position'] != 'left') {
        $raw_menu['menu']['menu_position'] = 'right';
    }
}
// TODO: MENU POSITION
$menu_position[] = array("label" => __("Tabs - Bottom"),"value" => "bottom");
$menu_position[] = array("label" => __("Tabs - Top"),"value" => "top");
$menu_position[] = array("label" => __("Side - Left"),"value" => "left");
$menu_position[] = array("label" => __("Side - Right"),"value" => "right");
$z = 0;
foreach($menu_position as $_menu_pos) {
    $_menu_position[$z] = array('label' => $_menu_pos['label'],'value' => $_menu_pos['value']);
    if($raw_menu['menu']['menu_position'] == $_menu_pos['value']) {
        $_menu_position[$z]['active'] = true;
    }
    $z++;
}
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('menu[menu_position]','default','select',__('Menu Position'),$_menu_position,__('Where your put menu layout?'));
$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('menu[header_background]','default','select',__('Header Background'),$_header_background,__('Color for background header'),'data-type="color"');
$form_input .= '</div>';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('menu[menu_background]','default','select',__('Menu Background'),$_menu_background,__('Color for menu body'),'data-type="color"');
$form_input .= '</div>';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('menu[menu_color]','default','select',__('Menu Color'),$_menu_color,__('Forecolor for font'),'data-type="color"');
$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('menu[header_image_background]','default','text',__('Header Background (Images)'),'',__('file ext *.png (828px x 543px)'),'data-type="image-picker"','8',$raw_menu['menu']['header_image_background']);
$form_input .= '</div>';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('menu[expanded_header]','default','text',__('Expanded Header'),'',__('file ext *.png (828px x 543px)'),'data-type="image-picker"','8',$raw_menu['menu']['expanded_header']);
$form_input .= '</div>';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('menu[logo]','default','text',__('Logo'),'',__('file ext *.png (240px x 240px)'),'data-type="image-picker"','8',$raw_menu['menu']['logo']);
$form_input .= '</div>';
$form_input .= '</div>';


$form_input .= '</div>';
$form_input .= '</div>';


// TODO: MENU TYPE
$items_type[] = array('label' => __('New Page / in-Link'),'value' => 'link');
$items_type[] = array('label' => __('HTML Link / href'),'value' => 'html-link');
$items_type[] = array('label' => __('Divider / Title / Separator (Only for Sidemenu)'),'value' => 'divider');
$items_type[] = array('label' => __('App - Exit (Minimize)'),'value' => 'app-exit');
$items_type[] = array('label' => __('App - Go to Home'),'value' => 'app-home');
$items_type[] = array('label' => __('Embed - Iframe (recommended: a single page)'),'value' => 'iframe');
$items_type[] = array('label' => __('Open - WebView (recommended: a single page)'),'value' => 'webview');
$items_type[] = array('label' => __('Open - App Browser (+Toolbar, recommended: a single page)'),'value' => 'app-browser');
$items_type[] = array('label' => __('Open - External Browser (Android/iOS Browser)'),'value' => 'ext-browser');
$items_type[] = array('label' => __('Open - App Email'),'value' => 'ext-email');
$items_type[] = array('label' => __('Open - App SMS'),'value' => 'ext-sms');
$items_type[] = array('label' => __('Open - App Call'),'value' => 'ext-call');
$items_type[] = array('label' => __('Open - App PlayStore'),'value' => 'ext-playstore');
$items_type[] = array('label' => __('Open - App GEO'),'value' => 'ext-geo');

if(!isset($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'])) {
    $_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] = false;
}
if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true) {
    $items_type[] = array('label' => __('Cordova Plugin Barcode Scanner - Show Alert'),'value' => 'barcodescanner-alert');
    $items_type[] = array('label' => __('Cordova Plugin Barcode Scanner - Open Internal URL'),'value' => 'barcodescanner-link-internal');
    $items_type[] = array('label' => __('Cordova Plugin Barcode Scanner - Open External URL'),'value' => 'barcodescanner-link-external');
    $items_type[] = array('label' => __('Cordova Plugin Barcode Scanner - Open AppBrowser'),'value' => 'barcodescanner-appbrowser');
}

if(!isset($_SESSION['PROJECT']['cordova_plugin']['xsocialsharing']['enable'])) {
    $_SESSION['PROJECT']['cordova_plugin']['xsocialsharing']['enable'] = false;
}
if($_SESSION['PROJECT']['cordova_plugin']['xsocialsharing']['enable'] == true) {
    $items_type[] = array('label' => __('Cordova Plugin Social X-Sharing - Share My App (Playstore)'),'value' => 'xsocialsharing-share-myapp');
}

$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">'.__('Items').'</h5>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';
$form_input .= '<blockquote class="blockquote blockquote-danger">
<h4>'.__('The rules that apply are:').'</h4>
<ul>
<li>'.__('For type <code>`New Page / in-link`</code>, when used same <code>variable name</code> with <code>prefix page</code> will be make in-link and if no page will be create <code>new page</code></li>').'
<li>'.__('<code>Variable names</code> may only use characters <code>a-z and _</code>').'</li>
<li>'.__('To enable the new <code>Item Type</code> eg: <ins>barcode scanner</ins>, You can activate cordova plugin using menu: <code>Extra Menus -&raquo; (IMAB) Cordova Plugin Others</code>').'</li>

</ul>
</blockquote>';

$form_input .= '<div class="table-responsive">';
$form_input .= '<table class="table table-striped sortable">';
$form_input .= '<thead>';
$form_input .= '<tr>';
$form_input .= '<th></th>';
$form_input .= '<th>'.__('Label').' <span style="color:red">*</span></th>';
$form_input .= '<th title="'.__('Variable Menu or Prefix Page').'">'.__('Variables').' <span style="color:red">*</span></th>';
$form_input .= '<th>'.__('Icon A').' <span style="color:red">*</span></th>';
$form_input .= '<th>'.__('Icon B').'</th>';
$form_input .= '<th>'.__('Email/URL/Phone').'</th>';
$form_input .= '<th>'.__('Desc').'/'.__('Message').'</th>';
$form_input .= '<th>'.__('Type').'</th>';
$form_input .= '<th></th>';
$form_input .= '</tr>';
$form_input .= '</thead>';
$form_input .= '<tbody>';
$_js_for_var = null;
for($i = 0; $i < $max_menu; $i++) {
    $z = $i + 1;
    $_raw_menu['menu']['items'][$i]['label'] = '';
    $_raw_menu['menu']['items'][$i]['icon'] = '';
    $_raw_menu['menu']['items'][$i]['icon-alt'] = '';
    $_raw_menu['menu']['items'][$i]['var'] = '';
    $_raw_menu['menu']['items'][$i]['type'] = 'link';
    $_raw_menu['menu']['items'][$i]['option'] = '';
    if(!isset($raw_menu['menu']['items'][$i]['var'])) {
        $raw_menu['menu']['items'][$i]['var'] = 'menu_'.$i;
    }
    if(isset($raw_menu['menu']['items'][$i]['label'])) {
        if(!isset($raw_menu['menu']['items'][$i]['option'])) {
            $raw_menu['menu']['items'][$i]['option'] = '';
        }
        if(!isset($raw_menu['menu']['items'][$i]['desc'])) {
            $raw_menu['menu']['items'][$i]['desc'] = '';
        }
        $_raw_menu['menu']['items'][$i]['label'] = $raw_menu['menu']['items'][$i]['label'];
        $_raw_menu['menu']['items'][$i]['icon'] = $raw_menu['menu']['items'][$i]['icon'];
        $_raw_menu['menu']['items'][$i]['icon-alt'] = $raw_menu['menu']['items'][$i]['icon-alt'];
        $_raw_menu['menu']['items'][$i]['option'] = $raw_menu['menu']['items'][$i]['option'];
        $_raw_menu['menu']['items'][$i]['type'] = $raw_menu['menu']['items'][$i]['type'];
        $_raw_menu['menu']['items'][$i]['var'] = $raw_menu['menu']['items'][$i]['var'];
        $_raw_menu['menu']['items'][$i]['desc'] = $raw_menu['menu']['items'][$i]['desc'];
    }
    $z = 0;
    foreach($items_type as $_item_type) {
        $_items_type[$z] = $_item_type;
        if($_raw_menu['menu']['items'][$i]['type'] == $_item_type['value']) {
            $_items_type[$z]['active'] = true;
        }
        $z++;
    }
    $form_input .= '<tr id="data-'.$i.'">';
    $form_input .= '<td class="v-align">';
    $form_input .= '<span class="glyphicon glyphicon-move"></span>';
    $form_input .= '</td>';
    $form_input .= '<td>';
    $form_input .= $bs->FormGroup('menu[items]['.$i.'][label]','default','text','','Menu '.$i,'<em>'.__('Nice text').'</em>','required '.$direction,'8',$_raw_menu['menu']['items'][$i]['label']);
    $form_input .= '</td>';
    $form_input .= '<td>';
    $form_input .= $bs->FormGroup('menu[items]['.$i.'][var]','default','text','','','<em>'.__('blank = auto, format: <code>a-z and _</code>').'</em>','','8',$_raw_menu['menu']['items'][$i]['var'],'page_var typeahead');
    $form_input .= '</td>';
    $form_input .= '<td>';
    $form_input .= $bs->FormGroup('menu[items]['.$i.'][icon]','default','text','','ion-ios-telephone','<em>'.__('ionicons class').'</em>','data-type="icon-picker" required','8',$_raw_menu['menu']['items'][$i]['icon']);
    $form_input .= '</td>';
    $form_input .= '<td>';
    $form_input .= $bs->FormGroup('menu[items]['.$i.'][icon-alt]','default','text','','ion-ios-telephone','<em>'.__('ionicons class').'</em>','data-type="icon-picker"','8',$_raw_menu['menu']['items'][$i]['icon-alt']);
    $form_input .= '</td>';
    $form_input .= '<td>';
    $form_input .= $bs->FormGroup('menu[items]['.$i.'][option]','default','text','','',''.__('blank = auto'),'data-type="option"','8',$_raw_menu['menu']['items'][$i]['option']);
    $form_input .= '</td>';
    if(!isset($_raw_menu['menu']['items'][$i]['desc'])) {
        $_raw_menu['menu']['items'][$i]['desc'] = '';
    }
    $form_input .= '<td>';
    $form_input .= $bs->FormGroup('menu[items]['.$i.'][desc]','default','text','','','','','8',$_raw_menu['menu']['items'][$i]['desc']);
    $form_input .= '</td>';
    $form_input .= '<td>';
    $form_input .= $bs->FormGroup('menu[items]['.$i.'][type]','default','select','',$_items_type,'','','');
    $form_input .= '</td>';
    $form_input .= '<td>';
    $form_input .= '<a class="remove-item btn btn-danger btn-sm" href="#!_" data-target="#data-'.$i.'" ><i class="glyphicon glyphicon-trash"></i></a>';
    $form_input .= '</td>';
    $form_input .= '</tr>';
    $_js_for_var .= "\r\n";
    $_js_for_var .= "\t".'$("#menu_items__'.$i.'__label_").on("keydown",function(){'."\r\n";
    $_js_for_var .= "\t\t".'var set_link = strToLink($(this).val());'."\r\n";
    $_js_for_var .= "\t\t".'$("#menu_items__'.$i.'__var_").val(set_link);'."\r\n";
    $_js_for_var .= "\t".'});'."\r\n";

    $_js_for_var .= "\t".'$("#menu_items__'.$i.'__label_").on("blur",function(){'."\r\n";
    $_js_for_var .= "\t\t".'var set_link = strToLink($(this).val());'."\r\n";
    $_js_for_var .= "\t\t".'$("#menu_items__'.$i.'__var_").val(set_link);'."\r\n";
    $_js_for_var .= "\t".'});'."\r\n";


}
$form_input .= '</tbody>';
$form_input .= '</table>';
$form_input .= '</div>';
$form_input .= '</div>';
$form_input .= '</div>';
//$form_input .= $bs->FormGroup('fix_default_page', 'default', 'checkbox', '', 'Fix pages `dashboard`,`about_us` and `slide_tab_menu`', null, '', '8');


$form_input .= '<blockquote class="blockquote blockquote-warning"><p>'.__('If you got the menu has not been perfect, please click save menu button again.').'</p></blockquote>';
if($_SESSION['menu-save'] == 2) {
    $text_save = __('Apply Menu');
    $color_save = 'success';
} else {
    $text_save = __('Save Menu');
    $color_save = 'danger';
}
$form_input .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,array(array(
        'name' => 'menu-save',
        'label' => $text_save.' &raquo; (2x click)',
        'tag' => 'submit',
        'color' => $color_save),array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));
$icon = new jsmIonicon();
$modal_dialog = $icon->display();
$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-list fa-stack-1x"></i></span>(IMAB) Menu</h4>';
$content .= notice();

if($_SESSION['menu-save'] == 2) {
    $content .= $bs->Alerts(null,'Please click <strong>Apply Menu</strong> button again for apply the menus!!!','danger',true);
}
$content .= '<blockquote class="blockquote blockquote-danger">
<h4>'.__('The rules that apply are:').'</h4>
<ol>
<li>'.__('The pages `dashboard`,`about_us`, `bookmarks` and `slide_tab_menu` will be created automatically, you can delete it when link broken.').'</li>
<li>'.__('Do not use number in first character label and variable.').'</li>
<li>'.__('If you update the menu, that action will create a new page and delete any changes made on the previous page and to fix it please save all <a target="_blank" href="./?page=tables">(IMAB) Tables</a> that have been made before.').'</li>
<li>'.__('To avoid losing the page after edited, you can locking the page that <i class="fa fa-unlock"></i> or <i class="fa fa-lock"></i> in <a target="_blank" href="./?page=page#page-manager">(IMAB) Page</a>.').'</li>
<li>'.__('After create menu, you can change color scheme using <a target="_blank" href="./?page=x-custom-themes">(IMAB) Custom Themes</a>').'</li>
</ol>
</blockquote>';
$content .= $notice;
$content .= $bs->Forms('app-setup','','post','default',$form_input);
$content .= $bs->Modal('icon-dialog','Ionicon Tables',$modal_dialog,'md',null,'Close',null);
$content .= $bs->Modal('color-dialog','Issues','We feel <kbd>menu background</kbd> and <kbd>menu color</kbd> using the same color is not good for your eyes!!!','md',null,'Close',null);
$footer = null;
if(!isset($_SESSION['PROJECT']['page'])) {
    $_SESSION['PROJECT']['page'] = array();
}
$_current_pages = array();
if(is_array($_SESSION['PROJECT']['page'])) {
    foreach($_SESSION['PROJECT']['page'] as $current_page) {
        if(!isset($current_page['priority'])) {
            $current_page['priority'] = 'danger';
        }
        if(!isset($current_page['prefix'])) {
            $current_page['prefix'] = '';
        }
        $var = $current_page['prefix'];
        $_current_pages[$var] = $current_page['priority'];
    }
}
$footer .= '<script type="text/javascript">var current_pages = '.json_encode($_current_pages);
if(JSM_DEBUG == true) {
    $footer .= ';console.log(current_pages);';
}
$footer .= '
$("#app-setup").on("submit",function(e){
    var list_of_pages = "" ; 
    var complicaties_pages = ["about"];
    $(".page_var").each(function(){
        complicaties_pages.push($(this).val()); 
    }); 
    for(var i=0;i<complicaties_pages.length;i++){
        var page = complicaties_pages[i] ;
        if(current_pages[page]){
            list_of_pages += "\\r\\n\\t- page `" + page + "` = risk " + current_pages[page] ; 
        }
    }
    if(list_of_pages==""){
        return true;
    }
    var notice = "" ; 
    notice += "This action can potentially break pages as follow:\\r\\n" + list_of_pages + "\\r\\n\\r\\n" ; 
    notice += "Are you sure you want to overwrite this page?"  ;
    return confirm(notice);
});
$(document).ready(function(){
    var menu_background = $("#menu_menu_background_").val();
    var menu_color = $("#menu_menu_color_").val();   
    if(menu_background==menu_color){
        $("#color-dialog").modal();
    }  
    $("#menu_menu_background_,#menu_menu_color_").change(function(){
        var menu_background = $("#menu_menu_background_").val();
        var menu_color = $("#menu_menu_color_").val();   
        if(menu_background==menu_color){
            $("#color-dialog").modal();
        }      
    });
});
';
$footer .= '</script>';

$_page = array();
if(isset($_SESSION['PROJECT']['page'])) {
    foreach($_SESSION['PROJECT']['page'] as $page) {
        $_page[] = $page['prefix'];
    }
}
$footer .= '<script type="text/javascript">';
$footer .= 'var typehead_vars = '.json_encode($_page).';';
$footer .= '</script>';


$template->demo_url = $out_path.'/www/';
$template->title = $template->base_title.' | '.'Menu';
$template->base_desc = 'Menu';
$template->content = $content;
$template->footer = $footer;

?>