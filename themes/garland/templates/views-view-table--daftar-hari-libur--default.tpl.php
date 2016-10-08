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
 $lebarkolom = array(700,188);
?>
<table id="tabel-hari-libur-nasional" title="Daftar Hari Libur Nasional" class="easyui-datagrid <?php print $class; ?>"<?php print $attributes; ?> style="width:910px;height:300px;" data-options="striped: true,singleSelect:true,toolbar:'#form-hari-libur', onClickRow : function(rowIndex, rowData)
	{ 
		selected_row = rowIndex;
		$('#selected-value').html(rowData['title'].trim());
		$('#selected-jenis').html(rowData['field_jenis_hari_libur_value'].trim());
	}">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<?php
    	$index_kolom = 0;
    	?>
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
        <th data-options="field:'<?php print $newfield; ?>',width:<?php print $lebarkolom[$index_kolom]; ?>" class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
      <?php 
      $index_kolom++;
      endforeach; 
     	?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
        	<?php
        	if ($fields[$field] == 'field-jenis-hari-libur-value'){
        		if ($view->result[$count]->node_data_field_jenis_hari_libur_field_jenis_hari_libur_value){
        			$content = '<div id="'.$view->result[$count]->nid.'-'.$view->result[$count]->node_data_field_jenis_hari_libur_field_jenis_hari_libur_value.'">'.$content.'</div>';
        		}else{
        			$content = '<div id="'.$view->result[$count]->nid.'-0">-</div>';
        		}
        	}
        	?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<div id="form-hari-libur" style="padding: 5px;">
	<input type="text" id="nama-hari-libur" name="nama-hari-libur" style="float: left;width: 270px;height: 24px;padding:0 4px;margin-right: 10px;">
	<select id="jenis-hari-libur" name="jenis-hari-libur" style="width: 170px;height: 26px;padding:4px;margin-right: 10px;">
	<option value="0">Libur Nasional</option>
	<option value="1">Libur Hari Besar/Hari Raya</option>
	</select>
	<a id="simpan-hari-libur" class="easyui-linkbutton" data-options="iconCls:'icon-save'" onclick="tambah_hari_libur()" plain="true">Tambah</a>&nbsp;
	<a id="cancel-edit-hari-libur" style="display: none;" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" plain="true" onclick="cancel_edit_hari_libur()">Cancel</a>
	<a style="float: right;" class="easyui-linkbutton" iconCls="icon-edit" onclick="edit_hari_libur();" plain="true">Edit</a>
</div>
<div id="alert-dialog" class="easyui-dialog" title="Perhatian" data-options="closed: true,modal: true,iconCls: 'icon-no',onClose: function(){ $('#nama-hari-libur').focus(); }" style="width:400px;height:90px;padding:10px;">  
	Mohon isi field nama hari libur sebelum menekan tombol Tambah...!!!
</div> 
<div id="selected-value" style="display: none"></div>
<div id="selected-jenis" style="display: none"></div>