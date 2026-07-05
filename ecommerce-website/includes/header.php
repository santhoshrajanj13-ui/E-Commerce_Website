<?php
/**
 * Header (HTML head + opening body + navbar)
 * Module: Overall UI (Santhosh - Frontend Developer)
 * Usage: set $pageTitle before including this file.
 */
require_once __DIR__ . '/session.php';
if (!isset($pageTitle)) { $pageTitle = "ShopEase - Online Shopping"; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle); ?></title>
<link rel="stylesheet" href="<?php echo isset($assetPrefix) ? $assetPrefix : ''; ?>assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
