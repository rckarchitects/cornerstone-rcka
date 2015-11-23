-- 
-- Table structure for table `contacts_brochurelist`
-- 

CREATE TABLE `contacts_brochurelist` (
  `brochure_id` int(12) NOT NULL auto_increment,
  `brochure_name` text ,
  `brochure_date` int(12) default NULL,
  `brochure_desc` text ,
  PRIMARY KEY  (`brochure_id`)
) AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `contacts_colorlist`
-- 

CREATE TABLE `contacts_colorlist` (
  `color_id` int(8) NOT NULL auto_increment,
  `color_relation` int(8) default NULL,
  `color_background` varchar(6) default NULL,
  `color_text` varchar(6) default NULL,
  `color_description` varchar(150) default NULL,
  PRIMARY KEY  (`color_id`)
) AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `contacts_companylist`
-- 

CREATE TABLE `contacts_companylist` (
  `company_id` int(12) NOT NULL auto_increment,
  `company_name` varchar(100)  default NULL,
  `company_address` text ,
  `company_city` varchar(50)  default NULL,
  `company_county` varchar(50)  default NULL,
  `company_postcode` varchar(12)  default NULL,
  `company_web` varchar(50)  default NULL,
  `company_phone` varchar(50)  default NULL,
  `company_fax` varchar(50)  default NULL,
  `company_country` int(8) default NULL,
  PRIMARY KEY  (`company_id`)
) AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `contacts_contactlist`
-- 

CREATE TABLE `contacts_contactlist` (
  `contact_id` int(12) NOT NULL auto_increment,
  `contact_prefix` varchar(12) default NULL,
  `contact_namefirst` varchar(50) default NULL,
  `contact_namesecond` varchar(50) default NULL,
  `contact_title` int(50) default NULL,
  `contact_company` int(8) default NULL,
  `contact_telephone` varchar(50) default NULL,
  `contact_fax` varchar(50) default NULL,
  `contact_mobile` varchar(50) default NULL,
  `contact_email` varchar(100) default NULL,
  `contact_sector` varchar(8) default NULL,
  `contact_reference` text,
  `contact_department` varchar(100) default NULL,
  `contact_added` int(12) default NULL,
  `contact_relation` int(8) default NULL,
  `contact_discipline` int(8) default NULL,
  `contact_include` tinyint(1) default NULL,
  `contact_address` text,
  `contact_city` varchar(45) default NULL,
  `contact_county` varchar(45) default NULL,
  `contact_postcode` varchar(45) default NULL,
  `contact_country` int(4) default NULL,
  `contact_added_by` int(8) default NULL,
  PRIMARY KEY  (`contact_id`)
) AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `contacts_disciplinelist`
-- 

CREATE TABLE `contacts_disciplinelist` (
  `discipline_id` int(11) NOT NULL auto_increment,
  `discipline_name` varchar(75) default NULL,
  `discipline_ref` varchar(12) default NULL,
  PRIMARY KEY  (`discipline_id`)
) AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `contacts_issuelist`
-- 

CREATE TABLE `contacts_issuelist` (
  `issue_id` int(12) NOT NULL auto_increment,
  `issue_contact` int(12) default NULL,
  `issue_brochure` int(8) default NULL,
  `issue_date` int(18) default NULL,
  `issue_response` text,
  PRIMARY KEY  (`issue_id`)
) AUTO_INCREMENT=1468 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `contacts_labellist`
-- 

CREATE TABLE `contacts_labellist` (
  `label_id` int(12) NOT NULL auto_increment,
  `label_title` varchar(100) default NULL,
  `label_desc` text,
  `label_a` float default NULL,
  `label_b` float default NULL,
  `label_c` float default NULL,
  `label_d` float default NULL,
  `label_e` float default NULL,
  `label_f` float default NULL,
  `label_paper` varchar(12) default NULL,
  `label_g` float default NULL,
  `label_h` float default NULL,
  `label_topindent` float default NULL,
  `label_sideindent` float default NULL,
  `label_font` float default NULL,
  `label_spacing` float default NULL,
  PRIMARY KEY  (`label_id`)
) AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `contacts_prefixlist`
-- 

