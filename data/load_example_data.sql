USE mondate;

-- its-a-me
SET @me = (SELECT id FROM user LIMIT 1);

-- annihilate data
DELETE FROM appointment_user;
DELETE FROM appointment_tag;
DELETE FROM appointment;
DELETE FROM tag;

-- create tags
INSERT INTO tag (name, color)
VALUES ('Free Time', '56D6C1'),
       ('Dentist', '5574E6'),
       ('School', 'E64132'),
       ('Weekend', 'F5D133');

-- create appointments
INSERT INTO appointment (start, end, name, description, creator_id)
VALUES ('2021-03-09 13:00:00', '2021-03-09 15:00:00', 'Bowling', 'Wahoooo', @me),
       ('2021-03-08 08:00:00', '2021-03-08 16:00:00', 'gibb', 'Noooo', @me),
       ('2021-03-11 15:00:00', '2021-03-11 16:00:00', 'Dentist', 'Aaaaah', @me),
	   ('2021-03-17 16:00:00', '2021-03-17 16:30:00', 'Buy Cookies', 'Greattt', @me),
       ('2021-03-19 13:00:00', '2021-03-19 15:30:00', 'Cinema', 'Yeeee', @me),
       ('2021-03-28 00:00:00', '2021-03-28 13:00:00', 'Limbo', 'Neutral', @me);

-- connect appointments to users
INSERT INTO appointment_user (appointment_id, user_id)
VALUES ((SELECT id FROM appointment WHERE name = 'Bowling'), @me),
       ((SELECT id FROM appointment WHERE name = 'gibb'), @me),
       ((SELECT id FROM appointment WHERE name = 'Dentist'), @me),
	   ((SELECT id FROM appointment WHERE name = 'Buy Cookies'), @me),
       ((SELECT id FROM appointment WHERE name = 'Cinema'), @me),
       ((SELECT id FROM appointment WHERE name = 'Limbo'), @me);

-- connect appointments to tags
INSERT INTO appointment_tag (appointment_id, tag_id)
VALUES ((SELECT id FROM appointment WHERE name = 'Bowling'), (SELECT id FROM tag WHERE name = 'Free Time')),
       ((SELECT id FROM appointment WHERE name = 'gibb'), (SELECT id FROM tag WHERE name = 'School')),
       ((SELECT id FROM appointment WHERE name = 'Dentist'), (SELECT id FROM tag WHERE name = 'Dentist')),
       ((SELECT id FROM appointment WHERE name = 'Cinema'), (SELECT id FROM tag WHERE name = 'Free Time')),
       ((SELECT id FROM appointment WHERE name = 'Cinema'), (SELECT id FROM tag WHERE name = 'Weekend')),
       
       ((SELECT id FROM appointment WHERE name = 'Limbo'), (SELECT id FROM tag WHERE name = 'Weekend')),
       ((SELECT id FROM appointment WHERE name = 'Limbo'), (SELECT id FROM tag WHERE name = 'School')),
       ((SELECT id FROM appointment WHERE name = 'Limbo'), (SELECT id FROM tag WHERE name = 'Free Time')),
       ((SELECT id FROM appointment WHERE name = 'Limbo'), (SELECT id FROM tag WHERE name = 'Dentist'));