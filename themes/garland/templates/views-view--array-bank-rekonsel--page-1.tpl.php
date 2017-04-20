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
	_add_block_ui_plugins();
 drupal_add_js('
  var oTable;
  function edit_bank_rekonsel(alamat){
 		window.location = alamat;
 	}
 	function delete_bank_rekonsel(nTr,nid){
 		 if (nid > 0){
 		 	var konfirmasi = confirm("Yakin ingin menghapus data ini...!!?");
 		 	if (konfirmasi){
 		 		$("div.view-array-bank-rekonsel").block({ message: \'<p style=\"color: 808080;padding: .2em;font-size: 18px;\">Sedang menghapus...<img src=\"misc/media/images/loading.gif\"></p>\',css: { border: \'3px solid #a00\' } });
 		 		var request = new Object;
 		 		request.nid = nid;
				submitaddress = Drupal.settings.basePath + "hapusnodebynid2";
				$.ajax({ 
					type: "POST",
					url: submitaddress,
					data: request, 
					cache: false, 
					success: function(data){
						$("div.view-array-bank-rekonsel").unblock();
						oTable.fnDeleteRow(nTr);
					}
				});
 		 	}
 		 }
 	}
 	$(document).ready(function(){
 		$("#edit-submit-array-bank-rekonsel").val("View Data");
 		$("#edit-submit-array-bank-rekonsel").button();
 		oTable = $("#tabel-bank_rekonsel").dataTable( {
				"bJQueryUI": true,
				"bPaginate": true,
				"sPaginationType": "full_numbers",
				"bLengthChange": true,
				"bFilter": true,
				"bSort": true,
				"bInfo": true,
				"bAutoWidth": false,
				"bStateSave": true,
				"aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0,1 ] }
        ]
			});
		$("#add-adjustment").button();
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