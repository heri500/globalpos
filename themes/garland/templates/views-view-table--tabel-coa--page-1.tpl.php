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
 $datatables = true;
 drupal_add_css('misc/media/datatables/css/ColVis.css');
 _include_jquery_plugins($datatables);
 drupal_add_js('misc/media/datatables/js/ColVis.min.js');
 drupal_add_js('
  function goto_address(address_to_go){
  	window.location = address_to_go;
  }
  $(document).ready(function(){
	 	oTable = $("#tabel_coa").dataTable( {
			"bJQueryUI": true,
			"bPaginate": true,
			"bLengthChange": true,
			"bFilter": true,
			"bSort": false,
			"bInfo": true,
			"bAutoWidth": false,
			"bStateSave": true,
			"sDom": \'<"space"T><C><"clear"><"H"lfr>t<"F"ip>\',
				"oColVis": {
					"aiExclude": [ 0 ],
					"bRestore": true
				}
		});
		$("button").button();
	})
 ','inline');
 	global $user;
	$superuser = false;
 	foreach ($user->roles as $user_role){
 		if (trim($user_role) == 'Super User'){
 			$superuser = true;		
 		}
 	}
 	if ($superuser){
 		$alamat_tambah_subkategori = base_path().'node/add/chart-of-account';	
 	}
 	if ((arg(2) <> $id_posisi_user) AND !$superuser){
 		drupal_goto('akutansi');
 	}
?>
<button onclick="goto_address('<?php print $alamat_tambah_subkategori; ?>')" class="add_data">TAMBAH CHART OF ACCOUNT</button>
<table id="tabel_coa" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
    	<th class="table_button">	
    	</th>
      <?php foreach ($header as $field => $label): ?>
      	<?php if (trim($fields[$field]) <> 'nid'){ ?>
      	<?php
      		if ($field == 'title' || $field == 'field_kategori_coa_ref_nid' || $field == 'field_subkategori_coa_ref_nid'){
      			$lebar = 'style="width: 110px;"';	
      		}else{
      			$lebar = '';
      		}
      	?>
        <th class="views-field views-field-<?php print $fields[$field]; ?>" <?php print $lebar; ?> >
          <?php print $label; ?>
        </th>
        <?php } ?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
      	<?php
      		$alamatedit = base_path().'node/'.$rows[$count]['nid'].'/edit';
      	?>
        <td align="center"><img src="<?php print base_path().'misc/media/images/edit.ico'; ?>" onclick="goto_address('<?php print $alamatedit; ?>')" width="20" title="Klik untuk mengubah kategori material ini" class="icon_button"></td>
        <?php foreach ($row as $field => $content): ?>
        	<?php if (trim($fields[$field]) <> 'nid'){ ?>
          <td class="views-field views-field-<?php print $fields[$field]; ?>">
          	<?php
          		$content = str_replace("<p>","",$content);
          		$content = str_replace("</p>","",$content);
          	?>
            <?php print $content; ?>
          </td>
          <?php } ?>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<button onclick="goto_address('<?php print $alamat_tambah_subkategori; ?>')" class="add_data">TAMBAH CHART OF ACCOUNT</button>