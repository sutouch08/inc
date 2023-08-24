<?php
class Signature_model extends CI_Model
{
	private $tb = "signature";

	public function __construct()
	{
		parent::__construct();
	}


  public function get($emp_id)
  {
    $rs = $this->db->where('emp_id', $emp_id)->get($this->tb);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


	public function get_signature($emp_id)
	{
		$rs = $this->db->select('image_data')->where('emp_id', $emp_id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->image_data;
		}

		return NULL;
	}


  public function is_exists($emp_id)
  {
    $rs = $this->db->where('emp_id', $emp_id)->count_all_results($this->tb);

    if($rs > 0)
    {
      return TRUE;
    }

    return FALSE;
  }


  public function add(array $ds = array())
  {
    if( ! empty($ds))
    {
      return $this->db->insert($this->tb, $ds);
    }

    return FALSE;
  }


  public function update($emp_id, array $ds = array())
  {
    if( ! empty($ds))
    {
      return $this->db->where('emp_id', $emp_id)->update($this->tb, $ds);
    }

    return FALSE;
  }


  public function delete($emp_id)
  {
    return $this->db->where('emp_id', $emp_id)->delete($this->tb);
  }


  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    if( isset($ds['name']) && $ds['name'] != "" )
    {
      $this->db->like('firstName', $ds['name'])->or_like('lastName', $ds['name']);
    }

    $rs = $this->db->order_by('firstName', 'ASC')->limit($perpage, $offset)->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function count_rows(array $ds = array())
  {
    if( isset($ds['name']) && $ds['name'] != "" )
    {
      $this->db->like('firstName', $ds['name'])->or_like('lastName', $ds['name']);
    }

    return $this->db->count_all_results($this->tb);
  }

} // end class
?>
