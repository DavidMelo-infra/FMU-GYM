<?php
session_start();
if (isset($_SESSION['aluno_id'])) {
    header("Location: ../views/aluno/area_aluno.php");
    exit();
} else {
    header("Location: ../views/aluno/login.php");
    exit();
}
?>