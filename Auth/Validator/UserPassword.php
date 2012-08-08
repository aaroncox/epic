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
		// var_dump($this->_params);
		$query = array('username' => $this->_params[0], 'password' => md5($this->_params[1]));
		if(!Epic_Mongo::db('user')->fetchOne($query)) {
			$this->_error(self::DOESNT_MATCH);
    	return false;
		}
		return true;
	}
} // END class