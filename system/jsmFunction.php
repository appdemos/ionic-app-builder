<?php

if(!defined('JSM_EXEC'))
{
    die(':)');
}

//update
if(file_exists('system/includes/x-admob-pro.php'))
{
    @unlink('system/includes/x-admob-pro.php');
}


// remove old version
if(file_exists('system/includes/page-builder/online_radio_player.templates.php'))
{
    @unlink('system/includes/page-builder/online_radio_player.templates.php');
}
if(file_exists('system/includes/page-builder/online_radio_player.templates.php.disable'))
{
    @unlink('system/includes/page-builder/online_radio_player.templates.php.disable');
}

if(file_exists('system/includes/page-builder/page_cryptocurrencies.templates.php'))
{
    @unlink('system/includes/page-builder/page_cryptocurrencies.templates.php');
}
if(file_exists('system/includes/page-builder/page_cryptocurrencies.templates.php.disable'))
{
    @unlink('system/includes/page-builder/page_cryptocurrencies.templates.php.disable');
}

if(file_exists('system/includes/page-builder/page_menu_slidding.templates.php'))
{
    @unlink('system/includes/page-builder/page_menu_slidding.templates.php');
}
if(file_exists('system/includes/page-builder/page_menu_slidding.templates.php.disable'))
{
    @unlink('system/includes/page-builder/page_menu_slidding.templates.php.disable');
}


if(file_exists('output/ionic.config.json'))
{
    unlink('output/ionic.config.json');
}
if(file_exists('output/ionic.project'))
{
    unlink('output/ionic.project');
}
if(file_exists('output/config.xml'))
{
    unlink('output/config.xml');
}
if(file_exists('output/gui_cordova.ini'))
{
    unlink('output/gui_cordova.ini');
}

