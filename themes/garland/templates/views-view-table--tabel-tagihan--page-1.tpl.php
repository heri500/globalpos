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
 if (isset($_GET['bulan'])){
		$bulan = $_GET['bulan'];
	}else{
		$bulan = 12;
	}
	if (isset($_GET['tahun'])){
		$tahun = $_GET['tahun'];
	}else{
		$tahun = 2011;
	}
	if (isset($_GET['cabang'])){
		$workshop_nid = $_GET['cabang'];
	}else{
		$workshop_nid = 1;
	}
	$array_approved_summary = create_array_summary_approved_tagihan($workshop_nid,$bulan,$tahun);
	$button_unapproved = '';
	$summaryapproved = false;
	if (count($array_approved_summary) > 0){
		$summaryapproved = true;
		$button_unapproved = '<button id="do_unapprove_tagihan" class="un-approve-button" style="margin-bottom: 10px;">Un-approve Tagihan</button>';
	}
	php_to_drupal_settings(array('summaryapproved' => $summaryapproved));
 ?>
<button id="view_detail_tagihan" style="margin-bottom: 10px;">Lihat Detail Tagihan</button>&nbsp;<button id="do_approve_tagihan" style="margin-bottom: 10px;">Approve Tagihan</button>&nbsp;<?php print $button_unapproved; ?>
<div id="block_summary_place_holder">
</div> 
<div id="block_approve">
<p><b>Pilih tagihan yang akan diapprove/un-approve dengan meng klik baris tagihan yang diinginkan, kemudian klik Approve Tagihan untuk meng-approve tagihan yang dipilih, atau Un-Approve Tagihan untuk meng Un-approve tagihan yang dipilih (dapat dipilih beberapa tagihan sekaligus)</b></p>
<div class="form_row" style="margin-bottom:10px;"><button id="select_all" class="filter-button" style="font-size:12px;">Select All</button>&nbsp;&nbsp;<button id="de_select_all" class="filter-button" style="font-size:12px;">Deselect All</button></div>
<table id="tabel_tagihan" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
      	<?php
      		if ($field != 'nid' && $field != 'nid_1'){
      	  	if ($field == 'field_tanggal_stok_value' || $field == 'field_jumlah_value' || $field == 'status'){
          ?>
        <th style="width:40px;">
          <div style="height: 220px;margin-bottom:-60px;width:20px;"><?php print rotate_text($label,-150,14,'#2E6E9E'); ?></div>          
        </th>
        <?php
	      		}else{
	      			print '<th class="views-field views-field-'.$fields[$field].'" style="width: 300px;text-align: center;">';
	      			print $label;
	      			print '</th>';
	      		}
      		}
        ?>
      <?php endforeach; ?>
      <?php 
      	$array_pekerjaan = create_array_pekerjaan();
      	$total_per_pekerjaan = array();
      	for ($i = 0;$i < count($array_pekerjaan);$i++){
      		$pekerjaan = $array_pekerjaan[$i]->node_data_field_singkatan_field_singkatan_value;
      		$nid_pekerjaan = $array_pekerjaan[$i]->nid;
      		if ($array_pekerjaan[$i]->node_data_field_singkatan_field_pakai_balancer_value == 1){
	      		for ($j = 0;$j < 3;$j++){
	      			if ($j > 0){
	      				$pekerjaanbaru = $pekerjaan.'+PB'.$j;	
	      			}else{
	      				$pekerjaanbaru = $pekerjaan;
	      			}
	      			$total_per_pekerjaan[$nid_pekerjaan][$j] = 0;
	      			print '<th style="width:55px;"><div style="height: 220px;margin-bottom:-60px;width:25px;">'.rotate_text($pekerjaanbaru,-150,22,'#2E6E9E').'</div></th>';
	      		}
	      	}else{
	      		$total_per_pekerjaan[$nid_pekerjaan][0] = 0;
	      		print '<th style="width:55px;"><div style="height: 220px;margin-bottom:-60px;width:25px;">'.rotate_text($pekerjaan,-150,22,'#2E6E9E').'</div></th>';
	      	}
      	}
      ?>
    </tr>
  </thead>
  <tbody>
  	<?php 
  		$total_tabung = 0;
  	?>
    <?php foreach ($rows as $count => $row): ?>
    	<?php
    		$nid_tagihan = $rows[$count]['nid_1'];
    		$nid_pengiriman = $rows[$count]['nid'];
    		$array_pekerjaan_pengiriman = array();
    		$array_pekerjaan_pengiriman = create_array_pekerjaan_by_jenis_plat($nid_pengiriman);
    		$jumlah_tabung = $rows[$count]['field_jumlah_value'];
    		$total_tabung = $total_tabung + (int)$jumlah_tabung;
    		//dpm($array_pekerjaan_pengiriman);
    	?>
    	<tr id="<?php print $nid_tagihan; ?>" class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <?php
	      		if ($field != 'nid' && $field != 'nid_1'){
	      			if ($field == 'field_tanggal_stok_value'){
	      				$pecah_tanggal = explode("-",$content);
	      				$content = $pecah_tanggal[2];
	      			}else if($field == 'status'){
	      				if ($content == 'No'){
	      					$content = '<img src="'.base_path().'misc/media/images/warning.png">';
	      				}else{
	      					$content = '<img src="'.base_path().'misc/media/images/check.png">';
	      				}
	      			}
	      	?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php
		      	}
	        ?>
        <?php endforeach; ?>
        <?php 
	      	$array_pekerjaan = create_array_pekerjaan();
	      	for ($i = 0;$i < count($array_pekerjaan);$i++){
	      		$pekerjaan = $array_pekerjaan[$i]->node_data_field_singkatan_field_singkatan_value;
	      		$nid_pekerjaan = $array_pekerjaan[$i]->nid;
      			if ($array_pekerjaan[$i]->node_data_field_singkatan_field_pakai_balancer_value == 1){
		      		for ($j = 0;$j < 3;$j++){
		      			$jumlah = (int)$array_pekerjaan_pengiriman[$nid_pekerjaan][$j];
		      			if ($jumlah == 0){
		      				$jumlah_view = '-';	
		      			}else{
		      				$jumlah_view = $jumlah;
		      			}
		      			$total_per_pekerjaan[$nid_pekerjaan][$j] = $total_per_pekerjaan[$nid_pekerjaan][$j] + $jumlah;
		      			print '<td style="text-align: right;">'.$jumlah_view.'</td>';
		      		}
		      	}else{
		      		$jumlah = (int)$array_pekerjaan_pengiriman[$nid_pekerjaan][0];
		      		if ($jumlah == 0){
		      			$jumlah_view = '-';	
		      		}else{
		      			$jumlah_view = $jumlah;
		      		}
		      		$total_per_pekerjaan[$nid_pekerjaan][0] = $total_per_pekerjaan[$nid_pekerjaan][0] + $jumlah;
		      		print '<td style="text-align: right;">'.$jumlah_view.'</td>';
		      	}
	      	}
	      ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
  	<tr>
      <th colspan="2">
      	Total Tabung          
      </th>
      <th colspan="2" class="angka">
      	<?php print number_format($total_tabung,0,',','.'); ?>
      </th>
      <?php 
      	$array_pekerjaan = create_array_pekerjaan();
      	for ($i = 0;$i < count($array_pekerjaan);$i++){
      		$pekerjaan = $array_pekerjaan[$i]->node_data_field_singkatan_field_singkatan_value;
      		$nid_pekerjaan = $array_pekerjaan[$i]->nid;
      		if ($array_pekerjaan[$i]->node_data_field_singkatan_field_pakai_balancer_value == 1){
	      		for ($j = 0;$j < 3;$j++){
	      			if ($j > 0){
	      				$pekerjaanbaru = $pekerjaan.'+PB'.$j;	
	      			}else{
	      				$pekerjaanbaru = $pekerjaan;
	      			}
	      			print '<th class="angka" style="width:55px;">'.number_format($total_per_pekerjaan[$nid_pekerjaan][$j],0,',','.').'</th>';
	      		}
	      	}else{
	      		print '<th class="angka" style="width:55px;">'.number_format($total_per_pekerjaan[$nid_pekerjaan][0],0,',','.').'</th>';
	      	}
      	}
      ?>
    </tr>
  </tfoot>
