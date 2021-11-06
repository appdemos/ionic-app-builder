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
$bs = new jsmBootstrap();
$form_input = $html = null;
$error_notice = array();

if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}


$out_path = 'output/' . $file_name;
$form_input = $content = $footer = null;

$json_path = 'projects/' . $file_name . '/rss2json.json';

$max_rss2json = 1;
if (file_exists($json_path))
{
    $raw_rss2json = json_decode(file_get_contents($json_path), true);
    $max_rss2json = count($raw_rss2json['rss2json']['items']);
}

if (!isset($_GET['max-rss']))
{
    $_GET['max-rss'] = $max_rss2json;
}

$max_rss2json = (int)$_GET['max-rss'];

if (isset($_POST['rss-save']))
{

    $data['rss2json'] = $_POST['rss2json'];
    $data['rss2json']['items'] = array();
    foreach ($_POST['rss2json']['items'] as $item)
    {
        $data['rss2json']['items'][] = $item;
    }
    file_put_contents($json_path, json_encode($data));
    header('Location: ./?page=z-rss-to-json-converter&err=null&notice=save');
    die();
}


$_max_rss = array();
for ($i = 0; $i <= 20; $i++)
{
    $x = $i;

    $_max_rss[$i] = array('label' => $x, 'value' => $x);
    if ($max_rss2json == $x)
    {
        $_max_rss[$i]['active'] = true;
    }
}

$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">General</h5>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';

$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-6">';
$form_input .= $bs->FormGroup('max-rss', 'default', 'select', 'Max RSS', $_max_rss, null, ' onChange="window.location=\'?page=z-rss-to-json-converter&max-rss=\'+this.value;"');
$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">RSS</h5>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';
$form_input .= '<table class="table table-striped sortable">';
$form_input .= '<thead>';
$form_input .= '<tr>';
$form_input .= '<th></th>';
$form_input .= '<th>Label</th>';
$form_input .= '<th>URL/Path</th>';
$form_input .= '</tr>';
$form_input .= '</thead>';
$form_input .= '<tbody>';
for ($i = 0; $i < $max_rss2json; $i++)
{

    // handle error
    if (!isset($raw_rss2json['rss2json']['items'][$i]['label']))
    {
        $raw_rss2json['rss2json']['items'][$i]['label'] = null;
    }
    if (!isset($raw_rss2json['rss2json']['items'][$i]['url']))
    {
        $raw_rss2json['rss2json']['items'][$i]['url'] = null;
    }

    $form_input .= '<tr id="data-' . $i . '">';

    $form_input .= '<td class="v-align">';
    $form_input .= '<span class="glyphicon glyphicon-move"></span>';
    $form_input .= '</td>';

    $form_input .= '<td>';
    $form_input .= $bs->FormGroup('rss2json[items][' . $i . '][label]', 'default', 'text', '', 'mobile-items', '', 'required', '8', $raw_rss2json['rss2json']['items'][$i]['label']);
    $form_input .= '</td>';

    $form_input .= '<td>';
    $form_input .= $bs->FormGroup('rss2json[items][' . $i . '][url]', 'default', 'text', '', 'http://codecanyon.net/feeds/new-mobile-items.atom', '', 'required', '8', $raw_rss2json['rss2json']['items'][$i]['url']);
    $form_input .= '</td>';

    $form_input .= '<td>';
    $form_input .= '<a class="remove-item btn btn-danger btn-sm" href="#!_" data-target="#data-' . $i . '" ><i class="glyphicon glyphicon-trash"></i></a>';
    $form_input .= '</td>';

    $form_input .= '</tr>';
}
$form_input .= '</tbody>';
$form_input .= '</table>';
$form_input .= '</div>';
$form_input .= '</div>';


$form_input .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'rss-save',
        'label' => 'Save URL',
        'tag' => 'submit',
        'color' => 'primary'), array(
        'label' => 'Reset',
        'tag' => 'reset',
        'color' => 'default'))));

