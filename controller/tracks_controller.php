<?php

include_once(realpath(dirname(__FILE__)) . "/../model/tracks.php");
include_once(realpath(dirname(__FILE__)) . "/../model/albums.php");
include_once(realpath(dirname(__FILE__)) . "/../model/artists.php");

class Tracks_Controller
{
    /* --------------------------------------------------------------------------------------------
     * Main site controller functions
     * ------------------------------------------------------------------------------------------*/

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

    /* --------------------------------------------------------------------------------------------
     * API controller functions
     * ------------------------------------------------------------------------------------------*/

    /**
     * Handles a GET request
     */
    function processGet($routes)
    {
        // If an ID was provided, get that track
        if (count($routes) > 1 && preg_match('/[0-9]*/',$routes[1]))
        {
            $id = $routes[1];

            return json_encode(Track::getTrack($id));
        }
        // Otherwise get all tracks
        else
        {
            return json_encode(Track::getTracksList());
        }
    }

    /**
     * Handles a POST request
     */
    function processPost($input)
    {

    }

    /**
     * Handles a PUT request
     */
    function processPut($routes, $input)
    {

    }

    /**
     * Handles a DELETE request
     */
    function processDelete($routes)
    {

    }

    /**
     * Processes an API query
     *
     * @param[in] routes  The URI route, broken into an array
     * @param[in] method  HTTP method
     * @param[in] input   Any potential input parameters
     */
    function processQuery($routes, $method, $input)
    {
        switch($method)
        {
            case 'GET':
                return $this->processGet($routes);
                break;

            case 'POST':
                return $this->processPost($input);
                break;

            case 'PUT':
                return $this->processPut($routes, $input);
                break;

            case 'DELETE':
                return $this->processDelete($routes);
                break;

            default:
                $reqStatus = new RequestStatus();
                $reqStatus->action = $method;
                $reqStatus->id_affected = -1;
                $reqStatus->status = 'Failure';
                $reqStatus->comment = 'Requested HTTP method not supported';

                return json_encode($reqStatus);
        }
    }
}
