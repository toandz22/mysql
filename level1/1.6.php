<?php
require_once ('style.html');
require_once ('connection.php');
$sql ="SELECT store.store_id, SUM(payment.amount) AS total_revenue
FROM store
JOIN staff ON store.store_id = staff.store_id
JOIN payment ON staff.staff_id = payment.staff_id
WHERE YEAR(payment.payment_date) = 2005
GROUP BY store.store_id;";
echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
echo "<b> 1.6 : Viết truy vấn SQL để trả về doanh thu do mỗi cửa hàng tạo ra trong cơ sở dữ liệu cho năm 2021:</b> ";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<table><tr><th>store_id</th><th>total_revenue</th></tr>";
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><th>".$row["store_id"]."</th>". "<th>".$row["total_revenue"]."</th>"."</tr>";
  }
  } else {
    echo "0 results";
  }
  $conn->close();
?>