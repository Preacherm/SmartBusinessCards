<?php
include '../datatable-json/includes.php';

switch ($_GET['id']){
    case 'free':
        $info = json_decode(get_option('free_membership_plan'), true);
        $settings = $info['settings'];
        break;
    case 'trial':
        $info = json_decode(get_option('trial_membership_plan'), true);
        $settings = $info['settings'];
        break;
    default:
        $info = ORM::for_table($config['db']['pre'].'plans')
            ->where('id', $_GET['id'])
            ->find_one();
        $settings = json_decode($info['settings'],true);
        break;
}

?>
<div class="slidePanel-content">
    <header class="slidePanel-header">
        <div class="slidePanel-overlay-panel">
            <div class="slidePanel-heading">
                <h2><?php _e('Edit Plan') ?></h2>
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
        <form method="post" id="sidePanel_form" data-ajax-action="editMembershipPlan">
            <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
            <div class="form-body">
                <?php quick_switch(__('Activate'), 'active', ($info['status'] == '1')); ?>
                <div class="form-group">
                    <label for="name"><?php _e('Plan Name') ?>*</label>
                    <input id="name" name="name" type="text" class="form-control" value="<?php _esc($info['name']);?>">
                </div>
                <div class="form-group d-none">
                    <label for="badge"><?php _e('Plan Badge') ?></label>
                    <input id="badge" name="badge" type="text" class="form-control" value="<?php _esc($info['badge']);?>">
                    <span class="form-text text-muted"><?php _e('Paste Image Url, This badge will display in user profile after username.') ?></span>
                </div>
                <?php if($_GET['id'] != 'free' && $_GET['id'] != 'trial'){ ?>
                <div class="form-group">
                    <label for="monthly_price"><?php _e('Monthly Price') ?>*</label>
                    <input name="monthly_price" type="number" class="form-control" id="monthly_price" value="<?php _esc($info['monthly_price']);?>">
                    <span class="form-text text-muted"><?php _e('Set 0 to disable it.') ?></span>
                </div>
                <div class="form-group">
                    <label for="annual_price"><?php _e('Annual Price') ?>*</label>
                    <input name="annual_price" type="number" class="form-control" id="annual_price" value="<?php _esc($info['annual_price']);?>">
                    <span class="form-text text-muted"><?php _e('Set 0 to disable it.') ?></span>
                </div>
                <div class="form-group">
                    <label for="lifetime_price"><?php _e('Lifetime Price') ?>*</label>
                    <input name="lifetime_price" type="number" class="form-control" id="lifetime_price" value="<?php _esc($info['lifetime_price']);?>">
                    <span class="form-text text-muted"><?php _e('Set 0 to disable it.') ?></span>
                </div>
                <?php
                    quick_switch(__('Recommended'), 'recommended', ($info['recommended'] == 'yes'));
                }
                if($_GET['id'] == 'trial') { ?>
                    <div class="form-group">
                        <label for="days"><?php _e('Days') ?></label>
                        <input name="days" type="number" class="form-control" id="days" value="<?php _esc($info['days']);?>">
                        <span class="form-text text-muted"><?php _e('The number of days that the trial plan can be used.') ?></span>
                    </div>
                <?php } ?>
                <h5 class="m-t-35"><?php _e('Plan Settings') ?></h5>
                <hr>
                <div class="form-group">
                    <label for="scan_limit"><?php _e('Scans Limit Per Month') ?>*</label>
                    <input name="scan_limit" type="number" class="form-control" id="scan_limit" value="<?php _esc($settings['scan_limit']);?>">
                    <span class="form-text text-muted"><?php _e('For unlimited, enter 999') ?></span>
                </div>
                <div class="form-group">
                    <label for="field_limit"><?php _e('Additional Field Limit') ?>*</label>
                    <input name="field_limit" type="number" class="form-control" id="field_limit" value="<?php _esc($settings['field_limit']);?>">
                    <span class="form-text text-muted"><?php _e('For unlimited, enter 999') ?></span>
                </div>
                <?php quick_switch(__('Hide Branding'), 'hide_branding', ($settings['hide_branding'] == '1')); ?>
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
                            quick_switch(_esc($custom['title'], false), 'custom_'.$custom['id'], (isset($settings['custom'][$custom['id']]) && $settings['custom'][$custom['id']] == '1'));
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
                        $plan_taxes = explode(',', $info['taxes_ids']);
                        $taxes = ORM::for_table($config['db']['pre'].'taxes')
                            ->find_many();
                        foreach ($taxes as $tax){
                            $value = ($tax['value_type'] == 'percentage' ? (float) $tax['value'] .'%' : price_format($tax['value']));
                            echo '<option value="'.$tax['id'].'" '. (in_array($tax['id'], $plan_taxes)? 'selected':'') .'>'.$tax['name'].' ('.$value.')</option>';
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

