<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uom extends PS_Controller
{
  public $menu_code = 'DBPUOM';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'Units of Measure';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/uom';
    $this->load->model('masters/uom_model');
  }


  public function index()
  {
    $filter = array(
			'code' => get_filter('code', 'uom_code', ''),
      'name' => get_filter('name', 'uom_name', '')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();

		$rows     = $this->uom_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$filter['data'] = $this->uom_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

		$this->pagination->initialize($init);
    $this->load->view('masters/uom/uom_list', $filter);
  }



	public function edit($id)
  {
		if($this->pm->can_edit)
		{

			$data = $this->uom_model->get($id);

			if(!empty($data))
			{
				$this->load->view('masters/uom/uom_edit', $data);
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

		if($this->uom_model->is_exists_code($code, $id))
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

			if( ! $this->uom_model->update($id, $arr))
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
		$res = $this->api->getUomUpdateData();


		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->uom_model->get($rs->UomEntry);

				if(empty($cr))
				{
					$arr = array(
						"id" => $rs->UomEntry,
						"code" => $rs->UomCode,
						"name" => $rs->UomName,
						"last_sync" => now()
					);

					$this->uom_model->add($arr);
				}
				else
				{
					$arr = array(
						"code" => $rs->UomCode,
						"name" => $rs->UomName,
						"last_sync" => now()
					);

					$this->uom_model->update($rs->UomEntry, $arr);
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
		return clear_filter(array('uom_code', 'uom_name'));
	}


}//--- end class
 ?>
