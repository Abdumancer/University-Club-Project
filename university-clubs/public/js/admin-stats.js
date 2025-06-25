window.renderBarChart = function(ctxId, labels, data, label) {
    const ctx = document.getElementById(ctxId);
    if (!ctx || !labels.length || !data.length) {
        if (ctx) {
            ctx.parentNode.insertAdjacentHTML('beforeend', '<div style="color:#888; font-size:1.1em; margin-top:10px;">No data available</div>');
            ctx.remove();
        }
        return;
    }
    const colors = labels.map((_, i) => `hsl(${Math.floor(360 * i / labels.length)}, 70%, 55%)`);
    new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: colors,
                borderColor: colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: label,
                        font: { size: 16 }
                    },
                    ticks: {
                        precision: 0 
                    }
                }
            }
        }
    });
};
