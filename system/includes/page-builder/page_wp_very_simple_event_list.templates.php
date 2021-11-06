<?php

// TODO: CONFIG
$require_target_page = true;
$project = new ImaProject();

// TODO: VALIDATION - USE PAGEBUILDER MENU
if(!defined('JSM_EXEC'))
{
    die(':)');
}

// TODO: VALIDATION - PROJECT ACTIVE
if(isset($_SESSION["FILE_NAME"]))
{
    $file_name = $_SESSION["FILE_NAME"];
} else
{
    header("Location: ./?page=dashboard&err=project");
    die();
}

// TODO: GET PAGE TARGET
if(isset($_GET["target"]))
{
    $var = str2var($_GET["target"]);
}
// TODO: GET CURRENT CONFIG
$raw_config["wp_very_simple_event_list"] = array();
$file_config = JSM_PATH."/projects/".$_SESSION["FILE_NAME"]."/page_builder.wp_very_simple_event_list.".$var.".json";
if(file_exists($file_config))
{
    $raw_config = json_decode(file_get_contents($file_config),true);
}
// TODO: GENERATE CODE
if(isset($_POST["page-builder"]))
{

    // TODO: -----|-- SAVE CONFIG
    $raw_config = array();
    $raw_config["page_builder"]["wp_very_simple_event_list"][$var] = $_POST["wp_very_simple_event_list"];
    // update value
    $raw_config["page_builder"]["wp_very_simple_event_list"][$var]["page_title"] = htmlentities($_POST["wp_very_simple_event_list"]["page_title"]);
    $raw_config["page_builder"]["wp_very_simple_event_list"][$var]["site_url"] = htmlentities($_POST["wp_very_simple_event_list"]["site_url"]);
    $raw_config["page_builder"]["wp_very_simple_event_list"][$var]["page_bg"] = htmlentities($_POST["wp_very_simple_event_list"]["page_bg"]);
    file_put_contents($file_config,json_encode($raw_config));

    // TODO: -----|-- CREATE TABLE
    $newTable["tables"][$var]["parent"] = $var;
    $newTable["tables"][$var]["title"] = $var;
    $newTable["tables"][$var]["builder_link"] = @$_SERVER["HTTP_REFERER"];
    $newTable["tables"][$var]["template"] = "thumbnail";
    $newTable["tables"][$var]["template_single"] = "none";
    $newTable["tables"][$var]["itemtype"] = "item";
    $newTable["tables"][$var]["itemcolor"] = "colorful";
    $newTable["tables"][$var]["db_type"] = "online";
    $newTable["tables"][$var]["db_var"] = "";
    $newTable["tables"][$var]["db_var_single"] = "";
    $newTable["tables"][$var]["db_url"] = $raw_config["page_builder"]["wp_very_simple_event_list"][$var]["site_url"].'/wp-json/wp/v2/event?per_page=100&orderby=date&order=asc';
    $newTable["tables"][$var]["db_url_single"] = $raw_config["page_builder"]["wp_very_simple_event_list"][$var]["site_url"].'/wp-json/wp/v2/event/';

    $row = 0;
    $newTable["tables"][$var]["cols"][$row]["label"] = "#ID";
    $newTable["tables"][$var]["cols"][$row]["title"] = "id";
    $newTable["tables"][$var]["cols"][$row]["type"] = "id";
    $newTable["tables"][$var]["cols"][$row]["json"] = "true";

    $row++;
    $newTable["tables"][$var]["cols"][$row]["label"] = "title";
    $newTable["tables"][$var]["cols"][$row]["title"] = "title.rendered";
    $newTable["tables"][$var]["cols"][$row]["type"] = "heading-1";
    $newTable["tables"][$var]["cols"][$row]["page_list"] = "true";
    $newTable["tables"][$var]["cols"][$row]["page_detail"] = "true";
    $newTable["tables"][$var]["cols"][$row]["json"] = "true";


    $newTable["tables"][$var]["items_focus"] = "scroll";
    $newTable["tables"][$var]["max_items"] = "50";
    $newTable["tables"][$var]["fetch_per_scroll"] = "1";
    $newTable["tables"][$var]["icon"] = "ion-social-buffer";
    $newTable["tables"][$var]["relation_to"] = "localforage";
    $newTable["tables"][$var]["localstorage"] = "none";
    $newTable["tables"][$var]["motions"] = "none";
    $newTable["tables"][$var]["bookmarks"] = "none";
    $newTable["tables"][$var]["column-for-price"] = "none";
    $newTable["tables"][$var]["currency-symbol"] = "$";
    $newTable["tables"][$var]["version"] = "Upd.".date("ymdhi");
    $newTable["tables"][$var]["prefix"] = $var;
    $newTable["tables"][$var]["auth"]["type"] = "x-basic";

    file_put_contents(JSM_PATH."/projects/".$file_name."/tables.".$var.".json",json_encode($newTable));


    // TODO: -----|-- CREATE PAGE LISTING
    $old_page = json_decode(file_get_contents(JSM_PATH."/projects/".$file_name."/page.".$var.".json"),true);
    $newPage["page"][0] = array();
    $newPage["page"][0]["title"] = $raw_config["page_builder"]["wp_very_simple_event_list"][$var]["page_title"];
    $newPage["page"][0]["builder_link"] = @$_SERVER["HTTP_REFERER"];
    $newPage["page"][0]["prefix"] = $var;
    $newPage["page"][0]["img_bg"] = $raw_config["page_builder"]["wp_very_simple_event_list"][$var]["page_bg"];
    //$newPage["page"][0]["lock"] = true;
    $newPage["page"][0]["menutype"] = $old_page["page"][0]["menutype"];
    $newPage["page"][0]["parent"] = $old_page["page"][0]["parent"];
    $newPage["page"][0]["menu"] = $file_name;
    $newPage["page"][0]["js"] = "\$ionicConfig.backButton.text(\"\");";
    $newPage["page"][0]["table-code"]["url_detail"] = "";
    $newPage["page"][0]["table-code"]["url_list"] = "";
    $newPage["page"][0]["scroll"] = true;
    $newPage["page"][0]["for"] = "table-item";
    $newPage["page"][0]["css"] = "table.event td{padding:3px}";

    $newPage["page"][0]["content"] = null;
    $newPage["page"][0]["content"] .= "<ion-refresher pulling-text=\"{{ 'Pull to refresh...' | translate }}\" on-refresh=\"doRefresh()\"></ion-refresher>"."\r\n";
    $newPage["page"][0]["content"] .= "<div class=\"list\">"."\r\n";
    $newPage["page"][0]["content"] .= "<div class=\"card\" ng-repeat=\"item in data_".$var."s\"  >"."\r\n";

    $newPage["page"][0]["content"] .= "<div class=\"item item-text-wrap\">"."\r\n";
    $newPage["page"][0]["content"] .= "<h1 ng-bind-html=\"item.title.rendered | to_trusted\"></h1>"."\r\n";


    $newPage["page"][0]["content"] .= "<table class=\"event\">"."\r\n";
    $newPage["page"][0]["content"] .= "<tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>Date</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>:</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td><span class=\"label\">{{ item.x_metadata.event_date | phpTime | date }}</span></td>"."\r\n";
    $newPage["page"][0]["content"] .= "</tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>Time</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>:</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>{{ item.x_metadata.event_time }}</td>"."\r\n";
    $newPage["page"][0]["content"] .= "</tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>Start</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>:</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>{{ item.x_metadata.event_start_date | phpTime | date }}</td>"."\r\n";
    $newPage["page"][0]["content"] .= "</tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>Location</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>:</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>{{ item.x_metadata.event_location }}</td>"."\r\n";
    $newPage["page"][0]["content"] .= "</tr>"."\r\n";
    $newPage["page"][0]["content"] .= "</table>"."\r\n";
    $newPage["page"][0]["content"] .= "</div>"."\r\n";
    $newPage["page"][0]["content"] .= "<div class=\"item item-body\"><a href=\"#/".$file_name."/".$var."_singles/{{item.id}}\" class=\"button button-small button-calm pull-right\">Readmore</a></div>"."\r\n";
    $newPage["page"][0]["content"] .= "</div>"."\r\n";
    $newPage["page"][0]["content"] .= "</div>"."\r\n";
    $newPage["page"][0]["content"] .= "<br/><br/><br/><br/>"."\r\n";

    file_put_contents(JSM_PATH."/projects/".$file_name."/page.".$var.".json",json_encode($newPage));


    // TODO: -----|-- CREATE PAGE DETAIL
    $newPage["page"][0] = array();
    $newPage["page"][0]["title"] = "{{ ".$var.".title.rendered }}";
    $newPage["page"][0]["builder_link"] = "";
    $newPage["page"][0]["prefix"] = $var."_singles";
    $newPage["page"][0]["img_bg"] = $raw_config["page_builder"]["wp_very_simple_event_list"][$var]["page_bg"];
    $newPage["page"][0]["lock"] = true;
    $newPage["page"][0]["menutype"] = "sub-".$_SESSION["PROJECT"]["menu"]["type"];
    $newPage["page"][0]["parent"] = $var;
    $newPage["page"][0]["menu"] = 'false';
    $newPage["page"][0]["js"] = "\$ionicConfig.backButton.text(\"\");";
    $newPage["page"][0]["table-code"]["url_detail"] = "";
    $newPage["page"][0]["table-code"]["url_list"] = "";
    $newPage["page"][0]["scroll"] = true;
    $newPage["page"][0]["for"] = "table-item";
    $newPage["page"][0]["last_edit_by"] = "table (event)";
    $newPage["page"][0]["query"][0] = "id";
    $newPage["page"][0]["content"] = null;

    $newPage["page"][0]["content"] .= "<div class=\"list card\">"."\r\n";
    $newPage["page"][0]["content"] .= "<div class=\"item item-text-wrap\">"."\r\n";
    $newPage["page"][0]["content"] .= "<h1 ng-bind-html=\"event.title.rendered | to_trusted\"></h1>"."\r\n";
    $newPage["page"][0]["content"] .= "<img class=\"full-image\" ng-if=\"event.x_featured_media_large\"  src=\"{{ event.x_featured_media_large }}\" />"."\r\n";
    $newPage["page"][0]["content"] .= "<table class=\"event\">"."\r\n";
    $newPage["page"][0]["content"] .= "<tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>Date</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>:</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td><span class=\"label\">{{ event.x_metadata.event_date | phpTime | date }}</span></td>"."\r\n";
    $newPage["page"][0]["content"] .= "</tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>Time</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>:</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>{{ event.x_metadata.event_time }}</td>"."\r\n";
    $newPage["page"][0]["content"] .= "</tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>Start</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>:</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>{{ event.x_metadata.event_start_date | phpTime | date }}</td>"."\r\n";
    $newPage["page"][0]["content"] .= "</tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<tr>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>Location</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>:</td>"."\r\n";
    $newPage["page"][0]["content"] .= "<td>{{ event.x_metadata.event_location }}</td>"."\r\n";
    $newPage["page"][0]["content"] .= "</tr>"."\r\n";
    $newPage["page"][0]["content"] .= "</table>"."\r\n";
    $newPage["page"][0]["content"] .= "</div>"."\r\n";
    $newPage["page"][0]["content"] .= "<div class=\"item item-text-wrap noborder to_trusted {{ fontsize }}\" ng-bind-html=\"event.content.rendered | strHTML\"></div>"."\r\n";
    
    $newPage["page"][0]["content"] .= "<div class=\"item item-body\">"."\r\n";
    $newPage["page"][0]["content"] .= "<a class=\"button button-small button-assertive pull-right\" run-social-sharing message=\"{{event.link}}\"><i class=\"icon ion-android-share-alt\"></i> Share This Event</a>"."\r\n";
$newPage["page"][0]["content"] .= "</div>"."\r\n";

    $newPage["page"][0]["content"] .= "</div>"."\r\n";
    $newPage["page"][0]["content"] .= "<br/><br/><br/><br/>"."\r\n";


    file_put_contents(JSM_PATH."/projects/".$file_name."/page.".$var."_singles.json",json_encode($newPage));

    buildIonic($file_name);
    header("Location: ./?page=x-page-builder&prefix=page_wp_very_simple_event_list&target=".$var);
    die();
}


