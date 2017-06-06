// JavaScript Document

subjectsBySchoolId_Json = new Array();

//uses Dojo - be sure to include it
function fillDisciplineSelectBySchoolId(schoolId)
{
	var targetNode = dojo.byId('discipline');
	
	dojo.create("option", {innerHTML : "loading subject list..." }, targetNode);
	
	//loads the json into the array, so we only load it once
	if(subjectsBySchoolId_Json[schoolId] == undefined)
	{
		//loads the json into the array, so we only load it once
		ajaxLoadSubjectsBySchoolId(schoolId);
		//this function gets called again by the above
		return;
	}
	
	var jsonUrl = "/tandl/elearning/exemplars/ajax_DisciplinesBySchool.php?school="+schoolId;
	
	dojo.empty(targetNode);
	dojo.create("option", { value : 0 , innerHTML : "select Subject" }, targetNode);
	dojo.forEach(subjectsBySchoolId_Json[schoolId], function(row, i)
					   {
						   if(row.disciplineName != '')
						   {
						   dojo.create("option", { value : row.discipline_id, innerHTML : row.disciplineName }, targetNode);
						   }
					   }
				 );
	dojo.attr(targetNode, 'class', '');
	
	var discId = getQueryParameterByName("discipline");
	if(discId && discId != 0)
	  {
		  setSelect('discipline', discId);
		  dojo.attr(targetNode, 'class', 'active');
	  }
				
}





function ajaxLoadSubjectsBySchoolId(schoolId)
{
	var jsonUrl = "/tandl/elearning/exemplars/ajax_DisciplinesBySchool.php?school="+schoolId;
	
	var xhrArgs = {
			url: jsonUrl,
			handleAs: "json",
			
			load: function(data) {
				subjectsBySchoolId_Json[schoolId] = data;
				if(subjectsBySchoolId_Json[schoolId].length)
				{
				  fillDisciplineSelectBySchoolId(schoolId);
				}
			},
			error: function(error) {}
		}
	
	var deferred = dojo.xhrGet(xhrArgs);
  
}


//must include the "/tandl/js/functions.js" and Dojo for this to work!
function setupDisciplineField()
{
	var schoolId = dojo.byId('school').value;
	if(schoolId)
	{
	  fillDisciplineSelectBySchoolId(schoolId);
	}
}

dojo.addOnLoad(setupDisciplineField);