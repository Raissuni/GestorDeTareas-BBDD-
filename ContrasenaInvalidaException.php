<?php
class ContrasenaInvalidaException extends Exception{
    function __construct($mensaje){
        parent::__construct($mensaje);

    }
} 
?>