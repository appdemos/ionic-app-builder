<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2018
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
$direction = null;
if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
{
    $direction = 'dir="rtl"';
}


if(isset($_GET['update']))
{
    $prefix_table = strtolower($_GET['prefix']);
    $new_url_list_item = urldecode($_GET['url_list_item']);
    $new_url_single_item = urldecode($_GET['url_single_item']);
    //echo $new_url_list_item;
    $data_table = json_decode(file_get_contents("projects/".$file_name."/tables.".$prefix_table.".json"),true);
    unset($data_table['tables'][$prefix_table]['db_url_dinamic']);
    $data_table['tables'][$prefix_table]['db_type'] = 'online';
    $data_table['tables'][$prefix_table]['db_url'] = $new_url_list_item;
    $data_table['tables'][$prefix_table]['db_url_single'] = $new_url_single_item;

    file_put_contents("projects/".$file_name."/tables.".$prefix_table.".json",json_encode($data_table));
    buildIonic($file_name);
    header('Location: ./?page=tables&prefix='.$prefix_table.'&err=null&notice=save');
    die();
}

// TODO: TABLE - DELETE
if(isset($_GET['delete']))
{
    $delete_prefix = str2var($_GET['delete']);
    @unlink("projects/".$file_name."/tables.".$delete_prefix.".json");
    buildIonic($file_name);
    header('Location: ./?page=tables&err=null&notice=delete');
    die();
}
$subpage_path = str2var($_SESSION["PROJECT"]['menu']['title']);
if(!isset($_SESSION["PROJECT"]['app']['direction']))
{
    $_SESSION["PROJECT"]['app']['direction'] = 'ltr';
}
$direction = null;
if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
{
    $direction = 'dir="rtl"';
}
if(!isset($_GET['prefix']))
{
    $_GET['prefix'] = 'custom';
}
if($_GET['prefix'] == '')
{
    $_GET['prefix'] = 'custom';
}

$_GET['prefix'] = strtolower($_GET['prefix']);
$_get['prefix'] = $_GET['prefix'];

