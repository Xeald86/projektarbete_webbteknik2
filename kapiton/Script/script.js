// JQuery setup
$.ajaxSetup ({  
    cache: false
});  


/**
 * Variable-information
 * sec = section string, defines the section that are being requested/cashed/etc 
 */
var Kapiton = {};
Kapiton.Ajax = {
	Cashe: {
		sectionCashe: [],
		
		//Loads cashed data for section
		loadSectionCashe: function(sec) {
			var cashe = this.getCasheBySection(sec);
			$('#content').html(cashe[0].content);
		},
		
		//Checks if cashe is stored regardless of when its updated
		sectionIsCashed: function(sec) {
			if(this.sectionCashe.length == 0)
				return false;
				
			var found = this.getCasheBySection(sec);
			if(found.length >= 1)
				return true;
			return false;
		},
		
		//Checks if cashe is stored for a section within the last 2 minutes.
		sectionIsCashedAndNew: function(sec) {
			if(this.sectionCashe.length == 0)
				return false;
			
			var found = this.getCasheBySection(sec);
			if(found.length >= 1) {
				var now = new Date();
				//If cashe has been cashed for more then 2 minutes
				if(now - found[0].updated >= 180000)
					return false;
				else
					return true;
			}
			return false;
		},
		
		//Returns array of cashed data for a section
		getCasheBySection: function(sec) {
			return $.grep(this.sectionCashe, function(e){ return e.section == sec; });
		},
		
		//Adds cashe
		addSectionCashe: function(sec, data) {
			var now = new Date();
			
			//Is there old cashe saved?
			var old = this.getCasheBySection(sec);
			
			//Remove this cashe
			if(old.length >= 1) {
				this.sectionCashe = jQuery.grep(this.sectionCashe, function(e) {
				  return e.section != old[0].section;
				});
			}
			
			// Add new cashed data
			this.sectionCashe.push({section: sec, content: data, updated: now});
		}
	},
	
	//Cets content for section
    getContent: function(sec) {
    	$('#content').html('Loading...');
    	
    	//Is section cashed recently? within 2min. Also is there a connection?
    	if(this.Cashe.sectionIsCashedAndNew(sec) && navigator.onLine)
    		this.Cashe.loadSectionCashe(sec);
    	else if(navigator.onLine)
        	this.Request('get', 'sec='+sec, 'content', true, sec);
        else if(this.Cashe.sectionIsCashed(sec)) {
        	var cashe = Kapiton.Ajax.Cashe.getCasheBySection(sec);
        	$('#content').html(cashe[0].content);
        }
        else
        	$('#content').html('Cant find a internet-connection!');
    },
    
    //Saves content of a section
    saveContent: function(sec, param) {
    	//If online
    	if(navigator.onLine) {
    		$('#content').html('Saving...');
    		this.Request('get', 'sec='+sec+'&a=save&'+param, 'content', false, sec);
    	}
    	
    	//If offline
    	else
        	alert('Cant do this action when offline!');
    },
    
    //Ajax-request
    Request: function(reqType, param, goal, cashe, sec) {
    	var result = $.ajax({
	        url: 'Ajax/Ajax.php?'+param,
	        type: reqType,
	        dataType: 'html',
	        success: function (data) {
	        	//Cashe data
	        	if(cashe) {
	        		Kapiton.Ajax.Cashe.addSectionCashe(sec, data);
        		} else {
        			//Remove cashed data
        			Kapiton.Ajax.Cashe.sectionCashe = [];
        		}
	        	$('#'+goal).html(data);
	       },
	       error: function(jqXHR, textStatus, errorThrown) {
	       		var cashe = Kapiton.Ajax.Cashe.getCasheBySection(sec);
				if(cashe.length >= 1)
	       			$('#'+goal).html(cashe[0].content);
	       		else 
	       			$('#'+goal).html('Error: '+jqXHR.status+'<br />Please try again at a later time!');
	       }
	    });
    }
};




//Handles network-changes
window.addEventListener("offline", function(e) {
	$('#netStatus').removeClass('netOnline');
	$('#netStatus').addClass("netOffline");
  	$('#netStatus').html('Offline');
}, false);
window.addEventListener("online", function(e) {
	$('#netStatus').removeClass('netOffline');
  	$('#netStatus').addClass("netOnline");
  	$('#netStatus').html('Online');
}, false);




// Set function to main-menu options
$(document).on('click', "div.menuOpt", function() {
	if($(this).attr("class").indexOf("selectedOpt") < 0)
 	{
		$(".selectedOpt").toggleClass("selectedOpt");
	    $(this).toggleClass("selectedOpt");
	    
	    //This is where the magic happens
	    Kapiton.Ajax.getContent($(this).attr("id"));
   }
});


$(document).on('click', "#settingsSubmit", function() {
	Kapiton.Ajax.saveContent('settings', 'api='+$('#api').val()+'&cell='+$('#cell').val());
});

$(document).on('click', "#buyOrderSubmit", function() {
	Kapiton.Ajax.saveContent('buy', 'bc='+$('#bcOrder').val()+'&sek='+$('#sekOrder').val());
});

$(document).on('click', "#sellOrderSubmit", function() {
	Kapiton.Ajax.saveContent('sell', 'bc='+$('#bcOrder').val()+'&sek='+$('#sekOrder').val());
});

$( document ).ready(function() {
  Kapiton.Ajax.getContent('overview');
});