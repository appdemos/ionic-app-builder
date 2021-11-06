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

if (isset($_GET['prefix']))
{
    if ($_GET['action'] == 'delete')
    {
        if ($_GET['prefix'] == 'all')
        {
            foreach (glob("projects/" . $file_name . "/page.*.save") as $all_file)
            {
                @unlink($all_file);
            }
            header('Location: ./?page=h-recovery-and-issue');
        }
    }
    $oem_prefix = explode('.json.', $_GET['prefix']);
    $backup_json = 'projects/' . $file_name . '/' . basename($_GET['prefix']);

    if (isset($oem_prefix[1]))
    {
        switch ($_GET['action'])
        {
            case 'delete':

                if (file_exists($backup_json))
                {
                    @unlink($backup_json);
                }

                header('Location: ./?page=h-recovery-and-issue');
                break;
            case 'restore':
                $restore_json = 'projects/' . $file_name . '/' . $oem_prefix[0] . '.json';
                @unlink($restore_json);
                @copy($backup_json, $restore_json);
                //@unlink($backup_json);

                buildIonic($file_name);
                header('Location: ./?page=h-recovery-and-issue');
                break;
        }
    }
}

$content = null;
$footer = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-wrench fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) Recovery and Fix Issue</h4>';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-body">';
$content .= '<h4>Tables</h4>';
$content .= '<table class="table table-striped">';
$content .= '<thead>';
$content .= '<tr>';
$content .= '<th>Table Name</th>';
$content .= '<th>Page Target</th>';
$content .= '<th>Error</th>';
$content .= '<th>Goto</th>';
$content .= '</tr>';
$content .= '</thead>';
$content .= '<tbody>';
if (isset($_SESSION['PROJECT']['tables']))
{
    foreach ($_SESSION['PROJECT']['tables'] as $tables)
    {
        $check_tables[$tables['parent']][] = $tables['parent'];
    }

    foreach ($_SESSION['PROJECT']['tables'] as $tables)
    {
        $fix_pages = '-';
        $issues = '<ul class="list-unstyled">';
        $parent = $tables['parent'];
        $is_parent_broken = '';
        if (!file_exists('projects/' . $file_name . '/page.' . $parent . '.json'))
        {
            $is_parent_broken = ' (<span class="text-danger">error</span>)';
            $issues .= '<li><span class="label label-warning">Only notice: the target page does not exist, maybe will damage the ngcontroller.</span></li>';
        }
        $content .= '<tr>';
        $content .= '<td>' . $tables['title'] . '</td>';
        $content .= '<td>' . $parent . $is_parent_broken . '</td>';

        if (count($check_tables[$parent]) > 1)
        {
            $issues .= '<li><span class="label label-danger">Page target already used by other table, please delete one of them or create new page as target, then re-save correctly table.</span></li>';
            $fix_pages = '<a class="btn btn-danger btn-sm" target="_blank" href="./?page=tables&prefix=' . $tables['prefix'] . '&parent=' . $tables['parent'] . '">Tables</a>';
        }

        if (!isset($tables['error']['title']))
        {
            $tables['error']['title'] = '';
        }

        if ($tables['parent'] != "")
        {
            $items_list_json = 'projects/' . $file_name . '/page.' . $tables['parent'] . '.json';
            if (file_exists($items_list_json))
            {
                $detail_page = json_decode(file_get_contents($items_list_json), true);
                if (!isset($detail_page['page'][0]['last_edit_by']))
                {
                    $detail_page['page'][0]['last_edit_by'] = '';
                }
                if ($detail_page['page'][0]['last_edit_by'] == 'menu')
                {
                    $issues .= '<li><span class="label label-danger">Page has been overwritten by menu, please save table again.</span></li>';
                    $fix_pages = '<a class="btn btn-primary btn-sm" target="_blank" href="./?page=tables&prefix=' . $tables['prefix'] . '&parent=' . $tables['parent'] . '"><i class="fa fa-link"></i> Tables</a>';
                }
            } else
            {

            }
        }

        $issues .= '<ul>';

        $content .= '<td>' . $issues . '</td>';
        $content .= '<td>' . $fix_pages . '</td>';
        $content .= '</tr>';
    }
}
$content .= '</tbody>';
$content .= '</table>';
$content .= '<a class="btn btn-success pull-right" href="./?page=h-recovery-and-issue&">Refresh</a>';
$content .= '</div>';
$content .= '</div>';

