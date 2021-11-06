<?php

/**
 * @dataor Jasman
 * @copyright 2017
 */

//$require_target_page = true;
$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.data_user_offline.json';

if (isset($_POST['page-builder']))
{

    $json_save['page_builder']['data_user']['app_logo'] = htmlentities($_POST['data_user']['app_logo']);
    $app_logo = $json_save['page_builder']['data_user']['app_logo'];

    $json_save['page_builder']['data_user']['app_bg'] = htmlentities($_POST['data_user']['app_bg']);
    $app_bg = $json_save['page_builder']['data_user']['app_bg'];

    $json_save['page_builder']['data_user']['redirect'] = htmlentities($_POST['data_user']['redirect']);
    $redirect = $json_save['page_builder']['data_user']['redirect'];

    $background = $app_bg;

    if (isset($json_save['page_builder']['data_user']['background']))
    {
        $background = $json_save['page_builder']['data_user']['background'];
    }


    file_put_contents($pagebuilder_file, json_encode($json_save));


    // TODO: PAGE - FORM_USER
    $page_posts = null;
    $var = 'form_user';
    $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_posts['page'][0]['prefix'] = $var;
    $page_posts['page'][0]['img_bg'] = $background;
    $page_posts['page'][0]['parent'] = $var;
    $page_posts['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'] . '-custom';
    $page_posts['page'][0]['title'] = '';
    $page_posts['page'][0]['table-code']['url_detail'] = '';
    $page_posts['page'][0]['table-code']['url_list'] = '';
    $page_posts['page'][0]['scroll'] = true;
    $page_posts['page'][0]['menu'] = $_SESSION['FILE_NAME'];
    $page_posts['page'][0]['js'] = '';
    $page_posts['page'][0]['cache'] = 'false';
    $page_posts['page'][0]['last_edit_by'] = 'menu';
    $page_posts['page'][0]['css'] = null;
    $page_posts['page'][0]['for'] = '-';
    $page_posts['page'][0]['lock'] = true;
    $page_posts['page'][0]['header-shrink'] = true;
    $page_posts['page'][0]['button_up'] = 'none';
    $page_posts['page'][0]['remove-has-header'] = true;
    $page_posts['page'][0]['title-tranparant'] = true;
    $page_posts['page'][0]['css'] = '
.hero > .content h2{color: #fff;text-shadow: 0 1px 0px #000;}
.social-login { position: fixed; bottom: 0;}
.app-icon {background-color: #fff;background-size:cover !important; border-radius: 50%;height:80px;margin: 0 auto;width:80px;}
.item-md-radio { color: #fff; font-weight:600}  
    
    ';
    $page_posts['page'][0]['content'] = '
  
<div class="hero flat">
    <div class="content">
        <div class="app-icon" style="background: url(\'' . str_replace('output/' . $_SESSION["PROJECT"]['app']['prefix'] . '/www/', '', $app_logo) . '\') center;"></div>
        <h2>' . $_SESSION["PROJECT"]['app']['name'] . '</h2>
    </div>
</div>

<div class="list">

  <label class="item item-input item-md-label">
    <span class="input-label">Name</span>
    <input class="md-input" type="text" ng-model="data_user.fullname" ng-value="data_user.fullname">
  </label>
  
 
   <label class="item item-input item-md-label">
    <span class="input-label">Phone Number</span>
    <input class="md-input" type="text" ng-model="data_user.phone" ng-value="data_user.phone">
  </label>
  
  <label class="item item-input item-md-label">
    <span class="input-label">Address</span>
    <input class="md-input" type="text" ng-model="data_user.address" ng-value="data_user.address">
  </label>

</div>  
  
<div class="padding">
    <button ng-click="updateData(data_user)" class="button button-full button-assertive ink">Save</button>
</div>   

<br/>
<br/>
<br/>
    ';
    $page_posts['page'][0]['js'] = '
    
$scope.data_user = {};
$scope.show_form = true ;

$scope.$on("$ionicView.afterEnter", function (){  
       $ionicLoading.show();
       localforage.getItem("data_user_session", function(err, data_user_session){
            if(data_user_session === null){
			    $scope.show_form = true ;
		    }else{
		        $scope.show_form = false ; 
                var data_user = JSON.parse(data_user_session);
                $scope.data_user = data_user ;
                $rootScope.data_user = data_user ;
            }
       }).then(function(data_user_session){ 
     		$timeout(function() {
        			$ionicLoading.hide();
        	},500);
       }).catch(function(err){
	       $scope.show_form = true ;
      		$timeout(function() {
        		$ionicLoading.hide();
        	}, 500);
	   })
}); 

    
$scope.updateData = function(form){
    if(angular.isDefined(form)){
        $ionicLoading.show();
		var fullname = form.fullname || "demo";
		var address = form.address || "demo";
        localforage.setItem("data_user_session", JSON.stringify(form));
    	$timeout(function(){
    	   $ionicLoading.hide();   
           
           var confirmPopup = $ionicPopup.confirm({
             title: "Successfully",
             template: "The data has been successfully saved."
           });
    	
            confirmPopup.then(function(res) {
             if(res) {
               $state.go("' . $file_name . '.' . $redirect . '");
             } else {
                // cancel
             }
            });
   
           
        },500);  
    }
}    
';
    file_put_contents(JSM_PATH . '/projects/' . $_SESSION['FILE_NAME'] . '/page.' . $var . '.json', json_encode($page_posts));


    $custom_js['js']['directives'] = '
.run(function($ionicPlatform,$ionicLoading,$timeout,$rootScope,$state) {
	$ionicPlatform.ready(function() {
		$ionicLoading.show();
		localforage.getItem("data_user_session", function(err, data_user_session) {
			if (data_user_session === null) {
			     $state.go("' . $_SESSION['FILE_NAME'] . '.form_user");
			} else {
				var data_user = JSON.parse(data_user_session);
				$rootScope.data_user = data_user;                
			}
		}).then(function(data_user_session) {
            if (data_user_session === null) {
			     $state.go("' . $_SESSION['FILE_NAME'] . '.form_user");
			}else {
				var data_user = JSON.parse(data_user_session);
				$rootScope.data_user = data_user;                
			}
			$timeout(function() {
				$ionicLoading.hide();
			}, 500);
		}).
		catch (function(err) {
			$timeout(function() {
				$ionicLoading.hide();
                $state.go("' . $_SESSION['FILE_NAME'] . '.form_user");
			}, 500);
		});
                
	})
})';
    file_put_contents(JSM_PATH . '/projects/' . $_SESSION['FILE_NAME'] . '/js.json', json_encode($custom_js));


    $popover_files = JSM_PATH . '/projects/' . $_SESSION['FILE_NAME'] . '/popover.json';
    $popover_raw = json_decode(file_get_contents($popover_files), true);

    $id = count($popover_raw['popover']['menu']);
    $inlink = '#/' . $_SESSION['FILE_NAME'] . '/form_user';
    $add_to_popover = true;
    foreach ($popover_raw['popover']['menu'] as $menu)
    {
        if ($inlink == $menu['link'])
        {
            $add_to_popover = false;
        }
    }
    if ($add_to_popover == true)
    {
        $popover_raw['popover']['menu'][$id]['title'] = 'My Data';
        $popover_raw['popover']['menu'][$id]['link'] = $inlink;
        $popover_raw['popover']['menu'][$id]['type'] = 'link';
        file_put_contents($popover_files, json_encode($popover_raw));
    }

    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=data_user_for_offline_app');
    die();


}
$project = new ImaProject();


$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $_raw_data['page_builder']['data_user'];
}

if (!isset($raw_data['app_logo']))
{
    $raw_data['app_logo'] = 'data/images/avatar/pic6.jpg';
}
if (!isset($raw_data['app_bg']))
{
    $raw_data['app_bg'] = 'data/images/background/bg15.jpg';
}
if (!isset($raw_data['redirect']))
{
    $raw_data['redirect'] = 'dashboard';
}
$option_page[] = array('label' => '< select page >', 'value' => '');
$z = 1;
foreach ($project->get_pages() as $page)
{
    $option_page[$z] = array('label' => 'Page `' . $page['prefix'] . '` ' . $page['builder'] . '', 'value' => $page['prefix']);
    if ($raw_data['redirect'] == $page['prefix'])
    {
        $option_page[$z]['active'] = true;
    }
    $z++;
}

$form_input .= '<h4>General</h4>';
$form_input .= '<blockquote class="blockquote blockquote-warning">';
$form_input .= '<ul>';
$form_input .= '<li>This page builder will create a page named <code>form_user</code></li>';
$form_input .= '<li>and add a menu in the popover with the name: <code>my data</code></li>';
$form_input .= '<li>variable name: <code>$rootScope.data_user</code> or <code>{{ data_user | json }}</code></li>';
$form_input .= '</ul>';
$form_input .= '</blockquote>';
$form_input .= '<hr/>';

$form_input .= $bs->FormGroup('data_user[app_logo]', 'horizontal', 'text', 'Logo', '', '', 'data-type="image-picker"', '7', $raw_data['app_logo']);
$form_input .= $bs->FormGroup('data_user[app_bg]', 'horizontal', 'text', 'Background', '', '', 'data-type="image-picker"', '7', $raw_data['app_bg']);
$form_input .= $bs->FormGroup('data_user[redirect]', 'horizontal', 'select', 'Redirect to Page', $option_page, '', null, '4');

?>