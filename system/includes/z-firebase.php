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
if (!isset($_SESSION["PROJECT"]['menu']))
{
    header('Location: ./?page=menu&err=new');
    die();
}
$file_name = $_SESSION['FILE_NAME'];
if (!is_dir('output/' . $file_name . '/backend/firebase/'))
{
    mkdir('output/' . $file_name . '/backend/firebase/', 0777, true);
}
$dir_json = 'projects/' . $file_name . "/tables/";
$file_setting = 'projects/' . $file_name . '/firebase.json';
if (isset($_POST['firebase_save']))
{
    $postdata['firebase'] = $_POST['firebase'];
    foreach ($_POST['firebase']['table'] as $_table)
    {
        $_var = $_table['prefix'];
        $postdata['firebase']['table'][$_var]['prefix'] = $_var;
        if (isset($postdata['firebase']['table'][$_var]['used']))
        {
            $postdata['firebase']['table'][$_var]['used'] = true;
        } else
        {
            $postdata['firebase']['table'][$_var]['used'] = false;
        }
    }
    file_put_contents($file_setting, json_encode($postdata));
    buildIonic($file_name);
    header('Location: ./?page=z-firebase&err=null&notice=save');
    die();
}
if (!isset($_SESSION['PROJECT']['firebase']['url']))
{
    $_SESSION['PROJECT']['firebase']['url'] = '';
}
$bs = new jsmBootstrap();
$content = $out_path = $footer = $html = null;
$json_data = null;
$link_firebases = array();
$form_input = null;
$form_input .= '<blockquote class="blockquote blockquote-danger">';
$form_input .= '<h4>' . __('The rules that apply are:') . '</h4>';
$form_input .= '<ol>';
$form_input .= '<li>' . __('When making changes in <code>tables</code>, <code>forms</code> and <code>this settings</code>, you must replace the code that has been uploaded as well.') . '</li>';
$form_input .= '<li>' . __('<code>Checked the tables</code> that you want to display on the JSON Files.') . '</li>';
$form_input .= '<li>' . __('<code>Update URL List Item</code> button only for default table, for table with <code>dynamic 1st param</code> or <code>relation</code> that you should edit table manually operated') . '</li>';
$form_input .= '</ol>';
$form_input .= '</blockquote>';
$form_input .= $bs->FormGroup('firebase[url]', 'default', 'text', __('Firebase URL'), 'https://ebooks-e2568.firebaseio.com/', __('Your firebase link'), ' ', '5', htmlentities($_SESSION['PROJECT']['firebase']['url']));
$form_input .= '<table class="table table-striped">';
$form_input .= '<thead>';
$form_input .= '<tr>';
$form_input .= '<th></th>';
$form_input .= '<th>' . __('Table Name') . '</th>';
$form_input .= '<th>' . __('URL List Item') . '</th>';
$form_input .= '<th>' . __('(IMAB) Tables') . '</th>';
$form_input .= '</tr>';
$form_input .= '</thead>';
$form_input .= '<body>';
foreach (glob($dir_json . "/*.json") as $json_file)
{
    $readonly = $checked = '';
    $var_name = str_replace('.json', '', basename($json_file));
    $firebase_api_link = $_SESSION['PROJECT']['firebase']['url'] . '/' . $var_name . '.json';
$firebase_single_api_link = $_SESSION['PROJECT']['firebase']['url'] . '/' . $var_name . '/';

    if (!isset($_SESSION['PROJECT']['firebase']['table']))
    {
        $_SESSION['PROJECT']['firebase']['table'] = array();
    }
    if (!is_array($_SESSION['PROJECT']['firebase']['table']))
    {
        $_SESSION['PROJECT']['firebase']['table'] = array();
    }
    if (isset($_SESSION['PROJECT']['firebase']['table'][$var_name]))
    {
        if ($_SESSION['PROJECT']['firebase']['table'][$var_name]['used'] == true)
        {
            $checked = 'checked';
            $json_data[$var_name] = json_decode(file_get_contents($json_file), true);

            if (isset($_SESSION['PROJECT']['tables'][$var_name]['cols'][0]['title']))
            {
                if ($_SESSION['PROJECT']['tables'][$var_name]['cols'][0]['type'] == 'id')
                {
                    $firebase_api_link .= '?' . $_SESSION['PROJECT']['tables'][$var_name]['cols'][0]['title'] . '=-1';
                }
            }

        }
    }

    $link_firebases[$var_name]['link'] = $firebase_api_link;
    $link_firebases[$var_name]['name'] = $var_name;

    $form_input .= '<tr>';
    $form_input .= '<td style="width:30px;vertical-align: middle;">';
    $form_input .= '<input type="hidden" name="firebase[table][' . $var_name . '][prefix]" value="' . $var_name . '" />';
    $form_input .= $bs->FormGroup('firebase[table][' . $var_name . '][used]', 'inline', 'checkbox', ' ', '', '', $readonly . ' ' . $checked, '8', 'true');
    $form_input .= '</td>';
    $form_input .= '<td style="vertical-align: middle;">';
    $form_input .= $var_name;
    $form_input .= '</td>';
    $form_input .= '<td style="vertical-align: middle;">';
    $form_input .= $firebase_api_link;
    $form_input .= '</td>';
    $form_input .= '<td style="vertical-align: middle;">';
    $form_input .= '<a class="btn btn-xs btn-danger ' . $readonly . '" target="_blank" href="./?page=tables&prefix=' . str2var($var_name, false) . '&source_json=online&url_list_item=' . urlencode($firebase_api_link) . '&url_single_item='.$firebase_single_api_link.'&update">' . __('Update URL') . '</a>';
    $form_input .= '&nbsp;<a class="btn btn-xs btn-primary ' . $readonly . '" target="_blank" href="' . $firebase_api_link . '">' . __('Check URL') . '</a>';
    $form_input .= '</td>';
    $form_input .= '</tr>';

    //$link_firebases[$var_name]['link'] = 'https://' . $file_name . '.firebaseio.com/' . $var_name . '.json';
    //$link_firebases[$var_name]['name'] = $var_name;
}
$dir_target = 'output/' . $file_name . '/backend/firebase/';
if (!is_dir($dir_target))
{
    mkdir($dir_target, 0777, true);
}
$filezip = $dir_target . '/firebase.zip';
$filejson = $dir_target . '/firebase.json';
$data_json = json_encode($json_data);
file_put_contents($filejson, $data_json);
$zip = new ZipArchive();
if ($zip->open($filezip, ZIPARCHIVE::CREATE) !== true)
{
    exit("cannot open <$filezip>\n");
}
$zip->addFromString('firebase.json', $data_json);
$zip->close();
$form_input .= '</body>';
$form_input .= '</table>';
$form_input .= '</table>';
$form_input .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'firebase_save',
        'label' => __('Save Setting'),
        'tag' => 'submit',
        'color' => 'primary'), array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));

