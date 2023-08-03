<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channels extends PS_Controller
{
  public $menu_code = 'DBCHAN';
	public $menu_group_code = 'DB';
	public $title = 'Sales Channels';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/channels';
    $this->load->model('masters/channels_model');
  }


  public function index()
  {
		$filter = array(
			'code' => get_filter('code', 'channels_code', ''),
			'name' => get_filter('name', 'channels_name', '')
		);

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();

		$rows = $this->channels_model->count_rows($filter);

		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);

		$filter['data'] = $this->channels_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

		$this->pagination->initialize($init);

    $this->load->view('masters/channels/channels_list', $filter);
  }


	public function add_new()
	{
		if($this->pm->can_add)
		{
			$ds['top_position'] = $this->channels_model->get_top_position();
			$this->load->view('masters/channels/channels_add', $ds);
		}
		else
		{
			$this->permission_deny();
		}
	}


	public function add()
	{
		$sc = TRUE;

		$code = $this->input->post('code');
		$name = $this->input->post('name');
		$active = $this->input->post('active');
		$position = $this->input->post('position');

		if(! empty($code) && !empty($name))
		{
			if(!$this->channels_model->is_exists_code($code))
			{
				if(! $this->channels_model->is_exists($name))
				{
					$arr = array(
						'code' => $code,
						'name' => $name,
						'active' => $active == 1 ? 1 : 0,
						'position' => $position
					);

					if( ! $this->channels_model->add($arr))
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
				set_error('exists', $code);
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}


		$this->_response($sc);
	}



/*
	public function sync_data()
	{
		$sc = TRUE;

		$response = json_encode(array(
			array("id" => 1, "name" => "ร้านค้า"),
			array("id" => 2, "name" => "ผู้รับเหมา"),
			array("id" => 3, "name" => "Modern trade"),
			array("id" => 4, "name" => "Shopee"),
			array("id" => 5, "name" => "Lazada"),
			array("id" => 6, "name" => "JD Central"),
			array("id" => 7, "name" => "Walk in"),
			array("id" => 8, "name" => "Chat"),
			array("id" => 9, "name" => "เจ้าของโครงการ"),
			array("id" => 10, "name" => "Events"),
			array("id" => 11, "name" => "โรงงาน"),
			array("id" => 12, "name" => "Promotions")
		));

		$res = json_decode($response);

		if( ! empty($res))
		{
			foreach($res as $rs)
			{
				$ch = $this->channels_model->get($rs->id);

				if(empty($ch))
				{
					$arr = array(
						"id" => $rs->id,
						"name" => $rs->name,
						"position" => $this->channels_model->get_top_position(),
						"last_sync" => now()
					);

					$this->channels_model->add($arr);
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					$this->channels_model->update($rs->id, $arr);
				}
			}
		}

		$this->_response($sc);
	}

*/

  public function edit($id)
  {
		$this->title = "Edit Channels";

		if($this->pm->can_edit)
		{
			$channels = $this->channels_model->get($id);
			if( ! empty($channels))
			{
				$this->load->view('masters/channels/channels_edit', $channels);
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
			$position = intval($this->input->post('position'));
			$active = $this->input->post('active') == 1 ? 1 : 0;
			$is_default = $this->input->post('is_default') == 1 ? 1 : 0;

			if( ! empty($name) && ! empty($id))
			{
				if( ! $this->channels_model->is_exists($name, $id))
				{
					$arr = array(
						'name' => $name,
						'position' => $position,
						'active' => $active,
						'is_default' => $is_default
					);

					;

					if( ! $this->channels_model->update($id, $arr))
					{
						$sc = FALSE;
						set_error('update');
					}
					else
					{
						if($is_default == 1)
						{
							$this->db->trans_begin();

							$unset = $this->channels_model->unset_default();
							$set = $this->channels_model->set_default($id);

							if($unset && $set)
							{
								$this->db->trans_commit();
							}
							else
							{
								$this->db->trans_rollback();
							}
						}
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
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}



	public function is_exists_name()
	{
		$id = $this->input->post('id');
		$name = trim($this->input->post('name'));

		if($this->channels_model->is_exists($name, $id))
		{
			echo 'exists';
		}
		else
		{
			echo 'ok';
		}
	}

	public function is_exists_code()
	{
		$id = $this->input->post('id');
		$code = trim($this->input->post('code'));

		if($this->channels_model->is_exists_code($code, $id))
		{
			echo 'exists';
		}
		else
		{
			echo 'ok';
		}
	}



	public function delete()
	{
		$sc = TRUE;

		if($this->pm->can_delete)
		{
			$id = $this->input->post('id');

			if( ! empty($id))
			{
				if( ! $this->channels_model->has_transection($id))
				{
					if( ! $this->channels_model->delete($id))
					{
						$sc = FALSE;
						set_error('delete');
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = 'Delete failed because completed transections exists.';
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', 'id');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->response($sc);
	}


  public function clear_filter()
	{
		return clear_filter(array('channels_code','channels_name'));
	}

}//--- end class
 ?>
