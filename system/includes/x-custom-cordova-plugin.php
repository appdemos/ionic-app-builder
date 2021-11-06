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



if (isset($_GET['delete']))
{
    @unlink('projects/' . $file_name . '/mod.' . basename($_GET['delete']) . '.json');
    buildIonic($file_name);
    header('Location: ./?page=x-custom-cordova-plugin&err=null&notice=delete');
}

if (file_exists('system/includes/x-cordova-plugin.php'))
{
    @unlink('system/includes/x-cordova-plugin.php');
}

if (file_exists('projects/' . $file_name . '/mod.cordova-plugin-device.json'))
{
    @unlink('projects/' . $file_name . '/mod.cordova-plugin-device.json');
}
if (file_exists('projects/' . $file_name . '/mod.cordova-plugin-device.json'))
{
    @unlink('projects/' . $file_name . '/mod.cordova-plugin-device.json');
}
if (file_exists('projects/' . $file_name . '/mod.cordova-plugin-splashscreen.json'))
{
    @unlink('projects/' . $file_name . '/mod.cordova-plugin-splashscreen.json');
}
if (file_exists('projects/' . $file_name . '/mod.cordova-plugin-statusbar.json'))
{
    @unlink('projects/' . $file_name . '/mod.cordova-plugin-statusbar.json');
}
if (file_exists('projects/' . $file_name . '/mod.cordova-plugin-whitelist.json'))
{
    @unlink('projects/' . $file_name . '/mod.cordova-plugin-whitelist.json');
}
if (file_exists('projects/' . $file_name . '/mod.ionic-plugin-keyboard.json'))
{
    @unlink('projects/' . $file_name . '/mod.ionic-plugin-keyboard.json');
}
if (file_exists('projects/' . $file_name . '/mod..json'))
{
    @unlink('projects/' . $file_name . '/mod..json');
}

if (isset($_POST['add-plugin']))
{
    $plugin_name = basename($_POST['plugin']['name']);
    $mod = null;
    $mod['mod'][$plugin_name]['name'] = $plugin_name;
    $mod['mod'][$plugin_name]['engines'] = 'cordova';
    file_put_contents('projects/' . $file_name . '/mod.' . $plugin_name . '.json', json_encode($mod));
    buildIonic($file_name);
    header('Location: ./?page=x-custom-cordova-plugin&err=null&notice=save');
}

$content = $out_path = $footer = $html = null;

$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-code fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Custom Cordova Plugin</h4>';
$content .= '<blockquote class="blockquote blockquote-danger"><h4>'.__('The rules that apply are:').'</h4>'.__('<strong>Do not be disturbed!</strong> if you do not understand the use of cordova plugin, only for the custom code that needs cordova plugin.').'</blockquote>';

$content .= notice();
$form_input = null;
$form_input .= $bs->FormGroup('plugin[name]', 'default', 'text', __('Plugin Name'), 'cordova-plugin-camera', __('Fill with <code>cordova plugin id</code>, check in <a target="_blank" href="https://cordova.apache.org/plugins/">official cordova plugins</a>'), null, '8');
$form_input .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'add-plugin',
        'label' => __('Add Cordova Plugin').' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'), array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));


$content .= $bs->Forms('app-setup', '', 'post', 'default', $form_input);
$content .= '<div class="table-responsive">';
$content .= '<table class="table table-striped sortable">';
$content .= '<thead>';
$content .= '<tr>';
$content .= '<th>'.__('Plugin Name').'</th>';
$content .= '<th>'.__('Note').'</th>';
$content .= '<th></th>';
$content .= '</tr>';
$content .= '</thead>';
$content .= '<tbody>';
$content .= '<tr>';
$content .= '<td>';
$content .= '<h5>cordova-plugin-device</h5>';
$content .= '<a href="https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-device/" target="_blank">https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-device/<a>';
$content .= '</td>';
$content .= '<td>default</td>';
$content .= '<td></td>';
$content .= '</tr>';

$content .= '<tr>';
$content .= '<td>';
$content .= '<h5>cordova-plugin-console</h5>';
$content .= '<a href="https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-console/" target="_blank">https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-console/<a>';
$content .= '</td>';
$content .= '<td>default</td>';
$content .= '<td></td>';
$content .= '</tr>';

$content .= '<tr>';
$content .= '<td>';
$content .= '<h5>cordova-plugin-splashscreen</h5>';
$content .= '<a href="https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-splashscreen/" target="_blank">https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-splashscreen/<a>';
$content .= '</td>';
$content .= '<td>default</td>';
$content .= '<td></td>';
$content .= '</tr>';


$content .= '<tr>';
$content .= '<td>';
$content .= '<h5>cordova-plugin-statusbar</h5>';
$content .= '<a href="https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-statusbar/" target="_blank">https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-statusbar/<a>';
$content .= '</td>';
$content .= '<td>default</td>';
$content .= '<td></td>';
$content .= '</tr>';

$content .= '<tr>';
$content .= '<td>';
$content .= '<h5>cordova-plugin-whitelist</h5>';
$content .= '<a href="https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-whitelist/" target="_blank">https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-whitelist/<a>';
$content .= '</td>';
$content .= '<td>default</td>';
$content .= '<td></td>';
$content .= '</tr>';

$content .= '<tr>';
$content .= '<td>';
$content .= '<h5>cordova-plugin-statusbar</h5>';
$content .= '<a href="https://github.com/ionic-team/ionic-plugin-keyboard" target="_blank">https://github.com/ionic-team/ionic-plugin-keyboard<a>';
$content .= '</td>';
$content .= '<td>default</td>';
$content .= '<td></td>';
$content .= '</tr>';


foreach (glob("projects/" . $file_name . "/mod.*.json") as $mod_file)
{
    $mod_info = json_decode(file_get_contents($mod_file), true);
    $cordova = array_values($mod_info['mod']);
    if (!isset($cordova[0]['info']))
    {
        $cordova[0]['info'] = 'Custom Code';
    }
    $content .= '<tr>';
    $content .= '<td>';
    $content .= '<h5>' . $cordova[0]['name'] . '</h5>';
    $content .= '<a href="https://www.npmjs.com/package/' . $cordova[0]['name'] . '" target="_blank">https://www.npmjs.com/package/' . $cordova[0]['name'] . '</a>';
    $content .= '</td>';
    $content .= '<td>' . $cordova[0]['info'] . '</td>';
    $content .= '<td><a href="./?page=x-custom-cordova-plugin&delete=' . $cordova[0]['name'] . '" class="btn btn-danger">'.__('Delete').'</a></td>';
    $content .= '</tr>';
}
$content .= '</tbody>';
$content .= '</table>';
$content .= '</div>';

$template->demo_url = $out_path . '/www/';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Custom Cordova Plugin';
$template->base_desc = 'Docs';
$template->content = $content;
$template->footer = '';
$template->emulator = false;

?>