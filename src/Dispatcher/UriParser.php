<?php

namespace App\Dispatcher;

class UriParser {
    
    /**
     * Diese Methode wertet die Request URI aus und gibt den Controllername zurück.
     */
    public static function getControllerName() {
        $uriFragments = self::getUriFragments();
        
        if(!empty($uriFragments[0])) {
            $controllerName = $uriFragments[0];
            $controllerName = ucfirst($controllerName);
            return $controllerName;
        }
        
        return 'Default';
    }
    
    /**
     * Diese Methode wertet die Request URI aus und gibt den Actionname (Action = Methode im Controller) zurück.
     */
    public static function getMethodName() {
        $uriFragments = self::getUriFragments();
        
        $method = 'index';
        if(!empty($uriFragments[1])) {
            $method = $uriFragments[1];
        }
        
        return $method;
    }
    
    private static function getUriFragments() {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = strtok($uri, '?');
        $uri = trim($uri, '/');
        
        return explode('/', $uri);
    }
    
}
