<?php

/**
 * @author Jasman
 * @copyright Ihsana IT Solution 2017
 * 
 * Shoutcast v2.x
 */


$url = 'http://192.168.0.2:8100';
$rest_api = array();
$opts = array('http' => array('method' => "GET", 'header' => "Accept-language: en\r\n" . "Referer: http://ihsana.com/helpdesk/\r\n" . "Content-type: application/x-www-form-urlencoded\r\n" . "User-agent: Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0\r\n" . "Origin: null" . "\r\n"));
$context = stream_context_create($opts);


if (!isset($_GET['json']))
{
    $_GET['json'] = 'route';
}

switch ($_GET['json'])
{
    case 'route':
        $rest_api["routes"]['/route'] = array(
            "namespace" => "",
            "methods" => "GET",
            "link" => 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?json=route");
        $rest_api["routes"]['/status'] = array(
            "namespace" => "/status",
            "methods" => "GET",
            "link" => 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?json=status");
        $rest_api["routes"]['/history'] = array(
            "namespace" => "/history",
            "methods" => "GET",
            "link" => 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?json=history");
        break;
    case 'status':
        $content = @file_get_contents($url . '/index.html?sid=2', false, $context);
        $content = explode('<table cellpadding="2" cellspacing="0" border="0" align="center">', $content);
        $content = explode('</table>', $content[1]);
        $content = '{' . $content[0] . '}';
        $content = str_replace('<td width="120" valign="top">', '<td>', $content);
        $content = str_replace('<td valign="top">', '<td>', $content);
        $content = str_replace('<tr><td>', '"', $content);
        $content = str_replace('</td></tr>', '",', $content);
        $content = str_replace(': </td><td>', '":"', $content);
        $content = strip_tags($content);
        $content = str_replace(',}', '}', $content);
        $content = str_replace('Server Status', 'ServerStatus', $content);
        $content = str_replace('Stream Status', 'StreamStatus', $content);
        $content = str_replace('Stream Name', 'StreamName', $content);
        $content = str_replace('Content Type', 'ContentType', $content);
        $content = str_replace('Stream Genre(s)', 'StreamGenres', $content);
        $content = str_replace('Current Song', 'CurrentSong', $content);
        $rest_api = json_decode($content, true);
        break;
    case 'history':
        $content = @file_get_contents($url . '/played.html?sid=2', false, $context);
        $content = explode('<tr><td><b>Played @</b></td><td><b>Song Title</b></td></tr>', $content);
        $content = explode('</table>', $content[1]);
        $content = "[" . str_replace('</tr>', '"},', $content[0]) . ']';
        $content = str_replace('<td style="padding: 0 10px;"><b>Current Song</b></td>', ' (Current Song)', $content);
        $content = str_replace('<tr><td>', '{"time":"', $content);
        $content = str_replace('</td><td>', '","title":"', $content);
        $content = str_replace('},]', '}]', $content);
        $rest_api = json_decode($content, true);
        break;
}

header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode($rest_api, JSON_UNESCAPED_UNICODE);

?>