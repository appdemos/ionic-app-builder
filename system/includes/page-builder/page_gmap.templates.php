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

$table_json = file_get_contents(JSM_PATH . '/system/includes/page-builder/page_gmap/json/table.gmap.json');
$_table_gmap = json_decode($table_json, true);

if (isset($_POST['page-builder']))
{
    if (isset($_POST['page_target']))
    {
        $_GET['target'] = $_POST['page_target'];
    }
    $postdata['prefix'] = str2var($_GET['target']);
    $postdata['title'] = $_POST['title'];
    $postdata['location'] = $_POST['location'];
    $postdata['api_key'] = $_POST['api_key'];
    $postdata['map_type'] = $_POST['map_type'];
    $postdata['background'] = $_POST['background'];
    $postdata['content'] = $_POST['content'];
    $json_save['page_builder']['gmap'][$postdata['prefix']] = $postdata;
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.gmap.' . $postdata['prefix'] . '.json', json_encode($json_save));


    $table_gmap['tables'][$postdata['prefix']] = $_table_gmap['tables']['gmap'];
    $table_gmap['tables'][$postdata['prefix']]['parent'] = $postdata['prefix'];
    $table_gmap['tables'][$postdata['prefix']]['title'] = str2var($postdata['prefix']);
    $table_gmap['tables'][$postdata['prefix']]['prefix'] = $postdata['prefix'];
    $table_gmap['tables'][$postdata['prefix']]['db_url'] = '';
    $table_gmap['tables'][$postdata['prefix']]['db_url_single'] = '';
    $table_gmap['tables'][$postdata['prefix']]['db_type'] = 'offline';
    $table_gmap['tables'][$postdata['prefix']]['db_var'] = '';
    $table_gmap['tables'][$postdata['prefix']]['option']['gmap']['center_map'] = $postdata['location'];
    $table_gmap['tables'][$postdata['prefix']]['option']['gmap']['api_key'] = $postdata['api_key'];
    $table_gmap['tables'][$postdata['prefix']]['builder_link'] = @$_SERVER["HTTP_REFERER"];

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.' . $postdata['prefix'] . '.json', json_encode($table_gmap));


    $data[0]['id'] = '0';
    $data[0]['title'] = $postdata['title'];
    $data[0]['location'] = $postdata['location'];
    $data[0]['description'] = $postdata['content'];
    file_put_contents(JSM_PATH . '/output/' . $file_name . '/www/data/tables/' . $postdata['prefix'] . '.json', json_encode($data));
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables/' . $postdata['prefix'] . '.json', json_encode($data));

    $_page = null;
    $page_content = '
<!-- code refresh -->
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>
<!-- ./code refresh -->


<ion-list class="padding gmapmarker-search" >
	<div class="item item-input">
		<i class="icon ion-search placeholder-icon"></i>
		<input type="search" ng-model="filter_' . $postdata['prefix'] . 's" placeholder="Search" aria-label="filter ' . $postdata['prefix'] . 's" />
	</div>
</ion-list>

<ng-map draggable="true" class="gmapmarker-map" map-type-id="' . $postdata['map_type'] . '" zoom="19" center="' . $postdata['location'] . '" width="100%" height="100%" default-style="false">
	<marker ng-repeat="item in data_' . $postdata['prefix'] . 's | filter:filter_' . $postdata['prefix'] . 's as results" on-click="openModal($event)" position="{{item.location}}" clickable="true" id="{{item.id}}" ></marker>
</ng-map>


<script id="' . $postdata['prefix'] . '-single.html" type="text/ng-template">
	<ion-modal-view>
		<ion-header-bar class="bar bar-header light bar-balanced-900">
			<div class="header-item title">{{ ' . $postdata['prefix'] . '.title | to_trusted }}</div>
			<div class="buttons buttons-right header-item"><span class="right-buttons"><button class="button button-icon button-clear ion-close ink-black" ng-click="modal.hide()"></button></span></div>
		</ion-header-bar>
		<ion-content>
		  <div class="item item-text-wrap noborder to_trusted" ng-bind-html="' . $postdata['prefix'] . '.description | strHTML"></div>
		</ion-content>
	</ion-modal-view>
</script>
    
    ';
    $page_js = '  
$scope.' . $postdata['prefix'] . ' = [];
$ionicModal.fromTemplateUrl("' . $postdata['prefix'] . '-single.html",{scope: $scope,animation:"slide-in-up"}).then(function(modal){
    $scope.modal = modal;
});
$scope.openModal = function() {
    $scope.' . $postdata['prefix'] . ' = [];
    var itemID = this.id;
	for (var i = 0; i < data_' . $postdata['prefix'] . 's.length; i++) {
		if((data_' . $postdata['prefix'] . 's[i].id ===  parseInt(itemID)) || (data_' . $postdata['prefix'] . 's[i].id === itemID.toString())) {
			$scope.' . $postdata['prefix'] . ' = data_' . $postdata['prefix'] . 's[i] ;
		}
	}    
    $scope.modal.show();
};
$scope.closeModal = function() {
    $scope.modal.hide();
};
$scope.$on("$destroy", function() {
    $scope.modal.remove();
});

$ionicConfig.backButton.text("");
';
    $old_page = json_decode(file_get_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . $postdata['prefix'] . '.json'), true);

    $_page['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $_page['page'][0]['prefix'] = str2var($_GET['target']);
    $_page['page'][0]['img_bg'] = $postdata['background'];
    $_page['page'][0]['lock'] = true;
    $_page['page'][0]['content'] = $page_content;
    $_page['page'][0]['for'] = 'table-list';
    $_page['page'][0]['title'] = htmlentities($postdata['title']);
    $_page['page'][0]['menu'] = $file_name;
    $_page['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
    $_page['page'][0]['menutype'] = $old_page['page'][0]['menutype'];
    $_page['page'][0]['js'] = $page_js;
    $_page['page'][0]['last_edit_by'] = 'page_builder';

    $_page['page'][0]['css'] = '
#page-' . str2var($_GET['target']) . ' .gmapmarker-map {position: absolute;width:100%;height: 100%;margin: 0;padding:0;z-index:-1}
#page-' . str2var($_GET['target']) . ' .gmapmarker-search {position: fixed;top:40px;z-index: 999;width:100%;background-color:transparent;opacity:1;}
#page-' . str2var($_GET['target']) . ' .item.item-input {background-color:#ffffff;opacity:0.8;}    
    ';

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.' . str2var($_GET['target']) . '.json', json_encode($_page));

    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=page_gmap&target=' . str2var($_GET['target']));
    die();


}

$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.gmap.' . str2var($_GET['target']) . '.json';
$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $get_raw_data['page_builder']['gmap'][str2var($_GET['target'])];
}

