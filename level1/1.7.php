<?php
require_once ('style.html');
require_once ('connection.php');

// Số bản ghi tối đa trên một trang
$results_per_page = 10;

// Xác định trang hiện tại
if (!isset($_GET['page'])) {
  $page = 1;
} else {
  $page = $_GET['page'];
}

// Tính toán vị trí bắt đầu của bản ghi trên trang hiện tại
$start_index = ($page - 1) * $results_per_page;

$sql = "SELECT actor.first_name, actor.last_name 
        FROM actor
        JOIN film_actor ON actor.actor_id = film_actor.actor_id
        GROUP BY actor.first_name, actor.last_name 
        HAVING COUNT(film_actor.actor_id) > 20
        LIMIT $start_index, $results_per_page";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
  echo "<table><tr><th>First Name</th><th>Last Name</th></tr>";
  echo "<b>1.7: Viết truy vấn SQL để trả về tên của tất cả các diễn viên đã xuất hiện trong hơn 20 bộ phim trong cơ sở dữ liệu: </b>";
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["first_name"]."</td>". "<td>".$row["last_name"]."</td>"."</tr>";
  }

  echo "</table>";

  // Tạo đường dẫn phân trang
  $sql = "SELECT COUNT(*) as total FROM actor
          JOIN film_actor ON actor.actor_id = film_actor.actor_id
          GROUP BY actor.first_name, actor.last_name 
          HAVING COUNT(film_actor.actor_id) > 20";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $total_results = $row['total'];
  $total_pages = ceil($total_results / $results_per_page);

  echo "<div class='pagination'>";
  for ($i=1; $i<=$total_pages; $i++) {
    if ($i == $page) {
      echo "<a class='active' href='?page=$i'>$i</a>";
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
