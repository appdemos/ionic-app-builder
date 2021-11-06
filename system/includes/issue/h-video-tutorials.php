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

$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-youtube fa-stack-1x"></i></span>Helper - Video Tutorials</h4>';


$content .= '
<div class="ratio_16x9">
<iframe class="content" width="100%" src="https://www.youtube.com/embed/QgTzBo2iOAo?list=PLIW3UL24x-zUInbGBwj0VYCYsaV41qNgN" frameborder="0" allowfullscreen></iframe>
</div>

<style type="text/css">
.ratio_16x9 { position: relative;}
.ratio_16x9:before { display: block;  content: ""; width: 100%; padding-top: 56.25%;}
.ratio_16x9 > .content { position: absolute; top: 0; left: 0; right: 0; bottom: 0; height: 100%;}
</style>
';
$template->demo_url = $out_path . '/www/';
$template->title = $template->base_title . ' | ' . 'Docs';
$template->base_desc = 'Video Tutorials';
$template->content = $content;
$template->footer = '';
$template->emulator = false;

?>