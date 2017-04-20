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
 _include_new_jquery_plugins();
 _add_easy_ui_plugins(true);
 _add_block_ui_plugins();
 $array_komponen_gaji = create_array_from_view('array_komponen_gaji');
 $jspath = drupal_get_path('module','indogas_hrd').'/js/tabelpayroll.js';	
 drupal_add_js($jspath);
 $superuser = cek_super_user();
 if ($superuser){
 	if (isset($_GET['field_cabang_ref_nid'])){
 		$cabangpilihan = $_GET['field_cabang_ref_nid'];
 	}else{
 		$cabangpilihan = 1;
 	}
 }else{
 	$cabangpilihan = cek_posisi_user();
 }
 $node_cabang = node_load($cabangpilihan);
 if (isset($_GET['bulan'])){
 	$bulan_selection = create_bulan_selection($_GET['bulan']);	
 	$bulan = (int)$_GET['bulan'];
 }else{
 	$bulan = date('n');
 	$bulan_selection = create_bulan_selection($bulan);	
 }
 if (isset($_GET['tahun'])){
 	$tahun = create_input_tahun($_GET['tahun']);
 	$tahun_selected = $_GET['tahun'];
 }else{
 	$tahun_selected = date('Y');
 	$tahun = create_input_tahun($tahun_selected);
 }
 $bulanSebelum = $bulan - 1;
 $tahunSebelum = $tahun_selected;
 if ($bulanSebelum <= 0){
 	$bulanSebelum = 12;	
 	$tahunSebelum = $tahun_selected - 1;
 }
 $tglAwalInt = mktime(0,0,0,$bulanSebelum, 21,$tahunSebelum);
 $intTanggalAkhir = mktime(0,0,0,$bulan, 20,$tahun_selected);
 $div_bulan_selection = '<div id="div-bulan-selection" class="views-exposed-widgets"><label style="width: 80px;margin-right: 10px;">Periode</label>'.$bulan_selection.' '.$tahun.'</div>';
 $array_gaji = create_array_gaji($cabangpilihan,$bulan,$tahun_selected);
 //dpm($view->result);
