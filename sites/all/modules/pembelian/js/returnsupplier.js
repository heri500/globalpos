var oTable;
var pathutama = '';
var giCount = 1;
var totalbelanja = 0;
var totalproduk = 0;
var barisrubah;
var tglsekarang = '';
var tgltampil = '';
var currSym = '';
var tSep = '.';
var dSep = ',';

function addCommas(nStr){
	nStr += "";
	x = nStr.split(".");
	x1 = x[0];
	x2 = x.length > 1 ? dSep + x[1] : "";
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, "$1" + tSep + "$2");
	}
	return x1 + x2;
}

function tampilkantabelkasir(){
	oTable = $('#tabel_kasir').dataTable( {
		'bJQueryUI': true,
		'bPaginate': false,
		'bLengthChange': false,
		'bFilter': true,
		'bInfo': false,
		'sScrollY': '399px',
		'aoColumns': [
		{ 'bSortable': false },{ 'bSortable': false },null,null,null,null,null
		],
		'bAutoWidth': false
	});
}
function munculkankasir(){
	$('#dialogkasir').dialog('open');
}
function ubahharga(){
	if (totalproduk > 0){
		$('#dialogubahharga').dialog('open');
	}else{
		$('#pesantext').text('Mohon pilih produk terlebih dahulu...!!!');
		$('#dialogwarning').dialog('open');
	}
}
function ubahqty(){
	if (totalproduk > 0){
		$('#dialogubahqty').dialog('open');
	}else{
		$('#pesantext').text('Mohon pilih produk terlebih dahulu...!!!');
		$('#dialogwarning').dialog('open');
	}
}
function ubah_qty_produk(posisi,nTr,idproduk){
	barisrubah = nTr;
	$('#dialogubahqty2').dialog('open');
}
function tambahproduk(){
	var request = new Object();
	request.katacari = $('#barcode').val();
	alamatcariproduk = pathutama +'pembelian/cariproduk?idsupplier='+ $('#idsupplier').val();
	$.ajax({
		type: 'POST',
		url: alamatcariproduk,
		data: request,
		cache: false,
		success: function(data){
			pecahdata = new Array();
			pecahdata = data.split(';');
			if (pecahdata[0] != 'error'){
				subtotal = addCommas(parseFloat(pecahdata[2]).toFixed(2));
				nilaikirim = pecahdata[0] +'___1___'+ pecahdata[2];
				index_cek_box = pecahdata[0].trim();
				namacekbox = 'cekbox_'+ index_cek_box;
				if($('#'+ namacekbox).val()){
					var nilaicekbox = $('#'+ namacekbox).val();
					var pecahnilai = nilaicekbox.split('___');
					var qtybaru = parseFloat(pecahnilai[1]) + 1;
					var subtotallama = parseFloat(pecahnilai[1]) * parseFloat(pecahnilai[2]);
					var subtotal = qtybaru * parseFloat(pecahnilai[2]);
					totalbelanja = parseFloat(totalbelanja) - parseFloat(subtotallama) + parseFloat(subtotal);
					totalbelanjaView = parseFloat(Math.abs(totalbelanja)).toFixed(2);
					$('#totalbelanja').html('Total Return : '+ currSym +' '+ addCommas(totalbelanjaView));
					var nTr = $('#'+ namacekbox).parent().parent().get(0);
					var posisibaris = oTable.fnGetPosition(nTr);
					oTable.fnUpdate(qtybaru, posisibaris, 4 );
					nilaikirim = pecahnilai[0].trim() +'___'+ qtybaru +'___'+ pecahnilai[2];
					checkboxnilai = '<input checked="checked" style="display: none;" id="'+ namacekbox +'" name="'+ namacekbox +'" type="checkbox" value="'+ nilaikirim +'" />';
					subtotalView = parseFloat(Math.abs(subtotal)).toFixed(2);
					oTable.fnUpdate(addCommas(subtotalView) +' '+ checkboxnilai, posisibaris, 5 );
					posisiakhir = totalproduk-1;
					if (posisibaris == posisiakhir){
						$('#lastqty').val(qtybaru);
					}
				}else{
					var icondelete = '<img onclick="hapus_produk(\''+ index_cek_box +'\',this.parentNode.parentNode,\''+ pecahdata[0].trim() +'\')" title="Klik untuk menghapus" src="'+ pathutama +'misc/media/images/close.ico" width="24">';
					var iconubah = '<img onclick="ubah_qty_produk(\''+ index_cek_box +'\',this.parentNode.parentNode,\''+ pecahdata[0].trim() +'\')" title="Klik untuk mengubah qty produk ini" src="'+ pathutama +'misc/media/images/edit.ico" width="24">';

					$('#lastharga').val(pecahdata[2]);
					$('#last_id').val(pecahdata[0].trim());
					$('#lastqty').val('1');
					checkboxnilai = '<input checked="checked" style="display: none;" id="'+ namacekbox +'" name="'+ namacekbox +'" type="checkbox" value="'+ nilaikirim +'" />';
					var row = '<tr id="'+ index_cek_box +'"><td>'+ icondelete +'</td><td>'+ iconubah +'</td><td>'+ pecahdata[1] +'</td><td class="angka">'+ subtotal +'</td><td class="angka">1</td><td class="angka">'+ subtotal +' '+ checkboxnilai +'</td><td class="angka">'+ pecahdata[3] +'</td></tr>';
					$('#tabel_kasir').dataTable().fnAddTr($(row)[0]);
					giCount++;
					totalproduk++;
					totalbelanja = parseFloat(totalbelanja) + parseFloat(pecahdata[2]);
					totalbelanjaView = parseFloat(Math.abs(totalbelanja)).toFixed(2);
					$('#totalbelanja').html('Total Return : '+ currSym +' '+ addCommas(totalbelanjaView));
				}
				$('.dataTables_scrollBody').scrollTop($('.dataTables_scrollBody')[0].scrollHeight);
				$('#barcode').autocomplete('close');
				$('#barcode').select();
			}else{
				$('#pesantext').text('Produk yang dicari atau diinput tidak ada, silahkan masukkan barcode atau kode atau nama produk yang lain...!!!');
				$('#dialogwarning').dialog('open');
			}
		}
	});
}
function kirim_data(){
	if (totalproduk > 0){
		var sData = $('input', oTable.fnGetNodes()).serialize();
		$('#nilaikirim').val(sData);
		$('#dialogbayar').dialog('open');
	}else{
		$('#pesantext').text('Mohon pilih produk terlebih dahulu...!!!');
		$('#dialogwarning').dialog('open');
	}
}
function tutupwarning(){
	$('#dialogwarning').dialog('close');
}
function hapus_latest_produk(){
	if (totalproduk > 0){
        totalbelanja = parseFloat(totalbelanja) - (parseFloat($('#lastharga').val())*parseFloat($('#lastqty').val()));
		totalbelanjaView = parseFloat(Math.abs(totalbelanja)).toFixed(2);
        $('#totalbelanja').html('Total Return : '+ currSym +' '+ addCommas(totalbelanjaView));
        oTable.fnDeleteRow(totalproduk-1);
		totalproduk--;
		if (totalproduk >= 0){
			var nTr = oTable.fnGetNodes(totalproduk-1);
			idproduknya = nTr.getAttribute('id');
			var nilaidataakhir = $('#cekbox_'+ idproduknya).val();
			var pecahnilaiakhir = nilaidataakhir.split('___');
			$('#lastharga').val(pecahnilaiakhir[2]);
			$('#lastqty').val(pecahnilaiakhir[1]);
			$('#last_id').val(pecahnilaiakhir[0]);
		}else{
			$('#lastharga').val('');
			$('#lastqty').val('');
			$('#last_id').val('');
		}
	}else{
		$('#pesantext').text('Mohon pilih produk terlebih dahulu...!!!');
		$('#dialogwarning').dialog('open');
	}
}
function focusbarcode(){
	$('#barcode').select();
}
function hapus_produk(posisi,nTr,idproduk){
	var posisibaris = oTable.fnGetPosition(nTr);
	var nilaidata = $('#cekbox_'+ idproduk).val();
    console.log(nilaidata);
	var pecahnilai = nilaidata.split('___');
	totalbelanja = parseFloat(totalbelanja) - (parseFloat(pecahnilai[1])*parseFloat(pecahnilai[2]));
	totalbelanjaView = parseFloat(Math.abs(totalbelanja)).toFixed(2);
	$('#totalbelanja').html('Total Return : '+ currSym +' '+ addCommas(totalbelanjaView));
	oTable.fnDeleteRow(posisibaris,focusbarcode);
	totalproduk--;
	if (totalproduk > 0){
		var nTr = oTable.fnGetNodes(totalproduk-1);
		idproduknya = nTr.getAttribute('id');
		var nilaidataakhir = $('#cekbox_'+ idproduknya).val();
		var pecahnilaiakhir = nilaidataakhir.split('___');
		$('#lastharga').val(pecahnilaiakhir[2]);
		$('#lastqty').val(pecahnilaiakhir[1]);
		$('#last_id').val(pecahnilaiakhir[0]);
	}else{
		$('#lastharga').val('');
		$('#lastqty').val('');
		$('#last_id').val('');
	}
	$('#barcode').focus();
	$('#barcode').select();
}
function akhiri_belanja(){
	if ($('#carabayar').val() == 3){
		if ($('#nilaibayar').val() >= totalbelanja){
			do_selesai();
		}else{
			alert('Mohon isi pembayaran dengan nilai yang lebih besar atau sama dengan Total Return...!!!');
		}
	}else{
		do_selesai();
	}
}
function do_selesai(){
	var request = new Object();
	request.idsupplier = $('#idsupplier').val();
	request.detail_produk = $('#nilaikirim').val();
	request.totalbelanja = totalbelanja;
	request.bayar = $('#nilaibayar').val();
	request.tglreturn = $('#tglreturnkirim').val();
	request.carareturn = $('#carabayar').val();
	alamat = pathutama + 'pembelian/simpanreturn';
	$.ajax({
		type: 'POST',
		url: alamat,
		data: request,
		cache: false,
		success: function(data){
			if (data != 'error'){
				window.location = pathutama + 'return/kasir?tanggal='+ request.tglreturn;
			}else{
				alert('Ada masalah dalam penyimpanan data...!!!');
			}
		}
	});
}
function inisialulangautocomplete(){
	$('#barcode').autocomplete('destroy');
	$('#barcode').autocomplete({
		source: pathutama + 'pembelian/autocaribarang?idsupplier='+ $('#idsupplier').val(),
		select: function (event, ui) {
			$("#barcode").val(ui.item.value);
			tambahproduk();
		}
	});
	$('#barcode').select();
}
$(document).ready(function(){
	var tglsekarang = Drupal.settings.tglsekarang;
	var tgltampil = Drupal.settings.tgltampil;
	pathutama = Drupal.settings.basePath;

	currSym = Drupal.settings.currSym;
	tSep = Drupal.settings.tSep;
	dSep = Drupal.settings.dSep;
	
	$('#dialogkasir').dialog({
		modal: true,
		width: 925,
		closeOnEscape: false,
		height: 730,
		resizable: false,
		autoOpen: false,
		open: function(event, ui) {
			$('#tempatjam').clock({'format':'24','calendar':'false'});
			$('#barcode').focus();
		},
		position: ['auto','auto']
	});
	$('#dialogwarning').dialog({
		modal: true,
		width: 400,
		closeOnEscape: false,
		resizable: false,
		autoOpen: false,
		open: function(event, ui) {
			$('#tutupdialog').focus();
		},
		close: function(){
			$('#barcode').select();
		},
		position: ['auto','auto']
	});
	$('#dialogubahqty').dialog({
		modal: true,
		width: 250,
		height: 100,
		closeOnEscape: false,
		resizable: false,
		autoOpen: false,
		open: function(event, ui) {
			$('#newqty').val($('#lastqty').val());
			$('#newqty').select();
		},
		close: function(){
			$('#barcode').select();
		},
		position: ['auto','auto']
	});
	$('#dialogubahharga').dialog({
		modal: true,
		width: 250,
		height: 100,
		closeOnEscape: false,
		resizable: false,
		autoOpen: false,
		open: function(event, ui) {
			$('#newharga').val($('#lastharga').val());
			$('#newharga').select();
		},
		close: function(){
			$('#barcode').select();
		},
		position: ['auto','auto']
	});
	$('#dialogubahqty2').dialog({
		modal: true,
		width: 250,
		height: 100,
		closeOnEscape: false,
		resizable: false,
		autoOpen: false,
		open: function(event, ui) {
			idproduknya = barisrubah.getAttribute('id');
			var nilaidata = $('#cekbox_'+ idproduknya).val();
			var pecahnilai = nilaidata.split('___');
			$('#newqty2').val(pecahnilai[1]);
			$('#newqty2').select();
		},
		close: function(){
			$('#barcode').select();
		},
		position: ['auto','auto']
	});
	$('#dialogbayar').dialog({
		modal: true,
		width: 550,
		closeOnEscape: false,
		resizable: false,
		autoOpen: false,
		open: function(event, ui) {
			totalbelanjaView = parseFloat(Math.abs(totalbelanja)).toFixed(2);
			$('#totalbelanjauser').val(currSym +' '+ addCommas(totalbelanjaView));
			$('#carabayar').change();
		},
		close: function(){
			$('#barcode').select();
		},
		position: ['auto','auto']
	});
	tampilkantabelkasir();
	$('#dialogkasir').dialog('open');
	$('.ui-dialog-titlebar').css('font-size','14px');
	$('button').button();
	$('#barcode').keypress(function(e) {
		if (e.keyCode == 114){
			$('#tombolubahqty').click();
		}else if (e.keyCode == 13){
			if ($('#barcode').val() != ''){
				tambahproduk();
			}else{
				$('#pesantext').text('Mohon isi barcode atau kode produk atau nama produk yang ingin dicari...!!!');
				$('#dialogwarning').dialog('open');
			}
		}else if (e.keyCode == 116 || e.keyCode == 117){
			kirim_data();
		}else if (e.keyCode == 115){
			hapus_latest_produk();
		}else if (e.keyCode == 113){
			$('#tombolubahharga').click();
		}
	});
	$('#barcode').autocomplete({
		source: pathutama + 'pembelian/autocaribarang?idsupplier='+ $('#idsupplier').val(),
		select: function (event, ui) {
			$("#barcode").val(ui.item.value);
			tambahproduk();
		}
	});
	$('#newqty').autotab_filter({
		format: 'numeric',
		nospace: true
	});
	$('#newqty').keypress(function(e) {
		if (e.keyCode == 13){
			var baris_int = totalproduk-1;
			totalbelanja = parseFloat(totalbelanja) - (parseFloat($('#lastharga').val())*parseFloat($('#lastqty').val()));
			var nilaiubah = $('#newqty').val();
			oTable.fnUpdate(nilaiubah, baris_int, 4 );
			nilaisubtotal = parseFloat($('#lastharga').val())*parseFloat(nilaiubah);
			nilaisubtotalView = parseFloat(nilaisubtotal).toFixed(2);
			subtotalbaru = addCommas(nilaisubtotalView);
			var namacekbox = 'cekbox_'+ $('#last_id').val();
			var nilaikirim = $('#last_id').val() +'___'+ nilaiubah +'___'+ $('#lastharga').val();
			var checkboxnilai = '<input checked="checked" style="display: none;" id="'+ namacekbox +'" name="'+ namacekbox +'" type="checkbox" value="'+ nilaikirim +'" />';
			oTable.fnUpdate(subtotalbaru +' '+ checkboxnilai, baris_int, 5 );
			$('#lastqty').val(nilaiubah);
			totalbelanja = parseFloat(totalbelanja) + (parseFloat($('#lastharga').val())*parseFloat($('#lastqty').val()));
			totalbelanjaView = parseFloat(totalbelanja).toFixed(2);
			$('#totalbelanja').html('Total Return : '+ currSym +' '+ addCommas(totalbelanjaView));
			$('#dialogubahqty').dialog('close');
		}
	});
	$('#newharga').keypress(function(e) {
		if (e.keyCode == 13){
			var baris_int = totalproduk-1;
			totalbelanja = parseFloat(totalbelanja) - (parseFloat($('#lastharga').val())*parseFloat($('#lastqty').val()));
			var nilaiubah = $('#newharga').val();
			oTable.fnUpdate(nilaiubah, baris_int, 3 );
			nilaisubtotal = parseFloat($('#lastqty').val())*parseFloat(nilaiubah);
			subtotalbaru = parseFloat(nilaisubtotal).toFixed(2);
			subtotalbaru = addCommas(subtotalbaru);
			var namacekbox = 'cekbox_'+ $('#last_id').val();
			var nilaikirim = $('#last_id').val() +'___'+ $('#lastqty').val() +'___'+ nilaiubah;
			var checkboxnilai = '<input checked="checked" style="display: none;" id="'+ namacekbox +'" name="'+ namacekbox +'" type="checkbox" value="'+ nilaikirim +'" />';
			oTable.fnUpdate(subtotalbaru +' '+ checkboxnilai, baris_int, 5 );
			$('#lastharga').val(nilaiubah);
			totalbelanja = parseFloat(totalbelanja) + (parseFloat($('#lastharga').val())*parseFloat($('#lastqty').val()));
			totalbelanjaView = parseFloat(totalbelanja).toFixed(2);
			$('#totalbelanja').html('Total Return : '+ currSym +' '+ addCommas(totalbelanjaView));
			$('#dialogubahharga').dialog('close');
		}
	});
	$('#newqty2').keypress(function(e) {
		if (e.keyCode == 13){
			var baris_int = oTable.fnGetPosition(barisrubah);
			var idproduknya = barisrubah.getAttribute('id');
			var nilaidata = $('#cekbox_'+ idproduknya).val();
			var pecahnilai = nilaidata.split('___');
			totalbelanja = parseFloat(totalbelanja) - (parseFloat(pecahnilai[1])*parseFloat(pecahnilai[2]));
			var nilaiubah = $('#newqty2').val();
			oTable.fnUpdate(nilaiubah, baris_int, 4 );
			nilaisubtotal = parseFloat(pecahnilai[2])*parseFloat(nilaiubah);
			subtotalbaru = parseFloat(nilaisubtotal).toFixed(2);
			subtotalbaru = addCommas(subtotalbaru);
			var namacekbox = 'cekbox_'+ idproduknya;
			var nilaikirim = idproduknya +'___'+ nilaiubah +'___'+ pecahnilai[2];
			var checkboxnilai = '<input checked="checked" style="display: none;" id="'+ namacekbox +'" name="'+ namacekbox +'" type="checkbox" value="'+ nilaikirim +'" />';
			oTable.fnUpdate(subtotalbaru +' '+ checkboxnilai, baris_int, 5 );
			totalbelanja = parseFloat(totalbelanja) + (parseFloat(pecahnilai[2])*parseFloat(nilaiubah));
			posisiakhir = totalproduk-1;
			if (baris_int == posisiakhir){
				$('#lastqty').val(nilaiubah);
			}
			totalbelanjaView = parseFloat(totalbelanja).toFixed(2);
			$('#totalbelanja').html('Total Return : '+ currSym +' '+ addCommas(totalbelanjaView));
			$('#dialogubahqty2').dialog('close');
		}
	});
	$('#nilaibayar').keyup(function(e) {
		kembali = $('#nilaibayar').val() - totalbelanja;
		kembaliView = parseFloat(kembali).toFixed(2);
		$('#kembali').val(currSym + ' ' + addCommas(kembaliView));
		if (e.keyCode == 13) {
			akhiri_belanja();
		}
	});
	$('#tglreturn').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy',
		altField: '#tglreturnkirim',
		altFormat: 'yy-mm-dd',
		onClose: function(dateText, inst) {
			$('#barcode').select();
		}
	});
	$("#tglreturn").css("width","177px");
	$('#carabayar').change(function(){
		if ($('#carabayar').val() == 3){
			$('#nilaibayar').val(totalbelanja);
			kembali = $('#nilaibayar').val()-totalbelanja;
			$('#bariskembali').show();
			kembaliView = parseFloat(kembali).toFixed(2);
			$('#kembali').val(currSym +' '+ addCommas(kembaliView));
			$('#nilaibayar').removeAttr('readonly','readonly');
			$('#nilaibayar').select();
		}else{
			if ($('#carabayar').val() == 2){
				$('#nilaibayar').val(totalbelanja);
				$('#kembali').val(0);
				$('#cashback').show();
				$('#nilaibayar').attr('readonly','readonly');
				$('#nilaibayar').select();
			}else{
				$('#cashback').hide();
				$('#nilaibayar').val(0);
			}
			$('#bariskembali').hide();
		}
	});
	$('#carabayar').keyup(function(e) {
		if (e.keyCode == 13) {
			akhiri_belanja();
		}
	});
	$('#info-kasir-waktu').css('background','url('+ Drupal.settings.logo +') 99% 50% no-repeat');
	$('#info-kasir-waktu').css('background-size','75px 75px');
})