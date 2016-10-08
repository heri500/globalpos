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
 	foreach ($user->roles as $user_role){
 		if (trim($user_role) == 'Super User'){
 			$superuser = true;		
 		}
 	}
 	if ($superuser){
 		$alamat_tambah_jabatan = base_path().'node/add/jabatan';	
 	}
 	if ((arg(2) <> $id_posisi_user) AND !$superuser){
 		drupal_goto('hrd');
 	}
/*<button onclick="goto_address('<?php print $alamat_tambah_jabatan; ?>')" class="add_data">TAMBAH JABATAN</button>*/
?>
<table id="tabel_jabatan" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th class="table_button">No.</th>
      <?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>"><?php print $label; ?></th>
        <?php } ?>
      <?php endforeach; ?>
      <th class="table_button">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
    	<?php
      	$alamatedit = base_path().'node/'.$rows[$count]['nid'].'/edit?destination=node/add/jabatan';
      	$nomer = $count + 1;
      	$urutanjabatan = $view->result[$count]->node_data_field_urutan_tampil_jabatan_field_urutan_tampil_jabatan_value;
      	if (!$urutanjabatan > 0){
      		$node_jabatan = node_Load($rows[$count]['nid']);
      		$node_jabatan->field_urutan_tampil_jabatan[0]['value'] = $nomer;
      		node_save($node_jabatan);
      	}
      	$idbaris = $count + 100;
      ?>
      <tr data-position="<?php print $nomer; ?>" id="<?php print $rows[$count]['nid']; ?>" class="<?php print implode(' ', $row_classes[$count]); ?>">
        <td class="angka"><?php print $nomer; ?></td>
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid'){ ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php } ?>
        <?php endforeach; ?>
        <td align="center"><img src="<?php print base_path().'misc/media/images/edit.ico'; ?>" onclick="goto_address('<?php print $alamatedit; ?>')" width="20" title="Klik untuk mengubah kategori material ini" class="icon_button"></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>