# Polls Website

Marcy Rattner wrote this polls site using [CodeIgniter](http://www.codeigniter.com/) 
3.0.0 and [AngularJS](http://www.angularjs.org/) 1.3.15 for SENG365 at the 
[University of Canterbury](http://canterbury.ac.nz) on June 5, 2015.

## Website features

* There are two main views: the "voter" view and the "admin" view.
* Voters can view all polls and vote in any poll by clicking on its Vote button.
* Admins can see the results of a poll and clear all the votes from a poll (only 
if the poll has more than 0 votes).
* An admin can delete a poll by clicking its Delete button on the poll list. Deleting 
a poll also deletes all of its associated votes.
* The admin view contains a button for creating a new poll, which brings the admin 
to a form. The form cannot be submitted without the required elements filled in.

## Known issues

Loading times are slow between views, and there is "flicker" while the page
waits for data to come in. This could be sped up by using request caching. The
use of the route provider's `resolve` property would eliminate the
flicker but the user would see a blank page while waiting for the promised data
to resolve.

By design, there is no authentication or authorisation to distinguish 
between voters and admins, and anyone can submit more than one vote for the same
poll.

Injection is not protected against, beyond CodeIgniter's default behaviour
for cleaning input and output (e.g. in QueryBuilder methods).

When you add an answer field in the Create Poll form, the new field is not
focused. This could be fixed with a custom directive.

## Outside acknowledgments
I referred to the AngularJS, PHP, and CodeIgniter documentation a great deal,
which have many usage examples that I copied. I also referred to the Mozilla Development 
Network's [JavaScript documentation](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects).

All of the styling is from [Skeleton](http://getskeleton.com/) except for a few rules in
`polls-custom.css`. Skeleton uses external font resources from Google.

The site favicon is from [favicon.cc](http://www.favicon.cc/?action=icon&file_id=750216).

## API and back-end code

A key decision was to use CodeIgniter 3.0.0 instead of 2.x, due to the vital 
new feature of being able to specify HTTP methods in the application routes. 
Therefore, all of the methods in the REST API are clearly laid out in 
the `application/config/routes.php` file.

I decided to output a 404 error only if the database is connected to the
server and the requested resource does not exist. In the case of database
failure, the server throws an exception, resulting in a 500 error coming back
to the client. If a request to create a poll fails, the status code is 400
rather than 404, because the error was due to a malformed request rather than
not being able to find a resource. If no polls exist in the database, a call
to retrieve all polls will result in an empty list and no error.

The "input" and "output" CodeIgniter libraries allow the controller methods
to access HTTP request body data (via `$this->input->raw_input_stream`)
and to set HTTP response codes, headers, and data. All data is sent and
received in JSON format.

I wrote a `MY_Model` class to extend the core `CI_Model` because of the shared 
`load` and `checkResult` methods.

## Database design

Polls and votes are stored in the MySQL database using 3 tables: Polls, 
Options, and Votes. The database is prepopulated with some polls and votes.

When requesting the list of all polls, results are ordered by title.
The options of a poll are always presented in the order of their answer
number (array index).

Foreign key constraints in the database provide built-in error checking so
that a user cannot submit a vote with a poll ID or answer that don't exist. A
deletion of a Poll or Option record will cascade to its children, but an 
update of a Poll or Option when it has children is not allowed. This behaviour
never comes into play when using the API, since there are no "update" actions
in the API and when a poll is deleted, all of its corresponding votes and options
are deleted as well. Only direct database queries will be affected by these 
constraints.

## Front-end code

AngularJS views are located in the `/partials` top-level directory
and the AngularJS app module (where front-end routes are defined) is in the 
`/scripts` top-level directory. The Angular controllers are split 
into separate files in the `/scripts/ctrl` directory so that it is 
easier to distinguish between the behaviour of different app views.

I prefer to use `.$inject` syntax over the array dependency syntax
that the lab code demonstrated. It is much easier to read, and doesn't break
when the JavaScript is minified.

I chose to use a `.catch` instead of `.error` handler
for some AJAX requests so that I could display the response's status message, 
which is not available in the `.error` callback.

## Reflection

I am very glad I decided to implement the back-end API using version 3.0.0
of CodeIgniter, since it introduced the feature of specifying HTTP methods in
application routes. With this feature, implementation of the REST API was fairly
straightforward using normal controller methods, without any extra processing
code.

Building the back-end API was the portion of the assignment that felt the 
most valuable and rewarding to me. The principles that I learned are applicable
to building a back-end in any server-side language. Building the front-end, on 
the other hand, was an exercise in frustration, and I don't believe it taught me
anything besides the quirks and pitfalls peculiar to AngularJS.

