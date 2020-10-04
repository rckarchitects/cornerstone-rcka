<?php





function ProjectSubMenu($proj_id,$user_usertype_test,$page,$level) {
	
				global $user_usertype_current;
				global $user_id_current;

				$array_menu_page = array();
				$array_menu_text = array();
				$array_menu_image = array();
				$array_menu_usertype = array();
				$array_project_access = array(); // This determines whether a particular tab should be accessible to administrators and/or project leads. 1 = yes, NULL or 0 if no
				
				$proj_id = intval($proj_id);
				$level = intval($level);

	if ($page == "project_view" && $level == 1 && intval($proj_id) > 0) {
		
				$array_menu_page[] = "index2.php?page=project_view&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Project Home";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				$array_project_access[] = 1;
			
				$array_menu_page[] = "index2.php?page=project_contacts&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Contacts";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				$array_project_access[] = 1;
			
				$array_menu_page[] = "index2.php?page=tasklist_project&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "index2.php?page=drawings_list&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Drawings";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_reviews&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Reviews";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				$array_project_access[] = 1;
			
				$array_menu_page[] = "index2.php?page=project_checklist&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Checklist";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				$array_project_access[] = 1;
			
				$array_menu_page[] = "index2.php?page=project_planningcondition_list&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Planning Tracker";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_blog_list&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Journal";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_fees&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Fees";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_list&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Invoices";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 0;
				
				$array_menu_page[] = "index2.php?page=project_particulars&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Particulars";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_risks&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Risks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_actionstream&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Action Stream";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				$array_project_access[] = 1;
				
	} elseif ($page == "project_list" && $level == 1 && intval($proj_id) == 0) {
		
				$array_menu_page[] = "index2.php?page=project_all&amp;active=1";
				$array_menu_text[] = "Active Projects";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=project_all&amp;team=" . intval(UserGetTeam($_COOKIE['user']));
				$array_menu_text[] = "Team Projects";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=project_all&amp;active=2";
				$array_menu_text[] = "Inactive Projects";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;

				
	} elseif ($page == "project_list" && $level == 2 && intval($proj_id) == 0) {
		
				$array_menu_page[] = "index2.php?page=project_edit&amp;status=add";
				$array_menu_text[] = "Add Project";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=project_blog_edit&amp;status=add";
				$array_menu_text[] = "Add Journal Entry";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
				
				
	} elseif ($page == "project_view" && $level == 2 && intval($proj_id) > 0) {

				$array_menu_page[] = "index2.php?page=project_edit&amp;status=edit&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Edit Project";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 3;
			
				$array_menu_page[] = "index2.php?page=project_edit&amp;status=add";
				$array_menu_text[] = "Add Project";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 0;
			
				$array_menu_page[] = "pdf_project_sheet.php?proj_id=" . $proj_id;
				$array_menu_text[] = "Project Sheet";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;		
				
		
	} elseif ($page == "project_invoice" && $level == 2 && intval($proj_id) > 0) {

				$array_menu_page[] = "index2.php?page=timesheet_invoice_edit&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Add Invoice";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;

				$array_menu_page[] = "index2.php?page=timesheet_invoice_items_edit&amp;proj_id=" . $proj_id . "&amp;invoice_id=" . intval($_GET['invoice_id']);
				$array_menu_text[] = "Add Invoice Item";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;

				$array_menu_page[] = "index2.php?page=timesheet_invoice_edit&amp;invoice_id=" . intval($_GET['invoice_id']);
				$array_menu_text[] = "Edit Invoice";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 3;
				
	} elseif ($page == "project_risks" && $level == 2 && intval($proj_id) > 0) {

				$array_menu_page[] = "index2.php?page=project_risks&amp;action=list&amp;view=list&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "List Risks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;

				$array_menu_page[] = "index2.php?page=project_risks&amp;action=add&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Add New Risk";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;

				if (intval($_GET[risk_id]) > 0) {
					$array_menu_page[] = "index2.php?page=project_risk&amp;action=edit&amp;risk_id=". intval($_GET['risk_id']);
					$array_menu_text[] = "Edit Risk";
					$array_menu_image[] = "button_edit.png";
					$array_menu_usertype[] = 3;
				}
				
				$array_menu_page[] = "pdf_risks.php?proj_id=" . $proj_id;
				$array_menu_text[] = "Risk Register";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "pdf_risks_matrix.php?proj_id=" . $proj_id;
				$array_menu_text[] = "Risk Matrix";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				
	} elseif ($page == "project_fee" OR $page == "project_timesheet_view" && intval($proj_id) > 0) {

				$array_menu_page[] = "index2.php?page=project_fees&proj_id=" . $proj_id;
				$array_menu_text[] = "List Fee Stages";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 1;
	
				$array_menu_page[] = "index2.php?page=project_hourlyrates_view&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Edit Hourly Rates";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 0;
				
				$array_menu_page[] = "index2.php?page=project_timesheet_view&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "View Expenditure";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "index2.php?page=timesheet_fees_edit&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Add Fee Stage";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 0;
				
				$array_menu_page[] = "pdf_fee_drawdown.php?proj_id=" . $proj_id;
				$array_menu_text[] = "View Fee Drawdown (All Stages)";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "pdf_fee_drawdown.php?proj_id=" . $proj_id . "&amp;future=1";
				$array_menu_text[] = "View Fee Drawdown (Future Only)";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "pdf_fee_drawdown.php?proj_id=" . $proj_id . "&amp;confirmed=1";
				$array_menu_text[] = "View Fee Drawdown (Confirmed Stages Only)";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "pdf_fee_drawdown.php?proj_id=" . $proj_id . "&amp;showinvoices=yes";
				$array_menu_text[] = "View Fee Drawdown (with invoices)";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 0;
				
				$array_menu_page[] = "pdf_project_performance_summary.php?proj_id=" . intval($proj_id);
				$array_menu_text[] = "Project Performance Summary";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 1;
				
				$array_menu_page[] = "index2.php?page=timesheets_resource&amp;proj_id=" . intval($proj_id);
				$array_menu_text[] = "Project Resource Summary";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				$array_project_access[] = 1;
				
	} elseif ($page == "project_contacts" && intval($proj_id) > 0) {
	
				$array_menu_page[] = "index2.php?page=project_contacts&amp;contact_proj_add=add&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Add Project Contact";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;
				
	} elseif (($page == "project_blog" OR $page == "project_blog_list" OR $page == "project_blog_edit")  && intval($proj_id) > 0 ) {
	
				$array_menu_page[] = "index2.php?page=project_blog_edit&status=add&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Add Journal Entry";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_blog_list&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "List Journal Entries";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
	} elseif ($page == "project_tasks" && intval($proj_id) > 0) {
	
				$array_menu_page[] = "index2.php?page=tasklist_edit&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Add New Task";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;
		
				$array_menu_page[] = "index2.php?page=tasklist_project&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Outstanding Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=tasklist_project&amp;view=complete&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Completed Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "pdf_tasklist.php?proj_id=" . $proj_id;
				$array_menu_text[] = "Print Project Tasks";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 1;
				
	} elseif ($page == "tasklist_view") {
	
				$array_menu_page[] = "index2.php?page=tasklist_edit";
				$array_menu_text[] = "Add New Task";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;
		
				$array_menu_page[] = "index2.php?page=tasklist_view";
				$array_menu_text[] = "Outstanding Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=tasklist_view&amp;view=complete&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Completed Tasks";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
	} elseif ( $page == "date_list") {

				$array_menu_page[] = "index2.php?page=date_list";
				$array_menu_text[] = "List Future Dates";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=date_list&amp;filter=2";
				$array_menu_text[] = "List Past Dates";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;

				$array_menu_page[] = "index2.php?page=date_edit";
				$array_menu_text[] = "Add New Dates";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;

	} elseif ( $page == "project_expenses" && intval($proj_id) > 0) {

				$array_menu_page[] = "timesheet_expense_edit&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Add Expenses";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;


	} elseif ( $page == "drawings_list" && $level == 2 && intval($proj_id) > 0) {

				$array_menu_page[] = "pdf_drawing_list.php?proj_id=" . $proj_id;
				$array_menu_text[] = "Drawing Schedule";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "pdf_drawing_matrix.php?proj_id=" . $proj_id;
				$array_menu_text[] = "Drawing Matrix";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "pdf_drawing_matrix.php?proj_id=" . $proj_id;
				$array_menu_text[] = "Drawing Matrix";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				

	} elseif ( $page == "planning_conditions" && $level == 2 && intval($proj_id) > 0) {

				if ($_GET[showdetail] == 1) {
					$array_menu_page[] = "index2.php?page=project_planningcondition_list&amp;proj_id=" . $proj_id;
					$array_menu_text[] = "Simple List";
					$array_menu_image[] = "button_list.png";
					$array_menu_usertype[] = 2;

				} else {
					$array_menu_page[] = "index2.php?page=project_planningcondition_list&amp;proj_id=$proj_id&amp;showdetail=1";
					$array_menu_text[] = "Detailed List";
					$array_menu_image[] = "button_list.png";
					$array_menu_usertype[] = 2;
				}
				
				$array_menu_page[] = "index2.php?page=project_planningcondition_edit&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Add New";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "pdf_planning_conditions.php?proj_id=" . $proj_id;
				$array_menu_text[] = "Condition Schedule";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				

	} elseif ( $page == "project_checklist" && $level == 2 && intval($proj_id) > 0) {
		
				$group_id = intval($_GET[group_id]);
				
				if ($_GET[page] == "project_checklist_edit") {
					
					$array_menu_page[] = "index2.php?page=project_checklist&amp;proj_id=$proj_id&amp;group_id=$group_id";
					$array_menu_text[] = "Back to List";
					$array_menu_image[] = "button_list.png";
					$array_menu_usertype[] = 2;					
				
				}

				$array_menu_page[] = "index2.php?page=project_checklist_edit&amp;proj_id=$proj_id&amp;group_id=$group_id";
				$array_menu_text[] = "Edit Group";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 2;		
				
				$array_menu_page[] = "pdf_project_checklist_stages.php?proj_id=" . $proj_id;
				$array_menu_text[] = "Stages";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "pdf_project_checklist.php?proj_id=" . $proj_id;
				$array_menu_text[] = "Checklist";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 2;
				
	} elseif ( $page == "phonemessage_view") {
		
				if (intval($_GET[user_id]) > 0) { $user_id = intval($_GET[user_id]); } else { $user_id = intval($_COOKIE[user]); }
		
				$array_menu_page[] = "index2.php?page=phonemessage_view&amp;status=outstanding&amp;user_id=$user_id";
				$array_menu_text[] = "Outstanding Messages";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;	

				$array_menu_page[] = "index2.php?page=phonemessage_view&amp;status=all&amp;user_id=$user_id";
				$array_menu_text[] = "All Messages";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=phonemessage_view&amp;status=user&amp;user_id=$user_id";
				$array_menu_text[] = "Messages for Others";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;	
				
	
		
	} elseif ( $page == "project_drawings" && intval($proj_id) > 0) {
		
				if (intval($_GET[proj_id]) > 0) { $proj_id = intval($_GET[proj_id]); }

				$array_menu_page[] = "index2.php?page=drawings_list&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Drawing List";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=drawings_edit&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Add New Drawing";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=drawings_issue&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "Issue Drawings";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
	
				$array_menu_page[] = "index2.php?page=drawings_issues&amp;proj_id=" . $proj_id;
				$array_menu_text[] = "List Drawing Issues";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
		
	} elseif ( $page == "contacts_admin"  && $level == 1) {
	
				$array_menu_page[] = "index2.php?page=contacts_view";
				$array_menu_text[] = "All Contacts";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;	
				
				$array_menu_page[] = "index2.php?page=contacts_edit&amp;status=add";
				$array_menu_text[] = "Add Contact";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;	

				$array_menu_page[] = "index2.php?page=contacts_company_edit&amp;status=add";
				$array_menu_text[] = "Add Company";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=contacts_add_sector";
				$array_menu_text[] = "Add Sector";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=contacts_discipline_list";
				$array_menu_text[] = "List Disciplines";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
	
				$array_menu_page[] = "index2.php?page=contacts_company_merge";
				$array_menu_text[] = "Merge Companies";
				$array_menu_image[] = "button_settings.png";
				$array_menu_usertype[] = 4;
				
				$array_menu_page[] = "index2.php?page=contacts_compare_emails";
				$array_menu_text[] = "Compare Emails &amp; Company Names";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
	} elseif ( $page == "company_admin") {
		
				if (intval($_GET[company_id]) > 0) { $company_id = intval($_GET[company_id]);
	
					$array_menu_page[] = "index2.php?page=contacts_company_edit&amp;company_id=" . $company_id . "&amp;status=edit";
					$array_menu_text[] = "Edit Company";
					$array_menu_image[] = "button_edit.png";
					$array_menu_usertype[] = 2;
				
				}

	} elseif ( $page == "contacts_admin" OR $page == "contacts_view_detailed"  && $level == 2) {
		
				if (intval($contact_id) > 0) { $contact_id = intval(contact_id); } elseif (intval($_POST[contact_id]) > 0) { $contact_id = intval($_POST[contact_id]); } elseif (intval($_GET[contact_id]) > 0) { $contact_id = intval($_GET[contact_id]); }
				
					if (intval($contact_id) > 0) {
		
						$array_menu_page[] = "index2.php?page=contacts_edit&amp;contact_id=" . $contact_id."&amp;status=edit";
						$array_menu_text[] = "Edit Contact";
						$array_menu_image[] = "button_edit.png";
						$array_menu_usertype[] = 1;
						
						$array_menu_page[] = "index2.php?page=project_blog_edit&amp;status=add&amp;contact_id=" . $contact_id;
						$array_menu_text[] = "Add Journal Entry";
						$array_menu_image[] = "button_new.png";
						$array_menu_usertype[] = 1;
						
						$array_menu_page[] = "vcard.php?contact_id=" . $contact_id;
						$array_menu_text[] = "VCard File";
						$array_menu_image[] = "button_list.png";
						$array_menu_usertype[] = 1;
						
						//if ($module_onepage == 1) {
							$array_menu_page[] = ContactOnePage($contact_id);
							$array_menu_text[] = "Add to OnepageCRM";
							$array_menu_image[] = "button_new.png";
							$array_menu_usertype[] = 1;
						//}
					
					}
				
	} elseif ( $page == "timesheet_admin") {
		
			if (intval($_GET['user_id']) > 0) { $user_id = intval($_GET['user_id']); } else { $user_id = intval($_COOKIE['user']); }
		
				$array_menu_page[] = "index2.php?page=timesheet&user_view=$user_id";
				$array_menu_text[] = "Timesheets Home";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;
				
				$array_menu_page[] = "index2.php?page=timesheet_analysis";
				$array_menu_text[] = "Timesheet Analysis";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				
				$array_menu_page[] = "index2.php?page=timesheet_settings";
				$array_menu_text[] = "Timesheet Settings";
				$array_menu_image[] = "button_settings.png";
				$array_menu_usertype[] = 4;
				
	} elseif ( $page == "timesheet_settings") {
		
				$array_menu_page[] = "index2.php?page=timesheet_rates_hourly";
				$array_menu_text[] = "Hourly Rates";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				
				$array_menu_page[] = "index2.php?page=timesheet_rates_overhead";
				$array_menu_text[] = "Overhead Rates";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
				
				$array_menu_page[] = "index2.php?page=timesheet_rates_project";
				$array_menu_text[] = "Project Rates";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 4;
		
	} elseif ( $page == "invoice_admin") {
		
				$array_menu_page[] = "index2.php?page=timesheet_invoice";
				$array_menu_text[] = "Invoices Home";
				$array_menu_image[] = "button_home.png";
				$array_menu_usertype[] = 3;
			
				$array_menu_page[] = "index2.php?page=timesheet_invoice_edit";
				$array_menu_text[] = "Add Invoice";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_view_outstanding&amp;status=paid";
				$array_menu_text[] = "Paid Invoices";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_view_outstanding";
				$array_menu_text[] = "Oustanding Invoices";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_view_outstanding&amp;status=current";
				$array_menu_text[] = "Current Invoices";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_view_outstanding&amp;status=future";
				$array_menu_text[] = "Future Invoices";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=timesheet_invoice_view_month";
				$array_menu_text[] = "Invoices by Month";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;
				
	} elseif ( $page == "project_ambition") {
				
				$array_menu_page[] = "index2.php?page=project_ambition_schedule&amp;type=" . $_GET[type];
				$array_menu_text[] = "List Active Projects";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype = 2;
				
				$array_menu_page[] = "index2.php?page=project_ambition_schedule&amp;filter=all&amp;type=" . $_GET[type];
				$array_menu_text[] = "List All Projects";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 2;			
			
	} elseif ( $page == "media") {
				
				$array_menu_page[] = "index2.php?page=media";
				$array_menu_text[] = "Media Library";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=media&action=upload";
				$array_menu_text[] = "Upload Media";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;			
		
	} elseif ( $page == "user_admin" && $level == 1) {
				
				$array_menu_page[] = "index2.php?page=user_edit&amp;user_add=true";
				$array_menu_text[] = "Add User";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=user_list";
				$array_menu_text[] = "List Users";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 3;		
				
				$array_menu_page[] = "index2.php?page=phonemessage_edit&amp;status=new&amp;user_id=" . intval($_GET[user_id]);
				$array_menu_text[] = "Add Phone Message";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;		
		
	} elseif ( $page == "user_admin" && $level == 2) {
				
				$array_menu_page[] = "index2.php?page=user_edit&status=edit&amp;user_id=" . intval($_GET[user_id]);
				$array_menu_text[] = "Edit User";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 3;
				
				$array_menu_page[] = "index2.php?page=user_change_password&amp;user_id=" . intval($_GET[user_id]);
				$array_menu_text[] = "Change Password";
				$array_menu_image[] = "button_edit.png";
				if (intval($_COOKIE[user]) == intval($_GET[user_id])) { $array_menu_usertype[] = 1; } else { $array_menu_usertype[] = 4; }
		
	} elseif ( $page == "manual_page" && $level == 1) {
				
				$array_menu_page[] = "index2.php?page=manual_page";
				$array_menu_text[] = "Contents";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=manual_page&amp;action=add";
				$array_menu_text[] = "Add Page";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 2;
				
	} elseif ( $page == "manual_page" && $level == 2 && intval($_GET[manual_id]) > 0) {
				
				$array_menu_page[] = "index2.php?page=manual_page&amp;action=edit&amp;manual_id=" . intval($_GET[manual_id]) ;
				$array_menu_text[] = "Edit";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 2;
				
	} elseif ( $page == "blog_view" && $level == 2 && intval($_GET[blog_id]) > 0) {
				
				$array_menu_page[] = "index2.php?page=project_blog_list&amp;proj_id=" . intval($_GET[proj_id]) ;
				$array_menu_text[] = "List All";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_blog_edit&amp;status=add&amp;proj_id=" . intval($_GET[proj_id]) ;
				$array_menu_text[] = "Add Journal Entry";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_blog_edit&amp;status=edit&amp;proj_id=" . intval($proj_id) . "&amp;blog_id=" . intval($_GET[blog_id]);
				$array_menu_text[] = "Edit Journal Entry";
				$array_menu_image[] = "button_edit.png";
				$array_menu_usertype[] = 2;
				
	} elseif ( $page == "blog_list" && intval($proj_id) > 0) {
		
				$array_menu_page[] = "index2.php?page=project_blog_list&amp;proj_id=" . intval($_GET[proj_id]) ;
				$array_menu_text[] = "List All";
				$array_menu_image[] = "button_list.png";
				$array_menu_usertype[] = 1;
				
				$array_menu_page[] = "index2.php?page=project_blog_edit&amp;status=add&amp;proj_id=" . intval($_GET[proj_id]) ;
				$array_menu_text[] = "Add Journal Entry";
				$array_menu_image[] = "button_new.png";
				$array_menu_usertype[] = 1;		
		
		
	} elseif ( $page == "fee_stage_list") {
		
		
				$array_menu_page[] = "pdf_jobbook.php" ;
				$array_menu_text[] = "Printable Version";
				$array_menu_image[] = "button_pdf.png";
				$array_menu_usertype[] = 1;
		
		
	}
	
	DisplayMenu($level,$array_menu_page,$array_menu_text,$array_menu_image,$array_menu_usertype,$array_project_access);

}

