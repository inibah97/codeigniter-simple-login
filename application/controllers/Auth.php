<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Auth Class
 *
 * @package     CodeIgniter Simple Login
 * @subpackage  Controllers
 * @category    Auth
 * @author      Muhammad Haibah <inibah97@gmail.com>
 * @link        https://github.com/inibah97
 */
class Auth extends CI_Controller
{
    /**
     * Construct functions
     * 
     * @return void
     */
    public function __construct()
    {
        // Load the parent construct
        parent::__construct();

        // Load the libraries
        $this->load->library(['session', 'form_validation']);

        // Load the helpers
        $this->load->helper(['url', 'string', 'cookie']);

        // Load the models
        $this->load->model(['Users_model']);
    }

    /**
     * Login - Default for this controller
     * 
     * @return void
     */
    public function index()
    {
        $this->checkAuth();

        $data = [
            'page' => [
                'title' => 'Login'
            ]
        ];

        $this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('remember', 'Remember Me', 'trim|integer');

        if ($this->form_validation->run() === false) {
            $this->load->view('login', $data);
        } else {
            $username = $this->input->post('username', true);
            $password = $this->input->post('password', true);
            $remember = $this->input->post('remember', true);

            $checkUser = $this->Users_model->getUserByField([
                'username' => $username
            ], true);

            if ($checkUser) {
                if (password_verify($password, $checkUser->password)) {
                    $this->session->set_userdata('username', $checkUser->username);

                    if ($remember == '1') {
                        $rememberToken = random_string('md5');
                        $this->Users_model->updateUser($checkUser->id, ['remember_token' => $rememberToken]);

                        set_cookie([
                            'name'   => '_auth',
                            'value'  => $rememberToken,
                            'expire' => (60 * 60 * 24 * 7)
                        ]);
                    }

                    $this->session->set_flashdata('success_message', 'Welcome, ' . $checkUser->username . '.');
                    redirect('/');
                } else {
                    $this->session->set_flashdata('error_message', 'Invalid Username or Password');
                    redirect('login', 'refresh');
                }
            } else {
                $this->session->set_flashdata('error_message', 'Invalid Username or Password');
                redirect('login', 'refresh');
            }
        }
    }

    /**
     * Register
     * 
     * @return void
     */
    public function register()
    {
        $this->checkAuth();

        $data = [
            'page' => [
                'title' => 'Register'
            ]
        ];

        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[6]|max_length[30]|alpha_numeric|is_unique[users.username]');
        $this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[6]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Confirm Password', 'trim|required|min_length[6]|matches[password1]');

        if ($this->form_validation->run() === false) {
            $this->load->view('register', $data);
        } else {
            $username = $this->input->post('username', true);
            $password = $this->input->post('password1', true);

            $createUser = $this->Users_model->insertUser($username, $password);
            if ($createUser) {
                $this->session->set_flashdata('success_message', 'Congratulations! your account has been created, please login.');
                redirect('login');
            } else {
                $this->session->set_flashdata('error_message', 'An error occurred! Please try again later.');
                redirect('register', 'refresh');
            }
        }
    }

    /**
     * Logout
     * 
     * @return void
     */
    public function logout()
    {
        $rememberToken = get_cookie('_auth', true);
        if ($rememberToken) {
            $checkUser = $this->Users_model->getUserByField([
                'remember_token' => $rememberToken
            ], true);

            if ($checkUser) {
                $this->Users_model->updateUser($checkUser->id, [
                    'remember_token' => null
                ]);
            }

            delete_cookie('_auth');
        }

        if ($this->session->has_userdata('username')) {
            $this->session->unset_userdata('username');
        }

        $this->session->set_flashdata('success_message', 'You have logged out.');
        redirect('login');
    }

    /**
     * Check Auth
     * 
     * @return void
     */
    private function checkAuth()
    {
        if ($this->session->has_userdata('username')) {
            redirect('/');
            die;
        } else {
            $rememberToken = get_cookie('_auth', true);
            if ($rememberToken) {
                $checkUser = $this->Users_model->getUserByField([
                    'remember_token' => $rememberToken
                ], true);

                if ($checkUser) {
                    $this->session->set_userdata('username', $checkUser->username);
                    $this->session->set_flashdata('success_message', 'Welcome back, ' . $checkUser->username . '.');
                    redirect('/');
                    die;
                }
            }
        }
    }
}
