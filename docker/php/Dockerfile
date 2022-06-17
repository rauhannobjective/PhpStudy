FROM php:8.0-fpm

# Autor do arquivo
LABEL maintainer "Rauhann Chaves"

# Usuário host
ARG UID=1002
ARG GID=1002

# Setando diretorio
WORKDIR /app

# Dependencias PHP e Servidor
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions pdo_mysql zip exif pcntl gd

RUN apt-get update && apt-get install -y \
    build-essential \
    locales \
    zip \
    vim \
    unzip \
    git \
    curl

# Sincroniza usuário e grupo com o host
RUN groupadd -g ${GID} nginx && \
    useradd -u ${UID} -ms /bin/bash -g nginx nginx

# Instalação do composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.3.5

# Copiando arquivos para container
COPY --chown=nginx:nginx . /app
COPY ./docker/bash/entrypoint.sh /sbin/entrypoint.sh

# Expondo porta de serviço PHP
EXPOSE 9000

# Script para inicialização
CMD ["/sbin/entrypoint.sh"]
