<?php
    ob_start();
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }
    $pageTitle = "Settings";
    include('init.php');

    $p = isset($_GET['p']) ? $_GET['p'] : "manage";

    if($p == "manage"){ // Manage page
        $stmt = $con->prepare("SELECT employee.*, roles.Name, sections.Name As Section, settings.* FROM employee
                                INNER JOIN roles ON roles.ID = employee.Role_ID
                                INNER JOIN sections ON sections.ID = employee.Section_ID
                                INNER JOIN settings ON settings.Emp_ID = employee.ID
                                WHERE employee.ID = ?");
        $stmt->execute(array($_SESSION['id']));
        $emp = $stmt->fetch();
        ?>
        <div class="profiles">
            <div class="pro-header profile">
                <div class="pro-img">
                    <img src="layout/images/user.png">
                </div>
                <div class="pro-name">
                    <h2><?php echo $emp['Full_Name'] ?></h2>
                    <h5><?php echo $emp['Name'] ?> For <?php echo $emp['Section'] ?></h5>
                    <div class="d-flex flex-wrap">
                        <a href="profile.php" class="btn btn-primary w-50">Profile</a>
                    </div>
                </div>
            </div>
            <div class="profile-body">
                <form action="?p=update" method="POST">
                    <input type="hidden" name="id" value="<?php echo $emp['Emp_ID'] ?>">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="">Dark Mode</label>
                        </div>
                        <div class="col-sm-8 px-2">
                            <div class="switch-btn">
                                <input 
                                    type="checkbox" 
                                    <?php
                                        if($emp['Display_Mode'] == "night"){
                                            echo "checked";
                                        }
                                    ?> 
                                    name="mode"
                                    value="night"
                                    id="dark">
                                <label for="dark">Toggel</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="">Language</label>
                        </div>
                        <div class="col-sm-8">
                            <div class=" select-custom w-25">
                                <select name="lang" id="">
                                    <option value="">Language</option>
                                    <option 
                                        <?php
                                            if($emp['Language'] == "en"){
                                                echo "selected";
                                            }
                                        ?>
                                        value="en">English</option>
                                    <option 
                                        <?php
                                            if($emp['Language'] == "ar"){
                                                echo "selected";
                                            }
                                        ?>
                                        value="ar">عربي</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Start Submit Button -->
                    <div class="form-group row">
                        <div class="col-sm-5 offset-5 my-4">
                            <input type="submit" class="btn btn-primary" value="Save">
                        </div>
                    </div>
                    <!-- End Submit Button -->
                </form>
            </div>
        </div>

        <?php
    }elseif($p == "update"){ // Update
        echo '<div class="profile-body">';
            echo '<h3>Update Setting</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            $id     = $_POST['id'];
            if(isset($_POST['mode'])){
                $mode = "night";
            }else{
                $mode = "light";
            }
            $lang   = $_POST['lang'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(is_numeric($id)){ 
                $check = checkItem($id, "Emp_ID", "settings");
                if($check < 1){
                    $errors[] = "This User Is Not Exist.";
                }
            }
            
            // Update Data In Database
            if(empty($errors)){
                $stmt = $con->prepare("UPDATE settings SET `Display_Mode` = ?, Language = ? WHERE Emp_ID = ?");
                $stmt->execute(array($mode, $lang, $id));
                redirect("Saved Successfully.", "back", 4, "success");
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
    }else{
        $msg = "Sorry, Not Found This Page";
        redirect($msg, "back");
    }
    include($temps . "footer.php");
    ob_end_flush();

?>