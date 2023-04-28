<?php
class Appointment
{
  public $appointment_id;
  public $title;
  public $location;
  public $duration;
  public $description;
  public $expiring_date;
  public $timeslots;

  function __construct($appointment_id, $title, $location, $duration, $description, $expiring_date)
  {
    $this->appointment_id = $appointment_id;
    $this->title = $title;
    $this->location = $location;
    $this->duration = $duration;
    $this->description = $description;
    $this->expiring_date = $expiring_date;
    $this->timeslots = array();
  }

  public function setTimeslots($timeslots)
  {
    $this->timeslots = $timeslots;
  }
}

?>