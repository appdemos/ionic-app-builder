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

$table_posts = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/page_wordpress/json/table.posts.json'), true);
$page_posts = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/page_wordpress/json/page.posts.json'), true);
$page_post_singles = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/page_wordpress/json/page.post_singles.json'), true);

$background = 'data/images/background/bg3.jpg';
$per_page = 12;
if (isset($_POST['page-builder']))
{
    if (isset($_POST['page_target']))
    {
        $postdata['prefix'] = $_POST['page_target'];
    }

    $var = $postdata['prefix'];

    // TODO: page builder settings

    $json_save['page_builder']['page_wordpress'][$var]['wp_url'] = htmlentities($_POST['page_wordpress']['wp_url']);
    $json_save['page_builder']['page_wordpress'][$var]['cat_id'] = htmlentities($_POST['page_wordpress']['cat_id']);
    $site = $json_save['page_builder']['page_wordpress'][$var]['wp_url'];
    $cat_id = $json_save['page_builder']['page_wordpress'][$var]['cat_id'];
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.page_wordpress.' . $var . '.json', json_encode($json_save));

    // TODO: create table
    unset($table_posts['tables']['post']['db_url_dinamic']);

    $_table_posts['tables'][$var] = $table_posts['tables']['post'];
    $_table_posts['tables'][$var]['db_url'] = $site . '/wp-json/wp/v2/posts/?_embed&categories=' . $cat_id . '&per_page=' . $per_page . '&page=1';
    $_table_posts['tables'][$var]['db_url_single'] = $site . '/wp-json/wp/v2/posts/';
    $_table_posts['tables'][$var]['prefix'] = $var;
    $_table_posts['tables'][$var]['parent'] = $var;
    $_table_posts['tables'][$var]['title'] = $var;
    $_table_posts['tables'][$var]['bookmarks'] = 'bookmark';
    $_table_posts['tables'][$var]['version'] = 'Upd.' . date('ymdhi');
    $_table_posts['tables'][$var]['builder_link'] =  @$_SERVER["HTTP_REFERER"];
    

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.' . $var . '.json', json_encode($_table_posts));

    // TODO: + page -+- posts

    $old_page = json_decode(file_get_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $var . '.json'), true);

    $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_posts['page'][0]['prefix'] = $var;
    $page_posts['page'][0]['img_bg'] = $background;
    $page_posts['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
    $page_posts['page'][0]['lock'] = true;
    $page_posts['page'][0]['menutype'] = $old_page['page'][0]['menutype'];
    $page_posts['page'][0]['title'] = htmlentities($var) . ' - {{ paging }}';
    $page_posts['page'][0]['table-code']['url_detail'] = '#/' . $file_name . '/' . $var . '_singles/{{item.id}}';
    $page_posts['page'][0]['table-code']['url_list'] = '#/' . $file_name . '/' . $var . 's';
    $page_posts['page'][0]['scroll'] = true;

    //$page_posts['page'][0]['query_value'] = $cat_id;
    $page_posts['page'][0]['menu'] = $file_name;
    $page_posts['page'][0]['js'] = '
$ionicConfig.backButton.text("");   
 
var UriListing = "' . $site . '/wp-json/wp/v2/posts?_embed&categories=' . $cat_id . '&per_page=' . $per_page . '";    
if(!$scope.paging){$scope.paging=1;}
$scope.updatePaging=function(ev){
    if(ev === true){
        $scope.paging++;
    }else{
        if($scope.paging===1){
            return null;
        }
        $scope.paging--;
    }
	$scope.fetchURL = UriListing + "&page="+$scope.paging;
	$scope.fetchURLp = UriListing + "&page="+$scope.paging+"&callback=JSON_CALLBACK";
	$scope.hashURL = md5.createHash($scope.fetchURL.replace(targetQuery,raplaceWithQuery));
    $ionicLoading.show({
		template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\'
	});
    $scope.doRefresh();
    $timeout(function() {
        $scope.scrollTop();
    }, 1000);
}
    
    
    ';
    $page_posts['page'][0]['content'] = ' 
	
<!-- code refresh -->
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>
<!-- ./code refresh -->


<!-- code search -->
<ion-list class="card list" >
	<div class="item item-input">
		<i class="icon ion-search placeholder-icon"></i>
		<input type="search" ng-model="filter_posts" placeholder="Filter" aria-label="filter posts" />
	</div>
</ion-list>
<!-- ./code search -->

<!-- code listing -->
<div class="list card" ng-repeat="item in ' . $var . 's | filter:filter_' . $var . 's as results" ng-init="$last ? fireEvent() : null" >
	
    <a class="item item-avatar" ng-href="#/' . $file_name . '/' . $var . '_singles/{{item.id}}">
        <img alt="" ng-src="{{item.x_gravatar}}" />
        <h2 ng-bind-html="item.title.rendered | to_trusted"></h2>
        <p>{{item.x_date}}</p>		
	</a>
 
	<div class="item item-body">
        <img class="full-image" ng-if="item.x_featured_media_medium" alt="" ng-src="{{item.x_featured_media_medium}}" zoom-view="true" zoom-src="{{item.x_featured_media_original}}" />
		<p class="to_trusted" ng-bind-html="item.excerpt.rendered | to_trusted"></p>            
	</div>
    
    <a class="item item-icon-left assertive" href="#/' . $file_name . '/' . $var . '_singles/{{item.id}}">
        <i class="icon ion-android-more-vertical"></i>
        Readmore
    </a>
  
</div>
<!-- ./code listing -->


<!-- code infinite scroll -->
<ion-list class="list">
	<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>
</ion-list>
<!-- ./code infinite scroll -->


<!-- code search result not found -->
<ion-list class="list card" ng-if="results.length == 0">
	<div class="item">
		<p>No results found...!</p>
	</div>
</ion-list>
<!-- code search result not found -->


<div class="list">
	<div class="item tabs tabs-secondary tabs-stable">
		<a class="tab-item" ng-click="updatePaging(false);"><i class="icon ion-chevron-left"></i>Back</a>
        <span class="tab-item">{{ paging }}</span>
		<a class="tab-item" ng-click="updatePaging(true);"><i class="icon ion-chevron-right"></i> Next</a>
	</div>
</div> 

<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
';

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $var . '.json', json_encode($page_posts));


    // TODO: + page -+- post_singles


    $page_post_singles['page'][0]['title'] = '{{ ' . $var . '.title.rendered }}';
    $page_post_singles['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_post_singles['page'][0]['prefix'] = $var . '_singles';
    $page_post_singles['page'][0]['parent'] = $var;
    $page_post_singles['page'][0]['img_bg'] = $background;
    $page_post_singles['page'][0]['lock'] = true;
    $page_post_singles['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_post_singles['page'][0]['menu'] = $file_name;
    $page_post_singles['page'][0]['menutype'] = 'sub-' . $_SESSION['PROJECT']['menu']['type'];
    $page_post_singles['page'][0]['scroll'] = true;
    $page_post_singles['page'][0]['content'] = '
    
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>

<div class="list card">

    <div class="item item-avatar">
        <img alt="" ng-src="{{' . $var . '.x_gravatar}}" />
        <h2 ng-bind-html="' . $var . '.title.rendered | to_trusted"></h2>
        <p> {{ ' . $var . '.x_date }}</p>		
	</div>

    <div class="item noborder">
        <img class="full-image" ng-src="{{' . $var . '.x_featured_media_large}}" zoom-view="true" zoom-src="{{' . $var . '.x_featured_media_original}}"/>
    </div>
    
    
   	<div class="item noborder" ng-if="' . $var . '.x_metadata.video">
		<div class="embed_container">
            <video controls="controls" ng-src="{{ ' . $var . '.x_metadata.video | trustUrl }}"></video>
        </div>
	</div>
    
	<div class="item noborder" ng-if="' . $var . '.x_metadata.youtube">
	   <div class="embed_container">
	       <iframe width="100%" ng-src="{{ \'https://www.youtube.com/embed/\' + ' . $var . '.x_metadata.youtube | trustUrl }}" frameborder="0" allowfullscreen>
		   </iframe>
	   </div>
	</div>
    
    
    <div class="item item-text-wrap noborder to_trusted {{ fontsize }}" 
        ng-bind-html="' . $var . '.content.rendered | strHTML">
    </div>
    
    <div class="item item-button" ng-if="' . $var . '.x_metadata.download">
        <a class="button button-colorful ink-dark" ng-click="openURL(' . $var . '.x_metadata.download)">Download File</a>
    </div>
    
    <div class="item noborder">
        by <strong ng-bind-html="' . $var . '.x_author | strHTML"></strong>
    </div>
    
</div>
    
    
<div class="list card">
	<div class="item tabs tabs-secondary tabs-icon-left tabs-stable">
        <a class="tab-item" run-social-sharing message="{{' . $var . '.link}}"><i class="icon ion-android-share-alt"></i> Share</a>
	    <a class="tab-item" ng-click="addToDbVirtual(' . $var . ');"><i class="icon ion-android-add-circle"></i> Add Bookmark</a> 
        <a class="tab-item" ng-href="#/' . $file_name . '/' . $var . '_bookmark"><i class="icon ion-ios-star"></i> Bookmarks</a>   
    </div>
</div>    
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $var . '_singles.json', json_encode($page_post_singles));

    $page_bookmark['page'][0]['title'] = 'Bookmarks of ' . ucwords($var);
    $page_bookmark['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_bookmark['page'][0]['prefix'] = $var . '_bookmark';
    $page_bookmark['page'][0]['parent'] = $var;
    $page_bookmark['page'][0]['img_bg'] = '';
    $page_bookmark['page'][0]['lock'] = true;
    $page_bookmark['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_bookmark['page'][0]['menu'] = $file_name;
    $page_bookmark['page'][0]['menutype'] = 'sub-' . $_SESSION['PROJECT']['menu']['type'];
    $page_bookmark['page'][0]['for'] = 'table-bookmarks';
    $page_bookmark['page'][0]['css'] = '.' . $var . '_bookmark{margin-top: 50%;}' . "\r\n" . '.' . $var . '_bookmark .icon:before{font-size:72px;font-weight: 600;}';
    $page_bookmark['page'][0]['content'] = '
    
<ion-list class="list" ng-if="' . $var . '_bookmark.length != 0">

	<ion-item class="item item-icon-left" type="item-avatar" ng-repeat="item in ' . $var . '_bookmark" ng-href="#/' . $file_name . '/' . $var . '_singles/{{ item.id }}">
		<i class="icon ion-ios-bookmarks-outline"></i>
		<h2 class="" ng-bind-html="item.title.rendered | to_trusted"></h2>
		<ion-option-button class="assertive-bg" ng-click="removeDbVirtual' . ucwords($var) . '(item.id)"><i class="icon ion-trash-a"></i></ion-option-button>
	</ion-item>					
    
    <ion-item class="item item-button">
	   <button class="button button-small button-calm" ng-click="clearDbVirtual' . ucwords($var) . '();"><i class="icon ion-ios-refresh-outline"></i> Clear</button>
	</ion-item>

</ion-list>

<div class="' . $var . '_bookmark padding text-center" ng-if="' . $var . '_bookmark.length == 0">
	<i class="icon ion-ios-bookmarks-outline"></i>
	<p>There are no items</p>
</div>

    
    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $var . '_bookmark.json', json_encode($page_bookmark));


    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=page_wordpress&target=' . $var);
    die();

}
$var = str2var($_GET['target']);
$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.page_wordpress.' . $var . '.json';


$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $_raw_data['page_builder']['page_wordpress'][$var];
}
if (!isset($raw_data['wp_url']))
{
    $raw_data['wp_url'] = 'http://your_wordpress.org/';
}

