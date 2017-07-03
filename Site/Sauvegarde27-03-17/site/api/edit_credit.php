<?php
/**
 * Created by PhpStorm.
 * User: Yannis
 * Date: 27/02/2017
 * Time: 19:39
 */

require "bddConnect.php";

if (isset($_GET["credit"]) && isset($_GET["id"]) && isset($_GET["description"]) && isset($_GET["staff"]) && isset($_GET["mode"]))
{
    $bddIE = new bddConnect();

    $credits = $_GET["credit"];
    $idcard = $_GET["id"];
    $mode = $_GET["mode"];
    $mode = str_replace("[p]", "+", $mode);
    $mode = str_replace("[m]", "-", $mode);
    $staff = $_GET["staff"];
    $description = $_GET["description"];

    $dateToday = date("Y-m-d G:i:s");

    $request_student = "UPDATE `students` SET `credit`='$credits' WHERE `idCard` = '$idcard'";

    $data_student = $bddIE->requestAction($request_student);

    $request_log = "INSERT INTO `transac_log`(`client`, `montant`, `staff`, `date`, `detail`) VALUES ('$idcard', '$mode', '$staff', '$dateToday' ,'$description')";
    $bddIE->requestAction($request_log);

    print_r($data_student);
}