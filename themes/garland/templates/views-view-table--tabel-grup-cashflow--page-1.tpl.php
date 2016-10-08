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
 $datatables = true;
 _include_jquery_plugins($datatables);
 drupal_add_js('
 function goto_address(alamat){
 	window.location = alamat;
 }
 $(document).ready(function(){
 	oTable = $("#tabel-grup-kategori").dataTable( {
		"bJQueryUI": true,
		"bPaginate": true,
		"sPaginationType": "full_numbers",
		"bLengthChange": true,
		"bFilter": true,
		"bSort": true,
		"bInfo": true,
		"bAutoWidth": false,
		"bStateSave": true,
		"aoColumnDefs": [
      { "bSortable": false, "aTargets": [ 1,2 ] }
    ]
	});
	$("td.views-field-edit-node a").each(function(){
		var alamatedit = $(this).attr("href");
		var edit_img = "<img onclick=\"goto_address(\'"+ alamatedit +"\')\" src=\""+ Drupal.settings.basePath +"sites/all/libraries/images/edit.ico\" width=\"20\">";
		$(this).parent().append(edit_img);
		$(this).remove();
	});
	$("td.views-field-delete-node a").each(function(){
		alamatdelete = $(this).attr("href");
		var delete_img = "<img onclick=\"goto_address(\'"+ alamatdelete +"\')\" src=\""+ Drupal.settings.basePath +"sites/all/libraries/images/del.ico\" width=\"20\">";
		$(this).parent().append(delete_img);
		$(this).remove();
	});
	$("#add-grup-kategori").button().css("margin-bottom","10px").click(function(){
		alamat = Drupal.settings.basePath + "node/add/grup-kategori-cashflow?destination=akutansi/grupkategoricf";
		goto_address(alamat);
	});
 })
	','inline');
?>
<button id="add-grup-kategori">Tambah Grup Kategori CF</button>
<table id="tabel-grup-kategori" class="display <?php print $class; ?>"<?php print $attributes; ?>>
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>