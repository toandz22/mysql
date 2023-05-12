<?php
require_once ('style.html');
require_once ('connection.php');
$records_per_page = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

// Update rental_rate of films
$sql = "UPDATE film SET rental_rate = rental_rate * 1.1  
        WHERE film_id IN (
           SELECT inventory.film_id    
           FROM rental   
           INNER JOIN inventory ON rental.inventory_id = inventory.inventory_id    
           GROUP BY inventory.film_id    
           HAVING COUNT(*) > 100 
        ) 
        AND film_id > 0;";
$result = $conn->query($sql);

// Check if update was successful
if ($result === TRUE) {
    // Select top 5 most rented films
    $sql = "SELECT f.title, f.rental_rate
            FROM film f 
            LIMIT $records_per_page OFFSET $offset ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
        echo "<br> </br>";
        echo " <b> 4.1:Viết truy vấn SQL để cập nhật giá thuê của tất cả các phim trong cơ sở dữ liệu đã được thuê hơn 100 lần,
        đặt giá thuê mới cao hơn 10% so với giá hiện tại: </b> ";
        echo "<table><tr><th>title</th><th>rental_rate</th></tr>";

        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["title"]."</td>". "<td>".$row["rental_rate"]."</td>"."</tr>";
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
