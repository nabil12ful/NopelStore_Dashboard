<?php
############################################################
#|                      Check Item Exist                  |#
#|         This function check Is Item Exist in DB        |#
#|                           v2.0                         |#
#| $item     = Like User, Item, Category, Order           |#
#| $colName  = Name Of Column In Table                    |#
#| $table    = Name Of Table In Database                  |#
#| $condition= Like (ID !=) EX: "ID =" Or "Username !="   |#
#| $valCond  = EX: "1" Like ID || "user" Like Username    |#
############################################################
function checkItem($item, $colName, $table, $condition = NULL, $valCond = NULL){
    global $con;
    if($condition === NULL && $valCond === NULL){
        $stmt = $con->prepare("SELECT $colName FROM $table WHERE $colName = ?");
        $stmt->execute(array($item));
    }else{
        $stmt = $con->prepare("SELECT $colName FROM $table WHERE $colName = ? AND $condition ?");
        $stmt->execute(array($item, $valCond));
    }
    return $stmt->rowCount();
}

/*####################################################################
** Redirect Function [ This Function Accept Parameters ] v3.0
** $url      = Page You Want Be To Redirect "Home Page" By Default
** $Msg      = Echo The Message [ Error | Success | Warning | Info ]
** $sec      = Seconds Before Redirecting "3" Seconds By Default
** $type     = Type Of Msg Error OR Success
*/####################################################################

function redirect($Msg, $url = NULL, $sec = 3, $type = "error"){
    if($url === NULL){
        $url = "index.php";
    }else{
        $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== "" ? $_SERVER['HTTP_REFERER'] : "index.php";
    }
    if($type == "error"){
        echo "<h5 class='text-center alert alert-danger'>$Msg</h5>";
    }elseif($type == "success"){
        echo "<h5 class='text-center alert alert-success'>$Msg</h5>";
    }
    
    echo "<h5 class='text-center alert alert-info'>You Will Be Redirected Automatecaly After $sec Seconds. <a href='$url' class='alert-link'>Click Here To Back</a></div>";
    header("refresh:$sec;url=$url");
    exit();
}

##################################################
#   GetAge Function v1.0
#   $birthdate = Birthdate
#   require Date Is yyyy-mm-dd
##################################################

function getAge($birthdate){
    $today = date("Y-m-d");
    $age = date_diff(date_create($birthdate), date_create($today));
    return $age->format('%y Years %m Months %d Days');
}

##################################################
#   Upload Images Function v2.0
#   $images = array of files you want to uploaded
#   $folder = Folder Name of Upload Dir
#   $maxSize = Max MB Size For Every File Uploaded 
#   $allow_exts = Type Files You Want To Uploaded
##################################################

function uploadMultiImages($images, $folder, $maxFiles, $maxSize = 6, $allow_exts = array('jpeg', 'jpg', 'png', 'gif')){
    global $uploads;
    if(empty($folder)){
        $location = $uploads . "Undefine/";
    }else{
        if(!file_exists($uploads . $folder)){
            mkdir($uploads . $folder);
        }
        $location = $uploads . $folder . "/";
    }
    
    $allImgs   = array();
    $allow_ext = $allow_exts;
    $count     = count($images['name']);
    $errors    = array();
    $maxSizeKB = 1048576 * $maxSize;
    $temps     = array();
    if($count <= $maxFiles){
        for($i = 0; $i < $count; $i++){
        $name = $images['name'][$i];
        $size = $images['size'][$i];
        $type = $images['type'][$i];
        $temp = $images['tmp_name'][$i];
        $erro = $images['error'][$i];
        if($erro == 0){
            $ext = explode("/", $type);
            $ext = strtolower(end($ext));
            $newName = uniqid('', true) . '.' . $ext;
            if(in_array($ext, $allow_ext)){
                if($size <= $maxSizeKB){
                    $allImgs[] = $newName;
                    $temps[]   = $temp;
                }else{
                    $errors[] = "File number ".($i+1)." cant be more than $maxSize MB";
                }
            }else{
                $errors[] = "File Number " . ($i+1) . " Not Allowed Extention";
            }
        }elseif($erro == 4){
            $msg = "Please Choose One Image Or More.";
            redirect($msg, "back", 4);
        }
        }
        if(count($allImgs) == $count){
            //print_r($allImgs);
            $successUploaded = 0;
            $success         = array();
            for($i = 0; $i < $count; $i++){
                if(move_uploaded_file($temps[$i], $location . $allImgs[$i])){
                    $successUploaded = $i + 1;
                    $success[] = $location . $allImgs[$i];
                }else{
                    echo "<h5 class='text-center alert alert-danger'>File number ".($i+1)." has not uploaded</h5>";
                }
            }
            if($successUploaded == $count){
                echo "<h5 class='text-center alert alert-success'>Uploaded All Files Successfully.</h5>";
                return $success;
            }
        }else{
            foreach($errors as $err){
                echo "<h5 class='text-center alert alert-danger'>$err</h5>";
            }
            redirect("Please Try Again.", "back", 10);
        }
    }else{
        $msg = "You Are Selected $count Files. Please Select Files Less Than " . $maxFiles + 1 . ".";
        redirect($msg, "back", 6);
    }
}

