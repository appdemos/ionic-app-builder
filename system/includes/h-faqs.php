<?php

/**
 * @author Jasman
 * @copyright 2017
 */

if (!defined('JSM_EXEC'))
{
    die(':)');
}
if (!isset($_SESSION["PROJECT"]['menu']))
{
    header('Location: ./?page=menu&err=new');
    die();
}

$file_name = $_SESSION['FILE_NAME'];

$bs = new jsmBootstrap();
$content = $out_path = $footer = $html = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-question fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) FAQs</h4>';


$z = 0;

$z++;
$faqs[$z]['q'] = 'My emulator is not a real time update, what to do?';
$faqs[$z]['a'] = '
<p>Run browser in private/incognito mode on browser, disable ads block addons and don\'t using squid/proxy.</p>
<p>Then Turn off Firefox browser cache during development:</p> 
<ol>
<li>Open a new window or tab in Firefox</li>
<li>Type about:config in the address bar</li>
<li>Search for "cache" in the search bar and look for browser.cache.disk.enable in the filtered results</li>
<li>Double-click it will toggle it from "true" to "false". Default should be "true"</li>
</ol>';


$z++;
$faqs[$z]['q'] = 'How to make app notify mobile of update on the stores?';
$faqs[$z]['a'] = 'You don\'t have to manage this thing,when you add your new apk to the playstore with a new version code then the users who have already installed your application\'s old version will automatically notified.';


$z++;
$faqs[$z]['q'] = 'How to enable authorization for custom post type in WordPress?';
$faqs[$z]['a'] = '
<ol>
<li>Example event custom post type, edit your theme file: <kbd>wp-content/themes/yourtheme/functions.php</kbd>, then this code:
<pre>
 add_filter("rest_dispatch_request", function( $dispatch_result, $request, $route, $hndlr ){
    $target_base = "/wp/v2/event";    // Edit to your needs
    
    $pattern1 = untrailingslashit( $target_base );  
    $pattern2 = trailingslashit( $target_base );    
    
    if($pattern1 !== $route && $pattern2 !== substr( $route, 0, strlen($pattern2)))
        return $dispatch_result;
    
    if(is_user_logged_in())  
        return $dispatch_result;
  
    if( WP_REST_Server::READABLE !== $request->get_method() ) 
        return $dispatch_result;

    return new WP_Error( 
        "not-logged-in", 
        esc_html__( "Sorry, you are not allowed to do that.", "rest-api-helper"),["status" => 401]
    );

},10,4);

</pre>
</li>

<li>
Then edit rest-api-helper config, you can put code in file: <kbd>wp-config.php</kbd>, then add this code after <code>&lt;?php</code>
<pre>
define("IMH_RESTAPI_BASIC_AUTH", true);
define("IMH_ALLOW_PREFLIGHT_CORS", true);
</pre>

</li>
</ol>

';



$z++;
$faqs[$z]['q'] = 'ZipArchive and GD Library is missing or disabled';
$faqs[$z]['a'] = 'Run your terminal then type this command, example for php7.0:
<pre class="shell">
sudo apt-get install php7.0-zip
</pre>

<p>To see additional PHP 7 libraries that are available, run:</p>
<pre class="shell">
sudo apt-cache search php7.0-*
</pre>
<p>and also added</p>
<pre class="shell">
sudo apt-get install php7.0-gd
</pre>
<p>don\'t forget to restart Apache</p>

<pre class="shell">
sudo service apache2 restart
</pre>';


$z++;
$faqs[$z]['q'] = 'KCFinder Not Working on My Web Server, any suggestion?';
$faqs[$z]['a'] = 'You can change file browser by edit index.php file on the root IMA BuildeRz folder:  <pre class="html">define(\'JSM_FILEBROWSER\',\'kcfinder\');</pre> change to <pre class="html">define(\'JSM_FILEBROWSER\',\'elfinder\');</pre>';


$z++;
$faqs[$z]['q'] = 'Backend - PHP Warning:  file_get_contents(): http:// wrapper is disabled';
$faqs[$z]['a'] = 'Create a file with filename: <code>php.ini</code> and then write this code: <code>allow_url_fopen = On</code>, upload in same folder with your php files. If not solved, asking to your Hosting Provider to allowed <code>curl</code> or <code>allow_url_fopen</code>';


