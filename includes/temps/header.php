    <!-- main content start -->
    <div class="main-content">
        <!-- start header -->
        <header class="">
            <h2>
                <label for="nav-toggle">
                    <span class="fas fa-bars"></span>
                </label>

                <?php echo $pageTitle ?>
            </h2>

            <div class="search-wrapper">
                <span class="fa fa-search"></span>
                <input id="search" type="search" name="" placeholder="Search here">
            </div>

            <div class="user-wrapper">
                <img src="<?php echo $imgs; ?>user.png" width="60px" height="60px">
                <div>
                    <?php
                        $stmt = $con->prepare("SELECT employee.*, roles.Name, sections.Name As Section FROM employee
                                                INNER JOIN roles ON roles.ID = employee.Role_ID
                                                INNER JOIN sections ON sections.ID = employee.Section_ID
                                                WHERE employee.ID = ?");
                        $stmt->execute(array($_SESSION['id']));
                        $emp = $stmt->fetch();
                    ?>
                    <h4>@<?php echo $emp['Username'] ?></h4>
                    <small><?php echo $emp['Name'] ?> For <?php echo $emp['Section'] ?></small>
                </div>
            </div>
        </header>
        <!-- end header -->

        <!-- start main -->
        <main>