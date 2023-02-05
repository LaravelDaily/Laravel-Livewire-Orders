<div wire:poll.60="updateChartData">
    <canvas
        x-data="{
            chart: null,

            init: function () {
                let chart = new Chart($el, {
                    type: 'line',
                    data: @js($this->getData()),
                    options: {
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return '$' + context.formattedValue
                                    }
                                }
                            }
                        }
                    }
                })

                $wire.on('updateChartData', async ({ data }) => {
                    chart.data = data
                    chart.update('resize')
                })
            }
        }"
        style="height: 320px;"
        wire:ignore>
    </canvas>
</div>
