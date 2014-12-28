<?php

require 'Category.php';

class User
{
    public $UserID;
    public $UserName;
    public $PasswordHash;
    public $PasswordSalt;
    public $Email;
    public $CreatedOn;
    public $LastActivityDate;
    public $UserType;
    public $CurrentPoints;
    public $ApiKey;
    
    public $Categories;
    
    public function isAdmin()
    {
        return $this->UserType == 1;
    }
    
    public static function createFromDbObject($dbUser)
    {
        return static::create($dbUser->UserID, $dbUser->UserName, $dbUser->PasswordHash, $dbUser->PasswordSalt, $dbUser->Email, $dbUser->CreatedOn, $dbUser->LastActivityDate, $dbUser->UserType, $dbUser->CurrentPoints, $dbUser->ApiKey);
    }
    
    public static function create($userID, $userName, $passwordHash, $passwordSalt, $email, $createdOn, $lastActivityDate, $userType, $currentPoints, $apiKey)
    {
        $user = new User();
        
        $user->UserID = $userID;
        $user->UserName = $userName;
        $user->PasswordHash = $passwordHash;
        $user->PasswordSalt = $passwordSalt;
        $user->Email = $email;
        $user->CreatedOn = $createdOn;
        $user->LastActivityDate = $lastActivityDate;
        $user->UserType = $userType;
        $user->CurrentPoints = $currentPoints;
        $user->ApiKey = $apiKey;
        
        $user->Categories = array();
        
        return $user;
    }
    
    function addCategory($category)
    {
        $this->Categories[$category->CategoryID] = $category;
    }
}

?>