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
        $query = "SELECT * FROM users WHERE email = ? LIMIT 1;";

        $values = (array($email));

        return $this->db->query($query, $values)->row_array();
    }
    
    public function get_admin($user_info)
    {
        $login_query = "SELECT * FROM users WHERE email = ?
                                 AND role = 1
                                 AND password = ?";

        $values = (array($user_info['email'], $user_info['password']));

        return $this->db->query($login_query, $values)->row_array();
    }

    public function add_customer($user_info)
    {
        $insert_query = "INSERT INTO users (first_name, last_name, email, password, role, billing_full_name, billing_address_1, 
                         billing_address_2, billing_city, billing_state, billing_zip, shipping_full_name, shipping_address_1, 
                         shipping_address_2, shipping_city, shipping_state, shipping_zip, created_at)
                         VALUES (?, ?, ?, 'password123', 0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $values = (array(
            $user_info['shipping_first_name'], 
            $user_info['shipping_last_name'], 
            $user_info['email'], 
            $user_info['billing_first_name'] . " " . $user_info['billing_last_name'],
            $user_info['billing_address'],
            $user_info['billing_address2'],
            $user_info['billing_city'],
            $user_info['billing_state'],
            $user_info['billing_zip'],
            $user_info['shipping_first_name'] . " " . $user_info['shipping_last_name'],
            $user_info['shipping_address'],
            $user_info['shipping_address2'],
            $user_info['shipping_city'],
            $user_info['shipping_state'],
            $user_info['shipping_zip'],
        ));
        
        $this->db->query($insert_query, $values);
        return $this->db->insert_id();
    }
    
    public function insert_user($user_info)
    {
        $insert_query = "INSERT INTO users (first_name, last_name, email, password, created_at)
                            VALUES (?, ?, ?, ?, NOW())";
        $values = (array($user_info['first_name'], $user_info['last_name'], $user_info['email'], $user_info['password']));
        
        $this->db->query($insert_query, $values);
        return $this->db->insert_id();
    }

}

//end of file