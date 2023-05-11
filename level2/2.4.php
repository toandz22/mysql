<?php
require_once ('style.html');
require_once ('connection.php');
// số bản ghi hiển thị trên một trang
$records_per_page = 10;
// nếu biến page không tồn tại hoặc không phải là số thì gán giá trị mặc định là 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// tính vị trí bắt đầu lấy dữ liệu từ CSDL
$offset = ($page - 1) * $records_per_page;

$sql ="SELECT distinct a.first_name , a.last_name 
from actor a
inner join film_actor fa on fa.actor_id = a.actor_id -- lấy ra các diễn viên xuất hiện trong ít nhất 1 bộ phim 
inner join film_category fc on fc.film_id = fa.film_id -- lấy ra tên phim thuộc các danh mục film
inner join category c  on fc.category_id = c.category_id -- lấy ra tên danh mục film 
WHERE EXISTS ( -- Hàm where exists để lấy ra actor xuất hiện ít nhất trong 1 danh mục phim 
    SELECT 1
    FROM film_category fc2
    WHERE fc2.category_id = c.category_id
)
ORDER BY a.last_name, a.first_name
LIMIT $records_per_page OFFSET $offset;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    echo "<b>2.4: Viết truy vấn SQL để trả về tên của tất cả các diễn viên đã xuất hiện trong ít nhất một bộ phim trong mỗi danh mục trong cơ sở dữ liệu:  </b>";
    echo "<table><tr><th>first_name</th><th>last_name</th></tr>";
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["first_name"]."</td>". "<td>".$row["last_name"]."</td>"."</tr>";
    }
} else {
    echo "<tr><td colspan='2'>0 results</td></tr>";
}
echo "</table>";

// tính số trang
$sql = "SELECT COUNT(*) AS total_records FROM actor;";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row['total_records'];
$total_pages = ceil($total_records / $records_per_page);

// xuất các nút phân trang
echo "<div class='pagination'>";
for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $page) {
        echo "<a class='active' href='#'>".$i."</a>";
    } else {
        echo "<a href='?page=".$i. "'>".$i."</a>";
    }
}
echo "</div>";

$conn->close();
?>
