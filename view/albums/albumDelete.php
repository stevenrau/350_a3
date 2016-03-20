<?php
    // Include the artists controller file
    include("../../controller/albums_controller.php");

    $controller = new Albums_Controller();
    $album = $controller->getAlbum($_GET['albumId']);

    // If the confirm delete button was pressed, send info to the controller
    if( isset($_POST['confirmDelete']) )
    {
        $controller->deleteAlbum($_GET['albumId']);
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
            <h2> Delete Album </h2>

            <!-- Display the chosen artist info to be deleted -->
            <h3 class="operation_header"> Album chosen to delete: </h3>
            <p> *NOTE* By deleting this album, you will also delete any associated songs</p>
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
                Are you sure you want to delete this album?
                <input type="submit" name="confirmDelete" value="Delete">
            </form>

        </div>




        <footer>
            <small id="footer_copyright">Copyright &copy; 2016 Steven Rau</small>
        </footer>

    </body>

</html>
