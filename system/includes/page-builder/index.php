<?php

if (!isset($_GET['build']))
{
    die(":)");
}


$prefix = 'xxx';
$code = null;
$code .= "" . '<?php' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . '// TO' . 'DO: CONFIG' . "\r\n";
$code .= "" . '$require_target_page = true;' . "\r\n";
$code .= "" . '$project = new ImaProject();' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . '// TO' . 'DO: VALIDATION - USE PAGEBUILDER MENU' . "\r\n";
$code .= "" . 'if (!defined(\'JSM_EXEC\')){die(\':)\');}' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . '// TO' . 'DO: VALIDATION - PROJECT ACTIVE' . "\r\n";
$code .= "" . 'if(isset($_SESSION["FILE_NAME"])){' . "\r\n";
$code .= "" . "\t" . '$file_name = $_SESSION["FILE_NAME"];' . "\r\n";
$code .= "" . '}else{' . "\r\n";
$code .= "" . "\t" . 'header("Location: ./?page=dashboard&err=project");' . "\r\n";
$code .= "" . "\t" . 'die();' . "\r\n";
$code .= "" . '}' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . '// TO' . 'DO: GET PAGE TARGET' . "\r\n";
$code .= "" . 'if(isset($_GET["target"])){' . "\r\n";
$code .= "" . "\t" . '$var = str2var($_GET["target"]);' . "\r\n";
$code .= "" . '}' . "\r\n";
$code .= "" . '// TO' . 'DO: GET CURRENT CONFIG' . "\r\n";
$code .= "" . '$raw_config["' . $prefix . '"] = array();' . "\r\n";
$code .= "" . '$file_config = JSM_PATH . "/projects/" . $_SESSION["FILE_NAME"] . "/page_builder.' . $prefix . '.".$var.".json";' . "\r\n";
$code .= "" . 'if (file_exists($file_config)){' . "\r\n";
$code .= "\t" . '$raw_config = json_decode(file_get_contents($file_config),true);' . "\r\n";
$code .= "" . '}' . "\r\n";
$code .= "" . '// TO' . 'DO: GENERATE CODE' . "\r\n";
$code .= "" . 'if(isset($_POST["page-builder"])){' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "\t" . '// TO' . 'DO: -----|-- SAVE CONFIG' . "\r\n";
$code .= "" . "\t" . '$raw_config = array();' . "\r\n";
$code .= "" . "\t" . '$raw_config["page_builder"]["' . $prefix . '"][$var] = $_POST["' . $prefix . '"];' . "\r\n";
$code .= "" . "\t" . '// update value' . "\r\n";
$code .= "" . "\t" . '$raw_config["page_builder"]["' . $prefix . '"][$var]["page_title"] = htmlentities($_POST["' . $prefix . '"]["page_title"]);' . "\r\n";
$code .= "" . "\t" . '$raw_config["page_builder"]["' . $prefix . '"][$var]["site_url"] = htmlentities($_POST["' . $prefix . '"]["site_url"]);' . "\r\n";
$code .= "" . "\t" . '$raw_config["page_builder"]["' . $prefix . '"][$var]["page_bg"] = htmlentities($_POST["' . $prefix . '"]["page_bg"]);' . "\r\n";
$code .= "" . "\t" . 'file_put_contents($file_config,json_encode($raw_config));' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "\t" . '// TO' . 'DO: -----|-- CREATE TABLE' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["parent"] = $var;' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["title"] = $var;' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["builder_link"] = @$_SERVER["HTTP_REFERER"];' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["template"] = "manual_coding";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["template_single"] = "none";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["itemtype"] = "item";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["itemcolor"] = "colorful";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["db_type"] = "online";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["db_var"] = "";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["db_var_single"] = "";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["db_url"] = "http://ionic.co.id/mp3-listing.php";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["db_url_single"] = "";' . "\r\n";
$code .= "" . "\t" . '$row=0;' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["cols"][$row]["label"] = "#ID";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["cols"][$row]["title"] = "id";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["cols"][$row]["type"] = "id";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["cols"][$row]["json"] = "true";' . "\r\n";
$code .= "" . "\t" . '$row++;' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["cols"][$row]["label"] = "title";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["cols"][$row]["title"] = "title";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["cols"][$row]["type"] = "heading-1";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["cols"][$row]["page_list"] = "true";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["cols"][$row]["page_detail"] = "true";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["cols"][$row]["json"] = "true";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["items_focus"] = "scroll";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["max_items"] = "50";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["fetch_per_scroll"] = "1";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["icon"] = "ion-social-buffer";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["relation_to"] = "localforage";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["localstorage"] = "none";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["motions"] = "none";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["bookmarks"] = "none";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["column-for-price"] = "none";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["currency-symbol"] = "$";' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["version"] = "Upd." . date("ymdhi");' . "\r\n";
$code .= "" . "\t" . '$newTable["tables"][$var]["prefix"] = $var;' . "\r\n";
$code .= "" . "\t" . 'file_put_contents(JSM_PATH . "/projects/" . $file_name . "/tables." . $var . ".json", json_encode($newTable));' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "\t" . '// TO' . 'DO: -----|-- CREATE PAGE LISTING' . "\r\n";
$code .= "" . "\t" . '$old_page = json_decode(file_get_contents(JSM_PATH . "/projects/" . $file_name . "/page." . $var . ".json"), true);' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0] = array();' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["title"] = "Page Listing";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["builder_link"] = @$_SERVER["HTTP_REFERER"];' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["prefix"] = $var;' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["img_bg"] = $raw_config["page_builder"]["' . $prefix . '"][$var]["page_bg"];' . "\r\n";
$code .= "" . "\t" . '//$newPage["page"][0]["lock"] = true;' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["menutype"] = $old_page["page"][0]["menutype"];' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["parent"] = $old_page["page"][0]["parent"];' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["menu"] = $file_name;' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["js"] = "\$ionicConfig.backButton.text(\"\");";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["table-code"]["url_detail"] = "";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["table-code"]["url_list"] = "";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["scroll"] = true;' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["for"] = "table-item";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["content"] = null;' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["content"] .= "<ion-refresher pulling-text=\"{{ \'Pull to refresh...\' | translate }}\" on-refresh=\"doRefresh()\"></ion-refresher>"."\r\n";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["content"] .= "<div class=\"card\">"."\r\n";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["content"] .= "<a class=\"item\" ng-repeat=\"item in data_".$var."s\"  href=\"#/" . $file_name . "/" . $var . "_singles/{{item.id}}\" >"."\r\n";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["content"] .= "<pre>{{ item | json }}</pre>"."\r\n";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["content"] .= "</a>"."\r\n";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["content"] .= "</div>"."\r\n";' . "\r\n";
$code .= "" . "\t" . 'file_put_contents(JSM_PATH . "/projects/" . $file_name . "/page." . $var . ".json", json_encode($newPage));' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "\t" . '// TO' . 'DO: -----|-- CREATE PAGE DETAIL' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0] = array();' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["title"] = "{{ ". $var .".title }}";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["builder_link"] = ""; ' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["prefix"] = $var ."_singles";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["img_bg"] = $raw_config["page_builder"]["' . $prefix . '"][$var]["page_bg"];' . "\r\n";
$code .= "" . "\t" . '//$newPage["page"][0]["lock"] = true;' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["menutype"] =  "sub-" . $_SESSION["PROJECT"]["menu"]["type"];' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["parent"] =  $var;' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["menu"] = $file_name;' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["js"] = "\$ionicConfig.backButton.text(\"\");";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["table-code"]["url_detail"] = "";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["table-code"]["url_list"] = "";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["scroll"] = true;' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["for"] = "table-item";' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["content"] = null;' . "\r\n";
$code .= "" . "\t" . '$newPage["page"][0]["query"][0] = "id";' . "\r\n";

