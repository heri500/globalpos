<?php
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
 //dpm($view);
?>
<button id="add-adjustment" style="margin-bottom: 8px;" onclick="window.location = '<?php print base_path(); ?>node/add/bank-rekonsel';">Input Adjustment</button>
<table id="tabel-bank_rekonsel" class="<?php print $class; ?> display"<?php print $attributes; ?>>
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <th class="table_button">&nbsp;</th>
      <th class="table_button">&nbsp;</th>
      <?php foreach ($header as $field => $label): ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
    <?php
    	$nid_bank_rekonsel = $view->result[$count]->nid;
    	//dpm($nid_bank_rekonsel);
    	$alamat_edit = base_path().'node/'.$nid_bank_rekonsel.'/edit';
    ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<td class="center"><img onclick="edit_bank_rekonsel('<?php print $alamat_edit;?>')" title="Klik untuk mengubah" src="<?php print base_path().'misc/media/images/edit.ico'; ?>" width="20"></td>
      	<td class="center"><img onclick="delete_bank_rekonsel(this.parentNode.parentNode,<?php print $nid_bank_rekonsel;?>)" title="Klik untuk menghapus" src="<?php print base_path().'misc/media/images/del.ico'; ?>" width="20"></td>
        <?php foreach ($row as $field => $content): ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>