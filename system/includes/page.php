<?php

if(!defined('JSM_EXEC')) {
    die(':)');
}

$file_name = 'test';
$footer = $stateParam = null;

$bs = new jsmBootstrap();
$menu_page = '#/';
$form_input = $html = null;
if(isset($_SESSION['FILE_NAME'])) {
    $file_name = $_SESSION['FILE_NAME'];
} else {
    header('Location: ./?page=dashboard&err=project');
    die();
}
if(!isset($_SESSION["PROJECT"]['menu'])) {
    header('Location: ./?page=menu&err=new');
    die();
}

$direction = null;
if($_SESSION["PROJECT"]['app']['direction'] == 'rtl') {
    $direction = 'dir="rtl"';
}

$out_path = 'output/'.$file_name;
$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-language fa-stack-1x"></i></span>(IMAB) Pages</h4>';

if(file_exists('projects/'.$file_name.'/page..json')) {
    unlink('projects/'.$file_name.'/page..json');
}


if(!isset($_GET['prefix'])) {
    $_GET['prefix'] = '';
}

if(!isset($_GET['editor'])) {
    $_GET['editor'] = 'code-editor';
}

if($_GET['editor'] == '') {
    $_GET['editor'] = 'code-editor';
}

// TODO: -- | UNDO PAGE
if(isset($_GET['undo'])) {
    $undo_prefix = str2var($_GET['undo']);
    $target_prefix = str2var($_GET['prefix']);

    $page_target = 'projects/'.$file_name.'/page.'.$target_prefix.'.json';
    $page_undo = 'projects/'.$file_name.'/page.'.$target_prefix.'.json.'.$undo_prefix.'.save';
    @unlink($page_target);
    @copy($page_undo,$page_target);
    buildIonic($file_name);
    header('Location: ./?page=page&err=null&notice=recovery&prefix='.$target_prefix);
    die();
}


// TODO: -- | LOCK PAGE
if(isset($_GET['lock'])) {
    $lock_prefix = str2var($_GET['lock']);
    $lock_path = 'projects/'.$file_name.'/page.'.$lock_prefix.'.json';
    if(file_exists($lock_path)) {
        $lock_data = json_decode(file_get_contents($lock_path),true);
        $lock_data['page'][0]['lock'] = true;
        file_put_contents($lock_path,json_encode($lock_data));
        if($_GET['prefix'] == '') {
            header('Location: ./?page=page');
        } else {
            header('Location: ./?page=page&prefix='.$lock_prefix);
        }

    }
}
// TODO: -- | UNLOCK PAGE
if(isset($_GET['unlock'])) {
    $lock_prefix = str2var($_GET['unlock']);
    $lock_path = 'projects/'.$file_name.'/page.'.$lock_prefix.'.json';
    if(file_exists($lock_path)) {
        $lock_data = json_decode(file_get_contents($lock_path),true);
        $lock_data['page'][0]['lock'] = false;
        file_put_contents($lock_path,json_encode($lock_data));
        if($_GET['prefix'] == '') {
            header('Location: ./?page=page');
        } else {
            header('Location: ./?page=page&prefix='.$lock_prefix);
        }
    }
}


$page_prefix = str2var($_GET['prefix']);
$page_path = 'projects/'.$file_name.'/page.'.$page_prefix.'.json';

// TODO: -- | DELETE PAGE
if(isset($_GET['delete'])) {
    $delete_prefix = str2var($_GET['delete']);

    if($delete_prefix == 'all') {
        foreach(glob("projects/".$file_name."/page.*.json") as $page_json) {
            if(file_exists($page_json)) {
                @copy($page_json,$page_json.'.'.time().'.save');
            }
            @unlink($page_json);
        }
    } else {
        $page_path_for_delete = "projects/".$file_name."/page.".$delete_prefix.".json";

        if(file_exists($page_path_for_delete)) {
            @copy($page_path_for_delete,$page_path_for_delete.'.'.time().'.save');
        }
        @unlink($page_path_for_delete);
    }
    buildIonic($file_name);
    header('Location: ./?page=page&err=null&notice=delete');
    die();
}


// TODO: --| CHANGE INDEX
if(isset($_GET['index'])) {
    $index_prefix = str2var($_GET['index']);
    $_SESSION['PROJECT']['app']['index'] = $index_prefix;
    $new_app["app"] = $_SESSION['PROJECT']['app'];
    file_put_contents("projects/".$file_name."/app.json",json_encode($new_app));
    buildIonic($file_name);
    header('Location: ./?page=page&err=null&notice=change');
    die();
}


$z = 1;
$_page_select[0]['label'] = __("Select Page");
$_page_select[0]['value'] = "";
foreach(glob("projects/".$file_name."/page.*.json") as $filename) {

    $_list_pages = json_decode(file_get_contents($filename),true);
    if(isset($_list_pages['page'][0])) {
        $list_pages = $_list_pages['page'][0];

        $_locked_status = ' page ';
        if(!isset($list_pages['lock'])) {
            $list_pages['lock'] = false;
        }
        if($list_pages['lock'] == true) {
            $_locked_status = '--- ['.__('locked').'] ';
        }

        $_page_select[$z] = array('label' => '-'.$_locked_status.' `'.($list_pages['prefix']).'`','value' => $list_pages['prefix']);

        if($_GET['prefix'] == $list_pages['prefix']) {
            $_page_select[$z]['active'] = true;
        }
        $z++;
    }
}


$_page_editor[] = array('label' => __('Select Editor'),'value' => '#');
$_page_editor[] = array('label' => '- '.__('WYSIWYG / Visual Editor'),'value' => 'wysiwyg-editor');
$_page_editor[] = array('label' => '- '.__('Code Editor (recommended)'),'value' => 'code-editor');
$_page_editor[] = array('label' => '- '.__('Smart Code Builder (Beta)'),'value' => 'smart-code');

$z = 0;
foreach($_page_editor as $_xpage_editor) {
    $xpage_editor[$z] = $_xpage_editor;
    if($_xpage_editor['value'] == $_GET['editor']) {
        $xpage_editor[$z]['active'] = true;
    }
    $z++;
}

// TODO: -- | MODAL PAGE INFO
$modal_content = null;
$modal_content .= '<div class="div-responsive">';
$modal_content .= '<p>'.__('You can use the following code on the page').'</p>';

$modal_content .= '<div class="table-responsive">';
$modal_content .= '<table class="table table-bordered">';
$modal_content .= '<thead>';
$modal_content .= '<tr>';
$modal_content .= '<th>'.__('Name').'</th>';
$modal_content .= '<th>'.__('Example Code').'</th>';
$modal_content .= '</tr>';
$modal_content .= '</thead>';
$modal_content .= '<tbody>';

if(file_exists($page_path)) {
    $page_data = json_decode(file_get_contents($page_path),true);
    if(isset($page_data['page'][0]['variables'])) {
        if(is_array($page_data['page'][0]['variables'])) {
            foreach($page_data['page'][0]['variables'] as $variable) {
                $modal_content .= '<tr><td><span class="label label-success">Current Controller</span><br/>Column : <strong>'.$variable['label'].'</strong></td><td><pre>'.htmlentities($variable['value']).'</pre></td></tr>';
            }

        }
    }
}

foreach($_SESSION['PROJECT']['tables'] as $tb_ctrl) {
    if(!isset($tb_ctrl['prefix'])) {
        $tb_ctrl['prefix'] = '???';
    }
    if(!isset($tb_ctrl['parent'])) {
        $tb_ctrl['parent'] = '???';
    }

    $exp_code_vars = '<div ng-init="var_'.$tb_ctrl['prefix'].'s={}">'."\r\n".'<div ng-controller="'.$tb_ctrl['parent'].'Ctrl">'."\r\n".'<span ng-repeat="item_'.$tb_ctrl['prefix'].' in data_'.$tb_ctrl['prefix'].'s" ng-init="var_'.$tb_ctrl['prefix'].'s[$index]=item_'.$tb_ctrl['prefix'].'"></span>'."\r\n".'</div>'."\r\n".'<pre>{{ var_'.$tb_ctrl['prefix'].'s | json }} </pre>'."\r\n".'</div>';


    $modal_content .= '<tr><td><span class="label label-warning">Other Controller</span><br/>Table: <strong>'.$tb_ctrl['prefix'].'</strong><br/><br/>- Parent Scope</td><td><pre>'.htmlentities($exp_code_vars).'</pre></td></tr>';
}

$modal_content .= '</tbody>';
$modal_content .= '</table>';
$modal_content .= '</div>';
$modal_content .= '</div>';

$select_content = null;
$select_content .= '<div class="panel panel-default">';
$select_content .= '<div class="panel-heading">';
$select_content .= '<h5 class="panel-title">'.__('General').'</h5>';
$select_content .= '</div>';
$select_content .= '<div class="panel-body">';
$select_content .= '<div class="row">';
$select_content .= '<div class="col-md-5">';
$select_content .= $bs->FormGroup('page_prefix','default','select',__('Page'),$_page_select,' ','');
$select_content .= '</div>';

$select_content .= '<div class="col-md-5">';
$select_content .= $bs->FormGroup('page_editor','default','select',__('Editor'),$xpage_editor,' ','');
$select_content .= '</div>';

$select_content .= '<div class="col-md-2">';
$select_content .= $bs->Modal('modal-help',__('Variables and Controller'),$modal_content,'lg',null);
$select_content .= $bs->FormGroup(null,'default','html','','&nbsp;','<br/><a class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal-help">'.__('Page Variables').'</a>');
$select_content .= '</div>';

$select_content .= '</div>';

$select_content .= '</div>';
$select_content .= '</div>';


$page_templates[] = array('label' => __('None'),'value' => 'none');
$page_templates[] = array('label' => __('Cordova WebView'),'value' => 'webview');
$page_templates[] = array('label' => __('Iframe'),'value' => 'iframe');

//$page_templates[] = array('label' => 'Google Map', 'value' => 'gmap');

