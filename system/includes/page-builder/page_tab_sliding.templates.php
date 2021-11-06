<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

$require_target_page = true;
$dafault_tab = 5;
if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}
if (isset($_GET['tab']))
{
    $dafault_tab = (int)$_GET['tab'];
}

if (isset($_POST['page-builder']))
{
    if (isset($_POST['page_target']))
    {
        $_GET['target'] = $_POST['page_target'];
    }

    $postdata['prefix'] = str2var($_GET['target']);
    $postdata['tab_sliding'] = $_POST['tab_sliding'];
    $json_save['page_builder']['tab_sliding'][$postdata['prefix']] = $postdata;
    $json_save['page_builder']['tab_sliding'][$postdata['prefix']]['tab_sliding']['page'] = array();
    foreach ($_POST['tab_sliding']['page'] as $new_content)
    {
        if (strlen($new_content['content']) > 3)
        {
            $json_save['page_builder']['tab_sliding'][$postdata['prefix']]['tab_sliding']['page'][] = $new_content;
        }
    }
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.tab_sliding.' . $postdata['prefix'] . '.json', json_encode($json_save));

    $file_page = 'projects/' . $file_name . '/page.' . $postdata['prefix'] . '.json';

    $arr_page = json_decode(file_get_contents($file_page), true);
    $new_layout = null;

    $new_layout .= '<ion-slide-box class="ion-slide-tabs" slide-tabs-scrollable="false" show-pager="false" ion-slide-tabs>';
    foreach ($postdata['tab_sliding']['page'] as $new_content)
    {
        if (strlen($new_content['content']) > 3)
        {
            $new_layout .= '<ion-slide ion-slide-tab-label="' . htmlentities($new_content['label']) . '" >';
            $new_layout .= '<ion-content scroll="false" class="slidingTabContent">';
            $new_layout .= '<div class="padding">'; 
            $new_layout .= $new_content['content'];
            $new_layout .= '</div>';
            $new_layout .= '</ion-content>';
            $new_layout .= '</ion-slide>';
        }
    }
    $new_layout .= '</ion-slide-box>';
    $arr_page['page'][0]['title'] = $_POST['tab_sliding']['title'];
    $arr_page['page'][0]['content'] = $new_layout;

    file_put_contents($file_page, json_encode($arr_page));

    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=page_tab_sliding&target=' . $postdata['prefix']);
    die();
}


$pagebuilder_file = 'projects/' . $file_name . '/page_builder.tab_sliding.' . str2var($_GET['target']) . '.json';
$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $get_raw_data['page_builder']['tab_sliding'][str2var($_GET['target'])];
    //$dafault_tab = count($raw_data['tab_sliding']['page']);

}
// TODO: page target
$project = new ImaProject();
$out_path = 'output/' . $file_name;
$preview_url = $out_path . '/www/#/' . $_SESSION['PROJECT']['app']['prefix'] . '/' . $_GET['target'];


$option_page[] = array('label' => '< page >', 'value' => '');
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
if(!isset($raw_data['tab_sliding']['title'])){
    $raw_data['tab_sliding']['title']='My Page';
}

$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');
if ($_GET['target'] != '')
{
    $form_input .= $bs->FormGroup('tab_sliding[title]', 'horizontal', 'text', 'Title', 'My Page', '', '', '6', $raw_data['tab_sliding']['title']);
    $form_input .= '<div class="panel-body" id="slide-content">';
    for ($i = 0; $i < $dafault_tab; $i++)
    {
        if (!isset($raw_data['tab_sliding']['page'][$i]['label']))
        {
            $raw_data['tab_sliding']['page'][$i]['label'] = '';
        }
        if (!isset($raw_data['tab_sliding']['page'][$i]['content']))
        {
            $raw_data['tab_sliding']['page'][$i]['content'] = '';
        }
        $form_input .= '<h4>Page ' . ($i + 1) . '</h4>';
        $form_input .= '<div class="panel">';
        $form_input .= '<div class="panel-body">';
        $form_input .= $bs->FormGroup('tab_sliding[page][' . $i . '][label]', 'default', 'text', 'Label', 'Tab' . ($i + 1), '', '', '6', $raw_data['tab_sliding']['page'][$i]['label']);
        $form_input .= $bs->FormGroup('tab_sliding[page][' . $i . '][content]', 'default', 'textarea', '', '', '', '', '', $raw_data['tab_sliding']['page'][$i]['content']);
        $form_input .= '</div>';
        $form_input .= '</div>';
    }
    $form_input .= '</div>';
}
$footer .= '
<script src="./templates/default/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">

    tinymce.init({
        selector : "#slide-content textarea",
        force_br_newlines : false,
        force_p_newlines : false,
        forced_root_block : "",
        
    });
    


     $("#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_tab_sliding&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>