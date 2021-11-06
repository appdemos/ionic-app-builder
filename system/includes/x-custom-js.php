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
$js_content = $html = $content = null;
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
if (!isset($_GET['prefix']))
{
    $_GET['prefix'] = '';
}

$js_path = 'projects/' . $file_name . '/js.json';

if (isset($_GET['delete']))
{
    @unlink($js_path);
    buildIonic($file_name);
    header('Location: ./?page=x-custom-js&err=null&notice=delete');
    die();
}

if (isset($_POST['js-save']))
{
    $js_code = $_POST['js'];

    file_put_contents($js_path, json_encode(array('js' => $js_code)));
    buildIonic($file_name);
    header('Location: ./?page=x-custom-js&err=null&notice=save');
    die();
}

$raw_js['js']['rootScope'] = '';
$raw_js['js']['directives'] = '';

if (file_exists($js_path))
{
    $raw_js = json_decode(file_get_contents($js_path), true);
}
if (!isset($raw_js['js']['directives']))
{
    $raw_js['js']['directives'] = '';
}

if (!isset($raw_js['js']['router']))
{
    $raw_js['js']['router'] = '';
}
$js_content .= '<blockquote class="blockquote blockquote-danger">
<h4>' . __('The rules that apply are:') . '</h4>
<ol>
    <li>' . __('Write code based on the framework <a href="https://angularjs.org/" target="_blank">AngularJS v1</a>') . '</li>
    <li>' . __('Javascript may be work, but not recommended') . '</li>
</ol>
</blockquote>';
$js_content .= '<div class="panel panel-default">';
$js_content .= '<div class="panel-heading">';
$js_content .= '<h5 class="panel-title">AngularJS - Controller/Directives/Run/Filter/Config</h5>';
$js_content .= '</div>';
$js_content .= '<div class="panel-body">';
$js_content .= '<p>'.__('Writing your codes <strong class="text-danger">with</strong> using:').'</p>
<table class="table">
<thead>
<tr>
	<th>For</th>
	<th>Example</th>
</tr>
</thead>
<tbody>
<tr>
	<td>Controller</td>
	<td><code>.controller(\'...\',function(){/** code **/})</code> </td>
</tr>
<tr>
	<td>Directive</td>
	<td><code>.directive(\'...\',function(){/** code **/})</code></td>
</tr>
<tr>
	<td>Event</td>
	<td><code>.run(function($ionicPlatform){$ionicPlatform.ready(function(){/** code **/});})</code></td>
</tr>
<tr>
	<td>Filter</td>
	<td><code>.filter(\'...\', function(){/** code **/})</code></td>
</tr>
<tr>
	<td>Config</td>
	<td><code>.config(function(...){/** code **/})</code></td>
</tr>






</tbody>
</table>

<p>'.__('Watch Out! one bit of your application will not work.').'</p>
';

$js_content .= '<textarea name="js[directives]" id="js_directives">' . $raw_js['js']['directives'] . '</textarea>';
$js_content .= '<p>'.__('Press <strong>ctrl-space</strong> to activate autocompletion.').'</p>';
$js_content .= ''.__('Output').': <code>' . realpath(JSM_PATH . '/output/' . $file_name . '/www/js/services.js') . '</code>';

$js_content .= '<p>'.__('References').': <a target="_blank" href="http://www.w3schools.com/angular/angular_directives.asp">w3schools</a></p>';
$js_content .= '</div>';
$js_content .= '</div>';

$js_content .= '<div class="panel panel-default">';
$js_content .= '<div class="panel-heading">';
$js_content .= '<h5 class="panel-title">AngularJS - Router ($stateProvider)</h5>';
$js_content .= '</div>';
$js_content .= '<div class="panel-body">';
$js_content .= '<p>'.__('Writing your codes <strong class="text-danger">with</strong> using:').'<br/><code>.state(\'...\',{url:\'...\',templateUrl:\'...\',controller:\'...\'})</code></code></p>';
$js_content .= '<textarea name="js[router]" id="js_router">' . $raw_js['js']['router'] . '</textarea>';
$js_content .= '<p>'.__('Press <strong>ctrl-space</strong> to activate autocompletion.').'</p>';
$js_content .= __('Output').': <code>' . realpath(JSM_PATH . '/output/' . $file_name . '/www/js/app.js') . '</code>';
$js_content .= '</div>';
$js_content .= '</div>';

$button[] = array(
    'name' => 'js-save',
    'label' => __('Save JS Code').' &raquo;',
    'tag' => 'submit',
    'color' => 'primary');
$button[] = array(
    'label' => __('Reset'),
    'tag' => 'reset',
    'color' => 'warning');
if (file_exists($js_path))
{
    $button[] = array(
        'label' => __('Delete'),
        'icon' => 'glyphicon glyphicon glyphicon-trash delete-this-js',
        'tag' => 'anchor',
        'color' => 'danger',
        'link' => "./?page=x-custom-js&delete");
}
$js_content .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, $button));

$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-jsfiddle fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Custom JS</h4>';
$content .= notice();
$content .= $bs->Forms('app-setup', '', 'post', 'default', $js_content);
$footer = '
<link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="./templates/default/vendor/codemirror/addon/hint/show-hint.css">
<link rel="stylesheet" href="./templates/default/vendor/codemirror/theme/'.JSM_THEME_CODEMIRROR.'.css">
<script src="./templates/default/vendor/codemirror/lib/codemirror.js"></script>
<script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/show-hint.js"></script>
<script src="./templates/default/vendor/codemirror/addon/hint/javascript-hint.js"></script>
 
<script type="text/javascript">
  var editor = CodeMirror.fromTextArea(document.getElementById("js_directives"), {
    lineNumbers: true,
    theme: "'.JSM_THEME_CODEMIRROR.'",
    extraKeys: {"Ctrl-Space": "autocomplete"},
  });
  
  var editor = CodeMirror.fromTextArea(document.getElementById("js_router"), {
    lineNumbers: true,
    theme: "'.JSM_THEME_CODEMIRROR.'",
    extraKeys: {"Ctrl-Space": "autocomplete"},
  });
  
  
  $(".delete-this-js").parent().on("click",function(e){
    var notice = "" ; 
    notice += "This action cannot be restored again! \\r\\nAre you sure you want to delete this Custom JS?"  ;
    return confirm(notice);
});
  
</script>
';

$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Custom js';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;

?>