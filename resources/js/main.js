 //import * as d3 from "https://cdnjs.cloudflare.com/ajax/libs/d3/4.5.0/d3.min.js";
 //import * as jquery from "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js";
 import * as d3 from "d3";
 import * as $ from "jquery";
 import { list } from "postcss";

 const baseurl = window.location + "api";

 // DEFINE VARIABLES
 // Define size of map group
 // Full world map is 2:1 ratio
 // Using 12:5 because we will crop top and bottom of map
 let w = 3000;
 let h = 1250;
 // variables for catching min and max zoom factors
 var minZoom;
 var maxZoom;

 let countriesGroup;
 let countryLabels;
 let crashs;
 let airportsStart;

 let minXY;
 let maxXY;
 let countries;
 let midX;
 let midY;

 let zoomWidth;
 let zoomHeight;
 let zoomMidX;
 let zoomMidY;

 let maxXscale;
 let maxYscale;
 let zoomScale;
 let offsetX;
 let offsetY;
 let dleft;
 let dtop;

 // DEFINE FUNCTIONS/OBJECTS
 // Define map projection
 var projection = d3
     .geoEquirectangular()
     .center([0, 15]) // set centre to further North as we are cropping more off bottom of map
     .scale([w / (2 * Math.PI)]) // scale to fit group width
     .translate([w / 2, h / 2]); // ensure centred in group


 // Define map path
 var path = d3
     .geoPath()
     .projection(projection);


 // Create function to apply zoom to countriesGroup
 function zoomed() {
     let t = d3
         .event
         .transform;

     countriesGroup
         .attr("transform", "translate(" + [t.x, t.y] + ")scale(" + t.k + ")");
     crashs.attr("transform", "translate(" + [t.x, t.y] + ")scale(" + t.k + ")");
     airportsStart.attr("transform", "translate(" + [t.x, t.y] + ")scale(" + t.k + ")");
 }

 // Define map zoom behaviour
 var zoom = d3
     .zoom()
     .on("zoom", zoomed);

 function getTextBox(selection) {
     selection
         .each(function(d) {
             d.bbox = this
                 .getBBox();
         });
 }

 // Function that calculates zoom/pan limits and sets zoom to default value 
 function initiateZoom() {
     // Define a "minzoom" whereby the "Countries" is as small possible without leaving white space at top/bottom or sides
     minZoom = Math.max($("#map-holder").width() / w, $("#map-holder").height() / h);
     // set max zoom to a suitable factor of this value
     maxZoom = 20 * minZoom;
     // set extent of zoom to chosen values
     // set translate extent so that panning can't cause map to move out of viewport
     zoom
         .scaleExtent([minZoom, maxZoom])
         .translateExtent([
             [0, 0],
             [w, h]
         ]);
     // define X and Y offset for centre of map to be shown in centre of holder
     midX = ($("#map-holder").width() - minZoom * w) / 2;
     midY = ($("#map-holder").height() - minZoom * h) / 2;
     // change zoom transform to min zoom and centre offsets
     svg.call(zoom.transform, d3.zoomIdentity.translate(midX, midY).scale(minZoom));
 }

 // zoom to show a bounding box, with optional additional padding as percentage of box size
 function boxZoom(box, centroid, paddingPerc) {
     minXY = box[0];
     maxXY = box[1];
     // find size of map area defined
     zoomWidth = Math.abs(minXY[0] - maxXY[0]);
     zoomHeight = Math.abs(minXY[1] - maxXY[1]);
     // find midpoint of map area defined
     zoomMidX = centroid[0];
     zoomMidY = centroid[1];
     // increase map area to include padding
     zoomWidth = zoomWidth * (1 + paddingPerc / 100);
     zoomHeight = zoomHeight * (1 + paddingPerc / 100);
     // find scale required for area to fill svg
     maxXscale = $("svg").width() / zoomWidth;
     maxYscale = $("svg").height() / zoomHeight;
     zoomScale = Math.min(maxXscale, maxYscale);
     // handle some edge cases
     // limit to max zoom (handles tiny countries)
     zoomScale = Math.min(zoomScale, maxZoom);
     // limit to min zoom (handles large countries and countries that span the date line)
     zoomScale = Math.max(zoomScale, minZoom);
     // Find screen pixel equivalent once scaled
     offsetX = zoomScale * zoomMidX;
     offsetY = zoomScale * zoomMidY;
     // Find offset to centre, making sure no gap at left or top of holder
     dleft = Math.min(0, $("svg").width() / 2 - offsetX);
     dtop = Math.min(0, $("svg").height() / 2 - offsetY);
     // Make sure no gap at bottom or right of holder
     dleft = Math.max($("svg").width() - w * zoomScale, dleft);
     dtop = Math.max($("svg").height() - h * zoomScale, dtop);
     // set zoom
     svg
         .transition()
         .duration(500)
         .call(
             zoom.transform,
             d3.zoomIdentity.translate(dleft, dtop).scale(zoomScale)
         );
 }




 // on window resize
 $(window).resize(function() {
     // Resize SVG
     svg
         .attr("width", $("#map-holder").width())
         .attr("height", $("#map-holder").height());
     initiateZoom();
 });

 // create an SVG
 var svg = d3
     .select("#map-holder")
     .append("svg")
     // set to the same size as the "map-holder" div
     .attr("width", $("#map-holder").width())
     .attr("height", $("#map-holder").height())
     // add zoom functionality
     .call(zoom);


 let year = 2022;

 function initCrash() {
     const url = baseurl + '/incidents/' + year;
     d3.json(url, function(json) {
         console.log(json)

         crashs.selectAll("image").remove();
         airportsStart.selectAll("circle").remove();
         displayCrashs(json);
     });
 }

 let inputYear = d3.select('#rangeYear input');

 console.log("inputYear", inputYear);
 inputYear.attr("min", 1900);
 inputYear.attr("max", 2022);
 inputYear.attr("value", 2022);


 inputYear.on("input", function() {
     year = inputYear.property("value");
     d3.select("#rangeYear output").text(year);
 });

 inputYear.on("change", function() {
     initCrash();
 });


 // get map data
 d3.json(
     baseurl + "/maps",
     function(json) {

         //Bind data and create one path per GeoJSON feature


         countriesGroup = svg.append("g").attr("id", "map");
         crashs = svg.append("g").attr("id", "crashs");
         airportsStart = svg.append("g").attr("id", "airportStart");


         // add a background rectangle
         countriesGroup
             .append("rect")
             .attr("x", 0)
             .attr("y", 0)
             .attr("width", w)
             .attr("height", h);


         // draw a path for each feature/country
         countries = countriesGroup
             .selectAll("path")
             .data(json.features)
             .enter()
             .append("path")
             .attr("d", path)
             .attr("id", function(d, i) {
                 return "country" + d.properties.iso_a3;
             })
             .attr("class", "country")
             //      .attr("stroke-width", 10)
             //      .attr("stroke", "#ff0000")
             // add a mouseover action to show name label for feature/country
             .on("mouseover", function(d, i) {
                 //d3.select("#countryLabel" + d.properties.iso_a3).style("display", "block");
             })
             .on("mouseout", function(d, i) {
                 //d3.select("#countryLabel" + d.properties.iso_a3).style("display", "none");
             })
             // add an onclick action to zoom into clicked country
             .on("click", function(d, i) {
                 d3.selectAll(".country").classed("country-on", false);
                 d3.select(this).classed("country-on", true);
                 console.log(d);
                 boxZoom(path.bounds(d), path.centroid(d), 20);
             });
         // Add a label group to each feature/country. This will contain the country name and a background rectangle
         // Use CSS to have class "countryLabel" initially hidden
         countryLabels = countriesGroup
             .selectAll("g")
             .data(json.features)
             .enter()
             .append("g")
             .attr("class", "countryLabel")
             .attr("id", function(d) {
                 return "countryLabel" + d.properties.iso_a3;
             })
             .attr("transform", function(d) {
                 return (
                     "translate(" + path.centroid(d)[0] + "," + path.centroid(d)[1] + ")"
                 );
             })
             // add mouseover functionality to the label
             .on("mouseover", function(d, i) {
                 d3.select(this).style("display", "block");
             })
             .on("mouseout", function(d, i) {
                 d3.select(this).style("display", "none");
             })
             // add an onlcick action to zoom into clicked country
             .on("click", function(d, i) {
                 d3.selectAll(".country").classed("country-on", false);
                 d3.select("#country" + d.properties.iso_a3).classed("country-on", true);
                 boxZoom(path.bounds(d), path.centroid(d), 20);
             });
         // add the text to the label group showing country name
         countryLabels
             .append("text")
             .attr("class", "countryName")
             .style("text-anchor", "middle")
             .attr("dx", 0)
             .attr("dy", 0)
             .text(function(d) {
                 return d.properties.name;
             })
             .call(getTextBox);
         // add a background rectangle the same size as the text
         countryLabels
             .insert("rect", "text")
             .attr("class", "countryLabelBg")
             .attr("transform", function(d) {
                 return "translate(" + (d.bbox.x - 2) + "," + d.bbox.y + ")";
             })
             .attr("width", function(d) {
                 return d.bbox.width + 4;
             })
             .attr("height", function(d) {
                 return d.bbox.height;
             });



         initiateZoom();
         initCrash();
         displayButtonCloseSearchBar(false);


     }
 );


 function displayDetailCard(crash) { // pour toi 
     console.log(crash);
 }





































 function focusAndDisplayAirport(crash) { // pour moi
     console.log(crash);

     let d = {
         "type": "Feature",
         "properties": {},
         "geometry": {
             "type": "Polygon",
             "coordinates": [
                 [
                     [
                         crash.gps_crash.lat,
                         crash.gps_crash.lon
                     ],

                     [
                         crash.gps_depart.lat,
                         crash.gps_crash.lon
                     ],

                     [
                         crash.gps_depart.lat,
                         crash.gps_depart.lon
                     ],

                     [
                         crash.gps_crash.lat,
                         crash.gps_depart.lon
                     ],
                 ]
             ]
         },
         "bbox": {}
     };


     boxZoom(path.bounds(d), path.centroid(d), 100);

     toggleAllPoint(crash);

 }

 function toggleAllPoint(crash, focus = true) {

     d3.selectAll("#crashs image").each(function() {
         let isCrashSelected = false;
         if (focus) {
             isCrashSelected = d3.select(this).attr('id') == "crash_" + crash.id;
         }
         d3.select(this).transition().duration(900).style("visibility", function() {
             if (!focus) {
                 return "visible";
             }
             return isCrashSelected ? "visible" : "hidden";
         });
     });

     d3.selectAll("#airportStart circle").each(function() {
         let isCrashSelected = false;
         if (focus) {
             isCrashSelected = d3.select(this).attr('id') == "airport_" + crash.id;
         }
         d3.select(this).transition().duration(900).style("visibility", function() {
             if (!focus) {
                 return "visible";
             }
             return isCrashSelected ? "visible" : "hidden";
         });
     });
 }


 d3.select(".unfocus").on("click", function() {
     toggleAllPoint(null, false);

     initiateZoom();
 });

 d3.select(".emptySearch").on("click", function() {
     document.querySelector("#searchBar input").value = "";
     hideAll();
     initCrash();
     displayButtonCloseSearchBar(false);
 });


 function hideAll() {
     /*  d3.selectAll("#crashs image").each(function() {
          d3.select(this).transition().duration(900).style("visibility", "hidden");
      });

      d3.selectAll("#airportStart circle").each(function() {
          d3.select(this).transition().duration(900).style("visibility", "hidden");
      }); */

     crashs.selectAll("image").remove();
     airportsStart.selectAll("circle").remove();
 }

 function displayCrashs(listCrashs) {
     crashs.selectAll(".pin")
         .data(listCrashs)
         .enter()
         .append("svg:image", ".pin")
         .attr("xlink:href", "/assets/explosion.svg")
         .attr("width", 20)
         .attr("height", 20)
         .attr("transform", function(d) {
             return "translate(" + projection([
                 d.gps_crash.lon,
                 d.gps_crash.lat
             ]) + ")";
         })
         .attr("id", function(d) {
             return "crash_" + d.id;
         })
         .on("click", function(crash) {
             //displayDetailCard(crash); // pour toi 
             focusAndDisplayAirport(crash); // pour moi
         });

     airportsStart.selectAll("circle")
         .data(listCrashs)
         .enter()
         .append("circle")
         .attr("fill", "green")
         .attr("r", 10)
         .attr("id", function(d) {
             return "airport_" + d.id;
         })
         .attr("transform", function(d) {
             console.log(d);
             return "translate(" + projection([
                 d.gps_depart.lon,
                 d.gps_depart.lat
             ]) + ")";
         });
 }


 d3.select("#searchBar input").on("input", function() {
     console.log(this.value);
     displayButtonCloseSearchBar(true);

     if (!this.value) return initCrash();
     d3.json(baseurl + "/search/" + this.value.replaceAll("/", "-"), function(json) {

         if (!json) return;
         console.log(json);

         hideAll();
         displayCrashs(json);
     })
 });

 function displayButtonCloseSearchBar(value) {
     d3.select(".emptySearch").transition().duration(900).style("display", function() {
         return value ? "block" : "none";
     });
 }