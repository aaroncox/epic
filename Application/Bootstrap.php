<?php

/**
 * @see Zend_Application_Bootstrap_Bootstrap
 */
require_once 'Zend/Application/Bootstrap/Bootstrap.php'; 

/**
 *  Bootstrapper
 *
 * MW Bootstrapper
 *
 * @author Corey Frang
 * @category MW
 * @package MW_Application
 * @copyright Copyright (c) 2009 Momentum Workshop, Inc.
 * @version $Id: Bootstrap.php 519 2010-12-16 17:15:55Z corey $
 */
class Epic_Application_Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
  
 /**
  * Get the plugin loader for resources - makes sure to add prefix path to the loader
  *
  * @return Zend_Loader_PluginLoader_Interface
  */
  public function getPluginLoader()
  {
    if ($this->_pluginLoader === null) {
      parent::getPluginLoader();
      $this->_pluginLoader->addPrefixPath('Epic_Application_Resource', dirname(__FILE__).'/Resource/');
    }
    return $this->_pluginLoader;
  }

  protected function _bootstrap($resource=null)
  {
    parent::_bootstrap($resource);
  }

  public function _initMagicQuotes()
  {
    if (get_magic_quotes_gpc()) {
      function strip_quotes(&$var) {
        if (is_array($var)) {
          array_walk($var, 'strip_quotes');
        } else {
          $var = stripslashes($var);
        }
      }
    
      // Handle GPC
      foreach (array('GET','POST','COOKIE') as $v) {
        if (!empty(${"_".$v})) {
          array_walk(${"_".$v}, 'strip_quotes');
        }
      }
      ini_set("magic_quotes_gpc", 0);
    }
  }
  
  // public function _initMwView()
  // {
  //   // $this->registerPluginResource('view');
  //   $this->bootstrap('view');
  //   $view = $this->getResource('view');
  //   $view->doctype('XHTML1_STRICT');
  //   $view->addHelperPath(APPLICATION_PATH."/../library/Epic/View/Helper", "Epic_View_Helper_");
  // }
  
  // public function _initCacheManagerRegistry()
  // {
  //   try {
  //     $this->bootstrap('cachemanager');
  //     Zend_Registry::getInstance()->set('CacheManager',$this->getResource('cachemanager'));
  //   } catch(Exception $e) {
  //     // trigger_error($e->getMessage(), E_USER_NOTICE);
  //   }
  // }
  
  // public function _initTwitter()
  // {
  //   $config = $this->getOptions();
  //   
  //   if (isset($config['twitter']))
  //   {
  //   
  //     $registry = Zend_Registry::getInstance();
  // 
  //     $registry->twitter = $config['twitter'];
  //     
  //   }
  // }

}