<?php

			echo "<h1>Contacts</h1>";
			echo "<h2>List of Disciplines</h2>";

ProjectSubMenu(NULL,$user_usertype_current,"contacts_admin",1);

echo "<div class=\"page\">";

ContactsDisciplines();

echo "</div>";