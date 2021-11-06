<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */


if(!defined('JSM_EXEC'))
{
    die(':)');
}

if(isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}
$out_path = 'output/'.$file_name;

if(!file_exists($out_path.'/resources/android/icon/drawable-hdpi-icon.png'))
{
    @mkdir($out_path.'/resources/android/icon',0777,true);
    @mkdir($out_path.'/resources/android/splash',0777,true);
    @mkdir($out_path.'/resources/ios/icon',0777,true);
    @mkdir($out_path.'/resources/ios/splash',0777,true);
}

if(!file_exists($out_path.'/www/res/icon/android/ldpi-icon.png'))
{
    @mkdir($out_path.'/www/res/icon/android',0777,true);
    @mkdir($out_path.'/www/res/icon/ios',0777,true);
    @mkdir($out_path.'/www/res/screen/android',0777,true);
    @mkdir($out_path.'/www/res/screen/ios',0777,true);
}

if(!file_exists($out_path.'/www/data/images/header'))
{
    @mkdir($out_path.'/www/data/images/header',0777,true);
}

// TODO: LIST FONTAWESOME CODE
$fa_icons = array(
    "500px" => array('code' => '&#xf26e;'),
    "adjust" => array('code' => '&#xf042;'),
    "adn" => array('code' => '&#xf170;'),
    "align-center" => array('code' => '&#xf037;'),
    "align-justify" => array('code' => '&#xf039;'),
    "align-left" => array('code' => '&#xf036;'),
    "align-right" => array('code' => '&#xf038;'),
    "amazon" => array('code' => '&#xf270;'),
    "ambulance" => array('code' => '&#xf0f9;'),
    "anchor" => array('code' => '&#xf13d;'),
    "android" => array('code' => '&#xf17b;'),
    "angellist" => array('code' => '&#xf209;'),
    "angle-double-down" => array('code' => '&#xf103;'),
    "angle-double-left" => array('code' => '&#xf100;'),
    "angle-double-right" => array('code' => '&#xf101;'),
    "angle-double-up" => array('code' => '&#xf102;'),
    "angle-down" => array('code' => '&#xf107;'),
    "angle-left" => array('code' => '&#xf104;'),
    "angle-right" => array('code' => '&#xf105;'),
    "angle-up" => array('code' => '&#xf106;'),
    "apple" => array('code' => '&#xf179;'),
    "archive" => array('code' => '&#xf187;'),
    "area-chart" => array('code' => '&#xf1fe;'),
    "arrow-circle-down" => array('code' => '&#xf0ab;'),
    "arrow-circle-left" => array('code' => '&#xf0a8;'),
    "arrow-circle-o-down" => array('code' => '&#xf01a;'),
    "arrow-circle-o-left" => array('code' => '&#xf190;'),
    "arrow-circle-o-right" => array('code' => '&#xf18e;'),
    "arrow-circle-o-up" => array('code' => '&#xf01b;'),
    "arrow-circle-right" => array('code' => '&#xf0a9;'),
    "arrow-circle-up" => array('code' => '&#xf0aa;'),
    "arrow-down" => array('code' => '&#xf063;'),
    "arrow-left" => array('code' => '&#xf060;'),
    "arrow-right" => array('code' => '&#xf061;'),
    "arrow-up" => array('code' => '&#xf062;'),
    "arrows" => array('code' => '&#xf047;'),
    "arrows-alt" => array('code' => '&#xf0b2;'),
    "arrows-h" => array('code' => '&#xf07e;'),
    "arrows-v" => array('code' => '&#xf07d;'),
    "asterisk" => array('code' => '&#xf069;'),
    "at" => array('code' => '&#xf1fa;'),
    "automobile" => array('code' => '&#xf1b9;'),
    "backward" => array('code' => '&#xf04a;'),
    "balance-scale" => array('code' => '&#xf24e;'),
    "ban" => array('code' => '&#xf05e;'),
    "bank" => array('code' => '&#xf19c;'),
    "bar-chart" => array('code' => '&#xf080;'),
    "bar-chart-o" => array('code' => '&#xf080;'),
    "barcode" => array('code' => '&#xf02a;'),
    "bars" => array('code' => '&#xf0c9;'),
    "battery-0" => array('code' => '&#xf244;'),
    "battery-1" => array('code' => '&#xf243;'),
    "battery-2" => array('code' => '&#xf242;'),
    "battery-3" => array('code' => '&#xf241;'),
    "battery-4" => array('code' => '&#xf240;'),
    "battery-empty" => array('code' => '&#xf244;'),
    "battery-full" => array('code' => '&#xf240;'),
    "battery-half" => array('code' => '&#xf242;'),
    "battery-quarter" => array('code' => '&#xf243;'),
    "battery-three-quarters" => array('code' => '&#xf241;'),
    "bed" => array('code' => '&#xf236;'),
    "beer" => array('code' => '&#xf0fc;'),
    "behance" => array('code' => '&#xf1b4;'),
    "behance-square" => array('code' => '&#xf1b5;'),
    "bell" => array('code' => '&#xf0f3;'),
    "bell-o" => array('code' => '&#xf0a2;'),
    "bell-slash" => array('code' => '&#xf1f6;'),
    "bell-slash-o" => array('code' => '&#xf1f7;'),
    "bicycle" => array('code' => '&#xf206;'),
    "binoculars" => array('code' => '&#xf1e5;'),
    "birthday-cake" => array('code' => '&#xf1fd;'),
    "bitbucket" => array('code' => '&#xf171;'),
    "bitbucket-square" => array('code' => '&#xf172;'),
    "bitcoin" => array('code' => '&#xf15a;'),
    "black-tie" => array('code' => '&#xf27e;'),
    "bluetooth" => array('code' => '&#xf293;'),
    "bluetooth-b" => array('code' => '&#xf294;'),
    "bold" => array('code' => '&#xf032;'),
    "bolt" => array('code' => '&#xf0e7;'),
    "bomb" => array('code' => '&#xf1e2;'),
    "book" => array('code' => '&#xf02d;'),
    "bookmark" => array('code' => '&#xf02e;'),
    "bookmark-o" => array('code' => '&#xf097;'),
    "briefcase" => array('code' => '&#xf0b1;'),
    "btc" => array('code' => '&#xf15a;'),
    "bug" => array('code' => '&#xf188;'),
    "building" => array('code' => '&#xf1ad;'),
    "building-o" => array('code' => '&#xf0f7;'),
    "bullhorn" => array('code' => '&#xf0a1;'),
    "bullseye" => array('code' => '&#xf140;'),
    "bus" => array('code' => '&#xf207;'),
    "buysellads" => array('code' => '&#xf20d;'),
    "cab" => array('code' => '&#xf1ba;'),
    "calculator" => array('code' => '&#xf1ec;'),
    "calendar" => array('code' => '&#xf073;'),
    "calendar-check-o" => array('code' => '&#xf274;'),
    "calendar-minus-o" => array('code' => '&#xf272;'),
    "calendar-o" => array('code' => '&#xf133;'),
    "calendar-plus-o" => array('code' => '&#xf271;'),
    "calendar-times-o" => array('code' => '&#xf273;'),
    "camera" => array('code' => '&#xf030;'),
    "camera-retro" => array('code' => '&#xf083;'),
    "car" => array('code' => '&#xf1b9;'),
    "caret-down" => array('code' => '&#xf0d7;'),
    "caret-left" => array('code' => '&#xf0d9;'),
    "caret-right" => array('code' => '&#xf0da;'),
    "caret-square-o-down" => array('code' => '&#xf150;'),
    "caret-square-o-left" => array('code' => '&#xf191;'),
    "caret-square-o-right" => array('code' => '&#xf152;'),
    "caret-square-o-up" => array('code' => '&#xf151;'),
    "caret-up" => array('code' => '&#xf0d8;'),
    "cart-arrow-down" => array('code' => '&#xf218;'),
    "cart-plus" => array('code' => '&#xf217;'),
    "cc" => array('code' => '&#xf20a;'),
    "cc-amex" => array('code' => '&#xf1f3;'),
    "cc-diners-club" => array('code' => '&#xf24c;'),
    "cc-discover" => array('code' => '&#xf1f2;'),
    "cc-jcb" => array('code' => '&#xf24b;'),
    "cc-mastercard" => array('code' => '&#xf1f1;'),
    "cc-paypal" => array('code' => '&#xf1f4;'),
    "cc-stripe" => array('code' => '&#xf1f5;'),
    "cc-visa" => array('code' => '&#xf1f0;'),
    "certificate" => array('code' => '&#xf0a3;'),
    "chain" => array('code' => '&#xf0c1;'),
    "chain-broken" => array('code' => '&#xf127;'),
    "check" => array('code' => '&#xf00c;'),
    "check-circle" => array('code' => '&#xf058;'),
    "check-circle-o" => array('code' => '&#xf05d;'),
    "check-square" => array('code' => '&#xf14a;'),
    "check-square-o" => array('code' => '&#xf046;'),
    "chevron-circle-down" => array('code' => '&#xf13a;'),
    "chevron-circle-left" => array('code' => '&#xf137;'),
    "chevron-circle-right" => array('code' => '&#xf138;'),
    "chevron-circle-up" => array('code' => '&#xf139;'),
    "chevron-down" => array('code' => '&#xf078;'),
    "chevron-left" => array('code' => '&#xf053;'),
    "chevron-right" => array('code' => '&#xf054;'),
    "chevron-up" => array('code' => '&#xf077;'),
    "child" => array('code' => '&#xf1ae;'),
    "chrome" => array('code' => '&#xf268;'),
    "circle" => array('code' => '&#xf111;'),
    "circle-o" => array('code' => '&#xf10c;'),
    "circle-o-notch" => array('code' => '&#xf1ce;'),
    "circle-thin" => array('code' => '&#xf1db;'),
    "clipboard" => array('code' => '&#xf0ea;'),
    "clock-o" => array('code' => '&#xf017;'),
    "clone" => array('code' => '&#xf24d;'),
    "close" => array('code' => '&#xf00d;'),
    "cloud" => array('code' => '&#xf0c2;'),
    "cloud-download" => array('code' => '&#xf0ed;'),
    "cloud-upload" => array('code' => '&#xf0ee;'),
    "cny" => array('code' => '&#xf157;'),
    "code" => array('code' => '&#xf121;'),
    "code-fork" => array('code' => '&#xf126;'),
    "codepen" => array('code' => '&#xf1cb;'),
    "codiepie" => array('code' => '&#xf284;'),
    "coffee" => array('code' => '&#xf0f4;'),
    "cog" => array('code' => '&#xf013;'),
    "cogs" => array('code' => '&#xf085;'),
    "columns" => array('code' => '&#xf0db;'),
    "comment" => array('code' => '&#xf075;'),
    "comment-o" => array('code' => '&#xf0e5;'),
    "commenting" => array('code' => '&#xf27a;'),
    "commenting-o" => array('code' => '&#xf27b;'),
    "comments" => array('code' => '&#xf086;'),
    "comments-o" => array('code' => '&#xf0e6;'),
    "compass" => array('code' => '&#xf14e;'),
    "compress" => array('code' => '&#xf066;'),
    "connectdevelop" => array('code' => '&#xf20e;'),
    "contao" => array('code' => '&#xf26d;'),
    "copy" => array('code' => '&#xf0c5;'),
    "copyright" => array('code' => '&#xf1f9;'),
    "creative-commons" => array('code' => '&#xf25e;'),
    "credit-card" => array('code' => '&#xf09d;'),
    "credit-card-alt" => array('code' => '&#xf283;'),
    "crop" => array('code' => '&#xf125;'),
    "crosshairs" => array('code' => '&#xf05b;'),
    "css3" => array('code' => '&#xf13c;'),
    "cube" => array('code' => '&#xf1b2;'),
    "cubes" => array('code' => '&#xf1b3;'),
    "cut" => array('code' => '&#xf0c4;'),
    "cutlery" => array('code' => '&#xf0f5;'),
    "dashboard" => array('code' => '&#xf0e4;'),
    "dashcube" => array('code' => '&#xf210;'),
    "database" => array('code' => '&#xf1c0;'),
    "dedent" => array('code' => '&#xf03b;'),
    "delicious" => array('code' => '&#xf1a5;'),
    "desktop" => array('code' => '&#xf108;'),
    "deviantart" => array('code' => '&#xf1bd;'),
    "diamond" => array('code' => '&#xf219;'),
    "digg" => array('code' => '&#xf1a6;'),
    "dollar" => array('code' => '&#xf155;'),
    "dot-circle-o" => array('code' => '&#xf192;'),
    "download" => array('code' => '&#xf019;'),
    "dribbble" => array('code' => '&#xf17d;'),
    "dropbox" => array('code' => '&#xf16b;'),
    "drupal" => array('code' => '&#xf1a9;'),
    "edge" => array('code' => '&#xf282;'),
    "edit" => array('code' => '&#xf044;'),
    "eject" => array('code' => '&#xf052;'),
    "ellipsis-h" => array('code' => '&#xf141;'),
    "ellipsis-v" => array('code' => '&#xf142;'),
    "empire" => array('code' => '&#xf1d1;'),
    "envelope" => array('code' => '&#xf0e0;'),
    "envelope-o" => array('code' => '&#xf003;'),
    "envelope-square" => array('code' => '&#xf199;'),
    "eraser" => array('code' => '&#xf12d;'),
    "eur" => array('code' => '&#xf153;'),
    "euro" => array('code' => '&#xf153;'),
    "exchange" => array('code' => '&#xf0ec;'),
    "exclamation" => array('code' => '&#xf12a;'),
    "exclamation-circle" => array('code' => '&#xf06a;'),
    "exclamation-triangle" => array('code' => '&#xf071;'),
    "expand" => array('code' => '&#xf065;'),
    "expeditedssl" => array('code' => '&#xf23e;'),
    "external-link" => array('code' => '&#xf08e;'),
    "external-link-square" => array('code' => '&#xf14c;'),
    "eye" => array('code' => '&#xf06e;'),
    "eye-slash" => array('code' => '&#xf070;'),
    "eyedropper" => array('code' => '&#xf1fb;'),
    "facebook" => array('code' => '&#xf09a;'),
    "facebook-f" => array('code' => '&#xf09a;'),
    "facebook-official" => array('code' => '&#xf230;'),
    "facebook-square" => array('code' => '&#xf082;'),
    "fast-backward" => array('code' => '&#xf049;'),
    "fast-forward" => array('code' => '&#xf050;'),
    "fax" => array('code' => '&#xf1ac;'),
    "feed" => array('code' => '&#xf09e;'),
    "female" => array('code' => '&#xf182;'),
    "fighter-jet" => array('code' => '&#xf0fb;'),
    "file" => array('code' => '&#xf15b;'),
    "file-archive-o" => array('code' => '&#xf1c6;'),
    "file-audio-o" => array('code' => '&#xf1c7;'),
    "file-code-o" => array('code' => '&#xf1c9;'),
    "file-excel-o" => array('code' => '&#xf1c3;'),
    "file-image-o" => array('code' => '&#xf1c5;'),
    "file-movie-o" => array('code' => '&#xf1c8;'),
    "file-o" => array('code' => '&#xf016;'),
    "file-pdf-o" => array('code' => '&#xf1c1;'),
    "file-photo-o" => array('code' => '&#xf1c5;'),
    "file-picture-o" => array('code' => '&#xf1c5;'),
    "file-powerpoint-o" => array('code' => '&#xf1c4;'),
    "file-sound-o" => array('code' => '&#xf1c7;'),
    "file-text" => array('code' => '&#xf15c;'),
    "file-text-o" => array('code' => '&#xf0f6;'),
    "file-video-o" => array('code' => '&#xf1c8;'),
    "file-word-o" => array('code' => '&#xf1c2;'),
    "file-zip-o" => array('code' => '&#xf1c6;'),
    "files-o" => array('code' => '&#xf0c5;'),
    "film" => array('code' => '&#xf008;'),
    "filter" => array('code' => '&#xf0b0;'),
    "fire" => array('code' => '&#xf06d;'),
    "fire-extinguisher" => array('code' => '&#xf134;'),
    "firefox" => array('code' => '&#xf269;'),
    "flag" => array('code' => '&#xf024;'),
    "flag-checkered" => array('code' => '&#xf11e;'),
    "flag-o" => array('code' => '&#xf11d;'),
    "flash" => array('code' => '&#xf0e7;'),
    "flask" => array('code' => '&#xf0c3;'),
    "flickr" => array('code' => '&#xf16e;'),
    "floppy-o" => array('code' => '&#xf0c7;'),
    "folder" => array('code' => '&#xf07b;'),
    "folder-o" => array('code' => '&#xf114;'),
    "folder-open" => array('code' => '&#xf07c;'),
    "folder-open-o" => array('code' => '&#xf115;'),
    "font" => array('code' => '&#xf031;'),
    "fonticons" => array('code' => '&#xf280;'),
    "fort-awesome" => array('code' => '&#xf286;'),
    "forumbee" => array('code' => '&#xf211;'),
    "forward" => array('code' => '&#xf04e;'),
    "foursquare" => array('code' => '&#xf180;'),
    "frown-o" => array('code' => '&#xf119;'),
    "futbol-o" => array('code' => '&#xf1e3;'),
    "gamepad" => array('code' => '&#xf11b;'),
    "gavel" => array('code' => '&#xf0e3;'),
    "gbp" => array('code' => '&#xf154;'),
    "ge" => array('code' => '&#xf1d1;'),
    "gear" => array('code' => '&#xf013;'),
    "gears" => array('code' => '&#xf085;'),
    "genderless" => array('code' => '&#xf22d;'),
    "get-pocket" => array('code' => '&#xf265;'),
    "gg" => array('code' => '&#xf260;'),
    "gg-circle" => array('code' => '&#xf261;'),
    "gift" => array('code' => '&#xf06b;'),
    "git" => array('code' => '&#xf1d3;'),
    "git-square" => array('code' => '&#xf1d2;'),
    "github" => array('code' => '&#xf09b;'),
    "github-alt" => array('code' => '&#xf113;'),
    "github-square" => array('code' => '&#xf092;'),
    "gittip" => array('code' => '&#xf184;'),
    "glass" => array('code' => '&#xf000;'),
    "globe" => array('code' => '&#xf0ac;'),
    "google" => array('code' => '&#xf1a0;'),
    "google-plus" => array('code' => '&#xf0d5;'),
    "google-plus-square" => array('code' => '&#xf0d4;'),
    "google-wallet" => array('code' => '&#xf1ee;'),
    "graduation-cap" => array('code' => '&#xf19d;'),
    "gratipay" => array('code' => '&#xf184;'),
    "group" => array('code' => '&#xf0c0;'),
    "h-square" => array('code' => '&#xf0fd;'),
    "hacker-news" => array('code' => '&#xf1d4;'),
    "hand-grab-o" => array('code' => '&#xf255;'),
    "hand-lizard-o" => array('code' => '&#xf258;'),
    "hand-o-down" => array('code' => '&#xf0a7;'),
    "hand-o-left" => array('code' => '&#xf0a5;'),
    "hand-o-right" => array('code' => '&#xf0a4;'),
    "hand-o-up" => array('code' => '&#xf0a6;'),
    "hand-paper-o" => array('code' => '&#xf256;'),
    "hand-peace-o" => array('code' => '&#xf25b;'),
    "hand-pointer-o" => array('code' => '&#xf25a;'),
    "hand-rock-o" => array('code' => '&#xf255;'),
    "hand-scissors-o" => array('code' => '&#xf257;'),
    "hand-spock-o" => array('code' => '&#xf259;'),
    "hand-stop-o" => array('code' => '&#xf256;'),
    "hashtag" => array('code' => '&#xf292;'),
    "hdd-o" => array('code' => '&#xf0a0;'),
    "header" => array('code' => '&#xf1dc;'),
    "headphones" => array('code' => '&#xf025;'),
    "heart" => array('code' => '&#xf004;'),
    "heart-o" => array('code' => '&#xf08a;'),
    "heartbeat" => array('code' => '&#xf21e;'),
    "history" => array('code' => '&#xf1da;'),
    "home" => array('code' => '&#xf015;'),
    "hospital-o" => array('code' => '&#xf0f8;'),
    "hotel" => array('code' => '&#xf236;'),
    "hourglass" => array('code' => '&#xf254;'),
    "hourglass-1" => array('code' => '&#xf251;'),
    "hourglass-2" => array('code' => '&#xf252;'),
    "hourglass-3" => array('code' => '&#xf253;'),
    "hourglass-end" => array('code' => '&#xf253;'),
    "hourglass-half" => array('code' => '&#xf252;'),
    "hourglass-o" => array('code' => '&#xf250;'),
    "hourglass-start" => array('code' => '&#xf251;'),
    "houzz" => array('code' => '&#xf27c;'),
    "html5" => array('code' => '&#xf13b;'),
    "i-cursor" => array('code' => '&#xf246;'),
    "ils" => array('code' => '&#xf20b;'),
    "image" => array('code' => '&#xf03e;'),
    "inbox" => array('code' => '&#xf01c;'),
    "indent" => array('code' => '&#xf03c;'),
    "industry" => array('code' => '&#xf275;'),
    "info" => array('code' => '&#xf129;'),
    "info-circle" => array('code' => '&#xf05a;'),
    "inr" => array('code' => '&#xf156;'),
    "instagram" => array('code' => '&#xf16d;'),
    "institution" => array('code' => '&#xf19c;'),
    "internet-explorer" => array('code' => '&#xf26b;'),
    "intersex" => array('code' => '&#xf224;'),
    "ioxhost" => array('code' => '&#xf208;'),
    "italic" => array('code' => '&#xf033;'),
    "joomla" => array('code' => '&#xf1aa;'),
    "jpy" => array('code' => '&#xf157;'),
    "jsfiddle" => array('code' => '&#xf1cc;'),
    "key" => array('code' => '&#xf084;'),
    "keyboard-o" => array('code' => '&#xf11c;'),
    "krw" => array('code' => '&#xf159;'),
    "language" => array('code' => '&#xf1ab;'),
    "laptop" => array('code' => '&#xf109;'),
    "lastfm" => array('code' => '&#xf202;'),
    "lastfm-square" => array('code' => '&#xf203;'),
    "leaf" => array('code' => '&#xf06c;'),
    "leanpub" => array('code' => '&#xf212;'),
    "legal" => array('code' => '&#xf0e3;'),
    "lemon-o" => array('code' => '&#xf094;'),
    "level-down" => array('code' => '&#xf149;'),
    "level-up" => array('code' => '&#xf148;'),
    "life-bouy" => array('code' => '&#xf1cd;'),
    "life-buoy" => array('code' => '&#xf1cd;'),
    "life-ring" => array('code' => '&#xf1cd;'),
    "life-saver" => array('code' => '&#xf1cd;'),
    "lightbulb-o" => array('code' => '&#xf0eb;'),
    "line-chart" => array('code' => '&#xf201;'),
    "link" => array('code' => '&#xf0c1;'),
    "linkedin" => array('code' => '&#xf0e1;'),
    "linkedin-square" => array('code' => '&#xf08c;'),
    "linux" => array('code' => '&#xf17c;'),
    "list" => array('code' => '&#xf03a;'),
    "list-alt" => array('code' => '&#xf022;'),
    "list-ol" => array('code' => '&#xf0cb;'),
    "list-ul" => array('code' => '&#xf0ca;'),
    "location-arrow" => array('code' => '&#xf124;'),
    "lock" => array('code' => '&#xf023;'),
    "long-arrow-down" => array('code' => '&#xf175;'),
    "long-arrow-left" => array('code' => '&#xf177;'),
    "long-arrow-right" => array('code' => '&#xf178;'),
    "long-arrow-up" => array('code' => '&#xf176;'),
    "magic" => array('code' => '&#xf0d0;'),
    "magnet" => array('code' => '&#xf076;'),
    "mail-forward" => array('code' => '&#xf064;'),
    "mail-reply" => array('code' => '&#xf112;'),
    "mail-reply-all" => array('code' => '&#xf122;'),
    "male" => array('code' => '&#xf183;'),
    "map" => array('code' => '&#xf279;'),
    "map-marker" => array('code' => '&#xf041;'),
    "map-o" => array('code' => '&#xf278;'),
    "map-pin" => array('code' => '&#xf276;'),
    "map-signs" => array('code' => '&#xf277;'),
    "mars" => array('code' => '&#xf222;'),
    "mars-double" => array('code' => '&#xf227;'),
    "mars-stroke" => array('code' => '&#xf229;'),
    "mars-stroke-h" => array('code' => '&#xf22b;'),
    "mars-stroke-v" => array('code' => '&#xf22a;'),
    "maxcdn" => array('code' => '&#xf136;'),
    "meanpath" => array('code' => '&#xf20c;'),
    "medium" => array('code' => '&#xf23a;'),
    "medkit" => array('code' => '&#xf0fa;'),
    "meh-o" => array('code' => '&#xf11a;'),
    "mercury" => array('code' => '&#xf223;'),
    "microphone" => array('code' => '&#xf130;'),
    "microphone-slash" => array('code' => '&#xf131;'),
    "minus" => array('code' => '&#xf068;'),
    "minus-circle" => array('code' => '&#xf056;'),
    "minus-square" => array('code' => '&#xf146;'),
    "minus-square-o" => array('code' => '&#xf147;'),
    "mixcloud" => array('code' => '&#xf289;'),
    "mobile" => array('code' => '&#xf10b;'),
    "mobile-phone" => array('code' => '&#xf10b;'),
    "modx" => array('code' => '&#xf285;'),
    "money" => array('code' => '&#xf0d6;'),
    "moon-o" => array('code' => '&#xf186;'),
    "mortar-board" => array('code' => '&#xf19d;'),
    "motorcycle" => array('code' => '&#xf21c;'),
    "mouse-pointer" => array('code' => '&#xf245;'),
    "music" => array('code' => '&#xf001;'),
    "navicon" => array('code' => '&#xf0c9;'),
    "neuter" => array('code' => '&#xf22c;'),
    "newspaper-o" => array('code' => '&#xf1ea;'),
    "object-group" => array('code' => '&#xf247;'),
    "object-ungroup" => array('code' => '&#xf248;'),
    "odnoklassniki" => array('code' => '&#xf263;'),
    "odnoklassniki-square" => array('code' => '&#xf264;'),
    "opencart" => array('code' => '&#xf23d;'),
    "openid" => array('code' => '&#xf19b;'),
    "opera" => array('code' => '&#xf26a;'),
    "optin-monster" => array('code' => '&#xf23c;'),
    "outdent" => array('code' => '&#xf03b;'),
    "pagelines" => array('code' => '&#xf18c;'),
    "paint-brush" => array('code' => '&#xf1fc;'),
    "paper-plane" => array('code' => '&#xf1d8;'),
    "paper-plane-o" => array('code' => '&#xf1d9;'),
    "paperclip" => array('code' => '&#xf0c6;'),
    "paragraph" => array('code' => '&#xf1dd;'),
    "paste" => array('code' => '&#xf0ea;'),
    "pause" => array('code' => '&#xf04c;'),
    "pause-circle" => array('code' => '&#xf28b;'),
    "pause-circle-o" => array('code' => '&#xf28c;'),
    "paw" => array('code' => '&#xf1b0;'),
    "paypal" => array('code' => '&#xf1ed;'),
    "pencil" => array('code' => '&#xf040;'),
    "pencil-square" => array('code' => '&#xf14b;'),
    "pencil-square-o" => array('code' => '&#xf044;'),
    "percent" => array('code' => '&#xf295;'),
    "phone" => array('code' => '&#xf095;'),
    "phone-square" => array('code' => '&#xf098;'),
    "photo" => array('code' => '&#xf03e;'),
    "picture-o" => array('code' => '&#xf03e;'),
    "pie-chart" => array('code' => '&#xf200;'),
    "pied-piper" => array('code' => '&#xf1a7;'),
    "pied-piper-alt" => array('code' => '&#xf1a8;'),
    "pinterest" => array('code' => '&#xf0d2;'),
    "pinterest-p" => array('code' => '&#xf231;'),
    "pinterest-square" => array('code' => '&#xf0d3;'),
    "plane" => array('code' => '&#xf072;'),
    "play" => array('code' => '&#xf04b;'),
    "play-circle" => array('code' => '&#xf144;'),
    "play-circle-o" => array('code' => '&#xf01d;'),
    "plug" => array('code' => '&#xf1e6;'),
    "plus" => array('code' => '&#xf067;'),
    "plus-circle" => array('code' => '&#xf055;'),
    "plus-square" => array('code' => '&#xf0fe;'),
    "plus-square-o" => array('code' => '&#xf196;'),
    "power-off" => array('code' => '&#xf011;'),
    "print" => array('code' => '&#xf02f;'),
    "product-hunt" => array('code' => '&#xf288;'),
    "puzzle-piece" => array('code' => '&#xf12e;'),
    "qq" => array('code' => '&#xf1d6;'),
    "qrcode" => array('code' => '&#xf029;'),
    "question" => array('code' => '&#xf128;'),
    "question-circle" => array('code' => '&#xf059;'),
    "quote-left" => array('code' => '&#xf10d;'),
    "quote-right" => array('code' => '&#xf10e;'),
    "ra" => array('code' => '&#xf1d0;'),
    "random" => array('code' => '&#xf074;'),
    "rebel" => array('code' => '&#xf1d0;'),
    "recycle" => array('code' => '&#xf1b8;'),
    "reddit" => array('code' => '&#xf1a1;'),
    "reddit-alien" => array('code' => '&#xf281;'),
    "reddit-square" => array('code' => '&#xf1a2;'),
    "refresh" => array('code' => '&#xf021;'),
    "registered" => array('code' => '&#xf25d;'),
    "remove" => array('code' => '&#xf00d;'),
    "renren" => array('code' => '&#xf18b;'),
    "reorder" => array('code' => '&#xf0c9;'),
    "repeat" => array('code' => '&#xf01e;'),
    "reply" => array('code' => '&#xf112;'),
    "reply-all" => array('code' => '&#xf122;'),
    "retweet" => array('code' => '&#xf079;'),
    "rmb" => array('code' => '&#xf157;'),
    "road" => array('code' => '&#xf018;'),
    "rocket" => array('code' => '&#xf135;'),
    "rotate-left" => array('code' => '&#xf0e2;'),
    "rotate-right" => array('code' => '&#xf01e;'),
    "rouble" => array('code' => '&#xf158;'),
    "rss" => array('code' => '&#xf09e;'),
    "rss-square" => array('code' => '&#xf143;'),
    "rub" => array('code' => '&#xf158;'),
    "ruble" => array('code' => '&#xf158;'),
    "rupee" => array('code' => '&#xf156;'),
    "safari" => array('code' => '&#xf267;'),
    "save" => array('code' => '&#xf0c7;'),
    "scissors" => array('code' => '&#xf0c4;'),
    "scribd" => array('code' => '&#xf28a;'),
    "search" => array('code' => '&#xf002;'),
    "search-minus" => array('code' => '&#xf010;'),
    "search-plus" => array('code' => '&#xf00e;'),
    "sellsy" => array('code' => '&#xf213;'),
    "send" => array('code' => '&#xf1d8;'),
    "send-o" => array('code' => '&#xf1d9;'),
    "server" => array('code' => '&#xf233;'),
    "share" => array('code' => '&#xf064;'),
    "share-alt" => array('code' => '&#xf1e0;'),
    "share-alt-square" => array('code' => '&#xf1e1;'),
    "share-square" => array('code' => '&#xf14d;'),
    "share-square-o" => array('code' => '&#xf045;'),
    "shekel" => array('code' => '&#xf20b;'),
    "sheqel" => array('code' => '&#xf20b;'),
    "shield" => array('code' => '&#xf132;'),
    "ship" => array('code' => '&#xf21a;'),
    "shirtsinbulk" => array('code' => '&#xf214;'),
    "shopping-bag" => array('code' => '&#xf290;'),
    "shopping-basket" => array('code' => '&#xf291;'),
    "shopping-cart" => array('code' => '&#xf07a;'),
    "sign-in" => array('code' => '&#xf090;'),
    "sign-out" => array('code' => '&#xf08b;'),
    "signal" => array('code' => '&#xf012;'),
    "simplybuilt" => array('code' => '&#xf215;'),
    "sitemap" => array('code' => '&#xf0e8;'),
    "skyatlas" => array('code' => '&#xf216;'),
    "skype" => array('code' => '&#xf17e;'),
    "slack" => array('code' => '&#xf198;'),
    "sliders" => array('code' => '&#xf1de;'),
    "slideshare" => array('code' => '&#xf1e7;'),
    "smile-o" => array('code' => '&#xf118;'),
    "soccer-ball-o" => array('code' => '&#xf1e3;'),
    "sort" => array('code' => '&#xf0dc;'),
    "sort-alpha-asc" => array('code' => '&#xf15d;'),
    "sort-alpha-desc" => array('code' => '&#xf15e;'),
    "sort-amount-asc" => array('code' => '&#xf160;'),
    "sort-amount-desc" => array('code' => '&#xf161;'),
    "sort-asc" => array('code' => '&#xf0de;'),
    "sort-desc" => array('code' => '&#xf0dd;'),
    "sort-down" => array('code' => '&#xf0dd;'),
    "sort-numeric-asc" => array('code' => '&#xf162;'),
    "sort-numeric-desc" => array('code' => '&#xf163;'),
    "sort-up" => array('code' => '&#xf0de;'),
    "soundcloud" => array('code' => '&#xf1be;'),
    "space-shuttle" => array('code' => '&#xf197;'),
    "spinner" => array('code' => '&#xf110;'),
    "spoon" => array('code' => '&#xf1b1;'),
    "spotify" => array('code' => '&#xf1bc;'),
    "square" => array('code' => '&#xf0c8;'),
    "square-o" => array('code' => '&#xf096;'),
    "stack-exchange" => array('code' => '&#xf18d;'),
    "stack-overflow" => array('code' => '&#xf16c;'),
    "star" => array('code' => '&#xf005;'),
    "star-half" => array('code' => '&#xf089;'),
    "star-half-empty" => array('code' => '&#xf123;'),
    "star-half-full" => array('code' => '&#xf123;'),
    "star-half-o" => array('code' => '&#xf123;'),
    "star-o" => array('code' => '&#xf006;'),
    "steam" => array('code' => '&#xf1b6;'),
    "steam-square" => array('code' => '&#xf1b7;'),
    "step-backward" => array('code' => '&#xf048;'),
    "step-forward" => array('code' => '&#xf051;'),
    "stethoscope" => array('code' => '&#xf0f1;'),
    "sticky-note" => array('code' => '&#xf249;'),
    "sticky-note-o" => array('code' => '&#xf24a;'),
    "stop" => array('code' => '&#xf04d;'),
    "stop-circle" => array('code' => '&#xf28d;'),
    "stop-circle-o" => array('code' => '&#xf28e;'),
    "street-view" => array('code' => '&#xf21d;'),
    "strikethrough" => array('code' => '&#xf0cc;'),
    "stumbleupon" => array('code' => '&#xf1a4;'),
    "stumbleupon-circle" => array('code' => '&#xf1a3;'),
    "subscript" => array('code' => '&#xf12c;'),
    "subway" => array('code' => '&#xf239;'),
    "suitcase" => array('code' => '&#xf0f2;'),
    "sun-o" => array('code' => '&#xf185;'),
    "superscript" => array('code' => '&#xf12b;'),
    "support" => array('code' => '&#xf1cd;'),
    "table" => array('code' => '&#xf0ce;'),
    "tablet" => array('code' => '&#xf10a;'),
    "tachometer" => array('code' => '&#xf0e4;'),
    "tag" => array('code' => '&#xf02b;'),
    "tags" => array('code' => '&#xf02c;'),
    "tasks" => array('code' => '&#xf0ae;'),
    "taxi" => array('code' => '&#xf1ba;'),
    "television" => array('code' => '&#xf26c;'),
    "tencent-weibo" => array('code' => '&#xf1d5;'),
    "terminal" => array('code' => '&#xf120;'),
    "text-height" => array('code' => '&#xf034;'),
    "text-width" => array('code' => '&#xf035;'),
    "th" => array('code' => '&#xf00a;'),
    "th-large" => array('code' => '&#xf009;'),
    "th-list" => array('code' => '&#xf00b;'),
    "thumb-tack" => array('code' => '&#xf08d;'),
    "thumbs-down" => array('code' => '&#xf165;'),
    "thumbs-o-down" => array('code' => '&#xf088;'),
    "thumbs-o-up" => array('code' => '&#xf087;'),
    "thumbs-up" => array('code' => '&#xf164;'),
    "ticket" => array('code' => '&#xf145;'),
    "times" => array('code' => '&#xf00d;'),
    "times-circle" => array('code' => '&#xf057;'),
    "times-circle-o" => array('code' => '&#xf05c;'),
    "tint" => array('code' => '&#xf043;'),
    "toggle-down" => array('code' => '&#xf150;'),
    "toggle-left" => array('code' => '&#xf191;'),
    "toggle-off" => array('code' => '&#xf204;'),
    "toggle-on" => array('code' => '&#xf205;'),
    "toggle-right" => array('code' => '&#xf152;'),
    "toggle-up" => array('code' => '&#xf151;'),
    "trademark" => array('code' => '&#xf25c;'),
    "train" => array('code' => '&#xf238;'),
    "transgender" => array('code' => '&#xf224;'),
    "transgender-alt" => array('code' => '&#xf225;'),
    "trash" => array('code' => '&#xf1f8;'),
    "trash-o" => array('code' => '&#xf014;'),
    "tree" => array('code' => '&#xf1bb;'),
    "trello" => array('code' => '&#xf181;'),
    "tripadvisor" => array('code' => '&#xf262;'),
    "trophy" => array('code' => '&#xf091;'),
    "truck" => array('code' => '&#xf0d1;'),
    "try" => array('code' => '&#xf195;'),
    "tty" => array('code' => '&#xf1e4;'),
    "tumblr" => array('code' => '&#xf173;'),
    "tumblr-square" => array('code' => '&#xf174;'),
    "turkish-lira" => array('code' => '&#xf195;'),
    "tv" => array('code' => '&#xf26c;'),
    "twitch" => array('code' => '&#xf1e8;'),
    "twitter" => array('code' => '&#xf099;'),
    "twitter-square" => array('code' => '&#xf081;'),
    "umbrella" => array('code' => '&#xf0e9;'),
    "underline" => array('code' => '&#xf0cd;'),
    "undo" => array('code' => '&#xf0e2;'),
    "university" => array('code' => '&#xf19c;'),
    "unlink" => array('code' => '&#xf127;'),
    "unlock" => array('code' => '&#xf09c;'),
    "unlock-alt" => array('code' => '&#xf13e;'),
    "unsorted" => array('code' => '&#xf0dc;'),
    "upload" => array('code' => '&#xf093;'),
    "usb" => array('code' => '&#xf287;'),
    "usd" => array('code' => '&#xf155;'),
    "user" => array('code' => '&#xf007;'),
    "user-md" => array('code' => '&#xf0f0;'),
    "user-plus" => array('code' => '&#xf234;'),
    "user-secret" => array('code' => '&#xf21b;'),
    "user-times" => array('code' => '&#xf235;'),
    "users" => array('code' => '&#xf0c0;'),
    "venus" => array('code' => '&#xf221;'),
    "venus-double" => array('code' => '&#xf226;'),
    "venus-mars" => array('code' => '&#xf228;'),
    "viacoin" => array('code' => '&#xf237;'),
    "video-camera" => array('code' => '&#xf03d;'),
    "vimeo" => array('code' => '&#xf27d;'),
    "vimeo-square" => array('code' => '&#xf194;'),
    "vine" => array('code' => '&#xf1ca;'),
    "vk" => array('code' => '&#xf189;'),
    "volume-down" => array('code' => '&#xf027;'),
    "volume-off" => array('code' => '&#xf026;'),
    "volume-up" => array('code' => '&#xf028;'),
    "warning" => array('code' => '&#xf071;'),
    "wechat" => array('code' => '&#xf1d7;'),
    "weibo" => array('code' => '&#xf18a;'),
    "weixin" => array('code' => '&#xf1d7;'),
    "whatsapp" => array('code' => '&#xf232;'),
    "wheelchair" => array('code' => '&#xf193;'),
    "wifi" => array('code' => '&#xf1eb;'),
    "wikipedia-w" => array('code' => '&#xf266;'),
    "windows" => array('code' => '&#xf17a;'),
    "won" => array('code' => '&#xf159;'),
    "wordpress" => array('code' => '&#xf19a;'),
    "wrench" => array('code' => '&#xf0ad;'),
    "xing" => array('code' => '&#xf168;'),
    "xing-square" => array('code' => '&#xf169;'),
    "y-combinator" => array('code' => '&#xf23b;'),
    "y-combinator-square" => array('code' => '&#xf1d4;'),
    "yahoo" => array('code' => '&#xf19e;'),
    "yc" => array('code' => '&#xf23b;'),
    "yc-square" => array('code' => '&#xf1d4;'),
    "yelp" => array('code' => '&#xf1e9;'),
    "yen" => array('code' => '&#xf157;'),
    "youtube" => array('code' => '&#xf167;'),
    "youtube-play" => array('code' => '&#xf16a;'),
    "youtube-square" => array('code' => '&#xf166;'),
    );

