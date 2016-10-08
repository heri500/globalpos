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