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
 $buttonselectall = create_button_select_all(true);
?>
<div id="block_approve">
<?php print $buttonselectall; ?>
<table id="tabel_karyawan_baru" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
      <?php endforeach; ?>
      <th class="table_button">
    		&nbsp;
    	</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
    	<?php
    		$nid_karyawan = $view->result[$count]->nid;
    		$checkboxkaryawan = '<input class="approve-karyawan-baru" type="checkbox" checked="checked" id="cekbox_'.$nid_karyawan.'" name="cekbox_'.$nid_karyawan.'" value="'.$nid_karyawan.'">';
    	?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
        <td class="center">
    			<?php print $checkboxkaryawan; ?>
    		</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<button id="do_approve_karyawan">Approve Karyawan Baru</button>
</div>