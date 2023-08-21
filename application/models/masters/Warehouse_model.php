<?php
class Warehouse_model extends CI_Model
{
	private $tb = "OWHS";

  public function __construct()
  {
    parent::__construct();
  }


	public function get($code)
	{
		$rs = $this->ms
		->select('WhsCode, WhsName')
		->where('WhsCode', $code)
		->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_name($code)
	{
		$rs = $this->ms->select('WhsName')->where('WhsCode', $code)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->WhsName;
		}

		return NULL;
	}

	public function get_all()
	{
		$rs = $this->ms->select('WhsCode AS code, WhsName AS name')->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}
}
?>
