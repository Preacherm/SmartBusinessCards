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
                <h3><?php _e("Manage Vcard") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Add Vcards") ?></li>
                    </ul>
                </nav>
            </div>
            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-md-6">
                    <form id="vcard-details-form" name="vcard_form" method="post" action="#" enctype="multipart/form-data">
                        <div class="dashboard-box margin-top-0 margin-bottom-30">
                            <!-- Headline -->
                            <div class="headline">
                                <h3><i class="icon-feather-layers"></i><?php _e('Your Card'); ?></h3>
                                <?php if($vcard_link != "#"){ ?>
                                <a href="<?php _esc($vcard_link); ?>" title="<?php _e('Live Preview'); ?>" data-tippy-placement="top" class="margin-left-auto live-preview-button"><i class="icon-feather-eye"></i></a>
                                <?php } ?>
                            </div>
                            <?php if($card_id != ""){ ?>
                                <input type="hidden" name="id" value="<?php _esc($card_id)?>">
                            <?php } ?>
                            <div class="content with-padding padding-bottom-0" id="card-details-box">
                                <div class="submit-field">
                                    <div class="account-type row template-chooser">
                                        <?php
                                        foreach($vcard_templates as $vcards){
                                            ?>
                                            <div class="col-md-6 col-6 margin-right-0">
                                                <input type="radio" name="vcard_template" value="<?php _esc($vcards['folder']) ?>" id="<?php _esc($vcards['folder']) ?>" class="account-type-radio"
                                                       <?php
                                                       if($vcard_template == $vcards['folder'])
                                                           echo "checked";
                                                       ?>
                                                >
                                                <label for="<?php _esc($vcards['folder']) ?>" class="ripple-effect-dark">

                                                    <img class="margin-bottom-5" src="<?php _esc($config['site_url']);?>/vcard-templates/<?php _esc($vcards['folder']) ?>/screenshot.png">
                                                    <strong><?php _esc($vcards['name']) ?></strong>
                                                </label>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center submit-field">
                                    <div class="flex-grow-1">
                                        <h5 class="margin-bottom-0"><?php _e('Card color'); ?></h5></div>
                                    <div>
                                        <div class="card-color-wrapper">
                                            <button class="bm-color-picker"></button>
                                            <input type="hidden" class="color-input" name="color" value="<?php _esc($color); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <h5><?php _e('Banner'); ?></h5>
                                            <div class="uploadButton">
                                                <input class="uploadButton-input" type="file" accept="image/*"
                                                       id="cover_image"
                                                       name="cover_image" onchange="readImageURL(this, 'vcard-banner');"/>
                                                <label class="uploadButton-button ripple-effect"
                                                       for="cover_image"><?php _e('Upload image'); ?></label>
                                            </div>
                                        </div>
                                        <img alt="" id="vcard-banner" src="<?php _esc($config['site_url']);?>storage/cards/cover/<?php _esc($cover_image); ?>"
                                             style="max-width: 50%; height: 90px">
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <h5><?php _e('Logo'); ?></h5>
                                            <div class="uploadButton">
                                                <input class="uploadButton-input" type="file" accept="image/*"
                                                       id="main_image"
                                                       name="main_image" onchange="readImageURL(this, 'vcard-logo');"/>
                                                <label class="uploadButton-button ripple-effect"
                                                       for="main_image"><?php _e('Upload image'); ?></label>
                                            </div>
                                        </div>
                                        <img alt="" id="vcard-logo" src="<?php _esc($config['site_url']);?>storage/cards/logo/<?php _esc($main_image); ?>"
                                             style="max-width: 50%; height: 90px">
                                    </div>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e('Slug'); ?></h5>
                                    <input id="store-slug" class="with-border" name="slug" type="text" value="<?php _esc($slug); ?>" onblur="checkAvailabilityStoreSlug()">
                                    <div id="slug-availability-status"></div>
                                    <small><?php _e('Slug Hint'); ?></small>
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e('Title'); ?></h5>
                                    <input id="title" class="with-border" name="title" type="text" value="<?php _esc($title); ?>">
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e('Subtitle'); ?></h5>
                                    <input id="sub-title" class="with-border" name="sub_title" type="text" value="<?php _esc($sub_title); ?>">
                                </div>
                                <div class="submit-field">
                                    <h5><?php _e('Description'); ?></h5>
                                    <textarea id="description" class="with-border" name="description"><?php _esc($description); ?></textarea>
                                </div>
                                <div id="card-details-container"></div>
                            </div>
                            <div class="headline dashboard-box-footer">
                                <div>
                                    <small class="form-error"></small>
                                    <button type="submit" name="submit" class="button ripple-effect full-width"><?php _e('Save'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-box margin-top-0">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-feather-info"></i><?php _e('Add new information'); ?></h3>
                        </div>
                        <div class="content with-padding">
                            <div class="vcard-info-items-group">
                                <button class="vcard-info-items" data-type="phone" data-field-type="text" data-placeholder="<?php _e('Phone'); ?>" data-value="">
                                    <i class="fa fa-phone"></i><span><?php _e('Phone'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="email" data-field-type="email" data-placeholder="<?php _e('Email'); ?>" data-value="">
                                    <i class="fa fa-envelope"></i><span><?php _e('Email'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="address" data-field-type="text" data-placeholder="<?php _e('Address'); ?>" data-value="">
                                    <i class="fa fa-map-marker"></i><span><?php _e('Address'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="website" data-field-type="url" data-placeholder="<?php _e('Website'); ?>" data-value="">
                                    <i class="fa fa-link"></i><span><?php _e('Website'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="text" data-field-type="textarea" data-placeholder="<?php _e('Text'); ?>" data-value="">
                                    <i class="fa fa-align-left"></i><span><?php _e('Text'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="facebook" data-field-type="text" data-placeholder="<?php _e('Facebook'); ?>" data-value="">
                                    <i class="fa fa-facebook"></i><span><?php _e('Facebook'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="twitter" data-field-type="text" data-placeholder="<?php _e('Twitter'); ?>" data-value="">
                                    <i class="fa fa-twitter"></i><span><?php _e('Twitter'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="instagram" data-field-type="text" data-placeholder="<?php _e('Instagram'); ?>" data-value="">
                                    <i class="fa fa-instagram"></i><span><?php _e('Instagram'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="whatsapp" data-field-type="text" data-placeholder="+19876543210" data-value="">
                                    <i class="fa fa-whatsapp"></i><span><?php _e('WhatsApp'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="telegram" data-field-type="text" data-placeholder="+19876543210" data-value="">
                                    <i class="fa fa-send"></i><span><?php _e('Telegram'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="skype" data-field-type="text" data-placeholder="<?php _e('Skype'); ?>" data-value="">
                                    <i class="fa fa-skype"></i><span><?php _e('Skype'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="wechat" data-field-type="text" data-placeholder="+19876543210" data-value="">
                                    <i class="fa fa-wechat"></i><span><?php _e('WeChat'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="signal" data-field-type="text" data-placeholder="+19876543210" data-value="">
                                    <svg aria-hidden="true" focusable="false" viewBox="0 0 27 27" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="fill: currentcolor; height: 1em; overflow: visible; width: 1em;">
                                        <path d="M10.249 0.411007L10.5446 1.63349C9.36239 1.92857 8.22244 2.39227 7.16693 3.02459L6.51251 1.94965C7.67357 1.2541 8.94019 0.727166 10.249 0.411007ZM16.751 0.411007L16.4554 1.63349C17.6376 1.92857 18.7776 2.39227 19.8331 3.02459L20.4875 1.94965C19.3264 1.2541 18.0598 0.727166 16.751 0.411007ZM1.93159 6.52342C1.23495 7.68267 0.707193 8.94731 0.390539 10.2541L1.61493 10.5492C1.91048 9.36885 2.3749 8.23068 3.00821 7.17681L1.93159 6.52342ZM1.25606 13.5C1.25606 12.8888 1.29828 12.2775 1.40383 11.6663L0.158327 11.4766C-0.0527756 12.8255 -0.0527756 14.1745 0.158327 15.5234L1.40383 15.3337C1.29828 14.7225 1.25606 14.1112 1.25606 13.5ZM20.4875 25.0504L19.8331 23.9754C18.7776 24.6077 17.6376 25.0925 16.4343 25.3876L16.7299 26.6101C18.0598 26.2728 19.3264 25.7459 20.4875 25.0504ZM25.7439 13.5C25.7439 14.1112 25.7017 14.7225 25.5962 15.3337L26.8417 15.5234C27.0528 14.1745 27.0528 12.8255 26.8417 11.4766L25.5962 11.6663C25.7017 12.2775 25.7439 12.8888 25.7439 13.5ZM26.6095 16.7459L25.3851 16.4508C25.0895 17.6522 24.6251 18.7904 23.9918 19.8443L25.0684 20.4977C25.7651 19.3173 26.2928 18.0527 26.6095 16.7459ZM15.3366 25.5984C14.1122 25.7881 12.8878 25.7881 11.6634 25.5984L11.4734 26.8419C12.8245 27.0527 14.1755 27.0527 15.5266 26.8419L15.3366 25.5984ZM23.3585 20.7506C22.6196 21.7412 21.7541 22.6054 20.7619 23.3431L21.5219 24.3548C22.6196 23.5539 23.5696 22.5843 24.3929 21.5094L23.3585 20.7506ZM20.7619 3.65691C21.7541 4.39461 22.6196 5.25878 23.3585 6.24941L24.3718 5.49063C23.5696 4.39461 22.5985 3.44614 21.5219 2.6452L20.7619 3.65691ZM3.64152 6.24941C4.38038 5.25878 5.2459 4.39461 6.23808 3.65691L5.47811 2.6452C4.38038 3.44614 3.43041 4.41569 2.62823 5.49063L3.64152 6.24941ZM25.0684 6.52342L23.9918 7.17681C24.6251 8.23068 25.1106 9.36885 25.4062 10.5703L26.6306 10.2752C26.2928 8.94731 25.7651 7.68267 25.0684 6.52342ZM11.6634 1.40164C12.8878 1.21194 14.1122 1.21194 15.3366 1.40164L15.5266 0.15808C14.1755 -0.0526932 12.8245 -0.0526932 11.4734 0.15808L11.6634 1.40164ZM4.29593 24.692L1.67826 25.3033L2.29046 22.6897L1.04496 22.3946L0.43276 25.0082C0.263878 25.6827 0.686083 26.3782 1.38272 26.5258C1.57271 26.5679 1.76271 26.5679 1.9527 26.5258L4.57037 25.9356L4.29593 24.692ZM1.31939 21.2775L2.54378 21.5726L2.96599 19.76C2.35379 18.7272 1.88937 17.6101 1.59382 16.4508L0.369429 16.7459C0.643862 17.863 1.06607 18.9379 1.61493 19.9496L1.31939 21.2775ZM7.23026 24.0176L5.41478 24.4391L5.71032 25.6616L7.04027 25.3454C8.05356 25.8934 9.13018 26.315 10.249 26.589L10.5446 25.3665C9.3835 25.0925 8.26466 24.6288 7.23026 24.0176ZM13.5 2.53981C7.44136 2.53981 2.52267 7.45082 2.52267 13.5C2.52267 15.5656 3.11376 17.589 4.21149 19.3173L3.15598 23.8279L7.65246 22.774C12.7823 25.9988 19.5586 24.4602 22.7885 19.3384C26.0184 14.2166 24.4773 7.45082 19.3475 4.226C17.5954 3.12998 15.5688 2.53981 13.5 2.53981Z"></path></svg><span>Signal<?php _e('WhatsApp'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="snapchat" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <i class="fa fa-snapchat-ghost"></i><span><?php _e('Snapchat'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="linkedin" data-field-type="url" data-placeholder="<?php _e('Profile url'); ?>" data-value="">
                                    <i class="fa fa-linkedin"></i><span><?php _e('LinkedIn'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="pinterest" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <i class="fa fa-pinterest"></i><span><?php _e('Pinterest'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="soundcloud" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <i class="fa fa-soundcloud"></i><span><?php _e('Soundcloud'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="vimeo" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <i class="fa fa-vimeo"></i><span><?php _e('Vimeo'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="dribbble" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <i class="fa fa-dribbble"></i><span><?php _e('Dribbble'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="behance" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <i class="fa fa-behance"></i><span><?php _e('Behance'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="flickr" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <i class="fa fa-flickr"></i><span><?php _e('Flickr'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="youtube" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <i class="fa fa-youtube-play"></i><span><?php _e('YouTube'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="tiktok" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="fill: currentcolor; height: 1em; overflow: visible; width: 1em;">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.2585 3C15.5561 5.55428 16.9848 7.07713 19.4688 7.23914V10.112C18.0292 10.2524 16.7683 9.78262 15.3018 8.89699V14.2702C15.3018 21.096 7.84449 23.2291 4.84643 18.3365C2.91988 15.1882 4.09962 9.66382 10.2797 9.44241V12.4719C9.80893 12.5475 9.30564 12.6663 8.84565 12.8229C7.47109 13.2873 6.69181 14.1568 6.90827 15.6904C7.32497 18.6281 12.7258 19.4975 12.2766 13.7571V3.0054H15.2585V3Z"></path></svg><span><?php _e('TikTok'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="discord" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <svg aria-hidden="true" focusable="false" viewBox="0 0 30 21" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="fill: currentcolor; height: 1em; overflow: visible; width: 1em;">
                                        <path d="M25.9091 1.625C25.9091 1.625 22.7727 -0.7375 19.0909 -1L18.75 -0.34375C22.0909 0.44375 23.5909 1.55938 25.2273 2.9375C22.5 1.55938 19.7727 0.3125 15 0.3125C10.2273 0.3125 7.5 1.55938 4.77273 2.9375C6.34091 1.55938 8.18182 0.3125 11.25 -0.34375L10.9091 -1C7.02273 -0.671875 4.09091 1.625 4.09091 1.625C4.09091 1.625 0.613636 6.48125 0 16.0625C3.54545 20 8.86364 20 8.86364 20L9.95455 18.5562C8.04545 17.9 5.93182 16.7844 4.09091 14.75C6.27273 16.3906 9.61364 18.0312 15 18.0312C20.3864 18.0312 23.7273 16.3906 25.9091 14.75C24.0682 16.7844 21.8864 17.9 20.0455 18.5562L21.1364 20C21.1364 20 26.4545 20 30 16.0625C29.3864 6.48125 25.9091 1.625 25.9091 1.625ZM10.5682 13.4375C9.27273 13.4375 8.18182 12.2563 8.18182 10.8125C8.18182 9.36875 9.27273 8.1875 10.5682 8.1875C11.8636 8.1875 12.9545 9.36875 12.9545 10.8125C12.9545 12.2563 11.8636 13.4375 10.5682 13.4375ZM19.4318 13.4375C18.1364 13.4375 17.0455 12.2563 17.0455 10.8125C17.0455 9.36875 18.1364 8.1875 19.4318 8.1875C20.7273 8.1875 21.8182 9.36875 21.8182 10.8125C21.8182 12.2563 20.7273 13.4375 19.4318 13.4375Z"></path></svg><span><?php _e('Discord'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="twitch" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <i class="fa fa-twitch"></i><span><?php _e('Twitch'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="github" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <i class="fa fa-github"></i><span><?php _e('Github'); ?></span>
                                </button>
                                <button class="vcard-info-items" data-type="paypal" data-field-type="text" data-placeholder="<?php _e('Username'); ?>" data-value="">
                                    <i class="fa fa-paypal"></i><span><?php _e('PayPal'); ?></span>
                                </button>
                            </div>
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
<script>
    var LANG_LABEL = "<?php _e('Label') ?>",
        LANG_LIMIT_EXCEED_UPGRADE = "<?php _e('Limit exceed, please upgrade your membership.') ?>";

    var VCARD_DETAILS = <?php _esc($details) ?>,
        DETAILS_FIELD_LIMIT = <?php _esc($details_field_limit) ?>;
</script>
<link rel="stylesheet" href="<?php _esc(TEMPLATE_URL);?>/css/color-picker.min.css?ver=<?php _esc($config['version']);?>">
<script src="<?php _esc(TEMPLATE_URL);?>/js/color-picker.es5.min.js?ver=<?php _esc($config['version']);?>"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/jquery-ui.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/add-vcard.js?ver=<?php _esc($config['version']);?>"></script>
<script>
    function checkAvailabilityStoreSlug() {
        var $item = $("#store-slug").closest('.submit-field');
        var form_data = {
            action: 'checkStoreSlug',
            slug: $("#store-slug").val()
        };
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: form_data,
            dataType: 'html',
            beforeSend: function() {
                // Can Add a loader here
            },
            success:function(data){
                $("#slug-availability-status").html(data);
            },
            error:function ( jqXHR, textStatus ){
                console.log("error: " + textStatus);
            }
        });
    }
</script>
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH.'/overall_footer_dashboard.php';
?>




