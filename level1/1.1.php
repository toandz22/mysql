<?php
require_once('connection.php');
require_once ('style.html');
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 10;

// Tính toán vị trí bắt đầu của các items trong database
$start = ($page - 1) * $perPage;

$sql = "SELECT first_name, last_name FROM actor LIMIT $start, $perPage";
$result = $conn->query($sql);
echo "<b> 1.1: Viết truy vấn SQL để trả về họ và tên của tất cả các diễn viên trong cơ sở dữ liệu: </b>";
echo "<table><tr><th>first_name</th><th>last_name</th></tr>";


if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["first_name"]."</td><td>".$row["last_name"]."</td></tr>";
    }
} else {
    echo "<tr><td colspan='2'>0 results</td></tr>";
}

echo "</table>";

// Hiển thị phân trang
$sql = "SELECT COUNT(*) as count FROM actor";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
$totalItems = $data['count'];
$totalPages = ceil($totalItems / $perPage);

echo "<div class='pagination'>";
echo "Page: ";
for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == $page) {
        echo "<a class='current'>$i</a>";
    } else {
        echo "<a href='?page=$i'>$i</a>";
    }
}
echo "</div>";
echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
$conn->close();
?>
