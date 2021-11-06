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

$file_name = 'test';
if (!isset($_GET['prefix']))
{
    $_GET['prefix'] = null;
}
$prefix_json = $_GET['prefix'];

$footer = null;
$bs = new jsmBootstrap();
$form_input = $html = null;
if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}
if (!isset($_SESSION["PROJECT"]['menu']))
{
    header('Location: ./?page=menu&err=new');
    die();
}

$out_path = 'output/' . $file_name;
$content = null;


if (!isset($_GET['act']))
{
    $_GET['act'] = 'list';
}

$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-database fa-stack-1x"></i></span>Backend Tools -&raquo; (IMAB) JSON Editor (Offline Data)</h4>';
$content .= '<blockquote class="blockquote blockquote-danger">
<h4>' . __('The rules that apply are:') . '</h4>
<ul>
<li>' . __('This features is used for edit JSON File for offline app.') . '</li>
<li>' . __('Not support column contain characters:  <code>.</code>, <code>:</code>, <code>\'</code> or <code>[]</code> in variable name') . '</li>
<li>' . __('Press <code>PUSH to Project</code> before look preview project') . '</li>
 </ul>
</blockquote>';


$app_tables = $_SESSION['PROJECT']['tables'];

if ($prefix_json != '')
{
    if (isset($_SESSION['PROJECT']['tables'][$prefix_json]['sample_data']))
    {
        if ($_SESSION['PROJECT']['tables'][$prefix_json]['sample_data'] == 'true')
        {
            $msg_notice = __('JSON File clashed with Example Data, Go to <code>Table Menu</code> then unchecked <code>Generate JSON Files</code>');
            $content .= $bs->Modal('error-modal', __('Ops! JSON File clashed'), $msg_notice, 'md', null, 'Close', false);
        }
    }
}


$content .= notice();

// TODO: PUSH JSON
if (isset($_GET['push']))
{
    $dir_json = 'projects/' . $file_name . "/tables/";
    $file_json = $dir_json . $prefix_json . '.json';
    $out_json = $out_path . '/www/data/tables/';
    if (!file_exists($out_json))
    {
        @mkdir($out_json, 0777, true);
    }

    $new_file = $out_json . '/' . $prefix_json . ".json";
    $arr_items = json_decode(file_get_contents($file_json), true);
    $new_items = array();

    foreach ($arr_items as $arr_item)
    {
        $new_items[] = $arr_item;
    }
    file_put_contents($file_json, json_encode($new_items));

    if (@copy($file_json, $new_file))
    {
    }
}

