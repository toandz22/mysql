<?php
require_once ('style.html');
require_once ('connection.php');

$sql ="SELECT c.first_name, c.last_name, c.email, a.phone, a.address
FROM customer c
JOIN rental r ON c.customer_id = r.customer_id
JOIN address a ON a.address_id = c.address_id
JOIN inventory i ON r.inventory_id = i.inventory_id
JOIN film_category fc ON i.film_id = fc.film_id
JOIN category cate ON fc.category_id = cate.category_id
GROUP BY c.customer_id
HAVING COUNT(DISTINCT cate.category_id) = (SELECT COUNT(*) FROM category);";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    echo "<b> 2.2:Viết truy vấn SQL để trả về tên và thông tin liên hệ của tất cả khách hàng đã thuê phim ở tất cả các danh mục trong cơ sở dữ liệu: </b>";
    echo "<table><tr><th>first_name</th><th>last_name</th><th>email</th><th>phone</th><th>address</th></tr>";
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["first_name"]."</td>". "<td>".$row["last_name"]."</td>". "<td>".$row["email"]."</td>". "<td>".$row["phone"]."</td>". "<td>".$row["address"]."</td>"."</tr>";
    }
} else {
    echo "<tr><td colspan='2'>0 results</td></tr>";
}
echo "</table>";

$conn->close();
?>
