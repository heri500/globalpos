<?php

/**
 * @param null       $idPelanggan
 * @param bool|false $realData
 *
 * @return bool|int
 */
function getTotalHutangLatest($idPelanggan = null, $realData = false){
	$totalHutangLatest = 0;
	if (!empty($idPelanggan)){
		if ($realData){
			$strSqlTotalHutang = "SELECT SUM(besarhutang) AS total_hutang FROM detailpiutang WHERE idpelanggan=%d";
			$strSqlTotalBayar = "SELECT SUM(pembayaran) AS total_bayar FROM historypembayaran WHERE idpelanggan=%d";
			$strSqlTotalPemakaianDeposit = "SELECT SUM(penggunaan) AS total_guna FROM historypenggunaandeposit WHERE idpelanggan=%d";
			$totalHutang = db_result(db_query($strSqlTotalHutang, $idPelanggan));
			$totalBayar = db_result(db_query($strSqlTotalBayar, $idPelanggan));
			$totalGuna = db_result(db_query($strSqlTotalPemakaianDeposit, $idPelanggan));
		}else{
			$strSqlTotalHutang = "SELECT besarhutang FROM piutang WHERE idpelanggan=%d";
			$totalHutang = db_result(db_query($strSqlTotalHutang, $idPelanggan));
			$totalBayar = 0;
			$totalGuna = 0;
		}
		$totalHutangLatest = $totalHutang - $totalBayar + $totalGuna;
	}
	return $totalHutangLatest;
}
function getTotalHutangPelanggan(){
	$strSql = 'SELECT idpelanggan FROM pelanggan';
	$result = db_query($strSql);
	$totalHutang = 0;
	while ($data = db_fetch_object($result)){
		if (!empty($data->idpelanggan)){
			$totalHutangLatest = getTotalHutangLatest($data->idpelanggan);
		}
		$totalHutang = $totalHutang + $totalHutangLatest;
	}
	return $totalHutang;
}

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
function cekPiutangExists($idpelanggan = null){
	$piutangExist = 0;
	if (!empty($idpelanggan)){
		$piutangExist = db_result(
			db_query(
				"SELECT COUNT(idpelanggan) FROM piutang WHERE idpelanggan=%d",
				$idpelanggan
			)
		);
	}
	return $piutangExist;
}
/**
 * @param null $idPelanggan
 *
 * @return int
 */
function syncHutangPelanggan($idPelanggan = null){
	global $user;
	$strSql = 'SELECT idpelanggan FROM pelanggan';
	if (!empty($idPelanggan)){
		$strSql .= ' WHERE idpelanggan=%d';
		$result = db_query($strSql, $idPelanggan);
	}else{
		$result = db_query($strSql);
	}
	$totalSync = 0;
	while ($data = db_fetch_object($result)){
		$totalHutang = 0;
		if (!empty($data->idpelanggan)){
			$totalHutang = getTotalHutangLatest($data->idpelanggan, true);
		}
		$piutangExist = cekPiutangExists($data->idpelanggan);
		if ($piutangExist){
			$strSqlUpdate = 'UPDATE piutang SET besarhutang=\'%f\' WHERE idpelanggan=%d';
			db_query($strSqlUpdate, $totalHutang, $data->idpelanggan);
		}else{
			$strSqlInsert = "INSERT INTO piutang (idpelanggan, besarhutang, pembayaranterakhir,uid) ";
			$strSqlInsert .= "VALUES (%d, '%f','%f', %d)";
			db_query(
				$strSqlInsert,
				$data->idpelanggan,$totalHutang,-1*($totalHutang), $user->uid
			);
		}
		$totalSync++;
	}
	return $totalSync;
}