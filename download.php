<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */

error_reporting(0);

session_start();
if (!isset($_SESSION['JSM_DEMO']))
{
    $_SESSION['JSM_DEMO'] = true;
}
if ($_SESSION['JSM_DEMO'] == true)
{
    die();
}


set_time_limit(0);
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 0);
ini_set('upload_max_filesize', '5M');
ini_set('post_max_size', '8M');
ini_set('max_input_time', '60');
ini_set('safe_mode', 'off');
ini_set('max_input_vars', '1000');
//ini_set('session.use_cookies', '1');
function create_zip($files = array(), $destination = '', $prefix = '')
{
    echo 'Create new zip<br/>';
    $valid_files = array();
    if (is_array($files))
    {
        foreach ($files as $file)
        {
            if (file_exists($file))
            {
                $valid_files[] = $file;
            }
        }
    }
    echo 'Valid file: ' . count($valid_files) . '<br/>';
    if (count($valid_files))
    {
        $zip = new ZipArchive();
        if ($zip->open($destination, ZIPARCHIVE::CREATE))
        {
            foreach ($valid_files as $file)
            {
                $dir_zip = explode('/' . $prefix . '/', $file);
                $zip->addFile($file, $dir_zip[1]);
                echo 'Add File to Zip: ' . $file . '=>' . $dir_zip[1] . '<br/>';
            }
        }
        echo 'The zip archive contains ', $zip->numFiles, ' files with a status of: ', $zip->status;
        $zip->close();
        return file_exists($destination);
    } else
    {
        echo 'Try to create zip: Failed bacause count files.<br/>';
        return false;
    }
}
if (isset($_GET['prefix']))
{
    $prefix = basename($_GET['prefix']);

    if (isset($_GET['download']))
    {

        switch ($_GET['download'])
        {
            case 'project':
                $dir_target = 'projects/' . $prefix;
                $type = 'project';
                break;
            case 'data':
                $dir_target = 'output/' . $prefix . '/www/data';
                $type = 'data';
                break;
            case 'output':
                $dir_target = 'output/' . $prefix;
                $type = 'output';

                if (!isset($_GET['build']))
                {
                    $_GET['build'] = 'ionic';
                }

                if ($_GET['build'] == 'phonegap')
                {
                    if (!file_exists('output/' . $prefix . '/www/res/icon/android/mdpi-icon.png'))
                    {
                        die('<p>Error Icon and splashscreen! for create Icon and splashscreen go to Extra Menus -&raquo; (IMAB) Resources, or <a target="_blank" href="./?page=x-resources">click this</a></p>');
                        exit(0);
                    }
                    @copy($dir_target . '/config-phonegap.xml', $dir_target . '/config.xml');
                } else
                {
                    @copy($dir_target . '/config-ionic.xml', $dir_target . '/config.xml');
                }


                break;
        }
        if (is_dir($dir_target))
        {
            $path[] = $dir_target . '/*';
            while (count($path) != 0)
            {
                $v = array_shift($path);
                foreach (glob($v) as $item)
                {
                    if (is_dir($item))
                        $path[] = $item . '/*';
                    elseif (is_file($item))
                    {
                        $files_to_zip[] = $item;
                    }
                }
            }
            $result = create_zip($files_to_zip, 'projects/' . $type . '_' . $prefix . '.zip', $prefix);
            if ($_GET['download'] == 'output')
            {
                @copy($dir_target . '/config-ionic.xml', $dir_target . '/config.xml');
            }
            echo '<script type="text/javascript">window.location="./projects/' . $type . '_' . $prefix . '.zip";</script>';
        }
    }

    if (isset($_GET['clone']))
    {
        if (isset($_SESSION['PROJECT']['app']['prefix']))
        {


            $dir_src = 'projects/' . $prefix . '/';
            $dir_target = 'projects/' . $_SESSION['PROJECT']['app']['prefix'] . '/';

            echo 'source: ' . $dir_src . '<br/>';
            echo 'target: ' . $dir_target . '<br/>';
            foreach (glob($dir_src . "*.json") as $filename)
            {
                echo basename($filename) . '<br/>';
                if (basename($filename) != 'app.json')
                {
                    copy($filename, $dir_target . '/' . basename($filename));

                    $app_old = json_decode(file_get_contents($dir_src . '/app.json'), true);

                    $app_menus = json_decode(file_get_contents('projects/' . $_SESSION['PROJECT']['app']['prefix'] . '/menu.json'), true);
                    $app_menus['menu']['title'] = htmlentities($_SESSION['PROJECT']['app']['name']);
                    file_put_contents('projects/' . $_SESSION['PROJECT']['app']['prefix'] . '/menu.json', json_encode($app_menus));


                    foreach (glob("projects/" . $_SESSION['PROJECT']['app']['prefix'] . "/*.json") as $raw_json)
                    {
                        $raw_data = file_get_contents($raw_json);
                        $raw_data = str_replace('#\/' . $app_old['app']['prefix'], '#\/' . $_SESSION['PROJECT']['app']['prefix'], $raw_data);
                        file_put_contents($raw_json, $raw_data);
                        echo 'fix url: ' . $raw_json . '=>' . '#\/' . $app_old['app']['prefix'] . '<br/>';
                    }

                }

            }
            echo '<script type="text/javascript">window.location="./?page=dashboard&active=' . $_SESSION['PROJECT']['app']['prefix'] . '";</script>';
        } else
        {
            echo '<script type="text/javascript">window.location="./?page=dashboard&err=project";</script>';
        }
    }
}

?>