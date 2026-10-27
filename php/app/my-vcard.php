<?php
global $config,$link;
if(checkloggedin()) {
    $orm = ORM::for_table($config['db']['pre'].'vcards')
        ->where('user_id', $_SESSION['user']['id']);
    $rows = $orm->find_many();
    $count = $orm->count();
    $vcards = array();
    if($count){
        foreach ($rows as $row){
            $vcards[$row['id']]['id'] = $row['id'];
            $vcards[$row['id']]['slug'] = $row['slug'];
            $vcards[$row['id']]['title'] = $row['title'];
            $vcards[$row['id']]['sub_title'] = $row['sub_title'];
            $vcards[$row['id']]['main_image'] = $row['main_image'];
            $vcards[$row['id']]['created_at'] = date_format(new DateTime($row['created_at']),"d F, Y");
            $vcards[$row['id']]['link'] = $config['site_url'] . $row['slug'];
            $total_scans = ORM::for_table($config['db']['pre'] . 'vcard_view')
                ->where('vcard_id', $row['id'])
                ->count();
            $vcards[$row['id']]['total_scans'] = $total_scans;
        }
    }
    //Print Template 'Add-vcard Page'
    HtmlTemplate::display('my-vcard', array(
        'vcards' => $vcards,
        'total' => $count
    ));
} else{
    headerRedirect($link['LOGIN']);
}
?>