<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2018
 * @license Commercial License
 * 
 * @package Ihsana Mobile App Builder
 * 
 */


session_start();

set_time_limit(0);
ini_set('memory_limit','512M');
ini_set('max_execution_time',0);
ini_set('upload_max_filesize','5M');
ini_set('post_max_size','8M');
ini_set('max_input_time','60');
ini_set('safe_mode','off');
ini_set('max_input_vars','1000');
//ini_set('session.use_cookies', '1');


define('JSM_EXEC',true);
define('JSM_PACKAGE_NAME','com.imabuilder');
define('JSM_ANDROID_MINSDK','19'); //16 for android7.0.0 or 19 for ^7.1.1
define('JSM_VERSION','18.12.10');

define('JSM_FILEBROWSER','elfinder'); //support: elfinder, kcfinder
define('JSM_NEW_FEATURES','page,cordova-plugin-admob-free,cordova-plugin-admob-pro');

define('JSM_AUTH',false);
define('JSM_USERNAME','admin'); // your username
define('JSM_PASSWORD','admin'); // your password

define('JSM_CDN',false);
define('JSM_DEMO',false);
define('JSM_PATH',dirname(__file__));

define('JSM_THEME_CODEMIRROR','dracula'); // https://codemirror.net/demo/theme.html
define('JSM_EXPERT_CUSTOM',true); //enable custom code for expert user

header("cache-control: private, max-age=0, no-cache, no-store");
header("pragma: no-cache");

foreach(glob(JSM_PATH."/system/lang/*.php") as $file_lang)
{
    $var_lang = pathinfo(basename($file_lang),PATHINFO_FILENAME);
    $list_locales[$var_lang] = array('label' => $var_lang,'value' => $var_lang);
}

$list_locales['en'] = array('label' => 'English','value' => 'en');
$list_locales['id'] = array('label' => 'Bahasa Indonesia','value' => 'id');
$list_locales['tr'] = array('label' => 'Turkish','value' => 'tr');
$list_locales['pt-br'] = array('label' => 'Portuguese-Brazil','value' => 'pt-br');
$list_locales['ru'] = array('label' => 'Russian','value' => 'ru');

$translator['name'] = 'Ihsana Team';
$translator['url'] = 'http://ihsana.com';

if(($_SERVER["HTTP_HOST"] == 'ionic.co.id'))
{
    define('JSM_DEBUG',true);
    define('JSM_DEBUG_FOLDER','D:\xampp\htdocs\wwwroot\debug.co.id\public_html\\');
} else
{
    define('JSM_DEBUG',false);
    define('JSM_DEBUG_FOLDER','');
}

$_SESSION["JSM_DEMO"] = JSM_DEMO;
if(!isset($_SESSION['JSM_LANG']))
{
    $_SESSION['JSM_LANG'] = 'en';
}

if(isset($_GET['locale']))
{
    $_SESSION['JSM_LANG'] = $_GET['locale'];
    header("Location: ".$_SERVER["HTTP_REFERER"].'#');
}

define('JSM_LANG',$_SESSION['JSM_LANG']);

foreach(glob(JSM_PATH."/system/lang/*.php") as $filename)
{
    if(file_exists($filename))
    {
        require_once ($filename);
    }
}


$_text_langs = array();
function __($str)
{
    global $__;
    global $_text_langs;

    if(JSM_LANG == 'tr')
    {
        iconv("ISO-8859-1","UTF-8",$str);
    }

    $_text_langs[sha1($str)] = $str;

    if(isset($__[JSM_LANG][sha1($str)]))
    {
        if($__[JSM_LANG][sha1($str)] == '')
        {
            return '{'.$str.'}';
        } else
        {
            return ''.$__[JSM_LANG][sha1($str)].'';
        }

    } else
    {
        return '{'.$str.'}';
    }
}


if(!isset($_GET['page']))
{
    $_GET['page'] = null;
}
if(!isset($_GET['act']))
{
    $_GET['act'] = null;
}

