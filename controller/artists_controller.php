<?php

include_once(realpath(dirname(__FILE__)) . "/../model/artists.php");

class Artists_Controller
{
    /* --------------------------------------------------------------------------------------------
     * Main site controller functions
     * ------------------------------------------------------------------------------------------*/

     /**
      * Get a list of all artists
      */
     public function getAllArtists()
     {
          return Artist::getArtistsList();
     }

     /**
      * Gets an artist specified by the provided ID
      */
     public function getArtist()
     {
          // If the artistId is not set in the URL, redirect to the error page
          if (!isset($_GET['artistId']))
          {
               header("Location: ../../error.php");
               die("ERROR: Missing artist ID");
          }

          //Get the artist from the model using the ID passed in the url
          return Artist::getArtistById($_GET["artistId"]);
     }

     /**
      * Adds an artist to the model
      *
      * @param[in] name        The new artist's name
      * @param[in] imageName   Filename of uploaded image (from _FILES['newThumbnail']['name'])
      *                        or NULL if no image is required
      * @param[in] tmpImgName  Filename of temp image upload (from _FILES['newThumbnail']['tmp_name'])
      *                        or NULL if no image is required
      */
     public function addArtist($name, $imageName, $tmpImgName)
     {
          if(0 == strlen($name))
          {
               // Display an alert window and return if the field was empty
               echo "<script type=\"text/javascript\">
                         alert(\"The name field cannot be empty\");
                    </script>";

               return;
          }

          $newId = Artist::addArtist($name);

          // If the new Id is negative, there was already an artist with the given name
          if ($newId < 0)
          {
               echo "<script type=\"text/javascript\">
                         alert(\"An artist with that name already exists\");
                    </script>";

               return;
          }

          // If an image was provided, set it
          if ($imageName != NULL)
          {
               $this->uploadArtistThumbnail($newId, $imageName, $tmpImgName);
          }

          echo "<script type=\"text/javascript\">
                    alert(\"Successfully added a new artist\");
               </script>";
     }

     /**
      * Deletes an artist from the model
      *
      * @param[in] artistId  ID of the artist to remove
      */
     public function deleteArtist($artistId)
     {
          // Pass on the id to the model to handle the deletion
          $success = Artist::deleteArtist($artistId);

          if ($success)
          {
               echo "<script type=\"text/javascript\">
                         alert(\"Successfully deleted the artist\");
                    </script>";
          }
          else
          {
               echo "<script type=\"text/javascript\">
                         alert(\"ERROR: Could not delete the artist.\");
                    </script>";

               return;
          }
     }

     /**
      * Updates an artist's name
      *
      * @param[in] artistId       ID of the artist to update
      * @param[in] newArtistName  The new name to give to the artist with the given ID
      */
     public function updateArtistName($artistId, $newArtistName)
     {
          if(0 == strlen($newArtistName))
          {
               // Display an alert window and return if the field was empty
               echo "<script type=\"text/javascript\">
                         alert(\"The new name field cannot be empty\");
                    </script>";

               return;
          }

          // Grab the artist with the given ID
          $artist = Artist::getArtistById($artistId);

          $success = Artist::updateArtistName($artistId, $newArtistName);
          if ($success)
          {
               echo "<script type=\"text/javascript\">
                         alert(\"Successfully updated the artist name\");
                    </script>";
          }
          else
          {
               echo "<script type=\"text/javascript\">
                         alert(\"ERROR: Could not update the artist name. Perhaps an artist already exists with that name.\");
                    </script>";

               return;
          }

          // If not using the default image, update to the new name
          if (strcmp("default.png", basename($artist->thumbnail_url)) !== 0)
          {
               // Construct the relative image urls
               $oldImage = "../../artist_thumbnail/" . basename($artist->thumbnail_url);
               $newImage = "../../artist_thumbnail/" . $newArtistName . '.' .
                           pathinfo($oldImage, PATHINFO_EXTENSION);

               // Rename the image
               rename($oldImage, $newImage);

               $newAbsolute = "/350_a3/artist_thumbnail/" . basename($newImage);

               // And update the url in the db
               Artist::updateArtistThumbUrl($artistId, $newAbsolute);
          }
     }

     /**
      * Stores a new thumbnail image for a given artist
      *
      * @param[in] artistId    ID of the artist to update
      * @param[in] imageName   Filename of uploaded image (from _FILES['newThumbnail']['name'])
      * @param[in] tmpImgName  Filename of temp image upload (from _FILES['newThumbnail']['tmp_name'])
      */
     public function uploadArtistThumbnail($artistId, $imageName, $tmpImgName)
     {
          // Grab the artist with the given ID
          $artist = Artist::getArtistById($artistId);

          // Set the destination loaction. Use the artist's name as the image name
          $newFileName = $artist->name . "." . pathinfo($imageName, PATHINFO_EXTENSION);
          $uploadDir = "../../artist_thumbnail/";
          $uploadFile = $uploadDir . $newFileName;


          // Copy the tmp img to the destination location
          if (!move_uploaded_file($tmpImgName, $uploadFile))
          {
              die("ERROR: Possible file upload attack!");
          }

          // Use absolute path to the image in the DB
          $absolutePath = "/350_a3/artist_thumbnail/" . $newFileName;
          $success = Artist::updateArtistThumbUrl($artistId, $absolutePath);

          if ($success)
          {
               echo "<script type=\"text/javascript\">
                         alert(\"Successfully uploaded new artist image\");
                    </script>";
          }
          else
          {
               echo "<script type=\"text/javascript\">
                         alert(\"ERROR: Could not upload new artist image\");
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
        // If an ID was provided, get that artist
        if (count($routes) > 1 && preg_match('/[0-9]*/',$routes[1]))
        {
            $id = $routes[1];

            return json_encode(Artist::getArtistById($id));
        }
        // Otherwise get all artists
        else
        {
            return json_encode(Artist::getArtistsList());
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

        //Make sure a name was provided for the new artist
        if (isset($input["name"]) && count($input) == 1)
        {
            $name = $input["name"];

            $newId = Artist::addArtist($name);

            // The new ID will be -1 if there was an error adding the new artist (duplicate name)
            if ($newId < 0)
            {
                $reqStatus->status = 'Failure';
                $reqStatus->comment = 'An artist with that name already exists.';
            }
            else
            {
                $reqStatus->status = 'Success';
                $reqStatus->comment = 'Artist ' . $newId . ' successfully added.';
                $reqStatus->id_affected = $newId;
            }
        }
        else
        {
            $reqStatus->status = 'Failure';
            $reqStatus->comment = 'Only a name is expected when creating a new artist';
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
            //Then make sure a new name was provided for the artist
            if (isset($input["new_name"]) && count($input) == 1)
            {
                // Grab the new name and artist ID provided
                $newName = $input["new_name"];
                $id = $routes[1];

                // Try to update the artist's name
                $succ = Artist::updateArtistName($id, $newName);

                if ($succ)
                {
                    $reqStatus->status = 'Success';
                    $reqStatus->comment = 'Name updated for artist '. $id;
                    $reqStatus->id_affected = $id;
                }
                else
                {
                    $reqStatus->status = 'Failure';
                    $reqStatus->comment = 'Could not update the name for artist ' . $id;
                }
            }
            else
            {
                $reqStatus->status = 'Failure';
                $reqStatus->comment = 'Only a new_name is expected when updating an artist';
            }
        }
        else
        {
            $reqStatus->status = 'Failure';
            $reqStatus->comment = 'Must provide an ID for the artist you wish to update.';
        }

        return json_encode($reqStatus);
    }

    /**
     * Handles a DELETE request
     */
    function processDelete($routes)
    {
        $reqStatus = new RequestStatus();
        $reqStatus->action = 'DELETE';
        $reqStatus->id_affected = -1;

        // Make sure an id was provided
        if (count($routes) > 1 && preg_match('/[0-9]*/',$routes[1]))
        {
            $id = $routes[1];

            $success = Artist::deleteArtist($id);

            if ($success)
            {
                $reqStatus->status = 'Success';
                $reqStatus->comment = 'Artist ' . $id . ' successfully deleted.';
                $reqStatus->id_affected = $id;
            }
            else
            {
                $reqStatus->status = 'Failure';
                $reqStatus->comment = 'Failed to delete artist with id ' . $id;
            }
        }
        else
        {
            $reqStatus->status = 'Failure';
            $reqStatus->comment = 'Must provide an artist ID to delete.';
        }

        return json_encode($reqStatus);
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


?>
