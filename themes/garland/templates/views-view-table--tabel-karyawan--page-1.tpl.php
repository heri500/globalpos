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
 
	//dpm($rows);
?>
<table id="tabel_karyawan_indogas" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th class="table_button">	
    	</th>
    	<th class="table_button">	
    	</th>
      <?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid' && trim($fields[$field]) <> 'status'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
      <th class="table_button">	
      	&nbsp;
    	</th>
      <?php
      $array_perubahan_jabatan = create_array_status_perubahan_jabatan();
      //dpm($array_perubahan_jabatan);
      for ($i = 0;$i < count($array_perubahan_jabatan);$i++){
      ?>
      <th class="table_button">	
      	&nbsp;
    	</th>
      <?php	
      }
      ?>
      <th class="table_button">	
      	&nbsp;
    	</th>
    	<th class="table_button">	
      	&nbsp;
    	</th>
    	<th class="table_button">	
      	&nbsp;
    	</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
      		$nid_karyawan = $rows[$count]['nid'];
      		$status = $rows[$count]['status'];
      		$nama_karyawan = addslashes(decode_entities($rows[$count]['title']));
      		$alamatedit = base_path().'node/'.$nid_karyawan.'/edit';
      		if ($status == 'Yes'){
      			//$imagestatus = '<img title="Status Karyawan: Aktif" src="'.base_path().'misc/media/images/check.png" style="margin-left: 10px;">';	
      			$imagestatus = '<b>[A]</b>';
      		}else{
      			//$imagestatus = '<img title="Status Karyawan: Belum/Non Aktif" src="'.base_path().'misc/media/images/details_close.png" style="margin-left: 10px;">';
      			$imagestatus = '<b>[N]</b>';
      		}
      	?>
        <td align="center"><img src="<?php print base_path().'misc/media/images/edit.ico'; ?>" onclick="goto_address('<?php print $alamatedit; ?>')" width="20" title="Klik untuk mengubah data karyawan ini" class="icon_button"></td>
        <td align="center"><img src="<?php print base_path().'misc/media/images/forward_enabled.ico'; ?>" onclick="view_detail_karyawan('<?php print $rows[$count]['nid']; ?>')" width="20" title="Klik untuk melihat detail data karyawan ini" class="icon_button"></td>
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid' && trim($fields[$field]) <> 'status'){ ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php } ?>
        <?php endforeach; ?>
        <td class="center">	
      		<?php print $imagestatus; ?>
    		</td>
        <?php
        for ($i = 0;$i < count($array_perubahan_jabatan);$i++){
        	$alamattujuan = base_path().$array_perubahan_jabatan[$i]->node_data_field_destination_path_field_destination_path_value.'?nid_karyawan='.$nid_karyawan.'&proses_nid='.$array_perubahan_jabatan[$i]->nid;
        	$button_image = '<img onclick="goto_address(\''.$alamattujuan.'\')" src="'.base_path().$array_perubahan_jabatan[$i]->files_node_data_field_icon_image_filepath.'" title="'.$array_perubahan_jabatan[$i]->node_title.'" style="margin-left: 10px;cursor: pointer;">';
        	if ($array_perubahan_jabatan[$i]->node_title == 'Mutasi'){
        		$proses_array = cek_proses_mutasi_exist($nid_karyawan);
        		if (count($proses_array) > 0){
        			$alamattujuan .= '&nid_proses_mutasi='.$proses_array[0]->nid;
        			$button_image = '<img onclick="goto_address(\''.$alamattujuan.'\')" src="'.base_path().'misc/media/images/warning.png" title="Karyawan dalam proses permintaan mutasi, klik disini untuk merubah proses mutasi tersebut" style="margin-left: 10px;cursor: pointer;">';		
        		}
        	}else if ($array_perubahan_jabatan[$i]->node_title == 'Perubahan Status Ikatan Kerja'){	 
        		$args = array($nid_karyawan);
        		$proses_array = create_array_from_view('array_new_status_ik_karyawan', $args);
        		if (count($proses_array) > 0){
        			$alamattujuan .= '&nid_status_ikatan_karyawan='.$proses_array[0]->nid;
        			$button_image = '<img onclick="goto_address(\''.$alamattujuan.'\')" src="'.base_path().'misc/media/images/warning.png" title="Karyawan dalam proses permintaan perubahan status ikatan kerja, klik disini untuk merubah proses tersebut" style="margin-left: 10px;cursor: pointer;">';
        		}
        	}else if ($array_perubahan_jabatan[$i]->node_title == 'Perubahan Divisi'){	 
        		$args = array($nid_karyawan);
        		$proses_array = create_array_from_view('array_new_departemen_karyawan', $args);
        		if (count($proses_array) > 0){
        			$alamattujuan .= '&nid_departemen_karyawan='.$proses_array[0]->nid;
        			$button_image = '<img onclick="goto_address(\''.$alamattujuan.'\')" src="'.base_path().'misc/media/images/warning.png" title="Karyawan dalam proses permintaan perubahan departemen, klik disini untuk merubah proses tersebut" style="margin-left: 10px;cursor: pointer;">';
        		}
        	}else{
        		$proses_array = cek_proses_perubahan_exist($nid_karyawan);
        		if (count($proses_array) > 0){
        			$alamattujuan .= '&nid_proses_promosi_demosi='.$proses_array[0]->nid;
        			$button_image = '<img onclick="goto_address(\''.$alamattujuan.'\')" src="'.base_path().'misc/media/images/warning.png" title="Karyawan dalam proses permintaan perubahan jabatan, klik disini untuk merubah proses tersebut" style="margin-left: 10px;cursor: pointer;">';		
        		}
        	}
        ?>
        <td class="center"><?php print $button_image; ?></td>
        <?php
      	}
      	$proses_non_aktif_array = cek_proses_non_aktif_exist($nid_karyawan);
      	if (count($proses_non_aktif_array) > 0){
      		$button_non_aktif = '<img onclick="delete_proses(\''.$proses_non_aktif_array[0]->nid.'\',\''.$nid_karyawan.'\',\''.$nama_karyawan.'\',$(this))" src="'.base_path().'misc/media/images/warning.png" title="Batalkan proses penonaktifan karyawan" style="margin-left: 10px;cursor: pointer;">';
      	}else{
      		$button_non_aktif = '<img onclick="non_aktifkan_karyawan(\''.$nid_karyawan.'\',\''.$nama_karyawan.'\',$(this))" src="'.base_path().'misc/media/images/forbidden.png" title="Non aktifkan karyawan" style="margin-left: 10px;cursor: pointer;">';
      	}
      	$theme_path = drupal_get_path('theme', 'garland');
      	$proses_skorsing_array = cek_proses_skorsing_exist($nid_karyawan);
      	if (count($proses_skorsing_array) > 0){
      		$button_skorsing = '<img onclick="skors_karyawan(\''.$nid_karyawan.'\',\''.$nama_karyawan.'\',\''.$proses_skorsing_array[0]->nid.'\')" src="'.base_path().'misc/media/images/warning.png" title="Edit proses skors karyawan" style="margin-left: 10px;cursor: pointer;">';
      	}else{
      		$button_skorsing = '<img onclick="skors_karyawan(\''.$nid_karyawan.'\',\''.$nama_karyawan.'\',\'0\')" src="'.base_path().$theme_path.'/images/cancel16.png" title="Skors karyawan" style="margin-left: 10px;cursor: pointer;">';	
      	}
      	//$button_sanksi = '<img onclick="sanksi_karyawan(\''.$nid_karyawan.'\',\''.$nama_karyawan.'\',$(this))" src="'.base_path().$theme_path.'/images/scorm16.png" title="Surat Peringatan karyawan" style="margin-left: 10px;cursor: pointer;">';
      	$proses_sanksi_array = cek_proses_sanksi_exist($nid_karyawan);
      	if (count($proses_sanksi_array) > 0){
      		$button_sanksi = '<img onclick="sanksi_karyawan(\''.$nid_karyawan.'\',\''.$nama_karyawan.'\',\''.$proses_sanksi_array[0]->nid.'\')" src="'.base_path().'misc/media/images/warning.png" title="Edit proses sanksi karyawan" style="margin-left: 10px;cursor: pointer;">';
      	}else{
      		$button_sanksi = '<img onclick="sanksi_karyawan(\''.$nid_karyawan.'\',\''.$nama_karyawan.'\',\'0\')" src="'.base_path().$theme_path.'/images/scorm16.png" title="Proses pemberian sanksi karyawan" style="margin-left: 10px;cursor: pointer;">';	
      	}
        ?>
        <td class="center"><?php print $button_non_aktif; ?></td>
        <td class="center"><?php print $button_skorsing; ?></td>
        <td class="center"><?php print $button_sanksi; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<button onclick="goto_address('<?php print $alamat_tambah_karyawan; ?>')" class="add_data">TAMBAH KARYAWAN</button>
