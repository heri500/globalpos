<?php

function getTotalHutang($idpelanggan = null){
	$totalhutang = 0;
	if (!empty($idpelanggan)){
		$result = db_result(db_query("SELECT SUM(a.besarhutang) FROM 
		detailpiutang a, penjualan b WHERE a.idpenjualan = b.idpenjualan AND a.idpelanggan='%d'",
		$idpelanggan));
		$totalhutang = $result;
	}
	return $totalhutang;
}
function getTotalPembayaran($idpelanggan = null){
	$totalpembayaran = 0;
	if (!empty($idpelanggan)){
		$result = db_result(db_query("SELECT SUM(pembayaran) FROM historypembayaran WHERE idpelanggan='%d'",$idpelanggan));
		$totalpembayaran = $result;
	}
	return $totalpembayaran;
}
function getTotalPenggunaanDeposit($idpelanggan = null){
	$totalpenggunaan = 0;
	if (!empty($idpelanggan)){
		$result = db_result(db_query("SELECT SUM(penggunaan) FROM historypenggunaandeposit WHERE idpelanggan='%d'",$idpelanggan));
		$totalpenggunaan = $result;
	}
	return $totalpenggunaan;
}