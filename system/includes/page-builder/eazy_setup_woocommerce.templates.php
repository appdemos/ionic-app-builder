<?php

$_lock_the_page = false;

if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}

$app_menus = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_woocommerce/json/menu.json'), true);
$table_categories = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_woocommerce/json/table.categories.json'), true);
$table_products = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_woocommerce/json/table.products.json'), true);

$page_categories = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_woocommerce/json/page.categories.json'), true);
$page_products = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_woocommerce/json/page.products.json'), true);
$page_product_cart = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_woocommerce/json/page.product_cart.json'), true);
$page_product_singles = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_woocommerce/json/page.product_singles.json'), true);
$page_about_us = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_woocommerce/json/page.about_us.json'), true);
$page_faqs = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_woocommerce/json/page.faqs.json'), true);
$page_dashboard = json_decode(file_get_contents(JSM_PATH . '/system/includes/page-builder/eazy_setup_woocommerce/json/page.dashboard.json'), true);


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
    
    $app_config['app']['index'] = 'dashboard';
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/app.json', json_encode($app_config));

    $json_save['page_builder']['woocommerce']['woo_url'] = htmlentities($_POST['woocommerce']['woo_url']);
    $json_save['page_builder']['woocommerce']['woo_currency'] = htmlentities($_POST['woocommerce']['woo_currency']);

    $json_save['page_builder']['woocommerce']['consumer_key'] = htmlentities($_POST['woocommerce']['consumer_key']);
    $json_save['page_builder']['woocommerce']['consumer_secret'] = htmlentities($_POST['woocommerce']['consumer_secret']);

    $json_save['page_builder']['woocommerce']['label_categories'] = htmlentities($_POST['woocommerce']['label_categories']);
    $json_save['page_builder']['woocommerce']['label_products'] = htmlentities($_POST['woocommerce']['label_products']);

    $json_save['page_builder']['woocommerce']['label_cart'] = htmlentities($_POST['woocommerce']['label_cart']);
    $json_save['page_builder']['woocommerce']['label_help'] = htmlentities($_POST['woocommerce']['label_help']);
    $json_save['page_builder']['woocommerce']['label_rates'] = htmlentities($_POST['woocommerce']['label_rates']);
    $json_save['page_builder']['woocommerce']['label_faqs'] = htmlentities($_POST['woocommerce']['label_faqs']);
    $json_save['page_builder']['woocommerce']['label_aboutus'] = htmlentities($_POST['woocommerce']['label_aboutus']);


    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.woocommerce.json', json_encode($json_save));

    $site = $json_save['page_builder']['woocommerce']['woo_url'];
    $woo_currency = $json_save['page_builder']['woocommerce']['woo_currency'];
    $consumer_key = $json_save['page_builder']['woocommerce']['consumer_key'];
    $consumer_secret = $json_save['page_builder']['woocommerce']['consumer_secret'];

    $label_categories = $json_save['page_builder']['woocommerce']['label_categories'];
    $label_help = $json_save['page_builder']['woocommerce']['label_help'];
    $label_rates = $json_save['page_builder']['woocommerce']['label_rates'];
    $label_faqs = $json_save['page_builder']['woocommerce']['label_faqs'];
    $label_aboutus = $json_save['page_builder']['woocommerce']['label_aboutus'];
    $label_products = $json_save['page_builder']['woocommerce']['label_products'];
    $label_cart = $json_save['page_builder']['woocommerce']['label_cart'];

    $popover_config['popover']['icon'] = 'ion-android-more-vertical';
    $popover_config['popover']['title'] = htmlentities($label_help);
    $c = 0;

    $popover_config['popover']['menu'][$c]['title'] = 'Administrator';
    $popover_config['popover']['menu'][$c]['link'] = $site . '/wp-admin/';
    $popover_config['popover']['menu'][$c]['type'] = 'link-webview';
    $c++;

    $popover_config['popover']['menu'][$c]['title'] = htmlentities($label_faqs);
    $popover_config['popover']['menu'][$c]['link'] = '#/' . $file_name . '/faqs';
    $popover_config['popover']['menu'][$c]['type'] = 'link';
    $c++;

    $popover_config['popover']['menu'][$c]['title'] = htmlentities($label_aboutus);
    $popover_config['popover']['menu'][$c]['link'] = '#/' . $file_name . '/about_us';
    $popover_config['popover']['menu'][$c]['type'] = 'link';


    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/popover.json', json_encode($popover_config));


    // menu
    $app_menus['menu']['title'] = htmlentities($_SESSION['PROJECT']['app']['name']);

    $app_menus['menu']['items'][0]['label'] = htmlentities($label_categories);
    $app_menus['menu']['items'][1]['label'] = htmlentities($label_products);
    $app_menus['menu']['items'][2]['label'] = htmlentities($label_cart);

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/menu.json', json_encode($app_menus));

    // TODO: + tables -+- categories
    $table_categories['tables']['categorie']['db_url'] = $site . '/wp-json/ima_wc/v2/categories';
    $table_categories['tables']['categorie']['motions'] = 'none';

    // TODO: + tables -+- products
    $table_products['tables']['product']['db_url'] = $site . '/wp-json/ima_wc/v2/products/?categories=-1&per_pages=100';
    $table_products['tables']['product']['db_url_single'] = $site . '/wp-json/ima_wc/v2/products/';
    $table_products['tables']['product']['motions'] = 'none';

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.categorie.json', json_encode($table_categories));
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/tables.product.json', json_encode($table_products));

    // TODO: + page -+- categories
    $page_categories['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_categories['page'][0]['title'] = $label_categories;
    $page_categories['page'][0]['lock'] = $_lock_the_page;
    $page_categories['page'][0]['menu'] = $file_name;
    $page_categories['page'][0]['scroll'] = true;
    $page_categories['page'][0]['table-code']['url_detail'] = '#/' . $file_name . '/products/{{item.id}}';
    $page_categories['page'][0]['table-code']['url_list'] = '#/' . $file_name . '/categories';

    $page_categories['page'][0]['content'] = '
    
<!-- code refresh -->
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>
<!-- ./code refresh -->


<!-- code search -->
<ion-list class="list" >
	<div class="item item-input">
		<i class="icon ion-search placeholder-icon"></i>
		<input type="search" ng-model="filter_categories" placeholder="Filter" aria-label="filter categories" />
	</div>
</ion-list>
<!-- ./code search -->


<!-- code listing -->
<div class="list animate-none">
	<div class="item-thumbnail-3 card" ng-repeat="item in categories | filter:filter_categories as results" ng-init="$last ? fireEvent() : null"><a class="item item-colorful item-thumbnail-left item-text-wrap"  href="#/' . $file_name . '/products/{{item.id}}">
		<img alt="" class="full-image" ng-src="{{item.image.src}}" />
		<h3 class=""  ng-bind-html="item.name | to_trusted"></h3>
			<p >
			{{item.count}} items
			</p>
	       <i class="icon ion-android-more-horizontal pull-right"></i>
	</a></div>
</div>
<!-- ./code listing -->


<!-- code infinite scroll -->
<ion-list class="list">
	<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData">
    </ion-infinite-scroll>
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


    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.categories.json', json_encode($page_categories));

    // TODO: + page -+- products
    $page_products['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_products['page'][0]['title'] = $label_products;
    $page_products['page'][0]['menu'] = $file_name;
    $page_products['page'][0]['lock'] = $_lock_the_page;
    $page_products['page'][0]['scroll'] = true;
    $page_products['page'][0]['table-code']['url_detail'] = '#/' . $file_name . '/product_singles/{{item.id}}';
    $page_products['page'][0]['table-code']['url_list'] = '#/' . $file_name . '/products';

    $page_products['page'][0]['content'] = '
<!-- code refresh -->
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>
<!-- ./code refresh -->

<!-- code listing -->
<div class="list row" ng-repeat="rows in chunked_products" >
	<div class="col card" ng-repeat="item in rows">
		<a class="item item-image ink" ng-href="#/' . $file_name . '/product_singles/{{item.id}}">
			<img alt="" class="ratio1x1" ng-src="{{item.featured.thumbnail}}" />
		</a>
        
        <div class="item item-text-wrap" >
            <strong class="calm">{{ item.price | currency:"' . $woo_currency . '":2 }}</strong><br/>
            <span ng-bind-html="item.name | to_trusted">{{product.name}}</span>
        </div>
	
	</div>
</div>
<!-- ./code listing -->
      ';

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.products.json', json_encode($page_products));

    // TODO: + page -+- product_singles
    $page_product_singles['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_product_singles['page'][0]['menu'] = $file_name;
    $page_product_singles['page'][0]['lock'] = $_lock_the_page;
    $page_product_singles['page'][0]['content'] = '
    
<ion-refresher pulling-text="Pull to refresh..."  on-refresh="doRefresh()"></ion-refresher>                      
<div class="list">

    <div class="item item-avatar">
        <img alt="" class="ratio1x1" ng-src="{{product.featured.thumbnail}}" />
        <h2 ng-bind-html="product.name | to_trusted">{{product.name}}</h2>
        <p class="calm">{{ product.price | currency:"' . $woo_currency . '":2 }}</p>
    </div>

	<div class="item item-text-wrap noborder to_trusted">
    	<div class="slideshow_container to_trusted" ng-if="product.image_slidebox" >
			<ion-slides options="{slidesPerView:1}" slider="data.slider" class="slideshow">
				<ion-slide-page class="slideshow-item" ng-repeat="slide_item in product.image_slidebox | strExplode:\'|\' track by $index" >
					<div class="item-text-wrap" ng-bind-html="slide_item | to_trusted"></div>
				</ion-slide-page>
			</ion-slides>
    	</div>
	</div>
    
    <div class="item item-text-wrap noborder to_trusted" ng-bind-html="product.description | strHTML">
    </div>
        
        
   	<div ng-if="product.price" class="item tabs tabs-secondary tabs-icon-left">
		<a class="tab-item" ng-href="#/' . $file_name . '/product_cart"><i class="icon ion-android-cart"></i> ' . $label_cart . ' <span ng-show="item_in_virtual_table_product">( {{ item_in_virtual_table_product  }} )</span></a>
		<a class="tab-item" ng-click="addToDbVirtual(product);"><i class="icon ion-android-add-circle"></i> Add To Cart</a>
	</div>    
        
</div>

            
    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.product_singles.json', json_encode($page_product_singles));

    // TODO: + page -+- cart
    $page_product_cart['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_product_cart['page'][0]['title'] = $label_cart;
    $page_product_cart['page'][0]['menu'] = $file_name;
    $page_product_cart['page'][0]['lock'] = $_lock_the_page;
    $page_product_cart['page'][0]['content'] = '
    
<!-- shopping cart -->
	<div ng-if="product_cart.length != 0">
		<!-- items -->
		<div class="list" ng-init="product_order={}" >
			<div class="card" ng-repeat="item in product_cart" ng-init="product_order[$index]=item">
				<div class="item item-thumbnail-left item-button-right noborder">
					<img ng-src="{{ item.featured.thumbnail }}" />
					<h2 class="" ng-bind-html="item.name | to_trusted"></h2>
					<span>{{ item._sum | currency:"' . $woo_currency . '":2 }}</span>
					<input type="number" min="1" ng-change="updateDbVirtual()" ng-model="product_order[$index][\'_qty\']" />
					<button class="button button-small button-assertive button-outline" ng-click="removeDbVirtualProduct(item.id)"><i class="icon ion-trash-a"></i></button>
				</div>
			</div>
		</div>
		<!-- ./items -->

		<!-- totals -->
		<div class="list">
			<div class="item text-right">
				<h2>{{ product_cost | currency:"' . $woo_currency . '":2 }}</h2>
				<p>Go to Checkout to Pay</p>
			</div>
		</div>
		<!-- ./totals -->

		<!-- buttons -->
		<div class="list">
			<div class="item tabs tabs-secondary tabs-icon-top tabs-background-assertive-900">
				<a class="tab-item" ng-click="clearDbVirtualProduct();"><i class="icon ion-trash-a"></i> Clear</a>
				<a class="tab-item" ng-href="#/' . $file_name . '/product_checkout"><i class="icon ion-cash"></i> Checkout</a>
			</div>
		</div>
		<!-- ./buttons -->
	</div>
<!-- ./shopping cart -->


<!-- no items -->
	<div class="product_cart padding text-center" ng-if="product_cart.length == 0">
		<i class="icon ion-ios-cart-outline"></i>
		<p>There are no items in your cart</p>
	</div>
<!-- ./no items -->
    
    
    ';

    $page_product_cart['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.product_cart.json', json_encode($page_product_cart));

    // TODO: + page -+- checkout
    $page_checkout['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_checkout['page'][0]['prefix'] = 'product_checkout';
    $page_checkout['page'][0]['img_bg'] = ''; // $background;
    $page_checkout['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
    $page_checkout['page'][0]['lock'] = $_lock_the_page;
    $page_checkout['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'] . '-custom';
    $page_checkout['page'][0]['title'] = 'Checkout';
    $page_checkout['page'][0]['menu'] = $file_name;
    $page_checkout['page'][0]['for'] = '-';

    //$HeaderAuthorization = 'Basic ' . base64_encode( $consumer_key . ':'. $consumer_secret );
    $page_checkout['page'][0]['js'] = '
$ionicConfig.backButton.text(""); 
   
$scope.orders = {};     
$scope.orders.billing = {};     
$scope.orders.shipping = {};   
$scope.orders.line_items = [];    
   
// get items in cart  
localforage.getItem("product_cart", function(err,items){
	if(items === null){
		$scope.orders.line_items = []; 
	}else{
		try{
		    $scope.orders.line_items = [];  
            angular.forEach(JSON.parse(items), function(item, key) {
                $scope.orders.line_items.push ({ "product_id": item.id ,"quantity": item._qty });
            }); 
		}catch(e){
			$scope.orders.line_items = [];
		}
	}
}).then(function(items){
    $scope.orders.line_items = [];
    angular.forEach(JSON.parse(items), function(item, key) {
        $scope.orders.line_items.push ({ "product_id": item.id ,"quantity": item._qty });
    }); 
}).catch(function(err){
	 $scope.orders.line_items = []; 
});    
 
// set auth
// $http.defaults.headers.common["Authorization"] = "' . $HeaderAuthorization . '";      
$scope.payments = [
    {
        label: "Direct Bank Transfer",
        value: "bacs",
        desc: "Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won\'t be shipped until the funds have cleared in our account."
    },
    {
        label: "Cash on delivery",
        value: "cod",
        desc: "Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode."        
    }
];

$scope.countries = ' . json_encode($countries) . '; 

 
$scope.submitCheckout = function(orders){
        orders.billing = orders.shipping ;         
		$ionicLoading.show();
		var $messages, $title = null;
		$http({	method:"POST",
				url: "' . $site . '/wp-json/wc/v2/orders/?consumer_key=' . $consumer_key . '&consumer_secret=' . $consumer_secret . '",
                data: orders,
				headers: {"Content-Type":"application/json"}  
			}).then(function(response) {
				$messages = "Your order has been received<br/>Order ID: " + response.data.number ; 
				$title = "Thank you"; // response.data.title
			},function(response){
				$messages = response.data.message;
				$title = "Error: " + response.status;              
			}).finally(function(){
				$timeout(function() {
					$ionicLoading.hide();
					if($messages !== null){
    					var alertPopup = $ionicPopup.alert({
    						title: $title,
    						template: $messages,
    					});
					}
                    localforage.setItem("product_cart","[]");
			     }, 500);
		});
};

    ';
    $page_checkout['page'][0]['content'] = '
<form ng-submit="submitCheckout(orders)" novalidate="" name="form_order">
        
             
        <div class="item item-divider">
            BILLING DETAILS
        </div>
        
        <div class="list card">

            <label class="item item-input item-floating-label">
                <input type="email" autocomplete="off" class="form-control" name="email" placeholder="Email" ng-model="orders.billing.email" required="required" /> 
                <label for="email" class="control-label assertive" ng-show="form_order.email.$invalid && orders.billing.email!=\'\'">
                  <i class="icon ion-alert"></i> Email can\'t be empty
                </label>
            </label>
            
            <label class="item item-input item-floating-label">
                <input type="text" autocomplete="off" class="form-control" name="phone" placeholder="Phone" ng-model="orders.billing.phone" required="required" /> 
                <label for="phone" class="control-label assertive" ng-show="form_order.phone.$invalid && orders.billing.phone!=\'\'">
                  <i class="icon ion-alert"></i> Phone can\'t be empty
                </label>
            </label>
        
            <label class="item item-input item-floating-label">
                <input type="text" autocomplete="off" class="form-control" name="first_name" placeholder="First Name" ng-model="orders.shipping.first_name" required="required"/>        
                <label class="control-label assertive" ng-show="form_order.first_name.$invalid && orders.shipping.first_name!=\'\'">
                    <i class="icon ion-alert"></i> First name can\'t be empty
                </label>
            </label>
            
            <label class="item item-input item-floating-label">
                <input type="text" autocomplete="off" class="form-control" name="last_name" placeholder="Last Name" ng-model="orders.shipping.last_name" required="required"/>  
                <label class="control-label assertive" ng-show="form_order.last_name.$invalid && orders.shipping.last_name!=\'\'">
                  <i class="icon ion-alert"></i> Last name can\'t be empty
                </label>
            </label>
     
            <label class="item item-input item-floating-label">
                <input type="text" autocomplete="off" class="form-control" name="address_1" placeholder="Street Address" ng-model="orders.shipping.address_1" required="required"/>  
                <label class="control-label assertive" ng-show="form_order.address_1.$invalid && orders.shipping.address_1!=\'\'">
                  <i class="icon ion-alert"></i>
                  Street address can\'t be empty
                </label>
            </label> 
             
            <label class="item item-input item-floating-label">
                <input type="text" autocomplete="off" class="form-control" name="address_2" placeholder="Street Address" ng-model="orders.shipping.address_2" required="required"/>  
            </label> 

            <label class="item item-input item-floating-label">
                <input type="text" autocomplete="off" class="form-control" name="city" placeholder="Town/City" ng-model="orders.shipping.city" required="required"/>  
                <label class="control-label assertive" ng-show="form_order.city.$invalid && orders.shipping.city!=\'\'">
                  <i class="icon ion-alert"></i>
                  Town/city can\'t be empty
                </label>
            </label>

            <label class="item item-input item-floating-label">
                <input type="text" autocomplete="off" class="form-control" name="state" placeholder="State" ng-model="orders.shipping.state" required="required"/>  
                <label class="control-label assertive" ng-show="form_order.state.$invalid && orders.shipping.state!=\'\'">
                  <i class="icon ion-alert"></i>
                  State can\'t be empty
                </label>
            </label>
                     
            <label class="item item-input item-floating-label">
                <input type="text" autocomplete="off" class="form-control" name="postcode" placeholder="Zip Code" ng-model="orders.shipping.postcode" required="required"/>  
                <label for="postcode" class="control-label assertive" ng-show="form_order.postcode.$invalid && orders.shipping.postcode!=\'\'">
                  <i class="icon ion-alert"></i>
                  Zipcode can\'t be empty
                </label>
            </label>
            
            <label class="item item-input item-select">
               <div class="input-label">
                  Country
                </div>
                <select ng-model="orders.shipping.country">
                  <option ng-repeat="country in countries" ng-value="country.code">{{ country.name }}</option>
                </select>
            </label>                          
        </div>      

        <div class="item item-divider">
           PAYMENT METHOD
        </div>
        
        <div class="padding">
            <ion-list class="">
                <ion-radio ng-repeat="payment in payments" ng-model="orders.payment_method" ng-value="payment.value">
                    <div><b>{{payment.label}}</b></div>
                    <div class="item-text-wrap">{{payment.desc}}</div>
                </ion-radio>
            </ion-list>
        </div>
                 
        <div class="item item-button noborder">
            <button class="button button-assertive">Place Order</button>
        </div>
        
</form>

 

';


    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.product_checkout.json', json_encode($page_checkout));


    // TODO: + page -+- about us
    $page_about_us['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_about_us['page'][0]['img_bg'] = $background;
    $page_about_us['page'][0]['lock'] = $_lock_the_page;
    $page_about_us['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_about_us['page'][0]['css'] = '.about-us-box{background-color: rgba(255, 255, 255, 0.5);}' . "\r\n";
    $page_about_us['page'][0]['css'] .= '.about-us-box .item{border-color: rgba(255, 255, 255, 0.5);border-left:0;border-right:0;}';
    $page_about_us['page'][0]['content'] = '
<div class="padding scroll">

    <div class="padding about-us-box">
        <h2>' . htmlentities($_SESSION['PROJECT']['app']['name']) . '</h2>
        <div>
            ' . htmlentities($_SESSION['PROJECT']['app']['description']) . '
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
<br/><br/><br/>
';
    $page_about_us['page'][0]['js'] = '$ionicConfig.backButton.text("");';

    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.about_us.json', json_encode($page_about_us));


    // TODO: + page -+- faqs
    $page_faqs['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_faqs['page'][0]['lock'] = $_lock_the_page;
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
    
    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.faqs.json', json_encode($page_faqs));


    // TODO: + page -+- dashboard
    $page_dashboard['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_dashboard['page'][0]['lock'] = $_lock_the_page;
    $page_dashboard['page'][0]['css'] = '';
    $page_dashboard['page'][0]['img_bg'] = $background;
    $page_dashboard['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_dashboard['page'][0]['content'] = '

   
<!-- code slide hero -->
<div class="assertive-900-bg slide-box-hero" ng-controller="productsCtrl">
	<ion-slides class="slide-box-hero-content" options="{slidesPerView:1,autoplay:10000,loop:1}" slider="data.slider">
		<ion-slide-page class="slide-box-hero-item" ng-repeat="item in data_products | limitTo : 10:0" >
		<div class="slide-box-hero-container" style="background: url(\'{{item.featured.large}}\') no-repeat center center;">
			<div class="padding caption">
				<h2 ng-bind-html="item.name | strHTML"></h2>
				<a ng-href="#/' . $file_name . '/product_singles/{{item.id}}">>> more</a>
			</div>
		</div>
		</ion-slide-page>
	</ion-slides>
</div>
<!-- ./code slide hero -->

<!-- listing categories -->
<a ng-href="#/' . $file_name . '/categories" class="tags-heroes-title light-bg dark">'.$label_categories.' <i class="pull-right icon ion-chevron-right"></i></a>
<div class="light-bg" ng-controller="categoriesCtrl">
	<div class="tags-heroes-content list">
		<div class="row">
			<div ng-repeat="item in data_categories | limitTo:2:0" class="col" ng-class="$index ? \'col-33\':\'col-67\'" ><a href="#/' . $file_name . '/products/{{item.id}}" class="button button-small button-full ink" ng-class="{\'button-assertive\' : $index}">{{item.name}}</a></div>
		</div>
		<div class="row">
			<div ng-repeat="item in data_categories | limitTo:2:2" class="col" ng-class="$index ? \'col-66\':\'col-33\'" ><a href="#/' . $file_name . '/products/{{item.id}}" class="button button-small button-full ink" ng-class="$index ? \'button-stable\' : \'button-energized\'" >{{item.name}}</a></div>
		</div>
		<div class="row">
			<div ng-repeat="item in data_categories | limitTo:2:4" class="col" ng-class="$index ? \'col-33\':\'col-67\'" ><a href="#/' . $file_name . '/products/{{item.id}}" class="button button-small button-full ink" ng-class="{\'button-royal\' : $index}">{{item.name}}</a></div>
		</div>
	</div>
</div>
<!-- ./listing categories -->


<!-- listing products -->
<a ng-href="#/' . $file_name . '/products/-1" class="tags-heroes-title light-bg dark">'.$label_products.' <i class="pull-right icon ion-chevron-right"></i></a>
<div class="light-bg" ng-init="var_products={}">
    <div ng-controller="productsCtrl">
        <span ng-repeat="item_product in data_products" ng-init="var_products[$index]=item_product"></span>
    </div>

    <div ng-repeat="item in var_products | limitTo : 16:0">
        <a class="item item-thumbnail-left" href="#/' . $file_name . '/product_singles/{{ item.id }}">
            <img ng-src="{{ item.featured.large }}">
            <h2 ng-bind-html="item.name | strHTML"></h2>
            <span class="calm">{{ item.price | currency:"' . $woo_currency . '":2 }}</span> 
        </a>
    </div>
 
</div>
<!-- ./listing products -->

<div class="dark-bg stable">
       <div class="padding text-center">&copy ' . ($_SESSION['PROJECT']['app']['company']) . ', ' . date("Y") . '</div> 
</div>

<br/>
<br/> 
<br/>
<br/> 
<br/>

       
    ';
    file_put_contents(JSM_PATH . '/projects/' . $file_name . '/page.dashboard.json', json_encode($page_dashboard));





    // TODO: + page_builder -+- about_us
    $json_about_us_save['page_builder']['about_us']['about_us']['title'] = htmlentities($_SESSION['PROJECT']['app']['name']);
    $json_about_us_save['page_builder']['about_us']['about_us']['prefix'] = 'about_us';
    $json_about_us_save['page_builder']['about_us']['about_us']['background'] = $background;
    $json_about_us_save['page_builder']['about_us']['about_us']['company'] = htmlentities($_SESSION['PROJECT']['app']['company']);
    $json_about_us_save['page_builder']['about_us']['about_us']['content'] = htmlentities($_SESSION['PROJECT']['app']['description']);
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.about_us.about_us.json', json_encode($json_about_us_save));

    // TODO: + page_builder -+- faqs
    $json_faqs_save['page_builder']['faqs']['faqs']['title'] = 'FAQs';
    $json_faqs_save['page_builder']['faqs']['faqs']['prefix'] = 'faqs';
    $json_faqs_save['page_builder']['faqs']['faqs']['background'] = $background;
    file_put_contents('projects/' . $_SESSION['FILE_NAME'] . '/page_builder.faqs.faqs.json', json_encode($json_faqs_save));


    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=eazy_setup_woocommerce');
    die();
}

$pagebuilder_file = 'projects/' . $_SESSION['FILE_NAME'] . '/page_builder.woocommerce.json';
$raw_data = array();
if (file_exists($pagebuilder_file))
{
    $_raw_data = json_decode(file_get_contents($pagebuilder_file), true);
    $raw_data = $_raw_data['page_builder']['woocommerce'];
}


if (!isset($raw_data['woo_url']))
{
    $raw_data['woo_url'] = 'http://your_wordpress.org/';
}
if (!isset($raw_data['woo_currency']))
{
    $raw_data['woo_currency'] = 'Rp.';
}


$form_input .= '<blockquote class="blockquote blockquote-danger">';
$form_input .= '<p>Your WordPress Plugin requires REST API v2 and REST-API Helper, then must be active in your WordPress Site.</p>';
$form_input .= '<p>Beta Test not support payment gateway</p>';
$form_input .= '<ol>';
$form_input .= '<li>Download <a href="https://wordpress.org/plugins/rest-api/">WordPress REST API 2</a> and <a href="https://wordpress.org/plugins/rest-api-helper/">REST API Helper</a></li>';
$form_input .= '<li>Unzip and Upload `rest-api.xxx.zip` to the `/wp-content/plugins/rest-api` directory</li>';
$form_input .= '<li>Activate the plugin through the \'plugins\' menu in WordPress</li>';
$form_input .= '<li>Followed by unzip and Upload `rest-api-helper.xxx.zip` to the `/wp-content/plugins/rest-api-helper` directory</li>';
$form_input .= '<li>Then activate the plugin through the \'plugins\' menu </li>';
$form_input .= '<li>Still in \'plugins\' menu, click \'edit\' </li>';
$form_input .= '<li>Add <code>rest-api-helper</code> configuration to your wordpress config, file <kbd>wp-config.php</kbd> add code: <code>define("IMH_WOO", true);</code> after <kbd>&lt;?php</kbd></li>';
//$form_input .= '<li>You need allow iframe when you need wp-admin in app webview (go to Helper Tools -> Faqs -> Blank page in Webview or iframe)</li>';
$form_input .= '<li>For editing page `About Us` and `FAQs`, you can using <code>Extra Menus</code> -&gt; <code>Page builder</code> -&gt; <a target="_blank" href="./?page=x-page-builder&prefix=page_about_us&target=about_us">About Us</a> and <a target="_blank" href="./?page=x-page-builder&prefix=page_faqs&target=faqs">FAQs</a></li>';
$form_input .= '<li>Now save and please fill in the fields below:</li>';
$form_input .= '</ol>';
$form_input .= '</blockquote>';
$form_input .= '<hr/>';
if (!isset($raw_data['label_categories']))
{
    $raw_data['label_categories'] = 'Kategori';
}
if (!isset($raw_data['label_faqs']))
{
    $raw_data['label_faqs'] = 'Tanya Jawab';
}
if (!isset($raw_data['label_aboutus']))
{
    $raw_data['label_aboutus'] = 'Tentang Kami';
}
if (!isset($raw_data['label_help']))
{
    $raw_data['label_help'] = 'Bantuan';
}

if (!isset($raw_data['label_rates']))
{
    $raw_data['label_rates'] = 'Beri rating';
}
if (!isset($raw_data['label_cart']))
{
    $raw_data['label_cart'] = 'Keranjang Belanja';
}
if (!isset($raw_data['label_products']))
{
    $raw_data['label_products'] = 'Produk';
}


if (!isset($raw_data['consumer_key']))
{
    $raw_data['consumer_key'] = '';
}

if (!isset($raw_data['consumer_secret']))
{
    $raw_data['consumer_secret'] = '';
}

$form_input .= '<h4>Woocommerce</h4>';
$form_input .= $bs->FormGroup('woocommerce[woo_url]', 'horizontal', 'text', 'WordPress URL', 'http://demo.ihsana.net/wordpress/', '', null, '7', $raw_data['woo_url']);
$form_input .= $bs->FormGroup('woocommerce[woo_currency]', 'horizontal', 'text', 'Currency Symbol', 'Rp.', '', null, '3', $raw_data['woo_currency']);
$form_input .= '<hr/>';
$form_input .= '<h4>Woo API</h4>';
$form_input .= '<blockquote class="blockquote blockquote-warning">';
$form_input .= 'Only work for domain using SSL';
$form_input .= '</blockquote>';
$form_input .= $bs->FormGroup('woocommerce[consumer_key]', 'horizontal', 'text', 'Consumer Key', 'ck_d89859096d0cbec7dca675ca61e78dee675c0e32', '', null, '7', $raw_data['consumer_key']);
$form_input .= $bs->FormGroup('woocommerce[consumer_secret]', 'horizontal', 'text', 'Consumer Secret', 'cs_b3e7c815725fb9d5dc899b524053d1bf23aaa060', '', null, '7', $raw_data['consumer_secret']);
$form_input .= '<hr/>';
$form_input .= '<h4>Labels</h4>';
$form_input .= $bs->FormGroup('woocommerce[label_categories]', 'horizontal', 'text', 'Categories', 'Categories', '', null, '6', $raw_data['label_categories']);
$form_input .= $bs->FormGroup('woocommerce[label_products]', 'horizontal', 'text', 'Products', 'Products', '', null, '5', $raw_data['label_products']);
$form_input .= $bs->FormGroup('woocommerce[label_cart]', 'horizontal', 'text', 'Shopping Cart', 'Shopping Cart', '', null, '6', $raw_data['label_cart']);
$form_input .= $bs->FormGroup('woocommerce[label_help]', 'horizontal', 'text', 'Help', 'Help', '', null, '4', $raw_data['label_help']);
$form_input .= $bs->FormGroup('woocommerce[label_rates]', 'horizontal', 'text', 'Rate This App', 'Rate This App', '', null, '6', $raw_data['label_rates']);
$form_input .= $bs->FormGroup('woocommerce[label_faqs]', 'horizontal', 'text', 'FAQs', 'FAQs', '', null, '4', $raw_data['label_faqs']);
$form_input .= $bs->FormGroup('woocommerce[label_aboutus]', 'horizontal', 'text', 'About Us', 'About Us', '', null, '6', $raw_data['label_aboutus']);

?>