$subpage_path = str2var($_SESSION["PROJECT"]['menu']['title']);
if($_GET['prefix'] == '') {
    if(isset($_POST['create-page'])) {

        $page_class = '';
        $page_content = '
<div class="card">
  <div class="item item-text-wrap">
    <p>It\'s Work<br/>'.str2var($_POST['page']['title']).' page</p>
  </div>
</div>
';

        switch($_POST['page']['template']) {
            case 'iframe':
                $page_class = 'page-iframe';
                $page_content = '<iframe data-tap-disabled="true" class="fullscreen" src="'.$_POST['page']['option'].'" width="100%" height="100%" ></iframe>';
                break;
            case 'gmap':
                $page_class = 'page-gmap';
                $page_content = '<div id="google_maps"></div>';
                break;
            case 'webview':
                $page_class = 'page-webview';
                $page_content = '
                <div class="card" ng-init="openWebView(\''.$_POST['page']['option'].'\')">
                    <div class="padding">
                    <h2 class="text-center">Welcome</h2>
                    <p class="text-center">'.parse_url($_POST['page']['option'],PHP_URL_HOST).'</p>
                    <button class="button button-block button-calm" ng-click="openWebView(\''.$_POST['page']['option'].'\')">ENTER</button>
                    </div>
                </div>';
                break;
        }

        $new_page = null;

        if(!isset($_POST['page']['var'])) {
            $_POST['page']['var'] = str2var($_POST['page']['title']);
        }

        if(str2var($_POST['page']['var']) == '') {
            $_POST['page']['var'] = str2var($_POST['page']['title']);
        }

        $_POST['page']['var'] = str_replace('0','zero_',$_POST['page']['var']);
        $_POST['page']['var'] = str_replace('1','one_',$_POST['page']['var']);
        $_POST['page']['var'] = str_replace('2','two_',$_POST['page']['var']);
        $_POST['page']['var'] = str_replace('3','three_',$_POST['page']['var']);
        $_POST['page']['var'] = str_replace('4','four_',$_POST['page']['var']);
        $_POST['page']['var'] = str_replace('5','five_',$_POST['page']['var']);
        $_POST['page']['var'] = str_replace('6','six_',$_POST['page']['var']);
        $_POST['page']['var'] = str_replace('7','seven_',$_POST['page']['var']);
        $_POST['page']['var'] = str_replace('8','eight_',$_POST['page']['var']);
        $_POST['page']['var'] = str_replace('9','nine_',$_POST['page']['var']);

        $new_page['page'][0] = array(
            'title' => $_POST['page']['title'],
            'prefix' => str2var($_POST['page']['var']),
            'for' => '-',
            'last_edit_by' => 'page',
            'parent' => '-',
            'menutype' => $_SESSION['PROJECT']['menu']['type'].'-custom',
            'menu' => '-',
            'create-manual' => 'true',
            'version' => 'Upd.'.date('ymdhi'),
            'class' => $page_class,
            'content' => $page_content);

        // TODO: -- | SAVE NEW PAGE

        $_path_page = 'projects/'.$file_name.'/page.'.str2var($_POST['page']['var']).'.json';
        if(file_exists($_path_page)) {
            @copy($_path_page,$_path_page.'.'.time().'.save');
        }
        file_put_contents($_path_page,json_encode($new_page));
        buildIonic($file_name);
        header('Location: ./?page=page&prefix='.str2var($_POST['page']['var']));
    }

    // TODO:  -- | FORM NEW PAGE
    $select_content .= '<div class="panel panel-default" id="new-page">';
    $select_content .= '<div class="panel-heading">';
    $select_content .= '<h5 class="panel-title">'.__('New Page or Overwrite Page').'</h5>';
    $select_content .= '</div>';
    $select_content .= '<div class="panel-body">';
    $select_content .= '<blockquote class="blockquote blockquote-warning"><p>'.__('The use of the same variable will cause the old pages will be overwritten.').'</p></blockquote>';


    $select_content .= '<form method="post" action="">';

    $select_content .= '<div class="row">';
    $select_content .= '<div class="col-md-3">';
    $select_content .= $bs->FormGroup('page[title]','default','text',__('Title'),'','<em>'.__('Nice text').'</em>','required '.$direction,'8');
    $select_content .= '</div>';

    $select_content .= '<div class="col-md-3">';
    $select_content .= $bs->FormGroup('page[var]','default','text',__('Variable'),'','<em><code>'.__('format: a-z and _').'</code></em>','required','8','','typeahead');
    $select_content .= '</div>';

    $select_content .= '<div class="col-md-3">';
    $select_content .= $bs->FormGroup('page[template]','default','select',__('Template'),$page_templates,'','','8');
    $select_content .= '</div>';

    $select_content .= '<div class="col-md-3">';
    $select_content .= $bs->FormGroup('page[option]','default','text',__('Option/URL'),'','','','8');
    $select_content .= '</div>';

    $select_content .= '</div>';
    $select_content .= '<div class="row">';
    $select_content .= '<div class="col-md-4">';
    $select_content .= $bs->FormGroup(null,'default','html','','&nbsp;',$bs->Button('create-page','submit',__('Create new Page'),'success'));
    $select_content .= '</div>';

    $select_content .= '</div>';

    $select_content .= '</form>';


    $select_content .= '</div>';
    $select_content .= '</div>';


}

