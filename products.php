<?php
    ob_start();
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }
    $pageTitle = "Products";
    include('init.php');

    $p = isset($_GET['p']) ? $_GET['p'] : "manage";

    if($p == "manage"){ // Manage page
        $sorts = array('ASC', 'DESC');
        $sort  = "ORDER BY ID ASC";
        $so    = "ASC";
        if(isset($_GET['sort']) && in_array($_GET['sort'], $sorts)){
            $sort = "ORDER BY ID " . $_GET['sort'];
            $so   = $_GET['sort'];
        }else{
            $so = "ASC";
        }
        $stmt = $con->prepare("SELECT products.*, categories.Name, employee.Username, product_image.Image_Path AS Images FROM `products`
                            INNER JOIN categories ON categories.ID = products.Category
                            INNER JOIN product_image ON Product_ID = products.ID
                            INNER JOIN employee ON employee.ID = products.By_Emp WHERE product_image.Flag = 1 $sort");
        $stmt->execute();
        $pros = $stmt->fetchAll();
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
            </div>
            <div class="pro-body">
                <div class="table-responsive">
                    <table class="main-table table">
                        <thead>
                            <tr>
                                <td>#ID</td>
                                <td>Image</td>
                                <td>Title</td>
                                <td>Brand</td>
                                <td>Model</td>
                                <td>Price</td>
                                <td>Rating</td>
                                <td>Category</td>
                                <td>By Employee</td>
                                <td>Allow Comments</td>
                                <td>Status</td>
                                <td>Controls</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($pros as $pro){
                                echo "<tr>";
                                    echo "<td>".$pro['ID']."</td>";
                                    echo "<td class=\"pro-imgs\">";
                                        if(!empty($pro['Images'])){
                                            echo "<img src='" . $pro['Images'] . "'>";
                                        }else{
                                            echo "<img src='" . $imgs . "user.png'>";
                                        }
                                        
                                    echo "</td>";
                                    echo "<td>" . $pro['Title'] . "</td>";
                                    echo "<td>" . $pro['Brand'] . "</td>";
                                    echo "<td>" . $pro['Model'] . "</td>";
                                    echo "<td>" . $pro['Price'] . "</td>";
                                    echo "<td>" . $pro['Rating'] . "</td>";
                                    echo "<td>" . $pro['Name'] . "</td>";
                                    echo "<td>" . $pro['Username'] . "</td>";
                                    if($pro['Allow_Comments'] == 0){
                                        echo "<td>";
                                            echo "<div class='action my-1 bg-warning'>Disabled</div>";
                                        echo "</td>";
                                    }else{
                                        echo "<td>";
                                            echo "<div class='action my-1 bg-success'>Enabled</div>";
                                        echo "</td>";
                                    }
                                    if($pro['Status'] == 0){
                                        echo "<td>";
                                            echo "<div class='action my-1 bg-warning'>Unactivated</div>";
                                        echo "</td>";
                                    }else{
                                        echo "<td>";
                                            echo "<div class='action my-1 bg-success'>Activated</div>";
                                        echo "</td>";
                                    }
                                    echo '<td>';
                                        echo '<a href="?p=edit&id=' . $pro['ID'] . '" class="btn btn-primary">Edit</a>';
                                        echo '<a href="?p=delete&id=' . $pro['ID'] . '" class="btn btn-danger">Delete</a>';
                                    echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="btns">
            <ul>
                <li>
                    <a href="#" class="nav-btn">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-btn">1</a>
                </li>
                <li>
                    <a href="#" class="nav-btn">2</a>
                </li>
                <li>
                    <a href="#" class="nav-btn">3</a>
                </li>
                <li>
                    <a href="#" class="nav-btn">4</a>
                </li>
                <li>
                    <a href="#" class="nav-btn">
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </li>
            </ul>
        </div>

        <?php
    }elseif($p == "edit"){ // Edit Data Page
        echo '<div class="profile-body">';
            echo '<h3>Edit Product</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "id", "products");
                    if($check > 0){
                        // Get product Data
                        $stmt2 = $con->prepare("SELECT * FROM products WHERE ID = ?");
                        $stmt2->execute(array($_GET['id']));
                        $row = $stmt2->fetch();
                        // Get Product's Images
                        $stmt1 = $con->prepare("SELECT * FROM product_image WHERE Product_ID = ?");
                        $stmt1->execute(array($_GET['id']));
                        $imgs = $stmt1->fetchAll();
                        // Get all Categories
                        $stmt = $con->prepare("SELECT * FROM categories");
                        $stmt->execute();
                        $cats = $stmt->fetchAll();
                        ?>
                            <form action="?p=update" method="POST" class="form-horizonal was-validated" novalidate>
                                <input type="hidden" name="id" value="<?php echo $row['ID'] ?>">
                                <!-- Start Images Section -->
                                <div class="row">
                                    <div class="images">
                                        <?php
                                        foreach($imgs as $img){
                                            echo "<img src='". $img['Image_Path'] ."' width='140px' height='140px' >";
                                        }
                                        if(count($imgs) < 8){
                                            $dif = 8 - count($imgs);
                                            for($i = 0; $i < $dif; $i++){
                                                echo "<img src='Layout/images/add.png' width='140px' height='140px' >";
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <!-- End Images Section -->
                                <!-- Start Title Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Title:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input 
                                            type="text" 
                                            name="title" 
                                            class="form-control" 
                                            value="<?php echo $row['Title']; ?>" 
                                            placeholder="Name Of Product" 
                                            required>
                                        <div class="invalid-feedback">Product Title Is Required</div>
                                    </div>
                                </div>
                                <!-- End Title Field -->
                                <!-- Start Description Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Description:</label>
                                    <div class="col-sm-10 col-md-6 form-g input-group has-validation">
                                        <textarea name="description" id="" cols="100" rows="5"><?php echo $row['Description']; ?></textarea>
                                    </div>
                                </div>
                                <!-- End Description Field -->
                                <!-- Start Brand Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Brand:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input 
                                            type="text" 
                                            name="brand" 
                                            class="form-control" 
                                            value="<?php echo $row['Brand']; ?>" 
                                            placeholder="Ex: Samsung, DELL..etc" 
                                            required>
                                        <div class="invalid-feedback">Brand Is Required</div>
                                    </div>
                                </div>
                                <!-- End Brand Field -->
                                <!-- Start Model Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Model:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input type="text" name="model" class="form-control" value="<?php echo $row['Model']; ?>" placeholder="Model Of Product" required>
                                        <div class="invalid-feedback">Model Is Required</div>
                                    </div>
                                </div>
                                <!-- End Model Field -->
                                <!-- Start Price Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Price:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input type="text" name="price" class="form-control" value="<?php echo $row['Price']; ?>" placeholder="Price Of Product, $.." required>
                                        <div class="invalid-feedback">Price Is Required</div>
                                    </div>
                                </div>
                                <!-- End Price Field -->
                                <!-- Start Qty Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Qty:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <input type="text" name="qty" class="form-control" value="<?php echo $row['Amount']; ?>" placeholder="EX: 30" required>
                                        <div class="invalid-feedback">Qty Is Required</div>
                                    </div>
                                </div>
                                <!-- End Qty Field -->
                                <!-- Start Category Field -->
                                <div class="form-group form-group-lg row">
                                    <label for="" class="col-sm-2 col-form-label">Category:</label>
                                    <div class="col-sm-10 col-md-6 form-g">
                                        <select name="category" class="form-control form-select" required>
                                            <?php
                                            foreach($cats as $cat){?>
                                                <option value="<?php echo $cat['ID']; ?>" <?php if($cat['ID'] == $row['Category']){echo "selected";} ?>><?php echo $cat['Name']; ?></option>
                                            <?php } ?>
                                        </select>   
                                    </div>
                                </div>
                                <!-- End Category Field -->
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
                        $msg = "Sorry, This Product Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Product Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "update"){ // Update
        echo '<div class="profile-body">';
            echo '<h3>Update Product</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            $id       = $_POST['id'];
            $title    = $_POST['title']; 
            $desc     = $_POST['description']; 
            $brand    = $_POST['brand']; 
            $model    = $_POST['model']; 
            $price    = $_POST['price'];
            $qty      = $_POST['qty'];
            $cate     = $_POST['category'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(is_numeric($id)){ 
                $check = checkItem($id, "id", "products");
                if($check < 1){
                    $errors[] = "This Product Is Not Exist.";
                }
            }

            if(empty($title)){ // Check If Name Field Is Empty
                $errors[] = "Title Can't Be <strong>Empty</strong>";
            }
            if(empty($desc)){ // Check If Username Field Is Empty
                $errors[] = "Description Can't Be <strong>Empty</strong>";
            }
            if(empty($brand)){ // Check If Username Is Less Than 4 Char
                $errors[] = "Brand Can't Be <strong>Empty</strong>";
            }
            if(empty($model)){ // Check If Username Is More Than 15 Char
                $errors[] = "Model Can't Be <strong>Empty</strong>";
            }
            if(empty($price)){ // Check If Email Field Is Empty
                $errors[] = "Price Can't Be <strong>Empty</strong>";
            }
            if(!is_numeric($price)){ // Check If Phone Field Is Not Number
                $errors[] = "The Price Contains An <strong>Characters Or Symbols</strong>";
            }
            if(empty($qty)){ // Check If Email Field Is Empty
                $errors[] = "Price Can't Be <strong>Empty</strong>";
            }
            if(!is_numeric($qty)){ // Check If Phone Field Is Not Number
                $errors[] = "The Price Contains An <strong>Characters Or Symbols</strong>";
            }
            if(!is_numeric($cate)){ // Check If Phone Field Is Empty
                $errors[] = "The Category Contains An <strong>Characters Or Symbols</strong>";
            }
            if(is_numeric($cate)){ // Check If Phone Field Is Empty
                if($cate == 0){
                    $errors[] = "Please Select <strong>Category</strong>";
                }
            }
            
            // Echo Errors Is Exist
            foreach($errors as $err){
                echo "<h5 class='text-center alert alert-danger'>$err";
                    echo "<br><a href='customers.php?p=edit&id=$id' class='alert-link'> Back To Edit Customer Page</a>";
                echo "</h5>";
            }
            // Update Data In Database
            if(empty($errors)){
                $stmt = $con->prepare("UPDATE products SET `Title` = ?, Description = ?, Brand = ?, Model = ?, `Price` = ?, Amount = ?, Category = ? WHERE ID = ?");
                $stmt->execute(array($title, $desc, $brand, $model, $price, $qty, $cate, $id));
                redirect("Updated Product Data Successfully.", "back", 2, "success");
            }

        }else{
            $msg = "You Can't Browse This Page Directory.";
            redirect($msg, "back");
        }
        echo '</div>';
    }elseif($p == "add"){ // Add new
        echo '<div class="profile-body">';
            echo '<h3>Add Product</h3><hr>';
            echo '<div class="user-info">';
            $stmt = $con->prepare("SELECT * FROM categories");
            $stmt->execute();
            $cats = $stmt->fetchAll(); ?>
                <form action="?p=insert" method="POST" class="form-horizonal was-validated" novalidate enctype="multipart/form-data">
                    <!-- Start Title Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Images:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="file" name="files[]" class="form-file" multiple required>
                            <div class="invalid-feedback">Product Image Is Required</div>
                        </div>
                    </div>
                    <!-- End Title Field -->
                    <!-- Start Title Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Title:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="title" class="form-control" placeholder="Name Of Product" required>
                            <div class="invalid-feedback">Product Title Is Required</div>
                        </div>
                    </div>
                    <!-- End Title Field -->
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Description:</label>
                        <div class="col-sm-10 col-md-6 form-g input-group has-validation">
                            <textarea name="description" id="" cols="100" rows="5"></textarea>
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Brand Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Brand:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="brand" class="form-control" placeholder="Ex: Samsung, DELL..etc" required>
                            <div class="invalid-feedback">Brand Is Required</div>
                        </div>
                    </div>
                    <!-- End Brand Field -->
                    <!-- Start Model Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Model:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="model" class="form-control" placeholder="Model Of Product" required>
                            <div class="invalid-feedback">Model Is Required</div>
                        </div>
                    </div>
                    <!-- End Model Field -->
                    <!-- Start Price Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Price:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="price" class="form-control" placeholder="Price Of Product, $.." required>
                            <div class="invalid-feedback">Price Is Required</div>
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Qty Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Qty:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <input type="text" name="qty" class="form-control" placeholder="EX: 30" required>
                            <div class="invalid-feedback">Qty Is Required</div>
                        </div>
                    </div>
                    <!-- End Qty Field -->
                    <!-- Start Category Field -->
                    <div class="form-group form-group-lg row">
                        <label for="" class="col-sm-2 col-form-label">Category:</label>
                        <div class="col-sm-10 col-md-6 form-g">
                            <select name="category" class="form-control form-select" required>
                                <option value="0"></option>
                                <?php
                                foreach($cats as $cat){?>
                                    <option value="<?php echo $cat['ID']; ?>" ><?php echo $cat['Name']; ?></option>
                                <?php } ?>
                            </select>   
                        </div>
                    </div>
                    <!-- End Category Field -->
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
            echo '<h3>Insert Product</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            if(isset($_FILES['files'])){
                $links = uploadMultiImages($_FILES['files'], "products", 8, 6);
            }
            $title    = $_POST['title']; 
            $desc     = $_POST['description']; 
            $brand    = $_POST['brand']; 
            $model    = $_POST['model']; 
            $price    = $_POST['price'];
            $qty      = $_POST['qty'];
            $cate     = $_POST['category'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(empty($title)){ // Check If Name Field Is Empty
                $errors[] = "Title Can't Be <strong>Empty</strong>";
            }
            if(empty($desc)){ // Check If Username Field Is Empty
                $errors[] = "Description Can't Be <strong>Empty</strong>";
            }
            if(empty($brand)){ // Check If Username Is Less Than 4 Char
                $errors[] = "Brand Can't Be <strong>Empty</strong>";
            }
            if(empty($model)){ // Check If Username Is More Than 15 Char
                $errors[] = "Model Can't Be <strong>Empty</strong>";
            }
            if(empty($price)){ // Check If Email Field Is Empty
                $errors[] = "Price Can't Be <strong>Empty</strong>";
            }
            if(!is_numeric($price)){ // Check If Phone Field Is Not Number
                $errors[] = "The Price Contains An <strong>Characters Or Symbols</strong>";
            }
            if(empty($qty)){ // Check If Email Field Is Empty
                $errors[] = "Price Can't Be <strong>Empty</strong>";
            }
            if(!is_numeric($qty)){ // Check If Phone Field Is Not Number
                $errors[] = "The Price Contains An <strong>Characters Or Symbols</strong>";
            }
            if(!is_numeric($cate)){ // Check If Phone Field Is Empty
                $errors[] = "The Category Contains An <strong>Characters Or Symbols</strong>";
            }
            if(is_numeric($cate)){ // Check If Phone Field Is Empty
                if($cate == 0){
                    $errors[] = "Please Select <strong>Category</strong>";
                }
            }
            
            // Echo Errors Is Exist
            foreach($errors as $err){
                echo "<h5 class='text-center alert alert-danger'>$err";
                    echo "<br><a href='products.php?p=add' class='alert-link'> Back To Edit Customer Page</a>";
                echo "</h5>";
            }
            // Update Data In Database
            if(empty($errors)){
                $stmt = $con->prepare("INSERT INTO products(Title, Description, Brand, Model, Price, Amount, Category, By_Emp)
                                        VALUES(:title, :desc, :brand, :model, :price, :qty, :cate, :emp)");
                $stmt->execute(array(
                    "title" => $title,
                    "desc"  => $desc,
                    "brand" => $brand,
                    "model" => $model,
                    "price" => $price,
                    "qty"   => $qty,
                    "cate"  => $cate,
                    "emp"   => $_SESSION['id']
                ));
                $stmt1 = $con->prepare("SELECT ID FROM products WHERE Title = :tit AND Description = :dec");
                $stmt1->execute(array(
                    "tit" => $title,
                    "dec" => $desc));
                $last = $stmt1->fetch();
                $proID = $last['ID'];
                for($r = 0; $r < count($links); $r++){
                    if($r == 0){
                        $stmt2 = $con->prepare("INSERT INTO product_image(Product_ID, Image_Path, Flag) VALUES (:id, :path, :flag)");
                        $stmt2->execute(array(
                            "id" => $proID,
                            "path" => $links[$r],
                            "flag" => 1
                        ));
                    }else{
                        $stmt2 = $con->prepare("INSERT INTO product_image(Product_ID, Image_Path) VALUES (:id, :path)");
                        $stmt2->execute(array(
                            "id" => $proID,
                            "path" => $links[$r]
                        ));
                    }
                }
                $cats = $con->prepare("SELECT COUNT(*) FROM categories");
                redirect("Inserted Product Successfully.", "back", 2, "success");
            }else{
                for($i = 0; $i < count($links); $i++){
                    unlink($links[$i]);
                }
                echo "<h5 class='text-center alert alert-danger'>All File Has Been Deleted</h5>";
            }

        }else{
            $msg = "You Can't Browse This Page Directory.";
            redirect($msg, "back");
        }
        echo '</div>';
    }elseif($p == "delete"){
        echo '<div class="profile-body">';
            echo '<h3>Delete Product</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "products");
                    if($check > 0){
                        $stmt = $con->prepare("SELECT * FROM product_image WHERE Product_ID = :id");
                        $stmt->bindParam(":id", $_GET['id']);
                        $stmt->execute();
                        $imgs = $stmt->fetchAll();
                        foreach($imgs as $img){
                            unlink($img['Image_Path']);
                        }
                        $stmt2 = $con->prepare("DELETE FROM products WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $msg = ' Product Deleted Successfully';
                        redirect($msg, "back", 3, "success");
                    }else{
                        $msg = "Sorry, This Product Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Product Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "active"){
        echo '<div class="profile-body">';
            echo '<h3>Activate Product</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "products");
                    if($check > 0){
                        $stmt2 = $con->prepare("UPDATE products SET Status = 1 WHERE ID = :id");
                        $stmt2->bindParam(":id", $_GET['id']);
                        $stmt2->execute();
                        $msg = ' Product Activated Successfully';
                        redirect($msg, "back", 3, "success");
                    }else{
                        $msg = "Sorry, This Product Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Product Id Is Incorrect.";
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