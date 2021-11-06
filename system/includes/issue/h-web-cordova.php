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
$bs = new jsmBootstrap();
$form_input = $out_path = $html = $content = null;


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
$cordova_project = 'cordova/' . $file_name;
if (!file_exists($cordova_project))
{
    mkdir($cordova_project, 0777, true);
}
if (file_exists('projects/' . $file_name . '/app.json'))
{
    $raw_menu = json_decode(file_get_contents('projects/' . $file_name . '/app.json'), true);
}
$path['node_js'] = 'C:\guiCordova\nodejs';
$path['node_module'] = 'C:\guiCordova\nodejs\node_modules';
$path['jdk'] = 'C:\Program Files\Java\jdk1.8.0_65';
$path['android_sdk'] = 'C:\Users\IHSANA\AppData\Local\Android\sdk';
$path['android_tools'] = 'C:\Users\IHSANA\AppData\Local\Android\sdk';
$cmd = null;
$cmd .= 'cordova telemetry off' . "\r\n";
$cmd .= 'cordova create ' . $raw_menu['app']['prefix'] . ' "' . JSM_PACKAGE_NAME . '.' . str_replace('_', '', str2var($raw_menu['app']['company'])) . '.' . str_replace('_', '', str2var($raw_menu['app']['prefix'])) . '" "' . $raw_menu['app']['name'] . '"' . "\r\n";
$cmd .= 'cd ' . $raw_menu['app']['prefix'] . "\r\n";
$cmd .= 'cordova plugin add cordova-plugin-device --save' . "\r\n";
$cmd .= 'cordova plugin add cordova-plugin-console --save' . "\r\n";
$cmd .= 'cordova plugin add cordova-plugin-splashscreen --save' . "\r\n";
$cmd .= 'cordova plugin add cordova-plugin-statusbar --save' . "\r\n";
$cmd .= 'cordova plugin add cordova-plugin-whitelist --save' . "\r\n";
$cmd .= 'cordova plugin add ionic-plugin-keyboard --save' . "\r\n";
if (isset($_SESSION['PROJECT']['mod']))
{
    foreach ($_SESSION['PROJECT']['mod'] as $mod)
    {
        if ($mod['engines'] == 'cordova')
        {
            $cmd .= 'cordova plugin add ' . $mod['name'] . ' --save' . "\r\n";
        }
    }
}
if ($raw_menu['app']['soundtouch'] == true)
{
    $cmd .= 'cordova plugin add cordova-plugin-velda-devicefeedback --save' . "\r\n";
}

$cmd .= 'cordova platform add android' . "\r\n";
$cmd .= 'xcopy /Y /S "' . JSM_PATH . DIRECTORY_SEPARATOR . 'output' . DIRECTORY_SEPARATOR . $raw_menu['app']['prefix'] . DIRECTORY_SEPARATOR . '*"' . "\r\n";
$cmd .= 'cordova build android' . "\r\n";

$path_cmd = $cordova_project . '\start.cmd';
$new_cmd = window_command_scripts($path, $cmd);
file_put_contents($path_cmd, $new_cmd);

$content = '<pre style="font-family:FIXEDSYS;">' . shell_exec(realpath($path_cmd)) . '</pre>';


function window_command_scripts($path, $new_cmd = '')
{
    $path_node_js = $path['node_js'];
    $path_node_module = $path['node_module'];
    $path_jdk = $path['jdk'];
    $path_android_sdk = $path['android_sdk'];
    $path_android_tools = $path['android_tools'];
    $cmd = '
@ECHO OFF
color 0a
GOTO shell
:setenv

SET "PATH=%~dp0;%systemroot%;%systemroot%\System32;' . $path_node_js . ';' . $path_node_module . '\.bin;' . $path_jdk . '\bin;' . $path_android_tools . '"
SET "JAVA_HOME=' . $path_jdk . '\"
SET "ANDROID_HOME=' . $path_android_sdk . '\"

ECHO JAVA_HOME = %JAVA_HOME%
ECHO ANDROID_HOME = %ANDROID_HOME%
GOTO :EOF
:shell

IF "%1" EQU "setenv" (
	ECHO.
	ECHO                            Environment for using Cordova
	ECHO   ________  ___  ___   ______       _ _     _     ______     
	ECHO  ^|_   _^|  \/  ^| / _ \  ^| ___ \     ^(_^) ^|   ^| ^|    ^| ___ \    
	ECHO    ^| ^| ^| .  . ^|/ /_\ \ ^| ^|_/ /_   _ _^| ^| __^| ^| ___^| ^|_/ /____
	ECHO    ^| ^| ^| ^|\/^| ^|^|  _  ^| ^| ___ \ ^| ^| ^| ^| ^|/ _` ^|/ _ \    /^|_  /
	ECHO   _^| ^|_^| ^|  ^| ^|^| ^| ^| ^| ^| ^|_/ / ^|_^| ^| ^| ^| (_^| ^|  __/ ^|\ \ / / 
	ECHO   \___/\_^|  ^|_/\_^| ^|_/ \____/ \__,_^|_^|_^|\__,_^|\___\_^| \_/___^|
	ECHO   Easy Creating Your Own Apps Without Coding
	ECHO   http://goo.gl/D1giIr
	ECHO.
	CALL :setenv
' . $new_cmd . '
) ELSE (
	SETLOCAL
	TITLE Environment for using Cordova
	CD /D "%~dp0"
	PROMPT %username%@%computername%$S$P$_#$S
	START "" /B %COMSPEC% /K "%~f0" setenv
)

';
    return $cmd;
}


$template->demo_url = $out_path . '/www/';
$template->title = $template->base_title . ' | ' . 'Run Cordova Via Web';
$template->base_desc = 'Web Cordova';
$template->content = $content;
$template->footer = '';
$template->emulator = false;

?>