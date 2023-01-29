<?php
/*
* count required parameters for method
*/ 
namespace App\Lib\Traits;

trait Count_parametrs_of_method 
{
    function count_parameters_of_method($class, $method)
    {
        $action_method_relfection = new \ReflectionMethod($class, $method);
        return $action_method_relfection->getNumberOfRequiredParameters();
    }
}
?>