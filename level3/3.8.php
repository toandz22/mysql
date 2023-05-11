<?php
require_once('style.html');
require_once('connection.php');

$records_per_page = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

$sql = "SELECT title 
FROM film
WHERE film_id NOT IN (
                       SELECT inventory.film_id
                       FROM inventory
                       JOIN rental ON inventory.inventory_id = rental.inventory_id
                       JOIN customer ON rental.customer_id = customer.customer_id
                       JOIN film ON inventory.film_id = film.film_id
                       WHERE film.rating = 'G'
                     )
AND film_id IN (
                 SELECT inventory.film_id
                 FROM inventory
                 JOIN rental ON inventory.inventory_id = rental.inventory_id
                 GROUP BY inventory.film_id
                 HAVING COUNT(*) > 10
               )
LIMIT $records_per_page OFFSET $offset";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<id ='button'> <button onclick=\"window.location.href='http://localhost/mysql/form.html'\"><b> Back </b> </button>";
    echo "<br> </br>";
    echo "<b> 3.8: Viết truy vấn SQL để trả về tiêu đề của tất cả các phim trong cơ sở dữ liệu đã được thuê hơn 100 lần, nhưng chưa bao giờ được thuê bởi bất kỳ khách hàng nào đã thuê phim có xếp hạng 'G': </b>";
    echo "<table>
        <tr>
            <th>title</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row["title"] . "</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "No results found.";
}

// calculate total number of pages
$sql = "SELECT COUNT(*) AS total_records FROM film";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row['total_records'];
$total_pages = ceil($total_records / $records_per_page);

// display pagination links
echo "<div class='pagination'>";
echo "<button onclick=\"window.location.href='http://localhost/mysql/level3/3.8.php?page=1'\"><b> first page </b> </button>";
for ($i = max(1, $page - 10); $i <= min($page + 10, $total_pages); $i++) {
    if ($i == $page) {
        echo "<a class='active' href='#'>" . $i . "</a>";
    } else {
        echo "<a href='?page=" . $i . "'>" . $i . "</a>";
    }
}echo "<button onclick=\"window.location.href='http://localhost/mysql/level3/3.8.php?page= $total_pages'\"><b> Last page </b> </button>";
echo "</div>";

// close database connection
$conn->close();
?>
