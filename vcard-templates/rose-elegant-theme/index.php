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
    <link rel="dns-prefetch" href="//gstatic.com">

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
    <link rel="stylesheet" href="<?php _esc($config['site_url']);?>vcard-templates/rose-elegant-theme/css/style.css?ver=<?php _esc($config['version']);?>">

    <script src="<?php _esc(TEMPLATE_URL);?>/js/jquery.min.js"></script>
</head>
<body class="<?php _esc($lang_direction);?>">
<?php
    $re_initials = '';
    foreach (preg_split('/\s+/', trim((string)$title)) as $re_part) {
        if ($re_part !== '') { $re_initials .= strtoupper(substr($re_part, 0, 1)); }
    }
    $re_initials = substr($re_initials, 0, 2);
    if ($re_initials === '') { $re_initials = 'V'; }
?>
<div class="re-wrapper">
    <div class="re-card">

        <!-- Decorative corner + avatar -->
        <div class="re-top">
            <span class="re-corner"></span>
            <div class="re-avatar">
                <img src="<?php _esc($config['site_url']);?>storage/cards/logo/<?php _esc($main_image);?>" alt="<?php _esc($title);?>">
            </div>
        </div>

        <!-- Identity -->
        <div class="re-identity">
            <h1 class="re-name"><?php _esc($title);?></h1>
            <p class="re-role"><?php _esc($sub_title);?></p>
        </div>

        <!-- Body -->
        <div class="re-body">
            <?php if($description != ''){ ?>
                <div class="re-about"><?php _esc($description);?></div>
            <?php } ?>

            <?php if($show_details){ ?>
                <ul class="re-list vCard-list"></ul>
            <?php } ?>

            <div class="re-action">
                <a href="<?php _esc(htmlspecialchars($contact_download_url, ENT_QUOTES, 'UTF-8'));?>" class="btn add-to-contact-btn re-save-btn"><i class="fa fa-address-card"></i>&nbsp; <?php _e("Add to Contact");?></a>
            </div>

            <!-- Brand monogram -->
            <div class="re-brand">
                <span class="re-brand-mark"><?php echo htmlspecialchars($re_initials, ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="re-brand-text"><?php _esc($title);?></span>
            </div>

            <!-- Social bar -->
            <?php if($show_details){ ?>
                <div class="re-connect"><?php _e("Let's connect");?></div>
                <div class="re-social vCard-social"></div>
            <?php } ?>
        </div>

        <?php if(!$hide_branding){ ?>
            <div class="re-branding vcard-branding">
                <a href="<?php _esc($config['site_url']);?>" target="_blank"><?php _e("Provided by QuickVCard");?></a>
            </div>
        <?php } ?>
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
<script src="<?php _esc($config['site_url']);?>vcard-templates/rose-elegant-theme/js/script.js?ver=<?php _esc($config['version']);?>"></script>
</body>
</html>
