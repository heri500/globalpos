<?php
// $Id: views-view.tpl.php,v 1.13.2.2 2010/03/25 20:25:28 merlinofchaos Exp $
/**
 * @file views-view.tpl.php
 * Main view template
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 * - $admin_links: A rendered list of administrative links
 * - $admin_links_raw: A list of administrative links suitable for theme('links')
 *
 * @ingroup views_templates
 */
 	add_custom_css();
	$datatables = true;
	$fixedcolum = true;
	_include_new_jquery_plugins();
	_include_jquery_plugins($datatables,$fixedcolum);
	_add_block_ui_plugins();
	$jspath = drupal_get_path('module','indogas_hrd').'/js/tabel.absensi.js';
	drupal_add_js($jspath);
 	if (isset($_GET['bulan']) && isset($_GET['tahun'])){
 		$bulan = $_GET['bulan'];
 		$tahun = $_GET['tahun'];
 	}else{
 		$bulan = date('n');
 		$tahun = date('Y');;
 	}
 	if ($bulan < 10){
 		$bulanjs = '0'.$bulan;	
 	}else{
 		$bulanjs = $bulan;
 	}
 	php_to_drupal_settings(array('bulan' => $bulanjs));
 	php_to_drupal_settings(array('tahun' => $tahun));
 	global $user;
	$superuser = cek_super_user();
	if ($superuser){
		if (isset($_GET['cabang'])){
			$selected_cabang = $_GET['cabang'];
		}else{
			$selected_cabang = 1;
		}
	}else{
		$selected_cabang =	cek_posisi_user();
	}
	$i = 0;
	if ($superuser){
		$pilihan_cabang = create_cabang_selection($selected_cabang);
		$form_object[$i]['label'] = 'Workshop';
		$form_object[$i]['formobject'] = $pilihan_cabang;
		$i++;
	}
	$button = '<button id="filter_form" class="filter-button">Filter Data</button>';
	$form_information['width'] = 600;
	$form_information['marginbottom'] = 15; 
	$form_object[$i]['label'] = 'Bulan/Tahun';
	$pilihanbulan = create_bulan_selection($bulan);
	$pilihantahun = '<input type="text" id="tahun" name="tahun" class="tanggal" value="'.$tahun.'">';
	$form_object[$i]['formobject'] = $pilihanbulan.' '.$pilihantahun.' '.$button;
	$formfilterdata = create_standard_indogas_form($form_object,$form_information);
	$array_status_absen = create_array_status_absen();
	$warnaabsen = create_array_warna_absen();
	php_to_drupal_settings(array('warnaabsen' => $warnaabsen));
	php_to_drupal_settings(array('statusabsen' => $array_status_absen));
	$form_information['form_id'] = 'icon_absen_legend';
	$form_information['width'] = 200;
	$form_information['height'] = 56;
	$form_information['marginbottom'] = 15;
	$form_information['marginleft'] = 15;
	for ($i = 0;$i < count($array_status_absen);$i++){
		$form_object[$i]['labelwidth'] = 165;
		$form_object[$i]['label'] = create_kotak(45,20,$warnaabsen[$i]).'<div style="float:left;margin: 6px 0;">: '.$array_status_absen[$i].'</div>';
		$form_object[$i]['formobject'] = '';
	}
	$form_object[$i]['label'] = create_kotak(45,20,$warnaabsen[$i]).'<div style="float:left;margin: 6px 0;">: Libur</div>';
	$form_object[$i]['formobject'] = '';
	$i++;
	$form_object[$i]['label'] = create_kotak(45,20,$warnaabsen[0],'left','repeating-linear-gradient(45deg, transparent 0px, transparent 0px, rgba(255, 255, 2, 0) 5px, rgba(0, 0, 0, 1) 7px)').'<div style="float:left;margin: 6px 0;">: Tak Lengkap</div>';
	$form_object[$i]['formobject'] = '';
	$i++;
	$form_object[$i]['label'] = create_kotak(45,20,$warnaabsen[0],'left','url('.base_path().'themes/garland/images/user.png)').'<div style="float:left;margin: 6px 0;">: Absen Manual</div>';
	$form_object[$i]['formobject'] = '';
	$i++;
	$form_object[$i]['label'] = create_kotak(45,20,$warnaabsen[0],'left','url('.base_path().'themes/garland/images/fingerprint.png)').'<div style="float:left;margin: 6px 0;">: Absen Finger</div>';
	$form_object[$i]['formobject'] = '';
	$i++;
	$form_object[$i]['label'] = create_kotak(45,20,$warnaabsen[0],'left','url('.base_path().'themes/garland/images/ID_small.png)').'<div style="float:left;margin: 6px 0;">: Absen Kartu</div>';
	$form_object[$i]['formobject'] = '';
	$formlegend = create_standard_indogas_form($form_object,$form_information);
