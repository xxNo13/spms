<div>
    <div id="assignment"></div>
</div>


@push('assignment')
    <script>
        var options = {
            chart: {
                type: 'bar',
                height: '250px',
                zoom: {
                    enabled: false,
                },
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Target Finished',
                data: @json($assignments)
            }],
            xaxis: {
                categories: @json($days)
            }
        }

        var chart = new ApexCharts(document.querySelector("#assignment"), options);

        chart.render();
    </script>
@endpush
