#!/bin/bash

sonar-scanner -Dsonar.organization=drosanda -Dsonar.projectKey=drosanda_seme-indonesian-address-provider-api  -Dsonar.sources=. -Dsonar.host.url=https://sonarcloud.io
