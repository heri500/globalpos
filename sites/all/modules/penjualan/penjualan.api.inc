<?php
function JAM_KERJA_LAUNDRY(){
	return array(
		'normal' => array(
			'jam_masuk' => 7, 
			'jam_keluar' => 20, 
			'total_jam' => 13
		), 
		'minggu' => array(
			'jam_masuk' => 8, 
			'jam_keluar' => 16, 
			'total_jam' => 8
		)
	);	
}
function PERLAKUAN_KEMBALIAN(){
	return array('DIKEMBALIKAN','DISUMBANGKAN','DIMASUKAN DEPOSIT');
}

function getDataLaundry($nidtitipanlaundry = null){
	$dataLaundry = null;
	if (!empty($nidtitipanlaundry)){
		$dataLaundry = array();
		$strSQL = "SELECT a.idtitipanlaundry, a.nonota, a.tglpenjualan, a.idpemakai, b.name, a.idpelanggan, a.total, 
		a.totalmodal, a.carabayar, a.bayar, a.kembali, a.nokartu, a.keterangan, a.insert_date,a.status_laundry, 
		c.namapelanggan,c.telp,c.alamat, c.email
		FROM titipanlaundry a LEFT JOIN cms_users b ON a.idpemakai = b.uid 
		LEFT JOIN pelanggan c ON a.idpelanggan = c.idpelanggan WHERE a.idtitipanlaundry=%d";
		$result = db_query($strSQL,$nidtitipanlaundry);
		$dataLaundry['info'] = db_fetch_object($result);
		$dataLaundry['detail'] = getDetailLaundry($nidtitipanlaundry);
	}
	return $dataLaundry;
}
function getDetailLaundry($nidtitipanlaundry = null){
	$detailLaundry = null;
	if (!empty($nidtitipanlaundry)){
		$detailLaundry = array();
		$strSQL = "SELECT iddetail, idtitipanlaundry, a.idproduct, jumlah, a.hargapokok, a.hargajual, 
		a.diskon, status_laundry, sisa, diambil, b.namaproduct, b.satuan, masuk, perkiraan_ambil FROM detaillaundry a LEFT JOIN product b
		ON a.idproduct = b.idproduct WHERE a.idtitipanlaundry=%d ORDER BY perkiraan_ambil DESC";
		$result = db_query($strSQL,$nidtitipanlaundry);
		while ($data = db_fetch_object($result)){
			$data->masuk = date('Y-m-d H:i', $data->masuk);
			$data->perkiraan_ambil = date('Y-m-d H:i', $data->perkiraan_ambil);
			$detailLaundry[] = $data;
		}
	}
	return $detailLaundry;
}
function getDataPenjualan($nidpenjualan = null){
	$dataPenjualan = null;
	if (!empty($nidpenjualan)){
		$dataPenjualan = array();
		$strSQL = "SELECT idpenjualan, name, nonota, tglpenjualan, idpemakai, idpelanggan, total, 
		totalmodal, carabayar, bayar, kembali, nokartu, keterangan, insert_date,idtitipanlaundry,
		perlakuankembalian FROM penjualan a LEFT JOIN cms_users b ON a.idpemakai = b.uid WHERE idpenjualan=%d";
		$result = db_query($strSQL,$nidpenjualan);
		$dataPenjualan['info'] = db_fetch_object($result);
		if (!empty($dataPenjualan['info']->idtitipanlaundry)){
			$dataPenjualan['laundry'] = getDataLaundry($dataPenjualan['info']->idtitipanlaundry);
		}
		$dataPenjualan['detail'] = getDetailPenjualan($nidpenjualan);
	}
	return $dataPenjualan;
}
function getDetailPenjualan($nidpenjualan = null){
	$detailPenjualan = null;
	if (!empty($nidpenjualan)){
		$detailPenjualan = array();
		$strSQL = "SELECT iddetail, idpenjualan, a.idproduct, jumlah, a.hargapokok, a.hargajual, 
		a.diskon, b.namaproduct, b.satuan FROM detailpenjualan a LEFT JOIN product b
		ON a.idproduct = b.idproduct WHERE a.idpenjualan=%d";
		$result = db_query($strSQL,$nidpenjualan);
		while ($data = db_fetch_object($result)){
			$detailPenjualan[] = $data;
		}
	}
	return $detailPenjualan;
}
function printOutLaundryOld(){
	drupal_add_js('misc/media/jquery-1.4.4.min.js');
	$js_path = drupal_get_path('module','penjualan').'/js/direct.print.js';
	//drupal_add_js($js_path);
	$tablePrint = '';
	$nidtitipanlaundry = isset($_GET['nidlaundry']) ? $_GET['nidlaundry'] : null;
	if (!empty($nidtitipanlaundry)){
		global $user;
		$dataLaundry = getDataLaundry($nidtitipanlaundry);
		$tablePrint = '<table style="width: 240px;border-collapse: collapse;" >';
		$tablePrint .= '<tbody>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 12px;text-align: center;border-bottom: 1px dotted #444;border-top: 1px dotted #444;">LAUNDRY IKHWAN</td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;"><span style="float: left">'.$dataLaundry['info']->tglpenjualan.'</span><span style="float: right">'.$dataLaundry['info']->nonota.'&nbsp;&nbsp;</span></td></tr>';
		$nilaiBayar = '';
		if ($dataLaundry['info']->carabayar != 'KEMUDIAN'){
			$nilaiBayar = '[Rp. '.number_format($dataLaundry['info']->bayar,0,",",".").']';	
		}
		$tablePrint .= '<tr><td colspan="3" style="font-size: 9px;text-align: center;border-bottom: 1px dotted #444;"><span style="float: left">Status : MASUK</span><span style="float: right">Bayar : '.$dataLaundry['info']->carabayar.' '.$nilaiBayar.'&nbsp;&nbsp;</span></td></tr>';
		$totalLaundry = 0;
		for ($i = 0;$i < count($dataLaundry['detail']);$i++){
			$dataDetail = $dataLaundry['detail'][$i];
			if ($dataDetail->jumlah > 1){
				$tablePrint .= '<tr><td colspan="2" style="font-size: 10px;"><span style="float: left">'.$dataDetail->jumlah.' '.$dataDetail->satuan.' X '.number_format($dataDetail->hargajual,0,",",".").'</span></td></tr>';
			}
			$tablePrint .= '<tr>';
			$tablePrint .= '<td style="font-size: 10px;width: 75%;">'.$dataDetail->namaproduct.'</td>';
			$tablePrint .= '<td style="font-size: 10px;width: 25%;text-align: right;">'.number_format($dataDetail->hargajual*$dataDetail->jumlah,0,",",".").'&nbsp;&nbsp;</td>';
			$tablePrint .= '</tr>';
			$totalLaundry = $totalLaundry + ($dataDetail->hargajual*$dataDetail->jumlah);
		}
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;border-top: 1px dotted #444;"><span style="float: left">TOTAL</span><span style="float: right">'.number_format($totalLaundry,0,",",".").'&nbsp;&nbsp;</span></td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;border-top: 1px dotted #444;"><span style="float: left"><img src="'.base_path().'misc/media/images/bbm.png" width="12" style="vertical-align:middle;">&nbsp;5AB041B8</span><span style="float: right"><img src="'.base_path().'misc/media/images/whatsapp.png" width="11" style="vertical-align:middle;">&nbsp;+628982485685&nbsp;&nbsp;</span></td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;">Jl. Babakan Madang, Ruko Victoria,&nbsp;&nbsp;</td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;border-bottom: 1px dotted #444;">Blok C No.22, Sentul City - Bogor&nbsp;&nbsp;</td></tr>';
		$tablePrint .= '</tbody>';
		$tablePrint .= '</table>';
	}
	return $tablePrint;
}
function printOutLaundry(){
	drupal_add_js('misc/media/jquery-1.4.4.min.js');
	$js_path = drupal_get_path('module','penjualan').'/js/direct.print.js';
	drupal_add_js($js_path);
	global $user;
	$tablePrint = '';
	$nidtitipanlaundry = isset($_GET['nidlaundry']) ? $_GET['nidlaundry'] : null;
	$printHeader = false;
	if (!empty($nidtitipanlaundry)){
		global $user;
		$dataLaundry = getDataLaundry($nidtitipanlaundry);
		$tablePrint = '<table style="width: 240px;border-collapse: collapse;" >';
		$tablePrint .= '<tbody>';
		if ($printHeader){
			$tablePrint .= '<tr><td colspan="3" style="font-size: 12px;text-align: center;border-bottom: 1px dotted #444;border-top: 1px dotted #444;">LAUNDRY IKHWAN</td></tr>';
			$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;border-top: 1px dotted #444;"><span style="float: left"><img src="'.base_path().'misc/media/images/bbm.png" width="12" style="vertical-align:middle;">&nbsp;5AB041B8</span><span style="float: right"><img src="'.base_path().'misc/media/images/whatsapp.png" width="11" style="vertical-align:middle;">&nbsp;+628982485685&nbsp;&nbsp;</span></td></tr>';
		}
		$tablePrint .= '<tr><td colspan="3" style="font-size: 14px;line-height: 13px;">No. <strong>'.$dataLaundry['info']->nonota.'&nbsp;&nbsp;</strong></td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 14px;line-height: 13px;"><div style="width: 30%;float: left;">Nama</div><div style="width: 70%;float: left;">: <strong>'.$dataLaundry['info']->namapelanggan.'&nbsp;&nbsp;</strong></div></td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 14px;line-height: 13px;"><div style="width: 30%;float: left;">Telp/ HP</div><div style="width: 70%;float: left;">: '.$dataLaundry['info']->telp.'&nbsp;&nbsp;</div></td></tr>';
		$waktuPejualan = explode(' ', $dataLaundry['info']->tglpenjualan);
		$tablePrint .= '<tr><td colspan="3" style="font-size: 14px;line-height: 13px;"><div style="width: 30%;float: left;">Terima</div><div style="width: 70%;float: left;"><span style="float: left;">: '.$waktuPejualan[0].'</span><span style="float: right;">'.substr($waktuPejualan[1],0,5).'&nbsp;</span></div></td></tr>';
		$waktuSelesai = explode(' ', $dataLaundry['detail'][0]->perkiraan_ambil);
		$tablePrint .= '<tr><td colspan="3" style="font-size: 14px;border-bottom: 1px dotted #444;line-height: 13px;"><div style="width: 30%;float: left;">Selesai</div><div style="width: 70%;float: left;"><span style="float: left;">: '.$waktuSelesai[0].'</span><span style="float: right;">'.$waktuSelesai[1].'&nbsp;</span></div></td></tr>';
		$totalLaundry = 0;
		$totalDiskon = 0;
		$totalItem = array();
		for ($i = 0;$i < count($dataLaundry['detail']);$i++){
			$dataDetail = $dataLaundry['detail'][$i];
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3" style="font-size: 13px;line-height: 13px;"><strong>'.$dataDetail->namaproduct.'</strong></td>';
			$tablePrint .= '</tr>';
			if ($i == (count($dataLaundry['detail']) - 1)){
				$tablePrint .= '<tr style="border-bottom: 1px dotted #444;">';
			}else{
				$tablePrint .= '<tr>';
			}
			$tablePrint .= '<td style="font-size: 13px;width:30%;line-height: 13px;">'.$dataDetail->jumlah.' '.$dataDetail->satuan.'</td>';
			if (isset($totalItem[$dataDetail->satuan])){
				$totalItem[$dataDetail->satuan] = $totalItem[$dataDetail->satuan] + $dataDetail->jumlah;
			}else{
				$totalItem[$dataDetail->satuan] = $dataDetail->jumlah;	
			}
			$tablePrint .= '<td style="font-size: 13px;width:30%;line-height: 13px;">Rp. '.number_format($dataDetail->hargajual,0,",",".").'</td>';
			$tablePrint .= '<td style="font-size: 13px;line-height: 13px;line-height: 13px;text-align: right;"><span style="float: left;">Rp.</span><span style="float: right;">'.number_format($dataDetail->hargajual*$dataDetail->jumlah,0,",",".").'&nbsp;</span></td>';
			$tablePrint .= '</tr>';
			$totalLaundry = $totalLaundry + ($dataDetail->hargajual*$dataDetail->jumlah);
			$totalDiskon = $totalDiskon + round(($dataDetail->diskon*$dataDetail->hargajual*$dataDetail->jumlah)/100,0);
		}
		$nilaiBayar = 0;
		if ($dataLaundry['info']->carabayar != 'KEMUDIAN'){
			$nilaiBayar = $dataLaundry['info']->bayar;	
		}
		$sisaYangHarusDibayar = $totalLaundry - $nilaiBayar;
		$tablePrint .= '<tr>';
		$tablePrint .= '<td colspan="2" style="font-size: 13px;text-align: right;border-top: 1px dotted #444;">TOTAL :</td>';
		$tablePrint .= '<td style="font-size: 13px;text-align: right;border-top: 1px dotted #444;text-align: right"><span style="float: left;">Rp.</span><span style="float: right;">'.number_format($totalLaundry,0,",",".").'&nbsp;</span></td>';
		$tablePrint .= '</tr>';
		if ($totalDiskon > 0){
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="2" style="font-size: 13px;text-align: right;">DISKON :</td>';
			$tablePrint .= '<td style="font-size: 13px;text-align: right;text-align: right"><span style="float: left;">Rp.</span><span style="float: right;">'.number_format($totalDiskon,0,",",".").'&nbsp;</span></td>';
			$tablePrint .= '</tr>';
		}
		$tablePrint .= '<tr>';
		$tablePrint .= '<td colspan="2" style="font-size: 13px;text-align: right;">BAYAR :</td>';
		$tablePrint .= '<td style="font-size: 13px;text-align: right;text-align: right"><span style="float: left;">Rp.</span><span style="float: right;">'.number_format($nilaiBayar,0,",",".").'&nbsp;</span></td>';
		$tablePrint .= '</tr>';
		$tablePrint .= '<tr>';
		$tablePrint .= '<td colspan="2" style="font-size: 13px;text-align: right;border-bottom: 1px dotted #444;">SISA :</td>';
		$tablePrint .= '<td style="font-size: 13px;text-align: right;border-bottom: 1px dotted #444;text-align: right"><span style="float: left;">Rp.</span><span style="float: right;">'.number_format($sisaYangHarusDibayar,0,",",".").'&nbsp;</span></td>';
		$tablePrint .= '</tr>';
		if ($printHeader){
			$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;">Jl. Babakan Madang, Ruko Victoria,&nbsp;&nbsp;</td></tr>';
			$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;border-bottom: 1px dotted #444;">Blok C No.22, Sentul City - Bogor&nbsp;&nbsp;</td></tr>';
		}
		$totalItemView = '';
		if (count($totalItem)){
			$itemCount = 0;
			foreach($totalItem as $key => $value){
				if ($itemCount == 0){
					$totalItemView = $value.' '.$key;
				}else{
					$totalItemView .= '+'.$value.' '.$key;
				}
				$itemCount++;
			}
		}
		$statusPembayaran  = '<b>LUNAS</b>';
		if ($sisaYangHarusDibayar > 0){
			$statusPembayaran = '<b>BELUM LUNAS</b>';
		}
		$keterangan = !empty($dataLaundry['info']->keterangan) ? $dataLaundry['info']->keterangan : '-';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 13px;line-height: 13px;">Total Qty Item : '.$totalItemView.'</td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 13px;line-height: 13px;">Status : '.$statusPembayaran.'</td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 13px;line-height: 13px;">Ket : '.$keterangan.'</td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 13px;line-height: 13px;">Kasir : '.$user->name.'</td></tr>';
		$tablePrint .= '</tbody>';
		$tablePrint .= '</table>';
	}
	return $tablePrint;
}
function printOutPenjualanOld(){
	$perlakuanKembalian = PERLAKUAN_KEMBALIAN();
	drupal_add_js('misc/media/jquery-1.4.4.min.js');
	$js_path = drupal_get_path('module','penjualan').'/js/direct.print.js';
	drupal_add_js($js_path);
	$tablePrint = '';
	$nidpenjualan = isset($_GET['idpenjualan']) ? $_GET['idpenjualan'] : null;
	if (!empty($nidpenjualan)){
		global $user;
		$dataPenjualan = getDataPenjualan($nidpenjualan);
		$tablePrint = '<table style="width: 240px;border-collapse: collapse;" >';
		$tablePrint .= '<tbody>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 12px;text-align: center;border-bottom: 1px dotted #444;border-top: 1px dotted #444;">LAUNDRY IKHWAN</td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;"><span style="float: left">'.$dataPenjualan['info']->tglpenjualan.'</span><span style="float: right">'.$dataPenjualan['info']->nonota.'&nbsp;&nbsp;</span></td></tr>';
		$nilaiBayar = '[Rp. '.number_format($dataPenjualan['info']->bayar,0,",",".").']';	
		if (isset($dataPenjualan['laundry'])){
			$tablePrint .= '<tr><td colspan="3" style="font-size: 9px;text-align: center;border-bottom: 1px dotted #444;"><span style="float: left">MASUK : '.$dataPenjualan['laundry']['info']->tglpenjualan.'</span><span style="float: right">KASIR : '.$dataPenjualan['laundry']['info']->name.'&nbsp;&nbsp;</span></td></tr>';
			$tablePrint .= '<tr><td colspan="3" style="font-size: 9px;text-align: center;border-bottom: 1px dotted #444;"><span style="float: left">Status : AMBIL</span><span style="float: right">Bayar : '.$dataPenjualan['info']->carabayar.' '.$nilaiBayar.'&nbsp;&nbsp;</span></td></tr>';
		}else{
			$tablePrint .= '<tr><td colspan="3" style="font-size: 9px;text-align: center;border-bottom: 1px dotted #444;"><span style="float: left">'.$dataPenjualan['info']->name.'</span><span style="float: right">Bayar : '.$dataPenjualan['info']->carabayar.' '.$nilaiBayar.'&nbsp;&nbsp;</span></td></tr>';
		}
		$totalPenjualan = 0;
		for ($i = 0;$i < count($dataPenjualan['detail']);$i++){
			$dataDetail = $dataPenjualan['detail'][$i];
			if ($dataDetail->jumlah > 1){
				$tablePrint .= '<tr><td colspan="2" style="font-size: 10px;"><span style="float: left">'.$dataDetail->jumlah.' '.$dataDetail->satuan.' X '.number_format($dataDetail->hargajual,0,",",".").'</span></td></tr>';
			}
			$tablePrint .= '<tr>';
			$tablePrint .= '<td style="font-size: 10px;width: 75%;">'.$dataDetail->namaproduct.'</td>';
			$tablePrint .= '<td style="font-size: 10px;width: 25%;text-align: right;">'.number_format($dataDetail->hargajual*$dataDetail->jumlah,0,",",".").'&nbsp;&nbsp;</td>';
			$tablePrint .= '</tr>';
			$totalPenjualan = $totalPenjualan + ($dataDetail->hargajual*$dataDetail->jumlah);
		}
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;border-top: 1px dotted #444;"><span style="float: left">TOTAL</span><span style="float: right">'.number_format($totalPenjualan,0,",",".").'&nbsp;&nbsp;</span></td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;"><span style="float: left">BAYAR</span><span style="float: right">'.number_format($dataPenjualan['info']->bayar,0,",",".").'&nbsp;&nbsp;</span></td></tr>';
		$viewperlakuankembalian = '';
		if ($dataPenjualan['info']->perlakuankembalian != 0){
			$viewperlakuankembalian = '<span style="font-size: 10px">'.$perlakuanKembalian[$dataPenjualan['info']->perlakuankembalian].'</span>';
		}
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;"><span style="float: left">KEMBALI '.$viewperlakuankembalian.'</span><span style="float: right">'.number_format($dataPenjualan['info']->kembali,0,",",".").'&nbsp;&nbsp;</span></td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;border-top: 1px dotted #444;"><span style="float: left"><img src="'.base_path().'misc/media/images/bbm.png" width="12" style="vertical-align:middle;">&nbsp;5AB041B8</span><span style="float: right"><img src="'.base_path().'misc/media/images/whatsapp.png" width="11" style="vertical-align:middle;">&nbsp;+628982485685&nbsp;&nbsp;</span></td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;">Jl. Babakan Madang, Ruko Victoria,&nbsp;&nbsp;</td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;border-bottom: 1px dotted #444;">Blok C No.22, Sentul City - Bogor&nbsp;&nbsp;</td></tr>';
		$tablePrint .= '</tbody>';
		$tablePrint .= '</table>';
	}
	return $tablePrint;
}
function printOutPenjualan(){
	$perlakuanKembalian = PERLAKUAN_KEMBALIAN();
	drupal_add_js('misc/media/jquery-1.4.4.min.js');
	$js_path = drupal_get_path('module','penjualan').'/js/direct.print.js';
	drupal_add_js($js_path);
	$tablePrint = '';
	$nidpenjualan = isset($_GET['idpenjualan']) ? $_GET['idpenjualan'] : null;
	$printHeader = false;
	if (!empty($nidpenjualan)){
		global $user;
		$dataPenjualan = getDataPenjualan($nidpenjualan);
		$tablePrint = '<table style="width: 240px;border-collapse: collapse;" >';
		$tablePrint .= '<tbody>';
		if ($printHeader){
			$tablePrint .= '<tr><td colspan="3" style="font-size: 12px;text-align: center;border-bottom: 1px dotted #444;border-top: 1px dotted #444;">LAUNDRY IKHWAN</td></tr>';
		}
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;"><span style="float: left">'.$dataPenjualan['info']->tglpenjualan.'</span><span style="float: right">'.$dataPenjualan['info']->nonota.'&nbsp;&nbsp;</span></td></tr>';
		$nilaiBayar = '[Rp. '.number_format($dataPenjualan['info']->bayar,0,",",".").']';	
		if (isset($dataPenjualan['laundry'])){
			$tablePrint .= '<tr><td colspan="3" style="font-size: 9px;text-align: center;border-bottom: 1px dotted #444;"><span style="float: left">MASUK : '.$dataPenjualan['laundry']['info']->tglpenjualan.'</span><span style="float: right">KASIR : '.$dataPenjualan['laundry']['info']->name.'&nbsp;&nbsp;</span></td></tr>';
			$tablePrint .= '<tr><td colspan="3" style="font-size: 9px;text-align: center;border-bottom: 1px dotted #444;"><span style="float: left">Status : AMBIL</span><span style="float: right">Bayar : '.$dataPenjualan['info']->carabayar.' '.$nilaiBayar.'&nbsp;&nbsp;</span></td></tr>';
		}else{
			$tablePrint .= '<tr><td colspan="3" style="font-size: 9px;text-align: center;border-bottom: 1px dotted #444;"><span style="float: left">'.$dataPenjualan['info']->name.'</span><span style="float: right">Bayar : '.$dataPenjualan['info']->carabayar.' '.$nilaiBayar.'&nbsp;&nbsp;</span></td></tr>';
		}
		$totalPenjualan = 0;
		for ($i = 0;$i < count($dataPenjualan['detail']);$i++){
			$dataDetail = $dataPenjualan['detail'][$i];
			if ($dataDetail->jumlah > 1){
				$tablePrint .= '<tr><td colspan="2" style="font-size: 10px;"><span style="float: left">'.$dataDetail->jumlah.' '.$dataDetail->satuan.' X '.number_format($dataDetail->hargajual,0,",",".").'</span></td></tr>';
			}
			$tablePrint .= '<tr>';
			$tablePrint .= '<td style="font-size: 10px;width: 75%;">'.$dataDetail->namaproduct.'</td>';
			$tablePrint .= '<td style="font-size: 10px;width: 25%;text-align: right;">'.number_format($dataDetail->hargajual*$dataDetail->jumlah,0,",",".").'&nbsp;&nbsp;</td>';
			$tablePrint .= '</tr>';
			$totalPenjualan = $totalPenjualan + ($dataDetail->hargajual*$dataDetail->jumlah);
		}
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;border-top: 1px dotted #444;"><span style="float: left">TOTAL</span><span style="float: right">'.number_format($totalPenjualan,0,",",".").'&nbsp;&nbsp;</span></td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;"><span style="float: left">BAYAR</span><span style="float: right">'.number_format($dataPenjualan['info']->bayar,0,",",".").'&nbsp;&nbsp;</span></td></tr>';
		$viewperlakuankembalian = '';
		if ($dataPenjualan['info']->perlakuankembalian != 0){
			$viewperlakuankembalian = '<span style="font-size: 10px">'.$perlakuanKembalian[$dataPenjualan['info']->perlakuankembalian].'</span>';
		}
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;"><span style="float: left">KEMBALI '.$viewperlakuankembalian.'</span><span style="float: right">'.number_format($dataPenjualan['info']->kembali,0,",",".").'&nbsp;&nbsp;</span></td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;border-top: 1px dotted #444;"><span style="float: left"><img src="'.base_path().'misc/media/images/bbm.png" width="12" style="vertical-align:middle;">&nbsp;5AB041B8</span><span style="float: right"><img src="'.base_path().'misc/media/images/whatsapp.png" width="11" style="vertical-align:middle;">&nbsp;+628982485685&nbsp;&nbsp;</span></td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;">Jl. Babakan Madang, Ruko Victoria,&nbsp;&nbsp;</td></tr>';
		$tablePrint .= '<tr><td colspan="3" style="font-size: 11px;text-align: center;border-bottom: 1px dotted #444;">Blok C No.22, Sentul City - Bogor&nbsp;&nbsp;</td></tr>';
		$tablePrint .= '</tbody>';
		$tablePrint .= '</table>';
	}
	return $tablePrint;
}
function createDateRangeArray($strDateFrom,$strDateTo)
{
    $aryRange=array();
		$iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
}
function createLogoImagePath(){
	$logoPath = base_path().file_directory_path().'/cti_flex_logo.png';
	drupal_add_js(
		array(
			'logo' => $logoPath,
		),
	'setting');
	return $logoPath;
}