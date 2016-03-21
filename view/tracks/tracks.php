<?php
    // Include the albums controller file
    include("../../controller/tracks_controller.php");

    // Get all of the albums
    $controller = new Tracks_Controller();
    $tracksList = $controller->getAllTracks();
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
            <h2> Tracks  </h2>

            <h3 class="operation_header"> Possible Operations: </h3>
            <ul id="operation_list">
                <li class="operation_list">
                    <a class="operation_list" href="trackAdd.php"> Add a new track </a>
                </li>
            </ul>

            <!-- Construct the table of all tracks -->
            <h3 class="operation_header"> List of all tracks: </h3>
            <table id="track_list" border="1" cellpadding="5">
                <tr>
                    <td class="track_list_header">Album Art</td>
                    <td class="track_list_header">Title</td>
                    <td class="track_list_header">Artist</td>
                    <td class="track_list_header">Album</td>
                </tr>
                <?php foreach ($tracksList as $curTrack) : ?>
                <tr>

                    <td class="imgCol"> <img class="albumImg" src=" <?php echo $curTrack->artwork_url ?>" /></td>
                    <td> <?php echo $curTrack->title ?></td>
                    <td> <?php echo $curTrack->artist ?></td>
                    <td> <?php echo $curTrack->album ?></td>

                    <!-- Get the current albums's id to use in _GET methods -->
                    <?php $trackId = $curTrack->id; ?>

                    <td>
                        <form name="editTrack" action="trackUpdate.php" method="GET">
                            <input type="hidden" name="trackId" value="<?php echo $trackId; ?>"/>
                            <input type="submit" name="editTrack" value="Edit"/>
                        </form>
                    </td>
                    <td>
                        <form name="deleteTrack" action="trackDelete.php" method="GET">
                            <input type="hidden" name="trackId" value="<?php echo $trackId; ?>"/>
                            <input type="submit" name="deleteTrack" value="Delete"/>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>


        <footer>
            <small id="footer_copyright">Copyright &copy; 2016 Steven Rau</small>
        </footer>

    </body>

</html>
