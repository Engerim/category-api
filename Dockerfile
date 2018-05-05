FROM gcr.io/google-appengine/php

ARG BUILD_ENV=local
ENV WHITELIST_FUNCTIONS proc_open
ENV DOCUMENT_ROOT=/app/public

RUN /bin/bash /app/build-scripts/docker/custom.sh $BUILD_ENV
RUN rm -rf /app/build-scripts
