<?php include "header.php"; ?>
<link rel="stylesheet" href="assets/css/support.css">
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<?php
error_reporting(error_reporting() & ~E_NOTICE);
session_start();
include_once 'config.php';
if (is_array($_SESSION['log']) || is_object($_SESSION['log'])) {
    foreach ($_SESSION['log'] as $log) {
        $pos = $log->pos;
        $before = $log->before;
        $after = $log->after;
        $sql = "SELECT * FROM logs WHERE pos='" . $pos . "' and before_word = '" . $before . "' and after_word = '" . $after . "'";
        $query_result = $link->query($sql);
        if ($query_result->num_rows > 0) {
            //update    
            $result = $query_result->fetch_assoc();
            $count_word = $result['count_word'] + 1;
            $sql2 = "UPDATE logs SET count_word = '" . $count_word . "' WHERE pos='" . $pos . "' and before_word = '" . $before . "' and after_word = '" . $after . "'";
            $query = mysqli_query($link, $sql2) or die(mysqli_error($link));
        } else {
            //insert
            $sql3 = "INSERT INTO logs(pos, before_word, after_word, count_word) VALUE"
                    . "('" . $pos . "','" . $before . "','" . $after . "',"
                    . "1)";
            mysqli_query($link, $sql3) or die(mysqli_error($link));
        }
    }
}
include_once 'config.php';
$sql_retrieve_content = "SELECT * FROM `files_resource` WHERE `id` = "
        . $_SESSION['ss_id_file'] . " AND `file_name` = '" . $_SESSION["ss_filename"] . "'";
