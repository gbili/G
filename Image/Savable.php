<?php
class G_Image_Savable
extends G_Molecule_Savable
{
	
	/**
	 * Directory where images are saved
	 * local file system
	 * 
	 * @var unknown_type
	 */
	static private $_pathToImagesInLFS = '';
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_remoteUrlHTTP = null;
	
	/**
	 * /path/to/images/filePrefix.fileSuffix
	 * ex : /Users/gui/Images/somename.jpg
	 * @var unknown_type
	 */
	private $_filePrefix = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_supportedFileSuffixes = array('jpg', 'gif', 'png');
	
	/**
	 * 
	 * @var unknown_type
	 */
	static private $_defaultFileSuffix = 'jpg';
	
	/**
	 * Tries to create a different file name and
	 * save the content of it if the name already
	 * exists. (on false)
	 * Dissmisses the file if it already exists (on true)
	 * 
	 * @var unknown_type
	 */
	static private $_dissmissFileIfNameAlreadyInUse = true;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_fileNameChangesCount = 0;
	
	/**
	 * 
	 * @var unknown_type
	 */
	private $_fileNameChangesMaxCount = 10;
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setCustomRequestorTableName('Image');
	}
	
	/**
	 * Directory where the images are saved
	 * 
	 * @param string $path
	 * @return unknown_type
	 */
	static public function setPathToImagesInLFS($path)
	{
		if (DIRECTORY_SEPARATOR === mb_substr($path, -1)) {
			$path = mb_substr($path, 0, -1);
		}
		self::$_pathToImagesInLFS = (string) $path;
	}
	
	/**
	 * This will tell the filesystem save to dismiss the
	 * file when the name is already in use
	 * 
	 * Otherwise attempt to create a different file name
	 * 
	 * @param unknown_type $bool
	 * @return unknown_type
	 */
	static public function setDissmissFileIfNameAlreadyInUse($bool = true)
	{
		self::$_dissmissFileIfNameAlreadyInUse = (boolean) $bool;
	}

	/**
	 * 
	 * @return unknown_type
	 */
	static public function getPathToImagesInLFS()
	{
		if (null === self::$_pathToImagesInLFS) {
			throw new G_Image_Exception('The path is not set');
		}
		return self::$_pathToImagesInLFS;
	}
	
	/**
	 * 
	 * @param G_Url $remoteServeImageUrl
	 * @return unknown_type
	 */
	public function setSourceUrl(G_Url $remoteServerImageUrl)
	{
		if (!$remoteServerImageUrl->isValid()) {
			throw new G_Image_Exception('The url to the image in the remote server is not valid');
		}
		$this->_remoteUrlHTTP = $remoteServerImageUrl;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getSourceUrl()
	{
		if (null === $this->_remoteUrlHTTP) {
			throw new G_Image_Exception('Url to image was not set');
		}
		return $this->_remoteUrlHTTP;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getLocalPathToImage()
	{
		return $this->getLocalUrl();
	}
	
	/**
	 * Proxy
	 * returns the link to the image
	 * in the local file system
	 * 
	 * @return string
	 */
	public function getLocalUrl()
	{
		return self::getPathToImagesInLFS() . DIRECTORY_SEPARATOR . $this->getFileName();
	}
	
	public function hasLocalUrl()
	{
		return $this->isSetKey('localUrl');
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function fileSystemDelete()
	{
		if (file_exists($this->getLocalUrl()) && false === unlink($this->getLocalUrl())) {
			throw new G_Exception('Image was not able to delete itself from file system');
		}
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getFileName()
	{
		return $this->getFilePrefix()  . '.' . $this->getFileSuffix();
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getFileSuffix()
	{
		$suffix = mb_strtolower(mb_substr($this->getSourceUrl()->getPath(), -3));
		if (!in_array($suffix, $this->_supportedFileSuffixes)) {
			//set to default suffix
			$suffix = self::$_defaultFileSuffix;
		}
		return $suffix;
	}
	
	/**
	 * 
	 * @param unknown_type $prefix
	 * @return unknown_type
	 */
	public function setFilePrefix($prefix)
	{
		$prefix = (string) $prefix;
		//set max allowed file name
		if (250 <= mb_strlen($prefix)) {//make sure suffix fits in
			$count = ('-' === mb_substr($prefix, 248, 1))? 248 : 249;
			$prefix = mb_substr($prefix, 0, $count);
		}
		$this->_filePrefix = $prefix;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function getFilePrefix()
	{
		if (null === $this->_filePrefix) {
			throw new G_Exception('File Prefix is not set');
		}
		return $this->_filePrefix;
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
	public function fileSystemSave()
	{
		//save in file system
		G_Echo::l1("saving image with local url : {$this->getLocalUrl()}");
		$remoteSourceUrl = $this->getSourceUrl()->toString();
		if (!is_writable(self::getPathToImagesInLFS())) {
			G_Echo::l1("throwing exception\n");
			throw new G_Exception("The image could not be saved because destination directory is not writeable mod is : ");
		}
		if (true === file_exists($this->getLocalUrl())) {
			 if (false === self::$_dissmissFileIfNameAlreadyInUse
			 	&& $this->_fileNameChangesCount < $this->_fileNameChangesMaxCount) {
			 	$this->_fileNameChangesCount++;
			 	G_Echo::l1("generating new name for existing image name try nº: $this->_fileNameChangesCount");
			 	$this->setFilePrefix($this->getFilePrefix() . $this->_fileNameChangesCount);
			 	return $this->filesystemSave();//try to filesystem save until a non existing name is found and it will enter the else
			 } //if dissmiss is true, simply dont save the file
			 G_Echo::l1("image write skipped!");
		} else {
			G_Echo::l1("putting contents \n");
			if (false === file_put_contents($this->getLocalUrl(), $getRes = file_get_contents($remoteSourceUrl))) {
				if (false === $getRes) {
					throw new G_Exception("The image could not be retrieved");
				} else {
					throw new G_Exception("Fail to save image, unknown reason");
				}
			}
			G_Echo::l1("image write succeed!");
		}
	}
	
	
}