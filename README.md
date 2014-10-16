build_radius_groupings
======================

A simple PHP class for building "Zoom Level," groupings from database rows with Latitude and Longitude values. This runs a series of trig functions to determine the distance of a primary row from all the other rows in the database and sets groupings accordingly. It currently creates 3 zoom levels -- 300miles, 150miles, and 50miles but can be easily extended to have more granularity. 


The queries in the following methods (distanceQuery(), primaryQuery(), and updateQuery(),  can be updated to your table name and field names. 

The object is instantiated with the DB host, User, PW, and table properties to use -- these are set in the constructor method. 

The buildRadians method can add new records to existing groups, regroup deletions, and create new unique groups.

I hope this comes in handy!

:-)
