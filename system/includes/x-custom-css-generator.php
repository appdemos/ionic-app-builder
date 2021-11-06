<?php

/**
 * @author Jasman
 * @copyright 2017
 */
$file_name = 'test';
$bs = new jsmBootstrap();
$css_content = $html = $content = $footer = null;
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

$css_path = 'projects/' . $file_name . '/css.json';
$css_generator_path = 'projects/' . $file_name . '/css-generator.json';

if (isset($_GET['delete']))
{
    unlink($css_path);
    unlink($css_generator_path);
    buildIonic($file_name);
    header('Location: ./?page=x-custom-css-generator');
    die();
}


$css_code['css'] = '/** create using css generator **/' . "\r\n";
if (isset($_POST['css']))
{
    foreach ($_POST['css']['menu'] as $_menus)
    {

        if ($_menus['text-color'] != '')
        {
            $css_code['css'] .= '.menu-' . $_menus['name'] . ' a.item-content,.menu-' . $_menus['name'] . '  {color:' . $_menus['text-color'] . ' !important}' . "\r\n";
        }

        if ($_menus['icon-color'] != '')
        {
            $css_code['css'] .= '.menu-' . $_menus['name'] . ' .icon{color:' . $_menus['icon-color'] . ' !important}' . "\r\n";
        }


        if ($_menus['image-url'] != '')
        {
            $css_code['css'] .= '.menu-' . $_menus['name'] . ' a i:before,.menu-' . $_menus['name'] . ' i:before{ content: url("../' . $_menus['image-url'] . '");}' . "\r\n";
        }

    }

    if ($_POST['css']['font']['page-title']['fontname'] != 'default')
    {
        $css_code['css'] .= '.page-title .title,.nav-bar-title{font-family:"' . $_POST['css']['font']['page-title']['fontname'] . '" !important;font-size:' . $_POST['css']['font']['page-title']['fontsize'] . 'px !important}' . "\r\n";
    }
    
    if ($_POST['css']['font']['page-body']['fontname'] != 'default')
    {
        $css_code['css'] .= '.to_trusted p,.to_trusted td,.to_trusted th,.to_trusted pre,.to_trusted blockquote{font-family:"' . $_POST['css']['font']['page-body']['fontname'] . '" !important;font-size:' . $_POST['css']['font']['page-body']['fontsize'] . 'px !important}' . "\r\n";
    }
    
    if ($_POST['css']['font']['page-heading']['fontname'] != 'default')
    {
        $css_code['css'] .= 'h1,h2,h3,h4,h5,h6,h7,.h1,.h2,.h3,.h4,.h5,.h6,.h7{font-family:"' . $_POST['css']['font']['page-heading']['fontname'] . '" !important;}' . "\r\n";
    }
    
    $_postdata['css-generator'] = $_POST['css'];
    file_put_contents($css_path, json_encode($css_code));
    file_put_contents($css_generator_path, json_encode($_postdata));
    buildIonic($file_name);
}
$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-css3 fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Custom CSS Generator</h4>';
$content .= '<blockquote class="blockquote blockquote-info">' . __('This menu is used to simplify the creation of css code in the <a href="./?page=x-custom-css" target="_blank">(IMAB) Custom CSS</a>') . '</blockquote>';
$content .= notice();
$content .= '<form action="" method="post">';
$content .= '<h4>' . __('CSS for Menu') . '</h4>';
$content .= '<table id="group_column_list_" class="table table-striped sortable">';
$content .= '<thead>';
$content .= '<tr>';
$content .= '<th>' . __('Menu') . '</th>';
$content .= '<th>' . __('Icon Color') . '</th>';
$content .= '<th>' . __('Icon replace with image') . '</th>';
$content .= '<th>' . __('Text Color') . '</th>';
$content .= '</tr>';
$content .= '</thead>';

if (file_exists($css_generator_path))
{
    $_raw_css_gen = json_decode(file_get_contents($css_generator_path), true);
    $raw_css_gen = $_raw_css_gen['css-generator'];
}