CREATE TABLE `contacts_prefixlist` (
  `prefix_id` int(12) NOT NULL auto_increment,
  `prefix_name` varchar(20) default NULL,
  PRIMARY KEY  (`prefix_id`)
) AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `contacts_relationlist`
-- 

CREATE TABLE `contacts_relationlist` (
  `relation_id` int(8) NOT NULL auto_increment,
  `relation_name` varchar(50) default NULL,
  `relation_color` varchar(6) default NULL,
  `relation_text` varchar(6) default NULL,
  `relation_mobile` int(1) default '1',
  PRIMARY KEY  (`relation_id`)
) AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `contacts_sectorlist`
-- 

CREATE TABLE `contacts_sectorlist` (
  `sector_id` int(8) NOT NULL auto_increment,
  `sector_name` varchar(50) default NULL,
  PRIMARY KEY  (`sector_id`)
) AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `contacts_titlelist`
-- 

CREATE TABLE `contacts_titlelist` (
  `title_id` int(12) NOT NULL auto_increment,
  `title_name` varchar(50) default NULL,
  PRIMARY KEY  (`title_id`)
) AUTO_INCREMENT=168 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cpd`
-- 

CREATE TABLE `cpd` (
  `cpd_id` int(10) NOT NULL auto_increment,
  `cpd_date` int(10) default NULL,
  `cpd_desc` text,
  `cpd_time` int(12) default NULL,
  `cpd_type` int(1) default NULL,
  `cpd_value` int(1) default NULL,
  `cpd_user` int(4) default NULL,
  PRIMARY KEY  (`cpd_id`)
) AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `events`
-- 

CREATE TABLE `events` (
  `event_id` int(10) NOT NULL auto_increment,
  `event_type` varchar(20) default NULL,
  `event_time` int(20) default NULL,
  `event_description` text,
  `event_location` varchar(100) default NULL,
  `event_title` varchar(100) default NULL,
  `event_author` int(8) default NULL,
  PRIMARY KEY  (`event_id`)
) AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `hyperlinks`
-- 

CREATE TABLE `hyperlinks` (
  `popularity` int(11) default '0',
  `link_title` varchar(100) default '',
  `cat_1` varchar(100) default '',
  `cat_2` varchar(100) default '',
  `link_desc` varchar(100) default '',
  `link_url` varchar(100) default '',
  `link_author` varchar(100) default '',
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=121 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `hyperlinks_cat`
-- 

CREATE TABLE `hyperlinks_cat` (
  `id` int(11) NOT NULL auto_increment,
  `cat_1` varchar(100) default '',
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_admin`
-- 

CREATE TABLE `cstone_admin` (
  `admin_title` varchar(45) default NULL,
  `admin_message_login` tinyint(1) default NULL,
  `admin_popup_login` tinyint(1) default NULL,
  `admin_style` int(2) default NULL
) ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_contacts_countrylist`
-- 

CREATE TABLE `cstone_contacts_countrylist` (
  `country_id` smallint(6) NOT NULL auto_increment,
  `country_iso` char(2) default NULL,
  `country_name` varchar(80) NOT NULL default '',
  `country_printable_name` varchar(80) NOT NULL default '',
  `country_iso3` char(3) default NULL,
  PRIMARY KEY  (`country_id`)
) AUTO_INCREMENT=240 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_journal`
-- 

CREATE TABLE `cstone_journal` (
  `journal_id` int(12) NOT NULL auto_increment,
  `journal_type` int(12) default NULL,
  `journal_date` int(20) default NULL,
  `journal_contact` int(12) default NULL,
  `journal_text` text,
  `journal_project` int(12) default NULL,
  PRIMARY KEY  (`journal_id`)
) AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_phonemessage`
-- 

CREATE TABLE `cstone_phonemessage` (
  `message_action` varchar(100) default '',
  `message_priority` varchar(100) default '',
  `message_id` int(11) NOT NULL auto_increment,
  `message_name_from` varchar(100) default '',
  `message_name_for` varchar(100) default '',
  `message_name_taken` varchar(100) default '',
  `message_date_taken` varchar(100) default '',
  `message_time_taken` varchar(100) default '',
  `message_project_reference` varchar(100) default '',
  `message_viewed` varchar(100) default '',
  `message_text` text,
  `message_status` varchar(100) default '',
  `message_importance` varchar(100) default '',
  PRIMARY KEY  (`message_id`)
) AUTO_INCREMENT=691 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_procure`
-- 

