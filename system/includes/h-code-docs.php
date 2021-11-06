<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

if (!defined('JSM_EXEC'))
{
    die(':)');
}

$bs = new jsmBootstrap();
$content = $out_path = $footer = $html = null;

$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-code fa-stack-1x"></i></span>Helper Tools -&raquo; (IMAB) Code Docs</h4>';


$content .= '<div class="row">';
$content .= '<div class="col-md-12">';

$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h5 class="panel-title">Reference</h5></div>';
$content .= '<div class="panel-body">';

$content .= '<ul>';
$content .= '<li>Ionicframework V1, <a href="http://ionicframework.com/docs/v1/components/">component</a> and <a href="http://ionicframework.com/docs/v1/api/">API</a></li>';
$content .= '<li>Ionic Material, <a href="https://github.com/zachsoft/Ionic-Material/">github</a></li>';
$content .= '<li>AngularJS Google Maps: <a href="https://ngmap.github.io/#/!directions-with-current-location.html">https://ngmap.github.io</a></li>';
 $content .= '<li>ion-datetime-picker <a href="https://github.com/katemihalikova/ion-datetime-picker">github</a></li>';
 
$content .= '</ul>';

$content .= '</div>';
$content .= '</div>';


$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h5 class="panel-title">Ionic Class</h5></div>';
$content .= '<div class="panel-body">';


$colors = array(
    'light',
    'stable',
    'positive',
    'positive-900',
    'calm',
    'calm-900',
    'balanced',
    'balanced-900',
    'energized',
    'energized-900',
    'assertive',
    'assertive-900',
    'royal',
    'royal-900',
    'dark',
    );
$content .= '<h4>ClassName for Colors</h4>';
$content .= '<p>When you find the <strong>className</strong> in page editor, it is the color, you can replace it with another className.</p>';
$content .= '<table class="table table-striped">';
$content .= '
<thead>
<tr>
    <th>TEXT</th>
    <th>BACKGROUND</th>
    <th>BUTTON</th>
	<th>ITEM</th>
</tr>
</thead>
';

foreach ($colors as $color)
{
    $content .= '
<tr>
    <td><span class="' . $color . '">' . $color . '</span></td>
    <td><span class="' . $color . '-bg">' . $color . '-bg</span></td>
    <td><button class="ionic-button button-' . $color . '">button button-' . $color . '</button></td>
	<td><span class="' . $color . '-bg">item item-' . $color . '</span></td>
</tr>
';
}
$content .= '</table>';
$content .= '<p>You can change this color scheme using <a href="./?page=x-custom-themes">Custom Themes</a></p>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

$content .= '<div class="col-md-12">';
$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h5 class="panel-title">Filter</h5></div>';
$content .= '<div class="panel-body">';

$content .= '<h5>HTML</h5>';
$content .= '<pre class="html">' . htmlentities('<div ng-bind-html="item.string | strHTML"></div>') . '</pre>';
$content .= '<pre class="html">' . htmlentities('<div ng-bind-html="item.string | to_trusted"></div>') . '</pre>';

$content .= '<h5>Trusted URL</h5>';
$content .= '<p>Example using in iframe</p>';
$content .= '<pre class="html">' . htmlentities('<iframe width="100%" ng-src="{{ \'http://domain/page\' | trustUrl }}" ></iframe>') . '</pre>';
$content .= '<p>Example using in audio</p>';
$content .= '<pre class="html">' . htmlentities('<audio controls="controls" ng-src="\'http://domain/file.ogg\' | trustUrl }}" ></audio>') . '</pre>';
$content .= '<p>Example using in youtube</p>';
$content .= '<pre class="html">' . htmlentities('<div class="embed_container"><iframe width="100%" ng-src="{{ \'https://www.youtube.com/embed/YOUTUBE_ID\' | trustUrl }}" allowfullscreen></iframe></div>') . '</pre>';


$content .= '<h5>Limit</h5>';
$content .= '<pre class="html">' . htmlentities('<div>{{ item.string | limitTo:140 | strHTML }}</div>') . '</pre>';
$content .= '<pre class="html">' . htmlentities('<div ng-repeat="item in items | limitTo : 10:0" >') . '</pre>';

