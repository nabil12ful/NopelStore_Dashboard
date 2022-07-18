<?php
    ob_start();
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }
    $pageTitle = "Categories";
    include('init.php');

    $p = isset($_GET['p']) ? $_GET['p'] : "manage";

    if($p == "manage"){ // Manage page
        $sorts = array('ASC', 'DESC');
        $sort = "ORDER BY ID ASC";
        $so = "ASC";
        if(isset($_GET['sort']) && in_array($_GET['sort'], $sorts)){
            $sort = "ORDER BY Ordering " . $_GET['sort'];
            $so = $_GET['sort'];
        }else{
            $so = "ASC";
        }
        $stmt = $con->prepare("SELECT categories.* FROM categories $sort");
        $stmt->execute();
        $cats = $stmt->fetchAll();
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
                                <td>#Ordering</td>
                                <td>Name</td>
                                <td>Description</td>
                                <td>Proucts QTY</td>
                                <td>Visibility</td>
                                <td>Allow Comment</td>
                                <td>Allow Ads</td>
                                <td>Controls</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($cats as $cat){
                                echo "<tr>";
                                    echo "<td>" . $cat['ID'] . "</td>";
                                    echo "<td>" . $cat['Ordering'] . "</td>";
                                    echo "<td>" . $cat['Name'] . "</td>";
                                    echo "<td>" . $cat['Description'] . "</td>";
                                    echo "<td>" . $cat['QTY'] . "</td>";
                                    if($cat['Visibility'] == 0){
                                        echo "<td>";
                                            echo "<div class='action my-1 text-danger'>Hidden</div>";
                                        echo "</td>";
                                    }else{
                                        echo "<td>";
                                            echo "<div class='action my-1 text-success'>Visibe</div>";
                                        echo "</td>";
                                    }
                                    if($cat['Allow_Comment'] == 0){
                                        echo "<td>";
                                            echo "<div class='action my-1 text-danger'>Disabled</div>";
                                        echo "</td>";
                                    }else{
                                        echo "<td>";
                                            echo "<div class='action my-1 text-success'> Enabled</div>";
                                        echo "</td>";
                                    }
                                    if($cat['Allow_Ads'] == 0){
                                        echo "<td>";
                                            echo "<div class='action my-1 text-danger'>Disabled</div>";
                                        echo "</td>";
                                    }else{
                                        echo "<td>";
                                            echo "<div class='action my-1 text-success'>Enabled</div>";
                                        echo "</td>";
                                    }
                                    echo '<td>';
                                        echo '<a href="?p=edit&id=' . $cat['ID'] . '" class="btn btn-primary">Edit</a>';
                                        echo '<a href="?p=delete&id=' . $cat['ID'] . '" class="btn btn-danger">Delete</a>';
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
            echo '<h3>Edit Category</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "categories");
                    if($check > 0){
                        $stmt2 = $con->prepare("SELECT * FROM categories WHERE ID = ?");
                        $stmt2->execute(array($_GET['id']));
                        $row = $stmt2->fetch(); ?>
                            <form action="?p=update" method="POST" class="form-horizonal was-validated" novalidate>
                                <input type="hidden" name="id" value="<?php echo $row['ID'] ?>">
                                <!-- Start Ordering Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Orderng:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input type="number" name="order" class="form-control" value="<?php echo $row['Ordering']; ?>" placeholder="Ordering Of Category" required>
                                        <div class="invalid-feedback">Category Ordering Is Required</div>
                                    </div>
                                </div>
                                <!-- End Ordering Field -->
                                <!-- Start Name Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Name:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input type="text" name="name" class="form-control" value="<?php echo $row['Name']; ?>" placeholder="Name Of Category" required>
                                        <div class="invalid-feedback">Category Name Is Required</div>
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
                                <!-- Start Visibility Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Visibility:</label>
                                    <div class="col-sm-10 col-md-6 form-g form-check">
                                        <input type="radio" name="visibility" value="1" id="visible" <?php if($row['Visibility'] == 1){echo "checked";} ?> >
                                        <label for="visible">Visible</label>
                                        <input type="radio" name="visibility" value="0" id="hidden" <?php if($row['Visibility'] == 0){echo "checked";} ?> >
                                        <label for="hidden">Hidden</label>
                                    </div>
                                </div>
                                <!-- End Visibility Field -->
                                <!-- Start Allow Comment Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Allow Comment:</label>
                                    <div class="col-sm-10 col-md-6 form-g form-check">
                                        <input type="radio" name="comment" value="1" id="comyes" <?php if($row['Allow_Comment'] == 1){echo "checked";} ?> >
                                        <label for="comyes">Enable</label>
                                        <input type="radio" name="comment" value="0" id="comno" <?php if($row['Allow_Comment'] == 0){echo "checked";} ?> >
                                        <label for="comno">Disable</label>
                                    </div>
                                </div>
                                <!-- End Allow Comment Field -->
                                <!-- Start Allow Ads Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Allow Ads:</label>
                                    <div class="col-sm-10 col-md-6 form-g form-check">
                                        <input type="radio" name="ads" value="1" id="adyes" <?php if($row['Allow_Ads'] == 1){echo "checked";} ?> >
                                        <label for="adyes">Enable</label>
                                        <input type="radio" name="ads" value="0" id="adno" <?php if($row['Allow_Ads'] == 0){echo "checked";} ?> >
                                        <label for="adno">Disable</label>
                                    </div>
                                </div>
                                <!-- End Allow Ads Field -->
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
                        $msg = "Sorry, This Category Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Category Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "update"){ // Update Category
        echo '<div class="profile-body">';
            echo '<h3>Update Category</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            $id     = $_POST['id']; 
            $order  = $_POST['order']; 
            $name   = $_POST['name']; 
            $desc   = $_POST['desc']; 
            $visib  = $_POST['visibility']; 
            $comm   = $_POST['comment'];
            $ads    = $_POST['ads'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(is_numeric($id)){ 
                $check = checkItem($id, "ID", "categories");
                if($check < 1){
                    $errors[] = "This Category Is Not Exist.";
                }
            }
            if(!is_numeric($order)){
                $errors[] = "This Field Is Not Number.";
            }

            if(empty($name)){ // Check If Name Field Is Empty
                $errors[] = "Category Name Can't Be <strong>Empty</strong>";
            }
            $checkName = checkItem($name, "Name", "categories", "ID !=", $id); // Check Name Is Exist In Database
            if($checkName > 0){
                $errors[] = "This Category Is Already Exist In Database.";
            }
            
            
            // Update Data In Database
            if(empty($errors)){
                $stmt = $con->prepare("UPDATE categories SET `Name` = ?, Ordering = ?, Description = ?, Visibility = ?, Allow_Comment = ?, `Allow_Ads` = ? WHERE ID = ?");
                $stmt->execute(array($name, $order, $desc, $visib, $comm, $ads, $id));
                redirect("Updated Customer Data Successfully.", "back", 4, "success");
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
            echo '<h3>Add New Category</h3><hr>';
            echo '<div class="user-info">'; ?>
                <form action="?p=insert" method="POST" class="form-horizonal was-validated" novalidate>
                    <!-- Start Ordering Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Ordering:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="order" class="form-control" placeholder="Ordering Of Category" required>
                            <div class="invalid-feedback">Category Ordering Is Required</div>
                        </div>
                    </div>
                    <!-- End Ordering Field -->
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Name:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="name" class="form-control" placeholder="Name Of Category" required>
                            <div class="invalid-feedback">Category Name Is Required</div>
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Description:</label>
                        <div class="col-sm-10 col-md-6 form-g input-group has-validation">
                            <input type="text" name="desc" class="form-control" placeholder="Describe Of Category">
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Visibility Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Visibility:</label>
                        <div class="col-sm-10 col-md-6 form-g form-check">
                            <input type="radio" name="visibility" value="1" id="visible" checked required>
                            <label for="visible">Visible</label>
                            <input type="radio" name="visibility" value="0" id="hidden" required>
                            <label for="hidden">Hidden</label>
                        </div>
                    </div>
                    <!-- End Visibility Field -->
                    <!-- Start Allow Comment Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Allow Comment:</label>
                        <div class="col-sm-10 col-md-6 form-g form-check">
                            <input type="radio" name="comment" value="1" id="comyes" checked required>
                            <label for="comyes">Enable</label>
                            <input type="radio" name="comment" value="0" id="comno" required>
                            <label for="comno">Disable</label>
                        </div>
                    </div>
                    <!-- End Allow Comment Field -->
                    <!-- Start Allow Ads Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Allow Ads:</label>
                        <div class="col-sm-10 col-md-6 form-g form-check">
                            <input type="radio" name="ads" value="1" id="adyes" checked required>
                            <label for="adyes">Enable</label>
                            <input type="radio" name="ads" value="0" id="adno" required>
                            <label for="adno">Disable</label>
                        </div>
                    </div>
                    <!-- End Allow Ads Field -->
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
            echo '<h3>Insert New Category</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            $name   = $_POST['name']; 
            $order  = $_POST['order']; 
            $desc   = $_POST['desc']; 
            $visib  = $_POST['visibility']; 
            $comm   = $_POST['comment'];
            $ads    = $_POST['ads'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(empty($name)){ // Check If Name Field Is Empty
                $errors[] = "Category Name Can't Be <strong>Empty</strong>";
            }
            if(!is_numeric($order)){
                $errors[] = "This Field Is Not Number.";
            }
            $checkName = checkItem($name, "Name", "categories"); // Check Name Is Exist In Database
            if($checkName > 0){
                $errors[] = "This Category Is Already Exist In Database.";
            }
            
            // Insert Data In Database
            if(empty($errors)){ // Check If There Isn't Errors
                $stmt = $con->prepare("INSERT INTO categories(Name, Ordering, Description, visibility, Allow_Comment, Allow_Ads)
                                                    VALUES (:name, :order, :desc, :vis, :comm, :ads)");
                $stmt->execute(array(
                    "name" => $name,
                    "order"=> $order,
                    "desc" => $desc,
                    "vis"  => $visib,
                    "comm" => $comm,
                    "ads"  => $ads
                ));
                $msg = "Added New Category Successfully.";
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
            echo '<h3>Category Customer</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "categories");
                    if($check > 0){
                        $stmt2 = $con->prepare("DELETE FROM categories WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $msg = ' Category Deleted Successfully';
                        redirect($msg, "back", 3, "success");
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
    }else{
        $msg = "Sorry, Not Found This Page";
        redirect($msg, "back");
    }
    include($temps . "footer.php");
    ob_end_flush();

?>