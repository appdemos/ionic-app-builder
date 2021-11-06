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
    $postdata['items'] = $_POST['items'];
    $postdata['background'] = $_POST['background'];

    $json_save['page_builder']['faqs'][$postdata['prefix']] = $postdata;
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.faqs.' . $postdata['prefix'] . '.json', json_encode($json_save));


    $new_page_prefix = $postdata['prefix'];
    $new_page_class = '';
    $new_page_title = htmlentities($postdata['title']);
    $new_page_css = null;
    $new_page_css .= '.' . $new_page_prefix . '-box{background-color: rgba(255, 255, 255, 0.5);}';
    $new_page_css .= '.' . $new_page_prefix . '-box .item{border-color: rgba(255, 255, 255, 0.5);border-left:0;border-right:0;}';


    $faqs[0]['q'] = 'Ut vis nemore temporibus?';
    $faqs[0]['a'] = 'An platonem inciderint nec, at enim sententiae usu, nam ut dicta reformidans';

    $faqs[1]['q'] = 'Eripuit adipisci vix ea?';
    $faqs[1]['a'] = 'Soluta pericula mel ad, sumo deterruisset consequuntur usu te';

    $faqs[2]['q'] = 'Prima torquatos comprehensam?';
    $faqs[2]['a'] = 'Illud diceret explicari nec ut, tation evertitur et eos';


    $new_page_content = null;
    $new_page_content .= '<ion-list class="card list">' . "\r\n";
    $z = 0;

    if (is_array($postdata['items']))
    {
        $faqs = $postdata['items'];
    }
    foreach ($faqs as $faq)
    {
        $new_page_content .= "\t" . '<div>' . "\r\n";
        $new_page_content .= "\t\t" . '<ion-item class="item item-colorful noborder" ng-click="toggleGroup(' . $z . ')" ng-class="{active: isGroupShown(' . $z . ')}" ><i class="icon" ng-class="isGroupShown(' . $z . ') ? \'ion-minus\' : \'ion-plus\'"></i> <span>' . $faq['q'] . '</span></ion-item>' . "\r\n";
        $new_page_content .= "\t\t" . '<ion-item class="item item-text-wrap" ng-show="isGroupShown(' . $z . ')">' . $faq['a'] . '</ion-item>' . "\r\n";
        $new_page_content .= "\t" . '</div>' . "\r\n";
        $z++;
    }
    $new_page_content .= '</ion-list>';

    create_page($new_page_class, $new_page_title, $new_page_prefix, $new_page_content, $new_page_css, '', $postdata['background'], 'ion-ios-help',false,false,false);
}

$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.faqs.' . str2var($_GET['target']) . '.json';
$raw_data = array();
$raw_data['items'] = array();

if (file_exists($pagebuilder_file))
{
    $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $get_raw_data['page_builder']['faqs'][str2var($_GET['target'])];
}

if (!isset($raw_data['title']))
{
    $raw_data['title'] = 'FAQs';
}

if (!isset($_GET['max_question']))
{
    $_GET['max_question'] = 3;
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
$_option_questions = $option_questions = array();
for ($z = 3; $z <= 20; $z++)
{
    $_option_questions[] = array('value' => $z, 'label' => $z);
}
$x = 3;
foreach ($_option_questions as $_option_question)
{
    $option_questions[$x] = $_option_question;
    if ($x == $_GET['max_question'])
    {
        $option_questions[$x]['active'] = true;
    }
    $x++;
}

$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');
$form_input .= $bs->FormGroup('max_question', 'horizontal', 'select', 'Max Questions', $option_questions, '', null, '4');
if (!isset($raw_data['background']))
{
    $raw_data['background'] = 'data/images/background/bg11.jpg';
}
if ($_GET['target'] !== '')
{
    $form_input .= $bs->FormGroup('title', 'horizontal', 'text', 'Title', 'FAQs', 'page title', '', '6', $raw_data['title']);
    $form_input .= $bs->FormGroup('background', 'horizontal', 'text', 'Background', 'data/images/background/bg15.jpg', 'Image used for background', 'data-type="image-picker"', '8', $raw_data['background']);

    $form_input .= '<h4>FAQs Listing</h4>';
    for ($i = 0; $i < ((int)($_GET['max_question'])); $i++)
    {
        if (!isset($raw_data['items'][$i]['q']))
        {
            $raw_data['items'][$i]['q'] = 'question ' . $i . '?';
        }
        if (!isset($raw_data['items'][$i]['a']))
        {
            $raw_data['items'][$i]['a'] = 'your answer ' . $i;
        }

        $form_input .= $bs->FormGroup('items[' . $i . '][q]', 'horizontal', 'text', 'Question ' . ($i + 1) . ')', '', '', '', '6', $raw_data['items'][$i]['q']);
        $form_input .= $bs->FormGroup('items[' . $i . '][a]', 'horizontal', 'textarea', 'Answer ' . ($i + 1) . ')', '', '', '', '6', $raw_data['items'][$i]['a']);
    }
}

$footer .= '
<script src="./templates/default/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
     $("#page_target,#max_question").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_faqs&target=" +  $("#page_target").val() + "&max_question=" +  $("#max_question").val() ;
        return false;
     });
</script>
';

?>