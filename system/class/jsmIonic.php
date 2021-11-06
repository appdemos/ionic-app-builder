<?php

if(!defined('JSM_EXEC')) {
    die(':)');
}
define('JSM_PURCHASE',false);
define('IONIC_DEBUG',false);
define('IONIC_LOADING',true);
if(file_exists(dirname(__file__)."/LoremIpsum.php")) {
    require_once (dirname(__file__)."/LoremIpsum.php");
} else {
    die("error: class LoremIpsum");
}
if(JSM_OTHER_MARKET == 'true') {
    die("Error activation code");
    exit(0);
}
class Ionic
{
    var $config = array();
    var $appName = 'IMA Mobile';
    var $appDir = 'output/blank';
    var $pageTitle = 'Home';
    var $pagePrefix = 'home';
    var $pageTabs = array();
    var $pageSideMenus = array();
    var $pageHeaderBackground = 'dark';
    var $pageMenuColor = 'dark';
    var $pageMenuColorActive = 'dark';
    var $pageMenuBackground = 'dark';
    var $pageMenuStyle = 'none';
    private $add_css = null;
    private $enter = "\r\n";
    private $tab = "\t";
    private $app_js = null;
    private $controllers_js = null;
    private $services_js = null;
    private $subPages = array();
    private $tables = array();
    private $sample_data = array();
    private $popover = array();
    private $mainMenu = 'side_menus';
    private $gmap = false;
    private $gmap_key = '';
    private $auth = false;
    private $soundtouch = '';
    function __construct($dir,$config)
    {
        $GLOBALS['config'] = $config;
        $this->appName = $config['app']['name'];
        $this->appDir = $dir.'/'.$this->str2var($this->appName);
        if(isset($config['menu'])) {
            $this->pageTitle = $config['menu']['title'];
            $this->pagePrefix = $this->str2var($this->pageTitle);
            if(isset($config['menu']['header_background'])) {
                $this->pageHeaderBackground = $config['menu']['header_background'];
            }
            if(isset($config['menu']['menu_color'])) {
                $this->pageMenuColor = $config['menu']['menu_color'];
            }
            if(!isset($config['app']['name_unicode'])) {
                $config['app']['name_unicode'] = $config['app']['name'];
            }
            if($config['app']['name_unicode'] == '') {
                $config['app']['name_unicode'] = $config['app']['name'];
            }
            if(isset($config['menu']['menu_style'])) {
                $this->pageMenuStyle = $config['menu']['menu_style'];
            }
            if(isset($config['menu']['menu_background'])) {
                $this->pageMenuBackground = $config['menu']['menu_background'];
            }
            switch($config['menu']['type']) {
                case 'tabs':
                    $this->pageTabs = $config['menu']['items'];
                    $this->mainMenu = 'tabs';
                    break;
                case 'side_menus':
                    $this->pageSideMenus = $config['menu']['items'];
                    $this->mainMenu = 'side_menus';
                    break;
            }
            if(isset($config['page'])) {
                if(is_array($config['page'])) {
                    $this->subPages = $config['page'];
                }
            }
            if(isset($config['tables'])) {
                if(is_array($config['tables'])) {
                    $this->tables = $config['tables'];
                }
            }
            if(isset($config['popover'])) {
                if(is_array($config['popover'])) {
                    $this->popover = $config['popover'];
                } else {
                    $this->popover = array();
                }
            }
        }
        $this->config = $config;
        if(isset($this->config['tables'])) {
            foreach($this->config['tables'] as $tables) {
                // TODO: is_used --|-- gmap
                foreach($tables['cols'] as $cols) {
                    if($cols['type'] == 'gmap') {
                        $this->gmap = true;
                        $this->gmap_key = $tables['option']['gmap']['api_key'];
                    }
                }
                // TODO: is_used --|-- auth
                if(isset($tables['auth']['consumer_key'])) {
                    if($tables['auth']['consumer_key'] != '') {
                        $this->auth = true;
                    }
                }
            }
        }
        if(!isset($this->config['app']['direction'])) {
            // TODO: is_used --|-- ltr
            $this->config['app']['direction'] = 'ltr';
        }
    }
    function markup()
    {
        $ionic = $ionic_home = null;
        $status_bar_style = "#000000";
        $status_bar_bgcolor = "#ffffff";
        if(isset($this->config['configxml']['statusbar-style'])) {
            $status_bar_style = $this->config['configxml']['statusbar-style'];
            $status_bar_bgcolor = $this->config['configxml']['statusbar-bgcolor'];
        }
        $ionic .= '<!DOCTYPE html>'.$this->enter;
        $ionic .= '<html>'.$this->enter;
        if($this->config['app']['direction'] == 'ltr') {
            //    $ionic .= '<html>' . $this->enter;
        } else {
            //  $ionic .= '<html dir="rtl">' . $this->enter;
        }
        $ionic .= $this->tab.'<head>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<meta charset="utf-8" />'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width" />'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<link rel="manifest" href="manifest.json">'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<link rel="shortcut icon" href="'.$this->config['menu']['logo'].'">'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<meta name="mobile-web-app-title" content="'.$this->pageTitle.' Lite">'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<meta name="mobile-web-app-capable" content="yes">'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<meta name="theme-color" content="'.$status_bar_style.'">'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<!-- add to homescreen for ios -->'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<link rel="apple-touch-icon" href="'.$this->config['menu']['logo'].'">'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<meta name="apple-mobile-web-app-title" content="'.$this->pageTitle.' Lite">'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<meta name="apple-mobile-web-app-capable" content="yes">'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<!--meta name="apple-mobile-web-app-status-bar-style" content="'.$status_bar_style.'"-->'.$this->enter;
        if(isset($this->config['metatags']['tags'])) {
            $ionic .= $this->tab.$this->tab.$this->config['metatags']['tags'].$this->enter;
        }
        $ionic .= $this->tab.$this->tab.'<title>'.$this->pageTitle.'</title>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<link href="lib/ionic/css/ionic.min.css" rel="stylesheet" />'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<link href="lib/ionic-material/ionic.material.min.css" rel="stylesheet" />'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<link href="lib/ion-md-input/css/ion-md-input.min.css" rel="stylesheet" />'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<link href="lib/ion-datetime-picker/ion-datetime-picker.min.css" rel="stylesheet" />'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<link href="css/style.css" rel="stylesheet" />'.$this->enter;
        // TODO: enqueue --|-- external css
        if(isset($this->config['scripts']['src'])) {
            if(is_array($this->config['scripts']['src'])) {
                foreach($this->config['scripts']['src'] as $script) {
                    if($script['type'] == 'css') {
                        $ionic .= $this->tab.$this->tab.'<link href="'.$script['url'].'" rel="stylesheet" />'.$this->enter;
                    }
                }
            }
        }
        $ionic .= $this->tab.'</head>'.$this->enter;
        $ionic .= $this->tab.'<body ng-app="'.$this->pagePrefix.'" ng-controller="indexCtrl" id="{{ page_id }}">'.$this->enter;
        $ionic_table = null;
        if(isset($this->config['tables'])) {
            foreach($this->config['tables'] as $table) {
                if(isset($table['table_content'])) {
                    $ionic_table .= $table['table_content'];
                }
            }
        }
        if(isset($this->config['menu'])) {
            $ion_tab = null;
            // TODO: fix --|-- tab header
            if($ionic_table == null) {
                $ionic_home .= $this->tab.$this->tab.'<ion-nav-bar class="navbar-title bar-'.$this->pageHeaderBackground.'">'.$this->enter;
                $ionic_home .= $this->tab.$this->tab.$this->tab.'<ion-nav-back-button>'.$this->enter;
                $ionic_home .= $this->tab.$this->tab.$this->tab.'</ion-nav-back-button>'.$this->enter;
                $ionic_home .= $this->tab.$this->tab.'</ion-nav-bar>'.$this->enter;
                $ionic_home .= $ion_tab;
                $ionic_home .= $this->tab.$this->tab.'<ion-nav-view animation="none"></ion-nav-view>'.$this->enter;
                $ionic_home .= $this->tab.$this->tab.''.$this->enter;
            } else {
                $ionic_home .= $ionic_table;
            }
        } else {
            $ionic_home .= $this->tab.$this->tab.'<ion-pane><ion-header-bar class="bar-assertive"><h1 class="title">'.$this->config['app']['name'].'</h1></ion-header-bar><ion-content class="has-header"><ion-refresher></ion-refresher><div class="list"><div class="item item-body">Create a menu and click the save button with 2 clicks.</div></div></ion-content></ion-pane>'.$this->enter;
        }
        $ionic .= $ionic_home;
        // TODO: enqueue --|-- internal js
        if(IONIC_DEBUG == true) {
            $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/angular-chart/Chart.min.js"></script>'.$this->enter;
            $ionic .= $this->tab.$this->tab.'<script src="lib/localforage/localforage.js"></script>'.$this->enter;
            $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/ionic.bundle.js"></script>'.$this->enter;
        } else {
            $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/angular-chart/Chart.min.js"></script>'.$this->enter;
            $ionic .= $this->tab.$this->tab.'<script src="lib/localforage/localforage.min.js"></script>'.$this->enter;
            $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/ionic.bundle.min.js"></script>'.$this->enter;
        }
        if(!isset($this->config['app']['locale'])) {
            $this->config['app']['locale'] = 'en-us';
        }
        $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/angular-dynamic-locale/tmhDynamicLocale.min.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/angular-translate/angular-translate.min.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/angular-utf8-base64/angular-utf8-base64.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/angular-chart/angular-chart.min.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/angular-md5/angular-md5.min.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="lib/ionic-material/ionic.material.min.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="lib/ion-md-input/js/ion-md-input.min.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="lib/ionic-rating/ionic-rating.min.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="lib/ion-slide-tabs/js/slidingTabsDirective.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="lib/ion-datetime-picker/ion-datetime-picker.min.js"></script>'.$this->enter;
        if($this->gmap == true) {
            $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/angular-google-maps/ng-map.min.js"></script>'.$this->enter;
            if($this->gmap_key == '') {
                $ionic .= $this->tab.$this->tab.'<script src="http://maps.google.com/maps/api/js"></script>'.$this->enter;
            } else {
                $ionic .= $this->tab.$this->tab.'<script src="https://maps.googleapis.com/maps/api/js?key='.$this->gmap_key.'&callback"></script>'.$this->enter;
            }
        }
        if($this->config['app']['lazyload'] == true) {
            $ionic .= $this->tab.$this->tab.'<script src="lib/ionic-image-lazy-load/ionic-image-lazy-load.js"></script>'.$this->enter;
        }
        $ionic .= $this->tab.$this->tab.'<script src="lib/ionic/js/angular-cordova/ng-cordova.min.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<!-- cordova script (this will be a 404 during development) -->'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="cordova.js"></script>'.$this->enter;
        // TODO: enqueue --|-- external js
        if(isset($this->config['scripts']['src'])) {
            if(is_array($this->config['scripts']['src'])) {
                foreach($this->config['scripts']['src'] as $script) {
                    if($script['type'] == 'js') {
                        $ionic .= $this->tab.$this->tab.'<script src="'.$script['url'].'"></script>'.$this->enter;
                    }
                }
            }
        }
        $ionic .= $this->tab.$this->tab.'<!-- your app\'s js -->'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="js/app.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="js/controllers.js"></script>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<script src="js/services.js"></script>'.$this->enter;
        if(!isset($this->config['pwa']['service-workers']['enable'])) {
            $this->config['pwa']['service-workers']['enable'] = false;
        }
        if($this->config['pwa']['service-workers']['enable'] == true) {
            $ionic .= $this->tab.$this->tab.'<script>'.$this->enter;
            $ionic .= $this->tab.$this->tab.'if ("serviceWorker" in navigator){'.$this->enter;
            $ionic .= $this->tab.$this->tab.$this->tab.'navigator.serviceWorker.register("service-worker.js")'.$this->enter;
            $ionic .= $this->tab.$this->tab.$this->tab.'.then(() => console.log("service worker installed"))'.$this->enter;
            $ionic .= $this->tab.$this->tab.$this->tab.'.catch(err => console.error("Error", err));'.$this->enter;
            $ionic .= $this->tab.$this->tab.'}'.$this->enter;
            $ionic .= $this->tab.$this->tab.'</script>'.$this->enter;
        }
        $ionic .= $this->tab.'</body>'.$this->enter;
        $ionic .= '</html>'.$this->enter;
        $dependency[] = 'ionic';
        $dependency[] = 'ionMdInput';
        $dependency[] = 'ionic-material';
        $dependency[] = 'ion-datetime-picker';
        $dependency[] = 'ionic.rating';
        $dependency[] = 'utf8-base64';
        $dependency[] = 'angular-md5';
        $dependency[] = 'chart.js';
        $dependency[] = 'pascalprecht.translate';
        $dependency[] = 'tmh.dynamicLocale';
        if($this->config['app']['lazyload'] == true) {
            $dependency[] = 'ionicLazyLoad';
        }
        if($this->gmap == true) {
            $dependency[] = 'ngMap';
        }
        if(isset($this->config['scripts']['dependency'])) {
            $exp_defs = explode(",",$this->config['scripts']['dependency']);
            foreach($exp_defs as $exp_def) {
                if($exp_def != "") {
                    $dependency[] = (htmlentities($exp_def,false));
                }
            }
        }
        if(!isset($this->config['menu']['logo'])) {
            $this->config['menu']['logo'] = '';
        }
        if(!isset($this->config['page'])) {
            $this->config['page'] = array();
        }
        $this->app_js .= 'angular.module("'.$this->pagePrefix.'", ["ngCordova","'.implode('","',$dependency).'","'.$this->pagePrefix.'.controllers", "'.$this->pagePrefix.'.services"])'.$this->enter;
        $this->app_js .= $this->tab.'.run(function($ionicPlatform,$window,$interval,$timeout,$ionicHistory,$ionicPopup,$state,$rootScope){'.$this->enter;
        $this->app_js .= $this->enter;
        $this->app_js .= $this->tab.$this->tab.'$rootScope.appName = "'.htmlentities($this->config['app']['name_unicode']).'" ;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'$rootScope.appLogo = "'.htmlentities($this->config['menu']['logo']).'" ;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'$rootScope.appVersion = "'.htmlentities($this->config['app']['version']).'" ;'.$this->enter;
        $header_shrink = false;
        foreach($this->config['page'] as $__page) {
            if(!isset($__page['header-shrink'])) {
                $__page['header-shrink'] = false;
            }
            if($__page['header-shrink'] == true) {
                $header_shrink = true;
            }
        }
        if($header_shrink == false) {
            $this->app_js .= $this->tab.$this->tab.'$rootScope.headerShrink = false ;'.$this->enter;
        } else {
            $this->app_js .= $this->tab.$this->tab.'$rootScope.headerShrink = true ;'.$this->enter;
        }

        $this->app_js .= $this->enter;
        $this->app_js .= $this->tab.$this->tab.'$rootScope.liveStatus = "pause" ;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'$ionicPlatform.ready(function(){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'$rootScope.liveStatus = "run" ;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'$ionicPlatform.on("pause",function(){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'$rootScope.liveStatus = "pause" ;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'$ionicPlatform.on("resume",function(){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'$rootScope.liveStatus = "run" ;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->app_js .= $this->enter;
        $this->app_js .= $this->enter;
        // TODO: app.js -- |-- init menu
        foreach($this->config['menu']['items'] as $item_menu) {
            $this->app_js .= $this->tab.$this->tab.'$rootScope.hide_menu_'.$this->str2var($item_menu['var']).' = false ;'.$this->enter;
        }
        $this->app_js .= $this->enter;
        $this->app_js .= $this->enter;
        $this->app_js .= $this->tab.$this->tab.'$ionicPlatform.ready(function() {'.$this->enter;
        $this->app_js .= $this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'localforage.config({'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'driver : [localforage.WEBSQL,localforage.INDEXEDDB,localforage.LOCALSTORAGE],'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'name : "'.($this->config['app']['prefix']).'",'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'storeName : "'.($this->config['app']['prefix']).'",'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'description : "The offline datastore for '.htmlentities($this->config['app']['name']).' app"'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->app_js .= $this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'if(window.cordova){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$rootScope.exist_cordova = true ;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$rootScope.exist_cordova = false ;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
        // TODO: app.js -- |-- ionic-plugin-keyboard
        $this->app_js .= $this->tab.$this->tab.$this->tab.'//required: cordova plugin add ionic-plugin-keyboard --save'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'if(window.cordova && window.cordova.plugins.Keyboard) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'cordova.plugins.Keyboard.disableScroll(true);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->app_js .= $this->enter;
        // TODO: app.js -- |-- cordova-plugin-statusbar
        $this->app_js .= $this->tab.$this->tab.$this->tab.'//required: cordova plugin add cordova-plugin-statusbar --save'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'if(window.StatusBar) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'StatusBar.styleDefault();'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
        // TODO: app.js -- |-- cordova-plugin-admob-free
        if(isset($this->config['mod']['admob-free']['data'])) {
            $banner = $this->config['mod']['admob-free']['data'];
            if(isset($banner['banner']['code'])) {
                if(!isset($banner['banner']['on-ready'])) {
                    $banner['banner']['on-ready'] = false;
                }
                if(!isset($banner['interstitial']['on-ready'])) {
                    $banner['interstitial']['on-ready'] = false;
                }
                if(!isset($banner['rewardvideo']['on-ready'])) {
                    $banner['rewardvideo']['on-ready'] = false;
                }
                if(!isset($banner['rewardvideo']['code'])) {
                    $banner['rewardvideo']['code'] = '';
                }
                $this->app_js .= $this->tab.$this->tab.$this->tab.'// this will create a banner on startup'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'//required: cordova plugin add cordova-plugin-admob-free --save'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'if (typeof admob !== "undefined"){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var admobid = {};'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'admobid = {'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'banner: "'.trim($banner['banner']['code']).'",'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'interstitial: "'.trim($banner['interstitial']['code']).'",'.$this->enter;
                //$this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'rewardvideo: "'.$banner['rewardvideo']['code'].'"'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// banner'.$this->enter;

                if($banner['banner']['on-ready'] == true) {
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.banner.config({'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'id: admobid.banner,'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'autoShow: false'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.banner.prepare();'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;


                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$interval(function(){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if($rootScope.liveStatus == "run"){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.banner.show();'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},10000); '.$this->enter;


                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicPlatform.on("pause",function(){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.banner.hide();'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                } else {
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.banner.config({'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'id: admobid.banner,'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'autoShow: false'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.banner.prepare();'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicPlatform.on("pause",function(){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.banner.hide();'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;

                }
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// interstitial'.$this->enter;

                if($banner['interstitial']['on-ready'] == true) {
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.interstitial.config({'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'id: admobid.interstitial,'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'autoShow: false'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.interstitial.prepare();'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;


                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$interval(function(){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if($rootScope.liveStatus == "run"){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.interstitial.show();'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},10000); '.$this->enter;
                } else {
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.interstitial.config({'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'id: admobid.interstitial,'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'autoShow: false'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'admob.interstitial.prepare();'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }


                $this->app_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
            }
        }


        if(isset($this->config['mod']['admob']['data'])) {
            // TODO: app.js -- |-- cordova-plugin-admobpro
            $banner = $this->config['mod']['admob']['data'];
            if(!isset($banner['banner']['code'])) {
                $banner['banner']['code'] = '';
            }
            if(!isset($banner['interstitial']['code'])) {
                $banner['interstitial']['code'] = '';
            }
            if(!isset($banner['rewardvideo']['code'])) {
                $banner['rewardvideo']['code'] = '';
            }
            if(!isset($banner['banner']['on-ready'])) {
                $banner['banner']['on-ready'] = false;
            }
            if(!isset($banner['interstitial']['on-ready'])) {
                $banner['interstitial']['on-ready'] = false;
            }
            if(!isset($banner['rewardvideo']['on-ready'])) {
                $banner['rewardvideo']['on-ready'] = false;
            }
            $this->app_js .= $this->tab.$this->tab.$this->tab.'// this will create a banner on startup'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.'//required: cordova plugin add cordova-plugin-admobpro --save'.$this->enter;
            if(isset($this->config['mod']['admob']['position'])) {
                $position = $this->config['mod']['admob']['position'];
            } else {
                $position = 'BOTTOM_CENTER';
            }
            $this->app_js .= $this->tab.$this->tab.$this->tab.'if (typeof AdMob !== "undefined"){'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var admobid = {};'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'admobid = {'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'banner: "'.trim($banner['banner']['code']).'",'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'interstitial: "'.trim($banner['interstitial']['code']).'",'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'rewardvideo: "'.trim($banner['rewardvideo']['code']).'"'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;


            if($banner['banner']['on-ready'] == true) {
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.''.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// ADS BANNER'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'AdMob.createBanner({'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'adId: admobid.banner,'.$this->enter;
                if($this->config['mod']['admob']['test'] == 'true') {
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'isTesting: true,// TO'.'DO: remove this line when release'.$this->enter;
                }
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'overlap: false,'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'autoShow: false,'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'offsetTopBar: false,'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'position: AdMob.AD_POSITION.'.$position.','.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'bgColor: "white"'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//navigator.notification.activityStart(err.message, "Admob");'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;


                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$interval(function(){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if($rootScope.liveStatus == "run"){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'AdMob.showBanner(AdMob.AD_POSITION.'.$position.');'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//navigator.notification.activityStart(err.message, "Admob");'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},10000); '.$this->enter;


                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicPlatform.on("pause",function(){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'AdMob.hideBanner();'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//navigator.notification.activityStart(err.message, "Admob");'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;


            } else {
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.''.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// BANNER'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;

                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'AdMob.createBanner({'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'adId: admobid.banner,'.$this->enter;
                if($this->config['mod']['admob']['test'] == 'true') {
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'isTesting: true,// TO'.'DO: remove this line when release'.$this->enter;
                }
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'overlap: false,'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'autoShow: false,'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'offsetTopBar: false,'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'position: AdMob.AD_POSITION.'.$position.','.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'bgColor: "black"'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}, 500);'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicPlatform.on("pause",function(){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'AdMob.hideBanner();'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
            }

            if($banner['interstitial']['on-ready'] == true) {
                $this->app_js .= $this->tab.$this->tab.$this->tab.''.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// INTERSTITIAL'.$this->enter;

                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'AdMob.prepareInterstitial({'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'adId: admobid.interstitial,'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'autoShow: false,'.$this->enter;
                if($this->config['mod']['admob']['test'] == 'true') {
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'isTesting: true,// TO'.'DO: remove this line when release'.$this->enter;
                }
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.''.$this->enter;


                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$interval(function(){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if($rootScope.liveStatus == "run"){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'AdMob.showInterstitial();'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},10000); '.$this->enter;

            } else {
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.''.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// interstitial'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'AdMob.prepareInterstitial({'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'adId: admobid.interstitial,'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'autoShow: false,'.$this->enter;
                if($this->config['mod']['admob']['test'] == 'true') {
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'isTesting: true,// TO'.'DO: remove this line when release'.$this->enter;
                }
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.''.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}, 500);'.$this->enter;
            }

            if($banner['rewardvideo']['on-ready'] == true) {
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.''.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// REWARD'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'AdMob.prepareRewardVideoAd({'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'adId: admobid.rewardvideo,'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'autoShow: false,'.$this->enter;
                if($this->config['mod']['admob']['test'] == 'true') {
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'isTesting: true,// TO'.'DO: remove this line when release'.$this->enter;
                }
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;

                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$interval(function(){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if($rootScope.liveStatus == "run"){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'AdMob.showRewardVideoAd();'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},30000); '.$this->enter;

            } else {
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.''.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// rewardvideo'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'AdMob.prepareRewardVideoAd({'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'adId: admobid.rewardvideo,'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'autoShow: false,'.$this->enter;
                if($this->config['mod']['admob']['test'] == 'true') {
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'isTesting: true,// TO'.'DO: remove this line when release'.$this->enter;
                }
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}, 500);'.$this->enter;
            }

            $this->app_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
        }
        $this->app_js .= $this->enter;
        if(!isset($this->config['app']['network'])) {
            $this->config['app']['network'] = false;
        }
        if($this->config['app']['network'] == true) {
            // TODO: app.js -- |-- cordova-plugin-network-information
            $this->app_js .= $this->tab.$this->tab.$this->tab.'//required: cordova plugin add cordova-plugin-network-information --save'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.'$interval(function(){'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if ( typeof navigator == "object" && typeof navigator.connection != "undefined"){'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var networkState = navigator.connection.type;'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$rootScope.is_online = true ;'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if (networkState == "none") {'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$rootScope.is_online = false ;'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$window.location = "retry.html";'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.'}, 5000);'.$this->enter;
        }
        if(isset($this->config['push']['plugin'])) {
            switch($this->config['push']['plugin']) {
                case 'cordova-plugin-fcm':
                    $this->app_js .= $this->enter;
                    // TODO: app.js -- |-- cordova-plugin-fcm
                    $this->app_js .= $this->tab.$this->tab.$this->tab.'//required: cordova plugin add cordova-plugin-fcm --save'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.'if(window.cordova && FCMPlugin){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'FCMPlugin.getToken(function (token) {'.$this->enter;
                    if(IONIC_DEBUG == true) {
                        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'console.log(token);'.$this->enter;
                    }
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}, function (err) {'.$this->enter;
                    if(IONIC_DEBUG == true) {
                        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'console.log(\'error retrieving token: \' + err);'.$this->enter;
                    }
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'FCMPlugin.onNotification(function (data){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if (data.wasTapped) {'.$this->enter;
                    if(IONIC_DEBUG == true) {
                        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'alert(JSON.stringify(data));'.$this->enter;
                    }
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}else {'.$this->enter;
                    if(IONIC_DEBUG == true) {
                        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'alert(JSON.stringify(data));'.$this->enter;
                    }
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}, function (msg) {'.$this->enter;
                    if(IONIC_DEBUG == true) {
                        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'console.log(\'onNotification callback successfully registered: \' + msg);'.$this->enter;
                    }
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}, function (err) {'.$this->enter;
                    if(IONIC_DEBUG == true) {
                        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'console.log(\'Error registering onNotification callback: \' + err);'.$this->enter;
                    }
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.'}    '.$this->enter;
                    $this->app_js .= $this->enter;
                    break;
                case 'onesignal-cordova-plugin':
                    // TODO: app.js -- |-- onesignal-cordova-plugin
                    $this->app_js .= $this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.'//required: cordova plugin add onesignal-cordova-plugin --save'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.'if(window.plugins && window.plugins.OneSignal){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.plugins.OneSignal.enableNotificationsWhenActive(true);'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var notificationOpenedCallback = function(jsonData){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try {'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$window.location = "#/'.$this->config['app']['prefix'].'/" + jsonData.notification.payload.additionalData.page ;'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},200);'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'} catch(e){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'console.log("onesignal:" + e);'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    if(IONIC_DEBUG == true) {
                        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'alert("notification: " + JSON.stringify(jsonData));'.$this->enter;
                    }
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.plugins.OneSignal.startInit("'.htmlentities($this->config['push']['app_id']).'").handleNotificationOpened(notificationOpenedCallback).endInit();'.$this->enter;
                    //$this->app_js .= $this->tab . $this->tab . $this->tab . $this->tab . 'window.plugins.OneSignal.setSubscription(true);' . $this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->app_js .= $this->enter;
                    break;
                case 'phonegap-plugin-push':
                    break;
            }
        }
        $this->app_js .= $this->enter;
        $this->app_js .= $this->tab.$this->tab.'});'.$this->enter;
        if(!isset($this->config['app']['no-history-back'])) {
            $this->config['app']['no-history-back'] = 'none';
        }
        switch($this->config['app']['no-history-back']) {
            case 'goto-home':
                // TODO: app_js --|-- back-button
                $this->app_js .= $this->tab.$this->tab.'$ionicPlatform.registerBackButtonAction(function (e){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'if($ionicHistory.backView()){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.goBack();'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$state.go("'.$this->config['app']['prefix'].'.'.$this->config['app']['index'].'");'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'e.preventDefault();'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'return false;'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.'},101);'.$this->enter;
                break;
            case 'app-exit':
                // TODO: app_js --|-- back-button
                $this->app_js .= $this->tab.$this->tab.'$ionicPlatform.registerBackButtonAction(function (e){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'if($ionicHistory.backView()){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.goBack();'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var confirmPopup = $ionicPopup.confirm({'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'title: "Confirm Exit",'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: "Are you sure you want to exit?"'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'confirmPopup.then(function (close){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if(close){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'ionic.Platform.exitApp();'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'e.preventDefault();'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.$this->tab.'return false;'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.'},101);'.$this->enter;
                break;
        }
        $this->app_js .= $this->tab.'})'.$this->enter;
        $this->app_js .= $this->enter;
        $this->app_js .= $this->enter;
        // TODO: app_js --|-- filter
        // TODO: app_js --|------ to_trusted
        $this->app_js .= $this->tab.'.filter("to_trusted", ["$sce", function($sce){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function(text) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return $sce.trustAsHtml(text);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'};'.$this->enter;
        $this->app_js .= $this->tab.'}])'.$this->enter;
        $this->app_js .= $this->enter;
        // TODO: app_js --|------ trustUrl
        $this->app_js .= $this->tab.'.filter("trustUrl", function($sce) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function(url) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return $sce.trustAsResourceUrl(url);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'};'.$this->enter;
        $this->app_js .= $this->tab.'})'.$this->enter;
        $this->app_js .= $this->enter;
        // TODO: app_js --|------ trustJs
        $this->app_js .= $this->tab.'.filter("trustJs", ["$sce", function($sce){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function(text) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return $sce.trustAsJs(text);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'};'.$this->enter;
        $this->app_js .= $this->tab.'}])'.$this->enter;
        $this->app_js .= $this->enter;
        // TODO: app_js --|------ strExplode
        $this->app_js .= $this->tab.'.filter("strExplode", function() {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function($string,$delimiter) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'if(!$string.length ) return;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'var $_delimiter = $delimiter || "|";'.$this->enter;
        if(IONIC_DEBUG == true) {
            $this->app_js .= $this->tab.$this->tab.$this->tab.'console.log($string.split($_delimiter));'.$this->enter;
        }
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return $string.split($_delimiter);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'};'.$this->enter;
        $this->app_js .= $this->tab.'})'.$this->enter;
        $this->app_js .= $this->enter;
        // TODO: app_js --|------ strDate
        $this->app_js .= $this->tab.'.filter("strDate", function(){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function (input) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return new Date(input);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->app_js .= $this->tab.'})'.$this->enter;
        // TODO: app_js --|------ phpTime
        $this->app_js .= $this->tab.'.filter("phpTime", function(){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function (input) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'var timeStamp = parseInt(input) * 1000;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return timeStamp ;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->app_js .= $this->tab.'})'.$this->enter;
        // TODO: app_js --|------ strHTML
        $this->app_js .= $this->tab.'.filter("strHTML", ["$sce", function($sce){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function(text) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return $sce.trustAsHtml(text);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'};'.$this->enter;
        $this->app_js .= $this->tab.'}])'.$this->enter;
        // TODO: app_js --|------ strEscape
        $this->app_js .= $this->tab.'.filter("strEscape",function(){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return window.encodeURIComponent;'.$this->enter;
        $this->app_js .= $this->tab.'})'.$this->enter;
        // TODO: app_js --|------ strUnscape
        $this->app_js .= $this->tab.'.filter("strUnscape", ["$sce", function($sce) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'var div = document.createElement("div");'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function(text) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'div.innerHTML = text;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return $sce.trustAsHtml(div.textContent);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'};'.$this->enter;
        $this->app_js .= $this->tab.'}])'.$this->enter;
        $this->app_js .= $this->enter;
        // TODO: app_js --|------ stripTags
        $this->app_js .= $this->tab.'.filter("stripTags", ["$sce", function($sce){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function(text) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return text.replace(/(<([^>]+)>)/ig,"");'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'};'.$this->enter;
        $this->app_js .= $this->tab.'}])'.$this->enter;
        $this->app_js .= $this->enter;
        // TODO: app_js --|------ chartData
        $this->app_js .= $this->tab.'.filter("chartData", function(){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function (obj) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'var new_items = [];'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'angular.forEach(obj, function(child) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var new_item = [];'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var indeks = 0;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'angular.forEach(child, function(v){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if ((indeks !== 0) && (indeks !== 1)){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'new_item.push(v);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'indeks++;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'new_items.push(new_item);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return new_items;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->app_js .= $this->tab.'})'.$this->enter;
        $this->app_js .= $this->enter;
        // TODO: app_js --|------ chartLabels
        $this->app_js .= $this->tab.'.filter("chartLabels", function(){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function (obj){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'var new_item = [];'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'angular.forEach(obj, function(child) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'var indeks = 0;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'new_item = [];'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'angular.forEach(child, function(v,l) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if ((indeks !== 0) && (indeks !== 1)) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'new_item.push(l);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'indeks++;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return new_item;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->app_js .= $this->tab.'})'.$this->enter;
        // TODO: app_js --|------ chartSeries
        $this->app_js .= $this->tab.'.filter("chartSeries", function(){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'return function (obj) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'var new_items = [];'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'angular.forEach(obj, function(child) {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var new_item = [];'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var indeks = 0;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'angular.forEach(child, function(v){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if (indeks === 1){'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'new_item.push(v);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'indeks++;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'new_items.push(new_item);'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'return new_items;'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->app_js .= $this->tab.'})'.$this->enter;
        $this->app_js .= $this->enter;
        $this->app_js .= $this->enter;
        $this->app_js .= $this->enter;
        $lang_prefix = 'en-us';
        if(!isset($this->config['app']['locale'])) {
            $this->config['app']['locale'] = 'en-us';
        }
        if($this->config['app']['locale'] != '') {
            $lang_prefix = $this->config['app']['locale'];
        }
        $this->app_js .= '.config(["$translateProvider", function ($translateProvider){'.$this->enter;
        $this->app_js .= $this->tab.'$translateProvider.preferredLanguage("'.$lang_prefix.'");'.$this->enter;
        $this->app_js .= $this->tab.'$translateProvider.useStaticFilesLoader({'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'prefix: "translations/",'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'suffix: ".json"'.$this->enter;
        $this->app_js .= $this->tab.'});'.$this->enter;
        $this->app_js .= $this->tab.'$translateProvider.useSanitizeValueStrategy("escapeParameters");'.$this->enter;
        $this->app_js .= '}])'.$this->enter;
        $this->app_js .= $this->enter;
        $this->app_js .= $this->enter;
        $this->app_js .= '.config(function(tmhDynamicLocaleProvider){'.$this->enter;
        $this->app_js .= $this->tab.'tmhDynamicLocaleProvider.localeLocationPattern("lib/ionic/js/i18n/angular-locale_{{locale}}.js");'.$this->enter;
        $this->app_js .= $this->tab.'tmhDynamicLocaleProvider.defaultLocale("'.$lang_prefix.'");'.$this->enter;
        $this->app_js .= '})'.$this->enter;
        $this->app_js .= $this->enter;
        $this->app_js .= $this->enter;
        $this->controllers_js .= 'angular.module("'.$this->pagePrefix.'.controllers", [])'.$this->enter;
        $this->controllers_js .= $this->enter;
        $this->controllers_js .= $this->enter;
        $this->services_js .= 'angular.module("'.$this->pagePrefix.'.services", [])'.$this->enter;
        $this->services_js .= '// TO'.'DO: --|---- directive'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        // TODO: services_js ------|-- directive
        if($this->config['app']['soundtouch'] == true) {
            // TODO: services_js ------|------ sound-touch
            $this->services_js .= '// TO'.'DO: --|-------- sound-touch'.$this->enter;
            $this->services_js .= '.directive("soundTouch", function(){'.$this->enter;
            $this->services_js .= $this->tab."/** required: cordova-plugin-velda-devicefeedback **/".$this->enter;
            $this->services_js .= $this->tab.'return {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("touchend", onTouchEnd);'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'function onTouchEnd(event)'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (window.plugins && window.plugins.deviceFeedback){'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.plugins.deviceFeedback.acoustic();'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
            $this->services_js .= $this->tab.'};'.$this->enter;
            $this->services_js .= '})'.$this->enter;
            $this->soundtouch = ' sound-touch="true" ';
        }
        $this->services_js .= $this->tab.$this->enter;
        // TODO: services_js ------|------ zoomTap
        $this->services_js .= '// TO'.'DO: --|-------- zoomTap'.$this->enter;
        $this->services_js .= '.directive("zoomTap", function($compile, $ionicGesture) {'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'link: function($scope, $element, $attrs) {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'var zoom = minZoom = 10;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'var maxZoom = 50;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.attr("style", "width:" + (zoom * 10) + "%");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'var handlePinch = function(e){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (e.gesture.scale <= 1) {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoom--;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoom++;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (zoom >= maxZoom) {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoom = maxZoom;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (zoom <= minZoom) {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoom = minZoom;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        // $this->services_js .= $this->tab . $this->tab . $this->tab . $this->tab . 'console.log(e.gesture.scale);' . $this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$element.attr("style", "width:" + (zoom * 10) + "%");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'var handleDoubleTap = function(e){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'zoom++;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (zoom == maxZoom) {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoom = minZoom;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$element.attr("style", "width:" + (zoom * 10) + "%");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'var pinchGesture = $ionicGesture.on("pinch", handlePinch, $element);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'var doubletapGesture = $ionicGesture.on("doubletap", handleDoubleTap, $element);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$scope.$on("$destroy", function() {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicGesture.off(pinchGesture, "pinch", $element);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicGesture.off(doubletapGesture, "doubletap", $element);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ zoomView
        $this->services_js .= '// TO'.'DO: --|-------- zoom-view'.$this->enter;
        $this->services_js .= '.directive("zoomView", function($compile,$ionicModal, $ionicPlatform){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'link: function link($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if(typeof $scope.zoomImages == "undefined"){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.zoomImages=0;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if(typeof $scope.imagesZoomSrc == "undefined"){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.imagesZoomSrc = {};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.zoomImages++;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var indeks = $scope.zoomImages;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.imagesZoomSrc[indeks] = $attrs.zoomSrc;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$element.attr("ng-click", "showZoomView(" + indeks + ")");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$element.removeAttr("zoom-view");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$compile($element)($scope);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicPlatform.ready(function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var zoomViewTemplate = "";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoomViewTemplate += "<ion-modal-view class=\"zoom-view\">";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoomViewTemplate += "<ion-header-bar class=\"bar bar-header light bar-balanced-900\">";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoomViewTemplate += "<div class=\"header-item title\"></div>";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoomViewTemplate += "<div class=\"buttons buttons-right header-item\"><span class=\"right-buttons\"><button ng-click=\"closeZoomView()\" class=\"button button-icon ion-close button-clear button-dark\"></button></span></div>";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoomViewTemplate += "</ion-header-bar>";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoomViewTemplate += "<ion-content overflow-scroll=\"true\">";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoomViewTemplate += "<ion-scroll zooming=\"true\" overflow-scroll=\"false\" direction=\"xy\" style=\"width:100%;height:100%;position:absolute;top:0;bottom:0;left:0;right:0;\">";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoomViewTemplate += "<img ng-src=\"{{ zoom_src }}\" style=\"width:100%!important;display:block;width:100%;height:auto;max-width:400px;max-height:700px;margin:auto;padding:10px;\"/>";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoomViewTemplate += "</ion-scroll>";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoomViewTemplate += "</ion-content>";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'zoomViewTemplate += "</ion-modal-view>";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.zoomViewModal = $ionicModal.fromTemplate(zoomViewTemplate,{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'scope: $scope,'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'animation: "slide-in-up"'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.showZoomView = function(indeks){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.zoom_src = $scope.imagesZoomSrc[indeks] || $attrs.zoomSrc ;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'console.log(indeks,$scope.zoom_src,$scope.imagesZoomSrc);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.zoomViewModal.show();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.closeZoomView= function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.zoomViewModal.hide();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->enter;
        // TODO: services_js ------|------ header-shrink
        $this->services_js .= '// TO'.'DO: --|-------- headerShrink'.$this->enter;
        $this->services_js .= '.directive("headerShrink", function($document){'.$this->enter;
        $this->services_js .= $this->tab.'var fadeAmt;'.$this->enter;
        $this->services_js .= $this->tab.'var shrink = function(header, content, amt, max){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'amt = Math.min(44, amt);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'fadeAmt = 1 - amt / 44;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'ionic.requestAnimationFrame(function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'var translate3d = "translate3d(0, -" + amt + "px, 0)";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'if(header==null){return;}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'for (var i = 0, j = header.children.length; i < j; i++){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'header.children[i].style.opacity = fadeAmt;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'header.children[i].style[ionic.CSS.TRANSFORM] = translate3d;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'link: function($scope, $element, $attr){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'var starty = $scope.$eval($attr.headerShrink) || 0;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'var shrinkAmt;'.$this->enter;
        if($this->mainMenu == 'tabs') {
            $this->services_js .= $this->tab.$this->tab.$this->tab.'var header = $document[0].body.querySelector(".navbar-title");'.$this->enter;
        } else {
            $this->services_js .= $this->tab.$this->tab.$this->tab.'var header = $document[0].body.querySelector(".page-title");'.$this->enter;
        }
        $this->services_js .= $this->tab.$this->tab.$this->tab.'var headerHeight = $attr.offsetHeight || 44;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("scroll", function(e){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var scrollTop = null;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (e.detail){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'scrollTop = e.detail.scrollTop;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'} else if (e.target){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'scrollTop = e.target.scrollTop;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (scrollTop > starty){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'shrinkAmt = headerHeight - Math.max(0, (starty + headerHeight) - scrollTop);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'shrink(header, $element[0], shrinkAmt, headerHeight);'.$this->enter;
        //$this->services_js .= $this->tab . $this->tab . $this->tab . $this->tab .$this->tab . 'console.log("up",scrollTop);' . $this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'shrink(header, $element[0], 0, headerHeight);'.$this->enter;
        //$this->services_js .= $this->tab . $this->tab . $this->tab . $this->tab .$this->tab . 'console.log("down",scrollTop);' . $this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$scope.$parent.$on("$ionicView.leave", function (){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'shrink(header, $element[0], 0, headerHeight);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$scope.$parent.$on("$ionicView.enter", function (){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'shrink(header, $element[0], 0, headerHeight);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'}'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ fileread
        $this->services_js .= '// TO'.'DO: --|-------- fileread'.$this->enter;
        $this->services_js .= '.directive("fileread",function($ionicLoading,$timeout){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'scope:{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'fileread: "="'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'},'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'link: function(scope, element,attributes){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'element.bind("change", function(changeEvent) {'.$this->enter;
        if(IONIC_LOADING == true) {
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.show();'.$this->enter;
        } else {
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.show({'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\''.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        }
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'scope.fileread = "";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var reader = new FileReader();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'reader.onload = function(loadEvent) {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'scope.$apply(function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'scope.fileread = loadEvent.target.result;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'reader.onloadend = function(loadEvent) {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'scope.fileread = loadEvent.target.result;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},300);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}catch(err){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if(changeEvent.target.files[0]){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'reader.readAsDataURL(changeEvent.target.files[0]);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},300)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'}'.$this->enter;
        $this->services_js .= '}) '.$this->enter;
        // TODO: services_js ------|------ runAppSms
        $this->services_js .= '// TO'.'DO: --|-------- run-app-sms'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runAppSms", function(){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runApp);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function runApp(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var phoneNumber = $attrs.phone || "08123456789";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var textMessage = window.encodeURIComponent($attrs.message) || "Hello";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (ionic.Platform.isIOS()){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "sms:" + phoneNumber + ";?&body=" + textMessage;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "sms:" + phoneNumber + "?body=" + textMessage;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema,"_system","location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ runAppCall
        $this->services_js .= '// TO'.'DO: --|-------- run-app-call'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runAppCall", function(){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runApp);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function runApp(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var phoneNumber = $attrs.phone || "08123456789";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "tel:" + phoneNumber ;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema,"_system","location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ runAppGeo
        $this->services_js .= '// TO'.'DO: --|-------- run-app-geo'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runAppGeo", function(){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runApp);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function runApp(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var loc = $attrs.loc || "23,12312";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (ionic.Platform.isIOS()){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "maps://?q=" + loc ;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "geo:" + loc ;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema,"_system","location=yes");'.$this->enter;

        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ runAppEmail
        $this->services_js .= '// TO'.'DO: --|-------- run-app-email'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runAppEmail", function(){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runApp);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function runApp(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var EmailAddr = $attrs.email || "info@ihsana.com";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var textSubject = $attrs.subject || "";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var textMessage = window.encodeURIComponent($attrs.message) || "";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "mailto:" + EmailAddr + "?subject=" + textSubject + "&body=" + textMessage;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema,"_system","location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ runAppFacebook
        $this->services_js .= '// TO'.'DO: --|-------- run-app-facebook'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runAppFacebook", function(){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runApp);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function runApp(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var textLink = window.encodeURIComponent($attrs.link) || "http://ihsana.com/";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (ionic.Platform.isIOS()){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = \'fbapi20130214://dialog/share?app_id=966242223397117&version=20130410&method_args={"name":null,"description":null,"link":"\' + textLink + \'"}\' ;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}else if(ionic.Platform.isAndroid()){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "fb://faceweb/f?href=https://facebook.com/sharer/sharer.php?u=" + textLink;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "https://facebook.com/sharer/sharer.php?u=" + textLink;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema,"_system","location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ runAppWhatsapp
        $this->services_js .= '// TO'.'DO: --|-------- run-app-whatsapp'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runAppWhatsapp", function(){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runApp);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function runApp(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var textMessage = window.encodeURIComponent($attrs.message) || "";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "whatsapp://send?text=" + textMessage;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema,"_system","location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ runAppLine
        $this->services_js .= '// TO'.'DO: --|-------- run-app-line'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runAppLine", function(){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runApp);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function runApp(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var textMessage = window.encodeURIComponent($attrs.message) || "";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "line://msg/text/" + textMessage;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema,"_system","location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ runAppTwitter
        $this->services_js .= '// TO'.'DO: --|-------- run-app-twitter'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runAppTwitter", function(){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runApp);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function runApp(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var textMessage = window.encodeURIComponent($attrs.message) || "";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "twitter://post?message=" + textMessage;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema,"_system","location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ runOpenURL
        $this->services_js .= '// TO'.'DO: --|-------- run-open-url'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runOpenUrl", function(){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope,$element,$attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runOpenURL);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function runOpenURL(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var $href = $attrs.href || "http://ihsana.com/";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.open($href,"_system","location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ runAppBrowser
        $this->services_js .= '// TO'.'DO: --|-------- run-app-browser'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runAppBrowser", function(){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runApp);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function runApp(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var $href = $attrs.href || "http://ihsana.com/";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var appBrowser = window.open($href,"_blank","hardwareback=Done,toolbarposition=top,location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'appBrowser.addEventListener("loadstart",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStart("Please Wait", "Its loading....");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'appBrowser.addEventListener("loadstop",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'appBrowser.addEventListener("loaderror",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.location = "retry.html";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'appBrowser.addEventListener("exit",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ runWebview
        $this->services_js .= '// TO'.'DO: --|-------- run-webview'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runWebview", function(){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runApp);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function runApp(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var $href = $attrs.href || "http://ihsana.com/";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var appWebview = window.open($href,"_blank","location=no,toolbar=no");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'appWebview.addEventListener("loadstart",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStart("Please Wait", "Its loading....");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'appWebview.addEventListener("loadstop",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'appWebview.addEventListener("loaderror",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.location = "retry.html";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'appWebview.addEventListener("exit",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        // TODO: services_js ------|------ runSocialSharing
        $this->services_js .= '// TO'.'DO: --|-------- run-social-sharing'.$this->enter;
        $this->services_js .= "/** required: cordova-plugin-whitelist, cordova-plugin-inappbrowser **/".$this->enter;
        $this->services_js .= '.directive("runSocialSharing", function($ionicActionSheet, $timeout){'.$this->enter;
        $this->services_js .= $this->tab.'return {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'controller: function($scope, $element, $attrs){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", showSocialSharing);'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'function showSocialSharing(event)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var hideSheet = $ionicActionSheet.show('.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'titleText: \'Share This\','.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'buttons: ['.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'{ text: \'<i class="icon ion-social-facebook"></i> <b>Facebook</b>\'},'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'{ text: \'<i class="icon ion-social-twitter"></i> <b>Twitter</b>\'},'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'{ text: \'<i class="icon ion-social-whatsapp"></i> <b>Whatsapp</b>\'},'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'{ text: \'<i class="icon ion-ios-chatbubble"></i> <b>Line</b>\'},'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'],'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'cancelText: \'Cancel\','.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'cancel: function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// add cancel code.'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'buttonClicked: function(index)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'switch (index)'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'case 0:'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var textMessage = window.encodeURIComponent($attrs.message) || "";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if (ionic.Platform.isIOS()){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = \'fbapi20130214://dialog/share?app_id=966242223397117&version=20130410&method_args={"name":null,"description":null,"link":"\' + textMessage + \'"}\' ;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}else if(ionic.Platform.isAndroid()){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "fb://faceweb/f?href=https://facebook.com/sharer/sharer.php?u=" + textMessage;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "https://facebook.com/sharer/sharer.php?u=" + textMessage;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema, "_system", "location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'break;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'case 1:'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var textMessage = window.encodeURIComponent($attrs.message) || "";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "twitter://post?message=" + textMessage;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema, "_system", "location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'break;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'case 2:'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var textMessage = window.encodeURIComponent($attrs.message) || "";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "whatsapp://send?text=" + textMessage;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema, "_system", "location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'break;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'case 3:'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var textMessage = window.encodeURIComponent($attrs.message) || "";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var urlSchema = "line://msg/text/" + textMessage;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.open(urlSchema, "_system", "location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'break;'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function()'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'{'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'hideSheet();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}, 5000); '.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->services_js .= $this->tab.'};'.$this->enter;
        $this->services_js .= '})'.$this->enter;
        $this->services_js .= $this->enter;
        // TODO: services_js ------|------ barcodeScanner
        if(!isset($this->config['cordova_plugin']['barcodescanner']['enable'])) {
            $this->config['cordova_plugin']['barcodescanner']['enable'] = false;
        }
        if($this->config['cordova_plugin']['barcodescanner']['enable'] == true) {
            $this->services_js .= '// TO'.'DO: --|-------- barcode-scanner'.$this->enter;
            $this->services_js .= "/** required:  cordova-plugin-barcodescanner **/".$this->enter;
            $this->services_js .= '.directive("barcodeScanner", function($compile, $ionicModal, $ionicPlatform) {'.$this->enter;
            $this->services_js .= $this->tab.'return {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.'scope: {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'barcodeText: "="'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.'},'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.'link: function link($scope, $element, $attrs) {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'$scope.barcodeText = "";'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", runScanner);'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'function runScanner(event) {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.barcodeText = "";'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (window.cordova && window.cordova.plugins.barcodeScanner) {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'cordova.plugins.barcodeScanner.scan(function(result) {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.$apply(function() {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.barcodeText = result.text;'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},function(error) {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.form_contact.Barcode = "Scanning failed: " + error;'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},{'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'preferFrontCamera: false,'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'showFlipCameraButton: true,'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'showTorchButton: true,'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'torchOn: false,'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'prompt: "Place a barcode inside the scan area, then shake your phone",'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'resultDisplayDuration: 500,'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//orientation: "landscape",'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'disableAnimations: false,'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'disableSuccessBeep: false'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.$apply(function() {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.barcodeText = "Only work in real device!";'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
            $this->services_js .= $this->tab.'};'.$this->enter;
            $this->services_js .= '})'.$this->enter;
        }
        $this->services_js .= $this->enter;
        // TODO: services_js ------|------ clipboard
        if(!isset($this->config['cordova_plugin']['clipboard']['enable'])) {
            $this->config['cordova_plugin']['clipboard']['enable'] = false;
        }
        if($this->config['cordova_plugin']['clipboard']['enable'] == true) {
            $this->services_js .= '// TO'.'DO: --|-------- cordova-clipboard'.$this->enter;
            $this->services_js .= "/** required: cordova-clipboard **/".$this->enter;
            $this->services_js .= '.directive("clipboardCopy", function($compile, $ionicModal, $ionicPlatform,$ionicLoading,$timeout) {'.$this->enter;
            $this->services_js .= $this->tab.'return {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.'link: function link($scope, $element, $attrs) {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", clipboardCopy);'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'function clipboardCopy(){'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.show();'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var Text = $attrs.text || "" ;'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (window.cordova && window.cordova.plugins.clipboard) {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'cordova.plugins.clipboard.copy(Text);'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'alert("Only work in real device!");'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},500)'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
            $this->services_js .= $this->tab.'};'.$this->enter;
            $this->services_js .= '})'.$this->enter;
        }
        $this->services_js .= $this->enter;
        // TODO: services_js ------|------ geolocation
        if(!isset($this->config['cordova_plugin']['geolocation']['enable'])) {
            $this->config['cordova_plugin']['geolocation']['enable'] = false;
        }
        if($this->config['cordova_plugin']['geolocation']['enable'] == true) {
            $this->services_js .= '// TO'.'DO: --|-------- cordova-plugin-geolocation'.$this->enter;
            $this->services_js .= "/** required: cordova-plugin-geolocation **/".$this->enter;
            $this->services_js .= '.directive("geoLocation", function($compile, $ionicModal, $ionicPlatform) {'.$this->enter;
            $this->services_js .= $this->tab.'return {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.'scope: {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'geoText: "="'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.'},'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.'link: function link($scope, $element, $attrs) {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'$element.bind("click", getLocation);'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'function getLocation() {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.$apply(function() {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.geoText = "wait...";'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.geolocation.getCurrentPosition(function(position) {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var lat = position.coords.latitude;'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var lng = position.coords.longitude;'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.$apply(function() {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.geoText = lat + "," + lng;'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},function(error) {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.$apply(function() {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.geoText = error.message;'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},{'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'maximumAge: 3600000,'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'timeout: 60000,'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'enableHighAccuracy: false,'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'} catch (err) {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.$apply(function() {'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.geoText = err.message;'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->services_js .= $this->tab.$this->tab.'}'.$this->enter;
            $this->services_js .= $this->tab.'};'.$this->enter;
            $this->services_js .= '})'.$this->enter;
        }
        $this->services_js .= $this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->enter;
        if(isset($this->config['js']['directives'])) {
            $this->services_js .= $this->config['js']['directives'].$this->enter;
        }
        $this->services_js .= $this->enter;
        $this->services_js .= $this->enter;
        $this->services_js .= 'document.onclick = function (e){'.$this->enter;
        $this->services_js .= $this->tab.'e = e ||  window.event;'.$this->enter;
        $this->services_js .= $this->tab.'var element = e.target || e.srcElement;'.$this->enter;
        $this->services_js .= $this->tab.'if (element.target == "_system") {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'window.open(element.href, "_system", "location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'return false;'.$this->enter;
        $this->services_js .= $this->tab.'}'.$this->enter;
        $this->services_js .= $this->enter;
        $this->services_js .= $this->tab.'if (element.target == "_blank") {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'var appBrowser = window.open(element.href, "_blank", "hardwareback=Done,hardwareback=Done,toolbarposition=top,location=yes");'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'appBrowser.addEventListener("loadstart",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStart("Please Wait", "Its loading....");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'appBrowser.addEventListener("loadstop",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'appBrowser.addEventListener("loaderror",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'window.location = "retry.html";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'appBrowser.addEventListener("exit",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'return false;'.$this->enter;
        $this->services_js .= $this->tab.'}'.$this->enter;
        $this->services_js .= $this->enter;
        $this->services_js .= $this->tab.'if (element.target == "_self") {'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'var appWebview = window.open(element.href, "_blank","location=no,toolbar=no");'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'appWebview.addEventListener("loadstart",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStart("Please Wait", "Its loading....");'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'appWebview.addEventListener("loadstop",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'appWebview.addEventListener("loaderror",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'window.location = "retry.html";'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'appWebview.addEventListener("exit",function(){'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->services_js .= $this->tab.$this->tab.'return false;'.$this->enter;
        $this->services_js .= $this->tab.'}'.$this->enter;
        $this->services_js .= '};'.$this->enter;
        return $ionic;
    }
    function tabs()
    {
        $app_config = $this->config['app'];
        $ionic = null;
        if(count($this->pageTabs) != 0) {
            $style = null;
            if($this->pageMenuStyle == 'tabs-striped') {
                $style = 'tabs-striped';
            }
            $ionic .= '<ion-tabs class="'.$style.'  tabs-icon-top tabs-background-'.$this->pageMenuBackground.' tabs-color-'.$this->pageMenuColor.'" data-ink-opacity=".35" >'.$this->enter;
            $this->app_js .= $this->enter;
            // TODO: app_js --|-- config (tabs)
            $this->app_js .= '.config(function($stateProvider,$urlRouterProvider,$sceDelegateProvider,$ionicConfigProvider,$httpProvider){'.$this->enter;
            $menu_position = 'bottom';
            if(isset($this->config['menu']['menu_position'])) {
                $menu_position = $this->config['menu']['menu_position'];
            }
            $this->app_js .= $this->tab.'/** tabs position **/'.$this->enter;
            if($menu_position == 'bottom') {
                $this->app_js .= $this->tab.'$ionicConfigProvider.tabs.position("bottom"); '.$this->enter;
            } elseif($menu_position == 'top') {
                $this->app_js .= $this->tab.'$ionicConfigProvider.tabs.position("top");'.$this->enter;
            }
            if($this->pageMenuStyle == 'none') {
                $this->app_js .= $this->tab.'$ionicConfigProvider.tabs.style("standard");'.$this->enter;
            }
            if(isset($this->config['app']['domain'])) {
                $this->app_js .= $this->tab.'try{'.$this->enter;
                $domain = strtolower(trim(str_replace(array(
                    " ",
                    "\r",
                    "*",
                    "\n"),"",$this->config['app']['domain'])));
                $domain_arrs = explode(",",$domain);
                if(is_array($domain_arrs)) {
                    // TODO: app_js ------|-- domain whitelist
                    $this->app_js .= $this->tab.'// Domain Whitelist'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.'$sceDelegateProvider.resourceUrlWhitelist(['.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.'"self",'.$this->enter;
                    foreach($domain_arrs as $domain_arr) {
                        $domain_arr = strtolower($domain_arr);
                        $this->app_js .= $this->tab.$this->tab.$this->tab.'new RegExp(\'^(http[s]?):\/\/(w{3}.)?'.preg_quote($domain_arr).'/.+$\'),'.$this->enter;
                    }
                    $this->app_js .= $this->tab.$this->tab.']);'.$this->enter;
                }
                $this->app_js .= $this->tab.'}catch(err){'.$this->enter;
                $this->app_js .= $this->tab.$this->tab.'console.log("%cerror: %cdomain whitelist","color:blue;font-size:16px;","color:red;font-size:16px;");'.$this->enter;
                $this->app_js .= $this->tab.'}'.$this->enter;
            }
            $this->app_js .= $this->tab.'$stateProvider'.$this->enter;
            // TODO: app_js ------|-- state tabs
            $this->app_js .= $this->tab.'.state("'.$this->pagePrefix.'",{'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.'url: "/'.$this->pagePrefix.'",'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.'abstract: true,'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.'templateUrl: "templates/'.$this->pagePrefix.'-tabs.html",'.$this->enter;
            $this->app_js .= $this->tab.'})'.$this->enter;
            $this->app_js .= $this->enter;
            $this->popover_js();
            foreach($this->pageTabs as $pageTabs) {
                if($pageTabs['type'] != 'divider') {
                    if(!isset($_tab_default)) {
                        $_tab_default = $pageTabs;
                    }
                }
                if(strlen($pageTabs['icon-alt'] < 3)) {
                    $pageTabs['icon-alt'] = $pageTabs['icon'];
                }
                if(($pageTabs['type'] == 'link') || ($pageTabs['type'] == 'iframe')) {
                    $default_query = "";
                    foreach($this->config['page'] as $__page) {
                        if($this->str2var($pageTabs['var']) == $__page['prefix']) {
                            $default_query = "";
                            if(isset($__page['query_value'])) {
                                if($__page['db_url_dinamic'] != false) {
                                    $default_query = '({'.$__page['query'][0].':\''.$__page['query_value'].'\'})';
                                }
                            }
                        }
                    }
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.addslashes($pageTabs['label']).'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ui-sref="'.$this->pagePrefix.'.'.$this->str2var($pageTabs['var']).$default_query.'" >'.$this->enter;
                    $ionic .= $this->tab.$this->tab.'<ion-nav-view animation="none" name="'.$this->pagePrefix.'-'.$this->str2var($pageTabs['var']).'"></ion-nav-view>'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }
                // type menu
                $type_webview = array(
                    "webview",
                    "app-browser",
                    "ext-browser");
                if(in_array($pageTabs['type'],$type_webview)) {
                    if($pageTabs['option'] == "") {
                        $pageTabs['option'] = $this->config['app']['author_url'];
                    }
                }
                if($pageTabs['type'] == 'webview') {
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="openWebView(\''.$pageTabs['option'].'\')" >'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }
                if($pageTabs['type'] == 'app-browser') {
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="openAppBrowser(\''.$pageTabs['option'].'\')" >'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }
                if($pageTabs['type'] == 'ext-browser') {
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="openURL(\''.$pageTabs['option'].'\')" >'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }
                if($pageTabs['type'] == 'ext-email') {
                    if($pageTabs['option'] == "") {
                        $pageTabs['option'] = $this->config['app']['author_email'];
                    }
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="openURL(\'mailto:'.$pageTabs['option'].'\')" >'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }
                if($pageTabs['type'] == 'ext-sms') {
                    if($pageTabs['option'] == "") {
                        $pageTabs['option'] = '08123456789';
                    }
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="openURL(\'sms:'.$pageTabs['option'].'\')" >'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }
                if($pageTabs['type'] == 'ext-call') {
                    if($pageTabs['option'] == "") {
                        $pageTabs['option'] = '08123456789';
                    }
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="openURL(\'tel:'.$pageTabs['option'].'\')" >'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }
                if($pageTabs['type'] == 'ext-playstore') {
                    if($pageTabs['option'] == "") {
                        $pageTabs['option'] = str_replace("_","",JSM_PACKAGE_NAME.".".$this->str2var($this->config['app']['company']).".".$this->str2var($this->config['app']['prefix']));
                    }
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="openURL(\'market://details?id='.$pageTabs['option'].'\')" >'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }
                if($pageTabs['type'] == 'ext-geo') {
                    if($pageTabs['option'] == "") {
                        $pageTabs['option'] = '';
                    }
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="openURL(\'geo:'.$pageTabs['option'].'\')" >'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }
                if($pageTabs['type'] == 'app-exit') {
                    if($pageTabs['option'] == "") {
                        $pageTabs['option'] = '';
                    }
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="exitApp()" >'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }

                if($pageTabs['type'] == 'app-home') {
                    if($pageTabs['option'] == "") {
                        $pageTabs['option'] = '';
                    }
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ui-sref="'.$this->pagePrefix.'.'.$this->config['app']['index'].'" >'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }

                if($pageTabs['type'] == 'html-link') {
                    if($pageTabs['option'] == "") {
                        $pageTabs['option'] = '';
                    }
                    $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" href="'.$pageTabs['option'].'" >'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }

                // TODO: menu --|-- menu - dynamic item

                // TODO: menu --|-- menu - dynamic item - cordova plugin barcodescanner
                if(!isset($this->config['cordova_plugin']['xsocialsharing']['enable'])) {
                    $this->config['cordova_plugin']['xsocialsharing']['enable'] = false;
                }
                if($this->config['cordova_plugin']['xsocialsharing']['enable'] == true) {
                    if($pageTabs['type'] == 'xsocialsharing-share-myapp') {
                        if($pageTabs['desc'] == "") {
                            $pageTabs['desc'] = "I have been having fun with ".htmlentities($app_config['name_unicode'])." App. Try it NOW! :D";
                        }

                        if($pageTabs['option'] == "") {
                            $pageTabs['option'] = str_replace("_","","https://play.google.com/store/apps/details?id=".JSM_PACKAGE_NAME.".".$this->str2var($this->config['app']['company']).".".$this->str2var($this->config['app']['prefix']));
                        }

                        $text_invite = htmlentities($pageTabs['desc']);
                        $link_invite = htmlentities($pageTabs['option']);

                        $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="socialShare(null, \''.$text_invite.'\',null,\''.$link_invite.'\')" >'.$this->enter;
                        $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                    }
                }


                // TODO: menu --|-- menu - dynamic item - cordova plugin barcodescanner
                if(!isset($this->config['cordova_plugin']['barcodescanner']['enable'])) {
                    $this->config['cordova_plugin']['barcodescanner']['enable'] = false;
                }
                if($this->config['cordova_plugin']['barcodescanner']['enable'] == true) {
                    if($pageTabs['type'] == 'barcodescanner-alert') {
                        if($pageTabs['option'] == "") {
                            $pageTabs['option'] = '';
                        }
                        $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="barcodeScanner(\'alert\')" >'.$this->enter;
                        $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                    }
                    if($pageTabs['type'] == 'barcodescanner-link-internal') {
                        if($pageTabs['option'] == "") {
                            $pageTabs['option'] = '';
                        }
                        $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="barcodeScanner(\'inlink\')" >'.$this->enter;
                        $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                    }
                    if($pageTabs['type'] == 'barcodescanner-link-external') {
                        if($pageTabs['option'] == "") {
                            $pageTabs['option'] = '';
                        }
                        $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="barcodeScanner(\'outlink\')" >'.$this->enter;
                        $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                    }
                    if($pageTabs['type'] == 'barcodescanner-appbrowser') {
                        if($pageTabs['option'] == "") {
                            $pageTabs['option'] = '';
                        }
                        $ionic .= $this->tab.'<ion-tab class="{{ hide_menu_'.$pageTabs['var'].' }} menu-'.$pageTabs['var'].'" title="{{ \''.$pageTabs['label'].'\' | translate }}" icon-off="'.$pageTabs['icon-alt'].'" icon-on="'.$pageTabs['icon'].'" ng-click="barcodeScanner(\'appbrowser\')" >'.$this->enter;
                        $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                    }
                }
            }
            // TODO: fix --|-- tab without button
            foreach($this->config['page'] as $_page) {
                if(!isset($_page['create-manual'])) {
                    $_page['create-manual'] = false;
                }
                if(($_page['menutype'] == 'tabs-custom') || ($_page['create-manual'] == 'true')) {
                    $ionic .= $this->tab.'<ion-tab class="hide menu-'.$_page['prefix'].'" title="'.$_page['title'].'"  ui-sref="'.$this->pagePrefix.'.'.$_page['prefix'].'"  >'.$this->enter;
                    $ionic .= $this->tab.$this->tab.'<ion-nav-view animation="none" name="'.$this->pagePrefix.'-'.$_page['prefix'].'"></ion-nav-view>'.$this->enter;
                    $ionic .= $this->tab.'</ion-tab>'.$this->enter;
                }
            }
            $ionic .= '</ion-tabs>'.$this->enter;
            $this->sub_page();
            if(isset($this->config['js']['router'])) {
                $this->app_js .= $this->enter;
                $this->app_js .= '// router by user'.$this->enter;
                $this->app_js .= $this->config['js']['router'].$this->enter;
                $this->app_js .= $this->enter;
            }
            $this->app_js .= $this->tab.'$urlRouterProvider.otherwise("/'.$this->pagePrefix.'/'.$this->config['app']['index'].'");'.$this->enter;
            $this->app_js .= '});'.$this->enter;
        }
        return $ionic;
    }
    function sub_page()
    {
        if(count($this->subPages) != 0) {
            foreach($this->subPages as $sub_pages) {
                $query = null;
                if(isset($sub_pages['query'])) {
                    if(is_array($sub_pages['query'])) {
                        foreach($sub_pages['query'] as $query_id) {
                            $query .= "/:".$this->str2var($query_id,false);
                        }
                    }
                }
                if(!isset($sub_pages['menutype'])) {
                    $sub_pages['menutype'] = 'false';
                }
                if($sub_pages['menutype'] == 'subsub') {
                    $this->page_router($this->pagePrefix.'-'.$this->str2var($sub_pages['title']),$sub_pages,$query);
                }
                // TODO: menu --|-- tab listing
                if($sub_pages['menutype'] == 'tabs') {
                    $this->page_router($this->pagePrefix.'-'.$this->str2var($sub_pages['prefix']),$sub_pages,$query);
                }
                // TODO: menu --|-- tab detail
                if($sub_pages['menutype'] == 'sub-tabs') {
                    $this->page_router($this->pagePrefix.'-'.$this->str2var($sub_pages['parent']),$sub_pages,$query);
                }
                // TODO: menu --|-- tab custom
                if($sub_pages['menutype'] == 'tabs-custom') {
                    $this->page_router($this->pagePrefix.'-'.$this->str2var($sub_pages['prefix']),$sub_pages,$query);
                }
                // TODO: menu --|-- side_menus custom
                if($sub_pages['menutype'] == 'side_menus-custom') {
                    $this->page_router($this->pagePrefix.'-side_menus',$sub_pages,$query);
                }
                // TODO: menu --|-- side_menus
                if($sub_pages['menutype'] == 'side_menus') {
                    $this->page_router($this->pagePrefix.'-side_menus',$sub_pages,$query);
                }
                // TODO: menu --|-- side_menus detail
                if($sub_pages['menutype'] == 'sub-side_menus') {
                    $this->page_router($this->pagePrefix.'-side_menus',$sub_pages,$query);
                }
                $this->add_controllers($sub_pages);
                $this->tables_json($sub_pages);
            }
        }
    }
    function popover_js()
    {
        // TODO: controllers_js --|-- popover
        $direction = 'left';
        $attr_direction = null;
        if($this->config['app']['direction'] == 'rtl') {
            $direction = 'right';
            $attr_direction = 'dir="rtl"';
        }
        $costum_js = $header_bar = null;
        if(!isset($this->popover['menu'])) {
            $this->popover['menu'] = array();
        }
        if(count($this->popover['menu']) != 0) {
            $costum_js .= $this->tab.$this->enter;
            $costum_js .= $this->tab.'var popover_template = "";'.$this->enter;
            $costum_js .= $this->tab.'popover_template += "<ion-popover-view class=\"fit\">";'.$this->enter;
            if(!isset($this->popover['title'])) {
                $this->popover['title'] = '';
            }
            if($this->popover['title'] != '') {
                $costum_js .= $this->tab.'popover_template += "'.$this->tab.'<ion-header-bar>";'.$this->enter;
                $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.'<h1 '.addslashes($attr_direction).' class=\"title\">'.addslashes($this->popover['title']).'</h1>";'.$this->enter;
                $costum_js .= $this->tab.'popover_template += "'.$this->tab.'</ion-header-bar>";'.$this->enter;
            }
            $costum_js .= $this->tab.'popover_template += "'.$this->tab.'<ion-content>";'.$this->enter;
            $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.'<ion-list>";'.$this->enter;
            foreach($this->popover['menu'] as $popover_menu) {
                $popover_menu['title'] = addslashes($popover_menu['title']);
                if($popover_menu['type'] == 'show-notification-dialog') {
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"showNotificationDialog()\" >";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'show-fontsize-dialog') {
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"showFontSizeDialog()\" >";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'show-language-dialog') {
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"showLanguageDialog()\" >";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'app-clear-cache') {
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"clearCacheApp()\" >";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'app-exit') {
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"exitApp()\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'link') {
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-href=\"'.addslashes($popover_menu['link']).'\" ng-click=\"popover.hide()\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'divider') {
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<div '.addslashes($attr_direction).' class=\"item item-divider\" >";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</div>";'.$this->enter;
                }
                if($popover_menu['type'] == 'link-external') {
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"openURL(\''.addslashes($popover_menu['link']).'\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'link-webview') {
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"openWebView(\''.addslashes($popover_menu['link']).'\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'link-appbrowser') {
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"openAppBrowser(\''.addslashes($popover_menu['link']).'\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'link-ext-email') {
                    if($popover_menu['link'] == "") {
                        $popover_menu['link'] = $this->config['app']['author_email'];
                    }
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"openURL(\'mailto:'.addslashes($popover_menu['link']).'\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'link-ext-sms') {
                    if($popover_menu['link'] == "") {
                        $popover_menu['link'] = "";
                    }
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"openURL(\'sms:'.addslashes($popover_menu['link']).'\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'link-ext-call') {
                    if($popover_menu['link'] == "") {
                        $popover_menu['link'] = "";
                    }
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"openURL(\'tel:'.addslashes($popover_menu['link']).'\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'link-ext-playstore') {
                    if($popover_menu['link'] == "") {
                        $popover_menu['link'] = str_replace("_","",JSM_PACKAGE_NAME.".".$this->str2var($this->config['app']['company']).".".$this->str2var($this->config['app']['prefix']));
                    }
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"openURL(\'market://details?id='.addslashes($popover_menu['link']).'\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'link-ext-geo') {
                    if($popover_menu['link'] == "") {
                        $popover_menu['link'] = "";
                    }
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"openURL(\'geo:'.addslashes($popover_menu['link']).'\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'cordova-barcodescanner-alert') {
                    if($popover_menu['link'] == "") {
                        $popover_menu['link'] = "";
                    }
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"barcodeScanner(\'alert\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'cordova-barcodescanner-link-internal') {
                    if($popover_menu['link'] == "") {
                        $popover_menu['link'] = "";
                    }
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"barcodeScanner(\'inlink\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'cordova-barcodescanner-appbrowser') {
                    if($popover_menu['link'] == "") {
                        $popover_menu['link'] = "";
                    }
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"barcodeScanner(\'appbrowser\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
                if($popover_menu['type'] == 'cordova-barcodescanner-link-external') {
                    if($popover_menu['link'] == "") {
                        $popover_menu['link'] = "";
                    }
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'<a '.addslashes($attr_direction).' class=\"item dark-ink\" ng-click=\"barcodeScanner(\'outlink\')\">";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'{{ \''.addslashes($popover_menu['title']).'\' | translate }}";'.$this->enter;
                    $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.$this->tab.'</a>";'.$this->enter;
                }
            }
            $costum_js .= $this->tab.'popover_template += "'.$this->tab.$this->tab.'</ion-list>";'.$this->enter;
            $costum_js .= $this->tab.'popover_template += "'.$this->tab.'</ion-content>";'.$this->enter;
            $costum_js .= $this->tab.'popover_template += "</ion-popover-view>";'.$this->enter;
            $costum_js .= $this->tab.$this->enter;
            $costum_js .= $this->tab.$this->enter;
            $costum_js .= $this->tab.'$scope.popover = $ionicPopover.fromTemplate(popover_template,{'.$this->enter;
            $costum_js .= $this->tab.$this->tab.'scope: $scope'.$this->enter;
            $costum_js .= $this->tab.'});'.$this->enter;
            $costum_js .= $this->tab.$this->enter;
            $costum_js .= $this->tab.'$scope.closePopover = function(){'.$this->enter;
            $costum_js .= $this->tab.$this->tab.'$scope.popover.hide();'.$this->enter;
            $costum_js .= $this->tab.'};'.$this->enter;
            $costum_js .= $this->tab.$this->enter;
            $costum_js .= $this->tab.'$rootScope.closeMenuPopover = function(){'.$this->enter;
            $costum_js .= $this->tab.$this->tab.'$scope.popover.hide();'.$this->enter;
            $costum_js .= $this->tab.'};'.$this->enter;
            $costum_js .= $this->tab.$this->enter;
            $costum_js .= $this->tab.'$scope.$on("$destroy", function(){'.$this->enter;
            $costum_js .= $this->tab.$this->tab.'$scope.popover.remove();'.$this->enter;
            $costum_js .= $this->tab.'});'.$this->enter;
        }
        if($this->mainMenu == 'tabs') {
            $data_main['prefix'] = 'index';
            $this->add_controllers($data_main,$costum_js);
        } else {
            $data_main['prefix'] = 'index';
            $this->add_controllers($data_main);
            $data_main['prefix'] = 'side_menus';
            $this->add_controllers($data_main,$costum_js);
        }
    }
    function side_menus()
    {
        $app_config = $this->config['app'];
        $direction = 'left';
        $attr_direction = null;
        if($this->config['app']['direction'] == 'rtl') {
            $direction = 'right';
            $attr_direction = 'dir="rtl"';
        }
        $config = $this->config;
        $ionic = null;
        if(count($this->pageSideMenus) != 0) {
            if(!isset($this->popover['icon'])) {
                $pop_over_icon = '';
                $this->popover['icon'] = '';
            }
            if($this->popover['icon'] == '') {
                $pop_over_icon = 'ion-android-more-vertical';
            } else {
                $pop_over_icon = $this->popover['icon'];
            }
            if(!isset($this->popover['menu'])) {
                $this->popover['menu'] = array();
            }
            // TODO: app_js --|-- config
            $this->app_js .= '.config(function($stateProvider, $urlRouterProvider,$sceDelegateProvider,$httpProvider,$ionicConfigProvider){'.$this->enter;
            if(isset($this->config['app']['domain'])) {
                $domain = strtolower(trim(str_replace(array(
                    " ",
                    "\r",
                    "\n"),"",$this->config['app']['domain'])));
                $domain_arrs = explode(",",$domain);
                if(is_array($domain_arrs)) {
                    $this->app_js .= $this->tab.'try{'.$this->enter;
                    // TODO: app_js ------|-- domain whitelist
                    $this->app_js .= $this->tab.$this->tab.'// Domain Whitelist'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.'$sceDelegateProvider.resourceUrlWhitelist(['.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.$this->tab.'"self",'.$this->enter;
                    foreach($domain_arrs as $domain_arr) {
                        $this->app_js .= $this->tab.$this->tab.$this->tab.'new RegExp(\'^(http[s]?):\/\/(w{3}.)?'.preg_quote($domain_arr).'/.+$\'),'.$this->enter;
                    }
                    $this->app_js .= $this->tab.$this->tab.']);'.$this->enter;
                    $this->app_js .= $this->tab.'}catch(err){'.$this->enter;
                    $this->app_js .= $this->tab.$this->tab.'console.log("%cerror: %cdomain whitelist","color:blue;font-size:16px;","color:red;font-size:16px;");'.$this->enter;
                    $this->app_js .= $this->tab.'}'.$this->enter;
                }
            }
            $this->app_js .= $this->tab.'$stateProvider'.$this->enter;
            // TODO: app_js ------|-- state sidebar
            $this->app_js .= $this->tab.'.state("'.$this->pagePrefix.'",{'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.'url: "/'.$this->pagePrefix.'",'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.'abstract: true,'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.'templateUrl: "templates/'.$this->pagePrefix.'-side_menus.html",'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.'controller: "side_menusCtrl",'.$this->enter;
            $this->app_js .= $this->tab.'})'.$this->enter;
            $this->app_js .= $this->enter;
            $this->popover_js();
            $ionic .= '<ion-side-menus enable-menu-with-back-views="false">'.$this->enter;
            $menu_position = 'left';
            if(isset($this->config['menu']['menu_position'])) {
                $menu_position = $this->config['menu']['menu_position'];
            }
            if(($menu_position == 'left') || ($menu_position == 'right')) {
            } else {
                $menu_position = 'left';
            }
            if($menu_position == 'left') {
                $ionic .= $this->tab.'<ion-side-menu-content>'.$this->enter;
                // TODO: markup ------|-- navbar left
                $ionic .= $this->tab.$this->tab.'<ion-nav-bar ng-show="$root.headerExists" id="navbar-right-top" class="page-title bar-'.$this->pageHeaderBackground.'">'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'<ion-nav-back-button></ion-nav-back-button>'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'<ion-nav-buttons side="left">'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<button class="button button-icon button-clear ion-navicon" menu-toggle="left"></button>'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'</ion-nav-buttons>'.$this->enter;
                if(count($this->popover['menu']) != 0) {
                    $ionic .= $this->tab.$this->tab.$this->tab.'<ion-nav-buttons side="right">'.$this->enter;
                    if(isset($this->config['popover']['custom-code'])) {
                        if($this->config['popover']['custom-code'] !== '') {
                            $ionic .= $this->config['popover']['custom-code'];
                        }
                    }
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<button class="button button-icon button-clear '.$pop_over_icon.'" id="menu-popover" ng-click="popover.show($event)"></button>'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.'</ion-nav-buttons>'.$this->enter;
                } else {
                    if(isset($this->config['popover']['custom-code'])) {
                        if($this->config['popover']['custom-code'] !== '') {
                            $ionic .= $this->tab.$this->tab.$this->tab.'<ion-nav-buttons side="right">'.$this->enter;
                            $ionic .= $this->config['popover']['custom-code'];
                            $ionic .= $this->tab.$this->tab.$this->tab.'</ion-nav-buttons>'.$this->enter;
                        }
                    }
                }
                $ionic .= $this->tab.$this->tab.'</ion-nav-bar>'.$this->enter;
                // TODO: markup ----------|-- fab
                $ionic .= $this->tab.$this->tab.'<ion-nav-view name="fabButtonUp"></ion-nav-view>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<ion-nav-view animation="none" name="'.$this->pagePrefix.'-side_menus"></ion-nav-view>'.$this->enter;
                $ionic .= $this->tab.'</ion-side-menu-content>'.$this->enter;
            } else {
                $ionic .= $this->tab.'<ion-side-menu-content>'.$this->enter;
                // TODO: markup ------|-- navbar right
                $ionic .= $this->tab.$this->tab.'<ion-nav-bar ng-show="$root.headerExists" id="navbar-left-top" class="page-title bar-'.$this->pageHeaderBackground.'">'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'<ion-nav-back-button></ion-nav-back-button>'.$this->enter;
                if(count($this->popover['menu']) != 0) {
                    $ionic .= $this->tab.$this->tab.$this->tab.'<ion-nav-buttons side="left">'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<button class="button button-icon button-clear '.$pop_over_icon.'" id="menu-popover" ng-click="popover.show($event)"></button>'.$this->enter;
                    if(isset($this->config['popover']['custom-code'])) {
                        if($this->config['popover']['custom-code'] !== '') {
                            $ionic .= $this->config['popover']['custom-code'];
                        }
                    }
                    $ionic .= $this->tab.$this->tab.$this->tab.'</ion-nav-buttons>'.$this->enter;
                } else {
                    if(isset($this->config['popover']['custom-code'])) {
                        if($this->config['popover']['custom-code'] !== '') {
                            $ionic .= $this->tab.$this->tab.$this->tab.'<ion-nav-buttons side="left">'.$this->enter;
                            $ionic .= $this->config['popover']['custom-code'];
                            $ionic .= $this->tab.$this->tab.$this->enter;
                            $ionic .= $this->tab.$this->tab.$this->tab.'</ion-nav-buttons>'.$this->enter;
                        }
                    }
                }
                $ionic .= $this->tab.$this->tab.$this->tab.'<ion-nav-buttons side="right">'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<button class="button button-icon button-clear ion-navicon" menu-toggle="right"></button>'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'</ion-nav-buttons>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'</ion-nav-bar>'.$this->enter;
                // TODO: markup ----------|-- fab
                $ionic .= $this->tab.$this->tab.'<ion-nav-view name="fabButtonUp"></ion-nav-view>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<ion-nav-view animation="none" name="'.$this->pagePrefix.'-side_menus"></ion-nav-view>'.$this->enter;
                $ionic .= $this->tab.'</ion-side-menu-content>'.$this->enter;
            }
            $ionic .= $this->tab.'<ion-side-menu side="'.$menu_position.'">'.$this->enter;
            if(!isset($config['menu']['menu_style'])) {
                $config['menu']['menu_style'] = 'none';
            }
            if($config['menu']['menu_style'] == 'expanded-header') {
                // TODO: markup ------|-- expanded
                $this->add_css .= '.menu .bar.bar-header.expanded{background-image: url("../'.$config['menu']['expanded_header'].'");background-size: 120%;background-position:0%;transition: all .5s ease-in-out;}'.$this->enter;
                $this->add_css .= '.menu .bar.bar-header.expanded img{margin:auto;left:16px;right:16px;}'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<ion-header-bar class="text-center expanded '.$this->pageHeaderBackground.'-bg">'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'<img ng-src="{{ appLogo | trustUrl }}" class="avatar motion spin fade" />'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'<div class="menu-bottom"><span ng-bind-html="appName | strHTML"></span><br/></div>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'</ion-header-bar>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<ion-content class="has-expanded-header '.$this->pageMenuBackground.'-bg '.$this->pageMenuColor.'">'.$this->enter;
            } else {
                // TODO: markup ------|-- without expanded
                $ionic .= $this->tab.$this->tab.'<ion-header-bar class="bar-'.$this->pageHeaderBackground.'  ">'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'<h1 class="title"><span ng-bind-html="appName | strHTML"></span></h1>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'</ion-header-bar>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<ion-content class="'.$this->pageMenuBackground.'-bg '.$this->pageMenuColor.'">'.$this->enter;
            }
            $ionic .= $this->tab.$this->tab.$this->tab.'<ion-list class="list" >'.$this->enter;
            // TODO: menu --|-- Sidemenu - menu listing
            foreach($this->pageSideMenus as $pageSideMenus) {
                $default_query = "";
                if(($pageSideMenus['type'] == 'link') || ($pageSideMenus['type'] == 'iframe')) {
                    foreach($this->config['page'] as $__page) {
                        if($this->str2var($pageSideMenus['var']) == $__page['prefix']) {
                            $default_query = "";
                            if(isset($__page['query_value'])) {
                                if(isset($__page['db_url_dinamic'])) {
                                    if($__page['db_url_dinamic'] != false) {
                                        $default_query = '({'.$__page['query'][0].':\''.$__page['query_value'].'\'})';
                                    }
                                }
                            }
                        }
                    }
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ui-sref="'.$this->pagePrefix.'.'.$this->str2var($pageSideMenus['var']).$default_query.'">'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> '.'{{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                if($pageSideMenus['type'] == 'divider') {
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" class="item item-divider text-'.$direction.'" >'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.' {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                // Type Menu
                $type_webview = array(
                    "webview",
                    "app-browser",
                    "ext-browser");
                if(in_array($pageSideMenus['type'],$type_webview)) {
                    if($pageSideMenus['option'] == "") {
                        $pageSideMenus['option'] = $this->config['app']['author_url'];
                    }
                }
                if($pageSideMenus['type'] == 'webview') {
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="openWebView(\''.$pageSideMenus['option'].'\')">'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                if($pageSideMenus['type'] == 'app-browser') {
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="openAppBrowser(\''.$pageSideMenus['option'].'\')">'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                if($pageSideMenus['type'] == 'ext-browser') {
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="openURL(\''.$pageSideMenus['option'].'\')">'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                if($pageSideMenus['type'] == 'ext-email') {
                    if($pageSideMenus['option'] == "") {
                        $pageSideMenus['option'] = $this->config['app']['author_email'];
                    }
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="openURL(\'mailto:'.$pageSideMenus['option'].'\')">'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                if($pageSideMenus['type'] == 'ext-sms') {
                    if($pageSideMenus['option'] == "") {
                        $pageSideMenus['option'] = '08123456789';
                    }
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="openURL(\'sms:'.$pageSideMenus['option'].'\')">'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                if($pageSideMenus['type'] == 'ext-call') {
                    if($pageSideMenus['option'] == "") {
                        $pageSideMenus['option'] = '08123456789';
                    }
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="openURL(\'tel:'.$pageSideMenus['option'].'\')">'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                if($pageSideMenus['type'] == 'ext-playstore') {
                    if(!isset($pageSideMenus['option'])) {
                        $pageSideMenus['option'] = '';
                    }
                    if($pageSideMenus['option'] == "") {
                        $pageSideMenus['option'] = str_replace("_","",JSM_PACKAGE_NAME.".".$this->str2var($this->config['app']['company']).".".$this->str2var($this->config['app']['prefix']));
                    }
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="openURL(\'market://details?id='.$pageSideMenus['option'].'\')">'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                if($pageSideMenus['type'] == 'ext-geo') {
                    if($pageSideMenus['option'] == "") {
                        $pageSideMenus['option'] = "";
                    }
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="openURL(\'geo:'.$pageSideMenus['option'].'\')">'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                if($pageSideMenus['type'] == 'app-exit') {
                    if($pageSideMenus['option'] == "") {
                        $pageSideMenus['option'] = "";
                    }
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="exitApp()">'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                if($pageSideMenus['type'] == 'app-home') {
                    if($pageSideMenus['option'] == "") {
                        $pageSideMenus['option'] = "";
                    }
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close=""  ui-sref="'.$this->pagePrefix.'.'.$this->config['app']['index'].'" >'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }
                if($pageSideMenus['type'] == 'html-link') {
                    if($pageSideMenus['option'] == "") {
                        $pageSideMenus['option'] = "";
                    }
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close=""  href="'.$pageSideMenus['option'].'" >'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                    $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                }

                // TODO: menu --|-- menu - dynamic item

                // TODO: menu --|-- menu - dynamic item - cordova plugin xsocialsharing
                if(!isset($this->config['cordova_plugin']['xsocialsharing']['enable'])) {
                    $this->config['cordova_plugin']['xsocialsharing']['enable'] = false;
                }
                if($this->config['cordova_plugin']['xsocialsharing']['enable'] == true) {
                    if($pageSideMenus['type'] == 'xsocialsharing-share-myapp') {
                        if($pageSideMenus['desc'] == "") {
                            $pageSideMenus['desc'] = "I have been having fun with ".htmlentities($app_config['name_unicode'])." App. Try it NOW! :D";
                        }

                        if($pageSideMenus['option'] == "") {
                            $pageSideMenus['option'] = str_replace("_","","https://play.google.com/store/apps/details?id=".JSM_PACKAGE_NAME.".".$this->str2var($this->config['app']['company']).".".$this->str2var($this->config['app']['prefix']));
                        }

                        $text_invite = htmlentities($pageSideMenus['desc']);
                        $link_invite = htmlentities($pageSideMenus['option']);

                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="socialShare(null,\''.$text_invite.'\',null,\''.$link_invite.'\')" >'.$this->enter;
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                    }
                }


                // TODO: menu --|-- menu - dynamic item - cordova plugin barcodescanner
                if(!isset($this->config['cordova_plugin']['barcodescanner']['enable'])) {
                    $this->config['cordova_plugin']['barcodescanner']['enable'] = false;
                }
                if($this->config['cordova_plugin']['barcodescanner']['enable'] == true) {
                    if($pageSideMenus['type'] == 'barcodescanner-alert') {
                        if($pageSideMenus['option'] == "") {
                            $pageSideMenus['option'] = "";
                        }
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="barcodeScanner(\'alert\')" >'.$this->enter;
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                    }
                    if($pageSideMenus['type'] == 'barcodescanner-link-internal') {
                        if($pageSideMenus['option'] == "") {
                            $pageSideMenus['option'] = "";
                        }
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="barcodeScanner(\'inlink\')" >'.$this->enter;
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                    }
                    if($pageSideMenus['type'] == 'barcodescanner-link-external') {
                        if($pageSideMenus['option'] == "") {
                            $pageSideMenus['option'] = "";
                        }
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="barcodeScanner(\'outlink\')">'.$this->enter;
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                    }
                    if($pageSideMenus['type'] == 'barcodescanner-appbrowser') {
                        if($pageSideMenus['option'] == "") {
                            $pageSideMenus['option'] = "";
                        }
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<ion-item ng-hide="hide_menu_'.$this->str2var($pageSideMenus['var']).'" '.$this->soundtouch.' class="menu-'.$this->str2var($pageSideMenus['var']).' item-sidebar item item-icon-'.$direction.' text-'.$direction.' dark-ink" nav-clear="" menu-close="" ng-click="barcodeScanner(\'appbrowser\')">'.$this->enter;
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'<i class="icon '.$pageSideMenus['icon'].'"></i> {{ "'.$pageSideMenus['label'].'" | translate }}'.$this->enter;
                        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
                    }
                }
            }
            $ionic .= $this->tab.$this->tab.$this->tab.'</ion-list>'.$this->enter;
            $ionic .= $this->tab.$this->tab.'</ion-content>'.$this->enter;
            $ionic .= $this->tab.'</ion-side-menu>'.$this->enter;
            $ionic .= '</ion-side-menus>'.$this->enter;
            $this->sub_page();
            if(isset($this->config['js']['router'])) {
                // TODO: app_js --|-- router by user
                $this->app_js .= $this->enter;
                $this->app_js .= '// router by user'.$this->enter;
                $this->app_js .= $this->config['js']['router'].$this->enter;
                $this->app_js .= $this->enter;
            }
            $this->app_js .= $this->tab.'$urlRouterProvider.otherwise("/'.$this->pagePrefix.'/'.$this->config['app']['index'].'");'.$this->enter;
            $this->app_js .= '});'.$this->enter;
        }
        return $ionic;
    }
    /**
     * Ionic::add_controllers()
     * 
     * @param mixed $page
     * @param mixed $code
     * @return void
     */
    function add_controllers($page,$code = null)
    {
        $query = null;
        $retrieval_error_title = 'Error';
        $retrieval_error_content = 'An error occurred while collecting data';
        $this->controllers_js .= $this->enter;
        $_tables['template'] = '';
        $theme_no_animation = array(
            "table",
            "dictionary",
            "homepage1",
            "external",
            "gmapmarker",
            "wizard",
            "manual_coding",
            "image-reader");
        $this->controllers_js .= '// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- '.$this->enter;
        // TODO: controllers_js --|-- controller
        $add_def = '';
        if(isset($page['jsdef'])) {
            if(strlen($page['jsdef']) > 2) {
                $add_def = ','.$page['jsdef'];
            }
        }
        $this->controllers_js .= '.controller("'.$this->str2var($page['prefix']).'Ctrl", function($ionicConfig,$scope,$rootScope,$state,$location,$ionicScrollDelegate,$ionicListDelegate,$http,$httpParamSerializer,$stateParams,$timeout,$interval,$ionicLoading,$ionicPopup,$ionicPopover,$ionicActionSheet,$ionicSlideBoxDelegate,$ionicHistory,ionicMaterialInk,ionicMaterialMotion,$window,$ionicModal,base64,md5,$document,$sce,$ionicGesture,$translate,tmhDynamicLocale'.$add_def.'){'.$this->enter;


        // TODO: controllers_js --|-- controller -- | -- hide menu
        if(!isset($page['hide-menu'])) {
            $page['hide-menu'] = array();
        }

        foreach($page['hide-menu'] as $item_menu) {
            $this->controllers_js .= $this->tab.'$rootScope.hide_menu_'.$this->str2var($item_menu).' = "hide" ;'.$this->enter;
        }

        if(!isset($page['show-banner'])) {
            $page['show-banner'] = false;
        }
        if(!isset($page['show-banner'])) {
            $page['show-banner'] = false;
        }
        if(!isset($page['hide-banner'])) {
            $page['hide-banner'] = false;
        }

        if(!isset($page['show-interstitial'])) {
            $page['show-interstitial'] = false;
        }
        if(!isset($page['show-rewardvideo'])) {
            $page['show-rewardvideo'] = false;
        }
        // TODO: controllers_js --|-- showBanner
        if($page['show-banner'] == true) {
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- showBanner'.$this->enter;
            if(isset($this->config['mod']['admob']['data'])) {
                $this->controllers_js .= $this->tab.'if (typeof AdMob !== "undefined"){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'try{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'AdMob.showBanner(8);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
            }
            if(isset($this->config['mod']['admob-free']['data'])) {
                $this->controllers_js .= $this->tab.'if (typeof admob !== "undefined"){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'try{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'admob.banner.show();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
            }
        }
        // TODO: controllers_js --|-- hideBanner
        if($page['hide-banner'] == true) {
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- hideBanner'.$this->enter;
            if(isset($this->config['mod']['admob']['data'])) {
                $this->controllers_js .= $this->tab.'if (typeof AdMob !== "undefined"){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'try{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'AdMob.hideBanner();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
            }
            if(isset($this->config['mod']['admob-free']['data'])) {
                $this->controllers_js .= $this->tab.'if (typeof admob !== "undefined"){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'try{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'admob.banner.hide();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
            }
        }
        // TODO: controllers_js --|-- showInterstitial
        if($page['show-interstitial'] == true) {
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- showInterstitial'.$this->enter;
            if(isset($this->config['mod']['admob']['data'])) {
                $this->controllers_js .= $this->tab.'if (typeof AdMob !== "undefined"){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'try{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'AdMob.showInterstitial();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
            }
            if(isset($this->config['mod']['admob-free']['data'])) {
                $this->controllers_js .= $this->tab.'if (typeof admob !== "undefined"){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'try{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'admob.interstitial.show();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
            }
        }

        // TODO: controllers_js --|-- showRewardVideoAd
        if($page['show-rewardvideo'] == true) {
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- showRewardVideoAd'.$this->enter;
            if(isset($this->config['mod']['admob']['data'])) {
                $this->controllers_js .= $this->tab.'if (typeof AdMob !== "undefined"){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'try{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'AdMob.showRewardVideoAd();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}catch(err){ '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'//alert(err.message);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
            }
        }

        $this->controllers_js .= $this->tab.$this->enter;
        if(!isset($page['last_edit_by'])) {
            $page['last_edit_by'] = '-';
        }
        if(!isset($page['hide-navbar'])) {
            $page['hide-navbar'] = false;
        }
        // TODO: controllers_js --|-- $rootScope
        if($page['hide-navbar'] == true) {
            $this->controllers_js .= $this->tab.'$rootScope.headerExists = false;'.$this->enter;
        } else {
            $this->controllers_js .= $this->tab.'$rootScope.headerExists = true;'.$this->enter;
        }
        if($this->str2var($page['prefix']) != 'index') {
            $this->controllers_js .= $this->tab.'$rootScope.ionWidth = $document[0].body.querySelector(".view-container").offsetWidth || 412;'.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.grid64 = parseInt($rootScope.ionWidth / 64) ;'.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.grid80 = parseInt($rootScope.ionWidth / 80) ;'.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.grid128 = parseInt($rootScope.ionWidth / 128) ;'.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.grid256 = parseInt($rootScope.ionWidth / 256) ;'.$this->enter;
        }
        if($this->str2var($page['prefix']) == 'index') {
            if(!isset($tables['bookmarks'])) {
                $tables['bookmarks'] = 'none';
            }
            if(($tables['bookmarks'] == 'cart') || ($tables['bookmarks'] == 'bookmark')) {
                $this->controllers_js .= $this->tab.'$rootScope.item_in_virtual_table_'.$tables['prefix'].' = 0;'.$this->enter;
            }


            // TODO: controllers_js --|-- $rootScope.xsocialsharing
            if(!isset($this->config['cordova_plugin']['xsocialsharing']['enable'])) {
                $this->config['cordova_plugin']['xsocialsharing']['enable'] = false;
            }
            if($this->config['cordova_plugin']['xsocialsharing']['enable'] == true) {
                $this->controllers_js .= $this->enter;
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $rootScope.socialShare'.$this->enter;
                $this->controllers_js .= $this->tab."// required:  cordova-plugin-x-socialsharing".$this->enter;
                $this->controllers_js .= $this->tab.'$rootScope.socialShare = function(message, subject, fileOrFileArray, url, successCallback, errorCallback){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if (window.cordova && window.plugins.socialsharing){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.plugins.socialsharing.share(message, subject, fileOrFileArray, url, successCallback, errorCallback);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var socialSharePopup = $ionicPopup.alert({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'title: "Social Sharing",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: "Only work in real device!",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;

            }

            // TODO: controllers_js --|-- $rootScope.barcodeScanner
            if(!isset($this->config['cordova_plugin']['barcodescanner']['enable'])) {
                $this->config['cordova_plugin']['barcodescanner']['enable'] = false;
            }
            if($this->config['cordova_plugin']['barcodescanner']['enable'] == true) {
                $this->controllers_js .= $this->enter;
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $rootScope.barcodeScanner'.$this->enter;
                $this->controllers_js .= $this->tab."// required:  cordova-plugin-barcodescanner".$this->enter;
                $this->controllers_js .= $this->tab.'$rootScope.barcodeScanner = function(outputType){'.$this->enter;

                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'var barcodeText = "";'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if (window.cordova && window.cordova.plugins.barcodeScanner){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var barcodeText = "";'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'cordova.plugins.barcodeScanner.scan(function(result){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'barcodeText = result.text;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if(barcodeText===""){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'return false;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'switch(outputType){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'case "alert":'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var barcodePopup = $ionicPopup.alert({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'title: "Barcode Scanner",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: "Result:<br/>" + barcodeText,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'break;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'case "inlink":'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$window.location = barcodeText;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},200);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'break;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'case "appbrowser":'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var barcodeAppBrowser = window.open(barcodeText,"_blank","hardwareback=Done,hardwareback=Done,toolbarposition=top,location=yes");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'barcodeAppBrowser.addEventListener("loadstart",function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStart("Please Wait", "Its loading....");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'barcodeAppBrowser.addEventListener("loadstop",function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'barcodeAppBrowser.addEventListener("loaderror",function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.location = "retry.html";'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'barcodeAppBrowser.addEventListener("exit",function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},200);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'break;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'case "outlink":'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.open(barcodeText,"_system","location=yes");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},200);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'break;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},function(error){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var barcodePopup = $ionicPopup.alert({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'title: "Barcode Scanner",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: "An error occurred on the barcode scanner.",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'preferFrontCamera: false,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'showFlipCameraButton: true,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'showTorchButton: true,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'torchOn: false,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'prompt: "Place a barcode inside the scan area, then shake your phone",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'resultDisplayDuration: 500,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'//orientation: "landscape",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'disableAnimations: false,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'disableSuccessBeep: false'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var barcodePopup = $ionicPopup.alert({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'title: "Barcode Scanner",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: "Only work in real device!",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
            }
            $this->controllers_js .= $this->enter;
            // TODO: controllers_js --|-- $rootScope.exitApp
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $rootScope.exitApp'.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.exitApp = function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'var confirmPopup = $ionicPopup.confirm({'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'title: "Confirm Exit",'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'template: "Are you sure you want to exit?"'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'confirmPopup.then(function (close){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(close){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'ionic.Platform.exitApp();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.closeMenuPopover();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            // TODO: controllers_js --|-- $rootScope.changeLanguage
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $rootScope.changeLanguage'.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.changeLanguage = function(langKey){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'if(typeof langKey !== null){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$translate.use(langKey);'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'tmhDynamicLocale.set(langKey);'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'try {'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$rootScope.language_option = langKey;'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'localforage.setItem("language_option",langKey);'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}catch(e){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'localforage.setItem("language_option","'.$this->config['app']['locale'].'");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            // TODO: controllers_js --|-- $rootScope.showLanguageDialog
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $rootScope.showLanguageDialog'.$this->enter;
            if(!is_array($this->config['translation']['lang'])) {
                $this->config['translation']['lang'] = array();
            }
            $this->controllers_js .= $this->tab.'var modal_language = "";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_language += "<ion-modal-view>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_language += "<ion-header-bar class=\"bar bar-header bar-'.$this->config['menu']['header_background'].'\">";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_language += "<h1 class=\"title\">{{ \'Language\' | translate }}</h1>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_language += "</ion-header-bar>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_language += "<ion-content class=\"padding\">";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_language += "<div class=\"list\">";'.$this->enter;
            foreach($this->config['translation']['lang'] as $menu) {
                $this->controllers_js .= $this->tab.'modal_language += "<ion-radio icon=\"icon ion-android-radio-button-on\" ng-model=\"language_option\" ng-value=\"\''.$menu['prefix'].'\'\" ng-click=\"tryChangeLanguage(\''.$menu['prefix'].'\')\">'.$menu['label'].'</ion-radio>";'.$this->enter;
            }
            $this->controllers_js .= $this->tab.'modal_language += "<button class=\"button button-full button-'.$this->config['menu']['header_background'].'\" ng-click=\"closeLanguageDialog()\">{{ \'Close\' | translate }}</button>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_language += "</div>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_language += "</ion-content>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_language += "</ion-modal-view>";'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.languageDialog = $ionicModal.fromTemplate(modal_language,{'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'scope: $scope,'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'animation: "slide-in-up"'.$this->enter;
            $this->controllers_js .= $this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.showLanguageDialog = function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'$rootScope.languageDialog.show();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'localforage.getItem("language_option", function(err, value){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.language_option = value;'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}).then(function(value){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.language_option = value;'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}).catch(function (err){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.language_option = "'.$this->config['app']['locale'].'";'.$this->enter;
            //$this->controllers_js .= $this->tab . $this->tab . $this->tab . 'console.log(err);' . $this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'})'.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.closeLanguageDialog = function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'$rootScope.languageDialog.hide();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'$rootScope.closeMenuPopover();'.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.tryChangeLanguage = function(langKey){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'$rootScope.changeLanguage(langKey);'.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'localforage.getItem("language_option", function(err, value){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'if(value === null){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("language_option","'.$this->config['app']['locale'].'");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}else{'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.changeLanguage(value);'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
            $this->controllers_js .= $this->tab.'}).then(function(value){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'if(value === null){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("language_option","'.$this->config['app']['locale'].'");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}else{'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.changeLanguage(value);'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
            $this->controllers_js .= $this->tab.'}).catch(function (err){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'localforage.setItem("language_option","'.$this->config['app']['locale'].'");'.$this->enter;
            $this->controllers_js .= $this->tab.'})'.$this->enter;
            // TODO: controllers_js --|-- $rootScope.changeFontSize
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $rootScope.changeFontSize'.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.changeFontSize = function(fontSize){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'if(typeof fontSize !== null){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'try {'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$rootScope.fontsize_option = $rootScope.fontsize = fontSize;'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'localforage.setItem("fontsize_option",fontSize);'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}catch(e){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'localforage.setItem("fontsize_option","normal");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $rootScope.showFontSizeDialog'.$this->enter;
            $this->controllers_js .= $this->tab.'var modal_fontsize = "";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "<ion-modal-view>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "<ion-header-bar class=\"bar bar-header bar-'.$this->config['menu']['header_background'].'\">";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "<h1 class=\"title\">{{ \'Font Size\' | translate }}</h1>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "</ion-header-bar>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "<ion-content class=\"padding\">";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "<div class=\"list\">";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "<ion-radio icon=\"icon ion-android-radio-button-on\" ng-model=\"fontsize_option\" ng-value=\"\'small\'\" ng-click=\"tryChangeFontSize(\'small\');\">{{ \'Small\' | translate }}</ion-radio>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "<ion-radio icon=\"icon ion-android-radio-button-on\" ng-model=\"fontsize_option\" ng-value=\"\'normal\'\" ng-click=\"tryChangeFontSize(\'normal\');\">{{ \'Normal\' | translate }}</ion-radio>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "<ion-radio icon=\"icon ion-android-radio-button-on\" ng-model=\"fontsize_option\" ng-value=\"\'large\'\" ng-click=\"tryChangeFontSize(\'large\');\">{{ \'Large\' | translate }}</ion-radio>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "<button class=\"button button-full button-'.$this->config['menu']['header_background'].'\" ng-click=\"closeFontSizeDialog()\">{{ \'Close\' | translate }}</button>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "</div>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "</ion-content>";'.$this->enter;
            $this->controllers_js .= $this->tab.'modal_fontsize += "</ion-modal-view>";'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.fontSizeDialog = $ionicModal.fromTemplate(modal_fontsize,{'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'scope: $scope,'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'animation: "slide-in-up"'.$this->enter;
            $this->controllers_js .= $this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.showFontSizeDialog = function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'$rootScope.fontSizeDialog.show();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'localforage.getItem("fontsize_option", function(err, value){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.fontsize_option = $rootScope.fontsize = value;'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}).then(function(value){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.fontsize_option = $rootScope.fontsize = value;'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}).catch(function (err){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.fontsize_option = $rootScope.fontsize = "normal";'.$this->enter;
            //$this->controllers_js .= $this->tab . $this->tab . $this->tab . 'console.log(err);' . $this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'})'.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.closeFontSizeDialog = function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'$rootScope.fontSizeDialog.hide();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'$rootScope.closeMenuPopover();'.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'localforage.getItem("fontsize_option", function(err, value){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'if(value === null){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("fontsize_option","normal");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}else{'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.changeFontSize(value);'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
            $this->controllers_js .= $this->tab.'}).then(function(value){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'if(value === null){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("fontsize_option","normal");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}else{'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.changeFontSize(value);'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
            $this->controllers_js .= $this->tab.'}).catch(function (err){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'console.log(err);'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'localforage.setItem("fontsize_option","normal");'.$this->enter;
            $this->controllers_js .= $this->tab.'})'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.tryChangeFontSize = function(val){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'$rootScope.changeFontSize(val);'.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            if(!isset($this->config['push']['plugin'])) {
                $this->config['push']['plugin'] = 'none';
            }
            if($this->config['push']['plugin'] !== 'none') {
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $rootScope.modal_notification'.$this->enter;
                $this->controllers_js .= $this->tab.'var modal_notification = "";'.$this->enter;
                $this->controllers_js .= $this->tab.'$rootScope.disable_notification_option = false;'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "<ion-modal-view>";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "<ion-header-bar class=\"bar bar-header bar-'.$this->config['menu']['header_background'].'\">";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "<h1 class=\"title\">{{ \'Notifications\' | translate }}</h1>";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "</ion-header-bar>";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "<ion-content class=\"\">";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "<div class=\"list\">";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "<ion-toggle ng-model=\"disable_notification_option\"  ng-click=\"tryChangeNotification(disable_notification_option)\">";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "{{ \'Disable Alerts\' | translate }}";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "</ion-toggle>";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "<div class=\"item\">";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "<button class=\"button button-full button-'.$this->config['menu']['header_background'].'\" ng-click=\"closeNotificationDialog()\">{{ \'Close\' | translate }}</button>";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "</div>";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "</div>";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "</ion-content>";'.$this->enter;
                $this->controllers_js .= $this->tab.'modal_notification += "</ion-modal-view>";'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                // TODO: controllers_js --|-- $rootScope.notificationDialog
                $this->controllers_js .= $this->tab.'$rootScope.notificationDialog = $ionicModal.fromTemplate(modal_notification,{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'scope: $scope,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'animation: "slide-in-up"'.$this->enter;
                $this->controllers_js .= $this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                // TODO: controllers_js --|-- $rootScope.showNotificationDialog
                $this->controllers_js .= $this->tab.'$rootScope.showNotificationDialog = function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'get_notification();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$rootScope.notificationDialog.show();'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
                // TODO: controllers_js --|-- $rootScope.closeNotificationDialog
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'$rootScope.closeNotificationDialog = function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$rootScope.notificationDialog.hide();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$rootScope.closeMenuPopover();'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'var get_notification =  function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'localforage.getItem("disable_notification_option", function(err, value){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'var notification_value = false ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(value === null){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'notification_value = false ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(value === true){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'notification_value = true ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'notification_value = false ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("disable_notification_option",notification_value);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.disable_notification_option = notification_value ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}).then(function(value){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'var notification_value = false ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(value === null){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'notification_value = false ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(value === true){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'notification_value = true ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'notification_value = false ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("disable_notification_option",notification_value);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.disable_notification_option = notification_value ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}).catch(function (err){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("disable_notification_option",false);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.disable_notification_option = false ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'})'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'get_notification();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'$rootScope.tryChangeNotification = function(val){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$rootScope.changeNotification(val);'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'$rootScope.changeNotification = function(val){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$rootScope.disable_notification_option = val;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'localforage.setItem("disable_notification_option",val);'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.$watch("disable_notification_option", function (newValue, oldValue, scope) {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'if(window.plugins && window.plugins.OneSignal){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(newValue == true){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.plugins.OneSignal.setSubscription(false);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.plugins.OneSignal.setSubscription(true);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
            }
        }
        if($this->str2var($page['prefix']) == 'index') {
            // TODO: controllers_js --|-- $rootScope.clearCacheApp
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $rootScope.clearCacheApp'.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.clearCacheApp = function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'var confirmPopup = $ionicPopup.confirm({'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'title: "Confirm",'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'template: "Are you sure you want to clear cache?"'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'confirmPopup.then(function (close){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(close){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'localforage.keys().then(function(keys) {'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'for(var e = 0; e < keys.length ; e++) {'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'localforage.setItem(keys[e],[]);'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$state.go("'.$this->config['app']['prefix'].'.'.$this->config['app']['index'].'");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}).catch(function(err) {'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$state.go("'.$this->config['app']['prefix'].'.'.$this->config['app']['index'].'");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$rootScope.closeMenuPopover();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
        }
        if($this->gmap == true) {
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $rootScope.mapEnable'.$this->enter;
            $this->controllers_js .= $this->tab.'if(typeof google == "undefined"){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'$rootScope.mapEnable = false;'.$this->enter;
            $this->controllers_js .= $this->tab.'}else{'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'$rootScope.mapEnable = true;'.$this->enter;
            $this->controllers_js .= $this->tab.'}'.$this->enter;
        }
        $this->controllers_js .= $this->tab.'$rootScope.last_edit = "'.$page['last_edit_by'].'" ;'.$this->enter;
        $this->controllers_js .= $this->tab.'$scope.$on("$ionicView.afterEnter", function (){'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'var page_id = $state.current.name ;'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'$rootScope.page_id = page_id.replace(".","-") ;'.$this->enter;
        $this->controllers_js .= $this->tab.'});'.$this->enter;
        $this->controllers_js .= $this->tab.'if($rootScope.headerShrink == true){'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'$scope.$on("$ionicView.enter", function(){'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.scrollTop();'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->controllers_js .= $this->tab.'};'.$this->enter;
        // TODO: controllers_js ------|-- $scope.scrollTop
        $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.scrollTop'.$this->enter;
        $this->controllers_js .= $this->tab.'$rootScope.scrollTop = function(){'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'$timeout(function(){'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$ionicScrollDelegate.$getByHandle("top").scrollTop();'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'},100);'.$this->enter;
        $this->controllers_js .= $this->tab.'};'.$this->enter;
        if($this->str2var($page['prefix']) == 'index') {
            // TODO: controllers_js ------|-- $rootScope.openURL
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.openURL'.$this->enter;
            $this->controllers_js .= $this->tab.'// open external browser '.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.openURL = function($url){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'window.open($url,"_system","location=yes");'.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            // TODO: controllers_js ------|-- $rootScope.openAppBrowser
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.openAppBrowser'.$this->enter;
            $this->controllers_js .= $this->tab.'// open AppBrowser'.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.openAppBrowser = function($url){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'var appBrowser = window.open($url,"_blank","hardwareback=Done,hardwareback=Done,toolbarposition=top,location=yes");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'appBrowser.addEventListener("loadstart",function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStart("Please Wait", "Its loading....");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'appBrowser.addEventListener("loadstop",function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'appBrowser.addEventListener("loaderror",function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'window.location = "retry.html";'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'appBrowser.addEventListener("exit",function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            // TODO: controllers_js ------|-- $rootScope.openWebView
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.openWebView'.$this->enter;
            $this->controllers_js .= $this->tab.'// open WebView'.$this->enter;
            $this->controllers_js .= $this->tab.'$rootScope.openWebView = function($url){'.$this->enter;
            //$this->controllers_js .= $this->tab . $this->tab . 'var appWebview = window.open($url,"_self");' . $this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'var appWebview = window.open($url,"_blank","location=no,toolbar=no");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'appWebview.addEventListener("loadstart",function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStart("Please Wait", "Its loading....");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'appWebview.addEventListener("loadstop",function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'appWebview.addEventListener("loaderror",function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'window.location = "retry.html";'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'appWebview.addEventListener("exit",function(){'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'navigator.notification.activityStop();'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'};'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
        }
        $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.toggleGroup'.$this->enter;
        $this->controllers_js .= $this->tab.'$scope.toggleGroup = function(group) {'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'if ($scope.isGroupShown(group)) {'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.shownGroup = null;'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'} else {'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.shownGroup = group;'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->controllers_js .= $this->tab.'};'.$this->enter;
        $this->controllers_js .= $this->tab.$this->enter;
        $this->controllers_js .= $this->tab.'$scope.isGroupShown = function(group) {'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'return $scope.shownGroup === group;'.$this->enter;
        $this->controllers_js .= $this->tab.'};'.$this->enter;
        $this->controllers_js .= $this->tab.$this->enter;
        // TODO: controllers_js ------|-- $scope.redirect
        $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.redirect'.$this->enter;
        $this->controllers_js .= $this->tab.'// redirect'.$this->enter;
        $this->controllers_js .= $this->tab.'$scope.redirect = function($url){'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'$window.location.href = $url;'.$this->enter;
        if(IONIC_DEBUG == true) {
            $this->controllers_js .= $this->tab.$this->tab.'console.log("Ready to redirect");'.$this->enter;
        }
        $this->controllers_js .= $this->tab.'};'.$this->enter;
        if(!isset($page['button_back'])) {
            $page['button_back'] = '';
        }
        if($page['button_back'] == 'disable') {
            // TODO: controllers_js ------|-- $ionicHistory
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- disable back button'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'$ionicHistory.nextViewOptions({'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'disableAnimate: true,'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'disableBack: true'.$this->enter;
            $this->controllers_js .= $this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
        }
        if($page['button_back'] == 'enable') {
            // TODO: controllers_js ------|-- $ionicHistory
            $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- disable back button'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'$ionicHistory.nextViewOptions({'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'disableAnimate: false,'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.'disableBack: false'.$this->enter;
            $this->controllers_js .= $this->tab.'});'.$this->enter;
            $this->controllers_js .= $this->tab.$this->enter;
        }
        $this->controllers_js .= $this->tab.$this->enter;
        $this->controllers_js .= $this->tab.'// Set Motion'.$this->enter;
        $this->controllers_js .= $this->tab.'$timeout(function(){'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'ionicMaterialMotion.slideUp({'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'selector: ".slide-up"'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
        $this->controllers_js .= $this->tab.'}, 300);'.$this->enter;
        foreach($this->tables as $tables) {
            if(!isset($tables['max_items'])) {
                $tables['max_items'] = 100;
            }
            if(!is_numeric($tables['max_items'])) {
                $tables['max_items'] = 100;
            }
            $max_items = (int)$tables['max_items'];
            if(!isset($tables['languages']['error_auto_close'])) {
                $tables['languages']['error_auto_close'] = true;
            }
            if(!isset($tables['localstorage'])) {
                $tables['localstorage'] = 'localstorage';
            }
            if(!isset($tables['languages']['retrieval_error_title'])) {
                $tables['languages']['retrieval_error_title'] = 'Error';
            }
            if(!isset($tables['languages']['retrieval_error_content'])) {
                $tables['languages']['retrieval_error_content'] = 'An error occurred while collecting data';
            }
            if(!isset($tables['languages']['error_messages'])) {
                $tables['languages']['error_messages'] = 'false';
            }
            if($tables['languages']['error_messages'] == 'true') {
                $retrieval_error_title = '"'.$tables['languages']['retrieval_error_title'].'" + " (" + data.status + ")"';
                $retrieval_error_content = '"'.$tables['languages']['retrieval_error_content'].'" + "<br/><br/><pre>code: " + data.status + "<br/>error: " + data.statusText + "<br/>source: " + $rootScope.last_edit + "</pre>"';
            } else {
                $retrieval_error_title = '"'.$tables['languages']['retrieval_error_title'].'" + " (" + data.status + ")"';
                $retrieval_error_content = '"'.$tables['languages']['retrieval_error_content'].'"';
            }
            if(!isset($tables['auth']['type'])) {
                $tables['auth']['type'] = 'none';
            }
            if(!isset($tables['auth']['consumer_key'])) {
                $tables['auth']['consumer_key'] = '';
            }
            if(!isset($tables['auth']['consumer_secret'])) {
                $tables['auth']['consumer_secret'] = '';
            }
            if(!isset($tables['http_header'])) {
                $tables['http_header'] = array();
            }
            $db_var = $tables['db_var'];
            // TODO:  controllers_js --|-- datalisting
            if($tables['parent'] == $this->str2var($page['prefix'])) {
                $this->authorization($tables,$page);
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'// set default parameter http'.$this->enter;
                $this->controllers_js .= $this->tab.'var http_params = {};'.$this->enter;
                if(!isset($tables['option']['youtube']['api_key'])) {
                    $tables['option']['youtube']['api_key'] = '';
                }
                if($tables['option']['youtube']['api_key'] !== '') {
                    $this->controllers_js .= $this->tab.'http_params = {maxResults:10,part:"id,snippet",type:"video",key: "'.$tables['option']['youtube']['api_key'].'"};'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'// set HTTP Header '.$this->enter;
                $this->controllers_js .= $this->tab.'var http_header = {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'headers: {'.$this->enter;
                foreach($tables['http_header'] as $http_headers) {
                    if(strlen($http_headers['var']) > 2) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'"'.htmlentities($http_headers['var']).'": "'.htmlentities($http_headers['val']).'",'.$this->enter;
                    }
                }
                $this->controllers_js .= $this->tab.$this->tab.'},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'params: http_params'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
                // TODO:  controllers_js ------|-- dinamic pagelisting
                $text_param = '';
                $db_param = parse_url($tables['db_url']);
                if(!isset($db_param['query'])) {
                    $db_param['query'] = '';
                }
                $_param = explode('&',$db_param['query']);
                $param = explode('=',$_param[0]);
                if(isset($param[0])) {
                    if(strlen($param[0]) > 0) {
                        $text_param = $param[0];
                    }
                    if(isset($param[1])) {
                        $text_param .= '='.$param[1];
                    }
                }
                if(isset($page['query'])) {
                    if(is_array($page['query'])) {
                        foreach($page['query'] as $query_id) {
                            $__value = explode('=',$text_param);
                            $query .= $this->tab.$this->enter;
                            $query .= $this->tab.'$scope.first_param = {};'.$this->enter;
                            $query .= $this->tab.'$scope.first_param.'.$this->str2var($query_id).' = "'.$__value[1].'";'.$this->enter;
                            $query .= $this->tab.'if(typeof $stateParams.'.$this->str2var($query_id)." !== 'undefined'){".$this->enter;
                            if($tables['localstorage'] == 'localstorage') {
                                $query .= $this->tab.$this->tab.'window.localStorage.clear();'.$this->enter;
                            }
                            $query .= $this->tab.$this->tab.'raplaceWithQuery = "'.$query_id.'=" + $stateParams.'.$this->str2var($query_id).";".$this->enter;
                            $query .= $this->tab.$this->tab.'$scope.first_param.'.$this->str2var($query_id).' = $stateParams.'.$this->str2var($query_id).";".$this->enter;
                            $query .= $this->tab.'}'.$this->enter;
                            $query .= $this->tab.'if(typeof $rootScope.'.$this->str2var($page['prefix']).'QueryParam !== "undefined"){'.$this->enter;
                            $query .= $this->tab.$this->tab.'raplaceWithQuery = "'.$query_id.'=" +  $rootScope.'.$this->str2var($page['prefix']).'QueryParam ;'.$this->enter;
                            $query .= $this->tab.'}'.$this->enter;
                            $query .= $this->tab.'if($scope.first_param.'.$this->str2var($query_id).'=="-1"){'.$this->enter;
                            $query .= $this->tab.$this->tab.'$scope.first_param.'.$this->str2var($query_id).' = "";'.$this->enter;
                            $query .= $this->tab.'}'.$this->enter;
                        }
                    }
                }
                $this->controllers_js .= $this->tab.'var targetQuery = ""; //default param'.$this->enter;
                $this->controllers_js .= $this->tab.'var raplaceWithQuery = "";'.$this->enter;
                if($text_param != '') {
                    $this->controllers_js .= $this->tab.'//fix url '.$page['title'].$this->enter;
                    $this->controllers_js .= $this->tab.'targetQuery = "'.$text_param.'"; //default param'.$this->enter;
                    $this->controllers_js .= $this->tab.'raplaceWithQuery = "'.$text_param.'";'.$this->enter;
                    $this->controllers_js .= $this->tab.$query.$this->enter;
                    // $this->controllers_js .= $this->tab . 'console.log(targetQuery,raplaceWithQuery);' . $this->enter;
                }
                $_tables['template'] = $tables['template'];
                if($tables['template'] == 'slidebox-1') {
                    $tables['template'] = 'slidebox';
                }
                $template_none = array(
                    'homepage1',
                    'dictionary',
                    'image-reader',
                    'gmapmarker',
                    'youtube',
                    'manual_coding',
                    'external',
                    'homepage2',
                    'table',
                    'avatar',
                    '2-icon',
                    'faqs',
                    '1-icon',
                    'thumbnail',
                    'thumbnail-1',
                    'thumbnail-2',
                    'thumbnail-3',
                    'showcase',
                    'line-chart',
                    'line-bar',
                    'line-doughnut',
                    'button');
                if(in_array($tables['template'],$template_none)) {
                    $tables['template'] = 'none';
                }
                $this->controllers_js .= $this->tab.$this->enter;
                // TODO: controllers_js ------|-- $scope.splitArray
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.splitArray'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.splitArray = function(items,cols,maxItem) {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'var newItems = [];'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'if(maxItem == 0){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'maxItem = items.length;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'if(items){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'for (var i=0; i < maxItem; i+=cols) {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'newItems.push(items.slice(i, i+cols));'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'return newItems;'.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
                if(!isset($tables['bookmarks'])) {
                    $tables['bookmarks'] = 'none';
                }
                if(($tables['bookmarks'] == 'cart') || ($tables['bookmarks'] == 'bookmark')) {
                    if(!isset($query_id)) {
                        $query_id = 'id';
                        ;
                    }
                    if($query_id == '') {
                        $query_id = 'id';
                    }
                    // TODO: controllers_js ----------|-- $scope.addToDbVirtual
                    $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.addToVirtual'.$this->enter;
                    $this->controllers_js .= $this->tab.'$scope.addToDbVirtual = function(newItem){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'var is_already_exist = false ;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'// animation loading '.$this->enter;
                    // TODO: controllers_js ------------|-- $ionicLoading.show
                    if(IONIC_LOADING == true) {
                        $this->controllers_js .= $this->tab.$this->tab.'$ionicLoading.show();'.$this->enter;
                    } else {
                        $this->controllers_js .= $this->tab.$this->tab.'$ionicLoading.show({'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\''.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.'var virtual_items = []; '.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'localforage.getItem("'.$tables['prefix'].'_'.$tables['bookmarks'].'", function(err,dbVirtual){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(dbVirtual === null){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'virtual_items = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var last_items = JSON.parse(dbVirtual); '.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}catch(e){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var last_items = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'for(var z=0;z<last_items.length;z++){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'virtual_items.push(last_items[z]);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if(newItem.'.$query_id.' ==  last_items[z].'.$query_id.'){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'is_already_exist = true;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}).then(function(value){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(is_already_exist === false){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'newItem["_qty"]=1;'.$this->enter;
                    if($tables['bookmarks'] == 'cart') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'newItem["_sum"]=newItem.'.$tables['column-for-price'].';'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'virtual_items.push(newItem);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("'.$tables['prefix'].'_'.$tables['bookmarks'].'",JSON.stringify(virtual_items));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},200);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}).catch(function(err){'.$this->enter;
                    if(IONIC_DEBUG == true) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'console.log("indexDB: ",err);'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'virtual_items = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'virtual_items.push(newItem);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("'.$tables['prefix'].'_'.$tables['bookmarks'].'",JSON.stringify(virtual_items));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},200);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'})'.$this->enter;
                    $this->controllers_js .= $this->tab.'};'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->enter;
                }
                $this->controllers_js .= $this->tab.'$scope.gmapOptions = {options: { scrollwheel: false }};'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                if(!isset($tables['fetch_per_scroll'])) {
                    $tables['fetch_per_scroll'] = 3;
                }
                if(!is_numeric($tables['fetch_per_scroll'])) {
                    $tables['fetch_per_scroll'] = 3;
                }
                $this->controllers_js .= $this->tab.'var fetch_per_scroll = '.$tables['fetch_per_scroll'].';'.$this->enter;
                $this->controllers_js .= $this->tab.'// animation loading '.$this->enter;
                if(IONIC_LOADING == true) {
                    $this->controllers_js .= $this->tab.'$ionicLoading.show();'.$this->enter;
                } else {
                    $this->controllers_js .= $this->tab.'$ionicLoading.show({'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\''.$this->enter;
                    $this->controllers_js .= $this->tab.'});'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                if($tables['db_type'] == 'online') {
                    $url = $tables['db_url'];
                } else {
                    $url = 'data/tables/'.strtolower($this->str2var($tables['title'])).'.json';
                }
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.fetchURL'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.fetchURL = "'.$url.'";'.$this->enter;
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.fetchURLp'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.fetchURLp = "'.$this->url_param($url,'callback=JSON_CALLBACK').'";'.$this->enter;
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.hashURL'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.hashURL = md5.createHash( $scope.fetchURL.replace(targetQuery,raplaceWithQuery));'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.noMoreItemsAvailable = false; //readmore status'.$this->enter;
                $this->controllers_js .= $this->tab.'var lastPush = 0;'.$this->enter;
                $this->controllers_js .= $this->tab.'var data_'.$this->str2var($tables['prefix']).'s = [];'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                if($tables['localstorage'] == 'localstorage') {
                    $this->controllers_js .= $this->tab.'if(window.localStorage.getItem("data_'.$this->str2var($tables['prefix']).'s") !== "undefined"){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'data_'.$this->str2var($tables['prefix']).'s = JSON.parse(window.localStorage.getItem("data_'.$this->str2var($tables['prefix']).'s"));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'$scope.data_'.$this->str2var($tables['prefix']).'s = JSON.parse(window.localStorage.getItem("data_'.$this->str2var($tables['prefix']).'s"));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if (data_'.$this->str2var($tables['prefix']).'s !== null){'.$this->enter;
                    if($tables['template'] == 'none') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s = [];'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'for(lastPush = 0; lastPush < '.$max_items.'; lastPush++) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (angular.isObject(data_'.$this->str2var($tables['prefix']).'s[lastPush])){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s.push(data_'.$this->str2var($tables['prefix']).'s[lastPush]);'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                    if($tables['template'] == 'gallery') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if(data_'.$this->str2var($tables['prefix']).'s){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.chunked_'.$this->str2var($tables['prefix']).'s = $scope.splitArray(data_'.$this->str2var($tables['prefix']).'s, $rootScope.grid128,0);'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    }
                    if($tables['template'] == 'slidebox') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s = data_'.$this->str2var($tables['prefix']).'s;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicSlideBoxDelegate.update();'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}, 500);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.'}'.$this->enter;
                }
                if($tables['localstorage'] == 'localforage') {
                    $this->controllers_js .= $this->tab.'localforage.getItem("data_'.$this->str2var($tables['prefix']).'s_" + $scope.hashURL, function(err, get_'.$this->str2var($tables['prefix']).'s){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'if(get_'.$this->str2var($tables['prefix']).'s === null){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'data_'.$this->str2var($tables['prefix']).'s =[];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}else{'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'data_'.$this->str2var($tables['prefix']).'s = JSON.parse(get_'.$this->str2var($tables['prefix']).'s);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.data_'.$this->str2var($tables['prefix']).'s =JSON.parse( get_'.$this->str2var($tables['prefix']).'s);'.$this->enter;
                    if($tables['template'] == 'none') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s = [];'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'for(lastPush = 0; lastPush < '.$max_items.'; lastPush++) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (angular.isObject(data_'.$this->str2var($tables['prefix']).'s[lastPush])){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s.push(data_'.$this->str2var($tables['prefix']).'s[lastPush]);'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                    if($tables['template'] == 'gallery') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if(data_'.$this->str2var($tables['prefix']).'s){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.chunked_'.$this->str2var($tables['prefix']).'s = $scope.splitArray(data_'.$this->str2var($tables['prefix']).'s, $rootScope.grid128,0);'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    }
                    if($tables['template'] == 'slidebox') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s = data_'.$this->str2var($tables['prefix']).'s;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicSlideBoxDelegate.update();'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},200);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.'}).then(function(value){'.$this->enter;
                    if(IONIC_DEBUG == true) {
                        $this->controllers_js .= $this->tab.$this->tab.'console.log("forage: promise: ",value);'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.'}).catch(function (err){'.$this->enter;
                    if(IONIC_DEBUG == true) {
                        $this->controllers_js .= $this->tab.$this->tab.'console.log("forage: " + $scope.hashURL  + ":" + err);'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.'})'.$this->enter;
                }
                // $this->controllers_js .= $this->tab . $this->tab . 'console.log("data", data_' . $this->str2var($tables['prefix']) . 's);' . $this->enter;
                $this->controllers_js .= $this->tab.'if(data_'.$this->str2var($tables['prefix']).'s === null ){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'data_'.$this->str2var($tables['prefix']).'s =[];'.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.'if(data_'.$this->str2var($tables['prefix']).'s.length === 0 ){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'var url_request = $scope.fetchURL.replace(targetQuery,raplaceWithQuery);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// overwrite HTTP Header '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'http_header = {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'headers: {'.$this->enter;
                foreach($tables['http_header'] as $http_headers) {
                    if(strlen($http_headers['var']) > 2) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'"'.htmlentities($http_headers['var']).'": "'.htmlentities($http_headers['val']).'",'.$this->enter;
                    }
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'params: http_params'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
                // TODO:  controllers_js ----------|-- $http.get
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $http.get'.$this->enter;
                if($tables['languages']['error_messages'] == 'true') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'console.log("%cRetrieving JSON: %c" + url_request,"color:blue;font-size:18px","color:red;font-size:18px");'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$http.get(url_request,http_header).then(function(response) {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'data_'.$this->str2var($tables['prefix']).'s = response.data'.$db_var.';'.$this->enter;
                if($tables['languages']['error_messages'] == 'true') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'console.log("%cSuccessfully","color:blue;font-size:18px");'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'console.dir(data_'.$this->str2var($tables['prefix']).'s);'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.data_'.$this->str2var($tables['prefix']).'s = response.data'.$db_var.';'.$this->enter;
                // TODO:  controllers_js --------------|-- localstorage
                if($tables['localstorage'] == 'localstorage') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if(typeof(Storage) != "undefined"){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try {'.$this->enter;
                    // TODO:  controllers_js --------------|------ set localstorage
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localstorage'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'} catch(e) {'.$this->enter;
                    // TODO:  controllers_js --------------|------ clear localstorage
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- clear:localstorage'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.clear();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearCache();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearHistory();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$state.reload();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.$state = $state;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                // TODO:  controllers_js --------------|-- localforage
                if($tables['localstorage'] == 'localforage') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'s_" + $scope.hashURL, JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                }
                if($tables['template'] == 'none') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'for(lastPush = 0; lastPush < '.$max_items.'; lastPush++) {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if (angular.isObject(data_'.$this->str2var($tables['prefix']).'s[lastPush])){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s.push(data_'.$this->str2var($tables['prefix']).'s[lastPush]);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},function(response) {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var url_request = $scope.fetchURLp.replace(targetQuery,raplaceWithQuery);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// overwrite HTTP Header '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'http_header = {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'headers: {'.$this->enter;
                foreach($tables['http_header'] as $http_headers) {
                    if(strlen($http_headers['var']) > 2) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'"'.htmlentities($http_headers['var']).'": "'.htmlentities($http_headers['val']).'",'.$this->enter;
                    }
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'params: http_params'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
                // TODO:  controllers_js ----------|-- $http.jsonp
                if($tables['languages']['error_messages'] == 'true') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'console.log("%cRetrieving again: %c" + url_request,"color:blue;font-size:18px","color:red;font-size:18px");'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|------ $http.jsonp'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$http.jsonp(url_request,http_header).success(function(data){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'data_'.$this->str2var($tables['prefix']).'s = data'.$db_var.';'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.data_'.$this->str2var($tables['prefix']).'s = data'.$db_var.';'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                // TODO:  controllers_js --------------|-- localstorage
                if($tables['localstorage'] == 'localstorage') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if(typeof(Storage) != "undefined"){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try {'.$this->enter;
                    // TODO: controllers_js --------------|------ set localstorage
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------------- set:localstorage'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'} catch(e) {'.$this->enter;
                    // TODO: controllers_js --------------|------ clear localstorage
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------------- clear:localstorage'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.clear();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearCache();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearHistory();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$state.reload();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.$state = $state;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                // TODO:  controllers_js --------------|-- localforage
                if($tables['localstorage'] == 'localforage') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'s_" + $scope.hashURL,JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                if($tables['template'] == 'none') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'for(lastPush = 0; lastPush < '.$max_items.'; lastPush++) {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if (angular.isObject(data_'.$this->str2var($tables['prefix']).'s[lastPush])){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s.push(data_'.$this->str2var($tables['prefix']).'s[lastPush]);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}).error(function(data){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if(response.status ===401){'.$this->enter;
                // TODO: controllers_js --------------|-- error : Unauthorized
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|------------ error:Unauthorized'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.showAuthentication();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                // TODO: controllers_js --------------|-- error : Message
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|------------ error:Message'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var data = { statusText:response.statusText, status:response.status };'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var alertPopup = $ionicPopup.alert({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'title: '.$retrieval_error_title.','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: '.$retrieval_error_content.','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                if($tables['languages']['error_auto_close'] == true) {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'alertPopup.close();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}, 2000);'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}, 200);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}).finally(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.$broadcast("scroll.refreshComplete");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// event done, hidden animation loading'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                // TODO: force auth
                if(($tables['auth']['type'] == 'basic') || ($tables['auth']['type'] == 'x-basic') || ($tables['auth']['type'] == 'none')) {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if(angular.isDefined($scope.data_'.$this->str2var($tables['prefix']).'s.data)){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if($scope.data_'.$this->str2var($tables['prefix']).'s.data.status ===401){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.showAuthentication();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'return false;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                if($tables['template'] == 'gallery') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.chunked_'.$this->str2var($tables['prefix']).'s = $scope.splitArray(data_'.$this->str2var($tables['prefix']).'s, $rootScope.grid128,0);'.$this->enter;
                }
                if($tables['template'] == 'slidebox') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s = data_'.$this->str2var($tables['prefix']).'s;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicSlideBoxDelegate.update();'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}, 200);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}, 200);'.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                // TODO: controllers_js ------|-- $scope.doRefresh
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.doRefresh'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.doRefresh = function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'var url_request = $scope.fetchURL.replace(targetQuery,raplaceWithQuery);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'// retry retrieving data'.$this->enter;
                if($tables['localstorage'] == 'localstorage') {
                    $this->controllers_js .= $this->tab.$this->tab.'window.localStorage.clear();'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.'// overwrite http_header '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'http_header = {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'headers: {'.$this->enter;
                foreach($tables['http_header'] as $http_headers) {
                    if(strlen($http_headers['var']) > 2) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'"'.htmlentities($http_headers['var']).'": "'.htmlentities($http_headers['val']).'",'.$this->enter;
                    }
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'params: http_params'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'};'.$this->enter;
                // TODO:  controllers_js ----------|-- $http.get
                $this->controllers_js .= $this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|------ $http.get'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$http.get(url_request,http_header).then(function(response) {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'data_'.$this->str2var($tables['prefix']).'s = response.data'.$db_var.';'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.data_'.$this->str2var($tables['prefix']).'s = response.data'.$db_var.';'.$this->enter;
                if(IONIC_DEBUG == true) {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'console.log("retry retrieving data =>" + data_'.$this->str2var($tables['prefix']).'s  );'.$this->enter;
                }
                // TODO:  controllers_js --------------|-- localstorage
                if($tables['localstorage'] == 'localstorage') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(typeof(Storage) != "undefined"){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try {'.$this->enter;
                    // TODO:  controllers_js --------------|------ set localstorage
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------------- set:localstorage'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'} catch(e) {'.$this->enter;
                    // TODO: controllers_js --------------|------ clear localstorage
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------------- clear:localstorage'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.clear();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearCache();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearHistory();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$state.reload();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.$state = $state;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                // TODO:  controllers_js --------------|-- localforage
                if($tables['localstorage'] == 'localforage') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'s_" + $scope.hashURL,JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                }
                if($tables['template'] == 'none') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'for(lastPush = 0; lastPush < '.$max_items.'; lastPush++) {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if (angular.isObject(data_'.$this->str2var($tables['prefix']).'s[lastPush])){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s.push(data_'.$this->str2var($tables['prefix']).'s[lastPush]);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.'},function(response){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'// retrieving data with jsonp'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'var url_request =$scope.fetchURLp.replace(targetQuery,raplaceWithQuery);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// overwrite http_header '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'http_header = {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'headers: {'.$this->enter;
                foreach($tables['http_header'] as $http_headers) {
                    if(strlen($http_headers['var']) > 2) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'"'.htmlentities($http_headers['var']).'": "'.htmlentities($http_headers['val']).'",'.$this->enter;
                    }
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'params: http_params'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
                // TODO:  controllers_js ----------|-- $http.jsonp
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- $http.jsonp'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$http.jsonp(url_request,http_header).success(function(data){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'data_'.$this->str2var($tables['prefix']).'s = data'.$db_var.';'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.data_'.$this->str2var($tables['prefix']).'s = data'.$db_var.';'.$this->enter;
                if(IONIC_DEBUG == true) {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'console.log("retrieving data with jsonp =>" + data_'.$this->str2var($tables['prefix']).'s  );'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                // TODO:  controllers_js --------------|-- online
                if($tables['localstorage'] == 'localstorage') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if(typeof(Storage) != "undefined"){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'try {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------------- set:localstorage'.$this->enter;
                    // TODO:  controllers_js --------------|------ set localstorage
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'} catch(e) {'.$this->enter;
                    // TODO: controllers_js --------------|------ clear localstorage
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------------- clear:localstorage'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.clear();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearCache();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearHistory();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$state.reload();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.$state = $state;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                // TODO:  controllers_js --------------|-- localforage
                if($tables['localstorage'] == 'localforage') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'s_"+ $scope.hashURL,JSON.stringify(data_'.$this->str2var($tables['prefix']).'s));'.$this->enter;
                }
                if($tables['template'] == 'none') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'for(lastPush = 0; lastPush < '.$max_items.'; lastPush++) {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if (angular.isObject(data_'.$this->str2var($tables['prefix']).'s[lastPush])){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s.push(data_'.$this->str2var($tables['prefix']).'s[lastPush]);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}).error(function(resp){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if(response.status ===401){'.$this->enter;
                // TODO: controllers_js --------------|-- error : Unauthorized
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|------------ error:Unauthorized'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.showAuthentication();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                // TODO: controllers_js --------------|-- error : Message
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|------------ error:Message'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var data = { statusText:response.statusText, status:response.status };'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var alertPopup = $ionicPopup.alert({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'title: '.$retrieval_error_title.','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: '.$retrieval_error_content.','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'};'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}, 200);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}).finally(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.$broadcast("scroll.refreshComplete");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// event done, hidden animation loading'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                // TODO: force auth
                if($tables['auth']['type'] == 'basic') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if(angular.isDefined($scope.data_'.$this->str2var($tables['prefix']).'s.data)){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if($scope.data_'.$this->str2var($tables['prefix']).'s.data.status ===401){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.showAuthentication();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'return false;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                if($tables['template'] == 'gallery') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if(data_'.$this->str2var($tables['prefix']).'s){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.chunked_'.$this->str2var($tables['prefix']).'s = $scope.splitArray(data_'.$this->str2var($tables['prefix']).'s, $rootScope.grid128,0);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                if($tables['template'] == 'slidebox') {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if(data_'.$this->str2var($tables['prefix']).'s){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s = data_'.$this->str2var($tables['prefix']).'s;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicSlideBoxDelegate.update();'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}, 500);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
                // end
                if($tables['template'] == 'none') {
                    $this->controllers_js .= $this->tab.'if (data_'.$this->str2var($tables['prefix']).'s === null){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'data_'.$this->str2var($tables['prefix']).'s = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.'};'.$this->enter;
                    $this->controllers_js .= $this->tab.'// animation readmore'.$this->enter;
                    $this->controllers_js .= $this->tab.'var fetchItems = function() {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'for(var z=0;z<fetch_per_scroll;z++){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if (angular.isObject(data_'.$this->str2var($tables['prefix']).'s[lastPush])){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).'s.push(data_'.$this->str2var($tables['prefix']).'s[lastPush]);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'lastPush++;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}else{;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.noMoreItemsAvailable = true;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'$scope.$broadcast("scroll.infiniteScrollComplete");'.$this->enter;
                    $this->controllers_js .= $this->tab.'};'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->enter;
                    $this->controllers_js .= $this->tab.'// event readmore'.$this->enter;
                    $this->controllers_js .= $this->tab.'$scope.onInfinite = function() {'.$this->enter;
                    //$this->controllers_js .= $this->tab . $this->tab . 'console.log("onInfinite");' . $this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'$timeout(fetchItems, 500);'.$this->enter;
                    $this->controllers_js .= $this->tab.'};'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->enter;
                    if(!in_array($_tables['template'],$theme_no_animation)) {
                        $this->controllers_js .= $this->tab.'// create animation fade slide in right (ionic-material)'.$this->enter;
                        $this->controllers_js .= $this->tab.'$scope.fireEvent = function(){'.$this->enter;
                        //$this->controllers_js .= $this->tab . $this->tab . 'console.log("ionicMaterialMotion");' . $this->enter;
                        if(!isset($tables['motions'])) {
                            $tables['motions'] = 'fade-slide-in-right';
                        }
                        if($tables['motions'] == '') {
                            $tables['motions'] = 'fade-slide-in-right';
                        }
                        switch($tables['motions']) {
                            case 'blinds':
                                $this->controllers_js .= $this->tab.$this->tab.'ionicMaterialMotion.blinds();'.$this->enter;
                                break;
                            case 'ripple':
                                $this->controllers_js .= $this->tab.$this->tab.'ionicMaterialMotion.ripple();'.$this->enter;
                                break;
                            case 'fade-slide-in':
                                $this->controllers_js .= $this->tab.$this->tab.'ionicMaterialMotion.fadeSlideIn();'.$this->enter;
                                break;
                            case 'fade-slide-in-right':
                                $this->controllers_js .= $this->tab.$this->tab.'ionicMaterialMotion.fadeSlideInRight();'.$this->enter;
                                break;
                        }
                        $this->controllers_js .= $this->tab.$this->tab.'ionicMaterialInk.displayEffect();'.$this->enter;
                        $this->controllers_js .= $this->tab.'};'.$this->enter;
                    }
                }
            }
            // TODO: ----------------------------------------
            // TODO:  controllers_js --|-- data_single
            if($tables['prefix'].'_singles' == $this->str2var($page['prefix'])) {
                $this->authorization($tables,$page);
                $var_id = 'id';
                $var_src_id = 'id';
                if(is_array($tables['cols'])) {
                    foreach($tables['cols'] as $cols) {
                        if($cols['type'] == 'id') {
                            $var_id = $this->str2var($cols['title'],false);
                            $var_src_id = $this->str2var($cols['title'],false,true);
                        }
                    }
                }
                if(($tables['bookmarks'] == 'cart') || ($tables['bookmarks'] == 'bookmark')) {
                    //$this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$rootScope.item_in_virtual_table_'.$tables['prefix'].' = 0;'.$this->enter;
                    if(!isset($tables['column-for-price'])) {
                        $tables['column-for-price'] = 'please_select_col_price';
                    }
                    if($tables['column-for-price'] == '') {
                        $tables['column-for-price'] = 'please_select_col_price';
                    }
                    // TODO: controllers_js ----------|-- $scope.addToDbVirtual
                    $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.addToVirtual(); //data single'.$this->enter;
                    $this->controllers_js .= $this->tab.'$scope.addToDbVirtual = function(newItem){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'if(typeof newItem.'.$var_id.' === "undefined"){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'return false;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'var is_already_exist = false ;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'// animation loading '.$this->enter;
                    if(IONIC_LOADING == true) {
                        $this->controllers_js .= $this->tab.$this->tab.'$ionicLoading.show();'.$this->enter;
                    } else {
                        $this->controllers_js .= $this->tab.$this->tab.'$ionicLoading.show({'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\''.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.'var virtual_items = []; '.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'localforage.getItem("'.$tables['prefix'].'_'.$tables['bookmarks'].'", function(err,dbVirtual){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(dbVirtual === null){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'virtual_items = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var last_items = JSON.parse(dbVirtual); '.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}catch(e){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var last_items = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'for(var z=0;z<last_items.length;z++){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'virtual_items.push(last_items[z]);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if(newItem.'.$var_id.' ==  last_items[z].'.$var_id.'){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'is_already_exist = true;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}).then(function(value){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(is_already_exist === false){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'newItem["_qty"]=1;'.$this->enter;
                    if($tables['bookmarks'] == 'cart') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'newItem["_sum"]=newItem.'.$tables['column-for-price'].';'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'virtual_items.push(newItem);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("'.$tables['prefix'].'_'.$tables['bookmarks'].'",JSON.stringify(virtual_items));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$rootScope.item_in_virtual_table_'.$tables['prefix'].' = virtual_items.length;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},200);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}).catch(function(err){'.$this->enter;
                    if(IONIC_DEBUG == true) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'console.log("indexDB: ",err);'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'virtual_items = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'virtual_items.push(newItem);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("'.$tables['prefix'].'_'.$tables['bookmarks'].'",JSON.stringify(virtual_items));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$rootScope.item_in_virtual_table_'.$tables['prefix'].' = virtual_items.length;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},200);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'})'.$this->enter;
                    $this->controllers_js .= $this->tab.'};'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->enter;
                }
                if($tables['db_type'] == 'online') {
                    if($tables['db_url_single'] == '') {
                        $url = '"'.$tables['db_url'].'"';
                    } else {
                        $url = '"'.$tables['db_url_single'].'" + itemID';
                        if(preg_match("/firebase/i",$url)) {
                            $url .= '+ ".json";';
                        }
                    }
                } else {
                    $url = '"data/tables/'.strtolower($this->str2var($tables['title'])).'.json"';
                }
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'// set default parameter http'.$this->enter;
                $this->controllers_js .= $this->tab.'var http_params = {};'.$this->enter;
                if(!isset($tables['option']['youtube']['api_key'])) {
                    $tables['option']['youtube']['api_key'] = '';
                }
                if($tables['option']['youtube']['api_key'] !== '') {
                    $this->controllers_js .= $this->tab.'http_params = {maxResults:10,part:"id,snippet",type:"video",key: "'.$tables['option']['youtube']['api_key'].'"};'.$this->enter;
                }
                $this->controllers_js .= $this->enter;
                $this->controllers_js .= $this->tab.'// set HTTP Header '.$this->enter;
                $this->controllers_js .= $this->tab.'var http_header = {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'headers: {'.$this->enter;
                foreach($tables['http_header'] as $http_headers) {
                    if(strlen($http_headers['var']) > 2) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'"'.htmlentities($http_headers['var']).'": "'.htmlentities($http_headers['val']).'",'.$this->enter;
                    }
                }
                $this->controllers_js .= $this->tab.$this->tab.'},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'params: http_params'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
                $this->controllers_js .= $this->tab.'// animation loading '.$this->enter;
                if(IONIC_LOADING == true) {
                    $this->controllers_js .= $this->tab.'$ionicLoading.show();'.$this->enter;
                } else {
                    $this->controllers_js .= $this->tab.'$ionicLoading.show({'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\''.$this->enter;
                    $this->controllers_js .= $this->tab.'});'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'// Retrieving data'.$this->enter;
                $this->controllers_js .= $this->tab.'var itemID = $stateParams.'.$var_id.';'.$this->enter;
                $jsonp_url = $this->url_param(str_replace('"','',$url),'callback=JSON_CALLBACK');
                $jsonp_url = str_replace(' + itemID','" + itemID + "',$jsonp_url);
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.fetchURL'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.fetchURL = '.$url.';'.$this->enter;
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.fetchURLp'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.fetchURLp = "'.$jsonp_url.'";'.$this->enter;
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.hashURL'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.hashURL = md5.createHash($scope.fetchURL);'.$this->enter;
                if(IONIC_DEBUG == true) {
                    $this->controllers_js .= $this->tab.'console.log("hash: " + $scope.hashURL);'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.'var current_item = [];'.$this->enter;
                if($tables['db_url_single'] == '') {
                    if($tables['localstorage'] == 'localstorage') {
                        $this->controllers_js .= $this->tab.'if(window.localStorage.getItem("data_'.$this->str2var($tables['prefix']).'s") !== "undefined"){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'current_item = [];'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'var datas = JSON.parse(window.localStorage.getItem("data_'.$this->str2var($tables['prefix']).'s"));'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'if(datas!==null){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'for (var i = 0; i < datas.length; i++) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if((datas[i].'.$var_src_id.' ===  parseInt(itemID)) || (datas[i].'.$var_src_id.' === itemID.toString())) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'current_item = datas[i] ;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'// event done, hidden animation loading'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).' = current_item ;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'}, 100);'.$this->enter;
                        $this->controllers_js .= $this->tab.'};'.$this->enter;
                    }
                    if($tables['localstorage'] == 'localforage') {
                        $this->controllers_js .= $this->tab.'localforage.getItem("data_'.$this->str2var($tables['prefix']).'s_" + $scope.hashURL, function(err, get_datas){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'if(get_datas === null){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'current_item = [];'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'}else{'.$this->enter;
                        if(IONIC_DEBUG == true) {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'console.log("offline: " + JSON.parse(get_datas));'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(get_datas !== null){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var datas = JSON.parse(get_datas);'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'for (var i = 0; i < datas.length; i++) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if((datas[i].'.$var_src_id.' ===  parseInt(itemID)) || (datas[i].'.$var_src_id.' === itemID.toString())) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'current_item = datas[i] ;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// event done, hidden animation loading'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).' = current_item ;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}, 100);'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'};'.$this->enter;
                        $this->controllers_js .= $this->tab.'}).then(function(value){'.$this->enter;
                        if(IONIC_DEBUG == true) {
                            $this->controllers_js .= $this->tab.$this->tab.'console.log("forage: promise: ",value);'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.'}).catch(function (err){'.$this->enter;
                        if(IONIC_DEBUG == true) {
                            $this->controllers_js .= $this->tab.$this->tab.'console.log("forage: ",err);'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.'})'.$this->enter;
                    }
                } else {
                    if($tables['localstorage'] == 'localstorage') {
                        $this->controllers_js .= $this->tab.'if(window.localStorage.getItem("data_'.$this->str2var($tables['prefix']).'_" + itemID ) !== "undefined"){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'var current_item = JSON.parse(window.localStorage.getItem("data_'.$this->str2var($tables['prefix']).'_"+itemID));'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'// event done, hidden animation loading'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).' = current_item ;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'}, 500);'.$this->enter;
                        $this->controllers_js .= $this->tab.'};'.$this->enter;
                    }
                    if($tables['localstorage'] == 'localforage') {
                        $this->controllers_js .= $this->tab.'localforage.getItem("data_'.$this->str2var($tables['prefix']).'_single_" + $scope.hashURL, function(err, get_datas){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'if(get_datas === null){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'current_item = [];'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'}else{'.$this->enter;
                        if(IONIC_DEBUG == true) {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'console.log("offline: " + JSON.parse(get_datas));'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(get_datas !== null){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'current_item = JSON.parse(get_datas);'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).' = current_item ;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}, 500);'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'};'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'};'.$this->enter;
                        $this->controllers_js .= $this->tab.'}).then(function(value){'.$this->enter;
                        if(IONIC_DEBUG == true) {
                            $this->controllers_js .= $this->tab.$this->tab.'console.log("forage: promise: ",value);'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.'}).catch(function (err){'.$this->enter;
                        if(IONIC_DEBUG == true) {
                            $this->controllers_js .= $this->tab.$this->tab.'console.log("forage: ",err);'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.'})'.$this->enter;
                    }
                }
                $this->controllers_js .= $this->tab.'if( current_item.length === 0 ){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'var itemID = $stateParams.'.$var_id.';'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'var current_item = [];'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'// set HTTP Header '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'http_header = {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'headers: {'.$this->enter;
                foreach($tables['http_header'] as $http_headers) {
                    if(strlen($http_headers['var']) > 2) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'"'.htmlentities($http_headers['var']).'": "'.htmlentities($http_headers['val']).'",'.$this->enter;
                    }
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'params: http_params'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'};'.$this->enter;
                if(IONIC_DEBUG == true) {
                    $this->controllers_js .= $this->tab.$this->tab.'console.log("url_singles =>" + '.$url.' );'.$this->enter;
                }
                // TODO: controllers_js ----------|-- $http.get
                $this->controllers_js .= $this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $http.get'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$http.get($scope.fetchURL,http_header).then(function(response) {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// Get data single'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'var datas = response.data;'.$this->enter;
                if($tables['db_type'] == 'online') {
                    // tanpa url single
                    if($tables['db_url_single'] == '') {
                        if($tables['localstorage'] == 'localstorage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'try {'.$this->enter;
                            // TODO: controllers_js --------------|-- set:localstorage
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(datas));'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'} catch(e) {'.$this->enter;
                            // TODO: controllers_js --------------|-- clear:localstorage
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.clear();'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(datas));'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearCache();'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearHistory();'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$state.reload();'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.$state = $state;'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                        }
                        // TODO:  controllers_js --------------|-- localforage
                        if($tables['localstorage'] == 'localforage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'s_"+ $scope.hashURL,JSON.stringify(datas));'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'for (var i = 0; i < datas.length; i++) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if((datas[i].'.$var_src_id.' ===  parseInt(itemID)) || (datas[i].'.$var_src_id.' === itemID.toString())) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'current_item = datas[i] ;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    } else {
                        if($tables['localstorage'] == 'localstorage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'try {'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'_" + itemID,JSON.stringify(datas));'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'} catch(e) {'.$this->enter;
                            // TODO: controllers_js --------------|-- clear localstorage
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.clear();'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'_" + itemID,JSON.stringify(datas));'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearCache();'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicHistory.clearHistory();'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$state.reload();'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.$state = $state;'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                        }
                        // TODO:  controllers_js --------------|-- localforage
                        if($tables['localstorage'] == 'localforage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'_single_" + $scope.hashURL,JSON.stringify(datas));'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'current_item = datas ;'.$this->enter;
                    }
                } else {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'for (var i = 0; i < datas.length; i++) {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if((datas[i].'.$var_src_id.' ===  parseInt(itemID)) || (datas[i].'.$var_src_id.' === itemID.toString())) {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'current_item = datas[i] ;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.'},function(data) {'.$this->enter;
                // TODO: controllers_js --------------|-- error : Message
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// Error message'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var alertPopup = $ionicPopup.alert({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'title: '.$retrieval_error_title.','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: '.$retrieval_error_content.','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                if($tables['languages']['error_auto_close'] == true) {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'alertPopup.close();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}, 2000);'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.'}).finally(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.$broadcast("scroll.refreshComplete");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// event done, hidden animation loading'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).' = current_item ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}, 500);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                $this->controllers_js .= $this->tab.$this->enter;
                // TODO: controllers_js ------|-- $scope.doRefresh
                $this->controllers_js .= $this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.doRefresh'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.doRefresh = function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'// Retrieving data'.$this->enter;
                if($tables['localstorage'] == 'localstorage') {
                    $this->controllers_js .= $this->tab.$this->tab.'window.localStorage.clear();'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.'var itemID = $stateParams.'.$var_id.';'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'var current_item = [];'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'// overwrite http_header '.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'http_header = {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'headers: {'.$this->enter;
                foreach($tables['http_header'] as $http_headers) {
                    if(strlen($http_headers['var']) > 2) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'"'.htmlentities($http_headers['var']).'": "'.htmlentities($http_headers['val']).'",'.$this->enter;
                    }
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'params: http_params'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'};'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|------ $http.get'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$http.get($scope.fetchURL,http_header).then(function(response) {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// Get data single'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'var datas = response.data'.$db_var.';'.$this->enter;
                if($tables['db_type'] == 'online') {
                    if($tables['db_url_single'] == '') {
                        if($tables['localstorage'] == 'localstorage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(datas));'.$this->enter;
                        }
                        // TODO:  controllers_js --------------|-- localforage
                        if($tables['localstorage'] == 'localforage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'s_"+ $scope.hashURL,JSON.stringify(datas));'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'for (var i = 0; i < datas.length; i++) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if((datas[i].'.$var_src_id.' ===  parseInt(itemID)) || (datas[i].'.$var_src_id.' === itemID.toString())) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'current_item = datas[i] ;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    } else {
                        if($tables['localstorage'] == 'localstorage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'_" + itemID,JSON.stringify(datas));'.$this->enter;
                        }
                        // TODO:  controllers_js --------------|-- localforage
                        if($tables['localstorage'] == 'localforage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'_single_" + $scope.hashURL,JSON.stringify(datas));'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'current_item = datas ;'.$this->enter;
                    }
                } else {
                    if($tables['localstorage'] == 'localstorage') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(datas));'.$this->enter;
                    }
                    // TODO:  controllers_js --------------|-- localforage
                    if($tables['localstorage'] == 'localforage') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'s_"+ $scope.hashURL,JSON.stringify(datas));'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'for (var i = 0; i < datas.length; i++) {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'if((datas[i].'.$var_src_id.' ===  parseInt(itemID)) || (datas[i].'.$var_src_id.' === itemID.toString())) {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'current_item = datas[i] ;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.'},function(data) {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// Error message'.$this->enter;
                if(IONIC_DEBUG == true) {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'console.log("JSOP",$scope.fetchURLp);'.$this->enter;
                }
                // TODO: controllers_js ----------|-- $http.jsonp
                $this->controllers_js .= $this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- $http.jsonp'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$http.jsonp($scope.fetchURLp,http_header).success(function(response){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// Get data single'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var datas = response'.$db_var.';'.$this->enter;
                if(IONIC_DEBUG == true) {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'console.log("resp",datas);'.$this->enter;
                }
                // TODO:  controllers_js ------------|-- online
                if($tables['db_type'] == 'online') {
                    if($tables['db_url_single'] == '') {
                        if($tables['localstorage'] == 'localstorage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(datas));'.$this->enter;
                        }
                        // TODO:  controllers_js --------------|-- localforage
                        if($tables['localstorage'] == 'localforage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'s_"+ $scope.hashURL,JSON.stringify(datas));'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'for (var i = 0; i < datas.length; i++) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if((datas[i].'.$var_id.' ===  parseInt(itemID)) || (datas[i].'.$var_id.' === itemID.toString())) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'current_item = datas[i] ;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    } else // TODO:  controllers_js ------------|-- offline
                    {
                        if($tables['localstorage'] == 'localstorage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'_" + itemID,JSON.stringify(datas));'.$this->enter;
                        }
                        // TODO:  controllers_js --------------|-- localforage
                        if($tables['localstorage'] == 'localforage') {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'_single_" + $scope.hashURL,JSON.stringify(datas));'.$this->enter;
                        }
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'current_item = datas ;'.$this->enter;
                    }
                } else {
                    if($tables['localstorage'] == 'localstorage') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'window.localStorage.setItem("data_'.$this->str2var($tables['prefix']).'s",JSON.stringify(datas));'.$this->enter;
                    }
                    // TODO:  controllers_js --------------|-- localforage
                    if($tables['localstorage'] == 'localforage') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|---------- set:localforage'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'localforage.setItem("data_'.$this->str2var($tables['prefix']).'s_"+ $scope.hashURL,JSON.stringify(datas));'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'for (var i = 0; i < datas.length; i++) {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if((datas[i].'.$var_src_id.' ===  parseInt(itemID)) || (datas[i].'.$var_src_id.' === itemID.toString())) {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'current_item = datas[i] ;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.$broadcast("scroll.refreshComplete");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// event done, hidden animation loading'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).' = current_item ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}, 500);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}).error(function(resp){'.$this->enter;
                if(IONIC_DEBUG == true) {
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'console.log("Error",resp);'.$this->enter;
                }
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var alertPopup = $ionicPopup.alert({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'title: '.$retrieval_error_title.','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: '.$retrieval_error_content.','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}).finally(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.$broadcast("scroll.refreshComplete");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'// event done, hidden animation loading'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$this->str2var($tables['prefix']).' = current_item ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'controller_by_user();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}, 500);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
            }
            if(!isset($page['for'])) {
                $page['for'] = '-';
            }
            if(!isset($tables['bookmarks'])) {
                $tables['bookmarks'] = 'none';
            }
            if(($tables['bookmarks'] == 'cart') || ($tables['bookmarks'] == 'bookmark')) {
                // TODO: ------------------------------
                // TODO:  controllers_js --|-- bookmarks
                if($page['for'] == 'table-bookmarks') {
                    $var_id = 'id';
                    $var_src_id = 'id';
                    if(is_array($tables['cols'])) {
                        foreach($tables['cols'] as $cols) {
                            if($cols['type'] == 'id') {
                                $var_id = $this->str2var($cols['title'],false);
                            }
                        }
                    }
                    $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- Database for Cart/Bookmark'.$this->enter;
                    // TODO:  controllers_js --|------ $scope.loadDbVirtual
                    $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.loadDbVirtual'.ucwords($tables['prefix']).''.$this->enter;
                    $this->controllers_js .= $this->tab.' '.$this->enter;
                    $this->controllers_js .= $this->tab.'$scope.loadDbVirtual'.ucwords($tables['prefix']).' = function(){'.$this->enter;
                    if(IONIC_LOADING == true) {
                        $this->controllers_js .= $this->tab.$this->tab.'$ionicLoading.show();'.$this->enter;
                    } else {
                        $this->controllers_js .= $this->tab.$this->tab.'$ionicLoading.show({'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\''.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.'//'.$page['prefix'].$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'$scope.'.$tables['prefix'].'_'.$tables['bookmarks'].' = []; '.$this->enter;
                    if($tables['bookmarks'] == 'cart') {
                        $this->controllers_js .= $this->tab.$this->tab.'$scope.'.$tables['prefix'].'_cost = 0;'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.'localforage.getItem("'.$tables['prefix'].'_'.$tables['bookmarks'].'", function(err,dbVirtual){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(dbVirtual === null){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$tables['prefix'].'_'.$tables['bookmarks'].' = []; '.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}else{'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'try{'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$tables['prefix'].'_'.$tables['bookmarks'].' = JSON.parse(dbVirtual); '.$this->enter;
                    if(($tables['bookmarks'] == 'cart') || ($tables['bookmarks'] == 'bookmark')) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$rootScope.item_in_virtual_table_'.$tables['prefix'].' = $scope.'.$tables['prefix'].'_'.$tables['bookmarks'].'.length;'.$this->enter;
                    }
                    if($tables['bookmarks'] == 'cart') {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var total_cost = 0;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'angular.forEach($scope.'.$tables['prefix'].'_'.$tables['bookmarks'].', function(item, key) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var cost = item._qty * item.'.$tables['column-for-price'].';'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'total_cost += cost;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$tables['prefix'].'_cost = total_cost;'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}catch (e){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.'.$tables['prefix'].'_'.$tables['bookmarks'].' = []; '.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}).then(function(value){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},200);'.$this->enter;
                    // $this->controllers_js .= $this->tab . $this->tab . $this->tab . 'console.log($scope.' . $tables['prefix'] . '_' . $tables['bookmarks'] . ');' . $this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}).catch(function(err){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'console.log(err);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},200);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                    $this->controllers_js .= $this->tab.'};'.$this->enter;
                    // TODO:  controllers_js --|------ $ionicView.enter
                    $this->controllers_js .= $this->tab.'$scope.$on("$ionicView.enter", function (){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'$scope.loadDbVirtual'.ucwords($tables['prefix']).'();'.$this->enter;
                    $this->controllers_js .= $this->tab.'});'.$this->enter;
                    // TODO:  controllers_js --|------ $scope.clearDbVirtual
                    $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.clearDbVirtual'.ucwords($tables['prefix']).''.$this->enter;
                    $this->controllers_js .= $this->tab.'$scope.clearDbVirtual'.ucwords($tables['prefix']).' = function(){'.$this->enter;
                    if(($tables['bookmarks'] == 'cart') || ($tables['bookmarks'] == 'bookmark')) {
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$rootScope.item_in_virtual_table_'.$tables['prefix'].' = 0;'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->tab.'localforage.setItem("'.$tables['prefix'].'_'.$tables['bookmarks'].'",[]);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.loadDbVirtual'.ucwords($tables['prefix']).'();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'},200);'.$this->enter;
                    $this->controllers_js .= $this->tab.'};'.$this->enter;
                    // TODO:  controllers_js --|------ $scope.removeDbVirtual
                    $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.removeDbVirtual'.ucwords($tables['prefix']).''.$this->enter;
                    $this->controllers_js .= $this->tab.'$scope.removeDbVirtual'.ucwords($tables['prefix']).' = function(itemId){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'var virtual_items = [];'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'var last_items = $scope.'.$tables['prefix'].'_'.$tables['bookmarks'].';'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'for(var z=0;z<last_items.length;z++){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if(itemId!==last_items[z].'.$var_id.'){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'virtual_items.push(last_items[z]);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'localforage.setItem("'.$tables['prefix'].'_'.$tables['bookmarks'].'",JSON.stringify(virtual_items));'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'$timeout(function(){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$scope.loadDbVirtual'.ucwords($tables['prefix']).'();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'},200);'.$this->enter;
                    $this->controllers_js .= $this->tab.'};'.$this->enter;
                    if($tables['bookmarks'] == 'cart') {
                        // TODO: controllers_js ----------|-- $scope.addToDbVirtual
                        $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.updateDbVirtual();'.$this->enter;
                        $this->controllers_js .= $this->tab.'$scope.updateDbVirtual = function(){'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'var virtual_items = [];'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'var total_cost = 0;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'angular.forEach($scope.'.$tables['prefix'].'_'.$tables['bookmarks'].', function(item, key) {'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'var cost = item._qty * item.'.$tables['column-for-price'].';'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'item._sum = cost;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'this.push(item);'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'total_cost += cost;'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'},virtual_items);'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'localforage.setItem("'.$tables['prefix'].'_'.$tables['bookmarks'].'",JSON.stringify(virtual_items));'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'$scope.'.$tables['prefix'].'_cost = total_cost;'.$this->enter;
                        //$this->controllers_js .= $this->tab . $this->tab . 'console.log("update_cart",total_cost,virtual_items);' . $this->enter;
                        $this->controllers_js .= $this->tab.'};'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->enter;
                    }
                }
            }
        }
        // TODO: ------------------------------
        // TODO:  controllers_js --|-- form request
        if(!isset($page['for'])) {
            $page['for'] = '';
        }
        if($this->str2var($page['for']) == 'forms') {
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'// Form Request'.$this->enter;
            foreach($this->config['forms'] as $form) {
                if($page['prefix'] == 'form_'.$form['prefix']) {
                    $this->controllers_js .= $this->tab.'//'.$form['prefix'].$this->enter;
                    $this->controllers_js .= $this->tab.'$scope.form_'.$form['prefix'].'= {};'.$this->enter;
                    $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.submit'.ucwords($form['prefix']).''.$this->enter;
                    $this->controllers_js .= $this->tab.'$scope.submit'.ucwords($form['prefix']).' = function(){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'// animation loading '.$this->enter;
                    if(IONIC_LOADING == true) {
                        $this->controllers_js .= $this->tab.$this->tab.'$ionicLoading.show();'.$this->enter;
                    } else {
                        $this->controllers_js .= $this->tab.$this->tab.'$ionicLoading.show({'.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.$this->tab.'template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\''.$this->enter;
                        $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                    }
                    $this->controllers_js .= $this->tab.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'var $messages, $title = null;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'$http({'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'method:"'.strtoupper($this->str2var($form['method'])).'",'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'url: "'.($form['action']).'",'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'data: $httpParamSerializer($scope.form_'.$form['prefix'].'),  // pass in data as strings'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'headers: {"Content-Type":"application/x-www-form-urlencoded"}  // set the headers so angular passing info as form data (not request payload)'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'})'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'.then(function(response) {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$messages = response.data.message;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$title = response.data.title;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'},function(response){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$messages = response.statusText;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$title = response.status;'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}).finally(function(){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'// event done, hidden animation loading'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function() {'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$ionicLoading.hide();'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'if($messages !== null){'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// message'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'var alertPopup = $ionicPopup.alert({'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'title: $title,'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: $messages,'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'});'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'// clear input'.$this->enter;
                    $dont_create_var = array(
                        "divider",
                        "button",
                        "hidden",
                        "date",
                        "datetime",
                        "time");
                    foreach($form['input'] as $input) {
                        if(!in_array($input['type'],$dont_create_var)) {
                            $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$scope.form_'.$form['prefix'].'.'.$this->str2var($input['name'],false).' = "";'.$this->enter;
                        }
                    }
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}, 500);'.$this->enter;
                    $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                    $this->controllers_js .= $this->tab.'};'.$this->enter;
                }
            }
        }
        $this->controllers_js .= $this->tab.'// code '.$this->enter;
        $this->controllers_js .= $code.$this->enter;
        if(!isset($page['js'])) {
            $page['js'] = '';
        }
        // TODO: controllers_js --|-- controller_by_user
        $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- controller_by_user'.$this->enter;
        $this->controllers_js .= $this->tab.'// controller by user '.$this->enter;
        $this->controllers_js .= $this->tab.'function controller_by_user(){'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'try {'.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.$this->tab.''.$this->enter;
        $this->controllers_js .= $page['js'];
        $this->controllers_js .= $this->tab.$this->tab.$this->tab.''.$this->enter;
        $this->controllers_js .= $this->tab.$this->tab.'} catch(e){'.$this->enter;
        if(!isset($tables['languages']['error_messages'])) {
            $tables['languages']['error_messages'] = false;
        }
        if($tables['languages']['error_messages'] == 'true') {
            $tables['languages']['error_messages'] = true;
        }
        if($tables['languages']['error_messages'] == true) {
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'console.log("%cerror: %cPage: `'.$page['prefix'].'` and field: `Custom Controller`","color:blue;font-size:18px","color:red;font-size:18px");'.$this->enter;
            $this->controllers_js .= $this->tab.$this->tab.$this->tab.'console.dir(e);'.$this->enter;
        }
        $this->controllers_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->controllers_js .= $this->tab.'}'.$this->enter;
        $this->controllers_js .= $this->tab.'$scope.rating = {};'.$this->enter;
        $this->controllers_js .= $this->tab.'$scope.rating.max = 5;'.$this->enter;
        if(!in_array($_tables['template'],$theme_no_animation)) {
            $this->controllers_js .= $this->tab.$this->enter;
            $this->controllers_js .= $this->tab.'// animation ink (ionic-material)'.$this->enter;
            $this->controllers_js .= $this->tab.'ionicMaterialInk.displayEffect();'.$this->enter;
        }
        $this->controllers_js .= $this->tab.'controller_by_user();'.$this->enter;
        $this->controllers_js .= '})'.$this->enter;
    }
    private function authorization($tables,$page)
    {
        // TODO: controllers_js --|-- Authentication
        switch($tables['auth']['type']) {
            case 'basic':
                // TODO: controllers_js --|------ basic
                // TODO: controllers_js --|---------- default
                if($tables['auth']['consumer_key'] != '') {
                    $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- set:auth'.$this->enter;
                    $this->controllers_js .= $this->tab.'var http_value = "Basic '.str_replace('=','',base64_encode($tables['auth']['consumer_key'].':'.$tables['auth']['consumer_secret'])).'";'.$this->enter;
                    $this->controllers_js .= $this->tab.'$http.defaults.headers.common["Authorization"] = http_value;'.$this->enter;
                }
                // TODO: controllers_js ------|---------- $scope.showAuthentication
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.showAuthentication'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.showAuthentication  = function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'var form = {"uname":"demo","pwd":"demo"};'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$scope.form = {};'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'var authPopup = $ionicPopup.show({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'template: \'<input type="text" ng-model="form.uname" placeholder="Username"><input type="password" placeholder="Password" ng-model="form.pwd">\','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'title: "Authorization",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'subTitle: "Please use username and password",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'scope: $scope,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'buttons: ['.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'{text:"Cancel",onTap: function(e){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$state.go("'.$this->pagePrefix.'.'.$this->config['app']['index'].'");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'{text:"<strong>Save</strong>",type:"button-positive",onTap:function(e){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'return $scope.form;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'],'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}).then(function(form){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if( angular.isDefined(form)){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var uname = form.uname || "demo";'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var pwd = form.pwd || "demo";'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var http_value = "Basic " + base64.encode(uname + ":" + pwd);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$http.defaults.headers.common["Authorization"] = http_value;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'localforage.setItem("ima_session", JSON.stringify(http_value));'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.doRefresh();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'},function(err){'.$this->enter;
                // $this->controllers_js .= $this->tab . $this->tab . $this->tab . 'console.log("err",err);' . $this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'},function(msg){'.$this->enter;
                //$this->controllers_js .= $this->tab . $this->tab . $this->tab . 'console.log("msg",msg);' . $this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
                break;
            case 'x-basic':
                // TODO: controllers_js --|------ x-basic
                if($tables['auth']['consumer_key'] != '') {
                    $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- set:auth'.$this->enter;
                    $this->controllers_js .= $this->tab.'var http_value = "Basic '.str_replace('=','',base64_encode($tables['auth']['consumer_key'].':'.$tables['auth']['consumer_secret'])).'";'.$this->enter;
                    $this->controllers_js .= $this->tab.'$http.defaults.headers.common["X-Authorization"] = http_value;'.$this->enter;
                }
                // TODO: controllers_js ------|---------- $scope.showAuthentication
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.showAuthentication'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.showAuthentication  = function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'var form = {"uname":"demo","pwd":"demo"};'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$scope.form = {};'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'var authPopup = $ionicPopup.show({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'template: \'<input type="text" ng-model="form.uname" placeholder="Username"><input type="password" placeholder="Password" ng-model="form.pwd">\','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'title: "Authorization",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'subTitle: "Please use username and password",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'scope: $scope,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'buttons: ['.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'{text:"Cancel",onTap: function(e){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$state.go("'.$this->pagePrefix.'.'.$this->config['app']['index'].'");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'{text:"<strong>Save</strong>",type:"button-positive",onTap:function(e){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'return $scope.form;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'],'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}).then(function(form){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'if( angular.isDefined(form)){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var uname = form.uname || "demo";'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var pwd = form.pwd || "demo";'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var http_value = "Basic " + base64.encode(uname + ":" + pwd);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$http.defaults.headers.common["X-Authorization"] = http_value;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'localforage.setItem("ima_session", JSON.stringify(http_value));'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.doRefresh();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'},function(err){'.$this->enter;
                // $this->controllers_js .= $this->tab . $this->tab . $this->tab . 'console.log("err",err);' . $this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'},function(msg){'.$this->enter;
                //$this->controllers_js .= $this->tab . $this->tab . $this->tab . 'console.log("msg",msg);' . $this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
                break;
            case 'restapi-jwt-auth':
                // TODO: controllers_js --|------ restapi-jwt-auth
                // TODO: controllers_js ------|---------- $scope.showAuthentication
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.showAuthentication'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.showAuthentication  = function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'$scope.form = {};'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'var authPopup = $ionicPopup.show({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'template: \'<input type="text" ng-model="form.uname"><input type="password" ng-model="form.pwd">\','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'title: "Authorization",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'subTitle: "Please use username and password",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'scope: $scope,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'buttons: ['.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'{text:"Cancel",onTap: function(e){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$state.go("'.$this->pagePrefix.'.'.$this->config['app']['index'].'");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'{text:"<strong>Save</strong>",type:"button-positive",onTap:function(e){ return $scope.form;}},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'],'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}).then(function(form){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'$http({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'method: "POST",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'withCredentials : true,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'url: "http://wp.org/wp-json/jwt-auth/v1/token",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'data: \'{"username": "admin","password": "admin"}\','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'headers: {'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'"Content-Type":"application/json",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'"Access-Control-Request-Headers":"Authorization,Content-Type,Origin"'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}).then(function(response){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.''.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'console.log(response);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'var http_value = "Bearer " + response.data.token ;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$http.defaults.headers.common["Authorization"] = http_value;'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'$scope.doRefresh();'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'}, function(response){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.''.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'},function(err){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'console.log("err",err);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'},function(msg){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'console.log("msg",msg);'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
                break;
            case 'none':
                // TODO: controllers_js ------|------ none
                // TODO: controllers_js ------|---------- $scope.showAuthentication
                $this->controllers_js .= $this->tab.'// TO'.'DO: '.$this->str2var($page['prefix']).'Ctrl --|-- $scope.showAuthentication'.$this->enter;
                $this->controllers_js .= $this->tab.'$scope.showAuthentication  = function(){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'var authPopup = $ionicPopup.show({'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'template: \' This page required login\','.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'title: "Authorization",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'subTitle: "Authorization is required",'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'scope: $scope,'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'buttons: ['.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'{text:"Cancel",onTap: function(e){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$state.go("'.$this->pagePrefix.'.'.$this->config['app']['index'].'");'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.$this->tab.'}},'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.$this->tab.'],'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'}).then(function(form){'.$this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'},function(err){'.$this->enter;
                //$this->controllers_js .= $this->tab . $this->tab . $this->tab . 'console.log("err",err);' . $this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'},function(msg){'.$this->enter;
                //$this->controllers_js .= $this->tab . $this->tab . $this->tab . 'console.log("msg",msg);' . $this->enter;
                $this->controllers_js .= $this->tab.$this->tab.'});'.$this->enter;
                $this->controllers_js .= $this->tab.'};'.$this->enter;
                break;
        }
    }
    function tables_json($page)
    {
        foreach($this->tables as $tables) {
            if($tables['parent'] == $this->str2var($page['prefix'])) {
                $cols = $tables['cols'];
                $max_example = 50;
                switch($tables['template']) {
                    case 'chart-line':
                        $max_example = 3;
                        break;
                    case 'chart-bar':
                        $max_example = 3;
                        break;
                    case 'chart-doughnut':
                        $max_example = 3;
                        break;
                }
                for($i = 0; $i < $max_example; $i++) {
                    $r = 0;
                    foreach($cols as $col) {
                        $var = $this->str2var($col['title'],false);
                        switch($col['type']) {
                            case 'id':
                                $data_json[$i][$var] = $i;
                                break;
                            case 'text':
                                $lipsum = new LoremIpsum();
                                $data_json[$i][$var] = $lipsum->words(6);
                                switch($tables['template']) {
                                    case 'dictionary':
                                        $data_json[$i][$var] = $lipsum->words(1);
                                        break;
                                    case 'chart-line':
                                        $data_json[$i][$var] = rand(3,20);
                                        break;
                                    case 'chart-bar':
                                        $data_json[$i][$var] = rand(3,20);
                                        break;
                                    case 'chart-doughnut':
                                        $data_json[$i][$var] = rand(3,20);
                                        break;
                                    case 'showcase':
                                        $data_json[$i][$var] = $lipsum->words(2);
                                        break;
                                }
                                break;
                            case 'paragraph':
                                $lipsum = new LoremIpsum();
                                $data_json[$i][$var] = $lipsum->words(20);
                                break;
                            case 'heading-1':
                                $lipsum = new LoremIpsum();
                                if($this->config['app']['direction'] == "rtl") {
                                    $data_json[$i][$var] = html_entity_decode("&#1573;&#1576;&#1617;&#1575;&#1606; &#1575;&#1604;&#1571;&#1580;&#1604; &#1605;&#1605;&#1575;");
                                } else {
                                    $data_json[$i][$var] = ''.ucwords($lipsum->words(3)).'';
                                }
                                switch($tables['template']) {
                                    case 'dictionary':
                                        $data_json[$i][$var] = $lipsum->words(1);
                                        break;
                                    case 'chart-line':
                                        $data_json[$i][$var] = $lipsum->words(1);
                                        break;
                                    case 'chart-bar':
                                        $data_json[$i][$var] = $lipsum->words(1);
                                        break;
                                    case 'chart-doughnut':
                                        $data_json[$i][$var] = $lipsum->words(1);
                                        break;
                                }
                                break;
                            case 'heading-2':
                                $lipsum = new LoremIpsum();
                                $data_json[$i][$var] = ucwords($lipsum->words(2));
                                break;
                            case 'heading-3':
                                $lipsum = new LoremIpsum();
                                $data_json[$i][$var] = ucwords($lipsum->words(2));
                                break;
                            case 'heading-4':
                                $lipsum = new LoremIpsum();
                                $data_json[$i][$var] = ucwords($lipsum->words(2));
                                break;
                            case 'images':
                                if($r == 0) {
                                    $data_json[$i][$var] = 'data/images/avatar/pic'.rand(0,9).'.jpg';
                                } else {
                                    $data_json[$i][$var] = 'data/images/images/slidebox-'.rand(0,4).'.jpg';
                                }
                                $r++;
                                break;
                            case 'icon':
                                $icon = new jsmIonicon();
                                $iconList = $icon->iconList();
                                $id = rand(0,count($iconList));
                                if(isset($iconList[$id]['var'])) {
                                    $data_json[$i][$var] = 'ion-'.$iconList[$id]['var'];
                                } else {
                                    $data_json[$i][$var] = 'ion-home';
                                }
                                break;
                            case 'to_trusted':
                                $lipsum = new LoremIpsum();
                                $data_json[$i][$var] = null;
                                if($tables['template'] != 'slidebox') {
                                    if($this->config['app']['direction'] == "rtl") {
                                        $data_json[$i][$var] .= html_entity_decode('<p lang="ar" xml:lang="ar" dir="rtl">&#1603;&#1575;&#1606; &#1593;&#1606; &#1571;&#1604;&#1605;&#1617; &#1575;&#1593;&#1578;&#1583;&#1575;&#1569; &#1575;&#1604;&#1610;&#1575;&#1576;&#1575;&#1606;&#1548;, &#1602;&#1575;&#1605; &#1608;&#1575;&#1606;&#1607;&#1575;&#1569; &#1608;&#1610;&#1603;&#1610;&#1576;&#1610;&#1583;&#1610;&#1575;&#1548; &#1571;&#1610;. &#1602;&#1583; &#1578;&#1581;&#1578; &#1578;&#1587;&#1576;&#1576; &#1580;&#1586;&#1610;&#1585;&#1578;&#1610;. &#1586;&#1607;&#1575;&#1569; &#1587;&#1610;&#1575;&#1587;&#1577; &#1576;&#1575;&#1604;&#1601;&#1588;&#1604; &#1576;&#1607;&#1575; &#1578;&#1605;, &#1604;&#1594;&#1575;&#1578; &#1593;&#1585;&#1601;&#1607;&#1575; &#1590;&#1605;&#1606;&#1607;&#1575; &#1576;&#1593;&#1590; &#1571;&#1606;, &#1576;&#1593;&#1583; &#1578;&#1605; &#1601;&#1585;&#1606;&#1587;&#1575; &#1608;&#1575;&#1615;&#1587;&#1583;&#1604;. &#1571;&#1587;&#1610;&#1575; &#1606;&#1607;&#1575;&#1610;&#1577; &#1575;&#1604;&#1587;&#1610;&#1569; &#1603;&#1604; &#1601;&#1593;&#1604;, &#1576;&#1576;&#1593;&#1590; &#1608;&#1576;&#1593;&#1583;&#1605;&#1575; &#1603;&#1604; &#1583;&#1608;&#1606;. &#1610;&#1576;&#1602; &#1603;&#1604; &#1575;&#1604;&#1588;&#1617;&#1593;&#1576;&#1610;&#1606; &#1575;&#1604;&#1605;&#1608;&#1587;&#1608;&#1593;&#1577;. &#1607;&#1608; &#1576;&#1581;&#1588;&#1583; &#1575;&#1604;&#1581;&#1603;&#1605; &#1590;&#1605;&#1606;&#1607;&#1575; &#1583;&#1606;&#1608;.');
                                        $data_json[$i][$var] .= $lipsum->sentences(1,'p');
                                        $data_json[$i][$var] .= '<p lang="ar" xml:lang="ar" dir="rtl"><img src="data/images/images/slidebox-'.rand(0,4).'.jpg" class="full-image" /></p>';
                                        $data_json[$i][$var] .= html_entity_decode('<p lang="ar" xml:lang="ar" dir="rtl">&#1573;&#1576;&#1617;&#1575;&#1606; &#1575;&#1604;&#1571;&#1580;&#1604; &#1605;&#1605;&#1575; &#1576;&#1600;, &#1573;&#1610;&#1608; &#1578;&#1605; &#1602;&#1575;&#1605;&#1578; &#1571;&#1580;&#1586;&#1575;&#1569; &#1575;&#1604;&#1605;&#1587;&#1585;&#1581;. &#1575;&#1606; &#1608;&#1575;&#1587;&#1578;&#1605;&#1585;&#1578; &#1575;&#1604;&#1608;&#1575;&#1602;&#1593;&#1577; &#1575;&#1604;&#1576;&#1608;&#1604;&#1606;&#1583;&#1610; &#1608;&#1602;&#1583;, &#1610;&#1578;&#1576;&#1602;&#1617; &#1605;&#1587;&#1572;&#1608;&#1604;&#1610;&#1577; &#1588;&#1605;&#1608;&#1604;&#1610;&#1577;&#1611; &#1571;&#1610; &#1607;&#1584;&#1575;. &#1590;&#1585;&#1576; &#1608;&#1576;&#1575;&#1569;&#1578; &#1575;&#1604;&#1591;&#1585;&#1610;&#1602; &#1576;&#1605;&#1593;&#1575;&#1585;&#1590;&#1577; &#1603;&#1604;, &#1583;&#1608;&#1606; &#1605;&#1606; &#1576;&#1602;&#1610;&#1575;&#1583;&#1577; &#1575;&#1604;&#1582;&#1575;&#1587;&#1585;, &#1605;&#1588;&#1585;&#1608;&#1591; &#1573;&#1581;&#1603;&#1575;&#1605; &#1608;&#1575;&#1604;&#1593;&#1578;&#1575;&#1583; &#1603;&#1605;&#1575; &#1608;. &#1575;&#1604;&#1579;&#1602;&#1610;&#1604;&#1577; &#1608;&#1575;&#1604;&#1585;&#1608;&#1587;&#1610;&#1577; &#1601;&#1589;&#1604; &#1575;&#1606;. &#1605;&#1593; &#1575;&#1604;&#1571;&#1576;&#1585;&#1610;&#1575;&#1569; &#1575;&#1604;&#1571;&#1608;&#1585;&#1608;&#1576;&#1610;&#1577; &#1581;&#1610;&#1606;, &#1584;&#1604;&#1603; &#1575;&#1604;&#1587;&#1576;&#1576; &#1575;&#1604;&#1605;&#1590;&#1610; &#1605;&#1575;. &#1571;&#1606; &#1581;&#1578;&#1609; &#1580;&#1587;&#1610;&#1605;&#1577; &#1578;&#1581;&#1585;&#1610;&#1585;, &#1594;&#1610;&#1585; &#1576;&#1575;&#1604;&#1585;&#1594;&#1605; &#1604;&#1604;&#1605;&#1580;&#1607;&#1608;&#1583; &#1575;&#1604;&#1581;&#1603;&#1608;&#1605;&#1577; &#1607;&#1608;.</p>');
                                    } else {
                                        $data_json[$i][$var] .= $lipsum->sentences(1,'p');
                                        $data_json[$i][$var] .= '
                                        <ul>
                                        <li><a href="http://ihsana.com" target="_blank">Test Link _blank</a> </li>
                                        <li><a href="http://ihsana.com" target="_parent">Test Link _parent</a> </li>
                                        <li><a href="http://ihsana.com" target="system">Test Link system</a></li>
                                        <li><a href="http://ihsana.com">Test Normal Link</a></li>
                                        <li><a href="mailto:jasman@ihsana.com">Test Link Email</a> </li>
                                        <li><a href="http://ihsana.com"  run-app-browser="true">Test AppBrowser</a></li>
                                        </ul>
                                         ';
                                        $data_json[$i][$var] .= $lipsum->sentences(1,'blockquote');
                                        $data_json[$i][$var] .= $lipsum->sentences(1,'p');
                                        $data_json[$i][$var] .= '<p><img src="data/images/images/slidebox-'.rand(0,4).'.jpg" class="full-image" /></p>';
                                        $data_json[$i][$var] .= $lipsum->sentences(3,'p');
                                    }
                                } else {
                                    $data_json[$i][$var] .= $lipsum->sentences(1,'p');
                                    $data_json[$i][$var] .= '<p><img src="data/images/images/slidebox-'.rand(0,4).'.jpg" class="full-image" /></p>';
                                    $data_json[$i][$var] .= $lipsum->sentences(1,'p');
                                    $data_json[$i][$var] .= $lipsum->sentences(1,'blockquote');
                                    $data_json[$i][$var] .= $lipsum->sentences(1,'p');
                                }
                                break;
                            case 'link':
                                $data_json[$i][$var] = 'http://goo.gl/D1giIr';
                                break;
                            case 'share_link':
                                $data_json[$i][$var] = 'http://goo.gl/D1giIr';
                                break;
                            case 'video':
                                $data_json[$i][$var] = 'http://www.w3schools.com/html/mov_bbb.mp4';
                                break;
                            case 'ytube':
                                $youtube_id = array(
                                    '2YBNWG1fzck',
                                    'jRd7Hr3TZVo',
                                    'D4FZyNqVBpc',
                                    'E6OSVrcFp4M',
                                    );
                                $data_json[$i][$var] = $youtube_id[rand(0,count($youtube_id) - 1)];
                                break;
                            case 'audio':
                                $data_json[$i][$var] = 'http://www.w3schools.com/html/horse.mp3';
                                break;
                            case 'gmap':
                                $maps = array(
                                    '48.85693,2.3412',
                                    '-6.17149,106.82752',
                                    '35.68408,139.80885');
                                $data_json[$i][$var] = $maps[rand(0,count($maps) - 1)];
                                break;
                            case 'rating':
                                $data_json[$i][$var] = rand(2,5);
                                break;
                            case 'webview':
                                $data_json[$i][$var] = 'http://www.w3schools.com/';
                                break;
                            case 'appbrowser':
                                $data_json[$i][$var] = 'http://www.w3schools.com/';
                                break;
                            case 'slidebox':
                                $_slidebox = array();
                                for($h = 0; $h < 3; $h++) {
                                    $_slidebox[$h] = '<img src=\'data/images/images/slidebox-'.rand(0,4).'.jpg\' />';
                                }
                                $data_json[$i][$var] = implode('|',$_slidebox);
                                break;
                            case 'number':
                                $data_json[$i][$var] = rand(2,1000000);
                                break;
                            case 'float':
                                $data_json[$i][$var] = (rand(2,10) / rand(2,10));
                                break;
                            case 'date':
                                $data_json[$i][$var] = (time() + rand(0,(10 * 86400))) * 1000;
                                break;
                            case 'datetime':
                                $data_json[$i][$var] = (time() + rand(0,(10 * 86400))) * 1000;
                                break;
                            case 'date_php':
                                $data_json[$i][$var] = (time() + rand(0,(10 * 86400)));
                                break;
                            case 'datetime_php':
                                $data_json[$i][$var] = (time() + rand(0,(10 * 86400)));
                                break;
                            case 'datetime_string':
                                $data_json[$i][$var] = date('Y-m-d\Th:i:s');
                                break;
                            case 'app_email':
                                $data_json[$i][$var] = strtolower($lipsum->words(1).'@'.$lipsum->words(1).'.com');
                                break;
                            case 'app_sms':
                                $data_json[$i][$var] = '+'.(int)(rand(100000,990000)).(rand(100000,990000));
                                break;
                            case 'app_call':
                                $data_json[$i][$var] = '+'.(int)(rand(100000,990000)).(rand(100000,990000));
                                break;
                            case 'app_geo':
                                $maps = array(
                                    '48.85693,2.3412',
                                    '-6.17149,106.82752',
                                    '35.68408,139.80885');
                                $data_json[$i][$var] = $maps[rand(0,count($maps) - 1)];
                                break;
                        }
                    }
                }
                if(!isset($tables['sample_data'])) {
                    $tables['sample_data'] = false;
                }
                if($tables['sample_data'] == true) {
                    $this->sample_data[] = array("path" => "data/tables/".strtolower($this->str2var($tables['title'])).".json","data" => json_encode($data_json,JSON_UNESCAPED_UNICODE));
                }
            }
        }
    }
    function page_router($id,$page,$query = null,$link = null)
    {
        $template_btn_up = '';
        if($link == null) {
            $link = $this->str2var($page['prefix']);
        }
        $cache = null;
        if(isset($page['cache'])) {
            $cache = $this->tab.$this->tab.'cache:'.$page['cache'].','.$this->enter;
        }
        // TODO:  app_js --|-- state
        $this->app_js .= $this->tab.'.state("'.$this->pagePrefix.'.'.$this->str2var($page['prefix']).'", {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.'url: "/'.$link.$query.'",'.$this->enter;
        $this->app_js .= $cache;
        $this->app_js .= $this->tab.$this->tab.'views: {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.'"'.$id.'" : {'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'templateUrl:"templates/'.$this->pagePrefix.'-'.$page['prefix'].'.html",'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'controller: "'.$this->str2var($page['prefix']).'Ctrl"'.$this->enter;
        $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},'.$this->enter;
        // TODO:  controllers_js --|-- side_menus
        $template = '';
        $controller = '';
        // TODO:  app_js --|-- fab
        if(!isset($page['button_up'])) {
            $page['button_up'] = 'none';
        }
        if($page['button_up'] == '1') {
            $page['button_up'] = 'bottom-right';
        }
        $template = '';
        $controller = '';
        if($this->config['menu']['type'] == 'side_menus') {
            if($page['button_up'] != 'none') {
                $template_btn_up = '<button id="fab-up-button" ng-click="scrollTop()" class="button button-fab button-fab-'.$page['button_up'].' button-energized-900 spin"><i class="icon ion-arrow-up-a"></i></button>';
                $controller .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'controller: function ($timeout) {'.$this->enter;
                $controller .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'$timeout(function () {'.$this->enter;
                $controller .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'document.getElementById("fab-up-button").classList.toggle("on");'.$this->enter;
                $controller .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}, 900);'.$this->enter;
                $controller .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'}'.$this->enter;
            } else {
                $template = '';
                $controller = '';
            }
            $this->app_js .= $this->tab.$this->tab.$this->tab.'"fabButtonUp" : {'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: \''.$template_btn_up.'\','.$this->enter;
            $this->app_js .= $controller;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},'.$this->enter;
        } else {
            $this->app_js .= $this->tab.$this->tab.$this->tab.'"fabButtonUp" : {'.$this->enter;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'template: \''.$template_btn_up.'\','.$this->enter;
            $this->app_js .= $controller;
            $this->app_js .= $this->tab.$this->tab.$this->tab.$this->tab.$this->tab.'},'.$this->enter;
        }
        $this->app_js .= $this->tab.$this->tab.'}'.$this->enter;
        $this->app_js .= $this->tab.'})'.$this->enter;
        $this->app_js .= $this->enter;
    }
    function components_header_bar($title,$color)
    {
        $ionic = null;
        $ionic .= $this->tab.$this->tab.'<ion-header-bar class="bar-'.$color.'">'.$this->enter;
        $ionic .= $this->tab.$this->tab.$this->tab.'<h1 class="title">'.$title.'</h1>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'</ion-header-bar>'.$this->enter;
        return $ionic;
    }
    function components_list()
    {
        $ionic .= $this->tab.$this->tab.'<ion-list>'.$this->enter;
        $ionic .= $this->tab.$this->tab.$this->tab.'<ion-item>'.$this->enter;
        $ionic .= $this->tab.$this->tab.$this->tab.$this->tab.'<h1>'.$arg['title'].'</h1>'.$this->enter;
        $ionic .= $this->tab.$this->tab.$this->tab.'</ion-item>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'</ion-list>'.$this->enter;
        return $ionic;
    }
    function pages($arg)
    {
        $title_logo = '';
        $ionic = null;
        if(!isset($arg['title'])) {
            $arg['title'] = '';
        }
        if(!isset($arg['title-logo'])) {
            $arg['title-logo'] = '';
        }
        if($arg['title-logo'] !== '') {
            $title_logo = '<img src="'.$arg['title-logo'].'" class="page-logo" />';
        }
        if(!isset($arg['button_up'])) {
            $arg['button_up'] = 'none';
        }
        if($arg['button_up'] == '1') {
            $arg['button_up'] = 'bottom-right';
        }
        if(!isset($arg['class'])) {
            $arg['class'] = 'page-'.$arg['prefix'];
        } else {
            $arg['class'] = $arg['class'].' page-'.$arg['prefix'];
        }
        if(!isset($arg['hide-navbar'])) {
            $arg['hide-navbar'] = false;
        }
        if($arg['hide-navbar'] == true) {
            $hide_navbar = 'true';
        } else {
            $hide_navbar = 'false';
        }
        $ionic .= $this->tab.'<ion-view view-title="'.htmlentities($title_logo).' '.htmlentities($arg['title']).'" hide-nav-bar="'.$hide_navbar.'" >'.$this->enter;
        $directive = null;
        if($this->config['app']['lazyload'] == true) {
            $directive = 'lazy-scroll';
        }
        if($this->mainMenu == 'tabs') {
            if(!isset($this->popover['icon'])) {
                $this->popover['icon'] = '';
            }
            if($this->popover['icon'] == '') {
                $pop_over_icon = 'ion-android-more-vertical';
            } else {
                $pop_over_icon = $this->popover['icon'];
            }
            if(!isset($this->popover['menu'])) {
                $this->popover['menu'] = array();
            }
            if(count($this->popover['menu']) != 0) {
                $ionic .= $this->tab.$this->tab.'<!-- popover -->'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<ion-nav-buttons side="right">'.$this->enter;
                if(isset($this->config['popover']['custom-code'])) {
                    if($this->config['popover']['custom-code'] !== '') {
                        $ionic .= $this->config['popover']['custom-code'];
                    }
                }
                $ionic .= $this->tab.$this->tab.$this->tab.'<button class="button button-icon button-clear '.$pop_over_icon.'" id="menu-popover" ng-click="popover.show($event)"></button>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'</ion-nav-buttons>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<!-- ./popover -->'.$this->enter;
            } else {
                if(isset($this->config['popover']['custom-code'])) {
                    if($this->config['popover']['custom-code'] !== '') {
                        $ionic .= $this->tab.$this->tab.'<!-- popover -->'.$this->enter;
                        $ionic .= $this->tab.$this->tab.'<ion-nav-buttons side="right">'.$this->enter;
                        $ionic .= $this->config['popover']['custom-code'];
                        $ionic .= $this->tab.$this->tab.'</ion-nav-buttons>'.$this->enter;
                        $ionic .= $this->tab.$this->tab.'<!-- ./popover -->'.$this->enter;
                    }
                }
            }
            if(!isset($arg['button_up'])) {
                $arg['button_up'] = 'none';
            }
            if($arg['button_up'] == 'top-left') {
                $ionic .= $this->tab.$this->tab.'<!-- button_up -->'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<ion-nav-buttons side="left">'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'<button id="'.$this->config['app']['prefix'].'-'.$arg['prefix'].'_up_button" ng-click="scrollTop()" class="button button-fab button-fab-top-left button-energized-900"><i class="icon ion-arrow-up-a"></i></button>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'</ion-nav-buttons>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<!-- ./button_up -->'.$this->enter;
            }
            if($arg['button_up'] == 'top-right') {
                $ionic .= $this->tab.$this->tab.'<!-- button_up -->'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<ion-nav-buttons side="left">'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'<button id="'.$this->config['app']['prefix'].'-'.$arg['prefix'].'_up_button" ng-click="scrollTop()" class="button button-fab button-fab-top-right button-energized-900"><i class="icon ion-arrow-up-a"></i></button>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'</ion-nav-buttons>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<!-- ./button_up -->'.$this->enter;
            }
            $ionic .= $this->tab.$this->tab.'<ion-nav-title>'.$title_logo.' '.($arg['title']).'</ion-nav-title>'.$this->enter;
        }
        if(!isset($arg['scroll-zooming'])) {
            $arg['scroll-zooming'] = false;
        }
        if(!isset($arg['scroll'])) {
            $arg['scroll'] = false;
        }
        if(!isset($arg['overflow-scroll'])) {
            $arg['overflow-scroll'] = false;
        }
        if($arg['scroll'] == true) {
            $directive .= ' scroll="true" ';
        }
        if($arg['overflow-scroll'] == true) {
            $directive .= ' overflow-scroll="true" ';
        }
        if(!isset($arg['header-shrink'])) {
            $arg['header-shrink'] = false;
        }
        if($arg['header-shrink'] == true) {
            $directive .= ' header-shrink="true" scroll-event-interval="5" ';
        }
        if(!isset($arg['content-top'])) {
            $arg['content-top'] = false;
        }
        $ionic .= $this->tab.$this->tab.'<!-- content -->'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<ion-content delegate-handle="top" '.$directive.'  id="page-'.$arg['prefix'].'" class="'.$arg['class'].'">'.$this->enter;
        if($arg['scroll-zooming'] == true) {
            $ionic .= $this->tab.$this->tab.$this->tab.'<ion-scroll zooming="true" overflow-scroll="false" direction="xy" min-zoom="1" max-zoom="3" style="width:100%;height:100%;">'.$this->enter;
        }
        if($arg['content-top'] == true) {
            $ionic .= $this->tab.$this->tab.'<div style="height:44px;"></div>'.$this->enter;
        }
        $ionic .= $this->tab.$this->tab.$arg['content'].$this->enter;
        if($arg['scroll-zooming'] == true) {
            $ionic .= $this->tab.$this->tab.$this->tab.'</ion-scroll>'.$this->enter;
        }
        $ionic .= $this->tab.$this->tab.'</ion-content>'.$this->enter;
        $ionic .= $this->tab.$this->tab.'<!-- ./content -->'.$this->enter;
        $ionic .= $this->tab.'</ion-view>'.$this->enter;
        if(!isset($arg['after_ionicview'])) {
            $arg['after_ionicview'] = '';
        }
        if($arg['after_ionicview'] !== '') {
            $ionic .= $this->tab.$arg['after_ionicview'].$this->enter;
        }
        // TODO: html fab button_up
        if($this->mainMenu == 'tabs') {
            $space_heigh = '44px';
            if($this->config['menu']['menu_position'] == 'top') {
                $space_heigh = '0';
            }
            if($arg['button_up'] == 'bottom-left') {
                $ionic .= $this->tab.$this->tab.'<!-- button_up -->'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<div class="" style="bottom:'.$space_heigh.';position: absolute;left: 0px;right: 0px;">'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'<button id="'.$this->config['app']['prefix'].'-'.$arg['prefix'].'_up_button" ng-click="scrollTop()" class="button button-fab button-fab-bottom-left button-energized-900"><i class="icon ion-arrow-up-a"></i></button>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'</div>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<!-- ./button_up -->'.$this->enter;
            }
            if($arg['button_up'] == 'bottom-right') {
                $ionic .= $this->tab.$this->tab.'<!-- button_up -->'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<div class="" style="bottom:'.$space_heigh.';position: absolute;left: 0px;right: 0px;">'.$this->enter;
                $ionic .= $this->tab.$this->tab.$this->tab.'<button id="'.$this->config['app']['prefix'].'-'.$arg['prefix'].'_up_button" ng-click="scrollTop()" class="button button-fab button-fab-bottom-right button-energized-900"><i class="icon ion-arrow-up-a"></i></button>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'</div>'.$this->enter;
                $ionic .= $this->tab.$this->tab.'<!-- ./button_up -->'.$this->enter;
            }
        }
        return $ionic;
    }
    /**
     * Ionic::css()
     * 
     * @return
     */
    function css()
    {
        // TODO: css ------|-- css
        $ionic = null;
        $ionic .= '/** default fonts **/'.$this->enter;
        $ionic .= '@font-face{font-family:"Roboto";font-style:normal;font-weight:300;src:local("Roboto Light"),local("Roboto-Light"),url("../fonts/roboto-light.ttf") format("truetype"),url("../fonts/roboto-light.woff") format("woff")}'.$this->enter;
        $ionic .= '@font-face{font-family:"Roboto";font-style:normal;font-weight:400;src:local("Roboto"),local("Roboto-Regular"),url("../fonts/roboto-regular.ttf") format("truetype"),url("../fonts/roboto-regular.woff") format("woff")}'.$this->enter;
        $ionic .= '@font-face{font-family:"Roboto";font-style:normal;font-weight:500;src:local("Roboto Medium"),local("Roboto-Medium"),url("../fonts/roboto-medium.ttf") format("truetype"),url("../fonts/roboto-medium.woff") format("woff")}'.$this->enter;
        $ionic .= '@font-face{font-family:"Roboto";font-style:normal;font-weight:700;src:local("Roboto Bold"),local("Roboto-Bold"),url("../fonts/roboto-bold.ttf") format("truetype"),url("../fonts/roboto-bold.woff") format("woff")}'.$this->enter;
        // TODO: css ------|------ font by user
        if(isset($this->config['fonts'])) {
            if(is_array($this->config['fonts'])) {
                $ionic .= '/** custom fonts  **/'.$this->enter;
                foreach($this->config['fonts'] as $font) {
                    if(isset($font['used'])) {
                        $ionic .= '@font-face{font-family:"'.$font['font-family'].'";src:local("'.$font['font-family'].'"),url("'.$font['font-url-ttf'].'") format("truetype"),url("'.$font['font-url-woff'].'") format("woff")}'.$this->enter;
                    }
                }
            }
        }
        $ionic .= '/** typo **/'.$this->enter;
        $ionic .= '.back-text{font-size:10px}'.$this->enter;
        $ionic .= '.text-left {text-align: left !important;}'.$this->enter;
        $ionic .= '.text-right {text-align: right !important; }'.$this->enter;
        $ionic .= '.text-center {text-align: center !important;}'.$this->enter;
        $ionic .= '.text-justify {text-align: justify;}'.$this->enter;
        $ionic .= '.text-nowrap {white-space: nowrap;}'.$this->enter;
        $ionic .= '.text-lowercase {text-transform: lowercase;}'.$this->enter;
        $ionic .= '.text-uppercase {text-transform: uppercase;}'.$this->enter;
        $ionic .= '.text-capitalize {text-transform: capitalize;}'.$this->enter;
        $ionic .= '.to_trusted .item-text-wrap p {margin-bottom:14px; margin-top:14px;}'.$this->enter;
        $ionic .= '.to_trusted .item-text-wrap img {height:auto}'.$this->enter;
        $ionic .= '.to_trusted h1{font-size:36px;margin:14px 0 14px}'.$this->enter;
        $ionic .= '.to_trusted h2{font-size:30px;margin:14px 0 14px}'.$this->enter;
        $ionic .= '.to_trusted h3{font-size:24px;margin:14px 0 14px}'.$this->enter;
        $ionic .= '.to_trusted h4{font-size:18px;margin:14px 0 14px}'.$this->enter;
        $ionic .= '.to_trusted h5{font-size:14px;margin:14px 0 14px}'.$this->enter;
        $ionic .= '.to_trusted h6{font-size:12px;margin:14px 0 14px}'.$this->enter;
        $ionic .= '.to_trusted p{font-size:14px;margin:14px 0 14px}'.$this->enter;
        $ionic .= '.to_trusted img{max-width:100% !important;height:auto;background-color:#eee;margin-bottom:15px}'.$this->enter;
        $ionic .= '.to_trusted hr{border:1px solid #eee}'.$this->enter;
        $ionic .= '.to_trusted table {border-collapse:collapse;width: 100%;padding-bottom:6px;padding-top:6px;}'.$this->enter;
        $ionic .= '.to_trusted table,.to_trusted th,.to_trusted td{border: 1px solid #dddddd;padding:6px;}'.$this->enter;
        $ionic .= '.to_trusted dt{padding-top:6px;font-weight: 700;}'.$this->enter;
        $ionic .= '.to_trusted dd{padding-bottom:6px;}'.$this->enter;
        $ionic .= '.to_trusted dfn,.to_trusted cite,.to_trusted em,.to_trusted i{font-style:italic;}'.$this->enter;
        $ionic .= '.to_trusted ul{list-style: disc;margin-left: 1.5em;padding-bottom:6px;padding-top:6px;}'.$this->enter;
        $ionic .= '.to_trusted ol{list-style: decimal;margin-left: 1.5em;padding-bottom:6px;padding-top:6px;}'.$this->enter;
        $ionic .= '.to_trusted address{font-style: italic;padding-bottom:6px;padding-top:6px;}'.$this->enter;
        $ionic .= '.to_trusted code{background-color: #d1d1d1;padding: 0.125em 0.25em;}'.$this->enter;
        $ionic .= '.to_trusted pre{border: 1px solid #d1d1d1;line-height: 1.3125;padding: 0.125em 0.25em;font-family: \'Droid Sans Mono\';}'.$this->enter;
        $ionic .= '.to_trusted iframe{width:100%;height:auto;}'.$this->enter;
        $ionic .= '.to_trusted .alignleft{float:left;margin: 0.375em 1.75em 1.75em 0;}'.$this->enter;
        $ionic .= '.to_trusted .alignright{float:right;margin: 0.375em 0 1.75em 1.75em;}'.$this->enter;
        $ionic .= '.to_trusted .wp-video{max-width:100% !important;padding-bottom:56%;overflow:hidden;padding-bottom:56%;position:relative;height:0}'.$this->enter;
        $ionic .= '.to_trusted .wp-video video{left:0;position:absolute;top:0;width:100%;height:auto;background:#eee}'.$this->enter;
        $ionic .= '.to_trusted .wp-caption{height:auto;max-width:100% !important}'.$this->enter;
        $ionic .= '.to_trusted .wp-audio-shortcode{visibility: initial !important;}'.$this->enter;
        $ionic .= '.to_trusted.small p{font-size:12px;}'.$this->enter;
        $ionic .= '.to_trusted.small h1{font-size:32px}'.$this->enter;
        $ionic .= '.to_trusted.small h2{font-size:28px}'.$this->enter;
        $ionic .= '.to_trusted.small h3{font-size:22px}'.$this->enter;
        $ionic .= '.to_trusted.small h4{font-size:16px}'.$this->enter;
        $ionic .= '.to_trusted.small h5{font-size:12px}'.$this->enter;
        $ionic .= '.to_trusted.small h6{font-size:10px}'.$this->enter;
        $ionic .= '.to_trusted.normal p{font-size:14px}'.$this->enter;
        $ionic .= '.to_trusted.normal h1{font-size:36px}'.$this->enter;
        $ionic .= '.to_trusted.normal h2{font-size:30px}'.$this->enter;
        $ionic .= '.to_trusted.normal h3{font-size:24px}'.$this->enter;
        $ionic .= '.to_trusted.normal h4{font-size:18px}'.$this->enter;
        $ionic .= '.to_trusted.normal h5{font-size:14px}'.$this->enter;
        $ionic .= '.to_trusted.normal h6{font-size:12px}'.$this->enter;
        $ionic .= '.to_trusted.large p{font-size:18px;}'.$this->enter;
        $ionic .= '.to_trusted.large h1{font-size:42px}'.$this->enter;
        $ionic .= '.to_trusted.large h2{font-size:36px}'.$this->enter;
        $ionic .= '.to_trusted.large h3{font-size:28px}'.$this->enter;
        $ionic .= '.to_trusted.large h4{font-size:24px}'.$this->enter;
        $ionic .= '.to_trusted.large h5{font-size:18px}'.$this->enter;
        $ionic .= '.to_trusted.large h6{font-size:16px}'.$this->enter;
        $ionic .= 'table {width:100%;max-width:100%;margin:0;background-color: transparent; border-collapse: collapse;border-spacing: 0;}'.$this->enter;
        $ionic .= 'table > tbody > tr:nth-of-type(2n+1){background-color: #efefef;}'.$this->enter;
        $ionic .= 'table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {padding: 8px;line-height: 1.846;vertical-align: middle;border-top: 1px solid #dddddd;}'.$this->enter;
        $ionic .= '.bar .title {text-align: center !important;}'.$this->enter;
        $ionic .= '.tab-item {opacity:0.5}'.$this->enter;
        $ionic .= '.header-item img{vertical-align: middle;}'.$this->enter;
        $ionic .= '/** fontsize **/'.$this->enter;
        $ionic .= 'h1, .h1 { font-size:36px}'.$this->enter;
        $ionic .= 'h2, .h2 { font-size:30px}'.$this->enter;
        $ionic .= 'h3, .h3 { font-size:24px}'.$this->enter;
        $ionic .= 'h4, .h4 { font-size:18px}'.$this->enter;
        $ionic .= 'h5, .h5 { font-size:14px}'.$this->enter;
        $ionic .= 'h6, .h6 { font-size:12px}'.$this->enter;
        $ionic .= 'h1 small, .h1 small { font-size:24px}'.$this->enter;
        $ionic .= 'h2 small, .h2 small { font-size:18px}'.$this->enter;
        $ionic .= 'h3 small, .h3 small,'.$this->enter;
        $ionic .= 'h4 small, .h4 small { font-size:14px}'.$this->enter;
        $ionic .= '.item h1 { font-size:18px;}'.$this->enter;
        $ionic .= '.item p{margin-bottom: 12px;}'.$this->enter;
        $ionic .= '.scroll {height: 100%;}'.$this->enter;
        $ionic .= '/** ratio **/'.$this->enter;
        $ionic .= '.ratio1x2:before{padding-top: 200%;display: block;content: " ";}'.$this->enter;
        $ionic .= '.ratio1x1:before{padding-top: 100%;display: block;content: " ";}'.$this->enter;
        $ionic .= '.ratio4x3:before{padding-top: 75%;display: block;content: " ";}'.$this->enter;
        $ionic .= '.ratio16x9:before{padding-top: 56.25%;display: block;content: " ";}'.$this->enter;
        $ionic .= '.ratio2x1:before{padding-top: 50%;display: block;content: " ";}'.$this->enter;
        $ionic .= '.list .item {border: 0.5px solid rgba(160,160,160,0.12);}'.$this->enter;
        $ionic .= '.list .item-md-label {border: 0;}'.$this->enter;
        $ionic .= '.list .noborder {border: 0 solid transparent;}'.$this->enter;
        $ionic .= '.list .item-divider {color: inherit;}'.$this->enter;
        $ionic .= '.item-thumbnail-left > img:first-child {border-radius: 0%}'.$this->enter;
        $ionic .= '.item-icon-right .icon:before {background: #fff;}'.$this->enter;
        $ionic .= '.item-options .icon:before {background: transparent;}'.$this->enter;
        $ionic .= '.item-radio input:checked + .radio-content .item-content {background:transparent ;}'.$this->enter;
        $ionic .= '.item-radio .radio-icon {color:#387EF5}'.$this->enter;
        $ionic .= '.bar-header-inset,.bar-header-inset input{background:#eee;margin-top:2px;}'.$this->enter;
        $ionic .= '/** Template for Data Listing **/'.$this->enter;
        $ionic .= '.item-thumbnail-3 {overflow: hidden;}'.$this->enter;
        $ionic .= '.item-thumbnail-3 .item{border:0;height: 80px !important;min-height: 80px !important;}'.$this->enter;
        $ionic .= '.item-thumbnail-3 .item *{color:#fff}'.$this->enter;
        $ionic .= '.item-thumbnail-3 .item.item-light *{color:#000};'.$this->enter;
        $ionic .= '.item-thumbnail-3, .item-thumbnail-3 .item-content {min-height:80px !important;height: 80px !important;}'.$this->enter;
        $ionic .= '.item-thumbnail-3 img {border-radius: 0% !important; top:0 !important; height: 80px !important;width: 80px !important;}'.$this->enter;
        $ionic .= '.item-thumbnail-3 .icon{bottom: 10px;position: absolute;right: 16px;font-size:26px}'.$this->enter;
        $ionic .= '.item-thumbnail-3 h3{font-size:16px}'.$this->enter;
        $ionic .= '.item-thumbnail-3 p{font-size:11px}'.$this->enter;
        $ionic .= '.item-input.item-stacked-label, .item-input.item-floating-label,.item-input.item-placeholder-label{padding: 6px 5px 5px 16px;}'.$this->enter;
        $ionic .= '/** fix popover size **/'.$this->enter;
        $ionic .= 'ion-popover-view.fit {height:auto;}'.$this->enter;
        $ionic .= 'ion-popover-view.fit ion-content {position: relative;}'.$this->enter;
        $ionic .= 'ion-popover-view.fit ion-header-bar {position: relative;}'.$this->enter;
        $ionic .= 'ion-popover-view.fit .has-header{top:0  !important;;}'.$this->enter;
        $ionic .= 'ion-popover-view.fit .has-header:before{height:0  !important;;}'.$this->enter;
        $ionic .= '.platform-android ion-popover-view.fit {margin-top: 10px;}'.$this->enter;
        $ionic .= '.platform-ios ion-popover-view.fit {padding-top: 10px;padding-bottom: 10px;}'.$this->enter;
        $ionic .= '.button-outline.button-outline {box-shadow:none !important;-webkit-box-shadow:none !important;-moz-box-shadow:none !important;}'.$this->enter;
        $ionic .= '.loading-container .loading{background-color:transparent !important;}'.$this->enter;
        $ionic .= '.hero > .content > .avatar {height:72px;width:72px;border:0; overflow:hidden;}'.$this->enter;
        $ionic .= '.hero-md{max-height:0px !important;height:0px !important;box-shadow: 0 2px 5px 0 rgba(0,0,0,.26);}'.$this->enter;
        $ionic .= '.hero .hero-md-content{ width:100%;height: 100% !important;}'.$this->enter;
        $ionic .= '.avatar {border:0;overflow:hidden;}'.$this->enter;
        $ionic .= '.item-md-label {width: auto !important;}'.$this->enter;
        $ionic .= '.item-md-label .input-label{color: #fff;opacity: .5;padding: 0 10px !important;}'.$this->enter;
        $ionic .= '.item-md-label input {background-color: rgba(0,0,0,.6);}'.$this->enter;
        $ionic .= '@-moz-document url-prefix() { .item-md-label input.ng-not-empty {padding: 0px 10px;}}'.$this->enter;
        $ionic .= '.item-select select{background: transparent;}'.$this->enter;
        $ionic .= '.item-button-right .button{padding:0 12px;}'.$this->enter;
        $ionic .= '.item-input-inset input{width:100%;}'.$this->enter;
        $ionic .= '.button-small {font-size: 12px;height: inherit;padding:0 12px;}'.$this->enter;
        $ionic .= '.popup-buttons .button {font-size: 12px;height: inherit;padding:0 12px;}'.$this->enter;
        $ionic .= 'blockquote footer, blockquote small {display: block;font-size: 80%;line-height: 1.42857143; color: #777;}'.$this->enter;
        $ionic .= 'blockquote .small::before, blockquote footer::before, blockquote small::before {content: \'\2014 \00A0\';}'.$this->enter;
        $ionic .= '/** fix position **/'.$this->enter;
        $ionic .= '.relative {position:relative !important;left:0;right:0}'.$this->enter;
        $ionic .= '.fullscreen .slider {position: absolute !important;top: 0 !important;left: 0 !important;bottom: 0 !important;right: 0 !important;}'.$this->enter;
        $ionic .= '.fullscreen .card, .fullscreen .scroll{height: 100%;min-height: 100%;}'.$this->enter;
        $ionic .= 'iframe.fullscreen {width: 100%;height: 100%;padding:0;margin:0;min-height:100%;border: none;display: block;}'.$this->enter;
        $ionic .= '.page-iframe .scroll{height:100%}'.$this->enter;
        $ionic .= '/** ionic rating **/'.$this->enter;
        $ionic .= 'ul.rating li {display: inline !important; border: 0px;background: none;}'.$this->enter;
        $ionic .= 'ul.rating li i{color:#FFCC00}'.$this->enter;
        $ionic .= '.tabs.tabs-dark{background-color: #444444 !important;}'.$this->enter;
        $ionic .= '/** video, embed and object, google map  **/'.$this->enter;
        $ionic .= '.embed_container{line-height:0}'.$this->enter;
        $ionic .= '.embed_container iframe,.embed_container ng-map,.embed_container object,.embed_container embed,.embed_container video{width:100%}'.$this->enter;
        $ionic .= '.embed_container{height:0;overflow:hidden;padding-bottom:56%;position:relative}'.$this->enter;
        $ionic .= '.embed_container iframe,.embed_container ng-map,.embed_container object,.embed_container embed,.embed_container video{height:100%;left:0;position:absolute;top:0;width:100%;background: #eee}'.$this->enter;
        $ionic .= '.menu-open .ion-navicon {transform: rotate(-360deg);-webkit-transition: all 0.2s ease-in-out;transition: all 0.2s ease-in-out;}'.$this->enter;
        $ionic .= '.menu-open .ion-navicon:before {content: "\f2ca";}'.$this->enter;
        $ionic .= '/** image loader  **/'.$this->enter;
        $ionic .= '.image-loader-container .image-loader{stroke: #eeeeee;}'.$this->enter;
        $ionic .= '.item-thumbnail-left img,.item-avatar img,.hero img {background-color:#dedede;border:0}'.$this->enter;
        $ionic .= '.image-loader-container {margin: 9px;max-width: 81px;max-height: 81px;width: 100%;height: 100%;top: 0;left: 0;position: absolute; background: transparent; display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: center;  align-content: stretch;align-items: center;}'.$this->enter;
        $ionic .= '.item-avatar .image-loader-container {max-width:48px;max-height:48px;top: 10px;left:8px;}'.$this->enter;
        $ionic .= '.item-thumbnail-left .image-loader-container, .item-thumbnail-right .image-loader-container{max-width: 81px;max-height: 81px;top:0px;left:0px;}'.$this->enter;
        $ionic .= '.item-avatar .avatar,.item-avatar > img:first-child {max-width:48px;max-height:48px;width:48px;height:48px;}'.$this->enter;
        $ionic .= '.bar .title {left:40px !important; right:40px !important;}'.$this->enter;
        $ionic .= '*[dir="rtl"]{ text-align: right !important; direction: rtl !important;}'.$this->enter;
        $ionic .= '*[dir="rtl"] .item-input input{padding-right: 16px;direction: rtl !important;}'.$this->enter;
        $ionic .= '*[dir="rtl"] .item-input .item-label{padding-right: 16px;}'.$this->enter;
        $ionic .= '*[dir="rtl"] .item-input textarea{padding-right: 16px;}'.$this->enter;
        $ionic .= '*[dir="rtl"] .item-input, *[dir="rtl"] .item-divider{text-align: right !important; direction: rtl !important;}'.$this->enter;
        $ionic .= '*[dir="rtl"] .item-floating-label .input-label{padding-right: 16px;}'.$this->enter;
        $ionic .= '*[dir="rtl"] .placeholder-icon:first-child {padding-right: 16px;}'.$this->enter;
        $ionic .= '.slideshow_container { height:0;overflow:hidden;padding-bottom:66%;position:relative}'.$this->enter;
        $ionic .= '.slideshow_container .slideshow{width:100%;height:100%;left:0;position:absolute;top:0;width:100%;background: #eee}'.$this->enter;
        $ionic .= '.slideshow_container .slideshow img{max-width:100%;height:auto;}'.$this->enter;
        $ionic .= '.slideshow_container { min-height: 128px; position: relative !important;}'.$this->enter;
        $ionic .= '.slide-box-title,.tags-heroes-title {font-weight: 600;display: block;text-decoration:none;text-decoration-style: none;padding:3px 12px;margin: 0;border: 0;border-bottom: 1px solid rgba(0,0,0,.25);}'.$this->enter;
        $ionic .= '.tags-heroes-content .button.button-full{margin:0px;}'.$this->enter;
        // TODO: css ------|------ slide hero
        $ionic .= '.slide-box-hero .swiper-pagination-bullet {background: #fff;}'.$this->enter;
        $ionic .= '.slide-box-hero .swiper-pagination-bullet-active{width:10px;height:10px;}'.$this->enter;
        $ionic .= '.slide-box-hero { height:0;overflow:hidden;padding-bottom:66%;position:relative}'.$this->enter;
        $ionic .= '.slide-box-hero .slide-box-hero-content{width:100%;height:100%;left:0;position:absolute;top:0;width:100%;background: #333}'.$this->enter;
        $ionic .= '.slide-box-hero .slide-box-hero-content img{max-width:100%;height:auto;width: 100%;background: url("../data/images/blank.jpg") no-repeat center center;background-size: cover;}'.$this->enter;
        $ionic .= '.slide-box-hero { min-height: 128px; position: relative !important;}'.$this->enter;
        $ionic .= '.slide-box-hero .slide-box-hero-container{background-size:cover !important;width:100%;height:100%;}'.$this->enter;
        $ionic .= '.slide-box-hero .caption{bottom:16px;position:absolute;display:block;color: #fff;}'.$this->enter;
        $ionic .= '.slide-box-hero .caption h2{font-size: 20px;color: #fff;opacity: 0.95;text-shadow: 1px 1px 1px rgba(0,0,0,0.9);background: rgba(0,0,0,0.2) !important;padding: 6px;}'.$this->enter;
        $ionic .= '.slide-box-hero .caption a{text-decoration: unset;font-size: 16px;color: #fff;opacity: 0.95;text-shadow: 1px 1px 1px rgba(0,0,0,0.9);}'.$this->enter;
        $ionic .= '.slide-box-avatar {height:80px}'.$this->enter;
        $ionic .= '.slide-box-avatar .avatar{padding:0;margin-left: auto;margin-right: auto; margin-top:8px;margin-bottom:8px;position: relative;display: inherit;left:0;right:0;width: 64px;height: 64px;background: url("../data/images/blank.jpg") no-repeat center center;background-size: cover;}'.$this->enter;
        $ionic .= '.slide-box-avatar .swiper-pagination,.slide-box-thumbnail .swiper-pagination{visibility: hidden;}'.$this->enter;
        $ionic .= '.slide-box-thumbnail {height:120px;}'.$this->enter;
        $ionic .= '.slide-box-thumbnail .slide-box-thumbnail-item{border-left: .5px solid rgba(0,0,0,.05);border-right: .5px solid rgba(0,0,0,.05);}'.$this->enter;
        $ionic .= '.slide-box-thumbnail .thumbnail{height: 80px;width: 80px;padding:0;margin:0;margin-left: auto;margin-right: auto;position: relative;display: inherit;left:0;right:0;background: url("../data/images/blank.jpg") no-repeat center center;background-size: cover;}'.$this->enter;
        $ionic .= '.slide-box-thumbnail .caption{padding:3px;font-size:10px;line-height:12px;}'.$this->enter;
        $ionic .= '.slide-box-thumbnail .caption a{text-decoration: unset;}'.$this->enter;
        $ionic .= '.intro-box {margin-bottom: 0px;}'.$this->enter;
        $ionic .= '.intro-box .list.card:last-child {margin-bottom: 10px;}'.$this->enter;
        $ionic .= '.item-avatar img,img.full-image,.item-image img{background: url("../data/images/blank.jpg") no-repeat center center;background-size: cover;}'.$this->enter;
        $ionic .= '.ion-slide-tabs.slider{height: 100%}'.$this->enter;
        $ionic .= '.slidingTabs {height:48px;z-index:10}'.$this->enter;
        $ionic .= '.slidingTabs .scroll{height:auto;min-width:100%}'.$this->enter;
        $ionic .= '.slidingTabs ul{height:48px}'.$this->enter;
        $ionic .= '.slidingTabs ul li{height:48px;line-height:48px;text-align:center;float:left;margin:0;padding:0 12px 20px 12px;font-size:11px;text-transform:uppercase;-webkit-transition:color .25s ease-in-out;transition:color .25s ease-in-out;position:relative;overflow:hidden}'.$this->enter;
        $ionic .= '.slidingTabs ul li.tab-active{border-bottom:3px solid}'.$this->enter;
        $ionic .= '.slidingTabs ul li .ink{display:block;position:absolute;border-radius:100%;transform:scale(0)}'.$this->enter;
        $ionic .= '.slidingTabs ul li .ink.animate{-webkit-animation:ripple 0.65s linear;animation:ripple 0.65s linear}'.$this->enter;
        $ionic .= '.slidingTabs .tab-indicator-wrapper{width:100%;height:2px;-webkit-transform:translateY(-2px);position:absolute}'.$this->enter;
        $ionic .= '.slidingTabs .tab-indicator-wrapper .tab-indicator{height:100%;width:70px;position:relative}'.$this->enter;
        $ionic .= '.slidingTabs ul li.tab-active:nth-of-type(1n+1){border-color:#11C1F3;color:#11C1F3}'.$this->enter;
        $ionic .= '.slidingTabs ul li.tab-active:nth-of-type(2n+2){border-color:#E65100;color:#E65100}'.$this->enter;
        $ionic .= '.slidingTabs ul li.tab-active:nth-of-type(3n+3){border-color:#4CAF50;color:#4CAF50}'.$this->enter;
        $ionic .= '.slidingTabs ul li.tab-active:nth-of-type(4n+4){border-color:#11C1F3;color:#11C1F3}'.$this->enter;
        $ionic .= '.slidingTabs ul li.tab-active:nth-of-type(5n+5){border-color:#E65100;color:#E65100}'.$this->enter;
        $ionic .= '.slidingTabs ul li.tab-active:nth-of-type(6n+6){border-color:#4CAF50;color:#4CAF50}'.$this->enter;
        $ionic .= '.slidingTabs ul li.tab-active:nth-of-type(7n+7){border-color:#11C1F3;color:#11C1F3}'.$this->enter;
        $ionic .= '.slidingTabs ul li.tab-active:nth-of-type(8n+8){border-color:#E65100;color:#E65100}'.$this->enter;
        $ionic .= '.slidingTabs ul li.tab-active:nth-of-type(9n+9){border-color:#4CAF50;color:#4CAF50}'.$this->enter;
        $ionic .= '.ion-slide-tabs .slider-slide:nth-of-type(1n+1) .icon{color:#11C1F3}'.$this->enter;
        $ionic .= '.ion-slide-tabs .slider-slide:nth-of-type(2n+2) .icon{color:#E65100}'.$this->enter;
        $ionic .= '.ion-slide-tabs .slider-slide:nth-of-type(3n+3) .icon{color:#4CAF50}'.$this->enter;
        $ionic .= '.ion-slide-tabs .slider-slide:nth-of-type(4n+4) .icon{color:#11C1F3}'.$this->enter;
        $ionic .= '.ion-slide-tabs .slider-slide:nth-of-type(5n+5) .icon{color:#E65100}'.$this->enter;
        $ionic .= '.ion-slide-tabs .slider-slide:nth-of-type(6n+6) .icon{color:#4CAF50}'.$this->enter;
        $ionic .= '.ion-slide-tabs .slider-slide:nth-of-type(7n+7) .icon{color:#11C1F3}'.$this->enter;
        $ionic .= '.ion-slide-tabs .slider-slide:nth-of-type(8n+8) .icon{color:#E65100}'.$this->enter;
        $ionic .= '.ion-slide-tabs .slider-slide:nth-of-type(9n+9) .icon{color:#4CAF50}'.$this->enter;
        $ionic .= '.bar-positive-900,.bar-calm-900,.bar-balanced-900,.bar-energized-900,.bar-assertive-900,.bar-royal-900{color: #fff;}'.$this->enter;
        $ionic .= '.bar-positive-900 .title,.bar-calm-900 .title,.bar-balanced-900 .title,.bar-energized-900 .title,.bar-assertive-900 .title,.bar-royal-900 .title{color: #fff;}'.$this->enter;
        $ionic .= '@-webkit-keyframes ripple{100%{opacity:0;transform:scale(2.5)}}'.$this->enter;
        $ionic .= '/** pdf reader **/'.$this->enter;
        $ionic .= '.wrapper {margin: 0 auto; width: 960px; }'.$this->enter;
        $ionic .= '.pdf-controls { width: 100%; display: block; background: #eee; padding: 1em;}'.$this->enter;
        $ionic .= '.rotate0 {-webkit-transform: rotate(0deg); transform: rotate(0deg);}'.$this->enter;
        $ionic .= '.rotate90 {-webkit-transform: rotate(90deg); transform: rotate(90deg);}'.$this->enter;
        $ionic .= '.rotate180 {-webkit-transform: rotate(180deg); transform: rotate(180deg);}'.$this->enter;
        $ionic .= '.rotate270 {-webkit-transform: rotate(270deg); transform: rotate(270deg);}'.$this->enter;
        $ionic .= '.fixed { position: fixed; top: 0; left: calc(50% - 480px); z-index: 100; width: 100%; padding: 1em; background: rgba(238, 238, 238,.9); width: 960px;}'.$this->enter;
        // TODO: css ------|------ header by uses
        if(!isset($this->config['menu']['header_background'])) {
            $this->config['menu']['header_background'] = '';
        }
        $ionic .= '/** header **/'.$this->enter;
        if($this->config['menu']['header_background'] == 'images') {
            if($this->config['menu']['header_image_background'] != '') {
                $ionic .= '.bar-images{background: transparent !important; background-image:url("../'.$this->config['menu']['header_image_background'].'") !important; color:#ffffff; background-position: left top !important; background-repeat: no-repeat !important; background-size: cover !important;overflow: hidden;}';
            }
        }
        $ionic .= $this->enter;
        $ionic .= '/** menu **/'.$this->enter;
        $ionic .= $this->add_css;
        $ionic .= $this->enter;
        $ionic .= '/** option page **/'.$this->enter;
        foreach($this->subPages as $page) {
            // TODO: option page
            if(!isset($page['img_bg'])) {
                $page['img_bg'] = '';
            }
            if(!isset($page['title-tranparant'])) {
                $page['title-tranparant'] = false;
            }
            if(!isset($page['remove-has-header'])) {
                $page['remove-has-header'] = false;
            }
            // TODO: Background
            if($this->config['menu']['type'] != 'tabs') {
                $body_id = '#'.$this->config['app']['prefix'].'-'.$page['prefix'];
                $page_id = '#page-'.$page['prefix'];
                if($page['img_bg'] != '') {
                    $ionic .= $page_id.'{background: url("../'.$page['img_bg'].'") no-repeat left top fixed;background-size: cover;width:100%;height:100%;background-repeat:no-repeat;}'.$this->enter;
                }
                if($page['title-tranparant'] == true) {
                    $ionic .= $body_id.' #navbar-right-top .bar-header,'.$body_id.' #navbar-left-top .bar-header{background: transparent;box-shadow:none;-moz-box-shadow:none;-webkit-box-shadow:none;}'.$this->enter;
                    $ionic .= $body_id.' .has-header{top:0;}'.$this->enter;
                    $ionic .= $body_id.' .has-header:before{content:" ";height:44px;display:block}'.$this->enter;
                }
                if($page['remove-has-header'] == true) {
                    $ionic .= $body_id.' .has-header{top:0;}'.$this->enter;
                    $ionic .= $body_id.' .has-header:before{content:" ";height:0;display:block}'.$this->enter;
                }
            } else {
                $body_id = '#'.$this->config['app']['prefix'].'-'.$page['prefix'];
                $page_id = '#page-'.$page['prefix'];
                if($page['img_bg'] != '') {
                    $ionic .= $page_id.'{background: url("../'.$page['img_bg'].'") no-repeat left top fixed;background-size: cover;width:100%;height:100%;background-repeat:no-repeat;}'.$this->enter;
                }
                if($page['title-tranparant'] == true) {
                    $ionic .= $body_id.' .navbar-title .bar-header{background: transparent;box-shadow:none;-moz-box-shadow:none;-webkit-box-shadow:none;}'.$this->enter;
                    $ionic .= $page_id.'.has-header{top:0;}'.$this->enter;
                    $ionic .= $page_id.'.has-header:before{content:" ";height:44px;display:block}'.$this->enter;
                }
                if($page['remove-has-header'] == true) {
                    $ionic .= $page_id.'.has-header{top:0;}'.$this->enter;
                    $ionic .= $page_id.'.has-header:before{content:" ";height:0;display:block}'.$this->enter;
                }
            }
        }
        foreach($this->subPages as $page) {
            if(isset($page['css'])) {
                $ionic .= '/** page '.$page['prefix'].' **/'.$this->enter;
                $ionic .= $page['css'];
                $ionic .= $this->enter;
            }
        }
        $ionic .= '/** custom css **/'.$this->enter;
        if(isset($this->config['css'])) {
            $ionic .= $this->config['css'];
        }
        return $ionic;
    }
    function readMe()
    {
        $app_config = $this->config['app'];
        $readme = null;
        $readme .= '# '.$app_config['name']."\r\n";
        $readme .= $app_config['description']."\r\n";
        return $readme;
    }
    function buildXML($phonegap)
    {
        $app_config = $this->config['app'];
        if(!isset($app_config['start'])) {
            $app_config['start'] = 'index.html';
        }
        if($app_config['start'] == '') {
            $app_config['start'] = 'index.html';
        }
        if(!isset($app_config['name_unicode'])) {
            $app_config['name_unicode'] = $app_config['name'];
        }
        if($app_config['name_unicode'] == '') {
            $app_config['name_unicode'] = $app_config['name'];
        }
        if(!isset($app_config['splash-screen-delay'])) {
            $app_config['splash-screen-delay'] = '6000';
        }
        if(!isset($app_config['fade-splash-screen-duration'])) {
            $app_config['fade-splash-screen-duration'] = '6000';
        }
        if(!isset($app_config['sub-version'])) {
            $app_config['sub-version'] = false;
        }
        $subversion = '';
        if($app_config['sub-version'] == true) {
            $subversion = '.'.date("ymd");
        }
        if(!isset($this->config['configxml']['phonegap_cli'])) {
            $this->config['configxml']['phonegap_cli'] = 'cli-8.0.0';
        }
        if($this->config['configxml']['phonegap_cli'] == '') {
            $this->config['configxml']['phonegap_cli'] = 'cli-8.0.0';
        }
        if(!isset($this->config['configxml']['orientation'])) {
            $this->config['configxml']['orientation'] = '';
        }
        if($this->config['configxml']['orientation'] == 'default') {
            $this->config['configxml']['orientation'] = '';
        }
        $xml = null;
        // TODO: config.xml
        $xml .= '<?xml version=\'1.0\' encoding=\'utf-8\'?>'.$this->enter;
        $xml .= '<widget id="'.JSM_PACKAGE_NAME.'.'.str_replace('_','',$this->str2var($app_config['company'])).'.'.str_replace('_','',$app_config['prefix']).'" version="'.str_replace("&lrm;","",htmlentities($app_config['version'])).$subversion.'" xmlns="http://www.w3.org/ns/widgets" xmlns:cdv="http://cordova.apache.org/ns/1.0">'.$this->enter;
        $xml .= $this->tab.'<name>'.str_replace("&lrm;","",htmlentities($app_config['name_unicode'])).'</name>'.$this->enter;
        $xml .= $this->tab.'<description>'.str_replace("&lrm;","",($app_config['description'])).'</description>'.$this->enter;
        $xml .= $this->tab.'<author email="'.htmlentities($app_config['author_email']).'" href="'.htmlentities($app_config['author_url']).'">'.str_replace("&lrm;","",($app_config['author_name'])).'</author>'.$this->enter;
        $xml .= $this->tab.'<content src="'.htmlentities($app_config['start']).'" />'.$this->enter;
        $xml .= $this->tab.'<!-- Network Request Whitelist -->'.$this->enter;
        $xml .= $this->tab.'<access origin="*" />'.$this->enter;
        $xml .= $this->tab.'<platform name="android">'.$this->enter;
        $xml .= $this->tab.$this->tab.'<access origin="market:*" launch-external="yes"/>'.$this->enter;
        $xml .= $this->tab.$this->tab.'<access origin="tel:*" launch-external="yes"/>'.$this->enter;
        $xml .= $this->tab.$this->tab.'<access origin="geo:*" launch-external="yes"/>'.$this->enter;
        $xml .= $this->tab.$this->tab.'<access origin="mailto:*" launch-external="yes"/>'.$this->enter;
        $xml .= $this->tab.$this->tab.'<access origin="sms:*" launch-external="yes"/>'.$this->enter;
        $xml .= $this->tab.$this->tab.'<access origin="whatsapp:*" launch-external="yes"/>'.$this->enter;
        $xml .= $this->tab.$this->tab.'<access origin="line:*" launch-external="yes"/>'.$this->enter;
        $xml .= $this->tab.$this->tab.'<access origin="twitter:*" launch-external="yes"/>'.$this->enter;
        $xml .= $this->tab.$this->tab.'<access origin="fb:*" launch-external="yes"/>'.$this->enter;
        $xml .= $this->tab.$this->tab.'<access origin="fbapi20130214:*" launch-external="yes"/>'.$this->enter;
        $xml .= $this->tab.$this->tab.'<access origin="skype:*" launch-external="yes"/>'.$this->enter;
        $xml .= $this->tab.'</platform>'.$this->enter;
        $xml .= $this->tab.'<!-- Navigation Whitelist -->'.$this->enter;
        $xml .= $this->tab.'<allow-navigation href="http://*/*" />'.$this->enter;
        $xml .= $this->tab.'<allow-navigation href="https://*/*" />'.$this->enter;
        $xml .= $this->tab.'<allow-navigation href="data:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-navigation href="file:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-navigation href="http://localhost:8080/*"/>'.$this->enter;
        $xml .= $this->tab.'<!-- Intent Whitelist -->'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="http://*/*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="https://*/*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="rtsp://*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="rtmp://*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="rtp://*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="udp://*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="file://*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="tel:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="sms:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="mms:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="mailto:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="geo:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="google.navigation:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="google.streetview:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="maps:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="map:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="googlemap:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="whatsapp:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="line:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="twitter:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="fb:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="fbapi20130214:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="skype:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="linkedin:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="googlegmail:*" />'.$this->enter;
        $xml .= $this->tab.'<allow-intent href="youtube:*" />'.$this->enter;

        $xml .= $this->tab.'<platform name="android">'.$this->enter;
        $xml .= $this->tab.$this->tab.'<allow-intent href="market:*" />'.$this->enter;
        $xml .= $this->tab.'</platform>'.$this->enter;
        $xml .= $this->tab.'<platform name="ios">'.$this->enter;
        $xml .= $this->tab.$this->tab.'<allow-intent href="itms:*" />'.$this->enter;
        $xml .= $this->tab.$this->tab.'<allow-intent href="itms-apps:*" />'.$this->enter;
        $xml .= $this->tab.'</platform>'.$this->enter;
        $xml .= $this->tab.'<preference name="webviewbounce" value="false"/>'.$this->enter;
        $xml .= $this->tab.'<preference name="UIWebViewBounce" value="false"/>'.$this->enter;
        $xml .= $this->tab.'<preference name="DisallowOverscroll" value="true"/>'.$this->enter;
        $xml .= $this->tab.'<preference name="SplashScreenDelay" value="'.(int)$app_config['splash-screen-delay'].'"/>'.$this->enter;
        $xml .= $this->tab.'<preference name="FadeSplashScreenDuration" value="'.(int)$app_config['fade-splash-screen-duration'].'"/>'.$this->enter;
        $xml .= $this->tab.'<preference name="android-minSdkVersion" value="'.JSM_ANDROID_MINSDK.'"/>'.$this->enter;
        //$xml .= $this->tab.'<preference name="android-targetSdkVersion" value="28"/>'.$this->enter;

        $xml .= $this->tab.'<preference name="BackupWebStorage" value="none"/>'.$this->enter;
        $xml .= $this->tab.'<preference name="SplashScreen" value="screen"/>'.$this->enter;
        if($this->config['configxml']['orientation'] != '') {
            $xml .= $this->tab.'<preference name="Orientation" value="'.$this->config['configxml']['orientation'].'"/>'.$this->enter;
        }
        $status_bar_style = "lightcontent";
        $status_bar_bgcolor = "#dddddd";
        if(isset($this->config['configxml']['statusbar-style'])) {
            $status_bar_style = $this->config['configxml']['statusbar-style'];
            $status_bar_bgcolor = $this->config['configxml']['statusbar-bgcolor'];
        }
        
        $xml .= $this->tab.'<preference name="StatusBarOverlaysWebView" value="false" />'.$this->enter;
        $xml .= $this->tab.'<preference name="StatusBarStyle" value="'.$status_bar_style.'" />'.$this->enter;
        if(isset($this->config['configxml']['statusbar-bgcolor'])) {
            if($this->config['configxml']['statusbar-bgcolor'] != '') {
                $xml .= $this->tab.'<preference name="StatusBarBackgroundColor" value="'.$status_bar_bgcolor.'" />'.$this->enter;
            }
        } else {
            $xml .= $this->tab.'<preference name="StatusBarBackgroundColor" value="'.$status_bar_bgcolor.'" />'.$this->enter;
        }
        if(isset($this->config['configxml']['code'])) {
            $xml .= $this->tab.'<!-- cutom config.xml -->'.$this->enter;
            $xml .= $this->config['configxml']['code'].$this->enter;
            $xml .= $this->tab.'<!-- ./cutom config.xml -->'.$this->enter;
        }
        $xml .= $this->tab.'<platform name="android">'.$this->enter;
        $xml .= $this->tab.$this->tab.'<preference name="loadUrlTimeoutValue" value="20000" />'.$this->enter;
        $xml .= $this->tab.$this->tab.'<preference name="ErrorUrl" value="file:///android_asset/www/retry.html" />'.$this->enter;
        $xml .= $this->tab.$this->tab.'<preference name="LoadingDialog" value="Start...,Please wait..." />'.$this->enter;
        $xml .= $this->tab.'</platform>'.$this->enter;
        $xml .= $this->tab.'<platform name="ios">'.$this->enter;
        $xml .= $this->tab.$this->tab.'<preference name="ErrorUrl" value="Application Error" />'.$this->enter;
        $xml .= $this->tab.'</platform>'.$this->enter;
        $xml .= $this->tab.'<feature name="StatusBar">'.$this->enter;
        $xml .= $this->tab.$this->tab.'<param name="ios-package" onload="true" value="CDVStatusBar"/>'.$this->enter;
        $xml .= $this->tab.'</feature>'.$this->enter;
        $xml .= $this->tab.'<!-- default -->'.$this->enter;
        $xml .= $this->tab.'<plugin name="cordova-plugin-device" />'.$this->enter;
        $xml .= $this->tab.'<plugin name="cordova-plugin-console" />'.$this->enter;
        $xml .= $this->tab.'<plugin name="cordova-plugin-whitelist" />'.$this->enter;
        $xml .= $this->tab.'<plugin name="cordova-plugin-splashscreen" />'.$this->enter;
        $xml .= $this->tab.'<plugin name="cordova-plugin-statusbar" />'.$this->enter;
        $xml .= $this->tab.'<plugin name="ionic-plugin-keyboard" />'.$this->enter;
        $xml .= $this->tab.'<!-- ./default -->'.$this->enter;
        if(!isset($this->config['mod'])) {
            $this->config['mod'] = array();
        }
        foreach($this->config['mod'] as $plugin) {
            $xml .= $this->tab.'<plugin name="'.$plugin['name'].'" />'.$this->enter;
        }
        if($this->config['app']['soundtouch'] == true) {
            $xml .= $this->tab.'<platform name="android">'.$this->enter;
            $xml .= $this->tab.$this->tab.'<plugin name="cordova-plugin-velda-devicefeedback" />'.$this->enter;
            $xml .= $this->tab.'</platform>'.$this->enter;
        }
        $xml .= $this->tab.'<platform name="android">'.$this->enter;
        if($phonegap == false) {
            $xml .= $this->tab.$this->tab.'<!-- splash and icon for build using ionic/cordova -->'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon density="ldpi" src="resources/android/icon/drawable-ldpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon density="mdpi" src="resources/android/icon/drawable-mdpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon density="hdpi" src="resources/android/icon/drawable-hdpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon density="xhdpi" src="resources/android/icon/drawable-xhdpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon density="xxhdpi" src="resources/android/icon/drawable-xxhdpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon density="xxxhdpi" src="resources/android/icon/drawable-xxxhdpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="land-ldpi" src="resources/android/splash/drawable-land-ldpi-screen.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="land-mdpi" src="resources/android/splash/drawable-land-mdpi-screen.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="land-hdpi" src="resources/android/splash/drawable-land-hdpi-screen.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="land-xhdpi" src="resources/android/splash/drawable-land-xhdpi-screen.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="land-xxhdpi" src="resources/android/splash/drawable-land-xxhdpi-screen.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="land-xxxhdpi" src="resources/android/splash/drawable-land-xxxhdpi-screen.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="port-ldpi" src="resources/android/splash/drawable-port-ldpi-screen.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="port-mdpi" src="resources/android/splash/drawable-port-mdpi-screen.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="port-hdpi" src="resources/android/splash/drawable-port-hdpi-screen.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="port-xhdpi" src="resources/android/splash/drawable-port-xhdpi-screen.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="port-xxhdpi" src="resources/android/splash/drawable-port-xxhdpi-screen.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="port-xxxhdpi" src="resources/android/splash/drawable-port-xxxhdpi-screen.png" />'.$this->enter;
        }
        if($phonegap == true) {
            $xml .= $this->tab.$this->tab.'<icon qualifier="ldpi" src="www/res/icon/android/ldpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon qualifier="mdpi" src="www/res/icon/android/mdpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon qualifier="hdpi" src="www/res/icon/android/hdpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon qualifier="xhdpi" src="www/res/icon/android/xhdpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon qualifier="xxhdpi" src="www/res/icon/android/xxhdpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon qualifier="xxxhdpi" src="www/res/icon/android/xxxhdpi-icon.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<!-- not support using custom source -->'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="ldpi" src="www/res/screen/android/ldpi.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="mdpi" src="www/res/screen/android/mdpi.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="hdpi" src="www/res/screen/android/hdpi.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="xhdpi" src="www/res/screen/android/xhdpi.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="xxhdpi" src="www/res/screen/android/xxhdpi.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash density="xxxhdpi" src="www/res/screen/android/xxxhdpi.png" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<!-- ./not support using custom source -->'.$this->enter;
        }
        $xml .= $this->tab.'</platform>'.$this->enter;
        $xml .= $this->tab.'<platform name="ios">'.$this->enter;
        if($phonegap == false) {
            $xml .= $this->tab.$this->tab.'<icon height="57" src="resources/ios/icon/icon.png" width="57"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="114" src="resources/ios/icon/icon@2x.png" width="114"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="40" src="resources/ios/icon/icon-40.png" width="40"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="80" src="resources/ios/icon/icon-40@2x.png" width="80"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="120" src="resources/ios/icon/icon-40@3x.png" width="120"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="50" src="resources/ios/icon/icon-50.png" width="50"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="100" src="resources/ios/icon/icon-50@2x.png" width="100"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="60" src="resources/ios/icon/icon-60.png" width="60"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="120" src="resources/ios/icon/icon-60@2x.png" width="120"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="180" src="resources/ios/icon/icon-60@3x.png" width="180"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="72" src="resources/ios/icon/icon-72.png" width="72"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="144" src="resources/ios/icon/icon-72@2x.png" width="144"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="76" src="resources/ios/icon/icon-76.png" width="76"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="152" src="resources/ios/icon/icon-76@2x.png" width="152"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="167" src="resources/ios/icon/icon-83.5@2x.png" width="167"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="29" src="resources/ios/icon/icon-small.png" width="29"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="58" src="resources/ios/icon/icon-small@2x.png" width="58"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<icon height="87" src="resources/ios/icon/icon-small@3x.png" width="87"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="2436" src="resources/ios/splash/Default-2436h.png" width="1125" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="1125" src="resources/ios/splash/Default-Landscape-2436h.png" width="2436" />'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="1136" src="resources/ios/splash/Default-568h@2x~iphone.png" width="640"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="1334" src="resources/ios/splash/Default-667h.png" width="750"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="2208" src="resources/ios/splash/Default-736h.png" width="1242"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="1242" src="resources/ios/splash/Default-Landscape-736h.png" width="2208"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="1536" src="resources/ios/splash/Default-Landscape@2x~ipad.png" width="2048"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="768" src="resources/ios/splash/Default-Landscape~ipad.png" width="1024"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="2048" src="resources/ios/splash/Default-Portrait@2x~ipad.png" width="1536"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="1024" src="resources/ios/splash/Default-Portrait~ipad.png" width="768"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="960" src="resources/ios/splash/Default@2x~iphone.png" width="640"/>'.$this->enter;
            $xml .= $this->tab.$this->tab.'<splash height="480" src="resources/ios/splash/Default~iphone.png" width="320"/>'.$this->enter;
        }
        $xml .= $this->tab.'</platform>'.$this->enter;
        if($phonegap == true) {
            $xml .= $this->tab.'<preference name="phonegap-version" value="'.$this->config['configxml']['phonegap_cli'].'" />'.$this->enter;
        }

        if(isset($this->config['mod']['admob-free']['data'])) {
            $xml .= $this->tab.'<plugin name="cordova-plugin-admob-free">'.$this->enter;
            $xml .= $this->tab.$this->tab.'<variable name="ADMOB_APP_ID" value="'.$this->config['mod']['admob-free']['data']['app_id'].'" />'.$this->enter;
            $xml .= $this->tab.'</plugin>'.$this->enter;
        }

        $xml .= '</widget>';
        return $xml;
    }
    private function getAllFiles()
    {
        $files = array();
        $path[] = $this->appDir.'/www/*';
        while(count($path) != 0) {
            $v = array_shift($path);
            foreach(glob($v) as $item) {
                if(is_dir($item))
                    $path[] = $item.'/*';
                elseif(is_file($item)) {
                    $files[] = './'.end(explode("www/",$item));
                }
            }
        }
        return $files;
    }
    function serviceWorkers()
    {
        $files = $this->getAllFiles();
        $code = null;
        $code .= "'use strict';".$this->enter;
        $code .= "importScripts('./lib/sw-toolbox.js');".$this->enter;
        $code .= "self.toolbox.options.cache = {".$this->enter;
        $code .= $this->tab."name: '".$this->config['app']['prefix']."'".$this->enter;
        $code .= "};".$this->enter;
        $code .= "self.toolbox.precache(".$this->enter;
        $code .= $this->tab."[".$this->enter;
        $code .= $this->tab.$this->tab."'".implode("',\r\n\t\t'",$files)."'".$this->enter;
        $code .= $this->tab."]".$this->enter;
        $code .= ");".$this->enter;
        $code .= "self.toolbox.router.any('/*', self.toolbox.fastest);".$this->enter;
        $code .= "self.toolbox.router.default = self.toolbox.networkFirst;".$this->enter;
        return $code;
    }
    /**
     * Ionic::manifest_json()
     * 
     * @return
     */
    function manifest_json()
    {
        $status_bar_style = "#000000";
        $status_bar_bgcolor = "#ffffff";
        if(isset($this->config['configxml']['statusbar-style'])) {
            $status_bar_style = $this->config['configxml']['statusbar-style'];
            $status_bar_bgcolor = $this->config['configxml']['statusbar-bgcolor'];
        }
        $app_config = $this->config['app'];
        $app_menu = $this->config['menu'];
        if(!isset($app_config['start'])) {
            $app_config['start'] = 'index.html';
        }
        return '
        {
          "name": "'.$app_config['name'].' Lite",
          "short_name": "'.$app_config['name'].'",
          "start_url": "'.$app_config['start'].'",
          "display": "standalone",
          "icons":[{"src": "'.$app_menu['logo'].'"}],
          "prefer_related_applications": true,
          "related_applications": [
            {
              "platform": "play",
              "id": "'.JSM_PACKAGE_NAME.'.'.str_replace('_','',$this->str2var($app_config['company'])).'.'.str_replace('_','',$app_config['prefix']).'"
            }
          ],
         "background_color": "'.$status_bar_bgcolor.'",
         "theme_color": "'.$status_bar_style.'"
        }
                ';
    }
    /**
     * Ionic::ionic_config()
     * 
     * @return
     */
    function ionic_config()
    {
        $app_config = $this->config['app'];
        return '
                {
                    "name": "'.str_replace('_','',$app_config['prefix']).'",
                    "app_id": "'.JSM_PACKAGE_NAME.'.'.str_replace('_','',$this->str2var($app_config['company'])).'.'.str_replace('_','',$app_config['prefix']).'",
                    "type": "ionic1"
                }';
    }
    /**
     * Ionic::gui_cordova()
     * 
     * @return
     */
    function gui_cordova()
    {
        $app_config = $this->config['app'];
        $ini = null;
        $ini .= '[setting]'.$this->enter;
        $ini .= 'appID='.JSM_PACKAGE_NAME.'.'.str_replace('_','',$this->str2var($app_config['company'])).'.'.str_replace('_','',$app_config['prefix']).''.$this->enter;
        $ini .= 'appName='.$this->appName.''.$this->enter;
        $ini .= 'sourceCode='.realpath($this->appDir)."\r\n";
        $ini .= 'storePassword='.$this->enter;
        $ini .= '[plugin]'.$this->enter;
        $ini .= '1=cordova-plugin-device'.$this->enter;
        $ini .= '2=cordova-plugin-console'.$this->enter;
        $ini .= '3=cordova-plugin-whitelist'.$this->enter;
        $ini .= '4=cordova-plugin-splashscreen'.$this->enter;
        $ini .= '5=cordova-plugin-statusbar'.$this->enter;
        $ini .= '6=ionic-plugin-keyboard'.$this->enter;
        $z = 7;
        foreach($this->config['mod'] as $mod) {
            $ini .= $z.'='.$mod['name']."\r\n";
            $z++;
        }
        return $ini;
    }
    /**
     * Ionic::output()
     * 
     * @return void
     */
    function output()
    {
        if(!is_dir($this->appDir.'/resources/')) {
            mkdir($this->appDir.'/resources/',0777,true);
        }
        if(!is_dir($this->appDir.'/resources/android/')) {
            mkdir($this->appDir.'/resources/android/',0777,true);
        }
        if(!is_dir($this->appDir.'/resources/android/icon/')) {
            mkdir($this->appDir.'/resources/android/icon',0777,true);
        }
        if(!is_dir($this->appDir.'/resources/android/splash/')) {
            mkdir($this->appDir.'/resources/android/splash',0777,true);
        }
        if(!is_dir($this->appDir.'/resources/ios/')) {
            mkdir($this->appDir.'/resources/ios/',0777,true);
        }
        if(!is_dir($this->appDir.'/resources/ios/icon/')) {
            mkdir($this->appDir.'/resources/ios/icon',0777,true);
        }
        if(!is_dir($this->appDir.'/resources/ios/splash/')) {
            mkdir($this->appDir.'/resources/ios/splash',0777,true);
        }
        if(!is_dir($this->appDir.'/www/')) {
            mkdir($this->appDir.'/www/',0777,true);
        }
        if(!is_dir($this->appDir.'/www/templates/')) {
            mkdir($this->appDir.'/www/templates/',0777,true);
        }
        if(!is_dir($this->appDir.'/www/js/')) {
            mkdir($this->appDir.'/www/js/',0777,true);
        }
        if(!is_dir($this->appDir.'/www/css/')) {
            mkdir($this->appDir.'/www/css/',0777,true);
        }
        if(!is_dir($this->appDir.'/www/translations/')) {
            mkdir($this->appDir.'/www/translations/',0777,true);
        }
        // delete all
        foreach(glob($this->appDir.'/www/templates/*.*') as $filename) {
            @unlink($filename);
        }
        foreach(glob($this->appDir.'/www/js/*.*') as $filename) {
            @unlink($filename);
        }
        foreach(glob($this->appDir.'/www/css/*.*') as $filename) {
            @unlink($filename);
        }
        foreach(glob($this->appDir.'/www/img/*.*') as $filename) {
            @unlink($filename);
        }
        $file_ionic = dirname(__file__).'/ionic.zip';
        $file_lib_target = $this->appDir.'/www/';
        $zip = new ZipArchive;
        if($zip->open($file_ionic) === true) {
            $zip->extractTo($file_lib_target);
            $zip->close();
        } else {
            die('failed extract archive '.$file_ionic.' to '.$file_lib_target);
        }
        //create page base
        $content = $this->markup();
        if(!is_dir($this->appDir)) {
            mkdir($this->appDir,0777);
        }
        file_put_contents($this->appDir.'/www/index.html',$content);
        $start = '<!doctype html><html><head><script>window.location="./index.html";</script></head></html>';
        file_put_contents($this->appDir.'/www/start.html',$start);
        $config_xml = $this->buildXML(false);
        file_put_contents($this->appDir.'/config.xml',$config_xml);
        file_put_contents($this->appDir.'/config-ionic.xml',$config_xml);
        $config_xml = $this->buildXML(true);
        file_put_contents($this->appDir.'/config-phonegap.xml',$config_xml);
        $readme_md = $this->readMe();
        file_put_contents($this->appDir.'/README.md',$readme_md);
        file_put_contents($this->appDir.'/LICENSE',' ');
        $config_gui_cordova = $this->gui_cordova();
        file_put_contents($this->appDir.'/gui_cordova.ini',$config_gui_cordova);
        $config_ionic = $this->ionic_config();
        file_put_contents($this->appDir.'/ionic.config.json',$config_ionic);
        $manifest = $this->manifest_json();
        file_put_contents($this->appDir.'/www/manifest.json',$manifest);
        file_put_contents($this->appDir.'/ionic.project',$config_ionic);

        if(file_exists($this->appDir.'/www/templates/'.$this->pagePrefix.'-tabs.html')) {
            @unlink($this->appDir.'/www/templates/'.$this->pagePrefix.'-tabs.html');
        }
        if(file_exists($this->appDir.'/www/templates/'.$this->pagePrefix.'-side_menus.html')) {
            @unlink($this->appDir.'/www/templates/'.$this->pagePrefix.'-side_menus.html');
        }
        //create tab
        if(count($this->pageTabs) != 0) {
            $content = $this->tabs();
            if(!is_dir($this->appDir.'/www/templates/')) {
                mkdir($this->appDir.'/www/templates/',0777);
            }
            file_put_contents($this->appDir.'/www/templates/'.$this->pagePrefix.'-tabs.html',$content);
        }

        if(count($this->pageSideMenus) != 0) {
            $content = $this->side_menus();
            if(!is_dir($this->appDir.'/www/templates/')) {
                mkdir($this->appDir.'/www/templates/',0777);
            }
            file_put_contents($this->appDir.'/www/templates/'.$this->pagePrefix.'-side_menus.html',$content);
        }


        if(count($this->subPages) != 0) {
            foreach($this->subPages as $sub_pages) {
                $content = $this->pages($sub_pages);
                file_put_contents($this->appDir.'/www/templates/'.$this->pagePrefix.'-'.$sub_pages['prefix'].'.html',$content);
            }
        }
        $content = $this->app_js;
        if(!is_dir($this->appDir.'/www/js/')) {
            mkdir($this->appDir.'/www/js/',0777);
        }
        file_put_contents($this->appDir.'/www/js/app.js',$content);
        $content = $this->controllers_js;
        file_put_contents($this->appDir.'/www/js/controllers.js',$content);
        $content = $this->services_js;
        file_put_contents($this->appDir.'/www/js/services.js',$content);
        file_put_contents($this->appDir.'/www/css/style.css',$this->css());
        if(!is_dir($this->appDir.'/www/data/tables/')) {
            mkdir($this->appDir.'/www/data/tables/',0777,true);
        }
        if(!is_dir($this->appDir.'/www/data/images/')) {
            mkdir($this->appDir.'/www/data/images/',0777,true);
        }
        if(count($this->sample_data) != 0) {
            foreach($this->sample_data as $sample_data) {
                file_put_contents($this->appDir.'/www/'.$sample_data['path'],$sample_data['data']);
            }
        }
        $lang_prefix = 'en-us';
        if(!isset($this->config['app']['locale'])) {
            $this->config['app']['locale'] = 'en-us';
        }
        if($this->config['app']['locale'] != '') {
            $lang_prefix = $this->config['app']['locale'];
        }
        $content = $this->translations();
        file_put_contents($this->appDir.'/www/translations/'.$lang_prefix.'.json',$content);
        if(!isset($this->config['pwa']['service-workers']['enable'])) {
            $this->config['pwa']['service-workers']['enable'] = false;
        }
        if($this->config['pwa']['service-workers']['enable'] == true) {
            $sw_js = null;
            $sw_js = $this->serviceWorkers();
            file_put_contents($this->appDir.'/www/service-worker.js',$sw_js);
        }
    }
    /**
     * Ionic::str2var()
     * 
     * @param mixed $string
     * @param bool $strtolower
     * @param bool $dot
     * @return
     */
    private function str2var($string,$strtolower = true,$dot = false)
    {
        $char = 'abcdefghijklmnopqrstuvwxyz_12345678900';
        if($dot == true) {
            $char .= '.';
        }
        $Allow = null;
        if($strtolower == true) {
            $string = strtolower($string);
        } else {
            $char .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        $string = str_replace(array(
            ' ',
            '-',
            '__'),'_',($string));
        $string = str_replace(array('___','__'),'_',($string));
        for($i = 0; $i < strlen($string); $i++) {
            if(strstr($char,$string[$i]) != false) {
                $Allow .= $string[$i];
            }
        }
        return $Allow;
    }
    /**
     * Ionic::url_param()
     * 
     * @param mixed $url
     * @param mixed $append
     * @return
     */
    function url_param($url,$append)
    {
        $query = parse_url($url,PHP_URL_QUERY);
        if(strlen($query) == 0) {
            $new_url = $url.'?'.$append;
        } else {
            $new_url = $url.'&'.$append;
        }
        return $new_url;
    }
    /**
     * Ionic::translations()
     * 
     * @return
     */
    function translations()
    {
        if(!isset($this->config['menu']['items'])) {
            $this->config['menu']['items'] = array();
        }
        if(!isset($this->config['tables'])) {
            $this->config['tables'] = array();
        }
        if(!is_array($this->config['menu']['items'])) {
            $this->config['menu']['items'] = array();
        }
        $for_json = array();
        if(!is_array($this->config['tables'])) {
            $this->config['tables'] = array();
        }
        foreach($this->config['menu']['items'] as $menu) {
            $var = $menu['label'];
            if($var != '') {
                $for_json[$var] = $var;
            }
        }
        if(!isset($this->config['popover']['menu'])) {
            $this->config['popover']['menu'] = array();
        }
        if(!is_array($this->config['popover']['menu'])) {
            $this->config['popover']['menu'] = array();
        }
        foreach($this->config['popover']['menu'] as $menu) {
            $var = $menu['title'];
            if($var != '') {
                $for_json[$var] = $var;
            }
        }
        foreach($this->config['tables'] as $table) {
            if(isset($table['languages']['retrieval_error_title'])) {
                $var_error = $table['languages']['retrieval_error_title'];
                $for_json[$var_error] = $var_error;
            }
            if(isset($table['languages']['no_result_found'])) {
                $var_error = $table['languages']['no_result_found'];
                $for_json[$var_error] = $var_error;
            }
            if(isset($table['languages']['pull_for_refresh'])) {
                $var_error = $table['languages']['pull_for_refresh'];
                $for_json[$var_error] = $var_error;
            }
            if(isset($table['languages']['search'])) {
                $var_error = $table['languages']['search'];
                $for_json[$var_error] = $var_error;
            }
        }
        $other_vars[] = 'List';
        $other_vars[] = 'Detail';
        $other_vars[] = 'Bookmark';
        $other_vars[] = 'Shopping Cart';
        $other_vars[] = 'Add To Cart';
        $other_vars[] = 'Add To Bookmark';
        $other_vars[] = 'There are no items';
        $other_vars[] = 'Clear';
        $other_vars[] = 'Go To Checkout';
        $other_vars[] = 'Select a language?';
        foreach($other_vars as $other_var) {
            $for_json[$other_var] = $other_var;
        }
        if(defined("JSON_UNESCAPED_UNICODE")) {
            return json_encode($for_json,JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode($for_json);
        }
    }
}
/** JSM_ACTIVATION_CODE **/ 

?>