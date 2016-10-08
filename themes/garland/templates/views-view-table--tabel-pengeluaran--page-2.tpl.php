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
 	global $pettycashmode;
 	$pettycashmode = 'view';
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
	$saldoawal = $totalsaldoawal;
?>

<table id="tabelpengeluaran" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid-1' && trim($fields[$field]) != 'field-harga-value'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
      	<th style="width: 120px;text-align: center;">
	      	Debet
	      </th>
	      <th style="width: 120px;text-align: center;">
	      	Kredit
	      </th>
	      <th style="width: 120px;text-align: center;">
	      	Saldo
	      </th>
	      <th style="display: none;">
	      	&nbsp;
	      </th>
    </tr>
  </thead>
  <tbody>
  		<tr>
  			<td><img src="<?php print base_path().'misc/media/images/details_close.png'; ?>"></td>
  			<td>Saldo Awal</td>
  			<td>Saldo Awal</td>
  			<td><?php print substr($tglawal,0,10); ?></td>
  			<td style="text-align: center;">00:00</td>
  			<td><?php print '<div align="right">'.number_format($totalsaldoawal,0,',','.').'</div>'; ?></td>
  			<td><div align="right">0</div></td>
  			<td><?php print '<div align="right">'.number_format($totalsaldoawal,0,',','.').'</div>'; ?></td>
  			<td style="display: none;">
        &nbsp;
      	</td>
  		</tr>
    <?php foreach ($rows as $count => $row): ?>
    	<?php
    		$nid_jurnal = $rows[$count]['nid_1'];
    		$detailjurnal = views_embed_view("tabel_detail_jurnal_by_nidjurnal","default",$nid_jurnal);
      	$inputdetail = '<div id="detail_'.$nid_jurnal.'" style="display:none">'.$detailjurnal.'</div>';
      	global $totaltransaksi;
      	global $totaldebet;
      	global $totalkredit;
      	if ($totaldebet != $totalkredit){
	      	$totaldebetview = '<div align="right">'.number_format($totaldebet, 0, ',', '.').'</div>';
	      	$totalkreditview = '<div align="right">'.number_format($totalkredit, 0, ',', '.').'</div>';
	      	$saldoawal = $saldoawal - $totaldebet + $totalkredit;
	      	$totalsaldoawalview = '<div align="right">'.number_format($saldoawal, 0, ',', '.').'</div>';
      ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <?php if (trim($fields[$field]) != 'nid-1' && trim($fields[$field]) != 'field-harga-value'){ ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php 
          } 	
          ?>
        <?php endforeach; ?>
        <td>
        	<?php print $totalkreditview; ?>
        </td>
        <td>
        	<?php print $totaldebetview; ?>
        </td>
        <td>
	      	<?php print $totalsaldoawalview; ?>
	      </td>
        <td style="display: none;">
        <?php print $inputdetail; ?>
      	</td>
      </tr>
    <?php } ?>  
    <?php endforeach; ?>
  </tbody>
</table>