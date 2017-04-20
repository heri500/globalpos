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
  var oTable;
  var mainpath = "'.base_path().'";
  function formpembelian(nidcabang,nidpengajuan){
  	addpembelianaddress = mainpath + "inventory/pembelianmaterial?nid_cabang="+ nidcabang +"&nid_pengajuan="+ nidpengajuan;
  	goto_address(addpembelianaddress);
  }
  function goto_address(address_to_go){
  	window.location = address_to_go;
  }
  function fnFormatDetails ( oTable, nTr , nidpengajuan)
	{
		var detailpemakaian = $("#detail_"+ nidpengajuan).html();
		sOut = detailpemakaian;
		return sOut;
	}
	function see_details(nTr,iconbutton,nid){
		if ( iconbutton.src.match("details_close") ){
			/* This row is already open - close it */
			iconbutton.src = mainpath +"sites/all/libraries/datatables/images/details_open.png";
			oTable.fnClose( nTr );
		}else{
			/* Open this row */
			iconbutton.src = mainpath +"sites/all/libraries/datatables/images/details_close.png";
			oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr, nid), "details" );
		}
	}
 	$(document).ready(function(){
	 	oTable = $("#tabel_pengajuan_pembelian").dataTable( {
			"bJQueryUI": true,
			"bPaginate": true,
			"sPaginationType": "full_numbers",
			"bLengthChange": true,
			"bFilter": true,
			"bSort": false,
			"bInfo": true,
			"bAutoWidth": false,
			"bStateSave": true
		});
		$("button,#edit-submit-tabel-pengajuan-pembelian").button();
		$("#edit-field-status-pesanan-value-many-to-one").css("width","150px");
		$(".views-widget-filter-field_status_pesanan_value_many_to_one, .views-exposed-widget").css("width","270px");
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
 		$alamat_tambah_pemakaian = base_path().'node/add/pengajuan-pembelian';	
 	}else{
 		$view = views_get_view('get_posisi_user');
		$view->execute();
		if (count($view->result)){
			$id_posisi_user = $view->result[0]->node_data_field_user_id_field_cabang_id_nid;
			$alamat_tambah_pemakaian = base_path().'node/add/pengajuan-pembelian/'.$id_posisi_user;
		}	
 	}
 	if ((arg(2) <> $id_posisi_user) AND !$superuser){
 		drupal_goto('inventory');
 	}
 	//dpm($rows);
?>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_pemakaian; ?>')">PENGAJUAN PEMBELIAN</button>
<table id="tabel_pengajuan_pembelian" class="<?php print $class; ?>">
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
      <th>
      	No. PO
      </th>
      <?php
      	if ($superuser){
      ?>
      <th class="table_button">	
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
      	/*$view = views_get_view('get_posisi_pemakaian');
      	$view->set_arguments(array($rows[$count]['nid']));
				$view->execute();
				if (count($view->result)){
					$id_posisi_user_berdasarkan_pemakaian = $view->result[0]->node_data_field_cabang_ref_field_cabang_ref_nid;
      		$alamattambahdetail = base_path().'node/add/data-tabung-pemakaian/'.$id_posisi_user_berdasarkan_stok.'/'.$rows[$count]['nid'];
      	}*/
      	//$alamatedit = base_path().'node/'.$rows[$count]['nid'].'/edit';
      	$nid_pengajuan = $rows[$count]['nid'];
      	$node_pengajuan = node_load($nid_pengajuan);
      	$nid_cabang = $node_pengajuan->field_cabang_ref[0]['nid'];
      	if ($node_pengajuan->field_status_pesanan[0]['value'] != 'Complete' && $node_pengajuan->field_status_pesanan[0]['value'] != 'Diterima Sebagian'){
      		$tombolbeli = '<img style="cursor:pointer;" title="Klik untuk melakukan pembelian dari pengajuan ini" src="'.base_path().'misc/media/images/document_plain_new.png" onclick="formpembelian(\''.$nid_cabang.'\',\''.$nid_pengajuan.'\');">';	
      	}else{
      		$tombolbeli = '<img src="'.base_path().'misc/media/images/check.png">';		
      	}
      	//$alamatdetailpemakaian = base_path().'node/add/detail-pengajuan-pembelian/'.$rows[$count]['nid'].'/'.$rows[$count]['title'];
      	?>
      	<td align="center"><img class="detail_icon" onclick="see_details(this.parentNode.parentNode,this,'<?php print $nid_pengajuan; ?>')" src="<?php print base_path(); ?>sites/all/libraries/datatables/images/details_open.png" style="cursor:pointer;">	
      	<?php 
      		$detailpengajuan = views_embed_view("view_detail_pengajuan_pembelian","default",$nid_pengajuan);
	        $inputdetail = '<div id="detail_'.$nid_pengajuan.'" style="display:none">'.$detailpengajuan.'</div>';
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
        	$name = 'list_po_by_pengajuan';
        	$display_id = 'default';
        	unset($args);
        	$args = array($nid_pengajuan);
	        $list_po_by_pengajuan = views_get_view($name);
					$list_po_by_pengajuan->set_arguments($args);
					$list_po_by_pengajuan->set_display($display_id);
					$list_po_by_pengajuan->pre_execute();
				  $list_po_by_pengajuan->execute();
				  if (count($list_po_by_pengajuan->result)){
				  	$no_po_array = array(); 
				  	for ($j = 0;$j < count($list_po_by_pengajuan->result);$j++){
				  		$no_po_array[] = $list_po_by_pengajuan->result[$j]->node_title;
				  	}
				  	if (count($no_po_array) > 0){
				  		$no_po_list = implode("<br>",$no_po_array);
				  	}else{
				  		$no_po_list = '-';
				  	}
				  }else{
				  	$no_po_list = '-';
				  }
        ?>
        <td>
        	<?php print $no_po_list; ?>
        </td>
        <?php
      		if ($superuser){
	      ?>
	      	<td align="center">
	      		<?php print $tombolbeli; ?>	
	    		</td>
	      <?php		
	      	}
	      ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_pemakaian; ?>')">PENGAJUAN PEMBELIAN</button>