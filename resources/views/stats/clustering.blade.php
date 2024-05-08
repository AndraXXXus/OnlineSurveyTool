@extends('layouts.app')
@section('title', 'Stats')

@section('content')

<div class="container">

    <h1 class="font-bold my-4 text-4xl text-center"> Clustering </h1>

    {{-- <form method="POST" action="{{ route('stats.download_clusters') }}">
        @csrf
        <input type="hidden" name="cluster_indexes" id="cluster_indexes" value="">
        <input type="hidden" name="responderIds" id="responder_Ids" value="">
        <button type="submit">Submit</button>
    </form> --}}

    <hr>
    <select id = "select_choices" title='select cluster number'>
    </select>
    <select id = "select_linkage" title='select cluster linkage'>
        <option value="single">Single</option>
        <option value="complete">Complete</option>
    </select>

    <div class="container">



    </div >
    <div class="d-flex flex-wrap items-center  justify-content-center gap-2 p-3">
        <div class="card">
            <div class="card-body">
                <svg id="svg" height="1000" width="1000"></svg>
            </div>
        </div>

    </div>

</div>
@endsection

<script src="https://unpkg.com/@saehrimnir/druidjs"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.9.2/d3.js"></script>

<script>
    let global_C_indexes=0;

    document.addEventListener("DOMContentLoaded", function(event) {
        let max = 0;
        let nodes = 0;
        const matrix = druid.Matrix.from(@js($matrix));
        const dist_matrix = druid.distance_matrix(matrix, druid.cosine);

        const select_linkage = document.getElementById("select_linkage");

        select = document.getElementById('select_choices');
        select.value = 0 ;
        select_linkage.addEventListener("change", changeLinkage);
        changeLinkage();

        select.addEventListener("change", clusterit );


        function changeLinkage(){


            h_clustered = new druid.Hierarchical_Clustering(dist_matrix, select_linkage.value, 'precomputed');

            nodes = h_clustered.root.descendants();

            uniqu_dist_values = [...new Set(nodes.map(node => Number(node['dist'])))];

            uniqu_dist_values = uniqu_dist_values.sort(function (a, b) { return a - b; });
            max = d3.max(uniqu_dist_values);

            while (select.firstChild) {
                select.removeChild(select.lastChild);
            }

            for (var i = 0; i<uniqu_dist_values.length; i++){
                var opt = document.createElement('choice');
                opt.value = uniqu_dist_values[i];
                opt.innerHTML = uniqu_dist_values.length-i;
                select.appendChild(opt);
            }

            clusterit();
        }


        function clusterit(){

            d3.selectAll("g > *").remove()


            cut_dist = select.value;
            X = druid.Matrix.from(matrix);
            Y = X;
            data = X;


            threshold = cut_dist;
            const H_clusters = h_clustered.get_clusters(cut_dist, 'distance');

            let C_indexes = Array.from({length: matrix.length});
            global_C_indexes = C_indexes;

            for (let cluster_index = 0; cluster_index < H_clusters.length; ++cluster_index) {
                H_clusters[cluster_index].forEach(({index}) => C_indexes[index] = cluster_index)
            }



            const leaves = nodes.filter(n => n.isLeaf);

            const links = [];
            leaves.forEach((node, i) => node.x = i)
            nodes.forEach((node, i) => {
                node.x = node.x ?? d3.mean(node.leaves(), d => d.x);

                [node.left, node.right].forEach(child => {
                if (child) {
                    links.push({
                    "source": node,
                    "target": child
                    })
                }
                })
            })


            createVisualization(600, Y, C_indexes, data, max, 'dist', nodes, links, threshold);


        }


        function createVisualization(width, Y, clusters, data, max, type, nodes, links, threshold) {

            const W = 600;
            const H = 500;
            const margin = 20;

            c = d3.scaleOrdinal(d3.schemeDark2).domain(Array.from(new Set(clusters)));

            const svg = d3.select("svg").attr("width", W).attr("height", H + margin);

            const xp = d3.scaleLinear()
                .domain(d3.extent(Y, d => d[0]))
                .range([margin, W - margin]);

            const yp = d3.scaleLinear()
                .domain(d3.extent(Y, d => d[1]))
                .range([margin, H - margin]);


            d3.selectAll("circle")
                .data(Y)
                .enter()
                .append("circle")
                .attr("cx", d => xp(d[0]))
                .attr("cy", d => yp(d[1]))
                .attr("r", 10)
                .attr("fill", (_, i) => c(clusters[i]));


            const g_dendogram = svg.append("g").attr("transform", "translate(" + margin + ", " + margin + ")");

            g_dendogram.append("text").text("Dendogram");

            const x = d3.scaleLinear()
                .domain([-1, data._rows])
                .range([margin, W  - margin]);

            const y = d3.scaleLinear()
                .domain([max, 0])
                .nice()
                .range([margin, H - margin]);

            const link = ({ source, target }) => {
                const x1 = x(source.x);
                const y1 = y(source[type]);
                const x2 = x(target.x);
                const y2 = y(target[type]);
                const max_radius = 200;
                const x_dist = Math.abs(x1 - x2);
                const y_dist = Math.abs(y1 - y2);
                const radius = Math.min(x_dist, y_dist, max_radius);
                const cx = x1 < x2 ? radius : -radius;
                const counter_clockwise = cx < 0 ? 0 : 1;
                const xa = x2 - cx;

                return `M ${x1} ${y1} H ${xa} a ${radius} ${radius} 0 0 ${counter_clockwise} ${cx} ${radius} V ${y2}`;
            };

            g_dendogram.selectAll("path")
                .data(links)
                .enter()
                .append("path")
                .attr("d", link)
                .attr("fill", "none")
                .attr("stroke-width", "2")
                .attr("stroke", ({ source }) => {
                const cluster_set = new Set(source.index.map(i => clusters[i]));
                return cluster_set.size > 1 ? "grey" : c([...cluster_set.values()][0]);
                });

            g_dendogram.selectAll("circle")
                .data(nodes)
                .enter()
                .append("circle")
                .attr("cx", d => x(d.x))
                .attr("cy", d => y(d[type]))
                .attr("r", d => 8)
                .attr("fill", (d, i) => (d.isLeaf ? c(clusters[d.index]) : "none"));

            g_dendogram.append("path")
                .attr("d", `M ${margin} ${y(threshold)} h ${W -  margin}`)
                .attr("fill", "none")
                .attr("stroke", "black");

            g_dendogram.append("text")
                .attr("x", margin)
                .attr("dx", 5)
                .attr("y", y(threshold))
                .attr("dy", -5)
                .text(`${type}: ${d3.format(".2f")(threshold)}`);

            const axisG = g_dendogram.append("g");
            axisG.attr("transform", "translate(" + margin + ", 0)");
            axisG.call(d3.axisLeft(y));


            const yAxis = d3.axisLeft(y);
            svg.append(yAxis(axisG));

            console.log("global_C_indexes: "+global_C_indexes);
        }

    });
</script>
