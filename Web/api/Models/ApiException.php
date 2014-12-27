<?php

class ApiException extends Exception
{
    public $ErrorCode;
    public $ErrorMessage;
    
    public function __construct($errCode, $errMessage) 
    {
        parent::__construct($errMessage);
        $this->ErrorCode = $errCode;
        $this->ErrorMessage = "API Error: " . $errMessage;
    }
}

?>