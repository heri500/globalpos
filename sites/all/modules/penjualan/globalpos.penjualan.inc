<?php

function view_tabel_penjualan(){
    $path = drupal_get_path('theme', 'cti_flex');
    $form_style = $path.'/css/custom-style.css';
    drupal_add_css($form_style, 'theme', 'all', FALSE);
	if ($_POST["tgl1"] AND $_POST["tgl2"]){
		$tgl1 = $_POST["tgl1"];
		$tgl2 = $_POST["tgl2"];
	}else{
		$tgl1 = date("Y-m-d");
		$tgl2 = date("Y-m-d");
	}
	if ($_POST["pilihantampil"] == 'NOTA'){
		$nota = 'selected="selected"';
		$tabeltampil = tabel_penjualan($tgl1,$tgl2);
		$urutan = 1;
		$tampilData = 0;
	}elseif ($_POST["pilihantampil"] == 'PRODUK'){
		$produk = 'selected="selected"';
		$tabeltampil = penjualan_produk($tgl1,$tgl2);
		$urutan = 3;
		$tampilData = 1;
	}else{
		$tabeltampil = tabel_penjualan($tgl1,$tgl2);
		$nota = 'selected="selected"';
		$urutan = 1;
		$tampilData = 0;
	}
	drupal_add_css('misc/media/datatables.1.10/jquery/jquery-ui.css');
	drupal_add_css('misc/media/datatables.1.10/media/css/dataTables.jqueryui.css');
  drupal_add_css('misc/media/datatables.1.10/extensions/Buttons/css/buttons.dataTables.css');
  drupal_add_css('misc/media/datatables.1.10/extensions/Buttons/css/buttons.jqueryui.css');
	drupal_add_js('misc/media/datatables.1.10/media/js/jquery.js');
	drupal_add_js('misc/media/jqueryValidate/js/jquery.validationEngine-en.js');
	drupal_add_js('misc/media/jqueryValidate/js/jquery.validationEngine.js');
	drupal_add_js('misc/media/datatables.1.10/jquery/jquery-ui.js');
	drupal_add_js('misc/media/datatables.1.10/media/js/jquery.dataTables.js');
	drupal_add_js('misc/media/datatables.1.10/media/js/dataTables.jqueryui.js');
  drupal_add_js('misc/media/datatables.1.10/extensions/Buttons/js/dataTables.buttons.js');
  drupal_add_js('misc/media/datatables.1.10/extensions/Buttons/js/buttons.flash.js');
  drupal_add_js('misc/media/datatables.1.10/extensions/Buttons/js/buttons.html5.js');
  drupal_add_js('misc/media/datatables.1.10/extensions/Buttons/js/buttons.print.js');
  drupal_add_js('misc/media/datatables.1.10/extensions/Buttons/js/buttons.colVis.min.js');
	drupal_add_js('
		var oTable;
		var pathutama = "'.base_path().'";
		var urutan = "'.$urutan.'";
		var tampilData = "'.$tampilData.'";
		function tampiltabeljual(){
			if (tampilData == 0){
				oTable = $("#tabel_penjualan").dataTable( {
					"bJQueryUI": true,
					"bAutoWidth": false,
					"sPaginationType": "full_numbers",
					"bInfo": true,
					"aLengthMenu": [[100, 200, 300, -1], [100, 200, 300, "All"]],
					"iDisplayLength": 100,
					"aaSorting": [[urutan, "desc"]],
					"processing": true,
	        		"serverSide": true,
					"ajax": Drupal.settings.basePath + "sites/all/modules/datapelanggan/server_processing.php?request_data=penjualan&tglawal='.$tgl1.'&tglakhir='.$tgl2.'",
					buttons: [
	        	{
	          	extend: \'colvis\',
	            columns: \':not(:first-child)\'
	          }, "copy", "excel", "print"
	        ],
					"sDom": \'<"button-div"B><"H"lfr>t<"F"ip>\',
					"createdRow": function ( row, data, index ) {
						row.id = data[(data.length - 1)];
						$("td", row).eq(1).addClass("center");
						$("td", row).eq(2).addClass("center");
						$("td", row).eq(3).addClass("center");
						$("td", row).eq(4).addClass("angka");
						$("td", row).eq(5).addClass("angka");
						$("td", row).eq(6).addClass("angka");
						$("td", row).eq(7).addClass("center");
						$("td", row).eq(8).addClass("angka");
						$("td", row).eq(9).addClass("angka");
						$("td", row).eq(10).addClass("center");
					}
				});
			}else if (tampilData == 1){
				oTable = $("#tabel_penjualan").dataTable( {
					"bJQueryUI": true,
					"bAutoWidth": false,
					"sPaginationType": "full_numbers",
					"bInfo": true,
					"aLengthMenu": [[100, 200, 300, -1], [100, 200, 300, "All"]],
					"iDisplayLength": 100,
					"aaSorting": [[urutan, "desc"]],
					"processing": true,
	        "serverSide": true,
					"ajax": Drupal.settings.basePath + "sites/all/modules/datapelanggan/server_processing.php?request_data=penjualan2&tglawal='.$tgl1.'&tglakhir='.$tgl2.'",
					buttons: [
	        	{
	          	extend: \'colvis\',
	            columns: \':not(:first-child)\'
	          }, "copy", "excel", "print"
	        ],
					"sDom": \'<"button-div"B><"H"lfr>t<"F"ip>\',
					"createdRow": function ( row, data, index ) {
						row.id = data[(data.length - 1)];
						$("td", row).eq(0).addClass("center");
						$("td", row).eq(3).addClass("angka");
						$("td", row).eq(4).addClass("angka");
						$("td", row).eq(5).addClass("angka");
						$("td", row).eq(6).addClass("angka");
						$("td", row).eq(7).addClass("angka");
						$("td", row).eq(8).addClass("angka");
						$("td", row).eq(9).addClass("angka");
						$("td", row).eq(10).addClass("angka");
					}
				});
			}
		}
		function tampiltabeljualdetail(){
			oTable = $("#tabel_detail_penjualan").dataTable( {
				"bJQueryUI": true,
				"bAutoWidth": false,
				"bPaginate": false,
				"bLengthChange": false,
				"bInfo": false,
				"aaSorting": [[0, "asc"]],
				"sDom": \'<"H"<"toolbar">fr>t<"F"ip>\'
			});
		}
		function view_detail(idpenjualan,nonota){
			var request = new Object();
			request.idpenjualan = idpenjualan;
			alamat = pathutama + "penjualan/detailpenjualan";
			$.ajax({ 
				type: "POST",
				url: alamat,
				data: request, 
				cache: false, 
				success: function(data){
					$("#dialogdetail").html(data);
					tampiltabeljualdetail();
					$("div.toolbar").html("No. Nota : "+ nonota);
					$("#dialogdetail").dialog("open");
				}
			});
		}
		$(document).ready(function(){
			$("#dialogdetail").dialog({
				modal: true,
				width: 850,
				resizable: false,
				autoOpen: false,
				position: ["auto","auto"]
			});
			$("button").button();
			/*TableToolsInit.sSwfPath = pathutama +"misc/media/datatables/swf/ZeroClipboard.swf";*/
			tampiltabeljual();
			$("#tgl1").datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: "yy-mm-dd"
			});
			$("#tgl2").datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: "yy-mm-dd"
			});
		})
	','inline');
	
	$pilihantampil = '<select id="pilihantampil" name="pilihantampil">';
	$pilihantampil .= '<option '.$nota.'>NOTA</option>';
	$pilihantampil .= '<option '.$produk.'>PRODUK</option>';
	$pilihantampil .= '</select>';
	$pilihperiode = '<form action="'.base_path().'penjualan/viewpenjualan" method="post"><div id="pilihanperiode"><label>PILIH PERIODE PENJUALAN </label><input readonly="readonly" type="text" id="tgl1" name="tgl1" value="'.$tgl1.'">';
	$pilihperiode .= '<input readonly="readonly" type="text" id="tgl2" name="tgl2" value="'.$tgl2.'">'.$pilihantampil.'<button>LIHAT PENJUALAN</button></div></form>';
	$viewpenjualan = $pilihperiode.'<div id="viewpenjualan">'.$tabeltampil.'</div>';
	$viewpenjualan .= '<div id="dialogdetail" title="DETAIL PENJUALAN"></div>';
	return $viewpenjualan;
}

