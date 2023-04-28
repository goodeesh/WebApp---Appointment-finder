<?php
class Vote
{
    public $vote_id;
    public $Fk_timeslot_id;
    public $Fk_user_id;

    function __construct($vote_id, $Fk_timeslot_id, $Fk_user_id)
    {
        $this->vote_id = $vote_id;
        $this->Fk_timeslot_id = $Fk_timeslot_id;
        $this->Fk_user_id = $Fk_user_id;
    }
}
?>