$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-body">';
$content .= '<h4>Menu and Popover Menu</h4>';
$content .= '<table class="table table-striped">';
$content .= '<thead>';
$content .= '<tr>';
$content .= '<th>Menu</th>';
$content .= '<th>Name</th>';
$content .= '<th>Link</th>';
$content .= '<th>Errors</th>';
$content .= '<th>Goto</th>';
$content .= '</tr>';
$content .= '</thead>';
$content .= '<tbody>';
if (isset($_SESSION['PROJECT']['menu']))
{
    foreach ($_SESSION['PROJECT']['menu']['items'] as $items)
    {
        $issues = '-';
        if ($items['type'] == 'link')
        {
            $broken_link = true;
            foreach ($_SESSION['PROJECT']['page'] as $page)
            {
                if (!isset($page['query_value']))
                {
                    $page['query_value'] = '1';
                }

                if ($items['var'] === $page['prefix'])
                {
                    $broken_link = false;
                }

            }
            if ($broken_link == true)
            {
                $issues = '<span class="text-danger">Broken Link</span>';
            }
            $content .= '<tr>';
            $content .= '<td><span class="label label-success">' . $_SESSION['PROJECT']['menu']['type'] . '</span></td>';
            $content .= '<td>' . $items['label'] . '</td>';
            $content .= '<td>' . $items['var'] . '</td>';
            $content .= '<td>' . $issues . '</td>';
            $content .= '<td><a class="btn btn-sm btn-primary" href="./?page=menu"><i class="fa fa-link"></i>  Menu</a></td>';
            $content .= '</tr>';

        }

    }
}

if (isset($_SESSION['PROJECT']['popover']))
{
    if (!is_array($_SESSION['PROJECT']['popover']['menu']))
    {
        $_SESSION['PROJECT']['popover']['menu'] = array();
    }
    foreach ($_SESSION['PROJECT']['popover']['menu'] as $popover)
    {
        $issues = '-';
        if ($popover['type'] == 'link')
        {
            $broken_link = true;
            foreach ($_SESSION['PROJECT']['page'] as $page)
            {
                if (!isset($page['query_value']))
                {
                    $page['query_value'] = '1';
                }
                if ($popover['link'] === '#/' . $file_name . '/' . $page['prefix'])
                {
                    $broken_link = false;
                }
                if ($popover['link'] === '#/' . $file_name . '/' . $page['prefix'] . '/' . $page['query_value'])
                {
                    $broken_link = false;
                }

            }
            if ($broken_link == true)
            {
                $issues = '<span class="label label-danger">Broken Link</span>';
            }

            $content .= '<tr>';
            $content .= '<td><span class="label label-info">popover</span></td>';
            $content .= '<td>' . $popover['title'] . '</td>';
            $content .= '<td>' . $popover['link'] . '</td>';
            $content .= '<td>' . $issues . '</td>';
            $content .= '<td><a class="btn btn-sm btn-primary" href="./?page=popover"><i class="fa fa-link"></i>  Popover</a></td>';
            $content .= '</tr>';
        }

    }
}
$content .= '</tbody>';
$content .= '</table>';

$content .= '</div>';
$content .= '</div>';


$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-body">';
$content .= '<h4>Page Register</h4>';

$content .= '<table class="table table-striped">';
$content .= '<thead>';
$content .= '<tr>';
$content .= '<th>Pages</th>';
$content .= '<th>Menu Type</th>';
$content .= '<th>Change</th>';
$content .= '</tr>';
$content .= '</thead>';
$content .= '<tbody>';

$menutype[] = 'Error';
if ($_SESSION['PROJECT']['menu']['type'] == 'tabs')
{
    $menutype[] = 'tabs';
    $menutype[] = 'tabs-custom';
    $menutype[] = 'sub-tabs';
} else
{
    $menutype[] = 'side_menus';
    $menutype[] = 'side_menus-custom';
    $menutype[] = 'sub-side_menus';
}

