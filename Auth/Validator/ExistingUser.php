<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Auth_Validator_ExistingUser extends Zend_Validate_Abstract
{
	
    const DOESNT_EXISTS = 'doesntExist';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::DOESNT_EXISTS => "No user exists with this username"
    );

	/**
	 * isValid - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function isValid($value)
	{
		$this->_setValue($value);
		if(!Epic_Mongo::db('user')->fetchOne(array('username' => strtolower($value)))) {
			$this->_error(self::DOESNT_EXISTS);
    	return false;
		}
		return true;
	}
} // END class 