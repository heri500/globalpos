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
 	if (isset($_GET['bulan'])){
		$bulan = $_GET['bulan'];
	}else{
		$bulan = 12;
	}
	if (isset($_GET['tahun'])){
		$tahun = $_GET['tahun'];
	}else{
		$tahun = 2011;
	}
	if (isset($_GET['cabang'])){
		$workshop_nid = $_GET['cabang'];
	}else{
		$workshop_nid = 1;
	}
	$node_cabang = node_load($workshop_nid);
	if (isset($_GET['status'])){
		$selected_approved = $_GET['status'];
	}else{
		$selected_approved = 'All';
	}
	$namabulan = array_nama_bulan();
	$judul_halaman = 'Tabel Tagihan Workshop '.$node_cabang->title.' '.$namabulan[$bulan].' '.$tahun;
	drupal_set_title($judul_halaman);
	$filterdata = array('bulan' => $bulan, 'tahun' => $tahun, 'workshop' => $workshop_nid, 'statusapproval' => $selected_approved, 'cabanginfo' => $node_cabang);
	php_to_drupal_settings(array('filterdata' => $filterdata));
	$path = drupal_get_path('theme', 'garland');
	$form_style = $path.'/custom_css.css';
	drupal_add_css($form_style, 'theme', 'all', FALSE);
	$variables['styles'] = drupal_get_css();
	if (isset($_GET['pesan'])){
		drupal_get_messages('status');
		drupal_set_message(t('%title.', array('%title' => $_GET['pesan'])));	
	}
	drupal_add_css('misc/media/datatables/themes/redmond/jquery-ui-1.8.14.custom.css');
	drupal_add_js('misc/media/jquery-1.5.1.min.js');
	drupal_add_js('misc/media/jquery-ui-1.8.14.custom.min.js');
	drupal_add_js('misc/media/js/combo_autocomplete2.js');
	drupal_add_css('misc/media/datatables/css/demo_table_jui.css');
	drupal_add_js('misc/media/datatables/js/jquery.dataTables.min.js');
	_add_jeditable_plugins();
	drupal_add_js('misc/media/jquery.blockUI.js');
	drupal_add_js('
		var oTable;
		var oTable2;
		var oTable3;
		var oTable4;
		var array_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		function addCommas(nStr){
				nStr += "";
				x = nStr.split(",");
				x1 = x[0];
				x2 = x.length > 1 ? "," + x[1] : "";
				var rgx = /(\d+)(\d{3})/;
				while (rgx.test(x1)) {
					x1 = x1.replace(rgx, "$1" + "." + "$2");
				}
				return x1 + x2;
			}
		function view_detail_pekerjaan_data(nid){
			if (nid == 47){
				$("#block_penukaran_valve").toggle();
			}
		}
		function view_detail_tagihan_transportasi(){
			$("#block_tagihan_transportasi").toggle();
		}
		function getselectedrow(){
    	var selected_row = new Array;
    	$("#tabel_tagihan tr.row_selected").each(function(){
    		selected_row.push($(this).attr("id"));
    	});
    	return selected_row;
		}
		function getvalveselectedrow(){
    	var selected_row = "";
    	$("#tabel_penukaran_valve tr.row_selected").each(function(){
    		if (selected_row == ""){
    			selected_row = $(this).attr("id");
    		}else{
    			selected_row += "&"+ $(this).attr("id");
    		}
    	});
    	return selected_row;
		}
		function gettransportselectedrow(){
    	var selected_row = "";
    	$("#tabel_tagihan_transportasi tr.row_selected").each(function(){
    		if (selected_row == ""){
    			selected_row = $(this).attr("id");
    		}else{
    			selected_row += "&"+ $(this).attr("id");
    		}
    	});
    	return selected_row;
		}
		$(document).ready(function(){
			$("#tabel_tagihan tr,#tabel_penukaran_valve tr,#tabel_tagihan_transportasi tr").css("cursor","pointer");
			$("#tabel_tagihan tr,#tabel_penukaran_valve tr,#tabel_tagihan_transportasi tr").click( function() {
        $(this).toggleClass("row_selected");
    	});
	 		oTable = $("#tabel_tagihan").dataTable( {
				"bJQueryUI": true,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": true,
				"bInfo": true,
				"bAutoWidth": false,
				"bStateSave": true,
				"bSort": false
			});
			oTable2 = $("#summary_tagihan").dataTable( {
				"bJQueryUI": true,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": true,
				"bInfo": true,
				"bAutoWidth": false,
				"bStateSave": true,
				"bSort": false
			});
			$("#summary_tagihan_filter").css("width","100%");
			if (Drupal.settings.indogas.summaryapproved){
				gambar = "<img src=\""+ Drupal.settings.basePath +"sites/all/libraries/images/check.png\" style=\"margin-bottom: -4px;\" width=\"18\">";
				$("#summary_tagihan_filter").append("<div id=\"info-approved-summary\" style=\"float: left;width: auto;padding: 0 6px;font-weight: bold;color: yellow;\">Tagihan sudah di approve "+ gambar +"</div>");
			}else{
				$("#summary_tagihan_filter").append("<div id=\"info-approved-summary\" style=\"float: left;width: auto;padding: 0 6px;font-weight: bold;color: red;\">Tagihan belum di approve !!!</div>");
			}
			oTable3 = $("#tabel_penukaran_valve").dataTable( {
				"bJQueryUI": true,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": true,
				"bInfo": true,
				"bAutoWidth": false,
				"bStateSave": true,
				"bSort": false
			});
			oTable4 = $("#tabel_tagihan_transportasi").dataTable( {
				"bJQueryUI": true,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": true,
				"bInfo": true,
				"bAutoWidth": false,
				"bStateSave": true,
				"bSort": false
			});
			$("#tabel_penukaran_valve_wrapper").css("width","500px");
			$("#tabel_penukaran_valve_filter").before("<div class=\"title-at-toolbar\">PENUKARAN VALVE</div>");
			$("#tabel_tagihan_transportasi_filter").before("<div class=\"title-at-toolbar\">TAGIHAN TRANSPORTASI</div>");
			$("#block_penukaran_valve").hide();
			$("#tabel_tagihan_filter").before("<button id=\"approve_tagihan\" style=\"font-size: 12px;\" title=\"Klik untuk approve tagihan yang dipilih(di highligth)\">Approve Tagihan</button>");
			if (Drupal.settings.indogas.summaryapproved){
				$("#tabel_tagihan_filter").before("&nbsp;<button id=\"un_approve_tagihan\" class=\"un-approve-button\" style=\"font-size: 12px;\" title=\"Klik untuk un-approve tagihan yang dipilih(di highligth)\">Un-Approve Tagihan</button>");
			}
			$("#do_approve_tagihan").click(function(){
				$("#select_all").click();
				$("#approve_tagihan").click();
			});
			$("#do_unapprove_tagihan").button().click(function(){
			  var konfirmasi = confirm("Yakin ingin melakukan un-approve tagihan...?!!");
			  if (konfirmasi){
					$("#select_all").click();
					$("#un_approve_tagihan").click();
				}	
			});
			$("#un_approve_tagihan").button().click(function(){
				var request = new Object;
				request.datanidvalve = getvalveselectedrow();
				request.datanidtransport = gettransportselectedrow();
				request.datanid = getselectedrow();
				/*if (request.datanidvalve != ""){
					request.datanid += "&" + request.datanidvalve;
				}
				if (request.datanidtransport != ""){
					request.datanid += "&" + request.datanidtransport;
				}*/
				if (request.datanid != ""){
					$("#block_approve").hide();
					$("#block_penukaran_valve").hide();
					$("#block_tagihan_transportasi").hide();
					$("#block_summary_place_holder").show();
					$("#block_summary_place_holder").block({ message: \'<p style=\"color: 808080;padding: .2em;font-size: 18px;\">Un-Approving...<img src=\"misc/media/images/loading.gif\"></p>\',css: { border: \'3px solid #a00\' } });
					request.cabang_nid = Drupal.settings.indogas.filterdata["workshop"];
					request.bulan = Drupal.settings.indogas.filterdata["bulan"];
					request.tahun = Drupal.settings.indogas.filterdata["tahun"];
					submitaddress = Drupal.settings.basePath + "dounapprovetagihan";
					$.ajax({ 
						type: "POST",
						url: submitaddress,
						data: request, 
						cache: false, 
						success: function(data){
							$("#block_summary_place_holder").unblock();
							index_bulan = Drupal.settings.indogas.filterdata["bulan"] - 1;
							pesan = "Un-approve data pengiriman workshop "+ Drupal.settings.indogas.filterdata["cabanginfo"]["title"] +" bulan "+ array_bulan[index_bulan] +" "+ Drupal.settings.indogas.filterdata["tahun"] +" berhasil";
							//window.location = Drupal.settings.basePath + "tabeltagihan?pesan="+ pesan +"&cabang="+ Drupal.settings.indogas.filterdata["workshop"] +"&bulan="+ Drupal.settings.indogas.filterdata["bulan"] +"&tahun="+ Drupal.settings.indogas.filterdata["tahun"] +"&status="+ Drupal.settings.indogas.filterdata["statusapproval"];
						}
					});
				}else{
					alert("Mohon pilih tagihan yang ingin di Un-approve...!!!");
				}
			});
			$("#approve_tagihan").button().click(function(){
				var request = new Object;
				request.datanid = getselectedrow();
				request.datanidvalve = getvalveselectedrow();
				request.datanidtransport = gettransportselectedrow();
				console.dir(request.datanid);
				if (request.datanid.length > 0){
					$("#block_approve").hide();
					$("#block_penukaran_valve").hide();
					$("#block_tagihan_transportasi").hide();
					$("#block_summary_place_holder").show();
					$("#block_summary_place_holder").block({ message: \'<p style=\"color: 808080;padding: .2em;font-size: 18px;\">Approving...<img src=\"misc/media/images/loading.gif\"></p>\',css: { border: \'3px solid #a00\' } });
					request.cabang_nid = Drupal.settings.indogas.filterdata["workshop"];
					request.bulan = Drupal.settings.indogas.filterdata["bulan"];
					request.tahun = Drupal.settings.indogas.filterdata["tahun"];
					console.dir(request.datanid);
					//pecah_nid = request.datanid.split("&");
					submitaddress = Drupal.settings.basePath + "doapprovetagihan";
					$.ajax({ 
						type: "POST",
						url: submitaddress,
						data: request, 
						cache: false, 
						success: function(data){
							$("#block_summary_place_holder").unblock();
							index_bulan = Drupal.settings.indogas.filterdata["bulan"] - 1;
							pesan = "Approve data pengiriman workshop "+ Drupal.settings.indogas.filterdata["cabanginfo"]["title"] +" bulan "+ array_bulan[index_bulan] +" "+ Drupal.settings.indogas.filterdata["tahun"] +" berhasil";
							window.location = Drupal.settings.basePath + "tabeltagihan?pesan="+ pesan +"&cabang="+ Drupal.settings.indogas.filterdata["workshop"] +"&bulan="+ Drupal.settings.indogas.filterdata["bulan"] +"&tahun="+ Drupal.settings.indogas.filterdata["tahun"] +"&status="+ Drupal.settings.indogas.filterdata["statusapproval"];
						}
					});
				}else{
					alert("Mohon pilih tagihan yang ingin di approve...!!!");
				}
			});
			$("#tabel_tagihan_filter").css("margin-top","4px");
			$("#filter_tagihan").button().click(function(){
				window.location = Drupal.settings.basePath + "tabeltagihan?cabang="+ $("#cabang").val() +"&bulan="+ $("#bulan").val() +"&tahun="+ $("#tahun").val() +"&status="+ $("#approvedstatus").val();
			});
			$(".filter-button").button();
			$("#select_all").click(function(){
				$("#tabel_tagihan tbody tr").each(function(){
					if (!$(this).hasClass("row_selected") && !$(this).children().hasClass("dataTables_empty")){
						$(this).addClass("row_selected");
					}
				});
				$("#tabel_penukaran_valve tr").each(function(){
					if (!$(this).hasClass("row_selected") && !$(this).children().hasClass("dataTables_empty")){
						$(this).addClass("row_selected");
					}
				});
				$("#tabel_tagihan_transportasi tr").each(function(){
					if (!$(this).hasClass("row_selected") && !$(this).children().hasClass("dataTables_empty")){
						$(this).addClass("row_selected");
					}
				});
			});
			$("#de_select_all").click(function(){
				$("#tabel_tagihan tr").each(function(){
					if ($(this).hasClass("row_selected")){
						$(this).removeClass("row_selected");
					}
				});
				$("#tabel_penukaran_valve tr").each(function(){
					if ($(this).hasClass("row_selected")){
						$(this).removeClass("row_selected");
					}
				});
				$("#tabel_tagihan_transportasi tr").each(function(){
					if ($(this).hasClass("row_selected")){
						$(this).removeClass("row_selected");
					}
				});
			});
			$("#block_summary_place_holder").append($("#block_summary"));
			$("#block_approve").hide();
			$("#block_tagihan_transportasi").hide();
			$("#view_detail_tagihan").button().click(function(){
				$("#block_approve").toggle();
				$("#block_penukaran_valve").toggle();
				$("#block_tagihan_transportasi").toggle();
				$("#block_summary_place_holder").toggle();
				if ($("#view_detail_tagihan .ui-button-text").html() == "Lihat Detail Tagihan"){
					$("#view_detail_tagihan .ui-button-text").html("Hide Detail Tagihan");
				}else{
					$("#view_detail_tagihan .ui-button-text").html("Lihat Detail Tagihan");
				}
			});
			$("#do_approve_tagihan").button();
			$(".edit-harga-pekerjaan").editable(Drupal.settings.basePath + "simpanhargapekerjaan", {
				cssclass : "edit-class",
			  tooltip  : "Click to edit...",
				callback : function(value, settings) {
			  	console.log(this);
			  	console.log(this.id);
			    console.log(value);
			    console.log(settings);
			    var strID = this.id;
			    var splitID = strID.split("-");
			    var totalNilai = $("#total_pekerjaan_"+ splitID[1]).val() * value;
			    $("#total_nilai_"+ splitID[1]).html(addCommas(totalNilai));
			    $("#subtotal-"+ splitID[1]).val(totalNilai);
			    var totalSeluruhTagihan = 0;
			    $(".subtotaltagihan").each(function(){
			    	totalSeluruhTagihan = totalSeluruhTagihan + parseFloat($(this).val());
			   	});
			   	$("#total_seluruh_tagihan").html(addCommas(totalSeluruhTagihan));
			  }
			});
		})	
	','inline');
	$superuser = cek_super_user();
	if ($superuser){
		$pilihancabang = '<label style="width:80px;">Workshop</label>'.create_cabang_selection($workshop_nid,0);
		$pilihan_bulan = create_bulan_selection($bulan,0);
		$pilihantahun = '<label style="width:60px;">Tahun</label><input type="text" id="tahun" name="tahun" value="'.$tahun.'" style="width:100px;margin-bottom:0;">';
		$pilihanapproved = create_published_selection($selected_approved,0);
		$tombolfilter = '<button id="filter_tagihan" style="font-size: 11px;top:-1px;margin-bottom:0;">Filter Data</button>';
		$filter_tagihan = '<div id="form_jurnal_umum" style="width:97%;box-shadow: 2px 2px 4px #BBB;margin-bottom:10px;">';
		$filter_tagihan .= '<div class="form_row">'.$pilihancabang.' <label>Bulan Penagihan</label>'.$pilihan_bulan.' '.$pilihantahun.' '.$pilihanapproved.' '.$tombolfilter.'</div>';
		$filter_tagihan .= '</div>';
	}
?>
<div class="<?php print $classes; ?>">
  <?php if ($admin_links): ?>
    <div class="views-admin-links views-hide">
      <?php print $admin_links; ?>
    </div>
  <?php endif; ?>
  <?php print $filter_tagihan; ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>
  <?php
  /*
  <?php if ($exposed): ?>
    <div class="view-filters">
      <?php print $exposed; ?>
    </div>
  <?php endif; ?>
	*/ ?>
  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>
	
  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
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