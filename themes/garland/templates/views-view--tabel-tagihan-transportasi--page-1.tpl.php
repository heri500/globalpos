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
  add_custom_css();
	$datatables = true;
	$fixedcolum = false;
	_include_jquery_plugins($datatables,$fixedcolum);
	global $user;
	$superuser = cek_super_user();
	if ($superuser){
		drupal_add_js('
		var superuser = true;
		','inline');
	}else{
		drupal_add_js('
		var superuser = false;
		','inline');
	}
	drupal_add_js('
		var oTable;
		function edit_penukaran(nid){
			window.location = Drupal.settings.basePath + "node/"+ nid +"/edit?destination=produksi/tabeltagihantransport";
		}
		$(document).ready(function(){
			oTable = $("#tabel_tagihan_transportasi").dataTable( {
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
			$("#edit-created-max-wrapper label").html("s.d.");
	 		$("#edit-created-max-wrapper label").css("width","auto");
	 		$("#edit-created-max-wrapper").parent().parent().css("width","405px");
	 		$(".views-exposed-widget label:contains(\'Periode\')").css("width","80px");
	   	$("#edit-created-max-wrapper label").css("margin-right","8px");
			$("#edit-submit-tabel-tagihan-transportasi").text("Filter Data");
			$("#edit-submit-tabel-tagihan-transportasi").css("font-size","12px");
			$("#edit-submit-tabel-tagihan-transportasi").css("padding","0.3em 1em");
			$("#edit-submit-tabel-tagihan-transportasi").button();
			$("#edit-submit-tabel-tagihan-transportasi").parent().css("width","auto");
			$("#edit-submit-tabel-tagihan-transportasi").css("margin-top","0");
			$("#edit-created-min").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 00:00\' }).attr("readonly","readonly");
			$("#edit-created-max").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 23:59\' }).attr("readonly","readonly");
			$("#views-exposed-form-tabel-tagihan-transportasi-page-1").prepend($("#pilihancabang"));
			$("#views-exposed-form-tabel-tagihan-transportasi-page-1").css("margin-bottom","0");
			$("#views-exposed-form-tabel-tagihan-transportasi-page-1").parent().attr("id","form_jurnal_umum");
			if (superuser){
				$("#form_jurnal_umum").css("width","850px");
			}else{
				$("#form_jurnal_umum").css("width","540px");
			}
			$("#form_jurnal_umum").css("margin-bottom","10px");
			$(".views-exposed-widgets").css("margin-bottom","0");
		})	
	','inline');
	if ($superuser){
		if (isset($_GET['cabang'])){
			$selected_cabang = $_GET['cabang'];
		}else{
			$selected_cabang = 1;
		}
	}
 	if ($superuser){
 		$alamat_tambah_penukaran = base_path().'node/add/tagihan-transportasi';	
 		$pilihan_cabang = create_cabang_selection($selected_cabang,0);
		$formfilterdata = '<div id="pilihancabang" style="float:left;"><label style="width:80px"><b>Workshop :</b></label>'.$pilihan_cabang.'</div>';
 	}else{
 		$view = views_get_view('get_posisi_user');
		$view->execute();
		if (count($view->result)){
			$id_posisi_user = $view->result[0]->node_data_field_user_id_field_cabang_id_nid;
			$alamat_tambah_penukaran = base_path().'node/add/tagihan-transportasi/'.$id_posisi_user;
		}	
 	}
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
	<?php print $formfilterdata; ?>
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