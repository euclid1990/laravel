FROM ubuntu:18.04

ENV DEBIAN_FRONTEND noninteractive

# Install os packages
RUN apt-get update && apt-get install -y \
    openssl \
    apache2-utils \
    --no-install-recommends apt-utils \
    && rm -r /var/lib/apt/lists/*

COPY cert/rootCA.csr.cnf /scripts/

COPY cert/v3.ext /scripts/

COPY cert/*.sh /scripts/

RUN chmod a+x /scripts/*.sh

RUN mkdir /files

CMD ["/scripts/command.sh", "localhost"]
