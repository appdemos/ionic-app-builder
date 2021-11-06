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
$form_input = $html = $js_helper = null;
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
$bs = new jsmBootstrap();
$langs = new jsmLocale();
$content = $out_path = $footer = $html = null;
$out_path = 'output/' . $file_name;
if (!isset($_GET['lang']))
{
    $_GET['lang'] = $_SESSION['PROJECT']['app']['locale'];
}


if (isset($_POST['multilingual']))
{
    $text_lang = array();
    foreach ($_POST['multilingual'] as $lang)
    {
        $text_lang[$lang['var']] = $lang['val'];
    }
    $path_lang = $out_path . '/www/translations/' . basename($_GET['lang']) . '.json';
    $text_json = json_encode($text_lang);
    file_put_contents($path_lang, $text_json);

    // TODO: CREATE PAGE FOR LANGUAGE
 
    $page_content = null;
    $page_content .= '<ion-list class="list" >';
    $page_content .= '<div class="item item-divider">{{ \'Select a language?\' | translate }}</div>';
    foreach ($_SESSION['PROJECT']['translation']['lang'] as $menu)
    {
        $checked = '';
        if ($_SESSION['PROJECT']['app']['locale'] == $menu['prefix'])
        {
            $checked = '';
        }
        //$page_content .= '<ion-radio '.$checked.' ng-model="menu_language_option" icon="icon ion-android-radio-button-on" ng-click="tryChangeLanguage(\'' . $menu['prefix'] . '\')" ng-value="\'' . $menu['prefix'] . '\'">' . $menu['label'] . '</ion-radio>';
        $page_content .= '<button class="button button-full" ng-click="tryChangeLanguage(\'' . $menu['prefix'] . '\')" >' . $menu['label'] . '</button>';

    }
    $page_content .= '</ion-list>';


    $new_page = null;
    $new_page['page'][] = array(
        'title' => '{{ \'Language\' | translate }}',
        'prefix' => 'language',
        'for' => '-',
        'last_edit_by' => 'menu',
        'builder_link' => '',
        'priority' => 'low',
        'parent' => '',
        'menutype' => $_SESSION['PROJECT']['menu']['type'] . '-custom',
        'menu' => '',
        'lock' => false,
        'version' => 'Upd.' . date('ymdhi'),
        'js' => '$ionicConfig.backButton.text("");',
        'class' => 'padding',
        'bg_image' => true,
        'content' => $page_content);
    // TODO: -- | -- save - about_us
    $is_lock = false;
    $lock_path = 'projects/' . $file_name . '/page.language.json';
    if (file_exists($lock_path))
    {
        $lock_data = json_decode(file_get_contents($lock_path), true);
        $is_lock = $lock_data['page'][0]['lock'];
    }
    if ($is_lock == true)
    {
        $error_notice[] = 'Page <code>language</code> is <span class="fa fa-lock"></span> locked.';
    } else
    {
        if (file_exists('projects/' . $file_name . '/page.language.json'))
        {
            @copy('projects/' . $file_name . '/page.language.json', 'projects/' . $file_name . '/page.language.json' . '.' . time() . '.save');
        }
        file_put_contents('projects/' . $file_name . '/page.language.json', json_encode($new_page));
    }


    buildIonic($file_name);
    header('Location: ./?page=x-multilingual&err=null&notice=save&lang=' . basename($_GET['lang']) . '');
    die();
}
if ($_GET['act'] == 'delete')
{
    $path_lang = $out_path . '/www/translations/' . basename($_GET['lang']) . '.json';
    unlink($path_lang);
    buildIonic($file_name);
    header('Location: ./?page=x-multilingual');
    die();
}
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-exchange fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Multilingual</h4>';
$e_locale[] = array('label' => 'English - US', 'value' => 'en-us');
foreach ($langs->getLang() as $lang)
{
    $e_locale[] = array('label' => $lang['label'] . ' (' . $lang['prefix'] . ')', 'value' => $lang['prefix']);
}
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h4 class="panel-title">' . __('General') . '</h4></div>';
$content .= '<div class="panel-body">';
$content .= '<blockquote class="blockquote blockquote-danger">';
$content .= '<h4>' . __('The rules that apply are:') . '</h4>';
$content .= '<ol>';
$content .= '<li>' . __('Use <code>English-US</code> as the default language of the app, Go to <a href="./?page=dashboard" target="_blank">(IMAB) Dashboard</a> -&raquo; Edit -&raquo; Locale') . '</li>';
$content .= '<li>' . __('Go to <a href="./?page=popover" target="_blank">(IMAB) Popover</a> -&raquo; Add Menu Item -&raquo; type: <code>Dialog - Language Option</code>') . '</li>';
$content .= '</ol>';
$content .= '</blockquote>';

