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
 	foreach ($user->roles as $user_role){
 		if (trim($user_role) == 'Super User'){
 			$superuser = true;		
 		}
 	}
 	if ($superuser){
 		$alamat_tambah_penerimaan = base_path().'node/add/penerimaan-material';	
 	}else{
 		$view = views_get_view('get_posisi_user');
		$view->execute();
		if (count($view->result)){
			$id_posisi_user = $view->result[0]->node_data_field_user_id_field_cabang_id_nid;
			$alamat_tambah_penerimaan = base_path().'node/add/penerimaan-material/'.$id_posisi_user;
		}	
 	}
 	/*if ((arg(2) <> $id_posisi_user) AND !$superuser){
 		drupal_goto('produksi');
 	}*/
 	//dpm($rows);
?>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_penerimaan; ?>')">PENERIMAAN MATERIAL</button>
<table id="tabel_penerimaan_material" class="<?php print $class; ?>">
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
      	/*$alamatedit = base_path().'node/'.$rows[$count]['nid'].'/edit';
      	$alamatdetailpenerimaan = base_path().'node/add/detail-penerimaan-material/'.$rows[$count]['nid'].'/'.$rows[$count]['title'];*/
      	$nid_penerimaan = $rows[$count]['nid'];
      	?>
      	<td align="center"><img class="detail_icon" onclick="see_details(this.parentNode.parentNode,this,'<?php print $nid_penerimaan; ?>')" src="<?php print base_path(); ?>sites/all/libraries/datatables/images/details_open.png" style="cursor:pointer;">	
      	<?php 
      		$detailpenerimaan = views_embed_view("view_detail_penerimaan_material","default",$nid_penerimaan);
	        $inputdetail = '<div id="detail_'.$nid_penerimaan.'" style="display:none">'.$detailpenerimaan.'</div>';
	        print $inputdetail;
	      ?>
      	</td>	
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
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_penerimaan; ?>')">PENERIMAAN MATERIAL</button>