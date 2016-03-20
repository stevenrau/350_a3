<?php
    // Include the artists controller file
    include("../../controller/artists_controller.php");

    $controller = new Artists_Controller();

    // If the submit new asrtist button was pressed, send info to the controller
    if( isset($_POST['submit']) )
    {
        $newName = $_POST['newName'];
        $imgName = NULL;
        $imgTmpName = NULL;

        // If an image was provided, get the name details
        if (is_uploaded_file($_FILES['newThumbnail']['tmp_name']))
        {
            $imgName = $_FILES["newThumbnail"]["name"];
            $imgTmpName = $_FILES["newThumbnail"]["tmp_name"];
        }

        $controller->addArtist($newName, $imgName, $imgTmpName);
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
            <h2> Add an artist </h2>

            <form action="" method="post" enctype="multipart/form-data">
                <!-- Form for the new name  -->
                New name:
                <input class="form_input" type="text" name="newName">
                <br><br>
                <!-- Upload a new artist image -->
                <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                Upload artist image (optional):
                <input type="file" name="newThumbnail"  accept="image/*"/>
                <br><br>
                <input type="submit" name="submit" value="Add">
            </form>


        </div>




        <footer>
            <small id="footer_copyright">Copyright &copy; 2016 Steven Rau</small>
        </footer>

    </body>

</html>
