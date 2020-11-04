<?php
class User extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'All Users';
        $data['users'] = $this->user_model->get_user();
        $this->load->view('templates/header');
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    public function profile()
    {
        $data['title'] = 'User profile';
        $id = 'A160001'; # get the current session user first
        $data['user'] = $this->user_model->get_user($id);
        $sig_id = $data['user']['sig_id'];
        $usertype = $data['user']['usertype_id'];

        $this->load->view('templates/header');
        switch ($usertype) {
            case '1':
                $data['admin'] = $this->admin_model->get_admin($id);
                $this->load->view('user/admin/profile', $data);
                break;
            case '2':
                $data['mentor'] = $this->mentor_model->get_mentor($id);
                $data['activity_roles'] = $this->committee_model->get_activityroles($id);
                $this->load->view('user/mentor/profile', $data);
                break;
            case '3':
                $data['student'] = $this->student_model->get_student($id);
                $data['activity_roles'] = $this->committee_model->get_activityroles($id);
                $data['org_roles'] = $this->committee_model->get_orgroles($id, $sig_id);
                $this->load->view('user/student/profile', $data);
                break;
        }
        $this->load->view('templates/footer');
    }

    public function edit()
    {
        $data['title'] = 'Update profile';
        $id = 'A160001'; # get the current session user 
        if (!$id) {
            redirect('home');
        }
        $data['user'] = $this->user_model->get_user($id);
        $usertype_id = $data['user']['usertype_id'];

        $data['sigs'] = $this->sig_model->get_sig();
        $data['programs'] = $this->program_model->get_programs();
        $data['mentors'] = $this->mentor_model->get_mentor();

        $this->load->view('templates/header');
        switch ($usertype_id) {
            case '1':
                $data['admin'] = $this->admin_model->get_admin($id);
                $this->load->view('user/admin/update', $data);
                break;
            case '2':
                $data['mentor'] = $this->mentor_model->get_mentor($id);
                $this->load->view('user/mentor/update', $data);
                break;
            case '3':
                $data['student'] = $this->student_model->get_student($id);
                $this->load->view('user/student/update', $data);
                break;
        }
        $this->load->view('templates/footer');
        # NOT DONE
        # get the current session user
    }

    public function update($user_id)
    {
        $usertype_id = $this->input->post('usertype_id');
        $config['upload_path'] = './assets/images/profile';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 500;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;
        $config['file_name'] = $user_id . '-' . substr(md5(rand()), 0, 10);
        $this->load->library('upload', $config);

        if (@$_FILES['profile_image']['name'] != NULL) {
            if ($this->upload->do_upload('profile_image')) {
                $profile_image = $this->upload->data('file_name');
            } else {
                $profile_image = 'default.jpg';
            }
        }
        switch ($usertype_id) {
            case '1':
                //
                break;
            case '2':
                //
                break;
            case '3':
                $userchange = array('profile_image' => $profile_image);
                $studentchange = array('phonenum' => $this->input->post('phonenum'));
                $this->student_model->update_student($user_id, $studentchange);
                $this->user_model->update_user($user_id, $userchange);
                break;
        }
        redirect('profile');
    }

    public function delete($id)
    {
        $usertype_id = $this->user_model->get_usertype($id);
        if ($usertype_id) {
            switch ($usertype_id) {
                case 1:
                    // admin
                    $this->admin_model->delete_admin($id);
                    break;
                case 2:
                    // mentor
                    $this->mentor_model->delete_mentor($id);
                    break;
                case 3:
                    // student
                    $this->student_model->delete_student($id);
                    break;
            }
            $this->user_model->delete_user($id);
        }
        redirect('user');
    }

    public function login()
    {
        $data['title'] = 'Login';
        $this->load->view('templates/header');
        $this->load->view('user/login', $data);
    }

    public function register()
    {
        $usertype_id = $this->input->post('usertype_id');
        if (!$usertype_id) {
            redirect('home');
        }
        # this usertype_id is from login page

        # VALUE RETRIEVAL
        # this will return no value if we came from login page
        # but will return value if we submit registration form
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $sig_id = $this->input->post('sig_id');
        $dob = $this->input->post('dob');

        if ($usertype_id == '2') {
            $position = $this->input->post('position');
            $roomnum = $this->input->post('roomnum');
            $orgrole_id = $this->input->post('orgrole_id');
        } elseif ($usertype_id = '3') {
            $program_code = $this->input->post('program_code');
            $phonenum = $this->input->post('phonenum');
        }
        # END VALUE RETRIEVAL

        # VALIDATION OF REGISTRATION FORM
        if ($id && $name && $email && $password && $sig_id && $dob) {
            $this->form_validation->set_rules('id', 'ID', 'required|callback_id_exist');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'matches[password]');
            if ($usertype_id == '2' && $position && $roomnum && $orgrole_id) {
                $this->form_validation->set_rules('position', 'Position', 'required');
                $this->form_validation->set_rules('roomnum', 'Room number', 'required');
                $this->form_validation->set_rules('orgrole_id', 'Organization role', 'required');
            } elseif ($usertype_id == '3' && $program_code && $phonenum) {
                $this->form_validation->set_rules('program_code', 'Program', 'required');
                $this->form_validation->set_rules('phonenum', 'Phone Number', 'required');
            }
        }
        # END VALIDATION

        if ($this->form_validation->run() === FALSE) {
            # ON FIRST LANDING TO REGISTRATION FORM
            # NO REGISTRATION DATA IS SUBMITTED

            if ($usertype_id) {
                $data['usertype_name'] = ucfirst($this->user_model->get_usertypename($usertype_id));
            } else {
                redirect('login');
            }
            $data['usertype_id'] = $usertype_id; # this is to be sent as value in the registration form
            $data['title'] = 'Register';
            $data['sigs'] = $this->sig_model->get_sig();

            # this will fetch data from previously filled registration form when validation error occurs
            $data['id'] = $id;
            $data['name'] = $name;
            $data['email'] = $email;
            $data['sig_id'] = $sig_id;
            $data['dob'] = $dob;

            $this->load->view('templates/header');

            if ($usertype_id == '2') {
                // MENTOR
                $data['position'] = $position;
                $data['roomnum'] = $roomnum;
                $data['orgrole_id'] = $orgrole_id;
                $data['mentorroles'] = $this->role_model->get_mentor_roles();
                $this->load->view('user/mentor/register', $data);
            } else {
                // STUDENT
                $data['program_code'] = $program_code;
                $data['phonenum'] = $phonenum;
                $data['programs'] = $this->program_model->get_programs();
                $this->load->view('user/student/register', $data);
            }
        } else {
            # INSERT DATA TO DB
            $enc_password = md5($this->input->post('password'));
            $userdata = array(
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'password' => $enc_password,
                'sig_id' => $sig_id,
                'usertype_id' => $usertype_id,
                'dob' => $dob
            );
            $this->user_model->register_user($userdata);

            if ($usertype_id == '2') {
                # send data to mentor model
                $mentordata = array(
                    'matric' => $id,
                    'position' => $position,
                    'roomnum' => $roomnum,
                    'orgrole_id' => $orgrole_id
                );
                $this->mentor_model->register_mentor($mentordata);
            } elseif ($usertype_id = '3') {
                # send data to student model
                $studentdata = array(
                    'matric' => $id,
                    'phonenum' => $phonenum,
                    'program_code' => $program_code
                );
                $this->student_model->register_student($studentdata);
            }
            $data['title'] = 'Registration Successful';
            $data['content'] = 'Your registration is currently pending admin\'s approval. Your admin will contact you once your registration is approved';
            $this->load->view('templates/header');
            $this->load->view('user/register_success', $data);
        }
    }

    public function id_exist($id)
    {
        $id_exist = $this->user_model->id_exist($id);
        if ($id_exist == true) {
            $this->form_validation->set_message('id_exist',  'User already exists. Please select another ID');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function validate($id)
    {
        $data['title'] = 'Validate: ' . $id;
        $data['user'] = $this->user_model->get_user($id);
        $usertype_id = $data['user']['usertype_id'];
        // validate
        $this->form_validation->set_rules('id', 'ID', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('sig_id', 'SIG', 'required');
        if ($usertype_id == '2') {
            $this->form_validation->set_rules('position', 'Position', 'required');
            $this->form_validation->set_rules('roomnum', 'Room Number', 'required');
            $this->form_validation->set_rules('orgrole_id', 'SIG Role', 'required');
        } elseif ($usertype_id == '3') {
            $this->form_validation->set_rules('phonenum', 'Phone Number', 'required');
            $this->form_validation->set_rules('program_code', 'Program Code', 'required');
            $this->form_validation->set_rules('mentor_matric', 'Mentor Matric', 'required');
        }

        if ($this->form_validation->run() === FALSE) {

            $data['sigs'] = $this->sig_model->get_sig();
            $data['userstatuses'] = $this->user_model->get_userstatus();
            $this->load->view('templates/header');
            $this->load->view('user/validate', $data);

            if ($usertype_id == 2) {
                # IF MENTOR
                $data['mentorroles'] = $this->role_model->get_mentor_roles();
                $data['mentor'] = $this->mentor_model->get_mentor($id);
                print_r($data['mentor']);
                $this->load->view('user/mentor/validate_mentor', $data);
            } elseif ($usertype_id == 3) {
                # IF STUDENT
                $data['sigmentors'] = $this->mentor_model->get_sigmentors($data['user']['sig_id']);
                $data['programs'] = $this->program_model->get_programs();
                $data['student'] = $this->student_model->get_student($id);
                $this->load->view('user/student/validate_student', $data);
            }
            $this->load->view('templates/footer');
        } else {
            # FORM SUBMISSION HERE
            $userdata = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'sig_id' => $this->input->post('sig_id'),
                'dob' => $this->input->post('dob'),
                'userstatus_id' => $this->input->post('userstatus_id')
            );
            if ($usertype_id == '2') {
                $mentordata = array(
                    'position' => $this->input->post('position'),
                    'roomnum' => $this->input->post('roomnum'),
                    'orgrole_id' => $this->input->post('orgrole_id'),
                );
                if ($this->mentor_model->mentor_exist($id)) {
                    $this->mentor_model->update_mentor($id, $mentordata);
                } else {
                    $this->mentor_model->register_mentor($mentordata);
                }
            } elseif ($usertype_id == '3') {
                $studentdata = array(
                    'matric' => $id,
                    'phonenum' => $this->input->post('phonenum'),
                    'program_code' => $this->input->post('program_code'),
                    'mentor_matric' => $this->input->post('mentor_matric')
                );
                if ($this->student_model->student_exist($id)) {
                    $this->student_model->update_student($id, $studentdata);
                } else {
                    $this->student_model->register_student($studentdata);
                }
            }
            $this->user_model->update_user($id, $userdata);
            // $this->user_model->approve_user($id);
            redirect('user');
        }
    }

    public function set_userstatus($user_id)
    {
    }
}
