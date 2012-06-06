<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Mongo_DocumentSet extends Shanty_Mongo_DocumentSet
{
	public function setFromArray($array)
	{
		$length = count($array);
		for($x = 0; $x < $length; $x++) {
			$current = $array[$x];
			if(is_array($current)) {
				$new = $this->new();
				$new->setFromArray($current);
				$current = $new;
			}
			$this->setProperty($x, $current);
		}
		$length = count($this);
		for(;$x<$length;$x++) {
			$this->setProperty($x, null);
		}
		return $this;
	}
} // END class Epic_Mongo_DocumentSet extends Shanty_Mongo_DocumentSet