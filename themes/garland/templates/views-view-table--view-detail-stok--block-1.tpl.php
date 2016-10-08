<?php
// $Id: views-view-table.tpl.php,v 1.8 2009/01/28 00:43:43 merlinofchaos Exp $
/**
 * @file views-view-table.tpl.php
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $class: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * @ingroup views_templates
 */
 $datatables = true;
 _include_jquery_plugins($datatables);
 drupal_add_js('
  $(document).ready(function(){
	 	oTable = $("#tabel_detail_stok").dataTable( {
			"bJQueryUI": true,
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": false,
			"bInfo": false,
			"bAutoWidth": false,
			"bSort": false,
		});
	})
 ','inline');
 if (!isset($_GET['idstok'])){
 	$args = array(arg(3));	
 }else{
 	$args = array($_GET['idstok']);
 }
 $view = views_get_view('get_posisi_stok');
 $view->set_arguments($args);
 $view->execute();
 if (count($view->result)){
 	$totallaporanstok = $view->result[0]->node_data_field_jumlah_field_jumlah_value;
 	$lastdate = $view->result[0]->node_data_field_tanggal_stok_field_tanggal_stok_value.' '.$view->result[0]->node_data_field_jam_field_jam_value;
 }
?>
<div style="margin-bottom: 5px;"><b>DETAIL KONDISI STOK</p></div>
<div style="margin-bottom: 5px;"><b>Total Laporan Stok: <?php print $totallaporanstok; ?></p></div>
<table id="tabel_detail_stok" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
      	<th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
  	<?php
  		$totalstok = 0;
  	?>
  	<?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
        	<td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php
          	if (trim($fields[$field]) == 'field-jumlah-value'){
          		$totalstok = $totalstok + $content;
          	}
          ?>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
    <?php
    	$result = db_query("SELECT SUM(field_jumlah_value) AS totalkirim FROM node a,content_field_jumlah b,content_field_tanggal_stok c,content_field_jam d,content_field_cabang_ref e WHERE 
    	a.nid=b.nid AND a.nid=c.nid AND a.nid=d.nid AND a.nid=e.nid AND CONCAT(c.field_tanggal_stok_value,' ',d.field_jam_value) >= 
    	STR_TO_DATE('$lastdate','%%Y-%%m-%%d %%H:%%i:%%s') AND a.type='data_pengiriman' AND e.field_cabang_ref_nid='%d'",$_GET["idcabang"]);
    	$data = db_fetch_object($result);
    	$totalkirim = round($data->totalkirim,0);
    	$totalstok = $totalstok - $totalkirim;
    	$result = db_query("SELECT SUM(field_jumlah_value) AS totalterima FROM node a,content_field_jumlah b,content_field_tanggal_stok c,content_field_jam d,content_field_cabang_ref e WHERE 
    	a.nid=b.nid AND a.nid=c.nid AND a.nid=d.nid AND a.nid=e.nid AND CONCAT(c.field_tanggal_stok_value,' ',d.field_jam_value) >= 
    	STR_TO_DATE('$lastdate','%%Y-%%m-%%d %%H:%%i:%%s') AND a.type='data_penerimaan_tabung' AND e.field_cabang_ref_nid='%d'",$_GET["idcabang"]);
    	$data = db_fetch_object($result);
    	$totalterima = round($data->totalterima,0);
    	$totalstok = $totalstok + $totalterima;
    	if ($_GET["idcabang"] == 249 || $_GET["idcabang"] == 250 || $_GET["idcabang"] == 251){
	    	$result = db_query("SELECT SUM(field_jumlah_value) AS totaltukar FROM node a,content_field_jumlah b,content_field_tanggal_stok c,content_field_jam d,content_field_cabang_ref e WHERE 
	    	a.nid=b.nid AND a.nid=c.nid AND a.nid=d.nid AND a.nid=e.nid AND CONCAT(c.field_tanggal_stok_value,' ',d.field_jam_value) >= 
	    	STR_TO_DATE('$lastdate','%%Y-%%m-%%d %%H:%%i:%%s') AND a.type='penukaran_tabung' AND e.field_cabang_ref_nid='%d'",$_GET["idcabang"]);
	    	$data = db_fetch_object($result);
	    	$totaltukar = round($data->totaltukar,0);
	    	$totalstok = $totalstok - $totaltukar;
	    }
    ?>
    <tr>
    	<td>
    		Pengiriman
    	</td>
    	<td style="text-align: right;">
    		<?php print $totalkirim; ?>
    	</td>
    </tr>
    <tr>
    	<td>
    		Penerimaan
    	</td>
    	<td style="text-align: right;">
    		<?php print $totalterima; ?>
    	</td>
    </tr>
    <?php
    if ($_GET["idcabang"] == 249){
    ?>
    <tr>
    	<td>
    		Penukaran
    	</td>
    	<td style="text-align: right;">
    		<?php print $totaltukar; ?>
    	</td>
    </tr>
    <?php
  	}
    ?>
  </tbody>
</table>
<div align="right" style="margin-top: 3px;"><b>Total : <?php print $totalstok; ?></b></div>