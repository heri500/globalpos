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
 /*drupal_add_js('
 	$(document).ready(function(){
 		$(".editorbutton").click(function(){
			alert("Tes");
			$("#dialog-edit-jurnal").dialog("open");
		});
	})
	','inline');*/
	global $pettycashmode;
?>
<table class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th class="table_button"></th>
      <?php foreach ($header as $field => $label): ?>
      	<?php
      	if (trim($fields[$field]) <> 'field-nilai-transaksi-value-1' && trim($fields[$field]) <> 'nid' && trim($fields[$field]) <> 'nid-1' && trim($fields[$field]) != 'field-posisi-account-jurnal-value'){
      	?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php 
      	}
        ?>
      <?php endforeach; ?>
      <th class="table_button"></th>
    </tr>
  </thead>
  <tbody>
  	<?php
  		global $totaltransaksi;
  		global $totaldebet;
	    global $totalkredit;
  		$totaltransaksi = 0;
  		$totaldebet = 0;
  		$totalkredit = 0;
  	?>
    <?php foreach ($rows as $count => $row): ?>
    	<?php
    		$totaltransaksi = $totaltransaksi + $row['field_nilai_transaksi_value_1'];
    		if ($row['field_posisi_account_jurnal_value'] == 'Debet'){
    			$totaldebet = $totaldebet + $row['field_nilai_transaksi_value_1'];
    		}else{
    			$totalkredit = $totalkredit + $row['field_nilai_transaksi_value_1'];
    		}
    	?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
      		$nid_jurnal = $rows[$count]['nid'];
      		$value_to_edit = $nid_jurnal;
      		$posted_count = db_result(db_query("SELECT COUNT(*) FROM indogas_posted_jurnal WHERE nid_jurnal='%d'",$nid_jurnal));
        	if ($pettycashmode != 'view'){
	        	if ($posted_count > 0){
	        		$editbutton = '<img src="'.base_path().'misc/media/images/forbidden.png" width="16" title="Jurnal sudah diposting, repost untuk melakukan perubahan" class="icon_button">';
	        		$deletebutton = '<img src="'.base_path().'misc/media/images/forbidden.png" width="16" title="Jurnal sudah diposting, repost untuk melakukan penghapusan" class="icon_button">';
	        	}else{
	        		$editbutton = '<img onclick="opendialogedit(\''.$nid_jurnal.'\')" src="'.base_path().'misc/media/images/edit.ico" width="20" title="Klik untuk mengubah jurnal ini" class="icon_button">';
	        		$deletebutton = '<img onclick="delete_jurnal(\''.$nid_jurnal.'\',$(this).parent().parent())" src="'.base_path().'misc/media/images/delete2.png" width="16" title="Klik untuk menghapus jurnal ini" class="icon_button">';
	        	}
	        }else{
	        	$editbutton = '&nbsp;';
	        	$deletebutton = '&nbsp;';
	        }
      	?>
      	<td><?php print $editbutton; ?></td>
        <?php foreach ($row as $field => $content): ?>
          <?php
      		if (trim($fields[$field]) != 'field-nilai-transaksi-value-1' && trim($fields[$field]) != 'nid' && trim($fields[$field]) != 'nid-1' && trim($fields[$field]) != 'field-posisi-account-jurnal-value'){
      			if (trim($fields[$field]) <> 'field-nilai-transaksi-value'){
      				$value_to_edit .= '_X_'.$content;
      			}
      			if (trim($fields[$field]) == 'field-account-ref-nid'){
      				$content_view = '<div id="account_ref_'.$nid_jurnal.'">'.$content.'</div>';	
      			}else if(trim($fields[$field]) == 'field-nama-account-value'){
      				$content_view = '<div id="nama_account_ref_'.$nid_jurnal.'">'.$content.'</div>';		
      			}else if(trim($fields[$field]) == 'field-transaksi-value'){	
      				$content_view = '<div id="ket_transaksi_'.$nid_jurnal.'">'.$content.'</div>';	
      			}else{
      				$content_view = $content;
      			}
      		?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content_view; ?>
          </td>
          <?php
	      	}else{
	      		if (trim($fields[$field]) == 'field-nilai-transaksi-value-1' || trim($fields[$field]) == 'nid-1'){
	      			if (trim($fields[$field]) == 'field-nilai-transaksi-value-1'){
	      				$value_to_edit .= '_X_'.number_format($content,0);
	      			}else{
	      				$value_to_edit .= '_X_'.$content;
	      			}
	      		}
	      	}
	      	?>
        <?php endforeach; ?>
        <td><?php print $deletebutton; ?></td>
        <input type="hidden" id="data_jurnal_<?php print $nid_jurnal; ?>" name="data_jurnal_<?php print $nid_jurnal; ?>" value="<?php print $value_to_edit; ?>" >
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>