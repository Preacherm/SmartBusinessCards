<?php
require_once('includes.php');

// initilize all variable
$params = $columns = $totalRecords = $data = array();
$params = $_REQUEST;
if($params['draw'] == 1)
    $params['order'][0]['dir'] = "desc";
//define index of column
$columns = array(
    'upgrade_id',
    'sub_id',
    'user_id'
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if( !empty($params['search']['value']) ) {
    $where .=" WHERE ";
    $where .=" ( upgrade_id LIKE '".$params['search']['value']."%' )";
}

// getting total number records without any search
$sql = "SELECT * FROM `".$config['db']['pre']."upgrades` ";
$sqlTot .= $sql;
$sqlRec .= $sql;
//concatenate search sql if value exist
if(isset($where) && $where != '') {

    $sqlTot .= $where;
    $sqlRec .= $where;
}


$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

$queryTot = $pdo->query($sqlTot);
$totalRecords = $queryTot->rowCount();
$queryRecords = $pdo->query($sqlRec);

//iterate on results row and create new index array of data
foreach ($queryRecords as $row) {

    $id = $row['upgrade_id'];
    $start_date = date("d-m-Y",$row['upgrade_lasttime']);
    $end_date = date("d-m-Y",$row['upgrade_expires']);

    $username = 'Removed';
    $info = ORM::for_table($config['db']['pre'].'user')
        ->select('username')
        ->where('id',$row['user_id'])
        ->find_one();

    $username = $info['username'];

    if($row['sub_id'] == 'trial'){
        $sub_title = 'Trial';
    }else{
        $sub_info = ORM::for_table($config['db']['pre'].'plans')
            ->where('id',$row['sub_id'])
            ->find_one();

        $sub_title = stripslashes($sub_info['name']);
    }

    $rows = array();
    $rows[] = '<td>'.$username.'</td>';
    $rows[] = '<td>'.$sub_title.'</td>';
    $rows[] = '<td>'.$start_date.' / '.$end_date.'</td>';

    $rows['DT_RowId'] = $id;
    $data[] = $rows;
}

$json_data = array(
    "draw"            => intval( $params['draw'] ),
    "recordsTotal"    => intval( $totalRecords ),
    "recordsFiltered" => intval($totalRecords),
    "data"            => $data
);

echo json_encode($json_data);
