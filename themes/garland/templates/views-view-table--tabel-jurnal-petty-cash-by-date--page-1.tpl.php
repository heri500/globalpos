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
 	global $user;
 	$superuser = false;
 	if (in_array('Super User',$user->roles)){
 		$superuser = true;
 	}
 	if ($superuser){
  	$cabang_rs = db_query("SELECT nid,title FROM node WHERE type='cabang_indogas'");
  	$cabang_array = array();
  	while ($cabang_data = db_fetch_object($cabang_rs)){
  		$cabang_array[] = $cabang_data;
  	}
  	if ($_GET['cabang']){
  		$idcabangpilihan = (int)$_GET['cabang'];
  	}else{
  		$idcabangpilihan = 1;
  	}
  }else{
  	$view = views_get_view('get_posisi_user');
 		$view->execute();
 		if (count($view->result)){
  		$idcabangpilihan = $view->result[0]->node_data_field_user_id_field_cabang_id_nid;
  		drupal_set_message($idcabangpilihan);
  	}	
  }
	$account_nid = db_result(db_query("SELECT nid FROM indogas_nid_petty_cash LIMIT 1"));
	$saldoawal_rs = db_query("SELECT tglsaldoawal,saldo_awal FROM indogas_saldo_awal_account 
	WHERE nid_cabang='%d' AND nid_account='%d' ORDER BY tglsaldoawal DESC LIMIT 1",
	$idcabangpilihan,$account_nid);
	$saldo_awal_data = db_fetch_object($saldoawal_rs);
	if ($_GET['created']){
		$created_array = $_GET['created'];
		$tglawal = date("Y-m-d H:i", strtotime($created_array['min'].':00'));
		if ($tglawal == date("Y-m-d H:i", strtotime($saldo_awal_data->tglsaldoawal))){
			$totalsaldoawal = $saldo_awal_data->saldo_awal;
		}else{
			$totalsaldoawal = $saldo_awal_data->saldo_awal + selisih_debet_kredit($tglawal,date("Y-m-d H:i", strtotime($saldo_awal_data->tglsaldoawal)),$idcabangpilihan);
		}
	}else{
		$tglawal_default = '2011-12-01 00:00:00';
		$tglawal = date("Y-m-d H:i", strtotime($tglawal_default));
		if ($tglawal == date("Y-m-d H:i", strtotime($saldo_awal_data->tglsaldoawal))){
			$totalsaldoawal = $saldo_awal_data->saldo_awal;
		}else{
			$totalsaldoawal = $saldo_awal_data->saldo_awal + selisih_debet_kredit($tglawal,date("Y-m-d H:i", strtotime($saldo_awal_data->tglsaldoawal)),$idcabangpilihan);
		}
	}
?>
<table id="tabel-petty-cash" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
      	<?php
      	if (trim($fields[$field]) != 'field-posisi-account-jurnal-value' && trim($fields[$field]) != 'field-nilai-transaksi-value-1'){	
      	?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php
      	} 
        ?>
      <?php endforeach; ?>
    	<th>
    		Saldo
    	</th>
    </tr>
  </thead>
  <tbody>
  	<?php
  		$saldo = 0;
  		$tglsebelum = '';
  	?>
  		<tr>
  		<td><b><?php print date('d-m-Y',strtotime($tglawal)); ?></b></td>
  		<td><b>Saldo awal petty cash</b></td>
  		<td><div align="right"><b><?php print number_format($totalsaldoawal,0,',','.'); ?></b></div></td>
  		<td><div align="right"><b><?php print number_format($totalsaldoawal,0,',','.'); ?></b></div></td>
  		</tr>
    <?php foreach ($rows as $count => $row): ?>
    	<?php
    		$posisi_account = $rows[$count]['field_posisi_account_jurnal_value'];
    		if ($posisi_account == 'Kredit'){
    			$totalsaldoawal = $totalsaldoawal - $rows[$count]['field_nilai_transaksi_value_1'];
    			$saldo_bold = false;
    		}else{
    			$totalsaldoawal = $totalsaldoawal + $rows[$count]['field_nilai_transaksi_value_1'];
    			$row['field_transaksi_value_1'] = '<b>'.$rows[$count]['field_transaksi_value_1'].'</b>';
    			$row['field_nilai_transaksi_value'] = '<b>'.$row['field_nilai_transaksi_value'].'</b>';
    			$saldo_bold = true; 
    		}
    	?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php foreach ($row as $field => $content): ?>
          <?php
	      	if (trim($fields[$field]) != 'field-posisi-account-jurnal-value' && trim($fields[$field]) != 'field-nilai-transaksi-value-1'){	
	      		if (trim($fields[$field]) == 'field-nilai-transaksi-value'){
	      			$content = '<div align="right">'.$content.'</div>';
	      		}else if(trim($fields[$field]) == 'created'){
	      			if ($tglsebelum != $content){
	      				$tglsebelum = $content;
	      			}else{
	      				$content = '';
	      			}
	      		}
	      	?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php
	      	} 
	        ?>
        <?php endforeach; ?>
        <td>
        	<?php 
        		if ($saldo_bold){
        			print '<div align="right"><b>'.number_format($totalsaldoawal,0,',','.').'</b></div>'; 
        		}else{
        			print '<div align="right">'.number_format($totalsaldoawal,0,',','.').'</div>'; 
        		}
        	?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>