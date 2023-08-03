function createSO(code) {
	swal({
    title:'Create Sale Order',
    text:'ต้องการสร้าง Sale Order ใหม่ จาก Sale Quotation นี้หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'ไม่ใช่',
    confirmButtonText:'ใช่ ฉันต้องการ',
		closeOnConfirm:true
  },
  function(){
		load_in();
		$.ajax({
			url: BASE_URL + 'orders/orders/create_from_sq',
			type:'POST',
			cache:false,
			data:{
				'sq_code' : code
			},
			success:function(rs) {
				load_out();
				var rs = $.trim(rs);
				if(isJson(rs)) {
					var ds = $.parseJSON(rs);
					if(ds.status === 'success') {
						setTimeout(function() {
							swal({
								title:'Success',
								text: 'Sale Order Created Successfull : ' + ds.code,
								type:'success',
								timer:1000
							});

							setTimeout(function(){
								window.location.href = BASE_URL + 'orders/orders/edit/'+ds.code;
							},1200)
						}, 500)

					}
					else {
						swal({
							title:"Error!",
							text:ds.error,
							type:'error'
						});
					}
				}
				else {
					swal({
						title:'Error!',
						text:rs,
						type:'error'
					})
				}
			}
		})
  });
}



function printSQ(code) {
	var prop 			= "width=800, height=900. left="+center+", scrollbars=yes";
	var center    = ($(document).width() - 800)/2;
	var code  = $("#order_code").val();
  var target  = HOME + 'print_sq/'+code;
  window.open(target, '_blank', prop);
}