$content .= '<form action="">';
$content .= '<div class="row">';
$content .= '<div class="col-md-6">';
$content .= $bs->FormGroup('lang', 'default', 'select', __('Select Language'), $e_locale, '', null, '8');
$content .= '</div>';
$content .= '<div class="col-md-6">';
$content .= '<input type="hidden" name="page" value="x-multilingual" />';
$content .= '<br/><input type="submit" class="btn btn-danger" value="' . __('Add Language') . '" />';
$content .= '</div>';
$content .= '</div>';
$content .= '</form>';
$content .= '<table class="table table-striped">';
$content .= '<tbody>';
foreach (glob($out_path . '/www/translations/*.json') as $filename)
{
    $var_lang = str_replace('.json', '', basename($filename));
    $link = '';
    if ($var_lang !== $_SESSION['PROJECT']['app']['locale'])
    {
        $link .= '<a href="./?page=x-multilingual&lang=' . $var_lang . '&act=edit" class="btn btn-xs btn-success">' . __('Edit') . '</a> ';
        $link .= '<a href="./?page=x-multilingual&lang=' . $var_lang . '&act=delete" class="btn btn-xs btn-danger">' . __('Delete') . '</a>';
    }
    $content .= '<tr>';
    $content .= '<td>' . $langs->getLabel($var_lang) . '</td>';
    $content .= '<td>' . $link . '</td>';
    $content .= '</tr>';
}
$content .= '</tbody>';
$content .= '</table>';
$content .= '</div>';
$content .= '</div>';
if (strlen($_GET['lang']) > 1)
{
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading"><h4 class="panel-title">' . __('Edit Language') . '</h4></div>';
    $content .= '<div class="panel-body">';
    $path_lang = $out_path . '/www/translations/' . $_SESSION['PROJECT']['app']['locale'] . '.json';
    $content .= '<form method="post" action="">';
    $content .= '<table class="table table-striped">';
    $content .= '
        <thead>
        <tr>
            <th>' . $langs->getLabel($_SESSION['PROJECT']['app']['locale']) . '<th>
            <th>' . $langs->getLabel($_GET['lang']) . '</th>
        </tr>
        </thead>
        ';
    $content .= '<tbody>';
    if (file_exists($path_lang))
    {
        $curr_lang_path = $out_path . '/www/translations/' . basename($_GET['lang']) . '.json';
        if (file_exists($curr_lang_path))
        {
            $curr_text = json_decode(file_get_contents($curr_lang_path), true);
        } else
        {
            $curr_text = json_decode(file_get_contents($path_lang), true);
        }
        $text_langs = json_decode(file_get_contents($path_lang), true);
        foreach (array_keys($text_langs) as $text_lang)
        {
            if (!isset($curr_text[$text_lang]))
            {
                $curr_text_lang = '';
            } else
            {
                $curr_text_lang = $curr_text[$text_lang];
            }
            $content .= '
        <tr>
            <td>' . $text_lang . '<td>
            <td>
                <input type="hidden" name="multilingual[' . sha1($text_lang) . '][var]" class="form-control" value="' . $text_lang . '" />
                <input type="text"   name="multilingual[' . sha1($text_lang) . '][val]" class="form-control" value="' . $curr_text_lang . '" />
            </td>
        </tr>';
        }
    }
    $content .= '</tbody>';
    $content .= '</table>';
    $content .= '<input type="submit" class="btn btn-primary" value="' . __('Update Language') . ' (2x click)"/>';
    $content .= '</form>';
    $content .= '</div>';
    $content .= '</div>';
}
$subpage_path = str2var($_SESSION["PROJECT"]['menu']['title']);
$template->demo_url = $out_path . '/www/#/' . $subpage_path . '/language';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Multilingual ';
$template->base_desc = 'Docs';
$template->content = $content;
$template->footer = '';
$template->emulator = true;

?>