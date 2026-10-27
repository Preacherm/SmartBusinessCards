<?php
overall_header();
global $config;
?>
    <div class="hero-section-vCard">
        <div class="flex-container w-container">
            <div>
                <h1 class="hero-title"><?php _e("It all starts with a digital business card."); ?></h1>
                <p class="sub-text"><?php _e("Quick vCard is a digital business card maker SAAS script, an easy way to share your contacts and grow your professional relationships."); ?></p>
                <a href="<?php _esc($config['try_demo_link']);?>" target="_blank" class="button tDemo-btn"><?php _e("Try Demo"); ?></a>
            </div>
            <img src="<?php _esc(TEMPLATE_URL);?>/images/<?php _esc($banner_image);?>"
                 alt="<?php _e("It all starts with a digital business card."); ?>" class="hero-media">
        </div>
    </div>
    <!-- Content
    ================================================== -->
    <!-- Section How it Work Start-->
    <div class="section gray padding-top-65 padding-bottom-65">
        <div class="container">
            <div class="row">

                <div class="col-xl-12">
                    <!-- Section Headline -->
                    <div class="section-headline centered margin-top-0 margin-bottom-5">
                        <h3><?php _e("How It Works?"); ?></h3>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4">
                    <!-- Icon Box -->
                    <div class="icon-box with-line">
                        <!-- Icon -->
                        <div class="icon-box-circle">
                            <div class="icon-box-circle-inner">
                                <i class="la la-qrcode"></i>
                                <div class="icon-box-check"><i class="icon-material-outline-check"></i></div>
                            </div>
                        </div>
                        <h3><?php _e("Create Digital Business Card"); ?></h3>
                        <p><?php _e("Easily create QR code for your visiting card and make a great first impression. Fill your profile, it is simple!"); ?></p>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4">
                    <!-- Icon Box -->
                    <div class="icon-box with-line">
                        <!-- Icon -->
                        <div class="icon-box-circle">
                            <div class="icon-box-circle-inner">
                                <i class="la la-share-alt"></i>
                                <div class="icon-box-check"><i class="icon-material-outline-check"></i></div>
                            </div>
                        </div>
                        <h3><?php _e("Share your card with anyone"); ?></h3>
                        <p><?php _e("Easily share your digital business card with anyone using a QR code or send it through email, a link, and more. you can download QR code and print on anything like flyers, newsletters, or a billboard."); ?></p>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4">
                    <!-- Icon Box -->
                    <div class="icon-box">
                        <!-- Icon -->
                        <div class="icon-box-circle">
                            <div class="icon-box-circle-inner">
                                <i class="la la-trophy"></i>
                                <div class="icon-box-check"><i class="icon-material-outline-check"></i></div>
                            </div>
                        </div>
                        <h3><?php _e("Get More Customers"); ?></h3>
                        <p><?php _e("Your customers will find a way to reach you. All they need to do is scan QR code and choose a better channel to reach you."); ?></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Section How it Work End-->
    <!-- Section Feature Start-->
    <div class="section padding-top-65 padding-bottom-65">
        <div class="flex-container w-container margin-bottom-20">
            <div class="feature-image-mask"><img src="<?php _esc(TEMPLATE_URL);?>/images/feature-1.png" alt="<?php _e("Contactless digital business cards"); ?>" class="feature-image"></div>
            <div class="feature-text"><h2><strong><?php _e("Contactless digital business cards"); ?></strong></h2>
                <p class="sub-text"><?php _e("Quick vCard helps easily overcome any new sanitary friction with an ideal contactless experience and grow your professional relationships with its viral features. No physical contact is required to send or receive a virtual business card. You can create as many business cards as you’d like, with different information on each card. Make a card for your work contacts, one for clients or customers, and one for your friends."); ?></p>
            </div>
        </div>
        <div class="flex-container w-container margin-bottom-0">
            <div class="feature-text"><h2><strong><?php _e("Share your card with anyone"); ?></strong></h2>
                <p class="sub-text"><?php _e("Easily share your digital business card with anyone using a QR code or send it through email, a link, and more. you can download QR code and print on anything like flyers, newsletters, or a billboard."); ?></p>
            </div>
            <div class="feature-image-mask"><img src="<?php _esc(TEMPLATE_URL);?>/images/feature-2.png" alt="<?php _e("Share your card with anyone"); ?>" class="feature-image"></div>
        </div>
    </div>
    <!-- Section Feature End-->