function border_radius($file,$border_radius = 40)
{
    $filename = JSM_PATH.'/output/'.$_SESSION['FILE_NAME'].'/resources/'.$file;
    $size = getimagesize($filename);

    $im_source = imagecreatefrompng($filename);
    imagealphablending($im_source,true);
    imageantialias($im_source,true);
    $h = $w = $size[0];
    $r = ($border_radius / 100) * $w;

    $im = imagecreatetruecolor($w,$h);
    imagealphablending($im,true);
    imageantialias($im,true);
    $red = imagecolorallocate($im,255,0,0);
    $black = imagecolorallocate($im,0,0,0);

    imagefill($im,0,0,$black);


    $values = array(
        $r,
        0,
        ($w - $r),
        0,
        $w,
        $r,
        $w,
        ($h - $r),
        ($w - $r),
        $h,
        $r,
        $h,
        0,
        ($h - $r),
        0,
        $r);


    imagefilledpolygon($im,$values,8,$red);
    imagefilledellipse($im,$r,$r,($r * 2),($r * 2),$red);
    imagefilledellipse($im,($w - $r),$r,($r * 2),($r * 2),$red);
    imagefilledellipse($im,($w - $r),($w - $r),($r * 2),($r * 2),$red);
    imagefilledellipse($im,$r,$w - $r,($r * 2),($r * 2),$red);

    imagecolortransparent($im,$red);


    imagecopymerge($im_source,$im,0,0,0,0,$w,$w,100);

    imagecolortransparent($im_source,$black);
    imagealphablending($im_source,true);
    imageantialias($im_source,true);
    imagepng($im_source,$filename,9);
    imagedestroy($im_source);
    imagedestroy($im);
}
// TODO: FUNCTION CREATE IMAGES
function create_png($width,$height,$path,$bg = '#ffffff',$text = "&#xf1fc;",$left = 0,$top = 0,$style = 1,$str_text = '',$text_left = 0,$text_top = 0)
{


    $outputSize = $width;
    $fontSize = $outputSize;

    $min_size = $height;
    if($width < $height)
    {
        $min_size = $width;
    }
    if($height < $width)
    {
        $min_size = $height;
    }

    if($width == $height)
    {
        $fontSize = (int)($min_size * 0.60);
        $fontX = ($width / 2) - ($fontSize * 0.75) + ($fontSize * ($left / 100));
        $fontY = $height - ($fontSize * 0.30);
    } else
    {
        $fontSize = (int)($min_size * 0.20);
        $fontX = ($width / 2) - ($fontSize * 0.75) + ($fontSize * ($left / 100));
        $fontY = ($height / 2) + ($fontSize * 0.55);
    }


    $fileName = JSM_PATH.'/output/'.$_SESSION['FILE_NAME'].'/resources/'.$path;
    if(!file_exists(JSM_PATH.'/output/'.$_SESSION['FILE_NAME'].'/publishing'))
    {
        mkdir(JSM_PATH.'/output/'.$_SESSION['FILE_NAME'].'/publishing/',0777,true);
    }
    //create image
    $im = imagecreatetruecolor($width,$height);
    imagesavealpha($im,false);

    // Create some colors
    $fontC = imagecolorallocate($im,255,255,255);


    list($r,$g,$b) = sscanf($bg,"#%02x%02x%02x");
    $bgc = imagecolorallocatealpha($im,$r,$g,$b,0);
    imagefilledrectangle($im,0,0,$width,$height,$bgc);
    imagealphablending($im,true);

    $blue = imagecolorallocate($im,$r - 10,$g - 10,$b - 10);

    switch($style)
    {
        case '0':
            break;
        case '1':
            $values = array(
                0,
                0,
                $width / 2,
                $height / 2,
                $width / 4,
                0,
                0,
                0,
                );
            imagefilledpolygon($im,$values,4,$blue);

            $values = array(
                $width / 2,
                0,
                $width / 2,
                $height / 2,
                (($width / 4) * 3),
                0,
                0,
                0,
                );
            imagefilledpolygon($im,$values,4,$blue);


            $values = array(
                $width,
                0,
                $width / 2,
                $height / 2,
                $width,
                $height / 4,
                $width,
                0,
                );
            imagefilledpolygon($im,$values,4,$blue);

            $values = array(
                $width,
                $height / 2,
                $width / 2,
                $height / 2,
                $width,
                (($height / 4) * 3),
                $width,
                0,
                );
            imagefilledpolygon($im,$values,4,$blue);


            $values = array(
                $width,
                $height,
                $width / 2,
                $height / 2,
                ($width / 4) * 3,
                $height,
                $width,
                $height,
                );
            imagefilledpolygon($im,$values,4,$blue);


            $values = array(
                $width / 2,
                $height,
                $width / 2,
                $height / 2,
                ($width / 4),
                $height,
                $width,
                $height,
                );
            imagefilledpolygon($im,$values,4,$blue);

            $values = array(
                0,
                $height,
                $width / 2,
                $height / 2,
                0,
                ($height / 4) * 3,
                0,
                $height,
                );
            imagefilledpolygon($im,$values,4,$blue);


            $values = array(
                0,
                $height / 2,
                $width / 2,
                $height / 2,
                0,
                ($height / 4),
                0,
                $height,
                );
            imagefilledpolygon($im,$values,4,$blue);
            break;
        case '3':
            $values = array(
                0,
                0,
                $width,
                0,

                0,
                $height,
                0,
                0,
                );
            imagefilledpolygon($im,$values,4,$blue);

            break;
    }

    // cari ukuran tengah
    $centerX = $width / 2;
    $centerY = $height / 2;

    // ICON
    $font_icon = JSM_PATH.'/templates/default/vendor/fontawesome/fonts/fontawesome-webfont.ttf';
    $fontB = imagecolorallocate($im,55,55,55);
    // Get size of text
    list($_left,$_bottom,$_right,,,$_top) = imageftbbox($fontSize,0,$font_icon,$text);
    // Determine offset of text
    $left_offset = ($_right - $_left) / 2;
    $top_offset = ($_bottom - $_top) / 2;
    // Generate coordinates
    $x = $centerX - $left_offset;
    $y = $centerY - $top_offset;

    // Add the text
    imagettftext($im,$fontSize,0,$x + $left + 1,$y + $top + $fontSize + 1,$fontB,$font_icon,$text);
    imagettftext($im,$fontSize,0,$x + $left,$y + $top + $fontSize,$fontC,$font_icon,$text);


    $font_text = JSM_PATH.'/templates/default/fonts/FjallaOne.ttf';


    // Get size of text
    list($left,$bottom,$right,,,$top) = imageftbbox(($fontSize / 3),0,$font_text,$str_text);
    // Determine offset of text
    $left_offset = ($right - $left) / 2;
    $top_offset = ($bottom - $top) / 2;
    // Generate coordinates
    $x = $centerX - $left_offset;
    $y = $centerY - $top_offset;

    imagettftext($im,$fontSize / 3,0,$x + $text_left + 1,$y + $text_top + 1 + (($fontSize * 2) - ($fontSize / 2)),$fontB,$font_text,$str_text);
    imagettftext($im,$fontSize / 3,0,$x + $text_left,$y + $text_top + (($fontSize * 2) - ($fontSize / 2)),$fontC,$font_text,$str_text);


    imagepng($im,$fileName);
}

