<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

$require_target_page = true;

$addons['author'] = 'Jasman';
$addons['email'] = 'jasman@ihsana.com';

$bg_colors = array(
    'transparent',
    'stable',
    'light',
    'positive',
    'positive-900',
    'calm',
    'calm-900',
    'balanced',
    'balanced-900',
    'energized',
    'energized-900',
    'assertive',
    'assertive-900',
    'royal',
    'royal-900',
    );

$bs = new jsmBootstrap();
if (!isset($_GET['source']))
{
    $_GET['source'] = '';
}
if (!isset($_GET['target']))
{
    $_GET['target'] = '';
}
$_js_for_var = null;
$project = new ImaProject();
// TODO: page target
$option_page[] = array('label' => '< select page >', 'value' => '');
$z = 1;
foreach ($project->get_pages() as $page)
{

    $option_page[$z] = array('label' => 'Page `' . $page['prefix'] . '` ' . $page['builder'] . '', 'value' => $page['prefix']);
    if ($_GET['target'] == $page['prefix'])
    {
        $option_page[$z]['active'] = true;
    }
    $z++;

}
$direction = null;
if ($_SESSION["PROJECT"]['app']['direction'] == 'rtl')
{
    $direction = 'dir="rtl"';
}

if (isset($_POST['page-builder']))
{
    $postdata['prefix'] = str2var($_GET['target']);
    $postdata['source'] = str2var($_GET['source']);

    $app_json = file_get_contents(JSM_PATH . '/projects/' . $_SESSION['FILE_NAME'] . '/app.json');
    $app_config = json_decode($app_json, true);
    $app_config['app']['index'] = $postdata['prefix'];

    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/app.json', json_encode($app_config));

    $_POST['page_menu_box']['menu']['items'][0]['type'] = 'divider';
    $_POST['page_menu_box']['menu']['items'][0]['desc'] = '';
    $_POST['page_menu_box']['menu']['items'][0]['option'] = '';

    if (isset($_POST['page_menu_box']['slider']))
    {
        $_POST['page_menu_box']['slider'] = true;
    } else
    {
        $_POST['page_menu_box']['slider'] = false;
    }

    if (isset($_POST['page_menu_box']['hide-divider']))
    {
        $_POST['page_menu_box']['hide-divider'] = true;
    } else
    {
        $_POST['page_menu_box']['hide-divider'] = false;
    }

    if (isset($_POST['page_menu_box']['inverts-color']))
    {
        $_POST['page_menu_box']['inverts-color'] = true;
    } else
    {
        $_POST['page_menu_box']['inverts-color'] = false;
    }


    $json_save['page_builder']['page_menu_box'][$postdata['prefix']] = $_POST['page_menu_box'];
    $json_save['page_builder']['page_menu_box'][$postdata['prefix']]['menu']['items'] = array_values($_POST['page_menu_box']['menu']['items']);

    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.page_menu_box.' . $postdata['prefix'] . '.json', json_encode($json_save));

    $_menu_list = array();
    $data = $_POST['page_menu_box'];
    $is_use_divider = false;
    $menu_list = "\r\n";

    if ($_POST['page_menu_box']['slider'] == true)
    {
        $menu_list .= "\r\n";
        $menu_list .= "\t" . '<div class="slide-box-hero">' . "\r\n";
        $menu_list .= "\t\t" . '<ion-slides class="slide-box-hero-content" options="{slidesPerView:1,autoplay:10000,loop:1}" slider="data.slider">' . "\r\n";
        foreach ($data['barner']['items'] as $barner)
        {
            if (strlen($barner['src']) > 5)
            {
                $menu_list .= "\t\t\t" . '<ion-slide-page>' . "\r\n";
                $menu_list .= "\t\t\t\t" . '<img ng-src="' . $barner['src'] . '" class="" />' . "\r\n";
                $menu_list .= "\t\t\t" . '</ion-slide-page>' . "\r\n";
            }
        }
        $menu_list .= "\t\t" . '</ion-slides>' . "\r\n";
        $menu_list .= "\t" . '</div>' . "\r\n";

    }
    $menu_list .= "\t\t" . '<div class="dashboard-panel">' . "\r\n";
    $z = 0;
    $co_index = 0;
    foreach ($data['menu']['items'] as $menu_item)
    {
        if ($menu_item['type'] != "divider")
        {
            $sref = null;
            $z++;
            if ($z == 1)
            {
                $menu_list .= "\t\t\t" . '<!-- row -->' . "\r\n";
                $menu_list .= "\t\t\t" . '<div class="row">' . "\r\n";
            }
            if ($menu_item['type'] == 'link')
            {
                $sref = ' ng-href="' . $menu_item['option'] . '"';
            }
            $type_webview = array(
                "webview",
                "app-browser",
                "ext-browser");
            if (in_array($menu_item['type'], $type_webview))
            {
                if ($menu_item['option'] == "")
                {
                    $menu_item['option'] = $_SESSION['PROJECT']['app']['author_url'];
                }
            }
            if ($menu_item['type'] == 'app-browser')
            {
                $sref = 'ng-click="openAppBrowser(\'' . htmlentities($menu_item['option']) . '\')"';
            }
            if ($menu_item['type'] == 'ext-browser')
            {
                $sref = 'ng-click="openURL(\'' . htmlentities($menu_item['option']) . '\')"';
            }
            if ($menu_item['type'] == 'webview')
            {
                $sref = 'ng-click="openWebView(\'' . htmlentities($menu_item['option']) . '\')"';
            }
            if ($menu_item['type'] == 'ext-email')
            {
                if ($menu_item['option'] == "")
                {
                    $menu_item['option'] = $_SESSION['PROJECT']['app']['author_email'];
                }
                $sref = 'ng-click="openURL(\'mailto:' . htmlentities($menu_item['option']) . '\')"';
            }
            if ($menu_item['type'] == 'ext-sms')
            {
                if ($menu_item['option'] == "")
                {
                    $menu_item['option'] = '08123456789';
                }
                $sref = 'ng-click="openURL(\'sms:' . htmlentities($menu_item['option']) . '\')"';
            }
            if ($menu_item['type'] == 'ext-call')
            {
                if ($menu_item['option'] == "")
                {
                    $menu_item['option'] = '08123456789';
                }
                $sref = 'ng-click="openURL(\'tel:' . htmlentities($menu_item['option']) . '\')"';
            }
            if ($menu_item['type'] == 'ext-playstore')
            {
                if ($menu_item['option'] == "")
                {
                    $menu_item['option'] = str_replace("_", "", JSM_PACKAGE_NAME . '.' . str2var($_SESSION['PROJECT']['app']['company']) . "." . str2var($_SESSION['PROJECT']['app']['prefix']));
                }
                $sref = 'ng-click="openURL(\'market://details?id=' . htmlentities($menu_item['option']) . '\')"';
            }
            if ($menu_item['type'] == 'ext-geo')
            {
                if ($menu_item['option'] == "")
                {
                    $menu_item['option'] = "";
                }
                $sref = 'ng-click="openURL(\'geo:' . htmlentities($menu_item['option']) . '\')"';
            }
            if (!isset($menu_item['label']))
            {
                $menu_item['label'] = 'calm';
            }
            if ($menu_item['label'] == '')
            {
                $menu_item['label'] = 'calm';
            }
            if ($data['inverts-color'] == true)
            {
                $code_color_font = $menu_item['color'];
                $code_color_bg = 'transparent-bg';
            } else
            {
                $code_color_font = 'transparent';
                $code_color_bg = $menu_item['color'] . '-bg';
            }
            $menu_list .= "\t\t\t\t" . '<a id="menu-box-' . sha1($menu_item['label']) . '" class="col-33 ' . $code_color_bg . ' ' . $code_color_font . ' ink" ' . $sref . ' >' . "\r\n";
            $menu_list .= "\t\t\t\t\t" . '<i class="icon ' . htmlentities($menu_item['icon']) . '" ></i>' . "\r\n";
            $menu_list .= "\t\t\t\t\t" . '<p>' . htmlentities($menu_item['label']) . '</p>' . "\r\n";
            $menu_list .= "\t\t\t\t" . '</a>' . "\r\n";

            $co_index++;
            if ($co_index == 11)
            {
                $co_index = 0;
            }
            if ($z == 3)
            {
                $menu_list .= "\t\t\t" . '</div>' . "\r\n";
                $menu_list .= "\t\t\t" . '<!-- ./row -->' . "\r\n";
                $menu_list .= "\t\t\t" . "\r\n\r\n";
                $z = 0;
            }
        } else
        {
            if ($z != 0)
            {
                $menu_list .= "\t\t\t" . '</div>' . "\r\n";
                $menu_list .= "\t\t\t" . '<!-- ./row -->' . "\r\n";
            }
            if (!isset($data['hide-divider']))
            {
                $data['hide-divider'] = false;
            }
            if ($data['hide-divider'] == false)
            {
                $menu_list .= "\t\t\t" . '<div class="item item-title item-' . htmlentities($menu_item['color']) . ' no-border">' . htmlentities($menu_item['label']) . '</div>' . "\r\n";
            } else
            {
                $menu_list .= "\t\t\t" . '<div></div>' . "\r\n";
            }
            $z = 0;
        }
    }

    if (($z < 3) && ($z != 0))
    {
        $menu_list .= "\t\t\t" . '</div>' . "\r\n";
        $menu_list .= "\t\t\t" . '<!-- ./row -->' . "\r\n";
        $menu_list .= "\t\t\t" . "\r\n\r\n";
    }

    $menu_list .= "\t\t" . '</div>' . "\r\n";

    $menu_list .= '<br/>';
    $menu_list .= '<br/>';
    $menu_list .= '<br/>';

    $css_dashboard = '
.dashboard-panel .row .col-33 {text-decoration-line: unset;text-align: center;padding: 22px 20px 10px 20px;border:0;}
.dashboard-panel .row .col-33 i {font-size: 28px;margin-bottom: 2px;}
.dashboard-panel a:link, .dashboard-panel a:visited{text-decoration: none;}
.dashboard-panel .row .col-33 p {font-size: 12px;font-weight:500;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}              
    ';
    foreach ($data['menu']['items'] as $menu_item)
    {
        if (strlen($menu_item['icon-img']) > 2)
        {
            $css_dashboard .= '#menu-box-' . sha1($menu_item['label']) . ' i:before{ content: url("../' . $menu_item['icon-img'] . '");}' . "\r\n";
        }
    }
    $new_page_class = $postdata['prefix'];
    $new_page_title = htmlentities($data['title']);
    $new_page_prefix = $postdata['prefix'];
    $new_page_content = $menu_list;
    $new_page_css = $css_dashboard;
    $new_page_js = '';
    $url_background = htmlentities($data['background']);
    create_page($new_page_class, $new_page_title, $new_page_prefix, $new_page_content, $new_page_css, $new_page_js, true, false, false, false, false, false, false, false, false, $url_background);
}

