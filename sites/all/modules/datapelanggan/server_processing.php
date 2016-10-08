<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
$drupal_root_dir = '../../../../';

# make your drupal directory php's current directory
chdir($drupal_root_dir);

# not shown in the book, i had to set this constant
define('DRUPAL_ROOT', $drupal_root_dir);

# bootstrap drupal up to the point the database is loaded
include_once('./includes/bootstrap.inc');
drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
$baseDirectory = '';
if (!file_exists('/misc/media/images/forward_enabled.ico')){
	$baseDirectory = '..';
}


function serverSidePelanggan($request){
	global $baseDirectory;
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$searchQuery = $searchArray['value'];
	$arrayColumn = array(
		'','','','plg.kodepelanggan','plg.namapelanggan','plg.telp', 'plg.alamat',
		'plg.email','ptg.besarhutang','ptg.pembayaranterakhir','bayarterakhir'
	);
	$orderColumnArray = $_REQUEST['order'];
	$orderColumn = $arrayColumn[$orderColumnArray[0]['column']].' '.$orderColumnArray[0]['dir'];
	if (is_null($pageStart)){
		$pageStart = 0;
	}
	if (is_null($pageLength)){
		$pageLength = 25;
	}
	$firstRecord = $pageStart;
	$lastRecord = $pageStart + $pageLength;
	$strSQL = "SELECT plg.idpelanggan,plg.kodepelanggan,plg.namapelanggan,plg.telp,plg.alamat,plg.email,";
	$strSQLFilteredTotal = "SELECT COUNT(plg.idpelanggan) ";
	$strSQL .= "ptg.besarhutang, ptg.pembayaranterakhir, SUBSTR(ptg.last_update,1,10) AS bayarterakhir ";
	$strSQL .= "FROM pelanggan AS plg ";
	$strSQLFilteredTotal .= "FROM pelanggan AS plg ";
	$strSQL .= "LEFT JOIN piutang AS ptg ON ptg.idpelanggan = plg.idpelanggan WHERE 1=1 ";
	$strSQLFilteredTotal .= "LEFT JOIN piutang AS ptg ON ptg.idpelanggan = plg.idpelanggan WHERE 1=1 ";
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (plg.kodepelanggan LIKE '%%%s%%' OR plg.namapelanggan LIKE '%%%s%%' ";
		$strCriteria .= "OR plg.alamat LIKE '%%%s%%' OR plg.email LIKE '%%%s%%')";
	}
	$strSQL .= $strCriteria." ORDER BY $orderColumn LIMIT %d, %d";
	$strSQLFilteredTotal .= $strCriteria;
	if (!empty($searchQuery)){
		$result = db_query($strSQL,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$searchQuery,$searchQuery,$searchQuery,$searchQuery));
	}else{
		$result = db_query($strSQL,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal));
	}
	$output = array();
	while ($data = db_fetch_object($result)){
		$rowData = array();
		$lihathutang = "<img title=\"Klik untuk melihat detail hutang\" src=\"$baseDirectory/misc/media/images/forward_enabled.ico\" onclick=\"view_detail_hutang(".$data->idpelanggan.",'".$data->namapelanggan."','".$data->besarhutang."');\">";
		$bayarhutang = "<img title=\"Klik untuk mengisi form pembayaran\" src=\"$baseDirectory/misc/media/images/money2_24.png\" onclick=\"pembayaran(".$data->idpelanggan.",'".$data->namapelanggan."','".$data->besarhutang."','".$data->besarhutang."');\">";
		$lihatdiskon = "<img title=\"Klik untuk melihat dan menambah diskon pelanggan\" src=\"$baseDirectory/misc/media/images/diskon.png\" onclick=\"tabeldiskon(".$data->idpelanggan.",'".$data->namapelanggan."')\">";
		$rowData[] = $lihathutang;
		$rowData[] = $bayarhutang;
		$rowData[] = $lihatdiskon;
		$rowData[] = $data->kodepelanggan;
		$rowData[] = $data->namapelanggan;
		$rowData[] = $data->telp;
		$rowData[] = $data->alamat;
		$rowData[] = $data->email;
		$rowData[] = number_format($data->besarhutang,0,",",".");
		$rowData[] = number_format($data->pembayaranterakhir,0,",",".");
		$rowData[] = $data->bayarterakhir;
        $rowData[] = $data->idpelanggan;
		$output[] = $rowData;
	}
	$recordsTotal = db_result(db_query("SELECT COUNT(idpelanggan) FROM pelanggan"));
	return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => $output
		);
}
function serverSideProduk($request){
	global $baseDirectory;
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$searchQuery = $searchArray['value'];
	$arrayColumn = array(
		'prod.idproduct','kat.kategori','subkat.subkategori','supp.namasupplier', 'prod.barcode',
		'prod.alt_code','prod.namaproduct','prod.hargapokok','prod.hargajual','prod.margin'
		,'prod.minstok','prod.maxstok','prod.stok','prod.satuan','prod.keterangan','total_nilai'
	);
	$orderColumnArray = $_REQUEST['order'];
	$orderColumn = $arrayColumn[$orderColumnArray[0]['column']].' '.$orderColumnArray[0]['dir'];
	if (is_null($pageStart)){
		$pageStart = 0;
	}
	if (is_null($pageLength)){
		$pageLength = 25;
	}
	$firstRecord = $pageStart;
	$lastRecord = $pageStart + $pageLength;
	$strSQL = "SELECT prod.idproduct,prod.idsupplier,prod.idkategori,prod.idsubkategori,prod.barcode,prod.alt_code,";
	$strSQL .= "prod.namaproduct,prod.hargapokok,prod.hargajual,prod.margin,prod.minstok,prod.maxstok,";
	$strSQL .= "prod.stok,prod.satuan,prod.berat,prod.keterangan ,";
	$strSQLFilteredTotal = "SELECT COUNT(prod.idproduct) ";
	$strSQL .= "kat.kategori, subkat.subkategori, supp.namasupplier, prod.stok*prod.hargapokok AS total_nilai  ";
	$strSQL .= "FROM product AS prod ";
	$strSQLFilteredTotal .= "FROM product AS prod ";
	$strSQL .= "LEFT JOIN kategori AS kat ON kat.idkategori = prod.idkategori ";
	$strSQL .= "LEFT JOIN subkategori AS subkat ON subkat.idsubkategori = prod.idsubkategori ";
	$strSQL .= "LEFT JOIN supplier AS supp ON supp.idsupplier = prod.idsupplier ";
	$strSQL .= "WHERE 1=1 ";
	$strSQLFilteredTotal .= "LEFT JOIN kategori AS kat ON kat.idkategori = prod.idkategori ";
	$strSQLFilteredTotal .= "LEFT JOIN subkategori AS subkat ON subkat.idsubkategori = prod.idsubkategori ";
	$strSQLFilteredTotal .= "LEFT JOIN supplier AS supp ON supp.idsupplier = prod.idsupplier ";
	$strSQLFilteredTotal .= "WHERE 1=1 ";
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (prod.alt_code LIKE '%%%s%%' OR prod.barcode LIKE '%%%s%%' ";
		$strCriteria .= "OR prod.namaproduct LIKE '%%%s%%' OR kat.kategori LIKE '%%%s%%' ";
		$strCriteria .= "OR kat.kodekategori LIKE '%%%s%%' OR subkat.subkategori LIKE '%%%s%%' ";
		$strCriteria .= "OR subkat.kodesubkategori LIKE '%%%s%%' OR supp.namasupplier LIKE '%%%s%%' ";
		$strCriteria .= "OR supp.kodesupplier LIKE '%%%s%%' ";
		$strCriteria .= ") ";
	}
	if (isset($_REQUEST['statusstok']) && $_REQUEST['statusstok'] != '0'){
		if ($_REQUEST['statusstok'] == 'aman'){
			$strCriteria .= "AND (stok >= prod.minstok AND stok <= prod.maxstok) ";
		}else if($_REQUEST['statusstok'] == 'maksimum'){
			$strCriteria .= "AND (stok > prod.maxstok) ";
		}else if($_REQUEST['statusstok'] == 'minimum'){
			$strCriteria .= "AND (stok < prod.minstok) ";
		}
	}
	$strSQL .= $strCriteria." ORDER BY $orderColumn LIMIT %d, %d";
	$strSQLFilteredTotal .= $strCriteria;
	if (!empty($searchQuery)){
		$result = db_query($strSQL,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery));
	}else{
		$result = db_query($strSQL,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal));
	}
	$output = array();
	$totalNilaiBarang = 0;
	while ($data = db_fetch_object($result)){
		$rowData = array();
		$imgEdit = "<img title=\"Edit Produk ".$data->namaproduct."\" src=\"$baseDirectory/misc/media/images/edit.ico\" onclick=\"editproduk(".$data->idproduct.")\">";
		$rowData[] = $imgEdit;
		$rowData[] = $data->kategori;
		$rowData[] = $data->subkategori;
		$rowData[] = $data->namasupplier;
		$rowData[] = $data->barcode;
		$rowData[] = $data->alt_code;
		$rowData[] = $data->namaproduct;
		$rowData[] = number_format($data->hargapokok,0,",",".");
		$rowData[] = number_format($data->hargajual,0,",",".");
		$rowData[] = number_format($data->margin,0,",",".");
		$rowData[] = $data->minstok;
    $rowData[] = $data->maxstok;
    $rowData[] = number_format($data->stok,0,",",".");
    if ($data->stok < $data->minstok){
			$rowData[] = "<img title=\"Stok dibawah minimum\" src=\"$baseDirectory/misc/media/images/statusmerah.png\">";
		}elseif ($data->stok > $data->maxstok){
			$rowData[] = "<img title=\"Stok berlebihan/diatas maksimum stok\" src=\"$baseDirectory/misc/media/images/statuskuning.png\">";
		}elseif ($data->stok <= $data->maxstok AND $data->stok >= $data->minstok AND $data->stok > 0){
			$rowData[] = "<img title=\"Stok aman\" src=\"$baseDirectory/misc/media/images/statushijau.png\">";
		}elseif ($data->stok <= 0){
			$rowData[] = "<img title=\"Stok Kosong\" src=\"$baseDirectory/misc/media/images/statusmerah.png\">";
		}
		$rowData[] = $data->satuan;
		$rowData[] = $data->keterangan;
		$rowData[] = number_format($data->total_nilai,0,",",".");
		$totalNilaiBarang = $totalNilaiBarang + $data->total_nilai;
		$rowData[] = $data->idproduct;
		$output[] = $rowData;
	}
	$recordsTotal = db_result(db_query("SELECT COUNT(idproduct) FROM product"));
	return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => $output
		);
}

