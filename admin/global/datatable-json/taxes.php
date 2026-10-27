<?php
require_once('includes.php');

// initilize all variable
$params = $columns = $totalRecords = $data = array();
$params = $_REQUEST;

//define index of column
$columns = array(
    'internal_name',
    'name',
    'value',
    'billing_type',
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if( !empty($params['search']['value']) ) {
    $where .=" WHERE ";
    $where .=" ( internal_name LIKE '%".$params['search']['value']."%' ";
    $where .=" OR name LIKE '".$params['search']['value']."%' )";
}

// getting total number records without any search
$sql = "SELECT * FROM `".$config['db']['pre']."taxes` ";
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
    $internal_name = $row['internal_name'];
    $name = $row['name'];
    $description = $row['description'];
    $value = $row['value'];
    $value_type = $row['value_type'];
    $type = $row['type'];

    $rows = array();
    $rows[] = '<td>'.$internal_name.'</td>';
    $rows[] = '<td>'.$name.'<br>'.$description.'</td>';
    $rows[] = '<td>'.($value_type == 'percentage' ? (float) $value .'%' : price_format($value)).'</td>';
    $rows[] = '<td>'. ($type == 'inclusive' ? 'Inclusive' : 'Exclusive') .'</td>';
    $rows[] = '<td class="text-center">
                <div class="btn-group">
                    <a href="#" data-url="panel/taxes.php?id='.$id.'" data-toggle="slidePanel" title="'.__('Edit').'" class="btn-icon mr-1" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                    <a href="#" class="btn-icon btn-danger item-js-delete" title="'.__('Delete').'" data-tippy-placement="top" data-ajax-action="deleteTaxes"><i class="icon-feather-trash-2"></i></a>
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