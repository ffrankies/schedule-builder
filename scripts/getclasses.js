"use strict";

/******************************************************************************
 * Allows user to select a class, and then deselect it. 
 *****************************************************************************/
var select = function(selected) {
    
    return function() {
        
        //Move div from #classes to #selectedclasses
        var classesdiv = document.getElementById("classes");
        var selclass = classesdiv.removeChild(selected);
        var selecteddiv = document.getElementById("selectedclasses");
        selecteddiv.appendChild(selclass);
        
        //Allow div from #selectedclasses to #classes when deselected
        selclass.onclick = function() {
            
            selclass = selecteddiv.removeChild(selclass);
            classesdiv.appendChild(selclass);
            //Allow div to be selected again
            selclass.onclick = select(selclass);
            
        };
        
    };
    
};

/******************************************************************************
 * Makes classes transferrable between the #classes and #selectedclasses divs
 *****************************************************************************/
var makeTransferrable = function() {
    
    var classesdiv = document.getElementById("classes");
    var classes = classesdiv.children;
    
    for(var i = 0; i < classes.length; ++i) {
        
        classes[i].onclick = select(classes[i]);
        
    }
    
};

/******************************************************************************
 * Prevents already selected divs from displaying in the #classes div
 *****************************************************************************/
var removeSelected = function() {
    
    var classesdiv = document.getElementById("classes");
    var selecteddiv = document.getElementById("selectedclasses");
    var classes = classesdiv.children;
    var selected = selecteddiv.children;
    
    for(var i = 0; i < selected.length; ++i) {
        
        for(var pos = 0; pos < classes.length; ++pos) {
            
            if(classes[pos].innerHTML == selected[i].innerHTML)
                classesdiv.removeChild(classes[pos]);
            
        }
        
    }
    
};

/******************************************************************************
 * Sends out an AJAX request to retrieve classes from specified department, 
 * and populate the #classes div with the response
 *****************************************************************************/
var processAjax = function(deptcode) {
    
    var request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            document.getElementById("classes").innerHTML = request.responseText;
            removeSelected();
            makeTransferrable();
        }
    };
    request.open("GET", "../processing/getclasses.php?deptcode=" + deptcode, true);
    request.send();
    
};

document.addEventListener("readystatechange", function() {
    
    if( document.readyState === "interactive" ) {
        
        //Add an onlick function to all the department divs
        var depts = document.getElementsByClassName("department");
        
        for(var i = 0; i < depts.length; ++i) {

            depts[i].onclick = function() {
                            
                var deptcode = this.innerHTML;
                var index = deptcode.indexOf('|',0);
                deptcode = deptcode.substring(index+2);
                processAjax(deptcode);
                
            };
            
        }
        
        //When generate is clicked on, set a cookie with selected classes
        var link = document.getElementById("generate");
        link.onclick = function() {
            
            var selectedclasses = 
                document.getElementById("selectedclasses").children;
            var cookietext = "selected=";
            
            for(var j = 0; j < selectedclasses.length; ++j) {
                
                var contents = selectedclasses[j].innerHTML;
                var i1 = contents.indexOf('|') + 2;
                var i2 = contents.indexOf('|', i1) - 1;
                contents = contents.substring(i1, i2);
                //contents = contents.replace(/\s+/g, '');
                cookietext += ( contents + "-" );
                
            }
            
            cookietext = cookietext.substring(0, cookietext.length - 1) + ";";
            
            //Makes cookie expire in an 24 hours
            var d = new Date();
            d.setTime(d.getTime() + (24*60*60*1000));
            
            cookietext += ("expires=" + d.toUTCString());
            
            document.cookie = cookietext;
            
            window.location.assign("../show.php");
                    
        };
        
    }

});
