<?php

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
class fa_sales_types extends table_interface
{
	//fa_sales_types
	/*
| id            | int(11)     | NO   | PRI | NULL    | auto_increment |
| sales_type    | char(50)    | NO   |     | 0       |                |
| tax_included  | int(1)      | NO   |     | 0       |                |
| factor        | double      | NO   |     | 1       |                |
| inactive      | tinyint(1)  | NO   |     | 0       |                |
	*/
	protected $id;	
	protected $sales_type;
	protected $tax_included;
	protected $factor;
	protected $inactive;
	var $errors = array();
	var $warnings = array();

	//function __construct( /*$prefs_db*/ )
	function __construct( $caller = null )
	{
		//parent::__construct( $prefs_db );
		parent::__construct( $caller );
		$descl = 'varchar(' . DESCRIPTION_LENGTH . ')';
		$this->table_details['tablename'] = TB_PREF . 'sales_types';
		$this->tablename = $this->table_details['tablename'];
		$this->fields_array[] = array('name' => 'id', 'label' => 'Sales Type', 'type' => 'int(11)', 'null' => 'NOT NULL',  'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'sales_type', 'label' => 'Sales Type', 'type' => 'char(50)', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array('name' => 'tax_included', 'label' => 'Tax Included', 'type' => 'int(1)', 'null' => 'NOT NULL',  'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'factor', 'label' => 'Factor', 'type' => 'double', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '1' );
		$this->fields_array[] = array( 'name' => 'inactive', 'label' => 'Inactive', 'type' => 'bool', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		
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
	function getById()
	{
		return $this->getByPrimaryKey();
	}
	/****************************/
	/**************************//**
	 *
	 * @internal factor, sales_type, tax_included, inactive
	 * ****************************/
	function add_item()
	{
		$this->clear_sql_vars();
		$this->insert_array = array( );
		//$this->where_array = array();
		//$this->where_array['id'] =  $this->id;
		$this->insert_array['factor'] =  $this->factor;
		$this->insert_array['sales_type'] = $this->sales_type;
		$this->insert_array['tax_included'] = $this->tax_included;
		$this->insert_array['inactive'] = $this->inactive;
		$this->buildInsertQuery();
		$this->query( "Sales Type could not be insertd", "insert" );
	}
	/**************************//**
	 *
	 * @internal sales_type, tax_included, inactive
	 * ****************************/
	function update_item_by_id()
	{
		if( ! isset( $this->id ) )
			throw new Exception( "ID isn't set so can't update", KSF_FIELD_NOT_SET );
		$this->clear_sql_vars();
		$this->update_array = array( );
		$this->where_array = array();
		$this->where_array['id'] =  $this->id;
		//$this->update_array['factor'] =  $this->factor;
		$this->update_array['sales_type'] = $this->sales_type;
		$this->update_array['tax_included'] = $this->tax_included;
		$this->update_array['inactive'] = $this->inactive;
		$this->buildUpdateQuery();
		$res = $this->query( "Sales Type could not be updated", "update" );
		return $this->db_fetch( $res );
	}
	/**************************//**
	 *
	 *
	 * ****************************/
	function delete_item()
	{
		if( ! isset( $this->id ) )
			throw new Exception( "ID isn't set so can't delete", KSF_FIELD_NOT_SET );
		$this->clear_sql_vars();
		$this->select_array = array( '*' );
		$this->where_array = array();
		$this->where_array['id'] =  $this->id;
		//$this->where_array['factor'] =  $this->factor;
		//$this->where_array['sales_type'] = $this->sales_type;
		//$this->where_array['tax_included'] = $this->tax_included;
		//$this->where_array['inactive'] = $this->inactive;
		$this->buildDeleteQuery();
		$res = $this->query( "Sales Type could not be deleted", "delete" );
		return $this->db_fetch( $res );
	}
	/**************************//**
	 *
	 * @internal id
	 * ****************************/
	function get_sales_types()
	{
		if( ! isset( $this->id ) )
			throw new Exception( "ID isn't set so can't select", KSF_FIELD_NOT_SET );
		$this->clear_sql_vars();
		//$this->from_array = array( TB_PREF . 'stock_master' );
		$this->select_array = array( '*' );
		$this->where_array = array();
		$this->where_array['id'] =  $this->id;
		//$this->where_array['factor'] =  $this->factor;
		//$this->where_array['sales_type'] = $this->sales_type;
		//$this->where_array['tax_included'] = $this->tax_included;
		//$this->where_array['inactive'] = $this->inactive;
		$this->buildSelectQuery();
		$res = $this->query( "Sales Type could not be retrieved", "select" );
		return $this->db_fetch( $res );
	}
	/**//****************************************************************
	* Get SQL query to get sales_type from a string
	*
	* @since 20241027
	*
	* @param string sales_type
	* @return string SQL query
	********************************************************************/
	function get_sales_type_from_name_SQL( $sales_type )
	{
		$sql = "SELECT id from " . TB_PREF . "sales_types where sales_type in ( '" . $sales_type . "' )";
		return $sql;
	}
	/**//****************************************************************
	* Get sales_type from a string
	*
	* @since 20241027
	*
	* @param string sales_type
	* @return int sales_type
	********************************************************************/
	function get_sales_type_from_name( $sales_type )
	{
		$sql = $this->get_sales_type_from_name_SQL( $sales_type );
		$res = db_query( $sql, "Couldn't select!" );
		$row = db_fetch( $res );
		return $row['id'];
		//return $this->db_fetch( $res );
	}


}


/******************TESTING****************************/
/*
$test = new fa_sales_types();
$test->unit_test = true;
try {
	$test->add_inactive_multi_stock_master( 3, 10, 'test', 'CAD' );
} catch (Exception $e )
{
	var_dump( $this->sql );
	$e->getMsg();
}
try {
$test->update_inactive_match( 3, 11, 'test', 'CAD' );
} catch (Exception $e )
{
	var_dump( $this->sql );
	$e->getMsg();
}
try {
$test->update_inactive( 3, 12, 'test', 'CAD' );
} catch (Exception $e )
{
	var_dump( $this->sql );
	$e->getMsg();
}
 */
/*
	$bank->set( 'bank_account', '1060' );	//!< bank account number in sales_types
	$bank->insert();	//how do we determine success?
	$bank->select();	//assuming insert set id;
	$bank->set( 'inactive', true );
	$bank->update();
	$bank->select();	//assuming insert set id;
 */

/******************TESTING****************************/

?>
