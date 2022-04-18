import * as d3 from "d3";



// survivors
let diameterCircle = 20;

let datas = [...Array(100).keys()];

let svgSurvivors = d3.select("#graph_survivors")
    .append("svg")
    .attr("width", "100%")
    .attr("height", "25vh");

svgSurvivors.selectAll("circle")
    .append("g")
    .data(datas)
    .enter()
    .append("circle")
    .attr("cx", function(d) {
        return ((d % 10) * diameterCircle) + 10;
    })
    .attr("cy", function(d) {
        return (Math.floor(d / 10) * diameterCircle) + 10;
    })
    .attr("r", (diameterCircle / 2) + "px")
    .attr("fill", function(d) {
        return d > 95 ? "black" : "transparent";
    })
    .attr("stroke", "black");


//end survivors


//timeline

// Creating a path 
var path = d3.path();
path.moveTo(100, 100);
path.quadraticCurveTo(50, 0, 0, 100)

path.closePath();
d3.select("#graph_timeline")
    .append("svg")
    .attr("width", "100px")
    .attr("height", "100px")
    .append("path")
    .attr("d", path);

// end timeline



// moustique
diameterCircle = 10;

datas = [...Array(1000).keys()];

const svgMoustiques = d3.select("#graph_moustiques")
    .append("svg")
    .attr("width", "100%")
    .attr("height", "25vh");

svgMoustiques.selectAll("circle")
    .append("g")
    .data(datas)
    .enter()
    .append("circle")
    .attr("cx", function(d) {
        return ((d % 50) * diameterCircle) + 10;
    })
    .attr("cy", function(d) {
        return (Math.floor(d / 50) * diameterCircle) + 10;
    })
    .attr("r", (diameterCircle / 2) + "px")
    .attr("fill", function(d) {
        return d > datas.length - 2 ? "black" : "transparent";
    })
    .attr("stroke", "black");

//end moustique