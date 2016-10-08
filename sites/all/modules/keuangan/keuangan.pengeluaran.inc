<?php

function tabel_pengeluaran_view(){
	_addCustomCSS();
	drupal_add_css('misc/media/datatables/css/demo_table_jui.css');
	drupal_add_css('misc/media/datatables/themes/smoothness/jquery-ui-1.8.4.custom.css');
	_addJqueryPlugins();
	_addBlockUiPlugins();
	drupal_add_js('misc/media/jquery-ui-1.8.custom.min.js');
	drupal_add_js('misc/media/datatables/js/jquery.dataTables.min.js');
	$js_path = drupal_get_path('module','keuangan').'/js/tabel.pengeluaran.js';
	drupal_add_js($js_path);
	$idtabelkategori = 'tabel-kategori-pengeluaran';
	$idtabelpengeluaran = 'tabel-pengeluaran';
	drupal_add_js(
		array(
			'idtabelkategori' => $idtabelkategori,
			'idtabelpengeluaran' => $idtabelpengeluaran,
		), 
	'setting');
	$tabelkategoripengeluaran = '<div class="small-table-wrapper">'.tabel_kategori_pengeluaran($idtabelkategori).'</div>';
	$tabelpengeluaran = '<div class="small-table-wrapper">'.tabel_pengeluaran($idtabelpengeluaran).'</div>';
	$variables = array();
	$header = new stdClass();
	$header->title = 'Pengeluaran';
	$header->tabid = 'tabpengeluaran';
	$variables['element']['header'][0] = $header;
	$header = new stdClass();
	$header->title = 'Kategori';
	$header->tabid = 'tabkategori';
	$variables['element']['header'][1] = $header;
	$content = new stdClass();
	$content->tabid = 'tabpengeluaran';
	$content->tabcontent = $tabelpengeluaran;
	$variables['element']['div'][0] = $content;
	$content = new stdClass();
	$content->tabid = 'tabkategori';
	$formkategori = '<div id="form-input-kategori" class="small-form-wrapper">'.drupal_get_form('keuangan_input_kategori_form').'</div>';
	$content->tabcontent = $tabelkategoripengeluaran.$formkategori;
	$variables['element']['div'][1] = $content;
	return theme('jquery_ui_tab',$variables);
}
function tabel_kategori_pengeluaran($idtabelkategori){
	$header_array = array(
		'edit' => array('data' => '&nbsp;', 'class' => 'tablebutton'),
    'kategori' => array('data' => 'Kategori', 'class' => 'center'),
    'katerangan' => array('data' => 'Keterangan', 'class' => 'center'),
	);
	$arraykategori = getKategoriPengeluaran();
	$rows_array = array();
	for ($i = 0;$i < count($arraykategori);$i++){	
		$editbutton = '<img title="Klik untuk mengubah kategori pengeluaran" onclick="edit_kategori('.$arraykategori[$i]->id.', this.parentNode.parentNode);" src="'.base_path().'misc/media/images/edit.ico" width="22">';
		$cell_array = array();
		$cell_array['edit'] = array('data' => $editbutton, 'class' => 'center');
		$cell_array['kategori'] = array('data' => ucfirst($arraykategori[$i]->kategori), 'class' => 'align-left');
		$cell_array['katerangan'] = array('data' => $arraykategori[$i]->keterangan, 'class' => 'align-left');
		$rows_array[] = $cell_array;
	}
	if (!count($rows_array)){
		$cell_array = array();
		$cell_array['edit'] = array('data' => '-', 'class' => 'center');
		$cell_array['kategori'] = array('data' => '-', 'class' => 'align-left');
		$cell_array['katerangan'] = array('data' => '-', 'class' => 'align-left');
		$rows_array[0] = $cell_array;
	}
	return theme('table', $header_array, $rows_array, array('id' => $idtabelkategori,'class' => 'display',));
}
function tabel_pengeluaran($idtabelpengeluaran, $periode = null){
	$array_pengeluaran = getPengeluaranByPeriodeKategori(null,$periode);
	$array_kategori_pengeluaran = getKategoriPengeluaran();
	$arrayhari = arrayHari();
	$header_array = array(
		'edit' => array('data' => '&nbsp;', 'class' => 'tablebutton'),
		'hapus' => array('data' => '&nbsp;', 'class' => 'tablebutton'),
		'hari' => array('data' => 'Hari', 'class' => 'center'),
		'tanggal' => array('data' => 'Tanggal', 'class' => 'center'),
    'kategori' => array('data' => 'Kategori', 'class' => 'center'),
    'keterangan' => array('data' => 'Keterangan', 'class' => 'center'),
    'nilai' => array('data' => 'Nilai', 'class' => 'center'),
	);
	$rows_array = array();
	for ($i = 0;$i < count($array_pengeluaran);$i++){	
		$editbutton = '<img title="Klik untuk mengubah pengeluaran" onclick="edit_pengeluaran('.$array_pengeluaran[$i]->id.', this.parentNode.parentNode);" src="'.base_path().'misc/media/images/edit.ico" width="22">';
		$deletebutton = '<img title="Klik untuk menghapus pengeluaran" onclick="hapus_pengeluaran('.$array_pengeluaran[$i]->id.');" src="'.base_path().'misc/media/images/del.ico" width="22">';
		$cell_array = array();
		$cell_array['edit'] = array('data' => $editbutton, 'class' => 'center');
		$cell_array['hapus'] = array('data' => $deletebutton, 'class' => 'center');
		$index_hari = date('w', $array_pengeluaran[$i]->tglpengeluaran);
		$tglpengeluaran = date('d-m-Y', $array_pengeluaran[$i]->tglpengeluaran);
		$cell_array['hari'] = array('data' => $arrayhari[$index_hari], 'class' => 'center');
		$cell_array['tanggal'] = array('data' => $tglpengeluaran, 'class' => 'center');
		$cell_array['kategori'] = array('data' => $array_pengeluaran[$i]->kategori, 'class' => 'align-left');
		$cell_array['katerangan'] = array('data' => $array_kategori_pengeluaran[$array_pengeluaran[$i]->keterangan], 'class' => 'center');
		$cell_array['nilai'] = array('data' => number_format($array_pengeluaran[$i]->nilai,0,',','.'), 'class' => 'angka');
		$rows_array[] = $cell_array;
	}
	if (!count($rows_array)){
		$cell_array = array();
		$cell_array['edit'] = array('data' => '-', 'class' => 'center');
		$cell_array['hapus'] = array('data' => '-', 'class' => 'center');
		$cell_array['hari'] = array('data' => '-', 'class' => 'center');
		$cell_array['tanggal'] = array('data' => '-', 'class' => 'center');
		$cell_array['kategori'] = array('data' => '-', 'class' => 'center');
		$cell_array['katerangan'] = array('data' => '-', 'class' => 'align-left');
		$cell_array['nilai'] = array('data' => '-', 'class' => 'angka');
		$rows_array[0] = $cell_array;
	}
	return theme('table', $header_array, $rows_array, array('id' => $idtabelpengeluaran,'class' => 'display',));
}