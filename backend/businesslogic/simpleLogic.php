<?php
include("db/dataHandler.php");

class SimpleLogic
{

    private $dh;

    function __construct()
    {
        $this->dh = new DataHandler();
    }

    function handleRequest($method, $param)
    {
        switch ($method) {

            case "queryByUser":
                $res = $this->dh->queryByUser();
                break;

            case "saveData":
                $res = $this->dh->saveData($param);
                break;

            case "queryById":
                $res = $this->dh->queryById($param);
                break;

            case "saveVote":
                $res = $this->dh->saveVote($param);
                break;

            case "queryUsersVoteByTimeslotId":
                $res = $this->dh->queryUsersVoteByTimeslotId($param);
                break;

            case "deleteAppointment":
                $res = $this->dh->deleteAppointment($param);
                break;

            default:
                $res = null;
                break;
        }
        return $res;
    }
}