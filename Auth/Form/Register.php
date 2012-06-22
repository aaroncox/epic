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
			'validators' => array(
				new Epic_Auth_Validator_Username()
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
			// DO work!
		}
		return false;
	}} // END class Epic_Form_Login extends Epic_Form