$out_path = 'output/'.$file_name;
$content = null;
// TODO: FORM - SELECT TABLE
$z = 0;
$_tables_select[0] = array('value' => 'custom','label' => '--| '.__('New Table'));
$z++;
foreach(glob("system/includes/example-tables/*.json") as $templ_file)
{
    $_tables_select[$z] = array('value' => pathinfo($templ_file,PATHINFO_FILENAME),'label' => '--|-- '.__('Example Table').' '.ucwords(str_replace(array('_','-'),' ',pathinfo($templ_file,PATHINFO_FILENAME)).' '));
    $z++;
}
$z++;
foreach(glob("projects/".$file_name."/tables.*.json") as $filename)
{
    $_list_tabless = json_decode(file_get_contents($filename),true);
    if($_list_tabless['tables'] != null)
    {
        $_key = array_keys($_list_tabless['tables']);
        $key = $_key[0];
        if(!isset($_list_tabless['tables'][$key]['version']))
        {
            $_list_tabless['tables'][$key]['version'] = '?';
        }
        $_tables_select[$z] = array('label' => '* '.__('Edit table').' "'.ucwords($_list_tabless['tables'][$key]['title']).' - '.$_list_tabless['tables'][$key]['version'].'"','value' => $_list_tabless['tables'][$key]['prefix']);
        if($_GET['prefix'] == $_list_tabless['tables'][$key]['prefix'])
        {
            $_tables_select[$z]['active'] = true;
        }
        $z++;
    }
}
// TODO: --|-- SAVE TABLE
if(isset($_POST['table-save']))
{
    if(!empty($_POST['tables']['title']))
    {
        // Last Update
        $app_json = file_get_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/app.json');
        $app_config = json_decode($app_json,true);
        $app_config['app']['tb_version'] = 'Upd.'.date('ymdhi');
        file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/app.json',json_encode($app_config));

        if(!is_dir('projects/'.$file_name))
        {
            mkdir('projects/'.$file_name,0777,true);
        }
        $tables_prefix = str2var($_POST['tables']['title']);

        if($_POST['tables']['parent'] == 'none')
        {
            $_POST['tables']['parent'] = '';
        }

        $new_tables['tables'][$tables_prefix] = $_POST['tables'];
        $new_tables['tables'][$tables_prefix]['version'] = 'Upd.'.date('ymdhi');
        $new_tables['tables'][$tables_prefix]["prefix"] = $tables_prefix;
        $new_tables['tables'][$tables_prefix]["cols"] = array_values($_POST['tables']['cols']);
        if(!isset($_POST['tables']['languages']['error_messages']))
        {
            $new_tables['tables'][$tables_prefix]['languages']['error_messages'] = 'false';
        } else
        {
            $new_tables['tables'][$tables_prefix]['languages']['error_messages'] = 'true';
        }

        if(!isset($_POST['tables']['column-for-price']))
        {
            $new_tables['tables'][$tables_prefix]['column-for-price'] = 'none';
        } else
        {
            $new_tables['tables'][$tables_prefix]['column-for-price'] = $_POST['tables']['column-for-price'];
        }


        if(!isset($_POST['tables']['localstorage']))
        {
            $new_tables['tables'][$tables_prefix]['localstorage'] = 'none';
        } else
        {
            $new_tables['tables'][$tables_prefix]['localstorage'] = $_POST['tables']['localstorage'];
        }

        //fix
        $c = 0;
        $new_tables['tables'][$tables_prefix]["cols"][0]['type'] = 'id';

        foreach($new_tables['tables'][$tables_prefix]["cols"] as $fixcols)
        {
            $new_fix_colm[$c] = $fixcols;

            if(is_numeric($fixcols['title'][0]))
            {
                $fixcols['title'] = '_'.$fixcols['title'];
            }

            $new_fix_colm[$c]['title'] = str_replace(' ','_',$fixcols['title']);


            if(!isset($fixcols['json']))
            {
                $new_fix_colm[$c]['json'] = 'false';
            } else
            {
                $new_fix_colm[$c]['json'] = 'true';
            }
            if($fixcols['type'] == 'id')
            {
                $new_fix_colm[$c]['json'] = 'true';
            }
            if(isset($fixcols['page_list']))
            {
                $new_fix_colm[$c]['json'] = 'true';
            }
            if(isset($fixcols['page_detail']))
            {
                $new_fix_colm[$c]['json'] = 'true';
            }
            $c++;
        }

        $new_tables['tables'][$tables_prefix]["cols"] = $new_fix_colm;

        if(isset($_POST['table_content']))
        {
            $new_tables['tables'][$tables_prefix]['table_content'] = $_POST['table_content'];
        }


        // TODO: CHECK VALID RULES
        $new_tables['tables'][$tables_prefix]['error']['title'] = null;
        if(($new_tables['tables'][$tables_prefix]["db_url_dinamic"] == 'on') && ($new_tables['tables'][$tables_prefix]["db_type"] == 'offline'))
        {
            if($new_tables['tables'][$tables_prefix]['db_url'] == '')
            {
                $new_tables['tables'][$tables_prefix]['error']['title'] = 'Ops, URL List Item';
                $new_tables['tables'][$tables_prefix]['error']['content'] = 'When option <code>dinamic on 1st param</code> is <code>checked</code> and <code>Source JSON</code> is <code>offline</code>,<br/>you must fill the <code>URL List Item</code>';
            } else
            {
                $url_query = parse_url($new_tables['tables'][$tables_prefix]['db_url'],PHP_URL_QUERY);
                if($url_query == null)
                {
                    $new_tables['tables'][$tables_prefix]['error']['title'] = 'Ops, Query in URL List Item';
                    $new_tables['tables'][$tables_prefix]['error']['content'] = 'When option <code>dinamic on 1st param</code> is <code>checked</code> and <code>Source JSON</code> is <code>offline</code>,<br/>you must fill the <code>URL List Item</code> with <code>query</code>, example: <code>?column_var=-1</code>';
                }
            }
        }

        if(($new_tables['tables'][$tables_prefix]["db_url_dinamic"] == 'on') && ($new_tables['tables'][$tables_prefix]["db_type"] == 'online'))
        {
            if($new_tables['tables'][$tables_prefix]['db_url_single'] == '')
            {
                $new_tables['tables'][$tables_prefix]['error']['title'] = 'Ops, URL Single Item';
                $new_tables['tables'][$tables_prefix]['error']['content'] = 'When option <code>dinamic on 1st param</code> is <code>checked</code> and <code>Source JSON</code> is <code>online</code>,<br/>you must fill the <code>URL Single Item</code>';
            }

            if($new_tables['tables'][$tables_prefix]['db_url'] == '')
            {
                $new_tables['tables'][$tables_prefix]['error']['title'] = 'Ops, URL List Item';
                $new_tables['tables'][$tables_prefix]['error']['content'] = 'When option <code>dinamic on 1st param</code> is <code>checked</code> and <code>Source JSON</code> is <code>online</code>,<br/>you must fill the <code>URL List Item</code> with <code>query</code>, example: <code>http://domain.com/rest-api.php?var=1&var=2</code>';
            } else
            {
                $url_query = parse_url($new_tables['tables'][$tables_prefix]['db_url'],PHP_URL_QUERY);
                if($url_query == null)
                {
                    $new_tables['tables'][$tables_prefix]['error']['title'] = 'Ops, Query in URL List Item';
                    $new_tables['tables'][$tables_prefix]['error']['content'] = '
                    When option <code>dinamic on 1st param</code> is <code>checked</code> and <code>Source JSON</code> is <code>online</code>,
                    <br/>you must fill the <code>URL List Item</code> with <code>query</code>, 1st param will be dynamic value, example: 
                    <br/><code>http://domain.com/rest-api.php?var=[dynamic]&var=2</code>
                    <br/><code>http://domain.com/rest-api.php?chapter=1&json=book</code>
                    <br/><code>http://domain.com//wp-json/wp/v2/posts/?categories=2&per_page=10</code>
                    ';
                }
            }
        }

        $default_value = '';
        $db_param = parse_url($new_tables['tables'][$tables_prefix]['db_url']);
        $param = explode('=',$db_param['query']);
        if(isset($param[0]))
        {
            if(strlen($param[0]) > 0)
            {
                $new_tables['tables'][$tables_prefix]['query']['var'] = $param[0];
            }
        }
        if(isset($param[1]))
        {
            $_default_value = explode("&",$param[1]);
            $default_value = $_default_value[0];
        }
        $new_tables['tables'][$tables_prefix]['query']['val'] = $default_value;


        file_put_contents('projects/'.$file_name.'/tables.'.$tables_prefix.'.json',json_encode($new_tables));


        //create a page
        $overwrite_files = 'projects/'.$file_name.'/page.'.$new_tables['tables'][$tables_prefix]['parent'].'.json';
        if(file_exists($overwrite_files))
        {
            $cols = $new_tables['tables'][$tables_prefix]['cols'];
            $item_types = $new_tables['tables'][$tables_prefix]['itemtype'];
            $item_color = $new_tables['tables'][$tables_prefix]['itemcolor'];
            $item_prefix = str2var($new_tables['tables'][$tables_prefix]['prefix']);
            $template_singles = explode("|",$new_tables['tables'][$tables_prefix]['template_single']);
            $template_single = $template_singles[0];
            if(isset($template_singles[1]))
            {
                $template_single_option = $template_singles[1];
            } else
            {
                $template_single_option = '';
            }
            $uid = 'id';
            foreach($cols as $col)
            {
                if($col['type'] == 'id')
                {
                    $uid = str2var($col['title'],false);
                }
            }
            $page_detail_content = $page_list_content = $page_list_content_table = null;
            foreach($cols as $col)
            {
                $page_detail_var[] = array('value' => '<p>{{'.$item_prefix.'.'.str2var($col['title'],false).'}}</p>','label' => $col['title']);
                $page_list_var[] = array('value' => '<ul ng-repeat="item in buttons"><li>{{item.'.str2var($col['title'],false).'}}</li></ul>','label' => $col['title']);
                if(isset($col['page_list']))
                {
                    $images = $text = $heading = null;
                    // TODO: HTML FOR ITEM LISTING
                    switch($col['type'])
                    {
                        case 'id':
                            break;
                        case 'heading-1':
                            // TODO: --|---- HEADING-1
                            if(!isset($var_item_title))
                            {
                                $var_item_title = str2var($col['title'],false);
                            }
                            $page_list_content .= "\t\t\t\t\t".'<h1 ng-bind-html="item.'.str2var($col['title'],false).' | to_trusted"></h1>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><h1 ng-bind-html="item.'.str2var($col['title'],false).' | to_trusted"></h1></td>'."\r\n";
                            break;
                        case 'heading-2':
                            // TODO: --|---- HEADING-2
                            $page_list_content .= "\t\t\t\t\t".'<h2>{{item.'.str2var($col['title'],false).'}}</h2>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><h2>{{item.'.str2var($col['title'],false).'}}</h2></td>'."\r\n";
                            break;
                        case 'heading-3':
                            // TODO: --|---- HEADING-3
                            $page_list_content .= "\t\t\t\t\t".'<h3>{{item.'.str2var($col['title'],false).'}}</h3>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><h3>{{item.'.str2var($col['title'],false).'}}</h3></td>'."\r\n";
                            break;
                        case 'heading-4':
                            // TODO: --|---- HEADING-4
                            $page_list_content .= "\t\t\t\t\t".'<h4>{{item.'.str2var($col['title'],false).'}}</h4>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><h4>{{item.'.str2var($col['title'],false).'}}</h4></td>'."\r\n";
                            break;
                        case 'icon':
                            // TODO: --|---- ICON
                            $page_list_content .= "\t\t\t\t\t".'<i class="icon '.str2var($col['title'],false).'"></i>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><i class="icon '.str2var($col['title'],false).'"></i></td>'."\r\n";
                            if(!isset($var_item_icon_left))
                            {
                                $var_item_icon_left = str2var($col['title'],false);
                            }
                            if(!isset($var_item_icon_right))
                            {
                                $var_item_icon_right = str2var($col['title'],false);
                            }
                            break;

                        case 'text':
                            // TODO: --|---- TEXT
                            if(!isset($var_item_text))
                            {
                                $var_item_text = str2var($col['title'],false);
                                $var_item_text_label = $col['label'];
                            }
                            $list_cols_item_text[] = str2var($col['title'],false);
                            $list_cols_item_text_label[] = $col['label'];
                            if(preg_match("/\[txt\]/",$col['label']))
                            {
                                $page_list_content .= "\t\t\t\t\t\t".str_replace("[txt]",'{{item.'.str2var($col['title'],false).'}}',$col['label'])."\r\n";
                                $page_list_content_table .= "\t\t\t\t\t\t<td>".str_replace("[txt]",'{{item.'.str2var($col['title'],false).'}}',$col['label'])."</td>\r\n";
                            } else
                            {
                                $page_list_content .= "\t\t\t\t\t".''.$col['label'].' {{item.'.str2var($col['title'],false).'}}'."\r\n";
                                $page_list_content_table .= "\t\t\t\t\t<td>".''.$col['label'].' {{item.'.str2var($col['title'],false).'}}</td>'."\r\n";
                            }
                            break;
                            // TODO: --|---- PARAGRAPH
                        case 'paragraph':
                            $page_list_content .= "\t\t\t\t\t".'<p>{{item.'.str2var($col['title'],false).'}}</p>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><p>{{item.'.str2var($col['title'],false).'}}</p></td>'."\r\n";
                            if(!isset($var_item_paragraph))
                            {
                                $var_item_paragraph = str2var($col['title'],false);
                            }
                            break;
                            // TODO: --|---- IMAGES
                        case 'images':
                            if(!isset($var_item_images))
                            {
                                $var_item_images = str2var($col['title'],false);
                            }
                            $list_cols_item_images[] = str2var($col['title'],false);
                            if($_SESSION['PROJECT']['app']['lazyload'] == true)
                            {
                                $page_list_content .= "\t\t\t\t\t".'<img image-lazy-src="{{item.'.str2var($col['title'],false).'}}" />'."\r\n";
                                $page_list_content_table .= "\t\t\t\t\t".'<td><img image-lazy-src="{{item.'.str2var($col['title'],false).'}}" /></td>'."\r\n";
                            } else
                            {
                                $page_list_content .= "\t\t\t\t\t".'<img ng-src="{{item.'.str2var($col['title'],false).'}}" />'."\r\n";
                                $page_list_content_table .= "\t\t\t\t\t".'<td><img ng-src="{{item.'.str2var($col['title'],false).'}}" /></td>'."\r\n";
                            }
                            break;
                            // TODO: --|---- TO_TRUSTED
                        case 'to_trusted':
                            if(!isset($var_item_html))
                            {
                                $var_item_html = str2var($col['title'],false);
                            }
                            $page_list_content .= "\t\t\t\t\t".'<div class="to_trusted {{ fontsize }}" ng-bind-html="item.'.str2var($col['title'],false).' | strHTML"></div>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><div class="to_trusted {{ fontsize }}">{{ item.'.str2var($col['title'],false).' | strHTML }}</div></td>'."\r\n";
                            break;
                            // TODO: --|---- LINK
                        case 'link':
                            if(!isset($var_item_link))
                            {
                                $var_item_link = str2var($col['title'],false);
                            }
                            $page_list_content .= "\t\t\t\t\t".'<a ng-href="{{item.'.str2var($col['title'],false).'}}">'.$col['title'].'</a> '."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><a ng-href="{{item.'.str2var($col['title'],false).'}}">'.$col['title'].'</a><td> '."\r\n";
                            break;
                            // TODO: --|---- VIDEO
                        case 'video':
                            if(!isset($var_item_video))
                            {
                                $var_item_video = str2var($col['title'],false);
                            }
                            $page_list_content .= "\t\t\t\t\t".'<video controls="controls" ng-src="{{ item.'.str2var($col['title'],false).' | trustUrl }}" ></video> '."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><video controls="controls" ng-src="{{item.'.str2var($col['title'],false).' | trustUrl }}" ></video> </td>'."\r\n";
                            break;
                            // TODO: --|---- AUDIO
                        case 'audio':
                            if(!isset($var_item_audio))
                            {
                                $var_item_audio = str2var($col['title'],false);
                            }
                            $page_list_content .= "\t\t\t\t\t".'<audio controls="controls" ng-src="{{ item.'.str2var($col['title'],false).' | trustUrl }}"></audio> '."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><audio controls="controls" ng-src="{{ item.'.str2var($col['title'],false).' | trustUrl }}"></audio></td>'."\r\n";
                            break;
                            // TODO: --|---- RADTING
                        case 'rating':
                            if(!isset($var_item_rating))
                            {
                                $var_item_rating = str2var($col['title'],false);
                            }
                            $page_list_content .= "\t\t\t\t\t".'<rating ng-model="item.'.str2var($col['title'],false).'" max="rating.max"></rating>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><rating ng-model="item.'.str2var($col['title'],false).'" max="rating.max"></rating></td>'."\r\n";
                            break;
                            // TODO: --|---- SHARE LINK
                        case 'share_link':
                            $page_list_content .= "\t\t\t\t\t".'<p>'.$col['label'].'</p>'."\r\n";
                            $page_list_content .= "\t\t\t\t\t".'<div class="button-bar">'."\r\n";
                            $page_list_content .= "\t\t\t\t\t\t".'<button class="button button-small button-assertive icon ion-social-googleplus" ng-click="openURL(\'https://plus.google.com/share?url=\' + item.'.str2var($col['title'],false).')"></button>'."\r\n";
                            $page_list_content .= "\t\t\t\t\t\t".'<button class="button button-small button-positive icon ion-social-facebook" ng-click="openURL(\'https://facebook.com/sharer/sharer.php?u=\' + item.'.str2var($col['title'],false).')"></button>'."\r\n";
                            $page_list_content .= "\t\t\t\t\t\t".'<button class="button button-small button-calm icon ion-social-twitter" ng-click="openURL(\'https://twitter.com/share?url=\' + item.'.str2var($col['title'],false).')"></button>'."\r\n";
                            $page_list_content .= "\t\t\t\t\t\t".'<button class="button button-small button-assertive-900 icon ion-social-pinterest" ng-click="openURL(\'https://pinterest.com/pin/create/bookmarklet/?&url=\' + item.'.str2var($col['title'],false).')"></button>'."\r\n";
                            $page_list_content .= "\t\t\t\t\t\t".'<button class="button button-small button-positive-900 icon ion-social-linkedin" ng-click="openURL(\'https://www.linkedin.com/shareArticle?url=\' + item.'.str2var($col['title'],false).')"></button>'."\r\n";
                            $page_list_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<p>'.$col['label'].'</p>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<div class="button-bar">'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t\t".'<button class="button button-small button-assertive icon ion-social-googleplus" ng-click="openURL(\'https://plus.google.com/share?url=\' + item.'.str2var($col['title'],false).')"></button>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t\t".'<button class="button button-small button-positive icon ion-social-facebook" ng-click="openURL(\'https://facebook.com/sharer/sharer.php?u=\' + item.'.str2var($col['title'],false).')"></button>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t\t".'<button class="button button-small button-calm icon ion-social-twitter" ng-click="openURL(\'https://twitter.com/share?url=\' + item.'.str2var($col['title'],false).')"></button>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t\t".'<button class="button button-small button-assertive-900 icon ion-social-pinterest" ng-click="openURL(\'https://pinterest.com/pin/create/bookmarklet/?&url=\' + item.'.str2var($col['title'],false).')"></button>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t\t".'<button class="button button-small button-positive-900 icon ion-social-linkedin" ng-click="openURL(\'https://www.linkedin.com/shareArticle?url=\' + item.'.str2var($col['title'],false).')"></button>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'</div>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'</td>'."\r\n";
                            break;
                            // TODO: --|---- WEBVIEW
                        case 'webview':
                            $page_list_content .= "\t\t\t\t\t".'<a ng-click="openWebView(item.'.str2var($col['title'],false).')">'.$col['label'].'</a> '."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><a ng-click="openWebView(item.'.str2var($col['title'],false).')">'.$col['label'].'</a></td>'."\r\n";
                            break;
                            // TODO: --|---- APPBROWSER
                        case 'appbrowser':
                            $page_list_content .= "\t\t\t\t\t".'<a ng-click="openAppBrowser(item.'.str2var($col['title'],false).')">'.$col['label'].'</a> '."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><a ng-click="openAppBrowser(item.'.str2var($col['title'],false).')">'.$col['label'].'</a></td>'."\r\n";
                            break;
                            // TODO: --|---- YOUTUBE
                        case 'ytube':
                            if(!isset($var_item_youtube))
                            {
                                $var_item_youtube = str2var($col['title'],false);
                            }
                            $page_list_content .= "\t\t\t\t\t".'<div class="full-image embed-responsive embed-responsive-16by9"><iframe  width="100%" ng-src="{{ \'https://www.youtube.com/embed/\' + item.'.str2var($col['title'],false).' + \'?enablejsapi=1\' | trustUrl }}" frameborder="0" allowfullscreen></iframe></div>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td><div class="full-image embed-responsive embed-responsive-16by9"><iframe  width="100%" ng-src="{{ \'https://www.youtube.com/embed/\' + item.'.str2var($col['title'],false).' + \'?enablejsapi=1\' | trustUrl }}" frameborder="0" allowfullscreen></iframe></div></td>'."\r\n";
                            break;
                            // TODO: --|---- SLIDEBOX
                        case 'slidebox':
                            $page_list_content .= "\t\t\t\t\t".'<div class="slideshow_container to_trusted" ng-if="item.'.str2var($col['title'],false).'" >'."\r\n";
                            $page_list_content .= "\t\t\t\t\t\t\t".'<ion-slides options="{slidesPerView:1}" slider="data.slider" class="slideshow">'."\r\n";
                            $page_list_content .= "\t\t\t\t\t\t\t\t".'<ion-slide-page class="slideshow-item" ng-repeat="slide_item in  item.'.str2var($col['title'],false).' | strExplode:\'|\' track by $index" >'."\r\n";
                            $page_list_content .= "\t\t\t\t\t\t\t\t\t".'<div class="item-text-wrap" ng-bind-html="slide_item | to_trusted"></div>'."\r\n";
                            $page_list_content .= "\t\t\t\t\t\t\t\t".'</ion-slide-page>'."\r\n";
                            $page_list_content .= "\t\t\t\t\t\t\t".'</ion-slides>'."\r\n";
                            $page_list_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<td>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'<div class="slideshow_container to_trusted" ng-if="item.'.str2var($col['title'],false).'" >'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t\t\t".'<ion-slides options="{slidesPerView:1}" slider="data.slider" class="slideshow">'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t\t\t\t".'<ion-slide-page class="slideshow-item" ng-repeat="slide_item in  item.'.str2var($col['title'],false).' | strExplode:\'|\' track by $index" >'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t\t\t\t\t".'<div class="item-text-wrap" ng-bind-html="slide_item | to_trusted"></div>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t\t\t\t".'</ion-slide-page>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t\t\t".'</ion-slides>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'</div>'."\r\n";
                            $page_list_content_table .= "\t\t\t\t\t".'</td>'."\r\n";
                            break;
                            // TODO: --|---- GMAP
                        case 'gmap':
                            if(!isset($var_map))
                            {
                                $var_map = str2var($col['title'],false);
                            }
                            $page_list_content .= "\t\t\t\t\t".'<div ng-if="mapEnable" class="embed_container" data-tap-disabled="true"><ng-map zoom="12" width="100%" center="{{item.'.str2var($col['title'],false).'}}"></ng-map></div>'."\r\n";

                            $page_list_content_table .= "\t\t\t\t\t".'<td><div ng-if="mapEnable" class="embed_container" data-tap-disabled="true"><ng-map zoom="12" width="100%" center="{{item.'.str2var($col['title'],false).'}}"></ng-map></div></td>'."\r\n";
                            break;
                    }
                }
                // TODO: HTML FOR DETAIL ITEM
                if(isset($col['page_detail']))
                {
                    if(!isset($new_tables['tables'][$tables_prefix]['db_var_single']))
                    {
                        $new_tables['tables'][$tables_prefix]['db_var_single'] = '';
                    }
                    $_1stVar = '';
                    if($new_tables['tables'][$tables_prefix]['db_var_single'] != "")
                    {
                        $_1stVar = $new_tables['tables'][$tables_prefix]['db_var_single'];
                    }

                    $_item_prefix = $item_prefix.$_1stVar;

                    //$page_detail_var[] = array('value' => '{{' . $_item_prefix . '.' . str2var($col['title'],false) . '}}', 'label' => $col['title']);
                    $images = $text = $heading = null;
                    $page_detail_content .= "\r\n\t\t\t\t\t"."<!-- ".$col['type']." -->"."\r\n";
                    switch($col['type'])
                    {
                        case 'id':
                            break;
                        case 'heading-1':
                            // TODO: --|---- HEADING-1
                            if(!isset($heading_as_title))
                            {
                                $heading_as_title = '{{ '.$_item_prefix.'.'.str2var($col['title'],false).' }}';
                                $heading_as_title_raw = ''.$_item_prefix.'.'.str2var($col['title'],false).'';
                            }
                            if($template_single != 'none')
                            {
                                if(!isset($heading_as_title))
                                {
                                    $heading_as_title = '{{ '.$_item_prefix.'.'.str2var($col['title'],false).' }}';
                                    $heading_as_title_raw = ''.$_item_prefix.'.'.str2var($col['title'],false).'';
                                } else
                                {
                                    $page_detail_content .= "\t\t\t\t\t".'<div class="item item-divider" '.$direction.' ng-bind-html="'.$_item_prefix.'.'.str2var($col['title'],false).' | to_trusted">{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</div>'."\r\n";
                                }
                            } else
                            {
                                $page_detail_content .= "\t\t\t\t\t".'<div class="item item-divider" '.$direction.' ng-bind-html="'.$_item_prefix.'.'.str2var($col['title'],false).' | to_trusted">{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</div>'."\r\n";
                            }
                            break;
                        case 'heading-2':
                            // TODO: --|---- HEADING-2
                            if($template_single != 'none')
                            {
                                if(!isset($heading2_as_title))
                                {
                                    $heading2_as_title = '{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}';
                                } else
                                {
                                    $page_detail_content .= "\t\t\t\t\t".'<h2 class="item noborder" '.$direction.'>{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</h2>'."\r\n";
                                }
                            } else
                            {
                                $page_detail_content .= "\t\t\t\t\t".'<div class="item item-divider" '.$direction.'>{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</div>'."\r\n";
                            }
                            break;
                        case 'heading-3':
                            // TODO: --|---- HEADING-3
                            $page_detail_content .= "\t\t\t\t\t".'<h3 class="item noborder">{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</h3>'."\r\n";
                            break;
                        case 'heading-4':
                            // TODO: --|---- HEADING-4
                            $page_detail_content .= "\t\t\t\t\t".'<h4 class="item noborder">{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</h4>'."\r\n";
                            break;
                        case 'icon':
                            // TODO: --|---- ICON
                            $page_detail_content .= "\t\t\t\t\t".'<i class="icon {{'.$_item_prefix.'.'.str2var($col['title'],false).'}}"></i>'."\r\n";
                            break;
                        case 'text':
                            // TODO: --|---- TEXT
                            if(preg_match("/\[txt\]/",$col['label']))
                            {
                                $page_detail_content .= "\t\t\t\t\t".'<div class="item item-text-wrap"><span class="subdued" '.$direction.'>'.str_replace("[txt]",'{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}',$col['label']).'</span></div>'."\r\n";
                            } else
                            {
                                $page_detail_content .= "\t\t\t\t\t".'<div class="item item-text-wrap"><span class="subdued" '.$direction.'>'.$col['label'].'{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</span></div>'."\r\n";
                            }
                            break;
                        case 'paragraph':
                            // TODO: --|---- PARAGRAPH
                            $page_detail_content .= "\t\t\t\t\t".'<p class="item item-text-wrap noborder" '.$direction.'>{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</p>'."\r\n";
                            break;
                        case 'images':
                            // TODO: --|---- IMAGES
                            if($template_single != 'none')
                            {
                                if(!isset($hero_images))
                                {
                                    $hero_images = '{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}';
                                } else
                                {
                                    if($_SESSION['PROJECT']['app']['lazyload'] == true)
                                    {
                                        $page_detail_content .= "\t\t\t\t\t".'<div class="item noborder"><img class="full-image" image-lazy-loader="lines" image-lazy-src="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}"  zoom-view="true" zoom-src="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}" /></div>'."\r\n";
                                    } else
                                    {
                                        $page_detail_content .= "\t\t\t\t\t".'<div class="item noborder"><img class="full-image" ng-src="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}"   zoom-view="true" zoom-src="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}" /></div>'."\r\n";
                                    }
                                }
                            } else
                            {
                                if($_SESSION['PROJECT']['app']['lazyload'] == true)
                                {
                                    $page_detail_content .= "\t\t\t\t\t".'<div class="item noborder"><img class="full-image" image-lazy-loader="lines" image-lazy-src="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}"   zoom-view="true" zoom-src="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}"/></div>'."\r\n";
                                } else
                                {
                                    $page_detail_content .= "\t\t\t\t\t".'<div class="item noborder"><img class="full-image" ng-src="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}"  zoom-view="true" zoom-src="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}"/></div>'."\r\n";
                                }
                            }
                            break;

                        case 'to_trusted':
                            // TODO: --|---- TO-TRUSTED
                            $page_detail_content .= "\t\t\t\t\t".'<div class="item item-text-wrap noborder to_trusted {{ fontsize }}" ng-bind-html="'.$_item_prefix.'.'.str2var($col['title'],false).' | strHTML"></div>'."\r\n";
                            break;
                        case 'link':
                            // TODO: --|---- LINK
                            if($template_single != 'none')
                            {
                                if(!isset($ext_link))
                                {
                                    $ext_link = '<a class="button button-'.$item_color.' ink-dark" data-ink-opacity=".8" ng-click="openURL('.$_item_prefix.'.'.str2var($col['title'],false).')">'.$col['label'].'</a>';
                                } else
                                {
                                    $page_detail_content .= "\t\t\t\t\t".'<div class="item item-button"><a class="button button-'.$item_color.' ink-dark" ng-click="openURL('.$_item_prefix.'.'.str2var($col['title'],false).')">'.$col['label'].'</a></div>'."\r\n";
                                }
                            } else
                            {
                                $page_detail_content .= "\t\t\t\t\t".'<div class="item item-button"><a class="button button-'.$item_color.' ink-dark" ng-click="openURL('.$_item_prefix.'.'.str2var($col['title'],false).')">'.$col['label'].'</a></div>'."\r\n";
                            }
                            break;
                        case 'share_link':
                            // TODO: --|---- SHARE LINK
                            //$page_detail_content .= "\t\t\t\t\t" . '<!--' . "\r\n";
                            //$page_detail_content .= "\t\t\t\t\t" . '<div class="item">' . "\r\n";
                            //$page_detail_content .= "\t\t\t\t\t" . '<p>' . $col['label'] . '</p>' . "\r\n";
                            //$page_detail_content .= "\t\t\t\t\t" . '<div class="button-bar">' . "\r\n";
                            //$page_detail_content .= "\t\t\t\t\t\t" . '<button class="button button-small button-assertive icon ion-social-googleplus" ng-click="openURL(\'https://plus.google.com/share?url=\' + ' . $_item_prefix . '.' . str2var($col['title'], false) . ')"></button>' . "\r\n";
                            //$page_detail_content .= "\t\t\t\t\t\t" . '<button class="button button-small button-positive icon ion-social-facebook" ng-click="openURL(\'https://facebook.com/sharer/sharer.php?u=\' + ' . $_item_prefix . '.' . str2var($col['title'], false) . ')"></button>' . "\r\n";
                            //$page_detail_content .= "\t\t\t\t\t\t" . '<button class="button button-small button-calm icon ion-social-twitter" ng-click="openURL(\'https://twitter.com/share?url=\' + ' . $_item_prefix . '.' . str2var($col['title'], false) . ')"></button>' . "\r\n";
                            //$page_detail_content .= "\t\t\t\t\t\t" . '<button class="button button-small button-assertive-900 icon ion-social-pinterest" ng-click="openURL(\'https://pinterest.com/pin/create/bookmarklet/?&url=\' + ' . $_item_prefix . '.' . str2var($col['title'], false) . ')"></button>' . "\r\n";
                            //$page_detail_content .= "\t\t\t\t\t\t" . '<button class="button button-small button-positive-900 icon ion-social-linkedin" ng-click="openURL(\'https://www.linkedin.com/shareArticle?url=\' + ' . $_item_prefix . '.' . str2var($col['title'], false) . ')"></button>' . "\r\n";
                            //$page_detail_content .= "\t\t\t\t\t" . '</div>' . "\r\n";
                            //$page_detail_content .= "\t\t\t\t\t" . '</div>' . "\r\n";
                            //$page_detail_content .= "\t\t\t\t\t" . '-->' . "\r\n";

                            $page_detail_content .= "\t\t\t\t\t".'<div class="item">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<button run-social-sharing message="{{ '.$_item_prefix.'.'.str2var($col['title'],false).' }}" class="button button-small ion-android-share-alt button-outline button-positive icon-left">'.$col['label'].'</button>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";

                            break;

                        case 'video':
                            // TODO: --|---- VIDEO
                            $page_detail_content .= "\t\t\t\t\t".'<div class="item noborder" ng-if="'.$_item_prefix.'.'.str2var($col['title'],false).'">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'<div class="embed_container"><video controls="controls" ng-src="{{'.$_item_prefix.'.'.str2var($col['title'],false).' | trustUrl }}"></video></div>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;
                        case 'audio':
                            // TODO: --|---- AUDIO
                            $page_detail_content .= "\t\t\t\t\t".'<div class="item noborder" ng-if="'.$_item_prefix.'.'.str2var($col['title'],false).'">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'<audio controls="controls" class="full-image" ng-src="{{'.$_item_prefix.'.'.str2var($col['title'],false).' | trustUrl }}" ></audio>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;
                        case 'rating':
                            // TODO: --|---- RATING
                            $page_detail_content .= "\t\t\t\t\t".'<div class="item noborder text-right"><rating ng-model="'.$_item_prefix.'.'.str2var($col['title'],false).'" max="rating.max"></rating></div>'."\r\n";
                            break;
                        case 'ytube':
                            // TODO: --|---- YOUTUBE
                            $page_detail_content .= "\t\t\t\t\t".'<div class="item noborder" ng-if="'.$_item_prefix.'.'.str2var($col['title'],false).'">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<div class="embed_container">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t\t".'<iframe  width="100%" ng-src="{{ \'https://www.youtube.com/embed/\' + '.$_item_prefix.'.'.str2var($col['title'],false).' + \'?enablejsapi=1\' | trustUrl }}" frameborder="0" allowfullscreen>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t\t".'</iframe>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'</div>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;
                        case 'webview':
                            // TODO: --|---- WEBVIEW
                            $page_detail_content .= "\t\t\t\t\t".'<div class="item item-button"><button class="button button-'.$item_color.' ink-dark" ng-click="openWebView('.$_item_prefix.'.'.str2var($col['title'],false).')">'.$col['label'].'</button></div>'."\r\n";
                            break;
                        case 'appbrowser':
                            // TODO: --|---- APPBROWSER
                            $page_detail_content .= "\t\t\t\t\t".'<div class="item item-button"><button class="button button-'.$item_color.' ink-dark" ng-click="openAppBrowser('.$_item_prefix.'.'.str2var($col['title'],false).')">'.$col['label'].'</button></div>'."\r\n";
                            break;
                        case 'gmap':
                            // TODO: --|---- GMAP
                            $page_detail_content .= "\t\t\t\t\t".'<div class="item noborder">'."\r\n\t\t\t\t\t\t".'<div ng-if="mapEnable" class="embed_container" data-tap-disabled="true">'."\r\n\t\t\t\t\t\t\t".'<ng-map zoom="16" width="100%" center="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}">'."\r\n\t\t\t\t\t\t\t\t".'<marker position="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}" ></marker>'."\r\n\t\t\t\t\t\t\t".'</ng-map>'."\r\n\t\t\t\t\t\t".'</div>'."\r\n\t\t\t\t\t".'</div>'."\r\n";
                            break;
                        case 'slidebox':
                            // TODO: --|---- SLIDEBOX
                            $page_detail_content .= "\t\t\t\t\t".'<div class="item item-text-wrap noborder to_trusted">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'<div class="slideshow_container to_trusted" ng-if="'.$_item_prefix.'.'.str2var($col['title'],false).'" >'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t\t".'<ion-slides options="{slidesPerView:1}" slider="data.slider" class="slideshow">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t\t\t".'<ion-slide-page class="slideshow-item" ng-repeat="slide_item in '.$_item_prefix.'.'.str2var($col['title'],false).' | strExplode:\'|\' track by $index" >'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t\t\t\t".'<div class="item-text-wrap" ng-bind-html="slide_item | to_trusted"></div>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t\t\t".'</ion-slide-page>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t\t".'</ion-slides>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;
                            // TODO: --|---- NUMBER
                        case 'number':
                            $page_detail_content .= "\t\t\t\t\t".'<div class="padding item-text-wrap noborder">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-left"><strong>'.$col['label'].'</strong></span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-right">{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;
                            // TODO: --|---- FLOAT
                        case 'float':
                            $page_detail_content .= "\t\t\t\t\t".'<div class="padding item-text-wrap noborder">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-left"><strong>'.$col['label'].'</strong></span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-right">{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;
                            // TODO: --|---- DATE
                        case 'date':
                            $page_detail_content .= "\t\t\t\t\t".'<div class="padding item-text-wrap noborder">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-left"><strong>'.$col['label'].'</strong></span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-right">{{'.$_item_prefix.'.'.str2var($col['title'],false).' | date:\'fullDate\' }}</span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;
                            // TODO: --|---- DATETIME
                        case 'datetime':
                            $page_detail_content .= "\t\t\t\t\t".'<div class="padding item-text-wrap noborder">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-left"><strong>'.$col['label'].'</strong></span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-right">{{'.$_item_prefix.'.'.str2var($col['title'],false).' | date:\'medium\' }}</span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;

                        case 'date_php':
                            $page_detail_content .= "\t\t\t\t\t".'<div class="padding item-text-wrap noborder">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-left"><strong>'.$col['label'].'</strong></span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-right">{{'.$_item_prefix.'.'.str2var($col['title'],false).' | phpTime | date:\'fullDate\' }}</span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;
                            // TODO: --|---- DATE
                        case 'datetime_php':
                            $page_detail_content .= "\t\t\t\t\t".'<div class="padding item-text-wrap noborder">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-left"><strong>'.$col['label'].'</strong></span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-right">{{'.$_item_prefix.'.'.str2var($col['title'],false).' | phpTime | date:\'medium\' }}</span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;

                            // TODO: --|---- DATETIME_STRING
                        case 'datetime_string':
                            $page_detail_content .= "\t\t\t\t\t".'<div class="padding item-text-wrap noborder">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-left"><strong>'.$col['label'].'</strong></span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<span class="pull-right">{{'.$_item_prefix.'.'.str2var($col['title'],false).' | strDate | date:\'medium\' }}</span>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;

                            // TODO: --|---- EMAIL
                        case 'app_email':
                            $page_detail_content .= "\t\t\t\t\t".'<div class="padding item-text-wrap noborder">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<button class="button button-positive button-small pull-right icon-left icon-left ion-email" run-app-email email="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}" subject="subject" message="your message" >{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</button>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;

                            // TODO: --|---- SMS
                        case 'app_sms':
                            $page_detail_content .= "\t\t\t\t\t".'<div class="padding item-text-wrap noborder">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<button class="button button-balanced button-small pull-right icon-left icon-left ion-android-textsms" run-app-sms phone="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}" message="your message" >{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</button>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;

                            // TODO: --|---- CALL
                        case 'app_call':
                            $page_detail_content .= "\t\t\t\t\t".'<div class="padding item-text-wrap noborder">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<button class="button button-balanced-900 button-small pull-right icon-left icon-left ion-android-call" run-app-call phone="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}" >{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}</button>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;

                            // TODO: --|---- GEO
                        case 'app_geo':
                            $page_detail_content .= "\t\t\t\t\t".'<div class="padding item-text-wrap noborder">'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<button class="button button-royal-900 button-small pull-right icon-left icon-left ion-android-locate" run-app-geo loc="{{'.$_item_prefix.'.'.str2var($col['title'],false).'}}" >'.$col['label'].'</button>'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<!--button class="button button-royal-900 button-small pull-right icon-left icon-left ion-android-locate" href="google.navigation:q={{'.$_item_prefix.'.'.str2var($col['title'],false).'}}" >'.$col['label'].'</button-->'."\r\n";
                            $page_detail_content .= "\t\t\t\t\t\t".'<!--button class="button button-royal-900 button-small pull-right icon-left icon-left ion-android-locate" href="google.streetview:cbll={{'.$_item_prefix.'.'.str2var($col['title'],false).'}}" >'.$col['label'].'</button-->'."\r\n";

                            $page_detail_content .= "\t\t\t\t\t".'</div>'."\r\n";
                            break;


                    }
                    $page_detail_content .= "\t\t\t\t\t"."<!-- ./".$col['type']." -->"."\r\n";
                }
            }
            $current_page = json_decode(file_get_contents($overwrite_files),true);
            if(!isset($heading2_as_title))
            {
                $heading2_as_title = '&nbsp;';
            }
            if(!isset($hero_images))
            {
                $hero_images = $_SESSION['PROJECT']['menu']['logo'];
            }
            if(!isset($ext_link))
            {
                $ext_link = '&nbsp;';
            }
            $single_page['page'][0]['js'] = '$ionicConfig.backButton.text("");';
            $single_page['page'][0]['img_bg'] = '';
            $single_page['page'][0]['img_hero'] = '';
            $create_single_page_files = 'projects/'.$file_name.'/page.'.$tables_prefix.'_singles.json';
            $is_lock = false;
            $lock_path = $create_single_page_files;
            if(file_exists($lock_path))
            {
                $lock_data = json_decode(file_get_contents($lock_path),true);
                $is_lock = $lock_data['page'][0]['lock'];
                $single_page['page'][0]['img_bg'] = $lock_data['page'][0]['img_bg'];
                $single_page['page'][0]['img_hero'] = $lock_data['page'][0]['img_hero'];
            }
            // TODO: LINK TABS
            $query_tab = null;
            if(!isset($new_tables['tables'][$tables_prefix]['db_url_dinamic']))
            {
                $new_tables['tables'][$tables_prefix]['db_url_dinamic'] = false;
            }
            if($new_tables['tables'][$tables_prefix]['db_url_dinamic'] == true)
            {
                $db_param = parse_url($new_tables['tables'][$tables_prefix]['db_url']);
                $param = explode('=',$db_param['query']);
                if(isset($param[0]))
                {
                    if(isset($param[1]))
                    {
                        $query_tab = '/'.$param[1];
                        $query_tab_for_back = '/-1';
                    }
                }
            }
            $link_list_for_back = '#/'.$subpage_path.'/'.$tables_prefix.'s'.$query_tab_for_back;

            $link_list = '#/'.$subpage_path.'/'.$tables_prefix.'s'.$query_tab;
            $link_single = '#/'.$subpage_path.'/'.$tables_prefix.'_singles';
            $hero_color = $_SESSION["PROJECT"]['menu']['header_background'];
            $page_content = "\r\n";
            if($single_page['page'][0]['img_hero'] == '')
            {
                $single_page['page'][0]['img_hero'] = 'data/images/header/header0.jpg';
            }
            $page_content .= "\t\t\t".'<ion-refresher pulling-text="'.htmlentities($new_tables['tables'][$tables_prefix]['languages']['pull_for_refresh']).'"  on-refresh="doRefresh()"></ion-refresher>'."\r\n";
            // TODO: --|-- TEMPLATE DATA SINGLE
            if(!isset($new_tables['tables'][$tables_prefix]['bookmarks']))
            {
                $new_tables['tables'][$tables_prefix]['bookmarks'] = 'none';
            }
            if($template_single == 'tabs')
            {
                $page_content .= "\t\t\t".'<div class="tabs tabs-'.$item_color.' tabs-icon-top static">'."\r\n";
                $page_content .= "\t\t\t\t".'<!-- a ng-click="$ionicGoBack()" class="tab-item" -->'."\r\n";
                $page_content .= "\t\t\t\t".'<a href="'.$link_list_for_back.'" class="tab-item">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<i class="icon ion-ios-albums-outline"></i> {{ \'List\' | translate }}'."\r\n";
                $page_content .= "\t\t\t\t".'</a>'."\r\n";
                $page_content .= "\t\t\t\t".'<a class="tab-item tab-item-active active">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<i class="icon ion-ios-list-outline"></i> {{ \'Detail\' | translate }}'."\r\n";
                $page_content .= "\t\t\t\t".'</a>'."\r\n";
                if($new_tables['tables'][$tables_prefix]['bookmarks'] == 'bookmark')
                {
                    $page_content .= "\t\t\t\t".'<a class="tab-item" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_bookmark">'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<i class="icon ion-ios-bookmarks-outline"></i> {{ \'Bookmark\' | translate }}'."\r\n";
                    $page_content .= "\t\t\t\t".'</a>'."\r\n";
                }
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
            }
            if($template_single == 'heroes-1')
            {
                $page_content .= "\t\t\t".'<div class="hero slide-up '.$hero_color.'-bg" style="background-image: url(\''.$single_page['page'][0]['img_hero'].'\');">'."\r\n";
                $page_content .= "\t\t\t\t".'<div class="padding-horizontal padding-bottom content content">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<img alt="" class="avatar" ng-src="'.$hero_images.'" />'."\r\n";
                $page_content .= "\t\t\t\t\t".'<h3><a class="light" ng-bind-html="'.$heading_as_title_raw.' | strHTML">'.$heading_as_title.'</a></h3>'."\r\n";
                $page_content .= "\t\t\t\t\t".'<h4 class="light">'.$heading2_as_title.'</h4>'."\r\n";
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'</div>'."\r\n";
            }
            if($template_single == 'heroes-2')
            {
                $page_content .= "\t\t\t".'<div class="hero slide-up '.$hero_color.'-bg" style="background-image: url(\''.$single_page['page'][0]['img_hero'].'\');">'."\r\n";
                $page_content .= "\t\t\t\t".'<div class="padding-horizontal padding-bottom content">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<img alt="" class="avatar" ng-src="'.$hero_images.'" />'."\r\n";
                $page_content .= "\t\t\t\t\t".'<h3><a class="light" ng-bind-html="'.$heading_as_title_raw.' | strHTML">'.$heading_as_title.'</a></h3>'."\r\n";
                $page_content .= "\t\t\t\t\t".$ext_link."\r\n";
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'</div>'."\r\n";
            }
            if($template_single == 'heroes-3')
            {
                $page_content .= "\t\t\t".'<div class="hero slide-up '.$hero_color.'-bg" style="background-image: url(\''.$single_page['page'][0]['img_hero'].'\');">'."\r\n";
                $page_content .= "\t\t\t\t".'<div class="padding-horizontal padding-bottom content">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<img alt="" class="avatar" ng-src="'.$hero_images.'" />'."\r\n";
                $page_content .= "\t\t\t\t\t".'<h3><a class="light"  ng-bind-html="'.$heading_as_title_raw.' | strHTML" >'.$heading_as_title.'</a></h3>'."\r\n";
                $page_content .= "\t\t\t\t\t".'<h4 class="light">'.$heading2_as_title.'</h4>'."\r\n";
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'<div class="tabs tabs-'.str_replace('-900','',$item_color).' tabs-icon-top static">'."\r\n";
                $page_content .= "\t\t\t\t".'<!-- a ng-click="$ionicGoBack()" class="tab-item" -->'."\r\n";
                $page_content .= "\t\t\t\t".'<a href="'.$link_list_for_back.'" class="tab-item">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<i class="icon ion-ios-albums-outline"></i> {{ \'List\' | translate }}'."\r\n";
                $page_content .= "\t\t\t\t".'</a>'."\r\n";
                $page_content .= "\t\t\t\t".'<a class="tab-item tab-item-active active">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<i class="icon ion-ios-list-outline"></i> {{ \'Detail\' | translate }}'."\r\n";
                $page_content .= "\t\t\t\t".'</a>'."\r\n";
                if($new_tables['tables'][$tables_prefix]['bookmarks'] == 'bookmark')
                {
                    $page_content .= "\t\t\t\t".'<a class="tab-item" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_bookmark">'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<i class="icon ion-ios-bookmarks-outline"></i> {{ \'Bookmark\' | translate }}'."\r\n";
                    $page_content .= "\t\t\t\t".'</a>'."\r\n";
                }
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
            }
            if($template_single == 'heroes-4')
            {
                $page_content .= "\t\t\t".'<div class="hero slide-up '.$item_color.'-bg" style="background-image: url(\''.$single_page['page'][0]['img_hero'].'\');">'."\r\n";
                $page_content .= "\t\t\t\t".'<div class="padding-horizontal padding-bottom content">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<img alt="" class="avatar" ng-src="'.$hero_images.'" />'."\r\n";
                $page_content .= "\t\t\t\t\t".'<h3><a class="light" ng-bind-html="'.$heading_as_title_raw.' | strHTML">'.$heading_as_title.'</a></h3>'."\r\n";
                $page_content .= "\t\t\t\t\t".$ext_link."\r\n";
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'<div class="tabs tabs-'.str_replace('-900','',$item_color).' tabs-icon-top static">'."\r\n";
                $page_content .= "\t\t\t\t".'<!-- a ng-click="$ionicGoBack()" class="tab-item" -->'."\r\n";
                $page_content .= "\t\t\t\t".'<a href="'.$link_list_for_back.'" class="tab-item">'."\r\n";

                $page_content .= "\t\t\t\t\t".'<i class="icon ion-ios-albums-outline"></i> {{ \'List \' | translate }}'."\r\n";
                $page_content .= "\t\t\t\t".'</a>'."\r\n";
                $page_content .= "\t\t\t\t".'<a class="tab-item tab-item-active active">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<i class="icon ion-ios-list-outline"></i> {{ \'Detail\' | translate }}'."\r\n";
                $page_content .= "\t\t\t\t".'</a>'."\r\n";
                if($new_tables['tables'][$tables_prefix]['bookmarks'] == 'bookmark')
                {
                    $page_content .= "\t\t\t\t".'<a class="tab-item" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_bookmark">'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<i class="icon ion-ios-bookmarks-outline"></i> {{ \'Bookmark\' | translate }}'."\r\n";
                    $page_content .= "\t\t\t\t".'</a>'."\r\n";
                }
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
            }


            if($template_single == 'heroes-5')
            {
                $page_content .= "\t\t\t".'<div class="hero slide-up hero-md" ><div class="hero-md-content bar-'.$hero_color.'" >'."\r\n";
                $page_content .= "\t\t\t\t".'<div class="padding-horizontal padding-bottom content text-center">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<h3><a class="light" ng-bind-html="'.$heading_as_title_raw.' | strHTML">'.$heading_as_title.'</a></h3>'."\r\n";
                $page_content .= "\t\t\t\t\t".'<h4 class="light">'.$heading2_as_title.'</h4>'."\r\n";
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'</div></div>'."\r\n";
                $single_page['page'][0]['css'] = '#'.$tables_prefix.'_singles .bar{box-shadow: 0 0 0 0;}';
                $single_page['page'][0]['css'] .= "\r\n".'#'.$tables_prefix.'_singles .hero{height: 80px;}';
            }

            if($template_single == 'featured')
            {

                $page_content .= "\t\t\t".'<div class="hero has-mask slide-up" style="background-image: url(\''.$hero_images.'\');">'."\r\n";
                $page_content .= "\t\t\t\t".'<div class="content">'."\r\n";
                if($new_tables['tables'][$tables_prefix]['bookmarks'] == 'bookmark')
                {
                    //$page_content .= "\t\t\t\t" . ' <button ng-click="addToDbVirtual(' . $tables_prefix . ');" class="button button-large button-clear flat waves-effect waves-button waves-light icon ion-heart pull-right text-white"></button>' . "\r\n";
                    //$page_content .= "\t\t\t\t" . ' <button ng-click="addToDbVirtual(' . $tables_prefix . ');" class="button button-large button-clear flat waves-effect waves-button waves-light ion-android-add-circle pull-right text-white"></button>' . "\r\n";
                    //$page_content .= "\t\t\t\t" . ' <button ng-href="#/' . $subpage_path . '/' . $tables_prefix . '_bookmark" class="button button-large button-clear flat waves-effect waves-button waves-light icon ion-ios-star pull-right text-white"></button>' . "\r\n";
                }
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'<div class="mid-bar dark-bg z1 padding">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<h3 ng-bind-html="'.$heading_as_title_raw.' | strHTML">'.$heading_as_title.'</h3>'."\r\n";
                $page_content .= "\t\t\t\t\t".'<p>'.$heading2_as_title.'</p>'."\r\n";
                $page_content .= "\t\t\t".'</div>'."\r\n";
                $single_page['page'][0]['css'] = '.text-white{color:rgba(255,255,255,.8) !important}';

            }


            $page_content .= "\t\t\t".'<div class="list '.$template_single_option.'">'."\r\n";
            $page_content .= $page_detail_content;
            $page_content .= "\t\t\t".'</div>'."\r\n";
            if(!isset($new_tables['tables'][$tables_prefix]['bookmarks']))
            {
                $new_tables['tables'][$tables_prefix]['bookmarks'] = 'none';
            }
            // TODO: --|-- ADD CART BUTTON
            if($new_tables['tables'][$tables_prefix]['bookmarks'] == 'cart')
            {
                $page_content .= "\t\t\t".'<div class="list">'."\r\n";
                $page_content .= "\t\t\t\t".'<div class="item tabs tabs-secondary tabs-icon-left tabs-stable">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<a class="tab-item" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_cart"><i class="icon ion-android-cart"></i> {{ \'Shopping Cart\' | translate }} ( {{ item_in_virtual_table_'.$tables_prefix.' }} )</a>'."\r\n";
                $page_content .= "\t\t\t\t\t".'<a class="tab-item" ng-click="addToDbVirtual('.$tables_prefix.');"><i class="icon ion-android-add-circle"></i> {{ \'Add To Cart\' | translate }}</a>'."\r\n";
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'</div>'."\r\n";
            }
            // TODO: --|-- ADD BOOKMARK BUTTON
            if($new_tables['tables'][$tables_prefix]['bookmarks'] == 'bookmark')
            {
                $page_content .= "\t\t\t".'<div class="list">'."\r\n";
                $page_content .= "\t\t\t\t".'<div class="item tabs tabs-secondary tabs-icon-left tabs-stable">'."\r\n";
                $page_content .= "\t\t\t\t\t".'<a class="tab-item" ng-click="addToDbVirtual('.$tables_prefix.');"><i class="icon ion-android-add-circle"></i> {{ \'Add To Bookmark\' | translate }}</a>'."\r\n";
                $page_content .= "\t\t\t\t\t".'<a class="tab-item" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_bookmark"><i class="icon ion-ios-star"></i> {{ \'Bookmark\' | translate }} ( {{ item_in_virtual_table_'.$tables_prefix.' }} )</a>'."\r\n";
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'</div>'."\r\n";
            }
            $page_content .= "\t\t\t".'<br/><br/><br/>'."\r\n";

            //create a page
            if(!isset($heading_as_title))
            {
                $heading_as_title = ucwords($_POST['tables']['title']).' Detail';
            }
            // TODO: --|-- SAVE PAGE SINGLE
            $single_page['page'][0]['table-code']['detail'] = $page_detail_content;
            $single_page['page'][0]['cache'] = 'false';
            $single_page['page'][0]['version'] = 'Upd.'.date('ymdhi');
            $single_page['page'][0]['title'] = $heading_as_title;
            $single_page['page'][0]['prefix'] = $tables_prefix.'_singles';
            $single_page['page'][0]['for'] = 'table-item';
            $single_page['page'][0]['last_edit_by'] = 'table ('.$tables_prefix.')';
            $single_page['page'][0]['parent'] = $new_tables['tables'][$tables_prefix]['parent'];
            $single_page['page'][0]['content'] = $page_content;
            $single_page['page'][0]['priority'] = 'low';
            $single_page['page'][0]['menu'] = 'false';
            $single_page['page'][0]['menutype'] = 'sub-'.$_SESSION['PROJECT']['menu']['type'];
            $single_page['page'][0]['query'] = array($uid);
            $single_page['page'][0]['button_up'] = 'none';
            $single_page['page'][0]['builder_link'] = '';


            $single_page['page'][0]['scroll'] = true;


            $single_page['page'][0]['variables'] = $page_detail_var;
            if($is_lock == true)
            {
                $error_notice[] = 'Page <code>'.$tables_prefix.'_singles'.'</code> is <span class="fa fa-lock"></span> locked.';
            } else
            {
                if(!isset($new_tables['tables'][$tables_prefix]['relation_to']))
                {
                    $new_tables['tables'][$tables_prefix]['relation_to'] = 'none';
                }
                if($new_tables['tables'][$tables_prefix]['relation_to'] == 'none')
                {
                    //create page single if relation none
                    file_put_contents($create_single_page_files,json_encode($single_page));
                } else
                {
                    //remove page if relation
                    if(file_exists($create_single_page_files))
                    {
                        @unlink($create_single_page_files);
                    }
                }
                // TODO: --|-- REMOVE PAGE SINGLES
                //remove page single if homepage
                if($new_tables['tables'][$tables_prefix]['template'] == 'faqs')
                {
                    if(file_exists($create_single_page_files))
                    {
                        @unlink($create_single_page_files);
                    }
                }
                if($new_tables['tables'][$tables_prefix]['template'] == 'homepage1')
                {
                    if(file_exists($create_single_page_files))
                    {
                        @unlink($create_single_page_files);
                    }
                }
                //remove page single if homepage
                if($new_tables['tables'][$tables_prefix]['template'] == 'table')
                {
                    if(file_exists($create_single_page_files))
                    {
                        @unlink($create_single_page_files);
                    }
                }
                //remove page single if gmapmarker
                if($new_tables['tables'][$tables_prefix]['template'] == 'gmapmarker')
                {
                    if(file_exists($create_single_page_files))
                    {
                        @unlink($create_single_page_files);
                    }
                }
                //remove page single if homepage
                if($new_tables['tables'][$tables_prefix]['template'] == 'slidebox-1')
                {
                    if(file_exists($create_single_page_files))
                    {
                        @unlink($create_single_page_files);
                    }
                }
                //remove page single if homepage
                if($new_tables['tables'][$tables_prefix]['template'] == 'manual_coding')
                {
                    if(file_exists($create_single_page_files))
                    {
                        @unlink($create_single_page_files);
                    }
                }
                //remove page single if homepage
                if($new_tables['tables'][$tables_prefix]['template'] == 'dictionary')
                {
                    if(file_exists($create_single_page_files))
                    {
                        @unlink($create_single_page_files);
                    }
                }

                if($new_tables['tables'][$tables_prefix]['template'] == 'chart-doughnut')
                {
                    if(file_exists($create_single_page_files))
                    {
                        @unlink($create_single_page_files);
                    }
                }

                if($new_tables['tables'][$tables_prefix]['template'] == 'chart-bar')
                {
                    if(file_exists($create_single_page_files))
                    {
                        @unlink($create_single_page_files);
                    }
                }
                if($new_tables['tables'][$tables_prefix]['template'] == 'chart-line')
                {
                    if(file_exists($create_single_page_files))
                    {
                        @unlink($create_single_page_files);
                    }
                }

            }


            // TODO: --|-- TEMPLATE DATA LISTING
            $page_content = "\r\n";
            $page_content .= "\t\t\t".'<!-- code refresh -->'."\r\n";
            $page_content .= "\t\t\t".'<ion-refresher pulling-text="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['pull_for_refresh']).'\' | translate }}"  on-refresh="doRefresh()"></ion-refresher>'."\r\n";
            $page_content .= "\t\t\t".'<!-- ./code refresh -->'."\r\n";
            $page_content .= "\t\t\t".''."\r\n\r\n";
            $current_page['page'][0]['class'] = '';
            $current_page['page'][0]['attr'] = '';
            $current_page['page'][0]['button_up'] = 'none';
            $current_page['page'][0]['img_bg'] = '';
            $current_page['page'][0]['scroll'] = true;

            $page_content_js = null;
            $_code_search = $_code_listing = $_code_infinite_scroll = $_code_search_not_found = null;


            if(!isset($new_tables['tables'][$tables_prefix]['motions']))
            {
                $new_tables['tables'][$tables_prefix]['motions'] = 'fade-slide-in-right';
            }
            if($new_tables['tables'][$tables_prefix]['motions'] == '')
            {
                $new_tables['tables'][$tables_prefix]['motions'] = 'fade-slide-in-right';
            }


            // TODO: ------|----  OPTION
            if(!isset($new_tables['tables'][$tables_prefix]["items_focus"]))
            {
                $new_tables['tables'][$tables_prefix]["items_focus"] = 'scroll';
            }

            $focus_search = $new_tables['tables'][$tables_prefix]["items_focus"];

            if(!isset($new_tables['tables'][$tables_prefix]["max_items"]))
            {
                $new_tables['tables'][$tables_prefix]["max_items"] = 50;
            }
            if(!is_numeric($new_tables['tables'][$tables_prefix]["max_items"]))
            {
                $new_tables['tables'][$tables_prefix]["max_items"] = 50;
            }
            $items_max = (int)$new_tables['tables'][$tables_prefix]["max_items"];

            $filter_1st_param = '';
            if(!isset($new_tables['tables'][$tables_prefix]['db_url_dinamic']))
            {
                $new_tables['tables'][$tables_prefix]['db_url_dinamic'] = false;
            }
            if($new_tables['tables'][$tables_prefix]['db_url_dinamic'] == true)
            {
                $filter_1st_param = '| filter: first_param ';
            }


            $current_page['page'][0]['class'] = '';
            $current_page['page'][0]['attr'] = '';
            $current_page['page'][0]['hide-navbar'] = false;
            $current_page['page'][0]['title-tranparant'] = false;
            $current_page['page'][0]['remove-has-header'] = false;
            $current_page['page'][0]['overflow-scroll'] = false;
            $current_page['page'][0]['scroll'] = false;

            switch($new_tables['tables'][$tables_prefix]["template"])
            {
                    // TODO: ----------|-- BUTTON
                case 'button':

                    $code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                    $code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                    $code_search .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $code_search .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $code_search .= "\t\t\t\t".'</div>'."\r\n";
                    $code_search .= "\t\t\t".'</div>'."\r\n";
                    $code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                    $code_search .= "\t\t\t".''."\r\n\r\n";

                    if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
                    {
                        $code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                        $code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                        $code_search .= "\t\t\t\t".'<div class="item item-input-inset">'."\r\n";
                        $code_search .= "\t\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                        $code_search .= "\t\t\t\t\t".'</label>'."\r\n";
                        $code_search .= "\t\t\t\t\t".'<button class="button button-small" barcode-scanner barcode-text="filter_'.$tables_prefix.'s"><i class="ion-qr-scanner"></i></button>'."\r\n";
                        $code_search .= "\t\t\t\t".'</div>'."\r\n";
                        $code_search .= "\t\t\t".'</div>'."\r\n";
                        $code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                        $code_search .= "\t\t\t".''."\r\n\r\n";
                    }

                    $_code_listing = "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";
                    if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
                    {
                        $icon_pos = 'back';
                        $button_pos = 'left';
                    } else
                    {
                        $icon_pos = 'forward';
                        $button_pos = 'right';
                    }

                    if($focus_search == 'scroll')
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="item item-text-wrap item-button-'.$button_pos.'" '.$direction.' ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null" >'."\r\n";
                    } elseif($focus_search == 'search')
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="item item-text-wrap item-button-'.$button_pos.'" '.$direction.' ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results" >'."\r\n";
                    }

                    if(isset($var_item_title))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<span ng-bind-html="item.'.$var_item_title.' | to_trusted"></span>'."\r\n";
                    } else
                    {
                        $_code_listing .= 'Type `heading-1`???';
                    }
                    $_code_listing .= "\t\t\t\t".'<a class="button button-'.$new_tables['tables'][$tables_prefix]["itemcolor"].'" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" ><i class="icon ion-ios-arrow-'.$icon_pos.'"></i></a>'."\r\n";
                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".''."\r\n\r\n";
                    $_code_infinite_scroll = "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".''."\r\n\r\n\r\n";
                    $_code_search_not_found = "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<ion-list class="list" '.$direction.'>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'<div class="item" ng-if="results.length == 0" >'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</p>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<!-- ./code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".''."\r\n\r\n";
                    $page_content .= $code_search;
                    $page_content .= $_code_listing;

                    if($focus_search == 'scroll')
                    {
                        $page_content .= $_code_infinite_scroll;
                    }

                    $page_content .= $_code_search_not_found;

                    $current_page['page'][0]['table-code']['search'] = $code_search;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    $current_page['page'][0]['table-code']['infinite-scroll'] = $_code_infinite_scroll;
                    $current_page['page'][0]['table-code']['search-result'] = $_code_search_not_found;
                    break;
                    // TODO: ----------|-- GALLERY
                case 'gallery':
                    $filter_1st_param = '';
                    $link_open = "\t\t\t\t\t\t".'<a class="ink" href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" >'."\r\n";
                    $link = '#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}';
                    $link_close = "\t\t\t\t\t".'</a>'."\r\n";
                    $_code_listing = null;
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list row" ng-repeat="rows in chunked_'.$tables_prefix.'s '.$filter_1st_param.'" >'."\r\n";
                    $_code_listing .= "\t\t\t\t".'<div class="col card" ng-repeat="item in rows">'."\r\n";
                    if(isset($var_item_title))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<a class="item gallery ink button button-'.str_replace('-900','',$new_tables['tables'][$tables_prefix]["itemcolor"]).'" ng-href="'.$link.'">'."\r\n";
                        $_code_listing .= "\t\t\t\t\t\t".'<span ng-bind-html="item.'.$var_item_title.' | to_trusted"></span>'."\r\n";
                        $_code_listing .= "\t\t\t\t\t".'</a>'."\r\n";
                    }
                    if(isset($var_item_images))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<a class="item item-image ink" ng-href="'.$link.'">'."\r\n";
                        if($_SESSION['PROJECT']['app']['lazyload'] == true)
                        {
                            $_code_listing .= "\t\t\t\t\t\t".'<img alt="" class="ratio1x1" image-lazy-loader="lines" image-lazy-src="{{item.'.$var_item_images.'}}" />'."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t".'<img alt="" class="ratio1x1" ng-src="{{item.'.$var_item_images.'}}" />'."\r\n";
                        }
                        $_code_listing .= "\t\t\t\t\t".'</a>'."\r\n";
                    }
                    if(isset($var_item_video))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="item item-image">'."\r\n";
                        $_code_listing .= "\t\t\t\t\t\t".'<video class="full-image"   controls="controls"><source src="{{item.'.$var_item_video.'}}" type="video/mp4" /></video>'."\r\n";
                        $_code_listing .= "\t\t\t\t\t".'</div>'."\r\n";
                    }
                    if(isset($var_item_text))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="item item-text-wrap" '.$direction.'>'."\r\n";
                        if(preg_match("/\[txt\]/",$var_item_text_label))
                        {
                            $_code_listing .= "\t\t\t\t\t\t".str_replace("[txt]",'{{item.'.$var_item_text.'}}',$var_item_text_label)."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t".$var_item_text_label.'{{item.'.$var_item_text.'}}'."\r\n";
                        }
                        $_code_listing .= "\t\t\t\t\t".'</div>'."\r\n";
                    }
                    if(isset($var_item_rating))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="item"><rating ng-model="item.'.$var_item_rating.'" max="rating.max"></rating></div>'."\r\n";
                    }
                    if(isset($var_item_link))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="item tabs tabs-secondary tabs-icon-left">'."\r\n";
                        $_code_listing .= "\t\t\t\t\t\t".'<a class="tab-item stable-bg assertive" ng-click="openURL(item.'.$var_item_link.')"><i class="icon ion-link"></i></a>'."\r\n";
                        $_code_listing .= "\t\t\t\t\t\t".'<a class="tab-item stable-bg positive-900" ng-href="'.$link.'"><i class="icon ion-ios-arrow-forward"></i></a>'."\r\n";
                        $_code_listing .= "\t\t\t\t\t".'</div>'."\r\n";
                    }
                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".''."\r\n\r\n";
                    $page_content .= $_code_listing;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    break;
                    // TODO:  ----------|-- 2 ICON
                case '2-icon':
                    $code_search = null;
                    $code_search .= "\t\t\t".'<!-- code search -->'."\r\n";
                    $code_search .= "\t\t\t".'<ion-list class="card list" '.$direction.'>'."\r\n";
                    $code_search .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $code_search .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $code_search .= "\t\t\t\t".'</div>'."\r\n";
                    $code_search .= "\t\t\t".'</ion-list>'."\r\n";
                    $code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                    $code_search .= "\r\n\r\n";

                    if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
                    {
                        $code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                        $code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                        $code_search .= "\t\t\t\t".'<div class="item item-input-inset">'."\r\n";
                        $code_search .= "\t\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                        $code_search .= "\t\t\t\t\t".'</label>'."\r\n";
                        $code_search .= "\t\t\t\t\t".'<button class="button button-small" barcode-scanner barcode-text="filter_'.$tables_prefix.'s"><i class="ion-qr-scanner"></i></button>'."\r\n";
                        $code_search .= "\t\t\t\t".'</div>'."\r\n";
                        $code_search .= "\t\t\t".'</div>'."\r\n";
                        $code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                        $code_search .= "\t\t\t".''."\r\n\r\n";
                    }


                    $_code_listing = null;
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="card list light-bg animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";

                    if($focus_search == 'scroll')
                    {
                        $_code_listing .= "\t\t\t\t".'<a class="item item-icon-left ink-'.$item_color.' item-icon-right " '.$direction.' ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" >'."\r\n";
                    } elseif($focus_search == 'search')
                    {
                        $_code_listing .= "\t\t\t\t".'<a class="item item-icon-left ink-'.$item_color.' item-icon-right " '.$direction.' ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results"  ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" >'."\r\n";
                    }
                    if(isset($var_item_icon_left))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<i class="icon  '.$item_color.' {{item.'.$var_item_icon_left.'}}"></i>'."\r\n";
                    } else
                    {
                        $_code_listing .= "\t\t\t\t\t".'<i class="icon '.$item_color.' '.$new_tables['tables'][$tables_prefix]['icon'].'"></i>'."\r\n";
                    }
                    if(isset($var_item_title))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<span ng-bind-html="item.'.$var_item_title.' | to_trusted"></span>'."\r\n";
                    } else
                    {
                        $_code_listing .= 'Type `heading-1`???';
                    }
                    if(isset($var_item_text))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<span class="item-note item-text-wrap" '.$direction.'>'."\r\n";
                        if(preg_match("/\[txt\]/",$var_item_text_label))
                        {
                            $_code_listing .= "\t\t\t\t\t\t".str_replace("[txt]",'{{item.'.$var_item_text.'}}',$var_item_text_label)."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t".$var_item_text_label.'{{item.'.$var_item_text.'}}'."\r\n";
                        }
                        $_code_listing .= "\t\t\t\t\t\t".'</span>'."\r\n";
                    }
                    if(isset($var_item_html))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="item-note item-text-wrap" ng-bind-html="item.'.$var_item_html.' | to_trusted"></div>'."\r\n";
                    }
                    $_code_listing .= "\t\t\t\t\t".'<i class="icon ion-arrow-right-c"></i>'."\r\n";
                    $_code_listing .= "\t\t\t\t".'</a>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\r\n\r\n";
                    $_code_search_not_found = null;
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<ion-list ng-if="results.length == 0" class="list card">'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'<div class="item"  >'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</p>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<!-- ./code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\r\n\r\n";
                    $_code_infinite_scroll = null;
                    $_code_infinite_scroll .= "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\r\n\r\n";

                    $page_content .= $code_search;
                    $page_content .= $_code_listing;
                    if($focus_search == 'scroll')
                    {
                        $page_content .= $_code_infinite_scroll;
                    }
                    $page_content .= $_code_search_not_found;
                    $current_page['page'][0]['table-code']['search'] = $code_search;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    $current_page['page'][0]['table-code']['infinite-scroll'] = $_code_infinite_scroll;
                    $current_page['page'][0]['table-code']['search-result'] = $_code_search_not_found;
                    break;
                    // TODO:  ----------|-- 1 ICON
                case '1-icon':
                    $_code_search = null;
                    $_code_search .= "\t\t\t".'<!-- code search -->'."\r\n";
                    $_code_search .= "\t\t\t".'<ion-list class="card list" '.$direction.'>'."\r\n";
                    $_code_search .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                    $_code_search .= "\r\n\r\n";


                    if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
                    {
                        $_code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                        $_code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                        $_code_search .= "\t\t\t\t".'<div class="item item-input-inset">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'</label>'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<button class="button button-small" barcode-scanner barcode-text="filter_'.$tables_prefix.'s"><i class="ion-qr-scanner"></i></button>'."\r\n";
                        $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                        $_code_search .= "\t\t\t".''."\r\n\r\n";
                    }


                    $_code_listing = null;
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";
                    if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
                    {
                        $icon_pos = 'right';
                    } else
                    {
                        $icon_pos = 'left';
                    }

                    if($focus_search == 'scroll')
                    {
                        $_code_listing .= "\t\t\t\t".'<a class="item item-icon-'.$icon_pos.'" '.$direction.' ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" >'."\r\n";
                    } elseif($focus_search == 'search')
                    {
                        $_code_listing .= "\t\t\t\t".'<a class="item item-icon-'.$icon_pos.'" '.$direction.' ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results"  ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" >'."\r\n";
                    }

                    if(isset($var_item_icon_left))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<i class="icon '.$item_color.' {{item.'.$var_item_icon_left.'}}"></i>'."\r\n";
                    } elseif(isset($var_item_images))
                    {
                        if($_SESSION['PROJECT']['app']['lazyload'] == true)
                        {
                            $_code_listing .= "\t\t\t\t\t\t".'<i class="icon"><img alt="" image-lazy-loader="lines" image-lazy-src="{{item.'.$var_item_images.'}}"   /></i>'."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t".'<i class="icon"><img alt="" ng-src="{{item.'.$var_item_images.'}}" /></i>'."\r\n";
                        }
                    } else
                    {
                        $_code_listing .= "\t\t\t\t\t".'<i class="icon '.$item_color.' '.$new_tables['tables'][$tables_prefix]['icon'].'"></i>'."\r\n";
                    }
                    if(isset($var_item_title))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<span ng-bind-html="item.'.$var_item_title.' | to_trusted"></span>'."\r\n";
                    } else
                    {
                        $_code_listing .= 'Type `heading-1`???';
                    }
                    if(isset($var_item_text))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<p '.$direction.'>'."\r\n";
                        if(preg_match("/\[txt\]/",$var_item_text_label))
                        {
                            $_code_listing .= "\t\t\t\t\t\t".str_replace("[txt]",'{{item.'.$var_item_text.'}}',$var_item_text_label)."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t".$var_item_text_label.'{{item.'.$var_item_text.'}}'."\r\n";
                        }
                        $_code_listing .= "\t\t\t\t\t\t".'</p>'."\r\n";
                    }
                    $_code_listing .= "\t\t\t\t".'</a>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_infinite_scroll = null;
                    $_code_infinite_scroll .= "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\r\n\r\n";
                    $_code_search_not_found = null;
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'<div class="item" ng-if="results.length == 0" >'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</p>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\r\n\r\n";
                    $page_content .= $_code_search;
                    $page_content .= $_code_listing;

                    if($focus_search == 'scroll')
                    {
                        $page_content .= $_code_infinite_scroll;
                    }

                    $page_content .= $_code_search_not_found;
                    $current_page['page'][0]['table-code']['search'] = $_code_search;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    $current_page['page'][0]['table-code']['infinite-scroll'] = $_code_infinite_scroll;
                    $current_page['page'][0]['table-code']['search-result'] = $_code_search_not_found;
                    break;
                    // TODO:  ----------|-- FAQS
                case 'faqs':
                    $_code_search = null;
                    $_code_search .= "\t\t\t".'<!-- code search -->'."\r\n";
                    $_code_search .= "\t\t\t".'<ion-list class="card list" '.$direction.'>'."\r\n";
                    $_code_search .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                    $_code_search .= "\r\n\r\n";

                    if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
                    {
                        $_code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                        $_code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                        $_code_search .= "\t\t\t\t".'<div class="item item-input-inset">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'</label>'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<button class="button button-small" barcode-scanner barcode-text="filter_'.$tables_prefix.'s"><i class="ion-qr-scanner"></i></button>'."\r\n";
                        $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                        $_code_search .= "\t\t\t".''."\r\n\r\n";
                    }

                    $_code_listing = null;
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
                    {
                        $icon_pos = 'right';
                    } else
                    {
                        $icon_pos = 'left';
                    }

                    if($focus_search == 'scroll')
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="card" '.$direction.' ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null"   >'."\r\n";
                    } elseif($focus_search == 'search')
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="card" '.$direction.' ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results"  >'."\r\n";
                    }

                    $_code_listing .= "\t\t\t\t\t".'<ion-item class="item item-'.$item_color.' noborder" ng-click="toggleGroup(item)" ng-class="{active: isGroupShown(item)}" >'."\r\n";
                    $_code_listing .= "\t\t\t\t\t\t".'<i class="icon" ng-class="isGroupShown(item) ? \'ion-minus\' : \'ion-plus\'"></i>'."\r\n";
                    $_code_listing .= "\t\t\t\t\t\t".'&nbsp;'."\r\n";
                    if(isset($var_item_title))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<span ng-bind-html="item.'.$var_item_title.' | to_trusted"></span>'."\r\n";
                    } else
                    {
                        $_code_listing .= '?';
                    }
                    $_code_listing .= "\t\t\t\t\t".'</ion-item>'."\r\n";
                    $_code_listing .= "\t\t\t\t\t".'<ion-item class="item item-text-wrap" ng-show="isGroupShown(item)">'."\r\n";
                    if(isset($var_item_text))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<p '.$direction.'>'."\r\n";
                        if(preg_match("/\[txt\]/",$var_item_text_label))
                        {
                            $_code_listing .= "\t\t\t\t\t\t".str_replace("[txt]",'{{item.'.$var_item_text.'}}',$var_item_text_label)."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t".$var_item_text_label.'{{item.'.$var_item_text.'}}'."\r\n";
                        }
                        $_code_listing .= "\t\t\t\t\t\t".'</p>'."\r\n";
                    }
                    if(isset($var_item_html))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<div class="to_trusted" ng-bind-html="item.'.$var_item_html.' | to_trusted"></div>'."\r\n";
                    }
                    $_code_listing .= "\t\t\t\t\t".'</ion-item>'."\r\n";
                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n"; //loop
                    $_code_listing .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_infinite_scroll = null;
                    $_code_infinite_scroll .= "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\r\n\r\n";
                    $_code_search_not_found = null;
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'<div class="item" ng-if="results.length == 0" >'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</p>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\r\n\r\n";
                    $page_content .= $_code_search;
                    $page_content .= $_code_listing;
                    if($focus_search == 'scroll')
                    {
                        $page_content .= $_code_infinite_scroll;
                    }
                    $page_content .= $_code_search_not_found;
                    $current_page['page'][0]['table-code']['search'] = $_code_search;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    $current_page['page'][0]['table-code']['infinite-scroll'] = $_code_infinite_scroll;
                    $current_page['page'][0]['table-code']['search-result'] = $_code_search_not_found;
                    break;
                    // TODO:  ----------|-- THUMBNAIL
                case 'thumbnail':
                    $_code_search = null;
                    $_code_search .= "\t\t\t".'<!-- code search -->'."\r\n";
                    $_code_search .= "\t\t\t".'<ion-list class="card list" '.$direction.'>'."\r\n";
                    $_code_search .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                    $_code_search .= "\r\n\r\n";

                    if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
                    {
                        $_code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                        $_code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                        $_code_search .= "\t\t\t\t".'<div class="item item-input-inset">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'</label>'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<button class="button button-small" barcode-scanner barcode-text="filter_'.$tables_prefix.'s"><i class="ion-qr-scanner"></i></button>'."\r\n";
                        $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                        $_code_search .= "\t\t\t".''."\r\n\r\n";
                    }


                    $_code_listing = null;
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";
                    if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
                    {
                        $icon_pos = 'right';
                    } else
                    {
                        $icon_pos = 'left';
                    }
                    $link = '#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}';
                    if($focus_search == 'scroll')
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="list card" ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null" href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" >'."\r\n";
                    } elseif($focus_search == 'search')
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="list card" ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results" href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" >'."\r\n";
                    }

                    if(isset($var_item_title))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="item item-'.$item_color.'" '.$direction.' ng-bind-html="item.'.$var_item_title.' | to_trusted"></div>'."\r\n";
                    }
                    if(isset($var_item_images))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="item item-thumbnail-'.$icon_pos.' item-text-wrap">'."\r\n";
                        if($_SESSION['PROJECT']['app']['lazyload'] == true)
                        {
                            $_code_listing .= "\t\t\t\t\t\t".'<img alt="" class="full-image" image-lazy-loader="lines" image-lazy-src="{{item.'.$var_item_images.'}}"   />'."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t".'<img alt="" class="full-image" ng-src="{{item.'.$var_item_images.'}}" />'."\r\n";
                        }
                    } else
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="item item-text-wrap">'."\r\n";
                    }
                    if(isset($var_item_youtube))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="full-image embed-responsive embed-responsive-16by9"><iframe width="100%" ng-src="{{\'https://www.youtube.com/embed/\' + item.'.$var_item_youtube.'}}" frameborder="0" allowfullscreen></iframe></div>'."\r\n";
                    }
                    if(isset($var_item_text))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<p '.$direction.'>'."\r\n";
                        if(preg_match("/\[txt\]/",$var_item_text_label))
                        {
                            $_code_listing .= "\t\t\t\t\t\t".str_replace("[txt]",'{{item.'.$var_item_text.'}}',$var_item_text_label)."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t".$var_item_text_label.'{{item.'.$var_item_text.'}}'."\r\n";
                        }
                        $_code_listing .= "\t\t\t\t\t\t".'</p>'."\r\n";
                    }
                    if(isset($var_item_html))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<div class="to_trusted" ng-bind-html="item.'.$var_item_html.' | limitTo:140 | to_trusted"></div>'."\r\n";
                    }
                    if(isset($var_item_rating))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<p><rating ng-model="item.'.$var_item_rating.'" max="rating.max"></rating></p>'."\r\n";
                    }

                    if(isset($var_item_paragraph))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<div class="to_trusted" ng-bind-html="item.'.$var_item_paragraph.' | limitTo:140 | to_trusted"></div>'."\r\n";
                    }

                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t\t\t".'<a class="item button button-clear '.$item_color.' ink" href="'.$link.'">More</a>'."\r\n";
                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_infinite_scroll = null;
                    $_code_infinite_scroll .= "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\r\n\r\n";
                    $_code_search_not_found = null;
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'<div class="item" ng-if="results.length == 0" >'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</p>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\r\n\r\n";
                    $page_content .= $_code_search;
                    $page_content .= $_code_listing;
                    if($focus_search == 'scroll')
                    {
                        $page_content .= $_code_infinite_scroll;
                    }
                    $page_content .= $_code_search_not_found;
                    $current_page['page'][0]['table-code']['search'] = $_code_search;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    $current_page['page'][0]['table-code']['infinite-scroll'] = $_code_infinite_scroll;
                    $current_page['page'][0]['table-code']['search-result'] = $_code_search_not_found;
                    break;
                    // TODO:  ----------|-- THUMBNAIL II
                case 'thumbnail-2':
                    $_code_search = null;
                    $_code_search .= "\t\t\t".'<!-- code search -->'."\r\n";
                    $_code_search .= "\t\t\t".'<ion-list class="list" '.$direction.'>'."\r\n";
                    $_code_search .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                    $_code_search .= "\r\n\r\n";

                    if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
                    {
                        $_code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                        $_code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                        $_code_search .= "\t\t\t\t".'<div class="item item-input-inset">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'</label>'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<button class="button button-small" barcode-scanner barcode-text="filter_'.$tables_prefix.'s"><i class="ion-qr-scanner"></i></button>'."\r\n";
                        $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                        $_code_search .= "\t\t\t".''."\r\n\r\n";
                    }


                    $_code_listing = null;
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";
                    if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
                    {
                        $icon_pos = 'right';
                    } else
                    {
                        $icon_pos = 'left';
                    }
                    $link = '#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}';

                    if(isset($var_item_images))
                    {

                        if($focus_search == 'scroll')
                        {
                            $_code_listing .= "\t\t\t\t".'<a class="item item-thumbnail-'.$icon_pos.' item-text-wrap" ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null" href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";
                        } elseif($focus_search == 'search')
                        {
                            $_code_listing .= "\t\t\t\t".'<a class="item item-thumbnail-'.$icon_pos.' item-text-wrap" ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results"  href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";
                        }

                        if($_SESSION['PROJECT']['app']['lazyload'] == true)
                        {
                            $_code_listing .= "\t\t\t\t\t".'<img alt="" class="full-image " image-lazy-loader="lines" image-lazy-src="{{item.'.$var_item_images.'}}"   />'."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t".'<img alt="" class="full-image" ng-src="{{item.'.$var_item_images.'}}" />'."\r\n";
                        }
                    } else
                    {
                        if($focus_search == 'scroll')
                        {
                            $_code_listing .= "\t\t\t\t".'<a class="item item-text-wrap" ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null" href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";
                        } elseif($focus_search == 'search')
                        {
                            $_code_listing .= "\t\t\t\t".'<a class="item item-text-wrap" ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results"  href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";
                        }
                    }


                    if(isset($var_item_youtube))
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="full-image embed-responsive embed-responsive-16by9"><iframe width="100%" ng-src="{{ \'https://www.youtube.com/embed/\' + item.'.$var_item_youtube.'}}" frameborder="0" allowfullscreen></iframe></div>'."\r\n";
                    }
                    if(isset($var_item_title))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<h3 class="" '.$direction.' ng-bind-html="item.'.$var_item_title.' | to_trusted"></h3>'."\r\n";
                    }
                    if(isset($var_item_text))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<p '.$direction.'>'."\r\n";
                        if(preg_match("/\[txt\]/",$var_item_text_label))
                        {
                            $_code_listing .= "\t\t\t\t\t\t".str_replace("[txt]",'{{item.'.$var_item_text.'}}',$var_item_text_label)."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t".$var_item_text_label.'{{item.'.$var_item_text.'}}'."\r\n";
                        }
                        $_code_listing .= "\t\t\t\t\t\t".'</p>'."\r\n";
                    }
                    if(isset($var_item_html))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="to_trusted" ng-bind-html="item.'.$var_item_html.' | limitTo:140 | to_trusted"></div>'."\r\n";
                    }
                    if(isset($var_item_rating))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<p><rating ng-model="item.'.$var_item_rating.'" max="rating.max"></rating></p>'."\r\n";
                    }
                    $_code_listing .= "\t\t\t\t".'</a>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\r\n\r\n";
                    $_code_infinite_scroll = null;
                    $_code_infinite_scroll .= "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\r\n\r\n";
                    $_code_search_not_found = null;
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'<div class="item" ng-if="results.length == 0" >'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }} </p>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\r\n\r\n";
                    $page_content .= $_code_search;
                    $page_content .= $_code_listing;
                    if($focus_search == 'scroll')
                    {
                        $page_content .= $_code_infinite_scroll;
                    }
                    $page_content .= $_code_search_not_found;
                    $current_page['page'][0]['table-code']['search'] = $_code_search;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    $current_page['page'][0]['table-code']['infinite-scroll'] = $_code_infinite_scroll;
                    $current_page['page'][0]['table-code']['search-result'] = $_code_search_not_found;
                    break;
                    // TODO:  ----------|-- THUMBNAIL III
                case 'thumbnail-3':
                    $_code_search = null;
                    $_code_search .= "\t\t\t".'<!-- code search -->'."\r\n";
                    $_code_search .= "\t\t\t".'<ion-list class="list" '.$direction.'>'."\r\n";
                    $_code_search .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                    $_code_search .= "\r\n\r\n";

                    if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
                    {
                        $_code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                        $_code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                        $_code_search .= "\t\t\t\t".'<div class="item item-input-inset">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'</label>'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<button class="button button-small" barcode-scanner barcode-text="filter_'.$tables_prefix.'s"><i class="ion-qr-scanner"></i></button>'."\r\n";
                        $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                        $_code_search .= "\t\t\t".''."\r\n\r\n";
                    }

                    $_code_listing = null;
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";

                    if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
                    {
                        $icon_pos = 'right';
                        $icon_pos_x = 'left';
                    } else
                    {
                        $icon_pos = 'left';
                        $icon_pos_x = 'right';
                    }

                    $link = '#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}';

                    if(isset($var_item_images))
                    {

                        if($focus_search == 'scroll')
                        {
                            $_code_listing .= "\t\t\t\t".'<div class="item-thumbnail-3 card" ng-repeat="item in '.$tables_prefix.'s | filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null">'."\r\n";
                            $_code_listing .= "\t\t\t\t".'<a class="item item-'.$item_color.' item-thumbnail-'.$icon_pos.' "  href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";

                        } elseif($focus_search == 'search')
                        {
                            $_code_listing .= "\t\t\t\t".'<div class="item-thumbnail-3 card" ng-repeat="item in data_'.$tables_prefix.'s | filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results">'."\r\n";
                            $_code_listing .= "\t\t\t\t".'<a class="item item-'.$item_color.' item-thumbnail-'.$icon_pos.' "  href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";

                        }


                        if($_SESSION['PROJECT']['app']['lazyload'] == true)
                        {
                            $_code_listing .= "\t\t\t\t\t".'<img alt="" class="full-image " image-lazy-loader="lines" image-lazy-src="{{item.'.$var_item_images.'}}"   />'."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t".'<img alt="" class="full-image" ng-src="{{item.'.$var_item_images.'}}" />'."\r\n";
                        }

                    } else
                    {
                        if($focus_search == 'scroll')
                        {
                            $_code_listing .= "\t\t\t\t".'<div class="padding" ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null"><a class="item item-'.$item_color.'"  href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";

                        } elseif($focus_search == 'search')
                        {
                            $_code_listing .= "\t\t\t\t".'<div class="padding" ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results"  ><a class="item item-'.$item_color.'"  href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";

                        }

                    }

                    if(isset($var_item_youtube))
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="full-image embed-responsive embed-responsive-16by9"><iframe width="100%" ng-src="{{ \'https://www.youtube.com/embed/\' + item.'.$var_item_youtube.'}}" frameborder="0" allowfullscreen></iframe></div>'."\r\n";
                    }

                    if(isset($var_item_title))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<h3 class="" '.$direction.' ng-bind-html="item.'.$var_item_title.' | to_trusted"></h3>'."\r\n";
                    }

                    if(isset($var_item_text))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<p '.$direction.'>'."\r\n";
                        if(preg_match("/\[txt\]/",$var_item_text_label))
                        {
                            $_code_listing .= "\t\t\t\t\t\t".str_replace("[txt]",'{{item.'.$var_item_text.'}}',$var_item_text_label)."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t".$var_item_text_label.'{{item.'.$var_item_text.'}}'."\r\n";
                        }
                        $_code_listing .= "\t\t\t\t\t\t".'</p>'."\r\n";
                    }

                    if(isset($var_item_html))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="to_trusted" ng-bind-html="item.'.$var_item_html.' | limitTo:50 | to_trusted"></div>'."\r\n";
                    }


                    $_code_listing .= "\t\t\t\t".'<i class="icon ion-android-more-horizontal pull-'.$icon_pos_x.'"></i>'."\r\n";


                    $_code_listing .= "\t\t\t\t".'</a>'."\r\n";
                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n";

                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\r\n\r\n";
                    $_code_infinite_scroll = null;
                    $_code_infinite_scroll .= "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\r\n\r\n";
                    $_code_search_not_found = null;
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'<div class="item" ng-if="results.length == 0" >'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</p>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\r\n\r\n";
                    $page_content .= $_code_search;
                    $page_content .= $_code_listing;
                    if($focus_search == 'scroll')
                    {
                        $page_content .= $_code_infinite_scroll;
                    }
                    $page_content .= $_code_search_not_found;

                    $current_page['page'][0]['css'] = null;
                    $current_page['page'][0]['css'] .= '.item-thumbnail-3 img {'.$icon_pos.': 0 !important;}';
                    $current_page['page'][0]['css'] .= '.item-thumbnail-3 .icon {'.$icon_pos_x.': 6px !important;}';
                    $current_page['page'][0]['css'] .= '.item-thumbnail-3 .icon { bottom: 6px !important;}';

                    $current_page['page'][0]['table-code']['search'] = $_code_search;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    $current_page['page'][0]['table-code']['infinite-scroll'] = $_code_infinite_scroll;
                    $current_page['page'][0]['table-code']['search-result'] = $_code_search_not_found;
                    break;
                    // TODO:  ----------|-- SLIDEBOX MEDIA WIZARD
                case 'slidebox-1':
                    $page_content = null;
                    $current_page['page'][0]['button_up'] = 'none';
                    $current_page['page'][0]['class'] = 'fullscreen';
                    $current_page['page'][0]['attr'] = 'scroll="false"';
                    //$current_page['page'][0]['hide-navbar'] = true;
                    $current_page['page'][0]['img_bg'] = 'data/images/background/bg9.jpg';
                    $current_page['page'][0]['hide-navbar'] = false;
                    $current_page['page'][0]['title-tranparant'] = true;
                    $current_page['page'][0]['remove-has-header'] = true;
                    $current_page['page'][0]['overflow-scroll'] = true;
                    $current_page['page'][0]['scroll'] = true;
                    $current_page['page'][0]['css'] = '#'.$new_tables['tables'][$tables_prefix]['parent'].' #navbar-right-top .bar-header{background: transparent;box-shadow:none;-moz-box-shadow:none;-webkit-box-shadow:none;}'."\r\n";
                    $current_page['page'][0]['css'] .= '#'.$new_tables['tables'][$tables_prefix]['parent'].' .has-header{top: 0;}'."\r\n";
                    $page_content .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $page_content .= "\t\t\t".'<ion-slide-box show-pager="true" auto-play="true" pager-click="true" >'."\r\n";
                    $style_bg = '';
                    if(!isset($var_item_images))
                    {
                        $var_item_images = '';
                    }
                    if(strlen($var_item_images) > 0)
                    {
                        $style_bg = '{{item.'.$var_item_images.'}}';
                    }
                    $page_content .= "\t\t\t\t".'<ion-slide class="'.$item_color.'-bg" ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| limitTo : 5:0" style="opacity:0.8;" >'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<div class="text-center light" style="padding-top:40px;">'."\r\n";
                    $page_content .= "\t\t\t\t\t\t".'<div class="text-center padding light">'."\r\n";
                    $page_content .= "\t\t\t\t\t\t\t".'<img class="" ng-src="'.$style_bg.'" alt="" />'."\r\n";
                    if(isset($var_item_title))
                    {
                        $page_content .= "\t\t\t\t\t\t".'<h4 ng-bind-html="item.'.$var_item_title.' | to_trusted"></h4>'."\r\n";
                    }
                    if(isset($var_item_text))
                    {
                        $page_content .= "\t\t\t\t\t\t".'<p>';
                        if(preg_match("/\[txt\]/",$var_item_text_label))
                        {
                            $page_content .= str_replace("[txt]",'{{item.'.$var_item_text.'}}',$var_item_text_label);
                        } else
                        {
                            $page_content .= $var_item_text_label.'{{item.'.$var_item_text.'}}';
                        }
                        $page_content .= '</p>'."\r\n";
                    }
                    if(isset($var_item_html))
                    {
                        $page_content .= "\t\t\t\t\t\t\t".'<div ng-bind-html="item.'.$var_item_html.' | to_trusted"></div>'."\r\n";
                    }
                    if(isset($var_item_paragraph))
                    {
                        $page_content .= "\t\t\t\t\t\t".'<p>{{item.'.$var_item_paragraph.'}}</p>'."\r\n";
                    }
                    $page_content .= "\t\t\t\t\t\t".'</div>'."\r\n";
                    $page_content .= "\t\t\t\t\t".'</div>'."\r\n";
                    $page_content .= "\t\t\t\t".'</ion-slide>'."\r\n";
                    $page_content .= "\t\t\t".'</ion-slide-box>'."\r\n";
                    $page_content .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    break;
                    // TODO:  ----------|-- SLIDEBOX
                case 'slidebox':
                    $current_page['page'][0]['button_up'] = 'none';
                    $current_page['page'][0]['class'] = 'fullscreen';
                    $current_page['page'][0]['attr'] = 'scroll="false"';
                    $current_page['page'][0]['hide-navbar'] = false;
                    $current_page['page'][0]['title-tranparant'] = true;
                    $current_page['page'][0]['remove-has-header'] = true;
                    $current_page['page'][0]['overflow-scroll'] = true;
                    $current_page['page'][0]['scroll'] = true;
                    $page_content = null;
                    $page_content .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $page_content .= "\t\t\t".'<ion-slide-box show-pager="true" auto-play="true" pager-click="true" >'."\r\n";
                    $style_bg = '';
                    if(!isset($var_item_images))
                    {
                        $var_item_images = '';
                    }
                    if(strlen($var_item_images) > 0)
                    {
                        $style_bg = ' url(\'{{item.'.$var_item_images.'}}\') ';
                    }
                    $page_content .= "\t\t\t\t\t".'<ion-slide ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| limitTo : 5:0" style="background: #999 '.$style_bg.' no-repeat center center fixed;;background-size:cover;width:100%;height:100%;background-color:#ddd;" >'."\r\n";
                    $page_content .= "\t\t\t\t\t\t".'<div class="padding text-center light">'."\r\n";
                    $page_content .= "\t\t\t\t\t\t\t".'<div class="padding light dark-bg" style="opacity:0.8;margin-top:80px">'."\r\n";
                    if(isset($var_item_title))
                    {
                        $page_content .= "\t\t\t\t\t\t\t\t".'<h4 ng-bind-html="item.'.$var_item_title.' | to_trusted"></h4>'."\r\n";
                    }
                    if(isset($var_item_text))
                    {
                        $page_content .= "\t\t\t\t\t\t\t\t".'<p>';
                        if(preg_match("/\[txt\]/",$var_item_text_label))
                        {
                            $page_content .= str_replace("[txt]",'{{item.'.$var_item_text.'}}',$var_item_text_label);
                        } else
                        {
                            $page_content .= $var_item_text_label.'{{item.'.$var_item_text.'}}';
                        }
                        $page_content .= '</p>'."\r\n";
                    }
                    if(isset($var_item_paragraph))
                    {
                        $page_content .= "\t\t\t\t\t\t\t\t".'<p>{{item.'.$var_item_paragraph.'}}</p>'."\r\n";
                    }
                    if(isset($var_item_html))
                    {
                        $page_content .= "\t\t\t\t\t\t\t\t".'<div ng-bind-html="item.'.$var_item_html.' | to_trusted"></div>'."\r\n";
                    }
                    $page_content .= "\t\t\t\t\t\t\t\t".'<a class="button button-'.$item_color.'" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" >Readmore</a>'."\r\n";
                    $page_content .= "\t\t\t\t\t\t\t".'</div>'."\r\n";
                    $page_content .= "\t\t\t\t\t\t".'</div>'."\r\n";
                    $page_content .= "\t\t\t\t\t".'</ion-slide>'."\r\n";
                    $page_content .= "\t\t\t".'</ion-slide-box>'."\r\n";
                    $page_content .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    break;
                    // TODO:  ----------|-- NONE
                case 'none':
                    $_code_search = $_code_listing = $_code_infinite_scroll = $_code_search_not_found = null;
                    $_code_search .= "\t\t\t".'<!-- code search -->'."\r\n";
                    $_code_search .= "\t\t\t".'<ion-list class="list" '.$direction.'>'."\r\n";
                    $_code_search .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                    $_code_search .= "\r\n\r\n";

                    if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
                    {
                        $_code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                        $_code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                        $_code_search .= "\t\t\t\t".'<div class="item item-input-inset">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'</label>'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<button class="button button-small" barcode-scanner barcode-text="filter_'.$tables_prefix.'s"><i class="ion-qr-scanner"></i></button>'."\r\n";
                        $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                        $_code_search .= "\t\t\t".''."\r\n\r\n";
                    }


                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";
                    if($focus_search == 'scroll')
                    {
                        $_code_listing .= "\t\t\t\t".'<a class="'.$item_types.' '.$item_color.'" ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" >'."\r\n";
                    } elseif($focus_search == 'search')
                    {
                        $_code_listing .= "\t\t\t\t".'<a class="'.$item_types.' '.$item_color.'" ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results"  ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" >'."\r\n";
                    }

                    $_code_listing .= $page_list_content;
                    $_code_listing .= "\t\t\t\t".'</a>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\r\n\r\n";
                    $_code_infinite_scroll = null;
                    $_code_infinite_scroll .= "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\r\n\r\n";
                    $_code_search_not_found = null;
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'<div class="item" ng-if="results.length == 0" >'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</p>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\r\n\r\n";
                    $page_content .= $_code_search;
                    $page_content .= $_code_listing;
                    if($focus_search == 'scroll')
                    {
                        $page_content .= $_code_infinite_scroll;
                    }
                    $page_content .= $_code_search_not_found;
                    $current_page['page'][0]['table-code']['search'] = $_code_search;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    $current_page['page'][0]['table-code']['infinite-scroll'] = $_code_infinite_scroll;
                    $current_page['page'][0]['table-code']['search-result'] = $_code_search_not_found;
                    break;
                    // TODO:  ----------|-- HOME PAGE
                case 'homepage1':
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list">'."\r\n";
                    $_code_listing .= "\t\t\t\t".'<div class="item item-text-wrap" ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| limitTo:'.$items_max.':0"   >'."\r\n";
                    $_code_listing .= $page_list_content;
                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\r\n\r\n";
                    $page_content .= $_code_listing;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    break;
                    // TODO:  ----------|-- GMAP LISTING
                case 'gmapmarker':
                    $current_page['page'][0]['button_up'] = 'none';
                    if(!isset($new_tables['tables'][$tables_prefix]['option']['gmap']['center_map']))
                    {
                        $new_tables['tables'][$tables_prefix]['option']['gmap']['center_map'] = '48.85693,2.3412';
                    }
                    if(!isset($var_map))
                    {
                        $var_map = null;
                    }
                    $text_color = '';
                    if(($item_color != 'light') && ($item_color != 'stable'))
                    {
                        $text_color = 'light';
                    }
                    $page_content .= "\t\t\t".'<ion-list class="padding gmapmarker-search" '.$direction.'>'."\r\n";
                    $page_content .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $page_content .= "\t\t\t\t".'</div>'."\r\n";
                    $page_content .= "\t\t\t".'</ion-list>'."\r\n";
                    $page_content .= "\r\n";
                    $page_content .= "\t\t\t".'<ng-map draggable="true" class="gmapmarker-map" zoom="11" center="'.$new_tables['tables'][$tables_prefix]['option']['gmap']['center_map'].'" width="100%" height="100%" default-style="false">'."\r\n";
                    $page_content .= "\t\t\t\t".'<marker ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" on-click="openModal($event)" position="{{item.'.$var_map.'}}" clickable="true" id="{{item.'.$uid.'}}" ></marker>'."\r\n";
                    $page_content .= "\t\t\t".'</ng-map>'."\r\n";
                    $page_content .= "\r\n";
                    $page_content .= "\r\n";
                    $page_content .= "\t\t\t".'<script id="'.$tables_prefix.'-single.html" type="text/ng-template">'."\r\n";
                    $page_content .= "\t\t\t\t".'<ion-modal-view>'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<ion-header-bar class="bar bar-header '.$text_color.' bar-'.$item_color.'">'."\r\n";
                    if(isset($var_item_title))
                    {
                        $page_content .= "\t\t\t\t\t\t".'<div class="header-item title">{{ '.$tables_prefix.'.'.$var_item_title.' | to_trusted }}</div>'."\r\n";
                    }
                    $page_content .= "\t\t\t\t\t\t".'<div class="buttons buttons-right header-item"><span class="right-buttons"><button class="button button-icon button-clear ion-close ink-black" ng-click="modal.hide()"></button></span></div>'."\r\n";
                    $page_content .= "\t\t\t\t\t".'</ion-header-bar>'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<ion-content>'."\r\n";
                    $page_content .= $page_detail_content;
                    $page_content .= "\t\t\t\t\t".'</ion-content>'."\r\n";
                    $page_content .= "\t\t\t\t".'</ion-modal-view>'."\r\n";
                    $page_content .= "\t\t\t".'</script>'."\r\n";
                    $page_content .= "\r\n";
                    $page_content_js = '
