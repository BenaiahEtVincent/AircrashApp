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


//vehicules

const vehiculesDatas = [
    { "name": "planes", "death": 1000 },
    { "name": "helicopters", "death": 2000 },
    { "name": "cars", "death": 3000 },
    { "name": "buses", "death": 4000 },
    { "name": "trucks", "death": 5000 },
    { "name": "motorcycles", "death": 6000 },
    { "name": "bicycles", "death": 7000 },
    { "name": "boats", "death": 8000 },
    { "name": "submarines", "death": 9000 },
    { "name": "trains", "death": 10000 },
    { "name": "tanks", "death": 11000 },
];





const svg = d3.select('#graph_vehicules')
    .append("svg")
    .attr("width", "100%")
    .attr("height", "25vh")
    .attr("transform",
        "translate(" + 50 + "," + 50 + ")");;

// Add X axis
var x = d3.scaleBand()
    .domain(vehiculesDatas.map(d => d.name))
    .range([0, 500]);

svg.append("g")
    .attr("transform", "translate(0," + 100 + ")")
    .call(d3.axisBottom(x));

// Add Y axis
var y = d3.scaleLinear()
    .domain([0, 12000])
    .range([100, 0]);

svg.append("g")
    .call(d3.axisLeft(y))
    .append("text")
    .attr("class", "axis-title")
    .attr("transform", "rotate(-90)")
    .attr("y", 4)
    .attr("dy", ".5em")
    .style("text-anchor", "end")
    .attr("fill", "#5D6971")
    .text("Nombre de morts par an");


svg.append('g')
    .attr("id", "groupRect")
    .selectAll("rect")
    .data(vehiculesDatas)
    .enter()
    .append("rect")
    .attr("id", function(d) {
        return d.name
    })
    .attr("width", function(d) {
        return x.bandwidth();
    })
    .attr("height", function(d) {
        return (d.death / 100);
    })
    .attr("x", function(d) {
        return x(d.name);
    })
    .attr("y", function(d) {
        return y(d.death / 100) - d.death / 100;
    })
    .style("fill", function(d) {
        return d.name == "planes" ? "red" : "#69b3a2";
    })
    .style("opacity", "0.3")
    .attr("stroke", "black");






// end vehicules



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