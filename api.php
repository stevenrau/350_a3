<?php

    /* --------------------------------------------------------------------------------------------
     * Constants and datatypes
     * ------------------------------------------------------------------------------------------*/

    // HTTP error code for a bad client request
    $BAD_REQUEST_ERR = 400;

    // Define some constant IDs for each table to avoid a lot of string comparisons
    class TableIds
    {
        const UNKNOWN = 0;
        const ARTISTS_TABLE = 1;
        const ALBUMS_TABLE = 2;
        const TRACKS_TABLE = 3;
    }

    // A class to act as a JSON struct that will be returned to indicate the status of a request
    class RequestStatus
    {
        public $status;       // Status of the request (Success or failure)
        public $action;       // The action that was performed
        public $id_affected;  // ID of the entry affected by the operation
        public $comment;      // Any additional comments
    }

    /* --------------------------------------------------------------------------------------------
     * File helper functions
     * ------------------------------------------------------------------------------------------*/

    /**
     * Includes the controller necessary for the requested operation
     * If it is an invalid URI param, do not bother including anything.
     *
     * ex: 350_a3/api.php/albums/  Needs to include controller/albums_controller.php
     *
     * NOTE: # is used as my preg_match delimeter
     */
    function autoInclude($contrName)
    {
        // If the given route controller matches one of the three expected,
        // include it. This regex match ensures the URI does not have to be case sensitive
        if (preg_match("#(albums)#i", $contrName) || preg_match("#(artists)#i", $contrName) ||
            preg_match("#(tracks)#i", $contrName))
        {
            include('controller/' . strtolower($contrName) . '_controller.php');
        }
    }

    /**
     * Gets the current URI relative to this api.php script
     */
    function getCurrentUri()
    {
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));

        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = '/' . trim($uri, '/');

        return $uri;
    }

    function getTableId($tableName)
    {
        $tableName = strtolower($tableName);
        $id = 0;

        switch($tableName)
        {
            case 'albums':
                $id = TableIds::ALBUMS_TABLE;
                break;
            case 'artists':
                $id = TableIds::ARTISTS_TABLE;
                break;
            case 'tracks':
                $id = TableIds::TRACKS_TABLE;
                break;
            default:
                $id = TableIds::UNKNOWN;
        }

        return $id;
    }


    /* --------------------------------------------------------------------------------------------
     * Main code
     * ------------------------------------------------------------------------------------------*/

    $base_url = getCurrentUri();

    $routes = array();
    $base_routes = explode('/', $base_url);

    // Form an array where each offset is a subsection of the route
    foreach($base_routes as $route)
    {
        if(trim($route) != '')

        //Match regular expresssions Push one or more elements onto the end of array
        array_push($routes,$route);
    }

    // Grab the HTTP method
    $method = $_SERVER['REQUEST_METHOD'];

    // If no URI param was provided, nothing can be done, so quit
    if (count($routes) == 0)
    {
        http_response_code($BAD_REQUEST_ERR);

        die('Bad API request: No route specified.');
    }

    // Now that we have the request route, include the necessary controller(s)
    autoInclude($routes[0]);

    // Get the table ID from the route so that we know how to handle the URI request
    $table = getTableId($routes[0]);

    // Grab any potential input
    $input = json_decode(file_get_contents('php://input'),true);

    // Go to the proper helper function depending on what table/controller we need to
    // interface with
    switch ($table)
    {
        case TableIds::ARTISTS_TABLE:
            $artistsHandler = new Artists_Controller();
            echo ($artistsHandler->processQuery($routes, $method, $input));

            break;

        case TableIds::ALBUMS_TABLE:
            $albumsHandler = new Albums_Controller();
            echo ($albumsHandler->processQuery($routes, $method, $input));
            break;

        case TableIds::TRACKS_TABLE:
            $tracksHandler = new Tracks_Controller();
            echo ($tracksHandler->processQuery($routes, $method, $input));
            break;

        default:
            http_response_code($BAD_REQUEST_ERR);
            die('Bad API request: Table "' . $routes[0] . '" does not exist.');
    }
?>
