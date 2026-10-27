<?php
require_once('includes.php');

// initilize all variable
$params = $columns = $totalRecords = $data = array();
$params = $_REQUEST;

//define index of column
$columns = array(
    'country_code',
    'time_zone_id',
    'gmt',
    'dst',
    'raw'
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if( !empty($params['search']['value']) ) {
    $where .=" WHERE ";
    $where .=" ( country_code LIKE '".$params['search']['value']."%' ";
    $where .=" OR time_zone_id LIKE '%".$params['search']['value']."%' ";
    $where .=" OR gmt LIKE '".$params['search']['value']."%' ";
    $where .=" OR dst LIKE '".$params['search']['value']."%' ";
    $where .=" OR raw LIKE '".$params['search']['value']."%' )";
}

// getting total number records without any search
$sql = "SELECT * FROM `".$config['db']['pre']."time_zones` ";
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
    
    $id                 = $row['id'];
    $country_code       = $row['country_code'];
    $time_zone_id       = $row['time_zone_id'];
    $gmt                = $row['gmt'];
    $dst                = $row['dst'];
    $raw                = $row['raw'];

    $rows = array();
    $rows[] = '<td>'.$country_code.'</td>';
    $rows[] = '<td>'.$time_zone_id.'</td>';
    $rows[] = '<td>'.$gmt.'</td>';
    $rows[] = '<td>'.$dst.'</td>';
    $rows[] = '<td>'.$raw.'</td>';
    $rows[] = '<td class="text-center">
                <div class="btn-group">
                    <a href="#" data-url="panel/timezones.php?id='.$id.'" data-toggle="slidePanel" title="'.__('Edit').'" class="btn-icon mr-1" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                    <a href="#" class="btn-icon btn-danger item-js-delete" title="'.__('Delete').'" data-tippy-placement="top" data-ajax-action="deleteTimezone"><i class="icon-feather-trash-2"></i></a>
                </div>
            </td>';
    $rows[] = '<td>
                <div class="checkbox">
                    <input type="checkbox" id="check_'.$id.'" value="'.$id.'" class="quick-check">
                    <label for="check_'.$id.'"><span class="checkbox-icon"></span></label>
                </div>
            </td>';

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