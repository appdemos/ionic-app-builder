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
$file_name = 'test';
if (isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}
if (!isset($_SESSION["PROJECT"]['menu']))
{
    header('Location: ./?page=menu&err=new');
    die();
}

// TODO: --|-- CLASS MINIFIED
function minify($css)
{
    // Normalize whitespace
    $css = preg_replace('/\s+/', ' ', $css);

    // Remove spaces before and after comment
    $css = preg_replace('/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css);
    // Remove comment blocks, everything between /* and */, unless
    // preserved with /*! ... */ or /** ... */
    $css = preg_replace('~/\*(?![\!|\*])(.*?)\*/~', '', $css);
    // Remove ; before }
    $css = preg_replace('/;(?=\s*})/', '', $css);
    // Remove space after , : ; { } */ >
    $css = preg_replace('/(,|:|;|\{|}|\*\/|>) /', '$1', $css);
    // Remove space before , ; { } ( ) >
    $css = preg_replace('/ (,|;|\{|}|\(|\)|>)/', '$1', $css);
    // Strips leading 0 on decimal values (converts 0.5px into .5px)
    $css = preg_replace('/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css);
    // Strips units if value is 0 (converts 0px to 0)
    $css = preg_replace('/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css);
    // Converts all zeros value into short-hand
    $css = preg_replace('/0 0 0 0/', '0', $css);
    // Shortern 6-character hex color codes to 3-character where possible
    $css = preg_replace('/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css);
    return trim($css);
}


$subpage_path = str2var($_SESSION["PROJECT"]['menu']['title']);
$form_input = $html = $content = $footer = null;
$out_path = 'output/' . $file_name;
$content = $form_content = null;
$bs = new jsmBootstrap();

// TODO: --|-- DELETE

if (isset($_GET['delete']))
{
    @unlink('resources/lib/ionic-material/css/ionic.material.min.css');
    @unlink('resources/lib/ionic-material/css/ionic.material.css');
    @unlink('projects/' . $file_name . '/themes.json');
    buildIonic($file_name);
    header('Location: ./?page=x-custom-themes&notice=save&err=null');
}

// TODO: --|-- DEFAULT COLOR
$material_light_bg_dark_text_primary = 'rgba(0,0,0,0.87)';
$material_light_bg_dark_text_secondary = 'rgba(0,0,0,0.54)';
$material_light_bg_dark_text_hints = 'rgba(0,0,0,0.26)';
$material_dark_bg_light_text_primary = 'rgba(255,255,255,1)';
$material_dark_bg_light_text_secondary = 'rgba(255,255,255,0.7)';
$material_dark_bg_light_text_hints = 'rgba(255,255,255,0.3)';


$material_positive = '#3F51B5';
$material_positive900 = '#1A237E';
$material_positive100 = '#C5CAE9';

$material_calm = '#2196F3';
$material_calm900 = '#0D47A1';
$material_calm100 = '#BBDEFB';

$material_royal = '#673AB7';
$material_royal900 = '#311B92';
$material_royal100 = '#D1C4E9';

$material_balanced = '#4CAF50';
$material_balanced900 = '#1B5E20';
$material_balanced100 = '#C8E6C9';

$material_energized = '#FF9800';
$material_energized900 = '#E65100';
$material_energized100 = '#FFE0B2';

$material_assertive = '#F44336';
$material_assertive900 = '#B71C1C';
$material_assertive100 = '#FFCDD2';

$material_stable = '#E0E0E0';
// TODO: --|-- SAVE JSON
if (isset($_POST['themes-save']))
{
    if (!is_dir('projects/' . $file_name))
    {
        mkdir('projects/' . $file_name, 0777, true);
    }


    $material_positive = $_POST['themes']['positive'];
    $material_positive900 = $_POST['themes']['positive-900'];
    $material_positive100 = $_POST['themes']['positive-100'];

    $material_calm = $_POST['themes']['calm'];
    $material_calm900 = $_POST['themes']['calm-900'];
    $material_calm100 = $_POST['themes']['calm-100'];

    $material_royal = $_POST['themes']['royal'];
    $material_royal900 = $_POST['themes']['royal-900'];
    $material_royal100 = $_POST['themes']['royal-100'];

    $material_balanced = $_POST['themes']['balanced'];
    $material_balanced900 = $_POST['themes']['balanced-900'];
    $material_balanced100 = $_POST['themes']['balanced-100'];

    $material_energized = $_POST['themes']['energized'];
    $material_energized900 = $_POST['themes']['energized-900'];
    $material_energized100 = $_POST['themes']['energized-100'];

    $material_assertive = $_POST['themes']['assertive'];
    $material_assertive900 = $_POST['themes']['assertive-900'];
    $material_assertive100 = $_POST['themes']['assertive-100'];


    // TODO: --|-- MATERIAL CSS
    $css_material = "
.item-md-label {
  display: block;
  background: transparent;
  box-shadow: none;
  margin-left: 12px;
  margin-right: 12px;
  padding: 30px 0 0; }

.item-md-label .input-label {
  position: absolute;
  padding: 5px 0 0;
  z-index: 2;
  -webkit-transform: translate3d(0, -30px, 0) scale(1);
  transform: translate3d(0, -30px, 0) scale(1);
  -webkit-transition: all 0.2s ease;
  transition: all 0.2s ease;
  color: #fff;
  opacity: 0.5;
  filter: alpha(opacity=50);
  -webkit-transform-origin: 0;
  -ms-transform-origin: 0;
  transform-origin: 0; }

.item-md-label input {
  background-color: rgba(0, 0, 0, 0.6);
  bottom: 0;
  color: #fff;
  letter-spacing: 0.25rem;
  padding: 20px 10px;
  position: relative;
  z-index: 1; }

.item-md-label .highlight {
  position: absolute;
  bottom: 0;
  height: 2px;
  left: 0;
  width: 100%;
  -webkit-transform: translate3d(-100%, 0, 0);
  transform: translate3d(-100%, 0, 0);
  -webkit-transition: all 0.15s ease;
  transition: all 0.15s ease;
  z-index: 1; }

.item-md-label .highlight-light {
  background: #fff; }

.item-md-label .highlight-stable {
  background: #f8f8f8; }

.item-md-label .highlight-positive {
  background: #387ef5; }

.item-md-label .highlight-calm {
  background: #11c1f3; }

.item-md-label .highlight-balanced {
  background: #33cd5f; }

.item-md-label .highlight-energized {
  background: #ffc900; }

.item-md-label .highlight-assertive {
  background: #ef473a; }

.item-md-label .highlight-royal {
  background: #886aea; }

.item-md-label .highlight-dark {
  background: #444; }

.item-md-label .input-label {
  letter-spacing: 0.25rem;
  padding: 0 10px; }

.item-md-label input:focus ~ .input-label, .item-md-label input.used ~ .input-label {
  font-weight: bold;
  opacity: 0.7;
  filter: alpha(opacity=70);
  padding: 0;
  text-transform: uppercase;
  -webkit-transform: translate3d(0, -60px, 0) scale(0.9);
  transform: translate3d(0, -60px, 0) scale(0.9); }

.item-md-label input:focus ~ .highlight {
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0); }

 
.expanded .bar.bar-header,
.bar.bar-header.expanded {
  height: 75px; }

.expanded.bar.bar-header .title,
.bar.bar-header.expanded .title {
  bottom: 0;
  top: initial;
  padding-left: 16px; }

.expanded .bar.bar-header .title.fab-left,
.bar.bar-header.expanded .title.fab-left {
  bottom: 0;
  left: 90px;
  position: absolute;
  right: initial;
  top: initial; }

.expanded .bar.bar-header .title.fab-right,
.bar.bar-header.expanded .title.fab-right {
  bottom: 0;
  left: 4px;
  position: absolute;
  top: initial;
  right: initial; }

.expanded .bar.bar-header + .button-fab,
.bar.bar-header.expanded + .button-fab {
  top: 50px; }

.expanded .bar.bar-header.push-down,
.bar.bar-header.expanded.push-down {
  height: 44px;
  overflow: hidden; }

.expanded .bar.bar-header,
.bar.bar-header.expanded {
  -webkit-transition: height 1s cubic-bezier(0.55, 0, 0.1, 1);
  transition: height 1s cubic-bezier(0.55, 0, 0.1, 1);
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0); }

.expanded .bar.bar-header + .button-fab,
.bar.bar-header.expanded + .button-fab {
  -webkit-transition: all 1.1s cubic-bezier(0.55, 0, 0.1, 1);
  transition: all 1.1s cubic-bezier(0.55, 0, 0.1, 1);
  -webkit-transform: translate3d(0, 0, 0) scale(1);
  transform: translate3d(0, 0, 0) scale(1); }

.expanded .bar.bar-header.push-down + .button-fab,
.bar.bar-header.expanded.push-down + .button-fab {
  top: 0;
  -webkit-transform: translate3d(-100px, -100px, 0) scale(2.5);
  transform: translate3d(-100px, -100px, 0) scale(2.5); }

.expanded .bar.bar-header.push-down .title,
.bar.bar-header.expanded.push-down .title {
  opacity: 0;
  filter: alpha(opacity=0);
  left: initial;
  right: initial; }

.expanded .bar.bar-header .title,
.bar.bar-header.expanded .title {
  opacity: 1;
  filter: alpha(opacity=100);
  -webkit-transition: all 2s cubic-bezier(0.55, 0, 0.1, 1);
  transition: all 2s cubic-bezier(0.55, 0, 0.1, 1); }

.expanded .bar.bar-header .title, .bar.bar-header.expanded .title {
  bottom: 0;
  left: 42px !important;
  top: initial; }

.expanded.has-header-fab-left .bar.bar-header .title, .bar.bar-header.expanded.has-header-fab-left .title {
  left: 76px !important; }

 
.bar {
  z-index: 2;
  font-size: 1.3em;
  width: 100%;
  box-shadow: 0px 2px 5px 0 rgba(0, 0, 0, 0.26); }

.bar .button {
  min-width: 38px;
  z-index: 3; }

.bar .no-text span.back-text {
  display: none; }

.bar .title sup {
  opacity: 0.7; }

.bar.bar-header .button + .title {
  text-align: left;
  left: 35px;
  line-height: 46px; }

 
.button-bar {
  box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.15); }

.button-bar > .button {
  box-shadow: none;
  /*    line-height: initial; */ }

.button-bar > .button .icon:before,
.button-bar > .button:before {
  line-height: initial; }

.bar-footer .button-fab {
  position: absolute;
  top: -26px;
  bottom: initial; }

.bar-footer .buttons-left .button-fab {
  left: 8px; }

.bar-footer .buttons-right .button-fab {
  right: 8px; }

.bar .button.button-clear {
  box-shadow: none; }

.left-buttons .button-fab {
  left: 8px;
  top: 16px; }

.right-buttons .button-fab {
  right: 8px;
  top: 16px; }

.fab-left.title-left,
.fab-left.title.title-left {
  left: 68px; }

 
.button.button-fab,
.bar .button.button-fab {
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
  z-index: 9999;
  width: 56px;
  height: 56px;
  max-height: initial;
  max-width: initial;
  border-radius: 50%;
  border-radius: 50%;
  overflow: hidden;
  padding: 0;
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0);
  -webkit-transition: 0.3s fade-in-out;
  transition: 0.3s fade-in-out;
  -webkit-transition-property: -webkit-transform, box-shadow;
  transition-property: transform, box-shadow; }

.button.button-fab.button-fab-bottom-right,
.bar .button.button-fab.button-fab-bottom-right {
  top: auto;
  right: 16px;
  bottom: 16px;
  left: auto;
  position: absolute; }

.button.button-fab.button-fab-bottom-left,
.bar .button.button-fab.button-fab-bottom-left {
  top: auto;
  right: auto;
  bottom: 16px;
  left: 16px;
  position: absolute; }

.button.button-fab.button-fab-top-right,
.bar .button.button-fab.button-fab-top-right {
  top: 32px;
  right: 16px;
  bottom: auto;
  left: auto;
  position: absolute; }

.button.button-fab.button-fab-top-left,
.bar .button.button-fab.button-fab-top-left {
  top: 32px;
  right: auto;
  bottom: auto;
  left: 16px;
  position: absolute; }

.button.button-fab.button-fab-top-left.expanded,
.button.button-fab.button-fab-top-right.expanded,
.bar .button.button-fab.button-fab-top-left.expanded,
.bar .button.button-fab.button-fab-top-right.expanded {
  top: 48px; }

.button.button-fab i,
.bar .button.button-fab i {
  font-size: 2.5rem;
  margin-top: 0; }

.button.button-fab.mini,
.bar .button.button-fab.mini {
  width: 40px;
  height: 40px; }

.button.button-fab.mini i,
.bar .button.button-fab.mini i {
  font-size: 2rem; }

 
.motion {
  -webkit-transition: all 0.5s ease-out;
  transition: all 0.5s ease-out; }

.fade {
  opacity: 0;
  filter: alpha(opacity=0);
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transition: all 0.1s ease-out !important;
  transition: all 0.1s ease-out !important; }

.spin-back {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotate(360deg) scale(0) !important;
  transform: translateZ(0) rotate(360deg) scale(0) !important;
  -webkit-transition: all 0.1s ease-out !important;
  transition: all 0.1s ease-out !important; }

.spiral {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotate(-360deg) scale(0) translate(-120px) !important;
  transform: translateZ(0) rotate(-360deg) scale(0) translate(-120px) !important;
  -webkit-transition: all 0.1s ease-out !important;
  transition: all 0.1s ease-out !important; }

.spiral-back {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotate(360deg) scale(0) translate(120px) !important;
  transform: translateZ(0) rotate(360deg) scale(0) translate(120px) !important;
  -webkit-transition: all 0.1s ease-out !important;
  transition: all 0.1s ease-out !important; }

.menu-open .avatar {
  opacity: 1;
  filter: alpha(opacity=100);
  -webkit-transform: translateZ(0) rotate(0) scale(1) !important;
  transform: translateZ(0) rotate(0) scale(1) !important;
  -webkit-transition: all 0.3s ease-out !important;
  transition: all 0.3s ease-out !important; }

.button.button-fab.button-fab-top-left.motion {
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -webkit-transform: translate3d(-120px, 60px, 0);
  transform: translate3d(-120px, 60px, 0);
  -webkit-transition: all 0.1s ease-out;
  transition: all 0.1s ease-out; }

.button.button-fab.button-fab-top-right.motion {
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -webkit-transform: translate3d(120px, 60px, 0);
  transform: translate3d(120px, 60px, 0);
  -webkit-transition: all 0.1s ease-out;
  transition: all 0.1s ease-out; }

.button.button-fab.button-fab-bottom-left.motion {
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -webkit-transform: translate3d(-120px, 60px, 0);
  transform: translate3d(-120px, 60px, 0);
  -webkit-transition: all 0.1s ease-out;
  transition: all 0.1s ease-out; }

.button.button-fab.button-fab-bottom-right.motion {
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -webkit-transform: translate3d(120px, 60px, 0);
  transform: translate3d(120px, 60px, 0);
  -webkit-transition: all 0.1s ease-out;
  transition: all 0.1s ease-out; }

.spin {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotate(0) scale(0) !important;
  transform: translateZ(0) rotate(0) scale(0) !important;
  -webkit-transition: all 0.3s ease-out !important;
  transition: all 0.3s ease-out !important; }

.spin.on {
  -webkit-transform: translateZ(0) rotate(-360deg) scale(1) !important;
  transform: translateZ(0) rotate(-360deg) scale(1) !important; }

.flap {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotateX(0) scale(0) translate(-120px) !important;
  transform: translateZ(0) rotateX(0) scale(0) translate(-120px) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

.flap.on {
  -webkit-transform: translateZ(0) rotateX(-720deg) scale(1) translate(0) !important;
  transform: translateZ(0) rotateX(-720deg) scale(1) translate(0) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

.drop {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) scale(3) !important;
  transform: translateZ(0) scale(3) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

.drop.on {
  -webkit-transform: translateZ(0) scale(1) !important;
  transform: translateZ(0) scale(1) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

.flip {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotateY(0) scale(0) !important;
  transform: translateZ(0) rotateY(0) scale(0) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

.flip.on {
  -webkit-transform: translateZ(0) rotateY(-720deg) scale(1) !important;
  transform: translateZ(0) rotateY(-720deg) scale(1) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

 
.button.button-floating, .bar .button.button-floating {
  display: inline-block;
  color: #FFF;
  position: relative;
  z-index: 1;
  width: 37px;
  height: 37px;
  line-height: 37px;
  padding: 0;
  border-radius: 50%;
  background-clip: padding-box;
  -webkit-transition: 0.3s;
  transition: 0.3s;
  cursor: pointer; }

.button.button-floating i, .bar .button.button-floating i {
  width: inherit;
  display: inline-block;
  text-align: center;
  color: #FFF;
  font-size: 1.6rem;
  line-height: 37px; }

.button.button-floating.button-large, .bar .button.button-floating.button-large {
  width: 55.5px;
  height: 55.5px; }

.button.button-floating.button-large i, .bar .button.button-floating.button-large i {
  line-height: 55.5px; }
 
.button,
.button.button-large,
.button.button-flat,
.bar .button,
.bar .button.button-large,
.bar .button.button-flat {
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
  display: inline-block;
  height: 36px;
  padding: 0 2rem;
  border-radius: 2px;
  background-clip: padding-box;
  text-transform: uppercase;
  border: none;
  outline: 0;
  -webkit-tap-highlight-color: transparent; }

.button.disabled,
.button.disabled.button-large,
.button.button-floating.disabled,
.button.button-large.disabled,
.button.button:disabled,
.button.button-large:disabled,
.button.button-large:disabled,
.button.button-floating:disabled,
.bar .button.disabled,
.bar .button.disabled.button-large,
.bar .button.button-floating.disabled,
.bar .button.button-large.disabled,
.bar .button.button:disabled,
.bar .button.button-large:disabled,
.bar .button.button-large:disabled,
.bar .button.button-floating:disabled {
  background-color: #DFDFDF;
  box-shadow: none;
  color: #9F9F9F; }

.button.disabled:hover,
.button.disabled.button-large:hover,
.button.button-floating.disabled:hover,
.button.button-large.disabled:hover,
.button.button:disabled:hover,
.button.button-large:disabled:hover,
.button.button-large:disabled:hover,
.button.button-floating:disabled:hover,
.bar .button.disabled:hover,
.bar .button.disabled.button-large:hover,
.bar .button.button-floating.disabled:hover,
.bar .button.button-large.disabled:hover,
.bar .button.button:disabled:hover,
.bar .button.button-large:disabled:hover,
.bar .button.button-large:disabled:hover,
.bar .button.button-floating:disabled:hover {
  background-color: #DFDFDF;
  color: #9F9F9F; }

.button i,
.button.button-large i,
.button.button-floating i,
.button.button-large i,
.button.button-flat i,
.bar .button i,
.bar .button.button-large i,
.bar .button.button-floating i,
.bar .button.button-large i,
.bar .button.button-flat i {
  font-size: 1.3rem; }

.button-bar .button {
  border-radius: 0; }

.button,
.button-large,
.bar .button,
.bar .button-large {
  text-decoration: none;
  text-align: center;
  letter-spacing: 0.5px;
  -webkit-transition: 0.2s ease-out;
  transition: 0.2s ease-out;
  cursor: pointer; }

.button {
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  position: relative;
  outline: none;
  margin: 0;
  /* background: transparent; */
  white-space: nowrap;
  text-align: center;
  text-transform: uppercase;
  font-weight: 500;
  font-style: inherit;
  font-variant: inherit;
  font-size: inherit;
  text-decoration: none;
  cursor: pointer;
  overflow: hidden;
  -webkit-transition: box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), background-color 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), -webkit-transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
  transition: box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), background-color 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); }

.button:focus {
  outline: none; }

.button.ng-hide {
  -webkit-transition: none;
  transition: none; }

.button.cornered {
  border-radius: 0; }

.button.raised {
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0); }

.button-outline,
.button-outline:hover,
.button-outline:active {
  border-style: solid;
  border-width: 1px; }

.button.button-outline.button-assertive,
.button.button-outline.button-balanced,
.button.button-outline.button-calm,
.button.button-outline.button-dark,
.button.button-outline.button-energized,
.button.button-outline.button-light,
.button.button-outline.button-positive,
.button.button-outline.button-royal,
.button.button-outline.button-stable,
.button.button-outline {
  border-color: rgba(0, 0, 0, 0.1); }

.button-flat,
.bar .button-flat {
  box-shadow: none;
  background-color: transparent;
  color: #343434;
  cursor: pointer; }

.button.button-flat.disabled,
.bar .button.button-flat.disabled {
  color: #b3b3b3; }

.button.button-large i,
.bar .button.button-large i {
  font-size: 1.6rem; }

.button-pin-header.button-floating {
  position: absolute;
  z-index: 1000; }

.button-pin-header.button-pin-left {
  left: 24px;
  top: -24px; }

.button-pin-header.button-pin-right {
  right: 24px;
  top: -24px; }

.button:not([disabled]).raised:focus,
.button:not([disabled]).raised:hover,
.button:not([disabled]).floating:focus,
.button:not([disabled]).floating:hover {
  -webkit-transform: translate3d(0, -1px, 0);
  transform: translate3d(0, -1px, 0); }

.button.button-flat {
  box-shadow: none;
  color: inherit; }

.button.button-flat:hover {
  color: inherit; }

.button.button-flat,
.button.button-flat:hover,
.button.button-flat:active {
  color: #fff; }

.button.button-clear,
.button.button-clear:hover,
.button.button-clear:active {
  background: transparent; }

.button-full.ink,
.button-block.ink {
  display: block; }

.card-item.item {
  border: none;
  padding-bottom: 4px;
  padding-top: 4px; }

.card-item.item:first-child {
  padding-top: 16px; }

.card {
  box-shadow: 0px 2px 5px 0 rgba(0, 0, 0, 0.26);
  display: block;
  margin: 8px;
  padding: 0;
  position: relative; }

.card .image {
  display: block;
  margin-top: 10px;
  margin-bottom: 5px; }

.card img {
  box-shadow: 0px 2px 5px 0 rgba(0, 0, 0, 0.26);
  display: block;
  max-width: 100%;
  max-height: initial;
  position: static; }

.card.card-gallery img {
  border: none;
  box-shadow: none;
  display: block; }

.card .card-footer {
  font-size: 90%;
  opacity: 0.8;
  filter: alpha(opacity=80);
  padding-top: 10px; }

.card > .item {
  border: none; }

.card.card-gallery > .item {
  background: inherit; }

.card .icon + .icon {
  padding-left: 1rem; }

.card.animate-fade-in {
  opacity: 0;
  filter: alpha(opacity=0);
  -webkit-transform: translate3d(-30px, 1px, 0);
  -webkit-transition: all 1s ease-in-out; }

.card.animate-fade-in.done {
  opacity: 1;
  filter: alpha(opacity=100);
  -webkit-transform: translate3d(0, 0, 0); }

.card .item.item-avatar {
  min-height: 88px;
  padding-left: 88px; }

 
.hero {
  background-size: cover;
  box-shadow: 0px 2px 5px 0 rgba(0, 0, 0, 0.26);
  color: #fff;
  height: 200px;
  position: relative;
  text-align: center;
  -webkit-transition: all 1s cubic-bezier(0.55, 0, 0.1, 1);
  transition: all 1s cubic-bezier(0.55, 0, 0.1, 1);
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0);
  width: 100%; }

.hero > * {
  -webkit-transition: opacity 2.5s cubic-bezier(0.55, 0, 0.1, 1);
  transition: opacity 2.5s cubic-bezier(0.55, 0, 0.1, 1);
  opacity: 1;
  filter: alpha(opacity=100); }

.hero + .mid-bar {
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0);
  -webkit-transition: all 1s cubic-bezier(0.55, 0, 0.1, 1);
  transition: all 1s cubic-bezier(0.55, 0, 0.1, 1);
  height: initial;
  opacity: 1;
  filter: alpha(opacity=100); }

.hero .hero-icon {
  box-shadow: 0px 0 2px 0 rgba(0, 0, 0, 0.26);
  border-radius: 50%;
  display: inline-block;
  font-size: 65px;
  height: 150px;
  padding: 10px 30px;
  line-height: 136px;
  width: 150px; }

.hero.no-header {
  height: 244px; }

.hero > .content {
  bottom: 0;
  position: absolute;
  text-align: center;
  width: 100%;
  z-index: 1; }

.hero > .content > .avatar {
  background-position: center;
  background-size: cover;
  border: solid 1px rgba(255, 255, 255, 0.8);
  border-radius: 50%;
  display: inline-block;
  height: 88px;
  left: auto;
  margin-bottom: 10px;
  position: relative;
  width: 88px; }

.hero h1 .hero h2, .hero h3, .hero h4, .hero h5, .hero h6 {
  color: #fff;
  margin: 0; }

.hero h4 {
  color: rgba(255, 255, 255, 0.7);
  margin: 3px 0 16px; }

.hero h1 > a, .hero h2 > a, .hero h3 > a, .hero h4 > a, .hero h5 > a, .hero h6 > a {
  text-decoration: none; }

.hero + .button-bar {
  border-radius: 0;
  margin-top: 0; }

.hero + .button-bar > .button:first-child, .hero + .button-bar > .button:last-child {
  border-radius: 0; }

.hero .hero-icon {
  color: #fff;
  font-size: 96px; }

.hero .hero-icon + h1 {
  color: white;
  letter-spacing: 0.15rem; }

.hero .button, .hero .button.button-large, .hero .button.button-flat {
  margin: 0; }

.hero h1.title {
  color: #fff;
  font-size: 23px;
  margin: 0;
  text-align: left;
  padding-left: 80px;
  line-height: 59px; }

.hero + .mid-bar {
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0);
  -webkit-transition: all 1s cubic-bezier(0.55, 0, 0.1, 1);
  transition: all 1s cubic-bezier(0.55, 0, 0.1, 1);
  height: initial;
  opacity: 1;
  filter: alpha(opacity=100); }

.hero > * {
  -webkit-transition: opacity 2.5s cubic-bezier(0.55, 0, 0.1, 1);
  transition: opacity 2.5s cubic-bezier(0.55, 0, 0.1, 1);
  opacity: 1;
  filter: alpha(opacity=100); }



.item {
  font-size: 14px;
  width: 100%; }

.item-icon-left .icon {
  left: 16px; }

.item-icon-right .icon {
  right: 16px; }

/*
.list .item.item-icon-right {
    padding-right: 60px;
}
*/
.item-thumbnail-left > img:first-child, .item-thumbnail-left .item-image, .item-thumbnail-left .item-content > img:first-child, .item-thumbnail-left .item-content .item-image {
  border-radius: 50%; }

.tab-item.activated {
  height: calc(100% + 3px);
  }

 
.content + .list {
  padding-top: 0; }

.list .item {
  border: none;
  /*
    padding-left: 16px;
    padding-right: 16px;
    */
  text-align: left; }

.list .item.tabs {
  padding: initial; }

.list .item.item-bg-image {
  max-height: 150px;
  min-height: 150px; }

.list .item.item-bg-image > img {
  height: 100%;
  left: 0;
  max-width: initial;
  opacity: 0.65;
  filter: alpha(opacity=65);
  position: absolute;
  top: 0;
  width: 100%;
  z-index: 0; }

.list a.item {
  opacity: 1;
  filter: alpha(opacity=100); }

.list .item.item-bg-image h1, .list .item.item-bg-image h2, .list .item.item-bg-image h3, .list .item.item-bg-image h4, .list .item.item-bg-image h5, .list .item.item-bg-image h6 {
  color: #fff;
  font-weight: bold;
  position: relative;
  text-shadow: 0 0 3px rgba(0, 0, 0, 0.95);
  z-index: 1; }

.list .item.item-bg-image h2 {
  font-size: 24px; }

.list .item.item-bg-image h2 {
  font-size: 24px; }

.list .item.item-bg-image p {
  color: white;
  font-size: 17px;
  position: relative;
  text-shadow: 0 0 4px rgba(0, 0, 0, 0.95);
  z-index: 1; }

.item-avatar, .item-avatar .item-content, .item-avatar-left, .item-avatar-left .item-content {
  min-height: 80px; }

 
.item-thumbnail-left, .card > .item.item-thumbnail-left, .item-thumbnail-left .item-content {
  padding-left: 106px; }

.item-thumbnail-right, .card > .item.item-thumbnail-right, .item-thumbnail-right .item-content {
  padding-right: 106px; }

 
.item-avatar > img:first-child, .item-avatar .item-image, .item-avatar .item-content > img:first-child, .item-avatar .item-content .item-image, .item-avatar-left > img:first-child, .item-avatar-left .item-image, .item-avatar-left .item-content > img:first-child, .item-avatar-left .item-content .item-image {
  border-radius: 50%;
  left: 16px;
  max-height: 40px;
  max-width: 40px; }

/*
.item-avatar, .list .item-avatar {
    padding-left: 100px;
}
*/
.avatar, .item-avatar .avatar {
  background-position: center;
  background-size: cover;
  border-radius: 50%;
  display: inline-block;
  height: 56px;
  left: 16px;
  position: absolute;
  width: 56px; }

 
.list.half {
  display: inline-block;
  float: left;
  margin: 0;
  padding: 0;
  width: 50%; }

.list.half:first-child {
  padding: 16px 8px 16px 16px; }

.list.half:last-child {
  padding: 16px 16px 16px 8px; }

.list.half:first-child .card.card-gallery {
  margin-left: 0;
  margin-right: 0; }

.list.half:last-child .card.card-gallery {
  margin-left: 0;
  margin-right: 0; }

.list.condensed-space > .card, .list.condensed-space > .item {
  margin: 0px 0px 2px; }

.list .card.card-gallery {
  display: block;
  float: left;
  margin: 0 0 0 13px;
  padding: 0;
  width: auto; }

.list.half .item {
  width: 100%; }

.list.half .item.card {
  margin-bottom: 16px; }

.list .card.card-gallery.item h2 {
  padding: 12px; }

.list .item.item-gallery img {
  width: 100%; }

.item.item-divider {
  border-top: solid 1px rgba(0, 0, 0, 0.12);
  font-size: 14px;
  font-weight: bold;
  height: 48px;
  line-height: 48px;
  color: rgba(0, 0, 0, 0.54); }
  .item.item-divider:first-child {
    border: none; }

.item-avatar, .item-avatar .item-content, .item-avatar-left, .item-avatar-left .item-content, .card > .item-avatar {
  padding-left: 72px; }

.item.active, .item.activated, .item-complex.active .item-content, .item-complex.activated .item-content, .item .item-content.active, .item .item-content.activated {
  background-color: transparent; }

.list-inset {
  margin: 20px 30px;
  border-left: solid 1px #ccc;
  border-radius: 0;
  background-color: #fff; }

.list .item.item-floating-label,
.item-floating-label {
  border-bottom: solid 1px #ccc; }

.loader {
  position: relative;
  margin: 0px auto;
  width: 100px;
  height: 100px;
  zoom: 1.7; }

.circular {
  -webkit-animation: rotate 2s linear infinite;
  animation: rotate 2s linear infinite;
  height: 100px;
  position: relative;
  width: 100px; }

.path {
  stroke-dasharray: 1,200;
  stroke-dashoffset: 0;
  -webkit-animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
  animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
  stroke-linecap: round; }

@-webkit-keyframes rotate {
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg); } }

@keyframes rotate {
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg); } }

@-webkit-keyframes dash {
  0% {
    stroke-dasharray: 1,200;
    stroke-dashoffset: 0; }
  50% {
    stroke-dasharray: 89,200;
    stroke-dashoffset: -35; }
  100% {
    stroke-dasharray: 89,200;
    stroke-dashoffset: -124; } }

@keyframes dash {
  0% {
    stroke-dasharray: 1,200;
    stroke-dashoffset: 0; }
  50% {
    stroke-dasharray: 89,200;
    stroke-dashoffset: -35; }
  100% {
    stroke-dasharray: 89,200;
    stroke-dashoffset: -124; } }

@-webkit-keyframes color {
  100%, 0% {
    stroke: #d62d20; }
  40% {
    stroke: #0057e7; }
  66% {
    stroke: #008744; }
  80%, 90% {
    stroke: #ffa700; } }

@keyframes color {
  100%, 0% {
    stroke: #d62d20; }
  40% {
    stroke: #0057e7; }
  66% {
    stroke: #008744; }
  80%, 90% {
    stroke: #ffa700; } }

 
.login {
  background-position: 25% 25%;
  background-size: 180% 180%;
  height: 100%;
  -webkit-transition: all 1.5s ease-in-out;
  transition: all 1.5s ease-in-out; }

.login .item {
  margin: 0 12px;
  padding-left: 0;
  padding-right: 0;
  width: initial; }

.login .button-bar {
  bottom: 0;
  margin: 28px 12px 0;
  width: initial; }

.login .light-bg {
  background-color: #fff; }

.icon.hero-icon:before {
  line-height: 130px; }

 
.hero.has-mask:after, .item.has-mask:after, .card.has-mask:after {
  content: '';
  background: -webkit-linear-gradient(top, transparent 0%, rgba(0, 0, 0, 0.6) 100%);
  height: 100%;
  left: 0;
  position: absolute;
  top: 0;
  z-index: 0;
  width: 100%; }

.hero.has-mask-reverse:after, .item.has-mask-reverse:after, .card.has-mask-reverse:after {
  content: '';
  background: -webkit-linear-gradient(top, rgba(0, 0, 0, 0.6) 0%, transparent 100%);
  height: 100%;
  left: 0;
  position: absolute;
  top: 0;
  z-index: 0;
  width: 100%; }

 
.menu-bottom {
  bottom: 16px;
  left: 16px;
  right: 16px;
  position: absolute; }

.menu-top {
  top: 16px;
  left: 16px;
  right: 16px;
  position: absolute; }

.menu .avatar {
  top: 16px;
  left: 16px;
  height: 65px;
  width: 65px; }

.menu .bar.bar-header.expanded {
  box-shadow: none;
  min-height: 150px;
  color: #fff; }

.menu-open .bar.bar-header.expanded {
  background-position: 0;
  background-size: 100%; }

.has-expanded-header {
  top: 150px !important; }

.motion {
  -webkit-transition: all 0.5s ease-out;
  transition: all 0.5s ease-out; }

.fade {
  opacity: 0;
  filter: alpha(opacity=0);
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transition: all 0.1s ease-out !important;
  transition: all 0.1s ease-out !important; }

.spin-back {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotate(360deg) scale(0) !important;
  transform: translateZ(0) rotate(360deg) scale(0) !important;
  -webkit-transition: all 0.1s ease-out !important;
  transition: all 0.1s ease-out !important; }

.spiral {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotate(-360deg) scale(0) translate(-120px) !important;
  transform: translateZ(0) rotate(-360deg) scale(0) translate(-120px) !important;
  -webkit-transition: all 0.1s ease-out !important;
  transition: all 0.1s ease-out !important; }

.spiral-back {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotate(360deg) scale(0) translate(120px) !important;
  transform: translateZ(0) rotate(360deg) scale(0) translate(120px) !important;
  -webkit-transition: all 0.1s ease-out !important;
  transition: all 0.1s ease-out !important; }

.menu-open .avatar {
  opacity: 1;
  filter: alpha(opacity=100);
  -webkit-transform: translateZ(0) rotate(0) scale(1) !important;
  transform: translateZ(0) rotate(0) scale(1) !important;
  -webkit-transition: all 0.3s ease-out !important;
  transition: all 0.3s ease-out !important; }

.spin {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotate(0) scale(0) !important;
  transform: translateZ(0) rotate(0) scale(0) !important;
  -webkit-transition: all 0.3s ease-out !important;
  transition: all 0.3s ease-out !important; }

.spin.on {
  -webkit-transform: translateZ(0) rotate(-360deg) scale(1) !important;
  transform: translateZ(0) rotate(-360deg) scale(1) !important; }

.flap {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotateX(0) scale(0) translate(-120px) !important;
  transform: translateZ(0) rotateX(0) scale(0) translate(-120px) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

.flap.on {
  -webkit-transform: translateZ(0) rotateX(-720deg) scale(1) translate(0) !important;
  transform: translateZ(0) rotateX(-720deg) scale(1) translate(0) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

.drop {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) scale(3) !important;
  transform: translateZ(0) scale(3) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

.drop.on {
  -webkit-transform: translateZ(0) scale(1) !important;
  transform: translateZ(0) scale(1) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

.flip {
  -webkit-backface-visibility: hidden !important;
  backface-visibility: hidden !important;
  -webkit-transform: translateZ(0) rotateY(0) scale(0) !important;
  transform: translateZ(0) rotateY(0) scale(0) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

.flip.on {
  -webkit-transform: translateZ(0) rotateY(-720deg) scale(1) !important;
  transform: translateZ(0) rotateY(-720deg) scale(1) !important;
  -webkit-transition: all 0.5s ease-out !important;
  transition: all 0.5s ease-out !important; }

 
.bold {
  font-weight: bold; }

.static {
  position: static; }

.pull-left {
  float: left; }

.pull-right {
  float: right; }

.double-padding, .ionic-content.double-padding {
  padding: 16px; }

.double-padding-x {
  padding-left: 16px;
  padding-right: 16px; }

.double-padding-y {
  padding-top: 16px;
  padding-bottom: 16px; }

.outline {
  border-style: solid;
  border-width: 1px; }

.border-top {
  border-top: solid 1px #ccc;
  padding-top: 30px; }

.no-border {
  border: none; }

.circle {
  border-radius: 50%; }

.no-padding, .list.no-padding, .bar.no-padding, .button-bar.no-padding, .card.no-padding, .button.no-padding, .item.no-padding {
  padding: 0; }

.flat, .flat.tabs, .flat.button, .flat.button.icon, .flat.hero {
  box-shadow: none;
  -webkit-box-shadow: none; }

 
.im-wrapper, .padding {
  padding: 16px !important; }

.padding-bottom {
  padding-bottom: 16px !important; }

.padding-top {
  padding-top: 16px !important; }

.padding-left {
  padding-left: 16px !important; }

.padding-right {
  padding-right: 16px !important; }

.no-padding-bottom {
  padding-bottom: 0 !important; }

.no-padding-top {
  padding-top: 0 !important; }

.no-padding-left {
  padding-left: 0 !important; }

.no-padding-right {
  padding-right: 0 !important; }

 
.z1 {
  box-shadow: 0px 2px 5px 0 rgba(0, 0, 0, 0.26); }

 
.bar.bar-positive.darker {
  background-color: #164FAB; }

 
.bar.bar-positive.dark-positive-bg {
  background-color: #2C5CAD; }

 
.muted {
  color: #C3C3C3; }

.clear-bg {
  background: transparent; }

 
.animate-blinds .item,
.animate-blinds .item {
  visibility: hidden; }

.animate-blinds .item,
.animate-blinds .item {
  -ms-transform: scale3d(0.8, 0, 1);
  -webkit-transform: scale3d(0.8, 0, 1);
  transform: scale3d(0.8, 0, 1);
  -webkit-transition: -webkit-transform 0.3s cubic-bezier(0.55, 0, 0.1, 1);
  transition: transform 0.3s cubic-bezier(0.55, 0, 0.1, 1); }

.animate-blinds .item-bg-image > img.background,
.animate-blinds .item-bg-image > img.background {
  box-shadow: none;
  -ms-transform: scale3d(1, 1, 1);
  -webkit-transform: scale3d(1, 1, 1);
  transform: scale3d(1, 1, 1); }

.animate-blinds .in,
.animate-blinds.done > *,
.animate-blinds .in,
.animate-blinds.done > * {
  -ms-transform: translate3d(0, 0, 0);
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0); }

.animate-blinds .in,
.animate-blinds.done .item,
.animate-blinds .in,
.animate-blinds.done .item {
  visibility: visible; }

.animate-blinds .item,
.animate-blinds .item {
  visibility: hidden; }

.animate-blinds .item,
.animate-blinds .item {
  opacity: 0;
  filter: alpha(opacity=0); }

.animate-blinds .in,
.animate-blinds.done,
.animate-blinds .in,
.animate-blinds.done {
  -ms-transform: scale3d(1, 1, 1);
  -webkit-transform: scale3d(1, 1, 1);
  transform: scale3d(1, 1, 1);
  -webkit-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-blinds .in,
.animate-blinds.done,
.animate-blinds .in,
.animate-blinds.done {
  visibility: visible; }

.animate-blinds.done .in,
.animate-blinds.done .in {
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-blinds .has-mask-reverse:after,
.animate-blinds .has-mask-reverse:after {
  opacity: 0;
  filter: alpha(opacity=0);
  -webkit-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out; }

.animate-blinds.done .has-mask-reverse:after,
.animate-blinds.done .has-mask-reverse:after {
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-blinds .out,
.animate-blinds .out {
  -ms-transform: scale3d(0, 0, 1);
  -webkit-transform: scale3d(0, 0, 1);
  transform: scale3d(0, 0, 1); }

 
.animate-pan-in-left,
.animate-pan-in-left {
  background-position: 0% 0%; }

 
.animate-ripple .done,
.animate-ripple .done {
  visibility: hidden; }

.animate-ripple .done,
.animate-ripple .done {
  -ms-transform: scale3d(0.8, 0, 1);
  -webkit-transform: scale3d(0.8, 0, 1);
  transform: scale3d(0.8, 0, 1);
  -webkit-transition: -webkit-transform 0.3s cubic-bezier(0.55, 0, 0.1, 1);
  transition: transform 0.3s cubic-bezier(0.55, 0, 0.1, 1); }

.animate-ripple .item-bg-image img.background,
.animate-ripple .item-bg-image img.background {
  box-shadow: none;
  -ms-transform: scale3d(1, 1, 1);
  -webkit-transform: scale3d(1, 1, 1);
  transform: scale3d(1, 1, 1); }

.animate-ripple .in, .animate-ripple.done,
.animate-ripple .in, .animate-ripple.done {
  -ms-transform: scale3d(1, 1, 1);
  -webkit-transform: scale3d(1, 1, 1);
  transform: scale3d(1, 1, 1); }

.animate-ripple .in, .animate-ripple.done,
.animate-ripple .in, .animate-ripple.done {
  visibility: visible; }

.animate-ripple .item {
  -ms-transform: scale3d(0, 0, 1);
  -webkit-transform: scale3d(0, 0, 1);
  transform: scale3d(0, 0, 1);
  opacity: 0;
  filter: alpha(opacity=0); }

.animate-ripple .item.in {
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-ripple .done {
  visibility: hidden; }

.animate-ripple .done,
.animate-ripple .done {
  -ms-transform: scale3d(0.8, 0, 1);
  -webkit-transform: scale3d(0.8, 0, 1);
  transform: scale3d(0.8, 0, 1);
  -webkit-transition: -webkit-transform 0.3s cubic-bezier(0.55, 0, 0.1, 1);
  transition: transform 0.3s cubic-bezier(0.55, 0, 0.1, 1); }

 
  .animate-ripple .in .item-bg-image img:last-child,
  .animate-ripple .in .item-bg-image img:last-child { 
    opacity: 0;
  }

    .animate-ripple.done .item-bg-image img:last-child,
    .animate-ripple.done .item-bg-image img:last-child {
    opacity: 1;
    -moz-transition: all 1s ease-in-out;
    -o-transition: all 1s ease-in-out;
    -webkit-transition: all 1s ease-in-out;
    transition: all 1s ease-in-out;
    }

    .animate-ripple .item-bg-image img:last-child,
  .animate-ripple .item-bg-image img:last-child {
    box-shadow: none;
    -moz-transform: scale3d(1, 1, 1);
    -ms-transform: scale3d(1, 1, 1);
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
  }
  .animate-ripple .in .item-bg-image img:last-child,
  .animate-ripple .in .item-bg-image img:last-child { 
    opacity: 0;
  }

.animate-ripple.done .item-bg-image img:last-child,
.animate-ripple.done .item-bg-image img:last-child {
  opacity: 1;
    -moz-transition: all 0.3s ease-in-out;
    -o-transition: all 0.3s ease-in-out;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.animate-ripple .in,
.animate-ripple .in {
    opacity: 0.6;
}
 
.animate-ripple .in, .animate-ripple.done, .animate-ripple .in, .animate-ripple.done {
  -ms-transform: scale3d(1, 1, 1);
  -webkit-transform: scale3d(1, 1, 1);
  transform: scale3d(1, 1, 1);
  -webkit-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out; }

.animate-ripple .in, .animate-ripple.done, .animate-ripple .in, .animate-ripple.done {
  visibility: visible; }

.animate-ripple.done .in, .animate-ripple.done .in {
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-ripple .has-mask-reverse:after, .animate-ripple .has-mask-reverse:after {
  opacity: 0;
  filter: alpha(opacity=0);
  -webkit-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out; }

.animate-ripple.done .has-mask-reverse:after, .animate-ripple.done .has-mask-reverse:after {
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-ripple .out, .animate-ripple .out {
  -ms-transform: scale3d(0, 0, 1);
  -webkit-transform: scale3d(0, 0, 1);
  transform: scale3d(0, 0, 1); }

 
.animate-fade-slide-in .item,
.animate-fade-slide-in .item {
  visibility: hidden; }

.animate-fade-slide-in .item,
.animate-fade-slide-in .item {
  -ms-transform: scale3d(0.8, 0, 1);
  -webkit-transform: scale3d(0.8, 0, 1);
  transform: scale3d(0.8, 0, 1);
  -webkit-transition: -webkit-transform 0.3s cubic-bezier(0.55, 0, 0.1, 1);
  transition: transform 0.3s cubic-bezier(0.55, 0, 0.1, 1); }

.animate-fade-slide-in .item-bg-image img.background,
.animate-fade-slide-in .item-bg-image img.background {
  box-shadow: none;
  -ms-transform: scale3d(1, 1, 1);
  -webkit-transform: scale3d(1, 1, 1);
  transform: scale3d(1, 1, 1); }

.animate-fade-slide-in .in,
.animate-fade-slide-in.done .item,
.animate-fade-slide-in .in,
.animate-fade-slide-in.done .item {
  -ms-transform: translate3d(0, 0, 0);
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0); }

.animate-fade-slide-in .in,
.animate-fade-slide-in.done .item,
.animate-fade-slide-in .in,
.animate-fade-slide-in.done .item {
  visibility: visible; }

.list .item.item-bg-image,
.list .item.item-bg-image {
  max-height: 150px; }

.animate-fade-slide-in .item,
.animate-fade-slide-in .item {
  visibility: hidden; }

.animate-fade-slide-in .item,
.animate-fade-slide-in .item {
  -ms-transform: translate3d(-250px, 250px, 0);
  -webkit-transform: translate3d(-250px, 250px, 0);
  transform: translate3d(-250px, 250px, 0);
  -webkit-transition: -webkit-transform 0.5s cubic-bezier(0.55, 0, 0.1, 1);
  transition: transform 0.5s cubic-bezier(0.55, 0, 0.1, 1);
  opacity: 0;
  filter: alpha(opacity=0); }

.animate-fade-slide-in .in,
.animate-fade-slide-in.done,
.animate-fade-slide-in .in,
.animate-fade-slide-in.done {
  -ms-transform: scale3d(1, 1, 1);
  -webkit-transform: scale3d(1, 1, 1);
  transform: scale3d(1, 1, 1);
  -webkit-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-fade-slide-in .in,
.animate-fade-slide-in.done,
.animate-fade-slide-in .in,
.animate-fade-slide-in.done {
  visibility: visible; }

.animate-fade-slide-in.done .in,
.animate-fade-slide-in.done .in {
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-fade-slide-in .has-mask-reverse:after,
.animate-fade-slide-in .has-mask-reverse:after {
  opacity: 0;
  filter: alpha(opacity=0);
  -webkit-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out; }

.animate-fade-slide-in.done .has-mask-reverse:after,
.animate-fade-slide-in.done .has-mask-reverse:after {
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-fade-slide-in .out,
.animate-fade-slide-in .out {
  -ms-transform: scale3d(0, 0, 1);
  -webkit-transform: scale3d(0, 0, 1);
  transform: scale3d(0, 0, 1); }

 
.animate-fade-slide-in-right .item,
.animate-fade-slide-in-right .item {
  visibility: hidden; }

.animate-fade-slide-in-right .item,
.animate-fade-slide-in-right .item {
  -ms-transform: scale3d(0.8, 0, 1);
  -webkit-transform: scale3d(0.8, 0, 1);
  transform: scale3d(0.8, 0, 1);
  -webkit-transition: -webkit-transform 0.3s cubic-bezier(0.55, 0, 0.1, 1);
  transition: transform 0.3s cubic-bezier(0.55, 0, 0.1, 1); }

.animate-fade-slide-in-right .item-bg-image > img.background,
.animate-fade-slide-in-right .item-bg-image > img.background {
  box-shadow: none;
  -ms-transform: scale3d(1, 1, 1);
  -webkit-transform: scale3d(1, 1, 1);
  transform: scale3d(1, 1, 1); }

.animate-fade-slide-in-right .in,
.animate-fade-slide-in-right.done > *,
.animate-fade-slide-in-right .in,
.animate-fade-slide-in-right.done > * {
  -ms-transform: translate3d(0, 0, 0);
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0); }

.animate-fade-slide-in-right .in,
.animate-fade-slide-in-right.done .item,
.animate-fade-slide-in-right .in,
.animate-fade-slide-in-right.done .item {
  visibility: visible; }

.animate-fade-slide-in-right .item,
.animate-fade-slide-in-right .item {
  visibility: hidden; }

.animate-fade-slide-in-right .item,
.animate-fade-slide-in-right .item {
  -ms-transform: translate3d(250px, 250px, 0);
  -webkit-transform: translate3d(250px, 250px, 0);
  transform: translate3d(250px, 250px, 0);
  -webkit-transition: -webkit-transform 0.5s cubic-bezier(0.55, 0, 0.1, 1);
  transition: transform 0.5s cubic-bezier(0.55, 0, 0.1, 1);
  opacity: 0;
  filter: alpha(opacity=0); }

.animate-fade-slide-in-right .in,
.animate-fade-slide-in-right.done,
.animate-fade-slide-in-right .in,
.animate-fade-slide-in-right.done {
  -ms-transform: scale3d(1, 1, 1);
  -webkit-transform: scale3d(1, 1, 1);
  transform: scale3d(1, 1, 1);
  -webkit-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-fade-slide-in-right .in,
.animate-fade-slide-in-right.done,
.animate-fade-slide-in-right .in,
.animate-fade-slide-in-right.done {
  visibility: visible; }

.animate-fade-slide-in-right.done .in,
.animate-fade-slide-in-right.done .in {
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-fade-slide-in-right .has-mask-reverse:after,
.animate-fade-slide-in-right .has-mask-reverse:after {
  opacity: 0;
  filter: alpha(opacity=0);
  -webkit-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out; }

.animate-fade-slide-in-right.done .has-mask-reverse:after,
.animate-fade-slide-in-right.done .has-mask-reverse:after {
  opacity: 1;
  filter: alpha(opacity=100); }

.animate-fade-slide-in-right .out,
.animate-fade-slide-in-right .out {
  -ms-transform: scale3d(0, 0, 1);
  -webkit-transform: scale3d(0, 0, 1);
  transform: scale3d(0, 0, 1); }

 
.slide-up,
.slide-up,
.hero.slide-up {
  height: 100%;
  overflow: hidden;
  text-align: center; }

.slide-up {
  -webkit-transition: all 1s cubic-bezier(0.55, 0, 0.1, 1);
  transition: all 1s cubic-bezier(0.55, 0, 0.1, 1);
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0); }

.slide-up *,
.slide-up *,
.hero.slide-up * {
  opacity: 0;
  filter: alpha(opacity=0); }

.hero.slide-up + .mid-bar,
.slide-up + .mid-bar,
.slide-up + .mid-bar {
  height: 100%;
  opacity: 0.7;
  filter: alpha(opacity=70);
  -webkit-transform: translate3d(100%, -240px, 0);
  transform: translate3d(100%, -240px, 0); }

 
.ink, .button-fab, .button-flat, .button-raised, .button-clear, .popup .button {
  position: relative;
  cursor: pointer;
  /*display: inline-block;*/
  overflow: hidden;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  -webkit-tap-highlight-color: transparent;
  -webkit-transition: all 0.3s ease-out;
  -moz-transition: all 0.3s ease-out;
  -o-transition: all 0.3s ease-out;
  transition: all 0.3s ease-out; }

.ink-ripple {
  position: absolute;
  border-radius: 50%;
  width: 100px;
  height: 100px;
  margin-top: -50px;
  margin-left: -50px;
  opacity: 0;
  background-color: rgba(255, 255, 255, 0.4);
  -webkit-transition: all 0.5s ease-out;
  -moz-transition: all 0.5s ease-out;
  -o-transition: all 0.5s ease-out;
  transition: all 0.5s ease-out;
  -webkit-transition-property: -webkit-transform, opacity;
  -moz-transition-property: -moz-transform, opacity;
  -o-transition-property: -o-transform, opacity;
  transition-property: transform, opacity;
  -webkit-transform: scale(0);
  -moz-transform: scale(0);
  -ms-transform: scale(0);
  -o-transform: scale(0);
  transform: scale(0);
  pointer-events: none; }

.ink-notransition {
  -webkit-transition: none !important;
  -moz-transition: none !important;
  -o-transition: none !important;
  transition: none !important; }

.button-fab,
.button-flat,
.button-clear,
.button-raised,
.ink-button,
.ink-circle {
  -webkit-transform: translateZ(0);
  -moz-transform: translateZ(0);
  -ms-transform: translateZ(0);
  -o-transform: translateZ(0);
  transform: translateZ(0); }

.button-fab.activated,
.button-flat.activated,
.button-raised.activated,
.button-clear.activated,
.ink-button.activated,
.ink.activated,
.ink-circle.activated,
.popup .button.activated,
.button-fab:active,
.button-flat:active,
.button-raised:active,
.button-clear:active,
.ink-button:active,
.ink:active,
.ink-circle:active,
.popup .button:active {
  -webkit-mask-image: -webkit-radial-gradient(circle, #ffffff 100%, #000000 100%); }

.ink-button,
.ink-button:visited,
.ink-button:link,
.button-fab,
.button-fab:visited,
.button-fab:link,
.button-flat,
.button-flat:visited,
.button-flat:link,
.button-raised,
.button-raised:visited,
.button-raised:link,
.button-clear,
.button-clear:visited,
.button-clear:link,
.ink-button-input {
  white-space: nowrap;
  vertical-align: middle;
  cursor: pointer;
  border: none;
  outline: none;
  /*  color: inherit; */
  /*  background-color: rgba(0, 0, 0, 0); */
  font-size: 14px;
  text-align: center;
  text-decoration: none;
  z-index: 1; }

.ink-button {
  padding: 10px 15px;
  border-radius: 2px; }

.ink-button-input {
  margin: 0;
  padding: 10px 15px; }

.ink-input-wrapper {
  border-radius: 2px;
  vertical-align: bottom; }

.ink-input-wrapper.ink-button {
  padding: 0; }

.ink-input-wrapper .ink-button-input {
  position: relative;
  top: 0;
  left: 0;
  z-index: 1; }

.ink-circle {
  text-align: center;
  width: 2.5em;
  height: 2.5em;
  line-height: 2.5em;
  border-radius: 50%; }

.ink-float {
  -webkit-mask-image: none;
  -webkit-box-shadow: 0px 1px 1.5px 1px rgba(0, 0, 0, 0.12);
  box-shadow: 0px 1px 1.5px 1px rgba(0, 0, 0, 0.12); }

.ink-float:active {
  -webkit-box-shadow: 0px 8px 20px 1px rgba(0, 0, 0, 0.3);
  box-shadow: 0px 8px 20px 1px rgba(0, 0, 0, 0.3); }

.ink-block {
  display: block; }

.ink-ripple {
  z-index: 0;
  /* Firefox Bug: link not triggered unless -1 z-index */ }

/* Handled elsewhere
.button-fab,.button-raised,.button-flat,.ink-circle,.list a.item {
    -webkit-mask-image: none;
}
*/
.button-clear .ink-ripple,
*[class$='-clear'] > .ink-ripple,
*[class$='-light'] > .ink-ripple,
*[class$='-stable'] > .ink-ripple,
*[class$='-100'] > .ink-ripple,
.list .ink-ripple,
.ink-dark .ink-ripple {
  background-color: rgba(0, 0, 0, 0.2); }

.tab-item {
  position: relative;
}


* {
  font-family: 'RobotoDraft','Roboto','Helvetica Neue', 'Segoe UI', sans-serif; }

.rounded {
  border-radius: 4px; }

a {
  cursor: pointer; }

.has-header.expanded {
  top: 76px; }
 
.bar {
  border-bottom: none;
  padding: 0; }

.bar .button {
  min-height: 44px;
  min-width: 44px;
  max-width: 48px;
  margin-bottom: 0;
  max-height: 44px;
  width: 48px; }

.bar .title + .buttons.buttons-right {
  right: 0;
  top: 0; }

 
.title-left,
.title.title-left {
  left: 48px; }

.title-right,
.title.title-right {
  left: 48px; }

 
.positive-bg,
.button-positive,
.bar .button-positive,
.header-positive,
.button-bar-positive,
.bar-positive,
.positive-border,
.positive-bg:hover,
.bar .button-positive:hover,
.button-positive:hover,
.header-positive:hover,
.button-bar-positive:hover,
.bar-positive:hover,
.positive-border:hover,
.positive-bg:active,
.bar .button-positive:active,
.button-positive:active,
.header-positive:active,
.button-bar-positive:active,
.bar-positive:active,
.positive-border:active,
.positive-bg.activated,
.bar .button-positive.activated,
.button-positive.activated,
.header-positive.activated,
.button-bar-positive.activated,
.bar-positive.activated,
.positive-border.activated {
  background-color: $material_positive;
  color: #fff; }

.positive-900-bg,
.button-positive-900,
.bar .button-positive-900,
.header-positive-900,
.button-bar-positive-900,
.bar-positive-900,
.positive-900-border,
.positive-900-bg:hover,
.button-positive-900:hover,
.bar .button-positive-900:hover,
.header-positive-900:hover,
.button-bar-positive-900:hover,
.bar-positive-900:hover,
.positive-900-border:hover,
.positive-900-bg:active,
.bar .button-positive-900:active,
.button-positive-900:active,
.header-positive-900:active,
.button-bar-positive-900:active,
.bar-positive-900:active,
.positive-900-border:active,
.positive-900-bg.activated,
.button-positive-900.activated,
.bar .button-positive-900.activated,
.header-positive-900.activated,
.button-bar-positive-900.activated,
.bar-positive-900.activated,
.positive-900-border.activated {
  background-color: $material_positive900;
  color: #fff; }

.positive-100-bg,
.button-positive-100,
.bar .button-positive-100,
.header-positive-100,
.button-bar-positive-100,
.bar-positive-100,
.positive-100-border,
.positive-100-bg:hover,
.button-positive-100:hover,
.bar .button-positive-100:hover,
.header-positive-100:hover,
.button-bar-positive-100:hover,
.bar-positive-100:hover,
.positive-100-border:hover,
.positive-100-bg:active,
.button-positive-100:active,
.bar .button-positive-100:active,
.header-positive-100:active,
.button-bar-positive-100:active,
.bar-positive-100:active,
.positive-100-border:active,
.positive-100-bg.activated,
.button-positive-100.activated,
.bar .button-positive-100.activated,
.header-positive-100.activated,
.button-bar-positive-100.activated,
.bar-positive-100.activated,
.positive-100-border.activated {
  background-color: $material_positive100;
  color: #fff; }

.calm-bg,
.button-calm,
.bar .button-calm,
.header-calm,
.button-bar-calm,
.bar-calm,
.calm-border,
.calm-bg:hover,
.button-calm:hover,
.bar .button-calm:hover,
.header-calm:hover,
.button-bar-calm:hover,
.bar-calm:hover,
.calm-border:hover,
.calm-bg:active,
.button-calm:active,
.bar .button-calm:active,
.header-calm:active,
.button-bar-calm:active,
.bar-calm:active,
.calm-border:active,
.calm-bg.activated,
.button-calm.activated,
.bar .button-calm.activated,
.header-calm.activated,
.button-bar-calm.activated,
.bar-calm.activated,
.calm-border.activated {
  background-color: $material_calm;
  color: #fff; }

.calm-900-bg,
.button-calm-900,
.bar .button-calm-900,
.header-calm-900,
.button-bar-calm-900,
.bar-calm-900,
.calm-900-border,
.calm-900-bg:hover,
.button-calm-900:hover,
.bar .button-calm-900:hover,
.header-calm-900:hover,
.button-bar-calm-900:hover,
.bar-calm-900:hover,
.calm-900-border:hover,
.calm-900-bg:active,
.button-calm-900:active,
.bar .button-calm-900:active,
.header-calm-900:active,
.button-bar-calm-900:active,
.bar-calm-900:active,
.calm-900-border:active,
.calm-900-bg.activated,
.button-calm-900.activated,
.bar .button-calm-900.activated,
.header-calm-900.activated,
.button-bar-calm-900.activated,
.bar-calm-900.activated,
.calm-900-border.activated {
  background-color: $material_calm900;
  color: #fff; }

.calm-100-bg,
.button-calm-100,
.bar .button-calm-100,
.header-calm-100,
.button-bar-calm-100,
.bar-calm-100,
.calm-100-border,
.calm-100-bg:hover,
.button-calm-100:hover,
.bar .button-calm-100:hover,
.header-calm-100:hover,
.button-bar-calm-100:hover,
.bar-calm-100:hover,
.calm-100-border:hover,
.calm-100-bg:active,
.button-calm-100:active,
.bar .button-calm-100:active,
.header-calm-100:active,
.button-bar-calm-100:active,
.bar-calm-100:active,
.calm-100-border:active,
.calm-100-bg.activated,
.button-calm-100.activated,
.bar .button-calm-100.activated,
.header-calm-100.activated,
.button-bar-calm-100.activated,
.bar-calm-100.activated,
.calm-100-border.activated {
  background-color: $material_calm100;
  color: #fff; }

.royal-bg,
.button-royal,
.bar .button-royal,
.header-royal,
.button-bar-royal,
.bar-royal,
.royal-border,
.royal-bg:hover,
.button-royal:hover,
.bar .button-royal:hover,
.header-royal:hover,
.button-bar-royal:hover,
.bar-royal:hover,
.royal-border:hover,
.royal-bg:active,
.button-royal:active,
.bar .button-royal:active,
.header-royal:active,
.button-bar-royal:active,
.bar-royal:active,
.royal-border:active,
.royal-bg.activated,
.button-royal.activated,
.bar .button-royal.activated,
.header-royal.activated,
.button-bar-royal.activated,
.bar-royal.activated,
.royal-border.activated {
  background-color: $material_royal;
  color: #fff; }

.royal-900-bg,
.button-royal-900,
.bar .button-royal-900,
.header-royal-900,
.button-bar-royal-900,
.bar-royal-900,
.royal-900-border,
.royal-900-bg:hover,
.button-royal-900:hover,
.bar .button-royal-900:hover,
.header-royal-900:hover,
.button-bar-royal-900:hover,
.bar-royal-900:hover,
.royal-900-border:hover,
.royal-900-bg:active,
.button-royal-900:active,
.bar .button-royal-900:active,
.header-royal-900:active,
.button-bar-royal-900:active,
.bar-royal-900:active,
.royal-900-border:active,
.royal-900-bg.activated,
.button-royal-900.activated,
.bar .button-royal-900.activated,
.header-royal-900.activated,
.button-bar-royal-900.activated,
.bar-royal-900.activated,
.royal-900-border.activated {
  background-color: $material_royal900;
  color: #fff; }

.royal-100-bg,
.button-royal-100,
.bar .button-royal-100,
.header-royal-100,
.button-bar-royal-100,
.bar-royal-100,
.royal-100-border,
.royal-100-bg:hover,
.button-royal-100:hover,
.bar .button-royal-100:hover,
.header-royal-100:hover,
.button-bar-royal-100:hover,
.bar-royal-100:hover,
.royal-100-border:hover,
.royal-100-bg:active,
.button-royal-100:active,
.bar .button-royal-100:active,
.header-royal-100:active,
.button-bar-royal-100:active,
.bar-royal-100:active,
.royal-100-border:active,
.royal-100-bg.activated,
.button-royal-100.activated,
.bar .button-royal-100.activated,
.header-royal-100.activated,
.button-bar-royal-100.activated,
.bar-royal-100.activated,
.royal-100-border.activated {
  background-color: $material_royal100;
  color: #fff; }

.balanced-bg,
.button-balanced,
.bar .button-balanced,
.header-balanced,
.button-bar-balanced,
.bar-balanced,
.balanced-border,
.balanced-bg:hover,
.button-balanced:hover,
.bar .button-balanced:hover,
.header-balanced:hover,
.button-bar-balanced:hover,
.bar-balanced:hover,
.balanced-border:hover,
.balanced-bg:active,
.button-balanced:active,
.bar .button-balanced:active,
.header-balanced:active,
.button-bar-balanced:active,
.bar-balanced:active,
.balanced-border:active,
.balanced-bg.activated,
.button-balanced.activated,
.bar .button-balanced.activated,
.header-balanced.activated,
.button-bar-balanced.activated,
.bar-balanced.activated,
.balanced-border.activated {
  background-color: $material_balanced;
  color: #fff; }

.balanced-900-bg,
.button-balanced-900,
.bar .button-balanced-900,
.header-balanced-900,
.button-bar-balanced-900,
.bar-balanced-900,
.balanced-900-border,
.balanced-900-bg:hover,
.button-balanced-900:hover,
.bar .button-balanced-900:hover,
.header-balanced-900:hover,
.button-bar-balanced-900:hover,
.bar-balanced-900:hover,
.balanced-900-border:hover,
.balanced-900-bg:active,
.button-balanced-900:active,
.bar .button-balanced-900:active,
.header-balanced-900:active,
.button-bar-balanced-900:active,
.bar-balanced-900:active,
.balanced-900-border:active,
.balanced-900-bg.activated,
.button-balanced-900.activated,
.bar .button-balanced-900.activated,
.header-balanced-900.activated,
.button-bar-balanced-900.activated,
.bar-balanced-900.activated,
.balanced-900-border.activated {
  background-color: $material_balanced900;
  color: #fff; }

.balanced-100-bg,
.button-balanced-100,
.bar .button-balanced-100,
.header-balanced-100,
.button-bar-balanced-100,
.bar-balanced-100,
.balanced-100-border,
.balanced-100-bg:hover,
.button-balanced-100:hover,
.bar .balanced-100-bg:hover,
.header-balanced-100:hover,
.button-bar-balanced-100:hover,
.bar-balanced-100:hover,
.balanced-100-border:hover,
.balanced-100-bg:active,
.button-balanced-100:active,
.bar .button-balanced-100:active,
.header-balanced-100:active,
.button-bar-balanced-100:active,
.bar-balanced-100:active,
.balanced-100-border:active,
.balanced-100-bg.activated,
.button-balanced-100.activated,
.bar .button-balanced-100.activated,
.header-balanced-100.activated,
.button-bar-balanced-100.activated,
.bar-balanced-100.activated,
.balanced-100-border.activated {
  background-color: $material_balanced100;
  color: #fff; }

.energized-bg,
.button-energized,
.bar .button-energized,
.header-energized,
.button-bar-energized,
.bar-energized,
.energized-border,
.energized-bg:hover,
.button-energized:hover,
.bar .button-energized:hover,
.header-energized:hover,
.button-bar-energized:hover,
.bar-energized:hover,
.energized-border:hover,
.energized-bg:active,
.button-energized:active,
.bar .button-energized:active,
.header-energized:active,
.button-bar-energized:active,
.bar-energized:active,
.energized-border:active,
.energized-bg.activated,
.button-energized.activated,
.bar .button-energized.activated,
.header-energized.activated,
.button-bar-energized.activated,
.bar-energized.activated,
.energized-border.activated {
  background-color: $material_energized;
  color: #fff; }

.energized-900-bg,
.button-energized-900,
.bar .button-energized-900,
.header-energized-900,
.button-bar-energized-900,
.bar-energized-900,
.energized-900-border,
.energized-900-bg:hover,
.button-energized-900:hover,
.bar .button-energized-900:hover,
.header-energized-900:hover,
.button-bar-energized-900:hover,
.bar-energized-900:hover,
.energized-900-border:hover,
.energized-900-bg:active,
.button-energized-900:active,
.bar .button-energized-900:active,
.header-energized-900:active,
.button-bar-energized-900:active,
.bar-energized-900:active,
.energized-900-border:active,
.energized-900-bg.activated,
.button-energized-900.activated,
.bar .button-energized-900.activated,
.header-energized-900.activated,
.button-bar-energized-900.activated,
.bar-energized-900.activated,
.energized-900-border.activated {
  background-color: $material_energized900;
  color: #fff; }

.energized-100-bg,
.button-energized-100,
.bar .button-energized-100,
.header-energized-100,
.button-bar-energized-100,
.bar-energized-100,
.energized-100-border,
.energized-100-bg:hover,
.button-energized-100:hover,
.bar .button-energized-100:hover,
.header-energized-100:hover,
.button-bar-energized-100:hover,
.bar-energized-100:hover,
.energized-100-border:hover,
.energized-100-bg:active,
.button-energized-100:active,
.bar .button-energized-100:active,
.header-energized-100:active,
.button-bar-energized-100:active,
.bar-energized-100:active,
.energized-100-border:active,
.energized-100-bg.activated,
.button-energized-100.activated,
.bar .button-energized-100.activated,
.header-energized-100.activated,
.button-bar-energized-100.activated,
.bar-energized-100.activated,
.energized-100-border.activated {
  background-color: $material_energized100; }

.assertive-bg,
.button-assertive,
.bar .button-assertive,
.header-assertive,
.button-bar-assertive,
.bar-assertive,
.assertive-border,
.assertive-bg:hover,
.button-assertive:hover,
.bar .button-assertive:hover,
.header-assertive:hover,
.button-bar-assertive:hover,
.bar-assertive:hover,
.assertive-border:hover,
.assertive-bg:active,
.button-assertive:active,
.bar .button-assertive:active,
.header-assertive:active,
.button-bar-assertive:active,
.bar-assertive:active,
.assertive-border:active,
.assertive-bg.activated,
.button-assertive.activated,
.bar .button-assertive.activated,
.header-assertive.activated,
.button-bar-assertive.activated,
.bar-assertive.activated,
.assertive-border.activated {
  background-color: $material_assertive;
  color: #fff; }

.assertive-900-bg,
.button-assertive-900,
.bar .button-assertive-900,
.header-assertive-900,
.button-bar-assertive-900,
.bar-assertive-900,
.assertive-900-border,
.assertive-900-bg:hover,
.button-assertive-900:hover,
.bar .button-assertive-900:hover,
.header-assertive-900:hover,
.button-bar-assertive-900:hover,
.bar-assertive-900:hover,
.assertive-900-border:hover,
.assertive-900-bg:active,
.button-assertive-900:active,
.bar .button-assertive-900:active,
.header-assertive-900:active,
.button-bar-assertive-900:active,
.bar-assertive-900:active,
.assertive-900-border:active,
.assertive-900-bg.activated,
.button-assertive-900.activated,
.bar .button-assertive-900.activated,
.header-assertive-900.activated,
.button-bar-assertive-900.activated,
.bar-assertive-900.activated,
.assertive-900-border.activated {
  background-color: $material_assertive900;
  color: #fff; }

.assertive-100-bg,
.button-assertive-100,
.bar .button-assertive-100,
.header-assertive-100,
.button-bar-assertive-100,
.bar-assertive-100,
.assertive-100-border,
.assertive-100-bg:hover,
.button-assertive-100:hover,
.bar .button-assertive-100:hover,
.header-assertive-100:hover,
.button-bar-assertive-100:hover,
.bar-assertive-100:hover,
.assertive-100-border:hover,
.assertive-100-bg:active,
.button-assertive-100:active,
.bar .button-assertive-100:active,
.header-assertive-100:active,
.button-bar-assertive-100:active,
.bar-assertive-100:active,
.assertive-100-border:active,
.assertive-100-bg.activated,
.bar .button-assertive-100.activated,
.button-assertive-100.activated,
.header-assertive-100.activated,
.button-bar-assertive-100.activated,
.bar-assertive-100.activated,
.assertive-100-border.activated {
  background-color: $material_assertive100;
  color: #fff; }

.stable-bg,
.button-stable,
.bar .button-stable,
.header-stable,
.button-bar-stable,
.bar-stable,
.stable-border,
.stable-bg:hover,
.button-stable:hover,
.bar .button-stable:hover,
.header-stable:hover,
.button-bar-stable:hover,
.bar-stable:hover,
.stable-border:hover,
.stable-bg:active,
.button-stable:active,
.bar .button-stable:active,
.header-stable:active,
.button-bar-stable:active,
.bar-stable:active,
.stable-border:active,
.stable-bg.activated,
.button-stable.activated,
.bar .button-stable.activated,
.header-stable.activated,
.button-bar-stable.activated,
.bar-stable.activated,
.stable-border.activated {
  background-color: $material_stable;
  color: #fff; }

 
.positive,
.positive *,
*.positive,
.positive:hover,
.positive:hover *,
*.positive:hover,
.positive:active,
.positive:active *,
*.positive:active {
  color: $material_positive; }

.positive-900,
.positive-900 *,
*.positive-900,
.positive-900:hover,
.positive-900:hover *,
*.positive-900:hover,
.positive-900:active,
.positive-900:active *,
*.positive-900:active {
  color: $material_positive; }

.positive-100,
.positive-100 *,
*.positive-100,
.positive-100:hover,
.positive-100:hover *,
*.positive-100:hover,
.positive-100:active,
.positive-100:active *,
*.positive-100:active {
  color: $material_positive100; }

.calm-100,
.calm-100 *,
*.calm-100,
.calm-100:hover,
.calm-100:hover *,
*.calm-100:hover,
.calm-100:active,
.calm-100:active *,
*.calm-100:active {
  color: $material_calm; }

.calm-900,
.calm-900 *,
*.calm-900,
.calm-900:hover,
.calm-900:hover *,
*.calm-900:hover,
.calm-900:active,
.calm-900:active *,
*.calm-900:active {
  color: $material_calm900; }

.calm-100,
.calm-100 *,
*.calm-100,
.calm-100:hover,
.calm-100:hover *,
*.calm-100:hover,
.calm-100:active,
.calm-100:active *,
*.calm-100:active {
  color: ; }

.royal,
.royal *,
*.royal,
.royal:hover,
.royal:hover *,
*.royal:hover,
.royal:active,
.royal:active *,
*.royal:active {
  color: $material_royal; }

.royal-900,
.royal-900 *,
*.royal-900,
.royal-900:hover,
.royal-900:hover *,
*.royal-900:hover,
.royal-900:active,
.royal-900:active *,
*.royal-900:active {
  color: $material_royal900; }

.royal-100,
.royal-100 *,
*.royal-100,
.royal-100:hover,
.royal-100:hover *,
*.royal-100:hover,
.royal-100:active,
.royal-100:active *,
*.royal-100:active {
  color: $material_royal100; }

.balanced,
.balanced *,
*.balanced,
.balanced:hover,
.balanced:hover *,
*.balanced:hover,
.balanced:active,
.balanced:active *,
*.balanced:active {
  color: $material_balanced; }

.balanced-900,
.balanced-900 *,
*.balanced-900,
.balanced-900:hover,
.balanced-900:hover *,
*.balanced-900:hover,
.balanced-900:active,
.balanced-900:active *,
*.balanced-900:active {
  color: $material_balanced900; }

.balanced-100,
.balanced-100 *,
*.balanced-100,
.balanced-100:hover,
.balanced-100:hover *,
*.balanced-100:hover,
.balanced-100:active,
.balanced-100:active *,
*.balanced-100:active {
  color: $material_balanced100; }

.energized,
.energized *,
*.energized,
.energized:hover,
.energized:hover *,
*.energized:hover,
.energized:active,
.energized:active *,
*.energized:active {
  color: $material_energized; }

.energized-900,
.energized-900 *,
*.energized-900,
.energized-900:hover,
.energized-900:hover *,
*.energized-900:hover,
.energized-900:active,
.energized-900:active *,
*.energized-900:active {
  color: $material_energized900; }

.energized-100,
.energized-100 *,
*.energized-100,
.energized-100:hover,
.energized-100:hover *,
*.energized-100:hover,
.energized-100:active,
.energized-100:active *,
*.energized-100:active {
  color: $material_energized100; }

.assertive,
.assertive *,
*.assertive,
.assertive:hover,
.assertive:hover *,
*.assertive:hover,
.assertive:active,
.assertive:active *,
*.assertive:active {
  color: $material_assertive }

.assertive-900,
.assertive-900 *,
*.assertive-900,
.assertive-900:hover,
.assertive-900:hover *,
*.assertive-900:hover,
.assertive-900:active,
.assertive-900:active *,
*.assertive-900:active {
  color: $material_assertive900 }

.assertive-100,
.assertive-100 *,
*.assertive-100,
.assertive-100:hover,
.assertive-100:hover *,
*.assertive-100:hover,
.assertive-100:active,
.assertive-100:active *,
*.assertive-100:active {
  color: $material_assertive100; }

.stable,
.stable *,
*.stable,
.stable:hover,
.stable:hover *,
*.stable:hover,
.stable:active,
.stable:active *,
*.stable:active {
  color: $material_stable; }

.light,
.light *,
*.light,
.light:hover,
.light:hover *,
*.light:hover,
.light:active,
.light:active *,
*.light:active {
  color: #fff; }

.dark,
.dark *,
*.dark,
.dark:hover,
.dark:hover *,
*.dark:hover,
.dark:active,
.dark:active *,
*.dark:active {
  color: #444; }

.light-border {
  border-color: #ddd; }

.navbar-default .navbar-nav > li > a {
  margin: 0;
  padding-right: 26px;
  padding-left: 26px;
  border-top: 3px solid transparent;
  color: #BFD5C9;
  opacity: 1; }

 
.mid-bar {
  padding: 16px; }

.mid-bar h1,
.mid-bar h2,
.mid-bar h3,
.mid-bar h4,
.mid-bar h5,
.mid-bar h6 {
  color: #fff;
  margin-bottom: 5px; }

.mid-bar p {
  color: rgba(255, 255, 255, 0.5);
  margin-bottom: 0; }

 
.item-avatar,
.item-avatar .item-content,
.item-avatar-left,
.item-avatar-left .item-content,
.card > .item-avatar {
  padding-left: 95px; }

.item,
.item-complex .item-content,
.item-radio .item-content {
  background-color: transparent; }

.dark-bg h2,
.item.dark-bg h2 {
  color: #fff; }

.tabs-striped .tabs {
  box-shadow: 0px 2px 5px 0 rgba(0, 0, 0, 0.26); }

.bar .button.button-clear {
  color: #fff; }

.bar .button.button-icon .icon:before,
.bar .button.button-icon.icon-left:before,
.bar .button.button-icon.icon-right:before,
.bar .button.button-icon:before {
  vertical-align: top;
  font-size: 24px; }

.menu {
  background-color: transparent;
}

.button-icon.button.active,
.button-icon.button.activated {
  opacity: initial; 
  }

 
.popover {
  opacity: 0;
  position: absolute;
  right: 8px;
  transform: translate(50%, -50%) scale(0, 0);
  transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
  top: 8px; }

.popover.ng-enter {
  opacity: 1;
  transform: translate(0, -14px) scale(1, 1);
  transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out; }

.popover.ng-leave {
  opacity: 0;
  transform: translate(50%, -50%) scale(0, 0);
  transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out; }

 
.button {
  overflow: hidden !important; }

.tabs-background-energized-900 .tabs{
    background-color:$material_energized900;
    background-image:none}
    
.tabs-background-positive-900 .tabs{
    background-color:$material_positive900;
    background-image:none}
    
.tabs-background-calm-900 .tabs{
    background-color:$material_calm900;
    background-image:none}
    
.tabs-background-royal-900 .tabs{
    background-color:$material_royal900;
    background-image:none}
    
.tabs-background-balanced-900 .tabs{
    background-color:$material_balanced900;
    background-image:none}
    
.tabs-background-assertive-900 .tabs{
    background-color:$material_assertive900;
    background-image:none}



.tabs-background-energized-100 .tabs{
    background-color:$material_energized100;
    background-image:none}
    
.tabs-background-positive-100 .tabs{
    background-color:$material_positive100;
    background-image:none}
    
.tabs-background-calm-100 .tabs{
    background-color:$material_calm100;
    background-image:none}
    
.tabs-background-royal-100 .tabs{
    background-color:$material_royal100;
    background-image:none}
    
.tabs-background-balanced-100 .tabs{
    background-color:$material_balanced100;
    background-image:none}
    
.tabs-background-assertive-100 .tabs{
    background-color:$material_assertive100;
    background-image:none}
        
    
.tabs-color-energized-900 .tab-item{
    color:$material_energized900}
    
.tabs-color-positive-900 .tab-item{
    color:$material_positive900}
    
.tabs-color-calm-900 .tab-item{
    color:$material_calm900}
    
.tabs-color-royal-900 .tab-item{
    color:$material_royal900}
    
.tabs-color-balanced-900 .tab-item{
    color:$material_balanced900}
    
.tabs-color-assertive-900 .tab-item{
    color:$material_assertive900}
    
.tabs-color-energized-100 .tab-item{
    color:$material_energized100}
    
.tabs-color-positive-100 .tab-item{
    color:$material_positive100}
    
.tabs-color-calm-100 .tab-item{
    color:$material_calm100}
    
.tabs-color-royal-100 .tab-item{
    color:$material_royal100}
    
.tabs-color-balanced-100 .tab-item{
    color:$material_balanced100}
    
.tabs-color-assertive-100 .tab-item{
    color:$material_assertive100}
    
    
.tabs-striped.tabs-color-energized-900 .tab-item.activated,.tabs-striped.tabs-color-energized-900 .tab-item.active,.tabs-striped.tabs-color-energized-900 .tab-item.tab-item-active {
    margin-top:-2px;
    color:$material_energized900;
    border:0 solid $material_energized900;
    border-top-width:2px
}

.tabs-striped.tabs-color-positive-900 .tab-item.activated,.tabs-striped.tabs-color-positive-900 .tab-item.active,.tabs-striped.tabs-color-positive-900 .tab-item.tab-item-active {
    margin-top:-2px;
    color:$material_positive900;
    border:0 solid $material_positive900;
    border-top-width:2px
}

.tabs-striped.tabs-color-calm-900 .tab-item.activated,.tabs-striped.tabs-color-calm-900 .tab-item.active,.tabs-striped.tabs-color-calm-900 .tab-item.tab-item-active {
    margin-top:-2px;
    color:$material_calm900;
    border:0 solid $material_calm900;
    border-top-width:2px
}

.tabs-striped.tabs-color-royal-900 .tab-item.activated,.tabs-striped.tabs-color-royal-900 .tab-item.active,.tabs-striped.tabs-color-royal-900 .tab-item.tab-item-active {
    margin-top:-2px;
    color:$material_royal900;
    border:0 solid $material_royal900;
    border-top-width:2px
}

.tabs-striped.tabs-color-balanced-900 .tab-item.activated,.tabs-striped.tabs-color-balanced-900 .tab-item.active,.tabs-striped.tabs-color-balanced-900 .tab-item.tab-item-active {
    margin-top:-2px;
    color:$material_balanced900;
    border:0 solid $material_balanced900;
    border-top-width:2px
}

.tabs-striped.tabs-color-assertive-900 .tab-item.activated,.tabs-striped.tabs-color-assertive-900 .tab-item.active,.tabs-striped.tabs-color-assertive-900 .tab-item.tab-item-active {
    margin-top:-2px;
    color:$material_assertive900;
    border:0 solid $material_assertive900;
    border-top-width:2px
}

.tabs-background-energized-900 .tabs {
    background-color:$material_energized900;
    background-image:none
}

.tabs-background-positive-900 .tabs {
    background-color:$material_positive900;
    background-image:none
}

.tabs-background-calm-900 .tabs {
    background-color:$material_calm900;
    background-image:none
}

.tabs-background-royal-900 .tabs {
    background-color:$material_royal900;
    background-image:none
}

.tabs-background-balanced-900 .tabs {
    background-color:$material_balanced900;
    background-image:none
}

.tabs-background-assertive-900 .tabs {
    background-color:$material_assertive900;
    background-image:none
}

.tabs-background-transparent .tabs {
    background:transparent;
    box-shadow:none;
    border:none;
}


.tabs-energized-900 >.tabs,.tabs-energized-900.tabs {
    background-color:$material_energized900;
    background-image:none;
    color:#fff
}

.tabs-positive-900 >.tabs,.tabs-positive-900.tabs {
    background-color:$material_positive900;
    background-image:none;
    color:#fff
}

.tabs-calm-900 >.tabs,.tabs-calm-900.tabs {
    background-color:$material_calm900;
    background-image:none;
    color:#fff
}

.tabs-royal-900 >.tabs,.tabs-royal-900.tabs {
    background-color:$material_royal900;
    background-image:none;
    color:#fff
}

.tabs-balanced-900 >.tabs,.tabs-balanced-900.tabs {
    background-color:$material_balanced900;
    background-image:none;
    color:#fff
}

.tabs-assertive-900 >.tabs,.tabs-assertive-900.tabs {
background-color:$material_assertive900;
background-image:none;
color:#fff
}

.list >a:nth-of-type(1n+1) .colorful,.list >div:nth-of-type(1n+1) .colorful {
color:$material_positive900
}

.list >a:nth-of-type(2n+2) .colorful,.list >div:nth-of-type(2n+2) .colorful {
color:$material_calm900
}

.list >a:nth-of-type(3n+3) .colorful,.list >div:nth-of-type(3n+3) .colorful {
color:$material_balanced900
}

.list >a:nth-of-type(4n+4) .colorful,.list >div:nth-of-type(4n+4) .colorful {
color:$material_energized900
}

.list >a:nth-of-type(5n+5) .colorful,.list >div:nth-of-type(5n+5) .colorful {
color:$material_assertive900
}

.list >a:nth-of-type(6n+6) .colorful,.list >div:nth-of-type(6n+6) .colorful {
color:$material_royal900
}

.list >div:nth-of-type(1n+1) .colorful-bg .item,.list >div:nth-of-type(1n+1) .item-colorful,.list >div:nth-of-type(1n+1) .button-colorful {
background-color:$material_positive900;
color:#fff
}

.list >div:nth-of-type(2n+2) .colorful-bg .item,.list >div:nth-of-type(2n+2) .item-colorful,.list >div:nth-of-type(2n+2) .button-colorful {
background-color:$material_calm900;
color:#fff
}

.list >div:nth-of-type(3n+3) .colorful-bg .item,.list >div:nth-of-type(3n+3) .item-colorful,.list >div:nth-of-type(3n+3) .button-colorful {
background-color:$material_balanced900;
color:#fff
}

.list >div:nth-of-type(4n+4) .colorful-bg .item,.list >div:nth-of-type(4n+4) .item-colorful,.list >div:nth-of-type(4n+4) .button-colorful {
background-color:$material_energized900;
color:#fff
}

.list >div:nth-of-type(5n+5) .colorful-bg .item,.list >div:nth-of-type(5n+5) .item-colorful,.list >div:nth-of-type(5n+5) .button-colorful {
background-color:$material_assertive900;
color:#fff
}

.list >div:nth-of-type(6n+6) .colorful-bg .item,.list >div:nth-of-type(6n+6) .item-colorful,.list >div:nth-of-type(6n+6) .button-colorful {
background-color:$material_royal900;
color:#fff
} 
  

.list >div:nth-of-type(1n+1) .colorful-bg .item *,.list >div:nth-of-type(1n+1) .item-colorful *,.list >div:nth-of-type(1n+1) .button-colorful *{
color:#fff
}

.list >div:nth-of-type(2n+2) .colorful-bg .item *,.list >div:nth-of-type(2n+2) .item-colorful *,.list >div:nth-of-type(2n+2) .button-colorful *{
color:#fff
}

.list >div:nth-of-type(3n+3) .colorful-bg .item *,.list >div:nth-of-type(3n+3) .item-colorful *,.list >div:nth-of-type(3n+3) .button-colorful *{
color:#fff
}

.list >div:nth-of-type(4n+4) .colorful-bg .item *,.list >div:nth-of-type(4n+4) .item-colorful *,.list >div:nth-of-type(4n+4) .button-colorful *{
color:#fff
}

.list >div:nth-of-type(5n+5) .colorful-bg .item *,.list >div:nth-of-type(5n+5) .item-colorful *,.list >div:nth-of-type(5n+5) .button-colorful *{
color:#fff
}

.list >div:nth-of-type(6n+6) .colorful-bg .item *,.list >div:nth-of-type(6n+6) .item-colorful *,.list >div:nth-of-type(6n+6) .button-colorful *{
color:#fff
}
  
.item-energized-900 {
color:#fff;
background-color:$material_energized900;
background-image:none
}

.item-positive-900 {
color:#fff;
background-color:$material_positive900;
background-image:none
}

.item-calm-900 {
color:#fff;
background-color:$material_calm900;
background-image:none
}

.item-royal-900 {
color:#fff;
background-color:$material_royal900;
background-image:none
}

.item-balanced-900 {
color:#fff;
background-color:$material_balanced900;
background-image:none
}

.item-assertive-900 {
color:#fff;
background-color:$material_assertive900;
background-image:none
}  


.item-energized-100 {
color:#fff;
background-color:$material_energized100;
background-image:none
}

.item-positive-100 {
color:#fff;
background-color:$material_positive100;
background-image:none
}

.item-calm-100 {
color:#fff;
background-color:$material_calm100;
background-image:none
}

.item-royal-100 {
color:#fff;
background-color:$material_royal100;
background-image:none
}

.item-balanced-100 {
color:#fff;
background-color:$material_balanced100;
background-image:none
}

.item-assertive-100 {
color:#fff;
background-color:$material_assertive100;
background-image:none
}  
  
";

    // TODO: --|-- SAVE TO FILE
    $data['themes'] = $_POST['themes'];
    file_put_contents('resources/lib/ionic-material/css/ionic.material.min.css', minify($css_material));
    file_put_contents('resources/lib/ionic-material/css/ionic.material.css', $css_material);
    file_put_contents('projects/' . $file_name . '/themes.json', json_encode($data));
    buildIonic($file_name);
    header('Location: ./?page=x-custom-themes&notice=save&err=null');

}


if (file_exists('projects/' . $file_name . '/themes.json'))
{
    $_raw_data = json_decode(file_get_contents('projects/' . $file_name . '/themes.json'), true);

    $material_positive = $_raw_data['themes']['positive'];
    $material_positive900 = $_raw_data['themes']['positive-900'];
    $material_positive100 = $_raw_data['themes']['positive-100'];

    $material_calm = $_raw_data['themes']['calm'];
    $material_calm900 = $_raw_data['themes']['calm-900'];
    $material_calm100 = $_raw_data['themes']['calm-100'];

    $material_royal = $_raw_data['themes']['royal'];
    $material_royal900 = $_raw_data['themes']['royal-900'];
    $material_royal100 = $_raw_data['themes']['royal-100'];

    $material_balanced = $_raw_data['themes']['balanced'];
    $material_balanced900 = $_raw_data['themes']['balanced-900'];
    $material_balanced100 = $_raw_data['themes']['balanced-100'];

    $material_energized = $_raw_data['themes']['energized'];
    $material_energized900 = $_raw_data['themes']['energized-900'];
    $material_energized100 = $_raw_data['themes']['energized-100'];

    $material_assertive = $_raw_data['themes']['assertive'];
    $material_assertive900 = $_raw_data['themes']['assertive-900'];
    $material_assertive100 = $_raw_data['themes']['assertive-100'];

}

// TODO: --|-- MARKUP
$content = null;
$content .= '<h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-puzzle-piece fa-stack-1x"></i></span>Extra Menus -&raquo; (IMAB) Custom Themes</h4>';
$content .= '<blockquote class="blockquote blockquote-info">'.__('This menu is used to modify the color material design').'</blockquote>';
$form_content .= '<div class="row">';
$form_content .= '<div class="col-md-4">';
$form_content .= '  
                    <label>Positive</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[positive]" type="text" value="' . htmlentities($material_positive) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Positive 900</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[positive-900]" type="text" value="' . htmlentities($material_positive900) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Positive 100</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[positive-100]" type="text" value="' . htmlentities($material_positive100) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';
$form_content .= '</div>';

$form_content .= '<div class="col-md-4">';
$form_content .= '  
                    <label>Calm</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[calm]" type="text" value="' . htmlentities($material_calm) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Calm 900</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[calm-900]" type="text" value="' . htmlentities($material_calm900) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Calm 100</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[calm-100]" type="text" value="' . htmlentities($material_calm100) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';
$form_content .= '</div>';


$form_content .= '<div class="col-md-4">';
$form_content .= '  
                    <label>Royal</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[royal]" type="text" value="' . htmlentities($material_royal) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Royal 900</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[royal-900]" type="text" value="' . htmlentities($material_royal900) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Royal 100</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[royal-100]" type="text" value="' . htmlentities($material_royal100) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';
$form_content .= '</div>';
$form_content .= '</div>';


$form_content .= '<div class="row">';

$form_content .= '<div class="col-md-4">';
$form_content .= '  
                    <label>Balanced</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[balanced]" type="text" value="' . htmlentities($material_balanced) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Balanced 900</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[balanced-900]" type="text" value="' . htmlentities($material_balanced900) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Balanced 100</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[balanced-100]" type="text" value="' . htmlentities($material_balanced100) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';
$form_content .= '</div>';


$form_content .= '<div class="col-md-4">';
$form_content .= '  
                    <label>Energized</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[energized]" type="text" value="' . htmlentities($material_energized) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Energized 900</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[energized-900]" type="text" value="' . htmlentities($material_energized900) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Energized 100</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[energized-100]" type="text" value="' . htmlentities($material_energized100) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';
$form_content .= '</div>';


$form_content .= '<div class="col-md-4">';
$form_content .= '  
                    <label>Assertive</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[assertive]" type="text" value="' . htmlentities($material_assertive) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Assertive 900</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[assertive-900]" type="text" value="' . htmlentities($material_assertive900) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';

$form_content .= '
                    <label>Assertive 100</label>
                    <div data-type="color-picker" class="input-group colorpicker-component"> 
                        <input name="themes[assertive-100]" type="text" value="' . htmlentities($material_assertive100) . '" class="form-control" /> 
                        <span class="input-group-addon"><i></i></span> 
                    </div>
                    <br/>
                ';
$form_content .= '</div>';
$form_content .= '</div>';


$form_content .= $bs->FormGroup(null, 'default', 'html', null, $bs->ButtonGroups(null, array(
    array(
        'name' => 'themes-save',
        'label' => __('Save Setting').' &raquo;',
        'tag' => 'submit',
        'color' => 'primary'),
    array(
        'label' => __('Reset'),
        'tag' => 'reset',
        'color' => 'default'),
    array(
        'label' => __('Delete for using default color'),
        'icon' => 'glyphicon glyphicon glyphicon-trash',
        'tag' => 'anchor',
        'color' => 'danger',
        'link' => "./?page=x-custom-themes&delete"))));
$content .= $bs->Forms('theme-setup', '', 'post', 'default', $form_content);


$footer .= '
<script type="text/javascript">
 $("div[data-type=\'color-picker\']").colorpicker();
</script>
';
$template->demo_url = $out_path . '/www/#/';
$template->title = $template->base_title . ' | ' . 'Extra Menus -&raquo; Custom Themes';
$template->base_desc = '';
$template->content = $content;
$template->footer = $footer;

?>