<?php
class Bp_order_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    $customer_code = $this->_customer === TRUE ? $this->_user->customer_code : NULL;

    if(!empty($customer_code))
    {
      $this->db
      ->select('od.*, pm.term')
      ->from('orders AS od')
      ->join('payment_term AS pm', 'od.Payment = pm.id', 'left')
      ->where('role', 'C')->where('CardCode', $customer_code);

      if(isset($ds['code']) && $ds['code'] != '')
      {
        $this->db->like('code', $ds['code']);
      }

      if(!empty($ds['from_date']) && !empty($ds['to_date']))
      {
        $this->db
        ->where('DocDate >=', from_date($ds['from_date']))
        ->where('DocDate <=', to_date($ds['to_date']));
      }

      if(isset($ds['status']) && $ds['status'] != 'all')
      {
        $this->db->where('so_status', $ds['status']);
      }

      $rs = $this->db->order_by('code', 'DESC')->limit($perpage, $offset)->get();

      if($rs->num_rows() > 0)
      {
        return $rs->result();
      }
    }

    return NULL;
  }



  public function count_rows(array $ds = array())
  {
    $customer_code = $this->_customer === TRUE ? $this->_user->customer_code : NULL;

    if(!empty($customer_code))
    {
      $this->db->where('role', 'C')->where('CardCode', $customer_code);

      if(isset($ds['code']) && $ds['code'] != '')
      {
        $this->db->like('code', $ds['code']);
      }

      if(!empty($ds['from_date']) && !empty($ds['to_date']))
      {
        $this->db
        ->where('DocDate >=', from_date($ds['from_date']))
        ->where('DocDate <=', to_date($ds['to_date']));
      }

      if(isset($ds['status']) && $ds['status'] != 'all')
      {
        $this->db->where('so_status', $ds['status']);
      }

      return $this->db->count_all_results('orders');
    }

    return 0;
  }
}


 ?>