function serverSidePenjualan($request){
	global $baseDirectory;
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$tglAwal = $_REQUEST['tglawal'].' 00:00';
	$tglAkhir = $_REQUEST['tglakhir'].' 23:59';
	$searchQuery = $searchArray['value'];
	$arrayColumn = array(
		'penj.idpenjualan','penj.nonota','penj.tglpenjualan','penj.idpemakai', 
		'penj.total','penj.totalmodal','penj.carabayar','penj.bayar','penj.kembali'
		,'penj.nokartu','penj.keterangan','penj.insert_date','user.name'
	);
	$orderColumnArray = $_REQUEST['order'];
	$orderColumn = $arrayColumn[$orderColumnArray[0]['column']].' '.$orderColumnArray[0]['dir'];
	if (is_null($pageStart)){
		$pageStart = 0;
	}
	if (is_null($pageLength)){
		$pageLength = 100;
	}
	$firstRecord = $pageStart;
	$lastRecord = $pageStart + $pageLength;
	$strSQL = "SELECT penj.idpenjualan,penj.nonota,SUBSTR(penj.tglpenjualan,1,10) AS tanggal,";
	$strSQL .= "SUBSTR(penj.tglpenjualan,11,9) AS waktu, penj.idpemakai,penj.total,penj.totalmodal,";
	$strSQL .= "(penj.total-penj.totalmodal) AS laba, penj.carabayar,penj.bayar,penj.kembali,";
	$strSQL .= "penj.nokartu,penj.keterangan,penj.insert_date, user.name ";
	$strSQLFilteredTotal = "SELECT COUNT(penj.idpenjualan) ";
	$strSQL .= "FROM penjualan AS penj ";
	$strSQLFilteredTotal .= "FROM penjualan AS penj ";
	$strSQL .= "LEFT JOIN cms_users AS user ON user.uid = penj.idpemakai ";
	$strSQL .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' ";
	$strSQLFilteredTotal .= "LEFT JOIN cms_users AS user ON user.uid = penj.idpemakai ";
	$strSQLFilteredTotal .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' ";
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (penj.nonota LIKE '%%%s%%' OR SUBSTR(penj.tglpenjualan,1,10) LIKE '%%%s%%' ";
		$strCriteria .= "OR SUBSTR(penj.tglpenjualan,11,9) LIKE '%%%s%%' OR user.name LIKE '%%%s%%' ";
		$strCriteria .= ")";
	}
	$strSQL .= $strCriteria." ORDER BY $orderColumn LIMIT %d, %d";
	$strSQLFilteredTotal .= $strCriteria;
	if (!empty($searchQuery)){
		$result = db_query($strSQL,$tglAwal,$tglAkhir,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$tglAwal,$tglAkhir,$searchQuery,$searchQuery,$searchQuery,$searchQuery));
	}else{
		$result = db_query($strSQL,$tglAwal,$tglAkhir,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$tglAwal,$tglAkhir));
	}
	$output = array();
	while ($data = db_fetch_object($result)){
		$rowData = array();
		$imgDetail = "<img title=\"Klik untuk melihat detail penjualan\" onclick=\"view_detail(".$data->idpenjualan.",'".$data->nonota."');\" src=\"$baseDirectory/misc/media/images/forward_enabled.ico\">";
		$rowData[] = $imgDetail;
		$rowData[] = $data->nonota;
		$rowData[] = $data->tanggal;
		$rowData[] = $data->waktu;
		$rowData[] = number_format($data->total,0,",",".");
		$rowData[] = number_format($data->totalmodal,0,",",".");
		$rowData[] = number_format($data->laba,0,",",".");
		$rowData[] = $data->carabayar;
		$rowData[] = number_format($data->bayar,0,",",".");
		$rowData[] = number_format($data->kembali,0,",",".");
		$rowData[] = $data->name;
    $rowData[] = $data->idpenjualan;
		$output[] = $rowData;
	}
	$recordsTotal = db_result(db_query("SELECT COUNT(idpenjualan) FROM penjualan WHERE tglpenjualan BETWEEN '%s' AND '%s'",$tglAwal,$tglAkhir));
	return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => $output
		);
}

