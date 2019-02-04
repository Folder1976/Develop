$(function () {
    'use strict';
    var calc = true;
    $('.uptocall-mini-phone').click(function (e) {
        e.preventDefault();
        var img_src = $(this).data('src'),
        item_name = $(this).data('name'),
        item_cost_str = $(this).data('price'),
        item_cost_num = +item_cost_str.replace(/[^0-9.]/g, '').slice(0, -1);
       //debugger;
                
                var request = $.get('http://'+window.location.hostname+'/catalog/controller/callback/callback.php', function (data) {
                    //console.log(data);
                    $('#order-form').remove();
                    $('body').prepend(data).addClass('overflow-hidden');
                    $('#order-form .img').attr('src', img_src);
                    $('#order-form .name').text(item_name);
                   
                    calc = true;
                    if (item_cost_num == 0) {
                        item_cost_num = 'по запросу';
                        calc = false;
                        $('#order-form .curency').hide();
                    }else{
                         //$('#order-form .curency').text($('.curent_currency').data('symbol_left')+$('.curent_currency').data('symbol_right'));
                         $('#order-form .curency').text(' руб.');
                    }
                        $('#order-form .sum').text(item_cost_num).data('cost', item_cost_num);
                        
                    })
                .done(function() { console.log("second success"); })
                .fail(function() { console.log("error"); })
                .always(function() { console.log("finished"); });
               
                //console.log(window.location.hostname);
                
    });
    
    $(document).on('click', '#order-form .close-button', function () {
        $('#order-form').remove();		$('body').removeClass('overflow-hidden');
    });
    
    $(document).on('keypress change', '#order-form [name="value"]', function () {
        if ($(this).val() < 1) {
            $(this).val(1);
            }
            if (calc) {
                $('#order-form .sum').text($(this).val() * $('#order-form .sum').data('cost'));
                }
    });
    
    $(document).on('click', '#order-form .plus', function () {
        $('#order-form [name="value"]').val(+$('#order-form [name="value"]').val() + 1).change();
    });
    
    $(document).on('click', '#order-form .minus', function () {
        $('#order-form [name="value"]').val(+$('#order-form [name="value"]').val() - 1).change();
    });
    
    $(document).on('submit', '#order-form form', function (e) {
        e.preventDefault();
        var data = {};
        data['product'] = $('#order-form .name').text();
        
        $('#order-form [name]').each(function () {
            data[$(this).attr('name')] = $(this).val();
        });
        
        $('#order-form [type="submit"]').text('Отправка…');
        
        data['currency'] = $('.curent_currency').data('code');
        
        $.post('http://'+window.location.hostname+'/catalog/controller/callback/sendmail.php', data, function (response) {
            if (response['success']) {
                if(window.location.hostname == 'anticorsib.ru'){
                   
                    alert('Заявка принята. В ближайшее время мы с вами свяжемся.');
                    $('#order-form .close-button').trigger('click');
                    } else {
                        alert('Что-то пошло не так. Повторите попытку. ' + response['error']);
                        }
            }}, 'json');
        });
    });

