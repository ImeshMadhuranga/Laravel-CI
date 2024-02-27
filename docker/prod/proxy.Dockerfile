FROM nginx:1.19.6-alpine
COPY ./docker/prod/fs/proxy/ /
