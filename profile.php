<?php
    ob_start();
    session_start();
    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }
    $pageTitle = "Profile";
    include('init.php');

    $p = isset($_GET['p']) ? $_GET['p'] : "manage";

    if($p == "manage"){ // Manage page
        $stmt = $con->prepare("SELECT employee.*, roles.Name, sections.Name As Section FROM employee
                                INNER JOIN roles ON roles.ID = employee.Role_ID
                                INNER JOIN sections ON sections.ID = employee.Section_ID
                                WHERE employee.ID = ?");
        $stmt->execute(array($_SESSION['id']));
        $emp = $stmt->fetch();
        ?>
        <div class="profiles">
            <div class="pro-header profile">
                <div class="pro-img">
                    <img src="<?php echo $imgs ?>user.png">
                </div>
                <div class="pro-name">
                    <h2><?php echo $emp['Full_Name'] ?></h2>
                    <h5><?php echo $emp['Name'] ?> For <?php echo $emp['Section'] ?></h5>
                </div>
            </div>
            <div class="profile-body">
                        <h3>User Information</h3>
                        <div class="user-info">
                            <form name="accForm">
                                <div class="row-n">
                                    <div class="col-n-6">
                                        <h5>Username</h5>
                                        <input 
                                            name="username" 
                                            class="text-dark" 
                                            type="text" 
                                            value="<?php echo $emp['Username'] ?>" 
                                            placeholder="user123"
                                            disabled>
                                    </div>
                                    <div class="col-n-6">
                                        <h5>Email</h5>
                                        <input 
                                            name="email" 
                                            class="text-dark"
                                            type="email" 
                                            value="<?php echo $emp['Email'] ?>" 
                                            placeholder="user@domain.com"
                                            disabled>
                                    </div>
                                </div>
                                <div class="row-n">
                                    <div class="col-n-6">
                                        <h5>Full Name</h5>
                                        <input 
                                            name="full" 
                                            class="text-dark"
                                            type="text" 
                                            value="<?php echo $emp['Full_Name'] ?>" 
                                            placeholder="Name" 
                                            disabled>
                                    </div>
                                    <div class="col-n-6">
                                        <h5>Age</h5>
                                        <input 
                                            name="age"
                                            class="text-dark"
                                            type="text" 
                                            value="<?php echo getAge($emp['Birthdate'])  ?>" 
                                            placeholder="Name" 
                                            disabled>
                                    </div>
                                </div>
                                <hr>
                                <div class="contact-info">
                                    <h3>Contact Information</h3>
                                    <div class="row-n">
                                        <div class="col-n-4">
                                            <h5><i class="fas fa-phone-alt face"></i> Phone</h5>
                                            <input 
                                                type="number" 
                                                class="text-dark" 
                                                value="<?php echo $emp['Phone'] ?>" 
                                                placeholder="Phone" 
                                                disabled>
                                        </div>
                                        <div class="col-n-4">
                                            <h5><i class="fas fa-phone-alt face"></i> Country</h5>
                                            <input 
                                                type="text" 
                                                class="text-dark" 
                                                value="<?php echo $emp['Country'] ?>" 
                                                placeholder="Egypt" 
                                                disabled>
                                        </div>
                                        <div class="col-n-4">
                                            <h5><i class="fas fa-phone-alt face"></i> City</h5>
                                            <input 
                                                type="text" 
                                                class="text-dark" 
                                                value="<?php echo $emp['City'] ?>" 
                                                placeholder="Cairo" 
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="row-n">
                                        <div class="col-n-12">
                                            <h5>Address</h5>
                                            <input 
                                                name="address" 
                                                class="text-dark" 
                                                type="text" 
                                                value="<?php echo $emp['Address'] ?>" 
                                                placeholder="Address" 
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                    </div>
        </div>

        <?php
    }else{
        $msg = "Sorry, Not Found This Page";
        redirect($msg, "back");
    }
    include($temps . "footer.php");
    ob_end_flush();

?>