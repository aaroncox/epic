<?php
class Epic_Form_Mongo_User extends Epic_Form
{
	protected $_user = null;

	/**
	 * getUser - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function getUser()
	{
		if (!$this->_user instanceOf Epic_Mongo_Document_User) {
			var_dump($this->_user); exit;
			throw new Exception("Document Passed in is not a Epic_Mongo_Document_User!");
		}
		return $this->_user;
	}

	/**
	 * setUser($User) - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function setUser($user)
	{
		$this->_user = $user;
		return $this;
	}

	/**
	 * Checks if the document is new
	 *
	 * @return boolean
	 * @author Corey Frang
	 **/
	public function isNewUser()
	{
		$user = $this->getUser();
		return $this->_user->isNewDocument();
	}

  public function __construct($options = null)
	{
		parent::__construct( $options );
		// Userinit - User decorators
	}

	/**
	 * init - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function init()
	{
		parent::init();
		$user = $this->getUser();

		$this->addElement("text", "name", array(
			'order' => 75,
			'required' => true,
			'label' => 'Name',
		));
		
		$this->addElement("text", "username", array(
			'order' => 80,
			'required' => true,
			'label' => 'Username',
		));
		
		$this->addElement("text", "email", array(
			'order' => 85,
			'required' => true,
			'label' => 'Email',
		));
		
		$this->addElement("select", "access", array(
			'order' => 90,
			'label' => 'Access',
			'multiOptions' => array(
				'user' => 'user',
				'admin' => 'admin'
			)
		));
		
		$this->addElement("markdown", "source", array(
			'order' => 100,
			'class' => 'markDownEditor',
			'label' => 'Biography',
			'cols' => 'auto',
			'rows' => 15,
		));
		
		$this->addElement("password", "password1", array(
			'order' => 151,
			'required' => false,
			'label' => 'Password',
		));
	
		$this->addElement("password", "password2", array(
			'order' => 152,
			'required' => false,
			'label' => 'Password (Confirm)',
			'description' => 'You only need to enter the password if you wish to change it.',
		));
		
		// If this is a New User, modify the form slightly
		if($user->isNewDocument()) {
			$this->password1->setRequired('true');	// Require password1
			$this->password2->setRequired('true');	// Require password2
			$this->password2->setDescription(null);			// Remove the notice about the password being optional
		} else {
			// If this is not a new user, set the defaults
			$this->setDefaults(array(
				'name' => $user->name,
				'username' => $user->username,
				'access' => $user->_access,
				'email' => $user->email,
				'source' => $user->source,
			));
		}
		// Create the Save Button
		$this->setButtons(array("save" => "Save"));		
	}
	
	public function save() {
		$user = $this->getUser();
		$me = Epic_Auth::getInstance()->getProfile();

		$user->name = $this->name->getValue();
		$user->username = $this->username->getValue();
		$user->_access = $this->access->getValue();
		$user->email = $this->email->getValue();

		$user->description = $this->source->getRenderedValue();
		$user->source = $this->source->getValue();

		if($this->password1->getValue()) {
			$user->password = md5($this->password1->getValue());			
		}

		$save = $user->save();
		return $save;
	}
	public function process($data) {
		$user = $this->getUser();
		if($this->isValid($data)) {
			if($this->password1->getValue() && $this->password2->getValue() && $this->password1->getValue() !== $this->password2->getValue()) {
				$this->password1->addError("Passwords do not match.");
				return false;
			}
			if($user->isNewDocument()) {
				$user->_created = time();
				$user->_touched = time();
			} else {
				$user->_touched = time();
			}
			$this->save();
			return true;
		}
		return false;
	}
	public function render()
	{
		$this->removeDecorator('FloatClear');
		$this->getDecorator('HtmlTag')->setOption('class','r2-User-form')->setOption('id', 'ad-edit');
		return parent::render();
	}	
	
} // END class R2Db_Form_Message
