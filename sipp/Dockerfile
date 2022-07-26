FROM ubuntu:22.04

LABEL maintainer="Amir Fakour"

SHELL ["/bin/bash", "-c"]

WORKDIR /usr/src/SIPp

RUN DEBIAN_FRONTEND=noninteractive
RUN apt-get update -y
RUN apt-get install -y \
    vim \
    curl \
    wget \
    libssl-dev \
    libncurses5-dev \
    build-essential \
    pkg-config \
    dh-autoreconf \
    ncurses-dev \
    libpcap-dev \
    libsctp-dev \
    lksctp-tools \
    cmake

# Set Timezone
RUN echo ${TZ} > /etc/localtime && \
    apt-get install -y tzdata &&\
    dpkg-reconfigure --frontend noninteractive tzdata

# Download SIPp source
ENV SIPP_VERSION 3.6.1
RUN curl -sqLkv https://github.com/SIPp/sipp/releases/download/v${SIPP_VERSION}/sipp-${SIPP_VERSION}.tar.gz | tar xvzf - --strip-components=1

# Install SIPp
RUN ./build.sh --full && \
    make install
    
ENTRYPOINT ["sipp"]