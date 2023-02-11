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
                data: @json($ratings)
            }],
            xaxis: {
                categories: @json($targets)
            },
        }

        var chart = new ApexCharts(document.querySelector("#rating"), options);

        chart.render();
    </script>
@endpush
