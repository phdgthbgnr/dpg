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
            
        
			editable: true,
            
            selectable:true,
            selectHelper: true,
            allDay:false,

            select: function(start, end, allDay, jsEvent, view)
            {
                    if(view.name=='agendaDay')
                    {
                        //console.log(start);
                        var heuredeb=$.fullCalendar.formatDate( start, 'H(:mm{ - H:mm})' );
                        var heurefin=$.fullCalendar.formatDate( end, 'H(:mm{ - H:mm})' );
                        $('#eventStart').val(heuredeb);
                        $('#eventEnd').val(heurefin);
                        $('#hdeb').val(start);
                        $('#hfin').val(end);
                        var timestart=Math.round(start.getTime() / 1000);
                        var timeend=Math.round(end.getTime() / 1000);
                        //var fin=start.getTime() + (120*60*1000);
                        //console.log(fin);
                        //console.log(start);
                        var d = new Date();
                        var curdate=d.getTime()/86400000;
                        var curcal=start.getTime() / 86400000;
                        var diff=new Number(curcal-curdate).toFixed(0);
                        //console.log(curcal+' / '+curdate+' / '+diff);
                        if(diff>=2)
                        {
                            $('#calEventDialog').data('start',timestart).data('end',timeend).dialog('open');
                        }else{
                            $('#datenotallowed').dialog('open');
                        }
                    }
					/*
					if (title)
					{
						dpgcalendar.fullCalendar('renderEvent',
							{
								title: title,
								start: start,
								end: end,
								allDay: allDay
							},
							true // make the event "stick"
						);
					}
                    */
					dpgcalendar.fullCalendar('unselect');
				},
            
            lazyFetching:true,
            
            eventSources: [
                {
                    url: _ajax_refreshprof.url,
                    type: 'POST',
                    data:{
                        action: 'refreshprof',
                        _ajax_nonce: _ajax_refreshprof.nonce,
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
            
            eventResize: function(event, jsEvent, ui, view)
            {
                if(event.validee==1) {
                    $('#modifnotallowed').dialog('open');
                    dpgcalendar.fullCalendar( 'refetchEvents' );
                    return;
                }
                //console.log('end '+view.end.getTime() / 1000);
                //console.log('id '+event.id);
                var tid=event.id;
                var tend=event.end.getTime() / 1000;
                var hfintxt=$.fullCalendar.formatDate( event.end, 'H(:mm{ - H:mm}' );
                
               // console.log(event.id);
                
                if (busy) busy.abort();
                
                busy = $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'eventResize',
                            _ajax_nonce: _ajax_eventResize.nonce,
                            id:tid,
                            tend:tend,
                            end:event.end,
                            hfintxt:hfintxt
                        },
                        success: function(response){
                            //console.log(response);
                            if(response=='success')
                            {
                                dpgcalendar.fullCalendar( 'refetchEvents' );
                                
                                
                            }else{

                                
                            }
                            
                        }
                    });
            },
            
            eventDrop:function(event, dayDelta, minuteDelta, allDay,revertFunc, jsEvent,ui, view)
            {
                //console.log(event.id+ ' '+event.start);
                var tid=event.id;
                var tstart=event.start.getTime() / 1000;
                var tend=event.end.getTime() / 1000;
                var hstarttxt=$.fullCalendar.formatDate( event.start, 'H(:mm{ - H:mm}');
                var hfintxt=$.fullCalendar.formatDate( event.end, 'H(:mm{ - H:mm}');
                
                if(event.validee==1) {
                    $('#deplacnotallowed').dialog('open');
                    dpgcalendar.fullCalendar( 'refetchEvents' );
                    return;
                }
                
                if (busy) busy.abort();
                
                busy = $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'eventDrop',
                            _ajax_nonce: _ajax_eventDrop.nonce,
                            id:tid,
                            tend:tend,
                            tstart:tstart,
                            end:event.end,
                            start:event.start,
                            hdebtxt:hstarttxt,
                            hfintxt:hfintxt
                        },
                        success: function(response){
                            //console.log(response);
                            if(response=='success')
                            {
                                dpgcalendar.fullCalendar( 'refetchEvents' );
      
                            }else{
                                revertFunc();
                            }
                            
                        }
                    });
            },
            
            eventClick:function(calEvent,jsEvent,view)
            {
                var heuredeb=$.fullCalendar.formatDate( calEvent.start, 'H(:mm{ - H:mm})' );
                var heurefin=$.fullCalendar.formatDate( calEvent.end, 'H(:mm{ - H:mm})' );
                heuredeb=heuredeb.length==2?heuredeb+':00':heuredeb;
                heurefin=heurefin.length==2?heurefin+':00':heurefin;
                
                heuredeb = heuredeb.replace(":","h");
                heurefin = heurefin.replace(":","h");
                
                $('#eventStartm').text(heuredeb);
                $('#eventEndm').text(heurefin);
                $('#eventTitlem').text(calEvent.title);
                $('#eventEleve').text(calEvent.eleve);
                $('#adressEleve').text(calEvent.adresse);
                $('#villeEleve').text(calEvent.ville);
                $('#cpEleve').text(calEvent.cp);
                $('#telEleve').text(calEvent.tel);
                $('#mobileEleve').text(calEvent.mobile);
                
                $('#eventStartms').text(heuredeb);
                $('#eventEndms').text(heurefin);
                $('#eventTitlems').text(calEvent.title);
                $('#eventEleves').text(calEvent.eleve);
                
                if(calEvent.eleve=='') $('#eventEleves').text('Cours non retenu');
                $('#eventMatiere').text(calEvent.matiere);
                $('#eventMatieres').text(calEvent.matiere);
                
                var timestart=Math.round(calEvent.start.getTime() / 1000);
                var timeend=Math.round(calEvent.end.getTime() / 1000);
                if(calEvent.retenu==0 && calEvent.validee==0) $('#calsupprEventDialog').data('start',timestart).data('end',timeend).data('id',calEvent.id).dialog('open');
                if(calEvent.retenu==1 && calEvent.validee==0) $('#calmodifEventDialog').data('start',timestart).data('end',timeend).data('id',calEvent.id).dialog('open');
            },
            
            dayClick:function(date, allDay, jsEvent, view)
            {
                //console.log(date);
                dpgcalendar.fullCalendar('gotoDate',date);
                dpgcalendar.fullCalendar( 'changeView', 'agendaDay' );
            }
            
            

		});
    
    $('#calEventDialog').dialog({
        draggable: false,
        modal: false,
        dialogClass: 'no-close',
        resizable: false,
        autoOpen: false,
        title: 'Ajouter un cours',
        width: 400,
        //$(this).data('start');
        buttons: {
            Save: function() {
                if ($('#eventTitle').val() !== '') {
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
                    var eStart=$('#eventStart').val();
                    var eEnd=$('#eventEnd').val();
                    
                    if (busy) busy.abort();
                    
                    busy = $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: $('#frmcours').serialize()+'&start='+st+'&end='+nd+'&eventStart='+eStart+'&eventEnd='+eEnd,
                        success: function(response){
                            if(response=='success')
                            {
                                dpgcalendar.fullCalendar( 'refetchEvents' );
                                dpgcalendar.fullCalendar('unselect');
                                $('#calEventDialog').dialog('close');
                                $('#comment').val('');
                            }else{

                                
                            }
                            
                        }
                    });
                       
                }
                
                
            },
            Cancel: function() {
                $(this).dialog('close');
            }
        }
    });
    
    
    
    
    $('#calsupprEventDialog').dialog({
        draggable: false,
        modal: false,
        dialogClass: 'no-close',
        resizable: false,
        autoOpen: false,
        title: 'Effacer un cours',
        width: 400,
        buttons:{
             Effacer: function() {
                 
                var id=$(this).data('id');
                 
                if (busy) busy.abort();
                 
                 busy = $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: $('#frmmdfcours').serialize()+'&id='+id+'&actiontype=effacer',
                    success: function(response){
                        if(response=='success')
                        {
                                dpgcalendar.fullCalendar( 'refetchEvents' );
                                // console.log(response);
                                
                        }else{

                                
                        }
                            
                    }
                });
                 
                $(this).dialog('close');
            },
            
            Annuler: function(){
                $(this).dialog('close');
            }
            
        }
            
     });
    
    
    
    
     $('#calmodifEventDialog').dialog({
        draggable: false,
        modal: false,
        dialogClass: 'no-close',
        resizable: false,
        autoOpen: false,
        title: 'Effacer ou valider un cours',
        width: 400,
        buttons:{
             Effacer: function() {
                 
                var id=$(this).data('id');
                 
                if (busy) busy.abort();
                 
                 busy = $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: $('#frmmdfcours').serialize()+'&id='+id+'&actiontype=effacer',
                    success: function(response){
                        if(response=='success')
                        {
                                dpgcalendar.fullCalendar( 'refetchEvents' );
                                // console.log(response);
                                
                        }else{

                                
                        }
                            
                    }
                });
                 
                $(this).dialog('close');
            },
            
            Valider: function() {
                
                var id=$(this).data('id');
                 
                if (busy) busy.abort();
                 
                 busy = $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: $('#frmmdfcours').serialize()+'&id='+id+'&actiontype=valider',
                    success: function(response){
                        if(response=='success')
                        {
                                dpgcalendar.fullCalendar( 'refetchEvents' );
                                // console.log(response);
                                
                        }else{

                                
                        }
                            
                    }
                });
                 
                $(this).dialog('close');
            },
            Annuler: function(){
                $(this).dialog('close');
            }
            
        }
            
     });
    
    $('#datenotallowed').dialog({
        draggable: false,
        modal: false,
        dialogClass: 'no-close',
        resizable: false,
        autoOpen: false,
        title: 'Jour sélectionné déjà validé',
        buttons:{
            OK: function(){
                $(this).dialog('close');
            }
        }
    });
    
    
    $('#deplacnotallowed').dialog({
        draggable: false,
        modal: false,
        dialogClass: 'no-close',
        resizable: false,
        autoOpen: false,
        title: 'Jour sélectionné déjà validé',
        buttons:{
            OK: function(){
                $(this).dialog('close');
            }
        }
    });
    
    
    $('#modifnotallowed').dialog({
        draggable: false,
        modal: false,
        dialogClass: 'no-close',
        resizable: false,
        autoOpen: false,
        title: 'Jour sélectionné non valide',
        buttons:{
            OK: function(){
                $(this).dialog('close');
            }
        }
    });
    
    // chargement et affichage du resume des cours programmes
    $('#aresume').click(function(){
        
        if (busy) busy.abort();
                    
        busy = $.ajax({
            url: _ajax_resumecoursprof.url,
            type: 'POST',
            data: {
                action: 'resumecoursprof',
                _ajax_nonce:_ajax_resumecoursprof.nonce,
            },
                success: function(response){
                    if(response!='error')
                    {
                        $('#contentresume').empty().append(response);
                        $('#blocresume').css('display','block');
                    }else{
                       // console.log(response);    
                }
                                
            }
        });
        return false;
    });
    
    $('#exprotcsv').click(function(){
        if ($('#mois').val()!='00' && $('#annee').val()!='00')
        {
            $('#csvprof').submit();
        }
        return false;
    });
    
});