function tabel_penjualan($tgl1,$tgl2){
	//nonota, tglpenjualan, idpemakai, idlangganan, total, 
	//totalmodal, carabayar, bayar, kembali
	//$tgl2 = DateAdd(1,$tgl2,"Y-m-d");
	$tabelpenjualan ='<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabel_penjualan">';
	$tabelpenjualan .= '<thead>';
	$tabelpenjualan .= '<tr>';
	$tabelpenjualan .= '<th class="tablebutton"></th>';
	$tabelpenjualan .= '<th>NO. NOTA</th>';
	$tabelpenjualan .= '<th class="tanggal">TGL</th>';
	$tabelpenjualan .= '<th class="tanggal">JAM</th>';
	$tabelpenjualan .= '<th>TOTAL</th>';
	$tabelpenjualan .= '<th>MODAL</th>';
	$tabelpenjualan .= '<th>LABA</th>';
	$tabelpenjualan .= '<th>CARA BAYAR</th>';
	$tabelpenjualan .= '<th>BAYAR</th>';
	$tabelpenjualan .= '<th>KEMBALI</th>';
	$tabelpenjualan .= '<th>KASIR</th>';
	$tabelpenjualan .= '</tr>';
	$tabelpenjualan .= '</thead>';
	$tabelpenjualan .= '<tbody>';
	$tabelpenjualan .= '</tbody>';
	$tabelpenjualan .= '<tfoot>';
	$tabelpenjualan .= '<tr>';
	$tabelpenjualan .= '<th style="text-align:right" colspan="4">Total:&nbsp;&nbsp;</th>';
	$totaljual = getTotalPenjualan('total',$tgl1,$tgl2);
	$totalmodal = getTotalPenjualan('totalmodal',$tgl1,$tgl2);
	$totallaba = $totaljual - $totalmodal;
	$totalbayar = getTotalPenjualan('bayar',$tgl1,$tgl2);
	$totalkembali = getTotalPenjualan('kembali',$tgl1,$tgl2);
	$tabelpenjualan .= '<th style="text-align: right">Rp. '.number_format($totaljual,0,",",".").'&nbsp;&nbsp;</th>';
	$tabelpenjualan .= '<th style="text-align: right">Rp. '.number_format($totalmodal,0,",",".").'&nbsp;&nbsp;</th>';
	$tabelpenjualan .= '<th style="text-align: right">Rp. '.number_format($totallaba,0,",",".").'&nbsp;&nbsp;</th>';
	$tabelpenjualan .= '<th></th>';
	$tabelpenjualan .= '<th style="text-align: right">Rp. '.number_format($totalbayar,0,",",".").'&nbsp;&nbsp;</th>';
	$tabelpenjualan .= '<th style="text-align: right">Rp. '.number_format($totalkembali,0,",",".").'&nbsp;&nbsp;</th>';
	$tabelpenjualan .= '<th></th>';
	$tabelpenjualan .= '</tr>';
	$tabelpenjualan .= '</tfoot>';
	$tabelpenjualan .= '</table>';
	return $tabelpenjualan;
}

