"use strict";

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
        pointBackgroundColor: '#fff',
        pointRadius         : 4,
        borderWidth         : 5,
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
          pointBackgroundColor: '#fff',
          pointRadius         : 4,
          borderWidth         : 5,

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
            lineWidth: 2,
            
          },
          ticks    : ticksStyle
        }]
      }
      
    }
  })



// //one day
// var $oneChart = $('#oneday-chart')
//   var oneChart  = new Chart($oneChart, {
//     type   : 'bar',
//     data   : {
//       labels  : country.split(','),
//       datasets: [
//         {
//           backgroundColor: '#007bff',
//           borderColor    : '#007bff',
//           data           : players
//         },
//         // {
//         //   backgroundColor: '#ced4da',
//         //   borderColor    : '#ced4da',
//         //   data           : [700, 1700, 2700, 2000, 1800, 1500, 2000]
//         // }
//       ]
//     },
//     options: {
//       //maintainAspectRatio: false,
//       tooltips           : {
//         mode     : mode,
//         intersect: intersect
//       },
//       hover              : {
//         mode     : mode,
//         intersect: intersect
//       },
//       legend             : {
//         display: false
//       },
//       scales             : {
//         yAxes: [{
//           // display: false,
//           gridLines: {
//             display      : true,
//             lineWidth    : '4px',
//             color        : 'rgba(0, 0, 0, .2)',
//             zeroLineColor: 'transparent'
//           },
//           ticks    : $.extend({
//             beginAtZero: true,

            
//           }, ticksStyle)
//         }],
//         xAxes: [{
//           display  : true,
//           gridLines: {
//             display: true
//           },
//           ticks    : ticksStyle
//         }]
//       }
//     }
//   })

// //seven day
// var $sevenChart = $('#sevenday-chart')
//   var sevenChart  = new Chart($sevenChart, {
//     type   : 'bar',
//     data   : {
//       labels  : country7.split(','),
//       datasets: [
//         {
//           backgroundColor: '#007bff',
//           borderColor    : '#007bff',
//           data           : players7
//         },
//         // {
//         //   backgroundColor: '#ced4da',
//         //   borderColor    : '#ced4da',
//         //   data           : [700, 1700, 2700, 2000, 1800, 1500, 2000]
//         // }
//       ]
//     },
//     options: {
//       //maintainAspectRatio: false,
//       tooltips           : {
//         mode     : mode,
//         intersect: intersect
//       },
//       hover              : {
//         mode     : mode,
//         intersect: intersect
//       },
//       legend             : {
//         display: false
//       },
//       scales             : {
//         yAxes: [{
//           // display: false,
//           gridLines: {
//             display      : true,
//             lineWidth    : '4px',
//             color        : 'rgba(0, 0, 0, .2)',
//             zeroLineColor: 'transparent'
//           },
//           ticks    : $.extend({
//             beginAtZero: true,

            
//           }, ticksStyle)
//         }],
//         xAxes: [{
//           display  : true,
//           gridLines: {
//             display: true
//           },
//           ticks    : ticksStyle
//         }]
//       }
//     }
//   })

// //month day
// var $monthChart = $('#month-chart')
//   var monthChart  = new Chart($monthChart, {
//     type   : 'bar',
//     data   : {
//       labels  : countryM.split(','),
//       datasets: [
//         {
//           backgroundColor: '#007bff',
//           borderColor    : '#007bff',
//           data           : playersM
//         },
//         // {
//         //   backgroundColor: '#ced4da',
//         //   borderColor    : '#ced4da',
//         //   data           : [700, 1700, 2700, 2000, 1800, 1500, 2000]
//         // }
//       ]
//     },
//     options: {
//       //maintainAspectRatio: false,
//       tooltips           : {
//         mode     : mode,
//         intersect: intersect
//       },
//       hover              : {
//         mode     : mode,
//         intersect: intersect
//       },
//       legend             : {
//         display: false
//       },
//       scales             : {
//         yAxes: [{
//           // display: false,
//           gridLines: {
//             display      : true,
//             lineWidth    : '4px',
//             color        : 'rgba(0, 0, 0, .2)',
//             zeroLineColor: 'transparent'
//           },
//           ticks    : $.extend({
//             beginAtZero: true,

            
//           }, ticksStyle)
//         }],
//         xAxes: [{
//           display  : true,
//           gridLines: {
//             display: true
//           },
//           ticks    : ticksStyle
//         }]
//       }
//     }
//   })
