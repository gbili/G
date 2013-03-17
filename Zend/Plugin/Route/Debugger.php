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
class G_Zend_Plugin_Route_Debugger
extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
    	$a = array();
    	$a['uri'] = $request->getRequestUri();
    	$a['module'] = $request->getModuleName();
    	$a['controller'] = $request->getControllerName();
    	$a['action'] = $request->getActionName();
    	
    	throw new G_Exception(print_r($a, true));
    }
}