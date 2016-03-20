<?php

     include_once("connection.php");
     include_once("artists.php");

     class Album
     {
          // Define the artist's attributes
          public $id;
          public $title;
          public $artwork_url;
          public $artist;
          public $artist_img_url;

          public function __construct($id, $title, $artwork_url, $artist, $artist_img_url)
          {
               $this->id = $id;
               $this->title = $title;
               $this->artwork_url = $artwork_url;
               $this->artist = $artist;
               $this->artist_img_url = $artist_img_url;
          }

          /*
           * Adds a new album to the model with the given title and artist ID that the album
           * is associated with
           */
          public static function addAlbum($title, $artistId)
          {
               $db = Database::getInstance();

               // insert the new album info
               $sql = 'INSERT INTO albums(title, artist_id) VALUES (\'' . $title . '\', ' . $artistId . ')';
               $db->query($sql);

               // Return the newly added ID
               return $db->insert_id;
          }

           /*
           * Deletes album with the given ID from the database
           */
          public static function deleteAlbum($albumId)
          {
               $db = Database::getInstance();

               $sql = 'DELETE FROM albums WHERE id=' . $albumId;

               $succ = $db->query($sql);

               return $succ;
          }

          /*
           * Gets the album for the provided ID
           */
          public static function getAlbum($albumId)
          {
               $db = Database::getInstance();
               $req = $db->query('SELECT * from albums WHERE id = ' . $albumId);

               $album = $req->fetch_assoc();

               // Get the artist associated with this album
               $artist = Artist::getArtistById($album['artist_id']);

               return new Album($album['id'], $album['title'], $album['artwork_url'],
                                $artist->name, $artist->thumbnail_url);
          }

          /*
           * Static function to get all albums from the DB in a list
           */
          public static function getAlbumsList()
          {
               $list = [];
               $db = Database::getInstance();
               $req = $db->query('SELECT * FROM albums');

               // Create a list of all albums from the database results
               while ($album = $req->fetch_assoc())
               {
                    // Get the artist associated with this album
                    $curArtist = Artist::getArtistById($album['artist_id']);

                    $list[] = new Album($album['id'], $album['title'], $album['artwork_url'],
                                        $curArtist->name, $curArtist->thumbnail_url);
               }

               return $list;
          }

          /*
           * Updates the given album's title
           */
          public static function updateAlbumTitle($albumId, $newAlbumTitle)
          {
               $db = Database::getInstance();

               $sql = 'UPDATE albums SET title=\'' . $newAlbumTitle . '\' WHERE id=' . $albumId;

               $succ = $db->query($sql);

               return $succ;
          }

          /*
           * Updates a given album's artowrk URL
           */
          public static function updateAlbumArtUrl($albumId, $artworkUrl)
          {
               $db = Database::getInstance();
               $sql = 'UPDATE albums SET artwork_url=\'' . $artworkUrl . '\' WHERE id=' . $albumId;
               $succ = $db->query($sql);

               return $succ;
          }

          /**
           * Returns the ID for the album with the given name or -1 if it does not exist
           */
          public static function getAlbumId($title)
          {
               $db = Database::getInstance();

               $sql = 'SELECT * FROM albums WHERE title=\'' . $title . '\'';
               $req = $db->query($sql);
               if ($req->num_rows == 0)
               {
                    return -1;
               }

               $album = $req->fetch_assoc();

               return $album['id'];
          }

     }

?>

