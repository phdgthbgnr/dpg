jQuery(document).ready(function($)
{
    // requete ajax en cours
    var busy=null;
    
    // soumission formulaire preinscription
    $('#sendinscrp').click(function()
    {
        var error = false, form=$(this).parent('form');
        
        form.find('[required]').each(function(){
            
            if($.trim($(this).val()) == '')
            {
                $(this).css('border-color','#FF0000');
                error=true;
            }else{
                
                $(this).css('border-color','#CCCCCC');
            }
            
        });
        
        if(!error)
        {
            if (busy) busy.abort();
            
            busy = $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: form.serialize(),
                success: function(response){
                   // console.log(response);
                    if(response=='confirminscrp' || response=='confirmmodif')
                    {
                        // vide formulaire
                        form[0].reset();
                        if(response=='confirminscrp') document.location.href="confirmation-inscription";
                        if(response=='confirmmodif') document.location.href="confirmation-modification-profil";
                        
                    }else{
                        $('#brappel').css('display','none');
                        $('#crappel').prop('checked', false);
                        if($.isArray(response))
                        {
                           $.each(response,function(key,value){
                              
                               if(value=='cps')
                               {
                                   $('#brappel').css('display','block');
                               }else{
                                   $('#'+value).css('border-color','#FF0000');
                               }
                           });
                        }else{
                            //console.log(response);
                        }
                    }
                    
                }
            });
        }
        
        return false;
    });
                       
});