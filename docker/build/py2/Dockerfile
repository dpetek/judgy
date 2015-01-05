FROM ubuntu:14.10
MAINTAINER dpetek

ADD ./build_py2.sh /judgy/build_py2.sh
RUN chmod a+x /judgy/build_py2.sh

RUN apt-get update
RUN apt-get install -y build-essential
RUN apt-get install -y python2.7

ENTRYPOINT ["/judgy/build_py2.sh"]