<?php
/**
 * @file views-view.tpl.php
 * Main view template
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 * - $admin_links: A rendered list of administrative links
 * - $admin_links_raw: A list of administrative links suitable for theme('links')
 *
 * @ingroup views_templates
 */
 $datatables = true;
 _include_latest_jquery_plugins('jquery-1.7.2.js');
 _include_jquery_plugins($datatables);
 drupal_add_js('
  function goto_address(address_to_go){
  	window.location = address_to_go;
  }
  function view_detail_karyawan(nid){
  	address_togo = Drupal.settings.basePath + "hrd/viewdetailinfo?nid_karyawan="+ nid;
  	goto_address(address_togo);
  }
  function non_aktifkan_karyawan(nid,nama,img_object){
  	var konfirmasi = confirm("Apakah anda benar-benar ingin menonaktifkan karyawan : "+ nama +"...????");
  	if (konfirmasi){
  		$("#dialogalasan").dialog("open");	
  		$("#save_non_aktif").unbind("click");
  		$("#save_non_aktif").click(function(){
  			img_object.removeAttr("onclick");
  			img_object.unbind("click");
  			var request = new Object;
				request.nid_karyawan = nid;
				request.alasannonaktif = $("#alasan_non_aktif").val();
				request.tglnonaktif = $("#tanggal").val();
				submitaddress = Drupal.settings.basePath + "hrd/prosesnonaktif";
				$.ajax({ 
					type: "POST",
					url: submitaddress,
					data: request, 
					cache: false, 
					success: function(data){
						alert("Proses penonaktifan karyawan berhasil...!!");
						nid_proses = parseInt(data);
						$("#dialogalasan").dialog("close");
						img_object.attr("src",Drupal.settings.basePath+"misc/media/images/warning.png");
  					img_object.attr("title","Karyawan ini dalam proses menunggu approval penonaktifan...!, Klik untuk membatalkan");
  					img_object.click(function(){
  						delete_proses(nid_proses,nid,nama,$(this));
  					});
					}
				});
  		});
  	}
  }
  function skors_karyawan(nid,nama,nid_proses){
  	if (nid_proses == "0"){
  		window.location = Drupal.settings.basePath + "hrd/formskorsing?nid_karyawan="+ nid;
  	}else{
  		window.location = Drupal.settings.basePath + "hrd/formskorsing?nid_karyawan="+ nid +"&nid_proses_skorsing="+ nid_proses;
  	}
  }
  function sanksi_karyawan(nid,nama,nid_proses){
  	if (nid_proses == "0"){
  		window.location = Drupal.settings.basePath + "hrd/formsanksi?nid_karyawan="+ nid;
  	}else{
  		window.location = Drupal.settings.basePath + "hrd/formsanksi?nid_karyawan="+ nid +"&nid_proses_sanksi="+ nid_proses;
  	}
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
			"sPaginationType": "full_numbers",
			"bLengthChange": true,
			"bFilter": true,
			"bInfo": true,
			"bAutoWidth": false,
			"bStateSave": true,
			"aoColumnDefs": [
		  	{ "bSortable": false, "aTargets": [ 0,1,7,8,9,10,11,12,13 ] }
		  ]
		});
		$("button").button();
		$("#filter_form").click(function(){
			address_togo = Drupal.settings.basePath + "hrd/tabelkaryawan?cabang=" + $("#cabang").val();
			goto_address(address_togo);
		});
		$("#dialogalasan").dialog({
			height: 170,
			width: 515,
			modal: true,
			resizable: false,
			autoOpen: false
		});
		$("#tanggal").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd\' }).attr("readonly","readonly");
	})
 ','inline');
 	global $user;
	$superuser = cek_super_user();
	if ($superuser){
		if (isset($_GET['cabang'])){
			$selected_cabang = $_GET['cabang'];
		}else{
			$selected_cabang = '-';
		}
	}
	$formlegend = create_tabel_karyawan_icon_legend();
 	if ($superuser){
 		$alamat_tambah_karyawan = base_path().'node/add/data-karyawan';	
 		$button = '<button id="filter_form" class="filter-button">Filter Data</button>';
 		$pilihan_cabang = create_cabang_selection($selected_cabang,0);
		$form_object[0]['label'] = 'Workshop';
		$form_object[0]['formobject'] = $pilihan_cabang.' '.$button;
		$formfilterdata = '<div style="float:left;width:100%;margin-bottom: 10px;">'.create_standard_indogas_form($form_object,$form_information).'<div style="float:left;width:10px;">&nbsp;</div>'.$formlegend.'</div>';
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
	$tomboltambah = '<div style="float:left;width:100%;"><button onclick="goto_address('.$alamat_tambah_karyawan.')" class="add_data">TAMBAH KARYAWAN</button></div>';
?>
<div class="<?php print $classes; ?>">
  <?php if ($admin_links): ?>
    <div class="views-admin-links views-hide">
      <?php print $admin_links; ?>
    </div>
  <?php endif; ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>
	<?php 
		if ($superuser){
			print $formfilterdata; 
		}else{
			print $formlegend;	
		}
	?>
  <?php if ($exposed): ?>
    <div class="view-filters">
      <?php print $exposed; ?>
    </div>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
      <div id="dialogalasan" title="Alasan Non Aktif">
				<?php print $formalasan; ?>
			</div>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div> <?php /* class view */ ?>