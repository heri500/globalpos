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
 		$alamat_tambah_spp = base_path().'node/add/spp';	
 	}else{
 		$view = views_get_view('get_posisi_user');
		$view->execute();
		if (count($view->result)){
			$id_posisi_user = $view->result[0]->node_data_field_user_id_field_cabang_id_nid;
		}	
 	}
?>
<?php if ($superuser){ ?>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_spp; ?>')">FORM SPP</button>
<?php } ?>
<table id="tabel_spp" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th style="width: 20px;">	
    	</th>
    	<th style="width: 20px;">	
    	</th>
    	<th style="width: 20px;">	
    	</th>
      <?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
      <th style="width: 80px;">
      	Periode
      </th>
      <th style="width: 80px;">
      	s/d
      </th>
      <th style="width: 20px;">
      </th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
      	$alamatedit = base_path().'node/'.$rows[$count]['nid'].'/edit';
      	$alamatdetailspp = base_path().'node/add/detail-spp/'.$rows[$count]['nid'];
      	$node_spp = node_load($rows[$count]['nid']);
      	//dpm($node_spp);
      	$namafile = $node_spp->files[2]->filepath;
      	if ($namafile){
      		$spp_attachment = '<a href="'.base_path().$namafile.'" target="_blank"><img src="'.base_path().'misc/media/images/form_red.png"></a>';
      	}else{
      		$spp_attachment = "<b>-</b>";
      	}
      	$detail_spp = '<div id="detail_spp_'.$node_spp->nid.'" style="display: none;"><div style="width: 400px;">'.views_embed_view("view_detail_spp","default",$node_spp->nid).'</div></div>';
      	$detail_spbe_spp = '<div id="detail_spbe_spp_'.$node_spp->nid.'" style="display: none;"><div style="width: 500px;">'.views_embed_view("view_detail_spbe_spp","default",$node_spp->nid).'</div></div>';
      	?>
      	<td align="center"><img onclick="edit_spp('<?php print $rows[$count]['nid']; ?>')" src="<?php print base_path().'misc/media/images/edit.ico'; ?>" width="20" title="Klik untuk mengubah spp" class="icon_button"></td>
      	<td align="center"><img onclick="view_detail_spbe_spp(this.parentNode.parentNode,this,'<?php print $rows[$count]['nid']; ?>')" src="<?php print base_path().'misc/media/images/document.ico'; ?>" width="20" title="Klik untuk melihat spbe terkait spp" class="icon_button"><?php print $detail_spbe_spp; ?></td>
      	<td align="center"><img onclick="view_detail_spp(this.parentNode.parentNode,this,'<?php print $rows[$count]['nid']; ?>')" src="<?php print base_path().'misc/media/images/details_open.png'; ?>" title="Klik untuk melihat uraian pekerjaan spp" class="icon_button"><?php print $detail_spp; ?></td>
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        	<td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php } ?>
        <?php endforeach; ?>
        <td align="center"><?php print substr($node_spp->field_awal_periode[0]['value'],0,10); ?></td>
        <td align="center"><?php print substr($node_spp->field_akhir_periode[0]['value'],0,10); ?></td>
        <td><?php print $spp_attachment; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php if ($superuser){ ?>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_spp; ?>')">FORM SPP</button>
<?php } ?>