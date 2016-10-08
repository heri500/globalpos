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
 $datatables = true;
 _include_jquery_plugins($datatables);
 drupal_add_js('
  $(document).ready(function(){
	 	oTable = $("#tabel_detail_pembelian").dataTable( {
			"bJQueryUI": true,
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": false,
			"bInfo": false,
			"bSort": false,
			"bAutoWidth": false
		});
	})
 ','inline');
?>
<div style="margin-bottom: 5px;"><b>DETAIL <?php print arg(4); ?></p></div>
<table id="tabel_detail_pembelian" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'field-pembelian-ref-nid'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
  	<?php
  		$totalbelanja = 0;
  	?>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'field-pembelian-ref-nid'){ ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php
          	if (trim($fields[$field]) == 'field-jumlah-value'){
          		$jumlah = $content;
          	}
          	if (trim($fields[$field]) == 'field-harga-value'){
          		$totalbelanja = $totalbelanja + ($jumlah*$content);
          	}
          ?>
          <?php } ?>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<div align="right" style="margin-top: 3px;"><b>Total : <?php print $totalbelanja; ?></b></div>