$raw_menu['title'] = 'Menu';
$raw_menu['background'] = 'data/images/background/transparent.png';
$raw_menu['slider'] = false;

$raw_menu['menu']['items'][0]['label'] = 'Service';
$raw_menu['menu']['items'][0]['icon'] = 'ion-ios-home';
$raw_menu['menu']['items'][0]['type'] = 'divider';
$raw_menu['menu']['items'][0]['desc'] = '';
$raw_menu['menu']['items'][0]['option'] = '';

$raw_menu['menu']['items'][1]['label'] = 'Knowledge Center';
$raw_menu['menu']['items'][1]['icon'] = 'ion-ios-lightbulb';
$raw_menu['menu']['items'][1]['type'] = 'app-browser';
$raw_menu['menu']['items'][1]['desc'] = 'FAQ and Tutorials';
$raw_menu['menu']['items'][1]['option'] = 'http://domain.com/faq';

$raw_menu['menu']['items'][2]['label'] = 'Email Us';
$raw_menu['menu']['items'][2]['icon'] = 'ion-email';
$raw_menu['menu']['items'][2]['option'] = 'cs@domain.com';
$raw_menu['menu']['items'][2]['type'] = 'ext-email';
$raw_menu['menu']['items'][2]['desc'] = 'Your personal, dedicated enquiry portal';

