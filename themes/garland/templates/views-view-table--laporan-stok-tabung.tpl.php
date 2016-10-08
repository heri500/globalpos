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
 	function fnFormatDetails ( oTable, nTr , nidlaporan)
	{
		var detaillaporan = $("#detail_"+ nidlaporan).html();
		sOut = detaillaporan;
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
  function goto_address(address_to_go){
  	window.location = address_to_go;
  }
 	$(document).ready(function(){
	 	oTable = $("#tabel_laporan_stok").dataTable( {
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
 		$alamat_tambah_stok = base_path().'node/add/stok-tabung';	
 	}else{
 		$view = views_get_view('get_posisi_user');
		$view->execute();
		if (count($view->result)){
			$id_posisi_user = $view->result[0]->node_data_field_user_id_field_cabang_id_nid;
			$alamat_tambah_stok = base_path().'node/add/stok-tabung/'.$id_posisi_user;
		}	
 	}
 	if ((arg(2) <> $id_posisi_user) AND !$superuser){
 		drupal_goto('produksi');
 	}
 	//dpm($rows);
?>
<button class="add_data" onclick="goto_address('<?php print $alamat_tambah_stok; ?>')">BUAT LAPORAN STOK</button>
<table id="tabel_laporan_stok" class="<?php print $class; ?>">
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
        <?php
      	}
      	?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
      		$nid_laporan = $rows[$count]['nid'];
				?>
      	<td align="center">
      		<img class="detail_icon" onclick="see_details(this.parentNode.parentNode,this,'<?php print $nid_laporan; ?>')" src="<?php print base_path(); ?>sites/all/libraries/datatables/images/details_open.png" style="cursor:pointer;">	
      		<?php 
      			$detaillaporan = views_embed_view("tabel_detail_laporan_stok","default",$nid_laporan);
	        	$inputdetail = '<div id="detail_'.$nid_laporan.'" style="display:none">'.$detaillaporan.'</div>';
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
<button onclick="goto_address('<?php print $alamat_tambah_stok; ?>')" class="add_data">BUAT LAPORAN STOK</button>