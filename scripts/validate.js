"use strict";

document.addEventListener("readystatechange", function () {
    
    if (document.readyState === "interactive") {
        
        // Event handler for when form is submitted
        var form = document.forms[0];
        form.addEventListener("submit", function (event) {
                
            //Resets error message displays to none
            var messages = document.getElementsByClassName("error");
            for(var i = 0; i < messages.length; i++) {
                messages[i].style.display = "none";
            }
                
            //Makes sure email has at least 3 characters
            var entry = document.getElementById("emailin");
            if(entry.value.length < 3 || entry.value.length > 25) {
                event.preventDefault();
                document.getElementById("emailer").style.display = "block";
            } 
            
            //Makes sure email contains @
            if(entry.value.indexOf('@') < 0) {
                event.preventDefault();
                document.getElementById("emailtypeer").style.display = "block";
            } 
            
            //Validate password box
            entry = document.getElementById("pwdin");
            if(entry.value.length < 3 || entry.value.length > 25) {
                event.preventDefault();
                document.getElementById("passworder").style.display = "block";
            } 
            
            //Validate password box
            entry = document.getElementById("pwdin");
            if(entry.value.length < 3 || entry.value.length > 25) {
                event.preventDefault();
                document.getElementById("passworder").style.display = "block";
            } 
            
            //Validate password box
            entry = document.getElementById("pwdin");
            if(entry.value.length < 3 || entry.value.length > 25) {
                event.preventDefault();
                document.getElementById("passworder").style.display = "block";
            }
            
            //Makes sure name has at least 3 characters
            entry = document.getElementById("namein");
            if(entry.value.length < 3 || entry.value.length > 35) {
                event.preventDefault();
                document.getElementById("nameer").style.display = "block";
            } 
            
        });
    }
});
