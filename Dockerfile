FROM surnet/alpine-wkhtmltopdf:3.16.2-0.12.6-full as wkhtmltopdf

FROM alpine:3.17
ENV TZ=Asia/Shanghai

RUN apk update && apk --no-cache add nodejs npm

RUN apk --no-cache add php php-fpm php-opcache php-mysqli php-json php-openssl php-curl php81-pecl-redis \
    php-bcmath php-calendar php-dom php-exif php-fileinfo php-ftp php-gd php-gettext php-session \
    php-zlib php-xml php-phar php-intl  php-xmlreader php-ctype php-mbstring  php-tokenizer php-simplexml \
    php-bcmath php-xmlwriter php-pdo_mysql  strace php-zip php-zlib php-xsl php-xmlwriter php-sysvshm \
    php-sysvsem php-sysvmsg php-sodium php-sockets php-shmop php-posix  php-openssl  php-mysqli \
    php-mbstring php-ffi php-iconv php-imap php-mysqlnd php-pcntl nginx curl  python3 supervisor
#RUN apk  --no-cache add npm yarn
RUN apk add tzdata \
    &&  ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apk add --no-cache dcron libcap && \
    chown nobody:nobody /usr/sbin/crond && \
    setcap cap_setgid=ep /usr/sbin/crond

RUN apk add --no-cache \
        libstdc++ \
        libx11 \
        libxrender \
        libxext \
        libssl1.1 \
        ca-certificates \
        fontconfig \
        freetype \
        ttf-droid \
        ttf-freefont \
        ttf-liberation
COPY --from=wkhtmltopdf /bin/wkhtmltopdf /bin/wkhtmltopdf
COPY --from=wkhtmltopdf /bin/wkhtmltoimage /bin/wkhtmltoimage
COPY --from=wkhtmltopdf /bin/libwkhtmltox* /bin/
RUN apk upgrade
RUN apk update

ADD . /www/wwwroot/test-render
WORKDIR  /www/wwwroot/test-render
RUN mkdir -p /etc/nginx/sites-enabled/ /etc/supervisor/conf.d/  /run/nginx \
    && touch /run/nginx/nginx.pid \
    && cp www.conf /etc/php81/php-fpm.d/www.conf \
    && cp supervisord.conf /etc/supervisor/conf.d/supervisord.conf \
    && cp default.conf /etc/nginx/http.d/ \
    && rm -r Dockerfile \
    && curl https://getcomposer.org/composer-2.phar > /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && composer install --no-dev \
    #cron
    && cat /www/wwwroot/test-render/crontabfile > /var/spool/cron/crontabs/nobody \
    && chmod 600 /var/spool/cron/crontabs/nobody \
    && chown -R nobody.nobody /var/spool/cron/crontabs \
    && touch /var/spool/cron/crontabs/root \
    && chown -R nobody.nobody /var/spool/cron/crontabs/root \
    && chown nobody.nobody /var/spool/cron/crontabs/nobody \
    && touch /var/log/cron.log \
    && chown nobody.nobody /var/log/cron.log

RUN --mount=type=secret,id=_env,dst=/etc/secrets/.env cat /etc/secrets/.env > /www/wwwroot/test-render/.env

RUN chmod -R 777 /www/wwwroot/test-render/ \
    && npm install \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan migrate --force \
    && php artisan breeze:install blade --no-interaction

USER root

EXPOSE 80
CMD ["/usr/bin/supervisord","-c","/etc/supervisor/conf.d/supervisord.conf"]
