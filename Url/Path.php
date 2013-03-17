<?php
class G_Url_Path
extends G_Regex_Encapsulator_Abstract
{
	
	/**
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#toString()
	 */
	protected function _toString()
	{
		return $this->getDirectory() . (($this->hasFileName())? $this->getFileName() : '') . (($this->hasFileExtension())? ':' . $this->getPort() : '');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasFileName()
	{
		return $this->_hasPart('FileName');
	}

	/**
	 * 
	 * @param $userInfo
	 * @return unknown_type
	 */
	public function setFileName($fileName)
	{
		$this->_setPartWithDirtyData('FileName', $fileName, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getFileName()
	{
		return $this->_getPart('FileName');		
	}
	
	/**
	 * 
	 * @param unknown_type $hostName
	 * @return unknown_type
	 */
	public function setDirectory($dir)
	{
		$this->_setPartWithDirtyData('Directory', $dir, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getDirectory()
	{
		return $this->_getPart('Directory');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function hasFileExtension()
	{
		return $this->_hasPart('FileExtension');
	}
	
	/**
	 * 
	 * @param unknown_type $port
	 * @return unknown_type
	 */
	public function setFileExtension($port)
	{
		$this->_setPartWithDirtyData('FileExtension', $port, false);
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getFileExtension()
	{
		return $this->_getPart('FileExtension');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Url/G_Url_Abstract#_setParts()
	 */
	protected function _setParts()
	{
		$this->_setPart('Directory', $this->getRegex()->getDirectory(), false);
		if ($this->getRegex()->hasFileName()) {
			$this->_setPart('FileName', $this->getRegex()->getFileName(), false);
		}
		if ($this->getRegex()->hasFileExtension()) {
			$this->_setPart('FileExtension', $this->getRegex()->getFileExtension(), false);
		}
	}
}