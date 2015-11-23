<?php
include("FCKEditor/fckeditor.php") ;


$oFCKeditor = new FCKeditor('FCKeditor1') ;
$oFCKeditor->BasePath = '/FCKeditor/';
$oFCKeditor->Value = 'Default text in editor';
$oFCKeditor->Create() ;




?>
