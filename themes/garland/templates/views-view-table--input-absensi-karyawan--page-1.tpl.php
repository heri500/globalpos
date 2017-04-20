<?php
// $Id: views-view-table.tpl.php,v 1.8 2009/01/28 00:43:43 merlinofchaos Exp $
/**
 * @file views-view-table.tpl.php
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $class: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * @ingroup views_templates
 */
 //dpm($view->result);
 $superuser = cek_super_user();
 if ($superuser){
 	if (isset($_GET['cabang']) && (int)$_GET['cabang'] > 0){
 		$selected_cabang = $_GET['cabang'];	
 	}else{
 		$selected_cabang = 1;
 	}
 }else{
 	$selected_cabang = cek_posisi_user();	
 }
 $args = array($selected_cabang);
 $tanggal = date('Y-m-d');
 $nilaifilter = array('value' => $tanggal);
 $filterdata[0] = array('filtername' =>'field_tanggal_value', 'filtervalue' => $tanggal);
 $array_karyawan_proses_nonaktif = create_array_from_view('array_karyawan_proses_nonaktif',$args,$filterdata);
 $new_array_karyawan_proses_nonaktif = array();
 for ($i = 0;$i < count($array_karyawan_proses_nonaktif);$i++){
 		$new_array_karyawan_proses_nonaktif[] = $array_karyawan_proses_nonaktif[$i]->node_data_field_karyawan_ref_field_karyawan_ref_nid;
 }
 $array_karyawan_proses_nonaktif = $new_array_karyawan_proses_nonaktif;
 $jamstandard_rs = db_query("SELECT `jammasuk`, `jamkeluar`, `istirahat`, `menit_istirahat` FROM `indogas_jam_masuk_keluar_standard` 
 WHERE `nid_workshop`='%d'",$selected_cabang);
 $jamstandard_data = db_fetch_object($jamstandard_rs);
 if (!is_null($jamstandard_data->jammasuk)){
 	$standard_jam_masuk = $jamstandard_data->jammasuk;
 	$pecah_standar_masuk = explode(":",$standard_jam_masuk);
 	$jammasukstd = $pecah_standar_masuk[0];
 	$menitmasukstd = $pecah_standar_masuk[1];
 }else{
 	$standard_jam_masuk = '08:00';
 	$jammasukstd = '08';
 	$menitmasukstd = '00';
 }
 if (!is_null($jamstandard_data->jamkeluar)){
 	$standard_jam_keluar = $jamstandard_data->jamkeluar;
 	$pecah_standar_keluar = explode(":",$standard_jam_keluar);
 	$jamkeluarstd = $pecah_standar_keluar[0];
 	$menitkeluarstd = $pecah_standar_keluar[1];
 }else{
 	$standard_jam_keluar = '17:00';
 	$jamkeluarstd = '17';
 	$menitkeluarstd = '00';
 }
 if (!is_null($jamstandard_data->istirahat)){
 	$standard_istirahat = $jamstandard_data->istirahat;
 	$standard_menit_istirahat = $jamstandard_data->menit_istirahat;
 }else{
 	$standard_istirahat = '1';
 	$standard_menit_istirahat = '00';
 }
?>
<form id="form-absensi-karyawan" action="<?php print base_path().'hrd/saveabsensi'; ?>" method="POST">
<?php
	$pilihan_tanggal = '<input type="text" id="tanggal" name="tanggal" readonly="readonly" class="tanggal-pendek">';
	$submitbutton = '<button id="submit_absen" class="submit-button">Simpan Absensi</button>';
	$form_absen = '<div id="form-tglabsen-warpper" style="float:left;width: 100%;margin-top: 10px;"><div class="form-jurnal-umum" style="width:360px;box-shadow: 2px 2px 4px #BBB;margin-bottom:10px;">';
	$form_absen .= '<div class="form_row"><label>Tanggal Absen<span class="require_field">*</span></label>'.$pilihan_tanggal.'</div></div></div>';
	print $form_absen;
?>
<table id="tabelabsensi" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
        <?php 
        	if ($field != 'nid'){
        ?>
        <th rowspan="2" style="text-align: center;" class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
      	<th rowspan="2" style="width: 70px;text-align: center;">Status</th>
      	<th colspan="2" style="width: 100px;text-align: center;border-right:1px solid #C5DBEC;">Masuk</th>
      	<th colspan="3" style="width: 180px;text-align: center;">Keluar</th>
      	<th colspan="2" style="width: 100px;text-align: center;border-left: 1px solid #C5DBEC;">Break</th>
      	<th rowspan="2" style="width: 125px;text-align: center;">Keterangan</th>
    </tr>
    <tr>
    	<th style="width: 50px;text-align: center;">Jam</th>
      <th style="width: 50px;text-align: center;">Mnt</th>
      <th style="width: 90px;text-align: center;">Tgl</th>
      <th style="width: 50px;text-align: center;">Jam</th>
      <th style="width: 50px;text-align: center;">Mnt</th>
      <th style="width: 50px;text-align: center;">Jam</th>
      <th style="width: 50px;text-align: center;">Mnt</th>
    </tr>
  </thead>
  <tbody>
  	<?php
  		$array_id_jam_menit = array();
  		$i = 0;
  	?>
  	<?php foreach ($rows as $count => $row): ?>
    	<?php
    		$nid_karyawan = $view->result[$count]->nid;
    		if (!in_array($nid_karyawan,$array_karyawan_proses_nonaktif)){
    		$idselection = 'jam_masuk_'.$nid_karyawan;
    		$idselectstatus = 'status_'.$nid_karyawan;
    		$idtglkeluar = 'tgl_keluar_'.$nid_karyawan;
    		$idjamkeluar = 'jam_keluar_'.$nid_karyawan;
    		$menitmasuk = 'menit_masuk_'.$nid_karyawan;
    		$menitkeluar = 'menit_keluar_'.$nid_karyawan;
    		$istirahat = 'istirahat_'.$nid_karyawan;
    		$menitistirahat = 'menit_istirahat_'.$nid_karyawan;
    		$keterangan_id = 'ket_absen_'.$nid_karyawan;
    		$array_id_jam_menit[$i]['status'] = $idselectstatus;
    		$array_id_jam_menit[$i]['jammasuk'] = $idselection;
    		$array_id_jam_menit[$i]['tglkeluar'] = $idtglkeluar;
    		$array_id_jam_menit[$i]['jamkeluar'] = $idjamkeluar;
    		$array_id_jam_menit[$i]['menitmasuk'] = $menitmasuk;
    		$array_id_jam_menit[$i]['menitkeluar'] = $menitkeluar;
    		$array_id_jam_menit[$i]['istirahat'] = $istirahat;
    		$array_id_jam_menit[$i]['menitistirahat'] = $menitistirahat;
    		$array_id_jam_menit[$i]['keterangan'] = $keterangan_id;
    		$i++;
    		$jammasuk = create_input_jam_menit_selection($idselection,$jammasukstd);
    		$jamkeluar = create_input_jam_menit_selection($idjamkeluar,$jamkeluarstd);
    		$tglkeluar = '<input type="text" id="'.$idtglkeluar.'" name="'.$idtglkeluar.'" style="width: 80px;" class="tglkeluar">';
    		$pilihanmenitmasuk = create_input_jam_menit_selection($menitmasuk,$menitmasukstd,'left');
    		$pilihanmenitkeluar = create_input_jam_menit_selection($menitkeluar,$menitkeluarstd,'left');
    		$inputistirahat = create_input_jam_menit_selection($istirahat,$standard_istirahat);
    		$inputmenitistirahat = create_input_jam_menit_selection($menitistirahat,$standard_menit_istirahat,'left');
    		$keterangan = '<input type="text" id="'.$keterangan_id.'" name="'.$keterangan_id.'" class="input-keterangan" style="width: 120px;">';
    		$statusabsen = create_pilihan_status_absen($idselectstatus);
    		$proses_skorsing_array = cek_proses_skorsing_exist($nid_karyawan);
      	if (!count($proses_skorsing_array) > 0){
    	?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <?php 
	        	if ($field != 'nid'){
	        ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
          	<?php
          		if (trim($fields[$field]) == 'title'){
          			$pecahnama = explode(" ",$content);
          			$content = $pecahnama[0].' '.$pecahnama[1].' '.substr($pecahnama[2],0,1);
          			if(count($pecahnama) > 2){
          				$content .= '.';	
          			}
          		}
          	?>
            <?php print $content; ?>
          </td>
          <?php } ?>
        <?php endforeach; ?>
        	<td class="center"><?php print $statusabsen; ?></td>
	        <td class="center"><?php print $jammasuk; ?></td>
	      	<td class="center"><?php print $pilihanmenitmasuk; ?></td>
	      	<td class="center"><?php print $tglkeluar; ?></td>
	      	<td class="center"><?php print $jamkeluar; ?></td>
	      	<td class="center"><?php print $pilihanmenitkeluar; ?></td>
	      	<td class="center"><?php print $inputistirahat; ?></td>
	      	<td class="center"><?php print $inputmenitistirahat; ?></td>
	      	<td><?php print $keterangan; ?></td>
      </tr>
      <?php
    	}
      }
      ?>
    <?php endforeach; ?>
    <?php
    	php_to_drupal_settings(array('id_input' => $array_id_jam_menit));
    ?>
  </tbody>
</table>
<?php
	$submitbutton = '<button id="submit_absen" class="submit-button" style="font-size: 13px;">Simpan Absensi</button>';
	$form_absen = '<div class="form_row">'.$submitbutton.'</div>';
	$arraymessage['title'] = t('Sebelum menyimpan absensi pastikan :');
	$arraymessage['content'][0] = t('Tanggal yang dipilih sudah sesuai dengan tanggal absensi terkait.');
	$arraymessage['content'][1] = t('Data absensi per-karyawan sudah benar/valid.');
	$pesanperingatan = create_warning_message($arraymessage);
	print $form_absen.$pesanperingatan;
?>
</form>