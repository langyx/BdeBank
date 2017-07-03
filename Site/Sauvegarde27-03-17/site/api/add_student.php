<?php
/**
 * Created by PhpStorm.
 * User: Yannis
 * Date: 27/02/2017
 * Time: 18:43
 */

require "bddConnect.php";

if (isset($_GET["mail"]) && isset($_GET["idcard"]))
{
    $studenMail = $_GET["mail"];
    $studenCredId = $_GET["idcard"];

    $bddIE = new bddConnect();

    $studenSAFING = explode("/", $studenMail);

    $studenSAFING[1] = sha1($studenSAFING[1]);
    $request_student = "INSERT INTO `students`(`idCard`, `credit`, `mail`, `pass`, `staff`) VALUES ('$studenCredId',0,'$studenSAFING[0]', '$studenSAFING[1]', 0)";

    $data_student = $bddIE->requestAction($request_student);

    print_r($data_student);
}