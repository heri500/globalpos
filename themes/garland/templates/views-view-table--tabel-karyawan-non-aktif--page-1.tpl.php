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
  function view_detail_karyawan(nid){
  	address_togo = Drupal.settings.basePath + "hrd/viewdetailinfo?nid_karyawan="+ nid +"&destination=hrd/tabelkaryawannonaktif";
  	goto_address(address_togo);
  }
  
  function delete_proses(nid,nid_karyawan,nama,img_object){
  	var konfirmasi = confirm("Apakah anda benar-benar ingin menghapus permintaan penonaktifkan karyawan : "+ nama +"...????");
  	if (konfirmasi){
  		img_object.removeAttr("onclick");
  		img_object.unbind("click");
  		img_object.click(function(){
  			non_aktifkan_karyawan(nid_karyawan,nama,$(this));
  		});
  		var request = new Object;
			request.nid_proses = nid;
			submitaddress = Drupal.settings.basePath + "hrd/deleteprosesnonaktif";
			$.ajax({ 
				type: "POST",
				url: submitaddress,
				data: request, 
				cache: false, 
				success: function(data){
					alert(data);
					img_object.attr("src",Drupal.settings.basePath+"misc/media/images/forbidden.png");
  				img_object.attr("title","Non aktifkan karyawan");
				}
			});
  	}
  }
  $(document).ready(function(){
	 	oTable = $("#tabel_karyawan_indogas").dataTable( {
			"bJQueryUI": true,
			"bPaginate": true,
			"bLengthChange": true,
			"bFilter": true,
			"bInfo": true,
			"bAutoWidth": false,
			"bStateSave": true
		});
		$("button").button();
		$("#filter_form").click(function(){
			address_togo = Drupal.settings.basePath + "hrd/tabelkaryawannonaktif?cabang=" + $("#cabang").val();
			goto_address(address_togo);
		});
	})
 ','inline');
 	global $user;
	$superuser = cek_super_user();
	if ($superuser){
		if (isset($_GET['cabang'])){
			$selected_cabang = $_GET['cabang'];
		}else{
			$selected_cabang = 1;
		}
	}
	$datacabang = node_load($selected_cabang);
	$workshoptitle = strtoupper($datacabang->field_nama_lengkap[0]['value'].', '.$datacabang->title);
	if ($superuser){
 		$alamat_tambah_karyawan = base_path().'node/add/data-karyawan';	
 		$button = '<button id="filter_form" class="filter-button">Filter Data</button>';
 		$pilihan_cabang = create_cabang_selection($selected_cabang,0);
		$form_object[0]['label'] = 'Workshop';
		$form_object[0]['formobject'] = $pilihan_cabang.' '.$button;
		$formfilterdata = '<div style="float:left;width:100%;margin-bottom: 10px;">'.create_standard_indogas_form($form_object,$form_information).'<div style="float:left;width:10px;">&nbsp;</div></div>';
 	}else{
 		$view = views_get_view('get_posisi_user');
		$view->execute();
		if (count($view->result)){
			$id_posisi_user = $view->result[0]->node_data_field_user_id_field_cabang_id_nid;
			$alamat_tambah_karyawan = base_path().'node/add/data-karyawan/'.$id_posisi_user;
		}	
 	}
 	if ((!$id_posisi_user > 0) AND !$superuser){
 		drupal_goto('produksi');
 	}
 	$button2 = '<button id="save_non_aktif" class="filter-button">Nonaktifkan</button>';
 	$pilihan_alasan = create_alasan_nonaktif_selection('alasan_non_aktif',0,0);
 	$tanggal = '<input type="text" id="tanggal" name="tanggal" value="'.date("Y-m-d").'" style="margin-top:4px;width:100px;">';
	$form_object[0]['label'] = 'Alasan Non-aktif';
	$form_object[0]['formobject'] = $pilihan_alasan;
	$form_object[1]['label'] = 'Tanggal Non-aktif';
	$form_object[1]['formobject'] = $tanggal;
	$form_object[2]['label'] = $button2;
	$form_object[2]['formobject'] = '';
	$formalasan = create_standard_indogas_form($form_object,$form_information);
	if ($superuser){
		print $formfilterdata; 
	}
?>
<?php print '<h5 style="float:left;width:100%;font-weight: bold;margin: 15px 0 6px;">'.$workshoptitle.'</h5>'; ?>
<table id="tabel_karyawan_indogas" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<?php
    	if ($superuser){
    	?>
    	<th class="table_button">	
    	</th>
   	 	<?php } ?>
    	<th class="table_button">	
    	</th>
      <?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid' && trim($fields[$field]) <> 'status'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
      <th class="table_button">	
      	Start Date
    	</th>
      <th class="table_button">	
      	Resign Date
    	</th>
    	<th class="table_button">	
      	Alasan Non Aktif
    	</th>
      <th class="table_button">	
      	&nbsp;
    	</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
      		$nid_karyawan = $rows[$count]['nid'];
      		$karyawanDate = getKaryawanStartDateResignDate($nid_karyawan);
      		$karyawanDate['alasan_non_aktif'] = !empty($karyawanDate['alasan_non_aktif']) ? $karyawanDate['alasan_non_aktif'] : '-';
      		$status = $rows[$count]['status'];
      		$nama_karyawan = $rows[$count]['title'];
      		$alamatdelete = base_path().'node/'.$nid_karyawan.'/delete';
      		if ($status == 'Yes'){
      			//$imagestatus = '<img title="Status Karyawan: Aktif" src="'.base_path().'misc/media/images/check.png" style="margin-left: 10px;">';	
      			$imagestatus = '<b>[A]</b>';
      		}else{
      			//$imagestatus = '<img title="Status Karyawan: Belum/Non Aktif" src="'.base_path().'misc/media/images/details_close.png" style="margin-left: 10px;">';
      			$imagestatus = '<b>[N]</b>';
      		}
      	?>
      	<?php
	    	if ($superuser){
	    	?>
        <td align="center"><img src="<?php print base_path().'misc/media/images/del.ico'; ?>" onclick="goto_address('<?php print $alamatdelete; ?>')" width="20" title="Klik untuk menghapus data karyawan ini" class="icon_button"></td>
      	<?php } ?>
        <td align="center"><img src="<?php print base_path().'misc/media/images/forward_enabled.ico'; ?>" onclick="view_detail_karyawan('<?php print $rows[$count]['nid']; ?>')" width="20" title="Klik untuk melihat detail data karyawan ini" class="icon_button"></td>
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid' && trim($fields[$field]) <> 'status'){ ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php } ?>
        <?php endforeach; ?>
        <td class="center">	
      		<?php print $karyawanDate['start']; ?>
    		</td>
    		<td class="center">	
      		<?php print $karyawanDate['end']; ?>
    		</td>
    		<td class="center">	
      		<?php print $karyawanDate['alasan_non_aktif']; ?>
    		</td>
        <td class="center">	
      		<?php print $imagestatus; ?>
    		</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>