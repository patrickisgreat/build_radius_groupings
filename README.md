build_radius_groupings
======================

A simple series of PHP classes for building "Zoom Level," groupings from database rows with Latitude and Longitude values. This runs a series of trig functions to determine the distance of a primary row from all the other rows in the database and builds an array of arrays grouped accordingly. It currently creates 7 zoom levels. It is currently written to output a JSON data file of specific fields for the Private Lounge Member Locator app but can be easily extended for other apps. 

I hope this comes in handy!
:-)
basic implementation:
___________________________________________________________
The updateMembers.php file instantiates all the classes. 

$db obj / DbConnect class
The db connection object is injected via each constructor and must be instantiated first.

$post object / Post class
handles writing the JSON file after everything is complete. Instantiated with the path in whatever deployment where you need the JSON file to be written.

$getData obj / GetData class
This is a model to get the data you need from the database and pass it back to other classes. 
There's a primary getter, a getter for the markers array for the cluster method, and one getter for the final data file -- pretty straightforward. 

$cluster obj / Cluster class
This is where all the magic happens. Here we call a method in the getData class to grab an array of markers. We pass this array through the cluster method with a distance integer. This method pops each value off the end of this array and measures it's distance from each other marker in the original. If the distance integer is greater than the distance of this comparison we add the compared values to a child array and subsequently add the comparison values to this array. Then the sub array gets added to a parent which is what gets returned -- the parent array of cluster arrays. This array gets passed to the updateDb method which just loops through and updates the userfield table with the cluster grouping values. 





