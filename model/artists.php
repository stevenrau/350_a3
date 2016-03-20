<?php

     include_once("connection.php");

     class Artist
     {
          // Define the artist's attributes
          public $id;
          public $name;
          public $thumbnail_url;

          public function __construct($id, $name, $tumbnail_url)
          {
               $this->id = $id;
               $this->name = $name;
               $this->thumbnail_url = $tumbnail_url;
          }

          /**
           * Adds a new artist with a given name. The thumbnail_url will be left at the default
           * value and will have to be set with updateArtistThumbUrl() if required
           *
           * Returns the ID of the newly created artist
           */
          public static function addArtist($name)
          {
               $db = Database::getInstance();

               // First make sure there isn't already an entry with the given name
               $sql = 'SELECT * FROM artists WHERE name=\'' . $name . '\'';
               $req = $db->query($sql);
               if ($req->num_rows != 0)
               {
                    return -1;
               }

               // insert the new name
               $sql = 'INSERT INTO artists(name) VALUES (\'' . $name . '\')';
               $db->query($sql);

               // Then get the new id to return
               $sql = 'SELECT id FROM artists WHERE name=\'' . $name . '\'';
               $req = $db->query($sql);
               $newId = $req->fetch_assoc();

               return $newId['id'];
          }

          /*
           * Deletes artist with the given ID from the database
           */
          public static function deleteArtist($artistId)
          {
               $db = Database::getInstance();

               $sql = 'DELETE FROM artists WHERE id=' . $artistId;

               $succ = $db->query($sql);

               return $succ;
          }


          /*
           * Static function to get all artists from the DB in a list
           */
          public static function getArtistsList()
          {
               $list = [];
               $db = Database::getInstance();
               $req = $db->query('SELECT * FROM artists');

               // Create a list of all artists from the database results
               while ($artist = $req->fetch_assoc())
               {
                    $list[] = new Artist($artist['id'], $artist['name'], $artist['thumbnail_url']);
               }

               return $list;
          }

          /*
           * Returns the artist associated witht the given id
           */
          public static function getArtistById($artistId)
          {
               $db = Database::getInstance();
               $req = $db->query('SELECT * from artists WHERE id = ' . $artistId);

               $newArtist = $req->fetch_assoc();

               return new Artist($newArtist['id'], $newArtist['name'], $newArtist['thumbnail_url']);
          }

          /*
           * Updates the given artist's name
           */
          public static function updateArtistName($artistId, $newName)
          {
               $db = Database::getInstance();

               // First make sure there isn't already an entry with the given name
               $sql = 'SELECT * FROM artists WHERE name=\'' . $newName . '\'';
               $req = $db->query($sql);
               if ($req->num_rows != 0)
               {
                    return False;
               }

               $sql = 'UPDATE artists SET name=\'' . $newName . '\' WHERE id=' . $artistId;

               $succ = $db->query($sql);

               return $succ;
          }

          /*
           * Updates the given artist's thumbnail URL
           */
          public static function updateArtistThumbUrl($artistId, $thumbnaillUrl)
          {
               $db = Database::getInstance();
               $sql = 'UPDATE artists SET thumbnail_url=\'' . $thumbnaillUrl . '\' WHERE id=' . $artistId;
               $succ = $db->query($sql);

               return $succ;
          }

          /**
           * Returns the ID for the artist witht he given name or -1 if they do not exists
           */
          public static function getArtistId($name)
          {
               $db = Database::getInstance();

               $sql = 'SELECT * FROM artists WHERE name=\'' . $name . '\'';
               $req = $db->query($sql);
               if ($req->num_rows == 0)
               {
                    return -1;
               }

               $artist = $req->fetch_assoc();

               return $artist['id'];
          }
     }


?>
