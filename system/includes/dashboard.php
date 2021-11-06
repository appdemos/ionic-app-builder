<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2017
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

if(!defined('JSM_EXEC'))
{
    die(':)');
}
$bs = new jsmBootstrap();
$langs = new jsmLocale();
$locale[] = array('label' => 'English - US','value' => 'en-us');
foreach($langs->getLang() as $lang)
{
    $locale[] = array('label' => $lang['label'].' ('.$lang['prefix'].')','value' => $lang['prefix']);
}
$footer = null;
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
$file_name = './../system/404/';
$content = $form_input = null;

$raw_menu['app']['name'] = '';
$raw_menu['app']['name_unicode'] = '';

$raw_menu['app']['description'] = '';
$raw_menu['app']['version'] = '';
$raw_menu['app']['author_name'] = '';
$raw_menu['app']['author_email'] = '';
$raw_menu['app']['author_url'] = '';
$raw_menu['app']['company'] = '';
$raw_menu['app']['fb'] = 'http://facebook.com/{username}';
$raw_menu['app']['twitter'] = 'http://twitter.com/{username}';
$raw_menu['app']['gplus'] = 'https://plus.google.com/{user_id}';
$raw_menu['app']['index'] = 'dashboard';
$raw_menu['app']['lazyload'] = 'true';
$raw_menu['app']['soundtouch'] = 'false';
$raw_menu['app']['domain'] = 'youtube.com,w3schools.com';
$raw_menu['app']['start'] = 'index.html';
if(isset($_GET['active']))
{
    $_SESSION['FILE_NAME'] = str2var($_GET['active']);
    $file_name = $_SESSION['FILE_NAME'];

    //default module
    $mod = null;
    $mod['mod']['inappbrowser']['name'] = 'cordova-plugin-inappbrowser';
    $mod['mod']['inappbrowser']['engines'] = 'cordova';
    $mod['mod']['inappbrowser']['info'] = 'required by IMA BuildeRz Core';
    file_put_contents('projects/'.$file_name.'/mod.inappbrowser.json',json_encode($mod));

    buildIonic($file_name);
    header('Location: ./?page=dashboard&app_code=rebuild&app_id='.$file_name);
}

if(isset($_GET['reset']))
{
    session_destroy();
    foreach(glob("projects/*.zip") as $zip_file)
    {
        @unlink($zip_file);
    }
    header('Location: ./?page=dashboard');
}

if(isset($_GET['delete']))
{
    $path = str2var($_GET['delete']);
    @rrmdir('projects/'.$path);
    @rrmdir('output/'.$path);
    @unlink('projects/project_'.$path.'.zip');
    @unlink('projects/output_'.$path.'.zip');
    session_destroy();
    header('Location: ./?page=dashboard&err=null&notice=delete');
}
if(isset($_POST['menu-save']))
{
    $_POST['app']['name'] = str_replace('.',' Dot ',$_POST['app']['name']);

    $_POST['app']['name'] = str_replace('\'','',$_POST['app']['name']);

    $data['app'] = $_POST['app'];
    if(isset($_POST['app']['lazyload']))
    {
        $_POST['app']['lazyload'] = true;
    } else
    {
        $_POST['app']['lazyload'] = false;
    }
    if(isset($_POST['app']['soundtouch']))
    {
        $_POST['app']['soundtouch'] = true;
    } else
    {
        $_POST['app']['soundtouch'] = false;
    }

    if(isset($_POST['app']['sub-version']))
    {
        $_POST['app']['sub-version'] = true;
    } else
    {
        $_POST['app']['sub-version'] = false;
    }


    $file_name = '';
    if(isset($_POST['app']['name'][0]))
    {

        if(is_numeric($_POST['app']['name'][0]))
        {
            $file_name = '_';
        }
    }


    if(isset($_POST['app']['company'][0]))
    {
        if(is_numeric($_POST['app']['company'][0]))
        {
            $data['app']['company'] = substr(trim($_POST['app']['company']),1,strlen(trim($_POST['app']['company'])));
        }
    }

    $file_name .= str2var($_POST['app']['name']);

    $data['app']['prefix'] = $file_name;

    if(!is_dir('projects/'.$file_name))
    {
        @mkdir('projects/'.$file_name,0777,true);
    }
    $data['app']['index'] = 'dashboard';
    $data['app']['lazyload'] = $_POST['app']['lazyload'];
    $data['app']['soundtouch'] = $_POST['app']['soundtouch'];
    $data['app']['sub-version'] = $_POST['app']['sub-version'];

    if(isset($_POST['app']['network']))
    {
        $data['app']['network'] = true;
        $mod = null;
        $mod['mod']['network-information']['name'] = 'cordova-plugin-network-information';
        $mod['mod']['network-information']['engines'] = 'cordova';
        $mod['mod']['network-information']['info'] = 'required by IMA BuildeRz Core';
        file_put_contents('projects/'.$file_name.'/mod.network-information.json',json_encode($mod));
    } else
    {
        $data['app']['network'] = false;
        @unlink('projects/'.$file_name.'/mod.network-information.json');
    }

    $string = new jsmString();
    $data['app']['domain'] = $string->Convert($_POST['app']['domain'],'username','-,');


    $data['app']['description'] = $string->Convert($_POST['app']['description'],'alphabet',' -_,.');

    file_put_contents('projects/'.$file_name.'/app.json',json_encode($data));

    $_SESSION['FILE_NAME'] = $file_name;
    $form_input .= $bs->Alerts(null,'Application has been updated.','success',true);

    $mod = null;
    $mod['mod']['inappbrowser']['name'] = 'cordova-plugin-inappbrowser';
    $mod['mod']['inappbrowser']['engines'] = 'cordova';
    $mod['mod']['inappbrowser']['info'] = 'required by IMA BuildeRz Core';
    file_put_contents('projects/'.$file_name.'/mod.inappbrowser.json',json_encode($mod));


    buildIonic($file_name);
    header('Location: ./?page=dashboard&notice=save&err=null&app_id='.$file_name);
}
if(isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
}
if(file_exists('projects/'.$file_name.'/app.json'))
{
    $raw_menu = json_decode(file_get_contents('projects/'.$file_name.'/app.json'),true);
}
$content = notice();
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-server fa-stack-1x"></i></span>(IMAB) Dashboard</h4>';
$content .= '<ul class="nav nav-tabs">';
$content .= '<li class="active"><a href="#projects" data-toggle="tab">'.__('Projects').'</a></li>';
if(isset($_SESSION['FILE_NAME']))
{
    $content .= '<li><a href="#edit" data-toggle="tab">'.__('Edit').'</a></li>';
}
$content .= '<li><a href="#new" data-toggle="tab">'.__('New Project').'</a></li>';
if(isset($_SESSION['FILE_NAME']))
{
    $content .= '<li><a href="#help" data-toggle="tab">'.__('How To Build App?').'</a></li>';
}
$content .= '</ul>';
$content .= '<div class="tab-content">';
$content .= '<div class="tab-pane active" id="projects">';
$content .= '<br/>';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">'.__('Project Manager').'</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';

$content .= '<div class="table-responsive">';
$content .= '<table class="table table-striped">';
$content .= '<thead>';
$content .= '<tr>';
$content .= '<th style="width: 20%;">'.__('App Name').'</th>';
$content .= '<th class="app-desc">'.__('Description').'</th>';
$content .= '<th>'.__('Status').'</th>';
$content .= '<th>'.__('Clone').'</th>';
$content .= '<th colspan="3">'.__('Download').'</th>';
$content .= '<th>'.__('Delete').'</th>';
$content .= '</tr>';
$content .= '</thead>';
$content .= '<tbody>';
foreach(glob("projects/*/app.json") as $filename)
{
    $pefix = basename(pathinfo($filename,PATHINFO_DIRNAME));
    if($file_name == $pefix)
    {
        $btn_active = '<a class="btn btn-success" href="./?page=dashboard&active='.$pefix.'"><span class="fa fa-dot-circle-o"></span> Active</a>';
    } else
    {
        $btn_active = '<a class="btn btn-default" href="./?page=dashboard&active='.$pefix.'"><span class="fa fa-circle-o"></span>  Deactive</a>';
    }
    $btn_delete = '<a class="delete-this-project btn btn-xs btn-danger" href="./?page=dashboard&delete='.$pefix.'"><span class="glyphicon glyphicon-trash"></span> Delete</a>';
    $btn_download_project = '<a class="btn btn-sm btn-success" href="./download.php?download=project&prefix='.$pefix.'"><span class="fa fa-download"></span> IMA Project</a>';
    $btn_download_data = '<a class="btn btn-sm btn-success" href="./download.php?download=data&prefix='.$pefix.'"><span class="fa fa-download"></span> Data</a>';
    $btn_download_output = '
    
  <div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
      Output
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a target="_blank" href="./download.php?download=output&prefix='.$pefix.'">Ionic/Cordova</a></li>
      <li><a target="_blank" href="./download.php?download=output&prefix='.$pefix.'&build=phonegap">Phonegap</a></li>
    </ul>
  </div>    
 
    
    ';
    $btn_clone_project = '';
    if($file_name != $pefix)
    {
        $btn_clone_project = '<a class="btn btn-sm btn-warning clone-btn" href="./download.php?clone=project&prefix='.$pefix.'"><span class="fa fa-copy"></span> Clone to current</a>';
    }
    $data_project = json_decode(file_get_contents($filename),true);
    $content .= '<tr>';
    $content .= '<td>'.$data_project['app']['name'].'</td>';
    $content .= '<td class="app-desc"><small>'.$data_project['app']['description'].'</small></td>';
    $content .= '<td>'.$btn_active.'<div class="app-link-app"><a href="./output/'.$data_project['app']['prefix'].'/www/" class="btn btn-primary">App Link</a></div></td>';
    $content .= '<td>'.$btn_clone_project.'</td>';
    $content .= '<td>'.$btn_download_project.'</td>';
    $content .= '<td>'.$btn_download_data.'</td>';
    $content .= '<td>'.$btn_download_output.'</td>';
    $content .= '<td>'.$btn_delete.'</td>';
    $content .= '</tr>';
}
$content .= '</tbody>';
$content .= '</table>';
$content .= '</div>';
$content .= '<a class="btn btn-lg btn-primary" href="./?page=dashboard&reset=true"><span class="glyphicon glyphicon-retweet"></span> '.__('Reset Session').'</a>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
if(!isset($raw_menu['app']['domain']))
{
    $raw_menu['app']['domain'] = 'youtube.com, w3schools.com';
}
if(!isset($raw_menu['app']['soundtouch']))
{
    $raw_menu['app']['soundtouch'] = false;
}
if(!isset($raw_menu['app']['name_unicode']))
{
    $raw_menu['app']['name_unicode'] = $raw_menu['app']['name'];
}

