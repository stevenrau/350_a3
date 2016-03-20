<?php

include_once(realpath(dirname(__FILE__)) . "/../model/tracks.php");
include_once(realpath(dirname(__FILE__)) . "/../model/albums.php");
include_once(realpath(dirname(__FILE__)) . "/../model/artists.php");

class Tracks_Controller
{
     /**
      * Get a list of all tracks
      */
     public function getAllTracks()
     {
          return Track::getTracksList();
     }

     /**
      * Returns a track for the given id
      */
     public function getTrack($trackId)
     {
          //Get the track from the model using the ID passed in
          return Track::getTrack($trackId);
     }

     /**
      * Adds a track to the model
      *
      * @param[in] title    The new track's title
      * @param[in] artist   Artis name for the new track
      * @param[in] album    Album name for the new track
      */
     public function addTrack($title, $artist, $album)
     {
          if(0 == strlen($title) || 0 == strlen($artist) || 0 == strlen($album))
          {
               // Display an alert window and return if the field was empty
               echo "<script type=\"text/javascript\">
                         alert(\"The fields cannot be empty\");
                    </script>";

               return;
          };

          // First, check if the artist exists. If not, make a new entry
          $artistId = Artist::getArtistId($artist);
          if ($artistId < 0)
          {
               $artistId = Artist::addArtist($artist);
          }

          // Then check if the album exists. If not, make a new entry with the artist Id just retrieved
          $albumId = Album::getAlbumId($album);
          if ($albumId < 0)
          {
               $albumId = Album::addAlbum($album, $artistId);
          }

          $newId = Track::addTrack($title, $artistId, $albumId);

          // If the new Id is negative, soemthing went wrong
          if ($newId < 0)
          {
               echo "<script type=\"text/javascript\">
                         alert(\"Failed to add the new track\");
                    </script>";

               return;
          }

          echo "<script type=\"text/javascript\">
                    alert(\"Successfully added a new track\");
               </script>";
     }

     /**
      * Deletes a track from the model
      *
      * @param[in] trackId  ID of the track to remove
      */
     public function deleteTrack($trackId)
     {
          // Pass on the id to the model to handle the deletion
          $success = Track::deleteTrack($trackId);

          if ($success)
          {
               echo "<script type=\"text/javascript\">
                         alert(\"Successfully deleted the track\");
                    </script>";
          }
          else
          {
               echo "<script type=\"text/javascript\">
                         alert(\"ERROR: Could not delete the track.\");
                    </script>";

               return;
          }
     }

     /**
      * Updates a track's title
      *
      * @param[in] trackId        ID of the track to update
      * @param[in] newTrackTitle  The new title to give to the track with the given ID
      */
     public function updateTrackTitle($trackId, $newTrackTitle)
     {
          if(0 == strlen($newTrackTitle))
          {
               // Display an alert window and return if the field was empty
               echo "<script type=\"text/javascript\">
                         alert(\"The new title field cannot be empty\");
                    </script>";

               return;
          }

          $success = Track::updateTrackTitle($trackId, $newTrackTitle);
          if ($success)
          {
               echo "<script type=\"text/javascript\">
                         alert(\"Successfully updated the track title\");
                    </script>";
          }
          else
          {
               echo "<script type=\"text/javascript\">
                         alert(\"ERROR: Could not update the track title\");
                    </script>";

               return;
          }

     }
}
