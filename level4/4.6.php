<?php
require_once ('style.html');
require_once ('connection.php');
$records_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

// Update rental_rate of films
$sql = "UPDATE film
set rental_rate = 3.50
where rating = 'PG-13' AND length >120 ";
$result = $conn->query($sql);

// Check if update was successful
if ($result === TRUE) {
    $sql = "SELECT title, rental_rate 
    from film 
    where rating = 'PG-13' AND length >120
    LIMIT $offset, $records_per_page"; 
    $result = $conn->query($sql);
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    if ($result-> num_rows > 0) {
        
        echo "<br> </br>";
        echo " <b> 4.6: Viết truy vấn SQL để cập nhật giá thuê của tất cả các phim trong cơ sở dữ liệu có xếp hạng 'PG-13' và thời lượng hơn 2 giờ, 
        đặt giá mới là $3,5 : </b> ";
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
