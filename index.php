<?php
require_once __DIR__ . '/DatabaseEntryRepository.php';

$entriesRepository = new DatabaseEntryRepository();

$entries = $entriesRepository->findAll();
$error = ! empty($_GET['error']) ? $_GET['error'] : false;
$success = ! empty($_GET['success']) && 1 == $_GET['success'];

include __DIR__ . '/view.php';