function image_corner($file)
{
    $filename = JSM_PATH.'/output/'.$_SESSION['FILE_NAME'].'/resources/'.$file;
    $width = $height = 0;
    $img1 = ImageCreateFrompng($filename);
    $x = imagesx($img1) - $width;
    $y = imagesy($img1) - $height;
    $img2 = imagecreatetruecolor($x,$y);
    $bg = imagecolorallocate($img2,244,244,244);
    imagefill($img2,0,0,$bg);
    $e = imagecolorallocate($img2,0,0,0);
    $r = $x <= $y?$x : $y;
    imagefilledellipse($img2,($x / 2),($y / 2),$r,$r,$e);
    imagecolortransparent($img2,$e);
    imagecopymerge($img1,$img2,0,0,0,0,$x,$y,100);
    imagecolortransparent($img1,$bg);
    imagepng($img1,$filename);
    imagedestroy($img2);
    imagedestroy($img1);
}


$icons[] = array(
    'path' => 'icon.png',
    'w' => '1024',
    'h' => '1024');

$icons[] = array(
    'path' => 'splash.png',
    'w' => '2204',
    'h' => '2204');


/**
$icons[] = array(
    'path' => 'android/icon/drawable-ldpi-icon.png',
    'w' => '36',
    'h' => '36');
$icons[] = array(
    'path' => 'android/icon/drawable-mdpi-icon.png',
    'w' => '48',
    'h' => '48');
$icons[] = array(
    'path' => 'android/icon/drawable-hdpi-icon.png',
    'w' => '72',
    'h' => '72');
$icons[] = array(
    'path' => 'android/icon/drawable-xhdpi-icon.png',
    'w' => '96',
    'h' => '96');
$icons[] = array(
    'path' => 'android/icon/drawable-xxhdpi-icon.png',
    'w' => '144',
    'h' => '144');
$icons[] = array(
    'path' => 'android/icon/drawable-xxxhdpi-icon.png',
    'w' => '192',
    'h' => '192');
**/


