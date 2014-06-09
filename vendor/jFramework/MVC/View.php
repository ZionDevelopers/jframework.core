<?php
/**
 * jFramework
 *
 * @version 2.0.0
 * @link https://github.com/ZionDevelopers/jframework/ The jFramework GitHub Project
 * @copyright 2010-2014, Júlio César de Oliveira
 * @author Júlio César de Oliveira <talk@juliocesar.me>
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0 License
 */

namespace jFramework\MVC;

use jFramework\MVC\View\AbstractView;
use jFramework\Core\Registry;

/**
 * Generate view
 * 
 * Created: 2010-06-08 08:33 PM
 * Updated: 2014-06-08 08:33 AM
 * @version 0.0.1 
 * @package jFramework
 * @subpackage MVC
 * @copyright Copyright (c) 2010-2014, Júlio César de Oliveira
 * @author Júlio César de Oliveira <talk@juliocesar.me>
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0 License
 */
class View extends AbstractView
{
    private $vars = array();
    
    public function __set($name, $value)
    {
        $this->vars[$name] = $value;
    }
    
    public function __get($name)
    {
        $result = null;
        
        if(isset($this->$name)){
            $result = $this->$name;
        }
        
        if(isset($this->vars[$name])){
            $result = $this->vars[$name];        
        }
        
        return $result;
    }
    
    /**
     * To Parse View
     * 	
     */
    public function render()
    {        
        $file = Registry::get('FOLDER.view') . '/' . $this->viewFile. '.phtml';

        ob_start();
        require $file;
        $contents = ob_get_contents();
        ob_end_clean();
        
        return $contents;
    }

    public function getView()
    {
        return $this->view;
    }
    
    /**
     * To Parse Layout
     */
    public function renderlayout($view)
    {   
        // Define View
        $this->view = $view;
        
        // Get layout file
        $file = Registry::get('FOLDER.layout') . '/' . $this->layoutFile. '.phtml';
        
        // Start Buffer obtainer
        ob_start();
        $this->baseDir = dirname($_REQUEST['REQUEST_URI']);
        // Request View
        require $file;
        // Get Buffs
        $contents = ob_get_contents();
        // Clear buff and end buffer obtainer
        ob_end_clean();
        
        // Return view contents
        return $contents;
    }
}
