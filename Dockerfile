FROM php:8.3-fpm

# Dependencias del sistema (agregamos libaio1 y unzip, que Oracle necesita)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libaio1t64 \
    && rm -rf /var/lib/apt/lists/*

# --- Oracle Instant Client ---
# Descargamos las librerías de Oracle (basic + sdk) y las descomprimimos
# --- Oracle Instant Client ---
# Descargamos las librerías de Oracle (basic + sdk) y las descomprimimos
RUN mkdir -p /opt/oracle && cd /opt/oracle \
    && curl -fL -o instantclient-basic.zip https://download.oracle.com/otn_software/linux/instantclient/2380000/instantclient-basic-linux.x64-23.8.0.25.04.zip \
    && curl -fL -o instantclient-sdk.zip   https://download.oracle.com/otn_software/linux/instantclient/2380000/instantclient-sdk-linux.x64-23.8.0.25.04.zip \
    && { unzip -o instantclient-basic.zip || true; } \
    && { unzip -o instantclient-sdk.zip || true; } \
    && rm -f instantclient-basic.zip instantclient-sdk.zip \
    && echo /opt/oracle/instantclient_23_8 > /etc/ld.so.conf.d/oracle-instantclient.conf \
    && { ldconfig || true; }

# Oracle busca libaio.so.1, pero Debian 13 la instala como libaio.so.1t64: creamos el enlace
RUN ln -s /usr/lib/x86_64-linux-gnu/libaio.so.1t64 /usr/lib/x86_64-linux-gnu/libaio.so.1    

# --- Extensión oci8 de PHP ---
# La configuramos apuntando al Instant Client y la compilamos
RUN docker-php-ext-configure oci8 --with-oci8=instantclient,/opt/oracle/instantclient_23_8 \
    && docker-php-ext-install oci8

# Extensiones que ya tenías (mantenemos MySQL para no perder la red de seguridad)
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["php-fpm"]