CREATE TABLE `cstone_procure` (
  `procure_id` tinyint(2) NOT NULL auto_increment,
  `procure_title` varchar(32) default NULL,
  `procure_desc` text,
  PRIMARY KEY  (`procure_id`)
) AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_projects`
-- 

CREATE TABLE `cstone_projects` (
  `proj_rep_id` varchar(100) default '',
  `proj_id` int(11) NOT NULL auto_increment,
  `proj_num` varchar(100) default '',
  `proj_name` varchar(100) default '',
  `proj_address_1` varchar(100) default '',
  `proj_address_2` varchar(100) default '',
  `proj_address_3` varchar(100) default '',
  `proj_address_town` varchar(100) default '',
  `proj_address_county` varchar(100) default '',
  `proj_address_country` varchar(100) default '',
  `proj_address_postcode` varchar(100) default '',
  `proj_client_contact_id` int(10) unsigned default NULL,
  `proj_client_accounts_name` varchar(100) default '',
  `proj_client_accounts_phone` varchar(100) default '',
  `proj_client_accounts_fax` varchar(100) default '',
  `proj_client_accounts_email` varchar(100) default '',
  `proj_date_start` varchar(100) default '',
  `proj_date_complete` varchar(100) default '',
  `proj_active` int(1) default NULL,
  `proj_desc` text,
  `proj_riba` tinyint(2) default NULL,
  `proj_riba_begin` tinyint(2) default NULL,
  `proj_riba_conclude` tinyint(2) default NULL,
  `proj_procure` varchar(48) default NULL,
  `proj_conc` varchar(48) default NULL,
  `proj_value` int(12) default NULL,
  `proj_value_type` varchar(20) default NULL,
  `proj_consult_41` int(8) default NULL,
  `proj_consult_42` int(8) default NULL,
  `proj_consult_43` int(8) default NULL,
  `proj_consult_6` int(8) default NULL,
  `proj_consult_7` int(8) default NULL,
  `proj_consult_8` int(8) default NULL,
  `proj_consult_9` int(8) default NULL,
  `proj_consult_10` int(8) default NULL,
  `proj_consult_11` int(8) default NULL,
  `proj_consult_12` int(8) default NULL,
  `proj_consult_13` int(8) default NULL,
  `proj_consult_14` int(8) default NULL,
  `proj_consult_15` int(8) default NULL,
  `proj_consult_16` int(8) default NULL,
  `proj_consult_17` int(8) default NULL,
  `proj_consult_18` int(8) default NULL,
  `proj_consult_19` int(8) default NULL,
  `proj_tenant_1` int(8) default NULL,
  `proj_account_track` tinyint(1) default NULL,
  `proj_fee_track` tinyint(1) default NULL,
  `proj_country` int(8) default NULL,
  `proj_fee_type` int(4) default NULL,
  PRIMARY KEY  (`proj_id`)
) AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_tasklist`
-- 

CREATE TABLE `cstone_tasklist` (
  `tasklist_id` int(8) NOT NULL auto_increment,
  `tasklist_project` int(8) default NULL,
  `tasklist_contact` int(8) default NULL,
  `tasklist_fee` int(2) default NULL,
  `tasklist_notes` text,
  `tasklist_updated` varchar(12) default NULL,
  `tasklist_added` varchar(12) default NULL,
  `tasklist_completed` int(3) default NULL,
  `tasklist_person` int(2) default NULL,
  `tasklist_due` int(20) default NULL,
  `tasklist_comment` text,
  `tasklist_percentage` int(3) default NULL,
  PRIMARY KEY  (`tasklist_id`)
) AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_timesheet`
-- 

CREATE TABLE `cstone_timesheet` (
  `ts_id` int(11) NOT NULL auto_increment,
  `ts_user` varchar(8) default NULL,
  `ts_project` varchar(8) default NULL,
  `ts_hours` float default NULL,
  `ts_desc` text,
  `ts_day` varchar(12) default NULL,
  `ts_month` varchar(12) default NULL,
  `ts_year` varchar(12) default NULL,
  `ts_entry` int(20) default NULL,
  `ts_datestamp` varchar(12) default NULL,
  `ts_rate` float default NULL,
  `ts_overhead` float default NULL,
  PRIMARY KEY  (`ts_id`)
) AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_timesheet_datum`
-- 

