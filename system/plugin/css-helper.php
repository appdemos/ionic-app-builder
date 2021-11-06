<?php

/**
 * @author Jasman
 * @copyright 2016
 */

session_start();

$content = null;
header("Content-type: text/css");
if (isset($_SESSION['PROJECT']['fonts']))
{
    $file_name = $_SESSION['PROJECT']['app']['prefix'];
    if (is_array($_SESSION['PROJECT']['fonts']))
    {
        foreach ($_SESSION['PROJECT']['fonts'] as $font)
        {
            if (isset($font['used']))
            {
                $content .= '@font-face{
                font-family:"' . $font['font-family'] . '"; 
                src:local("' . $font['font-family'] . '"),
                url("../../' . str_replace('../', 'output/' . $file_name . '/www/fonts/', $font['font-url-ttf']) . '") format("truetype"),
                url("../../' . str_replace('../', 'output/' . $file_name . '/www/fonts/', $font['font-url-woff']) . '") format("woff")
                }' . "\r\n";

            }
        }
    }
}

echo $content;

?>