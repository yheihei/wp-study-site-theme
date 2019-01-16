<?php


spl_autoload_register(function($class) {
    $prefix = 'YheiStudyTheme\\';
    
    if(strpos($class, $prefix) === 0) {
        $className = substr($class, strlen($prefix));
        $classFilePath = __DIR__ . '/class/' . $className . '.php';
        if(file_exists($classFilePath)) {
            require $classFilePath;
        } else {
            echo 'No such class: ' . $className;
        }
    }
});