function detail_penjualan(){
	if ($_POST["idpenjualan"]){
		$tabelpenjualan ='<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabel_detail_penjualan">';
		$tabelpenjualan .= '<thead>';
		$tabelpenjualan .= '<tr>';
		$tabelpenjualan .= '<th>BARCODE</th>';
		$tabelpenjualan .= '<th>PRODUK</th>';
		$tabelpenjualan .= '<th class="qty">QTY</th>';
		$tabelpenjualan .= '<th>H.JUAL</th>';
		$tabelpenjualan .= '<th>H.MODAL</th>';
		$tabelpenjualan .= '<th>SUBTOTAL</th>';
		$tabelpenjualan .= '<th>MODAL</th>';
		$tabelpenjualan .= '<th>LABA</th>';
		$tabelpenjualan .= '</tr>';
		$tabelpenjualan .= '</thead>';
		$tabelpenjualan .= '<tbody>';
		$result = db_query("SELECT c.barcode, c.namaproduct, b.jumlah, b.hargajual, b.hargapokok,
	  	(b.hargajual-b.hargapokok)*b.jumlah AS laba,(b.hargajual*b.jumlah) AS subtotal,
	  	(b.hargapokok*b.jumlah) AS totalmodal FROM detailpenjualan b LEFT JOIN product c
	  	ON b.idproduct=c.idproduct LEFT JOIN supplier a
	  	ON a.idsupplier=c.idsupplier WHERE b.idpenjualan='%d'",$_POST["idpenjualan"]);
		while($data = db_fetch_object($result)){
			$tabelpenjualan .= '<tr>';
			$tabelpenjualan .= '<td>'.$data->barcode.'</td>';
			$tabelpenjualan .= '<td>'.$data->namaproduct.'</td>';
			$tabelpenjualan .= '<td class="angka">'.$data->jumlah.'</td>';
			$tabelpenjualan .= '<td class="angka">'.$data->hargajual.'</td>';
			$tabelpenjualan .= '<td class="angka">'.$data->hargapokok.'</td>';
			$tabelpenjualan .= '<td class="angka">'.$data->subtotal.'</td>';
			$tabelpenjualan .= '<td class="angka">'.$data->totalmodal.'</td>';
			$tabelpenjualan .= '<td class="angka">'.$data->laba.'</td>';
			$tabelpenjualan .= '</tr>';
		}
		$tabelpenjualan .= '</tbody>';
		$tabelpenjualan .= '</table><br><br>';
		print $tabelpenjualan;
	}
	exit();	
}
function penjualan_produk($tgl1,$tgl2){
	//$tgl2 = DateAdd(1,$tgl2,"Y-m-d");
	$tabelpenjualan ='<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabel_penjualan">';
	$tabelpenjualan .= '<thead>';
	$tabelpenjualan .= '<tr>';
	$tabelpenjualan .= '<th>BARCODE</th>';
	$tabelpenjualan .= '<th>PRODUK</th>';
	$tabelpenjualan .= '<th>SUPPLIER</th>';
	$tabelpenjualan .= '<th class="qty">QTY</th>';
	$tabelpenjualan .= '<th>H.JUAL MIN</th>';
	$tabelpenjualan .= '<th>H.JUAL MAX</th>';
	$tabelpenjualan .= '<th>H.MODAL MIN</th>';
	$tabelpenjualan .= '<th>H.MODAL MAX</th>';
	$tabelpenjualan .= '<th>SUBTOTAL</th>';
	$tabelpenjualan .= '<th>MODAL</th>';
	$tabelpenjualan .= '<th>LABA</th>';
	$tabelpenjualan .= '</tr>';
	$tabelpenjualan .= '</thead>';
	$tabelpenjualan .= '<tbody>';
	$totaljual = getTotalPenjualan2('jumlah','hargajual',$tgl1,$tgl2);
	$totalmodal = getTotalPenjualan2('jumlah','hargapokok',$tgl1,$tgl2);
	$totallaba = $totaljual - $totalmodal;
	$tabelpenjualan .= '</tbody>';
	$tabelpenjualan .= '<tfoot>';
	$tabelpenjualan .= '<tr>';
	$tabelpenjualan .= '<th style="text-align:right" colspan="8">Total:&nbsp;&nbsp;</th>';
	$tabelpenjualan .= '<th style="text-align: right">Rp. '.number_format($totaljual,0,",",".").'&nbsp;&nbsp;</th>';
	$tabelpenjualan .= '<th style="text-align: right">Rp. '.number_format($totalmodal,0,",",".").'&nbsp;&nbsp;</th>';
	$tabelpenjualan .= '<th style="text-align: right">Rp. '.number_format($totallaba,0,",",".").'&nbsp;&nbsp;</th>';
	$tabelpenjualan .= '</tr>';
	$tabelpenjualan .= '</tfoot>';
	$tabelpenjualan .= '</table>';
	return $tabelpenjualan;	
}

function DateAdd($v,$d=null , $f="m/d/Y"){ 
 /*
 To Use
 $TGLHITUNG = date("m/d/Y");
 $TGLAKHIRHITUNG = DateAdd(30,$TGLHITUNG);
 */	
 $d=($d?$d:date("Y-m-d")); 
 return date($f,strtotime($v." days",strtotime($d))); 
}
