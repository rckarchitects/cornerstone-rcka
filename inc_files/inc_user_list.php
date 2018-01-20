<?php

if ($user_usertype_current < 4) {
	

NotAllowed();
	
	
} else {
	
		if (intval($_GET[list_active]) == 0) { UsersList(0); }
		elseif (intval($_GET[list_active] == 1)) { UsersList(1); }

}



