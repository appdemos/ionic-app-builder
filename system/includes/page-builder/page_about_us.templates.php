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
    $postdata['title'] = $_POST['title'];
    $postdata['company'] = $_POST['company'];
    $postdata['background'] = $_POST['background'];
    $postdata['content'] = $_POST['content'];
    $json_save['page_builder']['about_us'][$postdata['prefix']] = $postdata;
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.about_us.' . $postdata['prefix'] . '.json', json_encode($json_save));


    $new_page_prefix = $postdata['prefix'];
    $new_page_class = '';
    $new_page_title = htmlentities($postdata['title']);
    $new_page_css = '.' . $new_page_prefix . '-box{background-color: rgba(255, 255, 255, 0.5);}';
    $new_page_css .= '.' . $new_page_prefix . '-box .item{border-color: rgba(255, 255, 255, 0.5);border-left:0;border-right:0;}';

    $new_page_content = '
<div class="padding scroll">

    <div class="padding ' . $new_page_prefix . '-box">
        <h2>' . htmlentities($postdata['company']) . '</h2>
        <div>
            ' . $postdata['content'] . '
        </div>
    </div>
    <br/>

    <div class="disable-user-behavior ' . $new_page_prefix . '-box">
     
      <a class="item item-icon-left" ng-click="openURL(\'' . strtolower($_SESSION["PROJECT"]["app"]["fb"]) . '\')" >
        <i class="positive icon ion-social-facebook"></i>
        Like Us on Facebook
      </a>
      
      <a class="item item-icon-left" ng-click="openURL(\'' . strtolower($_SESSION["PROJECT"]["app"]["gplus"]) . '\')" >
        <i class="assertive icon ion-social-googleplus"></i>
        Join us on Google+
      </a>
      
      <a class="item item-icon-left" ng-click="openURL(\'' . strtolower($_SESSION["PROJECT"]["app"]["twitter"]) . '\')" >
        <i class="calm icon ion-social-twitter"></i>
       Follow me on Twitter
      </a>
      
       <a class="item item-icon-left" ng-click="openURL(\'mailto://' . strtolower($_SESSION["PROJECT"]["app"]["author_email"]) . '\')" >
        <i class="icon ion-android-mail royal"></i>
        For Business Cooperation
        <p>
            Email: ' . strtolower($_SESSION["PROJECT"]["app"]["author_email"]) . '
        </p>
      </a>
      
    </div>
    <br/>
</div>
<br/><br/><br/>
';
    create_page($new_page_class, $new_page_title, $new_page_prefix, $new_page_content, $new_page_css, '', $postdata['background'], 'ion-help-buoy',false,false,false);
}

$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.about_us.' . str2var($_GET['target']) . '.json';
$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $get_raw_data['page_builder']['about_us'][str2var($_GET['target'])];
}

if (!isset($raw_data['title']))
{
    $raw_data['title'] = 'About US';
}
if (!isset($raw_data['company']))
{
    $raw_data['company'] = $_SESSION['PROJECT']['app']['company'];
}
if (!isset($raw_data['content']))
{
    $raw_data['content'] = '
a brief description about us.
<h4>Why choose us</h4>
<p>We are offers a great service.</p>

    ';
}

// TODO: page target
$project = new ImaProject();
$out_path = 'output/' . $file_name;
$preview_url = $out_path . '/www/#/' . $_SESSION['PROJECT']['app']['prefix'] . '/' . $_GET['target'];


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

if (!isset($raw_data['background']))
{
    $raw_data['background'] = 'data/images/background/bg10.jpg';
}

$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');

if ($_GET['target'] !== '')
{
    $form_input .= $bs->FormGroup('title', 'horizontal', 'text', 'Title', 'About Us', 'page title', '', '6', $raw_data['title']);
    $form_input .= $bs->FormGroup('company', 'horizontal', 'text', 'Company', 'IHSANA Inc', 'your company name', '', '5', $raw_data['company']);
    $form_input .= $bs->FormGroup('content', 'horizontal', 'textarea', 'Content', 'About your services', '', '', '8', $raw_data['content']);
    $form_input .= $bs->FormGroup('background', 'horizontal', 'text', 'Background', 'Background', '', 'data-type="image-picker"', '8', $raw_data['background']);
}

$footer .= '
<script src="./templates/default/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector : "#content",
        force_br_newlines : false,
        force_p_newlines : false,
        forced_root_block : "",
        
    });

     $("#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_about_us&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>