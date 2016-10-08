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
	drupal_add_js('
		var oTable;
		var mainpath = Drupal.settings.basePath;
		function add_detail_kategori(nid){
			window.location = mainpath + "node/add/cash-flow-by-kategori/"+ nid +"?destination=akutansi/tabelkategoricashflow";
		}
		function edit_kategori(nid){
			window.location = mainpath + "node/"+ nid +"/edit?destination=akutansi/tabelkategoricashflow";
		}
		function delete_detail_kategori(nid){
			var konfirmasi = confirm("Yakin ingin menghapus detail kategori cashflow ini...!!!(Tidak akan menghapus COA, hanya menghilangkan COA ini dari kategori cashflow terkait)");
			if (konfirmasi){
				var request = new Object;
				request.nid = nid;
				submitaddress = mainpath + "hapusnodebynid";
				$.ajax({ 
					type: "POST",
					url: submitaddress,
					data: request, 
					cache: false, 
					success: function(data){
						alert(data);
						window.location = mainpath + "akutansi/tabelkategoricashflow";
					}
				});
			}
		}
		function fnFormatDetails ( nid ){
			var detaildata = $("#detail-kategori-"+ nid).html();
			return detaildata;
		}
		function view_detail_kategori(nTr,iconbutton,nid){
			if ( iconbutton.src.match("details_close") ){
				/* This row is already open - close it */
				iconbutton.src = mainpath +"sites/all/libraries/datatables/images/details_open.png";
				oTable.fnClose( nTr );
			}else{
				/* Open this row */
				iconbutton.src = mainpath +"sites/all/libraries/datatables/images/details_close.png";
				oTable.fnOpen( nTr, fnFormatDetails(nid), "details" );
			}
		}
		$(document).ready(function(){
			oTable = $("#tabel-kategori-cashflow").dataTable( {
				"bJQueryUI": true,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": true,
				"bInfo": true,
				"bAutoWidth": false,
				"bStateSave": false,
				"bSort": true,
				"aaSorting": [[ 3 , "asc" ]]
			});
			var oTable2 = $(".detail_kategori").dataTable( {
				"bJQueryUI": false,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": false,
				"bInfo": true,
				"bAutoWidth": false,
				"bSort": false
			});
			$("#add-kategori-cashflow").button().css("margin-bottom","10px").click(function(){
				window.location = mainpath + "node/add/kategori-cash-flow?destination=akutansi/tabelkategoricashflow"
			});
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