function serverSidePenjualan2($request){
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$tglAwal = $_REQUEST['tglawal'].' 00:00';
	$tglAkhir = $_REQUEST['tglakhir'].' 23:59';
	$searchQuery = $searchArray['value'];
	$arrayColumn = array(
		'prod.barcode','prod.namaproduct','supp.namasupplier', 
		'totalqty','minhargapokok','maxhargapokok','minhargajual','maxhargajual',
		'subtotal','totalmodal','laba'
	);
	$orderColumnArray = $_REQUEST['order'];
	$orderColumn = $arrayColumn[$orderColumnArray[0]['column']].' '.$orderColumnArray[0]['dir'];
	if (is_null($pageStart)){
		$pageStart = 0;
	}
	if (is_null($pageLength)){
		$pageLength = 100;
	}
	$firstRecord = $pageStart;
	$lastRecord = $pageStart + $pageLength;
	$strSQL = "SELECT detail.idproduct,prod.barcode,prod.namaproduct,";
	$strSQL .= "supp.namasupplier, SUM(detail.jumlah) AS totalqty,";
	$strSQL .= "MIN(detail.hargapokok) AS minhargapokok,MAX(detail.hargapokok) AS maxhargapokok,";
	$strSQL .= "MIN(detail.hargajual) AS minhargajual, MAX(detail.hargajual) AS maxhargajual, ";
	$strSQL .= "SUM(detail.hargapokok*detail.jumlah) AS totalmodal, SUM(detail.hargajual*detail.jumlah) AS subtotal,";
	$strSQL .= "SUM((detail.hargajual-detail.hargapokok)*detail.jumlah) AS laba ";
	$strSQLFilteredTotal = "SELECT COUNT(detail.idproduct) ";
	$strSQL .= "FROM detailpenjualan AS detail ";
	$strSQLFilteredTotal .= "FROM detailpenjualan AS detail ";
	$strSQL .= "LEFT JOIN penjualan AS penj ON detail.idpenjualan = penj.idpenjualan ";
	$strSQL .= "LEFT JOIN product AS prod ON detail.idproduct = prod.idproduct ";
	$strSQL .= "LEFT JOIN supplier AS supp ON prod.idsupplier = supp.idsupplier ";
	$strSQL .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' ";
	$strSQLFilteredTotal .= "LEFT JOIN penjualan AS penj ON detail.idpenjualan = penj.idpenjualan ";
	$strSQLFilteredTotal .= "LEFT JOIN product AS prod ON detail.idproduct = prod.idproduct ";
	$strSQLFilteredTotal .= "LEFT JOIN supplier AS supp ON prod.idsupplier = supp.idsupplier ";
	$strSQLFilteredTotal .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' ";
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (prod.barcode LIKE '%%%s%%' OR prod.namaproduct LIKE '%%%s%%' ";
		$strCriteria .= "OR supp.namasupplier LIKE '%%%s%%' ";
		$strCriteria .= ")";
	}
	$strSQL .= $strCriteria." GROUP BY detail.idproduct ORDER BY $orderColumn LIMIT %d, %d";
	$strSQLFilteredTotal .= $strCriteria." GROUP BY detail.idproduct";
	if (!empty($searchQuery)){
		$result = db_query($strSQL,$tglAwal,$tglAkhir,$searchQuery,$searchQuery,$searchQuery,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$tglAwal,$tglAkhir,$searchQuery,$searchQuery,$searchQuery));
	}else{
		$result = db_query($strSQL,$tglAwal,$tglAkhir,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$tglAwal,$tglAkhir));
	}
	$output = array();
	while ($data = db_fetch_object($result)){
		$rowData = array();
		$rowData[] = $data->barcode;
		$rowData[] = $data->namaproduct;
		$rowData[] = $data->namasupplier;
		$rowData[] = number_format($data->totalqty,0,",",".");
		$rowData[] = number_format($data->minhargapokok,0,",",".");
		$rowData[] = number_format($data->maxhargapokok,0,",",".");
		$rowData[] = number_format($data->minhargajual,0,",",".");
		$rowData[] = number_format($data->maxhargajual,0,",",".");
		$rowData[] = number_format($data->subtotal,0,",",".");
		$rowData[] = number_format($data->totalmodal,0,",",".");
		$rowData[] = number_format($data->laba,0,",",".");
		$rowData[] = $data->idproduct;
		$output[] = $rowData;
	}
	$recordsTotal = db_result(db_query("SELECT COUNT(idproduct) FROM detailpenjualan AS detail LEFT JOIN penjualan AS penj ON detail.idpenjualan=penj.idpenjualan WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' GROUP BY detail.idproduct",$tglAwal,$tglAkhir));
	return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => $output
		);
}

