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
		$this->segment = 4;
	}


	public function index()
	{
		$filter = array(
			'uname' => get_filter('uname', 'ap_uname', ''),
			'status' => get_filter('status', 'ap_status', 'all')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else
		{
			$perpage = get_rows();

			$rows = $this->approver_model->count_rows($filter);

			$init = pagination_config($this->home.'/index/',$rows, $perpage, $this->segment);

			$approvers = $this->approver_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

			$filter['data'] = $approvers;

			$this->pagination->initialize($init);

			$this->load->view('approver/approver_list', $filter);
		}

	}



	public function add_new()
	{

		if($this->pm->can_add)
		{
			$ds['docType'] = $this->approver_model->doc_type_list();

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
			$approval = json_decode($this->input->post('approval'));
			$status = $this->input->post('status') == 1 ? 1 : 0;

			if( ! empty($user_id) && ! empty($approval))
			{
				if(! $this->approver_model->is_exists($user_id))
				{
					$this->db->trans_begin();
					$arr = array(
						'user_id' => $user_id,
						'status' => $status,
						'add_user' => $this->_user->uname
					);

					$id = $this->approver_model->add($arr);

					if($id)
					{
						foreach($approval as $ap)
						{
							if($sc === FALSE)
							{
								break;
							}

							$arr = array(
								'id_approver' => $id,
								'docType' => $ap->docType,
								'review' => $ap->review,
								'approve' => $ap->approve,
								'minDisc' => $ap->minDisc,
								'maxDisc' => $ap->maxDisc,
								'minAmount' => $ap->minAmount,
								'maxAmount' => $ap->maxAmount
							);

							if(! $this->approver_model->add_rule($arr))
							{
								$sc = FALSE;
								$this->error = "Failed to create approval rule";
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
		if($this->pm->can_edit)
		{
			$ap = $this->approver_model->get_rules($id);
			$as = array();

			if( ! empty($ap))
			{
				foreach($ap as $a)
				{
					$as[$a->docType] =	array(
						'review' => $a->review,
						'approve' => $a->approve,
						'minDisc' => $a->minDisc,
						'maxDisc' => $a->maxDisc,
						'minAmount' => round($a->minAmount, 2),
						'maxAmount' => round($a->maxAmount, 2)
					);
				}
			}

			$ds = array(
				'approver' => $this->approver_model->get($id),
				'docType' => $this->approver_model->doc_type_list(),
				'rules' => $as
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
			$approval = json_decode($this->input->post('approval'));
			$status = $this->input->post('status') == 1 ? 1 : 0;

			if( ! empty($id) && ! empty($approval))
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


				if(! $this->approver_model->drop_approve_rule($id))
				{
					$sc = FALSE;
					$this->error = "Drop approver rules failed";
				}

				if($sc === TRUE)
				{
					foreach($approval as $ap)
					{
						if($sc === FALSE)
						{
							break;
						}

						$arr = array(
							'id_approver' => $id,
							'docType' => $ap->docType,
							'review' => $ap->review,
							'approve' => $ap->approve,
							'minDisc' => $ap->minDisc,
							'maxDisc' => $ap->maxDisc,
							'minAmount' => $ap->minAmount,
							'maxAmount' => $ap->maxAmount
						);

						if(! $this->approver_model->add_rule($arr))
						{
							$sc = FALSE;
							$this->error = "Failed to create approval rule";
						}
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Update Approver failed";
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
		$ap = $this->approver_model->get_rules($id);
		$as = array();

		if( ! empty($ap))
		{
			foreach($ap as $a)
			{
				$as[$a->docType] =	array(
					'review' => $a->review,
					'approve' => $a->approve,
					'minDisc' => $a->minDisc,
					'maxDisc' => $a->maxDisc,
					'minAmount' => round($a->minAmount, 2),
					'maxAmount' => round($a->maxAmount, 2)
				);
			}
		}

		$ds = array(
			'approver' => $this->approver_model->get($id),
			'docType' => $this->approver_model->doc_type_list(),
			'rules' => $as
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
				$this->db->trans_begin();

				if( ! $this->approver_model->delete($id))
				{
					$sc = FALSE;
					set_error('delete');
				}

				if($sc === TRUE)
				{
					if( ! $this->approver_model->drop_approve_rule($id))
					{
						$sc = FALSE;
						$this->error = "Delete appval rule failed";
					}
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
		return clear_filter(array('ap_uname', 'ap_status'));
	}

}

 ?>
