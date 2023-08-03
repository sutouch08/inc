<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vat_group extends PS_Controller
{
  public $menu_code = 'DBOVTG';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = '';
	public $title = 'Vat group';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/vat_group';
    $this->load->model('masters/vat_group_model');
  }


  public function index()
  {
    $filter = array(
			'code' => get_filter('code', 'vat_group_code', ''),
      'name' => get_filter('name', 'vat_group_name', ''),
			'status' => get_filter('status', 'vat_group_status', 'all')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();

		$rows     = $this->vat_group_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$filter['data'] = $this->vat_group_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

		$this->pagination->initialize($init);
    $this->load->view('masters/vat_group/vat_group_list', $filter);
  }



	public function edit($id)
  {
		if($this->pm->can_edit)
		{

			$data = $this->vat_group_model->get($id);

			if(!empty($data))
			{
				$this->load->view('masters/vat_group/vat_group_edit', $data);
			}
			else
			{
				$this->page_error();
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

		$id = $this->input->post('id');
		$code = trim($this->input->post('code'));
		$name = trim($this->input->post('name'));

		if($this->vat_group_model->is_exists_code($code, $id))
		{
			$sc = FALSE;
			set_error('exists', $code);
		}
		else
		{
			$arr = array(
				'code' => $code,
				'name' => $name
			);

			if( ! $this->vat_group_model->update($id, $arr))
			{
				$sc = FALSE;
				set_error('update');
			}
		}

		//--- send update to SAP
		$this->update_sap($id, $name);

		$this->_response($sc);
	}



	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');

		$res = $this->api->getVatGroupUpdateData();

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->vat_group_model->get_by_code($rs->Code);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->Code,
						"name" => empty($rs->Name) ? NULL : $rs->Name,
						"Rate" => empty($rs->Rate) ? 0.00 : get_zero($rs->Rate),
						"status" => empty($rs->Inactive) ? 1 : ($rs->Inactive == 'Y' ? 0 : 1),
						"last_sync" => now()
					);

					$this->vat_group_model->add($arr);
				}
				else
				{
					$arr = array(
						"code" => $rs->Code,
						"name" => empty($rs->Name) ? NULL : $rs->Name,
						"Rate" => empty($rs->Rate) ? 0.00 : get_zero($rs->Rate),
						"status" => empty($rs->Inactive) ? 1 : ($rs->Inactive == 'Y' ? 0 : 1),
						"last_sync" => now()
					);

					$this->vat_group_model->update($cr->id, $arr);
				}
			}
		}

		$this->_response($sc);
	}



	private function update_sap($id, $name)
	{
		return TRUE;
	}


  public function clear_filter()
	{
		return clear_filter(array('vat_group_code', 'vat_group_name', 'vat_group_status'));
	}


}//--- end class
 ?>
