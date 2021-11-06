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
$bs = new jsmBootstrap();
$file_name = 'test';
if (!isset($_GET['prefix']))
{
    $_GET['prefix'] = null;
}
$prefix_json = $_GET['prefix'];
$content = null;
if (!isset($_GET['raw_file']))
{
    $_GET['raw_file'] = '';
}
if ($_GET['raw_file'] != '')
{
    $var_json = $_GET['raw_file'];
    if (isset($_SESSION['PROJECT']['tables'][$var_json]['sample_data']))
    {
        if ($_SESSION['PROJECT']['tables'][$var_json]['sample_data'] == 'true')
        {
            $msg_notice = 'JSON File clashed with Example Data, Go to <code>Table Menu</code> then unchecked <code>Generate JSON Files</code>';
            $content .= $bs->Modal('error-modal', 'Ops! JSON File clashed', $msg_notice, 'md', null, 'Close', false);
        }
    }
}
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
if (!isset($_GET['act']))
{
    $_GET['act'] = 'list';
}
if (file_exists('output/' . $file_name . '/www/data/tables/.json'))
{
    @unlink('output/' . $file_name . '/www/data/tables/.json');
}
$dir_json = 'projects/' . $file_name . "/tables/";
if (!file_exists($dir_json))
{
    @mkdir($dir_json, 0777, true);
}


$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-database fa-stack-1x"></i></span>Backend Tools -&raquo; (IMAB) JSON Raw Editor (Offline Data)</h4>';
$content .= '<blockquote class="blockquote blockquote-danger">
<h4>'.__('The rules that apply are:').'</h4>
<ul>
<li>'.__('This features is used for edit JSON File for offline app.').'</li>
<li>'.__('For get example JSON Format go to <a href="./?page=tables">(IMAB) Tables</a> Menu, in <code>Sample Data</code> section checked <code>Generate JSON Files</code>. Do not forget for <code>unchecked</code> when existing <code>example data</code></strong>').'.</li>
<li>'.__('JSON array can contain <strong>multiple objects</strong>, Example: <code>[{id:1,name:...},{id:2,name:...},{id:3,name:...}]</code>').'</li>
<li>'.__('For import JSON from <code>PhpMyAdmin -> Export JSON</code>, you must checked fix enter, you can use <a target="_blank" href="./?page=z-php-sql-restapi-generator">PHP SQL - RESTAPI Generator</a> for get SQL Column').'</li>
</ul>
</blockquote>';
$app_tables = $_SESSION['PROJECT']['tables'];
$out_json = $out_path . '/www/data/tables/';
$list_jsons[] = array('value' => 'null', 'label' => ''.__('Select JSON File').'');
$y=1;
foreach ($app_tables as $app_table)
{
    if (isset($app_table['prefix']))
    {
        $table_path = 'output/' . $file_name . '/www/data/tables/' . $app_table['prefix'] . '.json';
        $list_jsons[$y] = array('value' => $app_table['prefix'], 'label' => '--|- '. $table_path);
        if($_GET['raw_file']  == $app_table['prefix'] ){
            $list_jsons[$y]['active'] = true;
        }
        $y++;
    }
}
$form_input = null;
$form_input .= '<form class="form-inline" action="" method="get">';
$form_input .= '<input type="hidden" name="page" value="z-json-raw" />';
$form_input .= $bs->FormGroup('raw_file', 'inline', 'select', 'File', $list_jsons, null, null);
$form_input .= $bs->FormGroup(null, 'inline', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'edit',
        'label' => __('Edit'),
        'tag' => 'submit',
        'color' => 'primary'))));
$form_input .= '</form>';
$form_input .= '<hr/>';
$content .= $form_input;
$notice_format = '';
if (isset($_GET['raw_file']))
{
    $file_json = 'output/' . $file_name . '/www/data/tables/' . basename($_GET['raw_file']) . '.json';
    $file_project_json = 'projects/' . $file_name . '/tables/' . basename($_GET['raw_file']) . '.json';
    if (file_exists($file_json))
    {
        if (defined('JSON_PRETTY_PRINT'))
        {
            $json_data = json_encode(json_decode(file_get_contents($file_json), true), JSON_PRETTY_PRINT);
        } else
        {
            $json_data = json_encode(json_decode(file_get_contents($file_json), true));
        }
    } else
    {
        $json_data = '[]';
    }
    if (isset($_POST['raw_json']))
    {
        if (isset($_POST['fix-enter']))
        {
            $_POST['raw_json'] = str_replace("\r\n", "\\r\\n", $_POST['raw_json']);
        }
        $_raw_data = explode("[{\"", $_POST['raw_json']);
        if (isset($_raw_data[1]))
        {
            $raw_data = '[{"' . $_raw_data[1];
        } else
        {
            $raw_data = $_POST['raw_json'];
        }
        $raw_data_json = json_encode(json_decode($raw_data));
        if ($raw_data_json != 'null')
        {
            $notice_format = '<div class="alert alert-success"><p>'.__('JSON data has been saved').'.</p></div>';
            file_put_contents($file_json, $raw_data_json);
            file_put_contents($file_project_json, $raw_data_json);
            if (file_exists($file_json))
            {
                if (defined('JSON_PRETTY_PRINT'))
                {
                    $json_data = json_encode(json_decode(file_get_contents($file_json), true), JSON_PRETTY_PRINT);
                } else
                {
                    $json_data = json_encode(json_decode(file_get_contents($file_json), true));
                }
            }
        } else
        {
            $notice_format = '<div class="alert alert-danger"><p>'.__('JSON data has failed to be saved, because you give the wrong format').'.</p></div>';
            $json_data = $raw_data;
        }
    }
    $form_content = null;
    $form_content .= $notice_format;
    $form_content .= '<p><input type="checkbox" name="fix-enter" /> '.__('Fix Enter (checked for Import JSON file from PHPMyAdmin)').'</p>';
    $form_content .= '<textarea name="raw_json" id="raw_json" style="height:600px;width:100%">' . $json_data . '</textarea><br/>';
    $form_content .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
            'name' => 'json-save',
            'label' => __('Save JSON File').' &raquo;',
            'tag' => 'submit',
            'color' => 'primary'), )));
    $content .= $bs->Forms('theme-setup', '', 'post', 'default', $form_content);
}
$footer = '
<link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="./templates/default/vendor/codemirror/addon/hint/show-hint.css">
<link rel="stylesheet" href="./templates/default/vendor/codemirror/addon/fold/foldgutter.css">
<script src="./templates/default/vendor/codemirror/lib/codemirror.js"></script>
<script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
<script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
<script src="./templates/default/vendor/codemirror/addon/edit/matchbrackets.js"></script>
<script src="./templates/default/vendor/codemirror/addon/fold/foldcode.js"></script>
<script src="./templates/default/vendor/codemirror/addon/fold/foldgutter.js"></script>
<script src="./templates/default/vendor/codemirror/addon/fold/brace-fold.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/show-hint.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/javascript-hint.js"></script>
<script type="text/javascript">
  var editor = CodeMirror.fromTextArea(document.getElementById("raw_json"), {
    lineNumbers: true,
    foldGutter: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
    mode: "application/ld+json",
    extraKeys: {"Ctrl-Space": "autocomplete"},
  }); 
  editor.setSize("100%", 1200);
  
  $("#raw_file").change(function(){
        window.location = "./?page=z-json-raw&edit=edit&raw_file=" + $(this).val();
  });
</script>
';
$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Backend Tools -&raquo; JSON Raw Editor (Offline Data)';
$template->base_desc = 'tools';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>