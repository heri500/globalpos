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
 	global $user;
	$superuser = false;
	if (in_array('Super User',$user->roles)){
		$superuser = true;
	}
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
	if ($superuser){
		drupal_add_js('misc/media/jquery-ui-timepicker-addon.js');
		drupal_add_js('sites/all/libraries/jeditable.datepicker/jquery.jeditable.mini.js');
 		drupal_add_js('sites/all/libraries/jeditable.datepicker/jquery.jeditable.datepicker.js');
	}
	drupal_add_js('
 	var oTable;
  var mainpath = "'.base_path().'";
  var superuser = "'.$superuser.'";
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
		$("#edit-created-min").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 00:00\' }).attr("readonly","readonly");
		$("#edit-created-max").datepicker({ changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd 23:59\' }).attr("readonly","readonly");
		$("#views-exposed-form-laporan-pengiriman-by-post-date-page-1").prepend($("#pilihancabang"));
		$("#views-exposed-form-laporan-pengiriman-by-post-date-page-1").css("margin-bottom","0");
		$("button").button();
		console.log(superuser);
		if (superuser == "1"){
			$(".editable").editable(
				function( value, settings ) {
					var tgl_input_id = $(this).attr("id");
					var split_id_tgl = tgl_input_id.split("_");
					var nidkirim = split_id_tgl[2];
					var object_tgl = $(this);
					var request = new Object;
					request.nidkirim = nidkirim;
					jam = $("#jam_kirim_"+ nidkirim).text();
					request.waktubaru = value +" "+ jam;
					request.tanggal = value;
					request.jam = jam;
					console.log("Waktu : "+ request.waktubaru);
					submitaddress = mainpath + "updatekiriminfo";
					$.ajax({ 
						type: "POST",
						url: submitaddress,
						data: request, 
						cache: false, 
						success: function(data){
		      		object_tgl.html(value);
		      		$("#edit-submit-laporan-pengiriman-by-post-date").click();
		      	}
		      });	
		    },{
				type: "datepicker",
				datepicker: {
	        changeMonth: true,
	        changeYear: true,
	        dateFormat: "yy-mm-dd"
	      },
	      tooltip   : "Klik untuk mengubah tanggal...",
	      indicator : "Saving..."
    	});
    	$(".editable2").editable(
				function( value, settings ) {
					var jam_input_id = $(this).attr("id");
					var split_id_jam = jam_input_id.split("_");
					var nidkirim = split_id_jam[2];
					var object_jam = $(this);
					var request = new Object;
					request.nidkirim = nidkirim;
					request.tanggal = $("#tanggal_kirim_"+ nidkirim).text();
					request.jam = value;
					request.waktubaru = request.tanggal +" "+ value;
					submitaddress = mainpath + "updatekiriminfo";
					$.ajax({ 
						type: "POST",
						url: submitaddress,
						data: request, 
						cache: false, 
						success: function(data){
		      		object_jam.html(value);
		      	}
		      });	
		    },{
				type: "timepicker",
				timepicker: {
	        timeOnlyTitle: "PILIH WAKTU",
					timeText: "Waktu",
					hourText: "Jam",
					minuteText: "Menit",
					secondText: "Detik",
					currentText: "Saat Ini",
					showButtonPanel: false
	      },
	      submit		: "Ok",
	      tooltip   : "Klik untuk mengubah jam...",
	      indicator : "Saving..."
	    });
	    $(".editable3").editable(
				function( value, settings ) {
					var rit_input_id = $(this).attr("id");
					var split_id_rit = rit_input_id.split("_");
					var nidkirim = split_id_rit[2];
					var object_rit = $(this);
					var request = new Object;
					request.nidkirim = nidkirim;
					request.rit = value;
					submitaddress = mainpath + "updatekiriminfo";
					$.ajax({ 
						type: "POST",
						url: submitaddress,
						data: request, 
						cache: false, 
						success: function(data){
		      		object_rit.html(value);
		      	}
		      });	
		    },{
				submit		: "Ok",
	      tooltip   : "Klik untuk mengubah jam...",
	      indicator : "Saving..."
	    });
		}
		TableToolsInit.sSwfPath = mainpath + "misc/media/datatables/swf/ZeroClipboard.swf";
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
	})
 ','inline');
 	if ($superuser){
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