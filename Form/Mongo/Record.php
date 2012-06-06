<?php
class Epic_Form_Mongo_Record extends Epic_Form
{
	protected $_record = null;

	/**
	 * getRecord - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function getRecord($type = 'record')
	{
		if (!$this->_record instanceOf Epic_Mongo_Document_Record) {
			$this->_record = Epic_Mongo::newDoc($type);			
			// throw new Exception("Document Passed in is not a Epic_Mongo_Document_Record!");
		}
		return $this->_record;
	}

	/**
	 * setRecord($Record) - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function setRecord($record)
	{
		$this->_record = $record;
		return $this;
	}

	/**
	 * Checks if the document is new
	 *
	 * @return boolean
	 * @author Corey Frang
	 **/
	public function isNewRecord()
	{
		return ($this->_record) ? false : true;
	}

  public function __construct($options = null)
	{
		parent::__construct( $options );
		// Recordinit - Record decorators
	}

	/**
	 * init - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function init()
	{
		parent::init();

		$this->addElement("text", "name", array(
			'order' => 75,
			'required' => true,
			'label' => 'Name',
		));

		$this->addElement("select", "type", array(
			'order' => 80,
			'required' => true,
			'label' => 'Type',
			'multiOptions' => Epic_Mongo::dataTypes(Epic_Mongo::newDoc('record')),
		));

		if(!$this->isNewRecord()) {
			$record = $this->getRecord();
			$this->addElement("markdown", "description", array(
				'order' => 100,
				'required' => true,
				'class' => 'markDownEditor',
				'label' => 'Description',
				'description' => '',
				'cols' => 'auto',
				'rows' => 15,
			));
			
			$this->addElement("tags", "tags", array(
				'order' => 150,
				'required' => true,
				'limit' => 8,
				'label' => 'Tags',
			));			
			$this->setDefaults(array(
				'description' => $record->source,
				'tags' => $record->tags->getTags('tag'),
				'name' => $record->name,
				'type' => $record->_type,
			));
			$this->addAttributeFields($record);
		} 
		
		
		$this->setButtons(array("save" => "Save"));		
	}
	
	public function save() {
		$record = $this->getRecord($this->type->getValue());
		$me = Epic_Auth::getInstance()->getProfile();

		$record->name = $this->name->getValue();
		$record->_type = $this->type->getValue();
		
		if(!$record->isNewDocument()) {
			$record->source = $this->description->getValue();
			$record->description = $this->description->getRenderedValue();

			$filter = new Epic_Filter_TagJSON();

			if ($this->tags) {
				$record->tags->setTags($this->tags->getTags(), 'tag');
			}			
			$record->_touched = time();
			$record = $this->processAttributes($record);
		} else {
			$record->_created = time();			
			$record->save();
			$filter = new Epic_Filter_Slug();
			$this->addElement("hidden", "referrer", array(
				'value' => '/'.$record->_type."/".$record->id."/".$filter->filter($record->name)."/edit",
			));			
		}
		
		$save = $record->save();
		return $save;
	}
	public function process($data) {
		// $record = $this->getRecord();
		if($this->isValid($data)) {
			$this->save();
			return true;
		}
		return false;
	}
	public function render()
	{
		$this->removeDecorator('FloatClear');
		$this->getDecorator('HtmlTag')->setOption('class','r2-Record-form')->setOption('id', 'ad-edit');
		return parent::render();
	}	
	
} // END class R2Db_Form_Message
