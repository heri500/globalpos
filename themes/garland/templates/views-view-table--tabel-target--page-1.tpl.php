<?php
/**
 * @file views-view-table.tpl.php
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $header_classes: An array of header classes keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $class: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * - $field_classes: An array of classes to apply to each field, indexed by
 *   field id, then row number. This matches the index in $rows.
 * @ingroup views_templates
 */
 $datatables = true;
 _include_jquery_plugins($datatables);
 drupal_add_js('
	function go_to_address(alamat,destination){
		window.location = alamat + "?destination="+ destination;
	}
	$(document).ready(function(){
		oTable = $(".display").dataTable( {
			"bJQueryUI": true,
			"bPaginate": true,
			"sPaginationType": "full_numbers",
			"bLengthChange": true,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": false,
			"bStateSave": true,
			"oLanguage": {
		    "sProcessing":   "Sedang memproses...",
		    "sLengthMenu":   "Tampilkan _MENU_ entri",
		    "sZeroRecords":  "Tidak ditemukan data yang sesuai",
		    "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
		    "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
		    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
		    "sInfoPostFix":  "",
		    "sSearch":       "Cari:",
		    "sUrl":          "",
		    "oPaginate": {
		        "sFirst":    "Pertama",
		        "sPrevious": "Sebelumnya",
		        "sNext":     "Selanjutnya",
		        "sLast":     "Terakhir"
		    }
			}
		});
		$("button").button();
	})
	','inline');
	global $user;
	if ($user->uid == 1){
		$destination = $view->get_url();
		$args = array($destination);
		$viewname = 'get_view_add_destination';
		$array_add_button = create_array_from_view($viewname,$args);
		$alamat = base_path().$array_add_button[0]->node_data_field_button_label_field_add_destination_value;
		$label = $array_add_button[0]->node_data_field_button_label_field_button_label_value;
		print '<button class="button-tambah" onclick="go_to_address(\''.$alamat.'\',\''.$destination.'\')">'.$label.'</button>';	
	}
?>
<table class="<?php print $class; ?> display"<?php print $attributes; ?>>
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <?php if (!empty($header)) : ?>
    <thead>
      <tr>
        <?php foreach ($header as $field => $label): ?>
          <th class="<?php print $header_classes[$field]; ?>">
            <?php print $label; ?>
          </th>
        <?php endforeach; ?>
        <?php
        	if ($user->uid == 1){
        ?>
        	<th class="table-button">&nbsp;</th>
        	<th class="table-button">&nbsp;</th>
        <?php 
        	} 
        ?>
      </tr>
    </thead>
  <?php endif; ?>
  <tbody>
    <?php foreach ($rows as $row_index => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$row_index]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <td class="<?php print $field_classes[$field][$row_index]; ?>" <?php print drupal_attributes($field_attributes[$field][$row_index]); ?>>
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
        <?php
        	if ($user->uid == 1){
        		$nid = $view->result[$row_index]->nid;
        		$button = create_edit_delete_node_button($nid,$destination);
        ?>
        	<td class="tabel-button"><?php print $button['edit']; ?></td>
        	<td class="tabel-button"><?php print $button['hapus']; ?></td>
        <?php 
        	} 
        ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>