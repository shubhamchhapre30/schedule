<?php

include (APPPATH.'XeroOAuth/lib/XeroOAuth.php');
include (APPPATH.'XeroOAuth/lib/OAuthSimple.php');

class Xero extends SPACULLUS_Controller{
    
        function __construct(){
            parent::__construct();
            $this->load->model('timesheet_model');
            define ( "OAUTH_CALLBACK", base_url().'xero' );
        }
        
        function index(){
            
            $signatures = array (
		'consumer_key' => CONSUMER_KEY,
		'shared_secret' => CONSUMER_SECRET,
		'core_version' => '2.0',
		'payroll_version' => '1.0',
		'file_version' => '1.0' 
            );



            $XeroOAuth = new XeroOAuth ( array_merge ( array (
                            'application_type' => XRO_APP_TYPE,
                            'oauth_callback' => OAUTH_CALLBACK,
                            'user_agent' => XERO_USERAGENT 
            ), $signatures ) );
            $initialCheck = $XeroOAuth->diagnostics ();
            $checkErrors = count ( $initialCheck );
            if ($checkErrors > 0) {
                    // you could handle any config errors here, or keep on truckin if you like to live dangerously
                    foreach ( $initialCheck as $check ) {
                            echo 'Error: ' . $check . PHP_EOL;
                    }
            } else {
                    $oauthSession = $this->retrieveSession ();
                    
                    if (isset ( $_REQUEST ['oauth_verifier'] )) {
                      
                            $XeroOAuth->config ['access_token'] = $_SESSION ['oauth'] ['oauth_token'];
                            $XeroOAuth->config ['access_token_secret'] = $_SESSION ['oauth'] ['oauth_token_secret'];

                            $code = $XeroOAuth->request ( 'GET', $XeroOAuth->url ( 'AccessToken', '' ), array (
                                            'oauth_verifier' => $_REQUEST ['oauth_verifier'],
                                            'oauth_token' => $_REQUEST ['oauth_token'] 
                            ) );

                            if ($XeroOAuth->response ['code'] == 200) {

                                    $response = $XeroOAuth->extract_params ( $XeroOAuth->response ['response'] );
                                    $session = $this->persistSession ( $response );
                                    $here = base_url().'settings/index?active=1';
                                    unset ( $_SESSION ['oauth'] );
                                    header ( "Location: {$here}" );
                                    
                            } else {
                                return $this->outputError ( $XeroOAuth );
                            }
                            // start the OAuth dance
                    } elseif (isset ( $_REQUEST ['authenticate'] ) || isset ( $_REQUEST ['authorize'] )) {
                            $params = array (
                                            'oauth_callback' => OAUTH_CALLBACK 
                            );

                            $response = $XeroOAuth->request ( 'GET', $XeroOAuth->url ( 'RequestToken', '' ), $params );

                            if ($XeroOAuth->response ['code'] == 200) {

                                    $scope = "";
                                     //$scope = 'payroll.payrollcalendars,payroll.superfunds,payroll.payruns,payroll.payslip,payroll.employees,payroll.TaxDeclaration';
                                    if ($_REQUEST ['authenticate'] > 1){
                                        $scope = 'payroll.employees,payroll.payruns,payroll.timesheets';
                                    }
                                   
                                    $_SESSION ['oauth'] = $XeroOAuth->extract_params ( $XeroOAuth->response ['response'] );

                                    $authurl = $XeroOAuth->url ( "Authorize", '' ) . "?oauth_token={$_SESSION['oauth']['oauth_token']}&scope=" . $scope;
                                    echo $authurl;
                            } else {
                                return $this->outputError ( $XeroOAuth );
                            }
                    }
            }
        }
    
