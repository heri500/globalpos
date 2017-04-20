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

<table id="tabelpengeluaran" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid-1'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
	      <th style="display: none;">
	      	&nbsp;
	      </th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
    	<?php
      	$nid_jurnal = $rows[$count]['nid_1'];
        $detailjurnal = views_embed_view("tabel_detail_jurnal_by_nidjurnal","default",$nid_jurnal);
	      $inputdetail = '<div id="detail_'.$nid_jurnal.'" style="display:none">'.$detailjurnal.'</div>';
	      global $totaltransaksi;
	    ?>
      <tr id="baris_<?php print $nid_jurnal; ?>" class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <?php if (trim($fields[$field]) <> 'nid-1'){ ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php
            if (trim($fields[$field]) == 'field-harga-value'){
            	print '<div id="total_'.$nid_jurnal.'" align="right">Rp. '.number_format($totaltransaksi, 0, ',', '.').'</div>';
            }else if(trim($fields[$field]) == 'field-tanggal-stok-value'){
            	print '<div id="tanggal_jurnal_'.$nid_jurnal.'" class="editable">'.$content.'</div>';
            }else if(trim($fields[$field]) == 'field-jam-value'){
            	print '<div id="jam_jurnal_'.$nid_jurnal.'" class="editable2">'.$content.'</div>';
            }else if(trim($fields[$field]) == 'title'){
            	print '<div id="no_ref_'.$nid_jurnal.'" class="editable3">'.$content.'</div>';
            }else{
            ?>	
            <?php print $content; ?>
            <?php } ?>
          </td>
          <?php } ?>
        <?php endforeach; ?>
        <td style="display: none;">
        <?php print $inputdetail; ?>
      	</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>