$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-rss fa-stack-1x"></i></span>Backend Tools -&raquo; (IMAB) RSS to JSON Converter</h4>';
$content .= notice();
$content .= '<p><span class="label label-info">Note</span> : This tool is used to create a PHP code for convert the RSS web into JSON Format.</p>';

$content .= '<ul class="nav nav-tabs">';
$content .= '<li class="active"><a href="#home" data-toggle="tab">Code</a></li>';
$content .= '<li><a href="#help" data-toggle="tab">How to Use?</a></li>';
$content .= '</ul>';
$content .= '<br/>';


$content .= '<div class="tab-content">';
$content .= '<div class="tab-pane active" id="home">';
$content .= $bs->Forms('app-setup', '', 'post', 'default', $form_input);
$file_code = null;

$php = null;
$php .= '<?php' . "\r\n\r";
$php .= "\r\n";
$php .= "/**\r\n";
$php .= " * @author " . $_SESSION['PROJECT']['app']['author_name'] . " <" . $_SESSION['PROJECT']['app']['author_email'] . ">\r\n";
$php .= " * @copyright " . $_SESSION['PROJECT']['app']['company'] . " " . date("Y") . "\r\n";
$php .= " * @package " . $_SESSION['PROJECT']['app']['prefix'] . "\r\n";
$php .= " * \r\n";
$php .= " * \r\n";
$php .= " * Created using Ionic App Builder\r\n";
$php .= " * http://codecanyon.net/item/ionic-mobile-app-builder/15716727\r\n";
$php .= " */\r\n\r\n\r\n";


$php .= "/** --- START CONFIG --- **/\r\n";
$php .= '$append_url = "?ref=codegenerator";' . "\r\n";

if (isset($raw_rss2json['rss2json']['items']))
{
    if (is_array($raw_rss2json['rss2json']['items']))
    {

        foreach ($raw_rss2json['rss2json']['items'] as $item)
        {
            $php .= '$url_feeds["' . htmlentities(str2var($item['label'])) . '"] = "' . htmlentities($item['url']) . '";' . "\r\n";
        }
    }
}
$php .= '$date_format = "l, jS \of F Y h:i:s A";' . "\r\n";

$php .= "/** --- END CONFIG --- **/\r\n\r\n\r\n";
//$php .= "error_reporting(0); // remove this code for debug\r\n";

$php .= 'if(!ini_get("allow_url_fopen")){' . "\r\n";
$php .= "\t" . 'die("<strong>Error!</strong> The PHP allow_url_fopen setting is disabled, please edit your <a target=\"_blank\" href=\"http://php.net/allow-url-fopen\">php.ini</a>");' . "\r\n";
$php .= '}' . "\r\n";

$php .= "" . '$rest_api = array();' . "\r\n";
$php .= "" . 'function get_images($content){' . "\r\n";
$php .= "\t" . '$images = array();' . "\r\n";
$php .= "\t" . 'libxml_use_internal_errors(true);' . "\r\n";
$php .= "\t" . '$doc = new DOMDocument();' . "\r\n";
$php .= "\t" . '$doc->loadHTML($content);' . "\r\n";
$php .= "\t" . 'libxml_clear_errors();' . "\r\n";
$php .= "\t" . '$imageTags = $doc->getElementsByTagName("img");' . "\r\n";
$php .= "\t" . 'foreach ($imageTags as $tag)' . "\r\n";
$php .= "\t" . '{' . "\r\n";
$php .= "\t\t" . '$images[] = $tag->getAttribute("src");' . "\r\n";
$php .= "\t" . '}' . "\r\n";
$php .= "\t" . 'return $images;' . "\r\n";
$php .= "" . '}' . "\r\n";

