<?php
class Employee_model extends CI_Model
{
	private $tb = "OHEM";

	public function __construct()
	{
		parent::__construct();
	}


	public function get_all()
	{
		$rs = $this->ms
		->select('empID AS id, firstName, lastName, middleName, Active AS active')
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
		->select('empID AS id, firstName, lastName, middleName, Active AS active')
		->where('Active', 'Y')
		->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get($emp_id)
	{
		$rs = $this->ms
		->select('empID AS id, firstName, lastName, middleName, Active AS active, attachment, U_ISSUEDBY')
		->where('empID', $emp_id)
		->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_name($emp_id)
	{
		$rs = $this->ms->select('firstName AS name')->where('empID', $emp_id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->name;
		}

		return NULL;
	}


	public function get_full_name($emp_id)
	{
		$rs = $this->ms->select('firstName, lastName, middleName')->where('empID', $emp_id)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->firstName." ".$rs->row()->lastName;
		}

		return NULL;
	}


} //--- end class

 ?>
