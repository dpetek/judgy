FROM ubuntu:14.10
MAINTAINER dpetek

ADD ./run.sh /judgy/run.sh
RUN chmod a+x /judgy/run.sh

RUN apt-get update
RUN apt-get install -y build-essential
RUN apt-get install -y python2.7
RUN apt-get install -y php5

ENTRYPOINT ["judgy/run.sh"]