if(JSM_AUTH == true)
{
    if($_GET['page'] === 'o-auth')
    {
        if(isset($_POST['uname']))
        {
            if((JSM_USERNAME == $_POST['uname']) && (JSM_PASSWORD == $_POST['pwd']))
            {
                $_SESSION['is_login'] = true;
                header('Location: ?');
            } else
            {
                $_SESSION['is_login'] = false;

            }
        }
    }
    if($_GET['page'] !== 'o-auth')
    {
        if($_SESSION['is_login'] == false)
        {
            header('Location: ./?page=o-auth&login');
        }
    }
}

ob_start("ob_gzhandler");


if(JSM_DEBUG == false)
{
    error_reporting(0);
    //ob_start("ob_gzhandler");
    ob_start();
}

if(file_exists(JSM_PATH."/projects/config.php"))
{
    require_once (JSM_PATH."/projects/config.php");
} else
{
    //header("Location: ./setup.php");
}

if(!isset($_SESSION['FILE_NAME']))
{
    $_SESSION['FILE_NAME'] = null;
}
if(!isset($_SESSION['PROJECT']))
{
    $_SESSION['PROJECT'] = array();
} else
{

}

if(!isset($_SESSION['PROJECT']['mod']))
{
    $_SESSION['PROJECT']['mod'] = array();
}

if(!isset($_SERVER["HTTP_REFERER"]))
{
    $_SERVER["HTTP_REFERER"] = '';
}

if(!isset($_SESSION['GUIDES']))
{
    $_SESSION['GUIDES'] = false;
}

if(isset($_GET['guides']))
{
    if($_GET['guides'] == 'off')
    {
        $_SESSION['GUIDES'] = false;
    } else
    {
        $_SESSION['GUIDES'] = true;
    }
}

if(JSM_DEMO == true)
{
    if(!isset($_GET['act']))
    {
        $_GET['act'] = '';
    }
    unset($_FILES);
    unset($_POST);
    unset($_GET['delete']);
    unset($_GET['push']);

    if($_GET['act'] == "trash")
    {
        $_GET['act'] = null;
    }

    if($_GET['act'] == "create")
    {
        $_GET['act'] = null;
    }
}


if(!isset($_GET['lic']))
{
    $_GET['lic'] = null;
}

if($_GET['lic'] == 'reset')
{
    @unlink(JSM_PATH."/projects/config.php");
    if(file_exists("./system/class/jsmIonic.php"))
    {
        $update = file_get_contents("./system/class/jsmIonic.php");
        $code = explode("JSM_ACTIVATION_CODE",$update);
        @file_put_contents('./system/class/jsmIonic.php',$code[0]."JSM_ACTIVATION_CODE **/ \r\n\r\n?>");
    }

}


if(!isset($_SESSION['PROJECT']['tables']))
{
    $_SESSION['PROJECT']['tables'] = array();
}
if(file_exists(JSM_PATH."/system/class/jsmLocale.php"))
{
    require_once (JSM_PATH."/system/class/jsmLocale.php");
} else
{
    die("error: class jsmLocale");
}

if(file_exists(JSM_PATH."/system/jsmFunction.php"))
{
    require_once (JSM_PATH."/system/jsmFunction.php");
} else
{
    die("error: function");
}


if(!isset($_SESSION['TIME']))
{
    $_SESSION['TIME'] = time();
}
$_SESSION['LONGTIME'] = time() - $_SESSION['TIME'];

if(file_exists(JSM_PATH."/system/class/jsmTemplate.php"))
{
    require_once (JSM_PATH."/system/class/jsmTemplate.php");
} else
{
    die("error: class templates");
}

if(file_exists(JSM_PATH."/system/class/jsmString.php"))
{
    require_once (JSM_PATH."/system/class/jsmString.php");
} else
{
    die("error: class jsmString");
}


if(file_exists(JSM_PATH."/system/class/jsmBootstrap.php"))
{
    require_once (JSM_PATH."/system/class/jsmBootstrap.php");
} else
{
    die("error: class bootstrap");
}

if(file_exists(JSM_PATH."/system/class/jsmCountry.php"))
{
    require_once (JSM_PATH."/system/class/jsmCountry.php");
} else
{
    die("error: class country");
}

