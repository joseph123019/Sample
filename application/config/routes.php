<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = "login";
$route['404_override'] = 'myerror';


/*********** USER DEFINED ROUTES *******************/
$route['customer_name'] = 'sales/set_customer';

$route['loginMe'] = 'login/loginMe';
$route['reset_password'] = 'login/reset_password_view';
$route['password_reset'] = 'login/reset_password';
$route['dashboard'] = 'user';
$route['dashboard2'] = 'dashboard2';

$route['client_view'] = 'dashboard2/client_view';
$route['client_view/(:num)'] = 'dashboard2/client_view/$1';

$route['cashierUsers_view'] = 'dashboard2/cashierUsers_view';
$route['cashierUsers_view/(:num)'] = 'dashboard2/cashierUsers_view/$1';

$route['playlist_view'] = 'dashboard2/playlist_view';
$route['playlist_view/(:num)'] = 'dashboard2/playlist_view/$1';

$route['hrmanager_view'] = 'dashboard2/hrmanager_view';
$route['hrmanager_view/(:num)'] = 'dashboard2/hrmanager_view/$1';

$route['mediamanager_view'] = 'dashboard2/mediamanager_view';
$route['mediamanager_view/(:num)'] = 'dashboard2/mediamanager_view/$1';

$route['inventorymanager_view'] = 'dashboard2/inventorymanager_view';
$route['inventorymanager_view/(:num)'] = 'dashboard2/inventorymanager_view/$1';

$route['employee_view'] = 'dashboard2/emplist_view';
$route['employee_view/(:num)'] = 'dashboard2/emplist_view/$1';

$route['logs_view'] = 'dashboard2/logs_view';
$route['logs_view/(:num)'] = 'dashboard2/logs_view/$1';

$route['non_local_client'] = 'user/non_local_client';
$route['editReceipt'] = 'user/edit_receipt';
$route['logout'] = 'user/logout';
$route['userListing'] = 'user/userListing';
$route['userListing/(:num)'] = "user/userListing/$1";
$route['addNew'] = "user/addNew";
$route['MainURL'] = "user/mainURL";
$route['MainURL/(:any)'] = "user/mainURL/$1";

$route['addNewUser'] = "user/addNewUser";
$route['editOld'] = "user/editOld";
$route['editOld/(:num)'] = "user/editOld/$1";
$route['editUser'] = "user/editUser";
$route['deleteUser'] = "user/deleteUser";
$route['unDeleteUser'] = "user/unDeleteUser";
$route['loadChangePass'] = "user/loadChangePass";
$route['changePassword'] = "user/changePassword";
$route['pageNotFound'] = "user/pageNotFound";
$route['checkEmailExists'] = "user/checkEmailExists";
$route['checkUserNameExists'] = "user/checkUserNameExists";
$route['checkInventory'] = "Sales_Price_reverse/check_inventory";
$route['login-history'] = "user/loginHistoy";
$route['login-history/(:num)'] = "user/loginHistoy/$1";
$route['login-history/(:num)/(:num)'] = "user/loginHistoy/$1/$2";

$route['roleListing'] = 'role/roleListing';
$route['roleListing/(:num)'] = "role/roleListing/$1";
$route['addNewRole'] = "role/addNewRole";
$route['editOldRole'] = "role/editOldRole";
$route['editOldRole/(:num)'] = "role/editOldRole/$1";
$route['editRole'] = "role/editRole";
$route['deleteRole'] = "role/deleteRole";
$route['editAccessOldRole'] = 'role/editAccessOldRole';
$route['editAccessOldRole/(:num)'] = "role/editAccessOldRole/$1";
$route['editAccessRole'] = 'role/editAccessRole';

$route['clientListing'] = 'client/clientListing';
$route['clientListing/(:num)'] = "client/clientListing/$1";

