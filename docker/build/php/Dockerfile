FROM ubuntu:14.10
MAINTAINER dpetek

ADD ./build_php.sh /judgy/build_php.sh
RUN chmod a+x /judgy/build_php.sh

RUN apt-get update
RUN apt-get install -y build-essential
RUN apt-get install -y php5

ENTRYPOINT ["/judgy/build_php.sh"]