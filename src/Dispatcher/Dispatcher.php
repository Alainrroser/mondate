<?php

namespace App\Dispatcher;

class Dispatcher {
    
    /**
     * Diese Methode wertet die Request URI aus leitet die Anfrage entsprechend
     * weiter.
     */
    public static function dispatch() {
        $controllerName = UriParser::getControllerName() . 'Controller';
        $className = 'App\\Controller\\' . $controllerName;
        $methodName = UriParser::getMethodName();
        
        $controller = new $className();
        $controller->$methodName();
    }
    
}
