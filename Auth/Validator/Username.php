<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Auth_Validator_Username extends Zend_Validate_Abstract
{
	
    const ALREADY_EXISTS = 'alreadyExists';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::ALREADY_EXISTS => "A user with this username already exists."
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
		if(Epic_Mongo::db('user')->fetchOne(array('username' => $value))) {
			$this->_error(self::ALREADY_EXISTS);
    	return false;
		}
		return true;
	}

} // END class Epic_Auth_Validator_Username