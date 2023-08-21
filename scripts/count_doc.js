function count_sq() {
  if($('#SQ').length) {
    let sq = $('#SQ');
    let right = sq.val();
    let maxDisc = sq.data('disc');
    let maxAmount = sq.data('amount');

    $.ajax({
      url:BASE_URL + 'main/count_sq',
      type:'GET',
      cache:false,
      data:{
        'right' : right,
        'maxDisc' : maxDisc,
        'maxAmount' : maxAmount
      },
      success:function(rs) {
        $('#count-sq').text(rs);
      }
    })
  }
}

var loop = setInterval(function(){
  count_sq();
}, 300000);
