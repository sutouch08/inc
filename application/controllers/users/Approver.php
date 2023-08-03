<?php
class Approver extends PS_Controller
{
	public $menu_code = "SCAPPV";
	public $menu_group_code = "SC";
	public $title = "Approval";

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url()."users/approver";
		$this->load->model("users/approver_model");
		$this->load->helper("approver");
		$this->segment = 4;
	}


	public function index()
	{
		$filter = array(
			'uname' => get_filter('uname', 'ap_uname', ''),
			'team' => get_filter('team', 'ap_team', 'all'),
			'brand' => get_filter('brand', 'ap_brand', 'all'),
			'status' => get_filter('status', 'ap_status', 'all')
		);

		$perpage = get_rows();

		$rows = $this->approver_model->count_rows($filter);

		$init = pagination_config($this->home.'/index/',$rows, $perpage, $this->segment);

		$approvers = $this->approver_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

		$filter['data'] = $approvers;

		$this->pagination->initialize($init);

		$this->load->view('approver/approver_list', $filter);
	}



	public function add_new()
	{
		$this->title = "Add Approver";

		if($this->pm->can_add)
		{
			$this->load->model('masters/sales_team_model');
			$this->load->model('masters/product_brand_model');

			$ds = array(
				'sales_team' => $this->sales_team_model->get_all(),
				'brand' => $this->product_brand_model->get_all()
			);

			$this->load->view('approver/approver_add', $ds);
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
			$user_id = $this->input->post('user_id');
			$uname = $this->input->post('uname');
			$team = $this->input->post('team');
			$brand = $this->input->post('brand');
			$status = $this->input->post('status') == 1 ? 1 : 0;

			if( ! empty($user_id) && ! empty($team) && ! empty($brand))
			{
				$arr = array(
					'user_id' => $user_id,
					'uname' => $uname,
					'status' => $status,
					'date_add' => now(),
					'add_user' => $this->_user->uname
				);

				if(! $this->approver_model->is_exists($user_id))
				{
					$this->db->trans_begin();
					$id = $this->approver_model->add($arr);

					if($id)
					{
						foreach($team as $team_id)
						{
							if($sc === FALSE)
							{
								break;
							}

							$arr = array(
								'id_approver' => $id,
								'id_team' => $team_id
							);

							if(! $this->approver_model->add_team($arr))
							{
								$sc = FALSE;
								$this->error = "Insert team failed";
							}
						}

						if($sc === TRUE)
						{
							foreach($brand as $rs)
							{
								if($sc === FALSE)
								{
									break;
								}

								$arr = array(
									'id_approver' => $id,
									'id_brand' => $rs['id'],
									'max_disc' => $rs['max_disc']
								);

								if(! $this->approver_model->add_brand($arr))
								{
									$sc = FALSE;
									$this->error = "Insert brand failed";
								}
							}
						}
					}
					else
					{
						$sc = FALSE;
						$this->error = "Insert Approver failed";
					}


					if($sc === TRUE)
					{
						$this->db->trans_commit();
					}
					else
					{
						$this->db->trans_rollback();
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Approver already exists";
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : form data');
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
		$this->title = "Edit Approver";

		if($this->pm->can_edit)
		{
			$this->load->model('masters/sales_team_model');
			$this->load->model('masters/product_brand_model');
			$ap_brand = array();
			$ap_team = array();

			$brand = $this->approver_model->get_approver_brand($id);

			if(!empty($brand))
			{
				foreach($brand as $bs)
				{
					$ap_brand[$bs->id_brand] = $bs->max_disc;
				}
			}

			$team = $this->approver_model->get_approver_team($id);

			if(!empty($team))
			{
				foreach($team as $tm)
				{
					$ap_team[$tm->id_team] = $tm->id_team;
				}
			}


			$ds = array(
				'approver' => $this->approver_model->get($id),
				'brand' => $this->product_brand_model->get_all(),
				'sales_team' => $this->sales_team_model->get_all(),
				'ap_team' => $ap_team,
				'ap_brand' => $ap_brand
			);

			$this->load->view('approver/approver_edit', $ds);
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
			$user_id = $this->input->post('user_id');
			$uname = $this->input->post('uname');
			$team = $this->input->post('team');
			$brand = $this->input->post('brand');
			$status = $this->input->post('status') == 1 ? 1 : 0;

			if( ! empty($user_id) && ! empty($team) && ! empty($brand))
			{
				$this->db->trans_begin();

				$arr = array(
					'status' => $status,
					'date_upd' => now(),
					'update_user' => $this->_user->uname
				);

				if( ! $this->approver_model->update($id, $arr))
				{
					$sc = FALSE;
					$this->error = "Update failed";
				}


				if(! $this->approver_model->drop_team($id))
				{
					$sc = FALSE;
					$this->error = "Drop approver team failed";
				}

				if(! $this->approver_model->drop_brand($id))
				{
					$sc = FALSE;
					$this->error = "Drop approver brand failed";
				}

				if($sc === TRUE)
				{
					foreach($team as $team_id)
					{
						if($sc === FALSE)
						{
							break;
						}

						$arr = array(
							'id_approver' => $id,
							'id_team' => $team_id
						);

						if(! $this->approver_model->add_team($arr))
						{
							$sc = FALSE;
							$this->error = "Insert team failed";
						}
					}

					if($sc === TRUE)
					{
						foreach($brand as $rs)
						{
							if($sc === FALSE)
							{
								break;
							}

							$arr = array(
								'id_approver' => $id,
								'id_brand' => $rs['id'],
								'max_disc' => $rs['max_disc']
							);

							if(! $this->approver_model->add_brand($arr))
							{
								$sc = FALSE;
								$this->error = "Insert brand failed";
							}
						}
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Insert Approver failed";
				}


				if($sc === TRUE)
				{
					$this->db->trans_commit();
				}
				else
				{
					$this->db->trans_rollback();
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : form data');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}




	public function view_detail($id)
	{
		$this->load->model('masters/sales_team_model');
		$this->load->model('masters/product_brand_model');
		$ap_brand = array();
		$ap_team = array();

		$brand = $this->approver_model->get_approver_brand($id);

		if(!empty($brand))
		{
			foreach($brand as $bs)
			{
				$ap_brand[$bs->id_brand] = $bs->max_disc;
			}
		}

		$team = $this->approver_model->get_approver_team($id);

		if(!empty($team))
		{
			foreach($team as $tm)
			{
				$ap_team[$tm->id_team] = $tm->id_team;
			}
		}


		$ds = array(
			'approver' => $this->approver_model->get($id),
			'brand' => $this->product_brand_model->get_all(),
			'sales_team' => $this->sales_team_model->get_all(),
			'ap_team' => $ap_team,
			'ap_brand' => $ap_brand
		);

		$this->load->view('approver/approver_view_detail', $ds);
	}


	public function delete()
	{
		$sc = TRUE;

		if($this->pm->can_delete)
		{
			$id = $this->input->post('id');

			if( ! empty($id))
			{
				if( ! $this->approver_model->delete($id))
				{
					$sc = FALSE;
					set_error('delete');
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

		$this->_response($sc);
	}


	public function clear_filter()
	{
		return clear_filter(array('ap_uname', 'ap_team', 'ap_brand', 'ap_status'));
	}

}

 ?>
