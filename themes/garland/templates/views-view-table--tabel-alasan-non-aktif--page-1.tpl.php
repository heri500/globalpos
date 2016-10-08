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
  function goto_address(address_to_go){
  	window.location = address_to_go;
  }
  function edit_alasan(nid){
  	alamat_edit = Drupal.settings.basePath + "node/"+ nid +"/edit";
  	goto_address(alamat_edit);
  }
  $(document).ready(function(){
	 	oTable = $("#tabelalasannonaktif").dataTable( {
			"bJQueryUI": true,
			"bPaginate": true,
			"bLengthChange": true,
			"bFilter": true,
			"bInfo": true,
			"bAutoWidth": false
		});
	})
 ','inline');

?>
<table id="tabelalasannonaktif" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
      	<?php
        	if ($field == 'nid'){
        		$added_class = ' table_button';
        	}else{
        		$added_class = '';
        	}
	      ?>
        <th class="views-field views-field-<?php print $fields[$field].$added_class; ?>">
          <?php print $label; ?>
        </th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <?php
      $nid_alasan = $rows[$count]['nid'];
      ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
        	<?php
        	if ($field != 'nid'){
	        ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php
	      	}else{
	      		$image_to_edit = '<img src="'.base_path().'misc/media/images/edit.ico" width="20" onclick="edit_alasan(\''.$nid_alasan.'\')">';
	        ?>
	        <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $image_to_edit; ?>
          </td>
	        <?php
	      	}
	        ?>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>