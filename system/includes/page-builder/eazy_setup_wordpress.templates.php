<?php

if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}

function fix_entities($str)
{
    return $str;
}
$per_page = 15;
$app_menus = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/menu.json'), true);
$table_categories = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/table.categories.json'), true);
$table_posts = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/table.posts.json'), true);
$table_users = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/table.users.json'), true);

$page_categories = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/page.categories.json'), true);
$page_posts = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/page.posts.json'), true);
$page_post_singles = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/page.post_singles.json'), true);
$page_post_bookmark = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/page.post_bookmark.json'), true);
$page_users = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/page.users.json'), true);

$page_dashboard = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/page.dashboard.json'), true);
$page_about_us = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/page.about_us.json'), true);
$page_faqs = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_wordpress/json/page.faqs.json'), true);


$app_json = file_get_contents(JSM_PATH . '/projects/' . $_SESSION['FILE_NAME'] . '/app.json');
$app_config = json_decode($app_json, true);
$background = 'data/images/background/bg7.jpg';

if (isset($_POST['page-builder']))
{
    if (file_exists('projects/' . $_SESSION['FILE_NAME'] . '/page.menu_1.json'))
    {
        @unlink('projects/' . $_SESSION['FILE_NAME'] . '/page.menu_1.json');
    }

    if (file_exists('projects/' . $_SESSION['FILE_NAME'] . '/page.menu_2.json'))
    {
        @unlink('projects/' . $_SESSION['FILE_NAME'] . '/page.menu_2.json');
    }
    if (isset($_POST['wordpress']['show_categories']))
    {
        $_POST['wordpress']['show_categories'] = true;
    } else
    {
        $_POST['wordpress']['show_categories'] = false;
    }

    if (isset($_POST['wordpress']['show_users']))
    {
        $_POST['wordpress']['show_users'] = true;
    } else
    {
        $_POST['wordpress']['show_users'] = false;
    }

    if (isset($_POST['wordpress']['show_feature_posts']))
    {
        $_POST['wordpress']['show_feature_posts'] = true;
    } else
    {
        $_POST['wordpress']['show_feature_posts'] = false;
    }
    if (isset($_POST['wordpress']['enable_google_api']))
    {
        $_POST['wordpress']['enable_google_api'] = true;
    } else
    {
        $_POST['wordpress']['enable_google_api'] = false;
    }

    $json_save['page_builder']['wordpress']['wp_url'] = fix_entities($_POST['wordpress']['wp_url']);
    $json_save['page_builder']['wordpress']['cat_id'] = fix_entities($_POST['wordpress']['cat_id']);
    $json_save['page_builder']['wordpress']['app_logo'] = fix_entities($_POST['wordpress']['app_logo']);

    $json_save['page_builder']['wordpress']['show_categories'] = fix_entities($_POST['wordpress']['show_categories']);
    $json_save['page_builder']['wordpress']['show_users'] = fix_entities($_POST['wordpress']['show_users']);
    $json_save['page_builder']['wordpress']['show_feature_posts'] = fix_entities($_POST['wordpress']['show_feature_posts']);
    $json_save['page_builder']['wordpress']['enable_google_api'] = fix_entities($_POST['wordpress']['enable_google_api']);
    $json_save['page_builder']['wordpress']['google_apikey'] = fix_entities($_POST['wordpress']['google_apikey']);

    $json_save['page_builder']['wordpress']['label_dashboard'] = fix_entities($_POST['wordpress']['label_dashboard']);
    $json_save['page_builder']['wordpress']['label_categories'] = fix_entities($_POST['wordpress']['label_categories']);
    $json_save['page_builder']['wordpress']['label_posts'] = fix_entities($_POST['wordpress']['label_posts']);
    $json_save['page_builder']['wordpress']['label_authors'] = fix_entities($_POST['wordpress']['label_authors']);
    $json_save['page_builder']['wordpress']['label_bookmarks'] = fix_entities($_POST['wordpress']['label_bookmarks']);
    $json_save['page_builder']['wordpress']['label_help'] = fix_entities($_POST['wordpress']['label_help']);
    $json_save['page_builder']['wordpress']['label_rates'] = fix_entities($_POST['wordpress']['label_rates']);
    $json_save['page_builder']['wordpress']['label_faqs'] = fix_entities($_POST['wordpress']['label_faqs']);
    $json_save['page_builder']['wordpress']['label_aboutus'] = fix_entities($_POST['wordpress']['label_aboutus']);
    $json_save['page_builder']['wordpress']['label_more'] = fix_entities($_POST['wordpress']['label_more']);
    $json_save['page_builder']['wordpress']['label_clear_cache'] = fix_entities($_POST['wordpress']['label_clear_cache']);
    $json_save['page_builder']['wordpress']['label_exit_app'] = fix_entities($_POST['wordpress']['label_exit_app']);
    $json_save['page_builder']['wordpress']['label_fontsize'] = fix_entities($_POST['wordpress']['label_fontsize']);
    $json_save['page_builder']['wordpress']['label_language'] = fix_entities($_POST['wordpress']['label_language']);

    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.wordpress.json', json_encode($json_save));

    $site = $json_save['page_builder']['wordpress']['wp_url'];
    $cat_id = $json_save['page_builder']['wordpress']['cat_id'];
    $app_logo = $json_save['page_builder']['wordpress']['app_logo'];

    $label_categories = $json_save['page_builder']['wordpress']['label_categories'];
    $label_posts = $json_save['page_builder']['wordpress']['label_posts'];
    $label_authors = $json_save['page_builder']['wordpress']['label_authors'];
    $label_bookmarks = $json_save['page_builder']['wordpress']['label_bookmarks'];
    $label_dashboard = $json_save['page_builder']['wordpress']['label_dashboard'];

    $label_help = $json_save['page_builder']['wordpress']['label_help'];
    $label_rates = $json_save['page_builder']['wordpress']['label_rates'];
    $label_faqs = $json_save['page_builder']['wordpress']['label_faqs'];
    $label_aboutus = $json_save['page_builder']['wordpress']['label_aboutus'];

    $label_clear_cache = $json_save['page_builder']['wordpress']['label_clear_cache'];
    $label_exit_app = $json_save['page_builder']['wordpress']['label_exit_app'];
    $label_more = $json_save['page_builder']['wordpress']['label_more'];
    $label_fontsize = $json_save['page_builder']['wordpress']['label_fontsize'];
    $label_language = $json_save['page_builder']['wordpress']['label_language'];

    $app_config['app']['index'] = 'dashboard';
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/app.json', json_encode($app_config));


    $popover_config['popover']['icon'] = 'ion-android-more-vertical';
    $popover_config['popover']['title'] = fix_entities($label_help);
    $c = 0;

    $popover_config['popover']['menu'][$c]['title'] = fix_entities($label_language);
    $popover_config['popover']['menu'][$c]['link'] = '';
    $popover_config['popover']['menu'][$c]['type'] = 'show-language-dialog';
    $c++;

    $popover_config['popover']['menu'][$c]['title'] = fix_entities($label_fontsize);
    $popover_config['popover']['menu'][$c]['link'] = '';
    $popover_config['popover']['menu'][$c]['type'] = 'show-fontsize-dialog';
    $c++;

    $popover_config['popover']['menu'][$c]['title'] = 'Administrator';
    $popover_config['popover']['menu'][$c]['link'] = $site . '/wp-admin/';
    $popover_config['popover']['menu'][$c]['type'] = 'link-webview';
    $c++;

    $popover_config['popover']['menu'][$c]['title'] = fix_entities($label_faqs);
    $popover_config['popover']['menu'][$c]['link'] = '#/' . $file_name . '/faqs';
    $popover_config['popover']['menu'][$c]['type'] = 'link';
    $c++;

    $popover_config['popover']['menu'][$c]['title'] = fix_entities($label_aboutus);
    $popover_config['popover']['menu'][$c]['link'] = '#/' . $file_name . '/about_us';
    $popover_config['popover']['menu'][$c]['type'] = 'link';

    $c++;
    $popover_config['popover']['menu'][$c]['title'] = fix_entities($label_clear_cache);
    $popover_config['popover']['menu'][$c]['link'] = '';
    $popover_config['popover']['menu'][$c]['type'] = 'app-clear-cache';

    $c++;
    $popover_config['popover']['menu'][$c]['title'] = fix_entities($label_exit_app);
    $popover_config['popover']['menu'][$c]['link'] = '';
    $popover_config['popover']['menu'][$c]['type'] = 'app-exit';

    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/popover.json', json_encode($popover_config));


    // TODO: page_builder about_us
    $json_about_us_save['page_builder']['about_us']['about_us']['title'] = fix_entities($_SESSION['PROJECT']['app']['name']);
    $json_about_us_save['page_builder']['about_us']['about_us']['prefix'] = 'about_us';
    $json_about_us_save['page_builder']['about_us']['about_us']['background'] = $background;
    $json_about_us_save['page_builder']['about_us']['about_us']['company'] = fix_entities($_SESSION['PROJECT']['app']['company']);
    $json_about_us_save['page_builder']['about_us']['about_us']['content'] = fix_entities($_SESSION['PROJECT']['app']['description']);
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.about_us.about_us.json', json_encode($json_about_us_save));

    $json_faqs_save['page_builder']['faqs']['faqs']['title'] = 'FAQs';
    $json_faqs_save['page_builder']['faqs']['faqs']['prefix'] = 'faqs';
    $json_faqs_save['page_builder']['faqs']['faqs']['background'] = $background;
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.faqs.faqs.json', json_encode($json_faqs_save));


    // menu
    $app_menus['menu']['title'] = fix_entities($_SESSION['PROJECT']['app']['name']);
    $app_menus['menu']['type'] = 'side_menus';
    $app_menus['menu']['logo'] = $app_logo;

    $app_menus['menu']['items'][0]['label'] = fix_entities($label_dashboard);
    $app_menus['menu']['items'][1]['label'] = fix_entities($label_categories);
    $app_menus['menu']['items'][2]['label'] = fix_entities($label_posts);
    $app_menus['menu']['items'][3]['label'] = fix_entities($label_authors);
    $app_menus['menu']['items'][4]['label'] = fix_entities($label_bookmarks);

    $app_menus['menu']['items'][5]['label'] = fix_entities($label_help);
    $app_menus['menu']['items'][6]['label'] = fix_entities($label_rates);
    $app_menus['menu']['items'][7]['label'] = fix_entities($label_faqs);
    $app_menus['menu']['items'][8]['label'] = fix_entities($label_aboutus);

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/menu.json', json_encode($app_menus));
    // TODO: table

    // TODO: + table categories
    $table_categories['tables']['categorie']['db_url'] = $site . '/wp-json/wp/v2/categories?per_page=100';
    $table_categories['tables']['categorie']['version'] = 'Upd.' . date('ymdhi');
    $table_categories['tables']['categorie']['builder_link'] = @$_SERVER["HTTP_REFERER"];
    
    // TODO: + table posts
    $table_posts['tables']['post']['db_url'] = $site . '/wp-json/wp/v2/posts/?categories=' . $cat_id . '&per_page=' . $per_page . '&page=1';
    $table_posts['tables']['post']['db_url_single'] = $site . '/wp-json/wp/v2/posts/';
    $table_posts['tables']['post']['version'] = 'Upd.' . date('ymdhi');
    $table_posts['tables']['post']['builder_link'] = @$_SERVER["HTTP_REFERER"];

    if ($json_save['page_builder']['wordpress']['enable_google_api'] == true)
    {
        $row = 17;
        $table_posts["tables"]['post']["cols"][$row]["label"] = "gmap";
        $table_posts["tables"]['post']["cols"][$row]["title"] = "x_metadata.gmap";
        $table_posts["tables"]['post']["cols"][$row]["type"] = "gmap";
        $table_posts["tables"]['post']["cols"][$row]["page_detail"] = "true";
        $table_posts["tables"]['post']["cols"][$row]["json"] = "true";
    }

    // TODO: + table users
    $table_users['tables']['user']['db_url'] = $site . '/wp-json/wp/v2/users/';
    $table_users['tables']['user']['version'] = 'Upd.' . date('ymdhi');
    $table_users['tables']['user']['builder_link'] = @$_SERVER["HTTP_REFERER"];

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.categorie.json', json_encode($table_categories));
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.post.json', json_encode($table_posts));
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.user.json', json_encode($table_users));

    // TODO: page

    // TODO: + page -+- categories

    $page_categories['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_categories['page'][0]['img_bg'] = $background;
    $page_categories['page'][0]['version'] = 'Upd.' . date('ymdhi');
    $page_categories['page'][0]['scroll'] = true;
    $page_categories['page'][0]['lock'] = true;
    $page_categories['page'][0]['cache'] = 'true';
    $page_categories['page'][0]['title'] = "{{ '" . fix_entities($label_categories) . "' | translate }}";
    $page_categories['page'][0]['menu'] = $file_name;
    $page_categories['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_categories['page'][0]['remove-has-header'] = false;
    $page_categories['page'][0]['title-tranparant'] = false;
    $page_categories['page'][0]['table-code']['url_detail'] = '#/' . $file_name . '/posts/{{item.id}}';
    $page_categories['page'][0]['table-code']['url_list'] = '#/' . $file_name . '/categories?per_page=100';

    $page_categories['page'][0]['content'] = '
<!-- code refresh -->
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>
<!-- ./code refresh -->


<!-- code search -->
<ion-list class="card list" >
	<div class="item item-input">
		<i class="icon ion-search placeholder-icon"></i>
		<input type="search" ng-model="filter_categories.name" placeholder="{{ \'Filter\' | translate }}" aria-label="filter categories" />
	</div>
</ion-list>
<!-- ./code search -->

<!-- code listing -->
<div class="list animate-none">
	<div class="padding" ng-repeat="item in categories | filter:filter_categories as results" ng-init="$last ? fireEvent() : null"><a class="item item-text-wrap item-colorful"  href="#/wp_eazy_setup/posts/{{item.id}}">
		<h3 class=""  ng-bind-html="item.name | to_trusted"></h3>
		<p>{{item.count}} pages</p>
		<div class="to_trusted {{ fontsize }}" ng-bind-html="item.description | limitTo:50 | to_trusted"></div>
	    <i class="icon ion-android-more-horizontal pull-right"></i>
	</a></div>
</div>
<!-- ./code listing -->

<!-- code infinite scroll -->
<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>
<!-- ./code infinite scroll -->

<!-- code search result not found -->
<ion-list ng-if="results.length == 0" class="list card">
	<div class="item"  >
		<p>No results found...!</p>
	</div>
</ion-list>
<!-- ./code search result not found -->


<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

';

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.categories.json', json_encode($page_categories));


    // TODO: + page -+- posts
    $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_posts['page'][0]['img_bg'] = $background;
    $page_posts['page'][0]['version'] = 'Upd.' . date('ymdhi');
    $page_posts['page'][0]['lock'] = true;
    $page_posts['page'][0]['scroll'] = true;
    $page_posts['page'][0]['remove-has-header'] = false;
    $page_posts['page'][0]['title-tranparant'] = false;
    $page_posts['page'][0]['title'] = '{{posts[0].x_categories}} ({{paging}})'; //fix_entities($label_posts) . ' - {{ paging }}';
    $page_posts['page'][0]['query_value'] = $cat_id;
    $page_posts['page'][0]['menu'] = $file_name;
    $page_posts['page'][0]['cache'] = 'true';
    $page_posts['page'][0]['table-code']['url_detail'] = '#/' . $file_name . '/post_singles/{{item.id}}';
    $page_posts['page'][0]['table-code']['url_list'] = '#/' . $file_name . '/posts';
    $page_posts['page'][0]['js'] = '
$ionicConfig.backButton.text("");

var UriListing = "' . $site . '/wp-json/wp/v2/posts?categories=' . $cat_id . '&per_page=' . $per_page . '";    
if(!$scope.paging){$scope.paging=1;}
$scope.updatePaging=function(ev){
    if(ev === true){
        $scope.paging++;
    }else{
        if($scope.paging===1){return null;}
        $scope.paging--;
    }
	
	$scope.fetchURL = UriListing + "&page="+$scope.paging;
	$scope.fetchURLp = UriListing +  "&page="+$scope.paging+"&callback=JSON_CALLBACK";
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
		<input type="search" ng-model="filter_posts.title.rendered" placeholder="Filter" aria-label="filter posts" />
	</div>
</ion-list>
<!-- ./code search -->

<!-- code listing -->
<div class="list card" ng-repeat="item in posts | filter:filter_posts as results" ng-init="$last ? fireEvent() : null" >
	
    <a class="item item-avatar" ng-href="#/' . $file_name . '/post_singles/{{item.id}}">
        <img alt="" ng-src="{{item.x_gravatar}}" />
        <h2 ng-bind-html="item.title.rendered | to_trusted"></h2>
        <p> {{item.x_date}}</p>		
	</a>
 
	<div class="item item-body">
        <img class="full-image" ng-if="item.x_featured_media_medium" alt="" ng-src="{{item.x_featured_media_medium}}" zoom-view="true" zoom-src="{{item.x_featured_media_original}}" />
		<p class="to_trusted {{ fontsize }}" ng-bind-html="item.excerpt.rendered | to_trusted"></p>            
	</div>
    
 
 
	<div class="item tabs tabs-secondary tabs-icon-left tabs-stable">
        <a class="tab-item assertive" href="#/' . $file_name . '/post_singles/{{item.id}}"><i class="icon ion-android-more-vertical"></i>' . $label_more . '</a>
        <!--a class="tab-item" run-social-sharing message="{{item.link}}"><i class="icon ion-android-share-alt"></i> Share</a-->
		<a class="tab-item" ng-click="addToDbVirtual(item);"><i class="icon ion-ios-star"></i> Bookmark</a>
	</div>
 
  
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
';


    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.posts.json', json_encode($page_posts));


    // TODO: + page -+- post_singles
    $page_post_singles['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_post_singles['page'][0]['img_bg'] = '';
    $page_post_singles['page'][0]['cache'] = 'true';
    //$background;
    $page_post_singles['page'][0]['lock'] = true;
    $page_post_singles['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_post_singles['page'][0]['menu'] = $file_name;
    $page_post_singles['page'][0]['version'] = 'Upd.' . date('ymdhi');

    $gmap_code = '';
    if ($json_save['page_builder']['wordpress']['enable_google_api'] == true)
    {
        $gmap_code = '
         <div class="item noborder"  ng-if="post.x_metadata.gmap">
				<div ng-if="mapEnable" class="embed_container" data-tap-disabled="true">
					<ng-map zoom="16" width="100%" center="{{post.x_metadata.gmap}}">
						<marker position="{{post.x_metadata.gmap}}" ></marker>
					</ng-map>
				</div>
          </div>';
    }

    $page_post_singles['page'][0]['content'] = '
    
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>

<div class="list card">

    <div class="item item-avatar">
        <img alt="" ng-src="{{post.x_gravatar}}" />
        <h2 ng-bind-html="post.title.rendered | to_trusted"></h2>
        <p> {{post.x_date}}</p>		
	</div>

	<div class="item item-text-wrap noborder to_trusted" ng-if="post.x_metadata.slider">
    	<div class="slideshow_container to_trusted" >
    			<ion-slides options="{slidesPerView:1}" slider="data.slider" class="slideshow">
    				<ion-slide-page class="slideshow-item" ng-repeat="slide_item in post.x_metadata.slider | strExplode:\'|\' track by $index" >
    					<div class="item-text-wrap" ng-bind-html="slide_item | to_trusted"></div>
    				</ion-slide-page>
    			</ion-slides>
    	</div>
	</div>

    <div class="item noborder" ng-if="post.x_featured_media_large">
        <img class="full-image" ng-src="{{post.x_featured_media_large}}" zoom-view="true" zoom-src="{{post.x_featured_media_original}}"/>
    </div>
  
  
	<div class="item noborder" ng-if="post.x_metadata.video">
		<div class="embed_container">
            <video controls="controls" ng-src="{{ post.x_metadata.video | trustUrl }}"></video>
        </div>
	</div>
    
    ' . $gmap_code . '
    
	<div class="item noborder" ng-if="post.x_metadata.youtube">
	   <div class="embed_container">
	       <iframe width="100%" ng-src="{{ \'https://www.youtube.com/embed/\' + post.x_metadata.youtube | trustUrl }}" frameborder="0" allowfullscreen>
		   </iframe>
	   </div>
	</div>
                    
        
    <div class="item item-text-wrap noborder to_trusted {{ fontsize }}" ng-bind-html="post.content.rendered | strHTML"></div>
        
    <div class="item item-button" ng-if="post.x_metadata.download">
        <a class="button button-colorful ink-dark" ng-click="openURL(post.x_metadata.download)">Download File</a>
    </div>
    
    
    <div class="item noborder">
        by <strong ng-bind-html="post.x_author | strHTML"></strong>
    </div>
    
</div>
    
    
<div class="list card">
	<div class="item tabs tabs-secondary tabs-icon-left tabs-stable">
        <a class="tab-item" run-social-sharing message="{{post.link}}"><i class="icon ion-android-share-alt"></i> Share</a>
		<a class="tab-item" ng-click="addToDbVirtual(post);"><i class="icon ion-ios-star"></i> Bookmark</a>
		
	</div>
</div>    
<br/><br/><br/>
    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.post_singles.json', json_encode($page_post_singles));


    // TODO: + page -+- bookmark
    $page_post_bookmark['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_post_bookmark['page'][0]['img_bg'] = 'data/images/background/transparent.png';
    $page_post_bookmark['page'][0]['lock'] = true;
    $page_post_bookmark['page'][0]['title'] = "{{ '" . fix_entities($label_bookmarks) . "' | translate }}";
    $page_post_bookmark['page'][0]['menu'] = $file_name;
    $page_post_bookmark['page'][0]['scroll'] = true;
    $page_post_bookmark['page'][0]['version'] = 'Upd.' . date('ymdhi');
    $page_post_bookmark['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_post_bookmark['page'][0]['content'] = '
    
<ion-list class="list" ng-if="post_bookmark.length != 0">
	<ion-item class="item item-icon-left" type="item-avatar" ng-repeat="item in post_bookmark" ng-href="#/' . $file_name . '/post_singles/{{ item.id }}">
		<i class="icon ion-ios-bookmarks-outline"></i>
		<h2 class="" ng-bind-html="item.title.rendered | to_trusted"></h2>
		<ion-option-button class="assertive-bg" ng-click="removeDbVirtualPost(item.id)"><i class="icon ion-trash-a"></i></ion-option-button>
	</ion-item>					
    
    <ion-item class="item item-button">
	   <button class="button button-small button-calm" ng-click="clearDbVirtualPost();"><i class="icon ion-ios-refresh-outline"></i> Clear</button>
	</ion-item>

</ion-list>


<!-- no bookmark -->
<div class="post_bookmark padding text-center" ng-if="post_bookmark.length == 0">
	<i class="icon ion-ios-bookmarks-outline"></i>
	<p>There are no items</p>
</div>
<!-- no bookmark -->
    
    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.post_bookmark.json', json_encode($page_post_bookmark));


    // TODO: + page -+- users
    $page_users['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_users['page'][0]['img_bg'] = $background;
    $page_users['page'][0]['lock'] = true;
    $page_users['page'][0]['version'] = 'Upd.' . date('ymdhi');
    $page_users['page'][0]['title'] = fix_entities($label_authors);
    $page_users['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_users['page'][0]['scroll'] = true;
    $page_users['page'][0]['menu'] = $file_name;
    $page_users['page'][0]['table-code']['url_detail'] = '#/' . $file_name . '/user_singles/{{item.id}}';
    $page_users['page'][0]['table-code']['url_list'] = '#/' . $file_name . '/users';
    $page_users['page'][0]['content'] = '
<!-- code refresh -->
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>
<!-- ./code refresh -->


<!-- code search -->
<ion-list class="card list" >
	<div class="item item-input">
		<i class="icon ion-search placeholder-icon"></i>
		<input type="search" ng-model="filter_users" placeholder="Filter" aria-label="filter users" />
	</div>
</ion-list>
<!-- ./code search -->


<!-- code listing -->
<div class="list animate-none">
	<div class="list card" ng-repeat="item in users | filter:filter_users as results" ng-init="$last ? fireEvent() : null" >
		<div class="item item-colorful"  ng-bind-html="item.name | to_trusted"></div>
		<div class="item item-thumbnail-left item-text-wrap">
			<img alt="" class="full-image" ng-src="{{item.avatar_urls[96]}}" />
			<div class="to_trusted" ng-bind-html="item.description | to_trusted"></div>
	   </div>
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
<br/>
<br/>

    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.users.json', json_encode($page_users));

    // TODO: + page -+- dashboard
    $html_categories = $html_users = $html_feature_posts = null;
    $show_categories = $json_save['page_builder']['wordpress']['show_categories'];
    $show_users = $json_save['page_builder']['wordpress']['show_users'];
    $show_feature_posts = $json_save['page_builder']['wordpress']['show_feature_posts'];

    if ($show_categories == true)
    {
        $html_categories = '
    
<!-- code categories hero -->
<a ng-href="#/' . $file_name . '/categories" class="tags-heroes-title light-bg dark">' . $label_categories . ' <i class="pull-right icon ion-chevron-right"></i></a>
<div class="light-bg" ng-controller="categoriesCtrl">
	<div class="tags-heroes-content list">
		
        <div class="row">
			<div ng-repeat="item in data_categories | limitTo:2:0" class="col" ng-class="$index ? \'col-33\':\'col-67\'" >
                <a href="#/' . $file_name . '/posts/{{item.id}}" class="button button-small button-full ink" ng-class="$index ? \'button-stable\' : \'button-assertive\'"><span ng-bind-html="item.name | strHTML"></span></a>
            </div>
		</div>
        
		<div class="row">
			<div ng-repeat="item in data_categories | limitTo:2:2" class="col" ng-class="$index ? \'col-66\':\'col-33\'" >
                <a href="#/' . $file_name . '/posts/{{item.id}}" class="button button-small button-full ink" ng-class="$index ? \'button-energized-900\' : \'button-stable\'" ><span ng-bind-html="item.name | strHTML"></span></a>
            </div>
		</div>
        
         
		<div class="row">
			<div ng-repeat="item in data_categories | limitTo:2:4" class="col" ng-class="$index ? \'col-33\':\'col-67\'" >
                <a href="#/' . $file_name . '/posts/{{item.id}}" class="button button-small button-full ink" ng-class="$index ? \'button-stable\' : \'button-royal-900\'"><span ng-bind-html="item.name | strHTML"></span></a>
            </div>
		</div>
      
        
	</div>
</div>
<!-- ./code categories hero -->

    ';
    }
    $html_users = null;
    if ($show_users == true)
    {
        $html_users = '
<!-- code user -->
<a ng-href="#/' . $file_name . '/users" class="slide-box-title calm-900-bg">' . fix_entities($label_authors) . ' <i class="pull-right icon ion-chevron-right"></i></a>
<div class="calm-900-bg slide-box-avatar" ng-controller="usersCtrl" >
    <ion-slides class="slide-box-avatar-content" ng-if="data_users" options="{slidesPerView:grid80}" slider="data.slider" show-pager="false">
    	<ion-slide-page class="slide-box-avatar-item" ng-repeat="item in data_users | limitTo : 16:0" >
    		<img class="avatar" ng-src="{{ item.avatar_urls[96] }}" alt="" />
    	</ion-slide-page>
    </ion-slides>
</div>
<!-- ./code user  -->

        ';
    }

    if ($show_feature_posts == true)
    {
        $html_feature_posts = '
<!-- code feature posts -->
<a ng-href="#/' . $file_name . '/posts/' . $cat_id . '" class="slide-box-title stable-bg dark">' . fix_entities($label_posts) . ' <i class="pull-right icon ion-chevron-right"></i></a>
<div class="stable-bg dark slide-box-thumbnail" ng-controller="postsCtrl" >
	<ion-slides class="slide-box-thumbnail-content" ng-if="data_posts" options="{slidesPerView:grid80,autoplay:10000,loop:1}" slider="data.slider" show-pager="false">
		<ion-slide-page class="slide-box-thumbnail-item" ng-repeat="item in data_posts | limitTo : 16:0" ng-if="item.x_featured_media_large!=null">
			<a ng-href="#/' . $file_name . '/post_singles/{{item.id}}"><img class="thumbnail" ng-src="{{item.x_featured_media}}" alt="" /></a>
			<p class="caption"><a ng-href="#/' . $file_name . '/post_singles/{{item.id}}" ng-bind-html="item.title.rendered | strHTML" ></a></p>
		</ion-slide-page>
	</ion-slides>
</div>
<!-- ./code feature posts -->
        
';
    }

    $page_dashboard['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_dashboard['page'][0]['img_bg'] = $background;
    $page_dashboard['page'][0]['lock'] = true;
    $page_dashboard['page'][0]['remove-has-header'] = false;
    $page_dashboard['page'][0]['title-tranparant'] = false;
    $page_dashboard['page'][0]['header-shrink'] = false;

    $page_dashboard['page'][0]['title'] = fix_entities($label_dashboard);
    $page_dashboard['page'][0]['menu'] = $file_name;
    $page_dashboard['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_dashboard['page'][0]['version'] = 'Upd.' . date('ymdhi');
    $page_dashboard['page'][0]['content'] = '
    
<!-- code slide hero -->
<div class="assertive-900-bg slide-box-hero" ng-controller="postsCtrl">
	<ion-slides class="slide-box-hero-content" options="{slidesPerView:1,autoplay:10000,loop:1}" slider="data.slider">
		<ion-slide-page class="slide-box-hero-item" ng-repeat="item in data_posts | limitTo : 10:0" ng-if="item.x_featured_media_large!=null" >
    		<div class="slide-box-hero-container" style="background: url(\'{{item.x_featured_media_large}}\') no-repeat center center;">
    			<div class="padding caption">
    				<h2 ng-bind-html="item.title.rendered | strHTML"></h2>
    				<a ng-href="#/' . $file_name . '/post_singles/{{item.id}}"> &gt;&gt;' . $label_more . '</a>
    			</div>
    		</div>
		</ion-slide-page>
	</ion-slides>
</div>
<!-- ./code slide hero -->

' . $html_categories . '

' . $html_feature_posts . '

 
<!-- code items -->
<div class="intro-box list light-bg dark" ng-controller="postsCtrl" >
    <div class="list" ng-repeat="item in data_posts | limitTo: 5:0">
        <div class="item item-colorful"  ng-bind-html="item.title.rendered | strHTML"></div>
        <div class="item item-text-wrap" ng-class="item.x_featured_media ? \'item-thumbnail-left\' : \'\'">
            <img alt="" class="full-image" ng-src="{{item.x_featured_media}}" ng-if="item.x_featured_media!=null" />
            <span ng-bind-html="item.excerpt.rendered | limitTo:140 | strHTML "></span>
        </div>
        <a class="item button button-clear colorful ink" href="#/' . $file_name . '/post_singles/{{item.id}}">' . $label_more . '</a>
    </div>
</div>
<!-- ./code items -->

' . $html_users . '

<div class="dark-bg stable">
       <div class="padding text-center">&copy ' . fix_entities($_SESSION['PROJECT']['app']['company']) . ', ' . date("Y") . '</div> 
</div>

<br/>
<br/>

    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.dashboard.json', json_encode($page_dashboard));

    // TODO: + page -+- about us
    $page_about_us['page'][0]['builder_link'] = './?page=x-page-builder&prefix=page_about_us&target=about_us';
    $page_about_us['page'][0]['img_bg'] = $background;
    $page_about_us['page'][0]['lock'] = true;
    $page_about_us['page'][0]['version'] = 'Upd.' . date('ymdhi');
    $page_about_us['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_about_us['page'][0]['css'] = '.about-us-box{background-color: rgba(255, 255, 255, 0.5);}' . "\r\n";
    $page_about_us['page'][0]['css'] .= '.about-us-box .item{border-color: rgba(255, 255, 255, 0.5);border-left:0;border-right:0;}';
    $page_about_us['page'][0]['content'] = '
<div class="padding scroll">

    <div class="padding about-us-box">
        <h2>' . fix_entities($_SESSION['PROJECT']['app']['name']) . '</h2>
        <div>
            ' . fix_entities($_SESSION['PROJECT']['app']['description']) . '
        </div>
    </div>
    <br/>

    <div class="disable-user-behavior about-us-box">
     
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
      
       <a class="item item-icon-left" ng-click="openURL(\'mail://' . strtolower($_SESSION["PROJECT"]["app"]["author_email"]) . '\')" >
        <i class="icon ion-android-mail royal"></i>
        For Business Cooperation
        <p>
            Email: ' . strtolower($_SESSION["PROJECT"]["app"]["author_email"]) . '
        </p>
      </a>
      
    </div>
    <br/>
</div>
<br/>
<br/>
<br/>
';


    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.about_us.json', json_encode($page_about_us));


    // TODO: + page -+- faqs
    $page_faqs['page'][0]['builder_link'] = './?page=x-page-builder&prefix=page_faqs&target=faqs';
    $page_faqs['page'][0]['lock'] = true;
    $page_faqs['page'][0]['version'] = 'Upd.' . date('ymdhi');
    $page_faqs['page'][0]['css'] = '';
    $page_faqs['page'][0]['img_bg'] = $background;
    $page_faqs['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_faqs['page'][0]['content'] = '

<ion-list class="card list">
	<div>
		<ion-item class="item item-colorful noborder" ng-click="toggleGroup(1)" ng-class="{active: isGroupShown(1)}" ><i class="icon" ng-class="isGroupShown(1) ? \'ion-minus\' : \'ion-plus\'"></i> <span>Eripuit adipisci vix ea?</span></ion-item>
		<ion-item class="item item-text-wrap" ng-show="isGroupShown(1)">Soluta pericula mel ad, sumo deterruisset consequuntur usu te</ion-item>
	</div>
	<div>
		<ion-item class="item item-colorful noborder" ng-click="toggleGroup(2)" ng-class="{active: isGroupShown(2)}" ><i class="icon" ng-class="isGroupShown(2) ? \'ion-minus\' : \'ion-plus\'"></i> <span>Prima torquatos comprehensam?</span></ion-item>
		<ion-item class="item item-text-wrap" ng-show="isGroupShown(2)">Illud diceret explicari nec ut, tation evertitur et eos</ion-item>
	</div>
	<div>
		<ion-item class="item item-colorful noborder" ng-click="toggleGroup(3)" ng-class="{active: isGroupShown(3)}" ><i class="icon" ng-class="isGroupShown(3) ? \'ion-minus\' : \'ion-plus\'"></i> <span>Alia clita dissentias sit cu?</span></ion-item>
		<ion-item class="item item-text-wrap" ng-show="isGroupShown(3)">Tation intellegebat vix an, nam ubique docendi ad. An integre convenire scribentur mel.</ion-item>
	</div>
</ion-list>
<br/>
<br/>
    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.faqs.json', json_encode($page_faqs));


    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=eazy_setup_wordpress');
    die();
}


$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.wordpress.json';
$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $_raw_data['page_builder']['wordpress'];
}


if (!isset($raw_data['wp_url']))
{
    $raw_data['wp_url'] = 'http://your_wordpress.org/';
}

if (!isset($raw_data['cat_id']))
{
    $raw_data['cat_id'] = '-1';
}
if (!isset($raw_data['label_dashboard']))
{
    $raw_data['label_dashboard'] = 'Beranda';
}
if (!isset($raw_data['label_categories']))
{
    $raw_data['label_categories'] = 'Kategori';
}

if (!isset($raw_data['label_posts']))
{
    $raw_data['label_posts'] = 'Berita';
}
if (!isset($raw_data['label_authors']))
{
    $raw_data['label_authors'] = 'Team';
}

if (!isset($raw_data['label_help']))
{
    $raw_data['label_help'] = 'Bantuan';
}

if (!isset($raw_data['label_rates']))
{
    $raw_data['label_rates'] = 'Beri rating';
}

if (!isset($raw_data['label_bookmarks']))
{
    $raw_data['label_bookmarks'] = 'Bookmarks';
}
if (!isset($raw_data['label_faqs']))
{
    $raw_data['label_faqs'] = 'Tanya Jawab';
}
if (!isset($raw_data['label_aboutus']))
{
    $raw_data['label_aboutus'] = 'Tentang Kami';
}
if (!isset($raw_data['app_logo']))
{
    $raw_data['app_logo'] = 'data/images/avatar/pic0.jpg';
}

if (!isset($raw_data['label_exit_app']))
{
    $raw_data['label_exit_app'] = 'Exit';
}
if (!isset($raw_data['label_clear_cache']))
{
    $raw_data['label_clear_cache'] = 'Bersihkan Cache';
}
if (!isset($raw_data['label_more']))
{
    $raw_data['label_more'] = 'More';
}
if (!isset($raw_data['label_fontsize']))
{
    $raw_data['label_fontsize'] = 'Ukuran Tulisan';
}

if (!isset($raw_data['label_language']))
{
    $raw_data['label_language'] = 'Bahasa';
}

if (!isset($raw_data['show_categories']))
{
    $raw_data['show_categories'] = false;
}
if (!isset($raw_data['show_users']))
{
    $raw_data['show_users'] = false;
}
if (!isset($raw_data['show_feature_posts']))
{
    $raw_data['show_feature_posts'] = false;
}
$checked_categories = null;
if ($raw_data['show_categories'] == true)
{
    $checked_categories = 'checked="checked"';
}

$checked_users = null;
if ($raw_data['show_users'] == true)
{
    $checked_users = 'checked="checked"';
}


$checked_enable_google_api = null;
if(!isset($raw_data['enable_google_api'])){
    $raw_data['enable_google_api'] = false;
}
if ($raw_data['enable_google_api'] == true)
{
    $checked_enable_google_api = 'checked="checked"';
}
if (!isset($raw_data['google_apikey']))
{
    $raw_data['google_apikey'] = '';
}

$checked_show_feature_posts = null;
if ($raw_data['show_feature_posts'] == true)
{
    $checked_show_feature_posts = 'checked="checked"';
}

$form_input .= '<blockquote class="blockquote blockquote-info">';
$form_input .= 'This is a feature that affects the page builder:
<table class="table table-striped">
<tr>
	<th>Type</th>
	<th>Name/Prefix</th>
</tr>
<tr>
	<td>(IMAB) Menus</td>
	<td>overwrites</td>
</tr>
<tr>
	<td>(IMAB) Popover</td>
	<td>overwrites</td>
</tr>
<tr>
	<td>(IMAB) Tables</td>
	<td>categorie, posts, user</td>
</tr>
<tr>
	<td>(IMAB) Pages</td>
	<td>dashboard, bookmarks, categories, users, posts, post_singles, post_bookmark, faqs, about_us</td>
</tr>
</table>';
$form_input .= '</blockquote>';

$form_input .= '<blockquote class="blockquote blockquote-warning">';
$form_input .= '<h4>How to use it?</h4>';
$form_input .= '<p>Your WordPress Plugin requires REST API v2 and REST-API Helper, then must be active in your WordPress Site.</p>';
$form_input .= '<ol>';
$form_input .= '<li>Download <a href="https://wordpress.org/plugins/rest-api/">WordPress REST API 2</a> and <a href="https://wordpress.org/plugins/rest-api-helper/">REST API Helper</a></li>';
$form_input .= '<li>Unzip and Upload `rest-api.xxx.zip` to the `/wp-content/plugins/rest-api` directory</li>';
$form_input .= '<li>Activate the plugin through the \'plugins\' menu in WordPress</li>';
$form_input .= '<li>Followed by unzip and Upload `rest-api-helper.xxx.zip` to the `/wp-content/plugins/rest-api-helper` directory</li>';
$form_input .= '<li>Then activate the plugin through the \'plugins\' menu </li>';
$form_input .= '<li>Now save and please fill in the fields below:</li>';
$form_input .= '<li>For editing page `About Us` and `FAQs`, you can using <code>Extra Menus</code> -&gt; <code>(IMAB) Page builder</code> -&gt; <a target="_blank" href="./?page=x-page-builder&prefix=page_about_us&target=about_us">About Us</a> and <a target="_blank" href="./?page=x-page-builder&prefix=page_faqs&target=faqs">FAQs</a></li>';
$form_input .= '<li>You need allow iframe when you need wp-admin in app webview (go to Helper Tools -> Faqs -> Blank page in Webview or iframe)</li>';
$form_input .= '<li>
You can use custom fields for sending value to app.
<table class="table table-striped">
<thead>
    <tr>
    	<th>For</th>
    	<th>Custom Field Name</th>
        <th>Example Value</th>
    </tr>
</thead>

<tbody>
<tr>
	<td>HTML5 Video</td>
    <td>video</td>
	<td>http://site.com/video.mp4</td>
</tr>
<tr>
	<td>HTML5 Audio</td>
    <td>audio</td>
	<td>http://site.com/audio.mp3</td>
</tr>
<tr>
	<td>Youtube</td>
    <td>youtube</td>
	<td>4HkG8z3sa-0</td>
</tr> 
<tr>
	<td>Download File</td>
    <td>download</td>
	<td>http://site.com/file.pdf</td>
</tr>
<tr>
	<td>Slider</td>
    <td>slider</td>
	<td>slide1|slide2|slide3</td>
</tr>
</tbody>
</table>
</li>';

$form_input .= '</ol>';
$form_input .= '</blockquote>';
$form_input .= '<hr/>';


$form_input .= '<h4>Settings</h4>';
$form_input .= $bs->FormGroup('wordpress[wp_url]', 'horizontal', 'text', 'WordPress URL', 'http://demo.ihsana.net/wordpress/', '', null, '7', $raw_data['wp_url']);
$json_categories = $raw_data['wp_url'] . '/wp-json/wp/v2/categories';

$form_input .= $bs->FormGroup('wordpress[cat_id]', 'horizontal', 'text', 'Default Category ID', '-1', 'ID from Categories : <a target="_blank" href="' . $json_categories . '">' . $json_categories . '</a>', null, '7', $raw_data['cat_id']);
$form_input .= '<h4>Dashboard</h4>';
$form_input .= $bs->FormGroup('wordpress[show_categories]', 'horizontal', 'checkbox', '', 'Show Categories', '', $checked_categories, '7', 'true');
$form_input .= $bs->FormGroup('wordpress[show_users]', 'horizontal', 'checkbox', '', 'Show Users', '', $checked_users, '7', 'true');
$form_input .= $bs->FormGroup('wordpress[show_feature_posts]', 'horizontal', 'checkbox', '', 'Show feature Posts', '', $checked_show_feature_posts, '7', 'true');
$form_input .= $bs->FormGroup('wordpress[enable_google_api]', 'horizontal', 'checkbox', '', 'Enable Google API (Required for GMAP)', '', $checked_enable_google_api, '7', 'true');
$form_input .= $bs->FormGroup('wordpress[google_apikey]', 'horizontal', 'text', '', 'AIzaSyAVLRZEgsoGb4OnId_aHxgm396LfeOA44k', '<a target="_target" href="https://developers.google.com/maps/documentation/javascript/get-api-key">Google API Key</a> required for GMAP', null, '5', $raw_data['google_apikey']);


$form_input .= '<h4>Images</h4>';
$form_input .= $bs->FormGroup('wordpress[app_logo]', 'horizontal', 'text', 'Logo', '', '', 'data-type="image-picker"', '7', $raw_data['app_logo']);
$form_input .= '<h4>Labels</h4>';
$form_input .= $bs->FormGroup('wordpress[label_dashboard]', 'horizontal', 'text', 'Dashboard', 'Dashboard', '', null, '5', $raw_data['label_dashboard']);
$form_input .= $bs->FormGroup('wordpress[label_categories]', 'horizontal', 'text', 'Categories', 'Categories', '', null, '6', $raw_data['label_categories']);
$form_input .= $bs->FormGroup('wordpress[label_posts]', 'horizontal', 'text', 'Posts', 'Articles', '', null, '5', $raw_data['label_posts']);
$form_input .= $bs->FormGroup('wordpress[label_authors]', 'horizontal', 'text', 'Users', 'Teams', '', null, '5', $raw_data['label_authors']);
$form_input .= $bs->FormGroup('wordpress[label_bookmarks]', 'horizontal', 'text', 'Bookmarks', 'Favorites', '', null, '6', $raw_data['label_bookmarks']);
$form_input .= $bs->FormGroup('wordpress[label_help]', 'horizontal', 'text', 'Help', 'Help', '', null, '4', $raw_data['label_help']);
$form_input .= $bs->FormGroup('wordpress[label_rates]', 'horizontal', 'text', 'Rate This App', 'Rate This App', '', null, '6', $raw_data['label_rates']);
$form_input .= $bs->FormGroup('wordpress[label_faqs]', 'horizontal', 'text', 'FAQs', 'FAQs', '', null, '4', $raw_data['label_faqs']);
$form_input .= $bs->FormGroup('wordpress[label_aboutus]', 'horizontal', 'text', 'About Us', 'About Us', '', null, '6', $raw_data['label_aboutus']);
$form_input .= $bs->FormGroup('wordpress[label_exit_app]', 'horizontal', 'text', 'Exit', 'Exit', '', null, '4', $raw_data['label_exit_app']);
$form_input .= $bs->FormGroup('wordpress[label_clear_cache]', 'horizontal', 'text', 'Clear Cache', 'Clear Cache', '', null, '6', $raw_data['label_clear_cache']);
$form_input .= $bs->FormGroup('wordpress[label_more]', 'horizontal', 'text', 'Read more', 'More', '', null, '6', $raw_data['label_more']);
$form_input .= $bs->FormGroup('wordpress[label_fontsize]', 'horizontal', 'text', 'Font Size', 'Font Size', '', null, '6', $raw_data['label_fontsize']);
$form_input .= $bs->FormGroup('wordpress[label_language]', 'horizontal', 'text', 'Language', 'Language', '', null, '6', $raw_data['label_language']);

?>