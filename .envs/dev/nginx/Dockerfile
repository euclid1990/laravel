FROM nginx:1.15.11

# Install os packages
RUN apt-get update && apt-get install -y \
    vim \
    curl \
    && rm -r /var/lib/apt/lists/*

COPY nginx/*.sh /scripts/

COPY common/wait-for-it.sh /scripts/

RUN chmod a+x /scripts/*.sh

EXPOSE 80 443

CMD ["/scripts/command.sh"]
