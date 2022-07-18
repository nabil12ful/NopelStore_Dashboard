<?php
    ob_start();
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }
    $pageTitle = "Customers";
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
        $stmt = $con->prepare("SELECT * FROM customers $sort");
        $stmt->execute();
        $custs = $stmt->fetchAll();
        ?>
        <div class="products">
            <div class="pro-header">
                <!-- start products options -->
                <div class="bar">
                    <div class="options">
                        <ul>
                            <li>
                                <a href="?p=add" class="btn add"><i class="fas fa-plus-circle"></i> <span>Add New</span></a>
                            </li>
                        </ul>
                    </div>
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
                                <td>Image</td>
                                <td>Name</td>
                                <td>Age</td>
                                <td>Create Of</td>
                                <td>Username</td>
                                <td>E-Mail</td>
                                <td>Status</td>
                                <td>Options</td>
                                <td>Controls</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($custs as $cust){
                                echo "<tr>";
                                    echo "<td>".$cust['ID']."</td>";
                                    echo "<td class=\"pro-imgs\">";
                                        if(!empty($cust['Image'])){
                                            echo "<img src='".$cust['Image']."'>";
                                        }else{
                                            echo "<img src='layout/images/user.png'>";
                                        }
                                        
                                    echo "</td>";
                                    echo "<td>" . $cust['Full_Name'] . "</td>";
                                    echo "<td>" . getAge($cust['Birthdate']) . "</td>";
                                    echo "<td>" . $cust['DateOfCreate'] . "</td>";
                                    echo "<td>" . $cust['Username'] . "</td>";
                                    echo "<td>" . $cust['Email'] . "</td>";
                                    if($cust['RegStatus'] == 0){
                                        echo "<td>";
                                            echo "<div class='action my-1 bg-warning'>Unactivated</div>";
                                            if($cust['Blocked'] == 1){
                                                echo "<div class='action bg-danger'>Blocked</div>";
                                            }
                                        echo "</td>";
                                    }else{
                                        echo "<td>";
                                            echo "<div class='action my-1 bg-success'>Activated</div>";
                                            if($cust['Blocked'] == 1){
                                                echo "<div class='action bg-danger'>Blocked</div>";
                                            }
                                        echo "</td>";
                                    }
                                    echo "<td>";
                                        echo "<div class='contact'>";
                                            echo "<a href='?p=view&id=" . $cust['ID'] . "'>
                                                    <span class='fas fa-user-circle'></span>
                                                </a>";
                                            echo "<a href='?p=view&id=" . $cust['ID'] . "'>
                                                    <span class='fas fa-comment'></span>
                                                </a>";
                                            echo "<a href='?p=view&id=" . $cust['ID'] . "'>
                                                    <span class='fas fa-phone'></span>
                                                </a>";
                                        echo "</div>";
                                    echo "</td>";
                                    echo '<td>';
                                        echo '<a href="?p=edit&id=' . $cust['ID'] . '" class="btn btn-primary">Edit</a>';
                                        echo '<a href="?p=delete&id=' . $cust['ID'] . '" class="btn btn-danger">Delete</a>';
                                    echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
            
        </div>
        <div class="btns">
            <ul>
                <li>
                    <a href="" class="nav-btn">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </li>
                <li>
                    <a href="" class="nav-btn">1</a>
                </li>
                <li>
                    <a href="" class="nav-btn">2</a>
                </li>
                <li>
                    <a href="" class="nav-btn">3</a>
                </li>
                <li>
                    <a href="" class="nav-btn">4</a>
                </li>
                <li>
                    <a href="" class="nav-btn">
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </li>
            </ul>
        </div>

        <?php
    }elseif($p == "edit"){ // Edit Customer Data Page
        echo '<div class="profile-body">';
            echo '<h3>Edit Customer Data</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "id", "customers");
                    if($check > 0){
                        $stmt2 = $con->prepare("SELECT * FROM customers WHERE ID = ?");
                        $stmt2->execute(array($_GET['id']));
                        $row = $stmt2->fetch(); ?>
                            <form action="?p=update" method="POST" class="form-horizonal was-validated" novalidate>
                                <input type="hidden" name="id" value="<?php echo $row['ID'] ?>">
                                <!-- Start Name Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Full Name:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input type="text" name="name" class="form-control" value="<?php echo $row['Full_Name']; ?>" placeholder="Name Of Customer" required>
                                        <div class="invalid-feedback">Customer Name Is Required</div>
                                    </div>
                                </div>
                                <!-- End Name Field -->
                                <!-- Start Username Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Username:</label>
                                    <div class="col-sm-10 col-md-6 form-g input-group has-validation">
                                        <span class="input-group-text">@</span>
                                        <input type="text" name="username" class="form-control" value="<?php echo $row['Username']; ?>" placeholder="Username To Login" required>
                                        <div class="invalid-feedback">Customer Username Is Required</div>
                                    </div>
                                </div>
                                <!-- End Username Field -->
                                <!-- Start Email Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Email:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input type="text" name="email" class="form-control" value="<?php echo $row['Email']; ?>" placeholder="Email To Contact" required>
                                        <div class="invalid-feedback">Customer Email Is Required</div>
                                    </div>
                                </div>
                                <!-- End Email Field -->
                                <!-- Start Phone Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Phone:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input type="text" name="phone" class="form-control" value="<?php echo $row['Phone']; ?>" placeholder="Phone To Contact" required>
                                        <div class="invalid-feedback">Customer Phone Number Is Required</div>
                                    </div>
                                </div>
                                <!-- End Phone Field -->
                                <!-- Start Password Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Password:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>">
                                        <input type="password" name="password" class="form-control" placeholder="Leave Blank If You Don't Want To Change" autocomplete="new-password">
                                    </div>
                                </div>
                                <!-- End Password Field -->
                                <!-- Start Submit Button -->
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-2">
                                        <input type="submit" class="btn btn-primary" value="Update Data">
                                        <?php
                                        $id = $row['ID'];
                                        if($row['Blocked'] == 1){
                                            echo '<a href="?p=unblock&id=' . $id . '" class="btn btn-warning">Unblock</a>';
                                        }else{
                                            echo '<a href="?p=block&id=' . $id . '" class="btn btn-danger">Block</a>';
                                        }
                                        ?>
                                        
                                    </div>
                                </div>
                                <!-- End Submit Button -->
                            </form>
                        <?php
                    }else{
                        $msg = "Sorry, This User Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This User Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "update"){ // Update
        echo '<div class="profile-body">';
            echo '<h3>Update Customer Data</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            $id     = $_POST['id']; 
            $name   = $_POST['name']; 
            $user   = $_POST['username']; 
            $email  = $_POST['email']; 
            $phone  = $_POST['phone']; 
            // Password
            $pass = !empty($_POST['password']) ? $_POST['password'] : $_POST['oldpassword'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(is_numeric($id)){ 
                $check = checkItem($id, "id", "customers");
                if($check < 1){
                    $errors[] = "This User Is Not Exist.";
                }
            }

            if(empty($name)){ // Check If Name Field Is Empty
                $errors[] = "Full Name Can't Be <strong>Empty</strong>";
            }
            if(strlen($name) < 10){ // Check If Name Is Less Than 10 Char
                $errors[] = "Full Name Can't Be Less Than <strong>10 Characters</strong>";
            }
            if(strlen($name) > 25){ // Check If Name Is More Than 25 Char
                $errors[] = "Full Name Can't Be More Than <strong>25 Characters</strong>";
            }
            if(empty($user)){ // Check If Username Field Is Empty
                $errors[] = "Username Can't Be <strong>Empty</strong>";
            }
            if(strlen($user) < 4){ // Check If Username Is Less Than 4 Char
                $errors[] = "Username Can't Be Less Than <strong>4 Characters</strong>";
            }
            if(strlen($user) > 15){ // Check If Username Is More Than 15 Char
                $errors[] = "Username Can't Be More Than <strong>15 Characters</strong>";
            }
            $checkUser = checkItem($user, "Username", "customers", "ID !=", $id); // Check Username Is Exist In Database
            if($checkUser > 0){
                $errors[] = "This Username Is Already Exist In Database.";
            }
            if(empty($email)){ // Check If Email Field Is Empty
                $errors[] = "Email Can't Be <strong>Empty</strong>";
            }
            $checkEmail = checkItem($email, "Email", "customers", "ID !=", $id); // Check Email Is Exist In Database
            if($checkEmail > 0){
                $errors[] = "This Email Is Already Exist In Database.";
            }
            if(!is_numeric($phone)){ // Check If Phone Field Is Not Number
                $errors[] = "The Phone Number Contains An <strong>Characters Or Symbols</strong>";
            }
            if(empty($phone)){ // Check If Phone Field Is Empty
                $errors[] = "Phone Number Can't Be <strong>Empty</strong>";
            }
            if(strlen($phone) > 11){ // Check If Phone Number Is More Than 11 Num
                $errors[] = "The Phone Number Can't Be More Than <strong>11 Number</strong>";
            }
            if(strlen($phone) < 11){ // Check If Phone Number Is Less Than 11 Num
                $errors[] = "The Phone Number Can't Be Less Than <strong>11 Number</strong>";
            }
            $checkPhone = checkItem($phone, "Phone", "customers", "ID !=", $id); // Check Phone Is Exist In Database
            if($checkPhone > 0){
                $errors[] = "This Phone Number Is Already Exist In Database.";
            }
            if(!empty($_POST['password'])){ // Check If Password Field Is Not Empty
                if(strlen($_POST['password']) < 8){ // Check Password Is Less Than 8 Char
                    $errors[] = "Password Can't Be Less Than <strong>8 Characters</strong>";
                }
                $pass = sha1($pass);
            }
            
            // Echo Errors Is Exist
            foreach($errors as $err){
                echo "<h5 class='text-center alert alert-danger'>$err";
                    echo "<br><a href='customers.php?p=edit&id=$id' class='alert-link'> Back To Edit Customer Page</a>";
                echo "</h5>";
            }
            // Update Data In Database
            if(empty($errors)){
                $stmt = $con->prepare("UPDATE customers SET `Full_Name` = ?, Username = ?, Email = ?, Phone = ?, `Password` = ? WHERE ID = ?");
                $stmt->execute(array($name, $user, $email, $phone, $pass, $id));
                redirect("Updated Customer Data Successfully.", "back", 4, "success");
            }

        }else{
            $msg = "You Can't Browse This Page Directory.";
            redirect($msg, "back");
        }
        echo '</div>';
    }elseif($p == "add"){ // Add new Customer
        echo '<div class="profile-body">';
            echo '<h3>Add New Customer</h3><hr>';
            echo '<div class="user-info">'; ?>
                <form action="?p=insert" method="POST" class="form-horizonal needs-validation was-validated" novalidate>
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Full Name:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="name" class="form-control" placeholder="Name Of Customer" required>
                            <div class="invalid-feedback">Customer Name Is Required</div>
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Username:</label>
                        <div class="col-sm-10 col-md-6 form-g input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" name="username" class="form-control" placeholder="Username To Login" autocomplete="off" required>
                            <div class="invalid-feedback">Username is required</div>
                        </div>
                    </div>
                    <!-- End Username Field -->
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Email:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="email" class="form-control" placeholder="your@domain.com" required>
                            <div class="invalid-feedback">Customer Email Is Required</div>
                        </div>
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Phone Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Phone:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="phone" class="form-control" placeholder="Phone To Contact" required>
                            <div class="invalid-feedback">Customer Phone Number Is Required</div>
                        </div>
                    </div>
                    <!-- End Phone Field -->
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Password:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="password" name="password" class="form-control" placeholder="Password Required More Than 8 Char And Strong" autocomplete="new-password" required>
                            <div class="invalid-feedback">Customer Password Is Required</div>
                        </div>
                    </div>
                    <!-- End Password Field -->
                    <!-- Start Submit Button -->
                    <div class="form-group row">
                        <div class="col-sm-10 offset-2">
                            <input type="submit" class="btn btn-success btn-lg" value="Add New">
                        </div>
                    </div>
                    <!-- End Submit Button -->
                </form>
            <?php
            echo '</div>';
        echo '</div>';
    }elseif($p == "insert"){
        echo '<div class="profile-body">';
            echo '<h3>Insert New Customer</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            $name   = $_POST['name']; 
            $user   = $_POST['username']; 
            $email  = $_POST['email']; 
            $phone  = $_POST['phone']; 
            $pass   = $_POST['password'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(empty($name)){ // Check If Name Field Is Empty
                $errors[] = "Full Name Can't Be <strong>Empty</strong>";
            }
            if(strlen($name) < 10){ // Check If Name Is Less Than 10 Char
                $errors[] = "Full Name Can't Be Less Than <strong>10 Characters</strong>";
            }
            if(strlen($name) > 25){ // Check If Name Is More Than 25 Char
                $errors[] = "Full Name Can't Be More Than <strong>25 Characters</strong>";
            }
            if(empty($user)){ // Check If Username Field Is Empty
                $errors[] = "Username Can't Be <strong>Empty</strong>";
            }
            if(strlen($user) < 4){ // Check If Username Is Less Than 4 Char
                $errors[] = "Username Can't Be Less Than <strong>4 Characters</strong>";
            }
            if(strlen($user) > 15){ // Check If Username Is More Than 15 Char
                $errors[] = "Username Can't Be More Than <strong>15 Characters</strong>";
            }
            $checkUser = checkItem($user, "Username", "customers"); // Check Username Is Exist In Database
            if($checkUser > 0){
                $errors[] = "This Username Is Already Exist In Database.";
            }
            if(empty($email)){ // Check If Email Field Is Empty
                $errors[] = "Email Can't Be <strong>Empty</strong>";
            }
            $checkEmail = checkItem($email, "Email", "customers"); // Check Email Is Exist In Database
            if($checkEmail > 0){
                $errors[] = "This Email Is Already Exist In Database.";
            }
            if(!is_numeric($phone)){ // Check If Phone Field Is Not Number
                $errors[] = "The Phone Number Contains An <strong>Characters Or Symbols</strong>";
            }
            if(empty($phone)){ // Check If Phone Field Is Empty
                $errors[] = "Phone Number Can't Be <strong>Empty</strong>";
            }
            if(strlen($phone) > 11){ // Check If Phone Number Is More Than 11 Num
                $errors[] = "The Phone Number Can't Be More Than <strong>11 Number</strong>";
            }
            if(strlen($phone) < 11){ // Check If Phone Number Is Less Than 11 Num
                $errors[] = "The Phone Number Can't Be Less Than <strong>11 Number</strong>";
            }
            $checkPhone = checkItem($phone, "Phone", "customers"); // Check Phone Is Exist In Database
            if($checkPhone > 0){
                $errors[] = "This Phone Number Is Already Exist In Database.";
            }
            if(!empty($_POST['password'])){ // Check If Password Field Is Not Empty
                if(strlen($_POST['password']) < 8){ // Check Password Is Less Than 8 Char
                    $errors[] = "Password Can't Be Less Than <strong>8 Characters</strong>";
                }
            }
            
            // Insert Data In Database
            if(empty($errors)){ // Check If There Isn't Errors
                $passHashed = sha1($pass); // Encrypt Password
                $stmt = $con->prepare("INSERT INTO customers(Full_Name, Username, Email, Phone, Password, DateOfCreate)
                                                    VALUES (:name, :user, :email, :phone, :pass, now())");
                $stmt->execute(array(
                    "name" => $name,
                    "user" => $user,
                    "email"=> $email,
                    "phone"=> $phone,
                    "pass" => $passHashed
                ));
                $msg = "Added New Customer Successfully.";
                redirect($msg, "back", 5, "success");
            }else{
                // Echo Errors Is Exist
                foreach($errors as $err){
                    echo "<h5 class='text-center alert alert-danger'>$err";
                    echo "</h5>";
                }
                redirect("There Is Errors.", "back", 15);
            }

        }else{
            $msg = "You Can't Browse This Page Directory.";
            redirect($msg, "back");
        }
        echo '</div>';
    }elseif($p == "view"){
        echo '<div class="profile-body">';
            echo '<h3>View Customer Data</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "customers");
                    if($check > 0){
                        $stmt2 = $con->prepare("SELECT * FROM customers WHERE ID = ?");
                        $stmt2->execute(array($_GET['id']));
                        $row = $stmt2->fetch(); ?>
                            <!-- Start Name Field -->
                            <div class="form-group form-group-lg row">
                                <label for="" class="col-sm-2 col-form-label">Full Name:</label>
                                <div class="col-sm-10 col-md-6 form-g">
                                    <input type="text" name="name" class="form-control" readonly value="<?php echo $row['Full_Name']; ?>" placeholder="Name Of Customer" required>
                                </div>
                            </div>
                            <!-- End Name Field -->
                            <!-- Start Username Field -->
                            <div class="form-group form-group-lg row">
                                <label for="" class="col-sm-2 col-form-label">Username:</label>
                                <div class="col-sm-10 col-md-6 form-g input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" name="username" class="form-control" readonly value="<?php echo $row['Username']; ?>" placeholder="Username To Login" required>
                                </div>
                            </div>
                            <!-- End Username Field -->
                            <!-- Start Email Field -->
                            <div class="form-group form-group-lg row">
                                <label for="" class="col-sm-2 col-form-label">Email:</label>
                                <div class="col-sm-10 col-md-6 form-g">
                                    <input type="text" name="email" class="form-control" readonly value="<?php echo $row['Email']; ?>" placeholder="Email To Contact" required>
                                </div>
                            </div>
                            <!-- End Email Field -->
                            <!-- Start Phone Field -->
                            <div class="form-group form-group-lg row">
                                <label for="" class="col-sm-2 col-form-label">Phone:</label>
                                <div class="col-sm-10 col-md-6 form-g">
                                    <input type="text" name="phone" class="form-control" readonly value="<?php echo $row['Phone']; ?>" placeholder="Phone To Contact" required>
                                </div>
                            </div>
                            <!-- End Phone Field -->
                            <!-- Start Address Field -->
                            <div class="form-group form-group-lg row">
                                <label for="" class="col-sm-2 col-form-label">Address:</label>
                                <div class="col-sm-10 col-md-6 form-g">
                                    <input type="text" name="address" class="form-control" placeholder="Address" value="<?php echo $row['Address']; ?>" readonly>
                                </div>
                            </div>
                            <!-- End Address Field -->
                            <!-- Start Birthdate Field -->
                            <div class="form-group form-group-lg row">
                                <label for="" class="col-sm-2 col-form-label">Birthdate:</label>
                                <div class="col-sm-10 col-md-6 form-g">
                                    <input type="date" name="birthdate" class="form-control" placeholder="Birthdate" value="<?php echo $row['Birthdate']; ?>" readonly>
                                </div>
                            </div>
                            <!-- End Birthdate Field -->
                            <!-- Start Age Field -->
                            <div class="form-group form-group-lg row">
                                <label for="" class="col-sm-2 col-form-label">Age:</label>
                                <div class="col-sm-10 col-md-6 form-g">
                                    <input type="text" name="age" class="form-control" placeholder="Age" value="<?php echo getAge($row['Birthdate']); ?>" readonly>
                                </div>
                            </div>
                            <!-- End Age Field -->
                            <!-- Start RegStatus Field -->
                            <div class="form-group form-group-lg row">
                                <label for="" class="col-sm-2 col-form-label">RegStatus:</label>
                                <div class="col-sm-10 col-md-6 form-g">
                                    <input type="text" name="status" class="form-control" placeholder="Reg Status" value="<?php if($row['RegStatus'] == 0){echo "Unactivate";}else{echo "Activated";} ?>" readonly>
                                </div>
                            </div>
                            <!-- End RegStatus Field -->
                            <!-- Start Blocked Field -->
                            <div class="form-group form-group-lg row">
                                <label for="" class="col-sm-2 col-form-label">Blocked:</label>
                                <div class="col-sm-10 col-md-6 form-g">
                                    <input type="text" name="block" class="form-control" placeholder="Blocked user" value="<?php if($row['Blocked'] == 0){echo "No";}else{echo "Yes";} ?>" readonly>
                                </div>
                            </div>
                            <!-- End Blocked Field -->
                            <!-- Start Control Buttons -->
                            <div class="form-group row">
                                <div class="col-sm-10 offset-2">
                                    <a href="?p=edit&id=<?php echo $row['ID']; ?>" class="btn btn-primary">Edit</a>
                                    <?php
                                        $id = $row['ID'];
                                        if($row['Blocked'] == 1){
                                            echo '<a href="?p=unblock&id=' . $id . '" class="btn btn-warning">Unblock</a>';
                                        }else{
                                            echo '<a href="?p=block&id=' . $id . '" class="btn btn-danger">Block</a>';
                                        }
                                        ?>
                                </div>
                            </div>
                            <!-- End Control Buttons -->
                        <?php
                    }else{
                        $msg = "Sorry, This User Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This User Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "delete"){
        echo '<div class="profile-body">';
            echo '<h3>Delete Customer</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "customers");
                    if($check > 0){
                        $stmt2 = $con->prepare("DELETE FROM customers WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $msg = ' Customer Deleted Successfully';
                        redirect($msg, "back", 3, "success");
                    }else{
                        $msg = "Sorry, This User Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This User Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "block"){
        echo '<div class="profile-body">';
            echo '<h3>Block Customer</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "customers");
                    if($check > 0){
                        $stmt2 = $con->prepare("UPDATE customers SET Blocked = 1 WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $msg = ' Customer Blocked Successfully';
                        redirect($msg, "back", 3, "success");
                    }else{
                        $msg = "Sorry, This User Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This User Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "unblock"){
        echo '<div class="profile-body">';
            echo '<h3>Unblock Customer</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "customers");
                    if($check > 0){
                        $stmt2 = $con->prepare("UPDATE customers SET Blocked = 0 WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $msg = ' Customer Unblocked Successfully';
                        redirect($msg, "back", 3, "success");
                    }else{
                        $msg = "Sorry, This User Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This User Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "active"){
        echo '<div class="profile-body">';
            echo '<h3>Activate Customer</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "customers");
                    if($check > 0){
                        $stmt2 = $con->prepare("UPDATE customers SET RegStatus = 1 WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $msg = ' Customer Activated Successfully';
                        redirect($msg, "back", 3, "success");
                    }else{
                        $msg = "Sorry, This User Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This User Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }
    else{
        $msg = "Sorry, Not Found This Page";
        redirect($msg, "back");
    }
    include($temps . "footer.php");
    ob_end_flush();

?>