if($raw_menu['app']['name_unicode'] == '')
{
    $raw_menu['app']['name_unicode'] = $raw_menu['app']['name'];
}

// TODO: ----|-- EDIT PROJECT
$form_input .= '<blockquote class="blockquote blockquote-danger">'.__('All fields (without <strong>Unicode Name</strong>) should be written with ANSI characters, foreign language characters not support.').'</blockquote>';
$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">'.__('Properties').'</h5>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';
$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-5">';
$form_input .= $bs->FormGroup('app[name]','default','text',__('App Name'),'MyApp',__('App name is readonly value'),'required readonly','8',$raw_menu['app']['name']);
$form_input .= '</div>';
$form_input .= '<div class="col-md-7">';
$form_input .= $bs->FormGroup('app[name_unicode]','default','text',__('App Name Support Unicode'),'',__('Use it if you need language localization'),'','8',$raw_menu['app']['name_unicode']);
$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= $bs->FormGroup('app[description]','default','textarea',__('Description'),'',__('a brief description of your application'),'required','8',$raw_menu['app']['description']);
if(!isset($raw_menu['app']['sub-version']))
{
    $raw_menu['app']['sub-version'] = false;
}
if($raw_menu['app']['sub-version'] == true)
{
    $checkbox_subversion = 'checked';
} else
{
    $checkbox_subversion = '';
}
$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('app[version]','default','text',__('Version').' (config.xml)','1.0',__('Enter aplication version'),'required','8',$raw_menu['app']['version']);
$form_input .= '</div>';
$form_input .= '<div class="col-md-8">';
$form_input .= $bs->FormGroup('app[sub-version]','default','checkbox',__('Sub Version'),__('Increment according date'),'',$checkbox_subversion,'8','true');
$form_input .= '</div>';
$form_input .= '</div>';


$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-6">';
$form_input .= $bs->FormGroup('app[author_name]','default','text',__('Author'),'Your Name',__('Enter your fullname'),'required','8',$raw_menu['app']['author_name']);
$form_input .= $bs->FormGroup('app[company]','default','text',__('Company').' <span style="color:red">***</span>','AnaskiNet',__('Company name is readonly value'),'required readonly','8',$raw_menu['app']['company']);
$form_input .= '</div>';
$form_input .= '<div class="col-md-6">';
$form_input .= $bs->FormGroup('app[author_url]','default','url',__('Author URL'),'http://domain.com',__('Enter your website'),'required','8',$raw_menu['app']['author_url']);
$form_input .= $bs->FormGroup('app[author_email]','default','email',__('Author Email'),'your@domain.com',__('Enter your email'),'required','8',$raw_menu['app']['author_email']);
$form_input .= '</div>';
$form_input .= '</div>';


$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">'.__('Social Network').'</h5>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';

$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('app[fb]','default','text',__('Facebook URL'),'http://facebook.com/ihsanadotcom',__('Facebook link'),'','8',$raw_menu['app']['fb']);
$form_input .= '</div>';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('app[twitter]','default','text',__('Twitter URL'),'http://twitter.com/ihsana',__('Twitter link'),'','8',$raw_menu['app']['twitter']);
$form_input .= '</div>';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('app[gplus]','default','text',__('Google Plus URL'),'https://plus.google.com/u/0/114729178599494307997/',__('Google Plus link'),'','8',$raw_menu['app']['gplus']);
$form_input .= '</div>';
$form_input .= '</div>';


$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">'.__('Options').'</h5>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';

if(!isset($raw_menu['app']['splash-screen-delay']))
{
    $raw_menu['app']['splash-screen-delay'] = '500';
}
if(!is_numeric($raw_menu['app']['splash-screen-delay']))
{
    $raw_menu['app']['splash-screen-delay'] = '500';
}

if(!isset($raw_menu['app']['fade-splash-screen-duration']))
{
    $raw_menu['app']['fade-splash-screen-duration'] = '0';
}
if(!is_numeric($raw_menu['app']['fade-splash-screen-duration']))
{
    $raw_menu['app']['fade-splash-screen-duration'] = '0';
}

$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-6">';
$form_input .= $bs->FormGroup('app[domain]','default','text',__('Domain Whitelist'),'',__('Every domain separated by commas, such as: <code>youtube.com, w3schools.com</code>'),'required data-type="tagsinput"','8',$raw_menu['app']['domain']);
$form_input .= $bs->FormGroup('app[splash-screen-delay]','default','text',__('Splash Screen Delay').' (config.xml)','500','default: 500','','8',$raw_menu['app']['splash-screen-delay']);
$form_input .= $bs->FormGroup('app[fade-splash-screen-duration]','default','text',__('Fade Splash Screen Duration').' (config.xml)','0','default: 0',' ','8',$raw_menu['app']['fade-splash-screen-duration']);

$form_input .= '</div>';
$form_input .= '<div class="col-md-6">';

if($raw_menu['app']['lazyload'] == true)
{
    $checkbox = 'checked';
} else
{
    $checkbox = '';
}
if(!isset($raw_menu['app']['soundtouch']))
{
    $raw_menu['app']['soundtouch'] = false;
}
if($raw_menu['app']['soundtouch'] == true)
{
    $checkbox_sound_touch = 'checked';
} else
{
    $checkbox_sound_touch = '';
}
if(!isset($raw_menu['app']['network']))
{
    $raw_menu['app']['network'] = false;
}
if($raw_menu['app']['network'] == true)
{
    $checkbox_network = 'checked';
} else
{
    $checkbox_network = '';
}

if(!isset($raw_menu['app']['direction']))
{
    $raw_menu['app']['direction'] = 'ltr';
}

$form_input .= $bs->FormGroup('app[lazyload]','default','checkbox',__('Image Placeholder'),__('Image Lazy Load'),'',$checkbox,'8');
$form_input .= $bs->FormGroup('app[soundtouch]','default','checkbox',__('Sound Touch').' (*beta)',__('Android Acoustic (not compatible with ios app)'),'',$checkbox_sound_touch,'8');
$form_input .= $bs->FormGroup('app[network]','default','checkbox',__('No Network'),__('Go / Redirect to Retry Page','This feature will not suitable when using webview and admobpro'),'',$checkbox_network,'8');

$_direction = $e_direction = array();
$_direction[] = array('label' => __('LTR - Left to Right'),'value' => 'ltr');
$_direction[] = array('label' => __('RTL - Right to Left'),'value' => 'rtl');
$z = 0;
foreach($_direction as $direction)
{
    $e_direction[$z] = $direction;
    if($raw_menu['app']['direction'] == $direction['value'])
    {
        $e_direction[$z]['active'] = true;
    }
    $z++;
}

if(!isset($raw_menu['app']['locale']))
{
    $raw_menu['app']['locale'] = '';
}

$z = 0;
foreach($locale as $_locale)
{
    $e_locale[$z] = $_locale;
    if($raw_menu['app']['locale'] == $_locale['value'])
    {
        $e_locale[$z]['active'] = true;
    }
    $z++;
}

$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-6">';
$form_input .= $bs->FormGroup('app[direction]','default','select',__('Text Direction').' (*beta)',$e_direction,'',null,'8');
$form_input .= '</div>';
$form_input .= '<div class="col-md-6">';
$form_input .= $bs->FormGroup('app[locale]','default','select',__('Locale (language, date and time)'),$e_locale,'Locale for angularJS',null,'8');
$form_input .= '</div>';
$form_input .= '</div>';


$t = 0;
if(!isset($raw_menu['app']['start']))
{
    $raw_menu['app']['start'] = 'index.html';
}
if(!isset($raw_menu['app']['prefix']))
{
    $raw_menu['app']['prefix'] = 'null';
}
$option_start = array();
foreach(glob('output/'.$raw_menu['app']['prefix']."/www/*.html") as $file_html)
{
    $option_start[$t] = array('label' => basename($file_html),'value' => basename($file_html));
    if($raw_menu['app']['start'] == basename($file_html))
    {
        $option_start[$t]['active'] = true;
    }
    $t++;
}

$_option_no_history[] = array('label' => __('Goto Home'),'value' => 'goto-home');
$_option_no_history[] = array('label' => __('Ionic Default (Exit without Confirm)'),'value' => 'none');
$_option_no_history[] = array('label' => __('Apps Exit (with Confirm)'),'value' => 'app-exit');
if(!isset($raw_menu['app']['no-history-back']))
{
    $raw_menu['app']['no-history-back'] = 'none';
}
$z = 0;
foreach($_option_no_history as $no_history)
{
    $option_no_history[$z] = $no_history;
    if($raw_menu['app']['no-history-back'] == $no_history['value'])
    {
        $option_no_history[$z]['active'] = true;
    }
    $z++;
}

$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-6">';
$form_input .= $bs->FormGroup('app[start]','default','select',__('Start Page/Content').' (config.xml)',$option_start,__('The file to be executed for the first time run.'),null,'8');
$form_input .= '</div>';
$form_input .= '<div class="col-md-6">';
$form_input .= $bs->FormGroup('app[no-history-back]','default','select',__('Event No History (Back Button)'),$option_no_history,__('The Device\'s Back Button'),null,'8');
$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= '</div>';
$form_input .= '</div>';
$form_input .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,array(array(
        'name' => 'menu-save',
        'label' => __('Save Changes').' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'),array(
        'label' => 'Reset',
        'tag' => 'reset',
        'color' => 'default'))));
