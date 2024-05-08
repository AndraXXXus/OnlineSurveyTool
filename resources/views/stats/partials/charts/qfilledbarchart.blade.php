<div class="container">


    <h3 class="font-bold my-4 text-2xl text-center"> {{$title}} </h3>

    {{-- <div style="height: 300px"> --}}
        <canvas class="p-1 ml-4 mr-4" id={{$id}}></canvas>
    {{-- </div> --}}


  <!-- Required chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</div>
  <script>

    dataBar = {
      labels: {!! json_encode($labels) !!} ,
      datasets: [
        {
          label: "Question filled",
          data:  {{ json_encode($data) }} ,
          backgroundColor: "hsl(252, 82.9%, 67.8%)",
          borderColor: "hsl(252, 82.9%, 67.8%)",
        },
      ],
    };

    configBar = {
      type: "bar",
      data: dataBar,
      choices: {plugins: {
            legend: {
            display: false
            }
        },
                        responsive: true
                    },
            };
    var chartBar = new Chart(document.getElementById('<?php echo $id ?>'), configBar);
  </script>
