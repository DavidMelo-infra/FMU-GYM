<?php
session_start();
session_destroy();
header("Location: /FMU-GYM/views/aluno/login.php");
exit();

