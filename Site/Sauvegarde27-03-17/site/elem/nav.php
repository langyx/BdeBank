<?php
/**
 * Created by PhpStorm.
 * User: yannis
 * Date: 13/03/2017
 * Time: 17:02
 */

if (isset($_COOKIE['login']) && isset($_COOKIE['staff']))
{
    $login = $_COOKIE['login'];
    $staff = $_COOKIE['staff'];

}

?>

<!-- Navigation -->
<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="#page-top">BDE Bank</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="hidden">
                    <a href="#page-top"></a>
                </li>
                <li class="page-scroll">
                    <a href="index.php">Accueil</a>
                </li>
                <?php
                    if (isset($login)) { ?>
                        <li class="page-scroll">
                            <a href="./compte.php">Mon Compte</a>
                        </li>
                 <?php   } ?>

                <?php
                if (isset($login) && $staff) { ?>
                    <li class="page-scroll">
                        <a href="panel.php">Panel</a>
                    </li>
                <?php   } ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
