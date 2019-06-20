<?php

class Customer_model extends CI_Model 
{
      /**
        * It default constuctor which is called when home_model object is initialzied.It load base class methods & variables.
        * @returns void
        */
	
	function Customer_model()
            {
                parent::__construct();	
            }
            
            
        function insertCustomer(){
            $total_customer= getCustomerCount();
            $customer_id="CU-".($total_customer+1);
            $unserializedData = array();
            parse_str($_POST['customer_data'],$unserializedData);
            //print_r($unserializedData); die();  
            $data=array(
                        "customer_id"=>$customer_id,
                        "first_name" => $unserializedData["first_name"],
                        "last_name"=>$unserializedData["last_name"],
                        "email"=>$unserializedData["email"],
                        "phone"=>$unserializedData["phone"],
                        "external_id"=>$unserializedData["customer_external_id"],
                        "owner_id"=>$unserializedData["internal_owner"],
                        "customer_name"=>$unserializedData["customer_name"],
                        "parent_customer_id"=>$unserializedData["parent_customer_id"],
                        "customer_company_id"=>$unserializedData["customer_company_id"],
                        "status"=>"active",
                        "is_deleted"=>'0',
                        "create_date"=>date('Y-m-d H:i:s'),
             );
              $this->db->insert('customers',$data);
              //$id = $this->db->insert_id();
             return $customer_id;
        }
        
        function updateCustomer(){
             $unserializedData = array();
             parse_str($_POST['customer_data'],$unserializedData);
            //print_r($unserializedData); die();  
             $data=array(
                        "first_name" => $unserializedData["first_name"],
                        "last_name"=>$unserializedData["last_name"],
                        "email"=>$unserializedData["email"],
                        "phone"=>$unserializedData["phone"],
                        "external_id"=>$unserializedData["customer_external_id"],
                        "owner_id"=>$unserializedData["internal_owner"],
                        "customer_name"=>$unserializedData["customer_name"],
                        "parent_customer_id"=>$unserializedData["parent_customer_id"],
                        
             );
             $this->db->where('customer_company_id',$this->session->userdata('company_id'));
             $this->db->where('customer_id',$unserializedData["customerid"]);
             $this->db->update('customers',$data);
             return $unserializedData["customerid"];
            
        }
        
        function get_projects($customer_id,$limit,$offset){
            $this->db->select('*');
            $this->db->from('project');
            $this->db->where('project_customer_id',$customer_id);
            $this->db->where('company_id',  $this->session->userdata('company_id'));
            $this->db->where('is_deleted','0');
            $this->db->limit($limit,$offset);
            $query=  $this->db->get();
                   
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
	}
        
        function get_tasks($customer_id,$limit,$offset){
                $completed = $this->config->item('completed_id');
                $this->db->select('t.*,ts.task_status_name,CONCAT(u.first_name," ",u.last_name) as allocated_user_name,(SELECT COUNT(1) FROM tasks tm  WHERE tm.task_id = t.master_task_id and tm.is_deleted = 0) AS tm');
                $this->db->from('tasks t');
                $this->db->join("task_status ts","ts.task_status_id = t.task_status_id",'left');
                $this->db->join('users u','u.user_id = t.task_allocated_user_id','left');
                $this->db->where('t.task_status_id!=',$completed);
                $this->db->where('t.customer_id',$customer_id);
                $this->db->where('t.is_deleted','0');
                $this->db->where('t.task_company_id',$this->session->userdata('company_id'));
                $this->db->order_by('t.task_scheduled_date','desc');
                $this->db->limit($limit,$offset);
                $query= $this->db->get();
                
            //echo $this->db->last_query(); die();
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
        }
        
