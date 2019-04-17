FROM mysql:5.7

RUN apt-get update && \
    apt-get install -y vim sudo \
    --no-install-recommends apt-utils \
    && rm -r /var/lib/apt/lists/*

COPY mysql/entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod a+x /usr/local/bin/docker-entrypoint.sh

RUN rm /entrypoint.sh

RUN ln -s /usr/local/bin/docker-entrypoint.sh /entrypoint.sh # backward compatibility

ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 3306 33060

CMD ["mysqld"]
