import * as d3 from "d3";
import * as $ from "jquery";

d3.select("#infoButton").on("click", function() {
    d3.select("#map-section").style("display", "none");
    d3.select("#info-section").style("display", "block");
    console.log("HERE");
});

d3.select("#close-info-section").on("click", function() {
    d3.select("#map-section").style("display", "block");
    d3.select("#info-section").style("display", "none");
    console.log("HERE");
});