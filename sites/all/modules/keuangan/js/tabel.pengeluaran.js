var oTableKategori;
var oTablePengeluaran;
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
function tampiltabelkategori(){
	oTableKategori = $('#'+ Drupal.settings.idtabelkategori).dataTable( {
		'bJQueryUI': true,
		'bAutoWidth': false,
		'sPaginationType': 'full_numbers',
		'bInfo': true,
		'aaSorting': [[1, 'asc']],
		"aoColumnDefs": [
			{ "bSortable": false, "aTargets": [ 0 ] }
		]
	});
}
function tampiltabelpengeluaran(){
	oTablePengeluaran = $('#'+ Drupal.settings.idtabelpengeluaran).dataTable( {
		'bJQueryUI': true,
		'bAutoWidth': false,
		'sPaginationType': 'full_numbers',
		'bInfo': true,
		'aaSorting': [[2, 'asc']],
		"aoColumnDefs": [
			{ "bSortable": false, "aTargets": [ 0,1 ] }
		]
	});
}
function edit_kategori(nid, nTr){
	var aPos = oTableKategori.fnGetPosition( nTr );
	var aData = oTableKategori.fnGetData( aPos );
	$('#edit-kategori').val(aData[1]);
	$('#edit-keterangan').val(aData[2]);
	$('#edit-idkategori').val(nid);
	$('#edit-kategori').select();
}
jQuery(function ($) {
	$('#tabs').tabs();
	tampiltabelkategori();
	tampiltabelpengeluaran();
	$('#edit-submit').on('click', function(e){
		e.preventDefault();
		$('#form-input-kategori').block({ message: '<p style="color: 808080;padding: .2em;font-size: 18px;">Simpan kategori pengeluaran...<img src="/misc/media/images/loading.gif"></p>',css: { border: "3px solid #a00" } });
		var request = new Object();
		request.kategori = $('#edit-kategori').val();
		request.keterangan = $('#edit-keterangan').val();
		alamat = Drupal.settings.basePath + 'keuangan/insertKategoriPengeluaran/1';
		if (parseInt($('#edit-idkategori').val()) > 0){
			request.id = $('#edit-idkategori').val();
			alamat = Drupal.settings.basePath + 'keuangan/updateKategoriPengeluaran/1';
		}
		$.ajax({
			type: 'POST',
			url: alamat,
			data: request,
			cache: false,
			success: function(data){
				$('#edit-kategori').val('');
				$('#edit-keterangan').val('');
				$('#edit-kategori').val('');
				$('#form-input-kategori').unblock();
				$('#edit-kategori').focus();
			}
		});
	});
	$('#edit-batal').on('click', function(e){
		e.preventDefault();
		$('#edit-kategori').val('');
		$('#edit-keterangan').val('');
		$('#edit-idkategori').val('');
		$('#edit-kategori').focus();
	});
});