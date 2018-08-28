jQuery(document).ready(function($)
{
    /*
    $('.profok').hover(function(){
        var w=$(this).width();
        var p=$(this).position();
        var t=(Math.round(p.top)-56)+'px';
        var h=Math.round(p.left)+'px';
        $('#infoprofok').css('top',t);
        $('#infoprofok').css('left',h);
        $('#infoprofok').width(w);
        $('#infoprofok').css('display','block');
    },function(){
        $('#infoprofok').css('display','none');
    });
    
    $('.profnot').hover(function(){
        var w=$(this).width();
        var p=$(this).position();
        var t=(Math.round(p.top)-56)+'px';
        var h=Math.round(p.left)+'px';
        $('#infoprofnot').css('top',t);
        $('#infoprofnot').css('left',h);
        $('#infoprofnot').width(w);
        $('#infoprofnot').css('display','block');
    },function(){
        $('#infoprofnot').css('display','none');
    });
    */
    $('.profok').each(function(index){
        $(this).hover(function(){
        $('<li class="infoprof"></li>').insertAfter($(this).parent());
        $('.infoprof').empty().append('<strong>Votre profil (niveau scolaire et code postal) correspond</strong><br/>Vous pouvez vous inscrire sur le plannig de ce professeur');
            setTimeout(function(){
                $('.infoprof').addClass('animinfo');
            },.5);
        },function(){
           $(this).parent().next().remove();
        });
    });
        $('.profnot').hover(function(){
            $('<li class="infoprofnot"></li>').insertAfter($(this).parent());
            $('.infoprofnot').empty().append('<strong>Votre profil (niveau scolaire et/ou code postal) ne correspond pas</strong>');
            setTimeout(function(){
                $('.infoprofnot').addClass('animinfo');
            },.5);
        },function(){
            $(this).parent().next().remove();
        });
    
});