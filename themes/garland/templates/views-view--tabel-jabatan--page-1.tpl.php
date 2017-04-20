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
 _include_jquery_plugins($datatables);
 _add_datatable_reordering_plugins();
 _add_block_ui_plugins();
 drupal_add_js('
 	var oTable;
 	var perubahan = 0;
 	function goto_address(address_to_go){
  	window.location = address_to_go;
  }
  function update_urutan_jabatan(){
  	var nNodes = oTable.fnGetNodes();
  	var newUrutan = new Array;
  	for (var i = 0;i < nNodes.length;i++){
  		var nilai = new Object;
  		nilai.nid = nNodes[i].id;
  		nilai.posisi = nNodes[i].firstElementChild.innerHTML;
  		newUrutan[i] = nilai;
  	}
  	$("#node-form").block({ message: \'<p style=\"color: 808080;padding: .1em;margin-bottom:.8em;font-size: 18px;\">Update Urutan...<img src=\"misc/media/images/loading.gif\"></p>\',css: { border: \'3px solid #a00\' } });
  	$("#tabel_jabatan_place").block({ message: \'<p style=\"color: 808080;padding: .1em;margin-bottom:.8em;font-size: 18px;\">Update Urutan...<img src=\"misc/media/images/loading.gif\"></p>\',css: { border: \'3px solid #a00\' } });
  	var request = new Object;
		request.dataurutan = newUrutan;
		submitaddress = Drupal.settings.basePath + "hrd/updateurutanjabatan";
		$.ajax({ 
			type: "POST",
			url: submitaddress,
			data: request, 
			cache: false,
			success: function(data){
				$("#node-form").unblock();
				$("#tabel_jabatan_place").unblock();
				for (var i = 0;i < nNodes.length;i++){
		  		$("#"+ nNodes[i].id).removeAttr("style");
		  	}
		  	$("#perubahan-urutan-status").html("Drag and Drop untuk mengubah urutan jabatan, kemudian klik tombol <b>UPDATE URUTAN</b> untuk menyimpan perubahan urutan jabatan");
		  	$("#perubahan-urutan-status").removeClass("warning");
				$("#perubahan-urutan-status").addClass("status");
			}
		});
 	}
  $(document).ready(function(){
  	oTable = $("#tabel_jabatan").dataTable( {
  		"fnDrawCallback" : function() {
  			if (perubahan > 1){
					$("#perubahan-urutan-status").html("Urutan jabatan sudah berubah, klik UPDATE URUTAN untuk menyimpan perubahan..!!!");
					$("#perubahan-urutan-status").removeClass("status");
					$("#perubahan-urutan-status").addClass("warning");
					var nNodes = oTable.fnGetNodes();
					for (var i = 0;i < nNodes.length;i++){
						var posisi_baris = i + 1;
						if (posisi_baris != parseInt(nNodes[i].firstElementChild.innerHTML)){
							console.log(nNodes[i].id);
							$("#"+ nNodes[i].id).css("background-color","#F3E2A9");
						}
					}
				}
				perubahan++;
			},
			"bJQueryUI": true,
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": true,
			"bAutoWidth": false,
			"bStateSave": true,
			"aoColumnDefs": [
		   	{ "bSortable": false, "aTargets": [ 0,1,2,3 ] }
		   ]
		}).rowReordering();
		$("button").button();
	})
 ','inline');
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