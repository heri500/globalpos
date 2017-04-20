<?php foreach ($rows as $count => $row): ?>
	<?php print '<option value="'.$rows[$count]['nid'].'" >'.$rows[$count]['title'].'</option>'; ?>
<?php endforeach; ?>