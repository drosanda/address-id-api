#!/bin/bash
phpunit --configuration phpunit.xml --coverage-clover clover.xml --log-junit junit.xml --testsuite Dev
