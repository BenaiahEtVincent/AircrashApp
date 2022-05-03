import * as d3 from "d3";
import * as $ from "jquery";

d3.select("#infoButton").on("click", function() {
    d3.select("#map-section").style("display", "none");
    d3.select("#info-section").style("display", "flex");
});

d3.selectAll(".close-info-section").on("click", function() {
    d3.select("#map-section").style("display", "flex");
    d3.select("#info-section").style("display", "none");
});