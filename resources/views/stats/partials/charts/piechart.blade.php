<div class="container">


    <p class="font-bold text-2xl">{{$question_text}}</p>



    <p class="font-bold text-2xl text-center"> {{$extra_text}}</p>




    {{-- <div style="height: 300px; width: 400px"> --}}
    <canvas style="height: 300px; width: 400px" class="p-3 ml-auto  mr-auto" id={{$question_id}}></canvas>
    {{-- </div> --}}


</div>
  <!-- Required chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Chart pie -->
  <script>
    dataPie = {
      labels: {!!json_encode($labels)!!} ,
      datasets: [
        {
          label: "Occurrence",
          data:  {{ json_encode($data) }} ,
          backgroundColor: [
            "rgb(133, 105, 241)",
            "rgb(164, 101, 241)",
            "rgb(101, 143, 241)",
          ],
          hoverOffset: 4,
        },
      ],
    };

    configPie = {
      type: "pie",
      data: dataPie,
      options: {
      legend: {
        display: false
      }
   },
      choices: {plugins: {
    legend: {

      display: false
    }
  },
                responsive: false
            },
    };

    var chartBar = new Chart(document.getElementById('<?php echo $question_id ?>'), configPie);
  </script>
