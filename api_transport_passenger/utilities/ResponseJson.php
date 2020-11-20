<?php

require_once 'ApiResponse.php';

/**
* Class that displays the response in JSON format
* 
* @author Mirsha Rojas <alikey01@gmail.com>
*/

class ResponseJson extends ApiResponse
{

    /**
     * Prints the body of the response and sets the response code
     * 
     * @param  mixed|array $body Data to show in JSON
     */
    public function transform($body)
    {
        if ($this->status) {
            http_response_code($this->status);
        }

        header('Content-Type: application/json; charset=utf8');
        echo json_encode($body, JSON_PRETTY_PRINT);
    }
}