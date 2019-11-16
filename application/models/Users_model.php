<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Users Model Class
 *
 * @package     CodeIgniter Simple Login
 * @subpackage  Models
 * @category    Users
 * @author      Muhammad Haibah <inibah97@gmail.com>
 * @link        https://github.com/inibah97
 */
class Users_model extends CI_Model
{
    /**
     * The table used in this model
     * 
     * @var array
     */
    private $_table = [
        'users'
    ];

    /**
     * Construct functions
     * 
     * @return void
     */
    public function __construct()
    {
        // Load the parent construct
        parent::__construct();

        // Load the database libraries
        $this->load->database();
    }

    /**
     * Get user by specific data
     * 
     * @param array $data
     * @param bool  $first Get only the first data
     * @param object
     */
    public function getUserByField($data, $first = false)
    {
        $this->db->where($data);

        if ($first === true) {
            return $this->db->get($this->_table[0])->row();
        } else {
            return $this->db->get($this->_table[0])->result();
        }
    }

    /**
     * Insert new user to resources
     * 
     * @param  string $username
     * @param  string $password
     * @param  array  $options
     * @return int              database affected rows
     */
    public function insertUser($username, $password, $options = [])
    {
        $insert = [
            'username'   => $username,
            'password'   => password_hash($password, PASSWORD_BCRYPT),
            'created_at' => time(),
            'updated_at' => time()
        ];

        foreach ($options as $key => $value) {
            $insert[$key] = $value;
        }

        $this->db->insert($this->_table[0], $insert);
        return $this->db->affected_rows();
    }

    /**
     * Update user by specific data
     * 
     * @param  int   $id   USERS.ID
     * @param  array $data
     * @return int         Database affected rows
     */
    public function updateUser($id, $data)
    {
        $update = [
            'updated_at' => time()
        ];

        foreach ($data as $key => $value) {
            $update[$key] = $value;
        }

        $this->db->set($update);
        $this->db->where('id', $id);
        $this->db->update($this->_table[0]);
        return $this->db->affected_rows();
    }
}
