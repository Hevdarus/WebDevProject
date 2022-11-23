<?php
if (count($errors) > 0) {
	echo '<div class="error">';
	foreach ($errors as $error) {
		echo '<p>' . $error . '</p>';
	}
	echo '</div>';

	if ($errors[0] == "Hibás jelszó!") {

		header('refresh:3; location://police.hu');

	}
}
?>
