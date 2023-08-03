<?php
class Approver_model extends CI_Model
{
	public $tb = "approver";

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



	public function add_team(array $ds = array())
	{
		return $this->db->insert('approver_team', $ds);
	}


	public function add_brand(array $ds = array())
	{
		return $this->db->insert('approver_brand', $ds);
	}


	public function drop_team($id_approver)
	{
		return $this->db->where('id_approver', $id_approver)->delete("approver_team");
	}


	public function drop_brand($id_approver)
	{
		return $this->db->where('id_approver', $id_approver)->delete("approver_brand");
	}


	public function update($id, array $ds = array())
	{
		if(!empty($ds))
		{
			return $this->db->where('id', $id)->update($this->tb, $ds);
		}

		return FALSE;
	}



	public function delete($id)
	{
		return $this->db->where('id', $id)->delete($this->tb);
	}


	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		$this->db
		->distinct()
		->select('a.*, u.uname, u.name')
		->from('approver AS a')
		->join('user AS u', 'a.user_id = u.id', 'left')
		->join('approver_brand AS ab', 'a.id = ab.id_approver', 'left')
		->join('approver_team AS at', 'a.id = at.id_approver', 'left');

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

		if(isset($ds['team']) && $ds['team'] != 'all')
		{
			$this->db->where('at.id_team', $ds['team']);
		}

		if(isset($ds['brand']) && $ds['brand'] != 'all')
		{
			$this->db->where('ab.id_brand', $ds['brand']);
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
		->distinct()
		->select('a.id')
		->from('approver AS a')
		->join('approver AS a2', 'a.id = a2.id', 'left')
		->join('user AS u', 'a.user_id = u.id', 'left')
		->join('approver_brand AS ab', 'a.id = ab.id_approver', 'left')
		->join('approver_team AS at', 'a2.id = at.id_approver', 'left');

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

		if(isset($ds['team']) && $ds['team'] != 'all')
		{
			$this->db->where('at.id_team', $ds['team']);
		}

		if(isset($ds['brand']) && $ds['brand'] != 'all')
		{
			$this->db->where('ab.id_brand', $ds['brand']);
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


	public function is_approver($user_id, $team_id)
	{
		$rs = $this->db
		->distinct()
		->select('a.id')
		->from('approver AS a')
		->join('approver_team AS at', 'a.id = at.id_approver', 'left')
		->where('a.user_id', $user_id)
		->where('at.id_team', $team_id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->row()->id;
		}

		return FALSE;
	}



	public function get_approver_team($id)
	{
		$rs = $this->db->where('id_approver', $id)->get('approver_team');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_approver_brand($id)
	{
		$rs = $this->db
		->select('ab.*')
		->select('pb.name')
		->from('approver_brand AS ab')
		->join('product_brand AS pb', 'ab.id_brand = pb.id', 'left')
		->where('ab.id_approver', $id)
		->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

} //--- end class

 ?>
