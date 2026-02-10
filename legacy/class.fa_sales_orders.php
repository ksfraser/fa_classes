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
 * Various modules need to be able to get info about purchase order details from FA
 *
 *	This class uses FA specific routines (display_notification etc)
 *	This is a wrapper for the FA table.
 *
 * **********************************************************/
class fa_sales_orders_model extends fa_table_wrapper
{
	var $min_cid;
	var $max_cid;
	var $errors = array();
	var $warnings = array();

	/*
| order_no         | int(11)             | NO   | PRI | NULL       |       |
| trans_type       | smallint(6)         | NO   | PRI | 30         |       |
| version          | tinyint(1) unsigned | NO   |     | 0          |       |
| type             | tinyint(1)          | NO   |     | 0          |       |
| debtor_no        | int(11)             | NO   |     | 0          |       |
| branch_code      | int(11)             | NO   |     | 0          |       |
| reference        | varchar(100)        | NO   |     |            |       |
| customer_ref     | tinytext            | NO   |     | NULL       |       |
| comments         | tinytext            | YES  |     | NULL       |       |
| ord_date         | date                | NO   |     | 0000-00-00 |       |
| order_type       | int(11)             | NO   |     | 0          |       |
| ship_via         | int(11)             | NO   |     | 0          |       |
| delivery_address | tinytext            | NO   |     | NULL       |       |
| contact_phone    | varchar(30)         | YES  |     | NULL       |       |
| contact_email    | varchar(100)        | YES  |     | NULL       |       |
| deliver_to       | tinytext            | NO   |     | NULL       |       |
| freight_cost     | double              | NO   |     | 0          |       |
| from_stk_loc     | varchar(5)          | NO   |     |            |       |
| delivery_date    | date                | NO   |     | 0000-00-00 |       |
| payment_terms    | int(11)             | YES  |     | NULL       |       |
| total            | double              | NO   |     | 0          |       |

	 *
	 * */
	protected $order_no         ;// int(11)             | NO   | PRI | NULL       |       |
	protected $trans_type       ;// smallint(6)         | NO   | PRI | 30         |       |
	protected $version          ;// tinyint(1) unsigned | NO   |     | 0          |       |
	protected $type             ;// tinyint(1)          | NO   |     | 0          |       |
	protected $debtor_no        ;// int(11)             | NO   |     | 0          |       |
	protected $branch_code      ;// int(11)             | NO   |     | 0          |       |
	protected $reference        ;// varchar(100)        | NO   |     |            |       |
	protected $customer_ref     ;// tinytext            | NO   |     | NULL       |       |
	protected $comments         ;// tinytext            | YES  |     | NULL       |       |
	protected $ord_date         ;// date                | NO   |     | 0000-00-00 |       |
	protected $order_type       ;// int(11)             | NO   |     | 0          |       |
	protected $ship_via         ;// int(11)             | NO   |     | 0          |       |
	protected $delivery_address ;// tinytext            | NO   |     | NULL       |       |
	protected $contact_phone    ;// varchar(30)         | YES  |     | NULL       |       |
	protected $contact_email    ;// varchar(100)        | YES  |     | NULL       |       |
	protected $deliver_to       ;// tinytext            | NO   |     | NULL       |       |
	protected $freight_cost     ;// double              | NO   |     | 0          |       |
	protected $from_stk_loc     ;// varchar(5)          | NO   |     |            |       |
	protected $delivery_date    ;// date                | NO   |     | 0000-00-00 |       |
	protected $payment_terms    ;// int(11)             | YES  |     | NULL       |       |
	protected $total            ;// double              | NO   |     | 0          |       |
	protected $days_before;		//!<int how many days to search before ord_date
	protected $days_after;		//!<int how many days to search after ord_date