$scope.'.$tables_prefix.' = [];
$ionicModal.fromTemplateUrl("'.$tables_prefix.'-single.html",{scope: $scope,animation:"slide-in-up"}).then(function(modal){
    $scope.modal = modal;
});
$scope.openModal = function() {
    $scope.'.$tables_prefix.' = [];
    var itemID = this.id;
	for (var i = 0; i < data_'.$tables_prefix.'s.length; i++) {
		if((data_'.$tables_prefix.'s[i].'.$uid.' ===  parseInt(itemID)) || (data_'.$tables_prefix.'s[i].'.$uid.' === itemID.toString())) {
			$scope.'.$tables_prefix.' = data_'.$tables_prefix.'s[i] ;
		}
	}    
    $scope.modal.show();
};
$scope.closeModal = function() {
    $scope.modal.hide();
};
$scope.$on("$destroy", function() {
    $scope.modal.remove();
});
';
                    $current_page['page'][0]['css'] = '#page-'.$new_tables['tables'][$tables_prefix]['parent'].' .gmapmarker-map {position: absolute;width:100%;height: 100%;margin: 0;padding:0;z-index:-1}'."\r\n";
                    $current_page['page'][0]['css'] .= '#page-'.$new_tables['tables'][$tables_prefix]['parent'].' .gmapmarker-search {position: fixed;top:40px;z-index: 999;width:100%;background-color:transparent;opacity:1;}'."\r\n";
                    $current_page['page'][0]['css'] .= '#page-'.$new_tables['tables'][$tables_prefix]['parent'].' .item.item-input {background-color:#ffffff;opacity:0.8;}'."\r\n";
                    break;
                    // TODO:  ----------|-- MANUAL CODING
                case 'manual_coding':
                    $coding_cols = $new_tables['tables'][$tables_prefix]['cols'];
                    $page_content .= "\t\t\t".'<div class="card" ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'" >'."\r\n";
                    $page_content .= "\t\t\t".'<div class="item item-text-wrap"><pre>{{ item | json }}</pre></div>'."\r\n";
                    $page_content .= "\t\t\t".'</div>'."\r\n";
                    break;
                    // TODO:  ----------|-- TABLE
                case 'table':
                    $page_content .= "\r\n";
                    $page_content .= "\t\t\t".'<ion-list class="card">'."\r\n";
                    $page_content .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $page_content .= "\t\t\t\t".'</div>'."\r\n";
                    $page_content .= "\t\t\t".'</ion-list>'."\r\n";
                    $page_content .= "\t\t".'<div class="card">'."\r\n";

                    $page_content .= "\r\n";
                    $page_content .= "\t\t\t".'<table class="table table-striped">'."\r\n";
                    $page_content .= "\t\t\t\t".'<tbody>'."\r\n";
                    if($focus_search == 'scroll')
                    {
                        $page_content .= "\t\t\t\t\t".'<tr ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null"  >'."\r\n";

                    } elseif($focus_search == 'search')
                    {
                        $page_content .= "\t\t\t\t\t".'<tr ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results"  >'."\r\n";
                    }

                    $page_content .= $page_list_content_table;
                    $page_content .= "\t\t\t\t\t".'</tr>'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<tr ng-if="results.length == 0" >'."\r\n";
                    $page_content .= "\t\t\t\t\t\t".'<td>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</td>'."\r\n";
                    $page_content .= "\t\t\t\t\t".'</tr>'."\r\n";
                    $page_content .= "\t\t\t\t".'</tbody>'."\r\n";
                    $page_content .= "\t\t\t".'</table>'."\r\n";
                    $page_content .= "\r\n";
                    if($focus_search == 'scroll')
                    {
                        $page_content .= "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                        $page_content .= "\t\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                        $page_content .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    }
                    $page_content .= "\r\n\r\n";
                    $page_content .= "\t\t".'</div>'."\r\n";
                    $page_content .= "\r\n";
                    break;
                    // TODO:  ----------|-- DICTIONARY
                case 'dictionary':
                    if($_SESSION['PROJECT']['menu']['type'] == 'tabs')
                    {
                        //$title_search = '<div class="bar bar-header item-input-inset"><label class="item-input-wrapper"><i class="icon ion-ios-search placeholder-icon"></i><input ng-model="$root.filter_' . $tables_prefix . 's" type="search" placeholder="' . htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']) . '"></label></div>';
                        $title_search = '<div class="bar-header-inset transparent"><label class="item-input-wrapper transparent"><i class="icon ion-ios-search placeholder-icon"></i><input class="transparent" ng-model="$root.filter_'.$tables_prefix.'s" type="search" placeholder="'.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'"></label></div>';

                        $current_page['page'][0]['title'] = $title_search;

                        $page_content = "<!--\t\t\t"."\r\n";
                        $page_content .= "\t\t\t".'<div class="item-input-inset">'."\r\n";
                        $page_content .= "\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $page_content .= "\t\t\t\t".'<i class="icon ion-ios-search placeholder-icon"></i>'."\r\n";
                        $page_content .= "\t\t\t\t".'<input ng-model="$root.filter_'.$tables_prefix.'s" type="search" placeholder="'.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'"></label>'."\r\n";
                        $page_content .= "\t\t\t".'</div>'."\r\n";
                        $page_content .= "-->\t\t\t"."\r\n";
                    } else
                    {
                        $page_content = "\t\t\t"."\r\n";
                        $page_content .= "\t\t\t".'<div class="item-input-inset">'."\r\n";
                        $page_content .= "\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $page_content .= "\t\t\t\t".'<i class="icon ion-ios-search placeholder-icon"></i>'."\r\n";
                        $page_content .= "\t\t\t\t".'<input ng-model="$root.filter_'.$tables_prefix.'s" type="search" placeholder="'.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'"></label>'."\r\n";
                        $page_content .= "\t\t\t".'</div>'."\r\n";
                        $current_page['page'][0]['title'] = $new_tables['tables'][$tables_prefix]['title'];
                    }
                    $page_content .= "\t\t\t"."\r\n";
                    $page_content .= "\t\t\t".'<div class="list">'."\r\n";
                    $page_content .= "\t\t\t\t".'<div class="item item-text-wrap noborder" ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:20:0 as results" >'."\r\n";
                    if(isset($var_item_title))
                    {
                        $page_content .= "\t\t\t\t\t".'<strong ng-bind-html="item.'.$var_item_title.' | to_trusted"></strong>, '."\r\n";
                    }
                    for($ix = 0; $ix < 20; $ix++)
                    {
                        if(isset($list_cols_item_text[$ix]))
                        {
                            if(preg_match("/\[txt\]/",$list_cols_item_text_label[$ix]))
                            {
                                $page_content .= "\t\t\t\t\t".'<span>'.str_replace("[txt]",'{{item.'.$list_cols_item_text[$ix].'}}',$list_cols_item_text_label[$ix]).'</span>'."\r\n";
                            } else
                            {
                                $page_content .= "\t\t\t\t\t".'<span>'.$list_cols_item_text_label[$ix].'{{item.'.$list_cols_item_text[$ix].'}}'.'</span>'."\r\n";
                            }
                        }
                    }
                    $page_content .= "\t\t\t\t".'</div>'."\r\n";
                    $page_content .= "\t\t\t\t".'<div class="item noborder" ng-if="results.length == 0" >'."\r\n";
                    $page_content .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</p>'."\r\n";
                    $page_content .= "\t\t\t\t".'</div>'."\r\n";
                    $page_content .= "\t\t\t".'</div>'."\r\n";
                    $page_content .= "\r\n";
                    break;
                    // TODO:  ----------|-- AVATAR
                case 'avatar':
                    if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
                    {
                        $icon_pos = '-right';
                    } else
                    {
                        $icon_pos = '';
                    }
                    $_code_search = $_code_listing = $_code_infinite_scroll = $_code_search_not_found = null;
                    $_code_search .= "\t\t\t".'<!-- code search -->'."\r\n";
                    $_code_search .= "\t\t\t".'<ion-list class="card list" '.$direction.'>'."\r\n";
                    $_code_search .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";

                    if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
                    {
                        $_code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                        $_code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                        $_code_search .= "\t\t\t\t".'<div class="item item-input-inset">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'</label>'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<button class="button button-small" barcode-scanner barcode-text="filter_'.$tables_prefix.'s"><i class="ion-qr-scanner"></i></button>'."\r\n";
                        $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                        $_code_search .= "\t\t\t".''."\r\n\r\n";
                    }


                    $_code_listing .= "\r\n";
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $text_color = '';
                    if(($item_color != 'light') && ($item_color != 'stable'))
                    {
                        $text_color = 'light';
                    }
                    $_code_listing .= "\t\t\t".'<div class="list animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";

                    if($focus_search == 'scroll')
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="" ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null" >'."\r\n";
                    } elseif($focus_search == 'search')
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="" ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results" >'."\r\n";
                    }

                    $_code_listing .= "\t\t\t\t\t".'<div class="card '.$item_color.'-bg '.$text_color.' ink ink-dark">'."\r\n";
                    $_code_listing .= "\t\t\t\t\t\t".'<a class="item item-avatar item-text-wrap" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}" >'."\r\n";
                    if(isset($var_item_images))
                    {
                        if($_SESSION['PROJECT']['app']['lazyload'] == true)
                        {
                            $_code_listing .= "\t\t\t\t\t\t\t".'<img alt="" class="avatar" image-lazy-loader="lines" image-lazy-src="{{item.'.$var_item_images.'}}" />'."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t\t".'<img alt="" class="avatar" ng-src="{{item.'.$var_item_images.'}}" />'."\r\n";
                        }
                    }
                    if(isset($var_item_title))
                    {
                        $_code_listing .= "\t\t\t\t\t\t\t".'<h2 '.$direction.' ng-bind-html="item.'.$var_item_title.' | to_trusted"></h2>'."\r\n";
                    } else
                    {
                        $_code_listing .= "\t\t\t\t\t\t\t".'<h2 '.$direction.'>Type `heading-1`???</h2>';
                    }
                    if(isset($var_item_text))
                    {
                        $_code_listing .= "\t\t\t\t\t\t\t\t".'<p '.$direction.'>'."\r\n";
                        if(preg_match("/\[txt\]/",$var_item_text_label))
                        {
                            $_code_listing .= "\t\t\t\t\t\t\t\t".str_replace("[txt]",'{{item.'.$var_item_text.'}}',$var_item_text_label)."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t\t\t".$var_item_text_label.'{{item.'.$var_item_text.'}}'."\r\n";
                        }
                        $_code_listing .= "\t\t\t\t\t\t\t\t".'</p>'."\r\n";
                    }
                    $_code_listing .= "\t\t\t\t\t\t".'</a>'."\r\n";
                    $_code_listing .= "\t\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\r\n\r\n";
                    $_code_infinite_scroll = null;
                    $_code_infinite_scroll .= "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\r\n\r\n";
                    $_code_search_not_found = null;
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'<div class="item" ng-if="results.length == 0" >'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</p>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\r\n\r\n";
                    $page_content .= $_code_search;
                    $page_content .= $_code_listing;
                    if($focus_search == 'scroll')
                    {
                        $page_content .= $_code_infinite_scroll;
                    }
                    $page_content .= $_code_search_not_found;
                    $current_page['page'][0]['table-code']['search'] = $_code_search;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    $current_page['page'][0]['table-code']['infinite-scroll'] = $_code_infinite_scroll;
                    $current_page['page'][0]['table-code']['search-result'] = $_code_search_not_found;
                    break;
                    // TODO:  ----------|-- SHOWCASE
                case 'showcase':
                    if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
                    {
                        $icon_pos = '-right';
                    } else
                    {
                        $icon_pos = '';
                    }
                    $_code_search = $_code_listing = $_code_infinite_scroll = $_code_search_not_found = null;
                    $_code_search .= "\t\t\t".'<!-- code search -->'."\r\n";
                    $_code_search .= "\t\t\t".'<ion-list class="card list" '.$direction.'>'."\r\n";
                    $_code_search .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                    $_code_listing .= "\r\n";
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";


                    if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
                    {
                        $_code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                        $_code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                        $_code_search .= "\t\t\t\t".'<div class="item item-input-inset">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'</label>'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<button class="button button-small" barcode-scanner barcode-text="filter_'.$tables_prefix.'s"><i class="ion-qr-scanner"></i></button>'."\r\n";
                        $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                        $_code_search .= "\t\t\t".''."\r\n\r\n";
                    }

                    $text_color = '';
                    if(($item_color != 'light') && ($item_color != 'stable'))
                    {
                        $text_color = 'light';
                    }
                    $_code_listing .= "\t\t\t".'<div class="animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";

                    if($focus_search == 'scroll')
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="list card" ng-repeat="item in '.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null" >'."\r\n";
                    } elseif($focus_search == 'search')
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="list card" ng-repeat="item in data_'.$tables_prefix.'s '.$filter_1st_param.'| filter:filter_'.$tables_prefix.'s | limitTo:'.$items_max.':0 as results" >'."\r\n";
                    }


                    if(isset($list_cols_item_images[0]))
                    {
                        $_code_listing .= "\t\t\t\t".'<a class="item item-avatar item-text-wrap" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";
                        if($_SESSION['PROJECT']['app']['lazyload'] == true)
                        {
                            $_code_listing .= "\t\t\t\t\t\t\t\t".'<img alt="" image-lazy-loader="lines" image-lazy-src="{{item.'.$list_cols_item_images[0].'}}" />'."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t\t\t".'<img alt="" ng-src="{{item.'.$list_cols_item_images[0].'}}" />'."\r\n";
                        }
                    } else
                    {
                        $_code_listing .= "\t\t\t\t".'<a class="item item-text-wrap" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";
                    }

                    if(isset($var_item_title))
                    {
                        $_code_listing .= "\t\t\t\t\t\t\t".'<h2 '.$direction.' ng-bind-html="item.'.$var_item_title.' | to_trusted"></h2>'."\r\n";
                    } else
                    {
                        $_code_listing .= "\t\t\t\t\t\t\t".'<h2 '.$direction.'>?</h2>';
                    }
                    if(isset($list_cols_item_text[0]))
                    {
                        $_code_listing .= "\t\t\t\t\t\t\t\t".'<p '.$direction.'>'."\r\n";
                        if(preg_match("/\[txt\]/",$list_cols_item_text_label[0]))
                        {
                            $_code_listing .= "\t\t\t\t\t\t\t\t".str_replace("[txt]",'{{item.'.$list_cols_item_text[0].'}}',$list_cols_item_text_label[0])."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t\t\t".$list_cols_item_text_label[0].'{{item.'.$list_cols_item_text[0].'}}'."\r\n";
                        }
                        $_code_listing .= "\t\t\t\t\t\t\t\t".'</p>'."\r\n";
                    }
                    $_code_listing .= "\t\t\t\t".'</a>'."\r\n";
                    $_code_listing .= "\t\t\t\t\t".'<div class="item item-body">'."\r\n";
                    if(isset($list_cols_item_images[1]))
                    {
                        if($_SESSION['PROJECT']['app']['lazyload'] == true)
                        {
                            $_code_listing .= "\t\t\t\t\t\t\t\t".'<img alt="" image-lazy-loader="lines" image-lazy-src="{{item.'.$list_cols_item_images[1].'}}" />'."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t\t\t".'<img alt="" ng-src="{{item.'.$list_cols_item_images[1].'}}" />'."\r\n";
                        }
                    }
                    if(isset($var_item_html))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<div class="to_trusted" ng-bind-html="item.'.$var_item_html.' | limitTo:150:0 | to_trusted"></div>'."\r\n";
                    }
                    $_code_listing .= "\t\t\t\t\t".'<p>'."\r\n";
                    for($ix = 1; $ix < 5; $ix++)
                    {
                        if(isset($list_cols_item_text[$ix]))
                        {
                            if(preg_match("/\[txt\]/",$list_cols_item_text_label[$ix]))
                            {
                                $_code_listing .= "\t\t\t\t\t\t\t\t".'<span class="subdued">'.str_replace("[txt]",'{{item.'.$list_cols_item_text[$ix].'}}',$list_cols_item_text_label[$ix]).'</span>'."\r\n";
                            } else
                            {
                                $_code_listing .= "\t\t\t\t\t\t\t\t".'<span class="subdued">'.$list_cols_item_text_label[$ix].'{{item.'.$list_cols_item_text[$ix].'}}'.'</span>'."\r\n";
                            }
                        }
                    }
                    $_code_listing .= "\t\t\t\t\t".'</p>'."\r\n";
                    $_code_listing .= "\t\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\r\n\r\n";
                    $_code_infinite_scroll = null;
                    $_code_infinite_scroll .= "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\r\n\r\n";
                    $_code_search_not_found = null;
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'<div class="item" ng-if="results.length == 0" >'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</p>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\r\n\r\n";
                    $page_content .= $_code_search;
                    $page_content .= $_code_listing;
                    if($focus_search == 'scroll')
                    {
                        $page_content .= $_code_infinite_scroll;
                    }
                    $page_content .= $_code_search_not_found;
                    $current_page['page'][0]['table-code']['search'] = $_code_search;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    $current_page['page'][0]['table-code']['infinite-scroll'] = $_code_infinite_scroll;
                    $current_page['page'][0]['table-code']['search-result'] = $_code_search_not_found;
                    break;
                    // TODO:  ----------|-- YOUTUBE
                case 'youtube':
                    $_code_search = null;
                    $_code_search .= "\t\t\t".'<!-- code search -->'."\r\n";
                    $_code_search .= "\t\t\t".'<ion-list class="list" '.$direction.'>'."\r\n";
                    $_code_search .= "\t\t\t\t".'<div class="item item-input">'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<i class="icon ion-search placeholder-icon"></i>'."\r\n";
                    $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                    $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                    $_code_search .= "\r\n\r\n";

                    if($_SESSION['PROJECT']['cordova_plugin']['barcodescanner']['enable'] == true)
                    {
                        $_code_search = "\t\t\t".'<!-- code search -->'."\r\n";
                        $_code_search .= "\t\t\t".'<div class="list card animate-'.$new_tables['tables'][$tables_prefix]['motions'].'" '.$direction.'>'."\r\n";
                        $_code_search .= "\t\t\t\t".'<div class="item item-input-inset">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<label class="item-input-wrapper">'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<input type="search" ng-model="filter_'.$tables_prefix.'s" placeholder="{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['search']).'\' | translate }}" aria-label="filter '.$tables_prefix.'s" />'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'</label>'."\r\n";
                        $_code_search .= "\t\t\t\t\t".'<button class="button button-small" barcode-scanner barcode-text="filter_'.$tables_prefix.'s"><i class="ion-qr-scanner"></i></button>'."\r\n";
                        $_code_search .= "\t\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'</div>'."\r\n";
                        $_code_search .= "\t\t\t".'<!-- ./code search -->'."\r\n";
                        $_code_search .= "\t\t\t".''."\r\n\r\n";
                    }

                    $_code_listing = null;
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";
                    if($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
                    {
                        $icon_pos = 'right';
                    } else
                    {
                        $icon_pos = 'left';
                    }
                    $link = '#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}';

                    if(isset($var_item_images))
                    {
                        $_code_listing .= "\t\t\t\t".'<a class="item item-thumbnail-'.$icon_pos.' item-text-wrap" ng-repeat="item in '.$tables_prefix.'s | filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null" href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";
                        if($_SESSION['PROJECT']['app']['lazyload'] == true)
                        {
                            $_code_listing .= "\t\t\t\t\t".'<img alt="" class="full-image" image-lazy-loader="lines" image-lazy-src="{{item.'.$var_item_images.'}}"   />'."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t".'<img alt="" class="full-image" ng-src="{{item.'.$var_item_images.'}}" />'."\r\n";
                        }
                    } else
                    {
                        $_code_listing .= "\t\t\t\t".'<a class="item item-text-wrap" ng-repeat="item in '.$tables_prefix.'s | filter:filter_'.$tables_prefix.'s as results" ng-init="$last ? fireEvent() : null" href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}">'."\r\n";
                    }

                    if(isset($var_item_youtube))
                    {
                        $_code_listing .= "\t\t\t\t".'<div class="full-image embed-responsive embed-responsive-16by9"><iframe width="100%" src="{{ \'https://www.youtube.com/embed/\' + item.'.$var_item_youtube.'}}" frameborder="0" allowfullscreen></iframe></div>'."\r\n";
                    }

                    if(isset($var_item_title))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<h3 class="" '.$direction.' ng-bind-html="item.'.$var_item_title.' | to_trusted"></h3>'."\r\n";
                    }
                    if(isset($var_item_text))
                    {
                        $_code_listing .= "\t\t\t\t\t\t".'<p '.$direction.'>'."\r\n";
                        if(preg_match("/\[txt\]/",$var_item_text_label))
                        {
                            $_code_listing .= "\t\t\t\t\t\t".str_replace("[txt]",'{{item.'.$var_item_text.'}}',$var_item_text_label)."\r\n";
                        } else
                        {
                            $_code_listing .= "\t\t\t\t\t\t".$var_item_text_label.'{{item.'.$var_item_text.'}}'."\r\n";
                        }
                        $_code_listing .= "\t\t\t\t\t\t".'</p>'."\r\n";
                    }

                    if(isset($var_item_html))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<div class="to_trusted" ng-bind-html="item.'.$var_item_html.' | to_trusted"></div>'."\r\n";
                    }

                    if(isset($var_item_rating))
                    {
                        $_code_listing .= "\t\t\t\t\t".'<p><rating ng-model="item.'.$var_item_rating.'" max="rating.max"></rating></p>'."\r\n";
                    }
                    $_code_listing .= "\t\t\t\t".'</a>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\r\n\r\n";


                    $_code_infinite_scroll = null;
                    $_code_infinite_scroll .= "\t\t\t".'<!-- code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t\t".'<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_infinite_scroll .= "\t\t\t".'<!-- ./code infinite scroll -->'."\r\n";
                    $_code_infinite_scroll .= "\r\n\r\n";


                    $_code_search_not_found = null;
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<ion-list class="list">'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'<div class="item" ng-if="results.length == 0" >'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t\t".'<p>{{ \''.htmlentities($new_tables['tables'][$tables_prefix]['languages']['no_result_found']).'\' | translate }}</p>'."\r\n";
                    $_code_search_not_found .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'</ion-list>'."\r\n";
                    $_code_search_not_found .= "\t\t\t".'<!-- code search result not found -->'."\r\n";
                    $_code_search_not_found .= "\r\n\r\n";
                    $page_content .= $_code_search;
                    $page_content .= $_code_listing;
                    $page_content .= $_code_infinite_scroll;
                    $page_content .= $_code_search_not_found;
                    $current_page['page'][0]['table-code']['search'] = $_code_search;
                    $current_page['page'][0]['table-code']['listing'] = $_code_listing;
                    $current_page['page'][0]['table-code']['infinite-scroll'] = $_code_infinite_scroll;
                    $current_page['page'][0]['table-code']['search-result'] = $_code_search_not_found;
                    break;

                    // TODO:  ----------|-- CHART-LINE
                case 'chart-line':
                    $_code_listing = null;
                    $_code_listing .= "\r\n\r\n";
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";
                    $_code_listing .= "\t\t\t\t".'<div class="item item-body">'."\r\n";
                    $_code_listing .= "\t\t\t\t\t".'<canvas id="chart-'.$tables_prefix.'" class="chart chart-line" chart-data="chart_data_'.$tables_prefix.'s" chart-labels="chart_labels_'.$tables_prefix.'s" chart-series="chart_series_'.$tables_prefix.'s"></canvas>';
                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\r\n\r\n";
                    $page_content .= $_code_listing;


                    $page_content_js = "\r\n";
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n".'// series data';
                    $page_content_js .= "\r\n".'$scope.chart_data_'.$tables_prefix.'s = [];';
                    $page_content_js .= "\r\n".'angular.forEach(data_'.$tables_prefix.'s, function(child) {';
                    $page_content_js .= "\r\n\t".'var new_item = [];';
                    $page_content_js .= "\r\n\t".'var indeks = 0;';
                    $page_content_js .= "\r\n\t".'angular.forEach(child, function(v){';
                    $page_content_js .= "\r\n\t\t".'if ((indeks !== 0) && (indeks !== 1)){';
                    $page_content_js .= "\r\n\t\t\t".'new_item.push(v);';
                    $page_content_js .= "\r\n\t\t".'}';
                    $page_content_js .= "\r\n\t\t".'indeks++;';
                    $page_content_js .= "\r\n\t".'});';
                    $page_content_js .= "\r\n\t".'$scope.chart_data_'.$tables_prefix.'s.push(new_item);';
                    $page_content_js .= "\r\n".'});';
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n".'// x axis labels';
                    $page_content_js .= "\r\n".'$scope.chart_labels_'.$tables_prefix.'s = [];';
                    $page_content_js .= "\r\n\t".'var indeks = 0;';
                    $page_content_js .= "\r\n\t".'angular.forEach(data_'.$tables_prefix.'s[0],function(v,l){';
                    $page_content_js .= "\r\n\t\t".'if ((indeks !== 0) && (indeks !== 1)) {';
                    $page_content_js .= "\r\n\t\t\t".'$scope.chart_labels_'.$tables_prefix.'s.push(l);';
                    $page_content_js .= "\r\n\t\t".'}';
                    $page_content_js .= "\r\n\t\t".'indeks++;';
                    $page_content_js .= "\r\n\t".'});';
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n".'// series labels';
                    $page_content_js .= "\r\n".'$scope.chart_series_'.$tables_prefix.'s = [];';
                    $page_content_js .= "\r\n".'angular.forEach(data_'.$tables_prefix.'s, function(child) {';
                    $page_content_js .= "\r\n\t".'var new_item = [];';
                    $page_content_js .= "\r\n\t".'var indeks = 0;';
                    $page_content_js .= "\r\n\t".'angular.forEach(child, function(v){';
                    $page_content_js .= "\r\n\t\t".'if (indeks === 1){';
                    $page_content_js .= "\r\n\t\t\t".'new_item.push(v);';
                    $page_content_js .= "\r\n\t\t".'}';
                    $page_content_js .= "\r\n\t\t".'indeks++;';
                    $page_content_js .= "\r\n\t".'});';
                    $page_content_js .= "\r\n\t".'$scope.chart_series_'.$tables_prefix.'s.push(new_item);';
                    $page_content_js .= "\r\n".'});';
                    break;


                    // TODO:  ----------|-- CHART-BAR
                case 'chart-bar':
                    $_code_listing = null;
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";
                    $_code_listing .= "\t\t\t\t".'<div class="item item-body chart-container">'."\r\n";
                    $_code_listing .= "\t\t\t\t\t".'<canvas id="chart-'.$tables_prefix.'" class="chart chart-bar" chart-data="chart_data_'.$tables_prefix.'s" chart-labels="chart_labels_'.$tables_prefix.'s" chart-series="chart_series_'.$tables_prefix.'s"></canvas>';
                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\r\n\r\n";
                    $page_content .= $_code_listing;


                    $page_content_js = "\r\n";
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n".'// series data';
                    $page_content_js .= "\r\n".'$scope.chart_data_'.$tables_prefix.'s = [];';
                    $page_content_js .= "\r\n".'angular.forEach(data_'.$tables_prefix.'s, function(child) {';
                    $page_content_js .= "\r\n\t".'var new_item = [];';
                    $page_content_js .= "\r\n\t".'var indeks = 0;';
                    $page_content_js .= "\r\n\t".'angular.forEach(child, function(v){';
                    $page_content_js .= "\r\n\t\t".'if ((indeks !== 0) && (indeks !== 1)){';
                    $page_content_js .= "\r\n\t\t\t".'new_item.push(v);';
                    $page_content_js .= "\r\n\t\t".'}';
                    $page_content_js .= "\r\n\t\t".'indeks++;';
                    $page_content_js .= "\r\n\t".'});';
                    $page_content_js .= "\r\n\t".'$scope.chart_data_'.$tables_prefix.'s.push(new_item);';
                    $page_content_js .= "\r\n".'});';
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n".'// x axis labels';
                    $page_content_js .= "\r\n".'$scope.chart_labels_'.$tables_prefix.'s = [];';
                    $page_content_js .= "\r\n\t".'var indeks = 0;';
                    $page_content_js .= "\r\n\t".'angular.forEach(data_'.$tables_prefix.'s[0],function(v,l){';
                    $page_content_js .= "\r\n\t\t".'if ((indeks !== 0) && (indeks !== 1)) {';
                    $page_content_js .= "\r\n\t\t\t".'$scope.chart_labels_'.$tables_prefix.'s.push(l);';
                    $page_content_js .= "\r\n\t\t".'}';
                    $page_content_js .= "\r\n\t\t".'indeks++;';
                    $page_content_js .= "\r\n\t".'});';
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n".'// series labels';
                    $page_content_js .= "\r\n".'$scope.chart_series_'.$tables_prefix.'s = [];';
                    $page_content_js .= "\r\n".'angular.forEach(data_'.$tables_prefix.'s, function(child) {';
                    $page_content_js .= "\r\n\t".'var new_item = [];';
                    $page_content_js .= "\r\n\t".'var indeks = 0;';
                    $page_content_js .= "\r\n\t".'angular.forEach(child, function(v){';
                    $page_content_js .= "\r\n\t\t".'if (indeks === 1){';
                    $page_content_js .= "\r\n\t\t\t".'new_item.push(v);';
                    $page_content_js .= "\r\n\t\t".'}';
                    $page_content_js .= "\r\n\t\t".'indeks++;';
                    $page_content_js .= "\r\n\t".'});';
                    $page_content_js .= "\r\n\t".'$scope.chart_series_'.$tables_prefix.'s.push(new_item);';
                    $page_content_js .= "\r\n".'});';


                    break;
                    // TODO:  ----------|-- CHART-DOUGHNUT
                case 'chart-doughnut':
                    $_code_listing = null;
                    $_code_listing .= "\t\t\t".'<!-- code listing -->'."\r\n";
                    $_code_listing .= "\t\t\t".'<div class="list animate-'.$new_tables['tables'][$tables_prefix]['motions'].'">'."\r\n";
                    $_code_listing .= "\t\t\t\t".'<div class="item item-body chart-container" >'."\r\n";

                    $_code_listing .= "\t\t\t\t\t".'<canvas id="chart-'.$tables_prefix.'" class="chart chart-doughnut" chart-data="chart_data_'.$tables_prefix.'s" chart-labels="chart_labels_'.$tables_prefix.'s" chart-series="chart_series_'.$tables_prefix.'s"></canvas>';

                    $_code_listing .= "\t\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'</div>'."\r\n";
                    $_code_listing .= "\t\t\t".'<!-- ./code listing -->'."\r\n";
                    $_code_listing .= "\r\n\r\n";
                    $page_content .= $_code_listing;


                    $page_content_js = "\r\n";
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n".'// series data';
                    $page_content_js .= "\r\n".'$scope.chart_data_'.$tables_prefix.'s = [];';
                    $page_content_js .= "\r\n".'angular.forEach(data_'.$tables_prefix.'s, function(child) {';
                    $page_content_js .= "\r\n\t".'var new_item = [];';
                    $page_content_js .= "\r\n\t".'var indeks = 0;';
                    $page_content_js .= "\r\n\t".'angular.forEach(child, function(v){';
                    $page_content_js .= "\r\n\t\t".'if ((indeks !== 0) && (indeks !== 1)){';
                    $page_content_js .= "\r\n\t\t\t".'new_item.push(v);';
                    $page_content_js .= "\r\n\t\t".'}';
                    $page_content_js .= "\r\n\t\t".'indeks++;';
                    $page_content_js .= "\r\n\t".'});';
                    $page_content_js .= "\r\n\t".'$scope.chart_data_'.$tables_prefix.'s.push(new_item);';
                    $page_content_js .= "\r\n".'});';
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n".'// x axis labels';
                    $page_content_js .= "\r\n".'$scope.chart_labels_'.$tables_prefix.'s = [];';
                    $page_content_js .= "\r\n\t".'var indeks = 0;';
                    $page_content_js .= "\r\n\t".'angular.forEach(data_'.$tables_prefix.'s[0],function(v,l){';
                    $page_content_js .= "\r\n\t\t".'if ((indeks !== 0) && (indeks !== 1)) {';
                    $page_content_js .= "\r\n\t\t\t".'$scope.chart_labels_'.$tables_prefix.'s.push(l);';
                    $page_content_js .= "\r\n\t\t".'}';
                    $page_content_js .= "\r\n\t\t".'indeks++;';
                    $page_content_js .= "\r\n\t".'});';
                    $page_content_js .= "\r\n";
                    $page_content_js .= "\r\n".'// series labels';
                    $page_content_js .= "\r\n".'$scope.chart_series_'.$tables_prefix.'s = [];';
                    $page_content_js .= "\r\n".'angular.forEach(data_'.$tables_prefix.'s, function(child) {';
                    $page_content_js .= "\r\n\t".'var new_item = [];';
                    $page_content_js .= "\r\n\t".'var indeks = 0;';
                    $page_content_js .= "\r\n\t".'angular.forEach(child, function(v){';
                    $page_content_js .= "\r\n\t\t".'if (indeks === 1){';
                    $page_content_js .= "\r\n\t\t\t".'new_item.push(v);';
                    $page_content_js .= "\r\n\t\t".'}';
                    $page_content_js .= "\r\n\t\t".'indeks++;';
                    $page_content_js .= "\r\n\t".'});';
                    $page_content_js .= "\r\n\t".'$scope.chart_series_'.$tables_prefix.'s.push(new_item);';
                    $page_content_js .= "\r\n".'});';


                    break;


            }


            $page_content .= "\r\n";
            // TODO: ------|-- PREPARE DINAMIC URL
            $current_page['page'][0]['query'] = null;

            $default_value = '';
            if($new_tables['tables'][$tables_prefix]['db_url_dinamic'] == true)
            {
                $db_param = parse_url($new_tables['tables'][$tables_prefix]['db_url']);
                $param = explode('=',$db_param['query']);
                if(isset($param[0]))
                {
                    if(strlen($param[0]) > 0)
                    {
                        $current_page['page'][0]['query'] = array($param[0]);
                    } else
                    {
                    }
                }
                if(isset($param[1]))
                {
                    $_default_value = explode("&",$param[1]);
                    $default_value = $_default_value[0];
                }
            }

            $current_page['page'][0]['query_value'] = $default_value;
            $current_page['page'][0]['table-code']['url_detail'] = '#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{item.'.$uid.'}}';
            $current_page['page'][0]['table-code']['url_list'] = '#/'.$subpage_path.'/'.$new_tables['tables'][$tables_prefix]['parent'];
            // TODO: ------|-- PREPARE RELATION
            if($new_tables['tables'][$tables_prefix]['relation_to'] != 'none')
            {
                $page_target = $new_tables['tables'][$tables_prefix]['relation_to'];
                $tables_prefix = str2var($_POST['tables']['title']);
                $search_target = '#/'.$subpage_path.'/'.$tables_prefix.'_singles/';
                $replace_with = '#/'.$subpage_path.'/'.$page_target.'/';
                $page_content = str_replace($search_target,$replace_with,$page_content);
                $current_page['page'][0]['table-code']['listing'] = str_replace($search_target,$replace_with,$current_page['page'][0]['table-code']['listing']);
                $current_page['page'][0]['table-code']['url_detail'] = str_replace($search_target,$replace_with,$current_page['page'][0]['table-code']['url_detail']);
            }
            $page_content_js .= "//debug: all data\r\n";
            $page_content_js .= "//console.log(data_".$tables_prefix."s);\r\n";
            $page_content_js .= '$ionicConfig.backButton.text("");'."\r\n";
            // TODO: ------|-- PREPARE CONFIG
            $current_page['page'][0]['content'] = $page_content;
            $current_page['page'][0]['for'] = 'table-list';
            $current_page['page'][0]['last_edit_by'] = 'table ('.$tables_prefix.')';
            $current_page['page'][0]['cache'] = 'false';
            $current_page['page'][0]['variables'] = $page_list_var;
            $current_page['page'][0]['img_hero'] = '';
            $current_page['page'][0]['priority'] = 'low';
            $current_page['page'][0]['js'] = $page_content_js;
            $current_page['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'];
            $current_page['page'][0]['builder_link'] = '';
            $current_page['page'][0]['version'] = 'Upd.'.date('ymdhi');

            if(isset($new_tables['tables'][$tables_prefix]['db_url_dinamic']))
            {
                $current_page['page'][0]['db_url_dinamic'] = $new_tables['tables'][$tables_prefix]['db_url_dinamic'];
            }
            if(!isset($current_page['page'][0]['button_up']))
            {
                if($_SESSION['PROJECT']['menu']['type'] == 'side_menus')
                {
                    $current_page['page'][0]['button_up'] = 'bottom-right';
                } else
                {
                    $current_page['page'][0]['button_up'] = 'none';
                }
            }
            $is_lock = false;
            $lock_path = $overwrite_files;
            if(file_exists($lock_path))
            {
                $lock_data = json_decode(file_get_contents($lock_path),true);
                $is_lock = $lock_data['page'][0]['lock'];
                $current_page['page'][0]['img_bg'] = $lock_data['page'][0]['img_bg'];
                $current_page['page'][0]['img_hero'] = $lock_data['page'][0]['img_hero'];
            }
            // TODO: ------|-- SAVE PAGE LISTING
            if($is_lock == true)
            {
                $error_notice[] = 'Page <code>'.$tables_prefix.'</code> is <span class="fa fa-lock"></span> locked.';
            } else
            {
                file_put_contents($overwrite_files,json_encode($current_page));
            }
            // TODO: ------|-- SAVE PAGE BOOKMARK
            $create_cart_files = 'projects/'.$file_name.'/page.'.$tables_prefix.'_cart.json';
            $create_bookmark_files = 'projects/'.$file_name.'/page.'.$tables_prefix.'_bookmark.json';
            if(!isset($new_tables['tables'][$tables_prefix]['bookmarks']))
            {
                $new_tables['tables'][$tables_prefix]['bookmarks'] = 'none';
            }
            // TODO: ------|---- CONTENT CART
            if($new_tables['tables'][$tables_prefix]['bookmarks'] == 'cart')
            {

                $page_bookmark = null;
                $page_content = null;
                $product_images = $var_item_images;
                if($var_item_images == '')
                {
                    $product_images = 'none';
                }


                $cart_class = 'item-thumbnail-left';
                if($product_images == 'none')
                {
                    $cart_class = '';
                }
                $page_content .= "\r\n";
                $page_content .= "".'<!-- shopping cart -->'."\r\n";
                $page_content .= "\t".'<div ng-if="'.$tables_prefix.'_cart.length != 0">'."\r\n";

                $page_content .= "\t\t".'<!-- items -->'."\r\n";
                $page_content .= "\t\t".'<div class="list" ng-init="'.$tables_prefix.'_order={}" >'."\r\n";
                $page_content .= "\t\t\t".'<div class="card" ng-repeat="item in '.$tables_prefix.'_cart" ng-init="'.$tables_prefix.'_order[$index]=item">'."\r\n";
                $page_content .= "\t\t\t\t".'<div class="item '.$cart_class.' item-button-right noborder">'."\r\n";
                if($product_images != 'none')
                {
                    $page_content .= "\t\t\t\t\t".'<img ng-if="item.'.$product_images.'" ng-src="{{ item.'.$product_images.' }}" src="{{ item.'.$product_images.' }}"  />'."\r\n";
                }
                $page_content .= "\t\t\t\t\t".'<h2 class="" ng-bind-html="item.'.$var_item_title.' | to_trusted"></h2>'."\r\n";
                $page_content .= "\t\t\t\t\t".'<span>{{ item._sum | currency:"'.$new_tables['tables'][$tables_prefix]['currency-symbol'].'":2 }}</span>'."\r\n";
                $page_content .= "\t\t\t\t\t".'<input type="number" min="1" ng-change="updateDbVirtual()" ng-model="'.$tables_prefix.'_order[$index][\'_qty\']" />'."\r\n";
                $page_content .= "\t\t\t\t\t".'<button class="button button-small button-assertive button-outline" ng-click="removeDbVirtual'.ucwords($tables_prefix).'(item.'.$uid.')"><i class="icon ion-trash-a"></i></button>'."\r\n";
                $page_content .= "\t\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t".'</div>'."\r\n";
                $page_content .= "\t\t".'<!-- ./items -->'."\r\n";

                $page_content .= "\r\n";
                $page_content .= "\t\t".'<!-- totals -->'."\r\n";
                $page_content .= "\t\t".'<div class="list">'."\r\n";
                $page_content .= "\t\t\t".'<div class="item text-right">'."\r\n";
                $page_content .= "\t\t\t\t".'<h2>{{ '.$tables_prefix.'_cost | currency:"'.$new_tables['tables'][$tables_prefix]['currency-symbol'].'":2 }}</h2>'."\r\n";

                $page_content .= "\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t".'</div>'."\r\n";
                $page_content .= "\t\t".'<!-- ./totals -->'."\r\n";
                $page_content .= "\r\n";
                $page_content .= "\t\t".'<!-- buttons -->'."\r\n";
                $page_content .= "\t\t".'<div class="list">'."\r\n";
                $page_content .= "\t\t\t".'<div class="item tabs tabs-secondary tabs-icon-top tabs-stable">'."\r\n";
                $page_content .= "\t\t\t\t".'<a class="tab-item" ng-click="clearDbVirtual'.ucwords($tables_prefix).'();"><i class="icon ion-trash-a"></i> {{ \'Clear\' | translate }}</a>'."\r\n";
                $page_content .= "\t\t\t\t".'<a class="tab-item" ng-click="gotoCheckout()"><i class="icon ion-cash"></i> {{ \'Go To Checkout\' | translate }}</a>'."\r\n";
                $page_content .= "\t\t\t".'</div>'."\r\n";
                $page_content .= "\t\t".'</div>'."\r\n";
                $page_content .= "\t\t".'<!-- ./buttons -->'."\r\n";
                $page_content .= "\t".'</div>'."\r\n";
                $page_content .= "".'<!-- ./shopping cart -->'."\r\n";

                $page_content .= "\r\n";
                $page_content .= "\r\n";
                $page_content .= "".'<!-- no items -->'."\r\n";
                $page_content .= "\t".'<div class="'.$tables_prefix.'_cart padding text-center" ng-if="'.$tables_prefix.'_cart.length == 0">'."\r\n";
                $page_content .= "\t\t".'<i class="icon ion-ios-cart-outline"></i>'."\r\n";
                $page_content .= "\t\t".'<p>{{ \'There are no items\' | translate }}</p>'."\r\n";
                $page_content .= "\t".'</div>'."\r\n";
                $page_content .= "".'<!-- ./no items -->'."\r\n";

                $page_bookmark['page'][0]['cache'] = 'false';
                $page_bookmark['page'][0]['title'] = 'Shopping Cart';
                $page_bookmark['page'][0]['version'] = 'Upd.'.date('ymdhi');
                $page_bookmark['page'][0]['prefix'] = $tables_prefix.'_cart';
                $page_bookmark['page'][0]['for'] = 'table-bookmarks';
                $page_bookmark['page'][0]['last_edit_by'] = 'table ('.$tables_prefix.')';
                $page_bookmark['page'][0]['parent'] = $new_tables['tables'][$tables_prefix]['parent'];
                $page_bookmark['page'][0]['content'] = $page_content;
                $page_bookmark['page'][0]['priority'] = 'low';
                $page_bookmark['page'][0]['menu'] = 'false';
                $page_bookmark['page'][0]['builder_link'] = '';
                $page_bookmark['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'].'-custom';
                $page_bookmark['page'][0]['button_up'] = 'none';
                $page_bookmark['page'][0]['variables'] = '';
                $page_bookmark['page'][0]['css'] = '.'.$tables_prefix.'_cart{margin-top: 50%;}'."\r\n";
                $page_bookmark['page'][0]['css'] .= '.'.$tables_prefix.'_cart .icon:before{font-size: 72px;font-weight: 600;}'."\r\n";
                $page_bookmark['page'][0]['js'] = "\t".'$scope.gotoCheckout = function(){'."\r\n";
                $page_bookmark['page'][0]['js'] .= "\t\t".'alert("you must create payment gateway manual");'."\r\n";
                $page_bookmark['page'][0]['js'] .= "\t".'}'."\r\n";
                $page_bookmark['page'][0]['scroll'] = true;


                $is_cart_locked = false;
                if(file_exists($create_cart_files))
                {
                    $cart_lock_data = json_decode(file_get_contents($create_cart_files),true);
                    $is_cart_locked = $cart_lock_data['page'][0]['lock'];
                }
                if($is_cart_locked == true)
                {
                    $error_notice[] = 'Page <code>'.$tables_prefix.'_cart'.'</code> is <span class="fa fa-lock"></span> locked.';
                } else
                {
                    file_put_contents($create_cart_files,json_encode($page_bookmark));
                }


            }


            // TODO: ------|---- CONTENT BOOKMARK
            if($new_tables['tables'][$tables_prefix]['bookmarks'] == 'bookmark')
            {
                $page_bookmark = null;
                $page_content = null;
                //$page_content .= "\r\n\t\t" . '<pre> {{ ' . $tables_prefix . '_cart | json }} </pre>';
                $page_content .= "\r\n\r\n";
                $page_content .= "\r\n\t\t".'<ion-list class="list" ng-if="'.$tables_prefix.'_bookmark.length != 0">';
                $page_content .= "\r\n\t\t\t".'<ion-item class="item item-icon-left" type="item-avatar" ng-repeat="item in '.$tables_prefix.'_bookmark" ng-href="#/'.$subpage_path.'/'.$tables_prefix.'_singles/{{ item.'.$uid.' }}">';
                $page_content .= "\r\n\t\t\t\t".'<i class="icon ion-ios-bookmarks-outline"></i>';
                $page_content .= "\r\n\t\t\t\t".'<h2 class="" ng-bind-html="item.'.$var_item_title.' | to_trusted"></h2>';
                $page_content .= "\r\n\t\t\t\t".'<ion-option-button class="assertive-bg" ng-click="removeDbVirtual'.ucwords($tables_prefix).'(item.'.$uid.')"><i class="icon ion-trash-a"></i></ion-option-button>';
                $page_content .= "\r\n\t\t\t".'</ion-item>';
                $page_content .= "\t\t\t\t\t".'<ion-item class="item item-button">'."\r\n";
                $page_content .= "\t\t\t\t\t\t".'<button class="button button-small button-calm" ng-click="clearDbVirtual'.ucwords($tables_prefix).'();"><i class="icon ion-ios-refresh-outline"></i> {{ \'Clear\' | translate }}</button>'."\r\n";
                $page_content .= "\t\t\t\t\t".'</ion-item>'."\r\n";
                $page_content .= "\r\n\t\t".'</ion-list>';
                $page_content .= "\r\n\r\n";
                $page_content .= "\r\n\t\t".'<!-- no product -->';
                $page_content .= "\r\n\t\t".'<div class="'.$tables_prefix.'_bookmark padding text-center" ng-if="'.$tables_prefix.'_bookmark.length == 0">';
                $page_content .= "\r\n\t\t\t".'<i class="icon ion-ios-bookmarks-outline"></i>';
                $page_content .= "\t\t".'<p>{{ \'There are no items\' | translate }}</p>'."\r\n";
                $page_content .= "\r\n\t\t".'</div>';
                $page_content .= "\r\n\t\t".'<!-- no product -->';
                $page_bookmark['page'][0]['cache'] = 'false';
                $page_bookmark['page'][0]['title'] = 'Bookmarks of '.$current_page['page'][0]['title'];
                $page_bookmark['page'][0]['prefix'] = $tables_prefix.'_bookmark';
                $page_bookmark['page'][0]['for'] = 'table-bookmarks';
                $page_bookmark['page'][0]['last_edit_by'] = 'table ('.$tables_prefix.')';
                $page_bookmark['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
                $page_bookmark['page'][0]['content'] = $page_content;
                $page_bookmark['page'][0]['priority'] = 'low';
                $page_bookmark['page'][0]['version'] = 'Upd.'.date('ymdhi');
                $page_bookmark['page'][0]['menu'] = '';
                $page_bookmark['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'];
                $page_bookmark['page'][0]['builder_link'] = '';
                $page_bookmark['page'][0]['button_up'] = 'none';
                $page_bookmark['page'][0]['variables'] = '';
                $page_bookmark['page'][0]['css'] = '.'.$tables_prefix.'_bookmark{margin-top: 50%;}'."\r\n";
                $page_bookmark['page'][0]['css'] .= '.'.$tables_prefix.'_bookmark .icon:before{font-size:72px;font-weight: 600;}'."\r\n";
                $page_bookmark['page'][0]['scroll'] = true;

                $is_bookmark_locked = false;
                if(file_exists($create_bookmark_files))
                {
                    $bookmark_lock_data = json_decode(file_get_contents($create_bookmark_files),true);
                    $is_bookmark_locked = $bookmark_lock_data['page'][0]['lock'];
                }
                if($is_bookmark_locked == true)
                {
                    $error_notice[] = 'Page <code>'.$tables_prefix.'_bookmark'.'</code> is <span class="fa fa-lock"></span> locked.';
                } else
                {
                    file_put_contents($create_bookmark_files,json_encode($page_bookmark));
                }


            }
            if($new_tables['tables'][$tables_prefix]['bookmarks'] == 'none')
            {
                @unlink($create_cart_files);
                @unlink($create_bookmark_files);
            }
            if($new_tables['tables'][$tables_prefix]['bookmarks'] == 'bookmark')
            {
                @unlink($create_cart_files);
            }
            if($new_tables['tables'][$tables_prefix]['bookmarks'] == 'cart')
            {
                @unlink($create_bookmark_files);
            }
        }
        buildIonic($file_name);
        $_SESSION['PAGE_ERROR'] = $error_notice;
        header("Location: ./?page=tables&prefix=".$tables_prefix."&parent=".$new_tables['tables'][$tables_prefix]["parent"].'&err=null&notice=save');
        die();
    }
}
$tables_prefix = $_GET['prefix'];
$raw_tables['tables'][$tables_prefix]['title'] = null;
$raw_tables['tables'][$tables_prefix]['db_type'] = 'offline';
$raw_tables['tables'][$tables_prefix]['db_url'] = '';
$raw_tables['tables'][$tables_prefix]['db_url_single'] = '';
$raw_tables['tables'][$tables_prefix]['db_var'] = '';
$raw_tables['tables'][$tables_prefix]['languages']['no_result_found'] = 'No results found...!';
$raw_tables['tables'][$tables_prefix]['languages']['pull_for_refresh'] = 'Pull to refresh...';
$required = '';
// TODO: --------|-- EXAMPLE
if(isset($_GET['prefix']))
{
    $tables_prefix = $_GET['prefix'];
    $files = 'projects/'.$file_name.'/tables.'.$tables_prefix.'.json';
    if(file_exists($files))
    {
        $raw_tables = json_decode(file_get_contents($files),true);
    } else
    {
        $template_files = 'system/includes/example-tables/'.$tables_prefix.'.json';
        if(file_exists($template_files))
        {
            $raw_tables = json_decode(file_get_contents($template_files),true);
        }


        // TODO: --------|-- OPTION TEMPLATE FOR TABLE
        switch($tables_prefix)
        {
            case 'custom':
                $required .= '<div class="alert alert-danger">';
                $required .= '<h4>Custom Tables</h4>';
                $required .= '<p>If you have a JSON file on your website, you need only equate the name with the variable columns. Or If you already have a CMS that does not yet have a JSON Files, you can use the PHP + MySQL Features. Or if you\'re like using WordPress, you can use WordPress Plugin as back-end only.</p>';
                $required .= '<p>';
                $required .= '<a class="btn btn-sm btn-danger" target="_blank" href="./?page=z-php-sql-restapi-generator">PHP/SQL RESTAPI Generator</a>&nbsp;';
                $required .= 'or <a class="btn btn-sm btn-danger" target="_blank" href="./?page=z-wordpress-plugin-generator#help">WordPress + My App Plugin</a>&nbsp;';
                $required .= '</p>';
                $required .= '</div>';
                break;
            case 'tmp_wp_woocommerce':
                $z = 0;
                $var_name = 'tmp_wp_woocommerce';
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'ID';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'id';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'id';
                $z++;
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'Title';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'title.rendered';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'heading-1';
                $raw_tables['tables'][$var_name]['cols'][$z]['page_list'] = true;
                $raw_tables['tables'][$var_name]['cols'][$z]['page_detail'] = true;
                $z++;
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'Excerpt';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'excerpt.rendered';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'to_trusted';
                $z++;
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'Rating';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'x_metadata._wc_average_rating';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'rating';
                $raw_tables['tables'][$var_name]['cols'][$z]['page_list'] = true;
                $raw_tables['tables'][$var_name]['cols'][$z]['page_detail'] = true;
                $z++;
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'Content';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'content.rendered';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'to_trusted';
                $raw_tables['tables'][$var_name]['cols'][$z]['page_list'] = false;
                $raw_tables['tables'][$var_name]['cols'][$z]['page_detail'] = true;
                $z++;
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'Featured';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'x_featured_media';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'images';
                $raw_tables['tables'][$var_name]['cols'][$z]['page_list'] = true;
                $raw_tables['tables'][$var_name]['cols'][$z]['page_detail'] = true;
                $z++;
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'Tags : ';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'x_tags';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'text';
                $z++;
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'Categories : ';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'x_categories';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'text';
                $z++;
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'Date : ';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'x_date';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'text';
                $z++;
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'Author : ';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'x_author';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'text';
                $z++;
                $raw_tables["tables"][$var_name]["cols"][$z]["label"] = "Visibility : ";
                $raw_tables["tables"][$var_name]["cols"][$z]["title"] = "x_metadata._visibility";
                $raw_tables["tables"][$var_name]["cols"][$z]["type"] = "text";
                $z++;
                $raw_tables["tables"][$var_name]["cols"][$z]["label"] = "Stock Status : ";
                $raw_tables["tables"][$var_name]["cols"][$z]["title"] = "x_metadata._stock_status";
                $raw_tables["tables"][$var_name]["cols"][$z]["type"] = "text";
                $z++;
                $raw_tables["tables"][$var_name]["cols"][$z]["label"] = "Total Sales : ";
                $raw_tables["tables"][$var_name]["cols"][$z]["title"] = "x_metadata.total_sales";
                $raw_tables["tables"][$var_name]["cols"][$z]["type"] = "text";
                $z++;
                $raw_tables["tables"][$var_name]["cols"][$z]["label"] = "Regular Price : $[txt]/pcs";
                $raw_tables["tables"][$var_name]["cols"][$z]["title"] = "x_metadata._regular_price";
                $raw_tables["tables"][$var_name]["cols"][$z]["type"] = "text";
                $z++;
                $raw_tables["tables"][$var_name]["cols"][$z]["label"] = "Sale Price : $[txt]/pcs";
                $raw_tables["tables"][$var_name]["cols"][$z]["title"] = "x_metadata._sale_price";
                $raw_tables["tables"][$var_name]["cols"][$z]["type"] = "text";
                $z++;
                $raw_tables["tables"][$var_name]["cols"][$z]["label"] = "Weight : [txt]Kg";
                $raw_tables["tables"][$var_name]["cols"][$z]["title"] = "x_metadata._weight";
                $raw_tables["tables"][$var_name]["cols"][$z]["type"] = "text";
                $z++;
                $raw_tables["tables"][$var_name]["cols"][$z]["label"] = "Length";
                $raw_tables["tables"][$var_name]["cols"][$z]["title"] = "x_metadata._length";
                $raw_tables["tables"][$var_name]["cols"][$z]["type"] = "text";
                $z++;
                $raw_tables["tables"][$var_name]["cols"][$z]["label"] = "Width";
                $raw_tables["tables"][$var_name]["cols"][$z]["title"] = "x_metadata._width";
                $raw_tables["tables"][$var_name]["cols"][$z]["type"] = "text";
                $z++;
                $raw_tables["tables"][$var_name]["cols"][$z]["label"] = "Height";
                $raw_tables["tables"][$var_name]["cols"][$z]["title"] = "x_metadata._height";
                $raw_tables["tables"][$var_name]["cols"][$z]["type"] = "text";
                $z++;
                $raw_tables["tables"][$var_name]["cols"][$z]["label"] = "Price : $[txt]/pcs";
                $raw_tables["tables"][$var_name]["cols"][$z]["title"] = "x_metadata._price";
                $raw_tables["tables"][$var_name]["cols"][$z]["type"] = "text";
                $raw_tables['tables'][$var_name]['cols'][$z]['page_list'] = true;
                $raw_tables['tables'][$var_name]['cols'][$z]['page_detail'] = true;
                $z++;
                $raw_tables["tables"][$var_name]["cols"][$z]["label"] = "Stock";
                $raw_tables["tables"][$var_name]["cols"][$z]["title"] = "x_metadata._stock";
                $raw_tables["tables"][$var_name]["cols"][$z]["type"] = "text";
                $z++;
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'Order';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'link';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'link';
                $z++;
                $raw_tables['tables'][$var_name]['cols'][$z]['label'] = 'Share Link';
                $raw_tables['tables'][$var_name]['cols'][$z]['title'] = 'link';
                $raw_tables['tables'][$var_name]['cols'][$z]['type'] = 'share_link';
                $raw_tables['tables'][$var_name]['db_url'] = 'http://your-blog/wp-json/wp/v2/products';
                $raw_tables['tables'][$var_name]['db_type'] = 'online';
                $required .= '<div class="alert alert-danger">';
                $required .= '<h4>WooCommerce</h4>';
                $required .= '<p>WooCommerce is a free eCommerce plugin that allows you to sell anything, beautifully. In order for the Ionic App Builder working properly please install the plugins list below</p>';
                $required .= '<p>';
                $required .= '<a class="btn btn-sm btn-danger" target="_blank" href="https://wordpress.org/plugins/rest-api/">REST-API 2</a>&nbsp;';
                $required .= '<a class="btn btn-sm btn-danger" target="_blank" href="https://wordpress.org/plugins/rest-api-helper/">REST-API Helper</a>&nbsp;';
                $required .= '<a class="btn btn-sm btn-danger" target="_blank" href="https://wordpress.org/plugins/rest-api-enabler/">REST-API Enabler</a>&nbsp;';
                $required .= '<a class="btn btn-sm btn-danger" target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a>&nbsp;';
                $required .= '</p>';
                $required .= '</div>';
                break;
        }
    }
}
if(!isset($_GET['cols']))
{
    if(isset($raw_tables['tables'][$tables_prefix]['cols']))
    {
        $_GET['cols'] = count($raw_tables['tables'][$tables_prefix]['cols']);
    } else
    {
        $_GET['cols'] = 4;
    }
}
$max_column = (int)$_GET['cols'];
for($i = 1; $i <= 100; $i++)
{
    if($max_column == $i)
    {
        $table_col[] = array(
            'value' => $i,
            'label' => '- '.$i.' '.__('column'),
            'active' => true);
    } else
    {
        $table_col[] = array(
            'value' => $i,
            'label' => '- '.$i.' '.__('column'),
            );
    }
}
if(!isset($_GET['parent']))
{
    $_GET['parent'] = '';
}
if($_GET['parent'] == '')
{
    if(isset($raw_tables['tables'][$tables_prefix]['parent']))
    {
        $_GET['parent'] = $raw_tables['tables'][$tables_prefix]['parent'];
    } else
    {
        $_GET['parent'] = '';
    }
}
$_page_select[] = array('label' => __('No Page (Only Need Table)'),'value' => 'none');
$z = 1;
foreach(glob("projects/".$file_name."/page.*.json") as $filename)
{
    $_list_pages = json_decode(file_get_contents($filename),true);
    if(isset($_list_pages['page'][0]))
    {
        $list_pages = $_list_pages['page'][0];
        if(substr($list_pages['menutype'],0,4) != 'sub-')
        {
            if(!isset($list_pages['last_edit_by']))
            {
                $list_pages['last_edit_by'] = '-';
            }

            $_locked_status = ' page ';
            if(!isset($list_pages['lock']))
            {
                $list_pages['lock'] = false;
            }
            if($list_pages['lock'] == true)
            {
                $_locked_status = '--- ['.__('locked').'] ';
            }


            $_page_select[$z] = array('label' => '-'.$_locked_status.' `'.($list_pages['prefix']).'` ('.__('by').' '.$list_pages['last_edit_by'].')','value' => $list_pages['prefix']);
            if($_GET['parent'] == $list_pages['prefix'])
            {
                $_page_select[$z]['active'] = true;
            }
        }
        $z++;
    }
}
$table_content = null;
$table_content .= '<blockquote class="blockquote blockquote-info">'.__('You can using menu <a href="./?page=h-recovery-and-issue">(IMAB) Recovery and Issue</a> to check the conflicting table').'</blockquote>';
$table_content .= '<div class="panel panel-default">';
$table_content .= '<div class="panel-heading">';
$table_content .= '<h5 class="panel-title">'.__('General').'</h5>';
$table_content .= '</div>';
$table_content .= '<div class="panel-body">';
$table_content .= '<div class="row">';
$table_content .= '<div class="col-md-4" >';
$table_content .= $bs->FormGroup('table-current','default','select',__('Current Table'),$_tables_select);
$table_content .= '</div>';
$table_content .= '<div class="col-md-2">';
$table_content .= '<div class="form-group"><label>&nbsp;</label><div><a class="btn btn-danger btn-sm" id="select-current-table" href="#">'.__('Reload').'</a></div></div>';
$table_content .= '</div>';
$table_content .= '<div class="col-md-3">';
$table_content .= $bs->FormGroup('tables-cols','default','select',__('Need Column'),$table_col,__('How much need columns?'),'');
$table_content .= '</div>';
$table_content .= '<div class="col-md-3">';
$table_content .= $bs->FormGroup('tables[parent]','default','select',__('Page Target (Controller)'),$_page_select,__('it\'s will overwrite pages'),'');
$table_content .= '</div>';
$table_content .= '</div>';
if(!isset($raw_tables['tables'][$tables_prefix]['required']))
{
    $raw_tables['tables'][$tables_prefix]['required'] = null;
}
$table_content .= '<div class="row">';
$table_content .= '<div class="col-md-12">';
$table_content .= $raw_tables['tables'][$tables_prefix]['required'];
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$item_types[] = array('label' => 'Default','value' => 'item');
$item_types[] = array('label' => 'Text Wrap','value' => 'item item-text-wrap');
$item_types[] = array('label' => 'Thumbnail (left)','value' => 'item item-thumbnail-left');
$item_types[] = array('label' => 'Thumbnail (right)','value' => 'item item-thumbnail-right');
$item_types[] = array('label' => 'Avatar','value' => 'item item-avatar');
$item_types[] = array('label' => 'Icon (left)','value' => 'item item-icon-left');
$item_types[] = array('label' => 'Icon (right)','value' => 'item item-icon-right');
$item_types[] = array('label' => 'Icon (left + right)','value' => 'item item-icon-left item-icon-right');
$item_types[] = array('label' => 'Image','value' => 'item item-image');
$item_types[] = array('label' => 'Body','value' => 'item item-body');
$item_types[] = array('label' => 'Button (right)','value' => 'item item-button-right');
$item_types[] = array('label' => 'Button (left)','value' => 'item item-button-left');
if(!isset($raw_tables['tables'][$tables_prefix]['itemcolor']))
{
    $raw_tables['tables'][$tables_prefix]['itemcolor'] = 'colorful';
}
if(!isset($raw_tables['tables'][$tables_prefix]['itemtype']))
{
    $raw_tables['tables'][$tables_prefix]['itemtype'] = 'item';
}
$z = 0;
foreach($item_types as $item_type)
{
    $_item_types[$z] = $item_type;
    if($item_type['value'] == $raw_tables['tables'][$tables_prefix]['itemtype'])
    {
        $_item_types[$z]['active'] = true;
    }
    $z++;
}
$colors = array(
    'colorful',
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
    'dark');
