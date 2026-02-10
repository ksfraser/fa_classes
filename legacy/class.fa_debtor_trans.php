<?php

require_once( 'class.fa_table_wrapper.php' );

$path_to_root="../..";

/*
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/inventory/includes/inventory_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_codes_db.inc");
include_once($path_to_root . "/workcenters/includes/workcenters_db.inc");


/********************************************************//**
 * Get details about SALES INVOICES
 *
 *	This class uses FA specific routines (display_notification etc)
 *	This is a wrapper for the FA table.
 *
 * **********************************************************/
class fa_debtor_trans_model extends fa_table_wrapper
{
	var $min_cid;
	var $max_cid;
	var $errors = array();
	var $warnings = array();

	/*
	*	+----------------+----------------------+------+-----+------------+-------+
	*	| Field          | Type                 | Null | Key | Default    | Extra |
	*	+----------------+----------------------+------+-----+------------+-------+
	*	| trans_no       | int(11) unsigned     | NO   | PRI | 0          |       |
	*	| type           | smallint(6) unsigned | NO   | PRI | 0          |       |
	*	| version        | tinyint(1) unsigned  | NO   |     | 0          |       |
	*	| debtor_no      | int(11) unsigned     | YES  | MUL | NULL       |       |
	*	| branch_code    | int(11)              | NO   |     | -1         |       |
	*	| tran_date      | date                 | NO   | MUL | 0000-00-00 |       |
	*	| due_date       | date                 | NO   |     | 0000-00-00 |       |
	*	| reference      | varchar(60)          | NO   |     |            |       |
	*	| tpe            | int(11)              | NO   |     | 0          |       |
	*	| order_         | int(11)              | NO   |     | 0          |       |
	*	| ov_amount      | double               | NO   |     | 0          |       |
	*	| ov_gst         | double               | NO   |     | 0          |       |
	*	| ov_freight     | double               | NO   |     | 0          |       |
	*	| ov_freight_tax | double               | NO   |     | 0          |       |
	*	| ov_discount    | double               | NO   |     | 0          |       |
	*	| alloc          | double               | NO   |     | 0          |       |
	*	| rate           | double               | NO   |     | 1          |       |
	*	| ship_via       | int(11)              | YES  |     | NULL       |       |
	*	| dimension_id   | int(11)              | NO   |     | 0          |       |
	*	| dimension2_id  | int(11)              | NO   |     | 0          |       |
	*	| payment_terms  | int(11)              | YES  |     | NULL       |       |
	*	+----------------+----------------------+------+-----+------------+-------+



	 *
	 * */
	protected $trans_no         ;// int(11)             | NO   | PRI | NULL       |       |
	protected $type       ;// smallint(6)         | NO   | PRI | 30         |       |
	protected $version          ;// tinyint(1) unsigned | NO   |     | 0          |       |
	protected $debtor_no        ;// int(11)             | NO   |     | 0          |       |
	protected $branch_code      ;// int(11)             | NO   |     | 0          |       |
	protected $tran_date         ;// date                | NO   |     | 0000-00-00 |       |
	protected $due_date         ;// date                | NO   |     | 0000-00-00 |       |
	protected $reference        ;// varchar(100)        | NO   |     |            |       |
	protected $tpe       ;// int(11)             | NO   |     | 0          |       |
	protected $order_       ;// int(11)             | NO   |     | 0          |       |
	protected $ov_amount            ;// double              | NO   |     | 0          |       |
	protected $ov_gst            ;// double              | NO   |     | 0          |       |
	protected $ov_freight     ;// double              | NO   |     | 0          |       |
	protected $ov_freight_tax     ;// double              | NO   |     | 0          |       |
	protected $ov_discount     ;// double              | NO   |     | 0          |       |
	protected $alloc     ;// double              | NO   |     | 0          |       |
	protected $rate     ;// double              | NO   |     | 0          |       |
	protected $ship_via    ;// int(11)             | YES  |     | NULL       |       |
	protected $dimension_id    ;// int(11)             | YES  |     | NULL       |       |
	protected $dimension2_id    ;// int(11)             | YES  |     | NULL       |       |
	protected $payment_terms    ;// int(11)             | YES  |     | NULL       |       |
	protected $days_before;		//!<int how many days to search before tran_date
	protected $days_after;		//!<int how many days to search after tran_date
	protected $rounding_amount;	//!<float with taxes, rounding can mismatch.  How much variance for when we search for mathces?
	protected $net_sales;		//!<float	For matching imported transactions
	protected $total_collected;	//!<float	For matching imported transactions

