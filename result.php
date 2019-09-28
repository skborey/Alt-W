<?php
error_reporting(error_reporting() & ~E_NOTICE);
include('config.php');
session_start();
?>
<?php include "header.php"; ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<link rel="stylesheet" type="text/css" href="assets/css/base.css">
</head>
<?php include "menu_header.php"; ?>
    <div class="container">
        <div class="row margin-vert-30">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <br><center><div class="image-hover">
                                <a href="#">
                                    <figure>
                                        <img src="assets/img/portfolio/before.png" alt="before" width="200">
                                    </figure>
                                </a>
                            </div></center>
                        <div>
                            <figure>
                                <?php
                                $content1 = "";
                                $simicolon = 0;
                                $pre_w = "";
                                $numOfWord = count($_SESSION['content_modified']);
                                for ($i = 0; $i < $numOfWord; $i++) {
                                    $word = $_SESSION['content_modified'][$i];
                                    if (isset($word->lemma)) {
                                        $lemma = $word->lemma;
                                        if ($word->msg == 'success') {
                                            $content1 .= "<div class='dropdown'>"
                                                    . "&nbsp;"
                                                    . "<mark class='mark' id='mark_$i' style='background-color: #99ddff'>"
                                                    . "<span>"
                                                    . $lemma
                                                    . "</span>"
                                                    . "</mark>"
                                                    . "<div>"
                                                    . "</div>"
                                                    . "</div>";
                                        } else if ($pre_w == "'")
                                            $content1 .= $lemma;
                                        else if ($lemma == "''") {
                                            if ($simicolon % 2 == 0)
                                                $content1 .= $lemma;
                                            else
                                                $content1 .= " " . $lemma;
                                        }
                                        else if (preg_match('/[\'^£$%&*()}{@#~?><>,.|=_+¬-]/', $lemma)) {
                                            $content1 .= $lemma;
                                        } else {
                                            $content1 .= " " . $lemma;
                                        }
                                        $pre_w = $lemma;
                                    } else {
                                        $word = $_SESSION['content_resource'][$i];
                                        if ($word == "''")
                                            $simicolon++;

                                        if ($pre_w == "'") {
                                            $content1 .= $word;
                                        } else if ($word == "''") {
                                            if ($simicolon % 2 == 0)
                                                $content1 .= $word;
                                            else
                                                $content1 .= " " . $word;
                                        }
                                        else if (preg_match('/[\'^£$%&*()}{@#~?><>,.|=_+¬-]/', $word)) {
                                            $content1 .= $word;
                                        } else {
                                            $content1 .= " " . $word;
                                        }
                                        $pre_w = $word;
                                    }
                                }
                                echo "<center><h2 style='color: green'><b>" . $_SESSION['title'] . "</b></h2></center></br>"
                                . "<label class='MsoNormal' style=' font-weight: normal !important;
                                                         text-align: justify; text-justify: inter-word; word-wrap: break-word;'>"
                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $content1 . "</label>";
                                ?>
                            </figure>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <br>
                        <center>
                            <div class="image-hover">
                                <a href="#">
                                    <figure>
                                        <img src="assets/img/portfolio/after.png" alt="after" width="180">
                                    </figure>
                                </a>
                            </div>
                        </center>
                        <div>
                            <figure>
                                <?php
                                $simicolon = 0;
                                $pre_w = "";
                                $content2 = "";
                                for ($i = 0; $i < $numOfWord; $i++) {
                                    $word = $_SESSION['content_modified'][$i];
                                    if (isset($word->lemma)) {
                                        $lemma = $word->lemma;
                                        if ($word->msg == 'success') {
                                            $content2 .= "<div class='dropdown'>"
                                                    . "&nbsp;"
                                                    . "<mark class='mark' id='mark_$i' style='background-color: #99ddff'>"
                                                    . "<span id='$i' class='myspan'>";
                                            $words = $word->words;
                                            $synonyms = $words[0]->word; //use the first definition as default   
                                            if ('A' <= $lemma && $lemma <= 'Z')
                                                $content2 .= ucfirst($synonyms[0][0]); //and use the first synonym of the first defintion as default
                                            else
                                                $content2 .= $synonyms[0][0]; //and use the first synonym of the first defintion as default
                                            $content2 .= "</span>"
                                                    . "</mark>"
                                                    . "<div class='dropdown-content'>"
                                                    . "<form id='myform_$i' method='POST'>"
                                                    . "<center>"
                                                    . "<label for='select_word' style='color: purple'>Select new word</label>"
                                                    . "</center>";
                                            $content2 .= "<div class='container'>"
                                                    . "<ul class='nav nav-tabs'>"
                                                    . "<li class='active'><a data-toggle='tab' href='#r1$i'>"
                                                    . "<span><img src='assets/img/w.png' style='width:20px; hight:20px;' title='WordNet order'></span>"
                                                    . "</a></li>"
                                                    . "<li><a data-toggle='tab' href='#r2$i'>"
                                                    . "<span><img src='assets/img/a.png' style='width:20px; hight:20px;' title='Alphabetical order'></span>"
                                                    . "</a></li>"
                                                    . "<li><a data-toggle='tab' href='#r3$i'>"
                                                    . "<span><img src='assets/img/u.png' style='width:20px; hight:20px;' title='Frequency used order'></span>"
                                                    . "</a></li>"
                                                    . "</ul>"
                                                    . "<div class='tab-content'>";
                                            //WordNet order                                                                                           
                                            $content2 .= "<div id='r1$i' class='tab-pane fade in active'>";
                                            $content2 .= "<select name='word opts' id='opts' class='word' size='6' "
                                                    . "style='width:100%; border:none; padding:1em;' class='form-control'>";
                                            //show original word
                                            $content2 .= " 
                                                        <mark class='mark' id='mark_' style='background-color: #99ddff'>
                                                        <option id='logOf$i' style='border-bottom: 1px solid black; padding-bottom: 3px;' value='"
                                                    . $lemma . "'"
                                                    . "data-toggle='tooltip' data-container='#tooltip_container' "
                                                    . "title='(Original word)'>"
                                                    . $lemma
                                                    . "</option>";
                                            // $words
                                            $myarray = [];
                                            $n = count($words); //number of definition                                                
                                            $synonymAndMeaning1 = array();
                                            $synonymAndMeaning2 = array();
                                            for ($i_def = 0; $i_def < $n; $i_def++) {
                                                $word = $words[$i_def]->word; //each definition
                                                $definition = "";
                                                $definitions = $words[$i_def]->definition;
                                                foreach ($definitions as $def) {
                                                    $definition .= $def . " - ";
                                                }
                                                $sentence = "";
                                                $sentences = $words[$i_def]->sentence;
                                                if ($sentences != null)
                                                    foreach ($sentences as $sent) {
                                                        $sentence .= $sent . " ";
                                                    }
                                                $definition = str_replace("'", "`", $definition);
                                                $sentence = str_replace("'", "`", $sentence);
                                                $toolTipContent = "$definition Example: $sentence";
                                                $m = count($word); //number of syn in same definition
                                                for ($i_w = 0; $i_w < $m; $i_w++) {
                                                    if (strtoupper($word[$i_w][0]) == strtoupper($lemma)) {
                                                        //show only the difference word from lemma                                                                                       
                                                    } else {
                                                        $inarr = in_array($word[$i_w][0], $myarray);
                                                        if ($inarr) {
                                                            continue;
                                                        } else {
                                                            array_push($myarray, $word[$i_w][0]);
                                                        }
                                                        if ('A' <= $lemma && $lemma <= 'Z')
                                                            $word[$i_w][0] = ucfirst($word[$i_w][0]);
                                                        if ($i == 0) {
                                                            $content2 .= "                                               
                                                                        <mark class='mark' id='mark_$i_w' style='background-color: #99ddff'>
                                                                        <option value='" . ucfirst($word[$i_w][0]) . "' "
                                                                    . "data-toggle='tooltip' data-container='#tooltip_container' "
                                                                    . "title='" . $toolTipContent . "'>"
                                                                    . ucfirst($word[$i_w][0])
                                                                    . "</option>";
                                                            $synonymAndMeaning1[ucfirst($word[$i_w][0])] = $toolTipContent;
                                                            $sql2 = "SELECT * FROM logs WHERE pos='" . $_SESSION['content_pos'][$i] . "' and before_word = '" . $lemma . "' and after_word = '" . $word[$i_w][0] . "'";
                                                            $query_result2 = $link->query($sql2);
                                                            if ($query_result2->num_rows > 0) {
                                                                $c = $query_result2->fetch_assoc();
                                                                $synonymAndMeaning2[ucfirst($word[$i_w][0])] = $c['count_word'] . "**" . $toolTipContent;
                                                            } else
                                                                $synonymAndMeaning2[ucfirst($word[$i_w][0])] = "0**" . $toolTipContent;
                                                        } else {
                                                            $content2 .= " 
                                                                        <mark class='mark' id='mark_$i_w' style='background-color: #99ddff'>
                                                                        <option value='" . $word[$i_w][0] . "' "
                                                                    . "data-toggle='tooltip' data-container='#tooltip_container' "
                                                                    . "title='" . $toolTipContent . "'>"
                                                                    . $word[$i_w][0]
                                                                    . "</option>";
                                                            $synonymAndMeaning1[$word[$i_w][0]] = $toolTipContent;
                                                            $sql2 = "SELECT * FROM logs WHERE pos='" . $_SESSION['content_pos'][$i] . "' and before_word = '" . $lemma . "' and after_word = '" . $word[$i_w][0] . "'";
                                                            $query_result2 = $link->query($sql2);
                                                            if ($query_result2->num_rows > 0) {
                                                                $c = $query_result2->fetch_assoc();
                                                                $synonymAndMeaning2[$word[$i_w][0]] = $c['count_word'] . "*" . $toolTipContent;
                                                            } else
                                                                $synonymAndMeaning2[$word[$i_w][0]] = "0**" . $toolTipContent;
                                                        }
                                                    }
                                                }
                                            }
                                            ksort($synonymAndMeaning1);
                                            $content2 .= "</select>"
                                                    . "<div id='tooltip_container'></div>"
                                                    . "</div>";
                                            //Alphabetical order                                                                                              
                                            $content2 .= "<div id='r2$i' class='tab-pane fade' style='wight:10px;'>"
                                                    . "<select name='word opts' id='opts' class='word' size='6' "
                                                    . "style='width:100%; border:none; padding:1em;' class='form-control'>";
                                            //show original word
                                            $content2 .= " 
                                                        <mark class='mark' id='mark_' style='background-color: #99ddff'>
                                                        <option id='logOf$i' style='border-bottom: 1px solid black; padding-bottom: 3px;'value='"
                                                    . $lemma . "'"
                                                    . "data-toggle='tooltip' data-container='#tooltip_container' "
                                                    . "title='(Original word)'>"
                                                    . $lemma
                                                    . "</option>";
                                            foreach ($synonymAndMeaning1 as $key => $val) {
                                                $content2 .= "                                               
                                                            <mark class='mark' id='mark_$i_w' style='background-color: #99ddff'>
                                                            <option value='" . $key . "' "
                                                        . "data-toggle='tooltip' data-container='#tooltip_container' "
                                                        . "title='" . $val . "'>"
                                                        . $key
                                                        . "</option>";
                                            }
                                            $content2 .= "</select>"
                                                    . "<div id='tooltip_container'></div>"
                                                    . "</div>";
                                            //Frequency used order                                                                                            
                                            $content2 .= "<div id='r3$i' class='tab-pane fade'>"
                                                    . "<select name='word opts' id='opts' class='word' size='6' "
                                                    . "style='width:100%; border:none; padding:1em;' class='form-control'>";
                                            $content2 .= " 
                                                            <mark class='mark' id='mark_' style='background-color: #99ddff'>
                                                            <option id='logOf$i' style='border-bottom: 1px solid black; padding-bottom: 3px;'value='"
                                                    . $lemma . "'"
                                                    . "data-toggle='tooltip' data-container='#tooltip_container' "
                                                    . "title='(Original word)'>"
                                                    . $lemma
                                                    . "</option>";
                                            arsort($synonymAndMeaning2);
                                            foreach ($synonymAndMeaning2 as $key => $val) {
                                                $v = explode("**", $val);
                                                $content2 .= "                                               
                                                                    <mark class='mark' id='mark_$i_w' style='background-color: #99ddff'>
                                                                    <option value='" . $key . "' "
                                                        . "data-toggle='tooltip' data-container='#tooltip_container' "
                                                        . "title='" . $v[1] . "'>"
                                                        . $key
                                                        . "</option>";
                                            }
                                            $content2 .= "</select>"
                                                    . "<div id='tooltip_container'></div>"
                                                    . "</div>";
                                            if (!isset($_SESSION['log']["logOf$i"])) {
                                                $_SESSION['log']["logOf$i"] = new stdClass();
                                            }
                                            $_SESSION['log']["logOf$i"]->pos = $_SESSION['content_pos'][$i];
                                            $_SESSION['log']["logOf$i"]->before = $lemma;
                                            $_SESSION['log']["logOf$i"]->after = $synonyms[0][0];
                                            $content2 .= "</div>"
                                                    . "</div>";
                                            $content2 .= "</form>"
                                                    . "</div>"
                                                    . "</div>";
                                        } else if ($pre_w == "'")
                                            $content2 .= $lemma;
                                        else if ($lemma == "''") {
                                            if ($simicolon % 2 == 0)
                                                $content2 .= $lemma;
                                            else
                                                $content2 .= " " . $lemma;
                                        }
                                        else if (preg_match('/[\'^£$%&*()}{@#~?><>,.|=_+¬-]/', $lemma)) {
                                            $content2 .= $lemma;
                                        } else {
                                            $content2 .= " " . $lemma;
                                        }
                                        $pre_w = $lemma;
                                    } else {
                                        if ($word == "''")
                                            $simicolon++;

                                        if ($pre_w == "'") {
                                            $content2 .= $word;
                                        } else if ($word == "''") {
                                            if ($simicolon % 2 == 0)
                                                $content2 .= $word;
                                            else
                                                $content2 .= " " . $word;
                                        }
                                        else if (preg_match('/[\'^£$%&*()}{@#~?><>,.|=_+¬-]/', $word)) {
                                            $content2 .= $word;
                                        } else {
                                            $content2 .= " " . $word;
                                        }
                                        $pre_w = $word;
                                    }
                                }
                                echo "<center><h2 style='color: green'><b>" . $_SESSION['title'] . "</b></h2></center></br>"
                                . "<label id='content2LabelVal' class='MsoNormal' style=' font-weight: normal !important;"
                                . "text-align: justify; text-justify: inter-word; word-wrap: break-word;'>"
                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $content2 . "</label>";
                                ?>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
    <div class="container">
        <div class="row">
            <hr class="margin-vert-40">
            <div class="container">
                <div class="row margin-vert-30">
                    <div class="col-md-12">
                        <h1>
                            <img src="assets/img/export.png" alt="export" style="display: block; margin: 0 auto;"><br>
                        </h1>
                        <div class="col-md-6">
                            <h2 class="margin-vert-20"><b>Meaning of highlight colours :</b></h2>
                            <h2 style="font-size: 24px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="label label-default">1</span> <span class="label label-info">Word synonyms</span></h2>
                            <h2 style="font-size: 24px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="label label-default">2</span> <span class="label label-success">Passive voice</span></h2>
                            <h2 style="font-size: 24px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="label label-default">3</span> <span class="label label-warning">Active voice</span></h2>
                            <br>
                            <div class="col-md-12"><center>
                                    <p style="color: red; font-size: 16px;" id="note">
                                        &nbsp;&nbsp;<b>NOTE:</b>&nbsp;  
                                        You can change the result by moving your mouse on the highlight word or sentence in the <b>AFTER</b> side.
                                    </p></center>
                            </div><br><br><br><br>
                        </div>
                        <br><div class="col-md-6">
                            <form id="form_1" action="export.php" method="POST">
                                <div class="row">
                                    <center>
                                        <div class="col-md-12">
                                            <p class="round2">
                                                <br>&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="font-size: 30px;">
                                                    <input type="radio" value="txt" name="option[]" id="option">
                                                    <img src="assets/img/txts.png" alt="txt" width="50" height="50">&nbsp;&nbsp;
                                                </label>
                                                <label class="radio-inline" style="font-size: 30px;">
                                                    <input type="radio" value="pdf" name="option[]" id="option">
                                                    <img src="assets/img/pdfs.png" alt="pdf" width="50" height="50">&nbsp;&nbsp;
                                                </label>
                                                <label class="radio-inline" style="font-size: 30px;">
                                                    <input type="radio" value="doc" name="option[]" id="option">
                                                    <img src="assets/img/docs.png" alt="doc" width="50" height="50">&nbsp;&nbsp;
                                                </label><br><br>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="carousel-example-1" class="carousel slide" data-ride="carousel">
                                                <!-- <div class="carousel-inner" id="divCaptcha" hidden>
