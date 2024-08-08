function showSqModal() {
	load_in();
	$.ajax({
		url:HOME + 'get_quotation_list',
		type:'GET',
		cache:false,
		success:function(rs) {
			load_out();
			if( isJson(rs)) {
				let ds = JSON.parse(rs);
				let source = $('#sq-template').html();
				let output = $('#sq-detail');

				render(source, ds, output);

				$('#sqModal').modal('show');
			}
			else {
				$('#sqModal').modal('show');
			}
		}
	})
}

$('#sqModal').on('shown.bs.modal', function() {
	$('#sq-code').focus();
});


function getSqList() {
	let fromDate = $('#fDate').val();
	let toDate = $('#tDate').val();
	let customer = $('#sq-customer').val();
	let sqNo = $('#sq-code').val();

	load_in();

	$.ajax({
		url:HOME + 'get_quotation_list',
		type:'GET',
		cache:false,
		data:{
			'SQNO' :  sqNo,
			'CardCode' : customer,
			'fromDate' : fromDate,
			'toDate' : toDate
		},
		success:function(rs) {
			load_out();
			if( isJson(rs)) {
				let ds = JSON.parse(rs);
				let source = $('#sq-template').html();
				let output = $('#sq-detail');
				render(source, ds, output);
			}
			else {
				let ds = [];
				let source = $('#sq-template').html();
				let output = $('#sq-detail');
				render(source, ds, output);
			}
		}
	})
}


function addSqToOrder() {
  let sq = [];

  $('.sq-chk').each(function() {
    if($(this).is(':checked')) {
      let docEntry = $(this).val();
      sq.push(docEntry);
    }
  });

  if(sq.length == 0) {
    return false;
  }
  else {
    $('#sqModal').modal('hide');

    setTimeout(() => {
      load_in();

      $.ajax({
        url:HOME + 'get_sap_quotation_details',
        type:'POST',
        cache:false,
        data: {
          "sq_list" : JSON.stringify(sq)
        },
        success:function(rs) {
          load_out();

          if(isJson(rs)) {
            let data = JSON.parse(rs);

            if(data.length) {
              var row = getNextRow();

              data.forEach(function(ds, index) {
                let no = $('#row-no').val();
                let price = parseDefault(parseFloat(ds.stdPrice), 0.00);
                let sellPrice = parseDefault(parseFloat(ds.SellPrice), 0.00);
                let lineTotal = parseDefault(parseFloat(ds.LineTotal), 0.00);
                let qty = parseDefault(parseFloat(ds.Qty), 1);
                let disc1 = parseDefault(parseFloat(ds.DiscPrcnt), 0.00);

                ds.no = no;
                ds.Price = price;
                ds.SellPrice = sellPrice;
                ds.LineTotal = lineTotal;
                ds.PriceLabel = price.toFixed(2);
                ds.priceLabel = price.toFixed(2);
                ds.sellPriceLabel = sellPrice.toFixed(2);
                ds.lineTotalLabel = lineTotal.toFixed(2);
                ds.Qty = qty;

                source = ds.TreeType == 'I' ? $('#childRow-template').html() : $('#import-row-template').html();
                output = row == 0 ? $('#row-1') : $('#row-'+row);

                if(row == 0) {
                  render_before(source, ds, output);
                }
                else {
                  render_after(source, ds, output);
                }

                recalAmount(no);
                $('#whs-'+no).val(ds.WhsCode);
                $('#uom-'+no).val(ds.UomEntry);
                row = no;
                no++;
                $('#row-no').val(no);
                console.log(row);
              })

              reIndex();
              init();

              recalTotal();
            }
          }
        }
      })
    }, 300);
  }
}



function getNextRow() {
	let rowNo = 0;
  let lastNo = 0;
	$('.is-blank').each(function() {
		if($(this).val() == 0) {
			rowNo = $(this).data('no');
		}

		// if($(this).val() == 1)
		// {
		// 	rowNo = 0;
		// }
    console.log(rowNo);
	});

	return rowNo;
}
