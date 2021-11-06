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
$content = $out_path = $footer = $form_input = $html = null;

$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-code fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) Code Examples</h4>';

foreach ($_SESSION['PROJECT']['tables'] as $list_table)
{
    $list_tables[] = array('label' => $list_table['prefix'], 'value' => $list_table['prefix']);
}
$form_input .= $bs->FormGroup('table_source', 'default', 'select', 'Source Data', $list_tables, null, null);

$content .= '<div class="row">';
$content .= '<div class="col-md-6">';
$content .= $form_input;
$content .= '</div>';
$content .= '<div class="col-md-6">';
$content .= '</div>';
$content .= '</div>';

$footer = '
<script type="text/javascript">
  $("#table_source").change(function(){
        window.location = "./?page=h-code-examples&source=" + $(this).val();
  });
</script>
';

$template->demo_url = $out_path . '/www/';
$template->title = $template->base_title . ' | ' . 'Helper Tools -&raquo; Code Docs';
$template->base_desc = 'Docs';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>