$icons[] = array(
    'path' => 'android/icon/drawable-ldpi-icon.png',
    'w' => '167',
    'h' => '167');
    
$icons[] = array(
    'path' => 'android/icon/drawable-mdpi-icon.png',
    'w' => '111',
    'h' => '111');
    
$icons[] = array(
    'path' => 'android/icon/drawable-hdpi-icon.png',
    'w' => '167',
    'h' => '167');
    
$icons[] = array(
    'path' => 'android/icon/drawable-xhdpi-icon.png',
    'w' => '222',
    'h' => '222');
    
$icons[] = array(
    'path' => 'android/icon/drawable-xxhdpi-icon.png',
    'w' => '333',
    'h' => '333');
$icons[] = array(
    'path' => 'android/icon/drawable-xxxhdpi-icon.png',
    'w' => '444',
    'h' => '444');


//phonegap
$icons[] = array(
    'path' => '../www/res/icon/android/ldpi-icon.png',
    'w' => '36',
    'h' => '36');
$icons[] = array(
    'path' => '../www/res/icon/android/mdpi-icon.png',
    'w' => '48',
    'h' => '48');
$icons[] = array(
    'path' => '../www/res/icon/android/hdpi-icon.png',
    'w' => '72',
    'h' => '72');
$icons[] = array(
    'path' => '../www/res/icon/android/xhdpi-icon.png',
    'w' => '96',
    'h' => '96');
