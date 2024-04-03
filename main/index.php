
<!DOCTYPE html>
<html lang="en">
    
<!--Common Headers-->
<?php 
    require_once 'common_header.php'; 
?>

<?php 
    require_once 'common_session_no.php'; 
?>

<head>
    <title>Dashboard | Protect The Flag</title>

    <link rel="stylesheet" href="style/bootstrap.min.css" />

    <style>
        .card img 
        {
            height: 280px;
        }
    </style>

</head>

<body class="bg-light">
    <div class="container">

        <div class="text-center my-5">
            <h1>Protect The Flag - Challenges</h1>
            <a href="logout.php" class="btn btn-warning" style="position: absolute; top: 10px; right: 10px;">Log Out</a>
            <hr/> <!-- line break -->
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card mb-5 shadow-sm">
                    <img src="image/bear5.jpg" class="img-fluid" />

                    <div class="card-body">
                        <div class="card-title">
                            <h2>Challenge 1 - XSS </h2>
                        </div>
                        <div class="card-text">
                            <p>
                                XSS (Cross-Site Scripting) is a vulnerability commonly found in web applications that allows attackers to inject malicious 
                                scripts into web pages viewed by other users. These injected scripts execute within the context of the victim's browser, 
                                enabling attackers to steal sensitive information, hijack user sessions, or deface websites. 
                                By exploiting XSS vulnerabilities, attackers can manipulate input fields, such as search forms or comment sections, 
                                to inject scripts that execute when other users view the affected page. This can lead to unauthorized access to cookies, 
                                session tokens, or other sensitive data stored in the user's browser, compromising their privacy and security.
                            </p>
                        </div>
		                <a href="#" onclick="window.location.href = window.location.origin + '/xss.php';" class="btn btn-outline-primary rounded-0 float-end">Start</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card mb-5 shadow-sm">
                    <img src="image/bear6.jpg" class="img-fluid" />

                    <div class="card-body">
                        <div class="card-title">
                            <h2>Challenge 2 - SQLi </h2>
                        </div>
                        <div class="card-text">
                            <p>
                                SQLi (SQL Injection), on the other hand, is a type of vulnerability that occurs when attackers inject malicious SQL queries into 
                                input fields or query parameters of web applications. This allows them to manipulate the underlying SQL queries executed by the 
                                application's database server. By crafting specially crafted input, attackers can bypass authentication mechanisms, extract sensitive 
                                information from databases, modify or delete data, or even take control of the entire database server. SQLi attacks pose a serious threat 
                                to the security of web applications and can result in data breaches, data loss, and unauthorized access to sensitive information, 
                                making them a common target for malicious actors seeking to exploit vulnerabilities in web applications.
                            </p>
                        </div>
                        <a href="#" onclick="window.location.href = window.location.origin + '/sqli.php';" class="btn btn-outline-primary rounded-0 float-end">Start</a>                    </div>
                </div>
            </div> 
	</div>
</body>

</html>