CREATE TABLE `cstone_timesheet_datum` (
  `ts_datum_id` int(12) NOT NULL auto_increment,
  `ts_datum_date` int(12) default NULL,
  `ts_datum_user` int(4) default NULL,
  `ts_datum_project` int(6) default NULL,
  `ts_datum_hours` float default NULL,
  `ts_datum_rate` float default NULL,
  `ts_datum_overhead` float default NULL,
  PRIMARY KEY  (`ts_datum_id`)
) AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_timesheet_expense`
-- 

CREATE TABLE `cstone_timesheet_expense` (
  `ts_expense_id` int(12) NOT NULL auto_increment,
  `ts_expense_project` int(8) default NULL,
  `ts_expense_value` float default NULL,
  `ts_expense_date` int(12) default NULL,
  `ts_expense_desc` text,
  `ts_expense_user` int(8) default NULL,
  `ts_expense_verified` int(1) default NULL,
  PRIMARY KEY  (`ts_expense_id`)
) AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_timesheet_invoice`
-- 

CREATE TABLE `cstone_timesheet_invoice` (
  `invoice_id` int(12) NOT NULL auto_increment,
  `invoice_date` int(20) default NULL,
  `invoice_due` int(20) default NULL,
  `invoice_value_novat` float default NULL,
  `invoice_value_vat` float default NULL,
  `invoice_project` int(6) default NULL,
  `invoice_ref` varchar(100) default NULL,
  `invoice_text` text,
  PRIMARY KEY  (`invoice_id`)
) AUTO_INCREMENT=182 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_timesheet_overhead`
-- 

CREATE TABLE `cstone_timesheet_overhead` (
  `overhead_id` int(4) NOT NULL auto_increment,
  `overhead_rate` int(4) default NULL,
  `overhead_date` int(20) default NULL,
  PRIMARY KEY  (`overhead_id`)
) AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_timesheet_payment`
-- 

CREATE TABLE `cstone_timesheet_payment` (
  `payment_id` int(12) NOT NULL auto_increment,
  `payment_date` mediumint(20) default NULL,
  `payment_value` float default NULL,
  `payment_invoice` int(12) default NULL,
  PRIMARY KEY  (`payment_id`)
) AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_timesheet_rate_type`
-- 

CREATE TABLE `cstone_timesheet_rate_type` (
  `rate_id` int(10) unsigned NOT NULL auto_increment,
  `rate_value` int(10) unsigned default NULL,
  `rate_name` varchar(45) default NULL,
  PRIMARY KEY  (`rate_id`)
) AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_timesheet_rate_user`
-- 

CREATE TABLE `cstone_timesheet_rate_user` (
  `rate_id` int(8) NOT NULL auto_increment,
  `rate_user` int(4) default NULL,
  `rate_value` float default NULL,
  PRIMARY KEY  (`rate_id`)
) AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `cstone_user_details`
-- 

CREATE TABLE `cstone_user_details` (
  `user_password` varchar(100) default NULL,
  `user_address_county` varchar(100) default NULL,
  `user_address_postcode` varchar(100) default NULL,
  `user_address_town` varchar(100) default NULL,
  `user_address_3` varchar(100) default NULL,
  `user_address_2` varchar(100) default NULL,
  `user_address_1` varchar(100) default NULL,
  `user_id` int(11) NOT NULL auto_increment,
  `user_name_first` varchar(100) default NULL,
  `user_name_second` varchar(100) default NULL,
  `user_num_extension` varchar(100) default NULL,
  `user_num_mob` varchar(100) default NULL,
  `user_num_home` varchar(100) default NULL,
  `user_email` varchar(100) default NULL,
  `user_usertype` int(2) default '0',
  `user_active` varchar(4) default 'yes',
  `user_username` varchar(100) default NULL,
  `user_user_rate` float default NULL,
  `user_user_added` int(20) default NULL,
  `user_user_timesheet` varchar(4) default NULL,
  `user_holidays` tinyint(4) default NULL,
  PRIMARY KEY  (`user_id`)
) AUTO_INCREMENT=113 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `journal_articles`
-- 

