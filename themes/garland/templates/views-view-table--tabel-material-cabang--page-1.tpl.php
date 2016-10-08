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
  $(document).ready(function(){
	 	oTable = $("#tabel_material").dataTable( {
			"bJQueryUI": true,
			"bPaginate": true,
			"bLengthChange": true,
			"bFilter": true,
			"bInfo": true,
			"bAutoWidth": false,
			"aaSorting": [[ 1, "desc" ]]
		});
		$("button").button();
	})
 ','inline');
 	global $user;
	$superuser = false;
 	foreach ($user->roles as $user_role){
 		if (trim($user_role) == 'Super User'){
 			$superuser = true;		
 		}
 	}
 	if ($superuser){
 		$alamat_tambah_material = base_path().'node/add/data-material-workshop';	
 	}else{
 		$view = views_get_view('get_posisi_user');
		$view->execute();
		if (count($view->result)){
			$id_posisi_user = $view->result[0]->node_data_field_user_id_field_cabang_id_nid;
			$alamat_tambah_material = base_path().'node/add/data-material-workshop/'.$id_posisi_user;	
		}
 	}
 	if ((arg(2) <> $id_posisi_user) AND !$superuser){
 		drupal_goto('inventory');
 	}
?>
<button onclick="goto_address('<?php print $alamat_tambah_material; ?>')" class="add_data">TAMBAH MATERIAL CABANG</button>
<table id="tabel_material" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<?php /* <th class="table_button">	
    	</th> */ ?>
      <?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
      <th>Satuan</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
      		$alamatedit = base_path().'node/'.$rows[$count]['nid'].'/edit';
      		$view2 = views_get_view('material_nid_by_nid');
					$view2->set_arguments(array($rows[$count]['nid']));
					$view2->execute();
					if (count($view2->result)){
						$material_id = $view2->result[0]->node_data_field_material_ref_field_material_ref_nid;
						$view3 = views_get_view('material_info_by_nid');
						$view3->set_arguments(array($material_id));
						$view3->execute();
						if (count($view3->result)){
							$satuan_nid = $view3->result[0]->node_data_field_satuan_ref_field_satuan_ref_nid;
							$view4 = views_get_view('satuan_by_nid');
							$view4->set_arguments(array($satuan_nid));
							$view4->execute();
							if (count($view4->result)){
								$satuan_material = $view4->result[0]->node_title;
							}
							$hargamaterial = number_format($view3->result[0]->node_data_field_harga_field_harga_value,0,',','.');
						}
					}
      	?>
        <?php /* <td align="center"><img src="<?php print base_path().'misc/media/images/edit.ico'; ?>" onclick="goto_address('<?php print $alamatedit; ?>')" width="20" title="Klik untuk mengubah kategori material ini" class="icon_button"></td> */ ?>
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid'){ ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php } ?>
        <?php endforeach; ?>
        <td class="center"><?php print $satuan_material; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<button onclick="goto_address('<?php print $alamat_tambah_material; ?>')" class="add_data">TAMBAH MATERIAL CABANG</button>