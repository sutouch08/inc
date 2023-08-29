<?php
class Access_logs extends PS_Controller
{
  public $menu_code = "SCACLOG";
	public $menu_group_code = "SC";
	public $title = "Access Logs";

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url()."users/access_logs";
		$this->load->model("users/logs_model");
    $this->load->helper('access_logs');
		$this->segment = 4;
	}


	public function index()
	{
		$filter = array(
			'uname' => get_filter('uname', 'ac_uname', ''),
			'docType' => get_filter('docType', 'ac_docType', 'all'),
      'docNum' => get_filter('docNum', 'ac_docNum', ''),
      'action' => get_filter('action', 'ac_action', 'all'),
      'ip_address' => get_filter('ip_address', 'ac_ip_address', ''),
      'from_date' => get_filter('from_date', 'ac_from_date', ''),
      'to_date' => get_filter('to_date', 'ac_to_date', '')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else
		{
			$perpage = get_rows();

			$rows = $this->logs_model->count_rows($filter);
			$init = pagination_config($this->home.'/index/',$rows, $perpage, $this->segment);
			$filter['data'] = $this->logs_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
      $filter['docList'] = $this->user_model->docList();
			$this->pagination->initialize($init);

			$this->load->view('users/access_logs', $filter);
		}
	}


  public function clear_filter()
  {
    $filter = array('ac_uname', 'ac_docType', 'ac_docNum', 'ac_action', 'ac_ip_address', 'ac_from_date', 'ac_to_date');

    return clear_filter($filter);
  }
} //-- end class


?>
