const script = document.createElement('script');
script.src = 'https://cdn.jsdelivr.net/npm/chart.js';

function handleForm() {
    const form = document.getElementById('subscribe-form');
    const errors = document.getElementById('errors');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        errors.classList.add('state-disabled');

        fetch('/api/subscribe', {
            method: 'POST',
            body: new FormData(form)
        })
            .then(response => {
                if (response.ok) {
                    document.getElementById('email').value = null;
                    document.getElementById('price').value = null;
                } else {
                    response.json()
                        .then(res => {
                            const emailErrors = res.errors.email;
                            const priceErrors = res.errors.price;

                            if (emailErrors) {
                                for (const error of emailErrors) {
                                    errors.innerHTML += `Email: ${error}<br />`;
                                }
                            }

                            if (priceErrors) {
                                for (const error of priceErrors) {
                                    errors.innerHTML += `Email: ${error}<br />`;
                                }
                            }
                        })
                }
            })
            .catch(error => {
                console.log(error);
            })
            .finally(function () {
                errors.classList.remove('state-disabled');
            });
    });
}

function getData(callback)
{
    fetch('/api/prices')
        .then(response => response.json())
        .then(callback)
        .catch(error => console.error(error));
}

function drawChart() {
    const context = document.getElementById('chart');
    const data = {
        labels: [],
        datasets: [{
            label: 'Price in USD',
            data: [],
            borderWidth: 1
        }]
    };

    getData(function (prices) {
        for (const timestamp of Object.keys(prices)) {
            data.labels.push(timestamp);
            data.datasets[0].data.push(prices[timestamp]);
        }

        new Chart(context, {
            type: 'line',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
}

function init() {
    drawChart();
    handleForm();
}

script.onload = init;
document.head.appendChild(script);
