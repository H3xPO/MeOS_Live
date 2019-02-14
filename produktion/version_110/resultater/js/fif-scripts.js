
var competitiontimeSeriesData_Name = [];
var timeSeriesData_Data = [];

var competitionsList = [];
var classesList = [];
var legsList = [];
var radiosList = [];


var baseUrl = "/meos_mop/bootshow.php?";

var dataPage = "result";

var cmp = 0; var cmpText = "";
var cls = 0; var clsText = "";
var leg = 1; var legText = "";
var ord = 0; var ordText = "";
var radio = 0; var radioText = "";

var nextrefresh = new Date()
nextrefresh.setTime(nextrefresh.getTime() + 60000) // one minute from now.
//-------------------------------------------------------------------------------------------------------------------------

function setup()
{
	// Hook up event handlers
	$("#competition-selector").change(function () {
		if ($("select#competition-selector").prop('selectedIndex') >= 0)
		{
			cmp = $("#competition-selector").prop('value');
			cmpText = $("#competition-selector option:selected").text();
	    	initializeClassSelector();
	    }
	});
	
	$("#class-selector").change(function () {
		if ($("select#class-selector").prop('selectedIndex') >= 0)
		{
			cls = $("#class-selector").prop('value');
			clsText = $("#class-selector option:selected").text();
		    classSelectorChange();
		}
	});
	
	$("#leg-selector").change(function () {
		if ($("#leg-selector").length >0)
		{
			if ($("select#leg-selector").prop('selectedIndex') >= 0)
			{
				leg = $("#leg-selector").prop('value');
				legText = $("#leg-selector option:selected").text();
		    	initializeRadioSelector();
			}
		}
		else
		{
			leg = 1;
			legText = "";
	    	initializeRadioSelector();
		}
	});

	$("#radio-selector").change(function () {
		if ($("select#radio-selector").prop('selectedIndex') >= 0)
		{
			radio = $("#radio-selector").prop('value');
			radioText = $("#radio-selector option:selected").text();
		    loadResult();
		}
	});
	
	if ($("#reload-button").length) {
		$("#reload-button").click(function () {
				reload();
				});
	}
}

$(window).load(function() {
	setup();
    setTimeout("initializeSelectors()", 1000);
    setTimeout("autorefresh()", 10000);
});

$(document).ready(function() {
  $.ajaxSetup({ cache: false });
});

// Initialize selectors
function initializeSelectors() {
    listCompetitions = false;
    listClasses = false;
    listRadios = false;

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
			radiosList.length = 0;
            $.each(data, function (idx, val) {
                    	competitionsList.push(val);
            		}
        		);

		    $.each(competitionsList, function (idx, competitionInfo) {
		        var option = $('<option />');
		        option.attr('value', competitionInfo[2]).text(competitionInfo[1] + " : " + competitionInfo[0] + " (id="+competitionInfo[2] +")");
		        $('#competition-selector').append(option);
		    });
		
			/* select the first */
			if (competitionsList.length >0) {
				if (cmpText!="")
				{
					$('#competition-selector option:contains(' + cmpText + ')').each(function(){
						    if ($(this).text() == cmpText) {
						        $(this).attr('selected', 'selected');
						        return false;
						    }
						    return true;
						});
				}
				if ($("select#competition-selector").prop('selectedIndex') < 0)
				{
					$("select#competition-selector").prop('selectedIndex', 0);
				}
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
			radiosList.length = 0;
            $.each(data, function (idx, val)
		            	{
        		            classesList.push(val);
            		}
        		);

		    $.each(classesList, function (idx, classInfo) {
		        var option = $('<option />');
		        option.attr('value', classInfo[1]).text(classInfo[0]);
		        $('#class-selector').append(option);
		    });
		
			/* select the first */
			if (classesList.length >0) {
				if (clsText!="")
				{
					$('#class-selector option:contains(' + clsText + ')').each(function(){
						    if ($(this).text() == clsText) {
						        $(this).attr('selected', 'selected');
						        return false;
						    }
						    return true;
						});
				}
				if ($("select#class-selector").prop('selectedIndex') < 0)
				{
					$("select#class-selector").prop('selectedIndex', 0);
				}
				$('#class-selector').change();
			}
        	}
        );
}


function classSelectorChange() {
	if (dataPage == "result") {
		initializeLegSelector()	
	} else {
		$('#leg-div').hide();
		$('#leg-selector').hide();
		$('#radio-div').hide();
		$('#radio-selector').hide();
		loadStartlist();
	}
		
}


