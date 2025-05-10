<?php
session_start();

// Nếu chưa đăng nhập, chuyển về index.php
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Ghi chú của tôi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }

        .main {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        textarea {
            width: 100%;
            height: 300px;
            padding: 15px;
            border-radius: 5px;
            border: none;
            resize: vertical;
            transition: all 0.3s ease;
        }

        .controls {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        label {
            font-size: 14px;
        }

        .dark-mode {
            background-color: #121212;
            color: #f0f0f0;
        }

        .dark-mode textarea {
            background-color: #1e1e1e;
            color: white;
        }

        .light-mode {
            background-color: #f5f5f5;
            color: #222;
        }

        .light-mode textarea {
            background-color: white;
            color: black;
        }

        select, input[type="color"] {
            margin-top: 5px;
        }

        .logout-link {
            margin-top: 15px;
            display: inline-block;
        }
    </style>
</head>
<body class="light-mode">



<div class="main">
    <h2>👋 Xin chào, <?php echo $_SESSION['user']['name']; ?>!</h2>
    <p>Đây là nơi bạn có thể viết ghi chú và tùy chỉnh giao diện.</p>

    <body>
    <div style="position: absolute; top: 10px; right: 10px;">
        <a href="profile.php" style="padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">View Profile</a>
    </div>
    <!-- Phần còn lại của home.php -->


    <textarea id="noteArea" placeholder="Viết ghi chú tại đây..."></textarea>

    <div class="controls">
        <label>
            Kích thước chữ:
            <select id="fontSizeSelect">
                <option value="14px">Nhỏ</option>
                <option value="16px" selected>Vừa</option>
                <option value="20px">Lớn</option>
            </select>
        </label>

        <label>
            Màu nền ghi chú:
            <input type="color" id="bgColorPicker" value="#ffffff">
        </label>

        <label>
            Chủ đề:
            <select id="themeToggle">
                <option value="light">Sáng</option>
                <option value="dark">Tối</option>
            </select>
        </label>
    </div>

    <a class="logout-link" href="logout.php">🚪 Đăng xuất</a>
</div>



<script>
    const noteArea = document.getElementById('noteArea');
    const fontSizeSelect = document.getElementById('fontSizeSelect');
    const bgColorPicker = document.getElementById('bgColorPicker');
    const themeToggle = document.getElementById('themeToggle');

    fontSizeSelect.addEventListener('change', function() {
        noteArea.style.fontSize = this.value;
    });

    bgColorPicker.addEventListener('input', function() {
        noteArea.style.backgroundColor = this.value;
    });

    themeToggle.addEventListener('change', function() {
        document.body.className = this.value + '-mode';
    });
</script>


</body>
</html>