$raw_menu['menu']['items'][3]['label'] = 'Call Us';
$raw_menu['menu']['items'][3]['icon'] = 'ion-android-call';
$raw_menu['menu']['items'][3]['option'] = '+6282123456788';
$raw_menu['menu']['items'][3]['type'] = 'ext-call';
$raw_menu['menu']['items'][3]['desc'] = 'Speak to a live agent for support';


$raw_menu['menu']['items'][4]['label'] = 'Products';
$raw_menu['menu']['items'][4]['icon'] = 'ion-ios-cart';
$raw_menu['menu']['items'][4]['option'] = '';
$raw_menu['menu']['items'][4]['type'] = 'divider';
$raw_menu['menu']['items'][4]['desc'] = '';

$raw_menu['menu']['items'][5]['label'] = 'Store';
$raw_menu['menu']['items'][5]['icon'] = 'ion-ios-cart';
$raw_menu['menu']['items'][5]['type'] = 'app-browser';
$raw_menu['menu']['items'][5]['desc'] = 'Shoping easier';
$raw_menu['menu']['items'][5]['option'] = 'http://domain.com/shop';

$raw_menu['menu']['items'][6]['label'] = 'Products';
$raw_menu['menu']['items'][6]['icon'] = 'ion-cash';
$raw_menu['menu']['items'][6]['type'] = 'app-browser';
$raw_menu['menu']['items'][6]['desc'] = 'The latest our product information';
$raw_menu['menu']['items'][6]['option'] = 'http://domain.com/products';

