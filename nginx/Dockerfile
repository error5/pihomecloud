FROM debian:buster
MAINTAINER Errors :D

# Install required packages
RUN apt-get update -q \
    && DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends \
      ca-certificates \
      openssh-server \
      wget \
      vim \
      nginx

RUN ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log

ADD pihomecloud.conf /etc/nginx/conf.d/pihomecloud.conf

# Expose web. ssl on haproxy
EXPOSE 80
EXPOSE 443

STOPSIGNAL SIGTERM

# Define default command.
CMD ["nginx", "-g", "daemon off;"]
