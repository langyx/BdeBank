<?php
/**
 * Created by PhpStorm.
 * User: Yannis
 * Date: 27/02/2017
 * Time: 16:19
 */

require "bddConnect.php";

if (isset( $_GET["id"]))
{
    $idCard =  $_GET["id"];

    $bddIE = new bddConnect();

    $request_student = "select * from students WHERE idCard = '$idCard'";

    $data_student = $bddIE->requestData($request_student);

    print_r(json_encode($data_student));
}