<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = 'home';

$route['privacy-policy'] = 'home/content/privacy-policy';
$route['terms-and-condition'] = 'home/content/terms-and-condition';

$route['contactus'] = 'home/contactus';

$route['Condiciones-de-uso'] = 'home/payment_info1';
$route['Condiciones-generales-de-contratacion'] = 'home/payment_info2';

//$route['calender/myCalender'] = 'calendar/myCalendar';

//Rest Api routes

$route['api/v1/login'] = 'APIS/v1/API/login';
$route['api/v1/tasklist'] = 'APIS/v1/API/getTaskList';
$route['api/v1/kanbantask'] = 'APIS/v1/API/getKanbanTask';
$route['api/v1/opentask'] = 'APIS/v1/API/openTaskList';
$route['api/v1/overduetask'] = 'APIS/v1/API/overDueTaskList';
$route['api/v1/userstatus'] = 'APIS/v1/API/getUserStatus';
$route['api/v1/deletetask'] = 'APIS/v1/API/deleteTask';
$route['api/v1/projectteam'] = 'APIS/v1/API/getProjectTeam';
$route['api/v1/userdetail'] = 'APIS/v1/API/getUserdetail';
$route['api/v1/comment'] ='APIS/v1/API/addComment';
$route['api/v1/addTask'] = 'APIS/v1/API/addTask';
$route['api/v1/updateTask'] = 'APIS/v1/API/updateTask';
$route['api/v1/addRecurringTask'] = 'APIS/v1/API/addRecurringTask';

//User related API

$route['api/v1/users'] = 'APIS/v1/API/getUserList';
$route['api/v1/user(/:any)?'] = 'APIS/v1/API/getUserInfo/$0';
$route['api/v1/updateuser'] = 'APIS/v1/API/updateUserDetail';
$route['api/v1/deleteuser(/:any)?'] = 'APIS/v1/API/deleteUser/$0';
$route['api/v1/adduser'] = 'APIS/v1/API/addUser';

//Customer related API

$route['api/v1/customers'] = 'APIS/v1/API/getCustomerList';
$route['api/v1/customer(/:any)?'] = 'APIS/v1/API/getCustomerDetail/$0';
$route['api/v1/deletecustomer(/:any)?'] = 'APIS/v1/API/deleteCustomer/$0';
$route['api/v1/updatecustomer'] = 'APIS/v1/API/updateCustomer';
$route['api/v1/addcustomer'] = 'APIS/v1/API/addCustomer';
    

//Project related API
$route['api/v1/projects'] = 'APIS/v1/API/getProjectList';
$route['api/v1/project(/:any)?'] = 'APIS/v1/API/getprojectinfo/$0';
$route['api/v1/addproject'] = 'APIS/v1/API/addProject';
$route['api/v1/updateproject'] = 'APIS/v1/API/updateProject';
$route['api/v1/deleteproject(/:any)?'] = 'APIS/v1/API/deleteProject/$0'; 
        
        
//OAuth2 routes
$route['OAuth2/authorize'] = "OAuth2/check_user_authorization";
$route['OAuth2/token']= "OAuth2/get_access_token";
$route['OAuth2/resource'] = 'OAuth2/check_token_validation';
/* End of file routes.php */
/* Location: ./application/config/routes.php */