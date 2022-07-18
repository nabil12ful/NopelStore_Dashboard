<?php
    /*
    ===============================================
    ==            Template Page                  ==
    == You Can Add | Edit | Delete Members Here  ==
    ===============================================
    */
    $pageTitle = "Temp";
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: index.php");
    }
    include('init.php');

    $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

    // Start Manage Page 
    if($action == 'Manage'){ //Manage Page 
        
    }elseif($action == "Add"){ //Add New Member Page

    }elseif($action == "Insert"){ // Insert Member Page

    }elseif($action == 'Edit'){    // Edit Member Page 

    }elseif($action == 'Update'){  // Update Page Here 

    }elseif($action == "Delete"){ //Delete Member Page

    }elseif($action == 'Active'){ // Activate Member Paage
        
    } else{
        header("Location: members.php");
        exit();
    }

    include($tpls . 'footer.php');