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
                    <li class="active">
                        <span><span><a href="index.php">File Upload</a></span></span>
                    </li>
                    <li>
                        <span><span><a href="admin_log.php">Word list</a></span></span>
                    </li>
                    <li>
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
                        <h1>File Upload</h1>
                        <div class="breadcrumbs">
                            <a href="index.php">Admin</a> /
                            <a href="index.php">File Upload</a>
                        </div>
                    </div>
                    <br>
                    <div class="select-bar">
                    </div>
                    <form name="frmMain" action="#" method="POST">
                        <?php
                        include_once '../config.php';
                        $sql = "SELECT * FROM files_resource";
                        $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                        ?>
                        <br>                        
                        <div class="table">
                            <table id='example' class='display' cellpadding="0" cellspacing="0" width="600" border="1" align="center">
                                <thead>
                                    <tr>
                                        <td colspan="4">
                                            <strong><div align="center"><h3>File in mysql</h3></strong></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><div align="center">ID</div></th>
                                        <th><div align="center">File Name</div></th>
                                        <th><div align="center">Upload By</div></th>
                                        <th>
                                            <div align="center">
                                                Delete
                                                <input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="ClickCheckAll(this);">
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <?php
                                $i = 0;
                                while ($objResult = mysqli_fetch_array($result)) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td><div align="center"><?php echo $objResult["id"]; ?></div></td>
                                        <td><div align="center"><?php echo $objResult["file_name"]; ?></div></td>
                                        <td><div align="center"><?php echo $objResult["upload_by"]; ?></div></td>
                                        <td align="center">
                                            <input type="checkbox" name="chkDel[]" id="chkDel<?php echo $i; ?>"
                                                   value="<?php echo $objResult["id"]; ?>">
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>

                        <input type="hidden" name="hdnCount" value="<?php echo $i; ?>">
                        <?php
                        if (isset($_POST['btnDelete'])) {
                            ?>
                            <script type="text/javascript">
                                window.location = "index.php";
                            </script>
                        <?php } ?>

                        <?php
                        error_reporting(error_reporting() & ~E_NOTICE);
                        include_once '../config.php';
                        for ($i = 0; $i < count($_POST["chkDel"]); $i++) {
                            if ($_POST["chkDel"][$i] != "") {
                                $sql = "DELETE FROM files_resource WHERE id = '" . $_POST["chkDel"][$i] . "' ";
                                $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                            }
                        }
                        ?>
                        <script language="JavaScript">
                            $('#example').dataTable({
                                "ordering": false
                            });
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
                    <br><br><br><br><br><br><br><br><br><br><br>
                    <br><br><br><center><input type="submit" name="btnDelete" value="Delete"></center><br><br>
                    </form>
                </div>
            </div>
            <div id="footer"></div>
        </div>
    </body>
</html>