        function testLinks(){
             $default_format = $this->config->item('company_default_format');
            $signatures = array (
                   'consumer_key' => CONSUMER_KEY,
                   'shared_secret' => CONSUMER_SECRET,
                   'core_version' => '2.0',
                   'payroll_version' => '1.0',
                   'file_version' => '1.0' 
            );
            date_default_timezone_set($this->session->userdata("User_timezone"));
            $XeroOAuth = new XeroOAuth ( array_merge ( array (
                            'application_type' => XRO_APP_TYPE,
                            'oauth_callback' => OAUTH_CALLBACK,
                            'user_agent' => XERO_USERAGENT 
            ), $signatures ) );
            if (isset($_SESSION['access_token']) || XRO_APP_TYPE == 'Private'){
                $oauthSession = $this->retrieveSession ();
                if ( isset($oauthSession['oauth_token']) && isset($_REQUEST) ) {

                    $XeroOAuth->config['access_token']  = $oauthSession['oauth_token'];
                    $XeroOAuth->config['access_token_secret'] = $oauthSession['oauth_token_secret'];

                    if(isset($_REQUEST['contacts'])) {
                       if (!isset($_REQUEST['method'])){
                           $response = $XeroOAuth->request('GET', $XeroOAuth->url('Contacts', 'core'), array());

                           if ($XeroOAuth->response['code'] == 200) { 
                               $contacts = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                               echo $XeroOAuth->response['response'];
                           } else {
                               return $this->outputError($XeroOAuth);
                           }
                        }elseif(isset($_REQUEST['method']) && $_REQUEST['method'] == "post" ){
                             if(isset($_GET['customername'])){ $customer_name = $_GET['customername'];}else{ $customer_name ='';}
                             $xeroinfo = get_xero_account_info();
                             $xml = "<Contacts>
                                    <Contact>
                                      <Name>".$customer_name."</Name>
                                      <AccountsReceivableTaxType>".$xeroinfo->xero_tax_type."</AccountsReceivableTaxType>
                                    </Contact>
                                 </Contacts>";
                            $response = $XeroOAuth->request('POST', $XeroOAuth->url('Contacts', 'core'), array(), $xml);
                            if ($XeroOAuth->response['code'] == 200) {
                                $contact = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                                echo $XeroOAuth->response['response'];
                            }else {
                               return $this->outputError($XeroOAuth);
                            }
                        } 
                    }
                    if(isset($_REQUEST['invoice'])) {
                        if (isset($_REQUEST['method']) && $_REQUEST['method'] == "post" ) {
                            $timesheet_id = $_GET['timesheets_id'];
                            $timesheet_id = explode(',', $timesheet_id);
                            $xero_info = get_xero_account_info();
                            $customerids = $_GET['customers'];
                            $is_project_separte = $_GET['separte_project'];
                            if($timesheet_id){
                                foreach($timesheet_id as $id){
                                    $timesheet_data = $this->timesheet_model->get_one_timesheet_data($id);
                                    if($timesheet_data->export_to_xero == '0'){
                                        $form_date[$timesheet_data->from_date] = $timesheet_data->from_date;
                                        $to_date[$timesheet_data->to_date] = $timesheet_data->to_date;
                                        $users[] = $timesheet_data->timesheet_user_id;
                                    }
                                }
                            }
                            if(!empty($form_date) && !empty($to_date) && !empty($users)){
                                $new_from_date = array_search(min($form_date), $form_date);
                                $new_to_date = array_search(max($to_date), $to_date);
                                $xero_result =  $this->timesheet_model->xero_export($new_from_date,$new_to_date,$users);
                                if($xero_result['customers']){
                                    if($customerids == 'all' && $is_project_separte == '0'){
                                            foreach($xero_result['customers'] as $customer){
                                                 $xml = "<Invoices>
                                                            <Invoice>
                                                               <Type>ACCREC</Type>
                                                               <Contact>
                                                                 <Name>".htmlentities($customer->customer_name)."</Name>
                                                               </Contact>
                                                               <Date>".date("Y-m-d H:i:s")."</Date>
                                                               <DueDate>".date("Y-m-d H:i:s")."</DueDate>
                                                               <LineItems>";
                                                foreach($xero_result['result'] as $res){
                                                    if($res['customer_id'] == $customer->customer_id){
                                                        $task_array[] = $res['task_id'];
                                                        $quantity = round($res['billed_time']/60,2);
                                                        if (strpos($quantity, '.') == false) { $quantity = $quantity.'.00';}else {$quantity = $quantity;} 
                                                        if($res['project_title'] !=''){
                                                            $pro = ' - '.htmlentities($res['project_title']);
                                                        }else{
                                                            $pro = '';
                                                        }
                                                        $xml .= "<LineItem>
                                                                          <Description>
                                                                                ".date($default_format, strtotime($res['task_scheduled_date'])).$pro." - ".$res['allocated_user_name']." - ".htmlentities($res['task_title'])."
                                                                           </Description>
                                                                          <Quantity>".$quantity."</Quantity>
                                                                          <UnitAmount>".$res['charge_out_rate']."</UnitAmount>
                                                                          <AccountCode>".$xero_info->xero_account_code."</AccountCode>
                                                                          <TaxType>".$xero_info->xero_tax_type."</TaxType>
                                                                </LineItem>
                                                                ";
                                                    }
                                                }
                                                $xml .= '</LineItems></Invoice></Invoices>';
                                                
                                                $response = $XeroOAuth->request('POST', $XeroOAuth->url('Invoices', 'core'), array(), $xml);
                                                if ($response['code'] == 200) {
                                                    foreach($task_array as $array){
                                                        $this->db->set('exported','1');
                                                        $this->db->where('task_id',$array);
                                                        $this->db->update('tasks');
                                                    }
                                                } else {
                                                   return $this->outputError($XeroOAuth); 
                                                }
                                            }
                                    }
                                    else if($customerids != 'all' && $is_project_separte == '0'){
                                                $customer = get_customer_detail($customerids,  $this->session->userdata('company_id'));
                                                $xml = "<Invoices>
                                                            <Invoice>
                                                               <Type>ACCREC</Type>
                                                               <Contact>
                                                                 <Name>".htmlentities($customer->customer_name)."</Name>
                                                               </Contact>
                                                               <Date>".date("Y-m-d H:i:s")."</Date>
                                                               <DueDate>".date("Y-m-d H:i:s")."</DueDate>
                                                               <LineItems>";
                                                foreach($xero_result['result'] as $res){
                                                    if($res['customer_id'] == $customer->customer_id){
                                                        $task_array[] = $res['task_id'];
                                                        $quantity = round($res['billed_time']/60,2);
                                                        if (strpos($quantity, '.') == false) { $quantity = $quantity.'.00';}else {$quantity = $quantity;} 
                                                        if($res['project_title'] !=''){
                                                            $pro = ' - '.htmlentities($res['project_title']);
                                                        }else{
                                                            $pro = '';
                                                        }
                                                        $xml .= "<LineItem>
                                                                          <Description>
                                                                                ".date($default_format, strtotime($res['task_scheduled_date'])).$pro." - ".$res['allocated_user_name']." - ".htmlentities($res['task_title'])."
                                                                          </Description>
                                                                          <Quantity>".$quantity."</Quantity>
                                                                          <UnitAmount>".$res['charge_out_rate']."</UnitAmount>
                                                                          <AccountCode>".$xero_info->xero_account_code."</AccountCode>
                                                                          <TaxType>".$xero_info->xero_tax_type."</TaxType>
                                                                </LineItem>
                                                                ";
                                                    }
                                                }
                                                $xml .= '</LineItems></Invoice></Invoices>';
                                                
                                                $response = $XeroOAuth->request('POST', $XeroOAuth->url('Invoices', 'core'), array(), $xml);
                                                if ($response['code'] == 200) {
                                                    foreach($task_array as $array){
                                                        $this->db->set('exported','1');
                                                        $this->db->where('task_id',$array);
                                                        $this->db->update('tasks');
                                                    }
                                                } else {
                                                   return $this->outputError($XeroOAuth); 
                                                }
                                    }
                                    else if($customerids == "all" && $is_project_separte == '1'){
                                        foreach($xero_result['customers'] as $customer){
                                                $project_list = get_projectlist_of_customer($customer->customer_id);
                                                if($project_list){
                                                    foreach($project_list as $list){
                                                        $xml = "<Invoices>
                                                                            <Invoice>
                                                                               <Type>ACCREC</Type>
                                                                               <Contact>
                                                                                 <Name>".htmlentities($customer->customer_name)."</Name>
                                                                               </Contact>
                                                                               <Date>".date("Y-m-d H:i:s")."</Date>
                                                                               <DueDate>".date("Y-m-d H:i:s")."</DueDate>
                                                                               <LineItems>";
                                                        foreach($xero_result['result'] as $res){
                                                            if($res['customer_id'] == $customer->customer_id && $list->project_id == $res['task_project_id']){
                                                                $task_array[] = $res['task_id'];
                                                                $quantity = round($res['billed_time']/60,2);
                                                                if (strpos($quantity, '.') == false) { $quantity = $quantity.'.00';}else {$quantity = $quantity;} 
                                                                if($res['project_title'] !=''){
                                                                    $pro = ' - '.htmlentities($res['project_title']);
                                                                }else{
                                                                    $pro = '';
                                                                }
                                                                
                                                                $xml .= "<LineItem>
                                                                                  <Description>
                                                                                        ".date($default_format, strtotime($res['task_scheduled_date'])).$pro." - ".$res['allocated_user_name']." - ".htmlentities($res['task_title'])."
                                                                                   </Description>
                                                                                  <Quantity>".$quantity."</Quantity>
                                                                                  <UnitAmount>".$res['charge_out_rate']."</UnitAmount>
                                                                                  <AccountCode>".$xero_info->xero_account_code."</AccountCode>
                                                                                  <TaxType>".$xero_info->xero_tax_type."</TaxType>
                                                                        </LineItem>
                                                                        ";
                                                            }
                                                        }
                                                        $xml .= '</LineItems></Invoice></Invoices>';
                                                
                                                        $response = $XeroOAuth->request('POST', $XeroOAuth->url('Invoices', 'core'), array(), $xml);
                                                        if ($response['code'] == 200) {
                                                            foreach($task_array as $array){
                                                                $this->db->set('exported','1');
                                                                $this->db->where('task_id',$array);
                                                                $this->db->update('tasks');
                                                            }
                                                        } else {
                                                           return $this->outputError($XeroOAuth); 
                                                        }
                                                    }
                                                }else{
                                                        $xml = "<Invoices>
                                                            <Invoice>
                                                               <Type>ACCREC</Type>
                                                               <Contact>
                                                                 <Name>".htmlentities($customer->customer_name)."</Name>
                                                               </Contact>
                                                               <Date>".date("Y-m-d H:i:s")."</Date>
                                                               <DueDate>".date("Y-m-d H:i:s")."</DueDate>
                                                               <LineItems>";
                                                        foreach($xero_result['result'] as $res){
                                                            if($res['customer_id'] == $customer->customer_id){
                                                                $task_array[] = $res['task_id'];
                                                                $quantity = round($res['billed_time']/60,2);
                                                                if (strpos($quantity, '.') == false) { $quantity = $quantity.'.00';}else {$quantity = $quantity;} 
                                                                if($res['project_title'] !=''){
                                                                    $pro = ' - '.htmlentities($res['project_title']);
                                                                }else{
                                                                    $pro = '';
                                                                }
                                                                $xml .= "<LineItem>
                                                                                  <Description>
                                                                                        ".date($default_format, strtotime($res['task_scheduled_date'])).$pro." - ".$res['allocated_user_name']." - ".htmlentities($res['task_title'])."
                                                                                   </Description>
                                                                                  <Quantity>".$quantity."</Quantity>
                                                                                  <UnitAmount>".$res['charge_out_rate']."</UnitAmount>
                                                                                  <AccountCode>".$xero_info->xero_account_code."</AccountCode>
                                                                                  <TaxType>".$xero_info->xero_tax_type."</TaxType>
                                                                        </LineItem>
                                                                        ";
                                                            }
                                                        }
                                                        $xml .= '</LineItems></Invoice></Invoices>';

                                                        $response = $XeroOAuth->request('POST', $XeroOAuth->url('Invoices', 'core'), array(), $xml);
                                                        if ($response['code'] == 200) {
                                                                foreach($task_array as $array){
                                                                    $this->db->set('exported','1');
                                                                    $this->db->where('task_id',$array);
                                                                    $this->db->update('tasks');
                                                                }
                                                        } else {
                                                           return $this->outputError($XeroOAuth); 
                                                        }
                                                }
                                            }
                                    }
                                    else{
                                        $customer = get_customer_detail($customerids,  $this->session->userdata('company_id'));
                                        $project_list = get_projectlist_of_customer($customerids);
                                            if($project_list){
                                                foreach($project_list as $list){
                                                    $xml = "<Invoices>
                                                                <Invoice>
                                                                   <Type>ACCREC</Type>
                                                                   <Contact>
                                                                     <Name>".htmlentities($customer->customer_name)."</Name>
                                                                   </Contact>
                                                                   <Date>".date("Y-m-d H:i:s")."</Date>
                                                                   <DueDate>".date("Y-m-d H:i:s")."</DueDate>
                                                                   <LineItems>";
                                                    foreach($xero_result['result'] as $res){
                                                        if($res['customer_id'] == $customerids && $res['task_project_id'] == $list->project_id ){
                                                            $task_array[] = $res['task_id'];
                                                            $quantity = round($res['billed_time']/60,2);
                                                            if (strpos($quantity, '.') == false) { $quantity = $quantity.'.00';}else {$quantity = $quantity;} 
                                                            if($res['project_title'] !=''){
                                                                $pro = ' - '.htmlentities($res['project_title']);
                                                            }else{
                                                                $pro = '';
                                                            }
                                                            $xml .= "<LineItem>
                                                                              <Description>
                                                                                    ".date($default_format, strtotime($res['task_scheduled_date'])).$pro." - ".$res['allocated_user_name']." - ".htmlentities($res['task_title'])."
                                                                              </Description>
                                                                              <Quantity>".$quantity."</Quantity>
                                                                              <UnitAmount>".$res['charge_out_rate']."</UnitAmount>
                                                                              <AccountCode>".$xero_info->xero_account_code."</AccountCode>
                                                                              <TaxType>".$xero_info->xero_tax_type."</TaxType>
                                                                    </LineItem>
                                                                    ";
                                                        }
                                                    }
                                                    $xml .= '</LineItems></Invoice></Invoices>';

                                                    $response = $XeroOAuth->request('POST', $XeroOAuth->url('Invoices', 'core'), array(), $xml);
                                                    if ($response['code'] == 200) {
                                                        foreach($task_array as $array){
                                                            $this->db->set('exported','1');
                                                            $this->db->where('task_id',$array);
                                                            $this->db->update('tasks');
                                                        }
                                                    } else {
                                                       return $this->outputError($XeroOAuth); 
                                                    }
                                                }
                                            }else{
                                                $customer = get_customer_detail($customerids,  $this->session->userdata('company_id'));
                                                $xml = "<Invoices>
                                                            <Invoice>
                                                               <Type>ACCREC</Type>
                                                               <Contact>
                                                                 <Name>".htmlentities($customer->customer_name)."</Name>
                                                               </Contact>
                                                               <Date>".date("Y-m-d H:i:s")."</Date>
                                                               <DueDate>".date("Y-m-d H:i:s")."</DueDate>
                                                               <LineItems>";
                                                foreach($xero_result['result'] as $res){
                                                    if($res['customer_id'] == $customerids){
                                                        $task_array[] = $res['task_id'];
                                                        $quantity = round($res['billed_time']/60,2);
                                                        if (strpos($quantity, '.') == false) { $quantity = $quantity.'.00';}else {$quantity = $quantity;} 
                                                        if($res['project_title'] !=''){
                                                            $pro = ' - '.htmlentities($res['project_title']);
                                                        }else{
                                                            $pro = '';
                                                        }
                                                        $xml .= "<LineItem>
                                                                          <Description>
                                                                                ".date($default_format, strtotime($res['task_scheduled_date'])).$pro." - ".$res['allocated_user_name']." - ".htmlentities($res['task_title'])."
                                                                          </Description>
                                                                          <Quantity>".$quantity."</Quantity>
                                                                          <UnitAmount>".$res['charge_out_rate']."</UnitAmount>
                                                                          <AccountCode>".$xero_info->xero_account_code."</AccountCode>
                                                                          <TaxType>".$xero_info->xero_tax_type."</TaxType>
                                                                </LineItem>
                                                                ";
                                                    }
                                                }
                                                $xml .= '</LineItems></Invoice></Invoices>';
                                                
                                                $response = $XeroOAuth->request('POST', $XeroOAuth->url('Invoices', 'core'), array(), $xml);
                                                if ($response['code'] == 200) {
                                                    foreach($task_array as $array){
                                                        $this->db->set('exported','1');
                                                        $this->db->where('task_id',$array);
                                                        $this->db->update('tasks');
                                                    }
                                                } else {
                                                   return $this->outputError($XeroOAuth); 
                                                }
                                            }
                                    }
                                    $data['success'] = 'success';
                                    echo json_encode($data); die();
                                }
                            }
                        }
                    }
                    if(isset($_REQUEST['wipe'])){
                        unset($_SESSION['access_token']);
                        unset($_SESSION['oauth_token_secret']);
                    } 
                    if(isset($_REQUEST['organisation'])) {
                        $response = $XeroOAuth->request('GET', $XeroOAuth->url('Organisation', 'core'), array(), 'json');
                        if ($XeroOAuth->response['code'] == 200) {
                            $organisation = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                            $json = json_decode(json_encode($organisation),true);
                            $data['organisations'] = $json['Organisations'][0]['Name'];
                            $data['organisationID'] = $json['Organisations'][0]['OrganisationID'];
                            echo json_encode($data);
                        } else {
                            return $this->outputError($XeroOAuth);
                        }
                    }
                    if(isset($_REQUEST['accounts'])){
                        $type = $_REQUEST['type'];
                        if($type == '1'){
                            $ac_type = 'Type=="REVENUE"';
                        }else{
                            $ac_type = 'Type=="SALE"';
                        }
                        $response = $XeroOAuth->request('GET', $XeroOAuth->url('Accounts', 'core'), array('Where' => $ac_type));
                        if($XeroOAuth->response['code'] == 200) {
                            $accounts = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                            $data['accounts'] =$accounts->Accounts;
                            echo json_encode($data);
                        } else {
                            return $this->outputError($XeroOAuth);
                        }
                    }
                    if(isset($_REQUEST['TaxRates'])) { 
                        $response = $XeroOAuth->request('GET', $XeroOAuth->url('TaxRates', 'core'), array());

                        if ($XeroOAuth->response['code'] == 200 ) {
                            $accounts = $XeroOAuth->parseResponse($XeroOAuth->response['response'], $XeroOAuth->response['format']);
                            $data['tax'] =$accounts->TaxRates;
                            echo json_encode($data);
                        } else {
                            outputError($XeroOAuth);
                        }
                    }
                }
            }else{
                $data['error_code'] = 'error';
                echo json_encode($data); die();
            }
        }
 
        function persistSession($response){
            if (isset($response)) {
                $_SESSION['access_token']       = $response['oauth_token'];
                $_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];
                if(isset($response['oauth_session_handle'])){
                    $_SESSION['session_handle'] = $response['oauth_session_handle'];
                }
            } else {
                return false;
            }
        }

        function retrieveSession(){
            if (isset($_SESSION['access_token'])) {
                $response['oauth_token'] = $_SESSION['access_token'];
                $response['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
                return $response;
            } else {
                return false;
            }
        }

        function outputError($XeroOAuth){
            $data['error_code'] = 'error';
            $error =  $XeroOAuth->response['response'];
            $array = explode('=', $error); 
            //pr($error);
            if(strpos($error,"&oauth_problem_advice")){
                $new =  str_replace('&oauth_problem_advice', '', $array[1]);
            }else{
                $new = '';
            }
            if($new == ''){
               $array = json_decode($error);
               $data['other_msg'] = $array->Elements[0]->ValidationErrors[0]->Message;
            }
            $data['error_message'] = $new;
            $data['other_info'] = $error;
            echo json_encode($data);
        }
}