$content .= '<h5>Date</h5>';
$content .= '<h5>TimeStamp JS</h5><pre class="html">' . htmlentities('<div>{{ item.month | date:\'dd-MM-yyyy\' }}</div>') . '</pre>';
$content .= '<h5>String</h5><pre class="html">' . htmlentities('<div>{{ item.month | strDate | date:\'dd-MM-yyyy\' }}</div>') . '</pre>';
$content .= '<h5>TimeStamp PHP</h5><pre class="html">' . htmlentities('<div>{{ item.month | phpTime | date:\'dd-MM-yyyy\' }}</div>') . '</pre>';

$content .= 'more: <a href="https://www.w3schools.com/angular/ng_filter_date.asp">w3schools</a>';


$content .= '<h5>Escape</h5>';
$content .= '<pre class="html">' . htmlentities('<div>{{ item.string | strEscape }}</div>') . '</pre>';

$content .= '<h5>Unscape + Trusted HTML</h5>';
$content .= '<pre class="html">' . htmlentities('<div ng-bind-html="item.string | Unscape"></div>') . '</pre>';


$content .= '<h5>Number</h5>';
$content .= '<pre class="html">' . htmlentities('<div>{{ item.string | number }}</div>') . '</pre>';

$content .= '<h5>Currency</h5>';
$content .= '<pre class="html">' . htmlentities('<div>{{ item.string | currency:"Rp.":2 }}</div>') . '</pre>';

$content .= '</div>';
$content .= '</div>';
$content .= '</div>';


$content .= '<div class="col-md-12">';

$content .= '<div class="panel panel-default">';
$content .= '<div class="panel-heading"><h5 class="panel-title">Directive</h5></div>';
$content .= '<div class="panel-body">';
$content .= '<h4>CALL</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<button run-app-call phone="082233333734" class="button icon-left ion-android-call">082233333734</button>') . '</pre>';
$content .= '<h5>config.xml</h5>';
$content .= '<pre>' . htmlentities('<allow-intent href="tel:*" />') . '</pre>';
$content .= '<h5>plugin</h5>';
$content .= '<ul>';
$content .= '<li>cordova-plugin-whitelist</li>';
$content .= '<li>cordova-plugin-inappbrowser</li>';
$content .= '</ul>';
$content .= '<hr/>';
$content .= '<h4>SMS</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<button run-app-sms phone="082233333734" message="Your Message" class="button button-assertive icon-left ion-email">SMS</button>') . '</pre>';
$content .= '<h5>config.xml</h5>';
$content .= '<pre>' . htmlentities('<allow-intent href="sms:*" />') . '</pre>';
$content .= '<h5>plugin</h5>';
$content .= '<ul>';
$content .= '<li>cordova-plugin-whitelist</li>';
$content .= '<li>cordova-plugin-inappbrowser</li>';
$content .= '</ul>';

$content .= '<hr/>';
$content .= '<h4>Email</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<button run-app-email email="jasman@ihsana.com" subject="Subject Message" message="Your Message" class="icon-left ion-email">Email</button>') . '</pre>';
$content .= '<h5>config.xml</h5>';
$content .= '<pre>' . htmlentities('<allow-intent href="mailto:*" />') . '</pre>';
$content .= '<h5>plugin</h5>';
$content .= '<ul>';
$content .= '<li>cordova-plugin-whitelist</li>';
$content .= '<li>cordova-plugin-inappbrowser</li>';
$content .= '</ul>';

$content .= '<hr/>';
$content .= '<h4>Line</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<button run-app-line message="Your Message" class="button button-calm icon-left ion-ios-chatbubble">Line</button>') . '</pre>';
$content .= '<h5>config.xml</h5>';
$content .= '<pre>' . htmlentities('<allow-intent href="line:*" />') . '</pre>';
$content .= '<h5>plugin</h5>';
$content .= '<ul>';
$content .= '<li>cordova-plugin-whitelist</li>';
$content .= '<li>cordova-plugin-inappbrowser</li>';
$content .= '</ul>';

