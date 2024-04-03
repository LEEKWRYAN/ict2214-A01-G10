// Import necessary modules
var mysql = require('mysql');
var express = require('express');
var ejs = require('ejs');
var bodyParser = require('body-parser'); // Import body-parser module

// Create Express application
var app = express();

// Use body-parser middleware to parse JSON and URL-encoded request bodies
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Create MySQL connection
var con = mysql.createConnection({
  host: "localhost",
  user: "admin",
  password: "!Password123",
  database: "app_db"
});

con.connect(function(err){
  if (err) throw err;
  console.log("Connected to MySQL database!");
});

// Set the view engine to EJS
app.set('view engine', 'ejs');

// Handle GET request for the home page
app.get('/', function(req, res) {
  // Fetch data from MySQL database
  con.query("SELECT * FROM comments", function (err, result, fields) {
    if (err) throw err;

    // Render the index.ejs template with data
    res.render('index', { data: result });
  });
});

// Handle POST request for submitting comments
app.post('/addcomment', function(req, res) {
  var uname = req.body.uname; // User name input
  var comment = req.body.comment; // Comment input

  // Insert the form data into the database WITHOUT SANITIZATION
  var sql = "INSERT INTO comments (author, comment_text) VALUES (?, ?)";
  con.query(sql, [uname, comment], function (err, result) {
    if (err) throw err;
    console.log("1 record inserted");

    // Redirect to the home page after insertion
    res.redirect('/');
  });
});

// Start the server
app.listen(8080, function() {
  console.log('Server listening on port 8080');
});
