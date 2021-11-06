<?php

$run_emulator = true;

if (!defined('JSM_EXEC'))
{
    die(':)');
}

$file_name = 'test';
$bs = new jsmBootstrap();
$form_input = $html = $footer = null;


if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}

if (isset($_GET['tos']))
{
    if ($_GET['tos'] == 'true')
    {
        $_SESSION['JSM_PAGEBUILDER_AGREE'] = true;
        header('Location: ./?page=x-page-builder');
        die();
    }
}


if (!isset($_SESSION["PROJECT"]['menu']))
{
    header('Location: ./?page=menu&err=new');
    die();
}

$out_path = 'output/' . $file_name;

$preview_url = $out_path . '/www/#/' . $_SESSION['PROJECT']['app']['prefix'] . '/';

$available_for_submit = true;
$require_target_page = false;
if (!isset($_GET['prefix']))
{
    $_GET['prefix'] = '';
}
if (!isset($_GET['source']))
{
    $_GET['source'] = '';
}

if (!isset($_GET['target']))
{
    $_GET['target'] = '';
}

if (isset($_GET['disable']))
{
    $pagebuilder_file = basename($_GET['disable']);
    rename(JSM_PATH . "/system/includes/page-builder/" . $pagebuilder_file . ".templates.php", JSM_PATH . "/system/includes/page-builder/" . $pagebuilder_file . ".templates.php.disable");
    header('Location: ?page=x-page-builder');
    die();
}
if (isset($_GET['enable']))
{
    $pagebuilder_file = basename($_GET['enable']);
    rename(JSM_PATH . "/system/includes/page-builder/" . $pagebuilder_file . ".templates.php.disable", JSM_PATH . "/system/includes/page-builder/" . $pagebuilder_file . ".templates.php");
    header('Location: ?page=x-page-builder');
    die();
}
$error = null;

$module_path = 'system/includes/page-builder/';
if (isset($_FILES['new_pagebuilder']))
{
    $tmp_name = $_FILES["new_pagebuilder"]["tmp_name"];
    $zip = new ZipArchive;
    if ($zip->open($tmp_name) === true)
    {
        $zip->extractTo($module_path);
        $zip->close();
        $error = '<div class="alert alert-success"><p>IMA Builder modules has been successfully installed</p></div>';
    } else
    {
        $error = '<div class="alert alert-danger"><p>This is not <strong>IMA Builder</strong> modules, please try again</p></div>';
    }
}

$use_pagebuilder = null;
if (strlen($_GET['prefix']) > 5)
{
    $use_pagebuilder = ' -&raquo; <ins>' . ucwords(str_replace('_', ' ', $_GET['prefix'])) . '</ins>';
}
$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-book fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Page Builder ' . $use_pagebuilder . '</h4>';
if ($_GET['prefix'] != '')
{
    $content .= '
    <ul class="nav nav-tabs">
        <li><a href="./?page=x-page-builder">' . __('Modules') . '</a></li>
        <li class="active"><a href="#">' . __('Settings') . '</a></li>
    </ul>';
} else
{
    $content .= '
    <ul class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab">Modules</a></li>
        <li><a href="#recent-module" data-toggle="tab">' . __('Recently Used') . '</a></li>
        <li><a href="#new" data-toggle="tab">' . __('Module Manager') . '</a></li>
    </ul>';
}

$content .= '<br/>';

