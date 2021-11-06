<?php

error_reporting(0);
session_start();
$core = '18.07';
$check_version = '18.07';
unset($_SESSION['PROJECT']);
unset($_SESSION['FILE_NAME']);
unset($_SESSION['FILE_LOAD']);
set_time_limit(0);
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 0);
ini_set('upload_max_filesize', '5M');
ini_set('post_max_size', '8M');
ini_set('max_input_time', '60');
ini_set('safe_mode', 'off');
ini_set('max_input_vars', '1000');
ini_set('session.use_cookies', '1');
//ob_start();
// send phpinfo content
//phpinfo();
// get phpinfo content
//$php_info = ob_get_contents();
// flush the output buffer
//ob_end_clean();
function HumanSize($Bytes)
{
    $Type = array(
        "",
        "kilo",
        "mega",
        "giga",
        "tera",
        "peta",
        "exa",
        "zetta",
        "yotta");
    $Index = 0;
    while ($Bytes >= 1024)
    {
        $Bytes /= 1024;
        $Index++;
    }
    return ("" . number_format($Bytes, 2, '.', 3) . " " . $Type[$Index] . "bytes");
}
define('JSM_EXEC', true);
define('JSM_PATH', dirname(__file__));
$_SESSION['SESSION'] = 'OK';
if (file_exists("./system/class/jsmIonic.php"))
{
    $update = file_get_contents("./system/class/jsmIonic.php");
    $code = explode("/** JSM_ACTIVATION_CODE ", $update);
    $check_version = strlen($code[0]);
}
if (file_exists(JSM_PATH . "/projects/config.php"))
{
    require_once (JSM_PATH . "/projects/config.php");
}
if (!defined('JSM_ENVATO_USERNAME'))
{
    define('JSM_ENVATO_USERNAME', '');
}
if (!defined('JSM_EMAIL'))
{
    define('JSM_EMAIL', '');
}
if (!defined('JSM_PURCHASE_CODE'))
{
    define('JSM_PURCHASE_CODE', '');
}
if (!defined('JSM_PRODUCT_URL'))
{
    define('JSM_PRODUCT_URL', '');
}
if (!defined('JSM_OTHER_MARKET'))
{
    define('JSM_OTHER_MARKET', false);
}
if (isset($_GET['server']))
{
    $_SESSION["SERVER"] = $_GET['server'];
    header("Location: setup.php");
}
if (!isset($_SESSION["SERVER"]))
{
    $_SESSION["SERVER"] = 1;
}
switch ($_SESSION['SERVER'])
{
    case '1':
        $activate_url = "http://ihsana.net/pub/activation/new-" . $core . ".php?installation_code=" . $check_version . "&purchase_code=" . JSM_PURCHASE_CODE . "&email=" . JSM_EMAIL;
        break;
    case '2':
        $activate_url = "http://ihsana.com/pub/activation/new-" . $core . ".php?installation_code=" . $check_version . "&purchase_code=" . JSM_PURCHASE_CODE . "&email=" . JSM_EMAIL;
        break;
    case '3':
        $activate_url = "http://ihsana.net/pub/activation/renew-" . $core . ".php?installation_code=" . $check_version . "&purchase_code=" . JSM_PURCHASE_CODE . "&email=" . JSM_EMAIL;
        break;
}
if (isset($_POST['submit']))
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $purchase_code = htmlentities(trim($_POST['purchase_code']));
    $url_product = htmlentities($_POST['url_product']);
    $manual_activation_code = htmlentities($_POST['manual_activation_code']);
    $content = '<?php' . "\r\n";
    $content .= "\r\n";
    if (isset($_POST['other_market']))
    {
        $content .= 'define("JSM_OTHER_MARKET","true");' . "\r\n";
    } else
    {
        $content .= 'define("JSM_OTHER_MARKET","false");' . "\r\n";
    }
    $content .= 'define("JSM_ENVATO_USERNAME","' . htmlentities($username) . '");' . "\r\n";
    $content .= 'define("JSM_EMAIL","' . htmlentities($email) . '");' . "\r\n";
    $content .= 'define("JSM_PURCHASE_CODE","' . htmlentities($purchase_code) . '");' . "\r\n";
    $content .= 'define("JSM_PRODUCT_URL","' . htmlentities($url_product) . '");' . "\r\n";
    $content .= "\r\n";
    file_put_contents("./projects/config.php", $content);
    if (!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
    {
        $_SERVER["HTTP_ACCEPT_LANGUAGE"] = '';
    }
    if (!isset($_SERVER["HTTP_HOST"]))
    {
        $_SERVER["HTTP_HOST"] = '';
    }
    if (!isset($_SERVER["REQUEST_URI"]))
    {
        $_SERVER["REQUEST_URI"] = '';
    }
    if ($manual_activation_code == "")
    {
        $opts = array('http' => array('method' => "GET", 'header' => "Accept-language: " . @$_SERVER["HTTP_ACCEPT_LANGUAGE"] . "\r\n" . "Referer: http://ihsana.com/pub/\r\n" . "Content-type: application/x-www-form-urlencoded\r\n" . "User-agent: Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0\r\n" . "Php-Os: " . @$_SERVER["SERVER_SOFTWARE"] . "\r\n" . "Php-Url: http://" . @$_SERVER["HTTP_HOST"] . '/' . @$_SERVER["REQUEST_URI"] . "\r\n" . "Connection: close" . "\r\n"));
        $context = stream_context_create($opts);
        $activation_code = file_get_contents($activate_url, false, $context);
    } else
    {
        $activation_code = $manual_activation_code;
    }
    $update = file_get_contents("./system/class/jsmIonic.php");
    $code = explode("/** JSM_ACTIVATION_CODE ", $update);
    $new_update = $code[0];
    if (strlen($activation_code) > 5)
    {
        $new_update .= "/** JSM_ACTIVATION_CODE \r\n" . htmlentities(base64_decode($activation_code)) . "\r\n**/";
        file_put_contents("./system/class/jsmIonic.php", $new_update);
    }
    if (file_exists("./projects/config.php"))
    {
        header("Location: ./");
    } else
    {
        die("Permission danied!");
    }
}
if (JSM_PURCHASE_CODE != '')
{
    $code_activation = '<p>If empty will try to use the automatic activation, if filled please click for get <a target="_blank" class="btn btn-sm btn-danger" href="' . $activate_url . '">Activation Code</a></p>';
} else
{
    $code_activation = '';
}

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>IMA Setup v<?php

