<?php
function page_suggestions_sidebar($values) {
	global $LNG, $pluginsSettings, $db;
	
	$url = $values["site_url"];
	$likedPages = get_liked_pages($values);
	
	// If there are pages available, exclude them
	if($likedPages) {
	} else {
		$likedPages = "0";
	}

	$query = $db->query(sprintf("SELECT id, name, title, image, verified FROM pages WHERE id NOT IN (%s) AND image != 'default.png' ORDER BY RAND() DESC LIMIT 2", $likedPages));

	$rows = array();

	// return;

	// Store the array results
	while($row = $query->fetch_assoc()) {
		$rows[] = $row;
	}
	
	if(!empty($rows)) {
		$i = 0;
		$output = '<div class="sidebar-container widget-page-suggestions"><div class="sidebar-content"><div class="sidebar-header">'.$LNG['plugin_page_suggestions_title'].'</div><div class="sidebar-padding">';
		foreach($rows as $row) {
			if($i == 2) break; // Display only the last 6 suggestions

			// Add the elemnts to the array
			$output .= '<a href="'.permalink($url.'/index.php?a=page&name='.$row['name']).'" rel="loadpage"><div class="sidebar-subscriptions"><div class="sidebar-title-container"><div class="sidebar-title-name">'.$row['title'].'</div></div><img src="'.permalink($url.'/image.php?t=a&w=112&h=112&src='.$row['image']).'"></div></a>';
			$i++;
		}
		$output .= '</div></div></div>';
		return $output;
	}
}

function get_liked_pages($values){
	global $db;
	$query = sprintf("SELECT post FROM likes WHERE type = 2 AND `by` = %s;", $db->real_escape_string($values["idu"]));
	// Run the query
	$result = $db->query($query);

	// The array to store the subscribed pages
	$pages = array();
	while($row = $result->fetch_assoc()) {
		$pages[] = $row['post'];
	}

	// Close the query
	$result->close();

	// Return the liked page list (e.g: 13,22,19)
	return implode(',', $pages);
}
?>