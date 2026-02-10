<?php

require_once( 'class.origin.php' );

class fa_gl extends origin
{
/*
	*****************ORIGIN**************************************
	*function __construct( $loglevel = PEAR_LOG_DEBUG, $param_arr = null )
	*public function __call($method, $arguments)
	*function __get( $prop ) {
	*function __isset( $prop ) {
	*function is_supported_php() {
	*function object_var_names_old()
	*function object_var_names()
	*function user_access( $action )
	*function set( $field, $value = null, $enforce_only_native_vars = true )
	*function set_array( $field, $value = null, $index = 0, $enforce_only_native_vars = true, $autoinc_index = false, $replace = false )
	*function unset_var( $field )
	*function set_var( $var, $value )
	*function get( $field )
	*function get_var( $var )
	*function var2data()
	*function fields2data( $fieldlist )
	*function LogError( $message, $level = PEAR_LOG_ERR )
	*function LogMsg( $message, $level = PEAR_LOG_INFO )
	*function var_dump( $var, $level = PEAR_LOG_DEBUG )
	*function objectvars2array()
	*function match_tokens( $arr1, $arr2 )
	*function obj2obj( $obj )
	*function arr2obj( $arr )
	*function score_matches( $field, $value )
	*function isdiff( $key, $value )
	*****************ORIGIN**************************************
	*protected $config_values = array();   //!< What fields to be put on config screen.  Probably doesn't belong in this class :(
	*protected $tabs = array();
	*var $help_context;	  //!< help context for screens in FA
	*var $tb_pref;		       //!< FrontAccounting Table Prefix (i.e. 0_)
	*var $loglevel;		  //!< PEAR_LOG level that must be specified to be added to log/errors
	*var $errors;		    //!< array of error messages
	*var $log;		       //!< array of log messages
	*var $fields;		    //!< array of fields in the class
	*var $data;		      //!< array of data from the fields
	*private $testvar;
	*var $object_fields;	     //!< array of the variables in this object, under '___SOURCE_KEYS_'
	*protected $matchscores; //!<array indicating how many points for matching the field
	*protected $application;  //!< string which application is the child object holding data for
	*protected $module;	    //!< string which module is the child object holding data for
	*protected $container_arr;       //__get/__isset uses this
	*protected $obj_var_name_arr;    //Array of field names in this object that need to be translated in the NVL array
	*protected $dest_var_name_arr;   //Array of field names in the DEST Object for translating.
	*protected $name_value_list;
	*private static $_instance;      //For IteratorAggregate
	***!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!***
*/

	protected $type;
	protected $trans_id;	//!<int	  trans_no in other code
	protected $date_;	//!<string display date (not SQL date)
	protected $account;
	protected $dimension;
	protected $dimension2;
	protected $memo_;
	protected $amount;	//!<float amount in ->currency
	protected $currency;	//!<string	if not set, no conversion between currencies.
	protected $person_type_id;
	protected $person_id;
	protected $err_msg;
	protected $rate;
	protected $from_date;
	protected $to_date;
	protected $amount_min;	//!<float 
	protected $amount_max;	//!<float
	protected $filter_type;	//!<int a GL type
	protected $cart;	//!<class
	protected $debug;	//This might be worth having in origin!!

