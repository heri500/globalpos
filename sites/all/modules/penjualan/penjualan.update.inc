<?php

function update_penjualan(){
	if (isset($_POST['id'])){
		if (isset($_POST['tanggaljual'])){
			$idpenjualan = explode('-', $_POST['id']);
			$jampenjualan = $_POST['jampenjualan'];
			$tgljualbaru = date('Y-m-d',strtotime($_POST['tanggaljual'])).' '.$jampenjualan;
			db_query("UPDATE penjualan SET tglpenjualan='%s' WHERE idpenjualan=%d", $tgljualbaru, $idpenjualan[1]);
			if ($_POST['ubah'] == 'tanggal'){
				print $_POST['tanggaljual'];
			}else{
				print $jampenjualan;
			}
		}
	}else{
		$updateddata = new stdClass();
		$updateddata->error = 'ID Penjualan Empty';
		print $updateddata->error;
	}
	exit();	
}
function update_laundry(){
	if (isset($_POST['id'])){
		if (isset($_POST['tanggaljual'])){
			$idtitipanlaundry = explode('-', $_POST['id']);
			$jampenjualan = $_POST['jampenjualan'];
			$tgljualbaru = date('Y-m-d',strtotime($_POST['tanggaljual'])).' '.$jampenjualan;
			db_query("UPDATE titipanlaundry SET tglpenjualan='%s' WHERE idtitipanlaundry=%d", $tgljualbaru, $idtitipanlaundry[1]);
			if ($_POST['ubah'] == 'tanggal'){
				print $_POST['tanggaljual'];
			}else{
				print $jampenjualan;
			}
		}
	}else{
		$updateddata = new stdClass();
		$updateddata->error = 'ID titipanlaundry Empty';
		print $updateddata->error;
	}
	exit();	
}
function update_detail_customer_order(){
	if (isset($_POST['id'])){
		if (isset($_POST['qty'])){
			$iddetailorder = explode('-', $_POST['id']);
			$strSQL = 'SELECT jumlah FROM detailcustomerorder WHERE id=%d';
			$qtyLama = db_result(db_query($strSQL, $iddetailorder[1]));
			$selisih = $qtyLama - $_POST['qty'];
			$strSQL = 'SELECT idcustomerorder FROM detailcustomerorder WHERE id=%d';
			$idCustomerOrder = db_result(db_query($strSQL, $iddetailorder[1]));
			$strSQL = 'UPDATE detailcustomerorder SET jumlah=\'%f\', sisa = sisa-\'%f\', outstanding = outstanding-\'%f\' ';
			$strSQL .= 'WHERE id=%d';
			db_query($strSQL,$_POST['qty'],$selisih,$selisih,$iddetailorder[1]);
			$strSQL = 'SELECT SUM(hargajual*jumlah) AS total_order FROM detailcustomerorder WHERE idcustomerorder=%d';
			$totalOrder = db_result(db_query($strSQL,$idCustomerOrder));
			$strSQL = 'SELECT SUM(hargapokok*jumlah) AS total_modal FROM detailcustomerorder WHERE idcustomerorder=%d';
			$totalModal = db_result(db_query($strSQL,$idCustomerOrder));
			$strSQL = 'UPDATE customer_order SET total=\'%f\', totalmodal=\'%f\' WHERE id=%d';
			db_query($strSQL,$totalOrder,$totalModal,$idCustomerOrder);
			print $_POST['qty'];
		}
	}
	exit();
}
function insert_detail_customer_order(){
	$executedSQL = array();
	if (isset($_POST['idcustomerorder']) && isset($_POST['idproduk']) && isset($_POST['hargajual']) && isset($_POST['qty'])){
		print $_POST['idcustomerorder'].' '.$_POST['idproduk'].' '.$_POST['hargajual'].' '.$_POST['qty'];
		if ($_POST['idcustomerorder'] > 0 && $_POST['idproduk'] > 0 && $_POST['hargajual'] > 0 && $_POST['qty'] > 0){
			$strSQL = 'SELECT id FROM detailcustomerorder WHERE idproduct=%d AND idcustomerorder=%d LIMIT 1';
			$idDetailExist = db_result(db_query($strSQL, $_POST['idproduk'],$_POST['idcustomerorder']));
			if ($idDetailExist > 0){
				$strSQL = 'UPDATE detailcustomerorder SET jumlah = jumlah + \'%f\', hargajual=\'%f\', ';
				$strSQL .= 'hargapokok=\'%f\', sisa = sisa + \'%f\', outstanding = outstanding + \'%f\', ';
				$strSQL .= 'masuk=%d WHERE id=%d';
				$executedSQL[] = $strSQL;
				db_query(
					$strSQL,$_POST['qty'],$_POST['hargajual'],$_POST['hargapokok'],
					$_POST['qty'],$_POST['qty'],time(),$idDetailExist
				);
			}else{
				$strSQL = 'SELECT lead_time FROM product WHERE idproduct=%d';
				$leadTime = db_result(db_query($strSQL,$_POST['idproduk']));
				$perkiraanAmbil = mktime(date('H'),date('i') + $leadTime,0,date('n'),date('d'),date('Y'));
				$detailBarcode = createEAN13Code(getRandomString(9));
				$strSQL = 'INSERT INTO detailcustomerorder (idcustomerorder, idproduct, detailbarcode, jumlah, ';
				$strSQL .= 'hargapokok, hargajual, sisa, masuk, outstanding, perkiraan_ambil) VALUES ';
				$strSQL .= '(%d, %d, \'%s\', \'%f\', \'%f\', \'%f\', \'%f\', %d, \'%f\', %d)';
				$executedSQL[] = $strSQL;
				db_query(
					$strSQL,$_POST['idcustomerorder'],$_POST['idproduk'],$detailBarcode,$_POST['qty'],
					$_POST['hargajual'],$_POST['hargapokok'],$_POST['qty'],time(),$_POST['qty'],$perkiraanAmbil
				);
			}
			$strSQL = 'SELECT SUM(hargajual*jumlah) AS total_order FROM detailcustomerorder WHERE idcustomerorder=%d';
			$totalOrder = db_result(db_query($strSQL,$_POST['idcustomerorder']));
			$strSQL = 'SELECT SUM(hargapokok*jumlah) AS total_modal FROM detailcustomerorder WHERE idcustomerorder=%d';
			$totalModal = db_result(db_query($strSQL,$_POST['idcustomerorder']));
			$strSQL = 'UPDATE customer_order SET total=\'%f\', totalmodal=\'%f\' WHERE id=%d';
			db_query($strSQL,$totalOrder,$totalModal,$_POST['idcustomerorder']);
		}
		print json_encode($executedSQL);
	}
	exit();
}