$route['addNewClient'] = "client/addNewClient";
$route['checkClientNameExists'] = "client/checkClientNameExists";
$route['resetReceipt'] = "client/resetReceipt";
$route['resetInventory'] = "client/resetInventory";
$route['deleteClient'] = "client/deleteClient";
$route['updateClient'] = "client/updateClient";
$route['checkURL'] = "client/checkURL";
$route['editClient'] = "client/editClient";
$route['editOldClient'] = 'client/editOldClient';
$route['editOldClient/(:num)'] = "client/editOldClient/$1";
$route['storeList'] = 'client/storeList';
$route['storeList/(:num)'] = "client/storeList/$1";
$route['edit_client_media'] = 'client/edit_client_media';

$route['forgotPassword'] = "login/forgotPassword";
$route['resetPasswordUser'] = "login/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "login/resetPasswordConfirmUser";
$route['resetPasswordConfirmUser/(:any)'] = "login/resetPasswordConfirmUser/$1";
$route['resetPasswordConfirmUser/(:any)/(:any)'] = "login/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "login/createPasswordUser";

$route['PasswordManager'] = "Password_manager";
$route['bulk_show_password'] = "Password_manager/bulk_show_password";
$route['bulk_generate_password'] = "Password_manager/bulk_generate_password";

$route['delete_store'] = "Store_manager/delete_store";

$route['StoreManager'] = "Store_manager";

$route['Support'] = "EmailSupport/index";
$route['emailSupport'] = "EmailSupport/emailSupport";

$route['materialListing'] = "Materials";
$route['itemListing'] = "Items";
$route['pos'] = "Sales";
$route['pos_price_reverse'] = "Sales_Price_reverse";
$route['voidgasitems'] = "Sales_Price_reverse/voiditems";
$route['voidItems'] = "Sales/voiditems";
$route['suspended'] = "Sales/suspended";
$route['get_discount_percent'] = "Sales/get_discount_per_customer_type";
$route['passwordValidation'] = 'Sales/password_validate';
$route['passwordReloadValidation'] = 'Sales/password_reload_validate';
$route['passwordendValidation'] = 'Sales/password_validate_end';
$route['shift_start'] = 'Sales/shift_start';
$route['end_shift'] = 'Sales/end_shift';
$route['reload_credit'] = 'Sales/reload_credit';
$route['reload_credit_receipt'] = 'Sales/reload_credit_receipt';

$route['beg_end_inv'] = 'Beginning_End_Inv';

$route['get_pos_type_id'] = "Items/get_pos_type_id";
$route['save_item_pic'] = "Items/crop_item_pic";
$route['save_category_pic'] = "Categories/crop_category_pic";
$route['category_delete'] = "Categories/delete";
$route['shift_delete'] = "Shifts/delete";

$route['addItem'] = "Sales/additem";
$route['categoryClicked'] = "Sales/categoryClicked";
$route['edit_item'] = "Sales/edit_item";
$route['edit_item/(:num)'] = "Sales/edit_item/$1";
$route['categories'] ="Categories/index";
$route['subcategories'] ="Sub_Categories/index";
$route['passcodes'] = "Passcodes";
$route['shifts'] = "Shifts";
$route['customer_types'] = "Customer_types";
$route['expenses'] = "Expenses";

$route['returnItems'] = "Sales/item_returns";

$route['sales_per_item_report'] = "Sales_per_Details";
$route['total_sold_per_item_report'] = "Total_Sold_Item_Report";
$route['dailySales'] = "Sales/manage";
$route['total_sales_report'] = "Total_Sales";
$route['top_item_report'] = "Top_Item";
$route['inventory'] = "Inventory_Summary";
$route['materials_inventory'] = "Inventory_Materials_Summary";
$route['critical_items'] = "Critical_Items";
$route['inventory_tracking'] = "Inventory_Tracking";
$route['material_inventory_tracking'] = "Inventory_Tracking/material_inventory_tracking";
$route['Rest'] = "Rest";
$route['Running_order_api'] = "Running_order_api";
$route['App_api'] = "App_api";
$route['Pdfs'] = "Rest";
$route['inventoryValidation'] = "Sales/inventory_validation";

$route['Top_Item/manage_multi'] = "Top_Item/manage_multi";

$route['MobileSettings'] = "Mobile_Settings";
$route['WebBranding'] = "Web_Branding";
$route['POS_Machines'] = "POS_Machine";
$route['bir_reports'] = "Sales/bir_reports";
$route['e_journal'] = "Sales/e_journal";
$route['eod_reports'] = "Sales/eod_reports";

