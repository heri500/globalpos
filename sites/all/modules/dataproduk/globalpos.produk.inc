<?php

function data_produk(){
    $path = drupal_get_path('theme', 'cti_flex');
    $form_style = $path.'/css/custom-style.css';
    drupal_add_css($form_style, 'theme', 'all', FALSE);
    //$variables['styles'] = drupal_get_css();
	drupal_add_css('misc/media/datatables.1.10/jquery/jquery-ui.css');
	drupal_add_css('misc/media/datatables.1.10/media/css/dataTables.jqueryui.css');
  drupal_add_css('misc/media/datatables.1.10/extensions/Buttons/css/buttons.dataTables.css');
  drupal_add_css('misc/media/datatables.1.10/extensions/Buttons/css/buttons.jqueryui.css');
	drupal_add_css('misc/media/jqueryValidate/css/validationEngine.jquery.css');
	drupal_add_css('misc/media/jqueryValidate/css/template.css');
	drupal_add_js('misc/media/datatables.1.10/media/js/jquery.js');
	drupal_add_js('misc/media/jqueryValidate/js/jquery.validationEngine-en.js');
	drupal_add_js('misc/media/jqueryValidate/js/jquery.validationEngine.js');
	drupal_add_js('misc/media/datatables.1.10/jquery/jquery-ui.js');
	drupal_add_js('misc/media/datatables.1.10/media/js/jquery.dataTables.js');
	drupal_add_js('misc/media/datatables.1.10/media/js/dataTables.jqueryui.js');
  drupal_add_js('misc/media/datatables.1.10/extensions/Buttons/js/dataTables.buttons.js');
  drupal_add_js('misc/media/datatables.1.10/extensions/Buttons/js/buttons.flash.js');
  drupal_add_js('misc/media/datatables.1.10/extensions/Buttons/js/buttons.html5.js');
  drupal_add_js('misc/media/datatables.1.10/extensions/Buttons/js/buttons.print.js');
  drupal_add_js('misc/media/datatables.1.10/extensions/Buttons/js/buttons.colVis.min.js');
	drupal_add_js('misc/media/jquery.jeditable.js');
	drupal_add_js('misc/media/jquery.autotab-1.1b.js');
    drupal_add_js('sites/all/libraries/barcode/jquery-barcode.min.js');
	drupal_add_js('
		var oTable;
		var pathutama = "'.base_path().'";
		var pathfile = "'.base_path().file_directory_path().'/";
		var alamatupdate = "";
		var barcodesama = false;
		var renderer = "bmp";
        var btype = "ean13";
        var settings = {
            output:renderer,
            barWidth: 1,
            barHeight: 50
        };
        var statusstok = 0;
		function tampilkantabelproduk(){
			oTable = $("#tabel_produk").dataTable( {
				"bJQueryUI": true,
				"bAutoWidth": false,
				"sPaginationType": "full_numbers",
				"bInfo": true,
				"processing": true,
        "serverSide": true,
				"ajax": Drupal.settings.basePath + "sites/all/modules/datapelanggan/server_processing.php?request_data=produk&statusstok="+ statusstok,
				"aoColumns": [
					{ "bSortable": false },null,null,{ "bVisible": false },null,null,null,null,
					null,{ "bVisible": false },{ "bVisible": false },
					{ "bVisible": false },null,null,null,{ "bVisible": false },null
				],
				"aLengthMenu": [[100, 200, 300, -1], [100, 200, 300, "All"]],
				"iDisplayLength": 100,
				"aaSorting": [[0, "asc"]],
				buttons: [
        	{
          	extend: \'colvis\',
            columns: \':not(:first-child)\'
          }, "copy", "excel", "print"
        ],
				"sDom": \'<"button-div"B><"H"lfr>t<"F"ip>\',
				"createdRow": function ( row, data, index ) {
					row.id = data[(data.length - 1)];
          $("td", row).eq(3).addClass("center").editable(alamatupdate, {
          	"submitdata": function ( value, settings ) {
            	return { "row_id": this.parentNode.getAttribute("id"), "kol_id": 3 };
            },
            "width": "120px",
            "height": "20px",
            "submit": "Ok",
            "select": true,
            "indicator": "Menyimpan...",
            "tooltip": "Klik untuk mengubah..."
          });
          $("td", row).eq(4).addClass("center").editable(alamatupdate, {
          	"submitdata": function ( value, settings ) {
            	return { "row_id": this.parentNode.getAttribute("id"), "kol_id": 4 };
            },
            "width": "80px",
            "height": "20px",
            "submit": "Ok",
            "select": true,
            "indicator": "Menyimpan...",
            "tooltip": "Klik untuk mengubah..."
          });
          $("td", row).eq(5).addClass("left").editable(alamatupdate, {
          	"submitdata": function ( value, settings ) {
            	return { "row_id": this.parentNode.getAttribute("id"), "kol_id": 5 };
            },
            "width": "220px",
            "height": "20px",
            "submit": "Ok",
            "select": true,
            "indicator": "Menyimpan...",
            "tooltip": "Klik untuk mengubah..."
          });
          $("td", row).eq(6).addClass("angka").editable(alamatupdate, {
          	"submitdata": function ( value, settings ) {
            	return { "row_id": this.parentNode.getAttribute("id"), "kol_id": 6 };
            },
            "data": function (value, settings) {
            	var newValue = value.replace(".","");
            	newValue = newValue.replace(",",".");
        			return newValue;
    				},
            "width": "70px",
            "height": "20px",
            "submit": "Ok",
            "select": true,
            "indicator": "Menyimpan...",
            "tooltip": "Klik untuk mengubah..."
          });
          $("td", row).eq(7).addClass("angka").editable(alamatupdate, {
          	"submitdata": function ( value, settings ) {
            	return { "row_id": this.parentNode.getAttribute("id"), "kol_id": 7 };
            },
            "data": function (value, settings) {
            	var newValue = value.replace(".","");
            	newValue = newValue.replace(",",".");
        			return newValue;
    				},
            "width": "70px",
            "height": "20px",
            "submit": "Ok",
            "select": true,
            "indicator": "Menyimpan...",
            "tooltip": "Klik untuk mengubah..."
          });
          $("td", row).eq(8).addClass("angka").editable(alamatupdate, {
          	"submitdata": function ( value, settings ) {
            	return { "row_id": this.parentNode.getAttribute("id"), "kol_id": 8 };
            },
            "data": function (value, settings) {
            	var newValue = value.replace(".","");
            	newValue = newValue.replace(",",".");
        			return newValue;
    				},
            "width": "60px",
            "height": "20px",
            "submit": "Ok",
            "select": true,
            "indicator": "Menyimpan...",
            "tooltip": "Klik untuk mengubah..."
          });;
          $("td", row).eq(9).addClass("center");
          $("td", row).eq(10).addClass("center");
          $("td", row).eq(11).addClass("angka");
				}
			});
		}
		
		function simpankategori(){
			if ($("#kodekategori").val() != "" && $("#kategori").val() != ""){
				var request = new Object();
				request.kodekategori = $("#kodekategori").val();
				request.kategori = $("#kategori").val();
				request.keterangan = $("#keterangan").val();
				request.asal = "Subkategori";
				alamat = pathutama + "dataproduk/simpankategori";
				$.ajax({ 
					type: "POST",
					url: alamat,
					data: request, 
					cache: false, 
					success: function(data){
						$("#idkategori").append("<option value=\""+ data +"\">"+ request.kodekategori +"-"+ request.kategori +"</option>");
						$("#kodekategori").val("");
						$("#kategori").val("");
						$("#keterangan").val("");
						$("#dialogtambahkategori").dialog("close");
						$("#idkategori").val(data);
						filtersubkategori();
					}
				});
			}else{
				$("#tambahkategoriform").submit();
			}	
		}
		
		function simpansubkategori(){
			if ($("#kodesubkategori").val() != "" && $("#subkategori").val() != ""){
				var request = new Object();
				request.idkategori = $("#idkategori2").val();
				request.kodesubkategori = $("#kodesubkategori").val();
				request.subkategori = $("#subkategori").val();
				request.keterangan = $("#keterangansub").val();
				request.asal = "Produk";
				alamat = pathutama + "dataproduk/simpansubkategori";
				$.ajax({ 
					type: "POST",
					url: alamat,
					data: request, 
					cache: false, 
					success: function(data){
						$("#idsubkategori").append("<option value=\""+ data +"\">"+ request.kodesubkategori +"-"+ request.subkategori +"</option>");
						$("#kodesubkategori").val("");
						$("#subkategori").val("");
						$("#keterangansub").val("");
						$("#dialogtambahsubkategori").dialog("close");
						$("#idsubkategori").val(data);
						ubahkodebarang();
					}
				});
			}else{
				$("#tambahsubkategoriform").submit();
			}	
		}
		
		function simpansupplier(){
			if ($("#kodesupplier").val() != "" && $("#supplier").val() != ""){
				var request = new Object();
				request.kodesupplier = $("#kodesupplier").val();
				request.namasupplier = $("#namasupplier").val();
				request.keterangan = $("#keterangan").val();
				request.asal = "supplier";
				alamat = pathutama + "datasupplier/simpansupplier";
				$.ajax({ 
					type: "POST",
					url: alamat,
					data: request, 
					cache: false, 
					success: function(data){
						$("#idsupplier").append("<option value=\""+ data +"\">"+ request.kodesupplier +"-"+ request.namasupplier +"</option>");
						$("#kodesupplier").val("");
						$("#supplier").val("");
						$("#keterangan").val("");
						$("#dialogtambahsupplier").dialog("close");
						$("#idsupplier").val(data);
					}
				});
			}else{
				$("#tambahsupplierform").submit();
			}	
		}
		function simpansatuan(){
			if ($("#namasatuan").val() != ""){
				var request = new Object();
				request.namasatuan = $("#namasatuan").val();
				alamat = pathutama + "dataproduk/simpansatuan";
				$.ajax({ 
					type: "POST",
					url: alamat,
					data: request, 
					cache: false, 
					success: function(data){
						if (data != "error"){
							$("#satuan").append("<option value=\""+ request.namasatuan +"\">"+ request.namasatuan +"</option>");
							$("#namasatuan").val("");
							$("#dialogtambahsatuan").dialog("close");
							$("#satuan").val(data);
						}else{
							alert("Satuan ini sudah ada..!!");	
						}
					}
				});
			}else{
				$("#tambahsatuanform").submit();
			}	
		}
		function tutupformtambahproduk(){
			$("#formproduk").validationEngine("hide");
			$("#formpenambahanproduk").fadeOut("slow");
		}
		function filtersubkategori(){
			var request = new Object();
			request.idkategori = 	$("#idkategori").val();
			var alamat = pathutama + "dataproduk/filterkategori";
			$.ajax({ 
				type: "POST",
				url: alamat,
				data: request, 
				cache: false, 
				success: function(data){
					$("#idsubkategori").empty();
					$("#idsubkategori").append(data);
					ubahkodebarang();
				}
			});
		}
		function ubahkodebarang(){
			var kategori = "";
			$("#idkategori option:selected").each(function () {
				kategori = $(this).text();
			}); 
			var pecahkategori = kategori.split("-");
			var subkategori = "";
			$("#idsubkategori option:selected").each(function () {
				subkategori = $(this).text();
			});
			pecahsubkategori = subkategori.split("-");
			kodebarang = pecahkategori[0] +"-"+ pecahsubkategori[0];
			var request = new Object();
			request.kodealternatif = 	kodebarang;
			var alamat = pathutama + "dataproduk/cekkodealternatif";
			$.ajax({ 
				type: "POST",
				url: alamat,
				data: request, 
				cache: false, 
				success: function(data){
					$("#kodepoduk").val(kodebarang +"-"+ data.trim());
				}
			});
		}
		function hitungmargin(asal){
			if (asal == "hargapokok"){
				if ($("#margin").val() > 0 && $("#hargapokok").val() > 0){
					var hargajual = $("#hargapokok").val() * (100/(100-$("#margin").val()));
					$("#hargajual").val(Math.round(hargajual));
				}
			}else if (asal == "hargajual"){
				if ($("#hargapokok").val() > 0 && $("#hargajual").val() > 0){
					var margin = (($("#hargajual").val() - $("#hargapokok").val())/$("#hargajual").val())*100;
					$("#margin").val(Math.round(margin));
				}
			}else if (asal == "margin"){
				if ($("#hargapokok").val() > 0 && $("#margin").val() > 0){
					var hargajual = $("#hargapokok").val() * (100/(100-$("#margin").val()));
					$("#hargajual").val(Math.round(hargajual));
				}
			}
		}
		function cek_barcode(field, rules, i, options){
			if (field.val() != ""){
				$("#validating").html("<img src = \""+ pathutama +"misc/media/images/loading.gif\">");
				$("#validating").fadeIn("fast",function(){
					var request = new Object();
				 	request.barcode = field.val(); 
					cekbarcode = pathutama +"dataproduk/cekbarcode";
					$.ajax({
						type: "POST", 
						url: cekbarcode,
						data: request,
						cache: false,
						success: function(data){
							if (data == "sama"){
								$("#validating").fadeOut("fast",function(){
									$("#barcode").validationEngine("showPrompt", "Barcode ini sudah digunakan..!!", "error", "topRight", true);
									$("#barcode").select();
									barcodesama = true;
								})
							}else{
								if (data == "taksama"){
									barcodesama = false;
								}
								$("#validating").fadeOut("fast");
							}
						}
					});
				})
			}		
		}
		function simpanproduk(){
			if ($("#barcode").val() != "" && !barcodesama && $("#namaproduk").val() != "" && $("#hargapokok").val() > 0 && $("#hargajual").val() > 0){
				var request = new Object();
			 	request.barcode = $("#barcode").val();
			 	request.alt_code = $("#kodepoduk").val();
			 	request.idkategori = $("#idkategori").val();
			 	request.idsubkategori = $("#idsubkategori").val();
			 	request.idsupplier = $("#idsupplier").val();
			 	request.namaproduk = $("#namaproduk").val();
			 	request.hargapokok = $("#hargapokok").val();
			 	request.hargajual = $("#hargajual").val();
			 	request.margin = $("#margin").val();
			 	request.minstok = $("#minstok").val();
			 	request.maxstok = $("#maxstok").val();
			 	request.stok = $("#stok").val();
			 	request.satuan = $("#satuan").val();
			 	request.keterangan = $("#keteranganproduk").val();
				alamatsimpan = pathutama +"dataproduk/simpanproduk";
				$.ajax({
					type: "POST", 
					url: alamatsimpan,
					data: request,
					cache: false, 
					success: function(){
						$("#namaproduk").val("");
						$("#hargapokok").val("");
						$("#hargajual").val("");
						$("#margin").val("");
						$("#keteranganproduk").val("");
						$("#stok").val("");
						$("#minstok").val("");
						$("#maxstok").val("");
						ubahkodebarang();
						$("#barcode").val("");
						$("#refreshprodukbaru").click();
						alamatbarcode = pathutama +"penjualan/getrandomstring";
						$.ajax({
							type: "POST",
							url: alamatbarcode,
							cache: false,
							success: function(data){
								$("#barcode").val(data.trim());
								$("#barcode").select();
							}
						});
					}
				});
			}else{
				$("#formproduk").validationEngine("validate");
			}
		}
		function editproduk(idproduk){
			if (idproduk > 0){
				window.location = pathutama + "dataproduk/editproduk?idproduk="+ idproduk;
			}
		}
		function view_status(kondisistok){
		    if (kondisistok != "all"){
				window.location = pathutama + "dataproduk/produk?statusstok="+ kondisistok;
			}else{
				window.location = pathutama + "dataproduk/produk";
			}
		}
		$(document).ready(function() {
			alamatupdate = Drupal.settings.basePath + "dataproduk/updateproduk";
			statusstok = Drupal.settings.statusstokfilter;
			$("#dialogtambahkategori").dialog({
				modal: true,
				width: 350,
				resizable: false,
				autoOpen: false,
				position: ["auto","auto"],
				open: function(event, ui) {
					$("#kodekategori").focus();
				},
				close: function(){
					$("#tambahkategoriform").validationEngine("hide");
				}
			});
			$("#dialogtambahsubkategori").dialog({
				modal: true,
				width: 350,
				resizable: false,
				autoOpen: false,
				position: ["auto","auto"],
				open: function(event, ui) {
					$("#kodesubkategori").focus();
				},
				close: function(){
					$("#tambahsubkategoriform").validationEngine("hide");
				}
			});
			$("#dialogtambahsupplier").dialog({
				modal: true,
				width: 350,
				resizable: false,
				autoOpen: false,
				position: ["auto","auto"],
				open: function(event, ui) {
					$("#kodesupplier").focus();
				},
				close: function(){
					$("#tambahsupplierform").validationEngine("hide");
				}
			});
			$("#dialogtambahsatuan").dialog({
				modal: true,
				width: 350,
				resizable: false,
				autoOpen: false,
				position: ["auto","auto"],
				open: function(event, ui) {
					$("#namasatuan").select();
				},
				close: function(){
					$("#tambahsatuanform").validationEngine("hide");
				}
			});
			/*TableToolsInit.sSwfPath = pathutama +"misc/media/datatables/swf/ZeroClipboard.swf";*/
			
			$("#formsubkategori").validationEngine();
			$("#tambahkategoriform").validationEngine();
			$("#tambahsubkategoriform").validationEngine();
			$("button").button();
			$("#addkategori").click(function(){
      	$("#dialogtambahkategori").dialog("open");
      	$(".ui-dialog-title").css("font-size","15px");
      	$("#formproduk").validationEngine("hide");
      	return false;
      });
      $("#addsubkategori").click(function(){
      	$("#dialogtambahsubkategori").dialog("open");
      	$("#idkategori2").val($("#idkategori").val());
      	$(".ui-dialog-title").css("font-size","15px");
      	$("#formproduk").validationEngine("hide");
      	return false;
      });
      $("#addsupplier").click(function(){
      	$("#dialogtambahsupplier").dialog("open");
      	$(".ui-dialog-title").css("font-size","15px");
      	$("#formproduk").validationEngine("hide");
      	return false;
      });
      $("#addsatuan").click(function(){
      	$("#dialogtambahsatuan").dialog("open");
      	$(".ui-dialog-title").css("font-size","15px");
      	$("#formproduk").validationEngine("hide");
      	return false;
      });
      $("#tambahprodukbaru").button({
	      icons: {
	          primary: "ui-icon-plusthick"
	      },
	      text: false
      }).click(function(){
      	$("#formpenambahanproduk").fadeIn("slow",function(){
      		alamatbarcode = pathutama +"penjualan/getrandomstring";
      		$.ajax({
				type: "POST",
				url: alamatbarcode,
				cache: false,
				success: function(data){
					$("#barcode").val(data.trim());
      				$("#barcode").select();
      			}
      		});
      	});
      });
      $("#refreshprodukbaru").button({
	      icons: {
	          primary: "ui-icon-refresh"
	      },
	      text: false
      }).click(function(){
      	$("#tempattabel").html("Memuat Data Produk...<img src = \""+ pathutama +"misc/media/images/loading.gif\">");
      	var request = new Object();
			 	request.asal = "produk"; 
				alamattabelproduk = pathutama +"dataproduk/isitableproduk";
				$.ajax({
					type: "POST", 
					url: alamattabelproduk,
					data: request,
					cache: false,
					success: function(data){
						$("#tempattabel").fadeOut("fast",function(){
							$("#tempattabel").html(data);
							tampilkantabelproduk();
							$("#tempattabel").fadeIn("fast");
						});
					}
				});
      });
      $("#kodekategori, #kodesubkategori, #kodesupplier, #namasatuan").autotab_filter({
				format: "alphanumeric",
				uppercase: true,
				nospace: true
			});
			$("#hargapokok,#hargajual,#margin,#minstok,#maxstok,#stok").autotab_filter({
				format: "numeric",
				nospace: true
			});
			//$("#main h2").css("width","100%");
			//$("#main h2").css("float","left");
			$("#barcode").keypress(function(e) {
			    if(e.keyCode == 13) {
			    	$("#formproduk").validationEngine("validateField", "#barcode");
			    	ubahkodebarang();
			    	$("#idkategori").focus();
			    }
			});
			$("#formproduk").validationEngine();
			tampilkantabelproduk();
		})
	','inline');
	drupal_set_title('<span style="float: left;margin-right: 6px;">Data Produk</span><button id="tambahprodukbaru" class="tombolutama">Tambah Produk</button><button id="refreshprodukbaru" class="tombolutama">Refresh Tabel Produk</button>');
	//Pilihan Kategori
	$result = db_query("SELECT idkategori,kodekategori,kategori FROM kategori ORDER BY kodekategori,kategori");
	$pilihankategori = '<select id="idkategori" name="idkategori" onkeyup="filtersubkategori();" onchange="filtersubkategori();">';
	$i = 0;
	while ($data = db_fetch_object($result)){
		if ($i == 0){
			$idkategoriawal = $data->idkategori;
		}
		$pilihankategori .= '<option value="'.$data->idkategori.'">'.$data->kodekategori.'-'.$data->kategori.'</option>';
		$i++;
	}
	$pilihankategori .= '</select>';
	$buttonadd = '<img id="addkategori" title="Tambah Kategori Baru" src="'.base_path().'misc/media/images/add-small.ico">';
	//Pilihan Sub Kategori
	$result = db_query("SELECT idsubkategori,kodesubkategori,subkategori FROM subkategori WHERE idkategori='%d' 
	ORDER BY kodesubkategori,subkategori",$idkategoriawal);
	$pilihansubkategori = '<select id="idsubkategori" name="idsubkategori" onkeyup="ubahkodebarang();" onchange="ubahkodebarang();">';
	while ($data = db_fetch_object($result)){
		$pilihansubkategori .= '<option value="'.$data->idsubkategori.'">'.$data->kodesubkategori.'-'.$data->subkategori.'</option>';
	}
	$pilihansubkategori .= '</select>';
	$buttonadd2 = '<img id="addsubkategori" title="Tambah Subkategori Baru" src="'.base_path().'misc/media/images/add-small.ico">';
	//Pilihan Supplier
	$result = db_query("SELECT idsupplier,kodesupplier,namasupplier FROM supplier ORDER BY kodesupplier,namasupplier");
	$pilihansupplier = '<select id="idsupplier" name="idsupplier" >';
	while ($data = db_fetch_object($result)){
		$pilihansupplier .= '<option value="'.$data->idsupplier.'">'.$data->kodesupplier.'-'.$data->namasupplier.'</option>';
	}
	$pilihansupplier .= '</select>';
	$buttonadd3 = '<img id="addsupplier" title="Tambah Supplier Baru" src="'.base_path().'misc/media/images/add-small.ico">';
	//Pilihan Satuan
	$result = db_query("SELECT satuan FROM satuan ORDER BY satuan");
	$pilihansatuan = '<select id="satuan" name="satuan" >';
	while ($data = db_fetch_object($result)){
		if ($data->satuan == "PCS"){
			$pilihansatuan .= '<option selected value="'.$data->satuan.'">'.$data->satuan.'</option>';
		}else{
			$pilihansatuan .= '<option value="'.$data->satuan.'">'.$data->satuan.'</option>';
		}
	}
	$pilihansatuan .= '</select>';
	$buttonadd4 = '<img id="addsatuan" title="Tambah satuan Baru" src="'.base_path().'misc/media/images/add-small.ico">';
	//Pilihan Kategori untuk Dialog Box Tambah Sub kategori
	$result = db_query("SELECT idkategori,kodekategori,kategori FROM kategori ORDER BY kodekategori,kategori");
	$pilihankategori2 = '<select disabled="disabled" id="idkategori2" name="idkategori2">';
	while ($data = db_fetch_object($result)){
		$pilihankategori2 .= '<option value="'.$data->idkategori.'">'.$data->kodekategori.'-'.$data->kategori.'</option>';
	}
	$pilihankategori2 .= '</select>';
	//idsupplier,idkategori,idsubkategori,barcode,alt_code,namaproduct,hargapokok,hargajual,margin,minstok,maxstok,stok,satuan,berat,keterangan
	$barcloseform = '<div id="formpenambahanproduk"><div id="barplace"><div id="barcloseform" onclick="tutupformtambahproduk();" title="Tutup form penambahan data produk">Tutup Form</div></div>';
	$viewtabelproduk = $barcloseform.'<div id="formplace" class="produk"><form id="formproduk" action="'.base_path().'dataproduk/simpanproduk" method="post">';
	$viewtabelproduk .= '<label>BARCODE</label><input type="text" class="validate[required,funcCall[cek_barcode]]" id="barcode" name="barcode"><div id="validating"></div>';
	$viewtabelproduk .= '<label>KODE ALTERNATIF</label><input readonly="readonly" type="text" id="kodepoduk" name="kodeproduk">';
	$viewtabelproduk .= '<label>KATEGORI</label>'.$pilihankategori.$buttonadd;
	$viewtabelproduk .= '<label>SUBKATEGORI</label>'.$pilihansubkategori.$buttonadd2;
	$viewtabelproduk .= '<label>SUPPLIER</label>'.$pilihansupplier.$buttonadd3;
	$viewtabelproduk .= '<label>NAMA PRODUK</label><input type="text" class="validate[required]" id="namaproduk" name="namaproduk">';
	$viewtabelproduk .= '<label>HARGA POKOK</label><input type="text" class="validate[required]" id="hargapokok" name="hargapokok" onkeyup="hitungmargin(\'hargapokok\')">';
	$viewtabelproduk .= '<label>HARGA JUAL</label><input type="text" class="validate[required]" id="hargajual" name="hargajual" onkeyup="hitungmargin(\'hargajual\')">';
	$viewtabelproduk .= '<label>MARGIN HARGA</label><input type="text" maxlength="2" id="margin" name="margin" onkeyup="hitungmargin(\'margin\')">';
	$viewtabelproduk .= '<label>MINIMUM STOK</label><input type="text" id="minstok" name="minstok">';
	$viewtabelproduk .= '<label>MAKSIMUM STOK</label><input type="text" id="maxstok" name="maxstok">';
	$viewtabelproduk .= '<label>STOK SAAT INI</label><input type="text" id="stok" name="stok">';
	$viewtabelproduk .= '<label>SATUAN</label>'.$pilihansatuan.$buttonadd4;
	$viewtabelproduk .= '<label>KETERANGAN</label><input type="text" id="keteranganproduk" name="keteranganproduk">';
	$viewtabelproduk .= '</form><div><button onclick="simpanproduk();" style="font-size:12px;">Tambah Produk</button></div></div></div>';
	$pilihanstatus = '<div id="pilihanstatus">';
	$pilihanstatus .= '<img onclick="view_status(\'minimum\')" title="Klik untuk melihat produk dengan stok dibawah minimum" src="'.base_path().'misc/media/images/statusmerah.png">';
	$pilihanstatus .= '<img onclick="view_status(\'maksimum\')" title="Klik untuk melihat produk dengan stok diatas maksimum" src="'.base_path().'misc/media/images/statuskuning.png">';
	$pilihanstatus .= '<img onclick="view_status(\'aman\')" title="Klik untuk melihat produk dengan stok aman" src="'.base_path().'misc/media/images/statushijau.png">';
	$pilihanstatus .= '<img onclick="view_status(\'all\')" title="Klik untuk melihat seluruh produk" src="'.base_path().'misc/media/images/alldata.png">';
	$pilihanstatus .= '</div>';
	if ($_GET["statusstok"]){
		$STATUSTOK = $_GET["statusstok"];
		drupal_add_js(
			array(
				'statusstokfilter' => $STATUSTOK,
			),
			'setting'
		);
		$viewtabelproduk .= $pilihanstatus.'<div id="tempattabel" style="width: 100%;">'.tabel_produk($STATUSTOK).'</div>';
	}else{
		drupal_add_js(
			array(
				'statusstokfilter' => 0,
			),
			'setting'
		);
		$viewtabelproduk .= $pilihanstatus.'<div id="tempattabel" style="width: 100%;">'.tabel_produk().'</div>';
	}
	$viewtabelproduk .= '<div id="dialogtambahkategori" title="TAMBAH KATEGORI PRODUK">';
	$viewtabelproduk .= '<form id="tambahkategoriform"><div><label>KODE</label><input type="text" class="validate[required]" id="kodekategori" name="kodekategori"></div>';
	$viewtabelproduk .= '<div><label>KATEGORI</label><input class="validate[required]" type="text" id="kategori" name="kategori"></div>';
	$viewtabelproduk .= '<div><label>KETERANGAN</label><input type="text" id="keterangan" name="keterangan"></div></form>';
	$viewtabelproduk .= '<div><button id="tambahkategori" onclick="simpankategori();">Tambah Kategori</button></div>';
	$viewtabelproduk .= '</div>';
	$viewtabelproduk .= '<div id="dialogtambahsubkategori" title="TAMBAH SUBKATEGORI PRODUK">';
	$viewtabelproduk .= '<form id="tambahsubkategoriform"><div>';
	$viewtabelproduk .= '<div><label>KATEGORI</label>'.$pilihankategori2.'</div>';
	$viewtabelproduk .= '<div><label>KODE SUBKATEGORI</label><input type="text" class="validate[required]" id="kodesubkategori" name="kodesubkategori"></div>';
	$viewtabelproduk .= '<div><label>SUBKATEGORI</label><input type="text" class="validate[required]" id="subkategori" name="subkategori"></div>';
	$viewtabelproduk .= '<div><label>KETERANGAN</label><input type="text" id="keterangansub" name="keterangansub"></div></div></form>';
	$viewtabelproduk .= '<div><button id="tambahsubkategori" onclick="simpansubkategori();">Tambah Sub Kategori</button></div>';
	$viewtabelproduk .= '</div>';
	$viewtabelproduk .= '<div id="dialogtambahsupplier" title="TAMBAH SUPPLIER">';
	$viewtabelproduk .= '<form id="tambahsupplierform"><div><label>KODE SUPPLIER</label><input type="text" class="validate[required]" id="kodesupplier" name="kodesupplier"></div>';
	$viewtabelproduk .= '<div><label>NAMA SUPPLIER</label><input class="validate[required]" type="text" id="namasupplier" name="namasupplier"></div>';
	$viewtabelproduk .= '<div><label>TELP</label><input type="text" id="telp" name="telp"></div>';
	$viewtabelproduk .= '<div><label>ALAMAT</label><input type="text" id="alamat" name="alamat"></div>';
	$viewtabelproduk .= '<div><label>EMAIL</label><input type="text" id="email" name="email"></div>';
	$viewtabelproduk .= '</form>';
	$viewtabelproduk .= '<div><button id="tambahsupplier" onclick="simpansupplier();">Tambah Supplier</button></div></div>';
	$viewtabelproduk .= '<div id="dialogtambahsatuan" title="TAMBAH SATUAN">';
	$viewtabelproduk .= '<form id="tambahsatuanform"><div><label>SATUAN</label><input type="text" class=\"validate[required]\" id="namasatuan" name="namasatuan"></div>';
	$viewtabelproduk .= '</form>';
	$viewtabelproduk .= '<div><button id="tambahsatuan" onclick="simpansatuan();">Tambah Satuan</button></div>';
	$viewtabelproduk .= '</div>';
	return $viewtabelproduk;
}
function cek_kode_alt($kodealt = null){
	if ($_POST["kodealternatif"] OR !(is_null($kodealt))){
		if (is_null($kodealt)){
			$katacari = '%'.$_POST["kodealternatif"].'%';
		}else{
			$katacari = '%'.$kodealt.'%';
		}
		$result = db_query("SELECT COUNT(*) AS jumlahproduk FROM product WHERE alt_code LIKE '$katacari'");
		$data = db_fetch_object($result);
		if ($data->jumlahproduk){
			$jumlahkode = (($data->jumlahproduk)*1) + 1;
		}else{
			$jumlahkode = 1;
		}
		if (is_null($kodealt)){
			print $jumlahkode;
		}else{
			return $jumlahkode;
		}
	}
	exit();
}
function filter_kategori(){
	if ($_POST["idkategori"]){
		$result = db_query("SELECT idsubkategori,kodesubkategori,subkategori FROM subkategori WHERE idkategori='%d'",
		$_POST["idkategori"]);
		$tambahanpilihan = '';
		while($data = db_fetch_object($result)){
			$tambahanpilihan .= '<option value="'.$data->idsubkategori.'">'.$data->kodesubkategori.'-'.$data->subkategori.'</option>';	
		}
		print $tambahanpilihan;
	}
	exit();	
}

function tabel_produk($statusstok = null){
	$tabelproduk = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabel_produk">';
	$tabelproduk .= '<thead>';
	$tabelproduk .= '<tr>';
	$tabelproduk .= '<th class="tablebutton"></th>';
	$tabelproduk .= '<th>KATEGORI</th>';
	$tabelproduk .= '<th>SUBKATEGORI</th>';
	$tabelproduk .= '<th>SUPPLIER</th>';
	$tabelproduk .= '<th>BARCODE</th>';
	$tabelproduk .= '<th>KODE ALT</th>';
	$tabelproduk .= '<th>NAMA</th>';
	$tabelproduk .= '<th>H.POKOK</th>';
	$tabelproduk .= '<th>H.JUAL</th>';
	$tabelproduk .= '<th>MARGIN</th>';
	$tabelproduk .= '<th>MIN</th>';
	$tabelproduk .= '<th>MAX</th>';
	$tabelproduk .= '<th>STOK</th>';
	$tabelproduk .= '<th>STATUS</th>';
	$tabelproduk .= '<th>SATUAN</th>';
	$tabelproduk .= '<th>KETERANGAN</th>';
	$tabelproduk .= '<th>TOTAL(Rp.)</th>';
	$tabelproduk .= '</tr>';
	$tabelproduk .= '</thead>';
	$tabelproduk .= '<tbody>';
	$tabelproduk .= '</tbody>';
	$totalNilaiBarang = calculateTotalNilaiBarang($statusstok);
	$tabelproduk .= '<tfoot>';
	$tabelproduk .= '<tr>';
	$tabelproduk .= '<th class="right" colspan="16">TOTAL NILAI BARANG :</th>';
	$tabelproduk .= '<th class="right"><strong><div id="total-nilai-barang">Rp. '.number_format($totalNilaiBarang,2,',','.').'</div></strong></th>';
	$tabelproduk .= '</tr>';
	$tabelproduk .= '</tfoot>';
	$tabelproduk .= '</table>';
	if ($_POST["asal"]){
		print $tabelproduk;
	}else{
		return $tabelproduk;
	}
}
function nama_field($field_id = null,$namafield_id = null,$nama_field = null,$namatabel = null){
	if ($field_id AND $namatabel AND $nama_field){
		$result = db_query("SELECT $nama_field AS namafield FROM $namatabel WHERE $namafield_id='$field_id'");
		$data = db_fetch_object($result);
		return $data->namafield;
	}else{
		return "-";
	}
}
function simpan_satuan(){
	if ($_POST["namasatuan"]){
		$result = db_query("SELECT COUNT(*) AS jumlahsama FROM satuan WHERE satuan='%s'",$_POST["namasatuan"]);
		$data = db_fetch_object($result);
		if (!($data->jumlahsama > 0)){
			db_query("INSERT INTO satuan VALUES ('%s')",$_POST["namasatuan"]);
			print $_POST["namasatuan"];
		}else{
			print "error";	
		}
	}
	exit();	
}
function cek_barcode(){
	if ($_POST["barcode"]){
		$result = db_query("SELECT COUNT(*) AS jumlahsama FROM product WHERE barcode='%s'",$_POST["barcode"]);
		$data = db_fetch_object($result);
		if ($data->jumlahsama > 0){
			print "sama";	
		}else{
			print "taksama";
		}	
	}	
	exit();
}
function cek_barcode2(){
	if ($_POST["barcode"] AND $_POST["idproduk"]){
		$BARCODE = $_POST["barcode"];
		$IDPRODUK = $_POST["idproduk"];
		$sql = "SELECT COUNT(*) AS jumlahsama FROM product WHERE barcode='$BARCODE' AND idproduct<>'$IDPRODUK'";
		$result = db_query("SELECT COUNT(*) AS jumlahsama FROM product WHERE barcode='%s' AND idproduct<>'%d'",$_POST["barcode"],$_POST["idproduk"]);
		$data = db_fetch_object($result);
		if ($data->jumlahsama > 0){
			print "sama";	
		}else{
			print "taksama";
		}	
	}
	//print $_POST["idproduk"]." ".$_POST["barcode"];
	exit();
}
function simpan_produk(){
	/*idsupplier,idkategori,idsubkategori,barcode,alt_code,namaproduct,hargapokok,
	hargajual,margin,minstok,maxstok,stok,satuan,berat,keterangan*/
	$altCode = remove_utf8_bom($_POST["alt_code"]);
	if (isset($_POST["idproduk"])){
		print $_POST["idproduk"];
		if (isset($_POST["barcode"]) AND isset($_POST["namaproduk"])){
			$result = db_query("SELECT idkategori,idsubkategori,stok,hargapokok FROM product WHERE idproduct='%d'",$_POST["idproduk"]);
			$data = db_fetch_object($result);
			$STOKSEBELUM = $data->stok;
			$HARGAPOKOK = $data->hargapokok;
			if (!($data->idkategori == $_POST["idkategori"] AND $data->idsubkategori == $_POST["idsubkategori"])){
				$resultkategori = db_query("SELECT kodekategori FROM kategori WHERE idkategori='%d'",$_POST["idkategori"]);
				$datakategori = db_fetch_object($resultkategori);
				$resultsubkategori = db_query("SELECT kodesubkategori FROM subkategori WHERE idsubkategori='%d'",$_POST["idsubkategori"]);
				$datasubkategori = db_fetch_object($resultsubkategori);
				$kodealt = $datakategori->kodekategori."-".$datasubkategori->kodesubkategori;
				if (isset($_POST["alt_code"])){
					$kodealternatif = $altCode;
				}else{
					$kodealternatif = $kodealt.cek_kode_alt(remove_utf8_bom($kodealt));
				}
				//$sql = "UPDATE product SET idsupplier='%d',idkategori='%d',idsubkategori='%d',barcode='%s',alt_code='%s',namaproduct='%s',hargapokok='%f',hargajual='%f',margin='%f',minstok='%f',maxstok='%f',stok='%f',satuan='%s',keterangan='%s' WHERE idproduct='%d'";
				//$nilai = $_POST["idsupplier"].'-'.$_POST["idkategori"].'-'.$_POST["idsubkategori"].'-'.$_POST["barcode"].'-'.$kodealternatif.'-'.$_POST["namaproduk"].'-'.$_POST["hargapokok"].'-'.$_POST["hargajual"].'-'.$_POST["margin"].'-'.$_POST["minstok"].'-'.$_POST["maxstok"].'-'.$_POST["stok"].'-'.$_POST["satuan"].'-'.$_POST["keterangan"].'-'.$_POST["idproduk"];
				db_query("UPDATE product SET idsupplier='%d',idkategori='%d',idsubkategori='%d',barcode='%s',alt_code='%s',
				namaproduct='%s',hargapokok='%f',hargajual='%f',margin='%f',minstok='%f',maxstok='%f',stok='%f',satuan='%s',keterangan='%s' WHERE idproduct='%d'",
				$_POST["idsupplier"],$_POST["idkategori"],$_POST["idsubkategori"],$_POST["barcode"],$kodealternatif,
				$_POST["namaproduk"],$_POST["hargapokok"],$_POST["hargajual"],$_POST["margin"],$_POST["minstok"],$_POST["maxstok"],$_POST["stok"],
				$_POST["satuan"],$_POST["keterangan"],$_POST["idproduk"]);
				//print $sql;
				if ($_POST["hargapokok"] <> $HARGAPOKOK){
					global $user;
					db_query("INSERT INTO historyhargabeli (hargasebelum, hargasesudah, uid) VALUES('%f','%f','%d')",
					$HARGAPOKOK,$_POST["hargapokok"],$user->uid);
				}
			}else{
				db_query("UPDATE product SET idsupplier='%d',barcode='%s',
				namaproduct='%s',hargapokok='%f',hargajual='%f',margin='%f',minstok='%f',maxstok='%f',stok='%f',satuan='%s',keterangan='%s' WHERE idproduct='%d'",
				$_POST["idsupplier"],$_POST["barcode"],
				$_POST["namaproduk"],$_POST["hargapokok"],$_POST["hargajual"],$_POST["margin"],$_POST["minstok"],$_POST["maxstok"],$_POST["stok"],
				$_POST["satuan"],$_POST["keterangan"],$_POST["idproduk"]);
				if ($_POST["hargapokok"] <> $HARGAPOKOK){
					global $user;
					db_query("INSERT INTO historyhargabeli (hargasebelum, hargasesudah, uid) VALUES('%f','%f','%d')",
					$HARGAPOKOK,$_POST["hargapokok"],$user->uid);
				}
			}
			$SELISIHSTOK = ($_POST["stok"]*1)-$STOKSEBELUM;
			if ($SELISIHSTOK < 0){
				$KELUAR = abs($SELISIHSTOK);
				$KETERANGAN = 'Perubahan Stock-Stok Berkurang';
				db_query("INSERT INTO transaksistock (idproduk, stocksebelum, keluar, stocksetelah, keterangan) VALUES 
				('%d', '%f', '%f', '%f', '%s')",$_POST["idproduk"],$STOKSEBELUM,$KELUAR,$_POST["stok"],$KETERANGAN);
			}else{
				$MASUK = $SELISIHSTOK;
				$KETERANGAN = 'Perubahan Stock-Stok Bertambah';
				db_query("INSERT INTO transaksistock (idproduk, stocksebelum, masuk, stocksetelah, keterangan) VALUES 
				('%d', '%f', '%f', '%f', '%s')",$_POST["idproduk"],$STOKSEBELUM,$MASUK,$_POST["stok"],$KETERANGAN);
			}
		}
	}else{
		if (isset($_POST["barcode"]) AND isset($_POST["namaproduk"])){
			db_query("INSERT INTO product(idsupplier,idkategori,idsubkategori,barcode,alt_code,
			namaproduct,hargapokok,hargajual,margin,minstok,maxstok,stok,satuan,keterangan) VALUES(
			'%d','%d','%d','%s','%s',
			'%s','%f','%f','%f','%d','%d','%d','%s','%s')",
			$_POST["idsupplier"],$_POST["idkategori"],$_POST["idsubkategori"],$_POST["barcode"],$altCode,
			$_POST["namaproduk"],$_POST["hargapokok"],$_POST["hargajual"],$_POST["margin"],$_POST["minstok"],$_POST["maxstok"],$_POST["stok"],
			$_POST["satuan"],$_POST["keterangan"]);
			$result = db_query("SELECT idproduct FROM product WHERE alt_code='%s'",$_POST["alt_code"]);
			$data = db_fetch_object($result);
			$IDPRODUK = $data->idproduct;
			$KETERANGAN = 'Inisialisasi Stock';
			db_query("INSERT INTO transaksistock (idproduk, stocksebelum, masuk, stocksetelah, keterangan) VALUES 
			('%d', '%f', '%f', '%f', '%s')",$IDPRODUK,0,$_POST["stok"],$_POST["stok"],$KETERANGAN);
		}
	}
	exit();	
}

function update_subkategori(){
	$NILAI = $_POST['value'];
	$KOLOM = $_POST['kol_id'];
	$SUBKATEGORI_ID = $_POST['row_id'];
	$NILAITAMPIL = $NILAI;
	if ($KOLOM == "1"){
		$NILAI = strtoupper($NILAI);
		$sql_update = "UPDATE subkategori SET kodesubkategori='%s' WHERE idsubkategori='%d'";
	}elseif ($KOLOM == "2"){
		$sql_update = "UPDATE subkategori SET subkategori='%s' WHERE idsubkategori='%d'";
	}elseif ($KOLOM == "3"){
		$sql_update = "UPDATE subkategori SET keterangan='%s' WHERE idsubkategori='%d'";
	}elseif ($KOLOM == "0"){
		$sql_update = "UPDATE subkategori SET idkategori='%d' WHERE idsubkategori='%d'";
		$result = db_query("SELECT kodekategori,kategori FROM kategori WHERE idkategori='%d'",$NILAI);
		$data = db_fetch_object($result);
		$NILAITAMPIL = $data->kodekategori."-".$data->kategori;	
	}
	db_query($sql_update,$NILAI,$SUBKATEGORI_ID);
	echo $NILAITAMPIL;
	exit();	
}

function edit_data_produk(){
	if ($_GET["idproduk"]){
		$IDPRODUK = $_GET["idproduk"];
		$resultproduk = db_query("SELECT idsupplier, idkategori, idsubkategori, barcode, 
		alt_code, namaproduct, hargapokok, hargajual, margin, minstok, maxstok, stok, satuan, keterangan FROM product WHERE idproduct='%d'",
		$IDPRODUK);
		$dataproduk = db_fetch_object($resultproduk);
        $path = drupal_get_path('theme', 'cti_flex');
        $form_style = $path.'/css/custom-style.css';
        drupal_add_css($form_style, 'theme', 'all', FALSE);
        $variables['styles'] = drupal_get_css();
		drupal_add_css('misc/media/datatables/themes/smoothness/jquery-ui-1.8.4.custom.css');
		drupal_add_css('misc/media/css/validationEngine.jquery.css');
		drupal_add_js('misc/media/jquery-1.4.4.min.js');
		drupal_add_js('misc/media/jquery.validationEngine-en.js');
		drupal_add_js('misc/media/jquery.validationEngine.js');
		drupal_add_js('misc/media/jquery-ui-1.8.custom.min.js');
		drupal_add_js('misc/media/jquery.autotab-1.1b.js');
		drupal_add_js('
			var oTable;
			var pathutama = "'.base_path().'";
			var pathfile = "'.base_path().file_directory_path().'/";
			var barcodesama = false;
			
			function simpankategori(){
				if ($("#kodekategori").val() != "" && $("#kategori").val() != ""){
					var request = new Object();
					request.kodekategori = $("#kodekategori").val();
					request.kategori = $("#kategori").val();
					request.keterangan = $("#keterangan").val();
					request.asal = "Subkategori";
					alamat = pathutama + "dataproduk/simpankategori";
					$.ajax({ 
						type: "POST",
						url: alamat,
						data: request, 
						cache: false, 
						success: function(data){
							$("#idkategori").append("<option value=\""+ data +"\">"+ request.kodekategori +"-"+ request.kategori +"</option>");
							$("#kodekategori").val("");
							$("#kategori").val("");
							$("#keterangan").val("");
							$("#dialogtambahkategori").dialog("close");
							$("#idkategori").val(data);
							filtersubkategori();
						}
					});
				}else{
					$("#tambahkategoriform").submit();
				}	
			}
			
			function simpansubkategori(){
				if ($("#kodesubkategori").val() != "" && $("#subkategori").val() != ""){
					var request = new Object();
					request.idkategori = $("#idkategori2").val();
					request.kodesubkategori = $("#kodesubkategori").val();
					request.subkategori = $("#subkategori").val();
					request.keterangan = $("#keterangansub").val();
					request.asal = "Produk";
					alamat = pathutama + "dataproduk/simpansubkategori";
					$.ajax({ 
						type: "POST",
						url: alamat,
						data: request, 
						cache: false, 
						success: function(data){
							$("#idsubkategori").append("<option value=\""+ data +"\">"+ request.kodesubkategori +"-"+ request.subkategori +"</option>");
							$("#kodesubkategori").val("");
							$("#subkategori").val("");
							$("#keterangansub").val("");
							$("#dialogtambahsubkategori").dialog("close");
							$("#idsubkategori").val(data);
							ubahkodebarang();
						}
					});
				}else{
					$("#tambahsubkategoriform").submit();
				}	
			}
			
			function simpansupplier(){
				if ($("#kodesupplier").val() != "" && $("#supplier").val() != ""){
					var request = new Object();
					request.kodesupplier = $("#kodesupplier").val();
					request.namasupplier = $("#namasupplier").val();
					request.keterangan = $("#keterangan").val();
					request.asal = "supplier";
					alamat = pathutama + "datasupplier/simpansupplier";
					$.ajax({ 
						type: "POST",
						url: alamat,
						data: request, 
						cache: false, 
						success: function(data){
							$("#idsupplier").append("<option value=\""+ data +"\">"+ request.kodesupplier +"-"+ request.namasupplier +"</option>");
							$("#kodesupplier").val("");
							$("#supplier").val("");
							$("#keterangan").val("");
							$("#dialogtambahsupplier").dialog("close");
							$("#idsupplier").val(data);
						}
					});
				}else{
					$("#tambahsupplierform").submit();
				}	
			}
			function simpansatuan(){
				if ($("#namasatuan").val() != ""){
					var request = new Object();
					request.namasatuan = $("#namasatuan").val();
					alamat = pathutama + "dataproduk/simpansatuan";
					$.ajax({ 
						type: "POST",
						url: alamat,
						data: request, 
						cache: false, 
						success: function(data){
							if (data != "error"){
								$("#satuan").append("<option value=\""+ request.namasatuan +"\">"+ request.namasatuan +"</option>");
								$("#namasatuan").val("");
								$("#dialogtambahsatuan").dialog("close");
								$("#satuan").val(data);
							}else{
								alert("Satuan ini sudah ada..!!");	
							}
						}
					});
				}else{
					$("#tambahsatuanform").submit();
				}	
			}
			
			function filtersubkategori(idsubkategori){
				var request = new Object();
				request.idkategori = 	$("#idkategori").val();
				var alamat = pathutama + "dataproduk/filterkategori";
				$.ajax({ 
					type: "POST",
					url: alamat,
					data: request, 
					cache: false, 
					success: function(data){
						$("#idsubkategori").empty();
						$("#idsubkategori").append(data);
						ubahkodebarang();
						if (idsubkategori > 0){
							$("#idsubkategori").val(idsubkategori);
							ubahkodebarang();
						}
					}
				});
			}
			
			function ubahkodebarang(){
				var kategori = "";
				$("#idkategori option:selected").each(function () {
					kategori = $(this).text();
				}); 
				var pecahkategori = kategori.split("-");
				var subkategori = "";
				$("#idsubkategori option:selected").each(function () {
					subkategori = $(this).text();
				});
				pecahsubkategori = subkategori.split("-");
				kodebarang = pecahkategori[0] +"-"+ pecahsubkategori[0];
				var request = new Object();
				request.kodealternatif = 	kodebarang;
				var alamat = pathutama + "dataproduk/cekkodealternatif";
				$.ajax({ 
					type: "POST",
					url: alamat,
					data: request, 
					cache: false, 
					success: function(data){
						$("#kodepoduk").val(kodebarang +"-"+ data.trim());
					}
				});
			}
			function hitungmargin(asal){
				if (asal == "hargapokok"){
					if ($("#margin").val() > 0 && $("#hargapokok").val() > 0){
						var hargajual = $("#hargapokok").val() * (100/(100-$("#margin").val()));
						$("#hargajual").val(Math.round(hargajual));
					}
				}else if (asal == "hargajual"){
					if ($("#hargapokok").val() > 0 && $("#hargajual").val() > 0){
						var margin = (($("#hargajual").val() - $("#hargapokok").val())/$("#hargajual").val())*100;
						$("#margin").val(Math.round(margin));
					}
				}else if (asal == "margin"){
					if ($("#hargapokok").val() > 0 && $("#margin").val() > 0){
						var hargajual = $("#hargapokok").val() * (100/(100-$("#margin").val()));
						$("#hargajual").val(Math.round(hargajual));
					}
				}
			}
			function cek_barcode(field, rules, i, options){
				if (field.val() != ""){
					$("#validating").html("<img src = \""+ pathutama +"misc/media/images/loading.gif\">");
					$("#validating").fadeIn("fast",function(){
						var request = new Object();
					 	request.barcode = field.val();
					 	request.idproduk = $("#idproduk").val();
						cekbarcode = pathutama +"dataproduk/cekbarcode2";
						$.ajax({
							type: "POST", 
							url: cekbarcode,
							data: request,
							cache: false,
							success: function(data){
								if (data == "sama"){
									$("#validating").fadeOut("fast",function(){
										$("#barcode").validationEngine("showPrompt", "Barcode ini sudah digunakan..!!", "error", "topRight", true);
										$("#barcode").select();
										barcodesama = true;
									})
								}else{
									if (data == "taksama"){
										barcodesama = false;
									}
									$("#validating").fadeOut("fast");
								}
							}
						});
					})
				}		
			}
			function simpanproduk(){
				if ($("#barcode").val() != "" && !barcodesama && $("#namaproduk").val() != "" && $("#hargapokok").val() > 0 && $("#hargajual").val() > 0){
					var request = new Object();
					request.idproduk = $("#idproduk").val();
				 	request.barcode = $("#barcode").val();
				 	request.alt_code = $("#kodepoduk").val();
				 	request.idkategori = $("#idkategori").val();
				 	request.idsubkategori = $("#idsubkategori").val();
				 	request.idsupplier = $("#idsupplier").val();
				 	request.namaproduk = $("#namaproduk").val();
				 	request.hargapokok = $("#hargapokok").val();
				 	request.hargajual = $("#hargajual").val();
				 	request.margin = $("#margin").val();
				 	request.minstok = $("#minstok").val();
				 	request.maxstok = $("#maxstok").val();
				 	request.stok = $("#stok").val();
				 	request.satuan = $("#satuan").val();
				 	request.keterangan = $("#keteranganproduk").val();
					alamatsimpan = pathutama +"dataproduk/simpanproduk";
					$.ajax({
						type: "POST", 
						url: alamatsimpan,
						data: request,
						cache: false, 
						success: function(data){
							window.location = pathutama + "dataproduk/produk";
						}
					});
				}else{
					$("#formproduk").validationEngine("validate");
				}
			}
			function batal(){
				window.location = pathutama + "dataproduk/produk";
			}
			$(document).ready(function() {
				$("#dialogtambahkategori").dialog({
					modal: true,
					width: 350,
					resizable: false,
					autoOpen: false,
					position: ["auto","auto"],
					open: function(event, ui) {
						$("#kodekategori").focus();
					},
					close: function(){
						$("#tambahkategoriform").validationEngine("hide");
					}
				});
				$("#dialogtambahsubkategori").dialog({
					modal: true,
					width: 350,
					resizable: false,
					autoOpen: false,
					position: ["auto","auto"],
					open: function(event, ui) {
						$("#kodesubkategori").focus();
					},
					close: function(){
						$("#tambahsubkategoriform").validationEngine("hide");
					}
				});
				$("#dialogtambahsupplier").dialog({
					modal: true,
					width: 350,
					resizable: false,
					autoOpen: false,
					position: ["auto","auto"],
					open: function(event, ui) {
						$("#kodesupplier").focus();
					},
					close: function(){
						$("#tambahsupplierform").validationEngine("hide");
					}
				});
				$("#dialogtambahsatuan").dialog({
					modal: true,
					width: 350,
					resizable: false,
					autoOpen: false,
					position: ["auto","auto"],
					open: function(event, ui) {
						$("#namasatuan").select();
					},
					close: function(){
						$("#tambahsatuanform").validationEngine("hide");
					}
				});
				$("#formsubkategori").validationEngine();
				$("#tambahkategoriform").validationEngine();
				$("#tambahsubkategoriform").validationEngine();
				$("button").button();
				$("#addkategori").click(function(){
	      	$("#dialogtambahkategori").dialog("open");
	      	$(".ui-dialog-title").css("font-size","15px");
	      	$("#formproduk").validationEngine("hide");
	      	return false;
	      });
	      $("#addsubkategori").click(function(){
	      	$("#dialogtambahsubkategori").dialog("open");
	      	$("#idkategori2").val($("#idkategori").val());
	      	$(".ui-dialog-title").css("font-size","15px");
	      	$("#formproduk").validationEngine("hide");
	      	return false;
	      });
	      $("#addsupplier").click(function(){
	      	$("#dialogtambahsupplier").dialog("open");
	      	$(".ui-dialog-title").css("font-size","15px");
	      	$("#formproduk").validationEngine("hide");
	      	return false;
	      });
	      $("#addsatuan").click(function(){
	      	$("#dialogtambahsatuan").dialog("open");
	      	$(".ui-dialog-title").css("font-size","15px");
	      	$("#formproduk").validationEngine("hide");
	      	return false;
	      });
	      $("#kodekategori, #kodesubkategori, #kodesupplier, #namasatuan").autotab_filter({
					format: "alphanumeric",
					uppercase: true,
					nospace: true
				});
				$("#hargapokok,#hargajual,#margin,#minstok,#maxstok,#stok").autotab_filter({
					format: "numeric",
					nospace: true
				});
				$("#main h2").css("width","100%");
				$("#main h2").css("float","left");
				$("#barcode").keypress(function(e) {
				    if(e.keyCode == 13) {
				    	$("#formproduk").validationEngine("validateField", "#barcode");
				    	$("#idkategori").focus();
				    }
				});
				$("#formproduk").validationEngine();
				filtersubkategori("'.$dataproduk->idsubkategori.'");
				$("#barcode").select();
			})
		','inline');
		//Pilihan Kategori
		$result = db_query("SELECT idkategori,kodekategori,kategori FROM kategori ORDER BY kodekategori,kategori");
		$pilihankategori = '<select id="idkategori" name="idkategori" onkeypress="filtersubkategori();" onchange="filtersubkategori();">';
		while ($data = db_fetch_object($result)){
			if ($data->idkategori == $dataproduk->idkategori){
				$pilihankategori .= '<option selected value="'.$data->idkategori.'">'.$data->kodekategori.'-'.$data->kategori.'</option>';
			}else{
				$pilihankategori .= '<option value="'.$data->idkategori.'">'.$data->kodekategori.'-'.$data->kategori.'</option>';
			}
		}
		$pilihankategori .= '</select>';
		$buttonadd = '<img id="addkategori" title="Tambah Kategori Baru" src="'.base_path().'misc/media/images/add-small.ico">';
		//Pilihan Sub Kategori
		$pilihansubkategori = '<select id="idsubkategori" name="idsubkategori" onkeypress="ubahkodebarang();" onchange="ubahkodebarang();">';
		$pilihansubkategori .= '</select>';
		$buttonadd2 = '<img id="addsubkategori" title="Tambah Subkategori Baru" src="'.base_path().'misc/media/images/add-small.ico">';
		//Pilihan Supplier
		$result = db_query("SELECT idsupplier,kodesupplier,namasupplier FROM supplier ORDER BY kodesupplier,namasupplier");
		$pilihansupplier = '<select id="idsupplier" name="idsupplier" >';
		while ($data = db_fetch_object($result)){
			if ($data->idsupplier == $dataproduk->idsupplier){
				$pilihansupplier .= '<option selected value="'.$data->idsupplier.'">'.$data->kodesupplier.'-'.$data->namasupplier.'</option>';
			}else{
				$pilihansupplier .= '<option value="'.$data->idsupplier.'">'.$data->kodesupplier.'-'.$data->namasupplier.'</option>';
			}
		}
		$pilihansupplier .= '</select>';
		$buttonadd3 = '<img id="addsupplier" title="Tambah Supplier Baru" src="'.base_path().'misc/media/images/add-small.ico">';
		//Pilihan Satuan
		$result = db_query("SELECT satuan FROM satuan ORDER BY satuan");
		$pilihansatuan = '<select id="satuan" name="satuan" >';
		while ($data = db_fetch_object($result)){
			if ($data->satuan == $dataproduk->satuan){
				$pilihansatuan .= '<option selected value="'.$data->satuan.'">'.$data->satuan.'</option>';
			}else{
				$pilihansatuan .= '<option value="'.$data->satuan.'">'.$data->satuan.'</option>';
			}
		}
		$pilihansatuan .= '</select>';
		$buttonadd4 = '<img id="addsatuan" title="Tambah satuan Baru" src="'.base_path().'misc/media/images/add-small.ico">';
		//Pilihan Kategori untuk Dialog Box Tambah Sub kategori
		$result = db_query("SELECT idkategori,kodekategori,kategori FROM kategori ORDER BY kodekategori,kategori");
		$pilihankategori2 = '<select disabled="disabled" id="idkategori2" name="idkategori2">';
		while ($data = db_fetch_object($result)){
			$pilihankategori2 .= '<option value="'.$data->idkategori.'">'.$data->kodekategori.'-'.$data->kategori.'</option>';
		}
		$pilihankategori2 .= '</select>';
		//idsupplier,idkategori,idsubkategori,barcode,alt_code,namaproduct,hargapokok,hargajual,margin,minstok,maxstok,stok,satuan,berat,keterangan
		$editproduk = '<div id="formplace" class="produk"><form id="formproduk">';
		$dataproduk->barcode = empty($dataproduk->barcode) ? createEAN13Code(getRandomString(9)) : $dataproduk->barcode;
		$editproduk .= '<label>BARCODE</label><input type="text" class="validate[required,funcCall[cek_barcode]]" id="barcode" name="barcode" value="'.$dataproduk->barcode.'"><div id="validating"></div>';
		$editproduk .= '<input type="hidden" id="barcodelama" name="barcodelama" value="'.$dataproduk->barcode.'">';
		$editproduk .= '<input type="hidden" id="kodepoduk" name="kodeproduk" value="'.$dataproduk->alt_code.'">';
		$editproduk .= '<label>KATEGORI</label>'.$pilihankategori.$buttonadd;
		$editproduk .= '<label>SUBKATEGORI</label>'.$pilihansubkategori.$buttonadd2;
		$editproduk .= '<label>SUPPLIER</label>'.$pilihansupplier.$buttonadd3;
		$editproduk .= '<label>NAMA PRODUK</label><input type="text" class="validate[required]" id="namaproduk" name="namaproduk" value="'.$dataproduk->namaproduct.'">';
		$editproduk .= '<label>HARGA POKOK</label><input type="text" class="validate[required]" id="hargapokok" name="hargapokok" onkeyup="hitungmargin(\'hargapokok\')" value="'.$dataproduk->hargapokok.'">';
		$editproduk .= '<label>HARGA JUAL</label><input type="text" class="validate[required]" id="hargajual" name="hargajual" onkeyup="hitungmargin(\'hargajual\')" value="'.$dataproduk->hargajual.'">';
		$editproduk .= '<label>MARGIN HARGA</label><input type="text" maxlength="2" id="margin" name="margin" onkeyup="hitungmargin(\'margin\')" value="'.$dataproduk->margin.'">';
		$editproduk .= '<label>MINIMUM STOK</label><input type="text" id="minstok" name="minstok" value="'.$dataproduk->minstok.'">';
		$editproduk .= '<label>MAKSIMUM STOK</label><input type="text" id="maxstok" name="maxstok" value="'.$dataproduk->maxstok.'">';
		$editproduk .= '<label>STOK SAAT INI</label><input type="text" id="stok" name="stok" value="'.$dataproduk->stok.'">';
		$editproduk .= '<label>SATUAN</label>'.$pilihansatuan.$buttonadd4;
		$editproduk .= '<label>KETERANGAN</label><input type="text" id="keteranganproduk" name="keteranganproduk" value="'.$dataproduk->keterangan.'">';
		$editproduk .= '<input type="hidden" id="idproduk" name="idproduk" value="'.$IDPRODUK.'"></form><div><button onclick="simpanproduk();" style="font-size:12px;border: 1px solid blue;">Update Produk</button><button onclick="batal();" style="font-size:12px;margin-left: 6px;border: 1px solid red;">Batal</button></div></div></div>';
		$editproduk .= '<div id="dialogtambahkategori" title="TAMBAH KATEGORI PRODUK">';
		$editproduk .= '<form id="tambahkategoriform"><div><label>KODE</label><input type="text" class="validate[required]" id="kodekategori" name="kodekategori"></div>';
		$editproduk .= '<div><label>KATEGORI</label><input class="validate[required]" type="text" id="kategori" name="kategori"></div>';
		$editproduk .= '<div><label>KETERANGAN</label><input type="text" id="keterangan" name="keterangan"></div></form>';
		$editproduk .= '<div><button id="tambahkategori" onclick="simpankategori();">Tambah Kategori</button></div>';
		$editproduk .= '</div>';
		$editproduk .= '<div id="dialogtambahsubkategori" title="TAMBAH SUBKATEGORI PRODUK">';
		$editproduk .= '<form id="tambahsubkategoriform"><div>';
		$editproduk .= '<div><label>KATEGORI</label>'.$pilihankategori2.'</div>';
		$editproduk .= '<div><label>KODE SUBKATEGORI</label><input type="text" class="validate[required]" id="kodesubkategori" name="kodesubkategori"></div>';
		$editproduk .= '<div><label>SUBKATEGORI</label><input type="text" class="validate[required]" id="subkategori" name="subkategori"></div>';
		$editproduk .= '<div><label>KETERANGAN</label><input type="text" id="keterangansub" name="keterangansub"></div></div></form>';
		$editproduk .= '<div><button id="tambahsubkategori" onclick="simpansubkategori();">Tambah Sub Kategori</button></div>';
		$editproduk .= '</div>';
		$editproduk .= '<div id="dialogtambahsupplier" title="TAMBAH SUPPLIER">';
		$editproduk .= '<form id="tambahsupplierform"><div><label>KODE SUPPLIER</label><input type="text" class="validate[required]" id="kodesupplier" name="kodesupplier"></div>';
		$editproduk .= '<div><label>NAMA SUPPLIER</label><input class="validate[required]" type="text" id="namasupplier" name="namasupplier"></div>';
		$editproduk .= '<div><label>TELP</label><input type="text" id="telp" name="telp"></div>';
		$editproduk .= '<div><label>ALAMAT</label><input type="text" id="alamat" name="alamat"></div>';
		$editproduk .= '<div><label>EMAIL</label><input type="text" id="email" name="email"></div>';
		$editproduk .= '</form>';
		$editproduk .= '<div><button id="tambahsupplier" onclick="simpansupplier();">Tambah Supplier</button></div>';
		$editproduk .= '<div id="dialogtambahsatuan" title="TAMBAH SATUAN">';
		$editproduk .= '<form id="tambahsatuanform"><div><label>SATUAN</label><input type="text" class=\"validate[required]\" id="namasatuan" name="namasatuan"></div>';
		$editproduk .= '</form>';
		$editproduk .= '<div><button id="tambahsatuan" onclick="simpansatuan();">Tambah Satuan</button></div>';
		$editproduk .= '</div>';
		return $editproduk;
	}else{
		drupal_goto("dataproduk/produk");
	}
}

function update_data_produk(){
	$nilai = $_POST['value'];
	$kolom = $_POST['kol_id'];
	$productId = $_POST['row_id'];
	$result = db_query("SELECT stok,hargapokok,hargajual FROM product WHERE idproduct='%d'",$productId);
	$data = db_fetch_object($result);
	$stokSebelum = $data->stok;
	$hargaPokok = $data->hargapokok;
	$hargaJual = $data->hargajual;
	$nilaiRet = $nilai;
	if ($kolom == "3"){
		$sql_update = "UPDATE product SET barcode='%s' WHERE idproduct='%d'";
	}else if ($kolom == "4"){
		$sql_update = "UPDATE product SET alt_code='%s' WHERE idproduct='%d'";
	}else if ($kolom == "5"){
		$sql_update = "UPDATE product SET namaproduct='%s' WHERE idproduct='%d'";
	}else if ($kolom == "6"){
		$sql_update = "UPDATE product SET hargapokok='%f' WHERE idproduct='%d'";
		$nilaiRet = number_format($nilai,0,",",".");
		if ($nilai <> $hargaPokok){
			global $user;
			db_query("INSERT INTO historyhargabeli (hargasebelum, hargasesudah, uid) VALUES('%f','%f','%d')",
			$hargaPokok,$nilai,$user->uid);
		}
	}else if ($kolom == "7"){
		$sql_update = "UPDATE product SET hargajual='%f' WHERE idproduct='%d'";
		$nilaiRet = number_format($nilai,0,",",".");
		if ($nilai <> $hargaJual){
			global $user;
			db_query("INSERT INTO historyhargajual (hargasebelum, hargasesudah, uid) VALUES('%f','%f','%d')",
			$hargaJual,$nilai,$user->uid);
		}
	}else if ($kolom == "8"){
		$sql_update = "UPDATE product SET stok='%f' WHERE idproduct='%d'";
		$nilaiRet = number_format($nilai,0,",",".");
		$selisihStok = ($nilai*1)-$stokSebelum;
		if ($selisihStok < 0){
			$keluar = abs($selisihStok);
			$keterangan = 'Perubahan Stock-Stok Berkurang';
			db_query("INSERT INTO transaksistock (idproduk, stocksebelum, keluar, stocksetelah, keterangan) VALUES 
			('%d', '%f', '%f', '%f', '%s')",$productId,$stokSebelum,$keluar,$nilai,$keterangan);
		}else{
			$masuk = $selisihStok;
			$keterangan = 'Perubahan Stock-Stok Bertambah';
			db_query("INSERT INTO transaksistock (idproduk, stocksebelum, masuk, stocksetelah, keterangan) VALUES 
			('%d', '%f', '%f', '%f', '%s')",$productId,$stokSebelum,$masuk,$nilai,$keterangan);
		}
	}
	db_query($sql_update,$nilai,$productId);
	echo $nilaiRet;
	exit();	
}