<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}

$require_target_page = true;

$rss2json_settings['rss2json'] = array();
$file_settings = JSM_PATH . '/projects/' . $_SESSION['FILE_NAME'] . '/rss2json.json';
if (file_exists($file_settings))
{
    $rss2json_settings = json_decode(file_get_contents($file_settings), true);
}


$table_rss = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/page_rss/json/table.rss.json'), true);
$page_rss = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/page_rss/json/page.rss.json'), true);
$page_rss_singles = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/page_rss/json/page.rss_singles.json'), true);

$background = 'data/images/background/bg1.jpg';

if (isset($_POST['page-builder']))
{
    if (isset($_POST['page_target']))
    {
        $postdata['prefix'] = $_POST['page_target'];
    }

    $var = $postdata['prefix'];

    // TODO: page builder settings

    $json_save['page_builder']['page_rss'][$var]['rss_url'] = htmlentities($_POST['page_rss']['rss_url']);
    $json_save['page_builder']['page_rss'][$var]['api_url'] = htmlentities($_POST['page_rss']['api_url']);
    $json_save['page_builder']['page_rss'][$var]['rss_title'] = htmlentities($_POST['page_rss']['rss_title']);
    $rss_url = $json_save['page_builder']['page_rss'][$var]['rss_url'];
    $api_url = $json_save['page_builder']['page_rss'][$var]['api_url'];
    $rss_title = $json_save['page_builder']['page_rss'][$var]['rss_title'];

    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.page_rss.' . $var . '.json', json_encode($json_save));

    // TODO: create table
    unset($table_posts['tables']['post']['db_url_dinamic']);

    $_table_rss['tables'][$var] = $table_rss['tables']['rss'];
    $_table_rss['tables'][$var]['db_url'] = $api_url . '?json=' . $var;
    $_table_rss['tables'][$var]['prefix'] = $var;
    $_table_rss['tables'][$var]['parent'] = $var;
    $_table_rss['tables'][$var]['title'] = $var;
    $_table_rss['tables'][$var]['bookmarks'] = 'none';
    $_table_rss['tables'][$var]['version'] = 'Upd.' . date('ymdhi');
    $_table_rss['tables'][$var]['builder_link'] = @$_SERVER["HTTP_REFERER"];

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.' . $var . '.json', json_encode($_table_rss));

    // TODO: + page -+- rss
    $old_page = json_decode(file_get_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $postdata['prefix'] . '.json'), true);

    $page_rss['page'][0]['title'] = $rss_title;
    $page_rss['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_rss['page'][0]['prefix'] = $var;
    $page_rss['page'][0]['img_bg'] = $background;
    $page_rss['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
    $page_rss['page'][0]['lock'] = true;
    $page_rss['page'][0]['menutype'] = $old_page['page'][0]['menutype'];
    $page_rss['page'][0]['query_value'] = $cat_id;
    $page_rss['page'][0]['menu'] = $file_name;
    $page_rss['page'][0]['js'] = '';
    $page_rss['page'][0]['table-code']['url_detail'] = '#/' . $file_name . '/' . $var . '_singles/{{item.id}}';
    $page_rss['page'][0]['table-code']['url_list'] = '#/' . $file_name . '/' . $var . 's';
    $page_rss['page'][0]['scroll'] = true;
    $page_rss['page'][0]['content'] = '
    
<!-- code refresh -->
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>
<!-- ./code refresh -->


<!-- code search -->
<ion-list class="card list" >
	<div class="item item-input">
		<i class="icon ion-search placeholder-icon"></i>
		<input type="search" ng-model="filter_' . $var . 's" placeholder="Search" aria-label="filter ' . $var . 's" />
	</div>
</ion-list>
<!-- ./code search -->


<!-- code listing -->
<div class="list animate-none">
	<div class="list card" ng-repeat="item in ' . $var . 's | filter:filter_' . $var . 's as results" ng-init="$last ? fireEvent() : null" href="#/' . $file_name . '/' . $var . '_singles/{{item.id}}" >
		<div class="item item-colorful" ng-bind-html="item.title | to_trusted"></div>
		<div class="item item-thumbnail-left item-text-wrap">
            <img class="full-image" ng-src="{{ item.thumbnail }}" />
            <div><span ng-bind-html="item.content | stripTags | limitTo:75 | strHTML "></span>...</div>
	   </div>
		<a class="item button button-clear colorful ink" href="#/' . $file_name . '/' . $var . '_singles/{{item.id}}">More</a>
	</div>
</div>
<!-- ./code listing -->

<!-- code infinite scroll -->
<ion-list class="list">
	<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>
</ion-list>
<!-- ./code infinite scroll -->


<!-- code search result not found -->
<ion-list class="list">
	<div class="item" ng-if="results.length == 0" >
		<p>No results found...!</p>
	</div>
</ion-list>
<!-- code search result not found -->




    ';

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $var . '.json', json_encode($page_rss));


    // TODO: + page -+- rss_singles
    $page_rss_singles['page'][0]['title'] = '{{ ' . $var . '.title }}';
    $page_rss_singles['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_rss_singles['page'][0]['prefix'] = $var . '_singles';
    $page_rss_singles['page'][0]['parent'] = $var;
    $page_rss_singles['page'][0]['img_bg'] = ''; //$background;
    $page_rss_singles['page'][0]['lock'] = true;
    $page_rss_singles['page'][0]['for'] = 'table-item';
    $page_rss_singles['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_rss_singles['page'][0]['menu'] = $file_name;
    $page_rss_singles['page'][0]['menutype'] = 'sub-' . $_SESSION['PROJECT']['menu']['type'];
    $page_rss_singles['page'][0]['content'] = '
    
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>
   
<div class="list">
	<div class="item item-divider" ng-bind-html="' . $var . '.title | to_trusted">{{' . $var . '.title}}</div>
    
	<div class="item item-text-wrap noborder">
        <div class="to_trusted" ng-bind-html="' . $var . '.content | strHTML"></div>
    </div>

	<div class="item">
		<button run-social-sharing message="{{ ' . $var . '.x_link.attributes.href }}" class="button button-small ion-android-share-alt button-outline button-positive icon-left">Social Share</button>
	</div>
    
</div>
    
    
    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $var . '_singles.json', json_encode($page_rss_singles));

    // TODO: settings

    if (!isset($rss2json_settings['rss2json']['items']))
    {
        $rss2json_settings['rss2json']['items'] = array();
    }
    foreach ($rss2json_settings['rss2json']['items'] as $item)
    {
        $renew[md5($item['label'])]['label'] = $item['label'];
        $renew[md5($item['label'])]['url'] = $item['url'];
    }
    $renew[md5($var)]['label'] = $var;
    $renew[md5($var)]['url'] = $rss_url;
    $_rss2json_settings = $rss2json_settings;
    $_rss2json_settings['rss2json']['items'] = array_values($renew);

    file_put_contents($file_settings, json_encode($_rss2json_settings));

    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=page_rss&target=' . $var);
    die();

}
$var = str2var($_GET['target']);
$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.page_rss.' . $var . '.json';


$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $_raw_data['page_builder']['page_rss'][$var];
}
if (!isset($raw_data['rss_url']))
{
    $raw_data['rss_url'] = 'http://your_domain.org/feed/';
}