echo $core

?></title>
    <link href="./templates/default/css/bootstrap.css" rel="stylesheet"/>
    <link href="./templates/default/css/jsm.css" rel="stylesheet"/>
    <style type="text/css">
    .blog-title{color: #666;padding-top: 12px;padding-bottom: 12px;}
     body{background: #F7F7F0;background-image: url('../img/bg.png') !important;}
    </style>
  </head>
  <body>
    <div class="container-fluid">
    <h2 class="blog-title">IMAB Setup v<?php

echo $core

?></h2>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#requirements" data-toggle="tab">(IMAB) System Requirements</a></li>
        <li><a href="#activation" data-toggle="tab">(IMAB) Activation</a></li>
        <li><a href="./test-your-server.php" target="_blank" >Test Your Server</a></li>
    </ul>
    <br />
      <form action="" method="post">
      <div class="tab-content">
      <!-- TODO: requirements -->
      <div id="requirements" class="tab-pane active">
       <div class="panel panel-default">   
       <div class="panel-body"> 
    <blockquote class="blockquote blockquote-danger">
    <h4>For secury reason!</h4>
    <p> For public domain after the installation is complete, please delete this file (<kbd>setup.php</kbd> and <kbd>test-your-server.php</kbd>) and localhost no need delete it</p>
    </blockquote>
        <blockquote class="blockquote blockquote-info">
            <h4>Recommended</h4>
            <p>Please check the configuration of your web hosting? It will work normally when using XAMPP, WAMP, MAMP and default cPanel. 
            If you think your hosting enough to run well this tool, please click 2# Product Activation.</p>
        </blockquote>
<?php

$dir = '/';
$free = disk_free_space($dir);
$total = disk_total_space($dir);
$free_to_mbs = HumanSize($free);
$total_to_mbs = HumanSize($total);
$memory_limit = ini_get('memory_limit');
$upload_max_size = ini_get('upload_max_filesize');
$post_max_size = ini_get('post_max_size');
$max_execution_time = ini_get('max_execution_time');
$max_input_time = ini_get('max_input_time');
$session_use_cookies = ini_get('session.use_cookies');
$max_input_vars = ini_get('max_input_vars');
$max_input_nesting_level = ini_get('max_input_nesting_level');
echo '<pre>';
echo php_uname('a');
echo '</pre>';
echo '<table class="table table-striped">';
$php_version = '';
if (version_compare(phpversion(), '5.5.3', '<'))
{
    $php_version = '<span class="label label-danger">Required: PHP v5.5.4 or latest</span>';
} else
{
    $php_version = '<span class="label label-success">OK</span>';
}
echo '<tr><td style="width:500px;"><code>PHP VERSION</code></td><td style="width:400px;">' . PHP_VERSION . '</td><td>' . $php_version . '</td></tr>';
if ($_SERVER["HTTP_HOST"] == 'localhost')
{
    echo '<tr><td><code>Web Server</code></td><td>localhost</td><td><span class="label label-success">OK</span></td></tr>';
} elseif ($_SERVER["HTTP_HOST"] == '127.0.0.1')
{
    echo '<tr><td><code>Web Server</code></td><td>localhost</td><td><span class="label label-success">OK</span></td></tr>';
} else
{
    echo '<tr><td><code>Web Server</code></td><td>' . $_SERVER["HTTP_HOST"] .
        ' (public)</td><td><blockquote class="blockquote blockquote-danger"><h4>Warning!!!</h4><p>Not recommend in public domain, please install in <ins>localhost</ins>. <br/>We expect not installed on a web server that is used for another web, <br/>this tool is used to create the code, so it obviously could be exploited by <strong>black-hacker</strong> <br/>and also <strong>nodejs, ionic, cordova</strong> can not run under hosting/share-hosting. <br/>and you must use different domain to test <strong>cross-domain</strong>.</p><p>We are not responsible if you install the public domain, <strong>use it with your risk</strong>.</p><p><a class="btn btn-warning" target="_blank" href="https://www.apachefriends.org/download.html">Download XAMPP</a> or <a class="btn btn-warning" target="_blank" href="https://www.mamp.info/en/">MAMP</a> for localhost and better security.<br/>or Ignore this message If you are understand about risk and will protect your hosting with htpasswd/htaccess/leech protection. If you are in doubt, send us a message with username and purchase code (info@ihsana.com).</p></blockquote></td></tr>';
}
echo '<tr><td><code>Disk Free Space</code></td><td>' . $free_to_mbs . '</td><td><span class="label label-warning">For using share-hosting/non-unlimited hosting check manually, this required minimum free space 100 megabytes for 3 projects.</span><br/><span class="label label-warning">This tools required 20 megabytes every project.  Are you using localhost or free space more than 100mb? please ignore</span></td></tr>';
if ($max_execution_time != 0)
{
    $max_execution_time_label = '<span class="label label-warning">Recommended SET=0</span>';
} else
{
    $max_execution_time_label = '<span class="label label-success">OK</span>';
}
echo '<tr><td><code>php.ini => max_execution_time</code></td><td>' . $max_execution_time . '</td><td>' . $max_execution_time_label . '</td></tr>';
$output_buffering = ini_get('output_buffering');
if (strtolower($output_buffering) == '0')
{
    $output_buffering_label = '<span class="label label-warning">Recommended SET=4096</span>';
}

if (strtolower($output_buffering) == '')
{
    $output_buffering_label = '<span class="label label-warning">Recommended SET=4096</span>';
}
if (strtolower($output_buffering) == '4096')
{
    $output_buffering_label = '<span class="label label-success">OK</span>';
}
if (strtolower($output_buffering) == 'on')
{
    $output_buffering_label = '<span class="label label-success">OK</span>';
}
echo '<tr><td><code>php.ini => output_buffering</code></td><td>' . $output_buffering . '</td><td>' . $output_buffering_label . '</td></tr>';
if ((int)(str_replace(array(
    'M',
    'm',
    ' '), '', $memory_limit)) < 512)
{
    $memory_limit_label = '<span class="label label-warning">Recommended SET=512M</span>';
} else
{
    $memory_limit_label = '<span class="label label-success">OK</span>';
}
echo '<tr><td><code>php.ini => memory_limit</code></td><td>' . $memory_limit . '</td><td>' . $memory_limit_label . '</td></tr>';
if ((int)(str_replace(array(
    'M',
    'm',
    ' '), '', $upload_max_size)) < 5)
{
    $upload_max_size_label = '<span class="label label-warning">Recommended SET=5M</span>';
} else
{
    $upload_max_size_label = '<span class="label label-success">OK</span>';
}
echo '<tr><td><code>php.ini => upload_max_filesize</code></td><td>' . $upload_max_size . '</td><td>' . $upload_max_size_label . '</td></tr>';
if ((int)(str_replace(array(
    'M',
    'm',
    ' '), '', $post_max_size)) < 8)
{
    $post_max_size_label = '<span class="label label-warning">Recommended SET=8M</span>';
} else
{
    $post_max_size_label = '<span class="label label-success">OK</span>';
}
echo '<tr><td><code>php.ini => post_max_size</code></td><td>' . $post_max_size . '</td><td>' . $post_max_size_label . '</td></tr>';
if ($max_input_time < 60)
{
    $max_input_time_label = '<span class="label label-warning">SET=60</span>';
} else
{
    $max_input_time_label = '<span class="label label-success">OK</span>';
}
echo '<tr><td><code>php.ini => max_input_time</code></td><td>' . $max_input_time . '</td><td>' . $max_input_time_label . '</td></tr>';
if ($max_input_vars < 1000)
{
    $max_input_vars_label = '<span class="label label-warning">SET=1000</span>';
} else
{
    $max_input_vars_label = '<span class="label label-success">OK</span>';
}
echo '<tr><td><code>php.ini => max_input_vars</code></td><td>' . $max_input_vars . '</td><td>' . $max_input_vars_label . '</td></tr>';
if ($max_input_nesting_level < 64)
{
    $max_input_nesting_level_label = '<span class="label label-warning">SET=64</span>';
} else
{
    $max_input_nesting_level_label = '<span class="label label-success">OK</span>';
}
echo '<tr><td><code>php.ini => max_input_nesting_level</code></td><td>' . $max_input_nesting_level . '</td><td>' . $max_input_nesting_level_label . '</td></tr>';
if ($session_use_cookies == '1')
{
    $session_use_cookies_label = '<span class="label label-success">OK</span>';
} else
{
    $session_use_cookies_label = '<span class="label label-warning">Recommended SET=1</span>';
}
echo '<tr><td><code>php.ini => session.use_cookies</code></td><td>' . $session_use_cookies . '</td><td>' . $session_use_cookies_label . '</td></tr>';
if ($_SESSION['SESSION'] == "OK")
{
    echo '<tr><td><code>SESSION</code></td><td>Available</td><td><span class="label label-success">OK</span></td></tr>';
} else
{
    echo '<tr><td><code>SESSION</code></td><td>Available</td><td><span class="label label-danger">FAILED</span></td></tr>';
}
if (class_exists('ZipArchive'))
{
    echo '<tr><td><code>zlib extension</code></td><td>Available</td><td><span class="label label-success">OK</span></td></tr>';
} else
{
    echo '<tr><td><code>zlib extension</code></td><td>Available</td><td><span class="label label-danger">FAILED</span></td></tr>';
}
if (function_exists('file_get_contents'))
{
    echo '<tr><td><code>file_get_contents</code></td><td>Available</td><td><span class="label label-success">OK</span></td></tr>';
} else
{
    echo '<tr><td><code>file_get_contents</code></td><td>Available</td><td><span class="label label-danger">FAILED</span></td></tr>';
}
if ($content = @file_get_contents('http://ihsana.com/pub/test.txt'))
{
    echo '<tr><td><code>Connect ihsana.com</code></td><td>Allow</td><td>' . $content . '</td></tr>';
} else
{
    echo '<tr><td><code>Connect ihsana.com</code></td>Allow<td></td><td><span class="label label-danger">FAILED</span></td></tr>';
}
if ($content = @file_get_contents('http://ihsana.net/pub/test.txt'))
{
    echo '<tr><td><code>Connect ihsana.net</code></td><td>Allow</td><td>' . $content . '</td></tr>';
} else
{
    echo '<tr><td><code>Connect ihsana.net</code></td><td>Allow</td><td><span class="label label-danger">FAILED</span></td></tr>';
}
if (is_writable('projects'))
{
    echo '<tr><td><code>DIR /projects/</code><td>Writable</td></td><td><span class="label label-success">OK</span></td></tr>';
} else
{
    echo '<tr><td><code>DIR /projects/</code></td><td>Writable</td><td><span class="label label-danger">FAILED</span></td></tr>';
}
foreach (glob("projects/*") as $filename)
{
    if (is_dir($filename))
    {
        if (is_writable($filename))
        {
            echo '<tr><td><code>DIR /' . $filename . '/</code></td><td>Writable</td><td><span class="label label-success">OK</span></td></tr>';
        } else
        {
            echo '<tr><td><code>DIR /' . $filename . '/</code></td><td>Writable</td><td><span class="label label-danger">FAILED</span></td></tr>';
        }
    }
}
if (is_writable('output'))
{
    echo '<tr><td><code>DIR /output/</code></td><td>Writable</td><td><span class="label label-success">OK</span></td></tr>';
} else
{
    echo '<tr><td><code>DIR /output/</code></td><td>Writable</td><td><span class="label label-danger">FAILED</span></td></tr>';
}
foreach (glob("output/*") as $filename)
{
    if (is_dir($filename))
    {
        if (is_writable($filename))
        {
            echo '<tr><td><code>DIR /' . $filename . '/</code></td><td>Writable</td><td><span class="label label-success">OK</span></td></tr>';
        } else
        {
            echo '<tr><td><code>DIR /' . $filename . '/</code></td><td>Writable</td><td><span class="label label-danger">FAILED</span></td></tr>';
        }
    }
}
if (is_writable('system/class/jsmIonic.php'))
{
    echo '<tr><td><code>DIR /system/class/jsmIonic.php</code></td><td>Writable</td><td><span class="label label-success">OK</span></td></tr>';
} else
{
    echo '<tr><td><code>DIR /system/class/jsmIonic.php</code></td><td>Writable</td><td><span class="label label-danger">FAILED</span></td></tr>';
}
echo '</table>';

?>  
 </div>
 </div>
</div>
<!-- TODO: activation -->
<div id="activation" class="tab-pane">
       <div class="panel panel-default">
       <div class="panel-body">  
       <div>
            <a class="btn btn-primary" href="?server=1">SERVER 1</a>
            <a class="btn btn-primary" href="?server=2">SERVER 2</a>
            <a class="btn btn-primary" href="?server=3">SERVER 3 (only for Renew/Update)</a>         
       </div>
        <br/>
        <div class="alert alert-danger">
            <p>You will register with server: <span class="label label-danger"><?php echo $_SESSION["SERVER"];?></span></p>
            <p>Status activation:
<?php

if (file_exists(JSM_PATH . "/projects/config.php"))
{
    echo "<span class='label label-warning'>existing activation<span>";
} else
{
    echo "<span class='label label-warning'>new activation<span>";
}

?>
             </p>
        </div>
        <blockquote class="blockquote blockquote-info">
            <h4>Note</h4>
            <p>If you get status `new activation`, but you already click register button and the result still failed to active. It mean problem with permission files/folder your server.</p>
            <p>And status `existing activation` but still failed to active, try again more 2 or 3 times. If still problem, check your purchase code. When problem with internet connection use <code>Manual Activation</code>.</p>
        <h4>Changing permission files/folder</h4>
        <div class="row">
            <div class="col-md-12">
                <blockquote class="blockquote blockquote-danger">
                    <p>Permissions are used 777 by including folder and subfolders (option <code>-R</code> / recursive).</p>
                </blockquote>            
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h4>Linux/OSX</h4>
                <?php echo "<p>Run terminal, type this command:<pre>$ sudo su\r\n$ cd " . __dir__ . "\r\n$ chmod -R 777 *</pre></p>";?>   
            </div>
            <div class="col-md-6">
            <h4>Windows</h4>
            <p>Run explorer, go to <code><?php echo __dir__ ?></code>. Normally, you would access the properties of a file or folder by <code>right-clicking</code> it,
then selecting <code>Properties</code> from the appearing context menu.
in Tab <code>General</code>, unchecked <code>read-only</code> then click <code>OK</code> 
            </p>
            </div>
        </div>
         </blockquote>
       <div class="form-group">
            <label>Installation Code</label>
            <input type="text" class="form-control" readonly="" value="<?php echo $check_version ?>" />
        </div>
        <h4>Envato Market</h4>            
        <div class="form-group">
            <label>Username on Market</label>
            <input name="username" type="text" class="form-control" placeholder="username" required="" value="<?php echo JSM_ENVATO_USERNAME ?>" />
            <p class="help-block">please enter the correct username on market.</p>
        </div>
        <div class="form-group">
            <label>Your Email to contact us.</label>
            <input name="email" type="email" class="form-control" placeholder="you@domain.com" required="" value="<?php echo JSM_EMAIL ?>" />
            <p class="help-block">Your email will be used to contact us and <code>wrong email</code>, <code>you will lose support</code> (excludes: Extended License).</p>
        </div>
        <div class="form-group">
            <label>Envato Purchase Code</label>
            <input name="purchase_code" type="text" class="form-control" placeholder="758b0a8b-c495-4b2a-81z9-0be1bc5b073c" value="<?php echo JSM_PURCHASE_CODE ?>" />
            <p class="help-block">Please enter the correct purchase code, get it <a href="http://codecanyon.net/item/ionic-mobile-app-builder/15716727/support">here</a>.</p>
        </div>
        <hr />
        <?php

if (JSM_PURCHASE_CODE != ''){
    echo '
            <h4>Manual Activation</h4>  
            <div class="form-group">
                <label>Activation Code</label>
                ' . $code_activation . '
                <textarea name="manual_activation_code" class="form-control" placeholder="" /></textarea>
            </div>    
           ';
}

?>        
<div class="form-group">
    <label>IMA Builder Privacy Policy</label>
    <div style="overflow-y: scroll; height: 200px;">
    <p>This privacy policy has been compiled to better serve those who are concerned with how their 
	'Personally Identifiable Information' (PII) is being used online.</p>
    <strong>What personal information do we collect from the people that using our product, website or app?</strong> 
    <p>When ordering or registering on our site, as appropriate, you may be asked to enter your name, email address, purchase code and Public IP Address to help you with your experience.</p> 
    <strong>When do we collect information?</strong>
    <p>We collect information from you when using this app, you register on our site, Open a Support Ticket or enter information on our site.</p>
	<strong>Are we know your project?</strong>
    <p>We did not know it, it is your secret.</p>
    <strong>How do we use your information? </strong>
    <p>We may use the information we collect from you when using this app, you register, communication, surf the website in the following ways:</p>
    <ul>
	<li>To allow us to <strong>add your email to our support contact</strong> automatically.</li>
    <li>To allow us to <strong>better serve</strong> you in responding support tickets, for knowing BUYER or NOT.</li>
    <li>To sending <strong>latest information</strong>, <strong>download links</strong>, <strong>updates</strong> and more.</li>
    </ul>
    <p>All data that we collect will NOT be transferable and will NOT be sold, or NOT traded.</p>
    <p>With the registration of this product we will assume that you agree to these terms</p>
    <strong>Contacting Us</strong>
    <p>If there are any questions regarding this privacy policy, you may contact us using the information below.</p>
    Email: info@ihsana.com
    </div>
</div>
        <div class="form-group">
        <label></label>
        <input type="submit" name="submit" class="btn btn-lg btn-primary" type="submit" value="Register"/>
     </div> 
     </div>
     </div>
       </div>
       </div>
     </form> 
    </div>
    <script src="./templates/default/js/jquery.min.js"></script>
	<script src="./templates/default/js/jquery-sortable.min.js"></script>
    <script src="./templates/default/js/bootstrap.min.js"></script>
    <script src="./templates/default/js/typeahead.bundle.min.js"></script>
    <script src="./templates/default/js/jsm.js"></script>
  </body>
</html>