$php .= "" . 'function get_rss($url,$date_format) {' . "\r\n";
$php .= "\t" . 'global $append_url;' . "\r\n";
$php .= "\t" . '$rss_content = file_get_contents($url);' . "\r\n";
$php .= "\t" . '$obj = simplexml_load_string($rss_content,"SimpleXMLElement",LIBXML_NOCDATA);' . "\r\n";
$php .= "\t" . '$arr = json_decode(json_encode($obj), true);' . "\r\n";
$php .= "\t" . '$z = 0;' . "\r\n";
$php .= "\t" . '$new_entry = array();' . "\r\n";
$php .= "\t" . 'if(!isset($arr["entry"])){' . "\r\n";
$php .= "\t\t" . '$arr["entry"]=$arr["channel"]["item"] ;' . "\r\n";
$php .= "\t" . '}' . "\r\n";
$php .= "\t" . 'foreach ($arr[\'entry\'] as $entry){' . "\r\n";
$php .= "\t\t" . '$new_entry[$z] = $entry;' . "\r\n";
$php .= "\t\t" . '//fix id' . "\r\n";
$php .= "\t\t" . '$new_entry[$z][\'id\'] = $z;' . "\r\n";
$php .= "\t\t" . '//fix link' . "\r\n";
$php .= "\t\t" . 'if (isset($entry[\'link\'])){' . "\r\n";
$php .= "\t\t\t" . 'if (isset($entry[\'link\'][\'@attributes\'])){' . "\r\n";
$php .= "\t\t\t\t" . '$new_entry[$z][\'x_link\'][\'attributes\'] = $entry[\'link\'][\'@attributes\'];' . "\r\n";
$php .= "\t\t\t\t" . 'if (isset($new_entry[$z][\'x_link\'][\'attributes\'][\'href\'])){' . "\r\n";
$php .= "\t\t\t\t\t" . '$new_entry[$z][\'x_link\'][\'attributes\'][\'href\'] = $new_entry[$z][\'x_link\'][\'attributes\'][\'href\'].$append_url ;' . "\r\n";
$php .= "\t\t\t\t" . '}' . "\r\n";

$php .= "\t\t\t" . '}' . "\r\n";
$php .= "\t\t\t" . 'if (count($entry[\'link\']) > 1){' . "\r\n";
$php .= "\t\t\t\t" . '$y = 0;' . "\r\n";
$php .= "\t\t\t\t\t" . 'foreach ($entry[\'link\'] as $link){' . "\r\n";
$php .= "\t\t\t\t\t\t" . '$new_entry[$z][\'x_link\'][$y][\'attributes\'] = $link[\'@attributes\'] ;' . "\r\n";

$php .= "\t\t\t\t\t\t" . 'if (isset($link[\'@attributes\'][\'href\'])){' . "\r\n";
$php .= "\t\t\t\t\t\t\t" . '$new_entry[$z][\'x_link\'][$y][\'attributes\'][\'href\'] = $link[\'@attributes\'][\'href\'].$append_url ;' . "\r\n";
$php .= "\t\t\t\t\t\t" . '}' . "\r\n";

$php .= "\t\t\t\t\t" . '$y++;' . "\r\n";
$php .= "\t\t\t\t" . '}' . "\r\n";
$php .= "\t\t\t" . '}' . "\r\n";
$php .= "\t\t" . '}' . "\r\n";

$php .= "\t\t" . 'if (isset($entry[\'category\'][\'@attributes\'])){' . "\r\n";
$php .= "\t\t\t" . '$new_entry[$z][\'x_category\'][\'attributes\'] = $entry[\'category\'][\'@attributes\'];' . "\r\n";
$php .= "\t\t" . '}' . "\r\n";

$php .= "\t\t" . 'if (isset($entry[\'updated\'])){' . "\r\n";
$php .= "\t\t\t" . '$new_entry[$z][\'x_updated\'] = date($date_format, strtotime($entry[\'updated\']));' . "\r\n";
$php .= "\t\t" . '}' . "\r\n";

$php .= "\t\t" . 'if (isset($entry[\'published\'])){' . "\r\n";
$php .= "\t\t\t" . '$new_entry[$z][\'x_published\'] = date($date_format, strtotime($entry[\'published\']));' . "\r\n";
$php .= "\t\t" . '}' . "\r\n";

