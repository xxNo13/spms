<div>
    <div id="rating"></div>
</div>


@push('rating')
    <script>
        var options = {
            chart: {
                type: 'bar',
                height: '350px',
                zoom: {
                    enabled: false,
                },
                toolbar: {
                    show: false,
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Rating score',
                data: [5,5,4,3,5]
            }],
            xaxis: {
                categories: ['Target 1' ,'Target 2' ,'Target 3' ,'Target 4' ,'Target 5']
            },
        }

        var chart = new ApexCharts(document.querySelector("#rating"), options);

        chart.render();
    </script>
@endpush
