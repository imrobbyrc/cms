var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
    }
var mode      = 'index'
var intersect = true

var $playerChart = $('#playerchart')
var playerChart  = new Chart($playerChart, {
    data   : {
      labels  : day.split(','),
      datasets: [{
        label: 'New',
        type                : 'line',
        data                : data_register,
        backgroundColor     : '#007bff',
        borderColor         : '#007bff',
        pointBorderColor    : '#007bff',
        pointBackgroundColor: '#007bff',
        fill                : false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      },
        {
          label: 'Blocked',
          type                : 'line',
          data                : data_block,
          backgroundColor     : '#FF0000',
          borderColor         : '#FF0000',
          pointBorderColor    : '#FF0000',
          pointBackgroundColor: '#FF0000',
          fill                : false
          // pointHoverBackgroundColor: '#ced4da',
          // pointHoverBorderColor    : '#ced4da'
        }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      // legend             : {
      //   display: true
      // },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            
          },
          ticks    : $.extend({
            beginAtZero : true,
            suggestedMax: 50,
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: true,
            
          },
          ticks    : ticksStyle
        }]
      }
      
    }
  })