$php .= "\t\t" . 'if (isset($entry[\'description\'])){' . "\r\n";
$php .= "\t\t\t" . '$new_entry[$z][\'content\'] = $entry[\'description\'];' . "\r\n";
$php .= "\t\t\t" . 'unset($new_entry[$z][\'description\']);' . "\r\n";
$php .= "\t\t\t" . '$images = get_images($new_entry[$z][\'content\']);' . "\r\n";
$php .= "\t\t\t" . 'if (isset($images[0]))' . "\r\n";
$php .= "\t\t\t" . '{' . "\r\n";
$php .= "\t\t\t\t" . '$new_entry[$z][\'thumbnail\'] = $images[0];' . "\r\n";
$php .= "\t\t\t" . '}' . "\r\n";
$php .= "\t\t\t" . '$new_entry[$z][\'images\'] = $images;' . "\r\n";
$php .= "\t\t" . '}' . "\r\n";

$php .= "\t\t" . 'if (isset($entry[\'content\'])){' . "\r\n";
$php .= "\t\t\t" . '$images = get_images($new_entry[$z][\'content\']);' . "\r\n";
$php .= "\t\t\t" . 'if (isset($images[0]))' . "\r\n";
$php .= "\t\t\t" . '{' . "\r\n";
$php .= "\t\t\t\t" . '$new_entry[$z][\'thumbnail\'] = $images[0];' . "\r\n";
$php .= "\t\t\t" . '}' . "\r\n";
$php .= "\t\t\t" . '$new_entry[$z][\'images\'] = $images;' . "\r\n";
$php .= "\t\t" . '}' . "\r\n";


$php .= "\t\t" . 'if (isset($entry[\'pubDate\'])){' . "\r\n";
$php .= "\t\t\t" . 'if (!is_array($entry[\'pubDate\'])){' . "\r\n";
$php .= "\t\t\t\t" . '$new_entry[$z][\'x_published\'] = date($date_format, strtotime($entry[\'pubDate\']));' . "\r\n";
$php .= "\t\t\t\t" . 'unset($new_entry[$z][\'pubDate\']);' . "\r\n";
$php .= "\t\t\t" . '}' . "\r\n";
$php .= "\t\t" . '}' . "\r\n";

$php .= "\t\t" . 'if (isset($entry[\'link\'])){' . "\r\n";
$php .= "\t\t\t" . '$new_entry[$z][\'x_link\'][\'attributes\'][\'href\'] = $entry[\'link\'];' . "\r\n";
$php .= "\t\t" . '}' . "\r\n";

$php .= "\t\t" . 'if (isset($entry[\'enclosure\'][\'@attributes\'])){' . "\r\n";
$php .= "\t\t\t" . '$new_entry[$z][\'enclosure\'][\'attributes\'] = $entry[\'enclosure\'][\'@attributes\'];' . "\r\n";
$php .= "\t\t" . '}' . "\r\n";

$php .= "\t\t" . 'if (isset($entry[\'link\'][\'@attributes\'][\'href\'])){' . "\r\n";
$php .= "\t\t\t" . '$new_entry[$z][\'x_link\'][\'attributes\'][\'href\'] = $entry[\'link\'][\'@attributes\'][\'href\'];' . "\r\n";
$php .= "\t\t" . '}' . "\r\n";

$php .= "\t\t" . '$z++;' . "\r\n";
$php .= "\t" . '}' . "\r\n";

$php .= "\t" . 'return $new_entry;' . "\r\n";
$php .= "" . '}' . "\r\n";

$php .= "\r\n";
$php .= "\r\n" . 'if(!isset($_GET["json"])){';
$php .= "\r\n\t" . '$_GET["json"]= "route";';
$php .= "\r\n" . '}';
$php .= "\r\n";


$php .= 'switch($_GET["json"]){' . "\r\n";
if (isset($raw_rss2json['rss2json']['items']))
{
    if (is_array($raw_rss2json['rss2json']['items']))
    {
        foreach ($raw_rss2json['rss2json']['items'] as $item)
        {
            $php .= "\t" . 'case "' . str2var($item['label']) . '": ' . "\r\n";
            $php .= "\t\t" . '$rest_api = get_rss($url_feeds["' . str2var($item['label']) . '"],$date_format);' . "\r\n";
            $php .= "\t\t" . 'break;' . "\r\n";
        }
    }
}
$php .= "\t" . 'case "route":';
$z = 0;
if (isset($raw_rss2json['rss2json']['items']))
{
    if (is_array($raw_rss2json['rss2json']['items']))
    {

        foreach ($raw_rss2json['rss2json']['items'] as $item)
        {
            $php .= "\r\n\t\t" . '$rest_api["routes"][' . $z . '] = array("namespace"=>"' . str2var($item['label']) . '","methods"=>"GET","link"=>$_SERVER["PHP_SELF"]."?json=' . str2var($item['label']) . '");';
            $z++;
        }
    }
}
$php .= "\r\n\t\t" . 'break;';
$php .= "\r\n" . '}' . "\r\n";

