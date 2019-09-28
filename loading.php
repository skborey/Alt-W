<!DOCTYPE html>
<html lang="en">    
    <head>
        <title>Alternative Word Suggestion System for English Writings</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="shortcut icon" type="image/ico" href="assets/icon/favicons.ico">
        <link rel="apple-touch-icon" href="assets/icon/apple-touch-icon.png">
        <link rel="stylesheet" href="assets/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/splash.css">
    </head>
    <body>
        <div class="preload">
            <div class="logo">
                Alt + <span style="color: #BA55D3;">W</span>
            </div>
            <div class="loader-frame">
                <div class="loader1" id="loader1"></div>
                <div class="loader2" id="loader2"></div>
            </div>
            <div class="col-md-12 text-center">
                <br><br><br>
                <h3 style="color: white;">
                    Please wait a minute, <span style="color: #BA55D3;">system is processing.</span> 
                </h3>  
            </div>
        </div>
        <?php
        error_reporting(error_reporting() & ~E_NOTICE);
        include('config.php');
        require('wn_config.php');
        require('func_pas2act.php');
        require('func_act2pas.php');
        session_start();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        $title = $_POST["title"];
        $content = $_POST["content"];
        $upload_by = $_POST["upload-type"];
        $opt_to_passive = $_POST["toPassive"];
        $opt_to_active = $_POST["toActive"];
        $upload_date = date('Y-m-d H:i:s', $_POST['eid']);
        $_SESSION['title'] = $upload_by === "File" ? str_replace(".txt", "", $title) : $title;
        $_SESSION["ss_id_file"] = "";
        $_SESSION['ss_filename'] = "";
        /** Is array 1 decimal can contain
         * @Text(html) of sentence that is changed to passive or active.         
         * @Text(normal text) of word that having synonym or dosen't have synonym.
         * It is used for making hightligh of the pasive or active sentence in the "Befor" part when mouse over that sentnece.
         * For the word having synonym will generate the html hightligh code in the result.php
         */
        $_SESSION['content_resource'] = array();
        /** Is array 1 decimal contain Part-of-speech of the word in that index.
         *  It will be used to create the log.
         *  Example: $_SESSION['content_pos'][2021] is 'NN'
         */
        $_SESSION['content_pos'] = array();
        /** Is array that store 4 kinds of data.
         * [
         *   "@Object of original word and list of its synonym return from Wordnet.
         *            Example: $_SESSION['content_modified'][2018] is
         *            stdClass Object
         *              (
         *                   [lemma] => play
         *                   [msg] => success
         *                   [words] => Array
         *                       (
         *                           [0] => stdClass Object
         *                               (
         *                                   [word] => Array
         *                                       (
         *                                           [0] => Array
         *                                               (
         *                                                   [0] => act
         *                                                   [1] => 0
         *                                               )
         *                                          [1] => Array
         *                                               (
         *                                                   [0] => represent
         *                                                   [1] => 2
         *                                               )
         *                                       )
         *                                   [type] => v
         *                                   [definition] => Array
         *                                       (
         *                                           [0] => play a role or part
         *                                       )
         *
         *                                   [sentence] => Array
         *                                       (
         *                                           [0] => "Gielgud played Hamlet"
         *                                           [1] => "She wants to act Lady Macbeth, but she is too young for the role"
         *                                           [2] => "She played the servant to her husband's master"
         *                                       )
         *                            [1] => stdClass Object ...         
         *                        )",
         *   "@Text(html) of passive sentence with its active",
         *   "@Text(html) of active sentence with its passive",
         *   "@Text(normal text) of word that isn't changed. Example: $_SESSION['content_modified'][2021] is 'home'"
         * ]
         */
        $_SESSION['content_modified'] = array();

        function console($str) {
            echo "<script>console.log('" . $str . "');</script>";
        }

        //Store resource content then get that id and file name
        $sql_store_content = "INSERT INTO files_resource (file_name, content,  upload_by, upload_date)
                            VALUES ('" . $title . "','" . addslashes($content) . "', '" . $upload_by . "', '" . $upload_date . "')";
        $result_set = mysqli_query($link, $sql_store_content);
        $sql_call_id = "SELECT id, file_name FROM files_resource ORDER BY id DESC LIMIT 1";
        $result_id = mysqli_query($link, $sql_call_id);
        while ($row = $result_id->fetch_assoc()) {
            if (isset($_SESSION['ss_id_file'])) {
                unset($_SESSION['ss_id_file']);
                $_SESSION["ss_id_file"] = $row['id'];
            } else {
                $_SESSION["ss_id_file"] = $row['id'];
            }
            if (isset($_SESSION['ss_filename'])) {
                unset($_SESSION['ss_filename']);
                $_SESSION["ss_filename"] = $row['file_name'];
            } else {
                $_SESSION["ss_filename"] = $row['file_name'];
            }
        }
        //Find part-of-speech
        require './POS/file/vendor/autoload.php';

        use StanfordTagger\POSTagger;

