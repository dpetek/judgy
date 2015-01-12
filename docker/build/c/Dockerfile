FROM ubuntu:14.10
MAINTAINER dpetek

ADD ./build_c.sh /judgy/build_c.sh
RUN chmod a+x /judgy/build_c.sh

RUN apt-get update
RUN apt-get install -y build-essential
RUN apt-get install -y gcc
RUN apt-get install -y g++

ENTRYPOINT ["/judgy/build_c.sh"]