$content .= notice();
$page_json = array();
if(isset($_SESSION['PROJECT']['page'])) {
    foreach($_SESSION['PROJECT']['page'] as $auto_page) {
        $page_json[] = $auto_page['prefix'];
    }
}
$footer .= "\r\n";
$footer .= '<script type="text/javascript">'."\r\n";
$footer .= 'var typehead_vars = '.json_encode($page_json).";\r\n";
$footer .= '</script>'."\r\n";
$footer .= "\r\n";
// TODO: -- | SAVE EDIT --
if(file_exists($page_path)) {
    $old_page = json_decode(file_get_contents($page_path),true);

    if(isset($_POST['menu-save'])) {
        if(!is_dir('projects/'.$file_name)) {
            mkdir('projects/'.$file_name,0777,true);
        }


        $page_prefix = $_GET['prefix'];
        $page_editor = $_GET['editor'];

        $new_page = $old_page;
        $new_page['page'][0]['title'] = $_POST['page']['title'];
        $new_page['page'][0]['hide-menu'] = $_POST['page']['hide-menu'];
        $new_page['page'][0]['title-logo'] = $_POST['page']['title-logo'];
        $new_page['page'][0]['builder_link'] = '';
        $new_page['page'][0]['content'] = str_replace('output/'.$file_name.'/www/','',$_POST['page']['content']);
        $new_page['page'][0]['img_bg'] = $_POST['page']['img_bg'];
        $new_page['page'][0]['version'] = 'Upd.'.date('ymdhi');
        $new_page['page'][0]['img_hero'] = $_POST['page']['img_hero'];
        $new_page['page'][0]['css'] = $_POST['page']['css'];
        $new_page['page'][0]['after_ionicview'] = $_POST['page']['after_ionicview'];
        $new_page['page'][0]['js'] = $_POST['page']['js'];
        $new_page['page'][0]['jsdef'] = $_POST['page']['jsdef'];


        if(!isset($_POST['page']['code-builder'])) {
            $_POST['page']['code-builder'] = array();
        }
        if(!is_array($_POST['page']['code-builder'])) {
            $_POST['page']['code-builder'] = array();
        }
        $new_page['page'][0]['code-builder'] = $_POST['page']['code-builder'];

        $new_page['page'][0]['priority'] = 'high';
        if(isset($_POST['page']['hide-navbar'])) {
            $new_page['page'][0]['hide-navbar'] = true;
        } else {
            $new_page['page'][0]['hide-navbar'] = false;
        }

        if(isset($_POST['page']['title-tranparant'])) {
            $new_page['page'][0]['title-tranparant'] = true;
        } else {
            $new_page['page'][0]['title-tranparant'] = false;
        }

        if(isset($_POST['page']['cache'])) {
            $new_page['page'][0]['cache'] = 'true';
        } else {
            $new_page['page'][0]['cache'] = 'false';
        }


        if(isset($_POST['page']['remove-has-header'])) {
            $new_page['page'][0]['remove-has-header'] = true;
        } else {
            $new_page['page'][0]['remove-has-header'] = false;
        }

        if(isset($_POST['page']['header-shrink'])) {
            $new_page['page'][0]['header-shrink'] = true;
        } else {
            $new_page['page'][0]['header-shrink'] = false;
        }

        if(isset($_POST['page']['overflow-scroll'])) {
            $new_page['page'][0]['overflow-scroll'] = true;
        } else {
            $new_page['page'][0]['overflow-scroll'] = false;
        }

        if(isset($_POST['page']['scroll-zooming'])) {
            $new_page['page'][0]['overflow-scroll'] = true;
            $new_page['page'][0]['scroll-zooming'] = true;
        } else {
            $new_page['page'][0]['scroll-zooming'] = false;
        }

        if(isset($_POST['page']['scroll'])) {
            $new_page['page'][0]['scroll'] = true;
        } else {
            $new_page['page'][0]['scroll'] = false;
        }


        if(isset($_POST['page']['button_up'])) {
            $new_page['page'][0]['button_up'] = $_POST['page']['button_up'];
        } else {
            $new_page['page'][0]['button_up'] = 'none';
        }


        if(isset($_POST['page']['button_back'])) {
            $new_page['page'][0]['button_back'] = $_POST['page']['button_back'];
        } else {
            $new_page['page'][0]['button_back'] = 'none';
        }


        if(isset($_POST['page']['content-top'])) {
            $new_page['page'][0]['content-top'] = true;
        } else {
            $new_page['page'][0]['content-top'] = false;
        }

        if(isset($_POST['page']['show-banner'])) {
            $new_page['page'][0]['show-banner'] = true;
        } else {
            $new_page['page'][0]['show-banner'] = false;
        }
        if(isset($_POST['page']['hide-banner'])) {
            $new_page['page'][0]['hide-banner'] = true;
        } else {
            $new_page['page'][0]['hide-banner'] = false;
        }
        if(isset($_POST['page']['show-interstitial'])) {
            $new_page['page'][0]['show-interstitial'] = true;
        } else {
            $new_page['page'][0]['show-interstitial'] = false;
        }

        if(isset($_POST['page']['show-rewardvideo'])) {
            $new_page['page'][0]['show-rewardvideo'] = true;
        } else {
            $new_page['page'][0]['show-rewardvideo'] = false;
        }


        if(preg_match('/\-custom/',$new_page['page'][0]['menutype'])) {
            $new_page['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'].'-custom';
        }


        $is_lock = false;
        $lock_path = 'projects/'.$file_name.'/page.'.$page_prefix.'.json';
        if(file_exists($lock_path)) {
            $lock_data = json_decode(file_get_contents($lock_path),true);
            $is_lock = $lock_data['page'][0]['lock'];
        }

        if($is_lock == true) {
            $error_notice[] = 'Page <code>'.$page_prefix.'</code> is <span class="fa fa-lock"></span> locked.';
        } else {
            if(file_exists($lock_path)) {
                @copy($lock_path,$lock_path.'.'.time().'.save');
            }

            file_put_contents('projects/'.$file_name.'/page.'.$page_prefix.'.json',json_encode($new_page));
        }

        $_SESSION['PAGE_ERROR'] = $error_notice;
        buildIonic($file_name);
        header('Location: ./?page=page&prefix='.$page_prefix.'&editor='.$page_editor.'&err=null&notice=save');
        die();
    }

    $form_input .= $select_content;
    $raw_menu['page']['title'] = null;
    $raw_menu['page']['prefix'] = null;
    $raw_menu['page']['content'] = null;
    $raw_menu['page']['menu'] = null;
    $raw_menu['page']['img_bg'] = '';
    $raw_menu['page']['img_hero'] = '';

    // TODO: -- | CURRENT PAGE
    $get_raw_menu = json_decode(file_get_contents($page_path),true);

    $raw_menu = $get_raw_menu['page'][0];
    $raw_menu['content'] = str_replace('data/images/','output/'.$file_name.'/www/data/images/',$raw_menu['content']);
    $menu_page = $raw_menu['menu'];

    if(isset($raw_menu['query_value'])) {
        if($raw_menu['query_value'] != '') {
            $stateParam = '/'.$raw_menu['query_value'];
        }
    }

    // TODO: -- | ---- option - bg_image
    $page_background = null;
    if(!isset($raw_menu['bg_image'])) {
        $raw_menu['bg_image'] = false;
    }
    if($raw_menu['bg_image'] == true) {
        $page_background = 'checked';
    } else {
        $page_background = '';
    }

    // TODO: -- | ---- option - button_up

    $readonly_option = '';
    if($raw_menu['menutype'] == 'tabs-custom') {
        $readonly_option = 'disabled="disabled"';
        $raw_menu['button_up'] = 'none';
        $raw_menu['button_back'] = 'none';
    }


    $page_fab_up = array();
    if(!isset($raw_menu['button_up'])) {
        $raw_menu['button_up'] = 'none';
    }

    $_page_fab_up[] = array('value' => 'none','label' => 'none');
    $_page_fab_up[] = array('value' => 'top-left','label' => 'top - left');
    $_page_fab_up[] = array('value' => 'top-right','label' => 'top - right');
    $_page_fab_up[] = array('value' => 'bottom-right','label' => 'bottom - right');
    $_page_fab_up[] = array('value' => 'bottom-left','label' => 'bottom - left');
    $z = 0;
    foreach($_page_fab_up as $__page_fab_up) {
        $page_fab_up[$z] = $__page_fab_up;
        if($raw_menu['button_up'] == $__page_fab_up['value']) {
            $page_fab_up[$z]['active'] = true;
        }
        $z++;
    }


    // TODO: -- | ---- option - button_up


    $page_back_btn = array();
    if(!isset($raw_menu['button_back'])) {
        $raw_menu['button_back'] = 'none';
    }

    $_page_back_btn[] = array('value' => 'none','label' => 'Auto');
    $_page_back_btn[] = array('value' => 'enable','label' => 'Enable');
    $_page_back_btn[] = array('value' => 'disable','label' => 'Disable');

    $z = 0;
    foreach($_page_back_btn as $__page_back_btn) {
        $page_back_btn[$z] = $__page_back_btn;
        if($raw_menu['button_back'] == $__page_back_btn['value']) {
            $page_back_btn[$z]['active'] = true;
        }
        $z++;
    }


    // TODO: -- | ---- option - hide-navbar
    $page_hide_navbar = '';
    if(!isset($raw_menu['hide-navbar'])) {
        $raw_menu['hide-navbar'] = false;
    }

    if($raw_menu['hide-navbar'] == true) {
        $page_hide_navbar = 'checked';
    } else {
        $page_hide_navbar = '';
    }
    // TODO: -- | ---- option - title-tranparant
    $page_title_tranparant = '';
    if(!isset($raw_menu['title-tranparant'])) {
        $raw_menu['title-tranparant'] = false;
    }
    if($raw_menu['title-tranparant'] == true) {
        $page_title_tranparant = 'checked';
    } else {
        $page_title_tranparant = '';
    }

    // TODO: -- | ---- option - header-shrink
    if(!isset($raw_menu['header-shrink'])) {
        $raw_menu['header-shrink'] = false;
    }
    if($raw_menu['header-shrink'] == true) {
        $page_shrink_header = 'checked';
    } else {
        $page_shrink_header = '';
    }


    // TODO: -- | ---- option - remove-has-header
    if(!isset($raw_menu['remove-has-header'])) {
        $raw_menu['remove-has-header'] = false;
    }
    if($raw_menu['remove-has-header'] == true) {
        $page_remove_has_header = 'checked';
    } else {
        $page_remove_has_header = '';
    }

    // TODO: -- | ---- option - overflow-scroll
    if(!isset($raw_menu['overflow-scroll'])) {
        $raw_menu['overflow-scroll'] = false;
    }
    if($raw_menu['overflow-scroll'] == true) {
        $page_overflow_scroll = 'checked';
    } else {
        $page_overflow_scroll = '';
    }

    // TODO: -- | ---- option - scroll-zooming
    if(!isset($raw_menu['scroll-zooming'])) {
        $raw_menu['scroll-zooming'] = false;
    }
    if($raw_menu['scroll-zooming'] == true) {
        $page_scroll_zooming = 'checked';
    } else {
        $page_scroll_zooming = '';
    }

    if(!isset($raw_menu['db_url_dinamic'])) {
        $raw_menu['db_url_dinamic'] = false;
    }

    // TODO: -- | ---- option - content-top
    if(!isset($raw_menu['content-top'])) {
        $raw_menu['content-top'] = false;
    }
    if($raw_menu['content-top'] == true) {
        $page_content_top = 'checked';
    } else {
        $page_content_top = '';
    }


    if(!isset($raw_menu['cache'])) {
        $raw_menu['cache'] = 'false';
    }
    if($raw_menu['cache'] == 'true') {
        $page_cache = 'checked';
    } else {
        $page_cache = '';
    }


    // TODO: -- | ---- option - scroll
    if(!isset($raw_menu['scroll'])) {
        $raw_menu['scroll'] = false;
    }
    if($raw_menu['scroll'] == true) {
        $page_scroll = 'checked';
    } else {
        $page_scroll = '';
    }

    // TODO: -- | ---- option - img_bg
    if(!isset($raw_menu['img_bg'])) {
        $raw_menu['img_bg'] = '';
    }

    // TODO: -- | ---- option - img_hero
    if(!isset($raw_menu['img_hero'])) {
        $raw_menu['img_hero'] = '';
    }

    // TODO: -- | ---- option - js
    if(!isset($raw_menu['js'])) {
        $raw_menu['js'] = '';
    }

    if(!isset($raw_menu['jsdef'])) {
        $raw_menu['jsdef'] = '';
    }

    if(!isset($raw_menu['after_ionicview'])) {
        $raw_menu['after_ionicview'] = '';
    }
    if(!isset($raw_menu['css'])) {
        $raw_menu['css'] = '';
    }
    $page_prefix = $_GET['prefix'];
    $page_locked = '<a class="btn btn-sm btn-warning" href="./?page=page&lock='.$page_prefix.'&prefix='.$page_prefix.'">'.__('Lock This Page').'</a>';
    $label_locked = '';
    if(isset($raw_menu['lock'])) {
        if($raw_menu['lock'] == true) {
            $page_locked = '<a class="btn-sm btn btn-danger" href="./?page=page&unlock='.$page_prefix.'&prefix='.$page_prefix.'">'.__('Unlock This Page').'</a>';
            $label_locked = __('(Locked)');
        }
    }
    $page_undo = $use_as_homepage = null;
    $page_recovery = glob("projects/".$file_name."/page.".$page_prefix.".*.save");
    array_multisort(array_map('filemtime',$page_recovery),SORT_NUMERIC,SORT_DESC,$page_recovery);
    if(count($page_recovery) >= 1) {
        $page_undo = '&nbsp;';
        $page_undo .= '<div class="btn-group">';
        $page_undo .= '<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">';
        $page_undo .= '<i class="fa fa-undo"></i> '.__('Undo').' <span class="caret"></span>';
        $page_undo .= '</button>';
        $page_undo .= '<ul class="dropdown-menu" role="menu">';

        foreach($page_recovery as $save_page_file) {
            $page_time = end(explode('.json.',str_replace('.save','',$save_page_file)));
            $page_undo .= '<li><a href="?page=page&prefix='.$page_prefix.'&undo='.$page_time.'">'.date('Y-m-d h:i:s',$page_time).'</a></li>';
        }
        $page_undo .= '</ul>';
        $page_undo .= '</div>';
    }


    if($raw_menu['db_url_dinamic'] == 'on') {
        $use_as_homepage = '';
    } else {
        if($_GET['prefix'] != $_SESSION['PROJECT']['app']['index']) {
            $use_as_homepage = ' <a class="btn-sm btn btn-primary" href="./?page=page&index='.$page_prefix.'">'.__('Use as Home App').'</a>';
        }
    }

    $button = array();
    $button[] = array(
        'name' => 'menu-save',
        'label' => __('Save This Page').' &raquo;',
        'tag' => 'submit',
        'color' => 'primary');

    $button[] = array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'warning');


    if($_GET['prefix'] != '') {
        $button[] = array(
            'label' => __('Delete This Page'),
            'icon' => 'glyphicon glyphicon glyphicon-trash',
            'tag' => 'anchor',
            'color' => 'danger delete-this-page ',
            'link' => "./?page=page&delete=".str2var($_GET['prefix']));
    }

    $form_input .= '<div class="panel panel-default">';
    $form_input .= '<div class="panel-heading">';
    $form_input .= '<h5 class="panel-title">'.__('Page Editor').' '.$label_locked.'</h5>';
    $form_input .= '</div>';
    $form_input .= '<div class="panel-body">';
    $form_input .= '<div class="pull-right">'.$page_locked.$page_undo.$use_as_homepage.'</div>';
    if(in_array($_GET['prefix'],array(
        'dashboard',
        'about_us',
        'bookmarks',
        'slide_tab_menu'))) {
        $form_input .= '<blockquote class="blockquote blockquote-danger">This page is automatically created, not recommended to edit it</blockquote>';
    }
    $form_input .= '<br/>';
    if(!isset($raw_menu['title-logo'])) {
        $raw_menu['title-logo'] = '';
    }
    $form_input .= '<div class="row">';
    $form_input .= '<div class="col-md-9">';
    $form_input .= $bs->FormGroup('page[title]','default','text',__('Page Title').' <span style="color:red">*</span>','','','required '.$direction,'8',htmlentities(($raw_menu['title'])));
    $form_input .= '</div>';
    $form_input .= '<div class="col-md-3">';
    $form_input .= $bs->FormGroup('page[title-logo]','default','text',__('Title Logo').'','','','data-type="image-picker"','8',htmlentities(($raw_menu['title-logo'])));
    $form_input .= '</div>';
    $form_input .= '</div>';

    $form_input .= '<br/><br/>';
    $form_input .= '<!-- CODE-BUILDER -->';

    $form_input .= $bs->FormGroup('page[content]','default','textarea',__('Page Content').' <span style="color:red">*</span>','','<blockquote class="blockquote blockquote-info">'.__('HTML Markup, read <a target="_blank" href="./?page=h-code-docs">(IMAB) Code Docs</a> for custom code').'</blockquote>','','8',htmlentities(($raw_menu['content'])));
    $form_input .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,$button));
    $sample_list_link = null;
    if($_GET['editor'] == 'code-editor') {
        foreach($_SESSION['PROJECT']['page'] as $page) {
            $param_query = null;
            if(isset($page['query'])) {
                $param_query = '/1';
            }
            $link_url = '<!--//link to page `'.$page['prefix'].'`-->'."\r\n".'<div class="item">'."\r\n\t".'<a class="button button-assertive" ng-href="#/'.$subpage_path.'/'.$page['prefix'].$param_query.'">'.$page['prefix'].'</a>'."\r\n".'</div>'."\r\n".'<!--//link to page `'.$page['prefix'].'`-->';
            $sample_list_link .= '<li><a href="#!_" onclick="insertHTML(atob(\''.str_replace('=','',base64_encode($link_url)).'\'));">link To page `'.$page['prefix'].'`</a></li> ';
        }

        $form_input .= '
<div class="pull-right">
    <div class="btn-group">
      <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          '.__('Insert Code').'
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            '.$sample_list_link.'
        </ul>
      </div>
    </div>   
</div>

    ';

    }

    $form_input .= '</div>';
    $form_input .= '</div>';

    $form_input .= '<div class="panel panel-default">';
    $form_input .= '<div class="panel-heading">';
    $form_input .= '<h5 class="panel-title">'.__('Style').'</h5>';
    $form_input .= '</div>';
    $form_input .= '<div class="panel-body">';
    $form_input .= $bs->FormGroup('page[img_bg]','default','text',__('Background Image'),'data/images/background/bg0.jpg',__('file ext *.png (640 x 1136px)'),'data-type="image-picker"','8',$raw_menu['img_bg']);
    $form_input .= $bs->FormGroup('page[img_hero]','default','text',__('Hero Image'),'data/images/background/bg0.jpg',__('file ext *.png (828 x 543px)'),'data-type="image-picker"','8',$raw_menu['img_hero']);
    $form_input .= '<br/>';


    $form_input .= '<div class="row">';

    $form_input .= '<div class="col-md-4">';
    $form_input .= '<hr/>';
    $form_input .= $bs->FormGroup('page[button_up]','default','select',__('Button Go to Top'),$page_fab_up,'',$readonly_option,'8');
    $form_input .= $bs->FormGroup('page[button_back]','default','select',__('Button History Back'),$page_back_btn,__('Not support for <code>tabs menu</code>'),$readonly_option,'8');


    // TODO: -- | ---- option - admob
    $page_show_banner = $page_hide_banner = $page_show_interstitial = $page_show_rewardvideo = null;
    if(!isset($raw_menu['show-banner'])) {
        $raw_menu['show-banner'] = false;
    }

    if(!isset($raw_menu['hide-banner'])) {
        $raw_menu['hide-banner'] = false;
    }
    if(!isset($raw_menu['show-interstitial'])) {
        $raw_menu['show-interstitial'] = false;
    }
    if(!isset($raw_menu['show-rewardvideo'])) {
        $raw_menu['show-rewardvideo'] = false;
    }

    $page_show_banner = '';
    if($raw_menu['show-banner'] == true) {
        $page_show_banner = 'checked="checked"';
    }
    $page_hide_banner = '';
    if($raw_menu['hide-banner'] == true) {
        $page_hide_banner = 'checked="checked"';
    }
    $page_show_interstitial = '';
    if($raw_menu['show-interstitial'] == true) {
        $page_show_interstitial = 'checked="checked"';
    }
    $page_show_rewardvideo = '';
    if($raw_menu['show-rewardvideo'] == true) {
        $page_show_rewardvideo = 'checked="checked"';
    }

    if(isset($_SESSION['PROJECT']['mod']['admob'])) {
        $form_input .= '<hr/>';
        $form_input .= '<h5>'.__('AdmobPro Option').'</h5>';
        $form_input .= $bs->FormGroup('page[show-banner]','default','checkbox','',__('Show Banner'),' ',$page_show_banner,'8');
        $form_input .= $bs->FormGroup('page[hide-banner]','default','checkbox','',__('Hide Banner'),' ',$page_hide_banner,'8');
        $form_input .= $bs->FormGroup('page[show-interstitial]','default','checkbox','',__('Show Interstitial'),' ',$page_show_interstitial,'8');
        $form_input .= $bs->FormGroup('page[show-rewardvideo]','default','checkbox','',__('Show Reward Video'),' ',$page_show_rewardvideo,'8');
    }
    if(isset($_SESSION['PROJECT']['mod']['admob-free'])) {
        $form_input .= '<hr/>';
        $form_input .= '<h5>'.__('AdmobFree Option').'</h5>';
        $form_input .= $bs->FormGroup('page[show-banner]','default','checkbox','',__('Show Banner'),' ',$page_show_banner,'8');
        $form_input .= $bs->FormGroup('page[hide-banner]','default','checkbox','',__('Hide Banner'),' ',$page_hide_banner,'8');
        $form_input .= $bs->FormGroup('page[show-interstitial]','default','checkbox','',__('Show Interstitial'),' ',$page_show_interstitial,'8');
        //$form_input .= $bs->FormGroup('page[show-rewardvideo]','default','checkbox','',__('Show Reward Video'),' ',$page_show_rewardvideo,'8');
    }
    $form_input .= '</div>';


    $form_input .= '<div class="col-md-4">';
    $form_input .= '<hr/>';
    $form_input .= $bs->FormGroup('page[hide-navbar]','default','checkbox','',__('Hide Navbar'),' ',$page_hide_navbar,'8');
    $form_input .= $bs->FormGroup('page[header-shrink]','default','checkbox','',__('Header Shrink').'<br/><label style="padding:0px;margin-left:6px;font-style:italic;font-size: 10px;">- '.__('Not suitable using scroll native, cache and overflow').'</label>','',$page_shrink_header,'8');
    $form_input .= $bs->FormGroup('page[title-tranparant]','default','checkbox','',__('Header Tranparant'),' ',$page_title_tranparant,'8');
    $form_input .= $bs->FormGroup('page[remove-has-header]','default','checkbox','',__('Remove Has Header').' <small>(set=0px)</small>',' ',$page_remove_has_header,'8');

    $form_input .= $bs->FormGroup('page[cache]','default','checkbox','',__('Page Cache').'<br/><label style="padding:0px;margin-left:6px;font-style:italic;font-size: 10px;">- '.__('Need for record last position').'</label>','',$page_cache,'8');

    $form_input .= '</div>';

    $form_input .= '<div class="col-md-4">';
    $form_input .= '<hr/>';
    $form_input .= $bs->FormGroup('page[content-top]','default','checkbox','',__('Content Top').' <small>(set=44px)</small><br/><label style="padding:0px;margin-left:6px;font-style:italic;font-size: 10px;">- '.__('Replacing has-header').'</label>',' ',$page_content_top,'8');
    $form_input .= $bs->FormGroup('page[scroll]','default','checkbox','',__('Scroll Native').'',' ',$page_scroll,'8');
    $form_input .= $bs->FormGroup('page[overflow-scroll]','default','checkbox','',__('Overflow Scroll').'<br/><label style="padding:0px;margin-left:6px;font-style:italic;font-size: 10px;">- '.__('Not suitable with ion-refresher/pulldown refresh').'</label>',' ',$page_overflow_scroll,'8');
    $form_input .= $bs->FormGroup('page[scroll-zooming]','default','checkbox','',__('Scroll and Zooming').'',' ',$page_scroll_zooming,'8');
    $form_input .= '</div>';
    $form_input .= '</div>';

    $form_input .= '<hr/>';

    $form_input .= '<label>'.__('Hide the Menu').'</label>';
    $form_input .= '<p class="help-block">'.__('hide the following menu on this page').'</p>';
    $form_input .= '<div class="row">';

    // TODO: -- | ---- option - hide menu
    if(is_array($_SESSION['PROJECT']['menu']['items'])) {
        foreach($_SESSION['PROJECT']['menu']['items'] as $item_for_hide) {
            if(!isset($raw_menu['hide-menu'])) {
                $raw_menu['hide-menu'] = array();
            }
            $checked = '';
            foreach($raw_menu['hide-menu'] as $cur_item_menu) {

                if($cur_item_menu == $item_for_hide['var']) {
                    $checked = 'checked';
                }
            }
            $form_input .= '
            <div class="col-md-3">
                <div><input type="checkbox" name="page[hide-menu][]" '.$checked.' value="'.$item_for_hide['var'].'" /> '.$item_for_hide['label'].'</div>
            </div>
            ';
        }
    }
    $form_input .= '</div>';

    $form_input .= '</div>';
    $form_input .= '</div>';

    $form_input .= '<div class="panel-group" id="custom-page">';
    $form_input .= '<div class="panel panel-default">';
    $form_input .= '<div class="panel-heading">';
    $form_input .= '<a data-toggle="collapse" data-parent="#custom-page" href="#body-js"><h5 class="panel-title"><span>'.__('Advanced Custom').'</span> </h5></a>';

    $form_input .= '</div>';
    $form_input .= '<div id="body-js" class="panel-collapse ">';

    $form_input .= '<div class="panel-body">';

    $form_input .= '<h4>'.__('Append Code To ngController').'</h4>';
    $form_input .= '<span class="label label-danger">note</span> '.__('Wrong code will break your application that for non-programmer user should fill in the blank').'<hr/>';

    $form_input .= $bs->FormGroup('page[js]','default','textarea',__('Name:').' <code>'.$page_prefix.'Ctrl</code>','',__('Writing your codes <strong class="text-danger">without</strong> using ').'<code><s>.controller(\'...\',function(...){...})</s></code>','','8',htmlentities(stripslashes($raw_menu['js'])));

    $sample_list = null;
    foreach(glob("system/includes/page/ngcontroller.*.json") as $filename) {
        $sample_code = json_decode(file_get_contents($filename),true);
        $sample_list .= '<li><a href="#!_" onclick="updateCodeMirror(atob(\''.str_replace('=','',base64_encode($sample_code['code'])).'\'));">'.$sample_code['name'].'</a></li>';
    }

    foreach($_SESSION['PROJECT']['menu']['items'] as $item_menu) {
        $sample_list .= '<li><a href="#!_" onclick="updateCodeMirror(atob(\''.str_replace('=','',base64_encode("\$rootScope.hide_menu_".$item_menu['var']."= 'hide';")).'\'));">$rootScope.hide_menu_'.$item_menu['var'].'</a></li>';
    }
    if($_GET['editor'] == 'code-editor') {
        $form_input .= '
<div class="pull-right">
    <div class="btn-group">
      <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Insert Code
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            '.$sample_list.'
        </ul>
      </div>
    </div>   
</div>
<br/><br/>
    ';
        $form_input .= $bs->FormGroup('page[jsdef]','default','text',__('Additional dependencies'),'$def1, $def2','separator with coma (,) and do not add what\'s available: <code>$ionicConfig, $scope, $rootScope, $state, $location, $ionicScrollDelegate, $ionicListDelegate, $http, $httpParamSerializer, $stateParams, $timeout, $interval, $ionicLoading, $ionicPopup, $ionicPopover, $ionicActionSheet, $ionicSlideBoxDelegate, $ionicHistory, ionicMaterialInk, ionicMaterialMotion, $window, $ionicModal, base64, md5, $document, $sce, $ionicGesture, $translate, tmhDynamicLocale</code>','','8',htmlentities(stripslashes($raw_menu['jsdef'])));

        $form_input .= '<h4>'.__('Append Code to CSS').'</h4>';
        $form_input .= $bs->FormGroup('page[css]','default','textarea',__('Write the css code for this page'),'','','','8',htmlentities(stripslashes($raw_menu['css'])));
        if(JSM_EXPERT_CUSTOM == true) {
            $form_input .= '<h4>'.__('Append Code to IonicView').'</h4>';
            $form_input .= $bs->FormGroup('page[after_ionicview]','default','textarea',__('This html code will break the basic theme, do not use it if you can not fix it'),'','','','8',htmlentities(stripslashes($raw_menu['after_ionicview'])));
        }

    }
    $form_input .= '</div>';
    $form_input .= '</div>';
    $form_input .= '</div>';
    $form_input .= '</div>';


    $form_input .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,$button));

    // TODO: -- | EDITOR
    switch($_GET['editor']) {
            // TODO: -- | ---- text-editor
        case 'wysiwyg-editor':

            foreach(glob("system/templates/*.html") as $filename) {
                $templates[] = array(
                    'title' => pathinfo($filename,PATHINFO_FILENAME),
                    'description' => '',
                    'url' => $filename);
            }
            $content .= $bs->Forms('app-setup','','post','default',$form_input);
            $font_availables = null;

            if(isset($_SESSION['PROJECT']['fonts'])) {
                if(is_array($_SESSION['PROJECT']['fonts'])) {
                    foreach($_SESSION['PROJECT']['fonts'] as $font) {
                        if(isset($font['used'])) {
                            $font_availables .= $font['font-family'].'='.$font['font-family'].';';
                        }
                    }
                }
            }

            $_colors = array(
                'light',
                'stable',
                'positive',
                'calm',
                'balanced',
                'energized',
                'assertive',
                'royal',
                'dark',
                );

            $set_color_btn[] = array('text' => 'None ','value' => '');
            foreach($_colors as $btn_color) {
                $set_color_btn[] = array('text' => 'Button '.ucwords(str_replace('-',' ',$btn_color)),'value' => 'button button-'.$btn_color.' ink');
            }
            $footer .= '
<script src="./templates/default/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector : "#page_content_",
        plugins: "code textcolor image link table  contextmenu template gui_ion_visualblocks gui_ion_icons gui_ion_buttons gui_ion_quicktags gui_'.JSM_FILEBROWSER.'",
        content_css : [
                            "./system/plugin/css-helper.php",
                            "./templates/default/css/ionic.css",
                            "./templates/default/css/ionic.material.css",
                            "./templates/default/css/ionic.fix.css",
                            
                      ],
        toolbar1: "undo redo | fontselect forecolor backcolor  | bold italic underline | alignleft aligncenter alignright alignjustify | image table | numlist bullist | media",              
        toolbar2: "styleselect fontsizeselect | template | gui_ion_list gui_ion_buttons gui_ion_visualblocks gui_ion_icons gui_'.JSM_FILEBROWSER.'",
        gui_ion_buttons:{toolbar_text:true},
        gui_ion_visualblocks:{default_state:true,toolbar_text:true},
        font_formats: "Default=\'roboto\';'.$font_availables.'",
        link_class_list:'.json_encode($set_color_btn).',
        target_list : [{text: "None",value: ""},{text: "New window",alue: "_blank"},{text: "Top window",value: "_top"},{text: "Self window",value: "_self"}],
        
        templates:  '.json_encode($templates).',
        template_replace_values: {
            appName: "'.$_SESSION["PROJECT"]["app"]["name"].'",
            appDescription: "'.$_SESSION["PROJECT"]["app"]["description"].'",
            appVersion: "'.$_SESSION["PROJECT"]["app"]["version"].'",
            appAuthorName: "'.$_SESSION["PROJECT"]["app"]["author_name"].'",
            appAuthorEmail: "'.$_SESSION["PROJECT"]["app"]["author_email"].'",
            appAuthorUrl: "'.$_SESSION["PROJECT"]["app"]["author_url"].'",
         },
        force_br_newlines : false,
        force_p_newlines : false,
        forced_root_block : "",
        
    });
