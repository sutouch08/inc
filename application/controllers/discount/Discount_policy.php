<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount_policy extends PS_Controller
{
  public $menu_code = 'SCPOLI';
	public $menu_group_code = 'SC';
	public $title = 'Promotions';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'discount/discount_policy';
    $this->load->model('discount/discount_policy_model');
    $this->load->model('discount/discount_rule_model');
  }


  public function index()
  {
		$filter = array(
			'code' => get_filter('policy_code', 'policy_code', ''),
			'name' => get_filter('policy_name', 'policy_name', ''),
			'active' => get_filter('active', 'active', 'all'),
			'start_date' => get_filter('start_date', 'start_date', ''),
			'end_date' => get_filter('end_date', 'end_date', '')
		);

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();

		$rows = $this->discount_policy_model->count_rows($filter);

		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);

		$filter['data'] = $this->discount_policy_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

		$this->pagination->initialize($init);

    $this->load->view('discount/policy/policy_list', $filter);
  }




  public function add_new()
  {
    if($this->pm->can_add)
    {
      $this->load->view('discount/policy/policy_add');
    }
    else
    {
      $this->permission_page();
    }
  }



  public function add()
  {
		$sc = TRUE;

    if($this->input->post('name'))
    {
			if($this->pm->can_add)
			{
				$code = $this->get_new_code();

	      $arr = array(
	        'code' => $code,
	        'name' => $this->input->post('name'),
	        'start_date' => db_date($this->input->post('start_date')),
	        'end_date' => db_date($this->input->post('end_date')),
	        'user' => $this->_user->uname
	      );


				$id = $this->discount_policy_model->add($arr);

				if($id == FALSE)
				{
					$sc = FALSE;
					set_error('insert');
				}
			}
			else
			{
				$sc = FALSE;
				set_error('permission');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		echo $sc === TRUE ? $id : $this->error;
  }


  public function edit($id)
  {
    $this->load->helper('discount_rule');
    $rs = $this->discount_policy_model->get($id);
    $data['policy'] = $rs;
    $data['rules']  = $this->discount_rule_model->get_policy_rules($rs->id);

    $this->load->view('discount/policy/policy_edit', $data);
  }


  public function view_detail($id)
  {
    $this->load->helper('discount_rule');
    $rs = $this->discount_policy_model->get($id);
    $data['policy'] = $rs;
    $data['rules']  = $this->discount_rule_model->get_policy_rules($rs->id);
    $this->load->view('discount/policy/policy_view_detail', $data);
  }



  public function update()
  {
		$sc = TRUE;
    $id = $this->input->post('id');

    $ds = array(
      'name' => trim($this->input->post('name')),
      'start_date' => db_date($this->input->post('start_date')),
      'end_date' => db_date($this->input->post('end_date')),
      'active' => $this->input->post('active'),
      'update_user' => $this->_user->uname
    );

		if( ! $this->discount_policy_model->update($id, $ds))
		{
			$sc === FALSE;
			set_error('update');
		}

		$this->_response($sc);
  }



  public function get_active_rule()
  {
  	$rules = $this->discount_rule_model->get_active_rule();
  	$ds = array();
    if(!empty($rules))
    {
      foreach($rules as $rs)
      {
        $arr = array(
          'id_rule' => $rs->id,
          'ruleCode' => $rs->code,
          'ruleName' => $rs->name,
          'date_upd' => thai_date($rs->date_upd)
        );

        array_push($ds, $arr);
      }
    }
    else
    {
      $arr = array('nodata' => 'nodata');
      array_push($ds, $arr);
    }

    echo json_encode($ds);
  }




  public function delete_policy($id)
  {
    $rs = $this->discount_policy_model->delete($id);

    echo $rs->status === TRUE ? 'success' : $rs->message;
  }



  public function get_new_code()
  {
    $date = date('Y-m-d');
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_POLICY');
    $run_digit = getConfig('RUN_DIGIT_POLICY');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->discount_policy_model->get_max_code($pre);
    if(! is_null($code))
    {
      $run_no = mb_substr($code, ($run_digit*-1), NULL, 'UTF-8') + 1;
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', $run_no);
    }
    else
    {
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', '001');
    }

    return $new_code;
  }


  public function clear_filter()
  {
    $filter = array('policy_code', 'policy_name', 'active', 'start_date', 'end_date');
    clear_filter($filter);
    echo 'done';
  }

}//-- end class
?>
