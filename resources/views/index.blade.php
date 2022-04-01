<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1.0, width=device-width" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Interactive Map</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
</head>

<body>
    <section id="map-section">
    <div id="map-holder"></div>
    <div id="searchBar">
        <input type="text" id="searchByText" placeholder="Rechercher">
        <input type="date" id="searchByDate">
        <span id="totalFound">1000</span>
        <button class="search">search</button>
        <button class="emptySearch">close</button>
    </div>
    <div id="rangeYear">
        <input type="range">
        <output>2022</output>
    </div>

    <i id="infoButton" class="material-icons">info</i>
    
    <div id="avionCrashCard">
        <div id="maCarte" class="maClassCarte">
            <span class="material-icons unfocus">close</span>
            <div id="carrousel_images" class="slideshow-container">

                <!-- Full-width images with number and caption text -->
                <div class="mySlides fade">
                    <img src="/storage/planes/8/273.jpg" style="width:100%">
                </div>

                <!-- Next and previous buttons -->
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>

            <br>


            <p id="aircaft_operator">Compagnie aérienne : <span></span></p>

            <p id="aircaft_model">Modèle de l'avion : <span></span></p>
            <p id="crash_date">Date du crash : <span></span></p>
            <p id="aircaft_first_flight">Date premier vol : <span></span></p>
            <p id="incident_location">Lieu : <span></span></p>
            <p id="departure_airport">Lieu départ : <span></span></p>
            <p id="destination_airport">Lieu arrivé : <span></span></p>
            {{-- <p id="titreAvion">GPS Crash :</p>
            <ul>
                <li id="gps_crash_lat">Lat : <span></span></li>
                <li id="gps_crash_lon">Long : <span></span></li>
            </ul> --}}
            <p id="deaths">Nombre morts : <span></span></p>
            <p id="survivors">Nombre survivants : <span></span></p>
        </div>
    </div>
    </section>
    <section id="info-section">
        <i id="close-info-section" class="material-icons">close</i>

        COUCOU
    </section>
</body>

<script src="{{ asset('js/app.js') }}"></script>

</html>
