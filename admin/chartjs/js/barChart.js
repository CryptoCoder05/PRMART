/*
const CHART = document.getElementById('barChart');
let barChart = new Chart(CHART, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [
        {
          label: "Sales By This Year",
          borderWidth: 1,
          backgroundColor: 'rgb(255, 99, 132)',
          borderColor: 'rgba(255, 99, 132, 1)',
          data: [225, 110, 50, 12, 230, 310, 445,210,250,51,510,700],
        },
        {
          label: "Sales By Last Year",
          borderWidth: 1,
          backgroundColor: 'rgba(0, 255, 255, 0.1)',
          borderColor: 'rgba(255, 99, 132, 1)',
          data: [252, 108, 510, 312, 260, 410, 405,250,501,251,450,670],
        }
      ]
    }
});



//---Radar chart-----
const rCHART = document.getElementById('radarChart');
let radarChart = new Chart(rCHART, {
    // The type of chart we want to create
    type: 'radar',

    // The data for our dataset
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [
        {
          label: "Sales By This Year",
          borderWidth: 1,
          backgroundColor: 'rgba(00, 255, 00, 0.1)',
          borderColor: 'rgba(255, 99, 132, 1)',
          data: [225, 110, 50, 12, 230, 310, 445,210,250,51,510,700],
        },
        {
          label: "Sales By Last Year",
          borderWidth: 1,
          backgroundColor: 'rgba(0, 255, 255, 0.1)',
          borderColor: 'rgba(255, 99, 132, 1)',
          data: [252, 108, 510, 312, 260, 410, 405,250,501,251,450,670],
        }
      ]
    }
});

//---Polar area chart---
const pCHART = document.getElementById('polarAreaChart');
let polarChart = new Chart(pCHART, {
    // The type of chart we want to create
    type: 'polarArea',

    // The data for our dataset
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [
        {
          label: "Sales By This Year",
          backgroundColor: ['#f1c40f','#e67e22','#16a085','#2980b9','#8e44ad','#f1c40f','#e67e22','#16a085','#2980b9','#8e44ad','#f1c40f','#e67e22'],
          data: [225, 110, 150, 182, 230, 310, 445,210,250,541,510,700],
        }
      ]
    }
});

//---pie  chart---
const pieCHART = document.getElementById('pieChart');
let pieChart = new Chart(pieCHART, {
    // The type of chart we want to create
    type: 'pie',

    // The data for our dataset
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [
        {
          label: "Sales By This Year",
          backgroundColor: ['#f1c40f','#e67e22','#16a085','#2980b9','#8e44ad','#f1c40f','#e67e22','#16a085','#2980b9','#8e44ad','#f1c40f','#e67e22'],
          data: [225, 110, 150, 182, 230, 310, 445,210,250,541,510,700],
        }
      ]
    }
});

//---doughnut Chart---
const douCHART = document.getElementById('doughnutChart');
let douChart = new Chart(douCHART, {
    // The type of chart we want to create
    type: 'doughnut',

    // The data for our dataset
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [
        {
          label: "Sales By This Year",
          backgroundColor: ['#f1c40f','#e67e22','#16a085','#2980b9','#8e44ad','#f1c40f','#e67e22','#16a085','#2980b9','#8e44ad','#f1c40f','#e67e22'],
          data: [225, 110, 150, 182, 230, 310, 445,210,250,541,510,700],
        }
      ]
    }
});