CREATE TABLE `journal_articles` (
  `journal_id` int(10) NOT NULL auto_increment,
  `journal_date` varchar(20) default NULL,
  `journal_architect` varchar(50) default NULL,
  `journal_buildingname` varchar(50) default NULL,
  `journal_buildinglocation` varchar(20) default NULL,
  `journal_buildingtype` varchar(20) default NULL,
  `journal_feature` text,
  `journal_added` int(20) default NULL,
  `journal_name` varchar(50) default NULL,
  `journal_page` varchar(4) default NULL,
  PRIMARY KEY  (`journal_id`)
) AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `library_books`
-- 

CREATE TABLE `library_books` (
  `book_id` int(8) NOT NULL auto_increment,
  `book_author` varchar(200) default NULL,
  `book_title` varchar(200) default NULL,
  `book_type` varchar(12) default NULL,
  `book_architect` varchar(200) default NULL,
  `book_subject` text,
  PRIMARY KEY  (`book_id`)
) AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `riba_stages`
-- 

CREATE TABLE `riba_stages` (
  `riba_id` tinyint(2) NOT NULL auto_increment,
  `riba_letter` varchar(8) default NULL,
  `riba_desc` varchar(60) default NULL,
  `riba_generic` text,
  PRIMARY KEY  (`riba_id`)
) AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_ceiling_cornice`
-- 

CREATE TABLE `roomdata_ceiling_cornice` (
  `ceiling_cornice_id` int(8) NOT NULL auto_increment,
  `ceiling_cornice_name` varchar(100) default NULL,
  `ceiling_cornice_nbs` varchar(100) default NULL,
  `ceiling_cornice_man` varchar(100) default NULL,
  `ceiling_cornice_product` varchar(100) default NULL,
  `ceiling_cornice_colour` varchar(100) default NULL,
  `ceiling_cornice_link` varchar(100) default NULL,
  `ceiling_cornice_notes` text,
  PRIMARY KEY  (`ceiling_cornice_id`)
) AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_ceiling_finish`
-- 

CREATE TABLE `roomdata_ceiling_finish` (
  `ceiling_finish_id` int(8) NOT NULL auto_increment,
  `ceiling_finish_name` varchar(100) default NULL,
  `ceiling_finish_nbs` varchar(100) default NULL,
  `ceiling_finish_man` varchar(100) default NULL,
  `ceiling_finish_link` varchar(100) default NULL,
  `ceiling_finish_notes` text,
  `ceiling_finish_product` varchar(100) default NULL,
  `ceiling_finish_colour` varchar(100) default NULL,
  PRIMARY KEY  (`ceiling_finish_id`)
) AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_ceiling_shadow`
-- 

CREATE TABLE `roomdata_ceiling_shadow` (
  `ceiling_shadow_id` int(8) NOT NULL auto_increment,
  `ceiling_shadow_name` varchar(100) default NULL,
  `ceiling_shadow_nbs` varchar(100) default NULL,
  `ceiling_shadow_man` varchar(100) default NULL,
  `ceiling_shadow_link` varchar(100) default NULL,
  `ceiling_shadow_notes` text,
  `ceiling_shadow_product` varchar(100) default NULL,
  `ceiling_shadow_colour` varchar(100) default NULL,
  PRIMARY KEY  (`ceiling_shadow_id`)
) AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_ceiling_substrate`
-- 

CREATE TABLE `roomdata_ceiling_substrate` (
  `ceiling_substrate_id` int(8) NOT NULL auto_increment,
  `ceiling_substrate_name` varchar(100) default NULL,
  `ceiling_substrate_nbs` varchar(100) default NULL,
  `ceiling_substrate_man` varchar(100) default NULL,
  `ceiling_substrate_product` varchar(100) default NULL,
  `ceiling_substrate_colour` varchar(100) default NULL,
  `ceiling_substrate_link` varchar(100) default NULL,
  `ceiling_substrate_notes` text,
  PRIMARY KEY  (`ceiling_substrate_id`)
) AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_floor_finish`
-- 

CREATE TABLE `roomdata_floor_finish` (
  `floor_finish_id` int(4) NOT NULL auto_increment,
  `floor_finish_name` varchar(100) default NULL,
  `floor_finish_nbs` varchar(100) default NULL,
  `floor_finish_man` varchar(100) default NULL,
  `floor_finish_product` varchar(100) default NULL,
  `floor_finish_colour` varchar(100) default NULL,
  `floor_finish_link` varchar(100) default NULL,
  `floor_finish_notes` text,
  PRIMARY KEY  (`floor_finish_id`)
) AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_floor_substrate`
-- 

