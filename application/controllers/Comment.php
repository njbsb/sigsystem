<?php
class Comment extends CI_Controller
{

    public function create($activity_id)
    {
        $slug = $this->input->post('slug');
        $data['activity'] = $this->activity_model->get_activity($slug);
        $this->form_validation->set_rules('comment', 'Comment', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('activity/view', $data);
            $this->load->view('activity/committee');
            $this->load->view('activity/comments');
            $this->load->view('templates/footer');
        } else {
            $commentdata = array(
                'activity_id' => $activity_id,
                'student_matric' => $this->input->post('id'),
                'comment' => $this->input->post('comment'),
                'category_id' => $this->input->post('category_id')
            );
            $this->comment_model->create_comment($commentdata);
            redirect('activity/' . $slug);
        }
    }

    public function pagination($activity_id)
    {
        $config = array(
            'base_url' => '#',
            'total_rows' => $this->comment_model->count_all($activity_id),
            'per_page' => 5,
            'uri_segment' => 3,
            'use_page_numbers' => TRUE,
            'full_tag_open' => '<ul class="pagination">',
            'full_tag_close' => '</ul>',
            'first_tag_open' => '<li>',
            'first_tag_close' => '</li>',
            'last_tag_open' => '<li>',
            'last_tag_close' => '</li>',
            'next_link' => '&gt;',
            'next_tag_open' => '<li>',
            'next_tag_close' => '</li>',
            'prev_link' => '&lt;',
            'prev_tag_open' => '<li>',
            'prev_tag_close' => '</li>',
            'cur_tag_open' => '<li class="active"><a href="#">',
            'cur_tag_close' => '</a></li>',
            'num_tag_open' => '<li>',
            'num_tag_close' => '</li>',
            'num_links' => 1
        );
        $this->pagination->initialize($config);
        $page = $this->$this->uri->segment(3);
        $start = ($page - 1) * $config['per_page'];
        $output = array(
            'pagination_link' => $this->pagination->create_links(),
            'comment_table' => $this->comment_model->fetch_details($activity_id, $config['per_page'], $start)
        );
        echo json_encode($output);
    }
}
