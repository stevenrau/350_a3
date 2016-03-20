<?php

include_once(realpath(dirname(__FILE__)) . "/../model/artists.php");

class Artists_Controller
{
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
}


?>
