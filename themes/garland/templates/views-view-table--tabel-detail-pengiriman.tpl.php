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
 /*
 field_jenis_pekerjaan_ref_nid
 field_plat_balancer_value
 field_jumlah_value
 nid
 */
 //dpm($rows);
 $jumlah = array();
 for ($i = 0;$i < count($rows);$i++){
 	if ($rows[$i]['field_plat_balancer_value']){
 		$balancer = $rows[$i]['field_plat_balancer_value'];	
 	}else{
 		$balancer = 0;	
 	}
 	$jumlah[$balancer][$rows[$i]['nid']]	= $rows[$i]['field_jumlah_value'];
 }
 //dpm($jumlah);
 $pekerjaan_rs = db_query("SELECT a.nid,a.title,b.field_singkatan_value FROM node a,content_type_jenis_pekerjaan b WHERE a.nid=b.nid AND a.type='jenis_pekerjaan'");
 $jenis_pekerjaan = array();
 $singkatan = array();
 while ($pekerjaan_data = db_fetch_object($pekerjaan_rs)){
 	$jenis_pekerjaan[] = $pekerjaan_data->nid;	
 	$singkatan[] = $pekerjaan_data->field_singkatan_value;	
 }
 print '
 <table>
 	<thead>
 		<tr>
 			<th style="width:30px;">No.</th>
 			<th>Uraian Pekerjaan</th>';
 foreach ($singkatan as $pekerjaannya){
 	print '<th style="width:60px;text-align: center;">'.$pekerjaannya.'</div></th>';			
 }	
 $uraian[0] = 'LU tp plat';
 $uraian[1] = 'LU + 1 plat';
 $uraian[2] = 'LU + 2 plat';
 print '
 		</tr>
 	</thead>	
 	<tbody>
 		<tr style="border-bottom: 1px solid #D3E7F4;">
 			<td rowspan="3">3 KG</td>
 			<td>'.$uraian[0].'</td>';
 for ($j = 0;$j < count($jenis_pekerjaan);$j++){
 		if ((int)$jumlah[0][$jenis_pekerjaan[$j]] <= 0){
 			$tampilkan = '-';
 		}else{
 			$tampilkan = $jumlah[0][$jenis_pekerjaan[$j]];
 		}
 		print '
 			<td style="text-align: center;">'.$tampilkan.'</td>';
 }			
 print '
 		</tr>';
 for ($i = 1;$i < 3;$i++){
 	print '
 		<tr style="border-bottom: 1px solid #D3E7F4;">
 			<td>'.$uraian[$i].'</td>';
 	for ($j = 0;$j < count($jenis_pekerjaan);$j++){
 		if (!$jumlah[$i][$jenis_pekerjaan[$j]] > 0){
 			$tampilkan = '-';
 		}else{
 			$tampilkan = $jumlah[$i][$jenis_pekerjaan[$j]];
 		}
 		print '
 			<td style="text-align: center;">'.$tampilkan.'</td>';
	}
	print '
		</tr>';
 }		
 print'
 	</tbody>
 </table>';
?>