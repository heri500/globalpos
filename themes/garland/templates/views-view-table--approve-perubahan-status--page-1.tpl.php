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
<div class="approval_form">
<div class="form_row" style="margin-bottom:10px;"><button id="select_all" class="filter-button" style="font-size:12px;">Select All</button>&nbsp;&nbsp;<button id="de_select_all" class="filter-button" style="font-size:12px;">Deselect All</button></div>
<table id="tabel_perubahan_status" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th class="table_button">&nbsp;</th>
      <?php foreach ($header as $field => $label): ?>
        <?php
        	if ($field != 'nid'){
        ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
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
        	$nid_proses_perubahan = $rows[$count]['nid'];
        ?>
        <td class="table_button"><input type="checkbox" checked id="<?php print $nid_proses_perubahan; ?>" name="<?php print $nid_proses_perubahan; ?>" value="1"></td>
        <?php foreach ($row as $field => $content): ?>
        	<?php
	        	if ($field != 'nid'){
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
<div class="form_row" style="margin-top:5px;"><button id="approve_selected" class="filter-button" style="font-size:12px;">APPROVE PERUBAHAN</button></div>
<?php
	$arraymessage['title'] = t('Sebelum melakukan approval pastikan :');
	$arraymessage['content'][0] = t('Perubahan status yang akan di approve sudah dipilih(dicentang).');
	$arraymessage['content'][1] = t('Perubahan status yang tidak ingin di approve harus dihilangkan tanda centang nya.');
	$pesanperingatan = create_warning_message($arraymessage);
	print $pesanperingatan;
?>
</div>