function initializeLegSelector() {
    $("#leg-selector").empty();
	listLegsUrl  = baseUrl + "action=leg&cmp=" + cmp + "&cls=" + cls
    $.getJSON(
    	listLegsUrl,
    	null,
        function (data) {
			legsList.length = 0;
			radiosList.length = 0;
            $.each(data, function (idx, val)
		            	{
        		            legsList.push(val);
            		}
        		);

			if (legsList.length >0)
			{
				$('#leg-div').show();
				$('#leg-selector').show();
			    $.each(legsList, function (idx, legInfo) {
			        var option = $('<option />');
			        option.attr('value', legInfo[0]).text(legInfo[0]);
			        $('#leg-selector').append(option);
			    });
			
				if (legText!="")
				{
					$('#leg-selector option:contains(' + legText + ')').each(function(){
						    if ($(this).text() == legText) {
						        $(this).attr('selected', 'selected');
						        return false;
						    }
						    return true;
						});
				}
				if ($("select#leg-selector").prop('selectedIndex') < 0)
				{
					$("select#leg-selector").prop('selectedIndex', 0);
				}
				$('#leg-selector').change();
			}
			else
			{
				leg = 1;
				legText = "";
				$('#leg-div').hide();
				$('#leg-selector').hide();
				initializeRadioSelector();
			}
        	}
        );
}

function initializeRadioSelector() {
    $("#radio-selector").empty();
	listRadiosUrl  = baseUrl + "action=radio&cmp=" + cmp + "&cls=" + cls + "&leg=" + leg
    $.getJSON(
    	listRadiosUrl,
    	null,
        function (data) {
			radiosList.length = 0;
            $.each(data, function (idx, val) {
                    			radiosList.push(val);
							}
    			);

		    $.each(radiosList, function (idx, radioInfo) {
		        var option = $('<option />');
		        t = radioInfo[2] == radioInfo[1] ? radioInfo[2] : radioInfo[2] + " (" +radioInfo[1] + ")";
		        option.attr('value', radioInfo[1]).text(t);
		        $('#radio-selector').append(option);
		    });
		
			/* select the first */
			if (radiosList.length >0) {
				if (radioText!="")
				{
					$('#radio-selector option:contains(' + radioText + ')').each(function(){
						    if ($(this).text() == radioText) {
						        $(this).attr('selected', 'selected');
						        return false;
						    }
						    return true;
						});
				}
				if ($("select#radio-selector").prop('selectedIndex') < 0)
				{
					$("select#radio-selector").prop('selectedIndex', 0);
				}
				$('#radio-selector').change();
			}
		}
	);
}

function loadResult() {
	$('#resultContainer').prop('innerHTML',"");
	listResultUrl  = baseUrl + "action=result&cmp=" + cmp + "&cls=" + cls + "&leg=" + leg  + "&radio=" + radio
    $.getJSON(
    	listResultUrl,
    	null,
        function (data) {		
			$('#resultContainer').prop('innerHTML', formatResult(data));
		}
	);

	// move next refresh
	nextrefresh = new Date()
	nextrefresh.setTime(nextrefresh.getTime() + 60000) // one minute from now.
}

function loadStartlist() {
	$('#resultContainer').prop('innerHTML',"");
	listResultUrl  = baseUrl + "action=start&cmp=" + cmp + "&cls=" + cls + "&leg=" + leg  + "&radio=" + radio
    $.getJSON(
    	listResultUrl,
    	null,
        function (data) {		
			$('#resultContainer').prop('innerHTML', formatResult(data));
		}
	);

	// move next refresh
	nextrefresh = new Date()
	nextrefresh.setTime(nextrefresh.getTime() + 60000) // one minute from now.
}

function reload() {

	if (radiosList.length >0) {
		loadResult()
	} 
	else if (legsList.length >0) {
		$('#leg-selector').change();
	}
	else if (classesList.length >0) {
		$('#class-selector').change();
	}
	else if (competitionsList.length >0) {
		$('#competition-selector').change();
	}
	else {
		initializeSelectors();
	}
}

function formatResult(result) {

	head = false;

	html = "<table class='table-striped table-condensed table-bordered'>";
    $.each(result, 
    	function (idx, val) {
			html += "<tr>"
    		if (head == false)
    		{
				html += "<thead>" 
				$.each(result[idx], function(jdx, cell){
					html += "<th>" + cell + "</th>";  
				}); 
				html += "</thead>" 
				html += "<tbody>" 
    			head = true;
    		}
    		else
    		{
				$.each(result[idx], function(jdx, cell){
					html += "<td>" + cell + "</td>";  
				}); 
    		}
			html += "</tr>"
    		
		});
	html += "</tbody>" 
	html += "</table>";
	
	// html += nextrefresh.toString();
	return html;
}

function autorefresh() {
	d = new Date()
	if (d.valueOf() >= nextrefresh.valueOf()) {
		// alert("refresh\n" +d.toString())
		reload();
	}
	else {
		ddif =  parseInt((nextrefresh.valueOf() - d.valueOf()) / 1000);
		$('#reload-button').prop('title',"reload - sker automatisk om " + ddif + " sekunder");	
	}	

	setTimeout("autorefresh()", 1000);		
}