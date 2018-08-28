jQuery(document).ready(function($)
{
		var busy=null;
    
		var dpgcalendar=$('#calendar').fullCalendar({
            //firstHour:8,
            //firstHour:8,
            height: 610,
            minTime:8,
            maxTime:21,
            monthNames:['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Janv','Fév','Mar','Avr','Mai','Juin','Juill','Août','Sept','Oct','Nov','Déc'],
            dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
            dayNamesShort:['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
            axisFormat:'H(:mm)',
            allDaySlot:false,
            slotEventOverlap:false,
            defaultEventMinutes:90,
            snapMinutes:30,
            slotMinutes:30,
            timeFormat:{agenda: 'H:mm{ - H:mm}','': 'H(:mm)'},
            buttonText: {
                prev: '&lt;',
                next: '&gt;',
                today: 'Aujourd\'hui',
                month: 'Mois',
                week: 'Semaine',
                day: 'Jour'
            },
            
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
            
        
			editable: false,
            
            selectable:false,
            selectHelper: false,
            allDay:false,
            
            
            lazyFetching:true,
            
            eventSources: [
                {
                    url: _ajax_refreshapprenpriv.url,
                    type: 'POST',
                    data:{
                        action: 'refreshapprenpriv',
                        id:_ajax_refreshapprenpriv.id,
                        _ajax_nonce: _ajax_refreshapprenpriv.nonce,
                    },
                    error:function(e)
                    {
                        //console.log('erreur');
                    },
                    success:function(e)
                    {
                        //console.log(e);
                    }
                }
            ],
            
            eventClick:function(calEvent,jsEvent,view)
            {
                if(calEvent.allow==true)
                {
                    var heuredeb=$.fullCalendar.formatDate( calEvent.start, 'H(:mm{ - H:mm})' );
                    var heurefin=$.fullCalendar.formatDate( calEvent.end, 'H(:mm{ - H:mm})' );
                    
                    $('#eventStart').text(heuredeb);
                    $('#eventEnd').text(heurefin);
                    $('#eventTitle').text(calEvent.title);
                    $('#eventMatiere').text(calEvent.matierename);
                    $('#eventComment').text(calEvent.comment);
                    
                    $('#eventStartdpgr').text(heuredeb);
                    $('#eventEnddpgr').text(heurefin);
                    $('#eventTitledpgr').text(calEvent.title);
                    $('#eventProfdpgr').text(calEvent.prof);
                    $('#eventMatieredpgr').text(calEvent.matierename);
                    
                    var timestart=Math.round(calEvent.start.getTime() / 1000);
                    var timeend=Math.round(calEvent.end.getTime() / 1000);
                    if (calEvent.retenu==0) $('#calEventDialog').data('start',timestart).data('end',timeend).data('id',calEvent.id).data('idprof',calEvent.idprof).dialog('open');
                    if (calEvent.retenu==1) $('#calEventDeprog').data('start',timestart).data('end',timeend).data('id',calEvent.id).data('idprof',calEvent.idprof).dialog('open');
                }
            }
            
        });
    
    
    // deprogramme
     $('#calEventDeprog').dialog({
        resizable: false,
        autoOpen: false,
        title: 'Valider ou déprogrammer ce cours',
        width: 400,
        //$(this).data('start');
        buttons: {
		
            Valider:function(){
				var id=$(this).data('id');
				var idprof=$(this).data('idprof');
				if (busy) busy.abort();
				busy = $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data:{action:'validerplann', _ajax_nonce:_ajax_validerplann.nonce,id:id},
                        success: function(response){
                           if($.isArray(response))
                            {
                                if(response[0]=='success' && response.length==4)
                                {
                                    dpgcalendar.fullCalendar( 'refetchEvents' );
                                }
                                
                            }else{

                                // error
                            }
                            
                        }
                    });
            },
            
            Deprogrammer: function() {
                    //console.log($(this).data('start')+' '+$(this).data('end'));
                    /*
                    dpgcalendar.fullCalendar('renderEvent', {
                        title: $('#eventTitle').val(),
                        start: $('#hdeb').val(),
                        end: $('#hfin').val(),
                        //hdebtxt:$('#eventStart').val(),
                        //hfintxt:$('#eventEnd').val(),
                        allDay: false,
                        //className: eventClass,
                        color: '#6dbcda'
                    }, true // make the event "stick"
                    );
                    */
                    // enregistrement BDD
                   // console.log($('#frmcours').serialize());
                    var st=$(this).data('start');
                    var nd=$(this).data('end');
                    var id=$(this).data('id');
                    var idprof=$(this).data('idprof');
                    if (busy) busy.abort();
                    
                    busy = $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: $('#frmdeprog').serialize()+'&start='+st+'&end='+nd+'&id='+id+'&idprof='+idprof,
                        success: function(response){
                           if($.isArray(response))
                            {
                                if(response[0]=='success' && response.length==4)
                                {
                                    $('.total').empty().append(response[1]);
                                    $('.consom').empty().append(response[2]);
                                    $('.reste').empty().append(response[3]);
                                    dpgcalendar.fullCalendar( 'refetchEvents' );
                                }
                                
                            }else{

                                // error
                            }
                            
                        }
                    });
                       
                dpgcalendar.fullCalendar('unselect');
                $(this).dialog('close');
            },
            Annuler: function() {
                $(this).dialog('close');
            }
        }
    });
    
    
    // chargement et affichage du resume des cours programmes
    $('#aresume').click(function(){
        
        if (busy) busy.abort();
                    
        busy = $.ajax({
            url: _ajax_resumecours.url,
            type: 'POST',
            data: {
                action: 'resumecours',
                _ajax_nonce:_ajax_resumecours.nonce,
            },
                success: function(response){
                    if(response!='error')
                    {
                        $('#contentresume').empty().append(response);
                        $('#blocresume').css('display','block');
                        
                        $('.valider').each(function(){
                            $(this).click(function(){
                                //console.log($(this).attr('id'));
                            return false;
                            });
                        });
                        
                            $('.valider').each(function(){
                                $(this).click(function(){
                                    var id=$(this).attr('href');
                                    if (busy) busy.abort();
                                    busy=$.ajax({
                                            url:_ajax_valider.url,
                                            type:'POST',
                                            data:{action:'valider', _ajax_nonce:_ajax_valider.nonce,id:id},

                                            success:function(response){
                                                //console.log(response);
                                                if($.isArray(response)){
                                                    $.fancybox({
                                                        autoSize:false,
                                                        width:400,
                                                        height:60,
                                                        content: response[0]
                                                    });
                                                    id=response[1];
                                                    $('#cours'+id).parent().empty().append('<a href="#'+id+'" class="evaluer" id="cours'+id+'">Evaluer ce cours</a>');
                                                    
                                                    $('.evaluer').each(function(){
                                                        $(this).click(function(){
                                                            //console.log($(this).attr('href'));
                                                            id=$(this).attr('href');
                                                            if (busy) busy.abort();
                                                            busy=$.ajax({
                                                                url:_ajax_evaluercours.url,
                                                                type:'POST',
                                                                data:{action:'evaluercours', _ajax_nonce:_ajax_evaluercours.nonce,id:id},

                                                                success:function(response){
                                                                    //console.log(response);
                                                                    $.fancybox({
                                                                        autoSize:false,
                                                                        width:600,
                                                                        height:710,
                                                                        content: response,
                                                                        autoScale:false,
                                                                        autoDimensions:false
                                                                    });
                                                                }
                                                            });
                                                        return false;
                                                        });
                                                    });
                                                    
                                                }
                                            }
                                        });
                                     return false;
                                });
                               
                            });

                            $('.evaluer').each(function(){
                                $(this).click(function(){
                                    //console.log($(this).attr('href'));
                                    id=$(this).attr('href');
                                    if (busy) busy.abort();
                                    busy=$.ajax({
                                        url:_ajax_evaluercours.url,
                                        type:'POST',
                                        data:{action:'evaluercours', _ajax_nonce:_ajax_evaluercours.nonce,id:id},

                                        success:function(response){
                                            //console.log(response);
                                            $.fancybox({
                                                autoSize:false,
                                                width:600,
                                                height:710,
                                                content: response,
                                                autoScale:false,
                                                autoDimensions:false
                                            });
                                        }
                                    });
                                return false;
                                });
                            });

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
                                                    height:450,
                                                    content: response
                                                });
                                                
                                            }
                                        });
                                    return false;
                                });
                            });
                        
                        
                    }else{
                        //console.log(response);    
                }
                                
            }
        });
        return false;
    });
  
    
});