<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic Mobile App Builder
 */

if(!defined('JSM_EXEC'))
{
    die(':)');
}
$file_name = 'test';
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
$footer = null;
function str2SQL($string)
{

    $char = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_12345678900.';
    $Allow = null;
    $string = str_replace(array(
        ' ',
        '-',
        '__'),'_',($string));
    $string = str_replace(array('___','__'),'_',($string));
    for($i = 0; $i < strlen($string); $i++)
    {
        if(strstr($char,$string[$i]) != false)
        {
            $Allow .= $string[$i];
        }
    }
    return $Allow;
}
if(!isset($_GET['prefix']))
{
    $_GET['prefix'] = null;
}
$prefix_json = $_GET['prefix'];
$out_path = 'output/'.$file_name;
$content = null;
$php_mysql_path = 'projects/'.$file_name.'/php_sql.json';
if(file_exists($php_mysql_path))
{
    $raw_php_mysql = json_decode(file_get_contents($php_mysql_path),true);
}
if(!isset($raw_php_mysql))
{
    $raw_php_mysql = array();
}
if(!is_array($raw_php_mysql))
{
    $raw_php_mysql = array();
}

$_tables_used = array();
if(isset($raw_php_mysql['php_sql']))
{
    if(is_array($raw_php_mysql['php_sql']))
    {
        foreach($raw_php_mysql['php_sql'] as $used)
        {
            $_tables_used[] = $used['name'];
        }
    }
}

$php_mysql_path_config = 'projects/'.$file_name.'/php_sql_config.json';
if(file_exists($php_mysql_path_config))
{
    $raw_php_mysql_config = json_decode(file_get_contents($php_mysql_path_config),true);
}

if(!isset($raw_php_mysql_config))
{
    $raw_php_mysql_config = array();
}
if(!is_array($raw_php_mysql_config))
{
    $raw_php_mysql_config = array();
}

if(!isset($raw_php_mysql_config['php_sql_config']['host']))
{
    $raw_php_mysql_config['php_sql_config']['host'] = 'localhost';
}
if(!isset($raw_php_mysql_config['php_sql_config']['uname']))
{
    $raw_php_mysql_config['php_sql_config']['uname'] = 'root';
}
if(!isset($raw_php_mysql_config['php_sql_config']['pwd']))
{
    $raw_php_mysql_config['php_sql_config']['pwd'] = '';
}
if(!isset($raw_php_mysql_config['php_sql_config']['dbase']))
{
    $raw_php_mysql_config['php_sql_config']['dbase'] = 'ima_builder';
}

if(!isset($raw_php_mysql_config['php_sql_config']['user_email']))
{
    $raw_php_mysql_config['php_sql_config']['user_email'] = 'admin';
}
if(!isset($raw_php_mysql_config['php_sql_config']['user_password']))
{
    $raw_php_mysql_config['php_sql_config']['user_password'] = 'admin';
}

if(!isset($raw_php_mysql_config['php_sql_config']['theme']))
{
    $raw_php_mysql_config['php_sql_config']['theme'] = 'lumen';
}
if(!isset($raw_php_mysql_config['php_sql_config']['navbar']))
{
    $raw_php_mysql_config['php_sql_config']['navbar'] = 'nav-stacked';
}

if(!isset($raw_php_mysql_config['php_sql_config']['type_datetime']))
{
    $raw_php_mysql_config['php_sql_config']['type_datetime'] = '';
}
if(!isset($raw_php_mysql_config['php_sql_config']['type_date']))
{
    $raw_php_mysql_config['php_sql_config']['type_date'] = '';
}
if(!isset($raw_php_mysql_config['php_sql_config']['type_tags']))
{
    $raw_php_mysql_config['php_sql_config']['type_tags'] = '';
}

if(isset($_POST['php_sql_config']))
{
    $__new_data = $raw_php_mysql_config;
    $__new_data['php_sql_config']['navbar'] = $_POST['php_sql_config']['navbar'];
    $__new_data['php_sql_config']['theme'] = $_POST['php_sql_config']['theme'];

    if(isset($_POST['php_sql_config']['utf8']))
    {
        $__new_data['php_sql_config']['utf8'] = true;
    } else
    {
        $__new_data['php_sql_config']['utf8'] = false;
    }

    $__new_data['php_sql_config']['user_email'] = $_POST['php_sql_config']['user_email'];
    $__new_data['php_sql_config']['user_password'] = $_POST['php_sql_config']['user_password'];

    $__new_data['php_sql_config']['type_datetime'] = $_POST['php_sql_config']['type_datetime'];
    $__new_data['php_sql_config']['type_date'] = $_POST['php_sql_config']['type_date'];
    $__new_data['php_sql_config']['type_tags'] = $_POST['php_sql_config']['type_tags'];

    file_put_contents($php_mysql_path_config,json_encode($__new_data));
    buildIonic($file_name);
    header('Location: ./?page=z-php-sql-web-admin-generator&err=null&notice=save');
}

$sql = null;
$tables = $_SESSION['PROJECT']['tables'];

$r = $s = $z = 0;
$_relation_detect = $_is_sync = $table_contain_option = array();


foreach($tables as $table)
{
    if(in_array($table['prefix'],$_tables_used))
    {
        foreach($table['cols'] as $col)
        {
            if($col['type'] == 'id')
            {
                $col_id = str2SQL($col['title']);
                $column[$table['prefix']][] = array(
                    'type' => 'id',
                    'column_type' => 'int(12)',
                    'column_tip' => '',
                    'column_example' => '',
                    'column_name' => str2SQL($col['title']),
                    'column_label' => htmlentities(ucwords($col['title'])));
            }
        }
        if(!isset($col_id))
        {
            $col_id = 'id';
        }
        $new_colums = array();
        foreach($table['cols'] as $col)
        {
            $new_colums[str2SQL($col['title'])] = $col;
        }
        // TODO: column type
        foreach($new_colums as $col)
        {

            switch($col['type'])
            {
                case 'heading-1':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'type' => $col['type'],
                        'column_name' => str2SQL($col['title']),
                        'column_tip' => '',
                        'column_example' => '',
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'heading-2':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'type' => $col['type'],
                        'column_tip' => '',
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])));
                    break;
                case 'heading-3':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'heading-4':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'type' => $col['type'],
                        'column_tip' => '',
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'text':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'images':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'text',
                        'column_tip' => 'Using base64 image data (small images) <input data-target="#'.str2SQL($col['title']).'" type="file" data-type="image-base64" /> or file upload <input name="'.str2SQL($col['title']).'-upload" type="file" data-type="image-upload" />',
                        'type' => $col['type'],
                        'column_example' => 'http://your-domain/files.jpg',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'video':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => 'Maximum file size per upload : \'. ini_get("upload_max_filesize").\'',
                        'type' => $col['type'],
                        'column_example' => 'http://your-domain/file.mp4',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'ytube':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'gmap':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'webview':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => 'http://your-domain/pages',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'appbrowser':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => 'http://your-domain/pages',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'audio':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => 'Maximum file size per upload : \'. ini_get("upload_max_filesize").\'',
                        'type' => $col['type'],
                        'column_example' => 'http://your-domain/files.mp3',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'share_link':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => 'http://your-domain/pages',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'link':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => 'http://your-domain/pages',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'icon':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => 'use <kbd>ionicons class</kbd>',
                        'type' => $col['type'],
                        'column_example' => 'ion-android-bicycle',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'paragraph':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'slidebox':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'text',
                        'column_tip' => 'Separator with |, example: <code>&lt;img src=\\\'http://yourimages\\\'&gt;|&lt;img src=\\\'http://yourimages\\\'&gt;</code> and not support double quote (&quot;) so use single quote (\\\')',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'to_trusted':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'text',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'rating':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'int',
                        'column_tip' => 'Number: 1-5',
                        'type' => $col['type'],
                        'column_example' => '4',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'as_username':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'type' => $col['type'],
                        'column_name' => str2SQL($col['title']),
                        'column_tip' => '',
                        'column_example' => '',
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
                case 'as_password':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'type' => $col['type'],
                        'column_name' => str2SQL($col['title']),
                        'column_tip' => '',
                        'column_example' => '',
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;

                case 'number':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'int',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;

                case 'float':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'float',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;

                case 'app_email':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'email',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;

                case 'app_sms':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;

                case 'app_call':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;

                case 'app_geo':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;

                case 'date':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;

                case 'datetime':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;

                case 'date_php':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;

                case 'datetime_php':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;

                case 'datetime_string':
                    $column[$table['prefix']][] = array(
                        'column_type' => 'varchar',
                        'column_tip' => '',
                        'type' => $col['type'],
                        'column_example' => '',
                        'column_name' => str2SQL($col['title']),
                        'column_label' => htmlentities(ucwords($col['title'])),
                        );
                    break;
            }
        }
    }
}


$bs = new jsmBootstrap();
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-code fa-stack-1x"></i></span>Backend Tools -&raquo; (IMAB) Web Admin Generator (PHP + SQL)</h4>';
$content .= '<p><span class="label label-info">'.__('Note').'</span> : '.__('This features for create simple admin panel, for active this menu please select table in').' <a href="./?page=z-php-sql-restapi-generator">(IMAB) PHPSQL - RESTAPI Generator</a></p>';