<!--                                                    localhost: 6LdMBxYUAAAAAGSpWxs1G-swk4qo_5mWVfTpESOE
                                                    host ip: 6LdiSEwUAAAAAAFPykDl8Eq5N98B4tHRei-hEL7i
                                                    host domain:
                                                    <div class="g-recaptcha" id="captcha" data-sitekey="6LdiSEwUAAAAAAFPykDl8Eq5N98B4tHRei-hEL7i" data-callback="callback"></div>
                                                </div> -->
                                            </div>
                                        </div>
                                    </center></div>
                                <br>
                                <input name="content2Val" type="hidden" id="content2Val" value="test the content edited.">
                                <script type='text/javascript'>
                                    $(function () {
                                        var content2Val = document.getElementById('content2LabelVal').innerText;
                                        $('#content2Val').val(content2Val);
                                    });
                                </script>                                                                                     
                                <center>
                                    <input type="submit" name="submit_1" class="btn btn-lg btn-primary" value="Submit" id="downloadLink">
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php include "menu_footer.php"; ?>
    <?php include "footer.php"; ?>
    <script type='text/javascript'>
        var checkSelectOption1 = "no";
        var checkSelectOption2 = "no";
        var checkCaptcha = "no";
        $("input:radio[name='option[]']").on('click', function () {
            var $box1 = $(this);
            if ($box1.is(":checked")) {
                var group1 = "input:radio[name='option[]']";
                //console.log(group1);
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
                window.location = "https://docs.google.com/forms/d/1lHiGRgmimU2ss2qawR1yY3TLOzr9z0Sgk4xaIEDivTg";

                //remove all dropwon-content -> set val to content2Val            
                $('#content2LabelVal').filter($('#dropdown-content')).remove();
                content2Val = document.getElementById('content2LabelVal').innerText;
                $('#content2Val').val(content2Val);
            });
        });
        function callback() {
            if (checkSelectOption1 == "yes") {
                $("#downloadLink").removeAttr('disabled');
            }
        }
        $(document).ready(function () {
            var content2Val = document.getElementById('content2LabelVal').innerText;
            $('#content2Val').val(content2Val);
            $('.word').change(function () {
                var id = $(this).find('option').attr('id');
                var after = $(this).val();
                $(this).parents('.dropdown').find('.myspan').text(after);
                var before = $(this).find("option:first-child").val();
                $.ajax({
                    method: "POST",
                    url: "log.php",
                    data: {
                        id: id,
                        before: before,
                        after: after
                    },
                });
                content2Val = document.getElementById('content2LabelVal').innerText;
                $('#content2Val').val(content2Val);
            });
        });
    </script>
    </body>
</html> 