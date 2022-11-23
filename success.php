<?php
if(count($success) > 0) {
	echo '<div class="success">';
	foreach ($success as $msg){
		echo $msg;
	}
	echo '</div>';
}
?>
