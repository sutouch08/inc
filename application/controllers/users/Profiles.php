<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profiles extends PS_Controller{
	public $menu_code = 'SCPROF'; //--- Add/Edit Profile
	public $menu_group_code = 'SC'; //--- System security
	public $title = 'Profiles';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'users/profiles';
    $this->load->model('users/profile_model');
  }




  public function index()
  {
		$profileName = get_filter('profileName', 'profileName', '');

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_filter('set_rows', 'rows', 20);
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = get_filter('rows', 'rows', 300);
		}

		$segment = 4; //-- url segment
		$rows = $this->profile_model->count_rows($profileName);

		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);

		$result = $this->profile_model->get_profiles($profileName, $perpage, $this->uri->segment($segment));
    $data = array();

    if(!empty($result))
    {
      foreach($result as $rs)
      {
        $row = new stdClass();
        $row->id = $rs->id;
        $row->name = $rs->name;
        $row->member = $this->profile_model->count_members($rs->id);
        $data[] = $row;
      }
    }

    $ds = array(
      'profileName' => $profileName,
			'data' => $data
    );

		$this->pagination->initialize($init);

    $this->load->view('users/profile_view', $ds);
  }




  public function add_profile()
  {
    $this->title = 'New profile';
    $this->load->view('users/profile_add_view');
  }




  public function edit_profile($id)
  {
    $data['data'] = $this->profile_model->get_profile($id);
    $this->title = 'Edit Profile';
    $this->load->view('users/profile_edit_view', $data);
  }



  public function clear_filter()
	{
		clear_filter('profileName');
		echo 'done';
	}
}
?>
