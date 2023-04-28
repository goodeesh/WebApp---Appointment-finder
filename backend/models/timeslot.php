<?php
class Timeslot
{
    public $timeslot_id;
    public $date_time;
    public $Fk_appointment_id;
    public $vote_count;

    function __construct($timeslot_id, $date_time, $Fk_appointment_id)
    {
        $this->timeslot_id = $timeslot_id;
        $this->date_time = $date_time;
        $this->Fk_appointment_id = $Fk_appointment_id;
    }

    function setVoteCount($vote_count)
    {
        $this->vote_count = $vote_count;
    }
}
?>