function DisplayMenu($level,$array_menu_page,$array_menu_text,$array_menu_image,$array_menu_usertype,$array_project_access) {
	
	global $user_usertype_current;
	
	$proj_leader = GetProjectLead($_GET['proj_id']);

				$current_url =  htmlspecialchars ( substr($_SERVER['REQUEST_URI'],1) );
				
				$count = 0;
				
				if ($level == 1) { $level_style = "menu_bar"; $tab_style = "menu_tab"; } else { $level_style = "submenu_bar"; $tab_style = "submenu_bar"; }
				
				if (count($array_menu_page) > 0) {
					
				
						echo "<div class=\"" . $level_style . "\" id=\"" . $array_menu_page[$count] . "\">";

						foreach ($array_menu_page AS $menu_link) {
							
									if (($user_usertype_current >= $array_menu_usertype[$count]) OR ($array_project_access[$count] == 1 && $proj_leader == intval($_COOKIE['user']))) {
										
										//echo "<p>" . $array_project_access[$count] . "|" . $proj_leader . "|" . $_COOKIE['user'] . "</p>";
							
											if ($current_url != $array_menu_page[$count]) {
												 
												echo "<a href=\"" . $array_menu_page[$count] . "\" class=\"" . $tab_style . "\">";
												if ($array_menu_image[$count]) { echo "<img src=\"images/" . $array_menu_image[$count] . "\" />&nbsp;"; }
												echo $array_menu_text[$count];
												echo "</a>";
												
											} else {
												
												echo "<a href=\"" . $array_menu_page[$count] . "\" class=\"" . $tab_style . "\" style=\"background-color: white;\">";
												if ($array_menu_image[$count]) { echo "<img src=\"images/" . $array_menu_image[$count] . "\" />&nbsp;"; }
												echo $array_menu_text[$count];
												echo "</a>";
												
											}
											
									}
						 
								$count++;
						 
						}

						echo "</div>";
				
				}

}

function GetProjectLead($proj_id) {
	
	global $conn;
	
	$sql = "SELECT proj_rep_black from intranet_projects WHERE proj_id = " . intval($proj_id) . " LIMIT 1";
	$result = mysql_query($sql, $conn) or die(mysql_error());
		if (mysql_num_rows($result) > 0) {
			$array = mysql_fetch_array($result);
			return $array['proj_rep_black'];
		}
	
}
