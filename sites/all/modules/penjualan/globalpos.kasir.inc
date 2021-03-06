<?php

/**
 * @param null $idtitipanlaundry
 * @param null $alamatasal
 * @return string
 */

function tambahbarang(){

	$barcode = $_POST['barcode'];
	$nama = $_POST['nama'];
	$harga = $_POST['harga'];
	db_query("insert into product (barcode, namaproduct, type_product, hargapokok, hargajual) values ('%s', '%s', 1, %f, %f)",
	$barcode, $nama, $harga, $harga);
}

function openshift(){
		global $user;

    _addCustomCSS();
    drupal_add_js('misc/media/datatables.1.10/media/js/jquery.js');
    //$js_path = drupal_get_path('module','datapremis').'/js/datapremis.js';
    //drupal_add_js($js_path);
    $formPremis = drupal_get_form('openshift_input_form');
    return $formPremis;
}

function updateshift(){
	global $user;

	db_query("UPDATE shift set status = 1 where idpemakai=%d", $user->uid);
	drupal_goto("penjualan/openshift");
}


function closeshift(){
	global $user;

	$res_shift =  db_query("select * from shift where idpemakai = %d and status = 0", $user->uid);
	if($res_shift->num_rows == 0){
		drupal_goto("penjualan/openshift");
	}
	$shift = $res_shift->fetch_object();
	$res_sum =  db_query("select sum(total) total, count(*) num from penjualan where idshift = %d", $shift->idshift);
	$summary = $res_sum->fetch_object();

	$res_gst =  db_query("select sum(d.hargajual*d.jumlah-d.diskon) total
from penjualan p
join detailpenjualan d on (d.idpenjualan = p.idpenjualan)
join shift s on (s.idshift = p.idshift)
join product b on (b.idproduct = d.idproduct)
where s.idshift = %d and b.type_product = 1", $shift->idshift);
	$gst = $res_gst->fetch_object();

	$res_zero =  db_query("select sum(d.hargajual*d.jumlah-d.diskon) total
from penjualan p
join detailpenjualan d on (d.idpenjualan = p.idpenjualan)
join shift s on (s.idshift = p.idshift)
join product b on (b.idproduct = d.idproduct)
where s.idshift = %d and b.type_product = 0", $shift->idshift);
	$zero = $res_gst->fetch_object();

get_number_format($currencySym, $thousandSep, $decimalSep);
$dataPremis = getPremisInformation();
	drupal_add_js('misc/media/jquery-1.4.4.min.js');
	$js_path = drupal_get_path('module','penjualan').'/js/closeshift.js';
	drupal_add_js($js_path);

	$tablePrint = '';
	$printHeader = true;
	$pathLogo = createLogoImagePath();

			$tablePrint = '<table style="width: 240px;border-collapse: collapse;" >';
			$tablePrint .= '<tbody>';
			if ($printHeader){
					$tablePrint .= '<tr><td rowspan="3" style="border-bottom: 1px solid #000;border-top: 1px solid #000;width:50px;text-align: center;">';
		$tablePrint .= '<img height="48" src="'.$pathLogo.'" /></td>';
		$tablePrint .= '<td colspan="2" style="font-size: 14px;font-weight: bold;text-align: center;';
		$tablePrint .= 'border-bottom: 1px solid #000;border-top: 1px solid #000;">'.$dataPremis->nama.'</td></tr>';
					$tablePrint .= '<tr><td colspan="2" style="font-size: 11px;text-align: center;border-bottom: 1px solid #000;">'.$dataPremis->alamat.'&nbsp;&nbsp;</td></tr>';
					//$tablePrint .= '<tr><td colspan="2" style="font-size: 11px;text-align: center;border-bottom: 1px solid #000;">SENTUL CITY - BOGOR&nbsp;&nbsp;</td></tr>';
			}
			$tablePrint .= '<tr><td colspan="3" style="font-size: 14px;text-align: center;"><span style="float: left"></span><span style="float: right">&nbsp;&nbsp;</span></td></tr>';

			$tablePrint .= '<tr><td colspan="3" style="font-size: 14px;text-align: center;"><span style="float: left">Tanggal</span><span style="float: right">'.$shift->tanggal.'&nbsp;&nbsp;</span></td></tr>';
			$tablePrint .= '<tr><td colspan="3" style="font-size: 14px;text-align: center;border-bottom: 1px solid #000;"><span style="float: left">Cashier</span><span style="float: right">'.$shift->nama.'&nbsp;&nbsp;</span></td></tr>';
			$totalPenjualan = 0;
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3" style="font-size: 13px;font-weight: bold;">WANG KECIL</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3"  style="font-size: 13px;font-weight: bold;text-align: right;">RM'.number_format($shift->ra,2,$decimalSep,$thousandSep).'&nbsp;&nbsp;</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3" style="font-size: 13px;font-weight: bold;">GROSS SALES</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3"  style="font-size: 13px;font-weight: bold;text-align: right;">RM'.number_format($summary->total,2,$decimalSep,$thousandSep).'&nbsp;&nbsp;</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3" style="font-size: 13px;font-weight: bold;">CASH IN DRW</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3"  style="font-size: 13px;font-weight: bold;text-align: right;">RM'.number_format($summary->total + $shift->ra,2,$decimalSep,$thousandSep).'&nbsp;&nbsp;</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<tr><td colspan="3" style="font-size: 13px;font-weight: bold;text-align: center;border-top: 1px solid #000;"><span style="float: right">&nbsp;&nbsp;</span></td></tr>';
			$tablePrint .= '<td colspan="3" style="font-size: 13px;font-weight: bold;">STANDART TAX AMT</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3"  style="font-size: 13px;font-weight: bold;text-align: right;">RM'.number_format($gst->total,2,$decimalSep,$thousandSep).'&nbsp;&nbsp;</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<td colspan="3" style="font-size: 13px;font-weight: bold;">GST 6%</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3"  style="font-size: 13px;font-weight: bold;text-align: right;">RM'.number_format($gst->total*0.06,2,$decimalSep,$thousandSep).'&nbsp;&nbsp;</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<td colspan="3" style="font-size: 13px;font-weight: bold;">ZERO TAX AMT</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3"  style="font-size: 13px;font-weight: bold;text-align: right;">RM'.number_format($zero->total,2,$decimalSep,$thousandSep).'&nbsp;&nbsp;</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<td colspan="3" style="font-size: 13px;font-weight: bold;">ZERO RATE</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3"  style="font-size: 13px;font-weight: bold;text-align: right;">RM0.00<span style="float: right">&nbsp;&nbsp;</span></td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr><td colspan="3" style="font-size: 13px;font-weight: bold;text-align: center;border-top: 1px solid #000;"><span style="float: right">&nbsp;&nbsp;</span></td></tr>';

			$tablePrint .= '<td colspan="3" style="font-size: 13px;font-weight: bold;">Z TRANS</td>';
			$tablePrint .= '</tr>';
			$tablePrint .= '<tr>';
			$tablePrint .= '<td colspan="3"  style="font-size: 13px;font-weight: bold;text-align: right;">'.number_format($summary->num,0,$decimalSep,$thousandSep).'&nbsp;&nbsp;</td>';
			$tablePrint .= '</tr>';

			$tablePrint .= '<tr><td colspan="3" style="font-size: 12px;text-align: center;border-top: 1px solid #000;">TERIMAKASIH</td></tr>';
			$tablePrint .= '<tr><td colspan="3" style="font-size: 12px;text-align: center;">BERNIAGA DG TUHAN, BERKHITMAD PD SESAMA</td></tr>';
			$tablePrint .= '</tbody>';
			$tablePrint .= '</table>';
			$tablePrint .="<br/><br/>";
			$tablePrint .="<button id='print' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button'><span class='ui-button-text'>PRINT</span></button>";

	return $tablePrint;
}

function openshift_input_form(&$form_state){

    //$form['#attributes'] = array('class' => 'form-item-lined-up');
    $form['tarikh'] = array(
			'#type' => 'date',
  		'#title' => "Tarikh",
  		//'#default_value' => date(),
  		'#description' => "Tarikh",
			'#required' => TRUE
    );

	$form['nama'] = array(
        '#title' => t('Nama'),
        '#type' => 'textfield',
        '#default_value' => "",
        '#description' => t('Input Nama'),
    );

    $form['ra'] = array(
        '#title' => t('Wang Kecil'),
        '#type' => 'textfield',
        '#default_value' => "0",
        '#description' => t('Input RA'),
    );
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Simpan')
    );
    return $form;
}

function openshift_input_form_submit($form, &$form_state) {
    global $user;

    $formValues = $form_state['values'];
		$date = $formValues['tarikh']['year']."-".$formValues['tarikh']['month']."-".$formValues['tarikh']['day'];
		db_query("INSERT INTO shift (tanggal, nama, ra, idpemakai, status) VALUES ('%s', '%s', %f, %d, 0)",
			$date, $formValues['nama'],$formValues['ra'], $user->uid);
		drupal_set_message(t('Maklumat Shift sudah disimpan.'));
		drupal_goto("penjualan/kasir");
}

function penjualan($idtitipanlaundry = null, $alamatasal = null){
	global $user;

	$res =  db_query("select * from shift where idpemakai = %d and status = 0", $user->uid);
	if($res->num_rows == 0){
		drupal_goto("penjualan/openshift");
	}
	$shift = $res->fetch_object();

	get_number_format($currencySym, $thousandSep, $decimalSep);
    $gstSym = get_gst_symbols();
	$dataPremis = get_data_premis();
	$path = drupal_get_path('theme', 'cti_flex');
 	$form_style = $path.'/css/custom-style.css';
	$logoPath = base_path().file_directory_path().'/cti_flex_logo.png';
	drupal_add_css($form_style, 'theme', 'all', FALSE);
 	$variables['styles'] = drupal_get_css();
	drupal_add_css('misc/media/datatables/css/demo_table_jui.css');
	drupal_add_css('misc/media/themes/jquery-ui-1.8.11.custom.css');
	drupal_add_css('misc/media/css/validationEngine.jquery.css');
	drupal_add_js('misc/media/jquery-1.4.4.min.js');
	drupal_add_js('misc/media/jqClock.min.js');
	drupal_add_js('misc/media/jquery.validationEngine-en.js');
	drupal_add_js('misc/media/jquery.validationEngine.js');
	drupal_add_js('misc/media/jquery-ui-1.8.11.custom.min.js');
	drupal_add_js('misc/media/datatables/js/jquery.dataTables.min.js');
	drupal_add_js('misc/media/datatables/js/dataTables.addtr.js');
	drupal_add_js('misc/media/datatables/js/number.format.js');
	drupal_add_js('misc/media/jquery.autotab-1.1b.js');
    _addChosenJqueryPlugins();
	$idPelanggan = 0;
	if (!empty($idtitipanlaundry)){
		if (empty($alamatasal)){
			$alamatasal = 'viewlaundry';
		}
		drupal_add_js(
			array(
				'idtitipanlaundry' => $idtitipanlaundry,
				'alamatasal' => $alamatasal,
			),
		'setting');
		if ($alamatasal == 'viewlaundry' || $alamatasal == 'laundrykeluar'){
			$titipanlaundry = db_fetch_array(db_query("SELECT idtitipanlaundry, nonota, tglpenjualan, idpemakai,
			idpelanggan, total, totalmodal, carabayar, bayar, kembali, nokartu, keterangan, insert_date, status_laundry,
			users.name,laundry.nomer_rak FROM titipanlaundry laundry LEFT JOIN cms_users users ON laundry.idpemakai = users.uid
			WHERE idtitipanlaundry=%d",$idtitipanlaundry));
			if (count($titipanlaundry)){
				$tgllaundry = explode(' ', $titipanlaundry['tglpenjualan']);
				$splittgllaundry = explode('-',$tgllaundry[0]);
				$tglTampilLaundry = $splittgllaundry[2].'-'.$splittgllaundry[1].'-'.$splittgllaundry[0];
				$idPelanggan = $titipanlaundry['idpelanggan'];
				$kasirlaundry = $titipanlaundry['name'];
				$nomerRak = $titipanlaundry['nomer_rak'];
				$labelKasir = 'LAUNDRY';
				$labelTgl = 'MASUK';
			}
		}else if($alamatasal == 'viewcustomerorder' || $alamatasal == 'orderkeluar'){
			$titipanlaundry = db_fetch_array(db_query("SELECT id, nonota, tglorder, idpemakai,
			idpelanggan, total, totalmodal, carabayar, bayar, kembali, nokartu, keterangan, insert_date, status_order,
			users.name FROM customer_order custord LEFT JOIN cms_users users ON custord.idpemakai = users.uid
			WHERE id=%d",$idtitipanlaundry));
			if (count($titipanlaundry)){
				$tgllaundry = explode(' ', $titipanlaundry['tglorder']);
				$splittgllaundry = explode('-',$tgllaundry[0]);
				$tglTampilLaundry = $splittgllaundry[2].'-'.$splittgllaundry[1].'-'.$splittgllaundry[0];
				$idPelanggan = $titipanlaundry['idpelanggan'];
				$kasirlaundry = $titipanlaundry['name'];
				$labelKasir = 'ORDER';
				$labelTgl = 'ORDER';
			}
		}
	}
	$uploadData = false;
	if (isset($_GET['afterinsert']) && $_GET['afterinsert']){
		$uploadData = true;
	}
	$tglsekarang = date("Y-m-d");
	$tgltampil = date("d-m-Y");
	drupal_add_js(
		array(
			'tglsekarang' => $tglsekarang,
			'tgltampil' => $tgltampil,
			'logo' => $logoPath,
			'upload_data' => $uploadData,
		),
	'setting');
	$js_path = drupal_get_path('module','penjualan').'/js/kasir.form.new.js';
	drupal_add_js($js_path);
	if (isset($_GET['tanggal'])){
		$tanggal = $_GET['tanggal'];
		$tanggal = date('d-m-Y', strtotime($tanggal));
		$tanggalkirim = date('Y-m-d', strtotime($tanggal));
	}else{
		$tanggal = date('d-m-Y');
		$tanggalkirim = date('Y-m-d');
	}
	$panduantombol = '<a style="font-weight: bold;text-align: center;font-size:12px;margin:0;padding: .4em .3em;">Ctrl+Shift+</a>';
	$tombolcari = '<a id="tombolubahharga" class="cari" onclick="ubahharga()">F2:UBAH HARGA</a>';
	$tombolqty = '<a id="tombolubahqty" onclick="ubahqty()" class="buttonqty">F3:UBAH QTY</a>';
	$tombolhapus = '<a class="buttonhapus" onclick="hapus_latest_produk()">F4:PADAM ITEM</a>';
	$tombolselesai = '<a class="buttonselesai" onclick="kirim_data(1)">F5:BAYAR&PRINT</a>';
	$tombolprint = '<a class="buttonprint" onclick="kirim_data(0)">F6:BAYAR</a>';
	//$tomboltutupkasir = '<a class="buttontutup">F7:TUTUP KASIR</a>';
	$tombollihatomset = '<a class="buttonomset" onclick="hitung_omset();">F8:TOTAL</a>';
    $tombollihatpelanggan = '<a class="buttontambahbarang" onclick="tambah_barang();">F9:TAMBAH BRG</a>';
	$penjualan = '<button onclick="munculkankasir();" style="font-size:12px;border: 1px solid orangered;">Klik untuk memunculkan kasir</button>';
	$penjualan .= '<div id="dialogkasir" style="font-size: 12px;" title="GLOBAL POS KASIR ['.$tanggal.']">';
	$penjualan .= '<div id="info-kasir-waktu">';
	$penjualan .= '<div id="tempattanggalkasir">';
	$penjualan .= '<div id="tempattanggal">';
	$penjualan .= '<label>TARIKH</label><input readonly="readonly" type="text" id="tgljual" name="tgljual" value="'.$tanggal.'">';
	$penjualan .= '<input type="hidden" id="tgljualkirim" name="tgljualkirim" value="'.$tanggalkirim.'">';
	$penjualan .= '</div>';
	$penjualan .= '<div id="tempatnamakasir">';
	global $user;
	$penjualan .= '<label>STAFF</label><input readonly="readonly" type="text" id="kasir" name="kasir" value="'.$shift->nama.'">';
	$penjualan .= '</div>';
	if (!empty($idtitipanlaundry)){
		$penjualan .= '<div id="tempattanggal">';
		$penjualan .= '<label>KASIR '.$labelKasir.'</label><input readonly="readonly" type="text" id="kasir" name="kasir" value="'.$kasirlaundry.'">';
		$penjualan .= '</div>';
	}
	$penjualan .= '</div>';
	$penjualan .= '<div id="tempatjam" align="center">';
	$penjualan .= 'Jam';
	$penjualan .= '</div>';
	$penjualan .= '<div id="tempatkosong">';
	$penjualan .= '<div id="tempatsupplier"><label>PELANGGAN</label></div>';
	$penjualan .= '<div id="tempatsupplier2">'.createPelangganSelection($idPelanggan).'</div>';
	if (!empty($idtitipanlaundry)){
		$penjualan .= '<div id="tempatsupplier"><label>TGL '.$labelTgl.'</label><input readonly="readonly" type="text" id="tglmasuk" name="tglmasuk" value="'.$tglTampilLaundry.'">';
		$penjualan .= '</div>';
	}
	$penjualan .= '</div>';
	$penjualan .= '</div>';
	$penjualan .= '<div id="barcode_place">SCAN ATAU INPUT BARCODE, KOD ATAU NAMA PRODUK : <input type="text" id="barcode" name="barcode" class="barcodekasir"><input type="hidden" id="hiddenbarcode" name="hiddenbarcode"></div>';
	$tablekasir = view_kasir($idtitipanlaundry,$alamatasal);
	$penjualan .= '<div id="tempattabelkasir">'.$tablekasir.'</div>';
	$penjualan .= '<input type="hidden" id="last_id" name="last_id">';
	$penjualan .= '<input type="hidden" id="lastqty" name="lastqty" value="1">';
	$penjualan .= '<input type="hidden" id="lastharga" name="lastharga">';
	$penjualan .= '<input type="hidden" id="lastdiskon" name="lastdiskon">';
	$penjualan .= '<input type="hidden" id="lastbarcode" name="lastbarcode">';
	$penjualan .= '<input type="hidden" id="nilaikirim" name="nilaikirim">';
	//$penjualan .= '<div id="tempattombolkasir">';
	if ($nomerRak != ''){
		$penjualan .= '<div id="tempattombolkasir" style="height:330px;">';
		$penjualan .= '<a style="padding: 0.4em 0.3em;margin-top: 2px;margin-bottom: 4px;" class="buttonprint" onclick="preventDefault();">NO.RAK : '.$nomerRak.'</a>';
	}else{
		$penjualan .= '<div id="tempattombolkasir">';
	}
	$penjualan .= $panduantombol;
	$penjualan .= $tombolcari;
	$penjualan .= $tombolqty;
	$penjualan .= $tombolhapus;
	$penjualan .= $tombolselesai;
	$penjualan .= $tombolprint;
	//$penjualan .= $tombolprint;
	//$penjualan .= $tomboltutupkasir;
	$penjualan .= $tombollihatomset;
    $penjualan .= $tombollihatpelanggan;
	$penjualan .= '</div>';
    $penjualan .= '<div id="tempattotal"><div id="totalbelanja">TOTAL : '.$currencySym.' 0</div></div>';
	$penjualan .= '</div>';
	$penjualan .= '<div title="Informasi" id="dialogwarning">';
	$penjualan .= '<p id="pesantext">Produk yang dicari tidak ada, Silahkan masukkan Barcode/Kode Produk/Nama Produk yang lain...!!!</p>';
	$penjualan .= '<p style="text-align: center"><button id="tutupdialog" class="buttonwarning" onclick="tutupwarning();">CLOSE</button></p>';
	$penjualan .= '</div>';
	$penjualan .= '<div title="UBAH HARGA" id="dialogubahharga">';
	$penjualan .= '<label>Harga Baru : </label><input type="text" id="newharga" name="newharga" value="1">';
	$penjualan .= '</div>';
	$penjualan .= '<div title="TAMBAH BARANG" id="dialogtambahbarang">';
	$penjualan .= '<table><tr><td><label>Barcode</label></td><td>:</td><td><input type="text" id="newbarcode1" name="newbarcode"/></td></tr>';
	$penjualan .= '<tr><td><label>Nama</label></td><td>:</td><td><input type="text" id="newnama1" name="newnama1"/></td></tr>';
	$penjualan .= '<tr><td><label>Harga</label></td><td>:</td><td><input type="text" id="newharga1" name="newharga1"/></td></tr>';
	$penjualan .= '<tr><td colspan="3">&nbsp;</td></tr>';
	$penjualan .= '<tr><td colspan="3" style="text-align:center"><a id="buttonsubmitbarang">SIMPAN</a></td></tr>';
	$penjualan .= '</table></div>';
	$penjualan .= '<div title="UBAH QTY" id="dialogubahqty">';
	$penjualan .= '<label>Qty Baru : </label><input type="text" id="newqty" name="newqty" value="1">';
	$penjualan .= '</div>';
	$penjualan .= '<div title="UBAH QTY" id="dialogubahqty2">';
	$penjualan .= '<label>Qty Baru : </label><input type="text" id="newqty2" name="newqty2">';
	$penjualan .= '</div>';
	$penjualan .= '<div title="PEMBAYARAN" id="dialogbayar">';
	$carabayar = '<select id="carabayar" name="carabayar">';
	$result = db_query("SELECT carabayar FROM carabayar");
	$i = 0;
	while($data = db_fetch_object($result)){
		if ($i == 0){
			$carabayar .= '<option selected="selected" value="'.$data->carabayar.'">'.$data->carabayar.'</option>';
		}else{
			$carabayar .= '<option value="'.$data->carabayar.'">'.$data->carabayar.'</option>';
		}
		$i++;
	}
	$carabayar .= '</select>';
	$perlakuankembalian = '<select id="kembalian" name="kembalian">';
	//Pelanggan
	/*$pelanggan = '<select id="idpelanggan" name="idpelanggan">';
	$result = db_query("SELECT idpelanggan,namapelanggan FROM pelanggan");
	$i = 0;
	while($data = db_fetch_object($result)){
		if ($i == 0){
			$pelanggan .= '<option selected="selected" value="'.$data->idpelanggan.'">'.$data->namapelanggan.'</option>';
		}else{
			$pelanggan .= '<option value="'.$data->idpelanggan.'">'.$data->namapelanggan.'</option>';
		}
		$i++;
	}
	$pelanggan .= '</select>';*/
	$nilaibayar = '<input type="text" id="nilaibayar" name="nilaibayar">';
	$depositpelanggan = '<input type="text" id="depositpelanggan" name="depositpelanggan" readonly="readonly">';
	$nokartudebit = '<input type="text" id="nomerkartu" name="nomerkartu">';
	$kembali = '<input readonly="readonly" type="text" id="kembali" name="kembali">';
	$totalbelanja = '<input type="text" id="totalbelanjauser" name="totalbelanjauser">';
	//$penjualan .= '<div class="barisbayar"><label>Pelanggan</label>'.$pelanggan.'</div>';
	$penjualan .= '<div class="barisbayar"><label>Total Belanja</label>'.$totalbelanja.'</div>';
	$penjualan .= '<div class="barisbayar"><label>Pembayaran</label>'.$nilaibayar.'</div>';
	$penjualan .= '<div id="field_kembali" class="barisbayar"><label>Baki</label>'.$kembali.'</div>';
	$penjualan .= '</div>';
	return $penjualan;
}

function simpan_penjualan(){
	//nonota, idpemakai, total, bayar, kembali

	if (isset($_POST["detail_produk"]) AND isset($_POST["bayar"]) >= 0 AND
        isset($_POST["totalbelanja"]) AND isset($_POST['totalbelanjappn'])
    ){
		global $user;
		//date_default_timezone_set('Asia/Jakarta');
		$waktujual = $_POST["tgljual"]." ".date("H:i:s");
		$splitTanggal = explode('-', $_POST["tgljual"]);
		$splitJam = explode(':',date("H:i:s"));
		$intTanggal = mktime($splitJam[0],$splitJam[1],$splitJam[2],$splitTanggal[1],$splitTanggal[2],$splitTanggal[0]);
		$result = db_query("SELECT idpenjualan FROM penjualan ORDER BY idpenjualan DESC LIMIT 1");
		$data = db_fetch_object($result);
		if ($data->idpenjualan > 0){
			$next_id = $data->idpenjualan + 1;
		}else{
			$next_id = 1;
		}
		$no_nota = buat_nota($next_id);
		$kembali = $_POST["bayar"] - $_POST["totalbelanja"];
		$perkuankembalian = isset($_POST["perlakuankembalian"]) ? $_POST["perlakuankembalian"] : 0;
		$idtitipanlaundry = 0;
		if (isset($_POST['idtitipanlaundry']) && !empty($_POST['idtitipanlaundry'])){
			$idtitipanlaundry = $_POST['idtitipanlaundry'];
		}
		db_query("INSERT INTO penjualan (nonota, idpemakai, total, bayar, kembali, tglpenjualan, idpelanggan,
        carabayar, idtitipanlaundry, perlakuankembalian, ppn, total_plus_ppn, idshift)
		VALUES ('%s', '%d', '%f', '%f', '%f', '%s', '%d', '%s', '%d', '%d', '%f', '%f',
			(select idshift from shift where idpemakai = %d and status = 0))",
		$no_nota, $user->uid, $_POST["totalbelanja"], $_POST["bayar"], $kembali, $waktujual,
		$_POST["idpelanggan"],$_POST["carabayar"], $idtitipanlaundry,$perkuankembalian,
        $_POST["ppn"],$_POST["totalbelanjappn"], $user->uid);
		$next_id = db_last_insert_id('penjualan', 'idpenjualan');
		print $next_id;
		if ($kembali > 0 && $perkuankembalian == 2){
			if (isset($_POST["idpelanggan"]) && !empty($_POST["idpelanggan"])){
				$variables['idpelanggan'] = $_POST["idpelanggan"];
				$variables['hutang'] = getTotalHutang($_POST["idpelanggan"]);
				$variables['pembayaran'] = $kembali;
				$variables['tglbayar'] = $waktujual;
				$variables['idpenjualan'] = $next_id;
				$variables['idtitipanlaundry'] = $idtitipanlaundry;
				$variables['keterangan'] = 'Deposit Uang Dari Kembalian Nota Penjualan : '.$no_nota.' Tanggal : '.$waktujual;
				pembayaranhutang($variables);
			}
		}else if ($kembali < 0){
			db_query("INSERT INTO detailpiutang (idpelanggan, idpenjualan, besarhutang) VALUES ('%d','%d','%f')",
			$_POST["idpelanggan"],$next_id,abs($kembali));
			$resultpiutang = db_query("SELECT idpelanggan, besarhutang  FROM piutang WHERE idpelanggan='%d'",
			$_POST["idpelanggan"]);
			$datapiutang = db_fetch_object($resultpiutang);
			if ($datapiutang->idpelanggan){
				$besarhutangskrg = $datapiutang->besarhutang + abs($kembali);
				db_query("UPDATE piutang SET besarhutang='%f',uid='%d' WHERE idpelanggan='%d'",
				$besarhutangskrg,$user->uid,$_POST["idpelanggan"]);
			}else{
				db_query("INSERT INTO piutang(idpelanggan,besarhutang,uid)VALUES('%d','%f','%d')",
				$_POST["idpelanggan"],abs($kembali),$user->uid);
			}
		}
		if ($_POST["carabayar"] == "DEPOSIT"){
			if (isset($_POST["idpelanggan"]) && !empty($_POST["idpelanggan"])){
				$sisaDeposit = getTotalPembayaran($_POST["idpelanggan"]) - (getTotalHutang($_POST["idpelanggan"]) + getTotalPenggunaanDeposit($_POST["idpelanggan"]));
				$variables['idpelanggan'] = $_POST["idpelanggan"];
				$variables['deposit'] = $sisaDeposit;
				$variables['penggunaan'] = $_POST["bayar"];
				$variables['tglbayar'] = $waktujual;
				$variables['keterangan'] = 'Penggunaan deposit untuk pembayaran nota : '.$no_nota.' Tanggal : '.$waktujual;
				$variables['idpenjualan'] = $next_id;
				penggunaandeposit($variables);
				syncHutangPelanggan($_POST["idpelanggan"]);
			}
		}
		$detailTitipanLaundry = null;
		if (isset($_POST['idtitipanlaundry']) && !empty($_POST['idtitipanlaundry'])){
			$result = db_query("SELECT iddetail, idproduct, sisa, diambil FROM
			detaillaundry WHERE idtitipanlaundry='%d' AND sisa > 0",$_POST['idtitipanlaundry']);
			while ($data = db_fetch_object($result)){
				$detailTitipanLaundry[$data->idproduct] = $data;
			}
		}else if (isset($_POST['idcustomerorder']) && !empty($_POST['idcustomerorder'])){
			$result = db_query("SELECT id, idproduct, sisa, diambil FROM
			detailcustomerorder WHERE idcustomerorder='%d' AND sisa > 0",$_POST['idcustomerorder']);
			while ($data = db_fetch_object($result)){
				$detailCustomerOrder[$data->idproduct] = $data;
			}
		}
		$pecahdetail = explode("&",$_POST["detail_produk"]);
		$totalmodal = 0;
		foreach ($pecahdetail as $parameter){
			$pecahparameter = explode("=",$parameter);
			$pecahnilai = explode("___",$pecahparameter[1]);
			$IDPRODUK =	$pecahnilai[0];
			$QTY = $pecahnilai[1];
			$HARGAJUAL = $pecahnilai[2];
			$DISKON = $pecahnilai[3];
			$result = db_query("SELECT type_product, hargapokok,stok FROM product WHERE idproduct='%d'",$IDPRODUK);
			$data = db_fetch_object($result);
			$HARGAPOKOK = $data->hargapokok;
			$totalmodal = $totalmodal + ($HARGAPOKOK*$QTY);
			$STOKSEBELUM = $data->stok;
			db_query("INSERT INTO detailpenjualan(idpenjualan, idproduct, jumlah, hargapokok, hargajual, diskon) VALUES
			('%d', '%d', '%f', '%f', '%f', '%f')",
			$next_id,$IDPRODUK,$QTY,$HARGAPOKOK,$HARGAJUAL,$DISKON);

			db_query("update product set hargajual=%f where idproduct=%d", $HARGAJUAL, $IDPRODUK);

			if ($data->type_product == 0){
				$STOKSEKARANG = $STOKSEBELUM - $QTY;
				db_query("UPDATE product SET stok='%f' WHERE idproduct='%d'",$STOKSEKARANG,$IDPRODUK);
				$KETERANGAN = 'Penjualan';
				db_query("INSERT INTO transaksistock (idproduk, idpenjualan, stocksebelum, keluar, stocksetelah, keterangan) VALUES
				('%d', '%d', '%f', '%f', '%f', '%s')",$IDPRODUK,$next_id,$STOKSEBELUM,$QTY,$STOKSEKARANG,$KETERANGAN);
			}
			if (!empty($detailTitipanLaundry) && isset($detailTitipanLaundry[$IDPRODUK])){
				if ($detailTitipanLaundry[$IDPRODUK]->sisa - $QTY <= 0){
					$statusDetail = 2;
				}else{
					$statusDetail = 1;
				}
				$strSQL = "UPDATE detaillaundry SET sisa = sisa - %f, diambil = %d, status_laundry = %d WHERE iddetail = %d";
				db_query($strSQL, $QTY, $intTanggal, $statusDetail, $detailTitipanLaundry[$IDPRODUK]->iddetail);
			}else if(!empty($detailCustomerOrder) && isset($detailCustomerOrder[$IDPRODUK])){
				if ($detailCustomerOrder[$IDPRODUK]->sisa - $QTY <= 0){
					$statusDetail = 2;
				}else{
					$statusDetail = 1;
				}
				$strSQL = "UPDATE detailcustomerorder SET sisa = sisa - %f, diambil = %d, status_order = %d WHERE id = %d";
				db_query($strSQL, $QTY, $intTanggal, $statusDetail, $detailCustomerOrder[$IDPRODUK]->id);
			}
		}
		if (isset($_POST['idtitipanlaundry']) && !empty($_POST['idtitipanlaundry'])){
			$totalLaundry = db_result(db_query("SELECT COUNT(*) FROM detaillaundry WHERE idtitipanlaundry='%d'",$_POST['idtitipanlaundry']));
			$totalSelesai = db_result(db_query("SELECT COUNT(*) FROM detaillaundry WHERE idtitipanlaundry='%d' AND status_laundry=2",$_POST['idtitipanlaundry']));
			if ($totalSelesai > 0 && $totalSelesai == $totalLaundry){
				$statusLaundry = 2;
			}else if($totalSelesai > 0 && $totalSelesai < $totalLaundry){
				$statusLaundry = 1;
			}else{
				$statusLaundry = 0;
			}
			$strSQL = "UPDATE titipanlaundry SET status_laundry=%d WHERE idtitipanlaundry=%d";
			db_query($strSQL, $statusLaundry, $_POST['idtitipanlaundry']);
		}else if(isset($_POST['idcustomerorder']) && !empty($_POST['idcustomerorder'])){
			$totalOrder = db_result(db_query("SELECT COUNT(*) FROM detailcustomerorder WHERE idcustomerorder='%d'",$_POST['idcustomerorder']));
			$totalSelesai = db_result(db_query("SELECT COUNT(*) FROM detailcustomerorder WHERE idcustomerorder='%d' AND status_order=2",$_POST['idcustomerorder']));
			if ($totalSelesai > 0 && $totalSelesai == $totalOrder){
				$statusOrder = 2;
			}else if($totalSelesai > 0 && $totalSelesai < $totalOrder){
				$statusOrder = 1;
			}else{
				$statusOrder = 0;
			}
			$strSQL = "UPDATE customer_order SET status_order=%d WHERE id=%d";
			db_query($strSQL, $statusOrder, $_POST['idcustomerorder']);
		}
		db_query("UPDATE penjualan SET totalmodal='%f' WHERE idpenjualan='%d'",$totalmodal,$next_id);
		/*if (function_exists('is_connected') && is_connected()){
			if (function_exists('do_upload_premisdata')){
				do_upload_premisdata();
			}
		}*/
	}else{
		print 'error';
	}
	exit();
}
function buat_nota($idpenjualan){
	if ($idpenjualan > 0 AND $idpenjualan < 10){
		$no_nota = "N000000".$idpenjualan;
	}elseif ($idpenjualan >= 10 AND $idpenjualan < 100){
		$no_nota = "N00000".$idpenjualan;
	}elseif ($idpenjualan >= 100 AND $idpenjualan < 1000){
		$no_nota = "N0000".$idpenjualan;
	}elseif ($idpenjualan >= 1000 AND $idpenjualan < 10000){
		$no_nota = "N000".$idpenjualan;
	}elseif ($idpenjualan >= 10000 AND $idpenjualan < 100000){
		$no_nota = "N00".$idpenjualan;
	}elseif ($idpenjualan >= 100000 AND $idpenjualan < 1000000){
		$no_nota = "N0".$idpenjualan;
	}elseif ($idpenjualan >= 1000000){
		$no_nota = "N".$idpenjualan;
	}
	return $no_nota;
}
function hitung_omset($tgl = null){
	if ($_POST["tglpost"]){
		$tglomset = $_POST["tglpost"];
	}else{
		if (is_null($tgl)){
			$tglomset = date("Y-m-d");
		}else{
			$tglomset = $tgl;
		}
	}
	$result = db_query("SELECT SUM(total_plus_ppn) AS totalbelanja FROM penjualan WHERE SUBSTR(tglpenjualan,1,10)='%s'",$tglomset);
	$data = db_fetch_object($result);
	if ($_POST["tglpost"]){
		print $data->totalbelanja;
		exit();
	}else{
		return $data->totalbelanja;
	}
}
function cari_produk(){
	if ($_POST["katacari"]){
		$KATACARI = $_POST["katacari"].'%';
		//$sql = "SELECT idproduct,idkategori,namaproduct,hargajual FROM product WHERE alt_code LIKE '$KATACARI' OR barcode LIKE '$KATACARI' OR namaproduct LIKE '$KATACARI'";
		$result = db_query("SELECT idproduct,barcode,idkategori,namaproduct,hargajual,lead_time,aturan_jam_kerja,
		berlaku_sebelum_zuhur FROM product WHERE alt_code LIKE '%s' OR barcode LIKE '%s'
		OR UPPER(namaproduct) LIKE '%s' LIMIT 50",$KATACARI,$KATACARI,$KATACARI);
		$data = db_fetch_object($result);
		if ($data->idproduct){
			$result2 = db_query("SELECT besardiskon FROM diskonkategori WHERE idpelanggan='%d' AND idkategori='%d'",
			$_POST["idpelanggan"],$data->idkategori);
			$datadiskon = db_fetch_object($result2);
			if ($datadiskon->besardiskon >=0 ){
				$DISKON = $datadiskon->besardiskon;
			}else{
				$DISKON = 0;
			}
			if ($_POST["idpelanggan"] == 0){
				$DISKON = 0;
			}
			$takeTime = calculateTakeTime($data->lead_time, $_POST["waktumasuk"],$data->aturan_jam_kerja,$data->berlaku_sebelum_zuhur);
			print $data->idproduct.';'.$data->namaproduct.';'.$data->hargajual.';'.$DISKON.';'.$takeTime[0].';'.$takeTime[1].';'.$data->barcode;
		}else{
			print "error";
		}
	}
	exit();
}
function cari_barang(){
	if ($_GET["term"]){
        get_number_format($currencySym, $thousandSep, $decimalSep, false);
		$KATACARI = $_GET["term"].'%';
		//print(sprintf("SELECT barcode, alt_code, namaproduct, stok, hargajual FROM product WHERE alt_code LIKE '%s' OR barcode LIKE '%s' OR UPPER(namaproduct) LIKE '%s' ORDER by namaproduct LIMIT 20",$KATACARI,$KATACARI,$KATACARI));
		//die();
		$result = db_query("SELECT barcode, alt_code, namaproduct, stok, hargajual FROM product WHERE alt_code LIKE '%s' OR barcode LIKE '%s' OR UPPER(namaproduct) LIKE '%s' ORDER by namaproduct LIMIT 20",$KATACARI,$KATACARI,$KATACARI);
		$items = array();
		while ($data = db_fetch_object($result)) {
			$items[] = array(
				'value' => $data->namaproduct . '--->['.$currencySym.' ' . number_format($data->hargajual,2,$decimalSep,$thousandSep) . '][' . $data->stok . ']',
				'barcode'   => $data->barcode,
				'alt_code'  => $data->alt_code,
				'hargajual' => $data->hargajual,
			);
		}
		print json_encode($items);
	}
	exit();
}
function view_kasir($idtitipanlaundry = null, $alamatasal = null){
	$kasirtabel ='<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabel_kasir">';
	$kasirtabel .= '<thead>';
	$kasirtabel .= '<tr>';
	$kasirtabel .= '<th class="tablebutton"></th>';
	$kasirtabel .= '<th class="tablebutton"></th>';
	$kasirtabel .= '<th>PRODUK</th>';
	$kasirtabel .= '<th>HARGA</th>';
	$kasirtabel .= '<th class="diskon2">%</th>';
	$kasirtabel .= '<th class="diskon2">QTY</th>';
	$kasirtabel .= '<th class="subtotal">SUBTOTAL</th>';
	$kasirtabel .= '</tr>';
	$kasirtabel .= '</thead>';
	$kasirtabel .= '<tbody>';
	if (!empty($idtitipanlaundry)){
		if (empty($alamatasal) || $alamatasal == 'viewlaundry' || $alamatasal == 'laundrykeluar'){
			$result = db_query("SELECT b.iddetail, b.idproduct, b.iddetail,c.barcode, c.namaproduct, b.jumlah,
			b.hargajual, (b.hargajual*b.jumlah) AS subtotal, sisa, diambil FROM
			detaillaundry b LEFT JOIN product c ON b.idproduct=c.idproduct LEFT JOIN
			supplier a ON c.idsupplier=a.idsupplier WHERE b.idtitipanlaundry='%d' AND sisa > 0",$idtitipanlaundry);
			$dataLaundry = array();
			while ($data = db_fetch_object($result)){
				$dataLaundry[] = $data;
			}
			drupal_add_js(
				array(
					'data_laundry' => $dataLaundry,
				),
				'setting');
		}else if ($alamatasal == 'viewcustomerorder' || $alamatasal == 'orderkeluar'){
			$result = db_query("SELECT b.id, b.idproduct, c.barcode, c.namaproduct, b.jumlah,
			b.hargajual, (b.hargajual*b.jumlah) AS subtotal, sisa, diambil FROM
			detailcustomerorder b LEFT JOIN product c ON b.idproduct=c.idproduct LEFT JOIN
			supplier a ON c.idsupplier=a.idsupplier WHERE b.idcustomerorder='%d' AND
			sisa > 0",$idtitipanlaundry);
			$dataOrder = array();
			while ($data = db_fetch_object($result)){
				$dataOrder[] = $data;
			}
			drupal_add_js(
				array(
					'data_order' => $dataOrder,
				),
				'setting');
		}
	}
	$kasirtabel .= '</tbody>';
	$kasirtabel .= '</table>';
	return $kasirtabel;
}
function get_pelanggan_diskon(){
	$besarDiskon = array();
	if (isset($_POST['idpelanggan']) && isset($_REQUEST['idproduk'])){
		$idProduct = $_REQUEST['idproduk'];
		if (count($idProduct)){
			for ($i = 0;$i < count($idProduct);$i++) {
				$idKategori = db_result(db_query('SELECT idkategori FROM product WHERE idproduct = %d', $idProduct[$i]));
				if ($idKategori) {
					$besarDiskon[$idProduct[$i]] = db_result(db_query("SELECT besardiskon FROM diskonkategori WHERE idpelanggan='%d' AND idkategori='%d'", $_POST["idpelanggan"], $idKategori));
				}
			}
		}
	}
	$returnData = array($besarDiskon);
	print json_encode($returnData);
	exit();
}
