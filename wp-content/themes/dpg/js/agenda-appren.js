jQuery(document).ready(function($)
{
        $('#aresume').css('display','none');
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
                    url: _ajax_refreshprofappren.url,
                    type: 'POST',
                    data:{
                        action: 'refreshprofappren',
                        id:_ajax_refreshprofappren.id,
                        _ajax_nonce: _ajax_refreshprofappren.nonce,
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
                    $('#eventMatieredpgr').text(calEvent.matierename);
                    
                    var timestart=Math.round(calEvent.start.getTime() / 1000);
                    var timeend=Math.round(calEvent.end.getTime() / 1000);
                    
                    var d = new Date();
                    var curdate=d.getTime()/86400000;
                    var curcal=calEvent.start.getTime() / 86400000;
                    var diff=new Number(curcal-curdate).toFixed(0);
                    
					console.log(diff);
					
                    if(diff<3)
                    {
                        if(calEvent.validee==0)
                        {
                            $('#calEventnotallow').dialog('open');
                        }
                        return;
                    }
                    
                    var duree=(timeend-timestart)/60;
                    
                    var dureeok=true;
                    
                    // test conso
                    if (busy) busy.abort();
                    
                    busy = $.ajax({
                        url: _ajax_testconso.url,
                        type: 'POST',
                        data: {
                            action: 'testconso',
                            id:_ajax_testconso.id,
                            duree:duree,
                            _ajax_nonce: _ajax_testconso.nonce,
                        },
                        success: function(response){
                            if(response=='notallowed')
                            {
                                $('#calDureeDialog').dialog('open');
                            }else{
                                if (calEvent.retenu==0 && calEvent.validee==0) $('#calEventDialog').data('start',timestart).data('end',timeend).data('id',calEvent.id).data('idprof',calEvent.idprof).dialog('open');
                                if (calEvent.retenu==1 && calEvent.validee==0) $('#calEventDeprog').data('start',timestart).data('end',timeend).data('id',calEvent.id).data('idprof',calEvent.idprof).dialog('open');
                            }
                            
                        }
                    });
                    
                }
            }
 

		});
    
     $('#calDureeDialog').dialog({
        draggable: false,
        modal: false,
        dialogClass: 'no-close',
        resizable: false,
        autoOpen: false,
        title: 'Compte d\'heures dépassé',
        buttons:{
            OK: function(){
                $(this).dialog('close');
            }
        }
    });
    
    $('#calEventnotallow').dialog({
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
    
    
    $('#calEventDialog').dialog({
        draggable: false,
        modal: false,
        dialogClass: 'no-close',
        resizable: false,
        autoOpen: false,
        title: 'Sélectionner ce cours',
        width: 400,
        //$(this).data('start');
        buttons: {
            Oui: function() {
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
                        data: $('#frmreserve').serialize()+'&start='+st+'&end='+nd+'&id='+id+'&idprof='+idprof,
                        success: function(response){
                            //console.log(response);
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
    
    
    $('#calEventDeprog').dialog({
        resizable: false,
        autoOpen: false,
        title: 'Déprogrammer ce cours',
        width: 400,
        //$(this).data('start');
        buttons: {
            Oui: function() {
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
    
    
});