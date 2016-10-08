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
	 	oTable = $("#tabel_penerimaan").dataTable( {
			"bJQueryUI": true,
			"bPaginate": true,
			"sPaginationType": "full_numbers",
			"bLengthChange": true,
			"bFilter": true,
			"bInfo": true,
			"bAutoWidth": false,
			"bStateSave": true,
			"bSort": false
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
 		$alamat_tambah_stok = base_path().'node/add/penukaran-tabung';	
 	}else{
 		$view = views_get_view('get_posisi_user');
		$view->execute();
		if (count($view->result)){
			$id_posisi_user = $view->result[0]->node_data_field_user_id_field_cabang_id_nid;
			$alamat_tambah_stok = base_path().'node/add/penukaran-tabung/'.$id_posisi_user;
		}	
 	}
 	if ((arg(2) <> $id_posisi_user) AND !$superuser){
 		drupal_goto('produksi');
 	}
 	//dpm($rows);
?>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_stok; ?>')">PENUKARAN TABUNG</button>
<table id="tabel_penerimaan" class="<?php print $class; ?>">
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
      	/*$view = views_get_view('get_posisi_penerimaan');
      	$view->set_arguments(array($rows[$count]['nid']));
				$view->execute();
				if (count($view->result)){
					$id_posisi_user_berdasarkan_penerimaan = $view->result[0]->node_data_field_cabang_ref_field_cabang_ref_nid;
      		$alamattambahdetail = base_path().'node/add/data-tabung-penerimaan/'.$id_posisi_user_berdasarkan_stok.'/'.$rows[$count]['nid'];
      	}*/
      	$alamatedit = base_path().'node/'.$rows[$count]['nid'].'/edit';
      	?>
      	<td align="center"><img onclick="goto_address('<?php print $alamatedit; ?>')" src="<?php print base_path().'misc/media/images/edit.ico'; ?>" width="20" title="Klik untuk mengubah penukaran tabung" class="icon_button"></td>
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        	<?php if (trim($fields[$field]) == 'field-armada-ref-value'){ ?>
        	<?php
        		$idarmada = $content;
        		$name = 'get_no_plat_by_nid';
						$display_id = 'default';
						$args = array($idarmada);
						$no_plat = views_get_view($name);
						$no_plat->set_arguments($args);
						$no_plat->set_display($display_id);
						$no_plat->pre_execute();
						$no_plat->execute();
						if (count($no_plat->result)){
					?>
					<td class="views-field views-field-<?php print $fields[$field]; ?>">
						<?php print $no_plat->result[0]->node_data_field_no_plat_field_no_plat_value; ?>
          </td>
					<?php		
						}else{
        	?>
        	<td class="views-field views-field-<?php print $fields[$field]; ?>">
						<?php print $idarmada; ?>
          </td>
        	<?php		
						}
        	?>
        	<?php }else{ ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php } ?>
          <?php } ?>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_stok; ?>')">PENUKARAN TABUNG</button>