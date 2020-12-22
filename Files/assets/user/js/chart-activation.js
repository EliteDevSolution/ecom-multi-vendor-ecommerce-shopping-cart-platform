
let chartLabel = [25010, 22110, 29010, 32020, 25010, 29010, 28010,27010,31010,20010];
chartData = {
    labels: ["April", "May","June","July","September","October","November","December","January","February",],
    datasets: [
        {
            label: "",
            fill: true,
            backgroundColor: 'rgba(42, 156, 245, 0.9)',
            borderColor: 'rgba(42, 156, 245)',
            data: chartLabel,
        }]
};


createChart('chartChart', chartData);

function createChart(id,data){
    var ctx = document.getElementById(id).getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem) {
                        return tooltipItem.yLabel;
                    }
                }
            }
        }
    });

}

setTimeout(function(){
    document.getElementById('chartChart').style.height = '243px';
}, 100);
