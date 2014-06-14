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

namespace jFramework\MVC\View;

use jFramework\Core\Registry;

/**
 * Generante and Process XHTML
 * 
 * Created: 2010-06-09 12:21 PM (GMT -03:00)
 * Updated: 2014-06-09 12:21 PM (GMT -03:00)
 * @version 0.0.1 
 * @package jFramework
 * @subpackage MVC
 * @copyright Copyright (c) 2010-2014, Júlio César de Oliveira
 * @author Júlio César de Oliveira <talk@juliocesar.me>
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0 License
 */
class XHTML
{    
    /**
     * Format XHTML
     * @param string $xhtml
     * @return string
     */
    public static function format($xhtml)
    {
        // Define result failsafe
        $result = $xhtml;
        
        // Detect if Tidy exists and php is running on a WebServer
        if(class_exists('tidy') && PHP_SAPI != 'cli'){
            // Spawn Tidy
            $tidy = new \tidy();
            
            // Tidy Config
            $config = array(
                'indent' => true,
                'alt-text' => '',
                'clean' => true,
                'output-xhtml' => true,
                'wrap' => 20000000,
                'indent-spaces' => 0
            );
            
            // Parse xhtml
            $tidy->parseString($result, $config, Registry::get('APP.xhtml-charset'));
            
            // Clear and Repair XHTML
            $tidy->cleanRepair();
            
            // Convert Tidy OBJ to string
            $result = (string)$tidy;      
        }elseif(PHP_SAPI == 'cli'){
            // Strip XHTML
            $result = strip_tags($result);
        }
        
        // XHTML BaseRef fixer and SEO Optimizations
        if(PHP_SAPI != 'cli'){
            // BaseRef Fixer
            $result = preg_replace('/href="/i', 'href="' . Registry::get('baseDir') . '$1', $result);
            $result = preg_replace('/src="/i', 'src="' . Registry::get('baseDir') . '$1', $result);
            $result = preg_replace("/this.src='/i", "this.src='" . Registry::get('baseDir') . '$1', $result);

            // SEO Optimizations
            $result = preg_replace("/\n|\r\n|\r|\t/", '', $result);
        }
        
        return $result;
    }
}
