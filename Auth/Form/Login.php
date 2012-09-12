<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Auth_Form_Login extends Epic_Form
{
	/**
	 * init - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function init()
	{
		
		$this->addElement("text", "username", array(
			'required' => true,
			'label' => 'Username/Email',
			'validators' => array(
				new Epic_Auth_Validator_ExistingUser()
			),
			'filters' => array(
			 'StringtoLower'
			),
		));

		$this->addElement("password", "password", array(
			'required' => true,
			'label' => 'Password',
		));

		$this->setButtons(array("save" => "Login"));
	}
	
	public function process($data) {
		$this->password->setValidators(array(new Epic_Auth_Validator_UserPassword(array(strtolower($data['username']), $data['password']))));
		if($this->isValid($data)) {
			$auth = new Epic_Auth_Adapter_MongoDb();
			$auth->setIdentityKeyPath('username');
			$auth->setCredentialKeyPath('password');
			$username = strtolower($this->username->getValue());
			$auth->setIdentity($username);
			$auth->setCredential($this->password->getValue());
			$result = Epic_Auth::getInstance()->authenticate($auth);
			// Attempt to Login via Username
			if($result->isValid()) {
				return true;
			} else {
				// Failed? Try logging in via Email Address
				$auth->setIdentityKeyPath('email');
				$result = Epic_Auth::getInstance()->authenticate($auth);
				if($result->isValid()) {
					return true;
				} else {
					// Still bad? Fail out
					return $this->setErrors($result->getMessages());
				}
			}
		}
		return false;
	}
} // END class Epic_Form_Login extends Epic_Form