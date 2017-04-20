<?php
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
 _include_new_jquery_plugins();
 _add_easy_ui_plugins(true);
 $array_komponen_gaji = create_array_from_view('array_komponen_gaji');
 $jspath = drupal_get_path('module','indogas_hrd').'/js/tabelpayrollworkshop.js';	
 drupal_add_js($jspath);
 $cabangpilihan = cek_posisi_user();
 if (isset($_GET['bulan'])){
 	$bulan_selection = create_bulan_selection($_GET['bulan']);	
 	$bulan = (int)$_GET['bulan'];
 }else{
 	$bulan_selection = create_bulan_selection();	
 	$bulan = date('n');
 }
 if (isset($_GET['tahun'])){
 	$tahun = create_input_tahun($_GET['tahun']);
 	$tahun_selected = $_GET['tahun'];
 }else{
 	$tahun = create_input_tahun();
 	$tahun_selected = date('Y');
 }
 $div_bulan_selection = '<div id="div-bulan-selection" class="views-exposed-widgets"><label style="width: 80px;margin-right: 10px;">Periode</label>'.$bulan_selection.' '.$tahun.'</div>';
 if ($bulan < 10){
 	$bulancek = '0'.$bulan; 	
 }else{
 	$bulancek = $bulan;
 }
 $args = array($tahun_selected.'-'.$bulancek);
 $bulansebelum = (int)$bulancek - 1;
 if ($bulansebelum == 0){
 	$bulansebelum = 12;
 	$tahunsebelum = $tahun_selected - 1;
 }else{
 	$tahunsebelum = $tahun_selected;
 }
 if ($bulansebelum < 10){
 	$bulansebelum = '0'.$bulansebelum;	
 }
 $argsebelum = array($tahunsebelum.'-'.$bulansebelum);
 $array_hari_libur = create_array_from_view('array_tanggal_libur_by_bulan',$args);
 $array_hari_libur_sebelum = create_array_from_view('array_tanggal_libur_by_bulan',$argsebelum);
 $new_array_hari_libur = array();
 for ($i = 0;$i < count($array_hari_libur_sebelum);$i++){
 	$new_array_hari_libur[] = $array_hari_libur_sebelum[$i];
 }
 for ($i = 0;$i < count($array_hari_libur);$i++){
 	$new_array_hari_libur[] = $array_hari_libur[$i];
 }
 $array_hari_libur = $new_array_hari_libur;
 $variablename = 'batasilembur';
 $pilihanbatasilembur = get_indogas_variable_value($variablename);
 if ($pilihanbatasilembur != ''){
 		$pilihanbatasilembur = 0;
 		$batasanjamlembur = 0;
 }else{
 		if ($pilihanbatasilembur == 1){
 			$batasanjamlembur = get_indogas_variable_value($variablename);
 		}else{
 			$batasanjamlembur = 0;
 		}	
 }
 $args = array($cabangpilihan, $tahun_selected);
 $nilaiumr = create_array_from_view('get_umr_workshop', $args);
 if (count($nilaiumr)){
		$umrworkshop = $nilaiumr[0]->node_data_field_workshop_umr_ref_field_nilai_umr_value;
 }else{
		$args = array($cabangpilihan);
		$nilaiumr = create_array_from_view('get_umr_workshop', $args);
		$umrworkshop = $nilaiumr[0]->node_data_field_workshop_umr_ref_field_nilai_umr_value;
 }
