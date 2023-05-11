<?php
require_once('style.html');
require_once('connection.php');

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 30;

// tính vị trí bắt đầu lấy dữ liệu từ CSDL
$start = ($page - 1) * $perPage;

// tính tổng số bản ghi
$sql = "SELECT COUNT(*) as totalItems FROM customer JOIN address ON customer.address_id = address.address_id JOIN rental ON customer.customer_id = rental.customer_id WHERE rental.rental_date BETWEEN '2005-05-01' AND '2005-05-31'";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
$totalItems = $data['totalItems'];
$totalPages = ceil($totalItems / $perPage);

// lấy dữ liệu theo trang
$sql = "SELECT customer.first_name, customer.last_name, address.address FROM customer JOIN address ON customer.address_id = address.address_id JOIN rental ON customer.customer_id = rental.customer_id WHERE rental.rental_date BETWEEN '2005-05-01' AND '2005-05-31' LIMIT $start, $perPage";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
  echo "<b> 1.5: Viết truy vấn SQL để trả về tên và địa chỉ của tất cả khách hàng đã thuê phim trong tháng 1 năm 2022: </b>";
  echo "<table><tr><th>first_name</th><th>last_name</th><th>address</th></tr>";
  
  while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row["first_name"]}</td><td>{$row["last_name"]}</td><td>{$row["address"]}</td></tr>";
  }
} else {
  echo "<tr><td colspan='3'>0 results</td></tr>";
}
echo "</table>";

// phân trang
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

$conn->close();
?>
