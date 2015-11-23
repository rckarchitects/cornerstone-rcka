<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=ANSI" />
  
  <?php
  
  if ($settings_refresh > 0 AND $_SERVER['QUERY_STRING'] == NULL) { 
  echo "<meta http-equiv=\"refresh\" content=\"$settings_refresh\" />";
  }
  
  echo "<meta name=\"robots\" content=\"noindex\">";
  
  ?>
  <?php
  echo "
  <title>
  $settings_name
  </title>
  ";
  
  $font_file = "skins/" . $settings_style . "/font.inc";
  if (file_exists($font_file)) { echo file_get_contents($font_file); }
  
  
  
  echo "
  
  <link rel=\"search\" href=\"opensearchdescription.xml\"
      type=\"application/opensearchdescription+xml\"
      title=\"$settings_name\" />
	  
  <link rel=\"StyleSheet\" type=\"text/css\" href=\"skins/$settings_style/styles.css\" />

	<script type=\"text/javascript\">
	var current = \"1\";
	function menuSwitch(id){
    if(!document.getElementById) return false;
    var div = document.getElementById(\"page_element_\"+id);
    var curDiv = document.getElementById(\"page_element_\"+current);
    curDiv.style.display = \"none\";
    div.style.display = \"block\";
    current = id;
	}
	</script>
	
	<script type=\"text/javascript\">
	var current = \"1\";
	function itemSwitch(id){
    if(!document.getElementById) return false;
    var div = document.getElementById(\"item_switch_\"+id);
    var curDiv = document.getElementById(\"item_switch_\"+current);
    curDiv.style.display = \"none\";
    div.style.display = \"block\";
    current = id;
	}
	</script>
	

	
<script type=\"text/javascript\">
function PhoneMessageAlert()
{
if (confirm(\"You have outstanding messages. View now?\")) { location = \"index2.php?page=phonemessage_view&amp;status=view\"; }
}
</script>

<script type=\"text/javascript\">
function SelectAll(id)
{
    document.getElementById(id).focus();
    document.getElementById(id).select();
}
</script>
	
</head>
";
?>
