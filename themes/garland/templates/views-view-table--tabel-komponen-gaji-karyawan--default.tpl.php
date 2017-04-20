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
 $arraybulan = array_nama_bulan();
?>
<table id="tabel-komponen-gaji" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
      	<?php
      	if ($field == 'nid'){
      		$tabelbutton = 'table_button';	
      	}else{
      		$tabelbutton = '';
      	}
      	?>
        <th class="views-field views-field-<?php print $fields[$field]; ?> <?php print $tabelbutton; ?> center">
          <?php print $label; ?>
        </th>
      <?php endforeach; ?>
      <th class="center">Berlaku</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
    <?php
    $tglberlaku = $view->result[$count]->node_data_field_tanggal_berlaku_field_tanggal_berlaku_value;
    if (is_null($tglberlaku)){
    	$berlaku = '-';	
    }else{
    	$berlaku = $arraybulan[date('n', strtotime($tglberlaku))].' '.date('Y', strtotime($tglberlaku));
    }
    ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
        	<?php
        		if ($field == 'nid'){
        			$nid_komponen_gaji_karyawan = $content;
        			$content = '<img src="'.base_path().'misc/media/images/delete.ico" width="20" title="Klik untuk menghapus komponen gaji karyawan ini" onclick="hapus_komponen_gaji(this.parentNode.parentNode,\''.$nid_komponen_gaji_karyawan.'\')">';
        		}
        	?>
          <td class="views-field views-field-<?php print $fields[$field]; ?> center">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
        <td class="center"><?php print $berlaku; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>