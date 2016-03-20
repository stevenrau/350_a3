<?php
    // Include the artists controller file
    include("../../controller/artists_controller.php");

    // Get the artist we want to update
    $controller = new Artists_Controller();
    $artist = $controller->getArtist();

    // If the submit change button was pressed, send info to the controller
    if( isset($_POST['submitNewName']) )
    {
        $newName = $_POST['newName'];

        $controller->updateArtistName($_GET['artistId'], $_POST['newName']);
    }

    // If the upload new thumbnail button was pressed, send img info to the controller
    if( isset($_POST['submitNewThumbnail']) )
    {
        $controller->uploadArtistThumbnail($_GET['artistId'],
                                           $_FILES["newThumbnail"]["name"],
                                           $_FILES["newThumbnail"]["tmp_name"]);
    }
?>

<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="/350_a3/a3Style.css">
        <title>Artists</title>
    </head>

    <body>
        <header>
            <a href="/350_a3/index.php"><img id="header_logo" src="/350_a3/icons/site_logo.png" alt="logo" width='15%' height='auto' /></a>
            <h1 id="site_title">Steven Rau - Music Database</h1>
            <nav>
                <a class="navigation" href="/350_a3/index.php">Home</a> |
                <a class="navigation" href="/350_a3/view/tracks/tracks.php">Tracks</a> |
                <a class="navigation" href="/350_a3/view/artists/artists.php">Artists</a> |
                <a class="navigation" href="/350_a3/view/albums/albums.php">Albums</a>
            </nav>
        </header>

        <div id="home_main">
            <h2> Update Artist Info </h2>

            <!-- Display the current artist info to be updated -->
            <h3 class="operation_header"> Artist chosen to update: </h3>
            <p> *NOTE* Any updates made to this artist will carry over into any tracks or albums associated with this artist.</p>
            <table id="artist_list" border="1" cellpadding="5">
                <tr>
                    <td class="artist_list_header">Name</td>
                    <td class="artist_list_header">Artist Image</td>
                </tr>
                <tr>
                    <td> <?php echo $artist->name ?></td>
                    <td class="imgCol"> <img class="artistImg" src=" <?php echo $artist->thumbnail_url ?>" width='45%' height='auto' /></td>
                    <?php $artistId = $artist->id; ?>

                </tr>
            </table>

            <!-- Use POST form to submit the new name  -->
            <form action="" method="post">
                New name:
                <input class="form_input" type="text" name="newName">
                <input type="submit" name="submitNewName" value="Submit">
            </form>

            <!-- Upload a new artist image -->
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                Upload new artist thumbnail image:
                <input type="file" name="newThumbnail"  accept="image/*"/>
                <input type="submit" name="submitNewThumbnail" value="Upload" />
            </form>
        </div>




        <footer>
            <small id="footer_copyright">Copyright &copy; 2016 Steven Rau</small>
        </footer>

    </body>

</html>