if(isset($column))
{

    if(!isset($_SESSION['PROJECT']['push']['app_key']))
    {
        $_SESSION['PROJECT']['push']['app_key'] = '';
    }
    if(!isset($_SESSION['PROJECT']['push']['app_id']))
    {
        $_SESSION['PROJECT']['push']['app_id'] = '';
    }


    $navbars = $themes = $_themes = array();
    $navbars[] = array('label' => 'nav-bar','value' => 'nav-bar');
    $navbars[] = array('label' => 'nav-stacked','value' => 'nav-stacked');
    $c = 0;
    foreach($navbars as $navbar)
    {
        $_navbar[$c] = $navbar;
        if($raw_php_mysql_config['php_sql_config']['navbar'] == $navbar['value'])
        {
            $_navbar[$c]['active'] = true;
        }
        $c++;
    }
    $themes[] = array('value' => 'paper','label' => 'Paper');
    $themes[] = array('value' => 'cerulean','label' => 'Cerulean');
    $themes[] = array('value' => 'cosmo','label' => 'Cosmo');
    $themes[] = array('value' => 'cyborg','label' => 'Cyborg');
    $themes[] = array('value' => 'flatly','label' => 'Flatly');
    $themes[] = array('value' => 'journal','label' => 'Journal');
    $themes[] = array('value' => 'lumen','label' => 'Lumen');
    $themes[] = array('value' => 'readable','label' => 'Readable');
    $themes[] = array('value' => 'simplex','label' => 'Simplex');
    $themes[] = array('value' => 'slate','label' => 'Slate');
    $themes[] = array('value' => 'spacelab','label' => 'Spacelab');
    $themes[] = array('value' => 'superhero','label' => 'Superhero');
    $themes[] = array('value' => 'united','label' => 'United');
    $themes[] = array('value' => 'yeti','label' => 'Yeti');
    $themes[] = array('value' => 'sandstone','label' => 'Sandstone');

    $c = 0;
    foreach($themes as $theme)
    {
        $_themes[$c] = $theme;
        if($raw_php_mysql_config['php_sql_config']['theme'] == $theme['value'])
        {
            $_themes[$c]['active'] = true;
        }
        $c++;
    }

    $content .= "\r\n\r\n";
    $content .= '<ul class="nav nav-tabs">';
    $content .= '<li class="active"><a href="#code" data-toggle="tab">'.__('Code Generator').'</a></li>';
    $content .= '<li><a href="#help" data-toggle="tab" >'.__('How To Use?').'</a></li>';
    $content .= '</ul>';
    $content .= '<br/>';
    $content .= "\r\n\r\n";

    $content .= '<div class="tab-content">';
    $content .= '<div class="tab-pane active" id="code">';

    $content .= '<form action="" method="post">';
    $content .= '<div class="row">';
    $content .= '<div class="col-md-6">';
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading"><h4 class="panel-title">'.__('General').'</h4></div>';
    $content .= '<div class="panel-body">';
    $content .= '<h5>'.__('Layout').'</h5>';
    $content .= $bs->FormGroup('php_sql_config[navbar]','default','select',__('Navigation Bar'),$_navbar,'','','8');
    $content .= $bs->FormGroup('php_sql_config[theme]','default','select',__('Theme'),$_themes,__('Themes from').' <a target="_blank" href="https://www.bootstrapcdn.com/bootswatch/">https://www.bootstrapcdn.com/bootswatch/</a>','','8');
    //$content .= $bs->FormGroup('php_sql_config[utf8]', 'default', 'checkbox', '', 'UTF-8', '', '', '8');
    $content .= '<h5>'.__('Administrator').'</h5>';
    $content .= $bs->FormGroup('php_sql_config[user_email]','default','text',__('User Email'),'',__('Email for login as the administrator'),'','8',$raw_php_mysql_config['php_sql_config']['user_email']);
    $content .= $bs->FormGroup('php_sql_config[user_password]','default','text',__('User Password'),'',__('Password for login as the administrator'),'','8',$raw_php_mysql_config['php_sql_config']['user_password']);

    $content .= '<h5>'.__('Form Input').'</h5>';
    $content .= $bs->FormGroup('php_sql_config[type_datetime]','default','text',__('Date Time'),'',__('<code>#table .input-column</code>, separator with coma'),'','8',$raw_php_mysql_config['php_sql_config']['type_datetime']);
    $content .= $bs->FormGroup('php_sql_config[type_date]','default','text',__('Date'),'',__('<code>#table .input-column</code>, separator with coma'),'','8',$raw_php_mysql_config['php_sql_config']['type_date']);
    $content .= $bs->FormGroup('php_sql_config[type_tags]','default','text',__('Tags'),'',__('<code>#table .input-column</code>, separator with coma'),'','8',$raw_php_mysql_config['php_sql_config']['type_tags']);


    $content .= '<br/><input type="submit" value="'.__('Save Setting').'" class="btn btn-primary" />';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '<div class="col-md-6">';
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading"><h4 class="panel-title">'.__('Other Settings').'</h4></div>';
    $content .= '<div class="panel-body">';
    $content .= '<div class="row">';
    $content .= '<div class="col-md-6">';
    $content .= '<h5>'.__('Database Configuration').'</h5>';
    $content .= $bs->FormGroup('php_sql_config[host]','default','text',__('SQL Host'),'',__('your sql host'),'readonly','8',$raw_php_mysql_config['php_sql_config']['host']);
    $content .= $bs->FormGroup('php_sql_config[uname]','default','text',__('SQL Username'),'',__('your sql username'),'readonly','8',$raw_php_mysql_config['php_sql_config']['uname']);
    $content .= $bs->FormGroup('php_sql_config[pwd]','default','text',__('SQL Password'),'',__('your sql password'),'readonly','8',$raw_php_mysql_config['php_sql_config']['pwd']);
    $content .= $bs->FormGroup('php_sql_config[dbase]','default','text',__('SQL Database'),'',__('your sql database'),'readonly','8',$raw_php_mysql_config['php_sql_config']['dbase']);
    $content .= '<br/><a href="./?page=z-php-sql-restapi-generator" class="btn-sm btn btn-danger" >'.__('Edit').' (IMAB) REST-API Generator</a>';
    $content .= '</div>';
    $content .= '<div class="col-md-6">';
    $content .= '<h5>'.__('OneSignal Configuration').'</h5>';
    $content .= $bs->FormGroup('php_sql_config[app_id]','default','text','OneSignal AppID','',__('AppID from OneSignal Site'),'readonly','8',$_SESSION['PROJECT']['push']['app_id']);
    $content .= $bs->FormGroup('php_sql_config[app_key]','default','text','OneSignal AppKey','',__('AppKey from OneSignal Site'),'readonly','8',$_SESSION['PROJECT']['push']['app_key']);
    $content .= '<br/><a href="./?page=x-push-notifications" class="btn-sm btn btn-danger" >'.__('Edit').' (IMAB) Push Notification</a>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</form>';

    $code_php = create_php_code($column);
    // TODO: code --|-- debug
    if(JSM_DEBUG == true)
    {
        @mkdir(JSM_DEBUG_FOLDER.$_SESSION['PROJECT']['app']['prefix'].'\\',0777);
        @file_put_contents(JSM_DEBUG_FOLDER.$_SESSION['PROJECT']['app']['prefix'].'\web-admin.php',$code_php);
    }
    @file_put_contents('output/'.$_SESSION['PROJECT']['app']['prefix'].'/backend/php-sql/web-admin.php',$code_php);

    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading">';
    $content .= '<h5 class="panel-title">'.__('PHP Code').'</h5>';
    $content .= '</div>';
    $content .= '<div class="panel-body">';
    $content .= '<blockquote><p>'.__('Save this file example:').' <kbd>web-admin.php</kbd>, '.__('and click here for').' <a target="_blank" href="./output/'.$file_name.'/backend/php-sql/web-admin.php">Live Test</a></p></blockquote>';
    $content .= '<textarea id="code-php" name="code">'.htmlentities($code_php).'</textarea>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';

    $content .= "\r\n\r\n";
    $content .= '<div class="tab-pane" id="help">';

    $content .= '<blockquote class="blockquote blockquote-info">';
    $content .= '<ol>';
    $content .= '<li>'.__('Go to <a href="./?page=z-php-sql-restapi-generator">(IMAB) PHPSQL - RESTAPI Generator</a>, then complete until successful.').'</li>';
    $content .= '<li>'.__('Then, please complete the form on the <code>Code Generator</code> tab, and then click <code>Save Setting</code> button.').'</li>';
    $content .= '<li>'.__('Open a text editor (notepad, nano or vi) then copy the PHP Code on Code Tab and paste into your editor, then click save with filename <code>web-admin.php</code>.').'</li>';
    $content .= '<li>'.__('Upload <code>web-admin.php</code> to your server.').'</li>';
    $content .= '</ol>';
    $content .= '</blockquote>';

    $content .= '</div>';
    $content .= '</div>';
    $footer = '
<link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">
<script src="./templates/default/vendor/codemirror/lib/codemirror.js"></script>
<script src="./templates/default/vendor/codemirror/mode/clike/clike.js"></script>
<script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
<script src="./templates/default/vendor/codemirror/mode/php/php.js"></script>
<script src="./templates/default/vendor/codemirror/mode/sql/sql.js"></script>
<script type="text/javascript">
  var editor = CodeMirror.fromTextArea(document.getElementById("code-php"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true
  });
  var editor = CodeMirror.fromTextArea(document.getElementById("code-sql"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "text/x-mysql",
        indentUnit: 4,
        indentWithTabs: true
  });
</script>
';
} else
{
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading">';
    $content .= '<h5 class="panel-title">PHP Code</h5>';
    $content .= '</div>';
    $content .= '<div class="panel-body">';
    $content .= 'Please select tables in <a href="./?page=z-php-sql-restapi-generator">PHPSQL - RESTAPI Generator</a>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';
}
$_table_column = array();
foreach($_SESSION['PROJECT']['tables'] as $tb)
{
    foreach($tb['cols'] as $col)
    {
        $_table_column[] = '#'.$tb['prefix'].' .input-'.$col['title'];
    }
}

$footer .= '<script type="text/javascript">';
$footer .= '
var tags_vars = '.json_encode($_table_column).';

var stringMatcher = function(strs)
{
	return function findMatches(q, cb)
	{
		var matches, substringRegex;
		matches = [];
		substrRegex = new RegExp(q, "i");
		$.each(strs, function(i, str)
		{
			if (substrRegex.test(str))
			{
				matches.push(str);
			}
		});
		cb(matches);
	};
};
    
$("#php_sql_config_type_datetime_,#php_sql_config_type_date_,#php_sql_config_type_tags_").tagsinput({
  typeaheadjs: {
    source: stringMatcher(tags_vars)
  }
}
);

';
$footer .= '</script>';

// TODO: LAYOUT
$template->demo_url = $out_path.'/www/#/';
$template->title = $template->base_title.' | '.'Backend Tools -&raquo; Web Admin Generator (PHP + SQL)';
$template->base_desc = 'PHPSQL - BACKEND Generator';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;