</table>
</div>
<div id="block_penukaran_valve">
<?php
	$valve_table = '<table id="tabel_penukaran_valve" class="display" style="width:500px;">';
	$valve_table .= '<thead>';
	$valve_table .= '<tr>';
	$valve_table .= '<th class="table-button">&nbsp</th>';
	$valve_table .= '<th>Tanggal</th>';
	$valve_table .= '<th style="text-align: center;width: 80px;">Jumlah</th>';
	$valve_table .= '</tr>';
	$valve_table .= '</thead>';
	$valve_table .= '<tbody>';
	$array_penukaran = create_array_penukaran_valve($workshop_nid,$bulan,$tahun);
	$total_tukar_valve = 0;
	for ($i = 0;$i < count($array_penukaran);$i++){
		$valve_table .= '<tr id="'.$array_penukaran[$i]->nid.'">';
		if ($array_penukaran[$i]->node_status == 1){
			$status_penukaran = '<img src="'.base_path().'misc/media/images/check.png">';
		}else{
			$status_penukaran = '<img src="'.base_path().'misc/media/images/warning.png">';
		}
		$valve_table .= '<td>'.$status_penukaran.'</td>';
		$valve_table .= '<td>'.date('d-M-Y',$array_penukaran[$i]->node_created).'</td>';
		$valve_table .= '<td class="angka">'.$array_penukaran[$i]->node_data_field_jumlah_field_jumlah_value.'</td>';
		$valve_table .= '</tr>';
		$total_tukar_valve = $total_tukar_valve + $array_penukaran[$i]->node_data_field_jumlah_field_jumlah_value;
	}
	$valve_table .= '</tbody>';
  $valve_table .= '<tfoot>';
  $valve_table .= '<tr>';
	$valve_table .= '<th colspan="2">Total</th>';
	$valve_table .= '<th class="angka" style="font-weight: bold;color: blue;">'.number_format($total_tukar_valve,0,',','.').'</th>';
	$valve_table .= '</tr>';
  $valve_table .= '</tfoot>';
	$valve_table .= '</table>';
	print $valve_table;