$php .= "\r\n" . 'header(\'Content-type: application/json\');';
$php .= "\r\n" . 'header(\'Access-Control-Allow-Origin: *\');';
$php .= "\r\n" . 'if(defined("JSON_UNESCAPED_UNICODE")){';
$php .= "\r\n\t" . 'echo json_encode($rest_api,JSON_UNESCAPED_UNICODE);';
$php .= "\r\n" . '}else{';
$php .= "\r\n\t" . 'echo json_encode($rest_api);';
$php .= "\r\n" . '}' . "\r\n";

if (isset($raw_rss2json['rss2json']['items']))
{
    if (is_array($raw_rss2json['rss2json']['items']))
    {
        if (count($raw_rss2json['rss2json']['items']) > 0)
        {
            if ($raw_rss2json['rss2json']['items'][0]['url'] != "")
            {

                $content .= '<div class="panel panel-default">';
                $content .= '<div class="panel-heading">';
                $content .= '<h5 class="panel-title">PHP Code</h5>';
                $content .= '</div>';
                $content .= '<div class="panel-body">';
                $content .= '<blockquote class="blockquote blockquote-info">For testing you can using <a href="./output/' . $_SESSION['PROJECT']['app']['prefix'] . '/backend/rss/rss2json.php" target="_blank">Live Test</a></blockquote>';
                $content .= '<textarea id="code-php" >' . $php . '</textarea>';
                $content .= '</div>';
                $content .= '</div>';
            }
        }
    }
}
$content .= '</div>';
$content .= '<div class="tab-pane" id="help">';
$content .= '<p>To be able to display rss into the application, perform the following steps:</p>';
$content .= '<ol>';
$content .= '<li>Please complete the form on the code tab, and then click save button.</li>';
$content .= '<li>Open a text editor (notepad, nano or vi) then copy the PHP Code on Code Tab and paste into your editor, then click save with filename <strong>rss2json.php</strong>.</li>';
$content .= '<li>Upload <strong>rss2json.php</strong> to your server.</li>';
$content .= '<li>And you will get JSON URL like this:</li>';
$content .= '</ol>';


$content .= '<table class="table table-striped">';
$content .= '<thead>';
$content .= "<tr><th>Tables</th><th>Router</th></tr>";
$content .= '</thead>';
$content .= '<tbody>';
if (isset($raw_rss2json['rss2json']['items']))
{
    if (is_array($raw_rss2json['rss2json']['items']))
    {
        if (count($raw_rss2json['rss2json']['items']) > 0)
        {
            foreach ($raw_rss2json['rss2json']['items'] as $item)
            {
                $content .= "<tr><td>" . $item['label'] . "</td><td><code>http://[your-domain]/rss2json.php?json=" . str2var($item['label']) . "</code></td></tr>";
            }
        } else
        {
            $content .= "<tr><td>no rss url</td><td>...</td></tr>";
        }
    } else
    {
        $content .= "<tr><td>no rss url</td><td>...</td></tr>";
    }
} else
{
    $content .= "<tr><td>no rss url</td><td>...</td></tr>";
}


$content .= '</tbody>';
$content .= '</table>';
$content .= '</div>';
$content .= '</div>';

@mkdir('output/' . $file_name . '/backend/rss', 0777, true);
@file_put_contents('output/' . $file_name . '/backend/rss/rss2json.php', $php);


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
  
 
</script>
';
$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Backend Tools -&raquo; RSS to JSON Converter';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>