$content .= '<hr/>';
$content .= '<h4>Whatsapp</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<button run-app-whatsapp message="Your Message" class="button button-calm icon-left ion-social-whatsapp">Whatsapp</button>') . '</pre>';
$content .= '<h5>config.xml</h5>';
$content .= '<pre>' . htmlentities('<allow-intent href="whatsapp:*" />') . '</pre>';
$content .= '<h5>plugin</h5>';
$content .= '<ul>';
$content .= '<li>cordova-plugin-whitelist</li>';
$content .= '<li>cordova-plugin-inappbrowser</li>';
$content .= '</ul>';

$content .= '<hr/>';
$content .= '<h4>Facebook</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<button run-app-facebook link="http://ihsana.com" class="button button-calm icon-left ion-social-facebook">Facebook</button>') . '</pre>';
$content .= '<h5>config.xml</h5>';
$content .= '<pre>' . htmlentities('<allow-intent href="fbapi20130214:*" />') . '</pre>';
$content .= '<pre>' . htmlentities('<allow-intent href="fb:*" />') . '</pre>';
$content .= '<h5>plugin</h5>';
$content .= '<ul>';
$content .= '<li>cordova-plugin-whitelist</li>';
$content .= '<li>cordova-plugin-inappbrowser</li>';
$content .= '</ul>';


$content .= '<hr/>';
$content .= '<h4>Twitter</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<button run-app-twitter message="Your Message" class="button button-calm icon-left ion-social-twitter">Twitter</button>') . '</pre>';
$content .= '<h5>config.xml</h5>';
$content .= '<pre>' . htmlentities('<allow-intent href="twitter:*" />') . '</pre>';
$content .= '<h5>plugin</h5>';
$content .= '<ul>';
$content .= '<li>cordova-plugin-whitelist</li>';
$content .= '<li>cordova-plugin-inappbrowser</li>';
$content .= '</ul>';

$content .= '<hr/>';
$content .= '<h4>Social Sharing</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<button run-social-sharing message="Your Message" class="button button-calm icon-left">Share This</button>') . '</pre>';
$content .= '<h5>config.xml</h5>';
$content .= '<pre>' . htmlentities('<allow-intent href="whatsapp:*" />') . '</pre>';
$content .= '<pre>' . htmlentities('<allow-intent href="line:*" />') . '</pre>';
$content .= '<pre>' . htmlentities('<allow-intent href="twitter:*" />') . '</pre>';
$content .= '<h5>plugin</h5>';
$content .= '<ul>';
$content .= '<li>cordova-plugin-whitelist</li>';
$content .= '<li>cordova-plugin-inappbrowser</li>';
$content .= '</ul>';


$content .= '<hr/>';
$content .= '<h4>Open URL</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<a run-open-url href="http://yourlink">Link</a>') . '</pre>';


$content .= '<hr/>';
$content .= '<h4>GEO Location</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<a run-app-geo href="geo:78,454">Link</a>') . '</pre>';



$content .= '<hr/>';
$content .= '<h4>App Browser</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<button run-app-browser href="http://yourlink">Link</button>') . '</pre>';

$content .= '<hr/>';
$content .= '<h4>Webview</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<button run-webview href="http://yourlink">Link</button>') . '</pre>';

$content .= '<hr/>';
$content .= '<h4>Zoom Image</h4>';
$content .= '<h5>code</h5>';
$content .= '<pre class="html">' . htmlentities('<img zoom-view src="thumbnail.jpg" zoom-src="large.jpg" />') . '</pre>';

$content .= '<hr/>';
$content .= '<h4>Date Picker</h4>';
$content .= '<h5>code</h5>';

$content .= '<pre class="html">' . htmlentities('<div class="item" ion-datetime-picker ng-model="datetimeValue">Date: {{datetimeValue| date: "yyyy-MM-dd H:mm:ss"}}</div>') . '</pre>';



$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';

$template->demo_url = $out_path . '/www/';
$template->title = $template->base_title . ' | ' . 'Helper Tools -&raquo; Code Docs';
$template->base_desc = 'Docs';
$template->content = $content;
$template->footer = '';
$template->emulator = false;

?>