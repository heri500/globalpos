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
<table id="tabel_penerimaan" class="<?php print $class; ?> display">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        	<?php if (trim($fields[$field]) == 'field-armada-ref-value'){ ?>
        	<?php
        		$idarmada = $content;
        		$name = 'get_no_plat_by_nid';
						$display_id = 'default';
						$args = array($idarmada);
						$no_plat = views_get_view($name);
						$no_plat->set_arguments($args);
						$no_plat->set_display($display_id);
						$no_plat->pre_execute();
						$no_plat->execute();
						if (count($no_plat->result)){
					?>
					<td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $no_plat->result[0]->node_data_field_no_plat_field_no_plat_value; ?>
          </td>
					<?php		
						}else{
        	?>
        	<td class="views-field views-field-<?php print $fields[$field]; ?>">
						<?php print $idarmada; ?>
          </td>
        	<?php } ?>
        	<?php }else{ ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php } ?>
          <?php } ?>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>