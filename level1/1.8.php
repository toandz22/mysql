<?php
require_once ('style.html');
require_once ('connection.php');

// Số phần tử bạn muốn hiển thị trong mỗi trang
$results_per_page = 15;

// Tính toán số trang dựa trên kết quả truy vấn
$sql_count = "SELECT COUNT(*) as total FROM film WHERE rating ='PG-13' AND length > 120";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_results = $row_count['total'];
$total_pages = ceil($total_results / $results_per_page);

// Xác định trang hiện tại
if (!isset($_GET['page'])) {
    $current_page = 1;
} else {
    $current_page = $_GET['page'];
}

// Tính toán giá trị OFFSET và LIMIT để hiển thị kết quả
$offset = ($current_page - 1) * $results_per_page;
$sql ="SELECT title,rating,length from film WHERE rating ='PG-13' AND length > 120 LIMIT $offset, $results_per_page;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    echo "<b> 1.8: Viết truy vấn SQL để trả về tiêu đề của tất cả các phim trong cơ sở dữ liệu có xếp hạng 'PG-13' và thời lượng hơn 120 phút: </b>";
    echo "<table><tr><th>title</th><th>rating</th><th>length</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["title"]."</td>". "<td>".$row["rating"]."</td>"."<td>".$row["length"]."</td>"."</tr>";
  }
  echo "</table>";

  // Hiển thị các nút phân trang
  echo "<div class='pagination'>";
  for ($i = 1; $i <= $total_pages; $i++) {
      if ($i == $current_page) {
          echo "<span class='current-page'>$i</span>";
      } else {
          echo "<a href='?page=$i'>$i</a>";
      }
  }
  echo "</div>";
} else {
    echo "0 results";
}

$conn->close();
?>
