<?php include "header.php"; ?>
<link rel="stylesheet" type="text/css" href="assets/css/contact-form.css">
</head>
<?php include "menu_header.php"; ?>
    <div class="container">
        <div class="row margin-vert-30">
            <div class="col-md-9">
                <div class="headline">
                    <img src="assets/img/contact.png" alt="Contact Us" style="display: block; margin: 0 auto;">
                    <br>
                </div>
                <div class="headline">
                    <center>
                        <b>80 Village No.1 Vichitsongkram Road, Kathu, Kathu District, Phuket, 83120</b>
                    </center>
                    <br>
                </div>
                <?php
                require_once 'connect.php';
                require_once 'config_mail.php';
                require 'PHPMailer/PHPMailerAutoload.php';
                if (isset($_POST) & !empty($_POST)) {
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $subject = $_POST['subject'];
                    $message = $_POST['message'];
                    if (!isset($name) || empty($name)) {
                        $error[] = "Name is required";
                    }
                    if (!isset($email) || empty($email)) {
                        $error[] = "E-Mail is required";
                    }
                    if (!isset($subject) || empty($subject)) {
                        $error[] = "Subject is required";
                    }
                    if (!isset($message) || empty($message)) {
                        $error[] = "Message is required";
                    }
                    if (!isset($error) || empty($error)) {
                        // insert to db
                        $to = "jirayu.ch95@gmail.com";
                        $headers = "From : " . $email;
                        $mail = new PHPMailer();
                        $mail->Host = $smtphost;
                        $mail->SMTPAuth = true;
                        $mail->Username = $smtpuser;
                        $mail->Password = $smtppass;
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;
                        $mail->setFrom('altw@gmail.com', 'AltW PSU Project');
                        $mail->addAddress('jirayu.ch95@gmail.com', 'Jirayu Chinnawong');
                        $mail->Subject = $subject;
                        $mail->Body = "Name : " . $name . "\n" .
                                "E-Mail : " . $email . "\n" .
                                "Subject : " . $subject . "\n" .
                                "Message : " . $message . "\n" .
                                "\n\n\n" .
                                "==========================================\n" .
                                "AltW PSU Project" . "\n" .
                                "==========================================";
                        if (!$mail->send()) {
                            echo '<div class="alert danger">
                                                    <span class="closebtn">&times;</span>
                                                            <strong>Danger!</strong> Message could not be sent. <br> Mailer Error: '
                            . $mail->ErrorInfo
                            . '</div>';
                        } else {
                            $sql = "INSERT INTO contact (name, email, subject, message) VALUES ('{$name}', '{$email}', '{$subject}', '{$message}')";
                            if (mysqli_query($connection, $sql)) {
                                echo '<div class="alert success">
                                                      <span class="closebtn">&times;</span>
                                                      <strong>Success!</strong> Message has been sent, we will get back to you soon.
                                                      </div>';
                            }
                        }
                    }
                }
                ?>
                <form method="post" class="col-md-10 col-md-offset-1">
                    <div class="controls">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Name</label>
                                    <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Your Full Name" required autofocus>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email address</label>
                                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter Your Email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Subject</label>
                                    <input type="text" name="subject" class="form-control" id="exampleInputPassword1" placeholder="Enter Your Subject" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Message</label>
                                    <textarea style="resize: none;" class="form-control" name="message" rows="3" placeholder="Enter Your Query Here" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="ta__right" style="float: right;">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                    <input type="reset" class="btn btn-primary" value="Clear">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <br>
            <div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <b>
                                <center>Contact Info</center>
                            </b>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <center>&nbsp;&nbsp;
                            <img src="assets\img\TE+PSU_logo_TH2.png" height="260px">
                            <br>
                            <br>
                        </center>
                        <ul class="list-unstyled">
                            <li>
                                <strong class="color-primary">Monday-Saturday:</strong>
                                <br>&nbsp;&nbsp;&nbsp;9 a.m. to 6 p.m.</li>
                            <li>
                                <strong class="color-primary">Sunday:</strong>
                                <br>&nbsp;&nbsp;&nbsp;Closed</li>
                        </ul>
                    </div>
                    <div class="panel-footer">
                        <ul class="list-unstyled">
                            <li>
                                <i class="fa-phone color-primary"></i>0-7627-6012-13</li>
                            <li>
                                <i class="fa-home color-primary"></i>
                                <a target="blank" href="http://www.phuket.psu.ac.th">www.phuket.psu.ac.th</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <hr class="margin-vert-40">
    </div>
    <div id="base" class="container padding-vert-30">
        <div class="row">
            <div class="col-md-6">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.0112259143048!2d98.3502991491014!3d7.893893494285219!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30503034ccf5b0bf%3A0x7df5467aad0bbdf9!2sPrince+of+Songkla+University%2C+Phuket+Campus!5e0!3m2!1sen!2sth!4v1512370322900"
                        width="1030" height="210" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php include "menu_footer.php"; ?>
    <?php include "footer.php"; ?>
    <script type="text/javascript" charset="utf-8" src="assets/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" charset="utf-8" src="assets/js/script.js"></script>
    <script type="text/javascript" charset="utf-8" src="assets/js/tmStickUp.js"></script>
    <script type="text/javascript" charset="utf-8" src="assets/js/TMForm.js"></script>
    <script type="text/javascript" charset="utf-8" src="assets/js/modal.js"></script>
    </body>
</html>