$icons[] = array(
    'path' => '../www/res/icon/android/xxhdpi-icon.png',
    'w' => '144',
    'h' => '144');
$icons[] = array(
    'path' => '../www/res/icon/android/xxxhdpi-icon.png',
    'w' => '192',
    'h' => '192');


$icons[] = array(
    'path' => 'android/splash/drawable-land-ldpi-screen.png',
    'w' => '320',
    'h' => '200');
$icons[] = array(
    'path' => 'android/splash/drawable-land-mdpi-screen.png',
    'w' => '480',
    'h' => '320');
$icons[] = array(
    'path' => 'android/splash/drawable-land-hdpi-screen.png',
    'w' => '800',
    'h' => '480');
$icons[] = array(
    'path' => 'android/splash/drawable-land-xhdpi-screen.png',
    'w' => '1280',
    'h' => '720');
$icons[] = array(
    'path' => 'android/splash/drawable-land-xxhdpi-screen.png',
    'w' => '1600',
    'h' => '960');
$icons[] = array(
    'path' => 'android/splash/drawable-land-xxxhdpi-screen.png',
    'w' => '1920',
    'h' => '1280');


$icons[] = array(
    'path' => 'android/splash/drawable-port-ldpi-screen.png',
    'h' => '320',
    'w' => '200');
