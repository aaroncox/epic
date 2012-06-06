<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Form_Login extends Epic_Form
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
			'order' => 50,
			'required' => true,
			'label' => 'Username'
		));

		$this->addElement("password", "password", array(
			'order' => 60,
			'required' => true,
			'label' => 'Password'
		));

		$this->setButtons(array("save" => "Login"));
	}
	
	public function process($data) {
		if($this->isValid($data)) {
			$auth = new Epic_Auth_Adapter_MongoDb();
			$auth->setIdentityKeyPath('username');
			$auth->setCredentialKeyPath('password');
			$auth->setIdentity($this->username->getValue());
			$auth->setCredential($this->password->getValue());
			$result = Epic_Auth::getInstance()->authenticate($auth);
			if($result->isValid()) {
				return true;
			} else {
				$this->setErrors($result->getMessages());
				return false;
			}
		}
		return false;
	}} // END class Epic_Form_Login extends Epic_Form