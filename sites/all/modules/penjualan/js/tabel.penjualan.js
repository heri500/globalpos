var oTable;
var pathutama = '';
var urutan = '';
var alamatupdatedetailpenjualan = '';
function addCommas(nStr){
	nStr += "";
	x = nStr.split(",");
	x1 = x[0];
	x2 = x.length > 1 ? "," + x[1] : "";
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, "$1" + "." + "$2");
	}
	return x1 + x2;
}
function tampiltabeljual(){
	oTable = $('#tabel_penjualan').dataTable( {
		'bJQueryUI': true,
		'bAutoWidth': false,
		'sPaginationType': 'full_numbers',
		'bInfo': false,
		'aLengthMenu': [[100, 200, 300, -1], [100, 200, 300, 'All']],
		'iDisplayLength': 100,
		'aaSorting': [[2, 'desc']],
		'sDom': '<\'space\'T><C><\'clear\'><\'H\'lfr>t<\'F\'ip>',
		'oColVis': {
			'activate': 'mouseover',
			'aiExclude': [ 0,1 ]
		},
		"aoColumnDefs": [
			{ "bSortable": false, "aTargets": [ 0,1,3 ] }
		]
	});
}
function tampiltabeljualdetail(){
	oTable = $("#tabel_detail_penjualan").dataTable( {
		"bJQueryUI": true,
		"bAutoWidth": false,
		"bPaginate": false,
		"bLengthChange": false,
		"bInfo": false,
		"aaSorting": [[0, "asc"]],
		"sDom": "<'H'<'toolbar'>fr>t<'F'ip>"
	});
}
function view_detail(idpenjualan,nonota){
	var request = new Object();
	request.idpenjualan = idpenjualan;
	alamat = pathutama + 'penjualan/detailpenjualan';
	$.ajax({
		type: 'POST',
		url: alamat,
		data: request,
		cache: false,
		success: function(data){
			$('#dialogdetail').html(data);
			tampiltabeljualdetail();
			$('div.toolbar').html('No. Nota : '+ nonota);
			$('#dialogdetail').dialog('open');
			/*$('.edit-jumlah').editable(alamatupdatedetailpenjualan,{
				name : 'jumlahproduk',
				width : 60,
				height : 18,
				style   : 'margin: 0',
				tooltip   : 'Klik untuk mengubah jumlah barang',
		    indicator : 'Saving...'
		  });
		  $('.edit-hargajual').editable(alamatupdatedetailpenjualan,{
				name : 'hargajual',
				width : 90,
				height : 18,
				style   : 'margin: 0',
				tooltip   : 'Klik untuk mengubah hargajual',
		    indicator : 'Saving...'
		  });*/
		}
	});
}
function delete_penjualan(idpenjualan,nonota){
	var konfirmasi = confirm('Yakin ingin menghapus penjualan dengan no nota : '+ nonota +' ini...??!!');	
	if (konfirmasi){
		window.location = pathutama + 'penjualan/deletepenjualan/'+ idpenjualan +'?destination=penjualan/viewpenjualan';	
	}
}
function print_penjualan(idpenjualan,nonota){
	var konfirmasi = confirm('Yakin ingin mencetak nota penjualan dengan no nota : '+ nonota +' ini...??!!');	
	if (konfirmasi){
		window.open(pathutama + 'print/6?idpenjualan='+ idpenjualan);	
	}
}
$(document).ready(function(){
	pathutama = Drupal.settings.basePath;
	alamatupdatetanggaljual = pathutama + 'penjualan/updatepenjualan';
	//alamatupdatedetailpenjualan = pathutama + 'penjualan/updatedetailpenjualan';
	urutan = Drupal.settings.urutan;
	$('#dialogdetail').dialog({
		modal: true,
		width: 850,
		resizable: false,
		autoOpen: false,
		position: ['auto','auto']
	});
	$('button').button();
	TableToolsInit.sSwfPath = pathutama +'misc/media/datatables/swf/ZeroClipboard.swf';
	if (urutan == 1){
		$('.edit-tanggal').editable(alamatupdatetanggaljual,{
			submitdata : function(value, settings) {
			 var idpenjualan = $(this).attr('id');
			 var splitidpenjualan = idpenjualan.split('-');
			 idpenjualan = splitidpenjualan[1];
			 var jampenjualanupdate = $('#jampenjualan-'+ idpenjualan).html();
			 return {jampenjualan: jampenjualanupdate,ubah: 'tanggal'};
   		},
			name : 'tanggaljual',
			width : 130,
			height : 18,
			style   : 'margin: 0',
			tooltip   : 'Klik untuk mengubah tanggal penjualan',
	    indicator : 'Saving...',
	    type: "datepicker",
			datepicker: {
	      changeMonth: true,
	      changeYear: true,
	      dateFormat: "dd-mm-yy"
	    },
	    callback : function(value, settings) {
      	var split_tanggal = value.split('-');
      	var tanggaljual = new Date();
      	var bulan = parseInt(split_tanggal[1]) - 1;
				tanggaljual.setFullYear(split_tanggal[2],bulan,split_tanggal[0]);
				var indexhari = tanggaljual.getDay();
				var hari = Drupal.settings.namahari[indexhari];
				var idpenjualan = $(this).attr('id');
			 	var splitidpenjualan = idpenjualan.split('-');
			 	idpenjualan = splitidpenjualan[1];
			 	$('#haripenjualan-'+ idpenjualan).html(hari);
     	}
	  });
	  $('.edit-jam').editable(alamatupdatetanggaljual,{
			name : 'jampenjualan',
			width : 120,
			height : 18,
			style   : 'margin: 0;',
			type: "timepicker",
			submitdata : function(value, settings) {
			 var idpenjualan = $(this).attr('id');
			 var splitidpenjualan = idpenjualan.split('-');
			 idpenjualan = splitidpenjualan[1];
			 var tglpenjualanupdate = $('#tglpenjualan-'+ idpenjualan).html();
			 return {tanggaljual: tglpenjualanupdate,ubah: 'jam'};
   		},
			timepicker: {
		  	timeOnlyTitle: "PILIH WAKTU",
				timeText: "Waktu",
				hourText: "Jam",
				minuteText: "Menit",
				secondText: "Detik",
				currentText: "Saat Ini",
				showButtonPanel: false
		  },
		  submit		: "Ok",
			tooltip   : 'Klik untuk mengubah jam penjualan',
	    indicator : 'Saving...'
	  });
	}
	tampiltabeljual();
	$('#tgl1').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy'
	}).css('height','27px');
	$('#tgl2').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy'
	}).css('height','27px');
	$('#navigation').css('height','32px');
})