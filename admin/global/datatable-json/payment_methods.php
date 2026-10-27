<?php
require_once('includes.php');

// initilize all variable
$params = $columns = $totalRecords = $data = array();
$params = $_REQUEST;

//define index of column
$columns = array(
    'id',
    'payment_title',
    'payment_install'
);
$where = $sqlTot = $sqlRec = "";

// check search value exist
if( !empty($params['search']['value']) ) {
    $where .=" WHERE ";
    $where .=" ( payment_title LIKE '%".$params['search']['value']."%' ";
    $where .=" OR payment_install LIKE '".$params['search']['value']."%' )";
}

// getting total number records without any search
$sql = "SELECT * FROM `".$config['db']['pre']."payments` ";
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
    $id = $row['id'];
    $name = $row['payment_title'];
    $folder = $row['payment_folder'];
    if($row['payment_install'] == 1)
        $install = '<span class="badge badge-primary">'.__("Installed").'</span>';
    else
        $install = '<span class="badge badge-secondary">'.__("Uninstall").'</span>';

    if($row['payment_install'] == 1) {

        $install_button = '<a href="#" title="'.__('Uninstall').'" data-tippy-placement="top" class="btn-icon btn-danger uninstall-payment" data-ajax-action="uninstallPayment" data-folder="'.$folder.'"><i class="icon-feather-x"></i></a>';
    }
    else{
        $install_button = '<a href="#" title="'.__('Install').'" data-tippy-placement="top" class="btn-icon btn-primary install-payment" data-ajax-action="installPayment" data-folder="'.$folder.'"><i class="icon-feather-download"></i></a>';
    }

    $rows = array();
    $rows[] = '<td><img src="'.$config['site_url'].'admin/assets/images/payments/'.$folder.'.png" width="80px"/></td>';
    $rows[] = '<td>'.$name.'</td>';
    $rows[] = '<td>'.$install.'</td>';
    $rows[] = '<td class="text-center">
                <div class="btn-group">
                    <a href="#" title="'.__('Edit').'" class="btn-icon mr-1" data-tippy-placement="top" data-url="panel/payment_edit.php?id='.$id.'" data-toggle="slidePanel"><i class="icon-feather-edit"></i></a>
                   '.$install_button.'
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