$content .= notice();
if ($_GET['prefix'] == '')
{

    $content .= '<div class="tab-content">';
    $content .= '<div class="tab-pane active" id="home">';
    $content .= '
    <blockquote class="blockquote blockquote-danger">
    <h4>' . __('The rules that apply are:') . '</h4>
    <ol>
    <li>' . __('Module with the prefix <code>page_*</code> only an effect for a <code>specific page</code>,') . '</li>
    <li>' . __('and while beginning <code>eazy_*/setup_*</code> will affect <code>all the settings</code> either menu, table or page.') . '</li>
    <li>' . __('Maybe, not suitable for <code>Smartcode Builder</code> features') . '</li>
    <li>' . __('Page Builder using custom code, its will disappear if you save the table again.') . '</li>
    </ol>
    </blockquote>
    
    ';

    $content .= '<div class="row">';
    foreach (glob(JSM_PATH . "/system/includes/page-builder/*.templates.php") as $filename)
    {
        $readme = array();
        $link_prefix = str_replace('.templates.php', '', basename(realpath($filename)));
        if (file_exists(JSM_PATH . "/system/includes/page-builder/" . $link_prefix . "/readme.txt"))
        {
            $readme = json_decode(file_get_contents(JSM_PATH . "/system/includes/page-builder/" . $link_prefix . "/readme.txt"), true);
        }
        if (!isset($readme['for']))
        {
            $readme['for'] = 'Unknow';
        }
        if (!isset($readme['info']))
        {
            $readme['info'] = '-';
        }
        $img_thumbnail = './templates/default/img/pagebuilder.png';

        if (file_exists('./system/includes/page-builder/' . $link_prefix . '/assets/thumbnail.png'))
        {
            $img_thumbnail = './system/includes/page-builder/' . $link_prefix . '/assets/thumbnail.png';
        }

        $date_modif = 'rev' . date("y.m.d", filemtime($filename));

        $content .= '
    <div class="col-md-3">
    
        <div class="thumbnail" style="min-height: 430px;">
            <img style="border: 1px solid #ddd;" src="' . $img_thumbnail . '" alt="' . $link_prefix . '" />
            <div class="caption">
                <h3 style="font-size:16px;font-weight: 600;margin:0;">' . ucwords(str_replace('_', ' ', $link_prefix)) . '</h3>
                <p style=" border-left-color: #ddd;border-left-style: solid;border-left-width: 3px;font-size:10px;margin-top: 6px;padding-left: 6px;">' . ($readme['info']) . '<br/>-ver: ' . $date_modif . '</p>
                <p style="position: absolute;bottom: 32px;"><a href="./?page=x-page-builder&prefix=' . $link_prefix . '" class="btn btn-primary" >' . __('Choose') . '</a></p>
            </div>
            <span style="font-weight:800;position:absolute;top:-12px;left:32px;background:#fff;color:#000;padding:4px;opacity:1;border:1px solid #ddd;">
            ' . ($readme['for']) . '
            </span>
        </div>
        
    </div>';
    }
    $content .= '</div>';
    $content .= '</div>';

    // TODO: PANEL NEW MODULES
    $content .= '<div class="tab-pane" id="new">';
    $_content = null;
    $_content .= $bs->FormGroup('new_pagebuilder', 'default', 'file', __('Zip File'), '', '', '', '8', '');
    $_content .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
            'name' => 'upload',
            'label' => __('Upload') . ' &raquo;',
            'tag' => 'submit',
            'color' => 'primary'))));

    // TODO: ---- | ---- MODULE MANAGER
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading"><h4 class="panel-title">' . __('New Modules') . '</h4></div>';
    $content .= '<div class="panel-body">';
    $content .= $bs->Forms('module-setup', '', 'post', 'default', $_content);
    $content .= '</div>';
    $content .= '</div>';

    // TODO: ---- | ---- ENABLE MODULES
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading"><h4 class="panel-title">' . __('Enable Modules') . '</h4></div>';
    $content .= '<div class="panel-body">';

    $content .= '<div class="table-responsive">';
    $content .= '<table class="table table-striped">';

    foreach (glob(JSM_PATH . "/system/includes/page-builder/*.templates.php") as $filename)
    {
        $link_prefix = str_replace('.templates.php', '', basename(realpath($filename)));
        $content .= '<tr>';
        $content .= '<td>' . $link_prefix . '</td>';
        $content .= '<td><a href="./?page=x-page-builder&disable=' . $link_prefix . '" class="btn btn-danger btn-sm">' . __('Disable') . '</a></td>';
        $content .= '</tr>';
    }
    foreach (glob(JSM_PATH . "/system/includes/page-builder/*.templates.php.disable") as $filename)
    {
        $link_prefix = str_replace('.templates.php.disable', '', basename(realpath($filename)));
        $content .= '<tr>';
        $content .= '<td>' . $link_prefix . '</td>';
        $content .= '<td><a href="./?page=x-page-builder&enable=' . $link_prefix . '" class="btn btn-success btn-sm">' . __('Enable') . '</a></td>';
        $content .= '</tr>';
    }
    $content .= '</table>';
    $content .= '</div>';

    $content .= '</div>';

    $content .= '</div>';
    $content .= '</div><!-- ./new -->';


    $content .= '<div class="tab-pane" id="recent-module">';
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading"><h4 class="panel-title">' . __('Recently Used') . '</h4></div>';
    $content .= '<div class="panel-body">';

    $content .= '<h4>' . __('Page') . '</h4>';
    $content .= '<table class="table table-striped">';
    $content .= '<tr>';
    $content .= '<th>' . __('Pages') . '</th>';
    $content .= '<th>' . __('Modules') . '</th>';
    $content .= '<th>' . __('Edit') . '</th>';
    $content .= '</tr>';
    foreach ($_SESSION['PROJECT']['page'] as $page_used_by_module)
    {
        if (!isset($page_used_by_module['builder_link']))
        {
            $page_used_by_module['builder_link'] = '';
        }
        if ($page_used_by_module['builder_link'] !== '')
        {
            $page_prefix = $page_used_by_module['prefix'];
            $page_builder = end(explode('page=x-page-builder', $page_used_by_module['builder_link']));
            $get_page_builder_name = explode("&", end(explode('prefix=', $page_used_by_module['builder_link'])));
            $page_builder_prefix = $get_page_builder_name[0];
            $page_builder_name = str_replace('_', ' ', $page_builder_prefix);

            $content .= '<tr>';
            $content .= '<td><a target="_blank" href="./?page=page&prefix=' . $page_prefix . '">' . $page_prefix . '</a></td>';
            $content .= '<td><a target="_blank" href="./?page=x-page-builder' . $page_builder . '">' . ucwords($page_builder_name) . '</a></td>';
            $content .= '<td><a class="btn btn-primary btn-sm " target="_blank" href="./?page=x-page-builder' . $page_builder . '"><i class="fa fa-pencil"></i> Edit</a></td>';
            $content .= '</tr>';
        }
    }
    $content .= '</table>';

    $content .= '<h4>' . __('Tables') . '</h4>';
    $content .= '<table class="table table-striped">';
    $content .= '<tr>';
    $content .= '<th>' . __('Tables') . '</th>';
    $content .= '<th>' . __('Modules') . '</th>';
    $content .= '<th>' . __('Edit') . '</th>';
    $content .= '</tr>';
    foreach ($_SESSION['PROJECT']['tables'] as $tables_used_by_module)
    {
        if (!isset($tables_used_by_module['builder_link']))
        {
            $tables_used_by_module['builder_link'] = '';
        }
        if ($tables_used_by_module['builder_link'] !== '')
        {
            $page_prefix = $tables_used_by_module['prefix'];
            $page_builder = end(explode('page=x-page-builder', $tables_used_by_module['builder_link']));
            $get_page_builder_name = explode("&", end(explode('prefix=', $tables_used_by_module['builder_link'])));
            $page_builder_prefix = $get_page_builder_name[0];
            $page_builder_name = str_replace('_', ' ', $page_builder_prefix);

            $content .= '<tr>';
            $content .= '<td><a target="_blank" href="./?page=table&prefix=' . $page_prefix . '">' . $page_prefix . '</a></td>';
            $content .= '<td><a target="_blank" href="./?page=x-page-builder' . $page_builder . '">' . ucwords($page_builder_name) . '</a></td>';
            $content .= '<td><a class="btn btn-primary btn-sm " target="_blank" href="./?page=x-page-builder' . $page_builder . '"><i class="fa fa-pencil"></i> Edit</a></td>';
            $content .= '</tr>';
        }
    }
    $content .= '</table>';


    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div><!-- ./recent -->';

    $content .= '</div>';


} else
{
    // TODO: PAGE BUILDER --|-- SETTINGS
    $how_to_use = null;

    $template_prefix = str2var($_GET["prefix"]);
    if (file_exists(JSM_PATH . "/system/includes/page-builder/" . $template_prefix . ".templates.php"))
    {
        require_once (JSM_PATH . "/system/includes/page-builder/" . $template_prefix . ".templates.php");
    }
    $content .= '<h4 style="border-bottom:1px solid #ddd;padding-bottom: 6px;">' . ucwords(str_replace('_', ' ', $template_prefix)) . '</h4>';

    $available_for_submit = true;
    if ($require_target_page == true)
    {
        if ($_GET['target'] == '')
        {
            $available_for_submit = false;
        }
    }

    if ($available_for_submit == true)
    {
        $form_input .= $bs->FormGroup(null, 'horizontal', 'html', null, $bs->ButtonGroups(null, array(array(
                'name' => 'page-builder',
                'label' => __('Save Setting (2x Click)'),
                'tag' => 'submit',
                'color' => 'primary'), array(
                'label' => __('Reset'),
                'tag' => 'reset',
                'color' => 'default'))));
    }

    $page_builder_notice = '<p>This Page builder will add some pages</p>';
    if (preg_match("/page_/i", htmlentities($_GET['prefix'])))
    {
        $page_builder_notice = "<p>This Page Builder will override the <code>targeted page</code>, if you do not have a page please create first using the menu <a href=\"./?page=page#new-page\">(IMAB) Pages</a></p>";
    }
    if (preg_match("/eazy_setup/i", htmlentities($_GET['prefix'])))
    {
        $page_builder_notice = "<p>This Page Builder will override the all of <code>menus</code>, <code>pages</code>,<code>tables</code>, and <code>other</code>. if not sure please backup your project first using the menu <a href=\"./?page=x-import-project\">Extra Menus -&raquo; (IMAB) Import Project  -&raquo; Export The Project</a></p>";
    }
    $content .= '<blockquote class="blockquote blockquote-info">' . $page_builder_notice . '</blockquote>';
    $content .= $how_to_use;
    $content .= $bs->Forms('page-builder-setup', '', 'post', 'horizontal', $form_input);


}


