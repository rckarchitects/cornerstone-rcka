<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=ANSI" />
  
  <?php
  
  if ($settings_refresh > 0 AND $_SERVER['QUERY_STRING'] == NULL) { 
  echo "<meta http-equiv=\"refresh\" content=\"$settings_refresh\" />";
  }
  
  echo "<meta name=\"robots\" content=\"noindex\">";
  
  echo "<title>" . $settings_name . "</title>";
  
  $font_file = "skins/" . $settings_style . "/font.inc";
  if (file_exists($font_file)) { echo file_get_contents($font_file); }
  
  echo "<link href=\"https://fonts.googleapis.com/css?family=Work+Sans&display=swap\" rel=\"stylesheet\">";
  
  echo "
  
  <link rel=\"search\" href=\"opensearchdescription.xml\"
      type=\"application/opensearchdescription+xml\"
      title=\"$settings_name\" />
	  
  <link rel=\"StyleSheet\" type=\"text/css\" href=\"skins/$settings_style/styles.css\" />
  
  <link rel=\"StyleSheet\" type=\"text/css\" media=\"print\" href=\"skins/printstyles.css\" />

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
			
			
			<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js\"></script>

			<script type=\"text/javascript\">
			
				$(document).ready(function(){
					$(\".alert_delete\").click(function(){
					
					 var DelID = $(this).val();
					 var LoadPage = \"ajax.php?action=alert_delete&alert_id=\" + DelID;
					 var TargetAlert = \"target_\" + DelID;
					 
						$.get(LoadPage);
						$(\"#\" + TargetAlert).fadeOut(\"3000\");
			
					 });
				});
				</script>
				
				<script type=\"text/javascript\">
				function myFunction() {
					var popup = document.getElementById(\"myPopup\");
					popup.classList.toggle(\"show\");
				}
				</script>
				
				
				<script type=\"text/javascript\">
				function ShowProjectSwitcher() {
					var HideDIV = document.getElementById(\"project_title\");
					var ShowDIV = document.getElementById(\"project_switcher\");
					HideDIV.style.display = \"none\";
					ShowDIV.style.display = \"block\";
				}
				</script>
				
				<script type=\"text/javascript\">
				function HideProjectSwitcher() {
					var ShowDIV = document.getElementById(\"project_title\");
					var HideDIV = document.getElementById(\"project_switcher\");
					HideDIV.style.display = \"none\";
					ShowDIV.style.display = \"block\";
				}
				</script>
				
				";
				
		if (($_GET[page] == "tasklist_project" OR $_GET[page] == "tasklist_view") && $_GET[view] != "complete") {
				
				echo "<script>
					
						function StrikeThough(task){
						
							var ele = document.getElementsByClassName(task);
							for(var i=0;i<ele.length;i++){
								ele[i].style.textDecoration='line-through'
							}
							
							document.getElementById(task).disabled = true;
												
							var url=\"ajax.php?action=task_complete&task_id=\" + task;
							
							$.get(url);
							
							
						}
					
				</script>";
				
		} elseif ($_GET[page] == "tasklist_project" && $_GET[view] == "complete") {
				
				echo "<script>
					
						function UnStrikeThough(task){
						
							var ele = document.getElementsByClassName(task);
							for(var i=0;i<ele.length;i++){
								ele[i].style.textDecoration='none'
							}
							
							document.getElementById(task).disabled = true;
												
							var url=\"ajax.php?action=task_uncomplete&task_id=\" + task;
							
							$.get(url);

							
						}
					
				</script>";
			
		}


echo "<script src=\"/plugins/tinymce/tinymce.min.js\"></script>";

if ($module_contacts == 1) { ToggleTargetList(); }


echo "
	
</head>

<body>

";