$z = 0;
foreach($colors as $_color)
{
    $_item_color[$z] = array('label' => ucwords($_color),'value' => $_color);
    if($raw_tables['tables'][$tables_prefix]['itemcolor'] == $_color)
    {
        $_item_color[$z]['active'] = true;
    }
    $z++;
}
$db_types[] = array('label' => 'Offline (Backend Tools -> JSON/JSON Raw)','value' => 'offline');
$db_types[] = array('label' => 'Online','value' => 'online');
$z++;
foreach($db_types as $db_type)
{
    $_db_types[$z] = $db_type;
    if($raw_tables['tables'][$tables_prefix]['db_type'] == $db_type['value'])
    {
        $_db_types[$z]['active'] = true;
    }
    $z++;
}

// TODO: --------|-- OPTION DATA LISTING TEMPLATES
$item_templates[] = array('label' => 'Manual','value' => 'none');
$item_templates[] = array('label' => 'Coding / Page Builder','value' => 'manual_coding');
$item_templates[] = array('label' => 'Avatar (required: id + title + text)','value' => 'avatar');
$item_templates[] = array('label' => 'Gallery (required: id + title + image/text/rating/html)','value' => 'gallery');
$item_templates[] = array('label' => 'One Icon (required: id + title + icon/text)','value' => '1-icon');
$item_templates[] = array('label' => 'Two Icon (required: id + title + icon/text)','value' => '2-icon');
$item_templates[] = array('label' => 'Thumbnail I (required: id + title + image/text/rating/html )','value' => 'thumbnail');
$item_templates[] = array('label' => 'Thumbnail II (required: id + title + image/text/rating/html )','value' => 'thumbnail-2');
$item_templates[] = array('label' => 'Thumbnail III (required: id + title + image + text/html)','value' => 'thumbnail-3');
$item_templates[] = array('label' => 'Slidebox (required: id + title + image + text )','value' => 'slidebox');
$item_templates[] = array('label' => 'Slidebox - Media Wizard - Without Single Page (required: id + image + title + text/html/paragraph)','value' => 'slidebox-1');
$item_templates[] = array('label' => 'Button (required: id + title + icon)','value' => 'button');
$item_templates[] = array('label' => 'Homepage (Without Single Page)','value' => 'homepage1');
$item_templates[] = array('label' => 'Card Showcase (required: id + title + image + text + html)','value' => 'showcase');
$item_templates[] = array('label' => 'Table - Without Single Page (required: id + text)','value' => 'table');
$item_templates[] = array('label' => 'GMAP + Marker (required: id + title + gmap + html)','value' => 'gmapmarker');
//$item_templates[] = array('label' => 'Youtube', 'value' => 'youtube');
$item_templates[] = array('label' => 'Dictionary (required: id + title + text)','value' => 'dictionary');
$item_templates[] = array('label' => 'FAQs (required: id + title + text/html)','value' => 'faqs');

