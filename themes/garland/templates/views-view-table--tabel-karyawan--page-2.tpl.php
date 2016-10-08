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
 	if (isset($_GET['bulan']) && isset($_GET['tahun'])){
 		$bulan = $_GET['bulan'];
 		$tahun = $_GET['tahun'];
 	}else{
 		$bulan = date('n');
 		$tahun = date('Y');;
 	}
 	$lastday = get_last_day($bulan,$tahun);
 	$tglabsen = $tahun.'-'.$bulan.'-01';
 	global $user;
	$superuser = cek_super_user();
	if ($superuser){
		if (isset($_GET['cabang'])){
			$selected_cabang = $_GET['cabang'];
		}else{
			$selected_cabang = 1;
		}
	}else{
		$selected_cabang =	cek_posisi_user();
	}
	$array_absensi = re_arrange_array_absensi_karyawan($selected_cabang, $tglabsen);
	dpm($array_absensi);
	$warnaabsen = create_array_warna_absen();
 	$array_status_absen = create_array_status_absen();
 	$array_hari = create_array_hari(); 	
?>
<table id="tabel_absensi_karyawan" class="<?php print $class; ?>" style="width: 1300px;">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th class="table_button">	
    	</th>
    	<?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid'){ ?>
      	<?php
      		if ($field == 'title'){
      			$classadded = 'nama-karyawan';	
      		}else{
      			$classadded = '';
      		}
      	?>
      	<th class="views-field views-field-<?php print $fields[$field].' '.$classadded; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
      <?php
      for ($i = 1;$i <= $lastday;$i++){
      	if ($i < 10 ){
      		$no_tgl = '0'.$i;	
      	}else{
      		$no_tgl = $i;	
      	}
      ?>
      <th style="font-size:11px;width:30px;text-align: center;">	
      	<?php print $no_tgl; ?>
    	</th>
      <?php	
      }
      ?>
    </tr>
  </thead>
  <tbody>
  	<?php
  	$no = 0;
  	?>
    <?php foreach ($rows as $count => $row): ?>
    	<?php
      	$no++;
      	$nid_karyawan = $rows[$count]['nid'];
      	$nama_karyawan = $rows[$count]['title'];
      ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <td class="table_button" style="text-align: right;"><?php print $no; ?></td>
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid'){ ?>
          <td class="views-field views-field-<?php print $fields[$field].' '.$classadded; ?>">
          	<?php
          		if (trim($fields[$field]) == 'title'){
          			$pecahnama = explode(" ",$content);
          			$panjangnama12 = strlen($pecahnama[0].' '.$pecahnama[1]);
          			if (strlen($pecahnama[1]) > 15 || $panjangnama12 > 22){
          				$pecahnama[1] = substr($pecahnama[1],0,1).'.';
          			}
          			$content = $pecahnama[0].' '.$pecahnama[1].' '.substr($pecahnama[2],0,1);
          			if(count($pecahnama) > 2){
          				$content .= '.';	
          			}
          		}
          	?>
            <?php print trim($content); ?>
          </td>
          <?php } ?>
        <?php endforeach; ?>
        <?php
        for ($i = 1;$i <= $lastday;$i++){
        	$tglabsen_view = $tahun.'-'.$bulan.'-'.$i;
        	$hari_tampil = date('w',strtotime($tglabsen_view));
        	$status_hadir = $array_absensi[$nid_karyawan][$i]['status'];
        	$hari = $array_absensi[$nid_karyawan][$i]['hari'];
        	$jenisabsen = $array_absensi[$nid_karyawan][$i]['jenisabsen'];
        	$hadirClass = '';
        	if (!is_null($status_hadir)){
        		$warna_bg = $warnaabsen[$status_hadir];
        		$keteranganabsen = 'Nama: '.$nama_karyawan.', Kehadiran: '.$array_status_absen[$status_hadir];
        		if ($status_hadir != 0){
        			if ($status_hadir == 2){
        				$keteranganabsen .= ', Jam Masuk : '.$array_absensi[$nid_karyawan][$i]['masuk'].', Tanggal Keluar: '.$array_absensi[$nid_karyawan][$i]['tanggalkeluar'].', Jam Keluar: '.$array_absensi[$nid_karyawan][$i]['keluar'];
        			}
        			$keteranganabsen .= ', Keterangan : '.$array_absensi[$nid_karyawan][$i]['keterangan'];
        		}else{
        			if ($array_absensi[$nid_karyawan][$i]['keluar'] == '0:0'){
        				if (is_null($jenisabsen)){
        					$hadirClass = 'user-attendance-uncomplete';
        				}else if ($jenisabsen == 1){
        					$hadirClass = 'finger-attendance-uncomplete';
        				}else{
        					$hadirClass = 'card-attendance-uncomplete';
        				}
        				$keteranganabsen .= ', Jam Masuk : '.$array_absensi[$nid_karyawan][$i]['masuk'].', Tanggal Keluar: '.$array_absensi[$nid_karyawan][$i]['tanggalkeluar'].', Jam Keluar: -';
        			}else if ($array_absensi[$nid_karyawan][$i]['masuk'] == '0:0'){
        				if (is_null($jenisabsen)){
        					$hadirClass = 'user-attendance-uncomplete';
        				}else if ($jenisabsen == 1){
        					$hadirClass = 'finger-attendance-uncomplete';
        				}else{
        					$hadirClass = 'card-attendance-uncomplete';
        				}
        				$keteranganabsen .= ', Jam Masuk : -, Tanggal Keluar: '.$array_absensi[$nid_karyawan][$i]['tanggalkeluar'].', Jam Keluar: '.$array_absensi[$nid_karyawan][$i]['keluar'];	
        			}else{
        				if (is_null($jenisabsen)){
        					$hadirClass = 'user-attendance-complete';
        				}else if ($jenisabsen == 1){
        					$hadirClass = 'finger-attendance-complete';
        				}else{
        					$hadirClass = 'card-attendance-complete';
        				}
        				$keteranganabsen .= ', Jam Masuk : '.$array_absensi[$nid_karyawan][$i]['masuk'].', Tanggal Keluar: '.$array_absensi[$nid_karyawan][$i]['tanggalkeluar'].', Jam Keluar: '.$array_absensi[$nid_karyawan][$i]['keluar'];	
        			}
        		}
        		if (($hari_tampil == 0 || $status_hadir == 1) && $status_hadir != 0){
        			$warna_bg = $warnaabsen[6];
        			$keteranganabsen = 'Libur';
        		}
        	}else{
        		$keteranganabsen = '';
        		if ($hari_tampil == 0 || $hari_tampil == 6){
	        		$warna_bg = $warnaabsen[6];
	        		$keteranganabsen = 'Libur';
	        	}else{
	        		$warna_bg = '#FFFFFF';	
	        		$keteranganabsen = 'Belum ada data';
	        	}
        	}
        	if ((int)$array_absensi[$nid_karyawan][$i]['nid'] > 0){	
        ?>
        <td id="absensi_result_<?php print $nid_karyawan.'_'.$i.'_'.$array_absensi[$nid_karyawan][$i]['nid']; ?>" title="<?php print $keteranganabsen; ?>" class="center input_absensi <?php print $hadirClass; ?>" style="background-color:<?php print $warna_bg; ?>;">&nbsp;</td>
        <?php
      		}else{
      		$array_absensi[$nid_karyawan][$i]['nik'] = $view->result[$count]->node_data_field_nik_field_nik_value;	
      		$array_absensi[$nid_karyawan][$i]['nama'] = $view->result[$count]->node_title;
      		if ($bulan < 10){
      			$bulan = '0'.(int)$bulan;	
      		}
      		if ($i < 10){
      			$hari = '0'.(int)$i;
      		}else{
      			$hari = $i;
      		}
      		$tanggal_absensi = $tahun.'-'.$bulan.'-'.$hari;
      		$array_absensi[$nid_karyawan][$i]['tanggal'] = $tanggal_absensi;
      	?>
        <td id="absensi_result_<?php print $view->result[$count]->nid.'_'.$i; ?>" title="<?php print $keteranganabsen; ?>" class="center input_absensi" style="background-color:<?php print $warna_bg; ?>;">&nbsp;</td>
        <?php	
      		}
      	}
        ?>
      </tr>
    <?php endforeach; ?>
    <?php
    	php_to_drupal_settings(array('array_status_absensi' => $array_status_absen));
    	php_to_drupal_settings(array('array_absensi_karyawan' => $array_absensi));
    	php_to_drupal_settings(array('jumlahkaryawan' => $no));
    ?>
  </tbody>
</table>
