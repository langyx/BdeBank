<?php

/**
 * Created by PhpStorm.
 * User: Yannis
 * Date: 27/02/2017
 * Time: 16:26
 */
class bddConnect
{
    private  $bddPDO = NULL;

    function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        //DB CONNECTION
        $servername = "localhost";
        $username = "root";
        $password = "bite##13";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=bde_bank", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
            $this->bddPDO = $conn;
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function requestData($requestString)
    {
        if ($this->bddPDO == NULL)
        {
            return NULL;
        }
        return $this->bddPDO->query($requestString)->fetch(PDO::FETCH_ASSOC);
    }

    function requestTable($requestString)
    {
        if ($this->bddPDO == NULL)
        {
            return NULL;
        }
        $reqIt = $this->bddPDO->prepare($requestString);
        $reqIt->execute();
        $result = $reqIt->fetchAll();
        return $result;
    }

    function  requestAction($requestString)
    {
        if ($this->bddPDO == NULL)
        {
            return "NOBDD";
        }
        try
        {
            $this->bddPDO->query($requestString);
        }
        catch (PDOException  $e)
        {
            return $e->getMessage();
        }
        return "ok";
    }


}