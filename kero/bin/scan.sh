#!/bin/bash
export SONAR_TOKEN="7c5c7374b1d1f09333d9a038039b66739250d275"
sonar-scanner -Dsonar.organization=drosanda -Dsonar.projectKey=drosanda_seme-indonesian-address-provider-api  -Dsonar.sources=. -Dsonar.host.url=https://sonarcloud.io