function serverSideLaundry($request){
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$tglAwal = $_REQUEST['tglawal'].' 00:00';
	$tglAkhir = $_REQUEST['tglakhir'].' 23:59';
	$searchQuery = $searchArray['value'];
	$arrayColumn = array(
		'laundry.nonota','tanggal','totallaundry','laundry.carabayar','laundry.bayar',
		'plg.namapelanggan','laundry.status_laundry','perkiraan_ambil','laundry.keterangan'
	);
	$orderColumnArray = $_REQUEST['order'];
	$orderColumn = $arrayColumn[$orderColumnArray[0]['column']].' '.$orderColumnArray[0]['dir'];
	if (is_null($pageStart)){
		$pageStart = 0;
	}
	if (is_null($pageLength)){
		$pageLength = 100;
	}
	$firstRecord = $pageStart;
	$lastRecord = $pageStart + $pageLength;
	$strSQL = "SELECT laundry.idtitipanlaundry,laundry.nonota,SUBSTR(laundry.tglpenjualan,1,10) AS tanggal,";
	$strSQL .= "SUBSTR(laundry.tglpenjualan,11,9) AS waktu, laundry.idpemakai,";
	$strSQL .= "(SELECT SUM(hargajual*jumlah) FROM detaillaundry WHERE ";
	$strSQL .= "idtitipanlaundry = laundry.idtitipanlaundry) AS totallaundry,";
	$strSQL .= "(SELECT MAX(perkiraan_ambil) FROM detaillaundry WHERE ";
	$strSQL .= "idtitipanlaundry = laundry.idtitipanlaundry) AS perkiraan_ambil,";
	$strSQL .= "laundry.carabayar, laundry.bayar, laundry.status_laundry, ";
	$strSQL .= "plg.namapelanggan, laundry.keterangan, user.name ";
	$strSQL .= "FROM titipanlaundry AS laundry ";
	$strSQL .= "LEFT JOIN cms_users AS user ON user.uid = laundry.idpemakai ";
	$strSQL .= "LEFT JOIN pelanggan AS plg ON plg.idpelanggan = laundry.idpelanggan ";
	$strSQL .= "WHERE laundry.tglpenjualan BETWEEN '%s' AND '%s' ";
	$strSQLFilteredTotal = "SELECT COUNT(laundry.idtitipanlaundry) ";
	$strSQLFilteredTotal .= "FROM titipanlaundry AS laundry ";
	$strSQLFilteredTotal .= "LEFT JOIN cms_users AS user ON user.uid = laundry.idpemakai ";
	$strSQLFilteredTotal .= "LEFT JOIN pelanggan AS plg ON plg.idpelanggan = laundry.idpelanggan ";
	$strSQLFilteredTotal .= "WHERE laundry.tglpenjualan BETWEEN '%s' AND '%s' ";
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (laundry.nonota LIKE '%%%s%%' OR SUBSTR(laundry.tglpenjualan,1,10) LIKE '%%%s%%' ";
		$strCriteria .= "OR SUBSTR(laundry.tglpenjualan,11,9) LIKE '%%%s%%' OR user.name LIKE '%%%s%%' ";
		$strCriteria .= "OR plg.namapelanggan LIKE '%%%s%%' OR laundry.carabayar LIKE '%%%s%%' ";
		$strCriteria .= ")";
	}
	$strSQL .= $strCriteria." ORDER BY $orderColumn LIMIT %d, %d";
	$strSQLFilteredTotal .= $strCriteria;
	if (!empty($searchQuery)){
		$result = db_query($strSQL,$tglAwal,$tglAkhir,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$tglAwal,$tglAkhir,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery));
	}else{
		$result = db_query($strSQL,$tglAwal,$tglAkhir,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$tglAwal,$tglAkhir));
	}
    $arrayhari = arrayHariSS();
	$output = array();
	while ($data = db_fetch_object($result)){
		$rowData = array();
		$tomboldetail = "<img title=\"Klik untuk melihat detail laundry\" onclick=\"view_detail(".$data->idtitipanlaundry.",'".$data->nonota."');\" src=\"$baseDirectory/misc/media/images/forward_enabled.ico\" width=\"22\">";
		$tombolambil = "<img title=\"Klik untuk mengisi form pengambilan laundry\" onclick=\"pickup_laundry(".$data->idtitipanlaundry.",'".$data->nonota."');\" src=\"$baseDirectory/misc/media/images/update.ico\" width=\"22\">";
		$tombolhapus = "<img title=\"Klik untuk menghapus laundry\" onclick=\"delete_laundry(".$data->idtitipanlaundry.",'".$data->nonota."');\" src=\"$baseDirectory/misc/media/images/del.ico\" width=\"22\">";
		$tombolprint = "<img title=\"Klik untuk mencetak titipan laundry\" onclick=\"print_laundry(".$data->idtitipanlaundry.",'".$data->nonota."');\" src=\"$baseDirectory/misc/media/images/print.png\" width=\"22\">";
		$tombolselesai = "<img title=\"Laundry sudah diambil\" src=\"/misc/media/images/checks.png\" width=\"22\">";		
		$rowData[] = $tomboldetail;
		if ($data->status_laundry == 0 || $data->status_laundry == 1){
			$rowData[] = $tombolambil;
		}else{
			$rowData[] = $tombolselesai;
		}
		$rowData[] = $tombolprint;
		$rowData[] = $tombolhapus;
		$rowData[] = $data->nonota;
        $indexhari = date('w', strtotime($data->tanggal));
        $rowData[] = $arrayhari[$indexhari];
		$rowData[] = date('d-m-Y', strtotime($data->tanggal));
		$rowData[] = $data->waktu;
		$rowData[] = number_format($data->totallaundry,0,",",".");
        $rowData[] = $data->carabayar;
        $rowData[] = number_format($data->bayar,0,",",".");
        $sisaPembayaran = $data->totallaundry - $data->bayar;
        $rowData[] = number_format($sisaPembayaran,0,",",".");
        $rowData[] = $data->name;
        $rowData[] = $data->namapelanggan;
        if ($data->status_laundry == 0){
            $rowData[] = 'BELUM DIAMBIL';
        }else if ($data->status_laundry == 1){
            $rowData[] = 'DIAMBIL SEBAGIAN';
        }else if ($data->status_laundry == 2){
            $rowData[] = 'SUDAH DIAMBIL';
        }
        $rowData[] = date('d-m-Y H:i', $data->perkiraan_ambil);
        $rowData[] = $data->keterangan;
        $rowData[] = $data->idtitipanlaundry;
		$output[] = $rowData;
	}
	$recordsTotal = db_result(db_query("SELECT COUNT(idtitipanlaundry) FROM titipanlaundry WHERE tglpenjualan BETWEEN '%s' AND '%s'",$tglAwal,$tglAkhir));
	return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => $output,
            "order"           => $orderColumn
		);
}
function arrayHariSS(){
    $hari_array = array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');
    return $hari_array;
}
if ($_GET['request_data'] == 'pelanggan'){
	$returnArray = serverSidePelanggan($_GET);
}else if($_GET['request_data'] == 'produk'){
	$returnArray = serverSideProduk($_GET);
}else if($_GET['request_data'] == 'penjualan'){
	$returnArray = serverSidePenjualan($_GET);
}else if($_GET['request_data'] == 'penjualan2'){
	$returnArray = serverSidePenjualan2($_GET);
}else if($_GET['request_data'] == 'laundry'){
    $returnArray = serverSideLaundry($_GET);
}
echo json_encode($returnArray);
?>