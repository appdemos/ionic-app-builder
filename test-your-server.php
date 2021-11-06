<?php

/**
 * @author Jasman
 * @copyright 2017
 */

error_reporting(0);

header('Content-type: text/plain');

@mkdir('output/test/folder/', 0777, true);
if (file_exists('output/test/folder/'))
{
    if (is_dir('output/test/folder/'))
    {
        echo ':: Testing `mkdir` permission : ok' . "\r\n";
    } else
    {
        echo ':: Testing `mkdir` permission : failed' . "\r\n";
    }

} else
{
    echo ':: Testing `mkdir` permission : failed' . "\r\n";
}


@file_put_contents('output/test/folder/test.file', 'test');
if (file_exists('output/test/folder/test.file'))
{
    echo ':: Testing `fwrite` permission : ok' . "\r\n";
} else
{
    echo ':: Testing `fwrite` permission : failed' . "\r\n";
}

$im = imagecreatetruecolor(20, 20);
$text_color = imagecolorallocate($im, 233, 14, 91);
@imagestring($im, 1, 5, 5, 'OK', $text_color);
@imagepng($im, 'output/test/folder/test.jpg');
@imagedestroy($im);
if (file_exists('output/test/folder/test.jpg'))
{
    if (is_file('output/test/folder/test.jpg'))
    {
        echo ':: Testing `PHP GD` extension : ok' . "\r\n";
    } else
    {
        echo ':: Testing `PHP GD` extension : failed' . "\r\n";
    }
} else
{
    echo ':: Testing `PHP GD` extension : failed' . "\r\n";
}


$file_zip = 'UEsDBBQAAAAAAJ14D0tGlR1CAwAAAAMAAAAMAAAAemlwL3Rlc3QudHh0emlwUEsBAhQAFAAAAAAAnXgPS0aVHUIDAAAAAwAAAAwAAAAAAAAAAQAgAAAAAAAAAHppcC90ZXN0LnR4dFBLBQYAAAAAAQABADoAAAAtAAAAAAA';
@file_put_contents('output/test/folder/test.zip', base64_decode($file_zip));

$zip = new ZipArchive;
if ($zip->open('output/test/folder/test.zip') === true)
{
    $zip->extractTo('output/test/');
    $zip->close();
    echo ':: Testing `ZipArchive` Class : ok' . "\r\n";
} else
{
    echo ':: Testing `ZipArchive` Class : failed' . "\r\n";
}
if (file_exists('output/test/zip/test.txt'))
{
    if (is_file('output/test/zip/test.txt'))
    {
        echo ':: Testing `ZipArchive` file : ok' . "\r\n";
    } else
    {
        echo ':: Testing `ZipArchive` file : failed' . "\r\n";
    }
} else
{
    echo ':: Testing `ZipArchive` file : failed' . "\r\n";
}

@unlink('output/test/zip/test.txt');
@unlink('output/test/folder/test.zip');
@unlink('output/test/folder/test.jpg');
@unlink('output/test/folder/test.file');
@rmdir('output/test/zip');
@rmdir('output/test/folder');
@rmdir('output/test');

?>