if(file_exists(JSM_PATH."/system/class/jsmIonic.php"))
{
    define("JSM_IONIC_CLASS",JSM_PATH."/system/class/jsmIonic.php");
    require_once (JSM_PATH."/system/class/jsmIonic.php");
} else
{
    die("error: class ionic");
}


if(file_exists(JSM_PATH."/system/class/jsmIonicon.php"))
{
    require_once (JSM_PATH."/system/class/jsmIonicon.php");
} else
{
    die("error: class ionicon");
}
$guides = null;
$config = $page = null;
$template = new jsmTemplate();
$template->filename(JSM_PATH.'/templates/default/default.php');

$template->sidebar = null;

$template->sidebar .= '<div class="main-navbar navbar navbar-default navbar-static-top" role="navigation">';
$template->sidebar .= '<div class="container-fluid">';

$template->sidebar .= '
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>';

$template->sidebar .= '<div class="navbar-collapse collapse">';

$template->sidebar .= '<ul class="nav navbar-nav">';

if(!isset($_GET['page']))
{
    $_GET['page'] = 'dashboard';
}
$extra_tool = $backend_tool = $helper_tool = null;


$extra_tool .= '<li><a href="./system/plugin/'.JSM_FILEBROWSER.'/browse.php?type=images" target="_blank" id="image-browser" >Image Browser</a></li>';
$extra_tool .= '<li><a href="./system/plugin/'.JSM_FILEBROWSER.'/browse.php?type=file" target="_blank" id="file-browser" >File Browser</a></li>';
$extra_tool .= '<li class="divider"></li>';

$package_name = '';
if(isset($_SESSION['FILE_NAME']))
{
    $package_name = '&app_id='.$_SESSION['FILE_NAME'];
    if(JSM_PACKAGE_NAME != 'com.imabuilder')
    {
        $package_name = '&app_id='.JSM_PACKAGE_NAME.'.'.$_SESSION['FILE_NAME'].'';
    }
}
foreach(glob(JSM_PATH."/system/includes/*.php") as $filename)
{
    $active = null;
    if(isset($_GET['page']))
    {
        $active = null;
        $page = basename($_GET['page']);
        if($page == pathinfo($filename,PATHINFO_FILENAME))
        {
            $active = 'active';
        }
    }

    $prefix = substr(pathinfo($filename,PATHINFO_FILENAME),0,2);

    if($prefix == 'x-')
    {
        $name = substr(pathinfo($filename,PATHINFO_FILENAME),2,strlen(pathinfo($filename,PATHINFO_FILENAME)));
        $extra_tool .= '<li class="'.$active.'"><a href="./?page='.pathinfo($filename,PATHINFO_FILENAME).''.$package_name.'" id="'.strtolower(str_replace('.','-',$name)).'" >'.ucwords(str_replace('-',' ',$name)).'</a></li>';
    } elseif($prefix == 'z-')
    {
        $name = substr(pathinfo($filename,PATHINFO_FILENAME),2,strlen(pathinfo($filename,PATHINFO_FILENAME)));
        $backend_tool .= '<li class="'.$active.'"><a href="./?page='.pathinfo($filename,PATHINFO_FILENAME).''.$package_name.'" id="'.strtolower(str_replace('.','-',$name)).'" >'.ucwords(str_replace('-',' ',$name)).'</a></li>';
    } elseif($prefix == 'h-')
    {
        $name = substr(pathinfo($filename,PATHINFO_FILENAME),2,strlen(pathinfo($filename,PATHINFO_FILENAME)));
        $helper_tool .= '<li class="'.$active.'"><a href="./?page='.pathinfo($filename,PATHINFO_FILENAME).''.$package_name.'" id="'.strtolower(str_replace('.','-',$name)).'"  >'.ucwords(str_replace('-',' ',$name)).'</a></li>';
    } elseif($prefix == 'o-')
    {

    } else
    {
        $template->sidebar .= '<li class="'.$active.'"><a href="./?page='.pathinfo($filename,PATHINFO_FILENAME).''.$package_name.'" id="'.strtolower(str_replace('.php','',basename($filename))).'"  >'.ucwords(str_replace('-',' ',pathinfo($filename,PATHINFO_FILENAME))).'&nbsp;</a></li>';
    }
}

