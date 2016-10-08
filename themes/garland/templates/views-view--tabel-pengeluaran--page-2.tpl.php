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
 $path = drupal_get_path('theme', 'garland');
 $form_style = $path.'/custom_css.css';
 drupal_add_css($form_style, 'theme', 'all', FALSE);
 $variables['styles'] = drupal_get_css();
 $datatables = true;
 _include_jquery_plugins($datatables);
 drupal_add_js('sites/all/libraries/jeditable.datepicker/jquery.jeditable.mini.js');
 drupal_add_js('sites/all/libraries/jeditable.datepicker/jquery.jeditable.datepicker.js');
 drupal_add_js('sites/all/libraries/autotab/jquery.autotab.js');
 drupal_add_js('
  var mainpath = "'.base_path().'";
  var oTable;
  var nTrowselected;
  var nidselected;
  function fnFormatDetails ( oTable, nidpemakaian){
		var detailpemakaian = $("#detail_"+ nidpemakaian).html();
		sOut = detailpemakaian;
		return sOut;
	}
	function see_details(nTr,nid){
		if ( $("#"+ nid).attr("src") == mainpath +"sites/all/libraries/datatables/images/details_close.png"){
			/* This row is already open - close it */
			$("#"+ nid).attr("src",mainpath +"sites/all/libraries/datatables/images/details_open.png");
			oTable.fnClose( nTr );
		}else{
			/* Open this row */
			$("#"+ nidselected).attr("src",mainpath +"sites/all/libraries/datatables/images/details_open.png");
			oTable.fnClose( nTrowselected );
			$("#"+ nid).attr("src",mainpath +"sites/all/libraries/datatables/images/details_close.png");
			oTable.fnOpen( nTr, fnFormatDetails(oTable, nid), "details" );
			nTrowselected = nTr;
			nidselected = nid;
		}
	}
	$(document).ready(function(){
  	$("#edit-created-max-wrapper label").html("s.d.");
 		$("#edit-created-max-wrapper label").css("width","auto");
   	$("#edit-created-max-wrapper label").css("margin-right","8px");
		$("#edit-submit-tabel-pengeluaran").text("Filter Data");
		$("#edit-submit-tabel-pengeluaran").css("font-size","8.4pt");
		$("#edit-submit-tabel-pengeluaran").button();
		$("#edit-submit-tabel-pengeluaran").parent().css("width","auto");
		$("#edit-submit-tabel-pengeluaran").css("margin-top","0");
		$(".detail_icon").css("cursor","pointer");
		$(".detail_icon").each(function(){
			var nid = $(this).attr("id");
			$(this).click(function(){
				see_details(this.parentNode.parentNode,nid);
			});
		});
		oTable = $("#tabelpengeluaran").dataTable( {
			"bJQueryUI": true,
			"bPaginate": true,
			"bLengthChange": true,
			"bFilter": true,
			"bInfo": true,
			"bAutoWidth": false,
			"bSort": false,
		});
		$("#edit-created-min").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 00:00\' }).attr("readonly","readonly");
		$("#edit-created-max").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 23:59\' }).attr("readonly","readonly");
		$("#edit-created-min").change(function(){
 			tglpilihan = $(this).val();
 			splittglwaktu = tglpilihan.split(" ");
 			tglsaja = splittglwaktu[0];
 			tglset = splittglwaktu[0] +" 23:59";
 			$("#edit-created-max").datepicker("setDate", tglset );
 		});
		$("#views-exposed-form-tabel-pengeluaran-page-2").prepend($("#pilihancabang"));
		$("#views-exposed-form-tabel-pengeluaran-page-2").css("margin-bottom","0");
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