        function gettaskListByFilter($status_id,$owner_id,$allocated_id,$customer_id,$limit,$offset){
                $completed = $this->config->item('completed_id');
                $this->db->select('t.*,ts.task_status_name,CONCAT(u.first_name," ",u.last_name) as allocated_user_name');
                $this->db->from('tasks t');
                $this->db->join("task_status ts","ts.task_status_id = t.task_status_id",'left');
                $this->db->join('users u','u.user_id = t.task_allocated_user_id','left');
                if($status_id=='0'){
                    $this->db->where('t.task_status_id!=',$completed);
                }else{
                    $this->db->where('t.task_status_id',$status_id);
                }
                if($owner_id!='0'){
                    $this->db->where('t.task_owner_id',$owner_id);
                }
                if($allocated_id!='0'){
                    $this->db->where('t.task_allocated_user_id',$allocated_id);
                }
                $this->db->where('t.is_deleted','0');
                $this->db->where('t.customer_id',$customer_id);
                $this->db->where('t.task_company_id',$this->session->userdata('company_id'));
                $this->db->order_by('t.task_scheduled_date','desc');
                $this->db->limit($limit,$offset);
                $query= $this->db->get();
               // echo $this->db->last_query(); die();
                if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return 0;
		}
        }
        
        
        function counttaskListByFilter($status_id,$owner_id,$allocated_id,$customer_id){
                $completed = $this->config->item('completed_id');
                $this->db->select('t.*,ts.task_status_name,CONCAT(u.first_name," ",u.last_name) as allocated_user_name');
                $this->db->from('tasks t');
                $this->db->join("task_status ts","ts.task_status_id = t.task_status_id",'left');
                $this->db->join('users u','u.user_id = t.task_allocated_user_id','left');
                if($status_id=='0'){
                    $this->db->where('t.task_status_id!=',$completed);
                }else{
                    $this->db->where('t.task_status_id',$status_id);
                }
                if($owner_id!='0'){
                    $this->db->where('t.task_owner_id',$owner_id);
                }
                if($allocated_id!='0'){
                    $this->db->where('t.task_allocated_user_id',$allocated_id);
                }
                $this->db->where('t.is_deleted','0');
                $this->db->where('t.customer_id',$customer_id);
                $this->db->where('t.task_company_id',$this->session->userdata('company_id'));
                //$this->db->limit($limit,$offset);
                $query= $this->db->get();
               // echo $this->db->last_query(); die();
                
			return $query->num_rows();
		
        }

        function countTotalCustomerProject($customer_id){
            
            $this->db->where('company_id',$this->session->userdata('company_id'));
            $this->db->where('project_customer_id',$customer_id);
            $this->db->where('is_deleted','0');
            $query= $this->db->get('project');
            return $query->num_rows();
        }
        
        function countTotalCustomerTask($customer_id){
            $completed = $this->config->item('completed_id');
            $this->db->where('task_company_id',$this->session->userdata('company_id'));
            $this->db->where('customer_id',$customer_id);
            $this->db->where('task_status_id!=',$completed);
            $this->db->where('is_deleted','0');
            $query= $this->db->get('tasks');
            return $query->num_rows();
        }
        
        function get_Customer_List($limit,$offset){
            $search=isset($_POST['search'])?$_POST['search']:'';
            $this->db->select('c.*,CONCAT(u.first_name,SPACE(1),u.last_name) as ownername');
            $this->db->from('customers c');
            $this->db->join('users u','u.user_id = c.owner_id','left');
	   // $this->db->where('c.status','active');
            $this->db->where('c.is_deleted','0');
            $this->db->where('c.customer_company_id',$this->session->userdata('company_id'));
            $this->db->order_by('c.customer_name','asc');
            if($search){
                $this->db->like('c.customer_name',$search);
            }
            $this->db->limit($limit,$offset);
            $query=  $this->db->get();
            
                //echo $this->db->last_query();
                if($query->num_rows()>0){
                     return $query->result();
		}
                else
                {
                    return 0;
                }
        }
        
        function searchCustomerList($name,$limit,$offset){
            $this->db->select('c.*,CONCAT(u.first_name,SPACE(1),u.last_name) as ownername');
            $this->db->from('customers c');
            $this->db->join('users u','u.user_id = c.owner_id','left');
	    //$this->db->where('c.status','active');
            $this->db->where('c.is_deleted','0');
            $this->db->where('c.customer_company_id',$this->session->userdata('company_id'));
            $this->db->where("(c.customer_name LIKE '%$name%' OR c.external_id LIKE '%$name%' OR c.email LIKE '%$name%' OR c.phone LIKE '%$name%' OR c.first_name LIKE '%$name%' OR c.last_name LIKE '%$name%')");
            
            $this->db->order_by('c.customer_name','asc');
            $this->db->group_by('c.customer_id','asc');
            $this->db->limit($limit,$offset);
            $query=  $this->db->get();
            //echo $this->db->last_query(); die();
                //echo $CI->db->last_query(); die();
                if($query->num_rows()>0){
                     return $query->result();
		}
                else
                {
                    return 0;
                }
        }
        
         function countCustomerdata($name){
             $this->db->select('c.*,CONCAT(u.first_name,SPACE(1),u.last_name) as ownername');
            $this->db->from('customers c');
            $this->db->join('users u','u.user_id = c.owner_id','left');
	   
            $this->db->where('c.is_deleted','0');
            $this->db->where('c.customer_company_id',$this->session->userdata('company_id'));
            $this->db->where("(c.customer_name LIKE '%$name%' OR c.external_id LIKE '%$name%' OR c.email LIKE '%$name%' OR c.phone LIKE '%$name%' OR c.first_name LIKE '%$name%' OR c.last_name LIKE '%$name%')");
           
            $query=  $this->db->get();
            
               return $query->num_rows();
		
         }
       
        
}
?>

