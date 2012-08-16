<?php
/**
 * Epic_Form_Message
 *
 * @author Aaron Cox <aaronc@fmanet.org>
 * @param undocumented class
 * @package undocumented class
 **/
class Epic_Form_Mongo_Post extends Epic_Form
{
	protected $_post = null;

	/**
	 * getPost - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function getPost()
	{
		if (!$this->_post instanceOf Epic_Mongo_Document_Post) {
			throw new Exception("Document Passed in is not a Epic_Mongo_Document_Post!");
		}
		return $this->_post;
	}

	/**
	 * setPost($post) - undocumented function
	 *
	 * @return void
	 * @author Aaron Cox <aaronc@fmanet.org>
	 **/
	public function setPost($post)
	{
		$this->_post = $post;
		return $this;
	}

	/**
	 * Checks if the document is new
	 *
	 * @return boolean
	 * @author Corey Frang
	 **/
	public function isNewPost()
	{
		$post = $this->getPost();
		return $post->isNewDocument();
	}

  public function __construct($options = null)
	{
		parent::__construct( $options );
		// postinit - post decorators
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
		$post = $this->getPost();

		$this->addElement("text", "title", array(
			'order' => 75,
			'required' => true,
			'label' => 'Title',
		));

		$this->addElement("markdown", "source", array(
			'order' => 100,
			'required' => true,
			'class' => 'markDownEditor',
			'label' => 'Source',
			'description' => '',
			'cols' => 'auto',
			'rows' => 15,
		));
		
		$this->addElement("checkbox", "published", array(
			'order' => 125,
			'label' => 'Published?',
		));
		
		$this->addElement("tags", "tags", array(
			'order' => 150,
			'required' => true,
			'limit' => 8,
			'label' => 'Tags',
		));

		$this->setButtons(array("save" => "Post"));
		
		$published = false;
		if($post->_published) {
			$published = true;
		}

		$this->setDefaults(array(
			'title' => $post->title,
			'source' => $post->source,
			'published' => $published,
		));
	}
	
	public function save() {
		$post = $this->getPost();
		// Get the User Profile
		$me = Epic_Auth::getInstance()->getProfile();
		// Set the Author
		$post->_author = $me;
		// Set the time created
		if(!$post->_created) {
			$post->_created = time();			
		}
		// Set the last edited
		$post->_edited = time();
		if($this->title) {
			$post->title = $this->title->getValue();			
		}
		if($this->published && $this->published->getValue()) {
			if($post->_published == false) {
				$post->_published = time();				
			}
		} else {
			$post->_published = false;
		}
		// Set the Body and the Source for the Article on the Post
		$post->body = $this->source->getRenderedValue();
		$post->source = $this->source->getValue();
		// Now explode the Source looking for <!--break-->
		$parts = explode("<!--break-->", $this->source->getValue());
		// Set the Source as PART[0] of the exploded part (the 1st half - before break)
		$this->source->setValue($parts[0]);
		// And set the article preview to that part, rendered via Markdown
		$post->preview = $this->source->getRenderedValue();
		// Set the Tags 
		$filter = new Epic_Filter_TagJSON();
		if ($this->tags) {
			$post->tags->setTags($this->tags->getTags(),'tag');
		}
		$save = $post->save();
		return $save;
	}
	public function process($data) {
		$post = $this->getPost();
		if($this->isValid($data)) {
			if($post->isNewDocument()) {
				$post->_created = time();
				// Save the Poster's IP Address for Spam Protection Reasons
				$post->_postedFrom = $_SERVER['REMOTE_ADDR'];
			}
			$this->save();
			return true;
		}
		return false;
	}
	public function render()
	{
		$this->removeDecorator('FloatClear');
		$this->getDecorator('HtmlTag')->setOption('class','r2-post-form')->setOption('id', 'ad-edit');
		return parent::render();
	}	
	
} // END class R2Db_Form_Message
