<?php
/**
 * Momentum Workshop
 *
 * @author Corey Frang
 * @category MW
 * @package MW_Dom
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 */
require_once(dirname(__FILE__)."/ext/library/simplehtmldom/simple_html_dom.php");

/**
 *  MW_Dom
 *
 * undocumented
 *
 * @author Corey Frang
 * @category MW
 * @package MW_Dom
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 * @version $Id: Dom.php 514 2010-12-02 23:47:41Z corey $
 */
class Epic_Dom {
  static public function __invoke($string) {
    try {
      $uri = new Zend_Uri($string);
      return file_get_html($string);
    } catch (Zend_Uri_Exception $e) {}
    return str_get_html($string);
  }
  
  static public function string($string)
  {
    return str_get_html($string);
  }
}