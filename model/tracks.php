<?php

     include_once("connection.php");
     include_once("artists.php");
     include_once("albums.php");

     class Track
     {
          // Define the artist's attributes
          public $id;
          public $title;
          public $artwork_url;
          public $artist;
          public $album;

          public function __construct($id, $title, $artwork_url, $artist, $album)
          {
               $this->id = $id;
               $this->title = $title;
               $this->artwork_url = $artwork_url;
               $this->artist = $artist;
               $this->album = $album;
          }

          /*
           * Adds a new track to the model with the given title, artist ID, and album ID
           */
          public static function addTrack($title, $artistId, $albumId)
          {
               $db = Database::getInstance();

               // insert the new track info
               $sql = 'INSERT INTO tracks(title, artist_id, album_id) VALUES (\'' . $title . '\', ' .            $artistId . ', ' . $albumId . ')';
               $succ = $db->query($sql);

              $newId = $db->insert_id;

              // Return the newly added ID or -1 if something went wrong
              if (!$succ)
              {
                  $newId = -1;
              }

               return $newId;
          }

           /*
           * Gets the track for the provided ID
           */
          public static function getTrack($trackId)
          {
               $db = Database::getInstance();
               $req = $db->query('SELECT * from tracks WHERE id = ' . $trackId);

               $track = $req->fetch_assoc();

               // Get the artist associated with this track
               $artist = Artist::getArtistById($track['artist_id']);

               // Get the album associated with this track
               $album = Album::getAlbum($track['album_id']);

               return new Track($track['id'], $track['title'], $album->artwork_url,
                                $artist->name, $album->title);
          }

          /*
           * Deletes track with the given ID from the database
           */
          public static function deleteTrack($trackId)
          {
               $db = Database::getInstance();

               $sql = 'DELETE FROM tracks WHERE id=' . $trackId;

               $succ = $db->query($sql);

              // If nothing was done, that means the ID does not exist in the DB
              if (mysqli_affected_rows($db) == 0)
              {
                  return False;
              }

               return $succ;
          }

          /*
           * Static function to get all tracks from the DB in a list
           */
          public static function getTracksList()
          {
               $list = [];
               $db = Database::getInstance();
               $req = $db->query('SELECT * FROM tracks');

               // Create a list of all albums from the database results
               while ($track = $req->fetch_assoc())
               {
                    // Get the artist associated with this track
                    $curArtist = Artist::getArtistById($track['artist_id']);

                    // Get the album associated with this track
                    $curAlbum = Album::getAlbum($track['album_id']);

                    $list[] = new Track($track['id'], $track['title'], $curAlbum->artwork_url,
                                        $curArtist->name, $curAlbum->title);
               }

               return $list;
           }

          /*
           * Updates the given track's title
           */
          public static function updateTrackTitle($trackId, $newTrackTitle)
          {
               $db = Database::getInstance();

               $sql = 'UPDATE tracks SET title=\'' . $newTrackTitle . '\' WHERE id=' . $trackId;

               $succ = $db->query($sql);

              // If nothing was done, that means the ID does not exist in the DB
              if (mysqli_affected_rows($db) == 0)
              {
                  return False;
              }

               return $succ;
          }
     }

?>