$z++;
$faqs[$z]['q'] = 'API - How to use JSON from REST-API not allow cors, like blogspot, tumbrl?';
$faqs[$z]['a'] = 'You can using cors proxy like: <kbd>http://cors.io/</kbd> or <kbd>https://crossorigin.me</kbd>';

$z++;
$faqs[$z]['q'] = 'Custom Code - How enable ng-map directive?';
$faqs[$z]['a'] = 'Create a table contain column type <kbd>gmap</kbd> with page target <kbd>No Page</kbd>';

$z++;
$faqs[$z]['q'] = 'NodeJS - Native scroll break in Android 4.1';
$faqs[$z]['a'] = 'Native Scrolling doesn\'t really work in Android 4.1-4.3, because limitation of the webview. 
check this issue: <a href="https://github.com/­driftyco/ionic/­issues/4252">Issues #4252</a>
An option would be to use crosswalk plugin.
';

$z++;
$faqs[$z]['q'] = 'NodeJS - InAppBrowser.java:124: error: cannot find symbol || Config.isUrlWhiteListed(url))?';
$faqs[$z]['a'] = 'The problem comes from your cordova plugin, please update all your plugins.';

$z++;
$faqs[$z]['q'] = 'NodeJS - How to update cordova plugins?';
$faqs[$z]['a'] = 'manually: remove old plugin then add new plugin:
<pre class="shell">
cordova plugin rm cordova-plugin-xxxx --save
</pre>
then
<pre class="shell">
cordova plugin add cordova-plugin-xxxx --save
</pre>
use this command for check version:
<pre class="shell">
cordova plugin list
</pre>
or If still not work, install cordova-check-plugins in globally:
<pre class="shell">
npm install -g cordova-check-plugins --save
</pre>
Then run from the root of your Cordova project. You can optionally update outdated plugins interactively or automatically, e.g.
<pre class="shell">
cordova-check-plugins --update=auto
</pre>
';
 
 
 $z++;
$faqs[$z]['q'] = 'Running "cordova build android" - unable to find attribute android:fontVariationSettings and android:ttcIndex';
$faqs[$z]['a'] = 'Well documented plugin which "aligns various versions of the Android Support libraries specified by other plugins to a specific version". Tested without any destructive behavior. 
<pre class="shell">
cordova plugin add cordova-android-support-gradle-release --fetch
</pre>
<p>Read the documentation for a full set of options: <a target="_blank" href="https://github.com/dpa99c/cordova-android-support-gradle-release" rel="noreferrer">Readme</a></p>
';
 
 
 

$z++;
$faqs[$z]['q'] = 'NodeJS - Please fix the version conflict either by updating the version of the google-services plugin';
$faqs[$z]['a'] = 'Isssue come from <code>cordova-plugin-fcm</code>, go to Extra Menus -&raquo; Push Notifications, then use `onesignal-cordova-plugin` as push notification.
Remove the old cordova project, then repeat from scratch (Follow guides in Dashboard-&raquo; How to build? --&raquo; 2) How to build a project with Cordova?).
';

$faqs[$z]['q'] = 'API -Table settings for REST-API V1.x?';
$faqs[$z]['a'] = 'Not a good use of the old plugin, Fix issue about <kbd>crossdomain</kbd> and you can set up as follows:<br/>
Table Posts
<pre class="html">
URL Listing: http://yourwp/wp-json/posts/?filter[cat]=9373
URL Single: http://yourwp/wp-json/posts/
Dinamic on 1st param : checked
</pre>

Table Categories
<pre class="html">
URL Listing: http://yourwp/wp-json/taxonomies/category/terms
URL Single: 
Dinamic on 1st param : unchecked
Relation: posts
</pre>

Other Link:
<pre class="html">
http://yourwp/wp-json/posts/?filter[posts_per_page]=3&filter[category_name]=peristiwa
</pre>
';


$z++;

