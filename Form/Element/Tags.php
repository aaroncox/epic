<?php
class Epic_Form_Element_Tags extends Zend_Form_Element_Hidden {
	public $helper = 'formTags';

	public function __construct($spec, $options = null)
	{
		if (is_string($spec) && ((null !== $options) && is_string($options))) {
			$options = array('label' => $options);
		}
		if (isset($options['class'])) $options['class'].=' epic-tags';
		else $options['class'] = 'epic-tags';
		
		if (!empty($options['limit'])) {
			$limit = (int) $options['limit'];
		} else {
			$limit = false;
		}
		
		if (!empty($options['recordType'])) {
			$type = $options['recordType'];
		} else {
			$type = false;
		}
		$filter = new Epic_Filter_TagJSON(array('type' => $type, 'limit' => $limit));
		if (isset($options['filters'])) {
			$options['filters']["TagJSON"] = array('type'=>$type);
		} else {
			$options['filters'] = array("TagJSON" => $filter);
		}
		$hidden = parent::__construct($spec, $options);
	}
	
	public function getTags() {
		$value = $this->getValue();
		$filter = $this->getFilter("TagJSON");
		return $filter->toArray($this->getValue());
	}

}