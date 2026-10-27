<?php
include '../includes.php';
$page_title = __('Settings');
include '../header.php'; ?>

<!-- Page Body Start-->
<div class="page-body-wrapper">
    <?php include '../sidebar.php'; ?>

    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-lg-6 main-header">
                        <h2><?php _esc($page_title) ?></h2>
                        <h6 class="mb-0"><?php _e('admin panel') ?></h6>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="tab-content">
                <div class="tab-pane active" id="quick_settings_general">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('General') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="site_url">
                                                <?php _e('Site URL') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('The site url is the url where you installed Script. Must be add slash / at the end of url like http://example.com/') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <input id="site_url" class="form-control" type="text" name="site_url" value="<?php _esc(get_option("site_url")); ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="site_title">
                                                <?php _e('Site Title') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('The site title is what you would like your website to be known as, this will be used in emails and in the title of your webpages.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <input name="site_title" class="form-control" type="text" id="site_title" value="<?php echo $config['site_title']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Meta Keywords') ?></label>
                                            <div>
                                                <input name="meta_keywords" type="text" class="form-control" value="<?php echo get_option("meta_keywords"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Meta Description') ?></label>
                                            <div>
                                                <input name="meta_description" type="text" class="form-control" value="<?php echo get_option("meta_description"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>
                                                <?php _e('Try Demo Link') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('This link is for home page Try Demo button link.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <div>
                                                <input name="try_demo_link" type="text" class="form-control" value="<?php echo get_option("try_demo_link"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="default_user_plan"><?php _e('Default Membership Plan for New Users') ?></label>
                                            <select name="default_user_plan" id="default_user_plan" class="form-control">
                                                <option value="free" <?php if(get_option("default_user_plan") == 'free'){ echo "selected"; } ?>><?php _e('Free') ?></option>
                                                <option value="trial" <?php if(get_option("default_user_plan") == 'trial'){ echo "selected"; } ?>><?php _e('Trial') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="cron_exec_time"><?php _e('Cron job run time (In seconds)') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('Please enter time in seconds for example: 60 = 1 minutes , 3600 = 1 Hour.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <input name="cron_exec_time" class="form-control" type="text" id="cron_exec_time" value="<?php echo $config['cron_exec_time']; ?>">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label><?php _e('Term & Condition Page Link') ?></label>
                                            <div>
                                                <input name="termcondition_link" type="text" class="form-control" value="<?php echo get_option("termcondition_link"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php _e('Privacy Page Link') ?></label>
                                            <div>
                                                <input name="privacy_link" type="text" class="form-control" value="<?php echo get_option("privacy_link"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php _e('Cookie Policy Page Link') ?></label>
                                            <div>
                                                <input name="cookie_link" type="text" class="form-control" value="<?php echo get_option("cookie_link"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Show/hide Verify Email Message'), 'non_active_msg', (get_option("non_active_msg") == '1'), __('When disallow, An verify email message will be hide to non-active users.')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Allow Non-active users to add vcard'), 'non_active_allow', (get_option("non_active_allow") == '1'), __('When disallow, An error message will be shown to non-active users to verify their email address.')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Allow User Language Selection'), 'userlangsel', (get_option("userlangsel") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Allow User Theme Selection'), 'userthemesel', (get_option("userthemesel") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Theme/Color switcher'), 'color_switcher', (get_option("color_switcher") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Show/hide Cookie Consent Box'), 'cookie_consent', (get_option("cookie_consent") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Site Demo Mode'), 'demo_mode', (get_option("demo_mode") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Enable Maintenance Mode'), 'enable_maintenance_mode', (get_option("enable_maintenance_mode") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6 <?php if (empty($config['developer_credit'])) {
                                        echo "d-none";
                                    } ?>">
                                        <?php quick_switch(__('Show Developer Credit'), 'developer_credit', (get_option("developer_credit") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6 <?php if (empty($config['quickad_debug'])) {
                                        echo "d-none";
                                    } ?>">
                                        <?php quick_switch(__('Enable Developement Mode'), 'quickad_debug', (get_option("quickad_debug") == '1')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="general_setting" value="1">
                                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="quick_logo_watermark">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('Logo') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Favicon upload-->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php _e('Favicon Icon') ?><code>*</code></label>
                                            <div class="screenshot">
                                                <img class="redux-option-image" id="favicon_uploader" src="<?php _esc($config['site_url']);?>/storage/logo/<?php echo $config['site_favicon']?>" alt="" target="_blank" rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%">
                                            </div>
                                            <input class="form-control input-sm" type="file" name="favicon" onchange="readURL(this,'favicon_uploader')">
                                            <span class="help-block"><?php _e('Ideal Size') ?> 16x16 PX</span>
                                        </div>
                                    </div>

                                    <!-- Site Logo upload-->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php _e('Logo') ?><code>*</code></label>
                                            <div class="screenshot"><img class="redux-option-image" id="image_logo_uploader" src="<?php _esc($config['site_url']);?>/storage/logo/<?php echo $config['site_logo']?>" alt="" target="_blank" rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%"></div>
                                            <input class="form-control input-sm" type="file" name="file" onchange="readURL(this,'image_logo_uploader')">
                                            <span class="help-block"><?php _e('Ideal Size') ?> 170x60 PX</span>
                                        </div>
                                    </div>

                                    <!-- Site Logo upload-->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php _e('Footer Logo') ?><code>*</code></label>
                                            <div class="screenshot"><img class="redux-option-image" id="image_flogo_uploader" src="<?php _esc($config['site_url']);?>/storage/logo/<?php echo $config['site_logo_footer']?>" alt="" target="_blank" rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%"></div>
                                            <input class="form-control input-sm" type="file" name="footer_logo" onchange="readURL(this,'image_flogo_uploader')">
                                            <span class="help-block"><?php _e('Display in the footer') ?></span>
                                        </div>
                                    </div>

                                    <!-- Admin Logo upload-->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php _e('Admin Logo') ?></label>
                                            <div class="screenshot"><img class="redux-option-image" id="adminlogo" src="<?php _esc($config['site_url']);?>/storage/logo/<?php _esc($config['site_admin_logo'])?>" alt="" target="_blank" rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%"></div>
                                            <input class="form-control input-sm" type="file" name="adminlogo" onchange="readURL(this,'adminlogo')">
                                            <span class="help-block"><?php _e('Ideal Size') ?> 235x62 PX</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="logo_watermark" value="1">
                                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="quick_map">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('Map') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="map_type"><?php _e('Map Type (Google/Openstreet)') ?></label>
                                            <select name="map_type" id="map_type" class="form-control">
                                                <option value="google" <?php if(get_option('map_type') == 'google'){ echo "selected"; } ?>>Google Map</option>
                                                <option value="openstreet" <?php if(get_option('map_type') == 'openstreet'){ echo "selected"; } ?>>Openstreet Map</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Google Map Color') ?></label>
                                            <div>
                                                <input name="map_color" type="color" class="form-control" value="<?php echo get_option('map_color'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="gmap_api_key"><?php _e('OpenStreet Access Token') ?></label>
                                            <p class="help-block"><a href="https://account.mapbox.com/access-tokens/" target="_blank">Get MapBox Access Token For OpenStreet Map</a></p>
                                            <input name="openstreet_access_token" class="form-control" type="text" id="openstreet_access_token" value="<?php echo get_option('openstreet_access_token'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="gmap_api_key"><?php _e('Google Map API Key') ?></label>
                                            <p class="help-block"><a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><?php _e('Get API Key') ?></a></p>
                                            <input name="gmap_api_key" class="form-control" type="text" id="gmap_api_key" value="<?php echo get_option('gmap_api_key'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Default Map Latitude') ?></label>
                                            <div>
                                                <input name="home_map_latitude" type="text" class="form-control" value="<?php echo get_option('home_map_latitude'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Default Map Longitude') ?></label>
                                            <div>
                                                <input name="home_map_longitude" type="text" class="form-control" value="<?php echo get_option('home_map_longitude'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Contact Map Latitude') ?></label>
                                            <div>
                                                <input name="contact_latitude" type="text" class="form-control" value="<?php echo get_option('contact_latitude'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Contact Map Longitude') ?></label>
                                            <div>
                                                <input name="contact_longitude" type="text" class="form-control" value="<?php echo get_option('contact_longitude'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="quick_map" value="1">
                                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="quick_international">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('International') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="specific_country"><?php _e('Default Country') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('When user first time visit your website. Then the site run for that choosen default country.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <div>
                                                <select  class="js-select2 form-control" name="specific_country" id="specific_country" style="width: 100%;">
                                                    <?php

                                                    $country = get_country_list(get_option("specific_country"));
                                                    foreach ($country as $value){
                                                        echo '<option value="'.$value['code'].'" '.$value['selected'].'>'.$value['asciiname'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="timezone"><?php _e('Timezone') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('Set your website timezone.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <div>
                                                <select name="timezone" id="timezone" class="js-select2 form-control"  style="width: 100%;">
                                                    <?php
                                                    $timezone = get_timezone_list(get_option("timezone"));

                                                    foreach ($timezone as $value) {
                                                        $id = $value['id'];
                                                        $country_code = $value['country_code'];
                                                        $time_zone_id = $value['time_zone_id'];
                                                        $selected = $value['selected'];
                                                        echo '<option value="'.$time_zone_id.'" '.$selected.' data-tokens="'.$time_zone_id.'">'.$time_zone_id.'</option>';
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="currency"><?php _e('Currency') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('This is default currecny which used for payment method.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <div>
                                                <select name="currency" id="currency"  class="js-select2 form-control" style="width: 100%;">
                                                    <?php
                                                    $currency = get_currency_list(get_option("currency_code"));

                                                    foreach ($currency as $value)
                                                    {
                                                        $id          = $value['id'];
                                                        $code        = $value['code'];
                                                        $name       = $value['name'];
                                                        $html_code   = $value['html_entity'];
                                                        $selected =  $value['selected'];

                                                        echo '<option value="'.$id.'" '.$selected.' data-tokens="'.$name.'">'.$name.' ('.$html_code.')</option>';
                                                    }

                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="inputEmail3"><?php _e('Language') ?></label>
                                            <select name="lang" id="lang" class="js-select2 form-control" style="width: 100%;">
                                                <?php
                                                $langs = array();
                                                $lang_list = get_language_list();

                                                foreach ($lang_list as $lang)
                                                {
                                                    $lang_name = $lang['name'];
                                                    $lang_file_name = $lang['file_name'];
                                                    $path_of_file = ROOTPATH.'/includes/lang/lang_'.$lang_file_name.'.php';
                                                    $selected = "";
                                                    if(get_option("lang") == $lang_file_name) {
                                                        $selected = "selected";
                                                    }

                                                    if(file_exists($path_of_file))
                                                        echo '<option value="'.$lang_file_name.'" '.$selected.'>'.$lang_name.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="international" value="1">
                                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="quick_email">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('Email SMTP Setting') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="admin_email">
                                                <?php _e('Admin Email') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('This is the email address that the contact and report emails will be sent to, aswell as being the from address in signup and notification emails.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <div>
                                                <input name="admin_email" class="form-control" type="text" id="admin_email" value="<?php echo get_option("admin_email");?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="smtp_host">
                                                <?php _e('SMTP Host') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('This is the host address for your smtp server, this is only needed if you are using SMTP as the Email Send Type.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <div>
                                                <input name="smtp_host" type="text" class="form-control" id="smtp_host" value="<?php echo get_option("smtp_host");?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="smtp_host">
                                                <?php _e('SMTP Port') ?>
                                            </label>
                                            <input name="smtp_port" type="text" class="form-control" id="smtp_port" value="<?php echo get_option("smtp_port");?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="smtp_username">
                                                <?php _e('SMTP Username') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('This is the username for your smtp server, this is only needed if you are using SMTP as the Email Send Type.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <input name="smtp_username" class="form-control" type="text" id="smtp_username" value="<?php echo get_option("smtp_username");?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="smtp_password">
                                                <?php _e('SMTP Password') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('This is the password for your smtp server, this is only needed if you are using SMTP as the Email Send Type.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <input name="smtp_password" type="password" class="form-control" id="smtp_password" value="<?php echo get_option("smtp_password");?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="smtp_secure">
                                                <?php _e('SMTP Encryption') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('If your e-mail service provider supported secure connections, you can choose security method on list.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <select name="smtp_secure" id="smtp_secure" class="form-control">
                                                <option value="0" <?php if(get_option("smtp_secure") == '0'){ echo "selected"; } ?>><?php _e('Off') ?></option>
                                                <option value="1" <?php if(get_option("smtp_secure") == '1'){ echo "selected"; } ?>><?php _e('SSL') ?></option>
                                                <option value="2" <?php if(get_option("smtp_secure") == '2'){ echo "selected"; } ?>><?php _e('TLS') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="smtp_auth">
                                                <?php _e('SMTP Auth') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('SMTP Authentication, often abbreviated SMTP AUTH, is an extension of the Simple Mail Transfer Protocol whereby an SMTP client may log in using an authentication mechanism chosen among those supported by the SMTP server.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <select name="smtp_auth" id="smtp_auth" class="form-control">
                                                <option value="true" <?php if(get_option("smtp_auth") == 'true'){ echo "selected"; } ?>><?php _e('On') ?></option>
                                                <option value="false" <?php if(get_option("smtp_auth") == 'false'){ echo "selected"; } ?>><?php _e('Off') ?></option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="email_setting" value="1">
                                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="quick_theme_setting">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('Theme Setting') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="smtp_auth"><?php _e('Home Page Header Sticky/Unsticky') ?></label>

                                            <select name="header_sticky" id="header_sticky" class="form-control">
                                                <option value="sticky" <?php if(get_option("header_sticky") == 'sticky'){ echo "selected"; } ?>><?php _e('Sticky') ?></option>
                                                <option value="unsticky" <?php if(get_option("header_sticky") == 'unsticky'){ echo "selected"; } ?>><?php _e('Unsticky') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="smtp_auth"><?php _e('Home Page Transparant Header') ?></label>

                                            <select name="transparent_header" id="transparent_header" class="form-control">
                                                <option value="transparent-header" <?php if(get_option("transparent_header") == 'transparent-header'){ echo "selected"; } ?>><?php _e('Transparant') ?></option>
                                                <option value="0" <?php if(get_option("transparent_header") == ''){ echo "selected"; } ?>><?php _e('White') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="smtp_auth"><?php _e('Home Page Banner Overlay') ?></label>

                                            <select name="banner_overlay" id="banner_overlay" class="form-control">
                                                <option value="dark-overlay" <?php if(get_option("banner_overlay") == 'dark-overlay'){ echo "selected"; } ?>><?php _e('Dark Overlay') ?></option>
                                                <option value="" <?php if(get_option("banner_overlay") == ''){ echo "selected"; } ?>><?php _e('White Overlay') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Show membership plan on home page'), 'show_membershipplan_home', (get_option("show_membershipplan_home") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Show partners slider on home page'), 'show_partner_logo_home', (get_option("show_partner_logo_home") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Theme Color') ?></label>
                                            <div>
                                                <input name="theme_color" type="color" class="form-control" value="<?php echo get_option("theme_color"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Contact Address') ?></label>
                                            <div>
                                                <input name="contact_address" type="text" class="form-control" value="<?php echo get_option("contact_address"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Contact Email') ?></label>
                                            <div>
                                                <input name="contact_email" type="text" class="form-control" value="<?php echo get_option("contact_email"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Contact Phone') ?></label>
                                            <div>
                                                <input name="contact_phone" type="text" class="form-control" value="<?php echo get_option("contact_phone"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php _e('Footer Facebook Page Link') ?></label>
                                            <div>
                                                <input name="facebook_link" type="text" class="form-control" value="<?php echo get_option("facebook_link"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php _e('Footer Twitter Page Link') ?></label>
                                            <div>
                                                <input name="twitter_link" type="text" class="form-control" value="<?php echo get_option("twitter_link"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php _e('Footer Instagram Page Link') ?></label>
                                            <div>
                                                <input name="instagram_link" type="text" class="form-control" value="<?php echo get_option("instagram_link"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php _e('Footer LinkedIn Page Link') ?></label>
                                            <div>
                                                <input name="linkedin_link" type="text" class="form-control" value="<?php echo get_option("linkedin_link"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php _e('Footer Pinterest Page Link') ?></label>
                                            <div>
                                                <input name="pinterest_link" type="text" class="form-control" value="<?php echo get_option("pinterest_link"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php _e('Footer Youtube Page/Video Link') ?></label>
                                            <div>
                                                <input name="youtube_link" type="text" class="form-control" value="<?php echo get_option("youtube_link"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Copyright text') ?></label>
                                            <div>
                                                <input name="copyright_text" type="text" class="form-control" value="<?php echo get_option("copyright_text"); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php _e('Footer text') ?></label>
                                            <div>
                                                <textarea name="footer_text" class="form-control"><?php echo get_option("footer_text"); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>
                                                <?php _e('External Javascript or Css In header') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('You can add Any javascript code and style css. Like Google Analytics code. This code will paste on head part.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <div>
                                                <textarea name="external_code" type="text" class="form-control" rows="5"><?php echo get_option("external_code"); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="theme_setting" value="1">
                                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="quick_billing_details">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('Billing Details') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p><?php _e('These details will be used for the invoice.') ?></p>
                                        <div class="form-group">
                                            <label class=""><?php _e('Invoice Number Prefix') ?></label>
                                            <div>
                                                <input name="invoice_nr_prefix" type="text" class="form-control" value="<?php echo get_option("invoice_nr_prefix"); ?>" placeholder="Ex: INV-">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class=""><?php _e('Name') ?></label>
                                            <div>
                                                <input name="invoice_admin_name" type="text" class="form-control" value="<?php echo get_option("invoice_admin_name"); ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class=""><?php _e('Email') ?></label>
                                                    <div>
                                                        <input name="invoice_admin_email" type="text" class="form-control" value="<?php echo get_option("invoice_admin_email"); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class=""><?php _e('Phone') ?></label>
                                                    <div>
                                                        <input name="invoice_admin_phone" type="text" class="form-control" value="<?php echo get_option("invoice_admin_phone"); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class=""><?php _e('Address') ?></label>
                                            <div>
                                                <input name="invoice_admin_address" type="text" class="form-control" value="<?php echo get_option("invoice_admin_address"); ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class=""><?php _e('City') ?></label>
                                                    <div>
                                                        <input name="invoice_admin_city" type="text" class="form-control" value="<?php echo get_option("invoice_admin_city"); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class=""><?php _e('State/Province') ?></label>
                                                    <div>
                                                        <input name="invoice_admin_state" type="text" class="form-control" value="<?php echo get_option("invoice_admin_state"); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class=""><?php _e('ZIP Code') ?></label>
                                                    <div>
                                                        <input name="invoice_admin_zipcode" type="text" class="form-control" value="<?php echo get_option("invoice_admin_zipcode"); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class=""><?php _e('Country') ?></label>
                                            <div>
                                                <select class="form-control" name="invoice_admin_country">
                                                    <?php
                                                    $country = get_country_list();
                                                    foreach ($country as $value){
                                                        echo '<option value="'.$value['code'].'" '. (($value['code'] == get_option('invoice_admin_country'))? 'selected':'') .'>'.$value['asciiname'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class=""><?php _e('Tax Type') ?></label>
                                                    <div>
                                                        <input name="invoice_admin_tax_type" type="text" class="form-control" value="<?php echo get_option("invoice_admin_tax_type"); ?>" placeholder="Ex: VAT">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class=""><?php _e('Tax ID') ?></label>
                                                    <div>
                                                        <input name="invoice_admin_tax_id" type="text" class="form-control" value="<?php echo get_option("invoice_admin_tax_id"); ?>">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="billing_details" value="1">
                                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="quick_social_login_setting">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('Social Login Setting') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="quick-accordion quick-payment-sortable ui-sortable" id="accordion">
                                    <!-- Favebook Login -->
                                    <div class="card quick-card">
                                        <div class="card-header">
                                            <h5 class="mb-0 d-flex align-items-center">
                                                <button class="btn btn-link pl-0 ml-1" data-toggle="collapse" data-target="#facebook" aria-expanded="false" aria-controls="facebook" type="button"><?php _e('Facebook Login') ?>
                                                </button>
                                            </h5>
                                        </div>
                                        <div class="collapse" id="facebook" aria-labelledby="facebook" data-parent="#accordion">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <?php quick_switch(__('Login with Facebook'), 'facebook_login', (get_option("facebook_login") == '1')); ?>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label><?php _e('Facebook callback url') ?>
                                                                <i class="icon-feather-help-circle" title="<?php _e('Use this redirect url in facebook app.') ?>" data-tippy-placement="top"></i>
                                                            </label>
                                                            <div>
                                                                <input type="text" class="form-control" disabled value="<?php echo $config['site_url']; ?>includes/social_login/facebook/index.php">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label><?php _e('Facebook app id') ?></label>
                                                            <div>
                                                                <input name="facebook_app_id" type="text" class="form-control" value="<?php echo get_option("facebook_app_id"); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label><?php _e('Facebook app secret') ?></label>
                                                            <div>
                                                                <input name="facebook_app_secret" type="text" class="form-control" value="<?php echo get_option("facebook_app_secret"); ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Google Login -->
                                    <div class="card quick-card">
                                        <div class="card-header">
                                            <h5 class="mb-0 d-flex align-items-center">
                                                <button class="btn btn-link pl-0 ml-1" data-toggle="collapse" data-target="#google" aria-expanded="false" aria-controls="google" type="button"><?php _e('Google Login') ?>
                                                </button>
                                            </h5>
                                        </div>
                                        <div class="collapse" id="google" aria-labelledby="google" data-parent="#accordion">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <?php quick_switch(__('Login with Google'), 'google_login', (get_option("google_login") == '1')); ?>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label><?php _e('Google callback url') ?></label>
                                                            <div>
                                                                <input type="text" class="form-control" disabled value="<?php echo $config['site_url']; ?>includes/social_login/google/index.php">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label><?php _e('Google app id') ?></label>
                                                            <div>
                                                                <input name="google_app_id" type="text" class="form-control" value="<?php echo get_option("google_app_id"); ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label><?php _e('Google app secret') ?></label>
                                                            <div>
                                                                <input name="google_app_secret" type="text" class="form-control" value="<?php echo get_option("google_app_secret"); ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="social_login_setting" value="1">
                                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="quick_recaptcha">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('Google reCAPTCHA') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <h5 class="quick-bold"><?php _e('Instructions') ?></h5>
                                    <p><?php _e('To find your Site Key and Secret Key, follow the below steps:') ?></p>
                                    <ol>
                                        <li><?php _e('Go to the <a href="https://www.google.com/recaptcha/admin/create" target="_blank">Google reCAPTCHA</a> and register a new site.') ?></li>
                                        <li><?php _e("Enter label and select <strong>reCAPTCHA v2</strong> -> <strong>\"I'm not a robot\" Checkbox</strong> in <strong>reCAPTCHA type</strong> field.") ?></li>
                                        <li><?php _e('Enter your domain url.') ?></li>
                                        <li><?php _e('Accept Terms of Service and click on the <strong>Submit</strong> button.') ?></li>
                                        <li><?php _e('Look for the <strong>Site Key</strong> and <strong>Secret Key</strong>. Use them in the form below on this page.') ?></li>
                                        <li><?php _e('Enable Google reCAPTCHA and click on the <strong>Save</strong> button.') ?></li>
                                    </ol>
                                </div>
                                <?php quick_switch(__('Google reCAPTCHA'), 'recaptcha_mode', (get_option("recaptcha_mode") == '1')); ?>

                                <div class="form-group">
                                    <label><?php _e('Public Key') ?></label>
                                    <div>
                                        <input name="recaptcha_public_key" type="text" class="form-control" value="<?php echo get_option("recaptcha_public_key"); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php _e('Private Key') ?></label>
                                    <div>
                                        <input name="recaptcha_private_key" type="text" class="form-control" value="<?php echo get_option("recaptcha_private_key"); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="recaptcha_setting" value="1">
                                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="quick_blog">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('Blog Setting') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Blog'), 'blog_enable', (get_option("blog_enable") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Show Blog On Home Page'), 'show_blog_home', (get_option("show_blog_home") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Blog Commenting'), 'blog_comment_enable', (get_option("blog_comment_enable") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php quick_switch(__('Blog Banner Image'), 'blog_banner', (get_option("blog_banner") == '1')); ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php _e('Comment Approval') ?></label>
                                            <div>
                                                <select name="blog_comment_approval" id="blog_comment_approval" class="form-control">
                                                    <option <?php if(get_option("blog_comment_approval") == '1'){ echo "selected"; } ?> value="1">Disable Auto Approve Comments</option>
                                                    <option <?php if(get_option("blog_comment_approval") == '2'){ echo "selected"; } ?> value="2">Auto Approve Login Users Comments</option>
                                                    <option <?php if(get_option("blog_comment_approval") == '3'){ echo "selected"; } ?> value="3">Auto Approve All Comments</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>
                                                <?php _e('Who Can Comment') ?>
                                                <i class="icon-feather-help-circle" title="<?php _e('Non-login users have to enter their name and email address.') ?>" data-tippy-placement="top"></i>
                                            </label>
                                            <div>
                                                <select name="blog_comment_user" id="blog_comment_user" class="form-control">
                                                    <option <?php if(get_option("blog_comment_user") == '1'){ echo "selected"; } ?> value="1">Everyone</option>
                                                    <option <?php if(get_option("blog_comment_user") == '0'){ echo "selected"; } ?> value="0">Only Login Users</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="blog_setting" value="1">
                                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="quick_testimonials">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('Testimonials Setting') ?></h5>
                            </div>
                            <div class="card-body">
                                <?php quick_switch(__('Testimonials'), 'testimonials_enable', (get_option("testimonials_enable") == '1')); ?>
                                <?php quick_switch(__('Show On Blog Page'), 'show_testimonials_blog', (get_option("show_testimonials_blog") == '1')); ?>
                                <?php quick_switch(__('Show On Home Page'), 'show_testimonials_home', (get_option("show_testimonials_home") == '1')); ?>

                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="testimonials_setting" value="1">
                                <button name="submit" type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="quick_purchase_code">
                    <form method="post" class="ajax_submit_form" data-action="SaveSettings" data-ajax-sidepanel="true">
                        <div class="quick-card card">
                            <div class="card-header">
                                <h5><?php _e('Purchase Code') ?></h5>
                            </div>
                            <div class="card-body">
                                <?php if (isset($config['purchase_key']) && $config['purchase_key'] != "") { ?>
                                    <div class="alert alert-info"><?php _e('Your purchase code is already verified.') ?></div>
                                <?php } ?>
                                <div class="form-group">
                                    <label for="quick_purchase_code"><?php _e('Purchase Code') ?></label>
                                    <p>
                                        <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"
                                           target="_blank"><?php _e('Do not know your purchase code? Find here.') ?></a>
                                    </p>
                                    <input id="quick_purchase_code" class="form-control" type="text"
                                           name="purchase_key">
                                </div>
                                <div class="form-group">
                                    <label for="buyer_email"><?php _e('Email') ?></label>
                                    <input id="buyer_email" class="form-control" type="text" name="buyer_email">
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" name="valid_purchase_setting" value="1">
                                <button type="submit" class="btn btn-primary"><?php _e('Save') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
    <script id="quick-sidebar-menu-js-extra">
        var QuickMenu = {"page":"settings"};
        var BookmeScript = {"success":"Success","error":"Error"};
    </script>
    <?php ob_start() ?>
    <link rel="stylesheet" href="../assets/css/datatables.css" />
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <?php
$footer_content = ob_get_clean();

include '../footer.php';