##########################################
#   getCount FUNC v1.0                   #
#   $table = Table Name from DB          #
##########################################

function getCount($table){
    global $con;
    $stmt = $con->prepare("SELECT * FROM $table");
    $stmt->execute();
    $rows = $stmt->rowCount();
    return $rows;
}

###########################################
#   getStringOfNum FUNC v1.0              #
#   $num = Number                         #
#   This Function return Big Numbers With #
#   String Like 1000 = 1K And 1 Million   #
#   equal 1M                              #
###########################################

function getStringOfNum($num){
    $len = strlen($num);
    $oneK = 1000 - 1;
    $oneM = 1000000 - 1;
    if($num > $oneK && $len == 4){
        $num = substr($num, 0, 1) . "K";
    }elseif($num < $oneM && $len == 5){
        $num = substr($num, 0, 2) . "K";
    }elseif($num < $oneM && $len == 6){
        $num = substr($num, 0, 3) . "K";
    }elseif($num > $oneM && $len == 7){
        $num = substr($num, 0, 1) . "." . substr($num, 1, 1) . "M";
    }elseif($num > $oneM && $len == 8){
        $num = substr($num, 0, 2) . "." . substr($num, 1, 1) . "M";
    }elseif($num > $oneM && $len == 9){
        $num = substr($num, 0, 3) . "." . substr($num, 1, 1) . "M";
    }elseif($num > $oneM && $len == 10){
        $num = substr($num, 0, 1) . "." . substr($num, 1, 1) . "B";
    }elseif($num > $oneM && $len == 11){
        $num = substr($num, 0, 2) . "." . substr($num, 1, 1) . "B";
    }elseif($num > $oneM && $len == 12){
        $num = substr($num, 0, 3) . "." . substr($num, 1, 1) . "B";
    }
    return $num;
}

################################################
#   getRecentItems FUNC v1.0                   #
#   $table = Table Name Of Database            #
#   $orderBy = Column Name You Want Order By   #
#   $count = Count Items You Want To Be Return #
################################################

function getRecentItems($table, $orderBy, $count = 5){
    global $con;
    $stmt = $con->prepare("SELECT * FROM $table ORDER BY $orderBy DESC LIMIT $count");
    $stmt->execute();
    return $stmt->fetchAll();
}

#################################################
#   getLastMonths FUNC v1.0
#   $count = Count Of Months Returned
#   $array = Array Of Months
#################################################

function getLastMonths($count, $array){
    if($count > 0 ){
        $thisTime      = getdate(time());
        $current_month = $thisTime['mon'];
        $newArray      = array();
        for($i=1; $i <= $count; $i++){
            if($current_month > 0){
                $month = $array[$current_month];
                if($i > 1){
                    $newArray[] = '"' . $month . '",';
                }
                elseif($i == 1){
                    $newArray[] = '"' . $month . '"';
                }
                $current_month--;
            }elseif($current_month == 0){
                $current_month = 12;
                $month = $array[$current_month];
                    $newArray[] = '"' . $month . '",';
                $current_month--;
            }
        }
        return array_reverse($newArray);
    }
    elseif($count <= 0){
        return array("You are insert " . $count . ", Please insert number more than 0.");
    }
}

################################################
#   getLastDays FUNC v1.0
#   $count = Count Of Days Returned
#   $array = Array Of Days
################################################

