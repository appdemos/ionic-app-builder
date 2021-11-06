<?php

/**
 * @author Jasman
 * @copyright 2017
 */
$html = null;

    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="Ihsana CMS" />
    <meta name="robots" content="noindex, nofollow" />
    <link rel="icon" href="./templates/default/img/logo.png" type="image/x-icon" />
    <link href="http://ihsana.net/pub/rss.php" rel="alternate" type="application/atom+xml" title="IMA BuildeRz ~ Latest News" />
    <title>IMA BuildeRz | Login</title>
    <link href="./templates/default/css/bootstrap.css" rel="stylesheet"/>
    <style type="text/css">
    <!--
    body{padding-top:40px;padding-bottom:40px;background-color:#eee}
    .form-signin {}
    .form-signin {max-width:330px;padding:15px;margin:0 auto}
    .form-signin .form-signin-heading,.form-signin .checkbox{margin-bottom:10px}
    .form-signin .checkbox{font-weight:normal}
    .form-signin .form-control{position:relative;height:auto;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:10px;font-size:16px}
    .form-signin .form-control:focus{z-index:2}
    .form-signin input[type="text"]{margin-bottom:-1px;border-bottom-right-radius:0;border-bottom-left-radius:0}
    .form-signin input[type="password"]{margin-bottom:10px;border-top-left-radius:0;border-top-right-radius:0}
    -->
    </style>
</head>
<body>
    <div class="container">
      <form class="form-signin" action="./?page=o-auth" method="post" enctype="multipart/form-data">
        <h2 class="form-signin-heading">IMA BuildeRz</h2>
        <input name="uname" type="text" class="form-control" placeholder="Username" required="required" autofocus="true" />
        <input name="pwd" type="password" class="form-control" placeholder="Password" required="required"/>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>
    </div> 
</body>
</html>';

if (isset($_GET['logout']))
{
    $_SESSION['is_login'] = false;
    header('Location: ./?page=o-auth');
}
die($html);

?>