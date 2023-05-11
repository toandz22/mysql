<?php
require_once('style.html');
require_once('connection.php');

$records_per_page = 8;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$offset = ($page - 1) * $records_per_page;

$sql = "SELECT distinct title 
from
(select fc.film_id, c.name
from film_category as fc
join category as c on fc.category_id =c.category_id) as b1
join (select i.film_id,j.title
		from inventory as i
        join film as j on i.film_id = j.film_id) as b2 on b1.film_id = b2.film_id
        WHERE b1.name = 'Action'
        LIMIT $records_per_page OFFSET $offset ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    echo "<br> </br>";
    echo "<b> 2.10: Viết truy vấn SQL để trả về tiêu đề của tất cả các phim trong cơ sở dữ liệu đã được thuê bởi mọi khách hàng đã từng thuê phim từ danh mục 'Action' </b>";
    echo "<table><tr><th>Title</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["title"] . "</td></tr>";
    }
    echo "</table>";

    $sql = "SELECT COUNT(*) AS total_records FROM film WHERE title IS NOT NULL;";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_records = $row['total_records'];
    $total_pages = ceil($total_records / $records_per_page);

    // xuất các nút phân trang
    echo "<div class='pagination'>";
    for ($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++) {
        if ($i == $page) {
            echo "<a class='active' href='#'>" . $i . "</a>";
        } else {
            echo "<a href='?page=" . $i . "'>" . $i . "</a>";
        }
    }
    echo "</div>";
} else {
    echo "<p>No records found.</p>";
    
}

$conn->close();
?>
