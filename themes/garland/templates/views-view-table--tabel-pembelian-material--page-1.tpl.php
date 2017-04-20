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
 $superuser = cek_super_user();
 if ($superuser){
 	$alamat_tambah_pembelian = base_path().'inventory/pembelianmaterial';	
 }
?>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_pembelian; ?>')">PEMBELIAN MATERIAL</button>
<table id="tabel_pembelian_material" class="<?php print $class; ?>">
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
      <th class="table_button">	
    	</th>
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
      	$nid_pembelian = $rows[$count]['nid'];
      	$status_pembelian = $rows[$count]['field_status_po_value'];
      	/*$alamatedit = base_path().'node/'.$rows[$count]['nid'].'/edit';
      	$alamatdetailpembelian = base_path().'node/add/detail-pembelian-material/'.$rows[$count]['nid'].'/'.$rows[$count]['title'];*/
      	?>
      	<td align="center"><img class="detail_icon" onclick="see_details(this.parentNode.parentNode,this,'<?php print $nid_pembelian; ?>')" src="<?php print base_path(); ?>sites/all/libraries/datatables/images/details_open.png" style="cursor:pointer;">	
      	<?php 
      		$detailpembelian = views_embed_view("view_detail_pembelian","default",$nid_pembelian);
	        $inputdetail = '<div id="detail_'.$nid_pembelian.'" style="display:none">'.$detailpembelian.'</div>';
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
        <?php
        if (trim($status_pembelian) == 'Dalam Proses'){
        	$edit_pembelian = '<img src="'.base_path().'misc/media/images/edit.ico" width="20" onclick="editpembelian(\''.$nid_pembelian.'\')" style="cursor:pointer">';
        }else{
        	$edit_pembelian = '<img src="'.base_path().'misc/media/images/checks2.png">';
        }
        ?>
        <td align="center"><?php print $edit_pembelian; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_pembelian; ?>')">PEMBELIAN MATERIAL</button>