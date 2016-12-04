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
		,'prod.minstok','prod.maxstok','prod.stok','prod.stok','prod.satuan','prod.keterangan','prod.stok*prod.hargajual'
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
	$strSQL .= "kat.kategori, subkat.subkategori, supp.namasupplier, prod.stok*prod.hargajual AS total_nilai  ";
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
			$strCriteria .= "AND (stok >= prod.minstok AND stok <= prod.maxstok && prod.minstok != prod.maxstok) ";
		}else if($_REQUEST['statusstok'] == 'maksimum'){
			$strCriteria .= "AND (stok > prod.maxstok) ";
		}else if($_REQUEST['statusstok'] == 'minimum'){
			$strCriteria .= "AND (stok < prod.minstok) ";
		}
	}
	if (isset($_REQUEST['status_product'])){
		$strCriteria .= "AND (status_product = ".$_REQUEST['status_product'].") ";
	}else{
		$strCriteria .= "AND (status_product = 1) ";
	}
	if ($pageLength != '-1'){
		$strSQL .= $strCriteria." ORDER BY $orderColumn LIMIT %d, %d";
	}else{
		$strSQL .= $strCriteria." ORDER BY $orderColumn";
	}
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
		$rowData[] = '<input class="barcode-select" type="checkbox" id="check-'.$data->idproduct.'" name="check-'.$data->idproduct.'" value="'.$data->idproduct.'">';
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
			"data"            => $output,
			"sql" 			  => $strSQL,
		);
}

function serverSidePenjualan($request){
	global $baseDirectory;
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$tglAwal = $_REQUEST['tglawal'].' 00:00';
	$tglAkhir = $_REQUEST['tglakhir'].' 23:59';
	$idpelanggan = $_REQUEST['idpelanggan'];
	$searchQuery = $searchArray['value'];
	$arrayColumn = array(
		'penj.idpenjualan','penj.nonota','penj.tglpenjualan','penj.tglpenjualan',
		'penj.total','penj.totalmodal','(penj.total - penj.totalmodal)','penj.carabayar',
		'penj.bayar','penj.kembali','user.name','plg.namapelanggan'
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
	$strSQL .= "penj.nokartu,penj.keterangan,penj.insert_date, user.name, plg.namapelanggan ";
	$strSQLFilteredTotal = "SELECT COUNT(penj.idpenjualan) ";
	$strSQL .= "FROM penjualan AS penj ";
	$strSQLFilteredTotal .= "FROM penjualan AS penj ";
	$strSQL .= "LEFT JOIN cms_users AS user ON user.uid = penj.idpemakai ";
	$strSQL .= "LEFT JOIN pelanggan AS plg ON plg.idpelanggan = penj.idpelanggan ";
	if (empty($idpelanggan)){
		$strSQL .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' ";
	}else{
		$strSQL .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' AND penj.idpelanggan=%d ";
	}
	$strSQLFilteredTotal .= "LEFT JOIN cms_users AS user ON user.uid = penj.idpemakai ";
	$strSQLFilteredTotal .= "LEFT JOIN pelanggan AS plg ON plg.idpelanggan = penj.idpelanggan ";
	if (empty($idpelanggan)){
		$strSQLFilteredTotal .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' ";
	}else{
		$strSQLFilteredTotal .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' AND penj.idpelanggan=%d ";
	}
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (penj.nonota LIKE '%%%s%%' OR SUBSTR(penj.tglpenjualan,1,10) LIKE '%%%s%%' ";
		$strCriteria .= "OR SUBSTR(penj.tglpenjualan,11,9) LIKE '%%%s%%' OR user.name LIKE '%%%s%%' ";
		$strCriteria .= "OR plg.namapelanggan LIKE '%%%s%%' OR penj.carabayar LIKE '%%%s%%' ";
		$strCriteria .= ")";
	}
	$strSQL .= $strCriteria." ORDER BY $orderColumn LIMIT %d, %d";
	$strSQLFilteredTotal .= $strCriteria;
	if (!empty($searchQuery)){
		if (empty($idpelanggan)) {
			$result = db_query(
				$strSQL,
				$tglAwal,
				$tglAkhir,
				$searchQuery,
				$searchQuery,
				$searchQuery,
				$searchQuery,
				$searchQuery,
				$searchQuery,
				$firstRecord,
				$lastRecord
			);
			$recordsFiltered = db_result(
				db_query(
					$strSQLFilteredTotal,
					$tglAwal,
					$tglAkhir,
					$searchQuery,
					$searchQuery,
					$searchQuery,
					$searchQuery,
					$searchQuery,
					$searchQuery
				)
			);
		}else{
			$result = db_query(
				$strSQL,
				$tglAwal,
				$tglAkhir,
				$idpelanggan,
				$searchQuery,
				$searchQuery,
				$searchQuery,
				$searchQuery,
				$searchQuery,
				$searchQuery,
				$firstRecord,
				$lastRecord
			);
			$recordsFiltered = db_result(
				db_query(
					$strSQLFilteredTotal,
					$tglAwal,
					$tglAkhir,
					$idpelanggan,
					$searchQuery,
					$searchQuery,
					$searchQuery,
					$searchQuery,
					$searchQuery,
					$searchQuery
				)
			);
		}
	}else{
		if (empty($idpelanggan)) {
			$result = db_query($strSQL, $tglAwal, $tglAkhir, $firstRecord, $lastRecord);
			$recordsFiltered = db_result(db_query($strSQLFilteredTotal, $tglAwal, $tglAkhir));
		}else{
			$result = db_query($strSQL, $tglAwal, $tglAkhir, $idpelanggan, $firstRecord, $lastRecord);
			$recordsFiltered = db_result(db_query($strSQLFilteredTotal, $idpelanggan, $tglAwal, $tglAkhir));
		}
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
		$rowData[] = $data->namapelanggan;
		$tombolprint = "<img title=\"Klik untuk mencetak nota penjualan\" onclick=\"print_penjualan(".$data->idpenjualan.",'".$data->nonota."');\" src=\"$baseDirectory/misc/media/images/print.png\" width=\"22\">";
		$rowData[] = $tombolprint;
		$rowData[] = $data->idpenjualan;
		$output[] = $rowData;
	}
	if (empty($idpelanggan)) {
		$recordsTotal = db_result(
			db_query(
				"SELECT COUNT(idpenjualan) FROM penjualan WHERE tglpenjualan BETWEEN '%s' AND '%s'",
				$tglAwal,
				$tglAkhir
			)
		);
	}else{
		$recordsTotal = db_result(
			db_query(
				"SELECT COUNT(idpenjualan) FROM penjualan WHERE tglpenjualan BETWEEN '%s' AND '%s' AND idpelanggan=%d",
				$tglAwal,
				$tglAkhir,
				$idpelanggan
			)
		);
	}
	return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => $output,
			"sql"			  => $strSQL,
			"tglawal"		  => $tglAwal,
			"tglakhir"		  => $tglAkhir,
		);
}