function create_page($new_page_class, $new_page_title, $new_page_prefix, $new_page_content, $new_page_css = '', $new_page_js = '', $background = true, $icon = 'ion-android-star', $scroll = false, $overflow_scroll = false, $add_to_menu = true, $title_tranparant = false, $remove_has_header = false, $remove_button_up = false, $remove_header = false, $url_background = '', $after_ionicview = '')
{

    if ($add_to_menu == true)
    {
        if (file_exists('projects/' . $_SESSION['FILE_NAME'] . '/menu.json'))
        {
            $raw_menu = json_decode(file_get_contents('projects/' . $_SESSION['FILE_NAME'] . '/menu.json'), true);
            $raw_menu['menu']['items'][] = array(
                "label" => $new_page_title,
                "var" => $new_page_prefix,
                "type" => "link",
                "icon-alt" => $icon,
                "icon" => $icon);
            $row_menu = array();
            $row_menus = array();

            foreach ($raw_menu['menu']['items'] as $row_menu)
            {
                $row_menus[$row_menu['var']] = $row_menu;
            }
            $row_menu = array();
            $raw_menu['menu']['items'] = array();
            foreach ($row_menus as $row_menu)
            {
                $raw_menu['menu']['items'][] = $row_menu;
            }


            file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/menu.json', json_encode($raw_menu));
        }
    }
    if ($background == true)
    {
        $img_link = $url_background;
    } else
    {
        $img_link = '';
    }

    if (strlen($background) > 5)
    {
        $img_link = $background;
    }
    $new_page['page'][0] = array(
        'title' => $new_page_title,
        'prefix' => $new_page_prefix,
        'for' => '-',
        'last_edit_by' => 'page_builder',
        'parent' => $_SESSION['PROJECT']['menu']['type'],
        'menutype' => $_SESSION['PROJECT']['menu']['type'],
        'builder_link' => @$_SERVER["HTTP_REFERER"],
        'menu' => '-',
        'lock' => true,
        'class' => $new_page_class,
        'button_up' => 'bottom-right',
        'scroll' => true,
        'css' => $new_page_css,
        'js' => $new_page_js,
        'content' => $new_page_content,
        'after_ionicview' => $after_ionicview,
        'img_bg' => $img_link);

    if (isset($_GET['target']))
    {
        foreach ($_SESSION['PROJECT']['page'] as $page)
        {
            if ($page['prefix'] == $_GET['target'])
            {
                if (!isset($page['button_up']))
                {
                    $page['button_up'] = 'none';
                }
                $new_page['page'][0]['for'] = $page['for'];
                $new_page['page'][0]['version'] = 'Upd.' . date('ymdhi');
                $new_page['page'][0]['parent'] = $page['parent'];
                $new_page['page'][0]['menutype'] = $page['menutype'];
                $new_page['page'][0]['button_up'] = $page['button_up'];
                $new_page['page'][0]['scroll'] = $scroll;
                $new_page['page'][0]['overflow-scroll'] = $overflow_scroll;

                if (isset($page['query']))
                {
                    $new_page['page'][0]['query'] = $page['query'];
                } else
                {
                    unset($new_page['page'][0]['query']);
                }

                if (isset($page['db_url_dinamic']))
                {
                    if ($page['db_url_dinamic'] == false)
                    {
                        $new_page['page'][0]['db_url_dinamic'] = false;
                    } else
                    {
                        $new_page['page'][0]['db_url_dinamic'] = 'on';
                    }
                } else
                {
                    $new_page['page'][0]['db_url_dinamic'] = false;
                }


            }
        }
    }

    if ($title_tranparant == true)
    {
        $new_page['page'][0]['title-tranparant'] = true;
    }
    if ($remove_has_header == true)
    {
        $new_page['page'][0]['remove-has-header'] = true;
    }

    if ($remove_button_up == true)
    {
        $new_page['page'][0]['button_up'] = 'none';
    }
    if ($remove_header == true)
    {
        $new_page['page'][0]['hide-navbar'] = true;
    }

    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page.' . $new_page_prefix . '.json', json_encode($new_page));
    buildIonic($_SESSION['FILE_NAME']);
    //header('Location: ./?page=page&prefix=' . $new_page_prefix);
    //exit(0);
}

