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
$statusFile = '/var/www/html/status_xss.txt';
$status = trim(file_get_contents($statusFile));
$iframeSource = $status == 'active' ? "https://websechomeserver.ddns.net:3103" : "https://websechomeserver.ddns.net:3101";
?>
<html>
<head>
    <title>XSS Challenge Instructions</title>
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
        <h2>XSS Challenge Instructions</h2>
        <ol>

	    <li>Go into root</li>
            <li>Run: <code>apt-get update</code></li>
            <li>Install MySQL server: <code>apt install mysql-server</code></li>
            <li>Start the mysql service: <code>service mysql start</code></li>
            <li>Access MySQL as root: <code>sudo mysql -u root</code>
                <br>Execute the following commands in MySQL:
                <ul>
                    <li>CREATE USER 'admin'@'localhost' IDENTIFIED BY '!Password123';</li>
                    <li>ALTER USER 'admin'@'localhost' IDENTIFIED WITH mysql_native_password BY '!Password123';</li>
                    <li>GRANT CREATE, SELECT, INSERT, DELETE, DROP, ALTER, SHOW DATABASES ON *.* TO 'admin'@'localhost';</li>
                    <li>FLUSH PRIVILEGES;</li>
                </ul>
                <br>Exit MySQL: <code>exit</code>
            </li>
            <li>Access MySQL as admin: <code>sudo mysql -u admin -p</code>
                <br>Password: <code>!Password123</code>
                <br>Execute the following commands in MySQL:
                <ul>
                    <li>CREATE DATABASE app_db;</li>
                    <li>USE app_db;</li>
                    <li>CREATE TABLE comments (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        comment_text TEXT,
                        author VARCHAR(500),
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    );</li>
                </ul>
                <br>Exit MySQL: <code>exit</code>
            </li>

            <li>Start the nodeJs app: <code>node /config/Desktop/XSS-1/xss.js</code></li>
            <li>Open the web browser and navigate to <code>http://localhost:8080</code></li>
            <li>User performs a simple stored XSS by submitting <code>&lt;script&gt;alert("hacked")&lt;/script&gt;</code> as input. Observe the result.</li>
            <li>Login to the database to remove the malicious input. Use the following commands:
                <br><code>mysql -u admin -p</code>
                <br>Password: <code>!Password123</code>
            </li>
            <li>Stop the nodeJs app, open <code>xss.js</code> file and modify it to strip the ‘&lt;’ & ‘&gt;’ to prevent the XSS attack.</li>
            <li>After modifying, start up the nodeJs app again and test using the same <code>&lt;script&gt;</code> as input.</li>
            <li>If the webpage does not show an alert pop-up, you have completed this challenge.</li>
            <li>Repeat if unsuccessful in defending.</li>
            <li>Bonus: Try other methods of sanitizing the input such as by encoding special characters.</li>
        </ol>
    </div>
    <div class="iframe-container">
        <iframe src="<?php echo htmlspecialchars($iframeSource); ?>" width="100%" height="600" frameborder="0"></iframe>
    </div>
</div>

</body>
</html>
