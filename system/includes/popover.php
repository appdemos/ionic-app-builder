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
$form_input = $html = $footer = null;

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


$subpage_path = str2var($_SESSION["PROJECT"]['menu']['title']);

$out_path = 'output/'.$file_name;
$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-puzzle-piece fa-stack-1x"></i></span>(IMAB) Popover Menu</h4>';
$content .= notice();
$raw_popover['popover']['icon'] = 'ion-android-more-vertical';
$raw_popover['popover']['title'] = '';

$direction = null;
if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
{
    $direction = 'dir="rtl"';
}

if(isset($_POST['popover-save']))
{
    if(!is_dir('projects/'.$file_name))
    {
        mkdir('projects/'.$file_name,0777,true);
    }
    $data['popover']['icon'] = $_POST['popover']['icon'];
    $data['popover']['title'] = $_POST['popover']['title'];
    $data['popover']['custom-code'] = $_POST['popover']['custom-code'];
    foreach($_POST['popover']['menu'] as $menu)
    {
        if($menu['title'] != '')
        {
            $popover_menu[] = $menu;
        }
    }
    $data['popover']['menu'] = array_values($popover_menu);

    file_put_contents('projects/'.$file_name.'/popover.json',json_encode($data));
    buildIonic($file_name);
    header('Location: ./?page=popover&notice=save&err=null');

}

if(file_exists('projects/'.$file_name.'/popover.json'))
{
    $raw_popover = json_decode(file_get_contents('projects/'.$file_name.'/popover.json'),true);
}
if(!isset($raw_popover['popover']['title']))
{
    $raw_popover['popover']['title'] = null;
}
if(!isset($_GET['max-menu']))
{
    if(isset($raw_popover['popover']['menu']))
    {
        $_GET['max-menu'] = count($raw_popover['popover']['menu']);
    } else
    {
        $_GET['max-menu'] = 1;
    }

}
$max_menus = (int)$_GET['max-menu'];

for($i = 1; $i <= 100; $i++)
{
    if($max_menus == $i)
    {
        $max_menu[] = array(
            'value' => $i,
            'label' => '- '.$i.' menu',
            'active' => true);
    } else
    {
        $max_menu[] = array(
            'value' => $i,
            'label' => '- '.$i.' menu',
            );
    }
}
$popover_content = null;
$popover_content .= '<blockquote class="blockquote blockquote-info">'.__('You can using menu <a href="./?page=h-recovery-and-issue" target="_blank">(IMAB) Recovery and Issue</a> to check broken link on popover menu').'</blockquote>';
$popover_content .= '<div class="panel panel-default">';
$popover_content .= '<div class="panel-heading">';
$popover_content .= '<h5 class="panel-title">'.__('General').'</h5>';
$popover_content .= '</div>';
$popover_content .= '<div class="panel-body">';
$popover_content .= '<div class="row">';
$popover_content .= '<div class="col-md-4">';
$popover_content .= $bs->FormGroup('popover[max-menu]','default','select',__('Need Menu').'',$max_menu,' ','');
$popover_content .= '</div>';
$popover_content .= '<div class="col-md-4">';
$popover_content .= $bs->FormGroup('popover[title]','default','text',__('Menu Title').'','Help','',' '.$direction,'8',$raw_popover['popover']['title']);
$popover_content .= '</div>';
$popover_content .= '<div class="col-md-4">';
$popover_content .= $bs->FormGroup('popover[icon]','default','text',__('Menu Icon').' <span style="color:red">*</span>','ion-android-more-vertical','','data-type="icon-picker"','8',$raw_popover['popover']['icon']);
$popover_content .= '</div>';
$popover_content .= '</div>';
$popover_content .= '</div>';
$popover_content .= '</div>';


$popover_content .= '<div class="panel panel-default">';
$popover_content .= '<div class="panel-heading">';
$popover_content .= '<h5 class="panel-title">'.__('Menu Items').'</h5>';
$popover_content .= '</div>';
$popover_content .= '<div class="panel-body">';

