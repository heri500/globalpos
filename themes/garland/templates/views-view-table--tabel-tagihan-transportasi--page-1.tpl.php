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
?>
<table id="tabel_tagihan_transportasi" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
      	<?php
      	if ($field != 'nid' && $field != 'status'){
      	?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php
        }else{
        ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?> table_button">
        	&nbsp;
      	</th>	
        <?php
        }
        ?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
      	$nid_penukaran = $rows[$count]['nid'];
      	$status_penukaran = $rows[$count]['status'];
      	?>
        <?php foreach ($row as $field => $content): ?>
        	<?php
        	if ($field != 'nid' && $field != 'status'){
        	?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php
        	}else{
        		if ($field == 'nid'){
        			if ($status_penukaran == 'No'){
        				$content = '<img onclick="edit_penukaran(\''.$nid_penukaran.'\')" src="'.base_path().'misc/media/images/edit.ico" width="20" title="Klik untuk mengubah tagihan transportasi ini">';
        			}else{
        				$content = '<img src="'.base_path().'misc/media/images/forbidden.png" width="16" title="Tagihan transportasi tidak dapat diubah karena sudah di approve, silahkan hubungi pusat untuk melakukan perubahan">';	
        			}
        		}else{
        			if (trim($content) == "No"){
        				$content = '<img src="'.base_path().'misc/media/images/warning.png" width="16" title="Tagihan transportasi belum diapprove">';
        			}else{
        				$content = '<img src="'.base_path().'misc/media/images/check.png" width="16" title="Tagihan transportasi belum diapprove">';
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
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>