<?php

/**
 * @author Jasman
 * @copyright 2016
 */

if (!defined('JSM_EXEC'))
{
    die(':)');
}
$file_name = 'test';
$bs = new jsmBootstrap();
$css_content = $html = $content = null;
if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}


foreach (glob(JSM_PATH . '/output/' . $file_name . "/www/fonts/*.ttf") as $font)
{
    if (!preg_match("/roboto/", $font))
    {
        $fontfamily = pathinfo($font, PATHINFO_FILENAME);
        $code_css .= "\r\n\r\n";
        $code_css .= "@font-face {" . "\r\n";
        $code_css .= "\tfont-family: '" . $fontfamily . "';" . "\r\n";
        $code_css .= "\tsrc: url('output/" . $file_name . "/www/fonts/" . $fontfamily . ".ttf') format('truetype'), url('output/" . $file_name . "/www/fonts/" . $fontfamily . ".woff') format('woff');" . "\r\n";
        $code_css .= "}" . "\r\n";
    }
}
$content .= '<style>';
$content .= $code_css;
$content .= '</style>';

$content .= '<div class="row">';
for ($i = 32; $i < 255; $i++)
{
    $content .= '
    <div class="col-md-2">
    
    <input type="checkbox" />
    <span style="font-family: \'kidS Written\';font-size:36pt">&#' . $i . ';</span>
    <input type="text" class="form-control" />
    
    </div>
    ';
}
$content .= '</div>';

$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . ' Font Icon Maker';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>