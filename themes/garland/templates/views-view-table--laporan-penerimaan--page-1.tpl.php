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
 	global $user;
	$superuser = false;
	if (in_array('Super User',$user->roles)){
 		$alamat_tambah_penerimaan = base_path().'node/add/data-penerimaan-tabung';
 		$superuser = true;
 	}else{
 		$view_posisi = views_get_view('get_posisi_user');
 		$view_posisi->execute();	
 		if (count($view_posisi->result)){
  		$id_posisi_user = $view_posisi->result[0]->node_data_field_user_id_field_cabang_id_nid;
  		$alamat_tambah_penerimaan = base_path().'node/add/data-penerimaan-tabung/'.$id_posisi_user;
  	}
 	}	
?>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_penerimaan; ?>')">PENERIMAAN TABUNG</button>
<table id="tabel_penerimaan" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th class="table_button">	
    	&nbsp;
    	</th>
      <?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
      <?php
      	if ($superuser){
      ?>
      <th class="table_button">	
    	&nbsp;
    	</th>
      <?php
      	}
      ?>
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
  			$buttondelete = '<img onclick="delete_penerimaan(\''.$rows[$count]['nid'].'\',this.parentNode.parentNode)" src="'.base_path().'misc/media/images/delete2.png" width="16" title="Klik untuk menghapus penerimaan" class="icon_button">';
      	?>
      	<?php /*<td align="center"><img onclick="goto_address('<?php print $alamattambahdetail; ?>')" src="<?php print base_path().'misc/media/images/document.ico'; ?>" width="20" title="Klik untuk mengisi detail informasi penerimaan" class="icon_button"></td> */ ?>
      	<td align="center"><img onclick="goto_address('<?php print $alamatedit; ?>')" src="<?php print base_path().'misc/media/images/edit.ico'; ?>" width="20" title="Klik untuk mengubah penerimaan" class="icon_button"></td>
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
        	<?php } ?>
        	<?php }else{ ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php } ?>
          <?php } ?>
        <?php endforeach; ?>
        <?php
      	if ($superuser){
	      ?>
	      <td class="center"><?php print $buttondelete; ?></td>
	      <?php
	      	}
	      ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_penerimaan; ?>')">PENERIMAAN TABUNG</button>