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
	if (isset($_GET['pesan'])){
		drupal_get_messages('status');
		drupal_set_message($_GET['pesan']);	
	}
	$datatables = true;
	_include_jquery_plugins($datatables);
	_add_table_tools_plugins();
	drupal_add_js('misc/media/js/combo_autocomplete2.js');
	_add_block_ui_plugins();
	drupal_add_js('
		var oTable;
		function getselectedrow(){
    	var selected_row = "";
    	$("#tabel_tagihan tr.row_selected").each(function(){
    		if (selected_row == ""){
    			selected_row = $(this).attr("id");
    		}else{
    			selected_row += "&"+ $(this).attr("id");
    		}
    	});
    	return selected_row;
		}
		$(document).ready(function(){
			$("#tabel_tagihan tr").css("cursor","pointer");
			$("#tabel_tagihan tr").click( function() {
        $(this).toggleClass("row_selected");
    	});
    	TableToolsInit.sSwfPath = Drupal.settings.basePath + "misc/media/datatables/swf/ZeroClipboard.swf";
	 		oTable = $("#tabel_tagihan").dataTable( {
				"bJQueryUI": true,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": true,
				"bInfo": true,
				"bAutoWidth": false,
				"bStateSave": true,
				"bSort": false,
				"sDom": \'<"space"T><"clear"><"H"lfr>t<"F"ip>\'
			});
			$("#tabel_tagihan_filter").before("<button id=\"remove_tagihan\" style=\"font-size: 12px;\" title=\"Klik untuk menghapus tagihan yang dipilih(di highligth)\">Hapus Tagihan</button>");
			$("#remove_tagihan").button().click(function(){
				var konfirmasi = confirm("Apakah tagihan yang dipilih benar-benar ingin dihapus...?");
				if (konfirmasi){
					console.log(getselectedrow());
					var request = new Object;
					request.datanid = getselectedrow();
					if (request.datanid != ""){
						$("#block_approve").block({ message: \'<p style=\"color: 808080;padding: .2em;font-size: 18px;\">Hapus Penagihan...<img src=\"misc/media/images/loading.gif\"></p>\',css: { border: \'3px solid #a00\' } });
						request.cabang_nid = $("#cabang").val();
						submitaddress = Drupal.settings.basePath + "dohapustagihan";
						$.ajax({ 
							type: "POST",
							url: submitaddress,
							data: request, 
							cache: false, 
							success: function(data){
								$("#block_approve").unblock();
								window.location = Drupal.settings.basePath + "tabeltagihankashop?pesan=Hapus data penagihan berhasil";
							}
						});
					}else{
						alert("Mohon pilih tagihan yang ingin di approve...!!!");
					}
				}
			});
			$("#tabel_tagihan_filter").css("margin-top","4px");
			$("#filter_tagihan").button().click(function(){
				window.location = Drupal.settings.basePath + "tabeltagihankashop?cabang="+ $("#cabang").val() +"&bulan="+ $("#bulan").val() +"&tahun="+ $("#tahun").val() +"&status="+ $("#approvedstatus").val();
			});
			$(".filter-button").button();
			$("#select_all").click(function(){
				$("#tabel_tagihan tr").each(function(){
					if (!$(this).hasClass("row_selected")){
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
			});
		})	
	','inline');
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
	if (isset($_GET['status'])){
		$selected_approved = $_GET['status'];
	}else{
		$selected_approved = 'All';
	}
	global $user;
	$superuser = false;
 	if (in_array('Super User',$user->roles)){
 		$superuser = true;		
  }
  if ($superuser){
		$pilihancabang = '<label style="width:80px;">Workshop</label>'.create_cabang_selection($workshop_nid,0);
	}else{
		$pilihancabang = '';
	}
	$pilihan_bulan = create_bulan_selection($bulan,0);
	$pilihantahun = '<label style="width:60px;">Tahun</label><input type="text" id="tahun" name="tahun" value="'.$tahun.'" style="width:100px;margin-bottom:0;">';
	//$pilihanapproved = create_published_selection($selected_approved,0);
	$tombolfilter = '<button id="filter_tagihan" style="font-size: 11px;top:-1px;margin-bottom:0;">Filter Data</button>';
	$filter_tagihan = '<div id="form_jurnal_umum" style="width:97%;box-shadow: 2px 2px 4px #BBB;margin-bottom:10px;">';
	$filter_tagihan .= '<div class="form_row">'.$pilihancabang.' <label>Bulan Penagihan</label>'.$pilihan_bulan.' '.$pilihantahun.' '.$tombolfilter.'</div>';
	$filter_tagihan .= '</div>';
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