<?php
class Items extends CI_Controller
{
  public $ms;
  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE);
    $this->load->model('masters/products_model');
    $this->load->model('orders/discount_model');
    $this->load->helper('discount');
    $this->load->helper('order');
  }

  public function get_item_data()
	{
		$sc = TRUE;
		$itemCode = $this->input->get('ItemCode');
		$cardCode = $this->input->get('CardCode');
		$priceList = $this->input->get('PriceList');
		$docDate = db_date($this->input->get('DocDate'));
		$payment = $this->input->get('Payment');
		$qty = 1;

		$pd = $this->products_model->get($itemCode, $priceList);

		if(! empty($pd))
		{
      $childs = NULL;

      if($pd->TreeType == 'S')
      {
        $childs = $this->products_model->get_childs($pd->code);
      }

			$price = empty($childs) ? $pd->price : 0.00;

      $cost = empty($childs) ? $pd->cost : 0.00;

			$disc = empty($childs) ? $this->discount_model->getDiscountByManufacture($pd->FirmCode) : 0.00;

			if(empty($disc))
			{
				$disc = empty($childs) ? $this->discount_model->get_item_discount($itemCode) : 0.00;
			}

			$disAmount = empty($childs) ? ($disc * 0.01) * $price : 0.00;
			$sellPrice = empty($childs) ? $price - $disAmount : 0;
			$stock = empty($childs) ? $this->products_model->getItemStock($pd->code, $pd->dfWhsCode) : NULL;
      $uid = genUid();
      $dummy = $pd->code == 'FG-Dummy' ? 1 : 0;

			$ds = array(
        'uid' => $uid,
        'dummy' => $dummy,
				'ItemCode' => $pd->code,
				'ItemName' => $pd->name,
        'Description' => $pd->description,
				'dfWhsCode' => $pd->dfWhsCode,
				'OnHand' => empty($stock) ? number($pd->OnHand) : number($stock->OnHand),
				'Commited' => empty($stock) ? number($pd->IsCommited) : number($stock->IsCommited),
				'OnOrder' => empty($stock) ? number($pd->OnOrder) : number($stock->OnOrder),
				'Qty' => $qty,
        'UomEntry' => $pd->uom_id,
				'UomCode' => $pd->uom_code,
				'UomName' => $pd->uom,
        'Cost' => $cost,
				'Price' => $price, //--- ราคาตาม price list
				'SellPrice' => $sellPrice, //--- ราคาหลังส่วนลด
				'sysDiscLabel' => discountLabel($disc),
				'disc1' => round($disc, 2),
				'disc2' => NULL,
				'disc3' => NULL,
				'DiscPrcnt' => $disc,
				'discAmount' => $disAmount,
				'totalDiscAmount' => $disAmount * $qty,
				'VatGroup' => $pd->vat_group,
				'VatRate' => $pd->vat_rate,
				'VatAmount' => get_vat_amount($sellPrice, $pd->vat_rate),
				'TotalVatAmount' => (get_vat_amount($sellPrice, $pd->vat_rate) * $qty),
				'LineTotal' => ($sellPrice * $qty),
        'TreeType' => $pd->TreeType,
        'father_uid' => NULL,
        'father_code' => NULL,
        'childs' => array()
			);

      if( ! empty($childs))
      {
        foreach($childs as $rs)
        {
          $item = $this->products_model->get($rs->code, $priceList);

          if( ! empty($item))
          {
            $price = $item->price;
            $cost = $item->cost;

      			$disc = $this->discount_model->getDiscountByManufacture($item->FirmCode);

      			if(empty($disc))
      			{
      				$disc = $this->discount_model->get_item_discount($item->code);
      			}

            //$disc = 0.00;

      			$disAmount = ($disc * 0.01) * $price;
      			$sellPrice = $price - $disAmount;
      			$stock = $this->products_model->getItemStock($item->code, $item->dfWhsCode);

            $arr = array(
              'uid' => genUid(),
              'ItemCode' => $item->code,
      				'ItemName' => $item->name,
              'Description' => $item->description,
      				'dfWhsCode' => $item->dfWhsCode,
      				'OnHand' => empty($stock) ? number($item->OnHand) : number($stock->OnHand),
      				'Commited' => empty($stock) ? number($item->IsCommited) : number($stock->IsCommited),
      				'OnOrder' => empty($stock) ? number($item->OnOrder) : number($stock->OnOrder),
      				'Qty' => $rs->qty,
              'UomEntry' => $item->uom_id,
      				'UomCode' => $item->uom_code,
      				'UomName' => $item->uom,
              'Cost' => $cost,
      				'Price' => $price, //--- ราคาตาม price list
      				'SellPrice' => $sellPrice, //--- ราคาหลังส่วนลด
      				'sysDiscLabel' => discountLabel($disc),
      				'disc1' => round($disc, 2),
      				'disc2' => NULL,
      				'disc3' => NULL,
      				'DiscPrcnt' => $disc,
      				'discAmount' => $disAmount,
      				'totalDiscAmount' => $disAmount * $rs->qty,
      				'VatGroup' => $item->vat_group,
      				'VatRate' => $item->vat_rate,
      				'VatAmount' => get_vat_amount($sellPrice, $item->vat_rate),
      				'TotalVatAmount' => (get_vat_amount($sellPrice, $item->vat_rate) * $rs->qty),
      				'LineTotal' => ($sellPrice * $rs->qty),
              'TreeType' => 'I',
              'father_uid' => $uid,
              'father_code' => $pd->code
            );

            array_push($ds['childs'], $arr);
          }
        }
      }
		}
		else
		{
			$sc = FALSE;
			$this->error = "Item Not found";
		}

		echo $sc === TRUE ? json_encode($ds) : $this->error;
	}


	public function get_stock()
	{
		$ItemCode = $this->input->get('ItemCode');
		$WhsCode = $this->input->get('WhsCode');

		$stock = $this->products_model->getItemStock($ItemCode, $WhsCode);

		$arr = array(
			'ItemCode' => $ItemCode,
			'WhsCode' => $WhsCode,
			'OnHand' => (empty($stock) ? 0 : number($stock->OnHand)),
			'Commited' => (empty($stock) ? 0 : number($stock->IsCommited)),
			'OnOrder' => (empty($stock) ? 0 : number($stock->OnOrder))
		);

		echo json_encode($arr);
	}
}


 ?>
