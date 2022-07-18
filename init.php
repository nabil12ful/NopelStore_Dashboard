<?php
// Database Connect
include("connect.php");
// Paths
    $funcs   = "includes/funcs/";
    $langs   = "includes/langs/";
    $libs    = "includes/libs/";
    $temps   = "includes/temps/";
    $css     = "layout/css/";
    $js      = "layout/js/";
    $imgs    = "layout/images/";
    $uploads = "data/uploads/";

    // includes
    include($funcs . "func.php");
    // Get Lang From DB
    $stmt = $con->prepare("SELECT * FROM settings WHERE Emp_ID = ?");
    $stmt->execute(array($_SESSION['id']));
    $sett = $stmt->fetch();
    if($sett['Language'] == "en"){
        include($langs . "en.php");
    }
    if($sett['Language'] == "ar"){
        include($langs . "ar.php");
    }
    // Get Display Mode
    if($sett['Display_Mode'] == "night"){
        $display = "night";
    }else{
        $dispaly = "light";
    }
    include($temps . "head.php");
    include($temps . "sidebar.php");
    include($temps . "header.php");
