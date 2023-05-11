<?php
require_once ('style.html');
require_once ('connection.php');
// số bản ghi hiển thị trên một trang
$records_per_page = 20;
// nếu biến page không tồn tại hoặc không phải là số thì gán giá trị mặc định là 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// tính vị trí bắt đầu lấy dữ liệu từ CSDL
$offset = ($page - 1) * $records_per_page;

$sql ="SELECT concat(c.first_name,' ',c.last_name) AS customer_name, SUM(p.amount) AS total_revenue
FROM customer c
JOIN rental r ON c.customer_id = r.customer_id
JOIN payment p ON r.rental_id = p.rental_id
JOIN inventory i ON r.inventory_id = i.inventory_id
JOIN store s ON i.store_id = s.store_id
GROUP BY c.customer_id
ORDER BY total_revenue DESC
LIMIT $records_per_page OFFSET $offset;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    echo "<b> 2.1: Viết truy vấn SQL để trả về 10 khách hàng hàng đầu đã tạo ra nhiều doanh thu nhất cho cửa hàng, bao gồm tên của họ và tổng doanh thu được tạo ra: </b>";
    echo "<table><tr><th>customer_name</th><th>total_revenue</th></tr>";
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["customer_name"]."</td>". "<td>".$row["total_revenue"]."</td>"."</tr>";
    }
} else {
    echo "<tr><td colspan='2'>0 results</td></tr>";
}
echo "</table>";

// tính số trang
$sql = "SELECT COUNT(*) AS total_records FROM customer;";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row['total_records'];
$total_pages = ceil($total_records / $records_per_page);

// xuất các nút phân trang
echo "<div class='pagination'>";
for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $page) {
        echo "<a class='active' href='#'>".$i."</a>";
    } else {
        echo "<a href='?page=".$i."'>".$i."</a>";
    }
}
echo "</div>";


$conn->close();
?>
