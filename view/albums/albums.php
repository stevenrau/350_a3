<?php
    // Include the albums controller file
    include("../../controller/albums_controller.php");

    // Get all of the albums
    $controller = new Albums_Controller();
    $albumsList = $controller->getAllAlbums();
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
            <h2> Albums  </h2>

            <h3 class="operation_header"> Possible Operations: </h3>
            <ul id="operation_list">
                <li class="operation_list">
                    <a class="operation_list" href="albumAdd.php"> Add a new album </a>
                </li>
            </ul>

            <!-- Construct the table of all albums -->
            <h3 class="operation_header"> List of all albums: </h3>
            <table id="album_list" border="1" cellpadding="5">
                <tr>
                    <td class="album_list_header">Album Art</td>
                    <td class="album_list_header">Title</td>
                    <td class="album_list_header">Artist</td>
                    <td class="album_list_header">Artist Image</td>
                </tr>
                <?php foreach ($albumsList as $curAlbum) : ?>
                <tr>

                    <td class="imgCol"> <img class="albumImg" src=" <?php echo $curAlbum->artwork_url ?>" /></td>
                    <td> <?php echo $curAlbum->title ?></td>
                    <td> <?php echo $curAlbum->artist ?></td>
                    <td class="imgCol"> <img class="artistImg" src=" <?php echo $curAlbum->artist_img_url ?>" /></td>

                    <!-- Get the current albums's id to use in _GET methods -->
                    <?php $albumId = $curAlbum->id; ?>

                    <td>
                        <form name="editAlbum" action="albumUpdate.php" method="GET">
                            <input type="hidden" name="albumId" value="<?php echo $albumId; ?>"/>
                            <input type="submit" name="editAlbum" value="Edit"/>
                        </form>
                    </td>
                    <td>
                        <form name="deleteAlbum" action="albumDelete.php" method="GET">
                            <input type="hidden" name="albumId" value="<?php echo $albumId; ?>"/>
                            <input type="submit" name="deleteAlbum" value="Delete"/>
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
