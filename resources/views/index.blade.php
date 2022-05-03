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
        {{-- <img src="{{ asset('assets/loading2.svg') }}" id="loading-img"> --}}
        <img src="{{ asset('assets/loading.svg') }}" id="loading">

        <div id="map-holder"></div>
        <div id="searchBar">
            <p>Rechercher par pays et dates</p>
            <input type="text" id="searchByText" placeholder="Rechercher">
            <input type="date" id="searchByDate">
            <span id="totalFound">1000</span>
            <button class="search">Rechercher</button>
            <button class="emptySearch">Fermer</button>
        </div>
        <div id="rangeYear">
            <div id="rangeYearBar">
                <span id="play-button" class="material-icons">play_circle</span>
                <span id="stop-button" class="material-icons" style="display:none">stop_circle</span>
                <input type="range">
            </div>

            <output>2022</output>
        </div>

        {{-- <i id="infoButton" class="material-icons">info</i> --}}
        <i id="infoButton"></i>

        <div id="avionCrashCard">
            <div id="maCarte" class="maClassCarte">
                <div class="buttonGroup">
                    <a href="" id="editCrash"><span class="material-icons">edit</span></a>
                    <span class="material-icons unfocus">close</span>
                </div>
                <div id="carrousel_images" class="slideshow-container">

                    <!-- Full-width images with number and caption text -->
                    <div class="mySlides fade">
                        <img src="" style="width:100%">
                    </div>

                    <!-- Next and previous buttons -->
                    <a class="prev">&#10094;</a>
                    <a class="next">&#10095;</a>
                </div>

                <br>


                <p id="aircaft_operator" class="textCard">Compagnie aérienne :<br> <span></span></p>

                <p id="aircaft_model" class="textCard">Modèle de l'avion :<br> <span></span></p>
                <p id="crash_date" class="textCard">Date du crash :<br> <span></span></p>
                <p id="aircaft_first_flight" class="textCard">Date premier vol :<br> <span></span></p>
                <p id="incident_location" class="textCard">Lieu :<br> <span></span></p>
                <p id="departure_airport" class="textCard">Lieu départ :<br> <span></span></p>
                <p id="destination_airport" class="textCard">Lieu arrivé :<br> <span></span></p>
                {{-- <p id="titreAvion">GPS Crash :</p>
            <ul>
                <li id="gps_crash_lat">Lat : <span></span></li>
                <li id="gps_crash_lon">Long : <span></span></li>
            </ul> --}}
                <p id="deaths" class="textCard">Nombre morts : <span></span></p>
                <p id="survivors" class="textCard">Nombre survivants : <span></span></p>
            </div>
        </div>
    </section>
    <section id="info-section">


        {{-- <div id="logo"><img src="{{ asset('assets/avion.svg') }}" width="100px"></div>

        <div id="container_graphs">
            <div class="row">
                <div class="col-6">
                    95.7 % de survivants sur tous les crashs confondus
                </div>
                <div class="col-6">
                    <div id="graph_survivors"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div id="graph_vehicules"></div>
                </div>
                <div class="col-6">
                    Avions comparé aux autres moyens de locomotion
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    Les moustiques sont 1000 fois plus meurtrier
                </div>
                <div class="col-6">
                    <div id="graph_moustiques"></div>
                </div>
            </div>
        </div>

        <div id="source">
            <a href="https://www.globe-trotting.com/post/peur-en-avion">
                https://www.globe-trotting.com/post/peur-en-avion
            </a>
        </div> --}}

        @include('infoPage')

    </section>
</body>

<script src="{{ asset('js/app.js') }}"></script>

</html>
