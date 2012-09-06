<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Auth_Validator_UserPassword extends Zend_Validate_Abstract
{
	
    const DOESNT_MATCH = 'doesntMatch';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::DOESNT_MATCH => "The supplied login information is incorrect."
    );

	protected $_params = array();

	public function __construct($params) {
		$this->_params = $params;
	}

	/**
	 * isValid - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function isValid($value)
	{
		$this->_setValue($value);
		// Check to see if Username/Password is valid.
		$query1 = array('username' => $this->_params[0], 'password' => md5($this->_params[1]));
		if(Epic_Mongo::db('user')->fetchOne($query1)) {
			return true;
		}
		// Check to see if Email/Password is valid.
		$query2 = array('email' => $this->_params[0], 'password' => md5($this->_params[1]));
		if(Epic_Mongo::db('user')->fetchOne($query2)) {
			return true;
		}
		$this->_error(self::DOESNT_MATCH);
  	return false;
	}
} // END class