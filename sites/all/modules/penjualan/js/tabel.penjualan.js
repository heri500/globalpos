var oTable;
var pathutama = '';
var tglAwal = '';
var tglAkhir = '';
var urutan = 0;
var tampilData = 0;
var idpelanggan = 0;
var idsupplier = 0;
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
	if (tampilData == 0){
		oTable = $('#tabel_penjualan').dataTable( {
			'bJQueryUI': true,
			'bAutoWidth': false,
			'sPaginationType': 'full_numbers',
			'bInfo': true,
			'aLengthMenu': [[100, 200, 300, -1], [100, 200, 300, 'All']],
			'iDisplayLength': 100,
			'aaSorting': [[urutan, 'desc']],
			'processing': true,
			'serverSide': true,
			'ajax': Drupal.settings.basePath + 'sites/all/modules/datapelanggan/server_processing.php?request_data=penjualan&tglawal='+ tglAwal +'&tglakhir='+ tglAkhir +'&idpelanggan='+ Drupal.settings.filterId,
			buttons: [
				{
					extend: 'colvis',
					columns: [1,2,3,4,5,6,7,8,9,10,11]
				}, 'copy', 'excel', 'print'
			],
			'sDom': '<"button-div"B><"H"lfr>t<"F"ip>',
			'createdRow': function ( row, data, index ) {
				row.id = data[(data.length - 1)];
				$('td', row).eq(1).addClass('center');
				$('td', row).eq(2).addClass('center');
				$('td', row).eq(3).addClass('center');
				$('td', row).eq(4).addClass('angka');
				$('td', row).eq(5).addClass('angka');
				$('td', row).eq(6).addClass('angka');
				$('td', row).eq(7).addClass('center');
				$('td', row).eq(8).addClass('angka');
				$('td', row).eq(9).addClass('angka');
				$('td', row).eq(10).addClass('center');
				$('td', row).eq(11).addClass('center');
				$('td', row).eq(12).addClass('center');
			},
            'aoColumnDefs': [
                { 'bSortable': false, 'aTargets': [ 0,3,12 ] }
            ],
			'footerCallback': function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
					i.replace(/[\$.]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				// Total over all pages
				total = api
					.column( 4 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 4 ).footer() ).html(
					'Rp. '+ addCommas(total)
				).addClass('angka');
				total = api
					.column( 5 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 5 ).footer() ).html(
					'Rp. '+ addCommas(total)
				).addClass('angka');
				total = api
					.column( 6 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 6 ).footer() ).html(
					'Rp. '+ addCommas(total)
				).addClass('angka');
				total = api
					.column( 8 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 8 ).footer() ).html(
					'Rp. '+ addCommas(total)
				).addClass('angka');
				total = api
					.column( 9 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 9 ).footer() ).html(
					'Rp. '+ addCommas(total)
				).addClass('angka');
			},
		});
	}else if (tampilData == 1){
		oTable = $('#tabel_penjualan').dataTable( {
			'bJQueryUI': true,
			'bAutoWidth': false,
			'sPaginationType': 'full_numbers',
			'bInfo': true,
			'aLengthMenu': [[100, 200, 300, -1], [100, 200, 300, 'All']],
			'iDisplayLength': 100,
			'aaSorting': [[urutan, 'desc']],
			'processing': true,
			'serverSide': true,
			'ajax': Drupal.settings.basePath + 'sites/all/modules/datapelanggan/server_processing.php?request_data=penjualan2&tglawal='+ tglAwal +'&tglakhir='+ tglAkhir +'&idsupplier='+ Drupal.settings.filterId,
			buttons: [
				{
					extend: 'colvis'
				}, 'copy', 'excel', 'print'
			],
			'sDom': '<"button-div"B><"H"lfr>t<"F"ip>',
			'createdRow': function ( row, data, index ) {
				row.id = data[(data.length - 1)];
				$('td', row).eq(0).addClass('center');
				$('td', row).eq(3).addClass('angka');
				$('td', row).eq(4).addClass('angka');
				$('td', row).eq(5).addClass('angka');
				$('td', row).eq(6).addClass('angka');
				$('td', row).eq(7).addClass('angka');
				$('td', row).eq(8).addClass('angka');
				$('td', row).eq(9).addClass('angka');
				$('td', row).eq(10).addClass('angka');
			},
			'footerCallback': function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
					i.replace(/[\$.]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				// Total over all pages
				total = api
					.column( 8 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 8 ).footer() ).html(
					'Rp. '+ addCommas(total)
				).addClass('angka');
				total = api
					.column( 9 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 9 ).footer() ).html(
					'Rp. '+ addCommas(total)
				).addClass('angka');
				total = api
					.column( 10 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 10 ).footer() ).html(
					'Rp. '+ addCommas(total)
				).addClass('angka');
			},
		});
	}else if (tampilData == 2){
		oTable = $('#tabel_penjualan').dataTable( {
			'bJQueryUI': true,
			'bAutoWidth': false,
			'sPaginationType': 'full_numbers',
			'bInfo': true,
			'aLengthMenu': [[100, 200, 300, -1], [100, 200, 300, 'All']],
			'iDisplayLength': 100,
			'aaSorting': [[urutan, 'desc']],
			'processing': true,
			'serverSide': true,
			'ajax': Drupal.settings.basePath + 'sites/all/modules/datapelanggan/server_processing.php?request_data=penjualan3&tglawal='+ tglAwal +'&tglakhir='+ tglAkhir,
			buttons: [
				{
					extend: 'colvis'
				}, 'copy', 'excel', 'print'
			],
			'sDom': '<"button-div"B><"H"lfr>t<"F"ip>',
			'createdRow': function ( row, data, index ) {
				row.id = data[(data.length - 1)];
				$('td', row).eq(0).addClass('center');
				$('td', row).eq(2).addClass('angka');
				$('td', row).eq(3).addClass('angka');
				$('td', row).eq(4).addClass('angka');
			},
			'footerCallback': function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
					i.replace(/[\$.]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				// Total over all pages
				total = api
					.column( 2 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 2 ).footer() ).html(
					'Rp. '+ addCommas(total)
				).addClass('angka');
				total = api
					.column( 3 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 3 ).footer() ).html(
					'Rp. '+ addCommas(total)
				).addClass('angka');
				total = api
					.column( 4 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 4 ).footer() ).html(
					'Rp. '+ addCommas(total)
				).addClass('angka');
			},
		});
	}
}
function tampiltabeljualdetail(){
	oTable = $('#tabel_detail_penjualan').dataTable( {
		'bJQueryUI': true,
		'bAutoWidth': false,
		'bPaginate': false,
		'bLengthChange': false,
		'bInfo': false,
		'aaSorting': [[0, 'asc']],
		'sDom': '<"H"<"toolbar">fr>t<"F"ip>'
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
		}
	});
}
function print_penjualan(idpenjualan,nonota){
	var konfirmasi = confirm('Yakin ingin mencetak nota penjualan dengan no nota : '+ nonota +' ini...??!!');
	if (konfirmasi){
		window.open(pathutama + 'print/6?idpenjualangh='+ idpenjualan);
	}
}
$(document).ready(function(){
        console.log(Drupal.settings.filterId);
	pathutama = Drupal.settings.basePath;
	urutan = Drupal.settings.urutan;
	tampilData = Drupal.settings.tampilData;
    tglAwal = Drupal.settings.tglAwal;
    tglAkhir = Drupal.settings.tglAkhir;
	$('#dialogdetail').dialog({
		modal: true,
		width: 850,
		resizable: false,
		autoOpen: false,
		position: ['auto','auto']
	});
	$('button').button();
	/*TableToolsInit.sSwfPath = pathutama +'misc/media/datatables/swf/ZeroClipboard.swf';*/
	tampiltabeljual();
	$('#tgl1').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
	$('#tgl2').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
})