$faqs[$z]['q'] = 'NodeJS - How to fix error: Source path does not exist: resources/android/icon/drawable-hdpi-icon.png';
$faqs[$z]['a'] = 'Go to Extra Menus -&gt; Resources, Fill the form for create icons and splashscreen then click Save Resources button. 
				  You will founding 20 images here <kbd>' . realpath(JSM_PATH . "/output/" . $file_name . '/resources/android/') . '</kbd>
				  Then follow instruction in Dashboard -&gt; How To build?<br/><br/>
				  or You can using <kbd>ionic resources</kbd> without IMA BuildeRz <kbd>nodejs/yourapp/resources/android/</kbd>
				  <br/>
				  You can edit image using adobe photoshop or gimp.
				  ';

$z++;
$faqs[$z]['q'] = 'Phonegap - Splashscreen not appear in Adobe Phonegap Build?';
$faqs[$z]['a'] = 'There are different splashscreen and icon path in phonegap build, path here: <kbd>www/res/icon/android</kbd> and <kbd>www/res/screen/android</kbd> and for config.xml, copy from <kbd>config-phonegap.xml</kbd>
<pre class="html">' . htmlentities('
<platform name="android">
	<icon qualifier="ldpi" src="www/res/icon/android/ldpi-icon.png" />
	<icon qualifier="mdpi" src="www/res/icon/android/mdpi-icon.png" />
	<icon qualifier="hdpi" src="www/res/icon/android/hdpi-icon.png" />
	<icon qualifier="xhdpi" src="www/res/icon/android/xhdpi-icon.png" />
	<icon qualifier="xxhdpi" src="www/res/icon/android/xxhdpi-icon.png" />
	<icon qualifier="xxxhdpi" src="www/res/icon/android/xxxhdpi-icon.png" />
	<splash density="ldpi" src="www/res/screen/android/ldpi.png" />
	<splash density="mdpi" src="www/res/screen/android/mdpi.png" />
	<splash density="hdpi" src="www/res/screen/android/hdpi.png" />
	<splash density="xhdpi" src="www/res/screen/android/xhdpi.png" />
	<splash density="xxhdpi" src="www/res/screen/android/xxhdpi.png" />
	<splash density="xxxhdpi" src="www/res/screen/android/xxxhdpi.png" />
</platform>') . '</pre>
Not support custom path
';

$z++;
$faqs[$z]['q'] = 'NodeJS - Splashscreen not appear, whether should I do?';
$faqs[$z]['a'] = 'Do you have to install the plugin cordova-plugin-splashscreen,
				  Run command like this in your nodejs:
				  <pre class="shell">cordova plugin add cordova-plugin-splashscreen --save</pre>';

$z++;
$faqs[$z]['q'] = 'NodeJS - Still problem with icon and splashscreen?';
$faqs[$z]['a'] = 'If created manually, check your config.xml:
				  <pre class="html">' . htmlentities('
<platform name="android">
	<icon density="ldpi" src="resources/android/icon/drawable-ldpi-icon.png" />
	<icon density="mdpi" src="resources/android/icon/drawable-mdpi-icon.png" />
	<icon density="hdpi" src="resources/android/icon/drawable-hdpi-icon.png" />
	<icon density="xhdpi" src="resources/android/icon/drawable-xhdpi-icon.png" />
	<icon density="xxhdpi" src="resources/android/icon/drawable-xxhdpi-icon.png" />
	<icon density="xxxhdpi" src="resources/android/icon/drawable-xxxhdpi-icon.png" />
	<splash density="land-ldpi" src="resources/android/splash/drawable-land-ldpi-screen.png" />
	<splash density="land-mdpi" src="resources/android/splash/drawable-land-mdpi-screen.png" />
	<splash density="land-hdpi" src="resources/android/splash/drawable-land-hdpi-screen.png" />
	<splash density="land-xhdpi" src="resources/android/splash/drawable-land-xhdpi-screen.png" />
	<splash density="land-xxhdpi" src="resources/android/splash/drawable-land-xxhdpi-screen.png" />
	<splash density="land-xxxhdpi" src="resources/android/splash/drawable-land-xxxhdpi-screen.png" />
	<splash density="port-ldpi" src="resources/android/splash/drawable-port-ldpi-screen.png" />
	<splash density="port-mdpi" src="resources/android/splash/drawable-port-mdpi-screen.png" />
	<splash density="port-hdpi" src="resources/android/splash/drawable-port-hdpi-screen.png" />
	<splash density="port-xhdpi" src="resources/android/splash/drawable-port-xhdpi-screen.png" />
	<splash density="port-xxhdpi" src="resources/android/splash/drawable-port-xxhdpi-screen.png" />
	<splash density="port-xxxhdpi" src="resources/android/splash/drawable-port-xxxhdpi-screen.png" />