$content .= '<div class="tab-pane" id="edit">';
$content .= '<br/>';
$content .= $bs->Forms('app-setup','','post','default',$form_input);
$content .= '</div>';

// TODO: ----|-- NEW PROJECT
$new_form_input = null;
$new_form_input .= '<blockquote class="blockquote blockquote-danger">'.__('All fields (without <strong>Unicode Name</strong>) should be written with ANSI characters, foreign language characters not support.').'</blockquote>';
$new_form_input .= '<div class="panel panel-default">';
$new_form_input .= '<div class="panel-heading">';
$new_form_input .= '<h5 class="panel-title">'.__('Properties').'</h5>';
$new_form_input .= '</div>';
$new_form_input .= '<div class="panel-body">';
$new_form_input .= '<div class="row">';
$new_form_input .= '<div class="col-md-5">';
$new_form_input .= $bs->FormGroup('app[name]','default','text',__('App Name').'<span style="color:red">*</span>','MyApp',__('Enter your aplication name, <code>only the characters a-z, A-Z, 0-9, and spaces allowed, and disallow numeric on 1st characters</code> (note: cannot change it later).'),'required','8','','');
$new_form_input .= '</div>';
$new_form_input .= '<div class="col-md-7">';
$new_form_input .= $bs->FormGroup('app[name_unicode]','default','text',__('App Name Support Unicode').'','',__('Used for language localization, keep blank for default'),'','8');
$new_form_input .= '</div>';
$new_form_input .= '</div>';


$new_form_input .= $bs->FormGroup('app[description]','default','textarea',__('Description').' <span style="color:red">*</span>','',__('a brief description of your application'),'required','8','','');
$new_form_input .= '<div class="row">';
$new_form_input .= '<div class="col-md-4">';
$new_form_input .= $bs->FormGroup('app[version]','default','text',__('Version').' (config.xml) <span style="color:red">*</span>','1.0',__('Enter aplication version'),'required','8','');
$new_form_input .= '</div>';
$new_form_input .= '<div class="col-md-8">';
$new_form_input .= $bs->FormGroup('app[sub-version]','default','checkbox',__('Sub Version'),__('Increment according date'),'','checked','8','true');
$new_form_input .= '</div>';
$new_form_input .= '</div>';

$new_form_input .= '<div class="row">';
$new_form_input .= '<div class="col-md-6">';
$new_form_input .= $bs->FormGroup('app[author_name]','default','text',__('Author').' <span style="color:red">*</span>','Your Name',__('Enter your fullname'),'required','4','');
$new_form_input .= $bs->FormGroup('app[company]','default','text',__('Company').' <span style="color:red">***</span>','AnaskiNet',__('Your company name,  <code>only the characters a-z, A-Z, 0-9, . and spaces allowed, and disallow numeric on 1st characters</code>. (note: cannot change it later)'),'required','8','');
$new_form_input .= '</div>';
$new_form_input .= '<div class="col-md-6">';
$new_form_input .= $bs->FormGroup('app[author_url]','default','url',__('Author URL').' <span style="color:red">*</span>','http://domain.com',__('Enter your website'),'required','8','');
$new_form_input .= $bs->FormGroup('app[author_email]','default','email',__('Author Email').' <span style="color:red">*</span>','your@domain.com',__('Enter your email'),'required','8');
$new_form_input .= '</div>';
$new_form_input .= '</div>';


$new_form_input .= '</div>';
$new_form_input .= '</div>';
$new_form_input .= '<div class="panel panel-default">';
$new_form_input .= '<div class="panel-heading">';
$new_form_input .= '<h5 class="panel-title">'.__('Social Network').'</h5>';
$new_form_input .= '</div>';
$new_form_input .= '<div class="panel-body">';

$new_form_input .= '<div class="row">';
$new_form_input .= '<div class="col-md-4">';
$new_form_input .= $bs->FormGroup('app[fb]','default','text',__('Facebook URL'),'http://facebook.com/ihsanadotcom',__('Facebook link'),'','8','');
$new_form_input .= '</div>';
$new_form_input .= '<div class="col-md-4">';
$new_form_input .= $bs->FormGroup('app[twitter]','default','text',__('Twitter URL'),'http://twitter.com/ihsana',__('Twitter link'),'','8','');
$new_form_input .= '</div>';
$new_form_input .= '<div class="col-md-4">';
$new_form_input .= $bs->FormGroup('app[gplus]','default','text',__('Google Plus URL'),'https://plus.google.com/u/0/114729178599494307997/',__('Google Plus Link'),'','8','');
$new_form_input .= '</div>';
$new_form_input .= '</div>';


$new_form_input .= '</div>';
$new_form_input .= '</div>';


$new_form_input .= '<div class="panel panel-default">';
$new_form_input .= '<div class="panel-heading">';
$new_form_input .= '<h5 class="panel-title">'.__('Options').'</h5>';
$new_form_input .= '</div>';
$new_form_input .= '<div class="panel-body">';
$new_form_input .= '<div class="row">';
$new_form_input .= '<div class="col-md-6">';
$new_form_input .= $bs->FormGroup('app[domain]','default','text',__('Domain Whitelist').' <span style="color:red">*</span>','',__('Every domain separated by commas, such as: <code>youtube.com, w3schools.com</code>'),'required data-type="tagsinput"','8',"youtube.com,w3schools.com");
$new_form_input .= $bs->FormGroup('app[splash-screen-delay]','default','text',__('Splash Screen Delay').' (config.xml)','500','default: 500','','8','500');
$new_form_input .= $bs->FormGroup('app[fade-splash-screen-duration]','default','text',__('Fade Splash Screen Duration').' (config.xml)','0','default: 0',' ','8','0');


$new_form_input .= '</div>';
$new_form_input .= '<div class="col-md-6">';
$new_form_input .= $bs->FormGroup('app[lazyload]','default','checkbox',__('Image Placeholder'),__('Image Lazy Load'),'','','8');
$new_form_input .= $bs->FormGroup('app[soundtouch]','default','checkbox',__('Sound Touch').' (*beta)',__('Android Acoustic (not compatible with ios app)'),'','','8');
$new_form_input .= $bs->FormGroup('app[network]','default','checkbox',__('No Network'),__('Go / Redirect to Retry Page','This feature will not suitable when using webview and admobpro'),'','8');

$_direction = array();
$_direction[] = array('label' => __('LTR - Left to Right'),'value' => 'ltr');
$_direction[] = array('label' => __('RTL - Right to Left'),'value' => 'rtl');

$new_form_input .= '</div>';
$new_form_input .= '</div>';
$new_form_input .= '<div class="row">';
$new_form_input .= '<div class="col-md-6">';
$new_form_input .= $bs->FormGroup('app[direction]','default','select',__('Text Direction').' (*beta)',$_direction,'',null,'8');
$new_form_input .= '</div>';
$new_form_input .= '<div class="col-md-6">';
$new_form_input .= $bs->FormGroup('app[locale]','default','select',__('Locale (language, date and time)'),$locale,__('Locale for angularJS'),null,'8');
$new_form_input .= '</div>';
$new_form_input .= '</div>';


// TODO: ----|------ option - Start Page/Content

$new_option_start = array(array('label' => 'index.html','value' => 'index.html'));

$_option_no_history = array();
$_option_no_history[] = array('label' => __('Goto Home'),'value' => 'goto-home');
$_option_no_history[] = array('label' => __('Apps Exit (with Confirm)'),'value' => 'app-exit');
$_option_no_history[] = array('label' => __('Ionic Default (Exit without Confirm)'),'value' => 'none');


$new_form_input .= '<div class="row">';
$new_form_input .= '<div class="col-md-6">';
$new_form_input .= $bs->FormGroup('app[start]','default','select',__('Start Page/Content').' (config.xml)',$new_option_start,__('The file to be executed for the first time run.'),null,'8');
$new_form_input .= '</div>';
$new_form_input .= '<div class="col-md-6">';
$new_form_input .= $bs->FormGroup('app[no-history-back]','default','select',__('Event No History').' (Back Button)',$_option_no_history,__('The Device\'s Back Button'),null,'8');
$new_form_input .= '</div>';
$new_form_input .= '</div>';


$new_form_input .= '</div>';
$new_form_input .= '</div>';

$new_form_input .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,array(array(
        'name' => 'menu-save',
        'label' => __('Create App').' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'),array(
        'label' => 'Reset',
        'tag' => 'reset',
        'color' => 'default'))));
