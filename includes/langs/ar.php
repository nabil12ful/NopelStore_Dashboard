<?PHP

    function lang($pheras){
        $words = array(
            // Sidebar
            "DASHBOARD"         => "لوحة التحكم",
            "EMPLOYEES"         => "الموظفين",
            "CUSTOMERS"         => "العملاء",
            "PRODUCTS"          => "المنتجات",
            "ORDERS"            => "الطلبات",
            "CATEGORIES"        => "الفئات",
            "SETTINGS"          => "الاعدادات",
            "TASKS"             => "المهام",
            "LOGOUT"            => "تسجيل الخروج",
            // Home Pages
            "CUSTCARD"          => "عملاء",
            "PRODCARD"          => "منتجات",
            "ORDECARD"          => "طلبات",
            "INCOCARD"          => "الدخل",
            "LAST"              => "اخر",
            "SEEALL"            => "الكل",
            "ID"                => "الرقم",
            "IMAGE"             => "الصورة",
            "TITLE"             => "العنوان",
            "BRAND"             => "الماركة",
            "CATEGORY"          => "الفئة",
            "PRICE"             => "السعر",
            "BY-EMP"            => "بواسطة الموظف",
            "STATUS"            => "الحالة",
            "CUSTOMER"          => "عميل",
            // Manage Page
            "ADDNEW"            => "إضافة",
            "DEPARTMENT"        => "القسم",
            "ROLE"              => "Role",
            // Employee
            "NAME"              => "الاسم",
            "AGE"               => "العمر",
            "USERNAME"          => "اسم المستخدم",
            "EMAIL"             => "الايميل",
            "JOB"               => "الوظيفة",
            "DATEHIRING"        => "تاريخ التعيين",
            "OPTIONS"           => "الاختيارات",
            "CONTROLS"          => "التحكم",
            "UNACTIVATED"       => "غير مفعل",
            "ACTIVATED"         => "مفعل",
            "EDIT"              => "تعديل",
            "DELETE"            => "حذف",
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