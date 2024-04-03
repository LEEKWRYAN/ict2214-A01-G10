<!DOCTYPE html>
<html lang="en">
<!--Common Headers-->
<?php
    require_once 'common_header.php';
?>

<?php
    require_once 'common_session_no.php';
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
        <iframe src="https://websechomeserver.ddns.net:3101" frameborder="0"></iframe>
    </div>
</div>

</body>
</html>