	/**//**
	*
	**/
	function set( $field, $value = NULL, $enforce = false )
	{
		switch( $field )
		{
			case 'trans_no' :
				$field = 'trans_id';
				break;
		}
		parent::set( $field, $value, $enforce );
	}
	/**//********************************************
	* Add a GL trans
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount in home currency
	*************************************************/
	function add_gl_trans()
	{
		return add_gl_trans($this->type, $this->trans_id, $this->date_, $this->account, $this->dimension, $this->dimension2, $this->memo_,
		$this->amount, $this->currency=null, $this->person_type_id=null, $this->person_id=null,	$this->err_msg="", $this->rate=0);
	}
	/**//********************************************
	* GL Trans for standard costing, always home currency regardless of person
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function add_gl_trans_std_cost()
	{
		return add_gl_trans_std_cost($this->type, $this->trans_id, $this->date_, $this->account, $this->dimension, $this->dimension2, $this->memo_,	$this->amount, $this->person_type_id=null, $this->person_id=null, $this->err_msg="");
	}
	/**//********************************************
	* Add a GL balancing trans for rounding
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function add_gl_balance()
	{
		return add_gl_balance($this->type, $this->trans_id, $this->date_, $this->amount, $this->person_type_id=null, $this->person_id=null);
	}
	/**//********************************************
	* Get GL transactions
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_gl_transactions()
	{
		return get_gl_transactions($this->from_date, $this->to_date, $this->trans_id=0,
			$this->account=null, $this->dimension=0, $this->dimension2=0, $this->filter_type=null,
			$this->amount_min=null, $this->amount_max=null, $this->person_id=null);
	}
	/**//********************************************
	* Get a specific GL transaction
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_gl_trans()
	{
		return get_gl_trans($this->type, $this->trans_id);
	}

	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_gl_wo_cost_trans()
	{		
		return get_gl_wo_cost_trans($this->trans_id, $this->person_id=-1);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_gl_balance_from_to()
	{	
		return get_gl_balance_from_to($this->from_date, $this->to_date, $this->account, $this->dimension=0, $this->dimension2=0);
	}
	/**//********************************************
	* Get the SUM of the amounts for the account between the dates
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_gl_trans_from_to()
	{	
		return get_gl_trans_from_to($this->from_date, $this->to_date, $this->account, $this->dimension=0, $this->dimension2=0);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_balance()
	{
		return get_balance($this->account, $this->dimension, $this->dimension2, $this->from, $this->to, $this->from_incl=true, $this->to_incl=true);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_budget_trans_from_to()
	{	
		return get_budget_trans_from_to($this->from_date, $this->to_date, $this->account, $this->dimension=0, $this->dimension2=0);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function exists_gl_budget()
	{
		return exists_gl_budget($this->date_, $this->account, $this->dimension, $this->dimension2);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function add_update_gl_budget_trans()
	{
		return add_update_gl_budget_trans($this->date_, $this->account, $this->dimension, $this->dimension2, $this->amount);	
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function delete_gl_budget_trans()
	{
		return delete_gl_budget_trans($this->date_, $this->account, $this->dimension, $this->dimension2);	
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_only_budget_trans_from_to()
	{
		return get_only_budget_trans_from_to($this->from_date, $this->to_date, $this->account, $this->dimension=0, $this->dimension2=0);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function add_gl_tax_details()
	{
		return add_gl_tax_details($this->gl_code, $this->trans_type, $this->trans_no, $this->amount, $this->ex_rate, $this->date, $this->memo, $this->included=0, $this->net_amount = null);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function add_trans_tax_details()
	{
		return add_trans_tax_details($this->trans_type, $this->trans_no, $this->tax_id, $this->rate, $this->included,
				$this->amount, $this->net_amount, $this->ex_rate, $this->tran_date, $this->memo, $this->reg_type=null);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_trans_tax_details()
	{
		return get_trans_tax_details($this->trans_type, $this->trans_no);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function void_trans_tax_details()
	{
		return void_trans_tax_details($this->type, $this->type_no);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function clear_trans_tax_details()
	{
		return clear_trans_tax_details($this->type, $this->type_no);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_tax_summary()
	{
		return get_tax_summary($this->from, $this->to);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function write_journal_entries()
	{	
		return write_journal_entries($this->cart, $this->reverse, $this->use_transaction=true);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function exists_gl_trans()
	{	
		return exists_gl_trans($this->type, $this->trans_id);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function void_gl_trans()
	{
		return void_gl_trans($this->type, $this->trans_id, $this->nested=false);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function void_journal_trans()
	{
		return void_journal_trans($this->type, $this->type_no, $this->use_transaction=true);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	* 
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_sql_for_journal_inquiry()
	{
		return get_sql_for_journal_inquiry($this->filter, $this->from, $this->to, $this->ref='', $this->memo='', $this->alsoclosed=false);
	}
	/**//********************************************
	* Find matching transactions
	*
	* @since 20250314
	* 
	* @param array transaction types.  default ST_JOURNAL
	* @param float amount 
	* @param date transaction date
	* @param int days before date default 2 (posting over a weekend)
	* @param int days after date default 2 (posting over a weekend)
	* @param string|null account to search
	* @param array|null array of transaction numbers to ignore
	* @returns array associated array transaction details
	*************************************************/
	function findMatchingTransactions( $typeArr = array( ST_JOURNAL ), $amount, $date_, $before = 2, $after = 2, $account = null, $ignore_type_no_arr = null )
	{
		$ret_arr = array();
		$sql = "SELECT  amount, type, type_no, tran_date";
		$sql .= " FROM " . TB_PREF . "gl_trans g ";
		$sql .= "WHERE  abs(g.amount)='" . $amount . "'";
		$sql .= " and g.tran_date >= DATE_SUB( '" . $date_ . "', interval " . $before . " day )";
		$sql .= " and g.tran_date <  DATE_ADD( '" . $date_ . "', interval " . $after . " day )";
		if( null !== $account )
			$sql .= " and g.account='" . $account . "'";
		$sql .= " and g.type in ('";
		$tcount = 0;
		foreach( $typeArr as $val )
		{
			if( $tcount )
				$sql .= ", '";
			$sql .= $val ."'";
			$tcount++;
		}
		$sql .= ") ";
		if( null !== $ignore_type_no_arr )
		{
			$icount = 0;
			$sql .= " and g.type_no not in ('";
			foreach( $ignore_type_no_arr as $val )
			{
				if( $icount )
					$sql .= ", '";
				$sql .= $val ."'";
				$icount++;
			}
			$sql .= "') ";
		}
		
		
		if( $this->debug == PEAR_LOG_DEBUG )
		{
			var_dump( __FILE__ . "::" . __LINE__ . "::" . $sql );
		}
		$res = db_query( $sql, "can't find a matching GL trans" );
		while ( $trx = db_fetch_assoc( $res ) )
		{
			$ret_arr[] = $trx;
		}
		//array(4) { ["amount"]=> string(7) "-544.49" ["type"]=> string(1) "4" ["type_no"]=> string(3) "192" ["tran_date"]=> string(10) "2024-01-23" }
		//var_dump( $trx );
		//return $trx;
		return $ret_arr;
	}
}
