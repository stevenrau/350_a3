<?php
    // Include the album controller file
    include("../../controller/tracks_controller.php");

    // Get the track we want to update
    $controller = new Tracks_Controller();
    $track = $controller->getTrack($_GET['trackId']);

    // If the submit change button was pressed, send info to the controller
    if( isset($_POST['submitNewTitle']) )
    {
        $controller->updateTrackTitle($_GET['trackId'], $_POST['newTitle']);
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
            <h2> Update Track Info </h2>

            <!-- Display the current track info to be updated -->
            <h3 class="operation_header"> Track chosen to update: </h3>
            <table id="track_list" border="1" cellpadding="5">
                <tr>
                    <td class="track_list_header">Album Art</td>
                    <td class="track_list_header">Title</td>
                    <td class="track_list_header">Artist</td>
                    <td class="track_list_header">Album</td>
                </tr>
                <tr>
                    <td class="imgCol"> <img class="albumImg" src=" <?php echo $track->artwork_url ?>" /></td>
                    <td> <?php echo $track->title ?></td>
                    <td> <?php echo $track->artist ?></td>
                    <td> <?php echo $track->album ?></td>

                    <!-- Get the current albums's id to use in _GET methods -->
                    <?php $trackId = $track->id; ?>
                </tr>
            </table>

            <!-- Use POST form to submit the new title  -->
            <form action="" method="post">
                New track title:
                <input class="form_input" type="text" name="newTitle">
                <input type="submit" name="submitNewTitle" value="Submit">
            </form>

        </div>




        <footer>
            <small id="footer_copyright">Copyright &copy; 2016 Steven Rau</small>
        </footer>

    </body>

</html>
