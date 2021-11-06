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

$out_path = 'output/' . $file_name;
$project_path = 'projects/' . $file_name;
$project_temp_path = 'projects/' . $file_name . '/import/';
if (!is_dir($project_path . '/import/'))
{
    mkdir($project_path . '/import', 0777, true);
}
$error = null;


if (isset($_FILES['import-file']))
{
    if ($_FILES['import-file']['tmp_name'] != '')
    {
        //if (preg_match("/zip/i", $_FILES["import-file"]["type"]))
        //{
            $tmp_name = $_FILES["import-file"]["tmp_name"];
            $zip = new ZipArchive;
            if ($zip->open($tmp_name) === true)
            {
                if ($zip->locateName('app.json') !== false)
                {

                    $zip->extractTo($project_temp_path);
                    $zip->close();

                    $app_old = json_decode(file_get_contents($project_temp_path . '/app.json'), true);
                    unlink($project_temp_path . '/app.json');
                    foreach (glob($project_temp_path . "/*.json") as $filename)
                    {
                        copy($project_temp_path . '/' . pathinfo($filename, PATHINFO_BASENAME), $project_path . '/' . pathinfo($filename, PATHINFO_BASENAME));
                        @unlink($project_temp_path . '/' . pathinfo($filename, PATHINFO_BASENAME));
                    }


                    $app_menus = json_decode(file_get_contents(JSM_PATH . '/projects/' . $file_name . '/menu.json'), true);
                    $app_menus['menu']['title'] = htmlentities($_SESSION['PROJECT']['app']['name']);
                    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/menu.json', json_encode($app_menus));

                    foreach (glob("projects/" . $file_name . "/*.json") as $raw_json)
                    {
                        $raw_data = file_get_contents($raw_json);
                        $raw_data = str_replace('#\/' . $app_old['app']['prefix'], '#\/' . $file_name, $raw_data);
                        file_put_contents($raw_json, $raw_data);
                    }

                    buildIonic($file_name);

                    $error = '<div class="alert alert-success"><p>IMA Builder project has been successfully entered please select the item you want to restrore</p></div>';

                } else
                {
                    $error = '<div class="alert alert-danger"><p>[error:app] This is not a IMA Builder project, please try again</p></div>';
                }

            } else
            {
                $error = '<div class="alert alert-danger"><p>[error:zip] Failed to unzip IMA Builder project, please try again</p></div>';
            }

       // } //else
        //{
        //    $error = '<div class="alert alert-danger"><p>[error:mime] Not valid mime type, please try again</p></div>';
        //}
    }
}


$form_input = null;

$form_input .= $error;
$form_input .= $bs->FormGroup('import-file', 'default', 'file', 'IMA Project', '', '', '', '8', '');
//$form_input .= $bs->FormGroup('import-data', 'default', 'file', 'IMA Data', '', 'file ext *.zip', '', '8', '');

$form_input .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(array(
        'name' => 'upload',
        'label' => __('Upload'). ' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'))));


$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-gear fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Import Project</h4>';

$content .= '<div class="row">';
$content .= '<div class="col-md-6">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">'.__('Import The Project').'</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= '
<blockquote class="blockquote blockquote-danger">
'.__('You can import a <code>IMA Project</code> using this menu. Importing the project means <code>deleting the current project</code> configuration, if you do not want to lose the configuration, please using import menu on the <code>new project</code>').'.
</blockquote>
';
$content .= notice();
$content .= $bs->Forms('import-setup', '', 'post', 'default', $form_input);
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '<div class="col-md-6">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading">';
$content .= '<h5 class="panel-title">'.__('Export The Project').'</h5>';
$content .= '</div>';
$content .= '<div class="panel-body">';
$content .= '
<blockquote class="blockquote blockquote-danger">
'.__('For import data, just to copy all files in zip file to').' <code>'.realpath($out_path).'</code> 
</blockquote>';

$content .= '<a target="_blank" href="./download.php?download=project&prefix=' . $file_name . '" class="btn btn-primary">IMA Project</a>';
$content .= '&nbsp;<a target="_blank" href="./download.php?download=data&prefix=' . $file_name . '" class="btn btn-primary">IMA Data</a>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Import Project';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = true;

?>