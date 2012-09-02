<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Auth_Form_Register extends Epic_Form
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
			'label' => 'Username',
			'filters'    => array('StringTrim'),
			'validators' => array(
				new Epic_Auth_Validator_Username(),
			),
		));

		$this->addElement("password", "password1", array(
			'required' => true,
			'label' => 'Password',
			'filters'    => array('StringTrim'),
			'validators' => array(
					'NotEmpty',
					array('StringLength', false, array(6))
			),
		));

		$this->addElement("password", "password2", array(
			'required' => true,
			'label' => 'Password (Again)',
			'filters'    => array('StringTrim'),
			'validators' => array(
					'NotEmpty',
					array('StringLength', false, array(6))
			),
		));
		
		$this->addElement("text", "email", array(
			'required' => true,
			'label' => 'Email',
			'validators' => array(
				new Zend_Validate_EmailAddress(),
				new Epic_Auth_Validator_Email(),
			),
		));

		$this->setButtons(array("save" => "Register"));
	}
	
	public function process($data) {
		$this->password2->setRequired(true)->setValidators(array(new Epic_Auth_Validator_IdenticalValidator($data['password1'])));
		if($this->isValid($data)) {
			$user = Epic_Mongo::newDoc('user');
			$user->username = strtolower($this->username->getValue());
			$user->password = md5($this->password1->getValue());
			$user->email = $this->email->getValue();
			$user->_registered = time();
			$user->save();
			$auth = new Epic_Auth_Adapter_MongoDb();
			$auth->setIdentityKeyPath('username');
			$auth->setCredentialKeyPath('password');
			$auth->setIdentity(strtolower($this->username->getValue()));
			$auth->setCredential($this->password1->getValue());
			$result = Epic_Auth::getInstance()->authenticate($auth);
			if($result->isValid()) {
				return true;
			} else {
				return $this->setErrors($result->getMessages());
			}
		}
		return false;
	}} // END class Epic_Form_Login extends Epic_Form