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
 ?>
<div id="block_approve">
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
      	  	if ($field == 'field_tanggal_stok_value' || $field == 'field_jumlah_value'){
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
	      				$tgllama = $content;
	      				$pecah_tanggal = explode("-",$content);
	      				$content = $pecah_tanggal[2];
	      			}
	      	?>
          <td title="<?php print $tgllama; ?>" class="views-field views-field-<?php print $fields[$field]; ?>">
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
      <th>
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