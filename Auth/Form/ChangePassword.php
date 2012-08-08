<?php
/**
 * Momentum Workshop
 *
 * @author Corey Frang
 * @category MW
 * @package MW_Auth
 * @subpackage Form
 * @copyright Copyright (c) 2009 Momentum Workshop, Inc
 */

/**
 *  MW_Auth_Form_ChangePassword
 *
 * undocumented
 *
 * @author Corey Frang
 * @category MW
 * @package MW_Auth
 * @subpackage Form
 * @copyright Copyright (c) 2009 Momentum Workshop, Inc
 * @version $Id: ChangePassword.php 464 2010-09-17 22:40:43Z corey $
 */
class Epic_Auth_Form_ChangePassword extends Epic_Form {
	
	protected $_user = null;
	
	public function setUser($user) {
		$this->_user = $user;
	}
	
	public function getuser() {
		return $this->_user;
	}
	
	
  public function init()
  {
    parent::init();
    $this->addElement('password', 'current_password', array(
      'autocomplete'=>'off',
      'label'=>'Current Password',
			'required'=> true,
    ));

    $this->addElement('password', 'new_password', array(
      'autocomplete'=>'off',
      'label'=>'New Password',
			'required' => true,
    ));
    $this->addElement('password', 'new_password2', array(
			'autocomplete'=>'off',
      'label'=>'Verify Password', 
			'required' => true
    ));
    $this->setButtons(array('save'=>'Save'));
  }
  
  public function process(array $data)
  {
	  $this->new_password2->setRequired(true)->setValidators(array(new Epic_Auth_Validator_IdenticalValidator($data['new_password'])));  
		$user = $this->getUser();
		// var_dump($this->getElement('current_password'));
		if($current = $this->getElement('current_password')) {
			// var_dump("current", $this->current_password->getValue());
			$this->current_password->setValidators(array(new Epic_Auth_Validator_UserPassword(array($user->username, $data['current_password']))));
			// echo "compare against password"; exit;
		}
		// exit;
		if($this->isValid($data))	{
			// New Passwords
			$user->password = md5($this->new_password->getValue());
			unset($user->resetHash);
			unset($user->resetDate);
			return $user->save();
		} 
  }
}