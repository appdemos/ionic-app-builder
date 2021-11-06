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

$table_posts = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/page_blogger/json/table.posts.json'), true);
$page_posts = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/page_blogger/json/page.posts.json'), true);
$page_post_singles = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/page_blogger/json/page.post_singles.json'), true);

$background = 'data/images/background/transparent.png';
$per_page = 12;
if (isset($_POST['page-builder']))
{
    if (isset($_POST['page_target']))
    {
        $postdata['prefix'] = $_POST['page_target'];
    }

    $var = $postdata['prefix'];

    // TODO: page builder settings
    $api_key = $blog_id = null;
    $json_save['page_builder']['page_blogger'][$var]['blog_id'] = $blog_id = htmlentities($_POST['page_blogger']['blog_id']);
    $json_save['page_builder']['page_blogger'][$var]['max_result'] = $max_result = (int)($_POST['page_blogger']['max_result']);
    $json_save['page_builder']['page_blogger'][$var]['api_key'] = $api_key = htmlentities($_POST['page_blogger']['api_key']);


    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.page_blogger.' . $var . '.json', json_encode($json_save));

    // TODO: create table
    unset($table_posts['tables']['post']['db_url_dinamic']);

    $_table_posts['tables'][$var] = $table_posts['tables']['post'];
    $_table_posts['tables'][$var]['db_url'] = 'https://www.googleapis.com/blogger/v3/blogs/' . $blog_id . '/posts?maxResults=' . (int)$max_result . '&key=' . $api_key;
    $_table_posts['tables'][$var]['db_url_single'] = '';

    $_table_posts['tables'][$var]['prefix'] = $var;
    $_table_posts['tables'][$var]['db_var'] = '.items';
    $_table_posts['tables'][$var]['parent'] = $var;
    $_table_posts['tables'][$var]['title'] = $var;
    $_table_posts['tables'][$var]['bookmarks'] = 'none';
    $_table_posts['tables'][$var]['version'] = 'Upd.' . date('ymdhi');
    $_table_posts['tables'][$var]['builder_link'] = @$_SERVER["HTTP_REFERER"];


    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.' . $var . '.json', json_encode($_table_posts));

    // TODO: + page -+- posts
    $old_page = json_decode(file_get_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $postdata['prefix'] . '.json'), true);

    $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_posts['page'][0]['prefix'] = $var;
    $page_posts['page'][0]['img_bg'] = $background;
    $page_posts['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
    //$page_posts['page'][0]['lock'] = true;
    $page_posts['page'][0]['menutype'] = $old_page['page'][0]['menutype'];
    $page_posts['page'][0]['title'] = htmlentities($var);
    $page_posts['page'][0]['table-code']['url_detail'] = '#/' . $file_name . '/' . $var . '_singles/{{item.id}}';
    $page_posts['page'][0]['table-code']['url_list'] = '#/' . $file_name . '/' . $var . 's';
    $page_posts['page'][0]['scroll'] = true;

    //$page_posts['page'][0]['query_value'] = $cat_id;
    $page_posts['page'][0]['menu'] = $file_name;
    $page_posts['page'][0]['js'] = '';
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
        <img alt="" ng-src="{{item.author.image.url}}" />
        <h2 ng-bind-html="item.title | to_trusted"></h2>
        <p>{{item.updated | strDate | date:\'fullDate\' }}</p>		
	</a>
 
	<div class="item item-body">
		<span class="to_trusted" ng-bind-html=" item.content | stripTags | limitTo:140 | strHTML "></span>...
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
    $page_post_singles['page'][0]['title'] = '{{ ' . $var . '.title }}';
    $page_post_singles['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_post_singles['page'][0]['prefix'] = $var . '_singles';
    $page_post_singles['page'][0]['parent'] = $var;
    $page_post_singles['page'][0]['img_bg'] = $background;
    //$page_post_singles['page'][0]['lock'] = true;
    $page_post_singles['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_post_singles['page'][0]['menu'] = $file_name;
    $page_post_singles['page'][0]['menutype'] = 'sub-' . $_SESSION['PROJECT']['menu']['type'];
    $page_post_singles['page'][0]['scroll'] = true;
    $page_post_singles['page'][0]['content'] = '
    
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>

<div class="list">

    <div class="item item-avatar">
        <img alt="" ng-src="{{' . $var . '.author.image.url}}" />
        <h2 ng-bind-html="' . $var . '.title | to_trusted"></h2>
        <p> {{ ' . $var . '.updated | strDate | date:\'fullDate\' }}</p>		
	</div>

    
    <div class="item item-text-wrap noborder to_trusted" ng-bind-html="' . $var . '.content | strHTML"></div>
    <div class="item noborder">
        by <strong ng-bind-html="' . $var . '.author.displayName | strHTML"></strong>
    </div>
    
</div>
 
<div class="list card">
	<div class="item tabs tabs-secondary tabs-icon-left tabs-stable">
        <a class="tab-item" run-social-sharing message="{{' . $var . '.url}}"><i class="icon ion-android-share-alt"></i> Share This Link</a>
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

    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=page_blogger&target=' . $var);
    die();

}
$var = str2var($_GET['target']);
$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.page_blogger.' . $var . '.json';


$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $_raw_data['page_builder']['page_blogger'][$var];
}
if (!isset($raw_data['blog_id']))
{
    $raw_data['blog_id'] = '';
}

if (!isset($raw_data['api_key']))
{
    $raw_data['api_key'] = '';
}

if (!isset($raw_data['max_result']))
{
    $raw_data['max_result'] = 50;
}

// TODO: page target
$project = new ImaProject();
$out_path = 'output/' . $file_name;
$preview_url = $out_path . '/www/#/' . $_SESSION['PROJECT']['app']['prefix'] . '/' . $var;


$option_page[] = array('label' => '< select page >', 'value' => '');
$z = 1;
foreach ($project->get_pages() as $page)
{
    $option_page[$z] = array('label' => 'Page `' . $page['prefix'] . '`  ' . $page['builder'] . '', 'value' => $page['prefix']);
    if ($_GET['target'] == $page['prefix'])
    {
        $option_page[$z]['active'] = true;
    }
    $z++;
}


$form_input .= '<blockquote class="blockquote blockquote-warning">';
$form_input .= '<ul>';
$form_input .= '<li>Fill in the form below</li>';
$form_input .= '<li>then Save</li>';
$form_input .= '</ul>';
$form_input .= '</blockquote>';
$form_input .= '<hr/>';

$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');
if ($_GET['target'] != '')
{
    $form_input .= '<h4>Settings</h4>';
    $form_input .= $bs->FormGroup('page_blogger[blog_id]', 'horizontal', 'text', 'Blog ID', '906156264990799016', 'Get from dashboard admin, example: https://www.blogger.com/blogger.g?blogID=<code>906156264990799016</code>', '', '7', $raw_data['blog_id']);
    $form_input .= $bs->FormGroup('page_blogger[max_result]', 'horizontal', 'number', 'Max Result', '100', 'Number of posts to display, maximum 500.', 'max="500" min="1"', '7', $raw_data['max_result']);
    $form_input .= $bs->FormGroup('page_blogger[api_key]', 'horizontal', 'text', 'API Key', 'AIzaSyBijrk74r7Yt41CNJfQJIpfh2fR89TObvk', 'Read <a target="_blank" href="https://console.developers.google.com/">Google Console</a>', '', '7', $raw_data['api_key']);
}

$footer .= '
<script type="text/javascript">
     $("#page_target").on("change",function(){
        window.location= "./?page=x-page-builder&prefix=page_blogger&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>