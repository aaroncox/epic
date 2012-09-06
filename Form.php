<?php
class Epic_Form extends Zend_Form {
  /**
   * adds the MW_Form prefix path
   *
   * @return void
   * @author Corey Frang
   */
  public function init()
  {
    parent::init();
		$this->setAttrib('accept-charset', 'utf-8');	
    $this->addPrefixPath("Epic_Form", dirname(__FILE__)."/Form");
    $this->addElementPrefixPath("Epic_Validate", dirname(__FILE__)."/Validate", "validate");
    $this->addElementPrefixPath("Epic_Filter", dirname(__FILE__)."/Filter", "filter");
  }
  
  /**
   * an array of 'buttons' - we want to use this with the ajax helpers eventually
   *
   * array('elementName' => 'elementText');
   * @var array
   **/
  protected $_buttons = array();

  public function setButtons($buttons)
  {
    $this->_buttons = $buttons;
    foreach ($buttons as $name => $label)
    {
      $this->addElement('submit', $name, array(
          'label'=>$label,
          'class'=>$name,
          'decorators'=>array('ViewHelper'),
					'tabindex' => 1000
        ));
    }
    if (!count($this->_buttons)) {
    } else
    $this->addDisplayGroup(array_keys($this->_buttons),'buttons', array(
      'decorators'=>array(
        'FormElements',
        array('HtmlTag', array('tag'=>'div', 'class'=>'buttons')),
      )
    ));
    $this->buttons->setOrder(9999);
    return $this;
  }

  /**
   * Adds a form element named "referrer" and sets its default value to either
   * the 'referrer' param from the request, or the HTTP_REFERER header.
   *
   * @param Zend_Controller_Request_Abstract $request 
   * @return MW_Form
   * @author Corey Frang
   */
  public function trackReferrer(Zend_Controller_Request_Abstract $request)
  {
    $this->addElement('hidden', 'referrer');
    $this->setDefault('referrer', 
      $request->getParam('referrer', 
        $request->getServer('HTTP_REFERER')));
        // HTTP_REFERER not HTTP_REFERRER - grrr HTTP spec misspellings
        
    // use no decorator for the actual form element
		// $decorator = new Zend_Form_Decorator_ViewHelper(array('placement'=>Zend_Form_Decorator_ViewHelper::PREPEND));
		//     $this->referrer->setDecorators($decorator->render()); 

    // use our custom "referrer" decorator to stick the hidden before the <dl>
    // $decorators = $this->getDecorators();
    // $this->clearDecorators();
    // foreach ($decorators as $class => $decorator)
    // {
      // if (substr($class,-5) == '_Form') {
        // $this->addDecorator('Referrer');
        // $added = true;
      // }
			// var_dump(get_class($decorator), $added); 
      // $this->addDecorator($decorator);
    // }
    // if (!$added) $this->addDecorator('Referrer');

    return $this;
  }
  
  /**
   * Returns the referrer field if it exists.
   *
   * @return string | false
   * @param mixed $default The value to return if referrer isn't set
   * @author Corey Frang
   **/
  public function getReferrer($default = false)
  {
    if (!isset($this->referrer)) return $default;
    $val = $this->referrer->getValue();
    if ($val) return $val;
    return $default;
  }

	public function addAttributeFields($doc) {
		foreach($doc->attributes as $name => $data) {
			$this->addElement($data['type'], $name, $data['formElement']);
			$this->$name->setValue($doc->$name);
		}
	}
	
	public function processAttributes($doc) {
		foreach($doc->attributes as $name => $data) {
			$doc->$name = $this->$name->getValue();
		}
		return $doc;
	}
}