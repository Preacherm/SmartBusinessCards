<?php
$page_title = 'Digital Business Card Listing';
overall_header(_esc($page_title,false));
?>
    <div id="titlebar">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><?php _esc($page_title) ?></h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                            <li><?php _esc($page_title) ?></li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>
    <div class="container margin-bottom-50">
        <div class="col-xl-12">
            <form>
                <div class="input-with-icon">
                    <input class="with-border" type="text" placeholder="<?php _e("Search") ?>..." name="keyword"
                           value="<?php _esc($keyword)?>">
                    <i class="icon-feather-search"></i>
                </div>
            </form>
            <div class="companies-list">
                <?php
                if($total){
                    foreach($vcards as $vcard){
                        ?>
                        <a href="<?php _esc($vcard['link']) ?>" class="company">
                            <div class="company-inner-alignment">
                                <span class="company-logo"><img src="<?php _esc($config['site_url']);?>storage/cards/logo/<?php _esc($vcard['main_image']) ?>" alt=""></span>
                                <h4><?php _esc($vcard['title']) ?> (<?php _esc($vcard['sub_title']) ?>)</h4>
                            </div>
                        </a>
                        <?php
                    }
                }else{
                    echo '<p>'.__("No result found.").'</p>';
                }
                ?>
            </div>
            <!-- Pagination -->
            <div class="pagination-container margin-top-20">
                <nav class="pagination">
                    <ul>
                        <?php
                        if($total){
                            foreach($pages as $page) {
                                if ($page['current'] == 0){
                                    ?>
                                    <li><a href="<?php _esc($page['link'])?>"><?php _esc($page['title'])?></a></li>
                                <?php }else{
                                    ?>
                                    <li><a href="#" class="current-page"><?php _esc($page['title'])?></a></li>
                                <?php }
                            }
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
<?php
overall_footer();
?>