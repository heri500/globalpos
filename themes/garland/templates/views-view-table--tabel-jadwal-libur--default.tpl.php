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
 $lebarkolom = array(100,600,185);
?>
<table id="tabel-jadwal-libur" title="Jadwal Hari Libur Nasional" class="easyui-datagrid <?php print $class; ?>"<?php print $attributes; ?> style="width:910px;height:300px;" 
	data-options="striped: true,singleSelect:true,toolbar:'#form-jadwal-libur', onClickRow : function(rowIndex, rowData){ 
		selected_jadwal_row = rowIndex;
		$('#selected-jadwal-value').html(rowData['title'].trim()); 
		$('#selected-jadwal-tanggal').html(rowData['field_tanggal_hari_libur_value'].trim()); 
		$('#selected-jadwal-workshop').html(rowData['field_hari_libur_workshop_ref_nid'].trim()); 
	}">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<?php $i = 0; ?>
      <?php foreach ($header as $field => $label): ?>
      <?php
      	$fieldname = $fields[$field];
      	$pecah_field = explode('-',$fieldname);
      	$newfield = '';
      	if (count($pecah_field) > 1){
      		$j = 0;
      		foreach ($pecah_field as $value){
      			if ($j == 0){
      				$newfield = $value;
      			}else{
      				$newfield .= '_'.$value;
      			}
      			$j++;
      		}
      	}else{
      		$newfield = $fields[$field];
      	}
      ?>
        <th data-options="field:'<?php print $newfield; ?>',width:<?php print $lebarkolom[$i]; ?>" class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php $i++; ?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<div id="form-jadwal-libur" style="padding: 5px;">
	<input class="easyui-combobox" id="pilih-hari-libur" name="pilih-hari-libur"  
            data-options="  
                    url:'<?php print base_path(); ?>hrd/liburnasional.json',  
                    valueField:'nid',  
                    textField:'node_title',  
                    panelHeight:'auto'
            " style="width: 270px;">
  <input class="easyui-combobox" id="cabang-selection" name="cabang-selection"  
            data-options="  
                    url:'<?php print base_path(); ?>cabang.json',  
                    valueField:'nid',  
                    textField:'title',  
                    panelHeight:'auto'
            " style="width: 210px;">          
  <input type="text" id="tanggal" name="tanggal" style="width: 100px">         
	<a id="simpan-jadwal-libur" class="easyui-linkbutton" data-options="iconCls:'icon-save'" onclick="tambah_jadwal_libur()" plain="true">Tambah</a>&nbsp;
	<a id="cancel-edit-jadwal-libur" style="display: none;" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" plain="true" onclick="cancel_edit_jadwal_libur()">Cancel</a>
	<a id="remove-jadwal-libur" style="display: none;" class="easyui-linkbutton" data-options="iconCls:'icon-remove'" plain="true" onclick="remove_jadwal_libur()">Delete</a>
	<a style="float: right;" class="easyui-linkbutton" iconCls="icon-edit" onclick="edit_jadwal_libur();" plain="true">Edit</a>
</div>
<div id="selected-jadwal-value" style="display: none"></div>
<div id="selected-jadwal-tanggal" style="display: none"></div>
<div id="selected-jadwal-workshop" style="display: none"></div>