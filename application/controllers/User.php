<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    public function index()
    {
        $this->load->view('welcome_message');
    }

    public function login()
    {
        $this->data['title'] = "Login";

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if($this->ion_auth->logged_in()) {
            redirect('dashboard');
        } else if ($this->form_validation->run() === FALSE) {
            $this->data['username'] = $this->input->post('username');
            $this->data['wrong_password'] = true;
            $this->load->helper('form');
            $this->render('user/login_view');
        } else {
            $remember = (bool)$this->input->post('remember');
            if ($this->ion_auth->login($this->input->post('username'), $this->input->post('password'), $remember)) {
                redirect('dashboard');
            } else {
                $_SESSION['auth_message'] = $this->ion_auth->errors();
                $this->session->mark_as_flash('auth_message');
                $this->data['username'] = $this->input->post('username');
                $this->data['wrong_password'] = true;
                $this->render('user/login_view');
            }
        }
    }

    public function logout()
    {
        $this->ion_auth->logout();
        redirect('user/login');
    }
}