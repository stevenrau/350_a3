<?php
    // Include the album controller file
    include("../../controller/albums_controller.php");

    // Get the artist we want to update
    $controller = new Albums_Controller();
    $album = $controller->getAlbum($_GET['albumId']);

    // If the submit change button was pressed, send info to the controller
    if( isset($_POST['submitNewTitle']) )
    {
        $controller->updateAlbumTitle($_GET['albumId'], $_POST['newTitle']);
    }

    // If the upload new thumbnail button was pressed, send img info to the controller
    if( isset($_POST['submitNewArtwork']) )
    {
        $controller->uploadAlbumArtwork($_GET['albumId'],
                                        $_FILES["newArtwork"]["name"],
                                        $_FILES["newArtwork"]["tmp_name"]);
    }
?>

<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="/350_a3/a3Style.css">
        <title>Albums</title>
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
            <h2> Update Album Info </h2>

            <!-- Display the current artist info to be updated -->
            <h3 class="operation_header"> Album chosen to update: </h3>
            <p> *NOTE* Any updates made to this album will carry over into any tracks associated with this album.</p>
            <table id="album_list" border="1" cellpadding="5">
                <tr>
                    <td class="album_list_header">Album Art</td>
                    <td class="album_list_header">Title</td>
                    <td class="album_list_header">Artist</td>
                    <td class="album_list_header">Artist Image</td>
                </tr>
                <tr>
                    <td class="imgCol"> <img class="albumImg" src=" <?php echo $album->artwork_url ?>" /></td>
                    <td> <?php echo $album->title ?></td>
                    <td> <?php echo $album->artist ?></td>
                    <td class="imgCol"> <img class="artistImg" src=" <?php echo $album->artist_img_url ?>" /></td>

                    <!-- Get the current albums's id to use in _GET methods -->
                    <?php $albumId = $album->id; ?>
                </tr>
            </table>

            <!-- Use POST form to submit the new name  -->
            <form action="" method="post">
                New title:
                <input class="form_input" type="text" name="newTitle">
                <input type="submit" name="submitNewTitle" value="Submit">
            </form>

            <!-- Upload a new album image -->
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                Upload new album artwork image:
                <input type="file" name="newArtwork"  accept="image/*"/>
                <input type="submit" name="submitNewArtwork" value="Upload" />
            </form>
        </div>




        <footer>
            <small id="footer_copyright">Copyright &copy; 2016 Steven Rau</small>
        </footer>

    </body>

</html>
