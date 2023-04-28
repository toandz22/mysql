/* Viết một truy vấn SQL để trả về các tiêu đề của tất cả các bộ phim trong cơ sở dữ liệu có xếp hạng 'PG-13' và dài hơn 120 phút. */

select title,rating,length from film
where rating ='PG-13' AND length > 120;