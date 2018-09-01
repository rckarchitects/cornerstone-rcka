<?php

ProjectSwitcher("timesheet_invoice_list",$proj_id,1,1);


echo "<h2>Invoices</h2>";

	ProjectSubMenu($proj_id,$user_usertype_current,"project_view",1);
	ProjectSubMenu($proj_id,$user_usertype_current,"project_invoice",2);
	ProjectInvoices($proj_id,"project_invoice");
