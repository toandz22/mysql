<?php
require_once ('style.html');
require_once ('connection.php');
$records_per_page = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

// Update rental_rate of films
$sql = "UPDATE customer
SET email = 'kinhdi@example.com'
WHERE customer_id IN (
  SELECT DISTINCT rental.customer_id
  FROM rental
  JOIN inventory ON rental.inventory_id = inventory.inventory_id
  JOIN film ON inventory.film_id = film.film_id
  JOIN film_category ON film.film_id = film_category.film_id
  JOIN category ON film_category.category_id = category.category_id
  WHERE category.name = 'Horror' AND DATE_FORMAT(rental.rental_date, '%Y-%m') = '2005-05'
);
";
$result = $conn->query($sql);

// Check if update was successful
if ($result === TRUE) {
    $sql = "SELECT concat(first_name,last_name,'.',email) as new_email
    FROM customer 
    JOIN rental ON customer.customer_id = rental.customer_id 
    JOIN inventory ON rental.inventory_id = inventory.inventory_id 
    JOIN film ON inventory.film_id = film.film_id 
    JOIN film_category ON film.film_id = film_category.film_id 
    JOIN category ON category.category_id = film_category.category_id
    WHERE category.name = 'Horror' AND DATE_FORMAT(rental.rental_date, '%Y-%m') = '2005-05';
     ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
        echo "<br> </br>";
        echo " <b> 4.4: Viết truy vấn SQL để cập nhật địa chỉ email của tất cả khách hàng đã thuê phim từ danh mục 'Kinh dị' vào tháng 10 năm 2022, 
        đặt địa chỉ email mới là sự kết hợp giữa địa chỉ email hiện tại của họ và chuỗi 'Horror': </b> ";
        echo "<table>
        <tr>
        <th> new_email </th>
        </tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["new_email"]."</td>"."</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
} else {
    echo "Error updating rental rate: " . $conn->error;
}
$sql = "SELECT COUNT(*) AS total_records FROM customer";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row['total_records'];
$total_pages = ceil($total_records / $records_per_page);

// display pagination links
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
