<?php
class Logs_model extends CI_Model
{
  private $tb = "access_logs";

  public function __construct()
  {
    parent::__construct();
  }


  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    if( isset($ds['uname']) && $ds['uname'] != "")
    {
      $this->db->like('uname', $ds['uname']);
    }

    if( isset($ds['docNum']) && $ds['docNum'] != "")
    {
      $this->db->like('docNum', $ds['docNum']);
    }

    if( isset($ds['ip_address']) && $ds['ip_address'] != '')
    {
      $this->db->like('ip_address', $ds['ip_address']);
    }

    if( isset($ds['docType']) && $ds['docType'] != 'all')
    {
      $this->db->where('docType', $ds['docType']);
    }

    if( isset($ds['action']) && $ds['action'] != 'all')
    {
      $this->db->where('action', $ds['action']);
    }

    if( ! empty($ds['from_date']) && ! empty($ds['to_date']))
    {
      $this->db
      ->group_start()
      ->where('date_upd >=', from_date($ds['from_date']))
      ->where('date_upd <=', to_date($ds['to_date']))
      ->group_end();
    }

    $rs = $this->db->order_by('date_upd', 'DESC')->limit($perpage, $offset)->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function count_rows(array $ds = array())
  {
    if( isset($ds['uname']) && $ds['uname'] != "")
    {
      $this->db->like('uname', $ds['uname']);
    }

    if( isset($ds['docNum']) && $ds['docNum'] != "")
    {
      $this->db->like('docNum', $ds['docNum']);
    }

    if( isset($ds['ip_address']) && $ds['ip_address'] != '')
    {
      $this->db->like('ip_address', $ds['ip_address']);
    }

    if( isset($ds['docType']) && $ds['docType'] != 'all')
    {
      $this->db->where('docType', $ds['docType']);
    }

    if( isset($ds['action']) && $ds['action'] != 'all')
    {
      $this->db->where('action', $ds['action']);
    }

    if( ! empty($ds['from_date']) && ! empty($ds['to_date']))
    {
      $this->db
      ->group_start()
      ->where('date_upd >=', from_date($ds['from_date']))
      ->where('date_upd <=', to_date($ds['to_date']))
      ->group_end();
    }

    return $this->db->count_all_results($this->tb);
  }

}

 ?>