if (!isset($raw_data['title']))
{
    $raw_data['title'] = 'Office Addresses';
}
if (!isset($raw_data['location']))
{
    $raw_data['location'] = '48.85693,2.3412';
}
if (!isset($raw_data['content']))
{
    $raw_data['content'] = '
<p>
Praesent commodo cursus magna, vel scelerisque nisl consectetur et. 
Etiam porta sem malesuada magna mollis euismod. 
Cras mattis consectetur purus sit amet fermentum.
</p>
    
Phone: (000) 111 22 33<br/>
Fax: (000) 111 22 44<br/>
Email: one@yourwebsite.com<br/>
    
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
    $raw_data['background'] = 'data/images/background/bg11.jpg';
}
if (!isset($raw_data['api_key']))
{
    $raw_data['api_key'] = '';
}
if (!isset($raw_data['location']))
{
    $raw_data['location'] = '';
}
 

$_map_types[] = array('label' => 'ROADMAP (normal, default 2D map)', 'value' => 'ROADMAP');
$_map_types[] = array('label' => 'SATELLITE (photographic map)', 'value' => 'SATELLITE');
$_map_types[] = array('label' => 'HYBRID (photographic map + roads and city names)', 'value' => 'HYBRID');
$_map_types[] = array('label' => 'TERRAIN (map with mountains, rivers, etc.)', 'value' => 'TERRAIN');
if(!isset($raw_data['map_type'])){
    $raw_data['map_type'] = 'ROADMAP';
}
for ($i = 0; $i < count($_map_types); $i++)
{
    $map_types[$i] = $_map_types[$i];
    if ($raw_data['map_type'] == $map_types[$i]['value'])
    {
        $map_types[$i]['active'] = true;
    }
}
$form_input .= '<blockquote class="blockquote blockquote-danger">If you get error: <code>Oops! Something went wrong</code> check your Google API key.</blockquote>';
$form_input .= $bs->FormGroup('page_target', 'horizontal', 'select', 'Page Target', $option_page, 'Page will be overwritten', null, '4');

if ($_GET['target'] !== '')
{
    $form_input .= $bs->FormGroup('title', 'horizontal', 'text', 'Title', 'Office Addresses', 'your page title', 'required', '6', $raw_data['title']);
    $form_input .= $bs->FormGroup('location', 'horizontal', 'text', 'Location', '48.85693,2.3412', '', 'required', '5', $raw_data['location']);
    $form_input .= $bs->FormGroup('api_key', 'horizontal', 'text', 'Google API key', 'AIzaSyAJsxVTxzihZfoOVjwbFjyxMvNyk7uEw0s', 'Read <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">Google Documentation</a>', 'required', '5', $raw_data['api_key']);
    $form_input .= $bs->FormGroup('map_type', 'horizontal', 'select', 'MAP Type', $map_types, '', 'required', '5', $raw_data['map_type']);

    $form_input .= $bs->FormGroup('content', 'horizontal', 'textarea', 'Description', 'About your address', '', 'required', '8', $raw_data['content']);
    $form_input .= $bs->FormGroup('background', 'horizontal', 'text', 'Background', 'Background', '', 'data-type="image-picker" required', '8', $raw_data['background']);
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
        window.location= "./?page=x-page-builder&prefix=page_gmap&target=" +  $("#page_target").val() ;
        return false;
     });
</script>
';

?>