$content .= '<div class="tab-pane" id="new">';
$content .= '<br/>';
$content .= $bs->Forms('app-setup','','post','default',$new_form_input);
$content .= '</div>';
if(isset($_SESSION['FILE_NAME']))
{


    $content .= '<div class="tab-pane" id="help">';
    $content .= '<br/>';


    $cordova_plugin_html = null;
    $cordova_plugin_html .= 'cordova-plugin-device<br/>';
    $cordova_plugin_html .= 'cordova-plugin-console<br/>';
    $cordova_plugin_html .= 'cordova-plugin-splashscreen<br/>';
    $cordova_plugin_html .= 'cordova-plugin-statusbar<br/>';
    $cordova_plugin_html .= 'cordova-plugin-whitelist<br/>';
    $cordova_plugin_html .= 'ionic-plugin-keyboard<br/>';
    if($raw_menu['app']['soundtouch'] == true)
    {
        $cordova_plugin_html .= 'cordova-plugin-velda-devicefeedback<br/>';
    }

    foreach($_SESSION['PROJECT']['mod'] as $mod)
    {
        $cordova_plugin_html .= $mod['name'].'<br/>';
    }
    $_env = null;
    if(isset($_SERVER['PATH']))
    {

        $paths = explode(';',$_SERVER['PATH']);
        // TODO: PROJECT INFO
        $_env .= '<pre>';
        foreach($paths as $path)
        {
            $_env .= $path."\r\n";
        }
        $_env .= '</pre>';
    }


    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading">';
    $content .= '<h5 class="panel-title">Properties</h5>';
    $content .= '</div>';
    $content .= '<div class="panel-body">';
    $content .= '
    
<div class="row">
    <div class="col-md-5">
        <h4 class="page-title">'.__('Project Information').'</h4>
        <dl class="dl-horizontal">
          <dt>'.__('App Name').'</dt>
          <dd>'.$_SESSION['PROJECT']['app']['name'].'</dd>
          <dt>'.__('App Prefix').'</dt>
          <dd><span class="label label-danger">'.($_SESSION['PROJECT']['app']['prefix']).'</span></dd>
          <dt>'.__('Package Name').'</dt>
          <dd><span class="label label-info">'.JSM_PACKAGE_NAME.'.'.str_replace('_','',str2var($_SESSION['PROJECT']['app']['company'])).'.'.str_replace('_','',$_SESSION['PROJECT']['app']['prefix']).'</span></dd>
          <dt>'.__('Cordova').'</dt>
          <dd><a target="_blank" href="output/'.$_SESSION['PROJECT']['app']['prefix'].'/config.xml">config.xml</a></dd>
          <dt>'.__('Cordova Plugin').'</dt>
          <dd><pre>'.$cordova_plugin_html.'</pre></dd> 
        </dl>        
    </div>
    
    <div class="col-md-4">
        <h4 class="page-title">'.__('Your OS Environment').'</h4>
        '.$_env.'
    </div>   
        
    <div class="col-md-3">
        <h4 class="page-title">'.__('Official Cordova Guides').'</h4>
            <ul>
                <li><a href="https://cordova.apache.org/docs/en/latest/guide/platforms/android/">Android Platform Guide</a></li>
                <li><a href="https://cordova.apache.org/docs/en/latest/guide/platforms/blackberry10/home.html">Blackberry 10 Guides</a></li>
                <li><a href="https://cordova.apache.org/docs/en/latest/guide/platforms/ios/index.html">iOS Platform Guide</a></li>
                <li><a href="https://cordova.apache.org/docs/en/latest/guide/platforms/osx/index.html">OS X Platform Guide</a></li>
                <li><a href="https://cordova.apache.org/docs/en/latest/guide/platforms/ubuntu/index.html">Ubuntu Platform Guide</a></li>
                <li><a href="https://cordova.apache.org/docs/en/latest/guide/platforms/win8/index.html">Windows Platform Guide</a></li>
                <li><a href="https://cordova.apache.org/docs/en/latest/guide/platforms/wp8/home.html">WP8 Guides</a></li>
            </ul>    
    </div> 
 
</div>
<hr/>
<div class="row">
<div class="col-md-2">
<h4>'.__('What is Your Operating System for nodejs?').'</h4>
 <div class="checkbox"><label><input type="checkbox" class="what_os" value="win" checked="checked" />Window</label></div>
 <div class="checkbox"><label><input type="checkbox" class="what_os" value="nix" checked="checked" />Linux</label></div>
 <div class="checkbox"><label><input type="checkbox" class="what_os" value="osx" checked="checked" />OSX</label></div>
<hr/>
</div>
';
    $content .= '<div class="col-md-10">';
    $content .= '<h4>'.__('The Main Concepts: Import IMAB Output to Your Cordova Project').'</h4><p>'.__('Copy the output code into your cordova or ionic project.').'</p>';
    $content .= '<div class="win">';
    $content .= '<h4>Windows</h4>';
    $content .= '<pre class="shell">xcopy /Y /S "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'*"</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="osx nix">';
    $content .= '<h4>Linux/Mac</h4>';
    $content .= '<pre class="shell">yes | cp -rf "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'" .</pre>'."\r\n";
    $content .= '</div>';

    $download_output = '<a target="_blank" target="_blank" href="./download.php?download=output&prefix='.$raw_menu['app']['prefix'].'"><span class="fa fa-download"></span> Output</a>';
    $content .= '<blockquote class="blockquote blockquote-info">'.__('For <strong>online IMA BuildeRz</strong>, you can download then unzip/extract file: ').$download_output.' '.__('to your cordova project').'</blockquote>'."\r\n";
    $content .= '</div>';
    $content .= '</div>';


    $content .= '</div>';
    $content .= '</div>';

    $content .= '';
    $content .= '<blockquote class="blockquote blockquote-danger"><h4>'.__('Follow this Instructions').'</h4>'.__('This instructions below will be updated in accordance with a menu that you use on this tool, choose one of the compilers you just used (cordova, ionic or online phonegap)').'</blockquote>';

    $content .= '<div class="panel-group" id="accordion">';

    if(!isset($raw_menu['app']['prefix']))
    {
        session_destroy();
        header('Location: ./?page=dashboard&reset');
    }


    // TODO: HOWTO CORDOVA
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading"><a data-toggle="collapse" data-parent="#accordion" href="#how-to-cordova"><h5 class="panel-title">1) '.__('How to build a project with Cordova? (recommended)').'</h5></a></div>';
    $content .= '<div id="how-to-cordova" class="panel-collapse collapse">';
    $content .= '<div class="panel-body">';
    // TODO: --|-------- SYSTEM REQUIREMENT
    $content .= '<h5>'.__('System Requirements').'</h5>';
    $content .= '<ol>';
    $content .= '<li>';
    $content .= 'JDK 1.8 or '.__('Latest').', <a href="http://www.oracle.com/technetwork/java/javase/downloads/index.html" target="_blank">'.__('download').'</a>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= 'AndroidSDK, <a href="https://developer.android.com/studio/index.html#resources" target="_blank">'.__('download').'</a>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= 'Gradle, <a href="https://gradle.org/install/" target="_blank">'.__('download').'</a>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= 'NodeJS, <a href="https://nodejs.org/en/download/" target="_blank">'.__('download').'</a>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= __('Install cordova using').' <code>Node.js command prompt</code> (window) '.__('or using').' <code>terminal/bash</code><pre class="shell">npm install -g cordova --save</pre>'."\r\n";
    $content .= '</li>';


    $content .= '</ol>';

    $content .= '<h5>'.__('How to build APK?').'</h5>';
    $content .= '<blockquote class="blockquote blockquote-danger">'.__('Before running the command below, make sure your <ins>cordova</ins> is installed correctly. Please! test it with blank project').'</blockquote>';
    // TODO: --|-------- BUILD APK
    $content .= '<ol>';
    $content .= '<li>';
    $content .= '<p>'.__('Connect your internet, then run').' <code>Node.js command prompt</code> '.__('or using').' <code>terminal/bash</code></p>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>'.__('Make sure where current folder now (Maybe you can skip this step)').'</p>'."\r\n";

    $content .= '<div class="win">';
    $content .= '<h4>Window</h4>';
    $content .= '<p>'.__('Go to drive').' D:\</p>'."\r\n";
    $content .= '<pre class="shell">d:</pre>'."\r\n";
    $content .= '<p>'.__('Run').' explorer</p>'."\r\n";
    $content .= '<pre class="shell">explorer .</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="nix">';
    $content .= '<h4>Linux</h4>';
    $content .= '<pre class="shell">sudo su</pre>'."\r\n";
    $content .= '<pre class="shell">pwd</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="osx">';
    $content .= '<h4>Mac</h4>';

    $content .= '<p>'.__('Go to <code>Documents</code> folder, or other folder').'</p>'."\r\n";
    $content .= '<pre class="shell">cd ~/Documents/</pre>'."\r\n";
    $content .= '<p>'.__('Run').' finder</p>'."\r\n";
    $content .= '<pre class="shell">open .</pre>'."\r\n";
    $content .= '</div>';

    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>'.__('Start cordova project').' <code>'.$raw_menu['app']['name'].'</code> '.__('with run the following command:').'</p>'."\r\n";
    $content .= '<pre class="shell">cordova create '.$raw_menu['app']['prefix'].' "'.JSM_PACKAGE_NAME.'.'.str_replace('_','',str2var($raw_menu['app']['company'])).'.'.str_replace('_','',str2var($raw_menu['app']['prefix'])).'" "'.$raw_menu['app']['name'].'"</pre>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>'.__('Browse to the newly created folder:').'</p>'."\r\n";
    $content .= '<pre class="shell">cd '.$raw_menu['app']['prefix'].'</pre>'."\r\n";
    $content .= '</li>';
    $content .= '<li>'.__('Add cordova plugins').':';
    $content .= '<ul>';
    $content .= '<li>';
    $content .= '<p>'.__('Install the plugin').' <strong class="text-primary">cordova-plugin-device</strong> :</p>'."\r\n";
    $content .= '<pre class="shell">cordova plugin add cordova-plugin-device --save</pre>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>'.__('Install the plugin').' <strong class="text-primary">cordova-plugin-console</strong> :</p>'."\r\n";
    $content .= '<pre class="shell">cordova plugin add cordova-plugin-console --save</pre>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>'.__('Install the plugin').' <strong class="text-primary">cordova-plugin-splashscreen</strong> :</p>'."\r\n";
    $content .= '<pre class="shell">cordova plugin add cordova-plugin-splashscreen --save</pre>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>'.__('Install the plugin').' <strong class="text-primary">cordova-plugin-statusbar</strong> :</p>'."\r\n";
    $content .= '<pre class="shell">cordova plugin add cordova-plugin-statusbar --save</pre>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>'.__('Maybe already exist, but sometime problem about network, so for re-install the plugin').' <strong class="text-primary">cordova-plugin-whitelist</strong> :</p>'."\r\n";
    $content .= '<pre class="shell">cordova plugin rm cordova-plugin-whitelist --save</pre>'."\r\n";
    $content .= '<pre class="shell">cordova plugin add cordova-plugin-whitelist --save</pre>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>'.__('Install the plugin').' <strong class="text-primary">ionic-plugin-keyboard</strong> :</p>'."\r\n";
    $content .= '<pre class="shell">cordova plugin add ionic-plugin-keyboard --save</pre>'."\r\n";
    $content .= '</li>';
    //'cordova-plugin-inappbrowser'
    if(isset($_SESSION['PROJECT']['mod']))
    {
        foreach($_SESSION['PROJECT']['mod'] as $mod)
        {
            if($mod['engines'] == 'cordova')
            {
                $cordova_param = '';
                if(isset($mod['var']))
                {
                    $cordova_param = '--variable '.$mod['var'];
                }

                $content .= '<li>';
                $content .= '<p>'.__('Install the plugin').' <strong class="text-primary">'.$mod['name'].'</strong> :</p>'."\r\n";
                $content .= '<pre class="shell">cordova plugin add '.$mod['name'].' --save '.$cordova_param.'</pre>'."\r\n";
                $content .= '</li>';

            }
        }
    }
    if($raw_menu['app']['soundtouch'] == true)
    {
        $content .= '<li>';
        $content .= '<p>'.__('Install the plugin').' <strong class="text-primary">cordova-plugin-velda-devicefeedback</strong> :</p>'."\r\n";
        $content .= '<pre class="shell">cordova plugin add cordova-plugin-velda-devicefeedback --save</pre>'."\r\n";
        $content .= '</li>';
    }
    $content .= '</ul>';
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>'.__('Add platform do you need, example: <code>android</code> or <code>ios</code>').'</p>'."\r\n";
    $content .= '<pre class="shell">cordova platform add android@latest</pre>'."\r\n";

    $content .= '<div class="osx">';
    $content .= '<p>'.__('or for ios (only support for osx)').'</p>'."\r\n";
    $content .= '<pre class="shell">cordova platform add ios</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<p>'.__('If it fails in the middle of the process use the command').':</p>'."\r\n";
    $content .= '<pre class="shell">cordova clean</pre>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>'.__('Check cordova requirements').'</p>'."\r\n";
    $content .= '<pre class="shell">cordova requirements</pre>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>'.__('Copy the (IMAB) Output to current folder (cordova/nodejs) and override the files and folders when prompted.').'</p>'."\r\n";
    if(!file_exists(JSM_PATH.'/output/'.$raw_menu['app']['prefix'].'/resources/icon.png'))
    {
        $content .= '<p>'.__('<span class="label label-danger">Icon and Splashscreen</span> You have not created icon and splashscreen, click Extra Menu -&gt; <a target="_blank" href="./?page=x-resources" >(IMAB) Resources</a> for create its.').'</p>';
    }
    $content .= '<div class="win">';
    $content .= '<h4>Windows</h4>';
    $content .= '<pre class="shell">xcopy /Y /S "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'*"</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="osx nix">';
    $content .= '<h4>Linux/Mac</h4>';
    $content .= '<pre class="shell">yes | cp -rf "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'" .</pre>'."\r\n";
    $content .= '</div>';

    $download_output = '<a target="_blank" target="_blank" href="./download.php?download=output&prefix='.$raw_menu['app']['prefix'].'"><span class="fa fa-download"></span> (IMAB) Output</a>';
    $content .= '<div class="alert alert-info">'.__('For <strong>online IMA BuildeRz</strong>, you can download then unzip/extract file').': '.$download_output.' '.__('to your cordova project').'</div>'."\r\n";

    $content .= '</li>';

    if(isset($_SESSION['PROJECT']['mod']['notification']))
    {
        if($_SESSION['PROJECT']['mod']['notification']['name'] == 'cordova-plugin-fcm')
        {
            $content .= '<li>';
            $content .= __('Copy <a href="https://console.firebase.google.com/" target="_blank">google-service.json</a> file to <strong>root</strong> directory for Android. or Put your generated file GoogleService-Info.plist in the project root folder for IOS.')."\r\n";
            $content .= '</li>';
        }
    }
    $content .= '<li>';
    $content .= '<p>'.__('Build your project').'</p>'."\r\n";
    $content .= '<pre class="shell">cordova build android</pre>'."\r\n";

    $content .= '<div class="osx">';
    $content .= '<p>'.__('or for ios (only support for osx)').'</p>'."\r\n";

    $content .= '<pre class="shell">sudo npm install --global --unsafe-perm ios-deploy</pre>'."\r\n";
    $content .= '<p>if you get error, run this command:</p>'."\r\n";
    $content .= '<pre class="shell">cordova plugin save</pre>'."\r\n";
    $content .= '<pre class="shell">cordova platform rm ios</pre>'."\r\n";
    $content .= '<pre class="shell">cordova platform add ios</pre>'."\r\n";
    $content .= '<p>Now, build to xcode project</p>'."\r\n";
    $content .= '<pre class="shell">cordova build ios</pre>'."\r\n";
    $content .= '</div>';
    $content .= '<p>Then run file: <kbd>./platforms/ios/'.$raw_menu['app']['name'].'.xcworkspace/</kbd> with xcode</p>'."\r\n";

    $content .= '</li>';
    $content .= '</ol>';

    $content .= '<hr/>';
    // TODO: --|-------- REBUILD CORDOVA
    $content .= '<h4>'.__('To continue the project that you created earlier').':</h4>'."\r\n";
    $content .= '<ol>';
    $content .= '<li>';
    $content .= '<p>'.__('Close').' <code>Node.js command prompt</code>, '.__('then run again').' <code>Node.js command prompt</code></p>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>'.__('Make sure where current folder now (Maybe you can skip this step)').'</p>'."\r\n";

    $content .= '<div class="win">';
    $content .= '<h4>Window</h4>';
    $content .= '<p>'.__('Go to drive').' D:\</p>'."\r\n";
    $content .= '<pre class="shell">d:</pre>'."\r\n";
    $content .= '<p>'.__('Run').' explorer</p>'."\r\n";
    $content .= '<pre class="shell">explorer .</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="nix">';
    $content .= '<h4>Linux</h4>';
    $content .= '<pre class="shell">sudo su</pre>'."\r\n";
    $content .= '<pre class="shell">pwd</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="osx">';
    $content .= '<h4>Mac</h4>';
    $content .= '<p>'.__('Login as administrator').'</p>'."\r\n";
    $content .= '<pre class="shell">sudo su</pre>'."\r\n";
    $content .= '<p>'.__('Go to <code>Documents</code> folder, or other folder').'</p>'."\r\n";
    $content .= '<pre class="shell">cd ~/Documents/</pre>'."\r\n";
    $content .= '<p>'.__('Run').' finder</p>'."\r\n";
    $content .= '<pre class="shell">open .</pre>'."\r\n";
    $content .= '</div>';

    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>'.__('Then go to folder your project').':</p>'."\r\n";
    $content .= '<pre class="shell">cd '.$raw_menu['app']['prefix'].'</pre>'."\r\n";
    $content .= '</li>';


    $content .= '<li>';
    $content .= '<p>'.__('Copy the (IMAB) Output to current folder (cordova/nodejs) and override the files and folders when prompted.').'</p>'."\r\n";
    if(!file_exists(JSM_PATH.'/output/'.$raw_menu['app']['prefix'].'/resources/icon.png'))
    {
        $content .= '<p>'.__('<span class="label label-danger">Icon and Splashscreen</span> You have not created icon and splashscreen, click Extra Menu -&gt; <a target="_blank" href="./?page=x-resources" >(IMAB) Resources</a> for create its.').'</p>';
    }
    $content .= '<div class="win">';
    $content .= '<h4>Windows</h4>';
    $content .= '<pre class="shell">xcopy /Y /S "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'*"</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="osx nix">';
    $content .= '<h4>Linux/Mac</h4>';
    $content .= '<pre class="shell">yes | cp -rf "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'" .</pre>'."\r\n";
    $content .= '</div>';

    $download_output = '<a target="_blank" target="_blank" href="./download.php?download=output&prefix='.$raw_menu['app']['prefix'].'"><span class="fa fa-download"></span> (IMAB) Output</a>';
    $content .= '<div class="alert alert-info">'.__('For <strong>online IMA BuildeRz</strong>, you can download then unzip/extract file').': '.$download_output.' '.__('to your cordova project').'</div>'."\r\n";

    $content .= '</li>';

    if(isset($_SESSION['PROJECT']['mod']['notification']))
    {
        if($_SESSION['PROJECT']['mod']['notification']['name'] == 'cordova-plugin-fcm')
        {
            $content .= '<li>';
            $content .= __('Copy <a href="https://console.firebase.google.com/" target="_blank">google-service.json</a> file to <strong>root</strong> directory for Android. or Put your generated file GoogleService-Info.plist in the project root folder for IOS.')."\r\n";
            $content .= '</li>';
        }
    }
    $content .= '<li>';
    $content .= '<p>'.__('Build your project').'</p>'."\r\n";
    $content .= '<pre class="shell">cordova build android</pre>'."\r\n";

    $content .= '<div class="osx">';
    $content .= '<p>'.__('or for ios (only support for osx)').'</p>'."\r\n";

    $content .= '<pre class="shell">sudo npm install --global --unsafe-perm ios-deploy</pre>'."\r\n";
    $content .= '<p>if you get error, run this command:</p>'."\r\n";
    $content .= '<pre class="shell">cordova plugin save</pre>'."\r\n";
    $content .= '<pre class="shell">cordova platform rm ios</pre>'."\r\n";
    $content .= '<pre class="shell">cordova platform add ios</pre>'."\r\n";
    $content .= '<p>Now, build to xcode project</p>'."\r\n";
    $content .= '<pre class="shell">cordova build ios</pre>'."\r\n";
    $content .= '</div>';
    $content .= '<p>Then run file: <kbd>./platforms/ios/'.$raw_menu['app']['name'].'.xcworkspace/</kbd> with xcode</p>'."\r\n";

    $content .= '</li>';


    $content .= '</ol>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';


    // TODO: HOWTO IONIC
    $content .= '<div class="panel panel-default">'."\r\n";
    $content .= '<div class="panel-heading">'."\r\n";
    $content .= '<h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#how-to-ionic">2) How to build a project with Ionic?</a></h5>';
    $content .= '</div>';
    $content .= '<div id="how-to-ionic" class="panel-collapse collapse">';
    $content .= '<div class="panel-body">';

    $content .= '<h5>Requirements</h5>';
    $content .= '<ol>';
    $content .= '<li>';
    $content .= 'JDK 1.8 or Latest, <a href="http://www.oracle.com/technetwork/java/javase/downloads/index.html" target="_blank">download</a>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= 'AndroidSDK, <a href="https://developer.android.com/studio/index.html#resources" target="_blank">download</a>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= 'Gradle, <a href="https://gradle.org/install/" target="_blank">'.__('download').'</a>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= 'Git Command'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= 'NodeJS, <a href="https://nodejs.org/en/download/" target="_blank">download</a>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= 'Install ionic and cordova using "Node.js command prompt" (window) or using terminal/bash
    <pre>npm install -g ionic --save</pre>
    <pre>npm install -g cordova --save</pre>
    '."\r\n";
    $content .= '</li>';
    $content .= '</ol>';

    $content .= '<h5>How to build APK?</h5>';
    $content .= '<blockquote class="blockquote blockquote-danger">Before running the command below, make sure your <ins>cordova</ins> and <ins>ionic</ins> are installed correctly. Please! test it with blank project.</blockquote>';

    $content .= '<ol>';
    $content .= '<li>';
    $content .= '<p>Connect your internet, run <code>Node.js command prompt</code></p>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>Make sure where current folder now (Maybe you can skip this step)</p>'."\r\n";

    $content .= '<div class="win">';
    $content .= '<h4>Window</h4>';
    $content .= '<p>Go to drive D:\</p>'."\r\n";
    $content .= '<pre class="shell">d:</pre>'."\r\n";
    $content .= '<p>Open explorer</p>'."\r\n";
    $content .= '<pre class="shell">explorer .</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="nix">';
    $content .= '<h4>Linux</h4>';
    $content .= '<pre class="shell">sudo su</pre>'."\r\n";
    $content .= '<pre class="shell">pwd</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="osx">';
    $content .= '<h4>Mac</h4>';

    $content .= '<p>Go to <code>Documents</code> folder, or other folder</p>'."\r\n";
    $content .= '<pre class="shell">cd ~/Documents/</pre>'."\r\n";
    $content .= '<p>Open finder</p>'."\r\n";
    $content .= '<pre class="shell">open .</pre>'."\r\n";
    $content .= '</div>';

    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>Start your project with using the <code>--type=ionic1</code> flag and run the following command:</p>'."\r\n";

    $content .= '<pre class="shell">ionic start '.$raw_menu['app']['prefix'].' blank --display-name="'.$raw_menu['app']['name'].'" --type=ionic1</pre>'."\r\n";

    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Type <kbd>Y</kbd> to integrate your new app with Cordova</p>'."\r\n";
    $content .= '<pre class="shell">Y</pre>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Browse to the newly created folder:</p>'."\r\n";
    $content .= '<pre class="shell">cd '.$raw_menu['app']['prefix'].'</pre>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= 'Add ionic/cordova plugin:';
    $content .= '<ol>';

    $content .= '<li>';
    $content .= '<p>Maybe already exist, but sometime problem about network, so for re-install plugin <strong class="text-primary">cordova-plugin-whitelist</strong> :</p>'."\r\n";
    $content .= '<pre class="shell">ionic cordova plugin rm cordova-plugin-whitelist --save</pre>'."\r\n";
    $content .= '<pre class="shell">ionic cordova plugin add cordova-plugin-whitelist --save</pre>'."\r\n";
    $content .= '</li>';


    //'cordova-plugin-inappbrowser'
    if(isset($_SESSION['PROJECT']['mod']))
    {
        foreach($_SESSION['PROJECT']['mod'] as $mod)
        {
                $cordova_param = '';
                if(isset($mod['var']))
                {
                    $cordova_param = '--variable '.$mod['var'];
                }
            $content .= '<li>';
            $content .= '<p>Install plugin <strong class="text-primary">'.$mod['name'].'</strong> :</p>'."\r\n";
            $content .= '<pre class="shell">ionic cordova plugin add '.$mod['name'].' --save '.$cordova_param.'</pre>'."\r\n";
            $content .= '</li>';

        }
    }
    if($raw_menu['app']['soundtouch'] == true)
    {
        $content .= '<li>';
        $content .= '<p>Install plugin <strong class="text-primary">cordova-plugin-velda-devicefeedback</strong> (android only) :</p>'."\r\n";
        $content .= '<pre class="shell">ionic cordova plugin add cordova-plugin-velda-devicefeedback --save</pre>'."\r\n";
        $content .= '</li>';
    }
    $content .= '</ol>';
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>Add platform do you need, example: <code>android</code> or <code>ios</code></p>'."\r\n";
    $content .= '<pre class="shell">ionic cordova platform add android@latest</pre>'."\r\n";

    $content .= '<div class="osx">';
    $content .= '<p>or for ios (only support for osx)</p>'."\r\n";
    $content .= '<pre class="shell">ionic cordova platform add ios</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<p>If it fails in the middle of the process use the command:</p>'."\r\n";
    $content .= '<pre class="shell">cordova clean</pre>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>Copy the output of this project file to current folder and override the files and folders when prompted.</p>'."\r\n";
    $content .= '<div class="win">';
    $content .= '<h4>Windows</h4>';
    $content .= '<pre class="shell">xcopy /Y /S "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'*"</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="osx nix">';
    $content .= '<h4>Linux/Mac</h4>';
    $content .= '<pre class="shell">yes | cp -rf "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'" .</pre>'."\r\n";
    $content .= '</div>';

    $download_output = '<a target="_blank" href="./download.php?download=output&prefix='.$raw_menu['app']['prefix'].'"><span class="fa fa-download"></span> Output</a>';
    $content .= '<div class="alert alert-info">For <strong>online IMA BuildeRz</strong>, you can download then unzip/extract file: '.$download_output.' to your ionic project.</div>'."\r\n";


    $content .= '</li>';
    if(isset($_SESSION['PROJECT']['mod']['notification']))
    {
        if($_SESSION['PROJECT']['mod']['notification']['name'] == 'cordova-plugin-fcm')
        {
            $content .= '<li>';
            $content .= 'Copy <a href="https://console.firebase.google.com/" target="_blank">google-service.json</a> file to <strong>root</strong> directory for Android. or Put your generated file GoogleService-Info.plist in the project root folder for IOS.'."\r\n";
            $content .= '</li>';
        }
    }
    $content .= '<li>';
    $content .= '<p>Build your project</p>'."\r\n";
    $content .= '<pre class="shell">ionic cordova build android</pre>'."\r\n";
    $content .= '<div class="osx">';
    $content .= '<p>or for ios (only support for osx)</p>'."\r\n";
    $content .= '<pre class="shell">ionic cordova build ios</pre>'."\r\n";
    $content .= '<p>Then run file: <kbd>./platforms/ios/'.$raw_menu['app']['name'].'.xcworkspace/</kbd> with xcode</p>'."\r\n";

    $content .= '</div>';
    $content .= '</li>';
    $content .= '</ol>';

    // TODO: REBUILD IONIC
    $content .= '<h4>To continue the project that you created earlier:</h4>'."\r\n";
    $content .= '<ol>';
    $content .= '<li>';
    $content .= '<p>Close "Node.js command prompt", then run again "Node.js command prompt"</p>';
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>Make sure where current folder now</p>'."\r\n";

    $content .= '<div class="win">';
    $content .= '<h4>Window</h4>';
    $content .= '<p>Go to drive D:\</p>'."\r\n";
    $content .= '<pre class="shell">d:</pre>'."\r\n";
    $content .= '<p>Open explorer</p>'."\r\n";
    $content .= '<pre class="shell">explorer .</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="nix">';
    $content .= '<h4>Linux</h4>';
    $content .= '<pre class="shell">sudo su</pre>'."\r\n";
    $content .= '<pre class="shell">pwd</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="osx">';
    $content .= '<h4>Mac</h4>';
    $content .= '<p>Login as administrator</p>'."\r\n";
    $content .= '<pre class="shell">sudo su</pre>'."\r\n";
    $content .= '<p>Go to <code>Documents</code> folder, or other folder</p>'."\r\n";
    $content .= '<pre class="shell">cd ~/Documents/</pre>'."\r\n";
    $content .= '<p>Open finder</p>'."\r\n";
    $content .= '<pre class="shell">open .</pre>'."\r\n";
    $content .= '</div>';

    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>Then go to folder your project:</p>'."\r\n";
    $content .= '<pre class="shell">cd '.$raw_menu['app']['prefix'].'</pre>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Copy the output of this project file to current folder and override the files and folders when prompted.</p>'."\r\n";
    $content .= '<div class="win">';
    $content .= '<h4>Windows</h4>';
    $content .= '<pre class="shell">xcopy /Y /S "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'*"</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="osx nix">';
    $content .= '<h4>Linux/Mac</h4>';
    $content .= '<pre class="shell">yes | cp -rf "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'" .</pre>'."\r\n";
    $content .= '</div>';

    $download_output = '<a target="_blank" href="./download.php?download=output&prefix='.$raw_menu['app']['prefix'].'"><span class="fa fa-download"></span> Output</a>';
    $content .= '<div class="alert alert-info">For <strong>online IMA BuildeRz</strong>, you can download then unzip/extract file: '.$download_output.' to your ionic project.</div>'."\r\n";


    $content .= '</li>';
    if(isset($_SESSION['PROJECT']['mod']['notification']))
    {
        if($_SESSION['PROJECT']['mod']['notification']['name'] == 'cordova-plugin-fcm')
        {
            $content .= '<li>';
            $content .= 'Copy <a href="https://console.firebase.google.com/" target="_blank">google-service.json</a> file to <strong>root</strong> directory for Android. or Put your generated file GoogleService-Info.plist in the project root folder for IOS.'."\r\n";
            $content .= '</li>';
        }
    }
    $content .= '<li>';
    $content .= '<p>Build your project</p>'."\r\n";
    $content .= '<pre class="shell">ionic cordova build android</pre>'."\r\n";
    $content .= '<div class="osx">';
    $content .= '<p>or for ios (only support for osx)</p>'."\r\n";
    $content .= '<pre class="shell">ionic cordova build ios</pre>'."\r\n";
    $content .= '</div>';
    $content .= '</li>';


    $content .= '</ol>';

    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';


    // TODO:HOW TO ADOBE PHONEGAP
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading"><a data-toggle="collapse" data-parent="#accordion" href="#how-to-build-using-remote-phonegap"><h5 class="panel-title">3) How to build a project with Adobe Phonegap Service?</h5></a></div>';
    $content .= '<div id="how-to-build-using-remote-phonegap" class="panel-collapse collapse">';

    $content .= '<div class="panel-body">';


    $download_output = '<a target="_blank" href="./download.php?download=output&prefix='.$raw_menu['app']['prefix'].'&build=phonegap"><span class="fa fa-download"></span> Output</a>';

    $content .= '<h5 style="text-decoration: underline;">A. Directly Upload</h5>';

    $content .= '<p>Register <a href="https://build.phonegap.com/plans" target="_blank">Adobe ID</a>, '."\r\n";
    $content .= 'Sign up and try uploading your project output ('.$download_output.') to <a href="https://build.phonegap.com/" target="_blank">https://build.phonegap.com/</a> then click <em>Read to Build</em> button.</p>';
    $content .= '<br/>';

    $content .= '<h5 style="text-decoration: underline;">B. Or, via Github</h5>';
    $content .= '<p>Requirements:</p>';
    $content .= '<ol>';
    $content .= '<li>';
    $content .= '<strong>Adobe/Phonegap ID</strong>, Register <a href="https://build.phonegap.com/plans" target="_blank">here</a>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<strong>Git Command</strong>, If you do not have a git, download and install Git Command according your os, <a href="https://git-scm.com/downloads" target="_blank">Git</a> or <a href="https://git-for-windows.github.io/" target="_blank">Git for Window</a>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<strong>Github Account</strong>, Register <a href="https://github.com/join" target="_blank">here</a>'."\r\n";
    $content .= '</li>';
    $content .= '</ol>';
    $content .= '<p>Follow this step:</p>';
    $content .= '<ol>';

    $content .= '<li>';
    $content .= '<p>Create a new repository on <a href="https://github.com/new" target="_blank">GitHub</a> with repository name: <kbd>'.str_replace('_','-',$raw_menu['app']['prefix']).'</kbd></p><br/><img class="thumbnail" src="./templates/default/docs/git.png" width="622" height="515" />'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Run GIT CMD, then clone to local .git</p>'."\r\n";
    $content .= '<pre class="shell">git clone https://github.com/USERNAME/'.str_replace('_','-',$raw_menu['app']['prefix']).'</pre>';
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>Then go to folder your project:</p>'."\r\n";
    $content .= '<pre class="shell">cd '.str_replace('_','-',$raw_menu['app']['prefix']).'</pre>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Copy the output of this project file to current folder and override the files and folders when prompted.</p>'."\r\n";
    if(!file_exists(JSM_PATH.'/output/'.$raw_menu['app']['prefix'].'/resources/icon.png'))
    {
        $content .= '<p><span class="label label-danger">Icon</span> You have not created icon and splash screen, click Extra Menu -&gt; <a target="_blank" href="./?page=x-resources" >Resources</a> for create its.</p>';
    }

    $content .= '<div class="win">';
    $content .= '<h4>Windows</h4>';
    $content .= '<pre class="shell">xcopy /Y /S "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'*"</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="nix osx">';
    $content .= '<h4>Linux/Mac</h4>';
    $content .= '<pre class="shell">yes | cp -rf "'.JSM_PATH.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$raw_menu['app']['prefix'].DIRECTORY_SEPARATOR.'" .</pre>'."\r\n";
    $content .= '</div>';

    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Use config for phonegap by renaming <code>config-phonegap.xml</code> file to <code>config.xml</code></p>';
    $content .= '<div class="win">';
    $content .= '<h4>Windows</h4>';
    $content .= '<pre class="shell">xcopy /Y config-phonegap.xml config.xml</pre>'."\r\n";
    $content .= '</div>';

    $content .= '<div class="nix osx">';
    $content .= '<h4>Linux/Mac</h4>';
    $content .= '<pre class="shell">yes | cp -rf config-phonegap.xml config.xml</pre>'."\r\n";
    $content .= '</div>';
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Modify some files, then add their updated contents to the index:</p>'."\r\n";
    $content .= '<pre class="shell">git add .</pre>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Finally, commit your changes with:</p>'."\r\n";
    $content .= '<pre class="shell">git commit -m "Add existing file"</pre>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Upload, then wait until finish.</p>'."\r\n";
    $content .= '<pre class="shell">git push origin master</pre>'."\r\n";
    $content .= '</li>';


    $content .= '<li>';
    $content .= '<p>Login to <a href="https://build.phonegap.com/" target="_blank">https://build.phonegap.com/</a> with your Adobe ID</p>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Go to Private Tab, paste git repo then click <strong>Pull from git repository</strong>.</p><br/><img class="thumbnail" src="./templates/default/docs/phonegap1.png" width="721" height="269" />'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Then click <strong><strong>Ready to Build</strong></strong></p>'."\r\n";
    $content .= '</li>';

    $content .= '</ol>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';


    // TODO: PUBLISH
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading"><a data-toggle="collapse" data-parent="#accordion" href="#how-to-publish"><h5 class="panel-title">Generating a Signed Release APK File</h5></a></div>';
    $content .= '<div id="how-to-publish" class="panel-collapse collapse">';
    $content .= '<div class="panel-body">';
    $content .= '<ol>';
    $content .= '<li>';
    $content .= '<p>Make sure where current folder now</p>'."\r\n";
    $content .= '<h4>Window</h4>';
    $content .= '<p>Go to drive D:\</p>'."\r\n";
    $content .= '<pre class="shell">d:</pre>'."\r\n";
    $content .= '<p>Open explorer</p>'."\r\n";
    $content .= '<pre class="shell">explorer .</pre>'."\r\n";
    $content .= '<h4>Linux</h4>';
    $content .= '<pre class="shell">sudo su</pre>'."\r\n";
    $content .= '<pre class="shell">pwd</pre>'."\r\n";
    $content .= '<h4>Mac</h4>';
    $content .= '<p>Login as administrator</p>'."\r\n";
    $content .= '<pre class="shell">sudo su</pre>'."\r\n";
    $content .= '<p>Go to <code>Documents</code> folder, or other folder</p>'."\r\n";
    $content .= '<pre class="shell">cd ~/Documents/</pre>'."\r\n";
    $content .= '<p>Open finder</p>'."\r\n";
    $content .= '<pre class="shell">open .</pre>'."\r\n";
    $content .= '</li>';
    $content .= '<li>';
    $content .= '<p>Then go to folder your project:</p>'."\r\n";
    $content .= '<pre class="shell">cd '.$raw_menu['app']['prefix'].'</pre>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Let\'s generate our private key using the keytool command that comes with the JDK (For app already exist in playstore, you can use old key file rename to `'.$raw_menu['app']['prefix'].'.keystore`).</p>'."\r\n";
    $content .= '<pre class="shell">keytool -genkey -v -keystore '.$raw_menu['app']['prefix'].'.keystore -alias '.$raw_menu['app']['prefix'].' -keyalg RSA -keysize 2048 -validity 10000</pre>'."\r\n";
    $content .= '<blockquote class="blockquote blockquote-danger">You can upload <code>'.$raw_menu['app']['prefix'].'.keystore</code> file to Adobe Phonegap Online or continue this step for build apk in local machine, and don\'t forget backup this file.</blockquote>';
    $content .= '<hr/>';
    $content .= '</li>';


    $content .= '<li>';
    $content .= '<p>Build android apk with option release</p>'."\r\n";
    $content .= '<pre class="shell">cordova build android --release</pre>'."\r\n";
    $content .= '<p>we can find our unsigned APK file in <kbd>platforms/android/app/build/outputs/apk/release/app-release-unsigned.apk</kbd></p>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>Copying and rename APK filename</p>'."\r\n";
    $content .= '<h4>Window</h4><pre class="shell">echo f | xcopy /F /Y "platforms/android/app/build/outputs/apk/release/app-release-unsigned.apk" "platforms/android/app/build/outputs/apk/release/app-release.apk"</pre>'."\r\n";
    $content .= '<h4>Linux/Mac</h4><pre class="shell">cp "platforms/android/app/build/outputs/apk/release/app-release-unsigned.apk" "platforms/android/app/build/outputs/apk/release/app-release.apk"</pre>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>To sign the unsigned APK, run the jarsigner tool which is also included in the JDK</p>'."\r\n";
    $content .= '<pre class="shell">jarsigner -verbose -sigalg SHA1withRSA -digestalg SHA1 -keystore "'.$raw_menu['app']['prefix'].'.keystore" "platforms/android/app/build/outputs/apk/release/app-release.apk" '.$raw_menu['app']['prefix'].'</pre>'."\r\n";
    $content .= '</li>';

    $content .= '<li>';
    $content .= '<p>we need to run the zip align tool to optimize the APK</p>'."\r\n";
    $content .= '<pre class="shell">zipalign -v 4 "platforms/android/app/build/outputs/apk/release/app-release.apk" '.$raw_menu['app']['prefix'].'.apk</pre>'."\r\n";
    $content .= '<br/>';

    $content .= '<p>If not work, check build tools version available:</p>'."\r\n";
    $content .= '<h4>Window</h4><pre class="shell">dir "%appdata%/../local/android/sdk/build-tools/"</pre>'."\r\n";
    $content .= '<h4>OSX</h4><pre class="shell">ls ~/Library/Android/sdk/build-tools/</pre>'."\r\n";

    $content .= '<p>Run zipalign again:</p>'."\r\n";
    $content .= '<h4>Window</h4><pre class="shell">"%appdata%/../local/android/sdk/build-tools/[build-tools-version]/zipalign" -v 4 "platforms/android/app/build/outputs/apk/release/app-release.apk" '.$raw_menu['app']['prefix'].'.apk</pre>'."\r\n";
    $content .= 'Example:<pre class="shell">"%appdata%/../local/android/sdk/build-tools/26.0.2/zipalign" -v 4 "platforms/android/app/build/outputs/apk/release/app-release.apk" '.$raw_menu['app']['prefix'].'.apk</pre>'."\r\n";

    $content .= '<h4>OSX</h4><pre class="shell">~/Library/Android/sdk/build-tools/[build-tools-version]/zipalign -v 4 "platforms/android/app/build/outputs/apk/release/app-release.apk" '.$raw_menu['app']['prefix'].'.apk</pre>'."\r\n";
    $content .= 'Example:<pre class="shell">~/Library/Android/sdk/build-tools/26.0.2/zipalign -v 4 "platforms/android/app/build/outputs/apk/release/app-release.apk" '.$raw_menu['app']['prefix'].'.apk</pre>'."\r\n";


    $content .= '</li>';
    $content .= '</ol>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';

    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading">';
    $content .= '<h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#set-androidsdk">How to Install AndroidSDK?</a></h5>';
    $content .= '</div>';
    $content .= '<div id="set-androidsdk" class="panel-collapse collapse">';
    $content .= '<div class="panel-body">';
    $content .= '<p>Before install everything, please install <a target="_blank" href="http://www.oracle.com/technetwork/java/javase/downloads/index.html">Java Platform (JDK) 1.8.xx</a></p>';
    $content .= '<p>For install SDK tools package follow this process:</p>';
    $content .= '<ul>';
    $content .= '<li>Download <code>sdk_tools_xxx_xxx.zip</code> from <a href="https://developer.android.com/studio/index.html#downloads" target="_blank">https://developer.android.com/studio/index.html</a></li>';
    $content .= '<li>Create new folder to hold all of the other Android SDK packages for instance "AndroidSDK"</li>';
    $content .= '<li>Extract the sdk_tools_xxx_xxx.zip inside the AndroidSDK Folder</li>';

    $content .= '<li>run terminal/nodejs shell, type this command:</li>';
    $content .= '<p>Uninstall old platforms</p>';
    $content .= '<pre class="shell">';
    $content .= 'sdkmanager --uninstall "platforms;android-23" "platforms;android-24" "platforms;android-25"'."\r\n";
    $content .= '</pre>';

    $content .= '<p>Then install latest platforms</p>';
    $content .= '<pre class="shell">';
    $content .= 'sdkmanager "platform-tools" "platforms;android-26" "platforms;android-27" "platforms;android-28" "emulator" "tools" "build-tools;28.0.2"'."\r\n";
    $content .= '</pre>';
    $content .= '<p>Then for admobpro, you need install extra packages</p>';
    $content .= '<pre class="shell">';
    $content .= 'sdkmanager "extras;google;google_play_services" "extras;google;m2repository"'."\r\n";
    $content .= '</pre>';

    $content .= '</li>';

    $content .= '</ul>';
    $content .= '<p>or you can also install Android Studio package by download "android-studio-bundle-xxxx-xxxx", then test create new project.</p>';
    //$content .= '<img class="img-thumbnail" src="./templates/default/img/new-cordova-system-requirements.png"  />';
    $content .= '</div>'."\r\n";
    $content .= '</div>'."\r\n";
    $content .= '</div>'."\r\n";


    // TODO: HOWTO SET ENV
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading">';
    $content .= '<h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#set-environ">How to set Environment?</a></h5>';
    $content .= '</div>';
    $content .= '<div id="set-environ" class="panel-collapse collapse">';
    $content .= '<div class="panel-body">';
    $content .= '<p>Checking environment variables example run this command in terminal/shell or cmd</p>';
    $content .= '<pre class="shell">';
    $content .= 'aapt'."\r\n";
    $content .= '</pre>';
    $content .= '<pre class="shell">';
    $content .= 'keytool'."\r\n";
    $content .= '</pre>';
    $content .= '<pre class="shell">';
    $content .= 'jarsigner'."\r\n";
    $content .= '</pre>';
    $content .= '<pre class="shell">';
    $content .= 'zipalign'."\r\n";
    $content .= '</pre>';
    $content .= '<p>if you get message: ... is not recognized as an internal or external command or command not found</p>';

    $content .= '<h4>Not Permanent</h4>';
    $content .= '<p>You need run this command before build project</p>';
    $content .= '<h5>Linux and OSX</h5>';
    $content .= '<pre class="shell">';
    $content .= 'export ANDROID_HOME=<span class="text-danger">/Users/YourName/Desktop/android-sdk-macosx</span>'."\r\n";
    $content .= '</pre>';
    $content .= '<pre class="shell">';
    $content .= 'export PATH=${PATH}:$ANDROID_HOME/tools:$ANDROID_HOME/platform-tools:$ANDROID_HOME/build-tools/<span class="text-danger">24.0.1</span>'."\r\n";
    $content .= '</pre>';
    $content .= '<h5>Windows</h5>';
    $content .= '<pre class="shell">';
    $content .= 'SET "ANDROID_HOME=<span class="text-danger">D:\\Android\\sdk\\</span>"'."\r\n";
    $content .= '</pre>';
    $content .= '<pre class="shell">';
    $content .= 'SET "JAVA_HOME=<span class="text-danger">C:\\Program Files\\Java\\jdk1.8.0_65\\</span>"'."\r\n";
    $content .= '</pre>';
    $content .= '<pre class="shell">';
    $content .= 'SET "PATH=%PATH%;%JAVA_HOME%\\bin;%ANDROID_HOME%;%ANDROID_HOME%\\platform-tools;%ANDROID_HOME%\\build-tools\\<span class="text-danger">24.0.1</span>"'."\r\n";
    $content .= '</pre>';
    $content .= '<p><span class="label label-danger">Note</span> change red color according your computer.</p>';
    $content .= '<h4>Permanent</h4>';
    $content .= '<h5>Windows</h5>';
    $content .= '<ul>';
    $content .= '<li>Go to Control Panel &gt; System &gt; Advanced System Setting</li>';
    $content .= '<li>Click Tab Advance &gt; Environment Variables Button</li>';
    $content .= '<li>Add new environ for JAVA_HOME and ANDROID_HOME<br/><img src="./templates/default/docs/env1.png" width="357" height="153" /></li>';
    $content .= '<li>And also edit environ for PATH<br/>
<img src="./templates/default/docs/env2.png" width="357" height="153" /><br/>
Don\'t remove old value, just to append text with separator<strong>;</strong>.
<pre>C:\\Program Files\\Java\\jdk1.8.0_65\\bin\;D:\\Android\\sdk\\;D:\\Android\\sdk\\platform-tools\\;</pre>
</li>';
    $content .= '</ul>'."\r\n";
    $content .= '<h5>Linux and OSX</h5>'."\r\n";
    $content .= '<pre>'."\r\n";
    $content .= 'export ANDROID_HOME=<span class="text-danger">/Users/YourName/Desktop/android-sdk-macosx</span>'."\r\n";
    $content .= 'export PATH=${PATH}:$ANDROID_HOME/tools:$ANDROID_HOME/platform-tools:$ANDROID_HOME/build-tools/<span class="text-danger">24.0.1</span>'."\r\n";
    $content .= '</pre>'."\r\n";
    $content .= '<p>You can edit and pasting environ code in file:</p>'."\r\n";
    $content .= '<pre>'."\r\n";
    $content .= '~/.profile'."\r\n";
    $content .= '~/.bash_profile';
    $content .= '</pre>'."\r\n";
    $content .= 'path relative according your os.'."\r\n";
    $content .= '</div>'."\r\n";
    $content .= '</div>'."\r\n";
    $content .= '</div>'."\r\n";
    $content .= '<!-- ./set environ -->'."\r\n";


    $content .= '</div>';
    $content .= '</div>';


}
$content .= '</div>';


