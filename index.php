<!DOCTYPE html>
<html>
<meta charset="utf-8">
<style>

.frame {
  fill: none;
  stroke: #000;
}

.axis text {
  font: 10px sans-serif;
}

.axis line,
.axis circle {
  fill: none;
  stroke: #777;
  stroke-dasharray: 1,4;
}

.axis :last-of-type circle {
  stroke: #333;
  stroke-dasharray: none;
}

.line {
  fill: none;
  stroke: red;
  stroke-width: 1.5px;
}
.tab {
  background-color: #86d3d8;
  display: none;
  min-height: 100vh;
  padding: 20px;
}
.active_tab {
  display: block;
}
input[type="text"] {
  padding: 7px;
  border: none;
  border-radius: 2px;
  box-shadow: 0px 0px 5px 1px darkgrey;
  transition: box-shadow 0.2s;
  margin: 10px 5px;
}
input[type="text"]:hover, input[type="text"]:active, input[type="text"]:focus {
  box-shadow: 0px 0px 5px 2px grey;
  outline: none;
}
.tab button {
  padding: 7px;
  border: none;
  border-radius: 2px;
  box-shadow: 0px 0px 5px 1px darkgrey;
  transition: box-shadow 0.2s;
  background-color: white;
  margin: 10px 5px;
}
.tab button:hover, i.tab button:active, .tab button:focus {
  box-shadow: 0px 0px 5px 2px grey;
  outline: none;
}
button.plus-btn {
  margin-top: 20px;
  padding: 4px 7px;
}
button:focus {
  outline: none;
}
#tab_btn_container button {
  background-color: #aaaaaa;
  color: white;
  border-radius: 5px 5px 0px 0px;
  border: none;
  padding: 10px 20px;
  display: block;
  float: left;
}
.clear {
  clear: both;
}
#tab_btn_container button.active_tab_btn {
  background-color: #86d3d8;
}
svg {
  width: 100%;
  border-bottom: 2px solid black;
}

</style>
<body>
  <p id="demo"></p>
  <div id="tab_btn_container">
    <button type="button" class="active_tab_btn" onclick="changeTab(1)">Directivity</button>
    <button type="button" onclick="changeTab(2)">Pressure</button>
    <div class="clear"></div>
  </div>
  <div id="tab_container">
    <div class="tab active_tab">
      <div id="graph_container1">
        <div id="first-graph1">
          <input type="text" id="input_n1" value="5" />
          <input type="text" id="input_l1" value="0.1" />
          <input type="text" id="input_freq1" value="1000" />
          <button type="button" onclick="draw(1)">Submit</button>
        </div>
      </div>
      <button class="plus-btn" type="button" onclick="addGraph(1)" id="new_graph">+</button>
    </div>
    <div class="tab">
      <div id="graph_container2">
        <div id="second-graph2">
          <input type="text" id="input_freq2" value="25" />
          <input type="text" id="input_l2" value="4" />
          <button type="button" onclick="draw2(2)">Submit</button>
        </div>
      </div>
      <button class="plus-btn" type="button" onclick="addGraph(2)" id="new_graph">+</button>
    </div>
  </div>

<script src="math.js" type="text/javascript"></script>
<script src="//d3js.org/d3.v3.min.js"></script>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="https://d3js.org/d3-contour.v1.min.js"></script>
<script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
<script src="https://d3js.org/d3-hsv.v0.1.min.js"></script>

<script>
var count = 2;

function changeTab(tab) {
  for (var i = 0; i < document.getElementById('tab_container').children.length; i++) {
    document.getElementById('tab_container').children[i].classList.remove("active_tab");
    document.getElementById('tab_btn_container').children[i].classList.remove("active_tab_btn");
  }
  document.getElementById('tab_container').children[tab-1].classList.add("active_tab");
  document.getElementById('tab_btn_container').children[tab-1].classList.add("active_tab_btn");
}

function addGraph(type) {
  count++;
  if (type == 1) {
    document.getElementById('graph_container1').innerHTML += '<div id="first-graph'+count+'"><input type="text" id="input_n'+count+'" value="5" /> <input type="text" id="input_l'+count+'" value="0.1" /><input type="text" id="input_freq'+count+'" value="1000" /><button type="button" onclick="draw('+count+')">Submit</button></div>'
  }
  else {
    document.getElementById('graph_container2').innerHTML += '<div id="second-graph'+count+'"><input type="text" id="input_freq'+count+'" value="250" /> <input type="text" id="input_l'+count+'" value="4" /><button type="button" onclick="draw2('+count+')">Submit</button></div>'
  }
}

