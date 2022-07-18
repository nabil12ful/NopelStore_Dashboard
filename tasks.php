<?php
    ob_start();
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }
    $pageTitle = "Tasks";
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
        ## Get Section Id
        $stmtS = $con->prepare("SELECT Section_ID FROM employee WHERE ID = ?");
        $stmtS->execute(array($_SESSION['id']));
        $emp = $stmtS->fetch();
        ## Get Tasks For User Section Only
        $stmt = $con->prepare("SELECT * FROM tasks WHERE For_Section = ? $sort");
        $stmt->execute(array($emp['Section_ID']));
        $tasks = $stmt->fetchAll();
        
        ?>
        <div class="products">
            <div class="pro-header">
                <!-- start options -->
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
                    <?php
                    foreach($tasks as $task){
                        $id     = $task['ID'];
                        $title  = $task['Title'];
                        $desc   = $task['Description'];
                        $time   = $task['DateTime'];
                        $status = $task['Status'];
                        $finish = $task['Finshed_Date'];
                    ?>
                        <div class="task">
                            <div class="d-sm-flex align-items-md-center flex-wrap flex-lg-nowrap py-0 ps-sm-0 card-body">
                                <!-- <div class="text-start text-sm-center ps-0 ps-sm-4 mb-2 mb-sm-0 col-lg-1 col-auto">
                                    <div class="check-lg inbox-check me-sm-2">
                                        <div class="checkbox">
                                            <input type="checkbox" name="check[]" id="<?php echo $id; ?>">
                                            <label for="<?php echo $id; ?>"></label>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="d-block px-0 mb-4 mb-md-0 col-lg-8 col-10">
                                    <div class="mb-2">
                                        <div>
                                            <h5 class="tit text-left <?php if($status == 2){echo 'done';} ?>"><?php echo "<a style='text-decoration:none;' href='?p=view&id=$id'>" . $title . "</a>" ?></h5>
                                            <div class="d-inline-block d-sm-flex">
                                                <div>
                                                    <h6 class="fw-normal text-gray mb-3 mb-sm-0 pt-1"> <span class="text-success"> Start At:</span> <i class="fa fa-clock" aria-hidden="true"></i> <?php echo $time; ?></h6>
                                                </div>
                                                <div class="ms-sm-3 stat">
                                                    <?php
                                                        if($status == 0){
                                                            ?>
                                                            <span class="super-badge badge-lg badge bg-purple pl-2 pr-2 pt-1 pb-1 color-w">Waiting</span>
                                                            <?php
                                                        }
                                                        elseif($status == 1){
                                                            ?>
                                                            <span class="super-badge badge-lg badge bg-warning pl-2 pr-2 pt-1 pb-1 color-w">In Progress</span>
                                                            <?php
                                                        }
                                                        elseif($status == 2){
                                                            ?>
                                                            <span class="super-badge badge-lg badge bg-success pl-2 pr-2 pt-1 pb-1 color-w">Done</span>
                                                            <?php
                                                        }
                                                    ?>
                                                    
                                                </div>
                                                <?php
                                                if($finish !== "0000-00-00 00:00:00"){
                                                    $pre = date_diff(date_create($time), date_create($finish));
                                                    //echo $pre->format("%d Days %h Hours");
                                                
                                                ?>
                                                    <div>
                                                        <h6 class="fw-normal text-gray mb-3 mb-sm-0 pt-1">
                                                            <span class="red"> Finish At: </span>
                                                            <i class="fa fa-clock" aria-hidden="true"></i>
                                                            <?php echo $finish; ?>
                                                            <span class="red">During: </span>
                                                            <?php echo $pre->format("%d Days %h Hours %I Minutes") ?>
                                                        </h6>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="text-left">
                                            <span class="dec <?php if($status == 2){echo 'done';} ?> fw-normal "><?php echo $desc; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-lg-block d-xl-inline-flex align-items-center ms-lg-auto text-end justify-content-end px-md-0 col-xl-2 col-lg-2 col-sm-2 col-10 ml-4">
                                    <div class="opt">
                                        <div class="opt-item">
                                            <a href="tasks.php?p=edit&id=<?php echo $id; ?>" class="edit">
                                                <i class="fa fa-edit"></i>
                                                <div class="det-ed">
                                                    Edit
                                                </div>
                                            </a>
                                            
                                        </div>
                                        <div class="opt-item">
                                            <?php
                                                if($status == 0){
                                                    ?>
                                                    <a href="tasks.php?p=progress&id=<?php echo $id; ?>" class="prog">
                                                        <i class="fa fa-check-circle"></i>
                                                        <div class="det-pr">
                                                            Progress
                                                        </div>
                                                    </a>
                                                    <?php
                                                }
                                                elseif($status == 1){
                                                    ?>
                                                        <a href="tasks.php?p=done&id=<?php echo $id; ?>" class="done">
                                                            <i class="fa fa-check-circle"></i>
                                                            <div class="det-do">
                                                                Done
                                                            </div>
                                                        </a>
                                                    <?php
                                                }
                                                elseif($status == 2){
                                                    ?>
                                                    <a href="tasks.php?p=progress&id=<?php echo $id; ?>" class="prog">
                                                        <i class="fa fa-check-circle"></i>
                                                        <div class="det-pr">
                                                            Progress
                                                        </div>
                                                    </a>
                                                    <?php
                                                }
                                            ?>
                                            
                                        </div>
                                        <div class="opt-item">
                                            <a href="tasks.php?p=delete&id=<?php echo $id; ?>" class="del">
                                                <i class="fa fa-trash-alt"></i>
                                                <div class="det-de">
                                                    Delete
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
    }elseif($p == "view"){ // View Page
        echo '<div class="profile-body">';
            echo '<h3>Edit Task</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "id", "tasks");
                    if($check > 0){
                        // Get Tasks Data
                        $stmt2 = $con->prepare("SELECT * FROM tasks WHERE ID = ?");
                        $stmt2->execute(array($_GET['id']));
                        $row = $stmt2->fetch();
                        ?>
                            <form class="form-horizonal was-validated" novalidate>
                                <!-- Start Status Section -->
                                <div class="row">
                                    <div class="col-sm-2">
                                        <h4>Status:</h4>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php
                                        if($row['Status'] == 0){
                                            echo '<h4 class="text-danger">New</h4>';
                                        }elseif($row['Status'] == 1){
                                            echo '<h4 class="text-warning">Progress</h4>';
                                        }elseif($row['Status'] == 2){
                                            echo '<h4 class="text-success">Done</h4>';
                                        }
                                        ?>
                                        
                                    </div>
                                </div><hr>
                                <!-- End Status Section -->
                                <!-- Start Title -->
                                <div class="row-n">
                                    <div class="col-n-12">
                                        <h2>Insert title:</h2>
                                        <input 
                                            type="text" 
                                            placeholder="Title for Task.."
                                            name="title"
                                            class="text-dark"
                                            disabled
                                            value="<?php echo $row['Title'] ?>">
                                    </div>
                                </div>
                                <hr>
                                <!-- End Title -->
                                <!-- Start Description -->
                                <div class="row-n">
                                    <div class="col-n-12">
                                        <h2>Type a description</h2>
                                        <textarea name="desc" placeholder="Type what you want here?" rows=10 disabled class="text-dark"><?php echo $row['Description'] ?></textarea>
                                    </div>
                                </div>
                                <!-- End Decription -->
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
    }elseif($p == "edit"){ // Edit Page
        echo '<div class="profile-body">';
            echo '<h3>Edit Task</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "id", "tasks");
                    if($check > 0){
                        // Get Tasks Data
                        $stmt2 = $con->prepare("SELECT * FROM tasks WHERE ID = ?");
                        $stmt2->execute(array($_GET['id']));
                        $row = $stmt2->fetch();
                        ?>
                            <form action="?p=update" method="POST" class="form-horizonal was-validated" novalidate>
                                <input type="hidden" name="id" value="<?php echo $row['ID'] ?>">
                                <!-- Start Status Section -->
                                <div class="row">
                                    <div class="col-sm-2">
                                        <h4>Status:</h4>
                                    </div>
                                    <div class="col-sm-4">
                                        <?php
                                        if($row['Status'] == 0){
                                            echo '<h4 class="text-danger">New</h4>';
                                        }elseif($row['Status'] == 1){
                                            echo '<h4 class="text-warning">Progress</h4>';
                                        }elseif($row['Status'] == 2){
                                            echo '<h4 class="text-success">Done</h4>';
                                        }
                                        ?>
                                        
                                    </div>
                                </div><hr>
                                <!-- End Status Section -->
                                <!-- Start Title -->
                                <div class="row-n">
                                    <div class="col-n-12">
                                        <h2>Insert title:</h2>
                                        <input 
                                            type="text" 
                                            placeholder="Title for Task.."
                                            name="title"
                                            value="<?php echo $row['Title'] ?>">
                                    </div>
                                </div>
                                <hr>
                                <!-- End Title -->
                                <!-- Start Description -->
                                <div class="row-n">
                                    <div class="col-n-12">
                                        <h2>Type a description</h2>
                                        <textarea name="desc" placeholder="Type what you want here?" rows=10><?php echo $row['Description'] ?></textarea>
                                    </div>
                                </div>
                                <!-- End Decription -->
                                <!-- Start Submit Button -->
                                <div class="sub">
                                    <input type="submit" class="btn btn-primary" value="Update">
                                    <a href="?p=delete&id=<?php echo $row['ID']; ?>" class="btn btn-danger">Delete</a>
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
            echo '<h3>Update Task</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Get Data From POST Request
            $id       = $_POST['id'];
            $title    = $_POST['title']; 
            $desc     = $_POST['desc'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(is_numeric($id)){ 
                $check = checkItem($id, "id", "tasks");
                if($check < 1){
                    $errors[] = "This Task Is Not Exist.";
                }
            }

            if(empty($title)){ // Check If Title Field Is Empty
                $errors[] = "Title Can't Be <strong>Empty</strong>";
            }
            if(empty($desc)){ // Check If Description Field Is Empty
                $errors[] = "Description Can't Be <strong>Empty</strong>";
            }
            
            // Echo Errors Is Exist
            foreach($errors as $err){
                echo "<h5 class='text-center alert alert-danger'>$err";
                    echo "<br><a href='?p=edit&id=$id' class='alert-link'> Back To Edit Task Page</a>";
                echo "</h5>";
            }
            // Update Data In Database
            if(empty($errors)){
                $stmtSec = $con->prepare("SELECT employee.Section_ID, roles.Access FROM employee
                                        INNER JOIN roles ON roles.ID = employee.Role_ID
                                        WHERE employee.ID = ?");
                $stmtSec->execute(array($_SESSION['id']));
                $emp = $stmtSec->fetch();
                if($emp['Access'] == "Full" || $emp['Access'] == "FullWrite"){
                    $stmt = $con->prepare("UPDATE tasks SET `Title` = ?, Description = ? WHERE ID = ?");
                    $stmt->execute(array($title, $desc, $id));
                    redirect("Updated Task Successfully.", "back", 2, "success");
                }else{
                    $msg = "You Can't Updated Tasks, Back To Your Manager.";
                    redirect($msg, "Back");
                }
            }

        }else{
            $msg = "You Can't Browse This Page Directory.";
            redirect($msg, "back");
        }
        echo '</div>';
    }elseif($p == "add"){ // Add new
        echo '<div class="profile-body">';
            echo '<h3>Add Task</h3><hr>';
            echo '<div class="user-info">';?>
                <form action="?p=insert" method="POST" class="form-horizonal was-validated" novalidate enctype="multipart/form-data">
                    <!-- Start Title -->
                    <div class="row-n">
                        <div class="col-n-12">
                            <h2>Insert title:</h2>
                            <input 
                                type="text" 
                                placeholder="Title for Task.."
                                name="title">
                        </div>
                    </div>
                    <hr>
                    <!-- End Title -->
                    <!-- Start Description -->
                    <div class="row-n">
                        <div class="col-n-12">
                            <h2>Type a description</h2>
                            <textarea name="desc" placeholder="Type what you want here?" rows=10></textarea>
                        </div>
                    </div>
                    <!-- End Decription -->
                    <!-- Start Submit Button -->
                    <div class="sub">
                        <input type="submit" class="btn btn-success" value="Add">
                        <input type="reset" class="btn btn-danger" value="Cancel">
                    </div>
                    <!-- End Submit Button -->
                </form>
            <?php
            echo '</div>';
        echo '</div>';
    }elseif($p == "insert"){
        echo '<div class="profile-body">';
            echo '<h3>Insert Task</h3><hr>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Get Data From POST Request
            $title    = $_POST['title']; 
            $desc     = $_POST['desc'];
            // Validations
            $errors = array(); // Array Of Form Errors
            if(empty($title)){ // Check If Title Field Is Empty
                $errors[] = "Title Can't Be <strong>Empty</strong>";
            }
            if(empty($desc)){ // Check If Description Field Is Empty
                $errors[] = "Description Can't Be <strong>Empty</strong>";
            }
            // Echo Errors Is Exist
            foreach($errors as $err){
                echo "<h5 class='text-center alert alert-danger'>$err";
                    echo "<br><a href='?p=edit&id=$id' class='alert-link'> Back To Edit Task Page</a>";
                echo "</h5>";
            }
            // Update Data In Database
            if(empty($errors)){
                $stmtSec = $con->prepare("SELECT employee.Section_ID, roles.Access FROM employee
                                        INNER JOIN roles ON roles.ID = employee.Role_ID
                                        WHERE employee.ID = ?");
                $stmtSec->execute(array($_SESSION['id']));
                $emp = $stmtSec->fetch();
                if($emp['Access'] == "Full" || $emp['Access'] == "FullWrite"){
                    $stmt = $con->prepare("INSERT INTO tasks(Title, Description, For_Section, DateTime)
                                            VALUES(:title, :desc, :section, NOW())");
                    $stmt->execute(array(
                        "title"   => $title,
                        "desc"    => $desc,
                        "section" => $emp['Section_ID']
                    ));
                    redirect("Inserted Task Successfully.", "back", 2, "success"); 
                }else{
                    $msg = "You Can't Added New Tasks, Back To Your Manager.";
                    redirect($msg, "Back");
                }
            }

        }else{
            $msg = "You Can't Browse This Page Directory.";
            redirect($msg, "back");
        }
        echo '</div>';
    }elseif($p == "delete"){
        echo '<div class="profile-body">';
            echo '<h3>Delete Task</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "tasks");
                    if($check > 0){
                        $stmtSec = $con->prepare("SELECT roles.Access FROM employee
                                                INNER JOIN roles ON roles.ID = employee.Role_ID
                                                WHERE employee.ID = ?");
                        $stmtSec->execute(array($_SESSION['id']));
                        $emp = $stmtSec->fetch();
                        if($emp['Access'] == "Full" || $emp['Access'] == "FullWrite"){
                            $stmt2 = $con->prepare("DELETE FROM tasks WHERE ID = :id");
                            $stmt2->bindParam(":id", $_GET['id']);
                            $stmt2->execute();
                            $msg = ' Task Deleted Successfully';
                            redirect($msg, "back", 3, "success");
                        }else{
                            $msg = "You Can't Delete Tasks, Back To Your Manager.";
                            redirect($msg, "Back");
                        }
                    }else{
                        $msg = "Sorry, This Task Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Task Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "progress"){
        echo '<div class="profile-body">';
            echo '<h3>Update Task</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "tasks");
                    if($check > 0){
                        $stmtSec = $con->prepare("SELECT roles.Access FROM employee
                                                INNER JOIN roles ON roles.ID = employee.Role_ID
                                                WHERE employee.ID = ?");
                        $stmtSec->execute(array($_SESSION['id']));
                        $emp = $stmtSec->fetch();
                        if($emp['Access'] == "Full" || $emp['Access'] == "Write" || $emp['Access'] == "FullWrite"){
                            $stmt2 = $con->prepare("UPDATE tasks SET Status = 1 WHERE ID = :id");
                            $stmt2->bindParam(":id", $_GET['id']);
                            $stmt2->execute();
                            $msg = ' Task Opened Successfully';
                            redirect($msg, "back", 3, "success");
                        }else{
                            $msg = "You Can't Updated Tasks, Back To Your Manager.";
                            redirect($msg, "Back");
                        }
                    }else{
                        $msg = "Sorry, This Task Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Task Id Is Incorrect.";
                    redirect($msg);
                }
            echo '</div>';
        echo '</div>';
    }elseif($p == "done"){
        echo '<div class="profile-body">';
            echo '<h3>Update Task</h3><hr>';
            echo '<div class="user-info">';
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $check = checkItem($_GET['id'], "ID", "tasks");
                    if($check > 0){
                        $stmtSec = $con->prepare("SELECT roles.Access FROM employee
                                                INNER JOIN roles ON roles.ID = employee.Role_ID
                                                WHERE employee.ID = ?");
                        $stmtSec->execute(array($_SESSION['id']));
                        $emp = $stmtSec->fetch();
                        if($emp['Access'] == "Full" || $emp['Access'] == "Write" || $emp['Access'] == "FullWrite"){
                            $stmt2 = $con->prepare("UPDATE tasks SET Status = 2, Finshed_Date = now() WHERE ID = :id");
                            $stmt2->bindParam(":id", $_GET['id']);
                            $stmt2->execute();
                            $msg = ' Task Closed Successfully';
                            redirect($msg, "back", 3, "success");
                        }else{
                            $msg = "You Can't Updated Tasks, Back To Your Manager.";
                            redirect($msg, "Back");
                        }
                    }else{
                        $msg = "Sorry, This Task Is Not Exist.";
                        redirect($msg);
                    }
                }else{
                    $msg = "This Task Id Is Incorrect.";
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