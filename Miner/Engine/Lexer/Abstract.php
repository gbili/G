<?php
/**
 * The lexer is a data gateway, it maps the 
 * data dumped from the web, which is associated
 * with a lexer subclass constant to the processing
 * of that data. It is like a postman i.e. it
 * recieves some letters (data) with some addresses
 * (_lexer constants_) and it only gives the letter
 * to the person (_method definition_) it is intended to.
 * 
 * Note that the _method definitions_ must be implemented
 * by you in a lexer subclass in the populateInstance($instance, $info) 
 * method. You are to implement a switch() with your _lexer constants_ 
 * ($info array keys), and define how the _data_ ($info) must be handled
 * for the $instance all passed as arguments.
 * 
 * Each lexer should have a dictionary implemented
 * as CONSTANTS
 * 
 * populateInstance(:instance, array(:term=>:data)) will take a
 * G_Db_Buttons_Interface :instance and for each :term in the
 * dictionary, it will know how to handle the :data associated to
 * the :term (in $info array) and will populate the :instance
 * following the rules defined in (for example) a switch for each :term
 * 
 * isInDictionary(:term) helps in determinig whether populateInstance will
 * successfully handle the data passed along the :term
 * 
 * Lets say you want to dump videos, you will create
 * a class that will define the video attributes which
 * in turn replicate the storage i.e. the database. And
 * on every step the Engine spits some data to populate
 * the video class instance, the lexer subclass is then
 * called to map that information to the video class.
 * Remember all that mapping is defined in populateInstance()
 * by yourself.
 * 
 * @important you cannot call populateInstance($instance, $info)
 * method if you do not pass the $instance. However if you still
 * want to store some information that an action has spit and the
 * instance is not yet available, because the nIGP is further down
 * in the parsing tree (dumping process) use the lexer bucket. 
 * Ex. in videos: you get the category from the top of the tree and
 * then you have a list of videos under that category. You will only
 * be able to create a video instance once you have something unique
 * to differenciate it from the others (i.e. you have a video name as
 * opposed to a categroy name which is shared among all videos and is
 * not sufficient to make a video unique). Without an instance the lexer
 * would not be able to pass the category name to the video, that is why
 * the lexer bucket has been created. You can store the category name
 * inside the bucket and then once you extract the video name, from
 * within the nIGP, you will have a video instance to which the
 * engine will automatically inject the bucket contents that you will
 * have filled from the category extracting action with the lexer method
 * storeInBucket())
 * 
 * @see G_Miner_Engine nIGP (new instance generating point)
 * @see G_Miner_Engine for implementation of populateInstance() and bucket
 * 
 * @author gui
 *
 */
abstract class G_Miner_Engine_Lexer_Abstract
{

	/**
	 * This allows to store data that is sahred among all instances
	 * @see storeInBucket()
	 * 
	 * @var unknown_type
	 */
	private $_bucket = array();
	
	/**
	 * Overrides existing content
	 * 
	 * @param array $dumpingData key value pairs of data
	 * that must be retrieved later
	 * @return unknown_type
	 */
	public function storeInBucket(array $dumpingData)
	{
		foreach ($dumpingData as $entity => $value) {
			$this->_bucket[$entity] = $value;
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function isEmptyBucket()
	{
		return empty($this->_bucket);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getBucketContent()
	{
		return $this->_bucket;
	}
	
	/**
	 * 
	 * @param G_Db_Buttons_Interface $instance
	 * @return unknown_type
	 */
	public function injectBucketContent(G_Db_Buttons_Interface $instance)
	{
		if (false === $this->isEmptyBucket()) {
			$this->populateInstance($instance, $this->getBucketContent());
		}
	}
	
	/**
	 * An object and an array of data will be passed so
	 * that the implemented function can determine what to do
	 * and how to handle the data
	 * 
	 * @param unknown_type $instance the instance that will be populated
	 * @param array $info array(:term=>:data)) the data that will be used to populate the instance
	 * @return unknown_type
	 */
	abstract public function populateInstance(G_Db_Buttons_Interface $instance, array $info);
	
	/**
	 * Return true or false depending on whether the term is supported or not
	 * i.e. does populateInstance() know how to handle the data for that term
	 * 
	 * @param unknown_type $term
	 * @return unknown_type
	 */
	public function isInDictionary($term)
	{
		$reflection = new ReflectionClass($this);
		return (in_array($term, $reflection->getConstants()));
	}
	
	/**
	 * 
	 * @param unknown_type $v
	 * @return unknown_type
	 */
	public function isPlausibleValue($v)
	{
		return (is_string($v) && mb_strlen($v) > 0);
	}
}