foreach ($_SESSION['PROJECT']['menu']['items'] as $menu)
{
    if (!isset($raw_css_gen['menu'][$menu['var']]['icon-color']))
    {
        $raw_css_gen['menu'][$menu['var']]['icon-color'] = '#333333';
    }
    if (!isset($raw_css_gen['menu'][$menu['var']]['text-color']))
    {
        $raw_css_gen['menu'][$menu['var']]['text-color'] = '#333333';
    }
    if (!isset($raw_css_gen['menu'][$menu['var']]['image-url']))
    {
        $raw_css_gen['menu'][$menu['var']]['image-url'] = '';
    }
    if ($menu['type'] != 'divider')
    {

        $content .= '<tr>';

        $content .= '<td>';
        $content .= '<input type="hidden" name="css[menu][' . $menu['var'] . '][name]" value="' . $menu['var'] . '" />' . $menu['label'];
        $content .= '</td>';

        $content .= '<td>';
        $content .= '<div data-type="color-picker" class="input-group colorpicker-component"><input name="css[menu][' . $menu['var'] . '][icon-color]" type="text" value="' . $raw_css_gen['menu'][$menu['var']]['icon-color'] . '" class="form-control" /><span class="input-group-addon"><i></i></span></div>';
        $content .= '</td>';

        $content .= '<td>';
        $content .= '<input data-type="image-picker" id="css_menu_' . $menu['var'] . '_image-url"  name="css[menu][' . $menu['var'] . '][image-url]" type="text" value="' . $raw_css_gen['menu'][$menu['var']]['image-url'] . '" class="form-control" />';
        $content .= '</td>';

        $content .= '<td>';
        $content .= '<div data-type="color-picker" class="input-group colorpicker-component"><input name="css[menu][' . $menu['var'] . '][text-color]" type="text" value="' . $raw_css_gen['menu'][$menu['var']]['text-color'] . '" class="form-control" /><span class="input-group-addon"><i></i></span></div>';
        $content .= '</td>';

        $content .= '</tr>';
    }
}
$content .= '</table>';

$fontfamily[0] = array('label' => __('default'), 'value' => 'default');
$x = 1;
foreach (glob(JSM_PATH . '/output/' . $file_name . "/www/fonts/*.ttf") as $font)
{
    if (!preg_match("/roboto/i", $font))
    {
        $fontfamily[$x]['label'] = pathinfo(str_replace("\\", "/", $font), PATHINFO_FILENAME);
        $fontfamily[$x]['value'] = pathinfo(str_replace("\\", "/", $font), PATHINFO_FILENAME);
        $x++;
    }
}
$fontsize[] = array('label' => '8 px', 'value' => '8');
$fontsize[] = array('label' => '9 px', 'value' => '9');
$fontsize[] = array('label' => '10 px', 'value' => '10');
$fontsize[] = array('label' => '12 px', 'value' => '12');
$fontsize[] = array('label' => '14 px', 'value' => '14');
$fontsize[] = array('label' => '16 px', 'value' => '16');
$fontsize[] = array('label' => '18 px', 'value' => '18');
$fontsize[] = array('label' => '20 px', 'value' => '20');
$fontsize[] = array('label' => '22 px', 'value' => '22');
$fontsize[] = array('label' => '24 px', 'value' => '24');
$fontsize[] = array('label' => '28 px', 'value' => '28');
$fontsize[] = array('label' => '32 px', 'value' => '32');
$fontsize[] = array('label' => '36 px', 'value' => '36');
$fontsize[] = array('label' => '48 px', 'value' => '48');
$fontsize[] = array('label' => '72 px', 'value' => '72');

function fontsize($val)
{
    global $fontsize;
    $new_fontsize = array();
    $z = 0;
    foreach ($fontsize as $_fontsize)
    {
        $new_fontsize[$z] = $_fontsize;
        if ($_fontsize['value'] == $val)
        {
            $new_fontsize[$z]['active'] = true;
        }
        $z++;
    }
    return $new_fontsize;
}