?>	
</div>
<div id="block_tagihan_transportasi">
<?php
	$tagihan_table = '<table id="tabel_tagihan_transportasi" class="display" style="width:500px;">';
	$tagihan_table .= '<thead>';
	$tagihan_table .= '<tr>';
	$tagihan_table .= '<th class="table-button">&nbsp</th>';
	$tagihan_table .= '<th>Tanggal</th>';
	$tagihan_table .= '<th style="text-align: center;width: 110px;">Tagihan</th>';
	$tagihan_table .= '</tr>';
	$tagihan_table .= '</thead>';
	$tagihan_table .= '<tbody>';
	$array_tagihan = create_array_tagihan_transportasi($workshop_nid,$bulan,$tahun);
	$total_tagihan = 0;
	for ($i = 0;$i < count($array_tagihan);$i++){
		$tagihan_table .= '<tr id="'.$array_tagihan[$i]->nid.'">';
		if ($array_tagihan[$i]->node_status == 1){
			$status_tagihan = '<img src="'.base_path().'misc/media/images/check.png">';
		}else{
			$status_tagihan = '<img src="'.base_path().'misc/media/images/warning.png">';
		}
		$tagihan_table .= '<td>'.$status_tagihan.'</td>';
		$tagihan_table .= '<td>'.date('d-M-Y',$array_tagihan[$i]->node_created).'</td>';
		$tagihan_table .= '<td class="angka">'.number_format($array_tagihan[$i]->node_data_field_besar_tagihan_field_besar_tagihan_value,0,',','.').'</td>';
		$tagihan_table .= '</tr>';
		$total_tagihan = $total_tagihan + $array_tagihan[$i]->node_data_field_besar_tagihan_field_besar_tagihan_value;
	}
	$tagihan_table .= '</tbody>';
  $tagihan_table .= '<tfoot>';
  $tagihan_table .= '<tr>';
	$tagihan_table .= '<th colspan="2">Total</th>';
	$tagihan_table .= '<th class="angka" style="font-weight: bold;color: blue;">'.number_format($total_tagihan,0,',','.').'</th>';
	$tagihan_table .= '</tr>';
  $tagihan_table .= '</tfoot>';
	$tagihan_table .= '</table>';
	print $tagihan_table;