</platform>
    ') . '</pre>';


$z++;
$faqs[$z]['q'] = 'NodeJS - Everything was fine, but why icon and splashscreen still using ionic/cordova logo?';
$faqs[$z]['a'] = 'If you found res folder in <kbd>/root_project_in_nodejs/res/*</kbd> directory, overwrite to <kbd>/root_project_in_nodejs/platforms/android/res/*</kbd>';


$z++;
$faqs[$z]['q'] = 'NodeJS - How do I remove the cordova plugin is already installed in nodejs?';
$faqs[$z]['a'] = 'Run command like this in your nodejs:<br/>
                  <pre class="shell">cordova plugin rm cordova-plugin-name</pre>
                  <pre class="shell">cordova plugin rm cordova-plugin-admobpro</pre>';


$z++;
$faqs[$z]['q'] = 'NodeJS - How to set proxy using nodejs?';
$faqs[$z]['a'] = 'Run command like this in your nodejs:<br/>
                  <pre class="shell">npm config set proxy http://192.168.0.1:3129</pre>
                  <pre class="shell">npm config set https-proxy http://192.168.0.1:3129</pre>';

$z++;
$faqs[$z]['q'] = 'NodeJS - How to changing gradle distribution url?';
$faqs[$z]['a'] = 'Download gradle from official site, example link: http://services.gradle.org/distributions/gradle-*-all.zip, then copy to your localhost. in nodejs run this command:
                  <pre class="shell">SET "CORDOVA_ANDROID_GRADLE_DISTRIBUTION_URL=http://ionic.co.id/gradle-*-all.zip"</pre>';


$z++;
$faqs[$z]['q'] = 'Backend - Blank page in Webview or iframe, or crossdomain issues or Network error?';
$faqs[$z]['a'] = 'Issues with crossdomain, add this code to .htaccess:
<pre class="html">' . htmlentities('<IfModule mod_headers.c>
Header set Access-Control-Allow-Origin "*"
Header set cache-control "private, max-age=0, no-cache, no-store"
Header set pragma "no-cache"
Header set X-Frame-Options "ALLOWALL"
</IfModule>') . '</pre>';

$z++;
$faqs[$z]['q'] = 'IMA - Can you tell me how to properly change the package name?';
$faqs[$z]['a'] = 'You can change it by edit <kbd>index.php</kbd> file on the root IMA BuildeRz folder:
<pre class="html">define(\'JSM_PACKAGE_NAME\', \'com.imabuilder\');</pre>';

$z++;
$faqs[$z]['q'] = 'IMA - How to give imabuilder password, for prevent people from entering at will.?';
$faqs[$z]['a'] = 'You can active authorization/login by edit <kbd>index.php</kbd> file on the root IMA BuildeRz folder:
<pre class="html">
define(\'JSM_AUTH\', false);
define(\'JSM_USERNAME\', \'admin\'); // your username
define(\'JSM_PASSWORD\', \'admin\'); // your password
</pre>

<p>change to</p> 

<pre class="html">
define(\'JSM_AUTH\', true);
define(\'JSM_USERNAME\', \'your-username\'); // your username
define(\'JSM_PASSWORD\', \'your-password\'); // your password
</pre>

';




$z = 0;
foreach ($faqs as $faq)
{
    $content .= '<div class="panel panel-default">';
    $content .= '<div class="panel-heading"><a data-toggle="collapse" data-parent="#accordion" href="#faqs-' . $z . '"><p class="panel-title">' . $faq['q'] . '</p></a></div>';
    $content .= '<div id="faqs-' . $z . '" class="panel-collapse collapse">';
    $content .= '<div class="panel-body">';
    $content .= '' . $faq['a'] . '';
    $content .= '</div>' . "\r\n";
    $content .= '</div>' . "\r\n";
    $content .= '</div>' . "\r\n";
    $z++;
}

$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Helper Tools -&raquo; FAQs';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;
$template->emulator = false;

?>