if (isset($_GET['change']))
{
    if (isset($_GET['menutype']))
    {
        $page_prefix = $_GET['page_prefix'];
        $new_page = json_decode(file_get_contents('projects/' . $file_name . '/page.' . $page_prefix . '.json'), true);

        $new_page['page'][0]['menutype'] = $_GET['menutype'];
        file_put_contents('projects/' . $file_name . '/page.' . $page_prefix . '.json', json_encode($new_page));
        buildIonic($file_name);
        header('Location: ./?page=h-recovery-and-issue');
    }
}
foreach ($_SESSION['PROJECT']['page'] as $_pg)
{
    $content .= '<form action="" method="get" >';
    $content .= '<tr>';
    $content .= '<td>' . $_pg['prefix'] . '</td>';
    $content .= '<td><code>' . $_pg['menutype'] . '</code></td>';
    $content .= '<td>';
    $content .= '<input name="page" type="hidden" value="h-recovery-and-issue" />';
    $content .= '<input name="page_prefix" type="hidden" value="' . $_pg['prefix'] . '" />';
    $content .= '<select class="form-control" name="menutype">';
    foreach ($menutype as $_menutype)
    {
        $selected = '';
        if ($_pg['menutype'] == $_menutype)
        {
            $selected = 'selected';
        }
        $content .= '<option value="' . $_menutype . '" ' . $selected . '>' . $_menutype . '</option>';
    }
    $content .= '</select>';
    $content .= '</td>';

    $content .= '<td>';
    $content .= '<input class="btn btn-sm btn-danger" name="change" type="submit" value="Change" />';
    $content .= '</td>';

    $content .= '<td>';
    $content .= '<a target="_blank" class="btn btn-sm btn-success" href="./?page=page&prefix=' . $_pg['prefix'] . '">Go to Page</a>';
    $content .= '</td>';


    $content .= '</tr>';
    $content .= '</form>';
}
$content .= '</tbody>';
$content .= '</table>';
$content .= '</div>';
$content .= '</div>';


$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-body">';
$content .= '<h4>Page Recovery</h4>';
$content .= '<a class="btn btn-danger btn-sm" href="./?page=h-recovery-and-issue&prefix=all&action=delete">Delete All</a>';

$content .= '<table class="table table-striped">';
$content .= '<thead>';
$content .= '<tr>';
$content .= '<th>Name</th>';
$content .= '<th>Time</th>';
$content .= '<th>Info</th>';
$content .= '<th>Action</th>';
$content .= '</tr>';
$content .= '</thead>';
$content .= '<tbody>';
$recovery = glob("projects/" . $file_name . "/page.*.save");
array_multisort(array_map('filemtime', $recovery), SORT_NUMERIC, SORT_DESC, $recovery);
foreach ($recovery as $save_page_file)
{
    $_page = json_decode(file_get_contents($save_page_file), true);
    if (isset($_page['page'][0]))
    {
        if (!isset($_page['page'][0]['js']))
        {
            $_page['page'][0]['js'] = null;
        }
        $costum_control = '';
        if (strlen($_page['page'][0]['js']) > 2)
        {
            $costum_control = '<span>ngcontroller by user</span>';
        }
        $explode_time = explode('.json.', $save_page_file);
        $exp_time = str_replace('.save', '', $explode_time[1]);
        $content .= '<tr>';
        $content .= '<td>' . $_page['page'][0]['title'] . '</td>';

        $content .= '<td>' . date("Y-m-d H:i:s", $exp_time) . '</td>';
        $content .= '<td>' . $costum_control . '</td>';
        $content .= '<td><a class="btn btn-danger btn-sm" href="./?page=h-recovery-and-issue&prefix=' . basename($save_page_file) . '&action=delete">Delete</a> ';
        $content .= '<a class="btn btn-warning btn-sm" href="./?page=h-recovery-and-issue&prefix=' . basename($save_page_file) . '&action=restore">Restore</a> </td>';
        $content .= '</tr>';
    }
}
$content .= '</tbody>';
$content .= '</table>';
$content .= '</div>';
$content .= '</div>';

$out_path = 'output/' . $file_name;
$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Helper Tools -&raquo; Recovery and Fix Issue';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = true;

?>