FROM matiux/php:7.1-fpm-2.2.0

RUN echo 'alias test="vendor/bin/simple-phpunit --exclude-group=ignored"' >> /home/utente/.bashrc && \
    echo 'alias test-ignored="vendor/bin/simple-phpunit --group=ignored"' >> /home/utente/.bashrc && \
    echo 'alias test-all="vendor/bin/simple-phpunit"' >> /home/utente/.bashrc && \
    echo 'alias sf="bin/console"' >> /home/utente/.bashrc  && \
    echo 'alias sfcc="rm -Rf var/cache/*"' >> /home/utente/.bashrc  && \
    /usr/local/bin/composer self-update

RUN sed -i "s/user = www-data/user = utente/" /etc/php/7.1/fpm/pool.d/www.conf && \
    sed -i "s/group = www-data/group = utente/" /etc/php/7.1/fpm/pool.d/www.conf

RUN apt-get update && apt-get install -y git rsync curl && \
    rm -rf /var/lib/apt/lists/* && \
    apt-get purge --auto-remove -y && \
    rm -rf /src/*.deb

RUN echo "ServerAliveInterval 120" >> /etc/ssh/ssh_config

RUN sed -i "s/\/usr\/bin\/supervisord//" /usr/local/bin/start-container && \
    echo "" >> /usr/local/bin/start-container && \
    echo "sed -i \"s/{email}/\$GIT_USER_EMAIL/\" /home/utente/.gitconfig" >> /usr/local/bin/start-container && \
    echo "sed -i \"s/{name}/\$GIT_USER_NAME/\" /home/utente/.gitconfig" >> /usr/local/bin/start-container && \
    echo "/usr/bin/supervisord" >> /usr/local/bin/start-container

COPY --chown=utente:utente .gitconfig /home/utente/
COPY --chown=utente:utente var/.ssh/* /home/utente/.ssh/
RUN  chmod 600 /home/utente/.ssh/id_rsa
