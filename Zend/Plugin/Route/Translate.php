<?php
/**
 * The routeStartup method in a controller plugin is called before the 
 * routing process is started. This lets us hook in before the router 
 * and change the request URL to something it will understand.
 * 
 * As you can see, the code is mostly the same as the code in the __call 
 * method. The main difference is in the few last lines, which replace 
 * the old request URL with a one we craft in the plugin. 
 * 
 * This method is somewhat better than using __call, as it�s much easier 
 * to plug in a project or remove, as you can just use registerPlugin() 
 * in the front controller. 
 * 
 * This one does have one outstanding fault in the way it is right now, 
 * though: If you use any other kinds of URLs than the product-number-category.html 
 * kind, this one will get seriously messed up. You will need to add some additional 
 * checking to make sure that the URL is indeed the kind we would want 
 * to process using the plugin.
 * 
 * @author gui
 *
 */
class G_Zend_Plugin_Route_Translate
extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
    	/*
    	 * the request ur may look like this :
    	 * /
    	 * /module/controller/cat-name-here-pagenum.html
    	 * /controller/cat-name-here-pagenum.html
    	 * we do not want to treat theses requests :
    	 * /
    	 * and when the end of request is not .html
    	 * 
    	 * 
    	 */
    	$uri = $request->getRequestUri();
    	if (2 > strlen($uri) // '/'
    		|| '.' !== substr($uri, -5, 1)) { // not .html (in default>ajax module)
    		return;
    	}
    	
    	//get the last part of uri (the one with "cat-name-is-long-01-99-date.html"means: page 1, items per page 99, order by date)//max items per page 99
    	$uriArray = explode('/', $uri);
    	if ('sc.html' === $uriArray[1]) {
    		$uri = '/jsless';
    	} else if ('video' === $uriArray[1]) {
    		$uri = $this->_translateVideo($uri, $uriArray);
    	} else {
    		$uri = $this->_translateVideos($uri, $uriArray);
    	}
    	
    	//throw new G_Exception('intercepted : ' . print_r($uri, true));
    	
	    $request->setRequestUri($uri);
    }
    
    /**
     * 
     * @param unknown_type $uri
     * @return unknown_type
     */
    private function _translateVideos($uri, $uriArray)
    {
    	//everithing after last slash
    	$aLSlash = array_pop($uriArray);
    	$aLSParts = explode('-', $aLSlash);
    	
    	//vars
    	$vars = array();
    	$vars[] = substr(array_pop($aLSParts), 0, -5);//orderby (remove .html)
    	$vars[] = array_pop($aLSParts);//items per page
    	$vars[] = array_pop($aLSParts);//page number
    	
    	//cat
    	$vars[] = implode('-', $aLSParts);
 
    	$uri = '/jsless/index' . implode('/', $uriArray);
    	$uri .= "/cat/{$vars[3]}/page/{$vars[2]}/ipp/{$vars[1]}/orderby/{$vars[0]}";
    	return $uri;
    }
    
    /**
     * 
     * @param unknown_type $uri
     * @param unknown_type $uriArray
     * @return unknown_type
     */
    private function _translateVideo($uri, $uriArray)
    {
    	$uri = '/jsless/index/video/id/' . array_pop($uriArray);
    	return $uri;
    }
}