$code .= "" . "\t" . 'file_put_contents(JSM_PATH . "/projects/" . $file_name . "/page." . $var . "_singles.json", json_encode($newPage));' . "\r\n";


$code .= "" . "\r\n";
$code .= "" . "\t" . 'buildIonic($file_name);' . "\r\n";
$code .= "" . "\t" . 'header("Location: ./?page=x-page-builder&prefix=page_' . $prefix . '&target=" . $var);' . "\r\n";
$code .= "" . "\t" . 'die();' . "\r\n";
$code .= "" . '}' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "\r\n";
$code .= "" . '// TO' . 'DO: CREATE FORM INPUT' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "" . '$form_input .= "<blockquote class=\"blockquote blockquote-warning\">"; ' . "\r\n";
$code .= "" . "" . '$form_input .= "<h4>How to use it?</h4>"; ' . "\r\n";
$code .= "" . "" . '$form_input .= "<ol>"; ' . "\r\n";
$code .= "" . "" . '$form_input .= "<li>Complete the form below and click the <code>Save Setting</code> button 2 times</li>"; ' . "\r\n";
$code .= "" . "" . '$form_input .= "</ol>"; ' . "\r\n";
$code .= "" . "" . '$form_input .= "</blockquote>"; ' . "\r\n";
$code .= "" . '// TO' . 'DO: -----|-- PAGE TARGET' . "\r\n";
$code .= "" . '$page_target_list[] = array("label" => "< select page >", "value" => "");' . "\r\n";
$code .= "" . '$z = 1;' . "\r\n";
$code .= "" . 'foreach ($project->get_pages() as $page){' . "\r\n";
$code .= "" . "\t" . '$page_target_list[$z] = array("label" => "Page `" . $page["prefix"] . "` " . $page["builder"] . "", "value" => $page["prefix"]);' . "\r\n";
$code .= "" . "\t" . 'if ($_GET["target"] == $page["prefix"]){' . "\r\n";
$code .= "" . "\t\t" . '$page_target_list[$z]["active"] = true;' . "\r\n";
$code .= "" . "\t" . '}' . "\r\n";
$code .= "" . "\t" . '$z++;' . "\r\n";
$code .= "" . '}' . "\r\n";
$code .= "" . '$form_input .= $bs->FormGroup("page_target", "horizontal", "select", "Page Target", $page_target_list, "Page will be overwritten", null, "4");' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "\r\n";
$code .= "" . 'if ($_GET["target"] != ""){' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "\t" . '$form_input .= "<h4>Settings</h4>"; ' . "\r\n";
$code .= "" . "\r\n";
$code .= "" . "\t" . '// TO' . 'DO: -----|-- PAGE TITLE' . "\r\n";
$code .= "" . "\t" . 'if(!isset($raw_config["page_builder"]["' . $prefix . '"][$var]["page_title"])){' . "\r\n";
$code .= "" . "\t\t" . '$raw_config["page_builder"]["' . $prefix . '"][$var]["page_title"] = "My Page";' . "\r\n";
$code .= "" . "\t" . '}' . "\r\n";
$code .= "" . "\t" . '$form_input .= $bs->FormGroup("' . $prefix . '[page_title]", "horizontal", "text", "Page Title", "", "write the text for the page title", "", "7", $raw_config["page_builder"]["' . $prefix . '"][$var]["page_title"]);' . "\r\n";
$code .= "" . "\r\n";

