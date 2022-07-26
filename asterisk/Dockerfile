FROM ubuntu:22.04

LABEL maintainer="Amir Fakour"

SHELL ["/bin/bash", "-c"]

WORKDIR /usr/src

RUN DEBIAN_FRONTEND=noninteractive
RUN apt-get update -y
RUN apt-get install -y \
    zip \
    unzip \
    vim \
    git \
    curl \
    wget \
    libnewt-dev \
    libssl-dev \
    libncurses5-dev \
    subversion \
    libsqlite3-dev \
    build-essential \
    libjansson-dev \
    libxml2-dev \
    uuid-dev \ 
    unixodbc \
    unixodbc-dev

# Set Timezone
RUN echo ${TZ} > /etc/localtime && \
    apt-get install -y tzdata &&\
    dpkg-reconfigure --frontend noninteractive tzdata

# Install Asterisk
RUN wget https://downloads.asterisk.org/pub/telephony/asterisk/asterisk-19.5.0.tar.gz && \
    tar xzvf asterisk-19.5.0.tar.gz && \
    rm asterisk-19.5.0.tar.gz

WORKDIR /usr/src/asterisk-19.5.0

RUN ./contrib/scripts/get_mp3_source.sh && \
    ./contrib/scripts/install_prereq install && \
    ./configure --libdir=/usr/lib64 --with-jansson-bundled --with-pjproject-bundled

# Configure Menuselect
RUN make menuselect.makeopts && menuselect/menuselect \
    --enable chan_ooh323 \
    --enable format_mp3 \
    --enable app_macro \
    --enable agi-test.agi \
    --enable eagi-test \
    --enable eagi-sphinx-test \
    --enable jukebox.agi \
    menuselect.makeopts

RUN make && make install && \
    make samples && \
    make config

# Grant permissions to asterisk user
RUN groupadd asterisk && \
    useradd -r -d /var/lib/asterisk -g asterisk asterisk && \
    usermod -aG audio,dialout asterisk && \
    chown -R asterisk.asterisk /etc/asterisk && \
    chown -R asterisk.asterisk /var/{lib,log,spool}/asterisk && \
    chown -R asterisk.asterisk /usr/lib64/asterisk && \
    LIB_DIR=`find / -name libasteriskssl.so.1 | head -n 1 | xargs dirname` && \
    echo $LIB_DIR > /etc/ld.so.conf.d/libc.conf && \
    sed -i 's/#AST_USER="asterisk"/AST_USER="asterisk"/' /etc/default/asterisk && \
    sed -i 's/;run/run/g' /etc/asterisk/asterisk.conf && \
    ldconfig

# Install Alembic
RUN apt-get install -y python3-pip && \
    pip install alembic && \
    pip install mysqlclient

COPY ./ custom

# Realtime Database Configuration 
RUN apt-get -y install odbc-mariadb && \
    cat custom/config/extconfig.conf >> /etc/asterisk/extconfig.conf && \
    cat custom/config/pjsip.conf >> /etc/asterisk/pjsip.conf && \
    cat custom/config/sorcery.conf >> /etc/asterisk/sorcery.conf && \
    cat custom/config/modules.conf >> /etc/asterisk/modules.conf && \
    cat custom/config/cdr_adaptive_odbc.conf >> /etc/asterisk/cdr_adaptive_odbc.conf

RUN cat custom/config/extensions.conf >> /etc/asterisk/extensions.conf

# Set Limit udp ports
RUN sed -i "s/rtpend=20000/rtpend=10500/g" /etc/asterisk/rtp.conf

RUN service asterisk start

CMD sh custom/scripts/initiator.sh && /usr/sbin/asterisk -vvvvvvdddf