$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-database fa-stack-1x"></i></span>Backend Tools -&raquo; (IMAB) Firebase (Simple Database)</h4>';

$content .= '<ul class="nav nav-tabs">';
$content .= '<li class="active"><a href="#code" data-toggle="tab">' . __('Code Generator') . '</a></li>';
$content .= '<li><a href="#help" data-toggle="tab" >' . __('How To Use?') . '</a></li>';
$content .= '</ul>';
$content .= '<br/>';

$content .= '<div class="tab-content">';
$content .= '<div class="tab-pane active" id="code">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">' . __('General') . '</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= notice();
$content .= $bs->Forms('app-setup', '', 'post', 'default', $form_input);
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';


$content .= '<div class="tab-pane" id="help">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">' . __('Help') . '</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= '<blockquote class="blockquote blockquote-info">';
$content .= '<ol>';
$content .= '<li>' . __('Go to <a target="_blank" href="./?page=tables">(IMAB) Tables</a>, then create a table with option <code>Source JSON = offline</code> (Offline App)') . '</li>';
$content .= '<li>' . __('Then go to Backend Tools -&gt; <a target="_blank" href="./?page=z-json">(IMAB) JSON Editor</a> or <a target="_blank" href="./?page=z-json-raw">(IMAB) JSON Raw Editor</a> for edit your json files.') . '</li>';
$content .= '<li>' . __('Login to Your <a href="https://console.firebase.google.com/" target="_blank">Firebase Console</a>, then Create a project') . '</li>';
$content .= '<li>' . __('Go to <code>DEVELOP</code> -&gt; <code>Database</code> -&gt; <code>Realtime Database</code>') . '</li>';
$content .= '<li>' . __('Then give access to <code>readonly</code> for avoid error <code>Permission denied</code>, click <code>Rules</code> Tabs, edit rules like this:') . '
<pre>
{
  "rules": {
    ".read": "auth == null",
    ".write": "auth != null"
  }
}
</pre>
</li>';
$content .= '<li>' . __('Back to imabuilder, Go to <code>Backend Tools</code> -&raquo; <code>(IMAB) Firebase</code> -&raquo; <code>checked tables</code> and update <code>Firebase URL</code> according Firebase Console') . '</li>';
$content .= '<li>' . __('Go to Firebase Console again, then go to <code>DEVELOP</code> -&gt; <code>Database</code> -&gt; <code>Realtime Database</code> -&gt; <code>Data</code> Tabs -&gt; Icon More Vertical (<i class="fa fa-ellipsis-v"></i>) -&gt; <code>Import JSON</code>  -&gt; Browse -&gt; Upload JSON files (get json for firebase below)') . '</li>';

 $content .= '<li>' . __('Last step, please update the link on <code>URL List Item</code>, <code>URL Single Item</code> and <code>Source JSON = Online</code> on your <code>(IMAB) Tables</code>.') . '</li>';
$content .= '</ol>';
$content .= '</blockquote>';

$content .= '<a target="_blank" href="./output/' . $file_name . '/backend/firebase/firebase.json" class="btn btn-danger">JSON ' . __('for') . ' Firebase</a> ' . __('Or') . ' ';
$content .= '<a target="_blank" href="./output/' . $file_name . '/backend/firebase/firebase.zip" class="btn btn-danger">JSON ' . __('for') . ' Firebase (Zip File)</a>';

$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';


$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Backend Tools -&raquo; Firebase';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>