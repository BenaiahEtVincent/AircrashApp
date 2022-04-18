import * as d3 from "d3";

const datas = [...Array(10).keys()];

d3.select("#graph_survivors")
    .append("svg")
    .attr("width", "100%")
    .attr("height", "25vh")
    .append("g")
    .data(datas)
    .append("circle")
    .attr("cx", function(d) {
        return d + 10;
    })
    .attr("cy", function(d) {
        return d + 10;
    })
    .attr("r", "5px")
    .attr("fill", "red");

d3.select("#graph_timeline")
    .append("svg")
    .attr("width", "100%")
    .attr("height", "25vh")
    .append("g")
    .append("circle")
    .attr("cx", "50%")
    .attr("cy", "50%")
    .attr("r", "50%")
    .attr("fill", "green");



d3.select("#graph_moustiques")
    .append("svg")
    .attr("width", "100%")
    .attr("height", "25vh")
    .append("g")
    .append("circle")
    .attr("cx", "50%")
    .attr("cy", "50%")
    .attr("r", "50%")
    .attr("fill", "blue");