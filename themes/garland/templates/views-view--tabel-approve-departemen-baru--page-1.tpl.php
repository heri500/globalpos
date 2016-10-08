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
 if (isset($_GET['pesan'])){
  	drupal_set_message($_GET['pesan']);	
  }
 	add_custom_css();
	$datatables = true;
	_include_jquery_plugins($datatables);
	_add_block_ui_plugins();
	drupal_add_js('
		var oTable;
		$(document).ready(function(){
			//console.dir(Drupal.settings.indogas.array_absensi_karyawan);
			oTable = $("#tabel_departemen_baru").dataTable( {
				"bJQueryUI": true,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": true,
				"bInfo": true,
				"bAutoWidth": false
			});
			$("button").button();
			$("#select_all").click(function(){
				$(".approve-departemen-baru").attr("checked","checked");
			});
			$("#deselect_all").click(function(){
				$(".approve-departemen-baru").removeAttr("checked");
			});
			$("#do_approve_status").click(function(){
				$("#block_approve").block({ message: \'<p style=\"color: 808080;padding: .2em;font-size: 18px;\">Approving...<img src=\"../misc/media/images/loading.gif\"></p>\',css: { border: \'3px solid #a00\' } });
				var sData = $("input", oTable.fnGetNodes()).serialize();
				var request = new Object;
				request.datanid = sData;
				submitaddress = Drupal.settings.basePath + "hrd/doapprovedepartemenbaru";
				$.ajax({ 
					type: "POST",
					url: submitaddress,
					data: request, 
					cache: false, 
					success: function(data){
						$("#block_approve").unblock();
						window.location = Drupal.settings.basePath + "hrd/approvedepartemen?pesan=Approve departemen karyawan berhasil";
					}
				});
			});
		})
	','inline');
	$arraymessage['title'] = t('Untuk melakukan approve departemen :');
	$arraymessage['content'][0] = t('Pastikan data karyawan sudah benar');
	$arraymessage['content'][1] = t('Pilih/ Bubuhkan tanda centang pada karyawan yang ingin di approve');
	$arraymessage['content'][2] = t('Klik tombol approve jika sudah yakin akan melakukan approval');
	$pesaninformasi = create_warning_message($arraymessage);
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
  	<?php print $pesaninformasi; ?>
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