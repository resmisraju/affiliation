<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->load->helper(array('form', 'url'));

                $this->load->library('form_validation');
        
	}
    public function index()
    {
        $this->db->select('id,username');
		$this->db->from('user');
		$query = $this->db->get();
		$result = $query->result_array();
        $this->data['user'] = $result;
        //print_r($result);exit;
        $this->load->view('registration',$this->data);
    }
    public function save_user(){
        $this->form_validation->set_rules('name', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[15]|callback_password_check');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE)
                {
                    $error_msg =validation_errors();
                    echo json_encode(array('error_msg'=>$error_msg));
                }
                else
                {
                    //print_r($_POST);
                    $user_data  = array( 
                        'username'	=>$_POST['name'],
                        'email' => $_POST['email'],
                        'password' => $_POST['password'],
                        
                    );
                    $this->db->insert('user', $user_data);
                    $insert_id = $this->db->insert_id();
                    $level = 1;
                    if($_POST['parent']){
                        $parant_level = $this->get_parent_level($_POST['parent']);
                        $level = substr_count($parant_level, '~') + 1;
                        $identifier = $parant_level . $insert_id . '~';
                        
                    }else{
                        $level = 1;
                        $identifier = $insert_id . '~';
                    }
                    $user_level  = array( 
                        'user_id'	=>$insert_id,
                        'parent_id' => $_POST['parent'],
                        'level' => $level,
                        'identifier'  => $identifier
                    );
                    //print_r($user_level);exit;
                    $insert_id = $this->db->insert('user_hierarchy', $user_level);
                }
    }
    public function password_check($str){

        if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
            return TRUE;
          }
          $this->form_validation->set_message('password_check', 'The password field must be contains at least one letter and one digit.');
          return FALSE;
    } 
    private function get_parent_level($id) {
        $this->db->select('identifier');
		$this->db->from('user_hierarchy');
        $this->db->where('user_id',$id);
		$query = $this->db->get();
		$result = $query->row_array();
        $identifier = '';
        if($result){
            $identifier = $result['identifier'];
        }
        return $identifier;

        
    }

    public function add_sale(){
        $this->db->select('id,username');
		$this->db->from('user');
		$query = $this->db->get();
		$result = $query->result_array();
        $this->data['user'] = $result;
        //print_r($result);exit;
        $this->load->view('add_sales',$this->data);
    }

    public function save_sales(){
        $this->form_validation->set_rules('user', 'User', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required');
        if ($this->form_validation->run() == FALSE)
        {
            $error_msg =validation_errors();
            echo json_encode(array('error_msg'=>$error_msg));
        }
        else
        {
            //print_r($_POST);
            $user_data  = array( 
                'user_id'	=>$_POST['user'],
                'amount' => $_POST['amount'],
            );
            $this->db->insert('user_sales', $user_data);
            $insert_id = $this->db->insert_id();
            if($insert_id){
                /* add commission */
                $get_user_hierarchy = $this->get_user_hierarchy($_POST['user']);
                $commission = [1 => 0.10,2 => 0.05,3 => 0.03,4 => 0.02,5 => 0.01];
                foreach($get_user_hierarchy as $hierarchy){
                    $level=$hierarchy['level'];
                    if(isset($commission[$level])){
                        $discount_rate = $commission[$level];
                        $total_amount = $_POST['amount']*$discount_rate;
                        $commision_data  = array( 
                            'user_id'	=>$_POST['user'],
                            'sale_id' => $insert_id,
                            'amount'  => $total_amount,
                            'level'   => $level
                        );
                        $this->db->insert('user_commission', $commision_data);

                    }

                }
            }else{
                
            }
            
        }
    }

    function get_user_hierarchy($id){
        $this->db->select('*,u1.identifier as u1identifier,u2.identifier as u2identifier');
		$this->db->from('user_hierarchy u1');
        $this->db->join('user_hierarchy u2',"u2.identifier < u1.identifier AND u1.identifier LIKE CONCAT(u2.identifier, '%')");
		$this->db->where('u1.user_id', $id);
		$this->db->where('u2.level <=', 5);
        $query = $this->db->get();
		$result = $query->result_array();
        return $result;
    }
    
}
