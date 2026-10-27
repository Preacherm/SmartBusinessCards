<?php
include '../datatable-json/includes.php';
?>
<div class="slidePanel-content">
    <header class="slidePanel-header">
        <div class="slidePanel-overlay-panel">
            <div class="slidePanel-heading">
                <h2><?php _e('Add Plan') ?></h2>
            </div>
            <div class="slidePanel-actions">
                <button id="post_sidePanel_data" class="btn-icon btn-primary" title="<?php _e('Save') ?>">
                    <i class="icon-feather-check"></i>
                </button>
                <button class="btn-icon slidePanel-close" title="<?php _e('Close') ?>">
                    <i class="icon-feather-x"></i>
                </button>
            </div>
        </div>
    </header>
    <div class="slidePanel-inner">
        <form method="post" id="sidePanel_form" data-ajax-action="addMembershipPlan">
            <div class="form-body">
                <?php quick_switch(__('Activate'), 'active', true); ?>
                <div class="form-group">
                    <label for="name"><?php _e('Plan Name') ?>*</label>
                    <input id="name" name="name" type="text" class="form-control">
                </div>
                <div class="form-group d-none">
                    <label for="badge"><?php _e('Plan Badge') ?></label>
                    <input id="badge" name="badge" type="text" class="form-control">
                    <span class="form-text text-muted"><?php _e('Paste Image Url, This badge will display in user profile after username.') ?></span>
                </div>
                <div class="form-group">
                    <label for="monthly_price"><?php _e('Monthly Price') ?>*</label>
                        <input name="monthly_price" type="number" class="form-control" id="monthly_price" value="">
                    <span class="form-text text-muted"><?php _e('Set 0 to disable it.') ?></span>
                </div>
                <div class="form-group">
                    <label for="annual_price"><?php _e('Annual Price') ?>*</label>
                    <input name="annual_price" type="number" class="form-control" id="annual_price" value="">
                    <span class="form-text text-muted"><?php _e('Set 0 to disable it.') ?></span>
                </div>
                <div class="form-group">
                    <label for="lifetime_price"><?php _e('Lifetime Price') ?>*</label>
                        <input name="lifetime_price" type="number" class="form-control" id="lifetime_price" value="">
                    <span class="form-text text-muted"><?php _e('Set 0 to disable it.') ?></span>
                </div>
                <?php quick_switch(__('Recommended'), 'recommended', true); ?>
                <h5 class="m-t-35"><?php _e('Plan Settings') ?></h5>
                <hr>
                <div class="form-group">
                    <label for="scan_limit"><?php _e('Scans Limit Per Month') ?>*</label>
                    <input name="scan_limit" type="number" class="form-control" id="scan_limit" value="100">
                    <span class="form-text text-muted"><?php _e('For unlimited, enter 999') ?></span>
                </div>
                <div class="form-group">
                    <label for="field_limit"><?php _e('Additional Field Limit') ?>*</label>
                    <input name="field_limit" type="number" class="form-control" id="field_limit" value="2">
                    <span class="form-text text-muted"><?php _e('For unlimited, enter 999') ?></span>
                </div>
                <?php quick_switch(__('Hide Branding'), 'hide_branding', true); ?>
                <?php
                $plan_custom = ORM::for_table($config['db']['pre'].'plan_options')
                    ->where('active', 1)
                    ->order_by_asc('position')
                    ->find_array();
                if(!empty($plan_custom)) {?>
                    <h5 class="m-t-35"><?php _e('Custom Settings') ?></h5>
                    <hr>
                    <?php
                    foreach ($plan_custom as $custom) {
                        if(!empty($custom['title']) && trim($custom['title']) != '') {
                            quick_switch(_esc($custom['title'], false), 'custom_'.$custom['id']);
                            ?>
                            <?php
                        }
                    }
                }
                ?>
                <h5 class="m-t-35"><?php _e('Taxes') ?></h5>
                <hr>
                <div class="form-group">
                    <label><?php _e('Select Taxes') ?></label>
                    <select class="form-control quick-select2" name="taxes[]" multiple>
                        <?php
                        $taxes = ORM::for_table($config['db']['pre'].'taxes')
                            ->find_many();
                        foreach ($taxes as $tax){
                            $value = ($tax['value_type'] == 'percentage' ? (float) $tax['value'] .'%' : price_format($tax['value']));
                            echo '<option value="'.$tax['id'].'">'.$tax['name'].' ('.$value.')</option>';
                        }
                        ?>
                    </select>
                    <span class="form-text text-muted"><?php _e('Select taxes for this plan.') ?></span>
                </div>
                <input type="hidden" name="submit">
            </div>
        </form>
    </div>
</div>
<script>
    $('.quick-select2').select2({tags: true});
</script>