function serverSidePenjualan2($request){
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$tglAwal = $_REQUEST['tglawal'].' 00:00';
	$tglAkhir = $_REQUEST['tglakhir'].' 23:59';
	$idSupplier = $_REQUEST['idsupplier'];
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
	if (!empty($idSupplier)){
		$strSQL .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' AND prod.idsupplier = %d ";
	}else{
		$strSQL .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' ";
	}
	$strSQLFilteredTotal .= "LEFT JOIN penjualan AS penj ON detail.idpenjualan = penj.idpenjualan ";
	$strSQLFilteredTotal .= "LEFT JOIN product AS prod ON detail.idproduct = prod.idproduct ";
	$strSQLFilteredTotal .= "LEFT JOIN supplier AS supp ON prod.idsupplier = supp.idsupplier ";
	if (!empty($idSupplier)){
		$strSQLFilteredTotal .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' AND prod.idsupplier = %d ";
	}else {
		$strSQLFilteredTotal .= "WHERE penj.tglpenjualan BETWEEN '%s' AND '%s' ";
	}
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (prod.barcode LIKE '%%%s%%' OR prod.namaproduct LIKE '%%%s%%' ";
		$strCriteria .= "OR supp.namasupplier LIKE '%%%s%%' ";
		$strCriteria .= ")";
	}
	$strSQL .= $strCriteria." GROUP BY detail.idproduct ORDER BY $orderColumn LIMIT %d, %d";
	$strSQLFilteredTotal .= $strCriteria." GROUP BY detail.idproduct";
	if (!empty($searchQuery)){
		if (!empty($idSupplier)){
			$result = db_query(
				$strSQL,
				$tglAwal,
				$tglAkhir,
				$idSupplier,
				$searchQuery,
				$searchQuery,
				$searchQuery,
				$firstRecord,
				$lastRecord
			);
			$recordsFiltered = db_result(
				db_query($strSQLFilteredTotal, $tglAwal, $tglAkhir, $idSupplier, $searchQuery, $searchQuery, $searchQuery)
			);
		}else {
			$result = db_query(
				$strSQL,
				$tglAwal,
				$tglAkhir,
				$searchQuery,
				$searchQuery,
				$searchQuery,
				$firstRecord,
				$lastRecord
			);
			$recordsFiltered = db_result(
				db_query($strSQLFilteredTotal, $tglAwal, $tglAkhir, $searchQuery, $searchQuery, $searchQuery)
			);
		}
	}else{
		if (!empty($idSupplier)) {
			$result = db_query($strSQL, $tglAwal, $tglAkhir, $idSupplier, $firstRecord, $lastRecord);
			$recordsFiltered = db_result(db_query($strSQLFilteredTotal, $tglAwal, $tglAkhir, $idSupplier));
		}else{
			$result = db_query($strSQL, $tglAwal, $tglAkhir, $firstRecord, $lastRecord);
			$recordsFiltered = db_result(db_query($strSQLFilteredTotal, $tglAwal, $tglAkhir));
		}
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
		$tombolselesai = "<img title=\"Laundry sudah diambil\" src=\"$baseDirectory/misc/media/images/checks.png\" width=\"22\">";
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
function serverSideCustomerOrder($request){
	global $baseDirectory;
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$tglAwal = $_REQUEST['tglawal'].' 00:00';
	$tglAkhir = $_REQUEST['tglakhir'].' 23:59';
	$searchQuery = $searchArray['value'];
	$arrayColumn = array(
		5 => 'customerorder.nonota',
		7 => 'tanggal',
		9 => 'total',
		10 => 'customerorder.carabayar',
		11 => 'customerorder.bayar',
		14 => 'plg.namapelanggan',
		15 => 'customerorder.status_order',
		16 => 'perkiraan_ambil',
		17 => 'customerorder.keterangan',
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
	$strSQL = "SELECT customerorder.id,customerorder.nonota,SUBSTR(customerorder.tglorder,1,10) AS tanggal,";
	$strSQL .= "SUBSTR(customerorder.tglorder,11,9) AS waktu, customerorder.idpemakai,";
	$strSQL .= "customerorder.total,";
	$strSQL .= "(SELECT MAX(perkiraan_ambil) FROM detailcustomerorder WHERE ";
	$strSQL .= "idcustomerorder = customerorder.id) AS perkiraan_ambil,";
	$strSQL .= "customerorder.carabayar, customerorder.bayar, customerorder.status_order, ";
	$strSQL .= "plg.namapelanggan, customerorder.keterangan, user.name ";
	$strSQL .= "FROM customer_order AS customerorder ";
	$strSQL .= "LEFT JOIN cms_users AS user ON user.uid = customerorder.idpemakai ";
	$strSQL .= "LEFT JOIN pelanggan AS plg ON plg.idpelanggan = customerorder.idpelanggan ";
	$strSQL .= "WHERE customerorder.tglorder BETWEEN '%s' AND '%s' ";
	$strSQLFilteredTotal = "SELECT COUNT(customerorder.id) ";
	$strSQLFilteredTotal .= "FROM customer_order AS customerorder ";
	$strSQLFilteredTotal .= "LEFT JOIN cms_users AS user ON user.uid = customerorder.idpemakai ";
	$strSQLFilteredTotal .= "LEFT JOIN pelanggan AS plg ON plg.idpelanggan = customerorder.idpelanggan ";
	$strSQLFilteredTotal .= "WHERE customerorder.tglorder BETWEEN '%s' AND '%s' ";
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (customerorder.nonota LIKE '%%%s%%' OR SUBSTR(customerorder.tglorder,1,10) LIKE '%%%s%%' ";
		$strCriteria .= "OR SUBSTR(customerorder.tglorder,11,9) LIKE '%%%s%%' OR user.name LIKE '%%%s%%' ";
		$strCriteria .= "OR plg.namapelanggan LIKE '%%%s%%' OR customerorder.carabayar LIKE '%%%s%%' ";
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
		$tomboldetail = "<img title=\"Klik untuk melihat detail customer order\" onclick=\"view_detail(".$data->id.",'".$data->nonota."');\" src=\"$baseDirectory/misc/media/images/forward_enabled.ico\" width=\"22\">";
		$tombolambil = "<img title=\"Klik untuk mengisi form pengambilan customer order\" onclick=\"pickup_customerorder(".$data->id.",'".$data->nonota."');\" src=\"$baseDirectory/misc/media/images/update.ico\" width=\"22\">";
		$tombolhapus = "<img title=\"Klik untuk menghapus customer order\" onclick=\"delete_customerorder(".$data->id.",'".$data->nonota."');\" src=\"$baseDirectory/misc/media/images/del.ico\" width=\"22\">";
		$tombolprint = "<img title=\"Klik untuk mencetak customer order\" onclick=\"print_customerorder(".$data->id.",'".$data->nonota."');\" src=\"$baseDirectory/misc/media/images/print.png\" width=\"22\">";
		$tombolselesai = "<img title=\"Customer order sudah diambil\" src=\"$baseDirectory/misc/media/images/checks.png\" width=\"22\">";
		$tombolprintproduksi = "<img title=\"Klik untuk mencetak keperluan produksi\" onclick=\"print_production(".$data->id.",'".$data->nonota."');\" src=\"$baseDirectory/misc/media/images/print-production.png\" width=\"22\">";
		$rowData[] = $tomboldetail;
		if ($data->status_order == 0 || $data->status_order == 1){
			$rowData[] = $tombolambil;
		}else{
			$rowData[] = $tombolselesai;
		}
		$rowData[] = $tombolprint;
		$rowData[] = $tombolhapus;
		$rowData[] = '<div id="'.$data->nonota.'" class="barcode-place"></div>';
		$rowData[] = $data->nonota;
		$indexhari = date('w', strtotime($data->tanggal));
		$rowData[] = $arrayhari[$indexhari];
		$rowData[] = date('d-m-Y', strtotime($data->tanggal));
		$rowData[] = $data->waktu;
		$rowData[] = number_format($data->total,0,",",".");
		$rowData[] = $data->carabayar;
		$rowData[] = number_format($data->bayar,0,",",".");
		$sisaPembayaran = $data->total - $data->bayar;
		$rowData[] = number_format($sisaPembayaran,0,",",".");
		$rowData[] = $data->name;
		$rowData[] = $data->namapelanggan;
		if ($data->status_order == 0){
			$rowData[] = 'BELUM DIAMBIL';
		}else if ($data->status_order == 1){
			$rowData[] = 'DIAMBIL SEBAGIAN';
		}else if ($data->status_order == 2){
			$rowData[] = 'SUDAH DIAMBIL';
		}
		$rowData[] = date('d-m-Y H:i', $data->perkiraan_ambil);
		$rowData[] = $data->keterangan;
		$rowData[] = $tombolprintproduksi;
		$rowData[] = $data->id;
		$output[] = $rowData;
	}
	$recordsTotal = db_result(db_query("SELECT COUNT(id) FROM customer_order WHERE tglorder BETWEEN '%s' AND '%s'",$tglAwal,$tglAkhir));
	return array(
		"draw"            => isset ( $request['draw'] ) ?
			intval( $request['draw'] ) :
			0,
		"recordsTotal"    => intval( $recordsTotal ),
		"recordsFiltered" => intval( $recordsFiltered ),
		"data"            => $output,
		"order"           => $orderColumn,
	);
}
function arrayHariSS(){
    $hari_array = array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');
    return $hari_array;
}
function kategoriPengeluaran($request){
	global $baseDirectory;
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$searchQuery = $searchArray['value'];
	$arrayColumn = array(
		'id','kategori','jeniskategori','keterangan'
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
	$strSQL = "SELECT id, kategori, jeniskategori, keterangan, created, changed, uid ";
	$strSQLFilteredTotal = "SELECT COUNT(id) ";
	$strSQL .= "FROM cms_kategoripengeluaran ";
	$strSQLFilteredTotal .= "FROM cms_kategoripengeluaran ";
	$strSQL .= "WHERE 1=1 ";
	$strSQLFilteredTotal .= "WHERE 1=1 ";
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (kategori LIKE '%%%s%%' OR keterangan LIKE '%%%s%%' )";
	}
	$strSQL .= $strCriteria." ORDER BY $orderColumn LIMIT %d, %d";
	$strSQLFilteredTotal .= $strCriteria;
	if (!empty($searchQuery)){
		$result = db_query($strSQL,$searchQuery,$searchQuery,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$searchQuery,$searchQuery));
	}else{
		$result = db_query($strSQL,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal));
	}
	$output = array();
	$jenisKategori = array('Pengeluaran', 'Pemasukan');
	while ($data = db_fetch_object($result)){
		$rowData = array();
		$editbutton = '<img title="Klik untuk mengubah kategori pengeluaran" onclick="edit_kategori('.$data->id.', this.parentNode.parentNode);" src="'.$baseDirectory.'/misc/media/images/edit.ico" width="22">';
		$rowData[] = $editbutton;
		$rowData[] = $data->kategori;
		$rowData[] = $jenisKategori[$data->jeniskategori];
		$rowData[] = $data->keterangan;
		$rowData[] = $data->jeniskategori;
		$rowData[] = $data->id;
		$output[] = $rowData;
	}
	$recordsTotal = db_result(db_query("SELECT COUNT(id) FROM cms_kategoripengeluaran"));
	return array(
		"draw"            => isset ( $request['draw'] ) ?
			intval( $request['draw'] ) :
			0,
		"recordsTotal"    => intval( $recordsTotal ),
		"recordsFiltered" => intval( $recordsFiltered ),
		"data"            => $output
	);
}
function pengeluaran($request){
	global $baseDirectory;
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$searchQuery = $searchArray['value'];
	$arrayColumn = array(
		3 => 'pengeluaran.tglpengeluaran',
		4 => 'pengeluaran.kategori',
		5 => 'pengeluaran.keterangan',
		6 => 'pengeluaran.nilai'
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
	$strSQL = "SELECT pengeluaran.id, pengeluaran.keterangan, pengeluaran.kategori, ";
	$strSQL .= "pengeluaran.nilai, pengeluaran.tglpengeluaran, pengeluaran.created, ";
	$strSQL .= "pengeluaran.changed, pengeluaran.uid, ";
	$strSQL .= "katpengeluaran.kategori AS kategori_title ";
	$strSQLFilteredTotal = "SELECT COUNT(pengeluaran.id) ";
	$strSQL .= "FROM cms_pengeluaran AS pengeluaran ";
	$strSQL .= "LEFT JOIN cms_kategoripengeluaran AS katpengeluaran ";
	$strSQL .= "ON pengeluaran.kategori=katpengeluaran.id ";
	$strSQLFilteredTotal .= "FROM cms_pengeluaran AS pengeluaran ";
	$strSQLFilteredTotal .= "LEFT JOIN cms_kategoripengeluaran AS katpengeluaran ";
	$strSQLFilteredTotal .= "ON pengeluaran.kategori=katpengeluaran.id ";
	$strSQLFilteredTotal .= "WHERE 1=1 ";
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (katpengeluaran.kategori LIKE '%%%s%%' OR ";
		$strCriteria .= "katpengeluaran.keterangan LIKE '%%%s%%' OR ";
		$strCriteria .= "pengeluaran.keterangan LIKE '%%%s%%' OR ";
		$strCriteria .= "SUBSTR(pengeluaran.tglpengeluaran,1,10) LIKE '%%%s%%'  OR ";
		$strCriteria .= "pengeluaran.nilai LIKE '%%%s%%'";
		$strCriteria .= ")";
	}
	$strSQL .= $strCriteria." ORDER BY $orderColumn LIMIT %d, %d";
	$strSQLFilteredTotal .= $strCriteria;
	if (!empty($searchQuery)){
		$result = db_query($strSQL,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery));
	}else{
		$result = db_query($strSQL,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal));
	}
	$output = array();
	$arrayhari = arrayHariSS();
	while ($data = db_fetch_object($result)){
		$rowData = array();
		$editbutton = '<img title="Klik untuk mengubah pengeluaran" onclick="edit_pengeluaran('.$data->id.', this.parentNode.parentNode);" src="'.$baseDirectory.'/misc/media/images/edit.ico" width="22">';
		$deletebutton = '<img title="Klik untuk menghapus pengeluaran" onclick="hapus_pengeluaran('.$data->id.');" src="'.$baseDirectory.'/misc/media/images/del.ico" width="22">';
		$rowData[] = $editbutton;
		$rowData[] = $deletebutton;
		$index_hari = date('w', $data->tglpengeluaran);
		$tglpengeluaran = date('Y-m-d', $data->tglpengeluaran);
		$rowData[] = $arrayhari[$index_hari];
		$rowData[] = $tglpengeluaran;
		$rowData[] = $data->kategori_title;
		$rowData[] = $data->keterangan;
		$rowData[] = number_format($data->nilai,0,',','.');
		$rowData[] = $data->kategori;
		$output[] = $rowData;
	}
	$recordsTotal = db_result(db_query("SELECT COUNT(id) FROM cms_pengeluaran"));
	return array(
		"draw"            => isset ( $request['draw'] ) ?
			intval( $request['draw'] ) :
			0,
		"recordsTotal"    => intval( $recordsTotal ),
		"recordsFiltered" => intval( $recordsFiltered ),
		"data"            => $output
	);
}
function pemasukan($request){
	global $baseDirectory;
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$searchQuery = $searchArray['value'];
	$arrayColumn = array(
		3 => 'pemasukan.tglpemasukan',
		4 => 'pemasukan.kategori',
		5 => 'pemasukan.keterangan',
		6 => 'pemasukan.nilai'
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
	$strSQL = "SELECT pemasukan.id, pemasukan.keterangan, pemasukan.kategori, ";
	$strSQL .= "pemasukan.nilai, pemasukan.tglpemasukan, pemasukan.created, ";
	$strSQL .= "pemasukan.changed, pemasukan.uid, ";
	$strSQL .= "katpemasukan.kategori AS kategori_title ";
	$strSQLFilteredTotal = "SELECT COUNT(pemasukan.id) ";
	$strSQL .= "FROM cms_pemasukan AS pemasukan ";
	$strSQL .= "LEFT JOIN cms_kategoripengeluaran AS katpemasukan ";
	$strSQL .= "ON pemasukan.kategori=katpemasukan.id ";
	$strSQLFilteredTotal .= "FROM cms_pemasukan AS pemasukan ";
	$strSQLFilteredTotal .= "LEFT JOIN cms_kategoripengeluaran AS katpemasukan ";
	$strSQLFilteredTotal .= "ON pemasukan.kategori=katpemasukan.id ";
	$strSQLFilteredTotal .= "WHERE 1=1 ";
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (katpemasukan.kategori LIKE '%%%s%%' OR ";
		$strCriteria .= "katpemasukan.keterangan LIKE '%%%s%%' OR ";
		$strCriteria .= "pemasukan.keterangan LIKE '%%%s%%' OR ";
		$strCriteria .= "SUBSTR(pemasukan.tglpemasukan,1,10) LIKE '%%%s%%'  OR ";
		$strCriteria .= "pemasukan.nilai LIKE '%%%s%%'";
		$strCriteria .= ")";
	}
	$strSQL .= $strCriteria." ORDER BY $orderColumn LIMIT %d, %d";
	$strSQLFilteredTotal .= $strCriteria;
	if (!empty($searchQuery)){
		$result = db_query($strSQL,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$searchQuery,$searchQuery,$searchQuery,$searchQuery,$searchQuery));
	}else{
		$result = db_query($strSQL,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal));
	}
	$output = array();
	$arrayhari = arrayHariSS();
	while ($data = db_fetch_object($result)){
		$rowData = array();
		$editbutton = '<img title="Klik untuk mengubah pemasukan" onclick="edit_pemasukan('.$data->id.', this.parentNode.parentNode);" src="'.$baseDirectory.'/misc/media/images/edit.ico" width="22">';
		$deletebutton = '<img title="Klik untuk menghapus pemasukan" onclick="hapus_pemasukan('.$data->id.');" src="'.$baseDirectory.'/misc/media/images/del.ico" width="22">';
		$rowData[] = $editbutton;
		$rowData[] = $deletebutton;
		$index_hari = date('w', $data->tglpemasukan);
		$tglpemasukan = date('Y-m-d', $data->tglpemasukan);
		$rowData[] = $arrayhari[$index_hari];
		$rowData[] = $tglpemasukan;
		$rowData[] = $data->kategori_title;
		$rowData[] = $data->keterangan;
		$rowData[] = number_format($data->nilai,0,',','.');
		$rowData[] = $data->kategori;
		$output[] = $rowData;
	}
	$recordsTotal = db_result(db_query("SELECT COUNT(id) FROM cms_pemasukan"));
	return array(
		"draw"            => isset ( $request['draw'] ) ?
			intval( $request['draw'] ) :
			0,
		"recordsTotal"    => intval( $recordsTotal ),
		"recordsFiltered" => intval( $recordsFiltered ),
		"data"            => $output
	);
}
function serverSideGetProduct($request){
	$items = array();
	if ($_GET["term"]){
		$KATACARI = '%'.$_GET["term"].'%';
		$result = db_query("SELECT idproduct,barcode, alt_code, namaproduct, stok, hargajual,hargapokok,idkategori FROM product WHERE alt_code LIKE '%s' OR barcode LIKE '%s' OR UPPER(namaproduct) LIKE '%s' LIMIT 50",$KATACARI,$KATACARI,$KATACARI);
		$items = array();
		while ($data = db_fetch_object($result)) {
			$diskon = 0;
			if ($data->idproduct) {
				$idpelanggan = 0;
				if (isset($_GET["idpelanggan"])){
					$idpelanggan = $_GET["idpelanggan"];
				}
				$result2 = db_query(
					"SELECT besardiskon FROM diskonkategori WHERE idpelanggan='%d' AND idkategori='%d'",
					$idpelanggan,
					$data->idkategori
				);
				$datadiskon = db_fetch_object($result2);
				if (!empty($datadiskon) && $datadiskon->besardiskon >= 0) {
					$diskon = $datadiskon->besardiskon;
				}
			}
			$items[] = array(
				'value' => $data->namaproduct,
				'barcode'   => $data->barcode,
				'alt_code'  => $data->alt_code,
				'hargajual' => $data->hargajual,
				'hargapokok' => $data->hargapokok,
				'diskon' => $diskon,
				'id' => $data->idproduct,
			);
		}
	}
	return $items;
}
function serverSideGetOneProduct($request){
	$items = array();
	if ($_GET["term"]){
		$KATACARI = '%'.$_GET["term"].'%';
		$result = db_query("SELECT idproduct,barcode, alt_code, namaproduct, stok, hargajual,hargapokok FROM product WHERE alt_code LIKE '%s' OR barcode LIKE '%s' OR UPPER(namaproduct) LIKE '%s' LIMIT 1",$KATACARI,$KATACARI,$KATACARI);
		$items = array();
		while ($data = db_fetch_object($result)) {
			$diskon = 0;
			if ($data->idproduct) {
				$idpelanggan = 0;
				if (isset($_GET["idpelanggan"])){
					$idpelanggan = $_GET["idpelanggan"];
				}
				$result2 = db_query(
					"SELECT besardiskon FROM diskonkategori WHERE idpelanggan='%d' AND idkategori='%d'",
					$idpelanggan,
					$data->idkategori
				);
				$datadiskon = db_fetch_object($result2);
				if ($datadiskon->besardiskon >= 0) {
					$diskon = $datadiskon->besardiskon;
				}
			}
			$items[] = array(
				'value' => $data->namaproduct,
				'barcode'   => $data->barcode,
				'alt_code'  => $data->alt_code,
				'hargajual' => $data->hargajual,
				'hargapokok' => $data->hargapokok,
				'diskon' => $diskon,
				'id' => $data->idproduct,
			);
		}
	}
	return $items;
}
function serverSideDetailPenjualan($request){
	global $baseDirectory;
	$pageStart = $_GET['start'];
	$pageLength = $_GET['length'];
	$searchArray = $_REQUEST['search'];
	$idPenjualan = $_REQUEST['idpenjualan'];
	$searchQuery = $searchArray['value'];
	$arrayColumn = array(
		1 => 'product.barcode',
		2 => 'product.namaproduct',
		3 => 'detail.jumlah',
		4 => 'detail.hargajual',
		5 => 'detail.hargapokok',
		6 => '(detail.jumlah*detail.hargajual)',
		7 => '(detail.jumlah*detail.hargapokok)',
		8 => '(detail.jumlah*(detail.hargajual - detail.hargapokok))',
	);
	$orderColumnArray = isset($_REQUEST['order']) && !empty($_REQUEST['order']) ? $_REQUEST['order'] : array( 0 => array('column' => 1, 'dir' => 'ASC'));
	$orderColumn = $arrayColumn[$orderColumnArray[0]['column']].' '.$orderColumnArray[0]['dir'];
	if (is_null($pageStart)){
		$pageStart = 0;
	}
	if (is_null($pageLength) || $pageLength == -1){
		$pageLength = 25;
	}
	$firstRecord = $pageStart;
	$lastRecord = $pageStart + $pageLength;
	$strSQL = 'SELECT detail.iddetail,product.barcode, product.namaproduct, detail.jumlah,';
	$strSQL .= 'detail.hargapokok,detail.hargajual,(detail.hargajual*detail.jumlah) AS subtotal,';
	$strSQL .= '(detail.hargapokok*detail.jumlah) AS modal,';
	$strSQL .= '((detail.hargajual-detail.hargapokok)*detail.jumlah) AS laba ';
	$strSQL .= 'FROM detailpenjualan detail LEFT JOIN product product ';
	$strSQL .= 'ON detail.idproduct=product.idproduct ';
	$strSQL .= 'LEFT JOIN supplier supp ON product.idsupplier=supp.idsupplier ';
	$strSQL .= 'WHERE detail.idpenjualan=%d ';
	$strSQLFilteredTotal = 'SELECT COUNT(detail.iddetail) FROM ';
	$strSQLFilteredTotal .= 'detailpenjualan detail LEFT JOIN product product ';
	$strSQLFilteredTotal .= 'ON detail.idproduct=product.idproduct ';
	$strSQLFilteredTotal .= 'LEFT JOIN supplier supp ON product.idsupplier=supp.idsupplier ';
	$strSQLFilteredTotal .= 'WHERE detail.idpenjualan=%d ';
	$strCriteria = "";
	if (!empty($searchQuery)){
		$strCriteria .= "AND (product.barcode LIKE '%%%s%%' OR ";
		$strCriteria .= "product.namaproduct LIKE '%%%s%%'";
		$strCriteria .= ")";
	}
	$strSQL .= $strCriteria." ORDER BY $orderColumn LIMIT %d, %d";
	$strSQLFilteredTotal .= $strCriteria;
	if (!empty($searchQuery)) {
		$result = db_query($strSQL, $idPenjualan, $searchQuery, $searchQuery, $firstRecord, $lastRecord);
		$recordsFiltered = db_result(
			db_query($strSQLFilteredTotal, $idPenjualan, $searchQuery, $searchQuery)
		);
	}else{
		$result = db_query($strSQL,$idPenjualan,$firstRecord,$lastRecord);
		$recordsFiltered = db_result(db_query($strSQLFilteredTotal,$idPenjualan));
	}
	$output = array();
	while($data = db_fetch_object($result)){
		$rowData = array();
		$deletebutton = '<img title="Klik untuk menghapus detail penjualan" onclick="hapus_detail('.$data->iddetail.',\''.$data->namaproduct.'\');" src="'.$baseDirectory.'/misc/media/images/del.ico" width="22">';
		$rowData[] = $deletebutton;
		$rowData[] = $data->barcode;
		$rowData[] = $data->namaproduct;
		$rowData[] = $data->jumlah;
		$rowData[] = number_format($data->hargajual,0,',','.');
		$rowData[] = number_format($data->hargapokok,0,',','.');
		$rowData[] = number_format($data->subtotal,0,',','.');
		$rowData[] = number_format($data->modal,0,',','.');
		$rowData[] = number_format($data->laba,0,',','.');
		$rowData[] = $data->iddetail;
		$output[] = $rowData;
	}
	$recordsTotal = db_result(db_query("SELECT COUNT(iddetail) FROM detailpenjualan"));
	return array(
		"draw"            => isset ( $request['draw'] ) ?
			intval( $request['draw'] ) :
			0,
		"recordsTotal"    => intval( $recordsTotal ),
		"recordsFiltered" => intval( $recordsFiltered ),
		"data"            => $output
	);
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
}else if($_GET['request_data'] == 'kategoripengeluaran'){
	$returnArray = kategoriPengeluaran($_GET);
}else if($_GET['request_data'] == 'pengeluaran'){
	$returnArray = pengeluaran($_GET);
}else if($_GET['request_data'] == 'pemasukan'){
	$returnArray = pemasukan($_GET);
}else if($_GET['request_data'] == 'customerorder'){
	$returnArray = serverSideCustomerOrder($_GET);
}else if($_GET['request_data'] == 'getproduct'){
	$returnArray = serverSideGetProduct($_GET);
}else if($_GET['request_data'] == 'getproductbarcode'){
	$returnArray = serverSideGetOneProduct($_GET);
}else if($_GET['request_data'] == 'detailpenjualan'){
	$returnArray = serverSideDetailPenjualan($_GET);
}
echo json_encode($returnArray);
?>