<?php
    // Include the album controller file
    include("../../controller/albums_controller.php");

    $controller = new Albums_Controller();

    // If the submit new album button was pressed, send info to the controller
    if( isset($_POST['submit']) )
    {
        $newTitle = $_POST['newTitle'];
        $newArtist = $_POST['newArtist'];
        $imgName = NULL;
        $imgTmpName = NULL;

        // If an artwork image was provided, get the name details
        if (is_uploaded_file($_FILES['newArtwork']['tmp_name']))
        {
            $imgName = $_FILES["newArtwork"]["name"];
            $imgTmpName = $_FILES["newArtwork"]["tmp_name"];
        }

        $controller->addAlbum($newTitle, $newArtist, $imgName, $imgTmpName);
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
            <h2> Add an album </h2>

            <p> *NOTE* If the new album's artist does not exist in the database, a new entry for the artist will be created.</p>

            <form action="" method="post" enctype="multipart/form-data">
                <!-- Form for the new title  -->
                Title:
                <input class="form_input" type="text" name="newTitle">
                <br><br>
                <!-- Form for the artist name  -->
                Artist name:
                <input class="form_input" type="text" name="newArtist">
                <br><br>
                <!-- Upload a new artist image -->
                <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                Upload album art (optional):
                <input type="file" name="newArtwork"  accept="image/*"/>
                <br><br>
                <input type="submit" name="submit" value="Add">
            </form>


        </div>




        <footer>
            <small id="footer_copyright">Copyright &copy; 2016 Steven Rau</small>
        </footer>

    </body>

</html>
