jQuery(document).ready(function($)
{
    // requete ajax en cours
    var busy=null;
    
    $('#sendrech').click(function()
    {
        
        form=$(this).parent('form');
    
        if (busy) busy.abort();

            busy = $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: form.serialize(),
                success: function(response){
                    if(response!=0)
                    {
                        // vide formulaire
                       $("#resultcours").empty().append(response);

                    }else{
                        $("#resultcours").empty().append('<ul><li class="noresult">Il n\'y a aucun professeur correspondant à vos critères</li></ul>');
                    }
                }
            });

            return false;
    });
    
});