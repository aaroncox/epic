<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Auth_Form_ResetPassword extends Epic_Form
{
	public function init() {
		parent::init();
		$this->addElement('text', 'email', array(
			'label'=>'Email Address',
			'filters' => array(
			 'StringtoLower'
			),
			'required' => true,
		));
		$this->setButtons(array('save'=>'Reset Password'));
	}
	public function process($data) {
		if($this->isValid($data)) {
			if($user = Epic_Mongo::db('user')->fetchOne(array('email' => $this->email->getValue()))) {
				$user->resetDate = time();
				$user->resetHash = uniqid('reset', true);
				return $user->save();
			}
		}
		return false;
	}
} // END class Epic_Auth_Form_ResetPassword extends Epic_Form
