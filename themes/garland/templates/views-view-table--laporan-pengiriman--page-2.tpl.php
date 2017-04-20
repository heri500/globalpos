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
?>
<div style="float:left;margin-bottom:10px;">
	<img id="view_all_detail" src="<?php print base_path(); ?>sites/all/libraries/datatables/images/details_open.png" style="cursor:pointer;float:left;margin-right: 10px;"><span id="keterangan_icon" style="height:22px;float:left;margin-right:10px;">Klik untuk melihat semua detail pengiriman</span>
</div>
<div class="form_row" style="margin-bottom:10px;"><button id="select_all" class="filter-button" style="font-size:12px;">Select All</button>&nbsp;&nbsp;<button id="de_select_all" class="filter-button" style="font-size:12px;">Deselect All</button></div>
<table id="tabel_pengiriman" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th class="table_button">
    		&nbsp;
    	</th>
    	<?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>">
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
      <th class="table_button">&nbsp;
    	</th>
    </tr>
  </thead>
  <tbody>
  	<?php foreach ($rows as $count => $row): ?>
  		<?php
  			$nid_kirim = $rows[$count]['nid'];
  		?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<td align="center">
      		<img class="detail_icon" onclick="see_details(this.parentNode.parentNode,this,'<?php print $nid_kirim; ?>')" src="<?php print base_path(); ?>sites/all/libraries/datatables/images/details_open.png" style="cursor:pointer;">	
      	</td>
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid'){ ?>
        	<?php
        		if (trim($fields[$field]) <> 'field-armada-ref-value'){
        	?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
            <?php print $content; ?>
          </td>
          <?php 
        		}else{
	        		if ($superuser){
	        			$node_armada = node_load($content);
	        		?>
	        		<td class="views-field views-field-<?php print $fields[$field]; ?>">
	            	<?php print $node_armada->field_no_plat[0]['value']; ?>
	          	</td>
	        		<?php		
	        		}else{
	        		?>
	        		<td class="views-field views-field-<?php print $fields[$field]; ?>">
	            	<?php print $content; ?>
	          	</td>
	        		<?php	
	        		}
        		}
          }else{
          	$nidpengiriman = $content; 
	        	$cekboxname = 'input_pengiriman_'.$content;
	        	$inputcekbox = '<input type="checkbox" id="'.$cekboxname.'" name="'.$cekboxname.'" value="'.$content.'" checked="checked">';
	        	$detailpengiriman = views_embed_view("tabel_detail_pengiriman","default",$nidpengiriman);
	        	$inputdetail = '<div id="detail_'.$nidpengiriman.'" style="display:none">'.$detailpengiriman.'</div>';
	        } 
          ?>
        <?php endforeach; ?>
        <td align="center">
        <?php print $inputcekbox.$inputdetail; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>