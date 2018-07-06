<?php

echo "<h1>Media Library</h1>";

if ($_GET[action] == "upload") {
	MediaUploadForm();
} else {
	MediaBrowse($_GET[filter]);
}