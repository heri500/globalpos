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
	drupal_add_css('misc/media/datatables/themes/redmond/jquery-ui-1.8.14.custom.css');
	//drupal_add_css('misc/media/datatables/css/ColVis.css');
	drupal_add_js('misc/media/jquery-1.5.1.min.js');
	drupal_add_js('misc/media/jquery-ui-1.8.14.custom.min.js');
	drupal_add_css('misc/media/datatables/css/demo_table_jui.css');
	drupal_add_css('misc/media/datatables/css/TableTools.css');
	drupal_add_js('misc/media/datatables/js/jquery.dataTables.min.js');
	drupal_add_js('misc/media/datatables/ZeroClipboard/ZeroClipboard.js');
	drupal_add_js('misc/media/datatables/js/TableTools.min.js');
	//drupal_add_js('misc/media/datatables/js/ColVis.min.js');
	drupal_add_js('
	  var pathutama = "'.base_path().'";
		function edit_jurnal(nid_jurnal_ref){
			window.location = pathutama + "akutansi/editjurnal?jurnal_nid="+ nid_jurnal_ref;
		}
		$(document).ready(function(){
   		$(".submitted").hide();
   		$("#edit-created-max-wrapper label").html("s.d.");
   		$("#edit-created-max-wrapper label").css("width","auto");
   		$("#edit-created-max-wrapper label").css("margin-right","8px");
			$("#edit-submit-tabel-jurnal-by-post-date").text("Filter Data");
			$("#edit-submit-tabel-jurnal-by-post-date").css("font-size","8.4pt");
			$("#edit-submit-tabel-jurnal-by-post-date").button();
			//$("th.views-field-created").css("width","130px");
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
				"bStateSave": false,
				"aaSorting": [ [0,"desc"] ],
				"sDom": \'<"space"T><"clear"><"H"lfr>t<"F"ip>\'
			});
			$("#edit-created-min").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 00:00\' }).attr("readonly","readonly");
			$("#edit-created-max").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 23:59\' }).attr("readonly","readonly");
			//$("#views-exposed-form-tabel-jurnal-by-post-date-page-1").prepend($("#pilihanuser"));
			$("#views-exposed-form-tabel-jurnal-by-post-date-page-1").prepend($("#pilihancabang"));
   		$("#filtertabel").button();
   		$("#edit-submit-tabel-jurnal-by-post-date").parent().css("width","auto");
   		$("#edit-submit-tabel-jurnal-by-post-date").css("margin-top","0");
   		//$("#edit-uid").css("width","120px");
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
		/*$user_array = create_user_array();
		$filtered_form .= '<div id="pilihanuser" style="float:left;"><label style="width:80px"><b>User :</b></label><select id="user_id" name="user_id" style="width:200px;">';
		for ($i = 0;$i < count($user_array);$i++){
			if ($user_array[$i]->uid == $_GET['user_id']){
				$filtered_form .= '<option selected value="'.$user_array[$i]->uid.'">'.$user_array[$i]->name.'</option>';	
			}else{
				$filtered_form .= '<option value="'.$user_array[$i]->uid.'">'.$user_array[$i]->name.'</option>';	
			}
		}
		$filtered_form .= '</select></div>';*/
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