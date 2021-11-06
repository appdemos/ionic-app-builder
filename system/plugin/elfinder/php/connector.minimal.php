<?php

error_reporting(0);
session_start();


if (!isset($_SESSION['PROJECT']['app']))
{
    die("please activate IMA Project before.");
}

require './autoload.php';
elFinder::$netDrivers['ftp'] = 'FTP';
function access($attr, $path, $data, $volume, $isDir, $relpath)
{
    $basename = basename($path);
    return $basename[0] === '.'
        && strlen($relpath) !== 1
        ? !($attr == 'read' || $attr == 'write')
        : null;
}
if(!isset($_GET['type'])){
    $type_data = 'images';
}
if ($_GET['type'] == 'images')
{
    $type_data = 'images';
}

if ($_GET['type'] == 'file')
{
    $type_data = 'file';
}
$opts = array(
        'roots' => array(
            array(
            'driver' => 'LocalFileSystem',
            'path' => '../../../../output/' . $_SESSION['PROJECT']['app']['prefix'] . '/www/data/' . $type_data,
            'URL' => dirname($_SERVER['PHP_SELF']) . '/../../../../output/' . $_SESSION['PROJECT']['app']['prefix'] . '/www/data/' . $type_data,
            'trashHash' => 't1_Lw',
            'winHashFix' => DIRECTORY_SEPARATOR !== '/',
            'uploadDeny' => array('all'),
            'uploadAllow' => array('image', 'text/plain'),
            'uploadOrder' => array('deny', 'allow'),
            'accessControl' => 'access'
                ),
            array(
            'id' => '1',
            'driver' => 'Trash',
            'path' => '../../../../output/' . $_SESSION['PROJECT']['app']['prefix'] . '/.trash/',
            'tmbURL' => dirname($_SERVER['PHP_SELF']) . '/../../../../output/' . $_SESSION['PROJECT']['app']['prefix'] . '/.trash/.tmb/',
            'winHashFix' => DIRECTORY_SEPARATOR !== '/',
            'uploadDeny' => array('all'),
            'uploadAllow' => array('image', 'text/plain'),
            'uploadOrder' => array('deny', 'allow'),
            'accessControl' => 'access',
            )));
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();