$template->sidebar .= '<li><a id="page-builder" href="./?page=x-page-builder" >Page Builder&nbsp;</a></li>';

$template->sidebar .= '
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Extra Menus 
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">'.$extra_tool.'</ul>
                        </li>
                        ';
$template->sidebar .= '
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Back-End Tools
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">'.$backend_tool.'</ul>
                        </li>
                        ';
$template->sidebar .= '
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Helper Tools
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">'.$helper_tool.'</ul>
                        </li>
                        ';
if(JSM_AUTH == true)
{
    $template->sidebar .= '
                        <li class="dropdown">
                            <a href="./?page=o-auth&logout">Logout</a>
                        </li>
                        ';
}
$template->sidebar .= '</ul>';

$template->sidebar .= '<ul class="nav navbar-nav navbar-right">';
$template->sidebar .= '<li><a href="https://www.howtogeek.com/269265/how-to-enable-private-browsing-on-any-web-browser/"><span id="browser_mode"></span></a></li>';

if(isset($_SESSION['PROJECT']['app']))
{

    if($_SESSION['GUIDES'] == true)
    {
        $template->sidebar .= '<li><a href="./?guides=off"> <span class="label label-primary"><span class="fa fa-check-circle-o"></span> GUIDES</span></a></li>';
    } else
    {
        $template->sidebar .= '<li><a href="./?guides=on"> <span class="label label-danger"><span class="fa fa-circle-o"></span> GUIDES</span></a></li>';
    }
    $template->sidebar .= '
    
    
    <li>
    <a class="btn btn-danger" href="./?page=dashboard&active='.$_SESSION['PROJECT']['app']['prefix'].'"><span class="label label-danger">'.strtoupper(strtolower($_SESSION['PROJECT']['app']['name'])).'</span><br/></a>
    </li>
    
    
    
    ';

}

$debug_locale = null;
if(JSM_DEBUG == true)
{
    $debug_locale = '<li><a href="./?locale=xx">Debugger (XX)</a></li>';
}


$list_locale_html = null;
foreach($list_locales as $list_locale)
{
    $list_locale_html .= '<li><a href="./?locale='.strtolower($list_locale['value']).'">'.($list_locale['label']).' ('.strtolower($list_locale['value']).')</a></li>';
}
$template->sidebar .= '
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.strtoupper($_SESSION['JSM_LANG']).'</a>
        <ul class="dropdown-menu">
            '.$list_locale_html.'
            '.$debug_locale.'
        </ul>     
    </li>';
$template->sidebar .= '</ul>';

$template->sidebar .= '</div>';
$template->sidebar .= '</div>';
$template->sidebar .= '</div>';

$template->base_url = './';
$template->base_title = 'IMA BuildeRz v18';
$template->title = '';
$template->base_desc = '';
$template->base_url = '';
$template->content = '';
$template->footer = '';
$template->page_guide = null;

$filename = JSM_PATH."/system/includes/dashboard.php";
if(isset($_GET['page']))
{
    $page = basename($_GET['page']);
    if(file_exists(JSM_PATH."/system/includes/".$page.".php"))
    {
        $filename = JSM_PATH."/system/includes/".$page.".php";
    }
}


require_once ($filename);
if(JSM_DEBUG == false)
{
    if(filesize(JSM_IONIC_CLASS) != 640000)
    {
        //header("Location: ./setup.php");
    }
}

if(!isset($translator[$_SESSION['JSM_LANG']]["name"]))
{
    $translator[$_SESSION['JSM_LANG']]["name"] = 'Unknow';
}
if(!isset($translator[$_SESSION['JSM_LANG']]["url"]))
{
    $translator[$_SESSION['JSM_LANG']]["url"] = '';
}

$translator["name"] = $translator[$_SESSION['JSM_LANG']]["name"];
$translator["url"] = $translator[$_SESSION['JSM_LANG']]["url"];

$template->translator = $translator;
$template->display();
if(JSM_DEBUG == true)
{
    if(file_exists(JSM_PATH."/../locale.php"))
    {
        //require_once JSM_PATH."/../locale.php";
    }
}

?>