$icons[] = array(
    'path' => 'android/splash/drawable-port-mdpi-screen.png',
    'h' => '480',
    'w' => '320');
$icons[] = array(
    'path' => 'android/splash/drawable-port-hdpi-screen.png',
    'h' => '800',
    'w' => '480');
$icons[] = array(
    'path' => 'android/splash/drawable-port-xhdpi-screen.png',
    'h' => '1280',
    'w' => '720');
$icons[] = array(
    'path' => 'android/splash/drawable-port-xxhdpi-screen.png',
    'h' => '1600',
    'w' => '960');
$icons[] = array(
    'path' => 'android/splash/drawable-port-xxxhdpi-screen.png',
    'h' => '1920',
    'w' => '1280');


// TODO: phonegap

$icons[] = array(
    'path' => '../www/res/screen/android/ldpi.png',
    'h' => '320',
    'w' => '200');
$icons[] = array(
    'path' => '../www/res/screen/android/mdpi.png',
    'h' => '480',
    'w' => '320');
$icons[] = array(
    'path' => '../www/res/screen/android/hdpi.png',
    'h' => '800',
    'w' => '480');
$icons[] = array(
    'path' => '../www/res/screen/android/xhdpi.png',
    'h' => '1280',
    'w' => '720');
$icons[] = array(
    'path' => '../www/res/screen/android/xxhdpi.png',
    'h' => '1600',
    'w' => '960');
