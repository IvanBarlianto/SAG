FROM composer:2

ARG UID
ARG GID
ARG USER

ENV UID=${UID}
ENV GID=${GID}
ENV USER=${USER}

# Hapus delgroup dialout jika tidak diperlukan
# RUN delgroup dialout

# Tambahkan grup dengan ID yang diberikan
RUN addgroup -g ${GID} ${USER}

# Tambahkan pengguna dengan ID pengguna dan ID grup yang diberikan
RUN adduser -G ${USER} --system -D -s /bin/sh -u ${UID} ${USER}

WORKDIR /var/www/html