?>	
</div>
<div id="block_summary">
<?php 
	if ($summaryapproved){
		$array_summary = create_array_summary_approved_tagihan($workshop_nid,$bulan,$tahun);
		$summary_table = create_summary_approved_tagihan_tabel($array_summary);
		print $summary_table;
	}else{
		//$array_pekerjaan = create_array_pekerjaan();
		$summary_table = '<table id="summary_tagihan" class="display">';
		$summary_table .= '<thead>';
		$summary_table .= '<tr>';
		$summary_table .= '<th>Pekerjaan</th>';
		$summary_table .= '<th style="text-align: center;width: 80px;">Total</th>';
		$summary_table .= '<th style="text-align: center;width: 80px;">Harga</th>';
		$summary_table .= '<th style="text-align: center;width: 150px;">Nilai</th>';
		$summary_table .= '</tr>';
		$summary_table .= '</thead>';
		$summary_table .= '<tbody>';
		$array_pekerjaan = create_array_pekerjaan();
		$total_nilai_pekerjaan = 0;
		$array_summary = array();
	 	for ($i = 0;$i < count($array_pekerjaan);$i++){
	 		$pekerjaan = $array_pekerjaan[$i]->node_data_field_singkatan_field_singkatan_value;
	 		$judulpekerjaan = $array_pekerjaan[$i]->node_title;
	 		$nid_pekerjaan = $array_pekerjaan[$i]->nid;
	 		if ($array_pekerjaan[$i]->node_data_field_singkatan_field_pakai_balancer_value == 1){
	   		$hargapekerjaan = 0;
	   		for ($j = 0;$j < 3;$j++){
	   			if ($j > 0){
	   				$pekerjaanbaru = $judulpekerjaan.' + Plat Balancer '.$j;	
	   			}else{
	   				$pekerjaanbaru = $judulpekerjaan;
	   			}
	   			$hargapekerjaan = get_harga_pekerjaan($nid_pekerjaan,$j);
	   			$datahargapekerjaan = get_harga_pekerjaan_fullnode($nid_pekerjaan,$j);
	   			$nidDataHarga = $datahargapekerjaan->nid;
	   			$totalnilai = $hargapekerjaan * $total_per_pekerjaan[$nid_pekerjaan][$j];
	   			$totalnilai_view = number_format($totalnilai,0,',','.');
	   			if ($total_per_pekerjaan[$nid_pekerjaan][$j] != 0 && $hargapekerjaan > 0){
	   				$summary_table .= '<tr id="'.$array_pekerjaan[$i]->nid.'" class="baris_click" ';
	   				$summary_table .= 'onclick="view_detail_pekerjaan_data(\''.$array_pekerjaan[$i]->nid.'\')">';
	   				$summary_table .= '<td>'.$pekerjaanbaru.'</td><td class="angka"><input type="hidden" id="total_pekerjaan_'.$nidDataHarga.'" value="'.$total_per_pekerjaan[$nid_pekerjaan][$j].'"><input type="hidden" id="subtotal-'.$nidDataHarga.'" value="'.$totalnilai.'" class="subtotaltagihan">'.number_format($total_per_pekerjaan[$nid_pekerjaan][$j],0,',','.').'</td>';
	   				$summary_table .= '<td id="harga-'.$nidDataHarga.'" class="angka edit-harga-pekerjaan">'.$hargapekerjaan.'</td>';
	   				$summary_table .= '<td id="total_nilai_'.$nidDataHarga.'" class="angka" style="font-weight: bold;">'.$totalnilai_view.'</td></tr>';
	   			}
	   			$total_nilai_pekerjaan = $total_nilai_pekerjaan + $totalnilai;
	   			$array_summary[$array_pekerjaan[$i]->nid][$j]['nama'] = $pekerjaanbaru;
	   			$array_summary[$array_pekerjaan[$i]->nid][$j]['harga'] = $hargapekerjaan;
	   			$array_summary[$array_pekerjaan[$i]->nid][$j]['total'] = $total_per_pekerjaan[$nid_pekerjaan][$j];
	   			//$array_summary[$array_pekerjaan[$i]->nid][$j]['total-nilai'] = $hargapekerjaan * $array_summary[$array_pekerjaan[$i]->nid][$j]['harga'];
	   		}
	   	}else{
	   		if ($nid_pekerjaan == 47){
	   			$total_per_pekerjaan[$nid_pekerjaan][0] = count_total_penukaran_valve($workshop_nid,$bulan,$tahun);
	   		}
	   		$hargapekerjaan = get_harga_pekerjaan($nid_pekerjaan,0);
	   		$datahargapekerjaan = get_harga_pekerjaan_fullnode($nid_pekerjaan,0);
	   		$nidDataHarga = $datahargapekerjaan->nid;
	   		$totalnilai = $hargapekerjaan * $total_per_pekerjaan[$nid_pekerjaan][0];
	   		$totalnilai_view = number_format($totalnilai,0,',','.');
	   		if ($total_per_pekerjaan[$nid_pekerjaan][0] != 0 && $hargapekerjaan > 0){
	   			$summary_table .= '<tr id="'.$array_pekerjaan[$i]->nid.'" class="baris_click" ';
	   			$summary_table .= 'onclick="view_detail_pekerjaan_data(\''.$array_pekerjaan[$i]->nid.'\')">';
	   			$summary_table .= '<td>'.$judulpekerjaan.'</td>';
	   			$summary_table .= '<td class="angka"><input type="hidden" id="total_pekerjaan_'.$nidDataHarga.'" ';
	   			$summary_table .= 'value="'.$total_per_pekerjaan[$nid_pekerjaan][0].'"><input type="hidden" id="subtotal-'.$nidDataHarga.'" value="'.$totalnilai.'" class="subtotaltagihan">'.number_format($total_per_pekerjaan[$nid_pekerjaan][0],0,',','.').'</td>';
	   			$summary_table .= '<td id="harga-'.$nidDataHarga.'" class="angka edit-harga-pekerjaan">'.$hargapekerjaan.'</td>';
	   			$summary_table .= '<td id="total_nilai_'.$nidDataHarga.'" class="angka" style="font-weight: bold;">'.$totalnilai_view.'</td>';
	   			$summary_table .= '</tr>';
	   		}
	   		$total_nilai_pekerjaan = $total_nilai_pekerjaan + $totalnilai;
	   		$array_summary[$nid_pekerjaan][0]['nama'] = $judulpekerjaan;
	   		$array_summary[$nid_pekerjaan][0]['harga'] = $hargapekerjaan;
	   		$array_summary[$nid_pekerjaan][0]['total'] = $total_per_pekerjaan[$nid_pekerjaan][0];
	   		//$array_summary[$nid_pekerjaan][0]['total-nilai'] = $hargapekerjaan * $total_per_pekerjaan[$nid_pekerjaan][0];
	   	}
	 	}
	 	//dpm($array_summary);
	 	if ($total_tagihan > 0){
	 		$summary_table .= '<tr id="tagihan_transportasi_workshop" class="baris_click" onclick="view_detail_tagihan_transportasi()">';
	 		$summary_table .= '<td>Transport Fee</td>';
	 		$summary_table .= '<td class="angka">&nbsp;</td>';
	 		$summary_table .= '<td class="angka">&nbsp;</td>';
	 		$summary_table .= '<td class="angka" style="font-weight: bold;">'.number_format($total_tagihan,0,',','.').'</td>';
	 		$summary_table .= '</tr>';
	 	}
	 	$array_summary[0][0]['nama'] = 'Tagihan Transportasi';
	  $array_summary[0][0]['harga'] = $total_tagihan;
	  if ($total_tagihan > 0){
	  	$array_summary[0][0]['total'] = 1;
	  }else{
	  	$array_summary[0][0]['total'] = 0;
	  }
	 	$finance_pusat = cek_finance_pusat();
	 	if ($finance_pusat){
	 		//dpm($array_summary);
	 		create_temp_summary_approved_tagihan($array_summary,$workshop_nid,$bulan,$tahun);
	 	}
	 	$total_nilai_pekerjaan = $total_nilai_pekerjaan + $total_tagihan;
	  $summary_table .= '</tbody>';
	  $summary_table .= '<tfoot>';
	  $summary_table .= '<tr>';
		$summary_table .= '<th>Total Tagihan</th>';
		$summary_table .= '<th id="total_seluruh_tagihan" colspan="3" class="angka" style="font-weight: bold;color: blue;">'.number_format($total_nilai_pekerjaan,0,',','.').'</th>';
		$summary_table .= '</tr>';
	  $summary_table .= '</tfoot>';
		$summary_table .= '</table>';
		print $summary_table;
	}
?>
</div>
