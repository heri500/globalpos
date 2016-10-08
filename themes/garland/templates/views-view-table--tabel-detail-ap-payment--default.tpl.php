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
?>
<table id="detail-<?php print $view->args[0]; ?>" class="display detail-ap-table <?php print $class; ?>"<?php print $attributes; ?>>
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
      	<?php
        	if ($field == 'field_nilai_detail_ap_value'){
        ?>
        	<th class="center">
            Harga
          </th>
        <?php		
        	}
        ?>
        <th class="center views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
    	<?php
    	$harga = (int)$view->result[$count]->node_data_field_ap_payment_ref_field_nilai_detail_ap_value/(int)$view->result[$count]->node_node_data_field_payment_penerimaan_ref_node_data_field_jumlah_field_jumlah_value;
    	$tambahanclass = '';
    	?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
        <?php
        	if ($field == 'field_nilai_detail_ap_value'){
        ?>
        	<td class="angka">
            <?php print number_format($harga,0,',','.'); ?>
          </td>
        <?php		
        	}else if ($field == 'field_po_ref_nid'){
        		$tambahanclass = 'center';
        	}
        ?>
          <td class="<?php print $tambahanclass; ?> views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>