$icons[] = array(
    'path' => '../www/res/screen/android/xxxhdpi.png',
    'h' => '1920',
    'w' => '1280');


// TODO: ICON FOR IOS
$icons[] = array(
    'path' => 'ios/icon/icon.png',
    'w' => '57',
    'h' => '57');

$icons[] = array(
    'path' => 'ios/icon/icon-small.png',
    'w' => '29',
    'h' => '29');

$icons[] = array(
    'path' => 'ios/icon/icon-40.png',
    'w' => '40',
    'h' => '40');

$icons[] = array(
    'path' => 'ios/icon/icon-50.png',
    'w' => '50',
    'h' => '50');

$icons[] = array(
    'path' => 'ios/icon/icon-50.png',
    'w' => '57',
    'h' => '57');

$icons[] = array(
    'path' => 'ios/icon/icon-small@2x.png',
    'w' => '58',
    'h' => '58');

$icons[] = array(
    'path' => 'ios/icon/icon-60.png',
    'w' => '60',
    'h' => '60');

$icons[] = array(
    'path' => 'ios/icon/icon-72.png',
    'w' => '72',
    'h' => '72');

$icons[] = array(
    'path' => 'ios/icon/icon-76.png',
    'w' => '76',
    'h' => '76');

$icons[] = array(
    'path' => 'ios/icon/icon-40@3x.png',
    'w' => '120',
    'h' => '120');

$icons[] = array(
    'path' => 'ios/icon/icon-40@2x.png',
    'w' => '80',
    'h' => '80');

$icons[] = array(
    'path' => 'ios/icon/icon-small@3x.png',
    'w' => '87',
    'h' => '87');

$icons[] = array(
    'path' => 'ios/icon/icon-50@2x.png',
    'w' => '100',
    'h' => '100');

$icons[] = array(
    'path' => 'ios/icon/icon@2x.png',
    'w' => '114',
    'h' => '114');

$icons[] = array(
    'path' => 'ios/icon/icon-60@2x.png',
    'w' => '120',
    'h' => '120');

$icons[] = array(
    'path' => 'ios/icon/icon-72@2x.png',
    'w' => '144',
    'h' => '144');
$icons[] = array(
    'path' => 'ios/icon/icon-76@2x.png',
    'w' => '152',
    'h' => '152');

$icons[] = array(
    'path' => 'ios/icon/icon-60@3x.png',
    'w' => '180',
    'h' => '180');

$icons[] = array(
    'path' => 'ios/icon/icon-83.5@2x.png',
    'w' => '167',
    'h' => '167');

// TODO: SPLASH FOR IOS
$icons[] = array(
    'path' => 'ios/splash/Default~iphone.png',
    'w' => '320',
    'h' => '480');

$icons[] = array(
    'path' => 'ios/splash/Default@2x~iphone.png',
    'w' => '640',
    'h' => '960');

$icons[] = array(
    'path' => 'ios/splash/Default-Landscape~ipad.png',
    'w' => '1024',
    'h' => '768');

$icons[] = array(
    'path' => 'ios/splash/Default-Portrait~ipad.png',
    'w' => '768',
    'h' => '1024');

$icons[] = array(
    'path' => 'ios/splash/Default-568h@2x~iphone.png',
    'w' => '640',
    'h' => '1136');

$icons[] = array(
    'path' => 'ios/splash/Default-667h.png',
    'w' => '750',
    'h' => '1334');

$icons[] = array(
    'path' => 'ios/splash/Default-Landscape-736h.png',
    'w' => '2208',
    'h' => '1242');

$icons[] = array(
    'path' => 'ios/splash/Default-736h.png',
    'w' => '1242',
    'h' => '2208');

$icons[] = array(
    'path' => 'ios/splash/Default-Portrait@2x~ipad.png',
    'w' => '1536',
    'h' => '2048');

$icons[] = array(
    'path' => 'ios/splash/Default-Landscape@2x~ipad.png',
    'w' => '2048',
    'h' => '1536');

$icons[] = array(
    'path' => 'ios/splash/Default-Landscape@2x~ipad.png',
    'w' => '2048',
    'h' => '1536');

$icons[] = array(
    'path' => 'ios/splash/Default-2436h.png',
    'w' => '1125',
    'h' => '2436');

$icons[] = array(
    'path' => 'ios/splash/Default-Landscape-2436h.png',
    'w' => '2436',
    'h' => '1125');

$path_config = 'projects/'.$file_name.'/resources.json';
if(isset($_POST['resource-save']))
{
    // TODO: SAVE RESOURCES
    $postdata['resources'] = $_POST['resources'];
    file_put_contents($path_config,json_encode($postdata));

    $icon_code = '&#xf075;';
    if(isset($_POST['resources']['icon']))
    {
        $icon_code = html_entity_decode($_POST['resources']['icon']);
    }
    $icon_color = '#387ef5';
    if(isset($_POST['resources']['color']))
    {
        $icon_color = html_entity_decode($_POST['resources']['color']);
    }

    foreach($icons as $icon)
    {
        $text_logo = $_SESSION['PROJECT']['app']['name'];
        if($icon['h'] == $icon['w'])
        {
            $text_logo = '';
        }
        create_png($icon['w'],$icon['h'],$icon['path'],$icon_color,$icon_code,(int)$_POST['resources']['left'],(int)$_POST['resources']['top'],1,$text_logo,$_POST['resources']['text-left'],$_POST['resources']['text-top']);
        if($icon['h'] == $icon['w'])
        {
            //border_radius($icon['path'], 25);
        }
    }

    create_png(240,240,'/../www/data/images/header/mylogo.png',$icon_color,$icon_code,(int)$_POST['resources']['left'],(int)$_POST['resources']['top'],0);


    //image_corner('/../www/data/images/icon/logo.png');
    //create_png(828, 543, '/../www/data/images/header/header.png', $icon_color, '', (int)$_POST['resources']['left'], (int)$_POST['resources']['top']);

    create_png(512,512,'/../publishing/logo-512x512.png',$icon_color,$icon_code,(int)$_POST['resources']['left'],(int)$_POST['resources']['top'],true);
    //border_radius('/../publishing/logo-512x512.png', 10);

    buildIonic($file_name);
}

$raw_resources['color'] = '';
$raw_resources['icon'] = '';
$raw_resources['top'] = '0';
$raw_resources['left'] = '0';

if(file_exists($path_config))
{
    $get_raw_resources = json_decode(file_get_contents($path_config),true);
    if(isset($get_raw_resources['resources']))
    {
        $raw_resources = $get_raw_resources['resources'];
    }


}


$icons = $icon = null;
$footer = $content = null;
$bs = new jsmBootstrap();

// TODO: LIST COLORS
$color["positive"] = "#2593E5";
$color["calm"] = "#11c1f3";
$color["balanced"] = "#33cd5f";
$color["energized"] = "#ffc900";
$color["assertive"] = "#ef473a";
$color["royal"] = "#886aea";
$color["dark"] = "#4444444";
$color["positive-900"] = "#1A237E";
$color["calm-900"] = "#0D47A1";
$color["balanced-900"] = "#1B5E20";
$color["energized-900"] = "#E65100";
$color["assertive-900"] = "#B71C1C";
$color["royal-900"] = "#311B92";


$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-file-photo-o fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Resources</h4>';

$content .= '
<blockquote class="blockquote blockquote-danger">
    <h4>'.__('The rules that apply are:').'</h4>
    <ul>
        <li>'.__('Icon and Splashscreen only work in real device, it\'s will <ins>not be displayed on the (IMAB) Emulator</ins>').'</li>
        <li>'.__('Documentation about splash screen images can be found in the Cordova-Plugin-Splashscreen documentation <a target="_blank" href="https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-splashscreen/">Splashscreen plugin docs</a>').'</li>
        <li>'.__('For Adobe Phonegap online not support for custom path of splashscreen folder, you should using default path of splashscreen folder (<code>/www/res</code>)').'</li>
        <li>'.__('You must install <code>cordova-plugin-splashscreen</code> or have run the command on your cordova <pre class="shell">cordova plugin add cordova-plugin-splashscreen --save</pre> Or following instructions <code>How to Build</code> in <a target="_blank" href="./?page=dashboard">(IMAB) Dashboard</a>.').'</li>
        <li>'.__('Generate new resources will be erase all existing images').'</li>
    </ul>
</blockquote>
';


$var_color = array_keys($color);
$val_color = array_values($color);


for($i = 0; $i < count($color); $i++)
{
    $color_css[$i] = array('label' => ucwords($var_color[$i]),'value' => $val_color[$i].'" class="bg-'.$var_color[$i]);
    if($raw_resources['color'] == $val_color[$i])
    {
        $color_css[$i]['active'] = true;
    }
}


if($raw_resources['top'] == '')
{
    $raw_resources['top'] = 100;
}
if($raw_resources['left'] == '')
{
    $raw_resources['left'] = 20;
}
if($raw_resources['icon'] == '')
{
    $raw_resources['icon'] = '';
}

if(!isset($raw_resources['text-top']))
{
    $raw_resources['text-top'] = 0;
}
if(!isset($raw_resources['text-left']))
{
    $raw_resources['text-left'] = 0;
}

