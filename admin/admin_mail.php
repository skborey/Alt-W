<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <title>Alternative Word Suggestion System for English Writings</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="shortcut icon" type="image/ico" href="../assets/icon/favicons.ico">
        <link rel="apple-touch-icon" href="../assets/icon/apple-touch-icon.png">
        <style media="all" type="text/css">
            @import "../assets/css/all.css";
        </style>
        <link rel="stylesheet" type="text/css" href="../assets/css/chat.css">
    </head>
    <body>
        <div id="main">
            <div id="header">
                <a href="../index.php" class="logo" target="_blank">
                    <h1 style="color:purple;">
                        Alternative Word Suggestion System for English Writings Project
                    </h1>
                </a>
                <ul id="top-navigation">
                    <li>
                        <span><span><a href="index.php">File Upload</a></span></span>
                    </li>
                    <li>
                        <span><span><a href="admin_log.php">Word list</a></span></span>
                    </li>
                    <li class="active">
                        <span><span><a href="admin_mail.php">E-mail</a></span></span>
                    </li>
                    <li>
                        <span><span><a href="admin_file.php">Clear File</a></span></span>
                    </li>                    
                </ul>
            </div>
            <div id="middle">
                <div id="left-column">
                    <h3>Main</h3>
                    <ul class="nav">
                        <li>
                            <a href="../index.php" target="_blank">Home</a>
                        </li>
                        <li>
                            <a href="index.php">File Upload</a>
                        </li>
                        <li>
                            <a href="admin_log.php">Word list</a>
                        </li>
                        <li>
                            <a href="admin_mail.php">E-mail</a>
                        </li>
                        <li>
                            <a href="admin_file.php">Clear File</a>
                        </li>
                    </ul>
                </div>
                <div id="center-column">
                    <div class="top-bar">
                        <h1>E-Mail</h1>
                        <div class="breadcrumbs">
                            <a href="index.php">Admin</a> /
                            <a href="admin_mail.php">E-Mail</a>
                        </div>
                    </div>
                    <br />
                    <div class="select-bar">
                    </div>
                    <form name="frmMain" action="#" method="POST">
                        <?php
                        require_once '../connect.php';
                        $sql = "SELECT * FROM contact";
                        $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
                        ?>
                        <div class="container">
                            <div class="container">
                                <div align="center">
                                    <strong><h3>Mail in mysql</h3></strong>
                                    <span class="time-right">
                                        Delete all &nbsp;&nbsp;
                                        <input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="ClickCheckAll(this);">
                                    </span>
                                </div>
                            </div>
                            <?php
                            $i = 0;
                            while ($objResult = mysqli_fetch_array($result)) {
                                $i++;
                                ?>
                                <div class="container">
                                    <br>
                                    <img src="https://i.imgur.com/DY6gND0.png" alt="Avatar" style="width:100%;">
                                    <span><b>Name</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span><?php echo $objResult["name"]; ?> </span><br>
                                    <span><b>E-mail</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                    <span><?php echo $objResult["email"]; ?> </span><br>
                                    <span><b>Subject</b></span>&nbsp;&nbsp;&nbsp;&nbsp; 
                                    <span><?php echo $objResult["subject"]; ?> </span><br>
                                    <span><b>Message</b></span>&nbsp;&nbsp;
                                    <span style="text-align: justify; text-justify: inter-word; width:450px; overflsow: auto">
                                        <?php echo $objResult["message"]; ?>
                                    </span>
                                    <p></p>
                                    <span class="time-right"><?php echo $objResult["time"]; ?></span><br>
                                    <span class="time-right">
                                        Delete &nbsp;&nbsp;
                                        <input type="checkbox" name="chkDel[]" id="chkDel<?php echo $i; ?>"
                                               value="<?php echo $objResult["id"]; ?>">
                                    </span>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <input type="hidden" name="hdnCount" value="<?php echo $i; ?>">
                        <?php
                        if (isset($_POST['btnDelete'])) {
                            ?>
                            <script type="text/javascript">
                                window.location = "admin_mail.php";
                            </script>
                        <?php } ?>
                        <?php
                        error_reporting(error_reporting() & ~E_NOTICE);
                        require_once '../connect.php';
                        for ($i = 0; $i < count($_POST["chkDel"]); $i++) {
                            if ($_POST["chkDel"][$i] != "") {
                                $sql = "DELETE FROM contact WHERE id = '" . $_POST["chkDel"][$i] . "' ";
                                $result = mysqli_query($connection, $sql) or die(mysqli_error($connection));
                            }
                        }
                        ?>
                        <script language="JavaScript">
                            function ClickCheckAll(vol) {
                                var i = 1;
                                for (i = 1; i <= document.frmMain.hdnCount.value; i++) {
                                    if (vol.checked == true) {
                                        eval("document.frmMain.chkDel" + i + ".checked=true");
                                    } else {
                                        eval("document.frmMain.chkDel" + i + ".checked=false");
                                    }
                                }
                            }
                        </script>
                </div>
                <div id="right-column">
                    <strong class="h">INFO</strong>
                    <div class="box">
                        <h4>AltW Project V1.0.3</h4>
                    </div>
                    <br><br><br><br><br><br><p></p>
                    <br><br><br><center><input type="submit" name="btnDelete" value="Delete"></center><br><br>
                    </form>
                </div>
            </div>
            <div id="footer"></div>
        </div>
    </body>
</html>