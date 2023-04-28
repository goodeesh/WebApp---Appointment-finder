<?php
include("businesslogic/simpleLogic.php");

$param = "";
$method = "";

isset($_GET["method"]) ? $method = $_GET["method"] : false;
isset($_GET["param"]) ? $param = $_GET["param"] : false;

if ($method == "saveData" && $param != "") {
    $data = json_decode($param);
    $logic = new SimpleLogic();
    $result = $logic->handleRequest($method, $data);
    if ($result) {
        response("GET", 200, "Data saved successfully");
    } else {
        response("GET", 400, "Error saving data");
    }
} else if ($method == "queryByUser") {
    $logic = new SimpleLogic();
    $result = $logic->handleRequest($method, $param);
    response("GET", 200, $result);
} else if ($method == "queryById") {
    $logic = new SimpleLogic();
    $result = $logic->handleRequest($method, $param);
    response("GET", 200, $result);
} else if ($method == "saveVote") {
    $data = json_decode($param);
    $logic = new SimpleLogic();
    $result = $logic->handleRequest($method, $data);
    response("GET", 200, $result);
} else if ($method == "queryUsersVoteByTimeslotId") {
    $logic = new SimpleLogic();
    $result = $logic->handleRequest($method, $param);
    response("GET", 200, $result);
} else if ($method == "deleteAppointment") {
    $data = json_decode($param);
    $logic = new SimpleLogic();
    $result = $logic->handleRequest($method, $data);
    response("GET", 200, $result);
} else {
    response("GET", 405, "Method not supported yet!");
}

function response($method, $httpStatus, $data)
{
    header('Content-Type: application/json');
    switch ($method) {
        case "GET":
            http_response_code($httpStatus);
            echo (json_encode($data));
            break;
        default:
            http_response_code(405);
            echo ("Method not supported yet!");
    }
}