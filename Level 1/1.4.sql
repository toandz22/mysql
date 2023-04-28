/* Viết một truy vấn SQL để trả về thời gian cho thuê trung bình cho từng loại phim trong cơ sở dữ liệu. */

SELECT category.name, DATE_FORMAT(SEC_TO_TIME(AVG(TIME_TO_SEC(rental.return_date - rental.rental_date))), '%H:%i:%s') AS avg_rental_duration
FROM film
JOIN film_category ON film.film_id = film_category.film_id
JOIN category ON film_category.category_id = category.category_id
JOIN inventory ON film.film_id = inventory.film_id
JOIN rental ON inventory.inventory_id = rental.inventory_id
WHERE rental.return_date IS NOT NULL
GROUP BY category.name;

