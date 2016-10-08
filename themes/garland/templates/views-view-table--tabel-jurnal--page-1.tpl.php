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
 global $totaldebet;
 global $totalkredit;
?>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
        	<?php
	      		if ($field != 'field_nilai_transaksi_value' && $field != 'field_posisi_account_jurnal_value' && $field != 'nid'){
	      	?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print str_replace("+"," ",$content); ?>
          </td>
          <?php
	        	}
	        ?>
        <?php endforeach; ?>
        <?php
        	$nilaitransaksi = 1*$rows[$count]['field_nilai_transaksi_value'];
        	$jenistransaksi = $rows[$count]['field_posisi_account_jurnal_value'];
        	if ($jenistransaksi == 'Debet'){
        		$totaldebet = $totaldebet + $nilaitransaksi;
        		$nilaidebet = $nilaitransaksi;
        		$nilaikredit = 0;
        	}else if ($jenistransaksi == 'Kredit'){
        		$totalkredit = $totalkredit + $nilaitransaksi;
        		$nilaikredit = $nilaitransaksi;
        		$nilaidebet = 0;
        	}
        ?>
        	<td style="text-align: right;"><?php print number_format($nilaidebet, 0, ',', '.'); ?></td>
        	<td style="text-align: right;"><?php print number_format($nilaikredit, 0, ',', '.'); ?></td>
        <?php
        	$nid_detail_jurnal = $rows[$count]['nid'];
        	$posted_count = db_result(db_query("SELECT COUNT(*) FROM indogas_posted_jurnal WHERE nid_jurnal='%d'",$nid_detail_jurnal));
        	if ($posted_count > 0){
        		$statusview = '<img src="'.base_path().'misc/media/images/check.png" title="Jurnal sudah diposting">';	
        	}else{
        		$statusview = '<img src="'.base_path().'misc/media/images/warning.png" title="Jurnal Belum diposting">';	
        	}
        ?>	
        	<td align="center" valign="center" class="center"><?php print $statusview; ?></td>
      </tr>
    <?php endforeach; ?>