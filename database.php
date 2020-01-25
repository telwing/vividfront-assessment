<?php
require_once __DIR__ . '/DatabaseEntryRepository.php';

$active = isset($_POST['action']);
$query = '';
$error = null;
$success = false;

if ($active) {
    try {
        $action = $_POST['action'];
        $repo = new DatabaseEntryRepository();

        switch ($action) {
            case DatabaseEntryRepository::ADD_ACTION:
                $repo->create($_POST);
            case DatabaseEntryRepository::DELETE_ACTION:
                $repo->delete($_POST['id']);
        }

        $success = true;
        $error = null;
    } catch (Exception $e) {
        $success = false;
        $error = $e->getMessage();
    }

    $query = '?' . http_build_query([
        'error' => $error,
        'success' => $success,
    ]);
}

header("Location: http://{$_SERVER['HTTP_HOST']}/vividfront{$query}");
die();
