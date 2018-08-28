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
                    url: _ajax_refreshprof.url,
                    type: 'POST',
                    data:{
                        action: 'refreshprof',
                        id:_ajax_refreshprof.id,
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
 

		});
    
    
    
});