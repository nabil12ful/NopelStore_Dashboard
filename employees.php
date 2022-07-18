<?php
    ob_start();
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }
    $pageTitle = "Employees";
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
        $stmt = $con->prepare("SELECT employee.*, sections.Name AS Department, roles.Name AS Job FROM employee
                                INNER JOIN sections ON sections.ID = employee.Section_ID 
                                INNER JOIN roles ON roles.ID = employee.Role_ID $sort");
        $stmt->execute();
        $emps = $stmt->fetchAll();
        ?>
        <div class="products">
            <div class="pro-header">
                <!-- start  options -->
                <div class="bar">
                    <div class="options">
                        <ul>
                            <li>
                                <a href="?p=add" class="btn add"><i class="fas fa-plus-circle"></i> <span><?PHP lang("ADDNEW") ?></span></a>
                            </li>
                            <li>
                                <a href="department.php" class="btn add"><i class="fas fa-plus-circle"></i> <?PHP lang("DEPARTMENT") ?></a>
                            </li>
                            <li>
                                <a href="role.php" class="btn add"><i class="fas fa-plus-circle"></i> <?PHP lang("ROLE") ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="filter">
                        Sort: 
                        <a href="?sort=ASC" <?php if($so == 'ASC'){echo "class='active'";} ?> ><i class="fa fa-arrow-circle-up"></i> ASC</a> | 
                        <a href="?sort=DESC" <?php if($so == 'DESC'){echo "class='active'";} ?>><i class="fa fa-arrow-circle-down"></i> DESC</a>
                    </div>
                </div>
            </div>
            <div class="pro-body text-center">
                <div class="table-responsive">
                    <table class="text-center table">
                        <thead>
                            <tr>
                                <td>#<?PHP lang("ID") ?></td>
                                <td><?PHP lang("IMAGE") ?></td>
                                <td><?PHP lang("NAME") ?></td>
                                <td><?PHP lang("AGE") ?></td>
                                <td><?PHP lang("USERNAME") ?></td>
                                <td><?PHP lang("EMAIL") ?></td>
                                <td><?PHP lang("DEPARTMENT") ?></td>
                                <td><?PHP lang("JOB") ?></td>
                                <td><?PHP lang("DATEHIRING") ?></td>
                                <td><?PHP lang("STATUS") ?></td>
                                <td><?PHP lang("OPTIONS") ?></td>
                                <td><?PHP lang("CONTROLS") ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($emps as $emp){
                                echo "<tr>";
                                    echo "<td>".$emp['ID']."</td>";
                                    echo "<td class=\"pro-imgs\">";
                                        echo "<img src='" . $imgs . "user.png'>";
                                    echo "</td>";
                                    echo "<td>" . $emp['Full_Name'] . "</td>";
                                    echo "<td>" . getAge($emp['Birthdate']) . "</td>";
                                    echo "<td>" . $emp['Username'] . "</td>";
                                    echo "<td>" . $emp['Email'] . "</td>";
                                    echo "<td>" . $emp['Department'] . "</td>";
                                    echo "<td>" . $emp['Job'] . "</td>";
                                    echo "<td>" . $emp['DateOfHiring'] . "</td>";
                                    if($emp['RegStatu'] == 0){
                                        echo "<td>";
                                            echo "<div class='action my-1 bg-warning text-dark'>";
                                                lang("UNACTIVATED");
                                            echo "</div>";
                                        echo "</td>";
                                    }else{
                                        echo "<td>";
                                            echo "<div class='action my-1 bg-success'>";
                                                lang("ACTIVATED");
                                            echo "</div>";
                                        echo "</td>";
                                    }
                                    echo "<td>";
                                        echo "<div class='contact'>";
                                            echo "<a href='?p=view&id=" . $emp['ID'] . "'>
                                                    <span class='fas fa-user-circle'></span>
                                                </a>";
                                            echo "<a href='?p=view&id=" . $emp['ID'] . "'>
                                                    <span class='fas fa-comment'></span>
                                                </a>";
                                            echo "<a href='?p=view&id=" . $emp['ID'] . "'>
                                                    <span class='fas fa-phone'></span>
                                                </a>";
                                        echo "</div>";
                                    echo "</td>";
                                    echo '<td>';
                                        echo '<a href="?p=edit&id=' . $emp['ID'] . '" class="btn btn-primary">
                                                <i class="fa fa-edit"></i> ';
                                            lang("EDIT");
                                        echo '</a>';
                                        echo '<a href="?p=delete&id=' . $emp['ID'] . '" class="btn btn-danger">';
                                            lang("DELETE");
                                        echo '</a>';
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
    }elseif($p == "edit"){ // Edit Page
        echo '<div class="profile-body">';
            echo '<h3>' . lang("EMP_EDIT_TITLE") . '</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "id", "employee");
                    if($check > 0){
                        $stmt2 = $con->prepare("SELECT * FROM employee WHERE ID = ?");
                        $stmt2->execute(array($_GET['id']));
                        $row = $stmt2->fetch();
                        ## Get Sections Data 
                        $stmt1 = $con->prepare("SELECT * FROM sections");
                        $stmt1->execute();
                        $sections = $stmt1->fetchAll();
                        ## Get Roles Data
                        $stmt1 = $con->prepare("SELECT * FROM roles");
                        $stmt1->execute();
                        $roles = $stmt1->fetchAll(); ?>
                            <form action="?p=update" method="POST" class="form-horizonal was-validated" novalidate>
                                <input type="hidden" name="id" value="<?php echo $row['ID'] ?>">
                                <!-- Start Name Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label"><?php lang("FULL_NAME") ?>:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input 
                                            type="text" 
                                            name="name" 
                                            class="form-control" 
                                            value="<?php echo $row['Full_Name']; ?>" 
                                            placeholder="Name Of Employee" 
                                            required>
                                        <div class="invalid-feedback"><?php lang("EMP_NAME_REQ") ?></div>
                                    </div>
                                </div>
                                <!-- End Name Field -->
                                <!-- Start Username Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label"><?php lang("USERNAME") ?>:</label>
                                    <div class="col-sm-10 col-md-6 form-g input-group has-validation">
                                        <span class="input-group-text">@</span>
                                        <input 
                                            type="text" 
                                            name="username" 
                                            class="form-control" 
                                            value="<?php echo $row['Username']; ?>" 
                                            placeholder="Username To Login"
                                            required>
                                        <div class="invalid-feedback"><?php lang("EMP_USER_REQ") ?></div>
                                    </div>
                                </div>
                                <!-- End Username Field -->
                                <!-- Start Email Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label"><?php lang("EMAIL") ?>:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input 
                                            type="email" 
                                            name="email" 
                                            class="form-control" 
                                            value="<?php echo $row['Email']; ?>" 
                                            placeholder="Email To Contact" 
                                            required>
                                        <div class="invalid-feedback"><?php lang("EMP_EMAIL-REQ") ?></div>
                                    </div>
                                </div>
                                <!-- End Email Field -->
                                <!-- Start Phone Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label"><?php lang("PHONE") ?>:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input 
                                            type="text" 
                                            name="phone" 
                                            class="form-control" 
                                            value="<?php echo $row['Phone']; ?>" 
                                            placeholder="Phone To Contact" 
                                            required>
                                        <div class="invalid-feedback"><?php lang("EMP_PHONE_REQ") ?></div>
                                    </div>
                                </div>
                                <!-- End Phone Field -->
                                <!-- Start Password Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label"><?php lang("PASSWORD") ?>:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input 
                                            type="hidden" 
                                            name="oldpassword" 
                                            value="<?php echo $row['Password']; ?>">
                                        <input 
                                            type="password" 
                                            name="password" 
                                            class="form-control" 
                                            placeholder="Leave Blank If You Don't Want To Change" 
                                            autocomplete="new-password">
                                    </div>
                                </div>
                                <!-- End Password Field -->
                                <!-- Start Country Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label"><?php lang("COUNTRY") ?>:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input 
                                            type="text" 
                                            name="country"
                                            class="form-control" 
                                            placeholder="Country Was Born"
                                            value="<?php echo $row['Country'] ?>"
                                            required>
                                        <div class="invalid-feedback"><?php lang("EMP_COUNTRY_REQ") ?></div>
                                    </div>
                                </div>
                                <!-- End Country Field -->
                                <!-- Start City Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label"><?php lang("CITY") ?>:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input 
                                            type="text" 
                                            name="city" 
                                            class="form-control" 
                                            placeholder="City Name"
                                            value="<?php echo $row['City'] ?>"
                                            required>
                                        <div class="invalid-feedback"><?php lang("EMP_CITY_REQ") ?></div>
                                    </div>
                                </div>
                                <!-- End City Field -->
                                <!-- Start Address Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Address:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input 
                                            type="text" 
                                            name="address" 
                                            class="form-control" 
                                            placeholder="Address Details.."
                                            value="<?php echo $row['Address'] ?>"
                                            required>
                                        <div class="invalid-feedback">Employee Address Is Required</div>
                                    </div>
                                </div>
                                <!-- End Address Field -->
                                <!-- Start Section Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Section:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <select name="section" class="form-control form-select" required>
                                            <option value="0"></option>
                                            <?php
                                            foreach($sections as $sec){?>
                                                <option value="<?php echo $sec['ID']; ?>" <?php if($row['Section_ID'] == $sec['ID']){echo "selected";} ?> ><?php echo $sec['Name']; ?></option>
                                            <?php } ?>
                                        </select>   
                                    </div>
                                </div>
                                <!-- End Section Field -->
                                <!-- Start Role Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Role:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <select name="role" class="form-control form-select" required>
                                            <option value="0"></option>
                                            <?php
                                            foreach($roles as $role){?>
                                                <option value="<?php echo $role['ID']; ?>" <?php if($row['Role_ID'] == $role['ID']){echo "selected";} ?> ><?php echo $role['Name']; ?></option>
                                            <?php } ?>
                                        </select>   
                                    </div>
                                </div>
                                <!-- End Role Field -->
                                <!-- Start Submit Button -->
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-2">
                                        <input type="submit" class="btn btn-primary" value="Update Data">
                                        <?php
                                        if($row['RegStatu'] == 0){
                                            echo '<a href="?p=active&id=' . $row['ID'] . '" class="btn btn-success">Active</a>';
                                        }elseif($row['RegStatu'] == 1){
                                            echo '<a href="?p=unactive&id=' . $row['ID'] . '" class="btn btn-danger">Unactive</a>';
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
            echo '<h3>Update Employee Data</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            $id     = $_POST['id']; 
            $name   = $_POST['name']; 
            $user   = $_POST['username']; 
            $email  = $_POST['email']; 
            $phone  = $_POST['phone'];
            $sec    = $_POST['section'];
            $role   = $_POST['role'];
            $country  = $_POST['country'];
            $city    = $_POST['city'];
            $addr   = $_POST['address'];
            // Password
            $pass = !empty($_POST['password']) ? $_POST['password'] : $_POST['oldpassword'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(is_numeric($id)){ 
                $check = checkItem($id, "id", "employee");
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
            if(strlen($name) > 30){ // Check If Name Is More Than 30 Char
                $errors[] = "Full Name Can't Be More Than <strong>30 Characters</strong>";
            }
            if(empty($user)){ // Check If Username Field Is Empty
                $errors[] = "Username Can't Be <strong>Empty</strong>";
            }
            if(strlen($user) < 4){ // Check If Username Is Less Than 4 Char
                $errors[] = "Username Can't Be Less Than <strong>4 Characters</strong>";
            }
            if(strlen($user) > 20){ // Check If Username Is More Than 20 Char
                $errors[] = "Username Can't Be More Than <strong>21 Characters</strong>";
            }
            $checkUser = checkItem($user, "Username", "employee", "ID !=", $id); // Check Username Is Exist In Database
            if($checkUser > 0){
                $errors[] = "This Username Is Already Exist In Database.";
            }
            if(empty($email)){ // Check If Email Field Is Empty
                $errors[] = "Email Can't Be <strong>Empty</strong>";
            }
            $checkEmail = checkItem($email, "Email", "employee", "ID !=", $id); // Check Email Is Exist In Database
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
            $checkPhone = checkItem($phone, "Phone", "employee", "ID !=", $id); // Check Phone Is Exist In Database
            if($checkPhone > 0){
                $errors[] = "This Phone Number Is Already Exist In Database.";
            }
            if(!empty($_POST['password'])){ // Check If Password Field Is Not Empty
                if(strlen($_POST['password']) < 8){ // Check Password Is Less Than 8 Char
                    $errors[] = "Password Can't Be Less Than <strong>8 Characters</strong>";
                }
                $pass = sha1($pass);
            }
            if(empty($country)){ // Check If Country Field Is Empty
                $errors[] = "Country Can't Be <strong>Empty</strong>";
            }
            if(empty($city)){ // Check If City Field Is Empty
                $errors[] = "City Can't Be <strong>Empty</strong>";
            }
            if(empty($addr)){ // Check If Address Field Is Empty
                $errors[] = "Address Can't Be <strong>Empty</strong>";
            }
            if(!is_numeric($sec)){ // Check If Phone Field Is Empty
                $errors[] = "The Section Contains An <strong>Characters Or Symbols</strong>";
            }
            if(is_numeric($sec)){ // Check If Phone Field Is Empty
                if($sec == 0){
                    $errors[] = "Please Select <strong>The Section Name</strong>";
                }
            }
            if(!is_numeric($role)){ // Check If Phone Field Is Empty
                $errors[] = "The Role Contains An <strong>Characters Or Symbols</strong>";
            }
            if(is_numeric($role)){ // Check If Phone Field Is Empty
                if($role == 0){
                    $errors[] = "Please Select <strong> The Role Name</strong>";
                }
            }
            
            // Echo Errors Is Exist
            foreach($errors as $err){
                echo "<h5 class='text-center alert alert-danger'>$err";
                    echo "<br><a href='?p=edit&id=$id' class='alert-link'> Back To Edit Employee Page</a>";
                echo "</h5>";
            }
            // Update Data In Database
            if(empty($errors)){
                $stmt = $con->prepare("UPDATE employee SET `Full_Name` = ?, Username = ?, Email = ?, Phone = ?, `Password` = ?, Country = ?, City = ?, Address = ?, Section_ID = ?, Role_ID = ? WHERE ID = ?");
                $stmt->execute(array($name, $user, $email, $phone, $pass, $country, $city, $addr, $sec, $role, $id));
                redirect("Updated Employee Data Successfully.", "back", 4, "success");
            }

        }else{
            $msg = "You Can't Browse This Page Directory.";
            redirect($msg, "back");
        }
        echo '</div>';
    }elseif($p == "add"){ // Add new 
        echo '<div class="profile-body">';
            echo '<h3>Add New Employee</h3><hr>';
            echo '<div class="user-info">';
            ## Get Sections Data 
            $stmt1 = $con->prepare("SELECT * FROM sections");
            $stmt1->execute();
            $sections = $stmt1->fetchAll();
            ## Get Roles Data
            $stmt1 = $con->prepare("SELECT * FROM roles");
            $stmt1->execute();
            $roles = $stmt1->fetchAll();
            ?>
                <form action="?p=insert" method="POST" class="form-horizonal needs-validation was-validated" novalidate>
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Full Name:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control" 
                                placeholder="Name Of Employee" 
                                required>
                            <div class="invalid-feedback">Employee Name Is Required</div>
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Username:</label>
                        <div class="col-sm-10 col-md-6 form-g input-group">
                            <span class="input-group-text">@</span>
                            <input 
                                type="text" 
                                name="username" 
                                class="form-control" 
                                placeholder="Username To Login" 
                                autocomplete="off" 
                                required>
                            <div class="invalid-feedback">Username is required</div>
                        </div>
                    </div>
                    <!-- End Username Field -->
                    <!-- Start Birthdate Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Birthdate:</label>
                        <div class="col-sm-10 col-md-6 form-g input-group">
                            <input 
                                type="date" 
                                name="birth" 
                                class="form-control" 
                                placeholder="Birthdate" 
                                autocomplete="off" 
                                required>
                            <div class="invalid-feedback">Birthdate is required</div>
                        </div>
                    </div>
                    <!-- End Birthdate Field -->
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Email:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control" 
                                placeholder="your@domain.com" 
                                required>
                            <div class="invalid-feedback">Employee Email Is Required</div>
                        </div>
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Phone Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Phone:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input 
                                type="text" 
                                name="phone" 
                                class="form-control" 
                                placeholder="Phone To Contact" 
                                required>
                            <div class="invalid-feedback">Employee Phone Number Is Required</div>
                        </div>
                    </div>
                    <!-- End Phone Field -->
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Password:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input 
                                type="password" 
                                name="password" 
                                class="form-control" 
                                placeholder="Password Required More Than 8 Char And Strong" 
                                autocomplete="new-password"
                                required>
                            <div class="invalid-feedback">Employee Password Is Required</div>
                        </div>
                    </div>
                    <!-- End Password Field -->
                    <!-- Start Country Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Country:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input 
                                type="text" 
                                name="country" 
                                class="form-control" 
                                placeholder="Country Was Born" 
                                required>
                            <div class="invalid-feedback">Employee Country Is Required</div>
                        </div>
                    </div>
                    <!-- End Country Field -->
                    <!-- Start City Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">City:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input 
                                type="text" 
                                name="city" 
                                class="form-control" 
                                placeholder="City Name" 
                                required>
                            <div class="invalid-feedback">Employee City Is Required</div>
                        </div>
                    </div>
                    <!-- End City Field -->
                    <!-- Start Address Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Address:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input 
                                type="text" 
                                name="address" 
                                class="form-control" 
                                placeholder="Address Details.." 
                                required>
                            <div class="invalid-feedback">Employee Address Is Required</div>
                        </div>
                    </div>
                    <!-- End Address Field -->
                    <!-- Start Section Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Section:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <select name="section" class="form-control form-select" required>
                                <option value="0"></option>
                                <?php
                                foreach($sections as $sec){?>
                                    <option value="<?php echo $sec['ID']; ?>" ><?php echo $sec['Name']; ?></option>
                                <?php } ?>
                            </select>   
                        </div>
                    </div>
                    <!-- End Section Field -->
                    <!-- Start Role Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Role:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <select name="role" class="form-control form-select" required>
                                <option value="0"></option>
                                <?php
                                foreach($roles as $role){?>
                                    <option value="<?php echo $role['ID']; ?>" ><?php echo $role['Name']; ?></option>
                                <?php } ?>
                            </select>   
                        </div>
                    </div>
                    <!-- End Role Field -->
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
            echo '<h3>Insert New Employee</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            $name   = $_POST['name']; 
            $user   = $_POST['username']; 
            $birth  = $_POST['birth']; 
            $email  = $_POST['email']; 
            $phone  = $_POST['phone']; 
            $pass   = $_POST['password'];
            $sec    = $_POST['section'];
            $role   = $_POST['role'];
            $country  = $_POST['country'];
            $city    = $_POST['city'];
            $addr   = $_POST['address'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(empty($name)){ // Check If Name Field Is Empty
                $errors[] = "Full Name Can't Be <strong>Empty</strong>";
            }
            if(strlen($name) < 10){ // Check If Name Is Less Than 10 Char
                $errors[] = "Full Name Can't Be Less Than <strong>10 Characters</strong>";
            }
            if(strlen($name) > 30){ // Check If Name Is More Than 30 Char
                $errors[] = "Full Name Can't Be More Than <strong>30 Characters</strong>";
            }
            if(empty($user)){ // Check If Username Field Is Empty
                $errors[] = "Username Can't Be <strong>Empty</strong>";
            }
            if(strlen($user) < 4){ // Check If Username Is Less Than 4 Char
                $errors[] = "Username Can't Be Less Than <strong>4 Characters</strong>";
            }
            if(strlen($user) > 20){ // Check If Username Is More Than 20 Char
                $errors[] = "Username Can't Be More Than <strong>15 Characters</strong>";
            }
            $checkUser = checkItem($user, "Username", "employee"); // Check Username Is Exist In Database
            if($checkUser > 0){
                $errors[] = "This Username Is Already Exist In Database.";
            }
            if(empty($email)){ // Check If Email Field Is Empty
                $errors[] = "Email Can't Be <strong>Empty</strong>";
            }
            $checkEmail = checkItem($email, "Email", "employee"); // Check Email Is Exist In Database
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
            $checkPhone = checkItem($phone, "Phone", "employee"); // Check Phone Is Exist In Database
            if($checkPhone > 0){
                $errors[] = "This Phone Number Is Already Exist In Database.";
            }
            if(!empty($_POST['password'])){ // Check If Password Field Is Not Empty
                if(strlen($_POST['password']) < 8){ // Check Password Is Less Than 8 Char
                    $errors[] = "Password Can't Be Less Than <strong>8 Characters</strong>";
                }
                if(strlen($_POST['password']) > 30){ // Check Password Is More Than 30 Char
                    $errors[] = "Password Can't Be More Than <strong>30 Characters</strong>";
                }
            }
            if(empty($country)){ // Check If Country Field Is Empty
                $errors[] = "Country Can't Be <strong>Empty</strong>";
            }
            if(empty($city)){ // Check If City Field Is Empty
                $errors[] = "City Can't Be <strong>Empty</strong>";
            }
            if(empty($addr)){ // Check If Address Field Is Empty
                $errors[] = "Address Can't Be <strong>Empty</strong>";
            }
            if(!is_numeric($sec)){ // Check If Phone Field Is Empty
                $errors[] = "The Section Contains An <strong>Characters Or Symbols</strong>";
            }
            if(is_numeric($sec)){ // Check If Phone Field Is Empty
                if($sec == 0){
                    $errors[] = "Please Select <strong>The Section Name</strong>";
                }
            }
            if(!is_numeric($role)){ // Check If Phone Field Is Empty
                $errors[] = "The Role Contains An <strong>Characters Or Symbols</strong>";
            }
            if(is_numeric($role)){ // Check If Phone Field Is Empty
                if($role == 0){
                    $errors[] = "Please Select <strong>The Role Name</strong>";
                }
            }
            
            // Insert Data In Database
            if(empty($errors)){ // Check If There Isn't Errors
                $passHashed = sha1($pass); // Encrypt Password
                $stmt = $con->prepare("INSERT INTO employee(Full_Name, Username, Email, Phone, Password, Birthdate, Country, City, Address, Section_ID, Role_ID, DateOfHiring)
                                                    VALUES (:name, :user, :email, :phone, :pass, :birth, :coun, :city, :addr, :sec, :rol, now())");
                $stmt->execute(array(
                    "name" => $name,
                    "user" => $user,
                    "birth" => $birth,
                    "coun" => $country,
                    "city" => $city,
                    "addr" => $addr,
                    "sec" => $sec,
                    "rol" => $role,
                    "email"=> $email,
                    "phone"=> $phone,
                    "pass" => $passHashed
                ));
                $msg = "Added New Employee Successfully.";
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
            echo '<h3>View Employee Data</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "employee");
                    if($check > 0){
                        $stmt2 = $con->prepare("SELECT * FROM employee WHERE ID = ?");
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
                            <!-- Start Country Field -->
                            <div class="form-group form-group-lg row">
                                <label for="" class="col-sm-2 col-form-label">Country:</label>
                                <div class="col-sm-10 col-md-6 form-g">
                                    <input type="text" name="country" class="form-control" placeholder="Country" value="<?php echo $row['Country']; ?>" readonly>
                                </div>
                            </div>
                            <!-- End Country Field -->
                            <!-- Start City Field -->
                            <div class="form-group form-group-lg row">
                                <label for="" class="col-sm-2 col-form-label">City:</label>
                                <div class="col-sm-10 col-md-6 form-g">
                                    <input type="text" name="city" class="form-control" placeholder="City" value="<?php echo $row['City']; ?>" readonly>
                                </div>
                            </div>
                            <!-- End City Field -->
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
                                    <input type="text" name="status" class="form-control" placeholder="Reg Status" value="<?php if($row['RegStatu'] == 0){echo "Unactivate";}else{echo "Activated";} ?>" readonly>
                                </div>
                            </div>
                            <!-- End RegStatus Field -->
                            <!-- Start Control Buttons -->
                            <div class="form-group row">
                                <div class="col-sm-10 offset-2">
                                    <a href="?p=edit&id=<?php echo $row['ID']; ?>" class="btn btn-primary w-25">Edit</a>
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
            echo '<h3>Delete Employee</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "employee");
                    if($check > 0){
                        $stmtSec = $con->prepare("SELECT roles.Access FROM employee
                                                INNER JOIN roles ON roles.ID = employee.Role_ID
                                                WHERE employee.ID = ?");
                        $stmtSec->execute(array($_SESSION['id']));
                        $emp = $stmtSec->fetch();
                        if($emp['Access'] == "Full"){
                            $stmt2 = $con->prepare("DELETE FROM employee WHERE ID = :id");
                            $stmt2->bindParam(":id", $_GET['id']);
                            $stmt2->execute();
                            $msg = ' Employee Deleted Successfully';
                            redirect($msg, "back", 3, "success");
                        }else{
                            $msg = "You Can't Updated, Back To Your Manager.";
                            redirect($msg, "Back");
                        }
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
    }elseif($p == "unactive"){
        echo '<div class="profile-body">';
            echo '<h3>Unactive Employee</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "employee");
                    if($check > 0){
                        $stmtSec = $con->prepare("SELECT roles.Access FROM employee
                                                INNER JOIN roles ON roles.ID = employee.Role_ID
                                                WHERE employee.ID = ?");
                        $stmtSec->execute(array($_SESSION['id']));
                        $emp = $stmtSec->fetch();
                        if($emp['Access'] == "Full" || $emp['Access'] == "FullWrite"){
                            $stmt2 = $con->prepare("UPDATE employee SET RegStatu = 0 WHERE ID = :id");
                            $stmt2->bindParam(":id", $_GET['id']);
                            $stmt2->execute();
                            $msg = ' Employee Unactivated Successfully';
                            redirect($msg, "back", 3, "success");
                        }else{
                            $msg = "You Can't Updated, Back To Your Manager.";
                            redirect($msg, "Back");
                        }
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
            echo '<h3>Activate Employee</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "employee");
                    if($check > 0){
                        $stmtSec = $con->prepare("SELECT roles.Access FROM employee
                                                INNER JOIN roles ON roles.ID = employee.Role_ID
                                                WHERE employee.ID = ?");
                        $stmtSec->execute(array($_SESSION['id']));
                        $emp = $stmtSec->fetch();
                        
                        if($emp['Access'] == "Full" || $emp['Access'] == "FullWrite"){
                            $stmt2 = $con->prepare("UPDATE employee SET RegStatu = 1 WHERE ID = :id");
                            $stmt2->bindParam(":id", $_GET['id']);
                            $stmt2->execute();
                            $msg = ' Employee Activated Successfully';
                            redirect($msg, "back", 3, "success");
                        }else{
                            $msg = "You Can't Updated, Back To Your Manager.";
                            redirect($msg, "Back");
                        }
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