FROM fluentd:v1.9.1-1.0

USER root
RUN fluent-gem install fluent-plugin-elasticsearch
COPY fluent.conf /fluentd/etc/fluent.conf
USER fluent