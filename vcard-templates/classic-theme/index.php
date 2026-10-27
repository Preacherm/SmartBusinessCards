<!DOCTYPE html>
<html lang="<?php _esc($config['lang_code']);?>" dir="<?php _esc($lang_direction);?>">
<head>
    <title><?php if($page_title != ""){ _esc($page_title)._esc(" - "); } _esc($config['site_title']); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="HandheldFriendly" content="True">

    <meta name="author" content="<?php _esc($config['site_title']);?>">
    <meta name="keywords" content="<?php _esc($page_meta_keywords);?>">
    <meta name="description" content="<?php ($page_meta_description == '')?_esc($page_meta_description):_esc($page_meta_description);?>">

    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//google.com">
    <link rel="dns-prefetch" href="//apis.google.com">
    <link rel="dns-prefetch" href="//ajax.googleapis.com">
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//pagead2.googlesyndication.com">
    <link rel="dns-prefetch" href="//gstatic.com">
    <link rel="dns-prefetch" href="//oss.maxcdn.com">

    <link rel="shortcut icon" href="<?php _esc($config['site_url']);?>storage/logo/<?php _esc($config['site_favicon']);?>">
    <script async>
        var themecolor = '<?php _esc($config['theme_color']);?>';
        var siteurl = '<?php _esc($config['site_url']);?>';
        var template_name = '<?php _esc($config['tpl_name']);?>';
        var ajaxurl = "<?php _esc($config['site_url']);?>php/<?php _esc($config['quickad_user_secret_file']);?>.php";
    </script>
    <!--Loop for Theme Color codes-->
    <style>
        :root{
            <?php
                $themecolor = $config['theme_color'];
                foreach ($colors as $color){
                    echo "--theme-color-".$color['id'].": ".$color['value'].";";
                }
            ?>
        }
    </style>
    <!--Loop for Theme Color codes-->

    <link rel="stylesheet" href="<?php _esc($config['site_url']);?>includes/assets/css/icons.css">
    <link rel="stylesheet" href="<?php _esc($config['site_url']);?>includes/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php _esc($config['site_url']);?>vcard-templates/classic-theme/css/style.css?ver=<?php _esc($config['version']);?>">

    <script src="<?php _esc(TEMPLATE_URL);?>/js/jquery.min.js"></script>
</head>
<body class="<?php _esc($lang_direction);?>">
<!--[if lt IE 8]>
<p class="browserupgrade">
    You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.
</p>
<![endif]-->
<div class="wrapper">
    <div class="vCard">
        <div class="vCard-header">
            <div class="bg-white rounded overflow-hidden">
                <div class="p-4 cover" style="background-image:url(<?php _esc($config['site_url']);?>storage/cards/cover/<?php _esc($cover_image);?>)">
                    <div class="media align-items-end profile-head">
                        <div class="profile mr-3">
                            <img src="<?php _esc($config['site_url']);?>storage/cards/logo/<?php _esc($main_image);?>" alt="<?php _esc($title);?>" class="rounded mb-4 img-thumbnail">
                        </div>
                        <div class="media-body mb-5 text-white">
                            <h4 class="mt-0 mb-0 text-white"><?php _esc($title);?></h4>
                            <p class="small mb-4 text-white font-italic"><?php _esc($sub_title);?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-light p-4 pt-5 vcard-description">
                    <?php _esc($description);?>
                </div>
            </div>
        </div>
        <?php if($show_details){ ?>
            <div class="vCard-contact">
                <ul class="vCard-list">
                </ul>
            </div>
       <?php } ?>
    </div>
    <?php if(!$hide_branding){ ?>
        <div class="p-2 vcard-branding">
            <a href="<?php _esc($config['site_url']);?>" class="text-white" target="_blank"><?php _e("Provided by QuickVCard");?></a>
        </div>
    <?php } ?>
    <div class="add-to-contact-wrapper">
        <a href="<?php _esc(htmlspecialchars($contact_download_url, ENT_QUOTES, 'UTF-8'));?>" class="btn btn-primary add-to-contact-btn"><i class="fa fa-address-card"></i>&nbsp; <?php _e("Add to Contact");?></a>
    </div>
</div>
<script>
    var VCARD_DETAILS           = <?php _esc($details);?>,
        TITLE                   = "<?php _esc($title);?>",
        SUB_TITLE               = "<?php _esc($sub_title);?>",
        LOGO                    = "<?php _esc($config['site_url']);?>storage/cards/logo/<?php _esc($main_image);?>",
        LOGO_64_ENCODED         = "<?php _esc($main_image_64_encoded);?>",
        DESCRIPTION             = <?php _esc($description_js);?>,
        DETAILS_FIELD_LIMIT     = <?php _esc($details_field_limit);?>;
</script>
<script src="<?php _esc($config['site_url']);?>vcard-templates/classic-theme/js/script.js?ver=<?php _esc($config['version']);?>"></script>
</body>
</html>