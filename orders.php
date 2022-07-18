<?php
    ob_start();
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }
    $pageTitle = "Orders";
    include('init.php');

    $p = isset($_GET['p']) ? $_GET['p'] : "manage";

    if($p == "manage"){ // Manage page
        $sorts = array('ASC', 'DESC');
        $sort = "ORDER BY ID ASC";
        $so = "ASC";
        if(isset($_GET['sort']) && in_array($_GET['sort'], $sorts)){
            $sort = "ORDER BY ID " . $_GET['sort'];
            $so = $_GET['sort'];
        }else{
            $so = "ASC";
        }
        $stmt = $con->prepare("SELECT orders.*, customers.Full_Name FROM `orders`
                                INNER JOIN customers ON customers.ID = orders.Customer_ID $sort");
        $stmt->execute();
        $orders = $stmt->fetchAll();
        
        ?>
        <div class="products">
            <div class="pro-header">
                <!-- start products options -->
                <div class="bar">
                    <!-- <div class="options">
                        <ul>
                            <li>
                                <a href="?p=add" class="btn add"><i class="fas fa-plus-circle"></i> <span>Add New</span></a>
                            </li>
                        </ul>
                    </div> -->
                    <div class="filter">
                        Sort: 
                        <a href="?sort=ASC" <?php if($so == 'ASC'){echo "class='active'";} ?> ><i class="fa fa-arrow-circle-up"></i> ASC</a> | 
                        <a href="?sort=DESC" <?php if($so == 'DESC'){echo "class='active'";} ?>><i class="fa fa-arrow-circle-down"></i> DESC</a>
                </div>
            </div>
            <div class="pro-body text-center">
                <div class="table responsive">
                    <table class="main-table text-center table">
                        <thead>
                            <tr>
                                <td>#ID</td>
                                <td>Customer Name</td>
                                <td>Products</td>
                                <td>Address</td>
                                <td>Amount</td>
                                <td>Quantity</td>
                                <td>Date</td>
                                <td>Time</td>
                                <td>Status</td>
                                <td>Controls</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($orders as $order){
                                echo "<tr>";
                                    echo "<td>" . $orderID = $order['ID'] . "</td>";
                                    echo "<td>" . $order['Full_Name'] . "</td>";
                                    // Start products
                                    echo "<td>";
                                        $stmtPro = $con->prepare("SELECT order_details.*, products.Title FROM order_details INNER JOIN products ON products.ID = order_details.Product_ID WHERE Order_ID = ?");
                                        $stmtPro->execute(array($orderID));
                                        foreach($stmtPro->fetchAll() as $pro){
                                            echo $pro['Title'] . "<br>";
                                        }
                                    echo "</td>";
                                    // End Products
                                    echo "<td>" . $order['Address'] . "</td>";
                                    // Start Amount
                                    echo "<td>";
                                        $stmtAmount = $con->prepare("SELECT SUM(Price) AS Amount FROM order_details WHERE Order_ID = ?");
                                        $stmtAmount->execute(array($orderID));
                                        $price = $stmtAmount->fetch();
                                        echo $price['Amount'];
                                    echo "</td>";
                                    // End Amount
                                    // Start Quantity
                                    echo "<td>";
                                        $stmtAmount = $con->prepare("SELECT SUM(Count) AS Count FROM order_details WHERE Order_ID = ?");
                                        $stmtAmount->execute(array($orderID));
                                        $Qty = $stmtAmount->fetch();
                                        echo $Qty['Count'];
                                    echo "</td>";
                                    // End Quantity
                                    if($order['Time']){
                                        $time = explode(":", $order['Time']);
                                        if($time[0] >= 12){
                                            $time = ($time[0] - 12) . ":" . $time[1] . " PM";
                                            $time = explode(":", $time);
                                            if(strlen($time[0]) == 2){
                                                $time = $time[0] . ":" . $time[1];
                                            }else{
                                                $time = "0" . $time[0] . ":" . $time[1];
                                            }
                                        }elseif($time[0] == "00"){
                                            $time = "12:" . $time[1] . " AM";
                                        }else{
                                            $time = $time[0] . ":" . $time[1] . " AM";
                                        }
                                        
                                    }
                                    echo "<td>" . $order['Date'] . "</td>";
                                    echo "<td>" . $time . "</td>";
                                    if($order['Status'] == 0){
                                        echo "<td>";
                                            echo "<span class='status new'></span> New";
                                        echo "</td>";
                                    }elseif($order['Status'] == 1){
                                        echo "<td>";
                                            echo "<span class='status pending'></span> Pending";
                                        echo "</td>";
                                    }elseif($order['Status'] == 2){
                                        echo "<td>";
                                            echo "<span class='status available'></span> Done";
                                        echo "</td>";
                                    }elseif($order['Status'] == 3){
                                        echo "<td>";
                                            echo "<span class='status unavailable'></span> Rejected";
                                        echo "</td>";
                                    }
                                    echo '<td>';
                                        echo '<a href="?p=view&id=' . $order['ID'] . '" class="btn btn-primary"> <i class="fa fa-eye"></i> View</a>';
                                        echo '<a href="?p=delete&id=' . $order['ID'] . '" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>';
                                    echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php
    }elseif($p == "view"){ // View Order Details
        echo '<div class="profile-body bg-color-w color-n">';
            echo '<h3> <i class="fa fa-shopping-bag"></i> Order Details</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "orders");
                    if($check > 0){
                        $stmt2 = $con->prepare("SELECT orders.*, order_details.*, customers.Full_Name, customers.ID AS CID FROM orders
                                                INNER JOIN order_details ON order_details.Order_ID = orders.ID
                                                INNER JOIN customers ON customers.ID = orders.Customer_ID
                                                WHERE orders.ID = ?");
                        $stmt2->execute(array($_GET['id']));
                        $row = $stmt2->fetch(); ?>
                            <!-- Start Status Section -->
                            <div class="row">
                                <div class="col-sm-2">
                                    <h4>Status:</h4>
                                </div>
                                <div class="col-sm-4">
                                    <?php
                                    if($row['Status'] == 0){
                                        echo '<h4 class="text-info">New</h4>';
                                    }elseif($row['Status'] == 1){
                                        echo '<h4 class="text-warning">Pending</h4>';
                                    }elseif($row['Status'] == 2){
                                        echo '<h4 class="text-success">Done</h4>';
                                    }elseif($row['Status'] == 3){
                                        echo '<h4 class="text-danger">Rejected</h4>';
                                    }
                                    ?>
                                    
                                </div>
                            </div><hr>
                            <!-- Ent Status Section -->
                            <!-- Start Order Information -->
                            <div class="row-n">
                                <h4 class="tit">Order Information</h4>
                                <hr class="w-25 bg-primary">
                                <div class="col-n-3">
                                    <h5>Customer Name:</h5>
                                    <h6 class="text-primary"><a href="customers.php?p=view&id=<?php echo $row['CID'] ?>"><?php echo $row['Full_Name'] ?></a></h6>
                                </div>
                                <div class="col-n-3">
                                    <h5>Address:</h5>
                                    <h6 class="text-primary"><?php echo $row['Address'] ?></h6>
                                </div>
                                <div class="col-n-3">
                                    <h5>Date & Time:</h5>
                                    <?php
                                        if($row['DateTime']){
                                            $dateTime = explode(" ", $row['DateTime']);
                                            $date = $dateTime[0];
                                            $time = explode(":", $dateTime[1]);
                                            if($time[0] >= 12){
                                                $time = ($time[0] - 12) . ":" . $time[1] . " PM";
                                            }else{
                                                $time = $time[0] . ":" . $time[1] . " AM";
                                            }
                                        }
                                    ?>
                                    <h6 class="text-primary"><?php echo $date . " <span class='text-danger'>||</span> " . $time;?></h6>
                                </div>
                                <div class="col-n-3">
                                    <h5>Total Price:</h5>
                                    <?php
                                        $stmtAmount = $con->prepare("SELECT SUM(Price) AS Total FROM order_details WHERE Order_ID = ?");
                                        $stmtAmount->execute(array($row['ID']));
                                        $total = $stmtAmount->fetch();
                                    ?>
                                    <h6 class="text-primary"><?php echo $total['Total']; ?> EGP</h6>
                                </div>
                            </div>
                            <hr>
                            <!-- End Order Information -->
                            <!-- Start Products -->
                            <div class="row-n">
                                <h4 class="tit">Products</h4>
                                <hr class="w-25 bg-danger">
                                <div class="col-n-12">
                                    <?php
                                        //$stmtP = $con->prepare("SELECT products.Title, product_image.Path FROM products INNER JOIN products ON ");
                                        $stmtPro = $con->prepare("SELECT order_details.*, products.Title, product_image.Image_Path FROM order_details 
                                                                INNER JOIN products ON products.ID = order_details.Product_ID
                                                                INNER JOIN product_image ON order_details.Product_ID = product_image.Product_ID
                                                                WHERE Order_ID = ? AND Flag = 1");
                                        $stmtPro->execute(array($row['ID']));
                                        foreach($stmtPro->fetchAll() as $pro){
                                            echo '<div class="product">';
                                                echo '<img src="' . $pro['Image_Path'] . '" alt="" width="150px" height="150px">';
                                                echo '<h6>' . $pro['Title'] . '</h6>';
                                                echo '<span class="count">' . $pro['Count'] . '</span>';
                                                echo '<h6>Price: ' . $pro['Price'] . '</h6>';
                                            echo '</div>';
                                        }
                                    ?>
                                </div>
                            </div>
                            <!-- End Products -->
                            <!-- Start Discount -->
                            <div class="offer">
                                <div class="row-n">
                                    <div class="col-n-4">
                                        <span class="reqq">Is There A Discount?</span>
                                    </div>
                                    <div class="col-n-4">
                                        <input type="text" value="<?php echo $row['Coupon'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="row-n">
                                    <div class="col-n-4">
                                        <span class="reqq">Toatal Price After Discount</span>
                                    </div>
                                    <div class="col-n-4">
                                        <?php
                                            $disc = $total['Total'] * $row['Discount'] / 100;
                                        ?>
                                        <input type="text" value="<?php echo $total['Total'] - $disc; ?>" disabled>
                                        <span class="reqq">EGP</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- End Discount -->
                            <!-- Start Actions -->
                            <div class="row">
                                <?php
                                if($row['Status'] == 0){
                                    echo '<div class="col-sm-2">
                                            <a href="?p=pending&id=' . $row['ID'] . '" class="btn btn-warning">Pending</a>
                                        </div>';
                                }elseif($row['Status'] == 1){
                                    echo '<a href="?p=complete&id=' . $row['ID'] . '" class="btn btn-success">Complete</a>';
                                }
                                if($row['Status'] == 3){
                                    echo '<div class="col-sm-12 text-center">';
                                        echo "<h5 class='reqq text-danger'>Rejected</h5>";
                                    echo '</div>';
                                }else{
                                    echo '<div class="col-sm-2">
                                            <a href="?p=reject&id=' . $row['ID'] . '" class="btn btn-danger">Reject</a>
                                        </div>';
                                }
                                ?>
                            </div>
                            <!-- End Actions -->
                        <?php
                    }else{
                        $msg = "Sorry, This Category Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Category Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "delete"){
        echo '<div class="profile-body">';
            echo '<h3>Delete Order</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "orders");
                    if($check > 0){
                        $stmt2 = $con->prepare("DELETE FROM orders WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $stmt3 = $con->prepare("DELETE FROM order_details WHERE Order_ID = :id");
                        $stmt3->bindParam(":id", $_GET['id']);
                        $stmt3->execute();
                        $msg = ' Order Deleted Successfully';
                        redirect($msg, "back", 3, "success");
                    }else{
                        $msg = "Sorry, This Order Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Order Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "pending"){
        echo '<div class="profile-body">';
            echo '<h3>Pending Order</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "orders");
                    if($check > 0){
                        $stmt2 = $con->prepare("UPDATE orders SET Status = 1 WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $msg = ' Order Updated Successfully';
                        redirect($msg, "back", 3, "success");
                    }else{
                        $msg = "Sorry, This Order Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Order Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "complete"){
        echo '<div class="profile-body">';
            echo '<h3>Complete Order</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "orders");
                    if($check > 0){
                        $stmt2 = $con->prepare("UPDATE orders SET Status = 2 WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $msg = ' Order Updated Successfully';
                        redirect($msg, "back", 3, "success");
                    }else{
                        $msg = "Sorry, This Order Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Order Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "reject"){
        echo '<div class="profile-body">';
            echo '<h3>Reject Order</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "orders");
                    if($check > 0){
                        $stmt2 = $con->prepare("UPDATE orders SET Status = 3 WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $msg = ' Order Updated Successfully';
                        redirect($msg, "back", 3, "success");
                    }else{
                        $msg = "Sorry, This Order Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Order Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }else{
        $msg = "Sorry, Not Found This Page";
        redirect($msg, "back");
    }
    include($temps . "footer.php");
    ob_end_flush();

?>