<?php


return [


    'mainIndexes' => [
        'DashboardControllers@Dashboard' => [
            'title'=>'list-dashboard',
            'viewTitle'=>'الرئيسية',
            'modulePermissions' => '',
        ],
        'TopMenuControllers@index' => [
            'title'=>'list-topMenus',
            'viewTitle'=>'القوائم العلوية',
            'modulePermissions' => 'edit-topMenu,add-topMenu,delete-topMenu,sort-topMenu,charts-topMenu',
        ],
        'BottomMenuControllers@index' => [
            'title'=>'list-bottomMenus',
            'viewTitle'=>'القوائم السفلية',
            'modulePermissions' => 'edit-bottomMenu,add-bottomMenu,delete-bottomMenu,sort-bottomMenu,charts-bottomMenu',
        ],
        'PageControllers@index' => [
            'title'=>'list-pages',
            'viewTitle'=>'الصفحات',
            'modulePermissions' => 'edit-page,add-page,delete-page,sort-page,charts-page,uploadImage-page,deleteImage-page',
        ],
        'SliderControllers@index' => [
            'title'=>'list-sliders',
            'viewTitle'=>'الاسلايدر',
            'modulePermissions' => 'edit-slider,add-slider,delete-slider,sort-slider,charts-slider,uploadImage-slider,deleteImage-slider',
        ],
        'AdvantageControllers@index' => [
            'title'=>'list-advantages',
            'viewTitle'=>'مميزاتنا',
            'modulePermissions' => 'edit-advantage,add-advantage,delete-advantage,sort-advantage,charts-advantage',
        ],
        'BenefitControllers@index' => [
            'title'=>'list-benefits',
            'viewTitle'=>'فوائدنا',
            'modulePermissions' => 'edit-benefit,add-benefit,delete-benefit,sort-benefit,charts-benefit',
        ],
        'CityControllers@index' => [
            'title'=>'list-cities',
            'viewTitle'=>'المدن',
            'modulePermissions' => 'edit-city,add-city,delete-city,sort-city,charts-city',
        ],
        'OrderControllers@index' => [
            'title'=>'list-orders',
            'viewTitle'=>'الطلبات',
            'modulePermissions' => 'edit-order,delete-order,charts-order',
        ],
        'OrderControllers@trashes' => [
            'title'=>'list-trashes',
            'viewTitle'=>'المهملات',
            'modulePermissions' => 'edit-order,delete-order,charts-order',
        ],
        // Requests Contact Us
        
        'OrderControllers@newOrders' => [
            'title'=>'list-newOrders',
            'viewTitle'=>'الطلبات الجديدة',
            'modulePermissions' => 'edit-order,delete-order,charts-order',
        ],
        'OrderControllers@sentOrders' => [
            'title'=>'list-sentOrders',
            'viewTitle'=>'الطلبات تم الارسال',
            'modulePermissions' => 'edit-order,delete-order,charts-order',
        ],
        'OrderControllers@delayedOrders' => [
            'title'=>'list-delayedOrders',
            'viewTitle'=>'الطلبات تأجلت',
            'modulePermissions' => 'edit-order,delete-order,charts-order',
        ],
        'OrderControllers@receivedOrders' => [
            'title'=>'list-receivedOrders',
            'viewTitle'=>'الطلبات تم التسليم',
            'modulePermissions' => 'edit-order,delete-order,charts-order',
        ],
        'OrderControllers@unRepliedOrders' => [
            'title'=>'list-unRepliedOrders',
            'viewTitle'=>'الطلبات عدم الرد',
            'modulePermissions' => 'edit-order,delete-order,charts-order',
        ],
        'OrderControllers@cancelledOrders' => [
            'title'=>'list-cancelledOrders',
            'viewTitle'=>'الطلبات ملغية',
            'modulePermissions' => 'edit-order,delete-order,charts-order',
        ],
        'ContactUsControllers@index' => [
            'title'=>'list-contactUs',
            'viewTitle'=>'الاتصال بنا',
            'modulePermissions' => 'edit-contactUs,delete-contactUs,reply-contactUs,charts-contactUs',
        ],
        'GroupsControllers@index' => [
            'title'=>'list-groups',
            'viewTitle'=>'مجموعات المشرفين',
            'modulePermissions' => 'edit-group,add-group,delete-group,sort-group,charts-group',
        ],
        'UsersControllers@index' => [
            'title'=>'list-users',
            'viewTitle'=>'المشرفين والاداريين',
            'modulePermissions' => 'edit-user,add-user,delete-user,sort-user,charts-user,uploadImage-user,deleteImage-user',
        ],
        'LogControllers@index' => [
            'title'=>'list-logs',
            'viewTitle'=>'سجلات الدخول للنظام',
            'modulePermissions' => 'edit-log,delete-log,sort-log,charts-log',
        ],
        'PhotoControllers@index' => [
            'title'=>'list-photos',
            'viewTitle'=>'مكتبة الصور',
            'modulePermissions' => 'edit-photo,add-photo,delete-photo,sort-photo,charts-photo,uploadImage-photo,deleteImage-photo',
        ],
        'FileControllers@index' => [
            'title'=>'list-files',
            'viewTitle'=>'مكتبة الملفات',
            'modulePermissions' => 'edit-file,add-file,delete-file,sort-file,charts-file,uploadImage-file,deleteImage-file',
        ],
        'VariablesControllers@index' => [
            'title'=>'list-variables',
            'viewTitle'=>'اعدادات عامة',
            'modulePermissions' => 'edit-variable',
        ],
        'VariablesControllers@panel' => [
            'title'=>'list-variables2',
            'viewTitle'=>'اعدادات لوحة التحكم',
            'modulePermissions' => 'edit-variable2',
        ],
        'BlockedUsersControllers@index' => [
            'title'=>'list-blockedUsers',
            'viewTitle'=>'الاعضاء المحظورة',
            'modulePermissions' => 'edit-blockedUser,delete-blockedUser,sort-blockedUser,charts-blockedUser',
        ],  


    ],

    


];
