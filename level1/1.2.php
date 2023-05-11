<?php
require_once ('style.html');
require_once ('connection.php');
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 40;

$start = ($page - 1) * $perPage;

$sql ="SELECT  title , rental_rate , replacement_cost  from film LIMIT $start, $perPage";
$result = $conn->query($sql);
echo "<b> 1.2: Viết một truy vấn SQL để trả về tiêu đề của tất cả các bộ phim trong cơ sở dữ liệu, cùng với giá thuê và chi phí thay thế của chúng :</b>";
echo "<table><tr><th>title</th><th>rental_rate</th><th>replacement_cost</th></tr>";

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><th>".$row["title"]."</th>". "<th>".$row["rental_rate"]."</th>"."<th>".$row["replacement_cost"]."</th></tr>";
  }
  } else {
    echo "<tr><td colspan='2'>0 results</td></tr>";
}

echo "</table>";
$sql = "SELECT COUNT(*) as count FROM film";
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