	//function __construct( /*$prefs_db*/ )
	function __construct( $caller = null )
	{
		//parent::__construct( $prefs_db );
		$this->iam = "debtor_trans";
		parent::__construct( $caller );
		$descl = 'varchar(' . DESCRIPTION_LENGTH . ')';
		$this->table_details['tablename'] = TB_PREF . 'debtor_trans';
		$this->fields_array[] = array('name' => 'trans_no', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => 'NULL', );
		$this->fields_array[] = array('name' => 'type', 'type' => 'smallint(6) ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '30', );
		$this->fields_array[] = array('name' => 'version', 'type' => 'tinyint(1) unsigned', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'debtor_no', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'branch_code', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'tran_date', 'type' => 'date  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0000-00-00', );
		$this->fields_array[] = array('name' => 'due_date', 'type' => 'date  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0000-00-00', );
		$this->fields_array[] = array('name' => 'reference', 'type' => 'varchar(100) ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '', );  
		$this->fields_array[] = array('name' => 'tpe', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'order', 'type' => 'double  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'ov_amount', 'type' => 'double  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'ov_gst', 'type' => 'double  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'ov_freight', 'type' => 'double  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'ov_freight_tax', 'type' => 'double  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'ov_discount', 'type' => 'double  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'alloc', 'type' => 'double  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'rate', 'type' => 'double  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'ship_via', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'dimension_id', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'dimension2_id', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'payment_terms', 'type' => 'int ', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => 'NULL', );  

		$this->set( "rounding_amount", 0.01 );	//set penny rounding

	}
/*
function set( $var, $val = null, $enforce = false )
{

var_dump( "<br />" . __FILE__ . "::" . __LINE__ . "<br />");
var_dump( $var . "::" . $val );
var_dump( "<br />");
	try {
		$ret =  parent::set( $var, $val, $enforce );
var_dump( "<br />" . __FILE__ . "::" . __LINE__ . "<br />");
var_dump( $ret );
var_dump( "<br />");
		return $ret;
	} catch( Exception $e )
	{
		var_dump( $e );
	}
}
*/
	/*************************************************//**
	 * Retrieve Item, quantity, Supplier, Days to arrive on an order by order basis
	 *
UNTESTED
	 * @param none
	 * @return none.  Sets internal variable
	 * *************************************************** /
	function order2deliverydays()
	{
		//select d.item_code, s.supp_name, abs(datediff(d.delivery_date, o.tran_date) ) from 1_purch_order_details d, 1_purch_orders o, 1_suppliers s  where o.trans_no=d.trans_no   and o.supplier_id=s.supplier_id  order by d.item_code, s.supp_name;
		$this->select_array[] = 'd.item_code as stock_id';
		$this->select_array[] = 's.supp_name as supplier';
		$this->select_array[] = 'abs(datediff(d.delivery_date, o.tran_date) ) as days';
		$this->select_array[] = 'd.trans_no as order_number';
		$this->select_array[] = 'd.quantity_ordered as quantity';
		$this->from_array[] = 'purch_order_details d';
		$this->from_array[] = 'purch_orders o';
		$this->from_array[] = 'suppliers s';
		$this->where_array['o.trans_no'] ='d.trans_no';
		$this->where_array['o.supplier_id'] = 's.supplier_id';
		$this->orderby_array = array( 'd.item_code', 's.supp_name' );
		$this->buildSelectQuery();
	}
UNTESTED
	/**/
	/**//***********************************************************
	* Serach for a matching invoice/order
	*
	* @since 20250320
	*
	* @param string tran date
	* @param int type
	* @param int debtor_no|null	match against a specific customer or any
	* @param bool exact date only
	* @param int days before
	* @param int days_after
	* @param float rounding value
	* @returns array invoice data 
	******************************************************************/
	function findMatchingInvoices( $tran_date, $type = ST_SALESINVOICE, $debtor_no = null, $b_exact_date = true, $days_before = 0, $days_after = 0, $rounding_amount = 0.01 )
	{
		//var_dump( __FILE__ . "::" . __LINE__ . "<br />" );
		//var_dump( "Debtor: " . $debtor_no );
		//var_dump( "<br />" );
		//var_dump( "Exact Date: " );
		//var_dump( $b_exact_date );
		//var_dump( "<br />" );
	
		$this->clear_sql_vars();
		//SELECT * from 1_debtor_trans where tran_date='' and debtor_no='' and type=''
		$this->select_array[] = '*';
		$this->from_array[] = TB_PREF . $this->iam;
		if( $b_exact_date )
		{
			$this->where_array['tran_date'] = $tran_date;
		}
		else
		{
				// g.tran_date >= s.Date and g.tran_date < DATE_ADD( s.Date, interval 5 day )
				$this->where_array['tran_date'] = array( 'betweenf',
									 "DATE_SUB( '$tran_date', INTERVAL $days_before DAY )" ,
									 "DATE_ADD( '$tran_date', INTERVAL $days_after DAY )" 
									);
		}
		if( isset( $this->net_sales ) )
		{
		}
		if( isset( $this->ov_amount ) )
		{
			$this->where_array['ov_amount'] = array( 'between', $this->ov_amount - $rounding_amount, $this->ov_amount + $rounding_amount );
		}
		if( isset( $this->total_collected ) )
		{
			//If a payment has been applied and allocted correctly
			$this->where_array['alloc'] = array( 'between', $this->total_collected - $rounding_amount, $this->total_collected  + $rounding_amount );
			//OR ov_amount + 0v_gst + 0v_freight + ov_freight_tax + ov_discount

			//var_dump( "<br />->alloc<br />" );
		}
		if( isset( $this->alloc ) )
		{
			$this->where_array['alloc'] = $this->alloc;
			//var_dump( "<br />->alloc<br />" );
		}
		$this->where_array['type'] = $type;
		if( null !== $debtor_no )
		{
			$this->where_array['debtor_no'] = $debtor_no;
		}
		$this->buildSelectQuery();
/*
		var_dump( __FILE__ . "::" . __LINE__ . "<br />" );
		var_dump( $this->sql );
		var_dump( "<br />" );
*/
		return $this->query( "Couldn't find matching Invoices" );
	
	}
	function boo()
	{
	}
	/**//**
	*
	**/
	function getById()
	{
		return $this->getByPrimaryKey();
	}
	/**/
}


?>