$popover_content .= '<blockquote class="blockquote blockquote-danger">
<h4>'.__('The rules that apply are:').'</h4>
<ul>
<li>'.__('To enable the new <code>Item Type</code> eg: <ins>barcode scanner</ins>, You can activate cordova plugin using menu: <code>Extra Menus -&raquo; (IMAB) Cordova Plugin Others</code>').'</li>
</ul>
</blockquote>';

$popover_content .= '<div class="table-responsive">';
$popover_content .= '<table class="table table-striped sortable">';
$popover_content .= '<thead>';
$popover_content .= '<tr>';
$popover_content .= '<th></th>';
$popover_content .= '<th style="width:25%">'.__('Label').' <span style="color:red">*</span></th>';
$popover_content .= '<th style="width:50%">'.__('Link, Email, Phone or Loc').' <span style="color:red">*</span></th>';
$popover_content .= '<th style="width:25%">'.__('Type').'</th>';
$popover_content .= '<th></th>';
$popover_content .= '</tr>';
$popover_content .= '</thead>';
$popover_content .= '<tbody>';

$menu_type[] = array('label' => __('Divider / Title'),'value' => 'divider');
$menu_type[] = array('label' => __('in-Link (Internal)'),'value' => 'link');
$menu_type[] = array('label' => __('Open - Language Option'),'value' => 'show-language-dialog');
$menu_type[] = array('label' => __('Open - Font Size Option'),'value' => 'show-fontsize-dialog');
$menu_type[] = array('label' => __('Open - Notification Option'),'value' => 'show-notification-dialog');
$menu_type[] = array('label' => __('Open - External Browser (Android/iOs Browser)'),'value' => 'link-external');
$menu_type[] = array('label' => __('Open - Webview'),'value' => 'link-webview');
$menu_type[] = array('label' => __('Open - App Browser (+Toolbar)'),'value' => 'link-appbrowser');
$menu_type[] = array('label' => __('Open - App Email'),'value' => 'link-ext-email');
$menu_type[] = array('label' => __('Open - App SMS'),'value' => 'link-ext-sms');
$menu_type[] = array('label' => __('Open - App Call'),'value' => 'link-ext-call');
$menu_type[] = array('label' => __('Open - App PlayStore'),'value' => 'link-ext-playstore');
$menu_type[] = array('label' => __('Open - App GEO'),'value' => 'link-ext-geo');
$menu_type[] = array('label' => __('App - Exit (Minimize)'),'value' => 'app-exit');
$menu_type[] = array('label' => __('App - Clear Cache (LocalStorage)'),'value' => 'app-clear-cache');

