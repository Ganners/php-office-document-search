<?php

function __autoload($class) {

    if(!class_exists($class, false)) {
        $file_location = str_replace("\\", "/", $class);
        $file_location = strtolower($file_location);
        $file_location = $file_location . ".php";
        require_once($file_location);
    }

}