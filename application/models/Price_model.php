<?php
class Price_model extends CI_Model 
{
      /**
        * It default constuctor which is called when home_model object is initialzied.It load base class methods & variables.
        * @returns void
        */
	
	function Price_model()
            {
                parent::__construct();	
            }
            
        function countemployeeresult($search){
                $this->db->select('*');
                $this->db->from('users u');
                $this->db->where('u.is_deleted','0');
                $this->db->where('u.company_id',$this->session->userdata('company_id'));
                $this->db->like('u.first_name',$search);
                $query=  $this->db->get();
                return $query->num_rows();
                
        }  
        
        function search_employee_list($search,$limit,$offset){
                
                $this->db->select('u.first_name, u.last_name, u.user_id,u.user_status,u.cost_per_hour,u.base_charge_rate_per_hour,u.rate_updated_date,sls.staff_level_title');
		$this->db->from('users u');
                $this->db->join('staff_levels sls','sls.staff_level_id = u.staff_level','left');
		$this->db->where('u.company_id',$this->session->userdata('company_id'));
		$this->db->where('u.is_deleted','0');
                $this->db->order_by('u.first_name','asc');
                $this->db->like('u.first_name',$search);
                $this->db->limit($limit,$offset);
		$query = $this->db->get();
                if($query->num_rows()>0){
                    return $query->result_array();
                }else{
                    return 0;
                }
        }
        
        function insert_subcategory($category_id,$customer_id){
                
            
                $this->db->select('*');
                $this->db->from('task_category');
                $this->db->where('company_id',$this->session->userdata('company_id'));
                $this->db->where('parent_id',$category_id);
                $this->db->where('is_deleted','0');
                $this->db->where('category_status','active');
                $query = $this->db->get();
                if($query->num_rows()>0){
			$result_data = $query->result();
		} else {
			$result_data = array();
		}
                
                foreach($result_data as $result){
                    $data=array(
                        'category_name'=>$result->category_name,
                        'customer_id'=>$customer_id,
                        'category_id'=>$result->category_id,
                        'company_id'=>  $this->session->userdata('company_id'),
                        'parent_category_id'=>$result->parent_id,
                        'rate'=>'0',
                        'is_deleted'=>'0',
                        'created_date'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('customer_category',$data);
                }
            
        }
        
        function get_user_under_customer($customer_id,$limit,$offset,$search=''){
                
                if($search!=''){
                  $query = $this->db->query("SELECT `users`.`user_id`,`first_name`,`last_name`,`user_status`,`map`.`base_rate`,`map`.`update_date`,`staff_levels`.`staff_level_title` FROM (SELECT `update_date`, `user_id`, `base_rate` FROM `users_under_customer_rate` WHERE customer_id = '".$customer_id."' ) map right join users on map.user_id = users.user_id  left join staff_levels on users.staff_level = staff_levels.staff_level_id  where users.company_id = ".$this->session->userdata('company_id')." AND users.user_status = 'active' AND users.is_deleted = '0' AND `first_name` LIKE '%".$search."%'  ORDER BY `users`.`first_name` ASC LIMIT ".$offset.",".$limit);
                }else{
                    $query = $this->db->query("SELECT `users`.`user_id`,`first_name`,`last_name`,`user_status`,`map`.`base_rate`,`map`.`update_date`,`staff_levels`.`staff_level_title` FROM (SELECT `update_date`, `user_id`, `base_rate` FROM `users_under_customer_rate` WHERE customer_id = '".$customer_id."' ) map right join users on map.user_id = users.user_id left join staff_levels on users.staff_level = staff_levels.staff_level_id where users.company_id = ".$this->session->userdata('company_id')." AND users.user_status = 'active' AND users.is_deleted = '0' ORDER BY `users`.`first_name` ASC LIMIT ".$offset.",".$limit);
                }
                
                  if($query->num_rows()>0){
                       return $query->result_array();
                   }else{
                       return 0;
                   }
        }
        
        function count_user_under_manger($customer_id,$search=''){
                if($search!=''){
                    $query = $this->db->query("SELECT `users`.`user_id`,`first_name`,`last_name`,`user_status`,`map`.`base_rate`,`map`.`update_date` FROM (SELECT `update_date`, `user_id`, `base_rate` FROM `users_under_customer_rate` WHERE customer_id = '".$customer_id."' ) map right join users on map.user_id = users.user_id where users.company_id = ".$this->session->userdata('company_id')." AND users.user_status = 'active' AND users.is_deleted = '0' AND `first_name` LIKE '%".$search."%'");
                }else{
                    $query = $this->db->query("SELECT `users`.`user_id`,`first_name`,`last_name`,`user_status`,`map`.`base_rate`,`map`.`update_date` FROM (SELECT `update_date`, `user_id`, `base_rate` FROM `users_under_customer_rate` WHERE customer_id = '".$customer_id."' ) map right join users on map.user_id = users.user_id where users.company_id = ".$this->session->userdata('company_id')." AND users.user_status = 'active' AND users.is_deleted = '0' ");
                } 
                // echo $this->db->last_query(); die();
                  
                       return $query->num_rows();
                   
        }
}
