#!/bin/bash

export LC_ALL=ru_RU.UTF-8 && find 1cbitrix/. -type f -exec sh -c 'np=`echo {}|iconv -f cp1252 -t cp850| iconv -f cp866`; mv "{}" "$np"' \;