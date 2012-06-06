<?php
/**
 * Momentum Workshop
 *
 * @author Corey Frang
 * @category MW
 * @package Epic_Mongo
 * @subpackage DocumentSet
 * @copyright Copyright (c) 2011 Momentum Workshop, Inc
 */

/**
 *  Epic_Mongo_DocumentSet_Tags
 *
 * undocumented
 *
 * @author Corey Frang
 * @category MW
 * @package Epic_Mongo
 * @subpackage DocumentSet
 * @copyright Copyright (c) 2011 Momentum Workshop, Inc
 * @version $Id:$
 */
class Epic_Mongo_DocumentSet_Tags extends Epic_Mongo_DocumentSet {

	protected $_defaultReason = "tag";

	protected $_requirements = array(
			'$' => array('Document:Epic_Mongo_Document_Reference'),
			'$.reason' => array('Required'),
		);
		
	
	public function tag( Epic_Mongo_Document $ref, $reason = false, $meta = array() ) {
		if ( $reason === false ) $reason = $this->_defaultReason;
		$tag = $this->new();
		$tag->set( $ref );
		$tag->reason = $reason;
		$tag->refType = $ref->_type;
		foreach($meta as $key => $value) {
			$tag->$key = $value;
		}
		$this->addDocument($tag);
	}

	public function untag($ref = null, $reason = null) {
		if(is_string($ref)) {
			$reason = $ref;
			$ref = null;
		}
		if($ref && (!$ref instanceOf Epic_Mongo_Document)) throw new Exception("Unknown Type");
		if($reason && !is_string($reason)) throw new Exception("Reason isn't a string");

		$query = array();
		if($ref) $query['ref'] = $ref->createReference();
		if($reason) $query['reason'] = $reason;
		foreach ($this as $idx => $tag) {
			if ($ref && $ref->createReference() != $tag->ref->createReference()) {
				continue;
			}
			if ($reason && $tag->reason != $reason) {
				continue;
			}
			$this->setProperty($idx, null);
		}
		return false;
	}
	public function clearTags($reason) {
		foreach($this as $idx => $tag) {
			if ($tag->reason == $reason) {
				$this->setProperty($idx, null);				
			}
		}
	}
	public function hasTag($ref = null, $reason = null) {
		if(is_string($ref)) {
			$reason = $ref;
			$ref = null;
		}
		if($ref && (!$ref instanceOf Epic_Mongo_Document)) throw new Exception("Unknown Type");
		if($reason && !is_string($reason)) throw new Exception("Reason isn't a string");

		$query = array();
		if($ref) $query['ref'] = $ref->createReference();
		if($reason) $query['reason'] = $reason;
		foreach($this->export() as $tag) {
			if($query == $tag) return true;
			if(!$reason && $query['ref'] == $tag->ref) return true;
			if(!$ref && $query['reason'] == $tag->reason) return true;
		}
		return false;
	}
	
	protected function _resolve( $tag ) {
		return $tag->ref;
	}
	public function getTag($reason) {
		foreach($this as $tag) {
			if($tag->reason == $reason) return $this->_resolve( $tag );
		}
		return null;
	}
	
	public function getTags($reason = "all")
	{
		$return = array();
		foreach($this as $tag) {
			if ($reason == "all" || $tag->reason == $reason) {
				$tag = $this->_resolve( $tag );
				if ( $tag ) $return[] = $tag;
			}
		}
		return $return;
	}

	public function setTags($reason, $tags)
	{
		if (is_string($tags)) {
			// swap argument order.
			$tmp = $reason;
			$reason = $tags;
			$tags = $tmp;
			// trigger_error("Switch argument order....", E_USER_NOTICE);
		}
		$refs = array();
		$tagsArray = array();
		foreach ($tags as $idx => $tag) { 
			$refs[$idx] = $tag->createReference();
			$tagsArray[$idx] = $tag;
		}
		$now = array();
		foreach($this as $idx => $tag) {
			if ( ! $tag->ref ) {
				$this->setProperty( $idx, null );
			} else if ($tag->reason == $reason) {
				$now[$idx] = $tag->ref->createReference();
				$test = in_array($now[$idx], $refs);
				if (!$test) {
					$this->setProperty($idx, null);
				}
			}
		}
		foreach ($refs as $idx => $ref) {
			if (!in_array($ref, $now)) {
				$tag = $this->new();
				$tag->ref = $tagsArray[$idx];
				$tag->refType = $tagsArray[$idx]->_type;
				$tag->reason = $reason;
				$this->addDocument($tag);
			}
		}
		return $this;
	}
	
	public function setTag($reason, $tag, $meta = array()) {
		$this->setTags($reason, array($tag), $meta);
		return $this;
	}

}