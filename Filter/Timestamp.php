<?php
class Epic_Filter_Timestamp implements Zend_Filter_Interface
{
	public $format = 'Y-M-d H:i:s';
	
	public function filter($value) {
		if(!$value) return '';
		if(is_int($value)) {
			return date($this->format, $value);
		}
		$value = $value."";
		if($parsed = strtotime($value)) {
			return $parsed;
		}
		return $value;	
	}
} // END class EpicDb_Filter_Damage extends Zend_Filter_Abstract