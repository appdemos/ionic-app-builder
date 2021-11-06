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
    $postdata['prefix'] = str2var($_GET['target']);
    $postdata['title'] = $_POST['title'];
    $json_save['page_builder']['text_generator'][$postdata['prefix']] = $postdata;
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.text_generator.' . $postdata['prefix'] . '.json', json_encode($json_save));

    $new_page_class = 'text_generator';
    $new_page_title = $postdata['title'];
    $new_page_prefix = $postdata['prefix'];
    $form_markup = null;
    $form_markup .= "\r\n\t\t" . '<div class="text-generator card">';
    $form_markup .= "\r\n\t\t\t" . '<form>';

    $form_markup .= "\r\n\t\t\t" . '<label class="item item-input item-stacked-label">';
    $form_markup .= "\r\n\t\t\t\t" . '<span class="input-label">Text</span>';
    $form_markup .= "\r\n\t\t\t\t" . '<textarea ng-model="textInput" placeholder="Your Messagge" ></textarea>';
    $form_markup .= "\r\n\t\t\t" . '</label>';

    $form_markup .= "\r\n\t\t\t" . '<div class="item">';
    $form_markup .= "\r\n\t\t\t\t" . '<button class="button button-calm button-small" ng-click="genText(textInput)">Generate</button>';
    $form_markup .= "\r\n\t\t\t" . '</div>';

    $form_markup .= "\r\n\t\t\t" . '<label class="item item-input item-stacked-label">';
    $form_markup .= "\r\n\t\t\t\t" . '<span class="input-label">Output</span>';
    $form_markup .= "\r\n\t\t\t\t" . '<textarea ng-model="textOutput" ></textarea>';
    $form_markup .= "\r\n\t\t\t" . '</label>';


    $form_markup .= "\r\n\t\t\t" . '<div ng-if="textOutput" class="item">';
    $form_markup .= "\r\n\t\t\t\t" . '<button run-app-sms  phone="0" message="{{textOutput}}" class="button button-assertive icon-left ion-email">SMS</button>';
    $form_markup .= "\r\n\t\t\t\t" . '<button run-app-line message="{{textOutput}}" class="button button-calm icon-left ion-ios-chatbubble">Line</button>';
    $form_markup .= "\r\n\t\t\t" . '</div>';

    $form_markup .= "\r\n\t\t\t" . '</form>';

    $form_markup .= "\r\n\t\t" . '</div>';


    $new_page_js = '
    
   
    $scope.genText = function(textInput){
        var $replaceWith = [
            {text:"a",replace:"4"},
            {text:"i",replace:"1"},
            {text:"u",replace:"u"},
            {text:"e",replace:"3"},
            {text:"o",replace:"0"},
            {text:"s",replace:"5"},
            {text:"g",replace:"9"},
        ];
        
        var $text = textInput ;
        angular.forEach($replaceWith,function(item){
           var expr = new RegExp(item.text, "g");
           $text = $text.replace(expr,item.replace);
           console.log(item);
        });  
        $scope.textOutput = $text;
    };
    
 
    ';
    $new_page_content = $form_markup;
    $new_page_css = '
    .text-generator text-area{
        min-height:200px;   
    }
    ';
    create_page($new_page_class, $new_page_title, $new_page_prefix, $new_page_content, $new_page_css, $new_page_js, false, 'ion-email');
}
$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.about_us.' . str2var($_GET['target']) . '.json';
$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $raw_data = json_decode(file_get_contents($pagebuilder_file), true);
}

if (!isset($raw_data['title']))
{
    $raw_data['title'] = 'About US';
}
if (!isset($raw_data['company']))
{
    $raw_data['company'] = $_SESSION['PROJECT']['app']['company'];
}
 
$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/text_generator.about_us.' . str2var($_GET['target']) . '.json';
$raw_data = array();
if (file_exists($pagebuilder_file))
{
        $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
        $raw_data = $get_raw_data['page_builder']['text_generator'][str2var($_GET['target'])];
}

if (!isset($raw_data['title']))
{
    $raw_data['title'] = 'Text Generator';
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


$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');
if($_GET['target']!= ''){
$form_input .= $bs->FormGroup('title', 'horizontal', 'text', 'Title', 'About Us', 'page title', '', '6', $raw_data['title']);
}
$footer .= '
<script src="./templates/default/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
     $("#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_text_generator&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>