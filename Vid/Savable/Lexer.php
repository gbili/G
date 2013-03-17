<?php
/**
 * This will populate the video entites
 * with the data in the way specified in populateInstance();
 * @author gui
 *
 */
class G_Vid_Savable_Lexer
extends G_Miner_Engine_Lexer_Abstract
{
	
	/**
	 * 
	 * @var unknown_type
	 */
	const TITLE = 2;
	const DATE = 3;
	const TIME_LENGTH = 7;
	const CATEGORY = 10;
	const TAG = 11;
	const SOURCE = 12;
	const IMAGE = 13;
	const HOST_NAME = 14;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_hostName = null;
	
	/**
	 * 
	 * @param unknown_type $instance
	 * @param array $info
	 * @return unknown_type
	 */
	public function populateInstance(G_Db_Buttons_Interface $instance, array $info)
	{
		//force the instance to be of type:
		if (!($instance instanceof G_Vid_Savable)) {
			throw new G_Miner_Engine_Exception('The instance is not an instance of G_Video_Entity_Savable');
		}
		//plug each final result into the current object
		foreach ($info as $entity => $value) {
			G_Echo::l1("LEXER : ");
			if (!is_array($value) && !$this->isPlausibleValue($value)) {
				G_Echo::l1("not plausible value in lexer const : $entity, value : $value \n");
				continue;
			}
			switch ($entity) {
				case self::TITLE;
					$instance->setTitle(new G_Vid_Title_Savable($value));
					//if title is set after image and before shared title, set image fileprefix with it
					if ($instance->hasImage()) {
						G_Echo::l1("setting filerPefix from title\n");
						$instance->getImage()->setFilePrefix($instance->getTitle()->getSlug()->getValue());
					}
					G_Echo::l1("TITLE : {$instance->getTitle()->getSlug()->getValue()}\n");
				break;
				case self::CATEGORY;
					$instance->setCategory(new G_Vid_Category_Savable($value));
					G_Echo::l1("CAT : {$instance->getCategory()->getValue()}\n");
				break;
				//@TODO add tag handler from phrase
				case self::TAG;
					$instance->addTag(new G_Vid_Tag_Savable($value));
					G_Echo::l1("TITLE : {$instance->getTitle()->getSlug()->getValue()}\n");
				break;
				case self::DATE;
					$instance->setDate(new G_Time_AgoToDate($value));
					G_Echo::l1("DATE : $value\n");
				break;
				case self::TIME_LENGTH;
					$t = new G_Time_Length_StrToInt($value);
					$instance->setTimeLength($t->toString());
					G_Echo::l1("TIME_LENGTH : $value\n");
				break;
				case self::SOURCE;
					$instance->setSource(new G_Source_Savable(new G_Url($value)));
					if (null !== $this->_hostName) {
						$instance->getSource()->getHost()->setHFName($this->_hostName);
						G_Echo::l1("HOST_NAME : $this->_hostName\n");
						$this->_hostName = null;
					}
					G_Echo::l1("SOURCE : $value\n");
				break;
				case self::IMAGE;
					$i = new G_Vid_Image_Savable();
					$i->setSourceUrl(new G_Url($value));
					$instance->setImage($i);
					//set image prefix from the title available, shared by pref
					if ($instance->hasTitle()) {
						$i->setFilePrefix($instance->getTitle()->getSlug()->getValue());
					}
					G_Echo::l1("IMAGE : $value\n");
				break;
				case self::HOST_NAME;
					if (null !== $this->_hostName) {
						throw new G_Miner_Engine_Lexer_Exception('You must clear host name before setting it back again, last value : ' . $this->_hostName . ', current : ' . $value);
					}
					$this->_hostName = $value;
					G_Echo::l1("HOST_NAME (hold) : $this->_hostName\n");
				break;
				default;
					throw new G_Miner_Engine_Lexer_Exception('The info array passed to populate instance apears not to be compliant' . print_r($info, true));
				break;
			}
		}
	}
}