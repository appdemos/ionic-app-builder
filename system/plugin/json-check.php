<?php

/**
 * @author Jasman
 * @copyright 2016
 */

if(!isset($_GET['url']))
{
    die('Please enter valid URL');
}

if(!isset($_GET['db_var']))
{
    $_GET['db_var'] = '';
}


$url = urldecode($_GET['url']);

$last_id = strlen($url) - 1;
if(trim($url[$last_id]) == '=')
{
    $url = $url.'1';
}


$opts = array('http' => array('method' => "GET",'header' => "Accept-language: ".@$_SERVER["HTTP_ACCEPT_LANGUAGE"]."\r\n"."Referer: http://ihsana.com/helpdesk/\r\n"."Content-type: application/x-www-form-urlencoded\r\n"."User-agent: Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0\r\n"."Origin: ".$_SERVER["HTTP_HOST"]."\r\n"));


$context = stream_context_create($opts);
$file = @file_get_contents($url,false,$context);
if(!isset($http_response_header))
{
    $http_response_header = array();
}
echo '<html>';
echo '<head>';
echo '<title>JSON Data</title>';
echo '<link href="../../templates/default/css/bootstrap.css" rel="stylesheet"/>';
echo '</head>';
echo '<body>';
//echo '<div class="modal-content">';
echo '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="myModalLabel">Analyzing JSON Data</h4></div>';
echo '<div class="modal-body">';
echo '<h4>Header</h4>';
echo '<code>'.$url.'</code><br/>';
echo '<pre>';
$crossdomain = "Access-Control-Allow-Origin: * <span style='color:red;font-weight:600;'><-- Add this in your HTTP Header (Reference: http://enable-cors.org/server.html)</span>";
foreach($http_response_header as $header)
{
    if(preg_match("/404/i",$header))
    {
        $header = "".$header." <span style='color:red;font-weight:600;'><-- Link error, please repaired</span>";
    }
    if(preg_match("/sameorigin/i",$header))
    {
        $header = "".$header." <span style='color:red;font-weight:600;'><-- Delete this in your HTTP Header</span>";
    }
    if(preg_match("/Allow\-Origin/i",$header))
    {
        $crossdomain = "";
    }
    echo $header."\r\n";
}
echo $crossdomain;
echo '</pre>';
echo '<h4>JSON Format</h4>';
$json = json_decode($file,true);
echo '<pre>';
if($json == null)
{
    echo "<span style='color:red;font-weight: 600;'>ERR!</span> Failed to parse json."."\r\n";
}
if(is_array($json))
{
    echo "<span style='color:green;font-weight: 600;'>OK!</span> Successful to converting json to mixed data."."\r\n";

    if($_GET['type'] == 'list')
    {
        if(strlen($_GET['db_var']) > 0)
        {
            $vars = explode('.',substr($_GET['db_var'],1,strlen($_GET['db_var'])));
            $code_var = 'if(isset($test_db_var';
            foreach($vars as $var)
            {
                $code_var .= "['".$var."']";
            }
            $code_var .= ")){";
            $code_var .= "echo '<span style=\'color:green;font-weight: 600;\'>OK!</span> Structure JSON Data.';";
            $code_var .= "}else{";
            $code_var .= "echo '<span style=\'color:red;font-weight: 600;\'>ERR!</span> Structure JSON Data is incorrect <-- <span style=\'color:red;\'>Error on <strong>1st Variable</strong>, please fill with blank value</span>';";
            $code_var .= "}";

            eval($code_var);
        } else
        {
            echo '<span style=\'color:green;font-weight: 600;\'>OK!</span> Structure JSON Data';
        }
    }
    if($_GET['type'] == 'single')
    {
        if(strlen($_GET['db_var']) > 0)
        {
            if(isset($json[$_GET['db_var']]))
            {
                echo '<span style=\'color:green;font-weight: 600;\'>OK!</span> Structure JSON Data';
            } else
            {
                echo '<span style=\'color:red;font-weight: 600;\'>ERR!</span> Structure JSON Data is incorrect <-- <span style=\'color:red;\'>Error on <strong>URL Single Item</strong>, please fill with blank value</span>';
            }

        } else
        {
            echo '<span style=\'color:red;font-weight: 600;\'>ERR!</span> Structure JSON Data is incorrect <-- <span style=\'color:red;\'>Error on <strong>URL Single Item</strong>, please fill with blank value</span>';
        }
    }
}
echo '</pre>';
echo '</div>';
echo '</body>';
echo '</html>';

?>