<?php
class User_model extends CI_Model
{
	private $tb = "user";

  public function __construct()
  {
    parent::__construct();
  }



  public function add(array $data = array())
  {
    if(!empty($data))
    {
      $rs = $this->db->insert($this->tb, $data);

			if($rs)
			{
				return $this->db->insert_id();
			}
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



  public function delete($id)
  {
    return $this->db->where('id', $id)->delete($this->tb);
  }



  public function get($id)
  {
		$this->db
		->select('u.*, u.name AS display_name, p.name AS profile_name')
		->select('g.name AS group_name')
		->select('t.name AS team_name')
		->from('user AS u')
		->join('user_group AS g', 'u.group_id = g.id', 'left')
		->join('sale_team AS t', 'u.team_id = t.id', 'left')
		->join('profile AS p', 'u.id_profile = p.id', 'left');

    $rs = $this->db->where('u.id', $id)->get();

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }


  public function get_user_by_uid($uid)
  {
    $rs = $this->db->where('uid', $uid)->get($this->tb);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }


  public function get_by_uname($uname)
  {
		$this->db
		->select('u.*, u.name AS display_name, p.name AS profile_name')
		->select('g.name AS group_name')
		->select('t.name AS team_name')
		->from('user AS u')
		->join('user_group AS g', 'u.group_id = g.id', 'left')
		->join('sale_team AS t', 'u.team_id = t.id', 'left')
		->join('profile AS p', 'u.id_profile = p.id', 'left');

    $rs = $this->db->where('u.uname', $uname)->get();

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }



  public function get_name($uname)
  {
    $rs = $this->db->where('uname', $uname)->get($this->tb);
    if($rs->num_rows() == 1)
    {
      return $rs->row()->name;
    }

    return NULL;
  }

	public function get_uname($id)
	{
		$rs = $this->db->where('id', $id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->uname;
		}

		return NULL;
	}



	public function get_all()
	{
		$rs = $this->db->where('id >', 0, FALSE)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_all_active()
	{
		$rs = $this->db->where('id >', 0, FALSE)->where('active', 1)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function get_all_group()
	{
		$rs = $this->db->get('user_group');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}



	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		$this->db
		->select('u.*, u.name AS display_name, p.name AS profile_name')
		->select('g.name AS group_name, t.name AS team_name')
		->from('user AS u')
		->join('user_group AS g', 'u.group_id = g.id', 'left')
		->join('sale_team AS t', 'u.team_id = t.id', 'left')
		->join('profile AS p', 'u.id_profile = p.id', 'left')
    ->where('id_profile >', 0, FALSE);

		if( ! empty($ds['uname']))
		{
			$this->db->group_start()->like('u.uname', $ds['uname'])->or_like('u.name', $ds['uname'])->group_end();
		}

		if( ! empty($ds['group_id']) && $ds['group_id'] != 'all')
		{
			$this->db->where('u.group_id', $ds['group_id']);
		}

		if( ! empty($ds['sale_id']) && $ds['sale_id'] != 'all')
		{
			$this->db->where('sale_id', $ds['sale_id']);
		}

		if( ! empty($ds['emp_id']) && $ds['emp_id'] != 'all')
		{
			if($ds['emp_id'] == "null")
			{
				$this->db->where('emp_id IS NULL', NULL, FALSE);
			}
			else
			{
				$this->db->where('emp_id', $ds['emp_id']);
			}
		}

		if( ! empty($ds['team_id']) && $ds['team_id'] != 'all')
		{
			$this->db->where('u.team_id', $ds['team_id']);
		}

		if(isset($ds['profile_id']) && $ds['profile_id'] != 'all')
		{
			$this->db->where('u.id_profile', $ds['profile_id']);
		}

		if(isset($ds['active']) && $ds['active'] != 'all')
		{
			$this->db->where('u.active', $ds['active']);
		}

		$rs = $this->db->order_by('u.id', 'DESC')->limit($perpage, $offset)->get();

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}
	}




  public function count_rows(array $ds = array())
  {
		$this->db->where('id_profile >', 0, FALSE);

		if( ! empty($ds['uname']))
		{
			$this->db->group_start()->like('u.uname', $ds['uname'])->or_like('u.name', $ds['uname'])->group_end();
		}

		if( ! empty($ds['group_id']) && $ds['group_id'] != 'all')
		{
			$this->db->where('group_id', $ds['group_id']);
		}

		if( ! empty($ds['sale_id']) && $ds['sale_id'] != 'all')
		{
			$this->db->where('sale_id', $ds['sale_id']);
		}

		if( ! empty($ds['emp_id']) && $ds['emp_id'] != 'all')
		{
			if($ds['emp_id'] == "null")
			{
				$this->db->where('emp_id IS NULL', NULL, FALSE);
			}
			else
			{
				$this->db->where('emp_id', $ds['emp_id']);
			}
		}

		if( ! empty($ds['team_id']) && $ds['team_id'] != 'all')
		{
			$this->db->where('team_id', $ds['team_id']);
		}

		if(isset($ds['profile_id']) && $ds['profile_id'] != 'all')
		{
			$this->db->where('id_profile', $ds['profile_id']);
		}

		if(isset($ds['active']) && $ds['active'] != 'all')
		{
			$this->db->where('active', $ds['active']);
		}

    return $this->db->count_all_results($this->tb);
  }



  public function get_permission($menu, $id_profile)
  {
    if(!empty($menu))
    {
      $rs = $this->db->where('code', $menu)->get('menu');

      if($rs->num_rows() === 1)
      {
        if($rs->row()->valid == 1)
        {
          return $this->get_profile_permission($menu, $id_profile);
        }
        else
        {
          $ds = new stdClass();
          $ds->can_view = 1;
          $ds->can_add = 1;
          $ds->can_edit = 1;
          $ds->can_delete = 1;
          $ds->can_approve = 1;
          return $ds;
        }
      }
    }

    return FALSE;
  }



  private function get_profile_permission($menu, $id_profile)
  {
    $rs = $this->db
		->where('menu', $menu)
		->where('id_profile', $id_profile)
		->get('permission');

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return FALSE;
  }



  public function is_exists_uname($uname, $id = NULL)
  {
    if( ! empty($id))
    {
      $this->db->where('id !=', $id);
    }

    $rs = $this->db->where('uname', $uname)->count_all_results($this->tb);

    if($rs > 0)
    {
      return TRUE;
    }

    return FALSE;
  }



  public function is_exists_display_name($dname, $id = NULL)
  {
    if( ! empty($id))
    {
      $this->db->where('id !=', $id);
    }

    $rs = $this->db->where('name', $dname)->count_all_results($this->tb);

    if($rs > 0)
    {
      return TRUE;
    }

    return FALSE;
  }



  public function get_user_credentials($uname)
  {
    $this->db->where('uname', $uname);
    $rs = $this->db->get($this->tb);
    return $rs->row();
  }



  public function verify_uid($uid)
  {
    $this->db->select('uid');
    $this->db->where('uid', $uid);
    $this->db->where('active', 1);
    $rs = $this->db->get($this->tb);

    return $rs->num_rows() === 1 ? TRUE : FALSE;
  }



	public function has_transection($id)
	{
		$sq = $this->db->where('user_id', $id)->or_where('upd_user_id', $id)->count_all_results('quotation');
		$so = 0; //$this->db->where('user_id', $id)->or_where('upd_user_id', $id)->count_all_results('orders');
		$apv = 0; //$this->db->where('user_id', $id)->count_all_results('approver');

		$total = $so + $sq + $apv;

		return $total > 0 ? TRUE : FALSE;
	}


	public function add_logs(array $ds = array())
	{
		return $this->db->insert('access_logs', $ds);
	}


	public function docList()
	{
		$rs = $this->db->get('doc_type');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

} //---- End class

 ?>
