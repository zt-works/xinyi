<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <title><?php wp_title("|",true,"right"); ?></title>
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/frontend/app/favicon.png"
          mce_href="<?php echo get_template_directory_uri(); ?>/images/frontend/app/favicon.png" type="image/x-png">
    <link href="<?php echo get_template_directory_uri(); ?>/css/frontend/src/main.css" rel="stylesheet">
    <script src="<?php echo get_template_directory_uri(); ?>/js/frontend/src/baiduAnalytics.js"></script>
</head>
<body>
<div class="header">
    <h1 class="logo"><a href="<?php echo home_url(); ?>">心一教育</a></h1>

    <?php wp_nav_menu(); ?>
</div>
