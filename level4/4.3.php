<?php
require_once ('style.html');
require_once ('connection.php');
$records_per_page = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

// Update rental_rate of films
$sql = "UPDATE film
SET rental_rate = ROUND(rental_rate * 1.2, 2)
WHERE film_id IN (
  SELECT fc.film_id FROM film_category fc
  INNER JOIN category c ON fc.category_id = c.category_id
  WHERE c.name = 'Action'
)
AND release_year < 2007;";
$result = $conn->query($sql);

// Check if update was successful
if ($result === TRUE) {
    $sql = "SELECT f.title, f.rental_rate
    FROM film f
    JOIN film_category FC ON F.film_id = FC.film_id
    JOIN category C ON FC.category_id = C.category_id
    WHERE C.name = 'Action'
    AND F.release_year < 2007
     ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
        echo "<br> </br>";
        echo " <b> 4.3:Viết truy vấn SQL để cập nhật giá thuê của tất cả các phim trong danh mục 'Hành động' được phát hành trước năm 2005, 
        đặt giá mới cao hơn 20% so với giá hiện tại: </b> ";
        echo "<table>
        <tr>
        <th> title </th>
        <th> rental_rate </th>
        </tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["title"]."</td>"."<td>".$row["rental_rate"]."</td>"."</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
} else {
    echo "Error updating rental rate: " . $conn->error;
}
$sql = "SELECT COUNT(*) AS total_records FROM film";
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
