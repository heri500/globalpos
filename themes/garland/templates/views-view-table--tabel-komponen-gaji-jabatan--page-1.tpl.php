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
 _add_block_ui_plugins();
 drupal_add_js('
  function goto_address(address_to_go){
  	window.location = address_to_go;
  }
  function edit_komponen(nid){
  	alamat_edit = Drupal.settings.basePath + "node/"+ nid +"/edit";
  	goto_address(alamat_edit);
  }
  function hapus_komponen_jabatan_karyawan(nidkomponen, nidstatusikatan, nidworkshop){
  	var konfirmasi = confirm("Yakin ingin menghapus komponen gaji seluruh karyawan dengan status ini....??");
  	if (konfirmasi){
	  	$("#node-form").block({ message: "<p style=\"color: 808080;padding: .2em;font-size: 18px;\">Deleting Komponen Gaji Karyawan...<img src=\"misc/media/images/loading.gif\"></p>",css: { border: "3px solid #a00" } });
	  	alamathapus = Drupal.settings.basePath + "hrd/deletekomponengajikaryawanbystatus";
	  	var request = new Object;
	  	request.nidkomponen = nidkomponen;
	  	request.nidstatusikatan = nidstatusikatan;
	  	request.nidworkshop = nidworkshop;
	  	$.ajax({ 
				type: "POST",
				url: alamathapus,
				data: request, 
				cache: false, 
				success: function(data){
					console.dir(data);
					$("#node-form").unblock();
				}
			});	
		}
 	}
  $(document).ready(function(){
	 	oTable = $("#tabelkomponengaji").dataTable( {
			"bJQueryUI": true,
			"bPaginate": true,
			"bLengthChange": true,
			"bFilter": true,
			"bInfo": true,
			"bStateSave": true,
			"bAutoWidth": false,
			"aoColumnDefs": [
	      { "bSortable": false, "aTargets": [ 0, 5, 6, 7 ] }
	    ]
		});
		$("#node-form").css("float","left");
	})
 ','inline');
 //dpm($view->result);
?>
<table id="tabelkomponengaji" class="<?php print $class; ?> display">
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
      <?php
      $array_status_ikatan_kerja = create_array_from_view('array_status_ikatan_kerja');
      for ($i = 0;$i < count($array_status_ikatan_kerja);$i++){
      	$pecahstatus = explode(' ',$array_status_ikatan_kerja[$i]->node_title);
      	$judulstatus = '';
      	for($j = 0;$j < count($pecahstatus);$j++){
      		$judulstatus .= substr($pecahstatus[$j],0,1);
      	}
      	print '<th class="table_button">'.$judulstatus.'</th>';
      }
      ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <?php
      $nid_komponen = $rows[$count]['nid'];
      $nid_workshop = $rows[$count]['field_cabang_ref_nid'];
      if (!$nid_workshop){
      	$nid_workshop = 0;
      }
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
	      		$image_to_edit = '<img src="'.base_path().'misc/media/images/edit.ico" width="20" onclick="edit_komponen(\''.$nid_komponen.'\')">';
	        ?>
	        <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $image_to_edit; ?>
          </td>
	        <?php
	      	}
	        ?>
        <?php endforeach; ?>
        <?php
	      $array_status_ikatan_kerja = create_array_from_view('array_status_ikatan_kerja');
	      $nid_komponen_jabatan = $view->result[$count]->node_data_field_komponen_gaji_ref_field_komponen_gaji_ref_nid;
	      for ($i = 0;$i < count($array_status_ikatan_kerja);$i++){
	      	$title = 'Klik untuk delete semua komponen gaji '.$row['field_komponen_gaji_ref_nid'].' untuk karyawan berstatus '.$array_status_ikatan_kerja[$i]->node_title;
	      	$icondelete = '<img onclick="hapus_komponen_jabatan_karyawan('.$nid_komponen_jabatan.','.$array_status_ikatan_kerja[$i]->nid.','.$nid_workshop.')" src="'.base_path().'sites/all/libraries/images/delete.ico" width="20" title="'.$title.'">';
	      	print '<td>'.$icondelete.'</td>';
	      }
	      ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>