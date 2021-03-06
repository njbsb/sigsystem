<?php
class Pages extends CI_Controller
{
    public function view($page = 'home')
    {
        if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
            show_404();
        }
        if ($this->session->userdata('logged_in')) {
            $username  = $this->session->userdata('username');
            $user = $this->user_model->get_user($username);
            switch ($user['usertype']) {
                case 'mentor':
                    $userspecific = $this->mentor_model->get_mentor_profile($username);
                    break;
                case 'student':
                    $userspecific = $this->student_model->get_student_profile($username);
                    break;
            }
            $profileComplete = (isset($userspecific)) ? true : false;

            $activesession = $this->academic_model->get_activeacademicsession();
            if ($activesession) {
                $activities = $this->activity_model->get_upcomingactivities($activesession['id']);
                foreach ($activities as $key => $act) {
                    $date_event = date_create($act['datetime_start']);
                    $date_now = date_create(date('y-m-d'));
                    $diff = date_diff($date_now, $date_event);
                    $d = ($date_event < $date_now) ? 'Event passed' : $diff->format("%r%a days");
                    $activities[$key]['diff'] = $d;
                }
            } else {
                $activities = array();
            }
            $coursecount = $this->student_model->get_studentbycourse();
            $intakedata = $this->student_model->get_studentbyintake();
            $pieData = [];
            $barData = [];
            $totalmembercount = 0;
            foreach ($coursecount as $row) {
                $pieData['label'][] = $row->program_id;
                $pieData['data'][] = $row->program_count;
                $totalmembercount += $row->program_count;
            }
            foreach ($intakedata as $intake) {
                $barData['label'][] = $intake->yearjoined;
                $barData['data'][] = $intake->intake_count;
            }
            $data = array(
                'user_name' => $user['name'],
                'user' => $user,
                'profileComplete' => $profileComplete,
                'activesession' => $this->academic_model->get_activeacademicsession(),
                'birthdaymembers' => $this->user_model->get_birthdaymembers(),
                'upcomingactivities' => $activities,
                'chart_data' => json_encode($pieData),
                'total_count' => $totalmembercount,
                'barchart_data' => json_encode($barData)
            );
            $this->load->view('templates/header');
            $this->load->view('pages/home_user', $data);
            $this->load->view('templates/footer');
        } else {
            $data['title'] = ucfirst($page);
            $this->load->view('templates/header');
            $this->load->view('pages/' . $page, $data);
            $this->load->view('templates/footer');
        }
    }

    public function filelink()
    {
        if ($this->session->userdata('logged_in') and $this->session->userdata('user_type') != 'student') {
            $data = array(
                'title' => 'File Links',
                'templates' => $this->file_model->get_template()
            );
            $this->load->view('templates/header');
            $this->load->view('pages/filelink', $data);
            $this->load->view('templates/footer');
        } else {
            redirect('home');
        }
    }

    public function updatelink()
    {
        $id = $this->input->post('editid');
        $name = $this->input->post('editname');
        $path = $this->input->post('editpath');
        if ($id && $name && $path) {
            $this->file_model->update_link($id, array('name' => $name, 'path' => $path));
        }

        redirect('filelink');
    }

    public function createlink()
    {
        $name = $this->input->post('newname');
        $path = $this->input->post('newpath');
        $data = array(
            'name' => $name,
            'path' => $path
        );
        $this->file_model->create_link($data);
        redirect('filelink');
    }

    public function deletelink()
    {
        $id = $this->input->post('deleteid');
        $this->file_model->delete_link($id);
        redirect('filelink');
    }

    public function badge()
    {
        if (!$this->session->userdata('username') or $this->session->userdata('user_type') == 'student') {
            redirect('home');
        }
        $data =  array(
            'images' => $this->file_model->get_badge()
        );
        $this->load->view('templates/header');
        $this->load->view('pages/badge', $data);
        $this->load->view('templates/footer');
    }

    public function createbadge()
    {
        $name = $this->input->post('newtitle');
        $upload_file = $_FILES['newfile']['tmp_name'];
        if ($upload_file) {
            $data = file_get_contents($upload_file);
            $type = pathinfo($_FILES["newfile"]["name"], PATHINFO_EXTENSION);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $imagedata = array(
                'title' => $name,
                'photo' => $base64
            );
            $this->file_model->create_badge($imagedata);
        }
        redirect('badge');
    }

    public function updatebadge()
    {
        $id = $this->input->post('editid');
        $title = $this->input->post('edittitle');
        $imagedata = array('title' => $title);
        $upload_file = $_FILES['editfile']['tmp_name'];
        if ($upload_file) {
            $data = file_get_contents($upload_file);
            $type = pathinfo($_FILES["editfile"]["name"], PATHINFO_EXTENSION);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $imagedata['photo'] = $base64;
        }
        $this->file_model->update_badge($id, $imagedata);
        redirect('badge');
    }
}