$footer .= '
<script>
$(".delete-this-project").on("click",function(e){
    var notice = "" ; 
    notice += "This action cannot be restored again! \\r\\nAre you sure you want to delete this project?"  ;
    return confirm(notice);
});

$(".clone-btn").on("click",function(e){
    var notice = "Do you want to clone project to current project, \\r\\nits\' mean current project will be overwritten. Are you sure?\\r\\n\\r\\nClone not able for fix link, so please save everything again." ; 
     return confirm(notice);
});

$(document).ready(function(){
  
    checkOS();
    $(".what_os").click(function(){
        checkOS();
    });
    
    function checkOS(){
        $(".win").attr("style","display:none");
        $(".osx").attr("style","display:none");
        $(".nix").attr("style","display:none");
        $(".what_os").each(function(){
            var os_name = $(this).val();
            var is_true = false;
            if( $(this).is(":checked") ){
                is_true = true;
                $("."+os_name).attr("style","display:block");
            };
            console.log(os_name,is_true);
        });   
    }  
    
    $("#app_company_,#app_name_").keyup(function() {
        var value = $(this).val();
        
        if(value[0]=="0"){
            alert( "Ops, disallow numeric on 1st characters");
            $(this).val("Zero ");
        }
        
        if(value[0]=="1"){
            alert( "Ops, disallow numeric on 1st characters");
            $(this).val("One ");
        }
        
        if(value[0]=="2"){
            alert( "Ops, disallow numeric on 1st characters");
            $(this).val("Two ");
        }
        
        if(value[0]=="3"){
            alert( "Ops, disallow numeric on 1st characters");
            $(this).val("Three ");
        }

        if(value[0]=="4"){
            alert( "Ops, disallow numeric on 1st characters");
            $(this).val("Four ");
        }
        
        if(value[0]=="5"){
            alert( "Ops, disallow numeric on 1st characters");
            $(this).val("Five ");
        }
        
        if(value[0]=="6"){
            alert( "Ops, disallow numeric on 1st characters");
            $(this).val("Six ");
        }
        
        if(value[0]=="7"){
            alert( "Ops, disallow numeric on 1st characters");
            $(this).val("Seven ");
        }
        
        if(value[0]=="8"){
            alert( "Ops, disallow numeric on 1st characters");
            $(this).val("Eight ");
        }
        
        if(value[0]=="9"){
            alert( "Ops, disallow numeric on 1st characters");
            $(this).val("Nine ");
        }
        
    });
    
});



</script>
';


$template->demo_url = 'output/'.$file_name.'/www/';
$template->title = $template->base_title.' | '.'Dashboard';
$template->base_desc = 'Dashboard';
$template->content = $content;
$template->emulator = false;
$template->footer = $footer;

?>