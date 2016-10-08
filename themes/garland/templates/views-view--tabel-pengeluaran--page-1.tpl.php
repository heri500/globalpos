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
	function opendialogedit(nidjurnal){
		var jurnal_value = $("#data_jurnal_"+ nidjurnal).val();
		var jurnal_value_split = jurnal_value.split("_X_");
		$("#account").val(jurnal_value_split[1]+ "-"+ jurnal_value_split[2]);
		$("#transaksi_detail").val(jurnal_value_split[3]);
		nilai = jurnal_value_split[4].replace(/,/gi, "");
		$("#jumlah").val(parseInt(nilai));
		$( "#account_code" ).val( jurnal_value_split[1] );
   	$( "#account_nid" ).val( jurnal_value_split[5] );
   	$( "#account_name" ).val( jurnal_value_split[2] );
   	$( "#detail_jurnal_nid" ).val(nidjurnal);
		$("#dialog-edit-jurnal").dialog("open");
		$("#account").select();
	}
	function update_selected_jurnal(){
		nidjurnal = $( "#detail_jurnal_nid" ).val();
		if ($("#account").val() != "" && $("#account_nid").val() != "" && parseInt($("#account_nid").val()) > 0 && 
		$( "#detail_jurnal_nid" ).val() != "" && parseInt($( "#detail_jurnal_nid" ).val()) > 0 && 
		$("#jumlah").val() != "" && parseInt($("#jumlah").val()) > 0 && $("#transaksi_detail").val() != ""){
			$("#dialog-edit-jurnal").dialog( "disable" );
			var keterangan = $("#transaksi_detail").val();
			keterangan = keterangan.replace(/&/gi, "dan");
			var request = new Object;
			request.nidjurnal = nidjurnal;
			request.nidaccount = $("#account_nid").val();
			request.transaksi = keterangan;
			request.nilai = $("#jumlah").val();
			submitaddress = mainpath + "updatedetailjurnal";
			$.ajax({ 
				type: "POST",
				url: submitaddress,
				data: request, 
				cache: false, 
				success: function(data){
					alert("Update data berhasil");
					pecah_data = data.split("_X_");
					$("#total_"+ pecah_data[0]).html(pecah_data[1]);
					$("#nilai_transaksi_"+ nidjurnal).html(pecah_data[2]);
					jurnal_data = pecah_data[3] +"_X_"+ pecah_data[4] +"_X_"+ pecah_data[5] +"_X_"+ pecah_data[6] +"_X_"+ pecah_data[7] +"_X_"+ pecah_data[8];
					$("#account_ref_"+ nidjurnal).html(pecah_data[4]);
					$("#nama_account_ref_"+ nidjurnal).html(pecah_data[5]);
					$("#ket_transaksi_"+ nidjurnal).html(pecah_data[6]);
					$("#data_jurnal_"+ nidjurnal).val(jurnal_data);
					$("#"+ pecah_data[0]).attr("src",mainpath +"sites/all/libraries/datatables/images/details_open.png");
					oTable.fnClose( nTrowselected );
					$("#"+ pecah_data[0]).attr("src",mainpath +"sites/all/libraries/datatables/images/details_close.png");
					oTable.fnOpen( nTrowselected, fnFormatDetails(oTable, pecah_data[0]), "details" );
					$("#dialog-edit-jurnal").dialog( "enable" );
					$("#dialog-edit-jurnal").dialog( "close" );
				}
			});
		}
	}
	function delete_jurnal(nid_jurnal, nTr){
		var konfirmasi = confirm("Apakah jurnal ini benar-benar ingin dihapus..??!");
		if (konfirmasi){
			var request = new Object;
			request.nidjurnal = nid_jurnal;
			submitaddress = mainpath + "deletejurnal";
			$.ajax({ 
				type: "POST",
				url: submitaddress,
				data: request, 
				cache: false, 
				success: function(data){
				  console.log("Data hasil : "+ data);
					pecah_data = data.split("_X_");
					$("#total_"+ pecah_data[0]).html(pecah_data[1]);
      		alert("Jurnal berhasil dihapus...!");
      		if (pecah_data[2] == 0){
						$("#baris_"+ pecah_data[0]).remove();
						nTr.parent().parent().parent().parent().parent().parent().remove();
					}else{
						nTr.remove();
					}
      	}
      });
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
			"aaSorting": [ [2,"desc"], [3,"desc"] ]
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
		$("#views-exposed-form-tabel-pengeluaran-page-1").prepend($("#pilihancabang"));
		$("#views-exposed-form-tabel-pengeluaran-page-1").css("margin-bottom","0");
		$("#dialog-edit-jurnal").dialog({
			height: 130,
			width: 940,
			modal: true,
			resizable: false,
			autoOpen: false
		});
		$("#update_jurnal").button();
		ajax_source_data = mainpath + "getaccountdata";
		$( "#account" ).autocomplete({ 
   		source: ajax_source_data,
   		search: function( event, ui){
   			$("#progress").css("top","3px");
   			$("#progress").css("margin","3px 4px 0 -30px");
   			$("#progress").html("<img src=\""+ mainpath +"misc/media/images/loading2.gif\">");
   		},
   		close: function( event, ui ){
   			$("#progress").css("margin","0");
   			$("#progress").html("");	
   		},
   		select: function( event, ui ) {
   			$( "#account_code" ).val( ui.item.name );
   			$( "#account_nid" ).val( ui.item.nid );
   			$( "#account_name" ).val( ui.item.acc_name );
   			$( "#transaksi_detail" ).select();
			}
		});
		$("#transaksi_detail").autotab_filter({
			format: "custom",
			pattern: "[^-0-9a-zA-Z,.@%()/ ]+$"
		});
		$("#jumlah").autotab_filter("numeric");
		$(".editable").editable(
			function( value, settings ) {
				var tgl_input_id = $(this).attr("id");
				var split_id_tgl = tgl_input_id.split("_");
				var nidjurnal = split_id_tgl[2];
				var object_tgl = $(this);
				var request = new Object;
				request.nidjurnal = nidjurnal;
				request.tanggalbaru = value;
				request.jam = $("#jam_jurnal_"+ nidjurnal).text();
				console.log("Jam : "+ request.jam);
				request.perubahan = "tanggal";
				submitaddress = mainpath + "updatejurnalinfo";
				$.ajax({ 
					type: "POST",
					url: submitaddress,
					data: request, 
					cache: false, 
					success: function(data){
	      		object_tgl.html(value);
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
				var nidjurnal = split_id_jam[2];
				var object_jam = $(this);
				var request = new Object;
				request.nidjurnal = nidjurnal;
				request.tanggal = $("#tanggal_jurnal_"+ nidjurnal).text();
				request.jambaru = value;
				request.perubahan = "jam";
				submitaddress = mainpath + "updatejurnalinfo";
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
				var ref_input_id = $(this).attr("id");
				var split_id_ref = ref_input_id.split("_");
				var nidjurnal = split_id_ref[2];
				var object_ref = $(this);
				var request = new Object;
				request.nidjurnal = nidjurnal;
				request.refbaru = value;
				request.perubahan = "nomer_ref";
				submitaddress = mainpath + "updatejurnalinfo";
				$.ajax({ 
					type: "POST",
					url: submitaddress,
					data: request, 
					cache: false, 
					success: function(data){
	      		object_ref.html(value);
	      	}
	      });	
	    },{
			submit		: "Update",
      tooltip   : "Klik untuk mengubah No Referensi...",
      indicator : "Saving..."
    });
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
<div id="dialog-edit-jurnal" title="UBAH JURNAL">
	<div id="form_detail_jurnal" style="width: auto;">
		<div class="form_row"><label>Account</label><input type="text" id="account" name="account" style="width: 150px;"><span id="progress" style="float:left;"></span>
		<input type="hidden" id="account_code" name="account_code">
		<input type="hidden" id="account_nid" name="account_nid">
		<input type="hidden" id="account_name" name="account_name">
		<input type="hidden" id="detail_jurnal_nid" name="detail_jurnal_nid">
		<label style="width: 100px;">Keterangan<span class="require_field">*</span></label><input type="text" id="transaksi_detail" name="transaksi_detail" style="width: 200px;">
		<label>Jumlah<span class="require_field">*</span></label><input type="text" id="jumlah" name="jumlah" class="tanggal" style="width: 130px;">
		<button id="update_jurnal" style="font-size:11px;" title="Klik untuk mengupdate jurnal" onclick="update_selected_jurnal();">Update Jurnal</button>
	</div>
</div>