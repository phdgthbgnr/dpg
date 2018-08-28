jQuery(document).ready(function($)
{
    // requete ajax en cours
    var busy=null;
    
    // soumission formulaire preinscription
    $('#sendcontact').click(function()
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
                    if(response=='confirmcontact')
                    {
                        // vide formulaire
                        form[0].reset();
                        if(response=='confirmcontact') $('#confirmcntct').dialog('open');//document.location.href="confirmation-inscription";
                        
                    }else{
                        if($.isArray(response))
                        {
                           $.each(response,function(key,value){
                              
                                $('#'+value).css('border-color','#FF0000');
                               
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
    
    $( "#confirmcntct" ).dialog({
        resizable: false,
        autoOpen: false,
        dialogClass: "no-close",
        buttons: [
            {
              text: "OK",
              click: function() {
                $( this ).dialog( "close" );
              }
            }
          ]
    });
                       
});