// TODO: CREATE FORM INPUT

$form_input .= "<blockquote class=\"blockquote blockquote-warning\">";
$form_input .= "<h4>How to use it?</h4>";
$form_input .= "<ol>";
$form_input .= "<li>Login to your WordPress, and please install the plugin: <a href=\"https://wordpress.org/plugins/very-simple-event-list/\" target=\"_blank\">Very Simple Event List</a> then activate.</li>";
$form_input .= "<li>Complete the form below and click the <code>Save Setting</code> button 2 times</li>";
$form_input .= "</ol>";
$form_input .= "</blockquote>";
// TODO: -----|-- PAGE TARGET
$page_target_list[] = array("label" => "< select page >","value" => "");
$z = 1;
foreach($project->get_pages() as $page)
{
    $page_target_list[$z] = array("label" => "Page `".$page["prefix"]."` ".$page["builder"]."","value" => $page["prefix"]);
    if($_GET["target"] == $page["prefix"])
    {
        $page_target_list[$z]["active"] = true;
    }
    $z++;
}
$form_input .= $bs->FormGroup("page_target","horizontal","select","Page Target",$page_target_list,"Page will be overwritten",null,"4");


if($_GET["target"] != "")
{

    $form_input .= "<h4>Settings</h4>";

    // TODO: -----|-- PAGE TITLE
    if(!isset($raw_config["page_builder"]["wp_very_simple_event_list"][$var]["page_title"]))
    {
        $raw_config["page_builder"]["wp_very_simple_event_list"][$var]["page_title"] = "My Page";
    }
    $form_input .= $bs->FormGroup("wp_very_simple_event_list[page_title]","horizontal","text","Page Title","","write the text for the page title","","7",$raw_config["page_builder"]["wp_very_simple_event_list"][$var]["page_title"]);

    // TODO: -----|-- PAGE BACKGROUND
    if(!isset($raw_config["page_builder"]["wp_very_simple_event_list"][$var]["page_bg"]))
    {
        $raw_config["page_builder"]["wp_very_simple_event_list"][$var]["page_bg"] = "data/images/background/transparent.png";
    }
    $form_input .= $bs->FormGroup("wp_very_simple_event_list[page_bg]","horizontal","text","Page Background","","select an image that will be used for the background page.","data-type=\"image-picker\"","7",$raw_config["page_builder"]["wp_very_simple_event_list"][$var]["page_bg"]);

    // TODO: -----|-- SITE URL
    if(!isset($raw_config["page_builder"]["wp_very_simple_event_list"][$var]["site_url"]))
    {
        $raw_config["page_builder"]["wp_very_simple_event_list"][$var]["site_url"] = "http://yourwordpress.com/";
    }
    $form_input .= $bs->FormGroup("wp_very_simple_event_list[site_url]","horizontal","text","Site URL","","","","7",$raw_config["page_builder"]["wp_very_simple_event_list"][$var]["site_url"]);

    // TODO: SET PREVIEW EMULATOR
    $preview_url = "output/".$file_name."/www/#/".$file_name."/".$var;
}
$footer .= "<script type=\"text/javascript\">$(\"#page_target\").on(\"change\",function(){return window.location=\"./?page=x-page-builder&prefix=page_wp_very_simple_event_list&target=\"+$(\"#page_target\").val(),!1});</script>";
