<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */


if (!defined('JSM_EXEC'))
{
    die(':)');
}

if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}
$out_path = 'output/' . $file_name;

$content = $footer = $_content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-question fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) Project Info</h4>';
$_content .= '<h4>..:: TABLES ::..</h4>';
$_content .= '<table class="table table-hover table-bordered" border="1">';
$_content .= '<thead>';
$_content .= '<tr>';
$_content .= '<th>Prefix</th>';
$_content .= '<th>Version</th>';
$_content .= '<th>Page Target</th>';
$_content .= '<th>Source JSON</th>';
$_content .= '<th>URL List Item</th>';
$_content .= '<th>URL Single Item</th>';
$_content .= '<th>Dinamic<br/>on 1st param</th>';
$_content .= '<th>Relation To</th>';
$_content .= '</tr>';
$_content .= '</thead>';
$_content .= '<tbody>';
foreach ($_SESSION["PROJECT"]["tables"] as $app_table)
{
    
    if (!isset($app_table['version']))
    {
        $app_table['version'] = 'Upd.0000000000';
    }
    if (!isset($app_table['db_url_dinamic']))
    {
        $app_table['db_url_dinamic'] = '';
    }
    if(!isset($app_table['relation_to'])){
        $app_table['relation_to'] = '';
    }
    $_content .= '<tr>';
    $_content .= '<td><strong style="color:#' . ( substr( $app_table['version'],11,3)) . ' ">' . $app_table['prefix'] . '</strong></td>';
    $_content .= '<td>' . $app_table['version'] . '</td>';
    $_content .= '<td>' . $app_table['parent'] . '</td>';
    $_content .= '<td>' . $app_table['db_type'] . '</td>';
    $_content .= '<td>' . $app_table['db_url'] . '</td>';
    $_content .= '<td>' . $app_table['db_url_single'] . '</td>';
    $_content .= '<td>' . $app_table['db_url_dinamic'] . '</td>';
    $_content .= '<td>' . $app_table['relation_to'] . '</td>';
    $_content .= '</tr>';
    $app_table['version'] = '?';
    
}
$_content .= '</tbody>';
$_content .= '</table>';

$_content .= '<h4>..:: PAGES ::..</h4>';
$_content .= '<table class="table table-hover table-bordered" border="1">';
$_content .= '<thead>';
$_content .= '<tr>';
$_content .= '<th>Prefix</th>';
$_content .= '<th>Version</th>';
$_content .= '<th>Edit by</th>';
$_content .= '<th>Used for</th>';
$_content .= '<th>Menu Type</th>';
$_content .= '<th>Parent Page</th>';
$_content .= '<th>stateParam</th>';
$_content .= '<th>Lock</th>';
$_content .= '</tr>';
$_content .= '</thead>';
$_content .= '<tbody>';
foreach ($_SESSION["PROJECT"]["page"] as $app_page)
{
    if (!isset($app_page['version']))
    {
        $app_page['version'] = 'Upd.0000000000';
    }
    if (!isset($app_page['last_edit_by']))
    {
        $app_page['last_edit_by'] = '?';
    }
    if (!isset($app_page['query_value']))
    {
        $app_page['query_value'] = '';
    }
    if (!isset($app_page['db_url_dinamic']))
    {
        $app_page['db_url_dinamic'] = '';
    }
    if (!isset($app_page['lock']))
    {
        $app_page['lock'] = '';
    }
    if ($app_page['lock'] == 1)
    {
        $app_page['lock'] = 'on';
    }else{
        $app_page['lock'] = '';
    }
    $_content .= '<tr>';
    $_content .= '<td><strong style="color:#' . ( substr( $app_page['version'],11,3)) . ' "><a target="_blank" href="projects/' .$file_name.'/page.'. $app_page['prefix'] . '.json">' . $app_page['prefix'] . '</a></span></td>';
    $_content .= '<td>' . $app_page['version'] . '</td>';
    $_content .= '<td>' . $app_page['last_edit_by'] . '</td>';
    $_content .= '<td>' . $app_page['for'] . '</td>';
    $_content .= '<td>' . $app_page['menutype'] . '</td>';
    $_content .= '<td>' . $app_page['parent'] . '</td>';

    $_content .= '<td>' . $app_page['db_url_dinamic'] . '/' . $app_page['query_value'] . '</td>';
    $_content .= '<td>' . $app_page['lock'] . '</td>';
    $_content .= '</tr>';
}
$_content .= '</tbody>';
$_content .= '</table>';


$content .= $_content;
file_put_contents($out_path . '/project-info.html', $_content);

$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Helper Tools -&raquo; Project Info';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>