</script>
<style type="text/css">
	.row{display: inherit !important;"}
</style>
';
            break;
            // TODO: -- | ---- code-editor
        case 'code-editor':
            $content .= $bs->Forms('app-setup','','post','default',$form_input);
            $footer .= '
<link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="./templates/default/vendor/codemirror/theme/'.JSM_THEME_CODEMIRROR.'.css">

<link rel="stylesheet" href="./templates/default/vendor/codemirror/addon/hint/show-hint.css">

<script src="./templates/default/vendor/codemirror/lib/codemirror.js"></script>

<script src="./templates/default/vendor/codemirror/mode/xml/xml.js"></script>
<script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
<script src="./templates/default/vendor/codemirror/mode/css/css.js"></script>
<script src="./templates/default/vendor/codemirror/mode/htmlmixed/htmlmixed.js"></script>

<script src="./templates/default/vendor/codemirror/addon/hint/show-hint.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/xml-hint.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/html-hint.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/css-hint.js"></script>



  
<script type="text/javascript">


  
  var editorHTML = CodeMirror.fromTextArea(document.getElementById("page_content_"), {
    lineNumbers: true,
    mode: "text/html",
    theme: "'.JSM_THEME_CODEMIRROR.'",
    extraKeys: {"Ctrl-Space": "autocomplete"},
    value: document.documentElement.innerHTML
  });

 
   function insertHTML(data){
       var code = editorHTML.getDoc();
       var cursor = code.getCursor();  
       var line = code.getLine(cursor.line);  
       var pos = {  
            line: cursor.line,
            ch: line.length - 1  
        }
       code.replaceRange("\n" + data + "\n", pos); 
   }
   


  
  var editorJS = CodeMirror.fromTextArea(document.getElementById("page_js_"), {
    lineNumbers: true,
    theme: "'.JSM_THEME_CODEMIRROR.'",
    mode: "text/javascript",
    extraKeys: {"Ctrl-Space": "autocomplete"},
  });
  
  function updateCodeMirror(data){
       var code = editorJS.getDoc();
       var cursor = code.getCursor();  
       var line = code.getLine(cursor.line);  
       var pos = {  
            line: cursor.line,
            ch: line.length - 1  
        }
       code.replaceRange("\n" + data + "\n", pos); 
   }
   
    
    
  var editorCSS = CodeMirror.fromTextArea(document.getElementById("page_css_"), {
    lineNumbers: true,
    theme: "'.JSM_THEME_CODEMIRROR.'",
    mode: "text/css",
    extraKeys: {"Ctrl-Space": "autocomplete"}
  });
  
  var editorIonicviewHTML = CodeMirror.fromTextArea(document.getElementById("page_after_ionicview_"), {
    lineNumbers: true,
    theme: "'.JSM_THEME_CODEMIRROR.'",
    mode: "text/html",
    extraKeys: {"Ctrl-Space": "autocomplete"}
  });
  
  
</script>
';
            break;
            // TODO: -- | ---- smart-code
        case 'smart-code':
            $z = 0;

            foreach(glob("system/includes/page/smartcode.*.json") as $filename) {
                $ionlist = json_decode(file_get_contents($filename),true);
                $elements[$z]['name'] = $ionlist['name'];
                $elements[$z]['code'] = $ionlist['code'];
                $elements[$z]['source'] = 'ionicframework';
                $elements[$z]['color'] = 'primary';
                $z++;
            }


            $list_source = null;
            if(isset($_SESSION['PROJECT']['tables'])) {
                $seek_pages = $_SESSION['PROJECT']['page'];
                foreach($seek_pages as $seek_page) {
                    $is_use_for_page[] = $seek_page['prefix'];
                }
                $compare_tables = $_SESSION['PROJECT']['tables'];
                $element = $get_page_contain_codes = array();


                foreach($seek_pages as $seek_page) {
                    if(isset($seek_page['table-code']['menu'])) {
                        $elements[$z]['name'] = 'Menu';
                        $elements[$z]['code'] = $seek_page['table-code']['menu'];
                        $elements[$z]['source'] = 'menu '.$seek_page['prefix'];
                        $elements[$z]['color'] = 'success';
                        $z++;
                    }
                }


                foreach($compare_tables as $compare_table) {

                    if(in_array($compare_table['parent'],$is_use_for_page)) {

                        $item_images = $item_heading = 'error';
                        foreach($compare_table['cols'] as $table_col) {

                            if($table_col['type'] == 'images') {
                                $item_images = str2var($table_col['title'],false);
                            }
                            if($table_col['type'] == 'heading-1') {
                                $item_heading = str2var($table_col['title'],false);
                            }
                        }

                        $ng_controller = 'ng-controller="'.$compare_table['parent'].'Ctrl"';
                        $label_controller = 'controller '.$compare_table['parent'];
                        if($page_prefix == $compare_table['parent']) {
                            $ng_controller = '';
                            $label_controller = '';
                        }

                        // table

                        // code listing
                        $get_page_contain_code = null;


                        foreach($seek_pages as $get_page) {
                            if($get_page['prefix'] == $compare_table['parent']) {
                                $get_page_contain = $get_page;
                                if(isset($get_page['table-code'])) {
                                    $get_page_contain_code = $get_page;

                                }
                            }
                        }

                        // TODO: -- | ---- smart-code ----|---- Template - Data Listing
                        if($get_page_contain_code != null) {
                            if(isset($get_page_contain_code['table-code']['listing'])) {
                                $data_listing_code = "\r\n";
                                $data_listing_code .= "\t".'<div '.$ng_controller.'>'."\r\n";
                                $data_listing_code .= $get_page_contain_code['table-code']['listing'];

                                if(isset($get_page_contain_code['table-code']['infinite-scroll'])) {
                                    $data_listing_code .= $get_page_contain_code['table-code']['infinite-scroll'];
                                }
                                if(isset($get_page_contain_code['table-code']['search-result'])) {
                                    $data_listing_code .= $get_page_contain_code['table-code']['search-result'];
                                }
                                $data_listing_code .= "\t".'</div>'."\r\n";
                                $elements[$z]['name'] = 'Data Listing';
                                $elements[$z]['code'] = $data_listing_code;
                                $elements[$z]['source'] = $label_controller;
                                $elements[$z]['color'] = 'primary';
                                $z++;
                            }

                            if(isset($get_page_contain_code['table-code']['search'])) {
                                $data_listing_code = "\r\n";
                                $data_listing_code .= $get_page_contain_code['table-code']['search'];
                                $elements[$z]['name'] = 'Search in Listing';
                                $elements[$z]['code'] = $data_listing_code;
                                $elements[$z]['source'] = $label_controller;
                                $elements[$z]['color'] = 'primary';
                                $z++;
                            }


                        }

                        // TODO: -- | ---- smart-code ----|---- Template - Tags Heroes
                        if($item_heading != 'error') {
                            $get_table_list[$compare_table['prefix']] = $compare_table;
                            if(!isset($get_page_contain['table-code']['url_detail'])) {
                                $get_page_contain['table-code']['url_detail'] = "#";
                            }
                            if(!isset($get_page_contain['table-code']['url_list'])) {
                                $get_page_contain['table-code']['url_list'] = "#";
                            }
                            $tags_templates = "\r\n";
                            $tags_templates .= "\r\n";
                            $tags_templates .= "\t\t".'<!-- code tag hero -->'."\r\n";
                            $tags_templates .= "\t\t".'<a ng-href="'.$get_page_contain['table-code']['url_list'].'" class="tags-heroes-title light-bg dark">'.$get_page_contain['title'].' <i class="pull-right icon ion-chevron-right"></i></a>'."\r\n";
                            $tags_templates .= "\t\t".'<div class="light-bg" '.$ng_controller.'>'."\r\n";
                            $tags_templates .= "\t\t\t".'<div class="tags-heroes-content list">'."\r\n";

                            $tags_templates .= "\t\t\t\t".'<div class="row">'."\r\n";
                            $tags_templates .= "\t\t\t\t\t".'<div ng-repeat="item in data_'.$compare_table['prefix'].'s | limitTo:2:0" class="col" ng-class="$index ? \'col-33\':\'col-67\'" ><a href="'.$get_page_contain['table-code']['url_detail'].'" class="button button-small button-full ink" ng-class="{\'button-assertive\' : $index}">{{item.'.$item_heading.'}}</a></div>'."\r\n";
                            $tags_templates .= "\t\t\t\t".'</div>'."\r\n";

                            $tags_templates .= "\t\t\t\t".'<div class="row">'."\r\n";
                            $tags_templates .= "\t\t\t\t\t".'<div ng-repeat="item in data_'.$compare_table['prefix'].'s | limitTo:2:2" class="col" ng-class="$index ? \'col-66\':\'col-33\'" ><a href="'.$get_page_contain['table-code']['url_detail'].'" class="button button-small button-full ink" ng-class="$index ? \'button-stable\' : \'button-energized\'" >{{item.'.$item_heading.'}}</a></div>'."\r\n";
                            $tags_templates .= "\t\t\t\t".'</div>'."\r\n";

                            $tags_templates .= "\t\t\t\t".'<div class="row">'."\r\n";
                            $tags_templates .= "\t\t\t\t\t".'<div ng-repeat="item in data_'.$compare_table['prefix'].'s | limitTo:2:4" class="col" ng-class="$index ? \'col-33\':\'col-67\'" ><a href="'.$get_page_contain['table-code']['url_detail'].'" class="button button-small button-full ink" ng-class="{\'button-royal\' : $index}">{{item.'.$item_heading.'}}</a></div>'."\r\n";
                            $tags_templates .= "\t\t\t\t".'</div>'."\r\n";

                            $tags_templates .= "\t\t\t".'</div>'."\r\n";
                            $tags_templates .= "\t\t".'</div>'."\r\n";
                            $tags_templates .= "\t\t".'<!-- ./code tag hero -->'."\r\n";
                            $tags_templates .= "\r\n";
                            $elements[$z]['name'] = 'Tags Heroes';
                            $elements[$z]['code'] = $tags_templates;
                            $elements[$z]['source'] = $label_controller;
                            $elements[$z]['color'] = 'success';
                            $z++;
                        }


                        // TODO: -- | ---- smart-code ----|---- Template - Slide Heroes + Title
                        if($item_images != 'error') {
                            $get_table_list[$compare_table['prefix']] = $compare_table;
                            if(!isset($get_page_contain['table-code']['url_list'])) {
                                $get_page_contain['table-code']['url_list'] = "#";
                            }

                            $slider_templates = "\r\n";
                            $slider_templates .= "\r\n";
                            $slider_templates .= "\t\t".'<!-- code slide hero -->'."\r\n";
                            $slider_templates .= "\t\t".'<div class="assertive-900-bg slide-box-hero" '.$ng_controller.'>'."\r\n";
                            $slider_templates .= "\t\t\t".'<ion-slides class="slide-box-hero-content" options="{slidesPerView:1,autoplay:10000,loop:1}" slider="data.slider">'."\r\n";
                            $slider_templates .= "\t\t\t\t".'<ion-slide-page class="slide-box-hero-item" ng-repeat="item in data_'.$compare_table['prefix'].'s | limitTo : 10:0" >'."\r\n";

                            $slider_templates .= "\t\t\t\t".'<div class="slide-box-hero-container" style="background: url(\'{{item.'.$item_images.'}}\') no-repeat center center;">'."\r\n";

                            $slider_templates .= "\t\t\t\t\t".'<div class="padding caption">'."\r\n";
                            $slider_templates .= "\t\t\t\t\t\t".'<h2 ng-bind-html="item.'.$item_heading.' | strHTML"></h2>'."\r\n";
                            $slider_templates .= "\t\t\t\t\t\t".'<a ng-href="'.$get_page_contain['table-code']['url_detail'].'">&gt;&gt; more</a>'."\r\n";
                            $slider_templates .= "\t\t\t\t\t".'</div>'."\r\n";

                            $slider_templates .= "\t\t\t\t".'</div>'."\r\n";

                            $slider_templates .= "\t\t\t\t".'</ion-slide-page>'."\r\n";
                            $slider_templates .= "\t\t\t".'</ion-slides>'."\r\n";
                            $slider_templates .= "\t\t".'</div>'."\r\n";
                            $slider_templates .= "\t\t".'<!-- ./code slide hero -->'."\r\n";
                            $slider_templates .= "\r\n";
                            $elements[$z]['name'] = 'Slide Heroes + Title';
                            $elements[$z]['code'] = $slider_templates;
                            $elements[$z]['source'] = $label_controller;
                            $elements[$z]['color'] = 'success';
                            $z++;

                            // TODO: -- | ---- smart-code ----|---- Template - Slide Heroes
                            $slider_templates = "\r\n";
                            $slider_templates .= "\r\n";
                            $slider_templates .= "\t\t".'<!-- code slide hero -->'."\r\n";
                            $slider_templates .= "\t\t".'<div class="assertive-900-bg slide-box-hero" '.$ng_controller.'>'."\r\n";
                            $slider_templates .= "\t\t\t".'<ion-slides class="slide-box-hero-content" options="{slidesPerView:1,autoplay:10000,loop:1}" slider="data.slider">'."\r\n";
                            $slider_templates .= "\t\t\t\t".'<ion-slide-page class="slide-box-hero-item" ng-repeat="item in data_'.$compare_table['prefix'].'s | limitTo : 10:0" >'."\r\n";
                            $slider_templates .= "\t\t\t\t\t".'<img ng-src="{{item.'.$item_images.'}}" alt="" />'."\r\n";
                            $slider_templates .= "\t\t\t\t".'</ion-slide-page>'."\r\n";
                            $slider_templates .= "\t\t\t".'</ion-slides>'."\r\n";
                            $slider_templates .= "\t\t".'</div>'."\r\n";
                            $slider_templates .= "\t\t".'<!-- ./code slide hero -->'."\r\n";
                            $slider_templates .= "\r\n";
                            $elements[$z]['name'] = 'Slide Heroes';
                            $elements[$z]['code'] = $slider_templates;
                            $elements[$z]['source'] = $label_controller;
                            $elements[$z]['color'] = 'info';
                            $z++;

                            // TODO: -- | ---- smart-code ----|---- Template - Slide Avatar
                            $slider_templates = "\r\n";
                            $slider_templates .= "\r\n";
                            $slider_templates .= "\t\t".'<!-- code slide avatar -->'."\r\n";
                            $slider_templates .= "\t\t".'<a ng-href="'.$get_page_contain['table-code']['url_list'].'" class="slide-box-title calm-900-bg">'.$get_page_contain['title'].' <i class="pull-right icon ion-chevron-right"></i></a>'."\r\n";
                            $slider_templates .= "\t\t".'<div class="calm-900-bg slide-box-avatar" '.$ng_controller.' >'."\r\n";
                            $slider_templates .= "\t\t".'<ion-slides class="slide-box-avatar-content" ng-if="data_'.$compare_table['prefix'].'s" options="{slidesPerView:grid80}" slider="data.slider" show-pager="false">'."\r\n";
                            $slider_templates .= "\t\t\t".'<ion-slide-page class="slide-box-avatar-item" ng-repeat="item in data_'.$compare_table['prefix'].'s | limitTo : 16:0" >'."\r\n";
                            $slider_templates .= "\t\t\t\t".'<a ng-href="'.$get_page_contain['table-code']['url_detail'].'"><img class="avatar" ng-src="{{item.'.$item_images.'}}" alt="" /></a>'."\r\n";
                            $slider_templates .= "\t\t\t".'</ion-slide-page>'."\r\n";
                            $slider_templates .= "\t\t".'</ion-slides>'."\r\n";
                            $slider_templates .= "\t\t".'</div>'."\r\n";
                            $slider_templates .= "\t\t".'<!-- ./code slide avatar -->'."\r\n";
                            $slider_templates .= "\r\n";

                            $elements[$z]['name'] = 'Slide Avatar';
                            $elements[$z]['code'] = $slider_templates;
                            $elements[$z]['source'] = $label_controller;
                            $elements[$z]['color'] = 'warning';
                            // TODO: -- | ---- smart-code ----|---- Template - Slide Thumbnail
                            $z++;
                            $slider_templates = "\r\n";
                            $slider_templates .= "\r\n";
                            $slider_templates .= "\t\t".'<!-- code slide thumbnail -->'."\r\n";
                            $slider_templates .= "\t\t".'<a ng-href="'.$get_page_contain['table-code']['url_list'].'" class="slide-box-title light-bg">'.$get_page_contain['title'].' <i class="pull-right icon ion-chevron-right"></i></a>'."\r\n";
                            $slider_templates .= "\t\t".'<div class="light-bg slide-box-thumbnail" '.$ng_controller.' >'."\r\n";
                            $slider_templates .= "\t\t\t".'<ion-slides class="slide-box-thumbnail-content" ng-if="data_'.$compare_table['prefix'].'s" options="{slidesPerView:grid80}" slider="data.slider" show-pager="false">'."\r\n";
                            $slider_templates .= "\t\t\t\t".'<ion-slide-page class="slide-box-thumbnail-item" ng-repeat="item in data_'.$compare_table['prefix'].'s | limitTo : 16:0" >'."\r\n";

                            $slider_templates .= "\t\t\t\t\t".'<img class="thumbnail" ng-src="{{item.'.$item_images.'}}" alt="" />'."\r\n";
                            $slider_templates .= "\t\t\t\t\t".'<p class="caption"><a ng-href="'.$get_page_contain['table-code']['url_detail'].'" >{{item.'.$item_heading.'}}</a></p>'."\r\n";

                            $slider_templates .= "\t\t\t\t".'</ion-slide-page>'."\r\n";
                            $slider_templates .= "\t\t\t".'</ion-slides>'."\r\n";
                            $slider_templates .= "\t\t".'</div>'."\r\n";
                            $slider_templates .= "\t\t".'<!-- ./code slide thumbnail -->'."\r\n";
                            $slider_templates .= "\r\n";
                            $elements[$z]['name'] = 'Slide Thumbnail';
                            $elements[$z]['code'] = $slider_templates;
                            $elements[$z]['source'] = $label_controller;
                            $elements[$z]['color'] = 'danger';

                            $z++;
                        }


                        // table
                    }

                }

            }

            // TODO: -- | ---- smart-code ----|---- Template - Data Detail

            if(isset($raw_menu['table-code']['detail'])) {
                $elements[$z]['name'] = 'Data Detail';
                $elements[$z]['code'] = $raw_menu['table-code']['detail'];
                $elements[$z]['source'] = 'current';
                $elements[$z]['color'] = 'primary';
            }


            $code_id = 0;
            if(!isset($elements)) {
                $elements = array();
                $list_source .= '<p>No data found in app controller, the controllers will be created when the table has been saved. Please create a table in advance.</p>';
            }

            foreach($elements as $element) {
                $base64_code = str_replace("=","",base64_encode($element['code']));
                $list_source .= '
                    <li class="list-group-item source-'.$element['source'].' list-group-item-'.$element['color'].'" data-type="list" data-code="'.$base64_code.'">
                        <a class="badge btn-remove pull-right"><span class="icon-move glyphicon glyphicon-trash"></span></a> 
                        <h5 class="list-group-item-heading">'.$element['name'].'</h5>
                        <p class="list-group-item-text">- '.$element['source'].'</p>
                        <input type="hidden" class="input-code" value="'.$base64_code.'" />
                        <input type="hidden" class="input-color" value="'.$element['color'].'" />
                        <input type="hidden" class="input-name" value="'.$element['name'].'" />
                        <input type="hidden" class="input-source" value="'.$element['source'].'" />
                    </li>
                    ';


                $code_id++;
            }


            // TODO:  --------|---- current set
            if(!isset($raw_menu['code-builder'])) {
                $raw_menu['code-builder'] = array();
            }
            $list_current_code = null;
            foreach($raw_menu['code-builder'] as $_current_code) {
                $list_current_code .= '
                    <li class="list-group-item  source-'.$_current_code['source'].' list-group-item-'.$_current_code['color'].'" data-type="list" data-code="'.$_current_code['code'].'">
                        <a class="badge btn-remove pull-right"><span class="icon-move glyphicon glyphicon-trash"></span></a> 
                        <h5 class="list-group-item-heading">'.$_current_code['name'].'</h5>
                        <p class="list-group-item-text">- '.$_current_code['source'].'</p>
                        <input type="hidden" class="input-code" value="'.$_current_code['code'].'" />
                        <input type="hidden" class="input-color" value="'.$_current_code['color'].'" />
                        <input type="hidden" class="input-name" value="'.$_current_code['name'].'" />
                        <input type="hidden" class="input-source" value="'.$_current_code['source'].'" />
                    </li>
                    ';
            }

            if(!isset($get_table_list)) {
                $get_table_list = array();
            }
            $page_available = '<div class="form-inline">';
            foreach($get_table_list as $_table_list) {
                $page_available .= '<div class="checkbox"><label><input type="checkbox" data-target=".source-list .source-'.$_table_list['prefix'].'" class="checkbox-list-source" checked="checked"/>&nbsp;'.$_table_list['title'].'&nbsp;</label></div>&nbsp;';
            }
            $page_available .= '</div>';

            // TODO:  --------|---- markup
            $smart_code = '
                                <br/>
                                <br/>
                                <div class="row">
                                <div class="col-md-8">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h5 class="panel-title">'.__('Ion Component').'</h5>
                                        </div>
                                        <div class="panel-body">
                                            '.$page_available.'
                                            <br/>
                                              <ol class="source-list list-group">
                                                '.$list_source.'
                                              </ol>
                                              
                                                <blockquote class="blockquote blockquote-danger">
                                                    <h4>'.__('The rules that apply are:').'</h4>
                                                    <ol>
                                                        <li>'.__('The number of components is displayed based on the source <code>(IMAB) Table</code>.').'</li>
                                                        <li>'.__('Not working for table created by <code>(IMAB) Page Builder</code>').'</li>
                                                        <li>'.__('Components not support for <code>(IMAB) Table</code> using <strong>Template for Data Listing</strong>: <code>Manual</code>, <code>GMAP Marker</code> and <code>Slidebox</code>.').'</li>
                                                        <li>'.__('Slidebox required <code>page</code> as <code>page target</code> and <code>(IMAB) Table</code> contain an images.').'</li>
                                                        <li>'.__('So that applications can run fast do not use too many components').'</li>
                                                        
                                                    </ol>
                                                </blockquote>
           
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h5 class="panel-title">'.__('Layout').'</h5>
                                        </div>
                                        <div class="panel-body">
                                              <ol class="list-target list-group" style="min-height: 300px;">
                                                  '.$list_current_code.'  
                                              </ol>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            ';

            $form_input = str_replace('<!-- CODE-BUILDER -->',$smart_code,$form_input);
            $content .= $bs->Forms('app-setup','','post','default',$form_input);


            $footer .= '<style type="text/css">';
            $footer .= '#group_page_content_ {display:none;}';
            $footer .= 'ol.source-list .btn-remove{display:none;}';
            $footer .= 'ol.source-list li,ol.list-target li{border-left: 0;border-right: 0;list-style:none;border-top: 3px solid;}';
            $footer .= 'ol.source-list li{cursor:move;width:24%;padding:5px;margin:0;display:inline-block;overflow:hidden;height:75px;}';
            $footer .= 'ol.source-list {max-height: 600px;overflow: hidden;overflow-y: scroll;overflow-x: hidden;}';
            $footer .= 'ol.source-list .list-group-item-heading,ol.list-target .list-group-item-heading{/** overflow: hidden;text-overflow: ellipsis;white-space: nowrap;**/ text-transform: uppercase;font-size:12px}';
            $footer .= 'ol.source-list .list-group-item-text,ol.list-target .list-group-item-text{font-size:11px;}';
            $footer .= 'ol.source-list .list-group-item-text{font-size:11px;position: absolute;bottom:12px;}';
            $footer .= '.list-target .badge{background-color: #000;border:1px solid #777}';
            $footer .= 'ol.list-target li{cursor:s-resize;}';

            $footer .= '</style>';

            $footer .= '
<script type="text/javascript">

setInterval(function(){ 
     
    $(".checkbox-list-source").each(function(){
        var target = $(this).attr("data-target");
        $(target).hide();
        if($(this).is(":checked")==true){
            $(target).show();
        }
     }); 
     
    $(".btn-remove").on("click", function() {
    	var target = $(this).parent();
    	$(target).replaceWith(" ");
    }); 
               
    var content = "";
    $("ol.list-target li").each(function(){
        var markup = $(this).attr("data-code");
        if(typeof markup !== "undefined"){
            try{
                content += atob(markup) ;
            } catch(e) {
                console.log("error:",e);
            }            
        }      
    });
    
    var _index = 0;
    $("ol.list-target .input-code").each(function(){
        $(this).prop("name","page[code-builder][" + _index + "][code]");
        _index++;
    });    
    
    _index = 0;
    $("ol.list-target .input-color").each(function(){
        $(this).prop("name","page[code-builder][" + _index + "][color]");
        _index++;
    }); 
    
    _index = 0;
    $("ol.list-target .input-name").each(function(){
        $(this).prop("name","page[code-builder][" + _index + "][name]");
        _index++;
    }); 
    
    _index = 0;
    $("ol.list-target .input-source").each(function(){
        $(this).prop("name","page[code-builder][" + _index + "][source]");
        _index++;
    }); 
    
    
        
    $("#page_content_").html(content);
}, 200);

$("ol.source-list").sortable({
    group: "no-drop",
    drop: false,
    onDragStart: function ($item, container, _super) {   
        var data_type_source = $item.attr("data-type");
        
        //console.log("type_source:",$item);
        if(data_type_source === "list"){
            console.log("type_source: list");
            if(!container.options.drop)
              $item.clone().insertAfter($item);
            _super($item, container);
        }
        
        if(data_type_source === "item"){
            //console.log("type_source: item");
            if(!container.options.drop)
              $item.clone().insertAfter($item);
            _super($item, container);
            
        }
    }
});

$("ol.list-target").sortable({
    group: "no-drop"
});


</script>
';

            break;
    }
    $footer .= '';

} else {
    $content .= $select_content;
    if(!is_dir('projects/'.$file_name)) {
        mkdir('projects/'.$file_name,0777,true);
    }
    // TODO: --| DASHBOARD

    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading">';
    $content .= '<h5 class="panel-title" id="page-manager">'.__('Page Manager').'</h5>';
    $content .= '</div>';
    $content .= '<div class="panel-body">';
    // TODO: --|-- TABLE PAGE LIST
    $content .= '<blockquote class="blockquote blockquote-danger">
    <h4>'.__('The rules that apply are:').'</h4>
    <ul>
    <li>'.__('<code>Key icon</code> mean <strong>lock/unlocking</strong> a page that cannot be edited and <code>star icon with green background</code> mean the <strong>first page will appear</strong> and <code>page without star</code> can not used for a link.').'</li>
    <li>'.__('Link in webView, AppBrowser or Iframe cannot connect to app.').'</li>
    </ul>
    </blockquote>';
    $content .= '<h5>'.__('Menu Type').': <strong class="label label-warning">'.strtolower($_SESSION['PROJECT']['menu']['type']).'</strong></h5>';
    $content .= '<div class="table-responsive">';
    $content .= '<table class="table table-striped">';
    $content .= '<thead>';
    $content .= '<tr>';
    $content .= '<th>'.__('Lock').'</th>';
    $content .= '<th>'.__('Index').'</th>';

    $content .= '<th>'.__('Prefix (Title)').'</th>';
    //$content .= '<th>Type</th>';
    $content .= '<th>'.__('Info').'</th>';
    $content .= '<th>'.__('Code in-Link (Apps)').'</th>';
    $content .= '<th style="min-width:75px;"></th>';
    $content .= '</tr>';
    $content .= '</thead>';
    $content .= '<tbody>';

    foreach(glob('projects/'.$file_name."/page.*.json") as $page_file) {

        $info_page = json_decode(file_get_contents($page_file),true);
        $page_detail = $info_page['page'][0];
        if(!isset($page_detail['last_edit_by'])) {
            $page_detail['last_edit_by'] = '?';
        }
        $content .= '<tr>';

        // TODO:  --| ----- query
        $is_query = false;

        $sref_query = '';
        $nghref_query = '';
        if($page_detail['for'] == 'table-list') {
            if(isset($page_detail['query'])) {
                if(is_array($page_detail['query'])) {
                    $sref_query = '({'.implode('.',$page_detail['query']).':item.id})';
                    $nghref_query = '/{{item.id}}';
                    $is_query = true;
                }
            }
        }
        if($page_detail['for'] == 'table-item') {
            if(isset($page_detail['query'])) {
                if(is_array($page_detail['query'])) {
                    $sref_query = '({id:item.'.implode('.',$page_detail['query']).'})';
                    $nghref_query = '/{{item.'.implode('.',$page_detail['query']).'}}';
                }
            }
        }

        // TODO:  --| ----- index option

        $index = null;
        if($is_query == false) {
            if($page_detail['for'] !== 'table-item') {

                $index = null;

                if($page_detail['prefix'] == $_SESSION['PROJECT']['app']['index']) {
                    $icon = '<span class="label label-success"><i class="glyphicon glyphicon-star"></i></span>';
                } else {
                    $icon = '<span class="label label-default"><i class="glyphicon glyphicon-star-empty"></i></span>';
                }
                $index = '<a href="./?page=page&index='.$page_detail['prefix'].'">'.$icon.'</a>';


            }


            if($_SESSION['PROJECT']['menu']['type'] == 'tabs') {
                if($page_detail['menutype'] == 'side_menus-custom') {
                    $update_page_detail['page'][0] = $page_detail;
                    $update_page_detail['page'][0]['menutype'] = 'tabs-custom';
                    file_put_contents($page_file,json_encode($update_page_detail));
                    buildIonic($file_name);
                    header('Location: ./?page=page&err=null&notice=fix');
                }

            } else {
                if($page_detail['menutype'] == 'tabs-custom') {
                    $update_page_detail['page'][0] = $page_detail;
                    $update_page_detail['page'][0]['menutype'] = 'side_menus-custom';
                    file_put_contents($page_file,json_encode($update_page_detail));
                    buildIonic($file_name);
                    header('Location: ./?page=page&err=null&notice=fix');
                }
            }
        }

        // TODO:  --| ----- lock/unlock
        $lock_page = null;

        if(!isset($page_detail['lock'])) {
            $page_detail['lock'] = false;
        }

        if($page_detail['lock'] == true) {
            $lock_page = '<a href="./?page=page&unlock='.$page_detail['prefix'].'"><span class="label label-warning"><i class="fa fa-lock"></i></span></a>';
        } else {
            $lock_page = '<a href="./?page=page&lock='.$page_detail['prefix'].'"><span class="label label-danger"><i class="fa fa-unlock"></i></span></a>';
        }
        $page_builder = null;
        if(!isset($page_detail['builder_link'])) {
            $page_detail['builder_link'] = '';
        }
        if(strlen($page_detail['builder_link']) >= 2) {
            $url = explode('/',$page_detail['builder_link']);
            $page_builder = '<a class="btn btn-xs btn-success" target="_blank" href="./'.$url[count($url) - 1].'"><i class="fa fa-anchor"><i/> page-builder</a>';
        }

        $content .= '<td>'.$lock_page.'</td>';
        $content .= '<td>'.$index.'</td>';
        $content .= '<td><label class="label label-info">'.$page_detail['prefix'].'</label><br/><small>( <code>'.$page_detail['title'].'</code> )</small><br/><br/><div>'.$page_builder.'</div></td>';
        $content .= '<td >';
        $content .= '<div style="background: #ccc;padding: 5px;font-size: 11px; min-width:200px;">';
        $content .= '<ul class="list-unstyled">';
        $content .= '<li>'.__('for').': <code>'.$page_detail['for'].'</code></li>';
        $content .= '<li>'.__('created by').': <code>'.$page_detail['last_edit_by'].'</code></li>';
        $content .= '<li>'.__('menutype').': <code>'.$page_detail['menutype'].'</code></li>';
        $content .= '</ul>';
        $qr_text = '#/'.$subpage_path.'/'.$page_detail['prefix'].$nghref_query;
        $content .= '<img src="./system/plugin/qr-code.php?text='.base64_encode($qr_text).'" width="80" height="80" />';

        $content .= '</div>';

        $content .= '</td>';

        $content .= '
        <td>
        <textarea style="padding:5px;font-family:courier;width:250px;" onclick="this.focus();this.select()">&lt;a ng-href="#/'.$subpage_path.'/'.$page_detail['prefix'].$nghref_query.'"&gt;'.ucwords(str_replace('_',' ',($page_detail['title']))).'&lt;/a&gt;</textarea>
        <textarea style="padding:5px;font-family:courier;width:250px;" onclick="this.focus();this.select()">&lt;a ui-sref="'.$subpage_path.'.'.$page_detail['prefix'].$sref_query.'"&gt;'.ucwords(str_replace('_',' ',($page_detail['title']))).'&lt;/a&gt;</textarea>
        </td>';


        $content .= '<td>
                        <div class="btn-group">
                        <a class="btn btn-danger btn-xs delete-this-page" href="./?page=page&delete='.$page_detail['prefix'].'" ><i class="glyphicon glyphicon-trash"></i></a>
                        <a class="btn btn-success btn-xs" href="./?page=page&prefix='.$page_detail['prefix'].'" ><i class="glyphicon glyphicon-pencil"></i></a>
                        </div>
                    </td>
                    ';
        $content .= '</tr>';
    }
    $content .= '</tbody>';
    $content .= '</table>';
    $content .= '</div>';
    $content .= '<a class="btn btn-danger" id="delete-all-pages" href="./?page=page&delete=all"><i class="fa fa-trash"></i> '.__('Delete All Pages').'</a>';
    $content .= '</div>';
    $content .= '</div>';
    //$template->emulator = false;
}