if (!isset($raw_data['api_url']))
{
    $raw_data['api_url'] = 'http://your_domain.org/rss2json.php';
}

if (!isset($raw_data['rss_title']))
{
    $raw_data['rss_title'] = 'RSS Feed';
}

// TODO: page target
$project = new ImaProject();
$out_path = 'output/' . $file_name;
$preview_url = $out_path . '/www/#/' . $_SESSION['PROJECT']['app']['prefix'] . '/' . $var;


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


$form_input .= '<blockquote class="blockquote blockquote-warning">';
$form_input .= '<p>This requires a server that supports php code</p>';
$form_input .= '<ol>';
$form_input .= '<li>Complete the form below, you can use multiple pages using a json url.</li>';
$form_input .= '<li>After all finish, used <a target="_blank" href="./?page=z-rss-to-json-converter">Backend Tools -> RSS 2 JSON Converter</a> for converting rss to json</li>';
$form_input .= '</ol>';
$form_input .= '</blockquote>';
$form_input .= '<hr/>';

$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');
if ($_GET['target'] != '')
{
    $form_input .= '<h4>Settings</h4>';
    $form_input .= $bs->FormGroup('page_rss[rss_title]', 'horizontal', 'text', 'Page Title', '', '', null, '7', $raw_data['rss_title']);
    $form_input .= $bs->FormGroup('page_rss[rss_url]', 'horizontal', 'text', 'RSS URL', 'http://demo.ihsana.net/rss/', '', null, '7', $raw_data['rss_url']);
    $form_input .= $bs->FormGroup('page_rss[api_url]', 'horizontal', 'text', 'RSS 2 JSON URL', 'http://demo.ihsana.net/rss2json.php', '<a target="_blank" href="./?page=z-rss-to-json-converter">RSS 2 JSON Converter</a>', null, '7', $raw_data['api_url']);
}

$footer .= '
<script type="text/javascript">
     $("#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_rss&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>