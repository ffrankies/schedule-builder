# Schedule Builder

A Web Application for Creating Schedules

Author: Frank Wanye

Languages Used: PHP, JavaScript, HTML/CSS, MySQL

IDE Used: [Cloud9](http://c9.io)

## Description

This web application was made as part of my Web Applications Development class (May - June, 2016).

It allows a user to log in, select a list of classes, and uses data from the database to produce a set of possible schedules, which the user can then save to local memory and access from within the app.

## The Schedule Builder in Use

### Logging in

![Logging in image](/screenshots/Login.png)

The application features a signup page as well, and some simple error checking on user input. A user has to be signed up and logged in to access the other pages of the app.

### List of Departments

![List of departments image](/screenshots/Departments.png)

Clicking on any department will cause the classes available in that department to be displayed on the webpage next to the departments.

### List of Classes

![List of classes image](/screenshots/Classes.png)

Clicking on any class will add it to the selected classes box, and remove it from the class listing for the department. 

![Selected classes image](/screenshots/Selected.png)

Clicking on any class in the selected classes box will remove it from the selected classes box, and add it back to the class listing for the department.

### Generating Schedules

![Generated schedules 1](/screenshots/Generated.png)

![Generated schedules 2](/screenshots/Generated2.png)

Clicking on the **Generate my Schedules** button will cause the application to come up with all available schedules using the sections of the selected classes stored in the database. These schedules can then be cycled through using the **Next** and **Previous** buttons. 

### Saving Schedules

![Saved schedules](/screenshots/Saved.png)

Clicking on **Save This Schedule** will save the schedule to local storage on the user's browser.

Clicking on **View Saved Schedules** will then show smaller versions of the saved schedules. These schedules can then be deleted by clicking on the **Delete** button under each schedule.

![Enlarged schedules](/screenshots/Enlarge.png)

Clicking on any schedule will enlarge it, so the user can view it more clearly. Clicking on the schedule again will cause it to shrink back to normal size.
