<?php
require_once ('style.html');
require_once ('connection.php');
// số bản ghi hiển thị trên một trang
$records_per_page = 10; 
// nếu biến page không tồn tại hoặc không phải là số thì gán giá trị mặc định là 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// tính vị trí bắt đầu lấy dữ liệu từ CSDL
$offset = ($page - 1) * $records_per_page;

$sql ="SELECT actor.first_name, actor.last_name, SUM(payment.amount) AS total_revenue
FROM actor
JOIN film_actor ON actor.actor_id = film_actor.actor_id
JOIN inventory ON film_actor.film_id = inventory.film_id
JOIN rental ON inventory.inventory_id = rental.inventory_id
JOIN payment ON rental.rental_id = payment.rental_id
GROUP BY actor.first_name, actor.last_name
order by total_revenue DESC 
LIMIT $records_per_page OFFSET $offset;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    echo "<br></br>";
    echo "<b> 2.6: Viết truy vấn SQL để trả về tổng doanh thu do mỗi diễn viên tạo ra trong cơ sở dữ liệu, dựa trên phí thuê phim mà họ đã xuất hiện: </b>";
    echo "<table><tr><th>first_name</th><th>last_name</th><th>total_revenue</th></tr>";
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["first_name"]."</td>". "<td>".$row["last_name"]."</td>"."<td>".$row["total_revenue"]."</td>"."</tr>";
    }
} else {
    echo "<tr><td colspan='2'>0 results</td></tr>";
}
echo "</table>";

// tính số trang
$sql = "SELECT COUNT(*) AS total_records FROM actor;";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row['total_records'];
$total_pages = ceil($total_records / $records_per_page);

// xuất các nút phân trang
echo "<div class='pagination'>";
    for ($i = max(1, $page - 10); $i <= min($page + 10, $total_pages); $i++) {
        if ($i == $page) {
            echo "<a class='active' href='#'>" . $i . "</a>";
        } else {
            echo "<a href='?page=" . $i . "'>" . $i . "</a>";
        }
    }
    echo "</div>";
    
$conn->close();
?>
