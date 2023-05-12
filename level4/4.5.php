<?php
require_once ('style.html');
require_once ('connection.php');
$records_per_page = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

// Update rental_rate of films
$sql = "UPDATE film
SET rental_rate = LEAST(rental_rate * 1.05, 4)
WHERE film_id IN (
  SELECT inventory.film_id
  FROM rental
  JOIN inventory ON rental.inventory_id = inventory.inventory_id
  GROUP BY inventory.film_id
  HAVING COUNT(DISTINCT customer_id) > 10
);
";
$result = $conn->query($sql);

// Check if update was successful
if ($result === TRUE) {
    $sql = "SELECT title, rental_rate
    FROM film
    where rental_rate < 4 and film_id IN (
        SELECT film_id
        FROM rental
        GROUP BY film_id
        HAVING COUNT(DISTINCT customer_id) > 10
    );
     ";
    $result = $conn->query($sql);
    if ($result-> num_rows > 0) {
        echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
        echo "<br> </br>";
        echo " <b> 4.5: Viết truy vấn SQL để cập nhật giá thuê của tất cả các phim trong cơ sở dữ liệu đã được hơn 10 khách hàng thuê, 
        đặt giá mới cao hơn 5% so với giá hiện tại, nhưng không cao hơn $4,00 : </b> ";
        echo "<table>
        <tr>
        <th> title </th>
        <th> rental_rate </th>
        </tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>"."<td>".$row["title"]."</td>"."<td>".$row["rental_rate"]."</td>"."</tr>";
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
