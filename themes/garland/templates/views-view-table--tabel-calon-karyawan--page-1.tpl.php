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
?>
<table id="tabel-calon-karyawan" class="display <?php print $class; ?>"<?php print $attributes; ?>>
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
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      <?php
      $nid_karyawan = $view->result[$count]->nid;
      $alamatedit = base_path().'node/'.$nid_karyawan.'/edit?destination=hrd/calonkaryawan';
      $alamathapus = base_path().'node/'.$nid_karyawan.'/delete?destination=hrd/calonkaryawan';
      $iconedit = '<img onclick="window.location=\''.$alamatedit.'\'" width="20" src="'.base_path().'sites/all/libraries/images/edit.ico" title="Klik untuk mengubah data karyawan ini">';
      $icondelete = '<img onclick="window.location=\''.$alamathapus.'\'" width="20" src="'.base_path().'sites/all/libraries/images/del.ico" title="Klik untuk menghapus data karyawan ini">';
      ?>
      	<td class="center"><?php print $iconedit; ?></td>
      	<td class="center"><?php print $icondelete; ?></td>
        <?php foreach ($row as $field => $content): ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>