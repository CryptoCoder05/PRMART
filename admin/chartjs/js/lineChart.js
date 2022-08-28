/*------------------------------Sales by months------------------------------------*/
const ctx = document.getElementById('myChart').getContext('2d');
let line1chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [
          {
            label: "Sales By This Year",
            fill: false,
            lineTension: 0.2,
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderCapStyle : 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(255, 99, 132,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(255, 99, 132,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [225, 110, 50, 12, 230, 310, 445,210,250,51,510,700],
        },
        {
          label: "Sales By Last Year",
          fill: true,
          lineTension: 0.2,
          backgroundColor: 'rgba(75, 192, 192,0.4)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderCapStyle : 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          pointBorderColor: "rgba(75,192,192,1)",
          pointBackgroundColor: "#fff",
          pointBorderWidth: 1,
          pointHoverRadius: 5,
          pointHoverBackgroundColor: "rgba(75,192,192,1)",
          pointHoverBorderColor: "rgba(220,220,220,1)",
          pointHoverBorderWidth: 2,
          pointRadius: 1,
          pointHitRadius: 10,
          data: [205, 150, 70, 112, 20, 210, 245,290,580,251,310,500],
      }
      ]
    },

    // Configuration options go here
    options: {}
});

//------------------------------Inventary line chart------------------------------------
var lineChart = document.getElementById('lineChart').getContext('2d');
var line2chart = new Chart(lineChart, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [
          {
            label: "Quantity",
            fill: false,
            lineTension: 0.2,
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderCapStyle : 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(255, 99, 132,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(255, 99, 132,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [225, 110, 50, 12, 230, 310, 445,210,250,51,510,700],
        },
        {
          label: "Threshold",
          fill: true,
          lineTension: 0.2,
          backgroundColor: 'rgba(75, 192, 192,0.4)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderCapStyle : 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          pointBorderColor: "rgba(75,192,192,1)",
          pointBackgroundColor: "#fff",
          pointBorderWidth: 1,
          pointHoverRadius: 5,
          pointHoverBackgroundColor: "rgba(75,192,192,1)",
          pointHoverBorderColor: "rgba(220,220,220,1)",
          pointHoverBorderWidth: 2,
          pointRadius: 1,
          pointHitRadius: 10,
          data: [205, 150, 70, 112, 20, 210, 245,290,580,251,310,500],
      }
      ]
    },

    options: {}
});
