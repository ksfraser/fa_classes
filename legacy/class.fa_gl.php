<?php

require_once( 'class.fa_origin.php' );
/**************************************************************************************//**
* MODEL class to handle FA's GL records
*
* @todo refactor FHS modules for changes to field names dimension1, max_dollar, min_dollar.  See ->set
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
	*function countMatchingTokens( $arr1, $arr2 )
	***!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!***
******************************************************************************************************/
class fa_gl extends fa_origin
{
	protected $startdate;	//!<date
	protected $enddate;	//!<date
	protected $account;	//!<string
	protected $dimension1;	//!<string
	protected $dimension;	//!<string
	protected $dimension2;	//!<string
	protected $filter;	//!<
	protected $min_dollar;	//!<float
	protected $max_dollar;	//!<fload
	protected $type;
	protected $type_no;    //!<int   trans_no in other code
	protected $date_;       //!<string display date (not SQL date)
	protected $memo_;
	protected $amount;      //!<float amount in ->currency
	protected $currency;    //!<string      if not set, no conversion between currencies.
	protected $person_type_id;
	protected $person_id;	//!<int FK
	protected $matching_gl_entries;	//!<array
	protected $days_spread;	//!<int
	protected $arr_arr;	//!<array
	protected $max_arrs;	//!<int
	protected $accountName;	//!<string accountName from the Transaction we are comparing from Bank Import
	protected $transactionDC;	//!<char D or C or B.  From Bank Import
	protected $transactionCode;	//!<string	Transaction REF from bank.  Not guaranteed to be GUID.
	protected $err_msg;
	protected $rate;
	protected $from_date;
	protected $to_date;
	protected $amount_min;  //!<float
	protected $amount_max;  //!<float
	protected $filter_type; //!<int a GL type
	protected $cart;	//!<class
	protected $debug;       //This might be worth having in origin!!


