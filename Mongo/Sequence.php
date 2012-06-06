<?php
/**
 * Momentum Workshop
 *
 * @author Corey Frang
 * @category MW
 * @package Epic_Mongo
 * @copyright Copyright (c) 2011 Momentum Workshop, Inc
 */

/**
 *  Epic_Mongo_Sequence
 *
 * undocumented
 *
 * @author Corey Frang
 * @category MW
 * @package Epic_Mongo
 * @copyright Copyright (c) 2011 Momentum Workshop, Inc
 * @version $Id: Sequence.php 547 2011-04-10 08:52:31Z corey $
 */
class Epic_Mongo_Sequence extends Epic_Mongo_Document {
  protected static $_collectionName = 'sequences';
  
  public static function getNextSequence($sequenceId) {
    $update = array(
  		'findandmodify' => static::getCollectionName(),
  		'query' => array('id' => $sequenceId),
  		'update' => array('$inc' => array('sequence' => 1)),
  		'new' => TRUE,
  		'upsert' => TRUE
  	);
  	$result = self::getMongoDb()->command($update);
  	if($result['ok'] == true) {
  	  return $result['value']['sequence'];				
  	}
  	return false;
  }

	public static function setNextSequence($sequenceId, $value) {
		$update = array(
  		'findandmodify' => static::getCollectionName(),
  		'query' => array('id' => $sequenceId),
  		'update' => array('$set' => array('sequence' => $value)),
  		'new' => TRUE,
  		'upsert' => TRUE
  	);
  	$result = self::getMongoDb()->command($update);
  	if($result['ok'] == true) {
  	  return $result['value']['sequence'];				
  	}
  	return false;
	}
}