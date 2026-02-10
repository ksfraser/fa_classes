<?php

//This file isn't called _model because I intend to put the _view class in here too!

require_once( 'class.table_interface.php' );

$path_to_root="../..";

/*
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/inventory/includes/inventory_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_codes_db.inc");
include_once($path_to_root . "/workcenters/includes/workcenters_db.inc");


/********************************************************//**
 * This class is the details for a SALES INVOICE!!
 *
 *	This class was copied and lightly modified from fa_sales_order_details
 *	so the functions below haven't been carefully checked nor tested (yet)
 *
 * @since 20250320
 *
 *	This class uses FA specific routines (display_notification etc)
 *
 * **********************************************************/
class fa_debtor_trans_details_model extends table_interface
{
	//fa_debtor_trans_details
	/*
	*	+---------------+------------+------+-----+---------+-------+
	*	| Field         | Type       | Null | Key | Default | Extra |
	*	+---------------+------------+------+-----+---------+-------+
	*	| id                | int(11)     | NO   | PRI | NULL    | auto_increment |
	*	| debtor_trans_no   | int(11)     | YES  |     | NULL    |                |
	*	| debtor_trans_type | int(11)     | YES  | MUL | NULL    |                |
	*	| stock_id          | varchar(64) | YES  |     | NULL    |                |
	*	| description       | tinytext    | YES  |     | NULL    |                |
	*	| unit_price        | double      | NO   |     | 0       |                |
	*	| unit_tax          | double      | NO   |     | 0       |                |
	*	| quantity          | double      | NO   |     | 0       |                |
	*	| discount_percent  | double      | NO   |     | 0       |                |
	*	| standard_cost     | double      | NO   |     | 0       |                |
	*	| qty_done          | double      | NO   |     | 0       |                |
	*	| src_id            | int(11)     | YES  | MUL | NULL    |                |
	*	+---------------+------------+------+-----+---------+-------+
	*/
	protected $id;	
	protected $debtor_trans_no;
	protected $debtor_trans_type
	protected $stock_id;
	protected $description;
	protected $unit_price;
	protected $unit_tax;
	protected $quantity;
	protected $discount_percent;
	protected $standard_cost;
	protected $qty_done;
	protected $src_id;
	
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
		$stockl = 'varchar(' . DESCRIPTION_LENGTH . ')';
		$refl = 'varchar(' . REFERENCE_LENGTH . ')';
		$loccdl = 'varchar(' . LOC_CODE_LENGTH . ')';
		$this->table_details['tablename'] = TB_PREF . 'debtor_trans_details';

		$this->fields_array[] = array('name' => 'id', 'label' => 'Index', 'type' => 'int(11)', 'null' => 'NOT NULL',  'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'debtor_trans_no', 'label' => 'Order Number', 'type' => 'int(11)', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'debtor_trans_type', 'label' => 'Reorder Level', 'type' => 'int(11)', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'stock_id', 'label' => 'Stock ID', 'type' => $stockl, 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'description', 'label' => 'Description', 'type' => $descl, 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'unit_price', 'label' => 'Unit Price', 'type' => 'double', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'unit_tax', 'label' => 'Unit Tax', 'type' => 'double', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'quantity', 'label' => 'Quantity Sold', 'type' => 'double', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'discount_percent', 'label' => 'Discount Percent', 'type' => 'double', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'standard_cost', 'label' => 'Discount Percent', 'type' => 'double', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'qty_done', 'label' => 'Quantity Ordered', 'type' => 'double', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		$this->fields_array[] = array( 'name' => 'src_id', 'label' => 'Source IDr', 'type' => 'int(11)', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => '0' );
		
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
	/**************************//**
	 *
	 *
	 * ****************************/
	function delete_lineitem()
	{
		$this->clear_sql_vars();
		$this->select_array() = array( '*' );
		$this->where_array = array();
		if( isset( $this->id ) )
			$this->where_array['id'] =  $this->id;
		//$this->where_array['stock_id'] =  $this->stock_id;
		//$this->where_array['quantity'] = $this->quantity;
		//$this->where_array['curr_abrev'] = $this->curr_abrev;
		//$this->where_array['price'] = $this->price;
		$this->buildDeleteQuery();
		$res = $this->query( "Order lineitem could not be deleted", "delete" );
		return $this->db_fetch( $res );
	}
	/**************************//**
	 *
	 *
	 * ****************************/
	function get_stock_id_orders()
	{
		$this->clear_sql_vars();
		$this->select_array() = array( '*' );
		$this->where_array = array();
		$this->where_array['stock_id'] =  $this->stock_id;
		//$this->where_array['quantity'] = $this->quantity;
		if( isset( $this->id ) )
			$this->where_array['id'] = $this->id;
		$this->buildSelectQuery();
		$res = $this->query( "Stock_ID order could not be retrieved", "select" );
		return $this->db_fetch( $res );
	}
	/***************************************************//**
	 * Get the list of items where not everything ordered by customers has been delivered
	 *
	 * Inspired by get_demand_qty in includes/db/manufacturing_db.inc
UNTESTED UNTESTED UNTESTED
	 *
	 * @param INTERNAL stock_id (optional)
	 * @return array
	 * **************************************************** /
	function get_unfilled_items()
	{
		//select * from 1_debtor_trans_details where quantity <> quantity and debtor_trans_type=ST_SALESORDER;
		$this->clear_sql_vars();
		$this->select_array() = array( '*' );
		$this->where_array = array();
		$this->where_array['debtor_trans_type'] =  ST_SALESINVOICE;
		$this->where_array['quantity'] = array( 'ne', 'quantity' );
		if( isset( $this->stock_id ) )
			$this->where_array['stock_id'] = $this->stock_id;
		$this->buildSelectQuery();
		$res = $this->query( "Stock_ID order could not be retrieved", "select" );
		return $this->db_fetch( $res );
	}
UNTESTED UNTESTED UNTESTED
	/**/
}


/******************TESTING****************************/
/*
$test = new fa_debtor_trans_details();
$test->unit_test = true;
try {
	$test->add_price_multi_stock_master( 3, 10, 'test', 'CAD' );
} catch (Exception $e )
{
	var_dump( $this->sql );
	$e->getMsg();
}
try {
$test->update_price_match( 3, 11, 'test', 'CAD' );
} catch (Exception $e )
{
	var_dump( $this->sql );
	$e->getMsg();
}
try {
$test->update_price( 3, 12, 'test', 'CAD' );
} catch (Exception $e )
{
	var_dump( $this->sql );
	$e->getMsg();
}
 */
/*
	$bank->set( 'bank_account', '1060' );	//!< bank account number in debtor_trans_details
	$bank->insert();	//how do we determine success?
	$bank->select();	//assuming insert set id;
	$bank->set( 'inactive', true );
	$bank->update();
	$bank->select();	//assuming insert set id;
 */

/******************TESTING****************************/

?>
