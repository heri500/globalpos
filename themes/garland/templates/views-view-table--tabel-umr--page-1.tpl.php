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
 	var oTable;
 	function goto_address(address_to_go){
  	window.location = address_to_go;
  }
 	$(document).ready(function(){
 		oTable = $("#tabel-umr").dataTable( {
			"bJQueryUI": true,
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": true,
			"bAutoWidth": false,
      "bSort": false
		});
 	})
 ','inline');
?>
<table id="tabel-umr" class="<?php print $class; ?> display"<?php print $attributes; ?>>
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
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
      	$nid_umr = $view->result[$count]->nid;
      	$alamatedit = base_path().'node/'.$nid_umr.'/edit?destination=node/add/umr';
      	?>
      	<td><img src="<?php print base_path().'misc/media/images/edit.ico'; ?>" onclick="goto_address('<?php print $alamatedit; ?>')" width="20" title="Klik untuk mengubah data umr ini" class="icon_button"></td>
        <?php foreach ($row as $field => $content): ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>