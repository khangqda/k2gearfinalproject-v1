<?php
session_start();
require_once('../../../config/database.php');

if (isset($_POST['submit_edit_category'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    $status = $_POST['status'];

    try {
        $sql = "UPDATE categories SET name = :name, slug = :slug, status = :status WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':slug' => $slug,
            ':status' => $status,
            ':id' => $id
        ]);

        header("Location: ../../index.php?view=categories&msg=updated");
        exit();
    } catch (PDOException $e) {
        die("Lỗi Database: " . $e->getMessage());
    }
}
?>