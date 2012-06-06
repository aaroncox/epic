<?php
/**
 *  MW_Filter_Slug
 *
 * HTML Slug
 *
 * @author Corey Frang
 */
class Epic_Filter_Slug implements Zend_Filter_Interface {
  public function filter($text)
  {
      $regex = array(
                '/ä/' => 'ae',
                '/ö/' => 'oe',
                '/ü/' => 'ue',
                '/Ä/' => 'Ae',
                '/Ö/' => 'Oe',
                '/Ü/' => 'Ue',
                '/ß/' => 'ss',
             '/\'s/i' => 's',
      '/[^a-z0-9]+/i' => '-', 
               '/-+/' => '-', 
               '/^-/' => '', 
               '/-$/' => '',
      );
      return preg_replace(array_keys($regex), array_values($regex), mb_strtolower($text));
  }
}