$raw_menu['menu']['items'][7]['label'] = 'News';
$raw_menu['menu']['items'][7]['icon'] = 'ion-ios-paper';
$raw_menu['menu']['items'][7]['type'] = 'app-browser';
$raw_menu['menu']['items'][7]['desc'] = 'The latest news';
$raw_menu['menu']['items'][7]['option'] = 'http://domain.com/news';

$raw_menu['menu']['items'][8]['label'] = 'Community';
$raw_menu['menu']['items'][8]['icon'] = 'ion-videocamera';
$raw_menu['menu']['items'][8]['option'] = '';
$raw_menu['menu']['items'][8]['type'] = 'divider';
$raw_menu['menu']['items'][8]['desc'] = '';

$raw_menu['menu']['items'][9]['label'] = 'Youtube';
$raw_menu['menu']['items'][9]['icon'] = 'ion-social-youtube';
$raw_menu['menu']['items'][9]['type'] = 'app-browser';
$raw_menu['menu']['items'][9]['desc'] = 'Watch video now';
$raw_menu['menu']['items'][9]['option'] = 'http://youtube.com/xxxx';

$raw_menu['menu']['items'][10]['label'] = 'Facebook';
$raw_menu['menu']['items'][10]['icon'] = 'ion-social-facebook';
$raw_menu['menu']['items'][10]['type'] = 'app-browser';
$raw_menu['menu']['items'][10]['desc'] = 'Information event and hot deals';
$raw_menu['menu']['items'][10]['option'] = 'http://facebook.com/news';

$raw_menu['menu']['items'][11]['label'] = 'Twitter';
$raw_menu['menu']['items'][11]['icon'] = 'ion-social-twitter';
$raw_menu['menu']['items'][11]['type'] = 'app-browser';
$raw_menu['menu']['items'][11]['desc'] = 'Find everything';
$raw_menu['menu']['items'][11]['option'] = 'http://twitter.com/xxxx';

$raw_menu['barner']['items'][0]['src'] = 'data/images/images/barner-1.jpg';
$raw_menu['barner']['items'][1]['src'] = 'data/images/images/barner-2.jpg';
$raw_menu['barner']['items'][2]['src'] = '';
$raw_menu['barner']['items'][3]['src'] = '';


$max_menu = 15;

$page_target = str2var($_GET['target']);
if (file_exists('projects/' . $file_name . '/page_builder.page_menu_box.' . $page_target . '.json'))
{
    $_raw_menu = json_decode(file_get_contents('projects/' . $file_name . '/page_builder.page_menu_box.' . $page_target . '.json'), true);

    $raw_menu = $_raw_menu['page_builder']['page_menu_box'][$page_target];
    $max_menu = count($raw_menu['menu']['items']);
} else
{
    $main_menu_raw = json_decode(file_get_contents('projects/' . $file_name . '/menu.json'), true);
    $main_menus = $main_menu_raw['menu']['items'];
    $z = 0;
    foreach ($main_menus as $main_menu)
    {
        $__main_menu[$z] = $main_menu;
        if ($main_menu['type'] == 'link')
        {
            $__main_menu[$z]['option'] = '#/' . $file_name . '/' . str2var($main_menu['var']);
        }
        $z++;
    }
    $raw_menu['menu']['items'] = $__main_menu;
    $max_menu = count($raw_menu['menu']['items']);
}

