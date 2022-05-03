//import * as d3 from "https://cdnjs.cloudflare.com/ajax/libs/d3/4.5.0/d3.min.js";
//import * as jquery from "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js";
import * as d3 from "d3";
import * as $ from "jquery";

let dataurl = process.env.MIX_DATA_URL;

const baseurl = (dataurl == "" ? window.location : dataurl) + "api";

console.log(baseurl);
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
let flights;
let plane;

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


d3.selection.prototype.first = function() {
    return d3.select(
        this.nodes()[0]
    );
};
d3.selection.prototype.last = function() {
    return d3.select(
        this.nodes()[this.size() - 1]
    );
};

// DEFINE FUNCTIONS/OBJECTS
// Define map projection
var projection = d3
    .geoEquirectangular()
    .center([0, 15]) // set centre to further North as we are cropping more off bottom of map
    .scale([w / (2 * Math.PI)]) // scale to fit group width
    .translate([w / 2, h / 2]); // ensure centred in group

// Define map path
var path = d3.geoPath().projection(projection);

// Create function to apply zoom to countriesGroup
function zoomed() {
    let t = d3.event.transform;

    countriesGroup.attr(
        "transform",
        "translate(" + [t.x, t.y] + ")scale(" + t.k + ")"
    );
    crashs.attr("transform", "translate(" + [t.x, t.y] + ")scale(" + t.k + ")");
    airportsStart.attr(
        "transform",
        "translate(" + [t.x, t.y] + ")scale(" + t.k + ")"
    );
    flights.attr(
        "transform",
        "translate(" + [t.x, t.y] + ")scale(" + t.k + ")"
    );

    plane.attr(
        "transform",
        "translate(" + [t.x, t.y] + ")scale(" + t.k + ")"
    );

}

// Define map zoom behaviour
var zoom = d3.zoom().on("zoom", zoomed);

function getTextBox(selection) {
    selection.each(function(d) {
        d.bbox = this.getBBox();
    });
}

// Function that calculates zoom/pan limits and sets zoom to default value
function initiateZoom() {
    // Define a "minzoom" whereby the "Countries" is as small possible without leaving white space at top/bottom or sides
    minZoom = Math.max(
        $("#map-holder").width() / w,
        $("#map-holder").height() / h
    );
    // set max zoom to a suitable factor of this value
    maxZoom = 20 * minZoom;
    // set extent of zoom to chosen values
    // set translate extent so that panning can't cause map to move out of viewport
    zoom.scaleExtent([minZoom, maxZoom]).translateExtent([
        [0, 0],
        [w, h],
    ]);
    // define X and Y offset for centre of map to be shown in centre of holder
    midX = ($("#map-holder").width() - minZoom * w) / 2;
    midY = ($("#map-holder").height() - minZoom * h) / 2;
    // change zoom transform to min zoom and centre offsets
    svg.call(
        zoom.transform,
        d3.zoomIdentity.translate(midX, midY).scale(minZoom)
    );


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
    svg.transition()
        .duration(500)
        .call(
            zoom.transform,
            d3.zoomIdentity.translate(dleft, dtop).scale(zoomScale)
        );
}

