<?php
/**
 * File index.php chính - Chuyển hướng đến controller
 * Điểm khởi đầu của ứng dụng
 */

if (isset($_GET['act'])) {
    $url = "controller/index.php?act=" . urlencode($_GET['act']);
    if (isset($_GET['id'])) {
        $url .= "&id=" . urlencode($_GET['id']);
    }
    header("Location: $url");
    exit();
}
header("Location: controller/index.php");
exit();
?>