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
 $totaldebet = 0;
 $totalkredit = 0;
 global $user;
 $superuser = false;
 if (in_array('Super User', $user->roles)){
 	$superuser = true;	
 }
?>
<table id="tabel_jurnal" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
        <?php
	      if ($field != 'field_nilai_penyesuaian_value' && $field != 'field_posisi_penyesuaian_value' && $field != 'status' && $field != 'nid' && $field != 'nid_1'){
	      ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php
        }
        ?>
      <?php endforeach; ?>
      <th>Debet</th>
			<th>Kredit</th>
			<th style="width: 20px;"></th>
    </tr>
  </thead>
  <tbody>
  	<?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <?php
          if ($field != 'field_nilai_penyesuaian_value' && $field != 'field_posisi_penyesuaian_value' && $field != 'status' && $field != 'nid' && $field != 'nid_1'){
		      ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php
	        }
	        ?>
        <?php endforeach; ?>
        <?php
        	$statuspenyesuaian = $rows[$count]['status'];
        	$nilaitransaksi = 1*$rows[$count]['field_nilai_penyesuaian_value'];
        	$jenistransaksi = $rows[$count]['field_posisi_penyesuaian_value'];
        	if ($jenistransaksi == 'Debet'){
        		$totaldebet = $totaldebet + $nilaitransaksi;
        		$nilaidebet = $nilaitransaksi;
        		$nilaikredit = 0;
        	}else if ($jenistransaksi == 'Kredit'){
        		$totalkredit = $totalkredit + $nilaitransaksi;
        		$nilaikredit = $nilaitransaksi;
        		$nilaidebet = 0;
        	}
        	$nid_detail_jurnal = $rows[$count]['nid_1'];
        	$nid_jurnal_ref = $rows[$count]['nid'];
        	if ($statuspenyesuaian == 'True'){
        		$statusview = '<img src="'.base_path().'misc/media/images/check.png" title="Jurnal penyesuaian sudah diposting">';	
        	}else{
        		if ($superuser){ 
        			$statusview = '<img title="Jurnal penyesuaian belum diposting, klik untuk mengedit jurnal penyesuaian ini" onclick="edit_jurnal_penyesuaian(\''.$nid_jurnal_ref.'\')" src="'.base_path().'misc/media/images/warning.png" style="cursor: pointer;">';	
        		}
        	}
        ?>
        <td style="text-align: right;"><?php print number_format($nilaidebet, 0, ',', '.'); ?></td>
        <td style="text-align: right;"><?php print number_format($nilaikredit, 0, ',', '.'); ?></td>
        <td style="width: 20px;"><?php print $statusview; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
  	<th colspan="5">Total Transaksi</th>
  	<th style="text-align: right;"><b><?php print number_format($totaldebet, 0, ',', '.'); ?></b></th>
  	<th style="text-align: right;"><b><?php print number_format($totalkredit, 0, ',', '.'); ?></b></th>
  	<th>&nbsp;</th>
	</tfoot>
</table>
<button id="do-posting-penyesuaian">Posting Penyesuaian</button>