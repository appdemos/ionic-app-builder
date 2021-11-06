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
$file_name = 'test';
if(!isset($_GET['prefix']))
{
    $_GET['prefix'] = null;
}
$prefix_json = $_GET['prefix'];

$footer = null;
$bs = new jsmBootstrap();
$form_input = $html = null;
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
if(!is_dir('output/'.$file_name.'/backend/php-sql/'))
{
    mkdir('output/'.$file_name.'/backend/php-sql/',0777,true);
}
$out_path = 'output/'.$file_name;
$content = null;
$php_mysql_path = 'projects/'.$file_name.'/php_sql.json';
$php_mysql_path_config = 'projects/'.$file_name.'/php_sql_config.json';

if(file_exists($php_mysql_path))
{
    $raw_php_mysql = json_decode(file_get_contents($php_mysql_path),true);
}

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
    $raw_php_mysql_config['php_sql_config']['dbase'] = 'db_'.$file_name;
}

if(!isset($raw_php_mysql_config['php_sql_config']['url']))
{
    $raw_php_mysql_config['php_sql_config']['url'] = 'http://domain.com/apps/'.$file_name.'/';
}

if(!isset($raw_php_mysql_config['php_sql_config']['theme']))
{
    $raw_php_mysql_config['php_sql_config']['theme'] = 'paper';
}
if(!isset($raw_php_mysql_config['php_sql_config']['navbar']))
{
    $raw_php_mysql_config['php_sql_config']['navbar'] = 'nav-stacked';
}
if(!isset($raw_php_mysql_config['php_sql_config']['user_email']))
{
    $raw_php_mysql_config['php_sql_config']['user_email'] = 'admin@localhost';
}
if(!isset($raw_php_mysql_config['php_sql_config']['user_password']))
{
    $raw_php_mysql_config['php_sql_config']['user_password'] = 'admin';
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

function get_table_info($name)
{
    global $raw_php_mysql;
    $info = null;
    if(isset($raw_php_mysql['php_sql']))
    {
        if(is_array($raw_php_mysql['php_sql']))
        {
            foreach($raw_php_mysql['php_sql'] as $used)
            {
                if($name == $used['name'])
                {
                    $info = $used;
                }
            }
        }
    }
    return $info;
}


// TODO: --|-- php
$php = null;
$php .= '<?php'."\r\n\r";
$php .= "\r\n";
$php .= "/**\r\n";
$php .= " * @author ".$_SESSION['PROJECT']['app']['author_name']." <".$_SESSION['PROJECT']['app']['author_email'].">\r\n";
$php .= " * @copyright ".$_SESSION['PROJECT']['app']['company']." ".date("Y")."\r\n";
$php .= " * @package ".$_SESSION['PROJECT']['app']['prefix']."\r\n";
$php .= " * \r\n";
$php .= " * \r\n";
$php .= " * Created using Ionic App Builder\r\n";
$php .= " * http://codecanyon.net/item/ionic-mobile-app-builder/15716727\r\n";
$php .= " */\r\n";
$php .= "\r\n";
$php .= "\r\n/** CONFIG:START **/";
$php .= "\r\n".'$config["host"] '."\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['host']).'" ; '."\t\t".'//host';
$php .= "\r\n".'$config["user"] '."\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['uname']).'" ; '."\t\t".'//Username SQL';
$php .= "\r\n".'$config["pass"] '."\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['pwd']).'" ; '."\t\t".'//Password SQL';
$php .= "\r\n".'$config["dbase"] '."\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['dbase']).'" ; '."\t\t".'//Database';
$php .= "\r\n".'$config["utf8"] '."\t\t".'= true ; '."\t\t".'//turkish charset set false';
$php .= "\r\n".'$config["timezone"] '."\t\t".'= "Asia/Jakarta" ; '."\t\t".'// check this site: http://php.net/manual/en/timezones.php';

//$php .= "\r\n" . '$config["limit"] ' . "\t\t" . '= 500 ; ' . "\t\t" . '//limit row';
$php .= "\r\n".'$config["abs_url_images"] '."\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['url']).'/media/image/" ; '."\t\t".'//Absolute Images URL';
$php .= "\r\n".'$config["abs_url_videos"] '."\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['url']).'/media/media/" ; '."\t\t".'//Absolute Videos URL';
$php .= "\r\n".'$config["abs_url_audios"] '."\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['url']).'/media/media/" ; '."\t\t".'//Absolute Audio URL';
$php .= "\r\n".'$config["abs_url_files"] '."\t\t".'= "'.htmlentities($raw_php_mysql_config['php_sql_config']['url']).'/media/file/" ; '."\t\t".'//Absolute Files URL';

$php .= "\r\n".'$config["image_allowed"][] '."\t\t".'= array("mimetype"=>"image/jpeg","ext"=>"jpg") ; '."\t\t".'//whitelist image';
$php .= "\r\n".'$config["image_allowed"][] '."\t\t".'= array("mimetype"=>"image/jpg","ext"=>"jpg") ; '."\t\t".'';
$php .= "\r\n".'$config["image_allowed"][] '."\t\t".'= array("mimetype"=>"image/png","ext"=>"png") ; '."\t\t".'';
$php .= "\r\n".'$config["file_allowed"][] '."\t\t".'= array("mimetype"=>"text/plain","ext"=>"txt") ; '."\t\t".'';
$php .= "\r\n".'$config["file_allowed"][] '."\t\t".'= array("mimetype"=>"","ext"=>"tmp") ; '."\t\t".'';


// TODO: --|-- php - config
$php .= "\r\n/** CONFIG:END **/";
$php .= "\r\n";
$php .= "\r\n";

$php .= "date_default_timezone_set(\$config['timezone']);\r\n";
$php .= 'if(isset($_SERVER["HTTP_X_AUTHORIZATION"])){'."\r\n";
$php .= "\t".'list($_SERVER["PHP_AUTH_USER"],$_SERVER["PHP_AUTH_PW"]) = explode(":" , base64_decode(substr($_SERVER["HTTP_X_AUTHORIZATION"],6)));'."\r\n";
$php .= '}'."\r\n";

$php .= '$rest_api=array("data"=>array("status"=>404,"title"=>"Not found"),"title"=>"Error","message"=>"Routes not found");'."\r\n";


$php .= "\r\n";
$php .= "".'/** connect to mysql **/'."\r\n";
$php .= "".'$mysql = new mysqli($config["host"], $config["user"], $config["pass"], $config["dbase"]);'."\r\n";
$php .= "".'if (mysqli_connect_errno()){'."\r\n";
$php .= "\t".'die(mysqli_connect_error());'."\r\n";
$php .= "".'}'."\r\n";
$php .= "\r\n";
$php .= "\r\n".'if(!isset($_GET["json"])){';
$php .= "\r\n\t".'$_GET["json"]= "route";';
$php .= "\r\n".'}';

$php .= "\r\n".'if((!isset($_GET["form"])) && ($_GET["json"] == "submit")) {';
$php .= "\r\n\t".'$_GET["json"]= "route";';
$php .= "\r\n".'}';

$php .= "\r\n";
$php .= "\r\n".'if($config["utf8"]==true){';
$php .= "\r\n\t".'$mysql->set_charset("utf8");';
$php .= "\r\n".'}';
$php .= "\r\n";
$php .= "\r\n";
$php .= "".'$get_dir = explode("/", $_SERVER["PHP_SELF"]);'."\r\n";
$php .= "".'unset($get_dir[count($get_dir)-1]);'."\r\n";
$php .= "".'$main_url = "http://" . $_SERVER["HTTP_HOST"] . implode("/",$get_dir)."/";'."\r\n";
$php .= "\r\n";

$tables = $_SESSION['PROJECT']['tables'];

// Check Auth
$table_contain_user = 'no_table_user';
$field_username = 'no_field_username';
$field_password = 'no_field_password';
$is_auth_support = false;
foreach($tables as $_table)
{
    if(!isset($_table['cols']))
    {
        $_table['cols'] = array();
    }
    $is_password = false;
    $is_username = false;
    foreach($_table['cols'] as $cols)
    {
        if($cols['type'] == 'as_password')
        {
            $is_password = true;
            $field_password = $cols['title'];
        }
        if($cols['type'] == 'as_username')
        {
            $is_username = true;
            $field_username = $cols['title'];
        }
    }
    if(($is_password == true) & ($is_username == true))
    {
        $is_auth_support = true;
        $table_contain_user = $_table['prefix'];
    }
}

// TODO: --|-- php - switch
$php .= "\r\n".'switch($_GET["json"]){';
foreach($tables as $table)
{


    $table_info = get_table_info($table['prefix']);
    $col_id = 'id';
    if($table_info != null)
    {
        foreach($table['cols'] as $col)
        {
            if($col['type'] == 'id')
            {
                $col_id = str2SQL($col['title']);
            }
        }

        $_new_column = array();
        foreach($table['cols'] as $col)
        {
            $_new_column[str2SQL($col['title'])] = $col;
        }
        // TODO: php -+- Listing
        $php .= "\t"."\r\n";
        $php .= "\t".'// TO'.'DO: -+- Listing : '.$table['prefix'].''."\r\n";
        $php .= "\t".'case "'.$table['prefix'].'":'."\r\n";
        $php .= "\t\t".'$rest_api=array();'."\r\n";

        if(!isset($table_info['auth']))
        {
            $table_info['auth'] = 'false';
        }
        if($table_info['auth'] == 'true')
        {
            // TODO: php -+----+- Auth User
            $php .= "\t\t".'// TO'.'DO: -+----+- Auth User'."\r\n";
            $php .= "\t\t".'$is_user = false;'."\r\n";
            $php .= "\t\t".'if(isset($_SERVER["PHP_AUTH_USER"])){'."\r\n";
            $php .= "\t\t\t".'$php_auth_user = $mysql->escape_string($_SERVER["PHP_AUTH_USER"]);'."\r\n";
            $php .= "\t\t\t".'$php_auth_pw = $mysql->escape_string($_SERVER["PHP_AUTH_PW"]);'."\r\n";
            $php .= "\t\t\t".'$auth_sql = "SELECT * FROM `'.$table_contain_user.'` WHERE `'.$field_username.'` = \'$php_auth_user\' AND `'.$field_password.'` = \'$php_auth_pw\'";'."\r\n";
            $php .= "\t\t\t".'if($result = $mysql->query($auth_sql)){'."\r\n";
            $php .= "\t\t\t\t".'$current_user = $result->fetch_array();'."\r\n";
            $php .= "\t\t\t\t".'if(isset($current_user["'.$field_username.'"])){'."\r\n";
            $php .= "\t\t\t\t\t".'$is_user = true;'."\r\n";
            $php .= "\t\t\t\t".'}'."\r\n";
            $php .= "\t\t\t".'}'."\r\n";

            $php .= "\t\t\t".'if($is_user == false){'."\r\n";
            $php .= "\t\t\t\t".'$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");'."\r\n";
            $php .= "\t\t\t\t".'break;'."\r\n";
            $php .= "\t\t\t".'}'."\r\n";
            $php .= "\t\t".'}else{'."\r\n";
            $php .= "\t\t\t".'$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");'."\r\n";
            $php .= "\t\t\t".'break;'."\r\n";
            $php .= "\t\t".'}'."\r\n";
        }


        $php .= "\t\t".'$where = $_where = null;'."\r\n";
        if($table_info['auth'] == 'true')
        {
            if($table_info['owned-by-me'] == 'true')
            {
                // TODO: php -+----+- filter by user
                $php .= "\t\t".'// TO'.'DO: -+----+- Filter by User'."\r\n";
                $php .= "\t\t".'$_where[] = "`'.$field_username.'` = \'$php_auth_user\'";'."\r\n";
            }
        }
        // TODO: php -+----+- where
        $php .= "\t\t".'// TO'.'DO: -+----+- statement where'."\r\n";
        foreach($_new_column as $col)
        {
            if($col_id != str2SQL($col['title']))
            {
                $php .= "\t\t".'if(isset($_GET["'.str2SQL($col['title']).'"])){'."\r\n";
                $php .= "\t\t\t".'if($_GET["'.str2SQL($col['title']).'"]!="-1"){'."\r\n";
                $php .= "\t\t\t\t".'$_where[] = "`'.str2SQL($col['title']).'` LIKE \'".$mysql->escape_string($_GET["'.str2SQL($col['title']).'"])."\'";'."\r\n";
                $php .= "\t\t\t".'}'."\r\n";
                $php .= "\t\t".'}'."\r\n";
            }
        }

        $php .= "\t\t".'if(isset($_GET["'.$col_id.'"])){'."\r\n";
        $php .= "\t\t\t".'if($_GET["'.$col_id.'"]!="-1"){'."\r\n";
        $php .= "\t\t\t\t".'$_where[] = "`'.$col_id.'` = \'".$mysql->escape_string($_GET["'.$col_id.'"])."\'";'."\r\n";
        $php .= "\t\t\t".'}'."\r\n";
        $php .= "\t\t".'}'."\r\n";

        $php .= "\t\t".'if(is_array($_where)){'."\r\n";
        $php .= "\t\t\t".'$where = " WHERE " . implode(" AND ",$_where);'."\r\n";
        $php .= "\t\t".'}'."\r\n";

        // TODO: php -+----+- order orderby
        $php .= "\t\t".'// TO'.'DO: -+----+- orderby'."\r\n";
        $php .= "\t\t".'$order_by = "`'.$col_id.'`";'."\r\n";
        $php .= "\t\t".'$sort_by = "DESC";'."\r\n";

        $php .= "\t\t".'if(!isset($_GET["order"])){'."\r\n";
        $php .= "\t\t\t".'$_GET["order"] = "`'.$col_id.'`";'."\r\n";
        $php .= "\t\t".'}'."\r\n";
        // TODO: php -+----+- order asc/desc
        $php .= "\t\t".'// TO'.'DO: -+----+- sort asc/desc'."\r\n";
        $php .= "\t\t".'if(!isset($_GET["sort"])){'."\r\n";
        $php .= "\t\t\t".'$_GET["sort"] = "desc";'."\r\n";
        $php .= "\t\t".'}'."\r\n";

        $php .= "\t\t".'if($_GET["sort"]=="asc"){'."\r\n";
        $php .= "\t\t\t".'$sort_by = "ASC";'."\r\n";
        $php .= "\t\t".'}else{'."\r\n";
        $php .= "\t\t\t".'$sort_by = "DESC";'."\r\n";
        $php .= "\t\t".'}'."\r\n";

        foreach($_new_column as $col)
        {
            $php .= "\t\t".'if($_GET["order"]=="'.str2SQL($col['title']).'"){'."\r\n";
            $php .= "\t\t\t".'$order_by = "`'.str2SQL($col['title']).'`";'."\r\n";
            $php .= "\t\t".'}'."\r\n";
        }

        $php .= "\t\t".'if($_GET["order"]=="random"){'."\r\n";
        $php .= "\t\t\t".'$order_by = "RAND()";'."\r\n";
        $php .= "\t\t".'}'."\r\n";


        $php .= "\t\t".'$limit = '.$table_info['limit'].';'."\r\n";
        $php .= "\t\t".'if(isset($_GET["limit"])){'."\r\n";
        $php .= "\t\t\t".'$limit = (int)$_GET["limit"] ;'."\r\n";
        $php .= "\t\t".'}'."\r\n";

        // TODO: php -+----+- SQL Query
        $php .= "\t\t".'// TO'.'DO: -+----+- SQL Query'."\r\n";
        $php .= "\t\t".'$sql = "SELECT * FROM `'.$table['prefix'].'` ".$where."ORDER BY ".$order_by." ".$sort_by." LIMIT 0, ".$limit." " ;'."\r\n";

        $php .= "\t\t".'if($result = $mysql->query($sql)){'."\r\n";
        $php .= "\t\t\t".'$z=0;'."\r\n";
        $php .= "\t\t\t".'while ($data = $result->fetch_array()){';


        foreach($_new_column as $col)
        {
            $php .= "\r\n\t\t\t\t";
            if(!isset($col['json']))
            {
                $col['json'] = 'false';
            }
            if($col['json'] == 'false')
            {
                $php .= "#";
            }

            if(($col['type'] == 'images') || ($col['type'] == 'video') || ($col['type'] == 'audio') || ($col['type'] == 'as_password') || ($col['type'] == 'as_username') || ($col['type'] == 'date') || ($col['type'] == 'datetime') || ($col['type'] == 'date_php') || ($col['type'] == 'datetime_php') || ($col['type'] == 'datetime_string'))
            {
                $php .= "\r\n\t\t\t\t/** ".$col['type']."**/";

                if(($col['type'] == 'images') || ($col['type'] == 'video') || ($col['type'] == 'audio'))
                {
                    $php .= "\r\n\t\t\t\t\$abs_url_images = \$config['abs_url_images'].'/';";
                    $php .= "\r\n\t\t\t\t\$abs_url_videos = \$config['abs_url_videos'].'/';";
                    $php .= "\r\n\t\t\t\t\$abs_url_audios = \$config['abs_url_audios'].'/';";
                    $php .= "\r\n\t\t\t\tif(!isset(\$data['".str2SQL($col['title'])."'])){\$data['".str2SQL($col['title'])."']='undefined';}; # ".$col['type'];


                    $php .= "\r\n\t\t\t\tif((substr(\$data['".str2SQL($col['title'])."'], 0, 7)=='http://')||(substr(\$data['".str2SQL($col['title'])."'], 0, 8)=='https://')){";
                    $php .= "\r\n\t\t\t\t\t\$abs_url_images = \$abs_url_videos  = \$abs_url_audios = '';";
                    $php .= "\r\n\t\t\t\t}\r\n\t\t\t\t";

                    $php .= "\r\n\t\t\t\tif(substr(\$data['".str2SQL($col['title'])."'], 0, 5)=='data:'){";
                    $php .= "\r\n\t\t\t\t\t\$abs_url_images = \$abs_url_videos  = \$abs_url_audios = '';";
                    $php .= "\r\n\t\t\t\t}\r\n\t\t\t\t";

                    if(($col['type'] == 'images'))
                    {
                        $php .= "\r\n\t\t\t\tif(\$data['".str2SQL($col['title'])."'] != ''){";
                        $php .= "\r\n\t\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = \$abs_url_images . \$data['".str2SQL($col['title'])."']; # ".$col['type'];
                        $php .= "\r\n\t\t\t\t}else{";
                        $php .= "\r\n\t\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = ''; # ".$col['type'];
                        $php .= "\r\n\t\t\t\t}";
                    }

                    if(($col['type'] == 'video'))
                    {
                        $php .= "\r\n\t\t\t\tif(\$data['".str2SQL($col['title'])."'] != ''){";
                        $php .= "\r\n\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = \$abs_url_videos . \$data['".str2SQL($col['title'])."']; # ".$col['type'];
                        $php .= "\r\n\t\t\t\t}else{";
                        $php .= "\r\n\t\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = ''; # ".$col['type'];
                        $php .= "\r\n\t\t\t\t}";
                    }

                    if(($col['type'] == 'audio'))
                    {
                        $php .= "\r\n\t\t\t\tif(\$data['".str2SQL($col['title'])."'] != ''){";
                        $php .= "\r\n\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = \$abs_url_audios . \$data['".str2SQL($col['title'])."']; # ".$col['type'];
                        $php .= "\r\n\t\t\t\t}else{";
                        $php .= "\r\n\t\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = ''; # ".$col['type'];
                        $php .= "\r\n\t\t\t\t}";
                    }
                }

                if(($col['type'] == 'date'))
                {
                    $php .= "\r\n\t\t\t\tif(\$data['".str2SQL($col['title'])."'] != ''){";
                    $php .= "\r\n\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] =  mktime( 0,0,0,substr(\$data['".str2SQL($col['title'])."'],5,2),substr(\$data['".str2SQL($col['title'])."'],8,2),substr(\$data['".str2SQL($col['title'])."'],0,4)) * 1000; # ".$col['type'];
                    $php .= "\r\n\t\t\t\t}else{";
                    $php .= "\r\n\t\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = 0; # ".$col['type'];
                    $php .= "\r\n\t\t\t\t}";
                }

                if(($col['type'] == 'datetime'))
                {
                    $php .= "\r\n\t\t\t\tif(\$data['".str2SQL($col['title'])."'] != ''){";
                    $php .= "\r\n\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] =  mktime(substr(\$data['".str2SQL($col['title'])."'],11,2),substr(\$data['".str2SQL($col['title'])."'],14,2),substr(\$data['".str2SQL($col['title'])."'],17,2),substr(\$data['".str2SQL($col['title'])."'],5,2),substr(\$data['".str2SQL($col['title'])."'],8,2),substr(\$data['".str2SQL($col['title'])."'],0,4)) * 1000; # ".$col['type'];
                    $php .= "\r\n\t\t\t\t}else{";
                    $php .= "\r\n\t\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = 0; # ".$col['type'];
                    $php .= "\r\n\t\t\t\t}";
                }

                if(($col['type'] == 'date_php'))
                {
                    $php .= "\r\n\t\t\t\tif(\$data['".str2SQL($col['title'])."'] != ''){";
                    $php .= "\r\n\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] =  mktime( 0,0,0,substr(\$data['".str2SQL($col['title'])."'],5,2),substr(\$data['".str2SQL($col['title'])."'],8,2),substr(\$data['".str2SQL($col['title'])."'],0,4)) ; # ".$col['type'];
                    $php .= "\r\n\t\t\t\t}else{";
                    $php .= "\r\n\t\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = 0; # ".$col['type'];
                    $php .= "\r\n\t\t\t\t}";
                }

                if(($col['type'] == 'datetime_php'))
                {
                    $php .= "\r\n\t\t\t\tif(\$data['".str2SQL($col['title'])."'] != ''){";
                    $php .= "\r\n\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] =  mktime(substr(\$data['".str2SQL($col['title'])."'],11,2),substr(\$data['".str2SQL($col['title'])."'],14,2),substr(\$data['".str2SQL($col['title'])."'],17,2),substr(\$data['".str2SQL($col['title'])."'],5,2),substr(\$data['".str2SQL($col['title'])."'],8,2),substr(\$data['".str2SQL($col['title'])."'],0,4)) ; # ".$col['type'];
                    $php .= "\r\n\t\t\t\t}else{";
                    $php .= "\r\n\t\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = 0; # ".$col['type'];
                    $php .= "\r\n\t\t\t\t}";
                }

                if(($col['type'] == 'datetime_string'))
                {
                    $php .= "\r\n\t\t\t\tif(\$data['".str2SQL($col['title'])."'] != ''){";
                    $php .= "\r\n\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = date('Y-m-d\TH:i:s', mktime(substr(\$data['".str2SQL($col['title'])."'],11,2),substr(\$data['".str2SQL($col['title'])."'],14,2),substr(\$data['".str2SQL($col['title'])."'],17,2),substr(\$data['".str2SQL($col['title'])."'],5,2),substr(\$data['".str2SQL($col['title'])."'],8,2),substr(\$data['".str2SQL($col['title'])."'],0,4))) ; # ".$col['type'];
                    $php .= "\r\n\t\t\t\t}else{";
                    $php .= "\r\n\t\t\t\t\t\$rest_api[\$z]['".str2var($col['title'],false)."'] = date('Y-m-d\Th:i:s',0); # ".$col['type'];
                    $php .= "\r\n\t\t\t\t}";
                }

            } else
            {
                $php .= "if(isset(\$data['".str2SQL($col['title'])."'])){\$rest_api[\$z]['".str2var($col['title'],false)."'] = \$data['".str2SQL($col['title'])."'];}; # ".$col['type'];
            }

        }
        $php .= "\r\n\t\t\t\t\$z++;";
        $php .= "\r\n";
        $php .= "\t\t\t".'}'."\r\n";
        $php .= "\t\t\t".'$result->close();'."\r\n";


        $php .= "\t\t\t".'if(isset($_GET["'.$col_id.'"])){'."\r\n";
        $php .= "\t\t\t\t".'if(isset($rest_api[0])){'."\r\n";
        $php .= "\t\t\t\t\t".'$rest_api = $rest_api[0];'."\r\n";
        $php .= "\t\t\t\t".'}else{'."\r\n";
        $php .= "\t\t\t\t\t".'$rest_api=array("data"=>array("status"=>404,"title"=>"Not found"),"title"=>"Error","message"=>"Invalid ID");'."\r\n";	
        $php .= "\t\t\t\t".'}'."\r\n";
        $php .= "\t\t\t".'}'."\r\n";
        $php .= "\t\t".'}'."\r\n";
        $php .= "\r\n\t\t".'break;';
        $php .= "\r\n";
    }
}

if($is_auth_support == true)
{
    // TODO: php -+- Authorization
    $php .= "\t".'// TO'.'DO: -+- Authorization'."\r\n";
    $php .= "\t".'case "auth":'."\r\n";
    // TODO: php -+----+- Auth User
    $php .= "\t\t".'// TO'.'DO: -+----+- Auth User'."\r\n";
    $php .= "\t\t".''."\r\n";
    $php .= "\t\t".'$is_user = false;'."\r\n";
    $php .= "\t\t".'if(isset($_SERVER["PHP_AUTH_USER"])){'."\r\n";
    $php .= "\t\t\t".'$php_auth_user = $mysql->escape_string($_SERVER["PHP_AUTH_USER"]);'."\r\n";
    $php .= "\t\t\t".'$php_auth_pw = $mysql->escape_string($_SERVER["PHP_AUTH_PW"]);'."\r\n";
    $php .= "\t\t\t".'$auth_sql = "SELECT * FROM `'.$table_contain_user.'` WHERE `'.$field_username.'` = \'$php_auth_user\' AND `'.$field_password.'` = \'$php_auth_pw\'";'."\r\n";
    $php .= "\t\t\t".'if($result = $mysql->query($auth_sql)){'."\r\n";
    $php .= "\t\t\t\t".'$current_user = $result->fetch_array();'."\r\n";
    $php .= "\t\t\t\t".'if(isset($current_user["'.$field_username.'"])){'."\r\n";
    $php .= "\t\t\t\t\t".'$is_user = true;'."\r\n";
    $php .= "\t\t\t\t".'}'."\r\n";
    $php .= "\t\t\t".'}'."\r\n";


    $php .= "\t\t\t".'if($is_user === true){'."\r\n";
    $php .= "\t\t\t\t".'$rest_api=array("data"=>array("status"=>200,"error"=>"Successfully"),"title"=>"Successfully","message"=>"Successfully");'."\r\n";
    $php .= "\t\t\t".'}else{'."\r\n";
    $php .= "\t\t\t\t".'$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Failed","message"=>"Username or password is incorrect, please try again.");'."\r\n";
    $php .= "\t\t\t".'}'."\r\n";

    $php .= "\t\t".'}else{'."\r\n";
    $php .= "\t\t\t".'$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");'."\r\n";
    $php .= "\t\t\t".'break;'."\r\n";
    $php .= "\t\t".'}'."\r\n";


    $php .= "\r\n\t\t".'break;';
    $php .= "\r\n";

    // TODO: php -+- me
    $php .= "\t".'// TO'.'DO: -+- me'."\r\n";
    $php .= "\t".'case "me":'."\r\n";
    // TODO: php -+----+- Auth User
    $php .= "\t\t".'// TO'.'DO: -+----+- Auth User'."\r\n";
    $php .= "\t\t".'$is_user = false;'."\r\n";
    $php .= "\t\t".'if(isset($_SERVER["PHP_AUTH_USER"])){'."\r\n";
    $php .= "\t\t\t".'$php_auth_user = $mysql->escape_string($_SERVER["PHP_AUTH_USER"]);'."\r\n";
    $php .= "\t\t\t".'$php_auth_pw = $mysql->escape_string($_SERVER["PHP_AUTH_PW"]);'."\r\n";
    $php .= "\t\t\t".'$auth_sql = "SELECT * FROM `'.$table_contain_user.'` WHERE `'.$field_username.'` = \'$php_auth_user\' AND `'.$field_password.'` = \'$php_auth_pw\'";'."\r\n";
    $php .= "\t\t\t".'if($result = $mysql->query($auth_sql)){'."\r\n";
    $php .= "\t\t\t\t".'$current_user = $result->fetch_array();'."\r\n";
    $php .= "\t\t\t\t".'if(isset($current_user["'.$field_username.'"])){'."\r\n";
    $php .= "\t\t\t\t\t".'$is_user = true;'."\r\n";
    $php .= "\t\t\t\t".'}'."\r\n";
    $php .= "\t\t\t".'}'."\r\n";


    $php .= "\t\t\t".'if($is_user == true){'."\r\n";
    $php .= "\t\t\t\t".'$rest_api["data"]["status"]=200;'."\r\n";

    foreach($_SESSION['PROJECT']['tables'][$table_contain_user]['cols'] as $me_cols)
    {
        if($me_cols['type'] != 'as_password')
        {
            $php .= "\t\t\t\t".'$rest_api["me"]["'.str2SQL($me_cols['title']).'"]= $current_user["'.str2SQL($me_cols['title']).'"];'."\r\n";
        }
    }

    //$php .= "\t\t\t\t" . '$rest_api=array("data"=>array("status"=>200,"error"=>"Successfully"),"title"=>"Successfully","message"=>"Successfully");' . "\r\n";

    $php .= "\t\t\t".'}else{'."\r\n";
    $php .= "\t\t\t\t".'$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Failed","message"=>"Username or password is incorrect, please try again.");'."\r\n";
    $php .= "\t\t\t".'}'."\r\n";

    $php .= "\t\t".'}else{'."\r\n";
    $php .= "\t\t\t".'$rest_api=array("data"=>array("status"=>401,"error"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");'."\r\n";
    $php .= "\t\t\t".'break;'."\r\n";
    $php .= "\t\t".'}'."\r\n";


    $php .= "\r\n\t\t".'break;';
    $php .= "\r\n";

}

// TODO: php -+- route
$php .= "\t".'// TO'.'DO: -+- route'."\r\n";
$php .= "\t".'case "route":';
$php .= "\t\t".'$rest_api=array();'."\r\n";


$php .= "\t\t".'$rest_api["site"]["name"] = "'.$_SESSION['PROJECT']['app']['name'].'" ;'."\r\n";
$php .= "\t\t".'$rest_api["site"]["description"] = "'.$_SESSION['PROJECT']['app']['description'].'" ;'."\r\n";
$php .= "\t\t".'$rest_api["site"]["imabuilder"] = "rev'.JSM_VERSION.'" ;'."\r\n";

$z = 0;
foreach($tables as $table)
{
    $table_info = get_table_info($table['prefix']);
    if($table_info != null)
    {
        foreach($table['cols'] as $col)
        {
            if($col['type'] == 'id')
            {
                $col_id = str2SQL($col['title']);
            }
        }

        $_new_column = array();
        foreach($table['cols'] as $col)
        {
            $_new_column[str2SQL($col['title'])] = $col;
        }


        $col = null;
        if(!isset($table['version']))
        {
            $table['version'] = '';
        }
        $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["namespace"] = "'.$table['prefix'].'";';
        $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["tb_version"] = "'.$table['version'].'";';
        $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["methods"][] = "GET";';
        $param = array();
        foreach($_new_column as $col)
        {
            if(!isset($col['json']))
            {
                $col['json'] = 'false';
            }
            if($col['json'] == 'true')
            {
                $param[] = str2SQL($col['title']);
                $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["args"]["'.str2SQL($col['title']).'"] = array("required"=>"false","description"=>"Selecting `'.htmlentities($table['title']).'` based `'.htmlentities($col['title']).'`");';
            }
        }
        $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["args"]["order"] = array("required"=>"false","description"=>"order by `random`, `'.implode("`, `",$param).'`");';
        $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["args"]["sort"] = array("required"=>"false","description"=>"sort by `asc` or `desc`");';
        $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["args"]["limit"] = array("required"=>"false","description"=> "limit the items that appear","type"=>"number");';
        $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json='.$table['prefix'].'";';
        $z++;
    }
}
// TODO: --|-- php ----- | ---- auth
if($is_auth_support == true)
{
    $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["namespace"] = "me";';
    $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["methods"][] = "GET";';
    $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=me";';
    $z++;

    $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["namespace"] = "auth";';
    $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["methods"][] = "GET";';
    $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=auth";';
    $z++;

    $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["namespace"] = "submit/me";';
    $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["methods"][] = "POST";';
    $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=submit&form=me";';
    $z++;

}

if(isset($_SESSION['PROJECT']['forms']))
{
    $forms = $_SESSION['PROJECT']['forms'];
    foreach($forms as $form)
    {

        if(!isset($form['version']))
        {
            $form['version'] = '';
        }
        if(!isset($form['tb_version']))
        {
            $form['tb_version'] = '';
        }
        $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["namespace"] = "submit/'.$form['table'].'";';
        $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["tb_version"] = "'.$form['version'].'";';

        $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["methods"][] = "POST";';
        $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=submit&form='.$form['table'].'";';

        foreach($form['input'] as $input)
        {
            if(!isset($input['type']))
            {
                $input['type'] = 'divider';
            }

            if($input['type'] != 'button')
            {
                if($input['type'] != 'divider')
                {

                    $php .= "\r\n\t\t".'$rest_api["routes"]['.$z.']["args"]["'.$input['name'].'"] = array("required"=>"true","description"=>"Insert data to field `'.htmlentities($input['label']).'` in table `'.htmlentities($form['table']).'`");';
                }
            }
        }
        $z++;
    }
}
$php .= "\r\n\t\t".'break;';
$php .= "\r\n";
// TODO: php -+- submit
$php .= "\t".'// TO'.'DO: -+- submit'."\r\n";
$php .= "\r\n\t".'case "submit":';
$php .= "\r\n";
$remove_from_query = array(
    'divider',
    'submit',
    'button',
    'reset');

$php .= "\t\t".'$rest_api=array();'."\r\n";
$php .= "\r\n\t\t".'$rest_api["methods"][0] = "POST";';
$php .= "\r\n\t\t".'$rest_api["methods"][1] = "GET";';
if(isset($_SESSION['PROJECT']['forms']))
{

    $php .= "\r\n\t\t".'switch($_GET["form"]){';

    $forms = $_SESSION['PROJECT']['forms'];
    $var_username = '_username';
    foreach($forms as $_form)
    {
        $new_form = array();
        foreach($_form['input'] as $_input)
        {
            $_var = $_input['name'];
            $new_form[$_var] = $_input;
        }
        
        $form = $_form;
        $form['input'] = array_values($new_form);

        $php .= "\r\n";
        // TODO: php -+- Submit : Table
        $php .= "\t\t\t".'// TO'.'DO: -+----+- '.$form['table'].''."\r\n";
        $php .= "\t\t\t".'case "'.$form['table'].'":'."\r\n";
        $php .= "\r\n";
        $prefix_table = $form['table'];
        if(isset($_SESSION['PROJECT']['tables'][$prefix_table]))
        {
            $table_form = $_SESSION['PROJECT']['tables'][$prefix_table];
            $as_username = $as_password = false;
            foreach($table_form['cols'] as $input)
            {
                if($input['type'] == 'as_username')
                {
                    $as_username = true;
                    $var_username = str2var($input['title'],false);
                }
                if($input['type'] == 'as_password')
                {
                    $as_password = true;
                }
            }
            $_is_auth = 'false';
            if($as_username == true)
            {
                $_is_auth = 'true';
            }
            if(($as_username == true) && ($as_password == true))
            {
                $_is_auth = 'false';
            }
            $php .= "\r\n\t\t\t\t".'$rest_api["auth"]["basic"] = '.$_is_auth.';'."\r\n";
            if($_is_auth == 'true')
            {

                // TODO: php -+----+- Auth User
                $php .= "\t\t\t\t".'// TO'.'DO: -+----+-----+- Auth User'."\r\n";
                $php .= "\t\t\t\t".'$is_user = false;'."\r\n";
                $php .= "\t\t\t\t".'if(isset($_SERVER["PHP_AUTH_USER"])){'."\r\n";
                $php .= "\t\t\t\t\t".'$php_auth_user = $mysql->escape_string($_SERVER["PHP_AUTH_USER"]);'."\r\n";
                $php .= "\t\t\t\t\t".'$php_auth_pw = $mysql->escape_string($_SERVER["PHP_AUTH_PW"]);'."\r\n";
                $php .= "\t\t\t\t\t".'$auth_sql = "SELECT * FROM `'.$table_contain_user.'` WHERE `'.$field_username.'` = \'$php_auth_user\' AND `'.$field_password.'` = \'$php_auth_pw\'";'."\r\n";
                $php .= "\t\t\t\t\t".'if($result = $mysql->query($auth_sql)){'."\r\n";
                $php .= "\t\t\t\t\t\t".'$current_user = $result->fetch_array();'."\r\n";
                $php .= "\t\t\t\t\t\t".'if(isset($current_user["'.$field_username.'"])){'."\r\n";
                $php .= "\t\t\t\t\t\t\t".'$is_user = true;'."\r\n";
                $php .= "\t\t\t\t\t\t".'}'."\r\n";
                $php .= "\t\t\t\t\t".'}'."\r\n";

                $php .= "\t\t\t\t\t".'if($is_user == false){'."\r\n";
                $php .= "\t\t\t\t\t\t".'$rest_api=array("data"=>array("status"=>401,"title"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");'."\r\n";
                $php .= "\t\t\t\t\t\t".'break;'."\r\n";
                $php .= "\t\t\t\t\t".'}'."\r\n";
                $php .= "\t\t\t\t".'}else{'."\r\n";
                $php .= "\t\t\t\t\t".'$rest_api=array("data"=>array("status"=>401,"title"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");'."\r\n";
                $php .= "\t\t\t\t\t".'break;'."\r\n";
                $php .= "\t\t\t\t".'}'."\r\n";
            }
        }


        foreach($form['input'] as $input)
        {
            if(!in_array($input['type'],$remove_from_query))
            {
                $php .= "\r\n\t\t\t\t".'$rest_api["args"]["'.str2var($input['name'],false).'"] = array("required"=>"true","description"=>"Receiving data from the input `'.($input['label']).'`");';
            }
        }
        $submit_query = array();
        foreach($form['input'] as $input)
        {
            if(!in_array($input['type'],$remove_from_query))
            {
                $php .= "\r\n\t\t\t\t".'if(!isset($_POST["'.str2var($input['name'],false).'"])){';
                $php .= "\r\n\t\t\t\t\t".'$_POST["'.str2var($input['name'],false).'"]="";';
                $php .= "\r\n\t\t\t\t".'}';

                $submit_query[] = '($_POST["'.str2var($input['name'],false).'"] != "")';

            }
        }
        $php .= "\r\n\t\t\t\t".'$rest_api["message"] = "'.addslashes($form['msg_error']).'";';
        $php .= "\r\n\t\t\t\t".'$rest_api["title"] = "Notice!";';
        $php .= "\r\n\t\t\t\t".'if('.implode(' || ',$submit_query).'){';
        $php .= "\r\n\t\t\t\t\t".'// avoid undefined';
        foreach($form['input'] as $input)
        {
            if(!in_array($input['type'],$remove_from_query))
            {
                $php .= "\r\n\t\t\t\t\t".'$input["'.str2var($input['name'],false).'"] = "";';
            }
        }
        $php .= "\r\n\t\t\t\t\t".'// variable post';
        $is_file_upload = false;
        foreach($form['input'] as $input)
        {
            if(!in_array($input['type'],$remove_from_query))
            {

                if($input['type'] == 'file')
                {
                    $is_file_upload = true;
                    $php .= "\r\n\t\t\t\t\t".'$invalid_file = true;';
                    $php .= "\r\n\t\t\t\t\t".'if(isset($_POST["'.str2var($input['name'],false).'"])){';
                    $php .= "\r\n\t\t\t\t\t\t".'if(!is_dir("media/image/")){';
                    $php .= "\r\n\t\t\t\t\t\t\t".'mkdir("media/image/",0777,true);';
                    $php .= "\r\n\t\t\t\t\t\t".'}';
                    $php .= "\r\n\t\t\t\t\t\t".'if(!is_dir("media/media/")){';
                    $php .= "\r\n\t\t\t\t\t\t\t".'mkdir("media/media/",0777,true);';
                    $php .= "\r\n\t\t\t\t\t\t".'}';
                    $php .= "\r\n\t\t\t\t\t\t".'if(!is_dir("media/file/")){';
                    $php .= "\r\n\t\t\t\t\t\t\t".'mkdir("media/file/",0777,true);';
                    $php .= "\r\n\t\t\t\t\t\t".'}';

                    $php .= "\r\n\t\t\t\t\t\t".'foreach($config["image_allowed"] as $image_allowed){'."// whitelist mimetype";
                    $php .= "\r\n\t\t\t\t\t\t\t".'$mimetype_image_allowed[] = $image_allowed["mimetype"];'."// create list";
                    $php .= "\r\n\t\t\t\t\t\t".'}'."";

                    $php .= "\r\n\t\t\t\t\t\t".'$parse_file = explode(";",substr($_POST["'.str2var($input['name'],false).'"],5,strlen($_POST["'.str2var($input['name'],false).'"])));'."// parsing file";
                    $php .= "\r\n\t\t\t\t\t\t".'$file_'.str2var($input['name'],false).' = base64_decode(str_replace("base64,","",$parse_file[1]));';

                    $php .= "\r\n\t\t\t\t\t\t".'if(in_array(strtolower($parse_file[0]),$mimetype_image_allowed)){'."// whitelist image";
                    $php .= "\r\n\t\t\t\t\t\t\t".'$ext = "tmp";';
                    $php .= "\r\n\t\t\t\t\t\t\t".'foreach($config["image_allowed"] as $image_allowed){'."// searching extention";
                    $php .= "\r\n\t\t\t\t\t\t\t\t".'if(strtolower($parse_file[0])==$image_allowed["mimetype"]){'."// filter";
                    $php .= "\r\n\t\t\t\t\t\t\t\t\t".'$invalid_file = false;';
                    $php .= "\r\n\t\t\t\t\t\t\t\t\t".'$ext = $image_allowed["ext"];'."";
                    $php .= "\r\n\t\t\t\t\t\t\t\t\t".'$file_name = "'.str2var($input['name'],false).'-" . sha1($file_'.str2var($input['name'],false).').".".$ext;';
                    $php .= "\r\n\t\t\t\t\t\t\t\t\t".'file_put_contents("media/image/".$file_name,$file_'.str2var($input['name'],false).');';
                    $php .= "\r\n\t\t\t\t\t\t\t\t\t".'$input["'.str2var($input['name'],false).'"] = $main_url ."/media/image/".  $mysql->escape_string($file_name);';
                    $php .= "\r\n\t\t\t\t\t\t\t\t".'}'."";
                    $php .= "\r\n\t\t\t\t\t\t\t".'}'."";
                    $php .= "\r\n\t\t\t\t\t\t".'}else{'."// whitelist files";
                    $php .= "\r\n\t\t\t\t\t\t\t".'$invalid_file = true;';
                    $php .= "\r\n\t\t\t\t\t\t\t".'$ext = "tmp";'."";
                    $php .= "\r\n\t\t\t\t\t\t\t".'foreach($config["file_allowed"] as $file_allowed){'."";
                    $php .= "\r\n\t\t\t\t\t\t\t\t".'if(strtolower($parse_file[0])==$file_allowed["mimetype"]){'."";
                    $php .= "\r\n\t\t\t\t\t\t\t\t\t".'$invalid_file = false;';
                    $php .= "\r\n\t\t\t\t\t\t\t\t\t".'$ext = $file_allowed["ext"];'."";
                    $php .= "\r\n\t\t\t\t\t\t\t\t\t".'$file_name = "'.str2var($input['name'],false).'-" . sha1($file_'.str2var($input['name'],false).').".".$ext;';
                    $php .= "\r\n\t\t\t\t\t\t\t\t\t".'file_put_contents("media/file/".$file_name,$file_'.str2var($input['name'],false).');';
                    $php .= "\r\n\t\t\t\t\t\t\t\t\t".'$input["'.str2var($input['name'],false).'"] = $main_url ."/media/image/".  $mysql->escape_string($file_name);';
                    $php .= "\r\n\t\t\t\t\t\t\t\t".'}'."";
                    $php .= "\r\n\t\t\t\t\t\t\t".'}'."";
                    $php .= "\r\n\t\t\t\t\t\t".'}'."";
                    $php .= "\r\n\t\t\t\t\t".'}'."";
                } else
                {
                    $php .= "\r\n\t\t\t\t\t".'if(isset($_POST["'.str2var($input['name'],false).'"])){';
                    $php .= "\r\n\t\t\t\t\t\t".'$input["'.str2var($input['name'],false).'"] = $mysql->escape_string($_POST["'.str2var($input['name'],false).'"]);';
                    $php .= "\r\n\t\t\t\t\t".'}'."\r\n";
                }


            }
        }

        $sql_column = $sql_value = array();
        if(!isset($_is_auth))
        {
            $_is_auth = false;
        }
        if($_is_auth == 'true')
        {
            // TODO: php -+----+-----+- Insert by User
            $php .= "\t\t\t\t".'// TO'.'DO: -+----+-----+- Insert by User '."\r\n";
            $php .= "\r\n\t\t\t\t\t".'$input["'.$var_username.'"] = $php_auth_user ;';

            $sql_column[] = '`'.$var_username.'`';
            $sql_value[] = '\'".$input["'.$var_username.'"]."\'';
        }

        foreach($form['input'] as $input)
        {
            if(!in_array($input['type'],$remove_from_query))
            {
                $sql_column[] = '`'.str2SQL($input['name']).'`';
                $sql_value[] = '\'".$input["'.str2var($input['name'],false).'"]."\'';
            }
        }
        if(!isset($form['msg_ok']))
        {
            $form['msg_ok'] = 'Successfully';
        }
        if(!isset($form['msg_error']))
        {
            $form['msg_error'] = 'Error';
        }
        $sql_query = 'INSERT INTO `'.$form['table'].'` ('.implode(',',$sql_column).') VALUES ('.implode(',',$sql_value).' )';
        $php .= "\r\n\t\t\t\t\t".'$sql_query = "'.$sql_query.'";';

        if($is_file_upload == false)
        {
            $php .= "\r\n\t\t\t\t\t".'if($query = $mysql->query($sql_query)){';
            $php .= "\r\n\t\t\t\t\t\t".'$rest_api["message"] = "'.addslashes($form['msg_ok']).'";';
            $php .= "\r\n\t\t\t\t\t\t".'$rest_api["title"] = "Successfully";';
            $php .= "\r\n\t\t\t\t\t".'}else{';
            $php .= "\r\n\t\t\t\t\t\t".'$rest_api["message"] = "Form input and SQL Column do not match.";';
            $php .= "\r\n\t\t\t\t\t\t".'$rest_api["title"] = "Fatal Error!";';
            $php .= "\r\n\t\t\t\t\t".'}';
        } else
        {
            $php .= "\r\n\t\t\t\t\t".'if($invalid_file ==false){';
            $php .= "\r\n\t\t\t\t\t\t".'if($query = $mysql->query($sql_query)){';
            $php .= "\r\n\t\t\t\t\t\t\t".'$rest_api["message"] = "'.addslashes($form['msg_ok']).'";';
            $php .= "\r\n\t\t\t\t\t\t\t".'$rest_api["title"] = "Successfully";';
            $php .= "\r\n\t\t\t\t\t\t".'}else{';
            $php .= "\r\n\t\t\t\t\t\t\t".'$rest_api["message"] = "Form input and SQL Column do not match.";';
            $php .= "\r\n\t\t\t\t\t\t\t".'$rest_api["title"] = "Fatal Error!";';
            $php .= "\r\n\t\t\t\t\t\t".'}';
            $php .= "\r\n\t\t\t\t\t".'}else{';
            $php .= "\r\n\t\t\t\t\t\t\t".'$rest_api["message"] = "Please upload valid file";';
            $php .= "\r\n\t\t\t\t\t\t\t".'$rest_api["title"] = "File invalid!";';
            $php .= "\r\n\t\t\t\t\t".'}';
        }
        $php .= "\r\n\t\t\t\t".'}else{';
        $php .= "\r\n\t\t\t\t\t".'$rest_api["message"] = "'.addslashes($form['msg_error']).'";';
        $php .= "\r\n\t\t\t\t\t".'$rest_api["title"] = "Notice!";';
        $php .= "\r\n\t\t\t\t".'}'."\r\n";
        $php .= "\r\n\t\t\t\t".'break;';
        $php .= "\r\n";
    }
    //}

    if($is_auth_support == true)
    {

        // TODO: php -+- Submit : Me
        $php .= "\t\t\t".'// TO'.'DO: -+- Submit : Me'."\r\n";
        $php .= "\t\t\t".'case "me":'."\r\n";

        // TODO: php -+----+- Auth User
        $php .= "\t\t\t\t".'// TO'.'DO: -+----+- Auth User'."\r\n";

        $php .= "\t\t\t\t".'$is_user = false;'."\r\n";
        $php .= "\t\t\t\t".'if(isset($_SERVER["PHP_AUTH_USER"])){'."\r\n";
        $php .= "\t\t\t\t\t".'$php_auth_user = $mysql->escape_string($_SERVER["PHP_AUTH_USER"]);'."\r\n";
        $php .= "\t\t\t\t\t".'$php_auth_pw = $mysql->escape_string($_SERVER["PHP_AUTH_PW"]);'."\r\n";
        $php .= "\t\t\t\t\t".'$auth_sql = "SELECT * FROM `'.$table_contain_user.'` WHERE `'.$field_username.'` = \'$php_auth_user\' AND `'.$field_password.'` = \'$php_auth_pw\'";'."\r\n";
        $php .= "\t\t\t\t\t".'if($result = $mysql->query($auth_sql)){'."\r\n";
        $php .= "\t\t\t\t\t\t".'$current_user = $result->fetch_array();'."\r\n";
        $php .= "\t\t\t\t\t\t".'if(isset($current_user["'.$field_username.'"])){'."\r\n";
        $php .= "\t\t\t\t\t\t\t".'$is_user = true;'."\r\n";
        $cols_user = array();
        $cols_table_user = $_SESSION['PROJECT']['tables'][$prefix_table]['cols'];
        foreach($cols_table_user as $fix_col_table_user)
        {
            if(($fix_col_table_user['type'] != 'id') && ($fix_col_table_user['type'] != 'as_password') && ($fix_col_table_user['type'] != 'as_username'))
            {
                $var = str2SQL($fix_col_table_user['title']);
                $cols_user[md5($var)] = $var;
            }
        }
        $update_cols = array();
        foreach($cols_user as $_col)
        {
            $php .= "\r\n\t\t\t\t\t\t\t".'$input["'.$_col.'"] = null;';
            $php .= "\r\n\t\t\t\t\t\t\t".'if(isset($_POST["'.$_col.'"])){';
            $php .= "\r\n\t\t\t\t\t\t\t\t".'$input["'.$_col.'"] = $mysql->escape_string($_POST["'.$_col.'"]);';
            $php .= "\r\n\t\t\t\t\t\t\t".'}'."\r\n";

            $update_cols[] = '`'.$_col.'` = \'".$input["'.$_col.'"]."\'';
        }
        $php .= "\t\t\t\t\t\t\t".'$update_me_sql = "UPDATE `'.$table_contain_user.'` SET '.implode(', ',$update_cols).' WHERE `'.$field_username.'`=\'$php_auth_user\'";'."\r\n";
        $php .= "\t\t\t\t\t\t\t".'if($query = $mysql->query($update_me_sql)){'."\r\n";
        $php .= "\t\t\t\t\t\t\t\t".'$rest_api=array("data"=>array("status"=>200,"title"=>"Successfully"),"title"=>"Successfully","message"=>"You have successfully updated your data.");'."\r\n";
        $php .= "\t\t\t\t\t\t\t".'}else{'."\r\n";
        $php .= "\t\t\t\t\t\t\t\t".'$rest_api=array("data"=>array("status"=>200,"title"=>"Error"),"title"=>"Error","message"=>"You have fail updated your data.");'."\r\n";
        $php .= "\t\t\t\t\t\t\t".'}'."\r\n";

        $php .= "\t\t\t\t\t\t".'}'."\r\n";
        $php .= "\t\t\t\t\t".'}'."\r\n";

        $php .= "\t\t\t\t\t".'if($is_user == false){'."\r\n";
        $php .= "\t\t\t\t\t\t".'$rest_api=array("data"=>array("status"=>401,"title"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");'."\r\n";
        $php .= "\t\t\t\t\t\t".'break;'."\r\n";
        $php .= "\t\t\t\t\t".'}'."\r\n";
        $php .= "\t\t\t\t".'}else{'."\r\n";
        $php .= "\t\t\t\t\t".'$rest_api=array("data"=>array("status"=>401,"title"=>"Unauthorized"),"title"=>"Unauthorized","message"=>"Sorry, you cannot see list resources.");'."\r\n";
        $php .= "\t\t\t\t\t".'break;'."\r\n";
        $php .= "\t\t\t\t".'}'."\r\n";


        $php .= "\r\n\t\t\t\t".'break;';
        $php .= "\r\n";
    }
    $php .= "\r\n\t\t".'}'."\r\n";
}

$php .= "\r\n";

$php .= "\r\n\t".'break;';
$php .= "\r\n";
$php .= "\r\n".'}'."\r\n";

$php .= "\r\n";
$php .= "\r\n".'header(\'Access-Control-Allow-Origin: *\');';
$php .= "\r\n".'header(\'Access-Control-Allow-Credentials: true\');';
$php .= "\r\n".'header(\'Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS\');';
$php .= "\r\n".'header(\'Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization,X-Authorization\');';


$php .= "\r\n".'if (!isset($_GET["callback"])){';
$php .= "\r\n\t".'header(\'Content-type: application/json\');';
$php .= "\r\n\t".'if(defined("JSON_UNESCAPED_UNICODE")){';
$php .= "\r\n\t\t".'echo json_encode($rest_api,JSON_UNESCAPED_UNICODE);';
$php .= "\r\n\t".'}else{';
$php .= "\r\n\t\t".'echo json_encode($rest_api);';
$php .= "\r\n\t".'}'."\r\n";
$php .= "\r\n".'}else{';
$php .= "\r\n\t".'if(defined("JSON_UNESCAPED_UNICODE")){';
$php .= "\r\n\t\t".'echo strip_tags($_GET["callback"]) ."(". json_encode($rest_api,JSON_UNESCAPED_UNICODE). ");" ;';
$php .= "\r\n\t".'}else{';
$php .= "\r\n\t\t".'echo strip_tags($_GET["callback"]) ."(". json_encode($rest_api) . ");" ;';
$php .= "\r\n\t".'}'."\r\n";
$php .= "\r\n".'}';

// TODO: DEBUG PHP CODE
if(JSM_DEBUG == true)
{
    @mkdir(JSM_DEBUG_FOLDER.$_SESSION['PROJECT']['app']['prefix'].'\\',0777);
    @file_put_contents(JSM_DEBUG_FOLDER.$_SESSION['PROJECT']['app']['prefix'].'\rest-api.php',$php);
}


$sql = null;
$sql .= "\r\n-- CREATE DATABASE IF NOT EXISTS `".$raw_php_mysql_config['php_sql_config']['dbase']."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci	;";
$sql .= "\r\n-- USE `".$raw_php_mysql_config['php_sql_config']['dbase']."`;"."\r\n";


foreach($tables as $table)
{
    $table_auth = false;
    $is_password = false;
    $is_username = false;
    foreach($table['cols'] as $cols)
    {
        if($cols['type'] == 'as_password')
        {
            $is_password = true;
        }
        if($cols['type'] == 'as_username')
        {
            $is_username = true;
        }
    }
    if(($is_password == true) & ($is_username == true))
    {
        $table_auth = true;
    }


    if(in_array($table['prefix'],$_tables_used))
    {
        $sql .= "\r\n-- Delete ".$table['prefix']." table";
        $sql .= "\r\n-- DROP TABLE `".$table['prefix']."`;";
        $sql .= "\r\n";
        $sql .= "\r\n-- Create ".$table['prefix']." table";
        $sql .= "\r\nCREATE TABLE IF NOT EXISTS `".$table['prefix']."` (";
        $found_id = false;
        foreach($table['cols'] as $col)
        {
            if($col['type'] == 'id')
            {
                if($found_id == false)
                {
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` int(11) NOT NULL AUTO_INCREMENT ,";
                    $col_id = str2SQL($col['title']);
                    $found_id = true;
                }

            }
        }

        if(!isset($col_id))
        {
            $col_id = 'id';
            $sql .= "\r\n\t`id` int(11) NOT NULL AUTO_INCREMENT ,";
        }
        $new_colums = array();
        foreach($table['cols'] as $col)
        {
            if($col['type'] != 'id')
            {
                $new_colums[str2SQL($col['title'])] = $col;
            }
        }

        foreach($new_colums as $col)
        {
            switch($col['type'])
            {
                case 'heading-1':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'heading-2':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'heading-3':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'heading-4':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'text':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` tinytext NOT NULL ,";
                    break;
                case 'slidebox':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` text NOT NULL ,";
                    break;
                case 'images':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` longtext NOT NULL ,";
                    break;
                case 'video':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'ytube':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'gmap':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'webview':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'appbrowser':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'audio':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'share_link':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'link':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;
                case 'icon':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(100) NOT NULL ,";
                    break;
                case 'paragraph':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` tinytext NOT NULL ,";
                    break;
                case 'to_trusted':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` text NOT NULL ,";
                    break;
                case 'rating':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` int(1) NOT NULL DEFAULT '5',";
                    break;
                case 'as_username':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(128) NOT NULL ,";
                    break;
                case 'as_password':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(128) NOT NULL ,";
                    break;


                case 'number':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` int(11) NOT NULL ,";
                    break;

                case 'float':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` float NOT NULL ,";
                    break;

                case 'date':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` date NOT NULL ,";
                    break;

                case 'datetime':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` datetime NOT NULL ,";
                    break;

                case 'date_php':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` date NOT NULL ,";
                    break;

                case 'datetime_php':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` datetime NOT NULL ,";
                    break;

                case 'datetime_string':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` datetime NOT NULL ,";
                    break;

                case 'app_email':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;

                case 'app_sms':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;

                case 'app_call':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;

                case 'app_geo':
                    $sql .= "\r\n\t`".str2SQL($col['title'])."` varchar(360) NOT NULL ,";
                    break;

            }

        }
        foreach($new_colums as $col)
        {
            if($table_auth == true)
            {
                if($col['type'] == 'as_username')
                {
                    $sql .= "\r\n\tUNIQUE KEY `".str2SQL($col['title'])."` (`".str2SQL($col['title'])."`),";
                }
            }
        }

        $sql .= "\r\n\tPRIMARY KEY (`".$col_id."`)";

        $sql .= "\r\n ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        $sql .= "\r\n\r\n"."\r\n";
    }
}
//ALTER TABLE `categorie` ADD `dfsd` INT NOT NULL ;

$app_tables = $_SESSION["PROJECT"]['tables'];
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-code fa-stack-1x"></i></span>Backend Tools -&raquo; (IMAB) REST-API Generator (PHP + SQL)</h4>';
$content .= '<p><span class="label label-info">'.__('Note').'</span> : '.__('This features is used for <code>Existing CMS</code> or use <code>PhpMyAdmin</code> as a back-end.').'</p>';


$content .= '<ul class="nav nav-tabs">';
$content .= '<li class="active"><a href="#code" data-toggle="tab">'.__('Code Generator').'</a></li>';
$content .= '<li><a href="#help" data-toggle="tab" >'.__('How To Use?').'</a></li>';
$content .= '</ul>';
$content .= '<br/>';


// TODO: --|-- SAVE JSON FILE
if(isset($_POST['sql_save']))
{
    //print_r($_POST['php_sql']);

    $data = array();
    $z = 0;
    foreach($_POST['php_sql'] as $php_sql)
    {
        if(isset($php_sql['name']))
        {
            if(!is_numeric($php_sql['limit']))
            {
                $php_sql['limit'] = 500;
            }
            if(isset($php_sql['auth']))
            {
                $php_sql_auth = 'true';
            } else
            {
                $php_sql_auth = 'false';
            }
            if(isset($php_sql['owned-by-me']))
            {
                $owned_by_me = 'true';
            } else
            {
                $owned_by_me = 'false';
            }
            $data['php_sql'][$z]['name'] = $php_sql['name'];
            //$data['php_sql'][$z]['sort'] = $php_sql['sort'];
            $data['php_sql'][$z]['limit'] = $php_sql['limit'];
            $data['php_sql'][$z]['auth'] = $php_sql_auth;
            $data['php_sql'][$z]['owned-by-me'] = $owned_by_me;

            $z++;
        }
    }

    file_put_contents($php_mysql_path,json_encode($data));

    $data = null;

    // TODO: Save Settings
    $data['php_sql_config'] = $raw_php_mysql_config['php_sql_config'];
    $data['php_sql_config']['host'] = $_POST['php_sql_config']['host'];
    $data['php_sql_config']['uname'] = $_POST['php_sql_config']['uname'];
    $data['php_sql_config']['pwd'] = $_POST['php_sql_config']['pwd'];
    $data['php_sql_config']['dbase'] = $_POST['php_sql_config']['dbase'];
    $data['php_sql_config']['url'] = $_POST['php_sql_config']['url'];

    file_put_contents($php_mysql_path_config,json_encode($data));


    buildIonic($file_name);
    header('Location: ./?page=z-php-sql-restapi-generator&err=null&notice=save');
    die();
}


$content .= '<div class="tab-content">';
$content .= '<div class="tab-pane active" id="code">';
$form_input = null;
$form_input .= '<div>';


$i = 0;

//$select_sort[] = array("label" => "ASC", "value" => "ASC");
//$select_sort[] = array("label" => "DESC", "value" => "DESC");

$form_input .= '<blockquote class="blockquote blockquote-danger">';
$form_input .= '<h4>'.__('The rules that apply are:').'</h4>';
$form_input .= '<ol>';
$form_input .= '<li>'.__('When making changes in <code>tables</code>, <code>forms</code> and <code>this settings</code>, you must replace the code that has been uploaded as well.').'</li>';
$form_input .= '<li>'.__('<code>Checked the tables</code> that you want to display on the JSON Files.').'</li>';
$form_input .= '<li>'.__('If you need a column/value did not want to appear in JSON, go to <code>(IMAB) Tables</code> and unchecked <code>Source</code> of column that does not want to appear.').'</li>';
$form_input .= '<li>'.__('<code>Required Auth</code> will be available when a table contain <code>as_password</code> and <code>as_username</code> in column type, and rest routes <code>me</code> and <code>auth</code> will be available').'</li>';
$form_input .= '<li>'.__('Column contain character <code>.</code>, <code>:</code>, <code>\'</code> and <code>[]</code> not support for SQL Code.').'</li>';
$form_input .= '<li>'.__('<code>Update URL List Item</code> button only for default table, for table with <code>dynamic 1st param</code> or <code>relation</code> that you should edit table manually operated').'</li>';
$form_input .= '</ol>';
$form_input .= '</blockquote>';
$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-3">';
$form_input .= '<h4>'.__('Database').'</h4>';


$form_input .= $bs->FormGroup('php_sql_config[host]','default','text',__('Host'),'localhost',__('your sql host'),' ','5',htmlentities($raw_php_mysql_config['php_sql_config']['host']));
$form_input .= $bs->FormGroup('php_sql_config[uname]','default','text',__('Username'),'username',__('your sql username'),' ','5',htmlentities($raw_php_mysql_config['php_sql_config']['uname']));
$form_input .= $bs->FormGroup('php_sql_config[pwd]','default','text',__('Password'),'password',__('your sql password'),' ','5',htmlentities($raw_php_mysql_config['php_sql_config']['pwd']));
$form_input .= $bs->FormGroup('php_sql_config[dbase]','default','text',__('Database'),'Database',__('your sql database'),' ','5',htmlentities($raw_php_mysql_config['php_sql_config']['dbase']));
$form_input .= $bs->FormGroup('php_sql_config[url]','default','text',__('URL Site'),'http://anaski.net/app/',__('URL planning to be used'),' ','5',htmlentities($raw_php_mysql_config['php_sql_config']['url']));

$form_input .= '</div>';

$var_password = $var_username = '???';
$is_auth_support = false;
foreach($app_tables as $app_table)
{
    $is_password = false;
    $is_username = false;
    foreach($app_table['cols'] as $cols)
    {
        if($cols['type'] == 'as_password')
        {
            $is_password = true;
            $var_password = $cols['title'];
        }
        if($cols['type'] == 'as_username')
        {
            $is_username = true;
            $var_username = $cols['title'];
        }
    }
    if(($is_password == true) & ($is_username == true))
    {
        $is_auth_support = true;
    }
}
$statement_info = null;
$form_input .= '<div class="col-md-9">';
$form_input .= '<h4>'.__('Tables').'</h4>';

$form_input .= '<table class="table table-striped">';
$form_input .= '<thead>';
$form_input .= '<tr>
<th style="width:50px"></th>
<th>'.__('Tables').'</th>
<th style="width:100px">'.__('Limit').'</th>
<th style="width:160px">'.__('JSON Listing').'</th>
<th style="width:160px">'.__('Statement').'</th>
<th style="width:160px">'.__('(IMAB) Tables').'</th>
</tr>';
$form_input .= '</thead>';
$form_input .= '<tbody>';

foreach($app_tables as $app_table)
{

    if(isset($app_table['prefix']))
    {


        $is_password = false;
        $is_username = false;
        foreach($app_table['cols'] as $cols)
        {

            if($cols['type'] == 'as_password')
            {
                $is_password = true;

            }
            if($cols['type'] == 'as_username')
            {
                $is_username = true;

            }
        }


        $table_info = get_table_info($app_table['prefix']);
        if($table_info == null)
        {
            $table_info['name'] = 'none';
            $table_info['sort'] = 'ASC';
            $table_info['limit'] = 100;
            $table_info['auth'] = false;
            $table_info['owned-by-me'] = false;
        }

        $readonly = '';
        $note = '';
        foreach($app_table['cols'] as $cols)
        {

            if(preg_match("/\.|\[|\(|\:|\'/",$cols['title']))
            {
                $readonly = 'readonly disabled';
                $note = '<blockquote class="blockquote blockquote-danger"><h4>Ops, disable...!!!</h4><p>'.__('Reason: Column contain character').' <code>.</code>, <code>:</code>, <code>\'</code> '.__('and').' <code>[]</code>. '.__('Found in variable column').' <code>'.htmlentities($cols['title']).'</code> '.__('is not compatible, replace with').' <code>'.str_replace(array(
                    '[',
                    ']',
                    '(',
                    ')',
                    '.'),'_',str2SQL($cols['title'])).'</code> '.__('in (IMAB) Tables Menu').'</p></blockquote>';
            }
        }
        $checked = '';
        if($table_info['name'] != 'none')
        {
            $checked = 'checked';
        }


        $form_input .= '<tr>';

        $form_input .= '<td>';
        $form_input .= $bs->FormGroup('php_sql['.$i.'][name]','inline','checkbox',' ','','',$readonly.' '.$checked,'8',$app_table['prefix']);
        $form_input .= '</td>';

        $form_input .= '<td>';
        if(!isset($app_table['version']))
        {
            $app_table['version'] = '?';
        }
        $form_input .= '<a href="./?page=tables&prefix='.str2var($app_table['title'],false).'" target="_blank"><strong>'.ucwords(trim($app_table['title'])).'</strong></a> ('.ucwords(trim($app_table['version'])).')'.$note;
        if(!is_array($app_table['cols']))
        {
            $app_table['cols'] = array();
        }
        $z_cols = array();
        foreach($app_table['cols'] as $cols)
        {
            $z_cols[] = '<code>'.$cols['title'].'</code>';
        }
        $form_input .= '<blockquote class="blockquote blockquote-info">'.__('Columns').': '.implode(', ',$z_cols).'</blockquote>';

        if(($is_password == true) && ($is_password == true))
        {
            $form_input .= '<blockquote class="blockquote blockquote-info">'.__('This table contain password, password not show in JSON Data').'</blockquote>';
        }

        $form_input .= '</td>';

        //$form_input .= '<td>';
        //$form_input .= $bs->FormGroup('php_sql[' . $i . '][sort]', 'inline', 'select', '', $_select_sort, '', $readonly, '8');
        //$form_input .= '</td>';

        $form_input .= '<td>';
        $form_input .= $bs->FormGroup('php_sql['.$i.'][limit]','inline','text','','','',$readonly,'12',$table_info['limit']);
        $form_input .= '</td>';


        if($is_auth_support == false)
        {
            $auth_readonly = 'readonly disabled';
        } else
        {
            $auth_readonly = '';
        }


        $auth_checked = "";
        if(!isset($table_info['auth']))
        {
            $table_info['auth'] = 'false';
        }
        if($table_info['auth'] == 'true')
        {
            $auth_checked = 'checked';
        }
        $form_input .= '<td>';
        $form_input .= $bs->FormGroup('php_sql['.$i.'][auth]','inline','checkbox','Required Auth','','',$auth_readonly.' '.$auth_checked,'12','true');
        $form_input .= '</td>';


        if($is_auth_support == false)
        {
            $owned_by_me_readonly = 'readonly disabled';
        } else
        {
            $owned_by_me_readonly = '';
        }


        $owned_by_me_checked = "";
        if(!isset($table_info['owned-by-me']))
        {
            $table_info['owned-by-me'] = 'false';
        }
        if($table_info['owned-by-me'] == 'true')
        {
            $owned_by_me_checked = 'checked';
        }

        $form_input .= '<td>';
        $form_input .= $bs->FormGroup('php_sql['.$i.'][owned-by-me]','inline','checkbox','Current User','','',$owned_by_me_readonly.' '.$owned_by_me_checked,'12','true');
        $form_input .= '</td>';
        $column_id = 'id';
        foreach($app_table['cols'] as $_col)
        {
            if($_col['type'] == 'id')
            {
                $column_id = $_col['title'];
            }
        }
        $url_list_item = $raw_php_mysql_config['php_sql_config']['url'].'/rest-api.php?json='.str2var($app_table['title'],false).'&sort=asc';
        $url_single_item = $raw_php_mysql_config['php_sql_config']['url'].'/rest-api.php?json='.str2var($app_table['title'],false).'&'.$column_id.'=';

        $form_input .= '<td>';
        $form_input .= '<a class="btn btn-xs btn-danger '.$readonly.'" target="_blank" href="./?page=tables&prefix='.(str2var($app_table['title'],false)).'&source_json=online&url_list_item='.urlencode($url_list_item).'&url_single_item='.urlencode($url_single_item).'&update">'.__('Update URL').'</a>';
        $form_input .= '&nbsp;<a class="btn btn-xs btn-primary '.$readonly.'" target="_blank" href="'.($url_list_item).'">'.__('Check URL').'</a>';

        $form_input .= '</td>';


        $form_input .= '</tr>';

        if($table_info['name'] != 'none')
        {
            if($table_info['auth'] == 'true')
            {
                $statement_info .= '<br/><code>SELECT * FROM `'.strtolower(trim($app_table['title'])).'` WHERE `'.$var_username.'` = \'AUTH_USER\' AND `'.$var_password.'` = \'AUTH_PW\'</code>';
            }
            if($table_info['owned-by-me'] == 'true')
            {
                $statement_info .= '<br/><code>SELECT * FROM `'.strtolower(trim($app_table['title'])).'` WHERE `'.$var_username.'` = \'AUTH_USER\' </code>';
            } else
            {
                $statement_info .= '<br/><code>SELECT * FROM `'.strtolower(trim($app_table['title'])).'`</code>';
            }
        }


        $i++;
    }
}
$form_input .= '</tbody>';
$form_input .= '</table>';


$form_input .= '<blockquote class="blockquote blockquote-warning">';
$form_input .= __('SQL Queries may be applied:').$statement_info;
$form_input .= '</blockquote>';

$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= '</div>';
$form_input .= '<div class="clearfix"><br/></div>';
$form_input .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,array(array(
        'name' => 'sql_save',
        'label' => __('Save Setting'),
        'tag' => 'submit',
        'color' => 'primary'),array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));


$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">'.__('General').'</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= notice();
$content .= $bs->Forms('app-setup','','post','default',$form_input);
$content .= '</div>';
$content .= '</div>';

@mkdir('output/'.$file_name.'/backend/php-sql',0777,true);
@file_put_contents('output/'.$_SESSION['PROJECT']['app']['prefix'].'/backend/php-sql/rest-api.php',$php);
@file_put_contents('output/'.$_SESSION['PROJECT']['app']['prefix'].'/backend/php-sql/rest-api.sql',$sql);


$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">'.__('SQL Code').'</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= '<p>'.__('If you already have a table in SQL (using other CMS) do not use the code below, but you do not have a SQL database, log in to phpMyAdmin and create database then create tables using this code:').'</p>
<blockquote class="blockquote blockquote-info"><p>'.__('Copy and').' <a href="./output/'.$file_name.'/backend/php-sql/rest-api.sql">'.__('execution this code').'</a> '.__('in in PHPMyAdmin').'</p></blockquote>
<textarea id="code-sql" name="code">'.htmlentities($sql).'</textarea>';
$content .= '</div>';
$content .= '</div>';


$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">'.__('PHP Code').'</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= '<p>'.__('For displaying JSON Data from your SQL, use this PHP Code:').'</p>';
$content .= '<blockquote class="blockquote blockquote-info"><p>'.__('Save this file example:').' <kbd>rest-api.php</kbd> (<a target="_blank" href="./output/'.$file_name.'/backend/php-sql/rest-api.php">Live test</a>)</p></blockquote>';
$content .= '<textarea id="code-php" name="code">'.htmlentities($php).'</textarea>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

$content .= '<div class="tab-pane" id="help">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">'.__('Help').'</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';

$content .= '<blockquote class="blockquote blockquote-info">';
$content .= '<p>'.__('To be able to display data from SQL into the application, perform the following steps:').'</p>';
$content .= '<ol>';
$content .= '<li>'.__('Create some tables in <code>(IMAB) Tables</code>').'</li>';
$content .= '<li>'.__('Then, please complete the form on the <code>Code Generator</code> tab, and then click <code>Save Setting</code> button.').'</li>';
$content .= '<li>'.__('Open a text editor (notepad, nano or vi) then copy the PHP Code on Code Tab and paste into your editor, then click save with filename <code>rest-api.php</code>.').'</li>';
$content .= '<li>'.__('Upload <code>rest-api.php</code> to your server.').'</li>';
$content .= '<li>'.__('And you will get the <code>URL List Item</code> and <code>URL Single Item</code> as below.').'</li>';
$content .= '<li>'.__('Last step, please update the link on <code>URL List Item</code> and <code>URL Single Item</code> on your <code>(IMAB) Tables</code>.').'</li>';
$content .= '</ol>';
$content .= '</blockquote>';

$content .= '<table class="table table-striped">';
if(!isset($_SESSION['PROJECT']['php_sql']))
{
    $_SESSION['PROJECT']['php_sql'] = array();
}
$is_checked = array();
foreach($_SESSION['PROJECT']['php_sql'] as $table_checked)
{
    $varName = $table_checked['name'];
    $is_checked[$varName] = $table_checked['name'];
}


foreach($app_tables as $app_table)
{
    $new_colums = array();
    foreach($app_table['cols'] as $col)
    {
        $new_colums[str2SQL($col['title'])] = $col;
    }

    if(isset($is_checked[$app_table['prefix']]))
    {

        $content .= '<tr>'."\r\n";
        $content .= '<td colspan="4"><h5 class="text-success">TABLE '.strtoupper(htmlentities($app_table['prefix'])).'</h5></td>'."\r\n";
        $content .= '</tr>'."\r\n";

        $content .= '<tr>';
        $content .= '<td>'.__('Method').'</td>';
        $content .= '<td>'.__('JSON For').'</td>';
        $content .= '<td>'.__('Filter By').'</td>';
        $content .= '<td>'.__('URL').'</td>';
        $content .= '</tr>';

        $content .= '<tr>'."\r\n";
        $content .= '<td>GET</td>'."\r\n";
        $content .= '<td><span class="label label-primary">URL List Item</span></td>'."\r\n";
        $content .= '<td>all</td>'."\r\n";
        $content .= '<td><code>'.$raw_php_mysql_config['php_sql_config']['url'].'/rest-api.php?json='.$app_table['prefix'].'</code></td>'."\r\n";
        $content .= '</tr>'."\r\n";

        $content .= '<tr>'."\r\n";
        $content .= '<td>GET</td>'."\r\n";
        $content .= '<td><span class="label label-primary">URL List Item</span></td>'."\r\n";
        $content .= '<td>all</td>'."\r\n";
        $content .= '<td><code>'.$raw_php_mysql_config['php_sql_config']['url'].'/rest-api.php?json='.$app_table['prefix'].'&order=random&sort=asc</code></td>'."\r\n";
        $content .= '</tr>'."\r\n";

        foreach($new_colums as $col)
        {
            if($col['type'] !== 'id')
            {
                $content .= '<tr>'."\r\n";
                $content .= '<td>GET</td>'."\r\n";
                $content .= '<td><span class="label label-primary">URL List Item</span></td>'."\r\n";
                $content .= '<td>all</td>'."\r\n";
                $content .= '<td><code>'.$raw_php_mysql_config['php_sql_config']['url'].'/rest-api.php?json='.$app_table['prefix'].'&order='.str2SQL($col['title']).'</code></td>'."\r\n";
                $content .= '</tr>'."\r\n";
            }
        }


        foreach($new_colums as $col)
        {
            if($col['type'] !== 'id')
            {
                $content .= '<tr>'."\r\n";
                $content .= '<td>GET</td>'."\r\n";
                $content .= '<td><span class="label label-info">URL List Item + 1st param</span></td>'."\r\n";
                $content .= '<td>'.(str2SQL($col['title'])).'</td>'."\r\n";
                $content .= '<td><code>'.$raw_php_mysql_config['php_sql_config']['url'].'/rest-api.php?'.str2SQL($col['title']).'=-1&json='.$app_table['prefix'].'</code></td>'."\r\n";
                $content .= '</tr>'."\r\n";
            }
        }
        $exist_id = false;
        foreach($new_colums as $col)
        {
            if($col['type'] == 'id')
            {
                if($exist_id == false)
                {
                    $content .= '<tr>'."\r\n";
                    $content .= '<td>GET</td>'."\r\n";
                    $content .= '<td><span class="label label-success">URL Single Item</span></td>'."\r\n";
                    $content .= '<td>'.(str2SQL($col['title'])).'</td>'."\r\n";
                    $content .= '<td><code>'.$raw_php_mysql_config['php_sql_config']['url'].'/rest-api.php?json='.$app_table['prefix'].'&'.str2SQL($col['title']).'=</code></td>'."\r\n";
                    $content .= '</tr>'."\r\n";
                    $exist_id = true;
                }
            }
        }
    }
}

if(isset($_SESSION['PROJECT']['forms']))
{
    $forms = $_SESSION['PROJECT']['forms'];
    $content .= '<tr>'."\r\n";
    $content .= '<td colspan="4"><h5 class="text-success">'.__('FORM SUBMIT').'</h5></td>'."\r\n";
    $content .= '</tr>'."\r\n";
    $content .= '<tr>';
    $content .= '<td>'.__('Method').'</td>';
    $content .= '<td>'.__('JSON For').'</td>';
    $content .= '<td>'.__('Filter By').'</td>';
    $content .= '<td>'.__('URL').'</td>';
    $content .= '</tr>';
    foreach($forms as $form)
    {
        $content .= '<tr>'."\r\n";
        $content .= '<td>POST</td>'."\r\n";
        $content .= '<td><span class="label label-warning">form '.str2SQL($form['title']).'</span></td>'."\r\n";
        $content .= '<td>-</td>'."\r\n";
        $content .= '<td><code>'.$raw_php_mysql_config['php_sql_config']['url'].'/rest-api.php?form='.$form['table'].'&json=submit</code></td>'."\r\n";
        $content .= '</tr>'."\r\n";
    }
}

$content .= '</tbody>';
$content .= '</table>';
$content .= '<blockquote class="blockquote blockquote-danger">';
$content .= '<h4>'.__('The rules that apply are:').'</h4>';
$content .= '<ol>';
$content .= '<li>'.__('For create dynamic data that you must use first parameter query.<br/><span class="label label-success">Correct</span> http://[your-domain]/[your_php_file].php?categories=[dinamic_value]&json=tables<br/><span class="label label-danger">Wrong</span> http://[your-domain]/[your_php_file].php?<s>json=tables</s>&categories=[dinamic_value]').'</li>';
$content .= '<li>'.__('For show data from descending to ascending, you can using parameter <code>sort=asc</code> or <code>sort=desc</code>').'</li>';
$content .= '<li>'.__('And to sort data based on a particular column can be used: <code>order=id</code>, <code>order=date</code> or etc.').'</li>';
$content .= '</ol>';
$content .= '</blockquote>';

$content .= '</div>';
$content .= '</div>';
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

$template->demo_url = $out_path.'/www/#/';
$template->title = $template->base_title.' | '.'Backend Tools -&raquo; REST-API Generator (PHP + SQL)';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>