?>
<div class="<?php print $classes; ?>">
  <?php if ($admin_links): ?>
    <div class="views-admin-links views-hide">
      <?php print $admin_links; ?>
    </div>
  <?php endif; ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>

  <?php if ($exposed): ?>
    <div class="view-filters">
      <?php print $exposed; ?>
    </div>
  <?php endif; ?>
	<?php print $formfilterdata.$formlegend; ?>
  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div> <?php /* class view */ ?>
<?php 
	$idselection = 'jam_masuk';
	$idjamkeluar = 'jam_keluar';
	$menitmasuk = 'menit_masuk';
	$menitkeluar = 'menit_keluar';
	$istirahat = 'istirahat';
	$menitistirahat = 'menit_istirahat';
	$idselectstatus = 'status';
	$jammasuk = create_input_jam_menit_selection($idselection,'08','center', true);
  $jamkeluar = create_input_jam_menit_selection($idjamkeluar,'16','center', true);
  $pilihanmenitmasuk = create_input_jam_menit_selection($menitmasuk,'00','center', true);
  $pilihanmenitkeluar = create_input_jam_menit_selection($menitkeluar,'00','center', true);
  $inputistirahat = create_input_jam_menit_selection($istirahat,'01','center', true);
  $inputmenitistirahat = create_input_jam_menit_selection($menitistirahat,'00','center', true);
  $inputtglkeluar = '<input type="text" id="tglkeluar" name="tglkeluar" style="width: 75px;" readonly="readonly">';
  $keterangan_id = 'keterangan';
  $keterangan = '<input type="text" id="'.$keterangan_id.'" name="'.$keterangan_id.'" style="width: 145px;">';
  $statusabsen = create_pilihan_status_absen($idselectstatus,65);
  $nidabsensi = '<input type="text" id="nid_absensi" name="nid_absensi" style="display: none;">';
  $input_nid_karyawan = '<input type="text" id="nid_karyawan_hide" name="nid_karyawan_hide" style="display: none;">';
  $input_tgl_hide = '<input type="text" id="tgl_hide" name="tgl_hide" style="display: none;">';
  $table_attributes = array('id' => $tableID, 'class' => $tableClass);
	$headers = array(
		array('data' => 'NIK', 'style' => 'width: 130px;'),
		array('data' => 'Nama'),
		array('data' => 'Jabatan'),
	);
?>
<div id="dialog-edit-absen" title="UBAH ABSENSI KARYAWAN">
	<div id="form_detail_jurnal" style="width: auto;">
		<div class="form_row"><input type="text" id="nik_karyawan" name="nik_karyawan" style="width: 65px;" disabled="disabled">&nbsp;<input type="text" id="namakaryawan" name="namakaryawan" style="width: 110px;" disabled="disabled">&nbsp;<input type="text" id="tglabsen" name="tglabsen" style="width: 75px;" disabled="disabled">
		<?php print $nidabsensi.$input_nid_karyawan.$input_tgl_hide.$statusabsen.'&nbsp;'.$jammasuk.'&nbsp;'.$pilihanmenitmasuk.'&nbsp;'.$inputtglkeluar.'&nbsp;'.$jamkeluar.'&nbsp;'.$pilihanmenitkeluar.'&nbsp;'.$inputistirahat.'&nbsp;'.$inputmenitistirahat.'&nbsp;'.$keterangan; ?><button id="update_absen" style="font-size:11px;float:left;" title="Klik untuk mengupdate absensi" onclick="update_selected_absensi();">Update</button>
	</div>
</div>