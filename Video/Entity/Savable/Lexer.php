<?php
/**
 * This will populate the video entites
 * with the data in the way specified in populateInstance();
 * @author gui
 *
 */
class G_Video_Entity_Savable_Lexer
extends G_Miner_Engine_Lexer_Abstract
{
	
	/**
	 * 
	 * @var unknown_type
	 */
	const TITLE = 2;
	const DATE = 3;
	const ACTOR = 4;
	const DIRECTOR = 5;
	const PRODUCER = 6;
	const TIME_LENGTH = 7;
	const COUNTRY = 8;
	const LANG = 9;
	const GENRE = 10;
	const TITLE_ORIGINAL = 11;
	const SOURCE = 12;
	const IMAGE = 13;
	const SYNOPSIS =14;
	
	/**
	 * 
	 * @param unknown_type $instance
	 * @param array $info
	 * @return unknown_type
	 */
	public function populateInstance(G_Db_Buttons_Interface $instance, array $info)
	{
		//force the instance to be of type:
		if (!($instance instanceof G_Video_Entity_Savable)) {
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
					$instance->setTitle(new G_Video_Entity_Title_Savable($value));
					//if title is set after image and before shared title, set image fileprefix with it
					if ($instance->getSharedInfo()->hasImage() && !$instance->getSharedInfo()->hasOriginalTitle()) {
						G_Echo::l1("setting filerPefix from title\n");
						$instance->getSharedInfo()->getImage()->setFilePrefix($instance->getTitle()->getSlug()->getValue());
						$instance->getSharedInfo()->setAsUsingRecycledImage();
					}
					G_Echo::l1("TITLE : {$instance->getTitle()->getSlug()->getValue()}\n");
					break;
				case self::DATE;
					if (!$this->isPlausibleValue($value)) {
						break;
					}
					$instance->getSharedInfo()->setDate(new G_Date_Year($value));
					G_Echo::l1("DATE : $value\n");
					break;
				case self::ACTOR;
					if (is_array($value)) {
						foreach ($value as $v) {
							if ($this->isPlausibleValue($v)) {
								$p = new G_Participant_Savable(new G_Participant_Role_Savable('Actor'), new G_MIE_Savable($v), $instance->getSharedInfo());
								$instance->getSharedInfo()->addParticipant($p);
							}
						} 
					} else {
						$p = new G_Participant_Savable(new G_Participant_Role_Savable('Actor'), new G_MIE_Savable($value), $instance->getSharedInfo());
						$instance->getSharedInfo()->addParticipant($p);
					}
					G_Echo::l1("ACTOR : $value\n");
					break;
				case self::DIRECTOR;
					if (is_array($value)) {
						foreach ($value as $v) {
							if ($this->isPlausibleValue($v)) {
								$p = new G_Participant_Savable(new G_Participant_Role_Savable('Director'), new G_MIE_Savable($v), $instance->getSharedInfo());
								$instance->getSharedInfo()->addParticipant($p);
							}
						} 
					} else {
						$p = new G_Participant_Savable(new G_Participant_Role_Savable('Director'), new G_MIE_Savable($value), $instance->getSharedInfo());
						$instance->getSharedInfo()->addParticipant($p);
					}
					G_Echo::l1("DIRECTOR : $value\n");
					break;
				case self::PRODUCER;
					if (is_array($value)) {
						foreach ($value as $v) {
							if ($this->isPlausibleValue($v)) {
								$p = new G_Participant_Savable(new G_Participant_Role_Savable('Producer'), new G_MIE_Savable($v), $instance->getSharedInfo());
								$instance->getSharedInfo()->addParticipant($p);
							}
						}
					} else {
						$p = new G_Participant_Savable(new G_Participant_Role_Savable('Producer'), new G_MIE_Savable($value), $instance->getSharedInfo());
						$instance->getSharedInfo()->addParticipant($p);
					}
					G_Echo::l1("PRODUCER : $value\n");
					break;
				case self::TIME_LENGTH;
					
					break;
				case self::COUNTRY;
					$instance->getSharedInfo()->setCountry(new G_Country($value));
					if ($instance->getSharedInfo()->getCountry()->isNormalized()) {
						$instance->setLang(current($instance->getSharedInfo()->getCountry()->getLangs()));
					}
					G_Echo::l1("COUNTRY : $value\n");
					break;
				case self::LANG;
					$instance->setLangISO(new G_International_LangISO($value));
					G_Echo::l1("LANG : $value\n");
					break;
				case self::GENRE;
					$instance->getSharedInfo()->addGenre(new G_Video_Entity_Genre_Savable($value));
					G_Echo::l1("GENRE : $value\n");
					break;
				case self::TITLE_ORIGINAL;
					$instance->getSharedInfo()->setOriginalTitle(new G_Video_Entity_Title_Savable($value));
					//if the original title is set after image, set or change image file prefix with it
					if ($instance->getSharedInfo()->hasImage()) {
						//don't set prefix if it was already set with the same value
						if ($instance->hasTitle() && 
						   ($instance->getTitle()->getSlug()->getValue() === $instance->getSharedInfo()->getOriginalTitle()->getSlug()->getValue())) {
							G_Echo::l1("not setting filerPefix from original title because it is same as title\n");
						   	break;
						}
						G_Echo::l1("setting filerPefix from original title\n");
						$instance->getSharedInfo()->getImage()->setFilePrefix($instance->getSharedInfo()->getOriginalTitle()->getSlug()->getValue());
					}
					G_Echo::l1("TITLE_ORIGINAL : " . $instance->getSharedInfo()->getOriginalTitle()->getSlug()->getValue() . "\n");
					break;
				case self::SOURCE;
					$instance->addSource(new G_Source($value));
					G_Echo::l1("SOURCE : $value\n");
					break;
				case self::IMAGE;
					$i = new G_Image_Savable();
					$i->setSourceUrl(new G_Url($value));
					$instance->getSharedInfo()->setImage($i);
					//set image prefix from the title available, shared by pref
					if ($instance->getSharedInfo()->hasOriginalTitle()) {
						$i->setFilePrefix($instance->getSharedInfo()->getOriginalTitle()->getSlug()->getValue());
					} else if ($instance->hasTitle()) {
						$i->setFilePrefix($instance->getTitle()->getSlug()->getValue());
						$instance->getSharedInfo()->setAsUsingRecycledImage();
					}
					G_Echo::l1("IMAGE : $value\n");
					break;
				case self::SYNOPSIS;
					$instance->setSynopsis($value);
					G_Echo::l1("SYNOPSIS : $value\n");
					break;
				default;
					throw new G_Video_Entity_Lexer_Exception('The info array passed to populate instance apears not to be compliant');
					break;
			}
		}
	}
}