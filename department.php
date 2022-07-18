<?php
    ob_start();
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }
    $pageTitle = "Department";
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
        $stmt = $con->prepare("SELECT sections.* FROM sections $sort");
        $stmt->execute();
        $dpts = $stmt->fetchAll();
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
                                <td>Name</td>
                                <td>Description</td>
                                <td>Mobile</td>
                                <td>Controls</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($dpts as $dpt){
                                echo "<tr>";
                                    echo "<td>" . $dpt['ID'] . "</td>";
                                    echo "<td>" . $dpt['Name'] . "</td>";
                                    echo "<td>" . $dpt['Description'] . "</td>";
                                    echo "<td>" . $dpt['Mobile'] . "</td>";
                                    echo "<td>";
                                        echo '<a href="?p=edit&id=' . $dpt['ID'] . '" class="btn btn-primary">Edit</a>';
                                        echo '<a href="?p=delete&id=' . $dpt['ID'] . '" class="btn btn-danger">Delete</a>';
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
    }elseif($p == "edit"){ // Edit Category Page
        echo '<div class="profile-body">';
            echo '<h3>Edit Department</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "sections");
                    if($check > 0){
                        $stmt2 = $con->prepare("SELECT * FROM sections WHERE ID = ?");
                        $stmt2->execute(array($_GET['id']));
                        $row = $stmt2->fetch(); ?>
                            <form action="?p=update" method="POST" class="form-horizonal was-validated" novalidate>
                                <input type="hidden" name="id" value="<?php echo $row['ID'] ?>">
                                <!-- Start Name Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Name:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input type="text" name="name" class="form-control" value="<?php echo $row['Name']; ?>" placeholder="Name Of Department" required>
                                        <div class="invalid-feedback">Department Name Is Required</div>
                                    </div>
                                </div>
                                <!-- End Name Field -->
                                <!-- Start Description Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Description:</label>
                                    <div class="col-sm-10 col-md-6 form-g input-group has-validation">
                                        <input type="text" name="desc" class="form-control" value="<?php echo $row['Description']; ?>" placeholder="Description">
                                    </div>
                                </div>
                                <!-- End Description Field -->
                                <!-- Start Mobile Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Mobile:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input type="text" name="mobile" class="form-control" value="<?php echo $row['Mobile']; ?>" placeholder="Mobile Of Department">
                                        <div class="invalid-feedback">Department Mobile Is Required</div>
                                    </div>
                                </div>
                                <!-- End Mobile Field -->
                                <!-- Start Submit Button -->
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-2">
                                        <input type="submit" class="btn btn-primary" value="Update">
                                        <a href="?p=delete&id=<?php echo $row['ID']; ?>" class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                                <!-- End Submit Button -->
                            </form>
                        <?php
                    }else{
                        $msg = "Sorry, This Department Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Department Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "update"){ // Update 
        echo '<div class="profile-body">';
            echo '<h3>Update Department</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            $id     = $_POST['id']; 
            $name   = $_POST['name']; 
            $desc   = $_POST['desc']; 
            $mobile  = $_POST['mobile'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(is_numeric($id)){ 
                $check = checkItem($id, "ID", "sections");
                if($check < 1){
                    $errors[] = "This Department Is Not Exist.";
                }
            }

            if(empty($name)){ // Check If Name Field Is Empty
                $errors[] = "Department Name Can't Be <strong>Empty</strong>";
            }
            $checkName = checkItem($name, "Name", "sections", "ID !=", $id); // Check Name Is Exist In Database
            if($checkName > 0){
                $errors[] = "This Department Is Already Exist In Database.";
            }
            
            
            // Update Data In Database
            if(empty($errors)){
                $stmt = $con->prepare("UPDATE sections SET `Name` = ?, Description = ?, Mobile = ? WHERE ID = ?");
                $stmt->execute(array($name, $desc, $mobile, $id));
                redirect("Updated Department Data Successfully.", "back", 4, "success");
            }else{
                // Echo Errors Is Exist
                foreach($errors as $err){
                    echo "<h5 class='text-center alert alert-danger'>$err";
                    echo "</h5>";
                }
                redirect("There Is Errors.", "back", 5);
            }

        }else{
            $msg = "You Can't Browse This Page Directory.";
            redirect($msg, "back");
        }
        echo '</div>';
    }elseif($p == "add"){ // Add new Category
        echo '<div class="profile-body">';
            echo '<h3>Add Department</h3><hr>';
            echo '<div class="user-info">'; ?>
                <form action="?p=insert" method="POST" class="form-horizonal was-validated" novalidate>
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Name:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="name" class="form-control" placeholder="Name Of Department" required>
                            <div class="invalid-feedback">Department Name Is Required</div>
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Description:</label>
                        <div class="col-sm-10 col-md-6 form-g input-group has-validation">
                            <input type="text" name="desc" class="form-control" placeholder="Describe Of Department">
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Mobile Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Mobile:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="mobile" class="form-control" placeholder="Mobile Of Department">
                            <div class="invalid-feedback">Department Mobile Is Required</div>
                        </div>
                    </div>
                    <!-- End Mobile Field -->
                    <!-- Start Submit Button -->
                    <div class="form-group row">
                        <div class="col-sm-10 offset-2">
                            <input type="submit" class="btn btn-success" value="Add New">
                        </div>
                    </div>
                    <!-- End Submit Button -->
                </form>
            <?php
            echo '</div>';
        echo '</div>';
    }elseif($p == "insert"){
        echo '<div class="profile-body">';
            echo '<h3>Insert New Department</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            $name   = $_POST['name']; 
            $desc   = $_POST['desc']; 
            $mobile  = $_POST['mobile']; 
            // Validations
            $errors = array(); // Array Of Form Errors
            if(empty($name)){ // Check If Name Field Is Empty
                $errors[] = "Department Name Can't Be <strong>Empty</strong>";
            }
            $checkName = checkItem($name, "Name", "sections"); // Check Name Is Exist In Database
            if($checkName > 0){
                $errors[] = "This Department Is Already Exist In Database.";
            }
            
            // Insert Data In Database
            if(empty($errors)){ // Check If There Isn't Errors
                $stmt = $con->prepare("INSERT INTO sections(Name, Description, Mobile)
                                                    VALUES (:name, :desc, :mobile)");
                $stmt->execute(array(
                    "name" => $name,
                    "desc" => $desc,
                    "mobile"  => $mobile
                ));
                $msg = "Added New Department Successfully.";
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
    }elseif($p == "delete"){
        echo '<div class="profile-body">';
            echo '<h3>Department Customer</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "sections");
                    if($check > 0){
                        $stmt2 = $con->prepare("DELETE FROM sections WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $msg = ' Department Deleted Successfully';
                        redirect($msg, "back", 3, "success");
                    }else{
                        $msg = "Sorry, This Department Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Department Id Is Incorrect.";
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