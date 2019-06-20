<?php

require(APPPATH.'OAuth2/Server.php');
require(APPPATH.'OAuth2/Autoloader.php');



class OAuth2 extends SPACULLUS_Controller{
   
    function OAuth2(){
        parent :: __construct ();
        OAuth2\Autoloader::register();
        
    }
    
    function server_init(){
        $db_host = $this->db->hostname;
        $db_name = $this->db->database;
        $dsn = 'mysql:dbname='.$db_name.';host='.$db_host;
        $username = $this->db->username;
        $password = $this->db->password;
        
        $storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
        $server = new OAuth2\Server($storage);
        return $server;
    }
    
    function check_user_authorization(){
        $server=   $this->server_init();
        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();
        
        // validate the authorize request
            if (!$server->validateAuthorizeRequest($request, $response)) {
                $response->send();
                die;
            }
            //check user login info
//            if(!check_user_authentication()){
//                redirect('home/third_party_login');
//            }
            // display an authorization form
            if (empty($_POST)) {
              exit('
            <form method="post">
              <label>Do You Authorize TestClient?</label><br />
              <input type="submit" name="authorized" value="yes">
              <input type="submit" name="authorized" value="no">
            </form>');
            }
            //$user_id = $this->session->userdata('user_id');
            // print the authorization code if the user has authorized your client
            $is_authorized = ($_POST['authorized'] === 'yes');
            $server->handleAuthorizeRequest($request, $response, $is_authorized,$user_id);
            if ($is_authorized) {
              // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
              $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
              
            }
            $response->send();
    }
    
    function get_access_token(){
        $db_host = $this->db->hostname;
        $db_name = $this->db->database;
        $dsn = 'mysql:dbname='.$db_name.';host='.$db_host;
        $username = $this->db->username;
        $password = $this->db->password;
        
        $storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
        $server = $this->server_init();
        $grant_type = $this->input->post('grant_type');
        //echo $grant_type; die();
        switch ($grant_type){
            case "authorization_code":
                 $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
                 break;
            case "refresh_token":
                $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage));
                break;
            case "client_credentials":
                $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
                break;
            case "password":
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $users = array($username =>array('password' => $password,'username'=> $username));
                $storage = new OAuth2\Storage\Memory(array('user_credentials' => $users));
                $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
                break;
        }
        $server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }
    
    function check_token_validation(){
            $server = $this->server_init();
            // Handle a request to a resource and authenticate the access token
            if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
                $server->getResponse()->send();
                die;
            }

            $token = $server->getAccessTokenData(OAuth2\Request::createFromGlobals());
            //echo json_encode(array("token data"=>$token)); die();
            echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!','token_info'=>$token));
    }
}

