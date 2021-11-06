<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

$require_target_page = true;

if (isset($_POST['page-builder']))
{
    if (isset($_POST['page_target']))
    {
        $_GET['target'] = $_POST['page_target'];
    }
    $postdata['prefix'] = str2var($_GET['target']);
    $postdata['slides'] = $_POST['slider']['slides'];
    $postdata['title'] = $_POST['slider']['title'];

    $app_json = file_get_contents(JSM_PATH . '/projects/' . $_SESSION['FILE_NAME'] . '/app.json');
    $app_config = json_decode($app_json, true);
    $app_config['app']['index'] = $postdata['prefix'];
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/app.json', json_encode($app_config));

    $json_save['page_builder']['slider'][$postdata['prefix']] = $postdata;
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.image_slider.' . $postdata['prefix'] . '.json', json_encode($json_save));

    $new_page_class = 'fullscreen';
    $new_page_title = htmlentities($postdata['title']);
    $new_page_prefix = $postdata['prefix'];

    $new_page_content = '';
    $new_page_content .= '<ion-slide-box show-pager="true" auto-play="true" pager-click="true" >' . "\r\n";

    foreach ($postdata['slides'] as $slide)
    {
        if ($slide['img'] != '')
        {
            $new_page_content .= "\t" . '<ion-slide style="background: #999 url(\'' . $slide['img'] . '\')  no-repeat center center fixed;;background-size:cover;width:100%;height:100%;background-color:#ddd;" >' . "\r\n";
            $new_page_content .= "\t\t" . '<div class="padding text-center light">' . "\r\n";
            if ($slide['title'] != '')
            {
                $new_page_content .= "\t\t\t" . '<div class="padding light dark-bg" style="opacity:0.8;margin-top:80px">' . "\r\n";
                $new_page_content .= "\t\t\t\t" . '<h4>' . $slide['title'] . '</h4>' . "\r\n";

                if ($slide['desc'] != '')
                {
                    $new_page_content .= "\t\t\t\t" . '<p>' . $slide['desc'] . '</p>' . "\r\n";
                }
                $new_page_content .= "\t\t\t" . '</div>' . "\r\n";
            }
            $new_page_content .= "\t\t" . '</div>' . "\r\n";
            $new_page_content .= "\t" . '</ion-slide>' . "\r\n";
        }
    }

    $new_page_content .= '</ion-slide-box>' . "\r\n";


    $new_page_css = '';
    create_page($new_page_class, $new_page_title, $new_page_prefix, $new_page_content, $new_page_css, '', true, '', true, true, false, true, true, false, false);
}

// TODO: page target
$project = new ImaProject();
$out_path = 'output/' . $file_name;
$preview_url = $out_path . '/www/#/' . $_SESSION['PROJECT']['app']['prefix'] . '/' . $_GET['target'];

$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.image_slider.' . str2var($_GET['target']) . '.json';
$raw_data = array();

 
$raw_data['slides'][0]['title'] = 'App Builder';
$raw_data['slides'][0]['img'] = 'data/images/background/bg7.jpg';
$raw_data['slides'][0]['desc'] = 'Suitable with many app backend, WordPress, Joomla, Drupal, PHP SQL, Private CMS';

$raw_data['slides'][1]['title'] = 'It\'s like a magic!';
$raw_data['slides'][1]['img'] = 'data/images/background/bg10.jpg';
$raw_data['slides'][1]['desc'] = 'Can make a hybrid app without the need to know programming.';


if (file_exists($pagebuilder_file))
{
    $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $get_raw_data['page_builder']['slider'][str2var($_GET['target'])];
}


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

if(!isset($raw_data['title'])){
    $raw_data['title']='';
}
    $form_input .= '
    <blockquote class="blockquote blockquote-danger">
    <ul>
      <li>This page used as home page/index, if you do not want it, please change it through the <code>(IMAB) Page</code> -&gt; <code>Page Manager</code></li>
    </ul>
    </blockquote>';
$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');

if ($_GET['target'] != '')
{
    
     $form_input .= $bs->FormGroup('slider[title]', 'horizontal', 'text', 'Title', 'Wizard', 'page title', '', '6', $raw_data['title']);

    for ($i = 0; $i < 10; $i++)
    {
        if (!isset($raw_data['slides'][$i]['title']))
        {
            $raw_data['slides'][$i]['title'] = null;
        }
        if (!isset($raw_data['slides'][$i]['desc']))
        {
            $raw_data['slides'][$i]['desc'] = null;
        }

        if (!isset($raw_data['slides'][$i]['img']))
        {
            $raw_data['slides'][$i]['img'] = '';
        }

        $form_input .= '<hr style="border-top:1px solid #ddd !important"/>';
        $form_input .= '<h4>Slide (' . ($i + 1) . ')</h4>';
        $form_input .= $bs->FormGroup('slider[slides][' . $i . '][img]', 'horizontal', 'text', 'Image', 'data/images/background/bg' . $i . '.jpg', 'source image', 'data-type="image-picker"', '8', $raw_data['slides'][$i]['img']);
        $form_input .= $bs->FormGroup('slider[slides][' . $i . '][title]', 'horizontal', 'text', 'Title', '', 'Leave blank if not needed', '', '6', $raw_data['slides'][$i]['title']);
        $form_input .= $bs->FormGroup('slider[slides][' . $i . '][desc]', 'horizontal', 'textarea', 'Description', '', 'Description required text in title field', '', '8', $raw_data['slides'][$i]['desc']);

    }
}
$footer .= '
<script type="text/javascript">
     $("#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_image_slider&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>