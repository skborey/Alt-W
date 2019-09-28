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
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.0.min.js"></script>
        <script type="text/javascript" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js">
        </script>
        <script>
            $(document).ready(function () {
                $('#example').DataTable();
            });
        </script>
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
                    <li>
                        <span><span><a href="admin_mail.php">E-mail</a></span></span>
                    </li>
                    <li class="active">
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
                        <h1>Clear File</h1>
                        <div class="breadcrumbs">
                            <a href="index.php">Admin</a> /
                            <a href="admin_file.php">Clear File</a>
                        </div>
                    </div>
                    <br>
                    <div class="select-bar">
                    </div>
                    <br>                        
                    <div class="table">
                        <table id='example' class='display'  cellpadding="0" cellspacing="0" width="600" border="1" align="center">
                            <thead>
                                <tr>
                                    <td colspan="2">
                                        <strong><div align="center"><h3>Clear File in server</h3></strong></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><div align="center">File Name</div></th>
                                    <th><div align="center">Delete</div></th>
                                </tr>
                            </thead>
                            <?php
                            $folder = "../download/";
                            if ($dir = opendir($folder)) {
                                while (($file = readdir($dir)) !== false) {
                                    if ($file != "." && $file != "..") {
                                        echo "<tr><td>" . $file . "</td>";
                                        echo "<form method='post' action='#'>";
                                        echo "<td>
                                        <input type='hidden' name='file_name' value='" . $file . "'>
                                        <div align='center'><input type='submit' name='delete_file' value='Delete File'></div>
                                        </td></tr>";
                                        echo "</form>";
                                    }
                                }
                                closedir($dir);
                            }
                            ?>
                        </table>
                    </div>
                    <?php
                    if (isset($_POST['delete_file'])) {
                        ?>
                        <script type="text/javascript">
                            window.location = "admin_file.php";
                        </script>
                    <?php } ?>
                    <?php
                    if (isset($_POST['delete_file'])) {
                        $filename = $_POST['file_name'];
                        unlink('../download/' . $filename);
                    }
                    ?>
                </div>
                <div id="right-column">
                    <strong class="h">INFO</strong>
                    <div class="box">
                        <h4>AltW Project V1.0.3</h4>
                    </div>
                </div>
            </div>
            <div id="footer"></div>
        </div>
    </body>
</html>