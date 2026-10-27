<?php
global $config,$link;
if (checkloggedin()) {
    $start = date('Y-m-01');
    $end = date_create(date('Y-m-t'))->modify('+1 day')->format('Y-m-d');

    $days = $scans = [];
    $total_scans = 0;

    $period = new \DatePeriod(date_create($start), \DateInterval::createFromDateString('1 day'), date_create($end));
    /** @var \DateTime $dt */
    foreach ($period as $dt) {
        $days[] = date('d M', $dt->getTimestamp());
        $scans[date('d M', $dt->getTimestamp())] = 0;
    }

    $results = ORM::for_table($config['db']['pre'] . 'vcards')
        ->select('id')
        ->where('user_id', $_SESSION['user']['id'])
        ->find_many();
    $total_vcards = ORM::for_table($config['db']['pre'] . 'vcards')
        ->where('user_id', $_SESSION['user']['id'])
        ->count();

    $vcard_ids = [];
    foreach ( $results as $result ) {
        $vcard_ids[] = $result['id'];
    }

    if (!empty($vcard_ids)) {
        $sql = "SELECT DATE(`date`) AS created, COUNT(1) AS scans 
                FROM " . $config['db']['pre'] . "vcard_view 
                WHERE 
                    `vcard_id` IN (".implode(',',$vcard_ids).")
                    AND `date` BETWEEN '$start' AND '$end'
                GROUP BY DATE(`date`)";

        $result = ORM::for_table($config['db']['pre'] . 'vcard_view')
            ->raw_query($sql)
            ->find_many();

        foreach ($result as $data) {
            $scans[date('d M', strtotime($data['created']))] = $data['scans'];
        }

        /*$total_scans = ORM::for_table($config['db']['pre'] . 'vcard_view')
            ->where('vcard_id', $vcard['id'])
            ->count();*/
    }

    $membership = get_user_membership_detail($_SESSION['user']['id']);
    $membership_name = $membership['name'];

    //Print Template 'Home/index Page'
    HtmlTemplate::display('dashboard', array(
        'scans' => json_encode(array_values($scans)),
        'days' => json_encode(array_values($days)),
        'membership_name' => $membership_name,
        'total_vcards' => $total_vcards
    ));
} else {
    headerRedirect($link['LOGIN']);
}
?>
