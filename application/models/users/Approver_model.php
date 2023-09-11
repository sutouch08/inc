<?php
class Approver_model extends CI_Model
{
	public $tb = "approver";
	public $td = "approve_rule";

	public function __construct()
	{
		parent::__construct();
	}


	public function get($id)
	{
		$rs = $this->db->where('id', $id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_rules($id)
	{
		$rs = $this->db->where('id_approver', $id)->get($this->td);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function add(array $ds = array())
	{
		if(!empty($ds))
		{
			$rs = $this->db->insert($this->tb, $ds);

			if($rs)
			{
				return $this->db->insert_id();
			}
		}

		return FALSE;
	}


	public function add_rule(array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->insert($this->td, $ds);
		}

		return FALSE;
	}


	public function update($id, array $ds = array())
	{
		if(!empty($ds))
		{
			return $this->db->where('id', $id)->update($this->tb, $ds);
		}

		return FALSE;
	}


	public function drop_approve_rule($id_approver)
	{
		return $this->db->where('id_approver', $id_approver)->delete($this->td);
	}


	public function delete($id)
	{
		return $this->db->where('id', $id)->delete($this->tb);
	}


	public function doc_type_list()
	{
		$rs = $this->db->get('doc_type');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		$this->db
		->select('a.*, u.uname, u.name, u.emp_id')
		->from('approver AS a')
		->join('user AS u', 'a.user_id = u.id', 'left');

		if(!empty($ds['uname']))
		{
			$this->db->group_start();
			$this->db->like('u.uname', $ds['uname']);
			$this->db->or_like('u.name', $ds['uname']);
			$this->db->group_end();
		}

		if(isset($ds['status']) && $ds['status'] !== 'all')
		{
			$this->db->where('a.status', $ds['status']);
		}

		$rs = $this->db->order_by('u.uname', 'ASC')->limit($perpage, $offset)->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function count_rows(array $ds = array())
	{
		$this->db
		->from('approver AS a')
		->join('user AS u', 'a.user_id = u.id', 'left');

		if(!empty($ds['uname']))
		{
			$this->db->group_start();
			$this->db->like('u.uname', $ds['uname']);
			$this->db->or_like('u.name', $ds['uname']);
			$this->db->group_end();
		}

		if(isset($ds['status']) && $ds['status'] !== 'all')
		{
			$this->db->where('a.status', $ds['status']);
		}

		return $this->db->count_all_results();
	}



	public function is_exists($user_id, $id = NULL)
	{
		if(!empty($id))
		{
			$this->db->where('id !=', $id);
		}

		$rs = $this->db->where('user_id', $user_id)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return TRUE;
		}

		return FALSE;
	}


	public function get_rule_by_user_id($user_id, $docType)
	{
		$rs = $this->db
		->select('a.*, r.docType, r.review, r.approve, r.maxDisc, r.maxAmount')
		->from('approve_rule AS r')
		->join('approver AS a', 'r.id_approver = a.id', 'left')
		->where('a.user_id', $user_id)
		->where('a.status', 1)
		->where('r.docType', $docType)
		->get();

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}

	public function get_doc_rule($approver_id, $docType)
	{
		$rs = $this->db->where('id_approver', $approver_id)->where('docType', $docType)->get('approve_rule');

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	//--- เช็คว่ามีรายชื่ออยู่ในกลุ่มผู้อนุมัติหรือไม่ (ใช้ในหน้า Main เพื่อแจ้งเตือนการอนุมัติ)
	public function is_approver($user_id)
	{
		$rs = $this->db->where('user_id', $user_id)->where('status', 1)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return TRUE;
		}

		return FALSE;
	}


	public function get_by_user_id($user_id)
	{
		$rs = $this->db->where('user_id', $user_id)->where('status', 1)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


} //--- end class

 ?>
