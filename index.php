<?php
session_start();
if(!isset($_SESSION['id'])){
    header("Location: login.php");
}
$pageTitle = "Dashboard";
include("init.php"); 

# Count Products
$products = 4;
# Count Customers
$customer = 5;
# Months Name Of Year
$month = array(
    1  => "Jan", 2  => "Feb",
    3  => "Mar", 4  => "Apr",
    5  => "May", 6  => "Jun",
    7  => "Jul", 8  => "Aug",
    9  => "Sep", 10 => "Oct",
    11 => "Nov", 12 => "Dec"
);
# Days Name Of Week
$days = array(
    1 => "Monday", 2 => "Tuesday",
    3 => "Wednesday", 4 => "Thursday",
    5 => "Friday", 6 => "Saturday",
    7 => "Sunday"
);
# Create Sales last 7 days In File JSON
$json = GetWeekSales();
file_put_contents("salesWeek.json", $json);

# Create Sales last 7 months In File JSON
$MonthSalesJSON = GetMonthSales();
file_put_contents("salesMonthsAPI.json", $MonthSalesJSON);

# Create Orders last 7 months In File JSON
$MonthOrdersJSON = getMonthOrders();
file_put_contents("ordersMonthsAPI.json", $MonthOrdersJSON);
?>

    <!-- start cards -->
    <div class="cards">
        <div class="card-single bg-color-w">
            <div>
                <h1><?php echo getStringOfNum(getCount("customers")); ?></h1>
                <span><?PHP lang("CUSTCARD") ?></span>
            </div>
            <div class="cicle red">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="card-single bg-color-w">
            <div>
                <h1><?php echo getStringOfNum(getCount("products")); ?></h1>
                <span><?PHP lang("PRODCARD") ?></span>
            </div>
            <div class="cicle orange">
                <i class="fas fa-warehouse"></i>
            </div>
        </div>
        <div class="card-single bg-color-w">
            <div>
                <h1><?php echo getStringOfNum(getCount("orders")); ?></h1>
                <span><?PHP lang("ORDECARD") ?></span>
            </div>
            <div class="cicle info">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>
        <div class="card-single bg-color-w">
            <div>
                <h1>$<?php echo getStringOfNum(56); ?></h1>
                <span><?PHP lang("INCOCARD") ?></span>
            </div>
            <div class="cicle green">
                <i class="fas fa-money-bill-alt"></i>
            </div>
        </div>
    </div>
    <!-- end cards -->
    <!-- Start Recent Cards -->
    <div class="recent-grid">
        <!-- start recent products -->
        <div class="products">
            <div class="card-n">
                <div class="card-header">
                    <h4><?PHP lang("LAST") ?> <?php echo $products?> <?PHP lang("PRODCARD") ?></h4>
                    <button><a href="products.php"><?PHP lang("SEEALL") ?><i class="fa fa-arrow-right"></i></a></button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <td><?PHP lang("ID") ?></td>
                                    <td><?PHP lang("IMAGE") ?></td>
                                    <td><?PHP lang("TITLE") ?></td>
                                    <td><?PHP lang("BRAND") ?></td>
                                    <td><?PHP lang("CATEGORY") ?></td>
                                    <td><?PHP lang("PRICE") ?></td>
                                    <td><?PHP lang("BY-EMP") ?></td>
                                    <td><?PHP lang("STATUS") ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    //$rows = getRecentItems("products", "ID", $products);
                                    $stmt2 = $con->prepare(" SELECT products.*, product_image.Image_Path, categories.Name, employee.Full_Name
                                                            FROM products
                                                            INNER JOIN product_image ON product_image.Product_ID = products.ID
                                                            INNER JOIN categories ON categories.ID = products.Category
                                                            INNER JOIN employee ON employee.ID = products.BY_Emp
                                                            WHERE product_image.Flag = 1
                                                            ORDER BY ID DESC LIMIT $products");
                                    $stmt2->execute();
                                    $rows = $stmt2->fetchAll();
                                    if(count($rows) > 0){
                                        foreach($rows AS $row){
                                            $id = $row['ID'];
                                            $img = $row['Image_Path']; 
                                            $title = $row['Title'];
                                            $brand = $row['Brand'];
                                            $cat = $row['Name'];
                                            $price = $row['Price'];
                                            $emp = $row['Full_Name'];
                                            $status = $row['Status'];
                                            echo "<tr>";
                                                echo "<td>$id</td>";
                                                echo "<td class='pro-imgs'>
                                                        <img src='$img'>
                                                    </td>";
                                                echo "<td>$title</td>";
                                                echo "<td>$brand</td>";
                                                echo "<td>$cat</td>";
                                                echo "<td>$price</td>";
                                                echo "<td>$emp</td>";
                                                echo "<td>";
                                                    if($status == 0){
                                                        echo '<span class="status unavailable"></span>
                                                        Unavailable';
                                                    }else{
                                                        echo '<span class="status available"></span>
                                                        Available';
                                                    }
                                                echo "</td>";
                                            echo "</tr>";
                                        }
                                    }else{
                                        echo "<tr><div class='text-gray text-center'>No Products Yet..</div></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end recent products -->
        <!-- start recent customers -->
        <div class="customers">
            <div class="card-n">
                <div class="card-header">
                    <h4><?PHP lang("LAST") ?> <?php echo $customer?> <?PHP lang("CUSTCARD") ?></h4>
                    <button><a href="customers.php"><?PHP lang("SEEALL") ?> <i class="fa fa-arrow-right"></i></a></button>
                </div>
                <div class="card-body">
                    <?php
                    $rows = getRecentItems("customers", "ID", $customer);
                    if(count($rows) == 0){
                        echo "<div class='text-info text-center'>Not Found Any Customers</div>";
                    }else{
                        foreach($rows AS $row){
                            $id = $row['ID'];
                            $name = $row['Full_Name']; ?>
                            <div class="customer">
                                <div class="info">
                                    <img src="<?php echo $imgs ?>user.png" alt="<?php echo $name ?>" width="40px" height="40px">
                                    <div>
                                        <h4><?php echo $name ?></h4>
                                        <small><?PHP lang("CUSTOMER") ?></small>
                                    </div>
                                </div>
                                <div class="contact">
                                    <a href="customers.php?p=view&id=<?php echo $id ?>">
                                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                                    </a>
                                    <a href="">
                                        <i class="fa fa-comment" aria-hidden="true"></i>
                                    </a>
                                    <a href="">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- end recent products -->
    </div>
    <!-- End Recent Cards -->
    <!-- Start Charts -->
    <div class="recent-grid">
        <!-- Start Sales Chart -->
        <div class="chart-sales">
            <?php
            // $jsonArray = array();
            // for($i = 1; $i <= 7; $i++){
            //     $jsonArray[] = $i + rand(60,800);
            // }
            //echo $jsonArray[0];
            ?>
            <!-- Start Header -->
            <div class="chart-header">
                <div class="col-n">
                    <h6>Overview</h6>
                    <h3>Sales Value</h3>
                </div>
                <div class="col-n">
                    <ul class="nav nav-pills justify-content-end">
                        <li class="nav-item" data-toggle="chart" data-target="#chart-sales-dark" data-update='{"data":{"labels":[<?php foreach(getLastMonths(7, $month) as $mon){echo $mon;} ?>],"datasets":[{"data":[<?php getJsonUrl("salesMonthsAPI.json")?>]}]}}' data-prefix="EGP " data-suffix="">
                            <a href="#" class="link active" data-toggle="tab">
                                <span class="d-none d-md-block">Month</span>
                                <span class="d-md-none">M</span>
                            </a>
                        </li>
                        <li class="nav-item" data-toggle="chart" data-target="#chart-sales-dark" data-update='{"data":{"labels":[<?php foreach(getLastDays(7, $days) as $day){echo $day;} ?>],"datasets":[{"data":[<?php getJsonUrl("salesWeek.json") ?>]}]}}' data-prefix="EGP " data-suffix="">
                            <a href="#" class="link" data-toggle="tab">
                                <span class="d-none d-md-block">Week</span>
                                <span class="d-md-none">W</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- End Header -->
            <!-- Start Body -->
            <div class="chart-body">
                <div class="chart">
                    <!-- chart wrapper -->
                    <canvas id="chart-sales-dark" class="chart-canvas"></canvas>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Sales Chart -->
        <!-- Start Orders Chart -->
        <!-- chart 2 -->
        <div class="bar-chart">
            <div class="chart-header">
                <div class="col-n">
                    <h6>Performance</h6>
                    <h3>Total orders</h3>
                </div>
            </div>
            <div class="chart-body">
                <div class="chart">
                    <canvas id="chart-bars" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
        <!-- End Orders Chart -->
    </div>
    <!-- End Charts -->

<?php
include($temps . "footer.php");