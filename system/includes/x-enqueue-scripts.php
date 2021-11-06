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
$form_input = null;
if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}
$max_scripts = 3;
$out_path = 'output/' . $file_name;
$content = $footer = null;
$bs = new jsmBootstrap();
if (isset($_POST['scripts-save']))
{


    $app_config['app'] = $_SESSION['PROJECT']['app'];
    $domain_whitelist = explode(',', $app_config['app']['domain']);

    foreach ($_POST['scripts'] as $script)
    {
        if (parse_url($script['url'], PHP_URL_HOST) != '')
        {
            $domain_whitelist[] = parse_url($script['url'], PHP_URL_HOST);
        }
    }
    foreach ($domain_whitelist as $domain)
    {
        $domain_name = rtrim(ltrim($domain));
        $_whitelist[$domain_name] = $domain_name;
    }
    $app_config['app']['domain'] = implode(',', $_whitelist);
    file_put_contents('projects/' . $file_name . '/app.json', json_encode($app_config));

    $scripts['scripts'] = $_POST['scripts'];
    file_put_contents('projects/' . $file_name . '/scripts.json', json_encode($scripts));
    buildIonic($file_name);
    header('Location: ./?page=x-enqueue-scripts&notice=save&err=null');
}

if (file_exists('projects/' . $file_name . '/scripts.json'))
{
    $raw_scripts = json_decode(file_get_contents('projects/' . $file_name . '/scripts.json'), true);

    if (isset($raw_scripts['scripts']['src']))
    {
        $max_scripts = count($raw_scripts['scripts']['src']);
    }
}

if (!isset($_GET['max-scripts']))
{
    $_GET['max-scripts'] = (int)$max_scripts;
}

$max_scripts = (int)$_GET['max-scripts'];

$_max_scripts = array();
for ($i = 0; $i <= 20; $i++)
{
    $x = $i;

    $_max_scripts[$i] = array('label' => $x, 'value' => $x);
    if ($max_scripts == $x)
    {
        $_max_scripts[$i]['active'] = true;
    }
}
$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">'.__('General').'</h5>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';
$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-3">';
$form_input .= $bs->FormGroup('max-scripts', 'default', 'select', __('Max CSS or JS Files'), $_max_scripts, null, ' onChange="window.location=\'?page=x-enqueue-scripts&max-scripts=\'+this.value;"');
$form_input .= '</div>';
$form_input .= '<div class="col-md-9">';

$form_input .= '<blockquote class="blockquote blockquote-danger">';
$form_input .= '<h4>' . __('The rules that apply are:') . '</h4>';
$form_input .= '<ul>';
$form_input .= '<li>'.__('It is recommended to put scripts files in a folder <code>/www/data/file/yourjs.js</code> that you can using url <code>data/file/yourjs.js</code>, and don\'t put scripts files in the folder <code>/www/js/</code> and <code>/www/lib/</code>, because it will be automatically deleted.').'</li>';
$form_input .= '<li><a href="./system/plugin/kcfinder/browse.php?type=file" target="_blank" >'.__('Upload').'</a> ';
$form_input .= __('or copy file to:').' <code>'. realpath( JSM_PATH .'/output/'.$file_name.'/www/data/file/').'</code>'; 
$form_input .= __('then fill URL with:').'<code>data/file/file.css</code></li>';   
$form_input .= '</ul>';
$form_input .= '</blockquote>';
$form_input .= '</div>';

$form_input .= '</div>';
$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= '<div class="panel panel-default">';

$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">'.__('CSS or JS Files').'</h5>';
$form_input .= '</div>';

$form_input .= '<div class="panel-body">';
$form_input .= '<table class="table table-striped sortable">';
$form_input .= '<thead>';
$form_input .= '<tr>';
$form_input .= '<th></th>';
$form_input .= '<th>'.__('URL').' <span style="color:red">*</span></th>';
$form_input .= '<th>'.__('Type').'</th>';
//$form_input .= '<th></th>';
$form_input .= '<th></th>';
$form_input .= '</tr>';
$form_input .= '</thead>';
$form_input .= '<tbody>';


$items_types[] = array('label' => 'CSS', 'value' => 'css');
$items_types[] = array('label' => 'JS', 'value' => 'js');


for ($i = 0; $i < $max_scripts; $i++)
{
    if (!isset($raw_scripts['scripts']['src'][$i]['url']))
    {
        $raw_scripts['scripts']['src'][$i]['url'] = '';
    }
    if (!isset($raw_scripts['scripts']['src'][$i]['type']))
    {
        $raw_scripts['scripts']['src'][$i]['type'] = 'js';
    }
    $_items_type = array();
    foreach ($items_types as $items_type)
    {
        $_items_type[$x] = $items_type;
        if ($raw_scripts['scripts']['src'][$i]['type'] == $items_type['value'])
        {
            $_items_type[$x]['active'] = true;
        }
        $x++;
    }

    $form_input .= '<tr id="data-' . $i . '">';

    $form_input .= '<td class="v-align">';
    $form_input .= '<span class="glyphicon glyphicon-move"></span>';
    $form_input .= '</td>';

    $form_input .= '<td>';
    $form_input .= $bs->FormGroup('scripts[src][' . $i . '][url]', 'default', 'text', '', 'URL ' . $i, '', 'required', '8', $raw_scripts['scripts']['src'][$i]['url']);
    $form_input .= '</td>';

    $form_input .= '<td>';
    $form_input .= $bs->FormGroup('scripts[src][' . $i . '][type]', 'default', 'select', '', $_items_type, '', '', '');
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

$dependency[] = 'ionic';
$dependency[] = 'ionMdInput';
$dependency[] = 'ionic-material';
$dependency[] = 'ionic.rating';
$dependency[] = 'utf8-base64';
$dependency[] = 'ionicLazyLoad';
$dependency[] = 'ngMap';

if (!isset($raw_scripts['scripts']['dependency']))
{
    $raw_scripts['scripts']['dependency'] = array();
}
$app_prefix = $_SESSION['PROJECT']['app']['prefix'];
$default_dependency = null;
$default_dependency .= '<span class="label label-danger"/>'.__('Note</span>: Only for programmer, wrong dependency will break your app. dependency using separator with coma');
$default_dependency .= '<br/>'.__('Default Dependency:').' <br/><code>angular.module("' . $app_prefix . '", ["ngCordova", "' . implode('", "', $dependency) . '", "' . $app_prefix . '.controllers", "' . $app_prefix . '.services"])</code>';

$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">'.__('For AngularJS').'</h5>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';
$form_input .= $bs->FormGroup('scripts[dependency]', 'default', 'text', 'Dependency', 'ngABC,ngDEF', $default_dependency, '', '', $raw_scripts['scripts']['dependency']);
$form_input .= '</div>';
$form_input .= '</div>';

$form_input .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'scripts-save',
        'label' => __('Save Setting').' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'), array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));

$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-slack fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Enqueue Scripts</h4>';
$content .= $bs->Forms('scripts-setup', '', 'post', 'default', $form_input);


$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Enqueue Scripts';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = true;

?>