$result_set = mysqli_query($link, $sql_retrieve_content);
if (!$result_set) {
    
} else {
    while ($row = mysqli_fetch_array($result_set)) {
        $title = $row['file_name'];
        $upload_by = $row['upload_by'];
        if ($row['upload_by'] == "File") {
            $temp_title = explode(".", $row['file_name']);
            $title = "";
            for ($i = 0; $i < count($temp_title) - 1; $i = $i + 1)
                $title .= $temp_title[$i];
        }
        $file_content = $row['content'];
    }
}
?>
<?php include "menu_header.php"; ?>
    <div class="container" id="thecontent">
        <div class="row margin-vert-30">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12"><br>
                        <center>
                            <div class="image-hover">
                                <img src="assets/img/portfolio/thank.png" alt="thank" width="460px"><br>
                            </div>
                        </center>
                    </div>
                    <div id="change"></div>
                    <?php
                    if (!$result_set) {
                        echo '<center><h2><b>Can not find your file. Please try again.<b></h2> <br><a href="index.php" type="button" class="btn btn-lg btn-danger">Try again</a></center></center><hr class="margin-vert-40">';
                    }
                    if ($_POST['submit_1']) {
                        error_reporting(error_reporting() & ~E_NOTICE);
                        session_start();
                        include('config.php');
                        include('report/vsword/VsWord.php');
                        require_once('report/fpdf181/fpdf.php');
                        if (isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response']) {
                            $secret = "6LdMBxYUAAAAAHBGWk77p790Y195jUSASgAJ2ljb";
                            $ip = $_SERVER['REMOTE_ADDR'];
                            $captcha = $_POST['g-recaptcha-response'];
                            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip$ip");
                            $array = json_decode($response, TRUE);
                        }
                        $sql_retrieve_content = "SELECT * FROM `files_resource` WHERE `id` = "
                                . $_SESSION['ss_id_file'] . " AND `file_name` = '" . $_SESSION["ss_filename"] . "'";
                        $result_set = mysqli_query($link, $sql_retrieve_content) or die(mysqli_error($link));
                        while ($row = mysqli_fetch_array($result_set)) {
                            $title = $row['file_name'];
                            $upload_by = $row['upload_by'];
                            if ($row['upload_by'] == "File") {
                                $temp_title = explode(".", $row['file_name']);
                                $title = "";
                                for ($i = 0; $i < count($temp_title) - 1; $i = $i + 1)
                                    $title .= $temp_title[$i];
                            }
                            $file_content = $row['content'];
                        }
                        //Name with space PDF cannot created
                        $title = str_replace(" ", "_", $title);
                        $name = $_POST['option'];
                        $file_content = $_POST['content2Val'];
                        if (isset($_POST['option'])) {
                            foreach ($name as $format) {
                                if ($format == "txt") {
                                    echo "<center><h2><b>You choose the following format file(s):</b> &nbsp; <b style='color: purple'>.TXT</b></h2></center><br>";
                                    $name = $title . '.txt';
                                    echo "<script>console.log('name : " . $title . "')</script>";
                                    echo "<script>console.log('title : " . $title . "')</script>";
                                    file_put_contents("download/" . $name, $title . "\r\n");
                                    file_put_contents("download/" . $name, $file_content, FILE_APPEND);
                                    echo "<center><a href=download/" . $name . " type='button' class='btn btn-lg btn-primary' id='downloadLink' download>Download</a>";
                                    echo "<hr class='margin-vert-40'>";
                                } else if ($format == "doc") {
                                    echo "<center><h2><b>You choose the following format file(s):</b> &nbsp; <b style='color: purple'>.DOCX</b></h2></center><br>";
                                    VsWord::autoLoad();
                                    $doc = new VsWord();
                                    $parser = new HtmlParser($doc);
                                    $parser->parse("<h4>" . $title . "</h4>");
                                    $parser->parse($file_content);
                                    $doc->saveAs("download/" . $title . ".docx");
                                    echo "<center><a href=download/" . $title . ".docx type='button' class='btn btn-lg btn-primary' id='downloadLink' download>Download</a>";
                                    echo "<hr class='margin-vert-40'>";
                                } else if ($format == "pdf") {
                                    echo "<center><h2><b>You choose the following format file(s):</b> &nbsp; <b style='color: purple'>.PDF</b></h2></center><br>";
                                    ob_start();
                                    $pdf = new FPDF();
                                    $pdf->AddPage();
                                    $pdf->AddFont('courier', '', 'courier.php');
                                    $pdf->SetFont('courier', 'B', 16);
                                    $pdf->Cell(190, 20, iconv('UTF-8', 'TIS-620', $title), 0, 1, 'C');
                                    $pdf->AddFont('courier', '', 'courier.php');
                                    $pdf->SetFont('courier', '', 16);
                                    $file_content = iconv('UTF-8', 'ASCII//TRANSLIT', $file_content);
                                    $pdf->MultiCell(190, 7, $file_content, 'C');
                                    $pdf->Output("download/" . $title . ".pdf", "F");
                                    echo "<script>console.log(" . $title . ")</script>";
                                    echo "<center><a href=download/" . $title . ".pdf type='button' class='btn btn-lg btn-primary' id='downloadLink' download>Download</a>";
                                    echo "<hr class='margin-vert-40'>";
                                    ob_end_flush();
                                }
                            }
                        }
                    }
                    ?>
                </div>
                <div class="col-md-12">
                    <center>
                        <p style="color: red; font-size: 16px" id="note"><b style="font-size: 16px">NOTE:</b>
                            After clicking on <b>"Download"</b>,
                            <br> we may ask you for a few minute to do our survey in order to improve our our system in the future.
                        </p>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <?php include "menu_footer.php"; ?>
    <?php include "footer.php"; ?>
    <script type="text/javascript">
        var checkSelectOption1 = "no";
        var checkSelectOption2 = "no";
        var checkCaptcha = "no";
        $("input:radio[name='option[]']").on('click', function () {
            var $box1 = $(this);
            if ($box1.is(":checked")) {
                var group1 = "input:radio[name='option[]']";
                console.log(group1);
                $(group1).prop("checked", false);
                $box1.prop("checked", true);
                checkSelectOption1 = "yes";
                $('#divCaptcha').removeAttr('hidden');
            } else {
                $box1.prop("checked", false);
            }
        });
        $(function () {
            $("#downloadLink").click(function () {
                window.open('https://docs.google.com/forms/d/1lHiGRgmimU2ss2qawR1yY3TLOzr9z0Sgk4xaIEDivTg', '_blank');
            });
        });
        function callback() {
            if (checkSelectOption1 == "yes") {
                $("#downloadLink").removeAttr('disabled');
            }
        }
    </script>
</body>
</html>