$code .= "" . "\t" . '// TO' . 'DO: -----|-- PAGE BACKGROUND' . "\r\n";
$code .= "" . "\t" . 'if(!isset($raw_config["page_builder"]["' . $prefix . '"][$var]["page_bg"])){' . "\r\n";
$code .= "" . "\t\t" . '$raw_config["page_builder"]["' . $prefix . '"][$var]["page_bg"] = "data/images/background/transparent.png";' . "\r\n";
$code .= "" . "\t" . '}' . "\r\n";
$code .= "" . "\t" . '$form_input .= $bs->FormGroup("' . $prefix . '[page_bg]", "horizontal", "text", "Page Background", "", "select an image that will be used for the background page.", "data-type=\"image-picker\"", "7", $raw_config["page_builder"]["' . $prefix . '"][$var]["page_bg"]);' . "\r\n";
$code .= "" . "\r\n";

$code .= "" . "\t" . '// TO' . 'DO: -----|-- SITE URL' . "\r\n";
$code .= "" . "\t" . 'if(!isset($raw_config["page_builder"]["' . $prefix . '"][$var]["site_url"])){' . "\r\n";
$code .= "" . "\t\t" . '$raw_config["page_builder"]["' . $prefix . '"][$var]["site_url"] = "http://yourwordpress.com/";' . "\r\n";
$code .= "" . "\t" . '}' . "\r\n";
$code .= "" . "\t" . '$form_input .= $bs->FormGroup("' . $prefix . '[site_url]", "horizontal", "text", "Site URL", "", "", "", "7", $raw_config["page_builder"]["' . $prefix . '"][$var]["site_url"]);' . "\r\n";
$code .= "" . "\r\n";

$code .= "" . "\t" . '// TO' . 'DO: SET PREVIEW EMULATOR' . "\r\n";
$code .= "" . "\t" . '$preview_url = "output/" . $file_name . "/www/#/" . $file_name . "/" . $var;' . "\r\n";
$code .= "" . '}' . "\r\n";
$code .= "" . '$footer .= "<script type=\"text/javascript\">$(\"#page_target\").on(\"change\",function(){return window.location=\"./?page=x-page-builder&prefix=page_' . $prefix . '&target=\"+$(\"#page_target\").val(),!1});</script>";' . "\r\n";

echo '<pre>';
echo str_replace("<br />", "", highlight_string($code, true));
echo '</pre>';

file_put_contents('page_' . $prefix . '.templates.php', $code);

if (!file_exists('page_' . $prefix))
{
    @mkdir('page_' . $prefix . '/assets/', 0777,true);
}

$code = '
{
	"info":"Still Beta Testing",
	"for":"certain pages"
}
' ;
file_put_contents('page_' . $prefix . '/readme.txt', $code);
?>
 