function fontfamily($val)
{
    global $fontfamily;
    $new_fontfamily = array();
    $z = 0;
    foreach ($fontfamily as $_fontfamily)
    {
        $new_fontfamily[$z] = $_fontfamily;
        if ($_fontfamily['value'] == $val)
        {
            $new_fontfamily[$z]['active'] = true;
        }
        $z++;
    }
    return $new_fontfamily;
}

if (!isset($raw_css_gen['font']['page-title']['fontsize']))
{
    $raw_css_gen['font']['page-title']['fontsize'] = '14';
}

if (!isset($raw_css_gen['font']['page-body']['fontsize']))
{
    //$raw_css_gen['font']['page-body']['fontsize'] = '14';
}

if (!isset($raw_css_gen['font']['page-body']['fontsize']))
{
    $raw_css_gen['font']['page-body']['fontsize'] = '14';
}

if (!isset($raw_css_gen['font']['page-body']['fontname']))
{
    $raw_css_gen['font']['page-body']['fontname'] = 'default';
}

if (!isset($raw_css_gen['font']['page-title']['fontname']))
{
    $raw_css_gen['font']['page-title']['fontname'] = 'default';
}

if (!isset($raw_css_gen['font']['page-heading']['fontname']))
{
    $raw_css_gen['font']['page-heading']['fontname'] = 'default';
}

$content .= '<h4>' . __('CSS for Fonts') . '</h4>';
$content .= '<table id="" class="table table-striped ">';
$content .= '<thead>';
$content .= '<tr>';
$content .= '<th>' . __('Element') . '</th>';
$content .= '<th>' . __('Font') . '</th>';
$content .= '<th>' . __('Size') . '</th>';
$content .= '</tr>';
$content .= '</thead>';
$content .= '<tbody>';

$content .= '<tr>';
$content .= '<td>' . __('Page Title') . '</td>';
$content .= '<td>' . $bs->FormGroup('css[font][page-title][fontname]', 'inline', 'select', '', fontfamily($raw_css_gen['font']['page-title']['fontname']), ' ', '') . '</td>';
$content .= '<td>' . $bs->FormGroup('css[font][page-title][fontsize]', 'inline', 'select', '', fontsize($raw_css_gen['font']['page-title']['fontsize']), ' ', '') . '</td>';
$content .= '</tr>';

$content .= '<tr>';
$content .= '<td>' . __('Heading (H1, H2, H3, H4, H5, H6, H7)') . '</td>';
$content .= '<td>' . $bs->FormGroup('css[font][page-heading][fontname]', 'inline', 'select', '', fontfamily($raw_css_gen['font']['page-heading']['fontname']), ' ', '') . '</td>';
//$content .= '<td>' . $bs->FormGroup('css[font][page-heading][fontsize]', 'inline', 'select', '', fontsize($raw_css_gen['font']['page-heading']['fontsize']), ' ', '') . '</td>';
$content .= '</tr>';

$content .= '<tr>';
$content .= '<td>' . __('Trust HTML') . '</td>';
$content .= '<td>' . $bs->FormGroup('css[font][page-body][fontname]', 'inline', 'select', '', fontfamily($raw_css_gen['font']['page-body']['fontname']), ' ', '') . '</td>';
$content .= '<td>' . $bs->FormGroup('css[font][page-body][fontsize]', 'inline', 'select', '', fontsize($raw_css_gen['font']['page-body']['fontsize']), ' ', '') . '</td>';
$content .= '</tr>';



$content .= '</tbody>';
$content .= '</table>';

$content .= '<input type="submit" value="' . __('Generate CSS Code') . '" class="btn btn-primary" />';
$content .= '&nbsp; <a class="btn btn-danger" href="./?page=x-custom-css-generator&delete=css-code">' . __('Reset CSS Code') . '</a>';

$content .= '</form>';

$content .= '';
$footer .= '
<script type="text/javascript">
 $("div[data-type=\'color-picker\']").colorpicker();
</script>
';
$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Custom CSS Generator';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;

?>