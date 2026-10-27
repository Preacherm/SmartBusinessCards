<?php
$page_title = 'My Business Cards';
overall_header(_esc($page_title,false));
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
                    <h3><?php _esc($page_title) ?></h3>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                            <li><?php _esc($page_title) ?></li>
                        </ul>
                    </nav>
                </div>

                <!-- Row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard-box">
                            <div class="headline">
                                <h3><i class="icon-feather-layers"></i> <?php _esc($page_title); ?></h3>
                                <a href="<?php url("ADD_VCARD") ?>" class="button ripple-effect"><i class="icon-feather-plus"></i> <?php _e("Add Vcard") ?></a>
                            </div>
                            <div class="content with-padding">
                                <ul class="dashboard-box-list" id="js-table-list">
                                    <?php foreach($vcards as $vcard){ ?>
                                    <ul class="dashboard-box-list">
                                        <li class="ajax-item-listing" data-item-id="<?php _esc($vcard['id'])?>">
                                            <!-- Job Listing -->
                                            <div class="job-listing">
                                                <!-- Job Listing Details -->
                                                <div class="job-listing-details">
                                                    <!-- Logo -->
                                                    <a href="<?php _esc($vcard['link'])?>" class="job-listing-company-logo">
                                                        <img src="<?php _esc($config['site_url'])?>storage/cards/logo/<?php _esc($vcard['main_image'])?>" alt="<?php _esc($vcard['title'])?>">
                                                    </a>

                                                    <!-- Details -->
                                                    <div class="job-listing-description">
                                                        <h3 class="job-listing-title">
                                                            <a href="<?php _esc($vcard['link'])?>"><?php _esc($vcard['title'])?></a>
                                                        </h3>

                                                        <!-- Job Listing Footer -->
                                                        <div class="job-listing-footer">
                                                            <ul>
                                                                <li><i class="icon-feather-eye"></i> <?php _e("Scans") ?> <?php _esc($vcard['total_scans'])?></li>
                                                                <li><i class="icon-material-outline-date-range"></i> <?php _e("Created") ?> <?php _esc($vcard['created_at'])?></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Buttons -->
                                            <div class="buttons-to-right always-visible">
                                                <a href="<?php url("QRBUILDER") ?>/<?php _esc($vcard['id'])?>" target="_blank" class="button ripple-effect"><i class="icon-material-outline-dashboard"></i> <?php _e("QR Code") ?></a>
                                                <a href="<?php _esc($vcard['link'])?>" target="_blank" class="button gray ripple-effect ico" title="<?php _e("Public View") ?>" data-tippy-placement="top"><i class="icon-feather-eye"></i></a>
                                                <a href="<?php url("ADD_VCARD") ?>/<?php _esc($vcard['id'])?>" class="button gray ripple-effect ico" title="<?php _e("Edit") ?>" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                                                <a href="#" class="button gray ripple-effect ico item-ajax-button" data-alert-message="<?php _e("Are you sure?") ?>" data-ajax-action="deleteVCard" title="<?php _e("Delete") ?>" data-tippy-placement="top"><i class="icon-feather-trash-2"></i></a>
                                            </div>
                                        </li>
                                        <?php } ?>
                                        <?php if(!$total){ ?>
                                            <li class="ajax-item-listing">
                                                <!-- Project Listing -->
                                                <div class="job-listing width-adjustment">
                                                    <!-- Project Listing Details -->
                                                    <div class="job-listing-details">
                                                        <?php _e("No Vcards available.") ?>
                                                        <a href="<?php url("ADD_VCARD") ?>"><?php _e("Create vcard here") ?></a>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
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
<?php
$footer_content = ob_get_clean();
include_once TEMPLATE_PATH.'/overall_footer_dashboard.php';
?>