/* Viết truy vấn SQL để trả về doanh thu được tạo bởi mỗi cửa hàng trong cơ sở dữ liệu cho năm 2005. */

SELECT store.store_id, SUM(payment.amount) AS total_revenue
FROM store
JOIN staff ON store.store_id = staff.store_id
JOIN payment ON staff.staff_id = payment.staff_id
WHERE YEAR(payment.payment_date) = 2005
GROUP BY store.store_id;