// TODO: --| CONFIRM DIALOG
if(!isset($_SESSION['PROJECT']['page'])) {
    $_SESSION['PROJECT']['page'] = array();
}
$_current_pages = array();

if(is_array($_SESSION['PROJECT']['page'])) {
    foreach($_SESSION['PROJECT']['page'] as $current_page) {
        if(!isset($current_page['priority'])) {
            $current_page['priority'] = 'danger';
        }
        $var = $current_page['prefix'];
        $_current_pages[$var] = $current_page['priority'];
    }
}

if($_SESSION['PROJECT']['menu']['type'] == 'tabs') {
    if(is_array($_SESSION['PROJECT']['page'])) {
        foreach($_SESSION['PROJECT']['page'] as $is_valid_page) {
            if(!isset($is_valid_page['last_edit_by'])) {
                $is_valid_page['last_edit_by'] = '';
            }
            if((($is_valid_page['last_edit_by'] == 'menu') && ($is_valid_page['menutype'] == 'side_menus')) || (($is_valid_page['last_edit_by'] == 'menu') && ($is_valid_page['menutype'] == 'tabs'))) {
                $is_page_error = true;
                foreach($_SESSION['PROJECT']['menu']['items'] as $menu_items) {
                    if($menu_items['var'] == $is_valid_page['prefix']) {
                        $is_page_error = false;
                    }
                }
                if($is_page_error == true) {
                    $info_page = json_decode(file_get_contents('projects/'.$file_name."/page.".$is_valid_page['prefix'].".json"),true);
                    $page_detail = $info_page['page'][0];
                    $update_page_detail['page'][0] = $page_detail;
                    $update_page_detail['page'][0]['menutype'] = 'tabs-custom';
                    $update_page_detail['page'][0]['menu'] = '-';
                    $update_page_detail['page'][0]['for'] = '-';
                    $update_page_detail['page'][0]['last_edit_by'] = 'page';
                    file_put_contents('projects/'.$file_name."/page.".$is_valid_page['prefix'].".json",json_encode($update_page_detail));
                    buildIonic($file_name);
                    header('Location: ./?page=page&err=null&notice=fix');
                }
            }
        }
    }
}


$footer .= '<script type="text/javascript">var current_pages = '.json_encode($_current_pages);
if(JSM_DEBUG == true) {
    $footer .= ';console.log(current_pages);';
}


$footer .= '

$("#delete-all-pages").on("click",function(e){
    var notice = "" ; 
    notice += "Are you sure you want to delete all pages?"  ;
    return confirm(notice);
});
    
$(".delete-this-page").on("click",function(e){
    var notice = "" ; 
    notice += "Are you sure you want to delete this page?"  ;
    return confirm(notice);
});
        

$("#app-setup").on("submit",function(e){
    var notice = "" ; 
    notice += "Page already exists, do you want to save it"  ;
    return confirm(notice);
});


';

$footer .= '</script>';

$template->demo_url = $out_path.'/www/#/'.$subpage_path.'/'.$page_prefix.$stateParam;
$template->title = $template->base_title.' | '.'Page';
$template->base_desc = 'Page';
$template->content = $content;
$template->footer = $footer;

?>