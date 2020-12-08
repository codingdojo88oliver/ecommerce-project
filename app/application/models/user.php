<?php
class User extends CI_Model
{
    public $user;
    
    function __construct()
    {
        parent::__construct();
    }

    public function get_customer($email)
    {
        $get_customer_query  = "SELECT * FROM users WHERE email = ? LIMIT 1;";

        $get_customer_values = (array($email));

        return $this->db->query($get_customer_query, $get_customer_values)->row_array();
    }
    
    public function get_admin($user_info)
    {
        $get_admin_query = "SELECT * FROM users WHERE email = ?
                            AND role = 1
                            AND password = ?";

        $get_admin_values = (array($user_info['email'], $user_info['password']));

        return $this->db->query($get_admin_query, $get_admin_values)->row_array();
    }

    public function add_customer($user_info)
    {
        $add_customer_query = "INSERT INTO users (first_name, last_name, email, password, role, created_at)
                               VALUES (?, ?, ?, 'password123', 0, NOW())";

        $add_customer_values = (array(
            $user_info['shipping_first_name'], 
            $user_info['shipping_last_name'], 
            $user_info['email'], 
        ));
        
        $this->db->query($add_customer_query, $add_customer_values);

        return $this->db->insert_id();
    }
    
    public function insert_user($user_info)
    {
        $insert_user_query  = "INSERT INTO users (first_name, last_name, email, password, created_at)
                              VALUES (?, ?, ?, ?, NOW())";

        $insert_user_values = (array($user_info['first_name'], $user_info['last_name'], $user_info['email'], $user_info['password']));
        
        $this->db->query($insert_user_query, $insert_user_values);
        return $this->db->insert_id();
    }

}

//end of file