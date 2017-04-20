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
 $column_width = array(120,220,120,80);
 $i = 0;
?>
<table id="tabel-status-po" title="PO Status Report" style="width:950px;height:350px;" class="easyui-datagrid <?php print $class; ?>"<?php print $attributes; ?> data-options="singleSelect:true,striped: true,rowStyler: function(index,row){ return 'vertical-align:top;'; }">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
        <th data-options="field:'<?php print  $fields[$field]; ?>'" width="<?php print $column_width[$i]; ?>" class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
      <?php $i++; ?>  
      <?php endforeach; ?>
      <th data-options="field:'penerimaan'" width="400">Data Penerimaan</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
    <?php
    	$nid_po = $view->result[$count]->nid;
    	$args = array($nid_po);
    	$array_penerimaan = create_array_from_view('array_all_penerimaan_by_po',$args);
    	$detailpenerimaan = create_accordion_penerimaan($array_penerimaan,$nid_po);
    ?>
    <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
        <td valign="top"><?php print $detailpenerimaan; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>