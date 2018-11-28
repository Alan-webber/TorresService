<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?php if(!empty($meta_tag)){?>

        <?php foreach ($meta_tag as $key => $value) {
            echo "<meta property=\"og:{$key}\" content=\"{$value}\">";
        }?>

    <?php }?>

    <title>Torres Service</title>

    <link rel="stylesheet" href="<?= base_url('assets/css/materialize.min.css') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/slick.css') ?>">
    
    <link rel="stylesheet" href="<?= base_url('assets/css/slick-theme.css') ?>">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Oleo+Script">
    
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css?v=3') ?>">

    <script src="<?= base_url('assets/js/jquery-3.3.1.min.js')?>"></script>

    <script src="<?= base_url('assets/js/materialize.min.js')?>"></script>

</head>
<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v3.2&appId=341514386659706&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

