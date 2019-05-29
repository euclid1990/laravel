FROM node:10.15.3-stretch

WORKDIR /var/www/app/client

COPY client/*.sh /scripts/

RUN chmod a+x /scripts/*.sh

RUN npm install -g @vue/cli

EXPOSE 3030

CMD ["/scripts/command.sh"]