CREATE TABLE `roomdata_floor_substrate` (
  `floor_substrate_id` int(4) NOT NULL auto_increment,
  `floor_substrate_name` varchar(100) default NULL,
  `floor_substrate_nbs` varchar(100) default NULL,
  `floor_substrate_man` varchar(100) default NULL,
  `floor_substrate_product` varchar(100) default NULL,
  `floor_substrate_colour` varchar(100) default NULL,
  `floor_substrate_link` varchar(100) default NULL,
  `floor_substrate_notes` text,
  PRIMARY KEY  (`floor_substrate_id`)
) AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_letters`
-- 

CREATE TABLE `roomdata_letters` (
  `letters_id` int(2) NOT NULL auto_increment,
  `letters_print` char(2) default NULL,
  PRIMARY KEY  (`letters_id`)
) AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_revision`
-- 

CREATE TABLE `roomdata_revision` (
  `revision_id` int(8) NOT NULL auto_increment,
  `revision_date` int(20) default NULL,
  `revision_letter` int(2) default NULL,
  `revision_person` varchar(120) default NULL,
  `revision_project` int(8) default NULL,
  `revision_description` text,
  PRIMARY KEY  (`revision_id`)
) AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_room`
-- 

CREATE TABLE `roomdata_room` (
  `room_id` int(8) NOT NULL auto_increment,
  `room_wall1` int(4) default NULL,
  `room_wall2` int(4) default NULL,
  `room_wall3` int(4) default NULL,
  `room_wall4` int(4) default NULL,
  `room_ceiling` int(4) default NULL,
  `room_floor` int(4) default NULL,
  `room_wall1_colour` varchar(200) default NULL,
  `room_wall2_colour` varchar(200) default NULL,
  `room_wall3_colour` varchar(200) default NULL,
  `room_wall4_colour` varchar(200) default NULL,
  `room_floor_colour` varchar(200) default NULL,
  `room_ceiling_colour` varchar(200) default NULL,
  `room_area` varchar(25) default NULL,
  `room_name` varchar(50) default NULL,
  `room_ceilingheight` varchar(25) default NULL,
  `room_soffitheight` varchar(25) default NULL,
  `room_floorlevel` varchar(25) default NULL,
  `room_reference` varchar(12) default NULL,
  `room_occupant` varchar(100) default NULL,
  `room_project` int(8) default NULL,
  `room_notes` text,
  `room_description` text,
  `room_furniture` text,
  PRIMARY KEY  (`room_id`)
) AUTO_INCREMENT=89 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_type_ceiling`
-- 

CREATE TABLE `roomdata_type_ceiling` (
  `ceilingtype_id` int(4) NOT NULL auto_increment,
  `ceilingtype_name` varchar(100) default NULL,
  `ceilingtype_substrate` int(4) default NULL,
  `ceilingtype_finish1` int(4) default NULL,
  `ceilingtype_finish2` int(4) default NULL,
  `ceilingtype_cornice` int(4) default NULL,
  `ceilingtype_shadow` int(4) default NULL,
  `ceilingtype_features` text,
  `ceilingtype_notes` text,
  `ceilingtype_project` int(4) NOT NULL default '0',
  PRIMARY KEY  (`ceilingtype_id`)
) AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_type_floor`
-- 

CREATE TABLE `roomdata_type_floor` (
  `floortype_id` int(4) NOT NULL auto_increment,
  `floortype_name` varchar(100) default NULL,
  `floortype_finish1` int(4) default NULL,
  `floortype_finish2` int(4) default NULL,
  `floortype_substrate` int(4) default NULL,
  `floortype_features` text,
  `floortype_notes` text,
  `floortype_project` int(4) NOT NULL default '0',
  PRIMARY KEY  (`floortype_id`)
) AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_type_wall`
-- 

