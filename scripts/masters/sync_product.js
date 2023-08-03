
var total = 0;
var limit = 100;
var updated = 0;
var resend = 3;
var send = 0;

var last_sync;

function syncData(){
  load_in();

	$.ajax({
    url:HOME + 'get_last_sync_date',
    type:'GET',
    cache:false,
    success:function(rs){
      last_sync = rs;
			$.ajax({
				url:HOME + 'count_update_rows',
				type:'GET',
				cache:false,
				data:{
					'last_sync_date' : last_sync
				},
				success:function(co) {
					if(! isNaN(co)) {
						total = co;

						updateData();
					}
				}
			})
    }
  });
}


function forceSyncData(){
	last_sync = '2020-01-01 00:00:00';
  load_in();

	$.ajax({
		url:HOME + 'count_update_rows',
		type:'GET',
		cache:false,
		data:{
			'last_sync_date' : last_sync
		},
		success:function(co) {
			if(! isNaN(co)) {
				total = co;
				updateData();
			}
		}
	})
}


function updateData() {
	console.log("Sync "+updated+" items out of "+total+" items");

	if(updated == total) {
		load_out();

		swal({
			title:'Success',
			type:'success',
			timer:1000
		});

		setTimeout(function() {
			window.location.reload();
		}, 1200);
	}
	else {
		$.ajax({
			url:HOME + 'sync_data',
			type:'GET',
			cache:false,
			data:{
				'last_sync' : last_sync,
				'limit' : limit,
				'offset' : updated
			},
			success:function (rs) {
				if(!isNaN(rs)) {
					updated += parseInt(rs);
          send = 0;
					updateData();
				}
        else {
          send++;
          if(send > resend) {
            load_out();
            text = "Updated "+updated+" of "+total+" successful";
            swal({
              title:'Error!',
              text: "response from API AS '"+rs+"' <br/>" + text,
              type:'error',
              html:true
            });
          }
          else {
            updateData();
          }
        }
			}
		});
	}
}


function syncItem(code) {
  load_in();
  $.ajax({
    url:HOME + 'sync_item',
    type:'GET',
    cache:false,
    data:{
      'code' : code
    },
    success:function(rs) {
      load_out();
      if(rs === 'success') {
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(function() {
          window.location.reload();
        }, 1200);
      }
      else {
        swal({
          title:'Error!',
          text:rs,
          type:'error'
        });
      }
    }
  })
}