$pos = new POSTagger();
        $content_with_pos = $pos->tag($content);
        if ($content_with_pos === "") {
            $content_with_pos = $content;
            $_SESSION['content_modified'][0] = $content_with_pos;
            $_SESSION['content_resource'][0] = $content_with_pos;
            $_SESSION['content_pos'][0] = NULL;
        } else {
            $content_with_pos_temp = str_replace("\n", " ", $content_with_pos);
            $content_with_pos = explode(" ", $content_with_pos_temp);
            $_SESSION['numOfW'] = count($content_with_pos);
            $_SESSION['contentPos'] = $content_with_pos;
            //Analyse content
            console("--- 1. Words in article ---");
            for ($i = 0; $i < count($content_with_pos); $i++) {
                console($content_with_pos[$i]);
            }
            console("--- 2. Words (non-duplicate) in article ---");
            $nonDuplicateWord = array();
            for ($i = 0; $i < count($content_with_pos); $i++) {
                if (!in_array($content_with_pos[$i], $nonDuplicateWord)) {
                    $nonDuplicateWord[] = $content_with_pos[$i];
                }
            }
            foreach ($nonDuplicateWord as $k => $val) {
                console($val);
            }
            console("--- 3. Index of duplicate words and percent of each words ---");
            $indexOfDuplicateWord = array(array()); //indexOfDuplicateWord['He'] = {1 , 3 , 5};
            for ($i = 0; $i < count($nonDuplicateWord); $i++) {
                $idx = 0;
                for ($j = 0; $j < count($content_with_pos); $j++) {
                    if ($nonDuplicateWord[$i] == $content_with_pos[$j]) {
                        $indexOfDuplicateWord[$nonDuplicateWord[$i]][$idx] = $j;
                        $idx++;
                    }
                }
            }
            for ($i = 0; $i < count($nonDuplicateWord); $i++) {
                console($nonDuplicateWord[$i] . " : " . (count($indexOfDuplicateWord[$nonDuplicateWord[$i]]) / count($content_with_pos) * 100) . "%");
                for ($j = 0; $j < count($indexOfDuplicateWord[$nonDuplicateWord[$i]]); $j++) {
                    console("  " . $indexOfDuplicateWord[$nonDuplicateWord[$i]][$j]);
                }
            }
            //@end analyse content
            //Count number of sentence by counting . an ,
            $fullstop = array();
            $idx = 0;
            for ($i = 0; $i < $_SESSION['numOfW']; $i++) {
                if ($content_with_pos[$i] == "._." || $content_with_pos[$i] == ",_,") {
                    $fullstop[$idx] = $i;
                    $idx++;
                }
            }
            //Idenifine sentence in content
            $changeToPassive = array();
            $changeToActive = array();
            for ($dot = 0; $dot < count($fullstop); $dot++) {
                $changeToPassive[$dot] = FALSE;
                $changeToActive[$dot] = FALSE;
            }
            //Find SYNONYM, ACTIVE TO PASSIVE, PASSIVE TO ACTIVE
            $idx = 0;
            for ($dot = 0; $dot < count($fullstop); $dot++) {
                $sentence = "";
                $prev_fullstop = $dot == 0 ? 0 : $fullstop[$dot - 1] + 1;
                $changeToPassive[$dot] = FALSE;
                $changeToActive[$dot] = FALSE;
                $v1_ = FALSE;
                $toBe_ = FALSE;
                $v3_ = FALSE;
                $by_ = FALSE;
                $otherPos_ = FALSE;
                $toBeIdx = -1;
                $v1Idx = -1;
                for ($i = $prev_fullstop; $i < $fullstop[$dot]; $i++) {
                    $word = explode("_", $content_with_pos[$i]);
                    $sentence .= $word[0];
                    if ($i < $fullstop[$dot] - 1)
                        $sentence .= " ";
                    if (in_array($word[1], array("NN", "NNS", "NNP", "NNPS", "VB", "VBN", "VBP", "VBZ", "VBG", "PRP")) || in_array($word[0], array("by", "A", "An", "The", "of"))) {
                        if (in_array($word[1], array("VB", "VBP", "VBZ", "VBG"))) {
                            $v1_ = TRUE;
                            if ($v1Idx == -1)
                                $v1Idx = $i - $prev_fullstop;
                        }

                        if (in_array($word[0], array("is", "am", "are"))) {
                            $toBe_ = TRUE;
                            if ($toBeIdx == -1)
                                $toBeIdx = $i - $prev_fullstop;
                        }

                        if ($word[1] == "VBN") {
                            $v3_ = TRUE;
                        }

                        if ($word[0] == "by") {
                            $by_ = TRUE;
                        }
                    } else {
                        $otherPos_ = TRUE;
                    }
                }
                $sentence .= ".";
                if ($i - $prev_fullstop <= 9 && $toBe_ && $v3_ && $by_ && !$otherPos_)
                    $changeToActive[$dot] = TRUE;
                else if ($i - $prev_fullstop <= 5 && $v1_ && !$otherPos_)
                    $changeToPassive[$dot] = TRUE;
                if ($opt_to_passive === "checked" && $changeToPassive[$dot]) { //Do PASSIVE VOICE if user checked
                    $toPass = toPassive($sentence, $v1Idx);
                    console($sentence);
                    $toPass = rtrim($toPass, '.');
                    $toPass .= substr($content_with_pos[$fullstop[$dot]], -1); //Chnage from . to , or .
                    $sentence = rtrim($sentence, '.');
                    $sentence .= substr($content_with_pos[$fullstop[$dot]], -1); //Chnage from . to , or .
                    if (strpos($toPass, 'Not found verb') === FALSE) {
                        $_SESSION['content_modified'][$idx] = "<div class='dropdown'>"
                                . "&nbsp;"
                                . "<mark class='mark2passive' id='mark_$idx' style='background-color: #d6f5d6'>"
                                . "<span id='$idx' class='myspan'>"
                                . $toPass
                                . "</span>"
                                . "</mark>"
                                . "<div class='dropdown-content'>"
                                . "<form id='myform_$idx' method='POST'>"
                                . "<center>"
                                . "<label for='select_word' style='color: purple'>Change sentence to</label>"
                                . "</center>"
                                . "<div class='container'>"
                                . "<select name='word opts' id='opts2passive' size='2' class='word' class='form-control'>"//style='width:100%; border:none; padding:1em;'
                                . "<mark class='mark2passive' id='mark_' style='background-color: #d6f5d6'>"
                                . "<option  style='border-bottom: 1px solid black; padding-bottom: 3px;' value='"
                                . str_replace("'", "`", $sentence) . "'"
                                . "data-toggle='tooltip' data-container='#tooltip_container' title='" . str_replace("'", "`", $sentence) . "'>"
                                . "Active voice (Original sentence)"
                                . "</option>"
                                . "<option value='"
                                . $toPass . "'"
                                . "data-toggle='tooltip' data-container='#tooltip_container' title='$toPass'>"
                                . "Passive voice"
                                . "</option>"
                                . "</select>"
                                . "<div id='tooltip_container'></div>"
                                . "</div>"
                                . "</form>"
                                . "</div>"
                                . "</div>";
                        $_SESSION['content_resource'][$idx] = "<mark class='mark' name='2pas' id='mark_$idx' style='background-color: #d6f5d6'>" . $sentence . "</mark>";
                        $idx++;
                    }
                } else if ($opt_to_active === "checked" && $changeToActive[$dot]) { //cannot Change sentence to PASSIVE VOICE => do ACTIVE VOICE
                    $toActi = toActive($sentence, $toBeIdx);
                    $toActi = rtrim($toActi, '.');
                    $toActi .= substr($content_with_pos[$fullstop[$dot]], -1); //chnage from . to , or .
                    $sentence = rtrim($sentence, '.');
                    $sentence .= substr($content_with_pos[$fullstop[$dot]], -1); //chnage from . to , or .
                    if (strpos($toActi, 'Not found verb') === FALSE) {
                        //$changeToActive[$dot] = TRUE;
                        $_SESSION['content_modified'][$idx] = "<div class='dropdown'>"
                                . "&nbsp;"
                                . "<mark class='mark2active' id='mark_$idx' style='background-color: #ffffcc'>"
                                . "<span id='$idx' class='myspan'>"
                                . $toActi
                                . "</span>"
                                . "</mark>"
                                . "<div class='dropdown-content'>"
                                . "<form id='myform_$idx' method='POST'>"
                                . "<center>"
                                . "<label for='select_word' style='color: purple'>Change sentence to</label>"
                                . "</center>"
                                . "<div class='container'>"
                                . "<select name='word opts' id='opts2active' size='2' class='word' class='form-control'>"//style='width:100%; border:none; padding:1em;'
                                . "<mark class='mark2active' id='mark_' style='background-color: #ffffcc'>"
                                . "<option  style='border-bottom: 1px solid black; padding-bottom: 3px;' value='"
                                . str_replace("'", "`", $sentence) . "'"
                                . "data-toggle='tooltip' data-container='#tooltip_container' title='" . str_replace("'", "`", $sentence) . "'>"
                                . "Passive voice (Original sentence)"
                                . "</option>"
                                . "<option value='"
                                . $toActi . "'"
                                . "data-toggle='tooltip' data-container='#tooltip_container' title='$toActi'>"
                                . "Active voice"
                                . "</option>"
                                . "</select>"
                                . "<div id='tooltip_container'></div>"
                                . "</div>"
                                . "</form>"
                                . "</div>"
                                . "</div>";
                        $_SESSION['content_resource'][$idx] = "<mark class='mark' name='2act' id='mark_$idx' style='background-color: #ffffcc'>" . $sentence . "</mark>";
                        $idx++;
                    }
                } else { // do SYNONYM
                    for ($i = $prev_fullstop; $i <= $fullstop[$dot]; $i++) {
                        $idxOf_ = strrpos($content_with_pos[$i], "_");
                        $p = substr($content_with_pos[$i], $idxOf_ + 1, 1);
                        $wp = explode("_", $content_with_pos[$i]);
                        if ($p == "J" || $p == "R" || $p == "V") {
                            if ($p == 'J')
                                $p = "a";
                            else if ($p == 'R')
                                $p = "r";
                            else if ($p == 'V')
                                $p = "v";
                            $_SESSION['content_modified'][$idx] = word(urldecode($wp[0]), $p);
                        } else {
                            $_SESSION['content_modified'][$idx] = $wp[0];
                        }
                        $_SESSION['content_resource'][$idx] = $wp[0];
                        $_SESSION['content_pos'][$idx] = $wp[1];
                        $idx++;
                    }
                }
            }
        }
        ?>
        <script type="text/javascript">
            setTimeout(function () {
                location.href = "result.php"
            }, 1200);
        </script>         
    </body>
</html>