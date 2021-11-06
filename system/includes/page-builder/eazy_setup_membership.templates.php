<?php

/**
 * @author Jasman
 * @copyright 2017
 */


if(isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}

$app_menus = json_decode(file_get_contents(JSM_PATH.'/system/includes/page-builder/eazy_setup_membership/json/menu.json'),true);


$table_users_json = file_get_contents(JSM_PATH.'/system/includes/page-builder/eazy_setup_membership/json/table.users.json');
$table_users = json_decode($table_users_json,true);

$forms_user_json = file_get_contents(JSM_PATH.'/system/includes/page-builder/eazy_setup_membership/json/forms.user.json');
$forms_user = json_decode($forms_user_json,true);

$page_form_user_json = file_get_contents(JSM_PATH.'/system/includes/page-builder/eazy_setup_membership/json/page.form_user.json');
$page_form_user = json_decode($page_form_user_json,true);


$page_about_us = json_decode(file_get_contents(JSM_PATH.'/system/includes/page-builder/eazy_setup_wordpress/json/page.about_us.json'),true);
$page_faqs = json_decode(file_get_contents(JSM_PATH.'/system/includes/page-builder/eazy_setup_wordpress/json/page.faqs.json'),true);


$app_json = file_get_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/app.json');
$app_config = json_decode($app_json,true);