	protected $cart;		//!<object
					//view_sales_order loads the cart right from the start.
					//and then saves it into _SESSION['view']	
					//_SESSION['view']->cust_ref, ->deliver_to, ->customer_name ->document_date, ->due_date, ->customer_currency ->location_name ->delivery_address
					//				->reference  ->phone  ->email  ->Comments
					//			->line_items has the stock data.
					//				->quantity, ->price, ->discount_percent, ->stock_id, ->description. ->units
	protected $delivery_numbers;	//!<array of trans_no.  Set by getDeliveryData;
	protected $delivery_data;	//!<array
	protected $invoice_numbers;	//!<array of trans_no.  Set by getinvoiceData;
	protected $invoice_data;	//!<array
	protected $credit_numbers;	//!<array of trans_no.  Set by getCreditData;
	protected $credit_data;		//!<array


	//function __construct( /*$prefs_db*/ )
	function __construct( $caller = null )
	{
		//parent::__construct( $prefs_db );
		$this->iam = "sales_orders";
		parent::__construct( $caller );
		$descl = 'varchar(' . DESCRIPTION_LENGTH . ')';
		$this->table_details['tablename'] = TB_PREF . 'sales_orders';
		$this->fields_array[] = array('name' => 'order_no', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => 'NULL', );
		$this->fields_array[] = array('name' => 'trans_type', 'type' => 'smallint(6) ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '30', );
		$this->fields_array[] = array('name' => 'version', 'type' => 'tinyint(1) unsigned', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'type', 'type' => 'tinyint(1) ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'debtor_no', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'branch_code', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'reference', 'type' => 'varchar(100) ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '', );  
		$this->fields_array[] = array('name' => 'customer_ref', 'type' => 'tinytext  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => 'NULL', );  
		$this->fields_array[] = array('name' => 'comments', 'type' => 'tinytext  ', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => 'NULL', );  
		$this->fields_array[] = array('name' => 'ord_date', 'type' => 'date  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0000-00-00', );
		$this->fields_array[] = array('name' => 'order_type', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'ship_via', 'type' => 'int ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'delivery_address', 'type' => 'tinytext  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => 'NULL', );  
		$this->fields_array[] = array('name' => 'contact_phone', 'type' => 'varchar(30) ', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => 'NULL', );  
		$this->fields_array[] = array('name' => 'contact_email', 'type' => 'varchar(100) ', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => 'NULL', );  
		$this->fields_array[] = array('name' => 'deliver_to', 'type' => 'tinytext  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => 'NULL', );  
		$this->fields_array[] = array('name' => 'freight_cost', 'type' => 'double  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  
		$this->fields_array[] = array('name' => 'from_stk_loc', 'type' => 'varchar(5) ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => ' ', );  
		$this->fields_array[] = array('name' => 'delivery_date', 'type' => 'date  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0000-00-00', );
		$this->fields_array[] = array('name' => 'payment_terms', 'type' => 'int ', 'null' => 'NULL', 'readwrite' => 'readwrite', 'default' => 'NULL', );  
		$this->fields_array[] = array('name' => 'total', 'type' => 'double  ', 'null' => 'NOT NULL', 'readwrite' => 'readwrite', 'default' => '0', );  


		$this->fields_array[] = array('name' => 'quantity_received', 'type' => 'double', 'null' => 'NOT NULL', 'default' => '0',           'readwrite' => 'readwrite',  );
		$this->table_details['primarykey'] = "order_no, trans_type";
	}
	/**//**
	* Set fields
	*
	* @param string field name
	* @param mixed field value
	* @param bool do we need to enforce membership
	************************************************
	function set( $var, $value = null, $enforce = true )
	{
		switch( $var )
		{	
			case "fields_array":
			case "table_details":
				throw new Exception( "$var is not allowed to be set!" );
				break;
			case "delivery_numbers":
			case "delivery_data":
			case "invoice_numbers":
			case "invoice_data":
			case "credit_numbers":
			case "credit_data":
				if( is_array( $value ) )
				{
					return parent::set( $var, $value, $enforce );
				}	
				else
				{
					throw new Exception( "$var is not of type array" );
				}
				break;
			case "trans_no":
				return $this->set( "order_no", $value, $enforce );
				break;
			default:
				return parent::set( $var, $value, $enforce );
				break;
		}
	}
	/**//*************************************************************
	* Initialize a cart to find the items data
	* 
	* @since 20250321
	*
	* @params none uses internal
	* @returns bool did we find a cart
	*******************************************************************
	function initCart()
	{
		if( ! isset( $this->trans_type ) )
		{
			throw new Exception( "Transaction Type not set so can't load a cart", KSF_FIELD_NOT_SET );
		}
		if( ! isset( $this->order_no ) )
		{
			throw new Exception( "Order Number not set so can't load a cart", KSF_FIELD_NOT_SET );
		}
		$this->cart = new Cart( $this->get( "trans_type" ), $this->get( "order_no" ) );
		if( isset( $this->cart ) and is_object( $this->cart ) )
			return true;
		else
			return false;
	}
	/*************************************************//**
	 * Retrieve Item, quantity, Supplier, Days to arrive on an order by order basis
	 *
	 * @param none
	 * @return none.  Sets internal variable
	 * ***************************************************/
	function order2deliverydays()
	{
		//select d.item_code, s.supp_name, abs(datediff(d.delivery_date, o.ord_date) ) from 1_purch_order_details d, 1_purch_orders o, 1_suppliers s  where o.order_no=d.order_no   and o.supplier_id=s.supplier_id  order by d.item_code, s.supp_name;
		$this->select_array[] = 'd.item_code as stock_id';
		$this->select_array[] = 's.supp_name as supplier';
		$this->select_array[] = 'abs(datediff(d.delivery_date, o.ord_date) ) as days';
		$this->select_array[] = 'd.order_no as order_number';
		$this->select_array[] = 'd.quantity_ordered as quantity';
		$this->from_array[] = 'purch_order_details d';
		$this->from_array[] = 'purch_orders o';
		$this->from_array[] = 'suppliers s';
		$this->where_array['o.order_no'] ='d.order_no';
		$this->where_array['o.supplier_id'] = 's.supplier_id';
		$this->orderby_array = array( 'd.item_code', 's.supp_name' );
		$this->buildSelectQuery();
	}
	/**//***********************************************************
	* Serach for a matching invoice/order
	*
	* @since 20250318
	*
	* @param string order date
	* @param int trans_type
	* @param int debtor_no|null	match against a specific customer or any
	* @param bool exact date only
	* @returns array invoice data 
	******************************************************************/
	function findMatchingInvoices( $ord_date, $trans_type = ST_SALESINVOICE, $debtor_no = null, $b_exact_date = true )
	{
/*
		var_dump( __FILE__ . "::" . __LINE__ . "<br />" );
		var_dump( "Debtor: " . $debtor_no );
		var_dump( "<br />" );
		var_dump( "Exact Date: " );
		var_dump( $b_exact_date );
		var_dump( "<br />" );
*/
	
		$this->clear_sql_vars();
		//SELECT * from 1_sales_orders where ord_date='' and debtor_no='' and trans_type=''
		$this->select_array[] = '*';
		$this->from_array[] = TB_PREF . $this->iam;
		if( $b_exact_date )
		{
			$this->where_array['ord_date'] = $ord_date;
		}
		else
		{
			//we want to search a date range
			$ord_date_count = 0;
			if( isset( $this->days_before ) )
			{	
				// g.tran_date >= s.Date and g.tran_date < DATE_ADD( s.Date, interval 5 day )
				//$this->where_array['ord_date'] = '$ord_date;
				$days_before =  $this->days_before;
				$this->where_array['ord_date'] = array( 'gte',
									 "DATE_SUB( $ord_date, INTERVAL $days_before DAY )" 
									);
				$ord_date_count++;
			}
			if( isset( $this->days_after ) )
			{	
				$this->where_array['ord_date'] = array( 'lte', "DATE_ADD( $ord_date, interval $this->days_after day )" );
				$ord_date_count++;
			}
			if( $ord_date_count < 1 )
			{
				$this->where_array['ord_date'] = $ord_date;
			}
		}
		$this->where_array['trans_type'] = $trans_type;
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
	/**//*******************************************************
	* Grab the DELIVERY data
	*
	* Code copied from view_sales_order, 
	* where it is displayed rather than put into an array
	*	
	* @since 20250321
	*
	* @param none
	* @return array
	********************************************************/
	function getDeliveryData()
	{
		if( ! isset ( $this->order_no ) )
		{
			if( isset( $_GET['trans_no'] ) )
			{
				$this->set( "order_no", $_GET['trans_no'] );
			}
			else
			{
				throw new Exception( "Order Number not set so can't find deliveries", KSF_FIELD_NOT_SET );
			}
		}
		$res_arr = array();
		if ( $result = get_sales_child_documents( ST_SALESORDER, $this->get( "order_no" ) ) ) 
		{
			$k = 0;
			while ($del_row = db_fetch($result))
			{
				$dn_numbers[] = $del_row["trans_no"];
				$this_total = $del_row["ov_freight"]+ $del_row["ov_amount"] + $del_row["ov_freight_tax"]  + $del_row["ov_gst"] ;
				$delivery_total += $this_total;
				$res_arr[$k] = $del_row; 
				$res_arr[$k]['delivery_total'] = $delivery_total; 
				$res_arr[$k]['total'] = $this_total; 
				$k++;
			}
		}
		$res_array['delivery_numbers'] = $dn_numbers;
		$this->set( "delivery_numbers", $dn_numbers );
		$this->set( "delivery_data", $res_array );
		return $res_array;
	}
	/**//*******************************************************
	* Grab the INVOICE data
	*
	* Code copied from view_sales_order, 
	* where it is displayed rather than put into an array
	*	
	* @since 20250321
	*
	* @param none
	* @return array
	********************************************************/
	function getInvoiceData()
	{
		if( ! isset ( $this->delivery_numbers ) )
		{
			$res = $this->getDeliveryData();
			if( ! isset ( $this->delivery_numbers ) )
			{
				throw new Exception( "Delivery Number(s) not found so can't find Invoices", KSF_FIELD_NOT_SET );
			}
		}
		$res_arr = array();
		if ( $result = get_sales_child_documents( ST_CUSTDELIVERY, $this->get( "delivery_numbers" ) ) ) 
		{
			$k = 0;
			while ($del_row = db_fetch($result))
			{
				$trans_numbers[] = $del_row["trans_no"];
				$this_total = $del_row["ov_freight"]+ $del_row["ov_amount"] + $del_row["ov_freight_tax"]  + $del_row["ov_gst"] ;
				$credits_total += $this_total;
				$res_arr[$k] = $del_row; 
				$res_arr[$k]['credits_total'] = $credits_total; 
				$res_arr[$k]['total'] = $this_total; 
				$k++;
			}
		}
		$res_array['invoice_numbers'] = $trans_numbers;
		$this->set( "invoice_numbers", $trans_numbers );
		$this->set( "invoice_data", $res_array );
		return $res_array;
	}
	/**//*******************************************************
	* Grab the Customer CREDITS data
	*
	* Code copied from view_sales_order, 
	* where it is displayed rather than put into an array
	*	
	* @since 20250321
	*
	* @param none
	* @return array
	********************************************************/
	function getCreditsData()
	{
		if( ! isset ( $this->invoice_numbers ) )
		{
			$res = $this->getInvoiceData();
			if( ! isset ( $this->invoice_numbers ) )
			{
				throw new Exception( "Invoice Number(s) not found so can't find Credits", KSF_FIELD_NOT_SET );
			}
		}
		$res_arr = array();
		if ( $result = get_sales_child_documents( ST_SALESINVOICE, $this->get( "invoice_numbers" ) ) ) 
		{
			$k = 0;
			while ($del_row = db_fetch($result))
			{
				$trans_numbers[] = $del_row["trans_no"];
				$this_total = $del_row["ov_freight"]+ $del_row["ov_amount"] + $del_row["ov_freight_tax"]  + $del_row["ov_gst"] ;
				$credits_total += $this_total;
				$res_arr[$k] = $del_row; 
				$res_arr[$k]['credits_total'] = $credits_total; 
				$res_arr[$k]['total'] = $this_total; 
				$k++;
			}
		}
		$res_array['credits_numbers'] = $trans_numbers;
		$this->set( "credit_numbers", $trans_numbers );
		$this->set( "credit_data", $res_array );
		return $res_array;
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
