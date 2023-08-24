<?php
class Signature extends PS_Controller
{
	public $menu_code = 'SCSIGN';
	public $menu_group_code = 'SC';
	public $title = 'Signature';
	public $segment = 4;

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url().'users/signature';
		$this->load->model('users/signature_model');
		$this->load->model('masters/employee_model');

	}


	public function index()
	{
		$filter = array(
			'name' => get_filter('name', 'emp_name', '')
		);

		$perpage = get_rows();

		$rows = $this->signature_model->count_rows($filter);

		$filter['data'] = $this->signature_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

		$init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);

		$this->pagination->initialize($init);

		$this->load->view('users/signature_list', $filter);
	}



	public function add()
	{
		$sc = TRUE;
		$emp_id = $this->input->post('emp_id');
		$image_data = $this->input->post('image_data');

		if( ! empty($emp_id))
		{
			if( ! empty($image_data))
			{
				$emp = $this->employee_model->get($emp_id);

				if( ! empty($emp))
				{
					$arr = array(
						'emp_id' => $emp->id,
						'firstName' => $emp->firstName,
						'lastName' => $emp->lastName,
						'image_data' => $image_data
					);

					if( ! $this->signature_model->is_exists($emp->id))
					{
						if( ! $this->signature_model->add($arr))
						{
							$sc = FALSE;
							$this->error = "Failed to add employee";
						}
					}
					else
					{
						if( ! $this->signature_model->update($emp_id, $arr))
						{
							$sc = FALSE;
							$this->error = "Failed to update employee data";
						}
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Invalid Employee";
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "No Image Data Found";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "No Employee Found";
		}

		$this->_response($sc);
	}


	public function delete()
	{
		$sc = TRUE;

		if($this->pm->can_delete)
		{
			$emp_id = $this->input->post('emp_id');
			
			if( ! $this->signature_model->delete($emp_id))
			{
				$sc = FALSE;
				set_error('delete');
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
		return clear_filter(array("emp_name"));
	}


} //-- end class
 ?>
