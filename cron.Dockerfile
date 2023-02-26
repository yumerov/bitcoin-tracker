FROM docker.io/bitnami/laravel:10

RUN apt-get update && apt-get install -y cron

RUN echo "* * * * * root php artisan schedule:run >> /dev/null 2>&1" >> /etc/crontab

CMD ["cron", "-f"]
