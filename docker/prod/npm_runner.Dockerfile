FROM node:18.0.0-alpine3.14
WORKDIR /app/
RUN npm install -g npm@9.6.6
RUN apk add g++ make python2
COPY ./application/package.json .
COPY ./application/package-lock.json .
RUN npm ci
COPY ./application/ .
