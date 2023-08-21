<style>

  @media (min-width: 200px) and (max-width: 767px){
    .cancleWatermark {
      font-size:80px !important;
    }
  }

  @media (min-width: 768px) and (max-width: 991px){
    .cancleWatermark {
      font-size:100px !important;
    }
  }

  @media (min-width: 992px) and (max-width: 1199px){
    .cancleWatermark {
      font-size:120px !important;
    }
  }

  @media (min-width: 1200px) {
    .cancleWatermark {
      font-size:150px !important;
    }
  }

</style>
<?php
$this->load->helper('print');
$footer_address = FALSE; //--- แสดงที่อยู่ท้ายแผ่นหรือไม่
$row_per_page = 21; //--- จำนวนบรรทัด/หน้า
$total_row 	= 0;
$row_text = 50;
$all_row = count($details);

foreach($details as $rs)
{
	$Description = $rs->type == 0 ? $rs->ItemName : $rs->LineText;
	$model = mb_strlen($Description);
	$newline = ceil(substr_count($Description, "\n") * 0.5);
	$text_length = $model;

	$u_row = $text_length > $row_text ? ceil($text_length/$row_text) : 1;
	$u_row = $u_row > $newline ? $u_row : $newline;
	$total_row += $u_row;
}


$total_row 	= $total_row == 0 ? 1 : ($total_row < $all_row ? $all_row : $total_row);
$name = empty($doc->DocNum) ? $doc->code : $doc->DocNum;

$config = array(
	"logo_position" => "middle",
	"title_position" => "center",
	"row" => $row_per_page,
	"total_row" => $total_row,
	"row_height" => 5,
	"sub_total_row" => 0,
	"font_size" => 14,
	"total_page" => ceil($total_row/$row_per_page),
	"show_footer" => TRUE,
  "footer_row" => 0
);

$this->printer->config($config);

$page  = '';
$page .= $this->printer->doc_header($name);

$tax_rate = getConfig('SALE_VAT_RATE');

$logo_path = base_url()."images/form-logo.png";


