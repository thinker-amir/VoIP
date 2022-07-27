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
    unixodbc-dev \
    odbc-mariadb

# Set Timezone
RUN echo ${TZ} > /etc/localtime && \
    apt-get install -y tzdata &&\
    dpkg-reconfigure --frontend noninteractive tzdata

# Clone Asterisk
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
    --enable CORE-SOUNDS-EN-WAV  \
    --enable CORE-SOUNDS-EN-ULAW  \
    --enable CORE-SOUNDS-EN-ALAW  \
    --enable CORE-SOUNDS-EN-GSM  \
    --enable CORE-SOUNDS-EN-G729  \
    --enable CORE-SOUNDS-EN-G722  \
    --enable CORE-SOUNDS-EN-SLN16  \
    --enable CORE-SOUNDS-EN-SIREN7  \
    --enable CORE-SOUNDS-EN-SIREN14  \
    --enable MOH-OPSOUND-WAV  \
    --enable MOH-OPSOUND-ULAW  \
    --enable MOH-OPSOUND-ALAW  \
    --enable MOH-OPSOUND-GSM  \
    --enable MOH-OPSOUND-G729  \
    --enable MOH-OPSOUND-G722  \
    --enable MOH-OPSOUND-SLN16  \
    --enable MOH-OPSOUND-SIREN7  \
    --enable MOH-OPSOUND-SIREN14  \
    --enable EXTRA-SOUNDS-EN-WAV  \
    --enable EXTRA-SOUNDS-EN-ULAW  \
    --enable EXTRA-SOUNDS-EN-ALAW  \
    --enable EXTRA-SOUNDS-EN-GSM  \
    --enable EXTRA-SOUNDS-EN-G729  \
    --enable EXTRA-SOUNDS-EN-G722  \
    --enable app_macro \
    --enable agi-test.agi \
    --enable eagi-test \
    --enable eagi-sphinx-test \
    --enable jukebox.agi \
    menuselect.makeopts

# Install Asterisk
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

# Append custom configuration to asterisk config files
RUN sed -i 's/\[threadpool\]/;[threadpool]/' /etc/asterisk/stasis.conf && \
    for file in `ls ./custom/config/*conf`; do cat $file >> /etc/asterisk/`basename $file`; done

RUN service asterisk start

CMD sh custom/scripts/initiator.sh && /usr/sbin/asterisk -vvvvvvdddf