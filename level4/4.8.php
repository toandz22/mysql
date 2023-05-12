<?php
require_once ('style.html');
require_once ('connection.php');
$records_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;
// Update rental_rate of films
$sql = "UPDATE customer c
JOIN address ad  ON ad.address_id = c.address_id
JOIN city ci ON ci.city_id = ad.city_id
JOIN country co ON co.country_id = ci.country_id
SET ad.address = CONCAT(ad.address, 'samecity')
WHERE c.last_name = c.last_name and ci.city = ci.city ";
$result = $conn->query($sql);

// Check if update was successful
if ($result === TRUE) {
    $sql = "SELECT c.first_name, c.last_name, address.address 
    from address 
    JOIN customer c ON c.address_id = address.address_id
    JOIN city ci ON ci.city_id = address.city_id
    WHERE c.last_name = c.last_name and ci.city = ci.city"; 
    $result = $conn->query($sql);
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    if ($result-> num_rows > 0) {
        
        echo "<br> </br>";
        echo " <b> 4.8: Viết truy vấn SQL để cập nhật địa chỉ của tất cả các khách hàng sống trong cùng thành phố với một khách hàng khác có cùng họ, 
        đặt địa chỉ mới là phần nối của địa chỉ hiện tại của họ và chuỗi 'samecity' : </b> ";
        echo "<table>
        <tr>
        <th> first_name </th>
        <th> last_name </th>
        <th> address </th>
        </tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>"."<td>".$row["first_name"]."</td>"."<td>".$row["last_name"]."</td>"."<td>".$row["address"]."</td>"."</tr>";
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
