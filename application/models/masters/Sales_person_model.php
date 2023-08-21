<?php
class Sales_person_model extends CI_Model
{
	private $tb = "OSLP";

	public function __construct()
	{
		parent::__construct();
	}


	public function get($id)
	{
		$rs = $this->ms
		->select('SlpCode AS id, SlpName AS name, EmpID AS emp_id, Active AS active')
		->select('Mobil AS phone, Email AS email, U_Name_Eng AS name_en')
		->select('Telephone AS id_line, U_Positions AS position')
		->where('SlpCode', $id)
		->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_name($id)
	{
		$rs = $this->ms
		->select('SlpName AS name')
		->where('SlpCode', $id)
		->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->name;
		}

		return NULL;
	}


	public function get_all()
	{
		$rs = $this->ms
		->select('SlpCode AS id, SlpName AS name, EmpID AS emp_id, Active AS active')
		->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_all_active()
	{
		$rs = $this->ms
		->select('SlpCode AS id, SlpName AS name, EmpID AS emp_id, Active AS active')
		->where('Active', 'Y')
		->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

} //--- end class

 ?>
