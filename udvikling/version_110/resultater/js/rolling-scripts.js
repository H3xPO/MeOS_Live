
var competitionsList = [];
var classesList = [];


var baseUrl = "/meos_mop/bootshow.php?";

var cmp = 0;
var cls = 0;
var leg = 0;
var ord = 0;
var radio = "finish";
var multiple = "";
//-------------------------------------------------------------------------------------------------------------------------

function Columnize()
{
//alert("hello");
    setTimeout("$('.wide').columnize({width:200});", 200);
}

function setup()
{
	// Hook up event handlers
	$("#competition-selector").change(function () {
		if ($("select#competition-selector").prop('selectedIndex') >= 0)
		{
			cmp = $("#competition-selector").prop('value');
	    	initializeClassSelector();
	    }
	});

	$("#class-selector").change(function () {
		if ($("select#class-selector").prop('selectedIndex') >= 0)
		{
			cls = $("#class-selector").prop('value');
		}
	});

    setTimeout("initializeSelectors()", 1000);
}


// Initialize selectors
function initializeSelectors() {
    listCompetitions = false;
    listClasses = false;

	initializeCompetitionSelector();
}

function initializeCompetitionSelector() {
    $("#competition-selector").empty();
	listCompetitionsUrl  = baseUrl	+ "action=comp"
    $.getJSON(
    	listCompetitionsUrl,
    	null,
       	function (data) {
            competitionsList.length = 0;
			classesList.length = 0;
            $.each(data, function (idx, val) {
                    	competitionsList.push(val);
            		}
        		);

		    $.each(competitionsList, function (idx, competitionInfo) {
		        var option = $('<option />');
		        option.attr('value', competitionInfo[2]).text(competitionInfo[1] + " : " + competitionInfo[0]);
		        $('#competition-selector').append(option);
		    });

			/* select the first */
			if (competitionsList.length >0) {
				$("select#competition-selector").prop('selectedIndex', 0);
				$('#competition-selector').change();
			}

        	}
        );
}

function initializeClassSelector() {
    $("#class-selector").empty();
	listClassesUrl  = baseUrl + "action=class&cmp=" + cmp

    $.getJSON(
    	listClassesUrl,
    	null,
        function (data) {
			classesList.length = 0;
            $.each(data, function (idx, val)
		            	{
        		            classesList.push(val);
            		}
        		);

		    $.each(classesList, function (idx, classInfo) {
		        //var option = $('<option />');
		        //option.attr('value', classInfo[1]+'#'+classInfo[0]).text(classInfo[0]);
		        var option="<nobr><input type=checkbox name='cls[]' value='"+classInfo[1]+"'>"+classInfo[0]+"</input></nobr>";
		        if (idx>0) { option="<br class='removeiffirst removeiflast'/>"+option;}
		        $('#class-selector').append(option);
		    });
        	}
        );

    Columnize();
}

function loadFinishResult(action, cmp, cls, leg, radio, multiple) {

	listResultUrl  = baseUrl + "action=classname&cmp=" + cmp + "&cls=" + cls;

	$.ajax({
        type: 'GET',
        url: listResultUrl,
        dataType: 'json',
        success:         function (data) {
        	classname = (data[0])[0];
		},
        data: {},
        async: false
    });


	listResultUrl  = baseUrl + "action=" + action + "&cmp=" + cmp + "&cls=" + cls + "&radio=" + radio + "&multiple=" + multiple + "&relayrolling=1";

	$.ajax({
        type: 'GET',
        url: listResultUrl,
        dataType: 'json',
        success:         function (data) {
			$('#horizontalmenu').append(formatLi(cls, classname));
			$('#resultContainer').append(formatResult(cls, classname, data));
		},
        data: {},
        async: false
    });
}

$(document).ready(function() {
  $.ajaxSetup({ cache: false });
});


function formatLi(cls, clsname) {
	return '<li><a href="#'+cls+'">'+htmlEncode(clsname)+'</a></li>';
}


function formatResult(cls, clsname, result) {

	/* Finder antal kolonner */
	head = false;
	kol = 4;
	$.each(result,
    	function (idx, val) {
    		if (head == false)
    		{
				$.each(result[idx], function(jdx, cell){
					kol=jdx;
				});
    			head = true;
    		}
		});

	head = false;

	html = "<div id='" + cls + "'>";
	html += "<h2>" + unescape(clsname) + "</h2>";
	html += "<table style='table-layout:fixed;' class='table-striped table-condensed table-bordered' width='100%'>";

    $.each(result,
    	function (idx, val) {

			html += "<tr>"
    		if (head == false)
    		{
				html += "<thead>"
				$.each(result[idx], function(jdx, cell){
					w="";
					if (jdx==0)
					{
						w=" width='6%'";
					}
					else if (jdx==1 || jdx == 2)
					{
						if (kol==6)
						{
							w=" width='21%'";
						}
						else
						{
							w=" width='35%'";
						};
					}
					else if (jdx>=3)
					{
						w=" width='12%'";
					}
					a = (jdx==1 || jdx == 2) ? 'text-align:left' : 'text-align:right';
					html += "<th style='"+ a +"' " + w + ">" + htmlEncode(cell) + "</th>";
				});
				html += "</thead>"
				html += "<tbody>"
    			head = true;
    		}
    		else
    		{
				$.each(result[idx], function(jdx, cell){
					a = (jdx==1 || jdx == 2) ? 'left' : 'right';
					if (jdx==0) {
						html += "<td style='padding: 2px;' valign='top' align='"+ a +"'>" + cell + "</td>";
					} else {
						html += "<td style='padding: 2px;' valign='top' align='"+ a +"'>" + htmlEncode(cell) + "</td>";
					}
					//html += "<td style='padding: 2px;' valign='top' align='"+ a +"'>" + htmlEncode(cell) + "</td>";
				});
    		}
			html += "</tr>"
		});
	html += "</tbody>"
	html += "</table>";
	html += "</div>";

	return html;
}

function htmlEncode(value) {
	return $('<div/>').text(value).html();
}

function escapeHtml(raw) {
    return raw.replace(/[&<>"']/g, function onReplace(match) {
        return '&#' + match.charCodeAt(0) + ';';
    });
}
