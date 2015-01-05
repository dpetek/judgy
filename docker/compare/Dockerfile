FROM ubuntu:13.10
MAINTAINER dpetek

ADD ./compare.sh /judgy/compare.sh
RUN chmod a+x /judgy/compare.sh

ENTRYPOINT ["/judgy/compare.sh"]