function getLastDays($count, array $array){
    if($count > 0){
        $thisDay = getdate(time());
        $current_day = $thisDay["weekday"];/* 
        $current_day = str_split($current_day, 3);
        $current_day = $current_day[0]; */
        $key = array_search($current_day, $array);
        $newDays = array();
        for($i =1; $i <= $count; $i++){
            if($key > 0){
                $day = $array[$key];
                if($i > 1){
                    array_push($newDays, '"' . $day . '",');
                }
                elseif($i == 1){
                    array_push($newDays, '"' . $day . '"');
                }
                $key--;
            }
            elseif($key == 0){
                $key = 7;
                $day = $array[$key];
                if($i > 1){
                    array_push($newDays, '"' . $day . '",');
                }
                elseif($i == 1){
                    array_push($newDays, '"' . $day . '"');
                }
                $key--;
            }
        }
        return array_reverse($newDays);
    }
    elseif($count <= 0){
        return array("You are insert " . $count . ", Please insert number more than 0.");
    }
}

################################################
#   getWeekSales FUNC v1.0                     #
#   Get all sales at last week                 #
################################################

function getWeekSales(){
    global $con;
    $data     = array();
    $d        = getdate(time());
    $date     = $d['year'] . "-" . $d['mon'] . $d['mday'];
    $fullDate = date('Y-m-d', strtotime($date));
    $dates    = array();
    for($i=0;$i < 7; $i++){
        $check = checkItem($fullDate, "Date", "orders");
        if($check > 0){
            $stmt = $con->prepare("SELECT SUM(order_details.Price) AS Price, Date FROM orders
                                    INNER JOIN order_details ON order_details.Order_ID = orders.ID
                                    WHERE DATE(Date) = DATE(?)");
            $stmt->execute(array($fullDate));
            $prices = $stmt->fetch();
            $data[] = $prices['Price'];
        }else{
            $data[] = 0;
        }
        $fullDate = date('Y-m-d', strtotime("-".($i + 1) ." days"));
        $dates[] = $fullDate;
    }
    return json_encode(array_reverse($data));
}

################################################
#   getMonthSales FUNC v1.0                    #
#   Get all sales at last Month                #
################################################

function getMonthSales(){
    global $con;
    $months = array();
    $monthsMinus = array();
    for($m = -1; $m < 6; $m++){
        $months[] = date("Y-m-d", strtotime(date('Y-m-1')." -$m months"));
    }
    for($m = 0; $m < 7; $m++){
        $monthsMinus[] = date("Y-m-d", strtotime(date('Y-m-1')." -$m months"));
    }
    $data = array();
    for($i = 0; $i < 7; $i++){
        $stmt = $con->prepare("SELECT SUM(order_details.Price) AS Price, Date FROM orders
                                INNER JOIN order_details ON order_details.Order_ID = orders.ID
                                WHERE DATE(Date) BETWEEN DATE(?) AND DATE(?)");
        $stmt->execute(array($monthsMinus[$i],$months[$i]));
        $prices = $stmt->fetch();
        if($prices['Price'] == NULL){
            $data [] = 0;
        }else{
            $data[] = $prices['Price'];
        }
    }
    return json_encode(array_reverse($data));
}

################################################
#   getMonthOrders FUNC v1.0                   #
#   Get all Orders at last Month               #
################################################

function getMonthOrders(){
    global $con;
    $months = array();
    $monthsMinus = array();
    for($m = -1; $m < 6; $m++){
        $months[] = date("Y-m-d", strtotime(date('Y-m-1')." -$m months"));
    }
    for($m = 0; $m < 7; $m++){
        $monthsMinus[] = date("Y-m-d", strtotime(date('Y-m-1')." -$m months"));
    }
    $data = array();
    for($i = 0; $i < 7; $i++){
        $stmt = $con->prepare("SELECT COUNT(ID) AS Orders, Date FROM orders
                                WHERE DATE(Date) BETWEEN DATE(?) AND DATE(?)");
        $stmt->execute(array($monthsMinus[$i],$months[$i]));
        $prices = $stmt->fetch();
        if($prices['Orders'] == NULL){
            $data [] = 0;
        }else{
            $data[] = $prices['Orders'];
        }
    }
    return json_encode(array_reverse($data));
}

################################################
#   getJsonUrl FUNC v1.0                       #
#   Get all data from JSON File                #
################################################


function getJsonUrl($url){
    $json = file_get_contents($url);
    $jo   = json_decode($json);
    $r    = "";
    $co   = count($jo);
    for($i = 0; $i < $co; $i++){
        if($i != $co - 1){
            $r = $r . $jo[$i] . ", ";
        }else{
            $r = $r . $jo[$i];
        }
    }
    echo $r;
}