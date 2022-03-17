<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1.0, width=device-width" />
    <title>Interactive Map</title>
    <style>
        body {
            margin: 0;
            background-color: #2A2C39;
            font-family: 'Helvetica', sans-serif;
            font-weight: 200;
            font-size: 17px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        #map-holder {
            width: 100vw;
            height: 100vh;
        }

        svg rect {
            fill: #2A2C39;
            /* map background colour */
        }

        .country {
            fill: #d0d0d0;
            /* country colour */
            stroke: #2A2C39;
            /* country border colour */
            stroke-width: 1;
            /* country border width */
        }

        .country-on {
            fill: #4B5358;
            /* highlight colour for selected country */
        }

        .countryLabel {
            display: none;
            /* hide all country labels by default */
        }

        .countryName {
            fill: #FFFAFF;
            /* country label text colour */
        }

        .countryLabelBg {
            fill: #30BCED;
            /* country label background colour */
        }

        #rangeYear {
            position: absolute;
            bottom: 5vh;
            width: 90vw;
            display: flex;
            flex-direction: column;
            padding: 2rem;
            margin: auto;
            border-radius: 15px;
            background-color: rgba(255, 255, 255, 0.705);
            backdrop-filter: blur(10px);
        }

        #rangeYear input {
            width: 100%;
        }

        #rangeYear output {
            margin: auto;
        }

        #searchBar {
            position: absolute;
            top: 5vh;
            left: 2vw;
            width: 30vw;
            display: flex;
            flex-direction: column;
            padding: 2rem;
            border-radius: 15px;
            background-color: rgba(255, 255, 255, 0.705);
            backdrop-filter: blur(10px);
        }

        #searchBar input {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        .hidden {
            display: none;
        }

        #avionCrashCard {
            background-color: rgba(182, 52, 52, 0.651);
            z-index: 1000;
            position: absolute;
            top: 30px;
            right: 50px;
            width: 20%;
        }

        #maCarte {
            padding: 20px 0 0 20px;
        }

        .titreAvion {
            color: white;
        }

    </style>
</head>

<body>
    <div id="map-holder"></div>
    <div id="searchBar">
        <input type="text">
        <span id="totalFound">1000</span>
        <button class="search">search</button>
        <button class="emptySearch">close</button>
    </div>
    <div id="rangeYear">
        <input type="range">
        <output>2022</output>
        <button class="unfocus">Unfocus</button>
    </div>
    <div id="avionCrashCard">
        <div id="maCarte" class="maClassCarte">
            <p id="aircaft_operator">Compagnie aérienne</p>
            <div id="carrousel_images">
            </div>
            <p id="aircaft_model">Modèle de l'avion</p>
            <p id="crash_date">Date du crash</p>
            <p id="aircaft_first_flight">Date Premier vol</p>
            <p id="incident_location">Lieu</p>
            <p id="departure_airport">Lieu Départ</p>
            <p id="destination_airport">Lieu Arrivé</p>
            <p id="titreAvion">GPS Crash :</p>
            <ul>
                <li id="incident_gps_lat">Lat :</li>
                <li id="incident_gps_lon">Long :</li>
            </ul>
            <p id="deaths">Nombre Mort</p>
            <p id="survivors">Nombre Survivant</p>
        </div>
    </div>
</body>

<script src="{{ asset('js/app.js') }}"></script>

</html>