$route['rewards'] = "Item_Rewards";
$route['promos'] = "Promos";

$route['audio'] = "audio";
$route['audio/overlay'] = "audio/overlay";

/* CLIENTS */
$route['teafarms'] = "teafarms";

/* CHANNELS */
$route['channels'] = "channels";

/* MEDIA */
$route['media'] = "media";
$route['media/addChannel'] = "media/addChannel";
$route['media/addChannelClient'] = "media/addChannelClient";
$route['media/editChannel'] = "media/editChannel";
$route['media/manage_files'] = "media/manage_files";
$route['media/process_files'] = "media/process_files";
$route['media/generate_media_xml'] = 'media/generate_media_xml';

// /* TIME KEEPER */
// $route['timekeeper'] = "timekeeper";
// $route['timekeeper/addEmployee'] = "timekeeper/addEmployee";
// $route['timekeeper/prcocessEmployee'] = "timekeeper/processEmployee";
// // $route['timekeeper/employee_logs'] = "timekeeper/employee_logs";

// // $route['timekeeper/employee_login'] = "timekeeper/employee_login";
// $route['employee_login/employee'] = "employee_login/employee";
// $route['employee_login/admin_index'] = "employee_login/admin_index";

/* TIME KEEPER */
$route['timekeeper'] = "timekeeper";
$route['timekeeper/addEmployee'] = "timekeeper/addEmployee";
$route['timekeeper/prcocessEmployee'] = "timekeeper/processEmployee";
// $route['timekeeper/employee_logs'] = "timekeeper/employee_logs";

// $route['timekeeper/employee_login'] = "timekeeper/employee_login";
$route['employee_login/employee'] = "employee_login/employee";



/* NOTIFICATIONS */
$route['notifications/add'] = 'notifications/add';
$route['notifications/edit/(:any)'] = 'notifications/edit/$1';
$route['notifications/views/(:any)'] = 'notifications/views/$1';
$route['notifications'] = 'notifications/index';
//$route['notifications/(:any)'] = 'notifications/archives';
$route['notifications/archives'] = 'notifications/archives';

$route['sales/manage_multi'] = 'sales/manage_multi';

/* REGISTER */
$route['add_register'] = 'register/add_register';
$route['pay_method'] = 'register/pay_method';
$route['success'] = 'register/success';

/* RUNNING ORDERS */
$route['running_orders'] = 'running_orders';
$route['running_orders/items'] = 'running_orders/items';

/* VERSIONING */
$route['versioning'] = 'versioning/index';

/* PER STORE */
$route['store'] = 'store/dashboard';
$route['points'] = 'points';
$route['set_email_points'] = 'sales/set_email_points';


/* PLAYLIST */
$route['playlist'] = 'playlist';
$route['playlist/add'] = 'playlist/add';
$route['playlist/edit_playlist'] = 'playlist/edit_playlist';
$route['playlist/manage_playlist'] = 'playlist/manage_playlist';
$route['playlist/add_to_playlist'] = 'playlist/add_to_playlist';

/* OVERLAY */

$route['overlay'] = 'overlay';
$route['overlay/create'] = 'overlay/create';

/* LOGS */
$route['activity_logs/logs_summary'] = 'activity_logs';
$route['activity_logs/bir_ativity_logs'] = 'activity_logs/bir_ativity_logs';

$route['waiting_order_count'] = 'sales/waiting_order_count';
$route['mobile_order_count'] = 'sales/mobile_order_count';
$route['item_ready'] = 'sales/update_claim_status';

/* SIGNUP AUTOMATION */

$route['paypal'] = 'paypal';
//$route['paypal/success'] = 'paypal/success';
$route['paypal/ipn'] = 'paypal/ipn';
$route['products/buy/(:any)'] = 'products/buy/$1';

$route['reservation'] = 'reservation';

/* Backend Printing */
$route['back_order'] = 'sales/orders';

/* Offline Installations */
$route['offline'] = 'offline';
?>