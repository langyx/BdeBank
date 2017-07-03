<?php

require "./api/bddConnect.php";

if (isset($_GET['mail']) && isset($_COOKIE['login']) && isset($_COOKIE['staff']) && $_COOKIE['staff'] == 1)
{
    $mailSearched = $_GET['mail'];
    $mailSearched = addslashes($mailSearched);
    $staff = $_COOKIE['staff'];
    $staffID = $_COOKIE['login'];

    $bddIE = new bddConnect();
    $request_student = "select * from students WHERE mail = '$mailSearched'";
    $data_student = $bddIE->requestData($request_student);

    if (empty($data_student)) {
        echo "<script>alert(\"Compte Inconnu\");</script>";
        echo "<script>document.location = \"panel.php\";</script>";
    }

    $loginDecomp = explode("@", $mailSearched);
    $imgProfile = "https://cdn.local.epitech.eu/userprofil/profilview/".$loginDecomp[0].".jpg";

    if (isset($_GET["montant-c"]) && isset($_GET["mode-c"]) && isset($_GET["description-c"]))
    {
        $montantC = addslashes($_GET["montant-c"]);
        $modeC = addslashes($_GET["mode-c"]);
        $descriptionC = addslashes($_GET["description-c"]);

        $credits = $data_student["credit"];
        $mode = "";
        if ($modeC == "add")
        {
            $mode = "+";
            $credits += $montantC;
        }else{
            $mode = "-";
            $credits -= $montantC;
        }
        if ($credits < 0)
        {
            echo "<script>document.location = \"compte.php?mail=$mailSearched\";</script>";
            exit(0);
        }
        $mode = $mode . $montantC;
        $idcard = $data_student["idCard"];

        $dateToday = date("Y-m-d G:i:s");

        $request_student = "UPDATE `students` SET `credit`='$credits' WHERE `idCard` = '$idcard'";

        $data_student = $bddIE->requestAction($request_student);

        $request_log = "INSERT INTO `transac_log`(`client`, `montant`, `staff`, `date`, `detail`) VALUES ('$idcard', '$mode', '$staffID', '$dateToday' ,'$descriptionC')";
        $bddIE->requestAction($request_log);
        echo "<script>document.location = \"compte.php?mail=$mailSearched\";</script>";
        exit(0);
    }
}else{
    if (isset($_COOKIE['login']))
    {
        $login = $_COOKIE['login'];
        $staff = $_COOKIE['staff'];

        $bddIE = new bddConnect();
        $request_student = "select * from students WHERE mail = '$login'";
        $data_student = $bddIE->requestData($request_student);

        $loginDecomp = explode("@", $login);
        $imgProfile = "https://cdn.local.epitech.eu/userprofil/profilview/".$loginDecomp[0].".jpg";
    }
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Freelancer - Start Bootstrap Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/freelancer.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .img-circle {
            border-radius: 50%;
        }

        .custab{
            border: 1px solid #ccc;
            /*padding: 5px;
            margin: 5% 50%;*/
            box-shadow: 3px 3px 2px #ccc;
            transition: 0.5s;
        }
        .custab:hover{
            box-shadow: 3px 3px 0px transparent;
            transition: 0.5s;
        }

        .btn span.glyphicon {
            opacity: 0;
        }
        .btn.active span.glyphicon {
            opacity: 1;
        }
    </style>

</head>

<body id="page-top" class="index">
<div id="skipnav"><a href="#maincontent">Skip to main content</a></div>

<?php include_once('elem/nav.php'); ?>


<!-- Contact Section -->
<section id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">

                <?php

                if (isset($staffID))
                {
                    ?>
                    <form class="form-horizontal" action="compte.php">

                            <!-- Form Name -->
                            <legend>Edition du solde :</legend>
                            <input type="hidden" name="mail" value="<?php echo $mailSearched;?>">

                            <!-- Textarea -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="request-description">Description</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" id="request-description" name="description-c"></textarea>
                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="full_name">Montant</label>
                                <div class="col-md-4">
                                    <input id="full_name" name="montant-c" type="text" placeholder="" class="form-control input-md" required="">

                                </div>
                            </div>

                            <div class="btn-group" data-toggle="buttons">

                                <label class="btn btn-success active">
                                    <input type="radio" name="mode-c" id="plus" autocomplete="off" value="add" checked>
                                    <span class="glyphicon glyphicon-ok"></span>
                                    Ajout
                                </label>

                                <label class="btn btn-primary">
                                    Retrait
                                    <input type="radio" name="mode-c" id="moins" autocomplete="off" value="less">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </label>

                            </div>

                            <br> <br>

                            <!-- Button -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="singlebutton"></label>
                                <div class="col-md-4">
                                    <button  class="btn btn-primary">Valider</button>
                                </div>
                            </div>

                        </fieldset>
                    </form>
                    <legend></legend>
                    <br>
                    <?php
                }

                ?>

                <hr class="star-primary">
                <h2><?php echo str_replace(".", " ", $loginDecomp[0]) . " :"; ?></h2>
                <hr class="star-primary">
                <br>
                <img class="img-circle" src="<?php echo $imgProfile; ?>">
                <br><br>

                <h3>Numéro de carte : <?php echo $data_student['idCard']; ?></h3>
                <hr class="star-light">
                <h3>Solde : <?php echo $data_student['credit']; ?> €</h3>
                <br>
                <hr class="star-primary">
                <h2>Historique :</h2>
                <hr class="star-primary">
                <br>

                <table class="table table-striped custab">
                    <thead>
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Montant</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Staff</th>
                    </tr>
                    </thead>
                    <?php

                    $request_history = "select * from transac_log WHERE client = '$data_student[idCard]' ORDER BY `date` DESC ";

                    $data_history = $bddIE->requestTable($request_history);

                    for ($i = 0; $data_history[$i]; $i++) {
                        echo "<tr>";
                        echo "<td>".$data_history[$i]["date"]."</td>";
                        echo "<td>".$data_history[$i]["montant"]."</td>";
                        echo "<td>".$data_history[$i]["detail"]."</td>";
                        echo "<td>".$data_history[$i]["staff"]."</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>

        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">

             </div>
        </div>
    </div>
</section>


<!-- Footer -->
<footer class="text-center">
    <div class="footer-above">
        <div class="container">
            <div class="row">
                <div class="footer-col col-md-4">
                    <h3></h3>

                </div>
                <div class="footer-col col-md-4">
                    <h3>Réseaux</h3>
                    <ul class="list-inline">
                        <li>
                            <a href="https://www.facebook.com/BDE.EpitechMarseille" class="btn-social btn-outline"><span class="sr-only">Facebook</span><i class="fa fa-fw fa-facebook"></i></a>
                        </li>
                    </ul>
                </div>
               
            </div>
        </div>
    </div>
    <div class="footer-below">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    Dev par lang_y <3
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
<div class="scroll-top page-scroll hidden-sm hidden-xs hidden-lg hidden-md">
    <a class="btn btn-primary" href="#page-top">
        <i class="fa fa-chevron-up"></i>
    </a>
</div>

<!-- Portfolio Modals -->
<div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-content">
        <div class="close-modal" data-dismiss="modal">
            <div class="lr">
                <div class="rl">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="modal-body">
                        <h2>Project Title</h2>
                        <hr class="star-primary">
                        <img src="img/portfolio/cabin.png" class="img-responsive img-centered" alt="">
                        <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                        <ul class="list-inline item-details">
                            <li>Client:
                                <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                </strong>
                            </li>
                            <li>Date:
                                <strong><a href="http://startbootstrap.com">April 2014</a>
                                </strong>
                            </li>
                            <li>Service:
                                <strong><a href="http://startbootstrap.com">Web Development</a>
                                </strong>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-content">
        <div class="close-modal" data-dismiss="modal">
            <div class="lr">
                <div class="rl">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="modal-body">
                        <h2>Project Title</h2>
                        <hr class="star-primary">
                        <img src="img/portfolio/cake.png" class="img-responsive img-centered" alt="">
                        <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                        <ul class="list-inline item-details">
                            <li>Client:
                                <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                </strong>
                            </li>
                            <li>Date:
                                <strong><a href="http://startbootstrap.com">April 2014</a>
                                </strong>
                            </li>
                            <li>Service:
                                <strong><a href="http://startbootstrap.com">Web Development</a>
                                </strong>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-content">
        <div class="close-modal" data-dismiss="modal">
            <div class="lr">
                <div class="rl">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="modal-body">
                        <h2>Project Title</h2>
                        <hr class="star-primary">
                        <img src="img/portfolio/circus.png" class="img-responsive img-centered" alt="">
                        <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                        <ul class="list-inline item-details">
                            <li>Client:
                                <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                </strong>
                            </li>
                            <li>Date:
                                <strong><a href="http://startbootstrap.com">April 2014</a>
                                </strong>
                            </li>
                            <li>Service:
                                <strong><a href="http://startbootstrap.com">Web Development</a>
                                </strong>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-content">
        <div class="close-modal" data-dismiss="modal">
            <div class="lr">
                <div class="rl">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="modal-body">
                        <h2>Project Title</h2>
                        <hr class="star-primary">
                        <img src="img/portfolio/game.png" class="img-responsive img-centered" alt="">
                        <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                        <ul class="list-inline item-details">
                            <li>Client:
                                <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                </strong>
                            </li>
                            <li>Date:
                                <strong><a href="http://startbootstrap.com">April 2014</a>
                                </strong>
                            </li>
                            <li>Service:
                                <strong><a href="http://startbootstrap.com">Web Development</a>
                                </strong>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-content">
        <div class="close-modal" data-dismiss="modal">
            <div class="lr">
                <div class="rl">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="modal-body">
                        <h2>Project Title</h2>
                        <hr class="star-primary">
                        <img src="img/portfolio/safe.png" class="img-responsive img-centered" alt="">
                        <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                        <ul class="list-inline item-details">
                            <li>Client:
                                <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                </strong>
                            </li>
                            <li>Date:
                                <strong><a href="http://startbootstrap.com">April 2014</a>
                                </strong>
                            </li>
                            <li>Service:
                                <strong><a href="http://startbootstrap.com">Web Development</a>
                                </strong>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="portfolio-modal modal fade" id="portfolioModal6" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-content">
        <div class="close-modal" data-dismiss="modal">
            <div class="lr">
                <div class="rl">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="modal-body">
                        <h2>Project Title</h2>
                        <hr class="star-primary">
                        <img src="img/portfolio/submarine.png" class="img-responsive img-centered" alt="">
                        <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                        <ul class="list-inline item-details">
                            <li>Client:
                                <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                </strong>
                            </li>
                            <li>Date:
                                <strong><a href="http://startbootstrap.com">April 2014</a>
                                </strong>
                            </li>
                            <li>Service:
                                <strong><a href="http://startbootstrap.com">Web Development</a>
                                </strong>
                            </li>
                        </ul>
                        <button id="btnSubmit" type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- jQuery -->
<script src="vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Plugin JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

<!-- Contact Form JavaScript -->
<script src="js/jqBootstrapValidation.js"></script>
<script src="js/contact_me.js"></script>

<!-- Theme JavaScript -->
<script src="js/freelancer.min.js"></script>

</body>

</html>
