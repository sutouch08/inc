<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_team extends PS_Controller{
	public $menu_code = 'DBSTEAM'; //--- Add/Edit Profile
	public $menu_group_code = 'SC'; //--- System security
	public $title = 'Sales Team';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'users/sales_team';
		$this->load->model('users/sales_team_model');
  }


  public function index()
  {
		$filter = array(
			'name' => get_filter('name', 'st_name', ''),
			'code' => get_filter('code', 'st_code', '')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else
		{
			//--- แสดงผลกี่รายการต่อหน้า
			$perpage = get_filter('set_rows', 'rows', 20);

			if($perpage > 300)
			{
				$perpage = get_filter('rows', 'rows', 300);
			}

			$segment = 4; //-- url segment
			$rows = $this->sales_team_model->count_rows($filter);

			//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
			$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);

			$rs = $this->sales_team_model->get_list($filter, $perpage, $this->uri->segment($segment));

			if( ! empty($rs))
			{
				foreach($rs as $rd)
				{
					$rd->member = $this->sales_team_model->count_member($rd->id);
				}
			}

			$filter['data'] = $rs;

			$this->pagination->initialize($init);

	    $this->load->view('sale_team/team_list', $filter);
		}
  }


  public function add_new()
  {
		if($this->pm->can_add)
		{
			$this->load->view('sale_team/team_add');
		}
		else
		{
			$this->permission_deny();
		}
  }


	public function add()
	{
		$sc = TRUE;

		if($this->pm->can_add)
		{
			$name = trim($this->input->post('name'));

			if( ! empty($name))
			{
				if( ! $this->sales_team_model->is_exists_name($name))
				{
					$arr = array(
						'name' => $name
					);

					if( ! $this->sales_team_model->add($arr))
					{
						$sc = FALSE;
						set_error('insert');
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $name);
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : Name');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}



	public function edit($id)
	{
		if($this->pm->can_edit)
		{
			$ds = $this->sales_team_model->get($id);

			if( ! empty($ds))
			{
				$this->load->view('sale_team/team_edit', $ds);
			}
			else
			{
				$this->error_page();
			}
		}
		else
		{
			$this->permission_deny();
		}
	}


	public function update()
	{
		$sc = TRUE;

		if($this->pm->can_edit)
		{
			$id = $this->input->post('id');
			$name = trim($this->input->post('name'));

			if( ! empty($id) && ! empty($name))
			{
				if( ! $this->sales_team_model->is_exists_name($name, $id))
				{
					$arr = array(
						'name' => $name
					);

					if( ! $this->sales_team_model->update($id, $arr))
					{
						$sc = FALSE;
						set_error('update');
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $name);
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : id and name');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}



	public function delete()
	{
		$sc = TRUE;

		if($this->pm->can_delete)
		{
			$id = $this->input->post('id');

			if( ! empty($id))
			{
				$member = $this->sales_team_model->count_member($id);

				if( $member == 0)
				{
					if( ! $this->sales_team_model->delete($id))
					{
						$sc = FALSE;
						set_error('delete');
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Unable to delete team. This team has related to {$member} users";
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : id');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}


	public function clear_filter()
	{
		return clear_filter(array('st_name'));
	}
}
?>
