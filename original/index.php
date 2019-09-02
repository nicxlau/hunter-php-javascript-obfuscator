<?php
require_once 'HunterObfuscator.php';
$code = '';
if (isset($_POST['submitCode'])) {
    if($_POST['code_type'] == 'js')     
        $hunter = new HunterObfuscator($_POST['code']);
    else
        $hunter = new HunterObfuscator($_POST['code'], true);
    if(!empty($_POST['code_exp']))
        $hunter->setExpiration($_POST['code_exp']);
    if(!empty($_POST['code_dn']))
    {
        $domains = explode(',', $_POST['code_dn']);
        foreach ($domains as $domain)
            $hunter->addDomainName($domain);
    }
    $code = $hunter->Obfuscate();
}
?>
<html>
<head>
    <title>PHP Javascript Obfuscator</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet">
    <style>
        .brand {
            font-family: 'Righteous', cursive;
            text-decoration: none;
            color: white;
        }
    </style>
    <script>
        function setWarning(text) {
            document.getElementById('warn').innerText = text;
        }
    </script>
</head>
<body style="background-color: #009fd1;">

<div class="container">
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <h1 class="navbar-text brand">PHP JavaScript Obfuscator</h1>
        </div>
    </div>
    <div class="clearfix" style="padding-top: 10px"></div>
    <form method="post" action="">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        Your code
                    </div>
                    <div class="panel-body text-center">
                        <textarea class="form-control" placeholder="Enter your Javascript or HTML code" style="height: 250px;resize: none" title="" name="code" required><?php if(isset($_POST["code"])) echo $_POST["code"]; ?></textarea>
                        <p id="warn" class="text-warning"></p>
                    </div>
                    <div class="panel-footer text-center">
                        <input class="btn btn-primary" type="submit" name="submitCode" value="Obfuscate now!">
                    </div>
                </div>
                <?php if(!empty($code)): ?>
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        Your obfuscated code
                    </div>
                    <div class="panel-body text-center">
                        <textarea class="form-control" onclick="this.focus();this.select();" style="height: 250px;resize: none" title="" readonly><?= $code ?></textarea>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        Options
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label">Code input type:</label>
                            <ul style="list-style-type: none">
                                <li><label><input onclick="setWarning('');" type="radio" name="code_type" value="js" checked> JavaScript</label></li>
                                <li><label><input onclick="setWarning('Please remove any comments from your Javascript codes inside your HTML code.');" type="radio" name="code_type" value="html"> HTML</label></li>
                            </ul>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label for="code_exp" class="control-label">Expiration time:</label><br/>
                            <input class="form-control" type="text" name="code_exp" id="code_exp" placeholder="+5 day"><br/>
                            <span class="text-muted">Example: +1 day, +2 week, next Thursday, etc...</span>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label for="code_dn" class="control-label">Allowed domain names:</label><br/>
                            <input class="form-control" type="text" name="code_dn" id="code_dn" placeholder="example1.com,example2.com"><br/>
                            <span class="text-muted">Example: example1.com,example2.com...</span>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        Please note...
                    </div>
                    <div class="panel-body">
                        <p>Although this can provide a high security level, a potentially thief can try to de-obfuscate and reach a closer code to the original one due to the public and open architecture of JavaScript.</p>
                        <p>So it's not recommended to use this to protect sensible information.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>


</div>
</body>
</html>