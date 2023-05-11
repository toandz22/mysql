<?php
require_once('style.html');
require_once('connection.php');

$records_per_page = 5;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

$sql = "SELECT c.first_name,c.last_name, COUNT(DISTINCT fc.category_id) AS num_categories, COUNT(r.rental_id) AS num_rentals
FROM customer c
JOIN rental r ON c.customer_id = r.customer_id
JOIN inventory i ON r.inventory_id = i.inventory_id
JOIN film f ON i.film_id = f.film_id
JOIN film_category fc ON f.film_id = fc.film_id
GROUP BY c.customer_id
HAVING num_categories = (SELECT COUNT(*) FROM category)
order by c.first_name,c.last_name 
LIMIT $records_per_page OFFSET $offset";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div id='button'><button onclick=\"window.location.href='http://localhost/mysql/form.html'\"><b>Back</b></button></div>";
    echo "<br> </br>";
    echo "<b> 3.7: Viết truy vấn SQL để trả về tên của tất cả khách hàng đã thuê ít nhất một phim từ mỗi danh mục trong cơ sở dữ liệu, cùng với số lượng phim đã thuê từ mỗi danh mục: </b>";
    echo "<table>
        <tr>
            <th>first_name</th>
            <th>last_name</th>
            <th>num_categories</th>
            <th>num_rentals</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row["first_name"] . "</td>
            <td>" . $row["last_name"] . "</td>
            <td>" . $row["num_categories"] . "</td>
            <td>" . $row["num_rentals"] . "</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "No results found.";
}

// calculate total number of pages
$sql = "SELECT COUNT(*) AS total_records FROM (SELECT COUNT(*) FROM film_actor GROUP BY actor_id HAVING COUNT(*) > 1) AS sub";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row['total_records'];
$total_pages = ceil($total_records / $records_per_page);

// display pagination links
echo "<div class='pagination'>";
echo "<button onclick=\"window.location.href='http://localhost/mysql/level3/3.7.php?page=1'\"><b> first page </b> </button>";
for ($i = max(1, $page - 10); $i <= min($page + 10, $total_pages); $i++) {
    if ($i == $page) {
        echo "<a class='active' href='#'>" . $i . "</a>";
    } else {
        echo "<a href='?page=" . $i . "'>" . $i . "</a>";
    }
}
echo "</div>";

// close database connection
$conn->close();


?>