	function __construct()
	{
		parent::__construct();
		$this->init_vars();
	}
	/**//**********************************************
	* Intercept fields we renamed.
	*
	* @since 20250401
	* 
	* @param string fieldname
	* @returns mixed field's value
	**************************************************/
	function get( $field )
	{
		switch( $field )
		{
			case "min_dollar":
				$field = "amount_min";
			case "max_dollar":
				$field = "amount_max";
			case "dimension1":
				$field = "dimension";
			case "trans_type":
				$field = "type";
			case "trans_id":
				$field = "type_no";
			break;
		}
		return parent::get( $field );

	}
	function set( $field, $value = null, $enforce = true )
	{
		switch( $field )
		{
			case "startdate":
			case "enddate":
				$value = sql2date( $value );
			break;
			case "max_arrs":
				$value = (int) $value;
			case "min_dollar":
				$this->set( "amount_min", $value, $enforce );
				//Did this so we can trap the misnamed and not have to fix everything
				throw new Exception( "min_dollar should be amount_min!!!" );
				break;
			case "max_dollar":
				$this->set( "amount_max", $value, $enforce );
				//Did this so we can trap the misnamed and not have to fix everything
				throw new Exception( "max_dollar should be amount_max!!!" );
				break;
			case "dimension1":
				$this->set( "dimension", $value, $enforce );
				//Did this so we can trap the misnamed and not have to fix everything
				throw new Exception( "Dimension2 should be dimension!!!" );
			case "trans_type":
				$this->set( "type", $value, $enforce );
				//Did this so we can trap the misnamed and not have to fix everything
				throw new Exception( "trans_type should be type in FA_GL!" );
			case "trans_id":
				$this->set( "type_no", $value, $enforce );
				//Did this so we can trap the misnamed and not have to fix everything
				throw new Exception( "trans_id should be type_no in FA_GL!" );
			break;
		}
		return parent::set( $field, $value, $enforce  );
	}
	function init_vars()
	{
		$this->dimension1 = 0;
		$this->dimension2 = 0;
		$this->account = null;    
		$this->person_id = null;    //Person is cust/supplier
		$this->filter = null;    
		$this->amount_min = 0;
		$this->amount_max = 0;
		$this->max_arrs = 128;
		$this->init_arr_arr();
		$this->matchscores['startdate'] = 2;
		$this->matchscores['enddate'] = 3;
		$this->matchscores['account'] = 32;
		$this->matchscores['amount'] = 4;
		$this->matchscores['accountName'] = 32;
		$this->matchscores['transactionCode'] = 64;
		$this->currency = null;
		$this->person_type_id = null;
		$this->person_id = null;
		$this->err_msg = "";
		$this->rate = 0;
		$this->from_incl = true;
		$this->to_incl = true;
		$this->included = 0;
		$this->net_amount = null;
		$this->reg_type = null;
		$this->use_transaction = true;
		$this->nested = false;
	}
	function init_arr_arr()
	{
		for( $x = 0; $x <= $this->max_arrs; $x++ )
		{
			$this->arr_arr[$x] = "";
		}

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
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return add_gl_trans($this->type, $this->type_no, $this->date_, $this->account, $this->dimension, $this->dimension2, $this->memo_,
		$this->amount, $this->currency, $this->person_type_id, $this->person_id, $this->err_msg, $this->rate);
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
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return add_gl_trans_std_cost($this->type, $this->type_no, $this->date_, $this->account, $this->dimension, $this->dimension2, $this->memo_,     $this->amount, $this->person_type_id, $this->person_id, $this->err_msg);
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
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return add_gl_balance($this->type, $this->type_no, $this->date_, $this->amount, $this->person_type_id, $this->person_id);
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
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return get_gl_trans($this->type, $this->type_no);
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
		return get_gl_wo_cost_trans($this->type_no, $this->person_id);
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
		return get_gl_balance_from_to($this->from_date, $this->to_date, $this->account, $this->dimension, $this->dimension2);
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
		return get_gl_trans_from_to($this->from_date, $this->to_date, $this->account, $this->dimension, $this->dimension2);
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
		return get_balance($this->account, $this->dimension, $this->dimension2, $this->from, $this->to, $this->from_incl, $this->to_incl);
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
		return get_budget_trans_from_to($this->from_date, $this->to_date, $this->account, $this->dimension, $this->dimension2);
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
		return get_only_budget_trans_from_to($this->from_date, $this->to_date, $this->account, $this->dimension, $this->dimension2);
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
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return add_gl_tax_details($this->gl_code, $this->type, $this->trans_no, $this->amount, $this->ex_rate, $this->date, $this->memo, $this->included, $this->net_amount );
	}
	/**//********************************************
	*
	*
	* @since 20250311
	*
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function add_ax_details()
	{
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return add_ax_details($this->type, $this->trans_no, $this->tax_id, $this->rate, $this->included,
				$this->amount, $this->net_amount, $this->ex_rate, $this->tran_date, $this->memo, $this->reg_type);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	*
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function get_ax_details()
	{
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return get_ax_details($this->type, $this->trans_no);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	*
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function void_ax_details()
	{
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return void_ax_details($this->type, $this->type_no);
	}
	/**//********************************************
	*
	*
	* @since 20250311
	*
	* @param none uses internal
	* @returns float the amount
	*************************************************/
	function clear_ax_details()
	{
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return clear_ax_details($this->type, $this->type_no);
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
		return write_journal_entries($this->cart, $this->reverse, $this->use_transaction);
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
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return exists_gl_trans($this->type, $this->type_no);
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
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return void_gl_trans($this->type, $this->type_no, $this->nested);
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
		if( ! isset( $this->type ) )
		{
			throw new Exception( "Field not set: type", KSF_VAR_NOT_SET );
		}
		return void_journal_trans($this->type, $this->type_no, $this->use_transaction);
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

	/**//********************************************************************
	* Look for existing GL transactions and score their level of match
	*
	* @param string memo
	* @returns array matching transactions	
	*************************************************************************/
	function find_matching_transactions( $memo = "" )
	{
		$this->get_gl_transactions();
		$this->init_arr_arr();

		while($arr = db_fetch($this->matching_gl_entries) )
		{
			//If there is only 1 matching row, then the transaction was a split transaction ( 1 or more payments, 1 or more expenses)
			//      with this Bank Account /CC being part.
			//If there are 2 matchs, but with +/- Amount, then the Vendor Name should match one entry, and the ACCOUNT should match the other.
			//If there are more than 2 matching, then we need to (manually) choose the correct match.

			//var_dump( $arr );

		/** Examples of MEMOs:
			DEPOSIT;000000000000000;SSQ, SOCIÃÂTÃÂ D'ASSURANCE-VIE						CUstomer payment
			E-TRANSFER 103621405552;Ashley Lacombe;Internet Banking								Customer Payment
			INTERNET TRANSFER 000000254563;Internet Banking									Bank Transfer
			PREAUTHORIZED DEBIT;Electronic Funds Transfer										PCF PAD W/D / PCF PAD W/D
			E-TRANSFER 103630828578;CHRISTOPHER SPARLING;Internet Banking							Customer Payment
			INTEREST;Branch Transaction
			SERVICE CHARGE;Branch Transaction										Bank Fees
			PREAUTHORIZED DEBIT;AIRDRIE Taxes;Electronic Funds Transfer							City Property Taxes.  VENDOR or QE (QE in this case)
			PREAUTHORIZED DEBIT;TD ON-LINE LOANS SYSTEM;Electronic Funds Tra						Car Loan

		**/


			$score = 0;
			$is_invoice = false;

			$score_date = $this->score_matches( "startdate", sql2date( $arr['tran_date'] ) );
			if( $score_date > 0 )
			{
				$score += $score_date;
			}
			else
			{
				$score += $this->score_matches( "enddate", sql2date( $arr['tran_date'] ) );
			}
			$score_acc = $this->score_matches( "Account", $arr['account'] );
			if( $score_acc > 0 ) 
			{
				$score += $score_acc;
			}
			else
			{
				/*	If this was a quick entry import from GnuCash, we have : as chunk dividers.
				 *      One of the chunks is the Merchant.
				 *	      ype 0, trans_no, person_id is blank.  account_name set.  memo_ set.
				 *
				 *      HOWEVER, it will also match FA generated entries.
				 *	      Supplier Invoice => ype 20, type_no is trans number, person_id == supplier
				 */
/*
				if( isset( $gl_vendor ) )
				{
					unset( $gl_vendor );
				}
*/
				switch( $arr['type'] )
				{
					//TODO: Replace these numbers with DEFINED values
					case 0:
					case ST_JOURNAL:
						$gl_vendor = $this->extractVendorFromMemo( $memo );
						break;
					case 20:
					case ST_SUPPINVOICE:
						$is_invoice = true;
						$score -= 8;
					//case ST_SUPPPAYMENT:
					case ST_SUPPAYMENT:
					case ST_SUPPCREDIT:
						$supplier = new fa_suppliers();
						$supplier->set( "supplier_id", $arr['person_id'] );
						$ret = $supplier->getById();
						$gl_vendor = $supplier->get( "supp_name" );     //should it be short name?  supp_ref?
						$arr['supp_name'] = $gl_vendor;
						//display_notification( __LINE__ . print_r( $supplier, true ) );
						break;
					case ST_SALESINVOICE:
						$is_invoice = true;
						$score -= 8;
						break;
					case 12:
					case ST_CUSTPAYMENT:
						$score += 8;
						$gl_customer = $this->extractPersonName( $arr );
						break;
				}	//SWITCH
				if( isset( $gl_vendor ) )
				{
					$score += $this->match_vendor_tokens( $gl_vendor );
					unset( $gl_vendor );
				}
				if( isset( $gl_customer ) )
				{
					$score += $this->match_customer_tokens( $gl_customer );
					unset( $gl_customer );
				}
			}
			if( isset( $this->transactionDC ) )
			{
				switch( $this->transactionDC )
				{
					case 'B':
					case 'C':
						$scoreamount = 1 * $arr['amount'];
					break;
					case 'D':
						$scoreamount = -1 * $arr['amount'];
					break;
				}
				$score += $this->score_matches( "amount", $scoreamount  );
			}
			if( isset( $this->transactionCode ) )
			{
				//Odds are slim of this matching since we seldom set the transactionCode in older imports
				//      //Transaction Code __might__ match depending on what was imported into Gnu way back when...
				//      //References are not guaranteed Unique between FIs.  However, if the rest here matches....
				$score += $this->score_matches( "transactionCode", $arr['reference']  );
			}
			$arr['score'] = $score;
			$arr['is_invoice'] = $is_invoice;
			
			//Insert the results by score.
		 	$ind = 128 - abs($score);
			while( $ind >=0 AND is_array( $this->arr_arr[$ind] ) )
			{
				$ind--;
			}
			if( $ind >= 0 )
				$this->arr_arr[$ind] = $arr;
		}	//WHILE
		//Take the scored array and throw away the empty ones.
		$new_arr = array();
		foreach( $this->arr_arr as $ar )
		{
			if( isset( $ar['tran_date'] ) )
			{
				$new_arr[] = $ar;
			}
		}
		return $new_arr;
	}
	function extractPersonName( $arr )
	{
		if( isset( $arr['person_name'] ) )
		{
			$personName = $arr['person_name'];
			//var_dump( $gl_customer );
		}
		else
		{
			$personName = null;
		}
		return $personName;
	}
	function extractVendorFromMemo( $memo )
	{
		//On previously imported GnuCash entries, we had xx:VENDOR:...:...:...:... in the MEMO field
		//var_dump( $memo );
		$exp = explode( ":", $memo );
		if( isset( $exp[1] ) )
		{
			$gl_vendor = $exp[1];
			//var_dump( $gl_vendor );
		}
		else
		{
			//var_dump( $exp );
			$gl_vendor = "";
		}
		return $gl_vendor;
	}
	/**//*********************************************************
	*
	* This was put into origin.  Why is it not found?
	*
	*************************************************************/
	function countMatchingTokens( $arr1, $arr2 )
	{
       		$result = array_intersect( $arr1, $arr2 );
       		return count( $result );
	}
	/**//*************************************************
	* Take the customer name, and transaction customer, and compare
	*
	* 	Compare token by token and calculate percentage amtch
	*
	* @param string the account name to match
	* @returns float percentage match times score weight
	******************************************************/
	function match_customer_tokens( $gl_customer )
	{
		//var_dump( $this );
	       $gl_customer_tokens = explode( " ", strtoupper( $gl_customer ) );
		$trz_customer_tokens = array();
		//$trz_customer_tokens = explode( " ", strtoupper( $this->accountName ) );
		//var_dump( $this->memo_ );
		if( ! isset( $this->memo_ ) )
		{
			return 0;
		}
		$trz_customer_memo_tokens = explode( ";", $this->memo_  );
		//var_dump( $trz_customer_memo_tokens );
		if( isset( $trz_customer_memo_tokens[1] ) )
		{
			$trz_customer_tokens = explode( " ", strtoupper( $trz_customer_memo_tokens[1] ) );
		}
		$count_gl = count( $gl_customer_tokens );
		$count_trz = count( $trz_customer_tokens );
		//For percentage Name Match, base upon SMALLER number of chunks.
		if( $count_trz <= $count_gl )
		{
			//var_dump( __LINE__ );
			if( $count_trz > 0 )
			{
				//var_dump( __LINE__ );
				$percent = 100 / $count_trz;
			}
			else
			{
				//var_dump( __LINE__ );
				$percent = 0;
			}
		}
		else
		{
			//var_dump( __LINE__ );
			if( $count_gl > 0 )
			{
				//var_dump( __LINE__ );
				$percent = 100 / $count_gl;
			}
			else
			{
				//var_dump( __LINE__ );
				$percent = 0;
			}
		}
		//var_dump( $trz_customer_tokens );
		//print_r( "Arrays: " . $trz_customer_tokens . "//" . $gl_customer_tokens, true );
		$matched = $this->countMatchingTokens( $gl_customer_tokens, $trz_customer_tokens );
		$weight = $this->matchscores['accountName'];
		$score = round( $matched * $percent * $weight / 100, 0, PHP_ROUND_HALF_EVEN );
//		$score += round( $matched * $percent * $weight / 100, 0, PHP_ROUND_HALF_EVEN );
		return $score;
	}
	/**//*************************************************
	* Take the Vendor name, and account vendor, and compare
	*
	* 	Compare token by token and calculate percentage amtch
	*
	* @param string the account name to match
	* @returns float percentage match times score weight
	******************************************************/
	function match_vendor_tokens( $gl_vendor )
	{
	       $gl_vendor_tokens = explode( " ", $gl_vendor );
		$trz_vendor_tokens = explode( " ", $this->accountName );
		$count_gl = count( $gl_vendor_tokens );
		$count_trz = count( $trz_vendor_tokens );
		//For percentage Name Match, base upon SMALLER number of chunks.
		if( $count_trz <= $count_gl )
		{
			//var_dump( __LINE__ );
			if( $count_trz > 0 )
			{
				//var_dump( __LINE__ );
				$percent = 100 / $count_trz;
			}
			else
			{
				//var_dump( __LINE__ );
				$percent = 0;
			}
		}
		else
		{
			//var_dump( __LINE__ );
			if( $count_gl > 0 )
			{
				//var_dump( __LINE__ );
				$percent = 100 / $count_gl;
			}
			else
			{
				//var_dump( __LINE__ );
				$percent = 0;
			}
		}
		$matched = $this->countMatchingTokens( $gl_vendor_tokens, $trz_vendor_tokens );
		$weight = $this->matchscores['accountName'];
		$score = round( $matched * $percent * $weight / 100, 0, PHP_ROUND_HALF_EVEN );
//		$score += round( $matched * $percent * $weight / 100, 0, PHP_ROUND_HALF_EVEN );
		return $score;
	}
	/**//********************************************
	* Get GL transactions
	*
	* @since 20250311
	* @modified 20250401 Merge between FHS and bank_import
	*
	* @param none uses internal
	* @returns MYSQL_RES 
	*************************************************/
	function get_gl_transactions()
	{
		if( ! isset( $this->days_spread ) )
		{
			$this->days_spread = 0;
		}
		if( ! isset( $this->dimension ) )
		{
			$this->dimension = 0;
		}
		if( ! isset( $this->dimension2 ) )
		{
			$this->dimension2 = 0;
		}
		if( isset( $this->startdate ) AND isset( $this->from_date ) )
		{
			throw new Exception( "Both Startdate and from_date set.  Which do we use?" );
		}
		if( isset( $this->startdate ) )
			$startdate = $this->startdate;
		else
			$startdate = $this->from_date;
		if( isset( $this->enddate ) AND isset( $this->to_date ) )
		{
			throw new Exception( "Both enddate and to_date set.  Which do we use?" );
		}
		if( isset( $this->enddate ) )
			$enddate = $this->enddate;
		else
			$enddate = $this->to_date;
		if( isset( $this->amount_min ) AND isset( $this->min_dollar ) )
		{
			throw new Exception( "Both amount_min and min_dollar set.  Which do we use?" );
		}
		if( isset( $this->amount_min ) )
			$amount_min = $this->amount_min;
		else
		if( isset( $this->min_dollar ) )
			$amount_min = $this->min_dollar;
		if( isset( $this->amount_max ) AND isset( $this->max_dollar ) )
		{
			throw new Exception( "Both amount_max and max_dollar set.  Which do we use?" );
		}
		if( isset( $this->amount_max ) )
			$amount_max = $this->amount_max;
		else
		if( isset( $this->max_dollar ) )
			$amount_max = $this->max_dollar;
		if( isset( $this->type_no ) )
			$type_no = $this->type_no;
		else
			$type_no = 0;
		if( isset( $this->account ) )
			$account = $this->account;
		else
			$account = null;
		if( isset( $this->person_id ) )
			$person_id = $this->person_id;
		else
			$person_id = null;
		if( isset( $this->filter_type ) )
			$filter_type = $this->filter_type;
		else
			$filter_type = null;
		$startdate = add_days(       $startdate, -1 * $this->days_spread );
		$enddate = add_days(       $enddate, 1 * $this->days_spread );

		//echo "Searching for $startdate, $enddate, $this->days_spread, $amount_min, $amount_max <br />";

		$this->matching_gl_entries = get_gl_transactions( $startdate, $enddate, $type_no,
					$account, $this->dimension, $this->dimension2, $filter_type,
					$amount_min, $amount_max, $person_id);
		return $this->matching_gl_entries;

	}
	/**//**********************************************************
	* Accept the Bank Import transaction and convert to this class
	*
	* This is used to prep this class to do a search 
	*	Customer E-transfers usually get recorded the day after the "payment date" when recurring invoice, or recorded paid on Quick Invoice
	*
	* @param array Bank Import transaction
	* @param int days lee-way for searching
	* @return none
	****************************************************************/
	function transaction2me( $trz, $spread = 2 )
	{
		$this->set( "amount_min", $trz['transactionAmount'] );
		$this->set( "amount_max", $trz['transactionAmount'] );
		$this->set( "amount", $trz['transactionAmount'] );
		$this->set( "transactionDC", $trz['transactionDC'] );
		$this->set( "days_spread", $spread );
		$this->set( "startdate", $trz['valueTimestamp'] );     //Set converts using sql2date
		$this->set( "enddate", $trz['entryTimestamp'] );       //Set converts using sql2date
		$this->set( "accountName", $trz['accountName'] );
		$this->set( "transactionCode", $trz['transactionCode'] );
	}
}	//CLASS
