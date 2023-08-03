<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends PS_Controller
{
  public $menu_code = 'DBPDMD';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'Product Model';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/product_model';
    $this->load->model('masters/product_model_model');
  }


  public function index()
  {
    $filter = array(
      'name' => get_filter('name', 'model_name', ''),
			'code' => get_filter('code', 'model_code', '')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();

		$rows = $this->product_model_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$filter['data'] = $this->product_model_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

		$this->pagination->initialize($init);
    $this->load->view('masters/product_model/product_model_list', $filter);
  }


	public function add_new()
	{
		$this->load->view('masters/product_model/product_model_add');
	}


	public function add()
	{
		$sc = TRUE;
		$name = trim($this->input->post('name'));

		if( ! empty($name))
		{
			if( ! $this->product_model_model->is_exists_name($name))
			{
				$arr = array(
					'name' => $name
				);

				if( ! $this->product_model_model->add($arr))
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
			set_error('required');
		}

		$this->_response($sc);
	}


	public function edit($id)
  {
    $data = $this->product_model_model->get($id);
    $this->load->view('masters/product_model/product_model_edit', $data);
  }



	public function update()
	{
		$sc = TRUE;

		$id = $this->input->post('id');
		$name = trim($this->input->post('name'));

		if($this->product_model_model->is_exists_name($name, $id))
		{
			$sc = FALSE;
			set_error('exists', $name);
		}
		else
		{
			$arr = array(
				'name' => $name
			);

			if( ! $this->product_model_model->update($id, $arr))
			{
				$sc = FALSE;
				set_error('update');
			}
			else
			{
				//--- send update to SAP
				$this->update_sap($id);
			}
		}

		$this->_response($sc);
	}



	public function sync_data()
	{
		$sc = TRUE;
		$this->load->library('api');
		
		$date = $this->product_model_model->get_last_sync_date();

		$res = $this->api->getProductModelUpdateData($date);

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->product_model_model->get_by_code($rs->id);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->id,
						"name" => $rs->name,
						"last_sync" => now()
					);

					$this->product_model_model->add($arr);
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					$this->product_model_model->update($cr->id, $arr);
				}
			}
		}

		$this->_response($sc);
	}



	private function update_sap($id)
	{
		$rs = $this->product_model_model->get($id);

		if( ! empty($rs))
		{
			$this->load->library('update_api');
			$arr = array(
				'id' => $rs->code,
				'name' => $rs->name
			);

			return $this->update_api->updateProductModel($arr);
		}

		return FALSE;
	}


  public function clear_filter()
	{
		return clear_filter(array('model_name', 'model_code'));
	}


}//--- end class
 ?>
