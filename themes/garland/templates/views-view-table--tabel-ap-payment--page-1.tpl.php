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
 $array_latest_tutup_buku = create_array_latest_tutup_buku_bulanan();
 php_to_drupal_settings(array('latesttutupbuku' => $array_latest_tutup_buku));
?>
<button style="margin-bottom: 15px;" onclick="window.location = '<?php print base_path(); ?>akutansi/paymentform'">Input AP Payment</button>
<table id="table-ap-payment" class="display <?php print $class; ?>"<?php print $attributes; ?>>
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    		<th class="table_button">&nbsp;</th>
      <?php foreach ($header as $field => $label): ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
      <?php endforeach; ?>
      	<th>No. PO</th>
      	<th>No. Ref/Jurnal</th>
      	<th class="table_button">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
        	$nid_payment = $view->result[$count]->nid;
        	$array_po = get_payment_related_po($nid_payment);
        	$po = '<ul style="margin: 0;padding:0;">';
        	for ($i = 0;$i < count($array_po);$i++){
        		$po .= '<li>'.$array_po[$i].'</li>';
        	}
        	$po .= '</ul>';
        	$args = array($nid_payment);
        	$related_jurnal = create_array_from_view('nid_jurnal_by_nid_asal',$args);
        	$detail_payment = '<img src="'.base_path().'sites/all/libraries/images/details_open.png" width="20" onclick="view_details(this.parentNode.parentNode,this,'.$nid_payment.')">';
        	$detail_payment_table = '<div id="detail-ap-'.$nid_payment.'-wrapper" style="display: none;">'.views_embed_view('tabel_detail_ap_payment', 'default', $nid_payment).'</div>';
        	$detail_other_payment_table = '<div id="detail-other-ap-'.$nid_payment.'-wrapper" style="display: none;">'.views_embed_view('table_detail_other_ap_payment', 'default', $nid_payment).'</div>';
        	$nid_workshop = $view->result[$count]->node_data_field_total_ap_payment_field_ap_payment_workshop_ref_nid;
        	if ($view->result[$count]->node_created < $array_latest_tutup_buku[$nid_workshop][0]->node_created){
        		//$edit_icon = '<img src="'.base_path().'sites/all/libraries/images/check.png" width="20" title="Perubahan tidak dapat dilakukan karena jurnal sudah di closing untuk payment ini" onclick="alert($(this).attr(\'title\'))">';	
        		$delete_icon = '<img src="'.base_path().'sites/all/libraries/images/check.png" width="20" title="Delete payment tidak dapat dilakukan karena jurnal sudah di closing untuk payment ini" onclick="alert($(this).attr(\'title\'))">';	
        	}else{
        		//$edit_icon = '<img src="'.base_path().'sites/all/libraries/images/edit.ico" width="20" title="Klik untuk mengubah payment ini" onclick="ubah_payment('.$nid_payment.')">';	
        		$delete_icon = '<img src="'.base_path().'sites/all/libraries/images/del.ico" width="20" title="Klik untuk menghapus payment ini" onclick="hapus_payment(this.parentNode.parentNode,'.$nid_payment.')">';	
        	}
        ?>
      	<td valign="top"><?php print $detail_payment.$detail_payment_table.$detail_other_payment_table; ?></td>
        <?php foreach ($row as $field => $content): ?>
          <td valign="top" class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
        <td valign="top"><?php print $po; ?></td>
        <td valign="top"><?php print $related_jurnal[0]->node_title; ?></td>
        <td valign="top" class="center"><?php print $delete_icon; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>