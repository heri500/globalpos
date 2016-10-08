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
 drupal_add_js('
  function edit_tabung(address_to_go){
  	window.location = address_to_go;
  }
  $(document).ready(function(){
	 	oTable = $("#tabel_latest_datatabung_input").dataTable( {
			"bJQueryUI": true,
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false,
			"bAutoWidth": false
		});
		$("#tabel_latest_datatabung_input_filter").css("width","100%");
		$("#tabel_latest_datatabung_input_filter input").css("width","100px");
		$("button").button();
	})
 ','inline');
 $alamat_tambah_stok = base_path().'node/add/cabang-indogas';
?>
<table id="tabel_latest_datatabung_input" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th class="table_button">	
    	</th>
      <?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
      	$alamatedit = base_path().'node/'.$rows[$count]['nid'].'/edit';
      	?>
        <td align="center"><img src="<?php print base_path().'misc/media/images/edit.ico'; ?>" onclick="edit_tabung('<?php print $alamatedit; ?>')" width="20" title="Klik untuk mengubah no seri tabung untuk data stok terkait" class="icon_button"></td>
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        	<td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php } ?>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>