$item_templates[] = array('label' => 'Chart - Line Chart (required: id + title + text + text + text)','value' => 'chart-line');
$item_templates[] = array('label' => 'Chart - Bar Chart (required: id + title + text + text + text)','value' => 'chart-bar');
$item_templates[] = array('label' => 'Chart - Doughnut Chart (required: id + title + text + text + text)','value' => 'chart-doughnut');

$z = 0;
foreach($item_templates as $item_template)
{
    $_item_template[$z] = $item_template;
    if(!isset($item_template['value']))
    {
        $item_template['value'] = 'none';
    }
    if(!isset($raw_tables['tables'][$tables_prefix]['template']))
    {
        $raw_tables['tables'][$tables_prefix]['template'] = 'none';
    }
    if($raw_tables['tables'][$tables_prefix]['template'] == $item_template['value'])
    {
        $_item_template[$z]['active'] = true;
    }
    $z++;
}
// TODO: --------|-- OPTION SINGLE TEMPLATES
$item_template_single[] = array('label' => 'Manual','value' => 'none');
$item_template_single[] = array('label' => 'Featured','value' => 'featured');
$item_template_single[] = array('label' => 'Tabs','value' => 'tabs');
$item_template_single[] = array('label' => 'Heroes 1 (title + images + heading-2)','value' => 'heroes-1');
$item_template_single[] = array('label' => 'Heroes 1 + Tabs (title + images + heading-2)','value' => 'heroes-3');
$item_template_single[] = array('label' => 'Heroes 2 (title + images + button)','value' => 'heroes-2');
$item_template_single[] = array('label' => 'Heroes 2 + Tabs (title + images + button)','value' => 'heroes-4');
$item_template_single[] = array('label' => 'Heroes 3 + Fab (title + images + link)','value' => 'heroes-5');
$item_template_single[] = array('label' => 'Card - Tabs','value' => 'tabs|card');
$item_template_single[] = array('label' => 'Card - Heroes 1 (title + images + heading-2)','value' => 'heroes-1|card');
$item_template_single[] = array('label' => 'Card - Heroes 1 + Tabs (title + images + heading-2)','value' => 'heroes-3|card');
$item_template_single[] = array('label' => 'Card - Heroes 2 (title + images + button)','value' => 'heroes-2|card');
$item_template_single[] = array('label' => 'Card - Heroes 2 + Tabs (title + images + button)','value' => 'heroes-4|card');
$item_template_single[] = array('label' => 'Card - Heroes 3 + Fab (title + images + link)','value' => 'heroes-5|card');
$z = 0;
foreach($item_template_single as $template_single)
{
    $_item_template_single[$z] = $template_single;
    if(!isset($template_single['value']))
    {
        $_item_template_single['value'] = 'none';
    }
    if(!isset($raw_tables['tables'][$tables_prefix]['template_single']))
    {
        $raw_tables['tables'][$tables_prefix]['template_single'] = 'none';
    }
    if($raw_tables['tables'][$tables_prefix]['template_single'] == $template_single['value'])
    {
        $_item_template_single[$z]['active'] = true;
    }
    $z++;
}
$table_content .= '<div class="panel panel-default">';
$table_content .= '<div class="panel-heading">';
$table_content .= '<h5 class="panel-title">'.__('Properties').'</h5>';
$table_content .= '</div>';
$table_content .= '<div class="panel-body">';
$table_content .= '<div class="row">';
$table_content .= '<div class="col-md-4">';
$table_content .= $bs->FormGroup('tables[title]','default','text',__('Table Name <small>(or singular name)</small>').' <span style="color:red">***</span>','Movie','<span class="label label-danger">'.__('note').'</span>'.__('maximum length of 16 characters and change title will be create new table'),'required maxlength="16"','8',$raw_tables['tables'][$tables_prefix]['title']);
$table_content .= '</div>';
$table_content .= '<div class="col-md-3">';
$table_content .= $bs->FormGroup('tables[template]','default','select',__('Template for Data Listing'),$_item_template,__('Template for list items'),'');
$table_content .= '</div>';
$table_content .= '<div class="col-md-3">';
$table_content .= $bs->FormGroup('tables[template_single]','default','select',__('Template for Single Data'),$_item_template_single,__('Template for single item'));
$table_content .= '</div>';
$table_content .= '<div class="col-md-2">';
$table_content .= $bs->FormGroup('tables[itemtype]','default','select',__('Manual Code'),$_item_types,__('Alternative template'));
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '<div class="row">';
$table_content .= '<div class="col-md-3">';
$table_content .= $bs->FormGroup('tables[itemcolor]','default','select',__('Color'),$_item_color,__('Coloring template'),'data-type="color"');
$table_content .= '</div>';
$table_content .= '<div class="col-md-3">';
$table_content .= $bs->FormGroup('tables[db_type]','default','select',__('Source JSON').' <span style="color:red">**</span>',$_db_types,__('Type of aplication data'));
$table_content .= '</div>';
$table_content .= '<div class="col-md-3">';
$table_content .= $bs->FormGroup('tables[db_var]','default','text',__('1st Variable for List Item'),'','<span class="this-elms first-variable" data-target=".first-variable">'.__('Fill <ins>blank</ins> for default, <code>{<span class="text-danger">items</span>:[{...},{...}]}</code> then you must fill <span class="text-danger">.items</span>').'</span>','title="If the data json like {items:[{...},{...}]}"','8',htmlentities($raw_tables['tables'][$tables_prefix]['db_var']));
$table_content .= '</div>';
if(!isset($raw_tables['tables'][$tables_prefix]['db_var_single']))
{
    $raw_tables['tables'][$tables_prefix]['db_var_single'] = '';
}
$table_content .= '<div class="col-md-3">';
$table_content .= $bs->FormGroup('tables[db_var_single]','default','text',__('1st Variable for Single Item'),'','<span class="this-elms first-variable" data-target=".first-variable">'.__('Fill <ins>blank</ins> for default, <code>{<span class="text-danger">item</span>:{...}}</code> then you must fill <span class="text-danger">.item</span>').'</span>','title="If the data json like {item:{...}}"','8',htmlentities($raw_tables['tables'][$tables_prefix]['db_var_single']));
$table_content .= '</div>';

