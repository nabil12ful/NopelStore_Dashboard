<body class="light-mode">

        <!-- side bar section start -->
        <input type="checkbox" name="" id="nav-toggle">
        <div class="sidebar bg-color-m">
            <div class="sidebar-brand">
                
                <h2><span class="fab fa-redhat"></span><span class="lo">NopelStore</span></h2>
                <!-- <img src="<?php echo $imgs; ?>logo4.png" width="190px"> -->
            </div>

            <!-- side bar menu start -->
            <div class="sidebar-menu">
                <ul>
                    <li>
                        <a href="index.php" <?php if($pageTitle == "Dashboard"){echo 'class="active"';} ?> >
                            <span class="fa fa-tachometer-alt"></span>
                            <span><?PHP lang("DASHBOARD") ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="employees.php" <?php if($pageTitle == "Employees" || $pageTitle == "Department" || $pageTitle == "Role"){echo 'class="active"';} ?>>
                            <span class="fa fa-briefcase"></span>
                            <span><?PHP lang("EMPLOYEES") ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="customers.php" <?php if($pageTitle == "Customers"){echo 'class="active"';} ?> >
                            <span class="fa fa-users"></span>
                            <span><?PHP lang("CUSTOMERS") ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="products.php" <?php if($pageTitle == "Products"){echo 'class="active"';} ?>>
                            <span class="fa fa-warehouse"></span>
                            <span><?PHP lang("PRODUCTS") ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="orders.php" <?php if($pageTitle == "Orders"){echo 'class="active"';} ?>>
                            <span class="fa fa-shopping-bag"></span>
                            <span><?PHP lang("ORDERS") ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="categories.php" <?php if($pageTitle == "Categories"){echo 'class="active"';} ?>>
                            <span class="fas fa-tags"></span>
                            <span><?PHP lang("CATEGORIES") ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="settings.php" <?php if($pageTitle == "Settings"){echo 'class="active"';} ?>>
                            <span class="fas fa-cog"></span>
                            <span><?PHP lang("SETTINGS") ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="tasks.php" <?php if($pageTitle == "Tasks"){echo 'class="active"';} ?>>
                            <span class="fas fa-clipboard-list"></span>
                            <span><?PHP lang("TASKS") ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="../index.php" target="_blank">
                            <span class="fas fa-store"></span>
                            <span>Visit Shop</span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <span class="fas fa-sign-out-alt"></span>
                            <span><?PHP lang("LOGOUT") ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- side bar menu end -->
        </div>
        <!-- side bar section end -->