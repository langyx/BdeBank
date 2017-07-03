<?php
/**
 * Created by PhpStorm.
 * User: yannis
 * Date: 15/03/2017
 * Time: 15:34
 */

require "bddConnect.php";

if (isset($_GET["mail"]) && isset($_GET["pass"]))
{
    $mail = addslashes($_GET['mail']);
    $pass = addslashes($_GET['pass']);
    $pass = sha1($pass);

    $bddIE = new bddConnect();

    $request_student = "select * from students WHERE mail = '$mail' AND pass = '$pass'";

    $data_student = $bddIE->requestData($request_student);

    if (empty($data_student))
    {
        echo "ko";
    }else{
        if ($data_student["staff"] == 1)
        {
            echo "ok";
        }
    }
}

?>