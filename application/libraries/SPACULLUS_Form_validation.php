<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ExiteCMS
 *
 * An open source application development framework for PHP 4.3.2 or newer
 * ExiteCMS is based on CodeIgniter, Copyright (c) Ellislab Inc.
 *
 * Extension to the form validation library, to inform the library which
 * object contains your callback methods. Default, the CI superobject is
 * used.
 *
 * @package		ExiteCMS
 * @author		WanWizard
 * @copyright	Copyright (c) 2010, ExiteCMS.org
 * @link		http://www.exitecms.org
 * @since		Version 8.0
 * @filesource
 */

// ---------------------------------------------------------------------

class SPACULLUS_Form_validation extends CI_Form_validation
{
	private $callback;

	// -----------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @return void
	 * @access public
	 */
	function __construct()
	{
		// call the parent constructor
		parent::__construct();

		// set the default callback object to the CI superobject
		$this->callback =& get_instance();
	}

	// -----------------------------------------------------------------

	/**
	 * Inform the form validation library which object contains
	 * the callback methods used in the validation rules.
	 *
	 * Only one object per set of rules can be defined.
	 *
	 * @param	object
	 * @return	void
	 * @access	public
	 */
	function set_callback_object(&$obj = NULL)
	{
		if ( is_object($obj) )
		{
			// set the callback object
			$this->callback =& $obj;

			// make sure the callback object has access to the language library
			if ( ! isset($this->callback->lang) OR ! is_object($this->callback->lang) )
			{
				$this->callback->lang =& $this->CI->lang;
			}
		}
	}

	// -----------------------------------------------------------------

	/**
	 * Executes the Validation routines
	 *
	 * @access	private
	 * @param	array
	 * @param	array
	 * @param	mixed
	 * @param	integer
	 * @return	void
	 */
	function _execute($row, $rules, $postdata = NULL, $cycles = 0)
	{
		// save the current CI object
		$CI = $this->CI;

		// set the CI object to our custom callback object
		$this->CI = $this->callback;

		parent::_execute($row, $rules, $postdata, $cycles);

		// restore the saved CI object
		$this->CI = $CI;
	}
	
	/**
	 * Unique except. Check if a specific value is in use except when the value is attached to a specific row ID
	 *
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	public function unique_exclude($str, $field)
	{
		list($table, $column, $fld, $id) = explode(',', $field, 4);
	 
		$this->CI->form_validation->set_message('unique_exclude', 'The %s that you requested is already in use.');
	 
		$query = $this->CI->db->query("SELECT COUNT(*) AS dupe FROM {$this->CI->db->dbprefix($table)} WHERE {$column} = '$str' AND {$fld} <> {$id}");
		$row = $query->row();
	 
		return ($row->dupe > 0) ? FALSE : TRUE;
	}


	// --------------------------------------------------------------------
	
	/**
	 * Validate URL Address
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	 
	 
	function valid_url($str)
	{
		    
       $this->callback->form_validation->set_message('valid_url', 'The %s field must contain a valid url.');
		
		return ( ! preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $str)) ? FALSE : TRUE;
	} 
	
	
	
	function alpha_space($str)
	{
		$this->callback->form_validation->set_message('alpha_space', 'The %s field must contain only alphabet and space.');
		return ( ! preg_match("/^([-a-z ])+$/i", $str)) ? FALSE : TRUE;
	}
	
	
	function alpha_numeric_space($str)
	{
		$this->callback->form_validation->set_message('alpha_space', 'The %s field must contain only alphabet, numeric and space.');
		return ( ! preg_match("/^([-a-z 0-9])+$/i", $str)) ? FALSE : TRUE;
	}
	
	
	
	
	/**
	 * PCI compliance password
	 *
	 * @access  public
	 * @param   $str
	 * @return  bool
	 */
	 
	public function pci_password($str)
	{
		$special = '!@#$%*-_=+.';
	 
		$this->CI->form_validation->set_message('pci_password', 'For PCI compliance, %s must be between 6 and 99 characters in length, must not contain two consecutively repeating characters, contain at least one upper-case letter, at least one lower-case letter, at least one number, and at least one special character ('.$special.')');
	 
		return (preg_match('/^(?=^.{6,99}$)(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*['.$special.'])(?!.*?(.)\1{1,})^.*$/', $str)) ? TRUE : FALSE;
	}


}