function create_php_code($new_fields)
{
    global $raw_php_mysql_config;
    $colorful = array(
        "primary",
        "success",
        "info",
        "warning",
        "danger");
    $icolorful = 0;
    global $_tables_used;
    $tables = $_SESSION['PROJECT']['tables'];

    if(!isset($_SESSION['PROJECT']['push']['plugin']))
    {
        $_SESSION['PROJECT']['push']['plugin'] = null;
    }

    $php = null;
    $php .= '<?php'."\r\n\r";
    $php .= "/**\r\n";
    $php .= " * @author ".$_SESSION['PROJECT']['app']['author_name']." <".$_SESSION['PROJECT']['app']['author_email'].">\r\n";
    $php .= " * @copyright ".$_SESSION['PROJECT']['app']['company']." ".date("Y")."\r\n";
    $php .= " * @package ".$_SESSION['PROJECT']['app']['prefix']."\r\n";
    $php .= " * \r\n";
    $php .= " * \r\n";
    $php .= " * Created using IMA Builder\r\n";
    $php .= " * http://codecanyon.net/item/ionic-mobile-app-builder/15716727\r\n";
    $php .= " */\r\n";
    $php .= "\r\n";
    $php .= "\r\n/** CONFIG:START **/";
    // TODO: php --|-- config
    $php .= "\r\n/** database **/";
    $php .= "\r\n".'$config["host"]'."\t\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['host']).'" ; '."\t\t".'//host';
    $php .= "\r\n".'$config["user"]'."\t\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['uname']).'" ; '."\t\t".'//Username SQL';
    $php .= "\r\n".'$config["pass"]'."\t\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['pwd']).'" ; '."\t\t".'//Password SQL';
    $php .= "\r\n".'$config["dbase"]'."\t\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['dbase']).'" ; '."\t\t".'//Database';
    $php .= "\r\n/** admin **/";
    $php .= "\r\n".'$config["email"]'."\t\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['user_email']).'" ; '."\t\t".'//email for login';
    $php .= "\r\n".'$config["password"]'."\t\t\t".'= "'.sha1($raw_php_mysql_config['php_sql_config']['user_password']).'" ; '."\t\t".'// sha1(password)';
    $php .= "\r\n".'$config["utf8"]'."\t\t\t".'= true; '."\t\t".'';
    $php .= "\r\n".'$config["theme"]'."\t\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['theme']).'" ;'."\t\t".'// theme name you can get here https://www.bootstrapcdn.com/bootswatch/';
    $php .= "\r\n".'$config["navbar"]'."\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['navbar']).'" ; '."\t\t".'// nav-bar or nav-stacked';

    $php .= "\r\n/** Fix Input Format **/";
    $php .= "\r\n".'$config["input_datetime"]'."\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['type_datetime']).'";';
    $php .= "\r\n".'$config["input_date"]'."\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['type_date']).'";';

    $php .= "\r\n/** file **/";
    $php .= "\r\n".'$config["image_allowed"]'."\t\t".'= array("jpeg", "jpg", "png", "gif");';
    $php .= "\r\n".'$config["media_allowed"]'."\t\t".'= array("mp3", "mp4", "avi", "wav");';
    $php .= "\r\n".'$config["file_allowed"]'."\t\t".'= array("zip");';
    $php .= "\r\n".'$config["kcfinder"]'."\t\t".'= false;'."\t\t".'// download kcfinder from from http://kcfinder.sunhater.com then unzip with filename `kcfinder`, no need configuration (the key folder name `kcfinder` is exist)';


    if($_SESSION['PROJECT']['push']['plugin'] == 'onesignal-cordova-plugin')
    {
        if(!isset($_SESSION['PROJECT']['push']['app_key']))
        {
            $_SESSION['PROJECT']['push']['app_key'] = '';
        }
        if(!isset($_SESSION['PROJECT']['push']['app_id']))
        {
            $_SESSION['PROJECT']['push']['app_id'] = '';
        }
        $php .= "\r\n/** push notification **/";
        $php .= "\r\n".'$config["onesignal-appid"]'."\t\t\t".'= "'.$_SESSION['PROJECT']['push']['app_id'].'" ; '."\t\t".'//Your OneSignal AppId, available in OneSignal';
        $php .= "\r\n".'$config["onesignal-appkey"]'."\t\t\t".'= "'.$_SESSION['PROJECT']['push']['app_key'].'" ; '."\t\t".'//Your OneSignal AppKey';
    }

    $php .= "\r\n/** CONFIG:END **/";
    $php .= "\r\n";
    $php .= "\r\n";
    $php .= "\r\n/** LANGGUAGE:START **/";
    if($_SESSION['PROJECT']['push']['plugin'] == 'onesignal-cordova-plugin')
    {
        $php .= "\r\ndefine(\"LANG_PUSH_NOTIFICATION\",\"Push Notifications\");";
    }
    foreach($tables as $table)
    {
        if(in_array($table['prefix'],$_tables_used))
        {
            $langs[strtoupper($table['prefix'])] = $table['prefix'];
        }
    }
    foreach(array_keys($new_fields) as $key)
    {
        $fields = $new_fields[$key];
        foreach($fields as $field)
        {
            $langs[strtoupper(str2SQL($field['column_label']))] = str2SQL(html_entity_decode($field['column_label']));
        }
    }
    foreach($langs as $lang)
    {
        $php .= "\r\ndefine(\"LANG_".strtoupper($lang)."\",\"".ucwords(str_replace("_"," ",$lang))."s\");";
    }


    $php .= "\r\n/** default constant **/";
    $php .= "\r\nif(!defined(\"LANG_FILE_BROWSER\")){";
    $php .= "\r\n\tdefine(\"LANG_FILE_BROWSER\",\"File Browser\");";
    $php .= "\r\n}";

    $php .= "\r\nif(!defined(\"LANG_IMAGES\")){";
    $php .= "\r\n\tdefine(\"LANG_IMAGES\",\"Images\");";
    $php .= "\r\n}";

    $php .= "\r\nif(!defined(\"LANG_FILES\")){";
    $php .= "\r\n\tdefine(\"LANG_FILES\",\"Files\");";
    $php .= "\r\n}";

    $php .= "\r\nif(!defined(\"LANG_MEDIAS\")){";
    $php .= "\r\n\tdefine(\"LANG_MEDIAS\",\"Media\");";
    $php .= "\r\n}";

    $php .= "\r\nif(!defined(\"LANG_HOME\")){";
    $php .= "\r\n\tdefine(\"LANG_HOME\",\"Home\");";
    $php .= "\r\n}";

    $php .= "\r\nif(!defined(\"LANG_ADD\")){";
    $php .= "\r\n\tdefine(\"LANG_ADD\",\"Add\");";
    $php .= "\r\n}";

    $php .= "\r\nif(!defined(\"LANG_DELETE\")){";
    $php .= "\r\n\tdefine(\"LANG_DELETE\",\"Delete\");";
    $php .= "\r\n}";

    $php .= "\r\nif(!defined(\"LANG_LIST\")){";
    $php .= "\r\n\tdefine(\"LANG_LIST\",\"List\");";
    $php .= "\r\n}";

    $php .= "\r\nif(!defined(\"LANG_EDIT\")){";
    $php .= "\r\n\tdefine(\"LANG_EDIT\",\"Edit\");";
    $php .= "\r\n}";

    $php .= "\r\nif(!defined(\"LANG_MORE\")){";
    $php .= "\r\n\tdefine(\"LANG_MORE\",\"More\");";
    $php .= "\r\n}";

    $php .= "\r\nif(!defined(\"LANG_PUSH_NOTIFICATION\")){";
    $php .= "\r\n\tdefine(\"LANG_PUSH_NOTIFICATION\",\"Push Notifications\");";
    $php .= "\r\n}";


    $php .= "\r\n/** LANGGUAGE:END **/";
    $php .= "\r\n";
    $php .= "\r\n";
    $php .= "\r\n";

    $php .= ""."session_start();\r\n";


    $php .= "".'if($config["input_datetime"] == ""){'."\r\n";
    $php .= "\t".'$config["input_datetime"]=".mydatetimepicker";'."\r\n";
    $php .= "".'}'."\r\n";
    $php .= "".'if($config["input_date"] == ""){'."\r\n";
    $php .= "\t".'$config["input_date"]=".mydatepicker";'."\r\n";
    $php .= "".'}'."\r\n";
    
    $php .= "".'$_SESSION["KCFINDER"]["disabled"] = true; //terminate filebrowser'."\r\n";
    $php .= "".'if(!isset($_SESSION["IS_ADMIN"])){'."\r\n";
    $php .= "\t".'$_SESSION["IS_ADMIN"] = false;'."\r\n";
    $php .= "".'}'."\r\n";
    $php .= "".'if(file_exists("kcfinder/browse.php")){'."\r\n";
    $php .= "\r\n".'$config["kcfinder"]'."\t\t".'= true;'."\r\n";
    $php .= "".'}'."\r\n";
    $php .= "".'if($config["navbar"] == "nav-bar"){'."\r\n";
    $php .= "\t".'$sidebaleft = 12;'."\r\n";
    $php .= "\t".'$sidebaright = 12;'."\r\n";
    $php .= "".'}else{'."\r\n";
    $php .= "\t".'$config["navbar"] = "nav-stacked";'."\r\n";
    $php .= "\t".'$sidebaleft = 3;'."\r\n";
    $php .= "\t".'$sidebaright = 9;'."\r\n";
    $php .= "".'}'."\r\n";
    $php .= ""."//filebrowser enable if admin\r\n";
    $php .= "".'if($_SESSION["IS_ADMIN"] == true){'."\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"] = array();'."\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"]["disabled"] = false;'."\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"]["cookieDomain"] = parse_url($_SERVER["HTTP_HOST"],PHP_URL_HOST);'."\r\n";

    $php .= "\t".'$_SESSION["KCFINDER"]["uploadURL"] = "../media";'."\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"]["filenameChangeChars"] = array(" "=>"-",":"=>".");'."\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"]["dirnameChangeChars"] = array(" "=>"-",":"=>".");'."\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"]["denyUpdateCheck"] = true;'."\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"]["denyExtensionRename"] = true;'."\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"]["types"]["media"] = implode(" ",$config["media_allowed"]);'."\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"]["types"]["image"] = implode(" ",$config["image_allowed"]);'."\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"]["types"]["file"] = implode(" ",$config["file_allowed"]);'."\r\n";
    $php .= "".'}else{'."\r\n";
    $php .= ""."//terminate filebrowser\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"]["disabled"] = true;'."\r\n";
    $php .= "".'}'."\r\n";
    $php .= "".'$js_for_relation ="";'."\r\n";
    $php .= "\t".'$get_dir = explode("/", $_SERVER["PHP_SELF"]);'."\r\n";
    $php .= "\t".'unset($get_dir[count($get_dir)-1]);'."\r\n";
    $php .= "\t".'$main_url = "http://" . $_SERVER["HTTP_HOST"] ;'."\r\n";
    $php .= "\t".'$full_url = $main_url . implode("/",$get_dir);'."\r\n";
    $php .= "".'if(isset($_POST["email"]) && isset($_POST["password"])){'."\r\n";
    $php .= "\t".'if(($_POST["email"]==$config["email"]) && ( sha1($_POST["password"]) ==$config["password"])){'."\r\n";
    $php .= "\t\t".'$_SESSION["IS_ADMIN"] = true;'."\r\n";
    $php .= "\t\t".'header("Location: ?login=ok");'."\r\n";
    $php .= "\t".'}else{'."\r\n";
    $php .= "\t\t".'header("Location: ?login=error");'."\r\n";
    $php .= "\t".'}'."\r\n";
    $php .= "".'}'."\r\n";
    $php .= "".'if(!isset($_GET["table"])){'."\r\n";
    $php .= "\t".'$_GET["table"] = "home";'."\r\n";
    $php .= "".'}'."\r\n";
    $php .= "".'if(!isset($_GET["login"])){'."\r\n";
    $php .= "\t".'$_GET["login"] = "ok";'."\r\n";
    $php .= "".'}'."\r\n";
    $php .= "".'if(isset($_GET["logout"])){'."\r\n";
    $php .= "\t".'$_SESSION["IS_ADMIN"] = false;'."\r\n";
    $php .= "\t".'$_SESSION["KCFINDER"]["disabled"] = true; //terminate filebrowser'."\r\n";
    $php .= "\t".'header("Location: ?login=reset");'."\r\n";
    $php .= "".'}'."\r\n";
    $php .= "".'$tags_html = $error_notice = null;'."\r\n";
    $php .= "".'if($_SESSION["IS_ADMIN"]==true){'."\r\n";
    $php .= "\t".'/** connect to mysql **/'."\r\n";
    $php .= "\t".'$mysql = new mysqli($config["host"], $config["user"], $config["pass"], $config["dbase"]);'."\r\n";
    $php .= "\t".'if (mysqli_connect_errno()){'."\r\n";
    $php .= "\t\t".'die(mysqli_connect_error());'."\r\n";
    $php .= "\t".'}'."\r\n";
    $php .= "\t\r\n";

    $php .= "\t".'if($config["utf8"]==true){'."\r\n";
    $php .= "\t\t".'$mysql->set_charset("utf8");'."\r\n";
    $php .= "\t".'}'."\r\n";
    $php .= "\t\r\n";

    $php .= "\t".'/** prepare notice **/'."\r\n";
    $php .= "\t".'$notice = null;'."\r\n";
    $php .= "\t\r\n";
    $php .= "\t".'/** no action **/'."\r\n";
    $php .= "\t".'if(!isset($_GET["action"])){'."\r\n";
    $php .= "\t\t".'$_GET["action"] = "list";'."\r\n";
    $php .= "\t".'}'."\r\n";
    $php .= "\t\r\n";
    $icons = new jsmIonicon();
    foreach($icons->iconList() as $ionicon)
    {
        $icon_list[] = $ionicon['var'];
    }
    $php .= "\t".'/** create ionicon **/'."\r\n";
    $php .= "\t".'$ionicons = "'.implode(',',$icon_list).'";'."\r\n";
    $php .= "\t".'$ionicon_dialog = null;'."\r\n";
    $php .= "\t".'$ionicon_dialog .= "<div id=\"ionicons\" class=\"modal fade\" aria-labelledby=\"Ionicons\" aria-hidden=\"true\">"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .= "<div class=\"modal-dialog\">"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .= "<div class=\"modal-content\">"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .= "<div class=\"modal-header\">"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .= "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>";'."\r\n";
    $php .= "\t".'$ionicon_dialog .= "<h4 class=\"modal-title\">Ionicons</h4>"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .= "</div>"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .= "<div class=\"modal-body\">"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .= "<div style=\"width:100%;height:360px;overflow-x:scroll;\">"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .= "<div class=\"width:99%;\" >"."\\r\\n";'."\r\n";
    $php .= "\t".'foreach(explode(",",$ionicons) as $icon){'."\r\n";
    $php .= "\t\t".'$ionicon_dialog .= "<div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1\"><a href=\"#ion-".$icon."\" onclick=\"ionicons(\'ion-".$icon."\');\" style=\"font-size:28px\" ><i class=\"ion ion-".$icon."\"></i></a></div>"."\\r\\n";'."\r\n";
    $php .= "\t".'}'."\r\n";
    $php .= "\t".'$ionicon_dialog .="</div>"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .="</div>"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .="</div>"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .="</div>"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .="</div>"."\\r\\n";'."\r\n";
    $php .= "\t".'$ionicon_dialog .="</div>"."\\r\\n";'."\r\n";
    $php .= "\t\r\n";

    $php .= "\t".'$tags_html .= "<div class=\"header\">" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<div class=\"container-fluid\">" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<ul class=\"nav nav-pills pull-right\">" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<li><a href=\"?logout\">Logout</a></li>" ;'."\r\n";
    $php .= "\t".'$tags_html .= "</ul>" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<h1 class=\"text-muted\">'.$_SESSION['PROJECT']['app']['name'].'</h1>" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<p>'.$_SESSION['PROJECT']['app']['description'].'</p>" ;'."\r\n";
    $php .= "\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t".'$tags_html .= "</div>" ;'."\r\n";

    $php .= "\t".'$tags_html .= "<div class=\"container-fluid\">" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<div class=\"row\">" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<div class=\"col-md-".$sidebaleft."\">" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<div class=\"panel panel-default\">" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<div class=\"panel-body\">" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<ul class=\"nav nav-pills ".$config["navbar"]."\">" ;'."\r\n";

    $php .= "\t".'if($_GET["table"]=="home"){'."\r\n";
    $php .= "\t\t".'$tags_html .= "<li class=\"active\"><a href=\'?table=home\'>".@LANG_HOME."</a></li>" ;'."\r\n";
    $php .= "\t".'}else{'."\r\n";
    $php .= "\t\t".'$tags_html .= "<li><a href=\'?table=home\'>".@LANG_HOME."</a></li>" ;'."\r\n";
    $php .= "\t".'}'."\r\n";

    foreach($tables as $table)
    {
        if(in_array($table['prefix'],$_tables_used))
        {
            $php .= "\t".'if($_GET["table"]=="'.$table['prefix'].'s"){'."\r\n";
            $php .= "\t\t".'$tags_html .= "<li class=\"active\"><a href=\'?table='.$table['prefix'].'s\'>". @LANG_'.strtoupper($table['prefix']).'."</a></li>" ;'."\r\n";
            $php .= "\t".'}else{'."\r\n";
            $php .= "\t\t".'$tags_html .= "<li><a href=\'?table='.$table['prefix'].'s\'>". @LANG_'.strtoupper($table['prefix']).'."</a></li>" ;'."\r\n";
            $php .= "\t".'}'."\r\n";
        }
    }
    $php .= "\t".'if($config["kcfinder"]==true){'."\r\n";
    $php .= "\t\t".'if($_GET["table"]=="filebrowser"){'."\r\n";
    $php .= "\t\t\t".'$tags_html .= "<li class=\"active\"><a href=\'?table=filebrowser\'>".@LANG_FILE_BROWSER."</a></li>" ;'."\r\n";
    $php .= "\t\t".'}else{'."\r\n";
    $php .= "\t\t\t".'$tags_html .= "<li><a href=\'?table=filebrowser\'>".@LANG_FILE_BROWSER."</a></li>" ;'."\r\n";
    $php .= "\t\t".'}'."\r\n";
    $php .= "\t".'}'."\r\n";

    if($_SESSION['PROJECT']['push']['plugin'] == 'onesignal-cordova-plugin')
    {
        $php .= "\t".'if($_GET["table"]=="push-notification"){'."\r\n";
        $php .= "\t\t".'$tags_html .= "<li class=\"active\"><a href=\'?table=push-notification\'>".@LANG_PUSH_NOTIFICATION."</a></li>" ;'."\r\n";
        $php .= "\t".'}else{'."\r\n";
        $php .= "\t\t".'$tags_html .= "<li><a href=\'?table=push-notification\'>".@LANG_PUSH_NOTIFICATION."</a></li>" ;'."\r\n";
        $php .= "\t".'}'."\r\n";
    }

    $php .= "\t".'$tags_html .= "</ul>" ;'."\r\n";
    $php .= "\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<div class=\"col-md-".$sidebaright."\">" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<div class=\"panel panel-default\">" ;'."\r\n";
    $php .= "\t".'$tags_html .= "<div class=\"panel-body\">" ;'."\r\n";

    $php .= "\t".'switch($_GET["table"]){'."\r\n";

    // TODO: php --|-- table - home
    $php .= "\t\t".'case "home":'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<h4 class=\"page-title\">'.$_SESSION['PROJECT']['app']['name'].'</h4>" ;'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<div class=\"row\">" ;'."\r\n";
    foreach(array_keys($new_fields) as $key)
    {
        $var_id = 'id';
        $fields = $new_fields[$key];
        foreach($fields as $field)
        {
            if($field['type'] == 'id')
            {
                $var_id = $field['column_name'];
            }
        }


        $table_name = $key;
        $php .= "\t\t\t\t\t".'$tags_html .= "<div class=\"col-md-4\">" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<div class=\"panel panel-'.$colorful[$icolorful].'\" >" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<div class=\"panel-heading\">" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<h4  class=\"panel-title\"><span class=\"glyphicon glyphicon-list-alt\"></span> ". @LANG_'.strtoupper(str2SQL(html_entity_decode($table_name))).' ."</h4>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "</div>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<div class=\"panel-body\" style=\"min-height: 150px;\">" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<ul class=\"list\">" ;'."\r\n";

        $php .= "\t\t\t\t\t".'/** fetch data from '.$table_name.' **/'."\r\n";
        $php .= "\t\t\t\t\t".'$sql_query = "SELECT * FROM `'.$table_name.'` ORDER BY `'.$var_id.'`  DESC LIMIT 0 , 5" ;'."\r\n";
        $php .= "\t\t\t\t\t".'if($result = $mysql->query($sql_query)){'."\r\n";
        $php .= "\t\t\t\t\t\t".'while ($data = $result->fetch_array()){'."\r\n";
        //$php .= "\t\t\t\t\t\t\t" . '$tags_html .= "<tr>" ;' . "\r\n";

        $found_to_trusted = $found_heading_1 = $found_text = false;


        foreach($fields as $field)
        {
            if(!isset($field['type']))
            {
                $field['type'] = null;
            }
            if($field['type'] == 'heading-1')
            {
                $found_to_trusted = $found_heading_1 = $found_text = true;
                $php .= "\t\t\t\t\t\t\t".'$tags_html .= "<li>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</li>" ;'."\r\n";
            }
        }

        if($found_to_trusted == false)
        {
            foreach($fields as $field)
            {
                if(!isset($field['type']))
                {
                    $field['type'] = null;
                }
                if($field['type'] == 'to_trusted')
                {
                    $found_to_trusted = $found_heading_1 = $found_text = true;
                    $php .= "\t\t\t\t\t\t\t".'$tags_html .= "<li>" . htmlentities(stripslashes(substr($data["'.$field['column_name'].'"],0,50))) . "...</li>" ;'."\r\n";
                }
            }
        }

        if($found_text == false)
        {
            foreach($fields as $field)
            {
                if(!isset($field['type']))
                {
                    $field['type'] = null;
                }
                if($field['type'] == 'text')
                {
                    $found_to_trusted = $found_heading_1 = $found_text = true;
                    $php .= "\t\t\t\t\t\t\t".'$tags_html .= "<li>" . htmlentities(stripslashes(substr($data["'.$field['column_name'].'"],0,50))) . "...</li>" ;'."\r\n";
                }
            }
        }

        $php .= "\t\t\t\t\t\t".'}'."\r\n";
        $php .= "\t\t\t\t\t".'}'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "</ul>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "</div>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<div class=\"panel-footer text-right\"><a href=\"?table='.$table_name.'s\" class=\"btn btn-sm btn-'.$colorful[$icolorful].'\">".LANG_MORE."</a></div>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "</div>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "</div>" ;'."\r\n";
        $icolorful++;
        if($icolorful == (count($colorful)))
        {
            $icolorful = 0;
        }
    }
    $php .= "\t\t\t\t\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t\t".'break;'."\r\n";
    foreach(array_keys($new_fields) as $key)
    {
        $fields = $new_fields[$key];
        $table_name = $key;
        $form = null;
        $form = new html_form($table_name);
        foreach($fields as $field)
        {
            $form->addField($field);
        }

        $php .= "\t\t".'// TO'.'DO: '.ucwords($table_name.'s')."\r\n";
        $php .= "\t\t".'case "'.$table_name.'s":'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<h4>". @LANG_'.strtoupper(str2SQL(html_entity_decode($table_name))).' ."</h4>" ;'."\r\n";
        $php .= "\t\t\t\t".'switch($_GET["action"]){'."\r\n";
        // TODO: php --|-- table - list
        $php .= "\t\t\t\t\t".'// TO'.'DO: ---- listing'."\r\n";
        $php .= "\t\t\t\t\t".'case "list":'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<ul class=\"nav nav-tabs\">" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<li class=\"active\"><a href=\"?table='.$table_name.'s\">".@LANG_LIST."</a></li>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<li><a href=\"?table='.$table_name.'s&action=add\">".@LANG_ADD."</a></li>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "</ul>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<br/>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$tags_html .= "<div class=\"table-responsive\">" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$table_html = "<table id=\"datatable\" class=\"table table-striped table-hover\">" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$table_html .= "<thead>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$table_html .= "<tr>" ;'."\r\n";
        $var_id = 'id';
        $found_id = false;
        foreach($fields as $field)
        {
            if(!isset($field['type']))
            {
                $field['type'] = null;
            }
            if($field['type'] != 'id')
            {
                $php .= "\t\t\t\t\t".'$table_html .= "<th>".@LANG_'.strtoupper(str2SQL(html_entity_decode($field['column_label']))).'."</th>" ;'."\r\n";
            } else
            {
                if($found_id == false)
                {
                    $var_id = $field['column_name'];
                    $found_id = true;
                }
            }
        }
        $php .= "\t\t\t\t\t".'$table_html .= "<th style=\"width:100px;\">Action</th>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$table_html .= "</tr>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$table_html .= "</thead>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$table_html .= "<tbody>" ;'."\r\n";
        $php .= "\t\t\t\t\t".'/** fetch data from mysql **/'."\r\n";
        $php .= "\t\t\t\t\t".'$sql_query = "SELECT * FROM `'.$table_name.'`" ;'."\r\n";
        $php .= "\t\t\t\t\t".'if($result = $mysql->query($sql_query)){'."\r\n";
        $php .= "\t\t\t\t\t\t".'while ($data = $result->fetch_array()){'."\r\n";
        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<tr>" ;'."\r\n";
        foreach($fields as $field)
        {
            if(!isset($field['type']))
            {
                $field['type'] = null;
            }
            if($field['type'] != 'id')
            {
                switch($field['type'])
                {
                        // TODO: php --|-- table - list - colum type
                    case 'heading-1':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'heading-2':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'heading-3':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'heading-4':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'text':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'images':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td><img src=\"" . $data["'.$field['column_name'].'"] . "\" alt=\"#\" width=\"64\" height=\"64\" /></td>" ;'."\r\n";
                        break;
                    case 'video':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'ytube':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'gmap':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'webview':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'appbrowser':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'audio':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'share_link':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'link':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'icon':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td><span class=\"icon " . $data["'.$field['column_name'].'"] . "\" style=\"font-size:28px\"></span></td>" ;'."\r\n";
                        break;
                    case 'paragraph':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'slidebox':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'to_trusted':
                        $php .= "\t\t\t\t\t\t\t".'$content_html = substr( strip_tags($data["'.$field['column_name'].'"]),0,50);'."\r\n";
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . stripslashes($content_html). "...</td>" ;'."\r\n";
                        break;
                    case 'rating':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'as_username':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'as_password':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;

                    case 'number':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td class=\"text-right\">" .  (int)$data["'.$field['column_name'].'"]. "</td>" ;'."\r\n";
                        break;

                    case 'float':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td class=\"text-right\">" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;

                    case 'app_email':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;

                    case 'app_sms':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;

                    case 'app_call':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;

                    case 'app_geo':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;

                    case 'date':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'datetime':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'date_php':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'datetime_php':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;
                    case 'datetime_string':
                        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" . htmlentities(stripslashes($data["'.$field['column_name'].'"])) . "</td>" ;'."\r\n";
                        break;

                }
            }
        }
        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<td>" ;'."\r\n";
        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<div class=\"btn-group\" >" ;'."\r\n";

        if($_SESSION['PROJECT']['push']['plugin'] == 'onesignal-cordova-plugin')
        {
            if(!isset($_SESSION['PROJECT']['push']['app_key']))
            {
                $_SESSION['PROJECT']['push']['app_key'] = '';
            }
            if(!isset($_SESSION['PROJECT']['push']['app_id']))
            {
                $_SESSION['PROJECT']['push']['app_id'] = '';
            }
            $php .= "\t\t\t\t\t\t\t".'if($config["onesignal-appkey"] !== ""){'."\r\n";
            $php .= "\t\t\t\t\t\t\t\t".'$table_html .= "<a target=\"_blank\" class=\"btn btn-sm btn-info\" href=\"?table=push-notification&specific='.$table_name.'_singles/". $data["'.$var_id.'"]. "\"><span class=\"glyphicon glyphicon-bell\"></span></a> " ;'."\r\n";
            $php .= "\t\t\t\t\t\t\t".'}'."\r\n";
        }
        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<a class=\"btn btn-sm btn-warning\" href=\"?table='.$table_name.'s&action=edit&id=". $data["'.$var_id.'"]. "\"><span class=\"glyphicon glyphicon-pencil\"></span></a> " ;'."\r\n";
        $php .= "\t\t\t\t\t\t\t".'$table_html .= "<a onclick=\"return confirm(\'Are you sure you want to delete ID#". $data["'.$var_id.'"]. "\')\" class=\"btn btn-sm btn-danger\" href=\"?table='.$table_name.'s&action=delete&id=". $data["'.$var_id.'"]. "\"><span class=\"glyphicon glyphicon-trash\"></span></a> " ;'."\r\n";


        $php .= "\t\t\t\t\t\t\t".'$table_html .= "</div>" ;'."\r\n";
        $php .= "\t\t\t\t\t\t\t".'$table_html .= "</td>" ;'."\r\n";
        $php .= "\t\t\t\t\t\t\t".'$table_html .= "</tr>" ;'."\r\n";
        $php .= "\t\t\t\t\t\t\t".'$table_html .= "\\r\\n" ;'."\r\n";
        $php .= "\t\t\t\t\t\t".'}'."\r\n";
        $php .= "\t\t\t\t\t\t".'$result->close();'."\r\n";
        $php .= "\t\t\t\t\t".'}'."\r\n";
        $php .= "\t\t\t\t".'$table_html .= "</tbody>" ;'."\r\n";
        $php .= "\t\t\t\t".'$table_html .= "</table>" ;'."\r\n";
        $php .= "\t\t\t\t".'$table_html .= "</div>" ;'."\r\n";

        $php .= "\t\t\t\t".'$tags_html .= $table_html;'."\r\n";
        $php .= "\t\t\t\t".'break;'."\r\n";
        $php .= "\t\t\t".'case "add":'."\r\n";
        // TODO: php --|-- table - add
        $php .= "\t\t\t\t".'// TO'.'DO: ---- add'."\r\n";
        $php .= "\t\t\t\t".'$tags_html .= "<ul class=\"nav nav-tabs\">" ;'."\r\n";
        $php .= "\t\t\t\t".'$tags_html .= "<li><a href=\"?table='.$table_name.'s&action=list\">".@LANG_LIST."</a></li>" ;'."\r\n";
        $php .= "\t\t\t\t".'$tags_html .= "<li class=\"active\"><a href=\"?table='.$table_name.'s&action=add\">".@LANG_ADD."</a></li>" ;'."\r\n";
        $php .= "\t\t\t\t".'$tags_html .= "</ul>" ;'."\r\n";
        $php .= "\t\t\t\t".'$tags_html .= "<br/>" ;'."\r\n";
        $php .= "\t\r\n";
        $php .= "\t\t\t\t".'/** push button **/'."\r\n";
        $php .= "\t\t\t\t".'if(isset($_POST["add"])){'."\r\n";
        $php .= "\t\r\n";
        $php .= "\t\t\t\t\t".'/** avoid error **/'."\r\n";
        foreach($fields as $field)
        {
            if(!isset($field['type']))
            {
                $field['type'] = null;
            }
            if($field['type'] != 'id')
            {
                $php .= "\t\t\t\t\t".'$data_'.$field['column_name'].' = "";'."\r\n";
            }
        }
        $php .= "\t\r\n";
        $php .= "\t\t\t\t\t".'/** get input **/'."\r\n";
        foreach($fields as $field)
        {
            if(!isset($field['type']))
            {
                $field['type'] = null;
            }
            if($field['type'] != 'id')
            {
                $php .= "\t\t\t\t\t".'if(isset($_POST["'.$field['column_name'].'"])){'."\r\n";
                $php .= "\t\t\t\t\t\t".'$data_'.$field['column_name'].' = addslashes($_POST["'.$field['column_name'].'"]);'."\r\n";
                $php .= "\t\t\t\t\t".'}'."\r\n";
            }
            if($field['type'] == 'images')
            {
                $php .= "\t\t\t\t".'if(isset($_FILES["'.$field['column_name'].'-upload"]["name"])){'."\r\n";
                $php .= "\t\t\t\t\t".'if($_FILES["'.$field['column_name'].'-upload"]["name"]!=""){'."\r\n";
                $php .= "\t\t\t\t\t\t".'$ext = pathinfo($_FILES["'.$field['column_name'].'-upload"]["name"],PATHINFO_EXTENSION);'."\r\n";
                $php .= "\t\t\t\t\t\t".'$uploadfile =  "media/image/". sha1(time()).".".$ext;'."\r\n";
                $php .= "\t\t\t\t\t\t".'$uploadtemp =  $_FILES["'.$field['column_name'].'-upload"]["tmp_name"];'."\r\n";
                $php .= "\t\t\t\t\t\t".'$mimetype = getimagesize($uploadtemp);'."\r\n";
                $php .= "\t\t\t\t\t\t".'if(!is_dir(dirname(__FILE__)."/media/image/")) {'."\r\n";
                $php .= "\t\t\t\t\t\t\t".'mkdir( dirname(__FILE__)."/media/image/",0777, true);'."\r\n";
                $php .= "\t\t\t\t\t\t".'}'."\r\n";
                $php .= "\t\t\t\t\t\t\t".'if (in_array(strtolower($ext),$config["image_allowed"])){'."\r\n";
                $php .= "\t\t\t\t\t\t\t\t".'if(preg_match("/image/",$mimetype["mime"])){'."\r\n";
                $php .= "\t\t\t\t\t\t\t\t\t".'move_uploaded_file($uploadtemp,dirname(__FILE__)."/" .$uploadfile);'."\r\n";
                $php .= "\t\t\t\t\t\t\t\t\t".'$data_'.$field['column_name'].' =  $full_url ."/". $uploadfile;'."\r\n";
                $php .= "\t\t\t\t\t\t\t\t".'}'."\r\n";
                $php .= "\t\t\t\t\t\t\t".'}'."\r\n";
                $php .= "\t\t\t\t\t\t".'}'."\r\n";
                $php .= "\t\t\t\t\t".'}'."\r\n";
            }
        }
        $_field = $_postdata = null;
        foreach($fields as $field)
        {
            if(!isset($field['type']))
            {
                $field['type'] = null;
            }
            if($field['type'] != 'id')
            {
                $_field[] = '`'.$field['column_name'].'`';
                $_postdata[] = '\'$data_'.$field['column_name'].'\'';
            }
        }
        $php .= "\t\r\n";
        $php .= "\t\t\t\t\t".'/** prepare save to mysql **/'."\r\n";
        $php .= "\t\t\t\t\t".'$sql_query = "INSERT INTO `'.$table_name.'` ('.implode(',',$_field).') VALUES ('.implode(',',$_postdata).')" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$stmt = $mysql->prepare($sql_query);'."\r\n";
        $php .= "\t\t\t\t\t".'$stmt->execute();'."\r\n";
        $php .= "\t\t\t\t\t".'$stmt->close();'."\r\n";
        $php .= "\t\t\t\t\t".'header("Location: ?table='.$table_name.'s&action=list");'."\r\n";
        $php .= "\t\t\t\t".'}'."\r\n";
        $php .= "\t\r\n";
        $php .= "\t\r\n";
        $php .= "\t\t\t\t".'/** Create Form **/'."\r\n";
        $php .= "\t\t\t\t".'$tags_html .= \''."\r\n";
        $php .= "\t\t\t\t".'<form id="'.$table_name.'" action="" method="post" enctype="multipart/form-data">'."\r\n";
        $php .= "\t\t\t\t\t".$form->Code();
        $php .= "\t\t\t\t\t".'<div class="form-group">'."\r\n";
        $php .= "\t\t\t\t\t\t".'<label for="add"></label>'."\r\n";
        $php .= "\t\t\t\t\t\t".'<input class="btn btn-primary" type="submit" name="add" />'."\r\n";
        $php .= "\t\t\t\t\t".'</div>'."\r\n";
        $php .= "\t\t\t\t".'</form>'."\r\n";
        $php .= "\t\t\t\t".'\';'."\r\n";

        // TODO: TODO NEXT VERSION

        // TODO: CREATE LIST INPUT THEN TYPE

        $php .= "\t\t\t".'break;'."\r\n";
        // TODO: php --|-- table - edit
        $php .= "\t\t".'case "edit":'."\r\n";
        $php .= "\t\t\t".'// TO'.'DO: ---- edit'."\r\n";
        $php .= "\t\t\t".'$tags_html .= "<ul class=\"nav nav-tabs\">" ;'."\r\n";
        $php .= "\t\t\t".'$tags_html .= "<li><a href=\"?table='.$table_name.'s&action=list\">".@LANG_LIST."</a></li>" ;'."\r\n";
        $php .= "\t\t\t".'$tags_html .= "<li><a href=\"?table='.$table_name.'s&action=add\">".@LANG_ADD."</a></li>" ;'."\r\n";
        $php .= "\t\t\t".'$tags_html .= "<li class=\"active\"><a href=\"#\">".@LANG_EDIT."</a></li>" ;'."\r\n";
        $php .= "\t\t\t".'$tags_html .= "</ul>" ;'."\r\n";
        $php .= "\t\t\t".'$tags_html .= "<br/>" ;'."\r\n";
        $php .= "\t\t\t".'/** avoid error **/'."\r\n";
        $php .= "\t\t\t".'if(isset($_GET["id"])){'."\r\n";
        $php .= "\t\t\t\t".'/** fix security **/'."\r\n";
        $php .= "\t\t\t\t".'$entry_id = (int)$_GET["id"];'."\r\n";
        $php .= "\t\r\n";
        $php .= "\t\t\t\t".'/** avoid blank field **/'."\r\n";
        foreach($fields as $field)
        {
            if(!isset($field['type']))
            {
                $field['type'] = null;
            }
            if($field['type'] != 'id')
            {
                $php .= "\t\t\t\t".'$data_'.$field['column_name'].' = "";'."\r\n";
            }
        }
        $php .= "\t\r\n";
        $php .= "\t\t\t\t".'/** push button **/'."\r\n";
        $php .= "\t\t\t\t".'if(isset($_POST["edit"])){'."\r\n";
        $php .= "\t\t\t\t\t".'/** get input **/'."\r\n";
        foreach($fields as $field)
        {
            if(!isset($field['type']))
            {
                $field['type'] = null;
            }
            if($field['type'] != 'id')
            {
                $php .= "\t\t\t\t\t".'if(isset($_POST["'.$field['column_name'].'"])){'."\r\n";
                $php .= "\t\t\t\t\t\t".'$data_'.$field['column_name'].' = addslashes($_POST["'.$field['column_name'].'"]);'."\r\n";
                $php .= "\t\t\t\t\t".'}'."\r\n";
            }
            if($field['type'] == 'images')
            {
                $php .= "\t\t\t\t".'if(isset($_FILES["'.$field['column_name'].'-upload"]["name"])){'."\r\n";
                $php .= "\t\t\t\t\t".'if($_FILES["'.$field['column_name'].'-upload"]["name"]!=""){'."\r\n";
                $php .= "\t\t\t\t\t\t".'$ext = pathinfo($_FILES["'.$field['column_name'].'-upload"]["name"],PATHINFO_EXTENSION);'."\r\n";
                $php .= "\t\t\t\t\t\t".'$uploadfile =  "media/image/". sha1(time()).".".$ext;'."\r\n";
                $php .= "\t\t\t\t\t\t".'$uploadtemp =  $_FILES["'.$field['column_name'].'-upload"]["tmp_name"];'."\r\n";
                $php .= "\t\t\t\t\t\t".'$mimetype = getimagesize($uploadtemp);'."\r\n";
                $php .= "\t\t\t\t\t\t".'if(!is_dir(dirname(__FILE__)."/media/image/")) {'."\r\n";
                $php .= "\t\t\t\t\t\t\t".'mkdir( dirname(__FILE__)."/media/image/",0777, true);'."\r\n";
                $php .= "\t\t\t\t\t\t".'}'."\r\n";
                $php .= "\t\t\t\t\t\t\t".'if (in_array(strtolower($ext),$config["image_allowed"])){'."\r\n";
                $php .= "\t\t\t\t\t\t\t\t".'if(preg_match("/image/",$mimetype["mime"])){'."\r\n";
                $php .= "\t\t\t\t\t\t\t\t\t".'move_uploaded_file($uploadtemp,dirname(__FILE__)."/" .$uploadfile);'."\r\n";
                $php .= "\t\t\t\t\t\t\t\t\t".'$data_'.$field['column_name'].' =  $full_url."/". $uploadfile;'."\r\n";
                $php .= "\t\t\t\t\t\t\t\t".'}'."\r\n";
                $php .= "\t\t\t\t\t\t\t".'}'."\r\n";
                $php .= "\t\t\t\t\t\t".'}'."\r\n";
                $php .= "\t\t\t\t\t".'}'."\r\n";
            }
        }
        $_postdata = null;
        foreach($fields as $field)
        {
            if(!isset($field['type']))
            {
                $field['type'] = null;
            }
            if($field['type'] != 'id')
            {
                $_postdata[] = '`'.$field['column_name'].'` = \'$data_'.$field['column_name'].'\'';
            }
        }
        $php .= "\t\r\n";
        $php .= "\t\t\t\t\t".'/** update data to sql **/'."\r\n";
        $php .= "\t\t\t\t\t".'$sql_query = "UPDATE `'.$table_name.'` SET '.implode(',',$_postdata).' WHERE `'.$var_id.'`=$entry_id" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$stmt = $mysql->prepare($sql_query);'."\r\n";
        $php .= "\t\t\t\t\t".'$stmt->execute();'."\r\n";
        $php .= "\t\t\t\t\t".'$stmt->close();'."\r\n";
        $php .= "\t\t\t\t\t".'header("Location: ?table='.$table_name.'s&action=list");'."\r\n";
        $php .= "\t\t\t\t".'}'."\r\n";
        $php .= "\t\r\n";
        $php .= "\t\t\t".'/** fetch current data **/'."\r\n";
        $php .= "\t\t\t".'$sql_query = "SELECT * FROM `'.$table_name.'`  WHERE `'.$var_id.'`=$entry_id LIMIT 0,1" ;'."\r\n";
        $php .= "\t\t\t".'if($result = $mysql->query($sql_query)){'."\r\n";
        $php .= "\t\t\t\t".'while ($data = $result->fetch_array()){'."\r\n";
        $php .= "\t\t\t\t\t".'$rows[] = $data;'."\r\n";
        $php .= "\t\t\t\t".'}'."\r\n";
        $php .= "\t\t\t\t".'$result->close();'."\r\n";
        $php .= "\t\t\t".'}'."\r\n";
        $php .= "\t\r\n";
        $php .= "\t\t\t".'/** get single data **/'."\r\n";
        foreach($fields as $field)
        {
            if(!isset($field['type']))
            {
                $field['type'] = null;
            }
            if($field['type'] != 'id')
            {
                $php .= "\t\t\t".'if(isset($rows[0]["'.$field['column_name'].'"])){'."\r\n";
                $php .= "\t\t\t\t".'$data_'.$field['column_name'].' = stripslashes($rows[0]["'.$field['column_name'].'"]) ;'."\r\n";
                $php .= "\t\t\t".'}'."\r\n";
            }
        }
        $php .= "\t\r\n";
        $php .= "\t\t\t".'/** buat form edit **/'."\r\n";
        $php .= "\t\t\t".'$tags_html .= \''."\r\n";
        $php .= '<form action="" method="post" enctype="multipart/form-data" id="'.$table_name.'">'."\r\n";
        $php .= $form->Code(true);
        $php .= '<div class="form-group">'."\r\n";
        $php .= "\t".'<label for="edit"></label>'."\r\n";
        $php .= "\t".'<input class="btn btn-primary" type="submit" name="edit" />'."\r\n";
        $php .= '</div>'."\r\n";
        $php .= '</form>'."\r\n";
        $php .= "\t\t\t\t".'\';'."\r\n";
        $php .= "\t\t\t".'};'."\r\n";
        $php .= "\t\t\t".'break;'."\r\n";
        // TODO: php --|-- table - delete
        $php .= "\t\t\t".'case "delete":'."\r\n";
        $php .= "\t\t\t".'// TO'.'DO: ---- delete'."\r\n";
        $php .= "\t\t\t\t".'/** avoid error **/'."\r\n";
        $php .= "\t\t\t\t".'if(isset($_GET["id"])){'."\r\n";
        $php .= "\t\t\t\t\t".'/** fix security **/'."\r\n";
        $php .= "\t\t\t\t\t".'$entry_id = (int)$_GET["id"];'."\r\n";
        $php .= "\t\t\t\t\t".'/** delete item in sql **/'."\r\n";
        $php .= "\t\t\t\t\t".'$sql_query = "DELETE FROM `'.$table_name.'` WHERE `'.$var_id.'`=$entry_id" ;'."\r\n";
        $php .= "\t\t\t\t\t".'$stmt = $mysql->prepare($sql_query);'."\r\n";
        $php .= "\t\t\t\t\t".'$stmt->execute();'."\r\n";
        $php .= "\t\t\t\t\t".'$stmt->close();'."\r\n";
        $php .= "\t\t\t\t\t".'header("Location: ?table='.$table_name.'s&action=list");'."\r\n";
        $php .= "\t\t\t\t".'};'."\r\n";
        $php .= "\t\t\t\t".'break;'."\r\n";
        $php .= "\t\t\t".'}'."\r\n";
        $php .= "\t\t\t".'break;'."\r\n";
    }

    // TODO: php --|-- table - push-notifications
    $php .= "\t\t".'// TO'.'DO: Push Notification'."\r\n";
    $php .= "\t\t".'case "push-notification":'."\r\n";
    $php .= "\t\t\t".'$specific_page = null ;'."\r\n";
    $php .= "\t\t\t".'if(isset($_GET["specific"])){'."\r\n";
    $php .= "\t\t\t\t".'$specific_page = htmlentities($_GET["specific"]);'."\r\n";
    $php .= "\t\t\t".'}'."\r\n";
    $php .= "\t\t\t".'$tags_html .= "<h4 class=\"page-title\">".@LANG_PUSH_NOTIFICATION."</h4>" ;'."\r\n";

    $php .= "\t\t\t".'if(!ini_get("allow_url_fopen")) {'."\r\n";
    $php .= "\t\t\t\t".'$tags_html .="<p class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><strong>Error!</strong> The PHP allow_url_fopen setting is disabled, please edit your <a target=\"_blank\" class=\"btn btn-danger btn-xs\" href=\"http://php.net/allow-url-fopen\">php.ini</a></p>";'."\r\n";
    $php .= "\t\t\t".'}'."\r\n";

    $php .= "\t\t\t".'if(!function_exists("curl_version")) {'."\r\n";
    $php .= "\t\t\t\t".'$tags_html .="<p class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><strong>Error!</strong> cURL is NOT installed in your PHP installation</p>";'."\r\n";
    $php .= "\t\t\t".'}'."\r\n";

    $php .= "\t\t\t".'if(isset($_POST["push"])){'."\r\n";
    $php .= "\t\t\t\t".'$content = array("en" => $_POST["message"]);'."\r\n";
    $php .= "\t\t\t\t".'$fields = array("app_id" => $config["onesignal-appid"],"included_segments" => array("All"),"data" => array("page" =>  $_POST["page"] ), "contents" => $content);'."\r\n";
    $php .= "\t\t\t\t".'$fields = json_encode($fields);'."\r\n";
    $php .= "\t\t\t\t".'$ch = curl_init();'."\r\n";
    $php .= "\t\t\t\t".'curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");'."\r\n";
    $php .= "\t\t\t\t".'curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8","Authorization: Basic " . $config["onesignal-appkey"]));'."\r\n";
    $php .= "\t\t\t\t".'curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);'."\r\n";
    $php .= "\t\t\t\t".'curl_setopt($ch, CURLOPT_HEADER, false);'."\r\n";
    $php .= "\t\t\t\t".'curl_setopt($ch, CURLOPT_POST, true);'."\r\n";
    $php .= "\t\t\t\t".'curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);'."\r\n";
    $php .= "\t\t\t\t".'curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);'."\r\n";
    $php .= "\t\t\t\t".'$response = json_decode(curl_exec($ch),true);'."\r\n";
    $php .= "\t\t\t\t".'curl_close($ch);'."\r\n";
    $php .= "\t\t\t\t".'if(isset($response["errors"][0])){'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<div class=\"alert alert-dismissible alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>".$response["errors"][0]."</div>";'."\r\n";
    $php .= "\t\t\t\t".'}else{'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<div class=\"alert alert-dismissible alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>ID #".$response["id"]." with ".$response["recipients"]." recipients</div>";'."\r\n";

    $php .= "\t\t\t\t".'}'."\r\n";
    $php .= "\t\t\t".'}'."\r\n";

    $php .= "\t\t\t\t".'/** Create Form **/'."\r\n";
    $php .= "\t\t\t\t".'$tags_html .= \''."\r\n";
    $php .= "\t\t\t\t".'<form action="" method="post" enctype="multipart/form-data">'."\r\n";

    $php .= "\t\t\t\t\t".'<div class="form-group">'."\r\n";
    $php .= "\t\t\t\t\t\t".'<label for="message">Message</label>'."\r\n";
    $php .= "\t\t\t\t\t\t".'<textarea class="form-control" name="message" ></textarea>'."\r\n";
    $php .= "\t\t\t\t\t".'</div>'."\r\n";

    $php .= "\t\t\t\t\t".'<div class="form-group">'."\r\n";
    $php .= "\t\t\t\t\t\t".'<label for="page">Specific Pages</label>'."\r\n";
    $php .= "\t\t\t\t\t\t".'<input class="form-control" type="text" name="page" value="\'.$specific_page.\'"/>'."\r\n";
    $php .= "\t\t\t\t\t".'</div>'."\r\n";


    $php .= "\t\t\t\t\t".'<div class="form-group">'."\r\n";
    $php .= "\t\t\t\t\t\t".'<label for="submit"></label>'."\r\n";
    $php .= "\t\t\t\t\t\t".'<input class="btn btn-primary" type="submit" name="push" />'."\r\n";
    $php .= "\t\t\t\t\t".'</div>'."\r\n";

    $php .= "\t\t\t\t".'</form>'."\r\n";
    $php .= "\t\t\t\t".'\';'."\r\n";

    $php .= "\t\t\t".'break;'."\r\n";


    // TODO: php --|-- table - filebrowser
    $php .= "\t\t".'// TO'.'DO: FileBrowser'."\r\n";
    $php .= "\t\t".'case "filebrowser":'."\r\n";
    $php .= "\t\t\t".'if(!isset($_GET["type"])){'."\r\n";
    $php .= "\t\t\t\t\t".'$_GET["type"]="image";'."\r\n";
    $php .= "\t\t\t".'}'."\r\n";

    $php .= "\t\t\t".'$tags_html .= "<h4 class=\"page-title\">".@LANG_FILE_BROWSER."</h4>" ;'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<ul class=\"nav nav-tabs\">" ;'."\r\n";
    $php .= "\t\t\t".'if($_GET["type"]=="image"){'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<li class=\"active\"><a href=\"?table=filebrowser&type=image\">".@LANG_IMAGES."</a></li>" ;'."\r\n";
    $php .= "\t\t\t\t".'}else{'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<li><a href=\"?table=filebrowser&type=image\">".@LANG_IMAGES."</a></li>" ;'."\r\n";
    $php .= "\t\t\t\t".'}'."\r\n";
    $php .= "\t\t\t".'if($_GET["type"]=="file"){'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<li class=\"active\"><a href=\"?table=filebrowser&type=file\">".@LANG_FILES."</a></li>" ;'."\r\n";
    $php .= "\t\t\t\t".'}else{'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<li><a href=\"?table=filebrowser&type=file\">".@LANG_FILES."</a></li>" ;'."\r\n";
    $php .= "\t\t\t\t".'}'."\r\n";
    $php .= "\t\t\t".'if($_GET["type"]=="media"){'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<li class=\"active\"><a href=\"?table=filebrowser&type=media\">".@LANG_MEDIAS."</a></li>" ;'."\r\n";
    $php .= "\t\t\t\t".'}else{'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<li><a href=\"?table=filebrowser&type=media\">".@LANG_MEDIAS."</a></li>" ;'."\r\n";
    $php .= "\t\t\t\t".'}'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "</ul>" ;'."\r\n";
    $php .= "\t\t\t\t\t".'$tags_html .= "<br/>" ;'."\r\n";
    $php .= "\t\t\t".'$tags_html .= "<div>" ;'."\r\n";
    $php .= "\t\t\t".'$tags_html .= "<iframe src=\"kcfinder/browse.php?opener=tinymce4&type=".$_GET["type"]."\" style=\"border:0;padding:0;margin:0;overflow:hidden;min-height:480px;width:100%;\" ></iframe>" ;'."\r\n";
    $php .= "\t\t\t".'$tags_html .= "</div>" ;'."\r\n";

    $php .= "\t\t\t".'break;'."\r\n";


    $php .= "\t".'}'."\r\n";


    $php .= "\t\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t\t".'$tags_html .= "<footer>" ;'."\r\n";
    $php .= "\t\t".'$tags_html .= "<div class=\"container-fluid\">" ;'."\r\n";
    $php .= "\t\t".'$tags_html .= "<div class=\"row\">" ;'."\r\n";
    $php .= "\t\t".'$tags_html .= "<div class=\"col-md-12\"><p class=\"text-left navbar-text\">Copyright &copy; '.$_SESSION['PROJECT']['app']['company'].' '.date("Y").' - All Rights Reserved</p></div>" ;'."\r\n";
    $php .= "\t\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t\t".'$tags_html .= $ionicon_dialog ;'."\r\n";
    $php .= "\t\t".'$tags_html .= "</div>" ;'."\r\n";
    $php .= "\t\t".'$tags_html .= "</footer>" ;'."\r\n";
    $php .= "\t".'}else{'."\r\n";

    $php .= "\t\t".'
        if($_GET["login"] == "error"){
            $error_notice = "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><p>The email address or password is invalid. Please try to log in again.</p></div>";
        }
        ';


    $php .= "\t\t".'$tags_html .= \'
