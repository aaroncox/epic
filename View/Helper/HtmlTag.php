<?php
/**
 *  MW_View_Helper_HtmlTag
 *
 * html tag generic interface
 *
 * @author Corey Frang
 */
class Epic_View_Helper_HtmlTag extends Zend_View_Helper_HtmlElement {
  /**
   * Tag Name
   *
   * @var string
   **/
  protected $_tagName;
  
  /**
   * attributes
   *
   * @var array
   **/
  protected $_tagAttribs;
  
  /**
   * Content
   *
   * @var string|null
   **/
  protected $_content;
  
  /**
   * undocumented function
   *
   * @return void
   * @author Corey Frang
   **/
  public function htmlTag($tagName, $tagAttribs = array(), $content = null, $escape = false)
  {
    if (!is_array($tagAttribs) && $content === null) {
      $content = $tagAttribs;
      $tagAttribs = array();
    }
    $content .= ""; // force it to a string
    $this->_tagName = $tagName;
    $this->_tagAttribs = $tagAttribs;
    $this->_content = "";
    if ($escape) { 
      $this->_content .= $this->view->escape($content);
    } else {
      $this->_content .= $content;
    }
    return $this;
  }
  
  public function render()
  {  
    $tag = "<".$this->_tagName;
    if (is_array($this->_tagAttribs) && count($this->_tagAttribs)) {
      $tag .= $this->_htmlAttribs($this->_tagAttribs);
    }
    if (strlen($this->_content)) {
      $tag .= ">".$this->_content."</".$this->_tagName.">";
    } else {
      $tag .= $this->getClosingBracket();
    }
    return $tag;
  
    
  }
  
  /**
   * spits out an html tag.
   *
   * @return void
   * @author Corey Frang
   **/
  public function __toString()
  {
    return $this->render();
  }
}