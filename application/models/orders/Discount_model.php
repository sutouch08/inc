<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Discount_model extends CI_Model
{
	private $dp = "discount_policy";
	private $dr = "discount_rule";

  public function __construct()
  {
    parent::__construct();
  }


	public function get_discount($itemCode)
	{
		//---- ได้ส่วนลดที่ดีที่สุดมาแล้ว
		$d1 = 10;
		$d2 = 5;
		$d3 = 10;
		$price = $Price;

		$sc = array(
			'disc1' => $d1, //--- ข้อความที่ใช้แสดงส่วนลด เช่น 30%, 30
			'disc2' => $d2, //--- ข้อความที่ใช้แสดงส่วนลด เช่น 30%, 30
			'disc3' => $d3, //--- ข้อความที่ใช้แสดงส่วนลด เช่น 30%, 30
			'totalDiscAmount' => 0, //--- เอายอดส่วนลดที่ได้ มา คูณ ด้วย จำนวนสั่ง เป้นส่วนลดทั้งหมด
			'totalDiscPrecent' => 0, //-- แปลงส่วนลดทั้ง 3 มาเป็นส่วนลดเดียว
			'sellPrice' => $price //--- ราคา หลังส่วนลด
		); //-- end array

		if($d1 > 0 && $price > 0)
		{
			$amount = ($d1 * 0.01) * $price;
			$sc['totalDiscAmount'] += $amount;
			$price -= $amount;
			$sc['sellPrice'] = $price;

			if($d2 > 0)
			{
				$amount = ($d2 * 0.01) * $price;
				$sc['totalDiscAmount'] += $amount;
				$price  -= $amount;
				$sc['sellPrice'] = $price;

				if($d3 > 0)
				{
					$amount = ($d3 * 0.01) * $price;
					$sc['totalDiscAmount'] += $amount;
					$price  -= $amount;
					$sc['sellPrice'] = $price;
				}
			}
		}

		$sc['totalDiscPrecent'] = round(discountAmountToPercent($sc['totalDiscAmount'], 1, $price), 2);

		return (object) $sc;
	}

	private function qryGroup()
	{
		$i = 1;
		$qry = "";

		while($i <= 64)
		{
			$qry .= $i == 1 ? "QryGroup{$i}" : ", QryGroup{$i}";

			$i++;
		}

		return $qry;
	}

	public function getItemProperties($ItemCode)
	{
		$result = array();
		$qryGroup = $this->qryGroup();

		$ds = $this->ms->select($qryGroup)->where('ItemCode', $ItemCode)->get('OITM');

		if($ds->num_rows() === 1)
		{
			foreach($ds->row() as $key => $val)
			{
				if($val == 'Y')
				{
					$txt = $key;
					$txt = str_replace('QryGroup', '', $txt);
					$result[] = $txt;
				}
			}
		}

		return $result;
	}


	public function get_item_discount($ItemCode)
	{
		$disc = 0;
		$objKey = $this->getItemProperties($ItemCode);

		if( ! empty($objKey))
		{
			$rs = $this->ms
			->select('EDG1.*')
			->from('EDG1')
			->join('OEDG', 'EDG1.AbsEntry = OEDG.AbsEntry', 'left')
			->where('OEDG.Type', 'A')
			->where('OEDG.ValidFor', 'Y')
			->group_start()
			->where('OEDG.ValidForm <=', date('Y-m-d'))
			->where('OEDG.ValidTo >=', date('Y-m-d'))
			->or_where('OEDG.ValidForm IS NULL', NULL, FALSE)
			->or_where('OEDG.ValidTo IS NULL', NULL, FALSE)
			->group_end()
			->where('EDG1.ObjType', 8)
			->where_in('EDG1.ObjKey', $objKey)
			->get();

			if($rs->num_rows() > 0)
			{
				foreach($rs->result() as $rd)
				{
					if($rd->Discount > $disc)
					{
						$disc = $rd->Discount;
					}
				}
			}
		}

		return $disc;
	}


	public function getDiscountByManufacture($FirmCode)
	{
		$rs = $this->ms
		->select('EDG1.*')
		->from('EDG1')
		->join('OEDG', 'EDG1.AbsEntry = OEDG.AbsEntry', 'left')
		->where('OEDG.Type', 'A')
		->where('OEDG.ValidFor', 'Y')
		->group_start()
		->where('OEDG.ValidForm <=', date('Y-m-d'))
		->where('OEDG.ValidTo >=', date('Y-m-d'))
		->or_where('OEDG.ValidForm IS NULL', NULL, FALSE)
		->or_where('OEDG.ValidTo IS NULL', NULL, FALSE)
		->group_end()
		->where('EDG1.ObjType', 43)
		->where_in('EDG1.ObjKey', $FirmCode)
		->get();

		if($rs->num_rows() === 1)
		{
			return $rs->row()->Discount;
		}

		return NULL;
	}



} //--- end class
?>
