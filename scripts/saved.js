/******************************************************************************
 * Takes a string and converts it into a 32bit integer hashcode. Function 
 * modelled after java's string.hashcode method
 * @author: wes
 * @source: http://werxltd.com/wp/2010/05/13/javascript-implementation-of-javas-string-hashcode-method/
 *****************************************************************************/
String.prototype.hashCode = function() {
	var hash = 0;
	if (this.length == 0) return hash;
	for (var i = 0; i < this.length; i++) {
		var char = this.charCodeAt(i);
		hash = ((hash<<5)-hash)+char;
		hash = hash & hash; // Convert to 32bit integer
	}
	return hash;
};

document.addEventListener("readystatechange", function() {
    
    if(document.readyState === "interactive") {
        
        /**********************************************************************
         * Gets all delete buttons and tables, and assigns the appropriate 
         * onclick functionality to each of them
         *********************************************************************/
        var setButtons = function() {
            
            var deleteButtons = document.getElementsByClassName("delete");
            var tables = document.getElementsByTagName("table");

            for(var i = 0; i < deleteButtons.length; ++i) {
                
                deleteButtons[i].onclick = deleteTable(i);
                tables[i].onclick = enlarge(tables[i]);
                
            }
            
        };
        
        /**********************************************************************
         * Makes selected table larger when clicked on
         *********************************************************************/
        var enlarge = function(table) {
            
            return function() {
                
                table.parentNode.style.width = "98%";
                table.style.fontSize = "1.5em";
                table.clientHeight = "1000px";
                table.onclick = reduce(table);
            };
            
        };
        
        /**********************************************************************
         * Makes selected table smaller when clicked on
         *********************************************************************/
        var reduce = function(table) {
            
            return function() {
                
                table.parentNode.style.width = "48%";
                table.style.fontSize = "1em";
                table.clientHeight = "400px";
                table.onclick = enlarge(table);
                
            };
            
        };
        
        /**********************************************************************
         * When a delete button is clicked, deletes the corresponding table 
         * from localStorage and removes it from page
         *********************************************************************/
        var deleteTable = function(i) {
            
            return function() {
                
                var tables = document.getElementsByTagName("table");
                var key = tables[i].innerHTML.hashCode();
                window.localStorage.removeItem(key);
                var scheduleDivs = document.getElementsByClassName("schedule");
                schedules.removeChild(scheduleDivs[i]);
                setButtons();
                
            };
            
        };
        
        //Appends all schedules in localStorage to the #schedules div
        var schedules = document.getElementById("schedules");
        
        for(var i = 0; i < window.localStorage.length; ++i) {
            
            var table = window.localStorage.getItem(window.localStorage.key(i));  
            schedules.innerHTML += ("<div class='schedule'><table>" + table + 
                    "</table><div class='delete'>Delete</div></div>");
            
        }
        
        setButtons();
       
    } 
    
});