CREATE TABLE `roomdata_type_wall` (
  `walltype_id` int(4) NOT NULL auto_increment,
  `walltype_name` varchar(100) default NULL,
  `walltype_substrate` int(4) default NULL,
  `walltype_skirting` int(4) default NULL,
  `walltype_finish1` int(4) default NULL,
  `walltype_dado` int(4) default NULL,
  `walltype_features` text,
  `walltype_finish2` int(4) default NULL,
  `walltype_notes` text,
  `walltype_project` int(4) NOT NULL default '0',
  PRIMARY KEY  (`walltype_id`)
) AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_wall_dado`
-- 

CREATE TABLE `roomdata_wall_dado` (
  `wall_dado_id` int(8) NOT NULL auto_increment,
  `wall_dado_name` varchar(100) default NULL,
  `wall_dado_nbs` varchar(100) default NULL,
  `wall_dado_man` varchar(100) default NULL,
  `wall_dado_link` varchar(100) default NULL,
  `wall_dado_notes` text,
  `wall_dado_product` varchar(100) default NULL,
  `wall_dado_colour` varchar(100) default NULL,
  PRIMARY KEY  (`wall_dado_id`)
) AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_wall_finish`
-- 

CREATE TABLE `roomdata_wall_finish` (
  `wall_finish_id` int(8) NOT NULL auto_increment,
  `wall_finish_name` varchar(100) default NULL,
  `wall_finish_nbs` varchar(100) default NULL,
  `wall_finish_man` varchar(100) default NULL,
  `wall_finish_link` varchar(100) default NULL,
  `wall_finish_notes` text,
  `wall_finish_product` varchar(100) default NULL,
  `wall_finish_colour` varchar(100) default NULL,
  PRIMARY KEY  (`wall_finish_id`)
) AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_wall_skirting`
-- 

CREATE TABLE `roomdata_wall_skirting` (
  `wall_skirting_id` int(8) NOT NULL auto_increment,
  `wall_skirting_name` varchar(100) default NULL,
  `wall_skirting_nbs` varchar(100) default NULL,
  `wall_skirting_man` varchar(100) default NULL,
  `wall_skirting_product` varchar(100) default NULL,
  `wall_skirting_colour` varchar(100) default NULL,
  `wall_skirting_link` varchar(100) default NULL,
  `wall_skirting_notes` text,
  PRIMARY KEY  (`wall_skirting_id`)
) AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `roomdata_wall_substrate`
-- 

CREATE TABLE `roomdata_wall_substrate` (
  `wall_substrate_id` int(8) NOT NULL auto_increment,
  `wall_substrate_name` varchar(100) NOT NULL default '',
  `wall_substrate_nbs` varchar(100) NOT NULL default '',
  `wall_substrate_man` varchar(100) NOT NULL default '',
  `wall_substrate_product` varchar(100) default NULL,
  `wall_substrate_colour` varchar(100) default NULL,
  `wall_substrate_link` varchar(100) NOT NULL default '',
  `wall_substrate_notes` text NOT NULL,
  PRIMARY KEY  (`wall_substrate_id`)
) AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

-- 
-- Insert the admin user details for the default user
-- 

INSERT INTO `cstone_user_details` (
`user_password` ,
`user_address_county` ,
`user_address_postcode` ,
`user_address_town` ,
`user_address_3` ,
`user_address_2` ,
`user_address_1` ,
`user_id` ,
`user_name_first` ,
`user_name_second` ,
`user_num_extension` ,
`user_num_mob` ,
`user_num_home` ,
`user_email` ,
`user_usertype` ,
`user_active` ,
`user_username` ,
`user_user_rate` ,
`user_user_added` ,
`user_user_timesheet` ,
`user_holidays`
) VALUES (
'21232f297a57a5a743894a0e4a801fc3',
NULL ,
NULL ,
NULL ,
NULL ,
NULL ,
NULL ,
NULL ,
NULL ,
NULL ,
NULL ,
NULL ,
NULL ,
NULL ,
'5',
'1',
'admin',
NULL ,
NULL ,
NULL ,
NULL
);