<div class="container">    
    <div style="max-width: 330px;margin: 0 auto;">    
        <form method="post" enctype="multipart/form-data">
            <h2 class="form-signin-heading">Please Log in</h2>
            \'.$error_notice.\'
            <div class="form-group">
            	<label for="username">Username</label>
            	<input class="form-control" type="text" name="email" placeholder="Email address" required autofocus />
            </div>
            <div class="form-group">
            	<label for="password">Password</label>
            	<input class="form-control" type="password" name="password" placeholder="Password" required autofocus/>
            </div>
            <input type="submit" class="btn btn-primary" name="log-in"/>
        </form>
    </div>
</div>
\' ;'."\r\n";
    $php .= "\t".'}'."\r\n";
    $php .= "\t"."\r\n";
    $php .= "\t".'$kcfinder_tinymce = $kcfinder_input = null;'."\r\n";
    $php .= "\t".'if($config["kcfinder"]==true){'."\r\n";
    $php .= "\t\t".'$kcfinder_tinymce = \'
    file_browser_callback : function(field, url, type, win) {
		tinyMCE.activeEditor.windowManager.open({
			file: "kcfinder/browse.php?opener=tinymce4&cms=ima_builder&field=" + field + "&type=" + type,
			title: "KCFinder",
			width: 640,
			height: 500,
			inline: true,
			close_previous: false
		}, {
			window: win,
			input: field
		});
     return false;
	},\' ;'."\r\n";
    $php .= "\t\t".'$kcfinder_input = \'
            var KCFinderTarget = "";
            window.KCFinder = {
            	callBack: function(path) {
            		$("#" + KCFinderTarget).val(main_url + path);
            	},
            	Open: function(prop_id, file_type) {
            		KCFinderTarget = prop_id;
            		var newwindow = window.open("./kcfinder/browse.php?type=" + file_type, "Image Editor", "height=480,width=1024");
            		if (window.focus) {
            			newwindow.focus()
            		}
            	}
            }; 
            $("*[data-type=\\\'images\\\']").click(function(){
                KCFinder.Open($(this).prop("id"), "image");
            });
            $("*[data-type=\\\'audio\\\']").click(function(){
                KCFinder.Open($(this).prop("id"), "media");
            });
            $("*[data-type=\\\'video\\\']").click(function(){
                KCFinder.Open($(this).prop("id"), "media");
            });
            \';'."\r\n";
    $php .= "\t".'};'."\r\n";


    // TODO: php --|-- markup
    $php .= 'echo \'<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>'.$_SESSION['PROJECT']['app']['name'].'</title>    
    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/\'.strtolower($config["theme"]).\'/bootstrap.min.css" rel="stylesheet"/>
    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet"/>
    <link href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet" media="screen"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-corner-indicator.css" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
    <!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
    <script type="text/javascript"> var main_url = "\'.$main_url.\'";</script>
  </head>
  <body>
    \'.$tags_html.\'
    <script data-pace-options=\\\'{"ajax":true}\\\' src="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
    <script src="//code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
     
    <script type="text/javascript">
        window.icon_picker_target = "test"; 
        function readURL(input,target) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(target).val(e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function ionicons(myclass){
            $("#"+window.icon_picker_target).val(myclass);
            $("#ionicons").modal("hide");
        }
        $(document).ready(function() {
            if($("#datatable").length){
        	   $("#datatable").dataTable();
            }
            tinymce.init({
                selector: "textarea[data-type=\\\'to_trusted\\\']",
                plugins: "code textcolor image link table  contextmenu",
                toolbar1: "undo redo | forecolor backcolor  | bold italic underline | alignleft aligncenter alignright alignjustify | image table | numlist bullist | media",              
                toolbar2: "styleselect fontsizeselect",
                force_br_newlines : false,
                force_p_newlines : false,
                forced_root_block : "",
                extended_valid_elements : "*[*]",
                valid_elements : "*[*]",
                link_class_list:[{"text":"None ","value":""},{"text":"Button Light","value":"button button-light ink"},{"text":"Button Stable","value":"button button-stable ink"},{"text":"Button Positive","value":"button button-positive ink"},{"text":"Button Calm","value":"button button-calm ink"},{"text":"Button Balanced","value":"button button-balanced ink"},{"text":"Button Energized","value":"button button-energized ink"},{"text":"Button Assertive","value":"button button-assertive ink"},{"text":"Button Royal","value":"button button-royal ink"},{"text":"Button Dark","value":"button button-dark ink"}],
                target_list : [{text: "None",value: ""},{text: "New window",alue: "_blank"},{text: "Top window",value: "_top"},{text: "Self window",value: "_self"}],
                \'.$kcfinder_tinymce.\'               
            });
           	$("input[data-type=\\\'icon\\\']").click(function(){
           	       window.icon_picker_target = $(this).prop("id");
                   $("#ionicons").modal("show");
            });
            $("input[data-type=\\\'image-base64\\\']").change(function(){
                var target = $(this).attr("data-target");
                readURL(this,target);
            });
            \'.$kcfinder_input.\'                              
        });
        
    /** relation **/    
    \'.$js_for_relation.\'
    
    /** format **/
    $(function () {
        
        $("\'.$config["input_datetime"].\'").datetimepicker({
            format: "YYYY-MM-DD h:mm:ss"
        });
        
        $("\'.$config["input_date"].\'").datetimepicker({
            format: "YYYY-MM-DD"
        });
        
        $("input[data-type=\\\'date\\\'],input[data-type=\\\'date_php\\\']").datetimepicker({
            format: "YYYY-MM-DD"
        });
        
        $("input[data-type=\\\'datetime\\\'],input[data-type=\\\'datetime_php\\\'],input[data-type=\\\'datetime_string\\\']").datetimepicker({
            format: "YYYY-MM-DD h:mm:ss"
        });
        
  
                
    });
        
    </script>
    
    </body>
</html>\';';
    $php .= "\t"."\r\n";
    $php .= "\t"."\r\n";
    $php .= '?>';
    return $php;
}
// TODO: class --|-- form
class html_form
{
    var $fields = array();
    public function addField($field)
    {
        $this->fields[] = $field;
    }
    function Code($val = false)
    {
        $code = null;
        foreach($this->fields as $field)
        {
            $code .= $this->input($field,$val);
        }
        return $code;
    }
    private function input($field,$val = false)
    {
        $enter = "\r\n";
        $tab = "\t";
        $html = null;
        $html = $enter;
        $html .= $enter;
        $html .= '<!--'.$enter;
        $html .= '// TO'.'DO: ----|-- form : '.strtoupper(str2SQL(html_entity_decode($field['column_label']))).''.$enter;
        $html .= '-->'.$enter;

        $type = strtolower($field['column_type']);
        $input = $textarea = null;
        if($val == true)
        {
            $input = 'value="\'.htmlentities($data_'.$field['column_name'].').\'" ';
            $textarea = '\'.htmlentities($data_'.$field['column_name'].').\'';
        }
        $field['column_label'] = "' . @LANG_".strtoupper(str2SQL(html_entity_decode($field['column_label']))).". '";
        // TODO: markup colum type
        switch($type)
        {
            case 'float':
                $html .= '<div class="form-group">'.$enter;
                $html .= $tab.'<label for="'.$field['column_name'].'">'.$field['column_label'].'</label>'.$enter;
                $html .= $tab.'<input  class="form-control input-'.$field['column_name'].'" type="text" name="'.$field['column_name'].'" placeholder="'.$field['column_example'].'" '.$input.'/>'.$enter;
                $html .= $tab.'<p class="help-block">'.$field['column_tip'].'</p>'.$enter;
                $html .= '</div>'.$enter;
                break;
            case 'int':
                $html .= '<div class="form-group">'.$enter;
                $html .= $tab.'<label for="'.$field['column_name'].'">'.$field['column_label'].'</label>'.$enter;
                $html .= $tab.'<input  class="form-control input-'.$field['column_name'].'" type="number" name="'.$field['column_name'].'" placeholder="'.$field['column_example'].'" '.$input.'/>'.$enter;
                $html .= $tab.'<p class="help-block">'.$field['column_tip'].'</p>'.$enter;
                $html .= '</div>'.$enter;
                break;
            case 'email':
                $html .= '<div class="form-group">'.$enter;
                $html .= $tab.'<label for="'.$field['column_name'].'">'.$field['column_label'].'</label>'.$enter;
                $html .= $tab.'<input  class="form-control input-'.$field['column_name'].'" type="email" name="'.$field['column_name'].'" placeholder="'.$field['column_example'].'" '.$input.'/>'.$enter;
                $html .= $tab.'<p class="help-block">'.$field['column_tip'].'</p>'.$enter;
                $html .= '</div>'.$enter;
                break;
            case 'varchar':
                $html .= '<div class="form-group">'.$enter;
                $html .= $tab.'<label for="'.$field['column_name'].'">'.$field['column_label'].'</label>'.$enter;
                $html .= $tab.'<input  id="'.$field['column_name'].'" class="form-control input-'.$field['column_name'].'" data-type="'.$field['type'].'" type="text" name="'.$field['column_name'].'" placeholder="'.$field['column_example'].'" '.$input.'/>'.$enter;
                $html .= $tab.'<p class="help-block">'.$field['column_tip'].'</p>'.$enter;
                $html .= '</div>'.$enter;
                break;


            case 'text':
                $html .= '<div class="form-group">'.$enter;
                $html .= $tab.'<label for="'.$field['column_name'].'">'.$field['column_label'].'</label>'.$enter;
                $html .= $tab.'<textarea id="'.$field['column_name'].'" class="form-control input-'.$field['column_name'].'"  data-type="'.$field['type'].'" name="'.$field['column_name'].'" >'.$textarea.'</textarea>'.$enter;
                $html .= $tab.'<p class="help-block">'.$field['column_tip'].'</p>'.$enter;
                $html .= '</div>'.$enter;
                break;
            case 'tinytext':
                $html .= '<div class="form-group">'.$enter;
                $html .= $tab.'<label for="'.$field['column_name'].'">'.$field['column_label'].'</label>'.$enter;
                $html .= $tab.'<textarea  id="'.$field['column_name'].'" class="form-control input-'.$field['column_name'].'" name="'.$field['column_name'].'"  data-type="'.$field['type'].'">'.$textarea.'</textarea>'.$enter;
                $html .= $tab.'<p class="help-block">'.$field['column_tip'].'</p>'.$enter;
                $html .= '</div>'.$enter;
                break;
            case 'enum':
                $html .= '<div class="form-group">'.$enter;
                $html .= $tab.'<label for="'.$field['column_name'].'">'.$field['column_label'].'</label>'.$enter;
                $html .= $tab.'<input  class="form-control input-'.$field['column_name'].'" type="text" name="'.$field['column_name'].'" placeholder="'.$field['column_example'].'" '.$input.'/>'.$enter;
                $html .= $tab.'<p class="help-block">'.$field['column_tip'].'</p>'.$enter;
                $html .= '</div>'.$enter;
                break;
            case 'date':
                $html .= '<div class="form-group">'.$enter;
                $html .= $tab.'<label for="'.$field['column_name'].'">'.$field['column_label'].'</label>'.$enter;
                $html .= $tab.'<input class="form-control input-'.$field['column_name'].'" type="text" name="'.$field['column_name'].'" placeholder="'.$field['column_example'].'" '.$input.'/>'.$enter;
                $html .= $tab.'<p class="help-block">'.$field['column_tip'].'</p>'.$enter;
                $html .= '</div>'.$enter;
                break;
            case 'datetime':
                $html .= '<div class="form-group">'.$enter;
                $html .= $tab.'<label for="'.$field['column_name'].'">'.$field['column_label'].'</label>'.$enter;
                $html .= $tab.'<input  class="form-control input-'.$field['column_name'].'" type="text" name="'.$field['column_name'].'" placeholder="'.$field['column_example'].'" '.$input.'/>'.$enter;
                $html .= $tab.'<p class="help-block">'.$field['column_tip'].'</p>'.$enter;
                $html .= '</div>'.$enter;
                break;
            case 'time':
                $html .= '<div class="form-group">'.$enter;
                $html .= $tab.'<label for="'.$field['column_name'].'">'.$field['column_label'].'</label>'.$enter;
                $html .= $tab.'<input  class="form-control input-'.$field['column_name'].'" type="text" name="'.$field['column_name'].'" placeholder="'.$field['column_example'].'" '.$input.'/>'.$enter;
                $html .= $tab.'<p class="help-block">'.$field['column_tip'].'</p>'.$enter;
                $html .= '</div>'.$enter;
                break;
        }
        return $html;
    }
}

?>