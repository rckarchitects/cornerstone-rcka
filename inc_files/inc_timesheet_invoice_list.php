<?php

if (intval($_GET[proj_id]) > 0) { $proj_id = intval($_GET[proj_id]); } elseif (intval($_POST[proj_id]) > 0) { $proj_id = intval($_POST[proj_id]); }


ProjectSwitcher("timesheet_invoice_list",$proj_id,1,1);


echo "<h2>Invoices</h2>";

	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
	ProjectSubMenu($proj_id,$user_usertype_current,"project_invoice",2);
	ProjectInvoices($proj_id,"project_invoice");
