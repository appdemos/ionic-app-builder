<?php

/**
 * @author Jasman
 * @copyright 2016
 */


if (isset($_POST['page-builder']))
{
    $postdata = null;
    $postdata['wp_url'] = $_POST['wp_json_user']['wp_url'];

    $json_save['page_builder']['wp_json_user'] = $postdata;
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.wp_json_user.json', json_encode($json_save));


    $wp_link = $postdata['wp_url'];


    $new_page_content = '

<div class="hero no-header flat">
    <div class="content">
        <div class="app-icon" style="background: url(\'' . $_SESSION["PROJECT"]['menu']['logo'] . '\') center;"></div>
        <h2>' . $_SESSION["PROJECT"]['app']['name'] . '</h2>
    </div>
</div>

<div class="list">
    <ion-md-input placeholder="Username" highlight-color="balanced" type="text" ng-model="login_data.username"></ion-md-input>
    <ion-md-input placeholder="Password" highlight-color="energized" type="password" ng-model="login_data.password"></ion-md-input>
</div>

<div class="padding">
    <button ng-click="submitLogin()" class="button button-full button-assertive ink">Sign In</button>
    <a ui-sref="' . $_SESSION["PROJECT"]['app']['prefix'] . '.user_register" class="button button-full button-calm ink">Sign Up Here</a>
</div>
       
';


    $new_page_js = '

/** auth login **/
$scope.login_data = {username:"",password:""};
$scope.submitLogin = function(){
    
    // animation loading 
	$ionicLoading.show({
		template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\'
	});
    
    var username = $scope.login_data.username || "admin" ;
    var password = $scope.login_data.password || "1234" ;
    
    $http.get("' . $wp_link . '/api/user/generate_auth_cookie/?insecure=cool&username="+username+"&password="+password).then(function(resp_auth){ 
        
        $scope.login_data.username = "" ;
        $scope.login_data.password = "" ;                
        
        if(resp_auth.data.user){ 
            $ionicLoading.hide();
            window.localStorage.setItem("login_data",JSON.stringify(resp_auth.data));
            
            $http.get("' . $wp_link . '/api/user/get_userinfo/?user_id=" + resp_auth.data.user.id + "&insecure=cool").then(function(resp_userinfo){
                console.log(resp_userinfo.data);  
            });
            
            console.log(resp_auth.data);    
                    
            $ionicHistory.nextViewOptions({
                disableAnimate: true,
                disableBack: true
            });
            
            $ionicHistory.clearHistory();
            $ionicHistory.clearCache();
            $state.go("' . $_SESSION["PROJECT"]['app']['prefix'] . '.user_profile");
                
        }else{
            $ionicLoading.hide();
            $ionicPopup.show({
                title: "Something is wrong",
                template: "Please check your username and password.",
                buttons: [{
                    text: "Retry"
                }]
            })
            
        }
    },function errorCallback(err_auth){
        if(!err_auth.data){
            $ionicLoading.hide();
            $ionicPopup.show({
                title: "JSON URLs is problem",
                template: "Please check URLs or crossdomain issues.",
                buttons: [{
                    text: "Retry"
                }]
            })    
        }
            
    });
}

';

    $new_page_js = '

/** auth login **/
$scope.login_data = {username:"",password:""};
$scope.submitLogin = function(){
    
    // animation loading 
	$ionicLoading.show({
		template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\'
	});
    
    var username = $scope.login_data.username || "admin" ;
    var password = $scope.login_data.password || "1234" ;
 
    
    var auth_cookie_url = "' . $wp_link . '/api/user/generate_auth_cookie/?insecure=cool&username="+username+"&password="+password+"&callback=JSON_CALLBACK";
    $sce.trustAsResourceUrl(auth_cookie_url);
    
    $http.jsonp(auth_cookie_url).success(function(resp_auth_cookie, status, headers, config){
        console.log("resp_auth_cookie",resp_auth_cookie);
        
        $scope.login_data.username = "" ;
        $scope.login_data.password = "" ;
        
        if(resp_auth_cookie.user){ 
            $ionicLoading.hide();
            window.localStorage.setItem("login_data",JSON.stringify(resp_auth_cookie));      
            $ionicHistory.nextViewOptions({
                disableAnimate: true,
                disableBack: true
            });
            $ionicHistory.clearHistory();
            $ionicHistory.clearCache();
            $state.go("' . $_SESSION["PROJECT"]['app']['prefix'] . '.user_profile");            
        }else{
            $ionicLoading.hide();
            $ionicPopup.show({
                title: "Something is wrong",
                template: "Please check your username and password.",
                buttons: [{
                    text: "Retry"
                }]
            })
            
        }
    }).error(function (err_auth_cookie, status, headers, config) {
        console.log("err_auth_cookie",err_auth_cookie);
    });
}                 
';


    $new_page_class = 'login';
    $new_page_title = 'Login';
    $new_page_prefix = 'login';
    $new_page_css = '
.hero > .content h2{color: #fff;text-shadow: 0 1px 0px #000;}
.social-login { position: fixed; bottom: 0;}
.app-icon {background-color: #fff;background-size:cover !important; border-radius: 50%;height:80px;margin: 0 auto;width:80px;}
';


    $new_page = null;
    $new_page['page'][0] = array(
        'title' => 'User Login',
        'prefix' => 'user_login',
        'for' => '-',
        'last_edit_by' => 'page_builder',
        'priority' => 'low',
        'parent' => '',
        'menutype' => $_SESSION['PROJECT']['menu']['type'] . '-custom',
        'menu' => '',
        'lock' => false,
        'bg_image' => true,
        'img_bg' => 'data/images/background/bg3.jpg',
        'hide-navbar' => true,
        'button_up' => 'none',
        'content' => $new_page_content,
        'css' => $new_page_css,
        'js' => $new_page_js);
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page.user_login.json', json_encode($new_page));

    $new_page_content = '

<div class="list">
    <div class="item item-avatar item-text-wrap noborder">
        <img ng-src="{{ current_user.avatar }}" class="avatar" />
        <span>{{ current_user.username }}</span>
        <h2>{{ current_user.email }}</h2>
    </div>
</div>

<div class="list card">
    
    <div class="item item-divider">
        Personal Information
    </div>
    
    <label class="item item-input item-stacked-label">
        <span class="input-label">Display Name</span>
        <input type="text" ng-value="current_user.displayname" />
    </label>
    
    <label class="item item-input item-stacked-label">
        <span class="input-label">Firstname</span>
        <input type="text" ng-value="current_user.firstname" />
    </label>
    
    <label class="item item-input item-stacked-label">
        <span class="input-label">Lastname</span>
        <input type="text" ng-value="current_user.lastname" />
    </label>
    
    <label class="item item-input item-stacked-label">
        <span class="input-label">Date Registered</span>
        <input type="text" ng-value="current_user.registered" />
    </label>
    
        
    <label class="item item-input item-stacked-label">
        <span class="input-label">URL</span>
        <input type="text" ng-value="current_user.url" />
    </label> 
    
    <div class="item">
        <button class="button button-small button-assertive" ng-click="logOut()">Logout</button>
    </div>
    
</div>


 

';
    $new_page_js = '

    $interval(function() {
        
        if(window.localStorage.getItem("login_data") !== "undefined"){
            var login_data = JSON.parse(window.localStorage.getItem("login_data"));
            if(angular.isObject(login_data)){
                $scope.current_user = login_data.user;
            }else{
                window.localStorage.removeItem("login_data");
                $ionicHistory.nextViewOptions({
                    disableAnimate: true,
                    disableBack: true
                });
                
                $ionicHistory.clearHistory();
                $ionicHistory.clearCache();
                $state.go("' . $_SESSION["PROJECT"]['app']['prefix'] . '.user_login");
            }
        }else{
          
          window.localStorage.removeItem("login_data");
          
          $ionicHistory.nextViewOptions({
            disableAnimate: true,
            disableBack: true
          });
        
          $ionicHistory.clearHistory();
          $ionicHistory.clearCache();
          $state.go("' . $_SESSION["PROJECT"]['app']['prefix'] . '.user_login");
        }
    
    },500);


$scope.showDialog = function(){
    $ionicPopup.show({
        title: "Something is wrong",
        template: "Please check your username and password.",
        buttons: [{
            text: "Retry"
        }]
    })  
}

$scope.logOut = function(){
    window.localStorage.removeItem("login_data");
    
    $ionicHistory.nextViewOptions({
        disableAnimate: true,
        disableBack: true
    });
    
    $ionicHistory.clearHistory();
    $ionicHistory.clearCache();
    $state.go("' . $_SESSION["PROJECT"]['app']['prefix'] . '.user_login");
}

';

    $new_page = null;
    $new_page['page'][0] = array(
        'title' => 'User Profile',
        'prefix' => 'user_profile',
        'for' => '-',
        'last_edit_by' => 'page_builder',
        'priority' => 'low',
        'parent' => '',
        'menutype' => $_SESSION['PROJECT']['menu']['type'] . '-custom',
        'menu' => '',
        'lock' => false,
        'bg_image' => true,
        //'img_bg' => 'data/images/background/bg9.jpg',
        'hide-navbar' => false,
        'button_up' => 'none',
        'content' => $new_page_content,
        'css' => '',
        'js' => $new_page_js);
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page.user_profile.json', json_encode($new_page));


    $new_page_content = '

<div class="hero no-header flat">
    <div class="content">
        <div class="app-icon" style="background: url(\'' . $_SESSION["PROJECT"]['menu']['logo'] . '\') center;"></div>
        <h2>' . $_SESSION["PROJECT"]['app']['name'] . '</h2>
    </div>
</div>

<div class="list">
    <ion-md-input placeholder="User Name" highlight-color="assertive" type="text" ng-model="user_data.username"></ion-md-input>
    <ion-md-input placeholder="Email" highlight-color="assertive" type="text" ng-model="user_data.email"></ion-md-input>
    <ion-md-input placeholder="Password" highlight-color="assertive" type="password" ng-model="user_data.user_pass"></ion-md-input>
    <ion-md-input placeholder="Display Name" highlight-color="energized" type="text" ng-model="user_data.display_name"></ion-md-input>
</div>

<div class="padding">
    <button ng-click="submitRegister()" class="button button-full button-assertive ink">Sign Up</button>
    <a ui-sref="' . $_SESSION["PROJECT"]['app']['prefix'] . '.user_login" class="button button-full button-calm ink">Sign In Here</a>
</div>

';

    $new_page_js = '
            $scope.user_data = {username:"",user_pass:"",email:""};
            $scope.submitRegister = function(){
                // animation loading 
            	$ionicLoading.show({
            		template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\'
            	});    
                $http.get("' . $wp_link . '/api/get_nonce/?controller=user&method=register").then(function(resp_nonce){
                   
                    var http_params = $scope.user_data ;
                    http_params.nonce = resp_nonce.data.nonce;
                    var http_header = {params: http_params};
                    console.log("resp_nonce",resp_nonce);
                   
                    $http.get("' . $wp_link . '/api/user/register/?insecure=cool",http_header).then(function(resp_register){
                        if(resp_register.data.user_id){ 
                            $ionicLoading.hide();
                            $ionicPopup.show({
                                title: "Congratulations",
                                template: "Your account has been created successfully. Please login.",
                                buttons: [{
                                    text: "OK",         
                                    onTap: function(e){
                                        
                                        $ionicHistory.nextViewOptions({
                                            disableAnimate: true,
                                            disableBack: true
                                        });
                                        
                                        $ionicHistory.clearHistory();
                                        $ionicHistory.clearCache();
                                        $state.go("' . $_SESSION["PROJECT"]['app']['prefix'] . '.user_login");
            				        },
                                }]
                            })
                        }
                    },function(err_register){ 
                            $ionicLoading.hide();
                            $ionicPopup.show({
                                title: err_register.data.status,
                                template: err_register.data.error,
                                buttons: [{
                                    text: "Retry"
                                }]
                            })
                            console.log("register error");
                    }).finally(function() {
                        $ionicLoading.hide();
                        console.log("register done");
                    });
                    
                },function(err_nonce){
                        $ionicLoading.hide();
                        if(err_nonce.data){
                            $ionicPopup.show({
                                title: err_nonce.data.status,
                                template: err_nonce.data.error,
                                buttons: [{
                                    text: "Retry"
                                }]
                            })
                        }else{
                             $ionicPopup.show({
                                title: "JSON URLs is problem",
                                template: "Please check URLs or crossdomain issues.",
                                buttons: [{
                                    text: "Retry"
                                }]
                            })                             
                        }
                        console.log("err_nonce",err_nonce);
                }).finally(function(){
                     $ionicLoading.hide();
                });      
            }
';

$new_page_js = '
            $scope.user_data = {username:"",user_pass:"",email:""};
            $scope.submitRegister = function(){
                
                // animation loading 
            	$ionicLoading.show({
            		template: \'<div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div>\'
            	});   
                                 
                var nonce_url = "' . $wp_link . '/api/get_nonce/?controller=user&method=register&callback=JSON_CALLBACK";
                $sce.trustAsResourceUrl(nonce_url);
                
                $http.jsonp(nonce_url).success(function(resp_nonce, status, headers, config){
                    console.log("resp_nonce",resp_nonce);
                    
                    var http_params = $scope.user_data ;
                    http_params.nonce = resp_nonce.nonce;
                    var http_header = {params: http_params};
                                        
                    var register_url = "' . $wp_link . '/api/user/register/?insecure=cool&callback=JSON_CALLBACK";
                    $sce.trustAsResourceUrl(register_url);
                    $http.jsonp(register_url,http_header).success(function(resp_register, status, headers, config){
                            console.log("resp_register",resp_register);

                            if(resp_register.user_id){ 
                                    $ionicLoading.hide();
                                    $ionicPopup.show({
                                        title: "Congratulations",
                                        template: "Your account has been created successfully. Please login.",
                                        buttons: [{
                                            text: "OK",         
                                            onTap: function(e){
                                                
                                                $ionicHistory.nextViewOptions({
                                                    disableAnimate: true,
                                                    disableBack: true
                                                });
                                                
                                                $ionicHistory.clearHistory();
                                                $ionicHistory.clearCache();
                                                $state.go("' . $_SESSION["PROJECT"]['app']['prefix'] . '.user_login");
                    				        },
                                        }]
                                    })
                            }else{
                                    
                                    $ionicLoading.hide();
                                    $ionicPopup.show({
                                        title: resp_register.status,
                                        template: resp_register.error,
                                        buttons: [{
                                            text: "Retry"
                                        }]
                                    })
                                    
                            }
                    }).error(function (err_register, status, headers, config) {
                        console.log("err_register",err_register);
                    });    
                        
                }).error(function (err_nonce, status, headers, config) {
                    console.log("err_nonce",err_nonce);
                });
    
            }     
';


    $new_page = null;
    $new_page['page'][0] = array(
        'title' => 'User Register',
        'prefix' => 'user_register',
        'for' => '-',
        'last_edit_by' => 'page_builder',
        'priority' => 'low',
        'parent' => '',
        'menutype' => $_SESSION['PROJECT']['menu']['type'] . '-custom',
        'menu' => '',
        'lock' => false,
        'bg_image' => true,
        'img_bg' => 'data/images/background/bg3.jpg',
        'hide-navbar' => true,
        'button_up' => 'none',
        'content' => $new_page_content,
        'css' => '',
        'js' => $new_page_js);
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page.user_register.json', json_encode($new_page));


    buildIonic($_SESSION['FILE_NAME']);
}

$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.wp_json_user.json';
$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $get_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $get_raw_data['page_builder']['wp_json_user'];
}

$form_input .= '<blockquote class="blockquote blockquote-danger">';
$form_input .= '<p>Used for creating example code for user login: Page Login, Page Profile and Page Register</p>';
$form_input .= '</blockquote>';

if (!isset($raw_data['wp_url']))
{
    $raw_data['wp_url'] = 'http://demo.ihsana.net/';
}


$form_input .= '<blockquote class="blockquote blockquote-warning">';
$form_input .= '<p>Your WordPress Plugin requires REST API v2, REST-API Helper, JSON API, and JSON API User then must be active in your WordPress Site.</p>';
$form_input .= '<ol>';
$form_input .= '<li>Download <a href="https://wordpress.org/plugins/rest-api/">WordPress REST API 2</a>,
<a href="https://wordpress.org/plugins/rest-api-helper/">REST API Helper</a>, 
<a href="https://wordpress.org/plugins/json-api/">JSON API</a>, and
<a href="https://wordpress.org/plugins/json-api-user/">JSON API User</a>, 
 </li>';
$form_input .= '<li>Unzip and Upload `rest-api.xxx.zip`,`rest-api-helper.xxx.zip`,`json-api.xxx.zip` and `json-api-user.xxx.zip` to the `/wp-content/plugins/` directory</li>';
$form_input .= '<li>Activate the plugin through the \'plugins\' menu in WordPress</li>';
$form_input .= '<li>Activate user controller through the JSON API menu found in the WordPress admin center (Settings -> JSON API -> User)</li>';
$form_input .= '<li>Now save and please fill in the fields below:</li>';

$form_input .= '</ol>';
$form_input .= '</blockquote>';
$form_input .= '<hr/>';
$form_input .= $bs->FormGroup('wp_json_user[wp_url]', 'horizontal', 'text', 'WP URL', 'http://demo.ihsana.net/wordpress/', '', '', '7', $raw_data['wp_url']);

?>