$table_content .= '</div>';
$check_url_list = $check_url_single = '';
if($raw_tables['tables'][$tables_prefix]['db_url'] != '')
{
    $check_url_list = '<br/><a data-toggle="modal" data-target="#json-modal" class="btn btn-xs btn-danger" target="_blank" href="./system/plugin/json-check.php?type=list&url='.urlencode($raw_tables['tables'][$tables_prefix]['db_url']).'&db_var='.$raw_tables['tables'][$tables_prefix]['db_var'].'"><span class="fa fa-gear"></span>&nbsp;'.__('Analyzing JSON Data').'</a>';
}
if($raw_tables['tables'][$tables_prefix]['db_url_single'] != '')
{
    $found_var_id = 'id';
    foreach($raw_tables['tables'][$tables_prefix]['cols'] as $find_cols)
    {
        if($find_cols['type'] == 'id')
        {
            $found_var_id = str2var($find_cols['title'],false);
        }
    }
    $check_url_single = '<br/><a data-toggle="modal" data-target="#json-modal" class="btn btn-xs btn-danger" target="_blank" href="./system/plugin/json-check.php?type=single&url='.urlencode($raw_tables['tables'][$tables_prefix]['db_url_single']).'&db_var='.$found_var_id.'"><span class="fa fa-gear"></span>&nbsp;Analyzing JSON Data</a>';
}
$table_content .= '<div class="row">';
$table_content .= '<div class="col-md-5">';
$table_content .= $bs->FormGroup('tables[db_url]','default','text',__('URL List Item').' <span style="color:red">*</span>','http://domain/json/movies','
<p>Fill your JSON URL</p>
<blockquote class="blockquote blockquote-info first-param url-single-item">JSON array can contain <strong>multiple objects</strong>, Example:<br/><code>[{id:1,name:...},{id:2,name:...},{id:3,name:...}]</code></blockquote>
<blockquote data-target=".first-param" class="blockquote blockquote-info this-elms first-param"><strong><ins>Dynamic conditions or target for relation table</ins></strong>,
URL List, URL Single, 1st param are required, example you need var2 is dynamic <br/><code>false: http://yourweb/api?var1=val1&amp;var2=val2</code> 
<br/><code>true: http://yourweb/api?var2=val2&amp;var1=val1</code></blockquote>
<blockquote data-target=".first-variable" class="blockquote blockquote-info this-elms first-variable"><strong><ins>Certain conditions</ins></strong> when JSON objects can contain multiple name/values, you can convert to array using <strong>1st Variable</strong> above (eg: wp json-api plugin)</blockquote>
'.$check_url_list,'','8',$raw_tables['tables'][$tables_prefix]['db_url']);
$table_content .= '</div>';
$table_content .= '<div class="col-md-4">';
$table_content .= $bs->FormGroup('tables[db_url_single]','default','text',__('URL Single Item'),'http://domain/json/single_movie?id=','
<p>Fill <ins>blank</ins> for default</p>
<blockquote class="blockquote blockquote-info first-param url-single-item this-elms" data-target=".url-single-item">JSON objects can contain <strong>multiple name/values</strong>, Example:<br/><code>{id:1,name:...}</code><br/> URL List item is required</blockquote>
'.$check_url_single,'','8',$raw_tables['tables'][$tables_prefix]['db_url_single']);
$table_content .= '</div>';
$checked = '';
if(!isset($raw_tables['tables'][$tables_prefix]['db_url_dinamic']))
{
    $raw_tables['tables'][$tables_prefix]['db_url_dinamic'] = false;
}
if($raw_tables['tables'][$tables_prefix]['db_url_dinamic'] == true)
{
    $checked = 'checked';
}
if(!isset($raw_tables['tables'][$tables_prefix]['query']['var']))
{
    $raw_tables['tables'][$tables_prefix]['query']['var'] = '';
}
$_1stparam = null;
if(strlen($raw_tables['tables'][$tables_prefix]['query']['var']) > 2)
{
    $_1stparam = '(<code>'.$raw_tables['tables'][$tables_prefix]['query']['var'].'</code>)';
}
$table_content .= '<div class="col-md-3">';
$table_content .= $bs->FormGroup('tables[db_url_dinamic]','default','checkbox',__('Parameter'),__('Dinamic on 1st param ').$_1stparam,'<p>'.__('Don\'t checked for default').'</p><blockquote data-target=".first-param" class="blockquote blockquote-info this-elms first-param">Required URL List Item using query and URL Single Item is required</blockquote>',$checked);
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '<div id="json-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">wait...</div></div></div>';
// TODO: --------|-- COLUMN TYPE
$col_types[] = array('label' => 'ID','value' => 'id');
$col_types[] = array('label' => 'Heading 1 (title)','value' => 'heading-1');
$col_types[] = array('label' => 'Heading 2','value' => 'heading-2');
$col_types[] = array('label' => 'Text | Raw [txt]','value' => 'text');
$col_types[] = array('label' => 'Rating','value' => 'rating');
$col_types[] = array('label' => 'Images','value' => 'images');
$col_types[] = array('label' => 'Icon','value' => 'icon');
$col_types[] = array('label' => 'Paragraph','value' => 'paragraph');
$col_types[] = array('label' => 'Trust As Html','value' => 'to_trusted');
$col_types[] = array('label' => 'Link - Social Share','value' => 'share_link');
$col_types[] = array('label' => 'Link - Open External','value' => 'link');
$col_types[] = array('label' => 'Link - Open App Broswer','value' => 'appbrowser');
$col_types[] = array('label' => 'Link - Open WebView','value' => 'webview');

$col_types[] = array('label' => 'HTML5 Audio (value in backend: http://yoursite/audio.mp3)','value' => 'audio');
$col_types[] = array('label' => 'HTML5 Video (value in backend: http://yoursite/video.mp4)','value' => 'video');
$col_types[] = array('label' => 'Youtube ID (value in backend: 4HkG8z3sa-0)','value' => 'ytube');
$col_types[] = array('label' => 'Google Map (value in backend: 41,-87)','value' => 'gmap');
$col_types[] = array('label' => 'Slidebox (value in backend: <h3>slide1<h3>|<h3>slide2<h3>|<h3>slide3<h3>)','value' => 'slidebox');

$col_types[] = array('label' => 'as Username','value' => 'as_username');
$col_types[] = array('label' => 'as Password','value' => 'as_password');

// TODO: NEW FEATURES
$col_types[] = array('label' => 'Number','value' => 'number');
$col_types[] = array('label' => 'Float','value' => 'float');
$col_types[] = array('label' => 'Date (value in backend: 1527067919000)','value' => 'date');
$col_types[] = array('label' => 'Date Time (value in backend: 1527067919000)','value' => 'datetime');
$col_types[] = array('label' => 'PHP - Date (value in backend: 1527067919)','value' => 'date_php');
$col_types[] = array('label' => 'PHP - Date Time (value in backend: 1527067919)','value' => 'datetime_php');
$col_types[] = array('label' => 'Date Time (value in backend: 2018-05-02T14:17:35)','value' => 'datetime_string');
$col_types[] = array('label' => 'Open With : Email App','value' => 'app_email');
$col_types[] = array('label' => 'Open With : SMS App','value' => 'app_sms');
$col_types[] = array('label' => 'Open With : Dialer/Call App','value' => 'app_call');
$col_types[] = array('label' => 'Open With : GEO App (value in backend: 41,-87)','value' => 'app_geo');


$table_content .= '<div class="panel panel-default">';
$table_content .= '<div class="panel-heading">';
$table_content .= '<h5 class="panel-title">'.__('Columns').'</h5>';
$table_content .= '</div>';
$table_content .= '<div class="panel-body">';
$table_content .= '

<blockquote class="blockquote blockquote-danger">
<h4>'.__('The rules that apply are:').'</h4>
<ol>
<li>The table required have one column or two column which dataType <code>ID</code> and  must be placed <code>1st</code>, then <code>2nd</code>.</li>
<li><code>1st ID</code> used for <code>primary in MySQL</code>, but you can add other column for <code>2nd ID</code> that used as <code>parameter/slug</code> of link or <code>unique in mysql</code> eg: <code>href=&quot;/'.$file_name.'/'.$tables_prefix.'_singles/{{item.slug}}&quot;</code></li>
<li>ID on different tables can not be used, you must set type text or other, example <code>column cat_id</code> in <code>table posts</code> is not <code>type ID</code> or <code>column post_id</code> in <code>table `topics`</code> not <code>type ID</code>, The truth is <code>column post_id</code> in <code>table posts</code> and <code>column topic_id</code> in <code>table topics</code>, It is ID type.</li>
<li>Columns of <code>type: text/raw</code> can be custom, example <code>price: $[txt]/pcs</code> will displaying <code>price: $5/pcs</code></li>
<li>Uchecked <code>Source</code> for secret value like <code>password</code> in <code>JSON Listing</code></li>
<li><strong>PHP/MySQL Backend</strong>: <code>Required Login menu</code> will be available when a table contain <code>as_password</code> and <code>as_username</code> in column type</li>
<li><strong>PHP/MySQL Backend</strong>: <code>Form menu</code> using <code>table</code> that contain <code>as_username</code> in column type will using for <code>current login</code> in from submit.</li>
<li><strong>PHP/MySQL Backend, JSON or Offline Data</strong>: column variable contain character <code>.</code>, <code>:</code>,<code>\'</code> and <code>[ ]</code> not supported.</li>
</ol>
</blockquote>

';
$table_content .= '<div class="table-responsive">';
$table_content .= '<table id="group_column_list_" class="table table-striped sortable">';
$table_content .= '<thead>';
$table_content .= '<tr>';
$table_content .= '<th></th>';
$table_content .= '<th>'.__('Label').'<span style="color:red">*</span></th>';
$table_content .= '<th>'.__('Variable').'<span style="color:red">**</span></th>';
$table_content .= '<th style="width:15%">'.__('Type').'</th>';
$table_content .= '<th>'.__('Listing').'</th>';
$table_content .= '<th>'.__('Detail').'</th>';
$table_content .= '<th>'.__('Source').'<span style="color:red">**</span></th>';
$table_content .= '<th></th>';
$table_content .= '</tr>';
$table_content .= '</thead>';
$table_content .= '<tbody>';
for($i = 0; $i < $max_column; $i++)
{
    if(!isset($raw_tables['tables'][$tables_prefix]['cols'][$i]['label']))
    {
        $raw_tables['tables'][$tables_prefix]['cols'][$i]['label'] = null;
    }
    if(!isset($raw_tables['tables'][$tables_prefix]['cols'][$i]['title']))
    {
        $raw_tables['tables'][$tables_prefix]['cols'][$i]['title'] = null;
    }
    if(!isset($raw_tables['tables'][$tables_prefix]['cols'][$i]['type']))
    {
        $raw_tables['tables'][$tables_prefix]['cols'][$i]['type'] = 'text';
        if($i == 0)
        {
            $raw_tables['tables'][$tables_prefix]['cols'][$i]['type'] = 'id';
        }
        if($i == 1)
        {
            $raw_tables['tables'][$tables_prefix]['cols'][$i]['type'] = 'heading-1';
        }
        if($i == 2)
        {
            $raw_tables['tables'][$tables_prefix]['cols'][$i]['type'] = 'images';
        }
        if($i == 3)
        {
            $raw_tables['tables'][$tables_prefix]['cols'][$i]['type'] = 'to_trusted';
        }
        if($i == 4)
        {
            $raw_tables['tables'][$tables_prefix]['cols'][$i]['type'] = 'share_link';
        }
    }
    $_col_types = array();
    foreach($col_types as $col_type)
    {
        if($raw_tables['tables'][$tables_prefix]['cols'][$i]['type'] == $col_type['value'])
        {
            $_col_types[] = array(
                'label' => $col_type['label'],
                'value' => $col_type['value'],
                'active' => true);
        } else
        {
            $_col_types[] = array(
                'label' => $col_type['label'],
                'value' => $col_type['value'],
                );
        }
    }
    if(isset($raw_tables['tables'][$tables_prefix]['cols'][$i]['json']))
    {
        $col_json = 'checked';
        if($raw_tables['tables'][$tables_prefix]['cols'][$i]['json'] == 'false')
        {
            $col_json = '';
        }
    } else
    {
        $col_json = '';
    }
    if($raw_tables['tables'][$tables_prefix]['cols'][$i]['type'] == 'id')
    {
        $col_json = 'checked';
    }
    if(isset($raw_tables['tables'][$tables_prefix]['cols'][$i]['page_list']))
    {
        $page_list = 'checked';
        if($raw_tables['tables'][$tables_prefix]['cols'][$i]['page_list'] == false)
        {
            $page_list = '';
        } else
        {
            $col_json = 'checked';
        }
    } else
    {
        $page_list = '';
    }
    if(isset($raw_tables['tables'][$tables_prefix]['cols'][$i]['page_detail']))
    {
        $page_detail = 'checked';
        if($raw_tables['tables'][$tables_prefix]['cols'][$i]['page_detail'] == false)
        {
            $page_detail = '';
        } else
        {
            $col_json = 'checked';
        }
    } else
    {
        $page_detail = '';
    }
    $table_content .= '<tr id="data-'.$i.'">';
    $table_content .= '<td class="v-align">';
    $table_content .= '<span class="glyphicon glyphicon-move"></span>';
    $table_content .= '</td>';
    $table_content .= '<td>';
    $table_content .= $bs->FormGroup('tables[cols]['.$i.'][label]','default','text','','Label '.$i,'<strong>format</strong>: <span id="label-helpder-'.$i.'">text</span>','required '.$direction,'8',htmlentities($raw_tables['tables'][$tables_prefix]['cols'][$i]['label']),'colm_label');
    $table_content .= '</td>';
    $table_content .= '<td>';
    $table_content .= $bs->FormGroup('tables[cols]['.$i.'][title]','default','text','','label_'.$i,'<strong>format</strong>: a-z,A-Z,0-9,. and _<br/>Don\'t start variable with numbers','required','8',htmlentities($raw_tables['tables'][$tables_prefix]['cols'][$i]['title']));
    $table_content .= '</td>';
    $table_content .= '<td>';
    $table_content .= $bs->FormGroup('tables[cols]['.$i.'][type]','default','select','',$_col_types,'','data-helper="#label-helpder-'.$i.'" data-type="cols"','8','','column-type');
    $table_content .= '</td>';
    $table_content .= '<td>';
    $table_content .= $bs->FormGroup('tables[cols]['.$i.'][page_list]','default','checkbox','','',' ',$page_list.' title="Displaying in JSON Listing"','8','true','json-checked');
    $table_content .= '</td>';
    $table_content .= '<td>';
    $table_content .= $bs->FormGroup('tables[cols]['.$i.'][page_detail]','default','checkbox','','',' ',$page_detail.' title="Displaying in JSON Single/Detail"','8','true','json-checked');
    $table_content .= '</td>';
    $table_content .= '<td>';
    $table_content .= $bs->FormGroup('tables[cols]['.$i.'][json]','default','checkbox','','',' ',$col_json.' title="Unchecked for private/secret value"','8','true');
    $table_content .= '</td>';
    $table_content .= '<td>';
    $table_content .= '<a class="remove-item btn btn-danger btn-sm" href="#!_" data-target="#data-'.$i.'" ><i class="glyphicon glyphicon-trash"></i></a>';
    $table_content .= '</td>';
    $table_content .= '</tr>';
}
$table_content .= '</tbody>';
$table_content .= '</table>';
$table_content .= '</div>';
if(isset($raw_tables['tables'][$tables_prefix]['sample_data']))
{
    $sample_data = 'checked';
} else
{
    $sample_data = '';
}
if(!isset($raw_tables['tables'][$tables_prefix]['fetch_per_scroll']))
{
    $raw_tables['tables'][$tables_prefix]['fetch_per_scroll'] = 1;
}
for($i = 1; $i < 100; $i++)
{
    $z = $i - 1;
    $_fetch_per_scroll[$z] = array("label" => $i.' items',"value" => $i);
    if($raw_tables['tables'][$tables_prefix]['fetch_per_scroll'] == $_fetch_per_scroll[$z]['value'])
    {
        $_fetch_per_scroll[$z]['active'] = true;
    }
}
// TODO: --------|-- OPTION RELATION
if(!isset($raw_tables['tables'][$tables_prefix]['relation_to']))
{
    $current_relation_to = 'none';
} else
{
    $current_relation_to = $raw_tables['tables'][$tables_prefix]['relation_to'];
}
$relation_to[] = array('label' => 'none','value' => 'none');
foreach($_SESSION['PROJECT']['tables'] as $table_relation)
{
    if(!isset($table_relation['db_url_dinamic']))
    {
        $table_relation['db_url_dinamic'] = '';
    }
    if(($table_relation['db_url_dinamic'] == 'on') && ($tables_prefix != $table_relation['prefix']))
    {
        $relation_to[] = array('label' => ' -> Table `'.$table_relation['title'].'` ('.$table_relation['parent'].')','value' => $table_relation['parent']);
    }
}
$z = 0;
foreach($relation_to as $relation_to_item)
{
    $_relation_to[$z] = $relation_to_item;
    if($current_relation_to == $relation_to_item['value'])
    {
        $_relation_to[$z]['active'] = true;
    }
    $z++;
}
if(!isset($raw_tables['tables'][$tables_prefix]['icon']))
{
    $raw_tables['tables'][$tables_prefix]['icon'] = 'ion-social-buffer';
}
// TODO: --------|-- MAX ITEMS
if(!isset($raw_tables['tables'][$tables_prefix]['max_items']))
{
    $raw_tables['tables'][$tables_prefix]['max_items'] = '50';
}
if(!is_numeric($raw_tables['tables'][$tables_prefix]['max_items']))
{
    $raw_tables['tables'][$tables_prefix]['max_items'] = '50';
}

if(!isset($raw_tables['tables'][$tables_prefix]['items_focus']))
{
    $raw_tables['tables'][$tables_prefix]['items_focus'] = 'scroll';
}

$items_focus[] = array('value' => 'scroll','label' => 'Fetching Perscroll');
$items_focus[] = array('value' => 'search','label' => 'Searching in All Items');
$_items_focus = array();
$t = 0;
foreach($items_focus as $item_focus)
{
    $_items_focus[$t] = $item_focus;
    if($_items_focus[$t]['value'] == $raw_tables['tables'][$tables_prefix]['items_focus'])
    {
        $_items_focus[$t]['active'] = true;
    }
    $t++;
}
$table_content .= '<div class="row">';
$table_content .= '<div class="col-md-2">';
$table_content .= $bs->FormGroup('tables[items_focus]','default','select',__('Focus on Capability'),$_items_focus,'');
$table_content .= '</div>';
$table_content .= '<div class="col-md-2">';
$table_content .= $bs->FormGroup('tables[max_items]','default','text',__('Max item for 1st Loading'),'50','','','',$raw_tables['tables'][$tables_prefix]['max_items']);
$table_content .= '</div>';
$table_content .= '<div class="col-md-2">';
$table_content .= $bs->FormGroup('tables[fetch_per_scroll]','default','select',__('Next Item per scrolling'),$_fetch_per_scroll,__('fetching per scrolling'));
$table_content .= '</div>';
$table_content .= '<div class="col-md-2">';
$table_content .= $bs->FormGroup('tables[icon]','default','text',__('Default or alternate icon'),'ion-social-buffer',__('Ionicons class'),'data-type="icon-picker"','8',$raw_tables['tables'][$tables_prefix]['icon']);
$table_content .= '</div>';
$table_content .= '<div class="col-md-4">';
$table_content .= $bs->FormGroup('tables[sample_data]','default','checkbox',__('Sample Data'),__('Create JSON File'),' ',$sample_data,'8','true');
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '<div class="row">';
if(!isset($raw_tables['tables'][$tables_prefix]['localstorage']))
{
    $raw_tables['tables'][$tables_prefix]['localstorage'] = 'localforage';
}
if($raw_tables['tables'][$tables_prefix]['localstorage'] == 'true')
{
    $raw_tables['tables'][$tables_prefix]['localstorage'] = 'none';
}
// TODO: --------|-- OPTION STORAGE
$_localstorage[] = array('value' => 'none','label' => 'none');
//$_localstorage[] = array('value' => 'localstorage', 'label' => 'LocalStorage (Limit < 5mb)');
$_localstorage[] = array('value' => 'localforage','label' => 'localForage (IndexedDB)');
$z = 0;
foreach($_localstorage as $__localstorage)
{
    $localstorage[$z] = $__localstorage;
    if($raw_tables['tables'][$tables_prefix]['localstorage'] == $__localstorage['value'])
    {
        $localstorage[$z]['active'] = true;
    }
    $z++;
}
// TODO: --------|-- OPTION BOOKMARKS / CART
if(!isset($raw_tables['tables'][$tables_prefix]['bookmarks']))
{
    $raw_tables['tables'][$tables_prefix]['bookmarks'] = 'none';
}
$_bookmarks[] = array('value' => 'none','label' => 'none');
$_bookmarks[] = array('value' => 'cart','label' => 'Cart');
$_bookmarks[] = array('value' => 'bookmark','label' => 'Bookmark');
$z = 0;
foreach($_bookmarks as $_bookmark)
{
    $bookmarks[$z] = $_bookmark;
    if($raw_tables['tables'][$tables_prefix]['bookmarks'] == $_bookmark['value'])
    {
        $bookmarks[$z]['active'] = true;
    }
    $z++;
}

// TODO: --------|-- OPTION BOOKMARKS
if(!isset($raw_tables['tables'][$tables_prefix]['column-for-price']))
{
    $raw_tables['tables'][$tables_prefix]['column-for-price'] = 'none';
}

if(!isset($_SESSION['PROJECT']['tables'][$tables_prefix]['cols']))
{
    $_SESSION['PROJECT']['tables'][$tables_prefix]['cols'] = array();
}
if(is_array($_SESSION['PROJECT']['tables'][$tables_prefix]['cols']))
{
    $option_cols = $_SESSION['PROJECT']['tables'][$tables_prefix]['cols'];
} else
{
    $option_cols = array();
}
$_bookmark_options[] = array('value' => 'none','label' => 'none');
foreach($option_cols as $option_col)
{
    $_bookmark_options[] = array('value' => trim(str2var($option_col['title'],false)),'label' => $option_col['label'].' ('.str2var($option_col['title'],false).')');
}
$z = 0;
foreach($_bookmark_options as $_option_bookmark)
{
    $bookmark_options[$z] = $_option_bookmark;
    if($raw_tables['tables'][$tables_prefix]['column-for-price'] == $_option_bookmark['value'])
    {
        $bookmark_options[$z]['active'] = true;
    }
    $z++;
}

$currency_symbol = '$';
if(isset($raw_tables['tables'][$tables_prefix]['currency-symbol']))
{
    $currency_symbol = $raw_tables['tables'][$tables_prefix]['currency-symbol'];
}

// TODO: --------|-- OPTION MOTIONS
if(!isset($raw_tables['tables'][$tables_prefix]['motions']))
{
    $raw_tables['tables'][$tables_prefix]['motions'] = 'none';
}

$_motions[] = array('value' => 'none','label' => 'none');
$_motions[] = array('value' => 'blinds','label' => 'Blinds');
$_motions[] = array('value' => 'ripple','label' => 'Ripple');
$_motions[] = array('value' => 'fade-slide-in','label' => 'Fade Slide In');
$_motions[] = array('value' => 'fade-slide-in-right','label' => 'Fade Slide In Right');


$z = 0;
foreach($_motions as $_bookmark)
{
    $motions[$z] = $_bookmark;
    if($raw_tables['tables'][$tables_prefix]['motions'] == $_bookmark['value'])
    {
        $motions[$z]['active'] = true;
    }
    $z++;
}
$table_content .= '<div class="col-md-2">';
$table_content .= $bs->FormGroup('tables[relation_to]','default','select',__('Relation To'),$_relation_to,'Required: '.__('Table using <ins>dinamic 1st params</ins>'),'','8');
$table_content .= '</div>';

$table_content .= '<div class="col-md-2">';
$table_content .= $bs->FormGroup('tables[localstorage]','default','select',__('Offline Storage'),$localstorage,__('Offline Data'),'','8','true');
$table_content .= '</div>';

$table_content .= '<div class="col-md-2">';
$table_content .= $bs->FormGroup('tables[motions]','default','select',__('Motions or Animation'),$motions,__('required: hardware acceleration'),'','8','true');
$table_content .= '</div>';

$table_content .= '<div class="col-md-2">';
$table_content .= $bs->FormGroup('tables[bookmarks]','default','select',__('Virtual Table'),$bookmarks,__('page bookmark/cart'),'','8','true');
$table_content .= '</div>';

$table_content .= '<div class="col-md-2">';
$table_content .= $bs->FormGroup('tables[column-for-price]','default','select',__('Column for Price'),$bookmark_options,__('only for cart'),'','8','true');
$table_content .= '</div>';

$table_content .= '<div class="col-md-2">';
$table_content .= $bs->FormGroup('tables[currency-symbol]','default','text',__('Currency Symbol'),'$',__('only for cart'),'','8',$currency_symbol);
$table_content .= '</div>';


$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';


if(!isset($raw_tables['tables'][$tables_prefix]['languages']['retrieval_error_title']))
{
    $raw_tables['tables'][$tables_prefix]['languages']['retrieval_error_title'] = 'Network Error';
}
if(!isset($raw_tables['tables'][$tables_prefix]['languages']['retrieval_error_content']))
{
    $raw_tables['tables'][$tables_prefix]['languages']['retrieval_error_content'] = 'An error occurred while collecting data.';
}
if(!isset($raw_tables['tables'][$tables_prefix]['languages']['error_messages']))
{
    $error_messages = 'false';
    $raw_tables['tables'][$tables_prefix]['languages']['error_messages'] = 'false';
}


if($raw_tables['tables'][$tables_prefix]['languages']['error_messages'] == 'true')
{
    $error_messages = 'checked';
} else
{
    $error_messages = '';
}


if(!isset($raw_tables['tables'][$tables_prefix]['languages']['disable_error_notice']))
{
    $error_messages = 'false';
    $raw_tables['tables'][$tables_prefix]['languages']['disable_error_notice'] = 'false';
}
if($raw_tables['tables'][$tables_prefix]['languages']['disable_error_notice'] == 'true')
{
    $disable_error_notice = 'checked';
} else
{
    $disable_error_notice = '';
}


if(!isset($raw_tables['tables'][$tables_prefix]['languages']['no_result_found']))
{
    $raw_tables['tables'][$tables_prefix]['languages']['no_result_found'] = 'No results found...!';
}
if(!isset($raw_tables['tables'][$tables_prefix]['languages']['pull_for_refresh']))
{
    $raw_tables['tables'][$tables_prefix]['languages']['pull_for_refresh'] = 'Pull to refresh...';
}
if(!isset($raw_tables['tables'][$tables_prefix]['languages']['search']))
{
    $raw_tables['tables'][$tables_prefix]['languages']['search'] = 'Search';
}
if(isset($_GET['notice']))
{
    if($_GET['notice'] == 'save')
    {
        $table_content .= '
<script type="text/javascript">
    window.localStorage.clear();
</script>';
    }
}
// TODO: --------|-- OPTION TEXT MESSAGE
$table_content .= '<div class="panel-group" id="custom-messages">';
$table_content .= '<div class="panel panel-default">';
$table_content .= '<div class="panel-heading">';
$table_content .= '<a data-toggle="collapse" data-parent="#custom-messages" href="#body-messages"><h5 class="panel-title"><span></span> Custom Messages</h5></a>';
$table_content .= '</div>';
$table_content .= '<div id="body-messages" class="panel-collapse collapse">';
$table_content .= '<div class="panel-body">';
$table_content .= '<div class="row">';
$table_content .= '<div class="col-md-6">';
$table_content .= $bs->FormGroup('tables[languages][retrieval_error_title]','default','text','Error Network Title','Error Network','&nbsp;','required','8',$raw_tables['tables'][$tables_prefix]['languages']['retrieval_error_title']);
$table_content .= '</div>';
$table_content .= '<div class="col-md-6">';
$table_content .= $bs->FormGroup('tables[languages][retrieval_error_content]','default','text','Error Network Content','An error occurred while collecting data.','&nbsp;','required','8',$raw_tables['tables'][$tables_prefix]['languages']['retrieval_error_content']);
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '<div class="row">';
$table_content .= '<div class="col-md-6">';
$table_content .= $bs->FormGroup('tables[languages][no_result_found]','default','text','No results found...','No results found...!','&nbsp;','required','8',$raw_tables['tables'][$tables_prefix]['languages']['no_result_found']);
$table_content .= '</div>';
$table_content .= '<div class="col-md-6">';
$table_content .= $bs->FormGroup('tables[languages][pull_for_refresh]','default','text','Pull to refresh...','Pull to refresh...','&nbsp;','required','8',$raw_tables['tables'][$tables_prefix]['languages']['pull_for_refresh']);
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '<div class="row">';
$table_content .= '<div class="col-md-6">';
$table_content .= $bs->FormGroup('tables[languages][search]','default','text','Search','Search','&nbsp;','required','8',$raw_tables['tables'][$tables_prefix]['languages']['search']);
$table_content .= '</div>';
$table_content .= '<div class="col-md-6">';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '<div class="row">';
//$table_content .= '<div class="col-md-6">';
//$table_content .= $bs->FormGroup('tables[languages][disable_error_notice]', 'default', 'checkbox', '', 'Disable error message', null, $disable_error_notice, '8', 'true');
//$table_content .= '</div>';
$table_content .= '<div class="col-md-6">';
$table_content .= $bs->FormGroup('tables[languages][error_messages]','default','checkbox','','Show Error (Debug)',null,$error_messages,'8','true');
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
// TODO: --------|-- OPTION GMAP
if(!isset($raw_tables['tables'][$tables_prefix]['option']['gmap']['api_key']))
{
    $raw_tables['tables'][$tables_prefix]['option']['gmap']['api_key'] = '';
}
if(!isset($raw_tables['tables'][$tables_prefix]['option']['gmap']['center_map']))
{
    $raw_tables['tables'][$tables_prefix]['option']['gmap']['center_map'] = '48.85693,2.3412';
}
$table_content .= '<div class="panel-group" id="custom-option">';
$table_content .= '<div class="panel panel-default">';
$table_content .= '<div class="panel-heading">';
$table_content .= '<a data-toggle="collapse" data-parent="#custom-option" href="#body-option"><h5 class="panel-title"><span></span> Option</h5></a>';
$table_content .= '</div>';
$table_content .= '<div id="body-option" class="panel-collapse collapse">';
$table_content .= '<div class="panel-body">';
$table_content .= '<div class="row">';
$table_content .= '<div class="col-md-6">';
$table_content .= '<h4 class="panel-title">Google Maps</h4>';
$table_content .= $bs->FormGroup('tables[option][gmap][api_key]','default','text','API Key','AIzaSyAJsxVTxzihZfoOVjwbFjyxMvNyk7uEw0s','Read <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">Google Documentation</a>','','4',$raw_tables['tables'][$tables_prefix]['option']['gmap']['api_key']);
$table_content .= $bs->FormGroup('tables[option][gmap][center_map]','default','text','Center MAP','48.85693,2.3412','','','8',$raw_tables['tables'][$tables_prefix]['option']['gmap']['center_map']);
$table_content .= '</div>';
$table_content .= '<div class="col-md-6">';
$table_content .= '<h4 class="panel-title">Youtube</h4>';
if(!isset($raw_tables['tables'][$tables_prefix]['option']['youtube']['api_key']))
{
    $raw_tables['tables'][$tables_prefix]['option']['youtube']['api_key'] = '';
}
$table_content .= $bs->FormGroup('tables[option][youtube][api_key]','default','text','API Key','AIzaSyAJsxVTxzihZfoOVjwbFjyxMvNyk7uEw0s','Read <a target="_blank" href="https://console.developers.google.com/apis/api/youtube/">Google Documentation</a>','','4',$raw_tables['tables'][$tables_prefix]['option']['youtube']['api_key']);
//$table_content .= $bs->FormGroup('tables[option][youtube][center_map]', 'default', 'text', 'Center MAP', '48.85693,2.3412', '', '', '8', $raw_tables['tables'][$tables_prefix]['option']['youtube']['center_map']);
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
// TODO: --------|-- OPTION AUTHENTICATION
if(!isset($raw_tables['tables'][$tables_prefix]['auth']['consumer_key']))
{
    $raw_tables['tables'][$tables_prefix]['auth']['consumer_key'] = '';
}
if(!isset($raw_tables['tables'][$tables_prefix]['auth']['consumer_secret']))
{
    $raw_tables['tables'][$tables_prefix]['auth']['consumer_secret'] = '';
}
if(!isset($raw_tables['tables'][$tables_prefix]['auth']['type']))
{
    $raw_tables['tables'][$tables_prefix]['auth']['type'] = 'none';
}
$auth_types[] = array('value' => 'none','label' => 'None');
$auth_types[] = array('value' => 'basic','label' => 'HTTP Basic Auth');
$auth_types[] = array('value' => 'x-basic','label' => 'X - HTTP Basic Auth');

//$auth_types[] = array('value' => 'restapi-jwt-auth', 'label' => 'WP - JWT Auth for RESTAPI');
foreach($auth_types as $auth_type)
{
    $_auth_types[] = $auth_type;
}
$table_content .= '<div class="panel-group" id="custom-auth">';
$table_content .= '<div class="panel panel-default">';
$table_content .= '<div class="panel-heading">';
$table_content .= '<a data-toggle="collapse" data-parent="#custom-auth" href="#body-auth"><h5 class="panel-title"><span></span> Authentication</h5></a>';
$table_content .= '</div>';
$table_content .= '<div id="body-auth" class="panel-collapse collapse">';
$table_content .= '<div class="panel-body">';
$table_content .= '
<h5>Woocommerce</h5>
<blockquote class="blockquote">
<p>Authentication over HTTPS, You may use HTTP Basic Auth by providing the REST API Consumer Key as the username and the REST API Consumer Secret as the password, you can use it only for SSL Website.</p>
<footer>Source <a title="Woo REST API" href="https://woothemes.github.io/woocommerce-rest-api-docs/#authentication-over-http">Woo REST API</a></footer>
</blockquote>';
$table_content .= '
<h5>WordPress</h5>
<blockquote class="blockquote">
<p>Authentication via HTTP, You may use <a href="https://wordpress.org/plugins/application-passwords/">Application Passwords</a> or <a href="https://github.com/WP-API/Basic-Auth">Basic Authentication</a>.</p>
<footer>Source <a title="Woo REST API" href="http://v2.wp-api.org/guide/authentication/">WP REST API</a></footer>
</blockquote>';
$table_content .= $bs->FormGroup('tables[auth][type]','default','select','Type',$_auth_types,'','','4',$raw_tables['tables'][$tables_prefix]['auth']['type']);
$table_content .= $bs->FormGroup('tables[auth][consumer_key]','default','text','Consumer Key','ck_3f2f53bbcd24db8d6ae83791111d7917777e0369','','','4',$raw_tables['tables'][$tables_prefix]['auth']['consumer_key']);
$table_content .= $bs->FormGroup('tables[auth][consumer_secret]','default','text','Consumer Secret','cs_9daefa4a38ef5f93c501f8facadf3db3696013fc','','','8',$raw_tables['tables'][$tables_prefix]['auth']['consumer_secret']);
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
// TODO: --------|-- OPTION HEADER
$table_content .= '<div class="panel-group" id="custom-http-headers">';
$table_content .= '<div class="panel panel-default">';
$table_content .= '<div class="panel-heading">';
$table_content .= '<a data-toggle="collapse" data-parent="#custom-http-headers" href="#body-http-headers"><h5 class="panel-title"><span></span> Custom HTTP Header</h5></a>';
$table_content .= '</div>';
$table_content .= '<div id="body-http-headers" class="panel-collapse collapse">';
$table_content .= '<div class="panel-body">';
if(!isset($raw_tables['tables'][$tables_prefix]['http_header'][0]['var']))
{
    $raw_tables['tables'][$tables_prefix]['http_header'][0]['var'] = '';
}
if(!isset($raw_tables['tables'][$tables_prefix]['http_header'][1]['var']))
{
    $raw_tables['tables'][$tables_prefix]['http_header'][1]['var'] = '';
}
if(!isset($raw_tables['tables'][$tables_prefix]['http_header'][2]['var']))
{
    $raw_tables['tables'][$tables_prefix]['http_header'][2]['var'] = '';
}

if(!isset($raw_tables['tables'][$tables_prefix]['http_header'][0]['val']))
{
    $raw_tables['tables'][$tables_prefix]['http_header'][0]['val'] = '';
}
if(!isset($raw_tables['tables'][$tables_prefix]['http_header'][1]['val']))
{
    $raw_tables['tables'][$tables_prefix]['http_header'][1]['val'] = '';
}
if(!isset($raw_tables['tables'][$tables_prefix]['http_header'][2]['val']))
{
    $raw_tables['tables'][$tables_prefix]['http_header'][2]['val'] = '';
}
 

$table_content .= '<div class="table-responsive">';
$table_content .= '<table class="table table-striped">';
$table_content .= '<thead>';
$table_content .= '<tr>';
$table_content .= '<td>';
$table_content .= 'Variables';
$table_content .= '</td>';
$table_content .= '<td>';
$table_content .= 'Values';
$table_content .= '</td>';
$table_content .= '</tr>';
$table_content .= '</thead>';
$table_content .= '<tbody>';
$table_content .= '<tr>';
$table_content .= '<td>';
$table_content .= $bs->FormGroup('tables[http_header][0][var]','default','text',' ','Accept','','','4',$raw_tables['tables'][$tables_prefix]['http_header'][0]['var']);
$table_content .= '</td>';
$table_content .= '<td>';
$table_content .= $bs->FormGroup('tables[http_header][0][val]','default','text',' ','application/json, text/plain, */*','','','8',$raw_tables['tables'][$tables_prefix]['http_header'][0]['val']);
$table_content .= '</td>';
$table_content .= '</tr>';

$table_content .= '<tr>';
$table_content .= '<td>';
$table_content .= $bs->FormGroup('tables[http_header][1][var]','default','text',' ','Authorization','','','4',$raw_tables['tables'][$tables_prefix]['http_header'][1]['var']);
$table_content .= '</td>';
$table_content .= '<td>';
$table_content .= $bs->FormGroup('tables[http_header][1][val]','default','text',' ','Bearer WTlzHMbdIz9A7qOVI8wgFurm4IOGvGtR','','','8',$raw_tables['tables'][$tables_prefix]['http_header'][1]['val']);
$table_content .= '</td>';
$table_content .= '</tr>';

$table_content .= '<tr>';
$table_content .= '<td>';
$table_content .= $bs->FormGroup('tables[http_header][2][var]','default','text',' ','Referer','','','4',$raw_tables['tables'][$tables_prefix]['http_header'][2]['var']);
$table_content .= '</td>';
$table_content .= '<td>';
$table_content .= $bs->FormGroup('tables[http_header][2][val]','default','text',' ','http://google.com/','','','8',$raw_tables['tables'][$tables_prefix]['http_header'][2]['val']);
$table_content .= '</td>';
$table_content .= '</tr>';

$table_content .= '</tbody>';
$table_content .= '</table>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
$table_content .= '</div>';
if(!isset($raw_tables['tables'][$tables_prefix]['error']['title']))
{
    $raw_tables['tables'][$tables_prefix]['error']['title'] = null;
}
if($raw_tables['tables'][$tables_prefix]['error']['title'] != null)
{
    $table_content .= $bs->Modal('table_validation',$raw_tables['tables'][$tables_prefix]['error']['title'],$raw_tables['tables'][$tables_prefix]['error']['content'],'md','','Close',false);
    $footer .= '<script type="text/javascript">$("#table_validation").modal();</script>';
}

$button[] = array(
    'name' => 'table-save',
    'label' => 'Save Table &raquo;',
    'tag' => 'submit',
    'color' => 'primary table-save-again ');
if($_GET['parent'] != '')
{
    $button[] = array(
        'label' => 'Edit Page (list)',
        'icon' => 'glyphicon glyphicon glyphicon-pencil',
        'tag' => 'anchor',
        'color' => 'success',
        'link' => "./?page=page&prefix=".str2var($_GET['parent']));
}
if($_GET['prefix'] != '')
{
    $button[] = array(
        'label' => 'Edit Page (single)',
        'icon' => 'glyphicon glyphicon glyphicon-pencil',
        'tag' => 'anchor',
        'color' => 'info',
        'link' => "./?page=page&prefix=".str2var($_GET['prefix']).'_singles');
}
$button[] = array(
    'label' => 'Reset',
    'tag' => 'reset',
    'color' => 'warning');
if($_GET['prefix'] != '')
{
    $button[] = array(
        'label' => 'Delete',
        'icon' => 'glyphicon glyphicon glyphicon-trash',
        'tag' => 'anchor',
        'color' => 'danger delete-this-table',
        'link' => "./?page=tables&delete=".str2var($_GET['prefix']));
}
$table_content .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,$button));