if($raw_resources['text-top'] == '')
{
    $raw_resources['text-top'] = 0;
}
if($raw_resources['text-left'] == '')
{
    $raw_resources['text-left'] = 0;
}

$form_input = null;
$form_input .= '<div class="panel panel-default">';
$form_input .= '<div class="panel-heading">'.__('General').'</div>';
$form_input .= '<div class="panel-body">';

$form_input .= '<div class="row">';
$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('resources[color]','default','select',__('Background Color'),$color_css,'background color for splashscreen and icons','data-type="color-hexa"','8');
$form_input .= $bs->FormGroup('resources[icon]','default','text',__('FontAwesome Icon'),'','<a href="#!_" onclick="$(\'#resources_icon_\').val(\'\');" >'.__('Clear Text').'</a>','data-type="icon-picker"','8',htmlentities($raw_resources['icon']));
$form_input .= '</div>';

$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('resources[top]','default','number',__('Icon - Position Top'),'','Icon position from the top','','8',htmlentities($raw_resources['top']));
$form_input .= $bs->FormGroup('resources[left]','default','number',__('Icon - Position Left'),'','Icon position from the left','','8',htmlentities($raw_resources['left']));
$form_input .= '</div>';

$form_input .= '<div class="col-md-4">';
$form_input .= '<img class="img-thumbnail" src="./'.$out_path.'/resources/android/icon/drawable-hdpi-icon.png" width="72" height="72" />';
$form_input .= '&nbsp;<img class="img-thumbnail" src="./'.$out_path.'/resources/android/icon/drawable-xhdpi-icon.png" width="96" height="96" />';
$form_input .= '</div>';
$form_input .= '</div>';


$form_input .= '<div class="row">';

$form_input .= '<div class="col-md-4">';
$form_input .= $bs->FormGroup('resources[text-top]','default','number',__('Text - Position Top'),'','Text position from the top','','8',htmlentities($raw_resources['text-top']));
$form_input .= $bs->FormGroup('resources[text-left]','default','number',__('Text - Position Left'),'','Text position from the left','','8',htmlentities($raw_resources['text-left']));
$form_input .= '</div>';


$form_input .= '</div>';
$form_input .= '<br/>';


$form_input .= $bs->FormGroup(null,'default','html',null,$bs->ButtonGroups(null,array(array(
        'name' => 'resource-save',
        'label' => __('Generate New Resources'),
        'tag' => 'submit',
        'color' => 'primary'),array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'))));

$form_input .= '</div>';
$form_input .= '</div>';
$form_input .= '<hr/>';

$form_input .= '
<blockquote class="blockquote blockquote-info">
<h4>'.__('How to edit Icon and Splashscreen?').'</h4>
<ul>
<li>'.__('Complate the form then click <code>Generate New Resources</code> Button').'</li>
<li>'.__('Run <code>Finder</code> for OSx or <code>Windows Explorer</code> for windows, then go to folder:').'  
<table class="table table-striped">
<thead>
<tr>
	<th>'.__('Compiler').'</th>
	<th>'.__('Folder').'</th>
</tr>
</thead>
<tbody>
<tr>
	<td>Ionic or Cordova</td>
	<td><code>'.realpath(JSM_PATH.'/'.$out_path.'/resources/').'</code></td>
</tr>
<tr>
	<td>Adobe Phonegap Online</td>
	<td><code>'.realpath(JSM_PATH.'/'.$out_path.'/www/res/').'</code></td>
</tr>
</tbody>
</table>
</li>
<li>'.__('Then edit all images using <a target="_blank" href="https://www.adobe.com/sea/products/photoshop.html">Adobe Photoshop</a> or <a target="_blank" href="https://www.gimp.org/">Gimp</a>.').'</li>
<li>'.__('Adobe Phonegap for ios, you should add logo and screen manually.').'</li>
</ul>

</blockquote>
';
$files_to_png = array();
$dir_target = $out_path.'/resources';
if(is_dir($dir_target))
{
    $path[] = $dir_target.'/*';
    while(count($path) != 0)
    {
        $v = array_shift($path);
        foreach(glob($v) as $item)
        {
            if(is_dir($item))
                $path[] = $item.'/*';
            elseif(is_file($item))
            {
                if(pathinfo($item,PATHINFO_EXTENSION) == 'png')
                {
                    $files_to_png[] = $item;
                }
            }
        }
    }
}
$dir_target = $out_path.'/www/res';
if(is_dir($dir_target))
{
    $path[] = $dir_target.'/*';
    while(count($path) != 0)
    {
        $v = array_shift($path);
        foreach(glob($v) as $item)
        {
            if(is_dir($item))
                $path[] = $item.'/*';
            elseif(is_file($item))
            {
                if(pathinfo($item,PATHINFO_EXTENSION) == 'png')
                {
                    $files_to_png[] = $item;
                }
            }
        }
    }
}
$form_input .= '<div class="table-responsive" style="height: 360px;">';
$form_input .= '<table class="table table-striped">';
$form_input .= '<thead><tr><th>FileName</th><th class="text-center">Dimensions</th></tr></thead>';
foreach($files_to_png as $file)
{
    $imagesize = getimagesize($file);
    
    $form_input .= '
    <tr>
    <td><a target="_blank" href="'.$file.'?'.time().'">'.str_replace('output/'.$file_name.'/','',$file).'</a></td>
    <td class="text-center">'.$imagesize[0].' x '.$imagesize[1].'</td>
    <td><div class="col-md-4"><img src="'.$file.'?'.time().'" class="img-thumbnail" width="'.($imagesize[0] / 2 ).'" height="'.($imagesize[1] / 2).'" /></div></td>
  
    </tr>';
}
$form_input .= '</table>';
$form_input .= '</div>';
$form_input .= '';


$cmd = null;
$cmd .= '@ECHO OFF'."\r\n";
foreach($files_to_png as $file)
{
    if(preg_match("/android\/splash/i",$file))
    {
        $src = $file;
        $basename = basename($file);
        $dest = str_replace('-screen.png','/screen.png',$file);
        $dest = str_replace('/resources/','/platforms/',$dest);
        $dest = str_replace('/splash/','/res/',$dest);
        $dest_dir = str_replace('output\\'.$file_name.'\\','',str_replace('/','\\',pathinfo($dest,PATHINFO_DIRNAME)));
        $src_file = str_replace('output\\'.$file_name.'\\','',str_replace('/','\\',$src));

        $cmd .= "\r\n";
        $cmd .= 'MD "'.$dest_dir.'"'."\r\n";
        $cmd .= 'XCOPY /Y /S "'.$src_file.'" "'.$dest_dir.'\*"'."\r\n";
        $cmd .= 'REN "'.$dest_dir.'\\'.$basename.'" "screen.png"'."\r\n";
        $cmd .= 'DEL "'.$dest_dir.'\\'.$basename.'"'."\r\n";

    }
    if(preg_match("/android\/icon/i",$file))
    {
        $src = $file;
        $basename = basename($file);
        $dest = str_replace('-icon.png','/icon.png',$file);
        $dest = str_replace('/resources/','/platforms/',$dest);
        $dest = str_replace('/icon/','/res/',$dest);
        $dest_dir = str_replace('output\\'.$file_name.'\\','',str_replace('/','\\',pathinfo($dest,PATHINFO_DIRNAME)));
        $src_file = str_replace('output\\'.$file_name.'\\','',str_replace('/','\\',$src));

        $cmd .= "\r\n";
        $cmd .= 'MD "'.$dest_dir.'"'."\r\n";
        $cmd .= 'XCOPY /Y /S "'.$src_file.'" "'.$dest_dir.'\*"'."\r\n";
        $cmd .= 'REN "'.$dest_dir.'\\'.$basename.'" "icon.png"'."\r\n";
        $cmd .= 'DEL "'.$dest_dir.'\\'.$basename.'"'."\r\n";
    }
}
$cmd .= 'PAUSE'."\r\n";
file_put_contents($out_path.'/fix-android-res.bat',$cmd);


$content .= $bs->Forms('resources-form','','post','default',$form_input);

$modal_dialog = null;
$val_icon = array_keys($fa_icons);
$modal_dialog .= '<div class="ionicon-box">';
for($z = 0; $z < count($fa_icons); $z++)
{
    $modal_dialog .= '<div class="col-xs-1 col-md-1 col-lg-1 ionicon-item"><a data-toggle="tooltip" data-placement="top" title="'.$val_icon[$z].'" href="#'.$val_icon[$z].'" class="ionicon-list" data-icon="'.htmlentities($fa_icons[$val_icon[$z]]['code']).'"><span class="fa fa-'.$val_icon[$z].'"></span></a></div>';
}
$modal_dialog .= '</div>';
$content .= $bs->Modal('icon-dialog','FontAwesome Tables',$modal_dialog,'md',null,'Close',null);

$template->title = $template->base_title.' | '.'Extra Menus -&raquo; Resources';
$template->base_desc = 'resources';
$template->content = $content;
$template->footer = $footer;
$template->demo_url = $out_path.'/resources/';
//$template->emulator = false;
@file_put_contents(JSM_PATH.'/'.$out_path.'/resources/index.html','<img src="android/splash/drawable-port-ldpi-screen.png" width="100%" height="100%" style="padding:0;margin:0;" />');

?>