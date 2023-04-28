/* Viết một truy vấn SQL để trả lại tên và địa chỉ của tất cả các khách hàng đã thuê một bộ phim vào tháng 1 năm 2022. */

SELECT customer.first_name, customer.last_name, address.address
FROM customer JOIN address ON customer.address_id = address.address_id JOIN rental ON customer.customer_id = rental.customer_id
WHERE rental.rental_date BETWEEN '2005-05-01' AND '2005-05-31';