<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Image Gallery</title>
		<meta name="author" content="Yassin" />
		<link rel="stylesheet" type="text/css" href="style.css">
		<script>
			var max = <?php
				/* PHP code to determine how many pictures to scroll through and store that number in a javascript variable*/
				$current = "";
				if (array_key_exists("dir", $_GET)) {
					$current = str_replace('[', '[[]', $_GET["dir"].'/');
				}
				echo count(glob("".$current."*.{jpg,png,gif,jpeg}", GLOB_BRACE));
			?>;
			/* Javascript code to handle keyboard input to switch between images */
			var dir = document.location.href;
			document.addEventListener('keydown', function(event) {
			    if(event.keyCode == 37 || event.keyCode == 65) { //Left key
			    	if (dir.indexOf('id') >= 0) {
			    		if (parseInt(dir.substr(dir.indexOf('id') + 3)) > 0) {
			    			document.location.href = dir.substr(0, dir.indexOf('id') + 3) + (parseInt(dir.substr(dir.indexOf('id') + 3)) - 1);
			    		}
			    	}
			    }
			    else if(event.keyCode == 39 || event.keyCode == 68) { //Right key
			    	if (document.location.href.indexOf('id') >= 0) {
			    		if (parseInt(dir.substr(dir.indexOf('id') + 3)) < max - 1) {
			    			document.location.href = dir.substr(0, dir.indexOf('id') + 3) + (parseInt(dir.substr(dir.indexOf('id') + 3)) + 1);
			    		}
			    	} else {
			    		if (dir.indexOf('dir') >= 0) {
			    			document.location.href += '&id=1';
			    		} else {
			    			document.location.href += '?id=1';
			    		}
			    		
			    	}
			    } else if (event.keyCode == 83) { //Emulate down key with S
			    	window.scrollBy(0, 40);
			    } else if (event.keyCode == 87) { //Emulate up key with W
			    	window.scrollBy(0, -40);
			    }
			});
			</script>
	</head>
	<body>
		<div>
			<?php
				$directory = "";
				$current = "";
				$id = 0;
				
				/* Code to deal with link to go to previous directory. */
				if (array_key_exists("dir", $_GET)) {
					if ($_GET["dir"] != "" && $_GET["dir"][0] != ".") { //Prevents abuse of directories by inputting ".." or "" into the URL.
						$current = $_GET["dir"] . '/';
						$directory .= str_replace('[', '[[]', $_GET["dir"]).'/';
						echo "<a href=\"?dir=" . substr(substr($current, 0, strrpos($current, '/')), 0, strrpos(substr($current, 0, strrpos($current, '/')), '/')) . "\">..</a><br />\n\t\t\t";
					}
				}
				
				/* Get and store current image index, if any. */
				if (array_key_exists("id", $_GET)) {
					$id = $_GET["id"];
				}
				
				/* Array_map to filter directories to exclude full path from directories. */
				$dirname = array_map('basename', glob("$directory*", GLOB_ONLYDIR));
				
				/* Array used to replace common special characters with HTML-safe alternatives. */
				$encoding = array(array('+', ' ', '&'), array('%2B', '%20', '%26'));
				
				/* Print a link for every subdirectory of current directory. */
				for ($i = 0; $i < count($dirname); $i++) {
					echo "<a href=\"?dir=" . str_replace('[', '[[]', $current) . str_replace($encoding[0], $encoding[1], $dirname[$i]) . "\">$dirname[$i]</a><br />\n\t\t\t";
				}
				
				/* Store array of images in current directory. If there is at least one image, display it. */
				$images = glob("$directory*.{jpg,png,gif,jpeg}", GLOB_BRACE);
				if (count($images)) echo '<img src="' . $images[$id] . '" /><br />' . "\n"; 
			?>
		</div>
	</body>
</html>