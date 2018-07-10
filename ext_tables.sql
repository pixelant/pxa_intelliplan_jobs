#
# Table structure for table 'tx_pxaintelliplanjobs_domain_model_job'
#
CREATE TABLE tx_pxaintelliplanjobs_domain_model_job (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	company varchar(55) DEFAULT '' NOT NULL,
	apply_start int(11) DEFAULT '0' NOT NULL,
	description text NOT NULL,
	pub_date int(11) DEFAULT '0' NOT NULL,
	category varchar(55) DEFAULT '' NOT NULL,
	id int(11) DEFAULT '0' NOT NULL,
	number_of_positions_to_fill int(11) DEFAULT '0' NOT NULL,
  type varchar(55) DEFAULT '' NOT NULL,
	job_position_title varchar(55) DEFAULT '' NOT NULL,
	job_position_title_id int(11) DEFAULT '0' NOT NULL,
	job_position_category_id int(11) DEFAULT '0' NOT NULL,
	job_location varchar(55) DEFAULT '' NOT NULL,
	job_location_id int(11) DEFAULT '0' NOT NULL,
	job_occupation varchar(55) DEFAULT '' NOT NULL,
	job_occupation_id int(11) DEFAULT '0' NOT NULL,
	job_category varchar(55) DEFAULT '' NOT NULL,
	job_category_id int(11) DEFAULT '0' NOT NULL,
	service_category varchar(55) DEFAULT '' NOT NULL,
	service varchar(55) DEFAULT '' NOT NULL,
	country varchar(55) DEFAULT '' NOT NULL,
	country_id varchar(55) DEFAULT '' NOT NULL,
	state varchar(55) DEFAULT '' NOT NULL,
	state_id int(11) DEFAULT '0' NOT NULL,
	municipality varchar(55) DEFAULT '' NOT NULL,
	municipality_id int(11) DEFAULT '0' NOT NULL,
	company_logo_url varchar(255) DEFAULT '' NOT NULL,
	employment_extent varchar(55) DEFAULT '' NOT NULL,
	employment_extent_id int(11) DEFAULT '0' NOT NULL,
	employment_type varchar(55) DEFAULT '' NOT NULL,
	employment_type_id int(11) DEFAULT '0' NOT NULL,
	job_level varchar(55) DEFAULT '' NOT NULL,
	job_level_id int(11) DEFAULT '0' NOT NULL,
	contact1name varchar(55) DEFAULT '' NOT NULL,
	contact1email varchar(55) DEFAULT '' NOT NULL,
	pub_date_to int(11) DEFAULT '0' NOT NULL,
	last_updated int(11) DEFAULT '0' NOT NULL,
	content_elements int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY language (l10n_parent,sys_language_uid)

);


#
# Table structure for table 'tx_pxaintelliplanjobs_domain_model_job_content_mm'
#
CREATE TABLE tx_pxaintelliplanjobs_domain_model_job_content_mm (
	uid_local int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'sys_category'
#
CREATE TABLE sys_category (
	tx_pxaintelliplanjobs_import_id int(11) DEFAULT '0' NOT NULL,
);