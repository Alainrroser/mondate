USE mondate;

-- its-a-me
SET @me = (SELECT id FROM user WHERE email = 'marino.vonwattenwyl@bbcag.ch');

-- annihilate data
DELETE FROM appointment_user;
DELETE FROM appointment;
DELETE FROM tag;

-- create tags
SELECT * FROM tag;
INSERT INTO tag (name, color)
VALUES ('Free Time', '16D3E0'), ('Dentist', '1543EB'), ('School', 'FF0000');

-- create appointments
INSERT INTO appointment (date, start, end, name, description, creator_id)
VALUES
('2021-03-09', '13:00:00', '15:00:00', 'Bowling', 'Wahoooo', @me),
('2021-03-08', '08:00:00', '16:00:00', 'gibb', 'Noooo', @me),
('2021-03-11', '15:00:00', '16:00:00', 'Dentist', 'Aaaaah', @me),
('2021-03-19', '13:00:00', '15:30:00', 'Cinema', 'Yeeee', @me);

-- connect appointments to users
INSERT INTO appointment_user (appointment_id, user_id)
VALUES 
((SELECT id FROM appointment WHERE name = 'Bowling'), @me),
((SELECT id FROM appointment WHERE name = 'gibb'), @me),
((SELECT id FROM appointment WHERE name = 'Dentist'), @me),
((SELECT id FROM appointment WHERE name = 'Cinema'), @me);