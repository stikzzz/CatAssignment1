FROM php:apache

RUN apt-get update && \
    apt-get install -y \
        default-jre \
        default-jdk && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*



ENV JAVA_HOME /usr/lib/jvm/default-java
ENV PATH $JAVA_HOME/bin:$PATH



RUN mkdir -p /var/www/html/src/uploads


RUN mkdir -p /var/www/html/src/converted


COPY . /var/www/html/



RUN chmod 777  /var/www/html/src/uploads
RUN chmod 777  /var/www/html/src/converted