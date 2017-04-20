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
 	//drupal_get_messages('status');
  if (isset($_GET['field_workshop_penyesuaian_ref_nid'])){
  	if (trim($_GET['field_workshop_penyesuaian_ref_nid']) == 'All'){
 			$cabangpilihan = 0;	
 		}else{
 			$cabangpilihan = node_load($_GET['field_workshop_penyesuaian_ref_nid']);
 		}
 	}else{
 		$cabangpilihan = 0;
 	}
 	php_to_drupal_settings(array('cabangpilihan' => $cabangpilihan));
 	$tglawal = '';
 	$tglakhir = '';
 	if (isset($_GET['created_1'])){
 		$tanggalpilihan = $_GET['created_1'];
 		php_to_drupal_settings(array('tglawal' => $tanggalpilihan['min']));
 		php_to_drupal_settings(array('tglakhir' => $tanggalpilihan['max']));
 	}else{
 		php_to_drupal_settings(array('tglawal' => $tglawal));
 		php_to_drupal_settings(array('tglakhir' => $tglkhir));
 	}
 	$datatables = true;
	_include_jquery_plugins($datatables);
	_add_table_tools_plugins();
	_add_block_ui_plugins();
	drupal_add_js('
	  var pathutama = Drupal.settings.basePath;
	  var cabangpilihan = 0;
	  var tglawal = "";
	  var tglakhir = "";
		function edit_jurnal_penyesuaian(nid_jurnal_ref){
			window.location = pathutama + "akutansi/editjurnalpenyesuaian?jurnal_nid="+ nid_jurnal_ref;
		}
		$(document).ready(function(){
			cabangpilihan = Drupal.settings.indogas.cabangpilihan;
			if (Drupal.settings.indogas.tglawal){
				tglawal = Drupal.settings.indogas.tglawal;
			}else{
		  	tglawal = $("#edit-created-1-min").val();
		  }
		  if (Drupal.settings.indogas.tglakhir){
		  	tglakhir = Drupal.settings.indogas.tglakhir;
		  }else{
		  	tglakhir = $("#edit-created-1-max").val();
		  }
		  console.log(cabangpilihan + " " + tglawal + " " + tglakhir);
			$(".view-filters label").css("width","85px");
   		$("#edit-created-1-max-wrapper label").html("s.d.");
   		$("#edit-created-1-max-wrapper label").css("width","auto");
   		$("#edit-created-1-max-wrapper label").css("margin-right","8px");
			$("#edit-submit-tabel-jurnal-penyesuaian").text("Filter Data");
			$("#edit-submit-tabel-jurnal-penyesuaian").css("font-size","8.4pt");
			$("#edit-submit-tabel-jurnal-penyesuaian").button();
			TableToolsInit.sSwfPath = pathutama + "misc/media/datatables/swf/ZeroClipboard.swf";
		 	oTable = $("#tabel_jurnal").dataTable( {
				"bJQueryUI": true,
				"bPaginate": true,
				"sPaginationType": "full_numbers",
				"bLengthChange": true,
				"bFilter": true,
				"bSort": true,
				"bInfo": true,
				"bAutoWidth": false,
				"bStateSave": true,
				"aaSorting": [ [0,"desc"] ],
				"sDom": \'<"space"T><"clear"><"H"lfr>t<"F"ip>\'
			});
			$("#edit-created-1-min").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 00:00\' }).attr("readonly","readonly");
			$("#edit-created-1-max").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 23:59\' }).attr("readonly","readonly");
			$("#filtertabel").button();
   		$("#edit-submit-tabel-jurnal-penyesuaian").parent().css("width","auto");
   		var combo_left_margin_value = -1;
   		var combo_top_value = 3;
   		$("#edit-field-workshop-penyesuaian-ref-nid").css("width","auto");
   		$("#edit-field-workshop-penyesuaian-ref-nid-wrapper").parent().parent().css("width","320px");
   		$("#edit-created-1-min-wrapper").parent().parent().css("width","390px");
   		$("#tabel_jurnal_wrapper").before($("#tabel_jurnal_wrapper .space"));
   		$("#do-posting-penyesuaian").button();
   		$("#edit-created-1-min").change(function(){
   			tglpilihan = $(this).val();
   			splittglwaktu = tglpilihan.split(" ");
   			tglsaja = splittglwaktu[0];
   			tglset = splittglwaktu[0] +" 23:59";
   			$("#edit-created-1-max").datepicker("setDate", tglset );
   		});
   		$("#edit-created-1-min,#edit-created-1-max").css("width","120px");
   		$("#do-posting-penyesuaian").click(function(){
   			if (cabangpilihan == 0){
   				var konfirmasi = confirm("Yakin akan melakukan posting penyesuaian untuk seluruh workshop periode "+ tglawal.substr(0,10)  +" s.d "+ tglakhir.substr(0,10) +"?");
   				cabangposting = 0;
   			}else{
   				var konfirmasi = confirm("Yakin akan melakukan posting penyesuaian untuk workshop "+ cabangpilihan.field_nama_singkat[0].value +" periode "+ tglawal.substr(0,10) +" s.d "+ tglakhir.substr(0,10) +"?");
   				cabangposting = cabangpilihan.nid;
   			}
   			if (konfirmasi){
   				$(".view-tabel-jurnal-penyesuaian").block({ message: "<p style=\"color: 808080;padding: .2em;font-size: 18px;\">Saving...<img src=\"misc/media/images/loading.gif\"></p>",css: { border: "3px solid #a00" } });
   				var request = new Object;
					request.workshop = cabangposting;
					request.tglawal = tglawal;
					request.tglakhir = tglakhir;
					submitaddress = pathutama + "akutansi/postingjurnalpenyesuaian";
					$.ajax({ 
						type: "POST",
						url: submitaddress,
						data: request, 
						cache: false, 
						success: function(data){
							alert("Jurnal Penyesuaian berhasil diposting...!");
							$(".view-tabel-jurnal-penyesuaian").unblock();
							window.location = pathutama + "akutansi/tabelpenyesuaian";
						}
					});
   			}
   		});
   	})
	','inline');
	global $user;
	$superuser = cek_super_user();
	//dpm($view);
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