if(!isset($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable']))
{
    $_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] = false;
}
if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
{
    $menu_type[] = array('label' => __('Barcode Scanner - Show Alert'),'value' => 'cordova-barcodescanner-alert');
    $menu_type[] = array('label' => __('Barcode Scanner - Open Internal URL'),'value' => 'cordova-barcodescanner-link-internal');
    $menu_type[] = array('label' => __('Barcode Scanner - Open External URL'),'value' => 'cordova-barcodescanner-link-external');
    $menu_type[] = array('label' => __('Barcode Scanner - Open AppBrowser'),'value' => 'cordova-barcodescanner-appbrowser');

}

for($i = 0; $i < $max_menus; $i++)
{
    if(!isset($raw_popover['popover']['menu'][$i]['title']))
    {
        $raw_popover['popover']['menu'][$i]['title'] = '';
    }

    if(!isset($raw_popover['popover']['menu'][$i]['link']))
    {
        $raw_popover['popover']['menu'][$i]['link'] = '';
    }

    if(!isset($raw_popover['popover']['menu'][$i]['type']))
    {
        $raw_popover['popover']['menu'][$i]['type'] = 'link';
    }

    $z = 0;
    foreach($menu_type as $_item_type)
    {
        $_menu_type[$z] = $_item_type;
        if($raw_popover['popover']['menu'][$i]['type'] == $_item_type['value'])
        {
            $_menu_type[$z]['active'] = true;
        }
        $z++;
    }

    $popover_content .= '<tr id="data-'.$i.'">';

    $popover_content .= '<td class="v-align">';
    $popover_content .= '<span class="glyphicon glyphicon-move"></span>';
    $popover_content .= '</td>';

    $popover_content .= '<td>';
    $popover_content .= $bs->FormGroup('popover[menu]['.$i.'][title]','default','text','','Menu '.$i,__('Nice text'),' '.$direction,'12',$raw_popover['popover']['menu'][$i]['title']);
    $popover_content .= '</td>';

    $popover_content .= '<td>';
    $popover_content .= $bs->FormGroup('popover[menu]['.$i.'][link]','default','text','','#/'.$subpage_path.'/your_pages',__('Type <strong>#</strong> for internal link'),'','12',$raw_popover['popover']['menu'][$i]['link'],'typeahead');
    $popover_content .= '</td>';

    $popover_content .= '<td>';
    $popover_content .= $bs->FormGroup('popover[menu]['.$i.'][type]','default','select','',$_menu_type,'','','');
    $popover_content .= '</td>';


    $popover_content .= '<td>';
    $popover_content .= '<a class="remove-item btn btn-danger btn-sm" href="#!_" data-target="#data-'.$i.'" ><i class="glyphicon glyphicon-trash"></i></a>';
    $popover_content .= '</td>';

    $popover_content .= '</tr>';
}
$popover_content .= '</tbody>';
$popover_content .= '</table>';
$popover_content .= '</div>';
$popover_content .= '</div>';
$popover_content .= '</div>';
//if ($_SESSION['PROJECT']['menu']['type'] == 'side_menus')
//{
if(!isset($raw_popover['popover']['custom-code']))
{
    $raw_popover['popover']['custom-code'] = null;
}

$popover_content .= '<div class="panel panel-default">';
$popover_content .= '<div class="panel-heading">';
$popover_content .= '<h5 class="panel-title">'.__('Custom Popover').'</h5>';
$popover_content .= '</div>';
$popover_content .= '<div class="panel-body">';
$popover_content .= '<div class="div">';
$popover_content .= $bs->FormGroup('popover[custom-code]','default','textarea','','Custom Code','','','12',$raw_popover['popover']['custom-code']);
$popover_content .= '</div>';
$popover_content .= __('Sample code used:');
$popover_content .= '<pre>';
$popover_content .= htmlentities('<button class="button button-icon button-clear ion-android-share-alt" ng-controller="shareCtrl" ng-click="shareApp()"></button>');
$popover_content .= '</pre>';
$popover_content .= __('and write a new controller on  <a target="_blank" href="./?page=?page=x-custom-js">(IMAB) Custom JS</a>');
$popover_content .= '<pre>';
$popover_content .= htmlentities('.controller("shareCtrl", function($scope){ $scope.shareApp = function(){...}})');
$popover_content .= '</pre>';

$popover_content .= '</div>';
$popover_content .= '</div>';

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
 var editorHTML = CodeMirror.fromTextArea(document.getElementById("popover_custom-code_"), {
    lineNumbers: true,
    theme: "'.JSM_THEME_CODEMIRROR.'",
    mode: "text/html",
    extraKeys: {"Ctrl-Space": "autocomplete"},
    value: document.documentElement.innerHTML
  });
</script>
';

//}

$popover_content .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,array(array(
        'name' => 'popover-save',
        'label' => __('Save Popover').' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'),array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));

$icon = new jsmIonicon();
$modal_dialog = $icon->display();

$content .= $bs->Forms('app-setup','','post','default',$popover_content);
$content .= $bs->Modal('icon-dialog','Ionicon Tables',$modal_dialog,'md',null,'Close',null);

if(!isset($_SESSION['PROJECT']['page']))
{
    $_SESSION['PROJECT']['page'] = array();
}
if(!is_array($_SESSION['PROJECT']['page']))
{
    $_SESSION['PROJECT']['page'] = array();
}

$_page[] = googleplay_link();
$_page[] = mailto_link();

foreach($_SESSION['PROJECT']['page'] as $page)
{
    $param_query = null;
    if(isset($page['query']))
    {
        $param_query = '/1';
    }
    $_page[] = '#/'.$subpage_path.'/'.$page['prefix'].$param_query;
}


$content .= '<script type="text/javascript">';
$content .= 'var typehead_vars = '.json_encode($_page).';';
$content .= '</script>';
$template->demo_url = $out_path.'/www/#/';
$template->title = $template->base_title.' | '.'Popover';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;

?>