<?php

/** This file is part of KCFinder project
 *
 *      @desc Base configuration file
 *   @package KCFinder
 *   @version 3.12
 *    @author Pavel Tzonkov <sunhater@sunhater.com>
 * @copyright 2010-2014 KCFinder Project
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 *      @link http://kcfinder.sunhater.com
 */

/* IMPORTANT!!! Do not comment or remove uncommented settings in this file
even if you are using session configuration.
See http://kcfinder.sunhater.com/install for setting descriptions */

 
$_CONFIG = array(

    // GENERAL SETTINGS
    'disabled' => true,
    'uploadURL' => "../../../output/",
    'uploadDir' => "",
    'theme' => "default",

    'types' => array(
        'file' => "",
        'flash' => "",
        'images' => "*img",
        ),


    // IMAGE SETTINGS

    'imageDriversPriority' => "imagick gmagick gd",
    'jpegQuality' => 100,
    'thumbsDir' => "../../.thumbs",

    'maxImageWidth' => 2048,
    'maxImageHeight' => 2048,

    'thumbWidth' => 80,
    'thumbHeight' => 80,

    'watermark' => "",


    // DISABLE / ENABLE SETTINGS

    'denyZipDownload' => false,
    'denyUpdateCheck' => true,
    'denyExtensionRename' => true,


    // PERMISSION SETTINGS

    'dirPerms' => 0755,
    'filePerms' => 0644,

    'access' => array(
                        'files' => array(
                                'upload' => true,
                                'delete' => true,
                                'copy' => true,
                                'move' => true,
                                'rename' => true
                                ), 
                                
                        'dirs' => array(
                                'create' => false,
                                'delete' => false,
                                'rename' => false
                                )
                        ),

    'deniedExts' => "exe com msi bat cgi pl php phps phtml php3 php4 php5 php6 py pyc pyo pcgi pcgi3 pcgi4 pcgi5 pchi6",


    // MISC SETTINGS
    'filenameChangeChars' => array(' ' => "_", ':' => "."),
    'dirnameChangeChars' => array(' ' => "_", ':' => "."),
    'mime_magic' => "",

    'cookieDomain' => parse_url($_SERVER["HTTP_HOST"],PHP_URL_HOST),
    'cookiePath' => "",
    'cookiePrefix' => 'IHS_KCF_',


    // THE FOLLOWING SETTINGS CANNOT BE OVERRIDED WITH SESSION SETTINGS

    '_normalizeFilenames' => true,
    '_check4htaccess' => false,
    //'_tinyMCEPath' => "/tiny_mce",

    '_sessionVar' => "KCFINDER",
    );

?>