$max_menu = count($raw_menu['menu']['items']);

if (!isset($_GET['max-menu']))
{
    $_GET['max-menu'] = $max_menu;
}

if (!isset($raw_menu['background']))
{
    $raw_menu['background'] = 'data/images/background/transparent.png';
}
if (!isset($raw_menu['title']))
{
    $raw_menu['title'] = 'Welcome';
}

if (!isset($raw_menu['slider']))
{
    $raw_menu['slider'] = false;
}
if (!isset($raw_menu['inverts-color']))
{
    $raw_menu['inverts-color'] = false;
}
if (!isset($raw_menu['hide-divider']))
{
    $raw_menu['hide-divider'] = false;
}
if ($_GET['max-menu'] == 'undefined')
{
    header('Location: ./?page=x-page-builder&prefix=page_menu_box&max-menu=' . $max_menu . '&target=' . $page_target);
}

$max_menu = $_GET['max-menu'];


for ($z = 5; $z < 100; $z++)
{
    if ($_GET['max-menu'] == $z)
    {
        $option_menu[] = array(
            'value' => $z,
            'label' => $z,
            'active' => true);
    } else
    {
        $option_menu[] = array('value' => $z, 'label' => $z);
    }
}

$form_input .= '
    <blockquote class="blockquote blockquote-info">
    <ul>
    <li>Separate with a divider/title that can work with a gorgeous menu and using a divider for create a group menu</li>
    <li>This page used as home page/index, if you do not want it, please change it through the <code>(IMAB) Page</code> -&gt; <code>Page Manager</code></li>
    </ul>
    </blockquote>';

$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');

$items_type[] = array('label' => 'Divider / Title', 'value' => 'divider');
$items_type[] = array('label' => 'Link Internal', 'value' => 'link');
$items_type[] = array('label' => 'Open - WebView', 'value' => 'webview');
$items_type[] = array('label' => 'Open - AppBrowser', 'value' => 'app-browser');
$items_type[] = array('label' => 'Open - External Browser', 'value' => 'ext-browser');
$items_type[] = array('label' => 'Open - App Email', 'value' => 'ext-email');
$items_type[] = array('label' => 'Open - App SMS', 'value' => 'ext-sms');
$items_type[] = array('label' => 'Open - App Call', 'value' => 'ext-call');
$items_type[] = array('label' => 'Open - App PlayStore', 'value' => 'ext-playstore');
$items_type[] = array('label' => 'Open - App GEO', 'value' => 'ext-geo');

foreach ($bg_colors as $bg_color)
{
    $items_color[] = array('label' => $bg_color, 'value' => $bg_color);
}


