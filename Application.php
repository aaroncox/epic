<?php
/**
 * @see Zend_Application
 */
require_once "Zend/Application.php";


 /**
  * Epic Application
  *
  * Ensures that the Epic_ namespace is in the autoloader before going into bootstrap.
  */
class Epic_Application extends Zend_Application {

  public function __construct($environment, $options = null)  
  {
    require_once 'Zend/Loader/Autoloader.php';
    $this->_autoloader = Zend_Loader_Autoloader::getInstance();
    $this->_autoloader->registerNamespace('Epic_');
    parent::__construct($environment, $options);
  }
  
  public function setOptions(array $options)
  {
    if (isset($options["registry"]))
    {
      foreach($options["registry"] as $k=>$v)
        Zend_Registry::getInstance()->$k = $v;
    }
    return parent::setOptions($options);
  }
  
}