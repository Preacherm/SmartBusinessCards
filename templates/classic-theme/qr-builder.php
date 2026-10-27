<?php
overall_header(__("Dashboard"));
?>
<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php
    include_once TEMPLATE_PATH.'/dashboard_sidebar.php';
    ?>
    <!-- Dashboard Content
    ================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner" >

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3><?php _e("Dashboard") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("QR Builder") ?></li>
                    </ul>
                </nav>
            </div>


            <!-- Row -->
            <div class="row">
                <div class="col-md-4">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-settings"></i> <?php _e('QR Code Generator'); ?></h3>
                        </div>
                        <div class="content with-padding">
                            <form method="post" action="#" enctype="multipart/form-data">
                                <div class="d-flex align-items-center submit-field">
                                    <div class="flex-grow-1">
                                        <h5 class="margin-bottom-0"><?php _e('Foreground Color'); ?></h5></div>
                                    <div>
                                        <div class="qr-fg-color-wrapper">
                                            <button class="bm-color-picker"></button>
                                            <input type="hidden" class="color-input" name="qr_fg_color" value="<?php _esc($qr_fg_color); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center submit-field">
                                    <div class="flex-grow-1">
                                        <h5 class="margin-bottom-0"><?php _e('Background Color'); ?></h5></div>
                                    <div>
                                        <div class="qr-bg-color-wrapper">
                                            <button class="bm-color-picker"></button>
                                            <input type="hidden" class="color-input" name="qr_bg_color" value="<?php _esc($qr_bg_color); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e('Padding'); ?></h5>
                                    <input class="range-slider-single" id="qr-padding" name="qr_padding" type="text" data-slider-min="0" data-slider-max="5" data-slider-step="1" data-slider-value="<?php _esc($qr_padding); ?>" value="<?php _esc($qr_padding); ?>">
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e('Corner Radius'); ?></h5>
                                    <input class="range-slider-single" id="qr-radius" name="qr_radius" type="text" data-slider-min="0" data-slider-max="50" data-slider-step="10" data-slider-value="<?php _esc($qr_radius); ?>" value="<?php _esc($qr_radius); ?>">
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e('Mode'); ?></h5>
                                    <select id="qr-mode" name="qr_mode" class="with-border selectpicker">
                                        <option value="0" <?php if($qr_mode=="0") echo "selected" ?>><?php _e('Normal'); ?></option>
                                        <option value="2" <?php if($qr_mode=="2") echo "selected" ?>><?php _e('Text'); ?></option>
                                        <option value="4" <?php if($qr_mode=="4") echo "selected" ?>><?php _e('Image'); ?></option>
                                    </select>
                                </div>
                                <div id="qr-mode-customization">
                                    <div id="qr-mode-label">
                                        <div class="submit-field">
                                            <h5><?php _e('Text'); ?></h5>
                                            <input id="qr-text" class="with-border" name="qr_text" type="text" value="<?php _esc($qr_text); ?>">
                                        </div>
                                        <div class="d-flex align-items-center submit-field">
                                            <div class="flex-grow-1">
                                                <h5 class="margin-bottom-0"><?php _e('Text Color'); ?></h5></div>
                                            <div>
                                                <div class="qr-text-color-wrapper">
                                                    <button class="bm-color-picker"></button>
                                                    <input type="hidden" class="color-input" name="qr_text_color"
                                                           value="<?php _esc($qr_text_color); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="qr-mode-image">
                                        <div class="submit-field">
                                            <h5><?php _e('Image'); ?></h5>
                                            <div class="uploadButton">
                                                <input class="uploadButton-input" type="file" accept="image/*" id="qr-image"
                                                       name="qr_image"/>
                                                <img id="img-buffer" src="<?php _esc($qr_image); ?>" class="d-none">
                                                <label class="uploadButton-button ripple-effect" for="qr-image"><?php _e('Upload Image'); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e('Size'); ?></h5>
                                        <input class="range-slider-single" name="qr_mode_size" id="qr-mode-size" type="text" data-slider-min="1"
                                               data-slider-max="15" data-slider-step="1" data-slider-value="<?php _esc($qr_mode_size); ?>" value="<?php _esc($qr_mode_size); ?>">
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e('Position X'); ?></h5>
                                        <input class="range-slider-single" name="qr_position_x" id="qr-position-x" type="text"
                                               data-slider-min="0" data-slider-max="100" data-slider-step="1"
                                               data-slider-value="<?php _esc($qr_position_x); ?>" value="<?php _esc($qr_position_x); ?>">
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e('Position Y'); ?></h5>
                                        <input class="range-slider-single" name="qr_position_y" id="qr-position-y" type="text"
                                               data-slider-min="0" data-slider-max="100" data-slider-step="1"
                                               data-slider-value="<?php _esc($qr_position_y); ?>" value="<?php _esc($qr_position_y); ?>">
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="<?php _esc($vcard_id); ?>"/>
                                <button name="submit" type="submit" class="button"><?php _e('Save Settings'); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 sticky-sidebar">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-grid"></i> <?php _e('QR Code'); ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div id="qr-code-wrapper" class="margin-bottom-20" data-url="<?php _esc($scan_url); ?>"></div>
                            <button class="button ripple-effect qr-code-downloader"><i class="icon-feather-download"></i> <?php _e('Download Image'); ?></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-feather-link"></i> <?php _e('Helpful Links'); ?></h3>
                        </div>
                        <div class="content with-padding">
                            <p><a href="<?php url("ADD_VCARD") ?>/<?php _esc($vcard_id)?>"><i class="icon-feather-edit"></i> <?php _e('Edit VCard'); ?></a></p>
                            <p><a href="#" class="qr-code-downloader"><i class="icon-feather-download"></i> <?php _e('Download QR Code'); ?></a></p>
                            <p><a href="<?php _esc($card_url); ?>" class="live-preview-button"><i class="icon-feather-eye"></i> <?php _e('Live Preview'); ?></a></p>
                            <p><a href="#" class="copy-link" data-link="<?php _esc($card_url); ?>"><i class="icon-feather-copy"></i> <?php _e('Copy Link'); ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row / End -->

            <!-- Footer -->
            <div class="dashboard-footer-spacer"></div>
            <div class="small-footer margin-top-15">
                <div class="footer-social-links">
                    <?php _esc($config['copyright_text']);?>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
</div>
<?php ob_start() ?>
    <link rel="stylesheet" href="<?php _esc(TEMPLATE_URL);?>/css/color-picker.min.css?ver=<?php _esc($config['version']);?>">
    <script src="<?php _esc(TEMPLATE_URL);?>/js/color-picker.es5.min.js?ver=<?php _esc($config['version']);?>"></script>
    <script src="<?php _esc(TEMPLATE_URL);?>/js/sticky-sidebar.js"></script>
    <script src="<?php _esc(TEMPLATE_URL);?>/js/jquery-qrcode.min.js?ver=<?php _esc($config['version']);?>"></script>
    <script src="<?php _esc(TEMPLATE_URL);?>/js/script.js?ver=<?php _esc($config['version']);?>"></script>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH.'/overall_footer_dashboard.php';
?>