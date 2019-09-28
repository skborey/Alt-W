<?php
if (!empty($_FILES)) {
    $file_name = $_FILES['file']['name'];
    $file_name = str_replace(" ", "_", $file_name);
    $content = file_get_contents($_FILES['file']['tmp_name']);
    echo $content;
}