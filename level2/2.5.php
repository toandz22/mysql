<?php
require_once ('style.html');
require_once ('connection.php');
// số bản ghi hiển thị trên một trang
$records_per_page = 10; 
// nếu biến page không tồn tại hoặc không phải là số thì gán giá trị mặc định là 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// tính vị trí bắt đầu lấy dữ liệu từ CSDL
$offset = ($page - 1) * $records_per_page;

$sql ="SELECT c.first_name, c.last_name, COUNT(*) as rental_count
FROM customer c
JOIN rental r1 ON c.customer_id = r1.customer_id
JOIN rental r2 ON r1.customer_id = r2.customer_id AND r1.rental_id <> r2.rental_id AND r1.rental_date = r2.rental_date
JOIN inventory i ON r1.inventory_id = i.inventory_id
GROUP BY c.customer_id
HAVING rental_count > 1
LIMIT $records_per_page OFFSET $offset;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    echo "<br> </br>";
    echo "<b> 2.5: Viết một truy vấn SQL để trả về tên của tất cả các khách hàng đã thuê cùng một bộ phim nhiều lần trong một giao dịch, cùng với số lần họ đã thuê bộ phim đó: </b>";
    echo "<table><tr><th>first_name</th><th>last_name</th><th>rental_count</th></tr>";
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["first_name"]."</td>". "<td>".$row["last_name"]."</td>"."<td>".$row["rental_count"]."</td>"."</tr>";
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
    for ($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++) {
        if ($i == $page) {
            echo "<a class='active' href='#'>" . $i . "</a>";
        } else {
            echo "<a href='?page=" . $i . "'>" . $i . "</a>";
        }
    }
    echo "</div>";
    
$conn->close();
?>
