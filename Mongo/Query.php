<?php
/**
 * Momentum Workshop
 *
 * @author Corey Frang
 * @category MW
 * @package Epic_Mongo
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 */

/**
 *  Epic_Mongo_Query
 *
 * undocumented
 *
 * @author Corey Frang
 * @category MW
 * @package Epic_Mongo
 * @copyright Copyright (c) 2010 Momentum Workshop, Inc
 * @version $Id: Query.php 430 2010-08-04 20:07:38Z corey $
 */
class Epic_Mongo_Query {
  
  public $query;
  public $sort;
  public $limit = false;
  
  public function __construct($query = array(), $sort = array(), $limit = false)
  {
    $this->query = $query;
    $this->sort = $sort;
    $this->limit = $limit;
  }
  
  
  /**
   * add - Adds another query array
   * 
   *
   * @return Epic_Mongo_Query
   * @author Corey Frang
   **/
  public function add($query)
  {
    if (is_array($query)) {
      $query = new Epic_Mongo_Query($query);
    }
    if (!$query instanceOf Epic_Mongo_Query) {
      trigger_error('Query must be an array', E_USER_ERROR);
    }
    $return = new self(
        $query->query + $this->query, 
        $query->sort + $this->sort, 
        $query->limit ? $query->limit : $this->limit);
    
    return $return;
  }
  
  public function __toString()
  {
    return Zend_Json::encode($this->query);
  }
}