if (!isset($raw_data['cat_id']))
{
    $raw_data['cat_id'] = '1';
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


$json_categories = $raw_data['wp_url'] . '/wp-json/wp/v2/categories';

$form_input .= '<blockquote class="blockquote blockquote-warning">';
$form_input .= '<p>Your WordPress Plugin requires REST API v2 and REST-API Helper, then must be active in your WordPress Site.</p>';
$form_input .= '<ol>';
$form_input .= '<li>Download <a href="https://wordpress.org/plugins/rest-api/">WordPress REST API 2</a> and <a href="https://wordpress.org/plugins/rest-api-helper/">REST API Helper</a></li>';
$form_input .= '<li>Unzip and Upload `rest-api.xxx.zip` to the `/wp-content/plugins/rest-api` directory</li>';
$form_input .= '<li>Activate the plugin through the \'plugins\' menu in WordPress</li>';
$form_input .= '<li>Followed by unzip and Upload `rest-api-helper.xxx.zip` to the `/wp-content/plugins/rest-api-helper` directory</li>';
$form_input .= '<li>Then activate the plugin through the \'plugins\' menu </li>';
$form_input .= '<li>Now save and please fill in the fields below:</li>';
$form_input .= '</ol>';
$form_input .= '</blockquote>';
$form_input .= '<hr/>';

$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');
if ($_GET['target'] != '')
{
    $form_input .= '<h4>Settings</h4>';
    $form_input .= $bs->FormGroup('page_wordpress[wp_url]', 'horizontal', 'text', 'WordPress URL', 'http://demo.ihsana.net/wordpress/', '', null, '7', $raw_data['wp_url']);
    $form_input .= $bs->FormGroup('page_wordpress[cat_id]', 'horizontal', 'text', 'Category ID', '21', 'ID from Categories : <a target="_blank" href="' . $json_categories . '">' . $json_categories . '</a>', null, '7', $raw_data['cat_id']);
}

$footer .= '
<script type="text/javascript">
     $("#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_wordpress&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>