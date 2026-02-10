<?php

/*****************************************************************************************************************
	I have code for this class spread across ALOT of places

/var/www/html/ags/frontaccounting/gl/manage/bank_accounts.php
/var/www/html/ags/frontaccounting/ksf_modules_common_bak_hosed/class.fa_bank_accounts.php
/var/www/html/ags/frontaccounting.2.4.11/gl/manage/bank_accounts.php
/var/www/html/ags/frontaccounting.2.4.11/ksf_modules_common_bak_hosed/class.fa_bank_accounts.php
/var/www/html/ags/frontaccounting.2.4.11/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/devel/fhs/2/frontaccounting_20200308/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/devel/fhs/2/frontaccounting_20200512_recover/gl/manage/bank_accounts.php
/var/www/html/devel/fhs/2/frontaccounting_20200512_recover/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/devel/fhs/20170818/frontaccounting/gl/manage/bank_accounts.php
/var/www/html/devel/fhs/frontaccounting/gl/manage/bank_accounts.php
/var/www/html/devel/fhs/frontaccounting/ksf_modules_common_bak_hosed/class.fa_bank_accounts.php
/var/www/html/devel/fhs/frontaccounting/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/devel/fhs/frontaccounting/modules/ksf_modules_common_new/class.fa_bank_accounts.php
/var/www/html/devel/fhs/frontaccounting_20200512/gl/manage/bank_accounts.php
/var/www/html/devel/fhs/frontaccounting_20200512/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/devel/fhs/frontaccounting_fhs-laptop1/gl/manage/bank_accounts.php
/var/www/html/devel/fhs/frontaccounting_fhs-laptop1/ksf_modules_common_bak_hosed/class.fa_bank_accounts.php
/var/www/html/devel/fhs/frontaccounting_fhs-laptop1/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/devel/ksf_common/class.fa_bank_accounts.php
/var/www/html/devel/ksf_common/vendor/ksfraser/frontaccounting/class.fa_bank_accounts.php
/var/www/html/fhs/frontaccounting/gl/manage/bank_accounts.php
/var/www/html/fhs/frontaccounting/ksf_modules_common_bak_hosed/class.fa_bank_accounts.php
/var/www/html/fhs/frontaccounting/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/fhs/frontaccounting.20231224_orig/gl/manage/bank_accounts.php
/var/www/html/fhs/frontaccounting.20231224_orig/ksf_modules_common_bak_hosed/class.fa_bank_accounts.php
/var/www/html/fhs/frontaccounting.20231224_orig/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/fhs/frontaccounting3/frontaccounting/gl/manage/bank_accounts.php
/var/www/html/fhs/frontaccounting3/frontaccounting/ksf_modules_common_bak_hosed/class.fa_bank_accounts.php
/var/www/html/fhs/frontaccounting3/frontaccounting/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/fhs/frontaccounting_20230710/gl/manage/bank_accounts.php
/var/www/html/fhs/frontaccounting_20230710/ksf_modules_common_bak_hosed/class.fa_bank_accounts.php
/var/www/html/fhs/frontaccounting_20230710/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/fhs/frontaccounting_20230710/modules/ksf_modules_common/class.fa_bank_accounts.php~
/var/www/html/fhsws001/devel/fhs/20170818/frontaccounting/gl/manage/bank_accounts.php
/var/www/html/fhsws001/devel/fhs/fhs/frontaccounting/gl/manage/bank_accounts.php
/var/www/html/fhsws001/devel/fhs/frontaccounting/gl/manage/bank_accounts.php
/var/www/html/fhsws001/devel/fhs/frontaccounting/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/fhsws001/devel/frontaccounting/gl/manage/bank_accounts.php
/var/www/html/fhsws001/devel/frontaccounting-2.4.1/gl/manage/bank_accounts.php
/var/www/html/fhsws001/devel/frontaccounting-2.4.1/gl/manage/bank_accounts.php.diff
/var/www/html/fhsws001/devel/ksf_front/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/fhsws001/devel/ksf_front/ksf_modules_common/class.fa_bank_accounts.php~
/var/www/html/fhsws001/fhs/frontaccounting/gl/manage/bank_accounts.php
/var/www/html/fhsws001/fhs/frontaccounting/ksf_modules_common_bak_hosed/class.fa_bank_accounts.php
/var/www/html/fhsws001/fhs/frontaccounting/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/fhsws001/fhs/frontaccounting - Copy/gl/manage/bank_accounts.php
/var/www/html/infra/accounting/gl/manage/bank_accounts.php
/var/www/html/infra/accounting/modules/ksf_modules_common/class.fa_bank_accounts.php
/var/www/html/ksf_common/vendor/ksfraser/frontaccounting/src/class.fa_bank_accounts.php
 **************************************************************************************************************************/

require_once( 'class.table_interface.php' );

$path_to_root="../..";

/*
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/inventory/includes/inventory_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_codes_db.inc");
include_once($path_to_root . "/workcenters/includes/workcenters_db.inc");


/********************************************************//**
 * Various modules need to be able to add or get info about workcenters from FA
 *
 *	This class uses FA specific routines (display_notification etc)
 *
 * **********************************************************/
class fa_bank_accounts extends table_interface
{
	//fa_crm_persons
	protected $id;	
	protected $bank_account_name;
	protected $bank_curr_code;
	protected $inactive;
	var $min_cid;
	var $max_cid;
	var $errors = array();
	var $warnings = array();

	//function __construct( /*$prefs_db*/ )
	function __construct( $caller = null )
	{
		//parent::__construct( $prefs_db );
		parent::__construct( $caller );
		$descl = 'varchar(' . DESCRIPTION_LENGTH . ')';
		$this->table_details['tablename'] = TB_PREF . 'bank_accounts';
		$this->fields_array[] = array('name' => 'bank_account_name', 'label' => 'Bank Account Name', 'type' => $descl, 'null' => 'NOT NULL',  'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array('name' => 'id', 'label' => 'Bank Account', 'type' => 'int(11)', 'null' => 'NOT NULL',  'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array('name' => 'bank_curr_code', 'label' => 'Bank Currency Code', 'type' => $descl, 'null' => 'NOT NULL',  'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array('name' => 'inactive', 'label' => 'Record is Inactive', 'type' => 'bool', 'null' => 'NOT NULL',  'readwrite' => 'readwrite', 'default' => '0' );
		$this->table_details['primarykey'] = "id";
	}
	function insert()
	{
		$this->insert_table();
	}
	function update()
	{
		$this->update_table();
	}
	/*@bool@*/function getByName()
	{
		$fields = "*";	//comma separated list
		$where = array('bank_account_name');
		$orderby = array();
		$limit = null;	//int
		return $this->select_table( $fields, $where, $orderby, $limit );
	}
	function getById()
	{
		return $this->getByPrimaryKey();
	}

}


?>