$background = "data/images/background/bg6.jpg";
if(isset($_POST['page-builder']))
{
    $json_save['page_builder']['membership']['site_url'] = htmlentities($_POST['membership']['site_url']);
    $site_url = $json_save['page_builder']['membership']['site_url'];

    $json_save['page_builder']['membership']['app_logo'] = htmlentities($_POST['membership']['app_logo']);
    $app_logo = $json_save['page_builder']['membership']['app_logo'];

    $json_save['page_builder']['membership']['app_bg'] = htmlentities($_POST['membership']['app_bg']);
    $app_bg = $json_save['page_builder']['membership']['app_bg'];


    $json_save['page_builder']['membership']['app_code'] = htmlentities($_POST['membership']['app_code']);
    $app_code = $json_save['page_builder']['membership']['app_code'];

    $json_save['page_builder']['membership']['app_services'] = htmlentities($_POST['membership']['app_services']);
    $app_services = $json_save['page_builder']['membership']['app_services'];

    $background = $app_bg;

    if(isset($json_save['page_builder']['membership']['background']))
    {
        $background = $json_save['page_builder']['membership']['background'];
    }


    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/page_builder.membership.json',json_encode($json_save));


    // TODO: page_builder about_us
    $json_about_us_save['page_builder']['about_us']['about_us']['title'] = htmlentities($_SESSION['PROJECT']['app']['name']);
    $json_about_us_save['page_builder']['about_us']['about_us']['prefix'] = 'about_us';
    $json_about_us_save['page_builder']['about_us']['about_us']['background'] = $background;
    $json_about_us_save['page_builder']['about_us']['about_us']['company'] = htmlentities($_SESSION['PROJECT']['app']['company']);
    $json_about_us_save['page_builder']['about_us']['about_us']['content'] = htmlentities($_SESSION['PROJECT']['app']['description']);
    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/page_builder.about_us.about_us.json',json_encode($json_about_us_save));

    $json_faqs_save['page_builder']['faqs']['faqs']['title'] = 'FAQs';
    $json_faqs_save['page_builder']['faqs']['faqs']['prefix'] = 'faqs';
    $json_faqs_save['page_builder']['faqs']['faqs']['background'] = $background;
    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/page_builder.faqs.faqs.json',json_encode($json_faqs_save));


    $app_menus['menu']['title'] = htmlentities($_SESSION['PROJECT']['app']['name']);
    $app_menus['menu']['type'] = 'side_menus';
    $app_menus['menu']['logo'] = $app_logo;


    $app_config['app']['index'] = 'profile';
    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/app.json',json_encode($app_config));

    $table_users['tables']['user']['title'] = 'user';
    $table_users['tables']['user']['prefix'] = 'user';
    $table_users['tables']['user']['db_url'] = '';
    $table_users['tables']['user']['db_url_single'] = '';
    $table_users['tables']['user']['db_type'] = 'offline';
    $table_users['tables']['user']['db_var'] = '';
    $table_users['tables']['user']['builder_link'] = @$_SERVER["HTTP_REFERER"];

    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/tables.user.json',json_encode($table_users));

    $forms_user['forms']['user']['msg_ok'] = 'You have successfully signed up, please login!';
    $forms_user['forms']['user']['action'] = $site_url.'?json=submit&form=user';
    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/forms.user.json',json_encode($forms_user));

    // TODO: + user --+-- form register
    $page_form_user = null;
    $page_form_user['page'][0]['prefix'] = 'form_user';
    $page_form_user['page'][0]['title'] = 'Register';
    $page_form_user['page'][0]['lock'] = true;
    $page_form_user['page'][0]['menu'] = $file_name;
    $page_form_user['page'][0]['css'] = null;
    $page_form_user['page'][0]['css'] .= '.hero > .content h2{color: #fff;text-shadow: 0 1px 0px #000;}'."\r\n";
    $page_form_user['page'][0]['css'] .= '.social-login { position: fixed; bottom: 0;}'."\r\n";
    $page_form_user['page'][0]['css'] .= '.app-icon {background-color: #fff;background-size:cover !important; border-radius: 50%;height:80px;margin: 0 auto;width:80px;}'."\r\n";
    $page_form_user['page'][0]['css'] .= '.item-md-radio { color: #fff; font-weight:600}'."\r\n";
    $page_form_user['page'][0]['for'] = 'forms';
    $page_form_user['page'][0]['header-shrink'] = true;
    $page_form_user['page'][0]['button_up'] = 'none';
    $page_form_user['page'][0]['remove-has-header'] = true;
    $page_form_user['page'][0]['title-tranparant'] = true;
    $page_form_user['page'][0]['last_edit_by'] = 'page_builder';
    $page_form_user['page'][0]['parent'] = '';
    $page_form_user['page'][0]['menutype'] = 'side_menus-custom';
    $page_form_user['page'][0]['menu'] = '';
    $page_form_user['page'][0]['priority'] = 'warning';
    $page_form_user['page'][0]['class'] = 'has-header';
    $page_form_user['page'][0]['bg_image'] = true;
    $page_form_user['page'][0]['img_bg'] = $background;
    $page_form_user['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_form_user['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_form_user['page'][0]['content'] = '

<div class="hero no-header flat">
    <div class="content">
        <div class="app-icon" style="background: url(\''.str_replace('output/'.$_SESSION["PROJECT"]['app']['prefix'].'/www/','',$app_logo).'\') center;"></div>
        <h2>'.$_SESSION["PROJECT"]['app']['name'].'</h2>
    </div>
</div>

    
<div class="list">
    <ion-md-input placeholder="Full Name" highlight-color="assertive" type="text" ng-model="form_user.fullname"></ion-md-input>
    <ion-md-input placeholder="User Name" highlight-color="assertive" type="text" ng-model="form_user.uname"></ion-md-input>
    <ion-md-input placeholder="Password" highlight-color="assertive" type="password" ng-model="form_user.pwd"></ion-md-input>
    <ion-md-input placeholder="Address" highlight-color="assertive" type="text" ng-model="form_user.address"></ion-md-input>
    
    <ion-radio class="item-md-radio" ng-model="form_user.type_ic" ng-value="\'Resident Identity Card\'">Resident Identity Card</ion-radio>
    <ion-radio class="item-md-radio" ng-model="form_user.type_ic" ng-value="\'Driving Licence\'">Driving Licence</ion-radio>
    <ion-radio class="item-md-radio" ng-model="form_user.type_ic" ng-value="\'Other\'">Other</ion-radio>
    
    <ion-md-input placeholder="No. Identity Card" highlight-color="assertive" type="text" ng-model="form_user.no_ic"></ion-md-input>
    <ion-md-input placeholder="Phone Number" highlight-color="assertive" type="text" ng-model="form_user.phone"></ion-md-input>
    <ion-md-input placeholder="Email Address" highlight-color="assertive" type="email" ng-model="form_user.email"></ion-md-input>
</div>  
  
<div class="padding">
    <button ng-click="submitUser()" class="button button-full button-assertive ink">Sign Up</button>
    <a class="button button-full button-calm ink" ui-sref="'.$file_name.'.form_login">Sign In Here</a>
</div>
';
    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/page.form_user.json',json_encode($page_form_user));

    // TODO: + user --+-- form login
    $page_form_user = null;
    $page_form_user['page'][0]['prefix'] = 'form_login';
    $page_form_user['page'][0]['title'] = 'Login';
    $page_form_user['page'][0]['menu'] = $file_name;
    $page_form_user['page'][0]['lock'] = true;
    $page_form_user['page'][0]['css'] = null;
    $page_form_user['page'][0]['css'] .= '.hero > .content h2{color: #fff;text-shadow: 0 1px 0px #000;}'."\r\n";
    $page_form_user['page'][0]['css'] .= '.social-login { position: fixed; bottom: 0;}'."\r\n";
    $page_form_user['page'][0]['css'] .= '.app-icon {background-color: #fff;background-size:cover !important; border-radius: 50%;height:80px;margin: 0 auto;width:80px;}'."\r\n";
    $page_form_user['page'][0]['css'] .= '.item-md-radio { color: #fff; font-weight:600}'."\r\n";
    $page_form_user['page'][0]['for'] = 'forms';
    $page_form_user['page'][0]['header-shrink'] = true;
    $page_form_user['page'][0]['button_up'] = 'none';
    $page_form_user['page'][0]['remove-has-header'] = true;
    $page_form_user['page'][0]['title-tranparant'] = true;
    $page_form_user['page'][0]['last_edit_by'] = 'page_builder';
    $page_form_user['page'][0]['parent'] = '';
    $page_form_user['page'][0]['menutype'] = 'side_menus-custom';
    $page_form_user['page'][0]['menu'] = '';
    $page_form_user['page'][0]['priority'] = 'warning';
    $page_form_user['page'][0]['class'] = 'has-header';
    $page_form_user['page'][0]['bg_image'] = true;
    $page_form_user['page'][0]['img_bg'] = $background;
    $page_form_user['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_form_user['page'][0]['js'] = '
 
$scope.submitUser = function(form){
    
    $ionicLoading.show({
		template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\'
	});
    
    if( angular.isDefined(form)){
        
		var uname = form.uname || "demo";
		var pwd = form.pwd || "demo";
		var http_value = "Basic " + base64.encode(uname + ":" + pwd);
		$http.defaults.headers.common["X-Authorization"] = http_value;
        $http.get("'.$site_url.'?json=auth").then(function(resp){
            if(resp.data.data.status == 401){
    			var alertPopup = $ionicPopup.alert({
    				title: resp.data.title,
    				template: resp.data.message,
    			});
            }else{
           	    $ionicHistory.nextViewOptions({
            		disableAnimate: true,
            		disableBack: true
            	});
                localforage.setItem("ima_session", JSON.stringify(http_value));
                $state.go("'.$file_name.'.profile");
            }
        },function(resp){   
            
			$timeout(function() {
				$ionicLoading.hide();
                
        		var alertPopup = $ionicPopup.alert({
        			title: "Ops, error!",
        			template: "Problem with crossdomain or Invalid JSON URL.",
        		});
                            
			}, 500); 
                        
            
        }).finally(function(){   
            
			$timeout(function() {
				$ionicLoading.hide();
			}, 500);          
              
        });    
    }
    
	$timeout(function() {
	   $ionicLoading.hide();
	}, 1000);  
    
}    
';
    $page_form_user['page'][0]['content'] = '

<div class="hero no-header flat" >
    <div class="content">
        <div class="app-icon" style="background: url(\''.str_replace('output/'.$_SESSION["PROJECT"]['app']['prefix'].'/www/','',$app_logo).'\') center;"></div>
        <h2>'.$_SESSION["PROJECT"]['app']['name'].'</h2>
    </div>
</div>

<div class="list">
    <ion-md-input autocomplete="off" placeholder="Username" highlight-color="balanced" type="text" ng-model="form_user.uname"></ion-md-input>
    <ion-md-input autocomplete="off" placeholder="Password" highlight-color="energized" type="password" ng-model="form_user.pwd"></ion-md-input>
</div>

<div class="padding">
    <button ng-click="submitUser(form_user)" class="button button-full button-assertive ink">Sign In</button>
    <a ui-sref="'.$file_name.'.form_user" class="button button-full button-calm ink">Sign Up Here</a>
</div>

';
    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/page.form_login.json',json_encode($page_form_user));

    // TODO: + user --+-- form profiles
    $page_profile = null;
    $page_profile['page'][0]['prefix'] = 'profile';
    $page_profile['page'][0]['title'] = 'Profile';
    $page_profile['page'][0]['menu'] = $file_name;
    $page_profile['page'][0]['css'] = null;
    $page_profile['page'][0]['for'] = '-';
    $page_profile['page'][0]['lock'] = true;
    $page_profile['page'][0]['header-shrink'] = false;
    $page_profile['page'][0]['button_up'] = 'none';
    $page_profile['page'][0]['remove-has-header'] = false;
    $page_profile['page'][0]['title-tranparant'] = false;
    $page_profile['page'][0]['last_edit_by'] = 'page_builder';
    $page_profile['page'][0]['parent'] = '';
    $page_profile['page'][0]['menutype'] = 'side_menus-custom';
    $page_profile['page'][0]['menu'] = '';
    $page_profile['page'][0]['priority'] = 'warning';
    $page_profile['page'][0]['class'] = 'has-header';
    $page_profile['page'][0]['bg_image'] = false;
    $page_profile['page'][0]['img_bg'] = $background;
    $page_profile['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_profile['page'][0]['js'] = '

 
$scope.current_user = {} ;
$scope.$on("$ionicView.afterEnter", function (){  
    
   $ionicLoading.show();
   
   localforage.getItem("ima_session", function(err, ima_session){
		
        if(ima_session === null){
			// failed
		}else{
		    try {
        	   var _session = JSON.parse(ima_session);
        	   $http.defaults.headers.common["X-Authorization"] = _session;			
			}catch(e){
		      console.log(e);
            }
		}
        
	}).then(function(ima_session){
	   
		    try {
        	   var _session = JSON.parse(ima_session);
        	   $http.defaults.headers.common["X-Authorization"] = _session;			
			}catch(e){
		       console.log(e);
            }            
            
            $http.get("'.$site_url.'?json=me").then(function(resp){
                
                if(resp.data.data.status == 401){
               	    $ionicHistory.nextViewOptions({
                		disableAnimate: true,
                		disableBack: true
                	});
        			$state.go("'.$file_name.'.form_login");
                }else{
                    $scope.current_user = resp.data.me ;
                    $rootScope.current_user = resp.data.me ;
                }
                       
                
            },function(resp){   
                $state.go("'.$file_name.'.form_login"); 
            }).finally(function(){   
        		$timeout(function() {
        			$ionicLoading.hide();
        		}, 500);            
            });              
            
    // localforage error        
	}).catch(function(err){
	       
	})
});   


$rootScope.logoutUser = function(){
    delete $rootScope.current_user ;
    delete $scope.current_user ;
    
    var http_value = {};
    
    localforage.setItem("ima_session", JSON.stringify(http_value));    
    $http.defaults.headers.common["X-Authorization"] = "null" ;
    
      $ionicHistory.nextViewOptions({
        disableAnimate: true,
        disableBack: true
    });
    
    $state.go("'.$file_name.'.form_login");
}
    
$rootScope.updateUser = function(){
  		
    // animation loading 
    $ionicLoading.show({
    	template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\'
    });
	
		var $messages, $title = null;
		$http({
				method:"POST",
				url: "'.$site_url.'?json=submit&form=me",
				data: $httpParamSerializer($scope.current_user),   
				headers: {"Content-Type":"application/x-www-form-urlencoded"}  
			})
			.then(function(response) {
				$messages = response.data.message;
				$title = response.data.title;
			},function(response){
				$messages = response.statusText;
				$title = response.status;
			}).finally(function(){
				// event done, hidden animation loading
				$timeout(function() {
					$ionicLoading.hide();
					if($messages !== null){
						// message
    					var alertPopup = $ionicPopup.alert({
    						title: $title,
    						template: $messages,
    					});
					}
			     }, 500);
		  });
}
 
    ';

    $page_profile['page'][0]['content'] = '
    
<div class="card list">
 
  <label class="item item-input item-stacked-label">
    <span class="input-label">Full Name</span>
    <input type="text" ng-model="current_user.fullname" ng-value="current_user.fullname">
  </label>
  
  <label class="item item-input item-stacked-label">
    <span class="input-label">Address</span>
    <input type="text" ng-model="current_user.address" ng-value="current_user.address">
  </label>

  <label class="item item-input item-stacked-label">
    <span class="input-label">Email</span>
    <input type="email" ng-model="current_user.email" ng-value="current_user.email">
  </label>
  
  <label class="item item-input item-stacked-label">
    <span class="input-label">Phone</span>
    <input type="text" ng-model="current_user.phone" ng-value="current_user.phone">
  </label>
  
  <label class="item item-input item-stacked-label">
    <span class="input-label">Type Identity Card</span>
    <input type="text" ng-model="current_user.type_ic" ng-value="current_user.type_ic">
  </label>
    
  <label class="item item-input item-stacked-label">
    <span class="input-label">No. Identity Card</span>
    <input type="text" ng-model="current_user.no_ic" ng-value="current_user.no_ic">
  </label>

    <div class="padding">
        <button ng-click="updateUser()" class="button button-small button-assertive ink">Update</button>
        <button ng-click="logoutUser()" class="button button-small button-calm ink">Logout</button>
    </div>
        
</div>

    ';
    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/page.profile.json',json_encode($page_profile));


    // TODO: + page -+- faqs
    $page_faqs['page'][0]['lock'] = true;
    $page_faqs['page'][0]['css'] = '';
    $page_faqs['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_faqs['page'][0]['img_bg'] = $background;

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
    
    ';
    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/page.faqs.json',json_encode($page_faqs));


    // TODO: + page -+- about-us
    $page_about_us['page'][0]['img_bg'] = $background;
    $page_about_us['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_about_us['page'][0]['lock'] = true;
    $page_about_us['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_about_us['page'][0]['css'] = '.about-us-box{background-color: rgba(255, 255, 255, 0.5);}'."\r\n";
    $page_about_us['page'][0]['css'] .= '.about-us-box .item{border-color: rgba(255, 255, 255, 0.5);border-left:0;border-right:0;}';
    $page_about_us['page'][0]['content'] = '
<div class="padding scroll">

    <div class="padding about-us-box">
        <h2>'.htmlentities($_SESSION['PROJECT']['app']['name']).'</h2>
        <div>
            '.htmlentities($_SESSION['PROJECT']['app']['description']).'
        </div>
    </div>
    <br/>

    <div class="disable-user-behavior about-us-box">
     
      <a class="item item-icon-left" ng-click="openURL(\''.strtolower($_SESSION["PROJECT"]["app"]["fb"]).'\')" >
        <i class="positive icon ion-social-facebook"></i>
        Like Us on Facebook
      </a>
      
      <a class="item item-icon-left" ng-click="openURL(\''.strtolower($_SESSION["PROJECT"]["app"]["gplus"]).'\')" >
        <i class="assertive icon ion-social-googleplus"></i>
        Join us on Google+
      </a>
      
      <a class="item item-icon-left" ng-click="openURL(\''.strtolower($_SESSION["PROJECT"]["app"]["twitter"]).'\')" >
        <i class="calm icon ion-social-twitter"></i>
       Follow me on Twitter
      </a>
      
       <a class="item item-icon-left" ng-click="openURL(\'mail://'.strtolower($_SESSION["PROJECT"]["app"]["author_email"]).'\')" >
        <i class="icon ion-android-mail royal"></i>
        For Business Cooperation
        <p>
            Email: '.strtolower($_SESSION["PROJECT"]["app"]["author_email"]).'
        </p>
      </a>
      
    </div>
    <br/>
</div>
<br/><br/><br/>
';


    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/page.about_us.json',json_encode($page_about_us));

    switch($app_code)
    {
        case 'none':

            // TODO: + menu -
            $app_menus['menu']['items'] = array();
            $app_menus['menu']['items'][0]['label'] = 'Membership';
            $app_menus['menu']['items'][1]['label'] = 'Menu 1';
            $app_menus['menu']['items'][2]['label'] = 'Menu 2';
            $app_menus['menu']['items'][3]['label'] = 'Account';
            $app_menus['menu']['items'][4]['label'] = 'Profile';
            $app_menus['menu']['items'][5]['label'] = 'Help';
            $app_menus['menu']['items'][6]['label'] = 'Rate This App';
            $app_menus['menu']['items'][7]['label'] = 'Faqs';
            $app_menus['menu']['items'][8]['label'] = 'About Us';

            $app_menus['menu']['items'][0]['var'] = 'membership';
            $app_menus['menu']['items'][1]['var'] = 'menu_1';
            $app_menus['menu']['items'][2]['var'] = 'menu_2';
            $app_menus['menu']['items'][3]['var'] = 'account';
            $app_menus['menu']['items'][4]['var'] = 'profile';
            $app_menus['menu']['items'][5]['var'] = 'help';
            $app_menus['menu']['items'][6]['var'] = 'rate_this_app';
            $app_menus['menu']['items'][7]['var'] = 'faqs';
            $app_menus['menu']['items'][8]['var'] = 'about_us';


            $app_menus['menu']['items'][0]['icon'] = 'ion-ios-home';
            $app_menus['menu']['items'][1]['icon'] = 'ion-social-buffer';
            $app_menus['menu']['items'][2]['icon'] = 'ion-clipboard';
            $app_menus['menu']['items'][3]['icon'] = 'ion-android-person';
            $app_menus['menu']['items'][4]['icon'] = 'ion-android-person';
            $app_menus['menu']['items'][5]['icon'] = 'ion-help-circled';
            $app_menus['menu']['items'][6]['icon'] = 'ion-android-playstore';
            $app_menus['menu']['items'][7]['icon'] = 'ion-ios-help';
            $app_menus['menu']['items'][8]['icon'] = 'ion-help-buoy';

            $app_menus['menu']['items'][0]['icon-alt'] = 'ion-ios-home';
            $app_menus['menu']['items'][1]['icon-alt'] = 'ion-social-buffer';
            $app_menus['menu']['items'][2]['icon-alt'] = 'ion-clipboard';
            $app_menus['menu']['items'][3]['icon-alt'] = 'ion-android-person';
            $app_menus['menu']['items'][4]['icon-alt'] = 'ion-android-person';
            $app_menus['menu']['items'][5]['icon-alt'] = 'ion-help-circled';
            $app_menus['menu']['items'][6]['icon-alt'] = 'ion-android-playstore';
            $app_menus['menu']['items'][7]['icon-alt'] = 'ion-ios-help';
            $app_menus['menu']['items'][8]['icon-alt'] = 'ion-help-buoy';

            $app_menus['menu']['items'][0]['type'] = 'divider';
            $app_menus['menu']['items'][1]['type'] = 'link';
            $app_menus['menu']['items'][2]['type'] = 'link';
            $app_menus['menu']['items'][3]['type'] = 'divider';
            $app_menus['menu']['items'][4]['type'] = 'link';
            $app_menus['menu']['items'][5]['type'] = 'divider';
            $app_menus['menu']['items'][6]['type'] = 'ext-playstore';
            $app_menus['menu']['items'][7]['type'] = 'link';
            $app_menus['menu']['items'][8]['type'] = 'link';


            break;
        case 'laundry-service':


            // TODO: + page -+- order/history

            $page = null;
            $page['page'][0]['prefix'] = 'order';
            $page['page'][0]['title'] = 'History';
            $page['page'][0]['menu'] = $file_name;
            //$page['page'][0]['lock'] = true;
            $page['page'][0]['css'] = null;
            $page['page'][0]['for'] = 'forms';
            $page['page'][0]['header-shrink'] = false;
            $page['page'][0]['button_up'] = 'none';
            $page['page'][0]['remove-has-header'] = false;
            $page['page'][0]['title-tranparant'] = false;
            $page['page'][0]['last_edit_by'] = 'page_builder';
            $page['page'][0]['parent'] = '';
            $page['page'][0]['menutype'] = 'side_menus-custom';
            $page['page'][0]['menu'] = '';
            $page['page'][0]['priority'] = 'warning';
            $page['page'][0]['class'] = 'has-header';
            $page['page'][0]['bg_image'] = true;
            $page['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
            $page['page'][0]['img_bg'] = $background;

            $page['page'][0]['content'] = '
          
<!-- code refresh -->
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>
<!-- ./code refresh -->

<!-- code search -->
 
<ion-list class="card list" >
	<div class="item item-input">
		<i class="icon ion-search placeholder-icon"></i>
		<input type="search" ng-model="filter_orders" placeholder="Filter" aria-label="filter orders" />
	</div>
</ion-list>
 
<!-- ./code search -->


<!-- code listing -->
<div class="list animate-none">
    <div class="list card" ng-repeat="item in orders | filter:filter_orders as results" ng-init="$last ? fireEvent() : null" href="#/laundry_service/order_singles/{{item.id}}" >
		<div class="item item-colorful">
            <div  ng-bind-html="item.service | to_trusted"></div>
            <span class="badge badge-assertive">{{ item.status }}</span>
        </div>
		<div class="item item-text-wrap">
			<blockquote class="to_trusted" ng-bind-html="item.note | to_trusted"></blockquote>
            <p>Date: {{ item.date | date:\'yyyy-MM-dd H:mm:ss\'}}</p>
            
	   </div>
 
	</div>
</div>
<!-- ./code listing -->



    <!-- code infinite scroll -->
    <ion-list class="list">
    	<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData">
        </ion-infinite-scroll>
    </ion-list>
    <!-- ./code infinite scroll -->


    <!-- code search result not found -->
    <ion-list class="list" ng-if="results.length == 0">
    	<div class="item" >
    		<p>No results found...!</p>
    	</div>
    </ion-list>
    <!-- code search result not found -->

 
           
            ';
            file_put_contents(JSM_PATH.'/projects/'.$file_name.'/page.order.json',json_encode($page));

            // TODO: + form -+- form_order
            $fvar = 'order';

            $form['forms'][$fvar]['select'] = 'order';
            $form['forms'][$fvar]['max'] = '4';
            $form['forms'][$fvar]['method'] = 'post';
            $form['forms'][$fvar]['table'] = 'order';
            $form['forms'][$fvar]['title'] = 'Order';
            $form['forms'][$fvar]['action'] = $site_url.'?json=submit&form=order';
            $form['forms'][$fvar]['msg_ok'] = 'Your request has been sent!';
            $form['forms'][$fvar]['msg_error'] = 'Please! complete the form provided.';
            $form['forms'][$fvar]['layout'] = 'card';
            $form['forms'][$fvar]['style'] = 'stacked';
            $form['forms'][$fvar]['prefix'] = 'order';

            $c = 0;
            $form['forms'][$fvar]['input'][$c]['label'] = 'Date';
            $form['forms'][$fvar]['input'][$c]['name'] = 'date';
            $form['forms'][$fvar]['input'][$c]['type'] = 'datetime';
            $form['forms'][$fvar]['input'][$c]['placeholder'] = date('Y-m-d H:i:s');

            $c++;
            $form['forms'][$fvar]['input'][$c]['label'] = 'Service';
            $form['forms'][$fvar]['input'][$c]['name'] = 'service';
            $form['forms'][$fvar]['input'][$c]['type'] = 'text';
            $form['forms'][$fvar]['input'][$c]['placeholder'] = 'Service';

            $c++;
            $form['forms'][$fvar]['input'][$c]['label'] = 'Note';
            $form['forms'][$fvar]['input'][$c]['name'] = 'note';
            $form['forms'][$fvar]['input'][$c]['type'] = 'text';
            $form['forms'][$fvar]['input'][$c]['placeholder'] = '';

            $c++;
            $form['forms'][$fvar]['input'][$c]['label'] = 'Submit';
            $form['forms'][$fvar]['input'][$c]['name'] = 'submit';
            $form['forms'][$fvar]['input'][$c]['type'] = 'button';
            $form['forms'][$fvar]['input'][$c]['placeholder'] = 'Submit';
            file_put_contents(JSM_PATH.'/projects/'.$file_name.'/forms.order.json',json_encode($form));

            $radio_services = null;
            $app_services = str_replace("\r","",$app_services);
            foreach(explode("\n",$app_services) as $service)
            {
                if(strlen($service) >= 1)
                {
                    $radio_services .= '<ion-radio ng-model="form_order.service" ng-value="\''.htmlentities($service).'\'">'.htmlentities($service).'</ion-radio>'."\r\n";
                }
            }
            // TODO: + page -+- form_order
            $page = null;
            $page['page'][0]['prefix'] = 'form_order';
            $page['page'][0]['title'] = 'Order Service';
            $page['page'][0]['menu'] = $file_name;
            $page['page'][0]['lock'] = true;
            $page['page'][0]['css'] = null;
            $page['page'][0]['for'] = 'forms';
            $page['page'][0]['header-shrink'] = false;
            $page['page'][0]['button_up'] = 'none';
            $page['page'][0]['remove-has-header'] = false;
            $page['page'][0]['title-tranparant'] = false;
            $page['page'][0]['last_edit_by'] = 'page_builder';
            $page['page'][0]['parent'] = '';
            $page['page'][0]['menutype'] = 'side_menus-custom';
            $page['page'][0]['menu'] = '';
            $page['page'][0]['priority'] = 'warning';
            $page['page'][0]['class'] = 'has-header';
            $page['page'][0]['bg_image'] = true;
            $page['page'][0]['img_bg'] = $background;
            $page['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
            $page['page'][0]['content'] = '
            
<div class="list card"  >
	<form ng-submit="submitOrder()">
        <div class="item item-divider">Order Services</div>
        
    	<div class="item item item-icon-left" ion-datetime-picker date time ng-model="form_order.date">
    		<i class="icon ion-ios-calendar-outline positive"></i>
    		<span>What time was picked up?</span>
    		<br/><strong>{{ form_order.date | date:\'yyyy-MM-dd H:mm:ss\' }}</strong>
    	</div>

        <ion-list>
          '.$radio_services.'  
        </ion-list>
         
		<label class="item item-input item-stacked-label">
			<span class="input-label">Note</span>
			<input type="text" ng-model="form_order.note" name="note" placeholder="" />
		</label>
                    
      	<div class="item item-text-wrap">
    		 <blockquote>
                Our laundry pickup applies only to members who have registered. 
            </blockquote>
    	</div>     
     
      	<div class="item item-button noborder">
    		<button class="button button-assertive ink">Order</button>
    	</div>
        
	</form>
</div>

<br/>
<br/>
<br/>
<br/>
            
            ';
            file_put_contents(JSM_PATH.'/projects/'.$file_name.'/page.form_order.json',json_encode($page));

            // TODO: + table -+- order
            $table_json = file_get_contents(JSM_PATH.'/system/includes/page-builder/eazy_setup_membership/json/table.order.json');
            $get_table_arr = json_decode($table_json,true);

            $table_arr = null;
            $table_prefix = 'order';
            $table_arr['tables'][$table_prefix] = $get_table_arr['tables']['order'];
            $table_arr['tables'][$table_prefix]['builder_link'] = @$_SERVER["HTTP_REFERER"];
            $table_arr['tables'][$table_prefix]['parent'] = 'order';
            $table_arr['tables'][$table_prefix]['db_url'] = $site_url.'?json=order';
            $table_arr['tables'][$table_prefix]['auth']['type'] = 'basic';
            $table_arr['tables'][$table_prefix]['auth']['db_type'] = 'online';

            $z = 0;
            $table_arr['tables'][$table_prefix]['cols'][$z]['label'] = 'ID';
            $table_arr['tables'][$table_prefix]['cols'][$z]['title'] = 'id';
            $table_arr['tables'][$table_prefix]['cols'][$z]['type'] = 'id';
            $table_arr['tables'][$table_prefix]['cols'][$z]['page_list'] = 'true';
            //$table_arr['tables'][$table_prefix]['cols'][$z]['page_detail'] = 'true';
            $table_arr['tables'][$table_prefix]['cols'][$z]['json'] = 'true';

            $z++;
            $table_arr['tables'][$table_prefix]['cols'][$z]['label'] = 'Date [txt]';
            $table_arr['tables'][$table_prefix]['cols'][$z]['title'] = 'date';
            $table_arr['tables'][$table_prefix]['cols'][$z]['type'] = 'text';
            $table_arr['tables'][$table_prefix]['cols'][$z]['page_list'] = 'true';
            //$table_arr['tables'][$table_prefix]['cols'][$z]['page_detail'] = 'false';
            $table_arr['tables'][$table_prefix]['cols'][$z]['json'] = 'true';

            $z++;
            $table_arr['tables'][$table_prefix]['cols'][$z]['label'] = 'Service: [txt]';
            $table_arr['tables'][$table_prefix]['cols'][$z]['title'] = 'service';
            $table_arr['tables'][$table_prefix]['cols'][$z]['type'] = 'heading-1';
            $table_arr['tables'][$table_prefix]['cols'][$z]['page_list'] = 'true';
            //$table_arr['tables'][$table_prefix]['cols'][$z]['page_detail'] = 'false';
            $table_arr['tables'][$table_prefix]['cols'][$z]['json'] = 'true';


            $z++;
            $table_arr['tables'][$table_prefix]['cols'][$z]['label'] = 'by User [txt]';
            $table_arr['tables'][$table_prefix]['cols'][$z]['title'] = 'uname';
            $table_arr['tables'][$table_prefix]['cols'][$z]['type'] = 'as_username';
            $table_arr['tables'][$table_prefix]['cols'][$z]['page_list'] = 'false';
            //$table_arr['tables'][$table_prefix]['cols'][$z]['page_detail'] = 'false';
            $table_arr['tables'][$table_prefix]['cols'][$z]['json'] = 'true';

            $z++;
            $table_arr['tables'][$table_prefix]['cols'][$z]['label'] = 'Status [txt]';
            $table_arr['tables'][$table_prefix]['cols'][$z]['title'] = 'status';
            $table_arr['tables'][$table_prefix]['cols'][$z]['type'] = 'text';
            $table_arr['tables'][$table_prefix]['cols'][$z]['page_list'] = 'true';
            //$table_arr['tables'][$table_prefix]['cols'][$z]['page_detail'] = 'false';
            $table_arr['tables'][$table_prefix]['cols'][$z]['json'] = 'true';

            $z++;
            $table_arr['tables'][$table_prefix]['cols'][$z]['label'] = 'Note';
            $table_arr['tables'][$table_prefix]['cols'][$z]['title'] = 'note';
            $table_arr['tables'][$table_prefix]['cols'][$z]['type'] = 'to_trusted';
            $table_arr['tables'][$table_prefix]['cols'][$z]['page_list'] = 'true';
            //$table_arr['tables'][$table_prefix]['cols'][$z]['page_detail'] = 'false';
            $table_arr['tables'][$table_prefix]['cols'][$z]['json'] = 'true';

            file_put_contents(JSM_PATH.'/projects/'.$file_name.'/tables.order.json',json_encode($table_arr));
            // TODO: + php_sql -+-

            $php_sql['php_sql'][0]['name'] = 'order';
            $php_sql['php_sql'][0]['sort'] = 'DESC';
            $php_sql['php_sql'][0]['limit'] = '200';
            $php_sql['php_sql'][0]['auth'] = 'true';
            $php_sql['php_sql'][0]['owned-by-me'] = 'true';

            $php_sql['php_sql'][1]['name'] = 'user';
            $php_sql['php_sql'][1]['sort'] = 'ASC';
            $php_sql['php_sql'][1]['limit'] = '200';
            $php_sql['php_sql'][1]['auth'] = 'true';
            $php_sql['php_sql'][1]['owned-by-me'] = 'true';

            file_put_contents(JSM_PATH.'/projects/'.$file_name.'/php_sql.json',json_encode($php_sql));


            // TODO: + menu -
            $app_menus['menu']['items'] = array();
            $app_menus['menu']['items'][0]['label'] = 'Services';
            $app_menus['menu']['items'][1]['label'] = 'Order';
            $app_menus['menu']['items'][2]['label'] = 'History';
            $app_menus['menu']['items'][3]['label'] = 'Help';
            $app_menus['menu']['items'][4]['label'] = 'Rate This App';
            $app_menus['menu']['items'][5]['label'] = 'Faqs';
            $app_menus['menu']['items'][6]['label'] = 'About Us';

            $app_menus['menu']['items'][0]['var'] = 'services';
            $app_menus['menu']['items'][1]['var'] = 'form_order';
            $app_menus['menu']['items'][2]['var'] = 'order';
            $app_menus['menu']['items'][3]['var'] = 'help';
            $app_menus['menu']['items'][4]['var'] = 'rate_this_app';
            $app_menus['menu']['items'][5]['var'] = 'faqs';
            $app_menus['menu']['items'][6]['var'] = 'about_us';


            $app_menus['menu']['items'][0]['icon'] = 'ion-ios-home';
            $app_menus['menu']['items'][1]['icon'] = 'ion-social-buffer';
            $app_menus['menu']['items'][2]['icon'] = 'ion-clipboard';
            $app_menus['menu']['items'][3]['icon'] = 'ion-help-circled';
            $app_menus['menu']['items'][4]['icon'] = 'ion-android-playstore';
            $app_menus['menu']['items'][5]['icon'] = 'ion-ios-help';
            $app_menus['menu']['items'][6]['icon'] = 'ion-help-buoy';

            $app_menus['menu']['items'][0]['icon-alt'] = 'ion-ios-home';
            $app_menus['menu']['items'][1]['icon-alt'] = 'ion-social-buffer';
            $app_menus['menu']['items'][2]['icon-alt'] = 'ion-clipboard';
            $app_menus['menu']['items'][3]['icon-alt'] = 'ion-help-circled';
            $app_menus['menu']['items'][4]['icon-alt'] = 'ion-android-playstore';
            $app_menus['menu']['items'][5]['icon-alt'] = 'ion-ios-help';
            $app_menus['menu']['items'][6]['icon-alt'] = 'ion-help-buoy';

            $app_menus['menu']['items'][0]['type'] = 'divider';
            $app_menus['menu']['items'][1]['type'] = 'link';
            $app_menus['menu']['items'][2]['type'] = 'link';
            $app_menus['menu']['items'][3]['type'] = 'divider';
            $app_menus['menu']['items'][4]['type'] = 'ext-playstore';
            $app_menus['menu']['items'][5]['type'] = 'link';
            $app_menus['menu']['items'][6]['type'] = 'link';


            break;
    }

    // TODO: + save menu -+-
    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/menu.json',json_encode($app_menus));
    buildIonic($file_name);
    //header('Location: ./?page=x-page-builder&prefix=eazy_setup_membership');
    //die();
}

$pagebuilder_file = 'projects/'.$_SESSION['FILE_NAME'].'/page_builder.membership.json';
$raw_data = array();
if(file_exists($pagebuilder_file))
{
    $_raw_data = json_decode(file_get_contents($pagebuilder_file),true);
    $raw_data = $_raw_data['page_builder']['membership'];
}


if(!isset($raw_data['site_url']))
{
    $raw_data['site_url'] = 'http://your_site.com/';
}

if(!isset($raw_data['app_logo']))
{
    $raw_data['app_logo'] = 'data/images/avatar/pic6.jpg';
}
if(!isset($raw_data['app_bg']))
{
    $raw_data['app_bg'] = 'data/images/background/bg15.jpg';
}

if(!isset($raw_data['app_code']))
{
    $raw_data['app_code'] = 'laundry-service';
}

if(!isset($raw_data['app_services']))
{
    $raw_data['app_services'] = "Option A\r\nOption B\r\n";
}


if($raw_data['app_services'] == "")
{
    $raw_data['app_services'] = "Option A\r\nOption B\r\n";
}
$apps = array();
$_apps[] = array('label' => 'Create Manual','value' => 'none');
$_apps[] = array('label' => 'Laundry Service','value' => 'laundry-service');
//$_apps[] = array('label' => 'Catering - Group Event & Party Food', 'value' => 'catering-service');


$z = 0;
foreach($_apps as $_app)
{
    $apps[$z] = $_app;
    if($raw_data['app_code'] == $_app['value'])
    {
        $apps[$z]['active'] = true;
    }
    $z++;
}

$form_input .= '<blockquote class="blockquote blockquote-warning">';
$form_input .= '<h4>How to use?</h4>';
$form_input .= '<p>Note: Beta test</p>';
$form_input .= '<ol>';
$form_input .= '<li>Please complete the fields below then save.</li>';
$form_input .= '<li>Next use the <a target="_blank" href="./?page=z-php-sql-restapi-generator">REST API Generator</a> as backend and <a target="_blank" href="./?page=z-php-sql-web-admin-generator">Web Admin Generator</a> as web admin</li>';
$form_input .= '<li>For editing page `About Us` and `FAQs`, you can using <code>Extra Menus</code> -&gt; <code>Page builder</code> -&gt; <a target="_blank" href="./?page=x-page-builder&prefix=page_about_us&target=about_us">About Us</a> and <a target="_blank" href="./?page=x-page-builder&prefix=page_faqs&target=faqs">FAQs</a></li>';
$form_input .= '<li>';
$form_input .= '
For create page required authorization, add this code to controller
<pre>
if(!$rootScope.current_user ){
    $ionicHistory.nextViewOptions({
        disableAnimate: true,
        disableBack: true
    });
    $state.go("'.$file_name.'.form_login");
}
</pre>
';
$form_input .= '</li>';
$form_input .= '</ol>';
$form_input .= '</blockquote>';
$form_input .= '<hr/>';

$form_input .= '<h4>Settings</h4>';
$form_input .= $bs->FormGroup('membership[site_url]','horizontal','text','Site URL','http://demo.ihsana.net/rest-api.php','Where is <a target="_blank" href="./?page=z-php-sql-restapi-generator">rest-api.php</a> file?',null,'7',$raw_data['site_url']);
$form_input .= '<h4>Images</h4>';
$form_input .= $bs->FormGroup('membership[app_logo]','horizontal','text','Logo','','','data-type="image-picker"','7',$raw_data['app_logo']);
$form_input .= $bs->FormGroup('membership[app_bg]','horizontal','text','Background','','','data-type="image-picker"','7',$raw_data['app_bg']);
$form_input .= '<h4>Apps</h4>';
$form_input .= $bs->FormGroup('membership[app_code]','horizontal','select','App',$apps,'','','7');
$form_input .= $bs->FormGroup('membership[app_services]','horizontal','textarea','Options','','Separator with enter','','7',$raw_data['app_services']);

?>