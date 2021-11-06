<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

$require_target_page = true;

if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}
$table_json = file_get_contents(JSM_PATH . '/system/includes/page-builder/page_youtube/json/table.youtube.json');
$_table_youtube = json_decode($table_json, true);

if (isset($_POST['page-builder']))
{
    if (isset($_POST['page_target']))
    {
        $_GET['target'] = $_POST['page_target'];
    }

    $postdata['prefix'] = str2var($_GET['target']);
    $postdata['youtube'] = $_POST['youtube'];

    $json_save['page_builder']['youtube'][$postdata['prefix']] = $postdata;
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.youtube.' . $postdata['prefix'] . '.json', json_encode($json_save));

    $playlistId = $api_key = '';
    $playlistId = $postdata['youtube']['playlistID'];
    $api_key = $postdata['youtube']['apiKey'];
    $youtube_url = 'https://www.googleapis.com/youtube/v3/playlistItems?maxResults=50&part=id,snippet&playlistId=' . $playlistId . '&key=' . $api_key;


    $table_youtube['tables'][$postdata['prefix']] = $_table_youtube['tables']['youtube'];
    $table_youtube['tables'][$postdata['prefix']]['parent'] = $postdata['prefix'];
    $table_youtube['tables'][$postdata['prefix']]['title'] = $postdata['prefix'];
    $table_youtube['tables'][$postdata['prefix']]['prefix'] = $postdata['prefix'];
    $table_youtube['tables'][$postdata['prefix']]['db_url'] = $youtube_url;
    $table_youtube['tables'][$postdata['prefix']]['db_url_single'] = '';
    $table_youtube['tables'][$postdata['prefix']]['db_type'] = 'online';
    $table_youtube['tables'][$postdata['prefix']]['db_var'] = '.items';
    $table_youtube['tables'][$postdata['prefix']]['version'] = 'Upd.' . date('ymdhi');
    $table_youtube['tables'][$postdata['prefix']]['builder_link'] = @$_SERVER["HTTP_REFERER"];

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.' . $postdata['prefix'] . '.json', json_encode($table_youtube));


    // TODO: + page -+- listing
    $old_page = json_decode(file_get_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $postdata['prefix'] . '.json'), true);

    $page_list_video = null;
    $page_list_video['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_list_video['page'][0]['title'] = $postdata['youtube']['title'];
    $page_list_video['page'][0]['prefix'] = $postdata['prefix'];
    //$page_list_video['page'][0]['lock'] = true;
    $page_list_video['page'][0]['cache'] = 'false';
    $page_list_video['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
    $page_list_video['page'][0]['menutype'] = $old_page['page'][0]['menutype'];
    $page_list_video['page'][0]['menu'] = $file_name;
    $page_list_video['page'][0]['for'] = 'table-list';
    $page_list_video['page'][0]['css'] = '';
    $page_list_video['page'][0]['table-code']['url_detail'] = '#/' . $file_name . '/' . $postdata['prefix'] . '_singles/{{item.snippet.resourceId.videoId}}';
    $page_list_video['page'][0]['table-code']['url_list'] = '#/' . $file_name . '/' . $postdata['prefix'] . 's';
    $page_list_video['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_list_video['page'][0]['scroll'] = true;
    $page_list_video['page'][0]['content'] = '
    
<!-- code refresh -->
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>
<!-- ./code refresh -->


<!-- code search -->
<ion-list class="card list" >
	<div class="item item-input">
		<i class="icon ion-search placeholder-icon"></i>
		<input type="search" ng-model="filter_' . $postdata['prefix'] . 's" placeholder="Search" aria-label="filter ' . $postdata['prefix'] . 's" />
	</div>
</ion-list>
<!-- ./code search -->
    
<!-- code listing -->

<div class="list animate-none">

    <div class="list card" ng-repeat="item in ' . $postdata['prefix'] . 's | filter:filter_' . $postdata['prefix'] . 's as results" ng-init="$last ? fireEvent() : null"  >
        
        <div class="item item-colorful" ng-bind-html="item.snippet.title | to_trusted"></div>
        
        <div class="item item-thumbnail-left item-text-wrap">
            <img alt="" class="full-image" ng-src="{{item.snippet.thumbnails.default.url}}" />
            <p ng-bind-html="item.snippet.description | limitTo:75 | to_trusted"></p>
        </div>
        
        <a class="item item-icon-left assertive" ng-href="#/' . $file_name . '/' . $postdata['prefix'] . '_singles/{{item.snippet.resourceId.videoId}}" >
            <i class="icon ion-social-youtube"></i>
            Start Watching...
        </a>
        
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


    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $postdata['prefix'] . '.json', json_encode($page_list_video));


    // TODO: + page -+- single
    $page_detail_video = null;
    $page_detail_video['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];


    $page_detail_video['page'][0]['title'] = '{{ ' . $postdata['prefix'] . '.snippet.title }}';
    $page_detail_video['page'][0]['prefix'] = $postdata['prefix'] . '_singles';
    //$page_detail_video['page'][0]['lock'] = true;
    $page_detail_video['page'][0]['parent'] = '' . $postdata['prefix'] . '';
    $page_detail_video['page'][0]['menutype'] = 'sub-' . $_SESSION['PROJECT']['menu']['type'];
    $page_detail_video['page'][0]['menu'] = false;
    $page_detail_video['page'][0]['for'] = 'table-item';
    $page_detail_video['page'][0]['last_edit_by'] = 'page-builder';
    $page_detail_video['page'][0]['css'] = '.embed_container {background: #000;}';
    $page_detail_video['page'][0]['query'][0] = 'snippet.resourceId.videoId';
    $page_detail_video['page'][0]['cache'] = 'false';

    $page_detail_video['page'][0]['js'] = '
    
$ionicConfig.backButton.text("");
$scope.pauseVideo = function() {
    var iframe = document.getElementsByTagName("iframe")[0].contentWindow;
    iframe.postMessage(\'{"event":"command","func":"\' + \'pauseVideo\' +   \'","args":""}\', \'*\');
}


$scope.playVideo = function() {
    var iframe = document.getElementsByTagName("iframe")[0].contentWindow;
   iframe.postMessage(\'{"event":"command","func":"\' + \'playVideo\' +   \'","args":""}\', \'*\');
}

$scope.$on("$ionicView.beforeLeave", function(){
	$scope.pauseVideo();
});

$scope.$on("$ionicView.enter", function(){
	$scope.playVideo();
});
';


    $page_detail_video['page'][0]['content'] = '
    
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>
<div class="list">

	<div class="item item-title" ng-bind-html="' . $postdata['prefix'] . '.snippet.title | to_trusted">
    {{' . $postdata['prefix'] . '.snippet.title}}
    </div>
    
	<div class="item noborder" ng-if="' . $postdata['prefix'] . '.snippet.resourceId.videoId" >
        <div class="embed_container">
            <iframe 
                width="100%" 
                ng-src="{{ \'https://www.youtube.com/embed/\' + ' . $postdata['prefix'] . '.snippet.resourceId.videoId + \'?enablejsapi=1\' | trustUrl }}" 
                frameborder="0" 
                allowfullscreen>
            </iframe>
        </div>
    </div>
	<div class="item item-text-wrap noborder to_trusted" 
        ng-bind-html="' . $postdata['prefix'] . '.snippet.description | strHTML">
    </div>
    
    <div class="item item-text-wrap text-center noborder">
        <button class="button button-assertive">Open With Youtube App</button>
    </div>
    
</div>

    
    ';


    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $postdata['prefix'] . '_singles.json', json_encode($page_detail_video));


    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=page_youtube&target=' . $postdata['prefix']);
    die();
}

$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.youtube.' . str2var($_GET['target']) . '.json';
$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $get_raw_data['page_builder']['youtube'][str2var($_GET['target'])];
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

if (!isset($raw_data['youtube']['title']))
{
    $raw_data['youtube']['title'] = 'Youtube';
}
if (!isset($raw_data['youtube']['apiKey']))
{
    $raw_data['youtube']['apiKey'] = '';
}
if (!isset($raw_data['youtube']['playlistID']))
{
    $raw_data['youtube']['playlistID'] = '';
}

$form_input .= 'Before please read: <a target="_blank" href="https://www.youtube.com/static?template=terms">Terms of Service</a>';


$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');
if ($_GET['target'] != '')
{
    $form_input .= $bs->FormGroup('youtube[title]', 'horizontal', 'text', 'Title', 'Youtube', '', '', '6', $raw_data['youtube']['title']);
    $form_input .= $bs->FormGroup('youtube[apiKey]', 'horizontal', 'text', 'API Key', 'AIzaSyAnAi9xKNqI_xNGDKHtFZrInz5l_QkMqNs', 'Read <a target="_blank" href="https://console.developers.google.com/apis/api/youtube/">Google Documentation</a>', '', '6', $raw_data['youtube']['apiKey']);
    $form_input .= $bs->FormGroup('youtube[playlistID]', 'horizontal', 'text', 'Playlist ID', 'PLSC2odpss-AjKvqTagCQl77ieIJcas1ud', 'Get from youtube playlist:<br/>https://www.youtube.com/watch?v=txRnx0-BgPw&index=2&list=<code>PLSC2odpss-AjKvqTagCQl77ieIJcas1ud</code>', '', '6', $raw_data['youtube']['playlistID']);
}
$footer .= '
<script type="text/javascript">
     $("#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_youtube&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>