<?php
include 'includes.php';

$total_active_item = ORM::for_table($config['db']['pre'].'vcards')
    ->table_alias('v')
    ->inner_join($config['db']['pre']."user","u.id = v.user_id",'u')
    ->count();
$banned_user = ORM::for_table($config['db']['pre'].'user')->where('status',2)->count();
$total_user = ORM::for_table($config['db']['pre'].'user')->count();

$quick_fetch= ORM::for_table($config['db']['pre'].'balance')->find_one(1);
$totalearning = price_format($quick_fetch['total_earning']);

$page_title = 'Dashboard';
include 'header.php'; ?>

    <div class="page-body-wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-lg-6 main-header">
                        <h2><?php _e('Dashboard') ?></h2>
                        <h6 class="mb-0"><?php _e('admin panel') ?></h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <?php
            if(!isset($config['purchase_key']) || $config['purchase_key'] == ""){
                ?>
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong><?php _e('Important!') ?></strong> <?php _e('Please verify purchase code to use admin feature.') ?>
                    <a class="alert-link" href="<?php echo ADMINURL ?>global/settings.php#quick_purchase_code"><strong><?php _e('Click here') ?></strong></a>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="fun-fact">
                        <div class="fun-fact-icon" style="background-color: rgba(54, 189, 120, 0.07);">
                            <i class="icon-feather-copy" style="color: rgb(54, 189, 120);"></i>
                        </div>
                        <div class="fun-fact-text">
                            <span><?php _e('Total VCards') ?></span>
                            <h4 id="quick-revenue"><?php _esc($total_active_item); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fun-fact">
                        <div class="fun-fact-icon" style="background-color: rgba(81, 54, 189, 0.07);">
                            <i class="icon-feather-users" style="color: rgb(54 96 189);"></i>
                        </div>
                        <div class="fun-fact-text">
                            <span><?php _e('Total Users') ?></span>
                            <h4 id="quick-total-booking"><?php echo $total_user; ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fun-fact">
                        <div class="fun-fact-icon" style="background-color: rgba(239, 168, 15, 0.07);">
                            <i class="icon-feather-credit-card" style="color: rgb(239, 168, 15);"></i>
                        </div>
                        <div class="fun-fact-text">
                            <span><?php _e('Total Income') ?></span>
                            <h4 id="quick-pending-booking"><?php echo $totalearning; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="quick-card card">
                        <div class="card-header">
                            <h5><?php _e('VCard Statistics') ?></h5>
                        </div>
                        <div class="card-body pt-0 pb-0 pl-4 pr-0">
                            <div class="chart quick-chart" style="height: 310px;">
                                <canvas id="chart-statistics"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="quick-card card">
                        <div class="card-header">
                            <h5><?php _e('Weekly users') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="chart quick-chart" style="height: 240px;">
                                <canvas id="chart-users"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="quick-card card">
                        <div class="card-header">
                            <h5><?php _e('Recent 5 VCards') ?></h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $getItem = ORM::for_table($config['db']['pre'].'vcards')
                                ->table_alias('v')
                                ->select_many_expr('v.id vid','v.title','v.slug','v.created_at','v.main_image')
                                ->inner_join($config['db']['pre']."user","u.id = v.user_id",'u')
                                ->limit(5)
                                ->order_by_desc('v.id')
                                ->find_many();
                            foreach ($getItem as $ads) {
                                $ad_id          = $ads['vid'];
                                $ad_title       = $ads['title'];
                                $ad_created_at  = $ads['created_at'];
                                $ad_pic         = !empty($ads['main_image'])?$ads['main_image']:'default.png';
                                ?>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="pro-img p-r-10">
                                        <a href="#">
                                            <img src="../storage/cards/logo/<?php _esc($ad_pic); ?>" alt="<?php _esc($ad_title) ?>" width="60" height="60">
                                        </a>
                                    </div>
                                    <div class="pro-detail">
                                        <h6 class="m-t-0 m-b-5">
                                            <a href="<?php _esc($config['site_url'].$ads['slug']) ?>" target="_blank"><?php echo $ad_title; ?></a>
                                        </h6>
                                        <p class="text-muted font-12"><?php _esc($ad_created_at); ?> </p>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="text-right">
                                <a href="<?php echo ADMINURL; ?>app/vcards.php" class="btn btn-sm btn-rounded btn-primary m-t-10"><?php _e('View All') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="quick-card card">
                        <div class="card-header">
                            <h5><?php _e('Recent Registered') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><?php _e('NAME') ?></th>
                                        <th><?php _e('EMAIL') ?></th>
                                        <th><?php _e('DATE') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $rows = ORM::for_table($config['db']['pre'].'user')
                                        ->order_by_desc('id')
                                        ->limit(5)
                                        ->find_many();
                                    foreach ($rows as $info) {
                                        ?>
                                        <tr>
                                            <td class="text-truncate"><?php _esc($info['name']); ?></td>
                                            <td><span class="label label-info label-rounded"><?php _esc($info['email']); ?></span> </td>
                                            <td class="text-truncate"><?php _esc(timeAgo($info['created_at'])); ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <a href="<?php echo ADMINURL; ?>global/users.php"><?php _e('Check all Users') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        var QuickMenu = {"page":"dashboard"};
    </script>
<?php ob_start() ?>
    <script src="<?php echo ADMINURL; ?>assets/js/chart.min.js"></script>
<?php

$end = date_create(date('Y-m-d'))->modify('+1 day')->format('Y-m-d');
$start = date_create(date('Y-m-d', strtotime($end)))->modify('-7 days')->format('Y-m-d');

$users = $vcards = [];

$period = new \DatePeriod(date_create($start), \DateInterval::createFromDateString('1 day'), date_create($end));
/** @var \DateTime $dt */
foreach ($period as $dt) {
    $days[] = date('d M', $dt->getTimestamp());
    $vcards[date('d M', $dt->getTimestamp())] = 0;
    $users[date('d M', $dt->getTimestamp())] = 0;
}

/*$sql = "SELECT DATE(`date`) AS created, SUM(`words`) AS used_words
                FROM " . $config['db']['pre'] . "word_used 
                WHERE `date` BETWEEN '$start' AND '$end'
                GROUP BY DATE(`date`)";*/

$sql = "SELECT DATE(`created_at`) AS created, COUNT(`id`) AS total_vcards 
                FROM " . $config['db']['pre'] . "vcards 
                WHERE `created_at` BETWEEN '$start' AND '$end'
                GROUP BY DATE(`created_at`)";

$result = ORM::for_table($config['db']['pre'] . 'vcards')
    ->raw_query($sql)
    ->find_many();

foreach ($result as $data) {
    $vcards[date('d M', strtotime($data['created']))] = (int) $data['total_vcards'];
}

$sql = "SELECT DATE(`created_at`) AS created, COUNT(`id`) AS total_users 
                FROM " . $config['db']['pre'] . "user 
                WHERE `created_at` BETWEEN '$start' AND '$end'
                GROUP BY DATE(`created_at`)";

$result = ORM::for_table($config['db']['pre'] . 'user')
    ->raw_query($sql)
    ->find_many();

foreach ($result as $data) {
    $users[date('d M', strtotime($data['created']))] = $data['total_users'];
}

?>
    <script>
        var ctx = document.getElementById("chart-statistics");
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php _esc(json_encode(array_values($days))); ?>,
                datasets: [{
                    label: "<?php _e('This Week') ?>",
                    fill: true,
                    data: <?php _esc(json_encode(array_values($vcards))); ?>,
                    yAxisID: 'y-axis-1',
                    backgroundColor: 'rgba(42,65,232,0.08)',
                    borderColor: '#2a41e8',
                    borderWidth: "3",
                    pointRadius: 5,
                    pointHoverRadius: 5,
                    pointHitRadius: 10,
                    pointBackgroundColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointBorderWidth: "2",
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: false
                    },
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: '#333',
                        titleFontSize: 13,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        bodyFontSize: 13,
                        displayColors: false,
                        xPadding: 10,
                        yPadding: 10,
                        intersect: false
                    }
                }
            }
        });

        var ctx = document.getElementById("chart-users");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php _esc(json_encode(array_values($days))); ?>,
                datasets: [{
                    label: "<?php _e('This Week') ?>",
                    fill: true,
                    data: <?php _esc(json_encode(array_values($users))); ?>,
                    yAxisID: 'y-axis-1',
                    backgroundColor: '#bdc4f3',
                    borderColor: '#bdc4f3',
                    borderWidth: "2",
                    pointRadius: 5,
                    pointHoverRadius: 5,
                    pointHitRadius: 10,
                    pointBackgroundColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointBorderWidth: "2",
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: false
                    },
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: '#333',
                        titleFontSize: 13,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        bodyFontSize: 13,
                        displayColors: false,
                        xPadding: 10,
                        yPadding: 10,
                        intersect: false
                    }
                }
            }
        });
    </script>
<?php
$footer_content = ob_get_clean();

include 'footer.php'; ?>