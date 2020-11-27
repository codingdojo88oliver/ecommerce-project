<?php
class User extends CI_Model
{
    public $user;
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_admin($user_info)
    {
        $login_query = "SELECT * FROM users WHERE email = ?
                                 AND role = 1
                                 AND password = ?";

        $values = (array($user_info['email'], $user_info['password']));

        return $this->db->query($login_query, $values)->row_array();
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