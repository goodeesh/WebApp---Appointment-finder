<?php
class User
{
    public $user_id;
    public $user;

    function __construct($user_id, $user)
    {
        $this->user_id = $user_id;
        $this->user = $user;
    }
}
?>