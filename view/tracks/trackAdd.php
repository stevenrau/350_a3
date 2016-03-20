<?php
    // Include the tracks controller file
    include("../../controller/tracks_controller.php");

    $controller = new Tracks_Controller();

    // If the submit new track button was pressed, send info to the controller
    if( isset($_POST['submit']) )
    {
        $newTitle = $_POST['newTitle'];
        $newArtist = $_POST['newArtist'];
        $newAlbum = $_POST['newAlbum'];

        $controller->addTrack($newTitle, $newArtist, $newAlbum);
    }
?>

<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="/350_a3/a3Style.css">
        <title>Tracks</title>
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
            <h2> Add a new track </h2>

            <p> *NOTE* If the new track's artist and/or album does not exist in the database, a new entry for the artist/album will be created.</p>

            <form action="" method="post" enctype="multipart/form-data">
                <!-- Form for the new title  -->
                Title:
                <input class="form_input" type="text" name="newTitle">
                <br><br>
                <!-- Form for the artist name  -->
                Artist name:
                <input class="form_input" type="text" name="newArtist">
                <br><br>
                Album name:
                <input class="form_input" type="text" name="newAlbum">
                <br><br>
                <input type="submit" name="submit" value="Add">
            </form>


        </div>




        <footer>
            <small id="footer_copyright">Copyright &copy; 2016 Steven Rau</small>
        </footer>

    </body>

</html>