function draw2(svgid) {
  d3.select("#svg"+svgid).remove();
  var y = d3.range(-50,50,1);
  var x = d3.range(0,100,1);
  var freq=document.getElementById("input_freq"+svgid).value;
  var amp =1;
  var c=343;
  var k = (2*3.1415926535898*freq)/c;
  var l = document.getElementById("input_l"+svgid).value;
  var p = [];
  var width = 960;
  var height = 500;

  for (var i = 0; i < x.length; i++) {
    for (var j = 0; j < y.length; j++) {
    var r = Math.sqrt(Math.pow(x[i],2)+Math.pow((y[j]-l),2));
    var temp = math.re(math.subtract(math.multiply(math.divide(math.multiply(amp,math.pow(math.E,math.multiply(math.multiply(math.complex('-i'),k),r))),r),(l/2)),math.multiply(math.divide(math.multiply(amp,math.pow(math.E,math.multiply(math.multiply(math.complex('-i'),k),r))),r),(-l/2))));
    if (!temp) {
      temp = 0;
    }
    p.push(math.pow(temp,2)*100);
  }
  }

  var svg = d3.select("#second-graph"+svgid).append("svg")
  .attr("width", width)
  .attr("height", height)
  .attr("id", 'svg'+svgid);

  var i0 = d3.interpolateHsvLong(d3.hsv(120, 1, 0.65), d3.hsv(60, 1, 0.90)),
      i1 = d3.interpolateHsvLong(d3.hsv(60, 1, 0.90), d3.hsv(0, 0, 0.95)),
      interpolateTerrain = function(t) { return t < 0.5 ? i0(t * 2) : i1((t - 0.5) * 2); },
      color = d3.scaleSequential(interpolateTerrain).domain([90, 190]);

  d3.json("volcano.json", function(error, volcano) {
    if (error) throw error;

    svg.selectAll("path")
      .data(d3.contours()
          .size([100, 100])
          .thresholds(d3.range(1,21))
        (p))
      .enter().append("path")
        .attr("d", d3.geoPath(d3.geoIdentity().scale( width/ volcano.width)))
        .attr("fill", function(d) { return color(d.value); });
  });
}

function draw(svgid) {
var n =document.getElementById("input_n"+svgid).value;
var c=340;
var l=document.getElementById("input_l"+svgid).value*3.1415926535898;
var freq=document.getElementById("input_freq"+svgid).value;
var data = d3.range(1, 361, 1).map(function(t) {
  return [t/180*3.1415926535898,(Math.sin(n*(l/c*freq*Math.sin((t/180)* 3.1415926535898))))/(n*(l/c*freq*Math.sin((t/180)* 3.1415926535898)))];
});

var power=d3.range(1,11,1).map(function(t){
  return [t,Math.asin(0.6/t)];
});
// var data = d3.range(361).map(function(d) { return d ; });
// // for (i = 0; i < 361; i++) {
// //     data[i]=i;
// // }
//
// for (i = 0; i < data.length; i++) {
//     data[i]=(i/180)*3.1415926535898;
// }
// for (i = 0; i < data.length; i++) {
//     data[i]=Math.sin(data[i]);
// }
// for (i = 0; i < data.length; i++) {
//     data[i]=l/c*freq*data[i];
// }
// for (i = 0; i < data.length; i++) {
//     data[i]=Math.sin(n*data[i])/(n*data[i]);
// }
// document.getElementById("demo").innerHTML = power;

d3.select("#svg"+svgid).remove();

var width = 960,
    height = 500,
    radius = Math.min(width, height) / 2 - 30;

var r = d3.scale.linear()
    .domain([0, 1])
    .range([0, radius]);

var line = d3.svg.line.radial()
    .radius(function(d) { return r(d[1]); })
    .angle(function(d) { return -d[0] + Math.PI / 2; });

var svg = d3.select("#first-graph"+svgid).append("svg")
    .attr("width", width)
    .attr("height", height)
    .attr("id", 'svg'+svgid)
  .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

var gr = svg.append("g")
    .attr("class", "r axis")
  .selectAll("g")
    .data(r.ticks(5).slice(1))
  .enter().append("g");

gr.append("circle")
    .attr("r", r);

gr.append("text")
    .attr("y", function(d) { return -r(d) - 4; })
    .attr("transform", "rotate(15)")
    .style("text-anchor", "middle")
    .text(function(d) { return d; });

var ga = svg.append("g")
    .attr("class", "a axis")
  .selectAll("g")
    .data(d3.range(0, 360, 30))
  .enter().append("g")
    .attr("transform", function(d) { return "rotate(" + -d + ")"; });

ga.append("line")
    .attr("x2", radius);

ga.append("text")
    .attr("x", radius + 6)
    .attr("dy", ".35em")
    .style("text-anchor", function(d) { return d < 270 && d > 90 ? "end" : null; })
    .attr("transform", function(d) { return d < 270 && d > 90 ? "rotate(180 " + (radius + 6) + ",0)" : null; })
    .text(function(d) { return d + "°"; });

svg.append("path")
    .datum(data)
    .attr("class", "line")
    .attr("d", line);
}
</script>
</body>
</html>
