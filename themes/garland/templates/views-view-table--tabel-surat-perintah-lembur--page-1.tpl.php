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
 $superuser = cek_super_user();
 $args = array('halaman_printout','Surat Perintah Kerja Lembur');
 $halaman_print_out = create_array_from_view('get_nid_by_title_type',$args);
 $nid_halaman_print_out = $halaman_print_out[0]->nid;
 php_to_drupal_settings(array('nidhalamanprint' => $nid_halaman_print_out));
 //dpm($view->result);
?>
<table id="tabel-spl" class="display <?php print $class; ?>"<?php print $attributes; ?>>
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th class="table_button">&nbsp;</th>
    	<th class="table_button">&nbsp;</th>
    	<?php
    	if ($superuser){
    		print '<th>Workshop</th>';
    	}
    	?>
      <?php foreach ($header as $field => $label): ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
      <?php endforeach; ?>
      <th class="table_button">DKL</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
      	$icondetail = '<img src="'.base_path().'sites/all/libraries/images/details_open.png" width="20" title="Klik untuk melihat detail SPKL" onclick="view_details(this.parentNode.parentNode,this,'.$view->result[$count]->nid.')">';
      	$iconprint = '<img src="'.base_path().'sites/all/libraries/images/print.png" width="20" title="Klik untuk print SPKL" onclick="print_spl('.$view->result[$count]->nid.')">';
      	$karyawanDKL = getDataDKLBySPL2($view->result[$count]->nid);
				$listDKL = array();
				$viewListDKL = '-';
				if (count($karyawanDKL)){
					for($j = 0;$j < count($karyawanDKL);$j++){
						$listDKL[] = $karyawanDKL[$j]->node_node_data_field_karyawan_dkl_title;
					}
					$viewListDKL = implode(', ',$listDKL);
				}
				$args = array($view->result[$count]->nid);
      	$array_detail_spl = create_array_from_view('array_detail_spl', $args);
				$tabel_detail_spl = create_tabel_detail_spl($array_detail_spl,$view->result[$count]->nid,$viewListDKL);
      	$detail_data = '<div id="detail-spl-'.$view->result[$count]->nid.'-wrapper" style="display:none;">'.$tabel_detail_spl.'</div>';
      	if (count($karyawanDKL)){
      		$icondkl = '<img src="'.base_path().'sites/all/libraries/images/document.ico" width="20" title="Klik untuk mengisi data DKL" onclick="insertDKL('.$view->result[$count]->nid.')">';
      	}else{
      		$icondkl = '<img src="'.base_path().'sites/all/libraries/images/document_plain_new.png" width="20" title="Klik untuk mengisi data DKL" onclick="insertDKL('.$view->result[$count]->nid.')">';
      	}
      	?>
      	<td class="center"><?php print $icondetail.$detail_data; ?></td>
      	<td class="center"><?php print $iconprint; ?></td>
      	<?php
	    	if ($superuser){
	    		$nama_pt = $view->result[$count]->node_node_data_field_workshop_spl_node_data_field_nama_singkat_field_nama_singkat_value;
	    		$cabangworkshop = $view->result[$count]->node_node_data_field_workshop_spl_title;
	    		print '<td>'.$nama_pt.', '.$cabangworkshop.'</td>';
	    	}
	    	?>
        <?php foreach ($row as $field => $content): ?>
          <td id="<?php print $fields[$field]; ?>_<?php print $view->result[$count]->nid; ?>" class="views-field views-field-<?php print $fields[$field]; ?>"><?php print $content; ?></td>
        <?php endforeach; ?>
        <td class="center"><?php print $icondkl; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>