switch ($_GET['act'])
{
        // TODO: LIST JSON
    case 'list':
        $content .= '<ul class="nav nav-tabs">';
        $content .= '<li class="active"><a href="#" >' . __('List') . '</a></li>';
        $content .= '<li><a href="./?page=z-json&act=import" >' . __('Import') . '</a></li>';
        $content .= '</ul>';

        $content .= '<br/>';

        $content .= '<div class="tab-content">';
        $content .= '<div class="tab-pane active" id="export">';

        $content .= '<table class="table table-striped">';
        $content .= '<thead>';
        $content .= '<tr>';
        $content .= '<th>' . __('Tables') . '</th>';
        $content .= '<th>' . __('Available') . '</th>';
        $content .= '<th>' . __('Download') . '</th>';
        $content .= '<th>' . __('Action') . '</th>';
        $content .= '</tr>';
        $content .= '</thead>';

        $content .= '<tbody>';
        $file_target = array();

        foreach ($app_tables as $app_table)
        {
            $table_path = 'projects/' . $file_name . '/tables/' . $app_table['prefix'] . '.json';
            $download = $action = '';
            if (file_exists($table_path))
            {
                $available = '<a class="btn btn-success btn-xs">' . __('Available') . '</a>';
                $download = '
                <div class="btn-group btn-group-xs">
                <a target="_blank" href="./?page=z-json&act=download&prefix=' . $app_table['prefix'] . '&type=json" class="btn btn-xs btn-primary">JSON</a>
                <a target="_blank" href="./?page=z-json&act=download&prefix=' . $app_table['prefix'] . '&type=csv" class="btn btn-xs btn-success">CSV</a>
                <a target="_blank" href="./?page=z-json&act=download&prefix=' . $app_table['prefix'] . '&type=csv-xls" class="btn btn-xs btn-warning">CSV (MS Excel)</a>
                </div>
                     ';
                $action = '
                <a href="./?page=z-json&act=edit&prefix=' . $app_table['prefix'] . '" class="btn btn-warning"><span class="fa fa-pencil"></span> ' . __('Edit') . '</a>
                <a href="./?page=z-json&act=list&prefix=' . $app_table['prefix'] . '&push" class="btn btn-success"><span class="fa fa-ils"></span> ' . __('Push To Project') . '</a>
                <a href="./?page=z-json&act=trash&prefix=' . $app_table['prefix'] . '" class="btn btn-xs btn-danger"><span class="fa fa-trash"></span> ' . __('Delete') . '</a>
                ';
            } else
            {
                $available = '
                <a class="btn btn-xs btn-warning" href="./?page=z-json&act=create&prefix=' . $app_table['prefix'] . '">' . __('Create') . '</a>
                ';
            }
            $note = '<ul>';
            foreach ($app_table['cols'] as $cols)
            {
                if (preg_match("/\.|\[|\(|\:|\'/", $cols['title']))
                {
                    $note .= '
                     
                    <li>
                    <span class="label label-danger">' . __('disable') . '</span> : ' . __('Column contain character:') . ' <code>.</code>, <code>:</code>, <code>\'</code> ' . __('or') . ' <code>[]</code>. ' . __('It is found in variable column:') . ' <code>' . htmlentities($cols['title']) . '</code> ' . __('is not compatible, replace with') . ' <code>' . str_replace(array(
                        '[',
                        ']',
                        '(',
                        ')',
                        '.'), '_', $cols['title']) . '</code> ' . __('in (IMAB) Table Menu') . '</li>
                     ';
                    $action = $available = $download = null;

                }
            }
            $note .= '</ul>';

            $content .= '
                <tr>
                    <td style="width: 50%;">
                        <strong>' . strtoupper($app_table['title']) . '</strong>' . $note . ' 
                    </td>
                    
                    <td>
                        ' . $available . '
                    </td>
                    
                    <td>
                        ' . $download . '
                     </td>
                    <td>
                            ' . $action . '
                    </td>                     
                </tr>
                ';
        }
        $content .= '</tbody>';
        $content .= '</table>';

        $content .= '</div>';
        $content .= '</div>';

        break;
        // TODO: CREATE JSON
    case 'create':
        $dir_json = 'projects/' . $file_name . "/tables/";
        $file_json = $dir_json . $prefix_json . '.json';
        if (!file_exists($dir_json))
        {
            @mkdir($dir_json, 0777, true);
        }
        $data_arr = array();
        for ($i = 0; $i < 3; $i++)
        {
            foreach ($app_tables[$prefix_json]['cols'] as $cols)
            {
                $var = str2var($cols['title'], false);
                if ($cols['type'] == 'id')
                {
                    $data_arr[$i][$var] = $i;
                } else
                {
                    $data_arr[$i][$var] = sample_data($cols['type']);
                }
            }
        }

        file_put_contents($file_json, json_encode($data_arr));
        header('Location: ./?page=z-json&notice=create&error=null');
        break;
        // TODO: DELETE JSON
    case 'trash':
        $dir_json = 'projects/' . $file_name . "/tables/";
        $file_json = $dir_json . $prefix_json . '.json';
        if (file_exists($file_json))
        {
            unlink($file_json);
        }
        header('Location: ./?page=z-json&notice=delete&error=null');
        break;
        // TODO: DOWNLOAD JSON
    case 'download':
        header('Content-type: text/plain');

        $dir_json = 'projects/' . $file_name . "/tables/";
        $file_json = $dir_json . $prefix_json . '.json';

        if (!isset($_GET['type']))
        {
            $_GET['type'] = 'json';
        }

        $json_data = file_get_contents($file_json);


        switch ($_GET['type'])
        {
            case 'json':
                header('Content-type: application/json');
                header('Content-Disposition: attachment; filename="' . $prefix_json . '.json"');
                echo json_encode(json_decode($json_data, true));
                break;
            case 'csv':
                header('Content-type: text/csv');
                header('Content-Disposition: attachment; filename="' . $prefix_json . '.csv"');
                $arr_json = json_decode($json_data, true);
                foreach ($arr_json as $arr)
                {
                    echo '"' . implode('","', $arr) . '"' . "\r\n";
                }
                break;
            case 'csv-xls':
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="' . $prefix_json . '.csv"');
                $arr_json = json_decode($json_data, true);
                foreach ($arr_json as $arr)
                {
                    echo '"' . implode('";"', $arr) . '"' . "\r\n";
                }
                break;

        }
        die();
        break;

        // TODO: IMPORT JSON
    case 'import':
        $dir_json = 'projects/' . $file_name . "/tables/";

        if (!file_exists($dir_json))
        {
            @mkdir($dir_json, 0777, true);
        }
        $content .= '<ul class="nav nav-tabs">';
        $content .= '<li><a href="./?page=z-json&act=list">' . __('List') . '</a></li>';
        $content .= '<li class="active"><a href="#" >' . __('Import') . '</a></li>';
        $content .= '</ul>';
        $content .= '<br/>';

        $content .= '<div class="tab-pane" id="import">';


        if (isset($_FILES['file_data']))
        {
            $prefix_json = str2var($_POST['file_target']);
            $file_json = $dir_json . $prefix_json . '.json';


            $uploadfile = $dir_json . 'temp_' . sha1(time());
            if (move_uploaded_file($_FILES['file_data']['tmp_name'], $uploadfile))
            {
                $ext = pathinfo($_FILES["file_data"]["name"], PATHINFO_EXTENSION);

                $arr = array();
                switch ($_POST['file_type'])
                {
                    case 'csv':
                        $handle = fopen($uploadfile, "r");
                        while (($data = fgetcsv($handle, 0, ",")) !== false)
                        {
                            $arr[] = $data;
                        }
                        fclose($handle);
                        break;
                    case 'csv-xls':
                        $handle = fopen($uploadfile, "r");
                        while (($data = fgetcsv($handle, 0, ";")) !== false)
                        {
                            $arr[] = $data;
                        }
                        fclose($handle);
                        break;
                    case 'json':
                        $arr = json_decode(file_get_contents($uploadfile), true);
                        break;
                }
                $z = 0;
                $error = false;

                print_r($arr);

                foreach ($arr as $line)
                {
                    $cols = $_SESSION['PROJECT']['tables'][$prefix_json]['cols'];

                    $c = 0;
                    foreach ($cols as $col)
                    {
                        $line = array_values($line);

                        //die($c.'<pre>'.print_r($line,true));

                        if (!isset($line[$c]))
                        {
                            $line[$c] = 'error';
                            $error = true;
                        }
                        $new_arr[$z][str2var($col['title'], false)] = $line[$c];
                        $c++;
                    }
                    $z++;
                }


                unlink($uploadfile);
                if ($error == false)
                {
                    if (!file_exists($file_json))
                    {
                        @file_put_contents($file_json, json_encode($new_arr));
                        header('Location: ./?page=z-json&act=list&err=null&notice=save');
                    } else
                    {
                        header('Location: ./?page=z-json&act=import&err=true&notice=exist');
                    }

                } else
                {
                    header('Location: ./?page=z-json&act=import&err=true&notice=format');
                }

            }
        }


        $file_type[] = array('value' => 'csv-xls', 'label' => 'CSV (Excel or separator ; )');
        $file_type[] = array('value' => 'csv', 'label' => 'CSV (separator ,)');
        $file_type[] = array('value' => 'json', 'label' => 'JSON');

        foreach ($app_tables as $app_table)
        {
            $file_target[] = array('label' => $app_table['title'], 'value' => $app_table['prefix']);
        }

        $button[] = array(
            'name' => 'Upload File',
            'label' => __('Import File') . ' &raquo;',
            'tag' => 'submit',
            'color' => 'primary');

        $content .= '<form action="" method="post" enctype="multipart/form-data">';
        $content .= '<p><span class="label label-danger">note</span> for text using a specific language, you can using utf8 encoding file.</p>';
        $content .= $bs->FormGroup('file_target', 'default', 'select', 'Target', $file_target, null, null);
        $content .= $bs->FormGroup('file_type', 'default', 'select', 'File Type', $file_type, null, null);
        $content .= $bs->FormGroup('file_data', 'default', 'file', 'File', null, "", null, null);
        $content .= $bs->FormGroup(null, 'default', 'html', null, null, '<br/><br/>' . $bs->ButtonGroups(null, $button));
        $content .= '</form>';
        $content .= '</div>';

        break;
        // TODO: EDIT JSON
    case 'edit':
        $dir_json = 'projects/' . $file_name . "/tables/";
        $file_json = $dir_json . $prefix_json . '.json';

        $content .= '<ul class="nav nav-tabs">';
        $content .= '<li><a href="./?page=z-json&act=list">'.__('List').'</a></li>';
        $content .= '<li><a href="./?page=z-json&act=import" >'.__('Import').'</a></li>';
        $content .= '<li class="active"><a href="#" >'.__('Edit').'</a></li>';
        $content .= '</ul>';
        $content .= '<br/>';

        $content .= '<div class="tab-pane" id="edit">';
        $content .= '<a class="btn btn-danger" href="./?page=z-json&act=edit&prefix=' . $prefix_json . '&push"><i class="fa fa-ils"></i> '.__('Push To Project').'</a>';
        $content .= '<a target="_blank" class="btn btn-primary" href="./?page=tables&prefix=' . $prefix_json . '"><i class="fa fa-eye"></i> '.__('Preview').'</a>';
        $content .= '<a target="_blank" class="btn btn-success" href="./?page=z-json&act=download&prefix=' . $prefix_json . '&type=csv-xls"><i class="fa fa-download"></i> '.__('Download').'</a>';

        $datas = json_decode(file_get_contents($file_json), true);

        if (file_exists($file_json))
        {
            $cols = $_SESSION['PROJECT']['tables'][$prefix_json]['cols'];
            $current_datas = $datas;
            $id_key = 'id';
            foreach ($cols as $col)
            {
                if ($col['type'] == 'id')
                {
                    $id_key = str2var($col['title'], false);
                }
            }


            if (isset($_POST['action']))
            {

                $new_datas = $current_datas;
                $item_id = null;
                if (isset($_POST[$id_key]))
                {
                    $item_id = str2var($_POST[$id_key], false);
                }

                switch ($_POST['action'])
                {
                    case 'edit':
                        unset($_POST['action']);

                        $new_datas[$item_id] = $_POST;
                        $new_datas[$item_id][$id_key] = str2var($_POST[$id_key], false);
                        file_put_contents($file_json, json_encode($new_datas));
                        break;
                    case 'delete':
                        unset($_POST['action']);
                        unset($new_datas[$item_id]);
                        file_put_contents($file_json, json_encode($new_datas));
                        break;
                    case 'new':
                        unset($_POST['action']);
                        $new_datas[] = $_POST;
                        file_put_contents($file_json, json_encode($new_datas));

                        $datas = json_decode(file_get_contents($file_json), true);
                        $z = 0;
                        foreach ($datas as $data)
                        {
                            $new_data[$z] = $data;
                            $new_data[$z][$id_key] = $z;
                            $z++;
                        }
                        file_put_contents($file_json, json_encode($new_data));
                        break;
                }
                die(true);
            }


            $content .= "\r\n";
            $content .= '<table class="table table-striped" id="table-data">' . "\r\n";

            foreach ($cols as $col)
            {
                $content .= "\t\t" . '<colgroup></colgroup>' . "\r\n";
            }

            $content .= '<thead>' . "\r\n";
            $content .= '<tr>' . "\r\n";
            foreach ($cols as $col)
            {
                $form = null;
                if ($col['type'] != 'id')
                {
                    $form = '<input id="new-' . str2var($col['title'], false) . '" type="text" class="tabledit-input form-control" />';
                } else
                {
                    $form = '<input id="new-' . str2var($col['title'], false) . '" type="text" class="form-control" readonly/>';
                }
                $content .= '
                <th>
                ' . ucwords(str_replace('_', ' ', $col['title'])) . ' <small>(' . ucwords(str_replace('_', ' ', $col['type'])) . ')</small><br/>
                ' . $form . '
                </th>' . "\r\n";
            }
            $content .= '</tr>' . "\r\n";

            $content .= '</thead>' . "\r\n";

            $content .= '<tbody>' . "\r\n";

            foreach ($datas as $line)
            {
                $new_row = '<tr role="row">' . "\r\n";
                foreach ($cols as $col)
                {
                    $var = str2var($col['title'], false);
                    $type = str2var($col['type']);
                    if (!isset($line[$var]))
                    {
                        $line[$var] = 'undefined';
                    }
                    $val = $line[$var];
                    $new_row .= '<td data-type="' . $type . '" data-target="' . rand(100000, 99999999) . '">' . "\r\n";
                    if ($type == 'id')
                    {
                        $new_row .= $val;
                    } else
                    {
                        $new_row .= htmlentities(htmlentities($val));
                    }
                    $new_row .= '</td>' . "\r\n";
                }
                $new_row .= '</tr>' . "\r\n";

                $content .= $new_row;
            }
            $content .= '</tbody>' . "\r\n";
            $content .= '</table>' . "\r\n";


            $content .= '</div>' . "\r\n";
        }


        $js_column = $js_new_data = null;
        $z = 0;

        foreach ($cols as $col)
        {
            if ($col['type'] != 'id')
            {
                $js_column .= '[' . $z . ',"' . str2var($col['title'], false) . '"],';

                $js_new_data .= "\t\t\t\t\t\t" . 'postdata.' . str2var($col['title'], false) . ' = $("#new-' . str2var($col['title'], false) . '").val();' . "\r\n";
            }
            $z++;
        }

        $footer = '
                    <script src="./templates/default/vendor/jquery-tabledit/jquery.tabledit.min.js"></script>
                    <script type="text/javascript">
                     
                     $("#table-data").Tabledit({
                        url: "./?page=z-json&act=edit&prefix=' . $prefix_json . '&save",
                        restoreButton:false,
                        columns: {
                            identifier: [0, "' . $id_key . '"],
                            editable: [' . $js_column . ']
                        }
                    });
                    
                    
                    var KCFinderTarget = "";
                    var KCFinder = {
                    	callBack: function(e) {
                    	    var path = e.split("' . $file_name . '/www/"); 
                    		$(KCFinderTarget).val(path[1]);
                    	},
                    	open: function(prop_id, file_type) {
                    		KCFinderTarget = prop_id;
                    		var newwindow = window.open("./system/plugin/kcfinder/?type=" + file_type, "File Explorer", "height=480,width=1024");
                    		if (window.focus) {
                    			newwindow.focus()
                    		}
                    	}
                    };
                            
                    $(".tabledit-input").on("click",function(){
                        var findType = $(this).parent().attr("data-type");
                        window.ICON_PICKER = "td[data-target=\'" + $(this).parent().attr("data-target")  + "\'] .tabledit-input";
                        
                        if(findType=="icon"){
		                      $("#icon-dialog").modal();
                        }
                        
                        if(findType=="images"){
                            KCFinder.open(window.ICON_PICKER,"images");
                        }
                        
                    });
                    
                      
                    $(".tabledit-toolbar-column").append("<input id=\'create-row\' type=\'submit\'/ value=\''.__('Add New Item').'\' class=\'btn btn-primary\'>");
                  
                 
                    $("#create-row").on("click",function(){
                        var postdata = {action:"new"};
                        ' . $js_new_data . '
                        $.post("./?page=z-json&act=edit&prefix=' . $prefix_json . '&save",postdata,function(data, status){
                            window.location = "./?page=z-json&act=edit&prefix=' . $prefix_json . '&rand=' . time() . '";
                        });
                        
                    });
                   
                    </script>
                ';
        break;
}

$icon = new jsmIonicon();
$modal_dialog = $icon->display();
$content .= $bs->Modal('icon-dialog', 'Ionicon Tables', $modal_dialog, 'md', null, 'Close', null);

$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Backend Tools -&raquo; JSON Editor (Offline Data)';
$template->base_desc = 'tools';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>