class ImaProject
{

    function __construct()
    {

    }
    function get_tables()
    {
        $new_table = array();
        foreach ($_SESSION['PROJECT']['tables'] as $table)
        {
            $new_table[] = array('prefix' => $table['prefix'], "title" => $table['title']);
        }
        return $new_table;
    }
    function get_columns($table = '')
    {
        $new_column = array();
        if (isset($_SESSION['PROJECT']['tables'][$table]['cols']))
        {

            if (is_array($_SESSION['PROJECT']['tables'][$table]['cols']))
            {
                foreach ($_SESSION['PROJECT']['tables'][$table]['cols'] as $cols)
                {
                    $new_column[] = array('label' => $cols['label'], "value" => str2var($cols['title'], false));
                }
            }
        }
        return $new_column;
    }
    function get_pages()
    {
        $new_page = array();
        foreach ($_SESSION['PROJECT']['page'] as $page)
        {
            if (!isset($page['for']))
            {
                $page['for'] = '-';
            }

            if (!isset($page['title']))
            {
                $page['title'] = '';
            }

            $_pagebuilder = null;
            if (isset($page['builder_link']))
            {
                $_parse_url = explode('&prefix=', parse_url($page['builder_link'], PHP_URL_QUERY));
                if (count($_parse_url) != 1)
                {
                    $parse_url = explode('&', $_parse_url[1]);
                    $_pagebuilder = ' ( by PageBuilder: `' . $parse_url[0] . '` )';
                }
            }
            $new_page[] = array(
                'prefix' => $page['prefix'],
                "title" => $page['title'],
                'builder' => $_pagebuilder,
                'for' => $page['for']);
        }
        return $new_page;
    }
}

