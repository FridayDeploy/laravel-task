FROM node:20

ARG userId=1000
ARG groupId=1000

RUN groupmod -g $groupId node && usermod -u $userId -g $groupId node
USER node