var busy=null;

$('.points').each(function(){
    $(this).click(function(){
        var num=$(this).attr('href');
        num=parseInt(num.substr(1,1));
        //console.log(num);
        var pos=(num*-18)+'px';
        $(this).parent().css('backgroundPosition','0px '+pos);
        $(this).parent().parent().find(':input').val(num);
    });
});

$('#recomm').change(function(){
    var check=$(this).is(':checked');
    if(check){
        $('.norecomm').css('display','none');
    }else{
        $('.norecomm').css('display','table');
    }
});

$('#envoyer').click(function(){
    
    if (busy) busy.abort();
    
    var form=$('#frmevalu');
    
    busy = $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: form.serialize(),
            success: function(response){
                
                if($.isArray(response)){
                    $.fancybox.close();
                    var id=response[0];
                    $('#cours'+id).parent().empty().append('<a href="#'+id+'" class="affeval" id="cours'+id+'">Votre évaluation</a>');
                    
                    // meme script que dans agenda-appren-priv
                     $('.affeval').each(function(){
                            $(this).click(function(){
                                id=$(this).attr('href');
                                if (busy) busy.abort();
                                busy=$.ajax({
                                        url:_ajax_affeval.url,
                                        type:'POST',
                                        data:{action:'affeval', _ajax_nonce:_ajax_affeval.nonce,id:id},

                                        success:function(response){
                                            //console.log(response);
                                            $.fancybox({
                                                autoSize:false,
                                                width:600,
                                                height:600,
                                                content: response
                                            });
                                        }
                                    });
                            });
                        });
                    
                    
                }
            }
    });
    
    return false;
});