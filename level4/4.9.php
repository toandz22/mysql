<?php
require_once ('style.html');
require_once ('connection.php');
$records_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;
// Update rental_rate of films
$sql = "UPDATE film
SET rental_rate = rental_rate * 0.85
WHERE film_id IN (
  SELECT fc.film_id
  FROM film_category fc 
  JOIN category c ON fc.category_id = c.category_id 
  WHERE c.name = 'Comedy'
) AND release_year >= 2006; ";
$result = $conn->query($sql);

// Check if update was successful
if ($result === TRUE) {
    $sql = "SELECT f.title, f.rental_rate as new_rental_rate
    from film f
    join film_Category fc on fc.film_id = f.film_id
    join category c on c.category_id = fc.category_id
    where c.name = 'Comedy' and f.release_year >=2006"; 
    $result = $conn->query($sql);
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    if ($result-> num_rows > 0) {
        
        echo "<br> </br>";
        echo " <b> 4.9: Viết truy vấn SQL để cập nhật giá cho thuê của tất cả các phim trong danh mục 'Comedy' được phát hành vào năm 2006 trở đi, 
        đặt giá mới thấp hơn 15% so với giá hiện tại : </b> ";
        echo "<table>
        <tr>
        <th> title </th>
        <th> new_rental_rate </th>
        </tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>"."<td>".$row["title"]."</td>"."<td>".$row["new_rental_rate"]."</td>"."</tr>";
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
if ($page > 1) {
    echo "<a href='?page=" . ($page - 1) . "'>&laquo;</a>";
}
for ($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++) {
    if ($i == $page) {
        echo "<a class='active' href='#'>" . $i . "</a>";
    } else {
        echo "<a href='?page=" . $i . "'>" . $i . "</a>";
    }
}
if ($page < $total_pages) {
    echo "<a href='?page=" . ($page + 1) . "'>&raquo;</a>";
}
echo "</div>";
$conn->close(); 
?>
