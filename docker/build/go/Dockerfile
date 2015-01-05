FROM golang:1.3.3
MAINTAINER dpetek

ADD ./build_go.sh /judgy/build_go.sh
RUN chmod a+x /judgy/build_go.sh

ENTRYPOINT ["/judgy/build_go.sh"]