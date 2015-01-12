FROM ubuntu:14.10
MAINTAINER dpetek

ADD ./build_java.sh /judgy/build_java.sh
RUN chmod a+x /judgy/build_java.sh

RUN apt-get update
RUN apt-get install -y build-essential
RUN apt-get install -y openjdk-7-jdk

ENTRYPOINT ["/judgy/build_java.sh"]