// on window resize
$(window).resize(function() {
    // Resize SVG
    svg.attr("width", $("#map-holder").width()).attr(
        "height",
        $("#map-holder").height()
    );
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

displayButtonCloseSearchBar(false);
d3.select("#avionCrashCard").style("visibility", "hidden");

let year = 2022;

function initCrash() {

    let v = ((((year - 1900) / (2022 - 1900))) * 100).toFixed(0);
    console.log("YEAR", year, v);

    d3.select("#map").attr("class", "sepia-" + v);

    const url = baseurl + "/incidents/" + year;
    d3.json(url, function(json) {
        crashs.selectAll("image").remove();
        airportsStart.selectAll("circle").remove();
        flights.selectAll("line").remove();
        plane.selectAll("image").remove();
        displayCrashs(json);
    });
}

let inputYear = d3.select("#rangeYear input");

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
d3.json(baseurl + "/maps", function(json) {
    //Bind data and create one path per GeoJSON feature

    countriesGroup = svg.append("g").attr("id", "map");
    crashs = svg.append("g").attr("id", "crashs");
    airportsStart = svg.append("g").attr("id", "airportStart");
    flights = svg.append("g").attr("id", "flights");
    plane = svg.append("g").attr("id", "plane");

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
            searchCrashForCountry(d.properties.adm0_a3);

            d3.selectAll(".country").classed("country-on", false);
            d3.select(this).classed("country-on", true);
            //boxZoom(path.bounds(d), path.centroid(d), 20);
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
                "translate(" +
                path.centroid(d)[0] +
                "," +
                path.centroid(d)[1] +
                ")"
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
            d3.select("#country" + d.properties.iso_a3).classed(
                "country-on",
                true
            );
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
});

function displayDetailCard(crash) {
    // pour toi
    d3.select("#avionCrashCard")
        .transition()
        .duration(900)
        .style("visibility", "visible");


    for (const [key, value] of Object.entries(crash)) {
        d3.select("#" + key).select("span").text(value);
    }



    const crashDate = new Date(crash.crash_date);
    const crashDateFormatted = crashDate.toLocaleString("fr-CH", {
        day: "numeric",
        month: "short",
        year: "numeric",
        hour: "numeric",
        minute: "2-digit"
    });

    d3.select("#editCrash").attr("href", "/incidents/" + crash.id + "/edit");

    d3.select("#crash_date").select("span").text(crashDateFormatted);


    const firstFlight = new Date(crash.aircaft_first_flight);
    const firstFlightFormatted = firstFlight.toLocaleString("fr-CH", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });

    d3.select("#aircaft_first_flight").select("span").text(firstFlightFormatted);


    d3.select("#deaths").select("span").text(crash.deaths.total);
    d3.select("#survivors").select("span").text(crash.occupations.total - crash.deaths.total);

    d3.select("#gps_crash_lat").select("span").text(crash.gps_crash.lat);
    d3.select("#gps_crash_lon").select("span").text(crash.gps_crash.lon);



    d3.selectAll("#carrousel_images .mySlides").remove();

    crash.images.forEach((img, index) => {
        d3.select("#carrousel_images")
            .append("div")
            .attr("class", "mySlides")
            .style("display", function(d) {
                return index == 0 ? "block" : "none";
            })
            .append("img")
            .attr("src", img.full_link)
            .style("width", "100%")
            .style("aspect-ratio", "3/2");
    });

    let dx = crash.gps_crash.lat - crash.gps_depart.lat
    let dy = crash.gps_crash.lon - crash.gps_depart.lon
    let ang = Math.atan2(dy, dx) * 180 / Math.PI;

    let distance = calculateDistanceTwoPointsGPS(crash.gps_crash.lat, crash.gps_crash.lon, crash.gps_depart.lat, crash.gps_depart.lon);
    console.log(distance / 1000);
    plane.append("svg:image", ".plane")
        .attr("xlink:href", "/assets/avion.svg")
        .attr("width", 40)
        .attr("height", 40)
        .attr('x', d => -20)
        .attr('y', d => -20)
        .attr("id", "plane-picto")
        .attr("transform", function(d) {
            return (
                "translate(" +
                projection([crash.gps_depart.lon, crash.gps_depart.lat]) +
                ") rotate(" + ang + ")"

            );
        })
        .transition()
        .duration(distance / 1000)
        .ease(d3.easeLinear)
        .attr("transform", function(d) {
            return (
                "translate(" +
                projection([crash.gps_crash.lon, crash.gps_crash.lat]) +
                ") rotate(" + ang + ")"
            );
        })
        .remove();




}

function hideDetailCard() {
    d3.select("#avionCrashCard")
        .transition()
        .duration(900)
        .style("visibility", "hidden");
    d3.selectAll("#carrousel_images .mySlides").remove(); //clean all images
    d3.select("#map-holder svg").selectAll("#plane-picto").remove(); //clean plane

}

function focusAndDisplayAirport(crash) {
    // pour moi

    let d = {
        type: "Feature",
        properties: {},
        geometry: {
            type: "Polygon",
            coordinates: [
                [
                    [crash.gps_crash.lat, crash.gps_crash.lon],

                    [crash.gps_depart.lat, crash.gps_crash.lon],

                    [crash.gps_depart.lat, crash.gps_depart.lon],

                    [crash.gps_crash.lat, crash.gps_depart.lon],
                ],
            ],
        },
        bbox: {},
    };

    //boxZoom(path.bounds(d), path.centroid(d), 100);

    toggleAllPoint(crash);
}

function toggleAllPoint(crash, focus = true) {
    d3.selectAll("#crashs image").each(function() {
        let isCrashSelected = false;
        if (focus) {
            isCrashSelected = d3.select(this).attr("id") == "crash_" + crash.id;
        }
        d3.select(this)
            .transition()
            .duration(900)
            .style("visibility", function() {
                if (!focus) {
                    return "visible";
                }
                return isCrashSelected ? "visible" : "hidden";
            });
    });

    d3.selectAll("#airportStart circle").each(function() {
        let isCrashSelected = false;
        if (focus) {
            isCrashSelected =
                d3.select(this).attr("id") == "airport_" + crash.id;
        }
        d3.select(this)
            .transition()
            .duration(900)
            .style("visibility", function() {
                if (!focus) {
                    return "visible";
                }
                return isCrashSelected ? "visible" : "hidden";
            });
    });

    d3.selectAll("#flights line").each(function() {
        let isCrashSelected = false;
        if (focus) {
            isCrashSelected =
                d3.select(this).attr("id") == "flight_" + crash.id;
        }
        d3.select(this)
            .transition()
            .duration(900)
            .style("visibility", function() {
                if (!focus) {
                    return "visible";
                }
                return isCrashSelected ? "visible" : "hidden";
            });
    });


}

function unfocus() {
    toggleAllPoint(null, false);
    hideDetailCard();
    initiateZoom();
}

d3.select(".unfocus").on("click", function() {
    unfocus();
});

d3.select(".emptySearch").on("click", function() {
    document.querySelector("#searchByText").value = "";
    document.querySelector("#searchByDate").value = "";

    hideAll();
    unfocus();
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
    flights.selectAll("line").remove();
}

function displayCrashs(listCrashs, displayStart = true, displayRoute = true) {
    crashs
        .selectAll(".pin")
        .data(listCrashs)
        .enter()
        .append("svg:image", ".pin")
        .attr("xlink:href", "/assets/explosion.svg")
        .attr("width", 20)
        .attr("height", 20)
        .attr('x', d => -10)
        .attr('y', d => -10)
        .attr("transform", function(d) {
            return (
                "translate(" +
                projection([d.gps_crash.lon, d.gps_crash.lat]) +
                ")"
            );
        })
        .attr("id", function(d) {
            return "crash_" + d.id;
        })
        .on("click", function(crash) {
            console.log(crash);
            displayDetailCard(crash); // pour toi
            focusAndDisplayAirport(crash); // pour moi
        });

    if (displayStart) {
        airportsStart
            .selectAll("circle")
            .data(listCrashs)
            .enter()
            .append("circle")
            .attr("fill", "green")
            .attr("fill-opacity", "0.5")
            .attr("r", 10)
            .attr("id", function(d) {
                return "airport_" + d.id;
            })
            .attr("transform", function(d) {
                return (
                    "translate(" +
                    projection([d.gps_depart.lon, d.gps_depart.lat]) +
                    ")"
                );
            }).on("click", function(crash) {
                displayDetailCard(crash); // pour toi
                focusAndDisplayAirport(crash); // pour moi
            });
    }
    if (displayRoute) {
        flights
            .selectAll("line")
            .data(listCrashs)
            .enter()
            .append("line")
            .style("stroke", "red")
            .style("stroke-width", 1)
            .attr("id", function(d) {
                return "flight_" + d.id;
            })
            .attr("x1", function(d) {
                return projection([d.gps_depart.lon, d.gps_depart.lat])[0];
            })
            .attr("y1", function(d) {
                return projection([d.gps_depart.lon, d.gps_depart.lat])[1];
            })
            .attr("x2", function(d) {
                return projection([d.gps_crash.lon, d.gps_crash.lat])[0];
            })
            .attr("y2", function(d) {
                return projection([d.gps_crash.lon, d.gps_crash.lat])[1];
            }).on("click", function(crash) {
                displayDetailCard(crash); // pour toi
                focusAndDisplayAirport(crash); // pour moi
            });

    }

}

d3.select("#searchByText").on("change", function() {
    if (!this.value) return initCrash();
    searchAndDisplay(this.value);
});

d3.select("#searchBar button.search").on("click", function() {
    let value = document.querySelector("#searchByText").value;
    if (!value) {
        value = document.querySelector("#searchByDate").value;
        searchByDateAndDisplay(value);
        return;
    }
    searchAndDisplay(value);
});

function searchByDateAndDisplay(value) {
    unfocus();
    displayButtonCloseSearchBar(true);
    d3.json(baseurl + "/incidents/" + value.replaceAll("-", "/"), function(json) {
        if (!json) return;
        hideAll();
        displayCrashs(json);
        setTotalFound(Object.keys(json).length);
    });
}

function searchAndDisplay(value) {
    unfocus();
    displayButtonCloseSearchBar(true);
    d3.json(baseurl + "/search/" + value.replaceAll("/", "-"), function(json) {
        if (!json) return;
        hideAll();
        displayCrashs(json);
        setTotalFound(Object.keys(json).length);
    });
}

function displayButtonCloseSearchBar(value) {
    d3.select(".emptySearch")
        .transition()
        .duration(900)
        .style("display", function() {
            return value ? "block" : "none";
        });

    if (!value) {
        setTotalFound(0);
    }
}

function setTotalFound(value) {
    d3.select("#totalFound").text(value == 0 ? "" : value);
}

function searchCrashForCountry(code) {
    d3.json(baseurl + "/searchCountryCode/" + code, function(json) {
        if (!json) return;
        hideAll();
        displayCrashs(json);
        setTotalFound(Object.keys(json).length);
    });
}

const sleep = ms => new Promise(r => setTimeout(r, ms));

function displayPlayButton() {
    d3.select("#stop-button").style("display", "none");
    d3.select("#play-button").style("display", "block");
}

function hidePlayButton() {
    console.log("hide play button");
    d3.select("#play-button").style("display", "none");
    d3.select("#stop-button").style("display", "block");
}

d3.select("#stop-button").on("click", function() {
    runAnimation = false;
    hidePlayButton();
    d3.select("#play-button").style("display", "block");
})

let _crashs = [];

d3.select("#play-button").on("click", async function() {

    hidePlayButton();
    displayLoading();
    /* for (let _year = 1918; _year <= 2022; _year++) {
        await sleep(2000);
        inputYear.attr("value", _year);
        console.log(_year);
        document.querySelector("#rangeYear input").dispatchEvent(new Event('input', { bubbles: true }));
        await initCrash();
    } */

    const url = baseurl + "/incidents";
    console.log("craashslength", _crashs.length);
    if (_crashs.length != 0) {
        console.log("from cache");
        await animateAllCrashs(_crashs);
    } else {
        await d3.json(url, async function(json) {
            _crashs = json;
            await animateAllCrashs(json);
        });
    }

});

async function animateAllCrashs(json) {
    hideLoading();

    crashs.selectAll("image").remove();
    airportsStart.selectAll("circle").remove();
    flights.selectAll("line").remove();
    plane.selectAll("image").remove();
    await displayCrashsAnimate(json);
    displayPlayButton();
}


function calculateDistanceTwoPointsGPS(depart_lat, depart_lon, dest_lat, dest_lon) {
    const R = 6371e3; // metres
    const φ1 = depart_lat * Math.PI / 180; // φ, λ in radians
    const φ2 = dest_lat * Math.PI / 180;
    const Δφ = (dest_lat - depart_lat) * Math.PI / 180;
    const Δλ = (dest_lon - depart_lon) * Math.PI / 180;

    const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
        Math.cos(φ1) * Math.cos(φ2) *
        Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    const d = R * c; // in metres

    return d;
}

let runAnimation = true;

async function displayCrashsAnimate(_crashs) {
    runAnimation = true;

    let startYear = inputYear.attr("value") == 2022 ? 1918 : inputYear.attr("value");

    for (let i = 1915; i <= 2022; i++) {
        if (_crashs[i] && runAnimation) {
            if (startYear <= i) {
                await sleep(100);
            }
            inputYear.attr("value", i);
            document.querySelector("#rangeYear input").dispatchEvent(new Event('input', { bubbles: true }));

            // crashs.selectAll("image").remove();
            airportsStart.selectAll("circle").remove();
            flights.selectAll("line").remove();
            plane.selectAll("image").remove();
            displayCrashs(_crashs[i], false, false);
        }

    }
}

function displayLoading() {
    d3.select("#loading").style("display", "block");
}

function hideLoading() {
    d3.select("#loading").style("display", "none");
}