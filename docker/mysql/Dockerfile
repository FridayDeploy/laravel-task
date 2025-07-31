FROM mysql:8

ARG userId=1000
ARG groupId=1000

RUN groupmod -g $groupId mysql && usermod -u $userId -g $groupId mysql
USER mysql