$icon = new jsmIonicon();
$modal_dialog = $icon->display();
$content .= $bs->Modal('icon-dialog', 'Ionicon Tables', $modal_dialog, 'md', null, 'Close', null);


$_page[] = googleplay_link();
$_page[] = mailto_link();

foreach ($_SESSION['PROJECT']['page'] as $page)
{
    $param_query = null;
    if (isset($page['query']))
    {
        $param_query = '/1';
    }
    $_page[] = '#/' . $file_name . '/' . $page['prefix'] . $param_query;
}

$content .= '<script type="text/javascript">';
$content .= 'var typehead_vars = ' . json_encode($_page) . ';';
$content .= '</script>';

$pg_title = __('Page Builder ~ Agreement');
$pg_body = __('Page Builder Module is derived <ins>contributions from other users</ins>,
    IMA Builder does <ins>not test</ins> all module as a whole, so <ins>no support for that</ins>. but feel free, to use <ins>dozens of templates</ins>. 
    And you can also submit your template or idea if you want to use by another user. If you do not agree, you can disable the module from imabuilder by clicking <ins>Module Manager</ins>.');
$pg_body .= '<br/><br/><p><a class="btn btn-danger" href="./?page=x-page-builder&tos=true" class="btn btn-sm">Yes, I Agree</a></p>';
$content .= $bs->Modal('modal_pagebuilder', $pg_title, $pg_body, 'md', '', '', false, false);

if (!isset($_SESSION['JSM_PAGEBUILDER_AGREE']))
{
    $footer .= '<script type="text/javascript"> $("#modal_pagebuilder").modal();</script>';
}
$template->demo_url = $preview_url;
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Page Builder';
$template->base_desc = 'Page Builder';
$template->content = $content;
$template->emulator = $run_emulator;
$template->footer = $footer;
