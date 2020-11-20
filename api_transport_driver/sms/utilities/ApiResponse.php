<?php

/**
* Base class to representate information in our RESTful API,
* we ensure the same structure in all responses.
*
* @author Mirsha Rojas <alikey01@gmail.com>
*/

abstract class ApiResponse
{
    
    /**
     * Error code HTTP
     * @var int
     */
    public $status; 

    /**
     * This method must be overwritten to add the Content-Type header
     * and print the format that the information will have. For each
     * accepted data format we will construct a new class.
     * 
     * @param  mixed|array $body Data to transform
     */
    public abstract function transform($body);
}