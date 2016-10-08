<?php
// $Id: views-view.tpl.php,v 1.13.2.2 2010/03/25 20:25:28 merlinofchaos Exp $
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
 	_include_jquery_plugins($datatables);
	drupal_add_js('misc/media/js/combo_autocomplete2.js');
	_add_block_ui_plugins();
	$array_latest_tutup_buku = create_array_latest_tutup_buku_bulanan();
	php_to_drupal_settings(array('latesttutupbuku' => $array_latest_tutup_buku));
	$superuser = cek_super_user();
	php_to_drupal_settings(array('superuser' => $superuser));
	if (!$superuser){
		$workshop_nid = cek_posisi_user();
		php_to_drupal_settings(array('posisiuser' => $workshop_nid));	
	}
	drupal_add_js('
  var oTable;
  var mainpath = Drupal.settings.basePath;
  var superuser = Drupal.settings.indogas.superuser;
  function fnFormatDetails ( oTable, nTr , nidkirim)
	{
		var detailkirim = $("#detail_"+ nidkirim).html();
		sOut = detailkirim;
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
	var status_open = "close";
  $(document).ready(function(){
	 	oTable = $("#tabel_pengiriman").dataTable( {
			"bJQueryUI": true,
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": true,
			"bAutoWidth": false,
			"bStateSave": true,
			"bSort": false
		});
		$("button").button();
		$("#view_all_detail").click(function(){
			if (status_open == "close"){
				$(".detail_icon").click();
				img_src = mainpath +"sites/all/libraries/datatables/images/details_close.png";
				$("#view_all_detail").attr("src",img_src);
				$("#keterangan_icon").text("Klik untuk menutup semua detail pengiriman");
				status_open = "open";
			}else if(status_open == "open"){
				$(".detail_icon").click();
				img_src = mainpath +"sites/all/libraries/datatables/images/details_open.png";
				$("#view_all_detail").attr("src",img_src);
				$("#keterangan_icon").text("Klik untuk melihat semua detail pengiriman");
				status_open = "close";
			}
		});
		$(".approve_data").click(function(){
			if ($("#bulan").val() != 0 && $("#tahun").val() != ""){
				$("#block_approve").block({ message: \'<p style=\"color: 808080;padding: .2em;font-size: 18px;\">Approving...<img src=\"misc/media/images/loading.gif\"></p>\',css: { border: \'3px solid #a00\' } });
				var sData = $("input", oTable.fnGetNodes()).serialize();
				var request = new Object;
				request.datanid = sData;
				request.cabang_nid = $("#cabang").val();
				request.bulan = $("#bulan").val();
				request.tahun  = $("#tahun").val();
				submitaddress = mainpath + "dopilihpengiriman";
				$.ajax({ 
					type: "POST",
					url: submitaddress,
					data: request, 
					cache: false, 
					success: function(data){
						$("#block_approve").unblock();
						window.location = mainpath + "tagihanpengiriman?pesan=Approve data pengiriman berhasil";
					}
				});	
			}else{
				alert("Mohon pilih bulan dan tahun penagihan sebelum approve...!!!");
				$("#tahun").css("border-color","red");
				$("#bulan").css("border-color","red");
				$("#bulan").focus();
			}	
		});
		$(".submitted").hide();
   	$("#edit-created-max-wrapper label").html("s.d.");
   	$("#edit-created-max-wrapper label").css("width","auto");
   	$("#edit-created-max-wrapper label").css("margin-right","8px");
		$("#edit-submit-laporan-pengiriman").text("Filter Data");
		$("#edit-submit-laporan-pengiriman").css("font-size","8.4pt");
		$("#edit-submit-laporan-pengiriman").css("margin-top","0");
		$("#edit-submit-laporan-pengiriman").button();
		/*$("#edit-created-min").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 00:00\' }).attr("readonly","readonly");
		$("#edit-created-max").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 23:59\' }).attr("readonly","readonly");*/
		if (superuser){
			$("#cabang").change(function(){
				var latest_tutup_buku_date = Drupal.settings.indogas.latesttutupbuku[$("#cabang").val()][0]["node_created_minute"];
				if (latest_tutup_buku_date != 0){
					latest_tutup_buku = latest_tutup_buku_date;
					tahun = latest_tutup_buku.substr(0,4);
					bulan = latest_tutup_buku.substr(4,2);
					$("#edit-created-min,#edit-created-max").datepicker( "option", "minDate", new Date(tahun, bulan));
				}else{
					$("#edit-created-min,#edit-created-max").datepicker( "option", "minDate", new Date(2011, 11));
				}
			});
			var latest_tutup_buku_date = Drupal.settings.indogas.latesttutupbuku[$("#cabang").val()][0]["node_created_minute"];
			if (latest_tutup_buku_date != 0){
				latest_tutup_buku = latest_tutup_buku_date;
				tahun = latest_tutup_buku.substr(0,4);
				bulan = latest_tutup_buku.substr(4,2);
				$("#edit-created-min").datepicker({ 
					changeMonth: true,
					changeYear: true,
					dateFormat: \'yy-mm-dd 00:00\',
					minDate: new Date(tahun, bulan)
				}).attr("readonly","readonly");
				$("#edit-created-max").datepicker({ 
					changeMonth: true,
					changeYear: true,
					dateFormat: \'yy-mm-dd 23:59\',
					minDate: new Date(tahun, bulan)
				}).attr("readonly","readonly");
			}else{
				$("#edit-created-min").datepicker({ 
					changeMonth: true,
					changeYear: true,
					dateFormat: \'yy-mm-dd 00:00\',
					minDate: new Date(2011, 11)
				}).attr("readonly","readonly");
				$("#edit-created-max").datepicker({ 
					changeMonth: true,
					changeYear: true,
					dateFormat: \'yy-mm-dd 23:59\',
					minDate: new Date(2011, 11)
				}).attr("readonly","readonly");
			}
		}else{
			var latest_tutup_buku_date = Drupal.settings.indogas.latesttutupbuku[Drupal.settings.indogas.posisiuser][0]["node_created_minute"];
			if (latest_tutup_buku_date != 0){
				latest_tutup_buku = latest_tutup_buku_date;
				tahun = latest_tutup_buku.substr(0,4);
				bulan = latest_tutup_buku.substr(4,2);
				$("#edit-created-min").datepicker({ 
					changeMonth: true,
					changeYear: true,
					dateFormat: \'yy-mm-dd 00:00\',
					minDate: new Date(tahun, bulan)
				}).attr("readonly","readonly");
				$("#edit-created-max").datepicker({ 
					changeMonth: true,
					changeYear: true,
					dateFormat: \'yy-mm-dd 23:59\',
					minDate: new Date(tahun, bulan)
				}).attr("readonly","readonly");
			}else{
				$("#edit-created-min").datepicker({ 
					changeMonth: true,
					changeYear: true,
					dateFormat: \'yy-mm-dd 00:00\',
					minDate: new Date(2011, 11)
				}).attr("readonly","readonly");
				$("#edit-created-max").datepicker({ 
					changeMonth: true,
					changeYear: true,
					dateFormat: \'yy-mm-dd 23:59\',
					minDate: new Date(2011, 11)
				}).attr("readonly","readonly");
			}	
		}
		$("#views-exposed-form-laporan-pengiriman-page-2").css("margin-bottom","5px");
		$("#views-exposed-form-laporan-pengiriman-page-2").prepend($("#pilihancabang"));
		$("#views-exposed-form-laporan-pengiriman-page-2").append($("#bulanpenagihan"));
		$("#views-exposed-form-laporan-pengiriman-page-2").append($("#tahunpenagihan"));
		$("#bulan").change(function(){
			$("#bulanpenagihan").val($("#bulan").val());
		});
		$("#tahun").keyup(function(){
			$("#tahunpenagihan").val($("#tahun").val());
		});
		$("#edit-submit-laporan-pengiriman").parent().css("width","110px");
		$(".filter-button").button();
		$("#select_all").click(function(){
			$("#tabel_pengiriman tr td input[type=\"checkbox\"]").each(function(){
				if (!$(this).attr("checked")){
					$(this).attr("checked","checked");
				}
			});
		});
		$("#de_select_all").click(function(){
			$("#tabel_pengiriman tr td input[type=\"checkbox\"]").each(function(){
				if ($(this).attr("checked")){
					$(this).removeAttr("checked");
				}
			});
		});
	})
 ','inline');
  if (isset($_GET["pesan"])){
  	drupal_set_message($_GET["pesan"]);	
  }
 	global $user;
	$superuser = false;
 	foreach ($user->roles as $user_role){
 		if (trim($user_role) == 'Super User'){
 			$superuser = true;		
 		}
 	}
 	if ($superuser){
 		if (isset($_GET["idcabang"])){
 			$cabang_selected = $_GET["idcabang"];
 		}else{
 			$cabang_selected = 0;	
 		}
		$cabang_selection = '<select id="cabang" name="cabang" style="height: 22px;">';
		$cabang_rs = db_query("SELECT nid,title FROM node WHERE type='cabang_indogas' AND status='1'");
		while($cabang_data = db_fetch_object($cabang_rs)){
			if ($cabang_selected == 0){
				$cabang_selected = $cabang_data->nid;
				$selected = 'selected="selected"';
			}else{
				if ($cabang_data->nid == $cabang_selected){
					$selected = 'selected="selected"';
				}else{
					$selected = "";
				}
			}
			$cabang_selection .= '<option '.$selected.' value="'.$cabang_data->nid.'">'.$cabang_data->title.'</option>';	
		}	
		$cabang_selection .= '</select>';
	}else{
		$view = views_get_view('get_posisi_user');
 		$view->execute();
 		if (count($view->result)){
  		$cabang_selected = $view->result[0]->node_data_field_user_id_field_cabang_id_nid;
  	}else{
  		drupal_goto();	
  	}	
	}
	if ($_GET['cabang']){
		$nid_cabang_pilihan = $_GET['cabang'];
	}else{
		$nid_cabang_pilihan = 1;
	}
	$pilihan_tagihan = '<div style="float:left;width:100%;"><div id="form_jurnal_umum" style="width:490px;box-shadow: 2px 2px 4px #BBB;margin-bottom:10px;">';
	if (isset($_GET['bulanpenagihan']) && $_GET['bulanpenagihan'] != ''){
		$bulanpenagihan = $_GET['bulanpenagihan'];
	}else{
		$bulanpenagihan = 0;
	}
	if (isset($_GET['tahunpenagihan']) && $_GET['tahunpenagihan'] != ''){
		$tahunpenagihan = $_GET['tahunpenagihan'];
	}else{
		$tahunpenagihan = '';
	}
	$pilihanbulan = create_bulan_selection($bulanpenagihan,4,'bulan', true);
	$pilihan_tagihan .= '<div class="form_row"><label>Bulan Penagihan</label>'.$pilihanbulan.'  <label style="width:80px;">Tahun</label><input type="text" id="tahun" name="tahun" value="'.$tahunpenagihan.'" style="width:100px;"></div></div></div>';
	$approve_tagihan = '<button class="approve_data" style="margin:0;">APPROVE</button>';
	$approve_tagihan .= '<div class="warning" style="margin-top: 8px;">';
	$approve_tagihan .= '<i><p style="margin: 0 10px;">Klik Approve jika:</p>';
	$approve_tagihan .= '<ul><li>Bulan dan tahun Penagihan sudah ditentukan.</li>';
	$approve_tagihan .= '<li>Pastikan Pengiriman yang ditagih untuk bulan tersebut sudah terselesksi (tercentang) dan hilangkan tanda centang bila tidak termasuk penagihan bulan ini</li></ul></i>';
	$approve_tagihan .= '</div>';
	if ($superuser){
		$cabang_selection = '<div id="pilihancabang" style="float:left;"><label style="width: 80px;"><b>Workshop</b></label>'.create_cabang_selection($nid_cabang_pilihan).'</div>';
	}else{
		$cabang_selection = '';
	}
	$bulanpenagihan = '<input id="bulanpenagihan" name="bulanpenagihan" type="text"  value="'.$bulanpenagihan.'" style="display:none;">';
	$tahunpenagihan = '<input id="tahunpenagihan" name="tahunpenagihan" type="text"  value="'.$tahunpenagihan.'" style="display:none;">';
?>
<div id="block_approve">
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
  <?php print $pilihan_tagihan.$bulanpenagihan.$tahunpenagihan; ?>
  <div style="float:left;width: 100%;color: #444;"><b>Silahkan Pilih Pengiriman yang akan ditagihkan untuk bulan tersebut diatas:</b></div>
  <?php if ($exposed): ?>
    <div class="view-filters" style="width:100%;float:left;margin-top: 10px;">
      <?php print $exposed; ?>
      <?php print $cabang_selection; ?> 
    </div>
  <?php endif; ?>	
  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>
  
  <?php if ($rows): ?>
  	<div style="float:left;width: 100%;color: #444;margin-bottom: 8px;"><b>Bubuhkan tanda centang pada sudut kanan tabel untuk pengiriman yang di pilih dan hilangkan jika tidak dipilih</b></div>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
    <?php print $approve_tagihan; ?>
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

</div> 
</div><?php /* class view */ ?>