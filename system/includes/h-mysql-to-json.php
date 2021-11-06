<?php

/**
 * @author Jasman
 * @copyright 2017
 */

if (!defined('JSM_EXEC'))
{
    die(':)');
}

$rawdata['mysql']['host'] = 'localhost';
$rawdata['mysql']['user'] = 'root';
$rawdata['mysql']['pwd'] = '';
$rawdata['mysql']['db'] = 'db_verbs';

$file_name = $_SESSION['FILE_NAME'];

$bs = new jsmBootstrap();
$content = $out_path = $footer = $html = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-database fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) MySQL to JSON</h4>';
$json_data =$raw_json_data= '[]';
if (isset($_POST['get-json']))
{
    $config = $_POST['mysql'];
    $mysqli = new mysqli($config['host'], $config['user'], $config['pwd'], $config['db']);
    $mysqli->query("SET NAMES 'utf8'");
    $query = "SELECT * FROM verbs";
    $result = mysqli_query($mysqli, $query);
    $z = 0;
    while ($row = mysqli_fetch_array($result))
    {
        for ($i = 0; $i < 100; $i++)
        {
            unset($row[$i]);
        }
        $data[$z] = $row;
        $z++;
    }


    if (defined("JSON_UNESCAPED_UNICODE"))
    {
        $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
    } else
    {
        $json_data = json_encode($data);
    }
    if (defined('JSON_PRETTY_PRINT'))
    {
        $raw_json_data = json_encode(json_decode($json_data), JSON_PRETTY_PRINT);
    } else
    {
        $raw_json_data = json_encode(json_decode($json_data));
    }
}


$form_input = null;
$form_input .= $bs->FormGroup('mysql[host]', 'default', 'text', 'Host', 'localhost', null, null, '8', $rawdata['mysql']['host']);
$form_input .= $bs->FormGroup('mysql[user]', 'default', 'text', 'Username', 'root', null, null, '8', $rawdata['mysql']['user']);
$form_input .= $bs->FormGroup('mysql[pwd]', 'default', 'text', 'Password', '', null, null, '8', $rawdata['mysql']['pwd']);
$form_input .= $bs->FormGroup('mysql[db]', 'default', 'text', 'Database', '', null, null, '8', $rawdata['mysql']['db']);
$form_input .= $bs->FormGroup('mysql[db]', 'default', 'text', 'Database', '', null, null, '8', $rawdata['mysql']['db']);


$form_input .= '<br/><br/>';
$form_input .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'get-json',
        'label' => 'Get JSON &raquo;',
        'tag' => 'submit',
        'color' => 'primary'), )));

$content .= $bs->Forms('mysql-setup', '', 'post', 'default', $form_input);
$content .= '<textarea id="raw_json">'. $raw_json_data .'</textarea>';
$footer = '
<link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="./templates/default/vendor/codemirror/theme/'.JSM_THEME_CODEMIRROR.'.css">
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
    theme: "'.JSM_THEME_CODEMIRROR.'",
    foldGutter: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
    mode: "application/ld+json",
    extraKeys: {"Ctrl-Space": "autocomplete"},
  }); 
  editor.setSize("100%", 1200);
</script>
';
$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Helper Tools -&raquo; MySQL to JSON';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>