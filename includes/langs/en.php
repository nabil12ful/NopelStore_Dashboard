<?PHP

    function lang($pheras){
        $words = array(
            // Sidebar
            "DASHBOARD"         => "Dashboard",
            "EMPLOYEES"         => "Employees",
            "CUSTOMERS"         => "Customers",
            "PRODUCTS"          => "Products",
            "ORDERS"            => "Orders",
            "CATEGORIES"        => "Categories",
            "SETTINGS"          => "Settings",
            "TASKS"             => "Tasks",
            "LOGOUT"            => "Logout",
            // Home Pages
            "CUSTCARD"          => "Customers",
            "PRODCARD"          => "Products",
            "ORDECARD"          => "Orders",
            "INCOCARD"          => "Income",
            "LAST"              => "Last",
            "SEEALL"            => "See All ",
            "ID"                => "ID",
            "IMAGE"             => "Image",
            "TITLE"             => "Title",
            "BRAND"             => "Brand",
            "CATEGORY"          => "Category",
            "PRICE"             => "Price",
            "BY-EMP"            => "By Employee",
            "STATUS"            => "Status",
            "CUSTOMER"          => "Customer",
            // Manage Page
            "ADDNEW"            => "Add New",
            "DEPARTMENT"        => "DPRTMent",
            "ROLE"              => "Role",
            // Employee
            "NAME"              => "Name",
            "AGE"               => "Age",
            "USERNAME"          => "Username",
            "EMAIL"             => "E-Mail",
            "JOB"               => "Job",
            "DATEHIRING"        => "Date Of Hiring",
            "OPTIONS"           => "Options",
            "CONTROLS"          => "Controls",
            "UNACTIVATED"       => "Unactivated",
            "ACTIVATED"         => "Activated",
            "EDIT"              => "Edit",
            "DELETE"            => "Delete",
            // Employee Edit Page
            "EMP_EDIT_TITLE"    => "Edit Employee Data",
            // forms 
            "FULL_NAME"         => "Full Name",
            "EMP_NAME_REQ"      => "Employee Name Is Required",
            "EMP_USER_REQ"      => "Employee Username Is Required",
            "EMP_EMAIL_REQ"     => "Employee Email Is Required",
            "PHONE"             => "Phone",
            "EMP_PHONE_REQ"     => "Employee Phone Number Is Required",
            "PASSWORD"          => "Password",
            "COUNTRY"           => "Country",
            "EMP_COUNTRY_REQ"   => "Employee Country Is Required",
            "CITY"              => "City",
            "EMP_CITY_REQ"      => "Employee City Is Required",
            
        );

        echo $words[$pheras];
    }