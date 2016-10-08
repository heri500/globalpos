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
<button id="add-kategori-cashflow">Input Kategori Cashflow</button>
<table id="tabel-kategori-cashflow" class="<?php print $class; ?> display"<?php print $attributes; ?>>
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <th class="table_button">&nbsp</th>
      <th class="table_button">&nbsp</th>
      <th class="table_button">&nbsp</th>
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
      		$detail_cashflow = create_kategori_cashflow_detail($view->result[$count]->nid);
      		$tabel_detail = create_kategori_cashflow_detail_table($detail_cashflow);
      		$tabel_detail_warpper = '<div id="detail-kategori-'.$view->result[$count]->nid.'" style="display: none;">'.$tabel_detail.'</div>';
      		$icon_detail = '<img title="Klik untuk melihat detail kategori cashflow" src="'.base_path().'misc/media/images/details_open.png" onclick="view_detail_kategori(this.parentNode.parentNode,this,\''.$view->result[$count]->nid.'\');">'; 
      		$icon_add_detail = '<img title="Klik untuk menambah detail kategori cashflow" src="'.base_path().'misc/media/images/add.ico" width="20" onclick="add_detail_kategori(\''.$view->result[$count]->nid.'\');">'; 
      		$icon_edit_detail = '<img title="Klik untuk edit kategori cashflow" src="'.base_path().'misc/media/images/edit.ico" width="20" onclick="edit_kategori(\''.$view->result[$count]->nid.'\');">'; 
      	?>
      	<td><?php print $icon_detail.$tabel_detail_warpper; ?></td>
      	<td><?php print $icon_add_detail; ?></td>
      	<td><?php print $icon_edit_detail; ?></td>
        <?php foreach ($row as $field => $content): ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>