<?php
require 'header.php'; // starts session
session_unset();
session_destroy();
header("Location: index.php");
exit;

