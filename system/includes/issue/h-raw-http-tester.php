<?php

/**
 * @author Jasman
 * @copyright 2016
 */

if (!defined('JSM_EXEC'))
{
    die(':)');
}

$bs = new jsmBootstrap();
$content = $out_path = $footer = $html = null;


$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-users fa-stack-1x"></i></span>Helper - Raw HTTP Tester</h4>';

$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-body">';
$content .= $bs->FormGroup('domain', 'default', 'text', 'IP / Domain', '', '', '', '8', '');
$content .= $bs->FormGroup('port', 'default', 'text', 'Port', '', '', '', '8', '');
$content .= '</div>';
$content .= '</div>';


$template->demo_url = $out_path . '/www/';
$template->title = $template->base_title . ' | ' . 'Raw HTTP Tester';
$template->base_desc = 'Raw HTTP Tester';
$template->content = $content;
$template->footer = '';
$template->emulator = false;
?>