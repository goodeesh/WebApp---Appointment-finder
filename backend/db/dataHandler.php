<?php
include("./models/appointment.php");
include("./models/timeslot.php");
include("./models/user.php");
include("./models/vote.php");
class DataHandler
{

    public function queryByUser()
    {
        include('database.php');
        // query all appointments
        $sql = "SELECT * FROM appointment";
        $result = $db_obj->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = new Appointment($row["appointment_id"], $row["title"], $row["location"], $row["duration"], $row["description"], $row["expiring_date"]);
            }
        }
        return $data;
    }


    public function saveData($data)
    {
        include('database.php');

        // prepare and execute the appointment insertion statement
        $stmt = $db_obj->prepare("INSERT INTO appointment (title, location, duration, description, expiring_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $data->title, $data->location, $data->duration, $data->description, $data->expiring_date);
        $result = $stmt->execute();
        if (!$result) {
            return false;
        }

        // get the appointment_id of the newly inserted appointment
        $stmt = $db_obj->prepare("SELECT appointment_id FROM appointment WHERE title = ? AND location = ? AND duration = ? AND description = ? AND expiring_date = ?");
        $stmt->bind_param("sssss", $data->title, $data->location, $data->duration, $data->description, $data->expiring_date);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data->appointment_id = $row["appointment_id"];
            }
        }

        // insert the date_time values for the appointment using a prepared statement
        $stmt = $db_obj->prepare("INSERT INTO timeslot (date_time, Fk_appointment_id) VALUES (?, ?)");
        $stmt->bind_param("si", $dt, $data->appointment_id);
        foreach ($data->date_time as $dt) {
            $result = $stmt->execute();
            if (!$result) {
                return false;
            }
        }

        return true;
    }

    public function queryById($id)
    {
        include('database.php');
        // query the appointment with the given id
        $sql = "SELECT * FROM appointment WHERE appointment_id = ?";
        $stmt = $db_obj->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appointment = new Appointment($row["appointment_id"], $row["title"], $row["location"], $row["duration"], $row["description"], $row["expiring_date"]);
                // query the timeslots for the appointment with the given id
                $sql = "SELECT * FROM timeslot WHERE Fk_appointment_id = ?";
                $stmt = $db_obj->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result2 = $stmt->get_result();
                $timeslots = [];
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        $timeslot_id = $row2["timeslot_id"];
                        $date_time = $row2["date_time"];
                        $Fk_appointment_id = $row2["Fk_appointment_id"];
                        // count the votes for the timeslot
                        $sql = "SELECT COUNT(*) AS vote_count FROM vote WHERE Fk_timeslot_id = ?";
                        $stmt = $db_obj->prepare($sql);
                        $stmt->bind_param("i", $timeslot_id);
                        $stmt->execute();
                        $result3 = $stmt->get_result();
                        $vote_count = 0;
                        if ($result3->num_rows > 0) {
                            $row3 = $result3->fetch_assoc();
                            $vote_count = $row3["vote_count"];
                        }
                        // create the timeslot object and set the vote count
                        $timeslot = new Timeslot($timeslot_id, $date_time, $Fk_appointment_id);
                        $timeslot->setVoteCount($vote_count);
                        $timeslots[] = $timeslot;
                    }
                }
                $appointment->setTimeslots($timeslots);
                $data[] = $appointment;
            }
        }
        return $data;
    }

    public function saveVote($data)
    {
        // this is the format of the data object {"username": "Adrian","timeslots": {"7": true,"8": true,"9": false}}
        $username = $data->username;
        $timeslots = $data->timeslots;
        include('database.php');

        // check if username is as user in the table user
        $sql = "SELECT * FROM user WHERE user = ?";
        $stmt = $db_obj->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            // if not, insert the username into the table user
            $sql = "INSERT INTO user (user) VALUES (?)";
            $stmt = $db_obj->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
        }

        // get the user_id of the user
        $sql = "SELECT user_id FROM user WHERE user = ?";
        $stmt = $db_obj->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_id = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user_id = $row["user_id"];
            }
        }

        // delete the votes of the user for all the given timeslots from the function parameter
        $sql = "DELETE FROM vote WHERE Fk_user_id = ? AND Fk_timeslot_id = ?";
        $stmt = $db_obj->prepare($sql);
        foreach ($timeslots as $timeslot_id => $value) {
            $timeslot_id = (int) $timeslot_id;
            $stmt->bind_param("ii", $user_id, $timeslot_id);
            $stmt->execute();
        }

        // insert the vote into the table vote where the timeslots is true
        $sql = "INSERT INTO vote (Fk_user_id, Fk_timeslot_id) VALUES (?, ?)";
        $stmt = $db_obj->prepare($sql);
        foreach ($timeslots as $timeslot_id => $value) {
            if ($value) {
                $timeslot_id = (int) $timeslot_id;
                $stmt->bind_param("ii", $user_id, $timeslot_id);
                $stmt->execute();
            }
        }

        return true;
    }

    public function queryUsersVoteByTimeslotId($id)
    {
        include('database.php');

        // query the usernames of the users who voted for the timeslot with the given id
        $sql = "SELECT user FROM user JOIN vote ON user.user_id = vote.Fk_user_id WHERE vote.Fk_timeslot_id = ?";
        $stmt = $db_obj->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usernames = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usernames[] = $row["user"];
            }
        }

        return json_encode($usernames);
    }

    public function deleteAppointment($id)
    {
        include('database.php');

        // query all the timeslots of the appointment with the given id
        $sql = "SELECT * FROM timeslot WHERE Fk_appointment_id = ?";
        $stmt = $db_obj->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $timeslots = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $timeslots[] = $row["timeslot_id"];
            }
        }

        //query all the votes of the timeslots
        $sql = "SELECT * FROM vote WHERE Fk_timeslot_id = ?";
        $stmt = $db_obj->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $votes = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $votes[] = $row["vote_id"];
            }
        }

        // delete all the votes of the timeslots
        $sql = "DELETE FROM vote WHERE Fk_timeslot_id = ?";
        $stmt = $db_obj->prepare($sql);
        foreach ($timeslots as $timeslot_id) {
            $stmt->bind_param("i", $timeslot_id);
            $stmt->execute();
        }

        // delete all the timeslots of the appointment
        $sql = "DELETE FROM timeslot WHERE Fk_appointment_id = ?";
        $stmt = $db_obj->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // delete the appointment
        $sql = "DELETE FROM appointment WHERE appointment_id = ?";
        $stmt = $db_obj->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return true;
    }
}