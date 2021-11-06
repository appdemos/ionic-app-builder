<?php

/**
 * @author Jasman
 * @copyright 2016
 */

error_reporting(0);

foreach (glob("*.json") as $filename) {
  $content =  json_encode(json_decode(file_get_contents($filename)),JSON_PRETTY_PRINT);
  file_put_contents($filename,$content);
}

?>