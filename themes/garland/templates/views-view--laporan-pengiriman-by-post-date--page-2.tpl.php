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
	_add_table_tools_plugins();
	_add_block_ui_plugins();
	drupal_add_js('
 	var oTable;
  var mainpath = Drupal.settings.basePath;
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
  function goto_address(address_to_go){
  	window.location = address_to_go;
  }
 	$(document).ready(function(){
	 	$("#edit-created-max-wrapper label").html("s.d.");
	 	$("#edit-created-max-wrapper label").css("width","auto");
   	$("#edit-created-max-wrapper label").css("margin-right","8px");
		$("#edit-submit-laporan-pengiriman-by-post-date").text("Filter Data");
		$("#edit-submit-laporan-pengiriman-by-post-date").css("font-size","8.4pt");
		$("#edit-submit-laporan-pengiriman-by-post-date").button();
		$("#edit-submit-laporan-pengiriman-by-post-date").parent().css("width","auto");
   	$("#edit-submit-laporan-pengiriman-by-post-date").css("margin-top","0");
	 	oTable = $("#tabel_pengiriman").dataTable( {
			"bJQueryUI": true,
			"bPaginate": true,
			"sPaginationType": "full_numbers",
			"bLengthChange": true,
			"bFilter": true,
			"bInfo": true,
			"bAutoWidth": false,
			"bStateSave": true,
			"bSort": false
		});
		$("#edit-created-min").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 00:00\' }).attr("readonly","readonly");
		$("#edit-created-max").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 23:59\' }).attr("readonly","readonly");
		$("#views-exposed-form-laporan-pengiriman-by-post-date-page-2").prepend($("#pilihancabang"));
		$("#views-exposed-form-laporan-pengiriman-by-post-date-page-2").css("margin-bottom","0");
		$("button").button();
	})
 ','inline');
 	global $user;
	$superuser = false;
	if (in_array('Super User',$user->roles)){
		$cabang_rs = db_query("SELECT nid,title FROM node WHERE type='cabang_indogas'");
		$cabang_array = array();
		$nid_cabang_array = array();
		while ($cabang_data = db_fetch_object($cabang_rs)){
			if (count($cabang_array) == 0){
				$firstcabang = $cabang_data->nid;	
			}
			$cabang_array[] = $cabang_data;
			$nid_cabang_array[] = $cabang_data->nid;
		}
		$filtered_form = '<div id="pilihancabang" style="float:left;"><label style="width:80px"><b>Workshop :</b></label><select id="cabang" name="cabang" style="width:200px;">';
		for ($i = 0;$i < count($cabang_array);$i++){
			if ($cabang_array[$i]->nid == $_GET['cabang']){
				$filtered_form .= '<option selected value="'.$cabang_array[$i]->nid.'">'.$cabang_array[$i]->title.'</option>';	
			}else{
				$filtered_form .= '<option value="'.$cabang_array[$i]->nid.'">'.$cabang_array[$i]->title.'</option>';	
			}
		}	
		$filtered_form .= '</select></div>';
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
      <?php print $filtered_form; ?>
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