?>
<?php print $div_bulan_selection; ?>
<table id="tabel-payroll" title="Tabel Payroll" class="easyui-datagrid" data-options="singleSelect:true" style="width: 950px;height:auto;">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead data-options="frozen:true">
  	<tr>
  	<?php
  	$lebarkolom = array(80,220,120,90);
  	$lebarkolomabsen = 40;
  	$i = 0;
  	?>	
    <?php foreach ($header as $field => $label): ?>
      <th data-options="field:'<?php print $fields[$field]; ?>',halign:'center',width:<?php print $lebarkolom[$i]; ?>" class="views-field views-field-<?php print $fields[$field]; ?>">
    		<?php print $label; ?>
      </th>
    <?php $i++; ?>  
    <?php endforeach; ?>
  </tr>
  </thead>
  <thead>
  	<tr>
  		<th data-options="field:'jumlah-masuk',halign:'center',align:'center',width: <?php print $lebarkolomabsen; ?>">H</th>
  		<th data-options="field:'jumlah-izin',halign:'center',align:'center',width: <?php print $lebarkolomabsen; ?>">I/S</th>
  		<th data-options="field:'jumlah-alfa',halign:'center',align:'center',width: <?php print $lebarkolomabsen; ?>">A</th>
  		<th data-options="field:'jumlah-lembur',halign:'center',align:'center',width: <?php print $lebarkolomabsen; ?>">JL</th>
      <?php
      $lebarkolomgaji = 120;
      foreach ($array_komponen_gaji as $count => $value){
      	$pecahjudulkolom = explode(' ',$value->node_title);
      	if (count($pecahjudulkolom) > 1){
      		$judulkolom = substr($pecahjudulkolom[0],0,1).'. '.$pecahjudulkolom[1].' '.$pecahjudulkolom[2];	
      	}else{
      		$judulkolom = $value->node_title;
      	}
      	if ($value->node_data_field_perhitungan_field_sebagai_gaji_pokok_value == 1){
      		$nid_gaji_pokok = $value->nid;
      	}
      ?>
      <th data-options="field:'kg-<?php print $value->nid; ?>',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>"><?php print $judulkolom; ?></th>
      <?php	
      }
      ?>
      <th data-options="field:'tot-lembur',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Tot. Lembur</th>
      <th data-options="field:'tot-insentif',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Insentif</th>
      <th data-options="field:'jamsostek',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Jamsostek</th>	
      <th data-options="field:'pot-alpa',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Pot. Alpa</th>	
      <th data-options="field:'pot-lain',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Pot. Lainnya</th>	
      <th data-options="field:'tot-terima',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Tot. Penerimaan</th>	
    </tr>
  </thead>
  <tbody>
  	<?php foreach ($rows as $count => $row): ?>
    <?php
    	$nid_karyawan = $view->result[$count]->nid;
    	$array_komponen_gaji_karyawan = create_array_komponen_gaji_karyawan($nid_karyawan);
    	$array_hadir_karyawan = array_absensi_karyawan($nid_karyawan, $bulan, $tahun_selected, 0);
    	$hitunganlembur = $view->result[$count]->node_node_data_field_jabatan_ref_node_data_field_hitungan_lembur_field_hitungan_lembur_value;
    	$gajipokok = $array_komponen_gaji_karyawan[$nid_gaji_pokok]->nilai;
    	$total_insentif_karyawan[$nid_karyawan] = total_insentif_karyawan($nid_karyawan, $bulan, $tahun_selected);
    	$total_potongan_karyawan[$nid_karyawan] = total_potongan_karyawan($nid_karyawan, $bulan, $tahun_selected);
    	if ($hitunganlembur == 1){
    		$array_lemburan_karyawan[$nid_karyawan] = create_array_lemburan($array_hadir_karyawan,$node_cabang->field_jam_kerja[0]['value'],$array_hari_libur);
    		$status_ikatan = $view->result[$count]->node_node_data_field_status_ikatan_ref_title;
    		if ($status_ikatan == 'Kontrak' || $status_ikatan == 'Tetap'){
    			$array_lemburan_karyawan[$nid_karyawan] = hitung_lembur($status_ikatan, $array_lemburan_karyawan[$nid_karyawan], $gajipokok, $batasanjamlembur);
    		}else{
					$array_lemburan_karyawan[$nid_karyawan] = hitung_lembur($status_ikatan, $array_lemburan_karyawan[$nid_karyawan], $umrworkshop, $batasanjamlembur);
    		}
    	}else{
    		$array_lemburan_karyawan[$nid_karyawan]['total'] = 0;
    		$array_lemburan_karyawan[$nid_karyawan]['totaljamkonversi']	= 0;
    	}
    	//$total_insentif_karyawan[$nid_karyawan] = 
    	$array_alfa_karyawan = array_absensi_karyawan($nid_karyawan, $bulan, $tahun_selected, 1);
    	$array_izin_karyawan = array_absensi_karyawan($nid_karyawan, $bulan, $tahun_selected, 2);
    	$array_sakit_karyawan = array_absensi_karyawan($nid_karyawan, $bulan, $tahun_selected, 3);
    	$potonganalpa = ($gajipokok/20)*count($array_alfa_karyawan);
    	$totalgaji = 0;
    	if (count($array_hadir_karyawan) > 0){
    		if ($superuser || $view->result[$count]->node_data_field_nik_field_departemen_nid != 97180){
    ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
        <td><?php print '<div style="text-align: center;">'.count($array_hadir_karyawan).'</div>'; ?></td>
        <td><?php print '<div style="text-align: center;">'.(count($array_izin_karyawan).'/'.count($array_sakit_karyawan)).'</div>'; ?></td>
        <td><?php print '<div style="text-align: center;">'.count($array_alfa_karyawan).'</div>'; ?></td>
        <td><?php print '<div style="text-align: center;">'.$array_lemburan_karyawan[$nid_karyawan]['totaljamkonversi'].'</div>'; ?></td>
        <?php
	      foreach ($array_komponen_gaji as $count => $value){
	      	if ($array_komponen_gaji_karyawan[$value->nid]->nilai > 0){	
	      		if ($value->node_data_field_perhitungan_field_perhitungan_value == 0){
	      			$nilaigaji = $array_komponen_gaji_karyawan[$value->nid]->nilai;
	      			$totalgaji = $totalgaji + $nilaigaji;
	      		}else{
	      			$nilaigaji = $array_komponen_gaji_karyawan[$value->nid]->nilai * (count($array_hadir_karyawan) + count($array_izin_karyawan));
	      			$totalgaji = $totalgaji + $nilaigaji;
	      		}
	      		$gajitampil = number_format($nilaigaji,0,',','.');
	      	}else{
	      		$gajitampil = '-';
	      	}
	      ?>
	      <td><?php print '<div style="text-align: right;">'.$gajitampil.'</div>'; ?></td>
	      <?php	
	      }
	      $totalgaji = $totalgaji + $array_lemburan_karyawan[$nid_karyawan]['total'] + $total_insentif_karyawan[$nid_karyawan];
	      $jamsostek = $gajipokok * (2/100);
	      $totalgaji = $totalgaji - ($jamsostek + $potonganalpa + $total_potongan_karyawan[$nid_karyawan]);
	      if ($totalgaji > 0){
	      ?>
	      <td><?php print '<div style="text-align: right;">'.number_format($array_lemburan_karyawan[$nid_karyawan]['total'],0,',','.').'</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">'.number_format($total_insentif_karyawan[$nid_karyawan],0,',','.').'</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">'.number_format($jamsostek,0,',','.').'</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">'.number_format($potonganalpa,0,',','.').'</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">'.number_format($total_potongan_karyawan[$nid_karyawan],0,',','.').'</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">'.number_format($totalgaji,0,',','.').'</div>'; ?></td>
	      <?php
	    	}else{
	      ?>
	      <td><?php print '<div style="text-align: right;">-</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">-</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">'.number_format($potonganalpa,0,',','.').'</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">-</div>'; ?></td>
	      <?php
	    	}
	      ?>
      </tr>
      <?php
    		}
    	}
      ?>
    <?php endforeach; ?>
  </tbody>
</table>