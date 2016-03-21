<?php

include_once(realpath(dirname(__FILE__)) . "/../model/albums.php");
include_once(realpath(dirname(__FILE__)) . "/../model/artists.php");

class Albums_Controller
{
    /* --------------------------------------------------------------------------------------------
     * Main site controller functions
     * ------------------------------------------------------------------------------------------*/

    /**
      * Get a list of all albums
      */
     public function getAllAlbums()
     {
          return Album::getAlbumsList();
     }

     /**
      * Returns an album for the given id
      */
     public function getAlbum($albumId)
     {
          //Get the album from the model using the ID passed in
          return Album::getAlbum($albumId);
     }

     /**
      * Adds an album to the model
      *
      * @param[in] title       The new album's title
      * @param[in] artist      Artist's name for the new album
      * @param[in] imageName   Filename of uploaded image (from _FILES['newArtwork']['name'])
      *                        or NULL if no image is required
      * @param[in] tmpImgName  Filename of temp image upload (from _FILES['newArtwork']['tmp_name'])
      *                        or NULL if no image is required
      */
     public function addAlbum($title, $artist, $imageName, $tmpImgName)
     {
          if(0 == strlen($title) || 0 == strlen($artist))
          {
               // Display an alert window and return if the field was empty
               echo "<script type=\"text/javascript\">
                         alert(\"The title and/or artist name field(s) cannot be empty\");
                    </script>";

               return;
          };

          // First, check if the artist exists. If not, make a new entry
          $artistId = Artist::getArtistId($artist);
          if ($artistId < 0)
          {
               $artistId = Artist::addArtist($artist);
          }

          $newId = Album::addAlbum($title, $artistId);

          // If the new Id is negative, soemthign went wrong
          if ($newId < 0)
          {
               echo "<script type=\"text/javascript\">
                         alert(\"Failed to add the new album\");
                    </script>";

               return;
          }

          // If an artwork image was provided, set it
          if ($imageName != NULL)
          {
               $this->uploadAlbumArtwork($newId, $imageName, $tmpImgName);
          }

          echo "<script type=\"text/javascript\">
                    alert(\"Successfully added a new album\");
               </script>";
     }

     /**
      * Deletes an album from the model
      *
      * @param[in] albumID  ID of the album to remove
      */
     public function deleteAlbum($albumID)
     {
          // Pass on the id to the model to handle the deletion
          $success = Album::deleteAlbum($albumID);

          if ($success)
          {
               echo "<script type=\"text/javascript\">
                         alert(\"Successfully deleted the album\");
                    </script>";
          }
          else
          {
               echo "<script type=\"text/javascript\">
                         alert(\"ERROR: Could not delete the album.\");
                    </script>";

               return;
          }
     }

     /**
      * Updates an album's title
      *
      * @param[in] albumId        ID of the album to update
      * @param[in] newAlbumTitle  The new title to give to the album with the given ID
      */
     public function updateAlbumTitle($albumId, $newAlbumTitle)
     {
          if(0 == strlen($newAlbumTitle))
          {
               // Display an alert window and return if the field was empty
               echo "<script type=\"text/javascript\">
                         alert(\"The new title field cannot be empty\");
                    </script>";

               return;
          }

          //Get the album from the model with the given ID
          $album = Album::getAlbum($albumId);

          $success = Album::updateAlbumTitle($albumId, $newAlbumTitle);
          if ($success)
          {
               echo "<script type=\"text/javascript\">
                         alert(\"Successfully updated the album title\");
                    </script>";
          }
          else
          {
               echo "<script type=\"text/javascript\">
                         alert(\"ERROR: Could not update the album title\");
                    </script>";

               return;
          }

          // If not using the default artwork img, update to the new name
          if (strcmp("default.png", basename($album->artwork_url)) !== 0)
          {
               // Construct the relative image urls
               $oldImage = "../../artwork/" . basename($album->artwork_url);
               $newImage = "../../artwork/" . $newAlbumTitle . '.' .
                           pathinfo($oldImage, PATHINFO_EXTENSION);

               // Rename the image
               rename($oldImage, $newImage);

               $newAbsolute = "/350_a3/artwork/" . basename($newImage);

               // And update the url in the db
               Album::updateAlbumArtUrl($albumId, $newAbsolute);
          }
     }

     /**
      * Stores a new artwork image for a given album
      *
      * @param[in] albumId     ID of the album to update
      * @param[in] imageName   Filename of uploaded image (from _FILES['newArtwork']['name'])
      * @param[in] tmpImgName  Filename of temp image upload (from _FILES['newArtwork']['tmp_name'])
      */
     public function uploadAlbumArtwork($albumId, $imageName, $tmpImgName)
     {
          // Grab the album with the given ID
          $album = Album::getAlbum($albumId);

          // Set the destination loaction. Use the album's name as the image name
          $newFileName = $album->title . "." . pathinfo($imageName, PATHINFO_EXTENSION);
          $uploadDir = "../../artwork/";
          $uploadFile = $uploadDir . $newFileName;


          // Copy the tmp img to the destination location
          if (!move_uploaded_file($tmpImgName, $uploadFile))
          {
              die("ERROR: Possible file upload attack!");
          }

          // Use absolute path to the image in the DB
          $absolutePath = "/350_a3/artwork/" . $newFileName;
          $success = Album::updateAlbumArtUrl($albumId, $absolutePath);

          if ($success)
          {
               echo "<script type=\"text/javascript\">
                         alert(\"Successfully uploaded new album art\");
                    </script>";
          }
          else
          {
               echo "<script type=\"text/javascript\">
                         alert(\"ERROR: Could not upload new album art\");
                    </script>";
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
        // If an ID was provided, get that album
        if (count($routes) > 1 && preg_match('/[0-9]*/',$routes[1]))
        {
            $id = $routes[1];

            return json_encode(Album::getAlbum($id));
        }
        // Otherwise get all albums
        else
        {
            return json_encode(Album::getAlbumsList());
        }
    }

    /**
     * Handles a POST request
     */
    function processPost($input)
    {
        $reqStatus = new RequestStatus();
        $reqStatus->action = 'POST';
        $reqStatus->id_affected = -1;

        //Make sure a title and artist was provided for the new album
        if (isset($input["title"]) && isset($input["artist"]) && count($input) == 2)
        {
            $title = $input["title"];
            $artist = $input["artist"];

            // First, check if the artist exists. If not, make a new entry
            $artistId = Artist::getArtistId($artist);
            if ($artistId < 0)
            {
                $artistId = Artist::addArtist($artist);
            }

            // Then add the album
            $newId = Album::addAlbum($title, $artistId);

            // The new ID will be -1 if there was an error adding the new album
            if ($newId <= 0)
            {
                $reqStatus->status = 'Failure';
                $reqStatus->comment = 'Failed to create the new album.';
            }
            else
            {
                $reqStatus->status = 'Success';
                $reqStatus->comment = 'Album ' . $newId . ' successfully added.';
                $reqStatus->id_affected = $newId;
            }
        }
        else
        {
            $reqStatus->status = 'Failure';
            $reqStatus->comment = 'Expects a title and artist name when creating a new album';
        }

        return json_encode($reqStatus);
    }

    /**
     * Handles a PUT request
     */
    function processPut($routes, $input)
    {
        $reqStatus = new RequestStatus();
        $reqStatus->action = 'PUT';
        $reqStatus->id_affected = -1;

        // Make sure an ID was provided
        if (count($routes) > 1 && preg_match('/[0-9]*/',$routes[1]))
        {
            //Then make sure a new title was provided for the artist
            if (isset($input["new_title"]) && count($input) == 1)
            {
                // Grab the new title and album ID provided
                $newTitle = $input["new_title"];
                $id = $routes[1];
                $reqStatus->id_affected = $id;

                // Try to update the albums's name
                $succ = Album::updateAlbumTitle($id, $newTitle);

                if ($succ)
                {
                    $reqStatus->status = 'Success';
                    $reqStatus->comment = 'Title updated for album '. $id;
                }
                else
                {
                    $reqStatus->status = 'Failure';
                    $reqStatus->comment = 'Could not update the title for album ' . $id;
                }
            }
            else
            {
                $reqStatus->status = 'Failure';
                $reqStatus->comment = 'Only a new_title is expected when updating an album';
            }
        }
        else
        {
            $reqStatus->status = 'Failure';
            $reqStatus->comment = 'Must provide an ID for the album you wish to update.';
        }

        return json_encode($reqStatus);
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
