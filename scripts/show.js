var num,
    schedules,
    max,
    curr,
    error,
    success;

/******************************************************************************
 * Shows the table containing the current schedule
 *****************************************************************************/
var showSchedule = function() {
    
    error.style.display = "none";
    success.style.display = "none";
    curr.style.display = "none";
    curr = schedules[num - 1];
    curr.style.display = "table";
    
    var text = "Showing Schedule " + num + " of " + max;
    
    document.getElementById("center").innerHTML = text;
    
    var key = curr.innerHTML.hashCode();
    if(window.localStorage.getItem(key) != null) {
        error.style.display = "block";
    }
    
};

/******************************************************************************
 * Shows the next schedule
 *****************************************************************************/
var next = function() {
    
    return function() {
    
        num++;
        
        if(num > max)
            num = 1;
        
        showSchedule();
        
    };
    
};

/******************************************************************************
 * Shows the previous schedule
 *****************************************************************************/
var previous = function() {
    
    return function() {
        
        num--;
        
        if(num == 0)
            num = max;
            
        showSchedule();
        
    };
    
};

/******************************************************************************
 * Returns true if a row in a table is empty
 *****************************************************************************/
var empty = function(row) {
    
    var cells = row.getElementsByTagName("td");
    for(var i = 0; i < cells.length; ++i)
        if(cells[i].innerHTML != "")
            return false;
    return true;
    
};

/******************************************************************************
 * Removes empty rows from the beginning and end of a table
 *****************************************************************************/
var reduceSchedules = function() {
    
    for(var i = 0; i < max; ++i) {
        
        var schedule = schedules[i];
        var rows = schedule.getElementsByTagName("tr");
        var start = 1;
        
        var node;
        
        //Removes empty rows until the first occupied one
        while(empty(rows[start])) {
            node = rows[start];
            node.parentNode.removeChild(node);
        }
        
        var end = rows.length - 1;
        
        //Removes the empty rows after the last occupied one
        while(empty(rows[end])) {
            node = rows[end];
            node.parentNode.removeChild(node);
            end--;
        }
    }    
    
};

/******************************************************************************
 * Takes a string and converts it into a 32bit integer hashcode. Function 
 * modelled after java's string.hashcode method
 * @author: wes
 * @source: http://werxltd.com/wp/2010/05/13/javascript-implementation-of-javas-string-hashcode-method/
 *****************************************************************************/
String.prototype.hashCode = function(){
	var hash = 0;
	if (this.length == 0) return hash;
	for (var i = 0; i < this.length; i++) {
		var char = this.charCodeAt(i);
		hash = ((hash<<5)-hash)+char;
		hash = hash & hash; // Convert to 32bit integer
	}
	return hash;
};

/******************************************************************************
 * Saves the current schedule's inner HTML to localStorage. The key for each 
 * schedule is a hashcode
 *****************************************************************************/
var save = function() {
    
    return function() {
    
        var table = curr.innerHTML;
        var key = table.hashCode();
        if(window.localStorage.getItem(key) != null) {
            error.style.display = "block";
            success.style.display = "none";
        }
        else {
            window.localStorage.setItem(key, table);
            success.style.display = "block";
            error.style.display = "none";
        }
        
    };
    
};

document.addEventListener("readystatechange", function() {
    
   if(document.readyState === "interactive") {
       
        //Stores the message divs
        error = document.getElementById("error");
        success = document.getElementById("success");
        
        //Stores the number of the schedule currently showing
        num = 1;
       
        //Stores all the schedules (tables) in the document
        schedules = document.getElementsByTagName("table");
       
        //Stores the total nummber of tables in the document
        max = schedules.length;
        
        reduceSchedules();
       
        //Stores the currently showing schedule
        curr = schedules[num];
       
        showSchedule();
        
        document.getElementById("next").onclick = next();
        
        document.getElementById("prev").onclick = previous();
        
        document.getElementById("save").onclick = save();
       
   } 
    
});