if ($_GET['target'] != '')
{
    $form_input .= $bs->FormGroup('max_menu', 'horizontal', 'select', 'Max Menu', $option_menu, '', null, '4');
    $form_input .= $bs->FormGroup('page_menu_box[title]', 'horizontal', 'text', 'Title', '', '', null, '5', $raw_menu['title']);
    $form_input .= $bs->FormGroup('page_menu_box[background]', 'horizontal', 'text', 'Background', '', '', 'data-type="image-picker"', '7', $raw_menu['background']);
    $checked_slider = false;
    if ($raw_menu['slider'] == true)
    {
        $checked_slider = 'checked="checked"';
    }
    $form_input .= $bs->FormGroup('page_menu_box[slider]', 'horizontal', 'checkbox', '', 'Enable Heroes Slider', '', $checked_slider);


    $inverts_color = false;
    if ($raw_menu['inverts-color'] == true)
    {
        $inverts_color = 'checked="checked"';
    }
    $form_input .= $bs->FormGroup('page_menu_box[inverts-color]', 'horizontal', 'checkbox', '', 'Inverts Color/Background', '', $inverts_color);

    $hide_divider = false;
    if ($raw_menu['hide-divider'] == true)
    {
        $hide_divider = 'checked="checked"';
    }
    $form_input .= $bs->FormGroup('page_menu_box[hide-divider]', 'horizontal', 'checkbox', '', 'Hide Divider/Title', '', $hide_divider);


    $form_input .= '<div class="panel panel-default">';
    $form_input .= '<div class="panel-body">';
    $form_input .= '<h4>Items</h4>';

    $form_input .= '<table class="table table-striped sortable">';
    $form_input .= '<thead>';
    $form_input .= '<tr>';
    $form_input .= '<th></th>';
    $form_input .= '<th>Label <span style="color:red">*</span></th>';
    $form_input .= '<th>Icon <span style="color:red">*</span></th>';
    $form_input .= '<th>Image</th>';
    $form_input .= '<th>Email<br/>URL<br/>Phone</th>';
    $form_input .= '<th>Desc</th>';
    $form_input .= '<th>Type</th>';
    $form_input .= '<th>Color</th>';
    $form_input .= '<th></th>';
    $form_input .= '</tr>';
    $form_input .= '</thead>';
    $form_input .= '<tbody>';
    $_js_for_var = null;
    for ($i = 0; $i < $max_menu; $i++)
    {
        $divider = '';
        if ($i == 0)
        {
            $divider = 'disabled';
            $_raw_menu['menu']['items'][0]['type'] = 'divider';
            $_raw_menu['menu']['items'][0]['desc'] = '';
            $_raw_menu['menu']['items'][0]['option'] = '';
        }

        $z = $i + 1;
        $_raw_menu['menu']['items'][$i]['label'] = '';
        $_raw_menu['menu']['items'][$i]['icon'] = '';
        $_raw_menu['menu']['items'][$i]['type'] = 'link';
        $_raw_menu['menu']['items'][$i]['option'] = '';
        $_raw_menu['menu']['items'][0]['color'] = 'calm';

        if (isset($raw_menu['menu']['items'][$i]['label']))
        {
            if (!isset($raw_menu['menu']['items'][$i]['color']))
            {
                $raw_menu['menu']['items'][$i]['color'] = 'calm';

            }
            if(!isset($raw_menu['menu']['items'][$i]['icon-img'])){
                $raw_menu['menu']['items'][$i]['icon-img'] ='';
            }
            $_raw_menu['menu']['items'][$i]['label'] = $raw_menu['menu']['items'][$i]['label'];
            $_raw_menu['menu']['items'][$i]['icon'] = $raw_menu['menu']['items'][$i]['icon'];
            $_raw_menu['menu']['items'][$i]['icon-img'] = $raw_menu['menu']['items'][$i]['icon-img'];
            $_raw_menu['menu']['items'][$i]['option'] = $raw_menu['menu']['items'][$i]['option'];
            $_raw_menu['menu']['items'][$i]['type'] = $raw_menu['menu']['items'][$i]['type'];
            $_raw_menu['menu']['items'][$i]['desc'] = $raw_menu['menu']['items'][$i]['desc'];
            $_raw_menu['menu']['items'][$i]['color'] = $raw_menu['menu']['items'][$i]['color'];
        }

        $z = 0;

        foreach ($items_type as $_item_type)
        {
            $_items_type[$z] = $_item_type;
            if ($_raw_menu['menu']['items'][$i]['type'] == $_item_type['value'])
            {
                $_items_type[$z]['active'] = true;
            }
            $z++;
        }

        if (!isset($_raw_menu['menu']['items'][$i]['desc']))
        {
            $_raw_menu['menu']['items'][$i]['desc'] = '';
        }

        if (!isset($_raw_menu['menu']['items'][$i]['color']))
        {
            $_raw_menu['menu']['items'][$i]['color'] = 'calm';
        }

        if ($_raw_menu['menu']['items'][$i]['color'] == '')
        {
            $_raw_menu['menu']['items'][$i]['color'] = 'calm';
        }

        $z = 0;
        foreach ($items_color as $_item_color)
        {
            $_items_color[$z] = $_item_color;

            if ($_raw_menu['menu']['items'][$i]['color'] == $_item_color['value'])
            {
                $_items_color[$z]['active'] = true;
            }
            $z++;
        }

        if (!isset($_raw_menu['menu']['items'][$i]['icon-img']))
        {
            $_raw_menu['menu']['items'][$i]['icon-img'] = '';
        }

        $form_input .= '<tr id="data-' . $i . '">';

        $form_input .= '<td class="v-align">';
        $form_input .= '<span class="glyphicon glyphicon-move"></span>';
        $form_input .= '</td>';

        $form_input .= '<td>';
        $form_input .= $bs->FormGroup('page_menu_box[menu][items][' . $i . '][label]', 'default', 'text', '', 'Menu ' . $i, '<em>Nice text</em>', 'required ' . $direction, '8', $_raw_menu['menu']['items'][$i]['label']);
        $form_input .= '</td>';

        $form_input .= '<td>';
        $form_input .= $bs->FormGroup('page_menu_box[menu][items][' . $i . '][icon]', 'default', 'text', '', 'ion-ios-telephone', '<em>ionicons class</em>', 'data-type="icon-picker" required', '8', $_raw_menu['menu']['items'][$i]['icon']);
        $form_input .= '</td>';

        $form_input .= '<td>';
        $form_input .= $bs->FormGroup('page_menu_box[menu][items][' . $i . '][icon-img]', 'default', 'text', '', '', '', 'data-type="image-picker"', '8', $_raw_menu['menu']['items'][$i]['icon-img']);
        $form_input .= '</td>';

        $form_input .= '<td>';
        $form_input .= $bs->FormGroup('page_menu_box[menu][items][' . $i . '][option]', 'default', 'text', '', '', 'type # for internal link', '', '8', $_raw_menu['menu']['items'][$i]['option'], 'typeahead');
        $form_input .= '</td>';

        $form_input .= '<td>';
        $form_input .= $bs->FormGroup('page_menu_box[menu][items][' . $i . '][desc]', 'default', 'text', '', '', '', '', '8', $_raw_menu['menu']['items'][$i]['desc']);
        $form_input .= '</td>';

        $form_input .= '<td>';
        $form_input .= $bs->FormGroup('page_menu_box[menu][items][' . $i . '][type]', 'default', 'select', '', $_items_type, '', $divider, '', null);
        $form_input .= '</td>';

        $form_input .= '<td>';
        $form_input .= $bs->FormGroup('page_menu_box[menu][items][' . $i . '][color]', 'default', 'select', '', $_items_color, '', '', '', null);
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

    if (!isset($raw_menu['barner']['items'][5]['src']))
    {
        $raw_menu['barner']['items'][5]['src'] = '';
    }
    if (!isset($raw_menu['barner']['items'][4]['src']))
    {
        $raw_menu['barner']['items'][4]['src'] = '';
    }

    $form_input .= '<div class="panel panel-default">';
    $form_input .= '<div class="panel-body">';
    $form_input .= '<h4>Heroes Sliders</h4>';
    $form_input .= '<blockquote class="blockquote blockquote-info">You should check the <code>Enable Heroes Sliders</code> above.</blockquote>';
    $form_input .= '<div class="panel-body">';
    $form_input .= $bs->FormGroup('page_menu_box[barner][items][0][src]', 'default', 'text', 'Image 1', '', '', 'data-type="image-picker"', '7', $raw_menu['barner']['items'][0]['src']);
    $form_input .= $bs->FormGroup('page_menu_box[barner][items][1][src]', 'default', 'text', 'Image 2', '', '', 'data-type="image-picker"', '7', $raw_menu['barner']['items'][1]['src']);
    $form_input .= $bs->FormGroup('page_menu_box[barner][items][2][src]', 'default', 'text', 'Image 3', '', '', 'data-type="image-picker"', '7', $raw_menu['barner']['items'][2]['src']);
    $form_input .= $bs->FormGroup('page_menu_box[barner][items][3][src]', 'default', 'text', 'Image 4', '', '', 'data-type="image-picker"', '7', $raw_menu['barner']['items'][3]['src']);
    $form_input .= $bs->FormGroup('page_menu_box[barner][items][4][src]', 'default', 'text', 'Image 5', '', '', 'data-type="image-picker"', '7', $raw_menu['barner']['items'][4]['src']);
    $form_input .= $bs->FormGroup('page_menu_box[barner][items][5][src]', 'default', 'text', 'Image 6', '', '', 'data-type="image-picker"', '7', $raw_menu['barner']['items'][5]['src']);
    $form_input .= '</div>';

    $form_input .= '</div>';
    $form_input .= '</div>';
}


$preview_url .= $_GET['target'];

$footer .= '
<script type="text/javascript">
     $("#table_source,#page_target,#max_menu").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_menu_box&max-menu=" + $("#max_menu").val() + "&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>