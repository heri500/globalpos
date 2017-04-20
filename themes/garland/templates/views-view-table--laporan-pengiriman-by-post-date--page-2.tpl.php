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
?>
<table id="tabel_pengiriman" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th class="table_button">	
    	</th>
    	<?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        <?php
        if (trim($fields[$field]) <> 'status'){
        ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php
      	}else{
        ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>" style="width:25px;">
          <?php print $label; ?>
        </th>
        <?php } ?>
        <?php } ?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
    	<?php
  			$nid_kirim = $rows[$count]['nid'];
  		?>
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
      	$alamatdetailkirim = base_path().'node/add/detail-pengiriman/'.$rows[$count]['nid'].'/'.$rows[$count]['title'];
      	?>
      	<td align="center">
      		<img class="detail_icon" onclick="see_details(this.parentNode.parentNode,this,'<?php print $nid_kirim; ?>')" src="<?php print base_path(); ?>sites/all/libraries/datatables/images/details_open.png" style="cursor:pointer;">	
      		<?php 
      			$detailpengiriman = views_embed_view("tabel_detail_pengiriman","default",$nid_kirim);
	        	$inputdetail = '<div id="detail_'.$nid_kirim.'" style="display:none">'.$detailpengiriman.'</div>';
	        	print $inputdetail;
	        ?>	
      	</td>
      	<?php /*<td align="center"><img onclick="goto_address('<?php print $alamatedit; ?>')" src="<?php print base_path().'misc/media/images/edit.ico'; ?>" width="20" title="Klik untuk mengubah pengiriman" class="icon_button"></td>*/ ?>
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid'){ ?>
          <?php
          if (trim($fields[$field]) <> 'status'){
          ?>
          <?php
        		if (trim($fields[$field]) <> 'field-armada-ref-value'){
        	?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php 
        		}else{
        		$node_armada = node_load($content);
        	?>
        	<td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $node_armada->field_no_plat[0]['value']; ?>
          </td>
        	<?php		
        		}
          }else{
        		if ($content == 'Yes'){
        			$tampil = '<img title="Pengiriman Sudah di Approve" src="'.base_path().'misc/media/images/check.png">';		
        		}else{
        			$tampil = '<img title="Pengiriman Belum di Approve" src="'.base_path().'misc/media/images/warning.png">';
        		}
          ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>" align="center">
            <?php print $tampil; ?>
          </td>
          <?php } ?>
          <?php } ?>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>