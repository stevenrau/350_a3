-- Create and select the database
CREATE DATABASE IF NOT EXISTS music ;
USE music ;


-- Create the three tables

CREATE TABLE IF NOT EXISTS artists
(
  id             int(6)       UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name           varchar(50)  NOT NULL,
  thumbnail_url  varchar(256) DEFAULT '/350_a3/artist_thumbnail/default.png'
           -- Artist thumnbnail img URL can be empty since we want to allow creating an
           -- artist without providing a thumbnail image.
           -- If thumbnail image is provided for an album, it will be stored in
           -- a file folder and the location will be saved here
);

CREATE TABLE IF NOT EXISTS albums
(
  id           int(6)         UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title        varchar(50)    NOT NULL,
  artist_id    int(6)         UNSIGNED NOT NULL,
  artwork_url  varchar(256)   DEFAULT '/350_a3/artwork/default.png',
           -- Artwork URL can be empty since we want to allow creating an
           -- album without providing artwork
           -- If atwork is provided for an album, it will be stored in a file
           -- folder and the location will be saved here
  FOREIGN KEY (artist_id)  REFERENCES artists(id) ON DELETE CASCADE ON UPDATE CASCADE
           -- Cascade DELETE from parent table 'artists' to this child table
           -- i.e. If an artist is deleted from the artist table, all records
           --      involving that atist in this child table are also deleted
);

CREATE TABLE IF NOT EXISTS tracks
(
  id         int(6)       UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title      varchar(50)  NOT NULL,
  artist_id  int(6)       UNSIGNED NOT NULL,
  album_id   int(6)       UNSIGNED NOT NULL,
  FOREIGN KEY (artist_id) REFERENCES artists(id) ON DELETE CASCADE ON UPDATE CASCADE,
           -- Cascade DELETE from parent table 'artists' to this child table
           -- i.e. If an artist is deleted from the artist table, all records
           --      involving that atist in this child table are also deleted
  FOREIGN KEY (album_id)  REFERENCES albums(id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- Insert artists into the table
INSERT INTO artists(name) VALUES ('John Denver');
INSERT INTO artists(name) VALUES ('Kendrick Lamar');
INSERT INTO artists(name) VALUES ('Adele');
INSERT INTO artists(name) VALUES ('Taylor Swift');
INSERT INTO artists(name) VALUES ('Johnny Cash');

-- Give some of the artists thumbnails
UPDATE artists SET thumbnail_url='/350_a3/artist_thumbnail/John Denver.jpg' WHERE name='John Denver';
UPDATE artists SET thumbnail_url='/350_a3/artist_thumbnail/Adele.jpg' WHERE name='Adele';
UPDATE artists SET thumbnail_url='/350_a3/artist_thumbnail/Taylor Swift.jpg' WHERE name='Taylor Swift';


-- Insert albums into the table
INSERT INTO albums(title, artist_id) VALUES ('To Pimp a Butterfly', 2);
INSERT INTO albums(title, artist_id) VALUES ('25', 3);
INSERT INTO albums(title, artist_id) VALUES ('1989', 4);

-- Give some of the albums artwork
UPDATE albums SET artwork_url='/350_a3/artwork/To Pimp a Butterfly.png' WHERE title='To Pimp a Butterfly';
UPDATE albums SET artwork_url='/350_a3/artwork/1989.jpg' WHERE title='1989';


-- Insert tracks into the table
INSERT INTO tracks(title, artist_id, album_id) VALUES ('Alright', 2, 1);
INSERT INTO tracks(title, artist_id, album_id) VALUES ('King Kunta', 2, 1);
INSERT INTO tracks(title, artist_id, album_id) VALUES ('Hello', 3, 2);
INSERT INTO tracks(title, artist_id, album_id) VALUES ('Bad Blood', 4, 3);
INSERT INTO tracks(title, artist_id, album_id) VALUES ('Blank Space', 4, 3);
INSERT INTO tracks(title, artist_id, album_id) VALUES ('Shake It Off', 4, 3);
