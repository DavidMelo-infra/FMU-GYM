<?php
session_start();
session_destroy();
header("Location: /projeto/views/aluno/login.php");
exit();

