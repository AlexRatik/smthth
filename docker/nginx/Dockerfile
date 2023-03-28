FROM nginx:latest

RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    lsb-release \
    && curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html

COPY . /var/www/html

RUN npm install
