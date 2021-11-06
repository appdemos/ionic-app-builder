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
$content = $out_path = $footer = $html = null;
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
$project_path = 'output/' . $file_name;
$project_temp_path = 'output/' . $file_name . '/www/fonts/';

if (!is_dir($project_path . '/www/fonts'))
{
    mkdir($project_path . '/www/fonts', 0777, true);
}
$error = null;
$font_config_path = 'projects/' . $file_name . '/fonts.json';
if (isset($_POST['save-font']))
{
    $new_fonts['fonts'] = $_POST['fonts'];
    file_put_contents($font_config_path, json_encode($new_fonts));
    buildIonic($file_name);
    header('Location: ./?page=x-custom-fonts&err=null&notice=save');
}
$raw_fonts = array();
if (file_exists($font_config_path))
{
    $raw_fonts = json_decode(file_get_contents($font_config_path), true);
}
if ((isset($_FILES['font_ttf'])) && (isset($_FILES['font_woff'])))
{

    $tmp_font_ttf = $_FILES["font_ttf"]["tmp_name"];
    $tmp_font_woff = $_FILES["font_woff"]["tmp_name"];

    $font_ttf_name = $project_temp_path . '/' . $_FILES["font_ttf"]["name"];
    $font_woff_name = $project_temp_path . '/' . pathinfo($_FILES["font_ttf"]["name"], PATHINFO_FILENAME) . '.woff';


    if (pathinfo($font_ttf_name, PATHINFO_EXTENSION) == 'ttf')
    {
        move_uploaded_file($tmp_font_ttf, $font_ttf_name);
    } else
    {
        $error = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><p>Please upload TTF Files</p></div>';
    }
    if (pathinfo($font_woff_name, PATHINFO_EXTENSION) == 'woff')
    {
        move_uploaded_file($tmp_font_woff, $font_woff_name);
    } else
    {
        $error = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><p>Please upload WOFF Files</p></div>';
    }

} else
{
    $error = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><p>Please upload WOFF and TTF Files</p></div>';
}

$font_list = null;
$font_list .= '<div class="col-md-6">';
$font_list .= '<div class="panel panel-default">';
$font_list .= '<div class="panel-heading">';
$font_list .= '<h5 class="panel-title">'.__('Font Used').'</h5>';
$font_list .= '</div>';
$font_list .= '<div class="panel-body">';
$font_list .= '<blockquote class="blockquote blockquote-info">'.__('Select or checked the font to use:').'</blockquote>';

$font_list .= '<table class="table table-striped">';
foreach (glob(JSM_PATH . '/output/' . $file_name . "/www/fonts/*.ttf") as $font)
{
    if (!preg_match("/roboto/i", $font))
    {
        $fontfamily = pathinfo(str_replace("\\", "/", $font), PATHINFO_FILENAME);
        $path = explode($file_name . "/www/", $font);
        if (isset($raw_fonts['fonts'][$fontfamily]['used']))
        {
            $checked = 'checked';
        } else
        {
            $checked = '';
        }
        $font_list .= '<tr>';
        $font_list .= '<td><input type="checkbox" ' . $checked . ' value="true" name="fonts[' . $fontfamily . '][used]" /></td>';
        $font_list .= '<td>
        <input type="hidden" name="fonts[' . $fontfamily . '][font-path]" value="' . $font . '"/>
        <input type="hidden" name="fonts[' . $fontfamily . '][font-url-ttf]" value="../' . $path[1] . '"/>
        <input type="hidden" name="fonts[' . $fontfamily . '][font-url-woff]" value="../' . str_replace('.ttf', '.woff', $path[1]) . '"/>
        <input type="hidden" name="fonts[' . $fontfamily . '][font-family]" value="' . $fontfamily . '"/>
        ' . $fontfamily . '</td>';
        $font_list .= '</tr>';
    }
}
$font_list .= '</table>';
$font_list .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'save-font',
        'label' => __('Use This Font').' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'))));
$font_list .= '</div>';
$font_list .= '</div>';
$font_list .= '</div>';

$form_input = null;
$form_input .= '
<blockquote class="blockquote blockquote-info">
<h4>'.__('How to add a new font?').'</h4>
<ol>
<li>'.__('Download font files from <a target="_blank" href="https://github.com/google/fonts/">https://github.com/google/fonts/</a> or other sites').'</li> 
<li>'.__('Then convert to  WOFF and TTF Files, example using site <a target="_blank" href="http://www.font2web.com/">http://www.font2web.com/</a>').'</li>
<li>'.__('Upload WOFF and TTF Files, using menu <code>Extra Menus -&raquo; (IMAB) Custom Fonts -&raquo; New Fonts</code>').'</li>
<li>'.__('Checked list font that you want to used, on <code>Extra Menus -&raquo; (IMAB) Custom Fonts -&raquo; Font Used</code>').'</li>
<li>'.__('Then use custom css (Goto <code>Extra Menus -&raquo; (IMAB) Custom Css</code>) or Css Generator for without coding (Goto <code>Extra Menus -&raquo; (IMAB) Custom Css Generator</code>)').'</li>
<ol>
</blockquote>';
$form_input .= '<div class="col-md-6">';
$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">';
$form_input .= '<h5 class="panel-title">'.__('New Font').'</h5>';
$form_input .= '</div>';
$form_input .= '<div class="panel-body">';



$form_input .= $error;
$form_input .= $bs->FormGroup('font_ttf', 'default', 'file', __('TTF File'), '', __('Uploaded file must have ttf extension'), 'required', '8', '');
$form_input .= $bs->FormGroup('font_woff', 'default', 'file', __('Woff File'), '', __('Uploaded file must have woff extension'), 'required', '8', '');

$form_input .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'upload',
        'label' => __('Upload'). ' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'))));
$form_input .= '</div>';
$form_input .= '</div>';
$form_input .= '</div>';

$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-gear fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Custom Fonts</h4>';


$content .= notice();
$content .= $bs->Forms('upload-font', '', 'post', 'default', $form_input);
$content .= $bs->Forms('save-font', '', 'post', 'default', $font_list);

$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Custom Fonts';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = true;

?>