$content = null;
// TODO: -- HEADING
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-database fa-stack-1x"></i></span>(IMAB) Tables</h4>';
$content .= notice();
$content .= $bs->Forms('app-setup','','post','default',$table_content);
$content .= '<div id="column_preview"></div>';
$content .= '<div id="content_validation"></div>';
$icon = new jsmIonicon();
$modal_dialog = $icon->display();
$content .= $bs->Modal('icon-dialog','Ionicon Tables',$modal_dialog,'md',null,'Close',null);

//$footer .= '<'.'script src="//ih'.'sana.net/pub/theme.js?no-cache='.base64_encode(JSM_PURCHASE_CODE).'"></script>';
$footer .= '
<script type="text/javascript">
$(document).ready(function(){
    $(".column-type").on("click",function(){
        var _val = $(this).val();
        var _target = $(this).attr("data-helper");
        switch(_val) {
         case "text":
            $(_target).html("HTML<br/><strong>eg</strong>: &lt;i class=&quot;icon ion-heart&quot;&gt;&lt;/i&gt; [txt]");
          break;
         default:
            $(_target).html("text");
            break;
        }
    });
});    
</script>
';
$guides[] = array(
    'target' => '#group_table-current',
    'pos' => 'bottom',
    'text' => 'Please select current Table or create new table');