if(isset($_SESSION['FILE_NAME']))
{
    if(!isset($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable']))
    {
        $_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] = false;
    }

    if(!file_exists('projects/'.$_SESSION['FILE_NAME'].'/mod.cordova-plugin-dialogs.json'))
    {
        $mod = null;
        $mod['mod']['dialogs']['name'] = 'cordova-plugin-dialogs';
        $mod['mod']['dialogs']['engines'] = 'cordova';
        $mod['mod']['dialogs']['info'] = 'required by IMA BuildeRz Core';
        file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/mod.cordova-plugin-dialogs.json',json_encode($mod));
    }


    if(!file_exists('projects/'.$_SESSION['FILE_NAME']))
    {
        @mkdir('projects/'.$_SESSION['FILE_NAME'],0777,true);
    }
    unset($_SESSION['PROJECT']['tables']['custom']);

    foreach(glob("system/includes/example-tables/*.json") as $templ_file)
    {
        $prefix_table_temp = pathinfo($templ_file,PATHINFO_FILENAME);
        if(isset($_SESSION['PROJECT']['tables'][$prefix_table_temp]))
        {
            unset($_SESSION['PROJECT']['tables'][$prefix_table_temp]);
        }
    }

    @unlink('projects/translation.json');
    @unlink('projects/'.$_SESSION['FILE_NAME'].'/page_builder.page_wordpress..json');
    @unlink('projects/'.$_SESSION['FILE_NAME'].'/tables..json');
    @unlink('projects/'.$_SESSION['FILE_NAME'].'/page..json');
    @unlink('projects/'.$_SESSION['FILE_NAME'].'/page._singles.json');
    @unlink('output/'.$_SESSION['FILE_NAME'].'/www/translations/.json');
    @unlink('output/README.md');
    @unlink('output/LICENSE');
    @unlink('output/config-phonegap.xml');
    @unlink('output/config-ionic.xml');


    $_get_language['translation'] = null;
    $z = 0;

    $get_language = new jsmLocale();


    foreach(glob('output/'.$_SESSION['FILE_NAME'].'/www/translations/*.json') as $lang)
    {
        $tld = str_replace('.json','',basename($lang));
        $_get_language['translation']['lang'][$z]['prefix'] = $tld;
        $_get_language['translation']['lang'][$z]['label'] = $get_language->getLabel($tld);
        $z++;
    }


    $_SESSION['PROJECT']['translation'] = $_get_language['translation'];
    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/translation.json',json_encode($_get_language));
    // TODO: MERGE BOOKMARKS
    $bookmark_path = 'projects/'.$_SESSION['FILE_NAME'].'/page.bookmarks.json';
    $bookmark_available = false;
    $_content_bookmarks = null;
    $_content_bookmarks .= '<!-- this page not allowed for edit -->'."\r\n";
    $_content_bookmarks .= '<div class="list padding">'."\r\n";

    if(!isset($_SESSION['PROJECT']['page']))
    {
        $_SESSION['PROJECT']['page'] = array();
    }
    if(!is_array($_SESSION['PROJECT']['page']))
    {
        $_SESSION['PROJECT']['page'] = array();
    }


    if(isset($_SESSION['PROJECT']['menu']))
    {

        foreach($_SESSION['PROJECT']['page'] as $_bookmark_page)
        {
            if($_bookmark_page['for'] == 'table-bookmarks')
            {
                $_content_bookmarks .= "\t".'<a class="item item-icon-left" href="#/'.$_SESSION['FILE_NAME'].'/'.$_bookmark_page['prefix'].'">'."\r\n";
                $_content_bookmarks .= "\t\t".'<i class="icon colorful ion-ios-bookmarks"></i>'."\r\n";
                $_content_bookmarks .= "\t\t".$_bookmark_page['title'].''."\r\n";
                $_content_bookmarks .= "\t".'</a>'."\r\n";
                $bookmark_available = true;
            }
        }
        $_content_bookmarks .= '</div>'."\r\n";

        $bookmarks_page = null;
        $bookmarks_page['page'][0] = array(
            'content' => $_content_bookmarks,
            'title' => 'Bookmarks',
            'prefix' => 'bookmarks',
            'parent' => '',
            'menutype' => $_SESSION['PROJECT']['menu']['type'].'-custom',
            'lock' => true,
            'js' => '$ionicConfig.backButton.text("");',
            'version' => 'Upd.'.date('ymdhi'),
            'menu' => '',
            'for' => '-',
            'priority' => 'low',
            'last_edit_by' => 'menu');

        if($bookmark_available == true)
        {
            @file_put_contents($bookmark_path,json_encode($bookmarks_page));
        } else
        {
            if(file_exists($bookmark_path))
            {
                @unlink($bookmark_path);
            }
        }
    }
    // ===========================================================

    if(!file_exists('output/'.$_SESSION['FILE_NAME']))
    {
        @mkdir('output/'.$_SESSION['FILE_NAME'],0777,true);
    }

    if(!file_exists('output/'.$_SESSION['FILE_NAME'].'/www/'))
    {
        @mkdir('output/'.$_SESSION['FILE_NAME'].'/www/',0777,true);
    }

    if(!file_exists('output/'.$_SESSION['FILE_NAME'].'/.trash'))
    {
        @mkdir('output/'.$_SESSION['FILE_NAME'].'/.trash',0777,true);
    }


    if(!file_exists('projects/'.$_SESSION['FILE_NAME'].'/tables'))
    {
        @mkdir('projects/'.$_SESSION['FILE_NAME'].'/tables',0777,true);
    }

    if(!file_exists('output/'.$_SESSION['FILE_NAME'].'/www/index.html'))
    {
        //header('Location: ./?page=dashboard&reset=true');
    }
    if(!isset($_SESSION['PROJECT']['app']['locale']))
    {
        $_SESSION['PROJECT']['app']['locale'] = 'en-us';
    }
    if($_SESSION['PROJECT']['app']['locale'] == '')
    {
        $_SESSION['PROJECT']['app']['locale'] = 'en-us';
    }
}

function GuideMarkup($guides)
{
    $page_guide = null;
    if($_SESSION['GUIDES'] == true)
    {
        if(is_array($guides))
        {
            $guide_lists = '<ul id="guide" data-tourtitle="Open Page Guide for help">';
            foreach($guides as $guide)
            {
                $guide_lists .= '<li class="tlypageguide_'.$guide['pos'].'" data-tourtarget="'.$guide['target'].'">'.$guide['text'].'</li>';
            }
            $guide_lists .= '</ul>';

            $page_guide = '
                    <div style="display:none">
                        '.$guide_lists.'            
                        <div class="tlyPageGuideWelcome">
                            <p>Welcome to guides! guide is here to help you learn more.</p>
                            <button class="tlypageguide_start">let\'s go</button>
                            <button class="tlypageguide_ignore">not now</button>
                            <button class="tlypageguide_dismiss">got it, thanks</button>
                        </div>
                    </div>
                    ';
        }
    }
    return $page_guide;
}

function sample_data($type)
{
    $return = null;
    switch($type)
    {
        case 'text':
            $lipsum = new LoremIpsum();
            $return = $lipsum->words(10);
            break;
        case 'paragraph':
            $lipsum = new LoremIpsum();
            $return = $lipsum->words(20);
            break;
        case 'heading-1':
            $lipsum = new LoremIpsum();
            $return = ucwords($lipsum->words(2));
            break;
        case 'heading-2':
            $lipsum = new LoremIpsum();
            $return = ucwords($lipsum->words(2));
            break;
        case 'heading-3':
            $lipsum = new LoremIpsum();
            $return = ucwords($lipsum->words(2));
            break;
        case 'heading-4':
            $lipsum = new LoremIpsum();
            $return = ucwords($lipsum->words(2));
            break;
        case 'images':
            $return = 'data/images/images/slidebox-'.rand(0,4).'.jpg';
            break;
        case 'slidebox':
            $return = 'slide1|slide2';
            break;
        case 'icon':
            $icon = new jsmIonicon();
            $iconList = $icon->iconList();
            $id = rand(0,count($iconList));
            $return = 'ion-'.$iconList[$id]['var'];
            break;
        case 'to_trusted':
            $lipsum = new LoremIpsum();
            $return = $lipsum->sentences(1,'p');
            $return .= $lipsum->sentences(1,'blockquote');
            $return .= $lipsum->sentences(5,'p');
            break;
        case 'link':
            $return = 'http://ihsana.com/?p='.rand(0,9);
            break;
        case 'video':
            $return = 'http://www.w3schools.com/html/mov_bbb.mp4';
            break;
        case 'rating':
            $return = rand(2,5);
            break;
        case 'share_link':
            $return = 'http://goo.gl/D1giIr';
            break;
        case 'ytube':
            $return = '4HkG8z3sa-0';
            break;
        case 'audio':
            $return = 'http://www.w3schools.com/html/horse.mp3';
            break;
        case 'gmap':
            $maps = array(
                '48.85693,2.3412',
                '-6.17149,106.82752',
                '35.68408,139.80885');
            $return = $maps[rand(0,count($maps) - 1)];
            break;
        case 'webview':
            $return = 'http://goo.gl/D1giIr';
            break;

        case 'appbrowser':
            $return = 'http://www.w3schools.com/';
            break;

        case 'number':
            $return = rand(2,1000000);

            break;
        case 'float':
            $return = (rand(2,10) / rand(2,10));

            break;
        case 'date':
            $return = (time() + rand(0,(10 * 86400))) * 1000;
            break;

        case 'datetime':
            $return = (time() + rand(0,(10 * 86400))) * 1000;
            break;

        case 'date_php':
            $return = (time() + rand(0,(10 * 86400)));
            break;

        case 'datetime_php':
            $return = (time() + rand(0,(10 * 86400)));
            break;

        case 'datetime_string':
            $return = date('Y-m-d\Th:i:s');
            break;

        case 'app_email':
            $lipsum = new LoremIpsum();
            $return = strtolower($lipsum->words(1).'@'.$lipsum->words(1).'.com');
            break;
        case 'app_sms':
            $return = '+'.(int)(rand(100000,990000)).(rand(100000,990000));
            break;
        case 'app_call':
            $return = '+'.(int)(rand(100000,990000)).(rand(100000,990000));
            break;
        case 'app_geo':
            $maps = array(
                '48.85693,2.3412',
                '-6.17149,106.82752',
                '35.68408,139.80885');
            $return = $maps[rand(0,count($maps) - 1)];
            break;
    }
    return $return;
}
function str2var($string,$strtolower = true,$whitelist = '')
{
    $char = 'abcdefghijklmnopqrstuvwxyz_1234567890.[\']:'.$whitelist;

    $Allow = null;
    if($strtolower == true)
    {
        $string = strtolower($string);
    } else
    {
        $char .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    $string = str_replace(array(
        ' ',
        '-',
        '__'),'_',$string);

    $string = str_replace(array('___','__'),'_',$string);
    for($i = 0; $i < strlen($string); $i++)
    {
        if(strstr($char,$string[$i]) != false)
        {
            $Allow .= $string[$i];
        }
    }
    return $Allow;
}


function buildIonic($filename)
{

    $_SESSION["FILE_LOAD"] = null;
    $_new_config = array();
    $config = array();
    foreach(glob('projects/'.$filename."/*.json") as $file)
    {
        $_SESSION["FILE_LOAD"]['CONFIG'][] = $file;
        $_config = null;
        $name = pathinfo($file,PATHINFO_FILENAME);
        $_config = json_decode(file_get_contents($file,true),true);
        if(!is_array($_config))
        {
            $_config = array();
        }
        $config = array_merge_recursive($config,$_config);
    }


    $_SESSION["PROJECT"] = $config;
    $ionic = new Ionic('output/',$config);
    $ionic->output();

    foreach(glob('projects/'.$filename.'/img/*.png') as $src_file)
    {
        $new_file = 'output/'.$filename.'/www/img/'.basename($src_file);
        if(copy($src_file,$new_file))
        {
            $_SESSION["FILE_LOAD"]['COPY'][] = $src_file.' => '.$new_file;
        }

    }

    $source_css = 'resources/lib/ionic/css/ionic.min.css';
    if(file_exists($source_css))
    {
        $new_css = 'output/'.$filename.'/www/lib/ionic/css/ionic.min.css';
        @copy($source_css,$new_css);
    }
    $source_css = 'resources/lib/ionic-material/css/ionic.material.min.css';
    if(file_exists($source_css))
    {
        $new_css = 'output/'.$filename.'/www/lib/ionic-material/ionic.material.min.css';
        @copy($source_css,$new_css);
    }

    foreach(glob('projects/'.$filename.'/tables/*.json') as $src_file)
    {
        $new_file = 'output/'.$filename.'/www/data/tables/'.basename($src_file);
        if(copy($src_file,$new_file))
        {
            $_SESSION["FILE_LOAD"]['COPY'][] = $src_file.' => '.$new_file;
        }
    }

}

function notice()
{
    $notice = null;
    $bs = new jsmBootstrap();
    if(!isset($_GET['notice']))
    {
        $_GET['notice'] = null;
    }
    if(!isset($_GET['err']))
    {
        $_GET['err'] = 'null';
    }
    if($_GET['err'] == 'null')
    {
        switch($_GET['notice'])
        {
            case 'save':
                $notice = $bs->Alerts(null,__('Item has been successfully save!'),'success',true);
                break;
            case 'delete':
                $notice = $bs->Alerts(null,__('Item has been successfully delete!'),'success',true);
                break;
            case 'create':
                $notice = $bs->Alerts(null,__('Item has been successfully created!'),'success',true);
                break;
        }
    }

    if($_GET['err'] == 'project')
    {
        $notice = $bs->Modal('error-modal','Ops! Project is error','<p>Please select project or create new project for first time, go to <code>(IMAB) Dashboard</code> then click <code>Deactive</code> to activate one of the projects</p>','md',null,'Close',false);
    }


    if(isset($_SESSION['FILE_NAME']))
    {
        if($_GET['page'] !== 'x-resources')
        {
            if(isset($_SESSION['PROJECT']['menu']))
            {
                if(!file_exists('output/'.$_SESSION['FILE_NAME'].'/resources/icon.png'))
                {
                    $notice = $bs->Modal('error-modal','Ops! Resources is error','<p>Please create an Icon and Splashscreen first, go to <code>Extra Menus</code> -&raquo; <code>(IMAB) Resources</code>, then generate new resources</p>','md',null,'Close',false);
                }
            }
        }
    }


    if(!isset($_SESSION['PAGE_ERROR']))
    {
        $_SESSION['PAGE_ERROR'] = array();
    }
    if(count($_SESSION['PAGE_ERROR']) != 0)
    {
        $msg = '<p>Some pages can not be rewritten, it is because the page is locked, <br/>go to <code>(IMAB) Pages</code> -&raquo; <code>Page Manager</code> --&raquo; click key icon for <code>lock/unlock</code>.</p>';
        $msg .= '<ul>';
        foreach($_SESSION['PAGE_ERROR'] as $page_error)
        {
            $msg .= '<li>'.$page_error.'</li>';
        }
        $msg .= '</ul>';
        $notice = $bs->Modal('error-modal','Ops! Some pages is locked',$msg,'md',null,'Close',false);
    }
    $_SESSION['PAGE_ERROR'] = array();

    if($_GET['err'] == 'true')
    {
        switch($_GET['notice'])
        {
            case 'format':
                $notice = $bs->Alerts(null,'Invalid format!','danger',true);
                break;
            case 'exist':
                $notice = $bs->Alerts(null,'Data already exist, please delete first!','danger',true);
                break;
        }
    }


    // TODO: CHECK INDEX
    if(isset($_SESSION['PROJECT']['app']))
    {
        if($_GET['page'] != 'x-import-project')
        {
            if($_GET['page'] != 'dashboard')
            {
                if($_GET['page'] != 'menu')
                {
                    $msg_notice = 'Please select page as homepage or index! Go to <code>(IMAB) Pages</code> menu, in <code>Page Manager</code> section then select one page <strong>index</strong> by clicking <strong>star</strong> icon.';
                    if(!isset($_SESSION['PROJECT']['app']['index']))
                    {
                        $notice = $bs->Modal('error-modal','Ops! Page as index is error',$msg_notice,'md',null,'Close',false);
                    } else
                    {
                        if(!isset($_SESSION['PROJECT']['page']))
                        {
                            $_SESSION['PROJECT']['page'] = array();
                        }
                        $index_prefix = $_SESSION['PROJECT']['app']['index'];
                        $x_pages = $_SESSION['PROJECT']['page'];
                        $page_index_error = true;
                        foreach($x_pages as $x_page)
                        {
                            if($x_page['prefix'] == $index_prefix)
                            {
                                $page_index_error = false;
                            }
                        }

                        if($page_index_error == true)
                        {
                            $notice = $bs->Modal('error-modal','Ops! Home page is error',$msg_notice,'md',null,'Close',false);
                        }
                    }
                }
            }
        }
    }
    return $notice;
}

function googleplay_link()
{
    return "market://details?id=".JSM_PACKAGE_NAME.".".str_replace('_','',str2var($_SESSION['PROJECT']['app']['company'])).'.'.str_replace('_','',str2var($_SESSION['PROJECT']['app']['prefix']));
}

function mailto_link()
{
    return "mailto:".$_SESSION['PROJECT']['app']['author_email'];
}

if(isset($_SESSION['PROJECT']['app']))
{
    $redirect_fix = false;
    $recovery = glob("projects/".$_SESSION['FILE_NAME']."/page.*.json");
    foreach($recovery as $save_page_file)
    {
        $raw_page = json_decode(file_get_contents($save_page_file),true);
        if(trim($_SESSION['PROJECT']['menu']['type']) == 'side_menus')
        {

            if($raw_page['page'][0]['menutype'] == 'tabs-custom')
            {
                $raw_page['page'][0]['menutype'] = 'side_menus-custom';
                file_put_contents($save_page_file,json_encode($raw_page));
                $redirect_fix = true;
            }

            if($raw_page['page'][0]['menutype'] == trim('tabs'))
            {
                $raw_page['page'][0]['menutype'] = 'side_menus';
                file_put_contents($save_page_file,json_encode($raw_page));
                $redirect_fix = true;
            }

            if($raw_page['page'][0]['menutype'] == trim('sub-tabs'))
            {
                $raw_page['page'][0]['menutype'] = 'sub-side_menus';
                file_put_contents($save_page_file,json_encode($raw_page));
                $redirect_fix = true;
            }

        }

        if(trim($_SESSION['PROJECT']['menu']['type']) == 'tabs')
        {

            if($raw_page['page'][0]['menutype'] == 'sub-side_menus')
            {
                $raw_page['page'][0]['menutype'] = 'sub-tabs';
                file_put_contents($save_page_file,json_encode($raw_page));
                $redirect_fix = true;
            }

            if($raw_page['page'][0]['menutype'] == trim('side_menus'))
            {
                $raw_page['page'][0]['menutype'] = 'tabs';
                file_put_contents($save_page_file,json_encode($raw_page));
                $redirect_fix = true;
            }

            if($raw_page['page'][0]['menutype'] == 'side_menus-custom')
            {
                $raw_page['page'][0]['menutype'] = 'tabs-custom';
                file_put_contents($save_page_file,json_encode($raw_page));
                $redirect_fix = true;
            }

        }
    }

}

?>