<?php
/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solutiom 2016
 * @license Commercial License
 * 
 * @package Ionic App Builder
 */
/* if (JSM_DEMO == true) {
    $no_cache = base64_encode('demo-version');
} else {
    $no_cache = base64_encode(JSM_PURCHASE_CODE);
}*/
$output_url=null;
if(isset( $_SESSION['PROJECT']['app']['prefix'])){
    $output_path = explode('?page=',  $_SERVER["REQUEST_URI"]) ;
    $output_url ='http://' . $_SERVER["HTTP_HOST"] . str_replace('//','/',$output_path[0] . 'output/' . $_SESSION['PROJECT']['app']['prefix'].'/www/');
}

if (!isset($this->emulator)) {
    $this->emulator = true;
}
if ($this->emulator == true) {
    $col = 'col-md-8';
} else {
    $col = 'col-md-12';
}
$this->demo_url = str_replace('/www', '/www/?no-cache=' . time(), $this->demo_url);
if (!isset($_SESSION['emulator'])) {
    $_SESSION['emulator'] = '
                        <div class="table-responsive" style="overflow-y:scroll !important;">
                            <div class="marvel-device iphone5s black">
                                <div class="top-bar"></div>
                                <div class="sleep"></div>
                                <div class="volume"></div>
                                <div class="camera"></div>
                                <div class="sensor"></div>
                                <div class="speaker"></div>
                                <div class="screen">
                                    <iframe class="phone-frame" src="{{EMULATOR_LINK}}" allowfullscreen></iframe>
                                </div>
                                <div class="home"></div>
                                <div class="bottom-bar"></div>
                            </div>
                        </div>
                    ';
}
if (isset($_GET['emulator'])) {
    switch ($_GET['emulator']) {
        case 'nexus5':
            $_SESSION['emulator'] = '
                <div class="table-responsive" style="overflow-y:scroll !important;">
                   <div class="marvel-device nexus5">
            			<div class="top-bar"></div>
            			<div class="sleep"></div>
            			<div class="volume"></div>
            			<div class="camera"></div>
            			<div class="screen">
                            <iframe class="phone-frame" src="{{EMULATOR_LINK}}" allowfullscreen></iframe>
                        </div>
            		</div>
                </div>
                    ';
            header('Location: ' . $_SERVER["HTTP_REFERER"]);
            break;
        case 'iphone5s':
            $_SESSION['emulator'] = '
                    <div class="table-responsive" style="overflow-y:scroll !important;">
                        <div class="marvel-device iphone5s silver">
                            <div class="top-bar"></div>
                            <div class="sleep"></div>
                            <div class="volume"></div>
                            <div class="camera"></div>
                            <div class="sensor"></div>
                            <div class="speaker"></div>
                            <div class="screen">
                                <iframe class="phone-frame" src="{{EMULATOR_LINK}}" allowfullscreen></iframe>
                            </div>
                            <div class="home"></div>
                            <div class="bottom-bar"></div>
                        </div>
                    </div>
					';
            header('Location: ' . $_SERVER["HTTP_REFERER"]);
            break;
        case 'lumia920':
            $_SESSION['emulator'] = '
                    <div class="table-responsive" style="overflow-y:scroll !important;"> 
                        <div class="marvel-device lumia920 white">
                            <div class="top-bar"></div>
                            <div class="volume"></div>
                            <div class="camera"></div>
                            <div class="speaker"></div>
                            <div class="screen">
                                <iframe class="phone-frame" src="{{EMULATOR_LINK}}" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
            ';
            header('Location: ' . $_SERVER["HTTP_REFERER"]);
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['JSM_LANG'] ?>">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="Ihsana CMS" />
        <meta name="robots" content="noindex, nofollow" />
        <link rel="icon" href="./templates/default/img/logo.png" type="image/x-icon" />
       
        <title><?php echo $this->title; ?></title>
        <script type="text/javascript">
            var app_username = "<?php if (JSM_DEMO == false) { echo JSM_ENVATO_USERNAME;}?>";
            var app_email = "<?php if (JSM_DEMO == false) { echo JSM_EMAIL; }?>";
            var app_logtime = "<?php echo $_SESSION['LONGTIME']?>";
            var app_key = "<?php if (JSM_DEMO == false) { echo sha1(JSM_PURCHASE_CODE);}?>";
            var app_filebrowser = "<?php echo JSM_FILEBROWSER ?>";
        </script>
        <?php if (JSM_CDN == true){ ?>
            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css?no-cache=<?php echo $no_cache;?>" rel="stylesheet"/>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.css?no-cache=<?php echo $no_cache;?>" rel="stylesheet"/>
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css?no-cache=<?php echo $no_cache;?>" rel="stylesheet"/>
        <?php } else { ?>
            <link href="./templates/default/vendor/fontawesome/css/font-awesome.min.css?no-cache=<?php echo $no_cache;?>" rel="stylesheet"/>
            <link href="./templates/default/css/ionicon.css?no-cache=<?php echo $no_cache;?>" rel="stylesheet"/>
            <link href="./templates/default/css/bootstrap.css?no-cache=<?php echo $no_cache;?>" rel="stylesheet"/>
        <?php } ?>
        <link href="./templates/default/css/fonts.css?no-cache=<?php echo $no_cache;?>" rel="stylesheet"/>
        <link href="./templates/default/css/jsm.css?no-cache=<?php echo $no_cache;?>" rel="stylesheet"/>
        <link href="./templates/default/css/pageguide.min.css?no-cache=<?php echo $no_cache;?>" rel="stylesheet"/>
        <link href="./templates/default/vendor/devices/devices.min.css?no-cache=<?php echo $no_cache; ?>" rel="stylesheet"/>
        <link href="./templates/default/css/bootstrap-colorpicker.min.css?no-cache=<?php echo $no_cache; ?>" rel="stylesheet"/>
        <link href="./templates/default/css/bootstrap.tagsinput.css?no-cache=<?php echo $no_cache;?>" rel="stylesheet"/>
        
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="<?php echo $this->base_url; ?>/templates/default/js/html5shiv.js?no-cache=<?php echo $no_cache; ?>"></script>
          <script src="<?php echo $this->base_url; ?>/templates/default/js/respond.min.js?no-cache=<?php echo $no_cache;?>"></script>
        <![endif]-->
    </head>
    <body>
        <div class="header">
            <div class="container-fluid">
                <div class="blog-header">
                    <div class="blog-logo pull-left">
                        <img  src="./templates/default/img/logo.png" style="width:70px;height:70px;padding:7px"/>
                    </div>
                    
                    <div class="pull-left">
                        <h1 class="blog-title"><?php echo $this->base_title; ?></h1>
                        <p class="blog-description">Easy Creating Your Own Hybrid Apps Without Coding</p>
                    </div>
                    
            
                </div>
            </div>
        </div>
        <?php echo $this->sidebar;?>
        <div class="container-fluid">
            <div class="row">
                <div class="<?php echo $col;?> blog-main">
                    <div class="panel panel-default">
                        <div class="panel-body page-main">
                            <?php echo $this->content;?>
                            <?php echo $this->page_guide;?>
                        </div>
                    </div>
                </div>
        <?php if ($this->emulator == true) { ?>
                    <div class="col-sm-4" id="emulator">
                        <div class="panel panel-default">
                            <div class="panel-body">  
                                <h4><span class="fa-stack fa-lg"><i class="fa fa-square-o fa-stack-2x"></i><i class="fa fa-tablet fa-stack-1x"></i></span>(IMAB) Web Emulator</h4>
                                <div class="adb-trick">
                                    <div class="adb-notice">
                                         <blockquote class="blockquote blockquote-danger">
                                            You should disable <code>adsblock</code> addons 
                                        </blockquote> 
                                    </div>
                                    <div class="adb-enabled">
                                         <blockquote class="blockquote blockquote-info">
                                            <?php echo __('Run in <code>private/incognito</code> mode on browser, disable <code>ads block</code> addons and don\'t using <code>squid/proxy</code>');?>. 
                                         </blockquote> 
                                    </div>
                                </div>
                                <?php echo str_replace("{{EMULATOR_LINK}}", $this->demo_url, $_SESSION['emulator']);?>
                               
                                <hr />
                                Browser Link:<br />
                                <code><?php echo $output_url ?></code>
                                 
                                <p><span class="label label-danger">TIP</span> : <?php echo __('for resfresh pages in emulator pull down that pages');?>.</p>                        
                                <div class="x-panel-footer">
                                    <div class="btn-group" >
                                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                                            Web Emulator <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="./?emulator=nexus5"><i class="fa fa-android"></i> Nexus</a></li>
                                            <li><a href="./?emulator=iphone5s"><i class="fa fa-apple"></i> iphone</a></li>
                                            <li><a href="./?emulator=lumia920"><i class="fa fa-windows"></i> Lumia</a></li>
                                        </ul>
                                        <a class="btn-group btn btn-danger" href="#" onclick="window.localStorage.clear();">LocalStorage Clear</a>
                                        <a class="btn-group btn btn-danger" target="_blank" href="<?php echo $this->demo_url ?>">New Window</a>
                                    </div>
                                    <blockquote class="blockquote blockquote-danger">
                                        <h4><?php echo __('(IMAB) Emulator Rules')?>:</h4>
                                        <p><?php echo __('Not all features can be show in (IMAB) Emulator, there are features not show in (IMAB) Emulator')?></p>
                                        <ul>
                                            <li><?php echo __('Features: <code>Admob Pro</code> and <code>Push Notification</code>')?></li> 
                                            <li><?php echo __('Type menu open with <code>webView</code>, <code>appBrowser</code>, <code>SMS</code>, <code>Telp</code>, or <code>GooglePlay</code>')?></li> 
                                            <li><?php echo __('Image <code>Icon</code> and loading <code>Splashscreen</code>')?></li>
                                            <li><?php echo __('Fullscreen mode in video/iframe youtube')?></li>
                                            <li><?php echo __('Link in iframe/webview/appbrowser cannot connect to app')?></li>

                                        </ul>
                                    </blockquote>
                                </div>
                                <br/>
                            </div>
                        </div>
                    </div>
    <?php } ?>
            </div>
        </div>
<?php
if (JSM_DEBUG == false) {
    if (filesize(JSM_IONIC_CLASS) != 640000) {
        //echo '<script>window.location="./setup.php";</script>';
        //die();
    }
}
$messgae = null;
if (JSM_DEMO == false) {
    $messgae .= "\r\n\r\n";
    $messgae .= '------------------' . "\r\n";
    $messgae .= 'Envato:' . "\32" . '' . JSM_ENVATO_USERNAME . "\r\n";
    $messgae .= 'Product:' . "\32" . 'IMABuildeRz(' . JSM_VERSION . ')' . "\r\n";
    $messgae .= 'PurcaseCode:' . "\32" . '' . JSM_PURCHASE_CODE . "\r\n";
    $messgae .= 'Email:' . "\32" . '' . JSM_EMAIL . "\r\n";
    $messgae .= 'OS:' . "\32" . '' . PHP_OS . "\r\n";
    $messgae .= '------------------' . "\r\n";
} else {
    $messgae .= 'your msg';
   echo '<img src="https://sstatic1.histats.com/0.gif?3406294&101" alt="" />';
}
?>
        <footer id="support">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <h4><?php echo __('Latest News');?></h4>
                        <ul id="news" class="list-unstyled"></ul>
                    </div>
                    <div class="col-md-4">
                        <h4><?php echo __('This Product is licensed to:');?></h4>
                        <ul id="license" class="list-unstyled"></ul>
                    </div>
                    <div class="col-md-4">
                        <h4><?php echo __('Tutorials and Tech Support');?></h4>
                        <ul id="contact_us" class="list-unstyled">
                            <li>
                                Email: <a href="mailto:info@ihsana.com?body=<?php echo urlencode($messgae)?>&subject=Issue about ....">info@ihsana.com</a> or <a href="mailto:info@ihsana.net?body=<?php echo urlencode($messgae)?>&subject=Issue about ....">info@ihsana.net</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <div id="copyright">
            <div class="container-fluid">
                <p>Copyright &copy; <?php echo date("Y") ?> - <a href="http://codecanyon.net/item/ionic-mobile-app-builder/15716727?ref=codegenerator">Ihsana Mobile App Builder</a> - Translator by <a target="_blank" href="<?php echo $this->translator['url']  ?>"><?php echo $this->translator['name'] ?></a>,  you have version: <label class="text-danger">rev<?php echo JSM_VERSION; ?></label></p>
            </div>
        </div>
        <?php
if (JSM_DEBUG == true) {
    if (isset($_SESSION['FILE_NAME'])) {
        echo '<div class="container-fluid">';
        echo '<h4>Debug Mode</h4>';
        echo '<p>'.$_SERVER["HTTP_USER_AGENT"].'</p>';
        echo '<a target="_blank" class="btn btn-success" href="./projects/' . $_SESSION['FILE_NAME'] . '">projects</a>';
        echo '<a target="_blank" class="btn btn-info" href="./output/' . $_SESSION['FILE_NAME'] . '">Outputs</a>';
        echo '<textarea id="debug">' . htmlentities(json_encode(@$_SESSION['PROJECT'], JSON_PRETTY_PRINT)) . '</textarea>';
        echo '
    <link rel="stylesheet" href="./templates/default/vendor/codemirror/lib/codemirror.css">
    <link rel="stylesheet" href="./templates/default/vendor/codemirror/addon/hint/show-hint.css">
    <link rel="stylesheet" href="./templates/default/vendor/codemirror/addon/fold/foldgutter.css">
    <script src="./templates/default/vendor/codemirror/lib/codemirror.js"></script>
    <script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
    <script src="./templates/default/vendor/codemirror/mode/javascript/javascript.js"></script>
    <script src="./templates/default/vendor/codemirror/addon/edit/matchbrackets.js"></script>
    <script src="./templates/default/vendor/codemirror/addon/fold/foldcode.js"></script>
    <script src="./templates/default/vendor/codemirror/addon/fold/foldgutter.js"></script>
    <script src="./templates/default/vendor/codemirror/addon/fold/brace-fold.js"></script>
    <script src="./templates/default/vendor/codemirror/addon/hint/show-hint.js"></script>
    <script src="./templates/default/vendor/codemirror/addon/hint/javascript-hint.js"></script>
    <script type="text/javascript">
      var editor = CodeMirror.fromTextArea(document.getElementById("debug"), {
        lineNumbers: true,
        foldGutter: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
        mode: "application/ld+json",
        extraKeys: {"Ctrl-Space": "autocomplete"},
      });
    </script>
    
    ';
        echo '</div>';
    }
}
?>
 <?php
if (JSM_CDN == true) { ?>
            <script src="https://code.jquery.com/jquery-1.12.3.min.js?no-cache=<?php echo $no_cache; ?>"></script>
            <script src="./templates/default/js/jquery-sortable.min.js?no-cache=<?php echo $no_cache;?>"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js?no-cache=<?php echo $no_cache;?>"></script>
            <script src="./templates/default/js/typeahead.bundle.min.js?no-cache=<?php echo $no_cache;?>"></script>
   <?php } else { ?>
            <script src="./templates/default/js/jquery.min.js?no-cache=<?php echo $no_cache;?>"></script>
            <script src="./templates/default/js/jquery-sortable.min.js?no-cache=<?php echo $no_cache;?>"></script>
            <script src="./templates/default/js/bootstrap.min.js?no-cache=<?php echo $no_cache;?>"></script>
            <script src="./templates/default/js/typeahead.bundle.min.js?no-cache=<?php echo $no_cache;?>"></script>   
            <?php }?>
        <script src="./templates/default/js/bootstrap.tagsinput.min.js"></script>
        <script src="./templates/default/js/bootstrap-colorpicker.min.js"></script>
        <script src="./templates/default/js/pageguide.min.js?no-cache=<?php echo $no_cache; ?>"></script>
        <script src="./templates/default/js/jsm.js?no-cache=<?php echo $no_cache; ?>"></script>
        <script src="./templates/default/js/detect-private-browsing.js?no-cache=<?php echo $no_cache; ?>"></script>
        <?php echo $this->footer ?>
        <script type="text/javascript">
                                        jQuery(document).ready(function () {
                                            var pageguide = tl.pg.init({
                                                pg_caption: 'Page Guide',
                                                steps_element: "#guide"
                                            });
        <?php
foreach (explode(',', str_replace(' ', '', JSM_NEW_FEATURES)) as $_feature) {
    echo '$("#' . $_feature . '").prepend("<span class=\'badge pull-right danger-bg\'>NEW</span>");' . "\r\n";
}
?>
                                        });
                                        console.log("%cIMA BuildeRz", "color:green;font-size:48px;");
                                        console.log("%cDebuging: %cGo to -> Table -> Custom Messages -> checked Show Error (Debug)", "color:blue;font-size:16px;", "color:red;font-size:16px;");
                                        console.log("%cfile: cordova.js will be a 404 during development", "color:green;font-size:16px;");
        </script>
        
 
         
    </body>
</html>