?>
<?php print $div_bulan_selection; ?>
<table id="tabel-payroll" title="Tabel Payroll" class="easyui-datagrid <?php print $class; ?>"<?php print $attributes; ?> data-options="singleSelect:true" style="width: 950px;height:320px;">
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
    <th data-options="field:'tanggal-masuk',halign:'center',width:<?php print $lebarkolom[$i]; ?>">Tgl. Masuk</th>
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
      $totalkomponengaji = array();
      $index_kg = 0;
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
      	$totalkomponengaji[$index_kg] = 0;
      ?>
      <th data-options="field:'kg-<?php print $value->nid; ?>',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>"><?php print $judulkolom; ?></th>
      <?php	
      $index_kg++;
      }
      ?>
      <th data-options="field:'tot-lembur',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Tot. Lembur</th>
      <th data-options="field:'tot-insentif',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Insentif</th>
      <th data-options="field:'jamsostek',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Jamsostek</th>	
      <th data-options="field:'pot-alpa',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Pot. Alpa</th>	
      <th data-options="field:'pot-lain',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Pot. Lainnya</th>	
      <th data-options="field:'tot-terima',halign:'center',align:'right',width: <?php print $lebarkolomgaji; ?>">Tot. Penerimaan</th>	
      <?php
      for ($i = 0;$i < 6;$i++){
      	$totalkomponengaji[$index_kg] = 0;
      	$index_kg++;
      }
      //dpm($totalkomponengaji);
      ?>
    </tr>
  </thead>
  <tbody>
  	<?php 
  		$totalhadir = 0;
  		$totalizin = 0;
  		$totalsakit = 0;
  		$totalalpa = 0;
  		$totaljamkonversi = 0;
  	?>	
  	<?php foreach ($rows as $count => $row): ?>
    <?php
    	$totalgaji = 0;
    	$nid_karyawan = $view->result[$count]->nid;
    	$tanggalMasuk = $view->result[$count]->node_data_field_nik_field_tanggal_masuk_value;
    	$karyawanDate = getKaryawanStartDateResignDate($nid_karyawan);
    	$splitTglMasuk = explode('T',$tanggalMasuk);
    	$tanggalMasuk = explode('-',$splitTglMasuk[0]);
    	$intTanggalMasuk = mktime(0,0,0,$tanggalMasuk[1],$tanggalMasuk[2],$tanggalMasuk[0]);
    	$statusAktifKaryawan = $view->result[$count]->node_status;
    	if ($superuser && $intTanggalAkhir > $intTanggalMasuk && ((empty($karyawanDate['end_int']) && $statusAktifKaryawan) || (!empty($karyawanDate['end_int']) && $tglAwalInt < $karyawanDate['end_int']))){
    ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
        <td><?php print '<div style="text-align: center;">'.date('d-m-Y', strtotime($view->result[$count]->node_data_field_nik_field_tanggal_masuk_value)).'</div>'; ?></td>
        <td><?php print '<div style="text-align: center;">'.$array_gaji[$nid_karyawan]['kehadiran']->hadir.'</div>'; ?></td>
        <td><?php print '<div style="text-align: center;">'.$array_gaji[$nid_karyawan]['kehadiran']->izin.'/'.$array_gaji[$nid_karyawan]['kehadiran']->sakit.'</div>'; ?></td>
        <td><?php print '<div style="text-align: center;">'.$array_gaji[$nid_karyawan]['kehadiran']->alpa.'</div>'; ?></td>
        <td><?php print '<div style="text-align: center;">'.$array_gaji[$nid_karyawan]['lemburkonversi'].'</div>'; ?></td>
        <?php
        $totalhadir = $totalhadir + (int)$array_gaji[$nid_karyawan]['kehadiran']->hadir;
	  		$totalizin = $totalizin + (int)$array_gaji[$nid_karyawan]['kehadiran']->izin;
	  		$totalsakit = $totalsakit + (int)$array_gaji[$nid_karyawan]['kehadiran']->sakit;
	  		$totalalpa = $totalalpa + (int)$array_gaji[$nid_karyawan]['kehadiran']->alpa;
	  		$totaljamkonversi = $totaljamkonversi + ($array_gaji[$nid_karyawan]['lemburkonversi'] * 1);
        $index_kg = 0;
	      foreach ($array_komponen_gaji as $count => $value){
	      	if ($array_gaji[$nid_karyawan]['penerimaan'][$value->node_title] > 0){	
	      		$nilaigaji = $array_gaji[$nid_karyawan]['penerimaan'][$value->node_title];
	      		$totalgaji = $totalgaji + $nilaigaji;
	      		$gajitampil = number_format($nilaigaji,0,',','.');
	      	}else{
	      		$gajitampil = '-';
	      		$nilaigaji = 0;
	      	}
	      ?>
	      <td><?php print '<div style="text-align: right;">'.$gajitampil.'</div>'; ?></td>
	      <?php	
	      	$totalkomponengaji[$index_kg] = $totalkomponengaji[$index_kg] + $nilaigaji;
	      	$index_kg++;
	      }
	      if ($array_gaji[$nid_karyawan]['Take Home Pay'] > 0){
	      	if (!$array_gaji[$nid_karyawan]['penerimaan']['lembur'] > 0){
	      		$array_gaji[$nid_karyawan]['penerimaan']['lembur'] = 0;
	      	}
	      	if (!$array_gaji[$nid_karyawan]['penerimaan']['Insentif'] > 0){
	      		$array_gaji[$nid_karyawan]['penerimaan']['Insentif'] = 0;	
	      	}
	      	if (!$array_gaji[$nid_karyawan]['potongan']['Jamsostek'] > 0){
	      		$array_gaji[$nid_karyawan]['potongan']['Jamsostek'] = 0;	
	      	}
	      	if (!$array_gaji[$nid_karyawan]['potongan']['Potongan Alpa'] > 0){
	      		$array_gaji[$nid_karyawan]['potongan']['Potongan Alpa'] = 0;	
	      	}
	      	if (!$array_gaji[$nid_karyawan]['potongan']['Potongan Lain-lain'] > 0){
	      		$array_gaji[$nid_karyawan]['potongan']['Potongan Lain-lain'] = 0;	
	      	}
	      	if (!$array_gaji[$nid_karyawan]['Take Home Pay'] > 0){
	      		$array_gaji[$nid_karyawan]['Take Home Pay'] = 0;	
	      	}
	      ?>
	      <td><?php print '<div style="text-align: right;">'.number_format(round($array_gaji[$nid_karyawan]['penerimaan']['Lembur'],0),0,',','.').'</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">'.number_format(round($array_gaji[$nid_karyawan]['penerimaan']['Insentif'],0),0,',','.').'</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">'.number_format(round($array_gaji[$nid_karyawan]['potongan']['Jamsostek'],0),0,',','.').'</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">'.number_format(round($array_gaji[$nid_karyawan]['potongan']['Potongan Alpa'],0),0,',','.').'</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">'.number_format(round($array_gaji[$nid_karyawan]['potongan']['Potongan Lain-lain'],0),0,',','.').'</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">'.number_format(round($array_gaji[$nid_karyawan]['Take Home Pay'],0),0,',','.').'</div>'; ?></td>
	      <?php
	      $totalkomponengaji[$index_kg] = $totalkomponengaji[$index_kg] + round($array_gaji[$nid_karyawan]['penerimaan']['Lembur'],0);
	      $index_kg++;
	      $totalkomponengaji[$index_kg] = $totalkomponengaji[$index_kg] + round($array_gaji[$nid_karyawan]['penerimaan']['Insentif'],0);
	      $index_kg++;
	      $totalkomponengaji[$index_kg] = $totalkomponengaji[$index_kg] + round($array_gaji[$nid_karyawan]['potongan']['Jamsostek'],0);
	      $index_kg++;
	      $totalkomponengaji[$index_kg] = $totalkomponengaji[$index_kg] + round($array_gaji[$nid_karyawan]['potongan']['Potongan Alpa'],0);
	      $index_kg++;
	      $totalkomponengaji[$index_kg] = $totalkomponengaji[$index_kg] + round($array_gaji[$nid_karyawan]['potongan']['Potongan Lain-lain'],0);
	      $index_kg++;
	      $totalkomponengaji[$index_kg] = $totalkomponengaji[$index_kg] + round($array_gaji[$nid_karyawan]['Take Home Pay'],0);
	    	}else{
	      ?>
	      <td><?php print '<div style="text-align: right;">-</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">-</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">-</div>'; ?></td>
	      <td><?php print '<div style="text-align: right;">-</div>'; ?></td>
	      <?php
	    	}
	      ?>
      </tr>
      <?php
    		}
      ?>
    <?php endforeach; ?>
    <?php
    $totalcolumn = count($row) + 1;
    print '<tr>';
    for ($j = 0;$j < $totalcolumn;$j++){
    	if ($j == 3){
    		print '<td>Total</td>';
    	}else{
    		print '<td>&nbsp;</td>';	
    	}
    }
    print '<td><div style="text-align: center;">'.$totalhadir.'</div></td>';
    print '<td><div style="text-align: center;">'.$totalizin.'/'.$totalsakit.'</div></td>';
    print '<td><div style="text-align: center;">'.$totalalpa.'</div></td>';
    print '<td><div style="text-align: center;">'.$totaljamkonversi.'</div></td>';
    //dpm($totalkomponengaji);
    for ($index_kg = 0;$index_kg < count($totalkomponengaji);$index_kg++){
    	print '<td><div style="text-align: right;">'.number_format($totalkomponengaji[$index_kg],0,',','.').'</div></td>';
    }
    print '</tr>';
    //dpm($array_lemburan_karyawan);
    ?>
  </tbody>
</table>