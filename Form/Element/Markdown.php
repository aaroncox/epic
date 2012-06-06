<?php
/**
 * Momentum Workshop
 *
 * @author Corey Frang
 * @category MW
 * @package MW_Form
 * @subpackage Element
 * @copyright Copyright (c) 2011 Momentum Workshop, Inc
 */

/**
 *  MW_Form_Element_Markdown
 *
 * undocumented
 *
 * @author Corey Frang
 * @category MW
 * @package MW_Form
 * @subpackage Element
 * @copyright Copyright (c) 2011 Momentum Workshop, Inc
 * @version $Id: Markdown.php 549 2011-05-07 01:59:52Z corey $
 */
class Epic_Form_Element_Markdown extends Zend_Form_Element_Textarea {
	public $helper = 'formMarkdown';

	protected $_editor;
	protected $_purifyOptions = array();

	public function __construct($spec, $options = null)
	{
			if (is_string($spec) && ((null !== $options) && is_string($options))) {
					$options = array('label' => $options);
			}
			if (isset($options['class'])) $options['class'].=' markdown';
			else $options['class'] = 'markdown';
			parent::__construct($spec, $options);
	}

	public function setPurifyOptions(array $array)
	{
		$this->_purifyOptions = $array;
	}

	public function getRenderedValue()
	{
		$source = $this->getValue();
		$markdown = new Epic_Markup_Markdown();
		$html = $markdown->render($source);
		$purifier = new Epic_Filter_HtmlPurifier($this->_purifyOptions);
		return $purifier->filter($html);
	}
} // END class R2Db_Form_Element_Markdown