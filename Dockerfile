FROM tawazz/php7

COPY docker/config.conf /etc/apache2/sites-enabled/

RUN mkdir -p /app
RUN mkdir -p /app/logs
WORKDIR /app

RUN apt-get update && apt-get install -y npm \
supervisor cron  php7.0-gd  php-xdebug mysql-client

RUN curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.2/install.sh | bash && \
. "/root/.nvm/nvm.sh" && nvm install --lts

RUN npm install -g yarn

COPY docker/services /etc/supervisor/conf.d
COPY docker/tasks /etc/cron.d/tasks
COPY docker/php.ini /etc/php/7.0/apache2/php.ini

RUN touch /app/logs/cron.log
RUN chmod 0644 /etc/cron.d/tasks
RUN cp /usr/bin/nodejs /usr/bin/node

WORKDIR /app

EXPOSE 80
CMD ["/usr/bin/supervisord", "-n"]