$guides[] = array(
    'target' => '#group_tables-cols',
    'pos' => 'bottom',
    'text' => 'How much column do you need?');
$guides[] = array(
    'target' => '#group_tables_title_',
    'pos' => 'bottom',
    'text' => 'Write the name of the table with singular name like post (without s)');
$guides[] = array(
    'target' => '#group_tables_template_',
    'pos' => 'bottom',
    'text' => 'Select the template for the list of items.');
$guides[] = array(
    'target' => '#group_tables_template_single_',
    'pos' => 'bottom',
    'text' => 'Select the template for the detail of item (page single).');
$guides[] = array(
    'target' => '#group_tables_db_type_',
    'pos' => 'bottom',
    'text' => 'Are want create offline or online application?');
$guides[] = array(
    'target' => '#group_tables_db_url_',
    'pos' => 'bottom',
    'text' => 'Write your json link');
$guides[] = array(
    'target' => '#group_column_list_',
    'pos' => 'bottom',
    'text' => 'Fill column information');
$guides[] = array(
    'target' => '#group_column_list_',
    'pos' => 'bottom',
    'text' => 'Fill column information');
$guides[] = array(
    'target' => '#group_tables_sample_data_',
    'pos' => 'bottom',
    'text' => 'Checked sample data');
$guides[] = array(
    'target' => '#table-save',
    'pos' => 'bottom',
    'text' => 'Click Save button');
$guides[] = array(
    'target' => '#group_tables_parent_',
    'pos' => 'bottom',
    'text' => 'Select overwrite data');
$guides[] = array(
    'target' => '.table-save-again',
    'pos' => 'bottom',
    'text' => 'Click Save button again');

if(!isset($_SESSION['PROJECT']['page']))
{
    $_SESSION['PROJECT']['page'] = array();
}
$_current_pages = array();
if(is_array($_SESSION['PROJECT']['page']))
{
    foreach($_SESSION['PROJECT']['page'] as $current_page)
    {
        if(!isset($current_page['priority']))
        {
            $current_page['priority'] = 'danger';
        }
        $var = $current_page['prefix'];
        $_current_pages[$var] = $current_page['priority'];
    }
}
$footer .= '<script type="text/javascript">var current_pages = '.json_encode($_current_pages);
if(JSM_DEBUG == true)
{
    $footer .= ';console.log(current_pages);';
}
$footer .= '
$("#app-setup").on("submit",function(e){
    var list_of_pages = "" ; 
    var complicaties_pages = ["none"];
    var page_list = strToLink($("#tables_parent_").val()) ;
    var page_singles = strToLink( $("#tables_title_").val()) + "_singles" ;
    complicaties_pages.push(page_list); 
    complicaties_pages.push(page_singles); 
    console.log(complicaties_pages);
    for(var i=0;i<complicaties_pages.length;i++){
        var page = complicaties_pages[i] ;
        if(current_pages[page]){
            list_of_pages += "\\r\\n\\t- page `" + page + "` = risk " + current_pages[page] ; 
        }
    }
    var notice = "" ; 
    notice += "This action can potentially break pages as follow:\\r\\n" + list_of_pages + "\\r\\n\\r\\n" ; 
    notice += "Are you sure you want to overwrite this page?"  ;
    return confirm(notice);
});

$(".delete-this-table").on("click",function(e){
    var notice = "" ; 
    notice += "Are you sure you want to delete this tables?"  ;
    return confirm(notice);
});



function checkValidate(){
    $("#tables_db_url_single_").attr("required",null);
    $("#tables_db_url_").attr("required",null);
        
    var db_url_dinamic = $("#tables_db_url_dinamic_:checked").length;
    var db_type = $("#tables_db_type_").val();
    
    if(db_url_dinamic == true && db_type == "online" ){
        $("#tables_db_url_single_").attr("required","required");
        $("#tables_db_url_").attr("required","required");
    }    
    
    if(db_url_dinamic == true && db_type == "offline" ){
        $("#tables_db_url_single_").attr("required",null);
        $("#tables_db_url_").attr("required","required");
    }    
} 

$("#tables_db_url_dinamic_,#tables_db_type_,#tables_db_url_single_,#tables_db_url_").on("click",function(){
    //checkValidate();
});

//checkValidate();
';
$footer .= '</script>';
$dinamic_query = null;
if(!isset($raw_tables['tables'][$tables_prefix]['db_url_dinamic']))
{
    $raw_tables['tables'][$tables_prefix]['db_url_dinamic'] = '';
}
if($raw_tables['tables'][$tables_prefix]['db_url_dinamic'] != '')
{
    parse_str(parse_url($raw_tables['tables'][$tables_prefix]['db_url'],PHP_URL_QUERY),$urlparams);
    $default_value = array_values($urlparams);

    if(isset($default_value[0]))
    {
        if(!is_array($default_value[0]))
        {
            $dinamic_query = '/'.$default_value[0];
        } else
        {
            $dinamic_query = '/1';
        }

    } else
    {
        $dinamic_query = '/1';
    }
}
$template->page_guide = GuideMarkup($guides);
$template->demo_url = $out_path.'/www/#/'.$subpage_path.'/'.$_GET['parent'].$dinamic_query;
$template->title = $template->base_title.' | '.'Tables';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;

?>