//**************  กำหนดหัวตาราง  ******************************//
$thead	= array(
          array("ลำดับที่<br/>No.", "font-size:10px; width:10mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
          array("ITEM", "font-size:10px; width:20mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555; vertical-align:middle;"),
          array("รหัสสินค้า<br/>Model", "font-size:10px; width:30mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
					array("รายละเอียด<br/>Description", "font-size:10px; width:55mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
          array("จำนวน<br/>QTY", "font-size:10px; width:15mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
					array("ราคา/หน่วย<br/>Unit Price", "font-size:10px; width:15mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
					array("ส่วนลด<br/>Disc(%)", "font-size:10px; width:12mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
          array("ราคาสุทธิ<br/>Net Price", "font-size:10px; width:15mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;"),
          array("จำนวนเงิน<br/>Amount", "font-size:10px; width:20mm; text-align:center; padding:3px 0px 3px 0px; border:solid 1px #555555;")
          );

$this->printer->add_subheader($thead);


//***************************** กำหนด css ของ td *****************************//
$pattern = array(
            "font-size:10px; vertical-align:text-top; text-align:center; padding:3px; border:solid 1px #555555; min-height:5mm;", //-- ลำดับ
            "font-size:10px; vertical-align:text-top; text-align:left; padding:3px; border:solid 1px #555555; min-height:5mm;",  //--- Item code
            "font-size:10px; vertical-align:text-top; text-align:left; padding:3px; border:solid 1px #555555; min-height:5mm;",  //--- Item name
            "font-size:10px; vertical-align:text-top; text-align:left; padding:3px; border:solid 1px #555555; min-height:5mm; white-space:pre-wrap;", //--- Description
            "font-size:10px; vertical-align:text-top; text-align:center; padding:3px; border:solid 1px #555555; min-height:5mm;", //--- จำนวน
						"font-size:10px; vertical-align:text-top; text-align:right; padding:3px; border:solid 1px #555555; min-height:5mm;", //---- หน่วยละ
            "font-size:10px; vertical-align:text-top; text-align:center; padding:3px; border:solid 1px #555555; min-height:5mm;", //--- ส่วนลด
            "font-size:10px; vertical-align:text-top; text-align:right; padding:3px; border:solid 1px #555555; min-height:5mm;", //--- Net price
						"font-size:10px; vertical-align:text-top; text-align:right; padding:3px; border:solid 1px #555555; min-height:5mm;" //--- จำนวนเงิน
            );

$this->printer->set_pattern($pattern);


//*******************************  กำหนดช่องเซ็นของ footer *******************************//
$footer = "";
$footer .="<div style='width:70mm; height:40mm; float:left; border-right:solid 1px #555555; padding:5px;'>";
$footer .= '<table style="width:100%; margin-top:5px; font-size:10px;">
							<tr><td colspan="2" class="text-center">ผู้เสนอราคา<br/>Issue By</td></tr>
							<tr>
                <td style="width:50%; text-align:center; height:10mm;"><img src="noimage.png" height="10mm;" style="border:solid 1px #ccc;"/></td>
                <td style="width:50%; text-align:center; height:10mm;"><img src="noimage.png" height="10mm;" style="border:solid 1px #ccc;"/></td>
              </tr>
              <tr>
                <td style="width:50%; text-align:center;">SUPPORT SALES</td>
                <td style="width:50%; text-align:center;">'.$sale->name.'</td>
              </tr>
              <tr>
                <td style="width:50%; text-align:center;">'.$doc->OwnerName.'</td>
                <td style="width:50%; text-align:center;">'.$sale->position.'</td>
              </tr>
              <tr>
                <td style="width:50%; text-align:center;">#tel</td>
                <td style="width:50%; text-align:center;">'.$sale->phone.'</td>
              </tr>
							<tr>
								<td colspan="2" style="text-align:center;"><br/>Date ........../........../..........</td>
							</tr>
						</table>';
$footer .="</div>";

$footer .="<div style='width:69mm; height:40mm; float:left; border-right:solid 1px #555555; padding:5px;'>";
$footer .= '<table style="width:100%; margin-top:5px; font-size:10px;">
							<tr><td class="text-center" style="font-weight:smaller;">ITTHIRIT NICE CORPORATION PUBLIC COMPANY LIMITED<br/>Approve By</td></tr>
							<tr>
                <td style="text-align:center; height:10mm;"><img src="noimage.png" height="10mm;" style="border:solid 1px #ccc;"/></td>
              </tr>
              <tr>
                <td><br/><br/><br/></td>
              </tr>
							<tr>
								<td style="text-align:center;"><br/>Date ........../........../..........</td>
							</tr>
						</table>';
$footer .="</div>";
$footer .="<div style='width:50mm; height:40mm; float:left; padding:5px;'>";
$footer .= '<table style="width:100%; margin-top:5px; font-size:10px;">
							<tr><td class="text-center">อนุมิตัสั่งซื้อตามใบเสนอราคานี้<br/>Purchase approved with this quotation</td></tr>
							<tr>
                <td style="text-align:center; height:10mm;"><img src="noimage.png" height="10mm;" style="border:solid 1px #ccc;"/></td>
              </tr>
              <tr>
                <td><br/><br/><br/></td>
              </tr>
              <tr>
								<td style="text-align:center;"><br/>Date ........../........../..........</td>
							</tr>
						</table>';
$footer .="</div>";
$footer .= "<div style='width:190mm; height:5mm; margin:auto; border:none; padding:5px; text-align:right; font-size:10px;'>Print Date : &nbsp; ".date('d/m/Y')." &nbsp;&nbsp; Print Time : ".date('H:i:s')."</div>";





$this->printer->footer = $footer;

$total_page  = $this->printer->total_page == 0 ? 1 : $this->printer->total_page;
$total_price = 0;
$total_amount = 0;  //--- มูลค่ารวม(หลังหักส่วนลด)
$total_discount = 0;
$total_vat = 0;

$n = 1;
$index = 0;
while($total_page > 0 )
{
	$top = "";
	$top .= "<div style='width:190mm; margin:auto;'>";
		$top .= "<div class='text-left' style='padding-top:20px; padding-bottom:0px;'>";
			$top .= "<table class='width-100'>
								<tr>
									<td style='width:30mm; padding-top:5px;'>
										<img src='{$logo_path}' class='company-logo' width='100%' />
									</td>
									<td style='width:140mm; font-size:10px; padding:0px 0px 0px 5px; text-align:center'>
                    <span class='display-block margin-bottom-10' style='font-size:16px;'><strong>{$company->name}</strong></span>
                    <span class='display-block margin-bottom-5'>{$company->address1} {$company->address2} {$company->postcode}</span>
                    <span class='display-block margin-bottom-5'>TEL &nbsp;&nbsp; {$company->phone}&nbsp;&nbsp; FAX {$company->fax}&nbsp;&nbsp; TAX ID : {$company->taxId}</span>
                    <span class='display-block margin-bottom-5'>{$company->website} / FACEBOOK : {$company->facebook} / LINE@ : {$company->line}</span>
                  </td>
                  <td style='width:20mm; font-size:14; text-align:right; vertical-align:text-top;'>Page {$this->printer->current_page} of {$this->printer->total_page}</td>
								</tr>
								<tr>
                  <td colspan='3' style='font-size:18px; padding:5px 0px 5px 0px; text-align:center; border-top:solid 1px #000; border-bottom:solid 1px #000;' >
                    <strong>ใบเสนอราคา Sale Quotation</strong>
                  </td>
                </tr>
								</table>";
		$top .= "</div>";
	$top .= "</div>";

	$top .= "<div style='width:190mm; position:relative; margin:auto; padding-top:5px; border-radius:0px;'>";
	$top .= 	"<div style='width:110mm; float:left; padding:5px 10px 5px 10px; height:34mm; max-height:34mm;'>";
	$top .= 		"<table style='border:none; font-size:12px;'>";
	$top .= 			"<tr>";
	$top .= 				"<td style='width:14mm; vertical-align:text-top; padding-top:0px;'>Name</td>";
	$top .=					"<td style='white-space:pre-wrap; vertical-align:text-top; padding-top:0px;'>: {$doc->CardName}</td>";
	$top .= 			"</tr>";
	$top .= 			"<tr>";
	$top .= 				"<td style='vertical-align:text-top; padding-top:0px;'>Address</td>";
	$top .=					"<td style='white-space:pre-wrap; vertical-align:text-top; padding-top:0px;'>: {$doc->Address}</td>";
	$top .= 			"</tr>";
	$top .= 			"<tr>";
	$top .= 				"<td style='vertical-align:text-top; padding-top:0px;'>Email</td>";
	$top .= 				"<td style=''>: {$customer->E_Mail}</td>";
	$top .= 			"</tr>";
  $top .= 			"<tr>";
  $top .= 				"<td style='vertical-align:text-top; padding-top:0px;'>Attn</td>";
  $top .= 				"<td style=''>: {$doc->ContactPerson}</td>";
  $top .= 			"</tr>";
  $top .= 			"<tr>";
  $top .= 				"<td style='vertical-align:text-top; padding-top:0px;'>Attn</td>";
  $top .= 				"<td style=''>: </td>";
  $top .= 			"</tr>";
	$top .= 			"<tr>";
	$top .= 				"<td style='vertical-align:text-top; padding-top:0px;'>Project</td>";
	$top .= 				"<td style='white-space:pre-wrap; vertical-align:text-top; padding-top:0px;'>: </td>";
	$top .= 			"</tr>";
	$top .= 		"</table>";
	$top .= 	"</div>";

	//$top .=  	"<div style='width:1mm; float:left;'>&nbsp;</div>";
	$top .= 	"<div style='width:57mm; float:left; padding:5px 10px 5px 10px; height:34mm; max-height:34mm;'>";
	$top .= 		"<table style='table-layout:fixed; width:100%; border:none; font-size:12px;'>";

  $top .= 			"<tr>";
	$top .=					"<td style='width:20mm; white-space:normal; padding-top:0px;'>Quotation No</td>";
	$top .=					"<td style='white-space:normal; padding-top:0px;'>: ".(empty($doc->DocNum) ? $doc->code : $doc->DocNum)."</td>";
	$top .= 			"</tr>";

	$top .= 			"<tr>";
	$top .=					"<td style='width:30mm; white-space:normal;'>Date</td>";
	$top .=					"<td style='white-space:normal;'>: ".thai_date($doc->DocDate, FALSE, '/')."</td>";
	$top .= 			"</tr>";

  $top .= 			"<tr>";
	$top .=					"<td style='width:30mm; white-space:normal;'>Contack Sales</td>";
	$top .=					"<td style='white-space:normal;'>: {$sale->name}</td>";
	$top .= 			"</tr>";

  $top .= 			"<tr>";
	$top .=					"<td style='width:30mm; white-space:normal;'>ID LINE</td>";
	$top .=					"<td style='white-space:normal;'>: {$sale->id_line}</td>";
	$top .= 			"</tr>";

  $top .= 			"<tr>";
	$top .=					"<td style='width:30mm; white-space:normal;'>Mobile</td>";
	$top .=					"<td style='white-space:normal;'>: {$sale->phone}</td>";
	$top .= 			"</tr>";

  $top .= 			"<tr>";
	$top .=					"<td style='width:30mm; white-space:normal;'>Email</td>";
	$top .=					"<td style='white-space:normal;'>: {$sale->email}</td>";
	$top .= 			"</tr>";

	$top .= 		"</table>";
	$top .= 	"</div>";
	$top .= "</div>";



  $page .= $this->printer->page_start();
  $page .= $top;

	//$page .= ($doc->Status == 2 ? $cancleWatermark : "");

  $page .= $this->printer->content_start();
  $page .= $this->printer->table_start();
  $i = 0;

	$row = $this->printer->row;

	$last_row = FALSE;

  while($i < $row)
  {
    $rs = isset($details[$index]) ? $details[$index] : FALSE;

    if( ! empty($rs) )
    {
			$Description = $rs->Description;
			$model = mb_strlen($Description);
			$newline = ceil(substr_count($Description, "\n") * 0.5);
			$text_length = $model;

			$use_row = ceil($text_length/$row_text);
			$use_row = $use_row > $newline ? $use_row : $newline;

			if($use_row > 1)
			{
				//--- คำนวนบรรทัดที่ต้องใช้ต่อ 1 รายการ
				$use_row -= 1;
				$i += $use_row;
			}

      $data = array(
        $n,
        NULL,
        $rs->ItemName,
        $rs->Description,
        number($rs->Qty, 2),
        number($rs->Price,2),
        $rs->discLabel == 0 ? '0.00' : $rs->discLabel,
        number($rs->SellPrice, 2),
        number($rs->LineTotal, 2)
      );

      $row_price = ($rs->Price * $rs->Qty);
      $total_price += $row_price;
      $total_discount += $row_price - $rs->LineTotal;
      $total_amount   += $rs->LineTotal;
      $total_vat += $rs->LineTotal * ($rs->VatRate * 0.01);
      $n++;
    }
    else
    {
			$data = array("","&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;","&nbsp;");
    }

    $page .= empty($data) ? "" : $this->printer->print_row($data, $last_row);

		$index++;

		//--- check next row
		$nextrow = isset($details[$index]) ? $details[$index] : FALSE;

		if(!empty($nextrow))
		{
			$Description = $rs->Description;
			$model = mb_strlen($Description);
			$newline = ceil(substr_count($Description, "\n") * 0.5);
			$text_length = $model;
			$use_row = ceil($text_length/$row_text);
			$use_row = $use_row > $newline ? $use_row : $newline;

			$use_row += $i;

			if($row < $use_row)
			{
				if($i < $row)
				{
					$i++;
					$i++;
					$i++;
					while($i < $row)
					{
						$data = array("","&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;");
						$page .= $this->printer->print_row($data, $last_row);
						$i++;
					}
				}

				$i = $use_row;
				$last_row = TRUE;
			}
			else
			{
				$i++;
			}
		}
		else
		{
			$i++;
		}

		$total_row--;
  }

  if($this->printer->current_page == $this->printer->total_page)
  {
		$amountBfDisc = number($total_price, 2);
		$disAmount = number($total_discount, 2);
		$amountBfVat = number($total_amount,2);
		$vatAmount = number($total_vat, 2);
		$amountAfterVat = $total_amount * (1 + ($tax_rate * 0.01));
		$netAmount = number($amountAfterVat, 2);
		$baht_text = baht_text($amountAfterVat);
		$remark = $doc->Comments;
  }
  else
  {
		$amountBfDisc = "";
		$disAmount = "";
		$amountBfVat = "";
		$vatAmount = "";
		$amountAfterVat = "";
		$netAmount = "";
		$baht_text = "";
		$remark = "";
  }

  $subTotal = array();
  $fix_remark = "<u>Remark</u><br/>
                1.กำหนดยื่นราคา 30 วันนับจากวันเสนอราคา<br/>
                2.ชำระด้วยเงินสด/บัตรเครดิตธนาคารชาร์จ 2.5%/เงินโอนผ่านธนาคาร/เช็คธนาคาร<br/>
                3.ลูกค้าส่งหลักฐานการชำระเงินก่อนส่งมอบสินค้า<br/>
                4.เครดิตการค้าเป็นไปตามเงื่อนไขของบริษัทโดยผู้มีอำนาจลงนามเท่านั้น";

  //--- หมายเหต
  $page .= "<tr>";
	$page .= "<td colspan='9' style='font-size:12px; vertical-align:top; padding:5px; border:none; height:15mm; min-height:15mm;'>หมายเหตุ : {$doc->Comments}</td>";
	$page .= "</tr>";

  $page .= "<tr>";
  $page .= "<td colspan='4' style='font-size:10px; border:solid 1px #555555; vertical-align:top; padding:5px; '>{$fix_remark}</td>";
  $page .= "<td colspan='5' rowspan='2' style='padding:0; border:solid 1px #555555;'>";
  $page .=  "<table style='width:100%; margin-top:0; margin-bottom:0;'>";
  $page .=    "<tr>";
  $page .=      "<td style='width:60%; font-size:11px; padding:2px;'>จำนวนเงิน/Price</td>";
  $page .=      "<td style='font-size:11px; padding:2px;' class='text-right'>{$amountBfDisc}</td>";
  $page .=    "</tr>";
  $page .=    "<tr>";
  $page .=      "<td style='width:60%; font-size:11px; padding:2px;'>ส่วนลด/Discount &nbsp;&nbsp;{$doc->DiscPrcnt} %</td>";
  $page .=      "<td style='font-size:11px; padding:2px;' class='text-right'>{$disAmount}</td>";
  $page .=    "</tr>";
  $page .=    "<tr>";
  $page .=      "<td style='width:60%; font-size:11px; padding:2px;'>มูลค่าหลังหักส่วนลด/Net Price</td>";
  $page .=      "<td style='font-size:11px; padding:2px;' class='text-right'>{$amountBfVat}</td>";
  $page .=    "</tr>";
  $page .=    "<tr>";
  $page .=      "<td style='width:60%; font-size:11px; padding:2px;'>จำนวนภาษีมูลค่าเพิ่ม &nbsp; {$tax_rate} % </td>";
  $page .=      "<td style='font-size:11px; padding:2px;' class='text-right'>{$vatAmount}</td>";
  $page .=    "</tr>";
  $page .=    "<tr>";
  $page .=      "<td style='width:60%; font-size:11px; padding:2px;'>รวมเงินสุทธิ/Total Price</td>";
  $page .=      "<td style='font-size:11px; padding:2px;' class='text-right'>{$netAmount}</td>";
  $page .=    "</tr>";
  $page .=  "</table>";
  $page .= "</td>";
  $page .= "</tr>";
  $page .= "<tr>";
  $page .= "<td colspan='4' style='font-size:12px; vertical-align:middle; padding:5px; height:10mm; border:solid 1px #555555;'>จำนวนเงิน : {$baht_text}</td>";
  $page .= "</tr>";

  $page .= "<tr><td colspan='9' style='padding:0; border:solid 1px #555555;'>{$this->printer->footer}</td></tr>";

/*
	$page .= "<tr>";
	$page .= "<td rowspan='4' colspan='4' style='font-size:10px; border:solid 1px #555555; vertical-align:top; padding:5px; '>หมายเหตุ : {$fix_remark}</td>";
  $page .= "<td colspan='3' style='font-size:11px; border-top:solid 1px #555555; border-left:solid 1px #555555; padding:2px;'>จำนวนเงิน/Price</td>";
  $page .= "<td colspan='2' style='font-size:11px; border-top:solid 1px #555555; border-right:solid 1px #555555; padding:2px;' class='text-right'>{$amountBfDisc}</td>";
	$page .= "</tr>";

	$page .= "<tr>";
  $page .= "<td colspan='3' style='font-size:11px; border-left:solid 1px #555555; padding:2px;'>ส่วนลด/Discount &nbsp;&nbsp;{$doc->DiscPrcnt} %</td>";
  $page .= "<td colspan='2' style='font-size:11px; border-right:solid 1px #555555; padding:2px;' class='text-right'>{$disAmount}</td>";
	$page .= "</tr>";

	$page .= "<tr>";
  $page .= "<td colspan='3' style='font-size:11px; border-left:solid 1px #555555; padding:2px;'>มูลค่าหลังหักส่วนลด/Net Price</td>";
  $page .= "<td colspan='2' style='font-size:11px; border-right:solid 1px #555555; padding:2px;' class='text-right'>{$amountBfVat}</td>";
	$page .= "</tr>";

  $page .= "<td colspan='3' style='font-size:11px; border-left:solid 1px #555555; padding:2px;'>จำนวนภาษีมูลค่าเพิ่ม &nbsp; 7.00%</td>";
  $page .= "<td colspan='2' style='font-size:11px; border-right:solid 1px #555555; padding:2px;' class='text-right'>{$vatAmount}</td>";
	$page .= "</tr>";

	$page .= "<tr>";
  $page .= "<td colspan='4' style='font-size:12px; vertical-align:middle; padding:5px; height:10mm;'>จำนวนเงิน : {$baht_text}</td>";
  $page .= "<td colspan='3' style='font-size:11px; border-left:solid 1px #555555; padding:2px;'>รวมเงินสุทธิ/Total Price</td>";
  $page .= "<td colspan='2' style='font-size:11px; border-right:solid 1px #555555; padding:2px;' class='text-right'>{$netAmount}</td>";
	$page .= "</tr>";
  */

	$page .= $this->printer->table_end();

  $page .= $this->printer->content_end();

  // if($this->printer->show_footer)
  // {
  //   $page .= $this->printer->footer;
  // }

  $page .= $this->printer->page_end($footer_address);

  $total_page --;
  $this->printer->current_page++;
}

$page .= $this->printer->doc_footer();

echo $page;

 ?>

 <style type="text/css" media="print">
 	@page{
 		margin:0;
 		size:A4 portrait;
 	}
  </style>
