  <!-- pew_pew UDS -->
  <span class="coupon_input_wrapper">
  <span class="required"></span> Код UDS<br />
  <input type="text" name="uds-code" value="" class="large-field" id="coupon_code"/><a href="javascript:;" class="uds-send-coupon">отправить</a>
  <div class="error pew-uds-error">Неверный код</div>
  <br />
  <br />
  </span>
  <div class="uds_msg"></div>
  <div class="pew-uds-balance result-table">
      <div class="pew-uds-available">Вам доступно: <span class="scores">11234</span> бонуса</div>
   	Потратить <input type="text" name="uds-discount" class="scores_resived" style="width: 100px;"> бонусов <a href="#" class="uds-apply">применить</a>
        <input type="hidden" name="cupon_resived" class="cupon_resived">
  </div>
  
  
  <script>
   $('.uds-send-coupon').click(function(event) {
    var copupon = $('#coupon_code').val(); // получаем код купона
    
    $.ajax({
      url: '/sendcoupon.php', // отправляем в обработчик
      type: 'POST',
      data: {copupon: copupon},
        success: function(data) { 
          var obj = JSON.parse(data);
          //console.log(obj);

          var test = JSON.parse(obj);
          
          console.log(test);
          
          if(!test) {
            alert('К сожалению ваш купон не найден!');
            return false;
          }
          
          // заполняем поля
          $('.pew-uds-available').html('Уважаемый, '+ test['name']+' '+test['surname']+', Вам доступно: <span class="scores">'+test['scores']+'</span> бонуса');
          $('.scores_resived').val(test['scores']);
          var cod = $('#coupon_code').val();
          $('.cupon_resived').val(cod);
          $('.result-table').show();
          $('.coupon_input_wrapper').hide();
      
        }
    });
      
   });
    
    
    	//pew UDS
  

   $(".uds-apply").click(function(){
    var sum = $(".pew-uds-balance input").val();
  
    $(".cart-info tfoot tr:last-child").before('<tr><td colspan="4" class="price" align="right"><b>Скидка UDS:</b></td><td colspan="4" class="total" align="right"><span>0</span>р.</td></tr>');
     
    //добавление скидки
    $(".cart-info tfoot tr:nth-last-child(2) .total span").html(sum);
    
    //отнимаем от итоговой
    var total = $(".cart-info tfoot tr:last-child .total").html();
    total = total.replace('р.','').replace(' ','');
    var new_total = +total - +sum;
    
    $(".cart-info tfoot tr:last-child .total").html(new_total+' р.');
    
    $('.pew-uds-balance').hide();
    $('.uds_msg').html('Вам насчитана скидка в размере <b>'+sum+'р.</b>');
    $('.uds_msg').show();
    
    return false;
   });
      
  </script>
  <!-- pew_pew UDS -->
