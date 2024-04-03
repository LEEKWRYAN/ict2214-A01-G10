/*

    Reference: https://codeshack.io/basic-login-system-nodejs-express-mysql/

*/

var mysql = require("mysql");
var express = require("express");
var session = require("express-session");
var bodyParser = require("body-parser");
var path = require("path");

var connection = mysql.createConnection({
  host: "localhost",
  user: "login",
  password: "login",
  database: "login"
});

var app = express();
app.use(
  session({
    secret: require("crypto").randomBytes(64).toString("hex"),
    resave: true,
    saveUninitialized: true,
  })
);
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

app.get("/", function (request, response) {
  response.sendFile(path.join(__dirname + "/login.html"));
});

app.post("/auth", function (request, response) {
  var username = request.body.username;
  var password = request.body.password;
  if (username && password) {
    connection.query(
      'SELECT * FROM accounts WHERE username = "' + username + '" AND password = "' + password + '";',
      function (error, results, fields) {
        if (error) {
          // Handle MySQL query error
          console.error("Error executing MySQL query:", error);
          response.send("An error occurred. Please try again later.");
          response.end();
          return;
        }
        if (results && results.length > 0) {
          // Authentication successful
          request.session.loggedin = true;
          request.session.username = username;
          response.redirect("/home");
        } else {
          // No matching user found
          response.send("Incorrect Username and/or Password!");
          response.end();
        }
      }
    );
  } else {
    response.send("Please enter Username and Password!");
    response.end();
  }
});


app.get("/home", function (request, response) {
  if (request.session.loggedin) {
    response.send("Welcome back, " + request.session.username + "!");
  } else {
    response.send("Please login to view this page!");
  }
  response.end(); 
});

// Start the server
app.listen(8080, function() {
    console.log('Server listening on port 8080');
  });