<?php if($config['show_membershipplan_home']){ ?>
<!-- Membership Plans -->
<div class="section gray padding-top-60 padding-bottom-75">
    <div class="container">
        <div class="row">

            <div class="col-xl-12">
                <!-- Section Headline -->
                <div class="section-headline centered margin-top-0 margin-bottom-75">
                    <h3><?php _e("Membership Plan") ?></h3>
                </div>
            </div>

            <div class="col-xl-12">
                <form name="form1" method="post" action="<?php url("MEMBERSHIP") ?>">
                    <div class="billing-cycle-radios margin-bottom-70">
                        <?php if($total_monthly){ ?>
                        <div class="radio billed-monthly-radio">
                            <input id="radio-monthly" name="billed-type" type="radio" value="monthly" checked="">
                            <label for="radio-monthly"><span class="radio-label"></span> <?php _e("Monthly") ?></label>
                        </div>
                        <?php
                        }
                        if($total_annual){
                        ?>
                        <div class="radio billed-yearly-radio">
                            <input id="radio-yearly" name="billed-type" type="radio" value="yearly">
                            <label for="radio-yearly"><span class="radio-label"></span> <?php _e("Yearly") ?></label>
                        </div>
                        <?php
                        }
                        if($total_lifetime){
                        ?>
                        <div class="radio billed-lifetime-radio">
                            <input id="radio-lifetime" name="billed-type" type="radio" value="lifetime">
                            <label for="radio-lifetime"><span class="radio-label"></span> <?php _e("Lifetime") ?></label>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- Pricing Plans Container -->
                    <div class="pricing-plans-container">
                        <?php
                        foreach($sub_types as $plan){
                        ?>
                            <!-- Plan -->
                            <div class='pricing-plan <?php if(isset($plan['recommended']) && $plan['recommended']=="yes"){ echo 'recommended';} ?>'>

                                <?php
                                if(isset($plan['recommended']) && $plan['recommended']=="yes"){
                                    echo '<div class="recommended-badge">'.__("Recommended").'</div> ';
                                }
                                ?>
                                <h3><?php _esc($plan['title'])?></h3>
                                <?php
                                if($plan['id']=="free" || $plan['id']=="trial"){
                                    ?>
                                    <div class="pricing-plan-label"><strong>
                                            <?php
                                            if($plan['id']=="free")
                                                _e("Free");
                                            else
                                                _e("Trial");
                                            ?>
                                        </strong></div>

                                    <?php
                                }
                                else{
                                    if($total_monthly != 0)
                                        echo '<div class="pricing-plan-label billed-monthly-label"><strong>'._esc($plan['monthly_price'],false).'</strong>/ '.__("Monthly").'</div>';
                                    if($total_annual != 0)
                                        echo '<div class="pricing-plan-label billed-yearly-label"><strong>'._esc($plan['annual_price'],false).'</strong>/ '.__("Yearly").'</div>';
                                    if($total_lifetime != 0)
                                        echo '<div class="pricing-plan-label billed-lifetime-label"><strong>'._esc($plan['lifetime_price'],false).'</strong>/ '.__("Lifetime").'</div>';
                                }
                                ?>

                                <div class="pricing-plan-features">
                                    <strong><?php _e("Features of") ?> <?php _esc($plan['title'])?></strong>
                                    <ul>
                                        <li><strong><?php _esc($plan['field_limit'])?></strong> <?php _e("Additional fields on card") ?></li>
                                        <li><strong><?php _esc($plan['scan_limit'])?></strong> <?php _e("Scans Per Month") ?></li>
                                        <li>
                                            <?php
                                            if($plan['hide_branding']){
                                                echo '<span class="icon-text yes"><i class="icon-feather-check-circle margin-right-2"></i></span> ';
                                            }else{
                                                echo '<span class="icon-text no"><i class="icon-feather-x-circle margin-right-2"></i></span> ';
                                            }
                                            _e("Hide QuickVCard Branding");
                                            ?>
                                        </li>
                                        <?php _esc($plan['custom_settings'])?>
                                    </ul>
                                </div>
                                <?php
                                if($plan['Selected'] == 0){
                                    echo '<button type="submit" class="button full-width margin-top-20 ripple-effect" name="upgrade" value="'._esc($plan['id'],false).'">'.__("Upgrade").'</button>';
                                }
                                if($plan['Selected'] == 1){
                                    echo '<a href="javascript:void(0);" class="button full-width margin-top-20 ripple-effect">'.__("Current Plan").'</a>';
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Membership Plans / End-->
<?php } ?>

<!-- Testimonials -->
<?php if($config['testimonials_enable'] && $config['show_testimonials_home']){ ?>
<div class="section padding-top-65 padding-bottom-55">

    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <!-- Section Headline -->
                <div class="section-headline centered margin-top-0 margin-bottom-5">
                    <h3><?php _e("Testimonials") ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Carousel -->
    <div class="fullwidth-carousel-container margin-top-20">
        <div class="testimonial-carousel testimonials">

            <!-- Item -->
            <?php
            foreach($testimonials as $testimonial){
            ?>
            <div class="fw-carousel-review">
                <div class="testimonial-box">
                    <div class="testimonial-avatar">
                        <img src="<?php _esc($config['site_url']);?>storage/testimonials/<?php _esc($testimonial['image']) ?>" alt="<?php _esc($testimonial['name']) ?>">
                    </div>
                    <div class="testimonial-author">
                        <h4><?php _esc($testimonial['name']) ?></h4>
                        <span><?php _esc($testimonial['designation']) ?></span>
                    </div>
                    <div class="testimonial"><?php _esc($testimonial['content']) ?></div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <!-- Categories Carousel / End -->

</div>
<?php } ?>
<!-- Testimonials / End -->

<!-- Recent Blog Posts -->
<?php if($config['blog_enable'] && $config['show_blog_home']){ ?>
<div class="section padding-top-65 padding-bottom-50">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">

                <!-- Section Headline -->
                <div class="section-headline margin-top-0 margin-bottom-45">
                    <h3><?php _e("Recent Blog") ?></h3>
                    <a href="<?php url("BLOG") ?>" class="headline-link"><?php _e('View Blog')?></a>
                </div>

                <div class="row">
                    <!-- Blog Post Item -->
                    <?php
                    foreach($recent_blog as $blog){
                        ?>
                    <div class="col-xl-4">
                        <a href="<?php _esc($blog['link']) ?>" class="blog-compact-item-container">
                            <div class="blog-compact-item">
                                <img src="<?php _esc($config['site_url']);?>storage/blog/<?php _esc($blog['image']) ?>"
                                     alt="<?php _esc($blog['title']) ?>">
                                <span class="blog-item-tag"><?php _esc($blog['author']) ?></span>
                                <div class="blog-compact-item-content">
                                    <ul class="blog-post-tags">
                                        <li><?php _esc($blog['created_at']) ?></li>
                                    </ul>
                                    <h3><?php _esc($blog['title']) ?></h3>
                                    <p><?php _esc($blog['description']) ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                    <!-- Blog post Item / End -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<!-- Recent Blog Posts / End -->

<?php if($config['show_partner_logo_home']){ ?>
<div class="section gray border-top padding-top-45 padding-bottom-45">
    <!-- Logo Carousel -->
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <!-- Carousel -->
                <div class="col-md-12">
                    <div class="logo-carousel">
                        <?php
                        $dir = ROOTPATH.'/storage/partner/';
                        $i = 0;
                        foreach (glob($dir . '*') as $path) {
                            ?>
                            <div class="carousel-item">
                                <img src="<?php _esc($config['site_url']);?>storage/partner/<?php _esc(basename($path))?>">
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- Carousel / End -->
            </div>
        </div>
    </div>
</div>
<?php } ?>
    <script>
        var transparent_header = "<?php _esc($config['transparent_header'])?>";
        $(document).ready(function () {
            if(transparent_header != '0'){
                $("#wrapper").addClass('wrapper-with-transparent-header');
                $("#header-container").addClass('transparent-header');
            }
        });
    </script>
<?php
overall_footer();
?>